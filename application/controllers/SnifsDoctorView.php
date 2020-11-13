<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SnifsDoctorView extends CI_Controller {

    public static $path = "snifsdoctorview/";

    function __construct() {

        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Sauthentication_model', 'authentication');
        $this->load->model('SnifsDoctorView_model', 'nifsDoctorView');
        $this->load->model('SnifsWork_model', 'nifsWork');
        $this->load->model('SnifsWhere_model', 'nifsWhere');
        $this->load->model('SnifsCrimeShortValue_model', 'nifsCrimeShortValue');
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
        $this->load->model('SnifsInjury_model', 'nifsInjury');
        $this->load->model('SnifsKeywords_model', 'nifsKeywords');
        $this->load->model('SnifsQuestion_model', 'nifsQuestion');

        $this->perPage = 2;
        $this->modId = 51;
        $this->hrPeoplePositionId = '5,6,7,13,28,16,27';
        $this->hrPeopleDepartmentId = '4,16,17,18';
        $this->nifsCrimeTypeId = 353;
        $this->nifsQuestionCatId = 371;
        $this->nifsSolutionCatId = 364;
        $this->nifsCloseTypeCatId = 370;
        $this->nifsWhereCatId = 378;
        $this->nifsMotiveCatId = 391;
        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->hrPeopleExpertPositionId = '5,6,7,10,3,27,13';
        $this->hrPeopleExpertDepartmentId = '5';
        $this->hrPeopleExpertOnlyDepartment = ($this->session->adminDepartmentId == 7 ? 4 : $this->session->adminDepartmentId);
        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/nifsDoctorView.js', '/assets/system/core/nifsIsMixx.js', '/assets/system/core/nifsSendDocument.js', '/assets/system/core/nifsQuestion.js', '/assets/system/core/_hrPeople.js');

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
                $this->load->view(MY_ADMIN . '/nifsDoctorView/index', $this->body);
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
            $totalRec = $this->nifsDoctorView->listsCount_model(array(
                'auth' => $this->auth,
                'catId' => $this->input->get('catId'),
                'createNumber' => $this->input->get('createNumber'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'shortValueId' => $this->input->get('shortValueId'),
                'workId' => $this->input->get('workId'),
                'motiveId' => $this->input->get('motiveId'),
                'partnerId' => $this->input->get('partnerId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'protocolInDate' => $this->input->get('protocolInDate'),
                'protocolOutDate' => $this->input->get('protocolOutDate'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'closeDescription' => $this->input->get('closeDescription'),
                'crimeInDate' => $this->input->get('crimeInDate'),
                'crimeOutDate' => $this->input->get('crimeOutDate'),
                'age1' => $this->input->get('age1'),
                'age2' => $this->input->get('age2'),
                'isAgeInfinitive' => $this->input->get('isAgeInfinitive'),
                'crimeShortValueId' => $this->input->get('crimeShortValueId'),
                'expertId' => $this->input->get('expertId'),
                'whereId' => $this->input->get('whereId'),
                'catId' => $this->input->get('catId'),
                'payment' => $this->input->get('payment'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'departmentId' => $this->input->get('departmentId'),
                'statusId' => $this->input->get('statusId'),
                'isSperm' => $this->input->get('isSperm'),
                'isCrimeShip' => $this->input->get('isCrimeShip'),
                'sex' => $this->input->get('sex'),
                'closeDescription' => $this->input->get('closeDescription')));

            //get posts data
            $result = $this->nifsDoctorView->lists_model(array(
                'auth' => $this->auth,
                'catId' => $this->input->get('catId'),
                'createNumber' => $this->input->get('createNumber'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'shortValueId' => $this->input->get('shortValueId'),
                'workId' => $this->input->get('workId'),
                'motiveId' => $this->input->get('motiveId'),
                'partnerId' => $this->input->get('partnerId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'protocolInDate' => $this->input->get('protocolInDate'),
                'protocolOutDate' => $this->input->get('protocolOutDate'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'closeDescription' => $this->input->get('closeDescription'),
                'crimeInDate' => $this->input->get('crimeInDate'),
                'crimeOutDate' => $this->input->get('crimeOutDate'),
                'age1' => $this->input->get('age1'),
                'age2' => $this->input->get('age2'),
                'isAgeInfinitive' => $this->input->get('isAgeInfinitive'),
                'crimeShortValueId' => $this->input->get('crimeShortValueId'),
                'expertId' => $this->input->get('expertId'),
                'whereId' => $this->input->get('whereId'),
                'catId' => $this->input->get('catId'),
                'payment' => $this->input->get('payment'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'departmentId' => $this->input->get('departmentId'),
                'statusId' => $this->input->get('statusId'),
                'isSperm' => $this->input->get('isSperm'),
                'isCrimeShip' => $this->input->get('isCrimeShip'),
                'sex' => $this->input->get('sex'),
                'closeDescription' => $this->input->get('closeDescription'),
                'totalRows' => $totalRec,
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsDoctorView->addFormData_model();
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - нэмэх';

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
                'modId' => $this->input->post('modId'),
                'selectedId' => $this->body['row']->cat_id,
                'parentId' => 0,
                'space' => '',
                'counter' => 1,
                'required' => true));

            $this->body['controlNifsMotiveDropdown'] = $this->nifsMotive->controlNifsMotiveDropdown_model(array(
                'selectedId' => $this->body['row']->motive_id,
                'catId' => $this->nifsMotiveCatId,
                'tabindex' => 2));

            $this->body['controlWorkDropdown'] = $this->nifsWork->controlWorkDropdown_model(array(
                'selectedId' => $this->body['row']->work_id,
                'tabindex' => 11));

            $this->body['controlNifsWhereDropdown'] = $this->nifsWhere->controlNifsWhereDropdown_model(array(
                'selectedId' => $this->body['row']->where_id,
                'catId' => $this->nifsWhereCatId));

            $this->body['controlCrimeShortValueDropdown'] = $this->nifsCrimeShortValue->controlCrimeShortValueDropdown_model(array(
                'selectedId' => $this->body['row']->short_value_id,
                'tabindex' => 16));

            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
                'allInId' => array($this->session->adminPartnerId),
                'selectedId' => $this->body['row']->partner_id,
                'tabindex' => 12));

            $this->body['controlExpertDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
                'isCurrenty' => 1,
                'name' => 'expertId',
                'positionId' => $this->hrPeoplePositionId,
                'selectedId' => $this->body['row']->expert_id,
                'departmentId' => $this->hrPeopleExpertOnlyDepartment,
                'tabindex' => 17));

            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'departmentId' => $this->nifsDepartmentId,
                'html' => $this->load->view(MY_ADMIN . '/nifsDoctorView/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsDoctorView->editFormData_model(array('id' => $this->input->post('id')));

            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - засах';

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
                'modId' => $this->input->post('modId'),
                'selectedId' => $this->body['row']->cat_id,
                'parentId' => 0,
                'space' => '',
                'counter' => 1,
                'required' => true));

            $this->body['controlNifsMotiveDropdown'] = $this->nifsMotive->controlNifsMotiveDropdown_model(array(
                'selectedId' => $this->body['row']->motive_id,
                'catId' => $this->nifsMotiveCatId));

            $this->body['controlWorkDropdown'] = $this->nifsWork->controlWorkDropdown_model(array(
                'selectedId' => $this->body['row']->work_id));

            $this->body['controlNifsWhereDropdown'] = $this->nifsWhere->controlNifsWhereDropdown_model(array(
                'selectedId' => $this->body['row']->where_id,
                'catId' => $this->nifsWhereCatId));

            $this->body['controlCrimeShortValueDropdown'] = $this->nifsCrimeShortValue->controlCrimeShortValueDropdown_model(array(
                'selectedId' => $this->body['row']->short_value_id));

            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
                'allInId' => array($this->session->adminPartnerId),
                'selectedId' => $this->body['row']->partner_id));

            $this->body['controlExpertDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
                'isCurrenty' => 1,
                'name' => 'expertId',
                'positionId' => $this->hrPeoplePositionId,
                'selectedId' => $this->body['row']->expert_id,
                'departmentId' => $this->hrPeopleExpertOnlyDepartment));

            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'btn_save_close' => 'Хадгалах, хаалт',
                'departmentId' => $this->nifsDepartmentId,
                'html' => $this->load->view(MY_ADMIN . '/nifsDoctorView/formEdit', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        $this->nifsKeywords->agentNameInsert_model(array(
            'modId' => $this->input->post('modId'),
            'departmentId' => $this->nifsDepartmentId,
            'keyword' => $this->input->post('expertName')));

        echo json_encode($this->nifsDoctorView->insert_model(array()));
    }

    public function update() {

        $this->nifsKeywords->agentNameInsert_model(array(
            'modId' => $this->input->post('modId'),
            'departmentId' => $this->nifsDepartmentId,
            'keyword' => $this->input->post('expertName')));

        echo json_encode($this->nifsDoctorView->update_model());
    }

    public function delete() {
        echo json_encode($this->nifsDoctorView->delete_model());
    }

    public function closeFrom() {

        $this->body['row'] = $this->nifsDoctorView->editFormData_model(array('id' => $this->input->post('id')));
        $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
        $this->body['row']->module_title = $this->module->title . ' хаах';
        $this->body['controlNifsSolutionDropdown'] = $this->nifsSolution->controlNifsSolutionDropdown_model(array('selectedId' => $this->body['row']->solution_id, 'catId' => $this->nifsSolutionCatId));
        $this->body['controlNifsCloseTypeDropdown'] = $this->nifsCloseType->controlNifsCloseTypeDropdown_model(array('selectedId' => $this->body['row']->close_type_id, 'catId' => $this->nifsCloseTypeCatId));

        $this->body['controlNifsStatusDropdown'] = $this->nifsStatus->controlNifsStatusDropdown_model(array('selectedId' => 0));
        $this->body['controlNifsInjuryDropdown'] = $this->nifsInjury->controlNifsInjuryDropdown_model(array('selectedId' => $this->body['row']->injury_id));

        echo json_encode(array(
            'title' => $this->body['row']->module_title,
            'btn_yes' => 'Хадгалах',
            'btn_no' => 'Хаах',
            'html' => $this->load->view(MY_ADMIN . '/nifsDoctorView/closeForm', $this->body, TRUE)
        ));
    }

    public function close() {
        echo json_encode($this->nifsDoctorView->close_model());
    }

    function searchForm() {

        $this->body['row'] = $this->nifsDoctorView->addFormData_model();
        $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
        $this->body['row']->module_title = $this->module->title . ' - хайлт';

        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
            'modId' => $this->body['row']->mod_id,
            'selectedId' => 0,
            'parentId' => 0,
            'space' => '',
            'counter' => 1,
            'required' => true));

        $this->body['controlNifsMotiveDropdown'] = $this->nifsMotive->controlNifsMotiveDropdown_model(array(
            'selectedId' => 0,
            'catId' => $this->nifsMotiveCatId));

        $this->body['controlWorkDropdown'] = $this->nifsWork->controlWorkDropdown_model(array(
            'selectedId' => $this->body['row']->work_id));
        

        $this->body['controlNifsWhereDropdown'] = $this->nifsWhere->controlNifsWhereDropdown_model(array(
            'selectedId' => 0,
            'catId' => $this->nifsWhereCatId));

        $this->body['controlCrimeShortValueDropdown'] = $this->nifsCrimeShortValue->controlCrimeShortValueDropdown_model(array(
            'selectedId' => 0));

        $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
            'allInId' => array($this->session->adminPartnerId),
            'selectedId' => 0));

        $this->body['controlExpertDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
            'name' => 'expertId',
            'positionId' => $this->hrPeoplePositionId,
            'selectedId' => 0,
            'departmentId' => $this->hrPeopleExpertOnlyDepartment));


        $this->body['controlNifsStatusDropdown'] = $this->nifsStatus->controlNifsStatusDropdown_model(array('selectedId' => 0));

        $this->body['controlNifsCloseTypeDropdown'] = $this->nifsCloseType->controlNifsCloseTypeDropdown_model(array('selectedId' => $this->body['row']->close_type_id, 'catId' => $this->nifsCloseTypeCatId));

        $this->body['controlHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array('selectedId' => 0));
        
        echo json_encode(array(
            'title' => 'Дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/nifsDoctorView/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    public function controlNifsIsSpermDropdown() {
        echo json_encode($this->nifsDoctorView->controlNifsIsSpermDropdown_model(array('selectedId' => 0)));
    }

    public function getReportWorkInformationData() {
        echo json_encode($this->nifsDoctorView->getReportWorkInformationData_model(array(
            'reportIsClose' => $this->input->get('reportIsClose'),
            'departmentId' => $this->input->get('departmentId'), 
            'inDate' => $this->input->get('inDate'), 
            'outDate' => $this->input->get('outDate'), 
            'reportModId' => $this->input->get('reportModId'), 
            'reportMenuId' => $this->input->get('reportMenuId'))));
    }

    public function getReportWeightData() {
        echo json_encode($this->nifsDoctorView->getReportWeightData_model(array(
            'reportIsClose' => $this->input->get('reportIsClose'),
            'departmentId' => $this->input->get('departmentId'), 
            'inDate' => $this->input->get('inDate'), 
            'outDate' => $this->input->get('outDate'), 
            'reportModId' => $this->input->get('reportModId'), 
            'reportMenuId' => $this->input->get('reportMenuId'))));
    }

    public function getReportPartnerData() {
        echo json_encode($this->nifsDoctorView->getReportPartnerData_model(array(
            'reportIsClose' => $this->input->get('reportIsClose'),
            'departmentId' => $this->input->get('departmentId'), 
            'inDate' => $this->input->get('inDate'), 
            'outDate' => $this->input->get('outDate'), 
            'reportModId' => $this->input->get('reportModId'), 
            'reportMenuId' => $this->input->get('reportMenuId'))));
    }

    public function export() {

        $auth = authentication(array(
            'permission' => $this->session->authentication,
            'role' => 'export',
            'moduleMenuId' => $this->input->get('moduleMenuId'),
            'createdUserId' => 0,
            'currentUserId' => $this->session->userdata['adminUserId']));

        $module = $this->module->getData_model(array('id' => $auth->modId));

        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator($this->session->adminFullName);
        $objPHPExcel->getProperties()->setLastModifiedBy();
        $objPHPExcel->getProperties()->setTitle(DEFAULT_ORGANIZATION);
        $objPHPExcel->getProperties()->setSubject(DEFAULT_SYSTEM_NAME);
        $objPHPExcel->getProperties()->setDescription($module->title . ' шинжилгээний бүртгэл');
        $objPHPExcel->getActiveSheet()->setTitle($module->title);

        $objPHPExcel->setActiveSheetIndex(0);

        $this->styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

        $objPHPExcel->getActiveSheet()->mergeCells('A1:F1')->setCellValue('A1', $module->title . ' (' . base_url() . ')');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:F2')->setCellValue('A2', date('Y оны m сарын d'));
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);

        $data = $this->nifsDoctorView->export_model(array(
            'auth' => $auth,
            'catId' => $this->input->get('catId'),
            'createNumber' => $this->input->get('createNumber'),
            'keywordTypeId' => $this->input->get('keywordTypeId'),
            'keyword' => $this->input->get('keyword'),
            'shortValueId' => $this->input->get('shortValueId'),
            'workId' => $this->input->get('workId'),
            'motiveId' => $this->input->get('motiveId'),
            'partnerId' => $this->input->get('partnerId'),
            'inDate' => $this->input->get('inDate'),
            'outDate' => $this->input->get('outDate'),
            'protocolInDate' => $this->input->get('protocolInDate'),
            'protocolOutDate' => $this->input->get('protocolOutDate'),
            'closeInDate' => $this->input->get('closeInDate'),
            'closeOutDate' => $this->input->get('closeOutDate'),
            'closeDescription' => $this->input->get('closeDescription'),
            'crimeInDate' => $this->input->get('crimeInDate'),
            'crimeOutDate' => $this->input->get('crimeOutDate'),
            'age1' => $this->input->get('age1'),
            'age2' => $this->input->get('age2'),
            'isAgeInfinitive' => $this->input->get('isAgeInfinitive'),
            'crimeShortValueId' => $this->input->get('crimeShortValueId'),
            'expertId' => $this->input->get('expertId'),
            'whereId' => $this->input->get('whereId'),
            'catId' => $this->input->get('catId'),
            'payment' => $this->input->get('payment'),
            'closeTypeId' => $this->input->get('closeTypeId'),
            'departmentId' => $this->input->get('departmentId'),
            'statusId' => $this->input->get('statusId'),
            'isSperm' => $this->input->get('isSperm'),
            'isCrimeShip' => $this->input->get('isCrimeShip'),
            'sex' => $this->input->get('sex'),
            'closeDescription' => $this->input->get('closeDescription')));

        $objPHPExcel->getActiveSheet()->mergeCells('A3:A4')->setCellValue('A3', '№');
        $objPHPExcel->getActiveSheet()->mergeCells('B3:B4')->setCellValue('B3', 'Журнал №');
        $objPHPExcel->getActiveSheet()->mergeCells('C3:D3')->setCellValue('C3', 'Бүртгэсэн огноо');
        $objPHPExcel->getActiveSheet()->setCellValue('C4', 'Эхлэх');
        $objPHPExcel->getActiveSheet()->setCellValue('D4', 'Дуусах');
        $objPHPExcel->getActiveSheet()->mergeCells('E3:E4')->setCellValue('E3', 'Үндэслэл');
        $objPHPExcel->getActiveSheet()->mergeCells('F3:F4')->setCellValue('F3', 'Эцэг/эх/-ийн нэр');
        $objPHPExcel->getActiveSheet()->mergeCells('G3:G4')->setCellValue('G3', 'Өөрийн нэр');
        $objPHPExcel->getActiveSheet()->mergeCells('H3:H4')->setCellValue('H3', 'Регистр');
        $objPHPExcel->getActiveSheet()->mergeCells('I3:I4')->setCellValue('I3', 'Нас');
        $objPHPExcel->getActiveSheet()->mergeCells('J3:J4')->setCellValue('J3', 'Хүйс');
        $objPHPExcel->getActiveSheet()->mergeCells('K3:K4')->setCellValue('K3', 'Утас');
        $objPHPExcel->getActiveSheet()->mergeCells('L3:L4')->setCellValue('L3', 'Ажил');
        $objPHPExcel->getActiveSheet()->mergeCells('M3:M4')->setCellValue('M3', 'Ирүүлсэн байгууллага');
        $objPHPExcel->getActiveSheet()->mergeCells('N3:N4')->setCellValue('N3', 'Албан хаагч');
        $objPHPExcel->getActiveSheet()->mergeCells('O3:O4')->setCellValue('O3', 'Хэрэг болсон огноо');
        $objPHPExcel->getActiveSheet()->mergeCells('P3:P4')->setCellValue('P3', 'Болсон хэргийн товч');
        $objPHPExcel->getActiveSheet()->mergeCells('Q3:Q4')->setCellValue('Q3', 'Шинжээч эмч');
        $objPHPExcel->getActiveSheet()->mergeCells('R3:R4')->setCellValue('R3', 'Хаана');
        $objPHPExcel->getActiveSheet()->mergeCells('S3:S4')->setCellValue('S3', 'Хэргийн төрөл');
        $objPHPExcel->getActiveSheet()->mergeCells('T3:T4')->setCellValue('T3', 'Төлбөр');
        $objPHPExcel->getActiveSheet()->mergeCells('U3:U4')->setCellValue('U3', 'Хэргийн дугаар');
        $objPHPExcel->getActiveSheet()->mergeCells('V3:W3')->setCellValue('V3', 'Тогтоолын огноо');
        $objPHPExcel->getActiveSheet()->setCellValue('V4', 'Эхлэх');
        $objPHPExcel->getActiveSheet()->setCellValue('W4', 'Дуусах');
        $objPHPExcel->getActiveSheet()->mergeCells('X3:X4')->setCellValue('X3', 'Хаагдсан огноо');
        $objPHPExcel->getActiveSheet()->mergeCells('Y3:Y4')->setCellValue('Y3', 'Гэмтлийн зэрэг');


        $objPHPExcel->getActiveSheet()->getStyle('A3:Y4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A3:Y4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('A3:Y4')->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '085f35')
                    )
                )
        );

        $phpColor = new PHPExcel_Style_Color();
        $phpColor->setRGB('ffffff');

        $objPHPExcel->getActiveSheet()->getStyle('A3:Y4')->getFont()->setColor($phpColor);

        $i = 4;
        $j = 0;

        if ($data) {
            foreach ($data as $key => $row) {

                $i++;
                $j++;

                $param = json_decode($row->param);

                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $j);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $row->create_number);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $row->in_date);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $row->out_date);
                
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $param->motive);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $row->lname);
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $row->fname);
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $row->register);
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $row->age);
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $row->sex);
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $row->phone);
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $param->work);
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $param->partner);
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $row->expert_name);
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $row->crime_date);
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, $param->shortValue);
                $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, $param->expert);
                $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, $param->where);
                $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, $param->category);
                $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, $row->payment);
                $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, '№:' . $row->protocol_number, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, $row->protocol_in_date);
                $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, $row->protocol_out_date);
                $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, $row->close_date);
                $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, $param->closeType);

            }
        }
        
        $objPHPExcel->getActiveSheet()->getStyle('A3:Y' . $i)->applyFromArray($this->styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('A1:Y' . $i)->getFont()->setName('Arial');
        $objPHPExcel->getActiveSheet()->getStyle('A1:Y' . $i)->getFont()->setSize(10);

        $objPHPExcel->getActiveSheet()->getStyle('A4:Y' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);

        foreach ($objPHPExcel->getActiveSheet()->getColumnDimension() as $col) {
            $col->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->calculateColumnWidths();

        $this->fileName = $module->title . ' ' . date('YmdHis') . '.xlsx';
        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-disposition: attachment; filename=' . $this->fileName);
        header('Cache-Control: max-age=0');
        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save('php://output');
        exit();
    }

    public function dataUpdate() {
        echo json_encode($this->nifsDoctorView->dataUpdate_model());
    }

    public function checkCreateNumber() {
        echo json_encode(checkCreateNumber(array('table' => 'nifs_doctor_view', 'departmentId' => $this->nifsDepartmentId, 'createNumber' => $this->input->post('createNumber'))));
    }

}
