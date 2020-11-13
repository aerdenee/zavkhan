<?php

class SnifsDoctorView_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Suser_model', 'user');
        $this->load->model('SnifsMotive_model', 'nifsMotive');
        $this->load->model('SnifsResearchType_model', 'nifsResearchType');
        $this->load->model('SnifsCrimeType_model', 'nifsCrimeType');
        $this->load->model('SreportMenu_model', 'sreportMenu');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('SnifsWhere_model', 'nifsWhere');
        $this->load->model('ShrPeople_model', 'hrPeople');

        $this->modId = 51;
        $this->chartCatId = 399;

        $this->nifsCrimeTypeId = 353;
        $this->nifsQuestionCatId = 371;
        $this->nifsSolutionCatId = 359;
        $this->nifsCloseTypeCatId = 365;
        $this->nifsResearchTypeCatId = 380;
        $this->nifsMotiveCatId = 386;
        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->isActiveDepartment = 'is_active_nifs_doctor_view';
        $this->reportDefaultDayInterval = 7;

        if ($this->nifsDepartmentId == 7) {
            $this->nifsDepartmentId = 4;
        }
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => $this->modId,
            'cat_id' => 319,
            'create_number' => getCreateNumber(array('createNumber' => 0, 'table' => 'nifs_doctor_view', 'departmentId' => $this->nifsDepartmentId)),
            'in_date' => date('Y-m-d H:i:s'),
            'out_date' => date('Y-m-d H:i:s'),
            'fname' => '',
            'lname' => '',
            'register' => '',
            'age' => '',
            'sex' => 1,
            'phone' => '',
            'description' => '',
            'work_id' => 0,
            'partner_id' => 0,
            'expert_name' => '',
            'crime_date' => '',
            'short_value_id' => 0,
            'short_value' => '',
            'expert_id' => 0,
            'where_id' => 0,
            'motive_id' => 39,
            'payment' => 0,
            'close_description' => '',
            'close_date' => date('Y-m-d H:i:s'),
            'close_type_id' => 0,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'order_num' => getOrderNum(array('table' => 'nifs_doctor_view', 'field' => 'order_num')),
            'year' => '',
            'is_active' => 1,
            'is_age_infinitive' => 0,
            'protocol_number' => '',
            'protocol_in_date' => '',
            'protocol_out_date' => '',
            'is_sperm' => 0,
            'is_crime_ship' => 0,
            'is_bzhu' => 0,
            'is_skin' => 0,
            'injury_id' => 0,
            'payment_description' => ''
        )));
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cat_id,
                create_number,
                in_date,
                out_date,
                fname,
                lname,
                register,
                age,
                sex,
                phone,
                description,
                short_value_id,
                short_value,
                work_id,
                motive_id,
                where_id,
                partner_id,
                expert_name,
                IF(crime_date != \'0000-00-00 00:00:00\', DATE(crime_date), \'\') AS crime_date,
                expert_id,
                where_id,
                payment,
                close_description,
                close_date,
                close_type_id,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id,
                order_num,
                year,
                is_active,
                is_age_infinitive,
                \'\' AS weight,
                \'\' AS solution_id,
                protocol_number,
                DATE(protocol_in_date) AS protocol_in_date,
                DATE(protocol_out_date) AS protocol_out_date,
                is_sperm,
                is_crime_ship,
                is_bzhu,
                is_skin,
                injury_id,
                payment_description
            FROM `gaz_nifs_doctor_view`
            WHERE id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function listsCount_model($param = array()) {

        $query = $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {

            $queryString .= '';
        } else if ($auth->our->read and ! $auth->your->read) {

            $queryString .= ' AND NDV.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NDV.created_user_id = -1';
        }

        if ($param['departmentId'] != 0) {

            $queryString .= ' AND NDV.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
        } else {

            $queryString .= ' AND NDV.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        }

        if ($param['createNumber'] != '') {
            $queryString .= ' AND NDV.create_number = ' . $param['createNumber'];
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $queryString .= ' AND LOWER(NDV.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $queryString .= ' AND LOWER(NDV.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Регистрийн дугаар
                        $queryString .= ' AND LOWER(NDV.register) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Утас
                        $queryString .= ' AND LOWER(NDV.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Тайлбар
                        $queryString .= ' AND LOWER(NDV.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 6: {   //Албан хаагч
                        $queryString .= ' AND LOWER(NDV.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 7: {   //Дүгнэлтийн утга
                        $queryString .= ' AND LOWER(NDV.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' AND (
                        LOWER(NDV.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NDV.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NDV.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NDV.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NDV.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NDV.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                        break;
                    }
            }
        }

        if ($param['closeDescription'] != '') {
            $queryString .= ' AND LOWER(NDV.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\')';
        }

        if ($param['workId'] != 0) {
            $queryString .= ' AND NDV.work_id = ' . $param['workId'];
        } else if ($param['workId'] == 'all') {
            $queryString .= ' AND NDV.work_id != 0';
        }

        if ($param['motiveId'] != 0) {
            $queryString .= ' AND NDV.motive_id = ' . $param['motiveId'];
        } else if ($param['motiveId'] == 'all') {
            $queryString .= ' AND NDV.motive_id != 0';
        }

        if ($param['partnerId'] != 0) {
            $queryString .= ' AND NDV.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
        } else if ($param['partnerId'] == 'all') {
            $queryString .= ' AND NDV.partner_id != 0';
        }

        if ($param['shortValueId'] != 0) {
            $queryString .= ' AND NDV.short_value_id = ' . $param['shortValueId'];
        } else if ($param['shortValueId'] == 'all') {
            $queryString .= ' AND NDV.short_value_id != 0';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NDV.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NDV.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NDV.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND \'' . $param['outDate'] . '\' >= DATE(NDV.out_date)';
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NDV.protocol_in_date) AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NDV.protocol_in_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NDV.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NDV.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NDV.close_date) AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NDV.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NDV.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NDV.close_date)';
        }

        if ($param['crimeInDate'] != '' and $param['crimeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['crimeInDate'] . '\') <= DATE(NDV.crime_date) AND DATE(\'' . $param['crimeOutDate'] . '\') >= DATE(NDV.crime_date)';
        } else if ($param['crimeInDate'] != '' and $param['crimeOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['crimeInDate'] . '\') <= DATE(NDV.crime_date)';
        } else if ($param['crimeInDate'] == '' and $param['crimeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['crimeOutDate'] . '\') >= DATE(NDV.crime_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '' and $param['crimeInDate'] == '' and $param['crimeOutDate'] == '') {

            $queryString .= ' AND NDV.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['isAgeInfinitive'] == 1) {
            $queryString .= ' AND NDV.is_age_infinitive = ' . $param['isAgeInfinitive'];
        } else {
            if ($param['age1'] != '' and $param['age2'] != '') {
                $queryString .= ' AND (NDV.age >= ' . $param['age1'] . ' AND NDV.age <= ' . $param['age2'] . ')';
            } elseif ($param['age1'] != '' and $param['age2'] == '') {
                $queryString .= ' AND \'' . $param['age1'] . '\' >= NDV.age';
            } elseif ($param['age1'] == '' and $param['age2'] != '') {
                $queryString .= ' AND \'' . $param['age2'] . '\' <= NDV.age';
            }
        }

        if ($param['isAgeInfinitive'] != 0) {
            $queryString .= ' AND NDV.is_age_infinitive = ' . $param['isAgeInfinitive'];
        }

        if ($param['crimeShortValueId'] != 0) {
            $queryString .= ' AND NDV.crime_short_value_id = ' . $param['crimeShortValueId'];
        } else if ($param['crimeShortValueId'] == 'all') {
            $queryString .= ' AND NDV.crime_short_value_id != 0';
        }

        if ($param['expertId'] != 0) {
            $queryString .= ' AND NDV.expert_id = ' . $param['expertId'];
        } else if ($param['expertId'] == 'all') {
            $queryString .= ' AND NDV.expert_id != 0';
        }

        if ($param['whereId'] != 0) {

            $queryString .= ' AND NDV.where_id = ' . $param['whereId'];
        } else if ($param['whereId'] == 'all') {

            $queryString .= ' AND NDV.where_id != 0';
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND NDV.cat_id = ' . $param['catId'];
        } else if ($param['catId'] == 'all') {
            $queryString .= ' AND NDV.cat_id != 0';
        }

        if ($param['payment'] == 1) {
            $queryString .= ' AND NDV.payment = 1';
        }

        if ($param['payment'] == 2) {
            $queryString .= ' AND NDV.payment = 0';
        }

        if ($param['payment'] == 3) {
            $queryString .= ' AND NDV.payment = 2';
        }

        if ($param['isSperm'] == 1) {
            $queryString .= ' AND NDV.is_sperm = 1';
        }

        if ($param['isSperm'] == 2) {
            $queryString .= ' AND NDV.is_sperm = 0';
        }

        if ($param['sex'] == 1) {
            $queryString .= ' AND NDV.sex = 1';
        }

        if ($param['sex'] == 2) {
            $queryString .= ' AND NDV.sex = 0';
        }

        if ($param['closeTypeId'] > 0) {
            $queryString .= ' AND NDV.close_type_id = ' . $param['closeTypeId'];
        } else if ($param['closeTypeId'] == 'all') {
            $queryString .= ' AND NDV.close_type_id != 0';
        }

        if ($param['isCrimeShip'] == 1) {
            $queryString .= ' AND NDV.is_crime_ship = 1';
        }

        if ($param['isCrimeShip'] == 2) {
            $queryString .= ' AND NDV.is_crime_ship = 0';
        }

        if ($param['closeDescription'] != '') {

            $queryString .= ' AND (LOWER(NDV.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NDV.close_type_id != 0 AND DATE(NDV.close_date) <= DATE(NDV.out_date)';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NDV.close_type_id = 0 AND CURDATE() <= DATE(NDV.out_date)';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NDV.close_type_id != 0 AND DATE(NDV.close_date) > DATE(NDV.out_date)';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NDV.close_type_id = 0 AND DATE(\'' . $param['outDate'] . '\') >= DATE(NDV.out_date)';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND DATE(NDV.protocol_out_date) < DATE(NDV.in_date)';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NDV.close_type_id = 0';
        }

        $query = $this->db->query('
            SELECT 
                NDV.id
            FROM `gaz_nifs_doctor_view` AS NDV
            WHERE 1 = 1 ' . $queryString);

        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $data = array();

        $query = $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {

            $queryString .= '';
        } else if ($auth->our->read and ! $auth->your->read) {

            $queryString .= ' AND NDV.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NDV.created_user_id = -1';
        }

        if ($param['departmentId'] != 0) {

            $queryString .= ' AND NDV.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
        } else {

            $queryString .= ' AND NDV.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        }

        if ($param['createNumber'] != '') {
            $queryString .= ' AND NDV.create_number = ' . $param['createNumber'];
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $queryString .= ' AND LOWER(NDV.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $queryString .= ' AND LOWER(NDV.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Регистрийн дугаар
                        $queryString .= ' AND LOWER(NDV.register) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Утас
                        $queryString .= ' AND LOWER(NDV.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Тайлбар
                        $queryString .= ' AND LOWER(NDV.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 6: {   //Албан хаагч
                        $queryString .= ' AND LOWER(NDV.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 7: {   //Дүгнэлтийн утга
                        $queryString .= ' AND LOWER(NDV.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' AND (
                        LOWER(NDV.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NDV.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NDV.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NDV.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NDV.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NDV.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                        break;
                    }
            }
        }

        if ($param['closeDescription'] != '') {
            $queryString .= ' AND LOWER(NDV.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\')';
        }

        if ($param['workId'] != 0) {
            $queryString .= ' AND NDV.work_id = ' . $param['workId'];
        } else if ($param['workId'] == 'all') {
            $queryString .= ' AND NDV.work_id != 0';
        }

        if ($param['motiveId'] != 0) {
            $queryString .= ' AND NDV.motive_id = ' . $param['motiveId'];
        } else if ($param['motiveId'] == 'all') {
            $queryString .= ' AND NDV.motive_id != 0';
        }

        if ($param['partnerId'] != 0) {
            $queryString .= ' AND NDV.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
        } else if ($param['partnerId'] == 'all') {
            $queryString .= ' AND NDV.partner_id != 0';
        }

        if ($param['shortValueId'] != 0) {
            $queryString .= ' AND NDV.short_value_id = ' . $param['shortValueId'];
        } else if ($param['shortValueId'] == 'all') {
            $queryString .= ' AND NDV.short_value_id != 0';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NDV.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NDV.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NDV.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND \'' . $param['outDate'] . '\' >= DATE(NDV.out_date)';
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NDV.protocol_in_date) AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NDV.protocol_in_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NDV.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NDV.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NDV.close_date) AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NDV.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NDV.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NDV.close_date)';
        }

        if ($param['crimeInDate'] != '' and $param['crimeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['crimeInDate'] . '\') <= DATE(NDV.crime_date) AND DATE(\'' . $param['crimeOutDate'] . '\') >= DATE(NDV.crime_date)';
        } else if ($param['crimeInDate'] != '' and $param['crimeOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['crimeInDate'] . '\') <= DATE(NDV.crime_date)';
        } else if ($param['crimeInDate'] == '' and $param['crimeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['crimeOutDate'] . '\') >= DATE(NDV.crime_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '' and $param['crimeInDate'] == '' and $param['crimeOutDate'] == '') {

            $queryString .= ' AND NDV.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['isAgeInfinitive'] == 1) {
            $queryString .= ' AND NDV.is_age_infinitive = ' . $param['isAgeInfinitive'];
        } else {
            if ($param['age1'] != '' and $param['age2'] != '') {
                $queryString .= ' AND (NDV.age >= ' . $param['age1'] . ' AND NDV.age <= ' . $param['age2'] . ')';
            } elseif ($param['age1'] != '' and $param['age2'] == '') {
                $queryString .= ' AND \'' . $param['age1'] . '\' >= NDV.age';
            } elseif ($param['age1'] == '' and $param['age2'] != '') {
                $queryString .= ' AND \'' . $param['age2'] . '\' <= NDV.age';
            }
        }

        if ($param['isAgeInfinitive'] != 0) {
            $queryString .= ' AND NDV.is_age_infinitive = ' . $param['isAgeInfinitive'];
        }

        if ($param['crimeShortValueId'] != 0) {
            $queryString .= ' AND NDV.crime_short_value_id = ' . $param['crimeShortValueId'];
        } else if ($param['crimeShortValueId'] == 'all') {
            $queryString .= ' AND NDV.crime_short_value_id != 0';
        }

        if ($param['expertId'] != 0) {
            $queryString .= ' AND NDV.expert_id = ' . $param['expertId'];
        } else if ($param['expertId'] == 'all') {
            $queryString .= ' AND NDV.expert_id != 0';
        }

        if ($param['whereId'] != 0) {

            $queryString .= ' AND NDV.where_id = ' . $param['whereId'];
        } else if ($param['whereId'] == 'all') {

            $queryString .= ' AND NDV.where_id != 0';
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND NDV.cat_id = ' . $param['catId'];
        } else if ($param['catId'] == 'all') {
            $queryString .= ' AND NDV.cat_id != 0';
        }

        if ($param['payment'] == 1) {
            $queryString .= ' AND NDV.payment = 1';
        }

        if ($param['payment'] == 2) {
            $queryString .= ' AND NDV.payment = 0';
        }

        if ($param['payment'] == 3) {
            $queryString .= ' AND NDV.payment = 2';
        }

        if ($param['isSperm'] == 1) {
            $queryString .= ' AND NDV.is_sperm = 1';
        }

        if ($param['isSperm'] == 2) {
            $queryString .= ' AND NDV.is_sperm = 0';
        }

        if ($param['sex'] == 1) {
            $queryString .= ' AND NDV.sex = 1';
        }

        if ($param['sex'] == 2) {
            $queryString .= ' AND NDV.sex = 0';
        }

        if ($param['closeTypeId'] > 0) {
            $queryString .= ' AND NDV.close_type_id = ' . $param['closeTypeId'];
        } else if ($param['closeTypeId'] == 'all') {
            $queryString .= ' AND NDV.close_type_id != 0';
        }

        if ($param['isCrimeShip'] == 1) {
            $queryString .= ' AND NDV.is_crime_ship = 1';
        }

        if ($param['isCrimeShip'] == 2) {
            $queryString .= ' AND NDV.is_crime_ship = 0';
        }

        if ($param['closeDescription'] != '') {

            $queryString .= ' AND (LOWER(NDV.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NDV.close_type_id != 0 AND DATE(NDV.close_date) <= DATE(NDV.out_date)';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NDV.close_type_id = 0 AND CURDATE() <= DATE(NDV.out_date)';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NDV.close_type_id != 0 AND DATE(NDV.close_date) > DATE(NDV.out_date)';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NDV.close_type_id = 0 AND DATE(\'' . $param['outDate'] . '\') >= DATE(NDV.out_date)';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND DATE(NDV.protocol_out_date) < DATE(NDV.in_date)';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NDV.close_type_id = 0';
        }

        $query = $this->db->query('
            SELECT
                NDV.id,
                NDV.mod_id,
                NDV.cat_id,
                NDV.created_user_id,
                NDV.create_number,
                CONCAT("Э: ", DATE_FORMAT(NDV.in_date, \'%Y-%m-%d %H:%i\'), "<br>", "Д: ", DATE_FORMAT(NDV.out_date, \'%Y-%m-%d\')) AS in_out_date,
                (IF(NDV.lname != \'\', CONCAT(NDV.lname, "-н ", "<strong>", NDV.fname, "</strong>"), NDV.fname)) AS full_name,
                (IF(NDV.register != \'\', CONCAT(NDV.register), \'\')) AS register,
                NDV.age,
                (CASE 
                        WHEN (NDV.is_age_infinitive = 1) THEN \'\'
                        WHEN (NDV.is_age_infinitive = 0 AND NDV.age > 0 AND NDV.age < 1) THEN CONCAT(", ", CAST(NDV.age AS DECIMAL(10,1)), " сартай")
                        ELSE IF(NDV.is_age_infinitive = 0 AND NDV.age >= 1, CONCAT(", ", CAST(NDV.age AS DECIMAL(10)), " настай"), "")
                    END) AS age,
                (IF(NDV.sex = 1, \', эр\', \', эм\')) AS sex,
                NDV.work_id,
                NDV.partner_id,
                NDV.expert_name,
                NDV.short_value_id,
                IF(NDV.short_value != \'\', CONCAT(\', \', NDV.short_value), \'\') AS short_value,
                NDV.description,
                (IF(NDV.expert_id != 0, \'background-color: transparent;\', \'background-color: #2196F3; color:rgba(255,255,255,0.8);\')) AS expert_status,
                NDV.where_id,
                NDV.close_type_id,
                DATE(NDV.close_date) AS close_date,
                NDV.close_description,
                (CASE 
                    WHEN (NDV.payment = 1) THEN CONCAT(\'Төлбөр төлсөн\', \'<br>\', NDV.description)
                    WHEN NDV.payment = 0 THEN CONCAT(\'Төлбөр төлөөгүй\', \'<br>\', NDV.description)
                    ELSE CONCAT(\'Төлбөрөөс чөлөөлсөн\', \'<br>\', NDV.payment_description)
                END) AS payment,
                (CASE 
                    WHEN (NDV.close_type_id != 0 AND NDV.close_date > NDV.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                    WHEN NDV.close_type_id = 0 AND NOW() > NDV.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                    ELSE \'background-color: transparent;\'
                END) AS row_status,
                (CASE 
                    WHEN (NDV.send_document_chemical_id != 0 AND NDV.send_document_chemical_close_type_id != 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/chemical.svg" style="width:16px;" alt="Хими">\'
                    WHEN (NDV.send_document_chemical_id != 0 AND NDV.send_document_chemical_close_type_id = 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/chemical-grey.svg" style="width:16px;" alt="Хими">\'
                    ELSE \'\'
                END) AS send_document_chemical,
                (CASE 
                    WHEN (NDV.send_document_biology_id != 0 AND NDV.send_document_biology_close_type_id != 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/dna.svg" style="width:16px;" alt="Биологи">\'
                    WHEN (NDV.send_document_biology_id != 0 AND NDV.send_document_biology_close_type_id = 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/dna-grey.svg" style="width:16px;" alt="Биологи">\'
                    ELSE \'\'
                END) AS send_document_biology,
                (CASE 
                    WHEN (NDV.send_document_bakterlogy_id != 0 AND NDV.send_document_bakterlogy_close_type_id != 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/baktery.svg" style="width:16px;" alt="Бактериологи">\'
                    WHEN (NDV.send_document_bakterlogy_id != 0 AND NDV.send_document_bakterlogy_close_type_id = 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/baktery-grey.svg" style="width:16px;" alt="Бактериологи">\'
                    ELSE \'\'
                END) AS send_document_bakterlogy,
                NDV.send_document_chemical_id,
                NDV.send_document_chemical_close_type_id,
                NDV.send_document_biology_id,
                NDV.send_document_biology_close_type_id,
                NDV.send_document_bakterlogy_id,
                NDV.send_document_bakterlogy_close_type_id,
                NDV.param
            FROM `gaz_nifs_doctor_view` AS NDV
            WHERE 1 = 1 ' . $queryString . '
            ORDER BY NDV.create_number DESC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $param = json_decode($row->param);

                array_push($data, array(
                    'id' => $row->id,
                    'mod_id' => $row->mod_id,
                    'create_number' => $row->create_number,
                    'in_out_date' => $row->in_out_date,
                    'full_name' => $row->full_name . '<br>' . $row->register . $row->age . $row->sex,
                    'is_work' => $param->work,
                    'partner' => $param->partner . ' ' . $row->expert_name,
                    'short_value' => $param->shortValue . ' ' . $row->short_value,
                    'expert_status' => $row->expert_status,
                    'expert' => $param->expert,
                    'is_where' => $param->where,
                    'category' => $param->category,
                    'close_type' => ($param->closeType != '' ? $row->close_date . '<br>' . $param->closeType : ''),
                    'description' => $row->payment,
                    'row_status' => $row->row_status,
                    'send_document_chemical_id' => $row->send_document_chemical_id,
                    'send_document_chemical_close_type_id' => $row->send_document_chemical_close_type_id,
                    'send_document_chemical' => $row->send_document_chemical,
                    'send_document_biology_id' => $row->send_document_biology_id,
                    'send_document_biology_close_type_id' => $row->send_document_biology_close_type_id,
                    'send_document_biology' => $row->send_document_biology,
                    'send_document_bakterlogy_id' => $row->send_document_bakterlogy_id,
                    'send_document_bakterlogy_close_type_id' => $row->send_document_bakterlogy_close_type_id,
                    'send_document_bakterlogy' => $row->send_document_bakterlogy
                ));
            }
        }
        return array('data' => $data, 'search' => self::searchKeywordView_model());
    }

    public function insert_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $this->input->post('motiveId')));
        $this->nifsWorkData = $this->nifsWork->getData_model(array('selectedId' => $this->input->post('workId')));
        $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->post('partnerId')));
        $this->nifsCrimeShortValueData = $this->nifsCrimeShortValue->getData_model(array('selectedId' => $this->input->post('shortValueId')));
        $this->nifsExpertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->post('expertId')));
        $this->nifsWhereData = $this->nifsWhere->getData_model(array('selectedId' => $this->input->post('whereId')));
        $this->categoryData = $this->category->getData_model(array('selectedId' => $this->input->post('catId')));

        $this->data = array(
            array(
                'id' => getUID('nifs_doctor_view'),
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'create_number' => getCreateNumber(array('createNumber' => $this->input->post('createNumber'), 'table' => 'nifs_doctor_view', 'departmentId' => $this->nifsDepartmentId)),
                'in_date' => $this->input->post('inDate') . ' ' . $this->input->post('inTime') . ':00',
                'out_date' => $this->input->post('outDate') . ' 00:00:00',
                'fname' => $this->input->post('fname'),
                'lname' => $this->input->post('lname'),
                'register' => mb_strtoupper($this->input->post('register'), 'UTF-8'),
                'age' => ($this->input->post('age') != null ? $this->input->post('age') : 0),
                'is_age_infinitive' => ($this->input->post('isAgeInfinitive') != null ? $this->input->post('isAgeInfinitive') : 0),
                'sex' => $this->input->post('sex'),
                'phone' => $this->input->post('phone'),
                'description' => $this->input->post('description'),
                'short_value_id' => $this->input->post('shortValueId'),
                'short_value' => $this->input->post('shortValue'),
                'is_crime_ship' => $this->input->post('isCrimeShip'),
                'work_id' => $this->input->post('workId'),
                'partner_id' => $this->input->post('partnerId'),
                'expert_name' => $this->input->post('expertName'),
                'crime_date' => $this->input->post('crimeDate'),
                'expert_id' => $this->input->post('expertId'),
                'where_id' => $this->input->post('whereId'),
                'motive_id' => $this->input->post('motiveId'),
                'payment' => $this->input->post('payment'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'order_num' => getOrderNum(array('table' => 'nifs_doctor_view', 'field' => 'order_num')),
                'year' => $this->session->adminCloseYear,
                'is_active' => 1,
                'department_id' => $this->nifsDepartmentId,
                'param' => json_encode(array(
                    'motive' => ($this->nifsMotiveData != false ? $this->nifsMotiveData->title : ''),
                    'work' => ($this->nifsWorkData != false ? $this->nifsWorkData->title : ''),
                    'partner' => ($this->partnerData != false ? $this->partnerData->title : ''),
                    'shortValue' => ($this->nifsCrimeShortValueData != false ? $this->nifsCrimeShortValueData->title : ''),
                    'expert' => ($this->nifsExpertData != false ? $this->nifsExpertData->full_name : ''),
                    'where' => ($this->nifsWhereData != false ? $this->nifsWhereData->title : ''),
                    'category' => ($this->categoryData != false ? $this->categoryData->title : ''),
                    'closeType' => '')),
                'protocol_number' => $this->input->post('protocolNumber'),
                'protocol_in_date' => $this->input->post('protocolInDate'),
                'protocol_out_date' => $this->input->post('protocolOutDate'),
                'is_crime_ship' => $this->input->post('isCrimeShip'),
                'payment_description' => $this->input->post('paymentDescription')
            )
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'nifs_doctor_view', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'type' => LOG_TYPE_UPDATE,
            'data' => json_encode($_POST)));

        $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $this->input->post('motiveId')));
        $this->nifsWorkData = $this->nifsWork->getData_model(array('selectedId' => $this->input->post('workId')));
        $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->post('partnerId')));
        $this->nifsCrimeShortValueData = $this->nifsCrimeShortValue->getData_model(array('selectedId' => $this->input->post('shortValueId')));
        $this->nifsExpertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->post('expertId')));
        $this->nifsWhereData = $this->nifsWhere->getData_model(array('selectedId' => $this->input->post('whereId')));
        $this->categoryData = $this->category->getData_model(array('selectedId' => $this->input->post('catId')));

        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'create_number' => $this->input->post('createNumber'),
            'in_date' => $this->input->post('inDate') . ' ' . $this->input->post('inTime') . ':00',
            'out_date' => $this->input->post('outDate') . ' 00:00:00',
            'fname' => $this->input->post('fname'),
            'lname' => $this->input->post('lname'),
            'register' => mb_strtoupper($this->input->post('register'), 'UTF-8'),
            'age' => ($this->input->post('age') != null ? $this->input->post('age') : 0),
            'is_age_infinitive' => ($this->input->post('isAgeInfinitive') != null ? $this->input->post('isAgeInfinitive') : 0),
            'sex' => $this->input->post('sex'),
            'phone' => $this->input->post('phone'),
            'description' => $this->input->post('description'),
            'short_value_id' => $this->input->post('shortValueId'),
            'short_value' => $this->input->post('shortValue'),
            'work_id' => $this->input->post('workId'),
            'partner_id' => $this->input->post('partnerId'),
            'expert_name' => $this->input->post('expertName'),
            'crime_date' => $this->input->post('crimeDate'),
            'expert_id' => $this->input->post('expertId'),
            'where_id' => $this->input->post('whereId'),
            'motive_id' => $this->input->post('motiveId'),
            'payment' => $this->input->post('payment'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'order_num' => $this->input->post('orderNum'),
            'year' => $this->session->adminCloseYear,
            'is_active' => 1,
            'param' => json_encode(array(
                'motive' => ($this->nifsMotiveData != false ? $this->nifsMotiveData->title : ''),
                'work' => ($this->nifsWorkData != false ? $this->nifsWorkData->title : ''),
                'partner' => ($this->partnerData != false ? $this->partnerData->title : ''),
                'shortValue' => ($this->nifsCrimeShortValueData != false ? $this->nifsCrimeShortValueData->title : ''),
                'expert' => ($this->nifsExpertData != false ? $this->nifsExpertData->full_name : ''),
                'where' => ($this->nifsWhereData != false ? $this->nifsWhereData->title : ''),
                'category' => ($this->categoryData != false ? $this->categoryData->title : ''),
                'closeType' => '')),
            'protocol_number' => $this->input->post('protocolNumber'),
            'protocol_in_date' => $this->input->post('protocolInDate'),
            'protocol_out_date' => $this->input->post('protocolOutDate'),
            'is_crime_ship' => $this->input->post('isCrimeShip'),
            'payment_description' => $this->input->post('paymentDescription'),
            'year' => $this->session->adminCloseYear
        );

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'nifs_doctor_view', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {

        $row = $this->getData_model(array('selectedId' => $this->input->post('id')));
        $this->slog->log_model(array(
            'modId' => $row->mod_id,
            'type' => LOG_TYPE_DELETE,
            'data' => json_encode($row)));

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->delete($this->db->dbprefix . 'nifs_doctor_view')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }

        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function close_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'type' => LOG_TYPE_CLOSE,
            'data' => json_encode($_POST)));

        $this->nifsCloseTypeData = $this->nifsCloseType->getData_model(array('selectedId' => $this->input->post('closeTypeId')));

        $extraData = $this->getData_model(array('selectedId' => $this->input->post('id')));
        $param = json_decode($extraData->param);

        $param->closeType = ($this->nifsCloseTypeData != false ? $this->nifsCloseTypeData->title : '');

        $this->data = array(
            'close_date' => $this->input->post('closeDate'),
            'close_type_id' => $this->input->post('closeTypeId'),
            'is_sperm' => $this->input->post('isSperm'),
            'is_bzhu' => $this->input->post('isBzhu'),
            'is_skin' => $this->input->post('isSkin'),
            'injury_id' => $this->input->post('injuryId'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'param' => json_encode($param));
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'nifs_doctor_view', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function searchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('createNumber')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('createNumber') . '</span>';
            $this->string .= form_hidden('createNumber', $this->input->get('createNumber'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->string .= form_hidden('keyword', $this->input->get('keyword'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('workId')) {
            if ($this->input->get('workId') != 'all') {
                $this->nifsWorkData = $this->nifsWork->getData_model(array('selectedId' => $this->input->get('workId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsWorkData->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүх шинжилгээ</span>';
            }

            $this->string .= form_hidden('workId', $this->input->get('workId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('motiveId')) {

            if ($this->input->get('motiveId') != 'all') {
                $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $this->input->get('motiveId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsMotiveData->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүх шинжилгээ</span>';
            }

            $this->string .= form_hidden('motiveId', $this->input->get('motiveId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('partnerId')) {
            if ($this->input->get('partnerId') != 'all') {
                $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->get('partnerId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->partnerData->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('partnerId', $this->input->get('partnerId'));
            $this->showResetBtn = TRUE;
        }

        $this->string .= form_hidden('inDate', $this->input->get('inDate'));
        $this->string .= form_hidden('outDate', $this->input->get('outDate'));
        if ($this->input->get('inDate') and $this->input->get('outDate')) {
            $this->string .= '<span class="label label-default label-rounded">Бүртгэсэн огноо: ' . date('Y.m.d', strtotime($this->input->get('inDate'))) . '-' . date('Y.m.d', strtotime($this->input->get('outDate'))) . '</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('inDate')) {
            $this->string .= '<span class="label label-default label-rounded">Бүртгэсэн огноо: ' . date('Y.m.d', strtotime($this->input->get('inDate'))) . ' хойш</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('outDate')) {
            $this->string .= '<span class="label label-default label-rounded">Бүртгэсэн огноо: ' . date('Y.m.d', strtotime($this->input->get('outDate'))) . ' өмнөх</span>';
            $this->showResetBtn = TRUE;
        }

        $this->string .= form_hidden('crimeInDate', $this->input->get('crimeInDate'));
        $this->string .= form_hidden('crimeOutDate', $this->input->get('crimeOutDate'));
        if ($this->input->get('crimeInDate') and $this->input->get('crimeOutDate')) {
            $this->string .= '<span class="label label-default label-rounded">Хэрэг болсон огноо: ' . date('Y.m.d', strtotime($this->input->get('crimeInDate'))) . '-' . date('Y.m.d', strtotime($this->input->get('crimeOutDate'))) . '</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('crimeInDate')) {
            $this->string .= '<span class="label label-default label-rounded">Хэрэг болсон огноо: ' . date('Y.m.d', strtotime($this->input->get('crimeInDate'))) . ' хойш</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('crimeOutDate')) {
            $this->string .= '<span class="label label-default label-rounded">Хэрэг болсон огноо: ' . date('Y.m.d', strtotime($this->input->get('crimeOutDate'))) . ' өмнөх</span>';
            $this->showResetBtn = TRUE;
        }

        $this->string .= form_hidden('age1', $this->input->get('age1'));
        $this->string .= form_hidden('age2', $this->input->get('age2'));
        if ($this->input->get('age1') and $this->input->get('age2')) {
            $this->string .= '<span class="label label-default label-rounded">Нас: ' . $this->input->get('age1') . '-' . $this->input->get('age2') . '-ны хооронд</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('age1')) {
            $this->string .= '<span class="label label-default label-rounded">Нас: ' . $this->input->get('age1') . '-с их</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('age2')) {
            $this->string .= '<span class="label label-default label-rounded">Нас: ' . $this->input->get('age2') . '-с бага</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('sex') == 1) {

            $this->string .= '<span class="label label-default label-rounded">Эр</span>';
            $this->string .= form_hidden('catId', $this->input->get('sex'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('sex') == 2) {

            $this->string .= '<span class="label label-default label-rounded">Эм</span>';
            $this->string .= form_hidden('catId', $this->input->get('sex'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('isAgeInfinitive')) {
            $this->string .= '<span class="label label-default label-rounded">Нас тодорхойлох боломжгүй</span>';
            $this->string .= form_hidden('isAgeInfinitive', $this->input->get('isAgeInfinitive'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('shortValueId')) {
            if ($this->input->get('shortValueId') != 'all') {
                $this->nifsCrimeShortValueData = $this->nifsCrimeShortValue->getData_model(array('selectedId' => $this->input->get('shortValueId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCrimeShortValueData->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүх шинжилгээ</span>';
            }

            $this->string .= form_hidden('shortValueId', $this->input->get('shortValueId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('expertId')) {
            if ($this->input->get('expertId') != 'all') {
                $this->expertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->get('expertId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->expertData->full_name . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүх шинжилгээ</span>';
            }

            $this->string .= form_hidden('expertId', $this->input->get('expertId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('whereId')) {
            if ($this->input->get('whereId') != 'all') {
                $this->nifsWhereData = $this->nifsWhere->getData_model(array('selectedId' => $this->input->get('whereId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsWhereData->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('whereId', $this->input->get('whereId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('catId')) {
            if ($this->input->get('catId') != 'all') {
                $this->cat = $this->category->getData_model(array('selectedId' => $this->input->get('catId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->cat->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүх шинжилгээ</span>';
            }

            $this->string .= form_hidden('catId', $this->input->get('catId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('payment')) {

            $this->string .= form_hidden('payment', $this->input->get('payment'));

            if ($this->input->get('payment') == 1) {
                $this->string .= '<span class="label label-default label-rounded">Төлбөр төлсөн</span>';
                $this->showResetBtn = TRUE;
            }

            if ($this->input->get('payment') == 2) {
                $this->string .= '<span class="label label-default label-rounded">Төлбөр төлөөгүй</span>';
                $this->showResetBtn = TRUE;
            }

            if ($this->input->get('payment') == 3) {
                $this->string .= '<span class="label label-default label-rounded">Төлбөр чөлөөлсөн</span>';
                $this->showResetBtn = TRUE;
            }
        }

        if ($this->input->get('closeTypeId')) {
            if ($this->input->get('closeTypeId') != 'all') {
                $this->nifsCloseType = $this->nifsCloseType->getData_model(array('selectedId' => $this->input->get('closeTypeId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCloseType->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('closeTypeId', $this->input->get('closeTypeId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('isCrimeShip')) {

            $this->string .= form_hidden('isCrimeShip', $this->input->get('isCrimeShip'));

            if ($this->input->get('isCrimeShip') == 1) {
                $this->string .= '<span class="label label-default label-rounded">Хохирогч</span>';
            }

            if ($this->input->get('isCrimeShip') == 2) {
                $this->string .= '<span class="label label-default label-rounded">Холбогдогч</span>';
            }

            $this->showResetBtn = TRUE;
        }

        $this->string .= form_hidden('protocolInDate', $this->input->get('protocolInDate'));
        $this->string .= form_hidden('protocolOutDate', $this->input->get('protocolOutDate'));
        if ($this->input->get('protocolInDate') and $this->input->get('protocolOutDate')) {
            $this->string .= '<span class="label label-default label-rounded">Тогтоолын огноо: ' . date('Y.m.d', strtotime($this->input->get('protocolInDate'))) . '-' . date('Y.m.d', strtotime($this->input->get('protocolOutDate'))) . '</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('protocolInDate')) {
            $this->string .= '<span class="label label-default label-rounded">Тогтоолын огноо: ' . date('Y.m.d', strtotime($this->input->get('protocolInDate'))) . ' хойш</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('protocolOutDate')) {
            $this->string .= '<span class="label label-default label-rounded">Тогтоолын огноо: ' . date('Y.m.d', strtotime($this->input->get('protocolOutDate'))) . ' өмнөх</span>';
            $this->showResetBtn = TRUE;
        }

        $this->string .= form_hidden('closeInDate', $this->input->get('closeInDate'));
        $this->string .= form_hidden('closeOutDate', $this->input->get('closeOutDate'));
        if ($this->input->get('closeInDate') and $this->input->get('closeOutDate')) {

            $this->string .= '<span class="label label-default label-rounded">Хаасан огноо: ' . date('Y.m.d', strtotime($this->input->get('closeInDate'))) . '-' . date('Y.m.d', strtotime($this->input->get('closeOutDate'))) . '</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('closeInDate')) {

            $this->string .= '<span class="label label-default label-rounded">Хаасан огноо: ' . date('Y.m.d', strtotime($this->input->get('closeInDate'))) . ' хойш</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('closeOutDate')) {

            $this->string .= '<span class="label label-default label-rounded">Хаасан огноо: ' . date('Y.m.d', strtotime($this->input->get('closeOutDate'))) . ' өмнөх</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('closeDescription')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('closeDescription') . '</span>';
            $this->string .= form_hidden('closeDescription', $this->input->get('closeDescription'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('statusId')) {
            $this->statusData = $this->nifsStatus->getData_model(array('selectedId' => $this->input->get('statusId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->statusData->title . '</span>';
            $this->string .= form_hidden('statusId', $this->input->get('statusId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('isSperm')) {

            $this->string .= form_hidden('isSperm', $this->input->get('isSperm'));

            if ($this->input->get('isSperm') == 1) {
                $this->string .= '<span class="label label-default label-rounded">Эр бэлгийн эс илэрсэн</span>';
                $this->showResetBtn = TRUE;
            }

            if ($this->input->get('isSperm') == 2) {
                $this->string .= '<span class="label label-default label-rounded">Эр бэлгийн эс илрээгүй</span>';
                $this->showResetBtn = TRUE;
            }
        }

        if ($this->input->get('departmentId')) {

            if ($this->input->get('departmentId') != 'all') {
                $this->hrPeopleDepartmentData = $this->hrPeopleDepartment->getData_model(array('selectedId' => $this->input->get('departmentId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->hrPeopleDepartmentData->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('departmentId', $this->input->get('departmentId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            //' . Scrime::$path . $param['path'] . '/' . $param['modId'] . '

            if ($this->input->get('isReportMenu') == 1) {

                $this->string .= ' <a href="javascript:;" onclick="_reportItem({reportMenuId: ' . $this->input->get('reportMenuId') . ', reportModId: ' . $this->input->get('reportModId') . '});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            } else {

                $this->string .= ' <a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            }
        }

        return ($this->showResetBtn ? '<div class="_search-result-inner"><form action="javascript:;" id="form-nifs-doctor-view"> Хайлтын үр дүн: ' . $this->string . '</form></div>' : '');
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cat_id,
                create_number,
                in_date,
                out_date,
                fname,
                lname,
                register,
                age,
                sex,
                phone,
                description,
                short_value_id,
                short_value,
                work_id,
                motive_id,
                where_id,
                partner_id,
                expert_name,
                crime_date,
                expert_id,
                where_id,
                payment,
                close_description,
                close_date,
                close_type_id,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id,
                order_num,
                year,
                is_active,
                is_age_infinitive,
                \'\' AS weight,
                \'\' AS solution_id,
                protocol_number,
                DATE(protocol_in_date) AS protocol_in_date,
                DATE(protocol_out_date) AS protocol_out_date,
                is_sperm,
                is_crime_ship,
                is_bzhu,
                is_skin,
                injury_id,
                param
            FROM `gaz_nifs_doctor_view`
            WHERE id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

    public function controlNifsIsSpermDropdown_model($param = array('selectedId' => 0)) {

        $this->html = $this->string = '';

        $this->data = array(
            array('id' => 1, 'title' => 'Илэрсэн'),
            array('id' => 2, 'title' => 'Илэрээгүй')
        );

        $this->html .= '<select name="isSperm" id="isSperm" class="select2">';
        $this->html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Бүгд - </option>';

        foreach ($this->data as $key => $row) {
            $this->html .= '<option value="' . $row['id'] . '" ' . ($param['selectedId'] == $row['id'] ? 'selected="selected"' : '') . '>' . $row['title'] . '</option>';
        }

        $this->html .= '</select>';

        return $this->html;
    }

    public function getReportWorkInformationData_model($param = array()) {

        $htmlData = $queryStringData = $queryStringYearData = $queryStringInDateData = $queryStringObjectData = $queryStringMotiveData = $isAjaxDepartmentUrl = '';

        if ($param['departmentId'] > 0) {

            $queryStringData .= ' AND NDV.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        if ($param['reportIsClose'] == 1) {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NDV.close_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NDV.close_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NDV.close_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NDV.close_date)';
            }
        } else {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NDV.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NDV.in_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NDV.in_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NDV.out_date)';
            }
        }


        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NDV.year = \'' . $this->session->adminCloseYear . '\'';
        }

        /* Болсон хэргийн утгаар шүүж харуулах - эхлэл */
        $query = $this->db->query('
            SELECT
                CSV.id, 
                CSV.title,
                NDV.date_all_count,
                IF(NDV.date_all_count > 0, \'_row-more\', \'\') AS date_all_count_class,
                NDVNOTVIEW.date_not_view_count,
                IF(NDVNOTVIEW.date_not_view_count > 0, \'_row-more\', \'\') AS date_not_view_count_class,
                NDVDIE.date_die_count,
                IF(NDVDIE.date_die_count > 0, \'_row-more\', \'\') AS date_die_count_class,
                NDVRETURN.date_return_count,
                IF(NDVRETURN.date_return_count > 0, \'_row-more\', \'\') AS date_return_count_class,
                AGE_0_1.age_0_1_count,
                IF(AGE_0_1.age_0_1_count > 0, \'_row-more\', \'\') AS age_0_1_count_class,
                AGE_1_17.age_1_17_count,
                IF(AGE_1_17.age_1_17_count > 0, \'_row-more\', \'\') AS age_1_17_count_class,
                AGE_18.age_18_count,
                IF(AGE_18.age_18_count > 0, \'_row-more\', \'\') AS age_18_count_class,
                AGE_INFINITIVE.age_infinitive_count,
                IF(AGE_INFINITIVE.age_infinitive_count > 0, \'_row-more\', \'\') AS age_infinitive_count_class,
                SEX1.sex1_count,
                IF(SEX1.sex1_count > 0, \'_row-more\', \'\') AS sex1_count_class,
                SEX0.sex0_count,
                IF(SEX0.sex0_count > 0, \'_row-more\', \'\') AS sex0_count_class
            FROM gaz_nifs_crime_short_value AS CSV
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS date_all_count
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS NDV ON NDV.short_value_id = CSV.id
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS date_not_view_count
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.close_type_id = 17 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS NDVNOTVIEW ON NDVNOTVIEW.short_value_id = CSV.id
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS date_die_count
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.close_type_id = 14 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS NDVDIE ON NDVDIE.short_value_id = CSV.id
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS date_return_count
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.close_type_id = 18 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS NDVRETURN ON NDVRETURN.short_value_id = CSV.id
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_0_1_count
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE 0 <= NDV.age AND NDV.age <= 1 AND NDV.is_age_infinitive = 0' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS AGE_0_1 ON AGE_0_1.short_value_id = CSV.id
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_1_17_count
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE 1 < NDV.age AND NDV.age <= 17 AND NDV.is_age_infinitive = 0' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS AGE_1_17 ON AGE_1_17.short_value_id = CSV.id
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_18_count
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE 18 <= NDV.age AND NDV.is_age_infinitive = 0' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS AGE_18 ON AGE_18.short_value_id = CSV.id
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_infinitive_count
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.is_age_infinitive = 1' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS AGE_INFINITIVE ON AGE_INFINITIVE.short_value_id = CSV.id
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS sex1_count
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.sex = 1' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SEX1 ON SEX1.short_value_id = CSV.id
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS sex0_count
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.sex = 0' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SEX0 ON SEX0.short_value_id = CSV.id
            WHERE CSV.is_active = 1
            ORDER BY CSV.title ASC');

        if ($query->num_rows() > 0) {

            $i = $sumTotalDateAllCount = $sumTotalDateNotViewCount = $sumTotalDateDieCount = $sumTotalDateReturnCount = $sumTotalAge01 = $sumTotalAge117 = $sumTotalAge18 = $sumTotalAgeInfinitive = $sumTotalSex1 = $sumTotalSex0 = 0;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Шинжилгээ</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Бүгд</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Үзүүлээгүй</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Нас барсан</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Албан тоотоор буцсан</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">0-1 нас хүртэл</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">1-17 нас</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">18-с дээш</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Нас тодорхойгүй</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Эр</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Эм</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $sumTotalDateAllCount = $sumTotalDateAllCount + $row->date_all_count;
                $sumTotalDateNotViewCount = $sumTotalDateNotViewCount + $row->date_not_view_count;
                $sumTotalDateDieCount = $sumTotalDateDieCount + $row->date_die_count;
                $sumTotalDateReturnCount = $sumTotalDateReturnCount + $row->date_return_count;
                $sumTotalAge01 = $sumTotalAge01 + $row->age_0_1_count;
                $sumTotalAge117 = $sumTotalAge117 + $row->age_1_17_count;
                $sumTotalAge18 = $sumTotalAge18 + $row->age_18_count;
                $sumTotalAgeInfinitive = $sumTotalAgeInfinitive + $row->age_infinitive_count;
                $sumTotalSex1 = $sumTotalSex1 + $row->sex1_count;
                $sumTotalSex0 = $sumTotalSex0 + $row->sex0_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->date_all_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->date_all_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->date_not_view_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&closeTypeId=17&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->date_not_view_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->date_die_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&closeTypeId=14&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->date_die_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->date_return_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&closeTypeId=18&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->date_return_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_0_1_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&age1=0&age2=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_0_1_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_1_17_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&age1=1&age2=17&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_1_17_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_18_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&age1=18&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_18_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_infinitive_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&isAgeInfinitive=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_infinitive_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->sex1_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&sex=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->sex1_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->sex0_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&sex=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->sex0_count . '</a></td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalDateAllCount > 0 ? $sumTotalDateAllCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&closeTypeId=17&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalDateNotViewCount > 0 ? $sumTotalDateNotViewCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&closeTypeId=14&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalDateDieCount > 0 ? $sumTotalDateDieCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&closeTypeId=18&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalDateReturnCount > 0 ? $sumTotalDateReturnCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&age1=0&age2=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge01 > 0 ? $sumTotalAge01 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&age1=1&age2=17&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge117 > 0 ? $sumTotalAge117 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&age1=18&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge18 > 0 ? $sumTotalAge18 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&isAgeInfinitive=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAgeInfinitive > 0 ? $sumTotalAgeInfinitive : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&sex=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalSex1 > 0 ? $sumTotalSex1 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&sex=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalSex0 > 0 ? $sumTotalSex0 : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';


            $queryNot = $this->db->query('
                SELECT 
                    NDV.create_number
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.short_value_id = 0 ' . $queryStringData);

            if ($queryNot->num_rows() > 0) {

                if ($queryNot->num_rows() > 0) {

                    $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ (' . $queryNot->num_rows() . '): ';

                    foreach ($queryNot->result() as $rowNot) {
                        $htmlData .= '<a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'createNumber=' . $rowNot->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNot->create_number . '</a>';
                    }

                    $htmlData .= '</div>';
                } else {
                    $htmlData .= '<br>';
                }
            }
        }
        /* Болсон хэргийн утгаар шүүж харуулах - төгсөгл */

        $query = $this->db->query('
            SELECT
                NF.id, 
                NF.title,
                NDV.motive_count,
                IF(NDV.motive_count > 0, \'_row-more\', \'\') AS motive_count_class
            FROM gaz_nifs_motive AS NF
            LEFT JOIN (
                SELECT 
                    NDV.motive_id,
                    COUNT(NDV.motive_id) AS motive_count
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NDV.motive_id
            ) AS NDV ON NDV.motive_id = NF.id
            WHERE NF.cat_id = 391
            ORDER BY NF.title ASC');

        if ($query->num_rows() > 0) {

            $i = $sumTotalMotiveCount = 0;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Шинжилгээ</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Нийт</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $sumTotalMotiveCount = $sumTotalMotiveCount + $row->motive_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->motive_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'motiveId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->motive_count . '</a></td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт</td>';
            $htmlData .= '<td class="text-center  _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'motiveId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalMotiveCount > 0 ? $sumTotalMotiveCount : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';


            $queryNot = $this->db->query('
                SELECT 
                    NDV.create_number
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.motive_id = 0 ' . $queryStringData);

            if ($queryNot->num_rows() > 0) {
                if ($queryNot->num_rows() > 0) {
                    $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ (' . $queryNot->num_rows() . '): ';

                    foreach ($queryNot->result() as $rowNot) {
                        $htmlData .= '<a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'createNumber=' . $rowNot->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNot->create_number . '</a>';
                    }
                    $htmlData .= '</div>';
                } else {
                    $htmlData .= '<br>';
                }
            }
        }


        $query = $this->db->query('
            SELECT
                NF.id, 
                NF.title,
                NDV.work_count,
                IF(NDV.work_count > 0, \'_row-more\', \'\') AS work_count_class
            FROM gaz_nifs_work AS NF
            LEFT JOIN (
                SELECT 
                    NDV.work_id,
                    COUNT(NDV.work_id) AS work_count
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NDV.work_id
            ) AS NDV ON NDV.work_id = NF.id
            WHERE NF.is_active = 1
            ORDER BY NF.title ASC');

        if ($query->num_rows() > 0) {

            $i = $sumTotalWorkCount = 0;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Шинжилгээ</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Нийт</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $sumTotalWorkCount = $sumTotalWorkCount + $row->work_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->work_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'workId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->work_count . '</a></td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт</td>';
            $htmlData .= '<td class="text-center  _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'workId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalWorkCount > 0 ? $sumTotalWorkCount : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';


            $queryNot = $this->db->query('
                SELECT 
                    NDV.create_number
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.work_id = 0 ' . $queryStringData);

            if ($queryNot->num_rows() > 0) {

                if ($queryNot->num_rows() > 0) {

                    $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ (' . $queryNot->num_rows() . '): ';

                    foreach ($queryNot->result() as $rowNot) {
                        $htmlData .= '<a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'createNumber=' . $rowNot->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNot->create_number . '</a>';
                    }

                    $htmlData .= '</div>';
                } else {
                    $htmlData .= '<br>';
                }
            }
        }

        $query = $this->db->query('
            SELECT
                NCT.id, 
                NCT.title,
                NDV.close_type_count,
                IF(NDV.close_type_count > 0, \'_row-more\', \'\') AS close_type_count_class
            FROM gaz_nifs_close_type AS NCT
            LEFT JOIN (
                SELECT 
                    NDV.close_type_id,
                    COUNT(NDV.close_type_id) AS close_type_count
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NDV.close_type_id
            ) AS NDV ON NDV.close_type_id = NCT.id
            WHERE NCT.is_active = 1
            ORDER BY NCT.title ASC');

        if ($query->num_rows() > 0) {

            $i = $sumTotalCloseTypeCount = 0;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Шинжилгээ</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Нийт</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $sumTotalCloseTypeCount = $sumTotalCloseTypeCount + $row->close_type_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->close_type_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'closeTypeId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->close_type_count . '</a></td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'closeTypeId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalCloseTypeCount > 0 ? $sumTotalCloseTypeCount : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';


            $queryNot = $this->db->query('
                SELECT 
                    NDV.create_number
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.close_type_id = 0 ' . $queryStringData);

            if ($queryNot->num_rows() > 0) {

                if ($queryNot->num_rows() > 0) {

                    $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ (' . $queryNot->num_rows() . '): ';

                    foreach ($queryNot->result() as $rowNot) {
                        $htmlData .= '<a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'createNumber=' . $rowNot->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNot->create_number . '</a>';
                    }

                    $htmlData .= '</div>';
                } else {
                    $htmlData .= '<br>';
                }
            }
        }

        /*         * * */
        $query = $this->db->query('
            SELECT
                NW.id, 
                NW.title,
                NDV.where_count,
                IF(NDV.where_count > 0, \'_row-more\', \'\') AS where_count_class
            FROM gaz_nifs_where AS NW
            LEFT JOIN (
                SELECT 
                    NDV.where_id,
                    COUNT(NDV.where_id) AS where_count
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NDV.where_id
            ) AS NDV ON NDV.where_id = NW.id
            WHERE NW.is_active = 1 AND NW.cat_id = 378
            ORDER BY NW.title ASC');

        if ($query->num_rows() > 0) {

            $i = $sumTotalWhereCount = 0;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Шинжилгээ</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Нийт</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $sumTotalWhereCount = $sumTotalWhereCount + $row->where_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->where_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'whereId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->where_count . '</a></td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'whereId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalWhereCount > 0 ? $sumTotalWhereCount : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';


            $queryNot = $this->db->query('
                SELECT 
                    NDV.create_number
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.close_type_id = 0 ' . $queryStringData);

            if ($queryNot->num_rows() > 0) {

                if ($queryNot->num_rows() > 0) {

                    $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ (' . $queryNot->num_rows() . '): ';

                    foreach ($queryNot->result() as $rowNot) {
                        $htmlData .= '<a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'createNumber=' . $rowNot->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNot->create_number . '</a>';
                    }

                    $htmlData .= '</div>';
                } else {
                    $htmlData .= '<br>';
                }
            }
        }


        /*         * * */
        $query = $this->db->query('
            SELECT
                C.id, 
                C.title,
                NDV.cat_count,
                IF(NDV.cat_count > 0, \'_row-more\', \'\') AS cat_count_class
            FROM gaz_category AS C
            LEFT JOIN (
                SELECT 
                    NDV.cat_id,
                    COUNT(NDV.cat_id) AS cat_count
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NDV.cat_id
            ) AS NDV ON NDV.cat_id = C.id
            WHERE C.is_active = 1 AND C.mod_id = 51
            ORDER BY C.title ASC');

        if ($query->num_rows() > 0) {

            $i = $sumTotalCatCount = 0;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Шинжилгээ</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Нийт</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $sumTotalCatCount = $sumTotalCatCount + $row->cat_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->cat_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'catId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->cat_count . '</a></td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'catId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalCatCount > 0 ? $sumTotalCatCount : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';


            $queryNot = $this->db->query('
                SELECT 
                    NDV.create_number
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.cat_id = 0 ' . $queryStringData);

            if ($queryNot->num_rows() > 0) {

                if ($queryNot->num_rows() > 0) {

                    $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ (' . $queryNot->num_rows() . '): ';

                    foreach ($queryNot->result() as $rowNot) {
                        $htmlData .= '<a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'createNumber=' . $rowNot->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNot->create_number . '</a>';
                    }

                    $htmlData .= '</div>';
                } else {
                    $htmlData .= '<br>';
                }
            }
        }

        return $htmlData;
    }

    public function getReportWeightData_model($param = array()) {

        $queryStringData = $htmlData = $isAjaxDepartmentUrl = '';

        if ($param['reportIsClose'] == 1) {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NDV.close_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NDV.close_date)';
            } elseif ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NDV.close_date)';
            } elseif ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NDV.close_date)';
            }
        } else {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NDV.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NDV.in_date)';
            } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NDV.in_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NDV.in_date)';
            }
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {
            $queryStringData .= ' AND NDV.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $query = $this->db->query('
            SELECT 
                NE.expert_id
            FROM `gaz_nifs_expert` AS NE
            INNER JOIN `gaz_nifs_doctor_view` AS NDV ON NE.mod_id = NDV.mod_id AND NE.cont_id = NDV.id
            INNER JOIN `gaz_hr_people_work` AS HPW ON NE.expert_id = HPW.people_id AND HPW.is_currenty = 1
            WHERE 1 = 1 ' . $queryStringData . '
            GROUP BY NE.expert_id');

        $inPeopleId = NIFS_EXTRA_EXPERT;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $row) {
                $inPeopleId .= $row->expert_id . ', ';
            }
        }
        $inPeopleId = rtrim($inPeopleId, ', ');

        if ($param['departmentId'] > 0) {
            $queryStringData .= ' AND NDV.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        $query = $this->db->query('
            SELECT 
                HP.id,
                IF(HP.is_active = 1, CONCAT(\'<strong>\',SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname, \'</strong><br><div style="font-size:90%">\', HPP.title, \', \', HPD.title, \'</div>\'), CONCAT(\'<strong>\', HP.fname, \'</strong>\')) AS full_name,
                NDVC.doctor_view_count,
                IF(NDVC.doctor_view_count > 0, \'_row-more\', \'\') AS doctor_view_count_class,
                NEVAL.doctor_view_neval_count,
                IF(NEVAL.doctor_view_neval_count > 0, \'_row-more\', \'\') AS doctor_view_neval_count_class,
                DVDAY.doctor_view_day_count,
                IF(DVDAY.doctor_view_day_count > 0, \'_row-more\', \'\') AS doctor_view_day_count_class,
                DVOTHER.doctor_view_other_count,
                IF(DVOTHER.doctor_view_other_count > 0, \'_row-more\', \'\') AS doctor_view_other_count_class,
                NDV_NORMAL_CLOSE.normal_close_count,
                IF(NDV_NORMAL_CLOSE.normal_close_count > 0, \'_row-more\', \'\') AS normal_close_count_class,
                NDV_NORMAL_HAND.normal_hand_count,
                IF(NDV_NORMAL_HAND.normal_hand_count > 0, \'_row-more\', \'\') AS normal_hand_count_class,
                NDV_WARNING_CLOSE.warning_close_count,
                IF(NDV_WARNING_CLOSE.warning_close_count > 0, \'_row-more\', \'\') AS warning_close_count_class,
                NDV_WARNING_HAND.warning_hand_count,
                IF(NDV_WARNING_HAND.warning_hand_count > 0, \'_row-more\', \'\') AS warning_hand_count_class
            FROM `gaz_hr_people` AS HP
            INNER JOIN `gaz_hr_people_work` AS HPW ON HP.id = HPW.people_id AND HPW.is_currenty = 1
            INNER JOIN `gaz_hr_people_position` AS HPP ON HPP.id = HPW.position_id 
            INNER JOIN `gaz_hr_people_department` AS HPD ON HPD.id = HPW.department_id 
            LEFT JOIN (
                SELECT 
                    NDV.expert_id,
                    COUNT(NDV.expert_id) AS doctor_view_count 
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NDV.expert_id
            ) NDVC ON NDVC.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NDV.expert_id,
                    COUNT(NDV.expert_id) AS doctor_view_neval_count 
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.where_id = 1 ' . $queryStringData . '
                GROUP BY NDV.expert_id
            ) NEVAL ON NEVAL.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NDV.expert_id,
                    COUNT(NDV.expert_id) AS doctor_view_day_count 
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.where_id = 2 ' . $queryStringData . '
                GROUP BY NDV.expert_id
            ) DVDAY ON DVDAY.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NDV.expert_id,
                    COUNT(NDV.expert_id) AS doctor_view_other_count 
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.where_id = 3 ' . $queryStringData . '
                GROUP BY NDV.expert_id
            ) DVOTHER ON DVOTHER.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NDV.expert_id,
                    COUNT(NDV.expert_id) AS normal_close_count 
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.close_type_id != 0 AND DATE(NDV.close_date) <= DATE(NDV.out_date)' . $queryStringData . '
                GROUP BY NDV.expert_id
            ) NDV_NORMAL_CLOSE ON NDV_NORMAL_CLOSE.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NDV.expert_id,
                    COUNT(NDV.expert_id) AS normal_hand_count 
                FROM `gaz_nifs_doctor_view` AS NDV 
                WHERE NDV.expert_id != 0 AND CURDATE() <= DATE(NDV.out_date) AND NDV.close_type_id = 0' . $queryStringData . '
                GROUP BY NDV.expert_id
            ) NDV_NORMAL_HAND ON NDV_NORMAL_HAND.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NDV.expert_id,
                    COUNT(NDV.expert_id) AS warning_close_count 
                FROM `gaz_nifs_doctor_view` AS NDV 
                WHERE NDV.expert_id != 0 AND DATE(NDV.close_date) > DATE(NDV.out_date) AND NDV.close_type_id != 0' . $queryStringData . '
                GROUP BY NDV.expert_id
            ) NDV_WARNING_CLOSE ON NDV_WARNING_CLOSE.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NDV.expert_id,
                    COUNT(NDV.expert_id) AS warning_hand_count 
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.expert_id != 0 AND CURDATE() >= DATE(NDV.out_date) AND NDV.close_type_id = 0' . $queryStringData . '
                GROUP BY NDV.expert_id
            ) NDV_WARNING_HAND ON NDV_WARNING_HAND.expert_id = HP.id 
            WHERE NDVC.doctor_view_count > 0 AND NDVC.expert_id != 0
            ORDER BY HP.fname ASC');

        if ($query->num_rows() > 0) {

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="2" style="width:30px;">#</th>';
            $htmlData .= '<th rowspan="2">Шинжээч эмч нарын нэрс</th>';
            $htmlData .= '<th rowspan="2" style="width:60px;" class="text-center">Нийт шинжилгээ</th>';
            $htmlData .= '<th rowspan="2" style="width:60px;" class="text-center">Жижүүр</th>';
            $htmlData .= '<th rowspan="2" style="width:60px;" class="text-center">Өдөр</th>';
            $htmlData .= '<th rowspan="2" style="width:60px;" class="text-center">Гадуур</th>';
            $htmlData .= '<th colspan="2" style="width:100px;" class="text-center">Хэвийн шинжилгээ</th>';
            $htmlData .= '<th colspan="2" style="width:100px;" class="text-center">Хугацаа хэтэрсэн</th>';
            $htmlData .= '</tr>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:50px;" class="text-center">Хаагдсан</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">Хаагдаагүй</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">Хаагдсан</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">Хаагдаагүй</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            $i = 1;
            $doctor_view_count = $doctor_view_neval_count = $doctor_view_day_count = $doctor_view_other_count = $normal_close_count = $normal_hand_count = $warning_close_count = $warning_hand_count = 0;

            foreach ($query->result() as $key => $row) {

                $doctor_view_count = $doctor_view_count + $row->doctor_view_count;
                $doctor_view_neval_count = $doctor_view_neval_count + $row->doctor_view_neval_count;
                $doctor_view_day_count = $doctor_view_day_count + $row->doctor_view_day_count;
                $doctor_view_other_count = $doctor_view_other_count + $row->doctor_view_other_count;
                $normal_close_count = $normal_close_count + $row->normal_close_count;
                $normal_hand_count = $normal_hand_count + $row->normal_hand_count;
                $warning_close_count = $warning_close_count + $row->warning_close_count;
                $warning_hand_count = $warning_hand_count + $row->warning_hand_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i . '</td>';
                $htmlData .= '<td>' . $row->full_name . '</td>';
                $htmlData .= '<td class="text-center ' . $row->doctor_view_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->doctor_view_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->doctor_view_neval_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'expertId=' . $row->id . '&whereId=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->doctor_view_neval_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->doctor_view_day_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'expertId=' . $row->id . '&whereId=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->doctor_view_day_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->doctor_view_other_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'expertId=' . $row->id . '&whereId=3&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->doctor_view_other_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->normal_close_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->normal_close_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->normal_hand_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->normal_hand_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->warning_close_count_class . '"><a href="javascript:;" class="_crime-crash-done" onclick="_initNifsDoctorView({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=3&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->warning_close_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->warning_hand_count_class . '"><a href="javascript:;" class="_crime-crash-hand" onclick="_initNifsDoctorView({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=4&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->warning_hand_count . '</a></td>';
                $htmlData .= '</tr>';

                $i++;
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($doctor_view_count > 0 ? $doctor_view_count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'expertId=all&whereId=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($doctor_view_neval_count > 0 ? $doctor_view_neval_count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'expertId=all&whereId=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($doctor_view_day_count > 0 ? $doctor_view_day_count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'expertId=all&whereId=3&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($doctor_view_other_count > 0 ? $doctor_view_other_count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($normal_close_count > 0 ? $normal_close_count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($normal_hand_count > 0 ? $normal_hand_count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" class="_crime-crash-done" onclick="_initNifsDoctorView({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=3&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($warning_close_count > 0 ? $warning_close_count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" class="_crime-crash-hand" onclick="_initNifsDoctorView({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=4&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($warning_hand_count > 0 ? $warning_hand_count : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
        }

        return $htmlData;
    }

    public function getReportPartnerData_model($param = array()) {

        $queryStringData = $queryStringYearData = $htmlData = $isAjaxDepartmentUrl = '';

        if ($param['departmentId'] > 0) {
            $queryStringData .= ' AND NC.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $queryStringYearData .= ' AND NC.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        if ($param['reportIsClose'] == 1) {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NC.close_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NC.close_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NC.close_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NC.close_date)';
            }
        } else {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NC.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NC.in_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NC.in_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NC.out_date)';
            }
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NC.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $query = $this->db->query('
            SELECT 
                P.id,
                P.title,
                YEAR_PARTNER_COUNT.year_partner_count,
                IF(YEAR_PARTNER_COUNT.year_partner_count > 0, \'_row-more\', \'\') AS year_partner_count_class,
                NC_PARTNER_COUNT.date_partner_count,
                IF(NC_PARTNER_COUNT.date_partner_count > 0, \'_row-more\', \'\') AS date_partner_count_class
            FROM `gaz_partner` AS P
            LEFT JOIN (
                SELECT
                    NC.partner_id,
                    COUNT(NC.partner_id) AS year_partner_count
                FROM `gaz_nifs_doctor_view` AS NC
                WHERE NC.partner_id != 0 AND NC.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NC.partner_id
            ) AS YEAR_PARTNER_COUNT ON YEAR_PARTNER_COUNT.partner_id = P.id
            LEFT JOIN (
                SELECT
                    NC.partner_id,
                    COUNT(NC.partner_id) AS date_partner_count
                FROM `gaz_nifs_doctor_view` AS NC
                WHERE NC.partner_id != 0 ' . $queryStringData . '
                GROUP BY NC.partner_id
            ) AS NC_PARTNER_COUNT ON NC_PARTNER_COUNT.partner_id = P.id
            WHERE P.is_active = 1 AND P.parent_id = 0
            ORDER BY P.title ASC');

        if ($query->num_rows() > 0) {

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Гарчиг</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">' . $this->session->adminCloseYear . ' он</th>';
            $htmlData .= '<th style="width:200px;" class="text-center">' . $param['inDate'] . ' - ' . $param['outDate'] . '</th>';
            $htmlData .= '</tr>';
            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            $i = $sumTotalYearPartnerCount = $sumTotalDatePartnerCount = 0;

            foreach ($query->result() as $key => $row) {

                ++$i;
                $sumTotalYearPartnerCount = $sumTotalYearPartnerCount + intval($row->year_partner_count);
                $sumTotalDatePartnerCount = $sumTotalDatePartnerCount + intval($row->date_partner_count);

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->year_partner_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . $row->year_partner_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->date_partner_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->date_partner_count . '</a></td>';
                $htmlData .= '</tr>';

                $isChildData = self::getReportPartnerChildData_model(array(
                            'reportIsClose' => $param['reportIsClose'],
                            'parentId' => $row->id,
                            'departmentId' => $param['departmentId'],
                            'inDate' => $param['inDate'],
                            'outDate' => $param['outDate'],
                            'space' => 40,
                            'num' => $i,
                            'reportMenuId' => $param['reportMenuId'],
                            'reportModId' => $param['reportModId']));

                if ($isChildData) {
                    $sumTotalYearPartnerCount = $sumTotalYearPartnerCount + $isChildData['yearPartnerCount'];
                    $sumTotalDatePartnerCount = $sumTotalDatePartnerCount + $isChildData['datePartnerCount'];

                    $htmlData .= $isChildData['html'];
                }
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td>Нийт</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'partnerId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalYearPartnerCount > 0 ? $sumTotalYearPartnerCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'partnerId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalDatePartnerCount > 0 ? $sumTotalDatePartnerCount : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';


            $queryNotPartner = $this->db->query('
            SELECT 
                NC.create_number
            FROM `gaz_nifs_doctor_view` AS NC 
            WHERE NC.partner_id = 0 ' . $queryStringData . '
            ORDER BY NC.create_number ASC');

            if ($queryNotPartner->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ томилсон байгууллага сонгоогүй шинжилгээ (' . $queryNotPartner->num_rows() . '): ';

                foreach ($queryNotPartner->result() as $rowNotPartner) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'createNumber=' . $rowNotPartner->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNotPartner->create_number . '</a>';
                }
                $htmlData .= '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }

        return $htmlData;
    }

    public function getReportPartnerChildData_model($param = array()) {

        $queryStringData = $queryStringYearData = $htmlData = $isAjaxDepartmentUrl = '';
        $i = $sumTotalYearPartnerCount = $sumTotalDatePartnerCount = 0;

        if ($param['departmentId'] > 0) {
            $queryStringData .= ' AND NC.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $queryStringYearData .= ' AND NC.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        if ($param['reportIsClose'] == 1) {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NC.close_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NC.close_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NC.close_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NC.close_date)';
            }
        } else {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NC.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NC.in_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NC.in_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NC.out_date)';
            }
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NC.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $query = $this->db->query('
            SELECT 
                P.id,
                P.title,
                YEAR_PARTNER_COUNT.year_partner_count,
                IF(YEAR_PARTNER_COUNT.year_partner_count > 0, \'_row-more\', \'\') AS year_partner_count_class,
                NC_PARTNER_COUNT.date_partner_count,
                IF(NC_PARTNER_COUNT.date_partner_count > 0, \'_row-more\', \'\') AS date_partner_count_class
            FROM `gaz_partner` AS P
            LEFT JOIN (
                SELECT
                    NC.partner_id,
                    COUNT(NC.partner_id) AS year_partner_count
                FROM `gaz_nifs_doctor_view` AS NC
                WHERE NC.partner_id != 0 AND NC.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NC.partner_id
            ) AS YEAR_PARTNER_COUNT ON YEAR_PARTNER_COUNT.partner_id = P.id
            LEFT JOIN (
                SELECT
                    NC.partner_id,
                    COUNT(NC.partner_id) AS date_partner_count
                FROM `gaz_nifs_doctor_view` AS NC
                WHERE NC.partner_id != 0 ' . $queryStringData . '
                GROUP BY NC.partner_id
            ) AS NC_PARTNER_COUNT ON NC_PARTNER_COUNT.partner_id = P.id
            WHERE P.is_active = 1 AND P.parent_id = ' . $param['parentId'] . '
            ORDER BY P.title ASC');

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                ++$i;
                $sumTotalYearPartnerCount = $sumTotalYearPartnerCount + intval($row->year_partner_count);
                $sumTotalDatePartnerCount = $sumTotalDatePartnerCount + intval($row->date_partner_count);

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $param['num'] . '.' . $i . '</td>';
                $htmlData .= '<td style="padding-left:' . $param['space'] . 'px;">' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->year_partner_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . $row->year_partner_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->date_partner_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->date_partner_count . '</a></td>';
                $htmlData .= '</tr>';

                $isChildData = self::getReportPartnerChildData_model(array(
                            'reportIsClose' => $param['reportIsClose'],
                            'parentId' => $row->id,
                            'departmentId' => $param['departmentId'],
                            'inDate' => $param['inDate'],
                            'outDate' => $param['outDate'],
                            'space' => $param['space'] + 20,
                            'num' => $param['num'] . '.' . $i,
                            'reportMenuId' => $param['reportMenuId'],
                            'reportModId' => $param['reportModId']));

                if ($isChildData) {
                    $sumTotalYearPartnerCount = $sumTotalYearPartnerCount + $isChildData['yearPartnerCount'];
                    $sumTotalDatePartnerCount = $sumTotalDatePartnerCount + $isChildData['datePartnerCount'];
                    $htmlData .= $isChildData['html'];
                }
            }
        }

        return array('html' => $htmlData, 'yearPartnerCount' => $sumTotalYearPartnerCount, 'datePartnerCount' => $sumTotalDatePartnerCount);
    }

    public function export_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $queryString = '';

        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } elseif ($auth->our->read and ! $auth->your->read) {
            $queryString .= ' AND NDV.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NDV.created_user_id = -1';
        }

        if ($this->session->adminDepartmentRoleId == 2) {

            $queryString .= ' AND NDV.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        } else {

            if ($param['departmentId'] != 0) {

                $queryString .= ' AND NDV.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            } else {

                $queryString .= ' AND NDV.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
            }
        }

        if ($param['catId'] != 0) {

            $queryString .= ' AND NDV.cat_id = ' . $param['catId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(NDV.in_date) AND \'' . $param['outDate'] . '\' >= DATE(NDV.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(NDV.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND \'' . $param['outDate'] . '\' >= DATE(NDV.out_date)';
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND \'' . $param['protocolInDate'] . '\' <= DATE(NDV.protocol_in_date) AND \'' . $param['protocolOutDate'] . '\' >= DATE(NDV.protocol_in_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $queryString .= ' AND \'' . $param['protocolInDate'] . '\' <= DATE(NDV.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND \'' . $param['protocolOutDate'] . '\' >= DATE(NDV.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND \'' . $param['closeInDate'] . '\' <= DATE(NDV.close_date) AND \'' . $param['closeOutDate'] . '\' >= DATE(NDV.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND \'' . $param['closeInDate'] . '\' <= DATE(NDV.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND \'' . $param['closeOutDate'] . '\' >= DATE(NDV.close_date)';
        }

        if ($param['crimeInDate'] != '' and $param['crimeOutDate'] != '') {

            $queryString .= ' AND \'' . $param['crimeInDate'] . '\' <= DATE(NDV.crime_date) AND \'' . $param['crimeOutDate'] . '\' >= DATE(NDV.crime_date)';
        } else if ($param['crimeInDate'] != '' and $param['crimeOutDate'] == '') {

            $queryString .= ' AND \'' . $param['crimeInDate'] . '\' <= DATE(NDV.crime_date)';
        } else if ($param['crimeInDate'] == '' and $param['crimeOutDate'] != '') {

            $queryString .= ' AND \'' . $param['crimeOutDate'] . '\' >= DATE(NDV.crime_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '' and $param['crimeInDate'] == '' and $param['crimeOutDate'] == '') {

            $queryString .= ' AND NDV.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['isAgeInfinitive'] == 1) {
            $queryString .= ' AND NDV.is_age_infinitive = ' . $param['isAgeInfinitive'];
        } else {

            if ($param['age1'] != '' and $param['age2'] != '') {
                $queryString .= ' AND (NDV.age >= ' . $param['age1'] . ' AND NDV.age <= ' . $param['age2'] . ')';
            } elseif ($param['age1'] != '' and $param['age2'] == '') {
                $queryString .= ' AND \'' . $param['age1'] . '\' >= NDV.age';
            } elseif ($param['age1'] == '' and $param['age2'] != '') {
                $queryString .= ' AND \'' . $param['age2'] . '\' <= NDV.age';
            }
        }

        if ($param['shortValueId'] != 0) {
            $queryString .= ' AND NDV.short_value_id = ' . $param['shortValueId'];
        }

        if ($param['partnerId'] != 0) {
            $queryString .= ' AND NDV.partner_id IN(' . $this->partner->getChildPartners_model(array($param['partnerId'])) . ')';
        }

        if ($param['motiveId'] != 0) {
            $queryString .= ' AND NDV.motive_id = ' . $param['motiveId'];
        }

        if ($param['closeTypeId'] > 0) {
            $queryString .= ' AND NDV.close_type_id = ' . $param['closeTypeId'];
        }

        if ($param['isCrimeShip'] == 1) {

            $queryString .= ' AND NDV.is_crime_ship = 1';
        }

        if ($param['isCrimeShip'] == 2) {

            $queryString .= ' AND NDV.is_crime_ship = 0';
        }

        if ($param['expertId'] != 0) {
            $queryString .= ' AND NDV.expert_id = ' . $param['expertId'];
        }

        if ($param['createNumber'] != '') {
            $queryString .= ' AND NDV.create_number = ' . $param['createNumber'];
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $queryString .= ' AND LOWER(NDV.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $queryString .= ' AND LOWER(NDV.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Регистрийн дугаар
                        $queryString .= ' AND LOWER(NDV.register) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Утас
                        $queryString .= ' AND LOWER(NDV.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Тайлбар
                        $queryString .= ' AND LOWER(NDV.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 6: {   //Албан хаагч
                        $queryString .= ' AND LOWER(NDV.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 7: {   //Дүгнэлтийн утга
                        $queryString .= ' AND LOWER(NDV.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' AND (
                        LOWER(NDV.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NDV.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NDV.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NDV.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NDV.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NDV.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                        break;
                    }
            }
        }

        if ($param['closeDescription'] != '') {

            $queryString .= ' AND LOWER(NDV.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\')';
        }

        if ($param['whereId'] != 0) {

            $queryString .= ' AND NDV.where_id = ' . $param['whereId'];
        }

        if ($param['payment'] == 1) {

            $queryString .= ' AND NDV.payment = 1';
        }

        if ($param['payment'] == 2) {

            $queryString .= ' AND NDV.payment = 0';
        }

        if ($param['isSperm'] == 1) {

            $queryString .= ' AND NDV.is_sperm = 1';
        }

        if ($param['isSperm'] == 2) {

            $queryString .= ' AND NDV.is_sperm = 0';
        }

        if ($param['sex'] == 1) {

            $queryString .= ' AND NDV.sex = 1';
        }

        if ($param['sex'] == 2) {

            $queryString .= ' AND NDV.sex = 0';
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NDV.close_type_id != 0 AND NDV.close_date <= NDV.out_date';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NDV.close_type_id = 0 AND NOW() <= NDV.out_date';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NDV.close_type_id != 0 AND NDV.close_date > NDV.out_date';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NDV.close_type_id = 0 AND NOW() > NDV.out_date';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND NDV.protocol_out_date < NDV.in_date';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NDV.close_type_id = 0';
        }

        $query = $this->db->query('
            SELECT
                NDV.id,
                NDV.mod_id,
                NDV.cat_id,
                NDV.created_user_id,
                NDV.create_number,
                DATE_FORMAT(NDV.in_date, \'%Y-%m-%d %H:%i\') AS in_date,
                DATE_FORMAT(NDV.out_date, \'%Y-%m-%d %H:%i\') AS out_date,
                NDV.lname,
                NDV.fname,
                NDV.register,
                (CASE 
                        WHEN (NDV.is_age_infinitive = 1) THEN \'\'
                        WHEN (NDV.is_age_infinitive = 0 AND NDV.age > 0 AND NDV.age < 1) THEN CONCAT(CAST(NDV.age AS DECIMAL(10,1)), " сартай")
                        ELSE IF(NDV.is_age_infinitive = 0 AND NDV.age >= 1, CONCAT(CAST(NDV.age AS DECIMAL(10)), " настай"), "")
                    END) AS age,
                (IF(NDV.sex = 1, \'Эр\', \'Эм\')) AS sex,
                NDV.phone,
                NDV.work_id,
                NDV.partner_id,
                NDV.expert_name,
                NDV.short_value_id,
                DATE_FORMAT(NDV.crime_date, \'%Y-%m-%d %H:%i\') AS crime_date,
                (IF(NDV.expert_id != 0, \'background-color: transparent;\', \'background-color: #2196F3; color:rgba(255,255,255,0.8);\')) AS expert_status,
                NDV.where_id,
                NDV.close_type_id,
                DATE(NDV.close_date) AS close_date,
                NDV.close_description,
                IF(NDV.payment = 1, \'Төлбөр төлсөн\', \'Төлбөр төлөөгүй\') AS payment, 
                NDV.protocol_number,
                DATE(NDV.protocol_in_date) AS protocol_in_date,
                DATE(NDV.protocol_out_date) AS protocol_out_date,
                NDV.description,
                (CASE 
                    WHEN (NDV.close_type_id != 0 AND NDV.close_date > NDV.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                    WHEN NDV.close_type_id = 0 AND NOW() > NDV.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                    ELSE \'background-color: transparent;\'
                END) AS row_status,
                (CASE 
                    WHEN (NSD.id != \'\' AND NSD.solution_id != \'\') THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/send-document-blue.svg" style="width:16px;">\'
                    WHEN NSD.id != \'\' AND NSD.solution_id = \'\' THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/send-document-grey.svg" style="width:16px;">\'
                    ELSE \'\'
                END) AS send_document,
                NSD.id AS send_document_id,
                NDV.param
            FROM `gaz_nifs_doctor_view` AS NDV
            LEFT JOIN `gaz_nifs_send_doc` AS NSD ON NDV.mod_id = NSD.mod_id AND NDV.id = NSD.cont_id
            WHERE 1 = 1 ' . $queryString . '
            ORDER BY NDV.create_number ASC');

        if ($query->num_rows() > 0) {

            return $query->result();
        }

        return false;
    }

    public function checkCreateNumber_model($param = array()) {
        $this->query = $this->db->query('
            SELECT 
                NDV.id
            FROM `gaz_nifs_doctor_view` AS NDV
            WHERE 1 = 1 ' . $this->queryString);
    }

    public function sendDocumentFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                object_count,
                send_object,
                question,
                question_id,
                expert,
                type_id,
                weight,
                send_close_description,
                send_created_date,
                send_close_date,
                solution_id,
                modified_date,
                created_user_id,
                modified_user_id
            FROM `gaz_nifs_doctor_view`
            WHERE id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function dataUpdate_model1($param = array()) {

        $query = $this->db->query('
                SELECT 
                    NA.id, 
                    NA.motive_id, 
                    NA.work_id, 
                    NA.partner_id,
                    NA.short_value_id,
                    NA.expert_id,
                    NA.where_id,
                    NA.cat_id,
                    NA.close_type_id,
                    NA.param
                FROM `gaz_nifs_doctor_view` AS NA');

        foreach ($query->result() as $key => $row) {

            $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $row->motive_id));
            $this->nifsWorkData = $this->nifsWork->getData_model(array('selectedId' => $row->work_id));
            $this->partnerData = $this->partner->getData_model(array('selectedId' => $row->partner_id));
            $this->nifsCrimeShortValueData = $this->nifsCrimeShortValue->getData_model(array('selectedId' => $row->short_value_id));
            $this->nifsExpertData = $this->hrPeople->getData_model(array('selectedId' => $row->expert_id));
            $this->nifsWhereData = $this->nifsWhere->getData_model(array('selectedId' => $row->where_id));
            $this->categoryData = $this->category->getData_model(array('selectedId' => $row->cat_id));
            $this->nifsCloseTypeData = $this->nifsCloseType->getData_model(array('selectedId' => $row->close_type_id));

            $data = array(
                'param' => json_encode(
                        array(
                            'motive' => ($this->nifsMotiveData != false ? $this->nifsMotiveData->title : ''),
                            'work' => ($this->nifsWorkData != false ? $this->nifsWorkData->title : ''),
                            'partner' => ($this->partnerData != false ? $this->partnerData->title : ''),
                            'shortValue' => ($this->nifsCrimeShortValueData != false ? $this->nifsCrimeShortValueData->title : ''),
                            'expert' => ($this->nifsExpertData != false ? $this->nifsExpertData->full_name : ''),
                            'where' => ($this->nifsWhereData != false ? $this->nifsWhereData->title : ''),
                            'category' => ($this->categoryData != false ? $this->categoryData->title : ''),
                            'closeType' => ($this->nifsCloseTypeData != false ? $this->nifsCloseTypeData->title : ''))));

            $this->db->where('id', $row->id);
            if ($this->db->update('gaz_nifs_doctor_view', $data)) {

                echo '<pre>';
                var_dump(json_decode($data['param']));
                echo '</pre>';
            }
        }
    }

    public function dataUpdate_model($param = array()) {

        $query = $this->db->query('
            SELECT
                NDV.id,
                NSD_TYPE_11.id AS send_document_chemical_id,
                NSD_TYPE_11.close_type_id AS send_document_chemical_close_type_id,
                NSD_TYPE_8.id AS send_document_biology_id,
                NSD_TYPE_8.close_type_id AS send_document_biology_close_type_id,
                NSD_TYPE_10.id AS send_document_bakterlogy_id,
                NSD_TYPE_10.close_type_id AS send_document_bakterlogy_close_type_id,
                NDV.param
            FROM `gaz_nifs_doctor_view` AS NDV
            LEFT JOIN `gaz_nifs_send_doc` AS NSD_TYPE_11 ON NDV.mod_id = NSD_TYPE_11.module_id AND NDV.id = NSD_TYPE_11.cont_id AND NSD_TYPE_11.type_id = 11
            LEFT JOIN `gaz_nifs_send_doc` AS NSD_TYPE_8 ON NDV.mod_id = NSD_TYPE_8.module_id AND NDV.id = NSD_TYPE_8.cont_id AND NSD_TYPE_8.type_id = 8
            LEFT JOIN `gaz_nifs_send_doc` AS NSD_TYPE_10 ON NDV.mod_id = NSD_TYPE_10.module_id AND NDV.id = NSD_TYPE_10.cont_id AND NSD_TYPE_10.type_id = 10
            ');

        foreach ($query->result() as $key => $row) {

            $data = array(
                'send_document_chemical_id' => $row->send_document_chemical_id,
                'send_document_chemical_close_type_id' => $row->send_document_chemical_close_type_id,
                'send_document_biology_id' => $row->send_document_biology_id,
                'send_document_biology_close_type_id' => $row->send_document_biology_close_type_id,
                'send_document_bakterlogy_id' => $row->send_document_bakterlogy_id,
                'send_document_bakterlogy_close_type_id' => $row->send_document_bakterlogy_close_type_id);

            $this->db->where('id', $row->id);

            $this->db->update($this->db->dbprefix . 'nifs_doctor_view', $data);
        }
    }

}
