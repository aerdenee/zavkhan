<?php

class SnifsEconomy_model extends CI_Model {

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
        $this->load->model('SnifsMasterCase_model', 'nifsMasterCase');

        $this->perPage = 2;

        $this->hrPeoplePositionId = '2,5,6,7';
        $this->hrPeopleDepartmentId = '6';

        $this->nifsCrimeTypeId = 355;
        $this->nifsQuestionCatId = 373;
        $this->nifsSolutionCatId = 361;
        $this->nifsCloseTypeCatId = 367;
        $this->nifsResearchTypeCatId = 382;
        $this->nifsMotiveCatId = 388;

        $this->modId = 56;
        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->isActiveDepartment = 'is_active_nifs_economy';
        $this->reportDefaultDayInterval = 7;
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => $this->modId,
            'cat_id' => 0,
            'is_mixx' => 0,
            'research_type_id' => 8,
            'motive_id' => 1,
            'create_number' => getCreateNumber(array('createNumber' => 0, 'table' => 'nifs_economy', 'departmentId' => $this->nifsDepartmentId)),
            'in_date' => date('Y-m-d H:i:s'),
            'out_date' => date('Y-m-d H:i:s'),
            'partner_id' => 0,
            'agent_name' => '',
            'description' => '',
            'type_id' => 0,
            'value' => '',
            'object' => '',
            'object_count' => 0,
            'question_id' => 0,
            'question' => '',
            'expert' => 0,
            'weight' => '',
            'protocol_number' => '',
            'protocol_value' => '',
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
            'case_id' => 1)));
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
                partner_id,
                agent_name,
                description,
                type_id,
                object,
                object_count,
                question_id,
                question,
                expert,
                weight,
                protocol_number,
                protocol_value,
                DATE(protocol_in_date) AS protocol_in_date,
                DATE(protocol_out_date) AS protocol_out_date,
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
                solution_id,
                extra_expert_value,
                case_id
            FROM `' . $this->db->dbprefix . 'nifs_economy`
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
            $queryString .= ' AND NEC.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NEC.created_user_id = -1';
        }

        if ($param['departmentId'] != 0) {

            $queryString .= ' AND NEC.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
        } else {
            $queryString .= ' AND NEC.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        }

        if ($param['createNumber'] != 0) {
            $queryString .= ' AND NEC.create_number = ' . $param['createNumber'];
        }

        if ($param['protocolNumber'] != 0) {
            $queryString .= ' AND NEC.protocol_number = ' . $param['protocolNumber'];
        }

        if ($param['researchTypeId'] != 0) {
            $queryString .= ' AND NEC.research_type_id = ' . $param['researchTypeId'];
        } else if ($param['researchTypeId'] == 'all') {
            $queryString .= ' AND NEC.research_type_id != 0';
        }

        if ($param['isMixx'] == 1) {
            $queryString .= ' AND NEC.is_mixx = 1';
        }

        if ($param['isMixx'] == 2) {
            $queryString .= ' AND NEC.is_mixx = 0';
        }

        if ($param['motiveId'] != 0) {
            $queryString .= ' AND NEC.motive_id = ' . $param['motiveId'];
        } else if ($param['motiveId'] == 'all') {
            $queryString .= ' AND NEC.motive_id != 0';
        }
        
        if ($param['caseId'] != 0) {
            $queryString .= ' AND NEC.case_id = ' . $param['caseId'];
        } else if ($param['caseId'] == 'all') {
            $queryString .= ' AND NEC.case_id != 0';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NEC.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NEC.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NEC.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NEC.out_date)';
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND NEC.cat_id = ' . $param['catId'];
        } else if ($param['catId'] == 'all') {
            $queryString .= ' AND NEC.cat_id != 0';
        }

        if ($param['questionId'] != 0) {
            $queryString .= ' AND NEC.question_id = ' . $param['questionId'];
        } else if ($param['questionId'] == 'all') {
            $queryString .= ' AND NEC.question_id != 0';
        }

        if ($param['partnerId'] != 0) {
            $queryString .= ' AND NEC.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
        } else if ($param['partnerId'] == 'all') {
            $queryString .= ' AND NEC.partner_id != 0';
        }

        if ($param['protocolNumber'] != 0) {
            $queryString .= ' AND NEC.protocol_number = ' . $param['protocolNumber'];
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NEC.protocol_in_date) AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NEC.protocol_in_date)';
        } elseif ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NEC.protocol_in_date)';
        } elseif ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NEC.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NEC.close_date) AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NEC.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NEC.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NEC.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND NEC.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Албан тушаалтны нэр
                        $queryString .= ' AND LOWER(NEC.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Тайлбар
                        $queryString .= ' AND LOWER(NEC.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Хэргийн утга
                        $queryString .= ' AND LOWER(NEC.protocol_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Дүгнэлтийн утга
                        $queryString .= ' AND LOWER(NEC.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' 
                        AND LOWER(NEC.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                            OR LOWER(NEC.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                            OR LOWER(NEC.object) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                            OR LOWER(NEC.protocol_value) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                            OR LOWER(NEC.question) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;
            }
        }

        if ($param['solutionId'] != 0) {
            $queryString .= ' AND NEC.solution_id = ' . $param['solutionId'];
        } else if ($param['solutionId'] == 'all') {
            $queryString .= ' AND NEC.solution_id != 0';
        }

        if ($param['weight'] != 0) {
            $queryString .= ' AND NEC.weight = ' . $param['weight'];
        }

        if ($param['closeTypeId'] != 0) {
            $queryString .= ' AND NEC.close_type_id = ' . $param['closeTypeId'];
        } else if ($param['closeTypeId'] == 'all') {
            $queryString .= ' AND NEC.close_type_id != 0';
        }

        if ($param['typeId'] != 0) {
            $queryString .= ' AND NEC.type_id = ' . $param['typeId'];
        } else if ($param['typeId'] == 'all') {
            $queryString .= ' AND NEC.type_id != 0';
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NEC.solution_id != 0 AND DATE(NEC.close_date) <= DATE(NEC.out_date)';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NEC.solution_id = 0 AND CURDATE() <= DATE(NEC.out_date)';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NEC.solution_id != 0 AND DATE(NEC.close_date) > DATE(NEC.out_date)';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NEC.solution_id = 0 AND CURDATE() >= DATE(NEC.out_date)';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND DATE(NEC.protocol_out_date) < DATE(NEC.in_date)';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NEC.solution_id = 0';
        }

        if ($param['closeDate'] != '') {
            $queryString .= ' AND DATE(NEC.close_date) = DATE(\'' . $param['closeDate'] . '\')';
        }

        if ($param['closeDescription'] != '') {

            $queryString .= ' AND (LOWER(NEC.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        if ($param['expertId'] != 0) {
            $query = $this->db->query('
                SELECT 
                    NE.expert_id 
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_economy` AS NEC ON NE.cont_id = NEC.id AND NE.mod_id = NEC.mod_id
                WHERE NE.expert_id = ' . $param['expertId'] . $queryString . '
                GROUP BY NEC.id');
        } else {
            $query = $this->db->query('
                SELECT 
                    NEC.id
                FROM `gaz_nifs_economy` AS NEC
                WHERE 1 = 1 ' . $queryString . ' 
                GROUP BY NEC.id');
        }
        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $data = array();

        $query = $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and !$auth->your->read) {
            $queryString .= ' AND NEC.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NEC.created_user_id = -1';
        }

        if ($param['departmentId'] != 0) {

            $queryString .= ' AND NEC.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
        } else {
            $queryString .= ' AND NEC.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        }

        if ($param['createNumber'] != 0) {
            $queryString .= ' AND NEC.create_number = ' . $param['createNumber'];
        }

        if ($param['protocolNumber'] != 0) {
            $queryString .= ' AND NEC.protocol_number = ' . $param['protocolNumber'];
        }

        if ($param['researchTypeId'] != 0) {
            $queryString .= ' AND NEC.research_type_id = ' . $param['researchTypeId'];
        } else if ($param['researchTypeId'] == 'all') {
            $queryString .= ' AND NEC.research_type_id != 0';
        }

        if ($param['isMixx'] == 1) {
            $queryString .= ' AND NEC.is_mixx = 1';
        }

        if ($param['isMixx'] == 2) {
            $queryString .= ' AND NEC.is_mixx = 0';
        }

        if ($param['motiveId'] != 0) {
            $queryString .= ' AND NEC.motive_id = ' . $param['motiveId'];
        } else if ($param['motiveId'] == 'all') {
            $queryString .= ' AND NEC.motive_id != 0';
        }

        if ($param['caseId'] != 0) {
            $queryString .= ' AND NEC.case_id = ' . $param['caseId'];
        } else if ($param['caseId'] == 'all') {
            $queryString .= ' AND NEC.case_id != 0';
        }
        
        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NEC.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NEC.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NEC.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NEC.out_date)';
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND NEC.cat_id = ' . $param['catId'];
        } else if ($param['catId'] == 'all') {
            $queryString .= ' AND NEC.cat_id != 0';
        }

        if ($param['questionId'] != 0) {
            $queryString .= ' AND NEC.question_id = ' . $param['questionId'];
        } else if ($param['questionId'] == 'all') {
            $queryString .= ' AND NEC.question_id != 0';
        }

        if ($param['partnerId'] != 0) {
            $queryString .= ' AND NEC.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
        } else if ($param['partnerId'] == 'all') {
            $queryString .= ' AND NEC.partner_id != 0';
        }

        if ($param['protocolNumber'] != 0) {
            $queryString .= ' AND NEC.protocol_number = ' . $param['protocolNumber'];
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NEC.protocol_in_date) AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NEC.protocol_in_date)';
        } elseif ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NEC.protocol_in_date)';
        } elseif ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NEC.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NEC.close_date) AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NEC.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NEC.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NEC.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND NEC.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Албан тушаалтны нэр
                        $queryString .= ' AND LOWER(NEC.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Тайлбар
                        $queryString .= ' AND LOWER(NEC.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Хэргийн утга
                        $queryString .= ' AND LOWER(NEC.protocol_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Дүгнэлтийн утга
                        $queryString .= ' AND LOWER(NEC.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' 
                        AND LOWER(NEC.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                            OR LOWER(NEC.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                            OR LOWER(NEC.object) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                            OR LOWER(NEC.protocol_value) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                            OR LOWER(NEC.question) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;
            }
        }

        if ($param['solutionId'] != 0) {
            $queryString .= ' AND NEC.solution_id = ' . $param['solutionId'];
        } else if ($param['solutionId'] == 'all') {
            $queryString .= ' AND NEC.solution_id != 0';
        }

        if ($param['weight'] != 0) {
            $queryString .= ' AND NEC.weight = ' . $param['weight'];
        }

        if ($param['closeTypeId'] != 0) {
            $queryString .= ' AND NEC.close_type_id = ' . $param['closeTypeId'];
        } else if ($param['closeTypeId'] == 'all') {
            $queryString .= ' AND NEC.close_type_id != 0';
        }

        if ($param['typeId'] != 0) {
            $queryString .= ' AND NEC.type_id = ' . $param['typeId'];
        } else if ($param['typeId'] == 'all') {
            $queryString .= ' AND NEC.type_id != 0';
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NEC.solution_id != 0 AND DATE(NEC.close_date) <= DATE(NEC.out_date)';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NEC.solution_id = 0 AND CURDATE() <= DATE(NEC.out_date)';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NEC.solution_id != 0 AND DATE(NEC.close_date) > DATE(NEC.out_date)';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NEC.solution_id = 0 AND CURDATE() >= DATE(NEC.out_date)';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND DATE(NEC.protocol_out_date) < DATE(NEC.in_date)';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NEC.solution_id = 0';
        }

        if ($param['closeDate'] != '') {
            $queryString .= ' AND DATE(NEC.close_date) = DATE(\'' . $param['closeDate'] . '\')';
        }

        if ($param['closeDescription'] != '') {

            $queryString .= ' AND (LOWER(NEC.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        if ($param['expertId'] != 0) {

            $query = $this->db->query('
                SELECT 
                    NEC.id,
                    NEC.mod_id,
                    NEC.cat_id,
                    NEC.created_user_id,
                    NEC.create_number,
                    IF(NEC.is_mixx = 1, "<span class=\'fa fa-users\'></span>", "") AS is_mixx,
                    CONCAT("И: ", DATE(NEC.in_date), "<br>", "Д: ", DATE(NEC.out_date)) AS in_out_date,
                    NEC.partner_id,
                    NEC.protocol_number,
                    NEC.agent_name,
                    IF(NEC.protocol_in_date != \'\', CONCAT(\'И:\', DATE(NEC.protocol_in_date)), \'\') AS protocol_in_date,
                    IF(NEC.protocol_out_date != \'\', CONCAT(\'Д:\', DATE(NEC.protocol_out_date)), \'\') AS protocol_out_date,
                    IF(NEC.object_count > 0, CONCAT(\' <strong>(\', NEC.object_count, \')</strong> \', NEC.object), \'\') AS object,
                    NEC.protocol_value,
                    NEC.question_id,
                    NEC.question,
                    NEC.expert,
                    (IF(NEC.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:#ffffff;\')) AS expert_status,
                    (IF(NEC.weight > 0, NEC.weight, \'\')) AS weight,
                    NEC.solution_id,
                    NEC.close_description,
                    DATE(NEC.close_date) AS close_date,
                    (CASE 
                        WHEN (NEC.solution_id != 0 AND NEC.close_date > NEC.out_date) THEN \'background-color: #4CAF50; color:#ffffff;\'
                        WHEN NEC.solution_id = 0 AND NOW() > NEC.out_date THEN \'background-color: #F44336; color:#ffffff;\'
                        ELSE \'\'
                    END) AS row_status,
                    DATE(NEC.close_date) AS close_date,
                    NEC.close_description,
                    NEC.description,
                    NEC.param
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_economy` AS NEC ON NE.cont_id = NEC.id AND NE.mod_id = NEC.mod_id
                WHERE NE.expert_id = ' . $param['expertId'] . $queryString . '
                GROUP BY NEC.id
                ORDER BY NEC.create_number DESC
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        } else {
            $query = $this->db->query('
                SELECT 
                    NEC.id,
                    NEC.mod_id,
                    NEC.cat_id,
                    NEC.created_user_id,
                    NEC.create_number,
                    IF(NEC.is_mixx = 1, "<span class=\'fa fa-users\'></span>", "") AS is_mixx,
                    CONCAT("И: ", DATE(NEC.in_date), "<br>", "Д: ", DATE(NEC.out_date)) AS in_out_date,
                    NEC.partner_id,
                    NEC.protocol_number,
                    NEC.agent_name,
                    IF(NEC.protocol_in_date != \'\', CONCAT(\'И:\', DATE(NEC.protocol_in_date)), \'\') AS protocol_in_date,
                    IF(NEC.protocol_out_date != \'\', CONCAT(\'Д:\', DATE(NEC.protocol_out_date)), \'\') AS protocol_out_date,
                    IF(NEC.object_count > 0, CONCAT(\' <strong>(\', NEC.object_count, \')</strong> \', NEC.object), \'\') AS object,
                    NEC.protocol_value,
                    NEC.question_id,
                    NEC.question,
                    NEC.expert,
                    (IF(NEC.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:#ffffff;\')) AS expert_status,
                    (IF(NEC.weight > 0, NEC.weight, \'\')) AS weight,
                    NEC.solution_id,
                    NEC.close_description,
                    DATE(NEC.close_date) AS close_date,
                    (CASE 
                        WHEN (NEC.solution_id != 0 AND NEC.close_date > NEC.out_date) THEN \'background-color: #4CAF50; color:#ffffff;\'
                        WHEN NEC.solution_id = 0 AND NOW() > NEC.out_date THEN \'background-color: #F44336; color:#ffffff;\'
                        ELSE \'\'
                    END) AS row_status,
                    DATE(NEC.close_date) AS close_date,
                    NEC.close_description,
                    NEC.description,
                    NEC.param
                FROM `gaz_nifs_economy` AS NEC
                WHERE 1 = 1 ' . $queryString . '
                ORDER BY NEC.create_number DESC
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        }

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $param = json_decode($row->param);

                array_push($data, array(
                    'id' => $row->id,
                    'create_number' => $row->create_number,
                    'is_mixx' => $row->is_mixx,
                    'in_out_date' => $row->in_out_date,
                    'partner_agent_date' => ('<strong>' . $row->protocol_number . '</strong> <br>' . $param->partner . ' ' . $row->agent_name . '<br>' . $row->protocol_in_date . '<br>' . $row->protocol_out_date),
                    'protocol_value' => $row->protocol_value,
                    'object' => $row->object,
                    'question' => $row->question,
                    'expert_status' => $row->expert_status,
                    'expert' => $row->expert,
                    'weight' => $row->weight,
                    'report' => ($row->solution_id > 0 ? ($row->close_description . '<br>' . $row->close_date) : ''),
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
                'create_number' => getCreateNumber(array('createNumber' => $this->input->post('createNumber'), 'table' => 'nifs_economy', 'departmentId' => $this->nifsDepartmentId)),
                'in_date' => $this->input->post('inDate'),
                'out_date' => $this->input->post('outDate'),
                'partner_id' => $this->input->post('partnerId'),
                'agent_name' => $this->input->post('agentName'),
                'description' => $this->input->post('description'),
                'type_id' => $this->input->post('typeId'),
                'object' => $this->input->post('object'),
                'object_count' => $this->input->post('objectCount'),
                'question_id' => $this->input->post('questionId'),
                'question' => $this->input->post('question'),
                'expert' => '',
                'protocol_number' => $this->input->post('protocolNumber'),
                'protocol_value' => $this->input->post('protocolValue'),
                'protocol_in_date' => $this->input->post('protocolInDate'),
                'protocol_out_date' => $this->input->post('protocolOutDate'),
                'department_id' => $this->nifsDepartmentId,
                'extra_expert_value' => $this->input->post('extraExpertValue'),
                'param' => json_encode(array(
                    'researchType' => ($this->nifsResearchTypeData ? $this->nifsResearchTypeData->title : ''),
                    'motive' => ($this->nifsMotiveData ? $this->nifsMotiveData->title : ''),
                    'partner' => ($this->partnerData ? $this->partnerData->title : ''),
                    'type' => ($this->nifsCrimeTypeData ? $this->nifsCrimeTypeData->title : ''),
                    'question' => ($this->nifsQuestionData ? $this->nifsQuestionData->title : '') . ' ' . $this->input->post('question'),
                    'solution' => ''
                )),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'order_num' => getOrderNum(array('table' => 'nifs_economy', 'field' => 'order_num')),
                'is_active' => 1,
                'year' => $this->session->adminCloseYear,
                'case_id' => $this->input->post('caseId')));

        if ($this->db->insert_batch($this->db->dbprefix . 'nifs_economy', $this->data)) {
            return $this->hrPeople->updateHrPeopleParam_model(array('modId' => $this->input->post('modId'), 'contId' => $param['getUID'], 'expertId' => $this->input->post('expertId'), 'extraExpertValue' => $this->input->post('extraExpertValue'), 'table' => 'nifs_economy'));
        }
        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->userdata['adminUserId'],
            'type' => LOG_TYPE_UPDATE,
            'data' => json_encode($_POST)));

        $this->nifsResearchTypeData = $this->nifsResearchType->getData_model(array('selectedId' => $this->input->post('researchTypeId')));
        $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $this->input->post('motiveId')));
        $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->post('partnerId')));
        $this->nifsCrimeTypeData = $this->nifsCrimeType->getData_model(array('selectedId' => $this->input->post('typeId')));
        $this->nifsQuestionData = $this->nifsQuestion->getData_model(array('selectedId' => $this->input->post('questionId')));

        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'is_mixx' => $this->input->post('isMixx'),
            'research_type_id' => $this->input->post('researchTypeId'),
            'motive_id' => $this->input->post('motiveId'),
            'create_number' => $this->input->post('createNumber'),
            'in_date' => $this->input->post('inDate'),
            'out_date' => $this->input->post('outDate'),
            'partner_id' => $this->input->post('partnerId'),
            'agent_name' => $this->input->post('agentName'),
            'description' => $this->input->post('description'),
            'type_id' => $this->input->post('typeId'),
            'object' => $this->input->post('object'),
            'object_count' => $this->input->post('objectCount'),
            'question_id' => $this->input->post('questionId'),
            'question' => $this->input->post('question'),
            'protocol_number' => $this->input->post('protocolNumber'),
            'protocol_value' => $this->input->post('protocolValue'),
            'protocol_in_date' => $this->input->post('protocolInDate'),
            'protocol_out_date' => $this->input->post('protocolOutDate'),
            'extra_expert_value' => $this->input->post('extraExpertValue'),
            'param' => json_encode(array(
                'researchType' => ($this->nifsResearchTypeData ? $this->nifsResearchTypeData->title : ''),
                'motive' => ($this->nifsMotiveData ? $this->nifsMotiveData->title : ''),
                'partner' => ($this->partnerData ? $this->partnerData->title : ''),
                'type' => ($this->nifsCrimeTypeData ? $this->nifsCrimeTypeData->title : ''),
                'question' => ($this->nifsQuestionData ? $this->nifsQuestionData->title : '') . ' ' . $this->input->post('question'),
                'solution' => ''
            )),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'year' => $this->session->adminCloseYear,
            'case_id' => $this->input->post('caseId'));

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'nifs_economy', $this->data)) {
            return $this->hrPeople->updateHrPeopleParam_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('id'), 'expertId' => $this->input->post('expertId'), 'extraExpertValue' => $this->input->post('extraExpertValue'), 'table' => 'nifs_economy'));
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
        if ($this->db->delete($this->db->dbprefix . 'nifs_economy')) {

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

        $extraData = $this->getData_model(array('selectedId' => $this->input->post('id')));
        $param = json_decode($extraData->param);

        $param->solution = ($this->nifsSolutionData != false ? $this->nifsSolutionData->title : '');

        $this->data = array(
            'close_date' => $this->input->post('closeDate'),
            'weight' => $this->input->post('weight'),
            'close_description' => $this->input->post('closeDescription'),
            'solution_id' => $this->input->post('solutionId'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'param' => json_encode($param)
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'nifs_economy', $this->data)) {
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
            $this->string .= '<span class="label label-default label-rounded">Бүртгэлийн дугаар: ' . $this->input->get('createNumber') . '</span>';
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

        if ($this->input->get('createExpertId')) {
            if ($this->input->get('createExpertId') != 'all') {
                $this->createExpertData = $this->nifsExpert->getData_model(array('selectedId' => $this->input->get('createExpertId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->createExpertData->full_name . '</span>';
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

        if ($this->input->get('motiveId')) {
            if ($this->input->get('motiveId') != 'all') {
                $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $this->input->get('motiveId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsMotiveData->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүх үндэслэл</span>';
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
                $this->nifsQuestionData = $this->nifsQuestion->getData_model(array('selectedId' => $this->input->get('questionId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsQuestionData->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('questionId', $this->input->get('questionId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('expertId')) {
            if ($this->input->get('expertId') != 'all') {
                $this->expertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->get('expertId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->expertData->full_name . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('expertId', $this->input->get('expertId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('caseId')) {

            if ($this->input->get('caseId') != 'all') {
                $this->nifsMaseterCaseData = $this->nifsMasterCase->getData_model(array('selectedId' => $this->input->get('caseId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsMaseterCaseData->title . '</span>';
            }  else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('typeId', $this->input->get('typeId'));
            $this->showResetBtn = TRUE;
        }
        
        if ($this->input->get('typeId')) {

            if ($this->input->get('typeId') != 'all') {
                $this->nifsCrimeType = $this->nifsCrimeType->getData_model(array('selectedId' => $this->input->get('typeId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCrimeType->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүх шинжилгээ</span>';
            }
            $this->string .= form_hidden('typeId', $this->input->get('typeId'));
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

        if ($this->showResetBtn) {
            //' . Scrime::$path . $param['path'] . '/' . $param['modId'] . '

            if ($this->input->get('isReportMenu') == 1) {

                $this->string .= ' <a href="javascript:;" onclick="_reportItem({reportMenuId: ' . $this->input->get('reportMenuId') . ', reportModId: ' . $this->input->get('reportModId') . '});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            } else {

                $this->string .= ' <a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            }
        }

        return ($this->showResetBtn ? '<div class="_search-result-inner"><form action="javascript:;" id="form-nifs-economy"> Хайлтын үр дүн: ' . $this->string . '</form></div>' : '');
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
                partner_id,
                agent_name,
                description,
                type_id,
                object,
                object_count,
                question_id,
                question,
                expert,
                weight,
                protocol_number,
                protocol_value,
                DATE(protocol_in_date) AS protocol_in_date,
                DATE(protocol_out_date) AS protocol_out_date,
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
                solution_id,
                extra_expert_value
            FROM `' . $this->db->dbprefix . 'nifs_economy`
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
        } elseif ($auth->our->read and ! $auth->your->read == 0) {
            $queryString .= ' AND NEC.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND NEC.created_user_id = -1';
        }

        if ($this->session->adminDepartmentRoleId == 2) {

            $queryString .= ' AND NEC.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        } else {

            if ($param['departmentId'] != 0) {

                $queryString .= ' AND NEC.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            }
        }

        if ($param['createNumber'] != 0) {
            $queryString .= ' AND NEC.create_number = ' . $param['createNumber'];
        }

        if ($param['protocolNumber'] != 0) {
            $queryString .= ' AND NEC.protocol_number = ' . $param['protocolNumber'];
        }

        if ($param['researchTypeId'] != 0) {
            $queryString .= ' AND NEC.research_type_id = ' . $param['researchTypeId'];
        }

        if ($param['isMixx'] == 1) {
            $queryString .= ' AND NEC.is_mixx = 1';
        }

        if ($param['isMixx'] == 2) {
            $queryString .= ' AND NEC.is_mixx = 0';
        }

        if ($param['motiveId'] != 0) {
            $queryString .= ' AND NEC.motive_id = ' . $param['motiveId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NEC.in_date) AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NEC.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NEC.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NEC.out_date)';
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND NEC.cat_id = ' . $param['catId'];
        }

        /*
         * ӨМНӨХ ХЭРГИЙН ДУГААРЫГ ХАЙХ ХЭСЭГ ЭНД ОРЖ ИРНЭ. ГЭХДНЭЭ ӨМНӨХ ХЭРЭГТЭЙ ХОЛБООТОЙ ХЭСГИЙГ БҮХ БҮРТГЭЛИЙН ХУВЬД БАГЦААР НЬ НЭГ АЯТАЙХАН ШИЙДЭЛ ОЛЖ ХИЙХ ХЭРЭГТЭЙ БАЙГАА
         */

        if ($param['questionId'] != 0) {
            $queryString .= ' AND NEC.question_id = ' . $param['questionId'];
        }

        if ($param['partnerId'] != 0) {
            $queryString .= ' AND NEC.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
        }

        if ($param['protocolNumber'] != 0) {
            $queryString .= ' AND NEC.protocol_number = ' . $param['protocolNumber'];
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NEC.protocol_in_date) AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NEC.protocol_in_date)';
        } elseif ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NEC.protocol_in_date)';
        } elseif ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NEC.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NEC.close_date) AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NEC.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NEC.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NEC.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND NEC.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Албан тушаалтны нэр
                        $queryString .= ' AND LOWER(NEC.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Тайлбар
                        $queryString .= ' AND LOWER(NEC.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Хэргийн утга
                        $queryString .= ' AND LOWER(NEC.protocol_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Дүгнэлтийн утга
                        $queryString .= ' AND LOWER(NEC.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' 
                        AND LOWER(NEC.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                            OR LOWER(NEC.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                            OR LOWER(NEC.object) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                            OR LOWER(NEC.protocol_value) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                            OR LOWER(NEC.question) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;
            }
        }

        if ($param['solutionId'] != 0) {
            $queryString .= ' AND NEC.solution_id = ' . $param['solutionId'];
        }

        if ($param['weight'] != 0) {
            $queryString .= ' AND NEC.weight = ' . $param['weight'];
        }

        if ($param['closeTypeId'] != 0) {
            $queryString .= ' AND NEC.close_type_id = ' . $param['closeTypeId'];
        }

        if ($param['typeId'] != 0) {
            $queryString .= ' AND NEC.type_id = ' . $param['typeId'];
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NEC.solution_id != 0 AND NEC.close_date < NEC.out_date';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NEC.solution_id = 0 AND NOW() <= NEC.out_date';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NEC.solution_id != 0 AND NEC.close_date > NEC.out_date';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NEC.solution_id = 0 AND DATE(NOW()) > DATE(NEC.out_date)';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND NEC.protocol_out_date < NEC.in_date';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NEC.solution_id = 0';
        }

        if ($param['closeDescription'] != '') {

            $queryString .= ' AND (LOWER(NEC.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        if ($param['expertId'] != 0) {

            $query = $this->db->query('
                SELECT 
                    NEC.id,
                    NEC.mod_id,
                    NEC.cat_id,
                    NEC.created_user_id,
                    NEC.create_number,
                    NEC.param,
                    IF(NEC.is_mixx = 1, "Бүрэлдэхүүнтэй", "Бүрэлдэхүүнгүй") AS is_mixx,
                    DATE(NEC.in_date) AS in_date,
                    DATE(NEC.out_date) AS out_date,
                    NEC.partner_id,
                    NEC.protocol_number,
                    NEC.agent_name,
                    IF(NEC.protocol_in_date != \'\', CONCAT(\'И:\', DATE(NEC.protocol_in_date)), \'\') AS protocol_in_date,
                    IF(NEC.protocol_out_date != \'\', CONCAT(\'Д:\', DATE(NEC.protocol_out_date)), \'\') AS protocol_out_date,
                    NEC.object_count,
                    NEC.object,
                    NEC.protocol_value,
                    NEC.question_id,
                    NEC.question,
                    NEC.expert,
                    (IF(NEC.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:#ffffff;\')) AS expert_status,
                    (IF(NEC.weight > 0, NEC.weight, \'\')) AS weight,
                    NEC.solution_id,
                    NEC.close_description,
                    DATE(NEC.close_date) AS close_date,
                    (CASE 
                        WHEN (NEC.solution_id != 0 AND NEC.close_date > NEC.out_date) THEN \'background-color: #4CAF50; color:#ffffff;\'
                        WHEN NEC.solution_id = 0 AND NOW() > NEC.out_date THEN \'background-color: #F44336; color:#ffffff;\'
                        ELSE \'\'
                    END) AS row_status,
                    NEC.description,
                    NEC.param
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_economy` AS NEC ON NE.cont_id = NEC.id AND NE.mod_id = NEC.mod_id
                WHERE NE.expert_id = ' . $param['expertId'] . $queryString . '
                GROUP BY NEC.id
                ORDER BY NEC.create_number DESC');
        } else {
            $query = $this->db->query('
                SELECT 
                    NEC.id,
                    NEC.mod_id,
                    NEC.cat_id,
                    NEC.created_user_id,
                    NEC.create_number,
                    NEC.param,
                    IF(NEC.is_mixx = 1, "Бүрэлдэхүүнтэй", "Бүрэлдэхүүнгүй") AS is_mixx,
                    DATE(NEC.in_date) AS in_date,
                    DATE(NEC.out_date) AS out_date,
                    NEC.partner_id,
                    NEC.protocol_number,
                    NEC.agent_name,
                    IF(NEC.protocol_in_date != \'\', CONCAT(\'И:\', DATE(NEC.protocol_in_date)), \'\') AS protocol_in_date,
                    IF(NEC.protocol_out_date != \'\', CONCAT(\'Д:\', DATE(NEC.protocol_out_date)), \'\') AS protocol_out_date,
                    NEC.object_count,
                    NEC.object,
                    NEC.protocol_value,
                    NEC.question_id,
                    NEC.question,
                    NEC.expert,
                    (IF(NEC.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:#ffffff;\')) AS expert_status,
                    (IF(NEC.weight > 0, NEC.weight, \'\')) AS weight,
                    NEC.solution_id,
                    NEC.close_description,
                    DATE(NEC.close_date) AS close_date,
                    (CASE 
                        WHEN (NEC.solution_id != 0 AND NEC.close_date > NEC.out_date) THEN \'background-color: #4CAF50; color:#ffffff;\'
                        WHEN NEC.solution_id = 0 AND NOW() > NEC.out_date THEN \'background-color: #F44336; color:#ffffff;\'
                        ELSE \'\'
                    END) AS row_status,
                    NEC.description,
                    NEC.param
                FROM `gaz_nifs_economy` AS NEC
                WHERE 1 = 1 ' . $queryString . '
                ORDER BY NEC.create_number DESC');
        }

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return false;
    }

    public function getReportWorkInformationData_model($param = array()) {

        $htmlData = $queryStringData = $queryStringYearData = $queryStringInDateData = $queryStringObjectData = $queryStringMotiveData = $isAjaxDepartmentUrl = '';

        if ($param['departmentId'] > 0) {

            $queryStringData .= ' AND NE.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $queryStringYearData .= ' AND NE.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NE.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NE.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NE.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NE.in_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NE.year = \'' . $this->session->adminCloseYear . '\'';
        }

        //Хэргийн төрлөөр тайлан - эхлэл
        //
        $query = $this->db->query('
            SELECT
                NCT.id,
                NCT.cat_id,
                NCT.title,
                YEAR_COUNT.year_type_count,
                IF(YEAR_COUNT.year_type_count > 0, \'_row-more\', \'\') AS year_type_count_class,
                YEAR_COUNT.year_object_count,
                YEAR_COUNT.year_weight_count,
                DATE_COUNT.date_type_count,
                IF(DATE_COUNT.date_type_count > 0, \'_row-more\', \'\') AS date_type_count_class,
                DATE_COUNT.date_object_count,
                DATE_COUNT.date_weight_count,
                RETURN_COUNT.return_count,
                IF(RETURN_COUNT.return_count > 0, \'_row-more\', \'\') AS return_count_class,
                MIXX1_COUNT.mixx1_count,
                IF(MIXX1_COUNT.mixx1_count > 0, \'_row-more\', \'\') AS mixx1_count_class,
                MIXX0_COUNT.mixx0_count,
                IF(MIXX0_COUNT.mixx0_count > 0, \'_row-more\', \'\') AS mixx0_count_class,
                NORMAL_COUNT_CLOSE.normal_count_close,
                IF(NORMAL_COUNT_CLOSE.normal_count_close > 0, \'_row-more\', \'\') AS normal_count_close_class,
                NORMAL_COUNT_HAND.normal_count_hand,
                IF(NORMAL_COUNT_HAND.normal_count_hand > 0, \'_row-more\', \'\') AS normal_count_hand_class,
                CRASH_COUNT_CLOSE.crash_count_close,
                IF(CRASH_COUNT_CLOSE.crash_count_close > 0, \'_row-more\', \'\') AS crash_count_close_class,
                CRASH_COUNT_HAND.crash_count_hand,
                IF(CRASH_COUNT_HAND.crash_count_hand > 0, \'_row-more\', \'\') AS crash_count_hand_class
            FROM `gaz_nifs_crime_type` AS NCT
            LEFT JOIN (
                SELECT
                    NE.type_id,
                    COUNT(NE.type_id) AS year_type_count,
                    SUM(NE.object_count) AS year_object_count,
                    SUM(NE.weight) AS year_weight_count
                FROM `gaz_nifs_economy` AS NE 
                WHERE NE.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NE.type_id
            ) AS YEAR_COUNT ON NCT.id = YEAR_COUNT.type_id
            LEFT JOIN (
                SELECT
                    NE.type_id,
                    COUNT(NE.type_id) AS date_type_count,
                    SUM(NE.object_count) AS date_object_count,
                    SUM(NE.weight) AS date_weight_count
                FROM `gaz_nifs_economy` AS NE 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NE.type_id
            ) AS DATE_COUNT ON NCT.id = DATE_COUNT.type_id
            LEFT JOIN (
                SELECT
                    NE.type_id,
                    COUNT(NE.type_id) AS return_count
                FROM `gaz_nifs_economy` AS NE 
                WHERE NE.solution_id = 45 ' . $queryStringData . '
                GROUP BY NE.type_id
            ) AS RETURN_COUNT ON NCT.id = RETURN_COUNT.type_id
            LEFT JOIN (
                SELECT
                    NE.type_id,
                    COUNT(NE.type_id) AS mixx1_count
                FROM `gaz_nifs_economy` AS NE 
                WHERE NE.is_mixx = 1 ' . $queryStringData . '
                GROUP BY NE.type_id
            ) AS MIXX1_COUNT ON NCT.id = MIXX1_COUNT.type_id
            LEFT JOIN (
                SELECT
                    NE.type_id,
                    COUNT(NE.type_id) AS mixx0_count
                FROM `gaz_nifs_economy` AS NE 
                WHERE NE.is_mixx = 0 ' . $queryStringData . '
                GROUP BY NE.type_id
            ) AS MIXX0_COUNT ON NCT.id = MIXX0_COUNT.type_id
            LEFT JOIN (
                SELECT 
                    NE.type_id, 
                    count(NE.type_id) AS normal_count_close 
                FROM `gaz_nifs_economy` AS NE 
                WHERE NE.solution_id != 0 AND DATE(NE.close_date) <= DATE(NE.out_date) ' . $queryStringData . '
                GROUP BY NE.type_id
            ) AS NORMAL_COUNT_CLOSE ON NORMAL_COUNT_CLOSE.type_id = NCT.id
            LEFT JOIN (
                SELECT 
                    NE.type_id, 
                    count(NE.type_id) AS normal_count_hand 
                FROM `gaz_nifs_economy` AS NE 
                WHERE NE.solution_id = 0 AND CURDATE() <= DATE(NE.out_date) ' . $queryStringData . '
                GROUP BY NE.type_id
            ) AS NORMAL_COUNT_HAND ON NORMAL_COUNT_HAND.type_id = NCT.id
            
            LEFT JOIN (
                SELECT 
                    NE.type_id, 
                    count(NE.type_id) AS crash_count_close 
                FROM `gaz_nifs_economy` AS NE 
                WHERE NE.solution_id != 0 AND DATE(NE.close_date) > DATE(NE.out_date) ' . $queryStringData . '
                GROUP BY NE.type_id
            ) AS CRASH_COUNT_CLOSE ON CRASH_COUNT_CLOSE.type_id = NCT.id
            LEFT JOIN (
                SELECT 
                    NE.type_id, 
                    count(NE.type_id) AS crash_count_hand 
                FROM `gaz_nifs_economy` AS NE 
                WHERE NE.solution_id = 0 AND CURDATE() >= DATE(NE.out_date) ' . $queryStringData . '
                GROUP BY NE.type_id
            ) AS CRASH_COUNT_HAND ON CRASH_COUNT_HAND.type_id = NCT.id
            WHERE NCT.cat_id = ' . $this->nifsCrimeTypeId . ' AND NCT.is_active = 1');

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

            $i = $sumTotalYearTypeCount = $sumTotalYearObjectCount = $sumTotalYearWeightCount = $sumTotalDateTypeCount = $sumTotalDateObjectCount = $sumTotalReturnCount = $sumTotalMixx1Count = $sumTotalMixx0Count = $sumTotalNormalCountHand = $sumTotalNormalCountClose = $sumTotalCrashCountClose = $sumTotalCrashCountHand = 0;

            foreach ($query->result() as $key => $row) {

                $sumTotalYearTypeCount = $sumTotalYearTypeCount + $row->year_type_count;
                $sumTotalYearObjectCount = $sumTotalYearObjectCount + $row->year_object_count;
                $sumTotalYearWeightCount = $sumTotalYearWeightCount + $row->year_weight_count;
                $sumTotalDateTypeCount = $sumTotalDateTypeCount + $row->date_type_count;
                $sumTotalReturnCount = $sumTotalReturnCount + $row->return_count;
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
                $htmlData .= '<td class="text-center ' . $row->year_type_count_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'typeId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . $row->year_type_count . '</a></td>';
                $htmlData .= '<td class="text-center">' . $row->year_object_count . '</td>';
                $htmlData .= '<td class="text-center">' . $row->year_weight_count . '</td>';
                $htmlData .= '<td class="text-center ' . $row->date_type_count_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'typeId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->date_type_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->return_count_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'typeId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&solutionId=45&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->return_count . '</a></td>';
                $htmlData .= '<td class="text-center">' . $row->date_object_count . '</td>';
                $htmlData .= '<td class="text-center ' . $row->mixx1_count_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'typeId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&isMixx=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->mixx1_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->mixx0_count_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'typeId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&isMixx=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->mixx0_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->normal_count_close_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'typeId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&statusId=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->normal_count_close . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->normal_count_hand_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'typeId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&statusId=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->normal_count_hand . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crash_count_close_class . '"><a href="javascript:;" class="_crime-crash-done" onclick="_initNifsEconomy({page: 0, searchQuery: \'typeId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&statusId=3&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crash_count_close . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crash_count_hand_class . '"><a href="javascript:;" class="_crime-crash-hand" onclick="_initNifsEconomy({page: 0, searchQuery: \'typeId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&statusId=4&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crash_count_hand . '</a></td>';
                $htmlData .= '</tr>';
            }
            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'typeId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalYearTypeCount > 0 ? $sumTotalYearTypeCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalYearObjectCount > 0 ? $sumTotalYearObjectCount : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalYearWeightCount > 0 ? $sumTotalYearWeightCount : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'typeId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalDateTypeCount > 0 ? $sumTotalDateTypeCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'typeId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&solutionId=45&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalReturnCount > 0 ? $sumTotalReturnCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalDateObjectCount > 0 ? $sumTotalDateObjectCount : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'typeId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&isMixx=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalMixx1Count > 0 ? $sumTotalMixx1Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'typeId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&isMixx=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalMixx0Count > 0 ? $sumTotalMixx0Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'typeId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&statusId=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalNormalCountClose > 0 ? $sumTotalNormalCountClose : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'typeId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&statusId=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalNormalCountHand > 0 ? $sumTotalNormalCountHand : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" class="_crime-crash-done" onclick="_initNifsEconomy({page: 0, searchQuery: \'typeId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&statusId=3&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrashCountClose > 0 ? $sumTotalCrashCountClose : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" class="_crime-crash-hand" onclick="_initNifsEconomy({page: 0, searchQuery: \'typeId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&statusId=4&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrashCountHand > 0 ? $sumTotalCrashCountHand : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNotMotive = $this->db->query('
            SELECT 
                NE.create_number
            FROM `gaz_nifs_economy` AS NE 
            WHERE NE.type_id = 0 ' . $queryStringData . '
            ORDER BY NE.create_number ASC');

            if ($queryNotMotive->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Хэргийн төрөл сонгоогүй шинжилгээ (' . $queryNotMotive->num_rows() . '): ';

                foreach ($queryNotMotive->result() as $rowNotMotive) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'createNumber=' . $rowNotMotive->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNotMotive->create_number . '</a>';
                }
                $htmlData .= '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }
        //Хэргийн төрлөөр тайлан - төгсгөл
        
        //Хэргийн төрөл тайлан - эхлэл
        $query = $this->db->query('
            SELECT
                NMC.id,
                NMC.title,
                NE.case_count,
                IF(NE.case_count > 0, \'_row-more\', \'\') AS case_count_class
            FROM `gaz_nifs_master_case` AS NMC
            LEFT JOIN (
                SELECT
                    NE.case_id,
                    COUNT(NE.case_id) AS case_count
                FROM `gaz_nifs_economy` AS NE 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NE.case_id
            ) AS NE ON NMC.id = NE.case_id
            WHERE NMC.mod_id = 90 AND NMC.is_active = 1');

        $caseNumRows = $query->num_rows();
        if ($caseNumRows > 0) {

            $i = 1;
            $sumTotalCaseCount = 0;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px; text-align:center; padding-top:10px; padding-bottom:10px;">#</th>';
            $htmlData .= '<th>Хэргийн төрөл</th>';
            $htmlData .= '<th style="width:100px; text-align:center;">Нийт</th>';
            $htmlData .= '</tr>';
            $htmlData .= '</thead>';
            $htmlData .= '<tbody>';
            foreach ($query->result() as $keyCase => $rowCase) {

                $sumTotalCaseCount = $sumTotalCaseCount + $rowCase->case_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i++ . '</td>';
                $htmlData .= '<td>' . $rowCase->title . '</td>';
                $htmlData .= '<td class="text-center ' . $rowCase->case_count_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'caseId=' . $rowCase->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $rowCase->case_count . '</a></td>';
                $htmlData .= '</tr>';
            }
            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';

            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . $sumTotalCaseCount . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNotCrimeType = $this->db->query('
            SELECT
                NE.id,
                NE.create_number
            FROM `gaz_nifs_economy` AS NE
            WHERE NE.case_id = 0 ' . $queryStringData . '
            ORDER BY NE.create_number ASC');

            if ($queryNotCrimeType->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Хэргийн төрөл сонгоогүй шинжилгээ (' . $queryNotCrimeType->num_rows() . '): ';

                foreach ($queryNotCrimeType->result() as $rowNotCrimeType) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'createNumber=' . $rowNotCrimeType->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNotCrimeType->create_number . '</a>';
                }
                $htmlData .= '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }
        //Хэргийн төрөл тайлан - төгсгөл
        
        //Үндэслэлээр тайлан харуулах эхлэл
        $query = $this->db->query('
            SELECT 
                M.id,
                M.title,
                ECONOMY.motive_count,
                IF(ECONOMY.motive_count > 0, \'_row-more\', \'\') AS motive_count_class
            FROM `gaz_nifs_motive` AS M
            LEFT JOIN (
                SELECT 
                    NE.motive_id, 
                    COUNT(NE.motive_id) AS motive_count 
                FROM `gaz_nifs_economy` AS NE
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NE.motive_id) AS ECONOMY ON ECONOMY.motive_id = M.id
            WHERE M.is_active = 1 AND M.cat_id = ' . $this->nifsMotiveCatId);

        if ($query->num_rows() > 0) {

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

            $i = $sumTotalMotiveCount = 0;

            foreach ($query->result() as $keyMotive => $rowMotive) {

                $sumTotalMotiveCount = $sumTotalMotiveCount + $rowMotive->motive_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $rowMotive->title . '</td>';
                $htmlData .= '<td class="text-center ' . $rowMotive->motive_count_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'motiveId=' . $rowMotive->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $rowMotive->motive_count . '</a></td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'motiveId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalMotiveCount > 0 ? $sumTotalMotiveCount : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNotMotive = $this->db->query('
            SELECT 
                NE.create_number
            FROM `gaz_nifs_economy` AS NE 
            WHERE NE.motive_id = 0 ' . $queryStringData . '
            ORDER BY NE.create_number ASC');

            if ($queryNotMotive->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ хаах шийдвэр сонгоогүй шинжилгээ (' . $queryNotMotive->num_rows() . '): ';

                foreach ($queryNotMotive->result() as $rowNotMotive) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'createNumber=' . $rowNotMotive->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNotMotive->create_number . '</a>';
                }
                $htmlData .= '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }
        //Үндэслэлээр тайлан харуулах төгсгөл
        
        //Бүрэлдэхүүнтэй, бүрэлдэхүүнгүй шинжилгээний тайлан - эхлэл
        $query = $this->db->query('
            SELECT
                NRT.id,
                NRT.title,
                MIXX1.mixx1_count,
                IF(MIXX1.mixx1_count > 0, \'_row-more\', \'\') AS mixx1_count_class,
                MIXX0.mixx0_count,
                IF(MIXX0.mixx0_count > 0, \'_row-more\', \'\') AS mixx0_count_class
            FROM `gaz_nifs_research_type` AS NRT
            LEFT JOIN (
                SELECT
                    NE.research_type_id,
                    COUNT(NE.research_type_id) AS mixx1_count
                FROM `gaz_nifs_economy` AS NE
                WHERE NE.is_mixx = 1 ' . $queryStringData . '
                GROUP BY NE.research_type_id
            ) AS MIXX1 ON NRT.id = MIXX1.research_type_id
            LEFT JOIN (
                SELECT
                    NE.research_type_id,
                    COUNT(NE.research_type_id) AS mixx0_count
                FROM `gaz_nifs_economy` AS NE
                WHERE NE.is_mixx = 0 ' . $queryStringData . '
                GROUP BY NE.research_type_id
            ) AS MIXX0 ON NRT.id = MIXX0.research_type_id
            WHERE NRT.cat_id = ' . $this->nifsResearchTypeCatId . ' AND NRT.is_active = 1');

        if ($query->num_rows() > 0) {

            $i = $sumTotalMixx1Count = $sumTotalMixx0Count = $sumTotalMixxCount = $sumMixxCount = 0;

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

            foreach ($query->result() as $key => $row) {

                $sumTotalMixx1Count = $sumTotalMixx1Count + $row->mixx1_count;
                $sumTotalMixx0Count = $sumTotalMixx0Count + $row->mixx0_count;
                $sumMixxCount = $row->mixx1_count + $row->mixx0_count;
                $sumTotalMixxCount = $sumTotalMixxCount + $sumMixxCount;


                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->mixx1_count_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'researchTypeId=' . $row->id . '&isMixx=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->mixx1_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->mixx0_count_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'researchTypeId=' . $row->id . '&isMixx=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->mixx0_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . ($sumMixxCount > 0 ? '' : '') . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'researchTypeId=' . $row->id . '&isMixx=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumMixxCount > 0 ? $sumMixxCount : '') . '</a></td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'researchTypeId=all&isMixx=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $sumTotalMixx1Count . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'researchTypeId=all&isMixx=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $sumTotalMixx0Count . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'researchTypeId=all&isMixx=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $sumTotalMixxCount . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNotMixx = $this->db->query('
            SELECT 
                NE.create_number
            FROM `gaz_nifs_economy` AS NE 
            WHERE NE.research_type_id = 0 ' . $queryStringData . '
            ORDER BY NE.create_number ASC');

            if ($queryNotMixx->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ (' . $queryNotMotive->num_rows() . '): ';

                foreach ($queryNotMixx->result() as $rowNotMixx) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'createNumber=' . $rowNotMixx->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNotMixx->create_number . '</a>';
                }
                $htmlData .= '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }
        //Бүрэлдэхүүнтэй, бүрэлдэхүүнгүй шинжилгээний тайлан - төгсгөл
        //Шийдвэрлэсэн байдал - Эхлэл
        $query = $this->db->query('
            SELECT 
                M.id,
                M.title,
                NC.solution_count AS solution_count,
                IF(NC.solution_count > 0, \'_row-more\', \'\') AS solution_count_class
            FROM `gaz_nifs_solution` AS M
            LEFT JOIN (
                SELECT 
                    NE.solution_id,
                    COUNT(NE.solution_id) AS solution_count
                FROM `gaz_nifs_economy` AS NE 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NE.solution_id
            ) AS NC ON NC.solution_id = M.id
            WHERE M.is_active = 1 AND M.cat_id = ' . $this->nifsSolutionCatId);

        if ($query->num_rows() > 0) {

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px; text-align:center; padding-top:10px; padding-bottom:10px;">#</th>';
            $htmlData .= '<th>Шинжилгээ</th>';
            $htmlData .= '<th style="width:100px; text-align:center;">Нийт</th>';
            $htmlData .= '</tr>';
            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            $i = $sumTotalSolutionCount = 0;
            foreach ($query->result() as $keySolution => $rowSolution) {

                $sumTotalSolutionCount = $sumTotalSolutionCount + $rowSolution->solution_count;
                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $rowSolution->title . '</td>';
                $htmlData .= '<td class="text-center ' . $rowSolution->solution_count_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'solutionId=' . $rowSolution->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $rowSolution->solution_count . '</a></td>';
                $htmlData .= '</tr>';
            }
            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'solutionId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $sumTotalSolutionCount . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNotMixx = $this->db->query('
            SELECT 
                NE.create_number
            FROM `gaz_nifs_economy` AS NE 
            WHERE NE.solution_id = 0 ' . $queryStringData . '
            ORDER BY NE.create_number ASC');

            if ($queryNotMixx->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ (' . $queryNotMotive->num_rows() . '): ';

                foreach ($queryNotMixx->result() as $rowNotMixx) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'createNumber=' . $rowNotMixx->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNotMixx->create_number . '</a>';
                }
                $htmlData .= '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }
        //Шийдвэрлэсэн байдал - Төгсгөл

        return $htmlData;
    }

    public function getReportWeightData_model($param = array()) {
        $queryStringData = $queryStringYearData = $htmlData = $isAjaxDepartmentUrl = '';

        if ($param['departmentId'] > 0) {

            $queryStringData .= ' AND NE.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $queryStringYearData .= ' AND NE.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NE.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NE.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NE.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NE.in_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NE.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $query = $this->db->query('
            SELECT 
                NEP.expert_id
            FROM `gaz_nifs_expert` AS NEP
            INNER JOIN `gaz_nifs_economy` AS NE ON NEP.mod_id = NE.mod_id AND NEP.cont_id = NE.id
            INNER JOIN `gaz_hr_people_work` AS HPW ON NEP.expert_id = HPW.people_id AND HPW.is_currenty = 1
            WHERE 1 = 1 AND NE.department_id = ' . $param['departmentId'] . $queryStringData . '
            GROUP BY NEP.expert_id');

        $inPeopleId = '';//NIFS_EXTRA_EXPERT;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $row) {
                $inPeopleId .= $row->expert_id . ', ';
            }
        }
        $inPeopleId = rtrim($inPeopleId, ', ');

        $query = $this->db->query('
            SELECT 
                HP.id,
                HP.email,
                HP.pic,
                HP.lname,
                HP.fname,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name,
                HPP.title AS position_title,
                HPD.title AS department_title,
                C.year_total_count,
                IF(C.year_total_count > 0, \'_row-more\', \'\') AS year_total_count_class,
                INTERVAL_CRIME_COUNT.interval_total_count,
                IF(INTERVAL_CRIME_COUNT.interval_total_count > 0, \'_row-more\', \'\') AS interval_total_count_class,
                MY.mixx_yes,
                IF(MY.mixx_yes > 0, \'_row-more\', \'\') AS mixx_yes_class,
                MN.mixx_no,
                IF(MN.mixx_no > 0, \'_row-more\', \'\') AS mixx_no_class,
                IF(INTERVAL_CRIME_COUNT.weight > 0, INTERVAL_CRIME_COUNT.weight, \'\') AS weight,
                IF(INTERVAL_CRIME_COUNT.object_count > 0, INTERVAL_CRIME_COUNT.object_count, \'\') AS object_count,
                NC_CLOSE_NORMAL.normal_count_close,
                IF(NC_CLOSE_NORMAL.normal_count_close > 0, \'_row-more\', \'\') AS normal_count_close_class,
                NC_HAND_NORMAL.normal_count_hand,
                IF(NC_HAND_NORMAL.normal_count_hand > 0, \'_row-more\', \'\') AS normal_count_hand_class,
                NC_CRASH_DONE.crash_count_close,
                IF(NC_CRASH_DONE.crash_count_close > 0, \'_row-more\', \'\') AS crash_count_close_class,
                NC_CRASH_HAND.crash_count_hand,
                IF(NC_CRASH_HAND.crash_count_hand > 0, \'_row-more\', \'\') AS crash_count_hand_class
            FROM `gaz_hr_people` AS HP
            INNER JOIN `gaz_hr_people_work` AS HPW ON HP.id = HPW.people_id AND HPW.is_currenty = 1
            INNER JOIN `gaz_hr_people_position` AS HPP ON HPP.id = HPW.position_id 
            INNER JOIN `gaz_hr_people_department` AS HPD ON HPD.id = HPW.department_id 
            LEFT JOIN (
                SELECT 
                    NEP.expert_id,
                    COUNT(NEP.expert_id) AS year_total_count 
                FROM `gaz_nifs_expert` AS NEP 
                INNER JOIN `gaz_nifs_economy` AS NE ON NEP.mod_id = NE.mod_id AND NEP.cont_id = NE.id
                WHERE 1 = 1' . $queryStringYearData . '
                GROUP BY NEP.expert_id
            ) C ON C.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NEP.expert_id,
                    COUNT(NEP.expert_id) AS interval_total_count,
                    SUM(NE.weight) AS weight,
                    SUM(NE.object_count) AS object_count
                FROM `gaz_nifs_expert` AS NEP 
                INNER JOIN `gaz_nifs_economy` AS NE ON NEP.mod_id = NE.mod_id AND NEP.cont_id = NE.id
                WHERE 1 = 1' . $queryStringData . '
                GROUP BY NEP.expert_id
            ) INTERVAL_CRIME_COUNT ON INTERVAL_CRIME_COUNT.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NEP.expert_id,
                    COUNT(NE.is_mixx) AS mixx_yes 
                FROM `gaz_nifs_expert` AS NEP 
                INNER JOIN `gaz_nifs_economy` AS NE ON NEP.mod_id = NE.mod_id AND NEP.cont_id = NE.id
                WHERE NE.is_mixx = 1' . $queryStringData . '
                GROUP BY NEP.expert_id
            ) MY ON MY.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NEP.expert_id,
                    COUNT(NE.is_mixx) AS mixx_no 
                FROM `gaz_nifs_expert` AS NEP 
                INNER JOIN `gaz_nifs_economy` AS NE ON NEP.mod_id = NE.mod_id AND NEP.cont_id = NE.id
                WHERE NE.is_mixx = 0' . $queryStringData . '
                GROUP BY NEP.expert_id
            ) MN ON MN.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NEP.expert_id, 
                    count(NEP.expert_id) AS normal_count_close
                FROM `gaz_nifs_expert` AS NEP
                INNER JOIN `gaz_nifs_economy` AS NE ON NEP.mod_id = NE.mod_id AND NEP.cont_id = NE.id
                WHERE NE.solution_id != 0 AND DATE(NE.close_date) < DATE(NE.out_date) ' . $queryStringData . '
                GROUP BY NEP.expert_id
            ) NC_CLOSE_NORMAL ON NC_CLOSE_NORMAL.expert_id = HP.id
            LEFT JOIN (
                SELECT 
                    NEP.expert_id, 
                    COUNT(NEP.expert_id) AS normal_count_hand 
                FROM `gaz_nifs_expert` AS NEP
                INNER JOIN `gaz_nifs_economy` AS NE ON NEP.mod_id = NE.mod_id AND NEP.cont_id = NE.id
                WHERE NE.solution_id = 0 AND CURDATE() <= DATE(NE.out_date) ' . $queryStringData . '
                GROUP BY NEP.expert_id
            ) NC_HAND_NORMAL ON NC_HAND_NORMAL.expert_id = HP.id
            LEFT JOIN (
                SELECT
                    NEP.expert_id,
                    COUNT(NEP.expert_id) AS crash_count_close
                FROM `gaz_nifs_expert` AS NEP
                INNER JOIN `gaz_nifs_economy` AS NE ON NEP.mod_id = NE.mod_id AND NEP.cont_id = NE.id
                WHERE NE.solution_id != 0 AND DATE(NE.close_date) > DATE(NE.out_date) ' . $queryStringData . '
                GROUP BY NEP.expert_id
            ) NC_CRASH_DONE ON NC_CRASH_DONE.expert_id = HP.id 
            LEFT JOIN (
                SELECT
                    NEP.expert_id,
                    COUNT(NEP.expert_id) AS crash_count_hand
                FROM `gaz_nifs_expert` AS NEP
                INNER JOIN `gaz_nifs_economy` AS NE ON NEP.mod_id = NE.mod_id AND NEP.cont_id = NE.id
                WHERE NE.solution_id = 0 AND CURDATE() >= DATE(NE.out_date) ' . $queryStringData . '
                GROUP BY NEP.expert_id
            ) NC_CRASH_HAND ON NC_CRASH_HAND.expert_id = HP.id 
            WHERE HP.id IN (' . $inPeopleId . ') ');

        if ($query->num_rows() > 0) {

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="2" style="width:30px;">#</th>';
            $htmlData .= '<th rowspan="2">Овог, нэр</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Нийт шинжилгээ <span style="font-weight:normal;">/оны эхнээс/</span></th>';
            $htmlData .= '<th colspan="5" class="text-center">' . date('Y.m.d', strtotime($param['inDate'])) . ' - ' . date('Y.m.d', strtotime($param['outDate'])) . '</th>';
            $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Хэвийн шинжилгээ</th>';
            $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Хугацаа хэтэрсэн</th>';
            $htmlData .= '</tr>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Ирсэн шинжилгээ</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Бүрэлдэхүүнтэй</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Бүрэлдэхүүнгүй</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Ачаалал</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Хийсэн шинжилгээний объект</th>';

            $htmlData .= '<th style="width:80px;" class="text-center">Хаагдсан</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хийж байгаа</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хаагдсан</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хийж байгаа</th>';

            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            $i = $sumCrime = $sumIntervalTotalCount = $sumMixxNo = $sumMixxYes = $sumWeight = $sumObject = $sumNormalCountHand = $sumNormalCountClose = $sumCrashCountHand = $sumCrashCountClose = 0;

            foreach ($query->result() as $key => $row) {

                $sumCrime = $sumCrime + (int) $row->year_total_count;
                $sumIntervalTotalCount = $sumIntervalTotalCount + (int) $row->interval_total_count;
                $sumMixxNo = $sumMixxNo + (int) $row->mixx_no;
                $sumMixxYes = $sumMixxYes + (int) $row->mixx_yes;
                $sumWeight = $sumWeight + (int) $row->weight;
                $sumObject = $sumObject + (int) $row->object_count;
                $sumNormalCountHand = $sumNormalCountHand + (int) $row->normal_count_hand;
                $sumNormalCountClose = $sumNormalCountClose + (int) $row->normal_count_close;
                $sumCrashCountHand = $sumCrashCountHand + (int) $row->crash_count_hand;
                $sumCrashCountClose = $sumCrashCountClose + (int) $row->crash_count_close;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td><strong>' . $row->full_name . '</strong> (' . $row->position_title . ', ' . $row->department_title . ')</td>';
                $htmlData .= '<td class="text-center ' . $row->year_total_count_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . $row->year_total_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->interval_total_count_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->interval_total_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->mixx_yes_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&isMixx=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->mixx_yes . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->mixx_no_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&isMixx=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->mixx_no . '</a></td>';
                $htmlData .= '<td class="text-center">' . $row->weight . '</td>';
                $htmlData .= '<td class="text-center">' . $row->object_count . '</td>';
                $htmlData .= '<td class="text-center ' . $row->normal_count_close_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->normal_count_close . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->normal_count_hand_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->normal_count_hand . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crash_count_close_class . '"><a href="javascript:;" class="_crime-crash-done" onclick="_initNifsEconomy({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=3&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->crash_count_close . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crash_count_hand_class . '"><a href="javascript:;" class="_crime-crash-hand" onclick="_initNifsEconomy({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=4&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->crash_count_hand . '</a></td>';

                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';

            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td>Нийт</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . $sumCrime . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . $sumIntervalTotalCount . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . $sumMixxYes . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . $sumMixxNo . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . $sumWeight . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . $sumObject . '</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $sumNormalCountClose . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $sumNormalCountHand . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" class="_crime-crash-done" onclick="_initNifsEconomy({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=3&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumCrashCountClose > 0 ? $sumCrashCountClose : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" class="_crime-crash-hand" onclick="_initNifsEconomy({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=4&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumCrashCountHand > 0 ? $sumCrashCountHand : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';
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
                FROM `gaz_nifs_economy` AS NC
                WHERE NC.partner_id != 0 AND NC.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NC.partner_id
            ) AS YEAR_PARTNER_COUNT ON YEAR_PARTNER_COUNT.partner_id = P.id
            LEFT JOIN (
                SELECT
                    NC.partner_id,
                    COUNT(NC.partner_id) AS date_partner_count
                FROM `gaz_nifs_economy` AS NC
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
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'partnerId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalYearPartnerCount > 0 ? $sumTotalYearPartnerCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'partnerId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalDatePartnerCount > 0 ? $sumTotalDatePartnerCount : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';


            $queryNotPartner = $this->db->query('
            SELECT 
                NC.create_number
            FROM `gaz_nifs_economy` AS NC 
            WHERE NC.partner_id = 0 ' . $queryStringData . '
            ORDER BY NC.create_number ASC');

            if ($queryNotPartner->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ томилсон байгууллага сонгоогүй шинжилгээ (' . $queryNotPartner->num_rows() . '): ';

                foreach ($queryNotPartner->result() as $rowNotPartner) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'createNumber=' . $rowNotPartner->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNotPartner->create_number . '</a>';
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
                FROM `gaz_nifs_economy` AS NC
                WHERE NC.partner_id != 0 AND NC.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NC.partner_id
            ) AS YEAR_PARTNER_COUNT ON YEAR_PARTNER_COUNT.partner_id = P.id
            LEFT JOIN (
                SELECT
                    NC.partner_id,
                    COUNT(NC.partner_id) AS date_partner_count
                FROM `gaz_nifs_economy` AS NC
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
                $htmlData .= '<td class="text-center ' . $row->year_partner_count_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . $row->year_partner_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->date_partner_count_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->date_partner_count . '</a></td>';
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
                    NE.id, 
                    NE.research_type_id, 
                    NE.motive_id, 
                    NE.partner_id,
                    NE.type_id,
                    NE.question_id,
                    NE.solution_id,
                    NE.close_type_id,
                    NE.param,
                    NE.question
                FROM `gaz_nifs_economy` AS NE');

        foreach ($query->result() as $key => $row) {

            $this->nifsResearchTypeData = $this->nifsResearchType->getData_model(array('selectedId' => $row->research_type_id));
            $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $row->motive_id));
            $this->partnerData = $this->partner->getData_model(array('selectedId' => $row->partner_id));
            $this->nifsCrimeTypeData = $this->nifsCrimeType->getData_model(array('selectedId' => $row->type_id));
            $this->nifsQuestionData = $this->nifsQuestion->getData_model(array('selectedId' => $row->question_id));

            $this->nifsSolutionData = $this->nifsSolution->getData_model(array('selectedId' => $row->solution_id));
            $this->nifsCloseTypeData = $this->nifsCloseType->getData_model(array('selectedId' => $row->close_type_id));

            $data = array(
                'param' => json_encode(array(
                    'researchType' => ($this->nifsResearchTypeData ? $this->nifsResearchTypeData->title : ''),
                    'motive' => ($this->nifsMotiveData ? $this->nifsMotiveData->title : ''),
                    'partner' => ($this->partnerData ? $this->partnerData->title : ''),
                    'type' => ($this->nifsCrimeTypeData ? $this->nifsCrimeTypeData->title : ''),
                    'question' => ($this->nifsQuestionData ? $this->nifsQuestionData->title : '') . ' ' . $row->question,
                    'solution' => ($this->nifsSolutionData ? $this->nifsSolutionData->title : ''))));

            $this->db->where('id', $row->id);
            if ($this->db->update('gaz_nifs_economy', $data)) {

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
                FROM `gaz_nifs_economy`');

        foreach ($this->query->result() as $key => $row) {
            if ((date('Ymd', strtotime($row->in_date)) >= date('Ymd', strtotime('2018-12-08'))) and date('Ymd', strtotime($row->in_date)) <= date('Ymd', strtotime('2019-12-06'))) {
                
                $this->db->where('id', $row->id);
                $this->db->update('gaz_nifs_economy', array('year' => '2019'));
                
            } else if ((date('Ymd', strtotime($row->in_date)) >= date('Ymd', strtotime('2019-12-07'))) and date('Ymd', strtotime($row->in_date)) <= date('Ymd', strtotime('2020-12-06'))) {
                
                $this->db->where('id', $row->id);
                $this->db->update('gaz_nifs_economy', array('year' => '2020'));
                
            }
            
        }
    }

}
