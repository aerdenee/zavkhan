<?php

class Thome_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function layoutToContentTblId_model($param = array('layoutId' => 1, 'limit' => 3)) {

        if (isset($param['limit'])) {
            $param['limit'] = 3;
        }
        
        $this->query = $this->db->query('
            SELECT 
                layout_' . $this->session->langShortCode . ' AS layout
            FROM `gaz_layout`
            WHERE id = ' . $param['layoutId']);
        
        if ($this->query->num_rows() > 0) {
            $this->result = $this->query->result();
            $this->row = $this->result['0'];
            $this->data = explode(',', $this->row->layout);
            
            $this->query = $this->db->query('
                SELECT 
                    C.id,
                C.mod_id,
                C.cat_id,
                C.show_pic_outside,
                C.show_pic_inside,
                C.pic,
                C.pic_vertical,
                C.title_' . $this->session->langShortCode . ' AS title,
                C.link_title_' . $this->session->langShortCode . ' AS link_title,
                C.intro_text_' . $this->session->langShortCode . ' AS intro_text,
                C.full_text_' . $this->session->langShortCode . ' AS full_text,
                C.comment_count_' . $this->session->langShortCode . ' AS comment_count,
                C.click_' . $this->session->langShortCode . ' AS click,
                C.click_real_' . $this->session->langShortCode . ' AS click_real,
                C.page_title_' . $this->session->langShortCode . ' AS page_title,
                C.meta_key_' . $this->session->langShortCode . ' AS meta_key,
                C.meta_desc_' . $this->session->langShortCode . ' AS meta_desc,
                C.h1_text_' . $this->session->langShortCode . ' AS h1_text,
                C.is_active_date,
                C.show_author,
                C.show_comment,
                C.show_click,
                C.show_social,
                C.theme_layout_id,
                U.url,
                C.created_user_id,
                C.tour_background_' . $this->session->langShortCode . ' AS tour_background,
                C.tour_days,
                C.tour_price,
                C.tour_included_service_' . $this->session->langShortCode . ' AS tour_included_service,
                C.tour_param_' . $this->session->langShortCode . ' AS tour_param
                FROM `gaz_content` AS C
                INNER JOIN `gaz_url` AS U on C.mod_id = U.mod_id AND C.id = U.cont_id
                WHERE 1 = 1 AND C.id IN(' . $this->row->layout . ') AND C.is_active_' . $this->session->langShortCode . ' = 1 
                LIMIT 0, ' . $param['limit']);
            return $this->query->result();
        }
        return false;
    }
    
    public function latestFeedbackByHomeId_model() {

        $this->db->select(
                $this->db->dbprefix . 'module.path_item, ' .
                $this->db->dbprefix . 'feedback.* FROM ' . $this->db->dbprefix . 'feedback');
        $this->db->join($this->db->dbprefix . 'module', $this->db->dbprefix . 'feedback.mod_id = ' . $this->db->dbprefix . 'module.id', 'inner');
        $this->db->order_by($this->db->dbprefix . 'feedback.id', 'DESC');
        $this->db->where($this->db->dbprefix . 'feedback.is_active = 1');
        $this->db->limit(1, 0);
        $query = $this->db->get();

        $data = array();
        $html = '';
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return (Array) $result['0'];
        }
        return false;
    }
    
    public function layoutToContentTbl_model($param = array('layoutId' => 1, 'limit' => 3)) {

        $this->db->where('id', $param['layoutId']);
        $this->query = $this->db->get($this->db->dbprefix . 'layout');
        $this->result = $this->query->result();
        $this->r = array();
        if ($this->result) {
            $this->data = (Array) $this->result['0'];
            

            $this->query = $this->db->query('
                SELECT 
                    C.id,
                    C.mod_id,
                    C.cat_id,
                    C.show_pic_outside,
                    C.pic,
                    C.pic_vertical,
                    C.title_' . $this->session->langShortCode . ' AS title,
                    C.link_title_' . $this->session->langShortCode . ' AS link_title,
                    C.intro_text_' . $this->session->langShortCode . ' AS intro_text,
                    U.url
                FROM `gaz_content` AS C
                INNER JOIN `gaz_url` U on C.mod_id = U.mod_id AND C.id = U.cont_id 
                WHERE C.id IN(' . $this->data['layout_' . $this->session->langShortCode] . ') AND C.is_active_' . $this->session->langShortCode . ' = 1');
            $this->result = $this->query->result();
            
            if (count($this->result) > 0) {
                
                foreach ($this->result as $k => $row) {
                    
                        array_push($this->r, $row);

                    if ($k === $param['limit'])
                        break;
                }
            }
        }

        return $this->r;
    }

    public function latestFeedbackByHome_model() {

        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.mod_id,
                C.cat_id,
                C.pic,
                C.title_' . $this->session->langShortCode . ',
                C.fname_' . $this->session->langShortCode . ',
                C.lname_' . $this->session->langShortCode . ',
                C.intro_text_' . $this->session->langShortCode . ',
                U.url
            FROM `gaz_feedback` AS C
            INNER JOIN `gaz_url` U on C.mod_id = U.mod_id AND C.id = U.cont_id 
            WHERE C.id IN(' . $this->data['layout_' . $this->session->langShortCode] . ') AND C.is_active_' . $this->session->langShortCode . ' = 1');
        $this->result = $this->query->result();
        
        if (count($this->result) > 0) {
            $result = $this->result;
            return (Array) $result['0'];
        }
        return false;
    }

}

?>