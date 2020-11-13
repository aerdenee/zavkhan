<?php

class SmoduleMenu_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Suser_model', 'user');
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'module_id' => 0,
            'parent_id' => 0,
            'menu_type_id' => 1,
            'mod_id' => 0,
            'cat_id' => 0,
            'cont_id' => 0,
            'title' => '',
            'order_num' => getOrderNum(array('table' => 'module_menu', 'field' => 'order_num')),
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s'),
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => $this->session->adminUserId,
            'is_active' => 1,
            'column_count' => 0,
            'icon' => 'icon-puzzle4',
            'menu_class' => '',
            'menu_css' => '',
            'partner_id' => 0)));
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                module_id,
                parent_id,
                menu_type_id,
                mod_id,
                cat_id,
                cont_id,
                title,
                order_num,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id,
                is_active,
                column_count,
                icon,
                menu_class,
                menu_css,
                partner_id
            FROM `gaz_module_menu`
            WHERE id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function listsCount_model($param = array('moduleId' => 0)) {
        $this->queryString = $this->getString = '';

        $this->auth = $param['auth'];

        if ($this->auth->our->read and $this->auth->your->read) {
            $this->queryString .= '';
        } else if ($this->auth->our->read and !$this->auth->your->read) {
            $this->queryString .= ' AND MM.created_user_id = ' . $this->session->adminUserId;
        } else if (!$this->auth->our->read and !$this->auth->your->read) {
            $this->queryString .= ' AND MM.created_user_id = -1';
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND MM.cat_id = ' . $param['catId'];
        }
        
        $this->query = $this->db->query('
            SELECT 
                MM.id
            FROM `gaz_module_menu` AS MM 
            WHERE MM.parent_id = 0 ' . $this->queryString);

        return $this->query->num_rows();
    }

    public function lists_model($param = array('moduleId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $this->queryString = $this->getString = '';
        $this->auth = $param['auth'];

        if ($this->auth->our->read and $this->auth->your->read) {
            $this->queryString .= '';
        } else if ($this->auth->our->read and !$this->auth->your->read) {
            $this->queryString .= ' AND MM.created_user_id = ' . $this->session->adminUserId;
        } else if (!$this->auth->our->read and !$this->auth->your->read) {
            $this->queryString .= ' AND MM.created_user_id = -1';
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND MM.cat_id = ' . $param['catId'];
            $this->getString .= form_hidden('catId', $param['catId']);
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
                MM.icon
            FROM `gaz_module_menu` AS MM
            WHERE  1 = 1 AND MM.parent_id = 0 ' . $this->queryString . '
            ORDER BY MM.order_num ASC');

        $html = form_open('', array('class' => 'form-100 form-horizontal', 'id' => 'form-module-menu-init', 'enctype' => 'multipart/form-data'));
        $html .= form_hidden('moduleId', $param['moduleId']);
        $html .= form_hidden('limit', $param['limit']);
        $html .= form_hidden('page', $param['page']);

        $html .= $this->getString;
        
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
        if ($this->auth->our->create == 1) {

            $html .= '<td><a href="javascript:;" class="l-btn l-btn-small l-btn-plain" group="" id="" onclick="_addFormModuleMenu({elem: this});"><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">Шинэ (F2)</span><span class="l-btn-icon dg-icon-add">&nbsp;</span></span></a></td>';
        } else {

            $html .= '<td><a href="javascript:;" class="l-btn l-btn-small l-btn-plain disabled" group="" id=""><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">Шинэ (F2)</span><span class="l-btn-icon dg-icon-add">&nbsp;</span></span></a></td>';
        }

        $html .= '<td><div class="datagrid-btn-separator"></div></td>';

        $html .= '<td><a href="javascript:;" class="l-btn l-btn-small l-btn-plain" onclick="_advensedSearchModuleMenu({elem: this});"><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">Дэлгэрэнгүй хайлт (Ctrl+f)</span><span class="l-btn-icon dg-icon-search">&nbsp;</span></span></a></td>';

        $html .= '</tr></tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= self::searchKeywordView_model();
        $html .= '</div>';

        if ($this->query->num_rows() > 0) {

            $html .= '<div class="table-responsive">';
            $html .= '<table class="table _dg">';
            $html .= '<thead>';
            $html .= '<tr class="_gridTitle">';
            $html .= '<th style="width:30px;">#</th>';
            $html .= '<th>Гарчиг</th>';
            $html .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody data-link="row" class="rowlink">';
            
            $i = 0;

            foreach ($this->query->result() as $key => $row) {

                $html .= '<tr data-id="' . $row->id . '" data-created-user-id="' . $row->created_user_id . '">';
                $html .= '<td>' . ++$i . '</td>';
                $html .= '<td class="context-menu-modulemenu-selected-row">' . $row->title . '</td>';
                $html .= '<td class="text-center">';
                $html .= '<div class="list-icons">';

                if (($this->auth->our->update and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->update and $row->created_user_id != $this->session->adminUserId)) {
                    $html .= '<div onclick="_editFormModuleMenu({elem: this, id: ' . $row->id . ', createdUserId: ' . $row->created_user_id . '});" class="list-icons-item"><i class="icon-pencil7"></i></div>';
                } else {
                    $html .= '<div class="list-icons-item disabled"><i class="icon-pencil7"></i></div>';
                }

                if (($this->auth->our->delete and $row->created_user_id == $this->session->adminUserId) or ($this->auth->your->delete and $row->created_user_id != $this->session->adminUserId)) {
                    $html .= '<div onclick="_deleteModuleMenu({elem: this, id: ' . $row->id . ', createdUserId: ' . $row->created_user_id . '});" class="list-icons-item"><i class="icon-trash"></i></div>';
                } else {
                    $html .= '<div class="list-icons-item disabled"><i class="icon-trash disabled"></i></div>';
                }

                $html .= '</ul>';
                $html .= '</td>';
                $html .= '</tr>';

                $html .= $this->listsChild_model(array('parentId' => $row->id, 'html' => '', 'space' => 50, 'number' => $i, 'auth' => $param['auth']));
            }

            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '</div>';
            $html .= '<div class="panel-footer">';

            $html .= '<div class="navbar text-right"></div>';
            $html .= '</div>';
        } 

        $html .= '<div class="card-footer d-sm-flex justify-content-sm-between align-items-sm-center">';
        $html .= $param['paginationHtml'];

        $html .= '</div>';
        $html .= '</div>';

        $html .= form_close();

        return $html;
    }

    public function listsChild_model($param = array('parentId' => 0, 'html' => '', 'space' => 20)) {
        
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
                MM.icon,
                MM.menu_class,
                MM.menu_css
            FROM `gaz_module_menu` AS MM
            WHERE MM.parent_id = ' . $param['parentId'] . '
            ORDER BY MM.order_num ASC');
        if ($this->query->num_rows() > 0) {
            $j = 0;
            foreach ($this->query->result() as $key => $row) {

                $param['html'] .= '<tr data-id="' . $row->id . '" data-created-user-id="' . $row->created_user_id . '">';
                $param['html'] .= '<td>' . $param['number'] . '.' . ++$j . '</td>';
                $param['html'] .= '<td class="context-menu-modulemenu-selected-row" style="padding-left:' . $param['space'] . 'px;">' . $row->title . '</td>';
                $param['html'] .= '<td class="text-center">';
                $param['html'] .= '<div class="list-icons">';

                if (($param['auth']->our->update and $row->created_user_id == $this->session->adminUserId) or ($param['auth']->your->update and $row->created_user_id != $this->session->adminUserId)) {
                    $param['html'] .= '<div onclick="_editFormModuleMenu({elem: this, id: ' . $row->id . ', createdUserId: ' . $row->created_user_id . '});" class="list-icons-item"><i class="icon-pencil7"></i></div>';
                } else {
                    $param['html'] .= '<div class="list-icons-item disabled"><i class="icon-pencil7"></i></div>';
                }

                if (($param['auth']->our->delete and $row->created_user_id == $this->session->adminUserId) or ($param['auth']->your->delete and $row->created_user_id != $this->session->adminUserId)) {
                    $param['html'] .= '<div onclick="_deleteModuleMenu({elem: this, id: ' . $row->id . ', createdUserId: ' . $row->created_user_id . '});" class="list-icons-item"><i class="icon-trash"></i></div>';
                } else {
                    $param['html'] .= '<div class="list-icons-item disabled"><i class="icon-trash disabled"></i></div>';
                }

                $param['html'] .= '</div>';
                $param['html'] .= '</td>';
                $param['html'] .= '</tr>';

                $param['html'] .= $this->listsChild_model(array('parentId' => $row->id, 'html' => '', 'space' => ($param['space'] + 20), 'number' => $j, 'auth' => $param['auth']));
            }
        }
        return $param['html'];
    }

    public function insert_model($param = array()) {
        
        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->userdata['adminUserId'],
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));
        
        $this->data = array(
            array(
                'id' => getUID('module_menu'),
                'module_id' => 54,
                'parent_id' => $this->input->post('parentId'),
                'menu_type_id' => $this->input->post('menuTypeId'),
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('moduleMenuCatId'),
                'cont_id' => $this->input->post('moduleMenuContId'),
                'title' => $this->input->post('title'),
                'order_num' => $this->input->post('orderNum'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => $this->session->adminUserId,
                'is_active' => 1,
                'column_count' => $this->input->post('columnCount'),
                'icon' => $this->input->post('icon'),
                'menu_class' => $this->input->post('menuClass'),
                'menu_css' => $this->input->post('menuCss'),
                'partner_id' => 0
            )
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'module_menu', $this->data)) {
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
        
        $this->data = array(
            'parent_id' => $this->input->post('parentId'),
            'menu_type_id' => $this->input->post('menuTypeId'),
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('moduleMenuCatId'),
            'cont_id' => $this->input->post('moduleMenuContId'),
            'title' => $this->input->post('title'),
            'order_num' => $this->input->post('orderNum'),
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s'),
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => $this->session->adminUserId,
            'is_active' => 1,
            'column_count' => $this->input->post('columnCount'),
            'icon' => $this->input->post('icon'),
            'menu_class' => $this->input->post('menuClass'),
            'menu_css' => $this->input->post('menuCss'),
            'partner_id' => 0
        );
        $this->db->where('id', $this->input->post('id'));


        if ($this->db->update($this->db->dbprefix . 'module_menu', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {
        
        $getData = $this->getData_model(array('selectedId' => $this->input->post('id')));
        $this->slog->log_model(array(
            'modId' => $getData->module_id,
            'createdUserId' => $this->session->userdata['adminUserId'],
            'type' => LOG_TYPE_DELETE,
            'data' => json_encode($getData)));
        
        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'module_menu')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function searchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            $this->string .= ' <a href="javascript:;" onclick="_initModuleMenu({moduleMenuId: ' . $param['moduleMenuId'] . ', page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return $this->string;
    }

    public function controlParentModuleMenuListDropdown_model($param = array('modId' => 0, 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1)) {

        $this->string = '';

        if (isset($param['disabled']) and $param['disabled'] == true) {
            $this->string .= ' disabled="true"';
        }

        if (isset($param['required']) and $param['required'] == true) {
            $this->string .= ' required="true"';
            $this->class .= ' required';
        }

        if (isset($param['readonly']) and $param['readonly'] == true) {
            $this->string .= ' disabled="true"';
        }

        $this->query = $this->db->query('
            SELECT 
                id,
                title
            FROM `gaz_module_menu`
            WHERE is_active = 1 AND parent_id = 0 
            ORDER BY order_num ASC');
        $this->html = '<select class="form-control" size="5" name="parentId" id="parentId" ' . $this->string . '>';
        $this->html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '>' . ' - Сонгох - </option>';

        if ($this->query->num_rows() > 0) {
            foreach ($this->query->result() as $key => $row) {

                $this->html .= '<option value="' . $row->id . '" ' . (($param['selectedId'] == $row->id) ? 'selected="selected"' : '') . '>' . $row->title . '</option>';

                $this->html .= self::controlParentModuleMenuChildListDropdown_model(array('selectedId' => $param['selectedId'], 'parentId' => $row->id, 'space' => '&nbsp; &nbsp; &nbsp; &nbsp;', 'html' => ''));
            }
        }
        $this->html .= '</select>';
        return $this->html;
    }

    public function controlParentModuleMenuChildListDropdown_model($param = array('selectedId' => 0, 'parentId' => 0, 'space' => '', 'html' => '')) {
        $param['html'] = '';
        $this->query = $this->db->query('
            SELECT 
                id,
                title
            FROM `gaz_module_menu`
            WHERE is_active = 1 AND parent_id = ' . $param['parentId'] . ' 
            ORDER BY order_num ASC');

        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $key => $row) {

                $param['html'] .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $param['space'] . $row->title . '</option>';

                $param['html'] .= $this->controlParentModuleMenuChildListDropdown_model(array('selectedId' => $param['selectedId'], 'parentId' => $row->id, 'space' => '&nbsp; &nbsp; &nbsp; &nbsp;' . $param['space'], 'html' => ''));
            }
        }

        return $param['html'];
    }

    public function controlCategoryDropdown_model($param = array('modId' => 0, 'selectedId' => 0)) {
        $query = $this->db->query('
            SELECT 
                C.id,
                C.title
            FROM `gaz_category` AS C
            WHERE C.mod_id = ' . $param['modId'] . '
            ORDER BY C.order_num ASC');

        $str = '<select class="form-control select2" name="moduleMenuCatId" id="moduleMenuCatId">';

        $str .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '>' . ' - Сонгох - </option>';

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $str .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';
                $str .= self::controlCategoryChildDropdown_model(array('selectedId' => $param['selectedId'], 'parentId' => $row->id, 'space' => '&nbsp; &nbsp; '));
            }
        }
        $str .= '</select>';
        return $str;
    }

    public function controlCategoryChildDropdown_model($param = array()) {
        $childHtml = '';
        $query = $this->db->query('
            SELECT 
                C.id,
                C.title
            FROM `gaz_category` AS C
            WHERE C.parent_id = ' . $param['parentId'] . '
            ORDER BY C.order_num ASC');

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $childHtml .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $param['space'] . $row->title . '</option>';
                $childHtml .= self::controlCategoryChildDropdown_model(array('selectedId' => $param['selectedId'], 'parentId' => $row->id, 'space' => '&nbsp; &nbsp; &nbsp; ' . $param['space']));
            }
        }
        return $childHtml;
    }

    public function controlContentDropdown_model($param = array('modId' => 0, 'catId' => 0, 'selectedId' => 0)) {
        $queryStringTable = '';
        $data = '<select name="moduleMenuContId" id="moduleMenuContId" class="form-control select2">';

        $data .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';

        if (!empty($param['modId']) and $param['menuTypeId'] == 5) {

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
                    $data .= '<option value="' . $rowTable->id . '" ' . ($param['selectedId'] == $rowTable->id ? 'selected="selected"' : '') . '>' . $rowTable->title . '</option>';
                }
            }
        }

        $data .= '</select>';

        return $data;
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                module_id,
                parent_id,
                menu_type_id,
                mod_id,
                cat_id,
                cont_id,
                title,
                order_num,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id,
                is_active,
                column_count,
                icon,
                menu_class,
                menu_css,
                partner_id
            FROM `gaz_module_menu`
            WHERE id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }
    
}
