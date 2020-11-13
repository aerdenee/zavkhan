<?php

class Tmenu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function footerMenu_model($param = array('locId' => 0, 'parentId' => 0, 'selectedId' => 0, 'class' => false)) {

        $this->html = '';

        $this->query = $this->db->query('
            SELECT 
                M.id,
                M.module_id,
                M.title,
                M.link_title,
                M.target,
                M.direct_url,
                M.link_type_id,
                U.url,
                C.title AS cat_title
            FROM `gaz_menu` AS M
            LEFT JOIN `gaz_url` U on M.module_id = U.mod_id AND M.id = U.cont_id
            LEFT JOIN `gaz_category` C on M.cat_id = C.id
            WHERE 
                M.lang_id = ' . $this->session->userdata['themeLanguage']['id'] . ' AND 
                M.location_id = ' . $param['locId'] . ' AND 
                M.parent_id = ' . $param['parentId'] . ' AND 
                M.is_active = 1
            ORDER BY M.order_num ASC');

        if ($this->query->num_rows() > 0) {

            $this->html .= '<ul class="list-unstyled ' . $param['class'] . '">';

            foreach ($this->query->result() as $k => $row) {

//                if ($k == 0) {
//                    $this->html .= '<li>' . $row->cat_title . '</li>';
//                }
                $this->html .= '<li class="footer-menu-arrow ' . ($param['selectedId'] == $row->id ? 'active' : '' ) . '">';

                if ($row->link_type_id == 1) {

                    $this->html .= '<a id="drop' . $row->id . '" href="' . $this->session->userdata['themeLanguage']['path'] . $row->url . '" title="' . $row->link_title . '" target="' . $row->target . '">' . $row->title . '</a>';
                } else {
                    if ($row->direct_url == 'index') {
                        $this->html .= '<a id="drop' . $row->id . '" href="' . $this->session->userdata['themeLanguage']['path'] . '" target="' . $row->target . '" title="' . $row->link_title . '">' . $row->title . '</a>';
                    } else {
                        $this->html .= '<a id="drop' . $row->id . '" href="' . $row->direct_url . '" target="' . $row->target . '" title="' . $row->link_title . '" target="' . $row->target . '">' . $row->title . '</a>';
                    }
                }

                $this->html .= '</li>';
            }

            $this->html .= '</ul>';
        }

        return $this->html;
    }

    function iconMenu_model($param = array('locId' => 0, 'parentId' => 0, 'selectMenuId' => 0, 'homeIcon' => false)) {

        $this->html = '';
        $this->childMenu = '';

        $this->query = $this->db->query('
            SELECT 
                M.id,
                M.module_id,
                M.title,
                M.link_title,
                M.target,
                M.direct_url,
                M.link_type_id,
                U.url,
                M.is_home,
                M.pic
            FROM `gaz_menu` AS M
            INNER JOIN `gaz_url` U on M.module_id = U.mod_id AND M.id = U.cont_id 
            WHERE 
                M.lang_id = ' . $this->session->userdata['themeLanguage']['id'] . ' AND 
                M.location_id = ' . $param['locId'] . ' AND 
                M.parent_id = 0 AND 
                M.is_active = 1 
            ORDER BY M.order_num ASC');

        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $k => $row) {

                $this->html .= '<div class="col-lg-3 col-md-6 col-sm-6">';

                $this->html .= '<div class="cspnews animated bounceInRight">';
                $this->html .= '<div class="imglink">';
                $this->html .= '<img class="in_shadow ikonlazy" src="' . UPLOADS_CONTENT_PATH . $row->pic . '" alt="' . $row->link_title . '">';
                $this->html .= '</div>';
                $this->html .= '<div class="fill_absolute in_shadow60">';
                $this->html .= '<a href="' . $this->session->userdata['themeLanguage']['path'] . $row->url . '"></a>';
                $this->html .= '</div>';

                $this->html .= '<div class="csptitlenew">';
                $this->html .= '<a href="' . $this->session->userdata['themeLanguage']['path'] . $row->url . '">' . $row->title . '</a>';
                $this->html .= '</div>';
                $this->html .= '</div>';

                $this->html .= '</div>';
            }
        }

        return $this->html;
    }

    function mainMenu_model($param = array('locId' => 0, 'parentId' => 0, 'selectedId' => 0)) {

        if (!isset($param['selectedId'])) {
            $param['selectedId'] = 0;
        }

        $html = $childMenu = $liClass = '';

        $query = $this->db->query('
            SELECT 
                M.id,
                M.module_id,
                M.title,
                M.link_title,
                M.target,
                M.direct_url,
                M.link_type_id,
                U.url,
                M.is_home
            FROM `gaz_menu` AS M
            INNER JOIN `gaz_url` U on M.module_id = U.mod_id AND M.id = U.cont_id 
            WHERE 
                M.lang_id = ' . $this->session->userdata['themeLanguage']['id'] . ' AND 
                M.location_id = ' . $param['locId'] . ' AND 
                M.parent_id = 0 AND 
                M.is_active = 1 
            ORDER BY M.order_num ASC');

        if ($query->num_rows() > 0) {

            $html .= '<ul class="navbar-nav">';
            foreach ($query->result() as $k => $row) {

                $childMenu = $this->mainMenuChild_model(array(
                    'locId' => $param['locId'],
                    'parentId' => $row->id,
                    'selectedId' => $param['selectedId']));

                if ($childMenu) {
                    $html .= '<li class="nav-item ' . ((($row->is_home == 1 and $param['selectedId'] == 0) or $param['selectedId'] == $row->id) ? 'active' : '') . ' dropdown">';

                    if ($row->link_type_id == '1') {

                        $html .= '<a 
                                href="' . $this->session->userdata['themeLanguage']['path'] . $row->url . '"
                                title="' . $row->link_title . '" 
                                target="' . $row->target . '" 
                                class="navbar-nav-link dropdown-toggle"
                                data-toggle="dropdown">' . $row->title . '</a>';
                    } else {

                        if ($row->target == '_parent') {
                            $html .= '<a 
                                href="' . $this->session->userdata['themeLanguage']['path'] . $row->direct_url . '" 
                                target="' . $row->target . '" 
                                title="' . $row->link_title . '"
                                class="navbar-nav-link">' . $row->title . '</a>';
                        } else {
                            $html .= '<a 
                                href="' . $row->direct_url . '" 
                                target="' . $row->target . '" 
                                title="' . $row->link_title . '"
                                class="navbar-nav-link">' . $row->title . '</a>';
                        }
                        
                    }

                    $html .= '<div class="dropdown-menu">' . $childMenu . '</div>';

                    $html .= '</li>';
                } else {
                    $html .= '<li class="nav-item ' . ((($row->is_home == 1 and $param['selectedId'] == 0) or $param['selectedId'] == $row->id) ? 'active' : '') . '">';

                    if ($row->link_type_id == '1') {

                        $html .= '<a 
                                href="' . $this->session->userdata['themeLanguage']['path'] . $row->url . '"
                                title="' . $row->link_title . '" 
                                target="' . $row->target . '" 
                                class="navbar-nav-link">' . $row->title . '</a>';
                    } else {

                        if ($row->target == '_parent') {
                            $html .= '<a 
                                href="' . $this->session->userdata['themeLanguage']['path'] . $row->direct_url . '" 
                                target="' . $row->target . '" 
                                title="' . $row->link_title . '"
                                class="navbar-nav-link">' . $row->title . '</a>';
                        } else {
                            $html .= '<a 
                                href="' . $row->direct_url . '" 
                                target="' . $row->target . '" 
                                title="' . $row->link_title . '"
                                class="navbar-nav-link">' . $row->title . '</a>';
                        }
                        
                    }

                    $html .= '</li>';
                }
            }
            $html .= '</ul>';
        }

        return $html;
    }

    function mainMenuChild_model($param = array('locId' => 0, 'parentId' => 0, 'selectedMenuId' => 0, 'html' => '')) {

        $html = '';

        $query = $this->db->query('
            SELECT 
                M.id,
                M.module_id,
                M.title,
                M.link_title,
                M.target,
                M.direct_url,
                M.link_type_id,
                U.url,
                M.is_home
            FROM `gaz_menu` AS M
            INNER JOIN `gaz_url` U on M.module_id = U.mod_id AND M.id = U.cont_id 
            WHERE M.lang_id = ' . $this->session->userdata['themeLanguage']['id'] . ' AND M.location_id = ' . $param['locId'] . ' AND M.parent_id = ' . $param['parentId'] . ' AND M.is_active = 1 ORDER BY M.order_num ASC');


        if ($query->num_rows() > 0) {

            

            foreach ($query->result() as $k => $row) {

                $childMenu = $this->mainMenuChild_model(array(
                    'locId' => $param['locId'],
                    'parentId' => $row->id,
                    'selectedId' => $param['selectedId']));

                if ($childMenu) {
                    $html .= '<div class="dropdown-submenu">';
                    if ($row->link_type_id == 1) {

                        $html .= '<a 
                                href="' . $this->session->userdata['themeLanguage']['path'] . $row->url . '"
                                title="' . $row->link_title . '" 
                                target="' . $row->target . '" 
                                class="dropdown-item ' . ((($param['selectedId'] > 0 and $param['selectedId'] == $row->id) or $row->is_home == 1) ? 'active' : '') . ' dropdown-toggle">' . $row->title . '</a>';
                    } else {

                        if ($row->target == '_parent') {
                            $html .= '<a 
                                href="' . $this->session->userdata['themeLanguage']['path'] . $row->direct_url . '" 
                                target="' . $row->target . '" 
                                title="' . $row->link_title . '"
                                class="dropdown-item ' . ($param['selectedId'] == $row->id ? 'active' : '') . ' dropdown-toggle">' . $row->title . '</a>';
                        } else {
                            $html .= '<a 
                                href="' . $row->direct_url . '" 
                                target="' . $row->target . '" 
                                title="' . $row->link_title . '"
                                class="dropdown-item ' . ($param['selectedId'] == $row->id ? 'active' : '') . ' dropdown-toggle">' . $row->title . '</a>';
                        }
                        
                        
                    }
                        $html .= '<div class="dropdown-menu">' . $childMenu . '</div>';
                    $html .= '</div>';
                } else {
                    if ($row->link_type_id == 1) {

                        $html .= '<a 
                                href="' . $this->session->userdata['themeLanguage']['path'] . $row->url . '"
                                title="' . $row->link_title . '" 
                                target="' . $row->target . '" 
                                class="dropdown-item ' . ((($param['selectedId'] > 0 and $param['selectedId'] == $row->id) or $row->is_home == 1) ? 'active' : '') . '">' . $row->title . '</a>';
                    } else {

                        if ($row->target == '_parent') {
                            $html .= '<a 
                                href="' . $this->session->userdata['themeLanguage']['path'] . $row->direct_url . '" 
                                target="' . $row->target . '" 
                                title="' . $row->link_title . '"
                                class="dropdown-item ' . ($param['selectedId'] == $row->id ? 'active' : '') . ' dropdown-toggle">' . $row->title . '</a>';
                        } else {
                            $html .= '<a 
                                href="' . $row->direct_url . '" 
                                target="' . $row->target . '" 
                                title="' . $row->link_title . '"
                                class="dropdown-item ' . ($param['selectedId'] == $row->id ? 'active' : '') . ' dropdown-toggle">' . $row->title . '</a>';
                        }
                    }
                }
            }

            return $html;
        }
        return false;
    }

    function getSelectedMenu_model($param = array('selectedId' => 0)) {

        if ($param['selectedId'] != NULL) {
            $this->query = $this->db->query('
                SELECT 
                    M.id,
                    M.pic,
                    M.cat_id,
                    M.title,
                    M.link_title,
                    M.h1_text,
                    M.page_title,
                    M.meta_key,
                    M.meta_desc,
                    M.department_id,
                    U.url,
                    M.mod_id
                FROM `gaz_menu` AS M
                INNER JOIN `gaz_url` U on M.module_id = U.mod_id AND M.id = U.cont_id 
                WHERE M.id = ' . $param['selectedId']);
            if ($this->query->num_rows()) {
                return $this->query->row();
            }
        }

        return false;
    }

    function getSelectedMenuByCategory_model($param = array('catId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                pic_' . $this->session->langShortCode . ' AS pic,
                cat_id,
                title_' . $this->session->langShortCode . ' AS title,
                link_title_' . $this->session->langShortCode . ' AS link_title,
                h1_text_' . $this->session->langShortCode . ' AS h1_text,
                page_title_' . $this->session->langShortCode . ' AS page_title,
                meta_key_' . $this->session->langShortCode . ' AS meta_key,
                meta_desc_' . $this->session->langShortCode . ' AS meta_desc
            FROM `gaz_menu`
            WHERE cat_id = ' . $param['catId']);
        if ($this->query->num_rows()) {
            return $this->query->row();
        }
        return false;
    }

    function partnerMenuUrl_model($param = array('parentId' => 0, 'partnerId' => 0)) {


        $this->html = '';
        $this->query = $this->db->query('
            SELECT 
                U.url
            FROM `gaz_menu` AS M
            INNER JOIN `gaz_url` U on M.module_id = U.mod_id AND M.id = U.cont_id 
            WHERE M.parent_id = ' . $param['parentId'] . ' AND M.partner_id = ' . $param['partnerId'] . ' AND M.is_active_' . $this->session->langShortCode . ' = 1 ORDER BY M.order_num ASC');


        if ($this->query->num_rows() > 0) {

            $this->row = $this->query->row();

            return $this->row->url . '/';
        }
        return '';
    }

    function getMenuList_model($param = array('locId' => 0, 'parentId' => 0, 'selectMenuId' => 0)) {

        $this->html = '';
        $this->childMenu = '';

        $this->query = $this->db->query('
            SELECT 
                M.id,
                M.module_id,
                M.title,
                M.link_title,
                M.target,
                M.direct_url,
                M.link_type_id,
                U.url,
                M.is_home
            FROM `gaz_menu` AS M
            INNER JOIN `gaz_url` U on M.module_id = U.mod_id AND M.id = U.cont_id 
            WHERE 
                M.lang_id = ' . $this->session->userdata['themeLanguage']['id'] . ' AND 
                M.location_id = ' . $param['locId'] . ' AND 
                M.parent_id = 0 AND 
                M.is_active = 1 
            ORDER BY M.order_num ASC');

        if ($this->query->num_rows() > 0) {

            $this->html .= '<div class="dropdown-menu" style="display: block; position: static; width: 100%; margin-top: 0; float: none; z-index: auto;">';

            $this->html .= '<div class="dropdown-header"><i class="icon-menu7"></i> Улаанбаатар аялал жуучлалын холбоо <i class="icon-gear mr-0 ml-auto"></i></div>';

            foreach ($this->query->result() as $key => $row) {

                $this->html .= '<a href="' . $this->session->userdata['themeLanguage']['path'] . $row->url . '" class="dropdown-item ' . ($param['selectMenuId'] == $row->id ? ' active' : '') . '">' . $row->title . '</a>';
                //$this->html .= '<div class="dropdown-divider"></div>';
            }

            $this->html .= '</div>';
        }

        return $this->html;
    }

}
