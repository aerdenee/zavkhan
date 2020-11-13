<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Schart extends CI_Controller {

    public static $path = "schart/";

    function __construct() {

        parent::__construct();
        $this->load->model('Schart_model', 'chart');
        
    }
    
    public function show() {
        echo json_encode($this->chart->show_model(array('chartId' => $this->input->post('chartId'), 'catId' => $this->input->post('catId'), 'initId' => $this->input->post('initId'), 'data' => $this->input->post('data'))));
    }
    
    public function eChartType() {
        echo json_encode($this->chart->eChartData_model(array('chartId' => $this->input->post('chartId'), 'data' => $this->input->post('data'))));
    }
    
    
    public function getData() {
        echo json_encode($this->chart->getData_model(array('chartId' => $this->input->post('chartId'), 'catId' => $this->input->post('catId'), 'initId' => $this->input->post('initId'))));
    }
    
}
