<?php
class Tcontact_model extends CI_Model {

    function __construct() 
    {
      /* Call the Model constructor */
      parent::__construct();

    }
    
    public function getItem_model($param = array('selectedId' => 0)) {
        
        $this->queryString = '';
        
        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.mod_id,
                C.cat_id,
                C.title,
                C.link_title,
                C.show_title,
                C.show_fax,
                C.fax,
                C.show_phone,
                C.phone,
                C.show_mobile,
                C.mobile,
                C.show_address,
                C.address,
                C.show_intro_text,
                C.intro_text,
                C.show_pic_outside,
                C.show_pic_inside,
                C.pic,
                C.show_email,
                C.email,
                C.show_post_address,
                C.post_address,
                C.h1_text,
                C.page_title,
                C.meta_key,
                C.meta_desc,
                C.show_click,
                C.click,
                C.click_real,
                C.is_active,
                C.order_num,
                C.email_to,
                C.parent_id,
                C.created_date,
                C.modified_date,
                C.created_user_id,
                C.modified_user_id,
                C.show_social,
                C.social,
                C.param,
                C.department_id,
                C.lang_id,
                C.theme_layout_id,
                IF(C.theme_layout_id > 0, TL.theme, \'item\') AS theme,
                C.partner_id,
                U.id AS url_id,
                U.url,
                CAT.title AS cat_title,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name
            FROM `gaz_contact` AS C
            LEFT JOIN `gaz_url` U on C.mod_id = U.mod_id AND C.id = U.cont_id 
            LEFT JOIN `gaz_hr_people` HP ON C.people_id = HP.id
            LEFT JOIN `gaz_theme_layout` TL ON C.theme_layout_id = TL.id
            INNER JOIN `gaz_category` AS CAT ON C.mod_id = CAT.mod_id AND C.cat_id = CAT.id
            WHERE C.lang_id = ' . $this->session->userdata['themeLanguage']['id'] . ' AND C.id = ' . $param['selectedId'] . ' AND C.is_active = 1');
        
        if ($this->query->result() > 0) {
            
            return $this->query->row();
            
        }
        
        return false;
        
    }
    
    public function getData_model($param = array('selectedId' => 0)) {
        
        $this->queryString = '';
        
        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.mod_id,
                C.cat_id,
                C.title,
                C.link_title,
                C.show_title,
                C.show_fax,
                C.fax,
                C.show_phone,
                C.phone,
                C.show_mobile,
                C.mobile,
                C.show_address,
                C.address,
                C.show_intro_text,
                C.intro_text,
                C.show_pic_outside,
                C.show_pic_inside,
                C.pic,
                C.show_email,
                C.email,
                C.show_post_address,
                C.post_address,
                C.h1_text,
                C.page_title,
                C.meta_key,
                C.meta_desc,
                C.show_click,
                C.click,
                C.click_real,
                C.is_active,
                C.order_num,
                C.email_to,
                C.parent_id,
                C.created_date,
                C.modified_date,
                C.created_user_id,
                C.modified_user_id,
                C.show_social,
                C.social,
                C.param,
                C.department_id,
                C.lang_id,
                C.theme_layout_id,
                IF(C.theme_layout_id > 0, TL.theme, \'item\') AS theme,
                C.partner_id,
                U.id AS url_id,
                U.url,
                CAT.title AS cat_title
            FROM `gaz_contact` AS C
            LEFT JOIN `gaz_url` U on C.mod_id = U.mod_id AND C.id = U.cont_id 
            LEFT JOIN `gaz_category` CAT ON C.mod_id = CAT.mod_id AND C.cat_id = CAT.id 
            LEFT JOIN `gaz_theme_layout` TL ON C.theme_layout_id = TL.id
            WHERE C.lang_id = ' . $this->session->userdata['themeLanguage']['id'] . ' AND C.id = ' . $param['selectedId'] . ' AND C.is_active = 1');
        
        if ($this->query->result() > 0) {
            
            return $this->query->row();
            
        }
        
        return false;
        
    }
    
}