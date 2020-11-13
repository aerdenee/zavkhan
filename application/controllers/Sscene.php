<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sscene extends CI_Controller {

    public static $path = "sscene/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Systemowner_model', 'systemowner');
        $this->load->model('Sscene_model', 'scene');
        $this->load->model('Scontent_model', 'content');
        $this->load->model('Suser_model', 'user');
        $this->load->model('Saddress_model', 'address');
        $this->load->model('Surguduldirect_model', 'urguduldirect');
        $this->load->model('Sbreadcrumb_model', 'breadcrumb');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Sdepartment_model', 'department');
        $this->load->model('Scrime_model', 'crime');
    }

    public function index() {

        $this->urlString = '';

        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array();
            $this->body = array();
            $this->body['mode'] = 'lists';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $config = array();

            //http://crime.local/sscene/index/34?catId=306&startDate=2017-07-02&endDate=2017-07-05&departmentId=6&expertId=9&crimeTypeId=1&keyword=%D0%B9
            if ($this->input->get('catId') != 0) {
                $this->urlString .= '&catId=' . $this->input->get('catId');
            }
            if ($this->input->get('startDate') != '') {
                $this->urlString .= '&startDate=' . $this->input->get('startDate');
            }
            if ($this->input->get('endDate') != '') {
                $this->urlString .= '&endDate=' . $this->input->get('endDate');
            }
            if ($this->input->get('departmentId') != '') {
                $this->urlString .= '&departmentId=' . $this->input->get('departmentId');
            }
            if ($this->input->get('expertId') != '') {
                $this->urlString .= '&expertId=' . $this->input->get('expertId');
            }
            if ($this->input->get('crimeTypeId') != '') {
                $this->urlString .= '&crimeTypeId=' . $this->input->get('crimeTypeId');
            }
            if ($this->input->get('keyword') != '') {
                $this->urlString .= '&keyword=' . $this->input->get('keyword');
            }

            $config["base_url"] = base_url(self::$path . "index/" . $this->body['modId'] . ($this->urlString != '' ? '?' . $this->urlString : ''));
            $config["total_rows"] = $this->scene->listsCount_model(array(
                'catId' => $this->input->get('catId'),
                'sceneDateIn' => $this->input->get('startDate'),
                'sceneDateOut' => $this->input->get('endDate'),
                'departmentId' => $this->input->get('departmentId'),
                'expertId' => $this->input->get('expertId'),
                'crimeTypeId' => $this->input->get('crimeTypeId'),
                'modId' => $this->body['modId'],
                'keyword' => $this->input->get('keyword')));

            $page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;
            $config["per_page"] = PAGINATION_PER_PAGE;
            $config["uri_segment"] = 4;

            $config['full_tag_open'] = '<ul class="pagination pagination-flat pagination-xs pull-right">';
            $config['full_tag_close'] = '</ul>';
            $config['num_links'] = PAGINATION_NUM_LINKS;
            $config['page_query_string'] = TRUE;
            $config['prev_link'] = '&lt; Өмнөх';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['next_link'] = 'Дараах &gt;';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="javascript:;">';
            $config["cur_page"] = $page;
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['first_link'] = FALSE;
            $config['last_link'] = FALSE;

            $this->pagination->initialize($config);

            $this->body['dataHtml'] = $this->scene->lists_model(array(
                'title' => $this->body['module']->title,
                'path' => $this->body['path'],
                'catId' => $this->input->get('catId'),
                'sceneDateIn' => $this->input->get('startDate'),
                'sceneDateOut' => $this->input->get('endDate'),
                'departmentId' => $this->input->get('departmentId'),
                'expertId' => $this->input->get('expertId'),
                'crimeTypeId' => $this->input->get('crimeTypeId'),
                'modId' => $this->body['modId'],
                'keyword' => $this->input->get('keyword'),
                'limit' => $config["per_page"],
                'page' => $page,
                'paginationHtml' => $this->pagination->create_links()));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/scene/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
            
        } else {
            redirect(MY_ADMIN);
        }
        
    }

    public function add() {
        
        if ($this->session->isLogin === TRUE) {
            
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array();
            $this->header['breadcrumb'] = '';
            $this->body = array();
            $this->body['mode'] = 'insert';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));
            
            $this->body['row'] = $this->scene->addFormData_model();
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));
            
            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1, 'required' => true));
            $this->body['controlDepartmentDropdown'] = $this->department->controlDepartmentDropdown_model(array('catId' => $this->session->userdata('adminDepartmentCatId'), 'selectedId' => 0));
            $this->body['controlExpertDropDown'] = $this->crime->controlLatentPrintDropDown_model(array('selectedId' => 0, 'name' => 'expertId', 'extraIsCrimeScene' => 2, 'extraIsCrimeScene' => '2'));
            $this->body['controlCrimeTypeDropdown'] = $this->crime->controlCrimeTypeDropdown_model(array('selectedId' => 0));
            
            
            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/scene/form', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
            
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {
        
        if ($this->session->isLogin === TRUE) {
            
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array();
            $this->body = array();
            $this->body['mode'] = 'update';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));
            
            $this->body['row'] = $this->scene->editFormData_model(array('id' => $this->uri->segment(4)));
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));
            
            
            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => $this->body['row']['cat_id'], 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlDepartmentDropdown'] = $this->department->controlDepartmentDropdown_model(array('catId' => $this->session->userdata('adminDepartmentCatId'), 'selectedId' => $this->body['row']['department_id']));
            $this->body['controlExpertDropDown'] = $this->crime->controlLatentPrintDropDown_model(array('selectedId' => $this->body['row']['expert_id'], 'name' => 'expertId', 'extraIsCrimeScene' => 2, 'extraIsCrimeScene' => '2'));
            $this->body['controlCrimeTypeDropdown'] = $this->crime->controlCrimeTypeDropdown_model(array('selectedId' => $this->body['row']['crime_type_id']));
            
            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/scene/form', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
            
        } else {
            redirect(MY_ADMIN);
        }
        
    }


    public function insert() {
        $getUID = getUID('scene');
        echo json_encode($this->scene->insert_model(array('getUID' => $getUID)));
    }

    public function update() {
        echo json_encode($this->scene->update_model());
    }

    public function delete() {
        echo json_encode($this->scene->delete_model());
    }

    function searchForm() {
        $this->body = array();
        $this->body['modId'] = $this->input->post('modId');
        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->input->post('modId'), 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1));
        $this->body['controlCrimeTypeDropdown'] = $this->crime->controlCrimeTypeDropdown_model(array('selectedId' => 0));
        $this->body['controlDepartmentDropdown'] = $this->department->controlDepartmentDropdown_model(array('catId' => $this->session->userdata('adminDepartmentCatId'), 'selectedId' => 0));
        $this->body['controlExpertDropDown'] = $this->crime->controlLatentPrintDropDown_model(array('selectedId' => 0, 'name' => 'expertId', 'extraIsCrimeScene' => 2, 'extraIsCrimeScene' => '2'));
        echo json_encode(array(
            'title' => 'Хэргийн газрын үзлэг - дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/scene/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    function export() {

        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator('УИХ-ын гишүүн Л.Элдэв-Очирын ажлын алба');
        $objPHPExcel->getProperties()->setLastModifiedBy();
        $objPHPExcel->getProperties()->setTitle('УИХ-ын гишүүн Л.Элдэв-Очирт иргэдээс ирсэн өргөдөл');
        $objPHPExcel->getProperties()->setSubject();
        $objPHPExcel->getProperties()->setDescription('УИХ-ын гишүүн Л.Элдэв-Очирын ажлын алба');
        $objPHPExcel->getActiveSheet()->setTitle();

        $objPHPExcel->setActiveSheetIndex(0);

        $this->styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $this->data = $this->scene->export_model(array(
            'isActive' => $this->input->post('isActive'),
            'catId' => $this->input->post('catId'),
            'startDate' => $this->input->post('startDate'),
            'endDate' => $this->input->post('endDate'),
            'cityId' => $this->input->post('cityId'),
            'soumId' => $this->input->post('soumId'),
            'streetId' => $this->input->post('streetId'),
            'createNumber' => $this->input->post('createNumber'),
            'modId' => $this->input->post('modId'),
            'keyword' => $this->input->post('keyword')));

        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

        if ($this->data) {

            $objPHPExcel->getActiveSheet()->mergeCells('A1:F1')->setCellValue('A1', 'УИХ-ын гишүүн Л.Элдэв-Очирт .... ирсэн өргөдөл');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:F2')->setCellValue('A2', date('Y оны m сарын d'));
            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);

            $objPHPExcel->getActiveSheet()->mergeCells('A3:A4')->setCellValue('A3', '№');
            $objPHPExcel->getActiveSheet()->mergeCells('B3:D3')->setCellValue('B3', 'Өргөдөл гаргагч');
            $objPHPExcel->getActiveSheet()->setCellValue('B4', 'Огноо');
            $objPHPExcel->getActiveSheet()->setCellValue('C4', 'Овог, нэр');
            $objPHPExcel->getActiveSheet()->setCellValue('D4', 'Хаяг');
            $objPHPExcel->getActiveSheet()->mergeCells('E3:E4')->setCellValue('E3', 'Товч агуулга');
            $objPHPExcel->getActiveSheet()->mergeCells('F3:F4')->setCellValue('F3', 'Тайлбар');

            $objPHPExcel->getActiveSheet()->getStyle('A3:F4')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A3:F4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B4:D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $i = 5;
            $j = 1;
            foreach ($this->data as $key => $row) {

                $this->location = '';
                //' . $row->city_id . ', ' . $row->city_title . '
                if ($row->city_title != '') {
                    $this->location .= $row->city_title . ', ';
                }
                if ($row->soum_title != '') {
                    $this->location .= $row->soum_title . ', ';
                }
                if ($row->street_title != '') {
                    $this->location .= $row->street_title . ', ';
                }

                $createdDate = explode(' ', $row->created_date);
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $j);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $createdDate['0']);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, mb_substr($row->lname, 0, 1, 'UTF-8') . '.' . $row->fname);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $this->location . $row->address . ', ' . $row->contact);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $row->description);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $row->close_description);

                $i++;
                $j++;
            }

            $objPHPExcel->getActiveSheet()->getStyle('A3:F' . $i)->applyFromArray($this->styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('A1:F' . $i)->getFont()->setName('Arial');
            $objPHPExcel->getActiveSheet()->getStyle('A1:F' . $i)->getFont()->setSize(10);
            $objPHPExcel->getActiveSheet()->getStyle('A4:B' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('C4:F' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

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

        $this->fileName = 'УИХ-ын гишүүн Л.Элдэв-Очирын ажлын алба - ' . date('Y-m-d-H-i-s') . '.xlsx';
        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-disposition: attachment; filename=' . $this->fileName);
        header('Cache-Control: max-age=0');
        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save('php://output');
        exit();
    }

}