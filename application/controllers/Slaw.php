<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Slaw extends CI_Controller {

    public static $path = "slaw/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Suser_model', 'user');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Sthemelayout_model', 'themelayout');
        $this->load->model('Systemowner_model', 'systemowner');
        $this->load->model('Slaw_model', 'law');
        $this->load->model('Sbreadcrumb_model', 'breadcrumb');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Spartner_model', 'partner');
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/law.js');
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
            $config["total_rows"] = $this->law->listsCount_model(array('modId' => $this->body['modId'], 'keyword' => $this->input->get('keyword'), 'catId' => $this->input->get('catId'), 'organizationId' => $this->input->get('campId')));
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

            $this->body['dataHtml'] = $this->law->lists_model(array(
                'title' => $this->body['module']->title,
                'modId' => $this->body['modId'],
                'keyword' => $this->input->get('keyword'),
                'catId' => $this->input->get('catId'),
                'organizationId' => $this->input->get('campId'),
                'limit' => $config["per_page"],
                'page' => $page,
                'paginationHtml' => $this->pagination->create_links()));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/law/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/editor/ckeditor/ckeditor.js', '/assets/system/core/law.js');
            $this->body = array();
            $this->body['mode'] = 'insert';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $this->body['row'] = $this->law->addFormData_model();
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));

            $this->body['row']['emptyTabContent'] = $this->load->view('/error/systemownerEmptyTab', '', TRUE);
            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlAuthorDropdown'] = $this->user->controlAuthorDropdown_model(array('selectedId' => $this->session->adminUserId, 'name' => 'authorId'));
            $this->body['controlThemeLayoutRadio'] = $this->themelayout->controlThemeLayoutRadio_model(array('themeLayoutId' => 1, 'modId' => $this->body['modId'], 'isCategory' => 0));
            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('selectedId' => $this->body['row']['partner_id']));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/law/formGeneral', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/editor/ckeditor/ckeditor.js', '/assets/system/core/law.js');
            $this->body = array();
            $this->body['mode'] = 'update';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $this->body['row'] = $this->law->editFormData_model(array('id' => $this->uri->segment(4)));
            
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => $this->body['row']['cat_id'], 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlAuthorDropdown'] = $this->user->controlAuthorDropdown_model(array('selectedId' => $this->body['row']['author_id'], 'name' => 'authorId', 'isDisabled' => false));
            $this->body['controlThemeLayoutRadio'] = $this->themelayout->controlThemeLayoutRadio_model(array('themeLayoutId' => $this->body['row']['theme_layout_id'], 'modId' => $this->body['row']['mod_id'], 'isCategory' => 0));
            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('selectedId' => $this->body['row']['partner_id']));
            
            $this->body['param']['modId'] = $this->body['modId'];
            $this->body['param']['contId'] = $this->body['row']['id'];

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/law/formGeneral', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        $this->getUID = getUID('content');
        generateUrl(array('modId' => $this->input->post('modId'), 'contId' => $this->getUID, 'url' => $this->input->post('url'), 'mode' => 'insert'));
        echo json_encode($this->law->insert_model(array('getUID' => $this->getUID)));
    }

    public function update() {

        generateUrl(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('id'), 'url' => $this->input->post('url'), 'mode' => 'update'));
        echo json_encode($this->law->update_model());
    }

    public function isActive() {
        echo json_encode($this->law->isActive_model());
    }

    public function delete() {
        echo json_encode($this->law->delete_model());
    }

    public function uploadImage() {
        echo json_encode(uploadImageSource(array('fieldName' => $this->input->post('fieldName'), 'path' => UPLOADS_CONTENT_PATH)));
    }

    public function removeImage() {
        echo json_encode(removeImageSource(array('fieldName' => $this->input->post('fieldName'), 'path' => UPLOADS_CONTENT_PATH)));
    }

    public function mediaForm() {
        $data['contId'] = $this->input->post('contId');
        $data['modId'] = $this->input->post('modId');
        $data['mediaId'] = $this->input->post('mediaId');
        $data['row'] = $this->law->mediaAddFormData_model();
        if ($this->input->post('mode') == 'mediaUpdate') {
            $data['row'] = $this->law->mediaEditFormData_model(array('id' => $this->input->post('mediaId')));
        }

        echo json_encode(
                array(
                    "title" => "Медиа файл",
                    "html" => $this->load->view(MY_ADMIN . '/law/formMedia', $data, true),
                    "btn_ok" => "Хадгалах",
                    "btn_no" => "Хаах"));
    }

    public function mediaInsert() {

        $this->newFile = array(
            'nameMn' => '', 'fileTypeMn' => '',
            'nameEn' => '', 'fileTypeEn' => '');

        if ($this->input->post('type') == 1) {
            $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
            $config['allowed_types'] = UPLOAD_IMAGE_TYPE;
            $config['max_size'] = UPLOAD_FILE_MAX_SIZE;
            $config['max_width'] = UPLOAD_IMAGE_MAX_WIDTH;
            $config['max_height'] = UPLOAD_IMAGE_MAX_HEIGHT;
            $config['remove_spaces'] = true;
            $config['encrypt_name'] = true;
            
            $this->load->library('upload', $config);

            if (isset($_FILES['attachFileMn']['name']) and $this->upload->do_upload('attachFileMn')) {

                $this->newFile['resultMn'] = $this->upload->data();
                $this->newFile['fileTypeMn'] = $this->newFile['resultMn']['file_type'];
                $this->newFile['nameMn'] = $this->newFile['resultMn']['file_name'];
                imageReSizeGaz(array(
                    'source_image' => $this->newFile['resultMn']['file_name'],
                    'new_image' => CROP_SMALL . $this->newFile['nameMn'],
                    'height' => SMALL_HEIGHT,
                    'width' => SMALL_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->newFile['resultMn']['file_name'],
                    'new_image' => CROP_MEDIUM . $this->newFile['nameMn'],
                    'height' => MEDIUM_HEIGHT,
                    'width' => MEDIUM_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->newFile['resultMn']['file_name'],
                    'new_image' => CROP_LARGE . $this->newFile['nameMn'],
                    'height' => LARGE_HEIGHT,
                    'width' => LARGE_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->newFile['resultMn']['file_name'],
                    'new_image' => CROP_BIG . $this->newFile['nameMn'],
                    'height' => BIG_HEIGHT,
                    'width' => BIG_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
            }

            if (isset($_FILES['attachFileEn']['name']) and $this->upload->do_upload('attachFileEn')) {
                $this->newFile['resultEn'] = $this->upload->data();
                $this->newFile['fileTypeEn'] = $this->newFile['resultEn']['file_type'];
                $this->newFile['nameEn'] = $this->newFile['resultEn']['file_name'];
                imageReSizeGaz(array(
                    'source_image' => $this->newFile['resultEn']['file_name'],
                    'new_image' => CROP_SMALL . $this->newFile['nameEn'],
                    'height' => SMALL_HEIGHT,
                    'width' => SMALL_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->newFile['resultEn']['file_name'],
                    'new_image' => CROP_MEDIUM . $this->newFile['nameEn'],
                    'height' => MEDIUM_HEIGHT,
                    'width' => MEDIUM_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->newFile['resultEn']['file_name'],
                    'new_image' => CROP_LARGE . $this->newFile['nameEn'],
                    'height' => LARGE_HEIGHT,
                    'width' => LARGE_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->newFile['resultEn']['file_name'],
                    'new_image' => CROP_BIG . $this->newFile['nameEn'],
                    'height' => BIG_HEIGHT,
                    'width' => BIG_WIDTH,
                    'upload_path' => $config['upload_path']
                ));

            }
        } else if ($this->input->post('type') == 2) {
            $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
            $config['allowed_types'] = UPLOAD_OFFICE_TYPE;
            $config['max_size'] = UPLOAD_FILE_MAX_SIZE;
            $config['remove_spaces'] = true;
            $config['encrypt_name'] = true;
            $this->load->library('upload', $config);

            if (isset($_FILES['attachFileMn']['name']) and $this->upload->do_upload('attachFileMn')) {

                $this->newFile['resultMn'] = $this->upload->data();
                $this->newFile['fileTypeMn'] = $this->newFile['resultMn']['file_type'];
                $this->newFile['nameMn'] = $this->newFile['resultMn']['file_name'];
            }

            if (isset($_FILES['attachFileEn']['name']) and $this->upload->do_upload('attachFileEn')) {
                $this->newFile['resultEn'] = $this->upload->data();
                $this->newFile['fileTypeEn'] = $this->newFile['resultEn']['file_type'];
                $this->newFile['nameEn'] = $this->newFile['resultEn']['file_name'];
            }
        } else {
            
            $this->newFile['fileTypeMn'] = 'youtube';
            $this->newFile['nameMn'] = $this->input->post('videoIdMn');
            
            $this->newFile['fileTypeEn'] = 'youtube';
            $this->newFile['nameEn'] = $this->input->post('videoIdEn');
        }
        
        if ($this->law->mediaInsert_model(array('fileNameMn' => $this->newFile['nameMn'], 'fileNameEn' => $this->newFile['nameEn'], 'fileTypeMn' => $this->newFile['fileTypeMn'], 'fileTypeEn' => $this->newFile['fileTypeEn']))) {
            echo json_encode(array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...'));
        }
    }

    public function mediaUpdate() {

        $this->newFile = array(
            'nameMn' => '', 'fileTypeMn' => '',
            'nameEn' => '', 'fileTypeEn' => '');

        if ($this->input->post('type') == 1) {
            $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
            $config['allowed_types'] = UPLOAD_IMAGE_TYPE;
            $config['max_size'] = UPLOAD_FILE_MAX_SIZE;
            $config['max_width'] = UPLOAD_IMAGE_MAX_WIDTH;
            $config['max_height'] = UPLOAD_IMAGE_MAX_HEIGHT;
            $config['remove_spaces'] = true;
            $config['encrypt_name'] = true;
            $this->load->library('upload', $config);

            if (isset($_FILES['attachFileMn']['name']) and $this->upload->do_upload('attachFileMn')) {

                $this->newFile['resultMn'] = $this->upload->data();
                $this->newFile['fileTypeMn'] = $this->newFile['resultMn']['file_type'];
                $this->newFile['nameMn'] = $this->newFile['resultMn']['file_name'];
                imageReSizeGaz(array(
                    'source_image' => $this->newFile['resultMn']['file_name'],
                    'new_image' => CROP_SMALL . $this->newFile['nameMn'],
                    'height' => SMALL_HEIGHT,
                    'width' => SMALL_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->newFile['resultMn']['file_name'],
                    'new_image' => CROP_MEDIUM . $this->newFile['nameMn'],
                    'height' => MEDIUM_HEIGHT,
                    'width' => MEDIUM_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->newFile['resultMn']['file_name'],
                    'new_image' => CROP_LARGE . $this->newFile['nameMn'],
                    'height' => LARGE_HEIGHT,
                    'width' => LARGE_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->newFile['resultMn']['file_name'],
                    'new_image' => CROP_BIG . $this->newFile['nameMn'],
                    'height' => BIG_HEIGHT,
                    'width' => BIG_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
            } else {
                $this->newFile['nameMn'] = $this->input->post('oldAttachFileMn');
                $this->newFile['fileTypeMn'] = $this->input->post('oldFileTypeMn');
            }

            if (isset($_FILES['attachFileEn']['name']) and $this->upload->do_upload('attachFileEn')) {
                $this->newFile['resultEn'] = $this->upload->data();
                $this->newFile['fileTypeEn'] = $this->newFile['resultEn']['file_type'];
                $this->newFile['nameEn'] = $this->newFile['resultEn']['file_name'];
                imageReSizeGaz(array(
                    'source_image' => $this->newFile['resultEn']['file_name'],
                    'new_image' => CROP_SMALL . $this->newFile['nameEn'],
                    'height' => SMALL_HEIGHT,
                    'width' => SMALL_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->newFile['resultEn']['file_name'],
                    'new_image' => CROP_MEDIUM . $this->newFile['nameEn'],
                    'height' => MEDIUM_HEIGHT,
                    'width' => MEDIUM_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->newFile['resultEn']['file_name'],
                    'new_image' => CROP_LARGE . $this->newFile['nameEn'],
                    'height' => LARGE_HEIGHT,
                    'width' => LARGE_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->newFile['resultEn']['file_name'],
                    'new_image' => CROP_BIG . $this->newFile['nameEn'],
                    'height' => BIG_HEIGHT,
                    'width' => BIG_WIDTH,
                    'upload_path' => $config['upload_path']
                ));

            } else {
                $this->newFile['nameEn'] = $this->input->post('oldAttachFileEn');
                $this->newFile['fileTypeEn'] = $this->input->post('oldFileTypeEn');
            }

        } else if ($this->input->post('type') == 2) {
            
            $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
            $config['allowed_types'] = UPLOAD_OFFICE_TYPE;
            $config['max_size'] = UPLOAD_FILE_MAX_SIZE;
            $config['remove_spaces'] = true;
            $config['encrypt_name'] = true;
            $this->load->library('upload', $config);

            if (isset($_FILES['attachFileMn']['name']) and $this->upload->do_upload('attachFileMn')) {

                $this->newFile['resultMn'] = $this->upload->data();
                $this->newFile['fileTypeMn'] = $this->newFile['resultMn']['file_type'];
                $this->newFile['nameMn'] = $this->newFile['resultMn']['file_name'];
                
            } else {
                $this->newFile['nameMn'] = $this->input->post('oldAttachFileMn');
                $this->newFile['fileTypeMn'] = $this->input->post('oldFileTypeMn');
            }

            if (isset($_FILES['attachFileEn']['name']) and $this->upload->do_upload('attachFileEn')) {
                $this->newFile['resultEn'] = $this->upload->data();
                $this->newFile['fileTypeEn'] = $this->newFile['resultEn']['file_type'];
                $this->newFile['nameEn'] = $this->newFile['resultEn']['file_name'];
            } else {
                $this->newFile['nameEn'] = $this->input->post('oldAttachFileEn');
                $this->newFile['fileTypeEn'] = $this->input->post('oldFileTypeEn');
            }
        } else {
            
            $this->newFile['nameMn'] = $this->input->post('oldAttachFileMn');
            $this->newFile['fileTypeMn'] = $this->input->post('oldFileTypeMn');
                
            $this->newFile['nameEn'] = $this->input->post('oldAttachFileEn');
            $this->newFile['fileTypeEn'] = $this->input->post('oldFileTypeEn');
                
            removeImageSource(array('fieldName' => $this->input->post('oldAttachFileMn'), 'path' => UPLOADS_CONTENT_PATH));
            removeImageSource(array('fieldName' => $this->input->post('oldAttachFileEn'), 'path' => UPLOADS_CONTENT_PATH));
        }

        if ($this->law->mediaUpdate_model(array('fileNameMn' => $this->newFile['nameMn'], 'fileNameEn' => $this->newFile['nameEn'], 'fileTypeMn' => $this->newFile['fileTypeMn'], 'fileTypeEn' => $this->newFile['fileTypeEn']))) {
            echo json_encode(array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...'));
        }
    }

    public function mediaList() {
        echo json_encode($this->law->mediaList_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('contId'), 'type' => $this->input->post('type'))));
    }

    public function mediaDelete() {
        echo json_encode($this->law->mediaDelete_model());
    }

    function searchForm() {
        $this->body = array();
        $this->body['modId'] = $this->input->post('modId');
        $this->body['controlOrganization'] = $this->reservation->controlCheckBoxCampList_model(array('organizationId' => 0));
        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->input->post('modId'), 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1));
        echo json_encode(array(
            'title' => 'Жагсаалтаас хайлт хийх',
            'html' => $this->load->view(MY_ADMIN . '/law/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

}
