<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sservice extends CI_Controller {

    public static $path = "sservice/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Sservice_model', 'service');
        $this->load->model('Suser_model', 'user');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Sthemelayout_model', 'themelayout');
        $this->load->model('Systemowner_model', 'systemowner');
        $this->load->model('Sbreadcrumb_model', 'breadcrumb');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Spartner_model', 'partner');
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/service.js');
            $this->body = array();
            $this->body['mode'] = 'lists';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));


            $config = array();

            if (count($this->input->get('campId')) > 0) {
                foreach ($this->input->get('campId') as $key => $value) {
                    $this->urlString .= '&campId[]=' . $this->input->get('campId[' . $key . ']');
                }
            }

            if ($this->input->get('keyword')) {
                $this->urlString .= '&keyword=' . $this->input->get('keyword');
            }

            if ($this->input->get('catId')) {
                $this->urlString .= '&catId=' . $this->input->get('catId');
            }

            $config["base_url"] = base_url(self::$path . "index/" . $this->body['modId'] . ($this->urlString != '' ? '?' . $this->urlString : ''));
            $config["total_rows"] = $this->service->listsCount_model(array('modId' => $this->body['modId'], 'keyword' => $this->input->get('keyword'), 'catId' => $this->input->get('catId'), 'organizationId' => $this->input->get('campId')));
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

            $this->body['dataHtml'] = $this->service->lists_model(array(
                'title' => $this->body['module']->title,
                'modId' => $this->body['modId'],
                'keyword' => $this->input->get('keyword'),
                'catId' => $this->input->get('catId'),
                'organizationId' => $this->input->get('campId'),
                'limit' => $config["per_page"],
                'page' => $page,
                'paginationHtml' => $this->pagination->create_links()));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/service/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/editor/ckeditor/ckeditor.js', '/assets/system/core/service.js');
            $this->body = array();
            $this->body['mode'] = 'insert';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $this->body['row'] = $this->service->addFormData_model();
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));

            $this->body['row']['emptyTabContent'] = $this->load->view('/error/systemownerEmptyTab', '', TRUE);
            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlAuthorDropdown'] = $this->user->controlAuthorDropdown_model(array('selectedId' => $this->session->adminUserId, 'name' => 'authorId'));
            $this->body['controlThemeLayoutRadio'] = $this->themelayout->controlThemeLayoutRadio_model(array('themeLayoutId' => 1, 'modId' => $this->body['modId'], 'isCategory' => 0));
            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('selectedId' => $this->body['row']['partner_id']));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/service/formGeneral', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/editor/ckeditor/ckeditor.js', '/assets/system/core/service.js');
            $this->body = array();
            $this->body['mode'] = 'update';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $this->body['row'] = $this->service->editFormData_model(array('id' => $this->uri->segment(4)));
            if ($this->body['row']['tour_param_mn'] == '') {
                $this->body['row']['tour_param_mn'] = $this->imageIcon();
            }
            $this->body['row']['tour_param_mn'] = json_decode($this->body['row']['tour_param_mn']);
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => $this->body['row']['cat_id'], 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlAuthorDropdown'] = $this->user->controlAuthorDropdown_model(array('selectedId' => $this->body['row']['author_id'], 'name' => 'authorId', 'isDisabled' => false));
            $this->body['controlThemeLayoutRadio'] = $this->themelayout->controlThemeLayoutRadio_model(array('themeLayoutId' => $this->body['row']['theme_layout_id'], 'modId' => $this->body['row']['mod_id'], 'isCategory' => 0));
            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('selectedId' => $this->body['row']['partner_id']));
            
            $this->body['param']['modId'] = $this->body['modId'];
            $this->body['param']['contId'] = $this->body['row']['id'];

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/service/formGeneral', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        $this->getUID = getUID('content');
        $this->newFile = $this->input->post('pic');

        if ($this->newFile != '') {
            $this->uploadPath = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
            $result = imageCropGaz(array(
                'source_image' => $this->newFile,
                'new_image' => $this->newFile,
                'crop_width' => $this->input->post('crop_width'),
                'crop_height' => $this->input->post('crop_height'),
                'crop_x' => $this->input->post('crop_x'),
                'crop_y' => $this->input->post('crop_y'),
                'upload_path' => $this->uploadPath
            ));
            if ($result['status'] === 'success') {
                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_SMALL . $this->newFile,
                    'height' => SMALL_HEIGHT,
                    'width' => SMALL_WIDTH,
                    'upload_path' => $this->uploadPath
                ));

                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_MEDIUM . $this->newFile,
                    'height' => MEDIUM_HEIGHT,
                    'width' => MEDIUM_WIDTH,
                    'upload_path' => $this->uploadPath
                ));

                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_LARGE . $this->newFile,
                    'height' => LARGE_HEIGHT,
                    'width' => LARGE_WIDTH,
                    'upload_path' => $this->uploadPath
                ));
                (is_file($this->uploadPath . $result['response']) ? unlink($this->uploadPath . $result['response']) : '');
            }
        }
        generateUrl(array('modId' => $this->input->post('modId'), 'contId' => $this->getUID, 'url' => $this->input->post('url'), 'mode' => 'insert'));
        echo json_encode($this->service->insert_model(array('pic' => $this->newFile, 'getUID' => $this->getUID)));
    }

    public function update() {

        $this->oldFile = $this->input->post('oldPic');
        $this->newFile = $this->input->post('pic');
        if ($this->newFile != '') {

            $this->uploadPath = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
            $result = imageCropGaz(array(
                'source_image' => $this->newFile,
                'new_image' => $this->newFile,
                'crop_width' => $this->input->post('crop_width'),
                'crop_height' => $this->input->post('crop_height'),
                'crop_x' => $this->input->post('crop_x'),
                'crop_y' => $this->input->post('crop_y'),
                'upload_path' => $this->uploadPath
            ));
            if ($result['status'] === 'success') {
                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_SMALL . $this->newFile,
                    'height' => SMALL_HEIGHT,
                    'width' => SMALL_WIDTH,
                    'upload_path' => $this->uploadPath
                ));

                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_MEDIUM . $this->newFile,
                    'height' => MEDIUM_HEIGHT,
                    'width' => MEDIUM_WIDTH,
                    'upload_path' => $this->uploadPath
                ));

                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_LARGE . $this->newFile,
                    'height' => LARGE_HEIGHT,
                    'width' => LARGE_WIDTH,
                    'upload_path' => $this->uploadPath
                ));
                (is_file($this->uploadPath . $result['response']) ? unlink($this->uploadPath . $result['response']) : '');

                if ($this->oldFile != '') {
                    removeImageSource(array('fieldName' => $this->oldFile, 'path' => UPLOADS_CONTENT_PATH));
                }
            }
        } else {
            $this->newFile = $this->oldFile;
        }
        generateUrl(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('id'), 'url' => $this->input->post('url'), 'mode' => 'update'));
        echo json_encode($this->service->update_model(array('pic' => $this->newFile)));
    }

    public function isActive() {
        echo json_encode($this->service->isActive_model());
    }

    public function delete() {
        echo json_encode($this->service->delete_model());
    }

    public function uploadImage() {
        echo json_encode(uploadImageSource(array('fieldName' => $this->input->post('fieldName'), 'path' => UPLOADS_CONTENT_PATH)));
    }

    public function removeImage() {
        echo json_encode(removeImageSource(array('fieldName' => $this->input->post('fieldName'), 'path' => UPLOADS_CONTENT_PATH)));
    }

    public function updateVerticalImage() {

        $this->oldFile = $this->input->post('verticalOldPic');
        $this->newFile = $this->input->post('verticalPic');
        if ($this->newFile != '') {

            $this->uploadPath = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
            $result = imageCropGaz(array(
                'source_image' => $this->newFile,
                'new_image' => $this->newFile,
                'crop_width' => $this->input->post('verticalCropWidth'),
                'crop_height' => $this->input->post('verticalCropHeight'),
                'crop_x' => $this->input->post('verticalCropX'),
                'crop_y' => $this->input->post('verticalCropY'),
                'upload_path' => $this->uploadPath
            ));
            if ($result['status'] === 'success') {
                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_SMALL . $this->newFile,
                    'height' => SMALL_HEIGHT,
                    'width' => SMALL_WIDTH,
                    'upload_path' => $this->uploadPath
                ));

                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_MEDIUM . $this->newFile,
                    'height' => MEDIUM_HEIGHT,
                    'width' => MEDIUM_WIDTH,
                    'upload_path' => $this->uploadPath
                ));

                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_LARGE . $this->newFile,
                    'height' => LARGE_HEIGHT,
                    'width' => LARGE_WIDTH,
                    'upload_path' => $this->uploadPath
                ));
                (is_file($this->uploadPath . $result['response']) ? unlink($this->uploadPath . $result['response']) : '');

                if ($this->oldFile != '') {
                    removeImageSource(array('fieldName' => $this->oldFile, 'path' => UPLOADS_CONTENT_PATH));
                }
            }
        }
        echo json_encode($this->service->updateVerticalImage_model(array('pic' => $this->newFile, 'id' => $this->input->post('id'))));
    }

    public function mediaForm() {
        $data['contId'] = $this->input->post('contId');
        $data['modId'] = $this->input->post('modId');
        $data['mediaId'] = $this->input->post('mediaId');
        $data['row'] = $this->service->mediaAddFormData_model();
        if ($this->input->post('mode') == 'mediaUpdate') {
            $data['row'] = $this->service->mediaEditFormData_model(array('id' => $this->input->post('mediaId')));
        }

        echo json_encode(
                array(
                    "title" => "Медиа файл",
                    "html" => $this->load->view(MY_ADMIN . '/service/formMedia', $data, true),
                    "btn_ok" => "Хадгалах",
                    "btn_no" => "Хаах"));
    }

    public function mediaInsert() {

        $this->newFileMn = '';
        $this->newFileEn = '';
        $this->fileTypeMn = '';
        $this->fileTypeEn = '';

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

                $this->resultMn = $this->upload->data();
                $this->fileTypeMn = $this->resultMn['file_type'];
                $this->newFileMn = getImageUID() . $this->resultMn['file_ext'];
                imageReSizeGaz(array(
                    'source_image' => $this->resultMn['file_name'],
                    'new_image' => CROP_SMALL . $this->newFileMn,
                    'height' => SMALL_HEIGHT,
                    'width' => SMALL_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultMn['file_name'],
                    'new_image' => CROP_MEDIUM . $this->newFileMn,
                    'height' => MEDIUM_HEIGHT,
                    'width' => MEDIUM_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultMn['file_name'],
                    'new_image' => CROP_LARGE . $this->newFileMn,
                    'height' => LARGE_HEIGHT,
                    'width' => LARGE_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultMn['file_name'],
                    'new_image' => CROP_BIG . $this->newFileMn,
                    'height' => BIG_HEIGHT,
                    'width' => BIG_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                (is_file($config['upload_path'] . $this->resultMn['file_name']) ? unlink($config['upload_path'] . $this->resultMn['file_name']) : '');
            }

            if (isset($_FILES['attachFileEn']['name']) and $this->upload->do_upload('attachFileEn')) {
                $this->resultEn = $this->upload->data();
                $this->fileTypeEn = $this->resultEn['file_type'];
                $this->newFileEn = getImageUID() . $this->resultEn['file_ext'];
                imageReSizeGaz(array(
                    'source_image' => $this->resultEn['file_name'],
                    'new_image' => CROP_SMALL . $this->newFileEn,
                    'height' => SMALL_HEIGHT,
                    'width' => SMALL_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultEn['file_name'],
                    'new_image' => CROP_MEDIUM . $this->newFileEn,
                    'height' => MEDIUM_HEIGHT,
                    'width' => MEDIUM_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultEn['file_name'],
                    'new_image' => CROP_LARGE . $this->newFileEn,
                    'height' => LARGE_HEIGHT,
                    'width' => LARGE_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultEn['file_name'],
                    'new_image' => CROP_BIG . $this->newFileEn,
                    'height' => BIG_HEIGHT,
                    'width' => BIG_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                (is_file($config['upload_path'] . $this->resultEn['file_name']) ? unlink($config['upload_path'] . $this->resultEn['file_name']) : '');
            }
        } else if ($this->input->post('type') == 2) {
            $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
            $config['allowed_types'] = UPLOAD_OFFICE_TYPE;
            $config['max_size'] = UPLOAD_FILE_MAX_SIZE;
            $config['remove_spaces'] = true;
            $config['encrypt_name'] = true;
            $this->load->library('upload', $config);

            if (isset($_FILES['attachFileMn']['name']) and $this->upload->do_upload('attachFileMn')) {

                $this->resultMn = $this->upload->data();
                $this->fileTypeMn = $this->resultMn['file_type'];
                $this->newFileMn = $this->resultMn['file_name'];
            }

            if (isset($_FILES['attachFileEn']['name']) and $this->upload->do_upload('attachFileEn')) {
                $this->resultEn = $this->upload->data();
                $this->fileTypeEn = $this->resultEn['file_type'];
                $this->newFileEn = $this->resultEn['file_name'];
            }
        } else {
            $this->newFileMn = $this->input->post('videoIdMn');
            $this->newFileEn = $this->input->post('videoIdEn');
        }

        if ($this->service->mediaInsert_model(array('fileNameMn' => $this->newFileMn, 'fileNameEn' => $this->newFileEn, 'fileTypeMn' => $this->fileTypeMn, 'fileTypeEn' => $this->fileTypeEn))) {
            echo json_encode(array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...'));
        }
    }

    public function mediaUpdate() {

        $this->newFileMn = '';
        $this->newFileEn = '';
        $this->fileTypeMn = '';
        $this->fileTypeEn = '';

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

                $this->resultMn = $this->upload->data();
                $this->fileTypeMn = $this->resultMn['file_type'];
                $this->newFileMn = getImageUID() . $this->resultMn['file_ext'];
                imageReSizeGaz(array(
                    'source_image' => $this->resultMn['file_name'],
                    'new_image' => CROP_SMALL . $this->newFileMn,
                    'height' => SMALL_HEIGHT,
                    'width' => SMALL_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultMn['file_name'],
                    'new_image' => CROP_MEDIUM . $this->newFileMn,
                    'height' => MEDIUM_HEIGHT,
                    'width' => MEDIUM_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultMn['file_name'],
                    'new_image' => CROP_LARGE . $this->newFileMn,
                    'height' => LARGE_HEIGHT,
                    'width' => LARGE_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultMn['file_name'],
                    'new_image' => CROP_BIG . $this->newFileMn,
                    'height' => BIG_HEIGHT,
                    'width' => BIG_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                (is_file($config['upload_path'] . $this->resultMn['file_name']) ? unlink($config['upload_path'] . $this->resultMn['file_name']) : '');
            } else {
                $this->newFileMn = $this->input->post('oldAttachFileMn');
                $this->fileTypeMn = $this->input->post('oldFileTypeMn');
            }

            if (isset($_FILES['attachFileEn']['name']) and $this->upload->do_upload('attachFileEn')) {
                $this->resultEn = $this->upload->data();
                $this->fileTypeEn = $this->resultEn['file_type'];
                $this->newFileEn = getImageUID() . $this->resultEn['file_ext'];
                imageReSizeGaz(array(
                    'source_image' => $this->resultEn['file_name'],
                    'new_image' => CROP_SMALL . $this->newFileEn,
                    'height' => SMALL_HEIGHT,
                    'width' => SMALL_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultEn['file_name'],
                    'new_image' => CROP_MEDIUM . $this->newFileEn,
                    'height' => MEDIUM_HEIGHT,
                    'width' => MEDIUM_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultEn['file_name'],
                    'new_image' => CROP_LARGE . $this->newFileEn,
                    'height' => LARGE_HEIGHT,
                    'width' => LARGE_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultEn['file_name'],
                    'new_image' => CROP_BIG . $this->newFileEn,
                    'height' => BIG_HEIGHT,
                    'width' => BIG_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                (is_file($config['upload_path'] . $this->resultEn['file_name']) ? unlink($config['upload_path'] . $this->resultEn['file_name']) : '');
            } else {
                $this->newFileEn = $this->input->post('oldAttachFileEn');
                $this->fileTypeEn = $this->input->post('oldFileTypeEn');
            }
        } else if ($this->input->post('type') == 2) {
            $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
            $config['allowed_types'] = UPLOAD_OFFICE_TYPE;
            $config['max_size'] = UPLOAD_FILE_MAX_SIZE;
            $config['remove_spaces'] = true;
            $config['encrypt_name'] = true;
            $this->load->library('upload', $config);

            if (isset($_FILES['attachFileMn']['name']) and $this->upload->do_upload('attachFileMn')) {

                $this->resultMn = $this->upload->data();
                $this->fileTypeMn = $this->resultMn['file_type'];
                $this->newFileMn = $this->resultMn['file_name'];
                
            } else {
                $this->newFileMn = $this->input->post('oldAttachFileMn');
                $this->fileTypeMn = $this->input->post('oldFileTypeMn');
            }

            if (isset($_FILES['attachFileEn']['name']) and $this->upload->do_upload('attachFileEn')) {
                $this->resultEn = $this->upload->data();
                $this->fileTypeEn = $this->resultEn['file_type'];
                $this->newFileEn = $this->resultEn['file_name'];
            } else {
                $this->newFileEn = $this->input->post('oldAttachFileEn');
                $this->fileTypeEn = $this->input->post('oldFileTypeEn');
            }
        } else {
            $this->newFileMn = $this->input->post('videoIdMn');
            $this->newFileEn = $this->input->post('videoIdEn');
            removeImageSource(array('fieldName' => $this->input->post('oldAttachFileMn'), 'path' => UPLOADS_CONTENT_PATH));
            removeImageSource(array('fieldName' => $this->input->post('oldAttachFileEn'), 'path' => UPLOADS_CONTENT_PATH));
        }

        if ($this->service->mediaUpdate_model(array('fileNameMn' => $this->newFileMn, 'fileNameEn' => $this->newFileEn, 'fileTypeMn' => $this->fileTypeMn, 'fileTypeEn' => $this->fileTypeEn))) {
            echo json_encode(array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...'));
        }
    }

    public function mediaList() {
        echo json_encode($this->service->mediaList_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('contId'), 'type' => $this->input->post('type'))));
    }

    public function mediaDelete() {
        echo json_encode($this->service->mediaDelete_model());
    }

    function searchForm() {
        $this->body = array();
        $this->body['modId'] = $this->input->post('modId');
        $this->body['controlOrganization'] = $this->reservation->controlCheckBoxCampList_model(array('organizationId' => 0));
        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->input->post('modId'), 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1));
        echo json_encode(array(
            'title' => 'Жагсаалтаас хайлт хийх',
            'html' => $this->load->view(MY_ADMIN . '/service/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    public function tourBackground() {
        echo json_encode($this->service->tourBackground_model());
    }

    public function tourIncludedService() {
        echo json_encode($this->service->tourIncludedService_model());
    }

    public function tourItineraryList() {
        echo json_encode($this->service->tourItineraryList_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('contId'))));
    }

    public function formTourItinerary() {
        $this->data['contId'] = $this->input->post('contId');
        $this->data['modId'] = $this->input->post('modId');
        $this->data['mediaId'] = $this->input->post('mediaId');
        $this->data['type'] = 5;
        $this->data['row'] = $this->service->mediaAddFormData_model();
        if ($this->input->post('mode') == 'tourItineraryUpdate') {
            $this->data['row'] = $this->service->mediaEditFormData_model(array('id' => $this->input->post('mediaId')));
        }

        echo json_encode(
                array(
                    "title" => "Tour itinerary",
                    "html" => $this->load->view(MY_ADMIN . '/service/formTourItinerary', $this->data, true),
                    "btn_ok" => "Хадгалах",
                    "btn_no" => "Хаах"));
    }

    public function tourItineraryInsert() {
        if ($this->service->tourItineraryInsert_model()) {
            echo json_encode(array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...'));
        } else {
            echo json_encode(array('status' => 'error', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...'));
        }
    }

    public function tourItineraryUpdate() {
        if ($this->service->tourItineraryUpdate_model()) {
            echo json_encode(array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...'));
        } else {
            echo json_encode(array('status' => 'error', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...'));
        }
    }

    public function tourItineraryDelete() {
        echo json_encode($this->service->tourItineraryDelete_model());
    }

    public function imageIcon() {
        return json_encode(array(
            array('icon' => 'camel', 'title_mn' => 'Тэмээ', 'title_en' => 'Camel', 'isChecked' => 0),
            array('icon' => 'ger', 'title_mn' => 'Гэр', 'title_en' => 'Ger', 'isChecked' => 0),
            array('icon' => 'group', 'title_mn' => 'Грүпп', 'title_en' => 'Group', 'isChecked' => 0),
            array('icon' => 'horse', 'title_mn' => 'Морь', 'title_en' => 'Horse', 'isChecked' => 0),
            array('icon' => 'hotel', 'title_mn' => 'Буудал', 'title_en' => 'Hotel', 'isChecked' => 0),
            array('icon' => 'plane', 'title_mn' => 'Онгоц', 'title_en' => 'Plane', 'isChecked' => 0)
        ));
    }

    public function timeLineForm() {
        $data['contId'] = $this->input->post('contId');
        $data['modId'] = $this->input->post('modId');
        $data['mediaId'] = $this->input->post('mediaId');
        $data['row'] = $this->service->timeLineAddFormData_model();
        if ($this->input->post('mode') == 'timeLineUpdate') {
            $data['row'] = $this->service->timeLineEditFormData_model(array('id' => $this->input->post('mediaId')));
        }

        echo json_encode(
                array(
                    "title" => "Медиа файл",
                    "html" => $this->load->view(MY_ADMIN . '/service/formTimeLine', $data, true),
                    "btn_ok" => "Хадгалах",
                    "btn_no" => "Хаах"));
    }

    public function timeLineInsert() {

        if ($this->input->post('type') == 1 or $this->input->post('type') == 7) {
            $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
            $config['allowed_types'] = UPLOAD_IMAGE_TYPE;
            $config['max_size'] = UPLOAD_FILE_MAX_SIZE;
            $config['max_width'] = UPLOAD_IMAGE_MAX_WIDTH;
            $config['max_height'] = UPLOAD_IMAGE_MAX_HEIGHT;
            $config['remove_spaces'] = true;
            $config['encrypt_name'] = true;
            $this->load->library('upload', $config);

            if (isset($_FILES['attachFileMn']['name']) and $this->upload->do_upload('attachFileMn')) {

                $this->resultMn = $this->upload->data();
                $this->newFileMn = getImageUID() . $this->resultMn['file_ext'];
                imageReSizeGaz(array(
                    'source_image' => $this->resultMn['file_name'],
                    'new_image' => CROP_SMALL . $this->newFileMn,
                    'height' => SMALL_HEIGHT,
                    'width' => SMALL_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultMn['file_name'],
                    'new_image' => CROP_MEDIUM . $this->newFileMn,
                    'height' => MEDIUM_HEIGHT,
                    'width' => MEDIUM_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultMn['file_name'],
                    'new_image' => CROP_LARGE . $this->newFileMn,
                    'height' => LARGE_HEIGHT,
                    'width' => LARGE_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultMn['file_name'],
                    'new_image' => CROP_BIG . $this->newFileMn,
                    'height' => BIG_HEIGHT,
                    'width' => BIG_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                (is_file($config['upload_path'] . $this->resultMn['file_name']) ? unlink($config['upload_path'] . $this->resultMn['file_name']) : '');
            } else {
                $this->newFileMn = $this->input->post('oldAttachFileMn');
            }

            if (isset($_FILES['attachFileEn']['name']) and $this->upload->do_upload('attachFileEn')) {
                $this->resultEn = $this->upload->data();
                $this->newFileEn = getImageUID() . $this->resultEn['file_ext'];
                imageReSizeGaz(array(
                    'source_image' => $this->resultEn['file_name'],
                    'new_image' => CROP_SMALL . $this->newFileEn,
                    'height' => SMALL_HEIGHT,
                    'width' => SMALL_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultEn['file_name'],
                    'new_image' => CROP_MEDIUM . $this->newFileEn,
                    'height' => MEDIUM_HEIGHT,
                    'width' => MEDIUM_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultEn['file_name'],
                    'new_image' => CROP_LARGE . $this->newFileEn,
                    'height' => LARGE_HEIGHT,
                    'width' => LARGE_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultEn['file_name'],
                    'new_image' => CROP_BIG . $this->newFileEn,
                    'height' => BIG_HEIGHT,
                    'width' => BIG_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                (is_file($config['upload_path'] . $this->resultEn['file_name']) ? unlink($config['upload_path'] . $this->resultEn['file_name']) : '');
            } else {
                $this->newFileEn = $this->input->post('oldAttachFileEn');
            }
        } else {
            $this->newFileMn = $this->input->post('videoIdMn');
            $this->newFileEn = $this->input->post('videoIdEn');
            removeImageSource(array('fieldName' => $this->input->post('oldAttachFileMn'), 'path' => UPLOADS_CONTENT_PATH));
            removeImageSource(array('fieldName' => $this->input->post('oldAttachFileEn'), 'path' => UPLOADS_CONTENT_PATH));
        }

        if ($this->service->timeLineInsert_model(array('fileNameMn' => $this->newFileMn, 'fileNameEn' => $this->newFileEn))) {
            echo json_encode(array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...'));
        } else {
            echo json_encode(array('status' => 'error', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...'));
        }
    }

    public function timeLineUpdate() {
        if ($this->input->post('type') == 1 or $this->input->post('type') == 7) {
            $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
            $config['allowed_types'] = UPLOAD_IMAGE_TYPE;
            $config['max_size'] = UPLOAD_FILE_MAX_SIZE;
            $config['max_width'] = UPLOAD_IMAGE_MAX_WIDTH;
            $config['max_height'] = UPLOAD_IMAGE_MAX_HEIGHT;
            $config['remove_spaces'] = true;
            $config['encrypt_name'] = true;
            $this->load->library('upload', $config);

            if (isset($_FILES['attachFileMn']['name']) and $this->upload->do_upload('attachFileMn')) {

                $this->resultMn = $this->upload->data();
                $this->newFileMn = getImageUID() . $this->resultMn['file_ext'];
                imageReSizeGaz(array(
                    'source_image' => $this->resultMn['file_name'],
                    'new_image' => CROP_SMALL . $this->newFileMn,
                    'height' => SMALL_HEIGHT,
                    'width' => SMALL_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultMn['file_name'],
                    'new_image' => CROP_MEDIUM . $this->newFileMn,
                    'height' => MEDIUM_HEIGHT,
                    'width' => MEDIUM_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultMn['file_name'],
                    'new_image' => CROP_LARGE . $this->newFileMn,
                    'height' => LARGE_HEIGHT,
                    'width' => LARGE_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultMn['file_name'],
                    'new_image' => CROP_BIG . $this->newFileMn,
                    'height' => BIG_HEIGHT,
                    'width' => BIG_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                (is_file($config['upload_path'] . $this->resultMn['file_name']) ? unlink($config['upload_path'] . $this->resultMn['file_name']) : '');
            } else {
                $this->newFileMn = $this->input->post('oldAttachFileMn');
            }

            if (isset($_FILES['attachFileEn']['name']) and $this->upload->do_upload('attachFileEn')) {
                $this->resultEn = $this->upload->data();
                $this->newFileEn = getImageUID() . $this->resultEn['file_ext'];
                imageReSizeGaz(array(
                    'source_image' => $this->resultEn['file_name'],
                    'new_image' => CROP_SMALL . $this->newFileEn,
                    'height' => SMALL_HEIGHT,
                    'width' => SMALL_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultEn['file_name'],
                    'new_image' => CROP_MEDIUM . $this->newFileEn,
                    'height' => MEDIUM_HEIGHT,
                    'width' => MEDIUM_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultEn['file_name'],
                    'new_image' => CROP_LARGE . $this->newFileEn,
                    'height' => LARGE_HEIGHT,
                    'width' => LARGE_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                imageReSizeGaz(array(
                    'source_image' => $this->resultEn['file_name'],
                    'new_image' => CROP_BIG . $this->newFileEn,
                    'height' => BIG_HEIGHT,
                    'width' => BIG_WIDTH,
                    'upload_path' => $config['upload_path']
                ));
                (is_file($config['upload_path'] . $this->resultEn['file_name']) ? unlink($config['upload_path'] . $this->resultEn['file_name']) : '');
            } else {
                $this->newFileEn = $this->input->post('oldAttachFileEn');
            }
        } else {
            $this->newFileMn = $this->input->post('videoIdMn');
            $this->newFileEn = $this->input->post('videoIdEn');
            removeImageSource(array('fieldName' => $this->input->post('oldAttachFileMn'), 'path' => UPLOADS_CONTENT_PATH));
            removeImageSource(array('fieldName' => $this->input->post('oldAttachFileEn'), 'path' => UPLOADS_CONTENT_PATH));
        }

        if ($this->service->timeLineUpdate_model(array('fileNameMn' => $this->newFileMn, 'fileNameEn' => $this->newFileEn))) {
            echo json_encode(array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...'));
        } else {
            echo json_encode(array('status' => 'error', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...'));
        }
    }

    public function timeLineList() {
        echo json_encode($this->service->timeLineList_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('contId'), 'type' => $this->input->post('type'))));
    }

    public function timeLineDelete() {
        echo json_encode($this->service->timeLineDelete_model());
    }

    public function ourTeamForm() {
        $data['contId'] = $this->input->post('contId');
        $data['modId'] = $this->input->post('modId');
        $data['mediaId'] = $this->input->post('mediaId');
        $data['row'] = $this->service->ourTeamAddFormData_model();
        if ($this->input->post('mode') == 'ourTeamUpdate') {
            $data['row'] = $this->service->ourTeamEditFormData_model(array('id' => $this->input->post('mediaId')));
        }

        echo json_encode(
                array(
                    "title" => "Медиа файл",
                    "html" => $this->load->view(MY_ADMIN . '/service/formOurTeam', $data, true),
                    "btn_ok" => "Хадгалах",
                    "btn_no" => "Хаах"));
    }

    public function ourTeamInsert() {
        $this->newFile = '';
        $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
        $config['allowed_types'] = UPLOAD_IMAGE_TYPE;
        $config['max_size'] = UPLOAD_FILE_MAX_SIZE;
        $config['max_width'] = UPLOAD_IMAGE_MAX_WIDTH;
        $config['max_height'] = UPLOAD_IMAGE_MAX_HEIGHT;
        $config['remove_spaces'] = true;
        $config['encrypt_name'] = true;
        $this->load->library('upload', $config);

        if (isset($_FILES['attachFileMn']['name']) and $this->upload->do_upload('attachFileMn')) {
            $this->resultMn = $this->upload->data();
            $this->newFile = $this->resultMn['file_name'];
        } else {
            $this->newFileMn = $this->input->post('oldAttachFileMn');
        }

        echo json_encode($this->service->ourTeamInsert_model(array('fileNameMn' => $this->newFile, 'fileNameEn' => $this->newFile)));
    }

    public function ourTeamUpdate() {
        $this->newFile = '';
        $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
        $config['allowed_types'] = UPLOAD_IMAGE_TYPE;
        $config['max_size'] = UPLOAD_FILE_MAX_SIZE;
        $config['max_width'] = UPLOAD_IMAGE_MAX_WIDTH;
        $config['max_height'] = UPLOAD_IMAGE_MAX_HEIGHT;
        $config['remove_spaces'] = true;
        $config['encrypt_name'] = true;
        $this->load->library('upload', $config);

        if (isset($_FILES['attachFileMn']['name']) and $this->upload->do_upload('attachFileMn')) {
            $this->resultMn = $this->upload->data();
            $this->newFile = $this->resultMn['file_name'];
        } else {
            $this->newFile = $this->input->post('oldAttachFileMn');
        }

        if ($this->service->ourTeamUpdate_model(array('fileNameMn' => $this->newFile, 'fileNameEn' => $this->newFile))) {
            echo json_encode(array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...'));
        } else {
            echo json_encode(array('status' => 'error', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...'));
        }
    }

    public function ourTeamList() {
        echo json_encode($this->service->ourTeamList_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('contId'), 'type' => $this->input->post('type'))));
    }

    public function ourTeamDelete() {
        echo json_encode($this->service->ourTeamDelete_model());
    }

    public function attachFileForm() {
        $data['contId'] = $this->input->post('contId');
        $data['modId'] = $this->input->post('modId');
        $data['mediaId'] = $this->input->post('mediaId');
        $data['row'] = $this->service->attachFileAddFormData_model();
        if ($this->input->post('mode') == 'attachFileUpdate') {
            $data['row'] = $this->service->attachFileEditFormData_model(array('id' => $this->input->post('mediaId')));
        }

        echo json_encode(
                array(
                    "title" => "Хавсралт файл",
                    "html" => $this->load->view(MY_ADMIN . '/service/formAttachFile', $data, true),
                    "btn_ok" => "Хадгалах",
                    "btn_no" => "Хаах"));
    }

    public function attachFileInsert() {
        $this->newFileMn = '';
        $this->newFileEn = '';

        $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_FILE_PATH;
        $config['allowed_types'] = UPLOAD_OFFICE_TYPE;
        $config['max_size'] = UPLOAD_FILE_MAX_SIZE;
        $config['remove_spaces'] = true;
        $config['encrypt_name'] = true;
        $this->load->library('upload', $config);

        if (isset($_FILES['attachFileMn']['name']) and $this->upload->do_upload('attachFileMn')) {
            $this->resultMn = $this->upload->data();
            $this->newFileMn = $this->resultMn['file_name'];
        } else {
            $this->newFileMn = $this->input->post('oldAttachFileMn');
        }

        if (isset($_FILES['attachFileEn']['name']) and $this->upload->do_upload('attachFileEn')) {
            $this->resultEn = $this->upload->data();
            $this->newFileEn = $this->resultEn['file_name'];
        } else {
            $this->newFileEn = $this->input->post('oldAttachFileEn');
        }

        if ($this->service->attachFileInsert_model(array('fileNameMn' => $this->newFileMn, 'fileNameEn' => $this->newFileEn))) {
            echo json_encode(array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...'));
        } else {
            echo json_encode(array('status' => 'error', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...'));
        }
    }

    public function attachFileUpdate() {
        
        $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_FILE_PATH;
            $config['allowed_types'] = UPLOAD_OFFICE_TYPE;
            $config['max_size'] = UPLOAD_FILE_MAX_SIZE;
            $config['remove_spaces'] = true;
            $config['encrypt_name'] = true;
            $this->load->library('upload', $config);

            if (isset($_FILES['attachFileMn']['name']) and $this->upload->do_upload('attachFileMn')) {
                $this->resultMn = $this->upload->data();
                $this->newFileMn = $this->resultEn['file_name'];
                unlink('.' . UPLOADS_FILE_PATH . $this->input->post('oldAttachFileMn'));
            } else {
                $this->newFileMn = $this->input->post('oldAttachFileMn');
            }

            if (isset($_FILES['attachFileEn']['name']) and $this->upload->do_upload('attachFileEn')) {
                $this->resultEn = $this->upload->data();
                $this->newFileEn = $this->resultEn['file_name'];
                unlink('.' . UPLOADS_FILE_PATH . $this->input->post('oldAttachFileEn'));
            } else {
                $this->newFileEn = $this->input->post('oldAttachFileEn');
            }

        if ($this->service->attachFileUpdate_model(array('fileNameMn' => $this->newFileMn, 'fileNameEn' => $this->newFileEn))) {
            echo json_encode(array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...'));
        } else {
            echo json_encode(array('status' => 'error', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...'));
        }
    }

    public function attachFileList() {
        echo json_encode($this->service->attachFileList_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('contId'), 'type' => $this->input->post('type'))));
    }

    public function attachFileDelete() {
        echo json_encode($this->service->attachFileDelete_model());
    }

}
