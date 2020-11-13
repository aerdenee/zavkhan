<?php

class Spermission_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => 29,
            'cat_id' => 0,
            'user_id' => '',
            'permission' => '',
            'created_date' => '',
            'modified_date' => '',
            'created_user_id' => 0,
            'modified_user_id' => 0
        )));
    }

    public function editFormData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                P.id,
                P.mod_id,
                P.cat_id,
                P.user_id
                U.email,
                U.user,
                U.lname,
                U.fname,
                CONCAT(SUBSTRING(U.lname, 1, 1), \'.\', U.fname) AS full_name,
                U.phone,
                P.created_date,
                U.pic,
                P.modified_date,
                P.created_user_id,
                P.modified_user_id,
                P.permission
            FROM `' . $this->db->dbprefix . 'permission` AS P
            LEFT JOIN `' . $this->db->dbprefix . 'user` AS U ON P.user_id = U.id
            WHERE P.id = ' . $param['selectedId']);


        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function insert_model($param = array('pic' => '', 'getUID' => 0)) {

        $this->data = array(
            array(
                'id' => $param['getUID'],
                'user_id' => $this->input->post('userId'),
                'permission' => md5($this->input->post('permission')),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0
            )
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'user', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'danger', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array('pic' => '')) {

        $this->data = array(
            array(
                'permission' => md5($this->input->post('permission')),
                'modified_date' => date('Y-m-d H:i:s'),
                'modified_user_id' => $this->session->adminUserId
            )
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'user', $this->data)) {

            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function listsCount_model($param = array()) {
        $this->queryString = $this->queryStringField = '';

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(U.lname_mn) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(U.fname_mn) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(U.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(U.email) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        $this->query = $this->db->query('
            SELECT 
                U.id 
            FROM `' . $this->db->dbprefix . 'permission` AS P 
            LEFT JOIN `' . $this->db->dbprefix . 'user` AS U ON P.user_id = U.id 
            WHERE 1 = 1 ' . $this->queryString . ' AND P.user_id != ' . $this->session->adminUserId . ' GROUP BY P.user_id');

        return $this->query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $this->queryString = $this->getString = '';
        $this->auth = authentication(array('authentication' => $this->session->authentication, 'modId' => $param['modId']));

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(U.lname_mn) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(U.fname_mn) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(U.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(U.email) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        $this->query = $this->db->query('
            SELECT 
                U.id,
                U.email,
                U.pic,
                U.lname,
                U.fname,
                CONCAT(SUBSTR(U.lname,1,1), \'.\', U.fname) AS full_name,
                U.phone,
                P.permission,
                P.created_date,
                P.mod_id,
                P.cat_id,
                P.modified_date,
                P.created_user_id,
                P.modified_user_id
            FROM `' . $this->db->dbprefix . 'permission` AS P 
            LEFT JOIN `' . $this->db->dbprefix . 'user` AS U ON P.user_id = U.id 
            WHERE 1 = 1 ' . $this->queryString . ' AND P.user_id != ' . $this->session->adminUserId . ' GROUP BY P.user_id
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        $this->html = form_open('', array('class' => 'form-horizontal', 'id' => 'form-reservation-export', 'enctype' => 'multipart/form-data'));
        $this->html .= form_hidden('modId', $param['modId']);
        $this->html .= form_hidden('limit', $param['limit']);
        $this->html .= form_hidden('page', $param['page']);
        $this->html .= form_hidden('our[\'create\']', $this->auth->our->create);
        $this->html .= form_hidden('our[\'read\']', $this->auth->our->read);
        $this->html .= form_hidden('our[\'update\']', $this->auth->our->update);
        $this->html .= form_hidden('our[\'delete\']', $this->auth->our->delete);
        $this->html .= $this->getString;

        $this->html .= '<div class="panel panel-white">';
        $this->html .= '<div class="panel-heading">';
        $this->html .= '<h6 class="panel-title">Хандах эрхийн тохиргоо</h6>';
        $this->html .= '<div class="heading-elements not-collapsible">';
        $this->html .= '<span class="label bg-blue heading-text">259 new today</span>';
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

        $this->html .= '<div class="navbar-btn navbar-left"></div>';

        $this->html .= '<div class="btn-group navbar-btn navbar-right">';
        $this->html .= form_button('addPermission', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Шинэ</span>', 'class="btn btn-default legitRipple" onclick="_addFormPermission({elem: this, modId:' . $param['modId'] . '});"', 'button');
        $this->html .= '<button type="button" class="btn btn-default legitRipple"><i class="icon-bin"></i> <span class="hidden-xs position-right">Delete</span></button>';
        $this->html .= '<button type="button" class="btn btn-default legitRipple"><i class="icon-spam"></i> <span class="hidden-xs position-right">Spam</span></button>';

        $this->html .= '</div>';

        $this->html .= '</div>';
        $this->html .= '</div>';
        $this->html .= '</div>';

        if ($this->query->num_rows() > 0) {

            $this->html .= '<div class="table-responsive">';
            $this->html .= '<table class="table">';
            $this->html .= '<thead>';
            $this->html .= '<tr>';
            $this->html .= '<th style="width:30px;">#</th>';
            $this->html .= '<th style="width:100px;">Зураг</th>';
            $this->html .= '<th style="width:200px;">Овог, нэр</th>';
            $this->html .= '<th style="width:200px;">Утас</th>';
            $this->html .= '<th>Мэйл</th>';
            $this->html .= '<th style="width:150px;" class="text-center">Нэвтрэх</th>';
            $this->html .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->html .= '</tr>';
            $this->html .= '</thead>';
            $this->html .= '<tbody data-link="row" class="rowlink">';

            $i = 1;
            foreach ($this->query->result() as $key => $row) {
                $this->html .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '">';
                $this->html .= '<td>' . $i++ . '</td>';
                $this->html .= '<td class="context-menu-permission-selected-row text-center"><img src="' . UPLOADS_USER_PATH . CROP_SMALL . $row->pic . '" style="width:50px;"></td>';
                $this->html .= '<td class="context-menu-permission-selected-row">' . $row->full_name . '</td>';
                $this->html .= '<td class="context-menu-permission-selected-row">' . $row->phone . '</td>';
                $this->html .= '<td class="context-menu-permission-selected-row">' . $row->email . '</td>';
                $this->html .= '<td class="text-center">';
                $this->html .= '<ul class="icons-list">';
                $this->html .= '<li><a href="javascript:;" onclick="_editFormPermission({elem: this, modId: ' . $row->mod_id . ', id: ' . $row->id . '})"><i class="icon-pencil7"></i></a></li>';
                $this->html .= '<li><a href="javascript:;" onclick="_removeItemPermission({elem: this, modId: ' . $row->mod_id . ', id: ' . $row->id . '});"><i class="icon-trash"></i></a></li>';
                $this->html .= '</ul>';
                $this->html .= '</td>';
                $this->html .= '</tr>';
                $i++;
            }

            $this->html .= '</tbody>';
            $this->html .= '</table>';
            $this->html .= '</div>';
            $this->html .= '<div class="panel-footer">';

            $this->html .= '<div class="navbar text-right">' . $param['paginationHtml'] . '</div>';
            $this->html .= '</div>';
        } else {
            $this->html .= '<div class="panel-body">';
            $this->html .= '<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Бичлэг байхгүй</div>';
            $this->html .= '</div>';
        }

        $this->html .= '</div>';


        $this->html .= form_close();
        return $this->html;
    }

    public function delete_model() {

        foreach ($this->input->post('id') as $key => $row) {

            $this->db->where('id', $this->input->post('id[' . $key . ']'));
            $this->db->delete($this->db->dbprefix . 'permission');
        }
        return array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай устгалаа');
    }

    public function searchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {


        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->showResetBtn = TRUE;
        }

        return $this->string;
    }

    public function getUserPermissionData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                P.permission
            FROM `' . $this->db->dbprefix . 'permission` AS P
            WHERE P.user_id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            $this->row = $this->query->row();
            return json_decode($this->row->permission);
        }

        $this->permissionConfig = array();

        $this->query = $this->db->query('
            SELECT 
                MM.id,
                MM.module_id,
                MM.mod_id,
                M.permission
            FROM `gaz_module_menu` AS MM
            LEFT JOIN `gaz_module` AS M ON MM.mod_id = M.id
            WHERE  1 = 1');
        foreach ($this->query->result() as $row) {
            $ourData = $yourData = $reportData = $customData = array();

            if ($row->permission != '') {
                $this->access = json_decode($row->permission);
                foreach ($this->access->crudOur as $ourRow) {
                    array_push($ourData, array('mode' => $ourRow->mode, 'status' => $ourRow->status));
                }
                foreach ($this->access->crudYour as $yourRow) {
                    array_push($yourData, array('mode' => $yourRow->mode, 'status' => $yourRow->status));
                }

                foreach ($this->access->custom as $customRow) {
                    array_push($customData, array('mode' => $customRow->mode, 'status' => $customRow->status));
                }
            } else {
                $ourData = array(
                    array('mode' => 'create', 'status' => 0, 'title' => 'Бичих'),
                    array('mode' => 'read', 'status' => 0, 'title' => 'Унших'),
                    array('mode' => 'update', 'status' => 0, 'title' => 'Засах'),
                    array('mode' => 'delete', 'status' => 0, 'title' => 'Устгах'));

                $yourData = array(
                    array('mode' => 'read', 'status' => 0, 'title' => 'Унших'),
                    array('mode' => 'update', 'status' => 0, 'title' => 'Засах'),
                    array('mode' => 'delete', 'status' => 0, 'title' => 'Устгах'));

                $customData = array(
                    array('mode' => 'report', 'status' => 0, 'title' => 'Тайлан'),
                    array('mode' => 'export', 'status' => 0, 'title' => 'Экспорт'),
                    array('mode' => 'close', 'status' => 0, 'title' => 'Хаах'));
            }

            array_push($this->permissionConfig, array(
                'id' => $row->id,
                'modId' => $row->mod_id,
                'isModule' => 0,
                'crudOur' => $ourData,
                'crudYour' => $yourData,
                'custom' => $customData
            ));
        }

        return json_decode(json_encode($this->permissionConfig));
    }

    public function setPermissionForm_model($param = array()) {

        $this->queryString = $this->getString = '';

        $this->auth = authentication(array(
            'permission' => $this->session->authentication, 
            'moduleMenuId' => $param['moduleMenuId'], 
            'createdUserId' => $param['createdUserId'], 
            'currentUserId' => $this->session->userdata['adminUserId'],
            'role' => 'update'));
        
        $getUserPermissionData = self::getUserPermissionData_model(array('selectedId' => $param['userId']));

        if ($this->auth->our->read and $this->auth->your->read) {
            $this->queryString .= '';
        } else if ($this->auth->our->read and !$this->auth->your->read) {
            $this->queryString .= ' AND MM.created_user_id = ' . $this->session->userdata['adminUserId'];
        } else {
            $this->queryString .= ' AND MM.created_user_id = -1';
        }

        $this->query = $this->db->query('
            SELECT 
                MM.id,
                MM.module_id,
                MM.mod_id,
                MM.parent_id,
                MM.title,
                MM.order_num,
                MM.partner_id,
                MM.created_date,
                MM.modified_date,
                MM.created_user_id,
                MM.modified_user_id,
                MM.is_active,
                MM.column_count,
                MM.menu_class,
                MM.menu_css,
                MM.icon,
                M.title AS module_title,
                M.category_title,
                M.permission
            FROM `gaz_module_menu` AS MM
            LEFT JOIN `gaz_module` AS M ON MM.mod_id = M.id
            WHERE  1 = 1 AND MM.parent_id = 0 ' . $this->queryString . '
            ORDER BY MM.order_num ASC');

        $this->html = form_open('javascript:;', array('class' => 'form-horizontal pt-0', 'id' => 'form-set-permission', 'enctype' => 'multipart/form-data'));


        $this->html .= form_hidden('modId', $this->auth->modId);
        $this->html .= form_hidden('userId', $param['userId']);

        if ($this->query->num_rows() > 0) {

            $this->html .= '<style type="text/css">.uniform-checker, .uniform-choice {margin-top: 0 !important;}</style>';
            $this->html .= '<div class="table-responsive _dialog">';
            $this->html .= '<table class="table _permission">';
            $this->html .= '<thead>';
            $this->html .= '<tr>';
            $this->html .= '<th style="width:30px;">#</th>';
            $this->html .= '<th>Гарчиг</th>';
            $this->html .= '<th style="width:200px;" class="text-center">Модуль</th>';
            $this->html .= '<th style="width:280px;" class="text-center">Өөрийн эрх</th>';
            $this->html .= '<th style="width:220px;" class="text-center">Бусдад хандах эрх</th>';
            $this->html .= '<th style="width:180px;" class="text-center">Бусад</th>';
            $this->html .= '</tr>';
            $this->html .= '</thead>';
            $this->html .= '<tbody>';

            $rowNumber = 0;
            $i = 1;
            foreach ($this->query->result() as $key => $row) {

                $rowNumber++;

                $row->permission = json_decode($row->permission);

                $this->getUserPermission = self::getUserPermission_model(array('data' => $getUserPermissionData, 'moduleMenuId' => $row->id));

                $this->our = $this->your = '';

                $this->html .= '<tr style="padding-top:10px; padding-bottom:10px; border-bottom:1px solid rgba(0,0,0,0.1); background-color:rgba(0,0,0,0.1);">';
                $this->html .= '<td class="_number">' . $i . '</td>';
                $this->html .= '<td>' . $row->title . '</td>';
                $this->html .= '<td data-id="' . $row->id . '">';
                $this->html .= '<div class="form-check">';
                $this->html .= '<label class="form-check-label" data-popup="tooltip" title="' . $row->module_title . '" data-placement="top" style="margin:0;">';
                $this->html .= form_input(array('name' => 'rowNumber[]', 'type' => 'hidden', 'value' => $rowNumber));
                $this->html .= form_input(array('name' => 'moduleMenu' . $rowNumber, 'type' => 'hidden', 'value' => $row->id));
                $this->html .= form_input(array('name' => 'modId' . $rowNumber, 'type' => 'hidden', 'value' => $row->mod_id));
                $this->html .= form_input(array('name' => 'isModule' . $rowNumber, 'type' => 'hidden', 'value' => $this->getUserPermission->isModule, 'data-permission-' . $row->id => 'input', 'data' => 'isModule'));

                $this->html .= form_checkbox(array('class' => 'checkbox', 'name' => 'controlModule' . $rowNumber), $this->getUserPermission->isModule, ($this->getUserPermission->isModule == 1 ? true : false), ' onclick="_setPermissionControl({elem:this});" data-permission-' . $row->id . '="check"');
                $this->html .= $row->module_title;
                $this->html .= '</label>';
                $this->html .= '</div>';

                $this->html .= '</td>';

                $this->html .= '<td>';
                $this->html .= '<table>';
                $this->html .= '<tr>';

                foreach ($row->permission->crudOur as $ourKey => $ourRow) {

                    $this->getUserOur = $this->getUserCRUDPermission_model(array('CRUD' => $this->getUserPermission->crudOur, 'mode' => $ourRow->mode));

                    if ($this->getUserOur) {
                        $this->html .= '<td data-id="' . $row->id . '2' . $ourKey . '" style="background:none;">';
                        $this->html .= '<div class="form-check">';
                        $this->html .= '<label class="form-check-label" data-popup="tooltip" title="' . $row->title . '" data-placement="top" style="margin-bottom:0; margin-right:10px;">';
                        $this->html .= form_input(array('name' => 'our' . $rowNumber . '[' . $ourRow->mode . ']', 'type' => 'hidden', 'value' => $this->getUserOur->status, 'data-permission-' . $row->id . '2' . $ourKey => 'input', 'data-permission-' . $row->id => 'input', 'data' => 'our[' . $ourRow->mode . ']'));
                        $this->html .= form_checkbox(array('class' => 'checkbox', 'name' => 'controlOur' . $rowNumber . '[' . $ourRow->mode . ']'), $this->getUserOur->status, ($this->getUserOur->status == 1 ? TRUE : FALSE), ' onclick="_setPermissionControl({elem:this});" data-permission-' . $row->id . '="check"');
                        $this->html .= $ourRow->title;
                        $this->html .= '</label>';
                        $this->html .= '</div>';
                        $this->html .= '</td>';
                    } else {

                        $this->html .= '<td data-id="' . $row->id . '2' . $ourKey . '" style="background:none;">';
                        $this->html .= '<div class="form-check">';
                        $this->html .= '<label class="form-check-label" data-popup="tooltip" title="' . $row->title . '" data-placement="top" style="margin-bottom:0; margin-right:10px;">';
                        $this->html .= form_input(array('name' => 'our' . $rowNumber . '[' . $ourRow->mode . ']', 'type' => 'hidden', 'value' => 0, 'data-permission-' . $row->id . '2' . $ourKey => 'input', 'data-permission-' . $row->id => 'input', 'data' => 'our[' . $ourRow->mode . ']'));
                        $this->html .= form_checkbox(array('class' => 'checkbox permission' . $row->id, 'name' => 'controlOur' . $rowNumber . '[' . $ourRow->mode . ']'), 0, FALSE, ' onclick="_setPermissionControl({elem:this});" data-permission-' . $row->id . '="check"');
                        $this->html .= $ourRow->title;
                        $this->html .= '</label>';
                        $this->html .= '</div>';
                        $this->html .= '</td>';
                    }
                }

                $this->html .= '</tr>';
                $this->html .= '</table>';
                $this->html .= '</td>';

                $this->html .= '<td>';
                $this->html .= '<table>';
                $this->html .= '<tr>';

                foreach ($row->permission->crudYour as $yourKey => $yourRow) {

                    $this->getUserYour = $this->getUserCRUDPermission_model(array('CRUD' => $this->getUserPermission->crudYour, 'mode' => $yourRow->mode));

                    if ($this->getUserYour) {

                        $this->html .= '<td data-id="' . $row->id . '3' . $yourKey . '" style="background:none;">';
                        $this->html .= '<div class="form-check">';
                        $this->html .= '<label class="form-check-label" data-popup="tooltip" title="' . $row->title . '" data-placement="top" style="margin-bottom:0; margin-right:10px;">';
                        $this->html .= form_input(array('name' => 'your' . $rowNumber . '[' . $yourRow->mode . ']', 'type' => 'hidden', 'value' => $this->getUserYour->status, 'data-permission-' . $row->id . '3' . $yourKey => 'input', 'data-permission-' . $row->id => 'input', 'data' => 'your[' . $yourRow->mode . ']'));
                        $this->html .= form_checkbox(array('class' => 'checkbox', 'name' => 'controlYour' . $rowNumber . '[' . $yourRow->mode . ']'), $this->getUserYour->status, ($this->getUserYour->status == 1 ? TRUE : FALSE), ' onclick="_setPermissionControl({elem:this});" data-permission-' . $row->id . '="check"');
                        $this->html .= $yourRow->title;
                        $this->html .= '</label>';
                        $this->html .= '</div>';
                        $this->html .= '</td>';
                    } else {

                        $this->html .= '<td data-id="' . $row->id . '3' . $yourKey . '" style="background:none;">';
                        $this->html .= '<div class="form-check">';
                        $this->html .= '<label class="form-check-label" data-popup="tooltip" title="' . $row->title . '" data-placement="top" style="margin-bottom:0; margin-right:10px;">';
                        $this->html .= form_input(array('name' => 'your' . $rowNumber . '[' . $yourRow->mode . ']', 'type' => 'hidden', 'value' => 0, 'data-permission-' . $row->id . '3' . $yourKey => 'input', 'data-permission-' . $row->id => 'input', 'data' => 'your[' . $yourRow->mode . ']'));
                        $this->html .= form_checkbox(array('class' => 'checkbox', 'name' => 'controlYour' . $rowNumber . '[' . $yourRow->mode . ']'), 0, FALSE, ' onclick="_setPermissionControl({elem:this});" data-permission-' . $row->id . '="check"');
                        $this->html .= $yourRow->title;
                        $this->html .= '</label>';
                        $this->html .= '</div>';
                        $this->html .= '</td>';
                    }
                }

                $this->html .= '</tr>';
                $this->html .= '</table>';
                $this->html .= '</td>';

                $this->html .= '<td>';
                $this->html .= '<table>';
                $this->html .= '<tr>';

                foreach ($row->permission->custom as $customKey => $customRow) {

                    $this->getUserCustom = $this->getUserCRUDPermission_model(array('CRUD' => $this->getUserPermission->custom, 'mode' => $customRow->mode));

                    if ($this->getUserCustom) {

                        $this->html .= '<td data-id="' . $row->id . '5' . $customKey . '" style="background:none;">';
                        $this->html .= '<div class="form-check">';
                        $this->html .= '<label class="form-check-label" data-popup="tooltip" title="' . $row->title . '" data-placement="top" style="margin-bottom:0; margin-right:10px;">';
                        $this->html .= form_input(array('name' => 'custom' . $rowNumber . '[' . $customRow->mode . ']', 'type' => 'hidden', 'value' => $this->getUserCustom->status, 'data-permission-' . $row->id . '5' . $customKey => 'input', 'data-permission-' . $row->id => 'input', 'data' => 'custom[' . $customRow->mode . ']'));
                        $this->html .= form_checkbox(array('class' => 'checkbox', 'name' => 'controlCustom' . $rowNumber . '[' . $customRow->mode . ']'), $customRow->status, ($this->getUserCustom->status == 1 ? TRUE : FALSE), ' onclick="_setPermissionControl({elem:this});" data-permission-' . $row->id . '="check"');
                        $this->html .= $customRow->title;
                        $this->html .= '</label>';
                        $this->html .= '</div>';
                        $this->html .= '</td>';
                    } else {

                        $this->html .= '<td data-id="' . $row->id . '5' . $customKey . '" style="background:none;">';
                        $this->html .= '<div class="form-check">';
                        $this->html .= '<label class="form-check-label" data-popup="tooltip" title="' . $row->title . '" data-placement="top" style="margin-bottom:0; margin-right:10px;">';
                        $this->html .= form_input(array('name' => 'custom' . $rowNumber . '[' . $customRow->mode . ']', 'type' => 'hidden', 'value' => 0, 'data-permission-' . $row->id . '5' . $customKey => 'input', 'data-permission-' . $row->id => 'input', 'data' => 'custom[' . $customRow->mode . ']'));
                        $this->html .= form_checkbox(array('class' => 'checkbox', 'name' => 'controlCustom' . $rowNumber . '[' . $customRow->mode . ']'), 0, FALSE, ' onclick="_setPermissionControl({elem:this});" data-permission-' . $row->id . '="check"');
                        $this->html .= $customRow->title;
                        $this->html .= '</label>';
                        $this->html .= '</div>';
                        $this->html .= '</td>';
                    }
                }

                $this->html .= '</tr>';
                $this->html .= '</table>';
                $this->html .= '</td>';

                $this->html .= '</tr>';

                $setPermissionFormChildList = $this->setPermissionFormChildList_model(array('parentId' => $row->id, 'html' => '', 'space' => 20, 'number' => $i, 'permissionData' => $getUserPermissionData, 'rowNumber' => $rowNumber, 'paramInput' => 'data-permission-' . $row->id . '="input"', 'paramCheck' => 'data-permission-' . $row->id . '="check"'));
                $this->html .= $setPermissionFormChildList['html'];
                $rowNumber = $setPermissionFormChildList['rowNumber'];
                $i++;
            }

            $this->html .= '</tbody>';
            $this->html .= '</table>';
            $this->html .= '</div>';
            $this->html .= '<div class="panel-footer">';

            //$this->html .= '<div class="navbar text-right"></div>';
            $this->html .= '</div>';
        } else {
            $this->html .= '<div class="panel-body">';
            $this->html .= '<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Бичлэг байхгүй</div>';
            $this->html .= '</div>';
        }
        $this->html .= form_close();
        return $this->html;
    }

    public function setPermissionFormChildList_model($param = array('parentId' => 0, 'space' => 20, 'number' => 0, 'permissionData' => array())) {

        $htmlChild = $ourChild = $yourChild = '';

        $this->queryChild = $this->db->query('
            SELECT 
                MM.id,
                MM.module_id,
                MM.mod_id,
                MM.parent_id,
                MM.title,
                MM.order_num,
                MM.partner_id,
                MM.created_date,
                MM.modified_date,
                MM.created_user_id,
                MM.modified_user_id,
                MM.is_active,
                MM.column_count,
                MM.icon,
                MM.menu_class,
                MM.menu_css,
                M.title AS module_title,
                M.category_title,
                M.permission
            FROM `gaz_module_menu` AS MM
            LEFT JOIN `gaz_module` AS M ON MM.mod_id = M.id
            WHERE MM.parent_id = ' . $param['parentId'] . '
            ORDER BY MM.order_num ASC');
        if ($this->queryChild->num_rows() > 0) {
            $j = 1;

            foreach ($this->queryChild->result() as $key => $rowChild) {

                $param['rowNumber'] ++;

                $rowChild->permission = json_decode($rowChild->permission);

                $getChildUserPermission = self::getUserPermission_model(array('data' => $param['permissionData'], 'moduleMenuId' => $rowChild->id));

                $ourChild = $yourChild = '';

                $htmlChild .= '<tr>';
                $htmlChild .= '<td>' . $param['number'] . '.' . $j . '</td>';
                $htmlChild .= '<td class="_number" style="padding-left: ' . $param['space'] . 'px;">' . $rowChild->title . '</td>';
                $htmlChild .= '<td data-id="' . $rowChild->id . '">';
                $htmlChild .= '<div class="form-check">';
                $htmlChild .= '<label class="form-check-label" data-popup="tooltip" title="' . $rowChild->module_title . '" data-placement="top" style="margin:0;">';
                $htmlChild .= form_input(array('name' => 'rowNumber[]', 'type' => 'hidden', 'value' => $param['rowNumber']));
                $htmlChild .= form_input(array('name' => 'moduleMenu' . $param['rowNumber'], 'type' => 'hidden', 'value' => $rowChild->id));
                $htmlChild .= form_input(array('name' => 'modId' . $param['rowNumber'], 'type' => 'hidden', 'value' => $rowChild->mod_id));
                //$htmlChild .= form_input(array('name' => 'isModule' . $param['rowNumber'], 'type' => 'hidden', 'value' => $getChildUserPermission->isModule, 'data-permission-' . $rowChild->id => 'input', 'data' => 'isModule'), $param['paramInput']);
                $htmlChild .= '<input type="hidden" name="isModule' . $param['rowNumber'] . '" value="' . $getChildUserPermission->isModule . '" data-permission-' . $rowChild->id . '="input" ' . $param['paramInput'] . ' data="isModule">';

                $htmlChild .= form_checkbox(array('class' => 'checkbox', 'name' => 'controlModule' . $rowChild->id), $getChildUserPermission->isModule, ($getChildUserPermission->isModule == 1 ? TRUE : FALSE), ' data-permission-' . $rowChild->id . '="check" ' . $param['paramCheck'] . ' onclick="_setPermissionControl({elem:this});"');
                $htmlChild .= $rowChild->module_title;
                $htmlChild .= '</label>';
                $htmlChild .= '</div>';
                $htmlChild .= '</td>';

                $htmlChild .= '<td>';
                $htmlChild .= '<table>';
                $htmlChild .= '<tr>';
                foreach ($rowChild->permission->crudOur as $ourKey => $ourRow) {

                    $getUserOur = $this->getUserCRUDPermission_model(array('CRUD' => $getChildUserPermission->crudOur, 'mode' => $ourRow->mode));
                    if (is_object($getUserOur)) {
                        $htmlChild .= '<td data-id="' . $rowChild->id . '2' . $ourKey . '" style="background:none;">';
                        $htmlChild .= '<div class="form-check">';
                        $htmlChild .= '<label class="form-check-label" data-popup="tooltip" title="' . $rowChild->title . '" data-placement="top" style="margin-bottom:0; margin-right:10px;">';
                        //$htmlChild .= form_input(array('name' => 'our' . $param['rowNumber'] . '[' . $ourRow->mode . ']', 'type' => 'hidden', 'value' => $getUserOur->status, 'data-permission-' . $rowChild->id => 'input', 'data-permission-' . $param['parentId'] => 'input', 'data' => 'our[' . $ourRow->mode . ']'));
                        $htmlChild .= '<input type="hidden" name="our' . $param['rowNumber'] . '[' . $ourRow->mode . ']" value="' . $getUserOur->status . '" data-permission-' . $rowChild->id . '2' . $ourKey . '="input" data-permission-' . $rowChild->id . '="input" ' . $param['paramInput'] . ' data="our[' . $ourRow->mode . ']">';
                        $htmlChild .= form_checkbox(array('class' => 'checkbox', 'name' => 'controlOur' . $param['rowNumber'] . '[' . $ourRow->mode . ']'), $getUserOur->status, ($getUserOur->status == 1 ? TRUE : FALSE), ' data-permission-' . $rowChild->id . '="check" ' . $param['paramCheck'] . ' onclick="_setPermissionControl({elem:this});"');
                        $htmlChild .= $ourRow->title;
                        $htmlChild .= '</label>';
                        $htmlChild .= '</div>';
                        $htmlChild .= '</td>';
                    } else {

                        $rowLabelText = $rowMode = '';

                        if ($ourKey == 0) { //create
                            $rowLabelText = 'Бичих';
                            $rowMode = 'create';
                        }

                        if ($ourKey == 1) { //read
                            $rowLabelText = 'Унших';
                            $rowMode = 'read';
                        }

                        if ($ourKey == 2) { //update
                            $rowLabelText = 'Засах';
                            $rowMode = 'update';
                        }

                        if ($ourKey == 3) { //delete
                            $rowLabelText = 'Устгах';
                            $rowMode = 'delete';
                        }

                        $htmlChild .= '<td data-id="' . $rowChild->id . '2' . $ourKey . '" style="background:none;">';
                        $htmlChild .= '<div class="form-check">';
                        $htmlChild .= '<label class="form-check-label" data-popup="tooltip" title="' . $rowChild->title . '" data-placement="top" style="margin-bottom:0; margin-right:10px;">';
                        //$htmlChild .= form_input(array('name' => 'our' . $param['rowNumber'] . '[' . $rowMode . ']', 'type' => 'hidden', 'value' => 0, 'data-permission-' . $rowChild->id => 'input', 'data-permission-' . $param['parentId'] => 'input', 'data' => 'our[' . $rowMode . ']'));
                        $htmlChild .= '<input type="hidden" name="our' . $param['rowNumber'] . '[' . $rowMode . ']" value="0" data-permission-' . $rowChild->id . '2' . $ourKey . '="input" data-permission-' . $rowChild->id . '="input" ' . $param['paramInput'] . ' data="our[' . $rowMode . ']">';
                        $htmlChild .= form_checkbox(array('class' => 'checkbox', 'name' => 'controlOur' . $param['rowNumber'] . '[' . $rowMode . ']'), 0, FALSE, ' data-permission-' . $rowChild->id . '="check" ' . $param['paramCheck'] . ' onclick="_setPermissionControl({elem:this});"');
                        $htmlChild .= $rowLabelText;
                        $htmlChild .= '</label>';
                        $htmlChild .= '</div>';
                        $htmlChild .= '</td>';
                    }
                }
                $htmlChild .= '</tr>';
                $htmlChild .= '</table>';
                $htmlChild .= '</td>';

                $htmlChild .= '<td>';
                $htmlChild .= '<table>';
                $htmlChild .= '<tr>';
                foreach ($rowChild->permission->crudYour as $yourKey => $yourRow) {
                    $getUserYour = $this->getUserCRUDPermission_model(array('CRUD' => $getChildUserPermission->crudYour, 'mode' => $yourRow->mode));
                    if (is_object($getUserYour)) {
                        $htmlChild .= '<td data-id="' . $rowChild->id . '3' . $yourKey . '" style="background:none;">';
                        $htmlChild .= '<div class="form-check">';
                        $htmlChild .= '<label class="form-check-label" data-popup="tooltip" title="' . $rowChild->title . '" data-placement="top" style="margin-bottom:0; margin-right:10px;">';
                        //$htmlChild .= form_input(array('name' => 'your' . $param['rowNumber'] . '[' . $yourRow->mode . ']', 'type' => 'hidden', 'value' => $getUserYour->status, 'data-permission-' . $rowChild->id => 'input', 'data-permission-' . $param['parentId'] => 'input', 'data' => 'your[' . $yourRow->mode . ']'));
                        $htmlChild .= '<input type="hidden" name="your' . $param['rowNumber'] . '[' . $yourRow->mode . ']" value="' . $getUserYour->status . '" data-permission-' . $rowChild->id . '3' . $yourKey . '="input" data-permission-' . $rowChild->id . '="input" ' . $param['paramInput'] . ' data="your[' . $yourRow->mode . ']">';
                        $htmlChild .= form_checkbox(array('class' => 'checkbox', 'name' => 'controlYour' . $param['rowNumber'] . '[' . $yourRow->mode . ']'), $getUserYour->status, ($getUserYour->status == 1 ? TRUE : FALSE), ' data-permission-' . $rowChild->id . '="check" ' . $param['paramCheck'] . ' onclick="_setPermissionControl({elem:this});"');
                        $htmlChild .= $yourRow->title;
                        $htmlChild .= '</label>';
                        $htmlChild .= '</div>';
                        $htmlChild .= '</td>';
                    } else {
                        $rowLabelText = $rowMode = '';

                        if ($yourKey == 0) { //read
                            $rowLabelText = 'Унших';
                            $rowMode = 'read';
                        }

                        if ($yourKey == 1) { //update
                            $rowLabelText = 'Засах';
                            $rowMode = 'update';
                        }

                        if ($yourKey == 2) { //delete
                            $rowLabelText = 'Устгах';
                            $rowMode = 'delete';
                        }

                        $htmlChild .= '<td data-id="' . $rowChild->id . '3' . $yourKey . '" style="background:none;">';
                        $htmlChild .= '<div class="form-check">';
                        $htmlChild .= '<label class="form-check-label" data-popup="tooltip" title="' . $rowChild->title . '" data-placement="top" style="margin-bottom:0; margin-right:10px;">';
                        $htmlChild .= '<input type="hidden" name="your' . $param['rowNumber'] . '[' . $rowMode . ']" value="0" data-permission-' . $rowChild->id . '3' . $yourKey . '="input" data-permission-' . $rowChild->id . '="input" ' . $param['paramInput'] . ' data="your[' . $rowMode . ']">';
                        $htmlChild .= form_checkbox(array('class' => 'checkbox', 'name' => 'controlYour' . $rowChild->id . '[' . $rowMode . ']'), 0, FALSE, ' data-permission-' . $rowChild->id . '="check" ' . $param['paramCheck'] . ' onclick="_setPermissionControl({elem:this});"');
                        $htmlChild .= $rowLabelText;
                        $htmlChild .= '</label>';
                        $htmlChild .= '</div>';
                        $htmlChild .= '</td>';
                    }
                }
                $htmlChild .= '</tr>';
                $htmlChild .= '</table>';
                $htmlChild .= '</td>';

                $htmlChild .= '<td>';
                $htmlChild .= '<table>';
                $htmlChild .= '<tr>';
                foreach ($rowChild->permission->custom as $customKey => $customRow) {

                    $getUserCustom = $this->getUserCRUDPermission_model(array('CRUD' => $getChildUserPermission->custom, 'mode' => $customRow->mode));

                    if (is_object($getUserCustom)) {
                        $htmlChild .= '<td data-id="' . $rowChild->id . '5' . $customKey . '" style="background:none;">';
                        $htmlChild .= '<div class="form-check">';
                        $htmlChild .= '<label class="form-check-label" data-popup="tooltip" title="' . $rowChild->title . '" data-placement="top" style="margin-bottom:0; margin-right:10px;">';
                        $htmlChild .= '<input type="hidden" name="custom' . $param['rowNumber'] . '[' . $customRow->mode . ']" value="' . $getUserCustom->status . '" data-permission-' . $rowChild->id . '5' . $customKey . '="input" data-permission-' . $rowChild->id . '="input" ' . $param['paramInput'] . ' data="custom[' . $customRow->mode . ']">';
                        $htmlChild .= form_checkbox(array('class' => 'checkbox', 'name' => 'controlCustom' . $param['rowNumber'] . '[' . $customRow->mode . ']'), $getUserCustom->status, ($getUserCustom->status == 1 ? TRUE : FALSE), ' data-permission-' . $rowChild->id . '="check" ' . $param['paramCheck'] . ' onclick="_setPermissionControl({elem:this});"');
                        $htmlChild .= $customRow->title;
                        $htmlChild .= '</label>';
                        $htmlChild .= '</div>';
                        $htmlChild .= '</td>';
                    } else {
                        $htmlChild .= '<td data-id="' . $rowChild->id . '5' . $customKey . '" style="background:none;">';
                        $htmlChild .= '<div class="form-check">';
                        $htmlChild .= '<label class="form-check-label" data-popup="tooltip" title="' . $rowChild->title . '" data-placement="top" style="margin-bottom:0; margin-right:10px;">';
                        $htmlChild .= '<input type="hidden" name="custom' . $param['rowNumber'] . '[' . $customRow->mode . ']" value="0" data-permission-' . $rowChild->id . '5' . $customKey . '="input" data-permission-' . $rowChild->id . '="input" ' . $param['paramInput'] . ' data="custom[' . $customRow->mode . ']">';
                        $htmlChild .= form_checkbox(array('class' => 'checkbox', 'name' => 'controlCustom' . $param['rowNumber'] . '[' . $customRow->mode . ']'), 0, FALSE, ' data-permission-' . $rowChild->id . '="check" ' . $param['paramCheck'] . ' onclick="_setPermissionControl({elem:this});"');
                        $htmlChild .= $customRow->title;
                        $htmlChild .= '</label>';
                        $htmlChild .= '</td>';
                    }
                }
                $htmlChild .= '</tr>';
                $htmlChild .= '</table>';
                $htmlChild .= '</td>';

                $htmlChild .= '</tr>';

                $setPermissionFormChildList = $this->setPermissionFormChildList_model(array('parentId' => $rowChild->id, 'html' => '', 'space' => ($param['space'] + 20), 'number' => $param['number'] . '.' . $j, 'permissionData' => $param['permissionData'], 'rowNumber' => $param['rowNumber'], 'paramInput' => $param['paramInput'] . ' data-permission-' . $rowChild->id . '="input"', 'paramCheck' => $param['paramCheck'] . ' data-permission-' . $rowChild->id . '="check"'));
                $htmlChild .= $setPermissionFormChildList['html'];
                $param['rowNumber'] = $setPermissionFormChildList['rowNumber'];
                $j++;
            }
        }
        return array('html' => $htmlChild, 'rowNumber' => $param['rowNumber']);
    }

    public function savePermission_model($param = array()) {

        $this->auth = array();
        foreach ($_POST['rowNumber'] as $key => $row) {

            $this->our = $this->your = $this->report = $this->custom = array();

            if (is_array($this->input->post('our' . $row)) and count($this->input->post('our' . $row)) > 0) {
                foreach ($this->input->post('our' . $row) as $ourKey => $ourRow) {
                    array_push($this->our, array(
                        'mode' => $ourKey,
                        'status' => $this->input->post('our' . $row . '[' . $ourKey . ']')));
                }
            }

            if (is_array($this->input->post('your' . $row)) and count($this->input->post('your' . $row)) > 0) {
                foreach ($this->input->post('your' . $row) as $yourKey => $yourRow) {
                    array_push($this->your, array(
                        'mode' => $yourKey,
                        'status' => $this->input->post('your' . $row . '[' . $yourKey . ']')));
                }
            }
            
            if (is_array($this->input->post('custom' . $row)) and count($this->input->post('custom' . $row)) > 0) {
                foreach ($this->input->post('custom' . $row) as $customKey => $customRow) {
                    array_push($this->custom, array(
                        'mode' => $customKey,
                        'status' => $this->input->post('custom' . $row . '[' . $customKey . ']')));
                }
            }


            $this->auth[$key] = array(
                'id' => $this->input->post('moduleMenu' . $row),
                'modId' => $this->input->post('modId' . $row),
                'isModule' => $this->input->post('isModule' . $row),
                'crudOur' => $this->our,
                'crudYour' => $this->your,
                'custom' => $this->custom
            );
        }

        $this->db->where('user_id', $this->input->post('userId'));
        $this->db->delete($this->db->dbprefix . 'permission');

        $this->data = array(
            array(
                'user_id' => $this->input->post('userId'),
                'permission' => json_encode($this->auth),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'order_num' => getOrderNum(array('table' => 'permission', 'field' => 'order_num'))));

        if ($this->db->insert_batch($this->db->dbprefix . 'permission', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'danger', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function getUserPermission_model($param = array('moduleMenuId' => 0, 'data' => array())) {

        $data = json_decode(json_encode(array(
            'id' => 0,
            'modId' => 0,
            'isModule' => 0,
            'crudOur' => array(
                array('mode' => 'create', 'status' => 0),
                array('mode' => 'read', 'status' => 0),
                array('mode' => 'update', 'status' => 0),
                array('mode' => 'delete', 'status' => 0)
            ),
            'crudYour' => array(
                array('mode' => 'create', 'status' => 0),
                array('mode' => 'read', 'status' => 0),
                array('mode' => 'update', 'status' => 0),
                array('mode' => 'delete', 'status' => 0)
            ),
            'custom' => array(
                array('mode' => 'report', 'status' => 0),
                array('mode' => 'close', 'status' => 0),
                array('mode' => 'export', 'status' => 0)
        ))));


        if (is_array($param['data']) and $param['data'] != '') {

            foreach ($param['data'] as $key => $row) {

                if (is_object($row) and $row->id == $param['moduleMenuId']) {

                    $data = $row;
                }
            }
        }
        return $data;
    }

    public function getUserCRUDPermission_model($param = array('CRUD' => array(), 'mode' => '')) {
        if (is_array($param['CRUD'])) {

            foreach ($param['CRUD'] as $key => $row) {

                if ($row->mode == $param['mode']) {
                    return $row;
                }
            }
        }
        return false;
    }

    public function removeUserPermission_model($param = array('pic' => '')) {

        $this->db->where('user_id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'permission')) {
            return array('status' => 'success', 'message' => 'Бүх тохиргоо устлаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function getModulePermission_model($param = array()) {

        $ourData = $yourData = $reportData = $customData = $modulePermissionData = array();

        $this->query = $this->db->query('
            SELECT 
                P.permission
            FROM `' . $this->db->dbprefix . 'permission` AS P
            WHERE P.user_id = ' . $param['userId']);

        if ($this->query->num_rows() > 0) {
            $this->row = $this->query->row();
            foreach (json_decode($this->row->permission) as $key => $row) {

                if ($param['modId'] == $row->modId) {

                    foreach ($row->crudOur as $ourRow) {
                        array_push($ourData, array('mode' => $ourRow->mode, 'status' => $ourRow->status));
                    }
                    foreach ($row->crudYour as $yourRow) {
                        array_push($yourData, array('mode' => $yourRow->mode, 'status' => $yourRow->status));
                    }
                    foreach ($row->custom as $customRow) {
                        array_push($customData, array('mode' => $customRow->mode, 'status' => $customRow->status));
                    }
                    break;
                }
            }

            array_push($modulePermissionData, array(
                'id' => $row->id,
                'modId' => $row->modId,
                'crudOur' => $ourData,
                'crudYour' => $yourData,
                'custom' => $customData
            ));
        } else {
            $ourData = array(
                array('mode' => 'create', 'status' => 0, 'title' => 'Бичих'),
                array('mode' => 'read', 'status' => 0, 'title' => 'Унших'),
                array('mode' => 'update', 'status' => 0, 'title' => 'Засах'),
                array('mode' => 'delete', 'status' => 0, 'title' => 'Устгах'));

            $yourData = array(
                array('mode' => 'read', 'status' => 0, 'title' => 'Унших'),
                array('mode' => 'update', 'status' => 0, 'title' => 'Засах'),
                array('mode' => 'delete', 'status' => 0, 'title' => 'Устгах'));

            $reportData = array(
                array('mode' => 'read', 'status' => 0, 'title' => 'Тайлан'));

            $customData = array(
                array('mode' => 'close', 'status' => 0, 'title' => 'Хаах'));

            array_push($modulePermissionData, array(
                'id' => 0,
                'modId' => 0,
                'crudOur' => $ourData,
                'crudYour' => $yourData,
                'report' => $reportData,
                'custom' => $customData
            ));
        }

        return json_decode(json_encode($modulePermissionData['0']));
    }

}
