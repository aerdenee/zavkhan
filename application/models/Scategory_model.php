<?php

class Scategory_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();

        $this->load->model('Simage_model', 'simage');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('Slog_model', 'slog');

        $this->modId = 55;
        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->isActiveDepartment = 'is_active_nifs_extra';
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => 0,
            'title' => '',
            'intro_text' => '',
            'parent_id' => '',
            'is_active' => 1,
            'order_num' => getOrderNum(array('table' => 'category', 'field' => 'order_num')),
            'cont_count' => '',
            'show_pic' => '',
            'pic' => $this->simage->returnDefaultImage_model(array('table' => 'category')),
            'theme_layout_id' => 1,
            'class' => '',
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s'),
            'created_user_id' => $this->session->userdata['adminUserId'],
            'modified_user_id' => 0,
            'department_id' => 0
        )));
    }

    public function editFormData_model($param = array('selectedId' => 0)) {

        $query = $this->db->query('
            SELECT 
                C.id,
                C.mod_id,
                C.title,
                C.intro_text,
                C.parent_id,
                C.is_active,
                C.order_num,
                C.cont_count,
                C.show_pic,
                (case when (C.pic is null or C.pic = \'\' or C.pic = \'default.svg\') then "' . $this->simage->returnDefaultImage_model(array('table' => 'category')) . '" else CONCAT("' . UPLOADS_CONTENT_PATH . CROP_SMALL . '", C.pic) end) as pic,
                C.theme_layout_id,
                C.class,
                C.created_date,
                C.modified_date,
                C.created_user_id,
                C.modified_user_id,
                C.department_id
            FROM `gaz_category` AS C
            WHERE C.id = ' . $param['selectedId']);

        if ($query->num_rows() > 0) {
            return $query->row();
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

        if ($this->session->adminAccessTypeId == 1) {
            if ($param['departmentId'] != 0) {

                $queryString .= ' AND C.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
                $getString .= form_hidden('departmentId', $param['departmentId']);
            }
        } else if ($this->session->adminAccessTypeId == 2) {
            if ($param['departmentId'] != 0) {

                $queryString .= ' AND C.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
                $getString .= form_hidden('departmentId', $param['departmentId']);
            } else {

                $queryString .= ' AND C.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ')';
            }
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND LOWER(C.title) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
        }

        $query = $this->db->query('
            SELECT 
                C.id
            FROM `gaz_category` AS C 
            WHERE C.lang_id = ' . $this->session->adminLangId . $queryString . ' AND C.mod_id = ' . $auth->modId . ' 
            ORDER BY C.id DESC');

        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $queryString = $getString = '';

        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and !$auth->your->read) {
            $queryString .= ' AND C.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND C.created_user_id = -1';
        }

        if ($this->session->adminAccessTypeId == 3) {
            if ($param['departmentId'] != 0) {

                $queryString .= ' AND C.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
                $getString .= form_hidden('departmentId', $param['departmentId']);
            }
        } else if ($this->session->adminAccessTypeId == 2) {
            if ($param['departmentId'] != 0) {

                $queryString .= ' AND C.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
                $getString .= form_hidden('departmentId', $param['departmentId']);
            } else {

                $queryString .= ' AND C.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ')';
            }
        }
        
        if ($param['keyword'] != '') {
            $queryString .= ' AND LOWER(C.title) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
            $getString .= form_hidden('keyword', $param['keyword']);
        }

        $query = $this->db->query('
            SELECT 
                C.id,
                C.title,
                C.order_num,
                C.mod_id,
                C.theme_layout_id,
                TL.title AS layout_title,
                DATE(C.created_date) AS created_date,
                C.modified_date,
                C.created_user_id,
                C.modified_user_id,
                IF(C.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                (case when (C.pic is null or C.pic = \'\' or C.pic = \'default.svg\') then "<img src=\"/assets/system/img/default.svg\">" else CONCAT("<img src=\"' . UPLOADS_CONTENT_PATH . CROP_SMALL . '", C.pic, "\">") end) as pic
            FROM `gaz_category` AS C
            LEFT JOIN `gaz_theme_layout` AS TL ON C.theme_layout_id = TL.id
            WHERE C.lang_id = ' . $this->session->adminLangId . ' AND C.parent_id = 0 ' . $queryString . ' AND C.mod_id = ' . $auth->modId . ' 
            ORDER BY C.id DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);


        $html = form_open('', array('class' => 'form-100 form-horizontal', 'id' => 'form-category-init', 'enctype' => 'multipart/form-data'));
        $html .= form_hidden('modId', $auth->modId);
        $html .= form_hidden('limit', $param['limit']);
        $html .= form_hidden('page', $param['page']);
        $html .= $getString;
        
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
        if ($auth->our->create) {

            $html .= '<td><a href="javascript:;" class="l-btn l-btn-small l-btn-plain" group="" id="" onclick="_addFormCategory({elem: this});"><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">Шинэ (F2)</span><span class="l-btn-icon dg-icon-add">&nbsp;</span></span></a></td>';
        } else {

            $html .= '<td><a href="javascript:;" class="l-btn l-btn-small l-btn-plain disabled" group="" id=""><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">Шинэ (F2)</span><span class="l-btn-icon dg-icon-add">&nbsp;</span></span></a></td>';
        }

        $html .= '<td><div class="datagrid-btn-separator"></div></td>';

        $html .= '<td><a href="javascript:;" class="l-btn l-btn-small l-btn-plain" onclick="_advensedSearchCategory({elem: this});"><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">Дэлгэрэнгүй хайлт (Ctrl+f)</span><span class="l-btn-icon dg-icon-search">&nbsp;</span></span></a></td>';

        $html .= '</tr></tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= self::searchKeywordView_model();
        $html .= '</div>';

        if ($query->num_rows() > 0) {

            $html .= '<div class="table-responsive">';
            $html .= '<table class="table">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th style="width:30px;">#</th>';
            $html .= '<th>Гарчиг</th>';
            $html .= '<th style="width:250px;">Ангилал</th>';
            $html .= '<th style="width:100px;" class="text-center">Огноо</th>';
            $html .= '<th style="width:60px;" class="text-center">ID</th>';
            $html .= '<th style="width:60px;" class="text-center">Төлөв</th>';
            $html .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody data-link="row" class="rowlink">';

            $i = 1;
            foreach ($query->result() as $key => $row) {

                $html .= '<tr data-id="' . $row->id . '" data-uid="' . $row->created_user_id . '">';
                $html .= '<td class="text-left">' . $i . '</td>';
                $html .= '<td class="context-menu-category-selected-row">' . $row->title . '</td>';
                $html .= '<td class="context-menu-category-selected-row">' . $row->layout_title . '</td>';
                $html .= '<td class="context-menu-category-selected-row text-center">' . $row->created_date . '</td>';
                $html .= '<td class="context-menu-category-selected-row text-center">' . $row->id . '</td>';
                $html .= '<td class="context-menu-category-selected-row text-center">' . $row->is_active . '</td>';
                $html .= '<td class="text-center">';
                $html .= '<div class="list-icons">';

                if (($auth->our->update and $row->created_user_id == $this->session->adminUserId) or ($auth->your->update and $row->created_user_id != $this->session->adminUserId)) {
                    $html .= '<div onclick="_editFormCategory({elem: this, id: ' . $row->id . '});" class="list-icons-item"><i class="icon-pencil7"></i></div>';
                } else {
                    $html .= '<div class="list-icons-item disabled"><i class="icon-pencil7"></i></div>';
                }

                if (($auth->our->delete and $row->created_user_id == $this->session->adminUserId) or ($auth->your->delete and $row->created_user_id != $this->session->adminUserId)) {
                    $html .= '<div onclick="_deleteCategory({elem: this, id: ' . $row->id . '});" class="list-icons-item"><i class="icon-trash"></i></div>';
                } else {
                    $html .= '<div class="list-icons-item disabled"><i class="icon-trash"></i></div>';
                }

                $html .= '</div>';
                $html .= '</td>';
                $html .= '</tr>';
                $html .= self::listsChild_model(array(
                    'parentId' => $row->id, 
                    'space' => 30, 
                    'autoNumber' => $i, 
                    'departmentId' => $param['departmentId'],
                    'auth' => $auth));
                $i++;
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

    public function listsChild_model($param = array('parentId' => 0, 'modId' => 0, 'space' => 10, 'autoNumber' => 1)) {

        $htmlChild = '';

        $query = $this->db->query('
            SELECT 
                C.id,
                C.title,
                C.order_num,
                C.mod_id,
                C.theme_layout_id,
                TL.title AS layout_title,
                DATE(C.created_date) AS created_date,
                C.modified_date,
                C.created_user_id,
                C.modified_user_id,
                IF(C.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                (case when (C.pic is null or C.pic = \'\') then \'default.svg\' else concat(\'s_\', C.pic) end) as pic
            FROM `gaz_category` AS C
            LEFT JOIN `gaz_theme_layout` AS TL ON C.theme_layout_id = TL.id
            WHERE C.lang_id = ' . $this->session->adminLangId . ' AND C.parent_id = ' . $param['parentId'] . ' AND C.mod_id = ' . $param['auth']->modId . ' 
            ORDER BY C.id DESC');

        if ($query->num_rows() > 0) {

            $j = 1;
            foreach ($query->result() as $row) {

                $htmlChild .= '<tr data-id="' . $row->id . '" data-uid="' . $row->created_user_id . '">';
                $htmlChild .= '<td class="text-left">' . $param['autoNumber'] . '.' . $j . '</td>';
                $htmlChild .= '<td class="context-menu-category-selected-row" style="padding-left: ' . $param['space'] . 'px;">' . $row->title . '</td>';
                $htmlChild .= '<td class="context-menu-category-selected-row">' . $row->layout_title . '</td>';
                $htmlChild .= '<td class="context-menu-category-selected-row text-center">' . $row->created_date . '</td>';
                $htmlChild .= '<td class="context-menu-category-selected-row text-center">' . $row->id . '</td>';
                $htmlChild .= '<td class="context-menu-category-selected-row text-center">' . $row->is_active . '</td>';

                $htmlChild .= '<td class="text-center">';
                $htmlChild .= '<div class="list-icons">';

                if (($param['auth']->our->update and $row->created_user_id == $this->session->adminUserId) or ($param['auth']->your->update and $row->created_user_id != $this->session->adminUserId)) {
                    $htmlChild .= '<div onclick="_editFormCategory({elem: this, id: ' . $row->id . '});" class="list-icons-item"><i class="icon-pencil7"></i></div>';
                } else {
                    $htmlChild .= '<div class="list-icons-item disabled"><i class="icon-pencil7"></i></div>';
                }

                if (($param['auth']->our->delete and $row->created_user_id == $this->session->adminUserId) or ($param['auth']->your->delete and $row->created_user_id != $this->session->adminUserId)) {
                    $htmlChild .= '<div onclick="_deleteCategory({elem: this, id: ' . $row->id . '});" class="list-icons-item"><i class="icon-trash"></i></div>';
                } else {
                    $htmlChild .= '<div class="list-icons-item disabled"><i class="icon-trash"></i></div>';
                }
                

                $htmlChild .= '</div>';
                $htmlChild .= '</td>';
                $htmlChild .= '</tr>';
                $htmlChild .= self::listsChild_model(array(
                    'parentId' => $row->id, 
                    'space' => (intval($param['space']) + 30), 
                    'autoNumber' => $param['autoNumber'] . '.' . $j, 
                    'departmentId' => $param['departmentId'],
                    'auth' => $param['auth']));
                $j++;
            }
        }
        return $htmlChild;
    }

    public function controlCategoryParentMultiRowDropdown_model($param = array('modId' => 0, 'selectedId' => 0, 'id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.title
            FROM `gaz_category` AS C
            WHERE C.lang_id = ' . $this->session->adminLangId . ' AND C.parent_id = 0 AND C.mod_id = ' . $param['modId'] . ' AND id != ' . $param['id'] . '
            ORDER BY C.order_num DESC');

        $this->html = '<select class="form-control" name="parentId" id="parentId" size="6" required="required">';

        $this->html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '>' . ' - Сонгох - </option>';

        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $row) {

                $this->html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';

                $this->html .= self::controlCategoryParentChildMultiRowDropdown_model(array('selectedId' => $param['selectedId'], 'id' => $param['id'], 'parentId' => $row->id, 'space' => '&nbsp; &nbsp; &nbsp;'));
            }
        }
        $this->html .= '</select>';

        return $this->html;
    }

    public function controlCategoryParentChildMultiRowDropdown_model($param = array('selectedId' => 0, 'id' => 0, 'parentId' => 0, 'space' => '')) {

        $html = '';
        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.title
            FROM `gaz_category` AS C
            WHERE C.lang_id = ' . $this->session->adminLangId . ' AND C.parent_id = ' . $param['parentId'] . ' AND id != ' . $param['id'] . '
            ORDER BY C.order_num DESC');

        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $row) {

                $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? ' selected="selected"' : '') . '>' . $param['space'] . $row->title . '</option>';

                $html .= self::controlCategoryParentChildMultiRowDropdown_model(array('selectedId' => $param['selectedId'], 'id' => $param['id'], 'parentId' => $row->id, 'space' => '&nbsp; &nbsp;  &nbsp;  &nbsp; ' . $param['space']));
            }
        }

        return $html;
    }

    public function controlLocationListDropdown_model($param = array('selectedId' => 0, 'id' => 0, 'parentId' => 0, 'space' => '')) {
        $query = $this->db->query('
            SELECT 
                C.id,
                C.title
            FROM `gaz_category` AS C
            WHERE C.lang_id = ' . $this->session->adminLangId . ' AND C.mod_id = ' . $param['modId'] . ' AND C.parent_id = ' . $param['parentId'] . ' 
            ORDER BY C.order_num ASC');

        $str = '<select class="form-control select2" name="locationId" id="locationId">';
        if ($param['selectedId'] == 0 and $param['counter'] == 1) {
            $str .= '<option value="0" selected="selected">' . ' - Сонгох - </option>';
        }
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $str .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . '&nbsp; &nbsp; ' . $param['counter'] . '. ' . $row->title . '</option>';
                $param['counter'] = $param['counter'] + 1;
                //$str .= self::categoryListChild_model(array('selectedId' => $param['selectedId'], 'parentId' => $row['id'], 'space' => '&nbsp; &nbsp; ' . $param['space'], 'counter' => $param['counter'], 'childHtml' => ''));
            }
        }
        $str .= '</select>';
        return $str;
    }

    public function controlCategoryListDropdown_model($param = array('modId' => 0, 'selectedId' => 0, 'required' => true)) {

        $this->queryString = $this->string = '';
        $controlName = 'catId';
//        if ($this->session->adminAccessTypeId == 2) {
//            $this->queryString .= ' AND partner_id = ' . $this->session->adminPartnerId;
//        } else {
//            $this->queryString .= ' AND parent_id = ' . $param['parentId'];
//        }

        if (isset($param['name'])) {
            $controlName = $param['name'];
        }

        if (isset($param['disabled']) and $param['disabled'] == true) {
            $this->string .= ' disabled="true"';
        }

        if (isset($param['required']) and $param['required'] == true) {
            $this->string .= ' required="true"';
        }

        if (isset($param['readonly']) and $param['readonly'] == true) {
            $this->string .= ' disabled="true"';
        }

        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.title
            FROM `gaz_category` AS C
            WHERE C.lang_id = ' . $this->session->adminLangId . ' AND C.parent_id = 0 AND C.mod_id = ' . $param['modId'] . $this->queryString . ' 
            ORDER BY C.order_num ASC');

        $this->html = '<select class="form-control select2" name="' . $controlName . '" id="' . $controlName . '" ' . $this->string . '>';

        $this->html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '>' . ' - Сонгох - </option>';

        if ($this->query->num_rows() > 0) {
            foreach ($this->query->result() as $row) {
                $this->html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>&nbsp; &nbsp;' . $row->title . '</option>';
                $this->html .= self::controlCategoryListChildDropdown_model(array('selectedId' => $param['selectedId'], 'parentId' => $row->id, 'space' => '&nbsp; &nbsp; &nbsp; &nbsp;'));
            }
        }
        $this->html .= '</select>';
        return $this->html;
    }

    public function controlCategoryListChildDropdown_model($param = array('selectedId' => 0, 'parentId' => 0, 'space' => '')) {

        $html = '';

        $this->query = $this->db->query('
            SELECT 
                id,
                title
            FROM `gaz_category`
            WHERE parent_id = ' . $param['parentId'] . ' 
            ORDER BY order_num ASC');

        if ($this->query->num_rows() > 0) {
            foreach ($this->query->result() as $row) {

                $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '> ' . $param['space'] . $row->title . '</option>';
                $html .= self::controlCategoryListChildDropdown_model(array('selectedId' => $param['selectedId'], 'parentId' => $row->id, 'space' => '&nbsp; &nbsp; &nbsp; &nbsp; ' . $param['space']));
            }
        }
        return $html;
    }

    public function insert_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        $data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'title' => $this->input->post('title'),
                'intro_text' => $this->input->post('introText'),
                'parent_id' => $this->input->post('parentId'),
                'is_active' => $this->input->post('isActive'),
                'order_num' => $this->input->post('orderNum'),
                'cont_count' => 0,
                'show_pic' => $this->input->post('showPic'),
                'pic' => $this->input->post('categoryPic'),
                'theme_layout_id' => $this->input->post('themeLayoutId'),
                'class' => $this->input->post('class'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'department_id' => $this->session->adminDepartmentId,
                'lang_id' => $this->session->adminLangId,
            )
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'category', $data)) {
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

        $data = array(
            'mod_id' => $this->input->post('modId'),
            'title' => $this->input->post('title'),
            'intro_text' => $this->input->post('introText'),
            'parent_id' => $this->input->post('parentId'),
            'is_active' => $this->input->post('isActive'),
            'order_num' => $this->input->post('orderNum'),
            'show_pic' => $this->input->post('showPic'),
            'pic' => ($this->input->post('categoryPic') != '' ? $this->input->post('categoryPic') : $this->input->post('categoryOldPic')),
            'theme_layout_id' => $this->input->post('themeLayoutId'),
            'class' => $this->input->post('class'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'category', $data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {

        if ($this->input->post('id')) {

            $listId = self::getChildCategores_model($this->input->post('id'));
            $this->query = $this->db->query('SELECT id, pic, mod_id FROM `gaz_category` WHERE id IN (' . $listId . ')');
            foreach ($this->query->result() as $key => $row) {
                
                $this->simage->removeUploadImage_model(array('uploadImage' => $row->pic, 'uploadPath' => UPLOADS_CONTENT_PATH));
                $this->slog->log_model(array(
                'modId' => $row->mod_id,
                'createdUserId' => $this->session->adminUserId,
                'type' => LOG_TYPE_DELETE,
                'data' => json_encode($row)));
            }
            if ($this->db->query('DELETE FROM `gaz_category` WHERE id IN (' . $listId . ')')) {
                return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
            }
        }

        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function isChild_model($catId) {
        $this->db->where('parent_id', $catId);
        $this->db->where('is_active', 1);
        $cateogry = $this->db->get($this->db->dbprefix . 'category');
        $cat = $cateogry->result();
        if (count($cat) > 0) {
            return true;
        }
        return false;
    }

    public function getChildCategores_model1($param = array()) {

        $this->data = '';
        if ($param != '') {

            $param = explode(',', $param);

            if (is_array($param) and count($param) > 0) {
                foreach ($param as $partnerKey => $partnerId) {
                    $this->query = $this->db->query('
                    SELECT 
                        C.id
                    FROM `' . $this->db->dbprefix . 'category` AS C 
                    WHERE C.is_active = 1 AND C.parent_id = ' . $partnerId);

                    if ($this->query->num_rows() > 0) {
                        foreach ($this->query->result() as $key => $row) {
                            $this->data .= $row->id . ',';
                        }
                        $this->data .= $partnerId . ',';
                    } else {
                        $this->data .= $partnerId . ',';
                    }
                }
            } else {
                $this->query = $this->db->query('
                    SELECT 
                        C.id
                    FROM `' . $this->db->dbprefix . 'category` AS C 
                    WHERE C.is_active = 1 AND C.parent_id = ' . $param);

                if ($this->query->num_rows() > 0) {
                    foreach ($this->query->result() as $key => $row) {
                        $this->data .= $row->id . ',';
                    }
                    $this->data .= $param . ',';
                } else {
                    $this->data .= $param . ',';
                }
            }
            return rtrim($this->data, ',');
        }

        return 0;
    }
    
    public function getChildCategores_model($param = array()) {

        $this->data = '';
        if ($param != '') {

            $param = explode(',', $param);

            if (is_array($param) and count($param) > 0) {
                foreach ($param as $partnerKey => $partnerId) {
                    $this->query = $this->db->query('
                    SELECT 
                        C.id
                    FROM `' . $this->db->dbprefix . 'category` AS C 
                    WHERE C.is_active = 1 AND C.parent_id = ' . $partnerId);

                    if ($this->query->num_rows() > 0) {
                        foreach ($this->query->result() as $key => $row) {
                            $this->data .= $row->id . ',';
                            $this->data .= self::getChildCategores_model($row->id) . ',';
                        }
                        $this->data .= $partnerId . ',';
                    } else {
                        $this->data .= $partnerId . ',';
                    }
                }
            } else {
                $this->query = $this->db->query('
                    SELECT 
                        C.id
                    FROM `' . $this->db->dbprefix . 'category` AS C 
                    WHERE C.is_active = 1 AND C.parent_id = ' . $param);

                if ($this->query->num_rows() > 0) {
                    foreach ($this->query->result() as $key => $row) {
                        $this->data .= $row->id . ',';
                        $this->data .= self::getChildCategores_model($row->id) . ',';
                    }
                    $this->data .= $param . ',';
                } else {
                    $this->data .= $param . ',';
                }
            }
            return rtrim($this->data, ',');
        }

        return 0;
    }

    public function getData_model($param = array('selectedId' => 0)) {

        if ($param['selectedId'] != NULL and $param['selectedId'] != 0) {

            $this->query = $this->db->query('
            SELECT 
                C.id,
                C.mod_id,
                C.title,
                C.intro_text,
                C.parent_id,
                C.is_active,
                C.order_num,
                C.cont_count,
                C.show_pic,
                (case when (C.pic is null OR C.pic = \'\' OR C.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', C.pic) end) as pic,
                C.theme_layout_id,
                TL.theme,
                C.class,
                C.created_date,
                C.modified_date,
                C.created_user_id,
                C.modified_user_id,
                C.partner_id
            FROM `' . $this->db->dbprefix . 'category` AS C
            LEFT JOIN `' . $this->db->dbprefix . 'theme_layout` AS TL ON C.theme_layout_id = TL.id
            WHERE C.id = ' . $param['selectedId']);

            if ($this->query->num_rows() > 0) {
                return $this->query->row();
            }
        }

        return false;
    }

    public function searchKeywordView_model($param = array('modId' => 0)) {
        $this->string = '';
        $this->showResetBtn = FALSE;
        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            $this->string .= ' <a href="javascript:;" onclick="_initCategory({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return $this->string;
    }

    public function getChildCategorys_model($param = array()) {

        $this->data = '';
        if (is_array($param) and count($param) > 0) {
            foreach ($param as $partnerKey => $partnerId) {
                $this->query = $this->db->query('
                    SELECT 
                        C.id
                    FROM `' . $this->db->dbprefix . 'category` AS C 
                    WHERE is_active = 1 AND parent_id = ' . $partnerId);

                if ($this->query->num_rows() > 0) {
                    foreach ($this->query->result() as $key => $row) {
                        $this->data .= $row->id . ',';
                    }
                    $this->data .= $partnerId . ',';
                } else {
                    $this->data .= $partnerId . ',';
                }
            }
        } else {
            $this->query = $this->db->query('
                    SELECT 
                        C.id
                    FROM `' . $this->db->dbprefix . 'category` AS C 
                    WHERE is_active = 1 AND parent_id = ' . $param);

            if ($this->query->num_rows() > 0) {
                foreach ($this->query->result() as $key => $row) {
                    $this->data .= $row->id . ',';
                }
                $this->data .= $param . ',';
            } else {
                $this->data .= $param . ',';
            }
        }

        return rtrim($this->data, ',');
    }

}
