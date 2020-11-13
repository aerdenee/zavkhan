<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sclass extends CI_Controller {

    public static $path = "sclass/";

    function __construct() {

        parent::__construct();
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Sclass_model', 'class');
        $this->load->model('Systemowner_model', 'systemowner');
        $this->load->model('Sbreadcrumb_model', 'breadcrumb');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Suser_model', 'user');
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/class.js');
            $this->body = array();
            $this->body['mode'] = 'lists';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['perPage'] = ($this->input->get('per_page') != null ? $this->input->get('per_page') : 0);
            
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/class/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function lists() {
        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->body['mode'] = 'lists';
            $this->body['modId'] = $this->input->post('modId');
            
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            
            if ($this->input->get('keyword') != '') {
                $this->urlString .= '&keyword=' . $this->input->get('keyword');
            }

            $this->paginationConfig["base_url"] = 'javascript:;';//base_url(self::$path . "index/" . $this->body['modId'] . ($this->urlString != '' ? '?' . $this->urlString : ''));
            $this->paginationConfig["total_rows"] = $this->class->listsCount_model(array('modId' => $this->body['modId'], 'keyword' => $this->input->get('keyword')));
            $this->page = ($this->input->post('perPage')) ? $this->input->post('perPage') : 0;
            $this->paginationConfig["per_page"] = PAGINATION_PER_PAGE;
            $this->paginationConfig["uri_segment"] = 4;

            $this->paginationConfig['full_tag_open'] = '<ul class="pagination pagination-flat pagination-xs pull-right">';
            $this->paginationConfig['full_tag_close'] = '</ul>';
            $this->paginationConfig['num_links'] = PAGINATION_NUM_LINKS;
            $this->paginationConfig['page_query_string'] = TRUE;
            $this->paginationConfig['prev_link'] = '&lt; Өмнөх';
            $this->paginationConfig['prev_tag_open'] = '<li>';
            $this->paginationConfig['prev_tag_close'] = '</li>';
            $this->paginationConfig['next_link'] = 'Дараах &gt;';
            $this->paginationConfig['next_tag_open'] = '<li>';
            $this->paginationConfig['next_tag_close'] = '</li>';
            $this->paginationConfig['cur_tag_open'] = '<li class="active"><a href="javascript:;">';
            $this->paginationConfig["cur_page"] = $this->page;
            $this->paginationConfig['cur_tag_close'] = '</a></li>';
            $this->paginationConfig['num_tag_open'] = '<li>';
            $this->paginationConfig['num_tag_close'] = '</li>';
            $this->paginationConfig['first_link'] = FALSE;
            $this->paginationConfig['last_link'] = FALSE;
            $this->pagination->initialize($this->paginationConfig);
            echo json_encode(array(
                'mode' => $this->body['mode'],
                'title' => 'Анги бүртгэлийн хуудас',
                'html' => $this->class->lists_model(array(
                    'title' => $this->body['module']->title,
                    'modId' => $this->body['modId'],
                    'parentId' => 0,
                    'keyword' => $this->input->get('keyword'),
                    'limit' => $this->paginationConfig["per_page"],
                    'page' => $this->page,
                    'paginationHtml' => $this->pagination->create_links()))));
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/class.js');
            $this->body = array();
            $this->body['mode'] = 'insert';
            $this->body['modId'] = ($this->uri->segment(3) != null ? $this->uri->segment(3) : $this->input->post('modId'));

            $this->body['row'] = $this->class->addFormData_model();
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            
            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlAuthorDropdown'] = $this->user->controlAuthorDropdown_model(array('selectedId' => $this->body['row']->teacher_id, 'name' => 'teacherId'));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));


            if ($this->input->is_ajax_request()) {
                echo json_encode(array(
                    'mode' => $this->body['mode'],
                    'title' => 'Анги бүртгэлийн хуудас',
                    'html' => $this->load->view(MY_ADMIN . '/class/form', $this->body, TRUE),
                    'btn_ok' => 'Хадгалах',
                    'btn_no' => 'Хаах'));
            } else {
                $this->load->view(MY_ADMIN . '/header', $this->header);
                $this->load->view(MY_ADMIN . '/class/form', $this->body);
                $this->load->view(MY_ADMIN . '/footer');
            }
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/editor/ckeditor/ckeditor.js', '/assets/system/core/class.js');
            $this->header['breadcrumb'] = '';
            $this->body = array();
            $this->body['mode'] = 'update';
            $this->body['modId'] = ($this->uri->segment(3) != null ? $this->uri->segment(3) : $this->input->post('modId'));
            $this->body['id'] = ($this->uri->segment(4) != null ? $this->uri->segment(4) : $this->input->post('id'));

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $this->body['row'] = $this->class->editFormData_model(array('id' => $this->body['id']));
            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlAuthorDropdown'] = $this->user->controlAuthorDropdown_model(array('selectedId' => $this->body['row']->teacher_id, 'name' => 'teacherId'));

            if ($this->input->is_ajax_request()) {
                echo json_encode(array(
                    'mode' => $this->body['mode'],
                    'title' => 'Анги бүртгэлийн хуудас',
                    'html' => $this->load->view(MY_ADMIN . '/class/form', $this->body, TRUE),
                    'btn_ok' => 'Хадгалах',
                    'btn_no' => 'Хаах'));
            } else {
                $this->load->view(MY_ADMIN . '/header', $this->header);
                $this->load->view(MY_ADMIN . '/class/form', $this->body);
                $this->load->view(MY_ADMIN . '/footer');
            }
            
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        $this->getUID = getUID('class');
        echo json_encode($this->class->insert_model(array('getUID' => $this->getUID)));
    }

    public function update() {
        echo json_encode($this->class->update_model());
    }

    public function delete() {
        echo json_encode($this->class->delete_model());
    }

    public function searchForm() {

        $this->body = array();
        $this->body['modId'] = $this->input->post('modId');
        echo json_encode(array(
            'title' => 'Жагсаалтаас хайлт хийх',
            'html' => $this->load->view(MY_ADMIN . '/class/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    public function getData() {
        echo json_encode($this->class->getData_model(array('selectedId' => ($this->input->post('classId') != null ? $this->input->post('classId') : $this->input->get('classId')))));
    }
}
