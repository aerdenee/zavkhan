<?php

class Tlayout_model extends CI_Model {

    function __construct() {
        /* Call the Model constructor */
        parent::__construct();
        $this->load->model('Tnews_model', 'tnews');

    }

    public function layoutData_model($param = array('layoutId' => 0, 'limit' => 0)) {

        $data = array();

        if (!isset($param['limit'])) {
            $param['limit'] = 1;
        }
        
        $query = $this->db->query('
            SELECT 
                layout
            FROM `gaz_layout`
            WHERE id = ' . $param['layoutId']);

        if ($query->num_rows() > 0) {

            $row = $query->row();

            $query = $this->db->query('
            SELECT 
                C.id,
                C.mod_id,
                C.cat_id,
                C.parent_id,
                C.show_pic_outside,
                C.show_pic_inside,
                C.pic,
                C.show_title,
                C.title,
                C.link_title,
                C.intro_text,
                C.full_text,
                C.page_title,
                C.meta_key,
                C.meta_desc,
                C.h1_text,
                C.show_date,
                C.created_date,
                C.modified_date,
                C.is_active_date,
                C.show_people,
                C.people_id,
                C.created_user_id,
                C.modified_user_id,
                C.show_comment,
                C.comment_count,
                C.show_click,
                C.click,
                C.click_real,
                C.is_active,
                C.order_num,
                C.show_social,
                C.param,
                C.lang_id,
                C.theme_layout_id,
                IF(C.theme_layout_id > 0, TL.theme, \'item\') AS theme,
                C.partner_id,
                U.id AS url_id,
                U.url,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name,
                CAT.title AS cat_title
            FROM `gaz_content` AS C
            LEFT JOIN `gaz_url` U on C.mod_id = U.mod_id AND C.id = U.cont_id 
            LEFT JOIN `gaz_hr_people` HP ON C.people_id = HP.id
            LEFT JOIN `gaz_theme_layout` TL ON C.theme_layout_id = TL.id
            INNER JOIN `gaz_category` AS CAT ON C.mod_id = CAT.mod_id AND C.cat_id = CAT.id
            WHERE C.lang_id = ' . $this->session->userdata['themeLanguage']['id'] . ' AND C.id IN(' . $row->layout . ') AND C.is_active = 1
            ORDER BY C.is_active_date DESC
            LIMIT 0, ' . $param['limit']);

            if ($query->num_rows() > 0) {
                foreach ($query->result() as $key => $row) {
                    $row->media = $this->tnews->getItemMedia_model(array('modId' => $row->mod_id, 'contId' => $row->id));
                    array_push($data, $row);
                }
            }

            return $data;
        }

        return false;
    }

}
