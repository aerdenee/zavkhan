<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SnifsSearch extends CI_Controller {

    public static $path = "snifsSearch/";

    function __construct() {

        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('SnifsSearch_model', 'nifsSearch');
        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array(
                '/assets/system/core/nifsSearch.js',
                '/assets/system/core/nifsAnatomy.js',
                '/assets/system/core/nifsCrime.js',
                '/assets/system/core/nifsDoctorView.js',
                '/assets/system/core/nifsEconomy.js',
                '/assets/system/core/nifsExtra.js',
                '/assets/system/core/nifsFileFolder.js',
                '/assets/system/core/nifsScene.js',
            );

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => 'Улсын хэмжээний нэгдсэн хайлт'));

            $this->body['emptyResult'] = $this->load->view(MY_ADMIN . '/page/empty', $this->body, TRUE);
            //load the view
            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/nifsSearch/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function lists() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            //total rows count
            $totalRec = $this->nifsSearch->listsCount_model(array(
                'protocolNumber' => $this->input->get('protocolNumber'),
                'keyword' => $this->input->get('keyword')));

            //get posts data
            $result = $this->nifsSearch->lists_model(array(
                'protocolNumber' => $this->input->get('protocolNumber'),
                'keyword' => $this->input->get('keyword'),
                'totalRows' => $totalRec,
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
        }
    }

    public function emptyPage() {

        echo json_encode($this->load->view(MY_ADMIN . '/page/empty', $this->body, TRUE));
        
    }

}
