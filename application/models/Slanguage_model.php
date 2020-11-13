<?php

class Slanguage_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    
    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
                SELECT 
                    L.id,
                    L.title,
                    L.is_active,
                    L.path,
                    L.code,
                    L.is_default
                FROM `gaz_language` AS L 
                WHERE L.is_active = 1 AND L.id = \'' . $param['selectedId'] . '\'');

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }
    
    public function setThemeSession_model($param = array('code' => 'mn', 'path' => '')) {

        $this->query = $this->db->query('
                SELECT 
                    L.id,
                    L.title,
                    L.is_active,
                    L.path,
                    L.code,
                    L.is_default
                FROM `gaz_language` AS L 
                WHERE L.is_active = 1 AND L.id = \'' . $param['id'] . '\'');

        $row = $this->query->row();

        return $this->session->set_userdata(array(
            'adminLangId' => $row->id,
            'adminLangCode' => $row->code,
            'adminLangTitle' => $row->title,
            'folderPath' => $row->path));

    }

    public function controlSystemLangLoginDropdown_model($param = array('selectedId' => 0)) {
        $html = '';

        $query = $this->db->query('
                SELECT 
                    L.id,
                    L.title,
                    L.path,
                    L.code,
                    L.is_default
                FROM `gaz_language` AS L
                WHERE L.is_active = 1');

        if ($query->num_rows() > 1 AND IS_MULTI_LANGUAGE) {

            $html .= '<div class="form-group form-group-feedback form-group-feedback-left">';
            $html .= '<select class="form-control select2" name="adminLangId" id="adminLangId">';

            $html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '>' . ' - Сонгох - </option>';

            foreach ($query->result() as $row) {
                $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>&nbsp; &nbsp;' . $row->title . '</option>';
            }

            $html .= '</select>';
            $html .= '</div>';
        } else {

            $query = $this->db->query('
                SELECT 
                    L.id,
                    L.title,
                    L.path,
                    L.code
                FROM `gaz_language` AS L
                WHERE L.is_active = 1 AND L.is_default = 1');
            $row = $query->row();
            $html .= '<input type="hidden" name="adminLangId" id="adminLangId" value="' . $row->id . '">';
        }

        return $html;
    }

}
