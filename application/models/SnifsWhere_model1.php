<?php

class SnifsWhere_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function controlWhereDropdown_model($param = array('selectedId' => 0)) {

        $this->html = $this->string = '';

        if (isset($param['disabled']) and $param['disabled'] == true) {
            $this->string .= ' disabled="true"';
        }

        if (isset($param['required']) and $param['required'] == true) {
            $this->string .= ' required="true"';
        }

        if (isset($param['readonly']) and $param['readonly'] == true) {
            $this->string .= ' disabled="true"';
        }

        $this->query = $this->db->query('
            SELECT 
                id,
                title
            FROM `' . $this->db->dbprefix . 'nifs_where`
            WHERE is_active = 1
            ORDER BY order_num DESC');

        $this->html .= '<select name="whereId" id="whereId" class="select2" ' . $this->string . '>';
        $this->html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';
        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $key => $row) {
                $this->html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';
            }
        }

        $this->html .= '</select>';

        return $this->html;
    }

    public function getData_model($param = array('selectedId' => 0)) {

        if ($param['selectedId'] != NULL and $param['selectedId'] != 0) {

            $this->query = $this->db->query('
            SELECT 
                id,
                title,
                order_num,
                is_active
            FROM `' . $this->db->dbprefix . 'nifs_where`
            WHERE id = ' . $param['selectedId']);

            if ($this->query->num_rows() > 0) {
                return $this->query->row();
            }
        }

        return false;
    }
    
}
