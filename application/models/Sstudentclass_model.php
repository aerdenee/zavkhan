<?php

class Sstudentclass_model extends CI_Model {

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
            'teacher_id' => 0,
            'student_id' => 0,
            'class_id' => 0,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'order_num' => getOrderNum(array('table' => 'user', 'field' => 'order_num'))
        );
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                SC.id,
                SC.mod_id,
                SC.cat_id,
                SC.teacher_id,
                SC.student_id,
                SC.class_id,
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
        return self::addFormData_model();
    }

    public function insert_model($param = array('pic' => '', 'getUID' => 0)) {

        $this->data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => 0,
                'teacher_id' => $this->input->post('teacherId'),
                'student_id' => $this->input->post('studentId'),
                'class_id' => $this->input->post('classId'),
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

    public function update_model($param = array('pic' => '')) {

        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => 0,
            'teacher_id' => $this->input->post('teacherId'),
            'student_id' => $this->input->post('studentId'),
            'class_id' => $this->input->post('classId'),
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

    public function listsCount_model($param = array()) {
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
            WHERE 1 = 1 ' . $this->queryString . ' AND SC.id != ' . $this->session->adminUserId . ' ORDER BY SC.id DESC');

        return $this->query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {
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
        
        $this->query = $this->db->query('
            SELECT 
                SC.id,
                SC.mod_id,
                SC.cat_id,
                SC.teacher_id,
                SC.student_id,
                SC.class_id,
                SC.created_date,
                SC.modified_date,
                SC.created_user_id,
                SC.modified_user_id,
                SC.order_num,
                C.title,
                C.start_date,
                C.end_date,
                CONCAT(SUBSTRING(U.lname, 1, 1), \'.\', U.fname) AS teacher_name,
            FROM `' . $this->db->dbprefix . 'student_class` AS SC
            INNER JOIN `' . $this->db->dbprefix . 'class` AS C ON SC.class_id = C.id
            INNER JOIN `' . $this->db->dbprefix . 'user` AS U ON SC.teacher_id = U.id
            WHERE 1 = 1 ' . $this->queryString . ' AND SC.id != ' . $this->session->adminUserId . '
            ORDER BY SC.id DESC
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
            $this->data['html'] .= '<th style="width:200px;">Овог, нэр</th>';
            $this->data['html'] .= '<th style="width:100px;">Эхлэх</th>';
            $this->data['html'] .= '<th style="width:100px;">Дуусах</th>';
            $this->data['html'] .= '<th>Тайлбар</th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';

            $i = 1;
            foreach ($this->query->result() as $key => $row) {
                $this->data['html'] .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '" data-uid="' . $row->created_user_id . '">';
                $this->data['html'] .= '<td>' . $i . '</td>';
                //' . UPLOADS_USER_PATH . CROP_SMALL . $row->pic . '
                $this->data['html'] .= '<td class="context-menu-selected-row-student-class">' . $row->title . ' /' . $row->teacher_name . '/<br></td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-student-class">' . $row->start_date . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-student-class">' . $row->end_date . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-student-class">' . word_limiter($row->intro_text, 20) . '</td>';
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
        $data = $this->input->post('id');
        foreach ($data as $id) {
            $this->db->where('id', $id);
            $query = $this->db->get($this->db->dbprefix . 'student');
            $result = $query->result();
            $row = (Array) $result['0'];
            removeImageSource(array('fieldName' => $row['pic'], 'path' => UPLOADS_USER_PATH));
            $this->db->where('id', $id);
            $this->db->delete($this->db->dbprefix . 'user');
        }
        return array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай устгалаа');
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

}
