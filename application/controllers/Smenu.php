<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Smenu extends CI_Controller {

    public static $path = "smenu/";

    function __construct() {

        parent::__construct();

        $this->load->model('Spage_model', 'page');
        $this->load->model('Smenu_model', 'menu');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('SlinkType_model', 'linkType');
        $this->load->model('Suser_model', 'user');

        $this->moduleId = 1;

        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->footer['jsFile'] = array('/assets/system/core/_menu.js', '/assets/system/core/_contentMedia.js');

            $this->body['auth'] = authentication(array(
                'permission' => $this->session->authentication, 
                'moduleMenuId' => $this->uri->segment(3),
                'role' => 'read',
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));
            
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['auth']->modId));

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['module']->title));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            if ($this->body['auth']->permission) {
                $this->load->view(MY_ADMIN . '/menu/index', $this->body);
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
                'moduleMenuId' => $this->input->get('moduleMenuId'),
                'role' => 'read',
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));

            $this->module = $this->module->getData_model(array('id' => $this->auth->modId));

            $page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

            //total rows count
            $totalRec = $this->menu->listsCount_model(array(
                'auth' => $this->auth,
                'locationId' => $this->input->get('locationId'),
                'moduleMenuId' => $this->input->get('moduleMenuId'),
                'modId' => $this->input->get('modId'),
                'catId' => $this->input->get('catId'),
                'partnerId' => $this->input->get('partnerId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'keyword' => $this->input->get('keyword')));

            //pagination configuration
            $config['base_url'] = base_url('lists');
            $config['modId'] = $this->input->get('modId');
            $config['total_rows'] = $totalRec;
            $config["cur_page"] = $page;
            $config['per_page'] = PAGINATION_PER_PAGE;
            $config['num_links'] = PAGINATION_NUM_LINKS;
            $config['link_func'] = '_initMenu';
            $this->ajax_pagination->initialize($config);


            //get posts data
            echo json_encode($this->menu->lists_model(array(
                        'auth' => $this->auth,
                        'title' => $this->module->title,
                        'locationId' => $this->input->get('locationId'),
                        'moduleMenuId' => $this->input->get('moduleMenuId'),
                        'modId' => $this->input->get('modId'),
                        'catId' => $this->input->get('catId'),
                        'partnerId' => $this->input->get('partnerId'),
                        'inDate' => $this->input->get('inDate'),
                        'outDate' => $this->input->get('outDate'),
                        'keyword' => $this->input->get('keyword'),
                        'totalRows' => $totalRec,
                        'limit' => $config["per_page"],
                        'page' => $page,
                        'path' => 'lists',
                        'paginationHtml' => $this->ajax_pagination->create_links())));
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->menu->addFormData_model();

            $this->module = $this->module->getData_model(array('id' => $this->body['row']->module_id));
            $this->body['row']->module_title = $this->module->title . ' - нэмэх';
            $this->body['row']->param = json_decode($this->body['row']->param);

            $this->body['controlLoocationListDropdown'] = $this->category->controlLocationListDropdown_model(array(
                'modId' => $this->body['row']->module_id,
                'selectedId' => 0,
                'parentId' => 0,
                'space' => '',
                'counter' => 1));

            $this->body['menuParentList'] = $this->menu->menuParentList_model(array(
                'locationId' => 0,
                'id' => 0,
                'editId' => 0,
                'parentId' => 0,
                'space' => '',
                'counter' => 1));

            $this->body['controlModuleListDropdown'] = $this->menu->controlModuleListDropdown_model();

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
                'modId' => $this->body['row']->mod_id,
                'selectedId' => 0,
                'required' => true));

            $this->body['controlContentListDropdown'] = $this->menu->controlContentListDropdown_model(array(
                'modId' => 0,
                'catId' => 0,
                'contId' => 0));

            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
                'selectedId' => $this->body['row']->partner_id));

            $this->body['controlLinkTypeRadioBox'] = $this->linkType->controlLinkTypeRadioBox_model(array(
                'selectedId' => $this->body['row']->link_type_id));

            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 700,
                'html' => $this->load->view(MY_ADMIN . '/menu/form', $this->body, TRUE)
            ));
        } else {

            redirect(MY_ADMIN);
        }
    }

    public function edit() {
        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->menu->editFormData_model(array('id' => $this->input->post('id')));
            $this->body['row']->param = json_decode($this->body['row']->param);

            $this->module = $this->module->getData_model(array('id' => $this->moduleId));
            $this->body['row']->module_title = $this->module->title . ' - засах';

            $this->body['controlLoocationListDropdown'] = $this->category->controlLocationListDropdown_model(array(
                'modId' => $this->body['row']->module_id,
                'selectedId' => $this->body['row']->location_id,
                'parentId' => 0,
                'space' => '',
                'counter' => 1));

            $this->body['menuParentList'] = $this->menu->menuParentList_model(array(
                'locationId' => $this->body['row']->location_id,
                'id' => $this->body['row']->parent_id,
                'editId' => $this->body['row']->id,
                'parentId' => 0,
                'space' => '',
                'counter' => 1));

            $this->body['controlModuleListDropdown'] = $this->menu->controlModuleListDropdown_model(array('selectedId' => $this->body['row']->mod_id));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
                'modId' => $this->body['row']->mod_id,
                'selectedId' => $this->body['row']->cat_id,
                'parentId' => 0,
                'space' => '',
                'counter' => 1));

            $this->body['controlContentListDropdown'] = $this->menu->controlContentListDropdown_model(array(
                'modId' => $this->body['row']->mod_id,
                'catId' => $this->body['row']->cat_id,
                'contId' => $this->body['row']->cont_id));

            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
                'selectedId' => $this->body['row']->partner_id));

            $this->body['controlLinkTypeRadioBox'] = $this->linkType->controlLinkTypeRadioBox_model(array(
                'selectedId' => $this->body['row']->link_type_id));

            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 700,
                'html' => $this->load->view(MY_ADMIN . '/menu/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        $this->getUID = getUID('menu');
        generateUrl(array('modId' => $this->moduleId, 'contId' => $this->getUID, 'url' => $this->input->post('url'), 'mode' => 'insert', 'langId' => $this->session->userdata['adminLangId']));
        echo json_encode($this->menu->insert_model(array('getUID' => $this->getUID)));
    }

    public function update() {
        generateUrl(array(
            'modId' => $this->moduleId, 
            'contId' => $this->input->post('id'), 
            'url' => $this->input->post('url'), 
            'mode' => 'update', 
            'langId' => $this->session->userdata['adminLangId']));
        echo json_encode($this->menu->update_model());
    }

    public function delete() {
        echo json_encode($this->menu->delete_model());
    }

    public function categoryList() {
        echo json_encode($this->category->controlCategoryListDropdown_model(array('modId' => $this->input->post('modId'), 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1)));
    }

    public function contentList() {
        echo json_encode($this->menu->controlContentListDropdown_model(array(
                    'modId' => $this->input->post('modId'),
                    'catId' => $this->input->post('catId'),
                    'contId' => $this->input->post('contId'))));
    }

    public function menuParentList() {
        echo json_encode($this->menu->menuParentList_model(array(
                    'locationId' => $this->input->post('locationId'),
                    'id' => $this->input->post('id'),
                    'editId' => $this->input->post('id'),
                    'parentId' => $this->input->post('parentId'),
                    'space' => '',
                    'counter' => 1)));
    }

    function searchForm() {
        $this->body = array();
        $this->body['modId'] = $this->input->post('modId');

        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->moduleId, 'selectedId' => 0, 'required' => true));
        $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('selectedId' => 0));
        echo json_encode(array(
            'title' => 'Жагсаалтаас хайлт хийх',
            'html' => $this->load->view(MY_ADMIN . '/menu/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    public function mLists() {

        //http://forensics.gov.mn/smenu/mLists/2/?locationId=256

        echo json_encode($this->menu->mLists_model(array(
                    'locationId' => $this->input->get('locationId'))));
    }

}
