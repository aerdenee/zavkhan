<?php

class StourItinerary_model extends CI_Model {

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
            'food' => '',
            'accommodation' => json_encode(array()),
            'transportation' => json_encode(array()),
            'other' => json_encode(array()),
            'intro_text' => '',
            'pic' => 'default.svg',
            'attach_file' => '',
            'mime_type' => '',
            'file_size' => 0,
            'is_active' => 1,
            'is_active_date' => date('Y-m-d H:i:s'),
            'param' => '',
            'order_num' => getOrderNum(array('table' => 'tour_itinerary', 'field' => 'order_num')),
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s'),
            'media_type_id' => 1,
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'lang_id' => 1
        )));
    }

    public function editFormData_model($param = array('contId' => 0, 'modId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                TI.id,
                TI.mod_id,
                TI.cont_id,
                TI.title,
                TI.food,
                TI.accommodation,
                TI.transportation,
                TI.other,
                TI.intro_text,
                (case when (TI.pic is null OR TI.pic = \'\' OR TI.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', TI.pic) end) as pic,
                TI.attach_file,
                TI.mime_type,
                TI.file_size,
                TI.is_active,
                TI.is_active_date,
                TI.param,
                TI.order_num,
                TI.created_date,
                TI.modified_date,
                TI.media_type_id,
                TI.created_user_id,
                TI.modified_user_id,
                TI.lang_id
            FROM `gaz_tour_itinerary` AS TI
            WHERE TI.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model(array('contId' => $param['contId'], 'modId' => $param['modId']));
    }

    public function listsCount_model($param = array()) {

        $this->queryString = '';

        $this->auth = $param['auth'];

        if ($this->auth->our->read and $this->auth->your->read) {
            $this->queryString .= '';
        } else if ($this->auth->our->read and ! $this->auth->your->read) {
            $this->queryString .= ' AND CM.created_user_id = ' . $this->session->userdata['adminUserId'];
        } else if (!$this->auth->our->read and ! $this->auth->your->read) {
            $this->queryString .= ' AND CM.created_user_id = -1';
        }

        if ($param['partnerId'] != 0) {
            $this->queryString .= ' AND partner_id = ' . $param['partnerId'];
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND cat_id = ' . $param['catId'];
        }

        if ($param['authorId'] != 0) {
            $this->queryString .= ' AND author_id = ' . $param['authorId'];
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(CM.title) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(CM.intro_text) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(CM.full_text) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {
            $this->queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(CM.is_active_date) AND \'' . $param['outDate'] . '\' >= DATE(CM.is_active_date)';
        } elseif ($param['inDate'] != '' and $param['outDate'] == '') {
            $this->queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(CM.is_active_date)';
        } elseif ($param['inDate'] == '' and $param['outDate'] != '') {
            $this->queryString .= ' AND \'' . $param['outDate'] . '\' >= DATE(CM.is_active_date)';
        }

        $this->query = $this->db->query('
            SELECT 
                CM.id
            FROM `gaz_tour_itinerary` AS CM 
            WHERE CM.lang_id = ' . $this->session->userdata['adminLangId'] . $this->queryString . ' AND CM.cont_id = ' . $param['contId'] . ' AND CM.mod_id = ' . $param['modId'] . ' 
            ORDER BY CM.id DESC');

        return $this->query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $data = array();

        $this->queryString = $this->getString = '';
        $this->auth = $param['auth'];

        if ($this->auth->our->read and $this->auth->your->read) {
            $this->queryString .= '';
        } else if ($this->auth->our->read and ! $this->auth->your->read) {
            $this->queryString .= ' AND CM.created_user_id = ' . $this->session->userdata['adminUserId'];
        } else if (!$this->auth->our->read and ! $this->auth->your->read) {
            $this->queryString .= ' AND CM.created_user_id = -1';
        }

        if ($param['partnerId'] != 0) {
            $this->queryString .= ' AND CM.partner_id = ' . $param['partnerId'];
            $this->getString .= form_hidden('partnerId', $param['partnerId']);
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND CM.cat_id = ' . $param['catId'];
            $this->getString .= form_hidden('catId', $param['catId']);
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(CM.title) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(CM.intro_text) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(CM.full_text) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        $this->getString .= form_hidden('inDate', $param['inDate']);
        $this->getString .= form_hidden('outDate', $param['outDate']);

        if ($param['inDate'] != '' and $param['outDate'] != '') {
            $this->queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(CM.is_active_date) AND \'' . $param['outDate'] . '\' >= DATE(CM.is_active_date)';
        } elseif ($param['inDate'] != '' and $param['outDate'] == '') {
            $this->queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(CM.is_active_date)';
        } elseif ($param['inDate'] == '' and $param['outDate'] != '') {
            $this->queryString .= ' AND \'' . $param['outDate'] . '\' >= DATE(CM.is_active_date)';
        }

        $this->query = $this->db->query('
            SELECT 
                CM.id,
                CM.mod_id,
                CM.cont_id,
                CM.title,
                CM.intro_text,
                (case when (CM.pic is null OR CM.pic = \'\' OR CM.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', CM.pic) end) as pic,
                CM.attach_file,
                CM.mime_type,
                IF(CM.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                CM.is_active_date,
                CM.param,
                CM.order_num,
                DATE_FORMAT(CM.modified_date, \'%Y-%m-%d\') AS modified_date,
                CM.media_type_id,
                CM.created_user_id,
                CM.modified_user_id,
                CM.lang_id,
                CMT.title AS content_media_type_title
            FROM `gaz_tour_itinerary` AS CM
            INNER JOIN `gaz_content_media_type` CMT ON CM.media_type_id = CMT.id
            WHERE CM.lang_id = ' . $this->session->userdata['adminLangId'] . $this->queryString . ' AND CM.cont_id = ' . $param['contId'] . ' AND CM.mod_id = ' . $param['modId'] . '
            ORDER BY CM.order_num ASC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);


        if ($this->query->num_rows() > 0) {

            $i = 0;
            
            foreach ($this->query->result() as $key => $row) {

                if ($row->media_type_id == 1) {
                    $row->pic = UPLOADS_CONTENT_PATH . $row->pic;
                }
                if ($row->media_type_id == 2) {
                    $row->pic = UPLOADS_CONTENT_PATH . 'iconMp4.svg';
                }
                if ($row->media_type_id == 3) {
                    $row->pic = UPLOADS_CONTENT_PATH . 'iconYoutube.svg';
                }
                if ($row->media_type_id == 4) {
                    $row->pic = UPLOADS_CONTENT_PATH . 'iconDocument.svg';
                }
                array_push($data, array(
                    'id' => $row->id,
                    'number' => ++$i,
                    'created_user_id' => $row->created_user_id,
                    'pic' => '<img src="' . $row->pic . '" style="width:80px;">',
                    'title' => '<strong>Day ' . $row->order_num . ':</strong> ' . $row->title,
                    'cat_title' => '',
                    'modified_date' => $row->modified_date,
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

        $data = array(
            array(
                'id' => getUID('tour_itinerary'),
                'mod_id' => $this->input->post('modId'),
                'cont_id' => $this->input->post('contId'),
                'title' => $this->input->post('title'),
                'food' => $this->input->post('food'),
                'transportation' => json_encode($this->input->post('transportation')),
                'accommodation' => json_encode($this->input->post('accommodation')),
                'other' => json_encode($this->input->post('other')),
                'intro_text' => $this->input->post('mediaIntroText'),
                'pic' => $this->input->post('pic'),
                'attach_file' => $this->input->post('attachFile'),
                'mime_type' => $this->input->post('mimeType'),
                'file_size' => $this->input->post('fileSize'),
                'is_active' => $this->input->post('isActive'),
                'param' => '',
                'order_num' => $this->input->post('orderNum'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'media_type_id' => $this->input->post('mediaTypeId'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'lang_id' => $this->session->adminLangId
            )
        );
        if ($this->db->insert_batch($this->db->dbprefix . 'tour_itinerary', $data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'type' => LOG_TYPE_UPDATE,
            'data' => json_encode($_POST)));

        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cont_id' => $this->input->post('contId'),
            'title' => $this->input->post('title'),
            'food' => $this->input->post('food'),
            'transportation' => json_encode($this->input->post('transportation')),
            'accommodation' => json_encode($this->input->post('accommodation')),
            'other' => json_encode($this->input->post('other')),
            'intro_text' => $this->input->post('mediaIntroText'),
            'pic' => ($this->input->post('pic') != '' ? $this->input->post('pic') : $this->input->post('oldPic')),
            'attach_file' => ($this->input->post('attachFile') != '' ? $this->input->post('attachFile') : $this->input->post('oldAttachFile')),
            'mime_type' => ($this->input->post('mimeType') != '' ? $this->input->post('mimeType') : $this->input->post('oldMimeType')),
            'file_size' => $this->input->post('fileSize'),
            'is_active' => $this->input->post('isActive'),
            'param' => '',
            'order_num' => $this->input->post('orderNum'),
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s'),
            'media_type_id' => $this->input->post('mediaTypeId'),
            'modified_user_id' => $this->session->adminUserId);

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'tour_itinerary', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model($param = array('uploadPath' => '',)) {

        $this->query = $this->db->query('
            SELECT 
                CM.mod_id,
                CM.pic,
                CM.attach_file
            FROM `gaz_tour_itinerary` AS CM
            WHERE 1 = 1 AND CM.id = ' . $param['selectedId']);
        $row = $this->query->row();

        $this->slog->log_model(array(
            'modId' => $row->mod_id,
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_DELETE,
            'data' => json_encode($row)));

        $this->simage->removeUploadImage_model(array('uploadImage' => $row->pic, 'uploadPath' => $param['uploadPath']));
        $this->sfile->removeUploadFile_model(array('uploadFile' => $row->attach_file, 'uploadPath' => $param['uploadPath']));

        $this->db->where('id', $param['selectedId']);
        if ($this->db->delete($this->db->dbprefix . 'tour_itinerary')) {
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
            FROM `gaz_tour_itinerary` AS CM
            INNER JOIN `gaz_content_media_type` CMT ON CM.media_type_id = CMT.id
            WHERE 1 = 1 ' . $this->queryString . ' AND CM.cont_id = ' . $param['contId'] . ' AND CM.mod_id = ' . $param['modId'] . ' 
            ORDER BY CM.order_num DESC');


        if ($this->query->num_rows() > 0) {

            return $this->query->result();
        }
        return false;
    }

    public function getData_model($param = array('selectedId' => 0)) {

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
                CM.lang_id
            FROM `gaz_tour_itinerary` AS CM
            WHERE CM.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

}
