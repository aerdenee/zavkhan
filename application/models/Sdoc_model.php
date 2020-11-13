<?php

class Sdoc_model extends CI_Model {

    private static $list = array();

    function __construct() {

        parent::__construct();

        $this->modId = 91;
    }

    public function insert_model($param = array()) {

        $getUID = getUID('doc');
        $dataDoc = array(
            array(
                'id' => $getUID,
                'mod_id' => $this->modId,
                'doc_type_id' => $param['docTypeId'],
                'department_id' => $param['departmentId'],
                'partner_id' => $param['partnerId'],
                'people_id' => $param['peopleId'],
                'doc_date' => $param['docDate'],
                'doc_number' => $param['docNumber'],
                'description' => $param['description'],
                'page_number' => $param['pageNumber'],
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->userdata['adminUserId'],
                'modified_user_id' => $this->session->userdata['adminUserId'],
                'year' => date('Y', strtotime($param['docDate'])),
                'lang_id' => $this->session->userdata['adminLangId'],
                'is_reply' => $param['isReply'],
                'reply_date' => $param['replyDate']
            )
        );
        if ($this->db->insert_batch($this->db->dbprefix . 'doc', $dataDoc)) {
            return array(
                'docId' => $getUID,
                'status' => 'success',
                'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {

        $dataDoc = array(
            'doc_type_id' => $param['docTypeId'],
            'department_id' => $param['departmentId'],
            'partner_id' => $param['partnerId'],
            'people_id' => $param['peopleId'],
            'doc_date' => $param['docDate'],
            'doc_number' => $param['docNumber'],
            'description' => $param['description'],
            'page_number' => $param['pageNumber'],
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->userdata['adminUserId'],
            'year' => date('Y', strtotime($param['docDate'])),
            'is_reply' => $param['isReply'],
            'reply_date' => $param['replyDate']);

        $this->db->where('id', $param['docId']);

        if ($this->db->update($this->db->dbprefix . 'doc', $dataDoc)) {

            return array(
                'docId' => $param['docId'],
                'status' => 'success',
                'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {

        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'doc')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

}
