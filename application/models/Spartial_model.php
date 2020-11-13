<?php

class Spartial_model extends CI_Model {

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
            'pic' => 'default.svg',
            'title' => '',
            'partial' => '',
            'mod_id' => '',
            'cat_id' => 1,
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s'),
            'order_num' => getOrderNum(array('table' => 'category', 'field' => 'order_num')),
            'partner_id' => 0,
            'is_active' => 1,
            'lang_id' => $this->session->adminLangId,
            'intro_text' => '')));
    }

    public function editFormData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                P.id,
                (case when (P.pic is null or P.pic = \'\') then \'default.svg\' else concat(\'s_\', P.pic) end) as pic,
                P.title,
                P.partial,
                P.mod_id,
                P.cat_id,
                P.created_user_id,
                P.modified_user_id,
                P.created_date,
                P.modified_date,
                P.order_num,
                P.partner_id,
                P.is_active,
                P.lang_id,
                P.intro_text
            FROM `gaz_partial` AS P
            WHERE P.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function listsCount_model($param = array()) {
        $this->queryString = $this->getString = '';

        $this->auth = $param['auth'];

        if ($this->auth->our->read == 1 and $this->auth->your->read == 1) {
            $this->queryString .= '';
        } elseif ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND P.created_user_id = ' . $this->session->adminUserId;
        } else {
            $this->queryString .= ' AND P.created_user_id = -1';
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND LOWER(P.title) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
        }

        $this->query = $this->db->query('
            SELECT 
                P.id
            FROM `gaz_partial` AS P 
            WHERE P.lang_id = ' . $this->session->adminLangId . $this->queryString . ' AND P.mod_id = ' . $this->auth->modId . ' 
            ORDER BY P.id DESC');

        return $this->query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $this->queryString = $this->getString = '';

        $this->auth = $param['auth'];

        if ($this->auth->our->read == 1 and $this->auth->your->read == 1) {
            $this->queryString .= '';
        } elseif ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND P.created_user_id = ' . $this->session->adminUserId;
        } else {
            $this->queryString .= ' AND P.created_user_id = -1';
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND LOWER(P.title) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        $this->query = $this->db->query('
            SELECT 
                P.id,
                (case when (P.pic is null or P.pic = \'\') then \'default.svg\' else concat(\'s_\', P.pic) end) as pic,
                P.title,
                P.partial,
                P.mod_id,
                P.cat_id,
                P.created_user_id,
                P.modified_user_id,
                P.created_date,
                P.modified_date,
                P.order_num,
                P.partner_id,
                P.is_active,
                P.lang_id,
                P.intro_text
            FROM `gaz_partial` AS P
            WHERE P.lang_id = ' . $this->session->adminLangId . $this->queryString . ' AND P.mod_id = ' . $this->auth->modId . ' 
            ORDER BY P.id DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        $this->html = form_open('', array('class' => 'form-horizontal', 'id' => 'form-partial-init', 'enctype' => 'multipart/form-data'));
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
            $this->html .= form_button('addPartial', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Шинэ (F2)</span>', 'class="btn btn-default legitRipple" onclick="_addFormPartial({elem: this});"', 'button');
        } else {
            $this->html .= form_button('addPartial', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Шинэ (F2)</span>', 'class="btn btn-default legitRipple" disabled="true"', 'button');
        }

        $this->html .= form_button('searchPartial', '<i class="icon-search4"></i> <span class="hidden-xs position-right">Хайх (F3)</span>', 'class="btn btn-default legitRipple" onclick="_advensedSearchPartial({elem: this});"', 'button');

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
            $this->html .= '<th style="width:150px;">Зураг</th>';
            $this->html .= '<th>Гарчиг</th>';
            $this->html .= '<th style="width:100px;">Огноо</th>';
            $this->html .= '<th style="width:60px;">Төлөв</th>';
            $this->html .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->html .= '</tr>';
            $this->html .= '</thead>';
            $this->html .= '<tbody data-link="row" class="rowlink">';

            $i = 0;
            foreach ($this->query->result() as $key => $row) {

                $this->html .= '<tr data-id="' . $row->id . '" data-uid="' . $row->created_user_id . '">';
                $this->html .= '<td class="text-center">' . ++$i . '</td>';
                $this->html .= '<td class="context-menu-partial-selected-row"><img src="' . UPLOADS_CONTENT_PATH . $row->pic . '"></td>';
                $this->html .= '<td class="context-menu-partial-selected-row">' . $row->id . ' - ' . $row->title . '</td>';
                $this->html .= '<td class="context-menu-partial-selected-row text-center">' . date('Y-m-d', strtotime($row->created_date)) . '</td>';
                $this->html .= '<td class="context-menu-partial-selected-row text-center">' . ($row->is_active == 1 ? '<span class="label bg-teal dropdown-toggle"><span class="fa fa-check"></span></span>' : '<span class="label label-danger"><span class="fa fa-lock"></span></span>') . '</td>';

                $this->html .= '<td class="text-center">';
                $this->html .= '<ul class="icons-list">';

                if (($this->auth->our->update == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->update == 1 and $row->created_user_id != $this->session->adminUserId)) {
                    $this->html .= '<li><a href="javascript:;" onclick="_editFormPartial({elem: this, id:' . $row->id . '});" title="Засах" data-action="edit"></a></li>';
                } else {
                    $this->html .= '<li class="disabled" title="Засах" data-action="edit"></li>';
                }

                if (($this->auth->our->delete == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->delete == 1 and $row->created_user_id != $this->session->adminUserId)) {
                    $this->html .= '<li><a href="javascript:;" onclick="_removePartial({elem: this, id:' . $row->id . '});" title="Устгах" data-action="delete"></a></li>';
                } else {
                    $this->html .= '<li class="disabled" title="Устгах" data-action="delete"></li>';
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

    public function insert_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        $data = array(
            array(
                'id' => $param['getUID'],
                'pic' => $this->input->post('pic'),
                'title' => $this->input->post('title'),
                'partial' => $this->input->post('partial'),
                'mod_id' => $this->input->post('modId'),
                'cat_id' => 0,
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'order_num' => getOrderNum(array('table' => 'category', 'field' => 'order_num')),
                'partner_id' => 0,
                'is_active' => $this->input->post('isActive'),
                'lang_id' => $this->session->adminLangId,
                'intro_text' => $this->input->post('introText')));

        if ($this->db->insert_batch($this->db->dbprefix . 'partial', $data)) {
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
            'pic' => ($this->input->post('pic') != '' ? $this->input->post('pic') : $this->input->post('oldPic')),
            'title' => $this->input->post('title'),
            'partial' => $this->input->post('partial'),
            'mod_id' => $this->input->post('modId'),
            'cat_id' => 0,
            'modified_user_id' => $this->session->adminUserId,
            'modified_date' => date('Y-m-d H:i:s'),
            'order_num' => getOrderNum(array('table' => 'category', 'field' => 'order_num')),
            'partner_id' => 0,
            'is_active' => $this->input->post('isActive'),
            'lang_id' => $this->session->adminLangId,
            'intro_text' => $this->input->post('introText'));

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'partial', $data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {

        $this->db->where('id', $this->input->post('id'));
        $query = $this->db->get($this->db->dbprefix . 'partial');
        $row = $query->row();

        $this->slog->log_model(array(
            'modId' => $row->mod_id,
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_DELETE,
            'data' => json_encode($row)));

        $this->simage->removeUploadImage_model(array('uploadImage' => $row->pic, 'uploadPath' => '.' . UPLOADS_CONTENT_PATH));

        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'partial')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function getData_model($param = array('selectedId' => 0)) {

        if ($param['selectedId'] != NULL and $param['selectedId'] != 0) {

            $this->query = $this->db->query('
            SELECT 
                P.id,
                (case when (P.pic is null or P.pic = \'\') then \'default.svg\' else concat(\'s_\', P.pic) end) as pic,
                P.title,
                P.partial,
                P.mod_id,
                P.cat_id,
                P.created_user_id,
                P.modified_user_id,
                P.created_date,
                P.modified_date,
                P.order_num,
                P.partner_id,
                P.is_active,
                P.lang_id,
                P.intro_text
            FROM `gaz_partial` AS P
            WHERE P.id = ' . $param['selectedId']);

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

}
