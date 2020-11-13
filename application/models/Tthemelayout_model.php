<?php

class Tthemelayout_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getTheme_model($param = array('modId' => 0, 'catId' => 0)) {
        
        if ($param['id'] != null) {
            $this->query = $this->db->query('
            SELECT 
                theme
            FROM `gaz_theme_layout`
            WHERE id = ' . $param['id']);
            if ($this->query->num_rows()) {
                $this->row = $this->query->row();
                return $this->row->theme;
            } else {
                if ($param['isCategory'] == 1) {
                    return 'lists';
                }
            }
            return 'itemRightSidebar';
        }
        return false;
    }

}
