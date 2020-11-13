<?php

class ShrPeopleSex_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
    }

    public function getData_model($param = array('selectedId' => 0)) {
        foreach ($this->sexData_model() as $key => $row) {
            if ($param['selectedId'] == $row->id) {
                return $row;
            }
        }
        exit();
    }
    public function sexData_model() {

        $data = array(
            array('id' => 1, 'title' => ' Эрэгтэй '),
            array('id' => 2, 'title' => ' Эмэгтэй ')
        );
        return json_decode(json_encode($data));
    }

    public function controlSexListDropdown_model($param = array('selectedId' => 0)) {

        $html = $string = $class = $name = '';
        
        if (isset($param['disabled']) and $param['disabled'] == 'true') {
            $string .= ' disabled="true"';
        }

        if (isset($param['required']) and $param['required'] == 'true') {
            $string .= ' required="true"';
            $class .= ' required';
        }

        if (isset($param['readonly']) and $param['readonly'] == 'true') {
            $string .= ' disabled="true"';
        }


        $html .= '<select name="sex" id="sex" class="select2" ' . $string . '>';

        $html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';

        foreach ($this->sexData_model() as $key => $row) {
            $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';
        }

        $html .= '</select>';

        return $html;
    }

}
