<?php

class ShrPeopleEducationRankMasterData_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Suser_model', 'user');
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => 63,
            'title_mn' => '',
            'title_en' => '',
            'is_active_mn' => 1,
            'is_active_en' => 1,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => 0,
            'modified_user_id' => 0,
            'order_num' => getOrderNum(array('table' => 'hr_people_accept_module', 'field' => 'order_num'))
        )));
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                HPAM.id,
                HPAM.module_id,
                HPAM.mod_id,
                HPAM.title_mn,
                HPAM.title_en,
                HPAM.is_active_mn,
                HPAM.is_active_en,
                HPAM.created_date,
                HPAM.modified_date,
                HPAM.created_user_id,
                HPAM.modified_user_id,
                HPAM.order_num
            FROM `gaz_hr_people_accept_module` AS HPAM
            WHERE HPAM.id = ' . $param['id']);

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
            $this->queryString .= ' AND HPAM.created_user_id = ' . $this->session->adminUserId;
        } else {
            $this->queryString .= ' AND HPAM.created_user_id = -1';
        }

//        if (isset($this->session->adminPartnerId)) {
//            $this->queryString .= ' AND K.user_partner_id IN (' . $this->partner->getChildPartners_model(array($this->session->adminPartnerId)) . ')';
//        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(HPAM.title_mn) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(HPAM.title_en) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        $this->query = $this->db->query('
            SELECT 
                HPAM.id
            FROM `gaz_hr_people_accept_module` AS HPAM 
            WHERE 1 = 1 ' . $this->queryString);

        return $this->query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $this->queryString = $this->getString = '';

        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'moduleMenuId' => $param['moduleMenuId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 1) {
            $this->queryString .= '';
        } elseif ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND HPAM.created_user_id = ' . $this->session->adminUserId;
        } else {
            $this->queryString .= ' AND HPAM.created_user_id = -1';
        }
//        if (isset($this->session->adminPartnerId)) {
//            $this->queryString .= ' AND K.user_partner_id IN (' . $this->partner->getChildPartners_model(array($this->session->adminPartnerId)) . ')';
//        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(HPAM.title_mn) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(HPAM.title_en) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        $this->query = $this->db->query('
            SELECT 
                HPAM.id,
                HPAM.module_id,
                HPAM.mod_id,
                HPAM.title_mn AS title,
                HPAM.is_active_mn AS is_active,
                HPAM.created_date,
                HPAM.modified_date,
                HPAM.created_user_id,
                HPAM.modified_user_id,
                HPAM.order_num
            FROM `gaz_hr_people_accept_module` AS HPAM
            WHERE 1 = 1 ' . $this->queryString . '
            ORDER BY HPAM.order_num DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        $this->html = form_open('', array('class' => 'form-horizontal', 'id' => 'form-hr-people-position-init', 'enctype' => 'multipart/form-data'));
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
            $this->html .= form_button('addHrPeopleAcceptModule', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Шинэ (F2)</span>', 'class="btn btn-default legitRipple" onclick="_addFormHrPeopleAcceptModule({elem: this});"', 'button');
        } else {
            $this->html .= form_button('addHrPeopleAcceptModule', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Шинэ (F2)</span>', 'class="btn btn-default legitRipple" disabled="true"', 'button');
        }

        $this->html .= form_button('searchHrPeopleAcceptModule', '<i class="icon-search4"></i> <span class="hidden-xs position-right">Хайх (F3)</span>', 'class="btn btn-default legitRipple" onclick="_advensedSearchHrPeopleAcceptModule({elem: this});"', 'button');


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
                $this->html .= '<td class="context-menu-hr-people-accept-module-selected-row" style="padding-left:' . $param['space'] . 'px;">' . $row->title . '</td>';
                $this->html .= '<td class="context-menu-hr-people-accept-module-selected-row text-center">' . ($row->is_active == 1 ? '<span class="label label-success"><i class="fa fa-check"></i></span>' : '<span class="label label-danger"><i class="fa fa-lock"></i></span>') . '</td>';
                $this->html .= '<td class="text-center">';
                $this->html .= '<ul class="icons-list">';

                if (($this->auth->our->update == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->update == 1 and $row->created_user_id != $this->session->adminUserId)) {
                    $this->html .= '<li><a href="javascript:;" onclick="_editFormHrPeopleAcceptModule({elem: this, id:' . $row->id . '});" title="Засах" data-action="edit"></a></li>';
                } else {
                    $this->html .= '<li><a href="javascript:;" class="disabled" title="Засах" data-action="edit"></a></li>';
                }

                if (($this->auth->our->update == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->update == 1 and $row->created_user_id != $this->session->adminUserId)) {
                    $this->html .= '<li><a href="javascript:;" onclick="_removeHrPeopleAcceptModule({elem: this, id:' . $row->id . '});" title="Устгах" data-action="delete"></a></li>';
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

    public function insert_model() {
        $this->data = array(
            array(
                'id' => getUID('hr_people_accept_module'),
                'module_id' => $this->input->post('modId'),
                'mod_id' => 63,
                'title_mn' => $this->input->post('titleMn'),
                'title_en' => $this->input->post('titleEn'),
                'is_active_mn' => $this->input->post('isActiveMn'),
                'is_active_en' => $this->input->post('isActiveEn'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => '0000-00-00 00:00:00',
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'order_num' => $this->input->post('orderNum'))
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'hr_people_accept_module', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model() {

        $this->data = array(
            'module_id' => $this->input->post('modId'),
            'title_mn' => $this->input->post('titleMn'),
            'title_en' => $this->input->post('titleEn'),
            'is_active_mn' => $this->input->post('isActiveMn'),
            'is_active_en' => $this->input->post('isActiveEn'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'order_num' => $this->input->post('orderNum'));
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'hr_people_accept_module', $this->data)) {
            return array('status' => 'success', 'message' => 'Өөрчлөлтийг амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Өөрчлөлтийн түүхийг хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {
        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'hr_people_accept_module')) {
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
            $this->string .= ' <a href="javascript:;" onclick="_initHrPeopleRank({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return $this->string;
    }

    public function controlHrPeopleEducationRankMasterOutDataDropDown_model($param = array('data' => array(), 'parentId' => 0, 'selectedId' => 0, 'name' => '')) {
        $this->html = $this->string = '';

        if (isset($param['multiple']) and strtolower($param['multiple']) == 'true') {
            $this->string .= ' multiple="multiple"';
        }

        if (isset($param['disabled']) and strtolower($param['disabled']) == 'true') {
            $this->string .= ' disabled="true"';
        }

        if (isset($param['required']) and strtolower($param['required']) == 'true') {
            $this->string .= ' required="true"';
        }

        if (isset($param['readonly']) and strtolower($param['readonly']) == 'true') {
            $this->string .= ' disabled="true"';
            $this->html .= form_hidden($param['name'], $param['selectedId']);
        }

        $this->html .= '<select name="' . $param['name'] . '" id="' . $param['name'] . '" class="select2" ' . $this->string . '>';

        if (!isset($param['multiple']) and isset($param['selectedId']) and strtolower($param['selectedId']) == 0) {
            $this->html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';
        }

        foreach ($param['data'] as $key => $row) {
            $this->html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';
        }

        $this->html .= '</select>';

        return $this->html;
    }

    public function controlHrPeopleEducationRankMasterDataDropDown_model($param = array('parentId' => 0, 'selectedId' => 0, 'name' => '')) {
        $this->html = $this->string = '';

        if (isset($param['multiple']) and strtolower($param['multiple']) == 'true') {
            $this->string .= ' multiple="multiple"';
        }

        if (isset($param['disabled']) and strtolower($param['disabled']) == 'true') {
            $this->string .= ' disabled="true"';
        }

        if (isset($param['required']) and strtolower($param['required']) == 'true') {
            $this->string .= ' required="true"';
        }

        if (isset($param['readonly']) and strtolower($param['readonly']) == 'true') {
            $this->string .= ' disabled="true"';
            $this->html .= form_hidden($param['name'], $param['selectedId']);
        }

        $this->html .= '<select name="' . $param['name'] . '" id="' . $param['name'] . '" class="select2" ' . $this->string . '>';

        if (!isset($param['multiple']) and isset($param['selectedId']) and strtolower($param['selectedId']) == 0) {
            $this->html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';
        }


        $this->query = $this->db->query('
                SELECT 
                    HPERMD.id,
                    HPERMD.title
                FROM `gaz_hr_people_education_rank_master_data` AS HPERMD
                WHERE 
                    HPERMD.is_active = 1
                ORDER BY HPERMD.order_num ASC');

        if ($this->query->num_rows() > 0) {
            foreach ($this->query->result() as $row) {
                $this->html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';
            }
        }

        $this->html .= '</select>';

        return $this->html;
    }

    public function getData_model($param = array('selectedId' => 0)) {
        $this->query = $this->db->query('
            SELECT 
                HPAM.id,
                HPAM.module_id,
                HPAM.mod_id,
                HPAM.title_mn AS title,
                HPAM.is_active_mn AS is_active,
                HPAM.created_date,
                HPAM.modified_date,
                HPAM.created_user_id,
                HPAM.modified_user_id,
                HPAM.order_num
            FROM `gaz_hr_people_accept_module` AS HPAM
            WHERE HPR.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

    public function getListData_model($param = array('selectedId' => 0)) {

        $query = $this->db->query('
            SELECT 
                HPERMD.id,
                HPERMD.title
            FROM `gaz_hr_people_education_rank_master_data` AS HPERMD
            WHERE 
                HPERMD.is_active = 1
            ORDER BY HPERMD.order_num ASC');

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return false;
    }

}
