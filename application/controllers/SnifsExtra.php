<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SnifsExtra extends CI_Controller {

    public static $path = "snifsExtra/";

    function __construct() {

        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('SnifsExtra_model', 'nifsExtra');
        $this->load->model('SnifsResearchType_model', 'nifsResearchType');
        $this->load->model('SnifsMotive_model', 'nifsMotive');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Sbreadcrumb_model', 'breadcrumb');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('SnifsCloseType_model', 'nifsCloseType');
        $this->load->model('SnifsCrimeType_model', 'nifsCrimeType');
        $this->load->model('SnifsQuestion_model', 'nifsQuestion');
        $this->load->model('SnifsSolution_model', 'nifsSolution');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('SnifsIsMixx_model', 'nifsIsMixx');
        $this->load->model('SnifsStatus_model', 'nifsStatus');
        $this->load->model('SnifsKeywords_model', 'nifsKeywords');


        $this->perPage = 2;
        $this->hrPeopleExpertPositionId = '5,6,7,10,3';
        $this->hrPeopleExpertDepartmentId = '5';

        $this->hrPeopleDoctorExpertPositionId = '5,6,7,10,27,13';
        $this->hrPeopleDoctorExpertDepartmentId = '4,18';   //Шүүх эмнэлгийн эмч нар болон орон нутгийн
        $this->hrPeopleExpertOnlyDepartment = ($this->session->adminDepartmentId == 7 ? 5 : $this->session->adminDepartmentId);

        $this->nifsCrimeTypeId = 354;
        $this->nifsQuestionCatId = 372;
        $this->nifsSolutionCatId = 360;
        $this->nifsCloseTypeCatId = 366;
        $this->nifsResearchTypeCatId = 381;
        $this->nifsMotiveCatId = 387;
        $this->doctorDepartmentId = '4,18'; //шүүх эмнэлэг, орон нутгийн шүүхийн эмнэлэгийн эмч нар

        $this->modId = 55;
        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        if ($this->nifsDepartmentId == 7) {
            $this->nifsDepartmentId = 5;
        }

        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->footer['jsFile'] = array('/assets/system/core/nifsExtra.js', '/assets/system/core/nifsIsMixx.js', '/assets/system/core/_hrPeople.js');

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
                $this->load->view(MY_ADMIN . '/nifsExtra/index', $this->body);
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
            $totalRec = $this->nifsExtra->listsCount_model(array(
                'auth' => $this->auth,
                'selectedId' => $this->input->get('selectedId'),
                'catId' => $this->input->get('catId'),
                'createNumber' => $this->input->get('createNumber'),
                'researchTypeId' => $this->input->get('researchTypeId'),
                'isMixx' => $this->input->get('isMixx'),
                'motiveId' => $this->input->get('motiveId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'isAgeInfinitive' => $this->input->get('isAgeInfinitive'),
                'age1' => $this->input->get('age1'),
                'age2' => $this->input->get('age2'),
                'sex' => $this->input->get('sex'),
                'questionId' => $this->input->get('questionId'),
                'partnerId' => $this->input->get('partnerId'),
                'expertDoctorId' => $this->input->get('expertDoctorId'),
                'expertId' => $this->input->get('expertId'),
                'protocolNumber' => $this->input->get('protocolNumber'),
                'protocolInDate' => $this->input->get('protocolInDate'),
                'protocolOutDate' => $this->input->get('protocolOutDate'),
                'solutionId' => $this->input->get('solutionId'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'typeId' => $this->input->get('typeId'),
                'weight' => $this->input->get('weight'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'statusId' => $this->input->get('statusId'),
                'departmentId' => $this->input->get('departmentId'),
                'closeDescription' => $this->input->get('closeDescription'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'payment' => $this->input->get('payment')));

            $result = $this->nifsExtra->lists_model(array(
                'auth' => $this->auth,
                'selectedId' => $this->input->get('selectedId'),
                'catId' => $this->input->get('catId'),
                'createNumber' => $this->input->get('createNumber'),
                'researchTypeId' => $this->input->get('researchTypeId'),
                'isMixx' => $this->input->get('isMixx'),
                'motiveId' => $this->input->get('motiveId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'isAgeInfinitive' => $this->input->get('isAgeInfinitive'),
                'age1' => $this->input->get('age1'),
                'age2' => $this->input->get('age2'),
                'sex' => $this->input->get('sex'),
                'questionId' => $this->input->get('questionId'),
                'partnerId' => $this->input->get('partnerId'),
                'expertDoctorId' => $this->input->get('expertDoctorId'),
                'expertId' => $this->input->get('expertId'),
                'protocolNumber' => $this->input->get('protocolNumber'),
                'protocolInDate' => $this->input->get('protocolInDate'),
                'protocolOutDate' => $this->input->get('protocolOutDate'),
                'solutionId' => $this->input->get('solutionId'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'typeId' => $this->input->get('typeId'),
                'weight' => $this->input->get('weight'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'statusId' => $this->input->get('statusId'),
                'departmentId' => $this->input->get('departmentId'),
                'closeDescription' => $this->input->get('closeDescription'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'payment' => $this->input->get('payment'),
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsExtra->addFormData_model();
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - Нэмэх';

            $this->body['controlNifsCrimeTypeDropdow'] = $this->nifsCrimeType->controlNifsCrimeTypeDropdown_model(array(
                'selectedId' => $this->body['row']->type_id,
                'catId' => $this->nifsCrimeTypeId,
                'required' => true));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
                'modId' => $this->body['row']->mod_id,
                'selectedId' => $this->body['row']->cat_id,
                'required' => true));

            $this->body['controlExpertDoctorDropDown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
                'name' => 'expertDoctorId',
                'departmentId' => $this->hrPeopleDoctorExpertDepartmentId,
                'positionId' => $this->hrPeopleDoctorExpertPositionId,
                'selectedId' => 0));

            $this->body['controlHrPeopleExpertMultiListDropdown'] = $this->hrPeople->controlHrPeopleMultiListDropdown_model(array(
                'name' => 'expertId[]',
                'positionId' => $this->hrPeopleExpertPositionId,
                'departmentId' => $this->hrPeopleExpertOnlyDepartment,
                'modId' => $this->body['row']->mod_id,
                'contId' => $this->body['row']->id,
                'isMixx' => $this->body['row']->is_mixx,
                'researchTypeId' => $this->body['row']->research_type_id,
                'isCurrenty' => 1,
                'initControlHtml' => 'initExtraControlExpertHtml',
                'isExtraValue' => 'true'));

            $this->body['controlNifsQuestionDropDown'] = $this->nifsQuestion->controlNifsQuestionDropDown_model(array(
                'selectedId' => $this->body['row']->question_id,
                'catId' => $this->nifsQuestionCatId));

            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
                'allInId' => array($this->session->adminPartnerId),
                'selectedId' => $this->body['row']->partner_id,
                'required' => 'true'));

            $this->body['controlNifsResearchTypeDropdown'] = $this->nifsResearchType->controlNifsResearchTypeDropdown_model(array(
                'selectedId' => $this->body['row']->research_type_id,
                'catId' => $this->nifsResearchTypeCatId,
                'tabindex' => 2));

            $this->body['controlNifsMotiveDropdown'] = $this->nifsMotive->controlNifsMotiveDropdown_model(array(
                'selectedId' => $this->body['row']->motive_id,
                'catId' => $this->nifsMotiveCatId));

            $this->body['controlNifsCrimeTypeDropdown'] = $this->nifsCrimeType->controlNifsCrimeTypeDropdown_model(array(
                'name' => 'typeId',
                'selectedId' => 0,
                'catId' => $this->nifsCrimeTypeId));

            $this->body['controlNifsIsMixxCheckBox'] = $this->nifsIsMixx->controlNifsIsMixxCheckBox_model(array(
                'isMixx' => $this->body['row']->is_mixx,
                'initControlHtml' => 'initExtraControlExpertHtml',
                'addButtonName' => 'addNifsExtraExpertButton',
                'tabindex' => 3));

            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'departmentId' => $this->nifsDepartmentId,
                'html' => $this->load->view(MY_ADMIN . '/nifsExtra/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsExtra->editFormData_model(array('id' => $this->input->post('id')));
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - Засварлах';

            $this->body['controlNifsCrimeTypeDropdow'] = $this->nifsCrimeType->controlNifsCrimeTypeDropdown_model(array(
                'selectedId' => $this->body['row']->type_id,
                'catId' => $this->nifsCrimeTypeId,
                'required' => true));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
                'modId' => $this->body['row']->mod_id,
                'selectedId' => $this->body['row']->cat_id,
                'required' => true));

            $this->body['controlExpertDoctorDropDown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
                'name' => 'expertDoctorId',
                'departmentId' => $this->hrPeopleDoctorExpertDepartmentId,
                'positionId' => $this->hrPeopleDoctorExpertPositionId,
                'selectedId' => 0));

            $this->body['controlHrPeopleExpertMultiListDropdown'] = $this->hrPeople->controlHrPeopleMultiListDropdown_model(array(
                'name' => 'expertId[]',
                'positionId' => $this->hrPeopleExpertPositionId,
                'departmentId' => $this->hrPeopleExpertOnlyDepartment,
                'modId' => $this->body['row']->mod_id,
                'contId' => $this->body['row']->id,
                'isMixx' => $this->body['row']->is_mixx,
                'researchTypeId' => $this->body['row']->research_type_id,
                'isCurrenty' => 1,
                'initControlHtml' => 'initExtraControlExpertHtml',
                'isExtraValue' => 'true'));

            $this->body['controlNifsQuestionDropDown'] = $this->nifsQuestion->controlNifsQuestionDropDown_model(array(
                'selectedId' => $this->body['row']->question_id,
                'catId' => $this->nifsQuestionCatId));

            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
                'allInId' => array($this->session->adminPartnerId),
                'selectedId' => $this->body['row']->partner_id,
                'required' => true));

            $this->body['controlNifsResearchTypeDropdown'] = $this->nifsResearchType->controlNifsResearchTypeDropdown_model(array(
                'selectedId' => $this->body['row']->research_type_id,
                'catId' => $this->nifsResearchTypeCatId, 'tabindex' => 2));

            $this->body['controlNifsMotiveDropdown'] = $this->nifsMotive->controlNifsMotiveDropdown_model(array(
                'selectedId' => $this->body['row']->motive_id,
                'catId' => $this->nifsMotiveCatId));

            $this->body['controlNifsCrimeTypeDropdown'] = $this->nifsCrimeType->controlNifsCrimeTypeDropdown_model(array(
                'name' => 'typeId',
                'selectedId' => $this->body['row']->type_id,
                'catId' => $this->nifsCrimeTypeId));

            $this->body['controlNifsIsMixxCheckBox'] = $this->nifsIsMixx->controlNifsIsMixxCheckBox_model(array(
                'isMixx' => $this->body['row']->is_mixx,
                'initControlHtml' => 'initExtraControlExpertHtml',
                'addButtonName' => 'addNifsExtraExpertButton'));

            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'btn_save_close' => 'Хадгалах, хаалт',
                'departmentId' => $this->nifsDepartmentId,
                'html' => $this->load->view(MY_ADMIN . '/nifsExtra/formEdit', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        $this->nifsKeywords->insert_model(array(
            'modId' => $this->input->post('modId'),
            'departmentId' => $this->nifsDepartmentId,
            'keyword' => $this->input->post('value')));

        $this->nifsKeywords->agentNameInsert_model(array(
            'modId' => $this->input->post('modId'),
            'departmentId' => $this->nifsDepartmentId,
            'keyword' => $this->input->post('agentName')));

        echo json_encode($this->nifsExtra->insert_model(array('getUID' => getUID('nifs_extra'))));
    }

    public function update() {

        $this->nifsKeywords->insert_model(array(
            'modId' => $this->input->post('modId'),
            'departmentId' => $this->nifsDepartmentId,
            'keyword' => $this->input->post('agentName')));

        $this->nifsKeywords->agentNameInsert_model(array(
            'modId' => $this->input->post('modId'),
            'departmentId' => $this->nifsDepartmentId,
            'keyword' => $this->input->post('agentName')));

        echo json_encode($this->nifsExtra->update_model());
    }

    public function delete() {
        echo json_encode($this->nifsExtra->delete_model());
    }

    public function closeFrom() {
        $this->body['row'] = $this->nifsExtra->editFormData_model(array('id' => $this->input->post('id')));

        $this->body['controlNifsSolutionDropdown'] = $this->nifsSolution->controlNifsSolutionDropdown_model(array('selectedId' => $this->body['row']->solution_id, 'catId' => $this->nifsSolutionCatId, 'required' => true));
        $this->body['controlNifsCloseTypeDropdown'] = $this->nifsCloseType->controlNifsCloseTypeDropdown_model(array('selectedId' => ($this->body['row']->close_type_id != 0 ? $this->body['row']->close_type_id : 5), 'catId' => $this->nifsCloseTypeCatId));

        echo json_encode(array(
            'title' => 'Шинжилгээ хаах',
            'btn_yes' => 'Хадгалах',
            'btn_no' => 'Хаах',
            'html' => $this->load->view(MY_ADMIN . '/nifsExtra/formClose', $this->body, TRUE)
        ));
    }

    public function close() {
        echo json_encode($this->nifsExtra->close_model());
    }

    function searchForm() {

        $this->body['row'] = $this->nifsExtra->addFormData_model();
        $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
        $this->body['row']->module_title = $this->module->title . ' - хайлт';

        $this->body['controlNifsCrimeTypeDropdow'] = $this->nifsCrimeType->controlNifsCrimeTypeDropdown_model(array(
            'selectedId' => 0,
            'catId' => $this->nifsCrimeTypeId,
            'required' => true));

        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
            'modId' => $this->body['row']->mod_id,
            'selectedId' => 0,
            'required' => true));

        $this->body['controlExpertDoctorDropDown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
            'name' => 'expertDoctorId',
            'departmentId' => $this->hrPeopleDoctorExpertDepartmentId,
            'positionId' => $this->hrPeopleDoctorExpertPositionId,
            'selectedId' => 0));

        $this->body['controlExpertDropDown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
            'name' => 'expertId',
            'departmentId' => $this->hrPeopleExpertDepartmentId,
            'positionId' => $this->hrPeopleExpertPositionId,
            'selectedId' => 0));

        $this->body['controlNifsQuestionDropDown'] = $this->nifsQuestion->controlNifsQuestionDropDown_model(array(
            'selectedId' => 0,
            'catId' => $this->nifsQuestionCatId));

        $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
            'allInId' => array($this->session->adminPartnerId),
            'selectedId' => 0,
            'required' => 'true'));

        $this->body['controlNifsResearchTypeDropdown'] = $this->nifsResearchType->controlNifsResearchTypeDropdown_model(array(
            'selectedId' => 0,
            'catId' => $this->nifsResearchTypeCatId,
            'tabindex' => 2));

        $this->body['controlNifsMotiveDropdown'] = $this->nifsMotive->controlNifsMotiveDropdown_model(array(
            'selectedId' => 0,
            'catId' => $this->nifsMotiveCatId));

        $this->body['controlNifsCrimeTypeDropdown'] = $this->nifsCrimeType->controlNifsCrimeTypeDropdown_model(array(
            'name' => 'typeId',
            'selectedId' => 0,
            'catId' => $this->nifsCrimeTypeId));

        $this->body['controlNifsIsMixxCheckBox'] = $this->nifsIsMixx->controlNifsIsMixxCheckBox_model(array(
            'isMixx' => $this->body['row']->is_mixx,
            'initControlHtml' => 'initExtraControlExpertHtml',
            'addButtonName' => 'addNifsExtraExpertButton'));

        $this->body['controlNifsIsMixxDropdown'] = $this->nifsIsMixx->controlNifsIsMixxDropdown_model(array('selectedId' => 0));
        $this->body['controlNifsSolutionDropdown'] = $this->nifsSolution->controlNifsSolutionDropdown_model(array('selectedId' => 0, 'catId' => $this->nifsSolutionCatId));
        $this->body['controlNifsCloseTypeDropdown'] = $this->nifsCloseType->controlNifsCloseTypeDropdown_model(array('selectedId' => 0, 'catId' => $this->nifsCloseTypeCatId));

        $this->body['controlNifsStatusDropdown'] = $this->nifsStatus->controlNifsStatusDropdown_model(array('selectedId' => 0));

        $this->body['controlHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array('name' => 'departmentId', 'modId' => 69, 'selectedId' => 0));

        echo json_encode(array(
            'title' => $this->module->title . ' дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/nifsExtra/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Хаах'
        ));
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
        $objPHPExcel->getProperties()->setDescription($module->title . ' бүртгэл');
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

        $data = $this->nifsExtra->export_model(array(
            'auth' => $auth,
            'moduleMenuId' => $this->input->get('moduleMenuId'),
            'catId' => $this->input->get('catId'),
            'createNumber' => $this->input->get('createNumber'),
            'researchTypeId' => $this->input->get('researchTypeId'),
            'isMixx' => $this->input->get('isMixx'),
            'motiveId' => $this->input->get('motiveId'),
            'inDate' => $this->input->get('inDate'),
            'outDate' => $this->input->get('outDate'),
            'isAgeInfinitive' => $this->input->get('isAgeInfinitive'),
            'age1' => $this->input->get('age1'),
            'age2' => $this->input->get('age2'),
            'sex' => $this->input->get('sex'),
            'questionId' => $this->input->get('questionId'),
            'partnerId' => $this->input->get('partnerId'),
            'expertDoctorId' => $this->input->get('expertDoctorId'),
            'expertId' => $this->input->get('expertId'),
            'protocolNumber' => $this->input->get('protocolNumber'),
            'protocolInDate' => $this->input->get('protocolInDate'),
            'protocolOutDate' => $this->input->get('protocolOutDate'),
            'solutionId' => $this->input->get('solutionId'),
            'closeTypeId' => $this->input->get('closeTypeId'),
            'typeId' => $this->input->get('typeId'),
            'weight' => $this->input->get('weight'),
            'keywordTypeId' => $this->input->get('keywordTypeId'),
            'keyword' => $this->input->get('keyword'),
            'statusId' => $this->input->get('statusId'),
            'departmentId' => $this->input->get('departmentId'),
            'closeDescription' => $this->input->get('closeDescription'),
            'closeInDate' => $this->input->get('closeInDate'),
            'closeOutDate' => $this->input->get('closeOutDate')));

        $objPHPExcel->getActiveSheet()->mergeCells('A3:A4')->setCellValue('A3', '№');
        $objPHPExcel->getActiveSheet()->mergeCells('B3:B4')->setCellValue('B3', 'Журнал №');
        $objPHPExcel->getActiveSheet()->mergeCells('C3:C4')->setCellValue('C3', 'Шинжилгээ');
        $objPHPExcel->getActiveSheet()->mergeCells('D3:D4')->setCellValue('D3', 'Бүрэлдэхүүн');
        $objPHPExcel->getActiveSheet()->mergeCells('E3:E4')->setCellValue('E3', 'Үндэслэл');
        $objPHPExcel->getActiveSheet()->mergeCells('F3:G3')->setCellValue('F3', 'Бүртгэсэн огноо');
        $objPHPExcel->getActiveSheet()->setCellValue('F4', 'Ирсэн');
        $objPHPExcel->getActiveSheet()->setCellValue('G4', 'Дуусах');
        $objPHPExcel->getActiveSheet()->mergeCells('H3:H4')->setCellValue('H3', 'Эцэг /эх/-ийн нэр');
        $objPHPExcel->getActiveSheet()->mergeCells('I3:I4')->setCellValue('I3', 'Өөрийн нэр');
        $objPHPExcel->getActiveSheet()->mergeCells('J3:J4')->setCellValue('J3', 'Нас');
        $objPHPExcel->getActiveSheet()->mergeCells('K3:K4')->setCellValue('K3', 'Хүйс');
        $objPHPExcel->getActiveSheet()->mergeCells('J3:J4')->setCellValue('L3', 'Томилсон байгууллага');
        $objPHPExcel->getActiveSheet()->mergeCells('M3:M4')->setCellValue('M3', 'Албан тушаалтны нэр');
        $objPHPExcel->getActiveSheet()->mergeCells('N3:N4')->setCellValue('N3', 'Шинжилгээний төрөл');
        $objPHPExcel->getActiveSheet()->mergeCells('O3:O4')->setCellValue('O3', 'Тайлбар');
        $objPHPExcel->getActiveSheet()->mergeCells('P3:P4')->setCellValue('P3', 'Регистр');
        $objPHPExcel->getActiveSheet()->mergeCells('Q3:Q4')->setCellValue('Q3', 'Хэргийн төрөл');
        $objPHPExcel->getActiveSheet()->mergeCells('R3:R4')->setCellValue('R3', 'Хэргийн утга');
        $objPHPExcel->getActiveSheet()->mergeCells('S3:T3')->setCellValue('S3', 'Обьект');
        $objPHPExcel->getActiveSheet()->setCellValue('S4', 'Тоо');
        $objPHPExcel->getActiveSheet()->setCellValue('T4', 'Эд зүйлс');
        $objPHPExcel->getActiveSheet()->mergeCells('U3:U4')->setCellValue('U3', 'Шинжээчид тавигдсан асуултууд');
        $objPHPExcel->getActiveSheet()->mergeCells('V3:V4')->setCellValue('V3', 'Шинжээч эмч');
        $objPHPExcel->getActiveSheet()->mergeCells('W3:W4')->setCellValue('W3', 'Хэргийн дугаар');
        $objPHPExcel->getActiveSheet()->mergeCells('X3:Y3')->setCellValue('X3', 'Тогтоолын огноо');
        $objPHPExcel->getActiveSheet()->setCellValue('X4', 'Ирсэн');
        $objPHPExcel->getActiveSheet()->setCellValue('Y4', 'Дуусах');
        $objPHPExcel->getActiveSheet()->mergeCells('Z3:Z4')->setCellValue('Z3', 'Хаагдсан огноо');
        $objPHPExcel->getActiveSheet()->mergeCells('AA3:AA4')->setCellValue('AA3', 'Ачаалал');
        $objPHPExcel->getActiveSheet()->mergeCells('AB3:AB4')->setCellValue('AB3', 'Илэрсэн эсэх');
        $objPHPExcel->getActiveSheet()->mergeCells('AC3:AC4')->setCellValue('AC3', 'Дүгнэлт');
        $objPHPExcel->getActiveSheet()->mergeCells('AD3:AD4')->setCellValue('AD3', 'Шийдсэн хэлбэр');

        $objPHPExcel->getActiveSheet()->getStyle('A3:AD4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A3:AD4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('A3:AD4')->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '085f35')
                    )
                )
        );

        $phpColor = new PHPExcel_Style_Color();
        $phpColor->setRGB('ffffff');

        $objPHPExcel->getActiveSheet()->getStyle('A3:AD4')->getFont()->setColor($phpColor);

        $i = 5;
        $j = 1;

        if ($data) {

            foreach ($data as $key => $row) {

                $param = json_decode($row->param);

                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $j);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $row->create_number);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $param->researchType);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $row->is_mixx);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $param->motive);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $row->in_date);
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $row->out_date);
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $row->lname);
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $row->fname);
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $row->age);
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $row->sex);
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $param->partner);
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $row->agent_name);
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $param->type);
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $row->description);
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, $row->register);
                $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, $param->category);
                $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, $row->crime_value);
                $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, $row->object_count);
                $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, $row->object);
                $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, $param->question);
                $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, $row->expert);
                $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, '№: ' . $row->protocol_number);
                $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, $row->protocol_in_date);
                $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, $row->protocol_out_date);
                $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, $row->close_date);
                $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, $row->weight);
                $objPHPExcel->getActiveSheet()->setCellValue('AB' . $i, $param->solution);
                $objPHPExcel->getActiveSheet()->setCellValue('AC' . $i, $row->close_description);
                $objPHPExcel->getActiveSheet()->setCellValue('AD' . $i, $param->closeType);

                $i++;
                $j++;
            }
        }

        $i--;
        $j--;

        $objPHPExcel->getActiveSheet()->getStyle('A3:AD' . $i)->applyFromArray($this->styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('A1:AD' . $i)->getFont()->setName('Arial');
        $objPHPExcel->getActiveSheet()->getStyle('A1:AD' . $i)->getFont()->setSize(10);

        $objPHPExcel->getActiveSheet()->getStyle('A4:AD' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
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

    public function getReportWorkInformationData() {
        echo json_encode($this->nifsExtra->getReportWorkInformationData_model(array(
                    'reportIsClose' => $this->input->get('reportIsClose'),
                    'departmentId' => $this->input->get('departmentId'),
                    'inDate' => $this->input->get('inDate'),
                    'outDate' => $this->input->get('outDate'),
                    'reportModId' => $this->input->get('reportModId'),
                    'reportMenuId' => $this->input->get('reportMenuId'))));
    }

    public function getReportWeightData() {
        echo json_encode($this->nifsExtra->getReportWeightData_model(array(
                    'reportIsClose' => $this->input->get('reportIsClose'),
                    'departmentId' => $this->input->get('departmentId'),
                    'inDate' => $this->input->get('inDate'),
                    'outDate' => $this->input->get('outDate'),
                    'reportModId' => $this->input->get('reportModId'),
                    'reportMenuId' => $this->input->get('reportMenuId'))));
    }

    public function getReportPartnerData() {
        echo json_encode($this->nifsExtra->getReportPartnerData_model(array(
                    'reportIsClose' => $this->input->get('reportIsClose'),
                    'departmentId' => $this->input->get('departmentId'),
                    'inDate' => $this->input->get('inDate'),
                    'outDate' => $this->input->get('outDate'),
                    'reportModId' => $this->input->get('reportModId'),
                    'reportMenuId' => $this->input->get('reportMenuId'))));
    }

    public function getReportQuestionData() {
        echo json_encode($this->nifsExtra->getReportQuestionData_model(array(
                    'reportIsClose' => $this->input->get('reportIsClose'),
                    'departmentId' => $this->input->get('departmentId'),
                    'inDate' => $this->input->get('inDate'),
                    'outDate' => $this->input->get('outDate'),
                    'reportModId' => $this->input->get('reportModId'),
                    'reportMenuId' => $this->input->get('reportMenuId'))));
    }

    public function dataUpdate() {
        echo json_encode($this->nifsExtra->dataUpdate_model());
    }

}
