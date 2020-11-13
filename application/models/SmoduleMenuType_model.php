<?php

class SmoduleMenuType_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function controlModuleMenuTypeRadioButton_model($param = array('selectedId' => 0)) {

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
            FROM `' . $this->db->dbprefix . 'module_menu_type`
            WHERE 1 = 1
            ORDER BY order_num ASC');

        

        if ($this->query->num_rows() > 0) {
            $this->html .= form_hidden('menuTypeId', $param['selectedId']);
            foreach ($this->query->result() as $key => $row) {
                $this->html .= '<div class="form-check form-check-inline">';
                $this->html .= '<label class="form-check-label">';
                $this->html .= form_radio(array('name' => 'menuType', 'class' => 'radio', 'onclick' => '_inlineSetMenuTypeValue({elem: this, val: ' . $row->id . '})'), $row->id, ($row->id == $param['selectedId'] ? TRUE : FALSE));
                $this->html .= '<span style="font-size:11px;">' . $row->title . '</span>';
                $this->html .= '</label>';
                $this->html .= '</div>';
            }
            
        }

        

        return $this->html;
    }

}
