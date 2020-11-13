<?php

class Tnews_model extends CI_Model {

    function __construct() {
        /* Call the Model constructor */
        parent::__construct();

        $this->load->model('Scategory_model', 'category');
        $this->load->model('Tmedia_model', 'tmedia');
        $this->load->model('Tmenu_model', 'tmenu');
    }

    public function getItem_model($param = array('contId' => 0)) {

        $this->queryString = '';
        $this->html = '';

        $this->query = $this->db->query('
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
                CAT.title AS cat_title,
                CONCAT(SUBSTRING(US.lname, 1, 1), \'.\', US.fname) AS full_name,
                C.facebook_count,
                C.twitter_count
            FROM `gaz_content` AS C
            LEFT JOIN `gaz_url` U on C.mod_id = U.mod_id AND C.id = U.cont_id 
            LEFT JOIN `gaz_user` US ON C.created_user_id = US.id
            LEFT JOIN `gaz_theme_layout` TL ON C.theme_layout_id = TL.id
            INNER JOIN `gaz_category` AS CAT ON C.mod_id = CAT.mod_id AND C.cat_id = CAT.id
            WHERE C.lang_id = ' . $this->session->userdata['themeLanguage']['id'] . ' AND C.id = ' . $param['contId'] . ' AND C.is_active = 1');

        if ($this->query->num_rows() > 0) {

            return $this->query->row();
        }

        return false;
    }

    public function getItemMedia_model($param = array('modId' => 0, 'contId' => 0, 'type' => '1,3')) {

        $query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cont_id,
                title,
                intro_text,
                pic,
                attach_file,
                pic_mime_type,
                pic_file_size,
                is_active,
                is_active_date,
                param,
                order_num,
                created_date,
                modified_date,
                media_type_id,
                created_user_id,
                modified_user_id
            FROM `gaz_content_media` 
            WHERE `is_active` = 1 AND `mod_id` = ' . $param['modId'] . ' AND cont_id = ' . $param['contId'] . '
            ORDER BY `order_num` DESC');

        if ($query->num_rows() > 0) {

            return $query->result();
        }

        return false;
    }

    public function listsCount_model($param = array()) {

        $queryString = '';

        $query = $this->db->query('
            SELECT 
                C.id
            FROM `gaz_content` AS C
            WHERE C.cat_id IN(' . $this->category->getChildCategores_model($param['catId']) . ') AND C.is_active = 1' . $queryString);

        return $query->num_rows();
    }

    public function lists_model($param = array('catId' => 0, 'page' => 0, 'limit' => 0)) {

        $queryString = '';

        $query = $this->db->query('
            SELECT 
                C.id,
                C.mod_id,
                C.pic,
                C.title,
                C.link_title,
                C.intro_text,
                C.full_text,
                C.comment_count,
                C.click,
                C.is_active_date,
                U.url,
                CAT.title AS cat_title,
                C.theme_layout_id,
                CONCAT(SUBSTRING(US.lname, 1, 1), \'.\', US.fname) AS author
            FROM `gaz_content` AS C
            INNER JOIN `gaz_url` AS U ON C.mod_id = U.mod_id AND C.id = U.cont_id
            INNER JOIN `gaz_category` AS CAT ON C.mod_id = CAT.mod_id AND C.cat_id = CAT.id
            INNER JOIN `gaz_user` AS US ON C.created_user_id = US.id
            WHERE 1 = 1 AND C.cat_id IN(' . $this->category->getChildCategores_model($param['catId']) . ') AND C.is_active = 1 ' . $queryString . '
            ORDER BY C.is_active_date DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        if ($query->num_rows() > 0) {

            return $query->result();
        }

        return false;
    }

    public function tabNewsLists_model($param = array('sortType' => 'date')) {


        $html = $queryString = '';

        if (isset($param['catId'])) {
            $queryString .= ' AND C.cat_id IN (' . $param['catId'] . ')';
        }

        if (isset($param['sortType']) and $param['sortType'] == 'date') {
            $queryString .= ' ORDER BY C.is_active_date DESC';
        } else if (isset($param['sortType']) and $param['sortType'] == 'click') {
            $queryString .= ' ORDER BY C.click DESC';
        } else if (isset($param['sortType']) and $param['sortType'] == 'comment') {
            $queryString .= ' ORDER BY COMMENT.comment_count DESC';
        }


        $query = $this->db->query('
            SELECT 
                C.id,
                C.pic,
                C.title,
                C.link_title,
                COMMENT.comment_count,
                C.is_active_date,
                U.url
            FROM `gaz_content` AS C
            INNER JOIN `gaz_url` AS U ON C.mod_id = U.mod_id AND C.id = U.cont_id
            LEFT JOIN (
                SELECT 
                    mod_id,
                    cont_id,
                    COUNT(id) AS comment_count
                FROM `gaz_comment`
                GROUP BY mod_id, cont_id
            ) AS COMMENT ON COMMENT.mod_id = C.mod_id AND COMMENT.cont_id = C.id
            WHERE C.is_active = 1 ' . $queryString . '
            LIMIT 0, 20');

        if ($query->num_rows() > 0) {

            $html .= '<ul class="_theme-news-list">';
            foreach ($query->result() as $key => $row) {

                $html .= '<li>';
                if ($key == 3) {
                    $html .= '<div class="mb-2 mt-2">' . $param['banner'] . '</div>';
                }
                
                $html .= '<table border="0" cellspacing="0" cellpadding="0" width="95%">';
                    $html .= '<tbody>';
                        $html .= '<tr>';
                            $html .= '<td width="100" valign="top">';
                                $html .= '<div class="_theme-news-list-img">';
                                    $html .= '<a href="' . $row->url . '"></a>';
                                    $html .= '<img src="' . UPLOADS_CONTENT_PATH . CROP_SMALL . $row->pic . '">';
                                $html .= '</div>';
                            $html .= '</td>';
                            $html .= '<td width="100%" valign="top">';
                                $html .= '<div class="_theme-news-list-description">';
                                    $html .= '<div class="_theme-news-list-title">';
                                        $html .= '<a href="' . $row->url . '">' . word_limiter($row->title, 10, '...') . '</a>';
                                    $html .= '</div>';
                                    $html .= '<div class="_theme-news-list-accessories">';
                                        //$html .= '<span class="_theme-icon-share">213</span> |';
                                        //$html .= '<a class="nlimg_link" data-turbolinks="true" target="_blank" href="/n/1tsg#ikcomments">';
                                        //$html .= '<span class="_theme-icon-empty">' . $row->comment_count . '</span> | ';
                                        //$html .= '</a> |';
                                    
                                        $html .= '<span class="_theme-icon-time"><span>' . date('Y.m.d', strtotime($row->is_active_date)) . '</span></span>';
                                    $html .= '</div>';
                                    
                                $html .= '</div>';
                                $html .= '<div class="rn_reading"></div>';
                            $html .= '</td>';
                        $html .= '</tr>';
                    $html .= '</tbody>';
                $html .= '</table>';
                
                $html .= '</li>';
            }
            $html .= '</ul>';
            return $html;
        }

        return false;
    }
    
    public function newsHorzintalLists_model($param = array('sortType' => 'date')) {


        $html = $queryString = '';

        if (isset($param['catId'])) {
            $queryString .= ' AND C.cat_id IN (' . $param['catId'] . ')';
        }

        if (isset($param['sortType']) and $param['sortType'] == 'date') {
            $queryString .= ' ORDER BY C.is_active_date DESC';
        } else if (isset($param['sortType']) and $param['sortType'] == 'click') {
            $queryString .= ' ORDER BY C.click DESC';
        } else if (isset($param['sortType']) and $param['sortType'] == 'comment') {
            $queryString .= ' ORDER BY C.comment_count DESC';
        }


        $query = $this->db->query('
            SELECT 
                C.id,
                C.pic,
                C.title,
                C.link_title,
                C.intro_text,
                C.comment_count,
                C.is_active_date,
                U.url
            FROM `gaz_content` AS C
            INNER JOIN `gaz_url` AS U ON C.mod_id = U.mod_id AND C.id = U.cont_id
            WHERE C.is_active = 1 ' . $queryString . '
            LIMIT 0, 6');

        if ($query->num_rows() > 0) {

            $html .= '<div class="row">';
            
            foreach ($query->result() as $key => $row) {

                $html .= '<div class="col-md-4 mb10 _theme-layout-news-item">';
                    $html .= '<a href="' . $row->url . '">';
                        $html .= '<h4>' . word_limiter($row->title, 10, '...') . '</h4>';
                        $html .= '<div class="image imghover" style="background-image: url(\'' . UPLOADS_CONTENT_PATH . CROP_SMALL . $row->pic . '\')"></div>';
                        $html .= '<div class="content">' . word_limiter($row->intro_text, 20, '...') . '</div>';
                    $html .= '</a>';
                $html .= '</div>';

            }
            
            $html .= '</div>';
            
            return $html;
        }

        return false;
    }
    
    public function newsItemHorzintalGetKeywordLists_model($param = array('sortType' => 'date')) {

        $html = $queryString = '';

        if (isset($param['keyword'])) {
            $param['keyword'] = explode(',', $html);
            $queryString .= ' AND LOWER(C.meta_key) LIKE (\'%' . trim($param['keyword']['0']) . '%\')';
        }

        if (isset($param['sortType']) and $param['sortType'] == 'date') {
            $queryString .= ' ORDER BY C.is_active_date DESC';
        } else if (isset($param['sortType']) and $param['sortType'] == 'click') {
            $queryString .= ' ORDER BY C.click DESC';
        } else if (isset($param['sortType']) and $param['sortType'] == 'comment') {
            $queryString .= ' ORDER BY C.comment_count DESC';
        }


        $query = $this->db->query('
            SELECT 
                C.id,
                C.pic,
                C.title,
                C.link_title,
                C.intro_text,
                C.comment_count,
                C.is_active_date,
                U.url
            FROM `gaz_content` AS C
            INNER JOIN `gaz_url` AS U ON C.mod_id = U.mod_id AND C.id = U.cont_id
            WHERE C.is_active = 1 ' . $queryString . '
            LIMIT 0, 3');

        if ($query->num_rows() > 0) {

            $html .= '<div class="row">';
            
            foreach ($query->result() as $key => $row) {

                $html .= '<div class="col-md-4">';
                $html .= '<div class="_theme-media">';
                    $html .= '<div class="imglink">';
                        $html .= '<img class="in_shadow" src="' . UPLOADS_CONTENT_PATH . CROP_SMALL . $row->pic . '" alt="' . $row->link_title . '">';
                    $html .= '</div>';
                    $html .= '<div class="fill_absolute in_shadow"><a href="' . $row->url . '" title="' . $row->link_title . '"></a></div>';
                    $html .= '<div class="poster-cant-footer"><a href="' . $row->url . '" title="' . $row->link_title . '">' . $row->title . '</a></div>';
                $html .= '</div>';
                $html .= '</div>';
            }
            
            $html .= '</div>';
            
            return $html;
        }

        return false;
    }

    public function downloadFile_model($param = array('mediaId' => 0)) {

        $this->data = array();

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cont_id,
                title_' . $this->session->langShortCode . ' AS title,
                intro_text_' . $this->session->langShortCode . ' AS intro_text,
                attach_file_' . $this->session->langShortCode . ' AS attach_file,
                file_type_' . $this->session->langShortCode . ' AS file_type,
                order_num,
                created_date,
                modified_date,
                type,
                created_user_id,
                modified_user_id
            FROM `gaz_content_media` 
            WHERE is_active_' . $this->session->langShortCode . ' = 1 AND id = ' . $param['mediaId']);
        if ($this->query->num_rows() > 0) {
            $this->row = $this->query->row();
            $this->size = '';

            if ($this->row->file_type == 'application/pdf') {
                $this->data['file'] = UPLOADS_CONTENT_PATH . $this->row->attach_file;
                $this->data['size'] = filesize($_SERVER['DOCUMENT_ROOT'] . $this->data['file']);
            } else if ($this->row->file_type == 'application/msword') {
                $this->data['file'] = UPLOADS_CONTENT_PATH . $this->row->attach_file;
                $this->data['size'] = filesize($_SERVER['DOCUMENT_ROOT'] . $this->data['file']);
            } else {
                $this->data['file'] = UPLOADS_CONTENT_PATH . CROP_BIG . $this->row->attach_file;
                $this->data['size'] = filesize($_SERVER['DOCUMENT_ROOT'] . $this->data['file']);
            }


            header('Content-Description: File Transfer');
            header('Content-Type: ' . $this->row->file_type);
            header('Content-Disposition: attachment; filename="' . $this->row->attach_file . '"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . $this->data['size']);
            readfile(base_url(UPLOADS_CONTENT_PATH . $this->row->attach_file));
        }
    }

    public function lastUpdateDate_model() {

        $query = $this->db->query('
            SELECT 
                C.created_date
            FROM `gaz_content` AS C
            WHERE C.is_active = 1
            ORDER BY C.created_date DESC');

        $row = $query->row();
        return date('Y/m/d', strtotime($row->created_date));
    }
}
