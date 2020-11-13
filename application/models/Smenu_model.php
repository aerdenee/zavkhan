<?php

class Smenu_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->model('ScontextMenu_model', 'contextMenu');
        $this->moduleId = 1;
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'module_id' => 1,
            'location_id' => 0,
            'parent_id' => 0,
            'mod_id' => 0,
            'cat_id' => 0,
            'cont_id' => 0,
            'title' => '',
            'link_title' => '',
            'h1_text' => '',
            'page_title' => '',
            'meta_key' => '',
            'meta_desc' => '',
            'is_active' => 1,
            'order_num' => getOrderNum(array('table' => 'menu', 'field' => 'order_num')),
            'target' => '_parent',
            'direct_url' => '',
            'link_type_id' => 1,
            'param' => '',
            'show_pic' => '',
            'pic' => $this->simage->returnDefaultImage_model(array('table' => 'menu')),
            'class' => '',
            'style' => '',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s'),
            'is_home' => 0,
            'column_count' => 1,
            'url' => getUID('url'),
            'partner_id' => 0)));
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                M.id,
                M.module_id,
                M.location_id,
                M.parent_id,
                M.mod_id,
                M.cat_id,
                M.cont_id,
                M.title,
                M.link_title,
                M.h1_text,
                M.page_title,
                M.meta_key,
                M.meta_desc,
                M.is_active,
                M.order_num,
                M.target,
                M.direct_url,
                M.link_type_id,
                M.param,
                M.show_pic,
                (case when (M.pic is null or M.pic = \'\' or M.pic = \'default.svg\') then \'' . $this->simage->returnDefaultImage_model(array('table' => 'menu')) . '\' else concat(\'' . UPLOADS_CONTENT_PATH . 's_\', M.pic) end) as pic,
                M.class,
                M.style,
                M.created_user_id,
                M.modified_user_id,
                M.created_date,
                M.modified_date,
                M.is_home,
                M.column_count,
                U.id AS url_id,
                U.url,
                M.partner_id
            FROM `gaz_menu` AS M
            LEFT JOIN `gaz_url` AS U ON M.id = U.cont_id AND M.module_id = U.mod_id
            WHERE M.id = ' . $param['id']);

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
            $queryString .= ' AND M.created_user_id = ' . $this->session->userdata['adminUserId'];
        } else if (!$auth->our->read and !$auth->your->read) {
            $queryString .= ' AND M.created_user_id = -1';
        }


        if ($param['catId'] != 0) {

            $queryString .= ' AND M.location_id = ' . $param['catId'];
        }

        if ($param['partnerId'] != 0) {

            $queryString .= ' AND M.partner_id = ' . $param['partnerId'];
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND LOWER(M.title) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
        }

        $query = $this->db->query('
            SELECT 
                M.id
            FROM `gaz_menu` AS M
            WHERE M.parent_id = 0 AND M.lang_id = ' . $this->session->userdata['adminLangId'] . $queryString);

        return $query->num_rows();
    }

    public function lists_model($param = array('moduleId' => 0, 'catId' => 0)) {

        $queryString = $getString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and !$auth->your->read) {
            $queryString .= ' AND M.created_user_id = ' . $this->session->userdata['adminUserId'];
        } else if (!$auth->our->read and !$auth->your->read) {
            $queryString .= ' AND M.created_user_id = -1';
        }


        if ($param['catId'] != 0) {

            $queryString .= ' AND M.location_id = ' . $param['catId'];
            $getString .= form_hidden('catId', $param['catId']);
        }

        if ($param['partnerId'] != 0) {

            $queryString .= ' AND M.partner_id = ' . $param['partnerId'];
            $getString .= form_hidden('partnerId', $param['partnerId']);
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND LOWER(M.title) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
            $getString .= form_hidden('keyword', $param['keyword']);
        }

        $query = $this->db->query('
            SELECT 
                M.id,
                M.title,
                M.module_id,
                M.order_num,
                DATE(M.created_date) AS created_date,
                M.modified_date,
                M.created_user_id,
                M.modified_user_id,
                IF(M.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                IF(M.is_home > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-home\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_home,
                C.title AS cat_title
            FROM `gaz_menu` AS M
            LEFT JOIN `gaz_category` AS C ON M.location_id = C.id
            WHERE M.parent_id = 0 AND M.lang_id = ' . $this->session->userdata['adminLangId'] . $queryString . '
            ORDER BY M.order_num DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        $html = form_open('', array('class' => 'form-100 form-horizontal', 'id' => 'form-menu-init', 'enctype' => 'multipart/form-data'));
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

            $html .= '<td><a href="javascript:;" class="l-btn l-btn-small l-btn-plain" group="" id="" onclick="_addFormMenu({elem: this});"><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">Шинэ (F2)</span><span class="l-btn-icon dg-icon-add">&nbsp;</span></span></a></td>';
        } else {

            $html .= '<td><a href="javascript:;" class="l-btn l-btn-small l-btn-plain disabled" group="" id=""><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">Шинэ (F2)</span><span class="l-btn-icon dg-icon-add">&nbsp;</span></span></a></td>';
        }

        $html .= '<td><div class="datagrid-btn-separator"></div></td>';

        $html .= '<td><a href="javascript:;" class="l-btn l-btn-small l-btn-plain" onclick="_advensedSearchMenu({elem: this});"><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">Дэлгэрэнгүй хайлт (Ctrl+f)</span><span class="l-btn-icon dg-icon-search">&nbsp;</span></span></a></td>';

        $html .= '</tr></tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= self::searchKeywordView_model();
        $html .= '</div>';

        if ($query->num_rows() > 0) {

            $html .= '<div class="table-responsive">';
            $html .= '<table class="table _dg">';
            $html .= '<thead>';
            $html .= '<tr class="_gridTitle">';
            $html .= '<th style="width:30px;">#</th>';
            $html .= '<th>Гарчиг</th>';
            $html .= '<th style="width:350px;">Ангилал</th>';
            $html .= '<th style="width:100px;" class="text-center">Огноо</th>';
            $html .= '<th style="width:60px;" class="text-center">Төлөв</th>';
            $html .= '<th style="width:60px;" class="text-center">Эхлэл</th>';
            $html .= '<th style="width:60px;" class="text-center">Эрэмбэ</th>';
            $html .= '<th style="width:60px;" class="text-center">ID</th>';
            $html .= '<th style="width:60px;" class="text-center"><i class="icon-cog7"></i></th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody data-link="row" class="rowlink">';

            $i = 1;

            foreach ($query->result() as $key => $row) {

                $html .= '<tr data-id="' . $row->id . '" data-uid="' . $row->created_user_id . '">';
                $html .= '<td class="text-center">' . $i . '</td>';
                $html .= '<td class="context-menu-menu-selected-row">' . $row->title . '</td>';
                $html .= '<td class="context-menu-menu-selected-row">' . $row->cat_title . '</td>';
                $html .= '<td class="context-menu-menu-selected-row">' . $row->created_date . '</td>';
                $html .= '<td class="context-menu-menu-selected-row text-center">' . $row->is_active . '</td>';
                $html .= '<td class="context-menu-menu-selected-row text-center">' . $row->is_home . '</td>';
                $html .= '<td class="context-menu-menu-selected-row text-center">' . $row->order_num . '</td>';
                $html .= '<td class="context-menu-menu-selected-row text-center">' . $row->id . '</td>';
                $html .= '<td class="text-center">';
                $html .= '<div class="list-icons">';

                if (($auth->our->update and $row->created_user_id == $this->session->adminUserId) or ( $auth->your->update and $row->created_user_id != $this->session->adminUserId)) {
                    $html .= '<div onclick="_editFormMenu({elem: this, id: ' . $row->id . '});" class="list-icons-item"><i class="icon-pencil7"></i></div>';
                } else {
                    $html .= '<div class="list-icons-item disabled"><i class="icon-pencil7"></i></div>';
                }
                if (($auth->our->delete and $row->created_user_id == $this->session->adminUserId) or ( $auth->your->delete and $row->created_user_id != $this->session->adminUserId)) {
                    $html .= '<div onclick="_removeMenu({elem: this, id: ' . $row->id . '});" class="list-icons-item"><i class="icon-trash"></i></div>';
                } else {
                    $html .= '<div class="list-icons-item disabled"><i class="icon-trash disabled"></i></div>';
                }

                $html .= '</td>';
                $html .= '</tr>';

                $html .= $this->listsChild_model(array('parentId' => $row->id, 'moduleId' => $row->module_id, 'space' => 30, 'childMenuHtml' => '', 'autoNumber' => $i, 'auth' => $auth));
                $i++;
            }

            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '</div>';
        }


        $html .= '<div class="card-footer d-sm-flex justify-content-sm-between align-items-sm-center">';
        $html .= $param['paginationHtml'];

        $html .= '</div>';
        $html .= '</div>';

        $html .= form_close();

        return $html;
    }

    public function listsChild_model($param = array('parentId' => 0, 'moduleId' => 0, 'space' => 10, 'childMenuHtml' => '', 'autoNumber' => 1)) {

        $auth = $param['auth'];
        
        $query = $this->db->query('
            SELECT 
                M.id,
                M.title,
                M.module_id,
                M.order_num,
                DATE(M.created_date) AS created_date,
                M.modified_date,
                M.created_user_id,
                M.modified_user_id,
                IF(M.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                IF(M.is_home > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-home\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_home,
                C.title AS cat_title
            FROM `gaz_menu` AS M
            LEFT JOIN `gaz_category` AS C ON M.location_id = C.id
            WHERE M.lang_id = ' . $this->session->userdata['adminLangId'] . ' AND M.parent_id = ' . $param['parentId'] . '
            ORDER BY M.order_num DESC');

        if ($query->num_rows() > 0) {

            $j = 1;
            foreach ($query->result() as $row) {

                $param['childMenuHtml'] .= '<tr data-id="' . $row->id . '" data-uid="' . $row->created_user_id . '">';
                $param['childMenuHtml'] .= '<td>' . $param['autoNumber'] . '.' . $j . '</td>';
                $param['childMenuHtml'] .= '<td class="context-menu-menu-selected-row" style="padding-left:' . $param['space'] . 'px;">' . $row->title . '</td>';
                $param['childMenuHtml'] .= '<td class="context-menu-menu-selected-row">' . $row->cat_title . '</td>';
                $param['childMenuHtml'] .= '<td class="context-menu-menu-selected-row">' . $row->created_date . '</td>';
                $param['childMenuHtml'] .= '<td class="context-menu-menu-selected-row text-center">' . $row->is_active . '</td>';
                $param['childMenuHtml'] .= '<td class="context-menu-menu-selected-row text-center">' . $row->is_home . '</td>';
                $param['childMenuHtml'] .= '<td class="context-menu-menu-selected-row text-center">' . $row->order_num . '</td>';
                $param['childMenuHtml'] .= '<td class="context-menu-menu-selected-row text-center">' . $row->id . '</td>';
                $param['childMenuHtml'] .= '<td class="text-center">';
                $param['childMenuHtml'] .= '<div class="list-icons">';
                if (($auth->our->update and $row->created_user_id == $this->session->adminUserId) or ( $auth->your->update and $row->created_user_id != $this->session->adminUserId)) {
                    $param['childMenuHtml'] .= '<div onclick="_editFormMenu({elem: this, id: ' . $row->id . '});" class="list-icons-item"><i class="icon-pencil7"></i></div>';
                } else {
                    $param['childMenuHtml'] .= '<div class="list-icons-item disabled"><i class="icon-pencil7"></i></div>';
                }
                if (($auth->our->delete and $row->created_user_id == $this->session->adminUserId) or ( $auth->your->delete and $row->created_user_id != $this->session->adminUserId)) {
                    $param['childMenuHtml'] .= '<div onclick="_removeMenu({elem: this, id: ' . $row->id . '});" class="list-icons-item"><i class="icon-trash"></i></div>';
                } else {
                    $param['childMenuHtml'] .= '<div class="list-icons-item disabled"><i class="icon-trash disabled"></i></div>';
                }
                $param['childMenuHtml'] .= '</td>';

                $param['childMenuHtml'] .= '</tr>';
                $param['childMenuHtml'] .= self::listsChild_model(array('parentId' => $row->id, 'moduleId' => $param['moduleId'], 'space' => (intval($param['space']) + 30), 'childMenuHtml' => '', 'autoNumber' => $param['autoNumber'] . '.' . $j, 'auth' => $auth));
                $param['autoNumber'] ++;
                $j++;
            }
        }
        return $param['childMenuHtml'];
    }

    public function insert_model($param = array()) {


        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->userdata['adminUserId'],
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        $this->param = array();
        $data = array(
            array(
                'id' => $param['getUID'],
                'module_id' => $this->moduleId,
                'location_id' => $this->input->post('locationId'),
                'parent_id' => $this->input->post('parentId'),
                'mod_id' => ($this->input->post('linkTypeId') == 1 ? $this->input->post('modId') : ''),
                'cat_id' => ($this->input->post('linkTypeId') == 1 ? $this->input->post('catId') : ''),
                'cont_id' => ($this->input->post('linkTypeId') == 1 ? $this->input->post('contId') : ''),
                'title' => $this->input->post('title'),
                'link_title' => $this->input->post('linkTitle'),
                'h1_text' => $this->input->post('h1Text'),
                'page_title' => $this->input->post('pageTitle'),
                'meta_key' => $this->input->post('metaKey'),
                'meta_desc' => $this->input->post('metaDesc'),
                'is_active' => $this->input->post('isActive'),
                'order_num' => $this->input->post('orderNum'),
                'target' => $this->input->post('target'),
                'direct_url' => $this->input->post('directUrl'),
                'link_type_id' => $this->input->post('linkTypeId'),
                'param' => json_encode($this->param),
                'show_pic' => $this->input->post('showPic'),
                'pic' => $this->input->post('menuPic'),
                'class' => $this->input->post('class'),
                'style' => $this->input->post('style'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'partner_id' => $this->input->post('partnerId'),
                'column_count' => $this->input->post('columnCount'),
                'lang_id' => $this->session->adminLangId,
                'is_home' => $this->input->post('isHome')
            )
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'menu', $data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->userdata['adminUserId'],
            'type' => LOG_TYPE_UPDATE,
            'data' => json_encode($_POST)));

        $this->param = array();
        $data = array(
            'location_id' => $this->input->post('locationId'),
            'parent_id' => $this->input->post('parentId'),
            'mod_id' => ($this->input->post('linkTypeId') == 1 ? $this->input->post('modId') : ''),
            'cat_id' => ($this->input->post('linkTypeId') == 1 ? $this->input->post('catId') : ''),
            'cont_id' => ($this->input->post('linkTypeId') == 1 ? $this->input->post('contId') : ''),
            'title' => $this->input->post('title'),
            'link_title' => $this->input->post('linkTitle'),
            'h1_text' => $this->input->post('h1Text'),
            'page_title' => $this->input->post('pageTitle'),
            'meta_key' => $this->input->post('metaKey'),
            'meta_desc' => $this->input->post('metaDesc'),
            'is_active' => $this->input->post('isActive'),
            'order_num' => $this->input->post('orderNum'),
            'target' => $this->input->post('target'),
            'direct_url' => $this->input->post('directUrl'),
            'link_type_id' => $this->input->post('linkTypeId'),
            'param' => json_encode($this->param),
            'show_pic' => $this->input->post('showPic'),
            'pic' => ($this->input->post('menuPic') != '' ? $this->input->post('menuPic') : $this->input->post('menuOldPic')),
            'class' => $this->input->post('class'),
            'style' => $this->input->post('style'),
            'modified_user_id' => $this->session->adminUserId,
            'modified_date' => date('Y-m-d H:i:s'),
            'partner_id' => $this->input->post('partnerId'),
            'column_count' => $this->input->post('columnCount'),
            'is_home' => $this->input->post('isHome'));

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'menu', $data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {


        if ($this->input->post('id')) {

            $listId = self::getChildMenus_model($this->input->post('id'));

            $this->query = $this->db->query('SELECT id, pic, mod_id, lang_id FROM `gaz_menu` WHERE id IN (' . $listId . ')');

            foreach ($this->query->result() as $key => $row) {
                $this->simage->removeUploadImage_model(array('uploadImage' => $row->pic, 'uploadPath' => UPLOADS_CONTENT_PATH));
                generateUrl(array('modId' => $row->mod_id, 'contId' => $row->id, 'langId' => $row->lang_id, 'mode' => 'delete'));

                $rowDeleteData = $this->getData_model(array('selectedId' => $row->id));
                $this->slog->log_model(array(
                    'modId' => $rowDeleteData->mod_id,
                    'createdUserId' => $this->session->adminUserId,
                    'type' => LOG_TYPE_DELETE,
                    'data' => json_encode($rowDeleteData)));
            }
            if ($this->db->query('DELETE FROM `gaz_menu` WHERE id IN (' . $listId . ')')) {
                return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
            }
        }

        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function menuParentList_model($param = array('locationId' => 0, 'id' => 0, 'editId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1)) {

        $str = '';

        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.title
            FROM `gaz_menu` AS C
            WHERE C.parent_id = ' . $param['parentId'] . ' AND C.location_id = ' . $param['locationId'] . '  AND C.id != ' . $param['editId'] . ' 
            ORDER BY C.order_num ASC');

        if ($param['counter'] == 1) {
            $str .= '<option value="0" selected="selected">' . ' - Сонгох - </option>';
        }
        if ($this->query->num_rows() > 0) {
            foreach ($this->query->result() as $key => $row) {

                $str .= '<option value="' . $row->id . '" ' . ($param['id'] == $row->id ? 'selected="selected"' : '') . '>' . $param['space'] . $row->title . '</option>';
                $param['counter'] = $param['counter'] + 1;
                $str .= self::menuParentList_model(array('locationId' => $param['locationId'], 'id' => $param['id'], 'editId' => $param['editId'], 'parentId' => $row->id, 'space' => '&nbsp; &nbsp; &nbsp;' . $param['space'], 'counter' => $param['counter']));
            }
        }
        return $str;
    }

    public function controlModuleListDropdown_model($param = array('selectedId' => 0)) {
        $this->db->where('is_active', 1);
        $this->db->order_by('order_num', 'ASC');
        $query = $this->db->get($this->db->dbprefix . 'module');
        $result = $query->result();
        $data = '<select name="modId" id="modId" class="form-control select2" required="required">';
        $data .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';
        if (count($result) > 0) {
            foreach ($result as $key => $value) {
                $row = (array) $value;
                if ($row['table'] != 'menu') {
                    $data .= '<option value="' . $row['id'] . '" ' . ($param['selectedId'] == $row['id'] ? 'selected="selected"' : '') . '>' . $row['title'] . '</option>';
                }
            }
        }
        $data .= '</select>';
        return $data;
    }

    public function controlContentListDropdown_model($param = array('modId' => 0, 'catId' => 0, 'contId' => 0)) {

        $queryStringTable = '';
        $data = '<select name="contId" id="contId" class="form-control select2">';

        $data .= '<option value="0" ' . ($param['contId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';

        if (!empty($param['modId'])) {

            $queryModule = $this->db->query('
                SELECT 
                    M.id,
                    M.table
                FROM `gaz_module` AS M
                WHERE M.id = ' . $param['modId']);
            $rowModule = $queryModule->row();

            if ($param['catId'] != 0) {
                $queryStringTable .= ' AND T.cat_id = ' . $param['catId'];
            }

            $queryTable = $this->db->query('
                SELECT 
                    T.id,
                    T.title
                FROM `gaz_' . $rowModule->table . '` AS T
                WHERE T.is_active = 1 AND T.mod_id = ' . $param['modId'] . ' AND T.cat_id = ' . $param['catId']);

            if ($queryTable->num_rows() > 0) {
                foreach ($queryTable->result() as $key => $rowTable) {
                    $data .= '<option value="' . $rowTable->id . '" ' . ($param['contId'] == $rowTable->id ? 'selected="selected"' : '') . '>' . $rowTable->title . '</option>';
                }
            }
        }

        $data .= '</select>';

        return $data;
    }

    public function searchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('inDate') and $this->input->get('outDate')) {
            $this->string .= '<span class="keyword-tag">Бүртгэсэн огноо: ' . $this->input->get('inDate') . '-' . $this->input->get('outDate') . '</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('inDate')) {
            $this->string .= '<span class="keyword-tag">Бүртгэсэн огноо: ' . $this->input->get('inDate') . ' хойш</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('outDate')) {
            $this->string .= '<span class="keyword-tag">Бүртгэсэн огноо: ' . $this->input->get('outDate') . ' өмнөх</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="keyword-tag">' . $this->input->get('keyword') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('catId')) {
            $this->cat = $this->category->getData_model(array('selectedId' => $this->input->get('catId')));
            $this->string .= '<span class="keyword-tag">' . $this->cat->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('partnerId')) {
            $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->get('partnerId')));
            $this->string .= '<span class="keyword-tag">' . $this->partnerData->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            //' . Scrime::$path . $param['path'] . '/' . $param['modId'] . '
            $this->string .= ' <a href="javascript:;" onclick="_initMenu({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return $this->string;
    }

    public function mLists_model($param = array('locationId' => 0)) {

        $data = array();

        $query = $this->db->query('
            SELECT 
                M.id,
                M.mod_id,
                M.cat_id,
                M.cont_id,
                M.title
            FROM `gaz_menu` AS M
            WHERE M.parent_id = 0 AND M.is_active = 1 AND M.location_id = ' . $param['locationId'] . '
            ORDER BY M.order_num ASC');

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $row->child = $this->mListsChild_model(array('parentId' => $row->id, 'locationId' => $param['locationId']));

                array_push($data, $row);
            }

            return $data;
        }

        return false;
    }

    public function mListsChild_model($param = array('locationId' => 0)) {

        $data = array();

        $query = $this->db->query('
            SELECT 
                M.id,
                M.mod_id,
                M.cat_id,
                M.cont_id,
                M.title
            FROM `gaz_menu` AS M
            WHERE M.parent_id = ' . $param['parentId'] . ' AND M.is_active = 1 AND M.location_id = ' . $param['locationId'] . '
            ORDER BY M.order_num ASC');

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $row->child = $this->mListsChild_model(array('parentId' => $row->id, 'locationId' => $param['locationId']));

                array_push($data, $row);
            }

            return $data;
        }
        return false;
    }

    public function getChildMenus_model($param = array()) {

        $this->data = '';
        if ($param != '') {

            $param = explode(',', $param);

            if (is_array($param) and count($param) > 0) {
                foreach ($param as $partnerKey => $partnerId) {
                    $this->query = $this->db->query('
                    SELECT 
                        M.id
                    FROM `' . $this->db->dbprefix . 'menu` AS M 
                    WHERE M.is_active = 1 AND M.parent_id = ' . $partnerId);

                    if ($this->query->num_rows() > 0) {
                        foreach ($this->query->result() as $key => $row) {
                            $this->data .= $row->id . ',';
                            $this->data .= self::getChildMenus_model($row->id) . ',';
                        }
                        $this->data .= $partnerId . ',';
                    } else {
                        $this->data .= $partnerId . ',';
                    }
                }
            } else {
                $this->query = $this->db->query('
                    SELECT 
                        M.id
                    FROM `' . $this->db->dbprefix . 'menu` AS M 
                    WHERE M.is_active = 1 AND M.parent_id = ' . $param);

                if ($this->query->num_rows() > 0) {
                    foreach ($this->query->result() as $key => $row) {
                        $this->data .= $row->id . ',';
                        $this->data .= self::getChildMenus_model($row->id) . ',';
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

    public function getData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                M.id,
                M.module_id,
                M.location_id,
                M.parent_id,
                M.mod_id,
                M.cat_id,
                M.cont_id,
                M.title,
                M.link_title,
                M.h1_text,
                M.page_title,
                M.meta_key,
                M.meta_desc,
                M.is_active,
                M.order_num,
                M.target,
                M.direct_url,
                M.link_type_id,
                M.param,
                M.show_pic,
                (case when (M.pic is null or M.pic = \'\') then \'default.svg\' else concat(\'s_\', M.pic) end) as pic,
                M.pic_background,
                M.class,
                M.style,
                M.created_user_id,
                M.modified_user_id,
                M.created_date,
                M.modified_date,
                M.is_home,
                M.column_count,
                U.id AS url_id,
                U.url,
                M.partner_id
            FROM `gaz_menu` AS M
            LEFT JOIN `gaz_url` AS U ON M.id = U.cont_id AND M.module_id = U.mod_id
            WHERE M.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

}
