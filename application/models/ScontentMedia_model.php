<?php

class ScontentMedia_model extends CI_Model {

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
            'title' => '',
            'intro_text' => '',
            'pic' => $this->simage->returnDefaultImage_model(array('table' => 'content_media')),
            'attach_file' => '',
            'pic_mime_type' => '',
            'pic_file_size' => 0,
            'attach_file_mime_type' => '',
            'attach_file_size' => 0,
            'is_active' => 1,
            'is_active_date' => date('Y-m-d H:i:s'),
            'order_num' => getOrderNum(array('table' => 'content_media', 'field' => 'order_num')),
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s'),
            'media_type_id' => 1,
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'lang_id' => 1
        )));
    }

    public function editFormData_model($param = array('contId' => 0, 'modId' => 0)) {

        $query = $this->db->query('
            SELECT 
                CM.id,
                CM.mod_id,
                CM.cont_id,
                CM.title,
                CM.intro_text,
                (case when (CM.pic is null or CM.pic = \'\' or CM.pic = \'default.svg\') then \'' . $this->simage->returnDefaultImage_model(array('table' => 'menu')) . '\' else concat(\'' . UPLOADS_CONTENT_PATH . 's_\', CM.pic) end) as pic,
                CM.attach_file,
                CM.pic_mime_type,
                CM.pic_file_size,
                CM.attach_file_mime_type,
                CM.attach_file_size,
                CM.created_date,
                CM.modified_date,
                CM.media_type_id,
                CM.created_user_id,
                CM.modified_user_id,
                CM.order_num,
                CM.is_active
            FROM `gaz_content_media` AS CM
            WHERE CM.id = ' . $param['selectedId']);

        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return self::addFormData_model(array('contId' => $param['contId'], 'modId' => $param['modId']));
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $queryString = $html = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and !$auth->your->read) {
            $queryString .= ' AND CM.created_user_id = ' . $this->session->userdata['adminUserId'];
        } else if (!$auth->our->read and !$auth->your->read) {
            $queryString .= ' AND CM.created_user_id = -1';
        }

        if ($param['partnerId'] != 0) {
            $queryString .= ' AND CM.partner_id = ' . $param['partnerId'];
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND CM.cat_id = ' . $param['catId'];
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND (LOWER(CM.title) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(CM.intro_text) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(CM.full_text) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {
            $queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(CM.is_active_date) AND \'' . $param['outDate'] . '\' >= DATE(CM.is_active_date)';
        } elseif ($param['inDate'] != '' and $param['outDate'] == '') {
            $queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(CM.is_active_date)';
        } elseif ($param['inDate'] == '' and $param['outDate'] != '') {
            $queryString .= ' AND \'' . $param['outDate'] . '\' >= DATE(CM.is_active_date)';
        }

        $query = $this->db->query('
            SELECT 
                CM.id,
                CM.mod_id,
                CM.cont_id,
                CM.title,
                CM.intro_text,
                (case when (CM.pic is null OR CM.pic = \'\' OR CM.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', CM.pic) end) as pic,
                CM.attach_file,
                CM.pic_mime_type,
                CM.pic_file_size,
                CM.attach_file_mime_type,
                CM.attach_file_size,
                IF(CM.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                CM.is_active_date,
                CM.media_type_id,
                CM.created_user_id,
                (case 
                    when (CM.media_type_id = 1) then \'<i class="fa fa-image"></i>\'
                    when (CM.media_type_id = 2) then \'<i class="fa fa-video-camera"></i>\' 
                    when (CM.media_type_id = 3) then \'<i class="fa fa-youtube-play"></i>\'
                    when (CM.media_type_id = 4) then \'<i class="fa fa-file-o"></i>\'
                    else \'<i class="fa fa-facebook"></i>\' end) as icon
            FROM `gaz_content_media` AS CM
            WHERE CM.lang_id = ' . $this->session->userdata['adminLangId'] . $queryString . ' AND CM.cont_id = ' . $param['contId'] . ' AND CM.mod_id = ' . $param['modId'] . '
            ORDER BY CM.order_num DESC');

        $html .= '<div class="_user-drop-zone">';
        $html .= '<a href="javascript:;" onclick="_addFormContentMedia({elem: this});">';
        $html .= '<span class="_user-drop-zone-button">';
        $html .= '<div class="uploader"></div>';
        $html .= '</span>';
        $html .= '</a>';
        $html .= '</div>';

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $row) {

                $html .= '<div class="_user-drop-zone">';
                $html .= '<input type="hidden" name="contentMediaId[]" value="' . $row->id . '">';
                $html .= '<input type="hidden" name="attachFile[]" value="' . $row->attach_file . '">';
                $html .= '<input type="hidden" name="picMimeType[]" value="' . $row->pic_mime_type . '">';
                $html .= '<input type="hidden" name="picFileSize[]" value="' . $row->pic_file_size . '">';
                $html .= '<input type="hidden" name="attachFileMimeType[]" value="' . $row->attach_file_mime_type . '">';
                $html .= '<input type="hidden" name="attachFileSize[]" value="' . $row->attach_file_size . '">';

                $html .= '<div class="_user-drop-zone-viewer" style="background-image: url(\'' . UPLOADS_CONTENT_PATH . $row->pic . '\');">';
                $html .= '<div class="_user-drop-zone-type-icon">' . $row->icon . '</div>';
                $html .= '<div class="_user-drop-zone-print-button" onclick="_editFormContentMedia({elem: this, id: ' . $row->id . '});" title="Мэдээлэл засах"><i class="fa fa-edit"></i></div>';
                $html .= '<div class="_user-drop-zone-show-button" onclick="_showContentMedia({elem: this, id: ' . $row->id . '});" title="Томоор харах"><i class="fa fa-external-link"></i></div>';
                $html .= '<div class="_user-drop-zone-delete-button" onclick="_deleteContentMedia({elem: this, id: ' . $row->id . '});" title="Хавсралт файл устгах"><i class="fa fa-trash-o"></i></div>';
                $html .= '</div>';
                $html .= '</div>';
            }
        }

        return '<div class="mt-2">' . $html . '</div>';
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
                'id' => getUID('content_media'),
                'mod_id' => $this->input->post('modId'),
                'cont_id' => $this->input->post('contId'),
                'title' => $this->input->post('contentMediaTitle'),
                'intro_text' => $this->input->post('contentMediaIntroText'),
                'pic' => ($this->input->post('contentMediaPic') != '' ? $this->input->post('contentMediaPic') : $this->input->post('contentMediaOldPic')),
                'attach_file' => ($this->input->post('contentMediaAttachFile') != '' ? $this->input->post('contentMediaAttachFile') : $this->input->post('contentMediaOldAttachFile')),
                'pic_mime_type' => ($this->input->post('contentMediaPicMimeType') != '' ? $this->input->post('contentMediaPicMimeType') : $this->input->post('contentMediaOldPicMimeType')),
                'pic_file_size' => ($this->input->post('contentMediaPicSize') != '' ? $this->input->post('contentMediaPicSize') : $this->input->post('contentMediaOldPicSize')),
                'attach_file_mime_type' => ($this->input->post('contentMediaAttachFileMimeType') != '' ? $this->input->post('contentMediaAttachFileMimeType') : $this->input->post('contentMediaOldAttachFileMimeType')),
                'attach_file_size' => ($this->input->post('contentMediaAttachFileSize') != '' ? $this->input->post('contentMediaAttachFileSize') : $this->input->post('contentMediaOldAttachFileSize')),
                'is_active' => $this->input->post('contentMediaIsActive'),
                'order_num' => getOrderNum(array('table' => 'content_media', 'field' => 'order_num')),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'media_type_id' => $this->input->post('masterMediaTypeId'),
                'created_user_id' => $this->session->userdata['adminUserId'],
                'modified_user_id' => $this->session->userdata['adminUserId'],
                'lang_id' => $this->session->userdata['adminLangId']
            )
        );
        if ($this->db->insert_batch($this->db->dbprefix . 'content_media', $data)) {
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
            'cont_id' => $this->input->post('contId'),
            'title' => $this->input->post('contentMediaTitle'),
            'intro_text' => $this->input->post('contentMediaIntroText'),
            'pic' => ($this->input->post('contentMediaPic') != '' ? $this->input->post('contentMediaPic') : $this->input->post('contentMediaOldPic')),
            'attach_file' => ($this->input->post('contentMediaAttachFile') != '' ? $this->input->post('contentMediaAttachFile') : $this->input->post('contentMediaOldAttachFile')),
            'pic_mime_type' => ($this->input->post('contentMediaPicMimeType') != '' ? $this->input->post('contentMediaPicMimeType') : $this->input->post('contentMediaOldPicMimeType')),
            'pic_file_size' => ($this->input->post('contentMediaPicSize') != '' ? $this->input->post('contentMediaPicSize') : $this->input->post('contentMediaOldPicSize')),
            'attach_file_mime_type' => ($this->input->post('contentMediaAttachFileMimeType') != '' ? $this->input->post('contentMediaAttachFileMimeType') : $this->input->post('contentMediaOldAttachFileMimeType')),
            'attach_file_size' => ($this->input->post('contentMediaAttachFileSize') != '' ? $this->input->post('contentMediaAttachFileSize') : $this->input->post('contentMediaOldAttachFileSize')),
            'is_active' => $this->input->post('contentMediaIsActive'),
            'order_num' => $this->input->post('orderNum'),
            'modified_date' => date('Y-m-d H:i:s'),
            'media_type_id' => $this->input->post('masterMediaTypeId'),
            'modified_user_id' => $this->session->userdata['adminUserId']);

        $this->db->where('id', $this->input->post('contentMediaId'));

        if ($this->db->update($this->db->dbprefix . 'content_media', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model($param = array('uploadPath' => '',)) {

        $query = $this->db->query('
            SELECT 
                CM.mod_id,
                CM.pic,
                CM.attach_file
            FROM `gaz_content_media` AS CM
            WHERE 1 = 1 AND CM.id = ' . $param['selectedId']);
        $row = $query->row();

        $this->slog->log_model(array(
            'modId' => $row->mod_id,
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_DELETE,
            'data' => json_encode($row)));

        $this->simage->removeUploadImage_model(array('uploadImage' => $row->pic, 'uploadPath' => $param['uploadPath']));
        $this->sfile->removeUploadFile_model(array('uploadFile' => $row->attach_file, 'uploadPath' => $param['uploadPath']));

        $this->db->where('id', $param['selectedId']);
        if ($this->db->delete($this->db->dbprefix . 'content_media')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function getListData_model($param = array('modId' => 0, 'contId' => 0)) {


        $this->queryString = $this->getString = '';

        $this->query = $this->db->query('
            SELECT 
                CM.id,
                CM.mod_id,
                CM.cont_id,
                CM.title,
                CM.intro_text,
                CM.pic,
                CM.attach_file,
                CM.mime_type,
                CM.is_active,
                CM.is_active_date,
                CM.param,
                CM.order_num,
                CM.created_date,
                CM.modified_date,
                CM.media_type_id,
                CM.created_user_id,
                CM.modified_user_id,
                CM.lang_id,
                CMT.title AS content_media_type_title
            FROM `gaz_content_media` AS CM
            INNER JOIN `gaz_content_media_type` CMT ON CM.media_type_id = CMT.id
            WHERE 1 = 1 ' . $this->queryString . ' AND CM.cont_id = ' . $param['contId'] . ' AND CM.mod_id = ' . $param['modId'] . ' 
            ORDER BY CM.order_num DESC');


        if ($this->query->num_rows() > 0) {

            return $this->query->result();
        }
        return false;
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $query = $this->db->query('
            SELECT 
                CM.id,
                CM.mod_id,
                CM.cont_id,
                CM.title,
                CM.intro_text,
                CM.pic,
                CM.attach_file,
                CM.pic_mime_type,
                CM.pic_file_size,
                CM.attach_file_mime_type,
                CM.attach_file_size,
                CM.created_date,
                CM.modified_date,
                CM.media_type_id,
                CM.created_user_id,
                CM.modified_user_id,
                CM.lang_id
            FROM `gaz_content_media` AS CM
            WHERE CM.id = ' . $param['selectedId']);

        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return self::addFormData_model();
    }

}
