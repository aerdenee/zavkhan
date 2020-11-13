<?php

class ScontentGmap_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
    }

    public function addFormData_model($param = array()) {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => $param['modId'],
            'cat_id' => 0,
            'cont_id' => $param['contId'],
            'address' => '',
            'is_active' => 1,
            'map_type_id' => 1,
            'param' => '',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => date('Y-m-d H:i:s'),
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s'),
            'lang_id' => $this->session->adminLangId,
            'draw_mode' => 'marker',
            'lat' => DEFAULT_LATITUDE,
            'lng' => DEFAULT_LONGITUDE
        )));
    }

    public function editFormData_model($param = array()) {
        $this->query = $this->db->query('
            SELECT 
                M.id,
                M.mod_id,
                M.cat_id,
                M.cont_id,
                M.address,
                M.is_active,
                M.map_type_id,
                M.param,
                M.created_user_id,
                M.modified_user_id,
                M.created_date,
                M.modified_date,
                M.created_date,
                M.modified_date,
                M.lang_id,
                M.draw_mode,
                M.lat,
                M.lng
            FROM `gaz_map` AS M
            WHERE M.id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $data = array();
        $queryString = '';
        $auth = $param['auth'];

        if ($this->auth->our->read and $this->auth->your->read) {
            $queryString .= '';
        } else if ($this->auth->our->read and !$this->auth->your->read) {
            $queryString .= ' AND CM.created_user_id = ' . $this->session->userdata['adminUserId'];
        } else if (!$this->auth->our->read and !$this->auth->your->read) {
            $queryString .= ' AND CM.created_user_id = -1';
        }

        $query = $this->db->query('
            SELECT 
                CM.id,
                CM.mod_id,
                CM.cont_id,
                CM.address,
                IF(CM.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                CM.map_type_id,
                CM.param,
                CM.created_user_id,
                DATE(CM.created_date) AS created_date,
                CONCAT(CM.draw_mode, \', lat: \', CM.lat, \' lng: \', CM.lng) AS coordinate
            FROM `gaz_map` AS CM
            WHERE CM.lang_id = ' . $this->session->adminLangId . ' AND CM.mod_id = ' . $param['modId'] . ' AND CM.cont_id = ' . $param['contId'] . ' 
            ORDER BY CM.created_date DESC');

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $row) {

                array_push($data, array(
                    'id' => $row->id,
                    'created_user_id' => $row->created_user_id,
                    'coordinate' => $row->coordinate,
                    'address' => $row->address,
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
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        $data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'cont_id' => $this->input->post('contId'),
                'address' => $this->input->post('address'),
                'is_active' => $this->input->post('isActive'),
                'map_type_id' => $this->input->post('mapTypeId'),
                'param' => '',
                'modified_user_id' => $this->session->adminUserId,
                'modified_date' => date('Y-m-d H:i:s'),
                'lang_id' => $this->session->adminLangId,
                'draw_mode' => $this->input->post('drawMode'),
                'lat' => $this->input->post('lat'),
                'lng' => $this->input->post('lng')));

        if ($this->db->insert_batch($this->db->dbprefix . 'map', $data)) {
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
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'cont_id' => $this->input->post('contId'),
            'address' => $this->input->post('address'),
            'is_active' => $this->input->post('isActive'),
            'map_type_id' => $this->input->post('mapTypeId'),
            'param' => '',
            'created_user_id' => $this->session->adminUserId,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s'),
            'lang_id' => $this->session->adminLangId,
            'draw_mode' => $this->input->post('drawMode'),
            'lat' => $this->input->post('lat'),
            'lng' => $this->input->post('lng')
        );

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'map', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {

        $this->db->where('id', $this->input->post('id'));
        $query = $this->db->get($this->db->dbprefix . 'map');
        $row = $query->row();

        $this->slog->log_model(array(
            'modId' => $row->mod_id,
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_DELETE,
            'data' => json_encode($row)));

        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'map')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function getData_model($param = array()) {

        $this->query = $this->db->query('
            SELECT 
                M.id,
                M.mod_id,
                M.cat_id,
                M.cont_id,
                M.address,
                M.is_active,
                M.map_type_id,
                M.param,
                M.created_user_id,
                M.modified_user_id,
                M.created_date,
                M.modified_date,
                M.draw_mode,
                M.lat,
                M.lng
            FROM `gaz_map` AS M
            WHERE M.lang_id = ' . $param['lang'] . ' AND M.mod_id = ' . $param['modId'] . ' AND M.cont_id = ' . $param['contId']);


        if ($this->query->num_rows() > 0) {

            return $this->query->result();
        }
        return false;
    }

}
