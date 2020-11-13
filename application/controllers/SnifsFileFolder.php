<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SnifsFileFolder extends CI_Controller {

    public static $path = "snifsFileFolder/";

    function __construct() {

        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('SnifsFileFolder_model', 'nifsFileFolder');
        $this->load->model('SnifsResearchType_model', 'nifsResearchType');
        $this->load->model('SnifsMotive_model', 'nifsMotive');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Sbreadcrumb_model', 'breadcrumb');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('SnifsCloseType_model', 'nifsCloseType');
        $this->load->model('SnifsSolution_model', 'nifsSolution');
        $this->load->model('SnifsIsMixx_model', 'nifsIsMixx');
        $this->load->model('SnifsCloseType_model', 'nifsCloseType');
        $this->load->model('SnifsSolution_model', 'nifsSolution');
        $this->load->model('SnifsQuestion_model', 'nifsQuestion');
        $this->load->model('SnifsStatus_model', 'nifsStatus');
        $this->load->model('SnifsKeywords_model', 'nifsKeywords');


        $this->perPage = 2;
        $this->hrPeoplePositionId = '5,6,7,13,27';
        $this->hrPeopleDepartmentId = '4,16,17,18';

        $this->hrPeoplePreExpertPositionId = '27';
        $this->hrPeoplePreExpertDepartmentId = 'all';
        $this->nifsCrimeTypeId = 354;
        $this->nifsQuestionCatId = 374;
        $this->nifsSolutionCatId = 362;
        $this->nifsCloseTypeCatId = 368;
        $this->nifsResearchTypeCatId = 383;
        $this->nifsMotiveCatId = 389;
        $this->modId = 50;

        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->hrPeopleExpertOnlyDepartment = ($this->session->adminDepartmentId == 7 ? 4 : $this->session->adminDepartmentId);

        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->footer['jsFile'] = array('/assets/system/core/nifsFileFolder.js', '/assets/system/core/nifsIsMixx.js', '/assets/system/core/nifsPreCrime.js', '/assets/system/core/_hrPeople.js', '/assets/system/core/nifsSendDocument.js', '/assets/system/core/nifsQuestion.js');

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
                $this->load->view(MY_ADMIN . '/nifsFileFolder/index', $this->body);
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
            $totalRec = $this->nifsFileFolder->listsCount_model(array(
                'auth' => $this->auth,
                'selectedId' => $this->input->get('selectedId'),
                'catId' => $this->input->get('catId'),
                'createNumber' => $this->input->get('createNumber'),
                'researchTypeId' => $this->input->get('researchTypeId'),
                'isMixx' => $this->input->get('isMixx'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'partnerId' => $this->input->get('partnerId'),
                'questionId' => $this->input->get('questionId'),
                'preCreateNumber' => $this->input->get('preCreateNumber'),
                'preExpertId' => $this->input->get('preExpertId'),
                'motiveId' => $this->input->get('motiveId'),
                'solutionId' => $this->input->get('solutionId'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'catId' => $this->input->get('catId'),
                'seniorExpertId' => $this->input->get('seniorExpertId'),
                'createExpertId' => $this->input->get('createExpertId'),
                'expertId' => $this->input->get('expertId'),
                'weight' => $this->input->get('weight'),
                'protocolNumber' => $this->input->get('protocolNumber'),
                'protocolInDate' => $this->input->get('protocolInDate'),
                'protocolOutDate' => $this->input->get('protocolOutDate'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'departmentId' => $this->input->get('departmentId'),
                'statusId' => $this->input->get('statusId'),
                'closeDescription' => $this->input->get('closeDescription'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'age1' => $this->input->get('age1'),
                'age2' => $this->input->get('age2'),
                'preCrime' => $this->input->get('preCrime')));



            //get posts data
            $result = $this->nifsFileFolder->lists_model(array(
                'auth' => $this->auth,
                'selectedId' => $this->input->get('selectedId'),
                'catId' => $this->input->get('catId'),
                'createNumber' => $this->input->get('createNumber'),
                'researchTypeId' => $this->input->get('researchTypeId'),
                'isMixx' => $this->input->get('isMixx'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'partnerId' => $this->input->get('partnerId'),
                'questionId' => $this->input->get('questionId'),
                'preCreateNumber' => $this->input->get('preCreateNumber'),
                'preExpertId' => $this->input->get('preExpertId'),
                'motiveId' => $this->input->get('motiveId'),
                'solutionId' => $this->input->get('solutionId'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'catId' => $this->input->get('catId'),
                'seniorExpertId' => $this->input->get('seniorExpertId'),
                'createExpertId' => $this->input->get('createExpertId'),
                'expertId' => $this->input->get('expertId'),
                'weight' => $this->input->get('weight'),
                'protocolNumber' => $this->input->get('protocolNumber'),
                'protocolInDate' => $this->input->get('protocolInDate'),
                'protocolOutDate' => $this->input->get('protocolOutDate'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'departmentId' => $this->input->get('departmentId'),
                'statusId' => $this->input->get('statusId'),
                'closeDescription' => $this->input->get('closeDescription'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'age1' => $this->input->get('age1'),
                'age2' => $this->input->get('age2'),
                'preCrime' => $this->input->get('preCrime'),
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));

            //load the view
            //$this->load->view(MY_ADMIN . '/nifsFileFolder/lists', $data, false);
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsFileFolder->addFormData_model();
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - нэмэх';

            $this->body['controlNifsSolutionDropdown'] = $this->nifsSolution->controlNifsSolutionDropdown_model(array(
                'selectedId' => 0, 'catId' => $this->nifsSolutionCatId));

            $this->body['controlpreExpertDropDown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
                'name' => 'preExpertId',
                'positionId' => $this->hrPeoplePreExpertPositionId,
                'selectedId' => 0,
                'isCurrenty' => 1));

            $this->body['controlSeniorExpertDropDown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
                'name' => 'seniorExpertId',
                'positionId' => $this->hrPeoplePositionId,
                'selectedId' => 0,
                'departmentId' => $this->hrPeopleDepartmentId,
                'isCurrenty' => 1));

            $this->body['controlCreateExpertDropDown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
                'name' => 'createExpertId',
                'positionId' => $this->hrPeoplePositionId,
                'selectedId' => 0,
                'departmentId' => $this->hrPeopleDepartmentId,
                'isCurrenty' => 1));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->input->post('modId'), 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlNifsResearchTypeDropdown'] = $this->nifsResearchType->controlNifsResearchTypeDropdown_model(array('selectedId' => $this->body['row']->research_type_id, 'catId' => $this->nifsResearchTypeCatId, 'tabindex' => 2));
            $this->body['controlNifsMotiveDropdown'] = $this->nifsMotive->controlNifsMotiveDropdown_model(array('selectedId' => $this->body['row']->motive_id, 'catId' => $this->nifsMotiveCatId));
            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('allInId' => array($this->session->adminPartnerId), 'selectedId' => 0));
            $this->body['controlNifsQuestionDropDown'] = $this->nifsQuestion->controlNifsQuestionDropDown_model(array('selectedId' => $this->body['row']->question_id, 'catId' => $this->nifsQuestionCatId));

            $this->body['controlHrPeopleMultiListDropdown'] = $this->hrPeople->controlHrPeopleMultiListDropdown_model(array(
                'name' => 'expertId[]',
                'departmentId' => $this->hrPeopleDepartmentId,
                'modId' => $this->body['row']->mod_id,
                'contId' => $this->body['row']->id,
                'positionId' => $this->hrPeoplePositionId,
                'isMixx' => $this->body['row']->is_mixx,
                'researchTypeId' => $this->body['row']->research_type_id,
                'isCurrenty' => 1,
                'initControlHtml' => 'initFileFolderControlExpertHtml',
                'isExtraValue' => 'true'));

            $this->body['controlNifsIsMixxCheckBox'] = $this->nifsIsMixx->controlNifsIsMixxCheckBox_model(array('isMixx' => $this->body['row']->is_mixx, 'initControlHtml' => 'initFileFolderControlExpertHtml', 'addButtonName' => 'addNifsFileFolderExpertButton'));


            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'departmentId' => $this->nifsDepartmentId,
                'html' => $this->load->view(MY_ADMIN . '/nifsFileFolder/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsFileFolder->editFormData_model(array('id' => $this->input->post('id')));
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - засах';

            $this->body['controlNifsSolutionDropdown'] = $this->nifsSolution->controlNifsSolutionDropdown_model(array('selectedId' => 0, 'catId' => $this->nifsSolutionCatId));

            $this->body['controlpreExpertDropDown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
                'name' => 'preExpertId',
                'positionId' => $this->hrPeoplePreExpertPositionId,
                'selectedId' => $this->body['row']->pre_expert_id,
                'isCurrenty' => 1));

            $this->body['controlSeniorExpertDropDown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
                'name' => 'seniorExpertId',
                'positionId' => $this->hrPeoplePositionId,
                'selectedId' => $this->body['row']->senior_expert_id,
                'departmentId' => $this->hrPeopleDepartmentId,
                'isCurrenty' => 1));

            $this->body['controlCreateExpertDropDown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
                'name' => 'createExpertId',
                'positionId' => $this->hrPeoplePositionId,
                'selectedId' => $this->body['row']->create_expert_id,
                'departmentId' => $this->hrPeopleDepartmentId,
                'isCurrenty' => 1));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->input->post('modId'), 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlNifsResearchTypeDropdown'] = $this->nifsResearchType->controlNifsResearchTypeDropdown_model(array('selectedId' => $this->body['row']->research_type_id, 'catId' => $this->nifsResearchTypeCatId, 'tabindex' => 2));
            $this->body['controlNifsMotiveDropdown'] = $this->nifsMotive->controlNifsMotiveDropdown_model(array('selectedId' => $this->body['row']->motive_id, 'catId' => $this->nifsMotiveCatId));
            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('allInId' => array($this->session->adminPartnerId), 'selectedId' => $this->body['row']->partner_id));
            $this->body['controlNifsQuestionDropDown'] = $this->nifsQuestion->controlNifsQuestionDropDown_model(array('selectedId' => $this->body['row']->question_id, 'catId' => $this->nifsQuestionCatId));

            $this->body['controlHrPeopleMultiListDropdown'] = $this->hrPeople->controlHrPeopleMultiListDropdown_model(array(
                'name' => 'expertId[]',
                'positionId' => $this->hrPeoplePositionId,
                'departmentId' => $this->hrPeopleExpertOnlyDepartment,
                'modId' => $this->body['row']->mod_id,
                'contId' => $this->body['row']->id,
                'isMixx' => $this->body['row']->is_mixx,
                'researchTypeId' => $this->body['row']->research_type_id,
                'isCurrenty' => 1,
                'isLogView' => 1,
                'initControlHtml' => 'initFileFolderControlExpertHtml',
                'isExtraValue' => 'true'));


            $this->body['controlNifsIsMixxCheckBox'] = $this->nifsIsMixx->controlNifsIsMixxCheckBox_model(array('isMixx' => $this->body['row']->is_mixx, 'initControlHtml' => 'initFileFolderControlExpertHtml', 'addButtonName' => 'addNifsFileFolderExpertButton'));

            echo json_encode(array(
                'title' => 'Хавтаст хэрэг засварлах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'btn_save_close' => 'Хадгалах, хаалт',
                'departmentId' => $this->nifsDepartmentId,
                'html' => $this->load->view(MY_ADMIN . '/nifsFileFolder/formEdit', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        $this->nifsKeywords->agentNameInsert_model(array(
            'modId' => $this->input->post('modId'),
            'departmentId' => $this->nifsDepartmentId,
            'keyword' => $this->input->post('agentName')));

        echo json_encode($this->nifsFileFolder->insert_model(array('getUID' => getUID('nifs_file_folder'))));
    }

    public function update() {

        $this->nifsKeywords->agentNameInsert_model(array(
            'modId' => $this->input->post('modId'),
            'departmentId' => $this->nifsDepartmentId,
            'keyword' => $this->input->post('agentName')));

        echo json_encode($this->nifsFileFolder->update_model());
    }

    public function delete() {
        echo json_encode($this->nifsFileFolder->delete_model());
    }

    public function closeFrom() {

        $this->body['row'] = $this->nifsFileFolder->editFormData_model(array('id' => $this->input->post('id')));
        $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
        $this->body['row']->module_title = $this->module->title . ' - засах';

        //$this->page->deny_model(array('moduleMenuId' => $this->input->post('moduleMenuId'), 'mode' => 'close', 'createdUserId' => $this->body['row']->created_user_id));
        $this->body['controlNifsSolutionDropdown'] = $this->nifsSolution->controlNifsSolutionDropdown_model(array('selectedId' => $this->body['row']->solution_id, 'catId' => $this->nifsSolutionCatId));

        $this->body['controlNifsCloseTypeDropdown'] = $this->nifsCloseType->controlNifsCloseTypeDropdown_model(array('selectedId' => $this->body['row']->close_type_id, 'catId' => $this->nifsCloseTypeCatId));

        echo json_encode(array(
            'title' => 'Шинжилгээ хаах',
            'btn_yes' => 'Хадгалах',
            'btn_no' => 'Хаах',
            'html' => $this->load->view(MY_ADMIN . '/nifsFileFolder/formClose', $this->body, TRUE)
        ));
    }

    public function close() {
        echo json_encode($this->nifsFileFolder->close_model());
    }

    public function searchForm() {

        $this->body['row'] = $this->nifsFileFolder->addFormData_model();
        $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
        $this->body['row']->module_title = $this->module->title . ' - хайлт';


        $this->body['controlNifsSolutionDropdown'] = $this->nifsSolution->controlNifsSolutionDropdown_model(array('selectedId' => 0, 'catId' => $this->nifsSolutionCatId));

        $this->body['controlpreExpertDropDown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array('name' => 'preExpertId', 'positionId' => $this->hrPeoplePreExpertPositionId, 'selectedId' => 0));
        $this->body['controlSeniorExpertDropDown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array('name' => 'seniorExpertId', 'positionId' => $this->hrPeoplePositionId, 'departmentId' => $this->hrPeopleDepartmentId, 'selectedId' => 0));
        $this->body['controlCreateExpertDropDown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array('name' => 'createExpertId', 'positionId' => $this->hrPeoplePositionId, 'departmentId' => $this->hrPeopleDepartmentId, 'selectedId' => 0));
        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->input->post('modId'), 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1));
        $this->body['controlNifsResearchTypeDropdown'] = $this->nifsResearchType->controlNifsResearchTypeDropdown_model(array('selectedId' => 0, 'catId' => $this->nifsResearchTypeCatId, 'tabindex' => 2));
        $this->body['controlNifsMotiveDropdown'] = $this->nifsMotive->controlNifsMotiveDropdown_model(array('selectedId' => 0, 'catId' => $this->nifsMotiveCatId));
        $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('allInId' => array($this->session->adminPartnerId), 'selectedId' => 0));
        $this->body['controlNifsQuestionDropDown'] = $this->nifsQuestion->controlNifsQuestionDropDown_model(array('selectedId' => 0, 'catId' => $this->nifsQuestionCatId));

        $this->body['controlExpertDropDown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array('name' => 'expertId', 'positionId' => $this->hrPeoplePositionId, 'departmentId' => $this->hrPeopleDepartmentId, 'selectedId' => 0, 'isExtraValue' => 'true'));

        $this->body['controlNifsIsMixxDropdown'] = $this->nifsIsMixx->controlNifsIsMixxDropdown_model(array('selectedId' => 0));

        $this->body['controlNifsStatusDropdown'] = $this->nifsStatus->controlNifsStatusDropdown_model(array('selectedId' => 0));

        $this->body['controlHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array('name' => 'departmentId', 'modId' => 69, 'selectedId' => 0));

        echo json_encode(array(
            'title' => $this->body['row']->module_title,
            'html' => $this->load->view(MY_ADMIN . '/nifsFileFolder/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    function searchReportForm() {

        $this->body = array();
        $this->body['modId'] = $this->input->post('modId');
        $this->body['path'] = $this->input->post('path');
        $this->body['controlDepartmentCategoryDropdown'] = $this->department->controlDepartmentCategoryDropdown_model(array('selectedId' => 0, 'isDisabled' => false));

        echo json_encode(array(
            'title' => 'Шинжээчийн тайлан',
            'html' => $this->load->view(MY_ADMIN . '/nifsFileFolder/formReportSearch', $this->body, TRUE),
            'width' => ($this->session->userdata('adminAccessTypeId') == 1 ? 600 : 350),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
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

        $data = $this->nifsFileFolder->export_model(array(
            'auth' => $auth,
            'catId' => $this->input->get('catId'),
            'createNumber' => $this->input->get('createNumber'),
            'researchTypeId' => $this->input->get('researchTypeId'),
            'isMixx' => $this->input->get('isMixx'),
            'inDate' => $this->input->get('inDate'),
            'outDate' => $this->input->get('outDate'),
            'partnerId' => $this->input->get('partnerId'),
            'questionId' => $this->input->get('questionId'),
            'preCreateNumber' => $this->input->get('preCreateNumber'),
            'preExpertId' => $this->input->get('preExpertId'),
            'motiveId' => $this->input->get('motiveId'),
            'solutionId' => $this->input->get('solutionId'),
            'closeTypeId' => $this->input->get('closeTypeId'),
            'catId' => $this->input->get('catId'),
            'seniorExpertId' => $this->input->get('seniorExpertId'),
            'createExpertId' => $this->input->get('createExpertId'),
            'expertId' => $this->input->get('expertId'),
            'weight' => $this->input->get('weight'),
            'protocolNumber' => $this->input->get('protocolNumber'),
            'protocolInDate' => $this->input->get('protocolInDate'),
            'protocolOutDate' => $this->input->get('protocolOutDate'),
            'keywordTypeId' => $this->input->get('keywordTypeId'),
            'keyword' => $this->input->get('keyword'),
            'departmentId' => $this->input->get('departmentId'),
            'statusId' => $this->input->get('statusId'),
            'closeDescription' => $this->input->get('closeDescription'),
            'closeInDate' => $this->input->get('closeInDate'),
            'closeOutDate' => $this->input->get('closeOutDate'),
            'age1' => $this->input->get('age1'),
            'age2' => $this->input->get('age2'),
            'preCrime' => $this->input->get('preCrime')));

        $objPHPExcel->getActiveSheet()->mergeCells('A3:A4')->setCellValue('A3', '№');
        $objPHPExcel->getActiveSheet()->mergeCells('B3:B4')->setCellValue('B3', 'Журнал №');
        $objPHPExcel->getActiveSheet()->mergeCells('C3:D3')->setCellValue('C3', 'Бүртгэсэн огноо');
        $objPHPExcel->getActiveSheet()->setCellValue('C4', 'Эхлэх');
        $objPHPExcel->getActiveSheet()->setCellValue('D4', 'Дуусах');
        $objPHPExcel->getActiveSheet()->mergeCells('E3:E4')->setCellValue('E3', 'Бүрэлдэхүүнтэй эсэх');
        $objPHPExcel->getActiveSheet()->mergeCells('F3:F4')->setCellValue('F3', 'Үндэслэл');
        $objPHPExcel->getActiveSheet()->mergeCells('G3:G4')->setCellValue('G3', 'Эцэг/эх/-ийн нэр');
        $objPHPExcel->getActiveSheet()->mergeCells('H3:H4')->setCellValue('H3', 'Өөрийн нэр');
        $objPHPExcel->getActiveSheet()->mergeCells('I3:I4')->setCellValue('I3', '');
        $objPHPExcel->getActiveSheet()->mergeCells('J3:J4')->setCellValue('J3', 'Томилсон байгууллага');
        $objPHPExcel->getActiveSheet()->mergeCells('K3:K4')->setCellValue('K3', 'Албан тушаалтны нэр');
        $objPHPExcel->getActiveSheet()->mergeCells('L3:L4')->setCellValue('L3', 'Шийдвэрлэх асуудал');
        $objPHPExcel->getActiveSheet()->mergeCells('M3:M4')->setCellValue('M3', 'Тайлбар');
        $objPHPExcel->getActiveSheet()->mergeCells('N3:N4')->setCellValue('N3', 'Өмнөх дүгнэлт');
        $objPHPExcel->getActiveSheet()->mergeCells('O3:P3')->setCellValue('O3', 'Ирүүлсэн объект');
        $objPHPExcel->getActiveSheet()->setCellValue('O4', 'Тоо');
        $objPHPExcel->getActiveSheet()->setCellValue('P4', 'Объект');
        $objPHPExcel->getActiveSheet()->mergeCells('Q3:Q4')->setCellValue('Q3', 'Хэргийн төрөл');
        $objPHPExcel->getActiveSheet()->mergeCells('R3:R4')->setCellValue('R3', 'Ахалсан шинжээч');
        $objPHPExcel->getActiveSheet()->mergeCells('S3:S4')->setCellValue('S3', 'Бичсэн шинжээч');
        $objPHPExcel->getActiveSheet()->mergeCells('T3:T4')->setCellValue('T3', 'Шинжээч');
        $objPHPExcel->getActiveSheet()->mergeCells('U3:U4')->setCellValue('U3', 'Хэргийн дугаар');
        $objPHPExcel->getActiveSheet()->mergeCells('V3:W3')->setCellValue('V3', 'Тогтоолын огноо');
        $objPHPExcel->getActiveSheet()->setCellValue('V4', 'Эхлэх');
        $objPHPExcel->getActiveSheet()->setCellValue('W4', 'Дуусах');
        $objPHPExcel->getActiveSheet()->mergeCells('X3:X4')->setCellValue('X3', 'Хаагдсан огноо');
        $objPHPExcel->getActiveSheet()->mergeCells('Y3:Y4')->setCellValue('Y3', 'Дүгнэлт');
        $objPHPExcel->getActiveSheet()->mergeCells('Z3:Z4')->setCellValue('Z3', 'Дүгнэлтээр гарсан эсэх');
        $objPHPExcel->getActiveSheet()->mergeCells('AA3:AA4')->setCellValue('AA3', 'Дүгнэлт зөрсөн эсэх');

        $objPHPExcel->getActiveSheet()->getStyle('A3:AA4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A3:AA4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('A3:AA4')->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '085f35')
                    )
                )
        );

        $phpColor = new PHPExcel_Style_Color();
        $phpColor->setRGB('ffffff');

        $objPHPExcel->getActiveSheet()->getStyle('A3:AA4')->getFont()->setColor($phpColor);

        $i = 5;
        $j = 1;

        if ($data) {

            foreach ($data as $key => $row) {

                $param = json_decode($row->param);

                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $j);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $row->create_number);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $row->in_date);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $row->out_date);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $param->researchType);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $row->is_mixx);
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $param->motive);
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $row->lname);
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $row->fname);
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $param->partner);
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $row->agent_name);
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $param->question);
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $row->description);
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $param->preCrime);
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $row->object_count);
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, $row->object);
                $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, $param->category);
                $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, $param->seniorExpert);
                $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, $param->createExpert);
                $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, $row->expert);
                $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, '№:' . $row->protocol_number, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, $row->protocol_in_date);
                $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, $row->protocol_out_date);
                $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, $row->close_date);
                $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, $row->close_description);
                $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, $param->solution);
                $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, $param->closeType);
//
                $i++;
                $j++;
            }
        }

        $i--;
        $j--;

        $objPHPExcel->getActiveSheet()->getStyle('A3:AA' . $i)->applyFromArray($this->styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('A1:AA' . $i)->getFont()->setName('Arial');
        $objPHPExcel->getActiveSheet()->getStyle('A1:Z' . $i)->getFont()->setSize(10);

        $objPHPExcel->getActiveSheet()->getStyle('A4:AA' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

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

    public function controlExpertDropdown() {
        echo json_encode($this->nifsExpert->controlExpertDropDown_model(array('selectedId' => 0, 'name' => 'expertId[]')));
    }

    public function getReportWorkInformationData() {
        echo json_encode($this->nifsFileFolder->getReportWorkInformationData_model(array(
                    'reportIsClose' => $this->input->get('reportIsClose'),
                    'departmentId' => $this->input->get('departmentId'),
                    'inDate' => $this->input->get('inDate'),
                    'outDate' => $this->input->get('outDate'),
                    'reportModId' => $this->input->get('reportModId'),
                    'reportMenuId' => $this->input->get('reportMenuId'))));
    }

    public function getReportPartnerData() {
        echo json_encode($this->nifsFileFolder->getReportPartnerData_model(array(
                    'reportIsClose' => $this->input->get('reportIsClose'),
                    'departmentId' => $this->input->get('departmentId'),
                    'inDate' => $this->input->get('inDate'),
                    'outDate' => $this->input->get('outDate'),
                    'reportModId' => $this->input->get('reportModId'),
                    'reportMenuId' => $this->input->get('reportMenuId'))));
    }

    public function getReportWeightData() {
        echo json_encode($this->nifsFileFolder->getReportWeightData_model(array(
                    'reportIsClose' => $this->input->get('reportIsClose'),
                    'departmentId' => $this->input->get('departmentId'),
                    'inDate' => $this->input->get('inDate'),
                    'outDate' => $this->input->get('outDate'),
                    'reportModId' => $this->input->get('reportModId'),
                    'reportMenuId' => $this->input->get('reportMenuId'))));
    }

    public function dataUpdate() {
        echo json_encode($this->nifsFileFolder->dataUpdate_model());
    }

}
