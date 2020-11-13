<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SdocClose extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('SmasterDocType_model', 'smasterDocType');
        $this->load->model('SdocClose_model', 'docClose');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('ShrPeoplePosition_model', 'hrPeoplePosition');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('SdocFile_model', 'sdocFile');

        $this->modId = 17;
    }

    public function lists() {

        if ($this->session->isLogin === TRUE) {

            //total rows count
            $totalRec = $this->docClose->listsCount_model(array(
                'type' => $this->input->get('type'),
                'docTypeId' => $this->input->get('docTypeId'),
                'docNumber' => $this->input->get('docNumber'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'departmentId' => $this->input->get('departmentId'),
                'partnerId' => $this->input->get('partnerId'),
                'peopleId' => $this->input->get('peopleId'),
                'keyword' => $this->input->get('keyword')));

            //get posts data
            $result = $this->docClose->lists_model(array(
                'type' => $this->input->get('type'),
                'docTypeId' => $this->input->get('docTypeId'),
                'docNumber' => $this->input->get('docNumber'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'departmentId' => $this->input->get('departmentId'),
                'partnerId' => $this->input->get('partnerId'),
                'peopleId' => $this->input->get('peopleId'),
                'keyword' => $this->input->get('keyword'),
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data']));
        }
    }
    
    public function basket() {

        $body['docDetialId'] = $this->input->post('docDetialId');
        $body['docCloseId'] = $this->input->post('docCloseId');
        $body['type'] = $this->input->post('type');
        
        $body['controlMasterDocTypeListDropdown'] = $this->smasterDocType->controlMasterDocTypeListDropdown_model(array('selectedId' => 0));

        $body['controlHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
            'name' => 'departmentId',
            'onlyMyDepartment' => false,
            'selectedId' => 0,
            'onchange' => '_searchToPartnerPeopleDocOutControl({elem:this})'));

        $body['controlPartnerDropdown'] = false;

        $body['controlHrPeopleListDropdown'] = false;

        echo json_encode(array(
            'title' => ' Албан бичгийн жагсаалт',
            'html' => $this->load->view(MY_ADMIN . '/docClose/basket', $body, TRUE),
            'btn_yes' => 'Хадгалах',
            'btn_no' => 'Хаах',
            'width' => 1000
        ));
    }
    
    public function update() {
        echo json_encode($this->docClose->update_model(array(
                'docDetialId' => $this->input->post('docDetialId'),
                'docCloseId' => $this->input->post('docCloseId'))));
    }
    
    public function getData() {
        echo json_encode($this->docClose->getData_model(array('selectedId' => $this->input->post('selectedId'))));
    }

}
