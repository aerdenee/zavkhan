<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Saccommodation extends CI_Controller {

    public static $path = "saccommodation/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Systemowner_model', 'systemowner');
        $this->load->model('Saccommodation_model', 'accommodation');
        $this->load->model('Suser_model', 'user');
        $this->load->model('Sbreadcrumb_model', 'breadcrumb');
        $this->load->model('Smodule_model', 'module');
    }

    public function index() {
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array();
            $this->body = array();
            $this->body['mode'] = 'lists';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));
            
            $this->body['controlComboCampList'] = $this->accommodation->controlComboCampList_model(array('selectedId' => 0));
            
            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/accommodation/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function add() {
        
        if ($this->session->isLogin === TRUE) {
            $header['cssFile'] = array();
            $header['jsFile'] = array();
            $this->body = array();
            $this->body['mode'] = 'insert';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));
            
            $this->body['row'] = $this->accommodation->addFormData_model();
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));
            
            $this->body['controlRadioBtnCamp'] = $this->accommodation->controlRadioBtnCamp_model(array('campId'=>$this->body['row']['organization_id']));
            $this->body['controlRadioBtnAccommodationType'] = $this->accommodation->controlRadioBtnAccommodationType_model(array('accommodationTypeId'=>$this->body['row']['accommodation_type_id']));
            $this->body['controlRadioBtnAccommodationClass'] = $this->accommodation->controlRadioBtnAccommodationClass_model(array('accommodationClassId'=>$this->body['row']['accommodation_class_id']));
            $this->body['controlRadioBtnAccommodationBed'] = $this->accommodation->controlRadioBtnAccommodationBed_model(array('accommodationBedId'=>$this->body['row']['accommodation_bed_id']));
            
            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/accommodation/form', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
            
        } else {
            redirect(MY_ADMIN);
        }
        
    }

    public function formMap() {
        $data['contId'] = $this->input->post('contId');
        $data['catId'] = $this->input->post('catId');
        $data['modId'] = $this->input->post('modId');
        $data['mapId'] = $this->input->post('id');
        $data['row'] = $this->accommodation->formMapData_model($data['mapId']);
        echo json_encode(
                array(
                    "title" => "Газрын зураг",
                    "html" => $this->load->view(MY_ADMIN . '/accommodation/formMap', $data, true),
                    "btn_ok" => "Хадгалах",
                    "btn_no" => "Хаах"));
    }

    public function formMedia() {
        $data['contId'] = $this->input->post('contId');
        $data['modId'] = $this->input->post('modId');
        $data['mediaId'] = $this->input->post('mediaId');
        $data['row'] = $this->accommodation->formMediaData_model(array('mediaId'=>$data['mediaId']));
        echo json_encode(
                array(
                    "title" => "Фото зураг",
                    "html" => $this->load->view(MY_ADMIN . '/accommodation/formMedia', $data, true),
                    "btn_ok" => "Хадгалах",
                    "btn_no" => "Хаах"));
    }

    public function edit() {
        if ($this->session->isLogin === TRUE) {
            $header['cssFile'] = array();
            $header['jsFile'] = array('https://maps.googleapis.com/maps/api/js?key=' . DEFAULT_GOOGLE_MAP_APK_KEY);
            $this->body = array();
            $this->body['mode'] = 'update';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $this->body['row'] = $this->accommodation->editFormData_model(array('id' => $this->uri->segment(4)));
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));
            
            $this->body['controlRadioBtnCamp'] = $this->accommodation->controlRadioBtnCamp_model(array('campId' => $this->body['row']['organization_id']));
            $this->body['controlRadioBtnAccommodationType'] = $this->accommodation->controlRadioBtnAccommodationType_model(array('accommodationTypeId' => $this->body['row']['accommodation_type_id']));
            $this->body['controlRadioBtnAccommodationClass'] = $this->accommodation->controlRadioBtnAccommodationClass_model(array('accommodationClassId' => $this->body['row']['accommodation_class_id']));
            $this->body['controlRadioBtnAccommodationBed'] = $this->accommodation->controlRadioBtnAccommodationBed_model(array('accommodationBedId' => $this->body['row']['accommodation_bed_id']));
            
            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/accommodation/form', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
            
        } else {
            redirect(MY_ADMIN);
        }
        
    }

    public function insert() {
        $getUID = getUID('accommodation');
        $this->contentmap->deleteInsert_model(array('modId' => $this->input->post('modId'), 'catId' => $this->input->post('catId'), 'contId' => $getUID, 'mapId' => $this->input->post('mapId')));
        $newFile = $this->input->post('pic');
        if ($newFile != '') {
            $upload_path = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
            $result = imageCropGaz(array(
                'source_image' => $newFile,
                'new_image' => $newFile,
                'crop_width' => $this->input->post('crop_width'),
                'crop_height' => $this->input->post('crop_height'),
                'crop_x' => $this->input->post('crop_x'),
                'crop_y' => $this->input->post('crop_y'),
                'upload_path' => $upload_path
            ));
            if ($result['status'] === 'success') {
                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_SMALL . $newFile,
                    'height' => SMALL_HEIGHT,
                    'width' => SMALL_WIDTH,
                    'upload_path' => $upload_path
                ));

                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_MEDIUM . $newFile,
                    'height' => MEDIUM_HEIGHT,
                    'width' => MEDIUM_WIDTH,
                    'upload_path' => $upload_path
                ));

                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_LARGE . $newFile,
                    'height' => LARGE_HEIGHT,
                    'width' => LARGE_WIDTH,
                    'upload_path' => $upload_path
                ));
                (is_file($upload_path . $result['response']) ? unlink($upload_path . $result['response']) : '');
            }
        }

        echo json_encode($this->accommodation->insert_model(array('pic' => $newFile, 'getUID' => $getUID)));
    }

    public function update() {
        $this->oldFile = $this->input->post('oldPic');
        $this->newFile = $this->input->post('pic');
        $this->contentmap->deleteInsert_model(array('modId' => $this->input->post('modId'), 'catId' => $this->input->post('catId'), 'contId' => $this->input->post('id'), 'mapId' => $this->input->post('mapId')));

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
        echo json_encode($this->accommodation->update_model(array('pic'=>$this->newFile)));
    }

    public function lists() {
        echo json_encode($this->accommodation->lists_model(array('modId' => $this->input->post('modId'), 'organizationId' => $this->input->post('organizationId'))));
    }

    public function isActive() {
        echo json_encode($this->accommodation->isActive_model());
    }

    public function delete() {
        echo json_encode($this->accommodation->delete_model());
    }

    public function insertMedia() {
        if ($this->input->post('type') === '1') {
            $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
            $config['allowed_types'] = UPLOAD_IMAGE_TYPE;
            $config['max_size'] = UPLOAD_FILE_MAX_SIZE;
            $config['max_width'] = UPLOAD_IMAGE_MAX_WIDTH;
            $config['max_height'] = UPLOAD_IMAGE_MAX_HEIGHT;
            $config['remove_spaces'] = true;
            $config['encrypt_name'] = true;
            (Integer) $error = 0;
            $files = $_FILES;
            foreach ($files['attachFileMn']['name'] as $key => $value) {
                $_FILES['attachFileMn']['name'] = $files['attachFileMn']['name'][$key];
                $_FILES['attachFileMn']['type'] = $files['attachFileMn']['type'][$key];
                $_FILES['attachFileMn']['tmp_name'] = $files['attachFileMn']['tmp_name'][$key];
                $_FILES['attachFileMn']['error'] = $files['attachFileMn']['error'][$key];
                $_FILES['attachFileMn']['size'] = $files['attachFileMn']['size'][$key];
                if (isset($_FILES['attachFileMn']['name'][$key])) {
                    $this->load->library('upload', $config);
                    if ($this->upload->do_upload('attachFileMn')) {
                        $result = $this->upload->data();
                        $newFile = getImageUID() . $result['file_ext'];
                        imageReSizeGaz(array(
                            'source_image' => $result['file_name'],
                            'new_image' => CROP_SMALL . $newFile,
                            'height' => SMALL_HEIGHT,
                            'width' => SMALL_WIDTH,
                            'upload_path' => $config['upload_path']
                        ));
                        imageReSizeGaz(array(
                            'source_image' => $result['file_name'],
                            'new_image' => CROP_MEDIUM . $newFile,
                            'height' => MEDIUM_HEIGHT,
                            'width' => MEDIUM_WIDTH,
                            'upload_path' => $config['upload_path']
                        ));
                        imageReSizeGaz(array(
                            'source_image' => $result['file_name'],
                            'new_image' => CROP_LARGE . $newFile,
                            'height' => LARGE_HEIGHT,
                            'width' => LARGE_WIDTH,
                            'upload_path' => $config['upload_path']
                        ));
                        copy($config['upload_path'] . $result['file_name'], $config['upload_path'] . $newFile);
                        unlink($config['upload_path'] . $result['file_name']);
                        if ($this->accommodation->insertMedias_model(array('fileNameMn'=>$newFile, 'fileNameEn'=>''))) {
                            $error += 0;
                        } else {
                            $error += 1;
                        }
                    } else {
                        $error += 1;
                    }
                }
            }
            if (empty($error)) {
                echo json_encode(array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...'));
            } else {
                echo json_encode(array('status' => 'error', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...'));
            }
        } else {
            if ($this->accommodation->insertMedias_model($this->input->post('attachFile'))) {
                echo json_encode(array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...'));
            } else {
                echo json_encode(array('status' => 'error', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...'));
            }
        }
    }

    public function updateMedia() {
        $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
        $config['allowed_types'] = UPLOAD_IMAGE_TYPE;
        $config['max_size'] = UPLOAD_FILE_MAX_SIZE;
        $config['remove_spaces'] = true;
        $config['encrypt_name'] = true;
        (Integer) $error = 0;

        if ($this->input->post('type') === '1') {
            if (isset($_FILES['attachFile']['name']['0'])) {
                $_FILES['attachFile']['name'] = $_FILES['attachFile']['name']['0'];
                $_FILES['attachFile']['type'] = $_FILES['attachFile']['type']['0'];
                $_FILES['attachFile']['tmp_name'] = $_FILES['attachFile']['tmp_name']['0'];
                $_FILES['attachFile']['error'] = $_FILES['attachFile']['error']['0'];
                $_FILES['attachFile']['size'] = $_FILES['attachFile']['size']['0'];
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('attachFile')) {
                    $result = $this->upload->data();
                    $newFile = getImageUID() . $result['file_ext'];
                    imageReSizeGaz(array(
                        'source_image' => $result['file_name'],
                        'new_image' => CROP_SMALL . $newFile,
                        'height' => SMALL_HEIGHT,
                        'width' => SMALL_WIDTH,
                        'upload_path' => $config['upload_path']
                    ));
                    imageReSizeGaz(array(
                        'source_image' => $result['file_name'],
                        'new_image' => CROP_MEDIUM . $newFile,
                        'height' => MEDIUM_HEIGHT,
                        'width' => MEDIUM_WIDTH,
                        'upload_path' => $config['upload_path']
                    ));
                    imageReSizeGaz(array(
                        'source_image' => $result['file_name'],
                        'new_image' => CROP_LARGE . $newFile,
                        'height' => LARGE_HEIGHT,
                        'width' => LARGE_WIDTH,
                        'upload_path' => $config['upload_path']
                    ));
                    copy($config['upload_path'] . $result['file_name'], $config['upload_path'] . $newFile);
                    unlink($config['upload_path'] . $result['file_name']);
                    if (is_file($config['upload_path'] . $this->input->post('oldAttachFile'))) {
                        unlink($config['upload_path'] . $this->input->post('oldAttachFile'));
                    }
                    echo json_encode($this->accommodation->updateMedia_model($newFile));
                } else {
                    echo json_encode(array('status' => 'error', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...'));
                }
            } else {
                echo json_encode($this->accommodation->updateMedia_model($this->input->post('oldAttachFile')));
            }
        } else {
            $oldFileName = $this->input->post('oldAttachFile');
            if (is_file($config['upload_path'] . CROP_SMALL . $oldFileName)) {
                unlink($config['upload_path'] . CROP_SMALL . $oldFileName);
            }
            if (is_file($config['upload_path'] . CROP_MEDIUM . $oldFileName)) {
                unlink($config['upload_path'] . CROP_MEDIUM . $oldFileName);
            }
            if (is_file($config['upload_path'] . CROP_LARGE . $oldFileName)) {
                unlink($config['upload_path'] . CROP_LARGE . $oldFileName);
            }
            echo json_encode($this->accommodation->updateMedia_model($this->input->post('videoId')));
        }
    }

    public function listsMedia() {
        echo json_encode($this->accommodation->listsMedia_model(array('modId' => $this->input->post('modId'), 'contId'=>$this->input->post('contId'))));
    }

    public function isActiveMedia() {
        echo json_encode($this->accommodation->isActiveMedia_model());
    }

    public function deleteMedia() {
        echo json_encode($this->accommodation->deleteMedia_model());
    }

    public function insertMap() {
        echo json_encode($this->accommodation->insertMap_model());
    }

    public function updateMap() {
        echo json_encode($this->accommodation->updateMap_model());
    }

    public function listsMap() {
        echo json_encode($this->accommodation->listsMap_model(array('modId' => $this->input->post('modId'), 'catId' => $this->input->post('catId'), 'contId' => $this->input->post('contId'))));
    }

    public function isActiveMap() {
        echo json_encode($this->accommodation->isActiveMap_model());
    }

    public function deleteMap() {
        echo json_encode($this->accommodation->deleteMap_model());
    }

    public function event() {
        echo json_encode($this->accommodation->eventInsertOrUpdate_model());
    }

    public function updateVerticalImage() {
        $newFile = '';
        $file = $this->input->post('verticalPic');
        $image = explode(".", $file);
        $oldFile = $this->input->post('verticalOldPic');
        $contId = $this->input->post('id');
        if ($file != '') {
            $upload_path = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
            $result = imageCropGaz(array(
                'source_image' => $file,
                'new_image' => $file,
                'crop_width' => $this->input->post('verticalCropWidth'),
                'crop_height' => $this->input->post('verticalCropHeight'),
                'crop_x' => $this->input->post('verticalCropX'),
                'crop_y' => $this->input->post('verticalCropY'),
                'upload_path' => $upload_path
            ));
            if ($result['status'] === 'success') {
                $newFile = getImageUID() . '.' . end($image);
                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_SMALL . $newFile,
                    'height' => SMALL_HEIGHT,
                    'width' => SMALL_WIDTH,
                    'upload_path' => $upload_path
                ));

                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_MEDIUM . $newFile,
                    'height' => MEDIUM_HEIGHT,
                    'width' => MEDIUM_WIDTH,
                    'upload_path' => $upload_path
                ));

                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_LARGE . $newFile,
                    'height' => LARGE_HEIGHT,
                    'width' => LARGE_WIDTH,
                    'upload_path' => $upload_path
                ));
                if (is_file($upload_path . $result['response']))
                    unlink($upload_path . $result['response']);

                if (is_file($upload_path . CROP_SMALL . $oldFile))
                    unlink($upload_path . CROP_SMALL . $oldFile);

                if (is_file($upload_path . CROP_MEDIUM . $oldFile))
                    unlink($upload_path . CROP_MEDIUM . $oldFile);

                if (is_file($upload_path . CROP_LARGE . $oldFile))
                    unlink($upload_path . CROP_LARGE . $oldFile);
            }
        }
        echo json_encode($this->accommodation->updateVerticalPic_model(array('verticalPic'=>$newFile, 'contId'=>$contId)));
    }

    public function listsAttachFile() {
        echo json_encode($this->accommodation->listsAttachFile_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('contId'))));
    }

    public function formAttachFile() {
        $data['contId'] = $this->input->post('contId');
        $data['modId'] = $this->input->post('modId');
        $data['mediaId'] = $this->input->post('mediaId');
        $data['row'] = $this->accommodation->formMediaData_model(array('mediaId' => $data['mediaId']));
        echo json_encode(
                array(
                    "title" => "Файл хавсаргах",
                    "html" => $this->load->view(MY_ADMIN . '/accommodation/formAttachFile', $data, true),
                    "btn_ok" => "Хадгалах",
                    "btn_no" => "Хаах"));
    }

    public function insertAttachFile() {
        $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
        $config['allowed_types'] = UPLOAD_ALL_FILE_TYPE;
        $config['max_size'] = UPLOAD_FILE_MAX_SIZE;
        $config['remove_spaces'] = true;
        $config['encrypt_name'] = true;
        (Integer) $error = 0;
        $files = $_FILES;
        if (isset($_FILES['attachFileMn'])) {
            $this->load->library('upload', $config);
            foreach ($files['attachFileMn']['name'] as $key => $value) {
                $_FILES['attachFileMn']['name'] = $files['attachFileMn']['name'][$key];
                $_FILES['attachFileMn']['type'] = $files['attachFileMn']['type'][$key];
                $_FILES['attachFileMn']['tmp_name'] = $files['attachFileMn']['tmp_name'][$key];
                $_FILES['attachFileMn']['error'] = $files['attachFileMn']['error'][$key];
                $_FILES['attachFileMn']['size'] = $files['attachFileMn']['size'][$key];
                
                $_FILES['attachFileEn']['name'] = $files['attachFileEn']['name'][$key];
                $_FILES['attachFileEn']['type'] = $files['attachFileEn']['type'][$key];
                $_FILES['attachFileEn']['tmp_name'] = $files['attachFileEn']['tmp_name'][$key];
                $_FILES['attachFileEn']['error'] = $files['attachFileEn']['error'][$key];
                $_FILES['attachFileEn']['size'] = $files['attachFileEn']['size'][$key];
                
                if (isset($_FILES['attachFileMn']['name'][$key])) {
                    
                    if ($this->upload->do_upload('attachFileMn')) {
                        $resultMn = $this->upload->data();
                        
                        if (isset($_FILES['attachFileEn']['name'][$key])) {
                        
                            if ($this->upload->do_upload('attachFileEn')) {
                                $resultEn = $this->upload->data();
                                $error += 0;
                            } else {
                                $error += 1;
                            }
                        }
                        
                        if ($this->accommodation->insertMedias_model(array('fileNameMn' => $resultMn["file_name"], 'fileNameEn' => $resultEn["file_name"]))) {
                            $error += 0;
                        } else {
                            $error += 1;
                        }
                    } else {
                        $error += 1;
                    }
                }
            }

            if (empty($error)) {
                echo json_encode(array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...'));
            } else {
                echo json_encode(array('status' => 'error', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...'));
            }
        }
    }

    public function updateAttachFile() {
        $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
        $config['allowed_types'] = UPLOAD_ALL_FILE_TYPE;
        $config['max_size'] = UPLOAD_FILE_MAX_SIZE;
        $config['remove_spaces'] = true;
        $config['encrypt_name'] = true;
        (Integer) $error = 0;
        if (isset($_FILES['attachFileMn']['name']['0'])) {
            $_FILES['attachFileMn']['name'] = $_FILES['attachFileMn']['name']['0'];
            $_FILES['attachFileMn']['type'] = $_FILES['attachFileMn']['type']['0'];
            $_FILES['attachFileMn']['tmp_name'] = $_FILES['attachFileMn']['tmp_name']['0'];
            $_FILES['attachFileMn']['error'] = $_FILES['attachFileMn']['error']['0'];
            $_FILES['attachFileMn']['size'] = $_FILES['attachFileMn']['size']['0'];
            
            $_FILES['attachFileEn']['name'] = $_FILES['attachFileEn']['name']['0'];
            $_FILES['attachFileEn']['type'] = $_FILES['attachFileEn']['type']['0'];
            $_FILES['attachFileEn']['tmp_name'] = $_FILES['attachFileEn']['tmp_name']['0'];
            $_FILES['attachFileEn']['error'] = $_FILES['attachFileEn']['error']['0'];
            $_FILES['attachFileEn']['size'] = $_FILES['attachFileEn']['size']['0'];
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('attachFileMn')) {
                $resultMn = $this->upload->data();
                
                if (isset($_FILES['attachFileEn']['name'][$key])) {
                        
                    if ($this->upload->do_upload('attachFileEn')) {
                        $resultEn = $this->upload->data();
                        $error += 0;
                    } else {
                        $error += 1;
                    }
                }
                if ($this->accommodation->updateMedia_model(array('fileNameMn' => $resultMn["file_name"], 'fileNameEn' => $resultEn["file_name"]))) {
                    if (is_file($config['upload_path'] . $this->input->post('oldAttachFileMn'))) {
                        unlink($config['upload_path'] . $this->input->post('oldAttachFileMn'));
                    }
                    if (is_file($config['upload_path'] . $this->input->post('oldAttachFileEn'))) {
                        unlink($config['upload_path'] . $this->input->post('oldAttachFileEn'));
                    }
                    echo json_encode(array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...'));
                } else {
                    echo json_encode(array('status' => 'error', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...'));
                }
            } else {
                echo json_encode(array('status' => 'error', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...'));
            }
        } else {
            if ($this->accommodation->updateMedia_model(array('fileNameMn' => $this->input->post('oldAttachFileMn'), 'fileNameEn' => $this->input->post('oldAttachFileEn')))) {
                echo json_encode(array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...'));
            } else {
                echo json_encode(array('status' => 'error', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...'));
            }
        }
    }

    public function uploadImage() {
        echo json_encode(uploadImageSource(array('fieldName' => $this->input->post('fieldName'), 'path' => UPLOADS_CONTENT_PATH)));
    }

    public function removeImage() {
        $image = $_SERVER['DOCUMENT_ROOT'] . $this->input->post('image');
        if (is_file($image)) {
            unlink($image);
            echo json_encode(array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Зургийг устгалаа'));
        } else {
            echo json_encode(array('status' => 'error', 'title' => 'Амжилтгүй', 'message' => 'Зургийг устгах үед алдаа гарлаа'));
        }
    }

    public function isActiveAttachFile() {
        echo json_encode($this->accommodation->isActiveMedia_model());
    }

    public function deleteAttachFile() {
        echo json_encode($this->accommodation->deleteMedia_model());
    }

    public function initVerticalPhoto() {
        echo json_encode($this->accommodation->editFormData_model(array('id' => $this->input->post('contId'))));
    }

    public function formPeopleData() {
        echo json_encode($this->accommodation->formPeopleData_model());
    }

    public function people() {
        echo json_encode($this->accommodation->peopleInsertOrUpdate_model());
    }

    public function uploadPhotos() {
        echo json_encode($this->accommodation->uploadPhotos_model());
    }

    function editor($path, $width) {
        //Loading Library For Ckeditor
        $this->load->library('ckeditor');
        $this->load->library('ckFinder');
        //configure base path of ckeditor folder 
        $this->ckeditor->basePath = base_url() . 'js/ckeditor/';
        $this->ckeditor->config['toolbar'] = 'Full';
        $this->ckeditor->config['language'] = 'en';
        $this->ckeditor->config['width'] = $width;
        //configure ckfinder with ckeditor config 
        var_dump($this->ckeditor->basePath);
        $this->ckfinder->SetupCKEditor($this->ckeditor, $path);
    }

}
