<?php

class Sdonate_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('string');
        $this->load->helper('date');
        $this->load->library('image_lib');
    }

    public function contentList_model($modId, $catId) {
        if ($catId != '') {
            $this->db->where('catid', $catId);
        }
        $this->db->where('modid', $modId);
        $this->db->where('lang', $this->session->lang);
        $this->db->order_by('createdate', 'DESC');
        $query = $this->db->get($this->db->dbprefix . 'donate');
        $result = $query->result();
        $data = array();
        if (count($result) > 0) {
            foreach ($result as $key => $value) {
                $row = (array) $value;
                array_push($data, $row);
            }
        }
        return $data;
    }

}

?>