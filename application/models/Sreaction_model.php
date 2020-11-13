<?php

class Sreaction_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
    }

    public function lists_model($param = array('modId' => 0, 'contId' => 0)) {

        $html = '';

        $query = $this->db->query('
            SELECT 
                MR.id,
                MR.title,
                MR.pic,
                RD.reaction_count
            FROM `' . $this->db->dbprefix . 'master_reaction` AS MR
            LEFT JOIN `' . $this->db->dbprefix . 'reaction_detail` AS RD ON MR.id = RD.reaction_id AND RD.mod_id = ' . $param['modId'] . ' AND RD.cont_id = ' . $param['contId'] . '
            WHERE MR.is_active =1
            ORDER BY RD.reaction_count DESC');

        if ($query->num_rows() > 0) {

            $html .= '<div class="ikon_reaction_container">';
            $html .= '<div class="ir_container">';
            $html .= '<div class="reaction_header">Энэ мэдээнд өгөх таны хариулал?</div>';
            $html .= '<div class="reaction_container">';
            foreach ($query->result() as $row) {
                $html .= '<div class="reaction">
          <div class="graph" style="height: 80px">
            <div class="graph_inside" style="height: 80px;">
              <div class="value" style="display: block;">' . $row->reaction_count . '</div>
            </div>
          </div>
          <div id="vote1" class="vote" rid="1" rtext="' . $row->title . '" ricon="' . $row->pic . '" path="/reaction/84179">
            <img src="' . $row->pic . '" width="32">
            <div id="indicator_1" class="indicator ">
            </div>
          </div>
          
        </div>';
            }
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            return $html;
        }
        return false;
    }

    public function insert_model($param = array()) {
        $this->data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'start_date' => $this->input->post('startDate'),
                'end_date' => $this->input->post('endDate'),
                'price' => $this->input->post('price'),
                'teacher_id' => $this->input->post('teacherId'),
                'intro_text' => $this->input->post('introText'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => '0000-00-00 00:00:00',
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'is_active' => $this->input->post('isActive'),
                'order_num' => $this->input->post('orderNum')
            )
        );
        $result = $this->db->insert_batch($this->db->dbprefix . 'class', $this->data);

        if ($result) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

}
