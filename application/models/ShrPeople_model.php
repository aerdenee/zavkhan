<?php

class ShrPeople_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Saddress_model', 'address');
        $this->load->model('Suser_model', 'user');
        $this->load->model('SreportMenu_model', 'sreportMenu');
        $this->load->model('ShrPeopleRelation_model', 'shrPeopleRelation');
        $this->load->model('ShrPeopleEducationRankMasterData_model', 'hrPeopleEducationRankMasterData');
        $this->load->model('ShrPeoplePosition_model', 'hrPeoplePosition');
        $this->load->model('ShrPeopleRank_model', 'hrPeopleRank');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('Simage_model', 'simage');
        $this->load->model('Sstatus_model', 'status');


        $this->departmentId = 5;
        $this->modId = 60;
        $this->departmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->isActiveDepartment = 'is_active_control';
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => $this->modId,
            'cat_id' => 0,
            'partner_id' => 0,
            'department_id' => 0,
            'position_id' => 0,
            'rank_id' => 0,
            'position_description' => '',
            'register' => '',
            'social_insurance' => '',
            'health_insurance' => '',
            'family_name' => '',
            'fname' => '',
            'lname' => '',
            'sex' => 1,
            'birthday' => date('Y-m-d'),
            'pic' => $this->simage->returnDefaultImage_model(array('table' => 'hr_people')),
            'birth_city_id' => 0,
            'birth_soum_id' => 0,
            'birth_street_id' => 0,
            'birth_address' => '',
            'nationality' => '',
            'social_origin' => '',
            'live_city_id' => 0,
            'live_soum_id' => 0,
            'live_street_id' => 0,
            'live_address' => '',
            'phone' => '',
            'fax' => '',
            'email' => '',
            'post_address' => '',
            'special_people' => '',
            'special_phone' => '',
            'is_active' => 1,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'param' => '',
            'order_num' => getOrderNum(array('table' => 'hr_people', 'field' => 'order_num')),
            'user_partner_id' => 0,
            'status_id' => 0
        )));
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT
                HP.id,
                HP.mod_id,
                HP.cat_id,
                HP.partner_id,
                HP.department_id,
                HP.position_id,
                HP.rank_id,
                HP.position_description,
                HP.register,
                HP.social_insurance,
                HP.health_insurance,
                HP.family_name,
                HP.fname,
                HP.lname,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name,
                HP.sex,
                HP.birthday,
                (case when (HP.pic is null or HP.pic = \'\' or HP.pic = \'default.svg\') then "' . $this->simage->returnDefaultImage_model(array('table' => 'hr_people')) . '" else CONCAT("' . UPLOADS_USER_PATH . CROP_SMALL . '", HP.pic) end) as pic,
                HP.birth_city_id,
                HP.birth_soum_id,
                HP.birth_street_id,
                HP.birth_address,
                HP.nationality,
                HP.social_origin,
                HP.live_city_id,
                HP.live_soum_id,
                HP.live_street_id,
                HP.live_address,
                HP.phone,
                HP.fax,
                HP.email,
                HP.post_address,
                HP.special_people,
                HP.special_phone,
                HP.is_active,
                HP.created_date,
                HP.modified_date,
                HP.created_user_id,
                HP.modified_user_id,
                HP.param,
                HP.order_num,
                HP.user_partner_id,
                HP.status_id
            FROM `gaz_hr_people` AS HP
            WHERE HP.id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function listsCount_model($param = array()) {

        $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and ! $auth->your->read) {
            $queryString .= ' AND HP.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND HP.created_user_id = -1';
        }

        if ($this->session->adminAccessTypeId == 3) {
            if ($param['departmentId'] != 0) {

                $queryString .= ' AND HP.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            }
        } else {
            if ($param['departmentId'] != 0) {

                $queryString .= ' AND HP.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            } else {

                $queryString .= ' AND HP.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ')';
            }
        }


        if ($param['lname'] != '') {
            $queryString .= ' AND lower(HP.lname) LIKE lower(\'%' . $param['lname'] . '%\')';
        }

        if ($param['fname'] != '') {
            $queryString .= ' AND lower(HP.fname) LIKE lower(\'%' . $param['fname'] . '%\')';
        }

        if ($param['sex'] == 1) {
            $queryString .= ' AND HP.sex = 1';
        }

        if ($param['sex'] == 2) {
            $queryString .= ' AND HP.sex = 0';
        }

        if ($param['birthday'] != '' AND ( $param['birthdayOperator'] == '-' OR $param['birthdayOperator'] == '=')) {

            $queryString .= ' AND DATE(HP.birthday) LIKE DATE(\'' . $param['birthday'] . '\')';
        } else if ($param['birthday'] != '' AND $param['birthdayOperator'] == '<') {

            $queryString .= ' AND DATE(HP.birthday) < DATE(\'' . $param['birthday'] . '\')';
        } else if ($param['birthday'] != '' AND $param['birthdayOperator'] == '<=') {

            $queryString .= ' AND DATE(HP.birthday) <= DATE(\'' . $param['birthday'] . '\')';
        } else if ($param['birthday'] != '' AND $param['birthdayOperator'] == '>') {

            $queryString .= ' AND DATE(HP.birthday) > DATE(\'' . $param['birthday'] . '\')';
        } else if ($param['birthday'] != '' AND $param['birthdayOperator'] == '>=') {

            $queryString .= ' AND DATE(HP.birthday) >= DATE(\'' . $param['birthday'] . '\')';
        }

        if ($param['liveCityId'] != 0 and $param['liveCityId'] != '') {
            $queryString .= ' AND HP.live_city_id = ' . $param['liveCityId'];
        }

        if ($param['liveSoumId'] != 0 and $param['liveSoumId'] != '') {
            $queryString .= ' AND HP.live_soum_id = ' . $param['liveSoumId'];
        }

        if ($param['liveStreetId'] != 0 and $param['liveStreetId'] != '') {
            $queryString .= ' AND HP.live_street_id = ' . $param['liveStreetId'];
        }

        if ($param['liveAddress'] != '') {
            $queryString .= ' AND HP.live_address LIKE (\'%' . $param['liveAddress'] . '%\')';
        }

        if ($param['familyName'] != '') {
            $queryString .= ' AND HP.family_name LIKE (\'%' . $param['familyName'] . '%\')';
        }

        if ($param['nationality'] != '') {
            $queryString .= ' AND HP.nationality LIKE (\'%' . $param['nationality'] . '%\')';
        }

        if ($param['socialOrigin'] != '') {
            $queryString .= ' AND HP.social_origin LIKE (\'%' . $param['socialOrigin'] . '%\')';
        }

        if ($param['register'] != '') {
            $queryString .= ' AND HP.register LIKE (\'%' . $param['register'] . '%\')';
        }

        if ($param['socialInsurance'] != '') {
            $queryString .= ' AND HP.social_insurance LIKE (\'%' . $param['socialInsurance'] . '%\')';
        }

        if ($param['healthInsurance'] != '') {
            $queryString .= ' AND HP.health_insurance LIKE (\'%' . $param['healthInsurance'] . '%\')';
        }

        if ($param['phone'] != '') {
            $queryString .= ' AND HP.phone LIKE (\'%' . $param['phone'] . '%\')';
        }

        if ($param['fax'] != '') {
            $queryString .= ' AND HP.fax LIKE (\'%' . $param['fax'] . '%\')';
        }

        if ($param['email'] != '') {
            $queryString .= ' AND HP.email LIKE (\'%' . $param['email'] . '%\')';
        }

        if ($param['postAddress'] != '') {
            $queryString .= ' AND HP.post_address LIKE (\'%' . $param['postAddress'] . '%\')';
        }

        if ($param['specialPeople'] != '') {
            $queryString .= ' AND HP.special_people LIKE (\'%' . $param['specialPeople'] . '%\')';
        }

        if ($param['specialPhone'] != '') {
            $queryString .= ' AND HP.special_phone LIKE (\'%' . $param['specialPhone'] . '%\')';
        }

        if ($param['positionId'] != 0 and $param['positionId'] != '') {
            $queryString .= ' AND HP.position_id LIKE (\'%' . $param['positionId'] . '%\')';
        }

        if ($param['rankId'] != 0 and $param['rankId'] != '') {
            $queryString .= ' AND HP.rank_id LIKE (\'%' . $param['rankId'] . '%\')';
        }

        if ($param['statusId'] != 0 and $param['statusId'] != '') {
            $queryString .= ' AND HP.status_id LIKE (\'%' . $param['statusId'] . '%\')';
        }

        $query = $this->db->query('
                SELECT 
                    HP.id
                FROM `gaz_hr_people` AS HP
                WHERE HP.is_active = 1 ' . $queryString);

        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $data = array();
        $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and ! $auth->your->read) {
            $queryString .= ' AND HP.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND HP.created_user_id = -1';
        }

        if ($this->session->adminAccessTypeId == 3) {
            if ($param['departmentId'] != 0) {

                $queryString .= ' AND HP.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            }
        } else {
            if ($param['departmentId'] != 0) {

                $queryString .= ' AND HP.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            } else {

                $queryString .= ' AND HP.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ')';
            }
        }

        if ($param['lname'] != '') {
            $queryString .= ' AND lower(HP.lname) LIKE lower(\'%' . $param['lname'] . '%\')';
        }

        if ($param['fname'] != '') {
            $queryString .= ' AND lower(HP.fname) LIKE lower(\'%' . $param['fname'] . '%\')';
        }

        if ($param['sex'] == 1) {
            $queryString .= ' AND HP.sex = 1';
        }

        if ($param['sex'] == 2) {
            $queryString .= ' AND HP.sex = 0';
        }

        if ($param['birthday'] != '' AND ( $param['birthdayOperator'] == '-' OR $param['birthdayOperator'] == '=')) {

            $queryString .= ' AND DATE(HP.birthday) LIKE DATE(\'' . $param['birthday'] . '\')';
        } else if ($param['birthday'] != '' AND $param['birthdayOperator'] == '<') {

            $queryString .= ' AND DATE(HP.birthday) < DATE(\'' . $param['birthday'] . '\')';
        } else if ($param['birthday'] != '' AND $param['birthdayOperator'] == '<=') {

            $queryString .= ' AND DATE(HP.birthday) <= DATE(\'' . $param['birthday'] . '\')';
        } else if ($param['birthday'] != '' AND $param['birthdayOperator'] == '>') {

            $queryString .= ' AND DATE(HP.birthday) > DATE(\'' . $param['birthday'] . '\')';
        } else if ($param['birthday'] != '' AND $param['birthdayOperator'] == '>=') {

            $queryString .= ' AND DATE(HP.birthday) >= DATE(\'' . $param['birthday'] . '\')';
        }

        if ($param['liveCityId'] != 0 and $param['liveCityId'] != '') {
            $queryString .= ' AND HP.live_city_id = ' . $param['liveCityId'];
        }

        if ($param['liveSoumId'] != 0 and $param['liveSoumId'] != '') {
            $queryString .= ' AND HP.live_soum_id = ' . $param['liveSoumId'];
        }

        if ($param['liveStreetId'] != 0 and $param['liveStreetId'] != '') {
            $queryString .= ' AND HP.live_street_id = ' . $param['liveStreetId'];
        }

        if ($param['liveAddress'] != '') {
            $queryString .= ' AND HP.live_address LIKE (\'%' . $param['liveAddress'] . '%\')';
        }

        if ($param['familyName'] != '') {
            $queryString .= ' AND HP.family_name LIKE (\'%' . $param['familyName'] . '%\')';
        }

        if ($param['nationality'] != '') {
            $queryString .= ' AND HP.nationality LIKE (\'%' . $param['nationality'] . '%\')';
        }

        if ($param['socialOrigin'] != '') {
            $queryString .= ' AND HP.social_origin LIKE (\'%' . $param['socialOrigin'] . '%\')';
        }

        if ($param['register'] != '') {
            $queryString .= ' AND HP.register LIKE (\'%' . $param['register'] . '%\')';
        }

        if ($param['socialInsurance'] != '') {
            $queryString .= ' AND HP.social_insurance LIKE (\'%' . $param['socialInsurance'] . '%\')';
        }

        if ($param['healthInsurance'] != '') {
            $queryString .= ' AND HP.health_insurance LIKE (\'%' . $param['healthInsurance'] . '%\')';
        }

        if ($param['phone'] != '') {
            $queryString .= ' AND HP.phone LIKE (\'%' . $param['phone'] . '%\')';
        }

        if ($param['fax'] != '') {
            $queryString .= ' AND HP.fax LIKE (\'%' . $param['fax'] . '%\')';
        }

        if ($param['email'] != '') {
            $queryString .= ' AND HP.email LIKE (\'%' . $param['email'] . '%\')';
        }

        if ($param['postAddress'] != '') {
            $queryString .= ' AND HP.post_address LIKE (\'%' . $param['postAddress'] . '%\')';
        }

        if ($param['specialPeople'] != '') {
            $queryString .= ' AND HP.special_people LIKE (\'%' . $param['specialPeople'] . '%\')';
        }

        if ($param['specialPhone'] != '') {
            $queryString .= ' AND HP.special_phone LIKE (\'%' . $param['specialPhone'] . '%\')';
        }

        if ($param['positionId'] != 0 and $param['positionId'] != '') {
            $queryString .= ' AND HP.position_id LIKE (\'%' . $param['positionId'] . '%\')';
        }

        if ($param['rankId'] != 0 and $param['rankId'] != '') {
            $queryString .= ' AND HP.rank_id LIKE (\'%' . $param['rankId'] . '%\')';
        }

        if ($param['statusId'] != 0 and $param['statusId'] != '') {
            $queryString .= ' AND HP.status_id LIKE (\'%' . $param['statusId'] . '%\')';
        }

        $query = $this->db->query('
            SELECT 
                HP.id,
                HP.created_user_id,
                HP.modified_date,
                HP.is_active,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name,
                (case when (HP.pic is null or HP.pic = \'\' or HP.pic = \'default.svg\') then "' . $this->simage->returnDefaultImage_model(array('table' => 'hr_people')) . '" else CONCAT("' . UPLOADS_USER_PATH . '", HP.pic) end) as pic,
                HP.live_address,
                HP.phone,
                HP.email,
                HP.special_people,
                HP.special_phone,
                \'\' AS status,
                HP.param
            FROM `gaz_hr_people` AS HP
            LEFT JOIN gaz_address AS AST ON HP.live_street_id = AST.id
            WHERE HP.is_active = 1 ' . $queryString . '
            ORDER BY HP.order_num DESC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $row) {

                $param = json_decode($row->param);

                array_push($data, array(
                    'id' => $row->id,
                    'created_user_id' => $row->created_user_id,
                    'full_name_position' => '<strong>' . $row->full_name . '</strong><br>' . $param->work->department . ', ' . $param->work->position . ' (' . $param->work->rank . ')',
                    'pic' => '<img src="' . $row->pic . '">',
                    'contact' => $param->live->city . ' ' . $param->live->soum . ' ' . $param->live->street . ' ' . $row->live_address . ' ' . $row->phone . ' ' . $row->email . ' ' . $row->special_people . ' ' . $row->special_phone,
                    'modified_date' => $row->modified_date,
                    'is_active' => $row->is_active
                ));
            }
        }
        return array('data' => $data, 'search' => self::searchKeywordView_model());
    }

    public function insert_model($param = array()) {


        $birthCity = $this->address->getData_model(array('selectedId' => $this->input->post('birthCityId')));
        $birthSoum = $this->address->getData_model(array('selectedId' => $this->input->post('birthSoumId')));
        $birthStreet = $this->address->getData_model(array('selectedId' => $this->input->post('birthStreetId')));

        $liveCity = $this->address->getData_model(array('selectedId' => $this->input->post('liveCityId')));
        $liveSoum = $this->address->getData_model(array('selectedId' => $this->input->post('liveSoumId')));
        $liveStreet = $this->address->getData_model(array('selectedId' => $this->input->post('liveStreetId')));

        $this->data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'register' => $this->input->post('register'),
                'social_insurance' => $this->input->post('socialInsurance'),
                'health_insurance' => $this->input->post('healthInsurance'),
                'family_name' => $this->input->post('familyName'),
                'fname' => $this->input->post('fname'),
                'lname' => $this->input->post('lname'),
                'sex' => $this->input->post('sex'),
                'birthday' => $this->input->post('birthday'),
                'pic' => $this->input->post('hrPeoplePic'),
                'birth_city_id' => $this->input->post('birthCityId'),
                'birth_soum_id' => $this->input->post('birthSoumId'),
                'birth_street_id' => $this->input->post('birthStreetId'),
                'birth_address' => $this->input->post('birthAddress'),
                'nationality' => $this->input->post('nationality'),
                'social_origin' => $this->input->post('socialOrigin'),
                'live_city_id' => $this->input->post('liveCityId'),
                'live_soum_id' => $this->input->post('liveSoumId'),
                'live_street_id' => $this->input->post('liveStreetId'),
                'live_address' => $this->input->post('liveAddress'),
                'phone' => $this->input->post('phone'),
                'fax' => $this->input->post('fax'),
                'email' => $this->input->post('email'),
                'post_address' => $this->input->post('postAddress'),
                'special_people' => $this->input->post('specialPeople'),
                'special_phone' => $this->input->post('specialPhone'),
                'is_active' => $this->input->post('isActive'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => '0000-00-00 00:00:00',
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => $this->session->adminUserId,
                'param' => json_encode(array(
                    'birth' => array('city' => ($birthCity != false ? $birthCity->title : ''), 'soum' => ($birthSoum != false ? $birthSoum->title : ''), 'street' => ($birthStreet != false ? $birthStreet->title : '')),
                    'live' => array('city' => ($liveCity != false ? $liveCity->title : ''), 'soum' => ($liveSoum != false ? $liveSoum->title : ''), 'street' => ($liveStreet != false ? $liveStreet->title : '')),
                    'work' => array('department' => '', 'position' => '', 'rank' => '')
                )),
                'order_num' => $this->input->post('orderNum'),
                'status_id' => $this->input->post('statusId')
            )
        );
        if ($this->db->insert_batch($this->db->dbprefix . 'hr_people', $this->data)) {

            /*
             * Tab 1
             * */
            $this->deleteInsertHrPeopleFamilyMember_model(array('peopleId' => $param['getUID']));

            $this->deleteInsertHrPeopleRelationMember_model(array('peopleId' => $param['getUID']));

            $this->deleteInsertHrPeopleWork_model(array('peopleId' => $param['getUID']));

            /*
             * Tab 2
             * */

            $this->deleteInsertHrPeopleEducation_model(array('peopleId' => $param['getUID']));

            $this->deleteInsertHrPeopleEducationDoctor_model(array('peopleId' => $param['getUID']));

            /*
             * Tab 3
             * */

            $this->deleteInsertHrPeopleCourse_model(array('peopleId' => $param['getUID']));

            $this->deleteInsertHrPeoplePositionRank_model(array('peopleId' => $param['getUID']));

            $this->deleteInsertHrPeopleEducationRank_model(array('peopleId' => $param['getUID']));

            $this->deleteInsertHrPeopleLanguage_model(array('peopleId' => $param['getUID']));

            /*
             * Tab 4
             * */

            $this->deleteInsertHrPeopleAward_model(array('peopleId' => $param['getUID']));


            /*
             * Tab 5
             * */

            /*
             * Tab 6
             * */
            $this->deleteInsertHrPeopleConviction_model(array('peopleId' => $param['getUID']));

            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {

        $birthCity = $this->address->getData_model(array('selectedId' => $this->input->post('birthCityId')));
        $birthSoum = $this->address->getData_model(array('selectedId' => $this->input->post('birthSoumId')));
        $birthStreet = $this->address->getData_model(array('selectedId' => $this->input->post('birthStreetId')));

        $liveCity = $this->address->getData_model(array('selectedId' => $this->input->post('liveCityId')));
        $liveSoum = $this->address->getData_model(array('selectedId' => $this->input->post('liveSoumId')));
        $liveStreet = $this->address->getData_model(array('selectedId' => $this->input->post('liveStreetId')));

        $this->data = array(
            'register' => $this->input->post('register'),
            'social_insurance' => $this->input->post('socialInsurance'),
            'health_insurance' => $this->input->post('healthInsurance'),
            'family_name' => $this->input->post('familyName'),
            'fname' => $this->input->post('fname'),
            'lname' => $this->input->post('lname'),
            'sex' => $this->input->post('sex'),
            'birthday' => $this->input->post('birthday'),
            'pic' => ($this->input->post('hrPeoplePic') != '' ? $this->input->post('hrPeoplePic') : $this->input->post('hrPeopleOldPic')),
            'birth_city_id' => $this->input->post('birthCityId'),
            'birth_soum_id' => $this->input->post('birthSoumId'),
            'birth_street_id' => $this->input->post('birthStreetId'),
            'birth_address' => $this->input->post('birthAddress'),
            'nationality' => $this->input->post('nationality'),
            'social_origin' => $this->input->post('socialOrigin'),
            'live_city_id' => $this->input->post('liveCityId'),
            'live_soum_id' => $this->input->post('liveSoumId'),
            'live_street_id' => $this->input->post('liveStreetId'),
            'live_address' => $this->input->post('liveAddress'),
            'phone' => $this->input->post('phone'),
            'fax' => $this->input->post('fax'),
            'email' => $this->input->post('email'),
            'post_address' => $this->input->post('postAddress'),
            'special_people' => $this->input->post('specialPeople'),
            'special_phone' => $this->input->post('specialPhone'),
            'is_active' => $this->input->post('isActive'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'param' => json_encode(array(
                'birth' => array('city' => ($birthCity != false ? $birthCity->title : ''), 'soum' => ($birthSoum != false ? $birthSoum->title : ''), 'street' => ($birthStreet != false ? $birthStreet->title : '')),
                'live' => array('city' => ($liveCity != false ? $liveCity->title : ''), 'soum' => ($liveSoum != false ? $liveSoum->title : ''), 'street' => ($liveStreet != false ? $liveStreet->title : '')),
                'work' => array('department' => '', 'position' => '', 'rank' => '')
            )),
            'order_num' => $this->input->post('orderNum'),
            'status_id' => $this->input->post('statusId'));
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'hr_people', $this->data)) {

            /*
             * Tab 1
             * */

            $this->deleteInsertHrPeopleFamilyMember_model(array('peopleId' => $this->input->post('id')));

            $this->deleteInsertHrPeopleRelationMember_model(array('peopleId' => $this->input->post('id')));

            $this->deleteInsertHrPeopleWork_model(array('peopleId' => $this->input->post('id')));

            /*
             * Tab 2
             * */

            $this->deleteInsertHrPeopleEducation_model(array('peopleId' => $this->input->post('id')));

            $this->deleteInsertHrPeopleEducationDoctor_model(array('peopleId' => $this->input->post('id')));

            /*
             * Tab 3
             * */

            $this->deleteInsertHrPeopleCourse_model(array('peopleId' => $this->input->post('id')));

            $this->deleteInsertHrPeoplePositionRank_model(array('peopleId' => $this->input->post('id')));

            $this->deleteInsertHrPeopleEducationRank_model(array('peopleId' => $this->input->post('id')));

            $this->deleteInsertHrPeopleLanguage_model(array('peopleId' => $this->input->post('id')));

            /*
             * Tab 4
             * */
            $this->deleteInsertHrPeopleAward_model(array('peopleId' => $this->input->post('id')));

            /*
             * Tab 5
             * */

            /*
             * Tab 6
             * */

            $this->deleteInsertHrPeopleConviction_model(array('peopleId' => $this->input->post('id')));

//            $this->deleteInsertHrPeopleWorkHistory_model(array('peopleId' => $this->input->post('id')));
//
            //



            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {
        $row = $this->getData_model(array('selectedId' => $this->input->post('id')));

        $this->simage->removeUploadImage_model(array('uploadImage' => $row->pic, 'uploadPath' => '.' . UPLOADS_USER_PATH));

        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'hr_people')) {

            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function updateHrPeopleParam_model($param = array('modId' => 0, 'contId' => 0, 'expertId' => array())) {

        $this->expertString = '';

        $this->db->where('mod_id', $param['modId']);
        $this->db->where('cont_id', $param['contId']);
        $this->db->delete($this->db->dbprefix . 'nifs_expert');

        if (is_array($param['expertId']) and $param['expertId']['0'] > 0) {

            foreach ($param['expertId'] as $value) {

                if ($value > 0) {
                    $this->nifsExpertData = $this->getData_model(array('selectedId' => $value));

                    if ($this->nifsExpertData) {
                        if ($this->nifsExpertData->is_active == 1) {
                            $this->expertString .= $this->nifsExpertData->full_name . ', ';
                        } else {
                            $this->expertString .= $this->nifsExpertData->fname . ': ' . $param['extraExpertValue'] . ', ';
                        }
                    }

                    $this->data = array(
                        array(
                            'id' => getUID('nifs_expert'),
                            'mod_id' => $param['modId'],
                            'cont_id' => $param['contId'],
                            'expert_id' => $value,
                            'department_id' => $this->nifsExpertData->department_id,
                            'created_date' => date('Y-m-d H:i:s'),
                            'modified_date' => date('Y-m-d H:i:s'),
                            'created_user_id' => $this->session->adminUserId,
                            'modified_user_id' => $this->session->adminUserId
                    ));

                    if (!$this->db->insert_batch($this->db->dbprefix . 'nifs_expert', $this->data)) {
                        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
                    }
                }
            }
            $this->expertString = rtrim($this->expertString, ', ');


            $this->db->where('id', $param['contId']);

            if ($this->db->update($this->db->dbprefix . $param['table'], array('expert' => $this->expertString))) {
                return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
            }
        } else if (is_array($param['expertId']) and $param['expertId']['0'] == 0) {

            $this->data = array(
                array(
                    'id' => getUID('nifs_expert'),
                    'mod_id' => $param['modId'],
                    'cont_id' => $param['contId'],
                    'expert_id' => 0,
                    'department_id' => $this->session->adminDepartmentId,
                    'created_date' => date('Y-m-d H:i:s'),
                    'modified_date' => date('Y-m-d H:i:s'),
                    'created_user_id' => $this->session->adminUserId,
                    'modified_user_id' => $this->session->adminUserId
            ));

            if ($this->db->insert_batch($this->db->dbprefix . 'nifs_expert', $this->data)) {

                $this->db->where('id', $param['contId']);
                $this->db->update($this->db->dbprefix . $param['table'], array('expert' => ''));

                return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
            }
        }

        return array('status' => 'error', 'message' => 'Алдаа гарлаа...');
    }

    public function searchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('lname')) {
            $this->string .= '<span class="label label-default label-rounded">Эцэг, эхийн нэр: ' . $this->input->get('lname') . '</span>';
            $this->string .= form_hidden('lname', $this->input->get('lname'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('fname')) {
            $this->string .= '<span class="label label-default label-rounded">Өөрийн нэр: ' . $this->input->get('fname') . '</span>';
            $this->string .= form_hidden('fname', $this->input->get('fname'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('sex') == 1) {
            $this->string .= '<span class="label label-default label-rounded">Хүйс: Эрэгтэй</span>';
            $this->string .= form_hidden('sex', $this->input->get('sex'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('sex') == 2) {
            $this->string .= '<span class="label label-default label-rounded">Хүйс: Эмэгтэй</span>';
            $this->string .= form_hidden('sex', $this->input->get('sex'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('birthday') != '') {
            $this->string .= '<span class="label label-default label-rounded">Төрсөн он, сар, өдөр: ' . $this->input->get('birthday') . '</span>';
            $this->string .= form_hidden('birthday', $this->input->get('birthday'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('liveCityId') != '' and $this->input->get('liveCityId') != 0) {
            $addressData = $this->address->getData_model(array('selectedId' => $this->input->get('liveCityId')));
            $this->string .= '<span class="label label-default label-rounded">Хот, аймаг: ' . $addressData->title . '</span>';
            $this->string .= form_hidden('liveCityId', $this->input->get('liveCityId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('liveSoumId') != '' and $this->input->get('liveSoumId') != 0) {
            $addressData = $this->address->getData_model(array('selectedId' => $this->input->get('liveSoumId')));
            $this->string .= '<span class="label label-default label-rounded">Сум, дүүрэг: ' . $addressData->title . '</span>';
            $this->string .= form_hidden('liveSoumId', $this->input->get('liveSoumId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('liveStreetId') != '' and $this->input->get('liveStreetId') != 0) {
            $addressData = $this->address->getData_model(array('selectedId' => $this->input->get('liveStreetId')));
            $this->string .= '<span class="label label-default label-rounded">Баг, хороо: ' . $addressData->title . '</span>';
            $this->string .= form_hidden('liveStreetId', $this->input->get('liveStreetId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('liveAddress') != '') {
            $this->string .= '<span class="label label-default label-rounded">Хаяг: ' . $this->input->get('liveAddress') . '</span>';
            $this->string .= form_hidden('liveAddress', $this->input->get('liveAddress'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('familyName') != '') {
            $this->string .= '<span class="label label-default label-rounded">Ургийн овог: ' . $this->input->get('familyName') . '</span>';
            $this->string .= form_hidden('familyName', $this->input->get('familyName'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('nationality') != '') {
            $this->string .= '<span class="label label-default label-rounded">Үндэс угсаа: ' . $this->input->get('nationality') . '</span>';
            $this->string .= form_hidden('nationality', $this->input->get('nationality'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('socialOrigin') != '') {
            $this->string .= '<span class="label label-default label-rounded">Нийгмийн гарал: ' . $this->input->get('socialOrigin') . '</span>';
            $this->string .= form_hidden('socialOrigin', $this->input->get('socialOrigin'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('register') != '') {
            $this->string .= '<span class="label label-default label-rounded">Регистрийн дугаар: ' . $this->input->get('register') . '</span>';
            $this->string .= form_hidden('register', $this->input->get('register'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('socialInsurance') != '') {
            $this->string .= '<span class="label label-default label-rounded">Нийгмийн даатгалын дэвтэр: ' . $this->input->get('socialInsurance') . '</span>';
            $this->string .= form_hidden('socialInsurance', $this->input->get('socialInsurance'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('healthInsurance') != '') {
            $this->string .= '<span class="label label-default label-rounded">Эрүүл мэндийн дэвтэр: ' . $this->input->get('healthInsurance') . '</span>';
            $this->string .= form_hidden('healthInsurance', $this->input->get('healthInsurance'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('phone') != '') {
            $this->string .= '<span class="label label-default label-rounded">Утас: ' . $this->input->get('phone') . '</span>';
            $this->string .= form_hidden('phone', $this->input->get('phone'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('fax') != '') {
            $this->string .= '<span class="label label-default label-rounded">Факс: ' . $this->input->get('fax') . '</span>';
            $this->string .= form_hidden('fax', $this->input->get('fax'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('email') != '') {
            $this->string .= '<span class="label label-default label-rounded">Мэйл хаяг: ' . $this->input->get('email') . '</span>';
            $this->string .= form_hidden('email', $this->input->get('email'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('postAddress') != '') {
            $this->string .= '<span class="label label-default label-rounded">Шуудангийн хаяг: ' . $this->input->get('postAddress') . '</span>';
            $this->string .= form_hidden('postAddress', $this->input->get('postAddress'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('specialPeople') != '') {
            $this->string .= '<span class="label label-default label-rounded">Онцгой шаардлага гарвал харилцах хүний нэр: ' . $this->input->get('specialPeople') . '</span>';
            $this->string .= form_hidden('specialPeople', $this->input->get('specialPeople'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('specialPhone') != '') {
            $this->string .= '<span class="label label-default label-rounded">Онцгой шаардлага гарвал харилцах хүний утас: ' . $this->input->get('specialPhone') . '</span>';
            $this->string .= form_hidden('specialPhone', $this->input->get('specialPhone'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('departmentId') != 0 and $this->input->get('departmentId') != '') {
            $hrPeopleDepartmentData = $this->hrPeopleDepartment->getData_model(array('selectedId' => $this->input->get('departmentId')));
            $this->string .= '<span class="label label-default label-rounded">Одоо ажиллаж буй газар, хэлтэс: ' . $hrPeopleDepartmentData->title . '</span>';
            $this->string .= form_hidden('departmentId', $this->input->get('departmentId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('positionId') != 0 and $this->input->get('positionId') != '') {
            $hrPeoplePositionData = $this->hrPeoplePosition->getData_model(array('selectedId' => $this->input->get('positionId')));
            $this->string .= '<span class="label label-default label-rounded">Албан тушаал: ' . $hrPeoplePositionData->title . '</span>';
            $this->string .= form_hidden('positionId', $this->input->get('positionId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('rankId') != 0 and $this->input->get('rankId') != '') {
            $hrPeopleRankData = $this->hrPeopleRank->getData_model(array('selectedId' => $this->input->get('rankId')));
            $this->string .= '<span class="label label-default label-rounded">Цол: ' . $hrPeopleRankData->title . '</span>';
            $this->string .= form_hidden('rankId', $this->input->get('rankId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('statusId') != 0 and $this->input->get('statusId') != '') {
            $statusData = $this->status->getData_model(array('selectedId' => $this->input->get('statusId')));
            $this->string .= '<span class="label label-default label-rounded">Төлөв: ' . $statusData->title . '</span>';
            $this->string .= form_hidden('statusId', $this->input->get('statusId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->string .= form_hidden('keyword', $this->input->get('keyword'));
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            $this->string .= ' <a href="javascript:;" onclick="_initHrPeople({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return ($this->showResetBtn ? '<div class="_search-result-inner"><form action="javascript:;" id="form-nifs-crime"> Хайлтын үр дүн: ' . $this->string . '</form></div>' : '');
    }

    public function listsHrPeopleFamilyMember_model($param = array()) {
        $html = $string = $disabled = '';

        if (isset($param['readonly']) and strtolower($param['readonly']) == 'true') {
            $string .= ' disabled="true"';
            $disabled = true;
        }

        $query = $this->db->query('
            SELECT 
                HPFM.id,
                HPFM.relation_id,
                HPFM.fl_name,
                HPFM.birth_year,
                HPFM.birth_address,
                HPFM.live_work,
                HPFM.is_active
            FROM `gaz_hr_people_family_member` AS HPFM
            WHERE HPFM.people_id = ' . $param['peopleId']);

        $html .= '<fieldset class="stepy-step">';
        $html .= '<legend>';
        $html .= '<span class="text-semibold">Гэр бүлийн байдал</span> <span class="_description">(Зөвхөн гэр бүлийн бүртгэлд байгаа хүмүүсийг бичнэ)</span>';
        if (!isset($param['readonly'])) {
            $html .= '<span class="pull-right">' . form_button('searchHr', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Нэмэх</span>', 'class="btn btn-primary btn-xs" onclick="_addHrPeopleFamilyMember({elem: this});"', 'button') . '</span>';
        }
        $html .= '<div class="clearfix"></div>';
        $html .= '</legend>';

        $html .= '<div class="table-responsive">';
        $html .= '<table class="_form-table">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th style="width:120px;">Таны юу болох</th>';
        $html .= '<th>Гэр бүлийн гишүүдийн эцэг/эх/-ийн болон өөрийн нь нэр</th>';
        $html .= '<th style="width:100px;">Төрсөн он</th>';
        $html .= '<th style="width:200px;">Төрсөн аймаг, хот сум дүүрэг</th>';
        $html .= '<th style="width:200px;">Одоо эрхэлж буй ажил</th>';
        if (!isset($param['readonly'])) {
            $html .= '<th style="width:50px;" class="text-center"><i class="icon-cog7"></i></th>';
        }
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody data-link="row" class="rowlink lists-hr-people-family-member">';

        $shrPeopleRelationData = $this->shrPeopleRelation->getListData_model();

        if ($query->num_rows() > 0) {

            $i = 1;
            foreach ($query->result() as $key => $row) {

                $html .= '<tr data-number="' . $i . '">';
                $html .= '<td class="_control"><input type="hidden" name="familyNumber[]" value="number' . $i . '">' . $this->shrPeopleRelation->controlHrPeopleRelationOutDataDropdown_model(array('name' => 'familyRelationId[]', 'data' => $shrPeopleRelationData, 'selectedId' => $row->relation_id, 'disabled' => $disabled)) . '</td>';
                $html .= '<td class="_control"><input type="text" name="familyFLName[]" class="_control_input" value="' . $row->fl_name . '" ' . $string . '></td>';
                $html .= '<td class="_control">' . $this->controlBirthYearDropdown_model(array('name' => 'familyBirthYear[]', 'selectedId' => $row->birth_year, 'disabled' => $disabled)) . '</td>';
                $html .= '<td class="_control"><input type="text" name="familyBirthAddress[]" class="_control_input" value="' . $row->birth_address . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="familyLiveWork[]" class="_control_input" value="' . $row->live_work . '" ' . $string . '></td>';
                if (!isset($param['readonly'])) {
                    $html .= '<td class="text-center">';
                    $html .= '<ul class="icons-list">';
                    $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
                    $html .= '</ul>';
                    $html .= '</td>';
                }
                $html .= '</tr>';
                $i++;
            }
        } else {

            $html .= '<tr data-number="1">';
            $html .= '<td class="_control"><input type="hidden" name="familyNumber[]" value="number1">' . $this->shrPeopleRelation->controlHrPeopleRelationOutDataDropdown_model(array('name' => 'familyRelationId[]', 'data' => $shrPeopleRelationData, 'selectedId' => 0)) . '</td>';
            $html .= '<td class="_control"><input type="text" name="familyFLName[]" class="_control_input"></td>';
            $html .= '<td class="_control">' . $this->controlBirthYearDropdown_model(array('name' => 'familyBirthYear[]', 'selectedId' => 0)) . '</td>';
            $html .= '<td class="_control"><input type="text" name="familyBirthAddress[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="familyLiveWork[]" class="_control_input"></td>';

            $html .= '<td class="text-center">';
            $html .= '<ul class="icons-list">';
            $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
            $html .= '</ul>';
            $html .= '</td>';
            $html .= '</tr>';

            $html .= '<tr data-number="2">';
            $html .= '<td class="_control"><input type="hidden" name="familyNumber[]" value="number2">' . $this->shrPeopleRelation->controlHrPeopleRelationOutDataDropdown_model(array('name' => 'familyRelationId[]', 'data' => $shrPeopleRelationData, 'selectedId' => 0)) . '</td>';
            $html .= '<td class="_control"><input type="text" name="familyFLName[]" class="_control_input"></td>';
            $html .= '<td class="_control">' . $this->controlBirthYearDropdown_model(array('name' => 'familyBirthYear[]', 'selectedId' => 0)) . '</td>';
            $html .= '<td class="_control"><input type="text" name="familyBirthAddress[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="familyLiveWork[]" class="_control_input"></td>';

            $html .= '<td class="text-center">';
            $html .= '<ul class="icons-list">';
            $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
            $html .= '</ul>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';

        $html .= '</fieldset>';

        return $html;
    }

    public function deleteInsertHrPeopleFamilyMember_model($param = array()) {

        $this->db->where('people_id', $param['peopleId']);
        $this->db->delete($this->db->dbprefix . 'hr_people_family_member');

        if ($this->input->post('familyNumber')) {
            foreach ($this->input->post('familyNumber') as $key => $value) {
                $data = array(
                    array(
                        'id' => getUID('hr_people_family_member'),
                        'people_id' => $param['peopleId'],
                        'relation_id' => $this->input->post('familyRelationId[' . $key . ']'),
                        'fl_name' => $this->input->post('familyFLName[' . $key . ']'),
                        'birth_year' => $this->input->post('familyBirthYear[' . $key . ']'),
                        'birth_address' => $this->input->post('familyBirthAddress[' . $key . ']'),
                        'live_work' => $this->input->post('familyLiveWork[' . $key . ']'),
                        'is_active' => 1,
                        'created_date' => date('Y-m-d H:i:s'),
                        'modified_date' => date('Y-m-d H:i:s'),
                        'created_user_id' => $this->session->adminUserId,
                        'modified_user_id' => $this->session->adminUserId,
                        'order_num' => getOrderNum(array('table' => 'hr_people_family_member', 'field' => 'order_num'))));

                $this->db->insert_batch($this->db->dbprefix . 'hr_people_family_member', $data);
            }
        }
    }

    public function listsHrPeopleRelationMember_model($param = array()) {
        $html = $string = $disabled = '';

        if (isset($param['readonly']) and strtolower($param['readonly']) == 'true') {
            $string .= ' disabled="true"';
            $disabled = true;
        }

        $query = $this->db->query('
            SELECT 
                RM.id,
                RM.relation_id,
                RM.fl_name,
                RM.birth_year,
                RM.birth_address,
                RM.live_work,
                RM.is_active
            FROM `gaz_hr_people_relation_member` AS RM
            WHERE RM.people_id = ' . $param['peopleId']);

        $html .= '<fieldset class="stepy-step">';
        $html .= '<legend>';
        $html .= '<span class="text-semibold">Садан төрлийн байдал</span> <span class="_description">(Таны эцэг, эх, эгч, дүү, өрх тусгаарласан хүүхэд болон таны эхнэр /нөхөр/-ийн эцэг, эхийг оруулна)</span>';
        if (!isset($param['readonly'])) {
            $html .= '<span class="pull-right">' . form_button('addHrPeopleRelationMember', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Нэмэх</span>', 'class="btn btn-primary btn-xs" onclick="_addHrPeopleRelationMember({elem: this});"', 'button') . '</span>';
        }
        $html .= '<div class="clearfix"></div>';
        $html .= '</legend>';

        $html .= '<div class="table-responsive">';
        $html .= '<table class="_form-table">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th style="width:105px;">Таны юу болох</th>';
        $html .= '<th>Садан төрлийн хүмүүсийн эцэг/эх/-ийн болон өөрийн нь нэр</th>';
        $html .= '<th style="width:80px;">Төрсөн он</th>';
        $html .= '<th style="width:200px;">Төрсөн аймаг, хот сум дүүрэг</th>';
        $html .= '<th style="width:200px;">Одоо эрхэлж буй ажил</th>';
        if (!isset($param['readonly'])) {
            $html .= '<th style="width:50px;" class="text-center"><i class="icon-cog7"></i></th>';
        }
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody data-link="row" class="rowlink lists-hr-people-relation-member">';

        $shrPeopleRelationData = $this->shrPeopleRelation->getListData_model();

        if ($query->num_rows() > 0) {

            $i = 1;
            foreach ($query->result() as $key => $row) {
                $html .= '<tr data-number="' . $i . '">';
                $html .= '<td class="_control"><input type="hidden" name="relationNumber[]" value="number' . $i . '">' . $this->shrPeopleRelation->controlHrPeopleRelationOutDataDropdown_model(array('name' => 'relationRelationId[]', 'data' => $shrPeopleRelationData, 'selectedId' => $row->relation_id, 'disabled' => $disabled)) . '</td>';
                $html .= '<td class="_control"><input type="text" name="relationFLName[]" class="_control_input" value="' . $row->fl_name . '" ' . $string . '></td>';
                $html .= '<td class="_control">' . $this->controlBirthYearDropdown_model(array('name' => 'relationBirthYear[]', 'selectedId' => $row->birth_year, 'disabled' => $disabled)) . '</td>';
                $html .= '<td class="_control"><input type="text" name="relationBirthAddress[]" class="_control_input" value="' . $row->birth_address . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="relationLiveWork[]" class="_control_input" value="' . $row->live_work . '" ' . $string . '></td>';
                if (!isset($param['readonly'])) {
                    $html .= '<td class="text-center">';
                    $html .= '<ul class="icons-list">';
                    $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
                    $html .= '</ul>';
                    $html .= '</td>';
                }
                $html .= '</tr>';
                $i++;
            }
        } else {

            $html .= '<tr data-number="1">';
            $html .= '<td class="_control"><input type="hidden" name="relationNumber[]" value="number1">' . $this->shrPeopleRelation->controlHrPeopleRelationOutDataDropdown_model(array('name' => 'relationRelationId[]', 'data' => $shrPeopleRelationData, 'selectedId' => 0)) . '</td>';
            $html .= '<td class="_control"><input type="text" name="relationFLName[]" class="_control_input"></td>';
            $html .= '<td class="_control">' . $this->controlBirthYearDropdown_model(array('name' => 'relationBirthYear[]', 'selectedId' => 0)) . '</td>';
            $html .= '<td class="_control"><input type="text" name="relationBirthAddress[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="relationLiveWork[]" class="_control_input"></td>';

            $html .= '<td class="text-center">';
            $html .= '<ul class="icons-list">';
            $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
            $html .= '</ul>';
            $html .= '</td>';
            $html .= '</tr>';

            $html .= '<tr data-number="2">';
            $html .= '<td class="_control"><input type="hidden" name="relationNumber[]" value="number2">' . $this->shrPeopleRelation->controlHrPeopleRelationOutDataDropdown_model(array('name' => 'relationRelationId[]', 'data' => $shrPeopleRelationData, 'selectedId' => 0)) . '</td>';
            $html .= '<td class="_control"><input type="text" name="relationFLName[]" class="_control_input"></td>';
            $html .= '<td class="_control">' . $this->controlBirthYearDropdown_model(array('name' => 'relationBirthYear[]', 'selectedId' => 0)) . '</td>';
            $html .= '<td class="_control"><input type="text" name="relationBirthAddress[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="relationLiveWork[]" class="_control_input"></td>';

            $html .= '<td class="text-center">';
            $html .= '<ul class="icons-list">';
            $html .= '<li onclick="_removeTableFormRow({elem: this});" title="Устгах"><span data-action="delete"></span></li>';
            $html .= '</ul>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '</fieldset>';
        return $html;
    }

    public function deleteInsertHrPeopleRelationMember_model($param = array()) {

        $this->db->where('people_id', $param['peopleId']);
        $this->db->delete($this->db->dbprefix . 'hr_people_relation_member');

        if ($this->input->post('relationNumber')) {
            foreach ($this->input->post('relationNumber') as $key => $value) {
                $data = array(
                    array(
                        'id' => getUID('hr_people_relation_member'),
                        'people_id' => $param['peopleId'],
                        'relation_id' => $this->input->post('relationRelationId[' . $key . ']'),
                        'fl_name' => $this->input->post('relationFLName[' . $key . ']'),
                        'birth_year' => $this->input->post('relationBirthYear[' . $key . ']'),
                        'birth_address' => $this->input->post('relationBirthAddress[' . $key . ']'),
                        'live_work' => $this->input->post('relationLiveWork[' . $key . ']'),
                        'is_active' => 1,
                        'created_date' => date('Y-m-d H:i:s'),
                        'modified_date' => date('Y-m-d H:i:s'),
                        'created_user_id' => $this->session->adminUserId,
                        'modified_user_id' => $this->session->adminUserId,
                        'order_num' => getOrderNum(array('table' => 'hr_people_relation_member', 'field' => 'order_num'))));
                $this->db->insert_batch($this->db->dbprefix . 'hr_people_relation_member', $data);
            }
        }
    }

    public function listsHrPeopleEducation_model($param = array()) {
        $html = $string = $disabled = '';

        if (isset($param['readonly']) and strtolower($param['readonly']) == 'true') {
            $string .= ' disabled="true"';
            $disabled = 'true';
        }

        $query = $this->db->query('
            SELECT 
                HRE.id,
                HRE.people_id,
                HRE.school_id,
                HRE.school_name,
                HRE.in_date,
                HRE.out_date,
                HRE.education_rank_id,
                HRE.profession,
                HRE.diplom_number,
                HRE.diplom_description,
                HRE.is_active,
                HRE.created_date,
                HRE.modified_date,
                HRE.created_user_id,
                HRE.modified_user_id
            FROM `gaz_hr_people_education` AS HRE
            WHERE HRE.people_id = ' . $param['peopleId']);

        $html .= '<fieldset class="stepy-step">';
        $html .= '<legend>';
        $html .= '<span class="text-semibold">Боловсрол</span> <span class="_description"></span>';
        if (!isset($param['readonly'])) {
            $html .= '<span class="pull-right">' . form_button('addHrPeopleEducation', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Нэмэх</span>', 'class="btn btn-primary btn-xs" onclick="_addHrPeopleEducation({elem: this});"', 'button') . '</span>';
        }
        $html .= '<div class="clearfix"></div>';
        $html .= '</legend>';

        $html .= '<div class="table-responsive">';
        $html .= '<table class="_form-table">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Сургуулийн нэр</th>';
        $html .= '<th style="width:100px;" class="text-center">Орсон он, сар, өдөр</th>';
        $html .= '<th style="width:100px;" class="text-center">Төгссөн он, сар, өдөр</th>';
        $html .= '<th style="width:152px;">Боловсрол</th>';
        $html .= '<th>Мэргэжил</th>';
        $html .= '<th style="width:150px;">Диплом №</th>';
        $html .= '<th>Сэдэв</th>';
        if (!isset($param['readonly'])) {
            $html .= '<th style="width:50px;" class="text-center"><i class="icon-cog7"></i></th>';
        }
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody data-link="row" class="rowlink lists-hr-people-education">';

        $hrPeopleEducationRankMasterData = $this->hrPeopleEducationRankMasterData->getListData_model();

        if ($query->num_rows() > 0) {

            $i = 1;
            foreach ($query->result() as $key => $row) {

                $html .= '<tr data-number="' . $i . '">';
                $html .= '<td class="_control"><input type="hidden" name="educationAutoNumber[]" value="number' . $i . '"><input type="text" name="educationSchoolName[]" class="_control_input" value="' . $row->school_name . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="educationInDate[]" class="_control_input init-date" value="' . $row->in_date . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="educationOutDate[]" class="_control_input init-date" value="' . $row->out_date . '" ' . $string . '></td>';
                $html .= '<td class="_control">' . $this->hrPeopleEducationRankMasterData->controlHrPeopleEducationRankMasterOutDataDropDown_model(array('data' => $hrPeopleEducationRankMasterData, 'name' => 'educationRankId[]', 'selectedId' => $row->education_rank_id, 'disabled' => $disabled)) . '</td>';
                $html .= '<td class="_control"><input type="text" name="educationProfession[]" class="_control_input" value="' . $row->profession . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="educationDiplomNumber[]" class="_control_input" value="' . $row->diplom_number . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="educationDiplomDescription[]" class="_control_input" value="' . $row->diplom_description . '" ' . $string . '></td>';
                if (!isset($param['readonly'])) {
                    $html .= '<td class="text-center">';
                    $html .= '<ul class="icons-list">';
                    $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
                    $html .= '</ul>';
                    $html .= '</td>';
                }
                $html .= '</tr>';
                $i++;
            }
        } else {

            $html .= '<tr data-number="1">';
            $html .= '<td class="_control"><input type="hidden" name="educationAutoNumber[]" value="number1"><input type="text" name="educationSchoolName[]" class="_control_input" ' . $string . '></td>';
            $html .= '<td class="_control"><input type="text" name="educationInDate[]" class="_control_input init-date" ' . $string . '></td>';
            $html .= '<td class="_control"><input type="text" name="educationOutDate[]" class="_control_input init-date" ' . $string . '></td>';
            $html .= '<td class="_control">' . $this->hrPeopleEducationRankMasterData->controlHrPeopleEducationRankMasterOutDataDropDown_model(array('data' => $hrPeopleEducationRankMasterData, 'name' => 'educationRankId[]', 'selectedId' => 0, 'disabled' => $disabled)) . '</td>';
            $html .= '<td class="_control"><input type="text" name="educationProfession[]" class="_control_input" ' . $string . '></td>';
            $html .= '<td class="_control"><input type="text" name="educationDiplomNumber[]" class="_control_input" ' . $string . '></td>';
            $html .= '<td class="_control"><input type="text" name="educationDiplomDescription[]" class="_control_input" ' . $string . '></td>';
            if (!isset($param['readonly'])) {
                $html .= '<td class="text-center">';
                $html .= '<ul class="icons-list">';
                $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
                $html .= '</ul>';
                $html .= '</td>';
            }
            $html .= '</tr>';

            $html .= '<tr data-number="2">';
            $html .= '<td class="_control"><input type="hidden" name="educationAutoNumber[]" value="number2"><input type="text" name="educationSchoolName[]" class="_control_input" ' . $string . '></td>';
            $html .= '<td class="_control"><input type="text" name="educationInDate[]" class="_control_input init-date" ' . $string . '></td>';
            $html .= '<td class="_control"><input type="text" name="educationOutDate[]" class="_control_input init-date" ' . $string . '></td>';
            $html .= '<td class="_control">' . $this->hrPeopleEducationRankMasterData->controlHrPeopleEducationRankMasterOutDataDropDown_model(array('data' => $hrPeopleEducationRankMasterData, 'name' => 'educationRankId[]', 'selectedId' => 0, 'disabled' => $disabled)) . '</td>';
            $html .= '<td class="_control"><input type="text" name="educationProfession[]" class="_control_input" ' . $string . '></td>';
            $html .= '<td class="_control"><input type="text" name="educationDiplomNumber[]" class="_control_input" ' . $string . '></td>';
            $html .= '<td class="_control"><input type="text" name="educationDiplomDescription[]" class="_control_input" ' . $string . '></td>';
            if (!isset($param['readonly'])) {
                $html .= '<td class="text-center">';
                $html .= '<ul class="icons-list">';
                $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
                $html .= '</ul>';
                $html .= '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '</fieldset>';

        return $html;
    }

    public function deleteInsertHrPeopleEducation_model($param = array()) {

        $this->db->where('people_id', $param['peopleId']);
        $this->db->delete($this->db->dbprefix . 'hr_people_education');
        if ($this->input->post('educationAutoNumber')) {
            foreach ($this->input->post('educationAutoNumber') as $key => $value) {

                $data = array(
                    array(
                        'id' => getUID('hr_people_education'),
                        'people_id' => $param['peopleId'],
                        'school_id' => 0,
                        'school_name' => $this->input->post('educationSchoolName[' . $key . ']'),
                        'in_date' => $this->input->post('educationInDate[' . $key . ']'),
                        'out_date' => $this->input->post('educationOutDate[' . $key . ']'),
                        'education_rank_id' => $this->input->post('educationRankId[' . $key . ']'),
                        'profession' => $this->input->post('educationProfession[' . $key . ']'),
                        'diplom_number' => $this->input->post('educationDiplomNumber[' . $key . ']'),
                        'diplom_description' => $this->input->post('educationDiplomDescription[' . $key . ']'),
                        'is_active' => 1,
                        'created_date' => date('Y-m-d H:i:s'),
                        'modified_date' => date('Y-m-d H:i:s'),
                        'created_user_id' => $this->session->adminUserId,
                        'modified_user_id' => $this->session->adminUserId,
                        'order_num' => getOrderNum(array('table' => 'hr_people_education', 'field' => 'order_num'))));

                $this->db->insert_batch($this->db->dbprefix . 'hr_people_education', $data);
            }
        }
    }

    public function listsHrPeopleEducationDoctor_model($param = array()) {
        $html = $string = $disabled = '';

        if (isset($param['readonly']) and strtolower($param['readonly']) == 'true') {
            $string .= ' disabled="true"';
            $disabled = 'true';
        }

        $query = $this->db->query('
            SELECT 
                ED.id,
                ED.people_id,
                ED.rank,
                ED.issue_palace,
                ED.issue_date,
                ED.diplom_number,
                ED.description,
                ED.is_active,
                ED.created_date,
                ED.modified_date,
                ED.created_user_id,
                ED.modified_user_id,
                ED.order_num
            FROM `gaz_hr_people_education_doctor` AS ED
            WHERE ED.people_id = ' . $param['peopleId']);

        $html .= '<fieldset class="stepy-step">';
        $html .= '<legend>';
        $html .= '<span class="text-semibold">Боловсролын докторын болон шинжлэх ухааны докторын зэрэг</span> <span class="_description"></span>';
        if (!isset($param['readonly'])) {
            $html .= '<span class="pull-right">' . form_button('addHrPeopleEducationDoctor', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Нэмэх</span>', 'class="btn btn-primary btn-xs" onclick="_addHrPeopleEducationDoctor({elem: this});"', 'button') . '</span>';
        }
        $html .= '<div class="clearfix"></div>';
        $html .= '</legend>';

        $html .= '<div class="table-responsive">';
        $html .= '<table class="_form-table">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th style="width:120px;">Зэрэг</th>';
        $html .= '<th>Хамгаалсан газар</th>';
        $html .= '<th style="width:100px;">Он, сар</th>';
        $html .= '<th style="width:100px;">Диплом №</th>';
        $html .= '<th>Сэдэв</th>';
        if (!isset($param['readonly'])) {
            $html .= '<th style="width:50px;" class="text-center"><i class="icon-cog7"></i></th>';
        }
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody data-link="row" class="rowlink lists-hr-people-education-doctor">';

        if ($query->num_rows() > 0) {

            $i = 1;
            foreach ($query->result() as $key => $row) {
                $html .= '<tr data-number="' . $i . '">';
                $html .= '<td class="_control"><input type="hidden" name="educationDoctorNumber[]" value="number' . $i . '"><input type="text" name="educationDoctorRank[]" class="_control_input" value="' . $row->rank . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="educationDoctorIssuePalace[]" class="_control_input" value="' . $row->issue_palace . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="educationDoctorIssueDate[]" class="_control_input init-date" value="' . $row->issue_date . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="educationDoctorDiplomNumber[]" class="_control_input" value="' . $row->diplom_number . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="educationDoctorDescription[]" class="_control_input" value="' . $row->description . '" ' . $string . '></td>';
                if (!isset($param['readonly'])) {
                    $html .= '<td class="text-center">';
                    $html .= '<ul class="icons-list">';
                    $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
                    $html .= '</ul>';
                    $html .= '</td>';
                }
                $html .= '</tr>';
                $i++;
            }
        } else {

            $html .= '<tr data-number="1">';
            $html .= '<td class="_control"><input type="hidden" name="educationDoctorNumber[]" value="number1"><input type="text" name="educationDoctorRank[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="educationDoctorIssuePalace[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="educationDoctorIssueDate[]" class="_control_input init-date"></td>';
            $html .= '<td class="_control"><input type="text" name="educationDoctorDiplomNumber[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="educationDoctorDescription[]" class="_control_input"></td>';
            $html .= '<td class="text-center">';
            $html .= '<ul class="icons-list">';
            $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
            $html .= '</ul>';
            $html .= '</td>';
            $html .= '</tr>';

            $html .= '<tr data-number="2">';
            $html .= '<td class="_control"><input type="hidden" name="educationDoctorNumber[]" value="number2"><input type="text" name="educationDoctorRank[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="educationDoctorIssuePalace[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="educationDoctorIssueDate[]" class="_control_input init-date"></td>';
            $html .= '<td class="_control"><input type="text" name="educationDoctorDiplomNumber[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="educationDoctorDescription[]" class="_control_input"></td>';
            $html .= '<td class="text-center">';
            $html .= '<ul class="icons-list">';
            $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
            $html .= '</ul>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '</fieldset>';
        return $html;
    }

    public function deleteInsertHrPeopleEducationDoctor_model($param = array()) {

        $this->db->where('people_id', $param['peopleId']);
        $this->db->delete($this->db->dbprefix . 'hr_people_education_doctor');

        if ($this->input->post('educationDoctorNumber')) {
            foreach ($this->input->post('educationDoctorNumber') as $key => $value) {
                $data = array(
                    array(
                        'id' => getUID('hr_people_education_doctor'),
                        'people_id' => $param['peopleId'],
                        'rank' => $this->input->post('educationDoctorRank[' . $key . ']'),
                        'issue_palace' => $this->input->post('educationDoctorIssuePalace[' . $key . ']'),
                        'issue_date' => $this->input->post('educationDoctorIssueDate[' . $key . ']'),
                        'diplom_number' => $this->input->post('educationDoctorDiplomNumber[' . $key . ']'),
                        'description' => $this->input->post('educationDoctorDescription[' . $key . ']'),
                        'is_active' => 1,
                        'created_date' => date('Y-m-d H:i:s'),
                        'modified_date' => date('Y-m-d H:i:s'),
                        'created_user_id' => $this->session->adminUserId,
                        'modified_user_id' => $this->session->adminUserId,
                        'order_num' => getOrderNum(array('table' => 'hr_people_education_doctor', 'field' => 'order_num'))));

                $this->db->insert_batch($this->db->dbprefix . 'hr_people_education_doctor', $data);
            }
        }
    }

    public function listsHrPeopleCourse_model($param = array()) {

        $html = $string = $disabled = '';

        if (isset($param['readonly']) and strtolower($param['readonly']) == 'true') {
            $string .= ' disabled="true"';
            $disabled = 'true';
        }

        $query = $this->db->query('
            SELECT 
                HPC.id,
                HPC.people_id,
                HPC.organization_title,
                HPC.in_date,
                HPC.out_date,
                HPC.duration,
                HPC.about,
                HPC.info,
                HPC.is_active,
                HPC.created_date,
                HPC.modified_date,
                HPC.created_user_id,
                HPC.modified_user_id,
                HPC.order_num
            FROM `gaz_hr_people_course` AS HPC
            WHERE HPC.people_id = ' . $param['peopleId']);

        $html .= '<fieldset class="stepy-step">';
        $html .= '<legend>';
        $html .= '<span class="text-semibold">Мэргэшлийн бэлтгэл</span> <span class="_description"></span>';
        if (!isset($param['readonly'])) {
            $html .= '<span class="pull-right">' . form_button('addHrPeopleCourse', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Нэмэх</span>', 'class="btn btn-primary btn-xs" onclick="_addHrPeopleCourse({elem: this});"', 'button') . '</span>';
        }
        $html .= '<div class="clearfix"></div>';
        $html .= '</legend>';

        $html .= '<div class="table-responsive">';
        $html .= '<table class="_form-table">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th style="width:120px;">Хаана ямар байгууллагад</th>';
        $html .= '<th style="width:100px;">Эхэлсэн он сар өдөр</th>';
        $html .= '<th style="width:100px;">Дууссан он сар өдөр</th>';
        $html .= '<th>Хугацаа</th>';
        $html .= '<th>Ямар чиглэлээр</th>';
        $html .= '<th>Үнэмлэх, гэрчилгээ №, олгосон он, сар, өдөр</th>';
        if (!isset($param['readonly'])) {
            $html .= '<th style="width:50px;" class="text-center"><i class="icon-cog7"></i></th>';
        }
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody data-link="row" class="rowlink lists-hr-people-course">';

        if ($query->num_rows() > 0) {

            $i = 1;

            foreach ($query->result() as $key => $row) {

                $html .= '<tr data-number="' . $i . '">';
                $html .= '<td class="_control"><input type="hidden" name="courseAutoNumber[]" value="number' . $i . '"><input type="text" name="courseOrganizationTitle[]" class="_control_input" value="' . $row->organization_title . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="courseInDate[]" class="_control_input init-date" value="' . $row->in_date . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="courseOutDate[]" class="_control_input init-date" value="' . $row->out_date . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="courseDuration[]" class="_control_input" value="' . $row->duration . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="courseAbout[]" class="_control_input" value="' . $row->about . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="courseInfo[]" class="_control_input" value="' . $row->info . '" ' . $string . '></td>';
                if (!isset($param['readonly'])) {
                    $html .= '<td class="text-center">';
                    $html .= '<ul class="icons-list">';
                    $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
                    $html .= '</ul>';
                    $html .= '</td>';
                }
                $html .= '</tr>';
                $i++;
            }
        } else {

            $html .= '<tr data-number="1">';
            $html .= '<td class="_control"><input type="hidden" name="courseAutoNumber[]" value="number1"><input type="text" name="courseOrganizationTitle[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="courseInDate[]" class="_control_input init-date"></td>';
            $html .= '<td class="_control"><input type="text" name="courseOutDate[]" class="_control_input init-date"></td>';
            $html .= '<td class="_control"><input type="text" name="courseDuration[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="courseAbout[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="courseInfo[]" class="_control_input"></td>';
            $html .= '<td class="text-center">';
            $html .= '<ul class="icons-list">';
            $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
            $html .= '</ul>';
            $html .= '</td>';
            $html .= '</tr>';

            $html .= '<tr data-number="2">';
            $html .= '<td class="_control"><input type="hidden" name="courseAutoNumber[]" value="number2"><input type="text" name="courseOrganizationTitle[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="courseInDate[]" class="_control_input init-date"></td>';
            $html .= '<td class="_control"><input type="text" name="courseOutDate[]" class="_control_input init-date"></td>';
            $html .= '<td class="_control"><input type="text" name="courseDuration[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="courseAbout[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="courseInfo[]" class="_control_input"></td>';
            $html .= '<td class="text-center">';
            $html .= '<ul class="icons-list">';
            $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
            $html .= '</ul>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '</fieldset>';
        return $html;
    }

    public function deleteInsertHrPeopleCourse_model($param = array()) {

        $this->db->where('people_id', $param['peopleId']);
        $this->db->delete($this->db->dbprefix . 'hr_people_course');

        foreach ($this->input->post('courseAutoNumber') as $key => $value) {
            $data = array(
                array(
                    'id' => getUID('hr_people_course'),
                    'people_id' => $param['peopleId'],
                    'organization_title' => $this->input->post('courseOrganizationTitle[' . $key . ']'),
                    'in_date' => $this->input->post('courseInDate[' . $key . ']'),
                    'out_date' => $this->input->post('courseOutDate[' . $key . ']'),
                    'duration' => $this->input->post('courseDuration[' . $key . ']'),
                    'about' => $this->input->post('courseAbout[' . $key . ']'),
                    'info' => $this->input->post('courseInfo[' . $key . ']'),
                    'is_active' => 1,
                    'created_date' => date('Y-m-d H:i:s'),
                    'modified_date' => date('Y-m-d H:i:s'),
                    'created_user_id' => $this->session->adminUserId,
                    'modified_user_id' => $this->session->adminUserId,
                    'order_num' => getOrderNum(array('table' => 'hr_people_course', 'field' => 'order_num'))));
            $this->db->insert_batch($this->db->dbprefix . 'hr_people_course', $data);
        }
    }

    public function listsHrPeoplePositionRank_model($param = array()) {

        $html = $string = $disabled = '';

        if (isset($param['readonly']) and strtolower($param['readonly']) == 'true') {
            $string .= ' disabled="true"';
            $disabled = 'true';
        }

        $query = $this->db->query('
            SELECT 
                HPPR.id,
                HPPR.people_id,
                HPPR.rank_level,
                HPPR.rank_name,
                HPPR.document_info,
                HPPR.cert_number,
                HPPR.is_active,
                HPPR.created_date,
                HPPR.modified_date,
                HPPR.created_user_id,
                HPPR.modified_user_id,
                HPPR.order_num
            FROM `gaz_hr_people_position_rank` AS HPPR
            WHERE HPPR.people_id = ' . $param['peopleId']);

        $html .= '<fieldset class="stepy-step">';
        $html .= '<legend>';
        $html .= '<span class="text-semibold">Албан тушаалын зэрэг, дэв, цол</span> <span class="_description"></span>';
        if (!isset($param['readonly'])) {
            $html .= '<span class="pull-right">' . form_button('addHrPeoplePositionRank', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Нэмэх</span>', 'class="btn btn-primary btn-xs" onclick="_addHrPeoplePositionRank({elem: this});"', 'button') . '</span>';
        }
        $html .= '<div class="clearfix"></div>';
        $html .= '</legend>';

        $html .= '<div class="table-responsive">';
        $html .= '<table class="_form-table">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th style="width:120px;">Албан тушаалын ангилал зэрэглэл</th>';
        $html .= '<th>Зэрэг дэв цолны нэр</th>';
        $html .= '<th>Зарлиг, захирамж, тушаалын нэр он сар, өдөр дугаар</th>';
        $html .= '<th style="width:100px;">Үнэмлэхний дугаар</th>';
        if (!isset($param['readonly'])) {
            $html .= '<th style="width:50px;" class="text-center"><i class="icon-cog7"></i></th>';
        }
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody data-link="row" class="rowlink lists-hr-people-position-rank">';

        if ($query->num_rows() > 0) {

            $i = 1;

            foreach ($query->result() as $key => $row) {

                $html .= '<tr data-number="' . $i . '">';
                $html .= '<td class="_control"><input type="hidden" name="positionRankAutoNumber[]" value="number' . $i . '"><input type="text" name="positionRankRankLevel[]" class="_control_input" value="' . $row->rank_level . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="positionRankRankName[]" class="_control_input" value="' . $row->rank_name . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="positionRankDocumentInfo[]" class="_control_input" value="' . $row->document_info . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="positionRankCertNumber[]" class="_control_input" value="' . $row->cert_number . '" ' . $string . '></td>';
                if (!isset($param['readonly'])) {
                    $html .= '<td class="text-center">';
                    $html .= '<ul class="icons-list">';
                    $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
                    $html .= '</ul>';
                    $html .= '</td>';
                }
                $html .= '</tr>';
                $i++;
            }
        } else {

            $html .= '<tr data-number="1">';
            $html .= '<td class="_control"><input type="hidden" name="positionRankAutoNumber[]" value="number1"><input type="text" name="positionRankRankLevel[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="positionRankRankName[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="positionRankDocumentInfo[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="positionRankCertNumber[]" class="_control_input"></td>';
            $html .= '<td class="text-center">';
            $html .= '<ul class="icons-list">';
            $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
            $html .= '</ul>';
            $html .= '</td>';
            $html .= '</tr>';

            $html .= '<tr data-number="2">';
            $html .= '<td class="_control"><input type="hidden" name="positionRankAutoNumber[]" value="number2"><input type="text" name="positionRankRankLevel[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="positionRankRankName[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="positionRankDocumentInfo[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="positionRankCertNumber[]" class="_control_input"></td>';
            $html .= '<td class="text-center">';
            $html .= '<ul class="icons-list">';
            $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
            $html .= '</ul>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '</fieldset>';
        return $html;
    }

    public function deleteInsertHrPeoplePositionRank_model($param = array()) {

        $this->db->where('people_id', $param['peopleId']);
        $this->db->delete($this->db->dbprefix . 'hr_people_position_rank');

        foreach ($this->input->post('positionRankAutoNumber') as $key => $value) {
            $data = array(
                array(
                    'id' => getUID('hr_people_position_rank'),
                    'mod_id' => 0,
                    'people_id' => $param['peopleId'],
                    'rank_level' => $this->input->post('positionRankRankLevel[' . $key . ']'),
                    'rank_name' => $this->input->post('positionRankRankName[' . $key . ']'),
                    'document_info' => $this->input->post('positionRankDocumentInfo[' . $key . ']'),
                    'cert_number' => $this->input->post('positionRankCertNumber[' . $key . ']'),
                    'is_active' => 1,
                    'created_date' => date('Y-m-d H:i:s'),
                    'modified_date' => date('Y-m-d H:i:s'),
                    'created_user_id' => $this->session->adminUserId,
                    'modified_user_id' => $this->session->adminUserId,
                    'order_num' => getOrderNum(array('table' => 'hr_people_position_rank', 'field' => 'order_num'))));
            $this->db->insert_batch($this->db->dbprefix . 'hr_people_position_rank', $data);
        }
    }

    public function listsHrPeopleEducationRank_model($param = array()) {

        $html = $string = $disabled = '';

        if (isset($param['readonly']) and strtolower($param['readonly']) == 'true') {
            $string .= ' disabled="true"';
            $disabled = 'true';
        }


        $query = $this->db->query('
            SELECT 
                HPER.id,
                HPER.people_id,
                HPER.title,
                HPER.register_organization,
                HPER.in_date,
                HPER.about,
                HPER.is_active,
                HPER.created_date,
                HPER.modified_date,
                HPER.created_user_id,
                HPER.modified_user_id,
                HPER.order_num
            FROM `gaz_hr_people_education_rank` AS HPER
            WHERE HPER.people_id = ' . $param['peopleId']);

        $html .= '<fieldset class="stepy-step">';
        $html .= '<legend>';
        $html .= '<span class="text-semibold">Эрдмийн зэрэг цол</span> <span class="_description"></span>';
        if (!isset($param['readonly'])) {
            $html .= '<span class="pull-right">' . form_button('addHrPeopleEducationRank', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Нэмэх</span>', 'class="btn btn-primary btn-xs" onclick="_addHrPeopleEducationRank({elem: this});"', 'button') . '</span>';
        }
        $html .= '<div class="clearfix"></div>';
        $html .= '</legend>';

        $html .= '<div class="table-responsive">';
        $html .= '<table class="_form-table">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th style="width:120px;">Цол</th>';
        $html .= '<th>Цол олгосон байгууллага</th>';
        $html .= '<th style="width:100px;">Он, сар</th>';
        $html .= '<th>Гэрчилгээ, дипломын дугаар</th>';
        if (!isset($param['readonly'])) {
            $html .= '<th style="width:50px;" class="text-center"><i class="icon-cog7"></i></th>';
        }
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody data-link="row" class="rowlink lists-hr-people-education-rank">';

        if ($query->num_rows() > 0) {

            $i = 1;

            foreach ($query->result() as $key => $row) {

                $html .= '<tr data-number="' . $i . '">';
                $html .= '<td class="_control"><input type="hidden" name="educationRankAutoNumber[]" value="number' . $i . '"><input type="text" name="educationRankTitle[]" class="_control_input" value="' . $row->title . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="educationRankRegisterOrganization[]" class="_control_input" value="' . $row->register_organization . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="educationRankInDate[]" class="_control_input init-date" value="' . $row->in_date . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="educationRankAbout[]" class="_control_input" value="' . $row->about . '" ' . $string . '></td>';
                if (!isset($param['readonly'])) {
                    $html .= '<td class="text-center">';
                    $html .= '<ul class="icons-list">';
                    $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
                    $html .= '</ul>';
                    $html .= '</td>';
                }
                $html .= '</tr>';
                $i++;
            }
        } else {

            $html .= '<tr data-number="1">';
            $html .= '<td class="_control"><input type="hidden" name="educationRankAutoNumber[]" value="number1"><input type="text" name="educationRankTitle[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="educationRankRegisterOrganization[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="educationRankInDate[]" class="_control_input init-date"></td>';
            $html .= '<td class="_control"><input type="text" name="educationRankAbout[]" class="_control_input"></td>';
            $html .= '<td class="text-center">';
            $html .= '<ul class="icons-list">';
            $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
            $html .= '</ul>';
            $html .= '</td>';
            $html .= '</tr>';

            $html .= '<tr data-number="2">';
            $html .= '<td class="_control"><input type="hidden" name="educationRankAutoNumber[]" value="number2"><input type="text" name="educationRankTitle[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="educationRankRegisterOrganization[]" class="_control_input"></td>';
            $html .= '<td class="_control"><input type="text" name="educationRankInDate[]" class="_control_input init-date"></td>';
            $html .= '<td class="_control"><input type="text" name="educationRankAbout[]" class="_control_input"></td>';
            $html .= '<td class="text-center">';
            $html .= '<ul class="icons-list">';
            $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
            $html .= '</ul>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '</fieldset>';

        return $html;
    }

    public function deleteInsertHrPeopleEducationRank_model($param = array()) {

        $this->db->where('people_id', $param['peopleId']);
        $this->db->delete($this->db->dbprefix . 'hr_people_education_rank');

        if ($this->input->post('educationRankAutoNumber')) {
            foreach ($this->input->post('educationRankAutoNumber') as $key => $value) {
                $data = array(
                    array(
                        'id' => getUID('hr_people_education_rank'),
                        'people_id' => $param['peopleId'],
                        'title' => $this->input->post('educationRankTitle[' . $key . ']'),
                        'register_organization' => $this->input->post('educationRankRegisterOrganization[' . $key . ']'),
                        'in_date' => $this->input->post('educationRankInDate[' . $key . ']'),
                        'about' => $this->input->post('educationRankAbout[' . $key . ']'),
                        'is_active' => 1,
                        'created_date' => date('Y-m-d H:i:s'),
                        'modified_date' => date('Y-m-d H:i:s'),
                        'created_user_id' => $this->session->adminUserId,
                        'modified_user_id' => $this->session->adminUserId,
                        'order_num' => getOrderNum(array('table' => 'hr_people_education_rank', 'field' => 'order_num'))));
                $this->db->insert_batch($this->db->dbprefix . 'hr_people_education_rank', $data);
            }
        }
    }

    public function listsHrPeopleLanguage_model($param = array()) {

        $html = $string = $disabled = '';

        if (isset($param['readonly']) and strtolower($param['readonly']) == 'true') {
            $string .= ' disabled="true"';
            $disabled = 'true';
        }

        $query = $this->db->query('
            SELECT 
                HPL.id,
                HPL.people_id,
                HPL.title,
                HPL.listening_excellent,
                HPL.listening_good,
                HPL.listening_middle,
                HPL.speak_excellent,
                HPL.speak_good,
                HPL.speak_middle,
                HPL.read_excellent,
                HPL.read_good,
                HPL.read_middle,
                HPL.write_excellent,
                HPL.write_good,
                HPL.write_middle,
                HPL.is_active,
                HPL.created_date,
                HPL.modified_date,
                HPL.created_user_id,
                HPL.modified_user_id,
                HPL.order_num
            FROM `gaz_hr_people_language` AS HPL
            WHERE HPL.people_id = ' . $param['peopleId']);

        $html .= '<fieldset class="stepy-step">';
        $html .= '<legend>';
        $html .= '<span class="text-semibold">Гадаад хэлний мэдлэг</span> <span class="_description"></span>';
        if (!isset($param['readonly'])) {
            $html .= '<span class="pull-right">' . form_button('addHrPeopleLanguage', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Нэмэх</span>', 'class="btn btn-primary btn-xs" onclick="_addHrPeopleLanguage({elem: this});"', 'button') . '</span>';
        }
        $html .= '<div class="clearfix"></div>';
        $html .= '</legend>';

        $html .= '<div class="table-responsive">';
        $html .= '<table class="_form-table">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th rowspan="2">Гадаад хэлний нэр</th>';
        $html .= '<th style="width:150px;" colspan="3" class="text-center">Сонсож ойлгох</th>';
        $html .= '<th style="width:150px;" colspan="3" class="text-center">Ярих</th>';
        $html .= '<th style="width:150px;" colspan="3" class="text-center">Унших</th>';
        $html .= '<th style="width:150px;" colspan="3" class="text-center">Бичих</th>';
        if (!isset($param['readonly'])) {
            $html .= '<th style="width:50px;" class="text-center" rowspan="2"><i class="icon-cog7"></i></th>';
        }
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<th class="text-center">Дунд</th>';
        $html .= '<th class="text-center">Сайн</th>';
        $html .= '<th class="text-center">Онц</th>';
        $html .= '<th class="text-center">Дунд</th>';
        $html .= '<th class="text-center">Сайн</th>';
        $html .= '<th class="text-center">Онц</th>';
        $html .= '<th class="text-center">Дунд</th>';
        $html .= '<th class="text-center">Сайн</th>';
        $html .= '<th class="text-center">Онц</th>';
        $html .= '<th class="text-center">Дунд</th>';
        $html .= '<th class="text-center">Сайн</th>';
        $html .= '<th class="text-center">Онц</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody data-link="row" class="rowlink lists-hr-people-language">';

        if ($query->num_rows() > 0) {

            $i = 1;

            foreach ($query->result() as $key => $row) {

                $html .= '<tr data-number="' . $i . '">';

                $html .= '<td class="_control"><input type="hidden" name="languageAutoNumber[]" value="number' . $i . '"><input type="text" name="languageTitle[]" class="_control_input" value="' . $row->title . '" ' . $string . '></td>';

                $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageListeningMiddle[]" class="language-listening language-listening-middle-' . $i . '" value="' . $row->listening_middle . '">' . form_radio(array('name' => 'languageListening' . $key, 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-listening-middle-' . $i . '\', removeClass: \'language-listening\'});'), 1, ($row->listening_middle == 1 ? TRUE : ''), $string) . ' Дунд</label></div></td>';
                $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageListeningGood[]" class="language-listening language-listening-good-' . $i . '" value="' . $row->listening_good . '">' . form_radio(array('name' => 'languageListening' . $key, 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-listening-good-' . $i . '\', removeClass: \'language-listening\'});'), 2, ($row->listening_good == 2 ? TRUE : ''), $string) . ' Сайн</label></div></td>';
                $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageListeningExcellent[]" class="language-listening language-listening-excellent-' . $i . '" value="' . $row->listening_excellent . '">' . form_radio(array('name' => 'languageListening' . $key, 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-listening-excellent-' . $i . '\', removeClass: \'language-listening\'});'), 3, ($row->listening_excellent == 3 ? TRUE : ''), $string) . ' Онц</label></div></td>';

                $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageSpeakMiddle[]" class="language-speak language-speak-middle-' . $i . '" value="' . $row->speak_middle . '">' . form_radio(array('name' => 'languageSpeak' . $key, 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-speak-middle-' . $i . '\', removeClass: \'language-speak\'});'), 1, ($row->speak_middle == 1 ? TRUE : ''), $string) . ' Дунд</label></div></td>';
                $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageSpeakGood[]" class="language-speak language-speak-good-' . $i . '" value="' . $row->speak_good . '">' . form_radio(array('name' => 'languageSpeak' . $key, 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-speak-good-' . $i . '\', removeClass: \'language-speak\'});'), 2, ($row->speak_good == 2 ? TRUE : ''), $string) . ' Сайн</label></div></td>';
                $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageSpeakExcellent[]" class="language-speak language-speak-excellent-' . $i . '" value="' . $row->speak_excellent . '">' . form_radio(array('name' => 'languageSpeak' . $key, 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-speak-excellent-' . $i . '\', removeClass: \'language-speak\'});'), 3, ($row->speak_excellent == 3 ? TRUE : ''), $string) . ' Онц</label></div></td>';

                $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageReadMiddle[]" class="language-read language-read-middle-' . $i . '" value="' . $row->read_middle . '">' . form_radio(array('name' => 'languageRead' . $key, 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-read-middle-' . $i . '\', removeClass: \'language-read\'});'), 1, ($row->read_middle == 1 ? TRUE : ''), $string) . ' Дунд</label></div></td>';
                $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageReadGood[]" class="language-read language-read-good-' . $i . '" value="' . $row->read_good . '">' . form_radio(array('name' => 'languageRead' . $key, 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-read-good-' . $i . '\', removeClass: \'language-read\'});'), 2, ($row->read_good == 2 ? TRUE : ''), $string) . ' Сайн</label></div></td>';
                $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageReadExcellent[]" class="language-read language-read-excellent-' . $i . '" value="' . $row->read_excellent . '">' . form_radio(array('name' => 'languageRead' . $key, 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-read-excellent-' . $i . '\', removeClass: \'language-read\'});'), 3, ($row->read_excellent == 3 ? TRUE : ''), $string) . ' Онц</label></div></td>';

                $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageWriteMiddle[]" class="language-write language-write-middle-' . $i . '" value="' . $row->write_middle . '">' . form_radio(array('name' => 'languageWrite' . $key, 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-write-middle-' . $i . '\', removeClass: \'language-write\'});'), 1, ($row->write_middle == 1 ? TRUE : ''), $string) . ' Дунд</label></div></td>';
                $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageWriteGood[]" class="language-write language-write-good-' . $i . '" value="' . $row->write_good . '">' . form_radio(array('name' => 'languageWrite' . $key, 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-write-good-' . $i . '\', removeClass: \'language-write\'});'), 2, ($row->write_good == 2 ? TRUE : ''), $string) . ' Сайн</label></div></td>';
                $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageWriteExcellent[]" class="language-write language-write-excellent-' . $i . '" value="' . $row->write_excellent . '">' . form_radio(array('name' => 'languageWrite' . $key, 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-write-excellent-' . $i . '\', removeClass: \'language-write\'});'), 3, ($row->write_excellent == 3 ? TRUE : ''), $string) . ' Онц</label></div></td>';
                if (!isset($param['readonly'])) {
                    $html .= '<td class="text-center">';
                    $html .= '<ul class="icons-list">';
                    $html .= '<li onclick="_removeTableFormRow({elem: this, id:' . $row->id . '});" title="Устгах"><span data-action="delete"></span></li>';
                    $html .= '</ul>';
                    $html .= '</td>';
                }
                $html .= '</tr>';
                $i++;
            }
        } else {

            $html .= '<tr data-number="1">';

            $html .= '<td class="_control"><input type="hidden" name="languageAutoNumber[]" value="number1"><input type="text" name="languageTitle[]" class="_control_input"></td>';

            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageListeningMiddle[]" class="language-listening language-listening-middle-1" value="0">' . form_radio(array('name' => 'languageListening0', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-listening-middle-1\', removeClass: \'language-listening\'});'), 1, FALSE) . ' Дунд</label></div></td>';
            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageListeningGood[]" class="language-listening language-listening-good-1" value="0">' . form_radio(array('name' => 'languageListening0', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-listening-good-1\', removeClass: \'language-listening\});'), 2, FALSE) . ' Сайн</label></div></td>';
            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageListeningExcellent[]" class="language-listening language-listening-excellent-1" value="0">' . form_radio(array('name' => 'languageListening0', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-listening-excellent-1\', removeClass: \'language-listening\});'), 3, FALSE) . ' Онц</label></div></td>';

            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageSpeakMiddle[]" class="language-speak language-speak-middle-1" value="0">' . form_radio(array('name' => 'languageSpeak0', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-speak-middle-1\', removeClass: \'language-speak\'});'), 1, FALSE) . ' Дунд</label></div></td>';
            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageSpeakGood[]" class="language-speak language-speak-good-1" value="0">' . form_radio(array('name' => 'languageSpeak0', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-speak-good-1\', removeClass: \'language-speak\'});'), 2, FALSE) . ' Сайн</label></div></td>';
            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageSpeakExcellent[]" class="language-speak language-speak-excellent-1" value="0">' . form_radio(array('name' => 'languageSpeak0', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-speak-excellent-1\', removeClass: \'language-speak\'});'), 3, FALSE) . ' Онц</label></div></td>';

            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageReadMiddle[]" class="language-read language-read-middle-1" value="0">' . form_radio(array('name' => 'languageRead0', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-read-middle-1\', removeClass: \'language-read\'});'), 1, FALSE) . ' Дунд</label></div></td>';
            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageReadGood[]" class="language-read language-read-good-1" value="0">' . form_radio(array('name' => 'languageRead0', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-read-good-1\', removeClass: \'language-read\'});'), 2, FALSE) . ' Сайн</label></div></td>';
            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageReadExcellent[]" class="language-read language-read-excellent-1" value="0">' . form_radio(array('name' => 'languageRead0', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-read-excellent-1\', removeClass: \'language-read\'});'), 3, FALSE) . ' Онц</label></div></td>';

            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageWriteMiddle[]" class="language-write language-write-middle-1" value="0">' . form_radio(array('name' => 'languageWrite0', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-write-middle-1\', removeClass: \'language-write\'});'), 1, FALSE) . ' Дунд</label></div></td>';
            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageWriteGood[]" class="language-write language-write-good-1" value="0">' . form_radio(array('name' => 'languageWrite0', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-write-good-1\', removeClass: \'language-write\'});'), 2, FALSE) . ' Сайн</label></div></td>';
            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageWriteExcellent[]" class="language-write language-write-excellent-1" value="0">' . form_radio(array('name' => 'languageWrite0', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-write-excellent-1\', removeClass: \'language-write\'});'), 3, FALSE) . ' Онц</label></div></td>';

            $html .= '<td class="text-center">';
            $html .= '<ul class="icons-list">';
            $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
            $html .= '</ul>';
            $html .= '</td>';

            $html .= '</tr>';



            $html .= '<tr data-number="2">';

            $html .= '<td class="_control"><input type="hidden" name="languageAutoNumber[]" value="number2"><input type="text" name="languageTitle[]" class="_control_input"></td>';

            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageListeningMiddle[]" class="language-listening language-listening-middle-2" value="0">' . form_radio(array('name' => 'languageListening1', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-listening-middle-2\', removeClass: \'language-listening\'});'), 1, FALSE) . ' Дунд</label></div></td>';
            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageListeningGood[]" class="language-listening language-listening-good-2" value="0">' . form_radio(array('name' => 'languageListening1', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-listening-good-2\', removeClass: \'language-listening\'});'), 2, FALSE) . ' Сайн</label></div></td>';
            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageListeningExcellent[]" class="language-listening language-listening-excellent-2" value="0">' . form_radio(array('name' => 'languageListening1', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-listening-excellent-2\', removeClass: \'language-listening\'});'), 3, FALSE) . ' Онц</label></div></td>';

            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageSpeakMiddle[]" class="language-speak language-speak-middle-2" value="0">' . form_radio(array('name' => 'languageSpeak1', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-speak-middle-2\', removeClass: \'language-speak\'});'), 1, FALSE) . ' Дунд</label></div></td>';
            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageSpeakGood[]" class="language-speak language-speak-good-2" value="0">' . form_radio(array('name' => 'languageSpeak1', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-speak-good-2\', removeClass: \'language-speak\'});'), 2, FALSE) . ' Сайн</label></div></td>';
            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageSpeakExcellent[]" class="language-speak language-speak-excellent-2" value="0">' . form_radio(array('name' => 'languageSpeak1', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-speak-excellent-2\', removeClass: \'language-speak\'});'), 3, FALSE) . ' Онц</label></div></td>';

            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageReadMiddle[]" class="language-read language-read-middle-2" value="0">' . form_radio(array('name' => 'languageRead1', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-read-middle-2\', removeClass: \'language-read\'});'), 1, FALSE) . ' Дунд</label></div></td>';
            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageReadGood[]" class="language-read language-read-good-2" value="0">' . form_radio(array('name' => 'languageRead1', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-read-good-2\', removeClass: \'language-read\'});'), 2, FALSE) . ' Сайн</label></div></td>';
            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageReadExcellent[]" class="language-read language-read-excellent-2" value="0">' . form_radio(array('name' => 'languageRead1', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-read-excellent-2\', removeClass: \'language-read\'});'), 3, FALSE) . ' Онц</label></div></td>';

            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageWriteMiddle[]" class="language-write language-write-middle-2" value="0">' . form_radio(array('name' => 'languageWrite1', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-write-middle-2\', removeClass: \'language-write\'});'), 1, FALSE) . ' Дунд</label></div></td>';
            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageWriteGood[]" class="language-write language-write-good-2" value="0">' . form_radio(array('name' => 'languageWrite1', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-write-good-2\', removeClass: \'language-write\'});'), 2, FALSE) . ' Сайн</label></div></td>';
            $html .= '<td class="_control"><div class="form-check form-check-inline"><label class="form-check-label"><input type="hidden" name="languageWriteExcellent[]" class="language-write language-write-excellent-2" value="0">' . form_radio(array('name' => 'languageWrite1', 'class' => 'radio', 'onclick' => '_setPeopleLanguageValue({elem: this, addClass: \'language-write-excellent-2\', removeClass: \'language-write\'});'), 3, FALSE) . ' Онц</label></div></td>';

            $html .= '<td class="text-center">';
            $html .= '<ul class="icons-list">';
            $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
            $html .= '</ul>';
            $html .= '</td>';

            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '</fieldset>';
        return $html;
    }

    public function deleteInsertHrPeopleLanguage_model($param = array()) {

        $this->db->where('people_id', $param['peopleId']);
        $this->db->delete($this->db->dbprefix . 'hr_people_language');

        if ($this->input->post('languageAutoNumber')) {
            foreach ($this->input->post('languageAutoNumber') as $key => $value) {
                $data = array(
                    array(
                        'id' => getUID('hr_people_language'),
                        'people_id' => $param['peopleId'],
                        'title' => $this->input->post('languageTitle[' . $key . ']'),
                        'listening_middle' => $this->input->post('languageListeningMiddle[' . $key . ']'),
                        'listening_good' => $this->input->post('languageListeningGood[' . $key . ']'),
                        'listening_excellent' => $this->input->post('languageListeningExcellent[' . $key . ']'),
                        'speak_middle' => $this->input->post('languageSpeakMiddle[' . $key . ']'),
                        'speak_good' => $this->input->post('languageSpeakGood[' . $key . ']'),
                        'speak_excellent' => $this->input->post('languageSpeakExcellent[' . $key . ']'),
                        'read_middle' => $this->input->post('languageReadMiddle[' . $key . ']'),
                        'read_good' => $this->input->post('languageReadGood[' . $key . ']'),
                        'read_excellent' => $this->input->post('languageReadExcellent[' . $key . ']'),
                        'write_middle' => $this->input->post('languageWriteMiddle[' . $key . ']'),
                        'write_good' => $this->input->post('languageWriteGood[' . $key . ']'),
                        'write_excellent' => $this->input->post('languageWriteExcellent[' . $key . ']'),
                        'is_active' => 1,
                        'created_date' => date('Y-m-d H:i:s'),
                        'modified_date' => date('Y-m-d H:i:s'),
                        'created_user_id' => $this->session->adminUserId,
                        'modified_user_id' => $this->session->adminUserId,
                        'order_num' => getOrderNum(array('table' => 'hr_people_language', 'field' => 'order_num'))));
                $this->db->insert_batch($this->db->dbprefix . 'hr_people_language', $data);
            }
        }
    }

    public function listsHrPeopleAward_model($param = array()) {

        $html = $string = $disabled = '';

        if (isset($param['readonly']) and strtolower($param['readonly']) == 'true') {
            $string .= ' disabled="true"';
            $disabled = true;
        }

        $query = $this->db->query('
            SELECT 
                HPA.id,
                HPA.mod_id,
                HPA.cat_id,
                HPA.in_date,
                HPA.title,
                HPA.is_active,
                HPA.created_date,
                HPA.modified_date,
                HPA.created_user_id,
                HPA.modified_user_id,
                HPA.order_num
            FROM `gaz_hr_people_award` AS HPA
            WHERE HPA.people_id = ' . $param['peopleId']);

        $html .= '<fieldset class="stepy-step">';
        $html .= '<legend>';
        $html .= '<span class="text-semibold">Хэзээ ямар шагнал авсан</span> <span class="_description"></span>';
        if (!isset($param['readonly'])) {
            $html .= '<span class="pull-right">' . form_button('addHrPeopleAward', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Нэмэх</span>', 'class="btn btn-primary btn-xs" onclick="_addHrPeopleAward({elem: this});"', 'button') . '</span>';
        }
        $html .= '<div class="clearfix"></div>';
        $html .= '</legend>';

        $html .= '<div class="table-responsive">';
        $html .= '<table class="_form-table">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th rowspan="2">Шагналын нэр</th>';
        $html .= '<th style="width:100px;" class="text-center">Он сар өдөр</th>';
        $html .= '<th class="text-center">Тайлбар</th>';
        if (!isset($param['readonly'])) {
            $html .= '<th style="width:50px;" class="text-center"><i class="icon-cog7"></i></th>';
        }
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody data-link="row" class="rowlink lists-hr-people-award">';


        if ($query->num_rows() > 0) {
            $i = 1;
            foreach ($query->result() as $key => $row) {
                $controlCategoryListDropdown = $this->category->controlCategoryListDropdown_model(array('name' => 'awardCatId[]', 'modId' => 68, 'selectedId' => $row->cat_id, 'disabled' => $disabled));
                $html .= '<tr data-number="' . $i . '">';
                $html .= '<td class="_control"><input type="hidden" name="awardAutoNumber[]" value="number' . $i . '">' . $controlCategoryListDropdown . '</td>';
                $html .= '<td class="_control text-center"><input type="text" name="awardDate[]" class="_control_input init-date" value="' . $row->in_date . '" ' . $string . '></td>';
                $html .= '<td class="_control text-center"><input type="text" name="awardTitle[]" class="_control_input" value="' . $row->title . '" ' . $string . '></td>';
                if (!isset($param['readonly'])) {
                    $html .= '<td class="text-center">';
                    $html .= '<ul class="icons-list">';
                    $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
                    $html .= '</ul>';
                    $html .= '</td>';
                }
                $html .= '</tr>';
                $i++;
            }
        } else {

            $controlCategoryListDropdown = $this->category->controlCategoryListDropdown_model(array('name' => 'awardCatId[]', 'modId' => 68, 'selectedId' => 0));

            $html .= '<tr data-number="1">';
            $html .= '<td class="_control"><input type="hidden" name="awardAutoNumber[]" value="number1">' . $controlCategoryListDropdown . '</td>';
            $html .= '<td class="_control text-center"><input type="text" name="awardDate[]" class="_control_input init-date"></td>';
            $html .= '<td class="_control text-center"><input type="text" name="awardTitle[]" class="_control_input"></td>';
            $html .= '<td class="text-center">';
            $html .= '<ul class="icons-list">';
            $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
            $html .= '</ul>';
            $html .= '</td>';
            $html .= '</tr>';

            $html .= '<tr data-number="2">';
            $html .= '<td class="_control"><input type="hidden" name="awardAutoNumber[]" value="number2">' . $controlCategoryListDropdown . '</td>';
            $html .= '<td class="_control text-center"><input type="text" name="awardDate[]" class="_control_input init-date"></td>';
            $html .= '<td class="_control text-center"><input type="text" name="awardTitle[]" class="_control_input"></td>';
            $html .= '<td class="text-center">';
            $html .= '<ul class="icons-list">';
            $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
            $html .= '</ul>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '</fieldset>';
        return $html;
    }

    public function deleteInsertHrPeopleAward_model($param = array()) {

        $this->db->where('people_id', $param['peopleId']);
        $this->db->delete($this->db->dbprefix . 'hr_people_award');

        if ($this->input->post('awardAutoNumber')) {
            foreach ($this->input->post('awardAutoNumber') as $key => $value) {
                $data = array(
                    array(
                        'id' => getUID('hr_people_award'),
                        'people_id' => $param['peopleId'],
                        'mod_id' => 0,
                        'cat_id' => $this->input->post('awardCatId[' . $key . ']'),
                        'in_date' => $this->input->post('awardDate[' . $key . ']'),
                        'title' => $this->input->post('awardTitle[' . $key . ']'),
                        'is_active' => 1,
                        'created_date' => date('Y-m-d H:i:s'),
                        'modified_date' => date('Y-m-d H:i:s'),
                        'created_user_id' => $this->session->adminUserId,
                        'modified_user_id' => $this->session->adminUserId,
                        'order_num' => getOrderNum(array('table' => 'hr_people_award', 'field' => 'order_num'))));
                $this->db->insert_batch($this->db->dbprefix . 'hr_people_award', $data);
            }
        }
    }

    public function hrPeopleReportAddForm_model() {

        return json_decode(json_encode(array(
            'id' => 0,
            'people_id' => 0,
            'mod_id' => 0,
            'cat_id' => 0,
            'title' => '',
            'attach_file' => '',
            'description' => '',
            'in_date' => date('Y-m-d'),
            'out_date' => date('Y-m-d'),
            'is_active' => 1,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s'),
            'created_user_id' => $this->session->userdata['adminUserId'],
            'modified_user_id' => $this->session->userdata['adminUserId'],
            'order_num' => getOrderNum(array('table' => 'hr_people_report', 'field' => 'order_num'))
        )));
    }

    public function getReportData_model($param = array()) {

        $query = $this->db->query('
            SELECT 
                HPR.id,
                HPR.people_id,
                HPR.mod_id,
                HPR.cat_id,
                HPR.title,
                HPR.attach_file,
                HPR.description,
                HPR.in_date,
                HPR.out_date,
                HPR.is_active,
                HPR.created_date,
                HPR.modified_date,
                HPR.created_user_id,
                HPR.modified_user_id,
                HPR.order_num
            FROM `gaz_hr_people_report` AS HPR
            WHERE HPR.id = ' . $param['selectedId']);

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return $this->hrPeopleReportAddForm_model();
        }
    }

    public function listsHrPeopleReport_model($param = array()) {

        $html = '';

        $this->queryString = $this->getString = '';

        $this->query = $this->db->query('
            SELECT 
                HPR.id,
                HPR.people_id,
                HPR.mod_id,
                HPR.cat_id,
                HPR.title,
                HPR.attach_file,
                DATE(HPR.in_date) AS in_date,
                DATE(HPR.out_date) AS out_date,
                HPR.is_active,
                DATE(HPR.created_date) AS created_date,
                HPR.modified_date,
                HPR.created_user_id,
                HPR.modified_user_id,
                HPR.order_num
            FROM `gaz_hr_people_report` AS HPR
            WHERE HPR.people_id = ' . $param['peopleId']);

        $html .= '<fieldset class="stepy-step">';
        $html .= '<legend>';
        $html .= '<span class="text-semibold">Хийсэн ажлын тайлан</span> <span class="_description">(хугацааны дараалалаар)</span>';
        $html .= '<span class="pull-right">' . form_button('addHrPeopleReport', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Нэмэх</span>', 'class="btn btn-primary btn-xs" onclick="_hrPeopleReportForm({elem: this, peopleId: ' . $param['peopleId'] . ', selectedId: 0});"', 'button') . '</span>';
        $html .= '<div class="clearfix"></div>';
        $html .= '</legend>';

        $html .= '<div class="table-responsive">';
        $html .= '<table class="_form-table">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th style="width:100px;" class="text-center">Эхэлсэн</th>';
        $html .= '<th style="width:100px;" class="text-center">Дууссан</th>';
        $html .= '<th>Тайлан</th>';
        $html .= '<th style="width:100px;" class="text-center">Илгээсэн</th>';
        $html .= '<th style="width:50px;" class="text-center"><i class="icon-cog7"></i></th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody data-link="row" class="rowlink lists-hr-people-report">';
        if ($this->query->num_rows() > 0) {
            $i = 1;
            foreach ($this->query->result() as $key => $row) {
                $html .= '<tr data-number="' . $i . '">';
                $html .= '<td class="_control text-center">' . $row->in_date . '</td>';
                $html .= '<td class="_control text-center">' . $row->out_date . '</td>';
                $html .= '<td class="_control pl-1 pr-2" style="cursor:pointer" onclick="_showHrPeopleReport({elem: this, selectedId: ' . $row->id . '});">' . $row->title . '</td>';
                $html .= '<td class="_control text-center">' . $row->created_date . '</td>';
                $html .= '<td class="text-center">';
                $html .= '<div onclick="_deleteHrPeopleReport({elem: this, id: ' . $row->id . ', peopleId: ' . $param['peopleId'] . '});" style="cursor:pointer;"><i class="fa fa-trash"></i></div>';
                $html .= '</td>';
                $html .= '</tr>';
                $i++;
            }
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '</fieldset>';
        return $html;
    }

    public function insertHrPeopleReport_model($param = array('uploadFieldName' => '', 'uploadPath' => UPLOADS_CONTENT_PATH)) {

        $fileUploadResult = false;
        if (isset($_FILES['attachFileUpload'])) {
            $file = explode(".", $_FILES['attachFileUpload']['name']);
            $config['file_name'] = getFileUID() . '.' . end($file);
            $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_HR_PATH;
            $config['allowed_types'] = UPLOAD_PDF_TYPE;
            $config['max_size'] = UPLOAD_FILE_MAX_SIZE;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('attachFileUpload')) {

                $fileUploadResult = $this->upload->data();
            }
        }

        $data = array(
            array(
                'id' => getUID('hr_people_report'),
                'people_id' => $this->input->post('peopleId'),
                'mod_id' => 0,
                'cat_id' => 0,
                'title' => $this->input->post('reportTitle'),
                'in_date' => $this->input->post('reportInDate'),
                'out_date' => $this->input->post('reportOutDate'),
                'description' => $this->input->post('reportDescription'),
                'attach_file' => (!$fileUploadResult ? '' : $fileUploadResult['file_name']),
                'is_active' => 1,
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => $this->session->adminUserId,
                'order_num' => getOrderNum(array('table' => 'hr_people_report', 'field' => 'order_num'))));
        if ($this->db->insert_batch($this->db->dbprefix . 'hr_people_report', $data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Файл хуулах үед алдаа гарлаа', 'data' => $this->upload->data());
    }

    public function deleteHrPeopleReport_model($param = array()) {

        $query = $this->db->query('
            SELECT 
                HPR.id,
                HPR.attach_file
            FROM `gaz_hr_people_report` AS HPR
            WHERE HPR.id = ' . $param['id']);
        if ($query->num_rows() > 0) {

            $row = $query->row();

            if (is_file($_SERVER['DOCUMENT_ROOT'] . UPLOADS_HR_PATH . $row->attach_file)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . UPLOADS_HR_PATH . $row->attach_file);
            }

            $this->db->where('id', $param['id']);

            if ($this->db->delete($this->db->dbprefix . 'hr_people_report')) {

                return array('status' => 'success', 'message' => 'Амжилттай устгалаа...');
            }
        }

        return array('status' => 'error', 'message' => 'Устгах үед алдаа гарлаа...');
    }

    public function listsHrPeopleConviction_model($param = array()) {

        $html = $string = $disabled = '';

        if (isset($param['readonly']) and strtolower($param['readonly']) == 'true') {
            $string .= ' disabled="true"';
            $disabled = true;
        }

        $query = $this->db->query('
            SELECT 
                HPC.id,
                HPC.people_id,
                HPC.mod_id,
                HPC.cat_id,
                HPC.title,
                HPC.description,
                HPC.in_date,
                HPC.is_active,
                HPC.created_date,
                HPC.modified_date,
                HPC.created_user_id,
                HPC.modified_user_id,
                HPC.order_num
            FROM `gaz_hr_people_conviction` AS HPC
            WHERE HPC.people_id = ' . $param['peopleId']);

        $html .= '<fieldset class="stepy-step">';
        $html .= '<legend>';
        $html .= '<span class="text-semibold">Шийтгэл</span> <span class="_description">(хугацааны дараалалаар)</span>';
        if (!isset($param['readonly'])) {
            $html .= '<span class="pull-right">' . form_button('addHrPeopleConviction', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Нэмэх</span>', 'class="btn btn-primary btn-xs" onclick="_addHrPeopleConviction({elem: this});"', 'button') . '</span>';
        }
        $html .= '<div class="clearfix"></div>';
        $html .= '</legend>';

        $html .= '<div class="table-responsive">';
        $html .= '<table class="_form-table">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th style="width:100px;">Огноо</th>';
        $html .= '<th style="width:200px;" class="text-center">Гарчиг</th>';
        $html .= '<th class="text-center">Тайлбар</th>';
        if (!isset($param['readonly'])) {
            $html .= '<th style="width:50px;" class="text-center"><i class="icon-cog7"></i></th>';
        }
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody data-link="row" class="rowlink lists-hr-people-conviction">';

        if ($query->num_rows() > 0) {

            $i = 1;
            foreach ($query->result() as $key => $row) {

                $html .= '<tr data-number="' . $i . '">';
                $html .= '<td class="_control"><input type="hidden" name="convictionAutoNumber[]" value="number' . $i . '"><input type="text" name="convictionInDate[]" class="_control_input init-date" value="' . $row->in_date . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="convictionTitle[]" class="_control_input" value="' . $row->title . '" ' . $string . '></td>';
                $html .= '<td class="_control text-center"><input type="text" name="convictionDesription[]" class="_control_input" value="' . $row->description . '" ' . $string . '></td>';
                if (!isset($param['readonly'])) {
                    $html .= '<td class="text-center">';
                    $html .= '<ul class="icons-list">';
                    $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
                    $html .= '</ul>';
                    $html .= '</td>';
                }
                $html .= '</tr>';
                $i++;
            }
        } else {

            $html .= '<tr data-number="1">';
            $html .= '<td class="_control text-center"><input type="hidden" name="convictionAutoNumber[]" value="number1"><input type="text" name="convictionInDate[]" class="_control_input init-date"></td>';
            $html .= '<td class="_control"><input type="text" name="convictionTitle[]" class="_control_input"></td>';
            $html .= '<td class="_control text-center"><input type="text" name="convictionDesription[]" class="_control_input"></td>';
            $html .= '<td class="text-center">';
            $html .= '<ul class="icons-list">';
            $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
            $html .= '</ul>';
            $html .= '</td>';
            $html .= '</tr>';

            $html .= '<tr data-number="2">';
            $html .= '<td class="_control text-center"><input type="hidden" name="convictionAutoNumber[]" value="number2"><input type="text" name="convictionInDate[]" class="_control_input init-date"></td>';
            $html .= '<td class="_control"><input type="text" name="convictionTitle[]" class="_control_input"></td>';
            $html .= '<td class="_control text-center"><input type="text" name="convictionDesription[]" class="_control_input"></td>';
            $html .= '<td class="text-center">';
            $html .= '<ul class="icons-list">';
            $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
            $html .= '</ul>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '</fieldset>';
        return $html;
    }

    public function deleteInsertHrPeopleConviction_model($param = array()) {

        $this->db->where('people_id', $param['peopleId']);
        $this->db->delete($this->db->dbprefix . 'hr_people_conviction');

        if ($this->input->post('convictionAutoNumber')) {
            foreach ($this->input->post('convictionAutoNumber') as $key => $value) {
                $data = array(
                    array(
                        'id' => getUID('hr_people_conviction'),
                        'people_id' => $param['peopleId'],
                        'mod_id' => 0,
                        'cat_id' => 0,
                        'title' => $this->input->post('convictionTitle[' . $key . ']'),
                        'description' => $this->input->post('convictionDesription[' . $key . ']'),
                        'in_date' => $this->input->post('convictionInDate[' . $key . ']'),
                        'is_active' => 1,
                        'created_date' => date('Y-m-d H:i:s'),
                        'modified_date' => date('Y-m-d H:i:s'),
                        'created_user_id' => $this->session->adminUserId,
                        'modified_user_id' => $this->session->adminUserId,
                        'order_num' => getOrderNum(array('table' => 'hr_people_conviction', 'field' => 'order_num'))));
                $this->db->insert_batch($this->db->dbprefix . 'hr_people_conviction', $data);
            }
        }
    }

    public function controlBirthYearDropdown_model($param = array('name' => '', 'selectedId' => 0)) {

        $html = $string = '';

        if (isset($param['disabled']) and $param['disabled'] == true) {
            $string .= ' disabled="true"';
        }

        if (isset($param['required']) and $param['required'] == true) {
            $string .= ' required="true"';
        }

        if (isset($param['readonly']) and $param['readonly'] == true) {
            $string .= ' disabled="true"';
        }

        $html .= '<select name="' . $param['name'] . '" class="select2" ' . $string . '>';
        $html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';

        $currentYear = (int) date('Y');
        for ($i = $currentYear; $i >= ($currentYear - 100); $i--) {
            $html .= '<option value="' . $i . '" ' . ($param['selectedId'] == $i ? 'selected="selected"' : '') . '>' . $i . ' он</option>';
        }

        $html .= '</select>';

        return $html;
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                HP.id,
                HP.mod_id,
                HP.cat_id,
                HP.partner_id,
                HP.position_id,
                HP.department_id,
                HP.rank_id,
                HP.position_description,
                HP.register,
                HP.social_insurance,
                HP.health_insurance,
                HP.family_name,
                HP.fname,
                HP.lname,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name,
                (case when (HP.pic is null or HP.pic = \'\') then \'default.svg\' else concat(\'m_\', HP.pic) end) as pic,
                HP.sex,
                HP.birthday,
                HP.birth_city_id,
                HP.birth_soum_id,
                HP.birth_street_id,
                HP.birth_address,
                HP.nationality,
                HP.social_origin,
                HP.live_city_id,
                HP.live_soum_id,
                HP.live_street_id,
                HP.live_address,
                HP.phone,
                HP.fax,
                HP.email,
                HP.post_address,
                HP.special_people,
                HP.special_phone,
                HP.is_active,
                HP.created_date,
                HP.modified_date,
                HP.created_user_id,
                HP.modified_user_id,
                HP.param,
                HP.order_num
            FROM `gaz_hr_people` AS HP
            WHERE HP.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        } else if ($param['selectedId'] == 1000001 or $param['selectedId'] == 1000002 or $param['selectedId'] == 1000003 or $param['selectedId'] == 1000004) {

            $tempData = array();
            $tempData[1000001] = 'Тусгай';
            $tempData[1000002] = 'Кримналистик';
            $tempData[1000003] = 'Гадны мэргэжилтэн';
            $tempData[1000004] = 'Орон нутгийн шинжээч эмч';

            return json_decode(json_encode(array(
                'id' => $param['selectedId'],
                'department_id' => 0,
                'full_name' => $tempData[$param['selectedId']] . ' - ' . $this->input->post('extraExpertValue')
            )));
        }
        return false;
    }

    public function controlHrPeopleListDropdown_model($param = array('departmentId' => 0, 'name' => '', 'selectedId' => 0)) {

        $query = $queryUnion = $queryString = $querySubString = $html = $string = $class = $name = '';

        if (!isset($param['name'])) {
            $param['name'] = 'peopleId';
        }

        if (isset($param['departmentId'])) {

            $querySubString .= ' AND HPW.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
        }

//        if (isset($param['positionId'])) {
//            $querySubString .= ' AND HPW.position_id IN (' . $param['positionId'] . ')';
//        }
//        if (isset($param['isCurrenty']) and $param['isCurrenty'] == 1) {
//            $querySubString .= ' AND _HPW.is_currenty  = ' . $param['isCurrenty'];
//            $queryString .= ' AND HP.status_id IN (5)';
//        }

        if (isset($param['disabled']) and $param['disabled'] == 'true') {
            $string .= ' disabled="true"';
        }

        if (isset($param['required']) and $param['required'] == 'true') {
            $string .= ' required="true"';
            $class .= ' required';
        }

        if (isset($param['readonly']) and $param['readonly'] == 'true') {
            $string .= ' disabled="true"';
        }

        if (isset($param['tabindex'])) {
            $string .= ' tabindex="' . $param['tabindex'] . '"';
        }

        if (isset($param['isLogView']) and $param['isLogView'] == 1) {
            $expertIn = '';

            $queryLog = $this->db->query('
                SELECT 
                    NE.expert_id
                FROM gaz_nifs_expert AS NE 
                WHERE NE.mod_id = ' . $param['modId'] . ' GROUP BY NE.expert_id');

            if ($queryLog->num_rows() > 0) {
                foreach ($queryLog->result() as $rowLog) {
                    $expertIn .= $rowLog->expert_id . ', ';
                }
                $expertIn = rtrim($expertIn, ', ');
            }

            $queryUnion = '
                SELECT 
                    HP.id, CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name, 
                    (case when (HP.pic is null or HP.pic = \'\') then \'default.svg\' else concat(\'s_\', HP.pic) end) as pic, 
                    HPP.title AS position_title, HPD.short_title AS department_title 
                FROM `gaz_hr_people` AS HP 
                INNER JOIN `gaz_hr_people_work` HPW ON HP.id = HPW.people_id 
                LEFT JOIN `gaz_hr_people_position` AS HPP ON HPW.position_id = HPP.id 
                LEFT JOIN `gaz_hr_people_department` AS HPD ON HPW.department_id = HPD.id 
                WHERE 
                    1 = 1 AND HP.id IN (' . $expertIn . ') 
                UNION DISTINCT ';
        }

        $query = $this->db->query($queryUnion . '
            SELECT 
                HP.id, CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name, 
                (case when (HP.pic is null or HP.pic = \'\') then \'default.svg\' else concat(\'s_\', HP.pic) end) as pic, 
                HPP.title AS position_title, HPD.short_title AS department_title 
            FROM `gaz_hr_people` AS HP 
            INNER JOIN `gaz_hr_people_work` HPW ON HP.id = HPW.people_id AND HPW.is_currenty = 1 ' . $querySubString . '
            INNER JOIN `gaz_hr_people_position` AS HPP ON HPW.position_id = HPP.id AND HPP.id NOT IN (' . NIFS_PEOPLE_POSITION_NOT_IN . ')
            INNER JOIN `gaz_hr_people_department` AS HPD ON HPW.department_id = HPD.id 
            WHERE 
                HP.is_active = 1 ' . $queryString . '
            ');

        $html .= '<select name="' . $param['name'] . '" id="' . $param['name'] . '" class="select2 form-control border-right-0" ' . $string . '>';
        $html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';
        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {
                $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->full_name . ' (' . $row->department_title . ' - ' . $row->position_title . ')</option>';
            }
            if (isset($param['isExtraValue']) and $param['isExtraValue'] == 'true') {

                $extraData = json_decode(json_encode(array(
                    array('id' => 643, 'old' => 1000001, 'full_name' => 'Тусгай'),
                    array('id' => 644, 'old' => 1000002, 'full_name' => 'Кримналистик'),
                    array('id' => 645, 'old' => 1000003, 'full_name' => 'Гадны мэргэжилтэн'),
                    array('id' => 646, 'old' => 1000004, 'full_name' => 'Орон нутгийн шинжээч эмч'))));

                foreach ($extraData as $key => $row) {
                    $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->full_name . '</option>';
                }
            }
        }

        $html .= '</select>';

        return $html;
    }

    public function controlHrPeopleMultiListDropdown_model($param = array('modId' => 0, 'contId' => 0)) {

        $htmlExtra = $string = $isDisabled = '';

        if (isset($param['isMixx']) and $param['isMixx'] == 0 and isset($param['researchTypeId']) and $param['researchTypeId'] != 4) {

            $isDisabled = 'disabled="disabled"';
        }

        if (!isset($param['readonly'])) {
            $param['readonly'] = 'false';
        }

        if (!isset($param['disabled'])) {
            $param['disabled'] = 'false';
        }

        if (!isset($param['isExtraValue'])) {
            $param['isExtraValue'] == 'false';
        }

        if (!isset($param['required'])) {
            $param['required'] = 'false';
        }

        if (!isset($param['isLogView'])) {
            $param['isLogView'] = 0;
        }

        $query = $this->db->query('
            SELECT 
                NE.expert_id
            FROM gaz_nifs_expert AS NE 
            WHERE NE.mod_id = ' . $param['modId'] . ' AND NE.cont_id = ' . $param['contId']);

        $numRows = $query->num_rows();
        if ($numRows > 0) {
            $i = 1;
            foreach ($query->result() as $key => $row) {

                if ($key > 0) {
                    $param['isDeleteButton'] = 1;
                }

                if ($i == 2) {
                    $htmlExtra .= '<div id="' . $param['initControlHtml'] . '">';
                }

                $htmlExtra .= '<div class="form-group row" data-hr-people-row="hr-people-row">';
                $htmlExtra .= form_label('Шинжээч', 'Шинжээч', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE));
                $htmlExtra .= '<div class="col-8">';

                $htmlExtra .= '<div class="input-group">';
                $htmlExtra .= '<span class="select2-group">';

                $htmlExtra .= self::controlHrPeopleListDropdown_model(array(
                            'isCurrenty' => $param['isCurrenty'],
                            'isLogView' => $param['isLogView'],
                            'departmentId' => $param['departmentId'],
                            'selectedId' => $row->expert_id,
                            'positionId' => $param['positionId'],
                            'modId' => $param['modId'],
                            'name' => $param['name'],
                            'readonly' => $param['readonly'],
                            'disabled' => $param['disabled'],
                            'required' => $param['required'],
                            'isExtraValue' => $param['isExtraValue']));

                $htmlExtra .= '</span>';
                $htmlExtra .= '<span class="input-group-append">';


                if (isset($param['isDeleteButton']) and $param['isDeleteButton'] == '1') {
                    $htmlExtra .= '<span class="input-group-text bg-primary border-primary text-white cursor-pointer" onclick="_removeControlHrPeople({elem:this});"><i class="icon-cancel-circle2"></i></span>';
                } else {
                    $htmlExtra .= '<span class="input-group-text bg-primary border-primary text-white cursor-pointer" onclick="_addControlHrPeople({elem:this, initControlHtml: \'' . $param['initControlHtml'] . '\', modId: 0, contId: 0, name: \'' . $param['name'] . '\', departmentId: \'' . $param['departmentId'] . '\', positionId: \'' . $param['positionId'] . '\', isExtraValue: \'' . $param['isExtraValue'] . '\'});" ' . $isDisabled . '><i class="icon-plus-circle2"></i></span>';
                }

                $htmlExtra .= '</span>';
                $htmlExtra .= '</div>';
                $htmlExtra .= '</div>';
                $htmlExtra .= '</div>';

                if ($numRows == 1) {
                    $htmlExtra .= '<div id="' . $param['initControlHtml'] . '">';
                }
                if ($i == $numRows) {
                    $htmlExtra .= '</div>';
                }

                $i++;
            }
        } else {
            $htmlExtra .= '<div class="form-group row" data-hr-people-row="hr-people-row">';
            $htmlExtra .= form_label('Шинжээч', 'Шинжээч', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE));
            $htmlExtra .= '<div class="col-8">';

            $htmlExtra .= '<div class="input-group">';
            $htmlExtra .= '<span class="select2-group init-default-hr-people">';

            $htmlExtra .= self::controlHrPeopleListDropdown_model(array(
                        'isCurrenty' => $param['isCurrenty'],
                        'departmentId' => $param['departmentId'],
                        'selectedId' => 0,
                        'positionId' => $param['positionId'],
                        'name' => $param['name'],
                        'readonly' => $param['readonly'],
                        'disabled' => $param['disabled'],
                        'required' => $param['required'],
                        'isExtraValue' => $param['isExtraValue']));
            $htmlExtra .= '</span>';
            $htmlExtra .= '<span class="input-group-append">';


            if (isset($param['isDeleteButton']) and $param['isDeleteButton'] == '1') {
                $htmlExtra .= '<span class="input-group-text bg-primary border-primary text-white cursor-pointer" onclick="_removeControlHrPeople({elem:this});"><i class="icon-cancel-circle2"></i></span>';
            } else {
                $htmlExtra .= '<span class="input-group-text bg-primary border-primary text-white cursor-pointer" onclick="_addControlHrPeople({elem:this, initControlHtml: \'' . $param['initControlHtml'] . '\',  modId: 0, contId: 0, name: \'' . $param['name'] . '\', departmentId: \'' . $param['departmentId'] . '\', positionId: \'' . $param['positionId'] . '\', isExtraValue: \'' . $param['isExtraValue'] . '\'});" ' . $isDisabled . '><i class="icon-plus-circle2"></i></span>';
            }

            $htmlExtra .= '</span>';
            $htmlExtra .= '</div>';
            $htmlExtra .= '</div>';
            $htmlExtra .= '</div>';
            $htmlExtra .= '<div id="' . $param['initControlHtml'] . '"></div>';
        }

        return $htmlExtra;
    }

    public function listsHrPeopleWork_model($param = array()) {

        $dateDiff = 0;
        $html = $string = $disabled = '';

        if (isset($param['readonly']) and strtolower($param['readonly']) == 'true') {
            $string .= ' disabled="true"';
            $disabled = 'true';
        }

        $query = $this->db->query('
            SELECT 
                HPW.id,
                HPW.people_id,
                HPW.department_id,
                HPW.position_id,
                HPW.rank_id,
                HPW.is_currenty,
                HPW.title,
                HPW.in_date,
                HPW.out_date,
                HPW.is_active,
                HPW.created_date,
                HPW.modified_date,
                HPW.created_user_id,
                HPW.modified_user_id,
                HPW.order_num
            FROM `gaz_hr_people_work` AS HPW
            WHERE HPW.people_id = ' . $param['peopleId']);

        $html .= '<fieldset class="stepy-step">';
        $html .= '<legend>';
        $html .= '<span class="text-semibold">Хөдөлмөр эрхлэлтийн байдал</span> <span class="_description"></span>';
        if (!isset($param['readonly'])) {
            $html .= '<span class="pull-right">' . form_button('addHrPeopleWork', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Нэмэх</span>', 'class="btn btn-primary btn-xs" onclick="_addHrPeopleWork({elem: this});"', 'button') . '</span>';
        }
        $html .= '<div class="clearfix"></div>';
        $html .= '</legend>';

        $html .= '<div class="table-responsive">';
        $html .= '<table class="_form-table">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th style="width:200px;">Газар, хэлтэс, алба</th>';
        $html .= '<th style="width:80px;">Албан тушаал</th>';
        $html .= '<th style="width:80px;">Цол</th>';
        $html .= '<th style="width:80px;">Ажилд орсон он сар өдөр</th>';
        $html .= '<th style="width:80px;">Ажлаас гарсан он сар өдөр</th>';
        $html .= '<th style="width:20px;" class="text-center">-</th>';
        $html .= '<th>Бусад</th>';
        if (!isset($param['readonly'])) {
            $html .= '<th style="width:50px;" class="text-center"><i class="icon-cog7"></i></th>';
        }
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody data-link="row" class="rowlink lists-hr-people-work">';

        if ($query->num_rows() > 0) {

            $i = 1;

            foreach ($query->result() as $key => $row) {
                $rowDate = 0;
                if ($row->is_currenty == 1) {
                    $dateDiff = $dateDiff + abs(strtotime(date('Y-m-d')) - strtotime($row->in_date));
                } else {
                    $dateDiff = $dateDiff + abs(strtotime($row->out_date) - strtotime($row->in_date));
                }

                $html .= '<tr class="lists-hr-people-work-tr" data-number="' . $i . '">';
                $html .= '<td class="_control"><input type="hidden" name="workAutoNumber[]" value="number' . $i . '">' . $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array('name' => 'workDepartmentId[]', 'modId' => 69, 'selectedId' => $row->department_id, 'disabled' => $disabled)) . '</td>';
                $html .= '<td class="_control">' . $this->hrPeoplePosition->controlHrPeoplePositionDropDown_model(array('name' => 'workPositionId[]', 'selectedId' => $row->position_id, 'disabled' => $disabled)) . '</td>';
                $html .= '<td class="_control">' . $this->hrPeopleRank->controlHrPeopleRankDropDown_model(array('name' => 'workRankId[]', 'selectedId' => $row->rank_id, 'disabled' => $disabled)) . '</td>';
                $html .= '<td class="_control"><input type="text" name="workInDate[]" class="_control_input init-date" value="' . $row->in_date . '" ' . $string . '></td>';
                $html .= '<td class="_control"><input type="text" name="workOutDate[]" class="_control_input init-date work-out-date-' . $i . '" value="' . $row->out_date . '" ' . $string . '></td>';
                $html .= '<td class="_control text-center"><input type="hidden" name="workIsCurrenty[]" value="' . $row->is_currenty . '" class="is-currenty" ' . $string . '>' . form_radio(array('name' => 'isCurrentyRB[]', 'class' => 'radio currentyrb', 'onclick' => '_setPeopleWorkIsCurrentyValue({elem: this, class: \'is-currenty\'});'), 0, ($row->is_currenty == 1 ? TRUE : FALSE)) . '</td>';
                $html .= '<td class="_control"><input type="text" name="workTitle[]" class="_control_input" value="' . $row->title . '" ' . $string . '></td>';
                if (!isset($param['readonly'])) {
                    $html .= '<td class="text-center">';
                    $html .= '<ul class="icons-list">';
                    $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
                    $html .= '</ul>';
                    $html .= '</td>';
                }
                $html .= '</tr>';
                $i++;
            }
        } else {

            $html .= '<tr class="lists-hr-people-work-tr" data-number="1">';
            $html .= '<td class="_control"><input type="hidden" name="workAutoNumber[]" value="number1">' . $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array('name' => 'workDepartmentId[]', 'modId' => 69, 'selectedId' => 0)) . '</td>';
            $html .= '<td class="_control">' . $this->hrPeoplePosition->controlHrPeoplePositionDropDown_model(array('name' => 'workPositionId[]', 'selectedId' => 0)) . '</td>';
            $html .= '<td class="_control">' . $this->hrPeopleRank->controlHrPeopleRankDropDown_model(array('name' => 'workRankId[]', 'selectedId' => 0)) . '</td>';
            $html .= '<td class="_control"><input type="text" name="workInDate[]" class="_control_input init-date"></td>';
            $html .= '<td class="_control"><input type="text" name="workOutDate[]" class="_control_input init-date work-out-date-1"></td>';
            $html .= '<td class="_control text-center"><input type="hidden" name="workIsCurrenty[]" value="1" class="is-currenty">' . form_radio(array('name' => 'isCurrentyRB[]', 'class' => 'radio currentyrb'), 1, TRUE) . '</td>';
            $html .= '<td class="_control"><input type="text" name="workTitle[]" class="_control_input"></td>';
            $html .= '<td class="text-center">';
            $html .= '<ul class="icons-list">';
            $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
            $html .= '</ul>';
            $html .= '</td>';
            $html .= '</tr>';

            $html .= '<tr class="lists-hr-people-work-tr" data-number="2">';
            $html .= '<td class="_control"><input type="hidden" name="workAutoNumber[]" value="number2">' . $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array('name' => 'workDepartmentId[]', 'modId' => 69, 'selectedId' => 0)) . '</td>';
            $html .= '<td class="_control">' . $this->hrPeoplePosition->controlHrPeoplePositionDropDown_model(array('name' => 'workPositionId[]', 'selectedId' => 0)) . '</td>';
            $html .= '<td class="_control">' . $this->hrPeopleRank->controlHrPeopleRankDropDown_model(array('name' => 'workRankId[]', 'selectedId' => 0)) . '</td>';
            $html .= '<td class="_control"><input type="text" name="workInDate[]" class="_control_input init-date"></td>';
            $html .= '<td class="_control"><input type="text" name="workOutDate[]" class="_control_input init-date work-out-date-2"></td>';
            $html .= '<td class="_control text-center"><input type="hidden" name="workIsCurrenty[]" value="0" class="is-currenty">' . form_radio(array('name' => 'isCurrentyRB[]', 'class' => 'radio currentyrb'), 0, FALSE) . '</td>';
            $html .= '<td class="_control"><input type="text" name="workTitle[]" class="_control_input"></td>';
            $html .= '<td class="text-center">';
            $html .= '<ul class="icons-list">';
            $html .= '<li onclick="_removeTableFormRow({elem: this, id:0});" title="Устгах"><span data-action="delete"></span></li>';
            $html .= '</ul>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        $years = floor($dateDiff / (365 * 60 * 60 * 24));
        $months = floor(($dateDiff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($dateDiff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));


        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '<div class="text-right mt-2 mb-2"><strong>Нийт ажилласан хугацаа: ' . ($years > 0 ? ($years . ' жил ') : '') . ($months > 0 ? ($months . ' сар ') : '') . ($days > 0 ? ($days . ' өдөр ') : '') . '</strong></div>';
        $html .= '</fieldset>';


        return $html;
    }

    public function deleteInsertHrPeopleWork_model($param = array()) {

        $this->db->where('people_id', $param['peopleId']);
        $this->db->delete($this->db->dbprefix . 'hr_people_work');

        $hrPeopleData = $this->getData_model(array('selectedId' => $param['peopleId']));
        $hrPeopleParam = json_decode($hrPeopleData->param);

        if ($this->input->post('workAutoNumber')) {
            foreach ($this->input->post('workAutoNumber') as $key => $value) {

                if ($this->input->post('workIsCurrenty[' . $key . ']') == 1) {
                    $hrPeopleDepartment = $this->hrPeopleDepartment->getData_model(array('selectedId' => $this->input->post('workDepartmentId[' . $key . ']')));
                    $hrPeoplePosition = $this->hrPeoplePosition->getData_model(array('selectedId' => $this->input->post('workPositionId[' . $key . ']')));
                    $hrPeopleRank = $this->hrPeopleRank->getData_model(array('selectedId' => $this->input->post('workRankId[' . $key . ']')));
                    $hrPeopleParam->work = array(
                        'department' => ($hrPeopleDepartment != false ? $hrPeopleDepartment->title : ''),
                        'position' => ($hrPeoplePosition != false ? $hrPeoplePosition->title : ''),
                        'rank' => ($hrPeopleRank != false ? $hrPeopleRank->title : ''));

                    $this->db->where('id', $param['peopleId']);
                    $this->db->update($this->db->dbprefix . 'hr_people', array('param' => json_encode($hrPeopleParam)));
                }
                $data = array(
                    array(
                        'id' => getUID('hr_people_work'),
                        'people_id' => $param['peopleId'],
                        'department_id' => $this->input->post('workDepartmentId[' . $key . ']'),
                        'is_currenty' => $this->input->post('workIsCurrenty[' . $key . ']'),
                        'position_id' => $this->input->post('workPositionId[' . $key . ']'),
                        'rank_id' => $this->input->post('workRankId[' . $key . ']'),
                        'title' => $this->input->post('workTitle[' . $key . ']'),
                        'in_date' => $this->input->post('workInDate[' . $key . ']'),
                        'out_date' => $this->input->post('workOutDate[' . $key . ']'),
                        'is_active' => 1,
                        'created_date' => date('Y-m-d H:i:s'),
                        'modified_date' => date('Y-m-d H:i:s'),
                        'created_user_id' => $this->session->adminUserId,
                        'modified_user_id' => $this->session->adminUserId,
                        'order_num' => getOrderNum(array('table' => 'hr_people_work', 'field' => 'order_num'))));

                $this->db->insert_batch($this->db->dbprefix . 'hr_people_work', $data);
            }
        } else {

            $hrPeopleDepartment = $this->hrPeopleDepartment->getData_model(array('selectedId' => $this->session->userdata['adminDepartmentId']));
            $hrPeoplePosition = $this->hrPeoplePosition->getData_model(array('selectedId' => $this->session->userdata['adminPositionId']));
            $hrPeopleRank = $this->hrPeopleRank->getData_model(array('selectedId' => $this->session->userdata['adminRankId']));
            $hrPeopleParam->work = array(
                'department' => ($hrPeopleDepartment != false ? $hrPeopleDepartment->title : ''),
                'position' => ($hrPeoplePosition != false ? $hrPeoplePosition->title : ''),
                'rank' => ($hrPeopleRank != false ? $hrPeopleRank->title : ''));

            $this->db->where('id', $param['peopleId']);
            $this->db->update($this->db->dbprefix . 'hr_people', array('param' => json_encode($hrPeopleParam)));

            $data = array(
                array(
                    'id' => getUID('hr_people_work'),
                    'people_id' => $param['peopleId'],
                    'department_id' => $this->session->userdata['adminDepartmentId'],
                    'is_currenty' => 1,
                    'position_id' => $this->session->userdata['adminPositionId'],
                    'rank_id' => $this->session->userdata['adminRankId'],
                    'title' => '',
                    'in_date' => '',
                    'out_date' => '',
                    'is_active' => 1,
                    'created_date' => date('Y-m-d H:i:s'),
                    'modified_date' => date('Y-m-d H:i:s'),
                    'created_user_id' => $this->session->adminUserId,
                    'modified_user_id' => $this->session->adminUserId,
                    'order_num' => getOrderNum(array('table' => 'hr_people_work', 'field' => 'order_num'))));

            $this->db->insert_batch($this->db->dbprefix . 'hr_people_work', $data);
        }
    }

    public function import_model($param = array('pic' => '', 'getUID' => 0)) {


        include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';

        $inputFileName = './people.xlsx';

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
            $rowData = $rowData['0'];

            //  Insert row data array into your database of choice here
//            $fname = explode('.', $rowData['1']);
//            if (count($fname) > 1) {
//                $fname = $fname['1'];
//            } else {
//                $fname = '';
//            }
// . ', <br> Албан тушаал: ' . $rowData['4'] . ', <br> Төрөл: ' . $rowData['3']
            $this->data = array(
                array(
                    'id' => getUID('hr_people'),
                    'mod_id' => 60,
                    'cat_id' => 0,
                    'partner_id' => 0,
                    'department_id' => 0,
                    'position_id' => 0,
                    'rank_id' => 0,
                    'position_description' => 'Цол: ' . $rowData['2'],
                    'register' => '',
                    'social_insurance' => '',
                    'health_insurance' => '',
                    'family_name' => '',
                    'fname' => $rowData['1'],
                    'lname' => $rowData['0'],
                    'sex' => 1,
                    'birthday' => '',
                    'pic' => '',
                    'birth_city_id' => 0,
                    'birth_soum_id' => 0,
                    'birth_street_id' => 0,
                    'birth_address' => '',
                    'nationality' => '',
                    'social_origin' => '',
                    'live_city_id' => 0,
                    'live_soum_id' => 0,
                    'live_street_id' => 0,
                    'live_address' => '',
                    'phone' => '',
                    'fax' => '',
                    'email' => '',
                    'post_address' => '',
                    'special_people' => '',
                    'special_phone' => '',
                    'is_active' => 1,
                    'created_date' => date('Y-m-d H:i:s'),
                    'modified_date' => date('Y-m-d H:i:s'),
                    'created_user_id' => $this->session->adminUserId,
                    'modified_user_id' => 0,
                    'param' => json_encode(array(
                        'birth' => array('city' => 13, 'soum' => 42, 'street' => 142),
                        'live' => array('city' => 13, 'soum' => 42, 'street' => 142),
                        'position' => array('partner' => '', 'department' => '', 'position' => '', 'rank' => '')
                    )),
                    'order_num' => getOrderNum(array('table' => 'hr_people', 'field' => 'order_num'))
                )
            );

            $this->db->insert_batch($this->db->dbprefix . 'hr_people', $this->data);
        }
    }

    public function dataUpdate_model($param = array()) {

        $this->query = $this->db->query('
            SELECT 
                HP.id,
                HP.param,
                CURRENT_WORK.department_id,
                CURRENT_WORK.position_id,
                CURRENT_WORK.rank_id
            FROM `gaz_hr_people` AS HP
            LEFT JOIN (SELECT * FROM gaz_hr_people_work WHERE is_currenty = 1) AS CURRENT_WORK ON HP.id = CURRENT_WORK.people_id');

        foreach ($this->query->result() as $key => $row) {

            $param = json_decode($row->param);

            $hrPeopleDepartment = $this->hrPeopleDepartment->getData_model(array('selectedId' => $row->department_id));
            $hrPeoplePosition = $this->hrPeoplePosition->getData_model(array('selectedId' => $row->position_id));
            $hrPeopleRank = $this->hrPeopleRank->getData_model(array('selectedId' => $row->rank_id));

            $newParam = array('birth' => $param->birth, 'live' => $param->live, 'work' => array(
                    'department' => ($hrPeopleDepartment != false ? $hrPeopleDepartment->title : ''),
                    'position' => ($hrPeoplePosition != false ? $hrPeoplePosition->title : ''),
                    'rank' => ($hrPeopleRank != false ? $hrPeopleRank->title : '')));
            $this->db->where('id', $row->id);
            if ($this->db->update($this->db->dbprefix . 'hr_people', array('param' => json_encode($newParam)))) {
                echo '<pre>';
                var_dump('ok');
                echo '</pre>';
            } else {
                echo '<pre>';
                var_dump('error');
                echo '</pre>';
            }
        }
    }

    public function dataUpdate1_model($param = array()) {

        $this->query = $this->db->query('
            SELECT 
                HP.id,
                HP.param
            FROM `gaz_hr_people` AS HP');

        foreach ($this->query->result() as $key => $row) {

            $param = json_decode($row->param);

            $newParam = array('birth' => $param->birth, 'live' => $param->live);
            $this->db->where('id', $row->id);
            if ($this->db->update($this->db->dbprefix . 'hr_people', array('param' => json_encode($newParam)))) {
                echo '<pre>';
                var_dump('ok');
                echo '</pre>';
            } else {
                echo '<pre>';
                var_dump('error');
                echo '</pre>';
            }
        }
    }

    public function dataUpdate2_model($param = array()) {

        $this->query = $this->db->query('
            SELECT 
                HP.id,
                HP.param
            FROM `gaz_hr_people` AS HP');

        foreach ($this->query->result() as $key => $row) {

            $param = json_decode($row->param);

            if (is_object($param->work->departmetn)) {
                $param->work = array('department' => '', 'position' => '', 'rank' => '');
                $this->db->where('id', $row->id);
                if ($this->db->update($this->db->dbprefix . 'hr_people', array('param' => json_encode($param)))) {
                    echo '<pre>';
                    var_dump('ok');
                    echo '</pre>';
                } else {
                    echo '<pre>';
                    var_dump('error');
                    echo '</pre>';
                }
            }
        }
    }

    public function duplicate_model() {

        $html = $this->queryString = '';

        $this->query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.mod_id,
                HPD.cat_id,
                HPD.parent_id,
                HPD.title AS title,
                HPD.is_active,
                HPD.created_date,
                HPD.modified_date,
                HPD.created_user_id,
                HPD.modified_user_id,
                HPD.order_num
            FROM `gaz_hr_people_department` AS HPD
            WHERE HPD.parent_id = 0 ' . $this->queryString . '
            ORDER BY HPD.title ASC');

        if ($this->query->num_rows() > 0) {

            $html .= '<div class="table-responsive">';
            $html .= '<table class="table">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th style="width:30px;">#</th>';
            $html .= '<th>Гарчиг</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody data-link="row" class="rowlink">';

            $i = 0;

            foreach ($this->query->result() as $key => $row) {

                $html .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '">';
                $html .= '<td>' . ++$i . '</td>';
                $html .= '<td class="context-menu-hr-people-department-selected-row">' . $row->id . ' - ' . $row->title . '</td>';
                $html .= '</tr>';

                $queryPeople = $this->db->query('
                    SELECT
                        HP.id,
                        HP.fname,
                        HP.lname,
                        SUBSTRING(HP.lname, 1, 1) AS lnamelname,
                        HP.param
                    FROM `gaz_hr_people` AS HP
                    WHERE HP.department_id = ' . $row->id . ' 
                    ORDER BY HP.fname ASC');

                if ($queryPeople->num_rows() > 0) {
                    $k = 1;
                    foreach ($queryPeople->result() as $key => $rowPeople) {

                        $paramP = '';

                        if ($rowPeople->param != '') {
                            $paramP = json_decode($rowPeople->param);
                            $paramP = $paramP->position;
                        } else {
                            $paramP = json_decode(json_encode(array('position' => '', 'rank' => '')));
                        }
                        $html .= '<tr>';
                        $html .= '<td></td>';
                        $html .= '<td style="padding-left: 30px;">' . $k++ . '. ' . $rowPeople->fname . '.' . $rowPeople->lnamelname . ' (' . $paramP->position . ', ' . $paramP->rank . ') - ' . $rowPeople->id . '</td>';
                        $html .= '</tr>';
                    }
                }

                $html .= self::duplicateListsChild_model(array('parentId' => $row->id, 'space' => 50, 'autoNumber' => $i));
            }

            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '</div>';
            $html .= '<div class="panel-footer">';
            $html .= '<div class="navbar text-right"></div>';
            $html .= '</div>';
        } else {
            $html .= '<div class="panel-body">';
            $html .= '<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Бичлэг байхгүй</div>';
            $html .= '</div>';
        }

        return $html;
    }

    public function duplicateListsChild_model($param = array('parentId' => 0, 'space' => 10, 'autoNumber' => 1)) {

        $html = '';

        $this->query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title
            FROM `gaz_hr_people_department` AS HPD
            WHERE HPD.parent_id = ' . $param['parentId'] . '
            ORDER BY HPD.order_num DESC');
        if ($this->query->num_rows() > 0) {

            $j = 1;
            foreach ($this->query->result() as $row) {

                $html .= '<tr>';
                $html .= '<td>' . $param['autoNumber'] . '.' . $j . '</td>';
                $html .= '<td class="context-menu-hr-people-department-selected-row" style="padding-left:' . $param['space'] . 'px;">' . $row->id . ' - ' . $row->title . '</td>';
                $html .= '</tr>';

                $queryPeople = $this->db->query('
                    SELECT
                        HP.id,
                        HP.fname,
                        HP.lname,
                        SUBSTRING(HP.lname, 1, 1) AS lnamelname,
                        HP.param
                    FROM `gaz_hr_people` AS HP
                    WHERE HP.department_id = ' . $row->id . ' 
                    ORDER BY HP.fname ASC');

                if ($queryPeople->num_rows() > 0) {
                    $k = 1;
                    foreach ($queryPeople->result() as $key => $rowPeople) {

                        if ($rowPeople->param != '') {
                            $paramChild = json_decode($rowPeople->param);
                            $paramChild = $paramChild->position;
                        } else {
                            $paramChild = json_decode(json_encode(array('position' => '', 'rank' => '')));
                        }


                        $html .= '<tr>';
                        $html .= '<td></td>';
                        $html .= '<td style="padding-left:' . $param['space'] . 'px;">' . $k++ . '. ' . $rowPeople->fname . '.' . $rowPeople->lnamelname . ' (' . $paramChild->position . ', ' . $paramChild->rank . ') - ' . $rowPeople->id . '</td>';
                        $html .= '</tr>';
                    }
                }


                $j++;
                $html .= self::duplicateListsChild_model(array('parentId' => $row->id, 'space' => (intval($param['space']) + 30), 'autoNumber' => $j));
            }
        }
        return $html;
    }

    public function notDepartmentList_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $html = '';

        $this->query = $this->db->query('
            SELECT 
                HP.id,
                HP.mod_id,
                HP.cat_id,
                HP.partner_id,
                HP.department_id,
                HP.position_id,
                HP.rank_id,
                HP.position_description,
                HP.register,
                HP.social_insurance,
                HP.health_insurance,
                HP.family_name,
                HP.fname,
                HP.lname,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name,
                HP.sex,
                HP.birthday,
                (case when (HP.pic is null or HP.pic = \'\') then \'default.svg\' else (case when (HP.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', HP.pic) end) end) as pic,
                HP.birth_city_id,
                HP.birth_soum_id,
                HP.birth_street_id,
                HP.birth_address,
                HP.nationality,
                HP.social_origin,
                HP.live_city_id,
                HP.live_soum_id,
                HP.live_street_id,
                HP.live_address,
                HP.phone,
                HP.fax,
                HP.email,
                HP.post_address,
                HP.special_people,
                HP.special_phone,
                HP.is_active,
                HP.created_date,
                HP.modified_date,
                HP.created_user_id,
                HP.modified_user_id,
                HP.param,
                HP.order_num,
                HP.user_partner_id
            FROM `gaz_hr_people` AS HP
            WHERE 1 = 1 AND HP.department_id = 0
            ORDER BY HP.order_num DESC');

        if ($this->query->num_rows() > 0) {

            $html .= '<table class="table">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th style="width:30px;">#</th>';
            $html .= '<th style="width:80px;"> Зураг</th>';
            $html .= '<th style="width:400px;">Овог, нэр, албан тушаал</th>';
            $html .= '<th>Хаяг, утас</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody data-link="row" class="rowlink">';

            $i = 0;
            foreach ($this->query->result() as $key => $row) {

                if ($row->param != '') {
                    $this->param = json_decode($row->param);
                } else {
                    $this->param = json_decode(json_encode(array(
                        'birth' => array('city' => '', 'soum' => '', 'street' => ''),
                        'live' => array('city' => '', 'soum' => '', 'street' => ''),
                        'position' => array('partner' => '', 'department' => '', 'position' => '', 'rank' => ''))));
                }

                $html .= '<tr data-id="' . $row->id . '" data-uid="' . $row->created_user_id . '">';
                $html .= '<td>' . ++$i . '</td>';
                $html .= '<td class="context-menu-hr-people-selected-row text-center"><img src="' . UPLOADS_USER_PATH . $row->pic . '" style="max-width:80px;"></td>';
                $html .= '<td class="context-menu-hr-people-selected-row">' . $row->full_name . ' - ' . $this->param->position->rank . '<br> (' . $this->param->position->department . ', ' . $this->param->position->position . ')</td>';
                $html .= '<td class="context-menu-hr-people-selected-row">' . $this->param->live->city . ', ' . $this->param->live->soum . ', ' . $this->param->live->street . ', ' . $row->live_address . ', ' . $row->phone . ', ' . $row->email . '</td>';
                $html .= '</tr>';
            }

            $html .= '</tbody>';
            $html .= '</table>';
        }


        return $html;
    }

    public function updateWorkList_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $html = '';

        $this->db->where('1', 1);
        $this->db->delete($this->db->dbprefix . 'hr_people_work');

        $this->query = $this->db->query('
            SELECT 
                HP.id,
                HP.department_id,
                HP.position_id,
                HP.rank_id,
                HP.created_date,
                HP.modified_date,
                HP.created_user_id,
                HP.modified_user_id
            FROM `gaz_hr_people` AS HP');

        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $key => $row) {

                $this->data = array(
                    array(
                        'id' => getUID('hr_people_work'),
                        'people_id' => $row->id,
                        'department_id' => $row->department_id,
                        'position_id' => $row->position_id,
                        'rank_id' => $row->rank_id,
                        'is_currenty' => 1,
                        'title' => '',
                        'in_date' => '',
                        'out_date' => '',
                        'is_active' => 1,
                        'created_date' => $row->created_date,
                        'modified_date' => $row->modified_date,
                        'created_user_id' => $row->created_user_id,
                        'modified_user_id' => $row->modified_user_id,
                        'order_num' => getOrderNum(array('table' => 'hr_people_work', 'field' => 'order_num'))));
                $this->db->insert_batch($this->db->dbprefix . 'hr_people_work', $this->data);
            }
        }


        return $html;
    }

    public function expertUpdate_model() {

        $this->query = $this->db->query('
            SELECT 
                NE.id,
                NE.expert_id
            FROM `gaz_nifs_expert` AS NE');

        foreach ($this->query->result() as $key => $row) {

            if ($row->expert_id == 1000001) {
                $row->expert_id = 643;

                $this->db->where('id', $row->id);
                if ($this->db->update($this->db->dbprefix . 'nifs_expert', array('expert_id' => 643))) {
                    echo '<pre>';
                    var_dump('ok');
                    echo '</pre>';
                }
            }

            if ($row->expert_id == 1000002) {
                $row->expert_id = 644;

                $this->db->where('id', $row->id);
                if ($this->db->update($this->db->dbprefix . 'nifs_expert', array('expert_id' => 644))) {
                    echo '<pre>';
                    var_dump('ok');
                    echo '</pre>';
                }
            }

            if ($row->expert_id == 1000003) {
                $row->expert_id = 645;

                $this->db->where('id', $row->id);
                if ($this->db->update($this->db->dbprefix . 'nifs_expert', array('expert_id' => 645))) {
                    echo '<pre>';
                    var_dump('ok');
                    echo '</pre>';
                }
            }

            if ($row->expert_id == 1000004) {
                $row->expert_id = 646;

                $this->db->where('id', $row->id);
                if ($this->db->update($this->db->dbprefix . 'nifs_expert', array('expert_id' => 646))) {
                    echo '<pre>';
                    var_dump('ok');
                    echo '</pre>';
                }
            }
        }
    }

}
