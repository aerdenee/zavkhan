<?php

class Stour_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();

        $this->load->model('Simage_model', 'simage');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('Slog_model', 'slog');


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
            'pic' => 'default.svg',
            'show_title' => 1,
            'title' => '',
            'link_title' => '',
            'code' => '',
            'intro_text' => '',
            'full_text' => '',
            'details' => '',
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
                (case when (C.pic is null or C.pic = \'\' or C.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', C.pic) end) as pic,
                C.show_title,
                C.title,
                C.link_title,
                C.code,
                C.intro_text,
                C.full_text,
                C.details,
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
            FROM `gaz_tour` AS C
            LEFT JOIN `gaz_url` AS U ON C.mod_id = U.mod_id AND C.id = U.cont_id AND C.lang_id = U.lang_id
            WHERE C.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function listsCount_model($param = array()) {

        $this->queryString = '';

        $this->auth = $param['auth'];

        if ($this->auth->our->read and $this->auth->your->read) {
            $this->queryString .= '';
        } elseif ($this->auth->our->read and !$this->auth->your->read) {
            $this->queryString .= ' AND C.created_user_id = ' . $this->session->adminUserId;
        } else {
            $this->queryString .= ' AND C.created_user_id = -1';
        }

//        if ($this->session->adminDepartmentRoleId == 2) {
//
//            $this->queryString .= ' AND C.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ')';
//        } else {
//
//            if ($param['departmentId'] != 0) {
//
//                $this->queryString .= ' AND C.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
//            } else {
//
//                $this->queryString .= ' AND C.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ')';
//            }
//        }

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
            FROM `gaz_tour` AS C 
            WHERE C.lang_id = ' . $this->session->adminLangId . $this->queryString . ' AND C.mod_id = ' . $param['modId'] . ' 
            ORDER BY C.id DESC');

        return $this->query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $data = array();

        $this->queryString = $this->getString = '';

        $this->auth = $param['auth'];

        if ($this->auth->our->read and $this->auth->your->read) {
            $this->queryString .= '';
        } elseif ($this->auth->our->read and !$this->auth->your->read) {
            $this->queryString .= ' AND C.created_user_id = ' . $this->session->adminUserId;
        } else {
            $this->queryString .= ' AND C.created_user_id = -1';
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND C.cat_id = ' . $param['catId'];
            $this->getString .= form_hidden('catId', $param['catId']);
        }

        if ($param['peopleId'] != 0) {
            $this->queryString .= ' AND C.people_id = ' . $param['peopleId'];
            $this->getString .= form_hidden('peopleId', $param['peopleId']);
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
                CONCAT(C.title, \' <strong>\', C.code, \'</strong>\') AS title,
                (case when (C.pic is null or C.pic = \'\' or C.pic = \'default.svg\') then "<img src=\"' . UPLOADS_CONTENT_PATH . 'default.svg\">" else CONCAT("<img src=\"' . UPLOADS_CONTENT_PATH . CROP_SMALL . '", C.pic, "\">") end) as pic,
                C.order_num,
                C.created_date,
                DATE_FORMAT(C.modified_date, \'%Y-%m-%d\') AS modified_date,
                C.created_user_id,
                C.modified_user_id,
                IF(C.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                C.mod_id
            FROM `gaz_tour` AS C
            LEFT JOIN `gaz_category` AS CAT ON C.cat_id = CAT.id
            WHERE C.lang_id = ' . $this->session->adminLangId . $this->queryString . ' AND C.mod_id = ' . $param['modId'] . ' 
            ORDER BY C.order_num DESC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
            

        if ($this->query->num_rows() > 0) {
            foreach ($this->query->result() as $key => $row) {

                $html = '<div class="list-icons">';
                if (($this->auth->our->update and $row->created_user_id == $this->session->adminUserId) or ($this->auth->your->update and $row->created_user_id != $this->session->adminUserId)) {
                    $html .= '<div onclick="_editFormContent({elem: this, id:' . $row->id . '});" class="list-icons-item"><i class="icon-pencil7"></i></div>';
                } else {
                    $html .= '<div class="list-icons-item disabled"><i class="icon-pencil7"></i></div>';
                }

                if (($this->auth->our->delete and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->delete and $row->created_user_id != $this->session->adminUserId)) {
                    $html .= '<div onclick="_removeContent({elem: this, id:' . $row->id . '});" class="list-icons-item"><i class="icon-trash"></i></div>';
                } else {
                    $html .= '<div class="list-icons-item disabled"><i class="icon-trash"></i></div>';
                }
                $html .= '</div>';
                array_push($data, array(
                    'id' => $row->id,
                    'created_user_id' => $row->created_user_id,
                    'pic' => $row->pic,
                    'title' => $row->title,
                    'cat_title' => $row->cat_title,
                    'modified_date' => $row->modified_date,
                    'is_active' => $row->is_active,
                    'config' => $html
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
                'pic' => $this->input->post('pic'),
                'show_title' => $this->input->post('showTitle'),
                'title' => $this->input->post('title'),
                'link_title' => $this->input->post('linkTitle'),
                'code' => $this->input->post('code'),
                'intro_text' => $this->input->post('introText'),
                'full_text' => $this->input->post('fullText'),
                'details' => $this->input->post('details'),
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

        if ($this->db->insert_batch($this->db->dbprefix . 'tour', $data)) {
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
            'pic' => ($this->input->post('pic') != '' ? $this->input->post('pic') : $this->input->post('oldPic')),
            'show_title' => $this->input->post('showTitle'),
            'title' => $this->input->post('title'),
            'link_title' => $this->input->post('linkTitle'),
            'code' => $this->input->post('code'),
            'intro_text' => $this->input->post('introText'),
            'full_text' => $this->input->post('fullText'),
            'details' => $this->input->post('details'),
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
            'click' => 0,
            'click_real' => 0,
            'is_active' => $this->input->post('isActive'),
            'order_num' => $this->input->post('orderNum'),
            'show_social' => $this->input->post('showSocial'),
            'param' => '',
            'theme_layout_id' => $this->input->post('themeLayoutId'),
            'partner_id' => $this->input->post('partnerId')
        );

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'tour', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {

        $this->db->where('id', $this->input->post('id'));
        $query = $this->db->get($this->db->dbprefix . 'tour');
        $row = $query->row();

        $this->slog->log_model(array(
            'modId' => $row->mod_id,
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_DELETE,
            'data' => json_encode($row)));

        $this->simage->removeUploadImage_model(array('uploadImage' => $row->pic, 'uploadPath' => UPLOADS_CONTENT_PATH));
        
        generateUrl(array('modId' => $row->mod_id, 'contId' => $row->id, 'langId' => $row->lang_id, 'mode' => 'delete'));

        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'tour')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
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

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('catId')) {
            $this->cat = $this->category->getData_model(array('selectedId' => $this->input->get('catId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->cat->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            //' . Scrime::$path . $param['path'] . '/' . $param['modId'] . '
            $this->string .= ' <a href="javascript:;" onclick="_initContent({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return ($this->showResetBtn ? '<td><div class="datagrid-btn-separator"></div></td><td class="_search-result-td"> Хайлтын үр дүн: ' . $this->string . '</td>' : '');
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
            FROM `gaz_tour` AS C
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
            FROM `gaz_tour` AS C
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

}
