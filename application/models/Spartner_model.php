<?php

class Spartner_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();

        $this->load->model('Scategory_model', 'category');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Simage_model', 'simage');
        $this->load->model('Slog_model', 'slog');
        $this->load->model('Ssocial_model', 'social');
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => 24,
            'cat_id' => 0,
            'parent_id' => 0,
            'pic' => 'default.svg',
            'cover' => 'default.svg',
            'show_title' => 1,
            'title' => '',
            'link_title' => '',
            'intro_text' => '',
            'full_text' => '',
            'page_title' => '',
            'meta_key' => '',
            'meta_desc' => '',
            'h1_text' => '',
            'show_date' => '',
            'created_date' => '',
            'modified_date' => '',
            'is_active_date' => 1,
            'show_people' => 1,
            'people_id' => 0,
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'show_comment' => 1,
            'comment_count' => 0,
            'show_click' => 1,
            'click' => 0,
            'click_real' => 0,
            'is_active' => 1,
            'order_num' => getOrderNum(array('table' => 'partner', 'field' => 'order_num')),
            'show_social' => 1,
            'param' => '',
            'theme_layout_id' => 1,
            'phone' => '',
            'email' => '',
            'manager_name' => '',
            'manager_phone' => '',
            'color' => '#ffffff',
            'description' => '',
            'address' => '',
            'city_id' => 0,
            'soum_id' => 0,
            'street_id' => 0,
            'social' => $this->social->socialDefault_model(),
            'url' => getUID('url')
        )));
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                P.id,
                P.mod_id,
                P.cat_id,
                P.parent_id,
                (case when (P.pic is null or P.pic = \'\') then \'default.svg\' else concat(\'s_\', P.pic) end) as pic,
                (case when (P.cover is null or P.cover = \'\') then \'default.svg\' else concat(\'s_\', P.cover) end) as cover,
                P.show_title,
                P.title,
                P.link_title,
                P.intro_text,
                P.full_text,
                P.page_title,
                P.meta_key,
                P.meta_desc,
                P.h1_text,
                P.show_date,
                P.created_date,
                P.modified_date,
                P.is_active_date,
                P.show_people,
                P.people_id,
                P.created_user_id,
                P.modified_user_id,
                P.show_comment,
                P.comment_count,
                P.show_click,
                P.click,
                P.click_real,
                P.is_active,
                P.order_num,
                P.show_social,
                P.param,
                P.theme_layout_id,
                P.phone,
                P.email,
                P.manager_name,
                P.manager_phone,
                P.color,
                P.description,
                P.address,
                P.city_id,
                P.soum_id,
                P.street_id,
                P.social,
                U.url
            FROM `gaz_partner` AS P
            LEFT JOIN `gaz_url` AS U ON P.id = U.cont_id AND P.mod_id = U.mod_id
            WHERE P.id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function listsCount_model($param = array()) {
        $this->queryString = $this->getString = '';

        $this->auth = $param['auth'];

        if ($this->auth->our->read and $this->auth->your->read) {
            $this->queryString .= '';
        } elseif ($this->auth->our->read and !$this->auth->your->read) {
            $this->queryString .= ' AND P.created_user_id = ' . $this->session->adminUserId;
        } else {
            $this->queryString .= ' AND P.created_user_id = -1';
        }

//        if (isset($this->session->adminPartnerId)) {
//            $this->queryString .= ' AND K.user_partner_id IN (' . $this->partner->getChildPartners_model(array($this->session->adminPartnerId)) . ')';
//        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND P.cat_id = ' . $param['catId'];
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(P.title) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(P.link_title) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(P.intro_text) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(P.full_text) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        $this->query = $this->db->query('
            SELECT 
                P.id
            FROM `gaz_partner` AS P 
            WHERE P.parent_id = 0 ' . $this->queryString);

        return $this->query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $this->queryString = $this->getString = '';

        $this->auth = $param['auth'];

        if ($this->auth->our->read and $this->auth->your->read) {
            $this->queryString .= '';
        } elseif ($this->auth->our->read and !$this->auth->your->read) {
            $this->queryString .= ' AND P.created_user_id = ' . $this->session->adminUserId;
        } else {
            $this->queryString .= ' AND P.created_user_id = -1';
        }
//        if (isset($this->session->adminPartnerId)) {
//            $this->queryString .= ' AND K.user_partner_id IN (' . $this->partner->getChildPartners_model(array($this->session->adminPartnerId)) . ')';
//        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND P.cat_id = ' . $param['catId'];
            $this->getString .= form_hidden('catId', $param['catId']);
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(P.title) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(P.link_title) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(P.intro_text) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(P.full_text) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        $this->query = $this->db->query('
            SELECT 
                P.id,
                P.mod_id,
                P.cat_id,
                P.parent_id,
                P.pic,
                P.cover,
                P.show_title,
                P.title,
                P.link_title,
                P.intro_text,
                P.full_text,
                P.page_title,
                P.meta_key,
                P.meta_desc,
                P.h1_text,
                P.show_date,
                P.created_date,
                P.modified_date,
                P.is_active_date,
                P.show_people,
                P.people_id,
                P.created_user_id,
                P.modified_user_id,
                P.show_comment,
                P.comment_count,
                P.show_click,
                P.click,
                P.click_real,
                P.is_active,
                P.order_num,
                P.show_social,
                P.param,
                P.theme_layout_id,
                P.phone,
                P.email,
                P.manager_name,
                P.manager_phone,
                P.color,
                P.description,
                P.address,
                P.city_id,
                P.soum_id,
                P.street_id,
                P.social
            FROM `gaz_partner` AS P
            WHERE P.parent_id = 0 ' . $this->queryString . '
            ORDER BY P.title ASC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        $html = form_open('', array('class' => 'form-100 form-horizontal', 'id' => 'form-category-init', 'enctype' => 'multipart/form-data'));
        $html .= form_hidden('modId', $this->auth->modId);
        $html .= form_hidden('limit', $param['limit']);
        $html .= form_hidden('page', $param['page']);
        $html .= $this->getString;
        
        $html .= '<div class="card _cardSystem">';
        $html .= '<div class="card-header header-elements-inline">';
        $html .= '<h5 class="card-title">' . $param['title'] . '</h5>';
        $html .= '<div class="header-elements">';

        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="card-body">';
        $html .= '<div class="datagrid-toolbar">';
        $html .= '<table cellspacing="0" cellpadding="0">';
        $html .= '<tbody><tr>';
        if ($this->auth->our->create) {

            $html .= '<td><a href="javascript:;" class="l-btn l-btn-small l-btn-plain" group="" id="" onclick="_addFormPartner({elem: this});"><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">Шинэ (F2)</span><span class="l-btn-icon dg-icon-add">&nbsp;</span></span></a></td>';
        } else {

            $html .= '<td><a href="javascript:;" class="l-btn l-btn-small l-btn-plain disabled" group="" id=""><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">Шинэ (F2)</span><span class="l-btn-icon dg-icon-add">&nbsp;</span></span></a></td>';
        }

        $html .= '<td><div class="datagrid-btn-separator"></div></td>';

        $html .= '<td><a href="javascript:;" class="l-btn l-btn-small l-btn-plain" onclick="_advensedSearchPartner({elem: this});"><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">Дэлгэрэнгүй хайлт (Ctrl+f)</span><span class="l-btn-icon dg-icon-search">&nbsp;</span></span></a></td>';

        $html .= '</tr></tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= self::searchKeywordView_model();
        $html .= '</div>';

        if ($this->query->num_rows() > 0) {

            $html .= '<div class="table-responsive">';
            $html .= '<table class="table">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th style="width:30px;">#</th>';
            $html .= '<th>Байгууллага</th>';
            $html .= '<th style="width:200px;">Менежер</th>';
            $html .= '<th style="width:100px;">Утас</th>';
            $html .= '<th style="width:100px;">Мэйл</th>';
            $html .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody data-link="row" class="rowlink">';

            $i = 0;

            foreach ($this->query->result() as $key => $row) {
                $html .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '">';
                $html .= '<td>' . ++$i . '</td>';
                $html .= '<td class="context-menu-partner-selected-row" style="padding-left:' . $param['space'] . 'px;">' . $row->title . ' (' . $row->id . ')</td>';
                $html .= '<td class="context-menu-partner-selected-row">' . $row->manager_name . '</td>';
                $html .= '<td class="context-menu-partner-selected-row">' . $row->phone . '</td>';
                $html .= '<td class="context-menu-partner-selected-row">' . $row->email . '</td>';
                $html .= '<td class="text-center">';
//                $html .= '<ul class="icons-list">';
//
//                if (($this->auth->our->update and $row->created_user_id == $this->session->adminUserId) or ($this->auth->your->update and $row->created_user_id != $this->session->adminUserId)) {
//                    $html .= '<li><a href="javascript:;" onclick="_editFormPartner({elem: this, id:' . $row->id . '});" title="Засах" data-action="edit"></a></li>';
//                } else {
//                    $html .= '<li><a href="javascript:;" class="disabled" title="Засах" data-action="edit"></a></li>';
//                }
//
//                if (($this->auth->our->delete and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->delete and $row->created_user_id != $this->session->adminUserId)) {
//                    $html .= '<li><a href="javascript:;" onclick="_removePartner({elem: this, id:' . $row->id . '});" title="Устгах" data-action="delete"></a></li>';
//                } else {
//                    $html .= '<li><a href="javascript:;" class="disabled" title="Устгах" data-action="delete"></a></li>';
//                }
//
//                $html .= '</ul>';
                $html .= '</td>';
                $html .= '</tr>';

                $html .= self::listsChild_model(array('auth' => $param['auth'], 'parentId' => $row->id, 'space' => 50, 'autoNumber' => $i, 'moduleMenuId' => $param['moduleMenuId']));
            }


            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '</div>';
        } else {

            $html .= '<div class="card-empty">Бичлэг байхгүй</div>';
        }

        $html .= '<div class="card-footer d-sm-flex justify-content-sm-between align-items-sm-center">';
        $html .= $param['paginationHtml'];

        $html .= '</div>';
        $html .= '</div>';

        $html .= form_close();

        return $html;
    }

    public function listsChild_model($param = array('parentId' => 0, 'space' => 10, 'autoNumber' => 1)) {

        $html = '';
        $this->auth = $param['auth'];

        if ($this->auth->our->read and $this->auth->your->read) {
            $this->queryString .= '';
        } else if ($this->auth->our->read and !$this->auth->your->read) {
            $this->queryString .= ' AND C.created_user_id = ' . $this->session->adminUserId;
        } else {
            $this->queryString .= ' AND C.created_user_id = -1';
        }

        $this->query = $this->db->query('
            SELECT 
                P.id,
                P.mod_id,
                P.cat_id,
                P.parent_id,
                P.pic,
                P.cover,
                P.show_title,
                P.title,
                P.link_title,
                P.intro_text,
                P.full_text,
                P.page_title,
                P.meta_key,
                P.meta_desc,
                P.h1_text,
                P.show_date,
                P.created_date,
                P.modified_date,
                P.is_active_date,
                P.show_people,
                P.people_id,
                P.created_user_id,
                P.modified_user_id,
                P.show_comment,
                P.comment_count,
                P.show_click,
                P.click,
                P.click_real,
                P.is_active,
                P.order_num,
                P.show_social,
                P.param,
                P.theme_layout_id,
                P.phone,
                P.email,
                P.manager_name,
                P.manager_phone,
                P.color,
                P.description,
                P.address,
                P.city_id,
                P.soum_id,
                P.street_id,
                P.social
            FROM `gaz_partner` AS P
            WHERE P.parent_id = ' . $param['parentId'] . '
            ORDER BY P.title ASC');
        if ($this->query->num_rows() > 0) {

            $j = 1;
            foreach ($this->query->result() as $row) {
                $html .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '">';
                $html .= '<td>' . $param['autoNumber'] . '.' . $j . '</td>';
                $html .= '<td class="context-menu-partner-selected-row" style="padding-left:' . $param['space'] . 'px;">' . $row->title . ' (' . $row->id . ')</td>';
                $html .= '<td class="context-menu-partner-selected-row">' . $row->manager_name . '</td>';
                $html .= '<td class="context-menu-partner-selected-row">' . $row->manager_phone . '</td>';
                $html .= '<td class="context-menu-partner-selected-row">' . $row->email . '</td>';
                $html .= '<td class="text-center">';
//                $html .= '<ul class="icons-list">';
//
//                if (($this->auth->our->update and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->update and $row->created_user_id != $this->session->adminUserId)) {
//                    $html .= '<li><a href="javascript:;" onclick="_editFormPartner({elem: this, id:' . $row->id . '});" title="Засах" data-action="edit"></a></li>';
//                } else {
//                    $html .= '<li><a href="javascript:;" class="disabled" title="Засах" data-action="edit"></a></li>';
//                }
//
//                if (($this->auth->our->delete and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->delete and $row->created_user_id != $this->session->adminUserId)) {
//                    $html .= '<li><a href="javascript:;" onclick="_removePartner({elem: this, id:' . $row->id . '});" title="Устгах" data-action="delete"></a></li>';
//                } else {
//                    $html .= '<li><a href="javascript:;" class="disabled" title="Устгах" data-action="delete"></a></li>';
//                }
//
//                $html .= '</ul>';
                $html .= '</td>';
                $html .= '</tr>';
                $j++;
                $html .= self::listsChild_model(array('auth' => $param['auth'], 'parentId' => $row->id, 'space' => (intval($param['space']) + 30), 'autoNumber' => $j, 'moduleMenuId' => $param['moduleMenuId']));
            }
        }
        return $html;
    }

    public function insert_model($param = array('getUID' => 0)) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        $this->data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'parent_id' => $this->input->post('parentId'),
                'pic' => $this->input->post('pic'),
                'cover' => $this->input->post('cover'),
                'show_title' => $this->input->post('showTitle'),
                'title' => $this->input->post('title'),
                'link_title' => $this->input->post('linkTitle'),
                'page_title' => $this->input->post('pageTitle'),
                'meta_key' => $this->input->post('metaKey'),
                'meta_desc' => $this->input->post('metaDesc'),
                'h1_text' => $this->input->post('h1Text'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'show_comment' => $this->input->post('showComment'),
                'show_click' => $this->input->post('showClick'),
                'click' => $this->input->post('click'),
                'is_active' => $this->input->post('isActive'),
                'order_num' => $this->input->post('orderNum'),
                'show_social' => $this->input->post('showSocial'),
                'param' => '',
                'theme_layout_id' => $this->input->post('themeLayoutId'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'manager_name' => $this->input->post('managerName'),
                'manager_phone' => $this->input->post('managerPhone'),
                'color' => $this->input->post('color'),
                'description' => $this->input->post('description'),
                'address' => $this->input->post('address'),
                'city_id' => $this->input->post('cityId'),
                'soum_id' => $this->input->post('soumId'),
                'street_id' => $this->input->post('streetId'),
                'social' => $this->social->postToJson_model()));

        if ($this->db->insert_batch($this->db->dbprefix . 'partner', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array('pic' => '')) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_UPDATE,
            'data' => json_encode($_POST)));

        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'parent_id' => $this->input->post('parentId'),
            'pic' => ($this->input->post('pic') != '' ? $this->input->post('pic') : $this->input->post('oldPic')),
            'cover' => ($this->input->post('cover') != '' ? $this->input->post('cover') : $this->input->post('oldCover')),
            'show_title' => $this->input->post('showTitle'),
            'title' => $this->input->post('title'),
            'link_title' => $this->input->post('linkTitle'),
            'page_title' => $this->input->post('pageTitle'),
            'meta_key' => $this->input->post('metaKey'),
            'meta_desc' => $this->input->post('metaDesc'),
            'h1_text' => $this->input->post('h1Text'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'show_comment' => $this->input->post('showComment'),
            'show_click' => $this->input->post('showClick'),
            'click' => $this->input->post('click'),
            'is_active' => $this->input->post('isActive'),
            'order_num' => $this->input->post('orderNum'),
            'show_social' => $this->input->post('showSocial'),
            'theme_layout_id' => $this->input->post('themeLayoutId'),
            'phone' => $this->input->post('phone'),
            'email' => $this->input->post('email'),
            'manager_name' => $this->input->post('managerName'),
            'manager_phone' => $this->input->post('managerPhone'),
            'color' => $this->input->post('color'),
            'description' => $this->input->post('description'),
            'address' => $this->input->post('address'),
            'city_id' => $this->input->post('cityId'),
            'soum_id' => $this->input->post('soumId'),
            'street_id' => $this->input->post('streetId'),
            'social' => $this->social->postToJson_model());
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'partner', $this->data)) {
            return array('status' => 'success', 'message' => 'Өөрчлөлтийг амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Өөрчлөлтийн түүхийг хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {

        $idArray = $this->getChildPartners_model($this->input->post('id'));

        $this->query = $this->db->query('
            SELECT 
                P.*
            FROM `gaz_partner` AS P 
            WHERE P.id IN (' . $idArray . ')');

        if ($this->query->num_rows() > 0) {
            foreach ($this->query->result() as $keyDelete => $rowDelete) {

                $this->slog->log_model(array(
                    'modId' => $rowDelete->mod_id,
                    'createdUserId' => $this->session->adminUserId,
                    'type' => LOG_TYPE_DELETE,
                    'data' => json_encode($rowDelete)));

                $this->simage->removeUploadImage_model(array('uploadImage' => $rowDelete->pic, 'uploadPath' => '.' . UPLOADS_CONTENT_PATH));
                $this->simage->removeUploadImage_model(array('uploadImage' => $rowDelete->cover, 'uploadPath' => '.' . UPLOADS_CONTENT_PATH));
            }
            $this->db->where_in('id', explode(',', $idArray));
            if ($this->db->delete($this->db->dbprefix . 'partner')) {
                return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
            }
        }

        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function searchKeywordView_model() {
        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('protocolNumber')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('protocolNumber') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('researchTypeId')) {
            $this->nifsResearchType = $this->nifsResearchType->getData_model(array('selectedId' => $this->input->get('researchTypeId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsResearchType->title . '</span>';
            $this->showResetBtn = TRUE;
        }
        if ($this->input->get('catId')) {
            $this->cat = $this->category->getData_model(array('selectedId' => $this->input->get('catId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->cat->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('inDate') and $this->input->get('outDate')) {
            $this->string .= '<span class="label label-default label-rounded">Бүртгэсэн огноо: ' . $this->input->get('inDate') . '-' . $this->input->get('outDate') . '</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('inDate')) {
            $this->string .= '<span class="label label-default label-rounded">Бүртгэсэн огноо: ' . $this->input->get('inDate') . ' хойш</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('outDate')) {
            $this->string .= '<span class="label label-default label-rounded">Бүртгэсэн огноо: ' . $this->input->get('outDate') . ' өмнөх</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('typeId')) {
            $this->nifsCrimeType = $this->nifsCrimeType->getData_model(array('selectedId' => $this->input->get('typeId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCrimeType->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('motiveId')) {
            $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $this->input->get('motiveId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsMotiveData->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('partnerId')) {
            $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->get('partnerId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->partnerData->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('closeTypeId')) {
            $this->nifsCloseType = $this->nifsCloseType->getData_model(array('selectedId' => $this->input->get('closeTypeId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCloseType->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('latentPrintExpertId')) {
            $this->latentPrintExpertData = $this->user->getData_model(array('selectedId' => $this->input->get('latentPrintExpertId')));
            $this->string .= '<span class="label label-default label-rounded">' . mb_substr($this->latentPrintExpertData->lname, 0, 1, 'UTF-8') . '.' . $this->latentPrintExpertData->fname . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('expertId')) {
            $this->expertData = $this->user->getData_model(array('selectedId' => $this->input->get('expertId')));
            $this->string .= '<span class="label label-default label-rounded">' . mb_substr($this->expertData->lname, 0, 1, 'UTF-8') . '.' . $this->expertData->fname . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('createNumber')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('createNumber') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('solutionId')) {
            $this->crimeMotiveData = $this->getScrimeSolutionData_model(array('selectedId' => $this->input->get('solutionId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->crimeMotiveData->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('protocolInDate') and $this->input->get('protocolOutDate')) {
            $this->string .= '<span class="label label-default label-rounded">Тогтоолын огноо: ' . $this->input->get('protocolInDate') . '-' . $this->input->get('protocolOutDate') . '</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('protocolInDate')) {
            $this->string .= '<span class="label label-default label-rounded">Тогтоолын огноо: ' . $this->input->get('protocolInDate') . ' хойш</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('protocolOutDate')) {
            $this->string .= '<span class="label label-default label-rounded">Тогтоолын огноо: ' . $this->input->get('protocolOutDate') . ' өмнөх</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            //' . Scrime::$path . $param['path'] . '/' . $param['modId'] . '
            $this->string .= ' <a href="javascript:;" onclick="_initPartner({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return $this->string;
    }

    public function controlPartnerMultiListDropdown_model($param = array('modId' => 0, 'allInId' => 0, 'selectedId' => 0)) {

        $htmlExtra = $string = $class = '';

        if (!isset($param['name'])) {
            $param['name'] = 'partnerId';
        }

        if (!isset($param['readonly'])) {
            $param['readonly'] = 'false';
        }

        if (!isset($param['disabled'])) {
            $param['disabled'] = 'false';
        }

        if (!isset($param['required'])) {
            $param['required'] = 'false';
        }

        $query = $this->db->query('
            SELECT 
                NE.partner_id
            FROM `gaz_doc_detail` AS NE 
            WHERE NE.mod_id = ' . $param['modId'] . ' AND NE.cont_id = ' . $param['contId']);

        $numRows = $query->num_rows();
        if ($numRows > 0) {
            $i = 1;
            foreach ($query->result() as $key => $row) {

                if ($key > 0) {
                    $param['isDeleteButton'] = 1;
                }
                
                if ($i == 2) {
                    $htmlExtra .= '<div id="' . $param['initControlHtml'] . '">';
                }
                
                $htmlExtra .= '<div class="form-group row" data-expert-row="expert-row">';
                $htmlExtra .= form_label('Байгууллага', 'Байгууллага', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE));
                $htmlExtra .= '<div class="col-8">';

                $htmlExtra .= '<div class="input-group">';
                $htmlExtra .= '<span class="select2-group">';

                    $htmlExtra .= self::controlPartnerDropdown_model(array(
                            'modId' => 24,
                            'selectedId' => $row->partner_id,
                            'name' => $param['name'],
                            'readonly' => $param['readonly'],
                            'disabled' => $param['disabled'],
                            'required' => $param['required'],
                            'isExtraValue' => $param['isExtraValue']));

                $htmlExtra .= '</span>';
                $htmlExtra .= '<span class="input-group-append">';


                if (isset($param['isDeleteButton']) and $param['isDeleteButton'] == '1') {
                    $htmlExtra .= '<span class="input-group-text bg-primary border-primary text-white cursor-pointer" onclick="' . $param['removeFunction'] . '"><i class="icon-cancel-circle2"></i></span>';
                } else {
                    $htmlExtra .= '<span class="input-group-text bg-primary border-primary text-white cursor-pointer" onclick="' . $param['addFunction'] . '"><i class="icon-plus-circle2"></i></span>';
                }

                $htmlExtra .= '</span>';
                $htmlExtra .= '</div>';
                $htmlExtra .= '</div>';
                $htmlExtra .= '</div>';
                
                if ($numRows == 1) {
                    $htmlExtra .= '<div id="' . $param['initControlHtml'] . '">';
                }
                if ($i == $numRows) {
                    $htmlExtra .= '</div>';
                }

                $i++;
            }
            
        } else {
            $htmlExtra .= '<div class="form-group row" data-expert-row="expert-row">';
            $htmlExtra .= form_label('Шинжээч', 'Шинжээч', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE));
            $htmlExtra .= '<div class="col-8">';

            $htmlExtra .= '<div class="input-group">';
            $htmlExtra .= '<span class="select2-group">';

            $htmlExtra .= self::controlPartnerDropdown_model(array(
                        'modId' => 24,
                        'selectedId' => 0,
                        'name' => $param['name'],
                        'readonly' => $param['readonly'],
                        'disabled' => $param['disabled'],
                        'required' => $param['required'],
                        'isExtraValue' => $param['isExtraValue']));
            $htmlExtra .= '</span>';
            $htmlExtra .= '<span class="input-group-append">';


            if (isset($param['isDeleteButton']) and $param['isDeleteButton'] == '1') {
                $htmlExtra .= '<span class="input-group-text bg-primary border-primary text-white cursor-pointer" onclick="' . $param['removeFunction'] . '"><i class="icon-cancel-circle2"></i></span>';
            } else {
                $htmlExtra .= '<span class="input-group-text bg-primary border-primary text-white cursor-pointer" onclick="' . $param['addFunction'] . '"><i class="icon-plus-circle2"></i></span>';
            }

            $htmlExtra .= '</span>';
            $htmlExtra .= '</div>';
            $htmlExtra .= '</div>';
            $htmlExtra .= '</div>';
            $htmlExtra .= '<div id="' . $param['initControlHtml'] . '"></div>';
        }
        
        return $htmlExtra;
    }

    public function controlPartnerDropdown_model($param = array('modId' => 0, 'allInId' => 0, 'selectedId' => 0)) {

        $queryString = $html = $string = $class = '';

        if (isset($param['whereInId']) and $param['whereInId'] != '') {
            $queryString .= ' AND P.id IN (' . $param['whereInId'] . ')';
        }

        if (isset($param['modId']) and $param['modId'] != '') {
            $queryString .= ' AND P.mod_id = ' . $param['modId'];
        }

        if (!isset($param['name'])) {
            $param['name'] = 'partnerId';
        }

        if (isset($param['disabled']) and $param['disabled'] == 'true') {
            $string .= ' disabled="disabled"';
        }

        if (isset($param['required']) and $param['required'] == 'true') {
            $string .= ' required="true"';
            $class .= ' required';
        }

        if (isset($param['readonly']) and $param['readonly'] == 'true') {
            $string .= ' disabled="disabled"';
        }

        if (isset($param['tabindex'])) {
            $string .= ' tabindex="' . $param['tabindex'] . '"';
        }

        $query = $this->db->query('
            SELECT 
                P.id,
                P.title
            FROM `gaz_partner` AS P
            WHERE P.parent_id = 0 AND P.is_active = 1 ' . $queryString . '
            ORDER BY P.order_num ASC');

        $html .= '<select name="' . $param['name'] . '" id="' . $param['name'] . '" class="select2 form-control border-right-0" ' . $string . '>';
        $html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';
        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {
                $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';
                $html .= self::controlPartnerChildDropdown_model(array('selectedId' => $param['selectedId'], 'parentId' => $row->id, 'space' => '&nbsp; &nbsp;  &nbsp;  &nbsp; '));
            }
        }

        $html .= '</select>';

        return $html;
    }

    public function controlPartnerChildDropdown_model($param = array('parentId' => 0, 'selectedId' => 0, 'space' => '')) {

        $htmlChild = '';

        $query = $this->db->query('
            SELECT 
                P.id,
                P.title
            FROM `gaz_partner` AS P
            WHERE P.parent_id = ' . $param['parentId'] . ' AND P.is_active = 1
            ORDER BY P.order_num DESC');

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {
                $htmlChild .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $param['space'] . $row->title . '</option>';
                $htmlChild .= self::controlPartnerChildDropdown_model(array('selectedId' => $param['selectedId'], 'parentId' => $row->id, 'space' => '&nbsp; &nbsp; &nbsp;  &nbsp; '));
            }
        }

        return $htmlChild;
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                title,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id,
                city_id,
                soum_id,
                street_id
            FROM `gaz_partner`
            WHERE id = ' . $param['selectedId']);


        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

    public function controlPartnerParentMultiRowDropdown_model($param = array('modId' => 0, 'selectedId' => 0, 'id' => 0, 'parentId' => 0, 'space' => '')) {

        $html = '';
        $this->query = $this->db->query('
            SELECT 
                P.id,
                P.title
            FROM `gaz_partner` AS P
            WHERE P.parent_id = 0 AND P.is_active = 1 AND P.mod_id = ' . $param['modId'] . ' AND P.id != ' . $param['id'] . '
            ORDER BY P.order_num DESC');

        if ($this->query->num_rows() > 0) {

            $html .= '<select class="form-control" name="parentId" id="parentId" size="10" required="required">';

            $html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '>' . ' - Сонгох - </option>';


            foreach ($this->query->result() as $row) {

                $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $param['space'] . $row->title . '</option>';

                $html .= self::controlPartnerParentMultiChildRowDropdown_model(array('selectedId' => $param['selectedId'], 'id' => $param['id'], 'parentId' => $row->id, 'space' => '&nbsp; &nbsp;  &nbsp;  &nbsp; ' . $param['space']));
            }

            $html .= '</select>';
        }

        return $html;
    }

    public function controlPartnerParentMultiChildRowDropdown_model($param = array('selectedId' => 0, 'id' => 0, 'parentId' => 0, 'space' => '')) {

        $html = '';
        $this->query = $this->db->query('
            SELECT 
                P.id,
                P.title
            FROM `gaz_partner` AS P
            WHERE P.parent_id = ' . $param['parentId'] . ' AND P.is_active = 1 AND P.id != ' . $param['id'] . '
            ORDER BY P.order_num DESC');

        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $row) {

                $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $param['space'] . $row->title . '</option>';

                $html .= self::controlPartnerParentMultiChildRowDropdown_model(array('selectedId' => $param['selectedId'], 'id' => $param['id'], 'parentId' => $row->id, 'space' => '&nbsp; &nbsp;  &nbsp;  &nbsp; ' . $param['space']));
            }
        }

        return $html;
    }

    public function getChildPartners_model($param = array()) {

        $data = '';

        if ($param != '') {


            $param = explode(',', $param);

            if (is_array($param) and count($param) > 0) {

                foreach ($param as $partnerKey => $partnerId) {

                    $this->query = $this->db->query('
                        SELECT 
                            P.id
                        FROM `' . $this->db->dbprefix . 'partner` AS P 
                        WHERE P.is_active = 1 AND P.parent_id = ' . $partnerId);

                    if ($this->query->num_rows() > 0) {

                        foreach ($this->query->result() as $key => $row) {

                            $data .= $row->id . ',';

                            $data .= $this->_getChildPartner_model($row->id);
                        }

                        $data .= $partnerId . ',';
                    } else {

                        $data .= $partnerId . ',';
                    }
                }
            }

            return rtrim($data, ',');
        }

        return 0;
    }

    public function _getChildPartner_model($param = array()) {

        $data = '';

        if ($param != '') {


            $param = explode(',', $param);

            if (is_array($param) and count($param) > 0) {

                foreach ($param as $partnerKey => $partnerId) {

                    $this->query = $this->db->query('
                        SELECT 
                            P.id
                        FROM `' . $this->db->dbprefix . 'partner` AS P 
                        WHERE P.is_active = 1 AND P.parent_id = ' . $partnerId);

                    if ($this->query->num_rows() > 0) {

                        foreach ($this->query->result() as $key => $row) {

                            $data .= $row->id . ',';

                            $data .= $this->_getChildPartner_model($row->id);
                        }

                        $data .= $partnerId . ',';
                    } else {

                        $data .= $partnerId . ',';
                    }
                }
            }

            return $data;
        }

        return 0;
    }
    
    public function dataUpdate_model() {
        $query = $this->db->query('
                SELECT 
                    P.id,
                    P.social
                FROM `gaz_partner` AS P');

        foreach ($query->result() as $key => $row) {

            $data = array('social' => $this->social->socialDefault_model());

            $this->db->where('id', $row->id);
            if ($this->db->update('gaz_partner', $data)) {

                echo '<pre>';
                var_dump(json_decode($data['social']));
                echo '</pre>';
            }
        }
    }

}
