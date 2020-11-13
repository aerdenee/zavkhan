<?php

class SnifsAnatomy_model extends CI_Model {

    private static $list = array();

    function __construct() {

        parent::__construct();
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Suser_model', 'user');
        $this->load->model('SnifsMotive_model', 'nifsMotive');
        $this->load->model('SnifsResearchType_model', 'nifsResearchType');
        $this->load->model('SnifsCrimeType_model', 'nifsCrimeType');
        $this->load->model('SnifsCloseType_model', 'nifsCloseType');
        $this->load->model('SnifsWork_model', 'nifsWork');
        $this->load->model('SnifsCrimeShortValue_model', 'nifsCrimeShortValue');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('SnifsWhere_model', 'nifsWhere');
        $this->load->model('SnifsSolution_model', 'nifsSolution');
        $this->load->model('SreportMenu_model', 'sreportMenu');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('SnifsStatus_model', 'nifsStatus');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('SnifsSolution_model', 'nifsSolution');
        $this->load->model('Schart_model', 'chart');


        $this->perPage = 2;
        $this->hrPeoplePositionId = '5,6,7,13,27';
        $this->hrPeopleDepartmentId = '4,16,17,18';

        $this->nifsCrimeTypeId = 357;
        $this->nifsQuestionCatId = 371;
        $this->nifsSolutionCatId = 363;
        $this->nifsCloseTypeCatId = 369;
        $this->nifsWhereCatId = 377;
        $this->nifsResearchTypeCatId = 384;
        $this->nifsMotiveCatId = 390;
        $this->modId = 52;
        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->isActiveDepartment = 'is_active_nifs_anatomy';
        $this->reportDefaultDayInterval = 7;

        if ($this->nifsDepartmentId == 7) {
            $this->nifsDepartmentId = 4;
        }
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => $this->modId,
            'cat_id' => 0,
            'create_number' => getCreateNumber(array('createNumber' => 0, 'table' => 'nifs_anatomy', 'departmentId' => $this->nifsDepartmentId)),
            'in_date' => date('Y-m-d H:i:s'),
            'out_date' => date('Y-m-d H:i:s'),
            'fname' => '',
            'lname' => '',
            'register' => '',
            'age' => '',
            'sex' => 1,
            'address' => '',
            'description' => '',
            'type_id' => 22,
            'work_id' => 0,
            'partner_id' => 0,
            'expert_name' => '',
            'short_value_id' => 0,
            'short_value' => '',
            'expert_id' => 0,
            'where_id' => 0,
            'solution_id' => 0,
            'close_description' => '',
            'close_date' => date('Y-m-d H:i:s'),
            'close_type_id' => 0,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'order_num' => getOrderNum(array('table' => 'nifs_anatomy', 'field' => 'order_num')),
            'year' => '',
            'is_active' => 1,
            'is_age_infinitive' => 0,
            'payment' => 0,
            'param' => '',
            'motive_id' => 32,
            'is_mixx' => 0,
            'protocol_number' => '',
            'protocol_in_date' => '',
            'protocol_out_date' => '',
            'research_type_id' => 0,
            'extra_expert_value' => '',
            'payment_description' => '')));
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                NA.id,
                NA.mod_id,
                NA.cat_id,
                NA.create_number,
                DATE(NA.in_date) AS in_date,
                DATE(NA.out_date) AS out_date,
                NA.fname,
                NA.lname,
                NA.register,
                NA.age,
                NA.sex,
                NA.address,
                NA.description,
                NA.short_value_id,
                NA.short_value,
                NA.type_id,
                NA.work_id,
                NA.where_id,
                NA.partner_id,
                NA.expert_name,
                NA.expert_id,
                NA.where_id,
                NA.solution_id,
                NA.weight,
                NA.close_description,
                NA.close_date,
                NA.close_type_id,
                NA.created_date,
                NA.modified_date,
                NA.created_user_id,
                NA.modified_user_id,
                NA.order_num,
                NA.year,
                NA.is_active,
                NA.is_age_infinitive,
                NA.payment,
                NA.param,
                NA.begin_date,
                NA.end_date,
                NA.motive_id,
                NA.is_mixx,
                NA.protocol_number,
                DATE(NA.protocol_in_date) AS protocol_in_date,
                DATE(NA.protocol_out_date) AS protocol_out_date,
                \'\' AS research_type_id,
                NA.extra_expert_value,
                NA.payment_description
            FROM `gaz_nifs_anatomy` AS NA
            WHERE NA.id = ' . $param['id']);

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

            $queryString .= ' AND NA.created_user_id = ' . $this->session->adminUserId;
        } else {

            $queryString .= ' AND NA.created_user_id = -1';
        }

        if ($param['selectedId'] != 0) {

            $queryString .= ' AND NA.id = ' . $param['selectedId'];
        }

        if ($param['departmentId'] != 0) {

            $queryString .= ' AND NA.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
        } else {

            $queryString .= ' AND NA.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        }

        if ($param['createNumber'] != '') {

            $queryString .= ' AND NA.create_number = ' . $param['createNumber'];
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $queryString .= ' AND LOWER(NA.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $queryString .= ' AND LOWER(NA.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Регистрийн дугаар
                        $queryString .= ' AND LOWER(NA.register) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Хаяг
                        $queryString .= ' AND LOWER(NA.address) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Албан хаагч
                        $queryString .= ' AND LOWER(NA.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 6: {   //Болсон хэргийн товч
                        $queryString .= ' AND LOWER(NA.short_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 7: {   //Тайлбар
                        $queryString .= ' AND LOWER(NA.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 8: {   //Дүгнэлтийн утга
                        $queryString .= ' AND LOWER(NA.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' AND (
                        LOWER(NA.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NA.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NA.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NA.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NA.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NA.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NA.short_value) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                        break;
                    }
            }
        }

        if ($param['workId'] != 0) {

            $queryString .= ' AND NA.work_id = ' . $param['workId'];
        } else if ($param['workId'] == 'all') {

            $queryString .= ' AND NA.work_id != 0';
        }

        if ($param['partnerId'] != 0) {

            $queryString .= ' AND NA.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
        } else if ($param['partnerId'] == 'all') {
            $queryString .= ' AND NA.partner_id != 0';
        }

        if ($param['shortValueId'] != 0) {

            $queryString .= ' AND NA.short_value_id = ' . $param['shortValueId'];
        } else if ($param['shortValueId'] == 'all') {
            $queryString .= ' AND NA.short_value_id != 0';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NA.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NA.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NA.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NA.out_date)';
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NA.protocol_in_date) AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NA.protocol_in_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NA.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NA.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NA.end_date) AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NA.end_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NA.end_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NA.end_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND NA.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['isAgeInfinitive'] == 1) {

            $queryString .= ' AND NA.is_age_infinitive = ' . $param['isAgeInfinitive'];
        } else {

            if ($param['age1'] != '' and $param['age2'] != '') {

                $queryString .= ' AND (NA.age >= ' . $param['age1'] . ' AND NA.age <= ' . $param['age2'] . ') AND NA.is_age_infinitive = 0';
            } else if ($param['age1'] != '' and $param['age2'] == '') {

                $queryString .= ' AND \'' . $param['age1'] . '\' >= NA.age AND NA.is_age_infinitive = 0';
            } else if ($param['age1'] == '' and $param['age2'] != '') {

                $queryString .= ' AND \'' . $param['age2'] . '\' <= NA.age AND NA.is_age_infinitive = 0';
            }
        }

        if ($param['whereId'] != 0) {

            $queryString .= ' AND NA.where_id = ' . $param['whereId'];
        } else if ($param['whereId'] == 'all') {
            $queryString .= ' AND NA.where_id != 0';
        }

        if ($param['catId'] != 0) {

            $queryString .= ' AND NA.cat_id = ' . $param['catId'];
        } else if ($param['catId'] == 'all') {
            $queryString .= ' AND NA.cat_id != all';
        }

        if ($param['motiveId'] != 0) {

            $queryString .= ' AND NA.motive_id = ' . $param['motiveId'];
        } else if ($param['motiveId'] == 'all') {

            $queryString .= ' AND NA.motive_id != 0';
        }

        if ($param['isMixx'] == 1) {

            $queryString .= ' AND NA.is_mixx = 1';
        }

        if ($param['isMixx'] == 2) {

            $queryString .= ' AND NA.is_mixx = 0';
        }

        if ($param['closeDate'] != '') {

            $queryString .= ' AND DATE(NA.close_date) = DATE(\'' . $param['closeDate'] . '\')';
        }

        if ($param['solutionId'] > 0) {

            $queryString .= ' AND NA.solution_id = ' . $param['solutionId'];
        } else if ($param['solutionId'] == 'all') {

            $queryString .= ' AND NA.solution_id != 0';
        }

        if ($param['closeTypeId'] > 0) {

            $queryString .= ' AND NA.close_type_id = ' . $param['closeTypeId'];
        } else if ($param['closeTypeId'] == 'all') {
            $queryString .= ' AND NA.close_type_id != 0';
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NA.solution_id != 0 AND DATE(NA.end_date) <= DATE(NA.out_date)';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NA.solution_id = 0 AND CURDATE() <= DATE(NA.out_date)';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NA.solution_id != 0 AND DATE(NA.end_date) > DATE(NA.out_date)';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NA.solution_id = 0 AND DATE(\'' . $param['outDate'] . '\') >= DATE(NA.out_date)';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND DATE(NA.protocol_out_date) < DATE(NA.in_date)';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NA.solution_id = 0';
        }

        if ($param['closeDescription'] != '') {

            $queryString .= ' AND (LOWER(NA.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        if ($param['sex'] == 1) {

            $queryString .= ' AND NA.sex = 1';
        }

        if ($param['sex'] == 2) {

            $queryString .= ' AND NA.sex = 0';
        }

        if ($param['payment'] == 1) {
            $queryString .= ' AND NA.payment = 1';
        }

        if ($param['payment'] == 2) {
            $queryString .= ' AND NA.payment = 0';
        }

        if ($param['payment'] == 3) {
            $queryString .= ' AND NA.payment = 2';
        }

        if ($param['expertId'] != 0) {

            $expertString = ' AND NE.expert_id = ' . $param['expertId'];
            if ($param['expertId'] == 'all') {
                $expertString = ' AND NE.expert_id != 0';
            }
            $query = $this->db->query('
                SELECT 
                    NE.expert_id 
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_anatomy` AS NA ON NE.cont_id = NA.id AND NE.mod_id = NA.mod_id
                WHERE 1 = 1 ' . $expertString . $queryString);
        } else {
            $query = $this->db->query('
                SELECT 
                    NA.id
                FROM `gaz_nifs_anatomy` AS NA
                WHERE 1 = 1 ' . $queryString);
        }
        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $data = array();
        $query = $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {

            $queryString .= '';
        } else if ($auth->our->read and ! $auth->your->read) {

            $queryString .= ' AND NA.created_user_id = ' . $this->session->adminUserId;
        } else {

            $queryString .= ' AND NA.created_user_id = -1';
        }

        if ($param['selectedId'] != 0) {

            $queryString .= ' AND NA.id = ' . $param['selectedId'];
        }

        if ($param['departmentId'] != 0) {

            $queryString .= ' AND NA.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
        } else {

            $queryString .= ' AND NA.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        }

        if ($param['createNumber'] != '') {

            $queryString .= ' AND NA.create_number = ' . $param['createNumber'];
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $queryString .= ' AND LOWER(NA.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $queryString .= ' AND LOWER(NA.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Регистрийн дугаар
                        $queryString .= ' AND LOWER(NA.register) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Хаяг
                        $queryString .= ' AND LOWER(NA.address) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Албан хаагч
                        $queryString .= ' AND LOWER(NA.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 6: {   //Болсон хэргийн товч
                        $queryString .= ' AND LOWER(NA.short_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 7: {   //Тайлбар
                        $queryString .= ' AND LOWER(NA.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 8: {   //Дүгнэлтийн утга
                        $queryString .= ' AND LOWER(NA.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' AND (
                        LOWER(NA.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NA.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NA.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NA.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NA.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NA.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NA.short_value) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                        break;
                    }
            }
        }

        if ($param['workId'] != 0) {

            $queryString .= ' AND NA.work_id = ' . $param['workId'];
        } else if ($param['workId'] == 'all') {

            $queryString .= ' AND NA.work_id != 0';
        }

        if ($param['partnerId'] != 0) {

            $queryString .= ' AND NA.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
        } else if ($param['partnerId'] == 'all') {
            $queryString .= ' AND NA.partner_id != 0';
        }

        if ($param['shortValueId'] != 0) {

            $queryString .= ' AND NA.short_value_id = ' . $param['shortValueId'];
        } else if ($param['shortValueId'] == 'all') {
            $queryString .= ' AND NA.short_value_id != 0';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NA.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NA.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NA.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NA.out_date)';
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NA.protocol_in_date) AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NA.protocol_in_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NA.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NA.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NA.end_date) AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NA.end_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NA.end_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NA.end_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND NA.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['isAgeInfinitive'] == 1) {

            $queryString .= ' AND NA.is_age_infinitive = ' . $param['isAgeInfinitive'];
        } else {

            if ($param['age1'] != '' and $param['age2'] != '') {

                $queryString .= ' AND (NA.age >= ' . $param['age1'] . ' AND NA.age <= ' . $param['age2'] . ') AND NA.is_age_infinitive = 0';
            } else if ($param['age1'] != '' and $param['age2'] == '') {

                $queryString .= ' AND \'' . $param['age1'] . '\' >= NA.age AND NA.is_age_infinitive = 0';
            } else if ($param['age1'] == '' and $param['age2'] != '') {

                $queryString .= ' AND \'' . $param['age2'] . '\' <= NA.age AND NA.is_age_infinitive = 0';
            }
        }

        if ($param['whereId'] != 0) {

            $queryString .= ' AND NA.where_id = ' . $param['whereId'];
        } else if ($param['whereId'] == 'all') {
            $queryString .= ' AND NA.where_id != 0';
        }

        if ($param['catId'] != 0) {

            $queryString .= ' AND NA.cat_id = ' . $param['catId'];
        } else if ($param['catId'] == 'all') {
            $queryString .= ' AND NA.cat_id != all';
        }

        if ($param['motiveId'] != 0) {

            $queryString .= ' AND NA.motive_id = ' . $param['motiveId'];
        } else if ($param['motiveId'] == 'all') {

            $queryString .= ' AND NA.motive_id != 0';
        }

        if ($param['isMixx'] == 1) {

            $queryString .= ' AND NA.is_mixx = 1';
        }

        if ($param['isMixx'] == 2) {

            $queryString .= ' AND NA.is_mixx = 0';
        }

        if ($param['closeDate'] != '') {

            $queryString .= ' AND DATE(NA.close_date) = DATE(\'' . $param['closeDate'] . '\')';
        }

        if ($param['solutionId'] > 0) {

            $queryString .= ' AND NA.solution_id = ' . $param['solutionId'];
        } else if ($param['solutionId'] == 'all') {

            $queryString .= ' AND NA.solution_id != 0';
        }

        if ($param['closeTypeId'] > 0) {

            $queryString .= ' AND NA.close_type_id = ' . $param['closeTypeId'];
        } else if ($param['closeTypeId'] == 'all') {
            $queryString .= ' AND NA.close_type_id != 0';
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NA.solution_id != 0 AND DATE(NA.end_date) <= DATE(NA.out_date)';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NA.solution_id = 0 AND CURDATE() <= DATE(NA.out_date)';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NA.solution_id != 0 AND DATE(NA.end_date) > DATE(NA.out_date)';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NA.solution_id = 0 AND DATE(\'' . $param['outDate'] . '\') >= DATE(NA.out_date)';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND DATE(NA.protocol_out_date) < DATE(NA.in_date)';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NA.solution_id = 0';
        }

        if ($param['closeDescription'] != '') {

            $queryString .= ' AND (LOWER(NA.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        if ($param['sex'] == 1) {

            $queryString .= ' AND NA.sex = 1';
        }

        if ($param['sex'] == 2) {

            $queryString .= ' AND NA.sex = 0';
        }

        if ($param['payment'] == 1) {
            $queryString .= ' AND NA.payment = 1';
        }

        if ($param['payment'] == 2) {
            $queryString .= ' AND NA.payment = 0';
        }

        if ($param['payment'] == 3) {
            $queryString .= ' AND NA.payment = 2';
        }

        if ($param['expertId'] != 0) {

            $expertString = ' AND NE.expert_id = ' . $param['expertId'];
            if ($param['expertId'] == 'all') {
                $expertString = ' AND NE.expert_id != 0';
            }

            $query = $this->db->query('
                SELECT
                    NA.id,
                    NA.mod_id,
                    NA.cat_id,
                    NA.created_user_id,
                    NA.create_number,
                    IF(NA.is_mixx = 1, "<span class=\'fa fa-users\'></span>", "") AS is_mixx,
                    CONCAT("И: ", DATE(NA.in_date), "<br>", "Д: ", DATE(NA.out_date)) AS in_out_date,
                    CONCAT("Э: ", DATE(NA.begin_date), "<br>", "Д: ", DATE(NA.end_date)) AS be_date,
                    CONCAT(IF(DATE(NA.protocol_in_date) != \'0000-00-00\', CONCAT("(И:", DATE(NA.protocol_in_date), " "), \'\'), IF(DATE(NA.protocol_out_date) != \'0000-00-00\', CONCAT(" Д:", DATE(NA.protocol_out_date), ")"), \'\')) AS protocol_in_out_date,
                    (IF(NA.lname != \'\', CONCAT(NA.lname, "-н ", "<strong>", NA.fname, "</strong>"), NA.fname)) AS full_name,
                    (CASE 
                            WHEN (NA.is_age_infinitive = 1) THEN \', нас тодорхойгүй\'
                            WHEN (NA.is_age_infinitive = 0 AND NA.age < 1) THEN CONCAT(", ", CONVERT(SUBSTRING(NA.age, 3, 2),UNSIGNED INTEGER), " сартай")
                            ELSE IF(NA.is_age_infinitive = 0 AND NA.age >= 1, CONCAT(", ", CONVERT(NA.age,UNSIGNED INTEGER), " настай"), "")
                    END) AS age,
                    (IF(NA.sex = 1, \', эр\', \', эм\')) AS sex,
                    (IF(NA.register != \'\', CONCAT(\', \', NA.register, \'\'), \'\')) AS register,
                    NA.work_id,
                    NA.partner_id,
                    IF(NA.short_value != \'\', CONCAT(NA.short_value, \', \'), \'\') AS short_value,
                    NA.expert,
                    IF(NA.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:rgba(255,255,255,0.8);\') AS expert_status,
                    NA.expert_name,
                    NA.where_id,
                    CONCAT((CASE 
                        WHEN (NA.payment = 1) THEN CONCAT(\'Төлбөр төлсөн\')
                        WHEN NA.payment = 0 THEN CONCAT(\'Төлбөр төлөөгүй\')
                        ELSE CONCAT(\'Төлбөрөөс чөлөөлсөн\', \'<br>\', NA.payment_description)
                    END), \'\', IF(NA.description != \'\', CONCAT(\' (\', NA.description, \')\'), \'\')) AS payment,
                    NA.solution_id,
                    NA.close_description,
                    NA.description,
                    IF(DATE(NA.end_date) != \'0000-00-00\', DATE(NA.end_date), \'\') AS end_date,
                    (CASE 
                            WHEN (NA.solution_id != 0 AND NA.end_date > NA.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                            WHEN NA.solution_id = 0 AND NOW() > NA.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                            ELSE \'background-color: transparent;\'
                    END) AS row_status,
                    (CASE 
			WHEN (NA.send_document_chemical_id != 0 AND NA.send_document_chemical_close_type_id != 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/chemical.svg" style="width:16px;" alt="Хими">\'
			WHEN (NA.send_document_chemical_id != 0 AND NA.send_document_chemical_close_type_id = 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/chemical-grey.svg" style="width:16px;" alt="Хими">\'
			ELSE \'\'
                    END) AS send_document_chemical,
                    (CASE 
			WHEN (NA.send_document_biology_id != 0 AND NA.send_document_biology_close_type_id != 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/dna.svg" style="width:16px;" alt="Биологи">\'
			WHEN (NA.send_document_biology_id != 0 AND NA.send_document_biology_close_type_id = 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/dna-grey.svg" style="width:16px;" alt="Биологи">\'
			ELSE \'\'
                    END) AS send_document_biology,
                    (CASE 
			WHEN (NA.send_document_bakterlogy_id != 0 AND NA.send_document_bakterlogy_close_type_id != 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/baktery.svg" style="width:16px;" alt="Бактериологи">\'
			WHEN (NA.send_document_bakterlogy_id != 0 AND NA.send_document_bakterlogy_close_type_id = 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/baktery-grey.svg" style="width:16px;" alt="Бактериологи">\'
			ELSE \'\'
                    END) AS send_document_bakterlogy,
                    NA.send_document_chemical_id,
                    NA.send_document_chemical_close_type_id,
                    NA.send_document_biology_id,
                    NA.send_document_biology_close_type_id,
                    NA.send_document_bakterlogy_id,
                    NA.send_document_bakterlogy_close_type_id,
                    NA.param
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_anatomy` AS NA ON NE.cont_id = NA.id AND NE.mod_id = NA.mod_id
                WHERE 1 = 1 ' . $expertString . $queryString . '
                ORDER BY NA.create_number DESC
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        } else {
            $query = $this->db->query('
                SELECT 
                    NA.id,
                    NA.mod_id,
                    NA.cat_id,
                    NA.created_user_id,
                    NA.create_number,
                    IF(NA.is_mixx = 1, "<span class=\'fa fa-users\'></span>", "") AS is_mixx,
                    CONCAT("И: ", DATE(NA.in_date), "<br>", "Д: ", DATE(NA.out_date)) AS in_out_date,
                    CONCAT("Э: ", DATE(NA.begin_date), "<br>", "Д: ", DATE(NA.end_date)) AS be_date,
                    CONCAT(IF(DATE(NA.protocol_in_date) != \'0000-00-00\', CONCAT("(И:", DATE(NA.protocol_in_date), " "), \'\'), IF(DATE(NA.protocol_out_date) != \'0000-00-00\', CONCAT(" Д:", DATE(NA.protocol_out_date), ")"), \'\')) AS protocol_in_out_date,
                    (IF(NA.lname != \'\', CONCAT(NA.lname, "-н ", "<strong>", NA.fname, "</strong>"), NA.fname)) AS full_name,
                    (CASE 
                            WHEN (NA.is_age_infinitive = 1) THEN \', нас тодорхойгүй\'
                            WHEN (NA.is_age_infinitive = 0 AND NA.age < 1) THEN CONCAT(", ", CONVERT(SUBSTRING(NA.age, 3, 2),UNSIGNED INTEGER), " сартай")
                            ELSE IF(NA.is_age_infinitive = 0 AND NA.age >= 1, CONCAT(", ", CONVERT(NA.age,UNSIGNED INTEGER), " настай"), "")
                    END) AS age,
                    (IF(NA.sex = 1, \', эр\', \', эм\')) AS sex,
                    (IF(NA.register != \'\', CONCAT(\', \', NA.register, \'\'), \'\')) AS register,
                    NA.work_id,
                    NA.partner_id,
                    IF(NA.short_value != \'\', CONCAT(NA.short_value, \', \'), \'\') AS short_value,
                    NA.expert,
                    IF(NA.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:rgba(255,255,255,0.8);\') AS expert_status,
                    NA.expert_name,
                    NA.where_id,
                    CONCAT((CASE 
                        WHEN (NA.payment = 1) THEN CONCAT(\'Төлбөр төлсөн\')
                        WHEN NA.payment = 0 THEN CONCAT(\'Төлбөр төлөөгүй\')
                        ELSE CONCAT(\'Төлбөрөөс чөлөөлсөн\', \'<br>\', NA.payment_description)
                    END), \'\', IF(NA.description != \'\', CONCAT(\' (\', NA.description, \')\'), \'\')) AS payment,
                    NA.solution_id,
                    NA.close_description,
                    NA.description,
                    IF(DATE(NA.end_date) != \'0000-00-00\', DATE(NA.end_date), \'\') AS end_date,
                    (CASE 
                            WHEN (NA.solution_id != 0 AND NA.end_date > NA.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                            WHEN NA.solution_id = 0 AND NOW() > NA.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                            ELSE \'background-color: transparent;\'
                    END) AS row_status,
                    (CASE 
			WHEN (NA.send_document_chemical_id != 0 AND NA.send_document_chemical_close_type_id != 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/chemical.svg" style="width:16px;" alt="Хими">\'
			WHEN (NA.send_document_chemical_id != 0 AND NA.send_document_chemical_close_type_id = 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/chemical-grey.svg" style="width:16px;" alt="Хими">\'
			ELSE \'\'
                    END) AS send_document_chemical,
                    (CASE 
			WHEN (NA.send_document_biology_id != 0 AND NA.send_document_biology_close_type_id != 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/dna.svg" style="width:16px;" alt="Биологи">\'
			WHEN (NA.send_document_biology_id != 0 AND NA.send_document_biology_close_type_id = 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/dna-grey.svg" style="width:16px;" alt="Биологи">\'
			ELSE \'\'
                    END) AS send_document_biology,
                    (CASE 
			WHEN (NA.send_document_bakterlogy_id != 0 AND NA.send_document_bakterlogy_close_type_id != 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/baktery.svg" style="width:16px;" alt="Бактериологи">\'
			WHEN (NA.send_document_bakterlogy_id != 0 AND NA.send_document_bakterlogy_close_type_id = 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/baktery-grey.svg" style="width:16px;" alt="Бактериологи">\'
			ELSE \'\'
                    END) AS send_document_bakterlogy,
                    NA.send_document_chemical_id,
                    NA.send_document_chemical_close_type_id,
                    NA.send_document_biology_id,
                    NA.send_document_biology_close_type_id,
                    NA.send_document_bakterlogy_id,
                    NA.send_document_bakterlogy_close_type_id,
                    NA.param
                FROM `gaz_nifs_anatomy` AS NA 
                WHERE 1 = 1 ' . $queryString . '
                ORDER BY NA.create_number DESC
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        }

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $param = json_decode($row->param);

                array_push($data, array(
                    'id' => $row->id,
                    'mod_id' => $row->mod_id,
                    'create_number' => $row->create_number,
                    'in_out_date' => $row->in_out_date,
                    'be_date' => $row->be_date,
                    'resolution' => $row->protocol_in_out_date,
                    'full_name' => $row->full_name . $row->age . $row->sex . $row->register,
                    'is_work' => $param->work,
                    'partner' => $param->partner . ' ' . $row->expert_name,
                    'short_value' => $row->short_value,
                    'expert_status' => $row->expert_status,
                    'expert' => $row->expert,
                    'is_where' => $param->where . '<br>' . $row->payment,
                    'diagnosis' => $row->end_date . '<br><strong>' . $param->solution . '</strong> ' . $row->close_description,
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
        $this->nifsTypeData = $this->nifsCrimeType->getData_model(array('selectedId' => $this->input->post('typeId')));
        $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->post('partnerId')));
        $this->nifsWhereData = $this->nifsWhere->getData_model(array('selectedId' => $this->input->post('whereId')));

        $this->data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'create_number' => getCreateNumber(array('createNumber' => $this->input->post('createNumber'), 'table' => 'nifs_anatomy', 'departmentId' => $this->nifsDepartmentId)),
                'in_date' => $this->input->post('inDate'),
                'out_date' => $this->input->post('outDate'),
                'fname' => $this->input->post('fname'),
                'lname' => $this->input->post('lname'),
                'register' => mb_strtoupper($this->input->post('register'), 'UTF-8'),
                'age' => ($this->input->post('age') != null ? $this->input->post('age') : 0),
                'is_age_infinitive' => ($this->input->post('isAgeInfinitive') != null ? $this->input->post('isAgeInfinitive') : 0),
                'sex' => $this->input->post('sex'),
                'address' => $this->input->post('address'),
                'description' => $this->input->post('description'),
                'short_value' => $this->input->post('shortValue'),
                'type_id' => $this->input->post('typeId'),
                'work_id' => $this->input->post('workId'),
                'partner_id' => $this->input->post('partnerId'),
                'expert_name' => $this->input->post('expertName'),
                'where_id' => $this->input->post('whereId'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'order_num' => getOrderNum(array('table' => 'nifs_anatomy', 'field' => 'order_num')),
                'year' => $this->session->adminCloseYear,
                'is_active' => 1,
                'department_id' => $this->nifsDepartmentId,
                'param' => json_encode(array(
                    'motive' => ($this->nifsMotiveData != false ? $this->nifsMotiveData->title : ''),
                    'work' => ($this->nifsWorkData != false ? $this->nifsWorkData->title : ''),
                    'type' => ($this->nifsTypeData != false ? $this->nifsTypeData->title : ''),
                    'partner' => ($this->partnerData != false ? $this->partnerData->title : ''),
                    'where' => ($this->nifsWhereData != false ? $this->nifsWhereData->title : ''),
                    'solution' => '',
                    'closeType' => '')),
                'payment' => $this->input->post('payment'),
                'motive_id' => $this->input->post('motiveId'),
                'is_mixx' => $this->input->post('isMixx'),
                'protocol_number' => $this->input->post('protocolNumber'),
                'protocol_in_date' => $this->input->post('protocolInDate'),
                'protocol_out_date' => $this->input->post('protocolOutDate'),
                'extra_expert_value' => $this->input->post('extraExpertValue'),
                'payment_description' => $this->input->post('paymentDescription')
            )
        );
        if ($this->db->insert_batch($this->db->dbprefix . 'nifs_anatomy', $this->data)) {
            return $this->hrPeople->updateHrPeopleParam_model(array('modId' => $this->input->post('modId'), 'contId' => $param['getUID'], 'expertId' => $this->input->post('expertId'), 'extraExpertValue' => $this->input->post('extraExpertValue'), 'table' => 'nifs_anatomy'));
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
        $this->nifsTypeData = $this->nifsCrimeType->getData_model(array('selectedId' => $this->input->post('typeId')));
        $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->post('partnerId')));
        $this->nifsWhereData = $this->nifsWhere->getData_model(array('selectedId' => $this->input->post('whereId')));

        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'create_number' => $this->input->post('createNumber'),
            'in_date' => $this->input->post('inDate'),
            'out_date' => $this->input->post('outDate'),
            'fname' => $this->input->post('fname'),
            'lname' => $this->input->post('lname'),
            'register' => mb_strtoupper($this->input->post('register'), 'UTF-8'),
            'age' => ($this->input->post('age') != null ? $this->input->post('age') : 0),
            'is_age_infinitive' => ($this->input->post('isAgeInfinitive') != null ? $this->input->post('isAgeInfinitive') : 0),
            'sex' => $this->input->post('sex'),
            'address' => $this->input->post('address'),
            'description' => $this->input->post('description'),
            'short_value' => $this->input->post('shortValue'),
            'type_id' => $this->input->post('typeId'),
            'work_id' => $this->input->post('workId'),
            'partner_id' => $this->input->post('partnerId'),
            'expert_name' => $this->input->post('expertName'),
            'where_id' => $this->input->post('whereId'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'param' => json_encode(array(
                'motive' => ($this->nifsMotiveData != false ? $this->nifsMotiveData->title : ''),
                'work' => ($this->nifsWorkData != false ? $this->nifsWorkData->title : ''),
                'type' => ($this->nifsTypeData != false ? $this->nifsTypeData->title : ''),
                'partner' => ($this->partnerData != false ? $this->partnerData->title : ''),
                'where' => ($this->nifsWhereData != false ? $this->nifsWhereData->title : ''),
                'solution' => '',
                'closeType' => '')),
            'payment' => $this->input->post('payment'),
            'motive_id' => $this->input->post('motiveId'),
            'is_mixx' => $this->input->post('isMixx'),
            'protocol_number' => $this->input->post('protocolNumber'),
            'protocol_in_date' => $this->input->post('protocolInDate'),
            'protocol_out_date' => $this->input->post('protocolOutDate'),
            'extra_expert_value' => $this->input->post('extraExpertValue'),
            'payment_description' => $this->input->post('paymentDescription'),
            'year' => $this->session->adminCloseYear
        );

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'nifs_anatomy', $this->data)) {
            return $this->hrPeople->updateHrPeopleParam_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('id'), 'expertId' => $this->input->post('expertId'), 'extraExpertValue' => $this->input->post('extraExpertValue'), 'table' => 'nifs_anatomy'));
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

        if ($this->db->delete($this->db->dbprefix . 'nifs_anatomy')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }

        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function close_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'type' => LOG_TYPE_CLOSE,
            'data' => json_encode($_POST)));

        $this->nifsSolutionData = $this->nifsSolution->getData_model(array('selectedId' => $this->input->post('solutionId')));
        $this->nifsCloseTypeData = $this->nifsCloseType->getData_model(array('selectedId' => $this->input->post('closeTypeId')));

        $extraData = $this->getData_model(array('selectedId' => $this->input->post('id')));
        $param = json_decode($extraData->param);

        $param->solution = ($this->nifsSolutionData != false ? $this->nifsSolutionData->title : '');
        $param->closeType = ($this->nifsCloseTypeData != false ? $this->nifsCloseTypeData->title : '');

        $this->data = array(
            'begin_date' => $this->input->post('beginDate'),
            'end_date' => $this->input->post('endDate'),
            'solution_id' => $this->input->post('solutionId'),
            'close_type_id' => $this->input->post('closeTypeId'),
            'close_description' => $this->input->post('closeDescription'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'param' => json_encode($param));
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'nifs_anatomy', $this->data)) {
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
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('shortValueId', $this->input->get('shortValueId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('expertId')) {
            if ($this->input->get('expertId') != 'all') {
                $this->expertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->get('expertId')));
                $this->string .= '<span class="label label-default label-rounded">Шинжээч эмч: ' . mb_substr($this->expertData->lname, 0, 1, 'UTF-8') . '.' . $this->expertData->fname . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }
            $this->string .= form_hidden('expertId', $this->input->get('expertId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('sex')) {
            if ($this->input->get('sex') != 0) {
                $this->sexData = $this->hrPeopleSex->getData_model(array('selectedId' => $this->input->get('sex')));
                $this->string .= '<span class="label label-default label-rounded">Хүйс: ' . $this->sexData->title . '</span>';
                $this->string .= form_hidden('sex', $this->input->get('sex'));
                $this->showResetBtn = TRUE;
            }
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

        if ($this->input->get('solutionId')) {

            if ($this->input->get('solutionId') != 'all') {
                $this->nifsSolution = $this->nifsSolution->getData_model(array('selectedId' => $this->input->get('solutionId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsSolution->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүх шинжилгээ</span>';
            }

            $this->string .= form_hidden('solutionId', $this->input->get('solutionId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('isMixx') and $this->input->get('isMixx') == 1) {
            $this->string .= '<span class="label label-default label-rounded">Бүрэлдэхүүнтэй шинжилгээ</span>';
            $this->string .= form_hidden('isMixx', $this->input->get('isMixx'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('isMixx') and $this->input->get('isMixx') == 2) {
            $this->string .= '<span class="label label-default label-rounded">Бүрэлдэхүүнгүй шинжилгээ</span>';
            $this->string .= form_hidden('isMixx', $this->input->get('isMixx'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('catId')) {
            if ($this->input->get('catId') != 'all') {
                $this->cat = $this->category->getData_model(array('selectedId' => $this->input->get('catId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->cat->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('catId', $this->input->get('catId'));
            $this->showResetBtn = TRUE;
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

        $this->string .= form_hidden('protocolInDate', $this->input->get('protocolInDate'));
        $this->string .= form_hidden('protocolOutDate', $this->input->get('protocolOutDate'));
        if ($this->input->get('protocolInDate') and $this->input->get('protocolOutDate')) {
            $this->string .= '<span class="label label-default label-rounded">Тогтоолын огноо: ' . $this->input->get('protocolInDate') . '-' . $this->input->get('protocolOutDate') . '</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('protocolInDate')) {
            $this->string .= '<span class="label label-default label-rounded">Тогтоолын огноо: ' . $this->input->get('protocolInDate') . ' хойш</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('protocolOutDate')) {
            $this->string .= '<span class="label label-default label-rounded">Тогтоолын огноо: ' . $this->input->get('protocolOutDate') . ' өмнөх</span>';
            $this->showResetBtn = TRUE;
        }

        $this->string .= form_hidden('closeInDate', $this->input->get('closeInDate'));
        $this->string .= form_hidden('closeOutDate', $this->input->get('closeOutDate'));
        if ($this->input->get('closeInDate') and $this->input->get('closeOutDate')) {

            $this->string .= '<span class="label label-default label-rounded">Хаасан огноо: ' . date('Y.m.d', strtotime($this->input->get('closeInDate'))) . '-' . date('Y.m.d', strtotime($this->input->get('closeOutDate'))) . '</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('closeInDate')) {

            $this->string .= '<span class="label label-default label-rounded">Хаасан огноо: ' . _dateFormat(array('date' => $this->input->get('closeInDate'))) . ' хойш</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('closeOutDate')) {

            $this->string .= '<span class="label label-default label-rounded">Хаасан огноо: ' . _dateFormat(array('date' => $this->input->get('closeOutDate'))) . ' өмнөх</span>';
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

        if ($this->input->get('selectedId')) {

            $this->string .= '<span class="label label-default label-rounded">Системд бүртгэгдсэн дугаар: ' . $this->input->get('selectedId') . '</span>';
            $this->string .= form_hidden('selectedId', $this->input->get('selectedId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            //' . Scrime::$path . $param['path'] . '/' . $param['modId'] . '

            if ($this->input->get('isReportMenu') == 1) {

                $this->string .= ' <a href="javascript:;" onclick="_reportItem({reportMenuId: ' . $this->input->get('reportMenuId') . ', reportModId: ' . $this->input->get('reportModId') . '});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            } else if ($this->input->get('selectedId') > 0) {

                $this->string .= ' <a href="/snifsSearch?keyword=' . $this->input->get('keyword') . '"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            } else {

                $this->string .= ' <a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            }
        }


        return ($this->showResetBtn ? '<div class="_search-result-inner"><form action="javascript:;" id="form-nifs-anatomy"> Хайлтын үр дүн: ' . $this->string . '</form></div>' : '');
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                NA.id,
                NA.mod_id,
                NA.cat_id,
                NA.create_number,
                DATE(NA.in_date) AS in_date,
                DATE(NA.out_date) AS out_date,
                NA.fname,
                NA.lname,
                NA.register,
                NA.age,
                NA.sex,
                NA.address,
                NA.description,
                NA.short_value_id,
                NA.short_value,
                NA.type_id,
                NA.work_id,
                NA.where_id,
                NA.partner_id,
                NA.expert_name,
                NA.expert_id,
                NA.where_id,
                NA.solution_id,
                NA.weight,
                NA.close_description,
                NA.close_date,
                NA.close_type_id,
                NA.created_date,
                NA.modified_date,
                NA.created_user_id,
                NA.modified_user_id,
                NA.order_num,
                NA.year,
                NA.is_active,
                NA.is_age_infinitive,
                NA.payment,
                NA.param,
                NA.begin_date,
                NA.end_date,
                NA.motive_id,
                NA.is_mixx,
                NA.protocol_number,
                DATE(NA.protocol_in_date) AS protocol_in_date,
                DATE(NA.protocol_out_date) AS protocol_out_date,
                \'\' AS research_type_id
            FROM `gaz_nifs_anatomy` AS NA
            WHERE NA.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

    public function getReportWorkInformationData_model($param = array()) {

        $htmlData = $queryStringData = $queryStringYearData = $queryStringInDateData = $queryStringObjectData = $queryStringMotiveData = $isAjaxDepartmentUrl = '';

        if ($param['departmentId'] > 0) {

            $queryStringData .= ' AND NA.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $queryStringYearData .= ' AND NA.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        if ($param['reportIsClose'] == 1) {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NA.end_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NA.end_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NA.end_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NA.end_date)';
            }
        } else {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NA.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NA.in_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NA.in_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NA.out_date)';
            }
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NA.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $query = $this->db->query('
            SELECT 
                M.id,
                M.title,
                NA.motive_count,
                IF(NA.motive_count > 0, \'_row-more\', \'\') AS motive_count_class
            FROM `gaz_nifs_motive` AS M
            LEFT JOIN (
                SELECT 
                    NA.motive_id,
                    COUNT(NA.motive_id) AS motive_count 
                FROM `gaz_nifs_anatomy` AS NA 
                WHERE 1 = 1 AND NA.motive_id != 0 ' . $queryStringData . '
                GROUP BY NA.motive_id
            ) AS NA ON NA.motive_id = M.id
            WHERE M.is_active = 1 AND M.cat_id = ' . $this->nifsMotiveCatId);

        if ($query->num_rows() > 0) {

            $i = $sumTotalMotiveCount = 0;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Гарчиг</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Тоо</th>';
            $htmlData .= '</tr>';
            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $sumTotalMotiveCount = $sumTotalMotiveCount + $row->motive_count;

                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->motive_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'motiveId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->motive_count . '</a></td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';

            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'motiveId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalMotiveCount > 0 ? $sumTotalMotiveCount : '' ) . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNot = $this->db->query('
            SELECT
                NA.id,
                NA.create_number
            FROM `gaz_nifs_anatomy` AS NA
            WHERE NA.motive_id = 0 ' . $queryStringData . '
            ORDER BY NA.create_number ASC');

            if ($queryNot->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Эрх бүхий байгууллага сонгоогүй шинжилгээ (' . $queryNot->num_rows() . '): ';

                foreach ($queryNot->result() as $rowNot) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'createNumber=' . $rowNot->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNot->create_number . '</a>';
                }
                $htmlData .= '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }

        $query = $this->db->query('
            SELECT
                C.id,
                C.title,
                CC.cat_count,
                IF(CC.cat_count > 0, \'\', \'\') AS cat_count_class
            FROM `gaz_category` AS C
            LEFT JOIN (
                SELECT
                    NA.cat_id,
                    COUNT(NA.cat_id) AS cat_count
                FROM `gaz_nifs_anatomy` AS NA 
                WHERE NA.cat_id != 0 ' . $queryStringData . '
                GROUP BY NA.cat_id
            ) AS CC ON CC.cat_id = C.id
            WHERE C.mod_id = ' . $this->modId);

        if ($query->num_rows() > 0) {

            $i = $sumTotalCatCount = 0;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Гарчиг</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Тоо</th>';
            $htmlData .= '</tr>';
            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $sumTotalCatCount = $sumTotalCatCount + $row->cat_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'catId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count . '</a></td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';

            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="_custom-foot text-right">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot _row-more"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'catId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $categoryCount . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNot = $this->db->query('
            SELECT
                NA.id,
                NA.create_number
            FROM `gaz_nifs_anatomy` AS NA
            WHERE NA.cat_id = 0 ' . $queryStringData . '
            ORDER BY NA.create_number ASC');

            if ($queryNot->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ (' . $queryNot->num_rows() . '): ';

                foreach ($queryNot->result() as $rowNot) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'createNumber=' . $rowNot->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNot->create_number . '</a>';
                }
                $htmlData .= '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }


        //Бүрэлдэхүүнтэй, бүрэлдэхүүнгүй шинжилгээний тайлан - эхлэл
        $queryMixx = $this->db->query('
            SELECT
                MIXX1.count_mixx1,
                IF(MIXX1.count_mixx1 > 0, \'_row-more\', \'\') AS count_mixx1_class,
                MIXX0.count_mixx0,
                IF(MIXX0.count_mixx0 > 0, \'_row-more\', \'\') AS count_mixx0_class
            FROM `gaz_nifs_research_type` AS NRT
            LEFT JOIN (
                SELECT
                    COUNT(NA.is_mixx) AS count_mixx1
                FROM `gaz_nifs_anatomy` AS NA 
                WHERE NA.is_mixx = 1 ' . $queryStringData . '
                GROUP BY NA.is_mixx
            ) AS MIXX1 ON 1 = 1
            LEFT JOIN (
                SELECT
                    COUNT(NA.is_mixx) AS count_mixx0
                FROM `gaz_nifs_anatomy` AS NA 
                WHERE NA.is_mixx = 0 ' . $queryStringData . '
                GROUP BY NA.is_mixx
            ) AS MIXX0 ON 1 = 1
            WHERE NRT.id = 1');

        if ($queryMixx->num_rows() > 0) {

            $i = $sumTotalMixx1Count = $sumTotalMixx0Count = $sumMixxCount = $sumTotalMixxCount = 0;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Гарчиг</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Бүрэлдэхүүнтэй</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Бүрэлдэхүүнгүй</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Тоо</th>';
            $htmlData .= '</tr>';
            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($queryMixx->result() as $key => $row) {

                $sumMixxCount = $row->count_mixx1 + $row->count_mixx0;
                $sumTotalMixx1Count = $sumTotalMixx1Count + $row->count_mixx1;
                $sumTotalMixx0Count = $sumTotalMixx0Count + $row->count_mixx0;
                $sumTotalMixxCount = $sumTotalMixxCount + $sumMixxCount;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>Шинжилгээ</td>';
                $htmlData .= '<td class="text-center ' . $row->count_mixx1_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'isMixx=1&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->count_mixx1 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->count_mixx0_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'isMixx=2&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->count_mixx0 . '</a></td>';
                $htmlData .= '<td class="text-center ' . ($sumMixxCount > 0 ? '_row-more' : '' ) . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'isMixx=0&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $sumMixxCount . '</a></td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'isMixx=1&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalMixx1Count > 0 ? $sumTotalMixx1Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'isMixx=2&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalMixx0Count > 0 ? $sumTotalMixx0Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'isMixx=0&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalMixxCount > 0 ? $sumTotalMixxCount : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $htmlData .= '<br>';
        }
        //Бүрэлдэхүүнтэй, бүрэлдэхүүнгүй шинжилгээний тайлан - төгсгөл
        //Шийдвэрлэх асуудлын тайлан - эхлэл
        $query = $this->db->query('
            SELECT
                NW.id,
                NW.title,
                NFFQ.work_count,
                IF(NFFQ.work_count > 0, \'_row-more\', \'\') AS work_count_class
            FROM `gaz_nifs_work` AS NW
            LEFT JOIN (
                SELECT
                    NA.work_id,
                    COUNT(NA.work_id) AS work_count
                FROM `gaz_nifs_anatomy` AS NA 
                WHERE NA.work_id != 0 ' . $queryStringData . '
                GROUP BY NA.work_id
            ) AS NFFQ ON NW.id = NFFQ.work_id
            WHERE NW.is_active = 1 ORDER BY NW.title ASC');

        if ($query->num_rows() > 0) {

            $i = $sumTotalWorkCount = 0;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Гарчиг</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Тоо</th>';
            $htmlData .= '</tr>';
            $htmlData .= '</thead>';
            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $sumTotalWorkCount = $sumTotalWorkCount + $row->work_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->work_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'workId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->work_count . '</td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';

            $htmlData .= '<tfoot>';

            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'workId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $sumTotalWorkCount . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNot = $this->db->query('
            SELECT
                NA.id,
                NA.create_number
            FROM `gaz_nifs_anatomy` AS NA
            WHERE NA.work_id = 0' . $queryStringData . '
            ORDER BY NA.create_number ASC');

            if ($queryNot->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ (' . $queryNot->num_rows() . '): ';

                foreach ($queryNot->result() as $rowNot) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'createNumber=' . $rowNot->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNot->create_number . '</a>';
                }
                $htmlData .= '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }
        //Шийдвэрлэх асуудлын тайлан - төгсгөл
        //Дүгнэлтээр гарсан тайлан - эхлэл
        $query = $this->db->query('
            SELECT 
                NS.id,
                NS.title,
                NA.solution_count,
                IF(NA.solution_count > 0, \'_row-more\', \'\') AS solution_count_class
            FROM `gaz_nifs_solution` AS NS
            LEFT JOIN (
                SELECT 
                    NA.solution_id,
                    COUNT(NA.solution_id) AS solution_count
                FROM `gaz_nifs_anatomy` AS NA 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NA.solution_id
            ) AS NA ON NA.solution_id = NS.id
            WHERE NS.is_active = 1 AND NS.cat_id = ' . $this->nifsSolutionCatId . ' ORDER BY NS.order_num ASC');

        if ($query->num_rows() > 0) {

            $i = $sumTotalSolutionCount = 0;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Гарчиг</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Тоо</th>';
            $htmlData .= '</tr>';
            $htmlData .= '</thead>';
            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $sumTotalSolutionCount = $sumTotalSolutionCount + $row->solution_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->solution_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->solution_count . '</a></td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSolutionCount > 0 ? $sumTotalSolutionCount : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNot = $this->db->query('
            SELECT
                NA.id,
                NA.create_number
            FROM `gaz_nifs_anatomy` AS NA
            WHERE NA.solution_id = 0' . $queryStringData . '
            ORDER BY NA.create_number ASC');

            if ($queryNot->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ (' . $queryNot->num_rows() . '): ';

                foreach ($queryNot->result() as $rowNot) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'createNumber=' . $rowNot->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNot->create_number . '</a>';
                }

                $htmlData .= '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }
        //Дүгнэлтээр гарсан тайлан - Төгсгөл

        $htmlData .= '</tbody>';
        $htmlData .= '</table>';
        $htmlData .= '</div>';

        return $htmlData;
    }

    public function getReportWeightData_model($param = array()) {

        $queryStringData = $queryStringHrPeopleData = $htmlData = $isAjaxDepartmentUrl = '';

        if ($param['reportIsClose'] == 1) {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NA.end_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NA.end_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NA.end_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NA.end_date)';
            }
        } else {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NA.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NA.in_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NA.in_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NA.out_date)';
            }
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {
            $queryStringData .= ' AND NA.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $query = $this->db->query('
            SELECT 
                NE.expert_id
            FROM `gaz_nifs_expert` AS NE
            INNER JOIN `gaz_nifs_anatomy` AS NA ON NE.mod_id = NA.mod_id AND NE.cont_id = NA.id
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
            $queryStringData .= ' AND NA.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        $htmlData .= '<div class="table-responsive">';
        $htmlData .= '<table class="table _report">';
        $htmlData .= '<thead>';
        $htmlData .= '<tr>';
        $htmlData .= '<th rowspan="2" style="width:30px;">#</th>';
        $htmlData .= '<th rowspan="2">Шинжээч эмч нарын нэрс</th>';
        $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Нийт шинжилгээ</th>';
        $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">ШЭШГ</th>';
        $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Гэмтэл</th>';
        $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Хөдөө</th>';
        $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Хорих</th>';
        $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Бусад</th>';
        $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Хэвийн шинжилгээ</th>';
        $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Хугацаа хэтэрсэн</th>';
        $htmlData .= '</tr>';

        $htmlData .= '<tr>';
        $htmlData .= '<th style="width:80px;" class="text-center">Хийгдэж байгаа</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">Хаагдсан</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">Хийгдэж байгаа</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">Хаагдсан</th>';
        $htmlData .= '</tr>';

        $htmlData .= '</thead>';

        $htmlData .= '<tbody>';

        $query = $this->db->query('
            SELECT 
                HP.id,
                IF(HP.is_active = 1, CONCAT(\'<strong>\',SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname, \'</strong><br><div style="font-size:90%">\', HPP.title, \', \', HPD.title, \'</div>\'), CONCAT(\'<strong>\', HP.fname, \'</strong>\')) AS full_name,
                CRIME_TOTAL.crime_total_count,
                IF(CRIME_TOTAL.crime_total_count > 0, \'_row-more\', \'\') AS crime_total_count_class,
                SHESHX_TOTAL.sheshx_count,
                IF(SHESHX_TOTAL.sheshx_count > 0, \'_row-more\', \'\') AS sheshx_count_class,
                GEMTEL_TOTAL.gemtel_count,
                IF(GEMTEL_TOTAL.gemtel_count > 0, \'_row-more\', \'\') AS gemtel_count_class,
                HUDUU_TOTAL.huduu_count,
                IF(HUDUU_TOTAL.huduu_count > 0, \'_row-more\', \'\') AS huduu_count_class,
                HORIH_TOTAL.horih_count,
                IF(HORIH_TOTAL.horih_count > 0, \'_row-more\', \'\') AS horih_count_class,
                BUSAD_TOTAL.busad_count,
                IF(BUSAD_TOTAL.busad_count > 0, \'_row-more\', \'\') AS busad_count_class,
                NORMAL_HAND.normal_hand_count,
                IF(NORMAL_HAND.normal_hand_count > 0, \'_row-more\', \'\') AS normal_hand_count_class,
                NORMAL_DONE.normal_done_count,
                IF(NORMAL_DONE.normal_done_count > 0, \'_row-more\', \'\') AS normal_done_count_class,
                CRASH_HAND.crash_hand_count,
                IF(CRASH_HAND.crash_hand_count > 0, \'_row-more\', \'\') AS crash_hand_count_class,
                CRASH_DONE.crash_done_count,
                IF(CRASH_DONE.crash_done_count > 0, \'_row-more\', \'\') AS crash_done_count_class
            FROM `gaz_hr_people` AS HP
            INNER JOIN `gaz_hr_people_work` AS HPW ON HP.id = HPW.people_id AND HPW.is_currenty = 1
            INNER JOIN `gaz_hr_people_position` AS HPP ON HPP.id = HP.position_id 
            INNER JOIN `gaz_hr_people_department` AS HPD ON HPD.id = HP.department_id
            LEFT JOIN (
                SELECT 
                    NE.expert_id,
                    COUNT(NE.expert_id) AS crime_total_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_anatomy` AS NA ON NE.cont_id = NA.id AND NE.mod_id = NA.mod_id
                WHERE NE.expert_id != 0 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) CRIME_TOTAL ON CRIME_TOTAL.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NE.expert_id,
                    COUNT(NE.expert_id) AS sheshx_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_anatomy` AS NA ON NE.cont_id = NA.id AND NE.mod_id = NA.mod_id
                WHERE NE.expert_id != 0 AND NA.where_id = 4 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) SHESHX_TOTAL ON SHESHX_TOTAL.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NE.expert_id,
                    COUNT(NE.expert_id) AS gemtel_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_anatomy` AS NA ON NE.cont_id = NA.id AND NE.mod_id = NA.mod_id
                WHERE NE.expert_id != 0 AND NA.where_id = 5 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) GEMTEL_TOTAL ON GEMTEL_TOTAL.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NE.expert_id,
                    COUNT(NE.expert_id) AS huduu_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_anatomy` AS NA ON NE.cont_id = NA.id AND NE.mod_id = NA.mod_id
                WHERE NE.expert_id != 0 AND NA.where_id = 6 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) HUDUU_TOTAL ON HUDUU_TOTAL.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NE.expert_id,
                    COUNT(NE.expert_id) AS horih_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_anatomy` AS NA ON NE.cont_id = NA.id AND NE.mod_id = NA.mod_id
                WHERE NE.expert_id != 0 AND NA.where_id = 7 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) HORIH_TOTAL ON HORIH_TOTAL.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NE.expert_id,
                    COUNT(NE.expert_id) AS busad_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_anatomy` AS NA ON NE.cont_id = NA.id AND NE.mod_id = NA.mod_id
                WHERE NE.expert_id != 0 AND NA.where_id = 8 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) BUSAD_TOTAL ON BUSAD_TOTAL.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NE.expert_id,
                    COUNT(NE.expert_id) AS normal_hand_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_anatomy` AS NA ON NE.cont_id = NA.id AND NE.mod_id = NA.mod_id
                WHERE NE.expert_id != 0 AND NA.solution_id = 0 AND CURDATE() <= DATE(NA.out_date) ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) NORMAL_HAND ON NORMAL_HAND.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NE.expert_id,
                    COUNT(NE.expert_id) AS normal_done_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_anatomy` AS NA ON NE.cont_id = NA.id AND NE.mod_id = NA.mod_id
                WHERE NE.expert_id != 0 AND NA.solution_id != 0 AND DATE(NA.end_date) <= DATE(NA.out_date) ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) NORMAL_DONE ON NORMAL_DONE.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NE.expert_id,
                    COUNT(NE.expert_id) AS crash_hand_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_anatomy` AS NA ON NE.cont_id = NA.id AND NE.mod_id = NA.mod_id
                WHERE NE.expert_id != 0 AND NA.solution_id = 0 AND CURDATE() >= DATE(NA.out_date) ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) CRASH_HAND ON CRASH_HAND.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NE.expert_id,
                    COUNT(NE.expert_id) AS crash_done_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_anatomy` AS NA ON NE.cont_id = NA.id AND NE.mod_id = NA.mod_id
                WHERE NE.expert_id != 0 AND NA.solution_id != 0 AND DATE(NA.end_date) > DATE(NA.out_date) ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) CRASH_DONE ON CRASH_DONE.expert_id = HP.id 
            WHERE HP.id IN(' . $inPeopleId . ') AND CRIME_TOTAL.crime_total_count > 0 
            ORDER BY HP.fname ASC');

        if ($query->num_rows() > 0) {

            $catNum = $sumCrimeTotalCount = $sumSheshxCount = $sumGemtelCount = $sumHuduuCount = $sumHorihCount = $sumBusadCount = $sumNormalHandCount = $sumNormalDoneCount = $sumCrashHandCount = $sumCrashDoneCount = 0;

            foreach ($query->result() as $keyDoctor => $rowDoctor) {
                $catNum++;

                $sumCrimeTotalCount = $sumCrimeTotalCount + $rowDoctor->crime_total_count;
                $sumSheshxCount = $sumSheshxCount + $rowDoctor->sheshx_count;
                $sumGemtelCount = $sumGemtelCount + $rowDoctor->gemtel_count;
                $sumHuduuCount = $sumHuduuCount + $rowDoctor->huduu_count;
                $sumHorihCount = $sumHorihCount + $rowDoctor->horih_count;
                $sumBusadCount = $sumBusadCount + $rowDoctor->busad_count;
                $sumNormalHandCount = $sumNormalHandCount + $rowDoctor->normal_hand_count;
                $sumNormalDoneCount = $sumNormalDoneCount + $rowDoctor->normal_done_count;
                $sumCrashHandCount = $sumCrashHandCount + $rowDoctor->crash_hand_count;
                $sumCrashDoneCount = $sumCrashDoneCount + $rowDoctor->crash_done_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $catNum . '</td>';
                $htmlData .= '<td>' . $rowDoctor->full_name . '</td>';
                $htmlData .= '<td class="text-center ' . $rowDoctor->crime_total_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'expertId=' . $rowDoctor->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $rowDoctor->crime_total_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $rowDoctor->sheshx_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'expertId=' . $rowDoctor->id . '&whereId=4&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $rowDoctor->sheshx_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $rowDoctor->gemtel_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'expertId=' . $rowDoctor->id . '&whereId=5&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $rowDoctor->gemtel_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $rowDoctor->huduu_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'expertId=' . $rowDoctor->id . '&whereId=6&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $rowDoctor->huduu_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $rowDoctor->horih_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'expertId=' . $rowDoctor->id . '&whereId=7&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $rowDoctor->horih_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $rowDoctor->busad_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'expertId=' . $rowDoctor->id . '&whereId=8&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $rowDoctor->busad_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $rowDoctor->normal_hand_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'expertId=' . $rowDoctor->id . '&statusId=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $rowDoctor->normal_hand_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $rowDoctor->normal_done_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'expertId=' . $rowDoctor->id . '&statusId=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $rowDoctor->normal_done_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $rowDoctor->crash_hand_count_class . '"><a href="javascript:;" class="_crime-crash-done" onclick="_initNifsAnatomy({page: 0, searchQuery: \'expertId=' . $rowDoctor->id . '&statusId=4&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $rowDoctor->crash_hand_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $rowDoctor->crash_done_count_class . '"><a href="javascript:;" class="_crime-crash-hand" onclick="_initNifsAnatomy({page: 0, searchQuery: \'expertId=' . $rowDoctor->id . '&statusId=3&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $rowDoctor->crash_done_count . '</a></td>';
                $htmlData .= '</tr>';
            }
        }

        $htmlData .= '</tbody>';
        $htmlData .= '<tfoot>';
        $htmlData .= '<tr>';
        $htmlData .= '<td colspan="2" class="text-right">Нийт</td>';
        $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumCrimeTotalCount > 0 ? $sumCrimeTotalCount : '') . '</a></td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($sumSheshxCount > 0 ? $sumSheshxCount : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($sumGemtelCount > 0 ? $sumGemtelCount : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($sumHuduuCount > 0 ? $sumHuduuCount : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($sumHorihCount > 0 ? $sumHorihCount : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($sumBusadCount > 0 ? $sumBusadCount : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($sumNormalHandCount > 0 ? $sumNormalHandCount : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($sumNormalDoneCount > 0 ? $sumNormalDoneCount : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot _crime-crash-done">' . ($sumCrashHandCount > 0 ? $sumCrashHandCount : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot _crime-crash-hand">' . ($sumCrashDoneCount > 0 ? $sumCrashDoneCount : '') . '</td>';
        $htmlData .= '</tr>';
        $htmlData .= '</tfoot>';
        $htmlData .= '</table>';

        $queryNot = $this->db->query('
            SELECT 
                NE.expert_id,
                NA.create_number
            FROM `gaz_nifs_expert` AS NE 
            INNER JOIN `gaz_nifs_anatomy` AS NA ON NE.mod_id = NA.mod_id AND NE.cont_id = NA.id 
            WHERE NE.expert_id = 0 ' . $queryStringData . '
            ORDER BY NA.create_number ASC');

        if ($queryNot->num_rows() > 0) {
            $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ (' . $queryNot->num_rows() . '): ';

            foreach ($queryNot->result() as $rowNot) {
                $htmlData .= '<a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'createNumber=' . $rowNot->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNot->create_number . '</a>';
            }

            $htmlData .= '</div>';
        } else {
            $htmlData .= '<br>';
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
                FROM `gaz_nifs_anatomy` AS NC
                WHERE NC.partner_id != 0 AND NC.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NC.partner_id
            ) AS YEAR_PARTNER_COUNT ON YEAR_PARTNER_COUNT.partner_id = P.id
            LEFT JOIN (
                SELECT
                    NC.partner_id,
                    COUNT(NC.partner_id) AS date_partner_count
                FROM `gaz_nifs_anatomy` AS NC
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
                $htmlData .= '<td class="text-center ' . $row->year_partner_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . $row->year_partner_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->date_partner_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->date_partner_count . '</a></td>';
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
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'partnerId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalYearPartnerCount > 0 ? $sumTotalYearPartnerCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'partnerId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalDatePartnerCount > 0 ? $sumTotalDatePartnerCount : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';


            $queryNotPartner = $this->db->query('
            SELECT 
                NC.create_number
            FROM `gaz_nifs_anatomy` AS NC 
            WHERE NC.partner_id = 0 ' . $queryStringData . '
            ORDER BY NC.create_number ASC');

            if ($queryNotPartner->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ томилсон байгууллага сонгоогүй шинжилгээ (' . $queryNotPartner->num_rows() . '): ';

                foreach ($queryNotPartner->result() as $rowNotPartner) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'createNumber=' . $rowNotPartner->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNotPartner->create_number . '</a>';
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
                FROM `gaz_nifs_anatomy` AS NC
                WHERE NC.partner_id != 0 AND NC.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NC.partner_id
            ) AS YEAR_PARTNER_COUNT ON YEAR_PARTNER_COUNT.partner_id = P.id
            LEFT JOIN (
                SELECT
                    NC.partner_id,
                    COUNT(NC.partner_id) AS date_partner_count
                FROM `gaz_nifs_anatomy` AS NC
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
                $htmlData .= '<td class="text-center ' . $row->year_partner_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . $row->year_partner_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->date_partner_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->date_partner_count . '</a></td>';
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
        } else if ($auth->our->read and ! $auth->your->read) {

            $queryString .= ' AND NA.created_user_id = ' . $this->session->adminUserId;
        } else {

            $queryString .= ' AND NA.created_user_id = -1';
        }

        if ($this->session->adminDepartmentRoleId == 2) {

            $queryString .= ' AND NA.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        } else {

            if ($param['departmentId'] != 0) {

                $queryString .= ' AND NA.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            } else {

                $queryString .= ' AND NA.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
            }
        }

        if ($param['createNumber'] != '') {
            $queryString .= ' AND NA.create_number = ' . $param['createNumber'];
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $queryString .= ' AND LOWER(NA.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $queryString .= ' AND LOWER(NA.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Регистрийн дугаар
                        $queryString .= ' AND LOWER(NA.register) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Хаяг
                        $queryString .= ' AND LOWER(NA.address) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Албан хаагч
                        $queryString .= ' AND LOWER(NA.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 6: {   //Болсон хэргийн товч
                        $queryString .= ' AND LOWER(NA.short_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 7: {   //Тайлбар
                        $queryString .= ' AND LOWER(NA.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 8: {   //Дүгнэлтийн утга
                        $queryString .= ' AND LOWER(NA.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' AND (
                        LOWER(NA.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NA.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NA.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NA.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NA.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NA.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NA.short_value) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                        break;
                    }
            }
        }

        if ($param['workId'] != 0) {
            $queryString .= ' AND NA.work_id = ' . $param['workId'];
        }

        if ($param['partnerId'] != 0) {
            $queryString .= ' AND NA.partner_id IN(' . $this->partner->getChildPartners_model(array($param['partnerId'])) . ')';
        }

        if ($param['shortValueId'] != 0) {
            $queryString .= ' AND NA.short_value_id = ' . $param['shortValueId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NA.in_date) AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NA.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NA.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NA.out_date)';
        }


        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NA.protocol_in_date) AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NA.protocol_out_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NA.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NA.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NA.end_date) AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NA.end_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NA.end_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NA.end_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND NA.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['isAgeInfinitive'] == 1) {

            $queryString .= ' AND NA.is_age_infinitive = ' . $param['isAgeInfinitive'];
        } else {


            if ($param['age1'] != '' and $param['age2'] != '') {
                $queryString .= ' AND (NA.age >= ' . $param['age1'] . ' AND NA.age <= ' . $param['age2'] . ') AND NA.is_age_infinitive = 0';
            } elseif ($param['age1'] != '' and $param['age2'] == '') {
                $queryString .= ' AND \'' . $param['age1'] . '\' >= NA.age AND NA.is_age_infinitive = 0';
            } elseif ($param['age1'] == '' and $param['age2'] != '') {
                $queryString .= ' AND \'' . $param['age2'] . '\' <= NA.age AND NA.is_age_infinitive = 0';
            }
        }

        if ($param['whereId'] != 0) {
            $queryString .= ' AND NA.where_id = ' . $param['whereId'];
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND NA.cat_id = ' . $param['catId'];
        }

        if ($param['motiveId'] != 0) {

            $queryString .= ' AND NA.motive_id = ' . $param['motiveId'];
        }

        if ($param['isMixx'] == 1) {
            $queryString .= ' AND NA.is_mixx = 1';
        }

        if ($param['isMixx'] == 2) {
            $queryString .= ' AND NA.is_mixx = 0';
        }

        if ($param['closeDate'] != '') {

            $queryString .= ' AND DATE(NA.close_date) = DATE(\'' . $param['closeDate'] . '\')';
        }

        if ($param['solutionId'] > 0) {

            $queryString .= ' AND NA.solution_id = ' . $param['solutionId'];
        }

        if ($param['closeTypeId'] > 0) {

            $queryString .= ' AND NA.close_type_id = ' . $param['closeTypeId'];
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NA.solution_id != 0 AND NA.end_date <= NA.out_date';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NA.solution_id = 0 AND NOW() <= NA.out_date';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)   
            $queryString .= ' AND NA.solution_id != 0 AND NA.end_date > NA.out_date';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NA.solution_id = 0 AND NOW() > NA.out_date';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND NA.protocol_out_date < NA.in_date';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NA.solution_id = 0';
        }

        if ($param['closeDescription'] != '') {

            $queryString .= ' AND (LOWER(NA.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        if ($param['sex'] == 1) {

            $queryString .= ' AND NA.sex = 1';
        } else if ($param['sex'] == 2) {

            $queryString .= ' AND NA.sex = 0';
        }

        if ($param['expertId'] != 0) {

            $query = $this->db->query('
                SELECT
                    NA.id,
                    NA.mod_id,
                    NA.cat_id,
                    NA.created_user_id,
                    NA.create_number,
                    IF(NA.is_mixx = 1, "Бүрэлдэхүүнтэй", "Бүрэлдэхүүнгүй") AS is_mixx,
                    DATE(NA.in_date) AS in_date,
                    DATE(NA.out_date) AS out_date,
                    DATE(NA.begin_date) AS begin_date,
                    DATE(NA.end_date) AS end_date,
                    NA.protocol_number,
                    DATE(NA.protocol_in_date) AS protocol_in_date,
                    DATE(NA.protocol_out_date) AS protocol_out_date,
                    NA.lname,
                    NA.fname,
                    (CASE 
                            WHEN (NA.is_age_infinitive = 1) THEN \'Нас тодорхойгүй\'
                            WHEN (NA.is_age_infinitive = 0 AND NA.age < 1) THEN CONCAT(CONVERT(SUBSTRING(NA.age, 3, 2),UNSIGNED INTEGER), " сартай")
                            ELSE IF(NA.is_age_infinitive = 0 AND NA.age >= 1, CONCAT(CONVERT(NA.age,UNSIGNED INTEGER), " настай"), "")
                    END) AS age,
                    (IF(NA.sex = 1, \'Эр\', \'Эм\')) AS sex,
                    (IF(NA.register != \'\', CONCAT(NA.register, \'\'), \'\')) AS register,
                    NA.address,
                    NA.work_id,
                    NA.partner_id,
                    NA.short_value,
                    NA.expert,
                    NA.description,
                    IF(NA.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:rgba(255,255,255,0.8);\') AS expert_status,
                    NA.expert_name,
                    NA.where_id,
                    IF(NA.payment = 1, \'Төлбөр төлсөн\', \'Төлбөр төлөөгүй\') AS payment,
                    NA.solution_id,
                    NA.close_description,
                    DATE(NA.end_date) AS end_date,
                    (CASE 
                            WHEN (NA.solution_id != 0 AND NA.end_date > NA.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                            WHEN NA.solution_id = 0 AND NOW() > NA.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                            ELSE \'background-color: transparent;\'
                    END) AS row_status,
                    (CASE 
			WHEN (NSD.id != \'\' AND NSD.solution_id != \'\') THEN \'Илгээсэн, хариу гарсан\'
			WHEN NSD.id != \'\' AND NSD.solution_id = \'\' THEN \'Илгээсэн\'
			ELSE \'\'
                    END) AS send_document,
                    NSD.id AS send_document_id,
                    NA.param
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_anatomy` AS NA ON NE.cont_id = NA.id AND NE.mod_id = NA.mod_id
                LEFT JOIN `gaz_nifs_send_doc` AS NSD ON NA.mod_id = NSD.mod_id AND NA.id = NSD.cont_id
                WHERE NE.expert_id = ' . $param['expertId'] . $queryString . '
                ORDER BY NA.create_number DESC');
        } else {
            $query = $this->db->query('
                SELECT 
                    NA.id,
                    NA.mod_id,
                    NA.cat_id,
                    NA.created_user_id,
                    NA.create_number,
                    IF(NA.is_mixx = 1, "Бүрэлдэхүүнтэй", "Бүрэлдэхүүнгүй") AS is_mixx,
                    DATE(NA.in_date) AS in_date,
                    DATE(NA.out_date) AS out_date,
                    DATE(NA.begin_date) AS begin_date,
                    DATE(NA.end_date) AS end_date,
                    NA.protocol_number,
                    DATE(NA.protocol_in_date) AS protocol_in_date,
                    DATE(NA.protocol_out_date) AS protocol_out_date,
                    NA.lname,
                    NA.fname,
                    (CASE 
                            WHEN (NA.is_age_infinitive = 1) THEN \'Нас тодорхойгүй\'
                            WHEN (NA.is_age_infinitive = 0 AND NA.age < 1) THEN CONCAT(CONVERT(SUBSTRING(NA.age, 3, 2),UNSIGNED INTEGER), " сартай")
                            ELSE IF(NA.is_age_infinitive = 0 AND NA.age >= 1, CONCAT(CONVERT(NA.age,UNSIGNED INTEGER), " настай"), "")
                    END) AS age,
                    (IF(NA.sex = 1, \'Эр\', \'Эм\')) AS sex,
                    (IF(NA.register != \'\', CONCAT(NA.register, \'\'), \'\')) AS register,
                    NA.address,
                    NA.work_id,
                    NA.partner_id,
                    NA.short_value,
                    NA.expert,
                    NA.description,
                    IF(NA.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:rgba(255,255,255,0.8);\') AS expert_status,
                    NA.expert_name,
                    NA.where_id,
                    IF(NA.payment = 1, \'Төлбөр төлсөн\', \'Төлбөр төлөөгүй\') AS payment,
                    NA.solution_id,
                    NA.close_description,
                    DATE(NA.end_date) AS end_date,
                    (CASE 
                            WHEN (NA.solution_id != 0 AND NA.end_date > NA.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                            WHEN NA.solution_id = 0 AND NOW() > NA.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                            ELSE \'background-color: transparent;\'
                    END) AS row_status,
                    (CASE 
			WHEN (NSD.id != \'\' AND NSD.solution_id != \'\') THEN \'Илгээсэн, хариу гарсан\'
			WHEN NSD.id != \'\' AND NSD.solution_id = \'\' THEN \'Илгээсэн\'
			ELSE \'\'
                    END) AS send_document,
                    NSD.id AS send_document_id,
                    NA.param
                FROM `gaz_nifs_anatomy` AS NA 
                LEFT JOIN `gaz_nifs_send_doc` AS NSD ON NA.mod_id = NSD.mod_id AND NA.id = NSD.cont_id
                WHERE 1 = 1 ' . $queryString . '
                ORDER BY NA.create_number DESC');
        }

        if ($query->num_rows() > 0) {

            return $query->result();
        }

        return false;
    }

    public function tempUpdateData_model($param = array()) {

        $this->query = $this->db->query('
            SELECT 
                NA.id,
                NA.mod_id,
                NA.expert_id
            FROM `gaz_nifs_anatomy` AS NA');


        foreach ($this->query->result() as $tempRow) {

            if ($tempRow->expert_id != '0') {
                return self::updateExpertParam_model(array('modId' => $tempRow->mod_id, 'contId' => $tempRow->id, 'expertId' => array($tempRow->expert_id)));
            }
        }
    }

    public function dataUpdate_model1($param = array()) {

        $query = $this->db->query('
                SELECT 
                    NA.id, 
                    NA.motive_id, 
                    NA.work_id, 
                    NA.type_id, 
                    NA.partner_id,
                    NA.where_id,
                    NA.solution_id,
                    NA.close_type_id,
                    NA.param
                FROM `gaz_nifs_anatomy` AS NA');

        foreach ($query->result() as $key => $row) {

            $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $row->motive_id));
            $this->nifsWorkData = $this->nifsWork->getData_model(array('selectedId' => $row->work_id));
            $this->nifsTypeData = $this->nifsCrimeType->getData_model(array('selectedId' => $row->type_id));
            $this->partnerData = $this->partner->getData_model(array('selectedId' => $row->partner_id));
            $this->nifsWhereData = $this->nifsWhere->getData_model(array('selectedId' => $row->where_id));
            $this->nifsSolutionData = $this->nifsSolution->getData_model(array('selectedId' => $row->solution_id));
            $this->nifsCloseTypeData = $this->nifsCloseType->getData_model(array('selectedId' => $row->close_type_id));

            $data = array(
                'param' => json_encode(
                        array(
                            'motive' => ($this->nifsMotiveData != false ? $this->nifsMotiveData->title : ''),
                            'work' => ($this->nifsWorkData != false ? $this->nifsWorkData->title : ''),
                            'type' => ($this->nifsTypeData != false ? $this->nifsTypeData->title : ''),
                            'partner' => ($this->partnerData != false ? $this->partnerData->title : ''),
                            'where' => ($this->nifsWhereData != false ? $this->nifsWhereData->title : ''),
                            'solution' => ($this->nifsSolutionData != false ? $this->nifsSolutionData->title : ''),
                            'closeType' => ($this->nifsCloseTypeData != false ? $this->nifsCloseTypeData->title : ''))));

            $this->db->where('id', $row->id);
            if ($this->db->update('gaz_nifs_anatomy', $data)) {

                echo '<pre>';
                var_dump(json_decode($data['param']));
                echo '</pre>';
            }
        }
    }

    public function dataUpdate_model($param = array()) {

        $query = $this->db->query('
                SELECT 
                    NA.id,
                    NSD_TYPE_11.id AS send_document_chemical_id,
                    NSD_TYPE_11.close_type_id AS send_document_chemical_close_type_id,
                    NSD_TYPE_8.id AS send_document_biology_id,
                    NSD_TYPE_8.close_type_id AS send_document_biology_close_type_id,
                    NSD_TYPE_10.id AS send_document_bakterlogy_id,
                    NSD_TYPE_10.close_type_id AS send_document_bakterlogy_close_type_id,
                    NA.param
                FROM `gaz_nifs_anatomy` AS NA 
                LEFT JOIN `gaz_nifs_send_doc` AS NSD_TYPE_11 ON NA.mod_id = NSD_TYPE_11.module_id AND NA.id = NSD_TYPE_11.cont_id AND NSD_TYPE_11.type_id = 11
                LEFT JOIN `gaz_nifs_send_doc` AS NSD_TYPE_8 ON NA.mod_id = NSD_TYPE_8.module_id AND NA.id = NSD_TYPE_8.cont_id AND NSD_TYPE_8.type_id = 8
                LEFT JOIN `gaz_nifs_send_doc` AS NSD_TYPE_10 ON NA.mod_id = NSD_TYPE_10.module_id AND NA.id = NSD_TYPE_10.cont_id AND NSD_TYPE_10.type_id = 10');

        foreach ($query->result() as $key => $row) {

            $data = array(
                'send_document_chemical_id' => $row->send_document_chemical_id,
                'send_document_chemical_close_type_id' => $row->send_document_chemical_close_type_id,
                'send_document_biology_id' => $row->send_document_biology_id,
                'send_document_biology_close_type_id' => $row->send_document_biology_close_type_id,
                'send_document_bakterlogy_id' => $row->send_document_bakterlogy_id,
                'send_document_bakterlogy_close_type_id' => $row->send_document_bakterlogy_close_type_id);
            

            $this->db->where('id', $row->id);

            $this->db->update($this->db->dbprefix . 'nifs_anatomy', $data);
        }
    }

}
