<?php

class Sstudentattendance_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Sawardtype_model', 'awardType');
        $this->load->model('Scareer_model', 'career');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Sclass_model', 'class');
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

    public function classList_model($param = array('modId' => 0, 'catId' => 0)) {
        $this->payment = $this->price = 0;
        $this->queryString = $this->querySubString = $this->getString = $this->queryStringField = $this->queryStringJoinTable = '';

        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND S.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND S.created_user_id = -1';
        }

        if ($param['startDate'] != NULL) {
            $this->queryString .= ' AND \'' . $param['startDate'] . '\' <= DATE(C.start_date)';
        }

        if ($param['endDate'] != NULL) {
            $this->queryString .= ' AND \'' . $param['endDate'] . '\' >= DATE(C.end_date)';
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
                CL.id,
                CL.mod_id,
                CL.cat_id,
                CAT.title_mn AS title,
                CL.teacher_id,
                CONCAT(SUBSTRING(U.lname_mn, 1, 1), \'.\', U.fname_mn) AS full_name,
                CL.start_date,
                CL.end_date,
                CL.price,
                CL.intro_text,
                CL.is_active,
                \'\' AS count,
                CL.created_user_id
            FROM `' . $this->db->dbprefix . 'class` AS CL 
            INNER JOIN `' . $this->db->dbprefix . 'category` AS CAT ON CL.cat_id = CAT.id
            INNER JOIN `' . $this->db->dbprefix . 'user` AS U ON CL.teacher_id = U.id
            WHERE CL.is_active = 1');

        $this->data = array();
        $this->data['html'] = '';
        $this->data['html'] .= form_open('', array('class' => 'form-vertical', 'id' => 'form-student-finance', 'enctype' => 'multipart/form-data'));
        $this->data['html'] .= form_hidden('modId', $param['modId']);
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
        $this->data['html'] .= '<h5 class="panel-title">Ирцийн мэдээлэл</h5>';
        $this->data['html'] .= '<div class="heading-elements">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';
        $this->data['html'] .= '<li>' . anchor(Sstudentattendance::$path . 'index/' . $param['modId'], '<i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i>', array()) . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="panel-body" style="padding-bottom:5px;">';
        $this->data['html'] .= '<div class="pull-left search-keyword-view"></div>';
        $this->data['html'] .= '<div class="pull-right">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';
        $this->data['html'] .= '<li>';
        $this->data['html'] .= '<div class="pull-left">';
        $this->data['html'] .= form_label('Эхлэх огноо', 'Эхлэх огноо', array('required' => 'required', 'class' => 'control-label pull-left', 'style' => 'margin-right:10px;', 'defined' => TRUE));
        $this->data['html'] .= form_input(array(
            'name' => 'startDate',
            'id' => 'startDate',
            'value' => date('Y-m-d'),
            'maxlength' => '10',
            'class' => 'form-control init-date pl-10',
            'required' => 'required'
        ));
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="pull-left pl-20">';
        $this->data['html'] .= form_label('Дуусах огноо', 'Дуусах огноо', array('required' => 'required', 'class' => 'control-label pull-left', 'style' => 'margin-right:10px;', 'defined' => TRUE));
        $this->data['html'] .= form_input(array(
            'name' => 'endDate',
            'id' => 'endDate',
            'value' => date('Y-m-d'),
            'maxlength' => '10',
            'class' => 'form-control init-date pl-10',
            'required' => 'required'
        ));
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="pull-left pl-10">';
        $this->data['html'] .= form_button('send', '<i class = "fa fa-search"></i> Хайх', 'class = "btn btn-info btn-rounded btn-xs" onclick = "_findStudentFinance(this);" ', 'button');
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="pull-left pl-10">';
        $this->data['html'] .= form_button('send', '<i class = "fa fa-search"></i> Дэлгэрэнгүй хайлт', 'class = "btn btn-info btn-rounded btn-xs" onclick = "_advensedSearchStudentFinance({elem:this, modId: ' . $param['modId'] . '});"', 'button');
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';

        if ($this->query->num_rows() > 0) {
            $this->data['html'] .= '<div class="table-responsive">';
            $this->data['html'] .= '<table class="table table-bordered table-custom">';
            $this->data['html'] .= '<thead>';
            $this->data['html'] .= '<tr>';
            $this->data['html'] .= '<th style="width:30px;">#</th>';
            $this->data['html'] .= '<th style="width:200px;">Анги</th>';
            $this->data['html'] .= '<th style="width:150px;">Багш</th>';
            $this->data['html'] .= '<th>Хуваарь</th>';
            $this->data['html'] .= '<th>Хугацаа</th>';
            $this->data['html'] .= '<th style="width:80px;">Оюутан</th>';
            $this->data['html'] .= '<th style="width:80px;">Төлбөр</th>';
            $this->data['html'] .= '<th style="width:100px;">Төлөв</th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';
            $i = 1;
            foreach ($this->query->result() as $key => $row) {
                $this->dateBetweenInfo = dateBetweenInfo(array('dateIn' => $row->start_date, 'dateOut' => $row->end_date));
                $this->data['html'] .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '" data-uid="' . $row->created_user_id . '">';
                $this->data['html'] .= '<td class="text-center">' . $i . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-student-class">' . $row->title . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-student-class">' . $row->full_name . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-student-class">' . $row->intro_text . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-student-class">' . $this->dateBetweenInfo['dateString'] . ' (' . $this->dateBetweenInfo['dateDiff'] . ' өдөр)</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-student-class">' . $row->count . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-student-class">' . $row->price . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-student-class">' . $row->is_active . '</td>';
                $this->data['html'] .= '</tr>';
                $i++;
            }

            $this->data['html'] .= '</tbody>';
            $this->data['html'] .= '</table>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<div class="panel-footer">';
            $this->data['html'] .= '<div class="heading-elements">';
            $this->data['html'] .= '<span class="heading-text text-semibold"></span>';
            $this->data['html'] .= '<div class="heading-btn pull-right">';
            $this->data['html'] .= '';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<a class="heading-elements-toggle"><i class="icon-more"></i></a>';
            $this->data['html'] .= '</div>';
        } else {
            $this->data['html'] .= '<div class="panel-body">';
            $this->data['html'] .= '<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Хайлт хийх утгаа сонгоод хайх товчийг дарна уу</div>';
            $this->data['html'] .= '</div>';
        }

        $this->data['html'] .= '</div>';
        $this->data['html'] .= form_close();
        return $this->data['html'];
    }

    public function dayAttendanceList_model($param = array('modId' => 0, 'catId' => 0)) {
        $this->payment = $this->price = 0;
        $this->queryString = $this->querySubString = $this->getString = $this->queryStringField = $this->queryStringJoinTable = '';

        $this->classData = $this->class->getData_model(array('selectedId' => $param['classId']));



        $this->data = array();
        $this->data['html'] = '';
        $this->data['html'] .= form_open('', array('class' => 'form-vertical', 'id' => 'form-student-finance', 'enctype' => 'multipart/form-data'));
        $this->data['html'] .= form_hidden('modId', $param['modId']);
        $this->data['html'] .= form_hidden('classId', $param['classId']);
        $this->data['html'] .= $this->getString;

        $this->data['html'] .= '<div class="panel panel-flat">';
        $this->data['html'] .= '<div class="panel-heading">';
        $this->data['html'] .= '<h5 class="panel-title">' . $this->classData->title . '</h5>';
        $this->data['html'] .= '<div class="heading-elements">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';
        $this->data['html'] .= '<li>' . anchor(Sstudentattendance::$path . 'index/' . $param['modId'], '<i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i>', array()) . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="panel-body" style="padding-bottom:5px;">';
        $this->data['html'] .= '</div>';

        $this->data['html'] .= '<div class="table-responsive">';
        $this->data['html'] .= '<table class="table table-bordered table-custom">';
        $this->data['html'] .= '<thead>';
        $this->data['html'] .= '<tr>';
        $this->data['html'] .= '<th style="width:30px;">#</th>';
        $this->data['html'] .= '<th style="width:210px;">Огноо</th>';
        $this->data['html'] .= '<th>Ирцийн мэдээлэл</th>';

        $this->begin = new DateTime($this->classData->start_date);
        $this->end = new DateTime($this->classData->end_date);
        $this->end = $this->end->modify('+1 day');
        $this->interval = new DateInterval('P1D');
        $this->daterange = new DatePeriod($this->begin, $this->interval, $this->end);

        $this->data['html'] .= '</tr>';
        $this->data['html'] .= '</thead>';
        $this->data['html'] .= '<tbody>';
        $i = 1;

        foreach ($this->daterange as $this->date) {
            $this->data['html'] .= '<tr data-mod-id="' . $param['modId'] . '" data-class-id="' . $param['classId'] . '">';
            $this->data['html'] .= '<td class="text-center">' . $i . '</td>';
            $this->data['html'] .= '<td class="context-menu-selected-row-studentattendance text-center">' . $this->date->format("Y сарын m сарын d өдөр") . '</td>';
            $this->data['html'] .= '<td class="context-menu-selected-row-studentattendance"><span style="cursor:pointer;" onclick="_studentAttendance({modId:' . $param['modId'] . ', classId:' . $param['classId'] . ', elem:this, date:\'' . $this->date->format("Y-m-d") . '\'});"><i class="icon-pencil7"></i> Ирц бүртгэх</span></td>';
            $this->data['html'] .= '</tr>';
            $i++;
        }




        $this->data['html'] .= '</tbody>';
        $this->data['html'] .= '</table>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="panel-footer">';
        $this->data['html'] .= '<div class="heading-elements">';
        $this->data['html'] .= '<span class="heading-text text-semibold"></span>';
        $this->data['html'] .= '<div class="heading-btn pull-right">';
        $this->data['html'] .= '';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<a class="heading-elements-toggle"><i class="icon-more"></i></a>';
        $this->data['html'] .= '</div>';



        $this->data['html'] .= form_close();
        return $this->data['html'];
    }

    public function attendanceList_model($param = array('modId' => 0, 'catId' => 0)) {

        $this->html = $this->getString = '';

        $this->classData = $this->class->getData_model(array('selectedId' => $param['classId']));

        $this->query = $this->db->query('
            SELECT 
                SC.id,
                SC.mod_id,
                SC.cat_id,
                SC.student_id,
                S.code,
                S.fname,
                S.lname,
                CONCAT(SUBSTRING(S.lname, 1, 1), \'.\', S.fname) AS full_name,
                S.sex,
                SC.created_date
            FROM `' . $this->db->dbprefix . 'student_class`  AS SC 
            INNER JOIN `' . $this->db->dbprefix . 'student` AS S ON SC.student_id = S.id
            WHERE SC.class_id = ' . $param['classId']);

        $this->html .= form_open('', array('class' => 'form-vertical', 'id' => 'form-student-attendance', 'enctype' => 'multipart/form-data'));
        $this->html .= form_hidden('modId', $param['modId']);
        $this->html .= form_hidden('classId', $param['classId']);
        $this->html .= form_hidden('date', $param['date']);
        $this->html .= $this->getString;

        $this->html .= '<div class="panel panel-flat">';
        $this->html .= '<div class="panel-heading">';
        $this->html .= '<h5 class="panel-title">' . $this->classData->title . ' сургалт, ' . date('Y оны m сарын d өдөр', strtotime($param['date'])) . '</h5>';
        $this->html .= '<div class="heading-elements">';
        $this->html .= '<ul class="list-inline heading-text">';
        $this->html .= '<li>' . anchor(Sstudentattendance::$path . 'index/' . $param['modId'], '<i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i>', array()) . '</li>';
        $this->html .= '</ul>';
        $this->html .= '</div>';
        $this->html .= '</div>';
        $this->html .= '<div class="panel-body" style="padding-bottom:5px;">';
        $this->html .= '</div>';
        if ($this->query->num_rows() > 0) {
            $this->html .= '<div class="table-responsive">';
            $this->html .= '<table class="table table-bordered table-custom">';
            $this->html .= '<thead>';
            $this->html .= '<tr>';
            $this->html .= '<th style="width:30px;">#</th>';
            $this->html .= '<th style="width:100px;">Код</th>';
            $this->html .= '<th style="width:150px;">Овог, нэр</th>';
            $this->html .= '<th style="width:100px;">Элссэн огноо</th>';
            $this->html .= '<th style="width:80px;">Нийт ирц</th>';
            $this->html .= '<th style="width:100px;" class="text-center">Суусан эсэх</th>';
            $this->html .= '<th class="text-center">Тайлбар</th>';
            $this->html .= '</tr>';
            $this->html .= '</thead>';
            $this->html .= '<tbody>';
            $i = 1;
            $this->getDataStudentAttendace = $this->getDataStudentAttendace_model(array('date' => $param['date'], 'classId' => $param['classId']));
            foreach ($this->query->result() as $key => $row) {
                //$this->dateBetweenInfo = dateBetweenInfo(array('dateIn' => $row->start_date, 'dateOut' => $row->end_date));
                $this->createdDate = explode(' ', $row->created_date);
                $this->html .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '">';
                $this->html .= '<td class="text-center">' . $i . '</td>';
                $this->html .= '<td class="context-menu-selected-row-studentattendance"><input type="hidden" name="studentId[]" value="' . $row->student_id . '">' . $row->code . '</td>';
                $this->html .= '<td class="context-menu-selected-row-studentattendance">' . $row->full_name . '</td>';
                $this->html .= '<td class="context-menu-selected-row-studentattendance">' . $this->createdDate['0'] . '</td>';
                $this->html .= '<td class="context-menu-selected-row-studentattendance text-center">0</td>';
                
                
                $this->html .= '<td class="context-menu-selected-row-studentattendance text-center">';
                $this->html .= '<label>';
                $this->html .= form_hidden('isActive[]', $this->getDataStudentAttendace['isActive' . $row->student_id]) . form_checkbox(array('name' => 'active', 'class' => 'checkbox', 'onclick' => '_setIsActiveStudentAttendance({elem:this})'), $this->getDataStudentAttendace['isActive' . $row->student_id], ($this->getDataStudentAttendace['isActive' . $row->student_id] == 1 ? true : false));
                $this->html .= 'Суусан </label>';
                $this->html .= '</td>';
                $this->html .= '<td class="context-menu-selected-row-studentattendance" style="padding:0;">' . form_input(array(
                            'name' => 'introText[]',
                            'value' => $this->getDataStudentAttendace['introText' . $row->student_id],
                            'maxlength' => '255',
                            'class' => 'form-control',
                            'required' => 'required',
                            'style' => 'height:41px; width:100%; border:none;'
                        )) . '</td>';
                $this->html .= '</tr>';
                $i++;
            }

            $this->html .= '</tbody>';
            $this->html .= '</table>';
            $this->html .= '</div>';
            $this->html .= '<div class="panel-footer">';
            $this->html .= '<div class="heading-elements">';
            $this->html .= '<span class="heading-text text-semibold"></span>';
            $this->html .= '<div class="heading-btn pull-right">';
            $this->html .= form_button('send', '<i class="glyphicon glyphicon-saved"></i> Хадгалах', 'class="btn btn-primary btn-md" onclick="_saveStudentAttendanceForm({});"', 'button');
            $this->html .= '</div>';
            $this->html .= '</div>';
            $this->html .= '<a class="heading-elements-toggle"><i class="icon-more"></i></a>';
            $this->html .= '</div>';
        } else {
            $this->html .= '<div class="panel-body">';
            $this->html .= '<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Хайлт хийх утгаа сонгоод хайх товчийг дарна уу</div>';
            $this->html .= '</div>';
        }

        $this->html .= form_close();
        return $this->html;
    }

    public function insertStudentAttendance_model($param = array()) {
        $this->db->where('class_id', $this->input->post('classId'));
        $this->db->where('date', $this->input->post('date'));
        $this->db->delete($this->db->dbprefix . 'student_attendance');
        
        foreach ($this->input->post('studentId') as $key => $studentId) {
            $data = array(
                array(
                    'mod_id' => $this->input->post('modId'),
                    'class_id' => $this->input->post('classId'),
                    'student_id' => $this->input->post('studentId[' . $key . ']'),
                    'date' => $this->input->post('date'),
                    'is_active' => $this->input->post('isActive[' . $key . ']'),
                    'intro_text' => $this->input->post('introText[' . $key . ']')
                )
            );
            $this->db->insert_batch($this->db->dbprefix . 'student_attendance', $data);
        }

        return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
    }

    public function getDataStudentAttendace_model($param = array()) {
        $this->data = array();
        $query = $this->db->query('
            SELECT 
                SA.id,
                SA.mod_id,
                SA.class_id,
                SA.student_id,
                SA.date,
                SA.is_active,
                SA.intro_text
            FROM `' . $this->db->dbprefix . 'student_attendance` AS SA
            WHERE 
                SA.date = \'' . $param['date'] . '\' AND SA.class_id=' . $param['classId']);

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $row) {
                $this->data['isActive' . $row->student_id] = $row->is_active;
                $this->data['introText' . $row->student_id] = $row->intro_text;
            };
            return $this->data;
        }

        return false;
    }
    
    public function getDataStudentAttendaceAll_model($param = array()) {
        $this->minDate = $this->maxDate = $this->day = 0;
        $query = $this->db->query('
            SELECT 
                SA.id,
                SA.mod_id,
                SA.class_id,
                SA.student_id,
                SA.date,
                SA.is_active,
                SA.intro_text
            FROM `' . $this->db->dbprefix . 'student_attendance` AS SA
            WHERE 
                SA.student_id = \'' . $param['studentId'] . '\' AND SA.class_id=' . $param['classId']);

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $row) {
                if (strtotime($row->date) < $this->minDate) {
                    $this->minDate = strtotime($row->date);
                }
                if (strtotime($row->date) > $this->maxDate) {
                    $this->maxDate = strtotime($row->date);
                }
                $this->day = $this->day + intval($row->is_active);
                $this->minDate = strtotime($row->date);
                $this->maxDate = strtotime($row->date);
            };
        }
        return array('startDate' => date('Y-m-d', $this->minDate), 'endDate' => date('Y-m-d', $this->maxDate), 'day' => $this->day);
    }
}
