<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Spartner extends CI_Controller {

    public static $path = "spartner/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Saddress_model', 'address');
        $this->load->model('Simage_model', 'simage');
        $this->load->model('Sthemelayout_model', 'themelayout');

        $this->perPage = 2;
        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/editor/ckeditor/ckeditor.js', '/assets/system/core/_partner.js', '/assets/system/core/_address.js');

            $this->body['auth'] = authentication(array(
                'permission' => $this->session->authentication, 
                'role' => 'isModule', 
                'moduleMenuId' => $this->uri->segment(3), 
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));
            
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['auth']->modId));

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['module']->title));
            
            //load the view
            $this->load->view(MY_ADMIN . '/header', $this->header);
            if ($this->body['auth']->permission) {
                $this->load->view(MY_ADMIN . '/partner/index', $this->body);
            } else {
                $this->load->view(MY_ADMIN . '/page/deny', $this->body);
            }
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function lists() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->auth = authentication(array(
                'permission' => $this->session->authentication, 
                'role' => 'read', 
                'moduleMenuId' => $this->input->get('moduleMenuId'), 
                'createdUserId' => 0,
                'currentUserId' => $this->session->userdata['adminUserId']));

            $this->module = $this->module->getData_model(array('id' => $this->input->get('modId')));

            $page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

            //total rows count
            $totalRec = $this->partner->listsCount_model(array(
                'auth' => $this->auth,
                'moduleMenuId' => $this->input->get('moduleMenuId'),
                'catId' => $this->input->get('catId'),
                'keyword' => $this->input->get('keyword')));

            //pagination configuration
            $config['base_url'] = base_url('lists');
            $config['total_rows'] = $totalRec;
            $config["cur_page"] = $page;
            $config['per_page'] = PAGINATION_PER_PAGE;
            $config['num_links'] = PAGINATION_NUM_LINKS;
            $config['link_func'] = '_initPartner';
            $this->ajax_pagination->initialize($config);


            //get posts data
            echo json_encode($this->partner->lists_model(array(
                    'auth' => $this->auth,
                        'title' => $this->module->title,
                        'moduleMenuId' => $this->input->get('moduleMenuId'),
                        'catId' => $this->input->get('catId'),
                        'keyword' => $this->input->get('keyword'),
                        'space' => 10,
                        'totalRows' => $totalRec,
                        'limit' => $config["per_page"],
                        'page' => $page,
                        'path' => 'lists',
                        'paginationHtml' => $this->ajax_pagination->create_links())));

            //load the view
            //$this->load->view(MY_ADMIN . '/partner/lists', $data, false);
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->partner->addFormData_model();
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlPartnerParentMultiRowDropdown'] = $this->partner->controlPartnerParentMultiRowDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->parent_id, 'id' => $this->body['row']->id, 'parentId' => 0, 'space' => ''));

            $this->body['controlCityDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'cityId', 'parentId' => 12, 'selectedId' => $this->body['row']->city_id));
            $this->body['controlSoumDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'soumId', 'parentId' => $this->body['row']->city_id, 'selectedId' => $this->body['row']->soum_id, 'disabled' => 'true'));
            $this->body['controlStreetDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'streetId', 'parentId' => $this->body['row']->soum_id, 'selectedId' => $this->body['row']->street_id, 'disabled' => 'true'));

            $this->body['controlThemeLayoutRadio'] = $this->themelayout->controlThemeLayoutRadio_model(array('themeLayoutId' => $this->body['row']->theme_layout_id, 'modId' => $this->body['row']->mod_id, 'isCategory' => 0));
            
            echo json_encode(array(
                'title' => $this->module->title . ' - Нэмэх',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/partner/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->partner->editFormData_model(array('id' => $this->input->post('id')));
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlPartnerParentMultiRowDropdown'] = $this->partner->controlPartnerParentMultiRowDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->parent_id, 'id' => $this->body['row']->id, 'parentId' => 0, 'space' => ''));

            $this->body['controlCityDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'cityId', 'parentId' => 12, 'selectedId' => $this->body['row']->city_id));
            $this->body['controlSoumDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'soumId', 'parentId' => $this->body['row']->city_id, 'selectedId' => $this->body['row']->soum_id, 'disabled' => ($this->body['row']->soum_id == 0 ? 'true' : 'false')));
            $this->body['controlStreetDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'streetId', 'parentId' => $this->body['row']->soum_id, 'selectedId' => $this->body['row']->street_id, 'disabled' => ($this->body['row']->street_id == 0 ? 'true' : 'false')));
            $this->body['controlThemeLayoutRadio'] = $this->themelayout->controlThemeLayoutRadio_model(array('themeLayoutId' => $this->body['row']->theme_layout_id, 'modId' => $this->body['row']->mod_id, 'isCategory' => 0));
            
            echo json_encode(array(
                'title' => $this->module->title . ' - Засах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/partner/formEdit', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        $this->getUID = getUID('partner');
        generateUrl(array('modId' => $this->input->post('modId'), 'contId' => $this->getUID, 'url' => $this->input->post('url'), 'mode' => 'insert', 'langId' => $this->session->userdata['adminLangId']));
        echo json_encode($this->partner->insert_model(array('getUID' => $this->getUID)));
    }

    public function update() {
        generateUrl(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('id'), 'url' => $this->input->post('url'), 'mode' => 'update', 'langId' => $this->session->userdata['adminLangId']));
        echo json_encode($this->partner->update_model());
    }

    public function delete() {
        echo json_encode($this->partner->delete_model());
    }

    function searchForm() {
        $this->body['row'] = $this->partner->addFormData_model();

        echo json_encode(array(
            'title' => 'Дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/partner/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    public function import() {
        $this->partner->import_model();
    }
    
    public function controlPartnerDropdown() {
        echo json_encode($this->partner->controlPartnerDropdown_model(array('modId' => $this->input->get('modId'), 'selectedId' => $this->input->get('selectedId'), 'name' => $this->input->get('name'))));
    }
    
    public function controlPartnerMultiListDropdown() {
        echo json_encode($this->partner->controlPartnerMultiListDropdown_model(array(
            'name' => $this->input->post('name'), 
            'modId' => $this->input->post('modId'), 
            'contId' => $this->input->post('contId'),
            'isExtraValue' => $this->input->post('isExtraValue'),
            'addFunction' => $this->input->post('addFunction'),
            'removeFunction' => $this->input->post('removeFunction'),
            'addButtonName' => $this->input->post('addButtonName'),
            'removeButtonName' => $this->input->post('removeButtonName'),
            'initControlHtml' => $this->input->post('initControlHtml'),
            'isDeleteButton' => $this->input->post('isDeleteButton'))));
    }
    
    public function dataUpdate() {
        $this->partner->dataUpdate_model();
    }

}
