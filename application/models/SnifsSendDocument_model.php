<?php

class SnifsSendDocument_model extends CI_Model {

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


        $this->perPage = 2;
        $this->hrPeopleExpertPositionId = '5,6,7,10,3,27,13';
        $this->hrPeopleExpertDepartmentId = '5';

        $this->hrPeopleDoctorExpertPositionId = '5,6,7,10';
        $this->hrPeopleDoctorExpertDepartmentId = '4,18';

        $this->nifsCrimeTypeId = 354;
        $this->nifsQuestionCatId = 372;
        $this->nifsSolutionCatId = 360;
        $this->nifsCloseTypeCatId = 366;
        $this->nifsResearchTypeCatId = 381;
        $this->nifsMotiveCatId = 439;
        $this->doctorDepartmentId = '4,18'; //шүүх эмнэлэг, орон нутгийн шүүхийн эмнэлэгийн эмч нар

        $this->modId = 81;
        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->isActiveDepartment = 'is_active_nifs_extra';
        $this->reportDefaultDayInterval = 7;

        if ($this->nifsDepartmentId == 7) {
            $this->nifsDepartmentId = 5;
        }
    }

    public function oldAddFormData_model($param = array('id' => 0)) {

        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => 81,
            'cat_id' => 0,
            'is_mixx' => 1,
            'research_type_id' => 0,
            'motive_id' => 0,
            'create_number' => '',
            'in_date' => date('Y-m-d H:i:s'),
            'out_date' => date('Y-m-d H:i:s'),
            'fname' => '',
            'lname' => '',
            'age' => '',
            'is_age_infinitive' => 0,
            'sex' => 1,
            'partner_id' => 0,
            'agent_name' => '',
            'description' => '',
            'type_id' => '',
            'pre_create_number' => '',
            'value' => '',
            'object' => '',
            'object_count' => 0,
            'question_id' => 0,
            'question' => '',
            'expert_doctor_id' => 0,
            'expert' => '',
            'weight' => '',
            'protocol_number' => '',
            'protocol_in_date' => date('Y-m-d H:i:s'),
            'protocol_out_date' => date('Y-m-d H:i:s'),
            'department_id' => 0,
            'param' => '',
            'param_close' => date('Y-m-d H:i:s'),
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s'),
            'created_user_id' => '',
            'modified_user_id' => '',
            'order_num' => '',
            'year' => '',
            'close_date' => '',
            'close_description' => '',
            'close_type_id' => '',
            'solution_id' => '',
            'extra_expert_value' => '',
            'register' => '',
            'theme_layout_id' => '',
            'work_id' => 0
        )));
    }

    public function oldEditFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cat_id,
                is_mixx,
                research_type_id,
                motive_id,
                create_number,
                in_date,
                out_date,
                fname,
                lname,
                age,
                is_age_infinitive,
                sex,
                partner_id,
                agent_name,
                description,
                type_id,
                pre_create_number,
                value,
                object,
                object_count,
                question_id,
                question,
                expert_doctor_id,
                expert,
                weight,
                protocol_number,
                DATE(protocol_in_date) AS protocol_in_date,
                DATE(protocol_out_date) AS protocol_out_date,
                department_id,
                param,
                param_close,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id,
                order_num,
                year,
                close_date,
                close_description,
                close_type_id,
                solution_id,
                extra_expert_value,
                register,
                theme_layout_id
            FROM `gaz_nifs_send_document`
            WHERE id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::oldAddFormData_model();
    }

    public function oldListsCount_model($param = array()) {

        $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and ! $this->auth->your->read) {
            $queryString .= ' AND NSD.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NSD.created_user_id = -1';
        }


        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $queryString .= ' AND LOWER(NSD.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $queryString .= ' AND LOWER(NSD.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Регистрийн дугаар
                        $queryString .= ' AND LOWER(NSD.register) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Утас
                        $queryString .= ' AND LOWER(NSD.register) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Тайлбар
                        $queryString .= ' AND (LOWER(NSD.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NSD.value) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;

                case 6: {   //Албан хаагч
                        $queryString .= ' AND LOWER(NSD.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 7: {   //Дүгнэлтийн утга
                        $queryString .= ' AND LOWER(NSD.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 8: {   //Объект
                        $queryString .= ' AND LOWER(NSD.object) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 9: {   //Нэмэлт асуулт
                        $queryString .= ' AND LOWER(NSD.question) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 10: {   //Хариу тайлбар
                        $queryString .= ' AND LOWER(NSD.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' AND (
                        LOWER(NSD.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NSD.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NSD.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NSD.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        (LOWER(NSD.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NSD.value) LIKE LOWER(\'%' . $param['keyword'] . '%\')) OR
                        LOWER(NSD.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NSD.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NSD.object) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NSD.question) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NSD.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                        break;
                    }
            }
        }

        if ($param['createNumber'] != '') {
            $queryString .= ' AND NSD.create_number = ' . $param['createNumber'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NSD.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NSD.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NSD.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NSD.out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NSD.close_date) AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NSD.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NSD.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NSD.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND NSD.year = \'2019\'';
        }

        if ($param['typeId'] != 0) {

            $queryString .= ' AND NSD.type_id = ' . $param['typeId'];
        }

        if ($param['closeTypeId'] > 0) {
            $queryString .= ' AND NSD.close_type_id = ' . $param['closeTypeId'];
        }

        if ($param['questionId'] > 0) {
            $queryString .= ' AND NSD.question_id = ' . $param['questionId'];
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NSD.close_type_id != 0 AND DATE(NSD.close_date) <= DATE(NSD.out_date)';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NSD.close_type_id = 0 AND CURDATE() <= DATE(NSD.out_date)';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NSD.close_type_id != 0 AND DATE(NSD.close_date) > DATE(NSD.out_date)';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NSD.close_type_id = 0 AND CURDATE() > DATE(NSD.out_date)';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND DATE(NSD.protocol_out_date) < DATE(NSD.in_date)';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NSD.close_type_id = 0';
        }

        if ($param['expertId'] != 0) {
            $query = $this->db->query('
                SELECT 
                    NSD.id
                FROM `gaz_nifs_send_document` AS NSD
                INNER JOIN `gaz_nifs_expert` AS NE ON NSD.id = NE.cont_id AND NSD.mod_id = NE.mod_id
                WHERE NE.expert_id = ' . $param['expertId'] . $queryString);
        } else {
            $query = $this->db->query('
                SELECT 
                    NSD.id
                FROM `gaz_nifs_send_document` AS NSD
                WHERE 1 = 1 ' . $queryString);
        }
        return $query->num_rows();
    }

    public function oldLists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $data = array();
        $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and ! $this->auth->your->read) {
            $queryString .= ' AND NSD.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NSD.created_user_id = -1';
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $queryString .= ' AND LOWER(NSD.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $queryString .= ' AND LOWER(NSD.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Регистрийн дугаар
                        $queryString .= ' AND LOWER(NSD.register) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Утас
                        $queryString .= ' AND LOWER(NSD.register) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Тайлбар
                        $queryString .= ' AND (LOWER(NSD.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NSD.value) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;

                case 6: {   //Албан хаагч
                        $queryString .= ' AND LOWER(NSD.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 7: {   //Дүгнэлтийн утга
                        $queryString .= ' AND LOWER(NSD.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 8: {   //Объект
                        $queryString .= ' AND LOWER(NSD.object) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 9: {   //Нэмэлт асуулт
                        $queryString .= ' AND LOWER(NSD.question) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 10: {   //Хариу тайлбар
                        $queryString .= ' AND LOWER(NSD.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' AND (
                        LOWER(NSD.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NSD.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NSD.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NSD.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        (LOWER(NSD.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NSD.value) LIKE LOWER(\'%' . $param['keyword'] . '%\')) OR
                        LOWER(NSD.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NSD.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NSD.object) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NSD.question) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NSD.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                        break;
                    }
            }
        }

        if ($param['createNumber'] != '') {
            $queryString .= ' AND NSD.create_number = ' . $param['createNumber'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NSD.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NSD.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NSD.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NSD.out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NSD.close_date) AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NSD.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NSD.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NSD.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND NSD.year = \'2019\'';
        }

        if ($param['typeId'] != 0) {

            $queryString .= ' AND NSD.type_id = ' . $param['typeId'];
        }

        if ($param['closeTypeId'] > 0) {
            $queryString .= ' AND NSD.close_type_id = ' . $param['closeTypeId'];
        }

        if ($param['questionId'] > 0) {
            $queryString .= ' AND NSD.question_id = ' . $param['questionId'];
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NSD.close_type_id != 0 AND DATE(NSD.close_date) <= DATE(NSD.out_date)';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NSD.close_type_id = 0 AND CURDATE() <= DATE(NSD.out_date)';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NSD.close_type_id != 0 AND DATE(NSD.close_date) > DATE(NSD.out_date)';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NSD.close_type_id = 0 AND CURDATE() > DATE(NSD.out_date)';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND DATE(NSD.protocol_out_date) < DATE(NSD.in_date)';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NSD.close_type_id = 0';
        }

        if ($param['expertId'] != 0) {

            $query = $this->db->query('
            SELECT
                NSD.id,
                NSD.mod_id,
                NSD.cat_id,
                NSD.is_mixx,
                NSD.research_type_id,
                NSD.motive_id,
                NSD.create_number,
                CONCAT("Э: ", DATE_FORMAT(NSD.in_date, \'%Y-%m-%d\'), "<br>", "Д: ", DATE_FORMAT(NSD.out_date, \'%Y-%m-%d\')) AS in_out_date,
                (IF(NSD.lname != \'\', CONCAT(NSD.lname, "-н ", "<strong>", NSD.fname, "</strong>"), NSD.fname)) AS full_name,
                (IF(NSD.register != \'\', CONCAT(NSD.register), \'\')) AS register,
                NSD.age,
                (CASE 
                        WHEN (NSD.is_age_infinitive = 1) THEN \'\'
                        WHEN (NSD.is_age_infinitive = 0 AND NSD.age > 0 AND NSD.age < 1) THEN CONCAT(", ", CAST(NSD.age AS DECIMAL(10,1)), " сартай")
                        ELSE IF(NSD.is_age_infinitive = 0 AND NSD.age >= 1, CONCAT(", ", CAST(NSD.age AS DECIMAL(10)), " настай"), "")
                    END) AS age,
                (IF(NSD.sex = 1, \', эр\', \', эм\')) AS sex,
                NSD.partner_id,
                NSD.agent_name,
                NSD.description,
                NSD.type_id,
                NSD.pre_create_number,
                NSD.value,
                NSD.object,
                NSD.object_count,
                NSD.question_id,
                NSD.question,
                NSD.expert_doctor_id,
                NSD.expert,
                NSD.weight,
                CONCAT("Э: ", DATE_FORMAT(NSD.in_date, \'%Y-%m-%d\'), "<br>", "Д: ", DATE_FORMAT(NSD.out_date, \'%Y-%m-%d\')) AS protocol_date,
                NSD.department_id,
                NSD.param,
                NSD.param_close,
                NSD.created_date,
                NSD.modified_date,
                NSD.created_user_id,
                NSD.modified_user_id,
                NSD.order_num,
                NSD.is_active,
                NSD.year,
                DATE(NSD.close_date) AS close_date,
                NSD.close_description,
                NSD.close_type_id,
                NSD.solution_id,
                NSD.extra_expert_value,
                NSD.register,
                NSD.param,
                (CASE 
                    WHEN (NSD.close_type_id != 0 AND NSD.close_date > NSD.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                    WHEN NSD.close_type_id = 0 AND NOW() > NSD.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                    ELSE \'background-color: transparent;\'
                END) AS row_status
            FROM `gaz_nifs_send_document` AS NSD
            INNER JOIN `gaz_nifs_expert` AS NE ON NSD.id = NE.cont_id AND NSD.mod_id = NE.mod_id
            WHERE NE.expert_id = ' . $param['expertId'] . $queryString . '
            ORDER BY NSD.create_number DESC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        } else {
            $query = $this->db->query('
            SELECT
                NSD.id,
                NSD.mod_id,
                NSD.cat_id,
                NSD.is_mixx,
                NSD.research_type_id,
                NSD.motive_id,
                NSD.create_number,
                CONCAT("Э: ", DATE_FORMAT(NSD.in_date, \'%Y-%m-%d\'), "<br>", "Д: ", DATE_FORMAT(NSD.out_date, \'%Y-%m-%d\')) AS in_out_date,
                (IF(NSD.lname != \'\', CONCAT(NSD.lname, "-н ", "<strong>", NSD.fname, "</strong>"), NSD.fname)) AS full_name,
                (IF(NSD.register != \'\', CONCAT(NSD.register), \'\')) AS register,
                NSD.age,
                (CASE 
                        WHEN (NSD.is_age_infinitive = 1) THEN \'\'
                        WHEN (NSD.is_age_infinitive = 0 AND NSD.age > 0 AND NSD.age < 1) THEN CONCAT(", ", CAST(NSD.age AS DECIMAL(10,1)), " сартай")
                        ELSE IF(NSD.is_age_infinitive = 0 AND NSD.age >= 1, CONCAT(", ", CAST(NSD.age AS DECIMAL(10)), " настай"), "")
                    END) AS age,
                (IF(NSD.sex = 1, \', эр\', \', эм\')) AS sex,
                NSD.partner_id,
                NSD.agent_name,
                NSD.description,
                NSD.type_id,
                NSD.pre_create_number,
                NSD.value,
                NSD.object,
                NSD.object_count,
                NSD.question_id,
                NSD.question,
                NSD.expert_doctor_id,
                NSD.expert,
                NSD.weight,
                CONCAT("Э: ", DATE_FORMAT(NSD.in_date, \'%Y-%m-%d\'), "<br>", "Д: ", DATE_FORMAT(NSD.out_date, \'%Y-%m-%d\')) AS protocol_date,
                NSD.department_id,
                NSD.param,
                NSD.param_close,
                NSD.created_date,
                NSD.modified_date,
                NSD.created_user_id,
                NSD.modified_user_id,
                NSD.order_num,
                NSD.is_active,
                NSD.year,
                DATE(NSD.close_date) AS close_date,
                NSD.close_description,
                NSD.close_type_id,
                NSD.solution_id,
                NSD.extra_expert_value,
                NSD.register,
                NSD.param,
                (CASE 
                    WHEN (NSD.close_type_id != 0 AND NSD.close_date > NSD.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                    WHEN NSD.close_type_id = 0 AND NOW() > NSD.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                    ELSE \'background-color: transparent;\'
                END) AS row_status
            FROM `gaz_nifs_send_document` AS NSD
            WHERE 1 = 1 ' . $queryString . '
            ORDER BY NSD.create_number DESC
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
                    'full_name' => $row->full_name . '<br>' . $row->register . $row->age . $row->sex,
                    'partner' => $param->partner . ' ' . $row->agent_name,
                    'short_value' => $row->value,
                    'expert_status' => '',
                    'doctor' => $param->expertDoctor . ' ' . $row->extra_expert_value,
                    'expert' => $row->expert . '<br><strong>' . $param->type . '</strong>',
                    'category' => $param->type,
                    'close_type' => ($row->close_type_id > 0 ? '<strong>' . $row->close_date . '</strong><br>' . $row->close_description : ''),
                    'description' => $row->description,
                    'row_status' => $row->row_status
                ));
            }
        }
        return array('data' => $data, 'search' => self::oldSearchKeywordView_model());
    }

    public function oldSearchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {

        $this->string = $this->showResetBtn = '';

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

        if ($this->input->get('typeId')) {
            $this->nifsCrimeTypeData = $this->nifsCrimeType->getData_model(array('selectedId' => $this->input->get('typeId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCrimeTypeData->title . '</span>';
            $this->string .= form_hidden('motiveId', $this->input->get('typeId'));
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

        if ($this->input->get('expertId')) {
            if ($this->input->get('expertId') != 'all') {
                $this->expertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->get('expertId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->expertData->full_name . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүх эмч</span>';
            }

            $this->string .= form_hidden('expertId', $this->input->get('expertId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('closeTypeId')) {
            $this->nifsCloseType = $this->nifsCloseType->getData_model(array('selectedId' => $this->input->get('closeTypeId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCloseType->title . '</span>';
            $this->string .= form_hidden('closeTypeId', $this->input->get('closeTypeId'));
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

        if ($this->input->get('departmentId')) {

            $this->hrPeopleDepartmentData = $this->hrPeopleDepartment->getData_model(array('selectedId' => $this->input->get('departmentId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->hrPeopleDepartmentData->title . '</span>';
            $this->string .= form_hidden('departmentId', $this->input->get('departmentId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('questionId')) {
            if ($this->input->get('questionId') != 'all') {
                $this->nifsQuestion = $this->nifsQuestion->getData_model(array('selectedId' => $this->input->get('questionId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsQuestion->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүх асуулт</span>';
            }

            $this->string .= form_hidden('closeTypeId', $this->input->get('questionId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            //' . Scrime::$path . $param['path'] . '/' . $param['modId'] . '

            if ($this->input->get('isReportMenu') == 1) {

                $this->string .= ' <a href="javascript:;" onclick="_reportItem({reportMenuId: ' . $this->input->get('reportMenuId') . ', reportModId: ' . $this->input->get('reportModId') . '});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            } else {

                $this->string .= ' <a href="javascript:;" onclick="_oldInitNifsSendDocument({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            }
        }

        return ($this->showResetBtn ? '<div class="_search-result-inner"><form action="javascript:;" id="form-nifs-send-document"> Хайлтын үр дүн: ' . $this->string . '</form></div>' : '');
    }

    public function oldClose_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'type' => LOG_TYPE_CLOSE,
            'data' => json_encode($_POST)));

        $this->data = array(
            'close_date' => $this->input->post('closeDate'),
            'close_type_id' => $this->input->post('closeTypeId'),
            'close_description' => $this->input->post('closeDescription'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId);
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'nifs_send_document', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function addFormData_model($param = array('modId' => 0, 'contId' => 0)) {
        return json_decode(json_encode(array(
            'id' => 0,
            'create_number' => getCreateNumber(array('createNumber' => 0, 'table' => 'nifs_send_doc', 'departmentId' => $this->nifsDepartmentId)),
            'module_id' => $param['modId'],
            'mod_id' => $this->modId,
            'cont_id' => $param['contId'],
            'object_count' => 0,
            'send_object' => '',
            'question' => '',
            'question_id' => 0,
            'question_extra' => '',
            'expert' => '',
            'type_id' => 1,
            'weight' => 0,
            'description' => '',
            'in_date' => date('Y-m-d H:i:s'),
            'out_date' => date('Y-m-d H:i:s'),
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
            'department_id' => $this->nifsDepartmentId)));
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                create_number,
                mod_id,
                module_id,
                cont_id,
                object_count,
                send_object,
                question,
                question_id,
                question_extra,
                expert,
                type_id,
                weight,
                description,
                in_date,
                out_date,
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
                solution_id,
                department_id
            FROM `' . $this->db->dbprefix . 'nifs_send_doc`
            WHERE id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function insert_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        $moduleData = $this->module->getData_model(array('id' => $this->input->post('moduleId')));

        if ($this->input->post('typeId') == 11) {
            $dataNifs = array(
                'send_document_chemical_id' => $param['getUID'],
                'send_document_chemical_close_type_id' => 0);
        }
        if ($this->input->post('typeId') == 8) {
            $dataNifs = array(
                'send_document_biology_id' => $param['getUID'],
                'send_document_biology_close_type_id' => 0);
        }
        if ($this->input->post('typeId') == 10) {
            $dataNifs = array(
                'send_document_bakterlogy_id' => $param['getUID'],
                'send_document_bakterlogy_close_type_id' => 0);
        }
        $this->db->where('id', $this->input->post('contId'));
        $this->db->update($this->db->dbprefix . 'nifs_' . $moduleData->table, $dataNifs);

        $data = array(
            array(
                'id' => $param['getUID'],
                'create_number' => getCreateNumber(array('createNumber' => $this->input->post('createNumber'), 'table' => 'nifs_send_doc', 'departmentId' => $this->nifsDepartmentId)),
                'mod_id' => $this->input->post('modId'),
                'module_id' => $this->input->post('moduleId'),
                'cont_id' => $this->input->post('contId'),
                'object_count' => $this->input->post('objectCount'),
                'send_object' => $this->input->post('sendObject'),
                'question_extra' => $this->input->post('questionExtra'),
                'in_date' => $this->input->post('inDate') . ' ' . date('H:i:s'),
                'out_date' => $this->input->post('outDate') . ' ' . date('H:i:s'),
                'type_id' => $this->input->post('typeId'),
                'department_id' => $this->input->post('departmentId'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'is_active' => 1,
                'year' => $this->session->adminContsCloseYear));

        if ($this->db->insert_batch($this->db->dbprefix . 'nifs_send_doc', $data)) {



            $this->hrPeople->updateHrPeopleParam_model(array('modId' => $this->input->post('modId'), 'contId' => $param['getUID'], 'expertId' => $this->input->post('expertId'), 'table' => 'nifs_send_doc'));
            return $this->nifsQuestion->updateQuestionParam_model(array('modId' => $this->input->post('modId'), 'contId' => $param['getUID'], 'questionId' => $this->input->post('questionId'), 'table' => 'nifs_send_doc'));
        }
        
        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'type' => LOG_TYPE_UPDATE,
            'data' => json_encode($_POST)));

        $this->data = array(
            'create_number' => $this->input->post('createNumber'),
            'mod_id' => $this->input->post('modId'),
            'module_id' => $this->input->post('moduleId'),
            'cont_id' => $this->input->post('contId'),
            'object_count' => $this->input->post('objectCount'),
            'send_object' => $this->input->post('sendObject'),
            'question_extra' => $this->input->post('questionExtra'),
            'in_date' => $this->input->post('inDate') . ' ' . date('H:i:s'),
            'out_date' => $this->input->post('outDate') . ' ' . date('H:i:s'),
            'type_id' => $this->input->post('typeId'),
            'department_id' => $this->input->post('departmentId'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId);

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'nifs_send_doc', $this->data)) {

            $this->hrPeople->updateHrPeopleParam_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('id'), 'expertId' => $this->input->post('expertId'), 'table' => 'nifs_send_doc'));
            return $this->nifsQuestion->updateQuestionParam_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('id'), 'questionId' => $this->input->post('questionId'), 'table' => 'nifs_send_doc'));
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {

        $row = $this->getData_model(array('selectedId' => $this->input->post('id')));

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'type' => LOG_TYPE_DELETE,
            'data' => json_encode($row)));

        $dataNifs = array();
        $moduleData = $this->module->getData_model(array('id' => $row->module_id));
        if ($row->type_id == 11) {
            $dataNifs = array(
                'send_document_chemical_id' => 0,
                'send_document_chemical_close_type_id' => 0);
        }
        if ($row->type_id == 8) {
            $dataNifs = array(
                'send_document_biology_id' => 0,
                'send_document_biology_close_type_id' => 0);
        }
        if ($row->type_id == 10) {
            $dataNifs = array(
                'send_document_bakterlogy_id' => 0,
                'send_document_bakterlogy_close_type_id' => 0);
        }

        $this->db->where('id', $row->cont_id);
        $this->db->update($this->db->dbprefix . 'nifs_' . $moduleData->table, $dataNifs);

        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'nifs_send_doc')) {

            $this->db->where('mod_id', $this->input->post('modId'));
            $this->db->where('cont_id', $this->input->post('id'));
            $this->db->delete($this->db->dbprefix . 'nifs_question_detail');

            $this->db->where('mod_id', $this->input->post('modId'));
            $this->db->where('cont_id', $this->input->post('id'));
            $this->db->delete($this->db->dbprefix . 'nifs_expert');

            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }

        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                module_id,
                cont_id,
                object_count,
                send_object,
                question,
                question_id,
                question_extra,
                expert,
                type_id,
                weight,
                description,
                in_date,
                out_date,
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
                solution_id
            FROM `' . $this->db->dbprefix . 'nifs_send_doc`
            WHERE id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

    public function setSendDocumentWindowTitle_model($param = array()) {

        if ($param['typeId'] == '8') {
            return 'Биологийн лабораторид шинжилгээ илгээх';
        } else if ($param['typeId'] == '10') {
            return 'Бактериологийн лабораторид шинжилгээ илгээх';
        } else if ($param['typeId'] == '11') {
            return 'Химийн лабораторид шинжилгээ илгээх';
        } else {
            return 'Илгээх бичиг';
        }
    }

    public function listsCount_model($param = array()) {
        $queryString = $query = '';

        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } elseif ($auth->our->read and !$auth->your->read) {
            $queryString .= ' AND NSD.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NSD.created_user_id = -1';
        }

        if ($param['departmentId'] != 0) {

            $queryString .= ' AND NSD.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
        } else {

            $queryString .= ' AND NSD.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        }
        
        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $queryString .= ' AND (LOWER(NDV.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NFF.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $queryString .= ' AND (LOWER(NDV.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NFF.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;

                case 3: {   //Регистрийн дугаар
                        $queryString .= ' AND (LOWER(NDV.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.register) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;

                case 4: {   //Утас
                        $queryString .= ' AND LOWER(NDV.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Тайлбар
                        $queryString .= ' AND (LOWER(NDV.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NFF.description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;

                case 6: {   //Албан хаагч
                        $queryString .= ' AND (LOWER(NDV.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NFF.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;

                case 7: {   //Дүгнэлтийн утга
                        $queryString .= ' AND (LOWER(NDV.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NFF.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;

                case 8: {   //Объект
                        $queryString .= ' AND LOWER(NSD.send_object) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 9: {   //Нэмэлт асуулт
                        $queryString .= ' AND LOWER(NSD.question_extra) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 10: {   //Хариу тайлбар
                        $queryString .= ' AND LOWER(NSD.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' AND (
                        LOWER(NDV.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NDV.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NDV.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NDV.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NDV.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NDV.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NDV.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NSD.send_object) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NSD.question_extra) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NSD.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                        break;
                    }
            }
        }

        if ($param['createNumber'] != '') {
            $queryString .= ' AND NSD.create_number = \'' . $param['createNumber'] . '\'';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NSD.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NSD.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NSD.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NSD.out_date)';
        }

        if ($param['typeId'] != 0) {
            $queryString .= ' AND NSD.type_id = ' . $param['typeId'];
        } else if ($param['typeId'] == 'all') {
            $queryString .= ' AND NSD.type_id != 0';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NSD.close_date) AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NSD.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NSD.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NSD.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND NSD.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NSD.close_type_id != 0 AND DATE(NSD.close_date) <= DATE(NSD.out_date)';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NSD.close_type_id = 0 AND CURDATE() <= DATE(NSD.out_date)';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NSD.close_type_id != 0 AND DATE(NSD.close_date) > DATE(NSD.out_date)';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NSD.close_type_id = 0 AND CURDATE() > DATE(NSD.out_date)';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND DATE(NSD.protocol_out_date) < DATE(NSD.in_date)';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NSD.close_type_id = 0';
        }

        if ($param['closeTypeId'] != 0) {
            $queryString .= ' AND NSD.close_type_id = ' . $param['closeTypeId'];
        } else if ($param['closeTypeId'] == 'all') {
            $queryString .= ' AND NSD.close_type_id = 0';
        }

        if ($param['expertId'] != 0) {
            $expertString = 'AND NE.expert_id = ' . $param['expertId'];
            if ($param['expertId'] == 'all') {
                $expertString = ' AND NE.expert_id != 0';
            }
            $query = $this->db->query('
                SELECT 
                    NE.expert_id 
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_send_doc` AS NSD ON NE.cont_id = NSD.id AND NE.mod_id = NSD.mod_id
                LEFT JOIN `gaz_nifs_file_folder` AS NFF ON NSD.cont_id = NFF.id AND NSD.module_id = NFF.mod_id 
                LEFT JOIN `gaz_nifs_doctor_view` AS NDV ON NSD.cont_id = NDV.id AND NSD.module_id = NDV.mod_id 
                LEFT JOIN `gaz_nifs_anatomy` AS NA ON NSD.cont_id = NA.id
                LEFT JOIN `gaz_nifs_crime_type` AS NCT ON NSD.type_id = NCT.id
                WHERE 1 = 1 ' . $expertString . ' AND NE.mod_id = ' . $this->modId . $queryString);
        } else if ($param['questionId'] != 0) {

            $questionString = 'AND NQD.question_id = ' . $param['questionId'];
            if ($param['questionId'] == 'all') {
                $questionString = ' AND NQD.question_id != 0';
            }

            $query = $this->db->query('
                SELECT 
                    NQD.question_id 
                FROM `gaz_nifs_send_doc` AS NSD 
                INNER JOIN `gaz_nifs_question_detail` AS NQD ON NSD.id = NQD.cont_id AND NSD.mod_id = NQD.mod_id
                LEFT JOIN `gaz_nifs_file_folder` AS NFF ON NSD.cont_id = NFF.id AND NSD.module_id = NFF.mod_id 
                LEFT JOIN `gaz_nifs_doctor_view` AS NDV ON NSD.cont_id = NDV.id AND NSD.module_id = NDV.mod_id 
                LEFT JOIN `gaz_nifs_anatomy` AS NA ON NSD.cont_id = NA.id
                LEFT JOIN `gaz_nifs_crime_type` AS NCT ON NSD.type_id = NCT.id
                WHERE 1 = 1 ' . $questionString . ' AND NQD.mod_id = ' . $this->modId . $queryString);
        } else {
            $query = $this->db->query('
                SELECT 
                    NSD.id
                FROM `gaz_nifs_send_doc` AS NSD
                LEFT JOIN `gaz_nifs_file_folder` AS NFF ON NSD.cont_id = NFF.id AND NSD.module_id = NFF.mod_id 
                LEFT JOIN `gaz_nifs_doctor_view` AS NDV ON NSD.cont_id = NDV.id AND NSD.module_id = NDV.mod_id 
                LEFT JOIN `gaz_nifs_anatomy` AS NA ON NSD.cont_id = NA.id
                LEFT JOIN `gaz_nifs_crime_type` AS NCT ON NSD.type_id = NCT.id
                WHERE 1 = 1 ' . $queryString);
        }


        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $data = array();

        $queryString = $query = '';

        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } elseif ($auth->our->read and !$auth->your->read) {
            $queryString .= ' AND NSD.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NSD.created_user_id = -1';
        }

        if ($param['departmentId'] != 0) {

            $queryString .= ' AND NSD.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
        } else {

            $queryString .= ' AND NSD.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        }
        
        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $queryString .= ' AND (LOWER(NDV.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NFF.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $queryString .= ' AND (LOWER(NDV.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NFF.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;

                case 3: {   //Регистрийн дугаар
                        $queryString .= ' AND (LOWER(NDV.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.register) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;

                case 4: {   //Утас
                        $queryString .= ' AND LOWER(NDV.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Тайлбар
                        $queryString .= ' AND (LOWER(NDV.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NFF.description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;

                case 6: {   //Албан хаагч
                        $queryString .= ' AND (LOWER(NDV.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NFF.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;

                case 7: {   //Дүгнэлтийн утга
                        $queryString .= ' AND (LOWER(NDV.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NFF.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;

                case 8: {   //Объект
                        $queryString .= ' AND LOWER(NSD.send_object) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 9: {   //Нэмэлт асуулт
                        $queryString .= ' AND LOWER(NSD.question_extra) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 10: {   //Хариу тайлбар
                        $queryString .= ' AND LOWER(NSD.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' AND (
                        LOWER(NDV.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NDV.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NDV.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NDV.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NDV.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NDV.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NDV.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NSD.send_object) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NSD.question_extra) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NSD.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                        break;
                    }
            }
        }

        if ($param['createNumber'] != '') {
            $queryString .= ' AND NSD.create_number = \'' . $param['createNumber'] . '\'';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NSD.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NSD.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NSD.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NSD.out_date)';
        }

        if ($param['typeId'] != 0) {
            $queryString .= ' AND NSD.type_id = ' . $param['typeId'];
        } else if ($param['typeId'] == 'all') {
            $queryString .= ' AND NSD.type_id != 0';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NSD.close_date) AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NSD.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NSD.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NSD.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND NSD.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NSD.close_type_id != 0 AND DATE(NSD.close_date) <= DATE(NSD.out_date)';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NSD.close_type_id = 0 AND CURDATE() <= DATE(NSD.out_date)';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NSD.close_type_id != 0 AND DATE(NSD.close_date) > DATE(NSD.out_date)';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NSD.close_type_id = 0 AND CURDATE() > DATE(NSD.out_date)';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND DATE(NSD.protocol_out_date) < DATE(NSD.in_date)';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NSD.close_type_id = 0';
        }

        if ($param['closeTypeId'] != 0) {
            $queryString .= ' AND NSD.close_type_id = ' . $param['closeTypeId'];
        } else if ($param['closeTypeId'] == 'all') {
            $queryString .= ' AND NSD.close_type_id = 0';
        }

        if ($param['expertId'] != 0) {

            $expertString = 'AND NE.expert_id = ' . $param['expertId'];
            if ($param['expertId'] == 'all') {
                $expertString = ' AND NE.expert_id != 0';
            }

            $query = $this->db->query('
                SELECT 
                    NE.expert_id,
                    NSD.id,
                    NSD.create_number,
                    NSD.mod_id,
                    NSD.module_id,
                    NSD.cont_id,
                    NSD.object_count,
                    NSD.send_object,
                    NSD.question,
                    NSD.question_extra,
                    NSD.expert,
                    IF(NSD.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:rgba(255,255,255,0.8);\') AS expert_status,
                    NSD.type_id,
                    NSD.weight,
                    NSD.description,
                    CONCAT("И: ", DATE(NSD.in_date), "<br>", "Д: ", DATE(NSD.out_date)) AS in_out_date,
                    NSD.created_date,
                    NSD.modified_date,
                    NSD.created_user_id,
                    NSD.modified_user_id,
                    NSD.order_num,
                    NSD.is_active,
                    NSD.year,
                    NSD.close_date,
                    NSD.close_description,
                    NSD.close_type_id,
                    NSD.solution_id,
                    NSD.department_id,
                    IF(DATE(NSD.close_date) != \'0000-00-00\', DATE(NSD.close_date), \'\') AS close_date,
                    (CASE 
                        WHEN (NSD.close_type_id != 0 AND NSD.close_date > NSD.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                        WHEN NSD.close_type_id = 0 AND NOW() > NSD.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                        ELSE \'background-color: transparent;\'
                    END) AS row_status,
                    NFF.create_number AS ff_create_number,
                    (IF(NFF.lname != \'\', CONCAT(NFF.lname, "-н ", "<strong>", NFF.fname, "</strong>"), NFF.fname)) AS ff_full_name,
                    NFF.expert AS ff_expert,
                    NFF.param AS ff_param,
                    NDV.create_number AS dv_create_number,
                    CONCAT("Э: ", DATE_FORMAT(NDV.in_date, \'%Y-%m-%d %H:%i\'), "<br>", "Д: ", DATE_FORMAT(NDV.out_date, \'%Y-%m-%d\')) AS dv_in_out_date,
                    (IF(NDV.lname != \'\', CONCAT(NDV.lname, "-н ", "<strong>", NDV.fname, "</strong>"), NDV.fname)) AS dv_full_name,
                    (IF(NDV.register != \'\', CONCAT(NDV.register), \'\')) AS dv_register,
                    (CASE 
                            WHEN (NDV.is_age_infinitive = 1) THEN \'\'
                            WHEN (NDV.is_age_infinitive = 0 AND NDV.age > 0 AND NDV.age < 1) THEN CONCAT(", ", CAST(NDV.age AS DECIMAL(10,1)), " сартай")
                            ELSE IF(NDV.is_age_infinitive = 0 AND NDV.age >= 1, CONCAT(", ", CAST(NDV.age AS DECIMAL(10)), " настай"), "")
                        END) AS dv_age,
                    (IF(NDV.sex = 1, \', эр\', \', эм\')) AS dv_sex,
                    NDV.param AS dv_param,
                    NA.create_number AS na_create_number,
                    CONCAT("И: ", DATE(NA.in_date), "<br>", "Д: ", DATE(NA.out_date)) AS na_in_out_date,
                    (IF(NA.lname != \'\', CONCAT(NA.lname, "-н ", "<strong>", NA.fname, "</strong>"), NA.fname)) AS na_full_name,
                    (CASE 
                            WHEN (NA.is_age_infinitive = 1) THEN \', нас тодорхойгүй\'
                            WHEN (NA.is_age_infinitive = 0 AND NA.age < 1) THEN CONCAT(", ", CONVERT(SUBSTRING(NA.age, 3, 2),UNSIGNED INTEGER), " сартай")
                            ELSE IF(NA.is_age_infinitive = 0 AND NA.age >= 1, CONCAT(", ", CONVERT(NA.age,UNSIGNED INTEGER), " настай"), "")
                    END) AS na_age,
                    (IF(NA.sex = 1, \', эр\', \', эм\')) AS na_sex,
                    (IF(NA.register != \'\', CONCAT(\', \', NA.register, \'\'), \'\')) AS na_register,
                    NA.expert AS na_expert,
                    NA.param AS na_param,
                    NCT.title AS crime_type,
                    NFF.agent_name AS ff_agent_name,
                    NFF.description AS ff_description,
                    NDV.expert_name AS dv_expert_name,
                    NA.expert_name AS na_expert_name,
                    NA.short_value AS na_short_value
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_send_doc` AS NSD ON NE.cont_id = NSD.id AND NE.mod_id = NSD.mod_id
                LEFT JOIN `gaz_nifs_file_folder` AS NFF ON NSD.cont_id = NFF.id AND NSD.module_id = NFF.mod_id 
                LEFT JOIN `gaz_nifs_doctor_view` AS NDV ON NSD.cont_id = NDV.id AND NSD.module_id = NDV.mod_id 
                LEFT JOIN `gaz_nifs_anatomy` AS NA ON NSD.cont_id = NA.id
                LEFT JOIN `gaz_nifs_crime_type` AS NCT ON NSD.type_id = NCT.id
                WHERE 1 = 1 ' . $expertString . ' AND NE.mod_id = ' . $this->modId . $queryString . '
                ORDER BY NSD.create_number DESC
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        } else if ($param['questionId'] != 0) {

            $questionString = 'AND NQD.question_id = ' . $param['questionId'];
            if ($param['questionId'] == 'all') {
                $questionString = ' AND NQD.question_id != 0';
            }

            $query = $this->db->query('
                SELECT 
                    NSD.id,
                    NSD.create_number,
                    NSD.mod_id,
                    NSD.module_id,
                    NSD.cont_id,
                    NSD.object_count,
                    NSD.send_object,
                    NSD.question,
                    NSD.question_extra,
                    NSD.expert,
                    IF(NSD.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:rgba(255,255,255,0.8);\') AS expert_status,
                    NSD.type_id,
                    NSD.weight,
                    NSD.description,
                    CONCAT("И: ", DATE(NSD.in_date), "<br>", "Д: ", DATE(NSD.out_date)) AS in_out_date,
                    NSD.created_date,
                    NSD.modified_date,
                    NSD.created_user_id,
                    NSD.modified_user_id,
                    NSD.order_num,
                    NSD.is_active,
                    NSD.year,
                    NSD.close_date,
                    NSD.close_description,
                    NSD.close_type_id,
                    NSD.solution_id,
                    NSD.department_id,
                    IF(DATE(NSD.close_date) != \'0000-00-00\', DATE(NSD.close_date), \'\') AS close_date,
                    (CASE 
                        WHEN (NSD.close_type_id != 0 AND NSD.close_date > NSD.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                        WHEN NSD.close_type_id = 0 AND NOW() > NSD.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                        ELSE \'background-color: transparent;\'
                    END) AS row_status,
                    NFF.create_number AS ff_create_number,
                    (IF(NFF.lname != \'\', CONCAT(NFF.lname, "-н ", "<strong>", NFF.fname, "</strong>"), NFF.fname)) AS ff_full_name,
                    NFF.expert AS ff_expert,
                    NFF.param AS ff_param,
                    NDV.create_number AS dv_create_number,
                    CONCAT("Э: ", DATE_FORMAT(NDV.in_date, \'%Y-%m-%d %H:%i\'), "<br>", "Д: ", DATE_FORMAT(NDV.out_date, \'%Y-%m-%d\')) AS dv_in_out_date,
                    (IF(NDV.lname != \'\', CONCAT(NDV.lname, "-н ", "<strong>", NDV.fname, "</strong>"), NDV.fname)) AS dv_full_name,
                    (IF(NDV.register != \'\', CONCAT(NDV.register), \'\')) AS dv_register,
                    (CASE 
                            WHEN (NDV.is_age_infinitive = 1) THEN \'\'
                            WHEN (NDV.is_age_infinitive = 0 AND NDV.age > 0 AND NDV.age < 1) THEN CONCAT(", ", CAST(NDV.age AS DECIMAL(10,1)), " сартай")
                            ELSE IF(NDV.is_age_infinitive = 0 AND NDV.age >= 1, CONCAT(", ", CAST(NDV.age AS DECIMAL(10)), " настай"), "")
                        END) AS dv_age,
                    (IF(NDV.sex = 1, \', эр\', \', эм\')) AS dv_sex,
                    NDV.param AS dv_param,
                    NA.create_number AS na_create_number,
                    CONCAT("И: ", DATE(NA.in_date), "<br>", "Д: ", DATE(NA.out_date)) AS na_in_out_date,
                    (IF(NA.lname != \'\', CONCAT(NA.lname, "-н ", "<strong>", NA.fname, "</strong>"), NA.fname)) AS na_full_name,
                    (CASE 
                            WHEN (NA.is_age_infinitive = 1) THEN \', нас тодорхойгүй\'
                            WHEN (NA.is_age_infinitive = 0 AND NA.age < 1) THEN CONCAT(", ", CONVERT(SUBSTRING(NA.age, 3, 2),UNSIGNED INTEGER), " сартай")
                            ELSE IF(NA.is_age_infinitive = 0 AND NA.age >= 1, CONCAT(", ", CONVERT(NA.age,UNSIGNED INTEGER), " настай"), "")
                    END) AS na_age,
                    (IF(NA.sex = 1, \', эр\', \', эм\')) AS na_sex,
                    (IF(NA.register != \'\', CONCAT(\', \', NA.register, \'\'), \'\')) AS na_register,
                    NA.expert AS na_expert,
                    NA.param AS na_param,
                    NCT.title AS crime_type,
                    NFF.agent_name AS ff_agent_name,
                    NFF.description AS ff_description,
                    NDV.expert_name AS dv_expert_name,
                    NA.expert_name AS na_expert_name,
                    NA.short_value AS na_short_value
                FROM `gaz_nifs_send_doc` AS NSD 
                INNER JOIN `gaz_nifs_question_detail` AS NQD ON NSD.id = NQD.cont_id AND NSD.mod_id = NQD.mod_id
                LEFT JOIN `gaz_nifs_file_folder` AS NFF ON NSD.cont_id = NFF.id AND NSD.module_id = NFF.mod_id 
                LEFT JOIN `gaz_nifs_doctor_view` AS NDV ON NSD.cont_id = NDV.id AND NSD.module_id = NDV.mod_id 
                LEFT JOIN `gaz_nifs_anatomy` AS NA ON NSD.cont_id = NA.id
                LEFT JOIN `gaz_nifs_crime_type` AS NCT ON NSD.type_id = NCT.id
                WHERE 1 = 1 ' . $questionString . ' AND NQD.mod_id = ' . $this->modId . $queryString . '
                ORDER BY NSD.create_number DESC
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        } else {
            $query = $this->db->query('
            SELECT
                NSD.id,
                NSD.create_number,
                NSD.mod_id,
                NSD.module_id,
                NSD.cont_id,
                NSD.object_count,
                NSD.send_object,
                NSD.question,
                NSD.question_extra,
                NSD.expert,
                IF(NSD.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:rgba(255,255,255,0.8);\') AS expert_status,
                NSD.type_id,
                NSD.weight,
                NSD.description,
                CONCAT("И: ", DATE(NSD.in_date), "<br>", "Д: ", DATE(NSD.out_date)) AS in_out_date,
                NSD.created_date,
                NSD.modified_date,
                NSD.created_user_id,
                NSD.modified_user_id,
                NSD.order_num,
                NSD.is_active,
                NSD.year,
                NSD.close_date,
                NSD.close_description,
                NSD.close_type_id,
                NSD.solution_id,
                NSD.department_id,
                IF(DATE(NSD.close_date) != \'0000-00-00\', DATE(NSD.close_date), \'\') AS close_date,
                (CASE 
                    WHEN (NSD.close_type_id != 0 AND NSD.close_date > NSD.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                    WHEN NSD.close_type_id = 0 AND NOW() > NSD.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                    ELSE \'background-color: transparent;\'
                END) AS row_status,
                NFF.create_number AS ff_create_number,
                (IF(NFF.lname != \'\', CONCAT(NFF.lname, "-н ", "<strong>", NFF.fname, "</strong>"), NFF.fname)) AS ff_full_name,
                NFF.expert AS ff_expert,
                NFF.param AS ff_param,
                NDV.create_number AS dv_create_number,
                (IF(NDV.lname != \'\', CONCAT(NDV.lname, "-н ", "<strong>", NDV.fname, "</strong>"), NDV.fname)) AS dv_full_name,
                (IF(NDV.register != \'\', CONCAT(NDV.register), \'\')) AS dv_register,
                (CASE 
                        WHEN (NDV.is_age_infinitive = 1) THEN \'\'
                        WHEN (NDV.is_age_infinitive = 0 AND NDV.age > 0 AND NDV.age < 1) THEN CONCAT(", ", CAST(NDV.age AS DECIMAL(10,1)), " сартай")
                        ELSE IF(NDV.is_age_infinitive = 0 AND NDV.age >= 1, CONCAT(", ", CAST(NDV.age AS DECIMAL(10)), " настай"), "")
                    END) AS dv_age,
                (IF(NDV.sex = 1, \', эр\', \', эм\')) AS dv_sex,
                NDV.param AS dv_param,
                NA.create_number AS na_create_number,
                (IF(NA.lname != \'\', CONCAT(NA.lname, "-н ", "<strong>", NA.fname, "</strong>"), NA.fname)) AS na_full_name,
                (CASE 
                    WHEN (NA.is_age_infinitive = 1) THEN \', нас тодорхойгүй\'
                    WHEN (NA.is_age_infinitive = 0 AND NA.age < 1) THEN CONCAT(", ", CONVERT(SUBSTRING(NA.age, 3, 2),UNSIGNED INTEGER), " сартай")
                    ELSE IF(NA.is_age_infinitive = 0 AND NA.age >= 1, CONCAT(", ", CONVERT(NA.age,UNSIGNED INTEGER), " настай"), "")
                END) AS na_age,
                (IF(NA.sex = 1, \', эр\', \', эм\')) AS na_sex,
                (IF(NA.register != \'\', CONCAT(NA.register, \'\'), \'\')) AS na_register,
                NA.expert AS na_expert,
                NA.param AS na_param,
                NCT.title AS crime_type,
                NFF.agent_name AS ff_agent_name,
                NFF.description AS ff_description,
                NDV.expert_name AS dv_expert_name,
                NA.expert_name AS na_expert_name,
                NA.short_value AS na_short_value
            FROM `gaz_nifs_send_doc` AS NSD
            LEFT JOIN `gaz_nifs_file_folder` AS NFF ON NSD.cont_id = NFF.id AND NSD.module_id = NFF.mod_id 
            LEFT JOIN `gaz_nifs_doctor_view` AS NDV ON NSD.cont_id = NDV.id AND NSD.module_id = NDV.mod_id 
            LEFT JOIN `gaz_nifs_anatomy` AS NA ON NSD.cont_id = NA.id
            LEFT JOIN `gaz_nifs_crime_type` AS NCT ON NSD.type_id = NCT.id
            WHERE 1 = 1 ' . $queryString . '
            ORDER BY NSD.create_number DESC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        }

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $directFullName = $directCreateNumber = $expert = $partner = $crimeValue = '';

                if ($row->module_id == 50) {

                    $directCreateNumber = 'Хавтаст хэрэг №:' . $row->ff_create_number . '<br>' . '';
                    $directFullName = $row->ff_full_name;

                    if ($row->ff_param != '') {
                        $param = json_decode($row->ff_param);

                        if (isset($param->expert)) {
                            $expert = ($param->seniorExpert != '' ? $param->seniorExpert . ', ' : '') . ($param->createExpert != '' ? $param->createExpert : '') . ($row->ff_expert != '' ? $row->ff_expert : '');
                        }

                        $partner = $param->partner . '<br>' . $row->ff_agent_name;
                    }

                    $crimeValue = $row->ff_description;
                } else if ($row->module_id == 51) {

                    $directCreateNumber = 'Үзлэг №:' . $row->dv_create_number . '<br>' . '';
                    $directFullName = $row->dv_full_name . '<br>' . $row->dv_register . $row->dv_sex . $row->dv_age;

                    if ($row->dv_param != '') {

                        $param = json_decode($row->dv_param);

                        if (isset($param->expert)) {
                            $expert = $param->expert;
                        }

                        $partner = $param->partner . '<br>' . $row->dv_expert_name;
                        $crimeValue = $param->shortValue;
                    }
                } else if ($row->module_id == 52) {

                    $directCreateNumber = 'Задлан №:' . $row->na_create_number . '<br>';
                    $directFullName = $row->na_full_name . '<br>' . $row->na_register . $row->na_sex . $row->na_age;

                    $expert = $row->na_expert;

                    if ($row->na_param != '') {
                        $param = json_decode($row->na_param);
                        $partner = $param->partner . '<br>' . $row->na_expert_name;
                        $crimeValue = $row->na_short_value;
                    }
                    
                }


                array_push($data, array(
                    'id' => $row->id,
                    'mod_id' => $row->mod_id,
                    'create_number' => $row->create_number,
                    'dir_create_number' => '<strong>' . $directCreateNumber . '</strong>' . $expert,
                    'dir_full_name' => $directFullName,
                    'partner' => $partner,
                    'crime_value' => $crimeValue,
                    'row_status' => $row->row_status,
                    'in_out_date' => $row->in_out_date,
                    'send_object' => $row->send_object . ' <strong>(' . $row->object_count . ')</strong> ',
                    'question' => $row->question . '<br>' . $row->question_extra,
                    'expert' => $row->expert . '<br><strong>' . $row->crime_type . '</strong>',
                    'expert_status' => $row->expert_status,
                    'close_type_id' => $row->close_type_id,
                    'close_description' => $row->close_description,
                    'close_type' => ($row->close_type_id > 0 ? '<strong>' . $row->close_date . '</strong><br>' . $row->close_description : '')
                ));
            }
        }

        return array('data' => $data, 'search' => self::searchKeywordView_model());
    }

    public function close_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'type' => LOG_TYPE_CLOSE,
            'data' => json_encode($_POST)));

        $this->data = array(
            'close_date' => $this->input->post('closeDate'),
            'close_type_id' => $this->input->post('closeTypeId'),
            'close_description' => $this->input->post('closeDescription'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId);
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'nifs_send_doc', $this->data)) {

            $getSendDocumentData = $this->getData_model(array('selectedId' => $this->input->post('id')));
            $moduleData = $this->module->getData_model(array('id' => $getSendDocumentData->module_id));
            $dataNifs = array(
                'send_document_chemical_close_type_id' => ($getSendDocumentData->type_id == 11 ? $this->input->post('closeTypeId') : 0),
                'send_document_biology_close_type_id' => ($getSendDocumentData->type_id == 8 ? $this->input->post('closeTypeId') : 0),
                'send_document_bakterlogy_close_type_id' => ($getSendDocumentData->type_id == 10 ? $this->input->post('closeTypeId') : 0));
            $this->db->where('id', $getSendDocumentData->cont_id);
            $this->db->update($this->db->dbprefix . 'nifs_' . $moduleData->table, $dataNifs);

            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function searchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('departmentId')) {
            $this->hrPeopleDepartmentData = $this->hrPeopleDepartment->getData_model(array('selectedId' => $this->input->get('departmentId')));
            $this->string .= '<span class="label label-default label-rounded">Мөр бэхжүүлсэн газар: ' . $this->hrPeopleDepartmentData->title . '</span>';
            $this->string .= form_hidden('departmentId', $this->input->get('departmentId'));
            $this->showResetBtn = TRUE;
        }
        
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

        if ($this->input->get('typeId')) {
            if ($this->input->get('typeId') != 'all') {
                $this->nifsCrimeTypeData = $this->nifsCrimeType->getData_model(array('selectedId' => $this->input->get('typeId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCrimeTypeData->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('motiveId', $this->input->get('typeId'));
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

        if ($this->input->get('expertId')) {
            if ($this->input->get('expertId') != 'all') {
                $this->expertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->get('expertId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->expertData->full_name . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүх эмч</span>';
            }

            $this->string .= form_hidden('expertId', $this->input->get('expertId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('closeTypeId')) {
            $this->nifsCloseType = $this->nifsCloseType->getData_model(array('selectedId' => $this->input->get('closeTypeId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCloseType->title . '</span>';
            $this->string .= form_hidden('closeTypeId', $this->input->get('closeTypeId'));
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

        if ($this->input->get('departmentId')) {

            $this->hrPeopleDepartmentData = $this->hrPeopleDepartment->getData_model(array('selectedId' => $this->input->get('departmentId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->hrPeopleDepartmentData->title . '</span>';
            $this->string .= form_hidden('departmentId', $this->input->get('departmentId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('questionId')) {
            if ($this->input->get('questionId') != 'all') {
                $this->nifsQuestion = $this->nifsQuestion->getData_model(array('selectedId' => $this->input->get('questionId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsQuestion->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүх асуулт</span>';
            }

            $this->string .= form_hidden('closeTypeId', $this->input->get('questionId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            //' . Scrime::$path . $param['path'] . '/' . $param['modId'] . '

            if ($this->input->get('isReportMenu') == 1) {

                $this->string .= ' <a href="javascript:;" onclick="_reportItem({reportMenuId: ' . $this->input->get('reportMenuId') . ', reportModId: ' . $this->input->get('reportModId') . '});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            } else {

                $this->string .= ' <a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            }
        }

        return ($this->showResetBtn ? '<div class="_search-result-inner"><form action="javascript:;" id="form-nifs-send-document"> Хайлтын үр дүн: ' . $this->string . '</form></div>' : '');
    }

    public function readResult_model($param = array('selectedId' => 0)) {
        $this->query = $this->db->query('
            SELECT 
                id,
                DATE(close_date) AS close_date,
                close_description,
                close_type_id,
                module_id,
                cont_id,
                expert,
                create_number
            FROM `' . $this->db->dbprefix . 'nifs_send_doc`
            WHERE close_type_id != 0 and cont_id = ' . $param['contId'] . ' and module_id = ' . $param['moduleId'] . ' and type_id = ' . $param['typeId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

    public function getReportWorkInformationData_model($param = array()) {

        $htmlData = $queryStringData = $queryStringYearData = $queryStringInDateData = $queryStringObjectData = $queryStringMotiveData = $isAjaxDepartmentUrl = '';

        if ($param['departmentId'] > 0) {

            $queryStringData .= ' AND NSD.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $queryStringYearData .= ' AND NSD.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        if ($param['reportIsClose'] == 1) {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['inDate'])) . '\') <= DATE(NSD.close_date) AND DATE(\'' . date('Y-m-d', strtotime($param['outDate'])) . '\') >= DATE(NSD.close_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['inDate'])) . '\') <= DATE(NSD.close_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['outDate'])) . '\') >= DATE(NSD.close_date)';
            }
        } else {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['inDate'])) . '\') <= DATE(NSD.in_date) AND DATE(\'' . date('Y-m-d', strtotime($param['outDate'])) . '\') >= DATE(NSD.in_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['inDate'])) . '\') <= DATE(NSD.in_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['outDate'])) . '\') >= DATE(NSD.out_date)';
            }
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NSD.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $query = $this->db->query('
            SELECT 
                NCT.id, 
                NCT.title,
                NSD_YEAR.type_count_year,
                IF(NSD_YEAR.type_count_year > 0, \'_row-more\', \'\') AS type_count_year_class,
                NSD_YEAR.object_count,
                NSD_DATE.type_count_date,
                IF(NSD_DATE.type_count_date > 0, \'_row-more\', \'\') AS type_count_date_class,
                NSD_DATE.date_object_count,
                NSD_CLOSE_TYPE_42.close_type_42_count_date,
                IF(NSD_CLOSE_TYPE_42.close_type_42_count_date > 0, \'_row-more\', \'\') AS close_type_42_count_date_class,
                NSD_CLOSE_TYPE_43.close_type_43_count_date,
                IF(NSD_CLOSE_TYPE_43.close_type_43_count_date > 0, \'_row-more\', \'\') AS close_type_43_count_date_class,
                NSD_STATUS_1.status_1_count_date,
                IF(NSD_STATUS_1.status_1_count_date > 0, \'_row-more\', \'\') AS status_1_count_date_class,
                NSD_STATUS_2.status_2_count_date,
                IF(NSD_STATUS_2.status_2_count_date > 0, \'_row-more\', \'\') AS status_2_count_date_class,
                NSD_STATUS_3.status_3_count_date,
                IF(NSD_STATUS_3.status_3_count_date > 0, \'_row-more\', \'\') AS status_3_count_date_class,
                NSD_STATUS_4.status_4_count_date,
                IF(NSD_STATUS_4.status_4_count_date > 0, \'_row-more\', \'\') AS status_4_count_date_class
            FROM gaz_nifs_crime_type AS NCT
            LEFT JOIN (
                SELECT
                    NSD.type_id,
                    COUNT(NSD.type_id) AS type_count_year,
                    SUM(NSD.object_count) AS object_count
                FROM `gaz_nifs_send_doc` AS NSD 
                WHERE NSD.year = \'' . $this->session->adminCloseYear . '\' 
                GROUP BY NSD.type_id
            ) AS NSD_YEAR ON NSD_YEAR.type_id = NCT.id
            LEFT JOIN (
                SELECT
                    NSD.type_id,
                    COUNT(NSD.type_id) AS type_count_date,
                    SUM(NSD.object_count) AS date_object_count
                FROM `gaz_nifs_send_doc` AS NSD
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NSD.type_id
            ) AS NSD_DATE ON NSD_DATE.type_id = NCT.id
            LEFT JOIN (
                SELECT
                    NSD.type_id,
                    COUNT(NSD.type_id) AS close_type_42_count_date
                FROM `gaz_nifs_send_doc` AS NSD
                WHERE NSD.close_type_id = 42 ' . $queryStringData . '
                GROUP BY NSD.type_id
            ) AS NSD_CLOSE_TYPE_42 ON NSD_CLOSE_TYPE_42.type_id = NCT.id
            LEFT JOIN (
                SELECT
                    NSD.type_id,
                    COUNT(NSD.type_id) AS close_type_43_count_date
                FROM `gaz_nifs_send_doc` AS NSD
                WHERE NSD.close_type_id = 43 ' . $queryStringData . '
                GROUP BY NSD.type_id
            ) AS NSD_CLOSE_TYPE_43 ON NSD_CLOSE_TYPE_43.type_id = NCT.id
            LEFT JOIN (
                SELECT
                    NSD.type_id,
                    COUNT(NSD.type_id) AS status_1_count_date
                FROM `gaz_nifs_send_doc` AS NSD
                WHERE NSD.close_type_id != 0 AND DATE(NSD.close_date) <= DATE(NSD.out_date) ' . $queryStringData . '
                GROUP BY NSD.type_id
            ) AS NSD_STATUS_1 ON NSD_STATUS_1.type_id = NCT.id
            LEFT JOIN (
                SELECT
                    NSD.type_id,
                    COUNT(NSD.type_id) AS status_2_count_date
                FROM `gaz_nifs_send_doc` AS NSD
                WHERE NSD.close_type_id = 0 AND CURDATE() <= DATE(NSD.out_date) ' . $queryStringData . '
                GROUP BY NSD.type_id
            ) AS NSD_STATUS_2 ON NSD_STATUS_2.type_id = NCT.id
            LEFT JOIN (
                SELECT
                    NSD.type_id,
                    COUNT(NSD.type_id) AS status_3_count_date
                FROM `gaz_nifs_send_doc` AS NSD
                WHERE NSD.close_type_id != 0 AND DATE(NSD.close_date) > DATE(NSD.out_date) ' . $queryStringData . '
                GROUP BY NSD.type_id
            ) AS NSD_STATUS_3 ON NSD_STATUS_3.type_id = NCT.id
            LEFT JOIN (
                SELECT
                    NSD.type_id,
                    COUNT(NSD.type_id) AS status_4_count_date
                FROM `gaz_nifs_send_doc` AS NSD
                WHERE NSD.close_type_id = 0 AND CURDATE() > DATE(NSD.out_date) ' . $queryStringData . '
                GROUP BY NSD.type_id
            ) AS NSD_STATUS_4 ON NSD_STATUS_4.type_id = NCT.id
            WHERE NCT.id IN(8,10,11)
            ORDER BY NCT.id ASC');

        $i = $sumTotalTypeCountYear = $sumTotalObjectCount = $sumTotalTypeCountDate = $sumTotalDateObjectCount = $sumTotalCloseType42CountDate = $sumTotalCloseType43CountDate = 0;
        $sumTotalStatus1CountDate = $sumTotalStatus2CountDate = $sumTotalStatus3CountDate = $sumTotalStatus4CountDate = 0;

        if ($query->num_rows() > 0) {

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="3" style="width:30px;">#</th>';
            $htmlData .= '<th rowspan="3">Лаборатори</th>';
            $htmlData .= '<th colspan="2" class="text-center">Оны эхнээс</th>';
            $htmlData .= '<th colspan="9" style="width:180px;" class="text-center">' . date('Y.m.d', strtotime($param['inDate'])) . ' - ' . date('Y.m.d', strtotime($param['outDate'])) . '</th>';
            $htmlData .= '</tr>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Нийт шинжилгээ</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Объект</th>';

            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Ирсэн шинжилгээ</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Илэрсэн</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Илрээгүй</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Объект</th>';

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

            foreach ($query->result() as $key => $row) {

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';

                $htmlData .= '<td class="text-center ' . $row->type_count_year_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'typeId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '\'});">' . $row->type_count_year . '</a></td>';
                $htmlData .= '<td class="text-center">' . $row->object_count . '</td>';

                $htmlData .= '<td class="text-center ' . $row->type_count_date_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'typeId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->type_count_date . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->close_type_42_count_date_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'typeId=' . $row->id . '&closeTypeId=42&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->close_type_42_count_date . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->close_type_43_count_date_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'typeId=' . $row->id . '&closeTypeId=43&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->close_type_43_count_date . '</a></td>';
                $htmlData .= '<td class="text-center">' . $row->date_object_count . '</td>';

                $htmlData .= '<td class="text-center ' . $row->status_1_count_date_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'typeId=' . $row->id . '&statusId=1&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->status_1_count_date . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->status_2_count_date_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'typeId=' . $row->id . '&statusId=2&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->status_2_count_date . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->status_3_count_date_class . '"><a href="javascript:;" class="_crime-crash-done" onclick="_initNifsSendDocument({page: 0, searchQuery: \'typeId=' . $row->id . '&statusId=3&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->status_3_count_date . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->status_4_count_date_class . '"><a href="javascript:;" class="_crime-crash-hand" onclick="_initNifsSendDocument({page: 0, searchQuery: \'typeId=' . $row->id . '&statusId=4&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->status_4_count_date . '</a></td>';

                $htmlData .= '</tr>';

                $sumTotalTypeCountYear = $sumTotalTypeCountYear + $row->type_count_year;
                $sumTotalObjectCount = $sumTotalObjectCount + $row->object_count;

                $sumTotalTypeCountDate = $sumTotalTypeCountDate + $row->type_count_date;
                $sumTotalDateObjectCount = $sumTotalDateObjectCount + $row->date_object_count;
                $sumTotalCloseType42CountDate = $sumTotalCloseType42CountDate + $row->close_type_42_count_date;
                $sumTotalCloseType43CountDate = $sumTotalCloseType43CountDate + $row->close_type_43_count_date;

                $sumTotalStatus1CountDate = $sumTotalStatus1CountDate + $row->status_1_count_date;
                $sumTotalStatus2CountDate = $sumTotalStatus2CountDate + $row->status_2_count_date;
                $sumTotalStatus3CountDate = $sumTotalStatus3CountDate + $row->status_3_count_date;
                $sumTotalStatus4CountDate = $sumTotalStatus4CountDate + $row->status_4_count_date;
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'typeId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalTypeCountYear > 0 ? $sumTotalTypeCountYear : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalObjectCount > 0 ? $sumTotalObjectCount : '') . '</td>';

            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'typeId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalTypeCountDate > 0 ? $sumTotalTypeCountDate : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'typeId=all&closeTypeId=42&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalCloseType42CountDate > 0 ? $sumTotalCloseType42CountDate : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'typeId=all&closeTypeId=43&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalCloseType43CountDate > 0 ? $sumTotalCloseType43CountDate : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalDateObjectCount > 0 ? $sumTotalDateObjectCount : '') . '</td>';

            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'typeId=all&statusId=1&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalStatus1CountDate > 0 ? $sumTotalStatus1CountDate : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'typeId=all&statusId=2&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalStatus2CountDate > 0 ? $sumTotalStatus2CountDate : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" class="_crime-crash-done" onclick="_initNifsSendDocument({page: 0, searchQuery: \'typeId=all&statusId=3&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalStatus3CountDate > 0 ? $sumTotalStatus3CountDate : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" class="_crime-crash-hand" onclick="_initNifsSendDocument({page: 0, searchQuery: \'typeId=all&statusId=4&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalStatus4CountDate > 0 ? $sumTotalStatus4CountDate : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNotCategory = $this->db->query('
            SELECT
                NSD.id,
                NSD.create_number
            FROM `gaz_nifs_send_doc` AS NSD
            WHERE NSD.type_id = 0 ' . $queryStringData . '
            ORDER BY NSD.create_number ASC');

            if ($queryNotCategory->num_rows() > 0) {
                $htmlNotCategoy = 'Шинжилгээний төрөл сонгоогдоогүй шинжилгээ (' . $queryNotCategory->num_rows() . '): ';
                foreach ($queryNotCategory->result() as $key => $row) {
                    $htmlNotCategoy .= '<a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'createNumber=' . $row->create_number . '\'});" class="label-not-selected-content">№: ' . $row->create_number . '</a>';
                }
                $htmlData .= '<div class="table-responsive-inside-description">' . $htmlNotCategoy . '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }


        $query = $this->db->query('
            SELECT 
                NQ.id,
                NQ.title,
                NCA.question_count,
                IF(NCA.question_count > 0, \'_row-more\', \'\') AS question_count_class,
                CLOSE_42.close_type_42_count,
                IF(CLOSE_42.close_type_42_count > 0, \'_row-more\', \'\') AS close_type_42_count_class,
                CLOSE_43.close_type_43_count,
                IF(CLOSE_43.close_type_43_count > 0, \'_row-more\', \'\') AS close_type_43_count_class,
                TYPE_8.type_8_count,
                IF(TYPE_8.type_8_count > 0, \'_row-more\', \'\') AS type_8_count_class,
                TYPE_10.type_10_count,
                IF(TYPE_10.type_10_count > 0, \'_row-more\', \'\') AS type_10_count_class,
                TYPE_11.type_11_count,
                IF(TYPE_11.type_11_count > 0, \'_row-more\', \'\') AS type_11_count_class
            FROM `gaz_nifs_question` AS NQ
            LEFT JOIN (
                SELECT
                    NQD.question_id,
                    COUNT(NQD.question_id) AS question_count
                FROM `gaz_nifs_send_doc` AS NSD 
                INNER JOIN `gaz_nifs_question_detail` AS NQD ON NSD.id = NQD.cont_id AND NSD.mod_id = NQD.mod_id
                WHERE NQD.question_id != 0 ' . $queryStringData . ' AND NSD.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NQD.question_id
            ) AS NCA ON NCA.question_id = NQ.id
            LEFT JOIN (
                SELECT
                    NQD.question_id,
                    COUNT(NQD.question_id) AS close_type_42_count
                FROM `gaz_nifs_send_doc` AS NSD 
                INNER JOIN `gaz_nifs_question_detail` AS NQD ON NSD.id = NQD.cont_id AND NSD.mod_id = NQD.mod_id
                WHERE NQD.question_id != 0 AND NSD.close_type_id = 42 ' . $queryStringData . '
                GROUP BY NQD.question_id
            ) AS CLOSE_42 ON CLOSE_42.question_id = NQ.id
            LEFT JOIN (
                SELECT
                    NQD.question_id,
                    COUNT(NQD.question_id) AS close_type_43_count
                FROM `gaz_nifs_send_doc` AS NSD 
                INNER JOIN `gaz_nifs_question_detail` AS NQD ON NSD.id = NQD.cont_id AND NSD.mod_id = NQD.mod_id
                WHERE NQD.question_id != 0 AND NSD.close_type_id = 43 ' . $queryStringData . '
                GROUP BY NQD.question_id
            ) AS CLOSE_43 ON CLOSE_43.question_id = NQ.id
            LEFT JOIN (
                SELECT
                    NQD.question_id,
                    COUNT(NQD.question_id) AS type_8_count
                FROM `gaz_nifs_send_doc` AS NSD 
                INNER JOIN `gaz_nifs_question_detail` AS NQD ON NSD.id = NQD.cont_id AND NSD.mod_id = NQD.mod_id
                WHERE NQD.question_id != 0 AND NSD.type_id = 8 ' . $queryStringData . '
                GROUP BY NQD.question_id
            ) AS TYPE_8 ON TYPE_8.question_id = NQ.id
            LEFT JOIN (
                SELECT
                    NQD.question_id,
                    COUNT(NQD.question_id) AS type_10_count
                FROM `gaz_nifs_send_doc` AS NSD 
                INNER JOIN `gaz_nifs_question_detail` AS NQD ON NSD.id = NQD.cont_id AND NSD.mod_id = NQD.mod_id
                WHERE NQD.question_id != 0 AND NSD.type_id = 10 ' . $queryStringData . '
                GROUP BY NQD.question_id
            ) AS TYPE_10 ON TYPE_10.question_id = NQ.id
            LEFT JOIN (
                SELECT
                    NQD.question_id,
                    COUNT(NQD.question_id) AS type_11_count
                FROM `gaz_nifs_send_doc` AS NSD 
                INNER JOIN `gaz_nifs_question_detail` AS NQD ON NSD.id = NQD.cont_id AND NSD.mod_id = NQD.mod_id
                WHERE NQD.question_id != 0 AND NSD.type_id = 11 ' . $queryStringData . '
                GROUP BY NQD.question_id
            ) AS TYPE_11 ON TYPE_11.question_id = NQ.id
            WHERE 
                NQ.is_active = 1 AND NQ.cat_id = 372
            ORDER BY NQ.order_num ASC');

        $i = $sumTotalQuestionCount = $sumTotalCloseType42Count = $sumTotalCloseType43Count = $sumTotalType8Count = $sumTotalType10Count = $sumTotalType11Count = 0;

        if ($query->num_rows() > 0) {

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Асуулт</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Нийт</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Илэрсэн</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Илрээгүй</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хими</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Биологи</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Бактерлоги</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->question_count_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'questionId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '\'});">' . $row->question_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->close_type_42_count_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'questionId=' . $row->id . '&closeTypeId=42&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->close_type_42_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->close_type_43_count_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'questionId=' . $row->id . '&closeTypeId=43&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->close_type_43_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_8_count_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'questionId=' . $row->id . '&typeId=8&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->type_8_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_10_count_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'questionId=' . $row->id . '&typeId=10&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->type_10_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_11_count_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'questionId=' . $row->id . '&typeId=11&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->type_11_count . '</a></td>';

                $htmlData .= '</tr>';

                $sumTotalQuestionCount = $sumTotalQuestionCount + $row->question_count;
                $sumTotalCloseType42Count = $sumTotalCloseType42Count + $row->close_type_42_count;
                $sumTotalCloseType43Count = $sumTotalCloseType43Count + $row->close_type_43_count;
                $sumTotalType8Count = $sumTotalType8Count + $row->type_8_count;
                $sumTotalType10Count = $sumTotalType10Count + $row->type_10_count;
                $sumTotalType11Count = $sumTotalType8Count + $row->type_11_count;
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right">Нийт</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'questionId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalQuestionCount > 0 ? $sumTotalQuestionCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'questionId=all&closeTypeId=42&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalCloseType42Count > 0 ? $sumTotalCloseType42Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'questionId=all&closeTypeId=43&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalCloseType43Count > 0 ? $sumTotalCloseType43Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'questionId=all&typeId=8&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalType8Count > 0 ? $sumTotalType8Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'questionId=all&typeId=10&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalType10Count > 0 ? $sumTotalType10Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'questionId=all&typeId=11&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalType11Count > 0 ? $sumTotalType11Count : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $htmlData .= '<br>';
        }

        return $htmlData;
    }

    public function getReportWeightData_model($param = array()) {

        $queryStringYearData = $queryStringData = $queryStringHrPeopleData = $htmlData = $isAjaxDepartmentUrl = '';

        if ($param['reportIsClose'] == 1) {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NSD.close_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NSD.close_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NSD.close_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NSD.close_date)';
            }
        } else {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NSD.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NSD.in_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\' <= DATE(NSD.in_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NSD.out_date)';
            }
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NSD.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $query = $this->db->query('
            SELECT 
                NE.expert_id
            FROM `gaz_nifs_expert` AS NE
            INNER JOIN `gaz_nifs_send_doc` AS NSD ON NE.mod_id = NSD.mod_id AND NE.cont_id = NSD.id
            INNER JOIN `gaz_hr_people_work` AS HPW ON NE.expert_id = HPW.people_id AND HPW.is_currenty = 1
            WHERE 1 = 1 ' . $queryStringData . '
            GROUP BY NE.expert_id');

        $inPeopleId = ''; //NIFS_EXTRA_EXPERT;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $row) {
                $inPeopleId .= $row->expert_id . ', ';
            }
        }
        $inPeopleId = rtrim($inPeopleId, ', ');

        if ($param['departmentId'] > 0) {
            $queryStringData .= ' AND NSD.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $queryStringYearData .= ' AND NSD.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $queryStringHrPeopleData .= ' AND HPW.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        $query = $this->db->query('
            SELECT 
                HP.id,
                IF(HP.is_active = 1, CONCAT(\'<strong>\',SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname, \'</strong><br><div style="font-size:90%">\', HPP.title, \', \', HPD.title, \'</div>\'), CONCAT(\'<strong>\', HP.fname, \'</strong>\')) AS full_name,
                C.year_total_count,
                IF(C.year_total_count > 0, \'_row-more\', \'\') AS year_total_count_class,
                CLOSE_TYPE_42.close_type_42_count,
                IF(CLOSE_TYPE_42.close_type_42_count > 0, \'_row-more\', \'\') AS close_type_42_count_class,
                CLOSE_TYPE_43.close_type_43_count,
                IF(CLOSE_TYPE_43.close_type_43_count > 0, \'_row-more\', \'\') AS close_type_43_count_class,
                0 AS close_type_cancel_count,
                \'\' AS close_type_cancel_count_class,
                CLOSE_TYPE_NORMAL.close_type_normal_count,
                IF(CLOSE_TYPE_NORMAL.close_type_normal_count > 0, \'_row-more\', \'\') AS close_type_normal_count_class,
                CLOSE_TYPE_CRASH.close_type_crash_count,
                IF(CLOSE_TYPE_CRASH.close_type_crash_count > 0, \'_row-more\', \'\') AS close_type_crash_count_class
            FROM `gaz_hr_people` AS HP
            INNER JOIN `gaz_hr_people_work` AS HPW ON HP.id = HPW.people_id AND HPW.is_currenty = 1
            INNER JOIN `gaz_hr_people_position` AS HPP ON HPP.id = HPW.position_id 
            INNER JOIN `gaz_hr_people_department` AS HPD ON HPD.id = HPW.department_id 
            LEFT JOIN (
                SELECT 
                    NE.expert_id,
                    COUNT(NE.expert_id) AS year_total_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_send_doc` AS NSD ON NE.mod_id = NSD.mod_id AND NE.cont_id = NSD.id
                WHERE 1 = 1 AND NSD.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NE.expert_id
            ) C ON C.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NE.expert_id,
                    COUNT(NE.expert_id) AS close_type_42_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_send_doc` AS NSD ON NE.mod_id = NSD.mod_id AND NE.cont_id = NSD.id
                WHERE NSD.close_type_id = 42 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) CLOSE_TYPE_42 ON CLOSE_TYPE_42.expert_id = HP.id
            LEFT JOIN (
                SELECT 
                    NE.expert_id,
                    COUNT(NE.expert_id) AS close_type_43_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_send_doc` AS NSD ON NE.mod_id = NSD.mod_id AND NE.cont_id = NSD.id
                WHERE NSD.close_type_id = 43 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) CLOSE_TYPE_43 ON CLOSE_TYPE_43.expert_id = HP.id
            LEFT JOIN (
                SELECT 
                    NE.expert_id,
                    COUNT(NE.expert_id) AS close_type_normal_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_send_doc` AS NSD ON NE.mod_id = NSD.mod_id AND NE.cont_id = NSD.id
                WHERE NSD.close_type_id = 0 AND CURDATE() <= DATE(NSD.out_date) ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) CLOSE_TYPE_NORMAL ON CLOSE_TYPE_NORMAL.expert_id = HP.id
            LEFT JOIN (
                SELECT 
                    NE.expert_id,
                    COUNT(NE.expert_id) AS close_type_crash_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_send_doc` AS NSD ON NE.mod_id = NSD.mod_id AND NE.cont_id = NSD.id
                WHERE NSD.close_type_id = 0 AND CURDATE() > DATE(NSD.out_date) ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) CLOSE_TYPE_CRASH ON CLOSE_TYPE_CRASH.expert_id = HP.id
            WHERE HP.id IN(' . $inPeopleId . ') ' . $queryStringHrPeopleData . '
            ORDER BY HP.fname ASC');

        if ($query->num_rows() > 0) {

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="2" style="width:30px;">#</th>';
            $htmlData .= '<th rowspan="2">Овог, нэр</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Нийт</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Илэрсэн</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Илэрээгүй</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Татгалзсан</th>';
            $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Хариу гараагүй</th>';
            $htmlData .= '</tr>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хэвийн</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хугацаа хэтэрсэн</th>';
            $htmlData .= '</tr>';
            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            $i = $sumTotalYearTotalCount = $sumTotalCloseType42Count = $sumTotalCloseType43Count = $sumTotalCloseTypeCancelCount = $sumTotalCloseTypeNormalCount = $sumTotalCloseTypeCrashCount = 0;

            foreach ($query->result() as $key => $row) {

                $sumTotalYearTotalCount = $sumTotalYearTotalCount + $row->year_total_count;
                $sumTotalCloseType42Count = $sumTotalCloseType42Count + $row->close_type_42_count;
                $sumTotalCloseType43Count = $sumTotalCloseType43Count + $row->close_type_43_count;
                $sumTotalCloseTypeCancelCount = $sumTotalCloseTypeCancelCount + $row->close_type_cancel_count;
                $sumTotalCloseTypeNormalCount = $sumTotalCloseTypeNormalCount + $row->close_type_normal_count;
                $sumTotalCloseTypeCrashCount = $sumTotalCloseTypeCrashCount + $row->close_type_crash_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->full_name . '</td>';
                $htmlData .= '<td class="text-center ' . $row->year_total_count_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->year_total_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->close_type_42_count_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'expertId=' . $row->id . '&closeTypeId=42&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->close_type_42_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->close_type_43_count_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'expertId=' . $row->id . '&closeTypeId=43&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->close_type_43_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->close_type_cancel_count_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'expertId=' . $row->id . '&closeTypeId=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->close_type_cancel_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->close_type_normal_count_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'expertId=' . $row->id . '&statusId=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->close_type_normal_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->close_type_crash_count_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'expertId=' . $row->id . '&statusId=4&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->close_type_crash_count . '</a></td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';

            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td>Нийт</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalYearTotalCount > 0 ? $sumTotalYearTotalCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'expertId=all&closeTypeId=42&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalCloseType42Count > 0 ? $sumTotalCloseType42Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'expertId=all&closeTypeId=43&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalCloseType43Count > 0 ? $sumTotalCloseType43Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'expertId=all&closeTypeId=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalCloseTypeCancelCount > 0 ? $sumTotalCloseTypeCancelCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'expertId=all&statusId=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalCloseTypeNormalCount > 0 ? $sumTotalCloseTypeNormalCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'expertId=all&statusId=4&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalCloseTypeCrashCount > 0 ? $sumTotalCloseTypeCrashCount : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNotExpert = $this->db->query('
            SELECT 
                NE.expert_id,
                NSD.create_number
            FROM `gaz_nifs_expert` AS NE 
            INNER JOIN `gaz_nifs_send_doc` AS NSD ON NE.mod_id = NSD.mod_id AND NE.cont_id = NSD.id 
            WHERE NE.expert_id = 0 ' . $queryStringData . '
            ORDER BY NSD.create_number ASC');

            if ($queryNotExpert->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Эмч сонгоогүй шинжилгээ (' . $queryNotExpert->num_rows() . '): ';

                foreach ($queryNotExpert->result() as $rowNotExpert) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'createNumber=' . $rowNotExpert->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNotExpert->create_number . '</a>';
                }
                $htmlData .= '</div>';
            } else {
                $htmlData .= '<br>';
            }

            $queryNotExpert = $this->db->query('
            SELECT 
                NE.expert_id,
                NSD.create_number
            FROM `gaz_nifs_expert` AS NE 
            INNER JOIN `gaz_nifs_send_doc` AS NSD ON NE.mod_id = NSD.mod_id AND NE.cont_id = NSD.id 
            WHERE NSD.close_type_id = 0 ' . $queryStringData . '
            ORDER BY NSD.create_number ASC');

            if ($queryNotExpert->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Хаагдаагүй шинжилгээ (' . $queryNotExpert->num_rows() . '): ';

                foreach ($queryNotExpert->result() as $rowNotExpert) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'createNumber=' . $rowNotExpert->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNotExpert->create_number . '</a>';
                }
                $htmlData .= '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }

        return $htmlData;
    }

    public function dataUpdate_model1($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $query = $this->db->query('
            SELECT
                NSD.id,
                NSD.create_number,
                NSD.mod_id,
                NSD.cont_id,
                NSD.object_count,
                NSD.send_object,
                NSD.question,
                NSD.question_extra,
                NSD.expert,
                IF(NSD.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:rgba(255,255,255,0.8);\') AS expert_status,
                NSD.type_id,
                NSD.weight,
                NSD.description,
                CONCAT("И: ", DATE(NSD.in_date), "<br>", "Д: ", DATE(NSD.out_date)) AS in_out_date,
                NSD.created_date,
                NSD.modified_date,
                NSD.created_user_id,
                NSD.modified_user_id,
                NSD.order_num,
                NSD.is_active,
                NSD.year,
                NSD.close_date,
                NSD.close_description,
                NSD.close_type_id,
                NSD.solution_id,
                NSD.department_id,
                IF(DATE(NSD.close_date) != \'0000-00-00\', DATE(NSD.close_date), \'\') AS close_date,
                (CASE 
                    WHEN (NSD.solution_id != 0 AND NSD.close_date > NSD.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                    WHEN NSD.solution_id = 0 AND NOW() > NSD.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                    ELSE \'background-color: transparent;\'
                END) AS row_status
            FROM `gaz_nifs_send_doc` AS NSD
            WHERE 1 = 1
            ORDER BY NSD.create_number DESC');

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $data = array(
                    'create_number' => $row->id,
                    'module_id' => $row->mod_id,
                    'mod_id' => $this->modId,
                    'department_id' => $this->nifsDepartmentId);

                $this->db->where('id', $row->id);

                if ($this->db->update($this->db->dbprefix . 'nifs_send_doc', $data)) {

                    echo '<pre>';
                    echo 'ok';
                    echo '</pre>';
                }
            }
        }
    }

    public function dataUpdate_model($param = array()) {

        $query = $this->db->query('
                SELECT 
                    id, 
                    in_date, 
                    year
                FROM `gaz_nifs_send_doc`');

        foreach ($query->result() as $key => $row) {
            if ((date('Ymd', strtotime($row->in_date)) >= date('Ymd', strtotime('2018-12-08'))) and date('Ymd', strtotime($row->in_date)) <= date('Ymd', strtotime('2019-12-06'))) {

                $this->db->where('id', $row->id);
                $this->db->update('gaz_nifs_send_doc', array('year' => '2019'));
            } else if ((date('Ymd', strtotime($row->in_date)) >= date('Ymd', strtotime('2019-12-07'))) and date('Ymd', strtotime($row->in_date)) <= date('Ymd', strtotime('2020-12-06'))) {

                $this->db->where('id', $row->id);
                $this->db->update('gaz_nifs_send_doc', array('year' => '2020'));
            }
        }
    }

    public function export_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $data = array();

        $queryString = $query = '';

        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } elseif ($auth->our->read and ! $auth->your->read) {
            $queryString .= ' AND NSD.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NSD.created_user_id = -1';
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $queryString .= ' AND (LOWER(NDV.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NFF.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $queryString .= ' AND (LOWER(NDV.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NFF.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;

                case 3: {   //Регистрийн дугаар
                        $queryString .= ' AND (LOWER(NDV.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.register) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;

                case 4: {   //Утас
                        $queryString .= ' AND LOWER(NDV.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Тайлбар
                        $queryString .= ' AND (LOWER(NDV.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NFF.description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;

                case 6: {   //Албан хаагч
                        $queryString .= ' AND (LOWER(NDV.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NFF.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;

                case 7: {   //Дүгнэлтийн утга
                        $queryString .= ' AND (LOWER(NDV.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NFF.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;

                case 8: {   //Объект
                        $queryString .= ' AND LOWER(NSD.send_object) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 9: {   //Нэмэлт асуулт
                        $queryString .= ' AND LOWER(NSD.question_extra) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 10: {   //Хариу тайлбар
                        $queryString .= ' AND LOWER(NSD.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' AND (
                        LOWER(NDV.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NDV.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NDV.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NDV.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NDV.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NDV.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NDV.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(NA.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
                        LOWER(NSD.send_object) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NSD.question_extra) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NSD.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                        break;
                    }
            }
        }

        if ($param['createNumber'] != '') {
            $queryString .= ' AND NSD.create_number = \'' . $param['createNumber'] . '\'';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NSD.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NSD.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NSD.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NSD.out_date)';
        }

        if ($param['typeId'] != 0) {
            $queryString .= ' AND NSD.type_id = ' . $param['typeId'];
        } else if ($param['typeId'] == 'all') {
            $queryString .= ' AND NSD.type_id != 0';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NSD.close_date) AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NSD.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NSD.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NSD.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND NSD.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NSD.close_type_id != 0 AND DATE(NSD.close_date) <= DATE(NSD.out_date)';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NSD.close_type_id = 0 AND CURDATE() <= DATE(NSD.out_date)';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NSD.close_type_id != 0 AND DATE(NSD.close_date) > DATE(NSD.out_date)';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NSD.close_type_id = 0 AND CURDATE() > DATE(NSD.out_date)';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND DATE(NSD.protocol_out_date) < DATE(NSD.in_date)';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NSD.close_type_id = 0';
        }

        if ($param['closeTypeId'] != 0) {
            $queryString .= ' AND NSD.close_type_id = ' . $param['closeTypeId'];
        } else if ($param['closeTypeId'] == 'all') {
            $queryString .= ' AND NSD.close_type_id = 0';
        }

        if ($param['expertId'] != 0) {

            $expertString = 'AND NE.expert_id = ' . $param['expertId'];
            if ($param['expertId'] == 'all') {
                $expertString = ' AND NE.expert_id != 0';
            }

            $query = $this->db->query('
                SELECT 
                    NE.expert_id,
                    NSD.id,
                    NSD.create_number,
                    NSD.mod_id,
                    NSD.module_id,
                    NSD.cont_id,
                    NSD.object_count,
                    NSD.send_object,
                    NSD.question,
                    NSD.question_extra,
                    NSD.expert,
                    IF(NSD.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:rgba(255,255,255,0.8);\') AS expert_status,
                    NSD.type_id,
                    NSD.weight,
                    NSD.description,
                    DATE(NSD.in_date) AS in_date,
                    DATE(NSD.out_date) AS out_date,
                    NSD.created_date,
                    NSD.modified_date,
                    NSD.created_user_id,
                    NSD.modified_user_id,
                    NSD.order_num,
                    NSD.is_active,
                    NSD.year,
                    NSD.close_date,
                    NSD.close_description,
                    NSD.close_type_id,
                    NSD.solution_id,
                    NSD.department_id,
                    IF(DATE(NSD.close_date) != \'0000-00-00\', DATE(NSD.close_date), \'\') AS close_date,
                    (CASE 
                        WHEN (NSD.close_type_id != 0 AND NSD.close_date > NSD.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                        WHEN NSD.close_type_id = 0 AND NOW() > NSD.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                        ELSE \'background-color: transparent;\'
                    END) AS row_status,
                    NFF.create_number AS ff_create_number,
                    (IF(NFF.lname != \'\', CONCAT(NFF.lname, "-н ", NFF.fname), NFF.fname)) AS ff_full_name,
                    NFF.expert AS ff_expert,
                    NFF.param AS ff_param,
                    NDV.create_number AS dv_create_number,
                    CONCAT("Э: ", DATE_FORMAT(NDV.in_date, \'%Y-%m-%d %H:%i\'), "<br>", "Д: ", DATE_FORMAT(NDV.out_date, \'%Y-%m-%d\')) AS dv_in_out_date,
                    (IF(NDV.lname != \'\', CONCAT(NDV.lname, "-н ", NDV.fname), NDV.fname)) AS dv_full_name,
                    (IF(NDV.register != \'\', CONCAT(NDV.register), \'\')) AS dv_register,
                    (CASE 
                            WHEN (NDV.is_age_infinitive = 1) THEN \'\'
                            WHEN (NDV.is_age_infinitive = 0 AND NDV.age > 0 AND NDV.age < 1) THEN CONCAT(", ", CAST(NDV.age AS DECIMAL(10,1)), " сартай")
                            ELSE IF(NDV.is_age_infinitive = 0 AND NDV.age >= 1, CONCAT(", ", CAST(NDV.age AS DECIMAL(10)), " настай"), "")
                        END) AS dv_age,
                    (IF(NDV.sex = 1, \', эр\', \', эм\')) AS dv_sex,
                    NDV.param AS dv_param,
                    NA.create_number AS na_create_number,
                    CONCAT("И: ", DATE(NA.in_date), "<br>", "Д: ", DATE(NA.out_date)) AS na_in_out_date,
                    (IF(NA.lname != \'\', CONCAT(NA.lname, "-н ", NA.fname), NA.fname)) AS na_full_name,
                    (CASE 
                            WHEN (NA.is_age_infinitive = 1) THEN \', нас тодорхойгүй\'
                            WHEN (NA.is_age_infinitive = 0 AND NA.age < 1) THEN CONCAT(", ", CONVERT(SUBSTRING(NA.age, 3, 2),UNSIGNED INTEGER), " сартай")
                            ELSE IF(NA.is_age_infinitive = 0 AND NA.age >= 1, CONCAT(", ", CONVERT(NA.age,UNSIGNED INTEGER), " настай"), "")
                    END) AS na_age,
                    (IF(NA.sex = 1, \', эр\', \', эм\')) AS na_sex,
                    (IF(NA.register != \'\', CONCAT(\', \', NA.register, \'\'), \'\')) AS na_register,
                    NA.expert AS na_expert,
                    NA.param AS na_param,
                    NCT.title AS crime_type,
                    NFF.agent_name AS ff_agent_name,
                    NFF.description AS ff_description,
                    NDV.expert_name AS dv_expert_name,
                    NA.expert_name AS na_expert_name,
                    NA.short_value AS na_short_value
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_send_doc` AS NSD ON NE.cont_id = NSD.id AND NE.mod_id = NSD.mod_id
                LEFT JOIN `gaz_nifs_file_folder` AS NFF ON NSD.cont_id = NFF.id AND NSD.module_id = NFF.mod_id 
                LEFT JOIN `gaz_nifs_doctor_view` AS NDV ON NSD.cont_id = NDV.id AND NSD.module_id = NDV.mod_id 
                LEFT JOIN `gaz_nifs_anatomy` AS NA ON NSD.cont_id = NA.id
                LEFT JOIN `gaz_nifs_crime_type` AS NCT ON NSD.type_id = NCT.id
                WHERE 1 = 1 ' . $expertString . ' AND NE.mod_id = ' . $this->modId . $queryString);
        } else if ($param['questionId'] != 0) {

            $questionString = 'AND NQD.question_id = ' . $param['questionId'];
            if ($param['questionId'] == 'all') {
                $questionString = ' AND NQD.question_id != 0';
            }

            $query = $this->db->query('
                SELECT 
                    NSD.id,
                    NSD.create_number,
                    NSD.mod_id,
                    NSD.module_id,
                    NSD.cont_id,
                    NSD.object_count,
                    NSD.send_object,
                    NSD.question,
                    NSD.question_extra,
                    NSD.expert,
                    IF(NSD.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:rgba(255,255,255,0.8);\') AS expert_status,
                    NSD.type_id,
                    NSD.weight,
                    NSD.description,
                    DATE(NSD.in_date) AS in_date,
                    DATE(NSD.out_date) AS out_date,
                    NSD.created_date,
                    NSD.modified_date,
                    NSD.created_user_id,
                    NSD.modified_user_id,
                    NSD.order_num,
                    NSD.is_active,
                    NSD.year,
                    NSD.close_date,
                    NSD.close_description,
                    NSD.close_type_id,
                    NSD.solution_id,
                    NSD.department_id,
                    IF(DATE(NSD.close_date) != \'0000-00-00\', DATE(NSD.close_date), \'\') AS close_date,
                    (CASE 
                        WHEN (NSD.close_type_id != 0 AND NSD.close_date > NSD.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                        WHEN NSD.close_type_id = 0 AND NOW() > NSD.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                        ELSE \'background-color: transparent;\'
                    END) AS row_status,
                    NFF.create_number AS ff_create_number,
                    (IF(NFF.lname != \'\', CONCAT(NFF.lname, "-н ", NFF.fname), NFF.fname)) AS ff_full_name,
                    NFF.expert AS ff_expert,
                    NFF.param AS ff_param,
                    NDV.create_number AS dv_create_number,
                    CONCAT("Э: ", DATE_FORMAT(NDV.in_date, \'%Y-%m-%d %H:%i\'), " ", "Д: ", DATE_FORMAT(NDV.out_date, \'%Y-%m-%d\')) AS dv_in_out_date,
                    (IF(NDV.lname != \'\', CONCAT(NDV.lname, "-н ", NDV.fname), NDV.fname)) AS dv_full_name,
                    (IF(NDV.register != \'\', CONCAT(NDV.register), \'\')) AS dv_register,
                    (CASE 
                            WHEN (NDV.is_age_infinitive = 1) THEN \'\'
                            WHEN (NDV.is_age_infinitive = 0 AND NDV.age > 0 AND NDV.age < 1) THEN CONCAT(", ", CAST(NDV.age AS DECIMAL(10,1)), " сартай")
                            ELSE IF(NDV.is_age_infinitive = 0 AND NDV.age >= 1, CONCAT(", ", CAST(NDV.age AS DECIMAL(10)), " настай"), "")
                        END) AS dv_age,
                    (IF(NDV.sex = 1, \', эр\', \', эм\')) AS dv_sex,
                    NDV.param AS dv_param,
                    NA.create_number AS na_create_number,
                    CONCAT("И: ", DATE(NA.in_date), "<br>", "Д: ", DATE(NA.out_date)) AS na_in_out_date,
                    (IF(NA.lname != \'\', CONCAT(NA.lname, "-н ", NA.fname), NA.fname)) AS na_full_name,
                    (CASE 
                            WHEN (NA.is_age_infinitive = 1) THEN \', нас тодорхойгүй\'
                            WHEN (NA.is_age_infinitive = 0 AND NA.age < 1) THEN CONCAT(", ", CONVERT(SUBSTRING(NA.age, 3, 2),UNSIGNED INTEGER), " сартай")
                            ELSE IF(NA.is_age_infinitive = 0 AND NA.age >= 1, CONCAT(", ", CONVERT(NA.age,UNSIGNED INTEGER), " настай"), "")
                    END) AS na_age,
                    (IF(NA.sex = 1, \', эр\', \', эм\')) AS na_sex,
                    (IF(NA.register != \'\', CONCAT(\', \', NA.register, \'\'), \'\')) AS na_register,
                    NA.expert AS na_expert,
                    NA.param AS na_param,
                    NCT.title AS crime_type,
                    NFF.agent_name AS ff_agent_name,
                    NFF.description AS ff_description,
                    NDV.expert_name AS dv_expert_name,
                    NA.expert_name AS na_expert_name,
                    NA.short_value AS na_short_value
                FROM `gaz_nifs_send_doc` AS NSD 
                INNER JOIN `gaz_nifs_question_detail` AS NQD ON NSD.id = NQD.cont_id AND NSD.mod_id = NQD.mod_id
                LEFT JOIN `gaz_nifs_file_folder` AS NFF ON NSD.cont_id = NFF.id AND NSD.module_id = NFF.mod_id 
                LEFT JOIN `gaz_nifs_doctor_view` AS NDV ON NSD.cont_id = NDV.id AND NSD.module_id = NDV.mod_id 
                LEFT JOIN `gaz_nifs_anatomy` AS NA ON NSD.cont_id = NA.id
                LEFT JOIN `gaz_nifs_crime_type` AS NCT ON NSD.type_id = NCT.id
                WHERE 1 = 1 ' . $questionString . ' AND NQD.mod_id = ' . $this->modId . $queryString);
        } else {
            $query = $this->db->query('
            SELECT
                NSD.id,
                NSD.create_number,
                NSD.mod_id,
                NSD.module_id,
                NSD.cont_id,
                NSD.object_count,
                NSD.send_object,
                NSD.question,
                NSD.question_extra,
                NSD.expert,
                IF(NSD.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:rgba(255,255,255,0.8);\') AS expert_status,
                NSD.type_id,
                NSD.weight,
                NSD.description,
                DATE(NSD.in_date) AS in_date,
                DATE(NSD.out_date) AS out_date,
                NSD.created_date,
                NSD.modified_date,
                NSD.created_user_id,
                NSD.modified_user_id,
                NSD.order_num,
                NSD.is_active,
                NSD.year,
                NSD.close_date,
                NSD.close_description,
                NSD.close_type_id,
                NSD.solution_id,
                NSD.department_id,
                IF(DATE(NSD.close_date) != \'0000-00-00\', DATE(NSD.close_date), \'\') AS close_date,
                (CASE 
                    WHEN (NSD.close_type_id != 0 AND NSD.close_date > NSD.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                    WHEN NSD.close_type_id = 0 AND NOW() > NSD.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                    ELSE \'background-color: transparent;\'
                END) AS row_status,
                NFF.create_number AS ff_create_number,
                (IF(NFF.lname != \'\', CONCAT(NFF.lname, "-н ", NFF.fname), NFF.fname)) AS ff_full_name,
                NFF.expert AS ff_expert,
                NFF.param AS ff_param,
                NDV.create_number AS dv_create_number,
                (IF(NDV.lname != \'\', CONCAT(NDV.lname, "-н ", NDV.fname), NDV.fname)) AS dv_full_name,
                (IF(NDV.register != \'\', CONCAT(NDV.register), \'\')) AS dv_register,
                (CASE 
                        WHEN (NDV.is_age_infinitive = 1) THEN \'\'
                        WHEN (NDV.is_age_infinitive = 0 AND NDV.age > 0 AND NDV.age < 1) THEN CONCAT(", ", CAST(NDV.age AS DECIMAL(10,1)), " сартай")
                        ELSE IF(NDV.is_age_infinitive = 0 AND NDV.age >= 1, CONCAT(", ", CAST(NDV.age AS DECIMAL(10)), " настай"), "")
                    END) AS dv_age,
                (IF(NDV.sex = 1, \', эр\', \', эм\')) AS dv_sex,
                NDV.param AS dv_param,
                NA.create_number AS na_create_number,
                (IF(NA.lname != \'\', CONCAT(NA.lname, "-н ", NA.fname), NA.fname)) AS na_full_name,
                (CASE 
                    WHEN (NA.is_age_infinitive = 1) THEN \', нас тодорхойгүй\'
                    WHEN (NA.is_age_infinitive = 0 AND NA.age < 1) THEN CONCAT(", ", CONVERT(SUBSTRING(NA.age, 3, 2),UNSIGNED INTEGER), " сартай")
                    ELSE IF(NA.is_age_infinitive = 0 AND NA.age >= 1, CONCAT(", ", CONVERT(NA.age,UNSIGNED INTEGER), " настай"), "")
                END) AS na_age,
                (IF(NA.sex = 1, \', эр\', \', эм\')) AS na_sex,
                (IF(NA.register != \'\', CONCAT(NA.register, \'\'), \'\')) AS na_register,
                NA.expert AS na_expert,
                NA.param AS na_param,
                NCT.title AS crime_type,
                NFF.agent_name AS ff_agent_name,
                NFF.description AS ff_description,
                NDV.expert_name AS dv_expert_name,
                NA.expert_name AS na_expert_name,
                NA.short_value AS na_short_value
            FROM `gaz_nifs_send_doc` AS NSD
            LEFT JOIN `gaz_nifs_file_folder` AS NFF ON NSD.cont_id = NFF.id AND NSD.module_id = NFF.mod_id 
            LEFT JOIN `gaz_nifs_doctor_view` AS NDV ON NSD.cont_id = NDV.id AND NSD.module_id = NDV.mod_id 
            LEFT JOIN `gaz_nifs_anatomy` AS NA ON NSD.cont_id = NA.id
            LEFT JOIN `gaz_nifs_crime_type` AS NCT ON NSD.type_id = NCT.id
            WHERE 1 = 1 ' . $queryString . '
            ORDER BY NSD.create_number DESC');
        }

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {



                $directFullName = $directCreateNumber = $expert = $partner = $crimeValue = '';


                if ($row->module_id == 50) {

                    $directType = 'Хавтаст хэрэг';

                    $directCreateNumber = $row->ff_create_number;
                    $directFullName = $row->ff_full_name;

                    $param = json_decode($row->ff_param);

                    if (isset($param->expert)) {
                        $expert = ($param->seniorExpert != '' ? $param->seniorExpert . ', ' : '') . ($param->createExpert != '' ? $param->createExpert : '') . ($row->ff_expert != '' ? $row->ff_expert : '');
                    }

                    $partner = $param->partner . '<br>' . $row->ff_agent_name;
                    $crimeValue = $row->ff_description;
                } else if ($row->module_id == 51) {

                    $directType = 'Эмчийн үзлэг';
                    $directCreateNumber = $row->dv_create_number;
                    $directFullName = $row->dv_full_name . '<br>' . $row->dv_register . $row->dv_sex . $row->dv_age;

                    $param = json_decode($row->dv_param);

                    if (isset($param->expert)) {
                        $expert = $param->expert;
                    }

                    $partner = $param->partner . '<br>' . $row->dv_expert_name;
                    $crimeValue = $param->shortValue;
                } else if ($row->module_id == 52) {

                    $directType = 'Задлан';
                    $directCreateNumber = $row->na_create_number;
                    $directFullName = $row->na_full_name . '<br>' . $row->na_register . $row->na_sex . $row->na_age;

                    $expert = $row->na_expert;
                    $param = json_decode($row->na_param);

                    $partner = $param->partner . '<br>' . $row->na_expert_name;
                    $crimeValue = $row->na_short_value;
                }


                array_push($data, array(
                    'id' => $row->id,
                    'mod_id' => $row->mod_id,
                    'create_number' => $row->create_number,
                    'dir_type' => $directType,
                    'dir_create_number' => $directCreateNumber,
                    'dir_expert' => $expert,
                    'dir_full_name' => $directFullName,
                    'partner' => $partner,
                    'crime_value' => $crimeValue,
                    'row_status' => $row->row_status,
                    'in_date' => $row->in_date,
                    'out_date' => $row->out_date,
                    'object_count' => $row->object_count,
                    'send_object' => $row->send_object,
                    'question' => $row->question . '<br>' . $row->question_extra,
                    'expert' => $row->expert,
                    'lab' => $row->crime_type,
                    'expert_status' => $row->expert_status,
                    'close_type' => ($row->close_type_id > 0 ? '<strong>' . $row->close_date . '</strong><br>' . $row->close_description : '')
                ));
            }

            return $data;
        }

        return false;
    }

    public function controlHrPeopleDepartmentDropdown_model($param = array('modId' => 0, 'selectedId' => 0)) {

        $html = $string = $class = $name = $tempString = '';

        if (isset($param['name'])) {
            $name = $param['name'];
        } else {
            $name = 'departmentId';
        }

        if (isset($param['disabled']) and $param['disabled'] == 'true') {
            $string = 'disabled = "true"';
        }

        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                HPD.short_title
            FROM `gaz_hr_people_department` AS HPD
            WHERE HPD.is_active = 1 AND HPD.is_active_control = 1 AND HPD.id IN(5,' . $this->session->userdata['adminDepartmentId'] . ')');

        $html .= '<select name="' . $name . '" id="' . $name . '" class="select2" ' . $string . '>';

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                if ($param['selectedId'] == $row->id) {
                    if (isset($param['disabled']) and $param['disabled'] == 'true') {
                        $tempString = '<input type="hidden" name="' . $name . '" value="' . $row->id . '">';
                    }
                    $html .= '<option value="' . $row->id . '" selected="selected">' . $row->short_title . '</option>';
                } else {
                    $html .= '<option value="' . $row->id . '">' . $row->short_title . '</option>';
                }
            }
        }

        $html .= '</select>';

        return $html . $tempString;
    }

}
