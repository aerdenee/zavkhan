<?php

class SdocFile_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Simage_model', 'simage');
        $this->load->model('Sfile_model', 'sfile');
        $this->load->model('Slog_model', 'slog');
    }

    public function lists_model($param = array()) {

        $html = $queryString = '';

        $query = $this->db->query('
            SELECT 
                DF.id,
                DF.doc_id,
                DF.pic AS pic_file,
                (CASE 
                    WHEN (DF.mime_type = \'application/pdf\') THEN CONCAT(\'/assets/system/img/doc/\', DF.pic)
                    ELSE CONCAT(\'' . UPLOADS_DOCUMENT_PATH . '\', DF.pic)
                END) AS pic,
                DF.attach_file,
                DF.mime_type,
                DF.file_size,
                IF(DF.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                DATE(DF.created_date) AS created_date,
                DATE(DF.modified_date) AS modified_date,
                DF.created_user_id,
                DF.modified_user_id,
                DF.media_type_id
            FROM `gaz_doc_file` AS DF
            WHERE DF.lang_id = ' . $this->session->userdata['adminLangId'] . ' AND DF.doc_id = ' . $param['docId'] . '
            ORDER BY DF.id DESC');

        if (isset($param['disabled']) and $param['disabled'] == 'false') {
            $html .= '<div class="_user-drop-zone">';
            $html .= '<a href="javascript:;">';
            $html .= '<span class="_user-drop-zone-button">';
            $html .= '<div class="uploader">';
            $html .= '<input type="file" name="docFileAttachFileUpload" id="docFileAttachFileUpload" class="pull-left file-styled" onchange="_fileUpload({elem: this, uploadPath: UPLOADS_TEMP_PATH, formId: \'' . $param['formId'] . '\', appendHtmlClass: \'.init-doc-file\', prefix: \'docFile\'});">';
            $html .= '</div>';
            $html .= '</span>';
            $html .= '</a>';
            $html .= '</div>';
            
            $html .= form_hidden('docFileAttachFile', '');
            $html .= form_hidden('docFileOldAttachFile', '');
        }

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $html .= '<div class="_user-drop-zone">';
                $html .= '<input type="hidden" name="docFileId[]" value="' . $row->id . '">';
                $html .= '<input type="hidden" name="attachFile[]" value="' . $row->attach_file . '">';
                $html .= '<input type="hidden" name="mimeType[]" value="' . $row->mime_type . '">';
                $html .= '<input type="hidden" name="fileSize[]" value="' . $row->file_size . '">';
                $html .= '<input type="hidden" name="filePath[]" value="' . UPLOADS_DOCUMENT_PATH . '">';
                $html .= '<div class="_user-drop-zone-viewer" style="background-image: url(\'' . $row->pic . '\');">';
                //$html .= '<div class="_user-drop-zone-print-button" onclick="_printDocFile({elem: this});" title="Хавсралт файл хэвлэх"><i class="fa fa-print"></i></div>';
                $html .= '<div class="_user-drop-zone-show-button" onclick="_showDocFile({elem: this});" title="Хавсралт файл томоор хэрэх"><i class="fa fa-external-link"></i></div>';
                if (isset($param['disabled']) and $param['disabled'] == 'false') {
                    $html .= '<div class="_user-drop-zone-delete-button" onclick="_deleteDocFile({elem: this});" title="Хавсралт файл устгах"><i class="fa fa-trash-o"></i></div>';
                }
                $html .= '</div>';
                $html .= '</div>';
            }
        }
        return '<span class="init-doc-file">' . $html . '</span>';
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
                    'to_people_id' => $this->input->post('toPartnerId'),
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

        $data = array(
            'doc_id' => $this->input->post('docId'),
            'pic' => $this->createThumbnail_model(array(
                'attachFile' => $this->input->post('attachFile'),
                'oldAttachFile' => $this->input->post('oldAttachFile'),
                'mimeType' => $this->input->post('mimeType'))),
            'attach_file' => ($this->input->post('attachFile') != '' ? $this->input->post('attachFile') : $this->input->post('oldAttachFile')),
            'mime_type' => ($this->input->post('mimeType') != '' ? $this->input->post('mimeType') : $this->input->post('oldMimeType')),
            'file_size' => $this->input->post('fileSize'),
            'is_active' => $this->input->post('isActive'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId);

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'doc_file', $data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model($param = array('uploadPath' => '')) {

        $query = $this->db->query('
            SELECT 
                DF.pic,
                DF.attach_file
            FROM `gaz_doc_file` AS DF
            WHERE DF.id = ' . $param['selectedId']);
        $row = $query->row();
        if ($query->num_rows() > 0) {
            $this->simage->removeUploadImage_model(array('uploadImage' => $row->pic, 'uploadPath' => UPLOADS_DOCUMENT_PATH));
            $this->sfile->removeUploadFile_model(array('uploadFile' => $row->attach_file, 'uploadPath' => UPLOADS_DOCUMENT_PATH));

            $this->db->where('id', $param['selectedId']);
            $this->db->delete($this->db->dbprefix . 'doc_file');
        } else {
            $this->simage->removeUploadImage_model(array('uploadImage' => $param['attachFile'], 'uploadPath' => UPLOADS_TEMP_PATH));
            $this->sfile->removeUploadFile_model(array('uploadFile' => $param['attachFile'], 'uploadPath' => UPLOADS_TEMP_PATH));
        }

        return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
    }

    public function createThumbnail_model($param = array()) {

        if ($param['mimeType'] == 'image/bmp' or $param['mimeType'] == 'image/gif' or $param['mimeType'] == 'image/jpeg' or $param['mimeType'] == 'image/png' or $param['mimeType'] == 'image/png' or $param['mimeType'] == 'image/webp') {

            $imageReSizeResult = $this->simage->imageReSize_model(array(
                'sourceImage' => ($param['attachFile'] != '' ? $param['attachFile'] : $param['oldAttachFile']),
                'newImage' => CROP_SMALL . ($param['attachFile'] != '' ? $param['attachFile'] : $param['oldAttachFile']),
                'height' => SMALL_HEIGHT,
                'width' => SMALL_WIDTH,
                'uploadPath' => UPLOADS_CONTENT_PATH));

            if ($imageReSizeResult['status'] == 'success') {
                return CROP_SMALL . ($param['attachFile'] != '' ? $param['attachFile'] : $param['oldAttachFile']);
            }
        } else if ($param['mimeType'] == 'video/vnd.uvvu.mp4' or $param['mimeType'] == 'audio/mp4' or $param['mimeType'] == 'video/mp4' or $param['mimeType'] == 'application/mp4') {

            return 'iconMp4.svg';
        } else if ($param['mimeType'] == 'application/pdf') {

            return 'iconPdf.svg';
        }

        return 'default.svg';
    }

    public function getListData_model($param = array('modId' => 0, 'contId' => 0)) {


        $this->queryString = $this->getString = '';

        $this->query = $this->db->query('
            SELECT 
                DM.id,
                DM.mod_id,
                DM.cont_id,
                DM.title,
                DM.intro_text,
                DM.pic,
                DM.attach_file,
                DM.mime_type,
                DM.is_active,
                DM.is_active_date,
                DM.param,
                DM.order_num,
                DM.created_date,
                DM.modified_date,
                DM.media_type_id,
                DM.created_user_id,
                DM.modified_user_id,
                DM.lang_id,
                CMT.title AS doc_file_type_title
            FROM `gaz_doc_file` AS DM
            INNER JOIN `gaz_doc_file_type` CMT ON DM.media_type_id = CMT.id
            WHERE 1 = 1 ' . $this->queryString . ' AND DM.cont_id = ' . $param['contId'] . ' AND DM.mod_id = ' . $param['modId'] . ' 
            ORDER BY DM.order_num DESC');


        if ($this->query->num_rows() > 0) {

            return $this->query->result();
        }
        return false;
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                DF.id,
                DF.mod_id,
                DF.doc_id,
                DF.pic,
                DF.attach_file,
                DF.mime_type,
                DF.file_size,
                DF.is_active,
                DF.created_date,
                DF.modified_date,
                DF.media_type_id,
                DF.created_user_id,
                DF.modified_user_id,
                DF.lang_id
            FROM `gaz_doc_file` AS DF
            WHERE DF.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

    public function getEnumData_model($param = array('contId' => 0, 'modId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                DM.id,
                DM.attach_file,
                DM.mime_type,
                DM.file_size
            FROM `gaz_doc_file` AS DM
            WHERE DM.id IN (' . $param['id'] . ')');

        if ($this->query->num_rows() > 0) {
            return $this->query->result();
        }
        return false;
    }

}
