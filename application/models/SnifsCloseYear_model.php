<?php

class SnifsCloseYear_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function closeYear_model() {

        $this->query = $this->db->query('
                SELECT 
                    id,
                    title,
                    close_date
                FROM `gaz_nifs_close_year`
                WHERE close_date LIKE \'' . date('Y') . '%\'');
        if ($this->query->num_rows() > 0) {
            $row = $this->query->row();
            if (strtotime(date('Y-m-d')) <= strtotime($row->close_date)) {
                return date('Y');
            }
        }
        return (intval(date('Y')) + 1);
    }

    public function getCloseDate_model($param = array()) {

        $this->query = $this->db->query('
                SELECT 
                    id,
                    title,
                    close_date
                FROM `gaz_nifs_close_year`
                WHERE close_year = \'' . $param['closeYear'] . '\'');
        if ($this->query->num_rows() > 0) {
            $row = $this->query->row();

            return $row->close_date;
        }
        return false;
    }

    public function getCloseYearDate_model($param = array()) {

        $this->query = $this->db->query('
                SELECT 
                    id,
                    title,
                    close_date
                FROM `gaz_nifs_close_year`
                WHERE close_year = \'' . $param['year'] . '\'');
        if ($this->query->num_rows() > 0) {
            $row = $this->query->row();

            return $row->close_date;
        }
        return false;
    }

}
