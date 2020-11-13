<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Slog extends CI_Controller {

    public static $path = "slog/";

    function __construct() {
        parent::__construct();
        $this->load->model('Slog_model', 'slog');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Spage_model', 'page');
        $this->load->model('Suser_model', 'user');
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->footer['jsFile'] = array('/assets/system/core/_log.js');

            $this->body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'isModule',
                'moduleMenuId' => $this->uri->segment(3),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['auth']->modId));

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['module']->title));

            //load the view
            $this->load->view(MY_ADMIN . '/header', $this->header);
            if ($this->body['auth']->permission) {
                $this->load->view(MY_ADMIN . '/log/index', $this->body);
            } else {
                $this->load->view(MY_ADMIN . '/page/deny', $this->body);
            }
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function lists() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->auth = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'read',
                'moduleMenuId' => $this->input->get('moduleMenuId'),
                'createdUserId' => 0,
                'currentUserId' => $this->session->userdata['adminUserId']));

            //total rows count
            $totalRec = $this->slog->listsCount_model(array(
                'auth' => $this->auth,
                'logYear' => $this->input->get('logYear'),
                'logMonth' => $this->input->get('logMonth'),
                'beginDay' => $this->input->get('beginDay'),
                'endDay' => $this->input->get('endDay'),
                'crudType' => $this->input->get('crudType'),
                'userId' => $this->input->get('userId'),
                'ipAddress' => $this->input->get('ipAddress')));

            $result = $this->slog->lists_model(array(
                'auth' => $this->auth,
                'logYear' => $this->input->get('logYear'),
                'logMonth' => $this->input->get('logMonth'),
                'beginDay' => $this->input->get('beginDay'),
                'endDay' => $this->input->get('endDay'),
                'crudType' => $this->input->get('crudType'),
                'userId' => $this->input->get('userId'),
                'ipAddress' => $this->input->get('ipAddress'),
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
        }
    }

    public function show() {
        $this->body['row'] = $this->slog->getData_model(array('selectedId' => $this->input->post('selectedId'), 'createdDate' => $this->input->post('createdDate')));
        
        echo json_encode(array(
                'title' => $this->body['row']->created_date,
                'html' => $this->load->view(MY_ADMIN . '/log/show', $this->body, TRUE)
            ));
    }
    
    function searchForm() {

        $this->body['controlLogDateYearDropdown'] = $this->slog->controlLogDateYearDropdown_model(array(
            'name' => 'logYear',
            'selectedId' => date('Y')));

        $this->body['controlLogDateMonthDropdown'] = $this->slog->controlLogDateMonthDropdown_model(array(
            'name' => 'logMonth',
            'selectedId' => date('m')));
        
        $this->body['controlLogBeginDayDropdown'] = $this->slog->controlLogDateDayDropdown_model(array(
            'controlName' => 'beginDay',
            'logYear' => date('Y'),
            'logMonth' => date('m'),
            'selectedId' => 0));
        
        $this->body['controlLogEndDayDropdown'] = $this->slog->controlLogDateDayDropdown_model(array(
            'controlName' => 'endDay',
            'logYear' => date('Y'),
            'logMonth' => date('m'),
            'selectedId' => 0));

        
        $this->body['controlUserDropDown'] = $this->user->controlUserDropDown_model(array(
            'name' => 'userId',
            'selectedId' => 0));

        echo json_encode(array(
            'title' => 'Лог системээс хайлт хийх',
            'html' => $this->load->view(MY_ADMIN . '/log/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Хаах'
        ));
    }

    public function controlLogDateDayDropdown() {
        echo json_encode($this->slog->controlLogDateDayDropdown_model(array(
            'controlName' => $this->input->post('controlName'),
            'logYear' => $this->input->post('logYear'),
            'logMonth' => $this->input->post('logMonth'),
            'selectedId' => 0)));
    }
    
    public function checkLogTable() {

        $this->slog->checkLogTable_model(array('tableName' => 'log_' . date('Ym')));
    }
    
    public function updateTable() {

        $this->slog->updateTable_modle();
    }

}
