<?php

class SclickCounter_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
    }
    public function clickCounter_model($param = array()) {

        $this->query = $this->db->query('
            SELECT 
                id,
                click,
                click_real
            FROM `' . $this->db->dbprefix . $param['table'] . '`
            WHERE id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            $row = $this->query->row();

            $this->data = array(
                'click' => ($row->click + 1),
                'click_real' => ($row->click + rand(1,2)));

            $this->db->where('id', $param['selectedId']);
            $this->db->update($this->db->dbprefix . $param['table'], $this->data);
        }
        
    }

}
