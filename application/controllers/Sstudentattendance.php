<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sstudentattendance extends CI_Controller {

    public static $path = "sstudentattendance/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Systemowner_model', 'systemowner');
        $this->load->model('Scontent_model', 'content');
        $this->load->model('Suser_model', 'user');
        $this->load->model('Sstudent_model', 'student');
        $this->load->model('Saddress_model', 'address');
        $this->load->model('Sdepartment_model', 'department');
        $this->load->model('Sbreadcrumb_model', 'breadcrumb');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('SawardType_model', 'awardType');
        $this->load->model('Seducation_model', 'education');
        $this->load->model('Sdegree_model', 'degree');
        $this->load->model('Scareer_model', 'career');
        $this->load->model('Sclass_model', 'class');
        $this->load->model('Sstudentfinance_model', 'studentfinance');
        $this->load->model('Sstudentattendance_model', 'studentattendance');
    }

    public function index() {

        $this->urlString = '';

        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/studentattendance.js');
            $this->body = array();
            $this->body['mode'] = 'lists';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);
            
            $this->body['perPage'] = ($this->input->get('per_page') != null ? $this->input->get('per_page') : 0);
            
            if ($this->input->get('createdDate')) {
                $this->urlString .= '&createdDate=' . $this->input->get('createdDate');
            }
            if ($this->input->get('soumId')) {
                $this->urlString .= '&soumId=' . $this->input->get('soumId');
            }
            if ($this->input->get('streetId')) {
                $this->urlString .= '&streetId=' . $this->input->get('streetId');
            }

            if ($this->input->get('birthday')) {
                $this->urlString .= '&birthday=' . $this->input->get('birthday');
            }
            if ($this->input->get('createdDate')) {
                $this->urlString .= '&createdDate=' . $this->input->get('createdDate');
            }
            if ($this->input->get('catId')) {
                $this->urlString .= '&catId=' . $this->input->get('catId');
            }
            if ($this->input->get('code')) {
                $this->urlString .= '&code=' . $this->input->get('code');
            }
            if ($this->input->get('keyword')) {
                $this->urlString .= '&keyword=' . $this->input->get('keyword');
            }

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/studentattendance/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function classList() {
        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->body['mode'] = 'lists';
            $this->body['modId'] = $this->input->get('modId');

            if ($this->input->get('cityId')) {
                $this->urlString .= '&cityId=' . $this->input->get('cityId');
            }
            if ($this->input->get('soumId')) {
                $this->urlString .= '&soumId=' . $this->input->get('soumId');
            }
            if ($this->input->get('streetId')) {
                $this->urlString .= '&streetId=' . $this->input->get('streetId');
            }

            if ($this->input->get('birthday')) {
                $this->urlString .= '&birthday=' . $this->input->get('birthday');
            }
            if ($this->input->get('createdDate')) {
                $this->urlString .= '&createdDate=' . $this->input->get('createdDate');
            }
            if ($this->input->get('catId')) {
                $this->urlString .= '&catId=' . $this->input->get('catId');
            }
            if ($this->input->get('code')) {
                $this->urlString .= '&code=' . $this->input->get('code');
            }
            if ($this->input->get('keyword')) {
                $this->urlString .= '&keyword=' . $this->input->get('keyword');
            }

            echo json_encode(array(
                'mode' => $this->body['mode'],
                'title' => 'Анги бүртгэлийн хуудас',
                'html' => $this->studentattendance->classList_model(array(
                    'title' => 'Хайлтын үр дүн',
                    'modId' => $this->body['modId'],
                    'cityId' => $this->input->get('cityId'),
                    'soumId' => $this->input->get('soumId'),
                    'streetId' => $this->input->get('streetId'),
                    'birthday' => $this->input->get('birthday'),
                    'createdDate' => $this->input->get('createdDate'),
                    'catId' => $this->input->get('catId'),
                    'code' => $this->input->get('code'),
                    'keyword' => $this->input->get('keyword'),
                    'startDate' => $this->input->get('startDate'),
                    'endDate' => $this->input->get('endDate')))));
        }
    }

    public function dayAttendanceList() {
        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            echo json_encode(array(
                'html' => $this->studentattendance->dayAttendanceList_model(array(
                    'modId' => $this->input->post('modId'),
                    'classId' => $this->input->post('id')))));
        }
    }
    
    public function attendanceList() {
        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            echo json_encode(array(
                'html' => $this->studentattendance->attendanceList_model(array(
                    'modId' => $this->input->post('modId'),
                    'classId' => $this->input->post('classId'),
                    'date' => $this->input->post('date')))));
        }
    }
    
    function searchForm() {
        $this->body = array();
        $this->body['modId'] = $this->input->post('modId');
        $this->body['controlClassListDropdown'] = $this->class->controlClassListDropdown_model(array('modId' => 37, 'selectedId' => 0));
        $this->body['controlCityDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'cityId', 'parentId' => 12, 'selectedId' => 0, 'required' => false));
        $this->body['controlSoumDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'soumId', 'parentId' => 0, 'selectedId' => 0, 'required' => false));
        $this->body['controlStreetDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'streetId', 'parentId' => 0, 'selectedId' => 0, 'required' => false));
        echo json_encode(array(
            'title' => 'Дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/studentattendance/searchForm', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    function insertStudentAttendance() {
        $this->getUID = getUID('student_attendance');
        echo json_encode($this->studentattendance->insertStudentAttendance_model(array('getUID' => $this->getUID)));
    }
}
