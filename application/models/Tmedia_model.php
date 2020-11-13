<?php

class Tmedia_model extends CI_Model {

    function __construct() {
        /* Call the Model constructor */
        parent::__construct();
    }

    public function banner_model($param = array()) {
        $html = '';
        $query = $this->db->query('
            SELECT 
                M.id,
                M.title,
                M.link_title,
                M.url,
                M.target,
                M.description,
                M.pic,
                M.attach_file,
                M.custom,
                M.duration                
            FROM `gaz_media` AS M
            WHERE M.lang_id = ' . $this->session->themeLanguage['id'] . ' AND M.is_active = 1 AND M.cat_id = ' . $param['catId'] . '
            ORDER BY M.order_num ASC');
        
        if ($query->num_rows() > 0) {
            
            $html .= '<div class="owl-carousel owl-banner owl-theme ' . $param['position'] . '">';
            
            foreach ($query->result() as $key => $row) {

                $html .= '<div class="item animated bounceInDown">';
                    $html .= '<img src="' . UPLOADS_MEDIA_PATH . $row->pic . '">';
                    $html .= '<a href="' . $row->url . '" target="' . $row->target . '" onclick=""></a>';
                $html .= '</div>';
                
            }
            $html .= '</div>';

        }
        
        return $html;
    }
    
    public function owlSlider_model($param = array()) {
        $html = '';
        $query = $this->db->query('
            SELECT 
                M.id,
                M.title,
                M.link_title,
                M.url,
                M.target,
                M.description,
                M.pic,
                M.attach_file,
                M.custom,
                M.duration                
            FROM `gaz_media` AS M 
            WHERE M.lang_id = ' . $this->session->themeLanguage['id'] . ' AND M.is_active = 1 AND M.cat_id = ' . $param['catId'] . '
            ORDER BY M.order_num ASC');
        
        if ($query->num_rows() > 0) {
            
            $html .= '<div id="owl-main" class="owl-carousel owl-main-slider owl-theme">';
            foreach ($query->result() as $key => $row) {

                $html .= '<div class="item animated bounceInDown" style="background-image: url(' . UPLOADS_MEDIA_PATH . $row->pic . ');">';
                    $html .= '<div class="container">';
                        $html .= '<div class="caption _theme-about-cover-information animated bounceInDown">';
								
                            $html .= '<h2 class="fadeInDown-1 light-color animated bounceInDown"><a href="' . $row->url . '">' . $row->title . '</a></h2>';
                            $html .= '<p class="fadeInDown-2 medium-color animated bounceInDown"><a href="' . $row->url . '">' . $row->description . '</a></p>';
                            //$html .= '<div class="_theme-about-cover-box fadeInDown-3"><a href="' . $row->url . '" class="btn btn-danger rounded-round">' . $this->lang->line('THEME_READMORE') . '</a></div>';
								
                        $html .= '</div>';
                    $html .= '</div>';
                $html .= '</div>';
                
            }
            $html .= '</div>';

        }
        
        return $html;
    }
    
    public function owlSliderPartner_model($param = array()) {
        $html = '';
        $query = $this->db->query('
            SELECT 
                M.id,
                M.title,
                M.link_title,
                M.url,
                M.target,
                M.description,
                M.pic,
                M.attach_file,
                M.custom,
                M.duration                
            FROM `gaz_media` AS M 
            WHERE M.lang_id = ' . $this->session->themeLanguage['id'] . ' AND M.is_active = 1 AND M.cat_id = ' . $param['catId'] . '
            ORDER BY M.order_num ASC');
        
        if ($query->num_rows() > 0) {
            
            $html .= '<div class="owl-carousel owl-partner">';
            foreach ($query->result() as $key => $row) {
                
                $html .= '<div class="item">';
                    $html .= '<a href="' . $row->url . '" target="' . $row->target . '" title="' . $row->link_title . '">';
                        $html .= '<img src="' . UPLOADS_MEDIA_PATH . $row->pic . '" alt="' . $row->link_title . '">';
                    $html .= '</a>';
                $html .= '</div>';
                
            }
            $html .= '</div>';

        }
        
        return $html;
    }
    
    public function footerPartner_model($param = array()) {
        $html = '';
        $query = $this->db->query('
            SELECT 
                M.id,
                M.title,
                M.link_title,
                M.url,
                M.target,
                M.description,
                M.pic,
                M.attach_file,
                M.custom,
                M.duration                
            FROM `gaz_media` AS M 
            WHERE M.lang_id = ' . $this->session->themeLanguage['id'] . ' AND M.is_active = 1 AND M.cat_id = ' . $param['catId'] . '
            ORDER BY M.order_num ASC');
        
        if ($query->num_rows() > 0) {
            
            $html .= '<ul class="_theme-footer-partner">';
            foreach ($query->result() as $key => $row) {
                
                $html .= '<li>';
                    $html .= '<a href="' . $row->url . '" target="' . $row->target . '" title="' . $row->link_title . '">';
                        $html .= '<img src="' . UPLOADS_MEDIA_PATH . $row->pic . '" alt="' . $row->link_title . '">';
                    $html .= '</a>';
                $html .= '</li>';
                
            }
            $html .= '</ul>';

        }
        
        return $html;
    }
    
    public function pageHeader_model($param = array()) {
        $html = '';
        $query = $this->db->query('
            SELECT 
                M.id,
                M.title,
                M.link_title,
                M.url,
                M.target,
                M.description,
                M.pic,
                M.attach_file,
                M.custom,
                M.duration                
            FROM `gaz_media` AS M 
            WHERE M.lang_id = ' . $this->session->themeLanguage['id'] . ' AND M.is_active = 1 AND M.cat_id = ' . $param['catId'] . '
            LIMIT 0, 1');
        
        if ($query->num_rows() > 0) {
            
            foreach ($query->result() as $key => $row) {
                
                $html .= '<div class="_page-header" style="background-image: url(' . UPLOADS_MEDIA_PATH . $row->pic . ');">
                    <div class="container">
                        <div class="caption"><h2 class="fadeInDown-1 light-color">' . $row->title . '</h2>
                            <p class="fadeInDown-2 medium-color">' . $row->description . '</p>
                        </div>
                    </div>
                </div>';
                
            }

        }
        
        return $html;
    }
}
