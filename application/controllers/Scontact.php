<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Scontact extends CI_Controller {

    public static $path = "scontact/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scontent_model', 'content');
        $this->load->model('Scontact_model', 'contact');
        $this->load->model('ScontentMedia_model', 'scontentMedia');
        $this->load->model('Suser_model', 'user');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Sthemelayout_model', 'themelayout');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('SclickCounter_model', 'clickCounter');
        
        $this->departmentId = $this->session->adminDepartmentId;
        $this->isActiveDepartment = 'is_active_control';
        
    }

    public function index() {
        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->footer['jsFile'] = array('/assets/system/editor/ckeditor/ckeditor.js', '/assets/system/core/_contact.js', '/assets/system/core/_contentMedia.js', '/assets/system/core/_contentComment.js', '/assets/system/core/_contentGmap.js');

            $this->body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'isModule',
                'moduleMenuId' => $this->uri->segment(3),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['auth']->modId));

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['module']->title));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            if ($this->body['auth']->permission) {
                $this->load->view(MY_ADMIN . '/contact/index', $this->body);
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
                'role' => 'isModule',
                'moduleMenuId' => $this->input->get('moduleMenuId'),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));
            
            //total rows count
            $totalRec = $this->contact->listsCount_model(array(
                'auth' => $this->auth,
                'catId' => $this->input->get('catId'),
                'partnerId' => $this->input->get('partnerId'),
                'peopleId' => $this->input->get('peopleId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'keyword' => $this->input->get('keyword')));

            $result = $this->contact->lists_model(array(
                'auth' => $this->auth,
                'catId' => $this->input->get('catId'),
                'partnerId' => $this->input->get('partnerId'),
                'peopleId' => $this->input->get('peopleId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'keyword' => $this->input->get('keyword'),
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));

        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {


            $this->body['contentJsFile'] = array();
            
            $this->body['row'] = $this->contact->addFormData_model();
            $this->body['row']->module_title = 'Холбоо барих нэмэх';

            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('selectedId' => $this->body['row']->partner_id));
            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id, 'required' => true));
            $this->body['controlThemeLayoutRadio'] = $this->themelayout->controlThemeLayoutRadio_model(array('themeLayoutId' => $this->body['row']->theme_layout_id, 'modId' => $this->body['row']->mod_id, 'isCategory' => 0));

            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/contact/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['contentJsFiles'] = array('/assets/system/core/_contentMedia.js', '/assets/system/core/_contentComment.js');

            $this->body['row'] = $this->contact->editFormData_model(array('selectedId' => $this->input->post('id')));

            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('selectedId' => $this->body['row']->partner_id));
            
            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
                'modId' => $this->body['row']->mod_id, 
                'selectedId' => $this->body['row']->cat_id, 
                'required' => true));
            
            $this->body['controlThemeLayoutRadio'] = $this->themelayout->controlThemeLayoutRadio_model(array(
                'themeLayoutId' => $this->body['row']->theme_layout_id, 
                'modId' => $this->body['row']->mod_id, 
                'isCategory' => 0));


            echo json_encode(array(
                'title' => 'Холбоо барих - засах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/contact/formEdit', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        $this->getUID = getUID('contact');
        generateUrl(array('modId' => $this->input->post('modId'), 'contId' => $this->getUID, 'url' => $this->input->post('url'), 'mode' => 'insert', 'langId' => $this->session->userdata['adminLangId']));
        echo json_encode($this->contact->insert_model(array('getUID' => $this->getUID)));
    }

    public function update() {
        generateUrl(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('id'), 'url' => $this->input->post('url'), 'mode' => 'update', 'langId' => $this->session->userdata['adminLangId']));
        echo json_encode($this->contact->update_model());
    }

    public function delete() {
        echo json_encode($this->contact->delete_model());
    }

    public function searchForm() {
        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->contact->addFormData_model();

            $this->auth = authentication(array('authentication' => $this->session->authentication, 'moduleMenuId' => $this->input->post('moduleMenuId')));
            $this->body['row']->mod_id = $this->auth->modId;

            $this->module = $this->module->getData_model(array('id' => $this->auth->modId));

            $this->page->deny_model(array('moduleMenuId' => $this->input->post('moduleMenuId'), 'mode' => 'read', 'createdUserId' => $this->body['row']->created_user_id));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->auth->modId, 'selectedId' => $this->body['row']->cat_id, 'required' => true));
            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('allInId' => array($this->session->adminPartnerId), 'selectedId' => 0, 'required' => true));
            $this->body['controlAuthorDropdown'] = $this->user->controlUserDropDown_model(array('selectedId' => $this->session->adminUserId, 'name' => 'authorId'));
            $this->body['controlThemeLayoutRadio'] = $this->themelayout->controlThemeLayoutRadio_model(array('themeLayoutId' => 1, 'modId' => $this->auth->modId, 'isCategory' => 0));


            echo json_encode(array(
                'title' => $this->module->title . ' дэлгэрэнгүй хайлт',
                'btn_yes' => 'Хайх',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/contact/formSearch', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function catList() {
        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->footer['jsFile'] = array('/assets/system/core/_comment.js');

            $this->auth = authentication(array('authentication' => $this->session->authentication, 'moduleMenuId' => $this->uri->segment(3)));

            $this->module = $this->module->getData_model(array('id' => $this->auth->modId));
            $this->body['category'] = $this->category->getData_model(array('selectedId' => $this->uri->segment(4)));

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['category']->title . ' | ' . $this->module->title));
            $this->page->deny_model(array('moduleMenuId' => $this->uri->segment(3), 'mode' => 'read', 'createdUserId' => $this->session->adminUserId));

            //total rows count
            $config["base_url"] = base_url('scontent/catList/' . $this->uri->segment(3) . '/' . $this->uri->segment(4) . ($this->urlString != '' ? '?' . $this->urlString : ''));
            $config["total_rows"] = $this->contact->listsCount_model(array(
                'moduleMenuId' => $this->uri->segment(3),
                'modId' => $this->auth->modId,
                'catId' => $this->uri->segment(4),
                'partnerId' => $this->input->get('partnerId'),
                'peopleId' => $this->input->get('peopleId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'keyword' => $this->input->get('keyword'),
                'departmentId' => $this->input->get('departmentId')));

            $page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;
            $config["per_page"] = PAGINATION_PER_PAGE;
            $config["uri_segment"] = 5;
            $config['full_tag_open'] = '<ul class="pagination bootpag pagination-separated pagination-sm">';
            $config['full_tag_close'] = '</ul>';
            $config['num_links'] = PAGINATION_NUM_LINKS;
            $config['page_query_string'] = TRUE;
            $config['prev_link'] = '&#171;';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['next_link'] = '&#187;';
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

            $this->body['html'] = $this->contact->catList_model(array(
                'isBackButton' => 0,
                'title' => $this->module->title . ' - ' . $this->body['category']->title,
                'moduleMenuId' => $this->uri->segment(3),
                'modId' => $this->auth->modId,
                'catId' => $this->uri->segment(4),
                'partnerId' => $this->input->get('partnerId'),
                'peopleId' => $this->input->get('peopleId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'keyword' => $this->input->get('keyword'),
                'departmentId' => $this->input->get('departmentId'),
                'totalRows' => $config["total_rows"],
                'limit' => $config["per_page"],
                'page' => $page,
                'path' => 'catList',
                'paginationHtml' => $this->pagination->create_links()));





            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/contact/catList', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
        }
    }

    public function show() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->contact->getItem_model(array('selectedId' => $this->uri->segment(4)));
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['module']->title));

            $this->body['contentMedia'] = $this->scontentMedia->getListData_model(array('contId' => $this->body['row']->id, 'modId' => $this->body['row']->mod_id));
            $this->body['hrPeople'] = $this->hrPeople->getData_model(array('selectedId' => $this->body['row']->people_id));

            $this->body['controlHrPeopleDepartmentDropDown'] = $this->hrPeopleDepartment->getData_model(array('name' => 'departmentId', 'selectedId' => $this->body['hrPeople']->department_id));
            $this->body['controlHrPeoplePositionDropDown'] = $this->hrPeoplePosition->getData_model(array('name' => 'positionId', 'selectedId' => $this->body['hrPeople']->position_id));

            $this->clickCounter->clickCounter_model(array('table' => 'content', 'selectedId' => $this->body['row']->id));

            $this->footer['jsFile'] = array('/assets/system/core/_comment.js');

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/contact/show', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
        }
    }

    public function mLists() {

        //http://forensics.gov.mn/scontent/mLists/?catid=459&page=0&limit=10
        //total rows count
        $totalRec = $this->contact->mListsCount_model(array(
            'catId' => $this->input->get('catId'),
            'keyword' => $this->input->get('keyword')));

        //get posts data
        echo json_encode(array(
            'total' => $totalRec,
            'result' => $this->contact->mLists_model(array(
                'catId' => $this->input->get('catId'),
                'keyword' => $this->input->get('keyword'),
                'page' => $this->input->get('page'),
                'limit' => $this->input->get('limit')))));
    }

    public function mShow() {

        //http://forensics.gov.mn/scontent/mShow/?selectedId=295
        $this->body['row'] = $this->contact->getItem_model(array('selectedId' => $this->input->get('selectedId')));
        $this->body['contentMedia'] = $this->scontentMedia->getListData_model(array('contId' => $this->body['row']->id, 'modId' => $this->body['row']->mod_id));
        $this->body['hrPeople'] = $this->hrPeople->getData_model(array('selectedId' => $this->body['row']->people_id));
        $this->clickCounter->clickCounter_model(array('table' => 'content', 'selectedId' => $this->body['row']->id));
        echo json_encode($this->body);
    }

    public function backupdate() {
        $this->getUID = getUID('content');

        echo json_encode($this->contact->backupdate_model(array('getUID' => $this->getUID)));
    }

}
