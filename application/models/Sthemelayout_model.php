<?php

class Sthemelayout_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function controlThemeLayoutRadio_model($param = array('themeLayoutId' => 0, 'modId' => 0, 'isCategory' => 0)) {

        $this->html = '';
        $this->query = $this->db->query('
            SELECT 
                id,
                title
            FROM `' . $this->db->dbprefix . 'theme_layout`
            WHERE is_active = 1 AND mod_id = ' . $param['modId'] . ' AND is_category = ' . $param['isCategory']);

        if ($this->query->num_rows() == 0) {
            $this->data = array(
                array(
                    'id' => getUID('theme_layout'),
                    'title' => 'Жагсаалт',
                    'is_active' => 1,
                    'mod_id' => $param['modId'],
                    'is_category' => $param['isCategory'],
                    'theme' => 'list'
                )
            );
            $this->db->insert_batch($this->db->dbprefix . 'theme_layout', $this->data);
        }

        if ($this->query->num_rows() > 0) {



            foreach ($this->query->result() as $key => $row) {
                $this->html .= '<div class="form-check form-check-inline">';
                if (intval($param['themeLayoutId']) == intval($row->id)) {
                    $this->html .= '<input type="hidden" name="themeLayoutId" id="themeLayoutId" value="' . $row->id . '">';
                    $this->html .= '<label class="form-check-label"><input type="radio" name="layout" value="' . $row->id . '" checked="checked" class="radio" onclick="_setThemeLayout(this);">' . $row->title . '</label>';
                } else if (empty($key)) {
                    $this->html .= '<input type="hidden" name="themeLayoutId" id="themeLayoutId" value="' . $row->id . '">';
                    $this->html .= '<label class="form-check-label"><input type="radio" name="layout" value="' . $row->id . '" checked="checked" class="radio" onclick="_setThemeLayout(this);">' . $row->title . '</label>';
                } else {
                    $this->html .= '<label class="form-check-label"><input type="radio" name="layout" value="' . $row->id . '" class="radio" onclick="_setThemeLayout(this);">' . $row->title . '</label>';
                }
                $this->html .= '</div>';
            }

            $this->html .= '
                <script type="text/javascript">
                function _setThemeLayout(elem) {
                    var _this = $(elem);
                    $(\'input[name="themeLayoutId"]\').val(_this.val());
                }
                </script>';
        }
        return $this->html;
    }

}

?>
