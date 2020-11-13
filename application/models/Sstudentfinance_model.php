<?php

class Sstudentfinance_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Sawardtype_model', 'awardType');
        $this->load->model('Scareer_model', 'career');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Sstudentattendance_model', 'studentattendance');
        
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

//        $this->query = $this->db->query('
//            SELECT 
//                C.id,
//                C.mod_id,
//                C.cat_id,
//                CAT.title_mn AS title,
//                C.intro_text,
//                C.created_date,
//                C.modified_date,
//                C.created_user_id,
//                C.modified_user_id,
//                C.is_active,
//                C.order_num,
//                C.start_date,
//                C.end_date,
//                C.price
//            FROM `' . $this->db->dbprefix . 'class` AS C
//            INNER JOIN `' . $this->db->dbprefix . 'category` AS CAT
//            WHERE 1 = 1 ' . $this->queryString . '
//            ORDER BY C.id DESC');
        
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
            GROUP BY SF.class_id');

        $this->data = array();
        $this->data['html'] = '';
        $this->data['html'] .= form_open('', array('class' => 'form-vertical', 'id' => 'form-student-finance', 'enctype' => 'multipart/form-data'));
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
        $this->data['html'] .= '<h5 class="panel-title">Санхүүгийн мэдээлэл</h5>';
        $this->data['html'] .= '<div class="heading-elements">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';
        $this->data['html'] .= '<li>' . anchor(Sstudentfinance::$path . 'index/' . $param['modId'], '<i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i>', array()) . '</li>';
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

        if ($this->input->get('startDate') and $this->query->num_rows() > 0) {
            $this->data['html'] .= '<div class="table-responsive">';
            $this->data['html'] .= '<table class="table table-bordered table-custom">';
            $this->data['html'] .= '<thead>';
            $this->data['html'] .= '<tr>';
            $this->data['html'] .= '<th style="width:30px;">#</th>';
            $this->data['html'] .= '<th style="width:100px;">Код</th>';
            $this->data['html'] .= '<th>Овог, нэр</th>';
            $this->data['html'] .= '<th style="width:100px;">Эхэлсэн</th>';
            $this->data['html'] .= '<th style="width:100px;">Дуссан</th>';
            $this->data['html'] .= '<th style="width:100px;">Төлөх ёстой</th>';
            $this->data['html'] .= '<th style="width:100px;">Төлсөн</th>';
            $this->data['html'] .= '<th style="width:100px;">Үлдэгдэл</th>';
            $this->data['html'] .= '<th style="width:100px;">Төлөв</th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';
            
            foreach ($this->query->result() as $key => $row) {
                $this->dateBetweenInfo = dateBetweenInfo(array('dateIn' => $row->start_date, 'dateOut' => $row->end_date));
                
                $this->data['html'] .= '<tr class="active"><td colspan="9" style="text-align:left;">' . $row->title . ', ' . $this->dateBetweenInfo['dateString'] . ' (' . $this->dateBetweenInfo['dateDiff'] . ' өдөр)</td></tr>';
                
                $this->querySub = $this->db->query('
                    SELECT 
                        SF.id,
                        SF.student_id,
                        SF.class_id,
                        S.code,
                        S.fname,
                        S.lname,
                        CONCAT(SUBSTRING(S.lname, 1, 1), \'.\', S.fname) AS full_name,
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
                    INNER JOIN `' . $this->db->dbprefix . 'student` AS S ON SF.student_id = S.id
                    WHERE SF.class_id = ' . $row->class_id . ' ' . $this->querySubString . '
                    GROUP BY SF.student_id DESC');

                if ($this->querySub->num_rows() > 0) {

                    $i = 1;
                    foreach ($this->querySub->result() as $key => $rowSub) {
                        
                        $this->getDataStudentAttendaceAll = $this->studentattendance->getDataStudentAttendaceAll_model(array('studentId' => $rowSub->student_id, 'classId' => $rowSub->class_id));
                        
                        $this->data['html'] .= '<tr>';
                        $this->data['html'] .= '<td class="text-center">' . $i . '</td>';
                        $this->data['html'] .= '<td>' . $rowSub->code . '</td>';
                        $this->data['html'] .= '<td>' . $rowSub->full_name . '</td>';
                        $this->data['html'] .= '<td>' . $this->getDataStudentAttendaceAll['startDate'] . ' ' . $this->getDataStudentAttendaceAll['day'] . '</td>';
                        $this->data['html'] .= '<td>' . $this->getDataStudentAttendaceAll['endDate'] . '</td>';
                        $this->data['html'] .= '<td>' . $row->price . '</td>';
                        $this->data['html'] .= '<td>Төлсөн</td>';
                        $this->data['html'] .= '<td>Үлдэгдэл</td>';
                        $this->data['html'] .= '<td>' . $rowSub->student_id . '</td>';
                        $this->data['html'] .= '</tr>';
                        $i++;
                    }
                }
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
            $this->data['html'] .= '<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Хайлт хийх утгаа сонгоод хайх товчийг дарна уу</div>';
            $this->data['html'] .= '</div>';
        }

        $this->data['html'] .= '</div>';
        $this->data['html'] .= form_close();
        return $this->data['html'];
    }

}
