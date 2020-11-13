<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ShrPeople extends CI_Controller {

    public static $path = "shrpeople/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('ShrPeoplePosition_model', 'hrPeoplePosition');
        $this->load->model('ShrPeopleRank_model', 'hrPeopleRank');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('ShrPeopleEducationRankMasterData_model', 'hrPeopleEducationRankMasterData');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Saddress_model', 'address');
        $this->load->model('Sstatus_model', 'status');

        $this->perPage = 2;
        $this->modId = 60;
        $this->departmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->isActiveDepartment = 'is_active_control';
        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/_hrPeople.js');

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
                $this->load->view(MY_ADMIN . '/hrPeople/index', $this->body);
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
                'moduleMenuId' => $this->input->get('moduleMenuId'),
                'createdUserId' => 0,
                'currentUserId' => $this->session->userdata['adminUserId'],
                'role' => 'read'));

            //total rows count
            $totalRec = $this->hrPeople->listsCount_model(array(
                'auth' => $this->auth,
                'lname' => $this->input->get('lname'),
                'fname' => $this->input->get('fname'),
                'sex' => $this->input->get('sex'),
                'birthday' => $this->input->get('birthday'),
                'birthdayOperator' => $this->input->get('birthdayOperator'),
                'liveCityId' => $this->input->get('liveCityId'),
                'liveSoumId' => $this->input->get('liveSoumId'),
                'liveStreetId' => $this->input->get('liveStreetId'),
                'liveAddress' => $this->input->get('liveAddress'),
                'familyName' => $this->input->get('familyName'),
                'nationality' => $this->input->get('nationality'),
                'socialOrigin' => $this->input->get('socialOrigin'),
                'register' => $this->input->get('register'),
                'socialInsurance' => $this->input->get('socialInsurance'),
                'healthInsurance' => $this->input->get('healthInsurance'),
                'phone' => $this->input->get('phone'),
                'fax' => $this->input->get('fax'),
                'email' => $this->input->get('email'),
                'postAddress' => $this->input->get('postAddress'),
                'specialPeople' => $this->input->get('specialPeople'),
                'specialPhone' => $this->input->get('specialPhone'),
                'departmentId' => $this->input->get('departmentId'),
                'positionId' => $this->input->get('positionId'),
                'rankId' => $this->input->get('rankId'),
                'statusId' => $this->input->get('statusId')));


            $result = $this->hrPeople->lists_model(array(
                'auth' => $this->auth,
                'modId' => $this->auth->modId,
                'lname' => $this->input->get('lname'),
                'fname' => $this->input->get('fname'),
                'sex' => $this->input->get('sex'),
                'birthday' => $this->input->get('birthday'),
                'birthdayOperator' => $this->input->get('birthdayOperator'),
                'liveCityId' => $this->input->get('liveCityId'),
                'liveSoumId' => $this->input->get('liveSoumId'),
                'liveStreetId' => $this->input->get('liveStreetId'),
                'liveAddress' => $this->input->get('liveAddress'),
                'familyName' => $this->input->get('familyName'),
                'nationality' => $this->input->get('nationality'),
                'socialOrigin' => $this->input->get('socialOrigin'),
                'register' => $this->input->get('register'),
                'socialInsurance' => $this->input->get('socialInsurance'),
                'healthInsurance' => $this->input->get('healthInsurance'),
                'phone' => $this->input->get('phone'),
                'fax' => $this->input->get('fax'),
                'email' => $this->input->get('email'),
                'postAddress' => $this->input->get('postAddress'),
                'specialPeople' => $this->input->get('specialPeople'),
                'specialPhone' => $this->input->get('specialPhone'),
                'departmentId' => $this->input->get('departmentId'),
                'positionId' => $this->input->get('positionId'),
                'rankId' => $this->input->get('rankId'),
                'statusId' => $this->input->get('statusId'),
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->hrPeople->addFormData_model();
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['row']->mod_id));

            $this->body['controlBirthCityDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => 12, 'selectedId' => $this->body['row']->birth_city_id, 'name' => 'birthCityId'));
            $this->body['controlBirthSoumDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => $this->body['row']->birth_city_id, 'selectedId' => $this->body['row']->birth_soum_id, 'name' => 'birthSoumId', 'readonly' => 'true'));
            $this->body['controlBirthStreetDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => $this->body['row']->birth_soum_id, 'selectedId' => $this->body['row']->birth_street_id, 'name' => 'birthStreetId', 'readonly' => 'true'));

            $this->body['controlLiveCityDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => 12, 'selectedId' => $this->body['row']->birth_city_id, 'name' => 'liveCityId'));
            $this->body['controlLiveSoumDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => $this->body['row']->birth_city_id, 'selectedId' => $this->body['row']->birth_soum_id, 'name' => 'liveSoumId', 'readonly' => 'true'));
            $this->body['controlLiveStreetDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => $this->body['row']->birth_soum_id, 'selectedId' => $this->body['row']->birth_street_id, 'name' => 'liveStreetId', 'readonly' => 'true'));

            $this->body['controlStatusListDropdown'] = $this->status->controlStatusListDropdown_model(array('modId' => 60, 'selectedId' => 0));

            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('selectedId' => 0));
            $this->body['controlHrPeopleDepartmentDropDown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array('name' => 'departmentId', 'selectedId' => 0, 'isActiveDepartment' => $this->isActiveDepartment, 'departmentId' => $this->departmentId));
            $this->body['controlHrPeoplePositionDropDown'] = $this->hrPeoplePosition->controlHrPeoplePositionDropDown_model(array('name' => 'positionId', 'selectedId' => 0));
            $this->body['controlHrPeopleRankDropDown'] = $this->hrPeopleRank->controlHrPeopleRankDropDown_model(array('name' => 'rankId', 'selectedId' => 0));

            echo json_encode(array(
                'title' => $this->body['module']->title . ' нэмэх',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/hrPeople/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->hrPeople->editFormData_model(array('id' => $this->input->post('id')));
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['row']->mod_id));

            $this->body['controlBirthCityDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => 12, 'selectedId' => $this->body['row']->birth_city_id, 'name' => 'birthCityId'));
            $this->body['controlBirthSoumDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => $this->body['row']->birth_city_id, 'selectedId' => $this->body['row']->birth_soum_id, 'name' => 'birthSoumId'));
            $this->body['controlBirthStreetDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => $this->body['row']->birth_soum_id, 'selectedId' => $this->body['row']->birth_street_id, 'name' => 'birthStreetId'));

            $this->body['controlLiveCityDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => 12, 'selectedId' => $this->body['row']->birth_city_id, 'name' => 'liveCityId'));
            $this->body['controlLiveSoumDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => $this->body['row']->birth_city_id, 'selectedId' => $this->body['row']->birth_soum_id, 'name' => 'liveSoumId'));
            $this->body['controlLiveStreetDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => $this->body['row']->birth_soum_id, 'selectedId' => $this->body['row']->birth_street_id, 'name' => 'liveStreetId'));

            $this->body['controlStatusListDropdown'] = $this->status->controlStatusListDropdown_model(array('modId' => 60, 'selectedId' => $this->body['row']->status_id));

            echo json_encode(array(
                'title' => $this->body['module']->title . ' засварлах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/hrPeople/formEdit', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function read() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->hrPeople->editFormData_model(array('id' => $this->input->post('id')));
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));

            $this->page->deny_model(array('moduleMenuId' => $this->input->post('moduleMenuId'), 'mode' => 'read', 'createdUserId' => $this->body['row']->created_user_id));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1, 'readonly' => true));
            $this->body['controlNifsResearchTypeDropdown'] = $this->nifsReSearchType->controlNifsResearchTypeDropdown_model(array('selectedId' => $this->body['row']->research_type_id, 'readonly' => true));
            $this->body['controlCrimeTypeDropdown'] = $this->nifsCrimeType->controlCrimeTypeDropdown_model(array('selectedId' => $this->body['row']->crime_type_id, 'readonly' => true));
            $this->body['controlMotiveDropdown'] = $this->nifsMotive->controlMotiveDropdown_model(array('selectedId' => $this->body['row']->crime_motive_id, 'readonly' => true));
            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('allInId' => array($this->session->adminPartnerId), 'selectedId' => $this->body['row']->partner_id, 'readonly' => true));
            $this->body['controlLatentPrintExpertDropDown'] = $this->nifsExpert->controlExpertDropDown_model(array('name' => 'latentPrintExpertId', 'selectedId' => $this->body['row']->latent_print_expert_id, 'readonly' => true));
            $this->body['controlExpertListDropdown'] = $this->nifsExpert->controlExpertListDropdown_model(array('modId' => $this->body['row']->mod_id, 'contId' => $this->body['row']->id, 'isMixx' => $this->body['row']->is_mixx, 'isRead' => 1, 'readonly' => true));
            $this->body['controlExpertDropDown'] = $this->nifsExpert->controlExpertDropDown_model(array('selectedId' => 0, 'name' => 'expertId[]', 'readonly' => true));

            echo json_encode(array(
                'title' => $this->module->title . ' дэлгэрэнгүй',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/hrPeople/formRead', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->hrPeople->insert_model(array('getUID' => getUID('hr_people'))));
    }

    public function update() {
        echo json_encode($this->hrPeople->update_model());
    }

    public function delete() {
        echo json_encode($this->hrPeople->delete_model());
    }

    public function searchForm() {

        $this->body['row'] = $this->hrPeople->addFormData_model();
        $this->body['module'] = $this->module->getData_model(array('id' => $this->body['row']->mod_id));

        $this->body['controlBirthCityDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => 12, 'selectedId' => $this->body['row']->birth_city_id, 'name' => 'birthCityId'));
        $this->body['controlBirthSoumDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => $this->body['row']->birth_city_id, 'selectedId' => $this->body['row']->birth_soum_id, 'name' => 'birthSoumId', 'readonly' => 'true'));
        $this->body['controlBirthStreetDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => $this->body['row']->birth_soum_id, 'selectedId' => $this->body['row']->birth_street_id, 'name' => 'birthStreetId', 'readonly' => 'true'));

        $this->body['controlLiveCityDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => 12, 'selectedId' => $this->body['row']->birth_city_id, 'name' => 'liveCityId'));
        $this->body['controlLiveSoumDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => $this->body['row']->birth_city_id, 'selectedId' => $this->body['row']->birth_soum_id, 'name' => 'liveSoumId', 'readonly' => 'true'));
        $this->body['controlLiveStreetDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => $this->body['row']->birth_soum_id, 'selectedId' => $this->body['row']->birth_street_id, 'name' => 'liveStreetId', 'readonly' => 'true'));

        $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('selectedId' => 0));
        $this->body['controlHrPeopleDepartmentDropDown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array('name' => 'departmentId', 'selectedId' => 0, 'isActiveDepartment' => $this->isActiveDepartment, 'departmentId' => $this->departmentId));
        $this->body['controlHrPeoplePositionDropDown'] = $this->hrPeoplePosition->controlHrPeoplePositionDropDown_model(array('name' => 'positionId', 'selectedId' => 0));
        $this->body['controlHrPeopleRankDropDown'] = $this->hrPeopleRank->controlHrPeopleRankDropDown_model(array('name' => 'rankId', 'selectedId' => 0));

        $this->body['controlStatusListDropdown'] = $this->status->controlStatusListDropdown_model(array('modId' => 60, 'selectedId' => 0));

        echo json_encode(array(
            'title' => 'Дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/hrPeople/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    public function listsHrPeopleFamilyMember() {
        echo json_encode($this->hrPeople->listsHrPeopleFamilyMember_model(array('peopleId' => $this->input->post('peopleId'))));
    }

    public function listsHrPeopleRelationMember() {
        echo json_encode($this->hrPeople->listsHrPeopleRelationMember_model(array('peopleId' => $this->input->post('peopleId'))));
    }

    public function listsHrPeopleEducation() {
        echo json_encode($this->hrPeople->listsHrPeopleEducation_model(array('peopleId' => $this->input->post('peopleId'))));
    }

    public function listsHrPeopleEducationDoctor() {
        echo json_encode($this->hrPeople->listsHrPeopleEducationDoctor_model(array('peopleId' => $this->input->post('peopleId'))));
    }

    public function listsHrPeopleCourse() {
        echo json_encode($this->hrPeople->listsHrPeopleCourse_model(array('peopleId' => $this->input->post('peopleId'))));
    }

    public function listsHrPeoplePositionRank() {
        echo json_encode($this->hrPeople->listsHrPeoplePositionRank_model(array('peopleId' => $this->input->post('peopleId'))));
    }

    public function listsHrPeopleEducationRank() {
        echo json_encode($this->hrPeople->listsHrPeopleEducationRank_model(array('peopleId' => $this->input->post('peopleId'))));
    }

    public function listsHrPeopleLanguage() {
        echo json_encode($this->hrPeople->listsHrPeopleLanguage_model(array('peopleId' => $this->input->post('peopleId'))));
    }

    public function listsHrPeopleWorkHistory() {
        echo json_encode($this->hrPeople->listsHrPeopleWorkHistory_model(array('peopleId' => $this->input->post('peopleId'))));
    }

    public function listsHrPeopleAward() {
        echo json_encode($this->hrPeople->listsHrPeopleAward_model(array('peopleId' => $this->input->post('peopleId'))));
    }

    public function reportForm() {
        $this->body['row'] = $this->hrPeople->getReportData_model(array('selectedId' => $this->input->post('selectedId')));
        $this->body['row']->people_id = $this->input->post('peopleId');
        echo json_encode(array(
            'title' => 'Ажлын тайлан',
            'btn_yes' => 'Хадгалах',
            'btn_no' => 'Хаах',
            'width' => 800,
            'html' => $this->load->view(MY_ADMIN . '/hrPeopleReport/form', $this->body, TRUE)
        ));
    }
    
    public function reportShow() {
        $this->body['row'] = $this->hrPeople->getReportData_model(array('selectedId' => $this->input->post('selectedId')));
        $this->body['row']->people_id = $this->input->post('peopleId');
        echo json_encode(array(
            'title' => 'Ажлын тайлан',
            'btn_yes' => 'Хадгалах',
            'btn_no' => 'Хаах',
            'width' => 800,
            'html' => $this->load->view(MY_ADMIN . '/hrPeopleReport/show', $this->body, TRUE)
        ));
    }
    
    public function insertHrPeopleReport() {
        echo json_encode($this->hrPeople->insertHrPeopleReport_model(array(
            'selectedId' => $this->input->post('selectedId'))));
    }

    public function deleteHrPeopleReport() {
        echo json_encode($this->hrPeople->deleteHrPeopleReport_model(array('id' => $this->input->post('id'))));
    }

    public function listsHrPeopleReport() {
        echo json_encode($this->hrPeople->listsHrPeopleReport_model(array('peopleId' => $this->input->post('peopleId'))));
    }

    public function listsHrPeopleConviction() {
        echo json_encode($this->hrPeople->listsHrPeopleConviction_model(array('peopleId' => $this->input->post('peopleId'))));
    }

    public function controlBirthYearDropdown() {
        echo json_encode($this->hrPeople->controlBirthYearDropdown_model(array('name' => $this->input->post('name'), 'selectedId' => $this->input->post('selectedId'))));
    }

    public function listsHrPeopleWork() {
        echo json_encode($this->hrPeople->listsHrPeopleWork_model(array('peopleId' => $this->input->post('peopleId'))));
    }

    public function controlHrPeopleListDropdown() {
        echo json_encode($this->hrPeople->controlHrPeopleListDropdown_model(array(
                    'name' => $this->input->post('name'),
                    'positionId' => $this->input->post('positionId'),
                    'departmentId' => $this->input->post('departmentId'),
                    'selectedId' => $this->input->post('selectedId'))));
    }

    public function controlHrPeopleMultiListDropdown() {
        echo json_encode($this->hrPeople->controlHrPeopleMultiListDropdown_model(array(
                    'isCurrenty' => $this->input->post('isCurrenty'),
                    'name' => $this->input->post('name'),
                    'departmentId' => $this->input->post('departmentId'),
                    'selectedId' => $this->input->post('selectedId'),
                    'modId' => $this->input->post('modId'),
                    'contId' => $this->input->post('contId'),
                    'positionId' => $this->input->post('positionId'),
                    'isMixx' => 1,
                    'isRead' => 0,
                    'isDeleteButton' => 1,
                    'initControlHtml' => $this->input->post('initControlHtml'),
                    'isExtraValue' => $this->input->post('isExtraValue'),
                    'extraExpertValue' => $this->input->post('extraExpertValue'))));
    }

    public function getData() {
        echo json_encode($this->hrPeople->getData_model(array('selectedId' => $this->input->post('selectedId'))));
    }

    public function dataUpdate() {
        echo json_encode($this->hrPeople->dataUpdate_model());
    }

    public function import() {
        $this->hrPeople->import_model();
    }

    public function duplicate() {

        $this->body['row'] = $this->hrPeople->duplicate_model();
        $this->load->view(MY_ADMIN . '/header', $this->header);

        $this->load->view(MY_ADMIN . '/hrPeople/duplicate', $this->body);
        $this->load->view(MY_ADMIN . '/footer', $this->footer);
    }

    public function notDepartmentList() {
        echo $this->hrPeople->notDepartmentList_model();
    }

    public function updateWorkList() {
        echo $this->hrPeople->updateWorkList_model();
    }

    public function expertUpdate() {
        echo $this->hrPeople->expertUpdate_model();
    }

}
