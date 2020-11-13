<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SnifsReportGeneral extends CI_Controller {

    public static $path = "snifsReportGeneral/";

    function __construct() {

        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('SnifsCrime_model', 'nifsCrime');
        $this->load->model('SnifsResearchType_model', 'nifsResearchType');
        $this->load->model('SnifsMotive_model', 'nifsMotive');
        $this->load->model('SnifsCrimeType_model', 'nifsCrimeType');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('SnifsCloseType_model', 'nifsCloseType');
        $this->load->model('SnifsSolution_model', 'nifsSolution');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('SnifsIsMixx_model', 'nifsIsMixx');
        $this->load->model('SnifsStatus_model', 'nifsStatus');
        $this->load->model('SnifsReportGeneral_model', 'nifsReportGeneral');
        $this->load->model('SnifsCloseYear_model', 'nifsCloseYear');

        $this->perPage = 2;
        $this->hrPeopleLatentPrintPositionId = '10,5,6,7';
        $this->hrPeopleLatentPrintDepartmentId = '8,18,3';

        $this->hrPeopleExpertPositionId = '10,5,6,7,3';
        $this->hrPeopleExpertDepartmentId = ($this->session->adminDepartmentId == 7 ? '8,18,3' : $this->session->adminAllDepartmentId);

        $this->nifsCrimeTypeId = 353;
        $this->nifsQuestionCatId = 371;
        $this->nifsSolutionCatId = 359;
        $this->nifsCloseTypeCatId = 365;
        $this->nifsResearchTypeCatId = 380;
        $this->nifsMotiveCatId = 386;

        $this->modId = 33;

        $this->header = $this->body = $this->footer = array();
        
    }

    public function getReportGeneralData() {
        echo json_encode($this->nifsReportGeneral->getReportGeneralData_model(array(
            'inDate' => $this->input->get('inDate'), 
            'outDate' => $this->input->get('outDate'), 
            'reportModId' => $this->input->get('reportModId'), 
            'reportMenuId' => $this->input->get('reportMenuId'),
            'departmentId' => $this->input->get('departmentId'),
            'reportIsClose' => $this->input->get('reportIsClose'))));
    }
    
    public function getExtrnalReportGeneralData() {
        echo json_encode($this->nifsReportGeneral->getExtrnalReportGeneralData_model(array('inDate' => $this->nifsCloseYear->getCloseYearDate_model(array('year'=>2019)), 'outDate' => date('Y-m-d'), 'year'=>'')));
    }
    
    public function getReportCrimeGeneralData() {
        echo json_encode($this->nifsReportGeneral->getReportCrimeGeneralData_model(array(
            'inDate' => $this->input->get('inDate'), 
            'outDate' => $this->input->get('outDate'), 
            'reportModId' => $this->input->get('reportModId'), 
            'reportMenuId' => $this->input->get('reportMenuId'),
            'departmentId' => $this->input->get('departmentId'),
            'reportIsClose' => $this->input->get('reportIsClose'))));
    }
    
    public function getReportAnatomyGeneralData() {
        echo json_encode($this->nifsReportGeneral->getReportAnatomyGeneralData_model(array(
            'inDate' => $this->input->get('inDate'), 
            'outDate' => $this->input->get('outDate'), 
            'reportModId' => $this->input->get('reportModId'), 
            'reportMenuId' => $this->input->get('reportMenuId'),
            'departmentId' => $this->input->get('departmentId'),
            'reportIsClose' => $this->input->get('reportIsClose'))));
    }
    
    public function getReportEconomyGeneralData() {
        echo json_encode($this->nifsReportGeneral->getReportEconomyGeneralData_model(array(
            'inDate' => $this->input->get('inDate'), 
            'outDate' => $this->input->get('outDate'), 
            'reportModId' => $this->input->get('reportModId'), 
            'reportMenuId' => $this->input->get('reportMenuId'),
            'departmentId' => $this->input->get('departmentId'),
            'reportIsClose' => $this->input->get('reportIsClose'))));
    }
    
    public function getReportDoctorViewGeneralData() {
        echo json_encode($this->nifsReportGeneral->getReportDoctorViewGeneralData_model(array(
            'inDate' => $this->input->get('inDate'), 
            'outDate' => $this->input->get('outDate'), 
            'reportModId' => $this->input->get('reportModId'), 
            'reportMenuId' => $this->input->get('reportMenuId'),
            'departmentId' => $this->input->get('departmentId'),
            'reportIsClose' => $this->input->get('reportIsClose'))));
    }
    
    public function getReportForensicMedicineDateIntervalData() {
        echo json_encode($this->nifsReportGeneral->getReportForensicMedicineDateIntervalData_model(array(
            'inDate' => $this->input->get('inDate'), 
            'outDate' => $this->input->get('outDate'), 
            'reportModId' => $this->input->get('reportModId'), 
            'reportMenuId' => $this->input->get('reportMenuId'),
            'departmentId' => $this->input->get('departmentId'),
            'reportIsClose' => $this->input->get('reportIsClose'))));
    }

    public function getReportGeneralDetail() {
        
        if ($this->input->get('modId') == 33) {
            //Криминалистик
            echo json_encode($this->nifsReportGeneral->getReportGeneralDetailCrimeData_model(array(
                'departmentId' => $this->input->get('departmentId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'reportModId' => $this->input->get('reportModId'),
                'reportMenuId' => $this->input->get('reportMenuId'),
                'reportIsClose' => $this->input->get('reportIsClose')
            )));
        } else if ($this->input->get('modId') == 34) {
            //Хэргийн газрын үзлэг
            echo json_encode($this->nifsReportGeneral->getReportGeneralDetailSceneData_model(array(
                'departmentId' => $this->input->get('departmentId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'reportModId' => $this->input->get('reportModId'),
                'reportMenuId' => $this->input->get('reportMenuId'),
                'reportIsClose' => $this->input->get('reportIsClose')
            )));
        } else if ($this->input->get('modId') == 'forensic') {
            //Шүүх эмнэлэг
            echo json_encode($this->nifsReportGeneral->getReportGeneralDetailForensicData_model(array(
                'departmentId' => $this->input->get('departmentId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'reportModId' => $this->input->get('reportModId'),
                'reportMenuId' => $this->input->get('reportMenuId'),
                'reportIsClose' => $this->input->get('reportIsClose')
            )));
        } else if ($this->input->get('modId') == 55) {
            //Тусгай шинжилгээ
            echo json_encode($this->nifsReportGeneral->getReportGeneralDetailExtraData_model(array(
                'departmentId' => $this->input->get('departmentId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'reportModId' => $this->input->get('reportModId'),
                'reportMenuId' => $this->input->get('reportMenuId'),
                'reportIsClose' => $this->input->get('reportIsClose')
            )));
        } else if ($this->input->get('modId') == 56) {
            //Эдийн засаг
            echo json_encode($this->nifsReportGeneral->getReportGeneralDetailEconomyData_model(array(
                'departmentId' => $this->input->get('departmentId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'reportModId' => $this->input->get('reportModId'),
                'reportMenuId' => $this->input->get('reportMenuId'),
                'reportIsClose' => $this->input->get('reportIsClose')
            )));
        } else if ($this->input->get('modId') == 81) {
            //Илгээх бичиг
            echo json_encode($this->nifsReportGeneral->getReportGeneralDetailSendDocumentData_model(array(
                'departmentId' => $this->input->get('departmentId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'reportModId' => $this->input->get('reportModId'),
                'reportMenuId' => $this->input->get('reportMenuId'),
                'reportIsClose' => $this->input->get('reportIsClose')
            )));
        }
    }
    
    public function updateAllData() {
        echo json_encode($this->nifsReportGeneral->updateAllData_model(array(
            'inDate' => $this->input->post('inDate'),
            'outDate' => $this->input->post('outDate')
        )));
    }

}
