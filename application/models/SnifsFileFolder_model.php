<?php

class SnifsFileFolder_model extends CI_Model {

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
        $this->load->model('SnifsSolution_model', 'nifsSolution');


        $this->perPage = 2;
        $this->hrPeoplePositionId = '5,6,7,13,27';
        $this->hrPeopleDepartmentId = '4,16,17,18';
        $this->nifsCrimeTypeId = 354;
        $this->nifsQuestionCatId = 374;
        $this->nifsSolutionCatId = 362;
        $this->nifsCloseTypeCatId = 368;
        $this->nifsResearchTypeCatId = 383;
        $this->nifsMotiveCatId = 389;
        $this->modId = 50;
        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->isActiveDepartment = 'is_active_nifs_file_folder';
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
            'create_number' => getCreateNumber(array('createNumber' => 0, 'table' => 'nifs_file_folder', 'departmentId' => $this->nifsDepartmentId)),
            'research_type_id' => 11,
            'is_mixx' => 0,
            'in_date' => date('Y-m-d H:i:s'),
            'out_date' => date('Y-m-d H:i:s'),
            'fname' => '',
            'lname' => '',
            'partner_id' => 0,
            'agent_name' => '',
            'question_id' => 0,
            'question' => '',
            'description' => '',
            'pre_create_number' => '',
            'pre_expert_id' => 0,
            'pre_value' => '',
            'motive_id' => 0,
            'protocol_number' => '',
            'object' => '',
            'object_count' => 0,
            'senior_expert_id' => 0,
            'create_expert_id' => 0,
            'expert_id' => 0,
            'weight' => '',
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'protocol_number' => '',
            'protocol_in_date' => '',
            'protocol_out_date' => '',
            'extra_expert_value' => '',
            'age' => 0)));
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cat_id,
                create_number,
                research_type_id,
                is_mixx,
                DATE(in_date) AS in_date,
                DATE(out_date) AS out_date,
                fname,
                lname,
                partner_id,
                agent_name,
                question_id,
                question,
                description,
                pre_create_number,
                pre_expert_id,
                pre_value,
                motive_id,
                protocol_number,
                object,
                object_count,
                senior_expert_id,
                create_expert_id,
                expert,
                weight,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id,
                protocol_number,
                DATE(protocol_in_date) AS protocol_in_date,
                DATE(protocol_out_date) AS protocol_out_date,
                close_type_id,
                close_date,
                close_type_description,
                close_description,
                solution_id,
                close_type_id,
                param,
                extra_expert_value,
                pre_crime,
                age
            FROM `' . $this->db->dbprefix . 'nifs_file_folder`
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
        } elseif ($auth->our->read and !$auth->your->read) {
            $queryString .= ' AND NFF.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NFF.created_user_id = -1';
        }

        if ($param['selectedId'] != 0) {

            $queryString .= ' AND NFF.id = ' . $param['selectedId'];
        }
        
        if ($param['departmentId'] != 0) {

            $queryString .= ' AND NFF.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
        } else {

            $queryString .= ' AND NFF.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        }

        if ($param['createNumber'] != 0) {
            $queryString .= ' AND NFF.create_number = ' . $param['createNumber'];
        }

        if ($param['researchTypeId'] != 0) {
            $queryString .= ' AND NFF.research_type_id = ' . $param['researchTypeId'];
        } else if ($param['researchTypeId'] == 'all') {
            $queryString .= ' AND NFF.research_type_id != 0';
        }

        if ($param['isMixx'] == 1) {
            $queryString .= ' AND NFF.is_mixx = 1';
        }

        if ($param['isMixx'] == 2) {
            $queryString .= ' AND NFF.is_mixx = 0';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NFF.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NFF.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NFF.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NFF.out_date)';
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NFF.protocol_in_date) AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NFF.protocol_in_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NFF.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NFF.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NFF.close_date) AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NFF.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NFF.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NFF.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND NFF.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['partnerId'] != 0) {
            $queryString .= ' AND NFF.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
        } else if ($param['partnerId'] == 'all') {
            $queryString .= ' AND NFF.partner_id != 0';
        }

        if ($param['questionId'] != 0) {

            $queryString .= ' AND NFF.question_id = ' . $param['questionId'];

            if ($param['questionId'] == 38) {

                if ($param['age1'] != '' and $param['age2'] != '') {

                    $queryString .= ' AND (NFF.age >= ' . floatval($param['age1']) . ' AND NFF.age <= ' . floatval($param['age2']) . ')';
                } else if ($param['age1'] != '' and $param['age2'] == '') {

                    $queryString .= ' AND \'' . floatval($param['age1']) . '\' <= NFF.age';
                } else if ($param['age1'] == '' and $param['age2'] != '') {

                    $queryString .= ' AND \'' . floatval($param['age2']) . '\' >= NFF.age';
                }
            }
        } else if ($param['questionId'] == 'all') {
            $queryString .= ' AND NFF.question_id != 0';
        }

        if ($param['preCreateNumber'] != 0) {
            $queryString .= ' AND NFF.pre_create_number = ' . $param['preCreateNumber'];
        }

        if ($param['preExpertId'] != 0) {
            $queryString .= ' AND NFF.pre_expert_id = ' . $param['preExpertId'];
        } else if ($param['preExpertId'] == 'all') {
            $queryString .= ' AND NFF.pre_expert_id != 0';
        }

        if ($param['motiveId'] != 0) {
            $queryString .= ' AND NFF.motive_id = ' . $param['motiveId'];
        } else if ($param['motiveId'] == 'all') {
            $queryString .= ' AND NFF.motive_id != 0';
        }

        if ($param['solutionId'] != 0) {
            $queryString .= ' AND NFF.solution_id = ' . $param['solutionId'];
        } else if ($param['solutionId'] == 'all') {
            $queryString .= ' AND NFF.solution_id != 0';
        }

        if ($param['closeTypeId'] != 0) {
            $queryString .= ' AND NFF.close_type_id = ' . $param['closeTypeId'];
        } else if ($param['closeTypeId'] == 'all') {
            $queryString .= ' AND NFF.close_type_id != 0';
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND NFF.cat_id = ' . $param['catId'];
        } else if ($param['catId'] == 'all') {
            $queryString .= ' AND NFF.cat_id != 0';
        }

        if ($param['seniorExpertId'] != 0) {
            $queryString .= ' AND NFF.senior_expert_id = ' . $param['seniorExpertId'];
        } else if ($param['seniorExpertId'] == 'all') {
            $queryString .= ' AND NFF.senior_expert_id != 0';
        }

        if ($param['createExpertId'] != 0) {
            $queryString .= ' AND NFF.create_expert_id = ' . $param['createExpertId'];
        } else if ($param['createExpertId'] == 'all') {
            $queryString .= ' AND NFF.create_expert_id != 0';
        }

        if ($param['weight'] != 0) {
            $queryString .= ' AND NFF.weight = ' . $param['weight'];
        }

        if ($param['protocolNumber'] != 0) {
            $queryString .= ' AND NFF.protocol_number = ' . $param['protocolNumber'];
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $queryString .= ' AND LOWER(NFF.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $queryString .= ' AND LOWER(NFF.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Албан тушаалтны нэр
                        $queryString .= ' AND LOWER(NFF.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Тайлбар
                        $queryString .= ' AND LOWER(NFF.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Дүгнэлтийн утга
                        $queryString .= ' AND LOWER(NFF.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' AND (
                        LOWER(NFF.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NFF.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NFF.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NFF.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NFF.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                        break;
                    }
            }
        }

        if ($param['closeDescription'] != '') {
            $queryString .= ' AND (LOWER(NFF.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        if ($param['preCrime'] != '') {

            $queryString .= ' AND LOWER(NFF.pre_crime) LIKE LOWER(\'%' . json_encode($param['preCrime']) . '%\')';
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NFF.solution_id != 0 AND NFF.close_type_id != 0 AND DATE(NFF.close_date) <= DATE(NFF.out_date)';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NFF.solution_id = 0 AND NFF.close_type_id = 0 AND CURDATE() <= DATE(NFF.out_date)';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NFF.solution_id != 0 AND NFF.close_type_id != 0 AND DATE(NFF.close_date) > DATE(NFF.out_date)';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NFF.solution_id = 0 AND NFF.close_type_id = 0 AND DATE(\'' . $param['outDate'] . '\') >= DATE(NFF.out_date)';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND DATE(NFF.protocol_out_date) < DATE(NFF.in_date)';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NFF.solution_id = 0 AND NFF.close_type_id = 0';
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
                INNER JOIN `gaz_nifs_file_folder` AS NFF ON NE.cont_id = NFF.id AND NE.mod_id = NFF.mod_id
                WHERE 1 = 1 ' . $expertString . $queryString);
        } else {
            $query = $this->db->query('
                SELECT 
                    NFF.id
                FROM `gaz_nifs_file_folder` AS NFF
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
        } elseif ($auth->our->read and !$auth->your->read) {
            $queryString .= ' AND NFF.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NFF.created_user_id = -1';
        }

        if ($param['selectedId'] != 0) {

            $queryString .= ' AND NFF.id = ' . $param['selectedId'];
        }
        
        if ($param['departmentId'] != 0) {

            $queryString .= ' AND NFF.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
        } else {

            $queryString .= ' AND NFF.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        }

        if ($param['createNumber'] != 0) {
            $queryString .= ' AND NFF.create_number = ' . $param['createNumber'];
        }

        if ($param['researchTypeId'] != 0) {
            $queryString .= ' AND NFF.research_type_id = ' . $param['researchTypeId'];
        } else if ($param['researchTypeId'] == 'all') {
            $queryString .= ' AND NFF.research_type_id != 0';
        }

        if ($param['isMixx'] == 1) {
            $queryString .= ' AND NFF.is_mixx = 1';
        }

        if ($param['isMixx'] == 2) {
            $queryString .= ' AND NFF.is_mixx = 0';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NFF.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NFF.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NFF.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NFF.out_date)';
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NFF.protocol_in_date) AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NFF.protocol_in_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NFF.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NFF.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NFF.close_date) AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NFF.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NFF.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NFF.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND NFF.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['partnerId'] != 0) {
            $queryString .= ' AND NFF.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
        } else if ($param['partnerId'] == 'all') {
            $queryString .= ' AND NFF.partner_id != 0';
        }

        if ($param['questionId'] != 0) {

            $queryString .= ' AND NFF.question_id = ' . $param['questionId'];

            if ($param['questionId'] == 38) {

                if ($param['age1'] != '' and $param['age2'] != '') {

                    $queryString .= ' AND (NFF.age >= ' . floatval($param['age1']) . ' AND NFF.age <= ' . floatval($param['age2']) . ')';
                } else if ($param['age1'] != '' and $param['age2'] == '') {

                    $queryString .= ' AND \'' . floatval($param['age1']) . '\' <= NFF.age';
                } else if ($param['age1'] == '' and $param['age2'] != '') {

                    $queryString .= ' AND \'' . floatval($param['age2']) . '\' >= NFF.age';
                }
            }
        } else if ($param['questionId'] == 'all') {
            $queryString .= ' AND NFF.question_id != 0';
        }

        if ($param['preCreateNumber'] != 0) {
            $queryString .= ' AND NFF.pre_create_number = ' . $param['preCreateNumber'];
        }

        if ($param['preExpertId'] != 0) {
            $queryString .= ' AND NFF.pre_expert_id = ' . $param['preExpertId'];
        } else if ($param['preExpertId'] == 'all') {
            $queryString .= ' AND NFF.pre_expert_id != 0';
        }

        if ($param['motiveId'] != 0) {
            $queryString .= ' AND NFF.motive_id = ' . $param['motiveId'];
        } else if ($param['motiveId'] == 'all') {
            $queryString .= ' AND NFF.motive_id != 0';
        }

        if ($param['solutionId'] != 0) {
            $queryString .= ' AND NFF.solution_id = ' . $param['solutionId'];
        } else if ($param['solutionId'] == 'all') {
            $queryString .= ' AND NFF.solution_id != 0';
        }

        if ($param['closeTypeId'] != 0) {
            $queryString .= ' AND NFF.close_type_id = ' . $param['closeTypeId'];
        } else if ($param['closeTypeId'] == 'all') {
            $queryString .= ' AND NFF.close_type_id != 0';
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND NFF.cat_id = ' . $param['catId'];
        } else if ($param['catId'] == 'all') {
            $queryString .= ' AND NFF.cat_id != 0';
        }

        if ($param['seniorExpertId'] != 0) {
            $queryString .= ' AND NFF.senior_expert_id = ' . $param['seniorExpertId'];
        } else if ($param['seniorExpertId'] == 'all') {
            $queryString .= ' AND NFF.senior_expert_id != 0';
        }

        if ($param['createExpertId'] != 0) {
            $queryString .= ' AND NFF.create_expert_id = ' . $param['createExpertId'];
        } else if ($param['createExpertId'] == 'all') {
            $queryString .= ' AND NFF.create_expert_id != 0';
        }

        if ($param['weight'] != 0) {
            $queryString .= ' AND NFF.weight = ' . $param['weight'];
        }

        if ($param['protocolNumber'] != 0) {
            $queryString .= ' AND NFF.protocol_number = ' . $param['protocolNumber'];
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $queryString .= ' AND LOWER(NFF.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $queryString .= ' AND LOWER(NFF.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Албан тушаалтны нэр
                        $queryString .= ' AND LOWER(NFF.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Тайлбар
                        $queryString .= ' AND LOWER(NFF.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Дүгнэлтийн утга
                        $queryString .= ' AND LOWER(NFF.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' AND (
                        LOWER(NFF.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NFF.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NFF.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NFF.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NFF.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                        break;
                    }
            }
        }

        if ($param['closeDescription'] != '') {
            $queryString .= ' AND (LOWER(NFF.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        if ($param['preCrime'] != '') {

            $queryString .= ' AND LOWER(NFF.pre_crime) LIKE LOWER(\'%' . json_encode($param['preCrime']) . '%\')';
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NFF.solution_id != 0 AND NFF.close_type_id != 0 AND DATE(NFF.close_date) <= DATE(NFF.out_date)';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NFF.solution_id = 0 AND NFF.close_type_id = 0 AND CURDATE() <= DATE(NFF.out_date)';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NFF.solution_id != 0 AND NFF.close_type_id != 0 AND DATE(NFF.close_date) > DATE(NFF.out_date)';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NFF.solution_id = 0 AND NFF.close_type_id = 0 AND DATE(\'' . $param['outDate'] . '\') >= DATE(NFF.out_date)';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND DATE(NFF.protocol_out_date) < DATE(NFF.in_date)';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NFF.solution_id = 0 AND NFF.close_type_id = 0';
        }
        
        if ($param['expertId'] != 0) {

            $expertString = ' AND NE.expert_id = ' . $param['expertId'];

            if ($param['expertId'] == 'all') {
                $expertString = ' AND NE.expert_id != 0';
            }

            $query = $this->db->query('
                SELECT 
                    NFF.id,
                    NFF.mod_id,
                    NFF.cat_id,
                    NFF.created_user_id,
                    NFF.create_number,
                    IF(NFF.is_mixx = 1, "<span class=\'fa fa-users\'></span>", "") AS is_mixx,
                    CONCAT("И:", DATE(NFF.in_date), "<br>", "И:", DATE(NFF.out_date)) AS in_out_date,
                    CONCAT(IF(NFF.lname != \'\', CONCAT(NFF.lname, \'-н \'), \'\'), \' <span style="">\', NFF.fname, \'</span>\') AS full_name,
                    NFF.partner_id,
                    NFF.agent_name,
                    NFF.protocol_number,
                    NFF.research_type_id,
                    IF(NFF.object_count > 0, CONCAT(\' <strong>(\', NFF.object_count, \')</strong> \', NFF.object), \'\') AS object,
                    NFF.pre_crime,
                    NFF.expert,
                    (CASE 
                        WHEN (NFF.expert != \'\' OR NFF.senior_expert_id != 0 OR NFF.create_expert_id != 0) THEN \'background-color: transparent;\'
                        ELSE \'background-color: #2196F3; color:#ffffff;\'
                    END) AS expert_status,
                    IF(NFF.solution_id != 0, CONCAT(NFF.close_description, \'<br>\', DATE(NFF.close_date)), \'\') AS report,
                    CONCAT(IF(NFF.close_type_id = 22, CONCAT(\'(<strong>Зөрсөн:</strong> \', NFF.close_type_description, \'), \'), \'\'), \' \', NFF.description) AS description,
                    (CASE 
                        WHEN (NFF.solution_id != 0 AND NFF.close_date > NFF.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                        WHEN NFF.solution_id = 0 AND NOW() > NFF.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                        ELSE \'\'
                    END) AS row_status,
                    (CASE 
			WHEN (NFF.send_document_chemical_id != 0 AND NFF.send_document_chemical_close_type_id != 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/chemical.svg" style="width:16px;" alt="Хими">\'
			WHEN (NFF.send_document_chemical_id != 0 AND NFF.send_document_chemical_close_type_id = 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/chemical-grey.svg" style="width:16px;" alt="Хими">\'
			ELSE \'\'
                    END) AS send_document_chemical,
                    (CASE 
			WHEN (NFF.send_document_biology_id != 0 AND NFF.send_document_biology_close_type_id != 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/dna.svg" style="width:16px;" alt="Биологи">\'
			WHEN (NFF.send_document_biology_id != 0 AND NFF.send_document_biology_close_type_id = 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/dna-grey.svg" style="width:16px;" alt="Биологи">\'
			ELSE \'\'
                    END) AS send_document_biology,
                    (CASE 
			WHEN (NFF.send_document_bakterlogy_id != 0 AND NSD_NFF.send_document_bakterlogy_close_type_id != 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/baktery.svg" style="width:16px;" alt="Бактериологи">\'
			WHEN (NFF.send_document_bakterlogy_id != 0 AND NSD_NFF.send_document_bakterlogy_close_type_id = 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/baktery-grey.svg" style="width:16px;" alt="Бактериологи">\'
			ELSE \'\'
                    END) AS send_document_bakterlogy,
                    NFF.send_document_chemical_id,
                    NFF.send_document_chemical_close_type_id,
                    NFF.send_document_biology_id,
                    NFF.send_document_biology_close_type_id,
                    NFF.send_document_bakterlogy_id,
                    NFF.send_document_bakterlogy_close_type_id,
                    NFF.param
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_file_folder` AS NFF ON NE.cont_id = NFF.id AND NE.mod_id = NFF.mod_id
                WHERE 1 = 1 ' . $expertString . $queryString . '
                ORDER BY NFF.create_number DESC
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        } else {
            $query = $this->db->query('
                SELECT 
                    NFF.id,
                    NFF.mod_id,
                    NFF.cat_id,
                    NFF.created_user_id,
                    NFF.create_number,
                    IF(NFF.is_mixx = 1, "<span class=\'fa fa-users\'></span>", "") AS is_mixx,
                    CONCAT("И:", DATE(NFF.in_date), "<br>", "И:", DATE(NFF.out_date)) AS in_out_date,
                    CONCAT(IF(NFF.lname != \'\', CONCAT(NFF.lname, \'-н \'), \'\'), \' <span style="">\', NFF.fname, \'</span>\') AS full_name,
                    NFF.partner_id,
                    NFF.agent_name,
                    NFF.protocol_number,
                    NFF.research_type_id,
                    IF(NFF.object_count > 0, CONCAT(\' <strong>(\', NFF.object_count, \')</strong> \', NFF.object), \'\') AS object,
                    NFF.pre_crime,
                    NFF.expert,
                    (CASE 
                        WHEN (NFF.expert != \'\' OR NFF.senior_expert_id != 0 OR NFF.create_expert_id != 0) THEN \'background-color: transparent;\'
                        ELSE \'background-color: #2196F3; color:#ffffff;\'
                    END) AS expert_status,
                    IF(NFF.solution_id != 0, CONCAT(NFF.close_description, \'<br>\', DATE(NFF.close_date)), \'\') AS report,
                    CONCAT(IF(NFF.close_type_id = 22, CONCAT(\'(<strong>Зөрсөн:</strong> \', NFF.close_type_description, \'), \'), \'\'), \' \', NFF.description) AS description,
                    (CASE 
                        WHEN (NFF.solution_id != 0 AND NFF.close_date > NFF.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                        WHEN NFF.solution_id = 0 AND NOW() > NFF.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                        ELSE \'\'
                    END) AS row_status,
                    (CASE 
			WHEN (NFF.send_document_chemical_id != 0 AND NFF.send_document_chemical_close_type_id != 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/chemical.svg" style="width:16px;" alt="Хими">\'
			WHEN (NFF.send_document_chemical_id != 0 AND NFF.send_document_chemical_close_type_id = 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/chemical-grey.svg" style="width:16px;" alt="Хими">\'
			ELSE \'\'
                    END) AS send_document_chemical,
                    (CASE 
			WHEN (NFF.send_document_biology_id != 0 AND NFF.send_document_biology_close_type_id != 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/dna.svg" style="width:16px;" alt="Биологи">\'
			WHEN (NFF.send_document_biology_id != 0 AND NFF.send_document_biology_close_type_id = 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/dna-grey.svg" style="width:16px;" alt="Биологи">\'
			ELSE \'\'
                    END) AS send_document_biology,
                    (CASE 
			WHEN (NFF.send_document_bakterlogy_id != 0 AND NFF.send_document_bakterlogy_close_type_id != 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/baktery.svg" style="width:16px;" alt="Бактериологи">\'
			WHEN (NFF.send_document_bakterlogy_id != 0 AND NFF.send_document_bakterlogy_close_type_id = 0) THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/baktery-grey.svg" style="width:16px;" alt="Бактериологи">\'
			ELSE \'\'
                    END) AS send_document_bakterlogy,
                    NFF.send_document_chemical_id,
                    NFF.send_document_chemical_close_type_id,
                    NFF.send_document_biology_id,
                    NFF.send_document_biology_close_type_id,
                    NFF.send_document_bakterlogy_id,
                    NFF.send_document_bakterlogy_close_type_id,
                    NFF.param
                FROM `gaz_nifs_file_folder` AS NFF
                WHERE 1 = 1 ' . $queryString . '
                ORDER BY NFF.create_number DESC
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        }

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $param = json_decode($row->param);
                $preCrimeHtml = '';

                if ($row->pre_crime != '') {
                    $preCrime = json_decode($row->pre_crime);
                    foreach ($preCrime as $preCrimeRow) {
                        //$preCrimeHtml .= '<span class="label label-default label-rounded" style="text-transform: inherit; margin-bottom:2px;">';
                        $preCrimeHtml .= '(' . $preCrimeRow['0'] . ' - ' . $preCrimeRow['1'] . ', ' . $preCrimeRow['2'] . '), ';
                        //$preCrimeHtml .= '</span>';
                    }
                    $preCrimeHtml = rtrim($preCrimeHtml, ', ');
                }

                array_push($data, array(
                    'id' => $row->id,
                    'mod_id' => $row->mod_id,
                    'create_number' => $row->create_number,
                    'is_mixx' => $row->is_mixx,
                    'in_out_date' => $row->in_out_date,
                    'full_name' => $row->full_name,
                    'partner' => $param->partner . ' ' . $row->agent_name,
                    'protocol' => '<strong>' . $row->protocol_number . '</strong> <br>' . $param->researchType,
                    'object' => $row->object,
                    'pre' => $preCrimeHtml,
                    'expert' => ($param->seniorExpert != '' ? $param->seniorExpert . ', ' : '') . ($param->createExpert != '' ? $param->createExpert : '') . ($row->expert != '' ? $row->expert : '') . ' ' . $param->question,
                    'expert_status' => $row->expert_status,
                    'report' => $row->report,
                    'description' => $row->description,
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
            'createdUserId' => $this->session->userdata['adminUserId'],
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        $this->expert = $preCrime = array();
        $queryPreCrime = '';

        $this->nifsResearchTypeData = $this->nifsResearchType->getData_model(array('selectedId' => $this->input->post('researchTypeId')));
        $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $this->input->post('motiveId')));
        $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->post('partnerId')));
        $this->nifsQuestionData = $this->nifsQuestion->getData_model(array('selectedId' => $this->input->post('questionId')));
        if ($this->input->post('preCrimeCreateNumber')) {
            foreach ($this->input->post('preCrimeCreateNumber') as $key => $row) {
                $queryPreCrime .= '№:' . $this->input->post('preCrimeCreateNumber[' . $key . ']') . ', ' . $this->input->post('preCrimeExpert[' . $key . ']') . ', ' . $this->input->post('preCrimeCrimeValue[' . $key . ']') . '; ';
                array_push($preCrime, array($this->input->post('preCrimeCreateNumber[' . $key . ']'), $this->input->post('preCrimeExpert[' . $key . ']'), $this->input->post('preCrimeCrimeValue[' . $key . ']')));
            }
        }
        $queryPreCrime = rtrim($queryPreCrime, '; ');
        $this->categoryData = $this->category->getData_model(array('selectedId' => $this->input->post('catId')));
        $this->seniorExpertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->post('seniorExpertId')));
        $this->createExpertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->post('createExpertId')));



        $this->data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'create_number' => getCreateNumber(array('createNumber' => $this->input->post('createNumber'), 'table' => 'nifs_file_folder', 'departmentId' => $this->nifsDepartmentId)),
                'research_type_id' => $this->input->post('researchTypeId'),
                'is_mixx' => $this->input->post('isMixx'),
                'in_date' => $this->input->post('inDate'),
                'out_date' => $this->input->post('outDate'),
                'fname' => $this->input->post('fname'),
                'lname' => $this->input->post('lname'),
                'partner_id' => $this->input->post('partnerId'),
                'agent_name' => $this->input->post('agentName'),
                'question_id' => $this->input->post('questionId'),
                'description' => $this->input->post('description'),
                'motive_id' => $this->input->post('motiveId'),
                'protocol_number' => $this->input->post('protocolNumber'),
                'object' => $this->input->post('object'),
                'object_count' => $this->input->post('objectCount'),
                'senior_expert_id' => $this->input->post('seniorExpertId'),
                'create_expert_id' => $this->input->post('createExpertId'),
                'expert' => '',
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'protocol_number' => $this->input->post('protocolNumber'),
                'protocol_in_date' => $this->input->post('protocolInDate'),
                'protocol_out_date' => $this->input->post('protocolOutDate'),
                'year' => $this->session->adminCloseYear,
                'is_active' => 1,
                'order_num' => getOrderNum(array('table' => 'nifs_file_folder', 'field' => 'order_num')),
                'department_id' => $this->nifsDepartmentId,
                'param' => json_encode(
                        array(
                            'researchType' => ($this->nifsResearchTypeData != false ? $this->nifsResearchTypeData->title : ''),
                            'motive' => ($this->nifsMotiveData != false ? $this->nifsMotiveData->title : ''),
                            'partner' => ($this->partnerData != false ? $this->partnerData->title : ''),
                            'question' => ($this->nifsQuestionData != false ? $this->nifsQuestionData->title : '') . ' ' . ($this->input->post('questionId') == 38 ? $this->input->post('age') : ''),
                            'preCrime' => $queryPreCrime,
                            'category' => ($this->categoryData != false ? $this->categoryData->title : ''),
                            'seniorExpert' => ($this->seniorExpertData != false ? $this->seniorExpertData->full_name : ''),
                            'createExpert' => ($this->createExpertData != false ? $this->createExpertData->full_name : ''),
                            'solution' => '',
                            'closeType' => '')),
                'extra_expert_value' => $this->input->post('extraExpertValue'),
                'pre_crime' => json_encode($preCrime),
                'age' => $this->input->post('age')));

        if ($this->db->insert_batch($this->db->dbprefix . 'nifs_file_folder', $this->data)) {
            return $this->hrPeople->updateHrPeopleParam_model(array('modId' => $this->input->post('modId'), 'contId' => $param['getUID'], 'expertId' => $this->input->post('expertId'), 'extraExpertValue' => $this->input->post('extraExpertValue'), 'table' => 'nifs_file_folder'));
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->userdata['adminUserId'],
            'type' => LOG_TYPE_UPDATE,
            'data' => json_encode($_POST)));

        $this->expert = $preCrime = array();
        $queryPreCrime = '';

        $this->nifsResearchTypeData = $this->nifsResearchType->getData_model(array('selectedId' => $this->input->post('researchTypeId')));
        $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $this->input->post('motiveId')));
        $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->post('partnerId')));
        $this->nifsQuestionData = $this->nifsQuestion->getData_model(array('selectedId' => $this->input->post('questionId')));
        if ($this->input->post('preCrimeCreateNumber')) {
            foreach ($this->input->post('preCrimeCreateNumber') as $key => $row) {
                $queryPreCrime .= '№:' . $this->input->post('preCrimeCreateNumber[' . $key . ']') . ', ' . $this->input->post('preCrimeExpert[' . $key . ']') . ', ' . $this->input->post('preCrimeCrimeValue[' . $key . ']') . '; ';
                array_push($preCrime, array($this->input->post('preCrimeCreateNumber[' . $key . ']'), $this->input->post('preCrimeExpert[' . $key . ']'), $this->input->post('preCrimeCrimeValue[' . $key . ']')));
            }
        }
        $queryPreCrime = rtrim($queryPreCrime, '; ');
        $this->categoryData = $this->category->getData_model(array('selectedId' => $this->input->post('catId')));
        $this->seniorExpertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->post('seniorExpertId')));
        $this->createExpertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->post('createExpertId')));

        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'create_number' => $this->input->post('createNumber'),
            'research_type_id' => $this->input->post('researchTypeId'),
            'is_mixx' => $this->input->post('isMixx'),
            'in_date' => $this->input->post('inDate'),
            'out_date' => $this->input->post('outDate'),
            'fname' => $this->input->post('fname'),
            'lname' => $this->input->post('lname'),
            'partner_id' => $this->input->post('partnerId'),
            'agent_name' => $this->input->post('agentName'),
            'question_id' => $this->input->post('questionId'),
            'description' => $this->input->post('description'),
            'motive_id' => $this->input->post('motiveId'),
            'protocol_number' => $this->input->post('protocolNumber'),
            'object' => $this->input->post('object'),
            'object_count' => $this->input->post('objectCount'),
            'senior_expert_id' => $this->input->post('seniorExpertId'),
            'create_expert_id' => $this->input->post('createExpertId'),
            'expert' => '',
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'protocol_number' => $this->input->post('protocolNumber'),
            'protocol_in_date' => $this->input->post('protocolInDate'),
            'protocol_out_date' => $this->input->post('protocolOutDate'),
            'year' => $this->session->adminCloseYear,
            'is_active' => 1,
            'order_num' => getOrderNum(array('table' => 'nifs_file_folder', 'field' => 'order_num')),
            'param' => json_encode(
                    array(
                        'researchType' => ($this->nifsResearchTypeData != false ? $this->nifsResearchTypeData->title : ''),
                        'motive' => ($this->nifsMotiveData != false ? $this->nifsMotiveData->title : ''),
                        'partner' => ($this->partnerData != false ? $this->partnerData->title : ''),
                        'question' => ($this->nifsQuestionData != false ? $this->nifsQuestionData->title : '') . ' ' . ($this->input->post('questionId') == 38 ? $this->input->post('age') : ''),
                        'preCrime' => $queryPreCrime,
                        'category' => ($this->categoryData != false ? $this->categoryData->title : ''),
                        'seniorExpert' => ($this->seniorExpertData != false ? $this->seniorExpertData->full_name : ''),
                        'createExpert' => ($this->createExpertData != false ? $this->createExpertData->full_name : ''),
                        'solution' => '',
                        'closeType' => '')),
            'extra_expert_value' => $this->input->post('extraExpertValue'),
            'pre_crime' => json_encode($preCrime),
            'age' => $this->input->post('age'),
            'year' => $this->session->adminCloseYear);
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'nifs_file_folder', $this->data)) {
            return $this->hrPeople->updateHrPeopleParam_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('id'), 'expertId' => $this->input->post('expertId'), 'extraExpertValue' => $this->input->post('extraExpertValue'), 'table' => 'nifs_file_folder'));
        }
        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {

        $row = $this->getData_model(array('selectedId' => $this->input->post('id')));
        $this->slog->log_model(array(
            'modId' => $row->mod_id,
            'createdUserId' => $this->session->userdata['adminUserId'],
            'type' => LOG_TYPE_DELETE,
            'data' => json_encode($row)));

        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'nifs_file_folder')) {

            $this->db->where('mod_id', $this->modId);
            $this->db->where('cont_id', $this->input->post('id'));
            $this->db->delete($this->db->dbprefix . 'nifs_expert');

            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }

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
            'close_type_description' => $this->input->post('closeTypeDescription'),
            'solution_id' => $this->input->post('solutionId'),
            'close_type_id' => $this->input->post('closeTypeId'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'param' => json_encode($param));

        $this->db->where('id', $this->input->post('id'));
        if ($this->db->update($this->db->dbprefix . 'nifs_file_folder', $this->data)) {
            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...', 'closeDate' => $this->input->post('closeDate'), 'closeDescription' => $this->input->post('closeDescription'));
        } else {
            $this->result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }

        return $this->result;
    }

    public function searchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('createNumber')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('createNumber') . '</span>';
            $this->string .= form_hidden('createNumber', $this->input->get('createNumber'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('protocolNumber')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('protocolNumber') . '</span>';
            $this->string .= form_hidden('protocolNumber', $this->input->get('protocolNumber'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('researchTypeId')) {
            if ($this->input->get('researchTypeId') != 'all') {
                $this->nifsResearchType = $this->nifsResearchType->getData_model(array('selectedId' => $this->input->get('researchTypeId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsResearchType->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүх шинжилгээ</span>';
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

            $this->string .= '<span class="label label-default label-rounded">Хаасан огноо: ' . $this->input->get('closeInDate') . '-' . $this->input->get('closeOutDate') . '</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('closeInDate')) {

            $this->string .= '<span class="label label-default label-rounded">Хаасан огноо: ' . $this->input->get('closeInDate') . ' хойш</span>';
            $this->showResetBtn = TRUE;
        } else if ($this->input->get('closeOutDate')) {

            $this->string .= '<span class="label label-default label-rounded">Хаасан огноо: ' . $this->input->get('closeOutDate') . ' өмнөх</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('partnerId')) {
            if ($this->input->get('partnerId') != 'all') {
                $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->get('partnerId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->partnerData->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('createExpertId')) {

            if ($this->input->get('createExpertId') != 'all') {
                $this->createExpertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->get('createExpertId')));
                $this->string .= '<span class="label label-default label-rounded">Бичсэн шинжээч: ' . $this->createExpertData->full_name . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('createExpertId', $this->input->get('createExpertId'));
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
                $this->string .= '<span class="label label-default label-rounded">Бүх шинжилгээ</span>';
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

        if ($this->input->get('seniorExpertId')) {
            if ($this->input->get('seniorExpertId') != 'all') {
                $this->seniorExpertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->get('seniorExpertId')));
                $this->string .= '<span class="label label-default label-rounded">Ахалсан шинжээч: ' . $this->seniorExpertData->full_name . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('seniorExpertId', $this->input->get('seniorExpertId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('expertId')) {

            if ($this->input->get('expertId') != 'all') {
                $this->expertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->get('expertId')));
                $this->string .= '<span class="label label-default label-rounded">Шинжээч: ' . $this->expertData->full_name . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }
            $this->string .= form_hidden('expertId', $this->input->get('expertId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('statusId')) {
            $this->statusData = $this->nifsStatus->getData_model(array('selectedId' => $this->input->get('statusId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->statusData->title . '</span>';
            $this->string .= form_hidden('statusId', $this->input->get('statusId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('typeId')) {
            if ($this->input->get('typeId') != 'all') {
                $this->nifsCrimeType = $this->nifsCrimeType->getData_model(array('selectedId' => $this->input->get('typeId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCrimeType->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('typeId', $this->input->get('typeId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('questionId')) {

            if ($this->input->get('questionId') != 'all') {
                $this->nifsQuestion = $this->nifsQuestion->getData_model(array('selectedId' => $this->input->get('questionId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsQuestion->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүх шинжилгээ</span>';
            }

            $this->string .= form_hidden('questionId', $this->input->get('questionId'));
            $this->showResetBtn = TRUE;

            if ($this->input->get('questionId') == 38) {

                if ($this->input->get('age1') != '' and $this->input->get('age2') != '') {

                    $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('age1') . ' - ' . $this->input->get('age2') . ' насны хооронд</span>';
                } else if ($this->input->get('age1') != '' and $this->input->get('age2') == '') {

                    $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('age1') . ' - наснаас дээш</span>';
                } else if ($this->input->get('age1') == '' and $this->input->get('age2') != '') {

                    $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('age2') . ' - наснаас доош</span>';
                }
            }
        }

        if ($this->input->get('closeTypeId')) {
            if ($this->input->get('closeTypeId') != 'all') {
                $this->nifsCloseType = $this->nifsCloseType->getData_model(array('selectedId' => $this->input->get('closeTypeId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCloseType->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүх шинжилгээ</span>';
            }

            $this->string .= form_hidden('closeTypeId', $this->input->get('closeTypeId'));
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

                $this->string .= ' <a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            }
        }

        return ($this->showResetBtn ? '<div class="_search-result-inner"><form action="javascript:;" id="form-nifs-file-folder"> Хайлтын үр дүн: ' . $this->string . '</form></div>' : '');
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cat_id,
                create_number,
                research_type_id,
                is_mixx,
                DATE(in_date) AS in_date,
                DATE(out_date) AS out_date,
                fname,
                lname,
                partner_id,
                agent_name,
                question_id,
                question,
                description,
                pre_create_number,
                pre_expert_id,
                pre_value,
                motive_id,
                protocol_number,
                object,
                object_count,
                senior_expert_id,
                create_expert_id,
                expert,
                weight,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id,
                protocol_number,
                DATE(protocol_in_date) AS protocol_in_date,
                DATE(protocol_out_date) AS protocol_out_date,
                close_type_id,
                close_date,
                close_type_description,
                close_description,
                solution_id,
                close_type_id,
                param,
                extra_expert_value,
                pre_crime,
                age
            FROM `' . $this->db->dbprefix . 'nifs_file_folder`
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
            $queryString .= ' AND NFF.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NFF.created_user_id = -1';
        }

        if ($this->session->adminDepartmentRoleId == 2) {

            $queryString .= ' AND NFF.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        } else {

            if ($param['departmentId'] != 0) {

                $queryString .= ' AND NFF.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            } else {

                $queryString .= ' AND NFF.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
            }
        }

        if ($param['createNumber'] != 0) {
            $queryString .= ' AND NFF.create_number = ' . $param['createNumber'];
        }

        if ($param['researchTypeId'] != 0) {
            $queryString .= ' AND NFF.research_type_id = ' . $param['researchTypeId'];
        }

        if ($param['isMixx'] == 1) {
            $queryString .= ' AND NFF.is_mixx = 1';
        }

        if ($param['isMixx'] == 2) {
            $queryString .= ' AND NFF.is_mixx = 0';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NFF.in_date) AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NFF.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NFF.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NFF.out_date)';
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NFF.protocol_in_date) AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NFF.protocol_out_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NFF.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NFF.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NFF.close_date) AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NFF.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NFF.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NFF.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND NFF.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['partnerId'] != 0) {
            $queryString .= ' AND NFF.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
        }

        if ($param['questionId'] != 0) {

            $queryString .= ' AND NFF.question_id = ' . $param['questionId'];

            if ($param['questionId'] == 38) {

                if ($param['age1'] != '' and $param['age2'] != '') {

                    $queryString .= ' AND (NFF.age >= ' . floatval($param['age1']) . ' AND NFF.age <= ' . floatval($param['age2']) . ')';
                } else if ($param['age1'] != '' and $param['age2'] == '') {

                    $queryString .= ' AND \'' . floatval($param['age1']) . '\' <= NFF.age';
                } else if ($param['age1'] == '' and $param['age2'] != '') {

                    $queryString .= ' AND \'' . floatval($param['age2']) . '\' >= NFF.age';
                }
            }
        }

        if ($param['preCreateNumber'] != 0) {
            $queryString .= ' AND NFF.pre_create_number = ' . $param['preCreateNumber'];
        }

        if ($param['preExpertId'] != 0) {
            $queryString .= ' AND NFF.pre_expert_id = ' . $param['preExpertId'];
        }

        if ($param['motiveId'] != 0) {
            $queryString .= ' AND NFF.motive_id = ' . $param['motiveId'];
        }

        if ($param['solutionId'] != 0) {
            $queryString .= ' AND NFF.solution_id = ' . $param['solutionId'];
        }

        if ($param['closeTypeId'] != 0) {
            $queryString .= ' AND NFF.close_type_id = ' . $param['closeTypeId'];
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND NFF.cat_id = ' . $param['catId'];
        }

        if ($param['seniorExpertId'] != 0) {
            $queryString .= ' AND NFF.senior_expert_id = ' . $param['seniorExpertId'];
        }

        if ($param['createExpertId'] != 0) {
            $queryString .= ' AND NFF.create_expert_id = ' . $param['createExpertId'];
        }

        if ($param['weight'] != 0) {
            $queryString .= ' AND NFF.weight = ' . $param['weight'];
        }

        if ($param['protocolNumber'] != 0) {
            $queryString .= ' AND NFF.protocol_number = ' . $param['protocolNumber'];
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $queryString .= ' AND LOWER(NFF.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $queryString .= ' AND LOWER(NFF.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
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

                case 5: {   //Дүгнэлтийн утга
                        $queryString .= ' AND LOWER(NEX.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' AND (
                        LOWER(NFF.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NFF.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NFF.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NFF.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
                        LOWER(NFF.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                        break;
                    }
            }
        }

        if ($param['closeDescription'] != '') {
            $queryString .= ' AND (LOWER(NFF.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        if ($param['preCrime'] != '') {

            $queryString .= ' AND LOWER(NFF.pre_crime) LIKE LOWER(\'%' . json_encode($param['preCrime']) . '%\')';
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NFF.solution_id != 0 AND NFF.close_type_id != 0 AND NFF.close_date <= NFF.out_date';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NFF.solution_id = 0 AND NFF.close_type_id = 0 AND NOW() <= NFF.out_date';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NFF.solution_id != 0 AND NFF.close_type_id != 0 AND NFF.close_date > NFF.out_date';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NFF.solution_id = 0 AND NFF.close_type_id = 0 AND NOW() > NFF.out_date';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND NFF.protocol_out_date < NFF.in_date';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NFF.solution_id = 0 AND NFF.close_type_id = 0';
        }

        //seniorExpertId
        //createExpertId
        ////expertId

        if ($param['expertId'] != 0) {

            $query = $this->db->query('
                SELECT 
                    NFF.id,
                    NFF.mod_id,
                    NFF.cat_id,
                    NFF.created_user_id,
                    NFF.create_number,
                    IF(NFF.is_mixx = 1, "Бүрэлдэхүүнтэй", "Бүрэлдэхүүнгүй") AS is_mixx,
                    DATE(NFF.in_date) AS in_date,
                    DATE(NFF.out_date) AS out_date,
                    NFF.lname,
                    NFF.fname,
                    NFF.partner_id,
                    NFF.agent_name,
                    NFF.protocol_number,
                    DATE(NFF.protocol_in_date) AS protocol_in_date,
                    DATE(NFF.protocol_out_date) AS protocol_out_date,
                    NFF.research_type_id,
                    NFF.object_count,
                    NFF.object,
                    NFF.pre_crime,
                    NFF.expert,
                    (IF(NFF.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:#ffffff;\')) AS expert_status,
                    NFF.solution_id,
                    NFF.close_description,
                    DATE(NFF.close_date) AS close_date,
                    IF(NFF.close_type_id = 22, CONCAT(\'(Зөрсөн: \', NFF.close_type_description, \'), \', NFF.description),\'\') AS description,
                    (CASE 
                        WHEN (NFF.solution_id != 0 AND NFF.close_date > NFF.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                        WHEN NFF.solution_id = 0 AND NOW() > NFF.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                        ELSE \'\'
                    END) AS row_status,
                    NFF.param
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_file_folder` AS NFF ON NE.cont_id = NFF.id AND NE.mod_id = NFF.mod_id
                WHERE NE.expert_id = ' . $param['expertId'] . $queryString . '
                ORDER BY NFF.create_number DESC');
        } else {
            $query = $this->db->query('
                SELECT 
                    NFF.id,
                    NFF.mod_id,
                    NFF.cat_id,
                    NFF.created_user_id,
                    NFF.create_number,
                    IF(NFF.is_mixx = 1, "Бүрэлдэхүүнтэй", "Бүрэлдэхүүнгүй") AS is_mixx,
                    DATE(NFF.in_date) AS in_date,
                    DATE(NFF.out_date) AS out_date,
                    NFF.lname,
                    NFF.fname,
                    NFF.partner_id,
                    NFF.agent_name,
                    NFF.protocol_number,
                    DATE(NFF.protocol_in_date) AS protocol_in_date,
                    DATE(NFF.protocol_out_date) AS protocol_out_date,
                    NFF.research_type_id,
                    NFF.object_count,
                    NFF.object,
                    NFF.pre_crime,
                    NFF.expert,
                    (IF(NFF.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:#ffffff;\')) AS expert_status,
                    NFF.solution_id,
                    NFF.close_description,
                    DATE(NFF.close_date) AS close_date,
                    IF(NFF.close_type_id = 22, CONCAT(\'(Зөрсөн: \', NFF.close_type_description, \'), \', NFF.description),\'\') AS description,
                    (CASE 
                        WHEN (NFF.solution_id != 0 AND NFF.close_date > NFF.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                        WHEN NFF.solution_id = 0 AND NOW() > NFF.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                        ELSE \'\'
                    END) AS row_status,
                    NFF.param
                FROM `gaz_nifs_file_folder` AS NFF
                WHERE 1 = 1 ' . $queryString . '
                ORDER BY NFF.create_number DESC');
        }

        if ($query->num_rows() > 0) {

            return $query->result();
        }

        return false;
    }

    public function getReportWorkInformationData_model($param = array()) {

        $htmlData = $queryStringData = $queryStringYearData = $queryStringInDateData = $queryStringObjectData = $queryStringMotiveData = $isAjaxDepartmentUrl = '';

        if ($param['departmentId'] > 0) {

            $queryStringData .= ' AND NFF.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $queryStringYearData .= ' AND NFF.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NFF.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NFF.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NFF.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NFF.in_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NFF.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $queryMotive = $this->db->query('
            SELECT 
                M.id,
                M.title,
                NFF.motive_count,
                IF(NFF.motive_count > 0, \'_row-more\', \'\') AS motive_count_class
            FROM `gaz_nifs_motive` AS M
            LEFT JOIN (
                SELECT 
                    NFF.motive_id,
                    COUNT(NFF.motive_id) AS motive_count 
                FROM `gaz_nifs_file_folder` AS NFF 
                WHERE 1 = 1 AND NFF.motive_id != 0 ' . $queryStringData . '
                GROUP BY NFF.motive_id
            ) AS NFF ON NFF.motive_id = M.id
            WHERE M.is_active = 1 AND M.cat_id = ' . $this->nifsMotiveCatId);

        $motiveNumRows = $queryMotive->num_rows();
        if ($motiveNumRows > 0) {

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

            foreach ($queryMotive->result() as $key => $row) {

                $sumTotalMotiveCount = $sumTotalMotiveCount + $row->motive_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->motive_count_class . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'motiveId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->motive_count . '</a></td>';
                $htmlData .= '</tr>';
            }
            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot _row-more"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'motiveId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $sumTotalMotiveCount . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNot = $this->db->query('
                SELECT
                    NFF.id,
                    NFF.create_number
                FROM `gaz_nifs_file_folder` AS NFF 
                WHERE NFF.motive_id = 0 ' . $queryStringData . '
                ORDER BY NFF.create_number ASC');

            if ($queryNot->num_rows() > 0) {
                $htmlExtra = 'Үндэслэл сонгоогдоогүй шинжилгээ (' . $queryNot->num_rows() . '): ';
                foreach ($queryNot->result() as $key => $rowNot) {
                    $htmlExtra .= '<a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'createNumber=' . $rowNot->create_number . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNot->create_number . '</a>';
                }
                $htmlData .= '<div class="table-responsive-inside-description">' . $htmlExtra . '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }

        $queryCategory = $this->db->query('
            SELECT
                C.id,
                C.title,
                CC.cat_count,
                IF(CC.cat_count > 0, \'\', \'\') AS cat_count_class
            FROM `gaz_category` AS C
            LEFT JOIN (
                SELECT
                    NFF.cat_id,
                    COUNT(NFF.cat_id) AS cat_count
                FROM `gaz_nifs_file_folder` AS NFF 
                WHERE NFF.cat_id != 0 ' . $queryStringData . '
                GROUP BY NFF.cat_id
            ) AS CC ON CC.cat_id = C.id
            WHERE C.mod_id = ' . $this->modId);

        if ($queryCategory->num_rows() > 0) {

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

            $i = $sumTotalCategoryCount = 0;
            foreach ($queryCategory->result() as $key => $row) {

                $sumTotalCategoryCount = $sumTotalCategoryCount + $row->cat_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'catId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count . '</a></td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="_custom-foot text-right">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $sumTotalCategoryCount . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNot = $this->db->query('
                SELECT
                    NFF.id,
                    NFF.create_number
                FROM `gaz_nifs_file_folder` AS NFF 
                WHERE NFF.cat_id = 0 ' . $queryStringData . '
                ORDER BY NFF.create_number ASC');

            if ($queryNot->num_rows() > 0) {
                $htmlExtra = 'Шинжилгээ (' . $queryNot->num_rows() . '): ';
                foreach ($queryNot->result() as $key => $rowNot) {
                    $htmlExtra .= '<a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'createNumber=' . $rowNot->create_number . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});" class="label-not-selected-content">№: ' . $rowNot->create_number . '</a>';
                }
                $htmlData .= '<div class="table-responsive-inside-description">' . $htmlExtra . '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }


        //Бүрэлдэхүүнтэй, бүрэлдэхүүнгүй шинжилгээний тайлан - эхлэл
        $query = $this->db->query('
            SELECT
                NRT.id,
                NRT.title,
                NFFRETURN.return_count,
                IF(NFFRETURN.return_count > 0, \'_row-more\', \'\') AS return_count_class,
                MIXX1.mixx1_count,
                IF(MIXX1.mixx1_count > 0, \'_row-more\', \'\') AS mixx1_count_class,
                MIXX0.mixx0_count,
                IF(MIXX0.mixx0_count > 0, \'_row-more\', \'\') AS mixx0_count_class
            FROM `gaz_nifs_research_type` AS NRT
            LEFT JOIN (
                SELECT
                    NFF.research_type_id,
                    COUNT(NFF.research_type_id) AS return_count
                FROM `gaz_nifs_file_folder` AS NFF 
                WHERE NFF.research_type_id != 0 AND NFF.solution_id = 47' . $queryStringData . '
                GROUP BY NFF.research_type_id
            ) AS NFFRETURN ON NRT.id = NFFRETURN.research_type_id
            LEFT JOIN (
                SELECT
                    NFF.research_type_id,
                    COUNT(NFF.research_type_id) AS mixx1_count
                FROM `gaz_nifs_file_folder` AS NFF 
                WHERE NFF.research_type_id != 0 AND NFF.is_mixx = 1 ' . $queryStringData . '
                GROUP BY NFF.research_type_id
            ) AS MIXX1 ON NRT.id = MIXX1.research_type_id
            LEFT JOIN (
                SELECT
                    NFF.research_type_id,
                    COUNT(NFF.research_type_id) AS mixx0_count
                FROM `gaz_nifs_file_folder` AS NFF 
                WHERE NFF.research_type_id != 0 AND NFF.is_mixx = 0 ' . $queryStringData . '
                GROUP BY NFF.research_type_id
            ) AS MIXX0 ON NRT.id = MIXX0.research_type_id
            WHERE NRT.cat_id = ' . $this->nifsResearchTypeCatId . ' AND NRT.is_active = 1');

        if ($query->num_rows() > 0) {

            $i = $sumTotalReturnCount = $sumTotalMixx1Count = $sumTotalMixx0Count = $sumMixxCount = $sumTotalMixxCount = 0;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Гарчиг</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Буцсан</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Бүрэлдэхүүнтэй</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Бүрэлдэхүүнгүй</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Тоо</th>';
            $htmlData .= '</tr>';
            $htmlData .= '</thead>';
            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $sumTotalReturnCount = $sumTotalReturnCount + $row->return_count;
                $sumTotalMixx1Count = $sumTotalMixx1Count + $row->mixx1_count;
                $sumTotalMixx0Count = $sumTotalMixx0Count + $row->mixx0_count;
                $sumMixxCount = $row->mixx1_count + $row->mixx0_count;
                $sumTotalMixxCount = $sumTotalMixxCount + $sumMixxCount;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->return_count_class . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'solutionId=47&researchTypeId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->return_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->mixx1_count_class . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'isMixx=1&researchTypeId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->mixx1_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->mixx0_count_class . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'isMixx=0&researchTypeId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->mixx0_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $sumMixxCount . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'isMixx=0&researchTypeId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumMixxCount > 0 ? $sumMixxCount : '') . '</a></td>';

                $htmlData .= '</tr>';
            }
            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'solutionId=47&researchTypeId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalReturnCount > 0 ? $sumTotalReturnCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'isMixx=1&researchTypeId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalMixx1Count > 0 ? $sumTotalMixx1Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'isMixx=2&researchTypeId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalMixx0Count > 0 ? $sumTotalMixx0Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'isMixx=0&researchTypeId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalMixxCount > 0 ? $sumTotalMixxCount : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNot = $this->db->query('
                SELECT
                    NFF.id,
                    NFF.create_number
                FROM `gaz_nifs_file_folder` AS NFF 
                WHERE NFF.research_type_id = 0 ' . $queryStringData . '
                ORDER BY NFF.create_number ASC');

            if ($queryNot->num_rows() > 0) {
                $htmlExtra = 'Шинжилгээ (' . $queryNot->num_rows() . '): ';
                foreach ($queryNot->result() as $key => $rowNot) {
                    $htmlExtra .= '<a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'createNumber=' . $rowNot->create_number . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});" class="label-not-selected-content">№: ' . $rowNot->create_number . '</a>';
                }
                $htmlData .= '<div class="table-responsive-inside-description">' . $htmlExtra . '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }
        //Бүрэлдэхүүнтэй, бүрэлдэхүүнгүй шинжилгээний тайлан - төгсгөл
        //Шийдвэрлэх асуудлын тайлан - эхлэл
        $query = $this->db->query('
            SELECT
                NQ.id,
                NQ.title,
                NFFQ.question_count,
                IF(NFFQ.question_count > 0, \'\', \'\') AS question_count_class,
                NFFRETURN.return_count,
                IF(NFFRETURN.return_count > 0, \'\', \'\') AS return_count_class
            FROM `gaz_nifs_question` AS NQ
            LEFT JOIN (
                SELECT
                    NFF.question_id,
                    COUNT(NFF.question_id) AS question_count
                FROM `gaz_nifs_file_folder` AS NFF 
                WHERE NFF.question_id != 0 ' . $queryStringData . '
                GROUP BY NFF.question_id
            ) AS NFFQ ON NQ.id = NFFQ.question_id
            LEFT JOIN (
                SELECT
                    NFF.question_id,
                    COUNT(NFF.question_id) AS return_count
                FROM `gaz_nifs_file_folder` AS NFF 
                WHERE NFF.question_id != 0 AND NFF.solution_id = 47 ' . $queryStringData . '
                GROUP BY NFF.question_id
            ) AS NFFRETURN ON NQ.id = NFFRETURN.question_id
            WHERE NQ.cat_id = ' . $this->nifsQuestionCatId . ' AND NQ.is_active = 1');

        if ($query->num_rows() > 0) {

            $i = $sumTotalQuestionCount = $sumTotalReturnCount = 0;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Гарчиг</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Буцсан</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Тоо</th>';
            $htmlData .= '</tr>';
            $htmlData .= '</thead>';
            $htmlData .= '<tbody>';


            foreach ($query->result() as $key => $row) {

                $sumTotalQuestionCount = $sumTotalQuestionCount + $row->question_count;
                $sumTotalReturnCount = $sumTotalReturnCount + $row->return_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->return_count_class . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'questionId=' . $row->id . '&solutionId=47&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->return_count . '</td>';
                $htmlData .= '<td class="text-center ' . $row->question_count_class . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'questionId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->question_count . '</td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" style="background-color:#fff !important;" class="text-right">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'questionId=all&solutionId=47&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalReturnCount > 0 ? $sumTotalReturnCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'questionId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalQuestionCount > 0 ? $sumTotalQuestionCount : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNot = $this->db->query('
                SELECT
                    NFF.id,
                    NFF.create_number
                FROM `gaz_nifs_file_folder` AS NFF 
                WHERE NFF.question_id = 0 ' . $queryStringData . '
                ORDER BY NFF.create_number ASC');

            if ($queryNot->num_rows() > 0) {
                $htmlExtra = 'Шинжилгээ (' . $queryNot->num_rows() . '): ';
                foreach ($queryNot->result() as $key => $rowNot) {
                    $htmlExtra .= '<a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'createNumber=' . $rowNot->create_number . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});" class="label-not-selected-content">№: ' . $rowNot->create_number . '</a>';
                }
                $htmlData .= '<div class="table-responsive-inside-description">' . $htmlExtra . '</div>';
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
                NFF.solution_count,
                IF(NFF.solution_count > 0, \'\', \'\') AS solution_count_class
            FROM `gaz_nifs_solution` AS NS
            LEFT JOIN (
                SELECT 
                    NFF.solution_id,
                    COUNT(NFF.solution_id) AS solution_count
                FROM `gaz_nifs_file_folder` AS NFF 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NFF.solution_id
            ) AS NFF ON NFF.solution_id = NS.id
            WHERE NS.is_active = 1 AND NS.cat_id = ' . $this->nifsSolutionCatId);

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
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'solutionId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->solution_count . '</a></td>';
                $htmlData .= '</tr>';
            }
            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'solutionId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSolutionCount > 0 ? $sumTotalSolutionCount : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNot = $this->db->query('
                SELECT
                    NFF.id,
                    NFF.create_number
                FROM `gaz_nifs_file_folder` AS NFF 
                WHERE NFF.solution_id = 0 ' . $queryStringData . '
                ORDER BY NFF.create_number ASC');

            if ($queryNot->num_rows() > 0) {
                $htmlExtra = 'Шинжилгээ (' . $queryNot->num_rows() . '): ';
                foreach ($queryNot->result() as $key => $rowNot) {
                    $htmlExtra .= '<a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'createNumber=' . $rowNot->create_number . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});" class="label-not-selected-content">№: ' . $rowNot->create_number . '</a>';
                }
                $htmlData .= '<div class="table-responsive-inside-description">' . $htmlExtra . '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }
        //Дүгнэлтээр гарсан тайлан - Төгсгөл
        //Дүгнэлтээр зөрсөн эсэх - эхлэл
        $query = $this->db->query('
            SELECT 
                NCT.id,
                NCT.title,
                NFF.close_type_count AS close_type_count,
                IF(NFF.close_type_count > 0, \'_row-more\', \'\') AS close_type_count_class
            FROM `gaz_nifs_close_type` AS NCT
            LEFT JOIN (
                SELECT 
                    NFF.close_type_id,
                    COUNT(NFF.close_type_id) AS close_type_count
                FROM `gaz_nifs_file_folder` AS NFF 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NFF.close_type_id
            ) AS NFF ON NFF.close_type_id = NCT.id
            WHERE NCT.is_active = 1 AND NCT.cat_id = ' . $this->nifsCloseTypeCatId);

        if ($query->num_rows() > 0) {

            $i = $sumTotalCloseTypeCount = 0;

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

                $sumTotalCloseTypeCount = $sumTotalCloseTypeCount + $row->close_type_count;

                $htmlData .= '<tr>';

                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->close_type_count_class . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'closeTypeId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->close_type_count . '</a></td>';
                $htmlData .= '</tr>';
            }
            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';

            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'closeTypeId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCloseTypeCount > 0 ? $sumTotalCloseTypeCount : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNot = $this->db->query('
                SELECT
                    NFF.id,
                    NFF.create_number
                FROM `gaz_nifs_file_folder` AS NFF 
                WHERE NFF.close_type_id = 0 ' . $queryStringData . '
                ORDER BY NFF.create_number ASC');

            if ($queryNot->num_rows() > 0) {
                $htmlExtra = 'Шинжилгээ (' . $queryNot->num_rows() . '): ';
                foreach ($queryNot->result() as $key => $rowNot) {
                    $htmlExtra .= '<a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'createNumber=' . $rowNot->create_number . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});" class="label-not-selected-content">№: ' . $rowNot->create_number . '</a>';
                }
                $htmlData .= '<div class="table-responsive-inside-description">' . $htmlExtra . '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }
        //Дүгнэлтээр зөрсөн эсэх - Төгсгөл

        return $htmlData;
    }

    public function getReportWeightData_model($param = array()) {

        $queryStringData = $queryStringHrPeopleData = $htmlData = $isAjaxDepartmentUrl = '';

        if ($param['departmentId'] > 0) {
            $queryStringData .= ' AND NFF.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
        }

        $query = $this->db->query('
            SELECT 
                NE.expert_id
            FROM `gaz_nifs_expert` AS NE
            INNER JOIN `gaz_nifs_file_folder` AS NFF ON NE.mod_id = NFF.mod_id AND NE.cont_id = NFF.id
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

        if ($param['reportIsClose'] == 1) {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NFF.close_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NFF.close_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NFF.close_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NFF.close_date)';
            }
        } else {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NFF.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NFF.in_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NFF.in_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NFF.out_date)';
            }
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NFF.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $query = $this->db->query('
            SELECT 
                IF(NFF.senior_expert_id > 0, NFF.senior_expert_id, NFF.create_expert_id) AS expert_id
            FROM `gaz_nifs_file_folder` AS NFF
            WHERE NFF.senior_expert_id != 0 OR NFF.create_expert_id != 0 ' . $queryStringData . '
            GROUP BY NFF.senior_expert_id, NFF.create_expert_id');

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $row) {
                $inPeopleId .= $row->expert_id . ', ';
            }
            $inPeopleId = rtrim($inPeopleId, ', ');
        }


        $query = $this->db->query('
            SELECT 
                HP.id,
                IF(HP.is_active = 1, CONCAT(\'<strong>\',SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname, \'</strong><br><div style="font-size:90%">\', HPP.title, \', \', HPD.title, \'</div>\'), CONCAT(\'<strong>\', HP.fname, \'</strong>\')) AS full_name,
                SENIOR_EXPERT.senior_expert_count,
                IF(SENIOR_EXPERT.senior_expert_count > 0, \'_row-more\', \'\') AS senior_expert_count_class,
                CREATE_EXPERT.create_expert_count,
                IF(CREATE_EXPERT.create_expert_count > 0, \'_row-more\', \'\') AS create_expert_count_class,
                EXPERT.expert_count,
                IF(EXPERT.expert_count > 0, \'_row-more\', \'\') AS expert_count_class,
                SENIOR_EXPERT.senior_object_count,
                CREATE_EXPERT.create_object_count,
                EXPERT.expert_object_count,
                CREATE_EXPERT_NORMAL_HAND.normal_count_hand,
                IF(CREATE_EXPERT_NORMAL_HAND.normal_count_hand > 0, \'_row-more\', \'\') AS normal_count_hand_class,
                CREATE_EXPERT_NORMAL_CLOSE.normal_count_close,
                IF(CREATE_EXPERT_NORMAL_CLOSE.normal_count_close > 0, \'_row-more\', \'\') AS normal_count_close_class,
                CREATE_EXPERT_CRASH_HAND.crash_count_hand,
                IF(CREATE_EXPERT_CRASH_HAND.crash_count_hand > 0, \'_row-more\', \'\') AS crash_count_hand_class,
                CREATE_EXPERT_CRASH_CLOSE.crash_count_close,
                IF(CREATE_EXPERT_CRASH_CLOSE.crash_count_close > 0, \'_row-more\', \'\') AS crash_count_close_class
            FROM `gaz_hr_people` AS HP
            INNER JOIN `gaz_hr_people_work` AS HPW ON HP.id = HPW.people_id AND HPW.is_currenty = 1
            INNER JOIN `gaz_hr_people_position` AS HPP ON HPP.id = HPW.position_id 
            INNER JOIN `gaz_hr_people_department` AS HPD ON HPD.id = HPW.department_id 
            LEFT JOIN (
                SELECT 
                    COUNT(NFF.senior_expert_id) AS senior_expert_count, 
                    NFF.senior_expert_id,
                    SUM(NFF.object_count) AS senior_object_count
                FROM gaz_nifs_file_folder AS NFF
                WHERE NFF.senior_expert_id != 0 ' . $queryStringData . '
                GROUP BY NFF.senior_expert_id
            ) SENIOR_EXPERT ON SENIOR_EXPERT.senior_expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NFF.create_expert_id,
                    COUNT(NFF.create_expert_id) AS create_expert_count ,
                    SUM(NFF.object_count) AS create_object_count
                FROM `gaz_nifs_file_folder` AS NFF 
                WHERE NFF.create_expert_id != 0 ' . $queryStringData . '
                GROUP BY NFF.create_expert_id
            ) CREATE_EXPERT ON CREATE_EXPERT.create_expert_id = HP.id
            LEFT JOIN (
                SELECT 
                    NE.expert_id,
                    COUNT(NE.expert_id) AS expert_count,
                    SUM(NFF.object_count) AS expert_object_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_file_folder` AS NFF ON NE.cont_id = NFF.id AND NE.mod_id = NFF.mod_id
                WHERE NE.expert_id != 0 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) EXPERT ON EXPERT.expert_id = HP.id
            LEFT JOIN (
                SELECT 
                    COUNT(NFF.create_expert_id) AS normal_count_hand, 
                    NFF.create_expert_id
                FROM gaz_nifs_file_folder AS NFF
                WHERE NFF.create_expert_id != 0 AND NFF.solution_id = 0 AND NFF.close_type_id = 0 AND CURDATE() <= DATE(NFF.out_date) ' . $queryStringData . '
                GROUP BY NFF.create_expert_id
            ) CREATE_EXPERT_NORMAL_HAND ON CREATE_EXPERT_NORMAL_HAND.create_expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    COUNT(NFF.create_expert_id) AS normal_count_close, 
                    NFF.create_expert_id
                FROM gaz_nifs_file_folder AS NFF
                WHERE NFF.create_expert_id != 0 AND NFF.solution_id != 0 AND NFF.close_type_id != 0 AND DATE(NFF.close_date) <= DATE(NFF.out_date) ' . $queryStringData . '
                GROUP BY NFF.create_expert_id
            ) CREATE_EXPERT_NORMAL_CLOSE ON CREATE_EXPERT_NORMAL_CLOSE.create_expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    COUNT(NFF.create_expert_id) AS crash_count_hand, 
                    NFF.create_expert_id
                FROM gaz_nifs_file_folder AS NFF
                WHERE NFF.create_expert_id != 0 AND NFF.solution_id = 0 AND NFF.close_type_id = 0 AND CURDATE() >= DATE(NFF.out_date) ' . $queryStringData . '
                GROUP BY NFF.create_expert_id
            ) CREATE_EXPERT_CRASH_HAND ON CREATE_EXPERT_CRASH_HAND.create_expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    COUNT(NFF.create_expert_id) AS crash_count_close, 
                    NFF.create_expert_id
                FROM gaz_nifs_file_folder AS NFF
                WHERE NFF.create_expert_id != 0 AND NFF.solution_id != 0 AND NFF.close_type_id != 0 AND DATE(NFF.close_date) > DATE(NFF.out_date) ' . $queryStringData . '
                GROUP BY NFF.create_expert_id
            ) CREATE_EXPERT_CRASH_CLOSE ON CREATE_EXPERT_CRASH_CLOSE.create_expert_id = HP.id 
            WHERE HP.id IN(' . $inPeopleId . ') AND (SENIOR_EXPERT.senior_object_count > 0 OR CREATE_EXPERT.create_expert_count > 0)
            ORDER BY HP.fname ASC');

        if ($query->num_rows() > 0) {

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;" rowspan="2">#</th>';
            $htmlData .= '<th rowspan="2">Овог, нэр</th>';
            $htmlData .= '<th style="width:60px;" rowspan="2" class="text-center">Нийт</th>';
            $htmlData .= '<th style="width:60px;" rowspan="2" class="text-center">Объект</th>';
            $htmlData .= '<th style="width:60px;" rowspan="2" class="text-center">Ахалсан</th>';
            $htmlData .= '<th style="width:60px;" rowspan="2" class="text-center">Бичсэн</th>';
            $htmlData .= '<th style="width:60px;" rowspan="2" class="text-center">Оролцсон</th>';
            $htmlData .= '<th style="width:120px;" colspan="2" class="text-center">Хэвийн шинжилгээ</th>';
            $htmlData .= '<th style="width:120px;" colspan="2" class="text-center">Хугацаа хэтэрсэн</th>';
            $htmlData .= '</tr>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:60px;" class="text-center">Хаагдсан</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Хийгдэж байгаа</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Хаагдсан</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Хийгдэж байгаа</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            $i = $sumTotalCount = $sumObjectCount = $sumTotalTotalCount = $sumTotalSeniorExpertCount = $sumTotalCreateExpertCount = $sumTotalExpertCount = $sumTotalObjectCount = $sumTotalNormalCountHand = $sumTotalNormalCountClose = $sumTotalCrashCountHand = $sumTotalCrashCountClose = 0;

            foreach ($query->result() as $key => $row) {

                $sumTotalCount = $row->senior_expert_count + $row->create_expert_count + $row->expert_count;
                $sumTotalTotalCount = $sumTotalTotalCount + $sumTotalCount;
                $sumObjectCount = $row->senior_object_count + $row->create_object_count + $row->expert_object_count;
                $sumTotalSeniorExpertCount = $sumTotalSeniorExpertCount + $row->senior_expert_count;
                $sumTotalCreateExpertCount = $sumTotalCreateExpertCount + $row->create_expert_count;
                $sumTotalExpertCount = $sumTotalExpertCount + $row->expert_count;
                $sumTotalObjectCount = $sumTotalObjectCount + $sumObjectCount;
                $sumTotalNormalCountHand = $sumTotalNormalCountHand + $row->normal_count_hand;
                $sumTotalNormalCountClose = $sumTotalNormalCountClose + $row->normal_count_close;
                $sumTotalCrashCountHand = $sumTotalCrashCountHand + $row->crash_count_hand;
                $sumTotalCrashCountClose = $sumTotalCrashCountClose + $row->crash_count_close;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->full_name . '</td>';
                $htmlData .= '<td class="text-center">' . ($sumTotalCount > 0 ? $sumTotalCount : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($sumObjectCount > 0 ? $sumObjectCount : '') . '</td>';
                $htmlData .= '<td class="text-center ' . $row->senior_expert_count_class . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&seniorExpertId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->senior_expert_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->create_expert_count_class . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&createExpertId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->create_expert_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->expert_count_class . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&expertId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->expert_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->normal_count_close_class . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'createExpertId=' . $row->id . '&statusId=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->normal_count_close . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->normal_count_hand_class . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'createExpertId=' . $row->id . '&statusId=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->normal_count_hand . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crash_count_close_class . '"><a href="javascript:;" class="_crime-crash-done" onclick="_initNifsFileFolder({page: 0, searchQuery: \'createExpertId=' . $row->id . '&statusId=3&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crash_count_close . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crash_count_hand_class . '"><a href="javascript:;" class="_crime-crash-hand" onclick="_initNifsFileFolder({page: 0, searchQuery: \'createExpertId=' . $row->id . '&statusId=4&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crash_count_hand . '</a></td>';

                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td>Нийт</td>';
            $htmlData .= '<td class="text-center">' . ($sumTotalTotalCount > 0 ? $sumTotalTotalCount : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($sumTotalObjectCount > 0 ? $sumTotalObjectCount : '') . '</td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&seniorExpertId=all&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSeniorExpertCount > 0 ? $sumTotalSeniorExpertCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&createExpertId=all&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCreateExpertCount > 0 ? $sumTotalCreateExpertCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&expertId=all&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalExpertCount > 0 ? $sumTotalExpertCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'createExpertId=all&statusId=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalNormalCountClose > 0 ? $sumTotalNormalCountClose : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'createExpertId=all&statusId=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalNormalCountHand > 0 ? $sumTotalNormalCountHand : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" class="_crime-crash-done" onclick="_initNifsFileFolder({page: 0, searchQuery: \'createExpertId=all&statusId=3&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrashCountClose > 0 ? $sumTotalCrashCountClose : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" class="_crime-crash-hand" onclick="_initNifsFileFolder({page: 0, searchQuery: \'createExpertId=all&statusId=4&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrashCountHand > 0 ? $sumTotalCrashCountHand : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

//            $queryNot = $this->db->query('
//                SELECT 
//                    NE.expert_id 
//                FROM 
//                    `gaz_nifs_expert` AS NE
//                INNER JOIN `gaz_nifs_file_folder` AS NFF ON NE.cont_id = NFF.id AND NE.mod_id = NFF.mod_id
//                WHERE NE.expert_id == 0 ' . $queryStringData);

            $queryNot = $this->db->query('
                SELECT 
                    NFF.create_number 
                FROM 
                    `gaz_nifs_file_folder` AS NFF
                WHERE NFF.create_expert_id = 0 ' . $queryStringData);

            if ($queryNot->num_rows() > 0) {
                $htmlExtra = 'Шинжилгээ (' . $queryNot->num_rows() . '): ';
                foreach ($queryNot->result() as $key => $rowNot) {
                    $htmlExtra .= '<a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'createNumber=' . $rowNot->create_number . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});" class="label-not-selected-content">№: ' . $rowNot->create_number . '</a>';
                }
                $htmlData .= '<div class="table-responsive-inside-description">' . $htmlExtra . '</div>';
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
                FROM `gaz_nifs_file_folder` AS NC
                WHERE NC.partner_id != 0 AND NC.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NC.partner_id
            ) AS YEAR_PARTNER_COUNT ON YEAR_PARTNER_COUNT.partner_id = P.id
            LEFT JOIN (
                SELECT
                    NC.partner_id,
                    COUNT(NC.partner_id) AS date_partner_count
                FROM `gaz_nifs_file_folder` AS NC
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
                $htmlData .= '<td class="text-center ' . $row->year_partner_count_class . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . $row->year_partner_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->date_partner_count_class . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->date_partner_count . '</a></td>';
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
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'partnerId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalYearPartnerCount > 0 ? $sumTotalYearPartnerCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'partnerId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalDatePartnerCount > 0 ? $sumTotalDatePartnerCount : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';


            $queryNotPartner = $this->db->query('
            SELECT 
                NC.create_number
            FROM `gaz_nifs_file_folder` AS NC 
            WHERE NC.partner_id = 0 ' . $queryStringData . '
            ORDER BY NC.create_number ASC');

            if ($queryNotPartner->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ томилсон байгууллага сонгоогүй шинжилгээ (' . $queryNotPartner->num_rows() . '): ';

                foreach ($queryNotPartner->result() as $rowNotPartner) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'createNumber=' . $rowNotPartner->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNotPartner->create_number . '</a>';
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
                FROM `gaz_nifs_file_folder` AS NC
                WHERE NC.partner_id != 0 AND NC.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NC.partner_id
            ) AS YEAR_PARTNER_COUNT ON YEAR_PARTNER_COUNT.partner_id = P.id
            LEFT JOIN (
                SELECT
                    NC.partner_id,
                    COUNT(NC.partner_id) AS date_partner_count
                FROM `gaz_nifs_file_folder` AS NC
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
                $htmlData .= '<td class="text-center ' . $row->year_partner_count_class . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . $row->year_partner_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->date_partner_count_class . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->date_partner_count . '</a></td>';
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

    public function dataUpdate_model1($param = array()) {

        $query = $this->db->query('
                SELECT 
                    NFF.id, 
                    NFF.research_type_id, 
                    NFF.motive_id, 
                    NFF.partner_id,
                    NFF.question_id,
                    NFF.pre_crime,
                    NFF.cat_id,
                    NFF.senior_expert_id,
                    NFF.create_expert_id,
                    NFF.solution_id,
                    NFF.close_type_id,
                    NFF.param
                FROM `gaz_nifs_file_folder` AS NFF');

        foreach ($query->result() as $key => $row) {

            $queryPreCrime = '';

            $this->nifsResearchTypeData = $this->nifsResearchType->getData_model(array('selectedId' => $row->research_type_id));
            $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $row->motive_id));
            $this->partnerData = $this->partner->getData_model(array('selectedId' => $row->partner_id));
            $this->nifsQuestionData = $this->nifsQuestion->getData_model(array('selectedId' => $row->question_id));
            $preCrime = json_decode($row->pre_crime);
            foreach ($preCrime as $preKey => $preRow) {
                $queryPreCrime .= '№:' . $preRow['0'] . ', ' . $preRow['1'] . ', ' . $preRow['2'] . '; ';
            }
            $queryPreCrime = rtrim($queryPreCrime, '; ');
            $this->categoryData = $this->category->getData_model(array('selectedId' => $row->cat_id));
            $this->seniorExpertData = $this->hrPeople->getData_model(array('selectedId' => $row->senior_expert_id));
            $this->createExpertData = $this->hrPeople->getData_model(array('selectedId' => $row->create_expert_id));
            $this->nifsSolutionData = $this->nifsSolution->getData_model(array('selectedId' => $row->solution_id));
            $this->nifsCloseTypeData = $this->nifsCloseType->getData_model(array('selectedId' => $row->close_type_id));

            $data = array(
                'param' => json_encode(
                        array(
                            'researchType' => ($this->nifsResearchTypeData != false ? $this->nifsResearchTypeData->title : ''),
                            'motive' => ($this->nifsMotiveData != false ? $this->nifsMotiveData->title : ''),
                            'partner' => ($this->partnerData != false ? $this->partnerData->title : ''),
                            'question' => ($this->nifsQuestionData != false ? $this->nifsQuestionData->title : '') . ' ' . $this->input->post('age'),
                            'preCrime' => $queryPreCrime,
                            'category' => ($this->categoryData != false ? $this->categoryData->title : ''),
                            'seniorExpert' => ($this->seniorExpertData != false ? $this->seniorExpertData->full_name : ''),
                            'createExpert' => ($this->createExpertData != false ? $this->createExpertData->full_name : ''),
                            'solution' => ($this->nifsSolutionData != false ? $this->nifsSolutionData->title : ''),
                            'closeType' => ($this->nifsCloseTypeData != false ? $this->nifsCloseTypeData->title : ''))));

            $this->db->where('id', $row->id);
            if ($this->db->update('gaz_nifs_file_folder', $data)) {

                echo '<pre>';
                var_dump(json_decode($data['param']));
                echo '</pre>';
            }
        }
    }
    
    public function dataUpdate_model($param = array()) {

        $query = $this->db->query('
            SELECT
                NFF.id,
                NSD_TYPE_11.id AS send_document_chemical_id,
                NSD_TYPE_11.close_type_id AS send_document_chemical_close_type_id,
                NSD_TYPE_8.id AS send_document_biology_id,
                NSD_TYPE_8.close_type_id AS send_document_biology_close_type_id,
                NSD_TYPE_10.id AS send_document_bakterlogy_id,
                NSD_TYPE_10.close_type_id AS send_document_bakterlogy_close_type_id,
                NFF.param
            FROM `gaz_nifs_file_folder` AS NFF
            LEFT JOIN `gaz_nifs_send_doc` AS NSD_TYPE_11 ON NFF.mod_id = NSD_TYPE_11.module_id AND NFF.id = NSD_TYPE_11.cont_id AND NSD_TYPE_11.type_id = 11
            LEFT JOIN `gaz_nifs_send_doc` AS NSD_TYPE_8 ON NFF.mod_id = NSD_TYPE_8.module_id AND NFF.id = NSD_TYPE_8.cont_id AND NSD_TYPE_8.type_id = 8
            LEFT JOIN `gaz_nifs_send_doc` AS NSD_TYPE_10 ON NFF.mod_id = NSD_TYPE_10.module_id AND NFF.id = NSD_TYPE_10.cont_id AND NSD_TYPE_10.type_id = 10
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

            $this->db->update($this->db->dbprefix . 'nifs_file_folder', $data);
        }
    }

}
