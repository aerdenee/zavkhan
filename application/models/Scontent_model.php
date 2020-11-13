<?php

class Scontent_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();

        $this->load->model('Simage_model', 'simage');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('Slog_model', 'slog');
        $this->load->model('Suser_model', 'user');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Scategory_model', 'category');


        $this->isActiveDepartment = 'is_active_control';
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => 2,
            'cat_id' => 0,
            'parent_id' => 0,
            'department_id' => 0,
            'show_pic_outside' => 1,
            'show_pic_inside' => 1,
            'pic' => $this->simage->returnDefaultImage_model(array('table' => 'content')),
            'show_title' => 1,
            'title' => '',
            'link_title' => '',
            'intro_text' => '',
            'full_text' => '',
            'page_title' => '',
            'meta_key' => '',
            'meta_desc' => '',
            'h1_text' => '',
            'show_date' => 1,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'is_active_date' => date('Y-m-d H:i:s'),
            'show_people' => 1,
            'people_id' => $this->session->adminPeopleId,
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'show_comment' => 1,
            'comment_count' => 0,
            'show_click' => 1,
            'click' => 0,
            'click_real' => 0,
            'is_active' => 1,
            'order_num' => getOrderNum(array('table' => 'content', 'field' => 'order_num')),
            'show_social' => 1,
            'param' => '',
            'lang_id' => 0,
            'theme_layout_id' => 0,
            'partner_id' => 1,
            'url' => getUID('url')
        )));
    }

    public function editFormData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.mod_id,
                C.cat_id,
                C.parent_id,
                C.department_id,
                C.show_pic_outside,
                C.show_pic_inside,
                (case when (C.pic is null or C.pic = \'\' or C.pic = \'default.svg\') then \'' . $this->simage->returnDefaultImage_model(array('table' => 'content')) . '\' else concat(\'' . UPLOADS_CONTENT_PATH . 's_\', C.pic) end) as pic,
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
            LEFT JOIN `gaz_url` AS U ON C.mod_id = U.mod_id AND C.id = U.cont_id AND C.lang_id = U.lang_id
            WHERE C.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function listsCount_model($param = array()) {

        $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and !$auth->your->read) {
            $queryString .= ' AND C.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND C.created_user_id = -1';
        }

//        if ($param['departmentId'] != 0) {
//
//            $queryString .= ' AND C.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
//        } else {
//
//            $queryString .= ' AND C.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ')';
//        }


        if ($param['partnerId'] != 0) {
            $queryString .= ' AND C.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND C.cat_id = ' . $param['catId'];
        }

        if ($param['userId'] != 0) {
            $queryString .= ' AND C.created_user_id = ' . $param['userId'];
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND (LOWER(C.title) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(C.intro_text) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(C.full_text) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {
            $queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(C.is_active_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(C.is_active_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {
            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(C.is_active_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {
            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(C.is_active_date)';
        }

        $query = $this->db->query('
            SELECT 
                C.id
            FROM `gaz_content` AS C
            WHERE C.lang_id = ' . $this->session->adminLangId . $queryString . ' AND C.mod_id = ' . $param['modId'] . ' 
            ORDER BY C.id DESC');

        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $data = array();
        $queryString = '';

        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and !$auth->your->read) {
            $queryString .= ' AND C.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND C.created_user_id = -1';
        }

//        if ($param['departmentId'] != 0) {
//
//            $queryString .= ' AND C.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
//        } else {
//
//            $queryString .= ' AND C.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ')';
//        }


        if ($param['partnerId'] != 0) {
            $queryString .= ' AND C.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND C.cat_id = ' . $param['catId'];
        }

        if ($param['userId'] != 0) {
            $queryString .= ' AND C.created_user_id = ' . $param['userId'];
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND (LOWER(C.title) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(C.intro_text) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(C.full_text) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {
            $queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(C.is_active_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(C.is_active_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {
            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(C.is_active_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {
            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(C.is_active_date)';
        }

        $query = $this->db->query('
            SELECT 
                C.id,
                C.cat_id,
                CAT.title AS cat_title,
                C.title,
                (case when (C.pic is null or C.pic = \'\' or C.pic = \'default.svg\') then "<img src=\"/assets/system/img/default.svg\">" else CONCAT("<img src=\"' . UPLOADS_CONTENT_PATH . CROP_SMALL . '", C.pic, "\">") end) as pic,
                C.order_num,
                C.created_date,
                DATE_FORMAT(C.modified_date, \'%Y-%m-%d\') AS modified_date,
                C.created_user_id,
                C.modified_user_id,
                IF(C.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                C.mod_id
            FROM `gaz_content` AS C
            LEFT JOIN `gaz_category` AS CAT ON C.cat_id = CAT.id
            WHERE C.lang_id = ' . $this->session->adminLangId . $queryString . ' AND C.mod_id = ' . $param['modId'] . ' 
            ORDER BY C.order_num DESC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);


        if ($query->num_rows() > 0) {
            $i = 0;
            foreach ($query->result() as $key => $row) {

                array_push($data, array(
                    'num' => ++$i,
                    'id' => $row->id,
                    'created_user_id' => $row->created_user_id,
                    'pic' => $row->pic,
                    'title' => $row->title,
                    'cat_title' => $row->cat_title,
                    'modified_date' => $row->modified_date,
                    'is_active' => $row->is_active
                ));
            }
        }
        return array('data' => $data, 'search' => self::searchKeywordView_model());
    }

    public function insert_model($param = array()) {

        $this->param = array();

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        $data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'parent_id' => $this->input->post('parentId'),
                'show_pic_outside' => $this->input->post('showPicOutside'),
                'show_pic_inside' => $this->input->post('showPicInside'),
                'pic' => $this->input->post('contentPic'),
                'show_title' => $this->input->post('showTitle'),
                'title' => $this->input->post('title'),
                'link_title' => $this->input->post('linkTitle'),
                'intro_text' => $this->input->post('introText'),
                'full_text' => $this->input->post('fullText'),
                'page_title' => $this->input->post('pageTitle'),
                'meta_key' => $this->input->post('metaKey'),
                'meta_desc' => $this->input->post('metaDesc'),
                'h1_text' => $this->input->post('h1Text'),
                'show_date' => $this->input->post('showDate'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'is_active_date' => $this->input->post('isActiveDate') . ' ' . $this->input->post('isActiveTime'),
                'show_people' => $this->input->post('showPeople'),
                'people_id' => $this->input->post('peopleId'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'show_comment' => $this->input->post('showComment'),
                'comment_count' => 0,
                'show_click' => $this->input->post('showClick'),
                'click' => 0,
                'click_real' => 0,
                'is_active' => $this->input->post('isActive'),
                'order_num' => $this->input->post('orderNum'),
                'show_social' => $this->input->post('showSocial'),
                'param' => '',
                'lang_id' => $this->session->adminLangId,
                'theme_layout_id' => $this->input->post('themeLayoutId'),
                'partner_id' => $this->input->post('partnerId'))
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'content', $data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_UPDATE,
            'data' => json_encode($_POST)));

        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'parent_id' => $this->input->post('parentId'),
            'show_pic_outside' => $this->input->post('showPicOutside'),
            'show_pic_inside' => $this->input->post('showPicInside'),
            'pic' => ($this->input->post('contentPic') != '' ? $this->input->post('contentPic') : $this->input->post('contentOldPic')),
            'show_title' => $this->input->post('showTitle'),
            'title' => $this->input->post('title'),
            'link_title' => $this->input->post('linkTitle'),
            'intro_text' => $this->input->post('introText'),
            'full_text' => $this->input->post('fullText'),
            'page_title' => $this->input->post('pageTitle'),
            'meta_key' => $this->input->post('metaKey'),
            'meta_desc' => $this->input->post('metaDesc'),
            'h1_text' => $this->input->post('h1Text'),
            'show_date' => $this->input->post('showDate'),
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s'),
            'is_active_date' => $this->input->post('isActiveDate') . ' ' . $this->input->post('isActiveTime'),
            'show_people' => $this->input->post('showPeople'),
            'people_id' => $this->input->post('peopleId'),
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'show_comment' => $this->input->post('showComment'),
            'comment_count' => $this->input->post('commentCount'),
            'show_click' => $this->input->post('showClick'),
            'click' => $this->input->post('click'),
            'is_active' => $this->input->post('isActive'),
            'order_num' => $this->input->post('orderNum'),
            'show_social' => $this->input->post('showSocial'),
            'param' => '',
            'theme_layout_id' => $this->input->post('themeLayoutId'),
            'partner_id' => $this->input->post('partnerId')
        );

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'content', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {

        $this->db->where('id', $this->input->post('id'));
        $query = $this->db->get($this->db->dbprefix . 'content');
        $row = $query->row();

        $this->slog->log_model(array(
            'modId' => $row->mod_id,
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_DELETE,
            'data' => json_encode($row)));

        $this->simage->removeUploadImage_model(array('uploadImage' => $row->pic, 'uploadPath' => UPLOADS_CONTENT_PATH));

        generateUrl(array('modId' => $row->mod_id, 'contId' => $row->id, 'langId' => $row->lang_id, 'mode' => 'delete'));

        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'content')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function searchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
        $this->string = '';
        $this->showResetBtn = FALSE;

        $this->string .= form_hidden('inDate', $this->input->get('inDate'));
        $this->string .= form_hidden('outDate', $this->input->get('outDate'));

        if ($this->input->get('inDate') and $this->input->get('outDate')) {
            $this->string .= '<span class="label label-default label-rounded">Бүртгэсэн огноо: ' . date('Y.m.d', strtotime($this->input->get('inDate'))) . '-' . date('Y.m.d', strtotime($this->input->get('outDate'))) . '</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('inDate')) {
            $this->string .= '<span class="label label-default label-rounded">Бүртгэсэн огноо: ' . date('Y.m.d', strtotime($this->input->get('inDate'))) . ' хойш</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('outDate')) {
            $this->string .= '<span class="label label-default label-rounded">Бүртгэсэн огноо: ' . date('Y.m.d', strtotime($this->input->get('outDate'))) . ' өмнөх</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('partnerId')) {
            if ($this->input->get('partnerId') != 'all') {
                $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->get('partnerId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->partnerData->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('partnerId', $this->input->get('partnerId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->string .= form_hidden('keyword', $this->input->get('keyword'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('catId')) {
            if ($this->input->get('catId') != 'all') {
                $this->cat = $this->category->getData_model(array('selectedId' => $this->input->get('catId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->cat->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('catId', $this->input->get('catId'));
            $this->showResetBtn = TRUE;
        }


        if ($this->input->get('userId')) {
            if ($this->input->get('expertId') != 'all') {
                $this->expertData = $this->user->getData_model(array('selectedId' => $this->input->get('expertId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->expertData->full_name . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('expertId', $this->input->get('expertId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('departmentId')) {

            $this->hrPeopleDepartmentData = $this->hrPeopleDepartment->getData_model(array('selectedId' => $this->input->get('departmentId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->hrPeopleDepartmentData->title . '</span>';
            $this->string .= form_hidden('departmentId', $this->input->get('departmentId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {

            $this->string .= ' <a href="javascript:;" onclick="_initContent({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return ($this->showResetBtn ? '<div class="_search-result-inner"><form action="javascript:;" id="form-content"> Хайлтын үр дүн: ' . $this->string . '</form></div>' : '');
    }

    public function catListCount_model($param = array('modId' => 0, 'catId' => 0)) {


        $this->queryString = $this->getString = '';

//        if ($this->session->adminDepartmentRoleId == 2) {
//
//            $this->queryString .= ' AND C.department_id IN (-1,' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ',' . $this->hrPeopleDepartment->getParentHrPeopleDepartment_model($this->session->adminDepartmentId) . ')';
//        }

        if (isset($param['catId']) and $param['catId'] != 0) {
            $this->queryString .= ' AND C.cat_id = ' . $param['catId'];
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(C.title) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(C.intro_text) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        $this->query = $this->db->query('
            SELECT 
                C.id
            FROM `gaz_content` AS C
            WHERE C.lang_id = ' . $this->session->adminLangId . $this->queryString . ' AND C.cat_id = ' . $param['catId']);

        return $this->query->num_rows();
    }

    public function catList_model($param = array('modId' => 0, 'catId' => 0)) {


        $this->queryString = $this->getString = '';

//        if ($this->session->adminDepartmentRoleId == 2) {
//
//            $this->queryString .= ' AND C.department_id IN (-1,' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ',' . $this->hrPeopleDepartment->getParentHrPeopleDepartment_model($this->session->adminDepartmentId) . ')';
//        }

        if (isset($param['catId']) and $param['catId'] != 0) {
            $this->queryString .= ' AND C.cat_id = ' . $param['catId'];
            $this->getString .= form_hidden('catId', $param['catId']);
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(C.title) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(C.intro_text) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.cat_id,
                CAT.title AS cat_title,
                C.title,
                C.intro_text,
                (case when (C.pic is null or C.pic = \'\') then \'default.svg\' else concat(\'s_\', C.pic) end) as pic,
                C.click,
                COMM.comment_count,
                C.is_active_date,
                C.order_num,
                C.created_date,
                C.modified_date,
                C.created_user_id,
                C.modified_user_id,
                C.is_active,
                C.mod_id,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS author,
                HPD.title AS department_title
            FROM `gaz_content` AS C
            INNER JOIN `gaz_category` AS CAT ON C.cat_id = CAT.id
            INNER JOIN `gaz_user` AS U ON C.created_user_id = U.id
            INNER JOIN `gaz_hr_people` AS HP ON U.people_id = HP.id
            INNER JOIN `gaz_hr_people_department` AS HPD ON HP.department_id = HPD.id
            LEFT JOIN ( 
                SELECT 
                    COM.mod_id, COM.cont_id, COUNT(COM.id) AS comment_count 
                FROM `gaz_comment` AS COM 
                GROUP BY COM.cont_id, COM.mod_id 
            ) AS COMM ON C.mod_id = COMM.mod_id AND C.id = COMM.cont_id
            WHERE C.lang_id = ' . $this->session->adminLangId . $this->queryString . ' AND C.cat_id = ' . $param['catId'] . '
            ORDER BY C.order_num DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        $this->html = form_open('', array('class' => 'form-horizontal', 'id' => 'form-category-init', 'enctype' => 'multipart/form-data'));

        $this->html .= $this->getString;

        if ($this->query->num_rows() > 0) {

            //$this->html .= '<div class="content">';
            $i = 0;
            $this->html .= '<div class="mb-3">';
            $this->html .= '<h6 class="mb-0 font-weight-semibold">' . $param['category']->title . '</h6>';
            //$this->html .= '<span class="text-muted d-block">Card with transparent footer</span>';
            $this->html .= '</div>';
            $this->html .= '<div class="row">';
            foreach ($this->query->result() as $key => $row) {

                $this->html .= '<div class="col-md-3 col-sm-6">';
                $this->html .= '<div class="card _blog">';
                $this->html .= '<div class="card-header">';
                $this->html .= '<div class="card-img-actions mb-3">';
                $this->html .= '<img class="card-img img-fluid" src="' . UPLOADS_CONTENT_PATH . $row->pic . '" alt="' . $row->title . '">';
                $this->html .= '<div class="card-img-actions-overlay card-img">';
                $this->html .= '<a href="scontent/show/' . $param['moduleMenuId'] . '/' . $row->id . '" class="_blogImage"></a>';
                $this->html .= '</div>';
                $this->html .= '</div>';
                $this->html .= '</div>';
                $this->html .= '<div class="card-body">';


                $this->html .= '<h5 class="font-weight-semibold mb-1">';
                $this->html .= '<a href="scontent/show/' . $param['moduleMenuId'] . '/' . $row->id . '" class="text-default">' . $row->title . '</a>';
                $this->html .= '</h5>';

                $this->html .= '<ul class="list-inline list-inline-dotted text-muted mb-3">';
                $this->html .= '<li class="list-inline-item"><a href="javascript:;">' . $row->author . '</a>, (' . $row->department_title . ')</li>';
                $this->html .= '</ul>';


                $this->html .= '</div>';

                $this->html .= '<div class="card-footer d-flex">';
                $this->html .= '<div class="text-default mr-2"><i class="icon-eye8"></i> ' . ($row->click != 0 ? $row->click : 'Шинэ') . '</div>';
                $this->html .= '<div class="text-default"><i class="icon-calendar52"></i> ' . date('Y-m-d', strtotime($row->is_active_date)) . '</div>';
                $this->html .= '<div class="ml-auto"><i class="icon-comment-discussion"></i> ' . ($row->comment_count != 0 ? $row->comment_count : 'Байхгүй') . '</div>';
                $this->html .= '</div>';
                $this->html .= '</div>';
                $this->html .= '</div>';
            }
            $this->html .= '</div>';
            $this->html .= '<div class="clearfix"></div>';
            $this->html .= '<div class="text-right">' . $param['paginationHtml'] . '</div>';
            //$this->html .= '</div>';
        } else {
            $this->html .= $this->load->view(MY_ADMIN . '/page/empty', '', TRUE);
        }

        $this->html .= form_close();
        return $this->html;
    }

    public function showItemMirrorList_model($param = array('modId' => 0, 'catId' => 0)) {


        $this->html = $this->queryString = $this->getString = '';

        if ($this->session->adminDepartmentRoleId == 2) {

            $this->queryString .= ' AND HA.department_id IN (-1,' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ',' . $this->hrPeopleDepartment->getParentHrPeopleDepartment_model($this->session->adminDepartmentId) . ')';
        }

        if (isset($param['catId']) and $param['catId'] != 0) {
            $this->queryString .= ' AND HA.cat_id = ' . $param['catId'];
            $this->getString .= form_hidden('catId', $param['catId']);
        }

        if (isset($param['keyword']) and $param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(HA.title) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(HA.intro_text) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        $this->query = $this->db->query('
            SELECT 
                HA.id,
                HA.title,
                HA.link_title,
                (case when (HA.pic is null OR HA.pic = \'\' OR HA.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', HA.pic) end) as pic,
                HA.modified_date AS is_active_date
            FROM `gaz_hr_ads` AS HA
            WHERE 1 = 1 ' . $this->queryString . '
            ORDER BY HA.order_num DESC
            LIMIT 0, 8');

        if ($this->query->num_rows() > 0) {

            $i = 0;
            $this->html .= '<div class="related_news">';
            $this->html .= '<div class="fancy-title"><h3>Энэ тухай</h3></div>';
            $this->html .= '<div class="row"></div>';
            $this->html .= '<div class="row">';
            foreach ($this->query->result() as $key => $row) {

                $this->html .= '<div class="col-xs-12 col-sm-6 col-md-6 related_news_item pt-2">';
                $this->html .= '<div class="row">
                                <div class="col-sm-6">
                                    <div class="image">
                                        <a href="shrAds/show/' . $param['moduleMenuId'] . '/' . $row->id . '" class="thumb mr-2">
                                            <img class="list-news-image" src="' . UPLOADS_CONTENT_PATH . $row->pic . '">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h4>
                                        <a href="shrAds/show/' . $param['moduleMenuId'] . '/' . $row->id . '" title="' . $row->link_title . '">' . $row->title . '</a>
                                    </h4>
                                    <span>
                                        <i class="fa fa-clock-o"></i>
                                        ' . dateDiff($row->is_active_date) . '
                                    </span>
                                </div>
                            </div>';
                $this->html .= '</div>';
            }
            $this->html .= '</div>';
            $this->html .= '</div>';
        }


        return $this->html;
    }

    public function showItemPeopleList_model($param = array('modId' => 0, 'catId' => 0)) {


        $this->html = $this->queryString = $this->getString = '';

        if ($this->session->adminDepartmentRoleId == 2) {
            $this->queryString .= ' AND HA.department_id IN (-1,' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ',' . $this->hrPeopleDepartment->getParentHrPeopleDepartment_model($this->session->adminDepartmentId) . ')';
        }

        if (isset($param['hrPeople']) and $param['hrPeople']->id != 0) {
            $this->queryString .= ' AND HA.people_id = ' . $param['hrPeople']->id;
        }

        $this->query = $this->db->query('
            SELECT 
                HA.id,
                HA.title,
                HA.link_title,
                (case when (HA.pic is null OR HA.pic = \'\' OR HA.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', HA.pic) end) as pic,
                HA.modified_date AS is_active_date
            FROM `gaz_hr_ads` AS HA
            ORDER BY HA.created_date DESC
            LIMIT 0, 8');

        if ($this->query->num_rows() > 0) {

            $i = 0;
            $this->html .= '<ul class="nav nav-tabs nav-tabs-solid nav-justified border-0">
                <li class="nav-item"><a href="#justified-right-icon-tab1" class="nav-link active" data-toggle="tab">' . $param['hrPeople']->full_name . ' нийтэлсэн</a></li>
            </ul>';
            $this->html .= '<div class="tab-content">';
            $this->html .= '<div class="tab-pane fade show active" id="justified-right-icon-tab1">';
            $this->html .= '<ul class="list-news list-unstyled">';
            foreach ($this->query->result() as $key => $row) {

                $this->html .= '<li class="clearfix py-2">
                            <a href="shrAds/show/' . $param['moduleMenuId'] . '/' . $row->id . '" class="thumb mr-2">

                                <img class="list-news-image" src="' . UPLOADS_CONTENT_PATH . $row->pic . '" alt="' . $row->link_title . '">

                            </a>
                            <h1>
                                <a href="shrAds/show/' . $param['moduleMenuId'] . '/' . $row->id . '" title="' . $row->link_title . '">' . $row->title . '</a>
                            </h1>
                            <span>
                                <i class="icofont icofont-wall-clock"></i>
                                ' . dateDiff($row->is_active_date) . '
                            </span>
                        </li>';
            }
            $this->html .= '</ul>';
            $this->html .= '</div>';
            $this->html .= '</div>';
        }


        return $this->html;
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

    public function mListsCount_model($param = array()) {

        $queryString = '';

        if ($param['catId'] != 0) {
            $queryString .= ' AND C.cat_id = ' . $param['catId'];
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND (LOWER(C.title) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(C.intro_text) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(C.full_text) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        $this->query = $this->db->query('
            SELECT 
                C.id
            FROM `gaz_content` AS C 
            WHERE 1 = 1 ' . $queryString . ' 
            ORDER BY C.id DESC');

        return $this->query->num_rows();
    }

    public function mLists_model($param = array('modId' => 0, 'catId' => 0)) {


        $queryString = '';

        if ($param['catId'] != 0) {
            $queryString .= ' AND C.cat_id = ' . $param['catId'];
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND (LOWER(C.title) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(C.intro_text) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(C.full_text) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        $query = $this->db->query('
            SELECT 
                C.id,
                C.cat_id,
                CAT.title AS cat_title,
                C.title,
                (case when (C.pic is null or C.pic = \'\') then \'default.svg\' else concat(\'s_\', C.pic) end) as pic,
                C.order_num,
                C.created_date,
                C.modified_date,
                C.created_user_id,
                C.modified_user_id,
                C.is_active,
                C.mod_id
            FROM `gaz_content` AS C
            LEFT JOIN `gaz_category` AS CAT ON C.cat_id = CAT.id
            WHERE 1 = 1 ' . $queryString . '
            ORDER BY C.order_num DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        if ($query->num_rows() > 0) {

            return $query->result();
        }

        return false;
    }

    public function dataUpdate_model($param = array('contId' => 0, 'catId' => 0)) {

        $query = $this->db->query('
            SELECT 
                C.id,
                C.mod_id,
                C.cat_id,
                C.parent_id,
                C.department_id,
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
                C.partner_id
            FROM `gaz_content_old` AS C');

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $row) {

                $getUID = getUID('content');
                generateUrl(array('modId' => $row->mod_id, 'contId' => $getUID, 'url' => $row->id, 'mode' => 'insert', 'langId' => $row->lang_id));

                $data = array(
                    array(
                        'id' => $getUID,
                        'mod_id' => $row->mod_id,
                        'cat_id' => $row->cat_id,
                        'parent_id' => $row->parent_id,
                        'show_pic_outside' => $row->show_pic_outside,
                        'show_pic_inside' => $row->show_pic_inside,
                        'pic' => $row->pic,
                        'show_title' => $row->show_title,
                        'title' => $row->title,
                        'link_title' => $row->link_title,
                        'intro_text' => $row->intro_text,
                        'full_text' => $row->full_text,
                        'page_title' => $row->page_title,
                        'meta_key' => $row->meta_key,
                        'meta_desc' => $row->meta_desc,
                        'h1_text' => $row->h1_text,
                        'show_date' => $row->show_date,
                        'created_date' => $row->created_date,
                        'modified_date' => $row->created_date,
                        'is_active_date' => $row->created_date,
                        'show_people' => $row->show_people,
                        'people_id' => $row->people_id,
                        'created_user_id' => $row->created_user_id,
                        'modified_user_id' => $row->modified_user_id,
                        'show_comment' => $row->show_comment,
                        'comment_count' => $row->comment_count,
                        'show_click' => $row->show_click,
                        'click' => $row->click,
                        'click_real' => $row->click_real,
                        'is_active' => $row->is_active,
                        'order_num' => $row->order_num,
                        'show_social' => $row->show_social,
                        'param' => $row->param,
                        'lang_id' => $row->lang_id,
                        'theme_layout_id' => $row->theme_layout_id,
                        'partner_id' => $row->partner_id));

                if ($this->db->insert_batch($this->db->dbprefix . 'content', $data)) {
                    
                }
            }
        }

        return false;
    }

}
