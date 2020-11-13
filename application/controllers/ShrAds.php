<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ShrAds extends CI_Controller {

    public static $path = "shrAds/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('ShrAds_model', 'hrAds');
        $this->load->model('Suser_model', 'user');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Sthemelayout_model', 'themelayout');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Spermission_model', 'permission');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('ScontentMedia_model', 'scontentMedia');
        $this->load->model('SclickCounter_model', 'clickCounter');

        $this->modId = 77;
        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->isActiveDepartment = 'is_active_control';
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->footer['jsFile'] = array('/assets/system/editor/ckeditor/ckeditor.js', '/assets/system/core/_hrAds.js', '/assets/system/core/_contentMedia.js', '/assets/system/core/_contentComment.js');

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
                $this->load->view(MY_ADMIN . '/hrAds/index', $this->body);
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


            //total rows count
            $totalRec = $this->hrAds->listsCount_model(array(
                'auth' => $this->auth,
                'catId' => $this->input->get('catId'),
                'partnerId' => $this->input->get('partnerId'),
                'peopleId' => $this->input->get('peopleId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'keyword' => $this->input->get('keyword'),
                'departmentId' => $this->input->get('departmentId')));

            $result = $this->hrAds->lists_model(array(
                'auth' => $this->auth,
                'catId' => $this->input->get('catId'),
                'partnerId' => $this->input->get('partnerId'),
                'peopleId' => $this->input->get('peopleId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'keyword' => $this->input->get('keyword'),
                'departmentId' => $this->input->get('departmentId'),
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {


            $this->body['contentJsFile'] = array();

            $this->body['row'] = $this->hrAds->addFormData_model();

            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' нэмэх';

            $this->body['controlHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
                'isMultiple' => 'true',
                'name' => 'departmentId[]',
                'selectedId' => 0,
                'data' => $this->hrAds->getSendDepartmentId_model(array('adsId' => 0)),
                'departmentId' => $this->nifsDepartmentId));

            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/hrAds/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['contentJsFiles'] = array('/assets/system/core/_contentMedia.js', '/assets/system/core/_contentComment.js');

            $this->body['row'] = $this->hrAds->editFormData_model(array('selectedId' => $this->input->post('id')));

            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            
            $this->body['controlHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
                'isMultiple' => 'true',
                'name' => 'departmentId[]',
                'selectedId' => 0,
                'data' => $this->hrAds->getSendDepartmentId_model(array('adsId' => $this->input->post('id'))),
                'departmentId' => $this->nifsDepartmentId));

            echo json_encode(array(
                'title' => $this->module->title . ' засах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/hrAds/formEdit', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->hrAds->insert_model(array('getUID' => getUID('hr_ads'))));
    }

    public function update() {
        echo json_encode($this->hrAds->update_model());
    }

    public function delete() {
        echo json_encode($this->hrAds->delete_model());
    }

    public function getData() {

        echo json_encode($this->hrAds->editFormData_model(array('selectedId' => $this->input->post('id'))));
    }

    public function getPhoneToken() {

        echo json_encode($this->hrAds->getPhoneToken_model(array('adsId' => $this->input->post('adsId'))));
    }

    public function sendNotificationCounter() {
        echo json_encode($this->hrAds->sendNotificationCounter_model(array('selectedId' => $this->input->post('selectedId'))));
    }

    public function searchForm() {
        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->hrAds->addFormData_model();

            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id, 'required' => true));
            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('allInId' => array($this->session->adminPartnerId), 'selectedId' => 0, 'required' => true));
            $this->body['controlAuthorDropdown'] = $this->user->controlUserDropDown_model(array('selectedId' => $this->session->adminUserId, 'name' => 'authorId'));
            $this->body['controlThemeLayoutRadio'] = $this->themelayout->controlThemeLayoutRadio_model(array('themeLayoutId' => 1, 'modId' => $this->body['row']->mod_id, 'isCategory' => 0));


            echo json_encode(array(
                'title' => $this->module->title . ' дэлгэрэнгүй хайлт',
                'btn_yes' => 'Хайх',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/hrAds/formSearch', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function catList() {
        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->footer['jsFile'] = array();

            $this->body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'isModule',
                'moduleMenuId' => $this->uri->segment(3),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));

            $this->module = $this->module->getData_model(array('id' => $this->body['auth']->modId));

            $this->body['category'] = $this->category->getData_model(array('selectedId' => $this->uri->segment(4)));

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['category']->title . ' | ' . $this->module->title));

            //total rows count
            $config["base_url"] = base_url('shrAds/catList/' . $this->uri->segment(3) . '/' . $this->uri->segment(4) . ($this->urlString != '' ? '?' . $this->urlString : ''));
            $config["total_rows"] = $this->hrAds->mListsCount_model(array(
                'moduleMenuId' => $this->uri->segment(3),
                'catId' => $this->uri->segment(4),
                'partnerId' => $this->input->get('partnerId'),
                'peopleId' => $this->input->get('peopleId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'keyword' => $this->input->get('keyword'),
                'departmentRoleId' => $this->session->adminDepartmentRoleId,
                'departmentId' => $this->session->adminDepartmentId));

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

            $this->body['html'] = $this->hrAds->catList_model(array(
                'category' => $this->body['category'],
                'isBackButton' => 0,
                'title' => $this->module->title . ' - ' . $this->body['category']->title,
                'moduleMenuId' => $this->uri->segment(3),
                'catId' => $this->uri->segment(4),
                'partnerId' => $this->input->get('partnerId'),
                'peopleId' => $this->input->get('peopleId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'keyword' => $this->input->get('keyword'),
                'totalRows' => $config["total_rows"],
                'limit' => $config["per_page"],
                'page' => $page,
                'path' => 'catList',
                'paginationHtml' => $this->pagination->create_links()));





            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/hrAds/catList', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function show() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->footer['jsFile'] = array('/assets/system/core/_comment.js');

            $this->body['row'] = $this->hrAds->getData_model(array('selectedId' => $this->uri->segment(4)));

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['module']->title));

            $this->body['contentMedia'] = $this->scontentMedia->getListData_model(array('contId' => $this->body['row']->id, 'modId' => $this->body['row']->mod_id));
            $this->body['hrPeople'] = $this->hrPeople->getData_model(array('selectedId' => $this->body['row']->created_user_id));

            $this->body['controlHrPeopleDepartmentDropDown'] = $this->hrPeopleDepartment->getData_model(array('name' => 'departmentId', 'selectedId' => $this->body['hrPeople']->department_id));
            $this->body['controlHrPeoplePositionDropDown'] = $this->hrPeoplePosition->getData_model(array('name' => 'positionId', 'selectedId' => $this->body['hrPeople']->position_id));

            $this->clickCounter->clickCounter_model(array('table' => 'hr_ads', 'selectedId' => $this->body['row']->id));

            $this->body['showItemMirrorList'] = $this->hrAds->showItemMirrorList_model(array('moduleMenuId' => $this->uri->segment(3)));
            $this->body['showItemPeopleList'] = $this->hrAds->showItemPeopleList_model(array('moduleMenuId' => $this->uri->segment(3), 'hrPeople' => $this->body['hrPeople']));


            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/hrAds/show', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function mLists() {

        //http://forensics.gov.mn/shrAds/mlists/?moduleMenuId=62&per_page=0&userId=49
        $this->urlString = '';

        $authenticationData = $this->permission->getUserPermissionData_model(array('selectedId' => $this->input->get('userId')));

        $this->auth = authentication(array('authentication' => $authenticationData, 'moduleMenuId' => $this->input->get('moduleMenuId')));
        $this->module = $this->module->getData_model(array('id' => $this->modId));
        $this->user = $this->user->getData_model(array('selectedId' => $this->input->get('userId')));
        $this->hrPeople = $this->hrPeople->getData_model(array('selectedId' => $this->user->id));


        $page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

        //total rows count
        $totalRec = $this->hrAds->mListsCount_model(array(
            'authenticationData' => $this->auth,
            'moduleMenuId' => $this->input->get('moduleMenuId'),
            'userId' => $this->input->get('userId'),
            'modId' => $this->auth->modId,
            'catId' => $this->input->get('catId'),
            'inDate' => $this->input->get('inDate'),
            'outDate' => $this->input->get('outDate'),
            'keyword' => $this->input->get('keyword'),
            'departmentRoleId' => $this->user->department_role_id,
            'departmentId' => $this->user->department_id));

        //pagination configuration
        $config['base_url'] = base_url('mLists');
        $config['modId'] = $this->input->get('modId');
        $config['total_rows'] = $totalRec;
        $config["cur_page"] = $page;
        $config['per_page'] = PAGINATION_PER_PAGE;
        $config['num_links'] = PAGINATION_NUM_LINKS;
        $config['link_func'] = '_initContent';
        $this->ajax_pagination->initialize($config);


        //get posts data
        echo json_encode($this->hrAds->mLists_model(array(
                    'title' => $this->module->title,
                    'authenticationData' => $this->auth,
                    'moduleMenuId' => $this->input->get('moduleMenuId'),
                    'userId' => $this->input->get('userId'),
                    'modId' => $this->auth->modId,
                    'catId' => $this->input->get('catId'),
                    'inDate' => $this->input->get('inDate'),
                    'outDate' => $this->input->get('outDate'),
                    'keyword' => $this->input->get('keyword'),
                    'departmentRoleId' => $this->user->department_role_id,
                    'departmentId' => $this->user->department_id,
                    'totalRows' => $totalRec,
                    'limit' => $config["per_page"],
                    'page' => $page,
                    'path' => 'lists',
                    'paginationHtml' => $this->ajax_pagination->create_links())));
    }

    public function mShow() {

        //http://forensics.gov.mn/shrAds/mShow/?moduleMenuId=62&selectedId=5&userId=49
        echo json_encode($this->hrAds->mGetData_model(array('selectedId' => $this->input->get('selectedId'))));
    }

}
