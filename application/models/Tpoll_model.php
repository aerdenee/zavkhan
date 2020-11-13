<?php

class Tpoll_model extends CI_Model {

    function __construct() {
        /* Call the Model constructor */
        parent::__construct();
        $this->load->database();
    }

    public function pollBlockItem_model($param = array('pollId' => 0)) {
        $this->htmlRoot = '';
        $this->htmlRoot .= '<ul class="tabs" role="tablist">';
        $this->htmlRoot .= '<li class="nav-item active">';
        $this->htmlRoot .= '<a class="nav-link">Санал асуулга</a>';
        $this->htmlRoot .= '</li>';
        $this->htmlRoot .= '</ul>';
        $this->htmlRoot .= '<div class="side-news _theme-poll-block-item" id="poll-block-item">' . self::pollBlockItemData_model(array('pollId' => $param['pollId'])) . '</div>';
        return $this->htmlRoot;
    }

    public function pollBlockItemData_model($param = array('pollId' => 0)) {

        $this->queryString = '';
        $this->html = '';

        $this->query = $this->db->query('
            SELECT 
                P.id,
                P.mod_id,
                P.cat_id,
                P.partner_id,
                P.title_' . $this->session->langShortCode . ' AS title,
                P.pic_' . $this->session->langShortCode . ' AS pic,
                P.show_pic_outside_' . $this->session->langShortCode . ' AS show_pic_outside,
                P.show_pic_inside_' . $this->session->langShortCode . ' AS show_pic_inside
            FROM `gaz_poll` AS P
            LEFT JOIN `gaz_url` U on P.mod_id = U.mod_id AND P.id = U.cont_id 
            LEFT JOIN `gaz_user` US on P.author_id = US.id
            WHERE P.is_active_' . $this->session->langShortCode . ' = 1 ORDER BY P.order_num DESC LIMIT 0, 1');

        if ($this->query->num_rows() > 0) {
            $this->row = $this->query->row();

            $this->html .= '<div class="title">' . $this->row->title . '</div>';
            $this->pollDetail = self::pollBlockItemDetail_model(array('pollId' => $this->row->id));

            if ($this->pollDetail) {
                $this->pollDetailCount = self::pollBlockItemDetailCount_model(array('pollId' => $this->row->id));
                $this->html .= '<ul class="poll-block-item-li">';
                foreach ($this->pollDetail as $key => $rowDetail) {
                    $this->rowPrecent = (($rowDetail->result * 100) / $this->pollDetailCount->result);
                    $this->rowPrecent = explode('.', $this->rowPrecent);
                    $this->rowPrecent = $this->rowPrecent['0'];
                    $this->html .= '<li class="">';
                    $this->html .= '<label class="radio-inline">';
                    $this->html .= form_radio(array('name' => 'poll-item-' . $this->row->id, 'class' => 'radio'), $rowDetail->id, 0);
                    $this->html .= $rowDetail->title . ' ' . ($this->pollDetailCount->result > 0 ? ' (' . $this->rowPrecent . '%) ' : '') . '<br>';
                    $this->html .= '<div class="_result-background"><div class="_result-current" style="width:' . $this->rowPrecent . '%;"></div></div>';
                    $this->html .= '</label>';
                    $this->html .= '</li>';
                }
                $this->html .= '</ul>';
            }
            $this->html .= '<div class="_footer">';
            if ($this->pollDetailCount->result > 0) {
                $this->html .= '<div class="_poll-result-text">Нийт ' . $this->pollDetailCount->result . ' санал өгсөн байна.</div>';
            }

            $this->html .= form_button('send', 'Санал өгөх', 'class="btn btn-primary btn-md" onclick="_poll({pollId: ' . $this->row->id . ', elem: this});"', 'button');
            $this->html .= '</div>';
            $this->html .= '<div id="poll-item-message"></div>';
        }
        return $this->html;
    }

    public function pollBlockItemDetail_model($param = array('pollId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                PD.id,
                PD.mod_id,
                PD.poll_id,
                PD.title_' . $this->session->langShortCode . ' AS title,
                PD.type_id,
                PD.result
            FROM `gaz_poll_detail` AS PD
            WHERE PD.is_active_' . $this->session->langShortCode . ' = 1 AND poll_id = ' . $param['pollId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->result();
        }
        return false;
    }

    public function pollBlockItemDetailCount_model($param = array('pollId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                sum(PD.result) AS result
            FROM `gaz_poll_detail` AS PD
            WHERE PD.is_active_' . $this->session->langShortCode . ' = 1 AND poll_id = ' . $param['pollId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

    public function vote_model($param = array()) {
        $this->query = $this->db->query('
            SELECT 
                PD.result
            FROM `' . $this->db->dbprefix . 'poll_detail` AS PD
            WHERE PD.is_active_' . $this->session->langShortCode . ' = 1 AND id = ' . $param['pollDetailId']);
        $this->rowDetail = $this->query->row();
        $this->data = array('result' => ($this->rowDetail->result + 1));
        $this->db->where('id', $param['pollDetailId']);
        if ($this->db->update($this->db->dbprefix . 'poll_detail', $this->data)) {
            return array('status' => 'success', 'html' => self::pollBlockItemData_model(array('pollId' => $param['pollId'])));
        }
        return array('status' => 'error', 'html' => 'Та саяхан санал өгсөн байна');
    }
}
