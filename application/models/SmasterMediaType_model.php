<?php

class SmasterMediaType_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();

        $this->load->model('Slog_model', 'slog');
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => 90,
            'title' => '',
            'is_active' => 1,
            'order_num' => getOrderNum(array('table' => 'master_media_type', 'field' => 'order_num')),
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->userdata['adminUserId'],
            'modified_user_id' => 0,
            'lang_id' => $this->session->userdata['adminLangId']
        )));
    }

    public function editFormData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                MDT.id,
                MDT.mod_id,
                MDT.title,
                MDT.is_active,
                MDT.order_num,
                MDT.created_date,
                MDT.modified_date,
                MDT.created_user_id,
                MDT.modified_user_id
            FROM `gaz_master_doc_type` AS MDT
            WHERE MDT.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $data = array();
        $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and ! $auth->your->read) {
            $queryString .= ' AND MDT.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND MDT.created_user_id = -1';
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND LOWER(MDT.title) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
        }

        $query = $this->db->query('
            SELECT 
                MMT.id,
                MMT.mod_id,
                MMT.title,
                IF(MMT.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                MMT.order_num,
                DATE(MMT.created_date) AS created_date,
                MMT.created_user_id,
                MMT.modified_user_id
            FROM `gaz_master_media_type` AS MMT
            WHERE 1 = 1 ' . $queryString . '
            ORDER BY MDT.order_num DESC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);

        if ($query->num_rows() > 0) {

            $i = 0;
            foreach ($query->result() as $key => $row) {

                array_push($data, array(
                    'id' => $row->id,
                    'num' => ++$i,
                    'created_user_id' => $row->created_user_id,
                    'title' => $row->title,
                    'created_date' => $row->created_date,
                    'is_active' => $row->is_active
                ));
            }
        }

        return array('data' => $data, 'search' => self::searchKeywordView_model());
    }

    public function insert_model($param = array()) {

        $this->param = array();

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        $data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'title' => $this->input->post('title'),
                'is_active' => $this->input->post('isActive'),
                'order_num' => $this->input->post('orderNum'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0));

        if ($this->db->insert_batch($this->db->dbprefix . 'master_doc_type', $data)) {
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

        $this->data = array(
            'title' => $this->input->post('title'),
            'is_active' => $this->input->post('isActive'),
            'order_num' => $this->input->post('orderNum'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId
        );

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'master_doc_type', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {

        $this->db->where('id', $this->input->post('id'));
        $query = $this->db->get($this->db->dbprefix . 'master_doc_type');
        $row = $query->row();

        $this->slog->log_model(array(
            'modId' => $row->mod_id,
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_DELETE,
            'data' => json_encode($row)));

        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'master_doc_type')) {
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
            $this->string .= ' <a href="javascript:;" onclick="_initMasterDocType({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return ($this->showResetBtn ? '<div class="_search-result-inner"><form action="javascript:;" id="form-master-doc-type"> Хайлтын үр дүн: ' . $this->string . '</form></div>' : '');
    }

    public function controlMasterDocTypeListDropdown_model1($param = array('modId' => 0, 'selectedId' => 0, 'isRequired' => true, 'isDisabled' => true)) {
        $html = $string = $hiddenInput = '';

        if (isset($param['disabled']) and strtolower($param['disabled']) == 'true') {
            $string .= ' disabled="true"';
            $hiddenInput .= form_hidden('docTypeId', $param['selectedId']);
        }

        $query = $this->db->query('
            SELECT 
                id,
                title
            FROM `' . $this->db->dbprefix . 'master_doc_type`
            WHERE is_active = 1
            ORDER BY order_num ASC');

        $html .= '<select class="form-control select2" name="docTypeId" id="docTypeId" ' . (isset($param['isRequired']) ? 'required="required"' : '') . ' ' . $string . '>';
        $html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '>' . ' - Сонгох - </option>';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $row) {
                $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';
            }
        }
        $html .= '</select>';
        return $html . $hiddenInput;
    }

    public function controlMasterMediaTypeRadioButton_model($param = array('selectedId' => 0)) {

        $html = $string = $name = '';

        if (isset($param['disabled']) and $param['disabled'] == true) {
            $string .= ' disabled="true"';
        }

        if (isset($param['required']) and $param['required'] == true) {
            $string .= ' required="true"';
        }

        if (isset($param['readonly']) and $param['readonly'] == true) {
            $string .= ' disabled="true"';
        }
        
        if (!isset($param['name'])) {
            $param['name'] = 'masterMediaType';
        }

        $query = $this->db->query('
            SELECT 
                id,
                title
            FROM `' . $this->db->dbprefix . 'master_media_type`
            WHERE 1 = 1
            ORDER BY order_num ASC');

        

        if ($query->num_rows() > 0) {
            $html .= form_hidden($param['name'] . 'Id', $param['selectedId']);
            foreach ($query->result() as $key => $row) {
                $html .= '<div class="form-check form-check-inline">';
                $html .= '<label class="form-check-label">';
                $html .= form_radio(array('name' => $param['name'], 'class' => 'radio', 'onclick' => '_inlineSetMediaTypeValue({elem: this, val: ' . $row->id . '})'), $row->id, ($row->id == $param['selectedId'] ? TRUE : FALSE));
                $html .= $row->title;
                $html .= '</label>';
                $html .= '</div>';
            }
            
            $html .= '
                <script type="text/javascript">
                    function _inlineSetMediaTypeValue(param) {
                        $(\'input[name="' . $param['name'] . 'Id"]\').val(param.val);
                    }
                </script>';
        }

        return $html;
    }
    
    public function getData_model($param = array('selectedId' => 0)) {

        if ($param['selectedId'] != NULL and $param['selectedId'] != 0) {

            $query = $this->db->query('
            SELECT 
                id,
                title,
                order_num,
                is_active
            FROM `' . $this->db->dbprefix . 'master_media_type`
            WHERE id = ' . $param['selectedId']);

            if ($query->num_rows() > 0) {
                return $query->row();
            }
        }

        return false;
    }

}
