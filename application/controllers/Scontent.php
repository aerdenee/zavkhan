<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Scontent extends CI_Controller {

    public static $path = "scontent/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scontent_model', 'content');
        $this->load->model('ScontentMedia_model', 'scontentMedia');
        $this->load->model('Suser_model', 'user');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Sthemelayout_model', 'themelayout');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('SclickCounter_model', 'clickCounter');
    }

    public function index() {

        if ($this->session->isLogin === TRUE) {

            $header['cssFile'] = array();
            $footer['jsFile'] = array('/assets/system/editor/ckeditor/ckeditor.js', '/assets/system/core/_content.js', '/assets/system/core/_contentMedia.js', '/assets/system/core/_masterMediaType.js', '/assets/system/core/_contentComment.js');

            $body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'isModule',
                'moduleMenuId' => $this->uri->segment(3),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));
            $body['module'] = $this->module->getData_model(array('id' => $body['auth']->modId));

            $header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $body['module']->title));

            $this->load->view(MY_ADMIN . '/header', $header);
            if ($body['auth']->permission) {
                $this->load->view(MY_ADMIN . '/content/index', $body);
            } else {
                $this->load->view(MY_ADMIN . '/page/deny', $body);
            }
            $this->load->view(MY_ADMIN . '/footer', $footer);
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function lists() {

        if ($this->session->isLogin === TRUE) {

            $auth = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'isModule',
                'moduleMenuId' => $this->input->get('moduleMenuId'),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));

            //total rows count
            $totalRec = $this->content->listsCount_model(array(
                'auth' => $auth,
                'modId' => $auth->modId,
                'catId' => $this->input->get('catId'),
                'partnerId' => $this->input->get('partnerId'),
                'userId' => $this->input->get('userId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'keyword' => $this->input->get('keyword'),
                'departmentId' => $this->input->get('departmentId')));

            $result = $this->content->lists_model(array(
                'auth' => $auth,
                'modId' => $auth->modId,
                'catId' => $this->input->get('catId'),
                'partnerId' => $this->input->get('partnerId'),
                'userId' => $this->input->get('userId'),
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


            $body['contentJsFile'] = array();

            $body['row'] = $this->content->addFormData_model();

            $body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'create',
                'moduleMenuId' => $this->input->post('moduleMenuId'),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));

            $body['row']->mod_id = $body['auth']->modId;
            $module = $this->module->getData_model(array('id' => $body['row']->mod_id));
            $body['row']->module_title = $module->title . ' нэмэх';

            $body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $body['row']->mod_id, 'selectedId' => $body['row']->cat_id, 'required' => true));
            $body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('selectedId' => $body['row']->partner_id));
            $body['controlHrPeopleListDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array('selectedId' => $body['row']->people_id, 'name' => 'peopleId'));
            $body['controlThemeLayoutRadio'] = $this->themelayout->controlThemeLayoutRadio_model(array('themeLayoutId' => 1, 'modId' => $body['row']->mod_id, 'isCategory' => 0));


            echo json_encode(array(
                'title' => $body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/content/form', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['contentJsFiles'] = array('/assets/system/core/_contentMedia.js', '/assets/system/core/_contentComment.js');

            $this->body['row'] = $this->content->editFormData_model(array('selectedId' => $this->input->post('id')));

            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id, 'required' => true));
            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('selectedId' => $this->body['row']->partner_id));
            $this->body['controlHrPeopleListDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array('selectedId' => $this->body['row']->people_id, 'name' => 'peopleId'));
            $this->body['controlThemeLayoutRadio'] = $this->themelayout->controlThemeLayoutRadio_model(array('themeLayoutId' => $this->body['row']->theme_layout_id, 'modId' => $this->body['row']->mod_id, 'isCategory' => 0));


            echo json_encode(array(
                'title' => $this->module->title . ' засах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/content/formEdit', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        $this->getUID = getUID('content');
        generateUrl(array('modId' => $this->input->post('modId'), 'contId' => $this->getUID, 'url' => $this->input->post('url'), 'mode' => 'insert', 'langId' => $this->session->userdata['adminLangId']));
        echo json_encode($this->content->insert_model(array('getUID' => $this->getUID)));
    }

    public function update() {

        generateUrl(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('id'), 'url' => $this->input->post('url'), 'mode' => 'update', 'langId' => $this->session->userdata['adminLangId']));
        echo json_encode($this->content->update_model());
    }

    public function delete() {
        echo json_encode($this->content->delete_model());
    }

    public function searchForm() {
        if ($this->session->isLogin === TRUE) {

            $body['row'] = $this->content->addFormData_model();

            $body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'isModule',
                'moduleMenuId' => $this->input->post('moduleMenuId'),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));
            $body['row']->mod_id = $body['auth']->modId;

            $module = $this->module->getData_model(array('id' => $body['row']->mod_id));

            $body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $body['row']->mod_id, 'selectedId' => $body['row']->cat_id, 'required' => true));
            $body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('selectedId' => 0));
            $body['controlUserDropDown'] = $this->user->controlUserDropDown_model(array('selectedId' => 0, 'name' => 'userId'));


            echo json_encode(array(
                'title' => $module->title . ' дэлгэрэнгүй хайлт',
                'btn_yes' => 'Хайх',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/content/formSearch', $body, TRUE)
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

            $this->auth = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'isModule',
                'moduleMenuId' => $this->uri->segment(3),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));

            $this->module = $this->module->getData_model(array('id' => $this->auth->modId));
            $this->body['category'] = $this->category->getData_model(array('selectedId' => $this->uri->segment(4)));

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['category']->title . ' | ' . $this->module->title));

            //total rows count
            $config["base_url"] = base_url('scontent/catList/' . $this->uri->segment(3) . '/' . $this->uri->segment(4) . ($this->urlString != '' ? '?' . $this->urlString : ''));
            $config["total_rows"] = $this->content->catListCount_model(array(
                'moduleMenuId' => $this->uri->segment(3),
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

            $this->body['html'] = $this->content->catList_model(array(
                'category' => $this->body['category'],
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
            $this->load->view(MY_ADMIN . '/content/catList', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
        }
    }

    public function show() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->content->getItem_model(array('selectedId' => $this->uri->segment(4)));
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['module']->title));

            $this->body['contentMedia'] = $this->scontentMedia->getListData_model(array('contId' => $this->body['row']->id, 'modId' => $this->body['row']->mod_id));
            $this->body['hrPeople'] = $this->hrPeople->getData_model(array('selectedId' => $this->body['row']->people_id));

            $this->clickCounter->clickCounter_model(array('table' => 'content', 'selectedId' => $this->body['row']->id));

            $this->body['showItemMirrorList'] = $this->content->showItemMirrorList_model(array('moduleMenuId' => $this->uri->segment(3)));
            $this->body['showItemPeopleList'] = $this->content->showItemPeopleList_model(array('moduleMenuId' => $this->uri->segment(3), 'hrPeople' => $this->body['hrPeople']));

            $this->footer['jsFile'] = array('/assets/system/core/_comment.js');

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/content/show', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
        }
    }

    public function mLists() {

        //http://forensics.gov.mn/scontent/mLists/?catid=459&page=0&limit=10
        //total rows count
        $totalRec = $this->content->mListsCount_model(array(
            'catId' => $this->input->get('catId'),
            'keyword' => $this->input->get('keyword')));

        //get posts data
        echo json_encode(array(
            'total' => $totalRec,
            'result' => $this->content->mLists_model(array(
                'catId' => $this->input->get('catId'),
                'keyword' => $this->input->get('keyword'),
                'page' => $this->input->get('page'),
                'limit' => $this->input->get('limit')))));
    }

    public function mShow() {

        //http://forensics.gov.mn/scontent/mShow/?selectedId=295
        $this->body['row'] = $this->content->getItem_model(array('selectedId' => $this->input->get('selectedId')));
        $this->body['contentMedia'] = $this->scontentMedia->getListData_model(array('contId' => $this->body['row']->id, 'modId' => $this->body['row']->mod_id));
        $this->body['hrPeople'] = $this->hrPeople->getData_model(array('selectedId' => $this->body['row']->people_id));
        $this->clickCounter->clickCounter_model(array('table' => 'content', 'selectedId' => $this->body['row']->id));
        echo json_encode($this->body);
    }

    public function dataUpdate() {
        echo json_encode($this->content->dataUpdate_model());
    }

}
