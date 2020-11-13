<?php

class Sstudent_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Sawardtype_model', 'awardType');
        $this->load->model('Scareer_model', 'career');
        $this->load->model('Scategory_model', 'category');
    }

    public function addFormData_model() {
        return array(
            'id' => 0,
            'mod_id' => 34,
            'cat_id' => 0,
            'code' => self::generateStudentCode_model(),
            'fname' => '',
            'lname' => '',
            'full_name' => '',
            'sex' => 1,
            'city_id' => 0,
            'soum_id' => 0,
            'street_id' => 0,
            'is_active' => 1,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'family_member_count' => 0,
            'email' => '',
            'phone' => '',
            'phone_2' => '',
            'mobile' => '',
            'address' => '',
            'birthday' => '2000-10-20',
            'order_num' => getOrderNum(array('table' => 'user', 'field' => 'order_num')),
            'pic' => '',
            'intro_text' => '',
            'facebook' => ''
        );
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                S.id,
                S.mod_id,
                S.cat_id,
                S.code,
                S.fname,
                S.lname,
                CONCAT(SUBSTRING(S.lname, 1, 1), \'.\', S.fname) AS full_name,
                S.sex,
                S.city_id,
                S.soum_id,
                S.street_id,
                S.is_active,
                S.created_date,
                S.modified_date,
                S.created_user_id,
                S.modified_user_id,
                S.family_member_count,
                S.email,
                S.phone,
                S.phone_2,
                S.mobile,
                S.address,
                S.birthday,
                S.order_num,
                S.pic,
                S.intro_text,
                S.facebook
            FROM `' . $this->db->dbprefix . 'student` AS S
            WHERE S.id = ' . $param['id']);


        if ($this->query->num_rows() > 0) {
            $this->result = $this->query->result();
            return (array) $this->result['0'];
        }
        return self::addFormData_model();
    }

    public function insert_model($param = array('pic' => '', 'getUID' => 0)) {

        $this->data = array(
            array(
                'id' => $param['getUID'],
                'pic' => $param['pic'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => 334,
                'code' => self::checkStudentCode_model(array('code' => $this->input->post('code'))),
                'fname' => $this->input->post('fname'),
                'lname' => $this->input->post('lname'),
                'sex' => $this->input->post('sex'),
                'city_id' => $this->input->post('cityId'),
                'soum_id' => $this->input->post('soumId'),
                'street_id' => $this->input->post('streetId'),
                'is_active' => $this->input->post('isActive'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => '0000-00-00 00:00:00',
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'family_member_count' => $this->input->post('familyMemberCount'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'phone_2' => $this->input->post('phone2'),
                'mobile' => $this->input->post('mobile'),
                'address' => $this->input->post('address'),
                'birthday' => $this->input->post('birthday'),
                'order_num' => getOrderNum(array('table' => 'user', 'field' => 'order_num')),
                'intro_text' => $this->input->post('introText'),
                'facebook' => $this->input->post('facebook')
            )
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'student', $this->data)) {
            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $this->result = array('status' => 'success', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $this->result;
    }

    public function update_model($param = array('pic' => '')) {

        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'pic' => $param['pic'],
            'code' => self::checkStudentCode_model(array('code' => $this->input->post('code'))),
            'fname' => $this->input->post('fname'),
            'lname' => $this->input->post('lname'),
            'sex' => $this->input->post('sex'),
            'city_id' => $this->input->post('cityId'),
            'soum_id' => $this->input->post('soumId'),
            'street_id' => $this->input->post('streetId'),
            'is_active' => $this->input->post('isActive'),
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'family_member_count' => $this->input->post('familyMemberCount'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'phone_2' => $this->input->post('phone2'),
            'mobile' => $this->input->post('mobile'),
            'address' => $this->input->post('address'),
            'birthday' => $this->input->post('birthday'),
            'order_num' => $this->input->post('orderNum'),
            'intro_text' => $this->input->post('introText'),
            'facebook' => $this->input->post('facebook')
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'student', $this->data)) {

            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {

            $this->result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }

        return $this->result;
    }

    public function listsCount_model($param = array()) {
        $this->queryString = $this->queryStringField = $this->queryStringJoinTable = '';
        $this->organization = 0;

        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND S.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND S.created_user_id = -1';
        }

        if ($param['cityId'] != 0) {
            $this->queryString .= ' AND S.city_id = ' . $param['cityId'];
        }
        if ($param['soumId'] != 0) {
            $this->queryString .= ' AND S.soum_id = ' . $param['soumId'];
        }
        if ($param['streetId'] != 0) {
            $this->queryString .= ' AND S.street_id = ' . $param['streetId'];
        }

        if ($this->input->get('birthday') != '') {
            $this->queryString .= ' AND \'' . $param['birthday'] . '\' >= DATE(S.birthday)';
        }

        if ($param['createdDate'] != '') {
            $this->queryString .= ' AND \'' . $param['createdDate'] . '\' >= DATE(S.created_date)';
        }
        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(S.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.address) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.phone_2) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.mobile) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.code) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.email) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }
        if ($param['code'] != '') {
            $this->queryString .= ' AND (LOWER(S.code) LIKE LOWER(\'%' . $param['code'] . '%\'))';
        }

        $this->query = $this->db->query('
            SELECT 
                S.id
            FROM `gaz_student` AS S 
            ' . $this->queryStringJoinTable . '
            WHERE 1 = 1 ' . $this->queryString . ' AND S.id != ' . $this->session->adminUserId . ' ORDER BY S.id DESC');

        return $this->query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {
        $this->payment = $this->price = 0;
        $this->queryString = $this->getString = $this->queryStringField = $this->queryStringJoinTable = '';

        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND S.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND S.created_user_id = -1';
        }

        if ($param['cityId'] != 0) {
            $this->queryString .= ' AND S.city_id = ' . $param['cityId'];
        }
        if ($param['soumId'] != 0) {
            $this->queryString .= ' AND S.soum_id = ' . $param['soumId'];
        }
        if ($param['streetId'] != 0) {
            $this->queryString .= ' AND S.street_id = ' . $param['streetId'];
        }

        if ($this->input->get('birthday') != '') {
            $this->queryString .= ' AND \'' . $param['birthday'] . '\' >= DATE(S.birthday)';
        }

        if ($param['createdDate'] != '') {
            $this->queryString .= ' AND \'' . $param['createdDate'] . '\' >= DATE(S.created_date)';
        }
        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(S.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.address) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.phone_2) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.mobile) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.code) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.email) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }
        if ($param['code'] != '') {
            $this->queryString .= ' AND (LOWER(S.code) LIKE LOWER(\'%' . $param['code'] . '%\'))';
        }

        $this->query = $this->db->query('
            SELECT 
                S.id,
                S.mod_id,
                S.cat_id,
                S.code,
                S.fname,
                S.lname,
                CONCAT(SUBSTRING(S.lname, 1, 1), \'.\', S.fname) AS full_name,
                S.sex,
                S.city_id,
                CITY.title_mn AS city_name,
                S.soum_id,
                SOUM.title_mn AS soum_name,
                S.street_id,
                STREET.title_mn AS street_name,
                S.is_active,
                S.created_date,
                S.modified_date,
                S.created_user_id,
                S.modified_user_id,
                S.family_member_count,
                S.email,
                S.phone,
                S.phone_2,
                S.mobile,
                S.address,
                S.birthday,
                S.order_num,
                S.pic,
                S.intro_text,
                S.facebook
            FROM `' . $this->db->dbprefix . 'student` AS S
            LEFT JOIN `' . $this->db->dbprefix . 'address` AS CITY ON S.city_id = CITY.id
            LEFT JOIN `' . $this->db->dbprefix . 'address` AS SOUM ON S.soum_id = SOUM.id
            LEFT JOIN `' . $this->db->dbprefix . 'address` AS STREET ON S.street_id = STREET.id
            WHERE 1 = 1 ' . $this->queryString . ' AND S.id != ' . $this->session->adminUserId . '
            ORDER BY S.id DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        $this->data = array();
        $this->data['html'] = '';
        $this->data['html'] .= form_open('', array('class' => 'form-horizontal', 'id' => 'form-reservation-export', 'enctype' => 'multipart/form-data'));
        $this->data['html'] .= form_hidden('modId', $param['modId']);
        $this->data['html'] .= form_hidden('limit', $param['limit']);
        $this->data['html'] .= form_hidden('page', $param['page']);
        $this->data['html'] .= form_hidden('our[\'create\']', $this->auth->our->create);
        $this->data['html'] .= form_hidden('our[\'read\']', $this->auth->our->read);
        $this->data['html'] .= form_hidden('our[\'update\']', $this->auth->our->update);
        $this->data['html'] .= form_hidden('our[\'delete\']', $this->auth->our->delete);
        $this->data['html'] .= form_hidden('your[\'read\']', $this->auth->your->read);
        $this->data['html'] .= form_hidden('your[\'update\']', $this->auth->your->update);
        $this->data['html'] .= form_hidden('your[\'delete\']', $this->auth->your->delete);
        $this->data['html'] .= $this->getString;

        $this->data['html'] .= '<div class="panel panel-flat">';
        $this->data['html'] .= '<div class="panel-heading">';
        $this->data['html'] .= '<h5 class="panel-title">' . $param['title'] . '</h5>';
        $this->data['html'] .= '<div class="heading-elements">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';
        $this->data['html'] .= '<li>' . anchor(Sstudent::$path . 'index/' . $param['modId'], '<i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i>', array()) . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="panel-body" style="padding-bottom:5px;">';
        $this->data['html'] .= '<div class="pull-left search-keyword-view">' . self::searchKeywordView_model(array('modId' => $param['modId'], 'path' => $param['path'])) . '</div>';
        $this->data['html'] .= '<div class="pull-right">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';

        if ($this->auth->our->create == 1) {
            $this->data['html'] .= '<li style="padding-right:10px;"><a href="javascript:;" class="btn btn-info btn-rounded btn-xs" onclick="_formStudent({modId:' . $param['modId'] . ',elem:this, mode:\'add\', id:0});"><i class="fa fa-plus"></i> Нэмэх</a>';
            //$this->data['html'] .= anchor(Sclass::$path . 'add/' . $param['modId'], '<i class="fa fa-plus"></i> Нэмэх', 'class="btn btn-info btn-rounded btn-xs"') . '</li>';
        } else {
            $this->data['html'] .= '<li style="padding-right:10px;"><a href="javascript:;" class="btn btn-info btn-rounded btn-xs" disabled="disabled"><i class="fa fa-plus"></i> Нэмэх</a></li>';
        }
        $this->data['html'] .= '<li style="padding-right:10px;">' . form_button('send', '<i class="fa fa-download"></i> Экспорт', 'class="btn btn-info btn-rounded btn-xs" onclick="_export({elem:this});" ' . ($this->query->num_rows() == 0 ? ' disabled="disabled"' : ''), 'button') . '</li>';
        $this->data['html'] .= '<li>' . form_button('send', '<i class="fa fa-search"></i> хайх', 'class="btn btn-info btn-rounded btn-xs" onclick="_advensedSearch({elem:this, modId: ' . $param['modId'] . '});"', 'button') . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        if ($this->query->num_rows() > 0) {

            $this->data['html'] .= '<div class="table-responsive">';
            $this->data['html'] .= '<table class="table table-bordered table-custom">';
            $this->data['html'] .= '<thead>';
            $this->data['html'] .= '<tr>';
            $this->data['html'] .= '<th style="width:30px;">#</th>';
            $this->data['html'] .= '<th style="width:100px;">Зураг</th>';
            $this->data['html'] .= '<th style="width:200px;">Овог, нэр</th>';
            $this->data['html'] .= '<th style="width:200px;">Холбоо барих</th>';
            $this->data['html'] .= '<th style="width:300px;">Сургалт</th>';
            $this->data['html'] .= '<th style="width:100px;">Төрсөн огноо</th>';
            $this->data['html'] .= '<th>Бусад мэдээлэл</th>';
            $this->data['html'] .= '<th style="width:100px;">Төлөв</th>';
            $this->data['html'] .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';

            $i = 1;
            foreach ($this->query->result() as $key => $row) {
                $this->data['html'] .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '" data-uid="' . $row->created_user_id . '">';
                $this->data['html'] .= '<td>' . $i . '</td>';
                //' . UPLOADS_USER_PATH . CROP_SMALL . $row->pic . '
                $this->data['html'] .= '<td class="context-menu-selected-row-student text-center"><img src="upload/user/student.jpg" style="width:50px;"></td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-student"><strong>' . $row->code . '</strong><br>' . $row->full_name . '<br></td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-student">' . ($row->mobile != '' ? $row->mobile . ', ' : '') . ($row->phone != '' ? $row->phone : '') . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-student">' . ($row->city_name != '' ? $row->city_name . ', ' : '') . ($row->soum_name != '' ? $row->soum_name . ', ' : '') . ($row->street_name != '' ? $row->street_name . ', ' : '') . $row->address . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-student text-center">' . $row->birthday . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-student">' . ($row->email != '' ? $row->email . ', ' : '') . ($row->facebook != '' ? $row->facebook . ', ' : '') . word_limiter($row->intro_text, 20) . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row text-center">' . (intval($row->is_active) == 1 ? '<span class="label bg-teal dropdown-toggle">Идэвхтэй</span>' : '<span class="label label-danger">Идэвхгүй</span>') . '</td>';
                $this->data['html'] .= '<td class="text-center">';
                $this->data['html'] .= '<ul class="icons-list">';
                if (($this->auth->our->update == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->update == 1 and $row->created_user_id != $this->session->adminUserId)) {
                    $this->data['html'] .= '<li><a href="javascript:;" onclick="_formStudent({modId: ' . $param['modId'] . ', elem: this, mode: \'edit\', id: ' . $row->id . '});"><i class="icon-pencil7"></i></a></li>';
                } else {
                    $this->data['html'] .= '<li><a href="javascript:;" class="disabled"><i class="icon-pencil7 disabled"></i></a></li>';
                }
                if (($this->auth->our->delete == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->delete == 1 and $row->created_user_id != $this->session->adminUserId)) {
                    $this->data['html'] .= '<li><a href="javascript:;" onclick="_removeItemStudent({modId:' . $row->mod_id . ', id:' . $row->id . ', elem:this});"><i class="icon-trash"></i></a></li>';
                } else {
                    $this->data['html'] .= '<li><a href="javascript:;" class="disabled"><i class="icon-trash"></i></a></li>';
                }
                $this->data['html'] .= '</ul>';
                $this->data['html'] .= '</td>';
                $this->data['html'] .= '</tr>';
                $i++;
            }

            $this->data['html'] .= '</tbody>';

            $this->data['html'] .= '</table>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<div class="panel-footer">';
            $this->data['html'] .= '<div class="heading-elements">';
            $this->data['html'] .= '<span class="heading-text text-semibold">Нийт ' . $param['rowCount'] . ' бичлэг байна</span>';
            $this->data['html'] .= '<div class="heading-btn pull-right">';
            $this->data['html'] .= $param['paginationHtml'];
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<a class="heading-elements-toggle"><i class="icon-more"></i></a>';
            $this->data['html'] .= '</div>';
        } else {
            $this->data['html'] .= '<div class="panel-body">';
            $this->data['html'] .= '<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Бичлэг байхгүй</div>';
            $this->data['html'] .= '</div>';
        }


        $this->data['html'] .= '</div>';
        $this->data['html'] .= form_close();
        return $this->data['html'];
    }

    public function delete_model() {

        $this->isDelete = true;

        foreach ($this->input->post('id') as $key => $id) {

            $this->query = $this->db->query('
            SELECT 
                S.id,
                S.pic
            FROM `' . $this->db->dbprefix . 'student` AS S
            WHERE S.id = ' . $this->input->post('id[' . $key . ']'));
            $this->row = $this->query->row();

            //tulbur tulsun bol oyutni medeelliig ustgah bolomjgui bh yostoi. Uuniig shalgaj bgaa heseg
            $this->queryFinance = $this->db->query('
                SELECT 
                    SF.id
                FROM `' . $this->db->dbprefix . 'student_finance` AS SF
                WHERE SF.student_id = ' . $this->input->post('id[' . $key . ']'));
            if ($this->queryFinance->num_rows() > 0) {
                $this->isDelete = false;
            }

            //Oyutan angid hamaaran burtgegdsen bol ustgah bolomjgui bh yostoi. Uuniig shalgaj bgaa heseg
            $this->queryClass = $this->db->query('
                SELECT 
                    SC.id
                FROM `' . $this->db->dbprefix . 'student_class` AS SC
                WHERE SC.student_id = ' . $this->input->post('id[' . $key . ']'));
            if ($this->queryClass->num_rows() > 0) {
                $this->isDelete = false;
            }

            if ($this->isDelete) {
                removeImageSource(array('fieldName' => $this->row->pic, 'path' => UPLOADS_USER_PATH));
                $this->db->where('id', $this->input->post('id[' . $key . ']'));
                $this->db->delete($this->db->dbprefix . 'student');
            }
        }
        if ($this->isDelete) {
            return array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай устгалаа');
        } else {
            return array('status' => 'error', 'title' => 'Анхааруулга', 'message' => 'Устгах боломжгүй');
        }
        
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $query = $this->db->query('
            SELECT 
                S.id,
                S.mod_id,
                S.cat_id,
                S.code,
                S.fname,
                S.lname,
                CONCAT(SUBSTRING(S.lname, 1, 1), \'.\', S.fname) AS full_name,
                S.sex,
                S.city_id,
                S.soum_id,
                S.street_id,
                S.is_active,
                S.created_date,
                S.modified_date,
                S.created_user_id,
                S.modified_user_id,
                S.family_member_count,
                S.email,
                S.phone,
                S.phone_2,
                S.mobile,
                S.address,
                S.birthday,
                S.order_num,
                S.pic
            FROM `gaz_student` AS S
            WHERE 
                S.is_active = 1 AND S.id=' . $param['selectedId']);

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return false;
    }

    public function searchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {


        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('cityId') != false and $this->input->get('soumId') != false and $this->input->get('streetId') != false) {

            $this->cityData = $this->address->getData_model(array('selectedId' => $this->input->get('cityId')));
            $this->soumData = $this->address->getData_model(array('selectedId' => $this->input->get('soumId')));
            $this->streetData = $this->address->getData_model(array('selectedId' => $this->input->get('streetId')));

            $this->string .= '<span class="label label-default label-rounded">' . $this->cityData->title . ', ' . $this->soumData->title . ', ' . $this->streetData->title . '</span>';
            $this->showResetBtn = TRUE;
        } elseif ($this->input->get('cityId') != 0 and $this->input->get('soumId') != 0 and $this->input->get('streetId') == 0) {

            $this->cityData = $this->address->getData_model(array('selectedId' => $this->input->get('cityId')));
            $this->soumData = $this->address->getData_model(array('selectedId' => $this->input->get('soumId')));

            $this->string .= '<span class="label label-default label-rounded">' . $this->cityData->title . ', ' . $this->soumData->title . '</span>';
            $this->showResetBtn = TRUE;
        } elseif ($this->input->get('cityId') != false and $this->input->get('soumId') == false and $this->input->get('streetId') == false) {
            $this->cityData = $this->address->getData_model(array('selectedId' => $this->input->get('cityId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->cityData->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('awardTypeId')) {
            $awardType = $this->awardType->getData_model(array('selectedId' => $this->input->get('awardTypeId')));
            $this->string .= '<span class="label label-default label-rounded">' . $awardType->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('administrativeMeasuresKeyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('administrativeMeasuresKeyword') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('careerId')) {
            $getCareerData = $this->career->getData_model(array('selectedId' => $this->input->get('careerId')));
            $this->string .= '<span class="label label-default label-rounded">' . $getCareerData->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('departmentCatId')) {
            $getCategoryData = $this->category->getData_model(array('selectedId' => $this->input->get('departmentCatId')));
            $this->string .= '<span class="label label-default label-rounded">' . $getCategoryData->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('birthday')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('birthday') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            $this->string .= ' <a href="' . Sstudent::$path . $param['path'] . '/' . $param['modId'] . '"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return $this->string;
    }

    public function export_model($param = array('modId' => 0, 'catId' => 0)) {
        $this->queryString = $this->getString = $this->queryStringField = $this->queryStringJoinTable = '';

        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND S.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND S.created_user_id = -1';
        }

        if ($param['cityId'] != 0) {
            $this->queryString .= ' AND S.city_id = ' . $param['cityId'];
        }
        if ($param['soumId'] != 0) {
            $this->queryString .= ' AND S.soum_id = ' . $param['soumId'];
        }
        if ($param['streetId'] != 0) {
            $this->queryString .= ' AND S.street_id = ' . $param['streetId'];
        }

        if ($this->input->get('birthday') != '') {
            $this->queryString .= ' AND \'' . $param['birthday'] . '\' >= DATE(S.birthday)';
        }

        if ($param['createdDate'] != '') {
            $this->queryString .= ' AND \'' . $param['createdDate'] . '\' >= DATE(S.created_date)';
        }
        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(S.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.address) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.phone_2) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.mobile) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.code) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.email) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }
        if ($param['code'] != '') {
            $this->queryString .= ' AND (LOWER(S.code) LIKE LOWER(\'%' . $param['code'] . '%\'))';
        }

        $this->query = $this->db->query('
            SELECT 
                S.id,
                S.mod_id,
                S.cat_id,
                S.code,
                S.fname,
                S.lname,
                CONCAT(SUBSTRING(S.lname, 1, 1), \'.\', S.fname) AS full_name,
                S.sex,
                S.city_id,
                S.soum_id,
                S.street_id,
                S.is_active,
                S.created_date,
                S.modified_date,
                S.created_user_id,
                S.modified_user_id,
                S.family_member_count,
                S.email,
                S.phone,
                S.phone_2,
                S.mobile,
                S.address,
                S.birthday,
                S.order_num,
                S.pic
            FROM `gaz_student` AS S
            WHERE 1 = 1 ' . $this->queryString . ' AND S.id != ' . $this->session->adminUserId . '
            ORDER BY S.id DESC');

        if ($this->query->num_rows() > 0) {
            return $this->query->result();
        }
        return false;
    }

    public function generateStudentCode_model() {
        $this->query = $this->db->query('
            SELECT 
                id
            FROM `gaz_student`
            WHERE created_date BETWEEN \'' . date('Y-m-d') . ' 00:00:00\' AND \'' . date('Y-m-d') . ' 23:59:59\'
            ORDER BY id DESC');

        if ($this->query->num_rows() > 0) {
            $this->row = $this->query->row();
            $number = intval($this->row->id) + 1;
            if ($number < 10) {
                $number = '00' . $number;
            } elseif ($number > 9 and $number < 100) {
                $number = '0' . $number;
            }
            return 'MJ' . date('Ymd') . $number;
        } else {
            return 'MJ' . date('Ymd001');
        }
    }

    public function checkStudentCode_model($param = array('code' => '')) {
        $this->query = $this->db->query('
            SELECT 
                id
            FROM `gaz_student`
            WHERE code = \'' . $param['code'] . '\'
            ORDER BY id DESC');

        if ($this->query->num_rows()) {
            return self::generateStudentCode_model();
        } else {
            return $param['code'];
        }
    }

    public function listsCountStudentClass_model($param = array()) {
        $this->queryString = $this->queryStringField = $this->queryStringJoinTable = '';
        $this->organization = 0;

        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND SC.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND SC.created_user_id = -1';
        }

        if ($param['createdDate'] != '') {
            $this->queryString .= ' AND \'' . $param['createdDate'] . '\' >= DATE(SC.created_date)';
        }

        $this->query = $this->db->query('
            SELECT 
                SC.id
            FROM `' . $this->db->dbprefix . 'student_class` AS SC 
            ' . $this->queryStringJoinTable . '
            WHERE 1 = 1 ' . $this->queryString . ' AND SC.student_id = ' . $param['studentId'] . ' ORDER BY SC.id DESC');

        return $this->query->num_rows();
    }

    public function listsStudentClass_model($param = array('modId' => 0, 'catId' => 0)) {
        $this->payment = $this->price = 0;
        $this->queryString = $this->getString = $this->queryStringField = $this->queryStringJoinTable = '';

        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND SC.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND SC.created_user_id = -1';
        }

        if ($param['createdDate'] != '') {
            $this->queryString .= ' AND \'' . $param['createdDate'] . '\' >= DATE(SC.created_date)';
        }

//        $this->query = $this->db->query('
//            SELECT 
//                SC.id,
//                SC.mod_id,
//                SC.cat_id,
//                SC.teacher_id,
//                SC.student_id,
//                SC.class_id,
//                SC.status_id,
//                ST.title_mn AS status_title,
//                SC.created_date,
//                SC.modified_date,
//                SC.created_user_id,
//                SC.modified_user_id,
//                SC.order_num,
//                CAT.title_mn AS title,
//                C.start_date,
//                C.end_date,
//                CONCAT(SUBSTRING(U.lname_mn, 1, 1), \'.\', U.fname_mn) AS teacher_name,
//                SC.intro_text
//            FROM `' . $this->db->dbprefix . 'student_class` AS SC
//            INNER JOIN `' . $this->db->dbprefix . 'class` AS C ON SC.class_id = C.id
//            INNER JOIN `' . $this->db->dbprefix . 'category` AS CAT ON SC.class_id = CAT.id
//            INNER JOIN `' . $this->db->dbprefix . 'user` AS U ON SC.teacher_id = U.id
//            INNER JOIN `' . $this->db->dbprefix . 'status` AS ST ON SC.status_id = ST.id
//            WHERE 1 = 1 ' . $this->queryString . ' AND SC.student_id = ' . $param['studentId'] . '
//            ORDER BY SC.id DESC
//            LIMIT ' . $param['page'] . ', ' . $param['limit']);
        $this->query = $this->db->query('
            SELECT 
                SC.id,
                SC.mod_id,
                SC.cat_id,
                SC.teacher_id,
                CONCAT(SUBSTRING(U.lname_mn, 1, 1), \'.\', U.fname_mn) AS teacher_name,
                SC.student_id,
                SC.class_id,
                CAT.title_mn AS class_title,
                CL.start_date,
                CL.end_date,
                SC.status_id,
                ST.title_mn AS status_title,
                SC.created_user_id
            FROM `gaz_student_class` AS SC
            INNER JOIN `gaz_class` AS CL ON SC.class_id = CL.id
            INNER JOIN `gaz_category` AS CAT ON CL.cat_id = CAT.id
            INNER JOIN `gaz_user` AS U ON SC.teacher_id = U.id
            INNER JOIN `gaz_status` AS ST ON SC.status_id = ST.id
            WHERE SC.student_id = ' . $param['studentId'] . '
            ORDER BY SC.id DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        $this->data = array();
        $this->data['html'] = '';
        $this->data['html'] .= form_open('', array('class' => 'form-horizontal', 'id' => 'form-reservation-export', 'enctype' => 'multipart/form-data'));

        $this->data['html'] .= '<div class="clearfix" style="margin-top:10px;"></div>';
        $this->data['html'] .= '<div class="pull-left search-keyword-view">' . self::searchKeywordView_model(array('modId' => $param['modId'], 'path' => $param['path'])) . '</div>';
        $this->data['html'] .= '<div class="pull-right">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';
        $this->data['html'] .= '<li><a href="javascript:;" class="btn btn-info btn-rounded btn-xs" onclick="_formStudentClass({modId:' . $param['modId'] . ',elem:this, mode:\'addStudentClass\', id:0, studentId:' . $param['studentId'] . '});"><i class="fa fa-plus"></i> Нэмэх</a>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="clearfix"></div>';
        if ($this->query->num_rows() > 0) {

            $this->data['html'] .= '<div class="table-responsive">';
            $this->data['html'] .= '<table class="table table-bordered table-custom">';
            $this->data['html'] .= '<thead>';
            $this->data['html'] .= '<tr>';
            $this->data['html'] .= '<th style="width:30px;">#</th>';
            $this->data['html'] .= '<th style="width:200px;">Анги</th>';
            $this->data['html'] .= '<th>Ангийн багш</th>';
            $this->data['html'] .= '<th style="width:100px;">Эхлэх</th>';
            $this->data['html'] .= '<th style="width:100px;">Дуусах</th>';
            $this->data['html'] .= '<th style="width:100px;">Төлөв</th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';

            $i = 1;
            foreach ($this->query->result() as $key => $row) {
                $this->data['html'] .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '" data-student-id="' . $row->student_id . '" data-uid="' . $row->created_user_id . '">';
                $this->data['html'] .= '<td>' . $i . '</td>';
                //' . UPLOADS_USER_PATH . CROP_SMALL . $row->pic . '
                $this->data['html'] .= '<td class="context-menu-selected-row-student-class">' . $row->class_title . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-student-class">' . $row->teacher_name . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-student-class">' . $row->start_date . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-student-class">' . $row->end_date . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-student-class text-center">' . $row->status_title . '</td>';
                $this->data['html'] .= '</tr>';
                $i++;
            }

            $this->data['html'] .= '</tbody>';
            $this->data['html'] .= '</table>';
            $this->data['html'] .= '</div>';
        } else {
            $this->data['html'] .= '<br><div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Бичлэг байхгүй</div><br>';
        }


        $this->data['html'] .= form_close();
        return $this->data['html'];
    }

    public function addStudentClassFormData_model() {
        return array(
            'id' => 0,
            'mod_id' => 34,
            'cat_id' => 0,
            'teacher_id' => 0,
            'student_id' => 0,
            'class_id' => 0,
            'status_id' => 0,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'order_num' => getOrderNum(array('table' => 'student_class', 'field' => 'order_num'))
        );
    }

    public function editStudentClassFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                SC.id,
                SC.mod_id,
                SC.cat_id,
                SC.teacher_id,
                SC.student_id,
                SC.class_id,
                SC.status_id,
                SC.created_date,
                SC.modified_date,
                SC.created_user_id,
                SC.modified_user_id,
                SC.order_num
            FROM `' . $this->db->dbprefix . 'student_class` AS SC
            WHERE SC.id = ' . $param['id']);


        if ($this->query->num_rows() > 0) {
            return (array) $this->query->row();
        }
        return self::addStudentClassFormData_model();
    }

    public function insertStudentClass_model($param = array('getUID' => 0)) {

        $this->data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'teacher_id' => $this->input->post('teacherId'),
                'student_id' => $this->input->post('studentId'),
                'class_id' => $this->input->post('classId'),
                'status_id' => $this->input->post('statusId'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => '0000-00-00 00:00:00',
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'order_num' => $this->input->post('orderNum')
            )
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'student_class', $this->data)) {
            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $this->result = array('status' => 'success', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $this->result;
    }

    public function updateStudentClass_model($param = array('pic' => '')) {

        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'teacher_id' => $this->input->post('teacherId'),
            'student_id' => $this->input->post('studentId'),
            'class_id' => $this->input->post('classId'),
            'status_id' => $this->input->post('statusId'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'order_num' => $this->input->post('orderNum')
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'student_class', $this->data)) {

            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {

            $this->result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }

        return $this->result;
    }

    public function deleteStudentClass_model() {
        $this->isDelete = true;
        foreach ($this->input->post('id') as $key => $id) {
            $this->query = $this->db->query('
                SELECT 
                    SC.student_id,
                    SC.class_id
                FROM `' . $this->db->dbprefix . 'student_class` AS SC
                WHERE SC.id = ' . $this->input->post('id[' . $key . ']'));
            $this->row = $this->query->row();

            $this->queryFinance = $this->db->query('
                SELECT 
                    SF.id
                FROM `' . $this->db->dbprefix . 'student_finance` AS SF
                WHERE SF.class_id = ' . $this->row->class_id . ' AND SF.student_id = ' . $this->row->student_id);
            if ($this->queryFinance->num_rows() > 0) {
                $this->isDelete = false;
            }
            if ($this->isDelete) {
                $this->db->where('id', $this->input->post('id[' . $key . ']'));
                $this->db->delete($this->db->dbprefix . 'student_class');
            }
        }
        if ($this->isDelete) {
            return array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'title' => 'Анхааруулга', 'message' => 'Устгах боломжгүй');
    }

    public function listsStudentFinance_model($param = array('modId' => 0, 'catId' => 0)) {
        $this->payment = $this->price = 0;
        $this->query = $this->db->query('
            SELECT 
                SF.id,
                SF.class_id,
                CL.start_date,
                CL.end_date,
                CL.price,
                CAT.title_mn AS title
            FROM `' . $this->db->dbprefix . 'student_finance` AS SF 
            INNER JOIN `' . $this->db->dbprefix . 'class` AS CL ON SF.class_id = CL.id
            INNER JOIN `' . $this->db->dbprefix . 'category` AS CAT ON CL.cat_id = CAT.id
            WHERE SF.student_id = ' . $param['studentId'] . '
            GROUP BY SF.class_id');

        $this->data = array();
        $this->data['html'] = '';
        $this->data['html'] .= form_open('', array('class' => 'form-horizontal', 'id' => 'form-reservation-export', 'enctype' => 'multipart/form-data'));

        $this->data['html'] .= '<div class="clearfix" style="margin-top:10px;"></div>';
        $this->data['html'] .= '<div class="pull-left search-keyword-view"></div>';
        $this->data['html'] .= '<div class="pull-right">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';
        $this->data['html'] .= '<li><a href="javascript:;" class="btn btn-info btn-rounded btn-xs" onclick="_formStudentFinance({modId:' . $param['modId'] . ',elem:this, mode:\'addStudentFinance\', id:0, studentId:' . $param['studentId'] . '});"><i class="fa fa-plus"></i> Нэмэх</a>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="clearfix"></div>';
        if ($this->query->num_rows() > 0) {
            foreach ($this->query->result() as $key => $row) {
                $this->querySub = $this->db->query('
                    SELECT 
                        SF.id,
                        SF.student_id,
                        SF.class_id,
                        SF.income,
                        SF.outlet,
                        SF.created_user_id,
                        SF.modified_user_id,
                        SF.created_date,
                        SF.modified_date,
                        SF.mod_id,
                        SF.cat_id,
                        SF.order_num,
                        SF.is_active,
                        CONCAT(SUBSTRING(TEACHER.lname_mn, 1, 1), \'.\', TEACHER.fname_mn) AS teacher_name
                    FROM `' . $this->db->dbprefix . 'student_finance` AS SF
                    INNER JOIN `' . $this->db->dbprefix . 'class` AS CL ON SF.class_id = CL.id
                    INNER JOIN `' . $this->db->dbprefix . 'user` AS TEACHER ON CL.teacher_id = TEACHER.id
                    WHERE SF.is_active = 1 AND SF.class_id = ' . $row->class_id . ' AND SF.student_id = ' . $param['studentId'] . '
                    ORDER BY SF.created_date DESC');

                if ($this->querySub->num_rows() > 0) {
                    $this->dateInfo = dateBetweenInfo(array('dateIn' => $row->start_date, 'dateOut' => $row->end_date));
                    $this->data['html'] .= '<div style="font-weight:bold;">' . $row->title . ', Хугацаа: ' . $this->dateInfo['dateString'] . ' (' . $this->dateInfo['dateDiff'] . ' хоног)</div>';
                    $this->data['html'] .= '<div class="table-responsive" style="margin-bottom:20px;">';
                    $this->data['html'] .= '<table class="table table-bordered table-custom">';
                    $this->data['html'] .= '<thead>';
                    $this->data['html'] .= '<tr>';
                    $this->data['html'] .= '<th style="width:30px;">#</th>';
                    $this->data['html'] .= '<th style="width:200px;">Багш</th>';
                    $this->data['html'] .= '<th>Огноо</th>';
                    $this->data['html'] .= '<th style="width:100px;">Төлсөн</th>';
                    $this->data['html'] .= '<th style="width:100px;">Буцсан</th>';
                    $this->data['html'] .= '</tr>';
                    $this->data['html'] .= '</thead>';
                    $this->data['html'] .= '<tbody>';
                    $i = 1;
                    foreach ($this->querySub->result() as $keySub => $rowSub) {
                        $this->data['html'] .= '<tr data-mod-id="' . $rowSub->mod_id . '" data-id="' . $rowSub->id . '" data-student-id="' . $rowSub->student_id . '" data-class-id="' . $rowSub->class_id . '" data-uid="' . $rowSub->created_user_id . '">';
                        $this->data['html'] .= '<td style="width:30px;">' . $i . '</td>';
                        $this->data['html'] .= '<td style="width:200px;" class="context-menu-selected-row-student-finance">' . $rowSub->teacher_name . '</td>';
                        $this->data['html'] .= '<td class="context-menu-selected-row-student-finance">' . $rowSub->created_date . '</td>';
                        $this->data['html'] .= '<td style="width:100px;" class="context-menu-selected-row-student-finance text-right">' . $rowSub->income . '</td>';
                        $this->data['html'] .= '<td style="width:100px;" class="context-menu-selected-row-student-finance text-right">' . $rowSub->outlet . '</td>';
                        $this->data['html'] .= '</tr>';
                        $i++;
                    }
                    $this->data['html'] .= '</tbody>';
                    $this->data['html'] .= '</table>';
                    $this->data['html'] .= '</div>';
                }
            }
        } else {
            $this->data['html'] .= '<br><div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Бичлэг байхгүй</div><br>';
        }
        $this->data['html'] .= form_close();
        return $this->data['html'];
    }

    public function addStudentFinanceFormData_model() {
        return array(
            'id' => 0,
            'student_id' => 0,
            'class_id' => 0,
            'income' => 0,
            'outlet' => 0,
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'mod_id' => 38,
            'cat_id' => 0,
            'order_num' => getOrderNum(array('table' => 'student_class', 'field' => 'order_num')),
            'is_active' => 1
        );
    }

    public function editStudentFinanceFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                SF.id,
                SF.student_id,
                SF.class_id,
                SF.income,
                SF.outlet,
                SF.created_user_id,
                SF.modified_user_id,
                SF.created_date,
                SF.modified_date,
                SF.mod_id,
                SF.cat_id,
                SF.order_num,
                SF.is_active
            FROM `' . $this->db->dbprefix . 'student_finance` AS SF
            WHERE SF.id = ' . $param['id']);


        if ($this->query->num_rows() > 0) {
            return (array) $this->query->row();
        }
        return self::addStudentFinanceFormData_model();
    }

    public function studentFinanceSum_model($param = array('modId' => 0, 'catId' => 0)) {
        $this->income = $this->outlet = 0;

        $this->query = $this->db->query('
            SELECT 
                SF.income,
                SF.outlet
            FROM `' . $this->db->dbprefix . 'student_finance` AS SF
            WHERE SF.student_id = ' . $param['studentId'] . ' AND SF.class_id = ' . $param['classId']);


        if ($this->query->num_rows() > 0) {
            foreach ($this->query->result() as $key => $row) {
                $this->income = $this->income + $row->income;
                $this->outlet = $this->outlet + $row->outlet;
            }
        }

        return array('income' => $this->income, 'outlet' => $this->outlet);
    }

    public function insertStudentFinance_model($param = array('getUID' => 0)) {

        $this->data = array(
            array(
                'id' => $param['getUID'],
                'student_id' => $this->input->post('studentId'),
                'class_id' => $this->input->post('classId'),
                'income' => $this->input->post('income'),
                'outlet' => $this->input->post('outlet'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => '0000-00-00 00:00:00',
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'order_num' => $this->input->post('orderNum'),
                'is_active' => $this->input->post('isActive')
            )
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'student_finance', $this->data)) {
            self::updateStudentFinanceItem_model(array('mode' => 'insert', 'income' => $this->input->post('income'), 'outlet' => $this->input->post('outlet'), 'studentId' => $this->input->post('studentId'), 'classId' => $this->input->post('classId')));
            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $this->result = array('status' => 'success', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $this->result;
    }

    public function updateStudentFinance_model($param = array('pic' => '')) {

        $this->data = array(
            'student_id' => $this->input->post('studentId'),
            'class_id' => $this->input->post('classId'),
            'income' => $this->input->post('income'),
            'outlet' => $this->input->post('outlet'),
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'order_num' => $this->input->post('orderNum'),
            'is_active' => $this->input->post('isActive')
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'student_finance', $this->data)) {
            self::updateStudentFinanceItem_model(array('mode' => 'update', 'income' => $this->input->post('income'), 'outlet' => $this->input->post('outlet'), 'studentId' => $this->input->post('studentId'), 'classId' => $this->input->post('classId')));
            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {

            $this->result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }

        return $this->result;
    }

    public function deleteStudentFinance_model() {
        $data = $this->input->post('id');
        foreach ($data as $id) {
            $this->db->where('id', $id);
            $this->db->delete($this->db->dbprefix . 'student_finance');
        }
        return array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай устгалаа');
    }

    public function updateStudentFinanceItem_model($param = array()) {

        $this->query = $this->db->query('
            SELECT 
                SF.id,
                SF.income,
                SF.outlet
            FROM `' . $this->db->dbprefix . 'student_finance` AS SF
            WHERE SF.student_id = ' . $param['studentId'] . ' AND SF.class_id = ' . $param['classId']);

        $this->data = array(
            'income' => $param['income'],
            'outlet' => $param['outlet']
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'student_finance', $this->data)) {

            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {

            $this->result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }

        return $this->result;
    }

}
