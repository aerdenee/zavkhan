<?php

class ShrPeopleRelation_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function controlHrPeopleRelationOutDataDropdown_model($param = array('data' => array(), 'selectedId' => 0)) {

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

        $this->html .= '<select name="' . $param['name'] . '" id="' . $param['name'] . '" class="select2" ' . $this->string . '>';
        $this->html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';

        foreach ($param['data'] as $key => $row) {
            $this->html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';
        }

        $this->html .= '</select>';

        return $this->html;
    }
    public function controlHrPeopleRelationDropdown_model($param = array('selectedId' => 0)) {

        $this->html = $this->string = $name = '';

        if (isset($param['disabled']) and $param['disabled'] == true) {
            $this->string .= ' disabled="true"';
        }

        if (isset($param['required']) and $param['required'] == true) {
            $this->string .= ' required="true"';
        }

        if (isset($param['readonly']) and $param['readonly'] == true) {
            $this->string .= ' disabled="true"';
        }

        if (isset($param['name'])) {
            $name = $param['name'];
        } else {
            $name = 'relationId';
        }
        
        $this->query = $this->db->query('
            SELECT 
                id,
                title
            FROM `' . $this->db->dbprefix . 'hr_people_relation`
            WHERE is_active = 1
            ORDER BY order_num ASC');

        $this->html .= '<select name="' . $name . '" id="' . $name . '" class="select2" ' . $this->string . '>';
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
            FROM `' . $this->db->dbprefix . 'hr_people_relation`
            WHERE id = ' . $param['selectedId']);

            if ($this->query->num_rows() > 0) {
                return $this->query->row();
            }
        }

        return false;
    }

    public function getListData_model($param = array('selectedId' => 0)) {

        $query = $this->db->query('
            SELECT 
                id,
                title
            FROM `' . $this->db->dbprefix . 'hr_people_relation`
            WHERE is_active = 1
            ORDER BY order_num ASC');

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return false;
    }

}
