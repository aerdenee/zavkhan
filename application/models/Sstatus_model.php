<?php

class Sstatus_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();

        $this->load->model('Simage_model', 'simage');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('Slog_model', 'slog');

        $this->isActiveDepartment = 'is_active_control';
        $this->moduleId = 87;
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'module_id' => $this->moduleId,
            'mod_id' => 0,
            'cat_id' => 0,
            'title' => '',
            'is_active' => 1,
            'order_num' => getOrderNum(array('table' => 'status', 'field' => 'order_num')),
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0
        )));
    }

    public function editFormData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                S.id,
                S.module_id,
                S.mod_id,
                S.cat_id,
                S.title,
                S.is_active,
                S.order_num,
                S.created_date,
                S.modified_date,
                S.created_user_id,
                S.modified_user_id
            FROM `gaz_status` AS S
            WHERE S.id = ' . $param['selectedId']);

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
            $queryString .= ' AND S.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND S.created_user_id = -1';
        }


        if ($param['keyword'] != '') {
            $queryString .= ' AND LOWER(S.title) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
        }

        if ($param['modId'] != 0) {
            $queryString .= ' AND S.mod_id=' . $param['modId'];
        }

        $query = $this->db->query('
            SELECT 
                S.id
            FROM `gaz_status` AS S 
            WHERE 1 = 1 ' . $queryString);

        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $data = array();
        $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and !$auth->your->read) {
            $queryString .= ' AND S.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND S.created_user_id = -1';
        }


        if ($param['keyword'] != '') {
            $queryString .= ' AND LOWER(S.title) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
        }

        if ($param['modId'] != 0) {
            $queryString .= ' AND S.mod_id=' . $param['modId'];
        }
        
        $query = $this->db->query('
            SELECT 
                S.id,
                S.module_id,
                S.mod_id,
                M.title AS module_title,
                S.cat_id,
                S.title,
                IF(S.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                S.order_num,
                DATE_FORMAT(S.modified_date, \'%Y-%m-%d\') AS modified_date,
                S.created_user_id,
                S.modified_user_id
            FROM `gaz_status` AS S
            INNER JOIN `gaz_module` AS M ON S.mod_id = M.id
            WHERE 1 = 1 ' . $queryString . '
            ORDER BY S.order_num DESC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);

        if ($query->num_rows() > 0) {
            
            $i = 0;
            foreach ($query->result() as $key => $row) {

                array_push($data, array(
                    'id' => $row->id,
                    'num' => ++$i,
                    'created_user_id' => $row->created_user_id,
                    'title' => $row->title,
                    'module_title' => $row->module_title,
                    'modified_date' => $row->modified_date,
                    'is_active' => $row->is_active
                ));
            }
        }
        
        return array('data' => $data, 'search' => self::searchKeywordView_model());
    }

    public function insert_model($param = array()) {

        $this->param = array();

        $this->slog->log_model(array(
            'modId' => $this->input->post('moduleId'),
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        $data = array(
            array(
                'id' => $param['getUID'],
                'module_id' => $this->input->post('moduleId'),
                'mod_id' => $this->input->post('modId'),
                'title' => $this->input->post('title'),
                'is_active' => $this->input->post('isActive'),
                'order_num' => $this->input->post('orderNum'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0)
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'status', $data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('moduleId'),
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_UPDATE,
            'data' => json_encode($_POST)));

        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'title' => $this->input->post('title'),
            'is_active' => $this->input->post('isActive'),
            'order_num' => $this->input->post('orderNum'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId
        );

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'status', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {

        $this->db->where('id', $this->input->post('id'));
        $query = $this->db->get($this->db->dbprefix . 'status');
        $row = $query->row();

        $this->slog->log_model(array(
            'modId' => $row->mod_id,
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_DELETE,
            'data' => json_encode($row)));

        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'status')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function searchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('statusId') != 0 and $this->input->get('statusId') != '') {
            $statusData = $this->status->getData_model(array('selectedId' => $this->input->get('statusId')));
            $this->string .= '<span class="label label-default label-rounded">Төлөв: ' . $statusData->title . '</span>';
            $this->string .= form_hidden('statusId', $this->input->get('statusId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->string .= form_hidden('keyword', $this->input->get('keyword'));
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            $this->string .= ' <a href="javascript:;" onclick="_initStatus({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return ($this->showResetBtn ? '<div class="_search-result-inner"><form action="javascript:;" id="form-status"> Хайлтын үр дүн: ' . $this->string . '</form></div>' : '');
    }

    public function controlStatusListDropdown_model($param = array('modId' => 0, 'selectedId' => 0, 'isRequired' => true, 'isDisabled' => true)) {
        $html = $string = $hiddenInput = '';
        
        if (isset($param['disabled']) and strtolower($param['disabled']) == 'true') {
            $string .= ' disabled="true"';
            $hiddenInput .= form_hidden('statusId', $param['selectedId']);
        }
        
        $query = $this->db->query('
            SELECT 
                id,
                title
            FROM `' . $this->db->dbprefix . 'status`
            WHERE mod_id = ' . $param['modId'] . ' AND is_active = 1
            ORDER BY order_num ASC');

        $html .= '<select class="form-control select2" name="statusId" id="statusId" ' . (isset($param['isRequired']) ? 'required="required"' : '') . ' ' . $string . '>';
        $html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '>' . ' - Сонгох - </option>';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $row) {
                $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';
            }
        }
        $html .= '</select>';
        return $html . $hiddenInput;
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cat_id,
                title,
                is_active,
                order_num,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id
            FROM `' . $this->db->dbprefix . 'status`
            WHERE id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

}
