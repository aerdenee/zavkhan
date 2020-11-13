<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sproduct extends CI_Controller {

    private static $path = "sproduct/";

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('form', 'url', 'file'));
        $this->load->library(array('session', 'image_lib'));
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Sproduct_model', 'content');
        $this->load->model('Sorganization_model', 'organization');

        self::checkLogin();
    }

    public function index() {
        $header['cssFile'] = array(
            '/assets/global/plugins/select2/select2.css',
            '/assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css',
            '/assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css',
            '/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css'
        );

        $header['jsFile'] = array(
            '/assets/global/plugins/select2/select2.min.js',
            '/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js',
            '/assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js',
            '/assets/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js',
            '/assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js',
            '/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'
        );

        $header['moduleTitle'] = 'Агуулгын жагсаалт';
        $modId = $this->uri->segment(2);
        $body['path'] = self::$path;
        $body['status'] = $this->uri->segment(3);
        $body['modId'] = $modId;
        $body['categoryList'] = $this->category->categoryListDropDown_model($modId);
        $this->load->view('header', $header);
        $this->load->view('product/index', $body);
        $this->load->view('footer');
    }

    public function checkLogin() {
        if (!isset($_SESSION['isLogin'])) {
            redirect('/' . MY_ADMIN);
        }

        if ($_SESSION['isLogin'] === false) {
            redirect('/' . MY_ADMIN);
        }
    }

    public function formAdd() {
        $header['cssFile'] = array(
            '/assets/global/plugins/select2/select2.css',
            '/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css',
            '/assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css',
            '/assets/global/plugins/bootstrap-datepicker/css/datepicker.css',
            '/assets/global/plugins/bootstrap-wysihtml5/wysiwyg-color.css',
            '/assets/global/plugins/jquery-tags-input/jquery.tagsinput.css',
            '/assets/global/plugins/jcrop/css/jquery.Jcrop.min.css',
            '/assets/admin/pages/css/image-crop.css'
        );

        $header['jsFile'] = array(
            '/assets/global/plugins/jquery-validation/js/jquery.validate.min.js',
            '/assets/global/plugins/jquery-validation/js/additional-methods.min.js',
            '/assets/global/plugins/select2/select2.min.js',
            '/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
            '/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js',
            '/assets/global/plugins/ckeditor/ckeditor.js',
            '/assets/global/plugins/jquery-tags-input/jquery.tagsinput.js',
            '/assets/global/plugins/jquery.form.js',
            '/assets/global/plugins/jcrop/js/jquery.color.js',
            '/assets/global/plugins/jcrop/js/jquery.Jcrop.min.js'
        );
        $header['moduleTitle'] = 'Ангилал үүсгэх';
        $modId = $this->uri->segment(2);
        $body['formType'] = $this->uri->segment(3);
        $body['modId'] = $modId;
        $contId = 0;
        $body['modId'] = $modId;
        $body['contId'] = $contId;
        $body['moduleTitle'] = 'Ангилал нэмэх, засах';

        $body['categoryListData'] = $this->category->categoryListDropDown_model($modId);
        $body['contentEdit'] = $this->content->contentEdit_model($contId);
        $body['organizationList'] = $this->organization->organizationList_model();

        $this->load->view('header', $header);
        $this->load->view('product/form', $body);
        $this->load->view('footer');
    }

    public function insert() {
        $modId = $this->uri->segment(2);

        $file = $this->input->post('pic');
        if ($file != '') {
            $upload_path = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
            $result = self::imageCrop(array(
                        'source_image' => $file,
                        'new_image' => $file,
                        'crop_width' => $this->input->post('crop_width'),
                        'crop_height' => $this->input->post('crop_height'),
                        'crop_x' => $this->input->post('crop_x'),
                        'crop_y' => $this->input->post('crop_y'),
                        'upload_path' => $upload_path
            ));

            self::imageRreSize(array(
                'source_image' => $result['response'],
                'new_image' => CROP_SMALL . $result['response'],
                'height' => SMALL_HEIGHT,
                'width' => SMALL_WIDTH,
                'upload_path' => $upload_path
            ));

            self::imageRreSize(array(
                'source_image' => $result['response'],
                'new_image' => CROP_MEDIUM . $result['response'],
                'height' => MEDIUM_HEIGHT,
                'width' => MEDIUM_WIDTH,
                'upload_path' => $upload_path
            ));

            self::imageRreSize(array(
                'source_image' => $result['response'],
                'new_image' => CROP_LARGE . $result['response'],
                'height' => LARGE_HEIGHT,
                'width' => LARGE_WIDTH,
                'upload_path' => $upload_path
            ));
            unlink($upload_path . $result['response']);
        }
        $this->content->contentInsert_model($modId);
        redirect(self::$path . $modId);
    }

    public function deleteContent() {
        $contId = $this->input->post('contId');
        echo json_encode($this->content->deleteContent_model($contId));
    }

    public function contentList() {
        $modId = $this->input->post('modId');
        $catId = $this->input->post('catId');
        echo json_encode($this->content->contentList_model($modId, $catId));
    }

    public function publish() {
        $modId = $this->uri->segment(2);
        $contId = $this->uri->segment(4);
        $publish = $this->uri->segment(6);

        $this->content->contentPublish_model($contId, $publish);

        redirect(self::$path . $modId);
    }

    public function formEdit() {
        $header['cssFile'] = array(
            '/assets/global/plugins/select2/select2.css',
            '/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css',
            '/assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css',
            '/assets/global/plugins/bootstrap-datepicker/css/datepicker.css',
            '/assets/global/plugins/bootstrap-wysihtml5/wysiwyg-color.css',
            '/assets/global/plugins/jquery-tags-input/jquery.tagsinput.css',
            '/assets/global/plugins/jcrop/css/jquery.Jcrop.min.css',
            '/assets/admin/pages/css/image-crop.css',
            '/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css',
            '/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
            '/assets/global/plugins/jquery-contextmenu/src/jquery.contextmenu.css',
            'http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css'
        );

        $header['jsFile'] = array(
            '/assets/global/plugins/jquery-validation/js/jquery.validate.min.js',
            '/assets/global/plugins/jquery-validation/js/additional-methods.min.js',
            '/assets/global/plugins/select2/select2.min.js',
            '/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
            '/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js',
            '/assets/global/plugins/ckeditor/ckeditor.js',
            '/assets/global/plugins/jquery-tags-input/jquery.tagsinput.js',
            '/assets/global/plugins/jquery.form.js',
            '/assets/global/plugins/jcrop/js/jquery.color.js',
            '/assets/global/plugins/jcrop/js/jquery.Jcrop.min.js',
            '/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
            '/assets/global/plugins/jquery-contextmenu/src/jquery.contextmenu.js'
        );
        $header['moduleTitle'] = 'Ангилал үүсгэх';
        $modId = $this->uri->segment(2);
        $body['formType'] = ($this->uri->segment(3) == 'edit' ? 'update' : '');
        $body['modId'] = $modId;
        $contId = $this->uri->segment(4);
        $body['modId'] = $modId;
        $body['contId'] = $contId;
        $body['moduleTitle'] = 'Ангилал нэмэх, засах';

        $body['categoryListData'] = $this->category->categoryListDropDown_model($modId);
        $body['contentEdit'] = $this->content->contentEdit_model($contId);
        $body['organizationList'] = $this->organization->organizationList_model();
        
        $this->load->view('header', $header);
        $this->load->view('product/form', $body);
        $this->load->view('footer');
    }

    public function update() {
        $modId = $this->uri->segment(2);
        $contId = $this->uri->segment(4);
        $file = $this->input->post('pic');
        $oldFile = $this->input->post('oldPic');
        if ($file != '') {
            $upload_path = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
            $result = self::imageCrop(array(
                        'source_image' => $file,
                        'new_image' => $file,
                        'crop_width' => $this->input->post('crop_width'),
                        'crop_height' => $this->input->post('crop_height'),
                        'crop_x' => $this->input->post('crop_x'),
                        'crop_y' => $this->input->post('crop_y'),
                        'upload_path' => $upload_path
            ));

            self::imageRreSize(array(
                'source_image' => $result['response'],
                'new_image' => CROP_SMALL . $result['response'],
                'height' => SMALL_HEIGHT,
                'width' => SMALL_WIDTH,
                'upload_path' => $upload_path
            ));

            self::imageRreSize(array(
                'source_image' => $result['response'],
                'new_image' => CROP_MEDIUM . $result['response'],
                'height' => MEDIUM_HEIGHT,
                'width' => MEDIUM_WIDTH,
                'upload_path' => $upload_path
            ));

            self::imageRreSize(array(
                'source_image' => $result['response'],
                'new_image' => CROP_LARGE . $result['response'],
                'height' => LARGE_HEIGHT,
                'width' => LARGE_WIDTH,
                'upload_path' => $upload_path
            ));
            unlink($upload_path . $result['response']);
            
            if ($oldFile !='') {
                unlink($upload_path . CROP_SMALL . $oldFile);
                unlink($upload_path . CROP_MEDIUM . $oldFile);
                unlink($upload_path . CROP_LARGE . $oldFile);
            }
        }
        $this->content->contentUpdate_model($contId);
        redirect(self::$path . $modId);
    }

    public function picUpload() {
        $result = self::imageUpload(
                        array(
                            'file_name' => 'picUpload',
                            'upload_path' => $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH,
                            'max_size' => 100000000000,
                            'max_width' => 5000,
                            'max_height' => 5000
                        )
        );
        $fileName = $result['response']['file_name'];
        if ($result['status'] == 'success') {
            if ($result['response']['image_width'] > 1024) {
                $result = self::imageRreSize(
                                array(
                                    'source_image' => $result['response']['file_name'],
                                    'new_image' => $result['response']['file_name'],
                                    'height' => 768,
                                    'width' => 1024,
                                    'upload_path' => $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH
                                )
                );
            }
            echo json_encode(array('status' => 'success', 'response' => $fileName));
        } else {
            echo json_encode(array('status' => 'error', 'response' => 'Алдаа гарлаа'));
        }
    }

    public function setSessionCatId() {
        $this->session->catId = $this->input->post('catId');
        echo json_encode(array('status'=>'success'));
    }

    public function imageRreSize($data) {
        $config['image_library'] = 'gd2';
        $config['source_image'] = $data['upload_path'] . $data['source_image'];
        $config['create_thumb'] = false;
        $config['maintain_ratio'] = TRUE;
        $config['height'] = $data['height'];
        $config['width'] = $data['width'];
        $config['new_image'] = $data['upload_path'] . $data['new_image'];
        $this->image_lib->initialize($config);
        if ($this->image_lib->resize()) {
            return array('status' => 'success', 'response' => 'Амжилттай');
        } else {
            return array('status' => 'error', 'response' => 'Амжилтгүй');
        }
    }

    public function imageUpload($data) {
        $config['file_name'] = time() . '_' . $_FILES[$data['file_name']]['name'];
        $config['upload_path'] = $data['upload_path'];
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = $data['max_size'];
        $config['max_width'] = $data['max_width'];
        $config['max_height'] = $data['max_height'];
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        unset($config);
        if (!$this->upload->do_upload($data['file_name'])) {
            return array('status' => 'error', 'response' => $this->upload->display_errors());
        } else {
            return array('status' => 'success', 'response' => $this->upload->data());
        }
    }

    public function imageCrop($data) {
        $image_config['image_library'] = 'gd2';
        $image_config['source_image'] = $data['upload_path'] . $data['source_image'];
        $image_config['new_image'] = $data['upload_path'] . $data['new_image'];
        $image_config['quality'] = "100%";
        $image_config['maintain_ratio'] = FALSE;
        $image_config['width'] = $data['crop_width'];
        $image_config['height'] = $data['crop_height'];
        $image_config['x_axis'] = $data['crop_x'];
        $image_config['y_axis'] = $data['crop_y'];

        $this->image_lib->clear();
        $this->image_lib->initialize($image_config);

        if ($this->image_lib->crop()) {
            return array('status' => 'success', 'response' => $data['new_image']);
        } else {
            return array('status' => 'success', 'response' => 'Алдаа гарлаа');
        }
    }

}

?>