<?php

class SnifsCloseType_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Suser_model', 'user');
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => 74,
            'cat_id' => 0,
            'parent_id' => 0,
            'title' => '',
            'order_num' => getOrderNum(array('table' => 'nifs_close_type', 'field' => 'order_num')),
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
            FROM `gaz_nifs_close_type` AS NCT
            WHERE NCT.id = ' . $param['id']);

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
        } elseif ($auth->our->read and ! $auth->your->read) {
            $queryString .= ' AND NCT.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NCT.created_user_id = -1';
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND NCT.cat_id = ' . $param['catId'];
        }


        if ($param['keyword'] != '') {

            $queryString .= ' AND LOWER(NCT.title) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
        }

        $query = $this->db->query('
            SELECT 
                NCT.id
            FROM `gaz_nifs_close_type` AS NCT
            WHERE NCT.parent_id = 0 ' . $queryString);

        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $queryString = '';

        $auth = $param['auth'];
        
        $data = array();

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } elseif ($auth->our->read and ! $auth->your->read) {
            $queryString .= ' AND NCT.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NCT.created_user_id = -1';
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND NCT.cat_id = ' . $param['catId'];
        }


        if ($param['keyword'] != '') {

            $queryString .= ' AND LOWER(NCT.title) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
        }

        $query = $this->db->query('
            SELECT 
                NCT.id,
                NCT.mod_id,
                NCT.cat_id,
                C.title AS cat_title,
                NCT.parent_id,
                NCT.title,
                NCT.order_num,
                IF(NCT.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                DATE(NCT.created_date) AS created_date,
                NCT.modified_date,
                NCT.created_user_id,
                NCT.modified_user_id
            FROM `gaz_nifs_close_type` AS NCT
            LEFT JOIN `gaz_category` AS C ON NCT.cat_id = C.id
            WHERE NCT.parent_id = 0 ' . $queryString . '
            ORDER BY NCT.order_num DESC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);

        if ($query->num_rows() > 0) {

            $i = 1;
            foreach ($query->result() as $key => $row) {

                array_push($data, array(
                    'id' => $row->id,
                    'mod_id' => $row->mod_id,
                    'create_number' => $i++,
                    'created_date' => $row->created_date,
                    'title' => $row->title,
                    'cat_title' => $row->cat_title,
                    'order_num' => $row->order_num,
                    'is_active' => $row->is_active,
                    'created_user_id' => $row->created_user_id
                ));
            }
        }
        return array('data' => $data, 'search' => self::searchKeywordView_model());
    }

    public function insert_model($param = array('getUID' => 0)) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));
        
        $this->data = array(
            array(
                'id' => getUID('nifs_close_type'),
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

        if ($this->db->insert_batch($this->db->dbprefix . 'nifs_close_type', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array('pic' => '')) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'type' => LOG_TYPE_UPDATE,
            'data' => json_encode($_POST)));
        
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

        if ($this->db->update($this->db->dbprefix . 'nifs_close_type', $this->data)) {
            return array('status' => 'success', 'message' => 'Өөрчлөлтийг амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Өөрчлөлтийн түүхийг хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {

        $row = $this->getData_model(array('selectedId' => $this->input->post('id')));
        $this->slog->log_model(array(
            'modId' => $row->mod_id,
            'type' => LOG_TYPE_DELETE,
            'data' => json_encode($row)));

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->delete($this->db->dbprefix . 'nifs_close_type')) {
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
            $this->string .= ' <a href="javascript:;" onclick="_initNifsCloseType({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return $this->string;
    }

    public function controlNifsCloseTypeDropdown_model($param = array('catId' => 0, 'selectedId' => 0)) {

        $this->queryString = $this->html = $this->string = $this->class = $name = '';

        if (isset($param['catId']) and $param['catId'] != '') {
            $this->queryString .= ' AND NCT.cat_id = ' . $param['catId'];
        }

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
        
        if (isset($param['name'])) {
            $name = $param['name'];
        } else {
            $name = 'closeTypeId';
        }
        
        $this->query = $this->db->query('
            SELECT 
                NCT.id,
                NCT.title
            FROM `gaz_nifs_close_type` AS NCT
            WHERE NCT.parent_id = 0 AND NCT.is_active = 1 ' . $this->queryString . '
            ORDER BY NCT.order_num ASC');

        $this->html .= '<select name="' . $name . '" id="' . $name . '" class="select2" ' . $this->string . '>';
        $this->html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';
        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $key => $row) {
                $this->html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';
            }
            
        }

        $this->html .= '</select>';

        return $this->html;
    }

    public function getData_model($param = array('selectedId' => 0)) {

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
            FROM `gaz_nifs_close_type` AS NCT
            WHERE NCT.id = ' . $param['selectedId']);


        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

}