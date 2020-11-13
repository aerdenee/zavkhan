<?php

class SnifsSendPhoto_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();

        $this->load->model('Simage_model', 'simage');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('Spermission_model', 'permission');
        $this->load->model('Suser_model', 'user');

        $this->modId = 78;
        $this->catId = 393;

        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->isActiveDepartment = 'is_active_nifs_send_photo';
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => $this->modId,
            'cat_id' => $this->catId,
            'department_id' => 0,
            'address' => '',
            'title' => '',
            'description' => '',
            'pic' => 'default.svg',
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'is_active' => 1,
            'order_num' => getOrderNum(array('table' => 'nifs_send_photo', 'field' => 'order_num'))
        )));
    }

    public function editFormData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                NSP.id,
                NSP.mod_id,
                NSP.cat_id,
                NSP.department_id,
                NSP.address,
                NSP.title,
                NSP.description,
                (case when (NSP.pic is null or NSP.pic = \'\') then \'default.svg\' else concat(\'s_\', NSP.pic) end) as pic,
                NSP.created_date,
                NSP.modified_date,
                NSP.created_user_id,
                NSP.modified_user_id,
                NSP.is_active,
                NSP.order_num
            FROM `gaz_nifs_send_photo` AS NSP
            WHERE NSP.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function listsCount_model($param = array()) {

        $queryString = '';

        $this->auth = $param['auth'];

        if ($this->auth->our->read and $this->auth->your->read) {

            $queryString .= '';
        } else if ($this->auth->our->read and !$this->auth->your->read) {

            $queryString .= ' AND NSP.created_user_id = ' . $this->session->adminUserId;
        } else {

            $queryString .= ' AND NSP.created_user_id = -1';
        }

        if ($param['departmentId'] != 0) {

            $queryString .= ' AND NSP.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
        } else {
            $queryString .= ' AND NSP.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND (LOWER(NSP.address) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NSP.description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {
            $queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(NSP.created_date) AND \'' . $param['outDate'] . '\' >= DATE(NSP.created_date)';
        } elseif ($param['inDate'] != '' and $param['outDate'] == '') {
            $queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(NSP.created_date)';
        } elseif ($param['inDate'] == '' and $param['outDate'] != '') {
            $queryString .= ' AND \'' . $param['outDate'] . '\' >= DATE(NSP.created_date)';
        }

        $query = $this->db->query('
            SELECT 
                NSP.id
            FROM `gaz_nifs_send_photo` AS NSP 
            WHERE 1 = 1 ' . $queryString . ' AND NSP.mod_id = ' . $param['modId'] . ' 
            ORDER BY NSP.id DESC');

        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {


        $data = array();

        $queryString = $getString = '';

        $this->auth = $param['auth'];

        if ($this->auth->our->read and $this->auth->your->read) {
            $queryString .= '';
        } elseif ($this->auth->our->read and !$this->auth->your->read) {
            $queryString .= ' AND NSP.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NSP.created_user_id = -1';
        }

        if ($param['departmentId'] != 0) {

            $queryString .= ' AND NSP.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
        } else {
            $queryString .= ' AND NSP.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND (LOWER(NSP.address) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NSP.description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
            $getString .= form_hidden('keyword', $param['keyword']);
        }

        $getString .= form_hidden('inDate', $param['inDate']);
        $getString .= form_hidden('outDate', $param['outDate']);

        if ($param['inDate'] != '' and $param['outDate'] != '') {
            $queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(NSP.created_date) AND \'' . $param['outDate'] . '\' >= DATE(NSP.created_date)';
        } elseif ($param['inDate'] != '' and $param['outDate'] == '') {
            $queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(NSP.created_date)';
        } elseif ($param['inDate'] == '' and $param['outDate'] != '') {
            $queryString .= ' AND \'' . $param['outDate'] . '\' >= DATE(NSP.created_date)';
        }
//                (case when (NSP.pic is null or NSP.pic = \'\') then \'default.svg\' else concat(\'s_\', NSP.pic) end) as pic,
        $query = $this->db->query('
            SELECT 
                NSP.id,
                NSP.created_user_id,
                (case when (NSP.pic is null or NSP.pic = \'\') then \'<img src="' . UPLOADS_NIFS_SEND_PHOTO_PATH . 'default.svg" style="width:100%;">\' else (case when (NSP.pic = \'<img src="' . UPLOADS_NIFS_SEND_PHOTO_PATH . 'default.svg" style="width:100%;">\') then \'<img src="' . UPLOADS_NIFS_SEND_PHOTO_PATH . 'default.svg" style="width:100%;">\' else concat(\'<img src="' . UPLOADS_NIFS_SEND_PHOTO_PATH . 's_\', NSP.pic, \'" style="width:100%;">\') end) end) as pic,
                CONCAT(\'<strong>\', NSP.address, \'</strong> \', NSP.description, \' \', DATE(NSP.created_date)) AS description,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname, \' \', HPD.title, \' \', HPP.title) AS full_name,
                CAT.title AS cat_title
            FROM `gaz_nifs_send_photo` AS NSP
            LEFT JOIN `gaz_category` AS CAT ON NSP.cat_id = CAT.id
            LEFT JOIN `gaz_user` AS U ON NSP.created_user_id = U.id
            LEFT JOIN `gaz_hr_people` AS HP ON U.people_id = HP.id
            INNER JOIN `gaz_hr_people_position` AS HPP ON HPP.id = HP.position_id 
            INNER JOIN `gaz_hr_people_department` AS HPD ON HPD.id = HP.department_id
            WHERE 1 = 1 ' . $queryString . ' AND NSP.mod_id = ' . $param['modId'] . ' 
            ORDER BY NSP.order_num DESC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);

        if ($query->num_rows() > 0) {
            $i = 1;
            foreach ($query->result() as $key => $row) {

                $html = '<div class="list-icons">';
                if (($this->auth->our->update and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->update and $row->created_user_id != $this->session->adminUserId)) {
                    $html .= '<div onclick="_editFormNifsSendPhoto({elem: this, id:' . $row->id . '});" class="list-icons-item" title="Засах"><i class="icon-pencil7"></i></div>';
                } else {
                    $html .= '<div class="list-icons-item disabled" title="Засах"><i class="icon-pencil7"></i></div>';
                }

                if (($this->auth->our->delete == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->delete == 1 and $row->created_user_id != $this->session->adminUserId)) {
                    $html .= '<div onclick="_removeNifsSendPhoto({elem: this, id:' . $row->id . '});" class="list-icons-item" title="Устгах"><i class="icon-trash"></i></div>';
                } else {
                    $html .= '<div class="list-icons-item disabled" title="Устгах"><i class="icon-trash"></i></div>';
                }

                $html .= '</div>';

                array_push($data, array(
                    'id' => $row->id,
                    'create_number' => $i,
                    'created_user_id' => $row->created_user_id,
                    'pic' => $row->pic,
                    'description' => $row->description,
                    'full_name' => $row->full_name,
                    'cat_title' => $row->cat_title,
                    'config' => $html
                ));
                $i++;
            }
        } else {
            $data = false;
        }
        return array('data' => $data, 'search' => self::searchKeywordView_model());
    }

    public function insert_model($param = array()) {

        $this->param = array();

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        $data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'department_id' => $this->session->adminDepartmentId,
                'address' => $this->input->post('address'),
                'description' => $this->input->post('description'),
                'pic' => $this->input->post('pic'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'is_active' => $this->input->post('isActive'),
                'order_num' => $this->input->post('orderNum')
            )
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'nifs_send_photo', $data)) {

            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'type' => LOG_TYPE_UPDATE,
            'data' => json_encode($_POST)));
//
//        $this->simage->imageDelete_model(array(
//            'modId' => $this->input->post('modId'),
//            'selectedId' => $this->input->post('id'),
//            'uploadPath' => UPLOADS_NIFS_SEND_PHOTO_PATH,
//            'dbFieldName' => 'pic',
//            'fileName' => $this->input->post('oldAttachFile'),
//            'isMedia' => 0));
//
//        $this->simage->imageReSize_model(array(
//            'uploadPath' => UPLOADS_NIFS_SEND_PHOTO_PATH,
//            'sourceImage' => ($this->input->post('pic') != '' ? $this->input->post('pic') : $this->input->post('oldPic')),
//            'newImage' => CROP_SMALL . ($this->input->post('pic') != '' ? $this->input->post('pic') : $this->input->post('oldPic')),
//            'height' => SMALL_HEIGHT,
//            'width' => SMALL_WIDTH
//        ));

        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'department_id' => $this->session->adminDepartmentId,
            'address' => $this->input->post('address'),
            'description' => $this->input->post('description'),
            'pic' => ($this->input->post('pic') != '' ? $this->input->post('pic') : $this->input->post('oldPic')),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'is_active' => $this->input->post('isActive'),
            'order_num' => $this->input->post('orderNum')
        );

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'nifs_send_photo', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {

        $this->db->where('id', $this->input->post('id'));
        $query = $this->db->get($this->db->dbprefix . 'nifs_send_photo');
        $row = $query->row();

        $this->slog->log_model(array(
            'modId' => $row->mod_id,
            'type' => LOG_TYPE_UPDATE,
            'data' => json_encode($row)));

        $this->simage->removeUploadImage_model(array('uploadImage' => $row->pic, 'uploadPath' => '.' . UPLOADS_NIFS_SEND_PHOTO_PATH));

        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'nifs_send_photo')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }

        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
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

        if ($this->showResetBtn) {
            //' . Scrime::$path . $param['path'] . '/' . $param['modId'] . '
            $this->string .= ' <a href="javascript:;" onclick="_initNifsSendPhoto({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return ($this->showResetBtn ? '<td><div class="datagrid-btn-separator"></div></td><td class="_search-result-td"> Хайлтын үр дүн: ' . $this->string . '</td>' : '');
    }

    public function mListsCount_model($param = array('modId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                NSP.id
            FROM `gaz_nifs_send_photo` AS NSP
            WHERE NSP.created_user_id = ' . $param['userId']);

        return $this->query->num_rows();
    }

    public function mLists_model($param = array('modId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                NSP.id,
                NSP.address,
                NSP.description AS description,
                (case when (NSP.pic is null or NSP.pic = \'\') then \'' . base_url(UPLOADS_NIFS_SEND_PHOTO_PATH . 'default.svg') . '\' else (case when (NSP.pic = \'default.svg\') then \'' . base_url(UPLOADS_NIFS_SEND_PHOTO_PATH . 'default.svg') . '\' else concat(\'' . base_url(UPLOADS_NIFS_SEND_PHOTO_PATH) . 's_\', NSP.pic) end) end) as pic,
                NSP.created_date,
                NSP.modified_date,
                NSP.created_user_id,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name,
                NSP.modified_user_id,
                NSP.is_active,
                NSP.order_num,
                HPP.title AS position_title,
                HPD.title AS department_title
            FROM `gaz_nifs_send_photo` AS NSP
            LEFT JOIN `gaz_category` AS CAT ON NSP.cat_id = CAT.id
            LEFT JOIN `gaz_user` AS U ON NSP.created_user_id = U.id
            LEFT JOIN `gaz_hr_people` AS HP ON U.people_id = HP.id
            INNER JOIN `gaz_hr_people_position` AS HPP ON HPP.id = HP.position_id 
            INNER JOIN `gaz_hr_people_department` AS HPD ON HPD.id = HP.department_id
            WHERE NSP.created_user_id = ' . $param['userId'] . '
            ORDER BY NSP.id DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);
        if ($this->query->num_rows() > 0) {

            return $this->query->result();
        }

        return false;
    }

    public function mInsert_model($param = array('getUID' => 0)) {

        if ($this->input->post('pic')) {

            $getUID = getUID('nifs_send_photo');

            $data = str_replace('data:image/jpeg;base64,', '', $this->input->post('pic'));
            $data = str_replace(' ', '+', $data);
            $data = base64_decode($data);
            $fileName = getFileUID() . '.jpg';
            $file = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_NIFS_SEND_PHOTO_PATH . $fileName;
            file_put_contents($file, $data);


            $this->simage->imageReSize_model(array(
                'uploadPath' => UPLOADS_NIFS_SEND_PHOTO_PATH,
                'sourceImage' => $fileName,
                'newImage' => CROP_SMALL . $fileName,
                'height' => SMALL_HEIGHT,
                'width' => SMALL_WIDTH
            ));

            $data = array(
                array(
                    'id' => $getUID,
                    'mod_id' => 78,
                    'cat_id' => 393,
                    'department_id' => 0,
                    'address' => $this->input->post('address'),
                    'title' => '',
                    'description' => $this->input->post('description'),
                    'pic' => $fileName,
                    'created_date' => date('Y-m-d H:i:s'),
                    'modified_date' => date('Y-m-d H:i:s'),
                    'created_user_id' => $this->input->post('userId'),
                    'modified_user_id' => 0,
                    'is_active' => 1,
                    'order_num' => getOrderNum(array('table' => 'nifs_send_photo', 'field' => 'order_num'))
                )
            );



            if ($this->db->insert_batch('gaz_nifs_send_photo', $data)) {

                return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
            }
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

}
