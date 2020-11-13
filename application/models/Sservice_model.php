<?php

class Sservice_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function addFormData_model() {
        return array(
            'id' => 0,
            'mod_id' => 0,
            'cat_id' => 0,
            'parent_id' => 0,
            'show_pic_outside' => 1,
            'show_pic_inside' => 1,
            'pic' => '',
            'pic_vertical' => '',
            'show_file' => 0,
            'file' => '',
            'show_title' => 1,
            'title_mn' => '',
            'link_title_mn' => '',
            'intro_text_mn' => '',
            'full_text_mn' => '',
            'page_title_mn' => '',
            'meta_key_mn' => '',
            'meta_desc_mn' => '',
            'h1_text_mn' => '',
            'show_date' => 1,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'is_active_date' => date('Y-m-d H:i:s'),
            'show_author' => 1,
            'author_id' => 0,
            'created_user_id' => 0,
            'modified_user_id' => 0,
            'show_comment' => 1,
            'comment_count_mn' => 0,
            'show_click' => 1,
            'click_mn' => 0,
            'click_real_mn' => 0,
            'is_active_mn' => 1,
            'order_num' => getOrderNum(array('table' => 'content', 'field' => 'order_num')),
            'show_social' => 1,
            'param' => '',
            'price' => 0,
            'price_sale' => 0,
            'type' => 1,
            'theme_layout_id' => 1,
            'is_active_en' => 0,
            'title_en' => '',
            'link_title_en' => '',
            'intro_text_en' => '',
            'full_text_en' => '',
            'page_title_en' => '',
            'meta_key_en' => '',
            'meta_desc_en' => '',
            'h1_text_en' => '',
            'comment_count_en' => 0,
            'click_en' => 0,
            'click_real_en' => 0,
            'url_id' => 0,
            'url' => getUID('url'),
            'tour_background_mn' => '',
            'tour_background_en' => '',
            'tour_days' => '',
            'tour_price' => '',
            'tour_included_service_mn' => '',
            'tour_included_service_en' => '',
            'tour_param_mn' => '',
            'tour_param_en' => '',
            'partner_id' => 0,
            'image_crop_type' => 1
        );
    }

    public function editFormData_model($param = array('id' => 0)) {

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
                C.title_mn,
                C.link_title_mn,
                C.intro_text_mn,
                C.full_text_mn,
                C.page_title_mn,
                C.meta_key_mn,
                C.meta_desc_mn,
                C.h1_text_mn,
                C.show_date,
                C.created_date,
                C.modified_date,
                C.is_active_date,
                C.show_author,
                C.author_id,
                C.created_user_id,
                C.modified_user_id,
                C.show_comment,
                C.comment_count_mn,
                C.show_click,
                C.click_mn,
                C.click_real_mn,
                C.is_active_mn,
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
            WHERE C.id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            $this->result = $this->query->result();
            return (array) $this->result['0'];
        }
        return self::addFormData_model();
    }

    public function listsCount_model($param = array()) {
        
        $this->queryString = '';

        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND C.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND C.created_user_id = -1';
        }

        if ($this->session->userdata['adminAccessTypeId'] == 2) {
            $this->queryString .= ' AND partner_id = ' . $this->session->userdata['adminPartnerId'];
        }
        
        if ($param['catId'] != 0) {
            $this->queryString .= ' AND cat_id = ' . $param['catId'];
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND LOWER(title_mn) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
        }

        $this->query = $this->db->query('
            SELECT 
                C.id
            FROM `gaz_content` AS C 
            WHERE 1 = 1 ' . $this->queryString . ' AND C.mod_id = ' . $param['modId'] . ' 
            ORDER BY C.id DESC');

        $this->result = $this->query->result();

        return count($this->result);
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {


        $this->queryString = $this->getString = '';
        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND C.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND C.created_user_id = -1';
        }

        if ($this->session->userdata['adminAccessTypeId'] == 2) {
            $this->queryString .= ' AND C.partner_id = ' . $this->session->userdata['adminPartnerId'];
        }
        
        if ($param['catId'] != 0) {
            $this->queryString .= ' AND cat_id = ' . $param['catId'];
            $this->getString .= form_hidden('catId', $param['catId']);
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND LOWER(title_mn) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.title_mn,
                C.pic,
                C.order_num,
                C.created_date,
                C.modified_date,
                C.created_user_id,
                C.modified_user_id,
                C.is_active_mn as is_active,
                C.mod_id
            FROM `gaz_content` AS C
            WHERE 1 = 1 ' . $this->queryString . ' AND C.mod_id = ' . $param['modId'] . ' 
            ORDER BY C.order_num DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        $this->result = $this->query->result();
        $this->resultCount = $this->query->num_rows();

        $this->data = array();
        $this->data['html'] = '';
        $this->data['html'] .= form_open('', array('class' => 'form-horizontal', 'id' => 'form-reservation-export', 'enctype' => 'multipart/form-data'));
        $this->data['html'] .= form_hidden('modId', $param['modId']);
        $this->data['html'] .= form_hidden('limit', $param['limit']);
        $this->data['html'] .= form_hidden('page', $param['page']);
        $this->data['html'] .= form_hidden('our[\'create\']', $this->auth->our->create);
        $this->data['html'] .= form_hidden('our[\'read\']', $this->auth->our->read);
        $this->data['html'] .= form_hidden('our[\'update\']', $this->auth->our->update);
        $this->data['html'] .= form_hidden('our[\'delete\']', $this->auth->our->delete);
        $this->data['html'] .= form_hidden('your[\'read\']', $this->auth->your->read);
        $this->data['html'] .= form_hidden('your[\'update\']', $this->auth->your->update);
        $this->data['html'] .= form_hidden('your[\'delete\']', $this->auth->your->delete);
        $this->data['html'] .= $this->getString;

        $this->data['html'] .= '<div class="panel panel-flat">';
        $this->data['html'] .= '<div class="panel-heading">';
        $this->data['html'] .= '<h5 class="panel-title">' . $param['title'] . '</h5>';
        $this->data['html'] .= '<div class="heading-elements">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';
        $this->data['html'] .= '<li>' . anchor(Sservice::$path . 'index/' . $param['modId'], '<i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i>', array()) . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="panel-body" style="padding-bottom:5px;">';
        $this->data['html'] .= '<div class="pull-right">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';

        if ($this->auth->our->create == 1) {
            $this->data['html'] .= '<li style="padding-right:10px;">' . anchor(Sservice::$path . 'add/' . $param['modId'], '<i class="fa fa-plus"></i> Нэмэх', 'class="btn btn-info btn-rounded btn-xs"') . '</li>';
        } else {
            $this->data['html'] .= '<li style="padding-right:10px;">' . anchor('javascript:;' . $param['modId'], '<i class="fa fa-plus"></i> Нэмэх', 'class="btn btn-info btn-rounded btn-xs disabled"') . '</li>';
        }

        $this->data['html'] .= '<li style="padding-right:10px;">' . form_button('send', '<i class="fa fa-download"></i> Экспорт', 'class="btn btn-info btn-rounded btn-xs" onclick="_export(this);" ' . (empty($this->resultCount) ? ' disabled="disabled"' : ''), 'button') . '</li>';
        $this->data['html'] .= '<li>' . form_button('send', '<i class="fa fa-search"></i> хайх', 'class="btn btn-info btn-rounded btn-xs" onclick="_advensedSearch({modId:' . $param['modId'] . ', elem:this});"', 'button') . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        if ($this->resultCount > 0) {

            $this->data['html'] .= '<div class="table-responsive">';
            $this->data['html'] .= '<table class="table table-bordered table-custom">';
            $this->data['html'] .= '<thead>';
            $this->data['html'] .= '<tr>';
            $this->data['html'] .= '<th style="width:30px;">#</th>';
            $this->data['html'] .= '<th style="width:100px;">Зураг</th>';
            $this->data['html'] .= '<th>Гарчиг</th>';
            $this->data['html'] .= '<th style="width:100px;">Огноо</th>';
            $this->data['html'] .= '<th style="width:100px;">Төлөв</th>';
            $this->data['html'] .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';
            $i = 1;
            foreach ($this->result as $value) {
                $row = (array) $value;
                $this->data['html'] .= '<tr data-mod-id="' . $row['mod_id'] . '" data-id="' . $row['id'] . '" data-uid="' . $row['created_user_id'] . '">';
                $this->data['html'] .= '<td>' . $i . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row text-center"><img src="' . UPLOADS_CONTENT_PATH . CROP_SMALL . $row['pic'] . '" style="max-width:70px;"></td>';
                $this->data['html'] .= '<td class="context-menu-selected-row">(' . $row['id'] . ') - ' . $row['title_mn'] . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row text-center">' . dateLastModified(array('createdDate' => $row['created_date'], 'modifiedDate' => $row['modified_date'])) . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row text-center">' . (intval($row['is_active']) == 1 ? '<span class="label bg-teal dropdown-toggle">Идэвхтэй</span>' : '<span class="label label-danger">Идэвхгүй</span>') . '</td>';
                $this->data['html'] .= '<td class="text-center">';
                $this->data['html'] .= '<ul class="icons-list">';
                if (($this->auth->our->update == 1 and $row['created_user_id'] == $this->session->adminUserId) or ( $this->auth->your->update == 1 and $row['created_user_id'] != $this->session->adminUserId)) {
                    $this->data['html'] .= '<li><a href="' . Sservice::$path . 'edit/' . $param['modId'] . '/' . $row['id'] . '"><i class="icon-pencil7"></i></a></li>';
                } else {
                    $this->data['html'] .= '<li><a href="javascript:;" class="disabled"><i class="icon-pencil7 disabled"></i></a></li>';
                }
                if (($this->auth->our->update == 1 and $row['created_user_id'] == $this->session->adminUserId) or ( $this->auth->your->update == 1 and $row['created_user_id'] != $this->session->adminUserId)) {
                    $this->data['html'] .= '<li><a href="javascript:;" onclick="_removeItem({id:' . $row['id'] . ', modId:' . $row['mod_id'] . '});"><i class="icon-trash"></i></a></li>';
                } else {
                    $this->data['html'] .= '<li><a href="javascript:;" class="disabled"><i class="icon-trash"></i></a></li>';
                }

                $this->data['html'] .= '</ul>';
                $this->data['html'] .= '</td>';
                $this->data['html'] .= '</tr>';
                $i++;
            }

            $this->data['html'] .= '</tbody>';
            $this->data['html'] .= '</table>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<div class="panel-footer">';
            $this->data['html'] .= '<div class="heading-elements">';
            $this->data['html'] .= '<span class="heading-text text-semibold"></span>';
            $this->data['html'] .= '<div class="heading-btn pull-right">';
            $this->data['html'] .= $param['paginationHtml'];
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<a class="heading-elements-toggle"><i class="icon-more"></i></a>';
            $this->data['html'] .= '</div>';
        } else {
            $this->data['html'] .= '<div class="panel-body">';
            $this->data['html'] .= '<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Бичлэг байхгүй</div>';
            $this->data['html'] .= '</div>';
        }


        $this->data['html'] .= '</div>';
        $this->data['html'] .= form_close();
        return $this->data['html'];
    }

    public function insert_model($param = array()) {

        $this->param = array();

        $data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'parent_id' => 0,
                'show_pic_outside' => $this->input->post('showPicOutside'),
                'show_pic_inside' => $this->input->post('showPicInside'),
                'pic' => $param['pic'],
                'pic_vertical' => '',
                'title_mn' => $this->input->post('titleMn'),
                'link_title_mn' => $this->input->post('linkTitleMn'),
                'intro_text_mn' => $this->input->post('introTextMn'),
                'full_text_mn' => $this->input->post('fullTextMn'),
                'page_title_mn' => $this->input->post('pageTitleMn'),
                'meta_key_mn' => $this->input->post('metaKeyMn'),
                'meta_desc_mn' => $this->input->post('metaDescMn'),
                'h1_text_mn' => $this->input->post('h1TextMn'),
                'show_date' => $this->input->post('showDate'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => '0000-00-00 00:00:00',
                'is_active_date' => $this->input->post('isActiveDate') . ' ' . $this->input->post('isActiveTime'),
                'show_author' => $this->input->post('showAuthor'),
                'author_id' => $this->input->post('authorId'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'show_comment' => $this->input->post('showComment'),
                'comment_count_mn' => 0,
                'show_click' => $this->input->post('showClick'),
                'click_mn' => $this->input->post('clickMn'),
                'click_real_mn' => 0,
                'is_active_mn' => $this->input->post('isActiveMn'),
                'order_num' => getOrderNum(array('table' => 'content', 'field' => 'order_num')),
                'show_social' => $this->input->post('showSocial'),
                'param' => json_encode($this->param),
                'price' => 0,
                'price_sale' => 0,
                'type' => 1,
                'theme_layout_id' => $this->input->post('themeLayoutId'),
                'is_active_en' => $this->input->post('isActiveEn'),
                'title_en' => $this->input->post('titleEn'),
                'link_title_en' => $this->input->post('linkTitleEn'),
                'intro_text_en' => $this->input->post('introTextEn'),
                'full_text_en' => $this->input->post('fullTextEn'),
                'page_title_en' => $this->input->post('pageTitleEn'),
                'meta_key_en' => $this->input->post('metaKeyEn'),
                'meta_desc_en' => $this->input->post('metaDescEn'),
                'h1_text_en' => $this->input->post('h1TextEn'),
                'comment_count_en' => 0,
                'click_en' => $this->input->post('clickEn'),
                'click_real_en' => 0,
                'partner_id' => ($this->session->userdata['adminAccessTypeId'] == 1 ? $this->input->post('partnerId') : $this->session->userdata['adminPartnerId'])
            )
        );
        $result = $this->db->insert_batch($this->db->dbprefix . 'content', $data);

        if ($result) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {

        $this->param = array();
        if ($this->input->post('organizationId')) {
            foreach ($this->input->post('organizationId') as $key => $value) {
                $this->param['organization']['org' . $this->input->post('organizationId[' . $key . ']')] = $this->input->post('organizationCheckValue[' . $key . ']');
            }
        }
        $data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'parent_id' => 0,
            'show_pic_outside' => $this->input->post('showPicOutside'),
            'show_pic_inside' => $this->input->post('showPicInside'),
            'pic' => $param['pic'],
            'title_mn' => $this->input->post('titleMn'),
            'link_title_mn' => $this->input->post('linkTitleMn'),
            'intro_text_mn' => $this->input->post('introTextMn'),
            'full_text_mn' => $this->input->post('fullTextMn'),
            'page_title_mn' => $this->input->post('pageTitleMn'),
            'meta_key_mn' => $this->input->post('metaKeyMn'),
            'meta_desc_mn' => $this->input->post('metaDescMn'),
            'h1_text_mn' => $this->input->post('h1TextMn'),
            'show_date' => $this->input->post('showDate'),
            'modified_date' => date('Y-m-d H:i:s'),
            'is_active_date' => $this->input->post('isActiveDate') . ' ' . $this->input->post('isActiveTime'),
            'show_author' => $this->input->post('showAuthor'),
            'author_id' => $this->input->post('authorId'),
            'modified_user_id' => $this->session->adminUserId,
            'show_comment' => $this->input->post('showComment'),
            'show_click' => $this->input->post('showClick'),
            'click_mn' => $this->input->post('clickMn'),
            'is_active_mn' => $this->input->post('isActiveMn'),
            'order_num' => $this->input->post('orderNum'),
            'show_social' => $this->input->post('showSocial'),
            'param' => json_encode($this->param),
            'theme_layout_id' => $this->input->post('themeLayoutId'),
            'is_active_en' => $this->input->post('isActiveEn'),
            'title_en' => $this->input->post('titleEn'),
            'link_title_en' => $this->input->post('linkTitleEn'),
            'intro_text_en' => $this->input->post('introTextEn'),
            'full_text_en' => $this->input->post('fullTextEn'),
            'page_title_en' => $this->input->post('pageTitleEn'),
            'meta_key_en' => $this->input->post('metaKeyEn'),
            'meta_desc_en' => $this->input->post('metaDescEn'),
            'h1_text_en' => $this->input->post('h1TextEn'),
            'click_en' => $this->input->post('clickEn')
        );
        
        if ($this->session->userdata['adminAccessTypeId'] == 1) {
            $data['partner_id'] = $this->input->post('partnerId');
        }
        $this->db->where('id', $this->input->post('id'));
        $result = $this->db->update($this->db->dbprefix . 'content', $data);
        if ($result) {
            $result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $result;
    }

    public function isActive_model() {
        $data = array(
            'is_active' => $this->input->post('isActive')
        );
        $this->db->where('id', $this->input->post('id'));
        $result = $this->db->update($this->db->dbprefix . 'content', $data);
        if ($result) {
            $result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $result;
    }

    public function delete_model() {
        $data = $this->input->post('id');
        foreach ($data as $key => $id) {
            $this->db->where('id', $this->input->post('id[' . $key . ']'));
            $query = $this->db->get($this->db->dbprefix . 'content');
            $result = $query->result();
            $row = (Array) $result['0'];

            removeImageSource(array('fieldName' => $row['pic'], 'path' => UPLOADS_CONTENT_PATH));
            generateUrl(array('modId' => $row['mod_id'], 'contId' => $row['id'], 'mode' => 'delete'));

            $this->db->where('id', $this->input->post('id[' . $key . ']'));
            $this->db->delete($this->db->dbprefix . 'content');
        }
        return array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай устгалаа');
    }

    public function updateVerticalImage_model($param = array()) {
        $data = array(
            'pic_vertical' => $param['pic']
        );
        $this->db->where('id', $param['id']);
        if ($this->db->update($this->db->dbprefix . 'content', $data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа', 'picVertical' => $param['pic']);
        } else {
            return array('status' => 'error', 'message' => 'Алдаа гарлаа', 'picVertical' => $param['pic']);
        }
    }

    public function mediaAddFormData_model() {
        return array(
            'id' => 0,
            'mod_id' => 0,
            'cont_id' => 0,
            'title_mn' => '',
            'title_en' => '',
            'intro_text_mn' => '',
            'attach_file_mn' => '',
            'is_active_mn' => 1,
            'is_active_en' => 0,
            'order_num' => getOrderNum(array('table' => 'content_media', 'field' => 'order_num')),
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'type' => 1,
            'created_user_id' => 0,
            'modified_user_id' => 0,
            'intro_text_en' => '',
            'attach_file_en' => '',
            'file_type_mn' => '',
            'file_type_en' => ''
        );
    }

    public function mediaEditFormData_model($param = array('id')) {
        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cont_id,
                title_mn,
                title_en,
                intro_text_mn,
                attach_file_mn,
                is_active_mn,
                is_active_en,
                order_num,
                created_date,
                modified_date,
                type,
                created_user_id,
                modified_user_id,
                intro_text_en,
                attach_file_en,
                file_type_mn,
                file_type_en
            FROM `gaz_content_media` 
            WHERE id = ' . $param['id']);
        $this->result = $this->query->result();

        if (count($this->result) > 0) {
            return (array) $this->result['0'];
        }
        return self::mediaAddFormData();
    }

    public function mediaList_model($param = array('modId' => 0, 'contId' => 0)) {

        $this->data = array();

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cont_id,
                intro_text_mn,
                attach_file_mn,
                title_mn,
                title_en,
                is_active_mn,
                is_active_en,
                order_num,
                created_date,
                modified_date,
                type,
                created_user_id,
                modified_user_id,
                intro_text_en,
                attach_file_en,
                file_type_mn AS file_type
            FROM `gaz_content_media` 
            WHERE mod_id = ' . $param['modId'] . ' AND cont_id = ' . $param['contId'] . ' AND type IN(' . $param['type'] . ')
            ORDER BY id DESC');

        $this->data['html'] = '<div class="pull-left">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';
        $this->data['html'] .= '<li style="padding-right:10px;"><a href="javascript:;" onclick="_formMediaDialog({modId: ' . $param['modId'] . ', contId: ' . $param['contId'] . ', id:0, mode:\'mediaInsert\', elem:this});" class="btn btn-info btn-rounded btn-xs"><i class="fa fa-plus"></i> Нэмэх</a></li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="clearfix"></div>';

        if ($this->query->num_rows() > 0) {

            $this->data['html'] .= '<div class="table-responsive">';
            $this->data['html'] .= '<table class="table table-bordered table-custom">';
            $this->data['html'] .= '<thead>';
            $this->data['html'] .= '<tr>';
            $this->data['html'] .= '<th style="width:30px;">#</th>';
            $this->data['html'] .= '<th style="width:100px;">Зураг</th>';
            $this->data['html'] .= '<th>Гарчиг</th>';
            $this->data['html'] .= '<th style="width:100px;">Огноо</th>';
            $this->data['html'] .= '<th style="width:100px;">Төлөв</th>';
            $this->data['html'] .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';
            $i = 1;
            foreach ($this->query->result() as $row) {

                $this->data['html'] .= '<tr data-mod-id="' . $row->mod_id . '"  data-cont-id="' . $row->cont_id . '" data-id="' . $row->id . '">';
                $this->data['html'] .= '<td>' . $i . '</td>';

                $imagePath = '/assets/images/icon-youtube.png';

                if ($row->type == 1) {
                    $imagePath = UPLOADS_CONTENT_PATH . CROP_SMALL . $row->attach_file_mn;
                } else if ($row->type == 2) {
                    $imagePath = '/assets/images/icon-file.png';
                }
                $this->data['html'] .= '<td class="content-media-context-menu-selected-row text-center"><img src="' . $imagePath . '" style="max-width:70px;"></td>';
                $this->data['html'] .= '<td class="content-media-context-menu-selected-row">' . $row->intro_text_mn . '</td>';
                $this->data['html'] .= '<td class="content-media-context-menu-selected-row text-center">' . dateLastModified(array('createdDate' => $row->created_date, 'modifiedDate' => $row->modified_date)) . '</td>';
                $this->data['html'] .= '<td class="content-media-context-menu-selected-row text-center">' . (intval($row->is_active_mn) == 1 ? '<span class="label bg-teal dropdown-toggle">Идэвхтэй</span>' : '<span class="label label-danger">Идэвхгүй</span>') . '</td>';
                $this->data['html'] .= '<td class="text-center">';
                $this->data['html'] .= '<ul class="icons-list">';
                $this->data['html'] .= '<li><a href="javascript:;"  onclick="_formMediaDialog({modId:' . $row->mod_id . ', contId:' . $row->cont_id . ', id:' . $row->id . ', mode:\'mediaUpdate\'});"><i class="icon-pencil7"></i></a></li>';
                $this->data['html'] .= '<li><a href="javascript:;" onclick="_removeMeidaItem({modId:' . $row->mod_id . ', contId:' . $row->cont_id . ', id:' . $row->id . '});"><i class="icon-trash"></i></a></li>';
                $this->data['html'] .= '</ul>';
                $this->data['html'] .= '</td>';
                $this->data['html'] .= '</tr>';
                $i++;
            }

            $this->data['html'] .= '</tbody>';
            $this->data['html'] .= '</table>';
            $this->data['html'] .= '</div>';
        } else {

            $this->data['html'] .= '<br><div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Бичлэг байхгүй</div>';
        }
        return $this->data['html'];
    }

    public function mediaInsert_model($param = array('fileNameMn' => '', 'fileNameEn' => '')) {
        $data = array(array(
                'mod_id' => $this->input->post('modId'),
                'cont_id' => $this->input->post('contId'),
                'title_mn' => $this->input->post('titleMn'),
                'title_en' => $this->input->post('titleEn'),
                'intro_text_mn' => $this->input->post('introTextMn'),
                'intro_text_en' => $this->input->post('introTextEn'),
                'attach_file_mn' => $param['fileNameMn'],
                'attach_file_en' => $param['fileNameEn'],
                'is_active_mn' => $this->input->post('isActiveMn'),
                'is_active_en' => $this->input->post('isActiveEn'),
                'order_num' => getOrderNum(array('table' => 'content_media', 'field' => 'order_num')),
                'created_date' => date('Y-m-d H:i:s'),
                'type' => $this->input->post('type'),
                'file_type_mn' => $param['fileTypeMn'],
                'file_type_en' => $param['fileTypeEn'],
                'created_user_id' => $this->session->adminUserId
        ));
        
        if ($this->db->insert_batch($this->db->dbprefix . 'content_media', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function mediaUpdate_model($param = array('fileNameMn' => '', 'fileNameEn' => '')) {
        $data = array(
            'mod_id' => $this->input->post('modId'),
            'cont_id' => $this->input->post('contId'),
            'title_mn' => $this->input->post('titleMn'),
            'title_en' => $this->input->post('titleEn'),
            'intro_text_mn' => $this->input->post('introTextMn'),
            'attach_file_mn' => $param['fileNameMn'],
            'intro_text_en' => $this->input->post('introTextEn'),
            'attach_file_en' => $param['fileNameEn'],
            'is_active_mn' => $this->input->post('isActiveMn'),
            'is_active_en' => $this->input->post('isActiveEn'),
            'order_num' => $this->input->post('orderNum'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'type' => $this->input->post('type'),
            'file_type_mn' => $param['fileTypeMn'],
            'file_type_en' => $param['fileTypeEn']
        );
        $this->db->where('id', $this->input->post('id'));
        
        if ($this->db->update($this->db->dbprefix . 'content_media', $data)) {
            $result = array('status' => 'success', 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $result = array('status' => 'success', 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $result;
    }

    public function mediaDelete_model() {

        foreach ($this->input->post('id') as $key => $id) {
            $mediaId = $this->input->post('id[' . $key . ']');
            $this->db->where('id', $mediaId);
            $query = $this->db->get($this->db->dbprefix . 'content_media');
            $result = $query->result();
            $row = (Array) $result['0'];

            removeImageSource(array('fieldName' => $row['attach_file_mn'], 'path' => UPLOADS_CONTENT_PATH));
            removeImageSource(array('fieldName' => $row['attach_file_en'], 'path' => UPLOADS_CONTENT_PATH));

            $this->db->where('id', $mediaId);
            $this->db->delete($this->db->dbprefix . 'content_media');
        }
        return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
    }

    public function tourBackground_model($param = array()) {

        $this->data = array(
            'tour_background_mn' => $this->input->post('tourBackgroundMn'),
            'tour_background_en' => $this->input->post('tourBackgroundEn')
        );
        $this->db->where('id', $this->input->post('id'));
        $result = $this->db->update($this->db->dbprefix . 'content', $this->data);
        if ($result) {
            $result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $result;
    }

    public function tourIncludedService_model($param = array()) {
        $this->tourParam = array();
        foreach ($this->input->post('imageIconName') as $iconkey => $iconRow) {
            array_push($this->tourParam, array('icon' => $this->input->post('imageIconName[' . $iconkey . ']'), 'title_mn' => $this->input->post('imageIconTitleMn[' . $iconkey . ']'), 'title_en' => $this->input->post('imageIconTitleEn[' . $iconkey . ']'), 'isChecked' => $this->input->post('imageIconIsChecked[' . $iconkey . ']')));
        }

        $this->data = array(
            'tour_days' => $this->input->post('tourDays'),
            'tour_price' => $this->input->post('tourPrice'),
            'tour_included_service_mn' => $this->input->post('tourIncludedServiceMn'),
            'tour_included_service_en' => $this->input->post('tourIncludedServiceEn'),
            'tour_param_mn' => json_encode($this->tourParam),
            'tour_param_en' => json_encode($this->tourParam)
        );
        $this->db->where('id', $this->input->post('id'));
        $result = $this->db->update($this->db->dbprefix . 'content', $this->data);
        if ($result) {
            $result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $result;
    }

    public function tourItineraryList_model($param = array('modId' => 0, 'catId' => 0)) {

        $this->data = array();

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cont_id,
                title_mn,
                title_en,
                intro_text_mn,
                attach_file_mn,
                is_active_mn,
                is_active_en,
                order_num,
                created_date,
                modified_date,
                type,
                created_user_id,
                modified_user_id,
                intro_text_en,
                attach_file_en
            FROM `gaz_content_media` 
            WHERE mod_id = ' . $param['modId'] . ' AND cont_id = ' . $param['contId'] . ' AND type IN(5)
            ORDER BY id DESC');

        $this->result = $this->query->result();

        $this->data['html'] = '<div class="pull-left">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';
        $this->data['html'] .= '<li style="padding-right:10px;"><a href="javascript:;" onclick="_formTourItineraryDialog({modId: ' . $param['modId'] . ', contId: ' . $param['contId'] . ', id:0, mode:\'tourItineraryInsert\', elem:this});" class="btn btn-info btn-rounded btn-xs"><i class="fa fa-plus"></i> Нэмэх</a></li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="clearfix"></div>';

        if (count($this->result) > 0) {

            $this->data['html'] .= '<div class="table-responsive">';
            $this->data['html'] .= '<table class="table table-bordered table-custom">';
            $this->data['html'] .= '<thead>';
            $this->data['html'] .= '<tr>';
            $this->data['html'] .= '<th style="width:30px;">#</th>';
            $this->data['html'] .= '<th>Гарчиг</th>';
            $this->data['html'] .= '<th style="width:100px;">Огноо</th>';
            $this->data['html'] .= '<th style="width:100px;">Төлөв</th>';
            $this->data['html'] .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';
            $i = 1;
            foreach ($this->result as $value) {
                $row = (array) $value;
                $this->data['html'] .= '<tr data-mod-id="' . $row['mod_id'] . '" data-cont-id="' . $row['cont_id'] . '" data-id="' . $row['id'] . '">';
                $this->data['html'] .= '<td>' . $i . '</td>';

                $this->data['html'] .= '<td class="tour-itinerary-context-menu-selected-row"><strong>' . $row['title_mn'] . '</strong> <br>' . $row['intro_text_mn'] . '</td>';
                $this->data['html'] .= '<td class="tour-itinerary-context-menu-selected-row text-center">' . dateLastModified(array('createdDate' => $row['created_date'], 'modifiedDate' => $row['modified_date'])) . '</td>';
                $this->data['html'] .= '<td class="tour-itinerary-context-menu-selected-row text-center">' . (intval($row['is_active_mn']) == 1 ? '<span class="label bg-teal dropdown-toggle">Идэвхтэй</span>' : '<span class="label label-danger">Идэвхгүй</span>') . '</td>';
                $this->data['html'] .= '<td class="text-center">';
                $this->data['html'] .= '<ul class="icons-list">';
                $this->data['html'] .= '<li><a href="javascript:;"  onclick="_formTourItineraryDialog({modId:' . $row['mod_id'] . ', contId:' . $row['cont_id'] . ', id:' . $row['id'] . ', mode:\'tourItineraryUpdate\'});"><i class="icon-pencil7"></i></a></li>';
                $this->data['html'] .= '<li><a href="javascript:;" onclick="_removeTourItineraryItem({modId:' . $row['mod_id'] . ', contId:' . $row['cont_id'] . ', id:' . $row['id'] . '});"><i class="icon-trash"></i></a></li>';
                $this->data['html'] .= '</ul>';
                $this->data['html'] .= '</td>';
                $this->data['html'] .= '</tr>';
                $i++;
            }

            $this->data['html'] .= '</tbody>';
            $this->data['html'] .= '</table>';
            $this->data['html'] .= '</div>';
        } else {

            $this->data['html'] .= '<br><div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Бичлэг байхгүй</div>';
        }
        return $this->data['html'];
    }

    public function tourItineraryInsert_model($param = array()) {
        $data = array(array(
                'mod_id' => $this->input->post('modId'),
                'cont_id' => $this->input->post('contId'),
                'title_mn' => $this->input->post('titleMn'),
                'title_en' => $this->input->post('titleEn'),
                'intro_text_mn' => $this->input->post('introTextMn'),
                'intro_text_en' => $this->input->post('introTextEn'),
                'is_active_mn' => $this->input->post('isActiveMn'),
                'is_active_en' => $this->input->post('isActiveEn'),
                'order_num' => getOrderNum(array('table' => 'content_media', 'field' => 'order_num')),
                'created_date' => date('Y-m-d H:i:s'),
                'type' => $this->input->post('type'),
                'created_user_id' => $this->session->adminUserId
        ));
        $result = $this->db->insert_batch($this->db->dbprefix . 'content_media', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function tourItineraryUpdate_model($param = array()) {
        $data = array(
            'mod_id' => $this->input->post('modId'),
            'cont_id' => $this->input->post('contId'),
            'title_mn' => $this->input->post('titleMn'),
            'title_en' => $this->input->post('titleEn'),
            'intro_text_mn' => $this->input->post('introTextMn'),
            'intro_text_en' => $this->input->post('introTextEn'),
            'is_active_mn' => $this->input->post('isActiveMn'),
            'is_active_en' => $this->input->post('isActiveEn'),
            'order_num' => getOrderNum(array('table' => 'content_media', 'field' => 'order_num')),
            'created_date' => date('Y-m-d H:i:s'),
            'type' => $this->input->post('type'),
            'created_user_id' => $this->session->adminUserId
        );
        $this->db->where('id', $this->input->post('id'));
        $result = $this->db->update($this->db->dbprefix . 'content_media', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function tourItineraryDelete_model() {

        foreach ($this->input->post('id') as $key => $id) {
            $mediaId = $this->input->post('id[' . $key . ']');
            $this->db->where('id', $mediaId);
            $query = $this->db->get($this->db->dbprefix . 'content_media');
            $result = $query->result();
            $row = (Array) $result['0'];

            $this->db->where('id', $mediaId);
            $this->db->delete($this->db->dbprefix . 'content_media');
        }
        return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
    }

    public function timeLineAddFormData_model() {
        return array(
            'id' => 0,
            'mod_id' => 0,
            'cont_id' => 0,
            'title_mn' => '',
            'title_en' => '',
            'date_mn' => date('Y-m-d H:i:s'),
            'date_en' => date('Y-m-d H:i:s'),
            'intro_text_mn' => '',
            'attach_file_mn' => '',
            'is_active_mn' => 1,
            'is_active_en' => 0,
            'order_num' => getOrderNum(array('table' => 'content_media', 'field' => 'order_num')),
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'type' => 7,
            'created_user_id' => 0,
            'modified_user_id' => 0,
            'intro_text_en' => '',
            'attach_file_en' => ''
        );
    }

    public function timeLineEditFormData_model($param = array('id')) {
        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cont_id,
                title_mn,
                title_en,
                date_mn,
                date_en,
                intro_text_mn,
                attach_file_mn,
                is_active_mn,
                is_active_en,
                order_num,
                created_date,
                modified_date,
                type,
                created_user_id,
                modified_user_id,
                intro_text_en,
                attach_file_en
            FROM `gaz_content_media` 
            WHERE id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            $this->result = $this->query->result();
            return (array) $this->result['0'];
        }
        return self::mediaAddFormData();
    }

    public function timeLineList_model($param = array('modId' => 0, 'contId' => 0)) {

        $this->data = array();

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cont_id,
                title_mn,
                title_en,
                date_mn,
                date_en,
                intro_text_mn,
                attach_file_mn,
                is_active_mn,
                is_active_en,
                order_num,
                created_date,
                modified_date,
                type,
                created_user_id,
                modified_user_id,
                intro_text_en,
                attach_file_en
            FROM `gaz_content_media` 
            WHERE mod_id = ' . $param['modId'] . ' AND cont_id = ' . $param['contId'] . ' AND type IN(' . $param['type'] . ')
            ORDER BY date_mn DESC');

        $this->data['html'] = '<div class="pull-left">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';
        $this->data['html'] .= '<li style="padding-right:10px;"><a href="javascript:;" onclick="_formTimeLineDialog({modId: ' . $param['modId'] . ', contId: ' . $param['contId'] . ', id:0, mode:\'timeLineInsert\', elem:this, type:7});" class="btn btn-info btn-rounded btn-xs"><i class="fa fa-plus"></i> Нэмэх</a></li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="clearfix"></div>';

        if ($this->query->num_rows() > 0) {

            $this->data['html'] .= '<div class="table-responsive">';
            $this->data['html'] .= '<table class="table table-bordered table-custom">';
            $this->data['html'] .= '<thead>';
            $this->data['html'] .= '<tr>';
            $this->data['html'] .= '<th style="width:30px;">#</th>';
            $this->data['html'] .= '<th style="width:100px;">Зураг</th>';
            $this->data['html'] .= '<th>Гарчиг</th>';
            $this->data['html'] .= '<th style="width:100px;">Огноо</th>';
            $this->data['html'] .= '<th style="width:100px;">Төлөв</th>';
            $this->data['html'] .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';
            $i = 1;
            foreach ($this->query->result() as $row) {
                $this->data['html'] .= '<tr data-mod-id="' . $row->mod_id . '"  data-cont-id="' . $row->cont_id . '" data-id="' . $row->id . '">';
                $this->data['html'] .= '<td>' . $i . '</td>';

                $imagePath = '/assets/images/icon-youtube.png';

                if ($row->type == 7) {
                    $imagePath = UPLOADS_CONTENT_PATH . CROP_SMALL . $row->attach_file_mn;
                }
                $this->data['html'] .= '<td class="content-timeline-context-menu-selected-row text-center"><img src="' . $imagePath . '" style="max-width:70px;"></td>';
                $this->data['html'] .= '<td class="content-timeline-context-menu-selected-row"><strong>' . $row->title_mn . ' - ' . $row->date_mn . '</strong><br>' . $row->intro_text_mn . '</td>';
                $this->data['html'] .= '<td class="content-timeline-context-menu-selected-row text-center">' . dateLastModified(array('createdDate' => $row->created_date, 'modifiedDate' => $row->modified_date)) . '</td>';
                $this->data['html'] .= '<td class="content-timeline-context-menu-selected-row text-center">' . (intval($row->is_active_mn) == 1 ? '<span class="label bg-teal dropdown-toggle">Идэвхтэй</span>' : '<span class="label label-danger">Идэвхгүй</span>') . '</td>';
                $this->data['html'] .= '<td class="text-center">';
                $this->data['html'] .= '<ul class="icons-list">';
                $this->data['html'] .= '<li><a href="javascript:;"  onclick="_formTimeLineDialog({modId:' . $row->mod_id . ', contId:' . $row->cont_id . ', id:' . $row->id . ', mode:\'timeLineUpdate\', type: ' . $row->type . '});"><i class="icon-pencil7"></i></a></li>';
                $this->data['html'] .= '<li><a href="javascript:;" onclick="_removeTimeLineItem({modId:' . $row->mod_id . ', contId:' . $row->cont_id . ', id:' . $row->id . ', type: ' . $row->type . '});"><i class="icon-trash"></i></a></li>';
                $this->data['html'] .= '</ul>';
                $this->data['html'] .= '</td>';
                $this->data['html'] .= '</tr>';
                $i++;
            }

            $this->data['html'] .= '</tbody>';
            $this->data['html'] .= '</table>';
            $this->data['html'] .= '</div>';
        } else {

            $this->data['html'] .= '<br><div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Бичлэг байхгүй</div>';
        }
        return $this->data['html'];
    }

    public function timeLineInsert_model($param = array('fileNameMn' => '', 'fileNameEn' => '')) {
        $this->data = array(
            array(
                'mod_id' => $this->input->post('modId'),
                'cont_id' => $this->input->post('contId'),
                'title_mn' => $this->input->post('titleMn'),
                'title_en' => $this->input->post('titleEn'),
                'date_mn' => $this->input->post('dateMn') . ' ' . $this->input->post('timeMn'),
                'date_en' => $this->input->post('dateEn') . ' ' . $this->input->post('timeEn'),
                'intro_text_mn' => $this->input->post('introTextMn'),
                'intro_text_en' => $this->input->post('introTextEn'),
                'attach_file_mn' => $param['fileNameMn'],
                'attach_file_en' => $param['fileNameEn'],
                'is_active_mn' => $this->input->post('isActiveMn'),
                'is_active_en' => $this->input->post('isActiveEn'),
                'order_num' => getOrderNum(array('table' => 'content_media', 'field' => 'order_num')),
                'created_date' => date('Y-m-d H:i:s'),
                'type' => $this->input->post('type'),
                'created_user_id' => $this->session->adminUserId
        ));
        if ($this->db->insert_batch($this->db->dbprefix . 'content_media', $this->data)) {
            return true;
        } else {
            return false;
        }
    }

    public function timeLineUpdate_model($param = array('fileNameMn' => '', 'fileNameEn' => '')) {
        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cont_id' => $this->input->post('contId'),
            'title_mn' => $this->input->post('titleMn'),
            'title_en' => $this->input->post('titleEn'),
            'date_mn' => $this->input->post('dateMn') . ' ' . $this->input->post('timeMn'),
            'date_en' => $this->input->post('dateEn') . ' ' . $this->input->post('timeEn'),
            'intro_text_mn' => $this->input->post('introTextMn'),
            'attach_file_mn' => $param['fileNameMn'],
            'intro_text_en' => $this->input->post('introTextEn'),
            'attach_file_en' => $param['fileNameEn'],
            'is_active_mn' => $this->input->post('isActiveMn'),
            'is_active_en' => $this->input->post('isActiveEn'),
            'order_num' => $this->input->post('orderNum'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'type' => $this->input->post('type')
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'content_media', $this->data)) {
            return array('status' => 'success', 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            return array('status' => 'success', 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
    }

    public function timeLineDelete_model() {

        foreach ($this->input->post('id') as $key => $id) {
            $mediaId = $this->input->post('id[' . $key . ']');
            $this->db->where('id', $mediaId);
            $query = $this->db->get($this->db->dbprefix . 'content_media');
            $result = $query->result();
            $row = (Array) $result['0'];

            removeImageSource(array('fieldName' => $row['attach_file_mn'], 'path' => UPLOADS_CONTENT_PATH));

            $this->db->where('id', $mediaId);
            $this->db->delete($this->db->dbprefix . 'content_media');
        }
        return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
    }

    public function ourTeamAddFormData_model() {
        return array(
            'id' => 0,
            'mod_id' => 0,
            'cont_id' => 0,
            'title_mn' => '',
            'title_en' => '',
            'date_mn' => date('Y-m-d H:i:s'),
            'date_en' => date('Y-m-d H:i:s'),
            'intro_text_mn' => '',
            'attach_file_mn' => '',
            'is_active_mn' => 1,
            'is_active_en' => 0,
            'order_num' => getOrderNum(array('table' => 'content_media', 'field' => 'order_num')),
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'type' => 6,
            'created_user_id' => 0,
            'modified_user_id' => 0,
            'intro_text_en' => '',
            'attach_file_en' => '',
            'position_mn' => '',
            'position_en' => '',
            'social' => self::socialDefault_model(),
            'param_mn' => json_encode(array(
                'lname' => array('label' => 'Эцгийн нэр', 'value' => '', 'show' => 1),
                'fname' => array('label' => 'Өөрийн нэр', 'value' => '', 'show' => 1),
                'position' => array('label' => 'Албан тушаал', 'value' => '', 'show' => 1))),
            'param_en' => json_encode(array(
                'lname' => array('label' => 'Эцгийн нэр', 'value' => '', 'show' => 1),
                'fname' => array('label' => 'Өөрийн нэр', 'value' => '', 'show' => 1),
                'position' => array('label' => 'Албан тушаал', 'value' => '', 'show' => 1)))
        );
    }

    public function ourTeamEditFormData_model($param = array('id')) {
        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cont_id,
                title_mn,
                title_en,
                date_mn,
                date_en,
                intro_text_mn,
                attach_file_mn,
                is_active_mn,
                is_active_en,
                order_num,
                created_date,
                modified_date,
                type,
                created_user_id,
                modified_user_id,
                intro_text_en,
                attach_file_en,
                social,
                param_mn,
                param_en
            FROM `' . $this->db->dbprefix . 'content_media` 
            WHERE id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            $this->result = $this->query->result();
            return (array) $this->result['0'];
        }
        return self::ourTeamAddFormData();
    }

    public function ourTeamList_model($param = array('modId' => 0, 'contId' => 0)) {

        $this->data = array();

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cont_id,
                title_mn,
                title_en,
                date_mn,
                date_en,
                intro_text_mn,
                attach_file_mn,
                is_active_mn,
                is_active_en,
                order_num,
                created_date,
                modified_date,
                type,
                created_user_id,
                modified_user_id,
                intro_text_en,
                attach_file_en,
                param_mn,
                param_en
            FROM `' . $this->db->dbprefix . 'content_media` 
            WHERE mod_id = ' . $param['modId'] . ' AND cont_id = ' . $param['contId'] . ' AND type = ' . $param['type'] . '
            ORDER BY order_num DESC');

        $this->data['html'] = '<div class="pull-left">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';
        $this->data['html'] .= '<li style="padding-right:10px;"><a href="javascript:;" onclick="_formOurTeamDialog({modId: ' . $param['modId'] . ', contId: ' . $param['contId'] . ', id:0, mode:\'ourTeamInsert\', elem:this, type:6});" class="btn btn-info btn-rounded btn-xs"><i class="fa fa-plus"></i> Нэмэх</a></li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="clearfix"></div>';

        if ($this->query->num_rows() > 0) {

            $this->data['html'] .= '<div class="table-responsive">';
            $this->data['html'] .= '<table class="table table-bordered table-custom">';
            $this->data['html'] .= '<thead>';
            $this->data['html'] .= '<tr>';
            $this->data['html'] .= '<th style="width:30px;">#</th>';
            $this->data['html'] .= '<th style="width:100px;">Зураг</th>';
            $this->data['html'] .= '<th>Гарчиг</th>';
            $this->data['html'] .= '<th style="width:100px;">Огноо</th>';
            $this->data['html'] .= '<th style="width:100px;">Төлөв</th>';
            $this->data['html'] .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';
            $i = 1;
            foreach ($this->query->result() as $row) {
                $this->data['html'] .= '<tr data-mod-id="' . $row->mod_id . '"  data-cont-id="' . $row->cont_id . '" data-id="' . $row->id . '">';
                $this->data['html'] .= '<td>' . $i . '</td>';

                $imagePath = '/assets/images/icon-youtube.png';

                
                    $imagePath = UPLOADS_CONTENT_PATH . $row->attach_file_mn;
                
                $this->data['html'] .= '<td class="content-ourteam-context-menu-selected-row text-center"><img src="' . $imagePath . '" style="max-width:70px;"></td>';
                $this->data['html'] .= '<td class="content-ourteam-context-menu-selected-row"><strong>' . $row->title_mn . '</strong><br>' . $row->intro_text_mn . '</td>';
                $this->data['html'] .= '<td class="content-ourteam-context-menu-selected-row text-center">' . dateLastModified(array('createdDate' => $row->created_date, 'modifiedDate' => $row->modified_date)) . '</td>';
                $this->data['html'] .= '<td class="content-ourteam-context-menu-selected-row text-center">' . (intval($row->is_active_mn) == 1 ? '<span class="label bg-teal dropdown-toggle">Идэвхтэй</span>' : '<span class="label label-danger">Идэвхгүй</span>') . '</td>';
                $this->data['html'] .= '<td class="text-center">';
                $this->data['html'] .= '<ul class="icons-list">';
                $this->data['html'] .= '<li><a href="javascript:;"  onclick="_formOurTeamDialog({modId:' . $row->mod_id . ', contId:' . $row->cont_id . ', id:' . $row->id . ', mode:\'ourTeamUpdate\', type:6});"><i class="icon-pencil7"></i></a></li>';
                $this->data['html'] .= '<li><a href="javascript:;" onclick="_removeOurTeamItem({modId:' . $row->mod_id . ', contId:' . $row->cont_id . ', id:' . $row->id . ', type:6});"><i class="icon-trash"></i></a></li>';
                $this->data['html'] .= '</ul>';
                $this->data['html'] .= '</td>';
                $this->data['html'] .= '</tr>';
                $i++;
            }

            $this->data['html'] .= '</tbody>';
            $this->data['html'] .= '</table>';
            $this->data['html'] .= '</div>';
        } else {

            $this->data['html'] .= '<br><div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Бичлэг байхгүй</div>';
        }
        return $this->data['html'];
    }

    public function ourTeamInsert_model($param = array('fileNameMn' => '', 'fileNameEn' => '')) {
        
        $this->socialQuery = array();
        foreach ($this->input->post('social') as $key => $value) {
            $this->socialQuery[$key] = array('class' => $this->input->post('socialClass[' . $key . ']'), 'label' => $this->input->post('socialLabel[' . $key . ']'), 'address' => $this->input->post('social[' . $key . ']'), 'show' => $this->input->post('showSocial[' . $key . ']'), 'class' => $this->input->post('class[' . $key . ']'));
        }

        $this->paramMnQuery = array();
        foreach ($this->input->post('paramMnLabel') as $key => $value) {
            $this->paramMnQuery[$key] = array('label' => $this->input->post('paramMnLabel[' . $key . ']'), 'value' => $this->input->post('paramMnValue[' . $key . ']'), 'show' => $this->input->post('paramMnShow[' . $key . ']'));
        }
        
        $this->paramEnQuery = array();
        foreach ($this->input->post('paramMnLabel') as $key => $value) {
            $this->paramEnQuery[$key] = array('label' => $this->input->post('paramEnLabel[' . $key . ']'), 'value' => $this->input->post('paramEnValue[' . $key . ']'), 'show' => $this->input->post('paramEnShow[' . $key . ']'));
        }
        
        $this->data = array(
            array(
                'mod_id' => $this->input->post('modId'),
                'cont_id' => $this->input->post('contId'),
                'title_mn' => $this->input->post('titleMn'),
                'title_en' => $this->input->post('titleEn'),
                'intro_text_mn' => $this->input->post('introTextMn'),
                'intro_text_en' => $this->input->post('introTextEn'),
                'attach_file_mn' => $param['fileNameMn'],
                'attach_file_en' => $param['fileNameEn'],
                'is_active_mn' => $this->input->post('isActiveMn'),
                'is_active_en' => $this->input->post('isActiveEn'),
                'order_num' => getOrderNum(array('table' => 'content_media', 'field' => 'order_num')),
                'created_date' => date('Y-m-d H:i:s'),
                'type' => $this->input->post('type'),
                'created_user_id' => $this->session->adminUserId,
                'social' => json_encode($this->socialQuery),
                'param_mn' => json_encode($this->paramMnQuery),
                'param_en' => json_encode($this->paramEnQuery)
        ));

        if ($this->db->insert_batch($this->db->dbprefix . 'content_media', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }
        
        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function ourTeamUpdate_model($param = array('fileNameMn' => '', 'fileNameEn' => '')) {
        
        $this->socialQuery = array();
        foreach ($this->input->post('social') as $key => $value) {
            $this->socialQuery[$key] = array('class' => $this->input->post('socialClass[' . $key . ']'), 'label' => $this->input->post('socialLabel[' . $key . ']'), 'address' => $this->input->post('social[' . $key . ']'), 'show' => $this->input->post('showSocial[' . $key . ']'), 'class' => $this->input->post('class[' . $key . ']'));
        }

        $this->paramMnQuery = array();
        foreach ($this->input->post('paramMnLabel') as $key => $value) {
            $this->paramMnQuery[$key] = array('label' => $this->input->post('paramMnLabel[' . $key . ']'), 'value' => $this->input->post('paramMnValue[' . $key . ']'), 'show' => $this->input->post('paramMnShow[' . $key . ']'));
        }
        
        $this->paramEnQuery = array();
        foreach ($this->input->post('paramMnLabel') as $key => $value) {
            $this->paramEnQuery[$key] = array('label' => $this->input->post('paramEnLabel[' . $key . ']'), 'value' => $this->input->post('paramEnValue[' . $key . ']'), 'show' => $this->input->post('paramEnShow[' . $key . ']'));
        }
        
        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cont_id' => $this->input->post('contId'),
            'title_mn' => $this->input->post('titleMn'),
            'title_en' => $this->input->post('titleEn'),
            'intro_text_mn' => $this->input->post('introTextMn'),
            'intro_text_en' => $this->input->post('introTextEn'),
            'attach_file_mn' => $param['fileNameMn'],
            'attach_file_en' => $param['fileNameEn'],
            'is_active_mn' => $this->input->post('isActiveMn'),
            'is_active_en' => $this->input->post('isActiveEn'),
            'order_num' => $this->input->post('orderNum'),
            'created_date' => date('Y-m-d H:i:s'),
            'type' => $this->input->post('type'),
            'created_user_id' => $this->session->adminUserId,
            'social' => json_encode($this->socialQuery),
            'param_mn' => json_encode($this->paramMnQuery),
            'param_en' => json_encode($this->paramEnQuery)
        );
        $this->db->where('id', $this->input->post('id'));
        if ($this->db->update($this->db->dbprefix . 'content_media', $this->data)) {
            return array('status' => 'success', 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...');
        } 
        return array('status' => 'success', 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function ourTeamDelete_model() {

        foreach ($this->input->post('id') as $key => $id) {
            $mediaId = $this->input->post('id[' . $key . ']');
            $this->db->where('id', $mediaId);
            $query = $this->db->get($this->db->dbprefix . 'content_media');
            $result = $query->result();
            $row = (Array) $result['0'];

            removeImageSource(array('fieldName' => $row['attach_file_mn'], 'path' => UPLOADS_CONTENT_PATH));

            $this->db->where('id', $mediaId);
            $this->db->delete($this->db->dbprefix . 'content_media');
        }
        return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
    }

    public function socialDefault_model() {
        return json_encode(array(
            'facebook' => array('label' => 'Facebook', 'address' => '', 'show' => 1, 'class' => 'facebook'),
            'twitter' => array('label' => 'Twitter', 'address' => '', 'show' => 1, 'class' => 'twitter'),
            'youtube' => array('label' => 'Youtube', 'address' => '', 'show' => 1, 'class' => 'youtube'),
            'soundCloud' => array('label' => 'Sound Cloud', 'address' => '', 'show' => 1, 'class' => 'soundcloud')));
    }
    
    public function attachFileAddFormData_model() {
        return array(
            'id' => 0,
            'mod_id' => 0,
            'cont_id' => 0,
            'title_mn' => '',
            'title_en' => '',
            'date_mn' => date('Y-m-d H:i:s'),
            'date_en' => date('Y-m-d H:i:s'),
            'intro_text_mn' => '',
            'attach_file_mn' => '',
            'is_active_mn' => 1,
            'is_active_en' => 0,
            'order_num' => getOrderNum(array('table' => 'content_media', 'field' => 'order_num')),
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'type' => 2,
            'created_user_id' => 0,
            'modified_user_id' => 0,
            'intro_text_en' => '',
            'attach_file_en' => ''
        );
    }

    public function attachFileEditFormData_model($param = array('id')) {
        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cont_id,
                title_mn,
                title_en,
                date_mn,
                date_en,
                intro_text_mn,
                attach_file_mn,
                is_active_mn,
                is_active_en,
                order_num,
                created_date,
                modified_date,
                type,
                created_user_id,
                modified_user_id,
                intro_text_en,
                attach_file_en
            FROM `gaz_content_media` 
            WHERE id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            $this->result = $this->query->result();
            return (array) $this->result['0'];
        }
        return self::attachFileAddFormData_model();
    }

    public function attachFileList_model($param = array('modId' => 0, 'contId' => 0)) {

        $this->data = array();

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cont_id,
                title_mn,
                title_en,
                date_mn,
                date_en,
                intro_text_mn,
                attach_file_mn,
                is_active_mn,
                is_active_en,
                order_num,
                created_date,
                modified_date,
                type,
                created_user_id,
                modified_user_id,
                intro_text_en,
                attach_file_en
            FROM `gaz_content_media` 
            WHERE mod_id = ' . $param['modId'] . ' AND cont_id = ' . $param['contId'] . ' AND type IN(' . $param['type'] . ')
            ORDER BY date_mn DESC');

        $this->data['html'] = '<div class="pull-left">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';
        $this->data['html'] .= '<li style="padding-right:10px;"><a href="javascript:;" onclick="_formAttachFileDialog({modId: ' . $param['modId'] . ', contId: ' . $param['contId'] . ', id:0, mode:\'attachFileInsert\', elem:this, type:2});" class="btn btn-info btn-rounded btn-xs"><i class="fa fa-plus"></i> Нэмэх</a></li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="clearfix"></div>';

        if ($this->query->num_rows() > 0) {

            $this->data['html'] .= '<div class="table-responsive">';
            $this->data['html'] .= '<table class="table table-bordered table-custom">';
            $this->data['html'] .= '<thead>';
            $this->data['html'] .= '<tr>';
            $this->data['html'] .= '<th style="width:30px;">#</th>';
            $this->data['html'] .= '<th style="width:100px;">Зураг</th>';
            $this->data['html'] .= '<th>Гарчиг</th>';
            $this->data['html'] .= '<th style="width:100px;">Огноо</th>';
            $this->data['html'] .= '<th style="width:100px;">Төлөв</th>';
            $this->data['html'] .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';
            $i = 1;
            foreach ($this->query->result() as $row) {
                $this->data['html'] .= '<tr data-mod-id="' . $row->mod_id . '"  data-cont-id="' . $row->cont_id . '" data-id="' . $row->id . '">';
                $this->data['html'] .= '<td>' . $i . '</td>';

                $imagePath = '/assets/images/icon-file.png';

                $this->data['html'] .= '<td class="content-attach-file-context-menu-selected-row text-center"><img src="' . $imagePath . '" style="max-width:70px;"></td>';
                $this->data['html'] .= '<td class="content-attach-file-context-menu-selected-row"><strong>' . $row->title_mn . '</strong><br>' . $row->intro_text_mn . '</td>';
                $this->data['html'] .= '<td class="content-attach-file-context-menu-selected-row text-center">' . dateLastModified(array('createdDate' => $row->created_date, 'modifiedDate' => $row->modified_date)) . '</td>';
                $this->data['html'] .= '<td class="content-attach-file-context-menu-selected-row text-center">' . (intval($row->is_active_mn) == 1 ? '<span class="label bg-teal dropdown-toggle">Идэвхтэй</span>' : '<span class="label label-danger">Идэвхгүй</span>') . '</td>';
                $this->data['html'] .= '<td class="text-center">';
                $this->data['html'] .= '<ul class="icons-list">';
                $this->data['html'] .= '<li><a href="javascript:;"  onclick="_formAttachFileDialog({modId:' . $row->mod_id . ', contId:' . $row->cont_id . ', id:' . $row->id . ', mode:\'attachFileUpdate\', type: ' . $row->type . '});"><i class="icon-pencil7"></i></a></li>';
                $this->data['html'] .= '<li><a href="javascript:;" onclick="_removeAttachFileItem({modId:' . $row->mod_id . ', contId:' . $row->cont_id . ', id:' . $row->id . ', type: ' . $row->type . '});"><i class="icon-trash"></i></a></li>';
                $this->data['html'] .= '</ul>';
                $this->data['html'] .= '</td>';
                $this->data['html'] .= '</tr>';
                $i++;
            }

            $this->data['html'] .= '</tbody>';
            $this->data['html'] .= '</table>';
            $this->data['html'] .= '</div>';
        } else {

            $this->data['html'] .= '<br><div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Бичлэг байхгүй</div>';
        }
        return $this->data['html'];
    }

    public function attachFileInsert_model($param = array('fileNameMn' => '', 'fileNameEn' => '')) {
        $this->data = array(
            array(
                'mod_id' => $this->input->post('modId'),
                'cont_id' => $this->input->post('contId'),
                'title_mn' => $this->input->post('titleMn'),
                'title_en' => $this->input->post('titleEn'),
                'date_mn' => $this->input->post('dateMn') . ' ' . $this->input->post('timeMn'),
                'date_en' => $this->input->post('dateEn') . ' ' . $this->input->post('timeEn'),
                'intro_text_mn' => $this->input->post('introTextMn'),
                'intro_text_en' => $this->input->post('introTextEn'),
                'attach_file_mn' => $param['fileNameMn'],
                'attach_file_en' => $param['fileNameEn'],
                'is_active_mn' => $this->input->post('isActiveMn'),
                'is_active_en' => $this->input->post('isActiveEn'),
                'order_num' => getOrderNum(array('table' => 'content_media', 'field' => 'order_num')),
                'created_date' => date('Y-m-d H:i:s'),
                'type' => $this->input->post('type'),
                'created_user_id' => $this->session->adminUserId
        ));
        if ($this->db->insert_batch($this->db->dbprefix . 'content_media', $this->data)) {
            return true;
        } else {
            return false;
        }
    }

    public function attachFileUpdate_model($param = array('fileNameMn' => '', 'fileNameEn' => '')) {
        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cont_id' => $this->input->post('contId'),
            'title_mn' => $this->input->post('titleMn'),
            'title_en' => $this->input->post('titleEn'),
            'date_mn' => $this->input->post('dateMn') . ' ' . $this->input->post('timeMn'),
            'date_en' => $this->input->post('dateEn') . ' ' . $this->input->post('timeEn'),
            'intro_text_mn' => $this->input->post('introTextMn'),
            'attach_file_mn' => $param['fileNameMn'],
            'intro_text_en' => $this->input->post('introTextEn'),
            'attach_file_en' => $param['fileNameEn'],
            'is_active_mn' => $this->input->post('isActiveMn'),
            'is_active_en' => $this->input->post('isActiveEn'),
            'order_num' => $this->input->post('orderNum'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'content_media', $this->data)) {
            return array('status' => 'success', 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            return array('status' => 'success', 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
    }

    public function attachFileDelete_model() {

        foreach ($this->input->post('id') as $key => $id) {
            $mediaId = $this->input->post('id[' . $key . ']');
            $this->db->where('id', $mediaId);
            $query = $this->db->get($this->db->dbprefix . 'content_media');
            $result = $query->result();
            $row = (Array) $result['0'];
            removeImageSource(array('fieldName' => $row['attach_file_mn'], 'path' => UPLOADS_FILE_PATH));
            removeImageSource(array('fieldName' => $row['attach_file_en'], 'path' => UPLOADS_FILE_PATH));

            $this->db->where('id', $mediaId);
            $this->db->delete($this->db->dbprefix . 'content_media');
        }
        return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
    }

}
