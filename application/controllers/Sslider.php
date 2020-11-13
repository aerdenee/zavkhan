<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sslider extends CI_Controller {

    private static $path = "sslider/";

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('form', 'url', 'file'));
        $this->load->library('session', 'image_lib');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Sslider_model', 'slider');

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
        $this->load->view('slider/index', $body);
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
        $body['contentEdit'] = $this->slider->contentEdit_model($contId);

        $this->load->view('header', $header);
        $this->load->view('slider/form', $body);
        $this->load->view('footer');
    }

    public function insert() {
        $modId = $this->uri->segment(2);

        $result = self::imageUpload(
                        array(
                            'file_name' => 'file',
                            'upload_path' => $_SERVER['DOCUMENT_ROOT'] . UPLOADS_SLIDER_PATH,
                            'max_size' => 100000000000,
                            'max_width' => 5000,
                            'max_height' => 5000
                        )
        );
        $_POST['file'] = $result['response']['file_name'];
        $_POST['target'] = '_parent';
        $_POST['type'] = 1;
        $this->slider->contentInsert_model($modId);
        redirect(self::$path . $modId, 'location', 303);
    }

    public function deleteContent() {
        $contId = $this->input->post('contId');
        echo json_encode($this->slider->deleteContent_model($contId));
    }

    public function contentList() {
        $modId = $this->input->post('modId');
        $catId = $this->input->post('catId');
        echo json_encode($this->slider->contentList_model($modId, $catId));
    }

    public function publish() {
        $modId = $this->uri->segment(2);
        $contId = $this->uri->segment(4);
        $publish = $this->uri->segment(6);

        $this->slider->contentPublish_model($contId, $publish);

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
            'http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css',
            '/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css',
            '/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css'
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
            '/assets/global/plugins/jquery-contextmenu/src/jquery.contextmenu.js',
            '/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
            '/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js',
            '/assets/global/plugins/clockface/js/clockface.js',
            '/assets/global/plugins/bootstrap-daterangepicker/moment.min.js',
            '/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js',
            '/assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js',
            '/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'
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
        $body['contentEdit'] = $this->slider->contentEdit_model($contId);

        $this->load->view('header', $header);
        $this->load->view('slider/form', $body);
        $this->load->view('footer');
    }

    public function update() {
        $modId = $this->uri->segment(2);
        $contId = $this->uri->segment(4);

        $oldFile = $this->input->post('oldFile');
        if ($_FILES['file']['name'] != '') {
            $result = self::imageUpload(
                            array(
                                'file_name' => 'file',
                                'upload_path' => $_SERVER['DOCUMENT_ROOT'] . UPLOADS_SLIDER_PATH,
                                'max_size' => 100000000000,
                                'max_width' => 5000,
                                'max_height' => 5000
                            )
            );
            $_POST['file'] = $result['response']['file_name'];
            $_POST['target'] = '_parent';
            $_POST['type'] = 1;

            if ($oldFile != '') {
                unlink($_SERVER['DOCUMENT_ROOT'] . UPLOADS_SLIDER_PATH . $oldFile);
            }
        }

        $this->slider->contentUpdate_model($contId);
        redirect(self::$path . $modId);
    }

    public function setSessionCatId() {
        $this->session->catId = $this->input->post('catId');
        echo json_encode(array('status' => 'success'));
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

}

?>