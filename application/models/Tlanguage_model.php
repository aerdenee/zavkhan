<?php

class Tlanguage_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function setThemeSession_model($param = array('code' => 'mn', 'path' => '')) {

        $tLang = array();
        $this->query = $this->db->query('
                SELECT 
                    L.id,
                    L.title,
                    L.is_active,
                    L.path,
                    L.code,
                    L.is_default
                FROM `gaz_language` AS L 
                WHERE L.is_active = 1 AND L.code = \'' . $param['code'] . '\'');

        $row = $this->query->row();

        if ($row->is_default == 1) {

            $param['path'] = (trim($param['path']) != '' ? '/' . trim($param['path'], '/') . '/' : '/');
            if (strlen($param['path']) == 1 and strlen($param['path']) > 0) {
                $param['path'] = trim($param['path'], '/');
            }
            array_push($tLang, array(
                'id' => $row->id,
                'title' => $row->title,
                'code' => $row->code,
                'folderPath' => $row->path,
                'path' => $param['path']
            ));
            
        } else {
            
            $param['path'] = $row->code . (trim($param['path']) != '' ? trim($param['path'], $row->code) : '/') . '/';

            array_push($tLang, array(
                'id' => $row->id,
                'title' => $row->title,
                'code' => $row->code,
                'folderPath' => $row->path,
                'path' => ltrim($param['path'], '/')
            ));
        }

        $this->session->set_userdata(array('themeLanguage'=>$tLang['0']));
        
    }

}
