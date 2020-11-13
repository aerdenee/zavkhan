<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SnifsScene extends CI_Controller {

    public static $path = "snifsScene/";

    function __construct() {

        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('SnifsScene_model', 'nifsScene');
        $this->load->model('SnifsSceneType_model', 'nifsSceneType');
        $this->load->model('SnifsSceneFingerType_model', 'nifsSceneFingerType');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('SnifsKeywords_model', 'nifsKeywords');

        $this->perPage = 2;
        $this->hrPeopleLatentPrintPositionId = '10,5,6,7';
        $this->hrPeopleLatentPrintDepartmentId = '8,18,3';

        $this->hrPeopleExpertPositionId = '2,3,4,5,6,7,8,10,11,12,13,14,15,16,17,18,19,27,28,29,30,41,42';
        $this->hrPeopleExpertDepartmentId = ($this->session->adminDepartmentId == 7 ? '1,3,4,5,6,7,8,18' : $this->session->adminAllDepartmentId);

        $this->nifsSceneFingerPrintTypeId = 447;
        $this->nifsSceneBootPrintTypeId = 448;
        $this->nifsSceneTransportPrintTypeId = 449;

        $this->modId = 33;
        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->isActiveDepartment = 'is_active_nifs_crime';

        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/nifsScene.js', '/assets/system/core/nifsIsMixx.js', '/assets/system/core/nifsQuestion.js', '/assets/system/core/_hrPeople.js');

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
                $this->load->view(MY_ADMIN . '/nifsScene/index', $this->body);
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
            $totalRec = $this->nifsScene->listsCount_model(array(
                'auth' => $this->auth,
                'catId' => $this->input->get('catId'),
                'inDate' => $this->input->get('inDate'),
                'inTime' => $this->input->get('inTime'),
                'outDate' => $this->input->get('outDate'),
                'outTime' => $this->input->get('outTime'),
                'sceneTypeId' => $this->input->get('sceneTypeId'),
                'partnerId' => $this->input->get('partnerId'),
                'expertId' => $this->input->get('expertId'),
                'createNumber' => $this->input->get('createNumber'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'departmentId' => $this->input->get('departmentId')));

            //get posts data
            $result = $this->nifsScene->lists_model(array(
                'auth' => $this->auth,
                'catId' => $this->input->get('catId'),
                'inDate' => $this->input->get('inDate'),
                'inTime' => $this->input->get('inTime'),
                'outDate' => $this->input->get('outDate'),
                'outTime' => $this->input->get('outTime'),
                'partnerId' => $this->input->get('partnerId'),
                'expertId' => $this->input->get('expertId'),
                'createNumber' => $this->input->get('createNumber'),
                'sceneTypeId' => $this->input->get('sceneTypeId'),
                'expertId' => $this->input->get('expertId'),
                'createNumber' => $this->input->get('createNumber'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'departmentId' => $this->input->get('departmentId'),
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsScene->addFormData_model();
            $this->body['row']->param = json_decode($this->body['row']->param);
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - нэмэх';

            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
                'allInId' => array($this->session->adminPartnerId),
                'selectedId' => 0));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
                'modId' => $this->input->post('modId'),
                'selectedId' => $this->body['row']->cat_id,
                'parentId' => 0,
                'space' => '',
                'counter' => 1));

            $this->body['controlNifsSceneTypeDropdown'] = $this->nifsSceneType->controlNifsSceneTypeDropdown_model(array(
                'catId' => $this->input->get('catId'),
                'selectedId' => $this->input->get('selectedId')));

            $this->body['controlFingerPrintTypeDropdown'] = $this->nifsSceneFingerType->controlNifsSceneFingerTypeDropdown_model(array(
                'name' => 'fingerPrintTypeId',
                'selectedId' => 0,
                'catId' => $this->nifsSceneFingerPrintTypeId));

            $this->body['controlBootPrintTypeDropdown'] = $this->nifsSceneFingerType->controlNifsSceneFingerTypeDropdown_model(array(
                'name' => 'bootPrintTypeId',
                'selectedId' => 0,
                'catId' => $this->nifsSceneBootPrintTypeId));

            $this->body['controlTransportPrintTypeDropdown'] = $this->nifsSceneFingerType->controlNifsSceneFingerTypeDropdown_model(array(
                'name' => 'transportPrintTypeId',
                'selectedId' => 0,
                'catId' => $this->nifsSceneTransportPrintTypeId));

            $this->body['controlHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
                'name' => 'latentPrintDepartmentId',
                'selectedId' => 0,
                'isActiveDepartment' => $this->isActiveDepartment,
                'departmentId' => $this->nifsDepartmentId));

            $this->body['controlHrPeopleExpertMultiListDropdown'] = $this->hrPeople->controlHrPeopleMultiListDropdown_model(array(
                'isCurrenty' => 1,
                'name' => 'expertId[]',
                'positionId' => $this->hrPeopleExpertPositionId,
                'departmentId' => $this->hrPeopleExpertDepartmentId,
                'modId' => $this->body['row']->mod_id,
                'contId' => $this->body['row']->id,
                'isMixx' => 1,
                'initControlHtml' => 'initSceneControlExpertHtml',
                'isExtraValue' => 'true'));

            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'departmentId' => $this->nifsDepartmentId,
                'html' => $this->load->view(MY_ADMIN . '/nifsScene/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsScene->editFormData_model(array('id' => $this->input->post('id')));
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - засах';
            $this->body['row']->param = json_decode($this->body['row']->param);

            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
                'allInId' => array($this->session->adminPartnerId),
                'selectedId' => $this->body['row']->partner_id));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
                'modId' => $this->input->post('modId'),
                'selectedId' => $this->body['row']->cat_id,
                'parentId' => $this->body['row']->partner_id,
                'space' => '',
                'counter' => 1));

            $this->body['controlNifsSceneTypeDropdown'] = $this->nifsSceneType->controlNifsSceneTypeDropdown_model(array(
                'catId' => $this->input->get('catId'),
                'selectedId' => $this->body['row']->scene_type_id));

            $this->body['controlFingerPrintTypeDropdown'] = $this->nifsSceneFingerType->controlNifsSceneFingerTypeDropdown_model(array(
                'name' => 'fingerPrintTypeId',
                'selectedId' => $this->body['row']->finger_print_type_id,
                'catId' => $this->nifsSceneFingerPrintTypeId));

            $this->body['controlBootPrintTypeDropdown'] = $this->nifsSceneFingerType->controlNifsSceneFingerTypeDropdown_model(array(
                'name' => 'bootPrintTypeId',
                'selectedId' => $this->body['row']->boot_print_type_id,
                'catId' => $this->nifsSceneBootPrintTypeId));

            $this->body['controlTransportPrintTypeDropdown'] = $this->nifsSceneFingerType->controlNifsSceneFingerTypeDropdown_model(array(
                'name' => 'transportPrintTypeId',
                'selectedId' => $this->body['row']->transport_print_type_id,
                'catId' => $this->nifsSceneTransportPrintTypeId));

            $this->body['controlHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
                'name' => 'latentPrintDepartmentId',
                'selectedId' => $this->body['row']->department_id,
                'isActiveDepartment' => $this->isActiveDepartment,
                'departmentId' => $this->nifsDepartmentId));

            $this->body['controlHrPeopleExpertMultiListDropdown'] = $this->hrPeople->controlHrPeopleMultiListDropdown_model(array(
                'isCurrenty' => 1,
                'name' => 'expertId[]',
                'positionId' => $this->hrPeopleExpertPositionId,
                'departmentId' => $this->hrPeopleExpertDepartmentId,
                'modId' => $this->body['row']->mod_id,
                'contId' => $this->body['row']->id,
                'isMixx' => 1,
                'initControlHtml' => 'initSceneControlExpertHtml',
                'isExtraValue' => 'true'));

            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'btn_save_close' => 'Хадгалах, хаалт',
                'departmentId' => $this->nifsDepartmentId,
                'html' => $this->load->view(MY_ADMIN . '/nifsScene/formEdit', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        $this->nifsKeywords->insert_model(array(
            'modId' => $this->input->post('modId'),
            'departmentId' => $this->nifsDepartmentId,
            'keyword' => $this->input->post('sceneValue')));

        $this->nifsKeywords->agentNameInsert_model(array(
            'modId' => $this->input->post('modId'),
            'departmentId' => $this->nifsDepartmentId,
            'keyword' => $this->input->post('sceneExpert')));

        echo json_encode($this->nifsScene->insert_model(array('getUID' => getUID('nifs_scene'))));
    }

    public function update() {

        $this->nifsKeywords->insert_model(array(
            'modId' => $this->input->post('modId'),
            'departmentId' => $this->nifsDepartmentId,
            'keyword' => $this->input->post('sceneValue')));

        $this->nifsKeywords->agentNameInsert_model(array(
            'modId' => $this->input->post('modId'),
            'departmentId' => $this->nifsDepartmentId,
            'keyword' => $this->input->post('sceneExpert')));

        echo json_encode($this->nifsScene->update_model());
    }

    public function delete() {
        echo json_encode($this->nifsScene->delete_model());
    }

    public function getReportWorkInformationData() {
        echo json_encode($this->nifsScene->getReportWorkInformationData_model(array(
                    'reportIsClose' => $this->input->get('reportIsClose'),
                    'departmentId' => $this->input->get('departmentId'),
                    'inDate' => $this->input->get('inDate'),
                    'outDate' => $this->input->get('outDate'),
                    'reportModId' => $this->input->get('reportModId'),
                    'reportMenuId' => $this->input->get('reportMenuId'))));
    }
    
    public function getReportWeightData() {
        echo json_encode($this->nifsScene->getReportWeightData_model(array(
            'reportIsClose' => $this->input->get('reportIsClose'),
            'departmentId' => $this->input->get('departmentId'), 
            'inDate' => $this->input->get('inDate'), 
            'outDate' => $this->input->get('outDate'), 
            'reportModId' => $this->input->get('reportModId'), 
            'reportMenuId' => $this->input->get('reportMenuId'))));
    }

    public function getReportPartnerData() {
        echo json_encode($this->nifsScene->getReportPartnerData_model(array(
            'reportIsClose' => $this->input->get('reportIsClose'),
            'departmentId' => $this->input->get('departmentId'), 
            'inDate' => $this->input->get('inDate'), 
            'outDate' => $this->input->get('outDate'), 
            'reportModId' => $this->input->get('reportModId'), 
            'reportMenuId' => $this->input->get('reportMenuId'))));
    }

    function searchForm() {

        $this->body['row'] = $this->nifsScene->addFormData_model();
        $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
        $this->body['row']->module_title = $this->module->title . ' - хайх';

        $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
            'selectedId' => 0));

        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
            'modId' => $this->body['row']->mod_id,
            'selectedId' => $this->body['row']->cat_id,
            'parentId' => 0,
            'space' => '',
            'counter' => 1));

        $this->body['controlHrPeopleExpertListDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
            'name' => 'expertId',
            'departmentId' => $this->hrPeopleExpertDepartmentId,
            'selectedId' => 0));

        $this->body['controlHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array('name' => 'departmentId', 'modId' => 69, 'selectedId' => 0));

        $this->body['controlNifsSceneTypeDropdown'] = $this->nifsSceneType->controlNifsSceneTypeDropdown_model(array(
            'catId' => $this->input->get('catId'),
            'selectedId' => $this->input->get('selectedId')));

        echo json_encode(array(
            'title' => $this->body['row']->module_title,
            'html' => $this->load->view(MY_ADMIN . '/nifsScene/formSearch', $this->body, TRUE),
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
            'html' => $this->load->view(MY_ADMIN . '/nifsScene/formReportSearch', $this->body, TRUE),
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

        $data = $this->nifsScene->export_model(array(
            'auth' => $auth,
            'catId' => $this->input->get('catId'),
            'inDate' => $this->input->get('inDate'),
            'inTime' => $this->input->get('inTime'),
            'outDate' => $this->input->get('outDate'),
            'outTime' => $this->input->get('outTime'),
            'partnerId' => $this->input->get('partnerId'),
            'expertId' => $this->input->get('expertId'),
            'createNumber' => $this->input->get('createNumber'),
            'sceneTypeId' => $this->input->get('sceneTypeId'),
            'expertId' => $this->input->get('expertId'),
            'createNumber' => $this->input->get('createNumber'),
            'keywordTypeId' => $this->input->get('keywordTypeId'),
            'keyword' => $this->input->get('keyword'),
            'departmentId' => $this->input->get('departmentId')));

        $objPHPExcel->getActiveSheet()->mergeCells('A3:A4')->setCellValue('A3', '№');
        $objPHPExcel->getActiveSheet()->mergeCells('B3:B4')->setCellValue('B3', 'Журнал №');
        $objPHPExcel->getActiveSheet()->mergeCells('C3:D3')->setCellValue('C3', 'Бүртгэсэн огноо');
        $objPHPExcel->getActiveSheet()->setCellValue('C4', 'Эхлэх');
        $objPHPExcel->getActiveSheet()->setCellValue('D4', 'Дуусах');
        $objPHPExcel->getActiveSheet()->mergeCells('E3:E4')->setCellValue('E3', 'Цагдаагийн хэлтэс');
        $objPHPExcel->getActiveSheet()->mergeCells('F3:F4')->setCellValue('F3', 'Мөрдөн байцаагч');
        $objPHPExcel->getActiveSheet()->mergeCells('G3:G4')->setCellValue('G3', 'Шинжээч');
        $objPHPExcel->getActiveSheet()->mergeCells('H3:H4')->setCellValue('H3', 'Үзлэгийн төрөл');
        $objPHPExcel->getActiveSheet()->mergeCells('I3:I4')->setCellValue('I3', 'Гэмт хэргийн төрөл');
        $objPHPExcel->getActiveSheet()->mergeCells('J3:J4')->setCellValue('J3', 'Хэргийн утга');
        $objPHPExcel->getActiveSheet()->mergeCells('K3:K4')->setCellValue('K3', 'Ул мөр илэрсэн эсэх');
        $objPHPExcel->getActiveSheet()->mergeCells('L3:N3')->setCellValue('L3', 'Гарын мөр бэхжүүлсэн арга');
        $objPHPExcel->getActiveSheet()->setCellValue('L4', 'Арга');
        $objPHPExcel->getActiveSheet()->setCellValue('M4', 'Тоо');
        $objPHPExcel->getActiveSheet()->setCellValue('M4', 'Дардас');
        $objPHPExcel->getActiveSheet()->mergeCells('O3:Q3')->setCellValue('O3', 'Гутлын мөр');
        $objPHPExcel->getActiveSheet()->setCellValue('O4', 'Арга');
        $objPHPExcel->getActiveSheet()->setCellValue('P4', 'Тоо');
        $objPHPExcel->getActiveSheet()->setCellValue('Q4', 'Мөр');
        $objPHPExcel->getActiveSheet()->mergeCells('R3:T3')->setCellValue('R3', 'Тээврийн хэрэгсэл');
        $objPHPExcel->getActiveSheet()->setCellValue('R4', 'Арга');
        $objPHPExcel->getActiveSheet()->setCellValue('S4', 'Тоо');
        $objPHPExcel->getActiveSheet()->setCellValue('T4', 'Мөр');
        $objPHPExcel->getActiveSheet()->mergeCells('U3:V3')->setCellValue('U3', 'Бусад');
        $objPHPExcel->getActiveSheet()->setCellValue('U4', 'Тоо');
        $objPHPExcel->getActiveSheet()->setCellValue('V4', 'Ул мөр');
        $objPHPExcel->getActiveSheet()->mergeCells('W3:W4')->setCellValue('W3', 'Гэрэл зураг');

        $objPHPExcel->getActiveSheet()->getStyle('A3:W4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A3:W4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('A3:W4')->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '085f35')
                    )
                )
        );

        $phpColor = new PHPExcel_Style_Color();
        $phpColor->setRGB('ffffff');

        $objPHPExcel->getActiveSheet()->getStyle('A3:W4')->getFont()->setColor($phpColor);

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
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $param->partner);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $row->scene_expert);
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $row->expert);
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $param->category);
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $param->sceneType);
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $row->scene_value);
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $row->is_trace);
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $param->fingerPrintType);
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $row->finger_print_count);
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $row->finger_print);
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $param->bootPrintType);
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, $row->boot_print_count);
                $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, $row->boot_print);
                $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, $param->transportPrintType);
                $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, $row->transport_print_count);
                $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, $row->transport_print);
                $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, $row->other_print_count);
                $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, $row->other_print);
                $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, $row->photo_count);
            }
        }


        $objPHPExcel->getActiveSheet()->getStyle('A3:W' . $i)->applyFromArray($this->styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('A1:W' . $i)->getFont()->setName('Arial');
        $objPHPExcel->getActiveSheet()->getStyle('A1:W' . $i)->getFont()->setSize(10);

        $objPHPExcel->getActiveSheet()->getStyle('A4:W' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

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
        echo json_encode($this->nifsScene->dataUpdate_model());
    }

}
