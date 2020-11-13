<?php

class Tmap_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
    }

    public function getCoordinateInfo_model($param = array()) {
        
        $this->data = array();
        $this->query = $this->db->query('
                SELECT 
                    M.id,
                    M.mod_id,
                    M.cat_id,
                    M.cont_id,
                    M.address,
                    M.is_active,
                    M.map_type_id,
                    M.param,
                    M.draw_mode,
                    M.lat,
                    M.lng
                FROM `gaz_map` AS M
                WHERE M.mod_id = ' . $param['modId'] . ' AND M.cont_id = ' . $param['contId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->result();
        }
        
        return false;
    }

}
