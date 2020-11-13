<?php

class Sreport_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Suser_model', 'user');
    }

    public function getItem_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.mod_id,
                C.cat_id,
                C.parent_id,
                C.show_pic_outside,
                C.show_pic_inside,
                C.pic,
                C.pic_vertical,
                C.show_file,
                C.file,
                C.show_title,
                C.title_mn AS title,
                C.link_title_mn AS link_title,
                C.intro_text_mn AS intro_text,
                C.full_text_mn AS full_text,
                C.page_title_mn AS page_title,
                C.meta_key_mn AS meta_key,
                C.meta_desc_mn AS meta_desc,
                C.h1_text_mn AS h1_text,
                C.show_date,
                C.created_date,
                C.modified_date,
                C.is_active_date,
                C.show_author,
                C.author_id,
                C.created_user_id,
                C.modified_user_id,
                C.show_comment,
                C.comment_count_mn AS comment_count,
                C.show_click,
                C.click_mn AS click,
                C.click_real_mn AS click_real,
                C.is_active_mn AS active,
                C.order_num,
                C.show_social,
                C.param,
                C.price,
                C.price_sale,
                C.type,
                C.theme_layout_id,
                C.is_active_en,
                C.title_en,
                C.link_title_en,
                C.intro_text_en,
                C.full_text_en,
                C.page_title_en,
                C.meta_key_en,
                C.meta_desc_en,
                C.h1_text_en,
                C.comment_count_en,
                C.click_en,
                C.click_real_en,
                U.id AS url_id,
                U.url,
                C.tour_background_mn,
                C.tour_background_en,
                C.tour_days,
                C.tour_price,
                C.tour_included_service_mn,
                C.tour_included_service_en,
                C.tour_param_mn,
                C.tour_param_en,
                C.partner_id
            FROM `gaz_content` AS C
            LEFT JOIN `gaz_url` AS U ON C.mod_id = U.mod_id AND C.id = U.cont_id
            WHERE C.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function home_model($param = array('data' => array())) {
        $this->html = '';
        define('REPORT_IMG_PATH', 'assets/images/report/');

        $this->html .= '<div class="container">';

        $this->html .= '<div class="row"><div class="col-12">';
        $this->html .= '<h1 class="mb-2 font-weight-bold">' . $param['module']->title . ' тайлан</h1>';
        $this->html .= '</div></div>';
        
        
        $this->html .= '<div class="row">';
        
        if ($param['data']) {
            
            foreach ($param['data'] as $key => $row) {

                $this->html .= '<div class="col-sm-6 col-xl-4">';
                
                    $this->html .= '<div class="flip-container animated bounceInRight" ontouchstart="this.classList.toggle(\'hover\');" onclick="_reportItem({reportMenuId: ' . $row->id . ', reportModId: ' . $row->mod_id . '});">';
                        $this->html .= '<div class="flipper">';
                            $this->html .= '<div class="front">';
				$this->html .= '<div class="_icon"><span class="icon-bars-alt"></span></div>';
                                $this->html .= '<div class="_title">' . $row->title . '</div>';
                            $this->html .= '</div>';
                            $this->html .= '<div class="back">';
				$this->html .= '<div class="_icon"><span class="icon-bars-alt"></span></div>';
                                $this->html .= '<div class="_title">' . $row->title . '</div>';
                            $this->html .= '</div>';
                        $this->html .= '</div>';
                    $this->html .= '</div>';
                $this->html .= '</div>';
            }
        }
        
        $this->html .= '</div>';
        $this->html .= '</div>';
        
        return $this->html;
    }

}
