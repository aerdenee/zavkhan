<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sreport extends CI_Controller {

    public static $path = "sreport/";

    function __construct() {

        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Sreport_model', 'sreport');
        $this->load->model('SreportMenu_model', 'sreportMenu');

        $this->load->model('SnifsCrime_model', 'nifsCrime');
        $this->load->model('SnifsExtra_model', 'nifsExtra');
        $this->load->model('SnifsEconomy_model', 'nifsEconomy');
        $this->load->model('SnifsFileFolder_model', 'nifsFileFolder');
        $this->load->model('SnifsAnatomy_model', 'nifsAnatomy');
        $this->load->model('SnifsDoctorView_model', 'nifsDoctorView');
        $this->load->model('SnifsSendDocument_model', 'nifsSendDocument');
        $this->load->model('SnifsReportGeneral_model', 'nifsReportGeneral');
        $this->load->model('SnifsScene_model', 'nifsScene');

        $this->modId = 57;
        $this->header = $this->body = $this->footer = array();

        $this->nifsDepartmentId = '';
        $this->isActiveDepartment = 'is_active_nifs_crime';
        $this->reportDefaultDayInterval = 7;
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->footer['jsFile'] = array(
                '/assets/system/core/_report.js',
                '/assets/system/core/nifsCrime.js',
                '/assets/system/core/nifsExtra.js',
                '/assets/system/core/nifsEconomy.js',
                '/assets/system/core/nifsFileFolder.js',
                '/assets/system/core/nifsAnatomy.js',
                '/assets/system/core/nifsSendDocument.js',
                '/assets/system/core/nifsReportGeneral.js',
                '/assets/system/core/nifsDoctorView.js',
                '/assets/system/core/nifsScene.js',);

            $this->body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'report',
                'moduleMenuId' => $this->uri->segment(3),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['auth']->modId));

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['module']->title . ' - тайлан'));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            if ($this->body['auth']->permission) {
                $this->load->view(MY_ADMIN . '/report/index', $this->body);
            } else {
                $this->load->view(MY_ADMIN . '/page/deny', $this->body);
            }
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function home() {

        $result = array();

        if ($this->session->isLogin === TRUE) {

            $auth = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'read',
                'moduleMenuId' => $this->input->get('moduleMenuId'),
                'createdUserId' => 0,
                'currentUserId' => $this->session->userdata['adminUserId']));

            $module = $this->module->getData_model(array('id' => $auth->modId));

            $result['html'] = $this->sreport->home_model(array(
                'data' => $this->sreportMenu->getListData_model(array('modId' => $auth->modId)), 
                'module' => $module));

            //get posts data
            echo json_encode(array(
                'title' => $module->title,
                'html' => $result['html']));
        }
    }

    public function getReportItem() {

        $_reportData = $_reportTitle = $_function = '';
        $_oneDay = 86400;
        $_dayInterval = 140;
        $_reportOutDate = date('Y-m-d');

        $_reportInDate = date('Y-m-d', strtotime($this->session->userdata['adminCloseDate']))/* date('Y-m-d', (strtotime($_reportOutDate) - ($_oneDay * $_dayInterval))) */;

        $this->auth = authentication(array(
            'permission' => $this->session->authentication,
            'role' => 'read',
            'moduleMenuId' => $this->input->get('moduleMenuId'),
            'createdUserId' => 0,
            'currentUserId' => $this->session->userdata['adminUserId']));

        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->auth->modId));

        $controlHrPeopleDepartmentDropdown = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array('selectedId' => $this->nifsDepartmentId, 'departmentId' => $this->nifsDepartmentId));

        switch ($this->input->get('reportModId')) {
            case 33: { // Кримналистик шинжилгээ
                    switch ($this->input->get('reportMenuId')) {
                        case 1: {   //Ажлын мэдээ
                                $_reportData = $this->nifsCrime->getReportWorkInformationData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Ажлын мэдээ - Кримналистик шинжиилгээ';
                                $_function = '_reportNifsCrimeWorkInformation({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', reportModId: ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 2: {   //Шинжээчийн тайлан
                                $_reportData = $this->nifsCrime->getReportWeightData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Шинжээчийн ачаалал - Кримналистик шинжиилгээ';
                                $_function = '_reportNifsCrimeWeight({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', reportModId: ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 3: {   //Шинжилгээ томилсон байгууллагын тайлан
                                $_reportData = $this->nifsCrime->getReportPartnerData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Шинжилгээ томилсон байгууллага - Кримналистик шинжиилгээ';
                                $_function = '_reportNifsCrimePartner({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', reportModId: ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 5: {   //Дүгнэлтийн тайлан
                                $_reportData = $this->nifsCrime->getReportCloseUserData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Шинжилгээ томилсон байгууллага - Кримналистик шинжиилгээ';
                                $_function = '_reportNifsCrimeCloseUser({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', reportModId: ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                    }
                };
                break;
            case 34: { // Кримналистик шинжилгээ
                    switch ($this->input->get('reportMenuId')) {
                        case 41: {   //Ажлын мэдээ
                                $_reportData = $this->nifsScene->getReportWorkInformationData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Ажлын мэдээ - Хэргийн газрын үзлэг';
                                $_function = '_reportNifsSceneWorkInformation({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', reportModId: ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 42: {   //Шинжээчийн тайлан
                                $_reportData = $this->nifsScene->getReportWeightData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Шинжээчийн ачаалал - Хэргийн газрын үзлэг';
                                $_function = '_reportNifsSceneWeight({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', reportModId: ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 43: {   //Харилцагч байгууллагын тайлан
                                $_reportData = $this->nifsScene->getReportPartnerData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Харилцагч байгууллага - Хэргийн газрын үзлэг';
                                $_function = '_reportNifsScenePartner({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', reportModId: ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                    }
                };
                break;
            case 50: {  //Хавтаст хэрэг
                    switch ($this->input->get('reportMenuId')) {
                        case 19: {   //Ажлын мэдээ
                                $_reportData = $this->nifsFileFolder->getReportWorkInformationData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Ажлын мэдээ - Хавтаст хэрэг';
                                $_function = '_reportNifsFileFolderWorkInformation({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', reportModId: ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 20: {   //Шинжээчийн тайлан
                                $_reportData = $this->nifsFileFolder->getReportWeightData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Шинжээчийн ачаалал - Хавтаст хэрэг';
                                $_function = '_reportNifsFileFolderWeight({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', reportModId: ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 21: {   //Шинжилгээ томилсон байгууллагын тайлан
                                $_reportData = $this->nifsFileFolder->getReportPartnerData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Шинжилгээ томилсон байгууллага - Хавтаст хэрэг';
                                $_function = '_reportNifsFileFolderPartner({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', reportModId: ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                    }
                };
                break;
            case 51: {  //Эмчийн үзлэг
                    switch ($this->input->get('reportMenuId')) {
                        case 17: {   //Ажлын мэдээ
                                $_reportData = $this->nifsDoctorView->getReportWorkInformationData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Ажлын мэдээлэл - Эмчийн үзлэг';
                                $_function = '_reporNifsDoctorViewtWorkInformation({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 18: {   //Эмчийн ачаалал
                                $_reportData = $this->nifsDoctorView->getReportWeightData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Ачаалал - Эмчийн үзлэг';
                                $_function = '_reportNifsDoctorViewWeight({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 25: {   //Гэмтлийн зэрэг
                                $_reportData = $this->nifsDoctorView->getReportCloseTypeData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Шинжээчийн ачаалал - Эмчийн үзлэг';
                                $_function = '_reportNifsDoctorViewCloseType({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 26: {   //Шинжилгээ томилсон байгууллага
                                $_reportData = $this->nifsDoctorView->getReportPartnerData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Шинжилгээ томилсон байгууллага - Эмчийн үзлэг';
                                $_function = '_reportNifsDoctorViewPartner({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                    }
                };
                break;
            case 52: {  //Задлан шинжилгээ
                    switch ($this->input->get('reportMenuId')) {
                        case 14: {   //Ажлын мэдээ
                                $_reportData = $this->nifsAnatomy->getReportWorkInformationData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Ажлын мэдээлэл - Задлан шинжиилгээ';
                                $_function = '_reportNifsAnatomyWorkInformation({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 15: {   //Шинжээчийн тайлан
                                $_reportData = $this->nifsAnatomy->getReportWeightData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Ажлын мэдээлэл - Задлан шинжиилгээ';
                                $_function = '_reportNifsAnatomyWeight({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 16: {   //Шинжилгээ томилсон байгууллагын тайлан
                                $_reportData = $this->nifsAnatomy->getReportPartnerData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Ажлын мэдээлэл - Задлан шинжиилгээ';
                                $_function = '_reportNifsAnatomyPartner({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 24: {   //Бүртгэлийн тайлан
                                $_reportData = $this->nifsAnatomy->getReportDieRegisterData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Ажлын мэдээлэл - Задлан шинжиилгээ';
                                $_function = '_reportNifsAnatomyDieRegister({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                    }
                };
                break;
            case 55: { //Тусгай шинжилгээ
                    $this->nifsDepartmentId = 5;

                    switch ($this->input->get('reportMenuId')) {
                        case 6: {   //Ажлын мэдээ
                                $_reportData = $this->nifsExtra->getReportWorkInformationData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Ажлын мэдээлэл - Тусгай шинжилгээ';
                                $_function = '_reportNifsExtraWorkInformation({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 7: {   //Шинжээчийн тайлан
                                $_reportData = $this->nifsExtra->getReportWeightData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Ачаалал - Тусгай шинжиилгээ';
                                $_function = '_reportNifsExtraWeight({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 8: {   //Шинжилгээ томилсон байгууллагын тайлан
                                $_reportData = $this->nifsExtra->getReportPartnerData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Шинжилгээ томилсон байгууллага - Тусгай шинжиилгээ';
                                $_function = '_reportNifsExtraPartner({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 9: {   //Асуултаар
                                $_reportData = $this->nifsExtra->getReportQuestionData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Асуултаар - Тусгай шинжиилгээ';
                                $_function = '_reportNifsExtraQuestion({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                    }
                };
                break;
            case 56: {  //Эдийн засгийн шинжилгээ
                    switch ($this->input->get('reportMenuId')) {
                        case 10: {   //Ажлын мэдээ
                                $_reportData = $this->nifsEconomy->getReportWorkInformationData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Ажлын мэдээ - Эдийн засгийн шинжилгээ';
                                $_function = '_reportNifsEconomyWorkInformation({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 11: {   //Ачаалал
                                $_reportData = $this->nifsEconomy->getReportWeightData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Ачаалал - Эдийн засгийн шинжилгээ';
                                $_function = '_reportNifsEconomyWeight({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 12: {   //Ачаалал
                                $_reportData = $this->nifsEconomy->getReportPartnerData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Шинжилгээ томилсон байгууллага - Эдийн засгийн шинжиилгээ';
                                $_function = '_reportNifsEconomyPartner({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                    }
                };
                break;
            case 81: { // Илгээх бичиг
                    $nifsDepartmentId = 5;

                    switch ($this->input->get('reportMenuId')) {
                        case 38: {   //Ажлын мэдээ
                                $_reportData = $this->nifsSendDocument->getReportWorkInformationData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Ажлын мэдээ - Илгээх бичиг';
                                $_function = '_reportNifsSendDocumentWorkInformation({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                                $controlHrPeopleDepartmentDropdown = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array('selectedId' => $nifsDepartmentId, 'departmentId' => $nifsDepartmentId));
                            };
                            break;
                        case 39: {   //Шинжээчийн тайлан
                                $_reportData = $this->nifsSendDocument->getReportWeightData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Ачаалал - Илгээх бичиг';
                                $_function = '_reportNifsSendDocumentWeight({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                    }
                };
                break;
            case 82: {  //Ерөнхий тайлан
                    $this->nifsDepartmentId = 4;

                    switch ($this->input->get('reportMenuId')) {
                        case 30: {   //Нэгдсэн дүн
                                $_reportData = $this->nifsReportGeneral->getReportGeneralOldData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Ерөнхий статистик тайлан';
                                $_function = '_nifsReportGeneral({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 31: {   //Кримналистик Нэгдсэн дүн
                                $_reportData = $this->nifsReportGeneral->getReportCrimeGeneralData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Ерөнхий статистик тайлан';
                                $_function = '_nifsReportCrimeGeneral({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 32: {   //Шүүх эмнэлэг
                                $_reportData = $this->nifsReportGeneral->getReportDoctorViewGeneralData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Эмчийн үзлэг - Ерөнхий статистик тайлан';
                                $_function = '_nifsReportDoctorViewGeneral({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                                $controlHrPeopleDepartmentDropdown = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array('selectedId' => $this->nifsDepartmentId, 'departmentId' => $this->nifsDepartmentId));
                            };
                            break;
                        case 33: {   //Задлан шинжилгээ Нэгдсэн дүн
                                $_reportData = $this->nifsReportGeneral->getReportAnatomyGeneral_model(array('reportMenuId' => $this->input->get('reportMenuId'), 'reportModId' => $this->input->get('reportModId')));
                            };
                            break;
                        case 34: {   //Эмчийн үзлэг Нэгдсэн дүн
                                $_reportData = $this->nifsReportGeneral->getReportDoctorViewGeneral_model(array('reportMenuId' => $this->input->get('reportMenuId'), 'reportModId' => $this->input->get('reportModId')));
                            };
                            break;
                        case 35: {   //Задлан шинжилгээ нэгдсэн дүн
                                $_reportData = $this->nifsReportGeneral->getReportAnatomyGeneralData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Задлан шинжилгээн - Ерөнхий статистик тайлан';
                                $_function = '_nifsReportAnatomyGeneral({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                        case 36: {   //Эдийн засгийн шинжилгээ Нэгдсэн дүн
                                $_reportData = $this->nifsReportGeneral->getReportEconomyGeneralData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => 6,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Эдийн засгийн шинжилгээний ерөнхий тайлан';
                                $_function = '_nifsReportEconomyGeneral({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                                $controlHrPeopleDepartmentDropdown = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array('selectedId' => 6, 'departmentId' => $this->nifsDepartmentId));
                            };
                            break;
                        case 37: {   //Шүүхий эмнэлгийн 7 хоногийн нэгдсэн дүн мэдээ
                                $_reportData = $this->nifsReportGeneral->getReportForensicMedicineDateIntervalData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => 4,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Шүүхий эмнэлгийн шинжилгээний ерөнхий тайлан';
                                $_function = '_nifsReportForensicMedicineDateInterval({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                                $controlHrPeopleDepartmentDropdown = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array('selectedId' => 4, 'departmentId' => $this->nifsDepartmentId));
                            };
                            break;
                        case 44: {   //Нэгдсэн дүн
                                $_reportData = $this->nifsReportGeneral->getReportGeneralData_model(array(
                                    'reportIsClose' => $this->input->get('reportIsClose'),
                                    'reportMenuId' => $this->input->get('reportMenuId'),
                                    'reportModId' => $this->input->get('reportModId'),
                                    'departmentId' => $this->nifsDepartmentId,
                                    'inDate' => $_reportInDate,
                                    'outDate' => $_reportOutDate));
                                $_reportTitle = 'Ерөнхий статистик тайлан';
                                $_function = '_nifsReportGeneral({elem: this, \'reportMenuId\': ' . $this->input->get('reportMenuId') . ', \'reportModId\': ' . $this->input->get('reportModId') . '});';
                            };
                            break;
                    }
                };
                break;
            default : {
                    $_reportData = '';
                }
        }

        echo json_encode(array(
            'html' => $this->load->view(MY_ADMIN . '/report/show', array(
                'title' => $_reportTitle,
                'reportData' => $_reportData,
                'control' => $controlHrPeopleDepartmentDropdown,
                'inDate' => $_reportInDate,
                'outDate' => $_reportOutDate,
                'reportMenuId' => $this->input->get('reportMenuId'),
                'reportModId' => $this->input->get('reportModId'),
                'embedFunction' => $_function), TRUE)));
    }

}
