<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ShrAdsViews extends CI_Controller {

    public static $path = "shrAdsViews/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('ShrAdsViews_model', 'shrAdsViews');
        $this->load->model('Suser_model', 'user');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Sthemelayout_model', 'themelayout');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('ScontentMedia_model', 'scontentMedia');
        $this->load->model('Spermission_model', 'permission');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('ShrPeoplePosition_model', 'hrPeoplePosition');
        $this->load->model('SclickCounter_model', 'clickCounter');

        $this->modId = 77;
        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->footer['jsFile'] = array('/assets/system/core/_hrAdsViews.js', '/assets/system/core/_comment.js');

            $this->body['auth'] = authentication(array('authentication' => $this->session->authentication, 'moduleMenuId' => $this->uri->segment(3)));
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['auth']->modId));

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['module']->title));
            $this->page->deny_model(array('moduleMenuId' => $this->uri->segment(3), 'mode' => 'read', 'createdUserId' => $this->session->adminUserId));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/hrAdsViews/index');
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
            
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function lists() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->auth = authentication(array('authentication' => $this->session->authentication, 'moduleMenuId' => $this->input->get('moduleMenuId')));
            $this->module = $this->module->getData_model(array('id' => $this->auth->modId));

            $page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

            //total rows count
            $totalRec = $this->shrAdsViews->listsCount_model(array(
                'moduleMenuId' => $this->input->get('moduleMenuId'),
                'modId' => $this->modId,
                'catId' => $this->input->get('catId'),
                'partnerId' => $this->input->get('partnerId'),
                'peopleId' => $this->input->get('peopleId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'keyword' => $this->input->get('keyword'),
                'departmentId' => $this->input->get('departmentId')));

            //pagination configuration
            $config['base_url'] = base_url('lists');
            $config['total_rows'] = $totalRec;
            $config["cur_page"] = $page;
            $config['per_page'] = PAGINATION_PER_PAGE;
            $config['num_links'] = PAGINATION_NUM_LINKS;
            $config['link_func'] = '_initLearning';
            $this->ajax_pagination->initialize($config);


            //get posts data
            echo json_encode($this->shrAdsViews->lists_model(array(
                        'title' => $this->module->title,
                        'moduleMenuId' => $this->input->get('moduleMenuId'),
                        'modId' => $this->modId,
                        'catId' => $this->input->get('catId'),
                        'partnerId' => $this->input->get('partnerId'),
                        'peopleId' => $this->input->get('peopleId'),
                        'inDate' => $this->input->get('inDate'),
                        'outDate' => $this->input->get('outDate'),
                        'keyword' => $this->input->get('keyword'),
                        'departmentId' => $this->input->get('departmentId'),
                        'totalRows' => $totalRec,
                        'limit' => $config["per_page"],
                        'page' => $page,
                        'path' => 'lists',
                        'paginationHtml' => $this->ajax_pagination->create_links())));
        }
    }

    public function show() {

        
        $this->body['row'] = $this->shrAdsViews->getItem_model(array('selectedId' => ($this->input->get('selectedId') != null ? $this->input->get('selectedId') : $this->uri->segment(3))));
        $this->body['module'] = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
        $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['module']->title));
        
        $this->body['contentMedia'] = $this->scontentMedia->getListData_model(array('contId' => $this->body['row']->id, 'modId' => $this->body['row']->mod_id));
        $this->body['hrPeople'] = $this->hrPeople->getData_model(array('selectedId' => $this->body['row']->people_id));
        $this->body['relatedList'] = '' /* $this->shrAdsViews->relatedList_model(array(
          'modId' => $this->body['row']->mod_id,
          'currentId' => $this->body['row']->id,
          'peopleId' => $this->body['row']->people_id)) */;

        
        $this->clickCounter->clickCounter_model(array('table' => 'hr_ads', 'selectedId' => $this->body['row']->id));

        if ($this->input->is_ajax_request()) {
            
            echo json_encode($this->load->view(MY_ADMIN . '/hrAdsViews/show', $this->body, TRUE));
            
        } else {

            $this->footer['jsFile'] = array('/assets/system/core/_hrAdsViews.js', '/assets/system/core/_comment.js');

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/hrAdsViews/show', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
            
        }
        
    }

    public function showMediaItem() {
        $this->body['contentMediaItem'] = $this->scontentMedia->getData_model(array('selectedId' => $this->input->post('selectedId')));
        $this->body['author'] = $this->user->getData_model(array('selectedId' => $this->body['contentMediaItem']->created_user_id));
        echo json_encode(array(
            'width' => 1000,
            'title' => $this->body['contentMediaItem']->title,
            'btn_close' => 'Хаах',
            'html' => $this->load->view(MY_ADMIN . '/hrAdsViews/showMediaItem', $this->body, TRUE)));
    }

    public function searchForm() {
        if ($this->session->isLogin === TRUE) {

            $this->auth = authentication(array('authentication' => $this->session->authentication, 'moduleMenuId' => $this->input->post('moduleMenuId')));

            $this->module = $this->module->getData_model(array('id' => 58));

            $this->page->deny_model(array('moduleMenuId' => $this->input->post('moduleMenuId'), 'mode' => 'read', 'createdUserId' => 0));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->modId, 'selectedId' => 0, 'required' => true));
            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('allInId' => array($this->session->adminPartnerId), 'selectedId' => 0, 'required' => true));
            $this->body['controlAuthorDropdown'] = $this->user->controlUserDropDown_model(array('selectedId' => 0, 'name' => 'authorId'));
            $this->body['controlThemeLayoutRadio'] = $this->themelayout->controlThemeLayoutRadio_model(array('themeLayoutId' => 1, 'modId' => $this->modId, 'isCategory' => 0));


            echo json_encode(array(
                'title' => $this->module->title . ' дэлгэрэнгүй хайлт',
                'btn_yes' => 'Хайх',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/hrAdsViews/formSearch', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function mLists() {
//Tumendemberel - 
        //http://forensics.gov.mn/slearning/mLists/?moduleMenuId=31&userId=49

        $this->urlString = '';
        $authenticationData = $this->permission->getUserPermissionData_model(array('selectedId' => $this->input->get('userId')));

        $this->auth = authentication(array('authentication' => $authenticationData, 'moduleMenuId' => $this->input->get('moduleMenuId')));

        $this->module = $this->module->getData_model(array('id' => $this->modId));

        $page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

        //total rows count
        $totalRec = $this->shrAdsViews->mListsCount_model(array(
            'authentication' => $authenticationData,
            'moduleMenuId' => $this->input->get('moduleMenuId'),
            'modId' => $this->modId,
            'catId' => array($this->input->get('catId')),
            'partnerId' => $this->input->get('partnerId'),
            'authorId' => $this->input->get('authorId'),
            'inDate' => $this->input->get('inDate'),
            'outDate' => $this->input->get('outDate'),
            'keyword' => $this->input->get('keyword'),
            'peopleId' => $this->input->get('peopleId')));

        //pagination configuration
        $config['base_url'] = base_url('lists');
        $config['total_rows'] = $totalRec;
        $config["cur_page"] = $page;
        $config['per_page'] = PAGINATION_PER_PAGE;
        $config['num_links'] = PAGINATION_NUM_LINKS;
        $config['link_func'] = '_initLearning';
        $this->ajax_pagination->initialize($config);


        //get posts data
        echo json_encode($this->shrAdsViews->mLists_model(array(
                    'authentication' => $authenticationData,
                    'title' => $this->module->title,
                    'moduleMenuId' => $this->input->get('moduleMenuId'),
                    'modId' => $this->modId,
                    'catId' => array($this->input->get('catId')),
                    'partnerId' => $this->input->get('partnerId'),
                    'authorId' => $this->input->get('authorId'),
                    'inDate' => $this->input->get('inDate'),
                    'outDate' => $this->input->get('outDate'),
                    'keyword' => $this->input->get('keyword'),
                    'peopleId' => $this->input->get('peopleId'),
                    'totalRows' => $totalRec,
                    'limit' => $config["per_page"],
                    'page' => $page,
                    'path' => 'lists',
                    'paginationHtml' => $this->ajax_pagination->create_links())));
    }

    public function mShow() {

        //http://forensics.gov.mn/slearning/mShow/?selectedId=284
        $this->body['row'] = $this->shrAdsViews->getItem_model(array('selectedId' => ($this->input->get('selectedId') != null ? $this->input->get('selectedId') : $this->uri->segment(3))));
        $this->body['contentMedia'] = $this->scontentMedia->getListData_model(array('contId' => $this->body['row']->id, 'modId' => $this->body['row']->mod_id));
        $this->body['author'] = $this->user->getData_model(array('selectedId' => $this->body['row']->people_id));
        $this->clickCounter->clickCounter_model(array('table' => 'content', 'selectedId' => $this->body['row']->id));
        echo json_encode($this->body);
    }

}
