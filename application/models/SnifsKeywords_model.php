<?php

class SnifsKeywords_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
    }

    public function crimeValueLists_model($param = array('modId' => 0, 'departmentId' => 0, 'keyword' => '')) {

        $data = array();
        $query = $this->db->query('
            SELECT 
                NK.keyword
            FROM `gaz_nifs_keywords` AS NK
            WHERE NK.mod_id = ' . $param['modId'] . ' AND NK.department_id = ' . $param['departmentId'] . ' AND NK.keyword != \'\'
            ORDER BY NK.keyword ASC');



        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $row) {
                array_push($data, $row->keyword);
            }
            return $data;
        }
        return false;
    }

    public function insert_model($param = array('getUID' => 0)) {

        if (trim($param['keyword'], '') != '') {
            $query = $this->db->query('
            SELECT 
                NK.keyword
            FROM `gaz_nifs_keywords` AS NK
            WHERE NK.mod_id = ' . $param['modId'] . ' AND NK.department_id = ' . $param['departmentId'] . ' AND LOWER(NK.keyword) = LOWER(\'' . $param['keyword'] . '\')');

            if ($query->num_rows() == 0) {
                $data = array(
                    array(
                        'id' => getUID('nifs_keywords'),
                        'mod_id' => $param['modId'],
                        'department_id' => $param['departmentId'],
                        'keyword' => $param['keyword'],
                        'created_date' => date('Y-m-d H:i:s'),
                        'created_user_id' => $this->session->adminUserId
                    )
                );

                if ($this->db->insert_batch($this->db->dbprefix . 'nifs_keywords', $data)) {
                    return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
                }
            }
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function agentNameLists_model($param = array('modId' => 0, 'departmentId' => 0, 'keyword' => '')) {

        $data = array();
        $query = $this->db->query('
            SELECT 
                NK.keyword
            FROM `gaz_nifs_keywords_agent_name` AS NK
            WHERE NK.mod_id = ' . $param['modId'] . ' AND NK.department_id = ' . $param['departmentId'] . ' AND NK.keyword != \'\'
            ORDER BY NK.keyword ASC');



        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $row) {
                array_push($data, $row->keyword);
            }
            return $data;
        }
        return false;
    }

    public function agentNameInsert_model($param = array('getUID' => 0)) {

        if (trim($param['keyword'], '') != '') {
            $query = $this->db->query('
            SELECT 
                NK.keyword
            FROM `gaz_nifs_keywords_agent_name` AS NK
            WHERE NK.mod_id = ' . $param['modId'] . ' AND NK.department_id = ' . $param['departmentId'] . ' AND LOWER(NK.keyword) = LOWER(\'' . $param['keyword'] . '\')');

            if ($query->num_rows() == 0) {
                $data = array(
                    array(
                        'id' => getUID('nifs_keywords_agent_name'),
                        'mod_id' => $param['modId'],
                        'department_id' => $param['departmentId'],
                        'keyword' => $param['keyword'],
                        'created_date' => date('Y-m-d H:i:s'),
                        'created_user_id' => $this->session->adminUserId
                    )
                );

                if ($this->db->insert_batch($this->db->dbprefix . 'nifs_keywords_agent_name', $data)) {
                    return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
                }
            }
        }


        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

}
