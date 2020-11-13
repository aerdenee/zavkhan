<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sreservation extends CI_Controller {

    public static $path = "sreservation/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Sreservation_model', 'reservation');
        $this->load->model('Spartner_model', 'partner');
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
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $config = array();
            if (count($this->input->get('campId')) > 0) {
                foreach ($this->input->get('campId') as $key => $value) {
                    $this->urlString .= '&campId[]=' . $this->input->get('campId[' . $key . ']');
                }
            }
            if ($this->input->get('keyword') != '') {
                $this->urlString .= '&keyword=' . $this->input->get('keyword');
            }

            if ($this->input->get('travelCode') != '') {
                $this->urlString .= '&travelCode=' . $this->input->get('travelCode');
            }

            if ($this->input->get('dateIn') != '' and $this->input->get('dateOut') != '') {
                $this->urlString .= '&dateIn=' . $this->input->get('dateIn') . '&dateOut=' . $this->input->get('dateOut');
            }

            $config["base_url"] = base_url(self::$path . "index/" . $this->body['modId'] . ($this->urlString != '' ? '?' . $this->urlString : ''));
            $config["total_rows"] = $this->reservation->listsCount_model(array(
                'modId' => $this->body['modId'],
                'organizationId' => $this->input->get('campId'),
                'dateIn' => $this->input->get('dateIn'),
                'dateOut' => $this->input->get('dateOut'),
                'travelCode' => $this->input->get('travelCode'),
                'keyword' => $this->input->get('keyword'),
                'partnerId' => $this->input->get('partnerId')));
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

            $this->body['dataHtml'] = $this->reservation->lists_model(array(
                'modId' => $this->body['modId'],
                'organizationId' => $this->input->get('campId'),
                'dateIn' => $this->input->get('dateIn'),
                'dateOut' => $this->input->get('dateOut'),
                'travelCode' => $this->input->get('travelCode'),
                'keyword' => $this->input->get('keyword'),
                'partnerId' => $this->input->get('partnerId'),
                'limit' => $config["per_page"],
                'page' => $page,
                'paginationHtml' => $this->pagination->create_links()));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/reservation/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
            
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function add() {
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array();
            $this->body = array();
            $this->body['mode'] = 'insert';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));
            
            $this->body['row'] = $this->reservation->addFormData_model();
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/reservation/form', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
            
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {
        if ($this->session->isLogin === TRUE) {
            $this->body = array();
            $this->body['mode'] = 'update';
            $this->body['modId'] = $this->input->post('modId');
            $this->body['path'] = '';

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $this->body['getData'] = $this->reservation->getData_model(array('selectedId' => $this->input->post('id')));
            $this->body['row'] = $this->reservation->editFormData_model(array('id' => $this->input->post('id'), 'modId' => $this->input->post('modId')));
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['getData']->created_user_id));
            
            echo json_encode(array(
                'title' => 'Захиалга засварлах хуудас',
                'html' => $this->load->view(MY_ADMIN . '/reservation/formEdit', $this->body, TRUE),
                'btn_yes' => 'Шалгах',
                'btn_no' => 'Хаах'
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->reservation->insert_model());
    }

    public function update() {
        echo json_encode($this->reservation->update_model(array()));
    }

    public function isActive() {
        echo json_encode($this->reservation->isActive_model());
    }

    public function delete() {
        echo json_encode($this->reservation->delete_model());
    }

    public function deleteReservationDtl() {
        echo json_encode($this->reservation->deleteReservationDtl_model());
    }

    public function insertReservationDtl() {
        echo json_encode($this->reservation->insertReservationDtl_model());
    }

    function formReSearch() {
        $this->body = array();
        echo json_encode(array(
            'title' => 'Сонгосон өдрүүдэд сул сууц байгаа эсэхийг шалгах',
            'html' => $this->load->view(MY_ADMIN . '/reservation/formReSearch', $this->body, TRUE),
            'btn_yes' => 'Шалгах',
            'btn_no' => 'Хаах'
        ));
    }

    function formCheckDate() {
        $this->body = array();
        $this->body['organizationId'] = 31;
        $this->body['controlOrganization'] = $this->reservation->controlRadioBtnCampList_model(array('organizationId' => $this->body['organizationId']));
        echo json_encode(array(
            'title' => 'Сонгосон өдрүүдэд сул сууц байгаа эсэхийг шалгах',
            'html' => $this->load->view(MY_ADMIN . '/reservation/formCheckDate', $this->body, TRUE),
            'btn_yes' => 'Шалгах',
            'btn_no' => 'Хаах'
        ));
    }

    function resultCheckDate() {
        echo json_encode($this->reservation->resultCheckDate_model(array(
                    'modId' => $this->input->post('modId'),
                    'organizationId' => $this->input->post('organizationId'),
                    'dateIn' => $this->input->post('dateIn'),
                    'dateOut' => $this->input->post('dateOut'),
                    'reservationId' => $this->input->post('reservationId'))));
    }

    function formCheckReservation() {
        $this->body = array();
        $this->body['controlPartnerList'] = $this->reservation->controlPartnerList_model(array('partnerId' => 0));
        echo json_encode(array(
            'title' => 'Захиалга баталгаажуулах хуудас',
            'html' => $this->load->view(MY_ADMIN . '/reservation/formCheckReservation', $this->body, TRUE),
            'btn_yes' => 'Шалгах',
            'btn_no' => 'Хаах'
        ));
    }

    function getPartnerInformation() {
        echo json_encode($this->reservation->getPartnerInformation_model(array('partnerId' => $this->input->post('partnerId'))));
    }

    function export() {

        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator('Мөнхтэнгэр сүлжээ жуулчны бааз');
        $objPHPExcel->getProperties()->setLastModifiedBy();
        $objPHPExcel->getProperties()->setTitle('Мөнхтэнгэр сүлжээ жуулчны бааз');
        $objPHPExcel->getProperties()->setSubject();
        $objPHPExcel->getProperties()->setDescription('Мөнхтэнгэр сүлжээ жуулчны бааз захиалгын систем');
        $objPHPExcel->getActiveSheet()->setTitle('Захиалга');

        $objPHPExcel->setActiveSheetIndex(0);

        $this->styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $this->data = $this->reservation->export_model(array(
            'modId' => $this->input->post('modId'),
            'organizationId' => $this->input->post('campId'),
            'dateIn' => $this->input->post('dateIn'),
            'dateOut' => $this->input->post('dateOut'),
            'travelCode' => $this->input->post('travelCode'),
            'keyword' => $this->input->post('keyword'),
            'partnerId' => $this->input->post('partnerId')));

        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

        $objPHPExcel->getActiveSheet()->mergeCells('A1:O1')->setCellValue('A1', 'Мөнх Тэнгэр баазын 2016 оны 5, 6, 7, 8, 9, 10-р саруудад гэрээт компаниудын жуулчид амрагчдыг хүлээн авч үйлчлэх захиалгын бүртгэл');
        $objPHPExcel->getActiveSheet()->getStyle('A1:O3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1:O3')->getFont()->setBold(true);

        $objPHPExcel->getActiveSheet()->mergeCells('A2:B2')->setCellValue('A2', 'Сар, өдөр');
        $objPHPExcel->getActiveSheet()->mergeCells('C2:D2')->setCellValue('C2', 'Ор, хоног');
        $objPHPExcel->getActiveSheet()->mergeCells('E2:E3')->setCellValue('E2', 'Ор, хоног');
        $objPHPExcel->getActiveSheet()->mergeCells('F2:G2')->setCellValue('F2', 'Өглөөний цай');
        $objPHPExcel->getActiveSheet()->mergeCells('H2:I2')->setCellValue('H2', 'Өдрийн хоол');
        $objPHPExcel->getActiveSheet()->mergeCells('J2:K2')->setCellValue('J2', 'Оройн хоол');
        $objPHPExcel->getActiveSheet()->mergeCells('L2:L3')->setCellValue('L2', 'Тайлбар');
        $objPHPExcel->getActiveSheet()->mergeCells('M2:N2')->setCellValue('M2', 'Захиалагч компани');
        $objPHPExcel->getActiveSheet()->mergeCells('O2:O3')->setCellValue('O2', 'Баталсан');

        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Ирэх');
        $objPHPExcel->getActiveSheet()->setCellValue('B3', 'Буцах');
        $objPHPExcel->getActiveSheet()->setCellValue('C3', 'Жуулчин');
        $objPHPExcel->getActiveSheet()->setCellValue('D3', 'Үйлч.аж');
        $objPHPExcel->getActiveSheet()->setCellValue('F3', 'Жуулчин');
        $objPHPExcel->getActiveSheet()->setCellValue('G3', 'Үйлч.аж');
        $objPHPExcel->getActiveSheet()->setCellValue('H3', 'Жуулчин');
        $objPHPExcel->getActiveSheet()->setCellValue('I3', 'Үйлч.аж');
        $objPHPExcel->getActiveSheet()->setCellValue('J3', 'Жуулчин');
        $objPHPExcel->getActiveSheet()->setCellValue('K3', 'Үйлч.аж');
        $objPHPExcel->getActiveSheet()->setCellValue('M3', 'Код');
        $objPHPExcel->getActiveSheet()->setCellValue('N3', 'Холбоо барих');

        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("N")->setAutoSize(true);

        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);

        if ($this->data) {
            $i = 4;
            foreach ($this->data as $key => $value) {
                $row = (array) $value;
                $dateIn = explode(' ', $row['date_in']);
                $dateOut = explode(' ', $row['date_out']);
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $dateIn['0']);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $dateOut['0']);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $row['adult']);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $row['staff']);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $row['bed_night']);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $row['breakfast_adult']);
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $row['breakfast_staff']);
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $row['lunch_adult']);
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $row['lunch_staff']);
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $row['dinner_adult']);
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $row['dinner_staff']);
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $row['description']);
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $row['travel_code']);
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $row['partner_title'] . ', ' . $row['partner_manager_name'] . ' - ' . $row['partner_manager_phone']);
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $row['status']);

                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':O' . $i)->getFill()->applyFromArray(array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => array(
                        'rgb' => ltrim($row['color'], "#")
                    )
                ));
                $i++;
            }
            $objPHPExcel->getActiveSheet()->getStyle('A1:O' . $i)->applyFromArray($this->styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('A1:O' . $i)->getFont()->setName('Arial');
            $objPHPExcel->getActiveSheet()->getStyle('A1:O' . $i)->getFont()->setSize(10);
            $objPHPExcel->getActiveSheet()->getStyle('C4:K' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('L4:O' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

            foreach (range('A', 'O') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(false)->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(false)->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(false)->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(false)->setWidth(30);
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Хоосон байна');
        }

        $this->fileName = 'munkhtenger-' . date('Y-m-d-H-i-s') . '.xlsx';
        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-disposition: attachment; filename=' . $this->fileName);
        header('Cache-Control: max-age=0');
        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save('php://output');
        exit();
    }

    function searchForm() {
        $this->body = array();
        $this->body['modId'] = $this->input->post('modId');
        $this->body['controlOrganization'] = $this->reservation->controlCheckBoxCampList_model(array('organizationId' => 0));
        $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('selectedId' => 0));
        echo json_encode(array(
            'title' => 'Захиалгын жагсаалтаас хайлт хийх',
            'html' => $this->load->view(MY_ADMIN . '/reservation/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Хаах'
        ));
    }

    public function viewReservation() {
        $this->body['row'] = $this->reservation->viewReservation_model(array('id' => $this->input->post('id')));
        if ($this->body['row']) {
            echo json_encode(array(
                'title' => 'Захиалгын хуудас',
                'html' => $this->load->view(MY_ADMIN . '/reservation/viewReservation', $this->body, TRUE)
            ));
        } else {
            echo json_encode(array(
            'title' => 'Захиалгын хуудас',
            'html' => FALSE
        ));
        }
    }

}
