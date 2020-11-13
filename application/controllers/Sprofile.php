<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sprofile extends CI_Controller {

    public static $path = "sprofile/";

    function __construct() {
        parent::__construct();

        $this->load->model('Slanguage_model', 'language');
        $this->load->model('Sprofile_model', 'profile');
        $this->load->model('Spage_model', 'page');
        $this->load->model('Suser_model', 'suser');
        $this->load->model('ShrPeople_model', 'shrPeople');
        $this->load->model('SnifsCrime_model', 'nifsCrime');
        $this->load->model('Saddress_model', 'address');
        $this->load->model('Sstatus_model', 'status');
        $this->load->model('ShrPeople_model', 'hrPeople');
        


        $this->header = $this->body = $this->footer = array();
        $this->perPage = 2;
    }

    public function initProfile() {

        if ($this->session->isLogin === TRUE) {

            $this->body['flash'] = $this->input->post('flash');
            
            $this->body['row'] = $this->shrPeople->editFormData_model(array('id' => $this->session->userdata['adminPeopleId']));
            
            $this->body['controlBirthCityDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => 12, 'selectedId' => $this->body['row']->birth_city_id, 'name' => 'birthCityId', 'disabled' => 'true'));
            $this->body['controlBirthSoumDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => $this->body['row']->birth_city_id, 'selectedId' => $this->body['row']->birth_soum_id, 'name' => 'birthSoumId', 'disabled' => 'true'));
            $this->body['controlBirthStreetDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => $this->body['row']->birth_soum_id, 'selectedId' => $this->body['row']->birth_street_id, 'name' => 'birthStreetId', 'disabled' => 'true'));

            $this->body['controlLiveCityDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => 12, 'selectedId' => $this->body['row']->birth_city_id, 'name' => 'liveCityId', 'disabled' => 'true'));
            $this->body['controlLiveSoumDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => $this->body['row']->birth_city_id, 'selectedId' => $this->body['row']->birth_soum_id, 'name' => 'liveSoumId', 'disabled' => 'true'));
            $this->body['controlLiveStreetDropDown'] = $this->address->controlAddressDropDown_model(array('parentId' => $this->body['row']->birth_soum_id, 'selectedId' => $this->body['row']->birth_street_id, 'name' => 'liveStreetId', 'disabled' => 'true'));

            $this->body['controlStatusListDropdown'] = $this->status->controlStatusListDropdown_model(array('modId' => 60, 'selectedId' => $this->body['row']->status_id, 'disabled' => 'true'));
            
            /**About*/
            $this->body['listsHrPeopleFamilyMember'] = $this->hrPeople->listsHrPeopleFamilyMember_model(array('peopleId' => $this->body['row']->id, 'readonly' => 'true'));
            $this->body['listsHrPeopleRelationMember'] = $this->hrPeople->listsHrPeopleRelationMember_model(array('peopleId' => $this->body['row']->id, 'readonly' => 'true'));
            $this->body['listsHrPeopleWorkHistory'] = $this->hrPeople->listsHrPeopleWork_model(array('peopleId' => $this->body['row']->id, 'readonly' => 'true'));
            
            /**Education*/
            $this->body['listsHrPeopleEducation'] = $this->hrPeople->listsHrPeopleEducation_model(array('peopleId' => $this->body['row']->id, 'readonly' => 'true'));
            $this->body['listsHrPeopleEducationDoctor'] = $this->hrPeople->listsHrPeopleEducationDoctor_model(array('peopleId' => $this->body['row']->id, 'readonly' => 'true'));
            
            /**Profission*/
            $this->body['listsHrPeopleCourse'] = $this->hrPeople->listsHrPeopleCourse_model(array('peopleId' => $this->body['row']->id, 'readonly' => 'true'));
            $this->body['listsHrPeoplePositionRank'] = $this->hrPeople->listsHrPeoplePositionRank_model(array('peopleId' => $this->body['row']->id, 'readonly' => 'true'));
            $this->body['listsHrPeopleEducationRank'] = $this->hrPeople->listsHrPeopleEducationRank_model(array('peopleId' => $this->body['row']->id, 'readonly' => 'true'));
            $this->body['listsHrPeopleLanguage'] = $this->hrPeople->listsHrPeopleLanguage_model(array('peopleId' => $this->body['row']->id, 'readonly' => 'true'));
            
            /**Award*/
            $this->body['listsHrPeopleAward'] = $this->hrPeople->listsHrPeopleAward_model(array('peopleId' => $this->body['row']->id, 'readonly' => 'true'));
            
            /**Report*/
            $this->body['listsHrPeopleReport'] = $this->hrPeople->listsHrPeopleReport_model(array('peopleId' => $this->body['row']->id, 'readonly' => 'true'));
                    
            /**Conviction*/
            $this->body['listsHrPeopleConviction'] = $this->hrPeople->listsHrPeopleConviction_model(array('peopleId' => $this->body['row']->id, 'readonly' => 'true'));
            
            switch ($this->input->post('path')) {
                case 'about' : {
                        echo json_encode(array($this->load->view(MY_ADMIN . '/profile/about', $this->body, TRUE)));
                    };
                    break;
                
                case 'education' : {
                        echo json_encode(array($this->load->view(MY_ADMIN . '/profile/education', $this->body, TRUE)));
                    };
                    break;
                
                case 'profission' : {
                        echo json_encode(array($this->load->view(MY_ADMIN . '/profile/profission', $this->body, TRUE)));
                    };
                    break;
                
                case 'award' : {
                        echo json_encode(array($this->load->view(MY_ADMIN . '/profile/award', $this->body, TRUE)));
                    };
                    break;
                
                case 'report' : {
                        echo json_encode(array($this->load->view(MY_ADMIN . '/profile/report', $this->body, TRUE)));
                    };
                    break;
                
                case 'conviction' : {
                        echo json_encode(array($this->load->view(MY_ADMIN . '/profile/conviction', $this->body, TRUE)));
                    };
                    break;
                
                case 'information' : {
                        echo json_encode(array($this->load->view(MY_ADMIN . '/profile/information', $this->body, TRUE)));
                    };
                    break;

                case 'photo' : {
                        echo json_encode(array($this->load->view(MY_ADMIN . '/profile/photo', $this->body, TRUE)));
                    };
                    break;
                case 'password' : {
                        
                        echo json_encode(array($this->load->view(MY_ADMIN . '/profile/password', $this->body, TRUE)));
                    };
                    break;
                case 'forensics' : {
                        
                        echo json_encode(array($this->load->view(MY_ADMIN . '/profile/forensics', $this->body, TRUE)));
                    };
                    break;
            }
        } else {
            redirect(base_url('systemowner'));
        }
    }

    public function about() {

        //$this->header['cssFile'] = array('/application/views/systemowner/profile/profile.css');
        $this->footer['jsFile'] = array('/assets/system/core/_profile.js');

        if ($this->session->isLogin === TRUE) {

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => ''));
            $this->body['userData'] = $this->suser->getData_model(array('selectedId' => $this->session->adminUserId));
            $this->body['menu'] = $this->profile->menu_model(array('userData' => $this->body['userData']));
            
            $this->body['flash'] = $this->input->post('flash');
            
            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/profile/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
            
        } else {

            redirect(base_url('systemowner'));
        }
    }
    
    public function education() {

        $this->footer['jsFile'] = array('/assets/system/core/_profile.js');

        if ($this->session->isLogin === TRUE) {

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => ''));
            $this->body['userData'] = $this->suser->getData_model(array('selectedId' => $this->session->adminUserId));
            $this->body['menu'] = $this->profile->menu_model(array('userData' => $this->body['userData']));
            
            $this->body['flash'] = $this->input->post('flash');
            
            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/profile/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
            
        } else {

            redirect(base_url('systemowner'));
        }
    }
    
    public function profission() {

        $this->footer['jsFile'] = array('/assets/system/core/_profile.js');

        if ($this->session->isLogin === TRUE) {

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => ''));
            $this->body['userData'] = $this->suser->getData_model(array('selectedId' => $this->session->adminUserId));
            $this->body['menu'] = $this->profile->menu_model(array('userData' => $this->body['userData']));
            
            $this->body['flash'] = $this->input->post('flash');
            
            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/profile/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
            
        } else {

            redirect(base_url('systemowner'));
        }
    }
    
    public function award() {

        $this->footer['jsFile'] = array('/assets/system/core/_profile.js');

        if ($this->session->isLogin === TRUE) {

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => ''));
            $this->body['userData'] = $this->suser->getData_model(array('selectedId' => $this->session->adminUserId));
            $this->body['menu'] = $this->profile->menu_model(array('userData' => $this->body['userData']));
            
            $this->body['flash'] = $this->input->post('flash');
            
            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/profile/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
            
        } else {

            redirect(base_url('systemowner'));
        }
    }
    
    public function report() {

        $this->footer['jsFile'] = array('/assets/system/editor/ckeditor/ckeditor.js', '/assets/system/core/_profile.js', '/assets/system/core/_hrPeople.js');

        if ($this->session->isLogin === TRUE) {

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => ''));
            $this->body['userData'] = $this->suser->getData_model(array('selectedId' => $this->session->adminUserId));
            $this->body['menu'] = $this->profile->menu_model(array('userData' => $this->body['userData']));
            
            $this->body['flash'] = $this->input->post('flash');
            
            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/profile/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
            
        } else {

            redirect(base_url('systemowner'));
        }
    }
    
    public function conviction() {

        $this->footer['jsFile'] = array('/assets/system/core/_profile.js');

        if ($this->session->isLogin === TRUE) {

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => ''));
            $this->body['userData'] = $this->suser->getData_model(array('selectedId' => $this->session->adminUserId));
            $this->body['menu'] = $this->profile->menu_model(array('userData' => $this->body['userData']));
            
            $this->body['flash'] = $this->input->post('flash');
            
            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/profile/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
            
        } else {

            redirect(base_url('systemowner'));
        }
    }
    
    public function information() {

        $this->footer['jsFile'] = array('/assets/system/core/_profile.js');

        if ($this->session->isLogin === TRUE) {


            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => ''));

            $this->body['userData'] = $this->suser->getData_model(array('selectedId' => $this->session->adminUserId));
            $this->body['menu'] = $this->profile->menu_model(array('userData' => $this->body['userData']));


            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/profile/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
        } else {

            redirect(base_url('systemowner'));
        }
    }

    public function photo() {

        $this->footer['jsFile'] = array('/assets/system/core/_profile.js');

        if ($this->session->isLogin === TRUE) {


            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => ''));

            $this->body['userData'] = $this->suser->getData_model(array('selectedId' => $this->session->adminUserId));
            $this->body['menu'] = $this->profile->menu_model(array('userData' => $this->body['userData']));


            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/profile/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
        } else {

            redirect(base_url('systemowner'));
        }
    }

    public function password() {

        $this->footer['jsFile'] = array('/assets/system/core/_profile.js');

        if ($this->session->isLogin === TRUE) {


            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => ''));

            $this->body['userData'] = $this->suser->getData_model(array('selectedId' => $this->session->adminUserId));
            $this->body['menu'] = $this->profile->menu_model(array('userData' => $this->body['userData']));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/profile/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
        } else {

            redirect(base_url('systemowner'));
        }
    }
    
    public function forensics() {

        $this->footer['jsFile'] = array('/assets/system/core/_profile.js');

        if ($this->session->isLogin === TRUE) {


            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => 'Миний шинжилгээ'));

            $this->body['userData'] = $this->suser->getData_model(array('selectedId' => $this->session->adminUserId));
            $this->body['menu'] = $this->profile->menu_model(array('userData' => $this->body['userData']));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/profile/forensics', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
        } else {

            redirect(base_url('systemowner'));
        }
    }
    
    public function mInformation() {

        echo json_encode($this->profile->information_model(array('selectedId' => $this->input->get('peopleId'))));
    }

    public function updateUserData() {

        echo json_encode($this->profile->updateUserData_model());
    }
    
    public function updatePhoto() {

        echo json_encode($this->profile->updatePhoto_model());
    }
    
    public function nifsCrimeLists() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            //total rows count
            $totalRec = $this->profile->nifsCrimeListsCount_model(array(
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

            $result = $this->profile->nifsCrimeLists_model(array(
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
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
        }
    }
    
    public function nifsExtraLists() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            //total rows count
            $totalRec = $this->profile->nifsExtraListsCount_model(array(
                'moduleMenuId' => $this->input->get('moduleMenuId'),
                'catId' => $this->input->get('catId'),
                'createNumber' => $this->input->get('createNumber'),
                'researchTypeId' => $this->input->get('researchTypeId'),
                'isMixx' => $this->input->get('isMixx'),
                'motiveId' => $this->input->get('motiveId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'isAgeInfinitive' => $this->input->get('isAgeInfinitive'),
                'age1' => $this->input->get('age1'),
                'age2' => $this->input->get('age2'),
                'sex' => $this->input->get('sex'),
                'questionId' => $this->input->get('questionId'),
                'partnerId' => $this->input->get('partnerId'),
                'expertDoctorId' => $this->input->get('expertDoctorId'),
                'expertId' => $this->input->get('expertId'),
                'protocolNumber' => $this->input->get('protocolNumber'),
                'protocolInDate' => $this->input->get('protocolInDate'),
                'protocolOutDate' => $this->input->get('protocolOutDate'),
                'solutionId' => $this->input->get('solutionId'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'typeId' => $this->input->get('typeId'),
                'weight' => $this->input->get('weight'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'statusId' => $this->input->get('statusId'),
                'departmentId' => $this->input->get('departmentId'),
                'closeDescription' => $this->input->get('closeDescription'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate')));

            $result = $this->profile->nifsExtraLists_model(array(
                'moduleMenuId' => $this->input->get('moduleMenuId'),
                'catId' => $this->input->get('catId'),
                'createNumber' => $this->input->get('createNumber'),
                'researchTypeId' => $this->input->get('researchTypeId'),
                'isMixx' => $this->input->get('isMixx'),
                'motiveId' => $this->input->get('motiveId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'isAgeInfinitive' => $this->input->get('isAgeInfinitive'),
                'age1' => $this->input->get('age1'),
                'age2' => $this->input->get('age2'),
                'sex' => $this->input->get('sex'),
                'questionId' => $this->input->get('questionId'),
                'partnerId' => $this->input->get('partnerId'),
                'expertDoctorId' => $this->input->get('expertDoctorId'),
                'expertId' => $this->input->get('expertId'),
                'protocolNumber' => $this->input->get('protocolNumber'),
                'protocolInDate' => $this->input->get('protocolInDate'),
                'protocolOutDate' => $this->input->get('protocolOutDate'),
                'solutionId' => $this->input->get('solutionId'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'typeId' => $this->input->get('typeId'),
                'weight' => $this->input->get('weight'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'statusId' => $this->input->get('statusId'),
                'departmentId' => $this->input->get('departmentId'),
                'closeDescription' => $this->input->get('closeDescription'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
        }
    }
    
    public function nifsEconomyLists() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            //total rows count
            $totalRec = $this->profile->nifsEconomyListsCount_model(array(
                'catId' => $this->input->get('catId'),
                'createNumber' => $this->input->get('createNumber'),
                'researchTypeId' => $this->input->get('researchTypeId'),
                'isMixx' => $this->input->get('isMixx'),
                'motiveId' => $this->input->get('motiveId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'questionId' => $this->input->get('questionId'),
                'partnerId' => $this->input->get('partnerId'),
                'expertId' => $this->input->get('expertId'),
                'protocolNumber' => $this->input->get('protocolNumber'),
                'protocolInDate' => $this->input->get('protocolInDate'),
                'protocolOutDate' => $this->input->get('protocolOutDate'),
                'solutionId' => $this->input->get('solutionId'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'weight' => $this->input->get('weight'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'typeId' => $this->input->get('typeId'),
                'statusId' => $this->input->get('statusId'),
                'closeDate' => $this->input->get('closeDate'),
                'departmentId' => $this->input->get('departmentId'),
                'closeDescription' => $this->input->get('closeDescription'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate')));

            //get posts data
            $result = $this->profile->nifsEconomyLists_model(array(
                'catId' => $this->input->get('catId'),
                'createNumber' => $this->input->get('createNumber'),
                'researchTypeId' => $this->input->get('researchTypeId'),
                'isMixx' => $this->input->get('isMixx'),
                'motiveId' => $this->input->get('motiveId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'questionId' => $this->input->get('questionId'),
                'partnerId' => $this->input->get('partnerId'),
                'expertId' => $this->input->get('expertId'),
                'protocolNumber' => $this->input->get('protocolNumber'),
                'protocolInDate' => $this->input->get('protocolInDate'),
                'protocolOutDate' => $this->input->get('protocolOutDate'),
                'solutionId' => $this->input->get('solutionId'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'weight' => $this->input->get('weight'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'typeId' => $this->input->get('typeId'),
                'statusId' => $this->input->get('statusId'),
                'closeDate' => $this->input->get('closeDate'),
                'departmentId' => $this->input->get('departmentId'),
                'closeDescription' => $this->input->get('closeDescription'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
        }
    }
    
    public function nifsFileFolderLists() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            //total rows count
            $totalRec = $this->profile->nifsFileFolderListsCount_model(array(
                'catId' => $this->input->get('catId'),
                'createNumber' => $this->input->get('createNumber'),
                'researchTypeId' => $this->input->get('researchTypeId'),
                'isMixx' => $this->input->get('isMixx'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'partnerId' => $this->input->get('partnerId'),
                'questionId' => $this->input->get('questionId'),
                'preCreateNumber' => $this->input->get('preCreateNumber'),
                'preExpertId' => $this->input->get('preExpertId'),
                'motiveId' => $this->input->get('motiveId'),
                'solutionId' => $this->input->get('solutionId'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'catId' => $this->input->get('catId'),
                'seniorExpertId' => $this->input->get('seniorExpertId'),
                'createExpertId' => $this->input->get('createExpertId'),
                'expertId' => $this->input->get('expertId'),
                'weight' => $this->input->get('weight'),
                'protocolNumber' => $this->input->get('protocolNumber'),
                'protocolInDate' => $this->input->get('protocolInDate'),
                'protocolOutDate' => $this->input->get('protocolOutDate'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'departmentId' => $this->input->get('departmentId'),
                'statusId' => $this->input->get('statusId'),
                'closeDescription' => $this->input->get('closeDescription'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'age1' => $this->input->get('age1'),
                'age2' => $this->input->get('age2'),
                'preCrime' => $this->input->get('preCrime')));



            //get posts data
            $result = $this->profile->nifsFileFolderLists_model(array(
                'catId' => $this->input->get('catId'),
                'createNumber' => $this->input->get('createNumber'),
                'researchTypeId' => $this->input->get('researchTypeId'),
                'isMixx' => $this->input->get('isMixx'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'partnerId' => $this->input->get('partnerId'),
                'questionId' => $this->input->get('questionId'),
                'preCreateNumber' => $this->input->get('preCreateNumber'),
                'preExpertId' => $this->input->get('preExpertId'),
                'motiveId' => $this->input->get('motiveId'),
                'solutionId' => $this->input->get('solutionId'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'catId' => $this->input->get('catId'),
                'seniorExpertId' => $this->input->get('seniorExpertId'),
                'createExpertId' => $this->input->get('createExpertId'),
                'expertId' => $this->input->get('expertId'),
                'weight' => $this->input->get('weight'),
                'protocolNumber' => $this->input->get('protocolNumber'),
                'protocolInDate' => $this->input->get('protocolInDate'),
                'protocolOutDate' => $this->input->get('protocolOutDate'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'departmentId' => $this->input->get('departmentId'),
                'statusId' => $this->input->get('statusId'),
                'closeDescription' => $this->input->get('closeDescription'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'age1' => $this->input->get('age1'),
                'age2' => $this->input->get('age2'),
                'preCrime' => $this->input->get('preCrime'),
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));

            //load the view
            //$this->load->view(MY_ADMIN . '/nifsFileFolder/lists', $data, false);
        }
    }
    
    public function nifsAnatomyLists() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            //total rows count
            $totalRec = $this->profile->nifsAnatomyListsCount_model(array(
                'catId' => $this->input->get('catId'),
                'createNumber' => $this->input->get('createNumber'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'shortValueId' => $this->input->get('shortValueId'),
                'workId' => $this->input->get('workId'),
                'motiveId' => $this->input->get('motiveId'),
                'partnerId' => $this->input->get('partnerId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'protocolInDate' => $this->input->get('protocolInDate'),
                'protocolOutDate' => $this->input->get('protocolOutDate'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'crimeDate' => $this->input->get('crimeDate'),
                'age1' => $this->input->get('age1'),
                'age2' => $this->input->get('age2'),
                'isAgeInfinitive' => $this->input->get('isAgeInfinitive'),
                'short_value' => $this->input->get('shortValue'),
                'crimeShortValueId' => $this->input->get('crimeShortValueId'),
                'expertId' => $this->input->get('expertId'),
                'whereId' => $this->input->get('whereId'),
                'catId' => $this->input->get('catId'),
                'payment' => $this->input->get('payment'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'departmentId' => $this->input->get('departmentId'),
                'solutionId' => $this->input->get('solutionId'),
                'isMixx' => $this->input->get('isMixx'),
                'closeDate' => $this->input->get('closeDate'),
                'statusId' => $this->input->get('statusId'),
                'sex' => $this->input->get('sex'),
                'closeDescription' => $this->input->get('closeDescription')));

            //get posts data
            $result = $this->profile->nifsAnatomyLists_model(array(
                'catId' => $this->input->get('catId'),
                'createNumber' => $this->input->get('createNumber'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'shortValueId' => $this->input->get('shortValueId'),
                'workId' => $this->input->get('workId'),
                'motiveId' => $this->input->get('motiveId'),
                'partnerId' => $this->input->get('partnerId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'protocolInDate' => $this->input->get('protocolInDate'),
                'protocolOutDate' => $this->input->get('protocolOutDate'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'crimeDate' => $this->input->get('crimeDate'),
                'age1' => $this->input->get('age1'),
                'age2' => $this->input->get('age2'),
                'isAgeInfinitive' => $this->input->get('isAgeInfinitive'),
                'crimeShortValueId' => $this->input->get('crimeShortValueId'),
                'short_value' => $this->input->get('shortValue'),
                'expertId' => $this->input->get('expertId'),
                'whereId' => $this->input->get('whereId'),
                'catId' => $this->input->get('catId'),
                'payment' => $this->input->get('payment'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'departmentId' => $this->input->get('departmentId'),
                'solutionId' => $this->input->get('solutionId'),
                'isMixx' => $this->input->get('isMixx'),
                'closeDate' => $this->input->get('closeDate'),
                'statusId' => $this->input->get('statusId'),
                'sex' => $this->input->get('sex'),
                'closeDescription' => $this->input->get('closeDescription'),
                'totalRows' => $totalRec,
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
        }
    }
    
    public function nifsDoctorViewLists() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            //total rows count
            $totalRec = $this->profile->nifsDoctorViewListsCount_model(array(
                'catId' => $this->input->get('catId'),
                'createNumber' => $this->input->get('createNumber'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'shortValueId' => $this->input->get('shortValueId'),
                'workId' => $this->input->get('workId'),
                'motiveId' => $this->input->get('motiveId'),
                'partnerId' => $this->input->get('partnerId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'protocolInDate' => $this->input->get('protocolInDate'),
                'protocolOutDate' => $this->input->get('protocolOutDate'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'closeDescription' => $this->input->get('closeDescription'),
                'crimeInDate' => $this->input->get('crimeInDate'),
                'crimeOutDate' => $this->input->get('crimeOutDate'),
                'age1' => $this->input->get('age1'),
                'age2' => $this->input->get('age2'),
                'isAgeInfinitive' => $this->input->get('isAgeInfinitive'),
                'crimeShortValueId' => $this->input->get('crimeShortValueId'),
                'expertId' => $this->input->get('expertId'),
                'whereId' => $this->input->get('whereId'),
                'catId' => $this->input->get('catId'),
                'payment' => $this->input->get('payment'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'departmentId' => $this->input->get('departmentId'),
                'statusId' => $this->input->get('statusId'),
                'isSperm' => $this->input->get('isSperm'),
                'isCrimeShip' => $this->input->get('isCrimeShip'),
                'sex' => $this->input->get('sex'),
                'closeDescription' => $this->input->get('closeDescription')));

            //get posts data
            $result = $this->profile->nifsDoctorViewLists_model(array(
                'catId' => $this->input->get('catId'),
                'createNumber' => $this->input->get('createNumber'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'shortValueId' => $this->input->get('shortValueId'),
                'workId' => $this->input->get('workId'),
                'motiveId' => $this->input->get('motiveId'),
                'partnerId' => $this->input->get('partnerId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'protocolInDate' => $this->input->get('protocolInDate'),
                'protocolOutDate' => $this->input->get('protocolOutDate'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'closeDescription' => $this->input->get('closeDescription'),
                'crimeInDate' => $this->input->get('crimeInDate'),
                'crimeOutDate' => $this->input->get('crimeOutDate'),
                'age1' => $this->input->get('age1'),
                'age2' => $this->input->get('age2'),
                'isAgeInfinitive' => $this->input->get('isAgeInfinitive'),
                'crimeShortValueId' => $this->input->get('crimeShortValueId'),
                'expertId' => $this->input->get('expertId'),
                'whereId' => $this->input->get('whereId'),
                'catId' => $this->input->get('catId'),
                'payment' => $this->input->get('payment'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'departmentId' => $this->input->get('departmentId'),
                'statusId' => $this->input->get('statusId'),
                'isSperm' => $this->input->get('isSperm'),
                'isCrimeShip' => $this->input->get('isCrimeShip'),
                'sex' => $this->input->get('sex'),
                'closeDescription' => $this->input->get('closeDescription'),
                'totalRows' => $totalRec,
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
        }
    }
    
}
