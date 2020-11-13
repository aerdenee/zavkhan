<?php

class Slayout_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'title' => '',
            'layout' => '',
            'mod_id' => 0,
            'cat_id' => 0,
            'created_user_id' => '',
            'modified_user_id' => '',
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'order_num' => getOrderNum(array('table' => 'layout', 'field' => 'order_num')),
            'partner_id' => 0,
            'department_id' => 0,
            'is_active' => 1)));
    }

    public function editFormData_model($param = array('selectedId' => 0)) {

        $query = $this->db->query('
            SELECT 
                id,
                title,
                layout,
                mod_id,
                cat_id,
                created_user_id,
                modified_user_id,
                created_date,
                modified_date,
                order_num,
                partner_id,
                department_id,
                is_active
            FROM `gaz_layout`
            WHERE id = ' . $param['selectedId']);

        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return self::addFormData_model();
    }

    public function listsCount_model($param = array()) {
        $queryString = '';

        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and !$auth->your->read) {
            $queryString .= ' AND L.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND L.created_user_id = -1';
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND LOWER(L.title) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
        }

        $query = $this->db->query('
            SELECT 
                L.id
            FROM `gaz_layout` AS L
            WHERE L.lang_id = ' . $this->session->userdata['adminLangId'] . $queryString . '
            ORDER BY L.id DESC');

        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '')) {

        $data = array();
        $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and !$auth->your->read) {
            $queryString .= ' AND L.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND L.created_user_id = -1';
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND LOWER(L.title) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
        }

        $query = $this->db->query('
            SELECT 
                L.id,
                CONCAT(L.title, \' - \', L.layout) AS title,
                L.mod_id,
                L.cat_id,
                L.created_user_id,
                L.modified_user_id,
                DATE(L.modified_date) AS modified_date,
                L.order_num,
                L.partner_id,
                L.department_id,
                IF(L.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active
            FROM `gaz_layout` AS L
            WHERE L.lang_id = ' . $this->session->userdata['adminLangId'] . $queryString . '
            ORDER BY L.id DESC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);


        if ($query->num_rows() > 0) {
            $i = 0;
            foreach ($query->result() as $key => $row) {

                array_push($data, array(
                    'num' => ++$i,
                    'id' => $row->id,
                    'created_user_id' => $row->created_user_id,
                    'title' => $row->title,
                    'modified_date' => $row->modified_date,
                    'is_active' => $row->is_active
                ));
            }
        }
        return array('data' => $data, 'search' => self::searchKeywordView_model());
    }

    public function insert_model($param = array()) {
        $data = array(
            array(
                'id' => getUID('layout'),
                'title' => $this->input->post('title'),
                'layout' => $this->input->post('layout'),
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'created_user_id' => $this->session->userdata['adminUserId'],
                'modified_user_id' => $this->session->userdata['adminUserId'],
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'order_num' => getOrderNum(array('table' => 'layout', 'field' => 'order_num')),
                'lang_id' => $this->session->userdata['adminLangId'],
                'is_active' => $this->input->post('isActive')
            )
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'layout', $data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {
        $data = array(
            'title' => $this->input->post('title'),
            'layout' => $this->input->post('layout'),
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'modified_user_id' => $this->session->userdata['adminUserId'],
            'modified_date' => date('Y-m-d H:i:s'),
            'is_active' => $this->input->post('isActive'));

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'layout', $data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {
        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'layout')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function searchKeywordView_model($param = array('modId' => 0)) {
        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->string .= form_hidden('keyword', $this->input->get('keyword'));
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {

            $this->string .= ' <a href="javascript:;" onclick="_initLayout({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return ($this->showResetBtn ? '<div class="_search-result-inner"><form action="javascript:;" id="form-layout"> Хайлтын үр дүн: ' . $this->string . '</form></div>' : '');
    }

}
