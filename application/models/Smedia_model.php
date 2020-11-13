<?php

class Smedia_model extends CI_Model {

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
            'cat_id' => 0,
            'department_id' => 0,
            'title' => '',
            'link_title' => '',
            'url' => '',
            'target' => '_parent',
            'description' => '',
            'pic' => $this->simage->returnDefaultImage_model(array('table' => 'media')),
            'attach_file' => '',
            'custom' => '',
            'duration' => 0,
            'is_active' => 1,
            'media_type_id' => 1,
            'is_active_date' => date('Y-m-d H:i:s'),
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s'),
            'created_user_id' => $this->session->userdata['adminUserId'],
            'modified_user_id' => 0,
            'order_num' => getOrderNum(array('table' => 'media', 'field' => 'order_num')),
            'param' => '',
            'price' => 0,
            'partner_id' => 0,
            'lang_id' => 1
        )));
    }

    public function editFormData_model($param = array('contId' => 0, 'modId' => 0)) {

        $query = $this->db->query('
            SELECT 
                M.id,
                M.mod_id,
                M.cat_id,
                M.department_id,
                M.title,
                M.link_title,
                M.url,
                M.target,
                M.description,
                (case when (M.pic is null or M.pic = \'\' or M.pic = \'default.svg\') then \'' . $this->simage->returnDefaultImage_model(array('table' => 'media')) . '\' else concat(\'' . UPLOADS_MEDIA_PATH . 's_\', M.pic) end) as pic,
                M.attach_file,
                M.custom,
                M.duration,
                M.is_active,
                M.media_type_id,
                M.is_active_date,
                M.created_date,
                M.modified_date,
                M.created_user_id,
                M.modified_user_id,
                M.order_num,
                M.param,
                M.price,
                M.partner_id,
                M.lang_id
            FROM `gaz_media` AS M
            WHERE M.id = ' . $param['selectedId']);

        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return self::addFormData_model();
    }

    public function listsCount_model($param = array()) {

        $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and !$auth->your->read) {
            $queryString .= ' AND M.created_user_id = ' . $this->session->userdata['adminUserId'];
        } else if (!$auth->our->read and !$auth->your->read) {
            $queryString .= ' AND M.created_user_id = -1';
        }
        
        if ($param['partnerId'] != 0) {
            $queryString .= ' AND M.partner_id = ' . $param['partnerId'];
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND M.cat_id = ' . $param['catId'];
        }

        if ($param['peopleId'] != 0) {
            $queryString .= ' AND M.people_id = ' . $param['peopleId'];
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND (LOWER(M.title) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(M.description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {
            $queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(M.is_active_date) AND \'' . $param['outDate'] . '\' >= DATE(M.is_active_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {
            $queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(M.is_active_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {
            $queryString .= ' AND \'' . $param['outDate'] . '\' >= DATE(M.is_active_date)';
        }

        $query = $this->db->query('
            SELECT 
                M.id
            FROM `gaz_media` AS M 
            WHERE M.lang_id = ' . $this->session->userdata['adminLangId'] . $queryString . ' AND M.mod_id = ' . $auth->modId . '
            ORDER BY M.id DESC');

        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $data = array();
        $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and !$auth->your->read) {
            $queryString .= ' AND M.created_user_id = ' . $this->session->userdata['adminUserId'];
        } else if (!$auth->our->read and !$auth->your->read) {
            $queryString .= ' AND M.created_user_id = -1';
        }
        
        if ($param['partnerId'] != 0) {
            $queryString .= ' AND M.partner_id = ' . $param['partnerId'];
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND M.cat_id = ' . $param['catId'];
        }

        if ($param['peopleId'] != 0) {
            $queryString .= ' AND M.people_id = ' . $param['peopleId'];
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND (LOWER(M.title) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(M.description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {
            $queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(M.is_active_date) AND \'' . $param['outDate'] . '\' >= DATE(M.is_active_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {
            $queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(M.is_active_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {
            $queryString .= ' AND \'' . $param['outDate'] . '\' >= DATE(M.is_active_date)';
        }

        $query = $this->db->query('
            SELECT 
                M.id,
                M.mod_id,
                M.cat_id,
                C.title AS cat_title,
                M.department_id,
                M.title,
                M.link_title,
                M.url,
                M.target,
                M.description,
                (case when (M.pic is null or M.pic = \'\') then "<img src=\"' . $this->simage->returnDefaultImage_model(array('table' => 'media')) . '\">" else CONCAT("<img src=\"' . UPLOADS_MEDIA_PATH . CROP_SMALL . '", M.pic, "\">") end) as pic,
                M.custom,
                M.duration,
                IF(M.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                M.is_active_date,
                DATE(M.created_date) AS created_date,
                M.modified_date,
                M.created_user_id,
                M.modified_user_id,
                M.order_num,
                M.param,
                M.price,
                M.partner_id
            FROM `gaz_media` AS M
            INNER JOIN `gaz_category` AS C ON M.cat_id = C.id
            WHERE M.lang_id = ' . $this->session->userdata['adminLangId'] . $queryString . ' AND M.mod_id = ' . $auth->modId . '
            ORDER BY M.order_num DESC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $row) {

                array_push($data, array(
                    'id' => $row->id,
                    'created_user_id' => $row->created_user_id,
                    'pic' => $row->pic,
                    'title' => $row->title,
                    'cat_title' => $row->cat_title,
                    'created_date' => $row->created_date,
                    'order_num' => $row->order_num,
                    'is_active' => $row->is_active
                ));
            }
        }
        return array('data' => $data, 'search' => self::searchKeywordView_model());
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
                'id' => getUID('media'),
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'partner_id' => $this->input->post('partnerId'),
                'title' => $this->input->post('title'),
                'link_title' => $this->input->post('linkTitle'),
                'url' => $this->input->post('url'),
                'target' => $this->input->post('target'),
                'description' => $this->input->post('description'),
                'pic' => ($this->input->post('mediaPic') != '' ? $this->input->post('mediaPic') : $this->input->post('mediaOldPic')),
                'attach_file' => ($this->input->post('mediaAttachFile') != '' ? $this->input->post('mediaAttachFile') : $this->input->post('mediaOldAttachFile')),
                'custom' => $this->input->post('custom'),
                'duration' => $this->input->post('duration'),
                'is_active' => $this->input->post('isActive'),
                'media_type_id' => $this->input->post('masterMediaTypeId'),
                'is_active_date' => $this->input->post('isActiveDate'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'order_num' => getOrderNum(array('table' => 'media', 'field' => 'order_num')),
                'param' => '',
                'price' => $this->input->post('price'),
                'lang_id' => $this->session->adminLangId
            )
        );
        if ($this->db->insert_batch($this->db->dbprefix . 'media', $data)) {
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
            'partner_id' => $this->input->post('partnerId'),
            'title' => $this->input->post('title'),
            'link_title' => $this->input->post('linkTitle'),
            'url' => $this->input->post('url'),
            'target' => $this->input->post('target'),
            'description' => $this->input->post('description'),
            'pic' => ($this->input->post('mediaPic') != '' ? $this->input->post('mediaPic') : $this->input->post('mediaOldPic')),
            'attach_file' => ($this->input->post('mediaAttachFile') != '' ? $this->input->post('mediaAttachFile') : $this->input->post('mediaOldAttachFile')),
            'custom' => $this->input->post('custom'),
            'duration' => $this->input->post('duration'),
            'is_active' => $this->input->post('isActive'),
            'media_type_id' => $this->input->post('masterMediaTypeId'),
            'is_active_date' => $this->input->post('isActiveDate'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'order_num' => $this->input->post('orderNum'),
            'param' => $this->input->post('param'),
            'price' => $this->input->post('price'));

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'media', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model($param = array('uploadPath' => '',)) {

        $query = $this->db->query('
            SELECT 
                M.mod_id,
                M.pic,
                M.attach_file
            FROM `gaz_media` AS M
            WHERE 1 = 1 AND M.id = ' . $param['selectedId']);
        $row = $query->row();

        $this->slog->log_model(array(
            'modId' => $row->mod_id,
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_DELETE,
            'data' => json_encode($row)));

        $this->simage->removeUploadImage_model(array('uploadImage' => $row->pic, 'uploadPath' => $param['uploadPath']));
        $this->sfile->removeUploadFile_model(array('uploadFile' => $row->attach_file, 'uploadPath' => $param['uploadPath']));

        $this->db->where('id', $param['selectedId']);
        if ($this->db->delete($this->db->dbprefix . 'media')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                M.id,
                M.mod_id,
                M.cat_id,
                M.department_id,
                M.title,
                M.link_title,
                M.url,
                M.link_type_id,
                M.description,
                (case when (M.pic is null OR M.pic = \'\' OR M.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', M.pic) end) as pic,
                M.attach_file,
                M.custom,
                M.duration,
                M.is_active,
                M.media_type_id,
                M.is_active_date,
                M.created_date,
                M.modified_date,
                M.created_user_id,
                M.modified_user_id,
                M.order_num,
                M.param,
                M.price,
                M.partner_id,
                M.lang_id,
                CT.title AS media_type_title
            FROM `gaz_media` AS M
            INNER JOIN `gaz_media_type` CT ON M.media_type_id = CT.id
            WHERE M.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function searchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('inDate') and $this->input->get('outDate')) {
            $this->string .= '<span class="label label-default label-rounded">Бүртгэсэн огноо: ' . $this->input->get('inDate') . '-' . $this->input->get('outDate') . '</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('inDate')) {
            $this->string .= '<span class="label label-default label-rounded">Бүртгэсэн огноо: ' . $this->input->get('inDate') . ' хойш</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('outDate')) {
            $this->string .= '<span class="label label-default label-rounded">Бүртгэсэн огноо: ' . $this->input->get('outDate') . ' өмнөх</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('catId')) {
            $this->cat = $this->category->getData_model(array('selectedId' => $this->input->get('catId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->cat->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('partnerId')) {
            $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->get('partnerId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->partnerData->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            //' . Scrime::$path . $param['path'] . '/' . $param['modId'] . '
            $this->string .= ' <a href="javascript:;" onclick="_initMedia({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return ($this->showResetBtn ? '<td><div class="datagrid-btn-separator"></div></td><td class="_search-result-td"> Хайлтын үр дүн: ' . $this->string . '</td>' : '');
    }

}
