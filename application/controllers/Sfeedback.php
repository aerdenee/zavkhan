<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sfeedback extends CI_Controller {

    public static $path = "sfeedback/";

    function __construct() {
        parent::__construct();
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Systemowner_model', 'systemowner');
        $this->load->model('Sfeedback_model', 'feedback');
        $this->load->model('Scontent_model', 'content');
        $this->load->model('Suser_model', 'user');
        $this->load->model('Sorganization_model', 'organization');
        $this->load->model('Sbreadcrumb_model', 'breadcrumb');
        $this->load->model('Smodule_model', 'module');
    }

    public function index() {

        $this->urlString = '';

        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/feedback.js');
            $this->body = array();
            $this->body['mode'] = 'lists';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $config = array();
            if ($this->input->get('keyword') != '') {
                $this->urlString .= '&keyword=' . $this->input->get('keyword');
            }

            $config["base_url"] = base_url(self::$path . "index/" . $this->body['modId'] . ($this->urlString != '' ? '?' . $this->urlString : ''));

            $config["total_rows"] = $this->feedback->listsCount_model(array('modId' => $this->body['modId'], 'keyword' => $this->input->get('keyword')));
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

            $this->body['dataHtml'] = $this->feedback->lists_model(array(
                'title' => $this->body['module']->title,
                'modId' => $this->body['modId'],
                'keyword' => $this->input->get('keyword'),
                'limit' => $config["per_page"],
                'page' => $page,
                'paginationHtml' => $this->pagination->create_links()));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/feedback/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function add() {
        
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/feedback.js');
            $this->body = array();
            $this->body['mode'] = 'insert';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));
            
            $this->body['row'] = $this->feedback->addFormData_model();
            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/feedback/form', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
            
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/feedback.js');
            $this->body = array();
            $this->body['mode'] = 'update';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));
            
            $this->body['row'] = $this->feedback->editFormData_model(array('id' => $this->uri->segment(4)));
            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => $this->body['row']['cat_id'], 'parentId' => 0, 'space' => '', 'counter' => 1));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/feedback/form', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
            
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        $this->getUID = getUID('feedback');
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

        echo json_encode($this->feedback->insert_model(array('pic' => $this->newFile, 'getUID' => $this->getUID)));
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
                    (is_file($this->uploadPath . CROP_SMALL . $this->oldFile) ? unlink($this->uploadPath . CROP_SMALL . $this->oldFile) : '');
                    (is_file($this->uploadPath . CROP_MEDIUM . $this->oldFile) ? unlink($this->uploadPath . CROP_MEDIUM . $this->oldFile) : '');
                    (is_file($this->uploadPath . CROP_LARGE . $this->oldFile) ? unlink($this->uploadPath . CROP_LARGE . $this->oldFile) : '');
                    (is_file($this->uploadPath . CROP_BIG . $this->oldFile) ? unlink($this->uploadPath . CROP_BIG . $this->oldFile) : '');
                }
            }
        } else {
            $this->newFile = $this->oldFile;
        }
        generateUrl(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('id'), 'url' => $this->input->post('url'), 'mode' => 'update'));
        
        echo json_encode($this->feedback->update_model(array('pic' => $this->newFile)));
    }

    public function lists() {
        echo json_encode($this->feedback->lists_model(array('modId' => $this->input->post('modId'), 'catId' => $this->input->post('catId'))));
    }

    public function isActive() {
        echo json_encode($this->feedback->isActive_model());
    }

    public function delete() {
        echo json_encode($this->feedback->delete_model());
    }

    public function uploadImage() {
        echo json_encode(uploadImageSource(array('fieldName' => $this->input->post('fieldName'), 'path' => UPLOADS_CONTENT_PATH)));
    }
    
    public function removeImage() {
        echo json_encode(removeImageSource(array('fieldName' => $this->input->post('fieldName'), 'path' => UPLOADS_CONTENT_PATH)));
    }

}
