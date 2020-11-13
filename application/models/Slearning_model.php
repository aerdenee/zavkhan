<?php

class Slearning_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();

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
                C.partner_id,
                U.id AS url_id,
                U.url
            FROM `gaz_content` AS C
            LEFT JOIN `gaz_url` AS U ON C.mod_id = U.mod_id AND C.id = U.cont_id
            WHERE C.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }

        return false;
    }

    public function catLists_model($param = array()) {
        $this->queryString = $this->getString = '';

        $this->auth = authentication(array('authentication' => $this->session->authentication, 'moduleMenuId' => $param['moduleMenuId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 1) {
            $this->queryString .= '';
        } elseif ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND C.created_user_id = ' . $this->session->adminUserId;
        } else {
            $this->queryString .= ' AND C.created_user_id = -1';
        }

        if ($param['catId'] != '') {
            $this->queryString .= ' AND C.parent_id = ' . $param['catId'];
        } else {
            $this->queryString .= ' AND C.parent_id = 0';
        }
        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.title,
                C.order_num,
                C.mod_id,
                C.theme_layout_id,
                TL.title AS layout_title,
                C.created_date,
                C.modified_date,
                C.created_user_id,
                C.modified_user_id,
                C.is_active,
                (case when (C.pic is null or C.pic = \'\') then \'folder.svg\' else concat(\'s_\', C.pic) end) as pic
            FROM `gaz_category` AS C
            LEFT JOIN `gaz_theme_layout` AS TL ON C.theme_layout_id = TL.id
            WHERE 1 = 1 ' . $this->queryString . ' AND C.mod_id = ' . $param['modId'] . ' 
            ORDER BY C.id DESC');


        $this->html = form_open('', array('class' => 'form-horizontal', 'id' => 'form-category-init', 'enctype' => 'multipart/form-data'));
        $this->html .= form_hidden('modId', $this->auth->modId);
        $this->html .= $this->auth->hidden;
        $this->html .= $this->getString;

        $this->html .= '<div class="panel panel-white">';
            $this->html .= '<div class="panel-heading">';
                $this->html .= '<h6 class="panel-title">' . $param['title'] . '</h6>';
                $this->html .= '<div class="heading-elements not-collapsible">';
                $this->html .= form_button('searchCategory', '<i class="icon-search4"></i> <span class="hidden-xs position-right">Хайх (F3)</span>', 'class="btn btn-default legitRipple" onclick="_advensedSearchCategory({elem: this});"', 'button');
                $this->html .= '<div class="clearfix"></div>';
                $this->html .= '</div>';
            $this->html .= '</div>';
            $this->html .= '<div class="panel-body" style="padding:0px; margin:0px;">';
        if ($this->query->num_rows() > 0) {

            $this->html .= '<div class="content">';
            $i = 0;
            
            if ($param['isBackButton'] == 1) {
                $this->html .= '<div class="_folderBox">';
                $this->html .= '<div class="_bodyBox">';
                $this->html .= '<a href="javascript:history.back();">';
                $this->html .= '<img src="assets/images/back.svg" alt="" style="width:100%;">';
                $this->html .= '</a>';
                $this->html .= '</div>';
                $this->html .= '<div class="_bodyText">';
                $this->html .= '<a href="javascript:history.back();">Буцах</a>';
                $this->html .= '</div>';
                $this->html .= '</div>';
            }
            
            foreach ($this->query->result() as $key => $row) {

                $this->html .= '<div class="_folderBox">';
                $this->html .= '<div class="_bodyBox">';
                $this->html .= '<a href="slearning/categoryShow/' . $param['moduleMenuId'] . '/?catId=' . $row->id . '">';
                $this->html .= '<img src="' . UPLOADS_CONTENT_PATH . $row->pic . '" alt="">';
                $this->html .= '</a>';
                $this->html .= '</div>';
                $this->html .= '<div class="_bodyText">';
                $this->html .= '<a href="/slearning/categoryShow/' . $param['moduleMenuId'] . '/?catId=' . $row->id . '">' . $row->title . '</a>';
                $this->html .= '</div>';
                $this->html .= '</div>';
            }
            $this->html .= '</div>';
        } else {
            $this->html .= '<div class="panel-body">';
            $this->html .= '<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Бичлэг байхгүй</div>';
            $this->html .= '</div>';
        }
            $this->html .= '</div>';
        $this->html .= '</div>';

        $this->html .= form_close();
        return $this->html;
    }

    public function listsCount_model($param = array()) {

        $this->queryString = '';

        $this->auth = authentication(array('authentication' => $this->session->authentication, 'moduleMenuId' => $param['moduleMenuId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 1) {
            $this->queryString .= '';
        } elseif ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND C.created_user_id = ' . $this->session->adminUserId;
        } else {
            $this->queryString .= ' AND C.created_user_id = -1';
        }

        if ($this->session->adminAccessTypeId == 4 OR $this->session->adminAccessTypeId == 3 OR $this->session->adminAccessTypeId == 2) {

            $this->queryString .= ' AND C.department_id IN (' . $this->session->adminAllDepartmentId . ')';
        } else {

            if ($param['departmentId'] != 0) {
                $this->queryString .= ' AND C.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            }
        }


        if ($param['partnerId'] != 0) {
            $this->queryString .= ' AND C.partner_id IN(' . $this->partner->getChildPartners_model(array($param['partnerId'])) . ')';
            $this->getString .= form_hidden('partnerId', $param['partnerId']);
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND C.cat_id = ' . $param['catId'];
        }

        if ($param['peopleId'] != 0) {
            $this->queryString .= ' AND C.people_id = ' . $param['peopleId'];
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(C.title) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(C.intro_text) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(C.full_text) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {
            $this->queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(C.is_active_date) AND \'' . $param['outDate'] . '\' >= DATE(C.is_active_date)';
        } elseif ($param['inDate'] != '' and $param['outDate'] == '') {
            $this->queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(C.is_active_date)';
        } elseif ($param['inDate'] == '' and $param['outDate'] != '') {
            $this->queryString .= ' AND \'' . $param['outDate'] . '\' >= DATE(C.is_active_date)';
        }

        $this->query = $this->db->query('
            SELECT 
                C.id
            FROM `gaz_content` AS C 
            WHERE 1 = 1 ' . $this->queryString . ' AND C.mod_id = ' . $param['modId'] . ' 
            ORDER BY C.id DESC');

        return $this->query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $this->queryString = $this->getString = '';
        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'moduleMenuId' => $param['moduleMenuId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 1) {
            $this->queryString .= '';
        } elseif ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND C.created_user_id = ' . $this->session->adminUserId;
        } else {
            $this->queryString .= ' AND C.created_user_id = -1';
        }

//        if ($this->session->adminAccessTypeId == 2) {
//
//            $this->queryString .= ' AND C.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->adminDepartmentId) . ')';
//            
//        } else {
//
//            $this->getString .= form_hidden('departmentId', $param['departmentId']);
//
//            if ($param['departmentId'] == 0 OR $param['departmentId'] == NULL) {
//
//                $this->queryString .= ' AND C.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->adminDepartmentId) . ')';
//            } else {
//
//                $this->queryString .= ' AND C.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
//            }
//        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND C.cat_id = ' . $param['catId'];
            $this->getString .= form_hidden('catId', $param['catId']);
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(C.title) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(C.intro_text) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(C.full_text) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        if ($param['peopleId'] != 0) {
            $this->queryString .= ' AND C.people_id = ' . $param['peopleId'];
            $this->getString .= form_hidden('peopleId', $param['peopleId']);
        }

        $this->getString .= form_hidden('inDate', $param['inDate']);
        $this->getString .= form_hidden('outDate', $param['outDate']);

        if ($param['inDate'] != '' and $param['outDate'] != '') {
            $this->queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(C.is_active_date) AND \'' . $param['outDate'] . '\' >= DATE(C.is_active_date)';
        } elseif ($param['inDate'] != '' and $param['outDate'] == '') {
            $this->queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(C.is_active_date)';
        } elseif ($param['inDate'] == '' and $param['outDate'] != '') {
            $this->queryString .= ' AND \'' . $param['outDate'] . '\' >= DATE(C.is_active_date)';
        }

        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.cat_id,
                CAT.title AS cat_title,
                C.title,
                C.intro_text,
                (case when (C.pic is null or C.pic = \'\') then \'default.svg\' else concat(\'m_\', C.pic) end) as pic,
                C.click,
                C.comment_count,
                C.is_active_date,
                C.order_num,
                C.created_date,
                C.modified_date,
                C.created_user_id,
                C.modified_user_id,
                C.is_active,
                C.mod_id,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS author
            FROM `gaz_content` AS C
            INNER JOIN `gaz_category` AS CAT ON C.cat_id = CAT.id
            INNER JOIN `gaz_hr_people` AS HP ON C.people_id = HP.id
            WHERE 1 = 1 ' . $this->queryString . ' AND C.mod_id = ' . $param['modId'] . ' 
            ORDER BY C.order_num DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        $this->html = form_open('', array('class' => 'form-horizontal', 'id' => 'form-content-init', 'enctype' => 'multipart/form-data'));
        $this->html .= form_hidden('modId', $this->auth->modId);
        $this->html .= form_hidden('limit', $param['limit']);
        $this->html .= form_hidden('page', $param['page']);

        $this->html .= $this->auth->hidden;
        $this->html .= $this->getString;

        $this->html .= '<div class="panel panel-white">';
        $this->html .= '<div class="panel-heading">';
        $this->html .= '<h6 class="panel-title">' . $param['title'] . '</h6>';
        $this->html .= '<div class="heading-elements not-collapsible">';
        //$this->html .= ($param['totalRows'] > 0 ? '<span class="label bg-blue heading-text">Нийт ' . $param['totalRows'] . ' бичлэг байна</span>' : '');
        $this->html .= $param['paginationHtml'];
        $this->html .= '</div>';
        $this->html .= '</div>';

        $this->html .= '<div class="panel-toolbar panel-toolbar-inbox">';
        $this->html .= '<div class="navbar navbar-default">';
        $this->html .= '<ul class="nav navbar-nav visible-xs-block no-border">';
        $this->html .= '<li>';
        $this->html .= '<a class="text-center collapsed legitRipple" data-toggle="collapse" data-target="#inbox-toolbar-toggle-single">';
        $this->html .= '<i class="icon-circle-down2"></i>';
        $this->html .= '</a>';
        $this->html .= '</li>';
        $this->html .= '</ul>';

        $this->html .= '<div class="navbar-collapse collapse" id="inbox-toolbar-toggle-single">';

        $this->html .= '<div class="navbar-btn navbar-left">' . self::searchKeywordView_model() . '</div>';

        $this->html .= '<div class="btn-group navbar-btn navbar-right">';
        $this->html .= form_button('searchContent', '<i class="icon-search4"></i> <span class="hidden-xs position-right">Хайх (F3)</span>', 'class="btn btn-default legitRipple" onclick="_advensedSearchContent({elem: this});"', 'button');


        $this->html .= '</div>';

        $this->html .= '</div>';
        $this->html .= '</div>';
        $this->html .= '</div>';

        if ($this->query->num_rows() > 0) {

            $this->html .= '<div class="col-md-12">';
            foreach ($this->query->result() as $key => $row) {

                $this->html .= '<div class="col-md-3">';
                $this->html .= '<div class="thumbnail _learning">';
                $this->html .= '<div class="thumb">';
                $this->html .= '<a href="javascript:;" onclick="_showLearning({selectedId: ' . $row->id . '});" data-toggle="modal" class="_thumbnail">';
                $this->html .= '<img src="' . UPLOADS_CONTENT_PATH . $row->pic . '" class="img-responsive img-rounded" alt="">';
                $this->html .= '<span class="zoom-image"><i class="icon-anchor"></i></span>';
                $this->html .= '</a>';
                $this->html .= '</div>';

                $this->html .= '<div class="caption _caption">';
                $this->html .= '<div class="content-group-sm media">';
                $this->html .= '<div class="media-body">';
                $this->html .= '<h6 class="text-semibold no-margin"><a href="javascript:;" onclick="_showLearning({selectedId: ' . $row->id . '});" class="text-default">' . $row->title . '</a></h6>';
                $this->html .= '<small class="text-muted"><a href="javascript:;">' . $row->author . '</a>, (' . $row->cat_title . ')</small>';
                $this->html .= '</div>';

                //$this->html .= '<h6 class="text-success media-right no-margin-bottom text-semibold">$49.99</h6>';
                $this->html .= '</div>';

                $this->html .= word_limiter($row->intro_text, 20);
                $this->html .= '</div>';

                $this->html .= '<div class="panel-footer panel-footer-transparent">';
                $this->html .= '<div class="heading-elements">';
                $this->html .= '<ul class="_icon-accessories">';
                $this->html .= '<li><i class="icon-eye8"></i> ' . ($row->click != 0 ? $row->click : 'Шинэ') . '</li>';
                $this->html .= '<li><i class="icon-calendar52"></i> ' . date('Y-m-d', strtotime($row->is_active_date)) . '</li>';
                $this->html .= '</ul>';


                $this->html .= '<ul class="_icon-accessories pull-right">';
                $this->html .= '<li><i class="icon-comment-discussion"></i> ' . ($row->comment_count != 0 ? $row->comment_count : 'Байхгүй') . '</li>';
                $this->html .= '</ul>';
                $this->html .= '<div class="clearfix"></div>';
                $this->html .= '</div>';
                $this->html .= '</div>';
                $this->html .= '</div>';
                $this->html .= '</div>';
            }

            $this->html .= '<div class="clearfix"></div>';

            $this->html .= '</div>';

            $this->html .= '<div class="clearfix"></div>';

            $this->html .= '<div class="navbar text-right">' . $param['paginationHtml'] . '</div>';
        } else {
            $this->html .= '<div class="panel-body">';
            $this->html .= '<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Бичлэг байхгүй</div>';
            $this->html .= '</div>';
        }

        $this->html .= '</div>';

        $this->html .= form_close();
        return $this->html;
    }

    public function relatedList_model($param = array('modId' => 0, 'catId' => 0)) {
        $this->queryString = $this->getString = '';


        if ($this->session->adminAccessTypeId == 4 OR $this->session->adminAccessTypeId == 3 OR $this->session->adminAccessTypeId == 2) {

            $this->queryString .= ' AND C.department_id IN (' . $this->session->adminAllDepartmentId . ')';
        }


        if ($param['currentId'] != 0) {
            $this->queryString .= ' AND C.id != ' . $param['currentId'];
        }

        if ($param['peopleId'] != 0) {
            $this->queryString .= ' AND C.people_id = ' . $param['peopleId'];
            $this->getString .= form_hidden('peopleId', $param['peopleId']);
        }

        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.cat_id,
                CAT.title AS cat_title,
                C.title,
                C.intro_text,
                (case when (C.pic is null or C.pic = \'\') then \'default.svg\' else concat(\'m_\', C.pic) end) as pic,
                C.click,
                C.comment_count,
                C.is_active_date,
                C.order_num,
                C.created_date,
                C.modified_date,
                C.created_user_id,
                C.modified_user_id,
                C.is_active,
                C.mod_id,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS author
            FROM `gaz_content` AS C
            INNER JOIN `gaz_category` AS CAT ON C.cat_id = CAT.id
            INNER JOIN `gaz_hr_people` AS HP ON C.people_id = HP.id
            WHERE 1 = 1 ' . $this->queryString . ' AND C.mod_id = ' . $param['modId'] . ' 
            ORDER BY C.order_num DESC
            LIMIT 0, 10');



        if ($this->query->num_rows() > 0) {

            $this->html = '<ul class="media-list">';
            foreach ($this->query->result() as $key => $row) {

                $this->html .= '<li class="media" onclick="_showLearning({selectedId: ' . $row->id . '});" title="' . $row->title . '" style="cursor:pointer;">';
                $this->html .= '<div class="media-left">';
                $this->html .= '<a href="javascript:;" class=""><img src="' . UPLOADS_CONTENT_PATH . $row->pic . '" class="img-circle img-md" alt=""></a>';
                $this->html .= '</div>';

                $this->html .= '<div class="media-body">';
                $this->html .= word_limiter($row->title, 10);
                $this->html .= '<div class="media-annotation"><i class="icon-calendar position-left"></i> ' . date('Y-m-d', strtotime($row->created_date)) . '</div>';
                $this->html .= '</div>';
                $this->html .= '</li>';
            }

            $this->html .= '</ul>';
        }

        return $this->html;
    }

    public function searchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
        $this->string = '';
        $this->showResetBtn = FALSE;

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

        if ($this->input->get('partnerId')) {
            $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->get('partnerId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->partnerData->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('catId')) {
            $this->cat = $this->category->getData_model(array('selectedId' => $this->input->get('catId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->cat->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('authorId')) {
            $this->user = $this->user->getData_model(array('selectedId' => $this->input->get('authorId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->user->full_name . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            //' . Scrime::$path . $param['path'] . '/' . $param['modId'] . '
            $this->string .= ' <a href="javascript:;" onclick="_initCategoryLearning({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return $this->string;
    }

    public function mListsCount_model($param = array()) {

        $this->queryString = '';

        $this->auth = authentication(array('authentication' => $param['authentication'], 'moduleMenuId' => $param['moduleMenuId']));

//        if ($this->auth->our->read == 1 and $this->auth->your->read == 1) {
//            $this->queryString .= '';
//        } else if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
//            $this->queryString .= ' AND C.created_user_id = ' . $param['userId'];
//        } else if ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
//            $this->queryString .= ' AND C.created_user_id = -1';
//        }
//        if ($this->session->adminAccessTypeId != 5) {
//            $this->queryString .= ' AND C.department_id IN (' . $this->session->adminAllDepartmentId . ')';
//        }

        if ($param['partnerId'] != 0) {
            $this->queryString .= ' AND C.partner_id = ' . $param['partnerId'];
        }

        if ($param['catId'] != 0 and count($param['catId']) > 0 and $param['catId']['0'] != null) {
            $catIdList = $this->category->getChildCategores_model($param['catId']);
            $this->queryString .= ' AND C.cat_id IN (' . $catIdList . ') ';
        }

        if ($param['peopleId'] != 0) {
            $this->queryString .= ' AND C.people_id = ' . $param['peopleId'];
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(C.title) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(C.intro_text) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(C.full_text) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {
            $this->queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(C.is_active_date) AND \'' . $param['outDate'] . '\' >= DATE(C.is_active_date)';
        } elseif ($param['inDate'] != '' and $param['outDate'] == '') {
            $this->queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(C.is_active_date)';
        } elseif ($param['inDate'] == '' and $param['outDate'] != '') {
            $this->queryString .= ' AND \'' . $param['outDate'] . '\' >= DATE(C.is_active_date)';
        }

        $this->query = $this->db->query('
            SELECT 
                C.id
            FROM `gaz_content` AS C
            WHERE 1 = 1 ' . $this->queryString . ' AND C.mod_id = ' . $param['modId'] . ' 
            ORDER BY C.id DESC');

        return $this->query->num_rows();
    }

    public function mLists_model($param = array('modId' => 0, 'catId' => 0)) {


        $this->queryString = $this->getString = '';
        $this->auth = authentication(array('authentication' => $param['authentication'], 'moduleMenuId' => $param['moduleMenuId']));

//        if ($this->auth->our->read == 1 and $this->auth->your->read == 1) {
//            $this->queryString .= '';
//        } else if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
//            $this->queryString .= ' AND C.created_user_id = ' . $param['UserId'];
//        } else if ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
//            $this->queryString .= ' AND C.created_user_id = -1';
//        }
//        if ($this->session->adminAccessTypeId != 5) {
//            $this->queryString .= ' AND C.department_id IN (' . $this->session->adminAllDepartmentId . ')';
//        }

        if ($param['partnerId'] != 0) {
            $this->queryString .= ' AND C.partner_id = ' . $param['partnerId'];
            $this->getString .= form_hidden('partnerId', $param['partnerId']);
        }

        if ($param['catId'] != 0 and count($param['catId']) > 0 and $param['catId']['0'] != null) {
            $catIdList = $this->category->getChildCategores_model($param['catId']);
            $this->queryString .= ' AND C.cat_id IN (' . $catIdList . ') ';
            $this->getString .= form_hidden('catId', $catIdList);
        }

        if ($param['peopleId'] != 0) {
            $this->queryString .= ' AND C.people_id = ' . $param['peopleId'];
            $this->getString .= form_hidden('authorId', $param['peopleId']);
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(C.title) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(C.intro_text) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(C.full_text) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        $this->getString .= form_hidden('inDate', $param['inDate']);
        $this->getString .= form_hidden('outDate', $param['outDate']);

        if ($param['inDate'] != '' and $param['outDate'] != '') {
            $this->queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(C.is_active_date) AND \'' . $param['outDate'] . '\' >= DATE(C.is_active_date)';
        } elseif ($param['inDate'] != '' and $param['outDate'] == '') {
            $this->queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(C.is_active_date)';
        } elseif ($param['inDate'] == '' and $param['outDate'] != '') {
            $this->queryString .= ' AND \'' . $param['outDate'] . '\' >= DATE(C.is_active_date)';
        }

        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.cat_id,
                CAT.title AS cat_title,
                C.title,
                C.intro_text,
                C.pic,
                C.click,
                C.comment_count,
                C.order_num,
                C.created_date,
                C.modified_date,
                C.is_active_date,
                C.created_user_id,
                C.modified_user_id,
                C.is_active,
                C.mod_id,
                C.people_id,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS author
            FROM `gaz_content` AS C
            INNER JOIN `gaz_category` AS CAT ON C.cat_id = CAT.id
            INNER JOIN `gaz_hr_people` AS HP ON C.people_id = HP.id
            WHERE 1 = 1 ' . $this->queryString . ' AND C.mod_id = ' . $param['modId'] . ' 
            ORDER BY C.order_num DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        if ($this->query->num_rows() > 0) {

            return $this->query->result();
        }

        return false;
    }

}
