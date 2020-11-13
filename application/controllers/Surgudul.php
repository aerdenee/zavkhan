<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Surgudul extends CI_Controller {

    public static $path = "surgudul/";
    public static $modId = 16;

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Systemowner_model', 'systemowner');
        $this->load->model('Surgudul_model', 'urgudul');
        $this->load->model('Scontent_model', 'content');
        $this->load->model('Suser_model', 'user');
        $this->load->model('Saddress_model', 'address');
        $this->load->model('Surguduldirect_model', 'urguduldirect');
        $this->load->model('Sbreadcrumb_model', 'breadcrumb');
        $this->load->model('Smodule_model', 'module');
    }

    public function index() {

        $this->urlString = '';

        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/urgudul.js');
            $this->body = array();
            $this->body['mode'] = 'lists';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

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
            $config["total_rows"] = $this->urgudul->listsCount_model(array(
                'isActive' => $this->input->get('isActive'),
                'catId' => $this->input->get('catId'),
                'startDate' => $this->input->get('startDate'),
                'endDate' => $this->input->get('endDate'),
                'cityId' => $this->input->get('cityId'),
                'soumId' => $this->input->get('soumId'),
                'streetId' => $this->input->get('streetId'),
                'createNumber' => $this->input->get('createNumber'),
                'modId' => $this->body['modId'],
                'keyword' => $this->input->get('keyword'),
                'urgudulDirectId' => $this->input->get('urgudulDirectId')));

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

            $this->body['dataHtml'] = $this->urgudul->lists_model(array(
                'rowCount' => $config["total_rows"],
                'path' => $this->body['path'],
                'title' => $this->body['module']->title,
                'isActive' => $this->input->get('isActive'),
                'catId' => $this->input->get('catId'),
                'startDate' => $this->input->get('startDate'),
                'endDate' => $this->input->get('endDate'),
                'cityId' => $this->input->get('cityId'),
                'soumId' => $this->input->get('soumId'),
                'streetId' => $this->input->get('streetId'),
                'createNumber' => $this->input->get('createNumber'),
                'modId' => $this->body['modId'],
                'keyword' => $this->input->get('keyword'),
                'urgudulDirectId' => $this->input->get('urgudulDirectId'),
                'limit' => $config["per_page"],
                'page' => $page,
                'paginationHtml' => $this->pagination->create_links()));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/urgudul/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/urgudul.js');
            $this->body = array();
            $this->body['mode'] = 'insert';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $this->body['row'] = $this->urgudul->addFormData_model();
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));

            $this->body['row']['emptyTabContent'] = $this->load->view('/error/systemownerEmptyTab', '', TRUE);
            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1, 'required' => true));
            $this->body['controlCityDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'cityId', 'parentId' => 12, 'selectedId' => 17, 'required' => true));
            $this->body['controlSoumDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'soumId', 'parentId' => 17, 'selectedId' => 257, 'required' => true));
            $this->body['controlStreetDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'streetId', 'parentId' => 0, 'selectedId' => 0, 'required' => true));
            $this->body['controlAuthorDropdown'] = $this->user->controlAuthorDropdown_model(array('selectedId' => $this->session->adminUserId, 'name' => 'authorId', 'isDisabled' => false));


            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/urgudul/form', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/urgudul.js');
            $this->body = array();
            $this->body['mode'] = 'update';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $this->body['row'] = $this->urgudul->editFormData_model(array('id' => $this->uri->segment(4)));
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));


            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => $this->body['row']['cat_id'], 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlCityDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'cityId', 'parentId' => 12, 'selectedId' => $this->body['row']['city_id']));
            $this->body['controlSoumDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'soumId', 'parentId' => $this->body['row']['city_id'], 'selectedId' => $this->body['row']['soum_id']));
            $this->body['controlStreetDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'streetId', 'parentId' => $this->body['row']['soum_id'], 'selectedId' => $this->body['row']['street_id']));
            $this->body['controlAuthorDropdown'] = $this->user->controlAuthorDropdown_model(array('selectedId' => $this->session->adminUserId, 'name' => 'authorId', 'isDisabled' => false));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/urgudul/form', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function close() {

        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/urgudul.js');
            $this->body = array();
            $this->body['mode'] = 'updateClose';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $this->body['row'] = $this->urgudul->editFormData_model(array('id' => $this->uri->segment(4)));
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));

            $this->body['category'] = $this->category->getData_model(array('selectedId' => $this->body['row']['cat_id']));
            $this->body['city'] = $this->address->getData_model(array('id' => $this->body['row']['city_id']));
            $this->body['soum'] = $this->address->getData_model(array('id' => $this->body['row']['soum_id']));
            $this->body['street'] = $this->address->getData_model(array('id' => $this->body['row']['street_id']));
            $this->body['controlUrgudulDirectDropDown'] = $this->urguduldirect->controlUrgudulDirectDropDown_model(array('selectedId' => $this->body['row']['urgudul_direct_id']));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/urgudul/close', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function track() {

        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/urgudul.js');

            $this->body = array();
            $this->body['mode'] = 'updateClose';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $this->body['row'] = $this->urgudul->editFormData_model(array('id' => $this->uri->segment(4)));
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));

            $this->body['category'] = $this->category->getData_model(array('selectedId' => $this->body['row']['cat_id']));
            $this->body['city'] = $this->address->getData_model(array('id' => $this->body['row']['city_id']));
            $this->body['soum'] = $this->address->getData_model(array('id' => $this->body['row']['soum_id']));
            $this->body['street'] = $this->address->getData_model(array('id' => $this->body['row']['street_id']));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/urgudul/track', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function read() {

        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/urgudul.js');
            $this->body = array();
            $this->body['mode'] = 'read';
            $this->body['modId'] = $this->uri->segment(3);
            $this->body['path'] = $this->uri->segment(2);

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $this->body['row'] = $this->urgudul->editFormData_model(array('id' => $this->uri->segment(4)));
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));

            $this->body['category'] = $this->category->getData_model(array('selectedId' => $this->body['row']['cat_id']));
            $this->body['city'] = $this->address->getData_model(array('id' => $this->body['row']['city_id']));
            $this->body['soum'] = $this->address->getData_model(array('id' => $this->body['row']['soum_id']));
            $this->body['street'] = $this->address->getData_model(array('id' => $this->body['row']['street_id']));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/urgudul/read', $this->body);
            $this->load->view(MY_ADMIN . '/footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function formMedia() {

        $data['contId'] = $this->input->post('contId');
        $data['modId'] = $this->input->post('modId');
        $data['mediaId'] = $this->input->post('mediaId');
        $data['row'] = $this->content->formMediaData_model($data['mediaId']);
        echo json_encode(
                array(
                    "title" => "Фото зураг",
                    "html" => $this->load->view(MY_ADMIN . '/content/formMedia', $data, true),
                    "btn_ok" => "Хадгалах",
                    "btn_no" => "Хаах"));
    }

    public function insert() {
        $getUID = getUID('urgudul');
        echo json_encode($this->urgudul->insert_model(array('getUID' => $getUID)));
    }

    public function update() {
        echo json_encode($this->urgudul->update_model());
    }

    public function updateClose() {
        echo json_encode($this->urgudul->updateClose_model());
    }

    public function delete() {
        echo json_encode($this->urgudul->delete_model());
    }

    public function printPage() {
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array();
            $this->body = array();
            $this->body['mode'] = 'printPage';
            $this->body['modId'] = self::$modId;
            $this->body['row'] = $this->urgudul->editFormData_model(array('id' => $this->input->post('id')));
            $this->body['getDataMediaList'] = $this->urgudul->getDataMediaList_model(array('contId' => $this->input->post('id')));
//$this->page->deny_model(array('modId' => self::$modId, 'mode' => 'lists', 'createdUserId' => $this->body['row']['created_user_id']));

            echo json_encode(array(
                'title' => $this->body['row']['create_number'],
                'html' => $this->load->view(MY_ADMIN . '/urgudul/printPage', $this->body, TRUE)));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function printBlank() {
        if ($this->session->isLogin === TRUE) {
            $this->body = array();
            $this->body['mode'] = 'printBlank';
            $this->body['modId'] = self::$modId;
            $this->body['row'] = $this->urgudul->editFormData_model(array('id' => $this->input->post('id')));
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));

            $this->body['category'] = $this->category->getData_model(array('selectedId' => $this->body['row']['cat_id']));
            $this->body['city'] = $this->address->getData_model(array('id' => $this->body['row']['city_id']));
            $this->body['soum'] = $this->address->getData_model(array('id' => $this->body['row']['soum_id']));
            $this->body['street'] = $this->address->getData_model(array('id' => $this->body['row']['street_id']));

            echo json_encode(array(
                'title' => $this->body['row']['create_number'],
                'html' => $this->load->view(MY_ADMIN . '/urgudul/printBlank', $this->body, TRUE)));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function generateCreateNumber() {
        echo json_encode($this->urgudul->generateCreateNumber_model());
    }

    public function mediaInsert() {

        $this->uploadPath = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_URGUDUL_PATH . $this->input->post('createNumber') . '/';

        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0777, TRUE);
        }

        write_file($this->uploadPath . 'index.html', '<script type="text/javascript">window.location.href = "' . base_url() . '";</script>');

        $this->image = uploadImageSource(array('fieldName' => $this->input->post('fieldName'), 'path' => UPLOADS_URGUDUL_PATH . $this->input->post('createNumber') . '/'));
        if ($this->image['status'] == 'success') {

            imageReSizeGaz(array(
                'source_image' => $this->image['response'],
                'new_image' => CROP_SMALL . $this->image['response'],
                'height' => SMALL_HEIGHT,
                'width' => SMALL_WIDTH,
                'upload_path' => $this->uploadPath
            ));
            (is_file($this->uploadPath . $this->image['response']) ? unlink($this->uploadPath . $this->image['response']) : '');
            echo json_encode($this->urgudul->mediaInsert_model(array('pic' => $this->image['response'], 'contId' => $this->input->post('contId'))));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Алдаа гарлаа'));
        }
    }

    public function mediaList() {
        echo json_encode($this->urgudul->mediaList_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('contId'), 'createNumber' => $this->input->post('createNumber'), 'controller' => $this->input->post('controller'))));
    }

    public function mediaDelete() {
        echo json_encode($this->urgudul->mediaDelete_model(array('id' => $this->input->post('id'))));
    }

    public function mediaPrint() {

        $this->body['row'] = $this->urgudul->mediaGetData_model(array('id' => $this->input->post('id')));

        echo json_encode(array(
            'title' => $this->body['row']->create_number,
            'html' => $this->load->view(MY_ADMIN . '/urgudul/printMedia', $this->body, TRUE)));
    }

    public function urgudulTrackList() {
        echo json_encode($this->urgudul->urgudulTrackList_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('contId'), 'controller' => $this->input->post('controller'))));
    }

    public function urgudulTrackInsert() {
        echo json_encode($this->urgudul->urgudulTrackInsert_model());
    }

    public function urgudulTrackDelete() {
        echo json_encode($this->urgudul->urgudulTrackDelete_model());
    }

    public function urgudulTrackUpdate() {
        echo json_encode($this->urgudul->urgudulTrackUpdate_model());
    }

    public function urgudulTrackForm() {

        $this->body['row'] = $this->urgudul->urgudulTrackGetData_model(array('id' => $this->input->post('id')));
        echo json_encode(
                array(
                    "title" => "Өргөдөл явц",
                    "html" => $this->load->view(MY_ADMIN . '/urgudul/urgudulTrackForm', $this->body, true),
                    "btn_ok" => "Хадгалах",
                    "btn_no" => "Хаах"));
    }

    function searchForm() {
        $this->body = array();
        $this->body['modId'] = $this->input->post('modId');
        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->input->post('modId'), 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1));
        $this->body['controlCityDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'cityId', 'parentId' => 12, 'selectedId' => 0, 'required' => false));
        $this->body['controlSoumDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'soumId', 'parentId' => 0, 'selectedId' => 0, 'required' => false));
        $this->body['controlStreetDropdown'] = $this->address->controlAddressDropDown_model(array('name' => 'streetId', 'parentId' => 0, 'selectedId' => 0, 'required' => false));
        $this->body['controlUrgudulDirectDropDown'] = $this->urguduldirect->controlUrgudulDirectDropDown_model(array('selectedId' => 0));
        echo json_encode(array(
            'title' => 'Өргөдлийн дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/urgudul/formSearch', $this->body, TRUE),
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

        $this->data = $this->urgudul->export_model(array(
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

        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

//  Loop through each row of the worksheet in turn

        for ($row = 1; $row <= $highestRow; $row++) {

            if ($row >= 5 and $sheet->getCellByColumnAndRow(9, $row)->getValue() != null) {
                $this->paramData = array(
                    'id' => getUID('urgudul'),
                    'generateDate' => date('Y-m-d'),
                    'lname' => '',
                    'fname' => '',
                    'address' => '',
                    'contact' => '',
                    'catId' => '',
                    'modId' => '',
                    'description' => '',
                    'page' => '',
                    'createdDate' => '',
                    'modifiedDate' => '',
                    'createNumber' => '',
                    'createdUserId' => '',
                    'modifiedUserId' => '',
                    'isActive' => '',
                    'cityId' => '',
                    'soumId' => '',
                    'streetId' => '',
                    'orderNum' => '',
                    'closeDescription' => '',
                    'closeAuthor' => '',
                    'closeDate' => '',
                    'closeUserId' => '',
                    'urgudulDirectId' => ''
                );
                
                $this->paramData['generateDate'] = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($sheet->getCellByColumnAndRow(1, $row)->getValue()));
                $tempName = explode('.', $sheet->getCellByColumnAndRow(2, $row)->getValue());
                if (count($tempName) > 0) {
                    $this->paramData['lname'] = $tempName['0'];
                    $this->paramData['fname'] = ($tempName['1'] != null ? $tempName['1'] : '');
                }
                
                $this->paramData['address'] = ($sheet->getCellByColumnAndRow(6, $row)->getValue() != null ? $sheet->getCellByColumnAndRow(6, $row)->getValue() : '');
                $this->paramData['contact'] = ($sheet->getCellByColumnAndRow(7, $row)->getValue() != null ? $sheet->getCellByColumnAndRow(7, $row)->getValue() : '');
                $catValue = mb_strtoupper($sheet->getCellByColumnAndRow(8, $row)->getValue(), 'UTF-8');
                $catId = 0;
                if ($catValue == mb_strtoupper('Эмчилгээ', 'UTF-8')) {
                    $catId = 235;
                } else if ($catValue == mb_strtoupper('Хүсэлт', 'UTF-8')) {
                    $catId = 275;
                } else if ($catValue == mb_strtoupper('Сургалтын төлбөр', 'UTF-8')) {
                    $catId = 240;
                } else if ($catValue == mb_strtoupper('Ажлын байр', 'UTF-8')) {
                    $catId = 243;
                } else if ($catValue == mb_strtoupper('Гомдол', 'UTF-8')) {
                    $catId = 306;
                } else if ($catValue == mb_strtoupper('Эмчилгээ, Ажлын байр', 'UTF-8')) {
                    $catId = 235;
                } else if ($catValue == mb_strtoupper('Ном, хэвлэл', 'UTF-8')) {
                    $catId = 307;
                } else if ($catValue == mb_strtoupper('Засвар', 'UTF-8')) {
                    $catId = 308;
                } else if ($catValue == mb_strtoupper('Эмчилгээний зардал', 'UTF-8')) {
                    $catId = 235;
                } else if ($catValue == mb_strtoupper('Шагнал', 'UTF-8')) {
                    $catId = 242;
                } else if ($catValue == mb_strtoupper('Гэр, байр сууц', 'UTF-8')) {
                    $catId = 309;
                } else if ($catValue == mb_strtoupper('Мөнгөн тусламж', 'UTF-8')) {
                    $catId = 310;
                } else if ($catValue == mb_strtoupper('Тусламж, төрийн үйлчилгээ', 'UTF-8')) {
                    $catId = 311;
                } else if ($catValue == mb_strtoupper('Нийгмийн асуудал', 'UTF-8')) {
                    $catId = 311;
                } else if ($catValue == mb_strtoupper('Тусламж', 'UTF-8')) {
                    $catId = 310;
                } else if ($catValue == mb_strtoupper('Ажлын байр, Сургалтын төлбөр, Нийгмийн халамж', 'UTF-8')) {
                    $catId = 243;
                } else if ($catValue == mb_strtoupper('Нийгмийн үйлчилгээ', 'UTF-8')) {
                    $catId = 311;
                } else if ($catValue == mb_strtoupper('Ажлын байр, Мөнгө, Ниймгийн үйлчилгээ', 'UTF-8')) {
                    $catId = 243;
                } else if ($catValue == mb_strtoupper('Талархал', 'UTF-8')) {
                    $catId = 312;
                } else if ($catValue == mb_strtoupper('Хэлмэгдэл', 'UTF-8')) {
                    $catId = 313;
                } else if ($catValue == mb_strtoupper('Мөнгө', 'UTF-8')) {
                    $catId = 310;
                } else if ($catValue == mb_strtoupper('Санал', 'UTF-8')) {
                    $catId = 311;
                } else if ($catValue == mb_strtoupper('Шагнал, ажил', 'UTF-8')) {
                    $catId = 242;
                } else if ($catValue == mb_strtoupper('Гомдол', 'UTF-8')) {
                    $catId = 306;
                } else if ($catValue == mb_strtoupper('Сургалт', 'UTF-8')) {
                    $catId = 240;
                } else if ($catValue == mb_strtoupper('Сургалтын төлбөр, Эмчилгээ, Бусад', 'UTF-8')) {
                    $catId = 314;
                }
                $this->paramData['catId'] = $catId;
                $this->paramData['modId'] = 16;
                $this->paramData['description'] = ($sheet->getCellByColumnAndRow(9, $row)->getValue() != null ? $sheet->getCellByColumnAndRow(9, $row)->getValue() : '');
                $this->paramData['page'] = 0;
                $this->paramData['createdDate'] = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($sheet->getCellByColumnAndRow(11, $row)->getValue()));
                $this->paramData['modifiedDate'] = '0000-00-00 00:00:00';
                $this->paramData['createNumber'] = 'U' . $sheet->getCellByColumnAndRow(12, $row)->getValue();
                $this->paramData['createdUserId'] = $this->session->adminUserId;
                $this->paramData['modifiedUserId'] = 0;
                $this->paramData['isActive'] = 1;
                $this->paramData['cityId'] = 17;
                $this->paramData['soumId'] = 255;
                $this->paramData['streetId'] = ($sheet->getCellByColumnAndRow(5, $row)->getValue() != null ? $sheet->getCellByColumnAndRow(5, $row)->getValue() : '');
                $this->paramData['orderNum'] = getOrderNum(array('table' => 'urgudul', 'field' => 'order_num'));
                $this->paramData['closeDescription'] = $sheet->getCellByColumnAndRow(15, $row)->getValue() . ', ' . $sheet->getCellByColumnAndRow(13, $row)->getValue() . ', ' . $sheet->getCellByColumnAndRow(16, $row)->getValue();
                $this->paramData['closeAuthor'] = '';
                $this->paramData['closeDate'] = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($sheet->getCellByColumnAndRow(14, $row)->getValue()));
                $this->paramData['closeUserId'] = 0;
                $this->paramData['urgudulDirectId'] = 0;

//                echo '<pre>';
//                var_dump($this->paramData);
//                echo '</pre>';
                $this->urgudul->import_model($this->paramData);
            }
        }
    }

}
