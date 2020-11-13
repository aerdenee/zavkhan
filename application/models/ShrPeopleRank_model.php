<?php

class ShrPeopleRank_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Suser_model', 'user');
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => 62,
            'title' => '',
            'is_active' => 1,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => 0,
            'modified_user_id' => 0,
            'order_num' => getOrderNum(array('table' => 'hr_people_rank', 'field' => 'order_num'))
        )));
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                HPR.id,
                HPR.mod_id,
                HPR.title,
                HPR.is_active,
                HPR.created_date,
                HPR.modified_date,
                HPR.created_user_id,
                HPR.modified_user_id,
                HPR.order_num
            FROM `gaz_hr_people_rank` AS HPR
            WHERE HPR.id = ' . $param['id']);

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
            $queryString .= ' AND HPR.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND HPR.created_user_id = -1';
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND (LOWER(HPR.title) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        $query = $this->db->query('
            SELECT 
                HPR.id
            FROM `gaz_hr_people_rank` AS HPR 
            WHERE 1 = 1 ' . $queryString);

        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $data = array();
        $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and !$auth->your->read) {
            $queryString .= ' AND HPR.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND HPR.created_user_id = -1';
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND (LOWER(HPR.title) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        $query = $this->db->query('
            SELECT 
                HPR.id,
                HPR.mod_id,
                HPR.title,
                IF(HPR.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                HPR.created_date,
                HPR.modified_date,
                HPR.created_user_id,
                HPR.modified_user_id,
                HPR.order_num
            FROM `gaz_hr_people_rank` AS HPR
            WHERE 1 = 1 ' . $queryString . '
            ORDER BY HPR.order_num DESC
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
                    'is_active' => $row->is_active,
                    'order_num' => $row->order_num
                ));
            }
        }
        return array('data' => $data, 'search' => self::searchKeywordView_model());
    }

    public function insert_model() {
        $this->data = array(
            array(
                'id' => getUID('hr_people_rank'),
                'mod_id' => $this->input->post('modId'),
                'title' => $this->input->post('title'),
                'is_active' => $this->input->post('isActive'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => $this->session->adminUserId,
                'order_num' => $this->input->post('orderNum'))
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'hr_people_rank', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model() {

        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'title' => $this->input->post('title'),
            'is_active' => $this->input->post('isActive'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'order_num' => $this->input->post('orderNum'));
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'hr_people_rank', $this->data)) {
            return array('status' => 'success', 'message' => 'Өөрчлөлтийг амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Өөрчлөлтийн түүхийг хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {
        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'hr_people_rank')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function searchKeywordView_model() {
        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->string .= form_hidden('keyword', $this->input->get('keyword'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('catId')) {
            if ($this->input->get('catId') != 'all') {
                $this->cat = $this->category->getData_model(array('selectedId' => $this->input->get('catId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->cat->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('catId', $this->input->get('catId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {

            $this->string .= ' <a href="javascript:;" onclick="_initHrPeopleRank({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return ($this->showResetBtn ? '<div class="_search-result-inner"><form action="javascript:;" id="form-content"> Хайлтын үр дүн: ' . $this->string . '</form></div>' : '');
    }

    public function controlHrPeopleRankDropDown_model($param = array('parentId' => 0, 'selectedId' => 0, 'name' => '')) {
        $this->html = $this->string = '';

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
        $this->html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';

        $this->query = $this->db->query('
                SELECT 
                    HPR.id,
                    HPR.title
                FROM `gaz_hr_people_rank` AS HPR
                WHERE 
                    HPR.is_active = 1
                ORDER BY HPR.order_num ASC');

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
                HPR.id,
                HPR.mod_id,
                HPR.title,
                HPR.is_active,
                HPR.created_date,
                HPR.modified_date,
                HPR.created_user_id,
                HPR.modified_user_id,
                HPR.order_num
            FROM `gaz_hr_people_rank` AS HPR
            WHERE HPR.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

}
