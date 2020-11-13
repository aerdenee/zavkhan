<?php

class Surguduldirect_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getData_model($param = array('id' => 0)) {
        $this->query = $this->db->query('
            SELECT 
                id,
                title,
                is_active,
                order_num
            FROM `gaz_urgudul_direct`
            WHERE id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            $this->result = $this->query->result();
            return $this->result['0'];
        }
        return false;
    }

    public function controlUrgudulDirectDropDown_model($param = array('selectedId' => 0)) {
        $this->html = '';

        $this->query = $this->db->query('
            SELECT 
                id,
                title
            FROM `gaz_urgudul_direct`
            WHERE 
                is_active = 1');

        $this->result = $this->query->result();

        $this->html .= '<select name="urgudulDirectId" id="urgudulDirectId" class="select2">';
        $this->html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';

        if ($this->query->num_rows() > 0) {
            $this->result = $this->query->result();
            foreach ($this->result as $row) {
                $this->html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';
            }
        }

        $this->html .= '</select>';

        return $this->html;
    }

}

?>