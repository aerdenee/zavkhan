<?php

class SnifsPreCrime_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Suser_model', 'user');
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => 0,
            'cont_id' => 0,
            'create_number' => '',
            'expert_id' => 0,
            'department_id' => 1,
            'crime_value' => '',
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0
        )));
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                NPC.id,
                NPC.mod_id,
                NPC.cont_id,
                NPC.create_number,
                NPC.expert_id,
                NPC.department_id,
                NPC.crime_value,
                NPC.created_date,
                NPC.modified_date,
                NPC.created_user_id,
                NPC.modified_user_id
            FROM `gaz_nifs_pre_crime` AS NPC
            WHERE NW.id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $html = '<span class="label label-default label-rounded">test data</span> <span class="label label-default label-rounded">test data</span>';
        $html .= '<span class="label label-default label-rounded">test data</span> <span class="label label-default label-rounded">test data</span>';
        $html .= '<span class="label label-default label-rounded">test data</span> <span class="label label-default label-rounded">test data</span>';
        $html .= '<span class="label label-default label-rounded">test data</span> <span class="label label-default label-rounded">test data</span>';
        
        $query = $this->db->query('
            SELECT 
                NPC.id,
                NPC.mod_id,
                NPC.cont_id,
                NPC.create_number,
                NPC.expert_id,
                NPC.department_id,
                NPC.crime_value,
                NPC.created_date,
                NPC.modified_date,
                NPC.created_user_id,
                NPC.modified_user_id
            FROM `gaz_nifs_pre_crime` AS NPC
            WHERE NPC.mod_id = ' . $param['modId'] . ' AND NPC.cont_id = ' . $param['contId']);


        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $html .= '<span class="label label-default label-rounded">' . $row->create_number . ' - ' . $row->expert_id . ', ' . $row->about . '</span>';
            }
        }

        return $html;
    }

    public function insert_model($param = array('getUID' => 0)) {

        $this->data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cont_id' => $this->input->post('contId'),
                'create_number' => $this->input->post('createNumber'),
                'expert_id' => $this->input->post('expertId'),
                'department_id' => 1,
                'crime_value' => $this->input->post('crimeValue'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0));

        if ($this->db->insert_batch($this->db->dbprefix . 'nifs_pre_crime', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array('pic' => '')) {

        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cont_id' => $this->input->post('contId'),
            'create_number' => $this->input->post('createNumber'),
            'expert_id' => $this->input->post('expertId'),
            'department_id' => 1,
            'crime_value' => $this->input->post('crimeValue'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'nifs_pre_crime', $this->data)) {
            return array('status' => 'success', 'message' => 'Өөрчлөлтийг амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Өөрчлөлтийн түүхийг хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {

        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'nifs_pre_crime')) {
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
            $this->string .= ' <a href="javascript:;" onclick="_initNifsCrimeType({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return $this->string;
    }

    public function controlNifsWhereDropdown_model($param = array('catId' => 0, 'selectedId' => 0)) {

        $this->queryString = $this->html = $this->string = $this->class = $name = '';

        if (isset($param['catId']) and $param['catId'] != '') {
            $this->queryString .= ' AND NW.cat_id = ' . $param['catId'];
        }

        if (isset($param['name'])) {
            $name = $param['name'];
        } else {
            $name = 'whereId';
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

        $this->query = $this->db->query('
            SELECT 
                NW.id,
                NW.title
            FROM `gaz_nifs_where` AS NW
            WHERE 1 = 1 AND NW.is_active = 1 ' . $this->queryString . '
            ORDER BY NW.order_num ASC');

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
                NPC.id,
                NPC.mod_id,
                NPC.cont_id,
                NPC.create_number,
                NPC.expert_id,
                NPC.department_id,
                NPC.about,
                NPC.created_date,
                NPC.modified_date,
                NPC.created_user_id,
                NPC.modified_user_id
            FROM `gaz_nifs_pre_crime` AS NPC
            WHERE NW.id = ' . $param['selectedId']);


        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

}
