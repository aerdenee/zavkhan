<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Spoll extends CI_Controller {

    public static $path = "spoll/";

    function __construct() {
        parent::__construct();
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Systemowner_model', 'systemowner');
        $this->load->model('Spoll_model', 'poll');
        $this->load->model('Suser_model', 'user');
        $this->load->model('Sbreadcrumb_model', 'breadcrumb');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Sthemelayout_model', 'themelayout');
        $this->load->model('Spartner_model', 'partner');
    }

    public function index() {

        $this->urlString = '';

        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/poll.js');
            $this->body = array();
            $this->body['mode'] = 'lists';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $config = array();
            if ($this->input->get('keyword')) {
                $this->urlString .= '&keyword=' . $this->input->get('keyword');
            }

            if ($this->input->get('catId')) {
                $this->urlString .= '&catId=' . $this->input->get('catId');
            }


            $config["base_url"] = base_url(self::$path . "index/" . $this->body['modId'] . ($this->urlString != '' ? '?' . $this->urlString : ''));

            $config["total_rows"] = $this->poll->listsCount_model(array('modId' => $this->body['modId'], 'catId' => $this->input->get('catId'), 'keyword' => $this->input->get('keyword')));
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

            $this->body['dataHtml'] = $this->poll->lists_model(array(
                'title' => $this->body['module']->title,
                'modId' => $this->body['modId'],
                'catId' => $this->input->get('catId'),
                'keyword' => $this->input->get('keyword'),
                'limit' => $config["per_page"],
                'page' => $page,
                'paginationHtml' => $this->pagination->create_links()));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/poll/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/poll.js');
            $this->body = array();
            $this->body['mode'] = 'insert';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $this->body['row'] = $this->poll->addFormData_model();
            $this->body['row']->emptyTabContent = $this->load->view('/error/systemownerEmptyTab', '', TRUE);
            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlAuthorDropdown'] = $this->user->controlAuthorDropdown_model(array('selectedId' => $this->session->adminUserId, 'name' => 'authorId', 'isDisabled' => ($this->session->adminAccessTypeId == 2 ? true : false)));
            $this->body['controlThemeLayoutRadio'] = $this->themelayout->controlThemeLayoutRadio_model(array('themeLayoutId' => 1, 'modId' => $this->body['modId'], 'isCategory' => 0));
            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('selectedId' => $this->body['row']->partner_id));

            $this->body['param'] = json_decode(json_encode(array('modId' => $this->body['modId'], 'pollId' => $this->body['row']->id)));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/poll/form', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/poll.js');
            $this->body = array();
            $this->body['mode'] = 'update';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $this->body['row'] = $this->poll->editFormData_model(array('id' => $this->uri->segment(4)));
            $this->body['row']->emptyTabContent = $this->load->view('/error/systemownerEmptyTab', '', TRUE);
            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlAuthorDropdown'] = $this->user->controlAuthorDropdown_model(array('selectedId' => $this->body['row']->author_id, 'name' => 'authorId', 'isDisabled' => false));
            $this->body['controlThemeLayoutRadio'] = $this->themelayout->controlThemeLayoutRadio_model(array('themeLayoutId' => $this->body['row']->theme_layout_id, 'modId' => $this->body['row']->mod_id, 'isCategory' => 0));
            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('selectedId' => $this->body['row']->partner_id));

            $this->body['param'] = json_decode(json_encode(array('modId' => $this->body['modId'], 'pollId' => $this->body['row']->id)));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/poll/form', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        $this->getUID = getUID('poll');
        $this->newFile = array();
        $this->newFile['picMn'] = $this->input->post('picMn');
        $this->newFile['picEn'] = $this->input->post('picEn');

        generateUrl(array('modId' => $this->input->post('modId'), 'contId' => $this->getUID, 'url' => $this->input->post('url'), 'mode' => 'insert'));
        echo json_encode($this->poll->insert_model(array('picMn' => $this->newFile['picMn'], 'picEn' => $this->newFile['picEn'], 'getUID' => $this->getUID)));
    }

    public function update() {

        $this->newFile = array();
        $this->newFile['picMn'] = $this->input->post('oldPicMn');
        $this->newFile['picEn'] = $this->input->post('oldPicEn');
        if ($this->input->post('picMn') != '') {
            $this->newFile['picMn'] = $this->input->post('picMn');
        }
        if ($this->input->post('picEn') != '') {
            $this->newFile['picEn'] = $this->input->post('picEn');
        }
        generateUrl(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('id'), 'url' => $this->input->post('url'), 'mode' => 'update'));
        echo json_encode($this->poll->update_model(array('picMn' => $this->newFile['picMn'], 'picEn' => $this->newFile['picEn'])));
    }

    public function lists() {
        echo json_encode($this->poll->lists_model(array('modId' => $this->input->post('modId'), 'catId' => $this->input->post('catId'))));
    }

    public function isActive() {
        echo json_encode($this->poll->isActive_model());
    }

    public function delete() {
        echo json_encode($this->poll->delete_model());
    }

    public function uploadImage() {
        echo json_encode(uploadImageSource(array('fieldName' => $this->input->post('fieldName'), 'path' => UPLOADS_CONTENT_PATH)));
    }

    public function removeImage() {
        echo json_encode(removeImageSource(array('fieldName' => $this->input->post('fieldName'), 'path' => UPLOADS_CONTENT_PATH)));
    }

    public function mediaForm() {
        $this->data['pollId'] = $this->input->post('pollId');
        $this->data['modId'] = $this->input->post('modId');
        $this->data['mediaId'] = $this->input->post('mediaId');
        $this->data['row'] = $this->poll->mediaAddFormData_model();
        if ($this->input->post('mode') == 'mediaUpdate') {
            $this->data['row'] = $this->poll->mediaEditFormData_model(array('id' => $this->input->post('mediaId')));
        }

        echo json_encode(
                array(
                    "title" => "Медиа файл",
                    "html" => $this->load->view(MY_ADMIN . '/poll/formDetail', $this->data, true),
                    "btn_ok" => "Хадгалах",
                    "btn_no" => "Хаах"));
    }

    public function mediaInsert() {

        if ($this->poll->mediaInsert_model(array('id' => getUID('poll_detail')))) {
            echo json_encode(array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...'));
        }
    }

    public function mediaUpdate() {

        if ($this->poll->mediaUpdate_model()) {
            echo json_encode(array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...'));
        }
    }

    public function mediaList() {
        echo json_encode($this->poll->mediaList_model(array('modId' => $this->input->post('modId'), 'pollId' => $this->input->post('pollId'))));
    }

    public function mediaDelete() {
        echo json_encode($this->poll->mediaDelete_model());
    }

}
