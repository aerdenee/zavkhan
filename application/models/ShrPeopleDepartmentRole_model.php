<?php

class ShrPeopleDepartmentRole_model extends CI_Model {

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
            'title' => '',
            'order_num' => getOrderNum(array('table' => 'hr_people_department_role', 'field' => 'order_num')),
            'is_active' => 1,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
        )));
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                HPDR.id,
                HPDR.mod_id,
                HPDR.cat_id,
                HPDR.title,
                HPDR.is_active,
                HPDR.created_date,
                HPDR.modified_date,
                HPDR.created_user_id,
                HPDR.modified_user_id,
                HPDR.order_num
            FROM `gaz_hr_people_department_role` AS HPD
            WHERE HPD.id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function listsCount_model($param = array()) {
        $this->queryString = $this->getString = '';

        $this->auth = authentication(array('authentication' => $this->session->authentication, 'moduleMenuId' => $param['moduleMenuId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 1) {
            $this->queryString .= '';
        } elseif ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND HPDR.created_user_id = ' . $this->session->adminUserId;
        } else {
            $this->queryString .= ' AND HPDR.created_user_id = -1';
        }

//        if (isset($this->session->adminPartnerId)) {
//            $this->queryString .= ' AND K.user_partner_id IN (' . $this->partner->getChildHrPeopleDepartment_model(array($this->session->adminPartnerId)) . ')';
//        }
//        if ($param['catId'] != 0) {
//            $this->queryString .= ' AND P.cat_id = ' . $param['catId'];
//        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(HPDR.title) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        $this->query = $this->db->query('
            SELECT 
                HPDR.id
            FROM `gaz_hr_people_department_role` AS HPDR 
            WHERE HPDR.parent_id = 0 ' . $this->queryString);

        return $this->query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $this->queryString = $this->getString = '';

        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'moduleMenuId' => $param['moduleMenuId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 1) {
            $this->queryString .= '';
        } elseif ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND HPDR.created_user_id = ' . $this->session->adminUserId;
        } else {
            $this->queryString .= ' AND HPDR.created_user_id = -1';
        }
//        if (isset($this->session->adminPartnerId)) {
//            $this->queryString .= ' AND K.user_partner_id IN (' . $this->partner->getChildHrPeopleDepartment_model(array($this->session->adminPartnerId)) . ')';
//        }
//        if ($param['catId'] != 0) {
//            $this->queryString .= ' AND P.cat_id = ' . $param['catId'];
//            $this->getString .= form_hidden('catId', $param['catId']);
//        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(HPDR.title) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        $this->query = $this->db->query('
            SELECT 
                HPDR.id,
                HPDR.mod_id,
                HPDR.cat_id,
                HPDR.title,
                HPDR.is_active,
                HPDR.created_date,
                HPDR.modified_date,
                HPDR.created_user_id,
                HPDR.modified_user_id,
                HPDR.order_num
            FROM `gaz_hr_people_department_role` AS HPDR
            WHERE 1 = 1 ' . $this->queryString . '
            ORDER BY HPDR.order_num DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        $this->html = form_open('', array('class' => 'form-horizontal', 'id' => 'form-hr-people-department-role-init', 'enctype' => 'multipart/form-data'));
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

        if ($this->auth->our->create == 1) {
            $this->html .= form_button('addHrPeopleDepartmentRole', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Шинэ (F2)</span>', 'class="btn btn-default legitRipple" onclick="_addFormHrPeopleDepartmentRole({elem: this});"', 'button');
        } else {
            $this->html .= form_button('addHrPeopleDepartmentRole', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Шинэ (F2)</span>', 'class="btn btn-default legitRipple" disabled="true"', 'button');
        }

        $this->html .= form_button('searchHrPeopleDepartmentRole', '<i class="icon-search4"></i> <span class="hidden-xs position-right">Хайх (F3)</span>', 'class="btn btn-default legitRipple" onclick="_advensedSearchHrPeopleDepartmentRole({elem: this});"', 'button');


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
            $this->html .= '<th>Гарчиг</th>';
            $this->html .= '<th style="width:60px;">Төлөв</th>';
            $this->html .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->html .= '</tr>';
            $this->html .= '</thead>';
            $this->html .= '<tbody data-link="row" class="rowlink">';

            $i = 0;

            foreach ($this->query->result() as $key => $row) {

                $this->html .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '">';
                $this->html .= '<td>' . ++$i . '</td>';
                $this->html .= '<td class="context-menu-hr-people-department-selected-row" style="padding-left:' . $param['space'] . 'px;">' . $row->title . '</td>';
                $this->html .= '<td class="context-menu-hr-people-department-selected-row text-center">' . ($row->is_active == 1 ? '<span class="label bg-teal dropdown-toggle" title="Идэвхтэй"><span class="fa fa-check"></span></span>' : '<span class="label label-danger" title="Идэвхгүй"><span class="fa fa-lock"></span></span>') . '</td>';
                $this->html .= '<td class="text-center">';
                $this->html .= '<ul class="icons-list">';

                if (($this->auth->our->update == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->update == 1 and $row->created_user_id != $this->session->adminUserId)) {
                    $this->html .= '<li><a href="javascript:;" onclick="_editFormHrPeopleDepartmentRole({elem: this, id:' . $row->id . '});" title="Засах" data-action="edit"></a></li>';
                } else {
                    $this->html .= '<li><a href="javascript:;" class="disabled" title="Засах" data-action="edit"></a></li>';
                }

                if (($this->auth->our->delete == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->delete == 1 and $row->created_user_id != $this->session->adminUserId)) {
                    $this->html .= '<li><a href="javascript:;" onclick="_removeHrPeopleDepartmentRole({elem: this, id:' . $row->id . '});" title="Устгах" data-action="delete"></a></li>';
                } else {
                    $this->html .= '<li><a href="javascript:;" class="disabled" title="Устгах" data-action="delete"></a></li>';
                }

                $this->html .= '</ul>';
                $this->html .= '</td>';
                $this->html .= '</tr>';
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

    public function insert_model($param = array('pic' => '', 'getUID' => 0)) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        $this->data = array(
            array(
                'id' => getUID('hr_people_department_role'),
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'title' => $this->input->post('title'),
                'is_active' => $this->input->post('isActive'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => '0000-00-00 00:00:00',
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'order_num' => $this->input->post('orderNum')
            )
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'hr_people_department_role', $this->data)) {
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
            'title' => $this->input->post('title'),
            'is_active' => $this->input->post('isActive'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'order_num' => $this->input->post('orderNum')
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'hr_people_department_role', $this->data)) {
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
        if ($this->db->delete($this->db->dbprefix . 'hr_people_department_role')) {
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
            $this->string .= ' <a href="javascript:;" onclick="_initHrPeopleDepartmentRole({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return $this->string;
    }

    public function controlHrPeopleDepartmentRoleDropdown_model($param = array('modId' => 0, 'selectedId' => 0)) {

        $queryString = $html = $string = $class = $name = '';

        if (isset($param['name'])) {
            $name = $param['name'];
        } else {
            $name = 'departmentRoleId';
        }

        if (isset($param['modId']) and $param['modId'] != '') {
            $queryString .= ' AND HPDR.mod_id = ' . $param['modId'];
        }

        if ($this->session->adminAccessTypeId == 4 OR $this->session->adminAccessTypeId == 3 OR $this->session->adminAccessTypeId == 2) {
            $queryString .= ' AND HPDR.id IN (' . $this->session->adminAllDepartmentId . ')';
        }

        $getDepartmentData = $this->getData_model(array('selectedId' => $this->session->adminDepartmentId));

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

        $query = $this->db->query('
            SELECT 
                HPDR.id,
                HPDR.title
            FROM `gaz_hr_people_department_role` AS HPDR
            WHERE HPDR.is_active = 1 ' . $queryString . '
            ORDER BY HPDR.order_num ASC');

        $html .= '<select name="' . $name . '" id="' . $name . '" class="select2" ' . $string . '>';
        //$html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';
        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {
                $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';
            }
        }

        $html .= '</select>';

        return $html;
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                HPDR.id,
                HPDR.mod_id,
                HPDR.cat_id,
                HPDR.title,
                HPDR.is_active,
                HPDR.created_date,
                HPDR.modified_date,
                HPDR.created_user_id,
                HPDR.modified_user_id,
                HPDR.order_num
            FROM `gaz_hr_people_department_role` AS HPDR
            WHERE HPDR.id = ' . $param['selectedId']);


        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

}
