<?php

class SnifsIsMixx_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function controlNifsIsMixxDropdown_model($param = array('selectedId' => 0)) {

        $this->html = $this->string = '';

        $this->data = array(
            array('id' => 1, 'title' => 'Бүрэлдэхүүнтэй'),
            array('id' => 2, 'title' => 'Бүрэлдэхүүнгүй')
        );

        $this->html .= '<select name="isMixx" id="isMixx" class="select2">';
        $this->html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Бүгд - </option>';

        foreach ($this->data as $key => $row) {
            $this->html .= '<option value="' . $row['id'] . '" ' . ($param['selectedId'] == $row['id'] ? 'selected="selected"' : '') . '>' . $row['title'] . '</option>';
        }

        $this->html .= '</select>';

        return $this->html;
    }

    public function controlNifsIsMixxCheckBox_model($param = array()) {
        $html = $string = '';
        $data = array('id' => 'mixCheckBox', 'name' => 'mixCheckBox', 'class' => 'radio', 'onclick' => '_nifsIsMixx({elem:this, initControlHtml: \'' . $param['initControlHtml'] . '\', addButtonName: \'' . $param['addButtonName'] . '\'});');
        if (isset($param['disabled']) and $param['disabled'] == 'true') {
            $data['disabled'] = true;
        }

        if (isset($param['required']) and $param['required'] == 'true') {
            $data['required'] = true;
        }

        if (isset($param['readonly']) and $param['readonly'] == 'true') {
            $data['disabled'] = true;
        }

        $html .= '<label class="col-form-label">';
        $html .= form_hidden('isMixx', $param['isMixx']);
        $html .= form_checkbox($data, 1, ($param['isMixx'] == '1' ? true : false));
        $html .= 'Бүрэлдэхүүнтэй эсэх ';
        $html .= '</label>';
        return $html;
    }

}
