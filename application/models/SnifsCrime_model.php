<?php

class SnifsCrime_model extends CI_Model {

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
        $this->load->model('SnifsStatus_model', 'nifsStatus');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('SnifsSolution_model', 'nifsSolution');
        $this->load->model('Schart_model', 'chart');
        $this->load->model('SnifsMasterCase_model', 'nifsMasterCase');

        $this->modId = 33;
        $this->chartCatId = 399;

        $this->nifsCrimeTypeId = 353;
        $this->nifsQuestionCatId = 371;
        $this->nifsSolutionCatId = 359;
        $this->nifsCloseTypeCatId = 365;
        $this->nifsResearchTypeCatId = 380;
        $this->nifsMotiveCatId = 386;
        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->isActiveDepartment = 'is_active_nifs_crime';
        $this->reportDefaultDayInterval = 7;

        if ($this->nifsDepartmentId == 7) {
            $this->nifsDepartmentId = 3;
        }
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => $this->modId,
            'cat_id' => 0,
            'create_number' => getCreateNumber(array('createNumber' => 0, 'table' => 'nifs_crime', 'departmentId' => $this->nifsDepartmentId)),
            'in_date' => date('Y-m-d H:i:s'),
            'partner_id' => 0,
            'agent_name' => '',
            'crime_value' => '',
            'crime_object' => '',
            'object_count' => 0,
            'question' => '',
            'expert_id' => 0,
            'weight' => '',
            'close_description' => '',
            'close_date' => '',
            'out_date' => date('Y-m-d H:i:s'),
            'description' => '',
            'research_type_id' => 0,
            'crime_type_id' => 0,
            'given' => '',
            'partner_id' => 0,
            'crime_again_id' => 0,
            'motive_id' => 4,
            'department_id' => 0,
            'latent_print_expert_id' => 0,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'protocol_number' => '',
            'protocol_in_date' => date('Y-m-d H:i:s'),
            'protocol_out_date' => date('Y-m-d H:i:s'),
            'user_partner_id' => $this->session->adminPartnerId,
            'close_type_id' => 0,
            'param' => '',
            'extra_expert_value' => '',
            'is_mixx' => '',
            'short_info' => '',
            'case_id' => 0
        )));
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cat_id,
                create_number,
                DATE(in_date) AS in_date,
                partner_id,
                agent_name,
                crime_value,
                crime_object,
                object_count,
                question,
                expert,
                weight,
                close_description,
                close_date,
                DATE(out_date) AS out_date,
                description,
                research_type_id,
                crime_type_id,
                given,
                crime_again_id,
                motive_id,
                latent_print_department_id,
                latent_print_expert_id,
                solution_id,
                close_type_id,
                department_id,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id,
                protocol_number,
                IF (protocol_in_date = \'0000-00-00 00:00:00\', \'\', DATE(protocol_in_date)) AS protocol_in_date,
                IF (protocol_out_date = \'0000-00-00 00:00:00\', \'\', DATE(protocol_out_date)) AS protocol_out_date,
                param,
                is_mixx,
                short_info,
                extra_expert_value,
                case_id
            FROM `gaz_nifs_crime`
            WHERE id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function listsCount_model($param = array()) {

        $query = $queryString = $getString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {

            $queryString .= '';
        } else if ($auth->our->read and !$auth->your->read) {

            $queryString .= ' AND NC.created_user_id = ' . $this->session->adminUserId;
        } else {

            $queryString .= ' AND NC.created_user_id = -1';
        }

        if ($param['selectedId'] != 0) {

            $queryString .= ' AND NC.id = ' . $param['selectedId'];
        }

        if ($param['departmentId'] != 0) {

            $queryString .= ' AND NC.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
        } else {

            $queryString .= ' AND NC.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        }

        if ($param['catId'] != 0) {

            $queryString .= ' AND NC.cat_id = ' . $param['catId'];
        } else if ($param['catId'] == 'all') {
            $queryString .= ' AND NC.cat_id != 0';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NC.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NC.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NC.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NC.out_date)';
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NC.protocol_in_date) AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NC.protocol_in_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NC.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NC.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NC.close_date) AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NC.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NC.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeDate'] . '\') >= DATE(NC.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND NC.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['researchTypeId'] != 0) {

            $queryString .= ' AND NC.research_type_id = ' . $param['researchTypeId'];
        }

        if ($param['isMixx'] == 1) {

            $queryString .= ' AND NC.is_mixx = 1';
        }

        if ($param['isMixx'] == 2) {

            $queryString .= ' AND NC.is_mixx = 0';
        }

        if ($param['caseId'] != 0) {

            $queryString .= ' AND NC.case_id = ' . $param['caseId'];
        }

        if ($param['typeId'] != 0) {

            $queryString .= ' AND NC.crime_type_id = ' . $param['typeId'];
        }

        if ($param['motiveId'] != 0) {

            $queryString .= ' AND NC.motive_id = ' . $param['motiveId'];
        }

        if ($param['partnerId'] != 0) {

            $queryString .= ' AND NC.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
        } else if ($param['partnerId'] == 'all') {
            $queryString .= ' AND NC.partner_id != 0';
        }

        if ($param['latentPrintDepartmentId'] != 0) {

            $queryString .= ' AND NC.latent_print_department_id = ' . $param['latentPrintDepartmentId'];
        }

        if ($param['latentPrintExpertId'] != 0) {

            $queryString .= ' AND NC.latent_print_expert_id = ' . $param['latentPrintExpertId'];
        }

        if ($param['createNumber'] != '') {

            $queryString .= ' AND NC.create_number = ' . $param['createNumber'];
        }

        if ($param['protocolNumber'] != '') {

            $queryString .= ' AND NC.protocol_number LIKE (\'%' . $param['protocolNumber'] . '%\') ';
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Албан тушаалтны нэр
                        $queryString .= ' AND LOWER(NC.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Тайлбар
                        $queryString .= ' AND LOWER(NC.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Холбогдох мэдээлэл
                        $queryString .= ' AND LOWER(NC.short_info) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Хэргийн утга
                        $queryString .= ' AND LOWER(NC.crime_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Дүгнэлтийн утга
                        $queryString .= ' AND LOWER(NC.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' 
                        AND (LOWER(NC.question) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                        OR LOWER(NC.crime_object) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                        OR LOWER(NC.crime_value) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                        OR LOWER(NC.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')
                        OR LOWER(NC.short_info) LIKE LOWER(\'%' . $param['keyword'] . '%\')
                        OR LOWER(NC.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;
            }
        }

        if ($param['solutionId'] > 0) {

            $queryString .= ' AND NC.solution_id = ' . $param['solutionId'];
        }

        if ($param['closeTypeId'] > 0) {

            $queryString .= ' AND NC.close_type_id = ' . $param['closeTypeId'];
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NC.solution_id != 0 AND DATE(NC.close_date) <= DATE(NC.out_date)';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NC.solution_id = 0 AND CURDATE() <= DATE(NC.out_date)';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NC.solution_id != 0 AND DATE(NC.close_date) > DATE(NC.out_date)';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NC.solution_id = 0 AND CURDATE() >= DATE(NC.out_date)';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND DATE(NC.protocol_out_date) < DATE(NC.in_date)';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NC.solution_id = 0';
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
                INNER JOIN `gaz_nifs_crime` AS NC ON NE.cont_id = NC.id AND NE.mod_id = NC.mod_id
                WHERE 1 = 1 ' . $expertString . $queryString);
        } else {

            $query = $this->db->query('
                SELECT 
                    NC.id
                FROM `gaz_nifs_crime` AS NC
                WHERE 1 = 1 ' . $queryString);
        }

        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $data = array();
        $query = $queryString = $queryStringJoinDepartment = $queryStringDepartment = $getString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {

            $queryString .= '';
        } else if ($auth->our->read and ! $auth->your->read) {

            $queryString .= ' AND NC.created_user_id = ' . $this->session->adminUserId;
        } else {

            $queryString .= ' AND NC.created_user_id = -1';
        }

        if ($param['selectedId'] != 0) {

            $queryString .= ' AND NC.id = ' . $param['selectedId'];
        }

        if ($param['departmentId'] != 0) {

            $queryString .= ' AND NC.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
        } else {

            $queryString .= ' AND NC.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        }

        if ($param['catId'] != 0) {

            $queryString .= ' AND NC.cat_id = ' . $param['catId'];
        } else if ($param['catId'] == 'all') {
            $queryString .= ' AND NC.cat_id != 0';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NC.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NC.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NC.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NC.out_date)';
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NC.protocol_in_date) AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NC.protocol_in_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['protocolInDate'] . '\') <= DATE(NC.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['protocolOutDate'] . '\') >= DATE(NC.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NC.close_date) AND DATE(\'' . $param['closeOutDate'] . '\') >= DATE(NC.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['closeInDate'] . '\') <= DATE(NC.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['closeDate'] . '\') >= DATE(NC.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND NC.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['researchTypeId'] != 0) {

            $queryString .= ' AND NC.research_type_id = ' . $param['researchTypeId'];
        }

        if ($param['isMixx'] == 1) {

            $queryString .= ' AND NC.is_mixx = 1';
        }

        if ($param['isMixx'] == 2) {

            $queryString .= ' AND NC.is_mixx = 0';
        }

        if ($param['caseId'] != 0) {

            $queryString .= ' AND NC.case_id = ' . $param['caseId'];
        }

        if ($param['typeId'] != 0) {

            $queryString .= ' AND NC.crime_type_id = ' . $param['typeId'];
        }

        if ($param['motiveId'] != 0) {

            $queryString .= ' AND NC.motive_id = ' . $param['motiveId'];
        }

        if ($param['partnerId'] != 0) {

            $queryString .= ' AND NC.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
        } else if ($param['partnerId'] == 'all') {
            $queryString .= ' AND NC.partner_id != 0';
        }

        if ($param['latentPrintDepartmentId'] != 0) {

            $queryString .= ' AND NC.latent_print_department_id = ' . $param['latentPrintDepartmentId'];
        }

        if ($param['latentPrintExpertId'] != 0) {

            $queryString .= ' AND NC.latent_print_expert_id = ' . $param['latentPrintExpertId'];
        }

        if ($param['createNumber'] != '') {

            $queryString .= ' AND NC.create_number = ' . $param['createNumber'];
        }

        if ($param['protocolNumber'] != '') {

            $queryString .= ' AND NC.protocol_number LIKE (\'%' . $param['protocolNumber'] . '%\') ';
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Албан тушаалтны нэр
                        $queryString .= ' AND LOWER(NC.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Тайлбар
                        $queryString .= ' AND LOWER(NC.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Холбогдох мэдээлэл
                        $queryString .= ' AND LOWER(NC.short_info) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Хэргийн утга
                        $queryString .= ' AND LOWER(NC.crime_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Дүгнэлтийн утга
                        $queryString .= ' AND LOWER(NC.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' 
                        AND (LOWER(NC.question) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                        OR LOWER(NC.crime_object) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                        OR LOWER(NC.crime_value) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                        OR LOWER(NC.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')
                        OR LOWER(NC.short_info) LIKE LOWER(\'%' . $param['keyword'] . '%\')
                        OR LOWER(NC.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;
            }
        }

        if ($param['solutionId'] > 0) {

            $queryString .= ' AND NC.solution_id = ' . $param['solutionId'];
        }

        if ($param['closeTypeId'] > 0) {

            $queryString .= ' AND NC.close_type_id = ' . $param['closeTypeId'];
        }

        if ($param['solutionId'] > 0) {

            $queryString .= ' AND NC.solution_id = ' . $param['solutionId'];
        }

        if ($param['closeTypeId'] > 0) {

            $queryString .= ' AND NC.close_type_id = ' . $param['closeTypeId'];
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NC.solution_id != 0 AND DATE(NC.close_date) <= DATE(NC.out_date)';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NC.solution_id = 0 AND CURDATE() <= DATE(NC.out_date)';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NC.solution_id != 0 AND DATE(NC.close_date) > DATE(NC.out_date)';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NC.solution_id = 0 AND CURDATE() >= DATE(NC.out_date)';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND DATE(NC.protocol_out_date) < DATE(NC.in_date)';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NC.solution_id = 0';
        }

        if ($param['expertId'] != 0) {

            $expertString = 'AND NE.expert_id = ' . $param['expertId'];
            if ($param['expertId'] == 'all') {
                $expertString = ' AND NE.expert_id != 0';
            }

            $query = $this->db->query('
                SELECT 
                    NC.id,
                    NC.mod_id,
                    NC.cat_id,
                    NC.create_number,
                    IF(NC.protocol_number != \'\', CONCAT(\'<div style="display:block; font-weight:700; width:100%;">\', NC.protocol_number, \'</div>\'), \'\') AS protocol_number,
                    CONCAT(IF(DATE(NC.protocol_in_date) != \'0000-00-00\', CONCAT("(И:", DATE(NC.protocol_in_date), " "), \'\'), IF(DATE(NC.protocol_out_date) != \'0000-00-00\', CONCAT(" Д:", DATE(NC.protocol_out_date), ")"), \'\')) AS protocol_in_out_date,
                    NC.in_date,
                    CONCAT("И: ", DATE(NC.in_date), "<br>", "Д: ", DATE(NC.out_date)) AS in_out_date,
                    NC.partner_id,
                    NC.agent_name,
                    NC.crime_value,
                    IF(NC.object_count > 0, CONCAT(NC.crime_object, \' <strong>(\', NC.object_count , \')</strong>\'), \'\') AS object,
                    NC.question,
                    NC.expert,
                    (IF(NC.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:#ffffff;\')) AS expert_status,
                    (IF(NC.weight > 0, NC.weight, \'\')) AS weight,
                    NC.close_description,
                    NC.close_date,
                    NC.out_date,
                    NC.description,
                    NC.crime_type_id,
                    NC.research_type_id,
                    NC.given,
                    NC.crime_again_id,
                    NC.motive_id,
                    NC.solution_id,
                    IF(NC.solution_id = 11, CONCAT("<br><strong>Татгалзсан</strong>"), "") AS solution_description,
                    NC.latent_print_department_id,
                    NC.latent_print_expert_id,
                    NC.close_type_id,
                    NC.created_date,
                    NC.modified_date,
                    NC.created_user_id,
                    NC.modified_user_id,
                    NC.order_num,
                    NC.param,
                    IF(NC.is_mixx = 1, "<span class=\'fa fa-users\'></span>", "") AS is_mixx,
                    NE.expert_id,
                    (CASE 
                        WHEN (NC.solution_id != 0 AND NC.close_date > NC.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                        WHEN NC.solution_id = 0 AND NOW() > NC.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                        ELSE \'\'
                    END) AS row_status
            FROM 
                `gaz_nifs_expert` AS NE
            INNER JOIN `gaz_nifs_crime` AS NC ON NE.cont_id = NC.id AND NE.mod_id = NC.mod_id 
            WHERE  1 = 1 ' . $expertString . $queryString . '
            ORDER BY NC.create_number DESC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        } else {
            //CONCAT(NC.protocol_number, "<br>")
            $query = $this->db->query('
                SELECT 
                    NC.id,
                    NC.mod_id,
                    NC.cat_id,
                    NC.create_number,
                    CONCAT("И: ", DATE(NC.in_date), "<br>", "Д: ", DATE(NC.out_date)) AS in_out_date,
                    IF(NC.protocol_number != \'\', CONCAT(\'<div style="display:block; font-weight:700; width:100%;">\', NC.protocol_number, \'</div>\'), \'\') AS protocol_number,
                    CONCAT(IF(DATE(NC.protocol_in_date) != \'0000-00-00\', CONCAT("(И:", DATE(NC.protocol_in_date), " "), \'\'), IF(DATE(NC.protocol_out_date) != \'0000-00-00\', CONCAT(" Д:", DATE(NC.protocol_out_date), ")"), \'\')) AS protocol_in_out_date,
                    NC.partner_id,
                    NC.agent_name,
                    NC.crime_value,
                    IF(NC.object_count > 0, CONCAT(NC.crime_object, \' <strong>(\', NC.object_count , \')</strong>\'), \'\') AS object,
                    NC.question,
                    NC.expert,
                    (IF(NC.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:#ffffff;\')) AS expert_status,
                    (IF(NC.weight > 0, NC.weight, \'\')) AS weight,
                    NC.close_description,
                    NC.close_date,
                    NC.out_date,
                    NC.description,
                    NC.crime_type_id,
                    NC.research_type_id,
                    NC.given,
                    NC.crime_again_id,
                    NC.motive_id,
                    NC.solution_id,
                    IF(NC.solution_id = 11, CONCAT("<br><strong>Татгалзсан</strong>"), "") AS solution_description,
                    NC.latent_print_department_id,
                    NC.latent_print_expert_id,
                    NC.close_type_id,
                    NC.created_date,
                    NC.modified_date,
                    NC.created_user_id,
                    NC.modified_user_id,
                    NC.order_num,
                    NC.param,
                    IF(NC.is_mixx = 1, "<span class=\'fa fa-users\'></span>", "") AS is_mixx,
                    (CASE 
                        WHEN (NC.solution_id != 0 AND NC.close_date > NC.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                        WHEN NC.solution_id = 0 AND NOW() > NC.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                        ELSE \'\'
                    END) AS row_status
                FROM `gaz_nifs_crime` AS NC
                WHERE 1 = 1 ' . $queryString . '
                ORDER BY NC.create_number DESC
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        }

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $param = json_decode($row->param);

                $question = rtrim(str_replace('|:||:|', ', ', $row->question), ', ');

                $closeInfo = '';
                if ($row->close_date != '' and $row->close_description != '') {
                    $closeInfo = date('Y-m-d', strtotime($row->close_date)) . '<br>' . $row->close_description;
                }

                array_push($data, array(
                    'id' => $row->id,
                    'mod_id' => $row->mod_id,
                    'created_user_id' => $row->created_user_id,
                    'create_number' => $row->create_number,
                    'in_out_date' => $row->in_out_date,
                    'is_mixx' => $row->is_mixx,
                    'resolution' => $row->protocol_number . ' ' . $param->partner . ' ' . $row->agent_name . ' ' . $row->protocol_in_out_date,
                    'crime_value' => $row->crime_value . '<br>' . $param->latentPrintExpert,
                    'object' => $row->object,
                    'question' => $question,
                    'expert_status' => $row->expert_status,
                    'expert' => ($row->cat_id > 0 ? $row->expert . '<br><strong>' . $param->category . '</strong>' : $row->expert),
                    'weight' => $row->weight,
                    'report' => $closeInfo,
                    'description' => $row->description,
                    'row_status' => $row->row_status
                ));
            }
        }
        return array('data' => $data, 'search' => self::searchKeywordView_model());
    }

    public function insert_model($param = array()) {
        $this->expert = array();
        $this->question = '';

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->userdata['adminUserId'],
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        foreach ($this->input->post('question') as $key => $value) {
            $this->question .= $this->input->post('question[' . $key . ']') . '|:||:|';
        }
        $this->question = rtrim($this->question, '|:||:|');

        $this->nifsResearchTypeData = $this->nifsResearchType->getData_model(array('selectedId' => $this->input->post('researchTypeId')));
        $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $this->input->post('motiveId')));
        $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->post('partnerId')));
        $this->categoryData = $this->category->getData_model(array('selectedId' => $this->input->post('catId')));
        $this->nifsCrimeTypeData = $this->nifsCrimeType->getData_model(array('selectedId' => $this->input->post('typeId')));
        $this->nifslatentPrintExpertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->post('latentPrintExpertId')));

        $this->data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'create_number' => getCreateNumber(array('createNumber' => $this->input->post('createNumber'), 'table' => 'nifs_crime', 'departmentId' => $this->nifsDepartmentId)),
                'in_date' => $this->input->post('inDate'),
                'out_date' => $this->input->post('outDate'),
                'partner_id' => $this->input->post('partnerId'),
                'agent_name' => $this->input->post('agentName'),
                'crime_value' => $this->input->post('crimeValue'),
                'crime_object' => $this->input->post('crimeObject'),
                'object_count' => ($this->input->post('objectCount') != 0 ? $this->input->post('objectCount') : ($this->input->post('crimeObject') != '' ? 1 : 0)),
                'question' => $this->question,
                'expert' => '',
                'description' => $this->input->post('description'),
                'research_type_id' => $this->input->post('researchTypeId'),
                'crime_type_id' => $this->input->post('typeId'),
                'motive_id' => $this->input->post('motiveId'),
                'latent_print_expert_id' => $this->input->post('latentPrintExpertId'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'year' => $this->session->adminCloseYear,
                'protocol_number' => $this->input->post('protocolNumber'),
                'protocol_in_date' => $this->input->post('protocolInDate'),
                'protocol_out_date' => $this->input->post('protocolOutDate'),
                'department_id' => $this->nifsDepartmentId,
                'extra_expert_value' => $this->input->post('extraExpertValue'),
                'weight' => 0,
                'param' => json_encode(array(
                    'researchType' => ($this->nifsResearchTypeData != false ? $this->nifsResearchTypeData->title : ''),
                    'motive' => ($this->nifsMotiveData != false ? $this->nifsMotiveData->title : ''),
                    'partner' => ($this->partnerData != false ? $this->partnerData->title : ''),
                    'category' => ($this->categoryData != false ? $this->categoryData->title : ''),
                    'type' => ($this->nifsCrimeTypeData != false ? $this->nifsCrimeTypeData->title : ''),
                    'latentPrintExpert' => ($this->nifslatentPrintExpertData != false ? $this->nifslatentPrintExpertData->full_name : ''),
                    'solution' => '')),
                'is_mixx' => $this->input->post('isMixx'),
                'short_info' => $this->input->post('shortInfo'),
                'case_id' => $this->input->post('caseId')
            )
        );
        if ($this->db->insert_batch($this->db->dbprefix . 'nifs_crime', $this->data)) {
            return $this->hrPeople->updateHrPeopleParam_model(array('modId' => $this->input->post('modId'), 'contId' => $param['getUID'], 'expertId' => $this->input->post('expertId'), 'extraExpertValue' => $this->input->post('extraExpertValue'), 'table' => 'nifs_crime'));
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_UPDATE,
            'data' => json_encode($_POST)));

        $this->editData = $this->editFormData_model(array('id' => $this->input->post('id')));
        $this->editData = json_decode($this->editData->param);

        $this->question = '';

        foreach ($this->input->post('question') as $key => $value) {
            $this->question .= $this->input->post('question[' . $key . ']') . '|:||:|';
        }
        $this->question = rtrim($this->question, '|:||:|');

        $this->nifsResearchTypeData = $this->nifsResearchType->getData_model(array('selectedId' => $this->input->post('researchTypeId')));
        $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $this->input->post('motiveId')));
        $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->post('partnerId')));
        $this->categoryData = $this->category->getData_model(array('selectedId' => $this->input->post('catId')));
        $this->nifsCrimeTypeData = $this->nifsCrimeType->getData_model(array('selectedId' => $this->input->post('typeId')));
        $this->nifslatentPrintExpertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->post('latentPrintExpertId')));

        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'create_number' => $this->input->post('createNumber'),
            'in_date' => $this->input->post('inDate'),
            'out_date' => $this->input->post('outDate'),
            'partner_id' => $this->input->post('partnerId'),
            'agent_name' => $this->input->post('agentName'),
            'crime_value' => $this->input->post('crimeValue'),
            'crime_object' => $this->input->post('crimeObject'),
            'object_count' => $this->input->post('objectCount'),
            'question' => $this->question,
            'description' => $this->input->post('description'),
            'research_type_id' => $this->input->post('researchTypeId'),
            'crime_type_id' => $this->input->post('typeId'),
            'motive_id' => $this->input->post('motiveId'),
            'latent_print_expert_id' => $this->input->post('latentPrintExpertId'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'protocol_number' => $this->input->post('protocolNumber'),
            'protocol_in_date' => $this->input->post('protocolInDate'),
            'protocol_out_date' => $this->input->post('protocolOutDate'),
            'extra_expert_value' => $this->input->post('extraExpertValue'),
            'param' => json_encode(array(
                'researchType' => ($this->nifsResearchTypeData != false ? $this->nifsResearchTypeData->title : ''),
                'motive' => ($this->nifsMotiveData != false ? $this->nifsMotiveData->title : ''),
                'partner' => ($this->partnerData != false ? $this->partnerData->title : ''),
                'category' => ($this->categoryData != false ? $this->categoryData->title : ''),
                'type' => ($this->nifsCrimeTypeData != false ? $this->nifsCrimeTypeData->title : ''),
                'latentPrintExpert' => ($this->nifslatentPrintExpertData != false ? $this->nifslatentPrintExpertData->full_name : ''),
                'solution' => '')),
            'is_mixx' => $this->input->post('isMixx'),
            'short_info' => $this->input->post('shortInfo'),
            'year' => $this->session->adminCloseYear,
            'case_id' => $this->input->post('caseId')
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'nifs_crime', $this->data)) {
            return $this->hrPeople->updateHrPeopleParam_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('id'), 'expertId' => $this->input->post('expertId'), 'extraExpertValue' => $this->input->post('extraExpertValue'), 'table' => 'nifs_crime'));
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
        if ($this->db->delete($this->db->dbprefix . 'nifs_crime')) {
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
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_CLOSE,
            'data' => json_encode($_POST)));

        $crimeData = $this->getData_model(array('selectedId' => $this->input->post('id')));
        $param = json_decode($crimeData->param);
        $this->nifsSolutionData = $this->nifsSolution->getData_model(array('selectedId' => $this->input->post('solutionId')));
        $param->solution = ($this->nifsSolutionData != false ? $this->nifsSolutionData->title : '');

        $this->data = array(
            'param' => json_encode($param),
            'close_date' => (($this->input->post('solutionId') == 0 and $this->input->post('closeTypeId') == 0) ? '0000-00-00 00:00:00' : $this->input->post('closeDate')),
            'weight' => (($this->input->post('solutionId') == 0 and $this->input->post('closeTypeId') == 0) ? 0 : $this->input->post('weight')),
            'close_description' => $this->input->post('closeDescription'),
            'solution_id' => $this->input->post('solutionId'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'nifs_crime', $this->data)) {
            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $this->result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }

        return $this->result;
    }

    public function searchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('protocolNumber')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('protocolNumber') . '</span>';
            $this->string .= form_hidden('protocolNumber', $this->input->get('protocolNumber'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('researchTypeId')) {
            $this->nifsResearchType = $this->nifsResearchType->getData_model(array('selectedId' => $this->input->get('researchTypeId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsResearchType->title . '</span>';
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

        if ($this->input->get('catId')) {
            if ($this->input->get('catId') != 'all') {
                $this->cat = $this->category->getData_model(array('selectedId' => $this->input->get('catId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->cat->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүх шинжилгээний төрөл</span>';
            }
            $this->string .= form_hidden('catId', $this->input->get('catId'));
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

        if ($this->input->get('caseId')) {

            if ($this->input->get('caseId') != 'all') {
                $this->nifsMaseterCaseData = $this->nifsMasterCase->getData_model(array('selectedId' => $this->input->get('caseId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsMaseterCaseData->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('typeId', $this->input->get('typeId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('typeId')) {
            $this->nifsCrimeType = $this->nifsCrimeType->getData_model(array('selectedId' => $this->input->get('typeId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCrimeType->title . '</span>';
            $this->string .= form_hidden('typeId', $this->input->get('typeId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('motiveId')) {
            $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $this->input->get('motiveId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsMotiveData->title . '</span>';
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

        if ($this->input->get('closeTypeId') and $this->input->get('closeTypeId') > 0) {
            $this->nifsCloseType = $this->nifsCloseType->getData_model(array('selectedId' => $this->input->get('closeTypeId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCloseType->title . '</span>';
            $this->string .= form_hidden('closeTypeId', $this->input->get('closeTypeId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('latentPrintDepartmentId')) {
            $this->hrPeopleDepartmentData = $this->hrPeopleDepartment->getData_model(array('selectedId' => $this->input->get('latentPrintDepartmentId')));
            $this->string .= '<span class="label label-default label-rounded">Мөр бэхжүүлсэн газар: ' . $this->hrPeopleDepartmentData->title . '</span>';
            $this->string .= form_hidden('latentPrintDepartmentId', $this->input->get('latentPrintDepartmentId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('latentPrintExpertId')) {
            $this->latentPrintExpertData = $this->user->getData_model(array('selectedId' => $this->input->get('latentPrintExpertId')));
            $this->string .= '<span class="label label-default label-rounded">Мөр бэхжүүлсэн шинжээч' . mb_substr($this->latentPrintExpertData->lname, 0, 1, 'UTF-8') . '.' . $this->latentPrintExpertData->fname . '</span>';
            $this->string .= form_hidden('latentPrintExpertId', $this->input->get('latentPrintExpertId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('expertId')) {
            if ($this->input->get('expertId') != 'all') {
                $this->expertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->get('expertId')));
                $this->string .= '<span class="label label-default label-rounded">' . mb_substr($this->expertData->lname, 0, 1, 'UTF-8') . '.' . $this->expertData->fname . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүх шинжээч</span>';
            }

            $this->string .= form_hidden('expertId', $this->input->get('expertId'));
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

        if ($this->input->get('solutionId') and $this->input->get('solutionId') > 0) {
            $this->nifsSolution = $this->nifsSolution->getData_model(array('selectedId' => $this->input->get('solutionId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsSolution->title . '</span>';
            $this->string .= form_hidden('solutionId', $this->input->get('solutionId'));
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

                $this->string .= ' <a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            }
        }

        return ($this->showResetBtn ? '<div class="_search-result-inner"><form action="javascript:;" id="form-nifs-crime"> Хайлтын үр дүн: ' . $this->string . '</form></div>' : '');
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cat_id,
                create_number,
                DATE(in_date) AS in_date,
                partner_id,
                agent_name,
                crime_value,
                crime_object,
                object_count,
                question,
                expert,
                weight,
                close_description,
                close_date,
                DATE(out_date) AS out_date,
                description,
                research_type_id,
                crime_type_id,
                given,
                crime_again_id,
                motive_id,
                latent_print_department_id,
                latent_print_expert_id,
                solution_id,
                close_type_id,
                department_id,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id,
                protocol_number,
                IF (protocol_in_date = \'0000-00-00 00:00:00\', \'\', DATE(protocol_in_date)) AS protocol_in_date,
                IF (protocol_out_date = \'0000-00-00 00:00:00\', \'\', DATE(protocol_out_date)) AS protocol_out_date,
                param,
                is_mixx,
                short_info,
                extra_expert_value
            FROM `gaz_nifs_crime`
            WHERE id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
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

        if ($param['reportIsClose'] == 1) {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['inDate'])) . '\') <= DATE(NC.close_date) AND DATE(\'' . date('Y-m-d', strtotime($param['outDate'])) . '\') >= DATE(NC.close_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['inDate'])) . '\') <= DATE(NC.close_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['outDate'])) . '\') >= DATE(NC.close_date)';
            }
        } else {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['inDate'])) . '\') <= DATE(NC.in_date) AND DATE(\'' . date('Y-m-d', strtotime($param['outDate'])) . '\') >= DATE(NC.in_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['inDate'])) . '\') <= DATE(NC.in_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['outDate'])) . '\') >= DATE(NC.out_date)';
            }
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NC.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $queryCategory = $this->db->query('
            SELECT
                C.id,
                C.title,
                NCA.cat_count_year,
                IF(NCA.cat_count_year > 0, \'_row-more\', \'\') AS cat_count_year_class,
                NCA.object_count,
                NCA.weight_count,
                NCINDATE.cat_count_in_date,
                IF(NCINDATE.cat_count_in_date > 0, \'_row-more\', \'\') AS cat_count_in_date_class,
                RETURNCRIME.return_count,
                IF(RETURNCRIME.return_count > 0, \'_row-more\', \'\') AS return_count_class,
                NCINDATE.date_object_count,
                NCMIXX1.count_mixx_1,
                IF(NCMIXX1.count_mixx_1 > 0, \'_row-more\', \'\') AS count_mixx_1_class,
                NCMIXX0.count_mixx_0,
                IF(NCMIXX0.count_mixx_0 > 0, \'_row-more\', \'\') AS count_mixx_0_class,
                HNC.hand_count,
                IF(HNC.hand_count > 0, \'_row-more\', \'\') AS hand_count_class,
                NC_CLOSE_NORMAL.close_count,
                IF(NC_CLOSE_NORMAL.close_count > 0, \'_row-more\', \'\') AS close_count_class,
                NCCRASHHAND.crash_count_hand,
                IF(NCCRASHHAND.crash_count_hand > 0, \'_row-more\', \'\') AS crash_count_hand_class,
                NCCRASHDONE.crash_count_done,
                IF(NCCRASHDONE.crash_count_done > 0, \'_row-more\', \'\') AS crash_count_done_class,
                DATE_HNC.hand_count AS date_hand_count,
                IF(DATE_HNC.hand_count > 0, \'_row-more\', \'\') AS date_hand_count_class,
                DATE_NC_CLOSE_NORMAL.close_count AS date_close_count,
                IF(DATE_NC_CLOSE_NORMAL.close_count > 0, \'_row-more\', \'\') AS date_close_count_class,
                DATE_NCCRASHDONE.crash_count_done AS date_crash_count_done,
                IF(DATE_NCCRASHDONE.crash_count_done > 0, \'_row-more\', \'\') AS date_crash_count_done_class,
                DATE_NCCRASHHAND.crash_count_hand AS date_crash_count_hand,
                IF(DATE_NCCRASHHAND.crash_count_hand > 0, \'_row-more\', \'\') AS date_crash_count_hand_class
            FROM `gaz_category` AS C
            LEFT JOIN (
                SELECT
                    NC.cat_id,
                    COUNT(NC.cat_id) AS cat_count_year,
                    SUM(NC.object_count) AS object_count,
                    SUM(NC.weight) AS weight_count
                FROM `gaz_nifs_crime` AS NC 
                WHERE NC.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NC.cat_id
            ) AS NCA ON NCA.cat_id = C.id
            LEFT JOIN (
                SELECT
                    NC.cat_id,
                    COUNT(NC.cat_id) AS cat_count_in_date,
                    SUM(NC.object_count) AS date_object_count
                FROM `gaz_nifs_crime` AS NC
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NC.cat_id
            ) AS NCINDATE ON NCINDATE.cat_id = C.id
            LEFT JOIN (
                SELECT
                    NC.cat_id,
                    COUNT(NC.cat_id) AS count_mixx_1
                FROM `gaz_nifs_crime` AS NC
                WHERE NC.is_mixx = 1 ' . $queryStringData . '
                GROUP BY NC.cat_id
            ) AS NCMIXX1 ON NCMIXX1.cat_id = C.id
            LEFT JOIN (
                SELECT
                    NC.cat_id,
                    COUNT(NC.cat_id) AS count_mixx_0
                FROM `gaz_nifs_crime` AS NC
                WHERE NC.is_mixx = 0 ' . $queryStringData . '
                GROUP BY NC.cat_id
            ) AS NCMIXX0 ON NCMIXX0.cat_id = C.id
            LEFT JOIN (
                SELECT
                    NC.cat_id,
                    SUM(NC.object_count) AS object_count
                FROM `gaz_nifs_crime` AS NC 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NC.cat_id
            ) AS NCOBJECT ON NCOBJECT.cat_id = C.id
            LEFT JOIN (
                SELECT 
                    NC.cat_id, 
                    count(NC.cat_id) AS hand_count 
                FROM `gaz_nifs_crime` AS NC
                WHERE NC.solution_id = 0 AND CURDATE() <= DATE(NC.out_date) AND NC.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NC.cat_id
            ) AS HNC ON HNC.cat_id = C.id
            LEFT JOIN (
                SELECT 
                    NC.cat_id, 
                    count(NC.cat_id) AS close_count 
                FROM `gaz_nifs_crime` AS NC
                WHERE NC.solution_id != 0 AND DATE(NC.close_date) <= DATE(NC.out_date) AND NC.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NC.cat_id
            ) AS NC_CLOSE_NORMAL ON NC_CLOSE_NORMAL.cat_id = C.id
            LEFT JOIN (
                SELECT
                    NC.cat_id,
                    COUNT(NC.cat_id) AS crash_count_hand
                FROM `gaz_nifs_crime` AS NC
                WHERE NC.solution_id = 0 AND CURDATE() > DATE(NC.out_date) AND NC.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NC.cat_id
            ) AS NCCRASHHAND ON NCCRASHHAND.cat_id = C.id
            LEFT JOIN (
                SELECT
                    NC.cat_id,
                    COUNT(NC.cat_id) AS crash_count_done
                FROM `gaz_nifs_crime` AS NC
                WHERE NC.solution_id != 0 AND DATE(NC.close_date) > DATE(NC.out_date) AND NC.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NC.cat_id
            ) AS NCCRASHDONE ON NCCRASHDONE.cat_id = C.id
            LEFT JOIN (
                SELECT 
                    NC.cat_id, 
                    count(NC.cat_id) AS hand_count 
                FROM `gaz_nifs_crime` AS NC
                WHERE NC.solution_id = 0 AND CURDATE() <= DATE(NC.out_date) ' . $queryStringData . '
                GROUP BY NC.cat_id
            ) AS DATE_HNC ON DATE_HNC.cat_id = C.id
            LEFT JOIN (
                SELECT 
                    NC.cat_id, 
                    count(NC.cat_id) AS close_count 
                FROM `gaz_nifs_crime` AS NC
                WHERE NC.solution_id != 0 AND DATE(NC.close_date) <= DATE(NC.out_date) ' . $queryStringData . '
                GROUP BY NC.cat_id
            ) AS DATE_NC_CLOSE_NORMAL ON DATE_NC_CLOSE_NORMAL.cat_id = C.id
            LEFT JOIN (
                SELECT
                    NC.cat_id,
                    COUNT(NC.cat_id) AS crash_count_done
                FROM `gaz_nifs_crime` AS NC
                WHERE NC.solution_id != 0 AND DATE(NC.close_date) > DATE(NC.out_date) ' . $queryStringData . '
                GROUP BY NC.cat_id
            ) AS DATE_NCCRASHDONE ON DATE_NCCRASHDONE.cat_id = C.id
            LEFT JOIN (
                SELECT
                    NC.cat_id,
                    COUNT(NC.cat_id) AS crash_count_hand
                FROM `gaz_nifs_crime` AS NC
                WHERE NC.solution_id = 0 AND CURDATE() >= DATE(NC.out_date) ' . $queryStringData . '
                GROUP BY NC.cat_id
            ) AS DATE_NCCRASHHAND ON DATE_NCCRASHHAND.cat_id = C.id
            LEFT JOIN (
                SELECT
                    NC.cat_id,
                    COUNT(NC.cat_id) AS return_count
                FROM `gaz_nifs_crime` AS NC
                WHERE NC.solution_id = 11 ' . $queryStringData . '
                GROUP BY NC.cat_id
            ) AS RETURNCRIME ON RETURNCRIME.cat_id = C.id
            WHERE C.mod_id = ' . $this->modId . '
            GROUP BY C.id
            ORDER BY NCA.cat_count_year DESC');

        $sumDateObjectCount = $sumDateReturnCount = $sumCatCountYear = $sumCatCountInDate = $sumCountMixx1 = $sumCountMixx0 = $sumCountWeight = $sumObjectCount = $sumHandCount = $sumCloseCount = $sumCrashCountHand = $sumCrashCountDone = $sumDateHandCount = $sumDateCloseCount = $sumDateCrashCountHand = $sumDateCrashCountDone = 0;

        if ($queryCategory->num_rows() > 0) {

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

            $catNum = 1;

            foreach ($queryCategory->result() as $keyCategory => $rowCategory) {

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $catNum . '</td>';
                $htmlData .= '<td>' . $rowCategory->title . '</td>';
                $htmlData .= '<td class="text-center ' . $rowCategory->cat_count_year_class . ' active-background"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=' . $rowCategory->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '\'});">' . $rowCategory->cat_count_year . '</a></td>';

                $htmlData .= '<td class="text-center">' . $rowCategory->object_count . '</td>';
                $htmlData .= '<td class="text-center">' . $rowCategory->weight_count . '</td>';


                $htmlData .= '<td class="text-center ' . $rowCategory->cat_count_in_date_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=' . $rowCategory->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $rowCategory->cat_count_in_date . '</a></td>';
                $htmlData .= '<td class="text-center ' . $rowCategory->return_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=' . $rowCategory->id . '&solutionId=11&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $rowCategory->return_count . '</a></td>';
                $htmlData .= '<td class="text-center">' . $rowCategory->date_object_count . '</td>';
                $htmlData .= '<td class="text-center ' . $rowCategory->count_mixx_1_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=' . $rowCategory->id . '&isMixx=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '\'});">' . $rowCategory->count_mixx_1 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $rowCategory->count_mixx_0_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=' . $rowCategory->id . '&isMixx=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '\'});">' . $rowCategory->count_mixx_0 . '</a></td>';

                $htmlData .= '<td class="text-center ' . $rowCategory->date_close_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=' . $rowCategory->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&statusId=1\'});">' . $rowCategory->date_close_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $rowCategory->date_hand_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=' . $rowCategory->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&statusId=2\'});">' . $rowCategory->date_hand_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $rowCategory->date_crash_count_done_class . '"><a class="_crime-crash-done" href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=' . $rowCategory->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&statusId=3\'});">' . $rowCategory->date_crash_count_done . '</a></td>';
                $htmlData .= '<td class="text-center ' . $rowCategory->date_crash_count_hand_class . '"><a class="_crime-crash-hand" href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=' . $rowCategory->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&statusId=4\'});">' . $rowCategory->date_crash_count_hand . '</a></td>';

                $htmlData .= '</tr>';

                $catNum++;

                $sumCatCountYear = $sumCatCountYear + $rowCategory->cat_count_year;
                $sumObjectCount = $sumObjectCount + $rowCategory->object_count;
                $sumDateObjectCount = $sumDateObjectCount + $rowCategory->date_object_count;
                $sumDateReturnCount = $sumDateReturnCount + $rowCategory->return_count;

                $sumDateHandCount = $sumDateHandCount + $rowCategory->date_hand_count;
                $sumDateCloseCount = $sumDateCloseCount + $rowCategory->date_close_count;
                $sumDateCrashCountHand = $sumDateCrashCountHand + $rowCategory->date_crash_count_hand;
                $sumDateCrashCountDone = $sumDateCrashCountDone + $rowCategory->date_crash_count_done;


                $sumCatCountInDate = $sumCatCountInDate + $rowCategory->cat_count_in_date;
                $sumCountMixx1 = $sumCountMixx1 + $rowCategory->count_mixx_1;
                $sumCountMixx0 = $sumCountMixx0 + $rowCategory->count_mixx_0;
                $sumCountWeight = $sumCountWeight + $rowCategory->weight_count;
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right">Нийт</td>';
            $htmlData .= '<td class="text-center">' . ($sumCatCountYear > 0 ? '<a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '\'});">' . $sumCatCountYear . '</a>' : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($sumObjectCount > 0 ? $sumObjectCount : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($sumCountWeight > 0 ? $sumCountWeight : '') . '</td>';


            $htmlData .= '<td class="text-center">' . ($sumCatCountInDate > 0 ? '<a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $sumCatCountInDate . '</a>' : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($sumDateReturnCount > 0 ? '<a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=all&solutionId=11&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $sumDateReturnCount . '</a>' : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($sumDateObjectCount > 0 ? $sumDateObjectCount : '') . '</td>';

            $htmlData .= '<td class="text-center">' . ($sumCountMixx1 > 0 ? '<a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=all&isMixx=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '\'});">' . $sumCountMixx1 . '</a>' : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($sumCountMixx0 > 0 ? '<a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=all&isMixx=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '\'});">' . $sumCountMixx0 . '</a>' : '') . '</td>';

            $htmlData .= '<td class="text-center">' . ($sumDateCloseCount > 0 ? '<a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&statusId=1\'});">' . $sumDateCloseCount . '</a>' : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($sumDateHandCount > 0 ? '<a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&statusId=2\'});">' . $sumDateHandCount . '</a>' : '') . '</td>';
            $htmlData .= '<td class="text-center _crime-crash-done">' . ($sumDateCrashCountDone > 0 ? '<a class="_crime-crash-done" href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&statusId=3\'});">' . $sumDateCrashCountDone . '</a>' : '') . '</td>';
            $htmlData .= '<td class="text-center _crime-crash-hand">' . ($sumDateCrashCountHand > 0 ? '<a class="_crime-crash-hand" href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&statusId=4\'});">' . $sumDateCrashCountHand . '</a>' : '') . '</td>';

            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNotCategory = $this->db->query('
            SELECT
                NC.id,
                NC.create_number
            FROM `gaz_nifs_crime` AS NC
            WHERE NC.cat_id = 0 ' . $queryStringData . '
            ORDER BY NC.create_number ASC');

            if ($queryNotCategory->num_rows() > 0) {
                $htmlNotCategoy = 'Шинжилгээний төрөл сонгоогдоогүй шинжилгээ (' . $queryNotCategory->num_rows() . '): ';
                foreach ($queryNotCategory->result() as $key => $row) {
                    $htmlNotCategoy .= '<a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'createNumber=' . $row->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $row->create_number . '</a>';
                }
                $htmlData .= '<div class="table-responsive-inside-description">' . $htmlNotCategoy . '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }



        //Үндэслэлээр тайлан харуулах эхлэл
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
                FROM `gaz_nifs_crime` AS NC
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NC.motive_id) AS NC ON NC.motive_id = M.id
            WHERE M.is_active = 1 AND M.cat_id = ' . $this->nifsMotiveCatId);

        if ($query->num_rows() > 0) {

            $sumTotalMotiveCount = 0;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px; text-align:center; padding-top:10px; padding-bottom:10px;">#</th>';
            $htmlData .= '<th class="pt-1 pb-1">Эрх бүхий этгээдээс ирүүлсэн шинжилгээ</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Тоо</th>';
            $htmlData .= '</tr>';
            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            $i = 1;

            foreach ($query->result() as $keyMotive => $rowMotive) {

                $sumTotalMotiveCount = $sumTotalMotiveCount + $rowMotive->motive_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i++ . '</td>';
                $htmlData .= '<td>' . $rowMotive->title . '</td>';
                $htmlData .= '<td class="text-center ' . $rowMotive->motive_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'motiveId=' . $rowMotive->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $rowMotive->motive_count . '</a></td>';
                $htmlData .= '</tr>';
            }
            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . $sumTotalMotiveCount . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNotMotive = $this->db->query('
            SELECT
                NC.id,
                NC.create_number
            FROM `gaz_nifs_crime` AS NC
            WHERE NC.motive_id = 0 ' . $queryStringData . '
            ORDER BY NC.create_number ASC');

            if ($queryNotMotive->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Эрх бүхий байгууллага сонгоогүй шинжилгээ (' . $queryNotMotive->num_rows() . '): ';

                foreach ($queryNotMotive->result() as $rowMotive) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'createNumber=' . $rowMotive->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowMotive->create_number . '</a>';
                }
                $htmlData .= '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }
        //Үндэслэлээр тайлан харуулах төгсгөл
        //Бүрэлдэхүүнтэй, Бүрэлдэхүүнгүй шинжилгээний тайлан - эхлэл
        $query = $this->db->query('
            SELECT
                NRT.id,
                NRT.title,
                NC_MIXX_1.mixx_1_research_count,
                IF(NC_MIXX_1.mixx_1_research_count > 0, \'_row-more\', \'\') AS mixx_1_research_count_class,
                NC_MIXX_0.mixx_0_research_count,
                IF(NC_MIXX_0.mixx_0_research_count > 0, \'_row-more\', \'\') AS mixx_0_research_count_class
            FROM `gaz_nifs_research_type` AS NRT
            LEFT JOIN (
                SELECT
                    NC.research_type_id,
                    COUNT(NC.research_type_id) AS mixx_1_research_count
                FROM `gaz_nifs_crime` AS NC
                WHERE NC.is_mixx = 1 ' . $queryStringData . '
                GROUP BY NC.research_type_id
            ) AS NC_MIXX_1 ON NRT.id = NC_MIXX_1.research_type_id
            LEFT JOIN (
                SELECT
                    NC.research_type_id,
                    COUNT(NC.research_type_id) AS mixx_0_research_count
                FROM `gaz_nifs_crime` AS NC
                WHERE NC.is_mixx = 0 ' . $queryStringData . '
                GROUP BY NC.research_type_id
            ) AS NC_MIXX_0 ON NRT.id = NC_MIXX_0.research_type_id
            WHERE NRT.cat_id = ' . $this->nifsResearchTypeCatId . ' AND NRT.is_active = 1');

        $mixx1NumRows = $query->num_rows();

        if ($mixx1NumRows > 0) {
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

            $i = 1;
            $mixx1TotalCount = $mixx0TotalCount = $mixxRowCount = $mixxTotalCount = 0;
            $htmlData .= '<tbody>';

            foreach ($query->result() as $keyMixx => $rowMixx) {

                $mixx1TotalCount = $mixx1TotalCount + $rowMixx->mixx_1_research_count;
                $mixx0TotalCount = $mixx0TotalCount + $rowMixx->mixx_0_research_count;
                $mixxRowCount = $rowMixx->mixx_1_research_count + $rowMixx->mixx_0_research_count;

                $mixxTotalCount = $mixxTotalCount + $mixxRowCount;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i++ . '</td>';
                $htmlData .= '<td>' . $rowMixx->title . '</td>';
                $htmlData .= '<td class="text-center ' . $rowMixx->mixx_1_research_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'researchTypeId=' . $rowMixx->id . '&isMixx=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $rowMixx->mixx_1_research_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $rowMixx->mixx_0_research_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'researchTypeId=' . $rowMixx->id . '&isMixx=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $rowMixx->mixx_0_research_count . '</a></td>';
                if ($mixxRowCount > 0) {
                    $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'researchTypeId=' . $rowMixx->id . '&isMixx=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $mixxRowCount . '</a></td>';
                } else {
                    $htmlData .= '<td class="text-center"></td>';
                }

                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';

            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт:</td>';
            if ($mixx1TotalCount > 0) {
                $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isMixx=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $mixx1TotalCount . '</a></td>';
            } else {
                $htmlData .= '<td class="text-center _custom-foot"></td>';
            }

            if ($mixx0TotalCount > 0) {
                $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isMixx=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $mixx0TotalCount . '</a></td>';
            } else {
                $htmlData .= '<td class="text-center _custom-foot"></td>';
            }

            if ($mixxTotalCount > 0) {
                $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isMixx=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $mixxTotalCount . '</a></td>';
            } else {
                $htmlData .= '<td class="text-center _custom-foot"></td>';
            }

            $htmlData .= '</tr>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<table>';

            $queryNotMixx = $this->db->query('
            SELECT
                NC.id,
                NC.create_number
            FROM `gaz_nifs_crime` AS NC
            WHERE NC.motive_id = 0 ' . $queryStringData . '
            ORDER BY NC.create_number ASC');

            if ($queryNotMixx->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ томилсон байдал сонгоогүй (' . $queryNotMixx->num_rows() . '): ';

                foreach ($queryNotMixx->result() as $rowNotMixx) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'createNumber=' . $rowNotMixx->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNotMixx->create_number . '</a>';
                }
                $htmlData .= '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }
        //Бүрэлдэхүүнтэй шинжилгээний тайлан - төгсгөл
        //Хэргийн төрөл тайлан - эхлэл
        $query = $this->db->query('
            SELECT
                NMC.id,
                NMC.title,
                NC.case_count,
                IF(NC.case_count > 0, \'_row-more\', \'\') AS case_count_class
            FROM `gaz_nifs_master_case` AS NMC
            LEFT JOIN (
                SELECT
                    NC.case_id,
                    COUNT(NC.case_id) AS case_count
                FROM `gaz_nifs_crime` AS NC 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NC.case_id
            ) AS NC ON NMC.id = NC.case_id
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
                $htmlData .= '<td class="text-center ' . $rowCase->case_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'caseId=' . $rowCase->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $rowCase->case_count . '</a></td>';
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
                NC.id,
                NC.create_number
            FROM `gaz_nifs_crime` AS NC
            WHERE NC.case_id = 0 ' . $queryStringData . '
            ORDER BY NC.create_number ASC');

            if ($queryNotCrimeType->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Хэргийн төрөл сонгоогүй шинжилгээ (' . $queryNotCrimeType->num_rows() . '): ';

                foreach ($queryNotCrimeType->result() as $rowNotCrimeType) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'createNumber=' . $rowNotCrimeType->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNotCrimeType->create_number . '</a>';
                }
                $htmlData .= '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }
        //Хэргийн төрөл тайлан - төгсгөл
        //Хэргийн өнгө тайлан - эхлэл
        $query = $this->db->query('
            SELECT
                NCT.id,
                NCT.cat_id,
                NCT.title,
                NC.crime_type_count,
                IF(NC.crime_type_count > 0, \'_row-more\', \'\') AS crime_type_count_class
            FROM `gaz_nifs_crime_type` AS NCT
            LEFT JOIN (
                SELECT
                    NC.crime_type_id,
                    COUNT(NC.crime_type_id) AS crime_type_count
                FROM `gaz_nifs_crime` AS NC 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NC.crime_type_id
            ) AS NC ON NCT.id = NC.crime_type_id
            WHERE NCT.cat_id = ' . $this->nifsCrimeTypeId . ' AND NCT.is_active = 1');

        $crimeTypeNumRows = $query->num_rows();
        if ($crimeTypeNumRows > 0) {

            $i = 1;
            $sumTotalCrimeTypeCount = 0;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px; text-align:center; padding-top:10px; padding-bottom:10px;">#</th>';
            $htmlData .= '<th>Хэргийн өнгө</th>';
            $htmlData .= '<th style="width:100px; text-align:center;">Нийт</th>';
            $htmlData .= '</tr>';
            $htmlData .= '</thead>';
            $htmlData .= '<tbody>';
            foreach ($query->result() as $keyCrimeType => $rowCrimeType) {

                $sumTotalCrimeTypeCount = $sumTotalCrimeTypeCount + $rowCrimeType->crime_type_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i++ . '</td>';
                $htmlData .= '<td>' . $rowCrimeType->title . '</td>';
                $htmlData .= '<td class="text-center ' . $rowCrimeType->crime_type_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'typeId=' . $rowCrimeType->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $rowCrimeType->crime_type_count . '</a></td>';
                $htmlData .= '</tr>';
            }
            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';

            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . $sumTotalCrimeTypeCount . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNotCrimeType = $this->db->query('
            SELECT
                NC.id,
                NC.create_number
            FROM `gaz_nifs_crime` AS NC
            WHERE NC.crime_type_id = 0 ' . $queryStringData . '
            ORDER BY NC.create_number ASC');

            if ($queryNotCrimeType->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Хэргийн төрөл сонгоогүй шинжилгээ (' . $queryNotCrimeType->num_rows() . '): ';

                foreach ($queryNotCrimeType->result() as $rowNotCrimeType) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'createNumber=' . $rowNotCrimeType->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNotCrimeType->create_number . '</a>';
                }
                $htmlData .= '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }
        //Хэргийн өнгө тайлан - төгсгөл
        //Шийдвэрлэсэн байдал - Эхлэл

        $query = $this->db->query('
            SELECT 
                M.id,
                M.title,
                NC.solution_count AS solution_count
            FROM `gaz_nifs_solution` AS M
            LEFT JOIN (
                SELECT 
                    NC.solution_id,
                    COUNT(NC.solution_id) AS solution_count
                FROM `gaz_nifs_crime` AS NC 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NC.solution_id
            ) AS NC ON NC.solution_id = M.id
            WHERE M.is_active = 1 AND M.cat_id = ' . $this->nifsSolutionCatId);

        $solutionNumRows = $query->num_rows();

        if ($solutionNumRows > 0) {

            $solutionCount = 0;
            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px; text-align:center; padding-top:10px; padding-bottom:10px;">#</th>';
            $htmlData .= '<th>Шинжилгээг шийдвэрлэсэн байдал</th>';
            $htmlData .= '<th style="width:100px; text-align:center;">Нийт</th>';
            $htmlData .= '</tr>';
            $htmlData .= '</thead>';
            $htmlData .= '<tbody>';

            foreach ($query->result() as $keySolution => $rowSolution) {

                $solutionCount = $solutionCount + $rowSolution->solution_count;
                $htmlData .= '<td>' . $i++ . '</td>';
                $htmlData .= '<td>' . $rowSolution->title . '</td>';
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'solutionId=' . $rowSolution->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $rowSolution->solution_count . '</a></td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';

            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт:</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . $solutionCount . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNotCrimeType = $this->db->query('
            SELECT
                NC.id,
                NC.create_number
            FROM `gaz_nifs_crime` AS NC
            WHERE NC.solution_id = 0 ' . $queryStringData . '
            ORDER BY NC.create_number ASC');

            if ($queryNotCrimeType->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Хаагдаагүй шинжилгээ (' . $queryNotCrimeType->num_rows() . '): ';

                foreach ($queryNotCrimeType->result() as $rowNotCrimeType) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'createNumber=' . $rowNotCrimeType->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNotCrimeType->create_number . '</a>';
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

        $queryStringYearData = $queryStringData = $queryStringHrPeopleData = $htmlData = $isAjaxDepartmentUrl = '';

        if ($param['reportIsClose'] == 1) {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['inDate'])) . '\') <= DATE(NC.close_date) AND DATE(\'' . date('Y-m-d', strtotime($param['outDate'])) . '\') >= DATE(NC.close_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['inDate'])) . '\') <= DATE(NC.close_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['outDate'])) . '\') >= DATE(NC.close_date)';
            }
        } else {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NC.in_date) AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NC.in_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NC.in_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NC.out_date)';
            }
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NC.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $query = $this->db->query('
            SELECT 
                NE.expert_id
            FROM `gaz_nifs_expert` AS NE
            INNER JOIN `gaz_nifs_crime` AS NC ON NE.mod_id = NC.mod_id AND NE.cont_id = NC.id
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
            $queryStringData .= ' AND NC.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $queryStringHrPeopleData .= ' AND HP.id IN(' . $inPeopleId . ')';
            $queryStringYearData .= ' AND NC.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
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
                    NE.expert_id,
                    COUNT(NE.expert_id) AS year_total_count,
                    COUNT(NC.object_count) AS year_object_count,
                    SUM(NC.weight) AS year_weight_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_crime` AS NC ON NE.mod_id = NC.mod_id AND NE.cont_id = NC.id
                WHERE 1 = 1 AND NC.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NE.expert_id
            ) C ON C.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NE.expert_id,
                    COUNT(NE.expert_id) AS date_total_count,
                    SUM(NC.object_count) AS date_object_count,
                    SUM(NC.weight) AS date_weight_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_crime` AS NC ON NE.mod_id = NC.mod_id AND NE.cont_id = NC.id
                WHERE 1 = 1' . $queryStringData . '
                GROUP BY NE.expert_id
            ) DATE_TOTAL_COUNT ON DATE_TOTAL_COUNT.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NE.expert_id,
                    COUNT(NE.expert_id) AS return_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_crime` AS NC ON NE.mod_id = NC.mod_id AND NE.cont_id = NC.id
                WHERE NC.solution_id = 11' . $queryStringData . '
                GROUP BY NE.expert_id
            ) RETURNCRIME ON RETURNCRIME.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NE.expert_id,
                    COUNT(NC.is_mixx) AS mixx1_count 
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_crime` AS NC ON NE.mod_id = NC.mod_id AND NE.cont_id = NC.id
                WHERE NC.is_mixx = 1' . $queryStringData . '
                GROUP BY NE.expert_id
            ) MIXX1 ON MIXX1.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NE.expert_id,
                    COUNT(NC.is_mixx) AS mixx0_count 
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_crime` AS NC ON NE.mod_id = NC.mod_id AND NE.cont_id = NC.id
                WHERE NC.is_mixx = 0' . $queryStringData . '
                GROUP BY NE.expert_id
            ) MIXX0 ON MIXX0.expert_id = HP.id 
            LEFT JOIN (
                SELECT 
                    NE.expert_id, 
                    COUNT(NE.expert_id) AS normal_count_hand 
                FROM `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_crime` AS NC ON NE.mod_id = NC.mod_id AND NE.cont_id = NC.id
                WHERE NC.solution_id = 0 AND CURDATE() <= DATE(NC.out_date) ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) NC_HAND_NORMAL ON NC_HAND_NORMAL.expert_id = HP.id
            LEFT JOIN (
                SELECT 
                    NE.expert_id, 
                    count(NE.expert_id) AS normal_count_close
                FROM `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_crime` AS NC ON NE.mod_id = NC.mod_id AND NE.cont_id = NC.id
                WHERE NC.solution_id != 0 AND DATE(NC.close_date) <= DATE(NC.out_date) ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) NC_CLOSE_NORMAL ON NC_CLOSE_NORMAL.expert_id = HP.id
            LEFT JOIN (
                SELECT
                    NE.expert_id,
                    COUNT(NE.expert_id) AS crash_count_hand
                FROM `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_crime` AS NC ON NE.mod_id = NC.mod_id AND NE.cont_id = NC.id
                WHERE NC.solution_id = 0 AND CURDATE() >= DATE(NC.out_date) ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) NC_CRASH_HAND ON NC_CRASH_HAND.expert_id = HP.id 
            LEFT JOIN (
                SELECT
                    NE.expert_id,
                    COUNT(NE.expert_id) AS crash_count_close
                FROM `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_crime` AS NC ON NE.mod_id = NC.mod_id AND NE.cont_id = NC.id
                WHERE NC.solution_id != 0 AND DATE(NC.close_date) > DATE(NC.out_date) ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) NC_CRASH_DONE ON NC_CRASH_DONE.expert_id = HP.id 
            WHERE HP.id IN(' . $inPeopleId . ') AND C.year_total_count > 0
            ORDER BY HP.fname ASC');

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

            $i = $sumTotalYearTotalCount = $sumTotalYearObjectCount = $sumTotalYearWeightCount = $sumTotalDateTotalCount = $sumTotalReturnCount = $sumTotalDateObjectCount = $sumTotalMixx1Count = $sumTotalMixx0Count = $sumTotalNormalCountClose = $sumTotalNormalCountHand = $sumTotalCrashCountClose = $sumTotalCrashCountHand = 0;
            foreach ($query->result() as $key => $row) {
                $sumTotalYearTotalCount = $sumTotalYearTotalCount + intval($row->year_total_count);
                $sumTotalYearObjectCount = $sumTotalYearObjectCount + intval($row->year_object_count);
                $sumTotalYearWeightCount = $sumTotalYearWeightCount + intval($row->year_weight_count);
                $sumTotalDateTotalCount = $sumTotalDateTotalCount + intval($row->date_total_count);
                $sumTotalReturnCount = $sumTotalReturnCount + intval($row->return_count);
                $sumTotalDateObjectCount = $sumTotalDateObjectCount + intval($row->date_object_count);
                $sumTotalMixx1Count = $sumTotalMixx1Count + intval($row->mixx1_count);
                $sumTotalMixx0Count = $sumTotalMixx0Count + intval($row->mixx0_count);
                $sumTotalNormalCountClose = $sumTotalNormalCountClose + intval($row->normal_count_close);
                $sumTotalNormalCountHand = $sumTotalNormalCountHand + intval($row->normal_count_hand);

                $sumTotalCrashCountClose = $sumTotalCrashCountClose + intval($row->crash_count_close);
                $sumTotalCrashCountHand = $sumTotalCrashCountHand + intval($row->crash_count_hand);

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->full_name . '</td>';
                $htmlData .= '<td class="text-center ' . $row->year_total_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . $row->year_total_count . '</a></td>';
                $htmlData .= '<td class="text-center">' . $row->year_object_count . '</td>';
                $htmlData .= '<td class="text-center">' . $row->year_weight_count . '</td>';
                $htmlData .= '<td class="text-center ' . $row->date_total_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->date_total_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->return_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&solutionId=11' . $isAjaxDepartmentUrl . '\'});">' . $row->return_count . '</a></td>';
                $htmlData .= '<td class="text-center">' . $row->date_object_count . '</td>';
                $htmlData .= '<td class="text-center ' . $row->mixx1_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '&isMixx=1' . $isAjaxDepartmentUrl . '\'});">' . $row->mixx1_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->mixx0_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '&isMixx=2' . $isAjaxDepartmentUrl . '\'});">' . $row->mixx0_count . '</a></td>';


                $htmlData .= '<td class="text-center ' . $row->normal_count_close_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->normal_count_close . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->normal_count_hand_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->normal_count_hand . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crash_count_close_class . '"><a href="javascript:;" class="_crime-crash-done" onclick="_initNifsCrime({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=3&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->crash_count_close . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crash_count_hand_class . '"><a href="javascript:;" class="_crime-crash-hand" onclick="_initNifsCrime({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=4&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->crash_count_hand . '</a></td>';

                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';

            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td>Нийт</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalYearTotalCount > 0 ? $sumTotalYearTotalCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot">' . $sumTotalYearObjectCount . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . $sumTotalYearWeightCount . '</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalDateTotalCount > 0 ? $sumTotalDateTotalCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&solutionId=11' . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalReturnCount > 0 ? $sumTotalReturnCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot">' . $sumTotalDateObjectCount . '</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '&isMixx=1' . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalMixx1Count > 0 ? $sumTotalMixx1Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '&isMixx=2' . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalMixx0Count > 0 ? $sumTotalMixx0Count : '') . '</a></td>';

            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=1&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalNormalCountClose > 0 ? $sumTotalNormalCountClose : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=2&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalNormalCountHand > 0 ? $sumTotalNormalCountHand : '') . '</a></td>';
            $htmlData .= '<td class="text-center _crime-crash-hand"><a href="javascript:;" class="_crime-crash-done" onclick="_initNifsCrime({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=3&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalCrashCountClose > 0 ? $sumTotalCrashCountClose : '') . '</a></td>';
            $htmlData .= '<td class="text-center _crime-crash-done"><a href="javascript:;" class="_crime-crash-hand" onclick="_initNifsCrime({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=4&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalCrashCountHand > 0 ? $sumTotalCrashCountHand : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNotExpert = $this->db->query('
            SELECT 
                NE.expert_id,
                NC.create_number
            FROM `gaz_nifs_expert` AS NE 
            INNER JOIN `gaz_nifs_crime` AS NC ON NE.mod_id = NC.mod_id AND NE.cont_id = NC.id 
            WHERE NE.expert_id = 0 ' . $queryStringData . '
            ORDER BY NC.create_number ASC');

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
                FROM `gaz_nifs_crime` AS NC
                WHERE NC.partner_id != 0 AND NC.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NC.partner_id
            ) AS YEAR_PARTNER_COUNT ON YEAR_PARTNER_COUNT.partner_id = P.id
            LEFT JOIN (
                SELECT
                    NC.partner_id,
                    COUNT(NC.partner_id) AS date_partner_count
                FROM `gaz_nifs_crime` AS NC
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
                $htmlData .= '<td class="text-center ' . $row->year_partner_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . $row->year_partner_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->date_partner_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->date_partner_count . '</a></td>';
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
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'partnerId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalYearPartnerCount > 0 ? $sumTotalYearPartnerCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'partnerId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalDatePartnerCount > 0 ? $sumTotalDatePartnerCount : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';


            $queryNotPartner = $this->db->query('
            SELECT 
                NC.create_number
            FROM `gaz_nifs_crime` AS NC 
            WHERE NC.partner_id = 0 ' . $queryStringData . '
            ORDER BY NC.create_number ASC');

            if ($queryNotPartner->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Шинжээч сонгоогүй шинжилгээ (' . $queryNotPartner->num_rows() . '): ';

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
                FROM `gaz_nifs_crime` AS NC
                WHERE NC.partner_id != 0 AND NC.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NC.partner_id
            ) AS YEAR_PARTNER_COUNT ON YEAR_PARTNER_COUNT.partner_id = P.id
            LEFT JOIN (
                SELECT
                    NC.partner_id,
                    COUNT(NC.partner_id) AS date_partner_count
                FROM `gaz_nifs_crime` AS NC
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
                $htmlData .= '<td class="text-center ' . $row->year_partner_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . $row->year_partner_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->date_partner_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->date_partner_count . '</a></td>';
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

        $queryString = $queryStringJoinDepartment = $queryStringDepartment = '';

        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {

            $queryString .= '';
        } else if ($auth->our->read and ! $auth->your->read) {

            $queryString .= ' AND NC.created_user_id = ' . $this->session->userdata['adminUserId'];
        } else {

            $queryString .= ' AND NC.created_user_id = -1';
        }

        if ($this->session->userdata['adminDepartmentRoleId'] == 2) {

            $queryString .= ' AND NC.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        } else {

            if ($param['departmentId'] != 0) {

                $queryString .= ' AND NC.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            } else {

                $queryString .= ' AND NC.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
            }
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND NC.cat_id = ' . $param['catId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NC.in_date) AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NC.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NC.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NC.in_date)';
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NC.protocol_in_date) AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NC.protocol_in_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NC.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NC.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NC.close_date) AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NC.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NC.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NC.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $queryString .= ' AND NC.year = \'' . $this->session->userdata['adminCloseYear'] . '\'';
        }

        if ($param['researchTypeId'] != 0) {

            $queryString .= ' AND NC.research_type_id = ' . $param['researchTypeId'];
        }

        if ($param['isMixx'] == 1) {
            $queryString .= ' AND NC.is_mixx = 1';
        }

        if ($param['isMixx'] == 2) {
            $queryString .= ' AND NC.is_mixx = 0';
        }

        if ($param['typeId'] != 0) {
            $queryString .= ' AND NC.crime_type_id = ' . $param['typeId'];
        }

        if ($param['motiveId'] != 0) {
            $queryString .= ' AND NC.motive_id = ' . $param['motiveId'];
        }

        if ($param['partnerId'] != 0) {
            $queryString .= ' AND NC.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
        }

        if ($param['latentPrintDepartmentId'] != 0) {
            $queryString .= ' AND NC.latent_print_department_id = ' . $param['latentPrintDepartmentId'];
        }

        if ($param['latentPrintExpertId'] != 0) {

            $queryString .= ' AND NC.latent_print_expert_id = ' . $param['latentPrintExpertId'];
        }

        if ($param['createNumber'] != '') {

            $queryString .= ' AND NC.create_number = ' . $param['createNumber'];
        }

        if ($param['protocolNumber'] != '') {

            $queryString .= ' AND NC.protocol_number LIKE (\'%' . $param['protocolNumber'] . '%\') ';
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Албан тушаалтны нэр
                        $queryString .= ' AND LOWER(NC.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Тайлбар
                        $queryString .= ' AND LOWER(NC.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Холбогдох мэдээлэл
                        $queryString .= ' AND LOWER(NC.short_info) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Хэргийн утга
                        $queryString .= ' AND LOWER(NC.crime_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Дүгнэлтийн утга
                        $queryString .= ' AND LOWER(NC.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $queryString .= ' 
                        AND (LOWER(NC.question) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                        OR LOWER(NC.crime_object) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                        OR LOWER(NC.crime_value) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                        OR LOWER(NC.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')
                        OR LOWER(NC.short_info) LIKE LOWER(\'%' . $param['keyword'] . '%\')
                        OR LOWER(NC.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;
            }
        }

        if ($param['solutionId'] > 0) {

            $queryString .= ' AND NC.solution_id = ' . $param['solutionId'];
        }

        if ($param['closeTypeId'] > 0) {

            $queryString .= ' AND NC.close_type_id = ' . $param['closeTypeId'];
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $queryString .= ' AND NC.solution_id != 0 AND NC.close_date <= NC.out_date';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $queryString .= ' AND NC.solution_id = 0 AND NOW() <= NC.out_date';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $queryString .= ' AND NC.solution_id != 0 AND NC.close_date > NC.out_date';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $queryString .= ' AND NC.solution_id = 0 AND NOW() > NC.out_date';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $queryString .= ' AND NC.protocol_out_date < NC.in_date';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $queryString .= ' AND NC.solution_id = 0';
        }

        if ($param['expertId'] != NULL and $param['expertId'] != 0) {

            $query = $this->db->query('
                SELECT 
                    NC.create_number,
                    DATE(NC.in_date) AS in_date,
                    DATE(NC.out_date) AS out_date,
                    NC.param,
                    IF(NC.is_mixx > 0, \'Бүрэлдэхүүнтэй\', \'Бүрэлдэхүүнгүй\') AS is_mixx,
                    NC.agent_name,
                    NC.crime_value,
                    NC.crime_object,
                    NC.object_count,
                    NC.partner_id,
                    NC.question,
                    NC.expert,
                    NC.weight,
                    NC.close_description,
                    DATE(NC.close_date) AS close_date,
                    NC.description,
                    NC.research_type_id,
                    NC.crime_type_id,
                    NC.given,
                    NC.crime_again_id,
                    NC.motive_id,
                    NC.solution_id,
                    NS.title AS solution_title,
                    NC.latent_print_department_id,
                    NC.latent_print_expert_id,
                    NC.close_type_id,
                    NC.created_date,
                    NC.modified_date,
                    NC.created_user_id,
                    NC.modified_user_id,
                    NC.order_num,
                    NC.city_id,
                    NC.soum_id,
                    NC.street_id,
                    NC.year,
                    NC.protocol_number,
                    DATE(NC.protocol_in_date) AS protocol_in_date,
                    DATE(NC.protocol_out_date) AS protocol_out_date,
                    NC.department_id,
                    NC.short_info,
                    NC.extra_expert_value,
                    NC.expert_department_id
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_crime` AS NC ON NE.cont_id = NC.id AND NE.mod_id = NC.mod_id 
                LEFT JOIN `gaz_category` AS C ON NC.cat_id = C.id AND NC.mod_id = C.mod_id 
                LEFT JOIN `gaz_nifs_solution` AS NS ON NC.solution_id = NS.id AND NC.mod_id = NS.mod_id 
                WHERE NE.expert_id = ' . $param['expertId'] . $queryString . '
                ORDER BY NC.create_number DESC');
        } else {

            //CONCAT(NC.protocol_number, "<br>")
            $query = $this->db->query('
                SELECT 
                    NC.create_number,
                    DATE(NC.in_date) AS in_date,
                    DATE(NC.out_date) AS out_date,
                    NC.param,
                    IF(NC.is_mixx > 0, \'Бүрэлдэхүүнтэй\', \'Бүрэлдэхүүнгүй\') AS is_mixx,
                    NC.agent_name,
                    NC.crime_value,
                    NC.crime_object,
                    NC.object_count,
                    NC.partner_id,
                    NC.question,
                    NC.expert,
                    NC.weight,
                    NC.close_description,
                    DATE(NC.close_date) AS close_date,
                    NC.description,
                    NC.research_type_id,
                    NC.crime_type_id,
                    NC.given,
                    NC.crime_again_id,
                    NC.motive_id,
                    NC.solution_id,
                    NS.title AS solution_title,
                    NC.latent_print_department_id,
                    NC.latent_print_expert_id,
                    NC.close_type_id,
                    NC.created_date,
                    NC.modified_date,
                    NC.created_user_id,
                    NC.modified_user_id,
                    NC.order_num,
                    NC.city_id,
                    NC.soum_id,
                    NC.street_id,
                    NC.year,
                    NC.protocol_number,
                    DATE(NC.protocol_in_date) AS protocol_in_date,
                    DATE(NC.protocol_out_date) AS protocol_out_date,
                    NC.department_id,
                    NC.short_info,
                    NC.extra_expert_value,
                    NC.expert_department_id
                FROM `gaz_nifs_crime` AS NC
                LEFT JOIN `gaz_category` AS C ON NC.cat_id = C.id AND NC.mod_id = C.mod_id 
                LEFT JOIN `gaz_nifs_solution` AS NS ON NC.solution_id = NS.id AND NC.mod_id = NS.mod_id 
                WHERE 1 = 1 ' . $queryString . '
                ORDER BY NC.create_number DESC');
        }

        if ($query->num_rows() > 0) {

            return $query->result();
        }

        return false;
    }

    public function dataUpdate_model1($param = array()) {

        $this->query = $this->db->query('
                SELECT 
                    NC.id, 
                    NC.research_type_id, 
                    NC.motive_id, 
                    NC.partner_id,
                    NC.cat_id,
                    NC.crime_type_id,
                    NC.latent_print_expert_id
                FROM `gaz_nifs_crime` AS NC');

        foreach ($this->query->result() as $key => $row) {

            $this->nifsResearchTypeData = $this->nifsResearchType->getData_model(array('selectedId' => $row->research_type_id));
            $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $row->motive_id));
            $this->partnerData = $this->partner->getData_model(array('selectedId' => $row->partner_id));
            $this->categoryData = $this->category->getData_model(array('selectedId' => $row->cat_id));
            $this->nifsCrimeTypeData = $this->nifsCrimeType->getData_model(array('selectedId' => $row->crime_type_id));
            $this->nifslatentPrintExpertData = $this->hrPeople->getData_model(array('selectedId' => $row->latent_print_expert_id));

            $data = array(
                'param' => json_encode(array(
                    'researchType' => ($this->nifsResearchTypeData != false ? $this->nifsResearchTypeData->title : ''),
                    'motive' => ($this->nifsMotiveData != false ? $this->nifsMotiveData->title : ''),
                    'partner' => ($this->partnerData != false ? $this->partnerData->title : ''),
                    'category' => ($this->categoryData != false ? $this->categoryData->title : ''),
                    'type' => ($this->nifsCrimeTypeData != false ? $this->nifsCrimeTypeData->title : ''),
                    'latentPrintExpert' => ($this->nifslatentPrintExpertData != false ? $this->nifslatentPrintExpertData->full_name : '')))
            );

            $this->db->where('id', $row->id);
            $this->db->update('gaz_nifs_crime', $data);
        }
    }

    public function dataUpdate_model($param = array()) {

        $this->query = $this->db->query('
                SELECT 
                    id, 
                    in_date, 
                    year
                FROM `gaz_nifs_crime`');

        foreach ($this->query->result() as $key => $row) {
            if ((date('Ymd', strtotime($row->in_date)) >= date('Ymd', strtotime('2018-12-08'))) and date('Ymd', strtotime($row->in_date)) <= date('Ymd', strtotime('2019-12-06'))) {
                
                $this->db->where('id', $row->id);
                $this->db->update('gaz_nifs_crime', array('year' => '2019'));
                
            } else if ((date('Ymd', strtotime($row->in_date)) >= date('Ymd', strtotime('2019-12-07'))) and date('Ymd', strtotime($row->in_date)) <= date('Ymd', strtotime('2020-12-06'))) {
                
                $this->db->where('id', $row->id);
                $this->db->update('gaz_nifs_crime', array('year' => '2020'));
                
            }
            
        }
    }

}
