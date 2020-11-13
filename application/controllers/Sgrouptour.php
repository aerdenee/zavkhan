<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sgrouptour extends CI_Controller {

    public static $path = "sgrouptour/";

    function __construct() {
        
        parent::__construct();
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Sgrouptour_model', 'grouptour');
        $this->load->model('Systemowner_model', 'systemowner');
        $this->load->model('Sbreadcrumb_model', 'breadcrumb');
        $this->load->model('Smodule_model', 'module');
        
    }

    public function index() {
        
        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/grouptour.js');
            $this->body = array();
            $this->body['mode'] = 'lists';
            $this->body['modId'] = $this->uri->segment(3);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $config = array();
            
            if ($this->input->get('keyword') != '') {
                $this->urlString .= '&keyword=' . $this->input->get('keyword');
            }
            
            $config["base_url"] = base_url(self::$path . "index/" . $this->body['modId'] . ($this->urlString != '' ? '?' . $this->urlString : ''));
            $config["total_rows"] = $this->grouptour->listsCount_model(array('modId' => $this->body['modId'], 'keyword' => $this->input->get('keyword')));
            $page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;
            $config["per_page"] = PAGINATION_PER_PAGE;
            $config["uri_segment"] = 4;

            $config['full_tag_open'] = '<ul class="pagination pagination-flat pagination-xs pull-right">';
            $config['full_tag_close'] = '</ul>';
            $config['num_links'] = PAGINATION_NUM_LINKS;
            $config['page_query_string'] = TRUE;
            $config['prev_link'] = '&lt; Өмнөх';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['next_link'] = 'Дараах &gt;';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="javascript:;">';
            $config["cur_page"] = $page;
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['first_link'] = FALSE;
            $config['last_link'] = FALSE;

            $this->pagination->initialize($config);

            $this->body['dataHtml'] = $this->grouptour->lists_model(array(
                'title' => $this->body['module']->title,
                'modId' => $this->body['modId'],
                'parentId' => 0,
                'keyword' => $this->input->get('keyword'),
                'limit' => $config["per_page"],
                'page' => $page,
                'paginationHtml' => $this->pagination->create_links()));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/grouptour/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
            
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function add() {
        
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/grouptour.js');
            $this->body = array();
            $this->body['mode'] = 'insert';
            $this->body['modId'] = $this->uri->segment(3);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));
            
            $this->body['row'] = $this->grouptour->addFormData_model();
            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlGroupTourListDropdown'] = $this->grouptour->controlGroupTourListDropdown_model(array('modId' => 19, 'selectedId' => 0));
            $this->body['controlGroupSizeListDropdown'] = $this->grouptour->controlGroupSizeListDropdown_model(array('selectedId' => 0));
            
            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/grouptour/form', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
            
        } else {
            redirect(MY_ADMIN);
        }
    }
    
    public function edit() {
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/grouptour.js');
            $this->header['breadcrumb'] = '';
            $this->body = array();
            $this->body['mode'] = 'update';
            $this->body['modId'] = $this->uri->segment(3);
            
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));
            
            $this->body['row'] = $this->grouptour->editFormData_model(array('id' => $this->uri->segment(4)));
            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => $this->body['row']['cat_id'], 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlGroupTourListDropdown'] = $this->grouptour->controlGroupTourListDropdown_model(array('modId' => 19, 'selectedId' => $this->body['row']['cont_id']));
            $this->body['controlGroupSizeListDropdown'] = $this->grouptour->controlGroupSizeListDropdown_model(array('selectedId' => $this->body['row']['group_size']));
            
            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/grouptour/form', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
            
        } else {
            redirect(MY_ADMIN);
        }
        
    }
    
    public function insert() {
        
        $this->getUID = getUID('group_tour');
        echo json_encode($this->grouptour->insert_model(array('getUID'=>$this->getUID)));
    }
    
    public function update() {
        echo json_encode($this->grouptour->update_model());
    }
    
    public function delete() {
        echo json_encode($this->grouptour->delete_model());
    }
    
    public function searchForm() {
        
        $this->body = array();
        $this->body['modId'] = $this->input->post('modId');
        echo json_encode(array(
            'title' => 'Жагсаалтаас хайлт хийх',
            'html' => $this->load->view(MY_ADMIN . '/grouptour/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
        
    }
    
}