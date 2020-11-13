<?php

class Troute_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
    }

    public function getUrlInfo_model($param = array()) {
        
        if (isset($param['url']) and trim($param['url'], ' ') != '') {

            $this->query = $this->db->query('
                SELECT 
                    mod_id,
                    cont_id,
                    url
                FROM `gaz_url`
                WHERE lower(url) = \'' . strtolower($param['url']) . '\'');

            if ($this->query->num_rows() > 0) {
                return $this->query->row();
            }
        } else {

            return json_decode(json_encode(array('mod_id' => 0, 'cont_id' => 0)));
        }
    }
    
    public function getUrl_model($param = array()) {

        if (isset($param['modId']) and isset($param['contId'])) {

            $this->query = $this->db->query('
                SELECT 
                    url
                FROM `gaz_url`
                WHERE mod_id = \'' . $param['modId'] . '\' and cont_id = \'' . $param['contId'] . '\'');

            if ($this->query->num_rows() > 0) {
                $row = $this->query->row();
                return $row->url . '/';
            }
        }
        return '';
    }

    public function getMenuInfo_model($param = array()) {
        
        if (isset($param['contId']) and trim($param['contId'], ' ') != '') {

            $this->query = $this->db->query('
                SELECT 
                    M.id,
                    M.mod_id,
                    M.cat_id,
                    M.cont_id,
                    M.department_id
                FROM `gaz_menu` AS M
                WHERE M.cont_id = \'' . ($param['contId'] != 0 ? $param['contId'] : 1) . '\'');

            if ($this->query->num_rows() > 0) {
                return $this->query->row();
            }
        } else if (isset($param['catId']) and trim($param['catId'], ' ') != '') {

            $this->query = $this->db->query('
                SELECT 
                    M.id,
                    M.mod_id,
                    M.cat_id,
                    M.cont_id,
                    M.department_id
                FROM `gaz_menu` AS M
                WHERE M.cat_id = \'' . $param['catId'] . '\'');

            if ($this->query->num_rows() > 0) {
                return $this->query->row();
            }
        } else if (isset($param['id']) and trim($param['id'], ' ') != '') {

            $this->query = $this->db->query('
                SELECT 
                    M.id,
                    M.mod_id,
                    M.cat_id,
                    M.cont_id,
                    M.department_id
                FROM `gaz_menu` AS M
                WHERE M.id = \'' . $param['id'] . '\'');

            if ($this->query->num_rows() > 0) {
                return $this->query->row();
            }
        }  else {

            return json_decode(json_encode(array('mod_id' => 0, 'cont_id' => 0)));
        }
    }

}
