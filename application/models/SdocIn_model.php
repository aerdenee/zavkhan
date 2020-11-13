<?php

class SdocIn_model extends CI_Model {

    private static $list = array();

    function __construct() {

        parent::__construct();

        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Suser_model', 'user');
        $this->load->model('SreportMenu_model', 'sreportMenu');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('ShrPeoplePosition_model', 'hrPeoplePosition');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Schart_model', 'chart');
        $this->load->model('Sdoc_model', 'sdoc');


        $this->modId = 17;
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => $this->modId,
            'doc_id' => 0,
            'from_department_id' => 0,
            'from_partner_id' => 0,
            'from_people_id' => 0,
            'to_department_id' => $this->session->userdata['adminDepartmentId'],
            'to_partner_id' => 0,
            'to_people_id' => 0,
            'doc_close_id' => 0,
            'created_user_id' => $this->session->userdata['adminUserId'],
            'doc_number' => '',
            'doc_date' => '',
            'status_id' => 0,
            'doc_type_id' => 0,
            'department_id' => $this->session->userdata['adminDepartmentId'],
            'partner_id' => 0,
            'people_id' => $this->session->userdata['adminPeopleId'],
            'create_number' => '',
            'doc_date' => date('Y-m-d'),
            'doc_number' => '',
            'description' => '',
            'page_number' => '',
            'is_reply' => 0,
            'reply_date' => '')));
    }

    public function editFormData_model($param = array('id' => 0)) {

        $query = $this->db->query('
            SELECT 
                DD.id,
                DD.mod_id,
                DD.doc_id,
                DD.from_department_id,
                DD.from_partner_id,
                DD.from_people_id,
                DD.to_department_id,
                DD.to_partner_id,
                DD.to_people_id,
                DD.created_date,
                DD.modified_date,
                DD.created_user_id,
                DD.modified_user_id,
                DD.status_id,
                DD.doc_close_id,
                CLOSE_DOC.doc_number,
                CLOSE_DOC.doc_date,
                D.doc_type_id,
                D.department_id,
                D.partner_id,
                D.people_id,
                D.doc_date,
                D.doc_number,
                D.description,
                D.page_number,
                D.is_reply,
                D.reply_date,
                D.page_number
            FROM `gaz_doc_detail` AS DD
            INNER JOIN `gaz_doc` AS D ON DD.doc_id = D.id
            LEFT JOIN `gaz_doc` AS CLOSE_DOC ON DD.doc_close_id = CLOSE_DOC.id
            WHERE DD.id = ' . $param['id']);

        if ($query->num_rows() > 0) {
            return $query->row();
        }

        return self::addFormData_model();
    }

    public function listsCount_model($param = array()) {

        $queryString = $query = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and ! $auth->your->read) {
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

        if ($param['fromDepartmentId'] != 0) {

            $queryString .= ' AND DD.from_department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['fromDepartmentId']) . ')';
        }

        if ($param['fromPartnerId'] != 0) {

            $queryString .= ' AND DD.from_partner_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['fromPartnerId']) . ')';
        }

        if ($param['fromPeopleId'] != 0) {

            $queryString .= ' AND DD.from_people_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['fromPeopleId']) . ')';
        }

        if ($param['keyword'] != '') {

            $queryString .= ' AND LOWER(D.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
        }

        $query = $this->db->query('
                SELECT 
                    DD.id
                FROM `gaz_doc_detail` AS DD
                INNER JOIN `gaz_doc` AS D ON DD.doc_id = D.id
                WHERE DD.to_department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ') AND D.lang_id = ' . $this->session->userdata['adminLangId'] . $queryString);

        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $data = array();
        $queryString = $query = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and ! $auth->your->read) {
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

        if ($param['fromDepartmentId'] != 0) {

            $queryString .= ' AND DD.from_department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['fromDepartmentId']) . ')';
        }

        if ($param['fromPartnerId'] != 0) {

            $queryString .= ' AND DD.from_partner_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['fromPartnerId']) . ')';
        }

        if ($param['fromPeopleId'] != 0) {

            $queryString .= ' AND DD.from_people_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['fromPeopleId']) . ')';
        }

        if ($param['keyword'] != '') {

            $queryString .= ' AND LOWER(D.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
        }

        $query = $this->db->query('
            SELECT 
                DD.id,
                DD.mod_id,
                DD.doc_id,
                DD.from_department_id,
                DD.from_partner_id,
                DD.from_people_id,
                DD.to_department_id,
                DD.to_partner_id,
                DD.to_people_id,
                IF(DD.from_department_id = 51, P.title, HPD.title) AS from_department,
                DD.doc_close_id,
                DD.created_date,
                DD.modified_date,
                DD.created_user_id,
                DD.modified_user_id,
                DD.status_id,
                DD.is_read,
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
                D.page_number,
                IF(DD.doc_close_id > 0, CONCAT(CLOSE_DOC.doc_date, \' өдрийн \', CLOSE_DOC.doc_number, \' тоот албан бичиг\'), \'\') AS close,
                CONCAT(SUBSTRING(U.lname, 1, 1), \'.\', U.fname) AS user,
                IF (DT.people_id != \'\', CONCAT(SUBSTRING(HPT.lname, 1, 1), \'.\', HPT.fname), \'\') AS transfer
            FROM `gaz_doc_detail` AS DD
            INNER JOIN `gaz_doc` AS D ON DD.doc_id = D.id
            LEFT JOIN `gaz_master_doc_type` AS MDT ON D.doc_type_id = MDT.id
            LEFT JOIN `gaz_hr_people_department` AS HPD ON DD.from_department_id = HPD.id
            LEFT JOIN `gaz_partner` AS P ON DD.from_partner_id = P.id
            LEFT JOIN `gaz_hr_people` AS HP ON DD.from_people_id = HP.id
            LEFT JOIN `gaz_doc` AS CLOSE_DOC ON DD.doc_close_id = CLOSE_DOC.id
            LEFT JOIN `gaz_user` AS U ON D.created_user_id = U.id
            LEFT JOIN `gaz_doc_transfer` AS DT ON DD.id = DT.doc_detail_id
            LEFT JOIN `gaz_hr_people` AS HPT ON DT.people_id = HPT.id
            WHERE DD.to_department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ') AND D.lang_id = ' . $this->session->userdata['adminLangId'] . $queryString . '
            GROUP BY DD.id DESC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);

        if ($query->num_rows() > 0) {

            $i = 0;
            foreach ($query->result() as $key => $row) {

                if ($row->is_read == 1) {
                    array_push($data, array(
                    'id' => $row->id,
                    'num' => ++$i,
                    'doc_id' => $row->doc_id,
                    'doc_date' => $row->doc_date,
                    'doc_number' => $row->doc_number,
                    'from_department_id' => $row->from_department_id,
                    'from_department' => $row->from_department,
                    'created_user_id' => $row->created_user_id,
                    'description' => $row->description,
                    'docTypeTitle' => $row->doc_type_title,
                    'transfer' => $row->transfer,
                    'close' => $row->close,
                    'user' => $row->user,
                    'doc_close_id' => $row->doc_close_id
                ));
                } else {
                    array_push($data, array(
                    'id' => $row->id,
                    'num' => ++$i,
                    'doc_id' => $row->doc_id,
                    'doc_date' => '<strong>' . $row->doc_date . '</strong>',
                    'doc_number' => '<strong>' . $row->doc_number . '</strong>',
                    'from_department_id' => $row->from_department_id,
                    'from_department' => '<strong>' . $row->from_department . '</strong>',
                    'created_user_id' => $row->created_user_id,
                    'description' => '<strong>' . $row->description . '</strong>',
                    'docTypeTitle' => '<strong>' . $row->doc_type_title . '</strong>',
                    'transfer' => '<strong>' . $row->transfer . '</strong>',
                    'close' => '<strong>' . $row->close . '</strong>',
                    'user' => '<strong>' . $row->user . '</strong>',
                    'doc_close_id' => $row->doc_close_id
                ));
                }
                
            }
        }
        return array('data' => $data, 'search' => self::searchKeywordView_model());
    }

    public function insert_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->modId,
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        $doc = $this->sdoc->insert_model(array(
            'docTypeId' => $this->input->post('docTypeId'),
            'departmentId' => $this->session->userdata['adminDepartmentId'],
            'partnerId' => 0,
            'peopleId' => $this->session->userdata['adminPeopleId'],
            'docDate' => $this->input->post('docDate'),
            'docNumber' => $this->input->post('docNumber'),
            'description' => $this->input->post('description'),
            'pageNumber' => $this->input->post('pageNumber'),
            'isReply' => $this->input->post('isReply'),
            'replyDate' => $this->input->post('replyDate')));

        if ($doc['status'] == 'success') {

            /** Илгээж байгаа албан бичгийн файлыг insert хийж байгаа хэсэг * */
            if ($this->input->post('attachFile') != NULL) {
                foreach ($this->input->post('attachFile') as $key => $attachFile) {

                    if (is_file($_SERVER['DOCUMENT_ROOT'] . UPLOADS_TEMP_PATH . $attachFile)) {
                        copy($_SERVER['DOCUMENT_ROOT'] . UPLOADS_TEMP_PATH . $attachFile, $_SERVER['DOCUMENT_ROOT'] . UPLOADS_DOCUMENT_PATH . $attachFile);

                        if ($this->input->post('mimeType[' . $key . ']') == 'image/jpeg' or $this->input->post('mimeType[' . $key . ']') == 'image/jpg') {
                            $this->simage->imageReSize_model(array(
                                'uploadPath' => UPLOADS_DOCUMENT_PATH,
                                'sourceImage' => $attachFile,
                                'newImage' => CROP_SMALL . $attachFile,
                                'height' => SMALL_HEIGHT,
                                'width' => SMALL_WIDTH
                            ));
                        }

                        unlink($_SERVER['DOCUMENT_ROOT'] . UPLOADS_TEMP_PATH . $attachFile);
                    }

                    $dataDocFile = array(
                        array(
                            'id' => getUID('doc_file'),
                            'mod_id' => $this->modId,
                            'doc_id' => $doc['docId'],
                            'pic' => ($this->input->post('mimeType[' . $key . ']') == 'application/pdf' ? 'pdf.png' : CROP_SMALL . $attachFile),
                            'attach_file' => $attachFile,
                            'mime_type' => $this->input->post('mimeType[' . $key . ']'),
                            'file_size' => $this->input->post('fileSize[' . $key . ']'),
                            'is_active' => 1,
                            'created_date' => date('Y-m-d H:i:s'),
                            'modified_date' => date('Y-m-d H:i:s'),
                            'created_user_id' => $this->session->userdata['adminUserId'],
                            'modified_user_id' => $this->session->userdata['adminUserId'],
                            'lang_id' => $this->session->userdata['adminLangId']));
                    $this->db->insert_batch($this->db->dbprefix . 'doc_file', $dataDocFile);
                }
            }


            /** Тухайн албан бичгийн бүртгэл insert хийж байгаа хэсэг * */
            $dataDocDetail = array(
                array(
                    'id' => getUID('doc_detail'),
                    'mod_id' => $this->modId,
                    'doc_id' => $doc['docId'],
                    'from_department_id' => $this->input->post('fromDepartmentId'),
                    'from_partner_id' => $this->input->post('fromPartnerId'),
                    'from_people_id' => $this->input->post('fromPeopleId'),
                    'to_department_id' => $this->input->post('toDepartmentId'),
                    'to_partner_id' => $this->input->post('toPartnerId'),
                    'to_people_id' => $this->input->post('toPeopleId'),
                    'created_date' => date('Y-m-d H:i:s'),
                    'modified_date' => date('Y-m-d H:i:s'),
                    'created_user_id' => $this->session->userdata['adminUserId'],
                    'modified_user_id' => $this->session->userdata['adminUserId'],
                    'year' => date('Y', strtotime($this->input->post('docDate'))),
                    'lang_id' => $this->session->userdata['adminLangId']));
            $this->db->insert_batch($this->db->dbprefix . 'doc_detail', $dataDocDetail);

            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->modId,
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_UPDATE,
            'data' => json_encode($_POST)));

        $doc = $this->sdoc->update_model(array(
            'docId' => $this->input->post('docId'),
            'docTypeId' => $this->input->post('docTypeId'),
            'departmentId' => $this->session->userdata['adminDepartmentId'],
            'partnerId' => 0,
            'peopleId' => $this->session->userdata['adminPeopleId'],
            'docDate' => $this->input->post('docDate'),
            'docNumber' => $this->input->post('docNumber'),
            'description' => $this->input->post('description'),
            'pageNumber' => $this->input->post('pageNumber'),
            'isReply' => $this->input->post('isReply'),
            'replyDate' => $this->input->post('replyDate')));

        if ($doc['status'] == 'success') {

            /** Илгээж байгаа албан бичгийн файлыг insert хийж байгаа хэсэг * */
            if ($this->input->post('attachFile')) {

                $this->db->where('doc_id', $this->input->post('docId'));
                $this->db->delete($this->db->dbprefix . 'doc_file');

                foreach ($this->input->post('attachFile') as $key => $attachFile) {

                    if (is_file($_SERVER['DOCUMENT_ROOT'] . UPLOADS_TEMP_PATH . $attachFile)) {
                        copy($_SERVER['DOCUMENT_ROOT'] . UPLOADS_TEMP_PATH . $attachFile, $_SERVER['DOCUMENT_ROOT'] . UPLOADS_DOCUMENT_PATH . $attachFile);

                        if ($this->input->post('mimeType[' . $key . ']') == 'image/jpeg' or $this->input->post('mimeType[' . $key . ']') == 'image/jpg') {
                            $this->simage->imageReSize_model(array(
                                'uploadPath' => UPLOADS_DOCUMENT_PATH,
                                'sourceImage' => $attachFile,
                                'newImage' => CROP_SMALL . $attachFile,
                                'height' => SMALL_HEIGHT,
                                'width' => SMALL_WIDTH
                            ));
                        }

                        unlink($_SERVER['DOCUMENT_ROOT'] . UPLOADS_TEMP_PATH . $attachFile);
                    }

                    $dataDocFile = array(
                        array(
                            'id' => getUID('doc_file'),
                            'mod_id' => $this->modId,
                            'doc_id' => $this->input->post('docId'),
                            'pic' => ($this->input->post('mimeType[' . $key . ']') == 'application/pdf' ? 'pdf.png' : CROP_SMALL . $attachFile ),
                            'attach_file' => $attachFile,
                            'mime_type' => $this->input->post('mimeType[' . $key . ']'),
                            'file_size' => $this->input->post('fileSize[' . $key . ']'),
                            'is_active' => 1,
                            'created_date' => date('Y-m-d H:i:s'),
                            'modified_date' => date('Y-m-d H:i:s'),
                            'created_user_id' => $this->session->userdata['adminUserId'],
                            'modified_user_id' => $this->session->userdata['adminUserId'],
                            'lang_id' => $this->session->userdata['adminLangId']));

                    $this->db->insert_batch($this->db->dbprefix . 'doc_file', $dataDocFile);
                }
            }


            /** Тухайн албан бичгийн бүртгэл insert хийж байгаа хэсэг * */
            $dataDocDetail = array(
                'doc_id' => $this->input->post('docId'),
                'from_department_id' => $this->input->post('fromDepartmentId'),
                'from_partner_id' => $this->input->post('fromPartnerId'),
                'from_people_id' => $this->input->post('fromPeopleId'),
                'to_department_id' => $this->input->post('toDepartmentId'),
                'to_partner_id' => $this->input->post('toPartnerId'),
                'to_people_id' => $this->input->post('toPeopleId'),
                'modified_date' => date('Y-m-d H:i:s'),
                'modified_user_id' => $this->session->userdata['adminUserId'],
                'year' => date('Y', strtotime($this->input->post('docDate'))));
            $this->db->where('id', $this->input->post('id'));
            $this->db->update($this->db->dbprefix . 'doc_detail', $dataDocDetail);
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {

        $row = $this->getData_model(array('selectedId' => $this->input->post('id')));

        $this->slog->log_model(array(
            'modId' => $this->modId,
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_DELETE,
            'data' => json_encode($row)));

        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'doc_detail')) {

            $query = $this->db->query('
                SELECT 
                    DD.id
                FROM `gaz_doc_detail` AS DD
                WHERE DD.from_department_id = ' . $row->to_department_id);
            if (empty($query->num_rows())) {

                $this->db->where('doc_detail_id', $this->input->post('id'));
                $this->db->delete($this->db->dbprefix . 'doc_transfer');

                $this->db->where('doc_detail_id', $this->input->post('id'));
                $this->db->delete($this->db->dbprefix . 'doc_close');

                $this->db->where('id', $row->doc_id);
                $this->db->delete($this->db->dbprefix . 'doc');

                $queryDocFile = $this->db->query('
                SELECT 
                    DF.id,
                    DF.pic,
                    DF.attach_file,
                    DF.mime_type
                FROM `gaz_doc_file` AS DF
                WHERE DF.doc_id = ' . $row->doc_id);
                if ($queryDocFile->num_rows() > 0) {
                    foreach ($queryDocFile->result() as $rowDocFile) {

                        if (($rowDocFile->mime_type == 'image/jpeg' or $rowDocFile->mime_type == 'image/jpg') and is_file($_SERVER['DOCUMENT_ROOT'] . UPLOADS_DOCUMENT_PATH . $rowDocFile->pic)) {
                            unlink($_SERVER['DOCUMENT_ROOT'] . UPLOADS_DOCUMENT_PATH . $rowDocFile->pic);
                        }

                        if (is_file($_SERVER['DOCUMENT_ROOT'] . UPLOADS_DOCUMENT_PATH . $rowDocFile->attach_file)) {
                            unlink($_SERVER['DOCUMENT_ROOT'] . UPLOADS_DOCUMENT_PATH . $rowDocFile->attach_file);
                        }
                    }

                    $this->db->where('doc_id', $row->doc_id);
                    $this->db->delete($this->db->dbprefix . 'doc_file');
                }
            }

            return array('status' =>
                'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function searchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
        $string = '';
        $showResetBtn = FALSE;

        if ($this->input->get('docTypeId')) {
            $smasterDocType = $this->smasterDocType->getData_model(array('selectedId' => $this->input->get('docTypeId')));
            $string .= '<span class="label label-default label-rounded">' . $smasterDocType->title . '</span>';
            $string .= form_hidden('docTypeId', $this->input->get('docTypeId'));
            $showResetBtn = TRUE;
        }

        if ($this->input->get('docNumber') != '') {

            $string .= '<span class="label label-default label-rounded">' . $this->input->get('docNumber') . '</span>';
            $string .= form_hidden('docNumber', $this->input->get('docNumber'));
            $showResetBtn = TRUE;
        }


        $string .= form_hidden('inDate', $this->input->get('inDate'));
        $string .= form_hidden('outDate', $this->input->get('outDate'));

        if ($this->input->get('inDate') and $this->input->get('outDate')) {
            $string .= '<span class="label label-default label-rounded">Албан бичгийн огноо: ' . date('Y.m.d', strtotime($this->input->get('inDate'))) . '-' . date('Y.m.d', strtotime($this->input->get('outDate'))) . '</span>';
            $showResetBtn = TRUE;
        } else if ($this->input->get('inDate')) {
            $string .= '<span class="label label-default label-rounded">Албан бичгийн огноо: ' . date('Y.m.d', strtotime($this->input->get('inDate'))) . ' хойш</span>';
            $showResetBtn = TRUE;
        } else if ($this->input->get('outDate')) {
            $string .= '<span class="label label-default label-rounded">Албан бичгийн огноо: ' . date('Y.m.d', strtotime($this->input->get('outDate'))) . ' өмнөх</span>';
            $showResetBtn = TRUE;
        }

        if ($this->input->get('fromDepartmentId')) {
            $hrPeopleDepartment = $this->hrPeopleDepartment->getData_model(array('selectedId' => $this->input->get('fromDepartmentId')));
            $string .= '<span class="label label-default label-rounded">' . $hrPeopleDepartment->title . '</span>';
            $string .= form_hidden('fromDepartmentId', $this->input->get('fromDepartmentId'));
            $showResetBtn = TRUE;
        }

        if ($this->input->get('fromPartnerId')) {
            $partner = $this->partner->getData_model(array('selectedId' => $this->input->get('fromPartnerId')));
            $string .= '<span class="label label-default label-rounded">' . $partner->title . '</span>';
            $string .= form_hidden('fromPartnerId', $this->input->get('fromPartnerId'));
            $showResetBtn = TRUE;
        }

        if ($this->input->get('fromPeopleId')) {
            $hrPeople = $this->hrPeople->getData_model(array('selectedId' => $this->input->get('fromPeopleId')));
            $string .= '<span class="label label-default label-rounded">' . $hrPeople->title . '</span>';
            $string .= form_hidden('fromPeopleId', $this->input->get('fromPeopleId'));
            $showResetBtn = TRUE;
        }

        if ($this->input->get('keyword')) {
            $string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $string .= form_hidden('keyword', $this->input->get('keyword'));
            $showResetBtn = TRUE;
        }

        if ($showResetBtn) {
            $string .= ' <a href="javascript:;" onclick="_initDocIn({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return ($showResetBtn ? '<div class="_search-result-inner"><form action="javascript:;" id="form-doc-in"> Хайлтын үр дүн: ' . $string . '</form></div>' : '');
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $query = $this->db->query('
            SELECT 
                DD.id,
                DD.mod_id,
                DD.doc_id,
                DD.from_department_id,
                DD.from_partner_id,
                DD.from_people_id,
                DD.to_department_id,
                DD.to_partner_id,
                DD.to_people_id,
                DD.created_date,
                DD.modified_date,
                DD.created_user_id,
                DD.modified_user_id,
                DD.status_id,
                DD.doc_close_id,
                CLOSE_DOC.doc_number,
                CLOSE_DOC.doc_date,
                D.doc_type_id,
                D.department_id,
                D.partner_id,
                D.people_id,
                D.doc_date,
                D.doc_number,
                D.description,
                D.page_number,
                D.is_reply,
                D.reply_date,
                D.page_number
            FROM `gaz_doc_detail` AS DD
            INNER JOIN `gaz_doc` AS D ON DD.doc_id = D.id
            LEFT JOIN `gaz_doc` AS CLOSE_DOC ON DD.doc_close_id = CLOSE_DOC.id
            WHERE DD.id = ' . $param['selectedId']);

        if ($query->num_rows() > 0) {
            return $query->row();
        }

        return self::addFormData_model();
    }

    public function isRead_model($param = array()) {
        /** Тухайн албан бичгийн уншсан тэмдэглэгээ * */
        $dataDocDetail = array('is_read' => 1);
        $this->db->where('id', $this->input->post('id'));
        $this->db->update($this->db->dbprefix . 'doc_detail', $dataDocDetail);
    }

}
