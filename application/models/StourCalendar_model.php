<?php

class StourCalendar_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Simage_model', 'simage');
        $this->load->model('Sfile_model', 'sfile');
        $this->load->model('Slog_model', 'slog');
    }

    public function addFormData_model($param = array('contId' => 0, 'modId' => 0)) {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => $param['modId'],
            'cont_id' => $param['contId'],
            'in_date' => date('Y-m-d'),
            'out_date' => date('Y-m-d'),
            'price' => '',
            'intro_text' => '',
            'is_active' => 1,
            'order_num' => getOrderNum(array('table' => 'content_media', 'field' => 'order_num')),
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s'),
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'lang_id' => 1
        )));
    }

    public function editFormData_model($param = array('contId' => 0, 'modId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                CM.id,
                CM.mod_id,
                CM.cont_id,
                DATE(CM.in_date) AS in_date,
                DATE(CM.out_date) AS out_date,
                CM.price,
                CM.intro_text,
                CM.is_active,
                CM.order_num,
                CM.created_date,
                CM.modified_date,
                CM.created_user_id,
                CM.modified_user_id,
                CM.lang_id
            FROM `gaz_tour_calendar` AS CM
            WHERE CM.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model(array('contId' => $param['contId'], 'modId' => $param['modId']));
    }

    public function listsCount_model($param = array()) {

        $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and ! $auth->your->read) {
            $queryString .= ' AND CM.created_user_id = ' . $this->session->userdata['adminUserId'];
        } else if (!$auth->our->read and ! $auth->your->read) {
            $queryString .= ' AND CM.created_user_id = -1';
        }

        if ($param['partnerId'] != 0) {
            $queryString .= ' AND CM.partner_id = ' . $param['partnerId'];
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND CM.cat_id = ' . $param['catId'];
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND (LOWER(CM.intro_text) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(CM.full_text) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {
            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(CM.is_active_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(CM.is_active_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {
            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(CM.is_active_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {
            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(CM.is_active_date)';
        }

        $query = $this->db->query('
            SELECT 
                CM.id
            FROM `gaz_tour_calendar` AS CM 
            WHERE CM.lang_id = ' . $this->session->userdata['adminLangId'] . $queryString . ' AND CM.cont_id = ' . $param['contId'] . ' AND CM.mod_id = ' . $param['modId'] . ' 
            ORDER BY CM.id DESC');

        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $data = array();

        $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and ! $auth->your->read) {
            $queryString .= ' AND CM.created_user_id = ' . $this->session->userdata['adminUserId'];
        } else if (!$auth->our->read and ! $auth->your->read) {
            $queryString .= ' AND CM.created_user_id = -1';
        }

        if ($param['partnerId'] != 0) {
            $queryString .= ' AND CM.partner_id = ' . $param['partnerId'];
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND CM.cat_id = ' . $param['catId'];
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND (LOWER(CM.intro_text) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(CM.full_text) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {
            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(CM.is_active_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(CM.is_active_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {
            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(CM.is_active_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {
            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(CM.is_active_date)';
        }

        $query = $this->db->query('
            SELECT 
                CM.id,
                CM.mod_id,
                CM.cont_id,
                DATE(CM.in_date) AS in_date,
                DATE(CM.out_date) AS out_date,
                CONCAT(DATE(CM.in_date), \' - \', DATE(CM.out_date)) AS in_out_date,
                CM.price,
                CM.intro_text,
                IF(CM.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                CM.order_num,
                DATE(CM.created_date) AS created_date,
                DATE(CM.modified_date) AS modified_date,
                CM.created_user_id,
                CM.modified_user_id,
                CM.lang_id
            FROM `gaz_tour_calendar` AS CM
            WHERE CM.lang_id = ' . $this->session->userdata['adminLangId'] . $queryString . ' AND CM.cont_id = ' . $param['contId'] . ' AND CM.mod_id = ' . $param['modId'] . '
            ORDER BY CM.order_num ASC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);


        if ($query->num_rows() > 0) {

            $i = 0;
            foreach ($query->result() as $key => $row) {

                array_push($data, array(
                    'id' => $row->id,
                    'number' => ++$i,
                    'created_user_id' => $row->created_user_id,
                    'title' => $row->in_out_date,
                    'price' => $row->price,
                    'intro_text' => $row->intro_text,
                    'created_date' => $row->created_date,
                    'is_active' => $row->is_active
                ));
            }
        }

        return $data;
    }

    public function insert_model($param = array()) {

        $this->param = array();

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        $inDate = explode('-', $this->input->post('inDate'));
        $data = array(
            array(
                'id' => getUID('tour_calendar'),
                'mod_id' => $this->input->post('modId'),
                'cont_id' => $this->input->post('contId'),
                'in_year' => $inDate['0'],
                'in_month' => $inDate['1'],
                'in_date' => $this->input->post('inDate'),
                'out_date' => $this->input->post('outDate'),
                'price' => $this->input->post('price'),
                'intro_text' => $this->input->post('introText'),
                'is_active' => $this->input->post('isActive'),
                'order_num' => getOrderNum(array('table' => 'tour_calendar', 'field' => 'order_num')),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'lang_id' => $this->session->adminLangId
            )
        );
        if ($this->db->insert_batch($this->db->dbprefix . 'tour_calendar', $data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'type' => LOG_TYPE_UPDATE,
            'data' => json_encode($_POST)));

        $inDate = explode('-', $this->input->post('inDate'));
        
        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cont_id' => $this->input->post('contId'),
            'in_year' => $inDate['0'],
            'in_month' => $inDate['1'],
            'in_date' => $this->input->post('inDate'),
            'out_date' => $this->input->post('outDate'),
            'price' => $this->input->post('price'),
            'intro_text' => $this->input->post('introText'),
            'is_active' => $this->input->post('isActive'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'lang_id' => $this->session->adminLangId);

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'tour_calendar', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model($param = array('uploadPath' => '',)) {

        $this->query = $this->db->query('
            SELECT 
                CM.*
            FROM `gaz_tour_calendar` AS CM
            WHERE 1 = 1 AND CM.id = ' . $param['selectedId']);
        $row = $this->query->row();

        $this->slog->log_model(array(
            'modId' => $row->mod_id,
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_DELETE,
            'data' => json_encode($row)));

        $this->db->where('id', $param['selectedId']);
        if ($this->db->delete($this->db->dbprefix . 'tour_calendar')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function getListData_model($param = array('modId' => 0, 'contId' => 0)) {


        $queryString = '';

        $query = $this->db->query('
            SELECT 
                CM.id,
                CM.mod_id,
                CM.cont_id,
                DATE(CM.in_date) AS in_date,
                DATE(CM.out_date) AS out_date,
                CONCAT(DATE(CM.in_date), \' - \', DATE(CM.out_date)) AS in_out_date,
                CM.price,
                CM.intro_text,
                IF(CM.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                CM.order_num,
                DATE(CM.created_date) AS created_date,
                DATE(CM.modified_date) AS modified_date,
                CM.media_type_id,
                CM.created_user_id,
                CM.modified_user_id,
                CM.lang_id
            FROM `gaz_tour_calendar` AS CM
            WHERE 1 = 1 ' . $queryString . ' AND CM.cont_id = ' . $param['contId'] . ' AND CM.mod_id = ' . $param['modId'] . ' 
            ORDER BY CM.order_num DESC');


        if ($query->num_rows() > 0) {

            return $query->result();
        }
        return false;
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $query = $this->db->query('
            SELECT 
                CM.id,
                CM.mod_id,
                CM.cont_id,
                DATE(CM.in_date) AS in_date,
                DATE(CM.out_date) AS out_date,
                CONCAT(DATE(CM.in_date), \' - \', DATE(CM.out_date)) AS in_out_date,
                CM.intro_text,
                CM.price,
                IF(CM.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                CM.order_num,
                DATE(CM.created_date) AS created_date,
                DATE(CM.modified_date) AS modified_date,
                CM.media_type_id,
                CM.created_user_id,
                CM.modified_user_id,
                CM.lang_id
            FROM `gaz_tour_calendar` AS CM
            WHERE CM.id = ' . $param['selectedId']);

        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return self::addFormData_model();
    }

}
