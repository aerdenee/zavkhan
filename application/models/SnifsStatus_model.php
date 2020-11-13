<?php

class SnifsStatus_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function nifsStatusData_model() {
        return json_decode(json_encode(array(
            array('id' => 1, 'title' => 'Хэвийн шинжилгээ (хугацаандаа хаагдсан)'),
            array('id' => 2, 'title' => 'Хэвийн шинжилгээ (гар дээр байгаа)'),
            array('id' => 3, 'title' => 'Хугацаа хэтэрсэн (хаагдсан шинжилгээ)'),
            array('id' => 4, 'title' => 'Хугацаа хэтэрсэн (хаагдаагүй шинжилгээ)'),
            array('id' => 5, 'title' => 'Тогтоолын хугацаа дуусаж ирсэн'),
            array('id' => 6, 'title' => 'Хаагдаагүй шинжилгээ /Хэвийн, хугацаа хэтэрсэн/'))));
    }
    
    public function controlNifsStatusDropdown_model($param = array('selectedId' => 0)) {

        $html = '';

        $html .= '<select name="statusId" id="statusId" class="select2">';
        $html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Бүгд - </option>';

        foreach ($this->nifsStatusData_model() as $row) {
            $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';
        }

        $html .= '</select>';

        return $html;
    }
    
    public function getData_model($param = array('selectedId' => 0)) {

        foreach ($this->nifsStatusData_model() as $row) {
            if ($param['selectedId'] == $row->id) {
                return $row;
                exit();
            }
        }
        return false;
    }

}
