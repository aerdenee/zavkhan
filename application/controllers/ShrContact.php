<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ShrContact extends CI_Controller {

    public static $path = "shrContact/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('ShrContact_model', 'hrContact');

        $this->modId = 60;
        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->isActiveDepartment = 'is_active_control';
        
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->footer['jsFile'] = array('/assets/system/core/hrContact.js');

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => 'Утасны жагсаалт'));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/hrContact/index');
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
            
        } else {
            redirect(MY_ADMIN);
        }
        
    }

    public function lists() {

        if ($this->session->isLogin === TRUE) {

            echo json_encode($this->hrContact->lists_model());
            
        }
        
    }
    
    public function listsData() {

        if ($this->session->isLogin === TRUE) {

            echo json_encode($this->hrContact->listsData_model(array('keyword' => $this->input->get('keyword'))));
            
        }
        
    }
    
    public function mLists() {

        echo json_encode($this->hrContact->mLists_model(array('keyword' => '')));
        
    }
    
    public function mListsData() {

        echo json_encode($this->hrContact->mListsData_model(array('keyword' => '')));
        
    }

}
