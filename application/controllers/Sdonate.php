<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sdonate extends CI_Controller {

    private static $path = "sdonate/";

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('form', 'url', 'file'));
        $this->load->library('session', 'image_lib');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Sdonate_model', 'donate');

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
        $this->load->view('donate/index', $body);
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

    public function contentList() {
        $modId = $this->input->post('modId');
        $catId = $this->input->post('catId');
        echo json_encode($this->donate->contentList_model($modId, $catId));
    }

    public function setSessionCatId() {
        $this->session->catId = $this->input->post('catId');
        echo json_encode(array('status' => 'success'));
    }

}