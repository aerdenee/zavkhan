<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Scategory extends CI_Controller {

    public static $path = "scategory/";

    function __construct() {

        parent::__construct();

        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Sthemelayout_model', 'themelayout');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');

        $this->header = $this->body = $this->footer = array();
        
        $this->isActiveDepartment = 'is_active_control';
    }

    public function index() {

        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->footer['jsFile'] = array('/assets/system/editor/ckeditor/ckeditor.js', '/assets/system/core/_category.js');

            $this->body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'isModule',
                'moduleMenuId' => $this->uri->segment(3),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['auth']->modId));

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['module']->title . ' - ангилал'));
            
            $this->load->view(MY_ADMIN . '/header', $this->header);
            if ($this->body['auth']->permission) {
                $this->load->view(MY_ADMIN . '/category/index', $this->body);
            } else {
                $this->load->view(MY_ADMIN . '/page/deny', $this->body);
            }
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function lists() {

        if ($this->session->isLogin === TRUE) {

            $auth = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'read',
                'moduleMenuId' => $this->input->get('moduleMenuId'),
                'createdUserId' => 0,
                'currentUserId' => $this->session->userdata['adminUserId']));
            $module = $this->module->getData_model(array('id' => $auth->modId));

            $page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

            //total rows count
            $totalCount = $this->category->listsCount_model(array(
                'auth' => $auth,
                'catId' => $this->input->get('catId'),
                'keyword' => $this->input->get('keyword'),
                'departmentId' => $this->input->get('departmentId')));

            //pagination configuration
            $config['base_url'] = base_url('lists');
            $config['total_rows'] = $totalCount;
            $config["cur_page"] = $page;
            $config['per_page'] = PAGINATION_PER_PAGE;
            $config['num_links'] = PAGINATION_NUM_LINKS;
            $config['link_func'] = '_initCategory';
            $this->ajax_pagination->initialize($config);

            //get posts data
            echo json_encode($this->category->lists_model(array(
                        'auth' => $auth,
                        'catId' => $this->input->get('catId'),
                        'title' => $module->title,
                        'keyword' => $this->input->get('keyword'),
                        'departmentId' => $this->input->get('departmentId'),
                        'limit' => $config["per_page"],
                        'page' => $page,
                        'path' => 'lists',
                        'totalRows' => $totalCount,
                        'paginationHtml' => $this->ajax_pagination->create_links())));
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->category->addFormData_model();

            $this->auth = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'create',
                'moduleMenuId' => $this->input->post('moduleMenuId'),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));
            
            $this->body['row']->mod_id = $this->auth->modId;
            $this->module = $this->module->getData_model(array('id' => $this->auth->modId));
            $this->body['row']->module_title = $this->module->title . ' - нэмэх';

            $this->body['controlThemeLayoutRadio'] = $this->themelayout->controlThemeLayoutRadio_model(array(
                'themeLayoutId' => 1,
                'modId' => $this->body['row']->mod_id,
                'isCategory' => 1));

            $this->body['controlCategoryParentMultiRowDropdown'] = $this->category->controlCategoryParentMultiRowDropdown_model(array(
                'modId' => $this->body['row']->mod_id,
                'selectedId' => $this->body['row']->parent_id,
                'id' => $this->body['row']->id));

            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 800,
                'html' => $this->load->view(MY_ADMIN . '/category/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {
        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->category->editFormData_model(array('selectedId' => $this->input->post('id')));

            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - засах';

            $this->body['controlThemeLayoutRadio'] = $this->themelayout->controlThemeLayoutRadio_model(array(
                'themeLayoutId' => $this->body['row']->theme_layout_id,
                'modId' => $this->body['row']->mod_id,
                'isCategory' => 1));

            $this->body['controlCategoryParentMultiRowDropdown'] = $this->category->controlCategoryParentMultiRowDropdown_model(array(
                'modId' => $this->body['row']->mod_id,
                'selectedId' => $this->body['row']->parent_id,
                'id' => $this->body['row']->id));

            echo json_encode(array(
                'title' => $this->body['row']->module_title . ' - ангилал',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 800,
                'html' => $this->load->view(MY_ADMIN . '/category/form', $this->body, TRUE)));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        $this->getUID = getUID('category');
        echo json_encode($this->category->insert_model(array('getUID' => $this->getUID, 'pic' => $this->input->post('pic'))));
    }

    public function update() {

        $this->newFile = $this->input->post('pic');

        if ($this->newFile == '') {
            $this->newFile = $this->input->post('oldPic');
        }
        echo json_encode($this->category->update_model(array('pic' => $this->newFile)));
    }

    public function delete() {
        echo json_encode($this->category->delete_model());
    }

    public function searchForm() {

        $this->body = array();
        $this->body['modId'] = $this->input->post('modId');
        echo json_encode(array(
            'title' => 'Хайлт хийх түлхүүр үгээ бичнэ үү',
            'html' => $this->load->view(MY_ADMIN . '/category/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }
    
    public function controlCategoryListDropdown() {
        echo json_encode($this->category->controlCategoryListDropdown_model(array('name' => $this->input->post('name'), 'modId' => $this->input->post('modId'), 'selectedId' => $this->input->post('selectedId'))));
    }

}
