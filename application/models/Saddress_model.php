<?php

class Saddress_model extends CI_Model {

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
            'mod_id' => 25,
            'cat_id' => 349,
            'parent_id' => 12,
            'title' => '',
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => 0,
            'modified_user_id' => 0,
            'order_num' => getOrderNum(array('table' => 'address', 'field' => 'order_num')),
            'is_active' => 1
        )));
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                A.id,
                A.mod_id,
                A.cat_id,
                A.parent_id,
                A.title,
                A.created_date,
                A.modified_date,
                A.created_user_id,
                A.modified_user_id,
                A.order_num,
                A.is_active
            FROM `gaz_address` AS A
            WHERE A.id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function listsCount_model($param = array()) {
        $this->queryString = $this->getString = '';

        $this->auth = $param['auth'];

        if ($this->auth->our->read and $this->auth->your->read) {
            $this->queryString .= '';
        } elseif ($this->auth->our->read and ! $this->auth->your->read) {
            $this->queryString .= ' AND A.created_user_id = ' . $this->session->adminUserId;
        } else {
            $this->queryString .= ' AND A.created_user_id = -1';
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND A.cat_id = ' . $param['catId'];
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(A.title) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        } else {
            $this->queryString .= ' AND A.parent_id = 12';
        }

        $this->query = $this->db->query('
            SELECT 
                A.id
            FROM `gaz_address` AS A 
            WHERE 1 = 1 ' . $this->queryString);

        return $this->query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $this->queryString = $this->getString = '';

        $this->auth = $param['auth'];

        if ($this->auth->our->read and $this->auth->your->read) {
            $this->queryString .= '';
        } elseif ($this->auth->our->read and ! $this->auth->your->read) {
            $this->queryString .= ' AND A.created_user_id = ' . $this->session->adminUserId;
        } else {
            $this->queryString .= ' AND A.created_user_id = -1';
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND A.cat_id = ' . $param['catId'];
            $this->getString .= form_hidden('catId', $param['catId']);
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(A.title) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        } else {
            $this->queryString .= ' AND A.parent_id = 12';
        }

        $this->query = $this->db->query('
            SELECT 
                A.id,
                A.mod_id,
                A.cat_id,
                C.title AS cat_title,
                A.parent_id,
                A.title,
                A.created_date,
                A.modified_date,
                A.created_user_id,
                A.modified_user_id,
                A.order_num,
                A.is_active
            FROM `gaz_address` AS A
            LEFT JOIN `gaz_category` AS C ON A.cat_id = C.id
            WHERE 1 = 1 ' . $this->queryString . '
            ORDER BY A.title ASC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        $html = form_open('', array('class' => 'form-100 form-horizontal', 'id' => 'form-address-init', 'enctype' => 'multipart/form-data'));
        $html .= form_hidden('modId', $this->auth->modId);
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
        if ($this->auth->our->create) {

            $html .= '<td><a href="javascript:;" class="l-btn l-btn-small l-btn-plain" group="" id="" onclick="_addFormAddress({elem: this});"><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">Шинэ (F2)</span><span class="l-btn-icon dg-icon-add">&nbsp;</span></span></a></td>';
        } else {

            $html .= '<td><a href="javascript:;" class="l-btn l-btn-small l-btn-plain disabled" group="" id=""><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">Шинэ (F2)</span><span class="l-btn-icon dg-icon-add">&nbsp;</span></span></a></td>';
        }

        $html .= '<td><div class="datagrid-btn-separator"></div></td>';

        $html .= '<td><a href="javascript:;" class="l-btn l-btn-small l-btn-plain" onclick="_advensedSearchAddress({elem: this});"><span class="l-btn-left l-btn-icon-left"><span class="l-btn-text">Дэлгэрэнгүй хайлт (Ctrl+f)</span><span class="l-btn-icon dg-icon-search">&nbsp;</span></span></a></td>';

        $html .= '</tr></tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= self::searchKeywordView_model();
        $html .= '</div>';

        if ($this->query->num_rows() > 0) {

            $html .= '<div class="table-responsive">';
            $html .= '<table class="table">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th style="width:30px;">#</th>';
            $html .= '<th>Гарчиг</th>';
            $html .= '<th style="width:200px;">Ангилал</th>';
            $html .= '<th style="width:60px;">Төлөв</th>';
            $html .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody data-link="row" class="rowlink">';

            $i = 0;

            foreach ($this->query->result() as $key => $row) {
                $html .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '">';
                $html .= '<td>' . ++$i . '</td>';
                $html .= '<td class="context-menu-address-selected-row" style="padding-left:' . $param['space'] . 'px;">' . $row->title . ' (' . $row->id . ')</td>';
                $html .= '<td class="context-menu-address-selected-row">' . $row->cat_title . '</td>';
                $html .= '<td class="context-menu-address-selected-row text-center">' . ($row->is_active == 1 ? '<span class="label label-success"><i class="fa fa-check"></i></span>' : '<span class="label label-danger"><i class="fa fa-lock"></i></span>') . '</td>';
                $html .= '<td class="text-center">';
//                $html .= '<ul class="icons-list">';
//
//                if (($this->auth->our->update == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->update == 1 and $row->created_user_id != $this->session->adminUserId)) {
//                    $this->html .= '<li><a href="javascript:;" onclick="_editFormAddress({elem: this, id:' . $row->id . '});" title="Засах" data-action="edit"></a></li>';
//                } else {
//                    $this->html .= '<li><a href="javascript:;" class="disabled" title="Засах" data-action="edit"></a></li>';
//                }
//
//                if (($this->auth->our->update == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->update == 1 and $row->created_user_id != $this->session->adminUserId)) {
//                    $this->html .= '<li><a href="javascript:;" onclick="_removeAddress({elem: this, id:' . $row->id . '});" title="Устгах" data-action="delete"></a></li>';
//                } else {
//                    $this->html .= '<li><a href="javascript:;" class="disabled" title="Устгах" data-action="delete"></a></li>';
//                }
//
//                $this->html .= '</ul>';
                $html .= '</td>';
                $html .= '</tr>';

                $html .= self::listsChild_model(array('parentId' => $row->id, 'space' => 50, 'autoNumber' => $i, 'auth' => $param['auth']));
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
        $this->auth = $param['auth'];

        if ($this->auth->our->read == 1 and $this->auth->your->read == 1) {
            $this->queryString .= '';
        } else if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND C.created_user_id = ' . $this->session->adminUserId;
        } else if ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND C.created_user_id = -1';
        }

        $this->query = $this->db->query('
            SELECT 
                A.id,
                A.mod_id,
                A.cat_id,
                C.title AS cat_title,
                A.parent_id,
                A.title,
                A.created_date,
                A.modified_date,
                A.created_user_id,
                A.modified_user_id,
                A.order_num,
                A.is_active
            FROM `gaz_address` AS A
            LEFT JOIN `gaz_category` AS C ON A.cat_id = C.id
            WHERE A.parent_id = ' . $param['parentId'] . '
            ORDER BY A.title ASC');
        if ($this->query->num_rows() > 0) {

            $j = 0;
            foreach ($this->query->result() as $row) {
                $html .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '">';
                $html .= '<td>' . $param['autoNumber'] . '.' . ++$j . '</td>';
                $html .= '<td class="context-menu-address-selected-row" style="padding-left:' . $param['space'] . 'px;">' . $row->title . ' (' . $row->id . ')</td>';
                $html .= '<td class="context-menu-address-selected-row">' . $row->cat_title . '</td>';
                $html .= '<td class="context-menu-address-selected-row text-center">' . ($row->is_active == 1 ? '<span class="label label-success"><i class="fa fa-check"></i></span>' : '<span class="label label-danger"><i class="fa fa-lock"></i></span>') . '</td>';
                $html .= '<td class="text-center">';
//                $html .= '<ul class="icons-list">';
//
//                if (($this->auth->our->update == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->update == 1 and $row->created_user_id != $this->session->adminUserId)) {
//                    $html .= '<li><a href="javascript:;" onclick="_editFormAddress({elem: this, id:' . $row->id . '});" title="Засах" data-action="edit"></a></li>';
//                } else {
//                    $html .= '<li><a href="javascript:;" class="disabled" title="Засах" data-action="edit"></a></li>';
//                }
//
//                if (($this->auth->our->update == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->update == 1 and $row->created_user_id != $this->session->adminUserId)) {
//                    $html .= '<li><a href="javascript:;" onclick="_removeAddress({elem: this, id:' . $row->id . '});" title="Устгах" data-action="delete"></a></li>';
//                } else {
//                    $html .= '<li><a href="javascript:;" class="disabled" title="Устгах" data-action="delete"></a></li>';
//                }
//
//                $html .= '</ul>';
                $html .= '</td>';
                $html .= '</tr>';
                $html .= self::listsChild_model(array('parentId' => $row->id, 'space' => (intval($param['space']) + 30), 'autoNumber' => $param['autoNumber'] . '.' . $j, 'auth' => $param['auth']));
            }
        }
        return $html;
    }

    public function insert_model() {
        $this->data = array(
            array(
                'id' => getUID('address'),
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'parent_id' => $this->input->post('parentId'),
                'title' => $this->input->post('title'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => '0000-00-00 00:00:00',
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'order_num' => $this->input->post('orderNum'),
                'is_active' => $this->input->post('isActive')
            )
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'address', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model() {

        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'parent_id' => $this->input->post('parentId'),
            'title' => $this->input->post('title'),
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'order_num' => $this->input->post('orderNum'),
            'is_active' => $this->input->post('isActive')
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'address', $this->data)) {
            return array('status' => 'success', 'message' => 'Өөрчлөлтийг амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Өөрчлөлтийн түүхийг хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {
        
        $idArray = $this->getChildAddress_model($this->input->post('id'));

        $this->query = $this->db->query('
            SELECT 
                A.*
            FROM `gaz_address` AS A 
            WHERE A.id IN (' . $idArray . ')');

        if ($this->query->num_rows() > 0) {
            foreach ($this->query->result() as $keyDelete => $rowDelete) {

                $this->slog->log_model(array(
                    'modId' => $rowDelete->mod_id,
                    'createdUserId' => $this->session->adminUserId,
                    'type' => LOG_TYPE_DELETE,
                    'data' => json_encode($rowDelete)));

            }
            $this->db->where_in('id', explode(',', $idArray));
            if ($this->db->delete($this->db->dbprefix . 'address')) {
                return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
            }
        }

        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
        
        
        
        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'address')) {
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

        if ($this->input->get('partnerId')) {
            $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->get('partnerId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->partnerData->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            //' . Scrime::$path . $param['path'] . '/' . $param['modId'] . '
            $this->string .= ' <a href="javascript:;" onclick="_initAddress({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return $this->string;
    }

    public function controlAddressDropDown_model($param = array('parentId' => 0, 'selectedId' => 0, 'name' => '')) {
        $html = $string = $hiddenInput = '';

        if (isset($param['disabled']) and strtolower($param['disabled']) == 'true') {
            $string .= ' disabled="true"';
            $hiddenInput .= form_hidden($param['name'], $param['selectedId']);
        }

        if (isset($param['required']) and strtolower($param['required']) == 'true') {
            $string .= ' required="true"';
        }

        if (isset($param['readonly']) and strtolower($param['readonly']) == 'true') {
            $string .= ' disabled="true"';
            $hiddenInput .= form_hidden($param['name'], $param['selectedId']);
        }

        $html .= '<select name="' . $param['name'] . '" id="' . $param['name'] . '" class="select2" ' . $string . '>';
        $html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';

        $query = $this->db->query('
                SELECT 
                    id,
                    title
                FROM `gaz_address`
                WHERE 
                    is_active = 1 AND parent_id = ' . $param['parentId'] . '
                ORDER BY order_num ASC');

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';
            }
        }

        $html .= '</select>';

        return $html . $hiddenInput;
    }

    public function getData_model($param = array('selectedId' => 0)) {

        if (isset($param['selectedId']) and $param['selectedId'] > 0) {
            $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cat_id,
                title,
                parent_id,
                created_date,
                modified_date,
                is_active,
                order_num,
                created_user_id,
                modified_user_id
            FROM `gaz_address`
            WHERE id = ' . $param['selectedId']);

            if ($this->query->num_rows() > 0) {
                return $this->query->row();
            }
        }

        return false;
    }

    public function controlAddressParentMultiRowDropdown_model($param = array('modId' => 0, 'selectedId' => 0, 'id' => 0, 'parentId' => 0, 'space' => '')) {

        $html = '';
        $this->query = $this->db->query('
            SELECT 
                A.id,
                A.title
            FROM `gaz_address` AS A
            WHERE A.parent_id = 0 AND A.is_active = 1 AND id != ' . $param['id'] . '
            ORDER BY A.title ASC');

        if ($this->query->num_rows() > 0) {

            $html .= '<select class="form-control" name="parentId" id="parentId" size="10" required="required">';

            $html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '>' . ' - Сонгох - </option>';


            foreach ($this->query->result() as $row) {

                $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $param['space'] . $row->title . '</option>';

                $html .= self::controlAddressParentMultiChildRowDropdown_model(array('selectedId' => $param['selectedId'], 'id' => $param['id'], 'parentId' => $row->id, 'space' => '&nbsp; &nbsp;  &nbsp;  &nbsp; ' . $param['space']));
            }

            $html .= '</select>';
        }

        return $html;
    }

    public function controlAddressParentMultiChildRowDropdown_model($param = array('selectedId' => 0, 'id' => 0, 'parentId' => 0, 'space' => '')) {

        $html = '';
        $this->query = $this->db->query('
            SELECT 
                A.id,
                A.title
            FROM `gaz_address` AS A
            WHERE A.parent_id = ' . $param['parentId'] . ' AND A.is_active = 1 AND id != ' . $param['id'] . '
            ORDER BY A.title ASC');

        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $row) {

                $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $param['space'] . $row->title . '</option>';

                $html .= self::controlAddressParentMultiChildRowDropdown_model(array('selectedId' => $param['selectedId'], 'id' => $param['id'], 'parentId' => $row->id, 'space' => '&nbsp; &nbsp;  &nbsp;  &nbsp; ' . $param['space']));
            }
        }

        return $html;
    }

    public function getChildAddress_model($param = array()) {

        $data = '';

        if ($param != '') {


            $param = explode(',', $param);

            if (is_array($param) and count($param) > 0) {

                foreach ($param as $partnerKey => $partnerId) {

                    $this->query = $this->db->query('
                        SELECT 
                            A.id
                        FROM `' . $this->db->dbprefix . 'address` AS A 
                        WHERE A.parent_id = ' . $partnerId);

                    if ($this->query->num_rows() > 0) {

                        foreach ($this->query->result() as $key => $row) {

                            $data .= $row->id . ',';

                            $data .= $this->_getChildAddress_model($row->id);
                        }

                        $data .= $partnerId . ',';
                    } else {

                        $data .= $partnerId . ',';
                    }
                }
            }

            return rtrim($data, ',');
        }

        return 0;
    }

    public function _getChildAddress_model($param = array()) {

        $data = '';

        if ($param != '') {


            $param = explode(',', $param);

            if (is_array($param) and count($param) > 0) {

                foreach ($param as $partnerKey => $partnerId) {

                    $this->query = $this->db->query('
                        SELECT 
                            A.id
                        FROM `' . $this->db->dbprefix . 'address` AS A 
                        WHERE A.parent_id = ' . $partnerId);

                    if ($this->query->num_rows() > 0) {

                        foreach ($this->query->result() as $key => $row) {

                            $data .= $row->id . ',';

                            $data .= $this->_getChildAddress_model($row->id);
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

}
