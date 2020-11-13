<?php

class Tgeneral_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
    }

    public function getGeneralInfo_model($param = array('langId' => 1)) {
        $data = array();
        $this->query = $this->db->query('
                SELECT 
                    G.general_key,
                    G.general_value
                FROM `gaz_general` AS G WHERE G.lang_id = ' . $param['langId']);

        if ($this->query->num_rows() > 0) {
            foreach ($this->query->result() as $key => $row) {
                $data[$row->general_key] = $row->general_value;
            }
        }
        
        return $data;
    }

}
