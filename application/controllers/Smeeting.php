<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Smeeting extends CI_Controller {

    public static $path = "smeeting/";

    function __construct() {
        parent::__construct();
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Systemowner_model', 'systemowner');
        $this->load->model('Smeeting_model', 'meeting');
        $this->load->model('Scontent_model', 'content');
        $this->load->model('Suser_model', 'user');
        $this->load->model('Saddress_model', 'address');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Spage_model', 'page');
        $this->load->model('Sbreadcrumb_model', 'breadcrumb');
        $this->load->model('Smodule_model', 'module');
    }

    public function index() {

        $this->urlString = '';

        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array();
            $this->body = array();
            $this->body['mode'] = 'lists';
            $this->body['modId'] = $this->uri->segment(3);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $config = array();

            if ($this->input->get('isActive') != '') {
                $this->urlString .= '&isActive=' . $this->input->get('isActive');
            }
            if ($this->input->get('catId') != 0) {
                $this->urlString .= '&catId=' . $this->input->get('catId');
            }
            if ($this->input->get('startDate') != '') {
                $this->urlString .= '&startDate=' . $this->input->get('startDate');
            }
            if ($this->input->get('endDate') != '') {
                $this->urlString .= '&endDate=' . $this->input->get('endDate');
            }
            if ($this->input->get('cityId') != '') {
                $this->urlString .= '&cityId=' . $this->input->get('cityId');
            }
            if ($this->input->get('soumId') != '') {
                $this->urlString .= '&soumId=' . $this->input->get('soumId');
            }
            if ($this->input->get('streetId') != '') {
                $this->urlString .= '&streetId=' . $this->input->get('streetId');
            }
            if ($this->input->get('createNumber') != '') {
                $this->urlString .= '&createNumber=' . $this->input->get('createNumber');
            }
            if ($this->input->get('keyword') != '') {
                $this->urlString .= '&keyword=' . $this->input->get('keyword');
            }
            if ($this->input->get('urgudulDirectId') != '') {
                $this->urlString .= '&urgudulDirectId=' . $this->input->get('urgudulDirectId');
            }

            $config["base_url"] = base_url(self::$path . "index/" . $this->body['modId'] . ($this->urlString != '' ? '?' . $this->urlString : ''));
            $config["total_rows"] = $this->meeting->listsCount_model(array(
                'isActive' => $this->input->get('isActive'),
                'catId' => $this->input->get('catId'),
                'meetingDate' => $this->input->get('meetingDate'),
                'cityId' => $this->input->get('cityId'),
                'soumId' => $this->input->get('soumId'),
                'streetId' => $this->input->get('streetId'),
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

            $this->body['dataHtml'] = $this->meeting->lists_model(array(
                'title' => $this->body['module']->title,
                'isActive' => $this->input->get('isActive'),
                'catId' => $this->input->get('catId'),
                'meetingDate' => $this->input->get('meetingDate'),
                'cityId' => $this->input->get('cityId'),
                'soumId' => $this->input->get('soumId'),
                'streetId' => $this->input->get('streetId'),
                'modId' => $this->body['modId'],
                'keyword' => $this->input->get('keyword'),
                'limit' => $config["per_page"],
                'page' => $page,
                'paginationHtml' => $this->pagination->create_links()));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/meeting/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function add() {
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/editor/ckeditor/ckeditor.js');
            $this->body = array();
            $this->body['mode'] = 'insert';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));
            $this->body['row'] = $this->meeting->addFormData_model();
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));
            $this->body['row']['emptyTabContent'] = $this->load->view('/error/systemownerEmptyTab', '', TRUE);
            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1, 'required' => true));
            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('modId' => 24, 'selectedId' => 0));
            $this->body['controlCityDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'cityId', 'parentId' => 12, 'selectedId' => 0, 'required' => true));
            $this->body['controlSoumDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'soumId', 'parentId' => 0, 'selectedId' => 0, 'required' => true));
            $this->body['controlStreetDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'streetId', 'parentId' => 0, 'selectedId' => 0, 'required' => true));
            $this->body['controlAuthorDropdown'] = $this->user->controlAuthorDropdown_model(array('selectedId' => $this->session->adminUserId, 'name' => 'authorId', 'isDisabled' => false));
            
            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/meeting/form', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/editor/ckeditor/ckeditor.js');
            $this->body = array();
            $this->body['mode'] = 'update';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));
            $this->body['row'] = $this->meeting->editFormData_model(array('id' => $this->uri->segment(4)));
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));
            
            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => $this->body['row']['cat_id'], 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlCityDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'cityId', 'parentId' => 12, 'selectedId' => $this->body['row']['city_id']));
            $this->body['controlSoumDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'soumId', 'parentId' => $this->body['row']['city_id'], 'selectedId' => $this->body['row']['soum_id']));
            $this->body['controlStreetDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'streetId', 'parentId' => $this->body['row']['soum_id'], 'selectedId' => $this->body['row']['street_id']));
            $this->body['controlAuthorDropdown'] = $this->user->controlAuthorDropdown_model(array('selectedId' => $this->session->adminUserId, 'name' => 'authorId', 'isDisabled' => false));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/meeting/form', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
            
        } else {
            redirect(MY_ADMIN);
        }
        
    }

    public function insert() {
        $getUID = getUID('meeting');
        echo json_encode($this->meeting->insert_model(array('getUID' => $getUID)));
    }

    public function update() {
        echo json_encode($this->meeting->update_model());
    }

    public function updateClose() {
        echo json_encode($this->meeting->updateClose_model());
    }

    public function delete() {
        echo json_encode($this->meeting->delete_model());
    }

    public function generateCreateNumber() {
        echo json_encode($this->meeting->generateCreateNumber_model());
    }

    public function meetingDtlList() {
        echo json_encode($this->meeting->meetingDtlList_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('contId'), 'createNumber' => $this->input->post('createNumber'), 'controller' => $this->input->post('controller'))));
    }

    public function meetingDtlDelete() {
        echo json_encode($this->meeting->meetingDtlDelete_model(array('id' => $this->input->post('id'))));
    }

    public function mediaPrint() {

        $this->body['row'] = $this->meeting->mediaGetData_model(array('id' => $this->input->post('id')));

        echo json_encode(array(
            'title' => $this->body['row']->create_number,
            'html' => $this->load->view(MY_ADMIN . '/urgudul/printMedia', $this->body, TRUE)));
    }

    public function parentDocument() {
        $this->body['row'] = '';//$this->meeting->parentDocument_model(array('id' => $this->input->post('id')));
        echo json_encode(
                array(
                    "title" => "Өргөдөл явц",
                    "width" => 800,
                    "html" => $this->load->view(MY_ADMIN . '/document/parentDocumentForm', $this->body, true),
                    "btn_ok" => "Хадгалах",
                    "btn_no" => "Хаах"));
    }

    function searchForm() {
        $this->body = array();
        $this->body['modId'] = $this->input->post('modId');
        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->input->post('modId'), 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1));
        $this->body['controlCityDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'cityId', 'parentId' => 12, 'selectedId' => 17, 'required' => false));
        $this->body['controlSoumDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'soumId', 'parentId' => 17, 'selectedId' => 0, 'required' => false));
        $this->body['controlStreetDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'streetId', 'parentId' => 0, 'selectedId' => 0, 'required' => false));
        $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('modId' => 24, 'selectedId' => 0));
        echo json_encode(array(
            'title' => 'Албан бичиг дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/document/formSearch', $this->body, TRUE),
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

        $this->data = $this->meeting->export_model(array(
            'isActive' => $this->input->post('isActive'),
            'catId' => $this->input->post('catId'),
            'generateDate' => $this->input->post('generateDate'),
            'cityId' => $this->input->post('cityId'),
            'soumId' => $this->input->post('soumId'),
            'streetId' => $this->input->post('streetId'),
            'createNumber' => $this->input->post('createNumber'),
            'modId' => $this->input->post('modId'),
            'keyword' => $this->input->post('keyword')));

        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

        if ($this->data) {

            $objPHPExcel->getActiveSheet()->mergeCells('A1:E1')->setCellValue('A1', 'УИХ-ын гишүүн Л.Элдэв-Очирт .... албан бичиг');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:E2')->setCellValue('A2', date('Y оны m сарын d'));
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);

            $objPHPExcel->getActiveSheet()->setCellValue('A3', '№');
            $objPHPExcel->getActiveSheet()->setCellValue('B3', 'Албан бичиг');
            $objPHPExcel->getActiveSheet()->setCellValue('C3', 'Төрөл');
            $objPHPExcel->getActiveSheet()->setCellValue('D3', 'Огноо');
            $objPHPExcel->getActiveSheet()->setCellValue('E3', 'Хариутай эсэх');

            $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

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
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $row->create_number . ', ' . $row->partner_title . ' №: ' . $row->document_number . ', ' . $row->description);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $row->cat_title);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, dateFormatMonth(array('date' => $row->generate_date)));
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $row->is_reply);
                
                $i++;
                $j++;
            }

            $objPHPExcel->getActiveSheet()->getStyle('A3:E' . $i)->applyFromArray($this->styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E' . $i)->getFont()->setName('Arial');
            $objPHPExcel->getActiveSheet()->getStyle('A1:E' . $i)->getFont()->setSize(10);
            $objPHPExcel->getActiveSheet()->getStyle('A4:A' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B4:B' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('C4:E' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            foreach (range('A', 'E') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false)->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(false)->setWidth(100);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(false)->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(false)->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(false)->setWidth(30);
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

    function import() {
        //  Include PHPExcel_IOFactory
        include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';

        $inputFileName = './document.xlsx';

//  Read your Excel workbook
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }

//  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

//  Loop through each row of the worksheet in turn
        for ($row = 1; $row <= $highestRow; $row++) {
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
            //  Insert row data array into your database of choice here
            echo '<pre>';
            var_dump($rowData);
            echo '</pre>';
        }
    }

}
