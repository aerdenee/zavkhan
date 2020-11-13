<?php

class SreportMenu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getListData_model($param = array('modId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                icon,
                title,
                description,
                pic,
                order_num,
                is_active
            FROM `' . $this->db->dbprefix . 'report_menu`
            WHERE is_active = 1 AND mod_id = ' . $param['modId'] . ' 
            ORDER BY order_num ASC');

        if ($this->query->num_rows() > 0) {
            return $this->query->result();
        }
        
        return false;
        
    }

    public function getItemData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                title,
                description,
                pic,
                order_num,
                is_active
            FROM `' . $this->db->dbprefix . 'report_menu`
            WHERE is_active = 1 AND id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }


        return false;
    }

}
