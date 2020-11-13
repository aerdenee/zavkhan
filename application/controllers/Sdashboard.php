<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sdashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Slanguage_model', 'language');
        $this->load->model('Spage_model', 'page');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Sdashboard_model', 'dashboard');
        $this->load->model('Schart_model', 'chart');
        $this->load->model('ShrContact_model', 'hrContact');
        
                
        $this->header = $this->body = $this->footer = array();

    }

    public function index() {

        $this->footer['jsFile'] = array('/assets/system/core/_dashboard.js', '/assets/system/core/_nifsChart.js');
        
        if ($this->session->isLogin) {

            $this->header['pageMeta'] = $this->page->meta_model();
            
            $this->body['getYear'] = $this->dashboard->getYear_model();

            $this->body['getCrimeCount'] = $this->dashboard->getCrimeCount_model();
            $this->body['getExtraCount'] = $this->dashboard->getExtraCount_model();
            $this->body['getEconomyCount'] = $this->dashboard->getEconomyCount_model();
            $this->body['getSendDocumentCount'] = $this->dashboard->getSendDocumentCount_model();
            $this->body['getFileFolderCount'] = $this->dashboard->getFileFolderCount_model();
            $this->body['getAnatomyCount'] = $this->dashboard->getAnatomyCount_model();
            $this->body['getDoctorViewCount'] = $this->dashboard->getDoctorViewCount_model();
            $this->body['learningLists'] = $this->dashboard->learningLists_model();
            $this->body['dashboardContactData'] = $this->hrContact->dashboardContactData_model();
            
            
            $this->body['generalChart'] = $this->chart->show_model(array('chartId' => 5, 'catId' => 0, 'initId' => 'generaldataall', 'data' => array(
                array('value' => $this->body['getCrimeCount'], 'name' => 'Кримналистик'),
                array('value' => ($this->body['getExtraCount'] + $this->body['getSendDocumentCount']), 'name' => 'Тусгай шинжилгээ'),
                array('value' => $this->body['getEconomyCount'], 'name' => 'Эдийн засгийн шинжилгээ'),
                array('value' => ($this->body['getFileFolderCount'] + $this->body['getAnatomyCount'] + $this->body['getDoctorViewCount']), 'name' => 'Шүүх эмнэлэг')
            )));
            
            $this->body['generalChartCrime'] = $this->chart->show_model(array('chartId' => 3, 'catId' => 0, 'initId' => 'generalDataCrime', 'data' => $this->dashboard->generalGraphicCrimeData_model()));
            $this->body['generalChartExtra'] = $this->chart->show_model(array('chartId' => 3, 'catId' => 0, 'initId' => 'generalDataExtra', 'data' => $this->dashboard->generalGraphicExtraData_model()));
            $this->body['generalChartDoctorView'] = $this->chart->show_model(array('chartId' => 3, 'catId' => 0, 'initId' => 'generalDataDoctorView', 'data' => $this->dashboard->generalGraphicDoctorViewData_model()));
            
            $this->body['nifsGeneralChartCity'] = $this->dashboard->chartGeneralData_model(array('chartId' => 'nifs_general_chart_city', 'parentId' => 8));
            $this->body['nifsGeneralChartProvince'] = $this->dashboard->chartGeneralData_model(array('chartId' => 'nifs_general_chart_province', 'parentId' => 18));
            $this->body['chartCenterGeneralData'] = $this->dashboard->chartCenterGeneralData_model(array('chartId' => 'nifs_general_chart_center', 'selectedId' => 99));
            //$this->body['getCrimeCount'] = $this->dashboard->getCrimeCount_model();
            
            $this->load->view(MY_ADMIN . '/header', $this->header);
            if (IS_DEFAULT_SYSTEM_USER) {
                $this->load->view(MY_ADMIN . '/dashboard/index', $this->body);
            } else {
                $this->load->view(MY_ADMIN . '/dashboard/nifs', $this->body);
            }
            
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
            
        } else {

            redirect(base_url('systemowner'));
            
        }
    }
    
    public function gerneralGraphicData() {
        return $this->dashboard->gerneralGraphicData_model();
    }
}
