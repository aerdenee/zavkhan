<?php

class SdocTransfer_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Simage_model', 'simage');
        $this->load->model('Sfile_model', 'sfile');
        $this->load->model('Slog_model', 'slog');

        $this->modId = 17;
    }

    public function listsCount_model($param = array()) {

        $queryString = $query = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and !$auth->your->read) {
            $queryString .= ' AND D.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND D.created_user_id = -1';
        }

        if ($param['docTypeId'] != 0) {
            $queryString .= ' AND D.doc_type_id = ' . $param['docTypeId'];
        }

        if ($param['docNumber'] != '') {

            $queryString .= ' AND D.doc_number LIKE (\'%' . $param['docNumber'] . '%\')';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(D.doc_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(D.doc_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(D.doc_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(D.doc_date)';
        }

        if ($param['keyword'] != '') {

            $queryString .= ' AND LOWER(D.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
        }

        $query = $this->db->query('
                SELECT 
                    DD.id
                FROM `gaz_doc_transfer` AS DT
                INNER JOIN `gaz_doc_detail` AS DD ON DT.doc_detail_id = DD.id
                INNER JOIN `gaz_doc` AS D ON DD.doc_id = D.id
                WHERE DT.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ') AND DT.people_id = ' . $this->session->userdata['adminPeopleId'] . $queryString);

        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $data = array();
        $queryString = $query = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and !$auth->your->read) {
            $queryString .= ' AND D.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND D.created_user_id = -1';
        }

        if ($param['docTypeId'] != 0) {
            $queryString .= ' AND D.doc_type_id = ' . $param['docTypeId'];
        }

        if ($param['docNumber'] != '') {

            $queryString .= ' AND D.doc_number LIKE (\'%' . $param['docNumber'] . '%\')';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(D.doc_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(D.doc_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(D.doc_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(D.doc_date)';
        }

        if ($param['keyword'] != '') {

            $queryString .= ' AND LOWER(D.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
        }

        $query = $this->db->query('
            SELECT 
                DT.id,
                DD.mod_id,
                DD.doc_id,
                IF(DD.from_department_id = 51, P.title, CONCAT(HPD.title, \' \', SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname)) AS department,
                DD.created_date,
                DD.modified_date,
                DD.created_user_id,
                DD.modified_user_id,
                DD.status_id,
                D.doc_type_id,
                MDT.title AS doc_type_title,
                D.doc_date,
                D.doc_number,
                D.description,
                D.page_number,
                D.is_reply,
                D.reply_date,
                IF(D.is_reply = 1, CONCAT(D.description, \'<br>Хариу өгөх огноо: \', D.reply_date), D.description) AS description,
                (CASE 
                    WHEN (DD.doc_close_id = 0 AND D.is_reply = 1 AND CURDATE() >= DATE(D.reply_date)) THEN \'background-color:#F44336; color:rgba(255,255,255,0.8);\'
                    WHEN (DD.doc_close_id > 0 AND D.is_reply = 1 AND DATE(CLOSE_DOC.doc_date) <= DATE(D.reply_date)) THEN \'background-color:#4CAF50; color:rgba(255,255,255,0.8);\'
                    ELSE \'\'
                END) AS row_status,
                DT.description AS transfer_description
            FROM `gaz_doc_transfer` AS DT
            INNER JOIN `gaz_doc_detail` AS DD ON DT.doc_detail_id = DD.id
            INNER JOIN `gaz_doc` AS D ON DD.doc_id = D.id
            LEFT JOIN `gaz_doc` AS CLOSE_DOC ON DD.doc_close_id = CLOSE_DOC.id
            LEFT JOIN `gaz_master_doc_type` AS MDT ON D.doc_type_id = MDT.id
            LEFT JOIN `gaz_hr_people_department` AS HPD ON DD.from_department_id = HPD.id
            LEFT JOIN `gaz_partner` AS P ON DD.from_partner_id = P.id
            LEFT JOIN `gaz_hr_people` AS HP ON DD.from_people_id = HP.id
            WHERE DT.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ') AND DT.people_id = ' . $this->session->userdata['adminPeopleId'] . $queryString . '
            ORDER BY D.id DESC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);

        if ($query->num_rows() > 0) {

            $i = 0;
            foreach ($query->result() as $key => $row) {

                array_push($data, array(
                    'id' => $row->id,
                    'num' => ++$i,
                    'doc_id' => $row->doc_id,
                    'doc_date' => $row->doc_date,
                    'doc_number' => $row->doc_number,
                    'department' => $row->department,
                    'created_user_id' => $row->created_user_id,
                    'description' => $row->description,
                    'docTypeTitle' => $row->doc_type_title,
                    'transfer_description' => $row->transfer_description
                ));
            }
        }
        return array('data' => $data);
    }
    
    public function addDeleteList_model($param = array()) {
        $html = $queryString = '';

        $query = $this->db->query('
            SELECT 
                DT.id,
                DT.doc_detail_id,
                DT.people_id,
                (case when (HP.pic is null or HP.pic = \'\') then \'default.svg\' else (case when (HP.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', HP.pic) end) end) as pic,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name,
                HPD.title AS department_title
            FROM `gaz_doc_transfer` AS DT
            LEFT JOIN `gaz_hr_people` AS HP ON DT.people_id = HP.id
            LEFT JOIN `gaz_hr_people_department` AS HPD ON HP.department_id = HPD.id
            WHERE DT.doc_detail_id = ' . $param['docDetialId'] . '
            ORDER BY DT.id DESC');

        if (isset($param['disabled']) and $param['disabled'] == 'false') {
            $html .= '<div class="_user-drop-zone">';
            $html .= '<a href="javascript:;">';
            $html .= '<span class="_user-add-transfer-button" onclick="_addDocTransfer({elem: this, docDetialId: ' . $param['docDetialId'] . '});">';
            $html .= '</span>';
            $html .= '</a>';
            $html .= '</div>';
        }

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $html .= '<div class="_user-drop-zone">';
                $html .= '<input type="hidden" name="docDetialId[]" value="' . $row->doc_detail_id . '">';
                $html .= '<input type="hidden" name="docTransferId[]" value="' . $row->id . '">';
                $html .= '<div class="_user-drop-zone-viewer" style="background-image: url(\'' . UPLOADS_USER_PATH . $row->pic . '\');">';
                $html .= '<div class="_user-drop-zone-show-button" onclick="_showDocTransfer({elem: this});" title="Төслийг томруулж харах"><i class="fa fa-external-link"></i></div>';
                $html .= '<div class="_user-drop-zone-print-button" onclick="_printDocTransfer({elem: this, id: ' . $row->id . '});" title="Төслийг хэвлэх"><i class="fa fa-print"></i></div>';
                
                $html .= '<div class="_user-drop-zone-show-information">' . $row->full_name . '</div>';
                if (isset($param['disabled']) and $param['disabled'] == 'false') {
                    $html .= '<div class="_user-drop-zone-delete-button" onclick="_deleteDocTransfer({elem: this});" title="Устгах"><i class="fa fa-trash-o"></i></div>';
                }
                $html .= '</div>';
                $html .= '</div>';
            }
        }
        return '<span class="init-doc-transfer">' . $html . '</span>';
    }

    public function editData_model($param = array()) {

        $query = $this->db->query('
            SELECT 
                DT.id,
                DT.mod_id,
                DD.doc_id,
                DD.from_department_id,
                DD.from_partner_id,
                DD.from_people_id,
                HPD.title AS from_department,
                P.title AS from_partner,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS from_full_name,
                DD.created_date,
                DD.modified_date,
                DD.created_user_id,
                DD.modified_user_id,
                DD.status_id,
                D.doc_type_id,
                MDT.title AS doc_type_title,
                D.doc_date,
                D.doc_number,
                D.description,
                D.page_number,
                D.is_reply,
                D.reply_date,
                IF(D.is_reply = 1, CONCAT(D.description, \'<br>Хариу өгөх огноо: \', D.reply_date), D.description) AS description,
                (CASE 
                    WHEN (DD.doc_close_id = 0 AND D.is_reply = 1 AND CURDATE() >= DATE(D.reply_date)) THEN \'background-color:#F44336; color:rgba(255,255,255,0.8);\'
                    WHEN (DD.doc_close_id > 0 AND D.is_reply = 1 AND DATE(CLOSE_DOC.doc_date) <= DATE(D.reply_date)) THEN \'background-color:#4CAF50; color:rgba(255,255,255,0.8);\'
                    ELSE \'\'
                END) AS row_status,
                DT.description AS transfer_description
            FROM `gaz_doc_transfer` AS DT
            INNER JOIN `gaz_doc_detail` AS DD ON DT.doc_detail_id = DD.id
            INNER JOIN `gaz_doc` AS D ON DD.doc_id = D.id
            LEFT JOIN `gaz_doc` AS CLOSE_DOC ON DD.doc_close_id = CLOSE_DOC.id
            LEFT JOIN `gaz_master_doc_type` AS MDT ON D.doc_type_id = MDT.id
            LEFT JOIN `gaz_hr_people_department` AS HPD ON DD.from_department_id = HPD.id
            LEFT JOIN `gaz_partner` AS P ON DD.from_partner_id = P.id
            LEFT JOIN `gaz_hr_people` AS HP ON DD.from_people_id = HP.id
            WHERE DT.id = ' . $param['id']);

        if ($query->num_rows() > 0) {

            return $query->row();
        }
        
        return false;
    }
    
    public function insert_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->modId,
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        $dataDocTransfer = array(
            array(
                'id' => getUID('doc_transfer'),
                'mod_id' => $this->input->post('modId'),
                'doc_detail_id' => $this->input->post('docDetialId'),
                'people_id' => $this->input->post('peopleId'),
                'department_id' => $this->input->post('departmentId'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->userdata['adminUserId'],
                'modified_user_id' => $this->session->userdata['adminUserId']));
        if ($this->db->insert_batch($this->db->dbprefix . 'doc_transfer', $dataDocTransfer)) {

            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }
    
    public function update_model($param = array('pic' => '')) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_UPDATE,
            'data' => json_encode($_POST)));

        $data = array(
            'description' => $this->input->post('transferDescription'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId);
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'doc_transfer', $data)) {
            return array('status' => 'success', 'message' => 'Өөрчлөлтийг амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Өөрчлөлтийн түүхийг хадгалах үед алдаа гарлаа...');
    }

    public function delete_model($param = array('selected' => '')) {

        $query = $this->db->query('
            SELECT 
                DT.*
            FROM `gaz_doc_transfer` AS DT
            WHERE DT.id = ' . $param['id']);
        $row = $query->row();
        $row->table = 'doc_transfer';
        
        $this->slog->log_model(array(
            'modId' => $this->modId,
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_DELETE,
            'data' => json_encode($row)));
        $this->db->where_in('id', $param['id']);
        if ($this->db->delete($this->db->dbprefix . 'doc_transfer')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                DT.id,
                DT.doc_detail_id,
                DT.people_id,
                (case when (HP.pic is null or HP.pic = \'\') then \'default.svg\' else (case when (HP.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', HP.pic) end) end) as pic,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name,
                HPD.title AS department_title,
                HPP.title AS position_title,
                DATE(DT.created_date) AS created_date,
                DT.description
            FROM `gaz_doc_transfer` AS DT
            INNER JOIN `gaz_hr_people` AS HP ON DT.people_id = HP.id
            INNER JOIN `gaz_hr_people_department` AS HPD ON HP.department_id = HPD.id
            INNER JOIN `gaz_hr_people_position` AS HPP ON HP.position_id = HPP.id
            WHERE DT.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

}
