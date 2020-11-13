<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sstudentclass extends CI_Controller {

    public static $path = "sstudentclass/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Systemowner_model', 'systemowner');
        $this->load->model('Scontent_model', 'content');
        $this->load->model('Suser_model', 'user');
        $this->load->model('Sstudent_model', 'student');
        $this->load->model('Saddress_model', 'address');
        $this->load->model('Sdepartment_model', 'department');
        $this->load->model('Sbreadcrumb_model', 'breadcrumb');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('SawardType_model', 'awardType');
        $this->load->model('Seducation_model', 'education');
        $this->load->model('Sdegree_model', 'degree');
        $this->load->model('Scareer_model', 'career');
        $this->load->model('Sclass_model', 'class');
    }

    public function index() {

        $this->urlString = '';

        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/studentclass.js');
            $this->body = array();
            $this->body['mode'] = 'lists';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['perPage'] = ($this->input->get('per_page') != null ? $this->input->get('per_page') : 0);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/student/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function lists() {
        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->body['mode'] = 'lists';
            $this->body['modId'] = $this->input->post('modId');

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));

            if ($this->input->get('cityId') != 0) {
                $this->urlString .= '&cityId=' . $this->input->get('cityId');
            }
            if ($this->input->get('soumId') != 0) {
                $this->urlString .= '&soumId=' . $this->input->get('soumId');
            }
            if ($this->input->get('streetId') != 0) {
                $this->urlString .= '&streetId=' . $this->input->get('streetId');
            }

            if ($this->input->get('birthday') != '') {
                $this->urlString .= '&birthday=' . $this->input->get('birthday');
            }
            if ($this->input->get('createdDate') != '') {
                $this->urlString .= '&createdDate=' . $this->input->get('createdDate');
            }
            if ($this->input->get('catId') != '') {
                $this->urlString .= '&catId=' . $this->input->get('catId');
            }
            if ($this->input->get('code') != '') {
                $this->urlString .= '&code=' . $this->input->get('code');
            }
            if ($this->input->get('keyword') != '') {
                $this->urlString .= '&keyword=' . $this->input->get('keyword');
            }

            $this->paginationConfig["base_url"] = base_url(self::$path . "index/" . $this->body['modId'] . ($this->urlString != '' ? '?' . $this->urlString : ''));
            $this->paginationConfig["total_rows"] = $this->student->listsCount_model(array(
                'modId' => $this->body['modId'],
                'cityId' => $this->input->get('cityId'),
                'soumId' => $this->input->get('soumId'),
                'streetId' => $this->input->get('streetId'),
                'birthday' => $this->input->get('birthday'),
                'createdDate' => $this->input->get('createdDate'),
                'catId' => $this->input->get('catId'),
                'code' => $this->input->get('code'),
                'keyword' => $this->input->get('keyword')
            ));
            $this->page = ($this->input->post('perPage')) ? $this->input->post('perPage') : 0;
            $this->paginationConfig["per_page"] = PAGINATION_PER_PAGE;
            $this->paginationConfig["uri_segment"] = 4;

            $this->paginationConfig['full_tag_open'] = '<ul class="pagination pagination-flat pagination-xs pull-right">';
            $this->paginationConfig['full_tag_close'] = '</ul>';
            $this->paginationConfig['num_links'] = PAGINATION_NUM_LINKS;
            $this->paginationConfig['page_query_string'] = TRUE;
            $this->paginationConfig['prev_link'] = '&lt; Өмнөх';
            $this->paginationConfig['prev_tag_open'] = '<li>';
            $this->paginationConfig['prev_tag_close'] = '</li>';
            $this->paginationConfig['next_link'] = 'Дараах &gt;';
            $this->paginationConfig['next_tag_open'] = '<li>';
            $this->paginationConfig['next_tag_close'] = '</li>';
            $this->paginationConfig['cur_tag_open'] = '<li class="active"><a href="javascript:;">';
            $this->paginationConfig["cur_page"] = $this->page;
            $this->paginationConfig['cur_tag_close'] = '</a></li>';
            $this->paginationConfig['num_tag_open'] = '<li>';
            $this->paginationConfig['num_tag_close'] = '</li>';
            $this->paginationConfig['first_link'] = FALSE;
            $this->paginationConfig['last_link'] = FALSE;
            $this->pagination->initialize($this->paginationConfig);
            echo json_encode(array(
                'mode' => $this->body['mode'],
                'title' => 'Анги бүртгэлийн хуудас',
                'html' => $this->student->lists_model(array(
                    'rowCount' => $this->paginationConfig["total_rows"],
                    'path' => 'index'/* $this->body['path'] */,
                    'title' => $this->body['module']->title,
                    'modId' => $this->body['modId'],
                    'cityId' => $this->input->get('cityId'),
                    'soumId' => $this->input->get('soumId'),
                    'streetId' => $this->input->get('streetId'),
                    'birthday' => $this->input->get('birthday'),
                    'createdDate' => $this->input->get('createdDate'),
                    'catId' => $this->input->get('catId'),
                    'code' => $this->input->get('code'),
                    'keyword' => $this->input->get('keyword'),
                    'limit' => $this->paginationConfig["per_page"],
                    'page' => $this->page,
                    'paginationHtml' => $this->pagination->create_links()))));
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/student.js');
            $this->body = array();
            $this->body['mode'] = 'insert';
            $this->body['modId'] = ($this->uri->segment(3) != null ? $this->uri->segment(3) : $this->input->post('modId'));

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));
            $this->body['row'] = $this->student->addFormData_model();
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']['mod_id'], 'selectedId' => $this->body['row']['cat_id'], 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlCityDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'cityId', 'parentId' => 12, 'selectedId' => $this->body['row']['city_id'], 'disabled' => ($this->body['mode'] == 'read' ? true : false)));
            $this->body['controlSoumDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'soumId', 'parentId' => $this->body['row']['city_id'], 'selectedId' => $this->body['row']['soum_id'], 'disabled' => ($this->body['mode'] == 'read' ? true : false)));
            $this->body['controlStreetDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'streetId', 'parentId' => $this->body['row']['soum_id'], 'selectedId' => $this->body['row']['street_id'], 'disabled' => ($this->body['mode'] == 'read' ? true : false)));

            if ($this->input->is_ajax_request()) {
                echo json_encode(array(
                    'mode' => $this->body['mode'],
                    'title' => 'Анги бүртгэлийн хуудас',
                    'html' => $this->load->view(MY_ADMIN . '/student/form', $this->body, TRUE),
                    'btn_ok' => 'Хадгалах',
                    'btn_no' => 'Хаах'));
            } else {
                $this->load->view(MY_ADMIN . '/header', $this->header);
                $this->load->view(MY_ADMIN . '/student/form', $this->body);
                $this->load->view(MY_ADMIN . '/footer');
            }
        } else {

            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/student.js');
            $this->body = array();
            $this->body['mode'] = 'update';
            $this->body['modId'] = ($this->uri->segment(3) != null ? $this->uri->segment(3) : $this->input->post('modId'));
            $this->body['selectedId'] = ($this->uri->segment(4) != null ? $this->uri->segment(4) : $this->input->post('id'));

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));
            $this->body['row'] = $this->student->editFormData_model(array('id' => $this->body['selectedId']));
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']['mod_id'], 'selectedId' => $this->body['row']['cat_id'], 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlCityDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'cityId', 'parentId' => 12, 'selectedId' => $this->body['row']['city_id'], 'disabled' => ($this->body['mode'] == 'read' ? true : false)));
            $this->body['controlSoumDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'soumId', 'parentId' => $this->body['row']['city_id'], 'selectedId' => $this->body['row']['soum_id'], 'disabled' => ($this->body['mode'] == 'read' ? true : false)));
            $this->body['controlStreetDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'streetId', 'parentId' => $this->body['row']['soum_id'], 'selectedId' => $this->body['row']['street_id'], 'disabled' => ($this->body['mode'] == 'read' ? true : false)));

            if ($this->input->is_ajax_request()) {
                echo json_encode(array(
                    'mode' => $this->body['mode'],
                    'title' => 'Анги бүртгэлийн хуудас',
                    'html' => $this->load->view(MY_ADMIN . '/student/form', $this->body, TRUE),
                    'btn_ok' => 'Хадгалах',
                    'btn_no' => 'Хаах'));
            } else {
                $this->load->view(MY_ADMIN . '/header', $this->header);
                $this->load->view(MY_ADMIN . '/student/form', $this->body);
                $this->load->view(MY_ADMIN . '/footer');
            }
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        $this->getUID = getUID('student');
        $this->newFile = $this->input->post('pic');

        if ($this->newFile != '') {
            $this->uploadPath = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_USER_PATH;
            $result = imageCropGaz(array(
                'source_image' => $this->newFile,
                'new_image' => $this->newFile,
                'crop_width' => $this->input->post('crop_width'),
                'crop_height' => $this->input->post('crop_height'),
                'crop_x' => $this->input->post('crop_x'),
                'crop_y' => $this->input->post('crop_y'),
                'upload_path' => $this->uploadPath
            ));
            if ($result['status'] === 'success') {
                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_SMALL . $this->newFile,
                    'height' => SMALL_HEIGHT,
                    'width' => SMALL_WIDTH,
                    'upload_path' => $this->uploadPath
                ));

                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_MEDIUM . $this->newFile,
                    'height' => MEDIUM_HEIGHT,
                    'width' => MEDIUM_WIDTH,
                    'upload_path' => $this->uploadPath
                ));

                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_LARGE . $this->newFile,
                    'height' => LARGE_HEIGHT,
                    'width' => LARGE_WIDTH,
                    'upload_path' => $this->uploadPath
                ));
                (is_file($this->uploadPath . $result['response']) ? unlink($this->uploadPath . $result['response']) : '');
            }
        }
        echo json_encode($this->student->insert_model(array('pic' => $this->newFile, 'getUID' => $this->getUID)));
    }

    public function update() {
        $this->oldFile = $this->input->post('oldPic');
        $this->newFile = $this->input->post('pic');

        if ($this->newFile != '') {

            $this->uploadPath = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_USER_PATH;
            $result = imageCropGaz(array(
                'source_image' => $this->newFile,
                'new_image' => $this->newFile,
                'crop_width' => $this->input->post('crop_width'),
                'crop_height' => $this->input->post('crop_height'),
                'crop_x' => $this->input->post('crop_x'),
                'crop_y' => $this->input->post('crop_y'),
                'upload_path' => $this->uploadPath
            ));
            if ($result['status'] === 'success') {
                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_SMALL . $this->newFile,
                    'height' => SMALL_HEIGHT,
                    'width' => SMALL_WIDTH,
                    'upload_path' => $this->uploadPath
                ));

                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_MEDIUM . $this->newFile,
                    'height' => MEDIUM_HEIGHT,
                    'width' => MEDIUM_WIDTH,
                    'upload_path' => $this->uploadPath
                ));

                imageReSizeGaz(array(
                    'source_image' => $result['response'],
                    'new_image' => CROP_LARGE . $this->newFile,
                    'height' => LARGE_HEIGHT,
                    'width' => LARGE_WIDTH,
                    'upload_path' => $this->uploadPath
                ));

                (is_file($this->uploadPath . $result['response']) ? unlink($this->uploadPath . $result['response']) : '');

                if ($this->oldFile != '') {
                    removeImageSource(array('fieldName' => $this->oldFile, 'path' => UPLOADS_USER_PATH));
                }
            }
        } else {
            $this->newFile = $this->oldFile;
        }
        echo json_encode($this->student->update_model(array('pic' => $this->newFile)));
    }

    public function delete() {
        echo json_encode($this->student->delete_model());
    }

    public function read() {

        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/editor/ckeditor/ckeditor.js', '/assets/system/core/user.js');
            $this->body = array();
            $this->body['mode'] = 'read';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));
            $this->body['row'] = $this->student->editFormData_model(array('id' => $this->uri->segment(4)));
            $this->body['userMenuListHtml'] = $this->student->userMenuListHtml_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'userId' => $this->body['row']['id']));
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/student/form', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function uploadImage() {
        echo json_encode(uploadImageSource(array('fieldName' => $this->input->post('fieldName'), 'path' => UPLOADS_USER_PATH)));
    }

    public function removeImage() {
        echo json_encode(removeImageSource(array('fieldName' => $this->input->post('fieldName'), 'path' => UPLOADS_USER_PATH)));
    }

    function searchForm() {
        $this->body = array();
        $this->body['modId'] = $this->input->post('modId');
        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->input->post('modId'), 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1));
        $this->body['controlCityDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'cityId', 'parentId' => 12, 'selectedId' => 0, 'required' => false));
        $this->body['controlSoumDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'soumId', 'parentId' => 0, 'selectedId' => 0, 'required' => false));
        $this->body['controlStreetDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'streetId', 'parentId' => 0, 'selectedId' => 0, 'required' => false));
        echo json_encode(array(
            'title' => 'Оюутны дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/student/searchForm', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    function export() {

        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator('Оюутны бүртгэл');
        $objPHPExcel->getProperties()->setLastModifiedBy();
        $objPHPExcel->getProperties()->setTitle('Оюутны бүртгэл');
        $objPHPExcel->getProperties()->setSubject();
        $objPHPExcel->getProperties()->setDescription('Оюутны бүртгэл');
        $objPHPExcel->getActiveSheet()->setTitle();

        $objPHPExcel->setActiveSheetIndex(0);

        $this->styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $this->data = $this->student->export_model(array(
            'modId' => $this->body['modId'],
            'cityId' => $this->input->get('cityId'),
            'soumId' => $this->input->get('soumId'),
            'streetId' => $this->input->get('streetId'),
            'birthday' => $this->input->get('birthday'),
            'createdDate' => $this->input->get('createdDate'),
            'catId' => $this->input->get('catId'),
            'code' => $this->input->get('code'),
            'keyword' => $this->input->get('keyword')));

        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

        if (!$this->data) {

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

    public function listsStudentClass() {
        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->body['mode'] = 'lists';
            $this->body['modId'] = $this->input->post('modId');

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));

            if ($this->input->get('cityId') != 0) {
                $this->urlString .= '&cityId=' . $this->input->get('cityId');
            }
            if ($this->input->get('soumId') != 0) {
                $this->urlString .= '&soumId=' . $this->input->get('soumId');
            }
            if ($this->input->get('streetId') != 0) {
                $this->urlString .= '&streetId=' . $this->input->get('streetId');
            }

            if ($this->input->get('birthday') != '') {
                $this->urlString .= '&birthday=' . $this->input->get('birthday');
            }
            if ($this->input->get('createdDate') != '') {
                $this->urlString .= '&createdDate=' . $this->input->get('createdDate');
            }
            if ($this->input->get('catId') != '') {
                $this->urlString .= '&catId=' . $this->input->get('catId');
            }
            if ($this->input->get('code') != '') {
                $this->urlString .= '&code=' . $this->input->get('code');
            }
            if ($this->input->get('keyword') != '') {
                $this->urlString .= '&keyword=' . $this->input->get('keyword');
            }

            $this->paginationConfig["base_url"] = base_url(self::$path . "index/" . $this->body['modId'] . ($this->urlString != '' ? '?' . $this->urlString : ''));
            $this->paginationConfig["total_rows"] = $this->student->listsCount_model(array(
                'modId' => $this->body['modId'],
                'cityId' => $this->input->get('cityId'),
                'soumId' => $this->input->get('soumId'),
                'streetId' => $this->input->get('streetId'),
                'birthday' => $this->input->get('birthday'),
                'createdDate' => $this->input->get('createdDate'),
                'catId' => $this->input->get('catId'),
                'code' => $this->input->get('code'),
                'keyword' => $this->input->get('keyword')
            ));
            $this->page = ($this->input->post('perPage')) ? $this->input->post('perPage') : 0;
            $this->paginationConfig["per_page"] = PAGINATION_PER_PAGE;
            $this->paginationConfig["uri_segment"] = 4;

            $this->paginationConfig['full_tag_open'] = '<ul class="pagination pagination-flat pagination-xs pull-right">';
            $this->paginationConfig['full_tag_close'] = '</ul>';
            $this->paginationConfig['num_links'] = PAGINATION_NUM_LINKS;
            $this->paginationConfig['page_query_string'] = TRUE;
            $this->paginationConfig['prev_link'] = '&lt; Өмнөх';
            $this->paginationConfig['prev_tag_open'] = '<li>';
            $this->paginationConfig['prev_tag_close'] = '</li>';
            $this->paginationConfig['next_link'] = 'Дараах &gt;';
            $this->paginationConfig['next_tag_open'] = '<li>';
            $this->paginationConfig['next_tag_close'] = '</li>';
            $this->paginationConfig['cur_tag_open'] = '<li class="active"><a href="javascript:;">';
            $this->paginationConfig["cur_page"] = $this->page;
            $this->paginationConfig['cur_tag_close'] = '</a></li>';
            $this->paginationConfig['num_tag_open'] = '<li>';
            $this->paginationConfig['num_tag_close'] = '</li>';
            $this->paginationConfig['first_link'] = FALSE;
            $this->paginationConfig['last_link'] = FALSE;
            $this->pagination->initialize($this->paginationConfig);
            echo json_encode(array(
                'mode' => $this->body['mode'],
                'title' => 'Анги бүртгэлийн хуудас',
                'html' => $this->student->lists_model(array(
                    'rowCount' => $this->paginationConfig["total_rows"],
                    'path' => 'index'/* $this->body['path'] */,
                    'title' => $this->body['module']->title,
                    'modId' => $this->body['modId'],
                    'cityId' => $this->input->get('cityId'),
                    'soumId' => $this->input->get('soumId'),
                    'streetId' => $this->input->get('streetId'),
                    'birthday' => $this->input->get('birthday'),
                    'createdDate' => $this->input->get('createdDate'),
                    'catId' => $this->input->get('catId'),
                    'code' => $this->input->get('code'),
                    'keyword' => $this->input->get('keyword'),
                    'limit' => $this->paginationConfig["per_page"],
                    'page' => $this->page,
                    'paginationHtml' => $this->pagination->create_links()))));
        }
    }

}
