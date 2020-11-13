<?php

class Sscene_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Sdepartment_model', 'department');
        $this->load->model('Suser_model', 'user');
        $this->load->model('Scrime_model', 'crime');
    }

    public function addFormData_model() {
        return array(
            'id' => 0,
            'mod_id' => 0,
            'cat_id' => 0,
            'department_id' => 0,
            'expert_id' => '',
            'scene_expert' => '',
            'scene_date_in' => date('Y-m-d H:i:s'),
            'scene_date_out' => date('Y-m-d H:i:s'),
            'crime_type_id' => 0,
            'scene_value' => '',
            'is_evidance' => 1,
            'latent_print' => '',
            'latent_print_stamp' => '',
            'boot_print' => '',
            'transport_print' => '',
            'other_print' => '',
            'photo_count' => 0,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'order_num' => getOrderNum(array('table' => 'urgudul', 'field' => 'order_num'))
        );
    }

    public function editFormData_model($param = array('id' => 0)) {
        $this->query = $this->db->query('
            SELECT 
                S.id,
                S.mod_id,
                S.cat_id,
                S.department_id,
                S.expert_id,
                S.scene_expert,
                S.scene_date_in,
                S.scene_date_out,
                S.crime_type_id,
                S.scene_value,
                S.is_evidance,
                S.latent_print,
                S.latent_print_stamp,
                S.boot_print,
                S.transport_print,
                S.other_print,
                S.photo_count,
                S.created_date,
                S.modified_date,
                S.created_user_id,
                S.modified_user_id,
                S.order_num
            FROM `gaz_scene` AS S 
            WHERE S.id = ' . $param['id'] . ' 
            ORDER BY S.id DESC');

        if ($this->query->num_rows() > 0) {
            $this->result = $this->query->result();
            return (array) $this->result['0'];
        }

        return self::addFormData_model();
    }

    public function insert_model($param = array('getUID' => 0)) {

        $this->query = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'department_id' => $this->input->post('departmentId'),
                'expert_id' => $this->input->post('expertId'),
                'scene_expert' => $this->input->post('sceneExpert'),
                'scene_date_in' => $this->input->post('sceneDateIn') . ' ' . $this->input->post('sceneDateInTime'),
                'scene_date_out' => $this->input->post('sceneDateOut') . ' ' . $this->input->post('sceneDateOutTime'),
                'crime_type_id' => $this->input->post('crimeTypeId'),
                'scene_value' => $this->input->post('sceneValue'),
                'is_evidance' => $this->input->post('isEvidance'),
                'latent_print' => $this->input->post('latentPrint'),
                'latent_print_stamp' => $this->input->post('latentPrintStamp'),
                'boot_print' => $this->input->post('bootPrint'),
                'transport_print' => $this->input->post('transportPrint'),
                'other_print' => $this->input->post('otherPrint'),
                'photo_count' => $this->input->post('photoCount'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => '0000-00-00 00:00:00',
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'order_num' => getOrderNum(array('table' => 'scene', 'field' => 'order_num'))
            )
        );
        if ($this->db->insert_batch($this->db->dbprefix . 'scene', $this->query)) {
            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $this->result = array('status' => 'success', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $this->result;
    }

    public function update_model() {
        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'department_id' => $this->input->post('departmentId'),
            'expert_id' => $this->input->post('expertId'),
            'scene_expert' => $this->input->post('sceneExpert'),
            'scene_date_in' => $this->input->post('sceneDateIn') . ' ' . $this->input->post('sceneDateInTime'),
            'scene_date_out' => $this->input->post('sceneDateOut') . ' ' . $this->input->post('sceneDateOutTime'),
            'crime_type_id' => $this->input->post('crimeTypeId'),
            'scene_value' => $this->input->post('sceneValue'),
            'is_evidance' => $this->input->post('isEvidance'),
            'latent_print' => $this->input->post('latentPrint'),
            'latent_print_stamp' => $this->input->post('latentPrintStamp'),
            'boot_print' => $this->input->post('bootPrint'),
            'transport_print' => $this->input->post('transportPrint'),
            'other_print' => $this->input->post('otherPrint'),
            'photo_count' => $this->input->post('photoCount'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'order_num' => $this->input->post('orderNum')
        );
        $this->db->where('id', $this->input->post('id'));
        if ($this->db->update($this->db->dbprefix . 'scene', $this->data)) {
            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $this->result = array('status' => 'success', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $this->result;
    }

    public function listsCount_model($param = array()) {
        $this->queryString = '';

        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND S.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND S.created_user_id = -1';
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND S.cat_id = ' . $param['catId'];
        }

        if ($param['sceneDateIn'] != '' and $param['sceneDateOut'] != '') {
            $this->queryString .= ' AND \'' . $param['sceneDateIn'] . '\' <= DATE(S.scene_date_in) AND \'' . $param['sceneDateOut'] . '\' >= DATE(S.scene_date_in)';
        } elseif ($param['sceneDateIn'] != '' and $param['sceneDateIn'] == '') {
            $this->queryString .= ' AND \'' . $param['sceneDateIn'] . '\' <= DATE(S.scene_date_in)';
        }

        if ($param['departmentId'] != 0) {
            $this->queryString .= ' AND S.department_id = ' . $param['departmentId'];
        }

        if ($param['expertId'] != 0) {
            $this->queryString .= ' AND S.expert_id = ' . $param['expertId'];
        }

        if ($param['crimeTypeId'] != 0) {
            $this->queryString .= ' AND S.crime_type_id = ' . $param['crimeTypeId'];
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(S.scene_expert) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.scene_value) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.latent_print) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.latent_print_stamp) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.boot_print) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.transport_print) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.other_print) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }


        $this->query = $this->db->query('
            SELECT 
                S.id
            FROM `gaz_scene` AS S 
            WHERE 1 = 1 ' . $this->queryString . ' AND S.mod_id = ' . $param['modId'] . ' 
            ORDER BY S.id DESC');

        return $this->query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $this->queryString = $this->getString = '';
        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND S.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND S.created_user_id = -1';
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND S.cat_id = ' . $param['catId'];
            $this->getString .= form_hidden('catId', $param['catId']);
        }

        $this->getString .= form_hidden('sceneDateIn', $param['sceneDateIn']);
        $this->getString .= form_hidden('sceneDateOut', $param['sceneDateOut']);

        if ($param['sceneDateIn'] != '' and $param['sceneDateOut'] != '') {
            $this->queryString .= ' AND \'' . $param['sceneDateIn'] . '\' <= DATE(S.scene_date_in) AND \'' . $param['sceneDateOut'] . '\' >= DATE(S.scene_date_in)';
        } elseif ($param['sceneDateIn'] != '' and $param['sceneDateIn'] == '') {
            $this->queryString .= ' AND \'' . $param['sceneDateIn'] . '\' <= DATE(S.scene_date_in)';
        }

        if ($param['departmentId'] != 0) {
            $this->queryString .= ' AND S.department_id = ' . $param['departmentId'];
            $this->getString .= form_hidden('departmentId', $param['departmentId']);
        }

        if ($param['expertId'] != 0) {
            $this->queryString .= ' AND S.expert_id = ' . $param['expertId'];
            $this->getString .= form_hidden('expertId', $param['expertId']);
        }

        if ($param['crimeTypeId'] != 0) {
            $this->queryString .= ' AND S.crime_type_id = ' . $param['crimeTypeId'];
            $this->getString .= form_hidden('crimeTypeId', $param['crimeTypeId']);
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(S.scene_expert) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.scene_value) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.latent_print) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.latent_print_stamp) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.boot_print) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.transport_print) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(S.other_print) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        $this->query = $this->db->query('
            SELECT 
                S.id,
                S.mod_id,
                S.cat_id,
                C.title_mn AS category_title,
                S.department_id,
                D.title_mn AS department_title,
                S.expert_id,
                CONCAT(SUBSTRING(U.lname_mn, 1, 1), \'.\', U.fname_mn) AS full_name,
                S.scene_expert,
                S.scene_date_in,
                S.scene_date_out,
                S.crime_type_id,
                CT.title AS crime_type_title,
                S.scene_value,
                S.is_evidance,
                S.latent_print,
                S.latent_print_stamp,
                S.boot_print,
                S.transport_print,
                S.other_print,
                S.photo_count,
                S.created_date,
                S.modified_date,
                S.created_user_id,
                S.modified_user_id,
                S.order_num
            FROM `gaz_scene` AS S
            LEFT JOIN `gaz_category` AS C ON S.cat_id = C.id
            LEFT JOIN `gaz_user` AS U ON S.expert_id = U.id
            LEFT JOIN `gaz_department` AS D ON S.department_id = D.id
            LEFT JOIN `gaz_crime_type` AS CT ON S.crime_type_id = CT.id
            WHERE 1 = 1 ' . $this->queryString . ' AND S.mod_id = ' . $param['modId'] . ' 
            ORDER BY S.id DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        $this->data = array();
        $this->data['html'] = '';
        $this->data['html'] .= form_open('', array('class' => 'form-horizontal', 'id' => 'form-reservation-export', 'enctype' => 'multipart/form-data'));
        $this->data['html'] .= form_hidden('modId', $param['modId']);
        $this->data['html'] .= form_hidden('limit', $param['limit']);
        $this->data['html'] .= form_hidden('page', $param['page']);
        $this->data['html'] .= $this->getString;

        $this->data['html'] .= '<div class="panel panel-flat">';
        $this->data['html'] .= '<div class="panel-heading">';
        $this->data['html'] .= '<h5 class="panel-title">' . $param['title'] . '</h5>';
        $this->data['html'] .= '<div class="heading-elements">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';
        $this->data['html'] .= '<li>' . anchor(Sscene::$path . 'index/' . $param['modId'], '<i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i>', array()) . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="panel-body" style="padding-bottom:5px;">';
        $this->data['html'] .= '<div class="pull-left search-keyword-view">' . self::searchKeywordView_model(array('modId' => $param['modId'], 'path' => $param['path'])) . '</div>';
        $this->data['html'] .= '<div class="pull-right">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';

        $this->data['html'] .= '<li style="padding-right:10px;">' . anchor(Sscene::$path . 'add/' . $param['modId'], '<i class="fa fa-plus"></i> Нэмэх', 'class="btn btn-info btn-rounded btn-xs"') . '</li>';
        $this->data['html'] .= '<li style="padding-right:10px;">' . form_button('send', '<i class="fa fa-download"></i> Экспорт', 'class="btn btn-info btn-rounded btn-xs" onclick="_export(this);" ' . (empty($this->query->num_rows()) ? ' disabled="disabled"' : ''), 'button') . '</li>';
        $this->data['html'] .= '<li>' . form_button('send', '<i class="fa fa-search"></i> хайх', 'class="btn btn-info btn-rounded btn-xs" onclick="_advensedSearch(this);"', 'button') . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        if ($this->query->num_rows() > 0) {

            $this->data['html'] .= '<div class="table-responsive">';
            $this->data['html'] .= '<table class="table table-bordered table-custom">';
            $this->data['html'] .= '<thead>';
            $this->data['html'] .= '<tr>';
            $this->data['html'] .= '<th style="width:30px;">#</th>';
            $this->data['html'] .= '<th style="width:150px;">Дүүрэг, Цагдаагийн хэлтэс</th>';
            $this->data['html'] .= '<th style="width:150px;">Шинжээч</th>';
            $this->data['html'] .= '<th style="width:150px;">Мөрдөн байцаагч</th>';
            $this->data['html'] .= '<th style="width:150px;">Үзлэг</th>';
            $this->data['html'] .= '<th style="width:150px;">Төрөл</th>';
            $this->data['html'] .= '<th>Утга</th>';
            $this->data['html'] .= '<th>Ул мөр</th>';
            $this->data['html'] .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';
            $i = 1;
            foreach ($this->query->result() as $row) {
                $this->data['html'] .= '<tr data-id="' . $row->id . '">';
                $this->data['html'] .= '<td>' . $i . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row">' . $row->department_title . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row">' . $row->full_name . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row">' . $row->scene_expert . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row">' . $row->category_title . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row">' . $row->crime_type_title . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row">' . $row->scene_value . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row">Гарын мөр: <strong>' . $row->latent_print . '</strong>, Гарын хээний дардас: <strong>' . $row->latent_print_stamp . '</strong>, Гутлын мөр: <strong>' . $row->boot_print . '</strong>, Тээврийн хэрэгслийн мөр: <strong>' . $row->transport_print . '</strong>, Бусад ул мөр, эд мөрийн баримт: <strong>' . $row->other_print . '</strong></td>';
                $this->data['html'] .= '<td class="text-center">';
                $this->data['html'] .= '<ul class="icons-list">';
                $this->data['html'] .= '<li><a href="' . Sscene::$path . 'edit/' . $param['modId'] . '/' . $row->id . '"><i class="icon-pencil7"></i></a></li>';
                $this->data['html'] .= '<li><a href="javascript:;" onclick="_removeItem(' . $row->id . ');"><i class="icon-trash"></i></a></li>';
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
            $this->data['html'] .= '<span class="heading-text text-semibold"></span>';
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
        foreach ($this->input->post('id') as $key => $id) {

            $this->db->where('id', $this->input->post('id[' . $key . ']'));
            $this->db->delete($this->db->dbprefix . 'scene');
        }
        return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
    }

    public function export_model($param = array('modId' => 0, 'catId' => 0)) {

        $this->queryString = $this->getString = '';
        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND U.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND U.created_user_id = -1';
        }

        if ($param['isActive'] != 0) {
            $this->queryString .= ' AND U.is_active = ' . $param['isActive'];
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND U.cat_id = ' . $param['catId'];
        }

        if ($param['startDate'] != '' and $param['endDate'] != '') {
            $this->queryString .= ' AND \'' . $param['startDate'] . '\' >= DATE(U.created_date) AND \'' . $param['endDate'] . '\' <= DATE(U.created_date)';
        } elseif ($param['startDate'] != '' and $param['endDate'] == '') {
            $this->queryString .= ' AND \'' . $param['startDate'] . '\' >= DATE(U.created_date)';
        }

        if ($param['cityId'] != 0 and $param['soumId'] != 0 and $param['streetId'] != 0) {
            $this->queryString .= ' AND U.city_id = ' . $param['cityId'] . ' AND U.soum_id = ' . $param['soumId'] . ' AND U.street_id = ' . $param['streetId'];
        } elseif ($param['cityId'] != 0 and $param['soumId'] != 0 and $param['streetId'] == 0) {
            $this->queryString .= ' AND U.city_id = ' . $param['cityId'] . ' AND U.soum_id = ' . $param['soumId'];
        } elseif ($param['cityId'] != 0 and $param['soumId'] == 0 and $param['streetId'] == 0) {
            $this->queryString .= ' AND U.city_id = ' . $param['cityId'];
        }

        if ($param['createNumber'] != '') {
            $this->queryString .= ' AND U.create_number = ' . $param['createNumber'];
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(U.urg_fname) LIKE LOWER(\'' . $param['keyword'] . '%\') OR LOWER(U.urg_lname) LIKE LOWER(\'' . $param['keyword'] . '%\') OR LOWER(U.urg_intro) LIKE LOWER(\'' . $param['keyword'] . '%\'))';
        }

        $this->query = $this->db->query('
            SELECT 
                U.id,
                U.generate_date,
                U.lname,
                U.fname,
                U.address,
                U.contact,
                U.cat_id,
                U.mod_id,
                U.description,
                U.page,
                U.created_date,
                U.modified_date,
                U.create_number,
                U.created_user_id,
                U.modified_user_id,
                U.is_active,
                U.city_id,
                C.title_mn AS city_title,
                U.soum_id,
                S.title_mn AS soum_title,
                U.street_id,
                G.title_mn AS street_title,
                U.order_num,
                U.close_description
            FROM `gaz_urgudul` AS U
            LEFT JOIN `gaz_address` AS C ON U.city_id = C.id
            LEFT JOIN `gaz_address` AS S ON U.soum_id = S.id
            LEFT JOIN `gaz_address` AS G ON U.street_id = G.id
            WHERE 1 = 1 ' . $this->queryString . ' AND U.mod_id = ' . $param['modId'] . ' 
            ORDER BY U.id DESC');

        if ($this->query->num_rows() > 0) {
            return $this->query->result();
        }
        return false;
    }

    public function searchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {

        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('catId')) {
            $this->cat = $this->category->getData_model(array('selectedId' => $this->input->get('catId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->cat->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('startDate') and $this->input->get('endDate')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('startDate') . '-' . $this->input->get('endDate') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('crimeTypeId')) {
            $this->crimeTypeData = $this->crime->getScrimeTypeData_model(array('selectedId' => $this->input->get('crimeTypeId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->crimeTypeData->title . '</span>';
            $this->showResetBtn = TRUE;
        }
        
        if ($this->input->get('departmentId')) {
            $this->departmentData = $this->department->getData_model(array('selectedId' => $this->input->get('departmentId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->departmentData->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('expertId')) {
            $this->expertData = $this->user->getData_model(array('selectedId' => $this->input->get('expertId')));
            $this->string .= '<span class="label label-default label-rounded">' . mb_substr($this->expertData->lname, 0, 1, 'UTF-8') . '.' . $this->expertData->fname . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->showResetBtn = TRUE;
        }


        if ($this->showResetBtn) {
            $this->string .= ' <a href="' . Sscene::$path . $param['path'] . '/' . $param['modId'] . '"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return $this->string;
    }
}
