<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SnifsCrime extends CI_Controller {

    public static $path = "snifsCrime/";

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
        $this->load->model('SnifsKeywords_model', 'nifsKeywords');
        $this->load->model('SnifsMasterCase_model', 'nifsMasterCase');

        $this->perPage = 2;
        $this->hrPeopleLatentPrintPositionId = '10,5,6,7';
        $this->hrPeopleLatentPrintDepartmentId = '8,18,3';

        $this->hrPeopleExpertPositionId = '2,3,4,5,6,7,8,10,11,12,13,14,15,16,17,18,19,27,28,29,30,41,42';
        $this->hrPeopleExpertDepartmentId = ($this->session->adminDepartmentId == 7 ? '1,3,4,5,6,7,8,18' : $this->session->adminAllDepartmentId);
        $this->hrPeopleExpertOnlyDepartment = ($this->session->adminDepartmentId == 7 ? 3 : $this->session->adminDepartmentId);
        $this->nifsCrimeTypeId = 353;
        $this->nifsQuestionCatId = 371;
        $this->nifsSolutionCatId = 359;
        $this->nifsCloseTypeCatId = 365;
        $this->nifsResearchTypeCatId = 380;
        $this->nifsMotiveCatId = 386;

        $this->modId = 33;
        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->isActiveDepartment = 'is_active_nifs_crime';

        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/nifsCrime.js', '/assets/system/core/nifsIsMixx.js', '/assets/system/core/_hrPeople.js');

            $this->body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'isModule',
                'moduleMenuId' => $this->uri->segment(3),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['auth']->modId));
            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['module']->title));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            if ($this->body['auth']->permission) {
                $this->load->view(MY_ADMIN . '/nifsCrime/index', $this->body);
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
            $totalRec = $this->nifsCrime->listsCount_model(array(
                'auth' => $this->auth,
                'selectedId' => $this->input->get('selectedId'),
                'catId' => $this->input->get('catId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'protocolInDate' => $this->input->get('protocolInDate'),
                'protocolOutDate' => $this->input->get('protocolOutDate'),
                'researchTypeId' => $this->input->get('researchTypeId'),
                'caseId' => $this->input->get('caseId'),
                'typeId' => $this->input->get('typeId'),
                'motiveId' => $this->input->get('motiveId'),
                'partnerId' => $this->input->get('partnerId'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'latentPrintDepartmentId' => $this->input->get('latentPrintDepartmentId'),
                'latentPrintExpertId' => $this->input->get('latentPrintExpertId'),
                'expertId' => $this->input->get('expertId'),
                'createNumber' => $this->input->get('createNumber'),
                'protocolNumber' => $this->input->get('protocolNumber'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'solutionId' => $this->input->get('solutionId'),
                'isMixx' => $this->input->get('isMixx'),
                'closeDate' => $this->input->get('closeDate'),
                'statusId' => $this->input->get('statusId'),
                'departmentId' => $this->input->get('departmentId'),
                'closeDescription' => $this->input->get('closeDescription'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'isMyList' => $this->input->get('isMyList')));

            $result = $this->nifsCrime->lists_model(array(
                'auth' => $this->auth,
                'selectedId' => $this->input->get('selectedId'),
                'catId' => $this->input->get('catId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'protocolInDate' => $this->input->get('protocolInDate'),
                'protocolOutDate' => $this->input->get('protocolOutDate'),
                'crimeAgainId' => $this->input->get('crimeAgainId'),
                'motiveId' => $this->input->get('motiveId'),
                'partnerId' => $this->input->get('partnerId'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'latentPrintDepartmentId' => $this->input->get('latentPrintDepartmentId'),
                'latentPrintExpertId' => $this->input->get('latentPrintExpertId'),
                'expertId' => $this->input->get('expertId'),
                'createNumber' => $this->input->get('createNumber'),
                'researchTypeId' => $this->input->get('researchTypeId'),
                'caseId' => $this->input->get('caseId'),
                'typeId' => $this->input->get('typeId'),
                'motiveId' => $this->input->get('motiveId'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'latentPrintExpertId' => $this->input->get('latentPrintExpertId'),
                'expertId' => $this->input->get('expertId'),
                'createNumber' => $this->input->get('createNumber'),
                'protocolNumber' => $this->input->get('protocolNumber'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'solutionId' => $this->input->get('solutionId'),
                'isMixx' => $this->input->get('isMixx'),
                'closeDate' => $this->input->get('closeDate'),
                'statusId' => $this->input->get('statusId'),
                'departmentId' => $this->input->get('departmentId'),
                'closeDescription' => $this->input->get('closeDescription'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'isMyList' => $this->input->get('isMyList'),
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsCrime->addFormData_model();
            $this->body['row']->param = json_decode($this->body['row']->param);
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - нэмэх';

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
                'modId' => $this->input->post('modId'),
                'selectedId' => $this->body['row']->cat_id,
                'parentId' => 0,
                'space' => '',
                'counter' => 1));

            $this->body['controlNifsMasterCaseDropdown'] = $this->nifsMasterCase->controlNifsMasterCaseDropdown_model(array(
                'modId' => 90,
                'selectedId' => $this->body['row']->case_id));

            $this->body['controlNifsResearchTypeDropdown'] = $this->nifsResearchType->controlNifsResearchTypeDropdown_model(array(
                'selectedId' => 1,
                'catId' => $this->nifsResearchTypeCatId,
                'tabindex' => 2));

            $this->body['controlNifsCrimeTypeDropdown'] = $this->nifsCrimeType->controlNifsCrimeTypeDropdown_model(array(
                'name' => 'typeId',
                'selectedId' => 0,
                'catId' => $this->nifsCrimeTypeId));

            $this->body['controlNifsMotiveDropdown'] = $this->nifsMotive->controlNifsMotiveDropdown_model(array(
                'selectedId' => $this->body['row']->motive_id,
                'catId' => $this->nifsMotiveCatId));

            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
                'allInId' => array($this->session->adminPartnerId),
                'selectedId' => 0));

            $this->body['controlHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
                'name' => 'latentPrintDepartmentId',
                'selectedId' => 0,
                'isActiveDepartment' => $this->isActiveDepartment,
                'departmentId' => $this->nifsDepartmentId));

            $this->body['controlHrPeopleLatentPrintExpertListDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
                'isCurrenty' => 1,
                'name' => 'latentPrintExpertId',
                'departmentId' => $this->hrPeopleLatentPrintDepartmentId,
                'positionId' => $this->hrPeopleLatentPrintPositionId,
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
                'initControlHtml' => 'initCrimeControlExpertHtml',
                'isExtraValue' => 'true'));

            $this->body['controlNifsIsMixxCheckBox'] = $this->nifsIsMixx->controlNifsIsMixxCheckBox_model(array(
                'isMixx' => $this->body['row']->is_mixx,
                'initControlHtml' => 'initCrimeControlExpertHtml',
                'addButtonName' => 'addNifsCrimeExpertButton'));



            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'departmentId' => $this->nifsDepartmentId,
                'html' => $this->load->view(MY_ADMIN . '/nifsCrime/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsCrime->editFormData_model(array('id' => $this->input->post('id')));
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - засах';
            $this->body['row']->param = json_decode($this->body['row']->param);

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
                'selectedId' => $this->body['row']->cat_id,
                'modId' => $this->input->post('modId'),
                'parentId' => 0,
                'space' => '',
                'counter' => 1));

            $this->body['controlNifsMasterCaseDropdown'] = $this->nifsMasterCase->controlNifsMasterCaseDropdown_model(array(
                'modId' => 90,
                'selectedId' => $this->body['row']->case_id));

            $this->body['controlNifsResearchTypeDropdown'] = $this->nifsResearchType->controlNifsResearchTypeDropdown_model(array(
                'selectedId' => $this->body['row']->research_type_id,
                'catId' => $this->nifsResearchTypeCatId,
                'tabindex' => 2));

            $this->body['controlNifsCrimeTypeDropdown'] = $this->nifsCrimeType->controlNifsCrimeTypeDropdown_model(array(
                'selectedId' => $this->body['row']->crime_type_id,
                'name' => 'typeId',
                'catId' => $this->nifsCrimeTypeId));

            $this->body['controlNifsMotiveDropdown'] = $this->nifsMotive->controlNifsMotiveDropdown_model(array(
                'selectedId' => $this->body['row']->motive_id,
                'catId' => $this->nifsMotiveCatId));

            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
                'selectedId' => $this->body['row']->partner_id,
                'allInId' => array($this->session->adminPartnerId)));

            $this->body['controlHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
                'name' => 'latentPrintDepartmentId',
                'selectedId' => $this->body['row']->latent_print_department_id,
                'isActiveDepartment' => $this->isActiveDepartment,
                'departmentId' => $this->nifsDepartmentId));

            $this->body['controlHrPeopleLatentPrintExpertListDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
                'isCurrenty' => 1,
                'name' => 'latentPrintExpertId',
                'departmentId' => $this->hrPeopleLatentPrintDepartmentId,
                'positionId' => $this->hrPeopleLatentPrintPositionId,
                'selectedId' => $this->body['row']->latent_print_expert_id));

            $this->body['controlHrPeopleExpertMultiListDropdown'] = $this->hrPeople->controlHrPeopleMultiListDropdown_model(array(
                'name' => 'expertId[]',
                'positionId' => $this->hrPeopleExpertPositionId,
                'departmentId' => $this->hrPeopleExpertOnlyDepartment,
                'modId' => $this->body['row']->mod_id,
                'contId' => $this->body['row']->id,
                'isMixx' => $this->body['row']->is_mixx,
                'researchTypeId' => $this->body['row']->research_type_id,
                'isCurrenty' => 1,
                'isLogView' => 1,
                'initControlHtml' => 'initCrimeControlExpertHtml',
                'isExtraValue' => 'true'));

            $this->body['controlNifsIsMixxCheckBox'] = $this->nifsIsMixx->controlNifsIsMixxCheckBox_model(array(
                'isMixx' => $this->body['row']->is_mixx,
                'initControlHtml' => 'initCrimeControlExpertHtml',
                'addButtonName' => 'addNifsCrimeExpertButton'));

            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'btn_save_close' => 'Хадгалах, хаалт',
                'departmentId' => $this->nifsDepartmentId,
                'html' => $this->load->view(MY_ADMIN . '/nifsCrime/formEdit', $this->body, TRUE)));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        $this->nifsKeywords->insert_model(array(
            'modId' => $this->input->post('modId'),
            'departmentId' => $this->nifsDepartmentId,
            'keyword' => $this->input->post('crimeValue')));

        $this->nifsKeywords->agentNameInsert_model(array(
            'modId' => $this->input->post('modId'),
            'departmentId' => $this->nifsDepartmentId,
            'keyword' => $this->input->post('agentName')));

        echo json_encode($this->nifsCrime->insert_model(array('getUID' => getUID('nifs_crime'))));
    }

    public function update() {

        $this->nifsKeywords->insert_model(array(
            'modId' => $this->input->post('modId'),
            'departmentId' => $this->nifsDepartmentId,
            'keyword' => $this->input->post('crimeValue')));

        $this->nifsKeywords->agentNameInsert_model(array(
            'modId' => $this->input->post('modId'),
            'departmentId' => $this->nifsDepartmentId,
            'keyword' => $this->input->post('agentName')));

        echo json_encode($this->nifsCrime->update_model());
    }

    public function delete() {
        echo json_encode($this->nifsCrime->delete_model());
    }

    public function closeFrom() {

        $this->body['row'] = $this->nifsCrime->editFormData_model(array('id' => $this->input->post('id')));
        $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
        $this->body['row']->module_title = $this->module->title . ' хаах';

        $this->body['controlNifsSolutionDropdown'] = $this->nifsSolution->controlNifsSolutionDropdown_model(array(
            'selectedId' => $this->body['row']->solution_id,
            'catId' => $this->nifsSolutionCatId));

        $this->body['controlNifsCloseTypeDropdown'] = $this->nifsCloseType->controlNifsCloseTypeDropdown_model(array(
            'selectedId' => $this->body['row']->close_type_id,
            'catId' => $this->nifsCloseTypeCatId));

        echo json_encode(array(
            'title' => $this->body['row']->module_title,
            'btn_yes' => 'Хадгалах',
            'btn_no' => 'Хаах',
            'html' => $this->load->view(MY_ADMIN . '/nifsCrime/formClose', $this->body, TRUE)
        ));
    }

    public function close() {
        echo json_encode($this->nifsCrime->close_model());
    }

    public function getReportWorkInformationData() {
        echo json_encode($this->nifsCrime->getReportWorkInformationData_model(array(
                    'reportIsClose' => $this->input->get('reportIsClose'),
                    'departmentId' => $this->input->get('departmentId'),
                    'inDate' => $this->input->get('inDate'),
                    'outDate' => $this->input->get('outDate'),
                    'reportModId' => $this->input->get('reportModId'),
                    'reportMenuId' => $this->input->get('reportMenuId'))));
    }

    public function getReportWeightData() {
        echo json_encode($this->nifsCrime->getReportWeightData_model(array(
                    'reportIsClose' => $this->input->get('reportIsClose'),
                    'departmentId' => $this->input->get('departmentId'),
                    'inDate' => $this->input->get('inDate'),
                    'outDate' => $this->input->get('outDate'),
                    'reportModId' => $this->input->get('reportModId'),
                    'reportMenuId' => $this->input->get('reportMenuId'))));
    }

    public function getReportPartnerData() {
        echo json_encode($this->nifsCrime->getReportPartnerData_model(array(
                    'reportIsClose' => $this->input->get('reportIsClose'),
                    'departmentId' => $this->input->get('departmentId'),
                    'inDate' => $this->input->get('inDate'),
                    'outDate' => $this->input->get('outDate'),
                    'reportModId' => $this->input->get('reportModId'),
                    'reportMenuId' => $this->input->get('reportMenuId'))));
    }

    function searchForm() {

        $this->body['row'] = $this->nifsCrime->addFormData_model();
        $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
        $this->body['row']->module_title = $this->module->title . ' - хайх';

        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
            'modId' => $this->body['row']->mod_id,
            'selectedId' => 0,
            'parentId' => 0,
            'space' => '',
            'counter' => 1));

        $this->body['controlNifsMasterCaseDropdown'] = $this->nifsMasterCase->controlNifsMasterCaseDropdown_model(array(
                'modId' => 90,
                'selectedId' => $this->body['row']->case_id));
        
        $this->body['controlNifsResearchTypeDropdown'] = $this->nifsResearchType->controlNifsResearchTypeDropdown_model(array(
            'selectedId' => 0,
            'catId' => $this->nifsResearchTypeCatId,
            'tabindex' => 2));

        $this->body['controlNifsCrimeTypeDropdown'] = $this->nifsCrimeType->controlNifsCrimeTypeDropdown_model(array(
            'name' => 'typeId',
            'selectedId' => 0,
            'catId' => $this->nifsCrimeTypeId));

        $this->body['controlNifsMotiveDropdown'] = $this->nifsMotive->controlNifsMotiveDropdown_model(array(
            'selectedId' => 0,
            'catId' => $this->nifsMotiveCatId));

        $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
            'allInId' => array($this->session->adminPartnerId),
            'selectedId' => 0));

        $this->body['controlHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
            'name' => 'latentPrintDepartmentId',
            'selectedId' => 0,
            'isActiveDepartment' => $this->isActiveDepartment,
            'departmentId' => $this->nifsDepartmentId));

        $this->body['controlHrPeopleLatentPrintExpertListDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
            'name' => 'latentPrintExpertId',
            'departmentId' => $this->hrPeopleLatentPrintDepartmentId,
            'positionId' => $this->hrPeopleLatentPrintPositionId,
            'selectedId' => 0));


        $this->body['controlHrPeopleExpertListDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
            'name' => 'expertId',
            'modId' => $this->body['row']->mod_id,
            'isCurrenty' => 1,
            'isLogView' => 1,
            'positionId' => $this->hrPeopleExpertPositionId,
            'departmentId' => $this->hrPeopleExpertDepartmentId,
            'selectedId' => 0));

        $this->body['controlNifsIsMixxDropdown'] = $this->nifsIsMixx->controlNifsIsMixxDropdown_model(array('selectedId' => 0));
        $this->body['controlNifsSolutionDropdown'] = $this->nifsSolution->controlNifsSolutionDropdown_model(array('selectedId' => 0, 'catId' => $this->nifsSolutionCatId));
        $this->body['controlNifsCloseTypeDropdown'] = $this->nifsCloseType->controlNifsCloseTypeDropdown_model(array('selectedId' => 0, 'catId' => $this->nifsCloseTypeCatId));

        $this->body['controlNifsStatusDropdown'] = $this->nifsStatus->controlNifsStatusDropdown_model(array('selectedId' => 0));

        $this->body['controlHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array('name' => 'departmentId', 'modId' => 69, 'selectedId' => 0));

        echo json_encode(array(
            'title' => $this->body['row']->module_title,
            'html' => $this->load->view(MY_ADMIN . '/nifsCrime/formSearch', $this->body, TRUE),
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

        $data = $this->nifsCrime->export_model(array(
            'auth' => $auth,
            'catId' => $this->input->get('catId'),
            'inDate' => $this->input->get('inDate'),
            'outDate' => $this->input->get('outDate'),
            'protocolInDate' => $this->input->get('protocolInDate'),
            'protocolOutDate' => $this->input->get('protocolOutDate'),
            'researchTypeId' => $this->input->get('researchTypeId'),
            'typeId' => $this->input->get('typeId'),
            'motiveId' => $this->input->get('motiveId'),
            'partnerId' => $this->input->get('partnerId'),
            'closeTypeId' => $this->input->get('closeTypeId'),
            'latentPrintDepartmentId' => $this->input->get('latentPrintDepartmentId'),
            'latentPrintExpertId' => $this->input->get('latentPrintExpertId'),
            'expertId' => $this->input->get('expertId'),
            'createNumber' => $this->input->get('createNumber'),
            'protocolNumber' => $this->input->get('protocolNumber'),
            'keywordTypeId' => $this->input->get('keywordTypeId'),
            'keyword' => $this->input->get('keyword'),
            'solutionId' => $this->input->get('solutionId'),
            'isMixx' => $this->input->get('isMixx'),
            'closeDate' => $this->input->get('closeDate'),
            'statusId' => $this->input->get('statusId'),
            'departmentId' => $this->input->get('departmentId'),
            'closeDescription' => $this->input->get('closeDescription'),
            'closeInDate' => $this->input->get('closeInDate'),
            'closeOutDate' => $this->input->get('closeOutDate')));

        $objPHPExcel->getActiveSheet()->mergeCells('A3:A4')->setCellValue('A3', '№');
        $objPHPExcel->getActiveSheet()->mergeCells('B3:B4')->setCellValue('B3', 'Журнал №');
        $objPHPExcel->getActiveSheet()->mergeCells('C3:D3')->setCellValue('C3', 'Бүртгэсэн огноо');
        $objPHPExcel->getActiveSheet()->setCellValue('C4', 'Эхлэх');
        $objPHPExcel->getActiveSheet()->setCellValue('D4', 'Дуусах');
        $objPHPExcel->getActiveSheet()->mergeCells('E3:E4')->setCellValue('E3', 'Шинжилгээ');
        $objPHPExcel->getActiveSheet()->mergeCells('F3:F4')->setCellValue('F3', 'Бүрэлдэхүүнтэй эсэх');
        $objPHPExcel->getActiveSheet()->mergeCells('G3:G4')->setCellValue('G3', 'Үндэслэл');
        $objPHPExcel->getActiveSheet()->mergeCells('H3:H4')->setCellValue('H3', 'Томилсон байгууллага');
        $objPHPExcel->getActiveSheet()->mergeCells('I3:I4')->setCellValue('I3', 'Албан тушаалтаны нэр');
        $objPHPExcel->getActiveSheet()->mergeCells('J3:J4')->setCellValue('J3', 'Шинжилгээний төрөл');
        $objPHPExcel->getActiveSheet()->mergeCells('K3:K4')->setCellValue('K3', 'Тайлбар');
        $objPHPExcel->getActiveSheet()->mergeCells('L3:L4')->setCellValue('L3', 'Холбогдох мэдээлэл');
        $objPHPExcel->getActiveSheet()->mergeCells('M3:M4')->setCellValue('M3', 'Хэргийн төрөл');
        $objPHPExcel->getActiveSheet()->mergeCells('N3:N4')->setCellValue('N3', 'Хэргийн утга');
        $objPHPExcel->getActiveSheet()->mergeCells('O3:O4')->setCellValue('O3', 'Мөр бэхжүүлсэн шинжээч');
        $objPHPExcel->getActiveSheet()->mergeCells('P3:P4')->setCellValue('P3', 'Обьект');
        $objPHPExcel->getActiveSheet()->mergeCells('Q3:Q4')->setCellValue('Q3', 'Ирүүлсэн обьект');
        $objPHPExcel->getActiveSheet()->mergeCells('R3:R4')->setCellValue('R3', 'Шинжээчийн асуулт');
        $objPHPExcel->getActiveSheet()->mergeCells('S3:S4')->setCellValue('S3', 'Шинжээч');
        $objPHPExcel->getActiveSheet()->mergeCells('T3:T4')->setCellValue('T3', 'Хэргийн дугаар');
        $objPHPExcel->getActiveSheet()->mergeCells('U3:V3')->setCellValue('U3', 'Тогтоолын огноо');
        $objPHPExcel->getActiveSheet()->setCellValue('U4', 'Эхлэх');
        $objPHPExcel->getActiveSheet()->setCellValue('V4', 'Дуусах');
        $objPHPExcel->getActiveSheet()->mergeCells('W3:W4')->setCellValue('W3', 'Хаагдсан огноо');
        $objPHPExcel->getActiveSheet()->mergeCells('X3:X4')->setCellValue('X3', 'Ачаалал');
        $objPHPExcel->getActiveSheet()->mergeCells('Y3:Y4')->setCellValue('Y3', 'Дүгнэлт');
        $objPHPExcel->getActiveSheet()->mergeCells('Z3:Z4')->setCellValue('Z3', 'Шийдвэр');

        $objPHPExcel->getActiveSheet()->getStyle('A3:Z4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A3:Z4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('A3:Z4')->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '085f35')
                    )
                )
        );

        $phpColor = new PHPExcel_Style_Color();
        $phpColor->setRGB('ffffff');

        $objPHPExcel->getActiveSheet()->getStyle('A3:Z4')->getFont()->setColor($phpColor);

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
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $param->researchType);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $row->is_mixx);
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $param->motive);
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $param->partner);
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $row->agent_name);
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $param->category);
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $row->description);
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $row->short_info);
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $param->type);
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $row->crime_value);
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $param->latentPrintExpert);
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, $row->object_count);
                $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, $row->crime_object);
                $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, $row->question);
                $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, $row->expert);
                $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, '№:' . $row->protocol_number, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, $row->protocol_in_date);
                $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, $row->protocol_out_date);
                $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, $row->close_date);
                $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, $row->weight);
                $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, $row->close_description);
                $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, $row->solution_title);
            }
        }



        $objPHPExcel->getActiveSheet()->getStyle('A3:Z' . $i)->applyFromArray($this->styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('A1:Z' . $i)->getFont()->setName('Arial');
        $objPHPExcel->getActiveSheet()->getStyle('A1:Z' . $i)->getFont()->setSize(10);

        $objPHPExcel->getActiveSheet()->getStyle('A4:Z' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

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
        echo json_encode($this->nifsCrime->dataUpdate_model());
    }

}
