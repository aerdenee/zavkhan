<?php

class SuserAccessType_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function controlUserAccessTypeRadioButton_model($param = array('selectedId' => 0)) {

        $this->html = $this->string = '';

        if (isset($param['disabled']) and $param['disabled'] == 'true') {
            $this->string .= ' disabled="true"';
        }

        if (isset($param['required']) and $param['required'] == 'true') {
            $this->string .= ' required="true"';
        }

        if (isset($param['readonly']) and $param['readonly'] == 'true') {
            $this->string .= ' disabled="true"';
        }

        $this->query = $this->db->query('
            SELECT 
                id,
                title
            FROM `' . $this->db->dbprefix . 'user_access_type`
            WHERE 1 = 1
            ORDER BY order_num DESC');

        

        if ($this->query->num_rows() > 0) {
            $this->html .= form_hidden('accessTypeId', $param['selectedId']);
            foreach ($this->query->result() as $key => $row) {
                $this->html .= '<div class="form-check form-check-inline">';
                $this->html .= '<label class="form-check-label">';
                $this->html .= form_radio(array('name' => 'accessType', 'class' => 'radio', 'onclick' => '_inlineSetAccessTypeValue({elem: this, val: ' . $row->id . '})'), $row->id, ($row->id == $param['selectedId'] ? TRUE : FALSE), $this->string);
                $this->html .= $row->title;
                $this->html .= '</label>';
                $this->html .= '</div>';
            }
            
            $this->html .= '
                <script type="text/javascript">
                    function _inlineSetAccessTypeValue(param) {
                        $(\'input[name="accessTypeId"]\').val(param.val);
                    }
                </script>';
        }

        

        return $this->html;
    }

    public function controlUserAccessDropdown_model($param = array('selectedId' => 0)) {

        $html = $string = '';

        if (!isset($param['name'])) {
            $param['name'] = 'accessTypeId';
        }
        
        if (isset($param['disabled']) and $param['disabled'] == 'true') {
            $string .= ' disabled="true"';
        }

        if (isset($param['required']) and $param['required'] == 'true') {
            $string .= ' required="true"';
        }

        if (isset($param['readonly']) and $param['readonly'] == 'true') {
            $string .= ' disabled="true"';
        }

        $query = $this->db->query('
            SELECT 
                id,
                title
            FROM `' . $this->db->dbprefix . 'user_access_type`
            WHERE 1 = 1
            ORDER BY order_num DESC');

        

        $html .= '<select name="' . $param['name'] . '" id="' . $param['name'] . '" class="select2" ' . $string . '>';
        $html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';
        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {
                $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';
            }
        }

        $html .= '</select>';
        
        return $html;
    }
    
    
}
