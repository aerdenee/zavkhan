<?php

class SnifsMasterCase_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();

        $this->load->model('Scategory_model', 'category');
        $this->load->model('Suser_model', 'user');
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => 90,
            'title' => '',
            'order_num' => getOrderNum(array('table' => 'nifs_crime_case', 'field' => 'order_num')),
            'is_active' => 1,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0
        )));
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                NCC.id,
                NCC.mod_id,
                NCC.title,
                NCC.order_num,
                NCC.is_active,
                NCC.created_date,
                NCC.modified_date,
                NCC.created_user_id,
                NCC.modified_user_id
            FROM `gaz_nifs_crime_case` AS NCC
            WHERE NCC.id = ' . $param['id']);

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
            $queryString .= ' AND NCC.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NCC.created_user_id = -1';
        }
        if ($param['catId'] != 0) {
            $queryString .= ' AND NCC.cat_id = ' . $param['catId'];
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND (LOWER(NCC.title) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        $query = $this->db->query('
            SELECT 
                NCT.id
            FROM `gaz_nifs_crime_type` AS NCT
            WHERE NCT.parent_id = 0 ' . $queryString);

        return $this->query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $this->queryString = $this->getString = '';

        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'moduleMenuId' => $param['moduleMenuId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 1) {
            $this->queryString .= '';
        } elseif ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND NCT.created_user_id = ' . $this->session->adminUserId;
        } else {
            $this->queryString .= ' AND NCT.created_user_id = -1';
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND NCT.cat_id = ' . $param['catId'];
            $this->getString .= form_hidden('catId', $param['catId']);
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(NCT.title) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        $this->query = $this->db->query('
            SELECT 
                NCT.id,
                NCT.mod_id,
                NCT.cat_id,
                C.title AS cat_title,
                NCT.parent_id,
                NCT.title,
                NCT.order_num,
                NCT.is_active,
                NCT.created_date,
                NCT.modified_date,
                NCT.created_user_id,
                NCT.modified_user_id
            FROM `gaz_nifs_crime_type` AS NCT
            LEFT JOIN `gaz_category` AS C ON NCT.cat_id = C.id
            WHERE NCT.parent_id = 0 ' . $this->queryString . '
            ORDER BY NCT.order_num DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        $this->html = form_open('', array('class' => 'form-horizontal', 'id' => 'form-partner-init', 'enctype' => 'multipart/form-data'));
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
            $this->html .= form_button('addNifsCrimeType', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Шинэ (F2)</span>', 'class="btn btn-default legitRipple" onclick="_addFormNifsCrimeType({elem: this});"', 'button');
        } else {
            $this->html .= form_button('addNifsCrimeType', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Шинэ (F2)</span>', 'class="btn btn-default legitRipple" disabled="true"', 'button');
        }

        $this->html .= form_button('searchNifsCrimeType', '<i class="icon-search4"></i> <span class="hidden-xs position-right">Хайх (F3)</span>', 'class="btn btn-default legitRipple" onclick="_advensedSearchNifsCrimeType({elem: this});"', 'button');


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
            $this->html .= '<th style="width:200px;">Ангилал</th>';
            $this->html .= '<th style="width:100px;">Төлөв</th>';
            $this->html .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->html .= '</tr>';
            $this->html .= '</thead>';
            $this->html .= '<tbody data-link="row" class="rowlink">';

            $i = 0;

            foreach ($this->query->result() as $key => $row) {
                $this->html .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '">';
                $this->html .= '<td>' . ++$i . '</td>';
                $this->html .= '<td class="context-menu-nifs-crime-type-selected-row" style="padding-left:' . $param['space'] . 'px;">' . $row->title . '</td>';
                $this->html .= '<td class="context-menu-nifs-crime-type-selected-row">' . $row->cat_title . '</td>';
                $this->html .= '<td class="context-menu-nifs-crime-type-selected-row">' . ($row->is_active == 1 ? '<span class="label bg-teal dropdown-toggle"><span class="fa fa-check"></span></span>' : '<span class="label label-danger"><span class="fa fa-lock"></span></span>') . '</td>';
                $this->html .= '<td class="text-center">';
                $this->html .= '<ul class="icons-list">';

                if (($this->auth->our->update == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->update == 1 and $row->created_user_id != $this->session->adminUserId)) {
                    $this->html .= '<li><a href="javascript:;" onclick="_editFormNifsCrimeType({elem: this, id:' . $row->id . '});" title="Засах" data-action="edit"></a></li>';
                } else {
                    $this->html .= '<li><a href="javascript:;" class="disabled" title="Засах" data-action="edit"></a></li>';
                }

                if (($this->auth->our->update == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->update == 1 and $row->created_user_id != $this->session->adminUserId)) {
                    $this->html .= '<li><a href="javascript:;" onclick="_removeNifsCrimeType({elem: this, id:' . $row->id . '});" title="Устгах" data-action="delete"></a></li>';
                } else {
                    $this->html .= '<li><a href="javascript:;" class="disabled" title="Устгах" data-action="delete"></a></li>';
                }

                $this->html .= '</ul>';
                $this->html .= '</td>';
                $this->html .= '</tr>';

                $this->html .= self::listsChild_model(array('parentId' => $row->id, 'space' => 50, 'autoNumber' => $i, 'moduleMenuId' => $param['moduleMenuId']));
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

    public function listsChild_model($param = array('parentId' => 0, 'space' => 10, 'autoNumber' => 1)) {

        $html = '';
        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'moduleMenuId' => $param['moduleMenuId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 1) {
            $this->queryString .= '';
        } else if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND C.created_user_id = ' . $this->session->adminUserId;
        } else if ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND C.created_user_id = -1';
        }

        $this->query = $this->db->query('
            SELECT 
                NCT.id,
                NCT.mod_id,
                NCT.cat_id,
                NCT.parent_id,
                NCT.title,
                NCT.order_num,
                NCT.is_active,
                NCT.created_date,
                NCT.modified_date,
                NCT.created_user_id,
                NCT.modified_user_id
            FROM `gaz_nifs_crime_type` AS NCT
            WHERE NCT.parent_id = ' . $param['parentId'] . '
            ORDER BY NCT.order_num DESC');
        if ($this->query->num_rows() > 0) {

            $j = 1;
            foreach ($this->query->result() as $row) {
                $html .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '">';
                $html .= '<td>' . $param['autoNumber'] . '.' . $j . '</td>';
                $html .= '<td class="context-menu-nifs-crime-type-selected-row" style="padding-left:' . $param['space'] . 'px;">' . $row->title . '</td>';
                $this->html .= '<td class="context-menu-nifs-crime-type-selected-row">' . ($row->is_active == 1 ? '<span class="label bg-teal dropdown-toggle"><span class="fa fa-check"></span></span>' : '<span class="label label-danger"><span class="fa fa-lock"></span></span>') . '</td>';
                $html .= '<td class="text-center">';
                $html .= '<ul class="icons-list">';

                if (($this->auth->our->update == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->update == 1 and $row->created_user_id != $this->session->adminUserId)) {
                    $html .= '<li><a href="javascript:;" onclick="_editFormNifsCrimeType({elem: this, id:' . $row->id . '});" title="Засах" data-action="edit"></a></li>';
                } else {
                    $html .= '<li><a href="javascript:;" class="disabled" title="Засах" data-action="edit"></a></li>';
                }

                if (($this->auth->our->update == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->update == 1 and $row->created_user_id != $this->session->adminUserId)) {
                    $html .= '<li><a href="javascript:;" onclick="_removeNifsCrimeType({elem: this, id:' . $row->id . '});" title="Устгах" data-action="delete"></a></li>';
                } else {
                    $html .= '<li><a href="javascript:;" class="disabled" title="Устгах" data-action="delete"></a></li>';
                }

                $html .= '</ul>';
                $html .= '</td>';
                $html .= '</tr>';
                $j++;
                $html .= self::listsChild_model(array('parentId' => $row->id, 'space' => (intval($param['space']) + 30), 'autoNumber' => $j, 'moduleMenuId' => $param['moduleMenuId']));
            }
        }
        return $html;
    }

    public function insert_model($param = array('getUID' => 0)) {

        $this->data = array(
            array(
                'id' => getUID('nifs_crime_type'),
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'parent_id' => $this->input->post('parentId'),
                'title' => $this->input->post('title'),
                'order_num' => $this->input->post('orderNum'),
                'is_active' => $this->input->post('isActive'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0
            )
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'nifs_crime_type', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array('pic' => '')) {

        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'parent_id' => $this->input->post('parentId'),
            'title' => $this->input->post('title'),
            'order_num' => $this->input->post('orderNum'),
            'is_active' => $this->input->post('isActive'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'nifs_crime_type', $this->data)) {
            return array('status' => 'success', 'message' => 'Өөрчлөлтийг амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Өөрчлөлтийн түүхийг хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {

        $delelteArray = $this->getChildNifsCrimeType_model($this->input->post('id'));
        $this->db->where_in('id', explode(',', $delelteArray));
        if ($this->db->delete($this->db->dbprefix . 'nifs_crime_type')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function searchKeywordView_model() {
        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('catId')) {
            $this->cat = $this->category->getData_model(array('selectedId' => $this->input->get('catId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->cat->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            //' . Scrime::$path . $param['path'] . '/' . $param['modId'] . '
            $this->string .= ' <a href="javascript:;" onclick="_initNifsCrimeType({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return $this->string;
    }

    public function controlNifsMasterCaseDropdown_model($param = array('selectedId' => 0)) {

        $queryString = $html = $string = $class = '';

        if (isset($param['disabled']) and $param['disabled'] == true) {
            $string .= ' disabled="true"';
        }

        if (isset($param['required']) and $param['required'] == true) {
            $string .= ' required="true"';
            $class .= ' required';
        }

        if (isset($param['readonly']) and $param['readonly'] == true) {
            $string .= ' disabled="true"';
        }
        
        $query = $this->db->query('
            SELECT 
                NMC.id,
                NMC.title
            FROM `gaz_nifs_master_case` AS NMC
            WHERE NMC.is_active = 1 ' . $queryString . '
            ORDER BY NMC.order_num ASC');

        $html .= '<select name="caseId" id="caseId" class="select2" ' . $string . '>';
        $html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';
        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {
                $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';
            }
        }

        $html .= '</select>';

        return $html;
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $query = $this->db->query('
            SELECT 
                NMC.id,
                NMC.mod_id,
                NMC.title,
                NMC.order_num,
                NMC.is_active,
                NMC.created_date,
                NMC.modified_date,
                NMC.created_user_id,
                NMC.modified_user_id
            FROM `gaz_nifs_master_case` AS NMC
            WHERE NMC.id = ' . $param['selectedId']);


        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

}
