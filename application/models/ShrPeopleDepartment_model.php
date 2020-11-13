<?php

class ShrPeopleDepartment_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Suser_model', 'user');
        $this->load->model('Slog_model', 'slog');
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => 69,
            'cat_id' => 0,
            'parent_id' => 0,
            'title' => '',
            'short_title' => '',
            'address' => '',
            'phone' => '',
            'is_active' => 1,
            'is_active_control' => 1,
            'createed_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'order_num' => getOrderNum(array('table' => 'hr_people_department', 'field' => 'order_num')),
            'lat' => '45.76566361753729',
            'lng' => '106.26942002832186'
        )));
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.mod_id,
                HPD.cat_id,
                HPD.parent_id,
                HPD.title,
                HPD.short_title,
                HPD.address,
                HPD.phone,
                HPD.is_active,
                HPD.is_active_control,
                HPD.created_date,
                HPD.modified_date,
                HPD.created_user_id,
                HPD.modified_user_id,
                HPD.order_num,
                HPD.lat,
                HPD.lng
            FROM `gaz_hr_people_department` AS HPD
            WHERE HPD.id = ' . $param['id']);

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
        } else if ($auth->our->read and ! $auth->your->read) {
            $queryString .= ' AND HPD.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND HPD.created_user_id = -1';
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND HPD.cat_id = ' . $param['catId'];
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND (LOWER(HPD.title) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }


        $query = $this->db->query('
            SELECT 
                HPD.id
            FROM `gaz_hr_people_department` AS HPD 
            WHERE HPD.parent_id = 0 ' . $queryString);

        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $queryString = $getString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and ! $auth->your->read) {
            $queryString .= ' AND HPD.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND HPD.created_user_id = -1';
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND HPD.cat_id = ' . $param['catId'];
            $getString .= form_hidden('catId', $param['catId']);
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND (LOWER(HPD.title) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
            $getString .= form_hidden('keyword', $param['keyword']);
        }

        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.mod_id,
                HPD.cat_id,
                HPD.parent_id,
                HPD.title,
                IF(HPD.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                IF(HPD.is_active_control > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active_control,
                DATE(HPD.created_date) AS created_date,
                HPD.modified_date,
                HPD.created_user_id,
                HPD.modified_user_id,
                HPD.order_num
            FROM `gaz_hr_people_department` AS HPD
            WHERE HPD.parent_id = 0 ' . $queryString . '
            ORDER BY HPD.order_num DESC
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

            $html .= '<td><a href="javascript:;" class="l-btn l-btn-small l-btn-plain" group="" id="" onclick="_addFormHrPeopleDepartment({elem: this});"><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">Шинэ (F2)</span><span class="l-btn-icon dg-icon-add">&nbsp;</span></span></a></td>';
        } else {

            $html .= '<td><a href="javascript:;" class="l-btn l-btn-small l-btn-plain disabled" group="" id=""><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">Шинэ (F2)</span><span class="l-btn-icon dg-icon-add">&nbsp;</span></span></a></td>';
        }

        $html .= '<td><div class="datagrid-btn-separator"></div></td>';

        $html .= '<td><a href="javascript:;" class="l-btn l-btn-small l-btn-plain" onclick="_advensedSearchHrPeopleDepartment({elem: this});"><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">Дэлгэрэнгүй хайлт (Ctrl+f)</span><span class="l-btn-icon dg-icon-search">&nbsp;</span></span></a></td>';

        $html .= '</tr></tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= self::searchKeywordView_model();
        $html .= '</div>';

        if ($query->num_rows() > 0) {

            $html .= '<div class="table-responsive">';
            $html .= '<table class="table _dg">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th style="width:30px;">#</th>';
            $html .= '<th>Гарчиг</th>';
            $html .= '<th style="width:100px;" class="text-center">Огноо</th>';
            $html .= '<th style="width:60px;" class="text-center">Төлөв</th>';
            $html .= '<th style="width:60px;" class="text-center">Контрол</th>';
            $html .= '<th style="width:60px;" class="text-center">ID</th>';
            $html .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody data-link="row" class="rowlink">';

            $i = 1;
            foreach ($query->result() as $key => $row) {

                $html .= '<tr data-id="' . $row->id . '" data-uid="' . $row->created_user_id . '">';
                $html .= '<td class="text-left">' . $i . '</td>';
                $html .= '<td class="context-menu-hr-people-department-selected-row">' . $row->title . '</td>';
                $html .= '<td class="context-menu-hr-people-department-selected-row text-center">' . $row->created_date . '</td>';
                $html .= '<td class="context-menu-hr-people-department-selected-row text-center">' . $row->is_active . '</td>';
                $html .= '<td class="context-menu-hr-people-department-selected-row text-center">' . $row->is_active_control . '</td>';
                $html .= '<td class="context-menu-hr-people-department-selected-row text-center">' . $row->id . '</td>';
                $html .= '<td class="text-center">';
                $html .= '<div class="list-icons">';

                if (($auth->our->update and $row->created_user_id == $this->session->adminUserId) or ( $auth->your->update and $row->created_user_id != $this->session->adminUserId)) {
                    $html .= '<div onclick="_editFormHrPeopleDepartment({elem: this, id: ' . $row->id . '});" class="list-icons-item"><i class="icon-pencil7"></i></div>';
                } else {
                    $html .= '<div class="list-icons-item disabled"><i class="icon-pencil7"></i></div>';
                }

                if (($auth->our->delete and $row->created_user_id == $this->session->adminUserId) or ( $auth->your->delete and $row->created_user_id != $this->session->adminUserId)) {
                    $html .= '<div onclick="_deleteHrPeopleDepartment({elem: this, id: ' . $row->id . '});" class="list-icons-item"><i class="icon-trash"></i></div>';
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

    public function listsChild_model($param = array('parentId' => 0, 'space' => 10, 'autoNumber' => 1)) {

        $html = '';
        $auth = $param['auth'];

        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.mod_id,
                HPD.cat_id,
                HPD.parent_id,
                HPD.title,
                HPD.short_title,
                IF(HPD.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                IF(HPD.is_active_control > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active_control,
                DATE(HPD.created_date) AS created_date,
                HPD.modified_date,
                HPD.created_user_id,
                HPD.modified_user_id,
                HPD.order_num
            FROM `gaz_hr_people_department` AS HPD
            WHERE HPD.parent_id = ' . $param['parentId'] . '
            ORDER BY HPD.order_num DESC');
        if ($query->num_rows() > 0) {

            $j = 1;
            foreach ($query->result() as $row) {
                $html .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '">';
                $html .= '<td>' . $param['autoNumber'] . '.' . $j . '</td>';
                $html .= '<td class="context-menu-hr-people-department-selected-row" style="padding-left:' . $param['space'] . 'px;">' . $row->title . '</td>';
                $html .= '<td class="context-menu-hr-people-department-selected-row text-center">' . $row->created_date . '</td>';
                $html .= '<td class="context-menu-hr-people-department-selected-row text-center">' . $row->is_active . '</td>';
                $html .= '<td class="context-menu-hr-people-department-selected-row text-center">' . $row->is_active_control . '</td>';
                $html .= '<td class="context-menu-hr-people-department-selected-row text-center">' . $row->id . '</td>';
                $html .= '<td class="text-center">';
                $html .= '<div class="list-icons">';

                if (($auth->our->update and $row->created_user_id == $this->session->adminUserId) or ( $auth->your->update and $row->created_user_id != $this->session->adminUserId)) {
                    $html .= '<div onclick="_editFormHrPeopleDepartment({elem: this, id: ' . $row->id . '});" class="list-icons-item"><i class="icon-pencil7"></i></div>';
                } else {
                    $html .= '<div class="list-icons-item disabled"><i class="icon-pencil7"></i></div>';
                }

                if (($auth->our->delete and $row->created_user_id == $this->session->adminUserId) or ( $auth->your->delete and $row->created_user_id != $this->session->adminUserId)) {
                    $html .= '<div onclick="_deleteHrPeopleDepartment({elem: this, id: ' . $row->id . '});" class="list-icons-item"><i class="icon-trash"></i></div>';
                } else {
                    $html .= '<div class="list-icons-item disabled"><i class="icon-trash"></i></div>';
                }

                $html .= '</ul>';
                $html .= '</td>';
                $html .= '</tr>';
                $j++;
                $html .= self::listsChild_model(array('parentId' => $row->id, 'space' => (intval($param['space']) + 30), 'autoNumber' => $j, 'auth' => $param['auth']));
            }
        }
        return $html;
    }

    public function insert_model($param = array('pic' => '', 'getUID' => 0)) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        $this->data = array(
            array(
                'id' => getUID('hr_people_department'),
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'parent_id' => $this->input->post('parentId'),
                'title' => $this->input->post('title'),
                'short_title' => $this->input->post('shortTitle'),
                'address' => $this->input->post('address'),
                'phone' => $this->input->post('phone'),
                'lat' => $this->input->post('lat'),
                'lng' => $this->input->post('lng'),
                'is_active' => $this->input->post('isActive'),
                'is_active_control' => $this->input->post('isActiveControl'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => $this->session->adminUserId,
                'order_num' => $this->input->post('orderNum')
            )
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'hr_people_department', $this->data)) {
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
            'title' => $this->input->post('title'),
            'short_title' => $this->input->post('shortTitle'),
            'address' => $this->input->post('address'),
            'phone' => $this->input->post('phone'),
            'lat' => $this->input->post('lat'),
            'lng' => $this->input->post('lng'),
            'is_active' => $this->input->post('isActive'),
            'is_active_control' => $this->input->post('isActiveControl'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'order_num' => $this->input->post('orderNum')
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'hr_people_department', $this->data)) {
            return array('status' => 'success', 'message' => 'Өөрчлөлтийг амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Өөрчлөлтийн түүхийг хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {

        $row = $this->getData_model(array('selectedId' => $this->input->post('id')));
        $this->slog->log_model(array(
            'modId' => $row->mod_id,
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_DELETE,
            'data' => json_encode($row)));

        $this->db->where_in('id', explode(',', $this->getChildHrPeopleDepartment_model($this->input->post('id'))));
        if ($this->db->delete($this->db->dbprefix . 'hr_people_department')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function searchKeywordView_model() {
        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            //' . Scrime::$path . $param['path'] . '/' . $param['modId'] . '
            $this->string .= ' <a href="javascript:;" onclick="_initHrPeopleDepartment({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return $this->string;
    }

    public function controlHrPeopleDepartmentDropdown_model($param = array('modId' => 0, 'selectedId' => 0)) {

        $queryString = $html = $string = $class = $name = '';

        if (isset($param['name'])) {
            $name = $param['name'];
        } else {
            $name = 'departmentId';
        }

        if (!isset($param['isMultiple'])) {
            $param['isMultiple'] = 'false';
            $param['data'] = false;
        }

        if (isset($param['modId']) and $param['modId'] != '') {
            $queryString .= ' AND HPD.mod_id = ' . $param['modId'];
        }

        if ($this->session->adminAccessTypeId == 2 and !isset($param['onlyMyDepartment'])) {

            if ($param['selectedId'] != 0) {

                $queryString .= ' AND HPD.id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['selectedId']) . ')';
                
            } else {
                $queryString .= ' AND HPD.id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ')';
            }
            
        } else {
            $queryString .= ' AND HPD.parent_id = 0';
        }

        if (isset($param['isMultiple']) and $param['isMultiple'] == 'true') {
            $string .= ' multiple="multiple"';
            $param['selectedId'] = $this->getDepartmentId_model(array('data' => $param['selectedId']));
        }

        if (isset($param['disabled']) and $param['disabled'] == 'true') {
            $string .= ' disabled="true"';
        }

        if (isset($param['required']) and $param['required'] == 'true') {
            $string .= ' required="true"';
            $class .= ' required';
        }

        if (isset($param['readonly']) and $param['readonly'] == 'true') {
            $string .= ' disabled="true"';
        }
        
        if (isset($param['onclick'])) {
            $string .= ' onclick="' . $param['onclick'] . '"';
        }
        
        if (isset($param['onchange'])) {
            $string .= ' onchange="' . $param['onchange'] . '"';
        }

        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                HPD.short_title
            FROM `gaz_hr_people_department` AS HPD
            WHERE HPD.is_active = 1 AND HPD.is_active_control = 1 ' . $queryString . '
            GROUP BY HPD.id
            ORDER BY HPD.order_num ASC');

        $html .= '<select name="' . $name . '" id="' . $name . '" class="select2" ' . $string . '>';
        if ($param['isMultiple'] == 'false') {
            $html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';
        }

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {
                if ($param['isMultiple'] == 'true') {
                    $html .= '<option value="' . $row->id . '" ' . ($this->getDepartmentId_model(array('data' => $param['data'], 'selectedId' => $row->id)) ? 'selected="selected"' : '') . '>' . $row->short_title . '</option>';
                } else {
                    $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->short_title . '</option>';
                }

                $html .= self::controlHrPeopleDepartmentChildDropdown_model(array(
                            'isMultiple' => $param['isMultiple'],
                            'data' => $param['data'],
                            'selectedId' => $param['selectedId'],
                            'parentId' => $row->id,
                            'space' => '&nbsp; &nbsp;  &nbsp;  &nbsp; '));
            }
        }

        $html .= '</select>';

        return $html;
    }

    public function getDepartmentId_model($param = array()) {
        if (is_array($param['data'])) {
            foreach ($param['data'] as $key => $row) {
                if ($row == $param['selectedId']) {
                    return true;
                }
            }
        }

        return false;
    }

    public function controlHrPeopleDepartmentChildDropdown_model($param = array('parentId' => 0, 'selectedId' => 0, 'space' => '', 'modId' => 0)) {

        $html = $string = $class = $queryStringChild = '';

        if ($this->session->adminDepartmentRoleId == 2) {

            $queryStringChild .= ' AND HPD.id IN (' . self::getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ')';
        }

        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                HPD.short_title
            FROM `gaz_hr_people_department` AS HPD
            WHERE HPD.parent_id = ' . $param['parentId'] . ' AND HPD.is_active = 1 AND HPD.is_active_control = 1 ' . $queryStringChild . '
            GROUP BY HPD.id
            ORDER BY HPD.order_num DESC');

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {
                if ($param['isMultiple'] == 'true') {
                    $html .= '<option value="' . $row->id . '" ' . ($this->getDepartmentId_model(array('data' => $param['data'], 'selectedId' => $row->id)) ? 'selected="selected"' : '') . '>' . $param['space'] . $row->short_title . '</option>';
                } else {
                    $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $param['space'] . $row->short_title . '</option>';
                }
                $html .= self::controlHrPeopleDepartmentChildDropdown_model(array(
                            'isMultiple' => $param['isMultiple'],
                            'data' => $param['data'],
                            'selectedId' => $param['selectedId'],
                            'parentId' => $row->id,
                            'space' => $param['space'] . ' &nbsp; &nbsp; &nbsp;  &nbsp; '));
            }
        }

        return $html;
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.mod_id,
                HPD.cat_id,
                HPD.parent_id,
                HPD.title,
                HPD.short_title,
                HPD.is_active,
                HPD.is_active_control,
                HPD.created_date,
                HPD.modified_date,
                HPD.created_user_id,
                HPD.modified_user_id,
                HPD.order_num
            FROM `gaz_hr_people_department` AS HPD
            WHERE HPD.id = ' . $param['selectedId']);


        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

    public function controlHrPeopleDepartmentParentMultiRowDropdown_model($param = array('modId' => 0, 'selectedId' => 0, 'id' => 0, 'parentId' => 0, 'space' => '')) {

        $html = '';
        $this->query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                HPD.short_title
            FROM `gaz_hr_people_department` AS HPD
            WHERE HPD.parent_id = 0 AND HPD.is_active = 1 AND HPD.mod_id = ' . $param['modId'] . ' AND HPD.id != ' . $param['id'] . '
            ORDER BY HPD.order_num DESC');

        $html .= '<select class="form-control" name="parentId" id="parentId" size="10" required="required">';

        $html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '>' . ' - Сонгох - </option>';

        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $row) {

                $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $param['space'] . $row->title . '</option>';

                $html .= self::controlHrPeopleDepartmentParentMultiChildRowDropdown_model(array('selectedId' => $param['selectedId'], 'id' => $param['id'], 'parentId' => $row->id, 'space' => '&nbsp; &nbsp;  &nbsp;  &nbsp; ' . $param['space']));
            }
        }
        $html .= '</select>';

        return $html;
    }

    public function controlHrPeopleDepartmentParentMultiChildRowDropdown_model($param = array('selectedId' => 0, 'id' => 0, 'parentId' => 0, 'space' => '')) {

        $html = '';
        $this->query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                HPD.short_title
            FROM `gaz_hr_people_department` AS HPD
            WHERE HPD.parent_id = ' . $param['parentId'] . ' AND HPD.is_active = 1 AND HPD.id != ' . $param['id'] . '
            ORDER BY HPD.order_num DESC');

        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $row) {

                $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $param['space'] . $row->title . '</option>';

                $html .= self::controlHrPeopleDepartmentParentMultiChildRowDropdown_model(array('selectedId' => $param['selectedId'], 'id' => $param['id'], 'parentId' => $row->id, 'space' => '&nbsp; &nbsp;  &nbsp;  &nbsp; ' . $param['space']));
            }
        }

        return $html;
    }

    public function getParentHrPeopleDepartment_model($param = array()) {

        $data = '';

        if ($param != '') {


            $param = explode(',', $param);

            if (is_array($param) and count($param) > 0) {

                foreach ($param as $key => $id) {

                    $this->query = $this->db->query('
                        SELECT 
                            HPD.id,
                            HPD.parent_id
                        FROM `' . $this->db->dbprefix . 'hr_people_department` AS HPD 
                        WHERE HPD.is_active = 1 AND HPD.id = ' . $id);

                    if ($this->query->num_rows() > 0) {

                        foreach ($this->query->result() as $key => $row) {

                            if ($row->parent_id != 0) {
                                $data .= $row->parent_id . ',';

                                $data .= $this->_parentHrPeopleDepartment_model($row->id);
                            }
                        }

                        $data .= $id . ',';
                    } else {

                        $data .= $id . ',';
                    }
                }
            }

            return rtrim($data, ',');
        }

        return 0;
    }

    public function _parentHrPeopleDepartment_model($param = array()) {

        $data = '';

        if ($param != '') {


            $param = explode(',', $param);

            if (is_array($param) and count($param) > 0) {



                foreach ($param as $key => $id) {

                    $this->query = $this->db->query('
                        SELECT 
                            HPD.id,
                            HPD.parent_id
                        FROM `' . $this->db->dbprefix . 'hr_people_department` AS HPD 
                        WHERE HPD.is_active = 1 AND HPD.id = ' . $id);

                    if ($this->query->num_rows() > 0) {

                        foreach ($this->query->result() as $key => $row) {

                            if ($row->parent_id != 0) {

                                $data .= $row->parent_id . ',';

                                $data .= $this->_parentHrPeopleDepartment_model($row->parent_id);
                            }
                        }

                        $data .= $id . ',';
                    } else {

                        $data .= $id . ',';
                    }
                }
            }

            return $data;
        }

        return 0;
    }

    public function getChildHrPeopleDepartment_model($param = array()) {

        $data = '';
        $arrayData = array();
        if ($param != '') {


            $param = explode(',', $param);

            if (is_array($param) and count($param) > 0) {

                foreach ($param as $partnerKey => $partnerId) {

                    $this->query = $this->db->query('
                        SELECT 
                            HPD.id
                        FROM `' . $this->db->dbprefix . 'hr_people_department` AS HPD 
                        WHERE HPD.is_active = 1 AND HPD.parent_id = ' . $partnerId);

                    if ($this->query->num_rows() > 0) {

                        foreach ($this->query->result() as $key => $row) {

                            $data .= $row->id . ',';

                            $data .= $this->_childHrPeopleDepartment_model($row->id);
                        }

                        $data .= $partnerId . ',';
                    } else {

                        $data .= $partnerId . ',';
                    }
                }
            }
            $arrayData = rtrim($data, ',');
            $param = explode(',', $arrayData);
            $arrayData = array_unique($param);
            $param = rtrim(implode(',', $arrayData), ',');
            
            return $param;

        }

        return 0;
    }

    public function _childHrPeopleDepartment_model($param = array()) {

        $data = '';

        if ($param != '') {


            $param = explode(',', $param);

            if (is_array($param) and count($param) > 0) {



                foreach ($param as $partnerKey => $partnerId) {

                    $this->query = $this->db->query('
                        SELECT 
                            HPD.id
                        FROM `' . $this->db->dbprefix . 'hr_people_department` AS HPD 
                        WHERE HPD.is_active = 1 AND HPD.parent_id = ' . $partnerId);

                    if ($this->query->num_rows() > 0) {

                        foreach ($this->query->result() as $key => $row) {

                            $data .= $row->id . ',';

                            $data .= $this->_childHrPeopleDepartment_model($row->id);
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
    
    public function import_model($param = array('pic' => '', 'getUID' => 0)) {


        include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';

        $inputFileName = './Book1.xlsx';

//  Read your Excel workbook
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }

//  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

//  Loop through each row of the worksheet in turn
        for ($row = 1; $row <= $highestRow; $row++) {

            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
            //  Insert row data array into your database of choice here

            $this->data = array(
                array(
                    'id' => getUID('partner'),
                    'mod_id' => 24,
                    'cat_id' => 313,
                    'parent_id' => 0,
                    'pic_mn' => '',
                    'pic_en' => '',
                    'cover_mn' => '',
                    'cover_en' => '',
                    'title_mn' => $rowData['0']['0'],
                    'title_en' => $rowData['0']['0'],
                    'link_title_mn' => $rowData['0']['1'],
                    'link_title_en' => $rowData['0']['1'],
                    'phone' => '',
                    'email' => '',
                    'manager_name' => $rowData['0']['0'],
                    'manager_phone' => '',
                    'is_active_mn' => 1,
                    'is_active_en' => 1,
                    'color' => '',
                    'order_num' => getOrderNum(array('table' => 'partner', 'field' => 'order_num')),
                    'description_mn' => '',
                    'description_en' => '',
                    'created_date' => date('Y-m-d H:i:s'),
                    'modified_date' => date('Y-m-d H:i:s'),
                    'created_user_id' => $this->session->adminUserId,
                    'modified_user_id' => 0,
                    'social' => '',
                    'city_id' => 0,
                    'soum_id' => 0,
                    'street_id' => 0,
                    'address_mn' => $rowData['0']['0'],
                    'address_en' => $rowData['0']['0']
                )
            );

            $this->db->insert_batch($this->db->dbprefix . 'partner', $this->data);
        }
    }

}
