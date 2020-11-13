<?php

class SnifsExtra_model extends CI_Model {

    private static $list = array();

    function __construct() {

        parent::__construct();

        $this->load->model('Scategory_model', 'category');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Suser_model', 'user');
        $this->load->model('SnifsMotive_model', 'nifsMotive');
        $this->load->model('SnifsResearchType_model', 'nifsResearchType');
        $this->load->model('SnifsCrimeType_model', 'nifsCrimeType');
        $this->load->model('SnifsSolution_model', 'nifsSolution');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('SnifsQuestion_model', 'nifsQuestion');
        $this->load->model('SnifsStatus_model', 'nifsStatus');
        $this->load->model('SnifsCloseType_model', 'nifsCloseType');

        $this->perPage = 2;
        $this->hrPeopleExpertPositionId = '5,6,7,10';
        $this->hrPeopleExpertDepartmentId = '5';

        $this->hrPeopleDoctorExpertPositionId = '5,6,7,10';
        $this->hrPeopleDoctorExpertDepartmentId = '4,18';

        $this->nifsCrimeTypeId = 354;
        $this->nifsQuestionCatId = 372;
        $this->nifsSolutionCatId = 360;
        $this->nifsCloseTypeCatId = 366;
        $this->nifsResearchTypeCatId = 381;
        $this->nifsMotiveCatId = 387;
        $this->doctorDepartmentId = '4,18'; //шүүх эмнэлэг, орон нутгийн шүүхийн эмнэлэгийн эмч нар

        $this->modId = 55;
        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->isActiveDepartment = 'is_active_nifs_extra';
        $this->reportDefaultDayInterval = 7;

        if ($this->nifsDepartmentId == 7) {
            $this->nifsDepartmentId = 5;
        }
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => $this->modId,
            'cat_id' => 321,
            'is_mixx' => 0,
            'research_type_id' => 5,
            'motive_id' => 14,
            'create_number' => getCreateNumber(array('createNumber' => 0, 'table' => 'nifs_extra', 'departmentId' => $this->nifsDepartmentId)),
            'in_date' => date('Y-m-d'),
            'out_date' => date('Y-m-d'),
            'fname' => '',
            'lname' => '',
            'age' => '',
            'is_age_infinitive' => 0,
            'sex' => 1,
            'partner_id' => 0,
            'agent_name' => '',
            'description' => '',
            'type_id' => 1,
            'pre_create_number' => '',
            'crime_value' => '',
            'object' => '',
            'object_count' => 0,
            'question_id' => 0,
            'question' => '',
            'expert_doctor_id' => 0,
            'expert' => '',
            'weight' => '',
            'protocol_number' => '',
            'protocol_in_date' => '',
            'protocol_out_date' => '',
            'department_id' => 0,
            'param' => '',
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s'),
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'order_num' => 0,
            'is_active' => 1,
            'year' => 0,
            'close_date' => date('Y-m-d H:i:s'),
            'close_description' => '',
            'close_type_id' => 0,
            'solution_id' => 0,
            'extra_expert_value' => '',
            'register' => '',
            'payment' => 0,
            'payment_description' => '')));
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cat_id,
                is_mixx,
                research_type_id,
                motive_id,
                create_number,
                DATE(in_date) AS in_date,
                DATE(out_date) AS out_date,
                fname,
                lname,
                IF(is_age_infinitive = 1, \'\', age) AS age,
                is_age_infinitive,
                sex,
                partner_id,
                agent_name,
                description,
                type_id,
                protocol_number,
                crime_value,
                object,
                object_count,
                question_id,
                question,
                expert_doctor_id,
                expert,
                weight,
                IF(DATE(protocol_in_date) = \'0000-00-00\', \'\', DATE(protocol_in_date)) AS protocol_in_date,
                IF(DATE(protocol_out_date) = \'0000-00-00\', \'\', DATE(protocol_out_date)) AS protocol_out_date,
                department_id,
                param,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id,
                order_num,
                is_active,
                year,
                close_date,
                close_description,
                close_type_id,
                pre_create_number,
                solution_id,
                extra_expert_value,
                register,
                payment,
                payment_description
            FROM `' . $this->db->dbprefix . 'nifs_extra`
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
        } else if ($auth->our->read and !$auth->your->read) {
            $queryString .= ' AND NEX.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NEX.created_user_id = -1';
        }

        if ($param['selectedId'] != 0) {

            $queryString .= ' AND NEX.id = ' . $param['selectedId'];
        }

        if ($param['departmentId'] != 0) {

            $queryString .= ' AND NEX.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            
        } else {

            $queryString .= ' AND NEX.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        }

        if ($param['createNumber'] != 0) {
            $queryString .= ' AND NEX.create_number = ' . $param['createNumber'];
        }

        if ($param['protocolNumber'] != 0) {
            $queryString .= ' AND NEX.protocol_number = ' . $param['protocolNumber'];
        }

        if ($param['researchTypeId'] != 0) {
            $queryString .= ' AND NEX.research_type_id = ' . $param['researchTypeId'];
        } else if ($param['researchTypeId'] == 'all') {
            $queryString .= ' AND NEX.research_type_id != 0';
        }

        if ($param['isMixx'] == 1) {
            $queryString .= ' AND NEX.is_mixx = 1';
        }

        if ($param['isMixx'] == 2) {
            $queryString .= ' AND NEX.is_mixx = 0';
        }

        if ($param['motiveId'] != 0) {
            $queryString .= ' AND NEX.motive_id = ' . $param['motiveId'];
        } else if ($param['motiveId'] == 'all') {
            $queryString .= ' AND NEX.motive_id != 0';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NEX.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NEX.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NEX.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . ')\' >= DATE(NEX.out_date)';
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NEX.protocol_in_date) AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NEX.protocol_out_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NEX.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NEX.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . ')\' <= DATE(NEX.close_date) AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NEX.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NEX.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NEX.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND NEX.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND NEX.cat_id = ' . $param['catId'];
        } else if ($param['catId'] == 'all') {
            $queryString .= ' AND NEX.cat_id != 0';
        }

        /*
         * ӨМНӨХ ХЭРГИЙН ДУГААРЫГ ХАЙХ ХЭСЭГ ЭНД ОРЖ ИРНЭ. ГЭХДНЭЭ ӨМНӨХ ХЭРЭГТЭЙ ХОЛБООТОЙ ХЭСГИЙГ БҮХ БҮРТГЭЛИЙН ХУВЬД БАГЦААР НЬ НЭГ АЯТАЙХАН ШИЙДЭЛ ОЛЖ ХИЙХ ХЭРЭГТЭЙ БАЙГАА
         */

        if ($param['isAgeInfinitive'] == 1) {

            $queryString .= ' AND NEX.is_age_infinitive = ' . $param['isAgeInfinitive'];
        } else {

            if ($param['age1'] != '' and $param['age2'] != '') {

                $queryString .= ' AND (NEX.age >= ' . floatval($param['age1']) . ' AND NEX.age <= ' . floatval($param['age2']) . ') AND NEX.is_age_infinitive = 0';
            } else if ($param['age1'] != '' and $param['age2'] == '') {

                $queryString .= ' AND \'' . floatval($param['age1']) . '\' >= NEX.age AND K.is_age_infinitive = 0';
            } elseif ($param['age1'] == '' and $param['age2'] != '') {

                $queryString .= ' AND \'' . floatval($param['age2']) . '\' <= NEX.age AND K.is_age_infinitive = 0';
            }
        }

        if ($param['questionId'] != 0) {
            $queryString .= ' AND NEX.question_id = ' . $param['questionId'];
        } else if ($param['questionId'] == 'all') {
            $queryString .= ' AND NEX.question_id != 0';
        }

        if ($param['partnerId'] != 0) {
            $queryString .= ' AND NEX.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
        } else if ($param['partnerId'] == 'all') {
            $queryString .= ' AND NEX.partner_id != 0';
        }

        if ($param['expertDoctorId'] != 0) {
            $queryString .= ' AND NEX.expert_doctor_id = ' . $param['expertDoctorId'];
        } else if ($param['expertDoctorId'] == 'all') {
            $queryString .= ' AND NEX.expert_doctor_id != 0';
        }

        if ($param['protocolNumber'] != 0) {
            $queryString .= ' AND NEX.protocol_number = ' . $param['protocolNumber'];
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $queryString .= ' AND LOWER(NEX.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $queryString .= ' AND LOWER(NEX.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Албан тушаалтны нэр
                        $queryString .= ' AND LOWER(NEX.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Тайлбар
                        $queryString .= ' AND LOWER(NEX.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Регистрийн дугаар
                        $queryString .= ' AND LOWER(NEX.register) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 6: {   //Хэргийн утга
                        $queryString .= ' AND LOWER(NEX.crime_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 7: {   //Нэмэлт асуулт
                        $queryString .= ' AND LOWER(NEX.question) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 8: {   //Дүгнэлтийн утга
                        $queryString .= ' AND LOWER(NEX.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' AND (
                        LOWER(NEX.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NEX.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NEX.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NEX.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NEX.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NEX.crime_value) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NEX.question) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NEX.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;
            }
        }

        if ($param['solutionId'] != 0) {
            $queryString .= ' AND NEX.solution_id = ' . $param['solutionId'];
        } else if ($param['solutionId'] == 'all') {
            $queryString .= ' AND NEX.solution_id != 0';
        }

        if ($param['weight'] != 0) {
            $queryString .= ' AND NEX.weight = ' . $param['weight'];
        }

        if ($param['typeId'] != 0) {
            $queryString .= ' AND NEX.type_id = ' . $param['typeId'];
        } else if ($param['typeId'] == 'all') {
            $queryString .= ' AND NEX.type_id != 0';
        }

        if ($param['closeTypeId'] > 0) {

            $queryString .= ' AND NEX.close_type_id = ' . $param['closeTypeId'];
        } else if ($param['closeTypeId'] == 'all') {

            $queryString .= ' AND NEX.close_type_id != 0';
        }

        if ($param['sex'] == 1) {
            $queryString .= ' AND NEX.sex = 1';
        }

        if ($param['sex'] == 2) {
            $queryString .= ' AND NEX.sex = 0';
        }

        if ($param['payment'] == 1) {
            $queryString .= ' AND NEX.payment = 1';
        }

        if ($param['payment'] == 2) {
            $queryString .= ' AND NEX.payment = 0';
        }

        if ($param['payment'] == 3) {
            $queryString .= ' AND NEX.payment = 2';
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NEX.solution_id != 0 AND DATE(NEX.close_date) <= DATE(NEX.out_date)';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NEX.solution_id = 0 AND CURDATE() <= DATE(NEX.out_date)';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NEX.solution_id != 0 AND DATE(NEX.close_date) > DATE(NEX.out_date)';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NEX.solution_id = 0 AND CURDATE() >= DATE(NEX.out_date)';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND DATE(NEX.protocol_out_date) < DATE(NEX.in_date)';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NEX.solution_id = 0';
        }

        if ($param['closeDescription'] != '') {

            $queryString .= ' AND (LOWER(NEX.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        if ($param['expertId'] != 0) {
            $query = $this->db->query('
                SELECT 
                    NE.expert_id 
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_extra` AS NEX ON NE.cont_id = NEX.id AND NE.mod_id = NEX.mod_id
                WHERE NE.expert_id = ' . $param['expertId'] . $queryString);
        } else {
            $query = $this->db->query('
                SELECT 
                    NEX.id
                FROM `gaz_nifs_extra` AS NEX
                WHERE 1 = 1 ' . $queryString . '
                GROUP BY NEX.id');
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
            $queryString .= ' AND NEX.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NEX.created_user_id = -1';
        }

        if ($param['selectedId'] != 0) {

            $queryString .= ' AND NEX.id = ' . $param['selectedId'];
        }

        if ($param['departmentId'] != 0) {

            $queryString .= ' AND NEX.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
        } else {

            $queryString .= ' AND NEX.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        }

        if ($param['createNumber'] != 0) {
            $queryString .= ' AND NEX.create_number = ' . $param['createNumber'];
        }

        if ($param['protocolNumber'] != 0) {
            $queryString .= ' AND NEX.protocol_number = ' . $param['protocolNumber'];
        }

        if ($param['researchTypeId'] != 0) {
            $queryString .= ' AND NEX.research_type_id = ' . $param['researchTypeId'];
        } else if ($param['researchTypeId'] == 'all') {
            $queryString .= ' AND NEX.research_type_id != 0';
        }

        if ($param['isMixx'] == 1) {
            $queryString .= ' AND NEX.is_mixx = 1';
        }

        if ($param['isMixx'] == 2) {
            $queryString .= ' AND NEX.is_mixx = 0';
        }

        if ($param['motiveId'] != 0) {
            $queryString .= ' AND NEX.motive_id = ' . $param['motiveId'];
        } else if ($param['motiveId'] == 'all') {
            $queryString .= ' AND NEX.motive_id != 0';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NEX.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NEX.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NEX.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . ')\' >= DATE(NEX.out_date)';
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NEX.protocol_in_date) AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NEX.protocol_out_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NEX.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NEX.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . ')\' <= DATE(NEX.close_date) AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NEX.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NEX.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NEX.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND NEX.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND NEX.cat_id = ' . $param['catId'];
        } else if ($param['catId'] == 'all') {
            $queryString .= ' AND NEX.cat_id != 0';
        }

        /*
         * ӨМНӨХ ХЭРГИЙН ДУГААРЫГ ХАЙХ ХЭСЭГ ЭНД ОРЖ ИРНЭ. ГЭХДНЭЭ ӨМНӨХ ХЭРЭГТЭЙ ХОЛБООТОЙ ХЭСГИЙГ БҮХ БҮРТГЭЛИЙН ХУВЬД БАГЦААР НЬ НЭГ АЯТАЙХАН ШИЙДЭЛ ОЛЖ ХИЙХ ХЭРЭГТЭЙ БАЙГАА
         */

        if ($param['isAgeInfinitive'] == 1) {

            $queryString .= ' AND NEX.is_age_infinitive = ' . $param['isAgeInfinitive'];
        } else {

            if ($param['age1'] != '' and $param['age2'] != '') {

                $queryString .= ' AND (NEX.age >= ' . floatval($param['age1']) . ' AND NEX.age <= ' . floatval($param['age2']) . ') AND NEX.is_age_infinitive = 0';
            } else if ($param['age1'] != '' and $param['age2'] == '') {

                $queryString .= ' AND \'' . floatval($param['age1']) . '\' >= NEX.age AND K.is_age_infinitive = 0';
            } elseif ($param['age1'] == '' and $param['age2'] != '') {

                $queryString .= ' AND \'' . floatval($param['age2']) . '\' <= NEX.age AND K.is_age_infinitive = 0';
            }
        }

        if ($param['questionId'] != 0) {
            $queryString .= ' AND NEX.question_id = ' . $param['questionId'];
        } else if ($param['questionId'] == 'all') {
            $queryString .= ' AND NEX.question_id != 0';
        }

        if ($param['partnerId'] != 0) {
            $queryString .= ' AND NEX.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
        } else if ($param['partnerId'] == 'all') {
            $queryString .= ' AND NEX.partner_id != 0';
        }

        if ($param['expertDoctorId'] != 0) {
            $queryString .= ' AND NEX.expert_doctor_id = ' . $param['expertDoctorId'];
        } else if ($param['expertDoctorId'] == 'all') {
            $queryString .= ' AND NEX.expert_doctor_id != 0';
        }

        if ($param['protocolNumber'] != 0) {
            $queryString .= ' AND NEX.protocol_number = ' . $param['protocolNumber'];
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $queryString .= ' AND LOWER(NEX.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $queryString .= ' AND LOWER(NEX.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Албан тушаалтны нэр
                        $queryString .= ' AND LOWER(NEX.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Тайлбар
                        $queryString .= ' AND LOWER(NEX.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Регистрийн дугаар
                        $queryString .= ' AND LOWER(NEX.register) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 6: {   //Хэргийн утга
                        $queryString .= ' AND LOWER(NEX.crime_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 7: {   //Нэмэлт асуулт
                        $queryString .= ' AND LOWER(NEX.question) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 8: {   //Дүгнэлтийн утга
                        $queryString .= ' AND LOWER(NEX.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' AND (
                        LOWER(NEX.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NEX.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NEX.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NEX.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NEX.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NEX.crime_value) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NEX.question) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NEX.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;
            }
        }

        if ($param['solutionId'] != 0) {
            $queryString .= ' AND NEX.solution_id = ' . $param['solutionId'];
        } else if ($param['solutionId'] == 'all') {
            $queryString .= ' AND NEX.solution_id != 0';
        }

        if ($param['weight'] != 0) {
            $queryString .= ' AND NEX.weight = ' . $param['weight'];
        }

        if ($param['typeId'] != 0) {
            $queryString .= ' AND NEX.type_id = ' . $param['typeId'];
        } else if ($param['typeId'] == 'all') {
            $queryString .= ' AND NEX.type_id != 0';
        }

        if ($param['closeTypeId'] > 0) {

            $queryString .= ' AND NEX.close_type_id = ' . $param['closeTypeId'];
        } else if ($param['closeTypeId'] == 'all') {

            $queryString .= ' AND NEX.close_type_id != 0';
        }

        if ($param['sex'] == 1) {
            $queryString .= ' AND NEX.sex = 1';
        }

        if ($param['sex'] == 2) {
            $queryString .= ' AND NEX.sex = 0';
        }

        if ($param['payment'] == 1) {
            $queryString .= ' AND NEX.payment = 1';
        }

        if ($param['payment'] == 2) {
            $queryString .= ' AND NEX.payment = 0';
        }

        if ($param['payment'] == 3) {
            $queryString .= ' AND NEX.payment = 2';
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NEX.solution_id != 0 AND DATE(NEX.close_date) <= DATE(NEX.out_date)';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NEX.solution_id = 0 AND CURDATE() <= DATE(NEX.out_date)';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NEX.solution_id != 0 AND DATE(NEX.close_date) > DATE(NEX.out_date)';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NEX.solution_id = 0 AND CURDATE() >= DATE(NEX.out_date)';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND DATE(NEX.protocol_out_date) < DATE(NEX.in_date)';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NEX.solution_id = 0';
        }

        if ($param['closeDescription'] != '') {

            $queryString .= ' AND (LOWER(NEX.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        if ($param['expertId'] != 0) {

            $query = $this->db->query('
                SELECT 
                    NEX.id,
                    NEX.create_number,
                    IF(NEX.is_mixx = 1, "<span class=\'fa fa-users\'></span>", "") AS is_mixx,
                    CONCAT("И:", DATE(NEX.in_date), "<br>", "Д:", DATE(NEX.out_date)) AS in_out_date,
                    NEX.agent_name,
                    CONCAT(IF(DATE(NEX.protocol_in_date) != \'0000-00-00\', CONCAT("<br>И:", DATE(NEX.protocol_in_date)), \'\'), \' \', IF(DATE(NEX.protocol_out_date) != \'0000-00-00\', CONCAT("<br>Д:", DATE(NEX.protocol_out_date), ""), \'\')) AS protocol_date,
                    
                    (IF(NEX.lname != \'\', CONCAT(NEX.lname, "-н ", "<strong>", NEX.fname, "</strong>"), NEX.fname)) AS full_name,
                    (CASE 
                        WHEN (NEX.is_age_infinitive = 1) THEN \'\'
                        WHEN (NEX.is_age_infinitive = 0 AND NEX.age < 1) THEN CONCAT(", ", CONVERT(SUBSTRING(NEX.age, 3, 2),UNSIGNED INTEGER), " сартай")
                        ELSE IF(NEX.is_age_infinitive = 0 AND NEX.age >= 1, CONCAT(", ", CONVERT(NEX.age,UNSIGNED INTEGER), " настай"), "")
                    END) AS age_calc,
                    (IF(NEX.sex = 1, \', эр\', \', эм\')) AS sex,
                    CONCAT(\'<strong>\', NEX.protocol_number, \'</strong><br>\', NEX.pre_create_number, \' \', NEX.crime_value) AS pre_create_number_value,
                    IF(NEX.object_count > 0, CONCAT(NEX.object, \' <strong>(\', NEX.object_count , \')</strong>\'), \'\') AS object,
                    NEX.question,
                    CONCAT(NEX.expert, " ") AS expert_type,
                    (IF(NEX.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:rgba(255,255,255,0.8);\')) AS expert_status,
                    (IF(NEX.weight > 0, NEX.weight, \'\')) AS weight,
                    NEX.close_type_id,
                    NEX.solution_id,
                    DATE(NEX.close_date) AS close_date,
                    NEX.close_description,
                    NEX.description,
                    (CASE 
                        WHEN (NEX.solution_id != 0 AND NEX.close_type_id != 0 AND NEX.close_date > NEX.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                        WHEN NEX.solution_id = 0 AND NEX.close_type_id = 0 AND NOW() > NEX.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                        ELSE \'background-color: transparent;\'
                    END) AS row_status,
                    NEX.created_user_id,
                    NEX.param
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_extra` AS NEX ON NE.cont_id = NEX.id
                WHERE NE.expert_id = ' . $param['expertId'] . ' AND NE.mod_id = 55 ' . $queryString . '
                ORDER BY NEX.create_number DESC
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        } else {
            $query = $this->db->query('
                SELECT 
                    NEX.id,
                    NEX.create_number,
                    IF(NEX.is_mixx = 1, "<span class=\'fa fa-users\'></span>", "") AS is_mixx,
                    CONCAT("И:", DATE(NEX.in_date), "<br>", "Д:", DATE(NEX.out_date)) AS in_out_date,
                    NEX.agent_name,
                    CONCAT(IF(DATE(NEX.protocol_in_date) != \'0000-00-00\', CONCAT("<br>И:", DATE(NEX.protocol_in_date)), \'\'), \' \', IF(DATE(NEX.protocol_out_date) != \'0000-00-00\', CONCAT("<br>Д:", DATE(NEX.protocol_out_date), ""), \'\')) AS protocol_date,
                    
                    (IF(NEX.lname != \'\', CONCAT(NEX.lname, "-н ", "<strong>", NEX.fname, "</strong>"), NEX.fname)) AS full_name,
                    (CASE 
                        WHEN (NEX.is_age_infinitive = 1) THEN \'\'
                        WHEN (NEX.is_age_infinitive = 0 AND NEX.age < 1) THEN CONCAT(", ", CONVERT(SUBSTRING(NEX.age, 3, 2),UNSIGNED INTEGER), " сартай")
                        ELSE IF(NEX.is_age_infinitive = 0 AND NEX.age >= 1, CONCAT(", ", CONVERT(NEX.age,UNSIGNED INTEGER), " настай"), "")
                    END) AS age_calc,
                    (IF(NEX.sex = 1, \', эр\', \', эм\')) AS sex,
                    CONCAT(\'<strong>\', NEX.protocol_number, \'</strong><br>\', NEX.pre_create_number, \' \', NEX.crime_value) AS pre_create_number_value,
                    IF(NEX.object_count > 0, CONCAT(NEX.object, \' <strong>(\', NEX.object_count , \')</strong>\'), \'\') AS object,
                    NEX.question,
                    CONCAT(NEX.expert, " ") AS expert_type,
                    (IF(NEX.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:rgba(255,255,255,0.8);\')) AS expert_status,
                    (IF(NEX.weight > 0, NEX.weight, \'\')) AS weight,
                    
                    NEX.close_type_id,
                    NEX.solution_id,
                    DATE(NEX.close_date) AS close_date,
                    NEX.close_description,
                    NEX.description,
                    (CASE 
                        WHEN (NEX.solution_id != 0 AND NEX.close_type_id != 0 AND NEX.close_date > NEX.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                        WHEN NEX.solution_id = 0 AND NEX.close_type_id = 0 AND NOW() > NEX.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                        ELSE \'background-color: transparent;\'
                    END) AS row_status,
                    NEX.created_user_id,
                    NEX.param
                FROM `gaz_nifs_extra` AS NEX
                WHERE 1 = 1 ' . $queryString . '
                ORDER BY NEX.create_number DESC
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        }

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $param = json_decode($row->param);

                array_push($data, array(
                    'id' => $row->id,
                    'create_number' => $row->create_number,
                    'in_out_date' => $row->in_out_date,
                    'is_mixx' => $row->is_mixx,
                    'partner_agent_date' => $param->partner . ' ' . $row->agent_name . ' ' . $row->protocol_date,
                    'who_is' => $row->full_name . '<br>' . $row->age_calc . $row->sex,
                    'pre_create_number_value' => $row->pre_create_number_value,
                    'object' => $row->object,
                    'question' => $param->question,
                    'expert_type' => $row->expert_type . '<br><strong>' . $param->type . '</strong>',
                    'expert_status' => $row->expert_status,
                    'weight' => $row->weight,
                    'report' => ($row->solution_id > 0 ? $row->close_date . '<br>' . $row->close_description : ''),
                    'description' => $row->description,
                    'row_status' => $row->row_status
                ));
            }
        }
        return array('data' => $data, 'search' => self::searchKeywordView_model());
    }

    public function insert_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->userdata['adminUserId'],
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        $this->nifsResearchTypeData = $this->nifsResearchType->getData_model(array('selectedId' => $this->input->post('researchTypeId')));
        $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $this->input->post('motiveId')));
        $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->post('partnerId')));
        $this->categoryData = $this->category->getData_model(array('selectedId' => $this->input->post('catId')));
        $this->nifsCrimeTypeData = $this->nifsCrimeType->getData_model(array('selectedId' => $this->input->post('typeId')));
        $this->nifsQuestionData = $this->nifsQuestion->getData_model(array('selectedId' => $this->input->post('questionId')));

        $this->data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'is_mixx' => $this->input->post('isMixx'),
                'research_type_id' => $this->input->post('researchTypeId'),
                'motive_id' => $this->input->post('motiveId'),
                'create_number' => getCreateNumber(array('createNumber' => $this->input->post('createNumber'), 'table' => 'nifs_extra', 'departmentId' => $this->nifsDepartmentId)),
                'in_date' => $this->input->post('inDate'),
                'out_date' => $this->input->post('outDate'),
                'fname' => $this->input->post('fname'),
                'lname' => $this->input->post('lname'),
                'age' => ($this->input->post('age') != null ? $this->input->post('age') : 0),
                'is_age_infinitive' => ($this->input->post('isAgeInfinitive') != null ? $this->input->post('isAgeInfinitive') : 0),
                'sex' => $this->input->post('sex'),
                'partner_id' => $this->input->post('partnerId'),
                'agent_name' => $this->input->post('agentName'),
                'description' => $this->input->post('description'),
                'type_id' => $this->input->post('typeId'),
                'crime_value' => $this->input->post('crimeValue'),
                'object' => $this->input->post('object'),
                'object_count' => $this->input->post('objectCount'),
                'question_id' => $this->input->post('questionId'),
                'question' => $this->input->post('question'),
                'expert' => '',
                'protocol_number' => $this->input->post('protocolNumber'),
                'protocol_in_date' => $this->input->post('protocolInDate'),
                'protocol_out_date' => $this->input->post('protocolOutDate'),
                'department_id' => $this->nifsDepartmentId,
                'extra_expert_value' => $this->input->post('extraExpertValue'),
                'param' => json_encode(array(
                    'researchType' => ($this->nifsResearchTypeData != false ? $this->nifsResearchTypeData->title : ''),
                    'motive' => ($this->nifsMotiveData != false ? $this->nifsMotiveData->title : ''),
                    'partner' => ($this->partnerData != false ? $this->partnerData->title : ''),
                    'type' => ($this->nifsCrimeTypeData != false ? $this->nifsCrimeTypeData->title : ''),
                    'category' => ($this->categoryData != false ? $this->categoryData->title : ''),
                    'question' => ($this->nifsQuestionData != false ? $this->nifsQuestionData->title : '') . ' ' . $this->input->post('question'),
                    'solution' => '',
                    'closeType' => '')),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'order_num' => getOrderNum(array('table' => 'nifs_extra', 'field' => 'order_num')),
                'is_active' => 1,
                'year' => $this->session->adminCloseYear,
                'register' => $this->input->post('register'),
                'payment' => $this->input->post('payment'),
                'payment_description' => $this->input->post('paymentDescription')));

        if ($this->db->insert_batch($this->db->dbprefix . 'nifs_extra', $this->data)) {
            return $this->hrPeople->updateHrPeopleParam_model(array('modId' => $this->input->post('modId'), 'contId' => $param['getUID'], 'expertId' => $this->input->post('expertId'), 'extraExpertValue' => $this->input->post('extraExpertValue'), 'table' => 'nifs_extra'));
        }
        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->userdata['adminUserId'],
            'type' => LOG_TYPE_UPDATE,
            'data' => json_encode($_POST)));

        $this->nifsCrimeTypeData = $this->nifsCrimeType->getData_model(array('selectedId' => $this->input->post('typeId')));
        $this->categoryData = $this->category->getData_model(array('selectedId' => $this->input->post('catId')));
        $this->nifsQuestionData = $this->nifsQuestion->getData_model(array('selectedId' => $this->input->post('questionId')));
        $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->post('partnerId')));
        $this->nifsResearchTypeData = $this->nifsResearchType->getData_model(array('selectedId' => $this->input->post('researchTypeId')));
        $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $this->input->post('motiveId')));

        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'is_mixx' => $this->input->post('isMixx'),
            'research_type_id' => $this->input->post('researchTypeId'),
            'motive_id' => $this->input->post('motiveId'),
            'create_number' => $this->input->post('createNumber'),
            'in_date' => $this->input->post('inDate'),
            'out_date' => $this->input->post('outDate'),
            'fname' => $this->input->post('fname'),
            'lname' => $this->input->post('lname'),
            'age' => ($this->input->post('age') != null ? $this->input->post('age') : 0),
            'is_age_infinitive' => ($this->input->post('isAgeInfinitive') != null ? $this->input->post('isAgeInfinitive') : 0),
            'sex' => $this->input->post('sex'),
            'partner_id' => $this->input->post('partnerId'),
            'agent_name' => $this->input->post('agentName'),
            'description' => $this->input->post('description'),
            'type_id' => $this->input->post('typeId'),
            'crime_value' => $this->input->post('crimeValue'),
            'object' => $this->input->post('object'),
            'object_count' => $this->input->post('objectCount'),
            'question_id' => $this->input->post('questionId'),
            'question' => $this->input->post('question'),
            'protocol_number' => $this->input->post('protocolNumber'),
            'protocol_in_date' => $this->input->post('protocolInDate'),
            'protocol_out_date' => $this->input->post('protocolOutDate'),
            'extra_expert_value' => $this->input->post('extraExpertValue'),
            'param' => json_encode(array(
                'researchType' => ($this->nifsResearchTypeData != false ? $this->nifsResearchTypeData->title : ''),
                'motive' => ($this->nifsMotiveData != false ? $this->nifsMotiveData->title : ''),
                'partner' => ($this->partnerData != false ? $this->partnerData->title : ''),
                'type' => ($this->nifsCrimeTypeData != false ? $this->nifsCrimeTypeData->title : ''),
                'category' => ($this->categoryData != false ? $this->categoryData->title : ''),
                'question' => ($this->nifsQuestionData != false ? $this->nifsQuestionData->title : '') . ' ' . $this->input->post('question'),
                'solution' => '',
                'closeType' => '')),
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s'),
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'order_num' => $this->input->post('orderNum'),
            'is_active' => 1,
            'year' => $this->session->adminCloseYear,
            'register' => $this->input->post('register'),
            'payment' => $this->input->post('payment'),
            'payment_description' => $this->input->post('paymentDescription'),
            'year' => $this->session->adminCloseYear);

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'nifs_extra', $this->data)) {
            return $this->hrPeople->updateHrPeopleParam_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('id'), 'expertId' => $this->input->post('expertId'), 'extraExpertValue' => $this->input->post('extraExpertValue'), 'table' => 'nifs_extra'));
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {

        $row = $this->getData_model(array('selectedId' => $this->input->post('id')));

        $this->slog->log_model(array(
            'modId' => $row->mod_id,
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_DELETE,
            'data' => json_encode($row)));

        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'nifs_extra')) {
            $this->db->where('mod_id', $this->modId);
            $this->db->where('cont_id', $this->input->post('id'));
            $this->db->delete($this->db->dbprefix . 'nifs_expert');
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');


        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function close_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->userdata['adminUserId'],
            'type' => LOG_TYPE_CLOSE,
            'data' => json_encode($_POST)));

        $this->nifsSolutionData = $this->nifsSolution->getData_model(array('selectedId' => $this->input->post('solutionId')));
        $this->nifsCloseTypeData = $this->nifsCloseType->getData_model(array('selectedId' => $this->input->post('closeTypeId')));

        $extraData = $this->getData_model(array('selectedId' => $this->input->post('id')));
        $param = json_decode($extraData->param);

        $param->solution = ($this->nifsSolutionData != false ? $this->nifsSolutionData->title : '');
        $param->closeType = ($this->nifsCloseTypeData != false ? $this->nifsCloseTypeData->title : '');


        $this->data = array(
            'close_date' => $this->input->post('closeDate'),
            'weight' => $this->input->post('weight'),
            'close_description' => $this->input->post('closeDescription'),
            'solution_id' => $this->input->post('solutionId'),
            'close_type_id' => $this->input->post('closeTypeId'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'param' => json_encode($param)
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'nifs_extra', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function searchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('createNumber')) {
            $this->string .= '<span class="label label-default label-rounded">Бүртгэлийн №: ' . $this->input->get('createNumber') . '</span>';
            $this->string .= form_hidden('createNumber', $this->input->get('createNumber'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('protocolNumber')) {
            $this->string .= '<span class="label label-default label-rounded">Хэргийн дугаар: ' . $this->input->get('protocolNumber') . '</span>';
            $this->string .= form_hidden('protocolNumber', $this->input->get('protocolNumber'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('researchTypeId')) {

            if ($this->input->get('researchTypeId') != 'all') {
                $this->nifsResearchType = $this->nifsResearchType->getData_model(array('selectedId' => $this->input->get('researchTypeId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsResearchType->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Шинжилгээ бүх</span>';
            }

            $this->string .= form_hidden('researchTypeId', $this->input->get('researchTypeId'));
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

        if ($this->input->get('age1') and $this->input->get('age2')) {
            $this->string .= '<span class="label label-default label-rounded">Нас: ' . $this->input->get('age1') . '-' . $this->input->get('age2') . '-ны хооронд</span>';
            $this->string .= form_hidden('age1', $this->input->get('age1'));
            $this->string .= form_hidden('age2', $this->input->get('age2'));
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('age1')) {
            $this->string .= '<span class="label label-default label-rounded">Нас: ' . $this->input->get('age1') . '-с их</span>';
            $this->string .= form_hidden('age1', $this->input->get('age1'));
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('age2')) {
            $this->string .= '<span class="label label-default label-rounded">Нас: ' . $this->input->get('age2') . '-с бага</span>';
            $this->string .= form_hidden('age2', $this->input->get('age2'));
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

            $this->string .= '<span class="label label-default label-rounded">Хаасан огноо: ' . _dateFormat(array('date' => $this->input->get('closeInDate'))) . '-' . _dateFormat(array('date' => $this->input->get('closeOutDate'))) . '</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('closeInDate')) {

            $this->string .= '<span class="label label-default label-rounded">Хаасан огноо: ' . _dateFormat(array('date' => $this->input->get('closeInDate'))) . ' хойш</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('closeOutDate')) {

            $this->string .= '<span class="label label-default label-rounded">Хаасан огноо: ' . _dateFormat(array('date' => $this->input->get('closeOutDate'))) . ' өмнөх</span>';
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

        if ($this->input->get('expertId')) {
            if ($this->input->get('expertId') != 'all') {
                $this->expertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->get('expertId')));
                $this->string .= '<span class="label label-default label-rounded">Шинжээч: ' . mb_substr($this->expertData->lname, 0, 1, 'UTF-8') . '.' . $this->expertData->fname . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('expertId', $this->input->get('expertId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('expertDoctorId')) {
            if ($this->input->get('expertDoctorId') != 'all') {
                $this->expertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->get('expertDoctorId')));
                $this->string .= '<span class="label label-default label-rounded">Шинжээч эмч: ' . mb_substr($this->expertData->lname, 0, 1, 'UTF-8') . '.' . $this->expertData->fname . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('expertDoctorId', $this->input->get('expertDoctorId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('solutionId')) {
            if ($this->input->get('solutionId') != 'all') {
                $this->nifsSolution = $this->nifsSolution->getData_model(array('selectedId' => $this->input->get('solutionId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsSolution->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Шинжилгээ хаасан байдал</span>';
            }

            $this->string .= form_hidden('solutionId', $this->input->get('solutionId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->string .= form_hidden('keyword', $this->input->get('keyword'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('closeDescription')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('closeDescription') . '</span>';
            $this->string .= form_hidden('closeDescription', $this->input->get('closeDescription'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('motiveId')) {
            if ($this->input->get('motiveId') != 'all') {
                $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $this->input->get('motiveId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsMotiveData->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Шинжилгээ хийсэн үндэслэл бүгд</span>';
            }

            $this->string .= form_hidden('motiveId', $this->input->get('motiveId'));
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

        if ($this->input->get('questionId')) {
            if ($this->input->get('questionId') != 'all') {
                $this->questionData = $this->nifsQuestion->getData_model(array('selectedId' => $this->input->get('questionId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->questionData->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('questionId', $this->input->get('questionId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('typeId')) {
            if ($this->input->get('typeId') != 'all') {
                $this->nifsCrimeType = $this->nifsCrimeType->getData_model(array('selectedId' => $this->input->get('typeId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCrimeType->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Шинжилгээний бүх төрөл</span>';
            }

            $this->string .= form_hidden('typeId', $this->input->get('typeId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('closeTypeId')) {

            if ($this->input->get('closeTypeId') != 'all') {
                $this->nifsCloseType = $this->nifsCloseType->getData_model(array('selectedId' => $this->input->get('closeTypeId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCloseType->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Хаасан байдал</span>';
            }

            $this->string .= form_hidden('closeTypeId', $this->input->get('closeTypeId'));
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

        if ($this->input->get('payment')) {

            $this->string .= form_hidden('payment', $this->input->get('payment'));

            if ($this->input->get('payment') == 1) {
                $this->string .= '<span class="label label-default label-rounded">Төлбөр төлсөн</span>';
                $this->showResetBtn = TRUE;
            }

            if ($this->input->get('payment') == 2) {
                $this->string .= '<span class="label label-default label-rounded">Төлбөр чөлөөлсөн</span>';
                $this->showResetBtn = TRUE;
            }

//            if ($this->input->get('payment') == 3) {
//                $this->string .= '<span class="label label-default label-rounded">Төлбөр чөлөөлсөн</span>';
//                $this->showResetBtn = TRUE;
//            }
        }

        if ($this->showResetBtn) {

            if ($this->input->get('isReportMenu') == 1) {

                $this->string .= ' <a href="javascript:;" onclick="_reportItem({reportMenuId: ' . $this->input->get('reportMenuId') . ', reportModId: ' . $this->input->get('reportModId') . '});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            } else if ($this->input->get('selectedId') > 0) {

                $this->string .= ' <a href="/snifsSearch?keyword=' . $this->input->get('keyword') . '"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            } else {

                $this->string .= ' <a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            }
        }

        return ($this->showResetBtn ? '<div class="_search-result-inner"><form action="javascript:;" id="form-nifs-extra"> Хайлтын үр дүн: ' . $this->string . '</form></div>' : '');
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cat_id,
                is_mixx,
                research_type_id,
                motive_id,
                create_number,
                DATE(in_date) AS in_date,
                DATE(out_date) AS out_date,
                fname,
                lname,
                IF(is_age_infinitive = 1, \'\', age) AS age,
                is_age_infinitive,
                sex,
                partner_id,
                agent_name,
                description,
                type_id,
                protocol_number,
                crime_value,
                object,
                object_count,
                question_id,
                question,
                expert_doctor_id,
                expert,
                weight,
                IF(DATE(protocol_in_date) = \'0000-00-00\', \'\', DATE(protocol_in_date)) AS protocol_in_date,
                IF(DATE(protocol_out_date) = \'0000-00-00\', \'\', DATE(protocol_out_date)) AS protocol_out_date,
                department_id,
                param,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id,
                order_num,
                is_active,
                year,
                close_date,
                close_description,
                close_type_id,
                pre_create_number,
                solution_id,
                extra_expert_value,
                register
            FROM `' . $this->db->dbprefix . 'nifs_extra`
            WHERE id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

    public function export_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $queryString = '';

        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } elseif ($auth->our->read and ! $auth->your->read) {
            $queryString .= ' AND NEX.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NEX.created_user_id = -1';
        }

        if ($this->session->adminDepartmentRoleId == 2) {

            $queryString .= ' AND NEX.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        } else {

            if ($param['departmentId'] != 0) {

                $queryString .= ' AND NEX.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            } else {

                $queryString .= ' AND NEX.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
            }
        }

        if ($param['createNumber'] != 0) {
            $queryString .= ' AND NEX.create_number = ' . $param['createNumber'];
        }

        if ($param['protocolNumber'] != 0) {
            $queryString .= ' AND NEX.protocol_number = ' . $param['protocolNumber'];
        }

        if ($param['researchTypeId'] != 0) {
            $queryString .= ' AND NEX.research_type_id = ' . $param['researchTypeId'];
        }

        if ($param['isMixx'] == 1) {
            $queryString .= ' AND NEX.is_mixx = 1';
        }

        if ($param['isMixx'] == 2) {
            $queryString .= ' AND NEX.is_mixx = 0';
        }

        if ($param['motiveId'] != 0) {
            $queryString .= ' AND NEX.motive_id = ' . $param['motiveId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NEX.in_date) AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NEX.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NEX.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NEX.out_date)';
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NEX.protocol_in_date) AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NEX.protocol_out_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NEX.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NEX.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND \'' . $param['closeInDate'] . '\' <= DATE(NEX.close_date) AND \'' . $param['closeOutDate'] . '\' >= DATE(NEX.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND \'' . $param['closeInDate'] . '\' <= DATE(NEX.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND \'' . $param['closeOutDate'] . '\' >= DATE(NEX.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND NEX.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND NEX.cat_id = ' . $param['catId'];
        }

        /*
         * ӨМНӨХ ХЭРГИЙН ДУГААРЫГ ХАЙХ ХЭСЭГ ЭНД ОРЖ ИРНЭ. ГЭХДНЭЭ ӨМНӨХ ХЭРЭГТЭЙ ХОЛБООТОЙ ХЭСГИЙГ БҮХ БҮРТГЭЛИЙН ХУВЬД БАГЦААР НЬ НЭГ АЯТАЙХАН ШИЙДЭЛ ОЛЖ ХИЙХ ХЭРЭГТЭЙ БАЙГАА
         */

        if ($param['isAgeInfinitive'] == 1) {

            $queryString .= ' AND NEX.is_age_infinitive = ' . $param['isAgeInfinitive'];
        } else {

            if ($param['age1'] != '' and $param['age2'] != '') {

                $queryString .= ' AND (NEX.age >= ' . floatval($param['age1']) . ' AND NEX.age <= ' . floatval($param['age2']) . ') AND NEX.is_age_infinitive = 0';
            } else if ($param['age1'] != '' and $param['age2'] == '') {

                $queryString .= ' AND \'' . floatval($param['age1']) . '\' >= NEX.age AND NEX.is_age_infinitive = 0';
            } else if ($param['age1'] == '' and $param['age2'] != '') {

                $queryString .= ' AND \'' . floatval($param['age2']) . '\' <= NEX.age AND NEX.is_age_infinitive = 0';
            }
        }

//        if ($param['sex'] == 0 or $param['sex'] == 1) {
//            $this->queryString .= ' AND K.sex = ' . $param['sex'];
//        }

        if ($param['questionId'] != 0) {
            $queryString .= ' AND NEX.question_id = ' . $param['questionId'];
        }

        if ($param['partnerId'] != 0) {
            $queryString .= ' AND NEX.partner_id IN(' . $this->partner->getChildPartners_model(array($param['partnerId'])) . ')';
        }

        if ($param['expertDoctorId'] != 0) {
            $queryString .= ' AND NEX.expert_doctor_id = ' . $param['expertDoctorId'];
        }

        if ($param['protocolNumber'] != 0) {
            $queryString .= ' AND NEX.protocol_number = ' . $param['protocolNumber'];
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $queryString .= ' AND LOWER(NEX.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $queryString .= ' AND LOWER(NEX.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Албан тушаалтны нэр
                        $queryString .= ' AND LOWER(NEX.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Тайлбар
                        $queryString .= ' AND LOWER(NEX.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Регистрийн дугаар
                        $queryString .= ' AND LOWER(NEX.register) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 6: {   //Хэргийн утга
                        $queryString .= ' AND LOWER(NEX.crime_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 7: {   //Нэмэлт асуулт
                        $queryString .= ' AND LOWER(NEX.question) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 8: {   //Дүгнэлтийн утга
                        $queryString .= ' AND LOWER(NEX.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' AND (
                        LOWER(NEX.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NEX.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NEX.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NEX.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NEX.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NEX.crime_value) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;
            }
        }

        if ($param['solutionId'] != 0) {
            $queryString .= ' AND NEX.solution_id = ' . $param['solutionId'];
        }

        if ($param['weight'] != 0) {
            $queryString .= ' AND NEX.weight = ' . $param['weight'];
        }

        if ($param['typeId'] != 0) {
            $queryString .= ' AND NEX.type_id = ' . $param['typeId'];
        }

        if ($param['closeTypeId'] != 0) {

            $queryString .= ' AND NEX.close_type_id = ' . $param['closeTypeId'];
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NEX.solution_id != 0 AND NEX.close_date <= NEX.out_date';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NEX.solution_id = 0 AND NOW() <= NEX.out_date';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NEX.solution_id != 0 AND NEX.close_date > NEX.out_date';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NEX.solution_id = 0 AND NOW() > NEX.out_date';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND NEX.protocol_out_date < NEX.in_date';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NEX.solution_id = 0';
        }

        if ($param['closeDescription'] != '') {

            $queryString .= ' AND (LOWER(NEX.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        if ($param['expertId'] != 0) {

            $query = $this->db->query('
                SELECT 
                    NEX.id,
                    NEX.create_number,
                    IF(NEX.is_mixx = 1, "Бүрэлдэхүүнтэй", "Бүрэлдэхүүнгүй") AS is_mixx,
                    DATE(NEX.in_date) AS in_date,
                    DATE(NEX.out_date) AS out_date,
                    NEX.lname,
                    NEX.fname,
                    (CASE 
                        WHEN (NEX.is_age_infinitive = 1) THEN \'Нас тодорхойгүй\'
                        WHEN (NEX.is_age_infinitive = 0 AND NEX.age < 1) THEN CONCAT(CONVERT(SUBSTRING(NEX.age, 3, 2),UNSIGNED INTEGER), " сартай")
                        ELSE IF(NEX.is_age_infinitive = 0 AND NEX.age >= 1, CONCAT(CONVERT(NEX.age,UNSIGNED INTEGER), " настай"), "")
                    END) AS age,
                    (IF(NEX.sex = 1, \'Эрэгтэй\', \'Эмэгтэй\')) AS sex,
                    NEX.agent_name,
                    NEX.crime_value,
                    NEX.object_count,
                    NEX.object,
                    NEX.register,
                    NEX.question,
                    NEX.expert,
                    NEX.protocol_number,
                    DATE(NEX.protocol_in_date) AS protocol_in_date,
                    DATE(NEX.protocol_out_date) AS protocol_out_date,
                    DATE(NEX.close_date) AS close_date,
                    NEX.close_description,
                    (IF(NEX.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:rgba(255,255,255,0.8);\')) AS expert_status,
                    (IF(NEX.weight > 0, NEX.weight, \'\')) AS weight,
                    
                    NEX.close_type_id,
                    NEX.solution_id,
                    
                    
                    NEX.description,
                    (CASE 
                        WHEN (NEX.solution_id != 0 AND NEX.close_type_id != 0 AND NEX.close_date > NEX.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                        WHEN NEX.solution_id = 0 AND NEX.close_type_id = 0 AND NOW() > NEX.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                        ELSE \'background-color: transparent;\'
                    END) AS row_status,
                    NEX.created_user_id,
                    NEX.param
                FROM 
                    `gaz_nifs_expert` AS NE
                WHERE NE.expert_id = ' . $param['expertId'] . ' AND NE.mod_id = 55 ' . $queryString . '
                ORDER BY NEX.create_number DESC');
        } else {
            $query = $this->db->query('
                SELECT 
                    NEX.id,
                    NEX.create_number,
                    IF(NEX.is_mixx = 1, "Бүрэлдэхүүнтэй", "Бүрэлдэхүүнгүй") AS is_mixx,
                    DATE(NEX.in_date) AS in_date,
                    DATE(NEX.out_date) AS out_date,
                    NEX.lname,
                    NEX.fname,
                    (CASE 
                        WHEN (NEX.is_age_infinitive = 1) THEN \'Нас тодорхойгүй\'
                        WHEN (NEX.is_age_infinitive = 0 AND NEX.age < 1) THEN CONCAT(CONVERT(SUBSTRING(NEX.age, 3, 2),UNSIGNED INTEGER), " сартай")
                        ELSE IF(NEX.is_age_infinitive = 0 AND NEX.age >= 1, CONCAT(CONVERT(NEX.age,UNSIGNED INTEGER), " настай"), "")
                    END) AS age,
                    (IF(NEX.sex = 1, \'Эрэгтэй\', \'Эмэгтэй\')) AS sex,
                    NEX.agent_name,
                    NEX.crime_value,
                    NEX.object_count,
                    NEX.object,
                    NEX.register,
                    NEX.question,
                    NEX.expert,
                    NEX.protocol_number,
                    DATE(NEX.protocol_in_date) AS protocol_in_date,
                    DATE(NEX.protocol_out_date) AS protocol_out_date,
                    DATE(NEX.close_date) AS close_date,
                    NEX.close_description,
                    (IF(NEX.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:rgba(255,255,255,0.8);\')) AS expert_status,
                    (IF(NEX.weight > 0, NEX.weight, \'\')) AS weight,
                    
                    NEX.close_type_id,
                    NEX.solution_id,
                    
                    
                    
                    NEX.description,
                    (CASE 
                        WHEN (NEX.solution_id != 0 AND NEX.close_type_id != 0 AND NEX.close_date > NEX.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                        WHEN NEX.solution_id = 0 AND NEX.close_type_id = 0 AND NOW() > NEX.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                        ELSE \'background-color: transparent;\'
                    END) AS row_status,
                    NEX.created_user_id,
                    NEX.param
                FROM `gaz_nifs_extra` AS NEX
                WHERE 1 = 1 ' . $queryString . '
                ORDER BY NEX.create_number DESC');
        }

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return false;
    }

    public function getReportWorkInformationData_model($param = array()) {

        $htmlData = $queryStringData = $queryStringYearData = $queryStringInDateData = $queryStringObjectData = $queryStringMotiveData = $isAjaxDepartmentUrl = '';

        if ($param['departmentId'] > 0) {

            $queryStringData .= ' AND NC.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $queryStringYearData .= ' AND NC.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NC.in_date) AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NC.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NC.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NC.in_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NC.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $query = $this->db->query('
            SELECT 
                NCT.id,
                NCT.title,
                YEAR_TYPE_COUNT.type_count,
                IF(YEAR_TYPE_COUNT.type_count > 0, \'_row-more\', \'\') AS type_count_class,
                YEAR_TYPE_COUNT.object_count,
                YEAR_TYPE_COUNT.weight_count,
                DATE_TYPE_COUNT.date_type_count,
                IF(DATE_TYPE_COUNT.date_type_count > 0, \'_row-more\', \'\') AS date_type_count_class,
                DATE_TYPE_COUNT.date_object_count,
                RETURN_EXTRA.close_type_count,
                IF(RETURN_EXTRA.close_type_count > 0, \'_row-more\', \'\') AS close_type_count_class,
                MIXX1.mixx1_count,
                IF(MIXX1.mixx1_count > 0, \'_row-more\', \'\') AS mixx1_count_class,
                MIXX0.mixx0_count,
                IF(MIXX0.mixx0_count > 0, \'_row-more\', \'\') AS mixx0_count_class,
                NC_CLOSE_NORMAL.normal_count_close,
                IF(NC_CLOSE_NORMAL.normal_count_close > 0, \'_row-more\', \'\') AS normal_count_close_class,
                NC_HAND_NORMAL.normal_count_hand,
                IF(NC_HAND_NORMAL.normal_count_hand > 0, \'_row-more\', \'\') AS normal_count_hand_class,
                NC_CRASH_DONE.crash_count_close,
                IF(NC_CRASH_DONE.crash_count_close > 0, \'_row-more\', \'\') AS crash_count_close_class,
                NC_CRASH_HAND.crash_count_hand,
                IF(NC_CRASH_HAND.crash_count_hand > 0, \'_row-more\', \'\') AS crash_count_hand_class
            FROM `gaz_nifs_crime_type` AS NCT
            LEFT JOIN (
                SELECT 
                        NC.type_id,
                        COUNT(NC.type_id) AS type_count,
                        SUM(NC.object_count) AS object_count,
                        SUM(NC.weight) AS weight_count
                FROM `gaz_nifs_extra` AS NC
                WHERE NC.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NC.type_id
            ) AS YEAR_TYPE_COUNT ON YEAR_TYPE_COUNT.type_id = NCT.id
            LEFT JOIN (
                SELECT 
                    NC.type_id,
                    COUNT(NC.type_id) AS date_type_count,
                    SUM(NC.object_count) AS date_object_count,
                    SUM(NC.weight) AS weight_count
                FROM `gaz_nifs_extra` AS NC
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NC.type_id
            ) AS DATE_TYPE_COUNT ON DATE_TYPE_COUNT.type_id = NCT.id
            LEFT JOIN (
                SELECT 
                        NC.type_id,
                        COUNT(NC.close_type_id) AS close_type_count
                FROM `gaz_nifs_extra` AS NC
                WHERE NC.close_type_id = 8 ' . $queryStringData . '
                GROUP BY NC.type_id
            ) AS RETURN_EXTRA ON RETURN_EXTRA.type_id = NCT.id
            LEFT JOIN (
                SELECT 
                        NC.type_id,
                        COUNT(NC.is_mixx) AS mixx1_count 
                FROM `gaz_nifs_extra` AS NC 
                WHERE NC.is_mixx = 1 ' . $queryStringData . '
                GROUP BY NC.type_id
            ) MIXX1 ON MIXX1.type_id = NCT.id
            LEFT JOIN (
                SELECT 
                        NC.type_id,
                        COUNT(NC.is_mixx) AS mixx0_count 
                FROM `gaz_nifs_extra` AS NC 
                WHERE NC.is_mixx = 0 ' . $queryStringData . '
                GROUP BY NC.type_id
            ) MIXX0 ON MIXX0.type_id = NCT.id
            LEFT JOIN (
                SELECT 
                        NC.type_id, 
                        COUNT(NC.type_id) AS normal_count_close 
                FROM `gaz_nifs_extra` AS NC
                WHERE NC.solution_id != 0 AND DATE(NC.close_date) <= DATE(NC.out_date) ' . $queryStringData . '
                GROUP BY NC.type_id
            ) NC_CLOSE_NORMAL ON NC_CLOSE_NORMAL.type_id = NCT.id
            LEFT JOIN (
                SELECT 
                        NC.type_id, 
                        COUNT(NC.type_id) AS normal_count_hand 
                FROM `gaz_nifs_extra` AS NC
                WHERE NC.solution_id = 0 AND CURDATE() <= DATE(NC.out_date) ' . $queryStringData . '
                GROUP BY NC.type_id
            ) NC_HAND_NORMAL ON NC_HAND_NORMAL.type_id = NCT.id
            LEFT JOIN (
                SELECT 
                        NC.type_id, 
                        COUNT(NC.type_id) AS crash_count_close 
                FROM `gaz_nifs_extra` AS NC
                WHERE NC.solution_id != 0 AND DATE(NC.close_date) > DATE(NC.out_date) ' . $queryStringData . '
                GROUP BY NC.type_id
            ) NC_CRASH_DONE ON NC_CRASH_DONE.type_id = NCT.id
            LEFT JOIN (
                SELECT 
                        NC.type_id, 
                        COUNT(NC.type_id) AS crash_count_hand 
                FROM `gaz_nifs_extra` AS NC
                WHERE NC.solution_id = 0 AND CURDATE() >= DATE(NC.out_date) ' . $queryStringData . '
                GROUP BY NC.type_id
            ) NC_CRASH_HAND ON NC_CRASH_HAND.type_id = NCT.id
            WHERE NCT.is_active = 1 AND NCT.cat_id = 354
            ORDER BY NCT.title ASC');

        if ($query->num_rows() > 0) {

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="3" style="width:30px;">#</th>';
            $htmlData .= '<th rowspan="3">Шинжилгээний төрөл</th>';
            $htmlData .= '<th colspan="3" class="text-center">Оны эхнээс</th>';
            $htmlData .= '<th colspan="9" style="width:180px;" class="text-center">' . date('Y.m.d', strtotime($param['inDate'])) . ' - ' . date('Y.m.d', strtotime($param['outDate'])) . '</th>';
            $htmlData .= '</tr>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Нийт шинжилгээ</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Объект</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Ачаалал</th>';

            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Ирсэн шинжилгээ</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Татгалзсан</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Объект</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Бүрэлдэхүүнтэй</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Бүрэлдэхүүнгүй</th>';

            $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Хэвийн шинжилгээ</th>';
            $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Хугацаа хэтэрсэн</th>';

            $htmlData .= '</tr>';

            $htmlData .= '<tr>';

            $htmlData .= '<th style="width:80px;" class="text-center">Хаагдсан</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хийж байгаа</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хаагдсан</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хийж байгаа</th>';


            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            $i = $sumTotalTypeCount = $sumTotalObjectCount = $sumTotalWeightCount = $sumTotalDateTypeCount = $sumTotalDateObjectCount = $sumTotalCloseTypeCount = $sumTotalMixx1Count = $sumTotalMixx0Count = $sumTotalNormalCountClose = $sumTotalNormalCountHand = $sumTotalCrashCountClose = $sumTotalCrashCountHand = 0;
            foreach ($query->result() as $key => $row) {

                $sumTotalTypeCount = $sumTotalTypeCount + $row->type_count;
                $sumTotalObjectCount = $sumTotalObjectCount + $row->object_count;
                $sumTotalWeightCount = $sumTotalWeightCount + $row->weight_count;
                $sumTotalDateTypeCount = $sumTotalDateTypeCount + $row->date_type_count;
                $sumTotalCloseTypeCount = $sumTotalCloseTypeCount + $row->close_type_count;
                $sumTotalDateObjectCount = $sumTotalDateObjectCount + $row->date_object_count;
                $sumTotalMixx1Count = $sumTotalMixx1Count + $row->mixx1_count;
                $sumTotalMixx0Count = $sumTotalMixx0Count + $row->mixx0_count;
                $sumTotalNormalCountClose = $sumTotalNormalCountClose + $row->normal_count_close;
                $sumTotalNormalCountHand = $sumTotalNormalCountHand + $row->normal_count_hand;
                $sumTotalCrashCountClose = $sumTotalCrashCountClose + $row->crash_count_close;
                $sumTotalCrashCountHand = $sumTotalCrashCountHand + $row->crash_count_hand;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'typeId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->type_count . '</a></td>';
                $htmlData .= '<td class="text-center">' . $row->object_count . '</td>';
                $htmlData .= '<td class="text-center">' . $row->weight_count . '</td>';

                $htmlData .= '<td class="text-center ' . $row->date_type_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'typeId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->date_type_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->close_type_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'typeId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&closeTypeId=8&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->close_type_count . '</a></td>';
                $htmlData .= '<td class="text-center">' . $row->date_object_count . '</td>';
                $htmlData .= '<td class="text-center ' . $row->mixx1_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'typeId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&isMixx=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->mixx1_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->mixx0_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'typeId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&isMixx=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->mixx0_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->normal_count_close_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'typeId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->normal_count_close . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->normal_count_hand_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'typeId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->normal_count_hand . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crash_count_close_class . '"><a href="javascript:;" class="_crime-crash-done" onclick="_initNifsExtra({page: 0, searchQuery: \'typeId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=3&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->crash_count_close . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crash_count_hand_class . '"><a href="javascript:;" class="_crime-crash-hand" onclick="_initNifsExtra({page: 0, searchQuery: \'typeId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=4&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->crash_count_hand . '</a></td>';

                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';

            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2">Нийт</td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'typeId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalTypeCount > 0 ? $sumTotalTypeCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center">' . $sumTotalObjectCount . '</td>';
            $htmlData .= '<td class="text-center">' . $sumTotalWeightCount . '</td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'typeId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalDateTypeCount > 0 ? $sumTotalDateTypeCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'typeId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&closeTypeId=8&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalCloseTypeCount > 0 ? $sumTotalCloseTypeCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center">' . $sumTotalDateObjectCount . '</td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'typeId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&isMixx=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalMixx1Count > 0 ? $sumTotalMixx1Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'typeId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&isMixx=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalMixx0Count > 0 ? $sumTotalMixx0Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'typeId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalNormalCountClose > 0 ? $sumTotalNormalCountClose : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'typeId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalNormalCountHand > 0 ? $sumTotalNormalCountHand : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" class="_crime-crash-done" onclick="_initNifsExtra({page: 0, searchQuery: \'typeId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=3&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalCrashCountClose > 0 ? $sumTotalCrashCountClose : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" class="_crime-crash-hand" onclick="_initNifsExtra({page: 0, searchQuery: \'typeId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=4&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalCrashCountHand > 0 ? $sumTotalCrashCountHand : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNotType = $this->db->query('
                SELECT
                    NC.id,
                    NC.create_number
                FROM `gaz_nifs_extra` AS NC
                WHERE NC.type_id = 0 ' . $queryStringData . '
                ORDER BY NC.create_number ASC');

            if ($queryNotType->num_rows() > 0) {
                $htmlNotType = 'Шинжилгээний төрөл сонгоогүй (' . $queryNotType->num_rows() . '): ';
                foreach ($queryNotType->result() as $key => $rowType) {
                    $htmlNotType .= '<a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'createNumber=' . $rowType->create_number . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowType->create_number . '</a>';
                }
                $htmlData .= '<div class="table-responsive-inside-description">' . $htmlNotType . '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }

        //Шинжилгээ хийсэн үндэслэлээр тайлан харуулах эхлэл
        //$resultMotiveData = $this->queryMotiveData_model(array('departmentId' => $param['departmentId'], 'inDate' => $param['inDate'], 'outDate' => $param['outDate'], 'modId' => $param['modId']));

        $query = $this->db->query('
            SELECT 
                M.id,
                M.title,
                NC.motive_count,
                IF(NC.motive_count > 0, \'_row-more\', \'\') AS motive_count_class
            FROM `gaz_nifs_motive` AS M
            LEFT JOIN (
                SELECT 
                    NC.motive_id, 
                    COUNT(NC.motive_id) AS motive_count 
                FROM `gaz_nifs_extra` AS NC
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NC.motive_id) AS NC ON NC.motive_id = M.id
            WHERE M.is_active = 1 AND M.cat_id = ' . $this->nifsMotiveCatId);

        $motiveNumRows = $query->num_rows();

        if ($motiveNumRows > 0) {

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Шинжилгээ хийсэн үндэслэл</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Тоо</th>';
            $htmlData .= '</tr>';
            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            $sumMotiveCount = 0;
            $i = 1;

            foreach ($query->result() as $keyMotive => $rowMotive) {

                $sumMotiveCount = $sumMotiveCount + $rowMotive->motive_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i++ . '</td>';
                $htmlData .= '<td>' . $rowMotive->title . '</td>';
                $htmlData .= '<td class="text-center ' . $rowMotive->motive_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'motiveId=' . $rowMotive->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $rowMotive->motive_count . '</a></td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';

            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot _row-more"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'motiveId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $sumMotiveCount . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNotMotive = $this->db->query('
            SELECT
                NC.id,
                NC.create_number
            FROM `gaz_nifs_extra` AS NC
            WHERE NC.motive_id = 0 ' . $queryStringData . '
            ORDER BY NC.create_number ASC');

            if ($queryNotMotive->num_rows() > 0) {
                $htmlNotMotive = 'Үндэслэл сонгоогдоогүй шинжилгээ (' . $queryNotMotive->num_rows() . '): ';
                foreach ($queryNotMotive->result() as $key => $rowMotive) {
                    $htmlNotMotive .= '<a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'createNumber=' . $rowMotive->create_number . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowMotive->create_number . '</a>';
                }
                $htmlData .= '<div class="table-responsive-inside-description">' . $htmlNotMotive . '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }
        //Шинжилгээ хийсэн үндэслэлээр тайлан харуулах төгсгөл
        //Бүрэлдэхүүнтэй, бүрэлдэхүүнгүй шинжилгээний тайлан - эхлэл
        $query = $this->db->query('
            SELECT
                NRT.id,
                NRT.title,
                MIXX1_NC.mixx1_research_count,
                IF(MIXX1_NC.mixx1_research_count > 0, \'_row-more\', \'\') AS mixx1_research_count_class,
                MIXX0_NC.mixx0_research_count,
                IF(MIXX0_NC.mixx0_research_count > 0, \'_row-more\', \'\') AS mixx0_research_count_class
            FROM `gaz_nifs_research_type` AS NRT
            LEFT JOIN (
                SELECT
                    NC.research_type_id,
                    COUNT(NC.research_type_id) AS mixx1_research_count
                FROM `gaz_nifs_extra` AS NC
                WHERE NC.is_mixx = 1 ' . $queryStringData . '
                GROUP BY NC.research_type_id
            ) AS MIXX1_NC ON NRT.id = MIXX1_NC.research_type_id
            LEFT JOIN (
                SELECT
                    NC.research_type_id,
                    COUNT(NC.research_type_id) AS mixx0_research_count
                FROM `gaz_nifs_extra` AS NC
                WHERE NC.is_mixx = 0 ' . $queryStringData . '
                GROUP BY NC.research_type_id
            ) AS MIXX0_NC ON NRT.id = MIXX1_NC.research_type_id
            WHERE NRT.cat_id = ' . $this->nifsResearchTypeCatId . ' AND NRT.is_active = 1');

        if ($query->num_rows() > 0) {

            $sumRow = $sumTotalRow = $sumTotalMixx1Count = $sumTotalMixx0Count = 0;
            $i = 1;


            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px; text-align:center; padding-top:10px; padding-bottom:10px;">#</th>';
            $htmlData .= '<th>Шинжилгээ томилсон байдал</th>';
            $htmlData .= '<th style="width:100px; text-align:center;">Бүрэлдэхүүнтэй</th>';
            $htmlData .= '<th style="width:100px; text-align:center;">Бүрэлдэхүүнгүй</th>';
            $htmlData .= '<th style="width:100px; text-align:center;">Нийт</th>';
            $htmlData .= '</tr>';
            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $keyMixx => $rowMixx) {

                $sumRow = $rowMixx->mixx1_research_count + $rowMixx->mixx0_research_count;
                $sumTotalRow = $sumTotalRow + $sumRow;

                $sumTotalMixx1Count = $sumTotalMixx1Count + $rowMixx->mixx1_research_count;
                $sumTotalMixx0Count = $sumTotalMixx0Count + $rowMixx->mixx0_research_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i++ . '</td>';
                $htmlData .= '<td>' . $rowMixx->title . '</td>';
                $htmlData .= '<td class="text-center ' . $rowMixx->mixx1_research_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'researchTypeId=' . $rowMixx->id . '&isMixx=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $rowMixx->mixx1_research_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $rowMixx->mixx0_research_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'researchTypeId=' . $rowMixx->id . '&isMixx=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $rowMixx->mixx0_research_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . ($sumRow > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'researchTypeId=' . $rowMixx->id . '&isMixx=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumRow > 0 ? $sumRow : '') . '</a></td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot _row-more"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'researchTypeId=all&isMixx=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $sumTotalMixx1Count . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot _row-more"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'researchTypeId=all&isMixx=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $sumTotalMixx0Count . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot _row-more"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'researchTypeId=all&isMixx=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $sumTotalRow . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';
            $queryNotMotive = $this->db->query('
            SELECT
                NC.id,
                NC.create_number
            FROM `gaz_nifs_extra` AS NC
            WHERE NC.motive_id = 0 ' . $queryStringData . '
            ORDER BY NC.create_number ASC');

            if ($queryNotMotive->num_rows() > 0) {
                $htmlNotMotive = 'Үндэслэл сонгоогдоогүй шинжилгээ (' . $queryNotMotive->num_rows() . '): ';
                foreach ($queryNotMotive->result() as $key => $rowMotive) {
                    $htmlNotMotive .= '<a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowMotive->create_number . '</a>';
                }
                $htmlData .= '<div class="table-responsive-inside-description">' . $htmlNotMotive . '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }
        //Бүрэлдэхүүнтэй, бүрэлдэхүүнгүй шинжилгээний тайлан - төгсгөл
        //Шийдвэрлэсэн хаасан байдал - Эхлэл
        $query = $this->db->query('
            SELECT 
                M.id,
                M.title,
                NC.solution_count,
                IF(NC.solution_count > 0, \'_row-more\', \'\') AS solution_count_class
            FROM `gaz_nifs_solution` AS M
            LEFT JOIN (
                SELECT 
                    NC.solution_id,
                    COUNT(NC.solution_id) AS solution_count
                FROM `gaz_nifs_extra` AS NC 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NC.solution_id
            ) AS NC ON NC.solution_id = M.id
            WHERE M.is_active = 1 AND M.cat_id = ' . $this->nifsSolutionCatId);

        if ($query->num_rows() > 0) {

            $sumTotalSolutionCount = 0;
            $i = 1;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px; text-align:center; padding-top:10px; padding-bottom:10px;">#</th>';
            $htmlData .= '<th>Шинжилгээ хаасан байдал</th>';
            $htmlData .= '<th style="width:100px; text-align:center;">Нийт</th>';
            $htmlData .= '</tr>';
            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $keySolution => $rowSolution) {

                $sumTotalSolutionCount = $sumTotalSolutionCount + $rowSolution->solution_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i++ . '</td>';
                $htmlData .= '<td>' . $rowSolution->title . '</td>';
                $htmlData .= '<td class="text-center ' . $rowSolution->solution_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'solutionId=' . $rowSolution->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $rowSolution->solution_count . '</a></td>';
                $htmlData .= '</tr>';
            }


            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot _row-more"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'solutionId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $sumTotalSolutionCount . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNotSolution = $this->db->query('
            SELECT
                NC.id,
                NC.create_number
            FROM `gaz_nifs_extra` AS NC
            WHERE NC.solution_id = 0 ' . $queryStringData . '
            ORDER BY NC.create_number ASC');

            if ($queryNotSolution->num_rows() > 0) {
                $htmlNotSolution = 'Шинжилгээ илэрсэн эсэх байдал сонгоогдоогүй шинжилгээ (' . $queryNotSolution->num_rows() . '): ';
                foreach ($queryNotSolution->result() as $key => $rowSolution) {
                    $htmlNotSolution .= '<a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'createNumber=' . $rowSolution->create_number . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '\'});" class="label-not-selected-content">№: ' . $rowSolution->create_number . '</a>';
                }
                $htmlData .= '<div class="table-responsive-inside-description">' . $htmlNotSolution . '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }
        //Шийдвэрлэсэн хаасан байдал - Төгсгөл
        //Шийдвэрлэсэн хаасан байдал - Эхлэл
        $query = $this->db->query('
            SELECT 
                M.id,
                M.title,
                NC.close_type_count,
                IF(NC.close_type_count > 0, \'_row-more\', \'\') AS close_type_count_class
            FROM `gaz_nifs_close_type` AS M
            LEFT JOIN (
                SELECT 
                    NC.close_type_id,
                    COUNT(NC.close_type_id) AS close_type_count
                FROM `gaz_nifs_extra` AS NC 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NC.close_type_id
            ) AS NC ON NC.close_type_id = M.id
            WHERE M.is_active = 1 AND M.cat_id = ' . $this->nifsCloseTypeCatId);

        if ($query->num_rows() > 0) {

            $sumTotalCloseTypeCount = 0;
            $i = 1;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px; text-align:center; padding-top:10px; padding-bottom:10px;">#</th>';
            $htmlData .= '<th>Шинжилгээ хаасан байдал</th>';
            $htmlData .= '<th style="width:100px; text-align:center;">Нийт</th>';
            $htmlData .= '</tr>';
            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $rowCloseType) {

                $sumTotalCloseTypeCount = $sumTotalCloseTypeCount + $rowCloseType->close_type_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i++ . '</td>';
                $htmlData .= '<td>' . $rowCloseType->title . '</td>';
                $htmlData .= '<td class="text-center ' . $rowCloseType->close_type_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'closeTypeId=' . $rowCloseType->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $rowCloseType->close_type_count . '</a></td>';
                $htmlData .= '</tr>';
            }


            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot _row-more"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'closeTypeId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $sumTotalCloseTypeCount . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNotCloseType = $this->db->query('
            SELECT
                NC.id,
                NC.create_number
            FROM `gaz_nifs_extra` AS NC
            WHERE NC.close_type_id = 0 ' . $queryStringData . '
            ORDER BY NC.create_number ASC');

            if ($queryNotCloseType->num_rows() > 0) {
                $htmlNotCloseType = 'Шинжилгээ шийдсэн хэлбэр сонгоогүй шинжилгээ (' . $queryNotCloseType->num_rows() . '): ';
                foreach ($queryNotCloseType->result() as $key => $rowCloseType) {
                    $htmlNotCloseType .= '<a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'createNumber=' . $rowCloseType->create_number . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '\'});" class="label-not-selected-content">№: ' . $rowCloseType->create_number . '</a>';
                }
                $htmlData .= '<div class="table-responsive-inside-description">' . $htmlNotCloseType . '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }
        //Шийдвэрлэсэн хаасан байдал - Төгсгөл

        return $htmlData;
    }

    public function getReportWeightData_model($param = array()) {

        $queryStringData = $queryStringYearData = $queryStringHrPeopleData = $htmlData = $isAjaxDepartmentUrl = '';

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NE.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NE.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NE.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NE.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NE.year = \'' . $this->session->adminCloseYear . '\'';
        }


        $query = $this->db->query('
            SELECT 
                NEP.expert_id
            FROM `gaz_nifs_expert` AS NEP
            INNER JOIN `gaz_hr_people_work` AS HPW ON NEP.expert_id = HPW.people_id AND HPW.is_currenty = 1
            INNER JOIN `gaz_nifs_extra` AS NE ON NEP.mod_id = NE.mod_id AND NEP.cont_id = NE.id
            WHERE 1 = 1 AND NE.department_id = ' . $param['departmentId'] . $queryStringData . '
            GROUP BY NEP.expert_id');

        $inPeopleId = NIFS_EXTRA_EXPERT;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $row) {
                $inPeopleId .= $row->expert_id . ', ';
            }
        }
        $inPeopleId = rtrim($inPeopleId, ', ');

        if ($param['departmentId'] > 0) {
            $queryStringData .= ' AND NE.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $queryStringYearData .= ' AND NE.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        $query = $this->db->query('
            SELECT 
                HP.id,
                IF(HP.is_active = 1, CONCAT(\'<strong>\',SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname, \'</strong><br><div style="font-size:90%">\', HPP.title, \', \', HPD.title, \'</div>\'), CONCAT(\'<strong>\', HP.fname, \'</strong>\')) AS full_name,
                C.year_total_count,
                IF(C.year_total_count > 0, \'_row-more\', \'\') AS year_total_count_class,
                C.year_object_count,
                C.year_weight_count,
                DATE_TOTAL_COUNT.date_total_count,
                IF(DATE_TOTAL_COUNT.date_total_count > 0, \'_row-more\', \'\') AS date_total_count_class,
                DATE_TOTAL_COUNT.date_object_count,
                DATE_TOTAL_COUNT.date_weight_count,
                RETURNCRIME.return_count,
                IF(RETURNCRIME.return_count > 0, \'_row-more\', \'\') AS return_count_class,
                MIXX1.mixx1_count,
                IF(MIXX1.mixx1_count > 0, \'_row-more\', \'\') AS mixx1_count_class,
                MIXX0.mixx0_count,
                IF(MIXX0.mixx0_count > 0, \'_row-more\', \'\') AS mixx0_count_class,
                NC_HAND_NORMAL.normal_count_hand,
                IF(NC_HAND_NORMAL.normal_count_hand > 0, \'_row-more\', \'\') AS normal_count_hand_class,
                NC_CLOSE_NORMAL.normal_count_close,
                IF(NC_CLOSE_NORMAL.normal_count_close > 0, \'_row-more\', \'\') AS normal_count_close_class,
                NC_CRASH_HAND.crash_count_hand,
                IF(NC_CRASH_HAND.crash_count_hand > 0, \'_row-more\', \'\') AS crash_count_hand_class,
                NC_CRASH_DONE.crash_count_close,
                IF(NC_CRASH_DONE.crash_count_close > 0, \'_row-more\', \'\') AS crash_count_close_class
            FROM `gaz_hr_people` AS HP
            INNER JOIN `gaz_hr_people_work` AS HPW ON HP.id = HPW.people_id AND HPW.is_currenty = 1
            INNER JOIN `gaz_hr_people_position` AS HPP ON HPP.id = HPW.position_id 
            INNER JOIN `gaz_hr_people_department` AS HPD ON HPD.id = HPW.department_id 
            LEFT JOIN (
                SELECT 
                    NEP.expert_id,
                    COUNT(NEP.expert_id) AS year_total_count,
                    COUNT(NE.object_count) AS year_object_count,
                    SUM(NE.weight) AS year_weight_count
                FROM `gaz_nifs_expert` AS NEP 
                INNER JOIN `gaz_nifs_extra` AS NE ON NEP.mod_id = NE.mod_id AND NEP.cont_id = NE.id
                WHERE 1 = 1 AND NE.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NEP.expert_id
            ) C ON C.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NEP.expert_id,
                    COUNT(NEP.expert_id) AS date_total_count,
                    SUM(NE.object_count) AS date_object_count,
                    SUM(NE.weight) AS date_weight_count
                FROM `gaz_nifs_expert` AS NEP 
                INNER JOIN `gaz_nifs_extra` AS NE ON NEP.mod_id = NE.mod_id AND NEP.cont_id = NE.id
                WHERE 1 = 1' . $queryStringData . '
                GROUP BY NEP.expert_id
            ) DATE_TOTAL_COUNT ON DATE_TOTAL_COUNT.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NEP.expert_id,
                    COUNT(NEP.expert_id) AS return_count
                FROM `gaz_nifs_expert` AS NEP 
                INNER JOIN `gaz_nifs_extra` AS NE ON NEP.mod_id = NE.mod_id AND NEP.cont_id = NE.id
                WHERE NE.solution_id = 11' . $queryStringData . '
                GROUP BY NEP.expert_id
            ) RETURNCRIME ON RETURNCRIME.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NEP.expert_id,
                    COUNT(NE.is_mixx) AS mixx1_count 
                FROM `gaz_nifs_expert` AS NEP 
                INNER JOIN `gaz_nifs_extra` AS NE ON NEP.mod_id = NE.mod_id AND NEP.cont_id = NE.id
                WHERE NE.is_mixx = 1' . $queryStringData . '
                GROUP BY NEP.expert_id
            ) MIXX1 ON MIXX1.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NEP.expert_id,
                    COUNT(NE.is_mixx) AS mixx0_count 
                FROM `gaz_nifs_expert` AS NEP 
                INNER JOIN `gaz_nifs_extra` AS NE ON NEP.mod_id = NE.mod_id AND NEP.cont_id = NE.id
                WHERE NE.is_mixx = 0' . $queryStringData . '
                GROUP BY NEP.expert_id
            ) MIXX0 ON MIXX0.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NEP.expert_id, 
                    COUNT(NEP.expert_id) AS normal_count_hand 
                FROM `gaz_nifs_expert` AS NEP
                INNER JOIN `gaz_nifs_extra` AS NE ON NEP.mod_id = NE.mod_id AND NEP.cont_id = NE.id
                WHERE NE.solution_id = 0 AND CURDATE() <= DATE(NE.out_date) ' . $queryStringData . '
                GROUP BY NEP.expert_id
            ) NC_HAND_NORMAL ON NC_HAND_NORMAL.expert_id = HP.id
            LEFT JOIN (
                SELECT 
                    NEP.expert_id, 
                    count(NEP.expert_id) AS normal_count_close
                FROM `gaz_nifs_expert` AS NEP
                INNER JOIN `gaz_nifs_extra` AS NE ON NEP.mod_id = NE.mod_id AND NEP.cont_id = NE.id
                WHERE NE.solution_id != 0 AND DATE(NE.close_date) <= DATE(NE.out_date) ' . $queryStringData . '
                GROUP BY NEP.expert_id
            ) NC_CLOSE_NORMAL ON NC_CLOSE_NORMAL.expert_id = HP.id
            LEFT JOIN (
                SELECT
                    NEP.expert_id,
                    COUNT(NEP.expert_id) AS crash_count_hand
                FROM `gaz_nifs_expert` AS NEP
                INNER JOIN `gaz_nifs_extra` AS NE ON NEP.mod_id = NE.mod_id AND NEP.cont_id = NE.id
                WHERE NE.solution_id = 0 AND CURDATE() >= DATE(NE.out_date) ' . $queryStringData . '
                GROUP BY NEP.expert_id
            ) NC_CRASH_HAND ON NC_CRASH_HAND.expert_id = HP.id 
            LEFT JOIN (
                SELECT
                    NEP.expert_id,
                    COUNT(NEP.expert_id) AS crash_count_close
                FROM `gaz_nifs_expert` AS NEP
                INNER JOIN `gaz_nifs_extra` AS NE ON NEP.mod_id = NE.mod_id AND NEP.cont_id = NE.id
                WHERE NE.solution_id != 0 AND DATE(NE.close_date) > DATE(NE.out_date) ' . $queryStringData . '
                GROUP BY NEP.expert_id
            ) NC_CRASH_DONE ON NC_CRASH_DONE.expert_id = HP.id 
            WHERE HP.id IN(' . $inPeopleId . ') AND C.year_total_count > 0 ' . $queryStringHrPeopleData);

        if ($query->num_rows() > 0) {

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="3" style="width:30px;">#</th>';
            $htmlData .= '<th rowspan="3">Овог, нэр</th>';
            $htmlData .= '<th colspan="3" style="width:80px;" class="text-center">Оны эхнээс</th>';
            $htmlData .= '<th colspan="9" class="text-center">' . date('Y.m.d', strtotime($param['inDate'])) . ' - ' . date('Y.m.d', strtotime($param['outDate'])) . '</th>';
            $htmlData .= '</tr>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Нийт шинжилгээ</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Объект</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Ачаалал</th>';


            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Ирсэн шинжилгээ</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Татгалзсан</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Объект</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Бүрэлдэхүүнтэй</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Бүрэлдэхүүнгүй</th>';

            $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Хэвийн шинжилгээ</th>';
            $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Хугацаа хэтэрсэн</th>';
            $htmlData .= '</tr>';

            $htmlData .= '<tr>';

            $htmlData .= '<th style="width:80px;" class="text-center">Хаагдсан</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хийж байгаа</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хаагдсан</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хийж байгаа</th>';

            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            $i = $sumTotalYearTotalCount = $sumTotalYearObjectCount = $sumTotalYearWeightCount = $sumTotalDateTotalCount = $sumTotalDateObjectCount = $sumTotalReturnCount = $sumTotalMixx1Count = $sumTotalMixx0Count = $sumTotalNormalCountClose = $sumTotalNormalCountHand = $sumTotalCrashCountClose = $sumTotalCrashCountHand = 0;
            foreach ($query->result() as $key => $row) {
                $sumTotalYearTotalCount = $sumTotalYearTotalCount + (int) $row->year_total_count;
                $sumTotalYearObjectCount = $sumTotalYearObjectCount + (int) $row->year_object_count;
                $sumTotalYearWeightCount = $sumTotalYearWeightCount + (int) $row->year_weight_count;
                $sumTotalDateTotalCount = $sumTotalDateTotalCount + (int) $row->date_total_count;
                $sumTotalDateObjectCount = $sumTotalDateObjectCount + (int) $row->date_object_count;
                $sumTotalReturnCount = $sumTotalReturnCount + (int) $row->return_count;
                $sumTotalMixx1Count = $sumTotalMixx1Count + (int) $row->mixx1_count;
                $sumTotalMixx0Count = $sumTotalMixx0Count + (int) $row->mixx0_count;
                $sumTotalNormalCountClose = $sumTotalNormalCountClose + (int) $row->normal_count_close;
                $sumTotalNormalCountHand = $sumTotalNormalCountHand + (int) $row->normal_count_hand;
                $sumTotalCrashCountClose = $sumTotalCrashCountClose + (int) $row->crash_count_close;
                $sumTotalCrashCountHand = $sumTotalCrashCountHand + (int) $row->crash_count_hand;


                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->full_name . '</td>';
                $htmlData .= '<td class="text-center ' . $row->year_total_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . $row->year_total_count . '</a></td>';
                $htmlData .= '<td class="text-center">' . $row->year_object_count . '</td>';
                $htmlData .= '<td class="text-center">' . $row->year_weight_count . '</td>';
                $htmlData .= '<td class="text-center ' . $row->date_total_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->date_total_count . '</a></td>';
                $htmlData .= '<td class="text-center">' . $row->date_object_count . '</td>';
                $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '&solutionId=11' . $isAjaxDepartmentUrl . '\'});">' . $row->return_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->mixx1_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&isMixx=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->mixx1_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->mixx0_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&isMixx=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->mixx0_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->normal_count_close_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->normal_count_close . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->normal_count_hand_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->normal_count_hand . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crash_count_close_class . '"><a href="javascript:;" class="_crime-crash-done" onclick="_initNifsExtra({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=3&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->crash_count_close . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crash_count_hand_class . '"><a href="javascript:;" class="_crime-crash-hand" onclick="_initNifsExtra({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=4&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->crash_count_hand . '</a></td>';

                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';

            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalYearTotalCount > 0 ? $sumTotalYearTotalCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot">' . $sumTotalYearObjectCount . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . $sumTotalYearWeightCount . '</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalDateTotalCount > 0 ? $sumTotalDateTotalCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot">' . $sumTotalDateObjectCount . '</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '&solutionId=11' . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalReturnCount > 0 ? $sumTotalReturnCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&isMixx=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalMixx1Count > 0 ? $sumTotalMixx1Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&isMixx=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalMixx0Count > 0 ? $sumTotalMixx0Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalNormalCountClose > 0 ? $sumTotalNormalCountClose : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalNormalCountHand > 0 ? $sumTotalNormalCountHand : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=3&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalCrashCountClose > 0 ? $sumTotalCrashCountClose : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=4&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalCrashCountHand > 0 ? $sumTotalCrashCountHand : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNotExpert = $this->db->query('
            SELECT 
                NEP.expert_id,
                NE.create_number
            FROM `gaz_nifs_expert` AS NEP 
            INNER JOIN `gaz_nifs_extra` AS NE ON NEP.mod_id = NE.mod_id AND NEP.cont_id = NE.id
            WHERE NEP.expert_id = 0 ' . $queryStringData . '
            ORDER BY NE.create_number ASC');

            if ($queryNotExpert->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Шинжээч сонгоогүй шинжилгээ (' . $queryNotExpert->num_rows() . '): ';

                foreach ($queryNotExpert->result() as $rowNotExpert) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'createNumber=' . $rowNotExpert->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNotExpert->create_number . '</a>';
                }
                $htmlData .= '</div>';
            } else {
                $htmlData .= '<br>';
            }
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
                FROM `gaz_nifs_extra` AS NC
                WHERE NC.partner_id != 0 AND NC.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NC.partner_id
            ) AS YEAR_PARTNER_COUNT ON YEAR_PARTNER_COUNT.partner_id = P.id
            LEFT JOIN (
                SELECT
                    NC.partner_id,
                    COUNT(NC.partner_id) AS date_partner_count
                FROM `gaz_nifs_extra` AS NC
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
                $htmlData .= '<td class="text-center ' . $row->year_partner_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . $row->year_partner_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->date_partner_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->date_partner_count . '</a></td>';
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
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'partnerId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalYearPartnerCount > 0 ? $sumTotalYearPartnerCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'partnerId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalDatePartnerCount > 0 ? $sumTotalDatePartnerCount : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';


            $queryNotPartner = $this->db->query('
            SELECT 
                NC.create_number
            FROM `gaz_nifs_extra` AS NC 
            WHERE NC.partner_id = 0 ' . $queryStringData . '
            ORDER BY NC.create_number ASC');

            if ($queryNotPartner->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ томилсон байгууллага сонгоогүй шинжилгээ (' . $queryNotPartner->num_rows() . '): ';

                foreach ($queryNotPartner->result() as $rowNotPartner) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'createNumber=' . $rowNotPartner->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNotPartner->create_number . '</a>';
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
                FROM `gaz_nifs_extra` AS NC
                WHERE NC.partner_id != 0 AND NC.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NC.partner_id
            ) AS YEAR_PARTNER_COUNT ON YEAR_PARTNER_COUNT.partner_id = P.id
            LEFT JOIN (
                SELECT
                    NC.partner_id,
                    COUNT(NC.partner_id) AS date_partner_count
                FROM `gaz_nifs_extra` AS NC
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
                $htmlData .= '<td class="text-center ' . $row->year_partner_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . $row->year_partner_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->date_partner_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->date_partner_count . '</a></td>';
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

    public function getReportQuestionData_model($param = array()) {
        $queryStringData = $queryStringDepartmentData = $htmlData = $isAjaxDepartmentUrl = '';

        if ($param['departmentId'] > 0) {
            $queryStringData .= ' AND NC.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {
            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NC.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NC.out_date)';
        } elseif ($param['inDate'] != '' and $param['outDate'] == '') {
            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NC.in_date)';
        } elseif ($param['inDate'] == '' and $param['outDate'] != '') {
            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NC.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {
            $queryStringData .= ' AND NC.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $query = $this->db->query('
            SELECT
                Q.id,
                Q.title,
                E.question_count,
                IF(E.question_count > 0, \'_row-more\', \'\') AS question_count_class,
                S1.sex_1_count,
                IF(S1.sex_1_count > 0, \'_row-more\', \'\') AS sex_1_count_class,
                S0.sex_0_count,
                IF(S0.sex_0_count > 0, \'_row-more\', \'\') AS sex_0_count_class,
                I.age_infinitive_count,
                IF(I.age_infinitive_count > 0, \'_row-more\', \'\') AS age_infinitive_count_class,
                A0_1.age_count_0_1,
                IF(A0_1.age_count_0_1 > 0, \'_row-more\', \'\') AS age_count_0_1_class,
                A1_4.age_count_1_4,
                IF(A1_4.age_count_1_4 > 0, \'_row-more\', \'\') AS age_count_1_4_class,
                A5_9.age_count_5_9,
                IF(A5_9.age_count_5_9 > 0, \'_row-more\', \'\') AS age_count_5_9_class,
                A10_14.age_count_10_14,
                IF(A10_14.age_count_10_14 > 0, \'_row-more\', \'\') AS age_count_10_14_class,
                A15_19.age_count_15_19,
                IF(A15_19.age_count_15_19 > 0, \'_row-more\', \'\') AS age_count_15_19_class,
                A20_24.age_count_20_24,
                IF(A20_24.age_count_20_24 > 0, \'_row-more\', \'\') AS age_count_20_24_class,
                A25_29.age_count_25_29,
                IF(A25_29.age_count_25_29 > 0, \'_row-more\', \'\') AS age_count_25_29_class,
                A30_34.age_count_30_34,
                IF(A30_34.age_count_30_34 > 0, \'_row-more\', \'\') AS age_count_30_34_class,
                A35_39.age_count_35_39,
                IF(A35_39.age_count_35_39 > 0, \'_row-more\', \'\') AS age_count_35_39_class,
                A40_44.age_count_40_44,
                IF(A40_44.age_count_40_44 > 0, \'_row-more\', \'\') AS age_count_40_44_class,
                A45_49.age_count_45_49,
                IF(A45_49.age_count_45_49 > 0, \'_row-more\', \'\') AS age_count_45_49_class,
                A50_54.age_count_50_54,
                IF(A50_54.age_count_50_54 > 0, \'_row-more\', \'\') AS age_count_50_54_class,
                A55_59.age_count_55_59,
                IF(A55_59.age_count_55_59 > 0, \'_row-more\', \'\') AS age_count_55_59_class,
                A60_64.age_count_60_64,
                IF(A60_64.age_count_60_64 > 0, \'_row-more\', \'\') AS age_count_60_64_class,
                A65.age_count_65,
                IF(A65.age_count_65 > 0, \'_row-more\', \'\') AS age_count_65_class
            FROM `gaz_nifs_question` AS Q
            INNER JOIN (
                SELECT 
                    NC.question_id,
                    COUNT(NC.question_id) AS question_count 
                FROM `gaz_nifs_extra` AS NC 
                WHERE 1 = 1
                GROUP BY NC.question_id
            ) AS E ON E.question_id = Q.id
            LEFT JOIN (
                SELECT 
                    NC.question_id,
                    COUNT(NC.sex) AS sex_1_count 
                FROM `gaz_nifs_extra` AS NC 
                WHERE NC.sex = 1 ' . $queryStringData . ' 
                GROUP BY NC.sex
            ) AS S1 ON S1.question_id = Q.id
            LEFT JOIN (
                SELECT 
                    NC.question_id,
                    COUNT(NC.sex) AS sex_0_count 
                FROM `gaz_nifs_extra` AS NC 
                WHERE NC.sex = 0 AND NC.is_age_infinitive = 0 ' . $queryStringData . ' 
                GROUP BY NC.sex
            ) AS S0 ON S0.question_id = Q.id
            LEFT JOIN (
                SELECT 
                    NC.question_id,
                    COUNT(NC.is_age_infinitive) AS age_infinitive_count
                FROM `gaz_nifs_extra` AS NC 
                WHERE NC.is_age_infinitive = 1 ' . $queryStringData . ' 
                GROUP BY NC.is_age_infinitive
            ) AS I ON I.question_id = Q.id
            LEFT JOIN (
                SELECT 
                    NC.question_id,
                    COUNT(NC.question_id) AS age_count_0_1
                FROM `gaz_nifs_extra` AS NC 
                WHERE NC.is_age_infinitive = 0 AND NC.age >= 0 AND NC.age <= 1 ' . $queryStringData . ' 
                GROUP BY NC.question_id
            ) AS A0_1 ON A0_1.question_id = Q.id
            LEFT JOIN (
                SELECT 
                    NC.question_id,
                    COUNT(NC.question_id) AS age_count_1_4
                FROM `gaz_nifs_extra` AS NC 
                WHERE NC.is_age_infinitive = 0 AND NC.age > 1 AND NC.age <= 4 ' . $queryStringData . ' 
                GROUP BY NC.question_id
            ) AS A1_4 ON A1_4.question_id = Q.id
            LEFT JOIN (
                SELECT 
                    NC.question_id,
                    COUNT(NC.question_id) AS age_count_5_9
                FROM `gaz_nifs_extra` AS NC 
                WHERE NC.is_age_infinitive = 0 AND NC.age >= 5 AND NC.age <= 9 ' . $queryStringData . ' 
                GROUP BY NC.question_id
            ) AS A5_9 ON A5_9.question_id = Q.id
            LEFT JOIN (
                SELECT 
                    NC.question_id,
                    COUNT(NC.question_id) AS age_count_10_14
                FROM `gaz_nifs_extra` AS NC 
                WHERE NC.is_age_infinitive = 0 AND NC.age >= 10 AND NC.age <= 14 ' . $queryStringData . ' 
                GROUP BY NC.question_id
            ) AS A10_14 ON A10_14.question_id = Q.id
            LEFT JOIN (
                SELECT 
                    NC.question_id,
                    COUNT(NC.question_id) AS age_count_15_19
                FROM `gaz_nifs_extra` AS NC 
                WHERE NC.is_age_infinitive = 0 AND NC.age >= 15 AND NC.age <= 19 ' . $queryStringData . ' 
                GROUP BY NC.question_id
            ) AS A15_19 ON A15_19.question_id = Q.id
            LEFT JOIN (
                SELECT 
                    NC.question_id,
                    COUNT(NC.question_id) AS age_count_20_24
                FROM `gaz_nifs_extra` AS NC 
                WHERE NC.is_age_infinitive = 0 AND NC.age >= 20 AND NC.age <= 24 ' . $queryStringData . ' 
                GROUP BY NC.question_id
            ) AS A20_24 ON A20_24.question_id = Q.id
            LEFT JOIN (
                SELECT 
                    NC.question_id,
                    COUNT(NC.question_id) AS age_count_25_29
                FROM `gaz_nifs_extra` AS NC 
                WHERE NC.is_age_infinitive = 0 AND NC.age >= 25 AND NC.age <= 29 ' . $queryStringData . ' 
                GROUP BY NC.question_id
            ) AS A25_29 ON A25_29.question_id = Q.id
            LEFT JOIN (
                SELECT 
                    NC.question_id,
                    COUNT(NC.question_id) AS age_count_30_34
                FROM `gaz_nifs_extra` AS NC 
                WHERE NC.is_age_infinitive = 0 AND NC.age >= 30 AND NC.age <= 34 ' . $queryStringData . ' 
                GROUP BY NC.question_id
            ) AS A30_34 ON A30_34.question_id = Q.id
            LEFT JOIN (
                SELECT 
                    NC.question_id,
                    COUNT(NC.question_id) AS age_count_35_39
                FROM `gaz_nifs_extra` AS NC 
                WHERE NC.is_age_infinitive = 0 AND NC.age >= 35 AND NC.age <= 39 ' . $queryStringData . ' 
                GROUP BY NC.question_id
            ) AS A35_39 ON A35_39.question_id = Q.id
            LEFT JOIN (
                SELECT 
                    NC.question_id,
                    COUNT(NC.question_id) AS age_count_40_44
                FROM `gaz_nifs_extra` AS NC 
                WHERE NC.is_age_infinitive = 0 AND NC.age >= 40 AND NC.age <= 44 ' . $queryStringData . ' 
                GROUP BY NC.question_id
            ) AS A40_44 ON A40_44.question_id = Q.id
            LEFT JOIN (
                SELECT 
                    NC.question_id,
                    COUNT(NC.question_id) AS age_count_45_49
                FROM `gaz_nifs_extra` AS NC 
                WHERE NC.is_age_infinitive = 0 AND NC.age >= 45 AND NC.age <= 49 ' . $queryStringData . ' 
                GROUP BY NC.question_id
            ) AS A45_49 ON A45_49.question_id = Q.id
            LEFT JOIN (
                SELECT 
                    NC.question_id,
                    COUNT(NC.question_id) AS age_count_50_54
                FROM `gaz_nifs_extra` AS NC 
                WHERE NC.is_age_infinitive = 0 AND NC.age >= 50 AND NC.age <= 54 ' . $queryStringData . ' 
                GROUP BY NC.question_id
            ) AS A50_54 ON A50_54.question_id = Q.id
            LEFT JOIN (
                SELECT 
                    NC.question_id,
                    COUNT(NC.question_id) AS age_count_55_59
                FROM `gaz_nifs_extra` AS NC 
                WHERE NC.is_age_infinitive = 0 AND NC.age >= 55 AND NC.age <= 59 ' . $queryStringData . ' 
                GROUP BY NC.question_id
            ) AS A55_59 ON A55_59.question_id = Q.id
            LEFT JOIN (
                SELECT 
                    NC.question_id,
                    COUNT(NC.question_id) AS age_count_60_64
                FROM `gaz_nifs_extra` AS NC 
                WHERE NC.is_age_infinitive = 0 AND NC.age >= 60 AND NC.age <= 64 ' . $queryStringData . ' 
                GROUP BY NC.question_id
            ) AS A60_64 ON A60_64.question_id = Q.id
            LEFT JOIN (
                SELECT 
                    NC.question_id,
                    COUNT(NC.question_id) AS age_count_65
                FROM `gaz_nifs_extra` AS NC 
                WHERE NC.is_age_infinitive = 0 AND NC.age >= 65 ' . $queryStringData . ' 
                GROUP BY NC.question_id
            ) AS A65 ON A65.question_id = Q.id
            WHERE Q.type_id = 1 AND Q.is_active = 1');

        if ($query->num_rows() > 0) {

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th style="min-width:150px;">Нэр</th>';
            $htmlData .= '<th style="width:50px; padding-left:1px; padding-right:1px;" class="text-center">Нийт</th>';
            $htmlData .= '<th style="width:50px; padding-left:1px; padding-right:1px;" class="text-center">Эр</th>';
            $htmlData .= '<th style="width:50px; padding-left:1px; padding-right:1px;" class="text-center">Эм</th>';
            $htmlData .= '<th style="width:80px; padding-left:1px; padding-right:1px;" class="text-center">Тодорхойгүй</th>';
            $htmlData .= '<th style="width:50px; padding-left:1px; padding-right:1px;" class="text-center">0 < 1</th>';
            $htmlData .= '<th style="width:50px; padding-left:1px; padding-right:1px;" class="text-center">1-4</th>';
            $htmlData .= '<th style="width:50px; padding-left:1px; padding-right:1px;" class="text-center">5-9</th>';
            $htmlData .= '<th style="width:50px; padding-left:1px; padding-right:1px;" class="text-center">10-14</th>';
            $htmlData .= '<th style="width:50px; padding-left:1px; padding-right:1px;" class="text-center">15-19</th>';
            $htmlData .= '<th style="width:50px; padding-left:1px; padding-right:1px;" class="text-center">20-24</th>';
            $htmlData .= '<th style="width:50px; padding-left:1px; padding-right:1px;" class="text-center">25-29</th>';
            $htmlData .= '<th style="width:50px; padding-left:1px; padding-right:1px;" class="text-center">30-34</th>';
            $htmlData .= '<th style="width:50px; padding-left:1px; padding-right:1px;" class="text-center">35-39</th>';
            $htmlData .= '<th style="width:50px; padding-left:1px; padding-right:1px;" class="text-center">40-44</th>';
            $htmlData .= '<th style="width:50px; padding-left:1px; padding-right:1px;" class="text-center">45-49</th>';
            $htmlData .= '<th style="width:50px; padding-left:1px; padding-right:1px;" class="text-center">50-54</th>';
            $htmlData .= '<th style="width:50px; padding-left:1px; padding-right:1px;" class="text-center">55-59</th>';
            $htmlData .= '<th style="width:50px; padding-left:1px; padding-right:1px;" class="text-center">60-64</th>';
            $htmlData .= '<th style="width:40px; padding-left:1px; padding-right:1px;" class="text-center">65 < </th>';
            $htmlData .= '</tr>';
            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            $i = $sumQuestion = $sumSex1 = $sumSex0 = $sumAgeInfinitive = $sumAge0_1 = $sumAge1_4 = $sumAge5_9 = $sumAge10_14 = $sumAge15_19 = $sumAge20_24 = $sumAge25_29 = $sumAge30_34 = $sumAge35_39 = $sumAge40_44 = $sumAge45_49 = $sumAge50_54 = $sumAge55_59 = $sumAge60_64 = $sumAge65 = 0;
            foreach ($query->result() as $key => $row) {

                $sumQuestion = $sumQuestion + $row->question_count;
                $sumSex1 = $sumSex1 + $row->sex_1_count;
                $sumSex0 = $sumSex0 + $row->sex_0_count;
                $sumAgeInfinitive = $sumAgeInfinitive + $row->age_infinitive_count;
                $sumAge0_1 = $sumAge0_1 + $row->age_count_0_1;
                $sumAge1_4 = $sumAge1_4 + $row->age_count_1_4;
                $sumAge5_9 = $sumAge5_9 + $row->age_count_5_9;
                $sumAge10_14 = $sumAge10_14 + $row->age_count_10_14;
                $sumAge15_19 = $sumAge15_19 + $row->age_count_15_19;
                $sumAge20_24 = $sumAge20_24 + $row->age_count_20_24;
                $sumAge25_29 = $sumAge25_29 + $row->age_count_25_29;
                $sumAge30_34 = $sumAge30_34 + $row->age_count_30_34;
                $sumAge35_39 = $sumAge35_39 + $row->age_count_35_39;
                $sumAge40_44 = $sumAge40_44 + $row->age_count_40_44;
                $sumAge45_49 = $sumAge45_49 + $row->age_count_45_49;
                $sumAge50_54 = $sumAge50_54 + $row->age_count_50_54;
                $sumAge55_59 = $sumAge55_59 + $row->age_count_55_59;
                $sumAge60_64 = $sumAge60_64 + $row->age_count_60_64;
                $sumAge65 = $sumAge65 + $row->age_count_65;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->question_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'questionId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->question_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->sex_1_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'questionId=' . $row->id . '&sex=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->sex_1_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->sex_0_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'questionId=' . $row->id . '&sex=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->sex_0_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_infinitive_count_class . '">' . $row->age_infinitive_count . '</td>';
                $htmlData .= '<td class="text-center ' . $row->age_count_0_1_class . '">' . $row->age_count_0_1 . '</td>';
                $htmlData .= '<td class="text-center ' . $row->age_count_1_4_class . '">' . $row->age_count_1_4 . '</td>';
                $htmlData .= '<td class="text-center ' . $row->age_count_5_9_class . '">' . $row->age_count_5_9 . '</td>';
                $htmlData .= '<td class="text-center ' . $row->age_count_10_14_class . '">' . $row->age_count_10_14 . '</td>';
                $htmlData .= '<td class="text-center ' . $row->age_count_15_19_class . '">' . $row->age_count_15_19 . '</td>';
                $htmlData .= '<td class="text-center ' . $row->age_count_20_24_class . '">' . $row->age_count_20_24 . '</td>';
                $htmlData .= '<td class="text-center ' . $row->age_count_25_29_class . '">' . $row->age_count_25_29 . '</td>';
                $htmlData .= '<td class="text-center ' . $row->age_count_30_34_class . '">' . $row->age_count_30_34 . '</td>';
                $htmlData .= '<td class="text-center ' . $row->age_count_35_39_class . '">' . $row->age_count_35_39 . '</td>';
                $htmlData .= '<td class="text-center ' . $row->age_count_40_44_class . '">' . $row->age_count_40_44 . '</td>';
                $htmlData .= '<td class="text-center ' . $row->age_count_45_49_class . '">' . $row->age_count_45_49 . '</td>';
                $htmlData .= '<td class="text-center ' . $row->age_count_50_54_class . '">' . $row->age_count_50_54 . '</td>';
                $htmlData .= '<td class="text-center ' . $row->age_count_55_59_class . '">' . $row->age_count_55_59 . '</td>';
                $htmlData .= '<td class="text-center ' . $row->age_count_60_64_class . '">' . $row->age_count_60_64 . '</td>';
                $htmlData .= '<td class="text-center ' . $row->age_count_65_class . '">' . $row->age_count_65 . '</td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td>Нийт</td>';
            $htmlData .= '<td class="text-center">' . $sumQuestion . '</td>';
            $htmlData .= '<td class="text-center">' . $sumSex1 . '</td>';
            $htmlData .= '<td class="text-center">' . $sumSex0 . '</td>';
            $htmlData .= '<td class="text-center">' . $sumAgeInfinitive . '</td>';
            $htmlData .= '<td class="text-center">' . $sumAge0_1 . '</td>';
            $htmlData .= '<td class="text-center">' . $sumAge1_4 . '</td>';
            $htmlData .= '<td class="text-center">' . $sumAge5_9 . '</td>';
            $htmlData .= '<td class="text-center">' . $sumAge10_14 . '</td>';
            $htmlData .= '<td class="text-center">' . $sumAge15_19 . '</td>';
            $htmlData .= '<td class="text-center">' . $sumAge20_24 . '</td>';
            $htmlData .= '<td class="text-center">' . $sumAge25_29 . '</td>';
            $htmlData .= '<td class="text-center">' . $sumAge30_34 . '</td>';
            $htmlData .= '<td class="text-center">' . $sumAge35_39 . '</td>';
            $htmlData .= '<td class="text-center">' . $sumAge40_44 . '</td>';
            $htmlData .= '<td class="text-center">' . $sumAge45_49 . '</td>';
            $htmlData .= '<td class="text-center">' . $sumAge50_54 . '</td>';
            $htmlData .= '<td class="text-center">' . $sumAge55_59 . '</td>';
            $htmlData .= '<td class="text-center">' . $sumAge60_64 . '</td>';
            $htmlData .= '<td class="text-center">' . $sumAge65 . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';
        } else {
            $htmlData .= '<div class="panel-body">';
            $htmlData .= '<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Хайлтын утгад тохирох мэдээлэл олдсонгүй.</div>';
            $htmlData .= '</div>';
        }

        return $htmlData;
    }

    public function dataUpdate_model1($param = array()) {

        $query = $this->db->query('
                SELECT 
                    NE.id, 
                    NE.research_type_id, 
                    NE.motive_id, 
                    NE.partner_id,
                    NE.type_id,
                    NE.cat_id,
                    NE.question_id,
                    NE.solution_id,
                    NE.close_type_id,
                    NE.param
                FROM `gaz_nifs_extra` AS NE');

        foreach ($query->result() as $key => $row) {

            $this->nifsResearchTypeData = $this->nifsResearchType->getData_model(array('selectedId' => $row->research_type_id));
            $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $row->motive_id));
            $this->partnerData = $this->partner->getData_model(array('selectedId' => $row->partner_id));
            $this->nifsCrimeTypeData = $this->nifsCrimeType->getData_model(array('selectedId' => $row->type_id));
            $this->categoryData = $this->category->getData_model(array('selectedId' => $row->cat_id));
            $this->questionData = $this->nifsQuestion->getData_model(array('selectedId' => $row->question_id));

            $this->nifsSolutionData = $this->nifsSolution->getData_model(array('selectedId' => $row->solution_id));
            $this->nifsCloseTypeData = $this->nifsCloseType->getData_model(array('selectedId' => $row->close_type_id));

            $data = array(
                'param' => json_encode(array(
                    'researchType' => ($this->nifsResearchTypeData != false ? $this->nifsResearchTypeData->title : ''),
                    'motive' => ($this->nifsMotiveData != false ? $this->nifsMotiveData->title : ''),
                    'partner' => ($this->partnerData != false ? $this->partnerData->title : ''),
                    'type' => ($this->nifsCrimeTypeData != false ? $this->nifsCrimeTypeData->title : ''),
                    'category' => ($this->categoryData != false ? $this->categoryData->title : ''),
                    'question' => ($this->questionData != false ? $this->questionData->title : ''),
                    'solution' => ($this->nifsSolutionData != false ? $this->nifsSolutionData->title : ''),
                    'closeType' => ($this->nifsCloseTypeData != false ? $this->nifsCloseTypeData->title : ''))));

            $this->db->where('id', $row->id);
            if ($this->db->update('gaz_nifs_extra', $data)) {

                echo '<pre>';
                var_dump(json_decode($data['param']));
                echo '</pre>';
            }
        }
    }
    
    public function dataUpdate_model($param = array()) {

        $this->query = $this->db->query('
                SELECT 
                    id, 
                    in_date, 
                    year
                FROM `gaz_nifs_extra`');

        foreach ($this->query->result() as $key => $row) {
            if ((date('Ymd', strtotime($row->in_date)) >= date('Ymd', strtotime('2018-12-08'))) and date('Ymd', strtotime($row->in_date)) <= date('Ymd', strtotime('2019-12-06'))) {
                
                $this->db->where('id', $row->id);
                $this->db->update('gaz_nifs_extra', array('year' => '2019'));
                
            } else if ((date('Ymd', strtotime($row->in_date)) >= date('Ymd', strtotime('2019-12-07'))) and date('Ymd', strtotime($row->in_date)) <= date('Ymd', strtotime('2020-12-06'))) {
                
                $this->db->where('id', $row->id);
                $this->db->update('gaz_nifs_extra', array('year' => '2020'));
                
            }
            
        }
    }

}
