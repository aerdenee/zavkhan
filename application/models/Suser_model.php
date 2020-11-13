<?php

class Suser_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->isActiveDepartment = 'is_active_control';
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'department_id' => 0,
            'people_id' => 0,
            'mod_id' => 22,
            'email' => '',
            'user' => '',
            'password' => '',
            'lname' => '',
            'fname' => '',
            'full_name' => '',
            'phone' => '',
            'access_type_id' => 1,
            'is_active' => 1,
            'created_date' => date('Y-m-d H:i:s'),
            'pic' => $this->simage->returnDefaultImage_model(array('table' => 'user')),
            'mod_id' => 22,
            'intro_text' => '',
            'order_num' => getOrderNum(array('table' => 'user', 'field' => 'order_num')),
            'visit_date' => '0000-00-00 00:00:00',
            'cat_id' => 0,
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'partner_id' => 0
        )));
    }

    public function editFormData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                U.id,
                U.department_id,
                U.people_id,
                U.email,
                U.user,
                U.lname,
                U.fname,
                CONCAT(SUBSTRING(U.lname, 1, 1), \'.\', U.fname) AS full_name,
                U.phone,
                U.access_type_id,
                U.is_active,
                U.created_date,
                (case when (U.pic is null or U.pic = \'\') then \'default.svg\' else (case when (U.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', U.pic) end) end) as pic,
                U.mod_id,
                U.intro_text,
                U.order_num,
                U.visit_date,
                U.cat_id,
                U.modified_date,
                U.created_user_id,
                U.modified_user_id,
                U.partner_id
            FROM `' . $this->db->dbprefix . 'user` AS U
            WHERE U.id = ' . $param['selectedId']);


        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function insert_model($param = array('pic' => '', 'getUID' => 0)) {

        if ($this->input->post('password1') == $this->input->post('password2') && $this->checkUserName_model(array('user' => $this->input->post('user'))) && $this->checkUserEmail_model(array('email' => $this->input->post('email')))) {
            $this->data = array(
                array(
                    'id' => $param['getUID'],
                    'department_id' => $this->input->post('userDepartmentId'),
                    'people_id' => $this->input->post('peopleId'),
                    'user' => mb_strtolower(trim($this->input->post('user'), ' '), 'utf-8'),
                    'password' => md5(trim($this->input->post('password1'), ' ')),
                    'email' => $this->input->post('email'),
                    'lname' => $this->input->post('lname'),
                    'fname' => $this->input->post('fname'),
                    'phone' => $this->input->post('phone'),
                    'access_type_id' => $this->input->post('accessTypeId'),
                    'created_date' => date('Y-m-d H:i:s'),
                    //'pic' => ($this->input->post('pic') ? $this->input->post('pic') : $this->input->post('oldPic')),
                    'mod_id' => $this->input->post('modId'),
                    'intro_text' => $this->input->post('introText'),
                    'order_num' => getOrderNum(array('table' => 'user', 'field' => 'order_num')),
                    'visit_date' => '0000-00-00 00:00:00',
                    'cat_id' => $this->input->post('catId'),
                    'modified_date' => date('Y-m-d H:i:s'),
                    'created_user_id' => $this->session->adminUserId,
                    'modified_user_id' => 0,
                    'partner_id' => $this->input->post('partnerId'),
                    'is_active' => 1
                )
            );

            if ($this->db->insert_batch($this->db->dbprefix . 'user', $this->data)) {
                return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
            }
        }

        return array('status' => 'danger', 'message' => 'Хадгалах үед алдаа гарлаа. Таны мэйл хаяг эсвэл нэвтрэх нэр давхардсан байна.');
    }

    public function update_model($param = array('pic' => '')) {

        $this->data = array(
            'email' => $this->input->post('email'),
            'lname' => $this->input->post('lname'),
            'fname' => $this->input->post('fname'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'access_type_id' => $this->input->post('accessTypeId'),
            //'pic' => ($this->input->post('pic') ? $this->input->post('pic') : $this->input->post('oldPic')),
            'mod_id' => $this->input->post('modId'),
            'order_num' => $this->input->post('orderNum'),
            'cat_id' => $this->input->post('catId'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'partner_id' => $this->input->post('partnerId')
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'user', $this->data)) {

            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function listsCount_model($param = array()) {

        $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and ! $auth->your->read) {
            $queryString .= ' AND U.created_user_id = ' . $this->session->userdata['adminUserId'];
        } else if (!$auth->our->read and ! $auth->your->read) {
            $queryString .= ' AND U.created_user_id = -1';
        }

        if ($this->session->userdata['adminAccessTypeId'] == 3) {
            
            if ($param['departmentId'] != 0) {

                $queryString .= ' AND U.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            }
            
        } else if ($this->session->userdata['adminAccessTypeId'] == 2) {
            
            if ($param['departmentId'] != 0) {

                $queryString .= ' AND U.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            } else {

                $queryString .= ' AND U.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->userdata['adminDepartmentId']) . ')';
            }
            
        } else {
            
            $queryString .= ' AND U.department_id = -1';
            
        }


        if ($param['lname'] != '') {
            $queryString .= ' AND LOWER(U.lname) LIKE LOWER(\'%' . $param['lname'] . '%\')';
        }

        if ($param['fname'] != '') {
            $queryString .= ' AND LOWER(U.fname) LIKE LOWER(\'%' . $param['fname'] . '%\')';
        }

        if ($param['phone'] != '') {
            $queryString .= ' AND LOWER(U.phone) LIKE LOWER(\'%' . $param['phone'] . '%\')';
        }

        if ($param['email'] != '') {
            $queryString .= ' AND LOWER(U.email) LIKE LOWER(\'%' . $param['email'] . '%\')';
        }

        $query = $this->db->query('
            SELECT 
                U.id 
            FROM `' . $this->db->dbprefix . 'user` AS U
            WHERE 1 = 1 ' . $queryString . ' AND U.id != ' . $this->session->adminUserId . ' GROUP BY U.id');

        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $data = array();

        $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and !$auth->your->read) {
            $queryString .= ' AND U.created_user_id = ' . $this->session->adminUserId;
        } else if (!$auth->our->read and !$auth->your->read) {
            $queryString .= ' AND U.created_user_id = -1';
        }

        if ($this->session->userdata['adminAccessTypeId'] == 3) {
            
            if ($param['departmentId'] != 0) {

                $queryString .= ' AND U.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            }
            
        } else if ($this->session->userdata['adminAccessTypeId'] == 2) {
            
            if ($param['departmentId'] != 0) {

                $queryString .= ' AND U.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            } else {

                $queryString .= ' AND U.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->userdata['adminDepartmentId']) . ')';
            }
            
        } else {
            
            $queryString .= ' AND U.department_id = -1';
            
        }

        if ($param['lname'] != '') {
            $queryString .= ' AND LOWER(U.lname) LIKE LOWER(\'%' . $param['lname'] . '%\')';
        }

        if ($param['fname'] != '') {
            $queryString .= ' AND LOWER(U.fname) LIKE LOWER(\'%' . $param['fname'] . '%\')';
        }

        if ($param['phone'] != '') {
            $queryString .= ' AND LOWER(U.phone) LIKE LOWER(\'%' . $param['phone'] . '%\')';
        }

        if ($param['email'] != '') {
            $queryString .= ' AND LOWER(U.email) LIKE LOWER(\'%' . $param['email'] . '%\')';
        }

        $query = $this->db->query('
            SELECT 
                U.id,
                U.email,
                (case when (U.pic is null or U.pic = \'\') then \'default.svg\' else (case when (U.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', U.pic) end) end) as pic,
                U.lname,
                U.fname,
                CONCAT(SUBSTR(U.lname,1,1), \'.\', U.fname) AS full_name,
                U.phone,
                IF(U.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                U.access_type_id,
                UAT.title AS access_type_title,
                U.mod_id,
                U.created_user_id,
                U.created_date,
                IF(U.token != \'\', "<span class=\"badge badge-success\"><span class=\"icon-mobile3\"></span></span>", "<span class=\"badge badge-secondary\"><span class=\"icon-mobile3\"></span></span>") AS token
            FROM `gaz_user` AS U 
            LEFT JOIN `gaz_user_access_type` AS UAT ON U.access_type_id = UAT.id
            WHERE 1 = 1 ' . $queryString . ' AND U.id != ' . $this->session->userdata['adminUserId'] . '
            ORDER BY U.id DESC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $row) {

                array_push($data, array(
                    'id' => $row->id,
                    'created_user_id' => $row->created_user_id,
                    'pic' => '<img src="' . UPLOADS_USER_PATH . $row->pic . '">',
                    'full_name' => $row->full_name,
                    'phone' => $row->phone,
                    'email' => $row->email,
                    'token' => $row->token,
                    'created_date' => $row->created_date,
                    'is_active' => $row->is_active
                ));
            }
        }

        return array('data' => $data, 'search' => self::searchKeywordView_model());
    }

    public function delete_model() {

        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'user')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }

        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                U.id,
                U.email,
                U.user,
                U.password,
                U.lname,
                U.fname,
                CONCAT(SUBSTRING(U.lname, 1, 1), \'.\', U.fname) AS full_name,
                U.sex,
                U.birthday,
                U.phone,
                U.access_type_id,
                U.is_active,
                U.created_date,
                (case when (U.pic is null or U.pic = \'\') then \'default-user.svg\' else concat(\'s_\', U.pic) end) as pic,
                U.mod_id,
                U.intro_text,
                U.order_num,
                U.visit_date,
                U.cat_id,
                U.modified_date,
                U.created_user_id,
                U.modified_user_id,
                U.partner_id,
                U.department_id,
                U.department_role_id,
                U.people_id
            FROM `gaz_user` AS U
            WHERE 
                1 = 1 AND U.id=' . $param['selectedId']);
//U.access_type_id IN(1,2,4)
        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }

        return false;
    }

    public function resetPassword_model() {

        $this->_return = '';

        if ($this->input->post('change-password')) {

            $this->data = array('password' => md5(trim($this->input->post('change-password'), ' ')));

            $this->db->where('id', $this->input->post('userId'));

            if ($this->db->update($this->db->dbprefix . 'user', $this->data)) {

                $this->_return = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
            } else {

                $this->_return = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
            }
        } else {

            $this->_return = array('status' => 'error', 'message' => 'Алдаа гарлаа');
        }

        return $this->_return;
    }

    public function changePasswordConfig_model() {

        $this->_return = '';

        if ($this->input->post('password1') == $this->input->post('password2')) {

            $this->data = array('password' => md5(trim($this->input->post('password1'), ' ')));

            $this->db->where('id', $this->input->post('id'));

            if ($this->db->update($this->db->dbprefix . 'user', $this->data)) {

                $this->_return = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
            } else {

                $this->_return = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
            }
        } else {

            $this->_return = array('status' => 'error', 'message' => 'Шинээр үүсгэж буй нууц үг ижил биш байна.');
        }

        return $this->_return;
    }

    public function searchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {


        $this->string = '';
        $this->showResetBtn = FALSE;


        if ($this->input->get('lname')) {
            $this->string .= '<span class="label label-default label-rounded">Овог: ' . $this->input->get('lname') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('fname')) {
            $this->string .= '<span class="label label-default label-rounded">Нэр: ' . $this->input->get('fname') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('phone')) {
            $this->string .= '<span class="label label-default label-rounded">Утас: ' . $this->input->get('phone') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('email')) {
            $this->string .= '<span class="label label-default label-rounded">Мэйл: ' . $this->input->get('email') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            //' . Scrime::$path . $param['path'] . '/' . $param['modId'] . '
            $this->string .= ' <a href="javascript:;" onclick="_initUser({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return ($this->showResetBtn ? '<div class="_search-result-inner"><form action="javascript:;" id="form-nifs-extra"> Хайлтын үр дүн: ' . $this->string . '</form></div>' : '');
    }

    public function checkUserName_model($param = array('user' => '')) {
        $this->query = $this->db->query('
            SELECT 
                id
            FROM `gaz_user` 
            WHERE user = \'' . $param['user'] . '\'');
        if ($this->query->num_rows() > 0) {
            return false;
        }
        return true;
    }

    public function checkUserEmail_model($param = array('email' => '')) {
        $this->query = $this->db->query('
            SELECT 
                id
            FROM `gaz_user` 
            WHERE email = \'' . $param['email'] . '\'');
        if ($this->query->num_rows() > 0) {
            return false;
        }
        return true;
    }

    public function controlUserDropDown_model($param = array('selectedId' => 0, 'name' => 'expertId')) {

        $this->html = $this->string = $this->queryString = '';

        if (isset($param['disabled']) and $param['disabled'] == true) {
            $this->string .= ' disabled="true"';
        }

        if (isset($param['required']) and $param['required'] == true) {
            $this->string .= ' required="true"';
        }

        if (isset($param['readonly']) and $param['readonly'] == true) {
            $this->string .= ' disabled="true"';
        }

        $this->query = $this->db->query('
            SELECT 
                U.id,
                U.lname,
                U.fname,
                CONCAT(SUBSTRING(U.lname, 1, 1), \'.\', U.fname) AS full_name
            FROM `gaz_user` AS U
            WHERE 
                U.access_type_id != 1 ' . $this->queryString . '
            ORDER BY U.order_num ASC');

        $this->html .= '<select name="' . $param['name'] . '" class="select2" ' . $this->string . '>';
        $this->html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';
        if ($this->query->num_rows() > 0) {
            foreach ($this->query->result() as $row) {
                $this->html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->full_name . '</option>';
            }
        }
        $this->html .= '</select>';

        return $this->html;
    }

    public function changePassword_model($param = array()) {

        $user = $this->getData_model(array('selectedId' => $this->session->adminUserId));

        if ($user->password == md5(trim($this->input->post('currentPassword'), ' '))) {

            if ($this->input->post('newPassword') == $this->input->post('confirmPassword')) {

                $this->data = array('password' => md5(trim($this->input->post('newPassword'), ' ')));

                $this->db->where('id', $this->session->adminUserId);

                if ($this->db->update($this->db->dbprefix . 'user', $this->data)) {

                    return array('status' => 'success', 'message' => 'Нууц үг амжилттай солигдлоо...');
                } else {

                    return array('status' => 'error', 'message' => 'Нууц үг солих үед алдаа гарлаа...');
                }
            } else {

                return array('status' => 'error', 'message' => 'Шинээр үүсгэж буй нууц үг ижил биш байна.');
            }
        } else {

            return array('status' => 'error', 'message' => 'Одоогийн хэрэглэж байгаа нууц үг буруу байна');
        }

        return false;
    }

    public function setPassword_model($param = array()) {

        if ($this->input->post('newPassword') == $this->input->post('confirmPassword')) {

            $this->data = array('password' => md5(trim($this->input->post('newPassword'), ' ')));

            $this->db->where('id', $this->input->post('userId'));

            if ($this->db->update($this->db->dbprefix . 'user', $this->data)) {

                return array('status' => 'success', 'message' => 'Нууц үг амжилттай солигдлоо...');
            } else {

                return array('status' => 'error', 'message' => 'Нууц үг солих үед алдаа гарлаа...');
            }
        }
        return array('status' => 'error', 'message' => 'Шинээр үүсгэж буй нууц үг ижил биш байна.');
    }

    public function dataUpdate_model($param = array()) {

        $query = $this->db->query('
                SELECT 
                    U.id, 
                    U.department_role_id, 
                    U.access_type_id
                FROM `gaz_user` AS U');

        foreach ($query->result() as $key => $row) {

            $accessTypeId = 1;

            if ($row->department_role_id == 1) { /* Улс */
                $accessTypeId = 3;
            }

            if ($row->department_role_id == 2) { /* Нийслэл, орон нутаг */
                $accessTypeId = 2;
            }

            $data = array('access_type_id' => $accessTypeId);

            $this->db->where('id', $row->id);
            if ($this->db->update('gaz_user', $data)) {

                echo '<pre>';
                var_dump($data);
                echo '</pre>';
            }
        }
    }

}
