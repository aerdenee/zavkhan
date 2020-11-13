<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Suser extends CI_Controller {

    public static $path = "suser/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Suser_model', 'user');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('ShrPeopleDepartmentRole_model', 'hrPeopleDepartmentRole');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('Sbreadcrumb_model', 'breadcrumb');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('SuserAccessType_model', 'userAccessType');
        $this->header = $this->body = $this->footer = array();
        $this->perPage = 2;

        $this->modId = 33;
        $this->departmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->isActiveDepartment = 'is_active_control';
    }

    public function index() {

        $this->footer['jsFile'] = array('/assets/system/core/_user.js', '/assets/system/core/_permission.js');

        if ($this->session->isLogin === TRUE) {

            $this->body['auth'] = authentication(array(
                'permission' => $this->session->authentication, 
                'role' => 'isModule', 
                'moduleMenuId' => $this->uri->segment(3), 
                'createdUserId' => 0,
                'currentUserId' => $this->session->userdata['adminUserId']));
            
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['auth']->modId));

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['module']->title));

            
            $this->load->view(MY_ADMIN . '/header', $this->header);
            
            if ($this->body['auth']->permission) {
                $this->load->view(MY_ADMIN . '/user/index', $this->body);
            } else {
                $this->load->view(MY_ADMIN . '/page/deny', $this->body);
            }
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
            
        } else {
            redirect(base_url('systemowner'));
        }
    }

    public function lists() {

        if ($this->session->isLogin === TRUE) {

            $auth = authentication(array(
                'permission' => $this->session->authentication, 
                'role' => 'read', 
                'moduleMenuId' => $this->input->get('moduleMenuId'), 
                'createdUserId' => 0,
                'currentUserId' => $this->session->userdata['adminUserId']));

            //total rows count
            $totalRec = $this->user->listsCount_model(array(
                'auth' => $auth,
                'catId' => $this->input->get('catId'),
                'partnerId' => $this->input->get('partnerId'),
                'lname' => $this->input->get('lname'),
                'fname' => $this->input->get('fname'),
                'phone' => $this->input->get('phone'),
                'email' => $this->input->get('email'),
                'departmentId' => $this->input->get('departmentId')));

            $result = $this->user->lists_model(array(
                    'auth' => $auth,
                    'catId' => $this->input->get('catId'),
                    'partnerId' => $this->input->get('partnerId'),
                    'peopleId' => $this->input->get('peopleId'),
                    'lname' => $this->input->get('lname'),
                    'fname' => $this->input->get('fname'),
                    'phone' => $this->input->get('phone'),
                    'email' => $this->input->get('email'),
                    'departmentId' => $this->input->get('departmentId'),
                    'rows' => $this->input->get('rows'),
                    'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->user->addFormData_model();
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - нэмэх';

            $this->body['controlHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
                'name' => 'userDepartmentId',
                'selectedId' => 0,
                'isActiveDepartment' => $this->isActiveDepartment,
                'departmentId' => $this->departmentId));
            $this->body['controlUserAccessDropdown'] = $this->userAccessType->controlUserAccessDropdown_model(array('selectedId' => $this->body['row']->access_type_id));

            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 600,
                'html' => $this->load->view(MY_ADMIN . '/user/form', $this->body, TRUE)));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->user->editFormData_model(array('selectedId' => $this->input->post('id')));
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - засах';

            $this->body['controlHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
                'selectedId' => $this->body['row']->department_id,
                'isActiveDepartment' => $this->isActiveDepartment,
                'departmentId' => $this->departmentId,
                'readonly' => 'true'));

            $this->body['controlHrPeopleDepartmentPeopleListDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
                'departmentId' => $this->body['row']->department_id,
                'name' => 'peopleId',
                'selectedId' => $this->body['row']->people_id,
                'readonly' => 'true'));

            $this->body['controlUserAccessDropdown'] = $this->userAccessType->controlUserAccessDropdown_model(array('selectedId' => $this->body['row']->access_type_id));

            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 600,
                'html' => $this->load->view(MY_ADMIN . '/user/formEdit', $this->body, TRUE)));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function read() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->user->editFormData_model(array('selectedId' => $this->input->post('id')));
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - дэлгэрэнгүй';

            $this->page->deny_model(array('moduleMenuId' => $this->input->post('moduleMenuId'), 'mode' => 'update', 'createdUserId' => $this->body['row']->created_user_id));

            $this->body['controlHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
                'selectedId' => $this->body['row']->department_id,
                'readonly' => 'true'));

            $this->body['controlUserAccessTypeRadioButton'] = $this->userAccessType->controlUserAccessTypeRadioButton_model(array(
                'selectedId' => $this->body['row']->access_type_id,
                'readonly' => 'true'));

            $this->body['controlHrPeopleDepartmentPeopleListDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
                'departmentId' => $this->body['row']->department_id,
                'name' => 'peopleId',
                'selectedId' => $this->body['row']->people_id,
                'readonly' => 'true'));

            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 500,
                'html' => $this->load->view(MY_ADMIN . '/user/read', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->user->insert_model(array('getUID' => getUID('user'))));
    }

    public function update() {
        echo json_encode($this->user->update_model());
    }

    public function delete() {
        echo json_encode($this->user->delete_model());
    }

    public function resetPassword() {
        echo json_encode($this->user->resetPassword_model());
    }

    public function checkUserName() {
        echo json_encode($this->user->checkUserName_model(array('user' => $this->input->post('user'))));
    }

    public function checkUserEmail() {
        echo json_encode($this->user->checkUserEmail_model(array('email' => $this->input->post('email'))));
    }

    function searchForm() {

        $this->body['row'] = $this->user->addFormData_model();
        $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
        $this->body['row']->module_title = $this->module->title . ' - хайх';

        $this->body['controlUserAccessTypeRadioButton'] = $this->userAccessType->controlUserAccessTypeRadioButton_model(array('selectedId' => $this->body['row']->access_type_id));
        
        $this->body['controlHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
                'name' => 'departmentId',
                'selectedId' => 0,
                'departmentId' => $this->departmentId));

        echo json_encode(array(
            'title' => $this->body['row']->module_title,
            'html' => $this->load->view(MY_ADMIN . '/user/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    public function export() {

        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator('');
        $objPHPExcel->getProperties()->setLastModifiedBy();
        $objPHPExcel->getProperties()->setTitle('');
        $objPHPExcel->getProperties()->setSubject();
        $objPHPExcel->getProperties()->setDescription('');
        $objPHPExcel->getActiveSheet()->setTitle();

        $objPHPExcel->setActiveSheetIndex(0);

        $this->styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $this->data = $this->user->export_model(array(
            'modId' => $this->input->post('modId'),
            'catId' => $this->input->post('catId'),
            'startDate' => $this->input->post('startDate'),
            'endDate' => $this->input->post('endDate'),
            'researchTypeId' => $this->input->post('researchTypeId'),
            'crimeTypeId' => $this->input->post('crimeTypeId'),
            'crimeAgainId' => $this->input->post('crimeAgainId'),
            'crimeMotiveId' => $this->input->post('crimeMotiveId'),
            'departmentCatId' => $this->input->post('departmentCatId'),
            'departmentId' => $this->input->post('departmentId'),
            'closeTypeId' => $this->input->post('closeTypeId'),
            'latentPrintExpertId' => $this->input->post('latentPrintExpertId'),
            'expertId' => $this->input->post('expertId'),
            'createNumber' => $this->input->post('createNumber'),
            'keyword' => $this->input->post('keyword'),
            'solutionId' => $this->input->post('solutionId')));

        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

        if ($this->data) {

            $objPHPExcel->getActiveSheet()->mergeCells('A1:F1')->setCellValue('A1', '...');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:F2')->setCellValue('A2', date('Y оны m сарын d'));
            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);

            $objPHPExcel->getActiveSheet()->setCellValue('A3', '№');
            $objPHPExcel->getActiveSheet()->setCellValue('B3', 'Журнал №');
            $objPHPExcel->getActiveSheet()->setCellValue('C3', 'Огноо');
            $objPHPExcel->getActiveSheet()->setCellValue('D3', 'Томилсон');
            $objPHPExcel->getActiveSheet()->setCellValue('E3', 'Мөрдөн байцаагч');
            $objPHPExcel->getActiveSheet()->setCellValue('F3', 'Утга');
            $objPHPExcel->getActiveSheet()->setCellValue('G3', 'Обьект');
            $objPHPExcel->getActiveSheet()->setCellValue('H3', 'Шинжээчид тавьсан асуулт');
            $objPHPExcel->getActiveSheet()->setCellValue('I3', 'Шинжээч');
            $objPHPExcel->getActiveSheet()->setCellValue('J3', 'Төрөл');
            $objPHPExcel->getActiveSheet()->setCellValue('K3', 'Ачаалал');
            $objPHPExcel->getActiveSheet()->setCellValue('L3', 'Хаагдсан төрөл');
            $objPHPExcel->getActiveSheet()->setCellValue('L3', 'Хаагдсан төрөл');
            $objPHPExcel->getActiveSheet()->setCellValue('M3', 'Хаагдсан огноо');
            $objPHPExcel->getActiveSheet()->setCellValue('N3', 'Хаагдсан дүгнэлт');
            $objPHPExcel->getActiveSheet()->setCellValue('O3', 'Тайлбар');

            $objPHPExcel->getActiveSheet()->getStyle('A3:O4')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A3:O4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $i = 4;
            $j = 1;
            foreach ($this->data as $key => $row) {

                $createdDate = explode(' ', $row->created_date);
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $j);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $row->create_number);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $createdDate['0']);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $row->department_title);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $row->agent_name);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $row->value);
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $row->object . ' (' . $row->object_count . ')');
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $row->quistion);
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, mb_substr($row->expert_lname, 0, 1, 'UTF-8') . '.' . $row->expert_fname);
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $row->category_title);
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $row->weight);
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $row->weight);
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, date('Y-m-d', strtotime($row->out_date)));
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $row->close_description);
                $i++;
                $j++;
            }

            $objPHPExcel->getActiveSheet()->getStyle('A3:N' . $i)->applyFromArray($this->styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('A1:N' . $i)->getFont()->setName('Arial');
            $objPHPExcel->getActiveSheet()->getStyle('A1:N' . $i)->getFont()->setSize(10);
            $objPHPExcel->getActiveSheet()->getStyle('A4:N' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //$objPHPExcel->getActiveSheet()->getStyle('C4:F' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            foreach (range('A', 'F') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false)->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(false)->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(false)->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(false)->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(false)->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(false)->setWidth(30);
        }

        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);

        $this->fileName = 'Шинжилгээний бүртгэл - ' . date('Y-m-d-H-i-s') . '.xlsx';
        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-disposition: attachment; filename=' . $this->fileName);
        header('Cache-Control: max-age=0');
        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save('php://output');
        exit();
    }

    public function permission() {
        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->user->editFormData_model(array('selectedId' => $this->input->post('id')));

            $this->page->deny_model(array('modId' => $this->input->post('modId'), 'mode' => 'update', 'createdUserId' => $this->body['row']->created_user_id));

            echo json_encode($this->user->lists_model(array(
                        'modId' => $this->input->get('modId'),
                        'catId' => $this->input->get('catId'),
                        'partnerId' => $this->input->get('partnerId'),
                        'keyword' => $this->input->get('keyword'))));

            echo json_encode(array(
                'title' => 'Нэвтрэх эрхийн тохиргоо',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 1200,
                'html' => $this->load->view(MY_ADMIN . '/user/permission', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function formChangePassword() {

        if ($this->session->isLogin === TRUE) {

            echo json_encode(array(
                'title' => 'Нууц үг солих',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 400,
                'html' => $this->load->view(MY_ADMIN . '/user/formChangePassword', $this->body, TRUE)));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function changePassword() {
        echo json_encode($this->user->changePassword_model());
    }
    
    public function setPassword() {
        echo json_encode($this->user->setPassword_model());
    }

    public function formSetUserPassword() {
        if ($this->session->isLogin === TRUE) {

            echo json_encode(array(
                'title' => 'Нууц үг шинээр үүсгэх',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 400,
                'html' => $this->load->view(MY_ADMIN . '/user/formSetPassword', $this->body, TRUE)));
        } else {
            redirect(MY_ADMIN);
        }
    }
    
    public function dataUpdate() {
        echo json_encode($this->user->dataUpdate_model());
    }

}
