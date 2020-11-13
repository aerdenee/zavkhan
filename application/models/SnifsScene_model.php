<?php

class SnifsScene_model extends CI_Model {

    private static $list = array();

    function __construct() {

        parent::__construct();
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Suser_model', 'user');
        $this->load->model('SnifsMotive_model', 'nifsMotive');
        $this->load->model('SnifsResearchType_model', 'nifsResearchType');
        $this->load->model('SnifsSceneType_model', 'nifsSceneType');
        $this->load->model('SreportMenu_model', 'sreportMenu');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('SnifsStatus_model', 'nifsStatus');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('SnifsSolution_model', 'nifsSolution');
        $this->load->model('Schart_model', 'chart');
        $this->load->model('SnifsSceneFingerType_model', 'nifsSceneFingerType');

        $this->modId = 34;
        $this->chartCatId = 399;

        $this->nifsSceneTypeId = 353;

        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->isActiveDepartment = 'is_active_nifs_crime';
        $this->reportDefaultDayInterval = 7;
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => $this->modId,
            'cat_id' => 0,
            'create_number' => getCreateNumber(array('createNumber' => 0, 'table' => 'nifs_scene', 'departmentId' => $this->nifsDepartmentId)),
            'department_id' => 0,
            'partner_id' => 0,
            'scene_expert' => '',
            'in_date' => date('Y-m-d H:i:s'),
            'out_date' => date('Y-m-d H:i:s'),
            'crime_type_id' => 0,
            'scene_value' => '',
            'is_trace' => 0,
            'finger_print' => '',
            'finger_print_count' => '',
            'finger_count' => '',
            'finger_print_type_id' => 0,
            'boot_print' => '',
            'boot_print_count' => '',
            'boot_print_type_id' => 0,
            'transport_print' => '',
            'transport_print_count' => '',
            'transport_print_type_id' => 0,
            'other_print' => '',
            'other_print_count' => 0,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'order_num' => getOrderNum(array('table' => 'nifs_scene', 'field' => 'order_num')),
            'year' => $this->session->adminCloseYear,
            'expert' => '',
            'param' => '',
            'extra_expert_value' => '',
            'photo_count' => '',
            'description' => ''
        )));
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cat_id,
                create_number,
                department_id,
                partner_id,
                scene_expert,
                in_date,
                out_date,
                scene_type_id,
                scene_value,
                is_trace,
                finger_print,
                finger_print_count,
                finger_count,
                finger_print_type_id,
                boot_print,
                boot_print_count,
                boot_print_type_id,
                transport_print,
                transport_print_count,
                transport_print_type_id,
                other_print,
                other_print_count,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id,
                order_num,
                year,
                expert,
                param,
                extra_expert_value,
                photo_count,
                description
            FROM `gaz_nifs_scene`
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

            $queryString .= ' AND NS.created_user_id = ' . $this->session->adminUserId;
        } else {

            $queryString .= ' AND NS.created_user_id = -1';
        }

        if ($param['departmentId'] != 0) {

            $queryString .= ' AND NS.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
        } else {

            $queryString .= ' AND NS.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        }

        if ($param['catId'] != 0) {

            $queryString .= ' AND NS.cat_id = ' . $param['catId'];
        } else if ($param['catId'] == 'all') {
            $queryString .= ' AND NS.cat_id != 0';
        }

        if ($param['partnerId'] != 0) {

            $queryString .= ' AND NS.partner_id = ' . $param['partnerId'];
        } else if ($param['partnerId'] == 'all') {
            $queryString .= ' AND NS.partner_id != 0';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NS.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NS.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NS.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NS.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryString .= ' AND NS.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['sceneTypeId'] != 0) {

            $queryString .= ' AND NS.scene_type_id = ' . $param['sceneTypeId'];
        } else if ($param['sceneTypeId'] == 'all') {
            $queryString .= ' AND NS.scene_type_id != 0';
        }

        if ($param['createNumber'] != '') {

            $queryString .= ' AND NS.create_number = ' . $param['createNumber'];
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Албан тушаалтны нэр
                        $queryString .= ' AND LOWER(NS.scene_expert) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Тайлбар
                        $queryString .= ' AND LOWER(NS.scene_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;
                default: {
                        $queryString .= ' 
                        AND (LOWER(NS.scene_expert) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                        OR LOWER(NS.scene_value) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;
            }
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Албан тушаалтны нэр
                        $queryString .= ' AND LOWER(NS.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Тайлбар
                        $queryString .= ' AND LOWER(NS.scene_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;
                default: {
                        $queryString .= ' 
                        AND (LOWER(NS.scene_value) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                        OR LOWER(NS.scene_expert) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;
            }
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
                INNER JOIN `gaz_nifs_scene` AS NS ON NE.cont_id = NS.id AND NE.mod_id = NS.mod_id
                WHERE NE.mod_id = ' . $this->modId . $expertString . $queryString);
        } else {

            $query = $this->db->query('
                SELECT 
                    NS.id
                FROM `gaz_nifs_scene` AS NS
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

            $queryString .= ' AND NS.created_user_id = ' . $this->session->adminUserId;
        } else {

            $queryString .= ' AND NS.created_user_id = -1';
        }

        if ($param['departmentId'] != 0) {

            $queryString .= ' AND NS.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
        } else {

            $queryString .= ' AND NS.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        }

        if ($param['catId'] != 0) {

            $queryString .= ' AND NS.cat_id = ' . $param['catId'];
        } else if ($param['catId'] == 'all') {
            $queryString .= ' AND NS.cat_id != 0';
        }

        if ($param['partnerId'] != 0) {

            $queryString .= ' AND NS.partner_id = ' . $param['partnerId'];
        } else if ($param['partnerId'] == 'all') {
            $queryString .= ' AND NS.partner_id != 0';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NS.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NS.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NS.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NS.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryString .= ' AND NS.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['sceneTypeId'] != 0) {

            $queryString .= ' AND NS.scene_type_id = ' . $param['sceneTypeId'];
        } else if ($param['sceneTypeId'] == 'all') {
            $queryString .= ' AND NS.scene_type_id != 0';
        }

        if ($param['createNumber'] != '') {

            $queryString .= ' AND NS.create_number = ' . $param['createNumber'];
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Албан тушаалтны нэр
                        $queryString .= ' AND LOWER(NS.scene_expert) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Тайлбар
                        $queryString .= ' AND LOWER(NS.scene_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;
                default: {
                        $queryString .= ' 
                        AND (LOWER(NS.scene_expert) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                        OR LOWER(NS.scene_value) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;
            }
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Албан тушаалтны нэр
                        $queryString .= ' AND LOWER(NS.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Тайлбар
                        $queryString .= ' AND LOWER(NS.scene_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;
                default: {
                        $queryString .= ' 
                        AND (LOWER(NS.scene_value) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                        OR LOWER(NS.scene_expert) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;
            }
        }

        if ($param['expertId'] != 0) {

            $expertString = ' AND NE.expert_id = ' . $param['expertId'];

            if ($param['expertId'] == 'all') {
                $expertString = ' AND NE.expert_id != 0';
            }

            $query = $this->db->query('
                SELECT 
                    NS.id,
                    NS.mod_id,
                    NS.create_number,
                    CONCAT("Э: ", DATE_FORMAT(NS.in_date, \'%Y-%m-%d %H:%i\'), "<br>", "Д: ", DATE_FORMAT(NS.out_date, \'%Y-%m-%d %H:%i\')) AS in_out_date,
                    NS.partner_id,
                    NS.scene_expert,
                    NS.expert,
                    NS.scene_value,
                    NS.is_trace,
                    IF(NS.finger_count > 0, CONCAT(\'Гарын мөр: \', NS.finger_count, \', \'), \'\') AS finger_count,
                    IF(NS.finger_print_count > 0, CONCAT(\'Гарын хээний дардас: \', NS.finger_print_count, \', \'), \'\') AS finger_print_count,
                    IF(NS.boot_print_count > 0, CONCAT(\'Гутлын мөр: \', NS.boot_print_count, \', \'), \'\') AS boot_print_count,
                    IF(NS.transport_print_count > 0, CONCAT(\'Тээврийн хэрэгслийн мөр: \', NS.transport_print_count, \', \'), \'\') AS transport_print_count,
                    IF(NS.other_print_count > 0, CONCAT(\'Бусад ул мөр, эд мөрийн баримт: \', NS.other_print, \', \'), \'\') AS other_print,
                    IF(NS.photo_count > 0, CONCAT(\'Гэрэл зураг: \', NS.photo_count, \', \'), \'\') AS photo_count,
                    NS.finger_print_type_id,
                    NS.boot_print_type_id,
                    NS.transport_print_type_id,
                    (NS.finger_print_count + NS.finger_count + NS.boot_print_count + NS.transport_print_count + NS.other_print_count + NS.photo_count) AS all_count,
                    NS.created_date,
                    NS.modified_date,
                    NS.created_user_id,
                    NS.modified_user_id,
                    NS.order_num,
                    NS.year,
                    NS.expert,
                    NS.param,
                    NS.extra_expert_value,
                    NS.description
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_scene` AS NS ON NE.cont_id = NS.id AND NE.mod_id = NS.mod_id 
                WHERE NE.mod_id = ' . $this->modId . $expertString . $queryString . '
                ORDER BY NS.create_number DESC
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        } else {
            //CONCAT(NC.protocol_number, "<br>")
            $query = $this->db->query('
                SELECT 
                    NS.id,
                    NS.mod_id,
                    NS.create_number,
                    CONCAT("Э: ", DATE_FORMAT(NS.in_date, \'%Y-%m-%d %H:%i\'), "<br>", "Д: ", DATE_FORMAT(NS.out_date, \'%Y-%m-%d %H:%i\')) AS in_out_date,
                    NS.partner_id,
                    NS.scene_expert,
                    NS.expert,
                    NS.scene_value,
                    NS.is_trace,
                    IF(NS.finger_count > 0, CONCAT(\'Гарын мөр: \', NS.finger_count, \', \'), \'\') AS finger_count,
                    IF(NS.finger_print_count > 0, CONCAT(\'Гарын хээний дардас: \', NS.finger_print_count, \', \'), \'\') AS finger_print_count,
                    IF(NS.boot_print_count > 0, CONCAT(\'Гутлын мөр: \', NS.boot_print_count, \', \'), \'\') AS boot_print_count,
                    IF(NS.transport_print_count > 0, CONCAT(\'Тээврийн хэрэгслийн мөр: \', NS.transport_print_count, \', \'), \'\') AS transport_print_count,
                    IF(NS.other_print_count > 0, CONCAT(\'Бусад ул мөр, эд мөрийн баримт: \', NS.other_print, \', \'), \'\') AS other_print,
                    IF(NS.photo_count > 0, CONCAT(\'Гэрэл зураг: \', NS.photo_count, \', \'), \'\') AS photo_count,
                    NS.finger_print_type_id,
                    NS.boot_print_type_id,
                    NS.transport_print_type_id,
                    (NS.finger_print_count + NS.finger_count + NS.boot_print_count + NS.transport_print_count + NS.other_print_count + NS.photo_count) AS all_count,
                    NS.created_date,
                    NS.modified_date,
                    NS.created_user_id,
                    NS.modified_user_id,
                    NS.order_num,
                    NS.year,
                    NS.expert,
                    NS.param,
                    NS.extra_expert_value,
                    NS.description
                FROM `gaz_nifs_scene` AS NS
                WHERE 1 = 1 ' . $queryString . '
                ORDER BY NS.create_number DESC
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        }

        if ($query->num_rows() > 0) {
            $i = 1;
            foreach ($query->result() as $key => $row) {

                $param = json_decode($row->param);

                array_push($data, array(
                    'id' => $row->id,
                    'create_number' => $row->create_number,
                    'created_user_id' => $row->created_user_id,
                    'in_out_date' => $row->in_out_date,
                    'partner' => $param->partner . ' ' . $row->scene_expert,
                    'expert' => $row->expert,
                    'type' => $param->category . '<br><strong>' . $param->sceneType . '</strong>',
                    'scene_value' => $row->scene_value,
                    'object' => '<strong>Нийт: ' . $row->all_count . '</strong>, ' . $row->finger_count . $row->finger_print_count . $row->boot_print_count . $row->transport_print_count . $row->other_print . $row->photo_count,
                    'description' => $row->description
                ));
                $i++;
            }
        }
        return array('data' => $data, 'search' => self::searchKeywordView_model());
    }

    public function insert_model($param = array()) {
        $this->expert = array();

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->userdata['adminUserId'],
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->post('partnerId')));
        $this->categoryData = $this->category->getData_model(array('selectedId' => $this->input->post('catId')));
        $this->nifsSceneTypeData = $this->nifsSceneType->getData_model(array('selectedId' => $this->input->post('sceneTypeId')));
        $this->fingerPrintTypeData = $this->nifsSceneFingerType->getData_model(array('selectedId' => $this->input->post('fingerPrintTypeId')));
        $this->bootPrintTypeData = $this->nifsSceneFingerType->getData_model(array('selectedId' => $this->input->post('bootPrintTypeId')));
        $this->transportPrintTypeData = $this->nifsSceneFingerType->getData_model(array('selectedId' => $this->input->post('transportPrintTypeId')));

        $this->data = array(
            array(
                'id' => $param['getUID'],
                'create_number' => $this->input->post('createNumber'),
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'department_id' => $this->nifsDepartmentId,
                'partner_id' => $this->input->post('partnerId'),
                'scene_expert' => $this->input->post('sceneExpert'),
                'in_date' => $this->input->post('inDate') . ' ' . $this->input->post('inTime') . ':00',
                'out_date' => $this->input->post('outDate') . ' ' . $this->input->post('outTime') . ':00',
                'scene_type_id' => $this->input->post('sceneTypeId'),
                'scene_value' => $this->input->post('sceneValue'),
                'is_trace' => $this->input->post('isTrace'),
                'finger_print_count' => $this->input->post('fingerPrintCount'),
                'finger_count' => $this->input->post('fingerCount'),
                'finger_print_type_id' => $this->input->post('fingerPrintTypeId'),
                'boot_print_count' => $this->input->post('bootPrintCount'),
                'boot_print_type_id' => $this->input->post('bootPrintTypeId'),
                'transport_print_count' => $this->input->post('transportPrintCount'),
                'transport_print_type_id' => $this->input->post('transportPrintTypeId'),
                'other_print' => $this->input->post('otherPrint'),
                'other_print_count' => $this->input->post('otherPrintCount'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'order_num' => getOrderNum(array('table' => 'nifs_scene', 'field' => 'order_num')),
                'year' => $this->session->adminCloseYear,
                'expert' => '',
                'extra_expert_value' => $this->input->post('extraExpertValue'),
                'photo_count' => $this->input->post('photoCount'),
                'description' => $this->input->post('description'),
                'param' => json_encode(array(
                    'partner' => ($this->partnerData != false ? $this->partnerData->title : ''),
                    'category' => ($this->categoryData != false ? $this->categoryData->title : ''),
                    'sceneType' => ($this->nifsSceneTypeData != false ? $this->nifsSceneTypeData->title : ''),
                    'fingerPrintType' => ($this->fingerPrintTypeData != false ? $this->fingerPrintTypeData->title : ''),
                    'bootPrintType' => ($this->bootPrintTypeData != false ? $this->bootPrintTypeData->title : ''),
                    'transportPrintType' => ($this->transportPrintTypeData != false ? $this->transportPrintTypeData->title : '')))
            )
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'nifs_scene', $this->data)) {
            return $this->hrPeople->updateHrPeopleParam_model(array('modId' => $this->input->post('modId'), 'contId' => $param['getUID'], 'expertId' => $this->input->post('expertId'), 'extraExpertValue' => $this->input->post('extraExpertValue'), 'table' => 'nifs_scene'));
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {


        $this->editData = $this->editFormData_model(array('id' => $this->input->post('id')));
        $this->editData = json_decode($this->editData->param);

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->userdata['adminUserId'],
            'type' => LOG_TYPE_UPDATE,
            'data' => json_encode($_POST)));

        $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->post('partnerId')));
        $this->categoryData = $this->category->getData_model(array('selectedId' => $this->input->post('catId')));
        $this->nifsSceneTypeData = $this->nifsSceneType->getData_model(array('selectedId' => $this->input->post('sceneTypeId')));
        $this->fingerPrintTypeData = $this->nifsSceneFingerType->getData_model(array('selectedId' => $this->input->post('fingerPrintTypeId')));
        $this->bootPrintTypeData = $this->nifsSceneFingerType->getData_model(array('selectedId' => $this->input->post('bootPrintTypeId')));
        $this->transportPrintTypeData = $this->nifsSceneFingerType->getData_model(array('selectedId' => $this->input->post('transportPrintTypeId')));

        $this->data = array(
            'create_number' => $this->input->post('createNumber'),
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'partner_id' => $this->input->post('partnerId'),
            'scene_expert' => $this->input->post('sceneExpert'),
            'in_date' => $this->input->post('inDate') . ' ' . $this->input->post('inTime') . ':00',
            'out_date' => $this->input->post('outDate') . ' ' . $this->input->post('outTime') . ':00',
            'scene_type_id' => $this->input->post('sceneTypeId'),
            'scene_value' => $this->input->post('sceneValue'),
            'is_trace' => $this->input->post('isTrace'),
            'finger_print_count' => $this->input->post('fingerPrintCount'),
            'finger_count' => $this->input->post('fingerCount'),
            'finger_print_type_id' => $this->input->post('fingerPrintTypeId'),
            'boot_print_count' => $this->input->post('bootPrintCount'),
            'boot_print_type_id' => $this->input->post('bootPrintTypeId'),
            'transport_print_count' => $this->input->post('transportPrintCount'),
            'transport_print_type_id' => $this->input->post('transportPrintTypeId'),
            'other_print' => $this->input->post('otherPrint'),
            'other_print_count' => $this->input->post('otherPrintCount'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'extra_expert_value' => $this->input->post('extraExpertValue'),
            'photo_count' => $this->input->post('photoCount'),
            'description' => $this->input->post('description'),
            'param' => json_encode(array(
                'partner' => ($this->partnerData != false ? $this->partnerData->title : ''),
                'category' => ($this->categoryData != false ? $this->categoryData->title : ''),
                'sceneType' => ($this->nifsSceneTypeData != false ? $this->nifsSceneTypeData->title : ''),
                'fingerPrintType' => ($this->fingerPrintTypeData != false ? $this->fingerPrintTypeData->title : ''),
                'bootPrintType' => ($this->bootPrintTypeData != false ? $this->bootPrintTypeData->title : ''),
                'transportPrintType' => ($this->transportPrintTypeData != false ? $this->transportPrintTypeData->title : ''))));

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'nifs_scene', $this->data)) {

            return $this->hrPeople->updateHrPeopleParam_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('id'), 'expertId' => $this->input->post('expertId'), 'extraExpertValue' => $this->input->post('extraExpertValue'), 'table' => 'nifs_scene'));
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
        if ($this->db->delete($this->db->dbprefix . 'nifs_scene')) {
            $this->db->where('mod_id', $this->modId);
            $this->db->where('cont_id', $this->input->post('id'));
            $this->db->delete($this->db->dbprefix . 'nifs_expert');
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
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
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
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

        if ($this->input->get('sceneTypeId')) {
            if ($this->input->get('sceneTypeId') != 'all') {
                $this->nifsSceneType = $this->nifsSceneType->getData_model(array('selectedId' => $this->input->get('sceneTypeId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsSceneType->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('typeId', $this->input->get('typeId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('motiveId')) {
            if ($this->input->get('motiveId') != 'all') {
                $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $this->input->get('motiveId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsMotiveData->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
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

        if ($this->input->get('latentPrintDepartmentId')) {
            if ($this->input->get('latentPrintDepartmentId') != 'all') {
                $this->hrPeopleDepartmentData = $this->hrPeopleDepartment->getData_model(array('selectedId' => $this->input->get('latentPrintDepartmentId')));
                $this->string .= '<span class="label label-default label-rounded">Мөр бэхжүүлсэн газар: ' . $this->hrPeopleDepartmentData->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('latentPrintDepartmentId', $this->input->get('latentPrintDepartmentId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('latentPrintExpertId')) {
            if ($this->input->get('latentPrintExpertId') != 'all') {
                $this->latentPrintExpertData = $this->user->getData_model(array('selectedId' => $this->input->get('latentPrintExpertId')));
                $this->string .= '<span class="label label-default label-rounded">Мөр бэхжүүлсэн шинжээч' . mb_substr($this->latentPrintExpertData->lname, 0, 1, 'UTF-8') . '.' . $this->latentPrintExpertData->fname . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

            $this->string .= form_hidden('latentPrintExpertId', $this->input->get('latentPrintExpertId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('expertId')) {
            if ($this->input->get('expertId') != 'all') {
                $this->expertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->get('expertId')));
                $this->string .= '<span class="label label-default label-rounded">' . mb_substr($this->expertData->lname, 0, 1, 'UTF-8') . '.' . $this->expertData->fname . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
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

        if ($this->input->get('solutionId')) {
            if ($this->input->get('solutionId') != 'all') {
                $this->nifsSolution = $this->nifsSolution->getData_model(array('selectedId' => $this->input->get('solutionId')));
                $this->string .= '<span class="label label-default label-rounded">' . $this->nifsSolution->title . '</span>';
            } else {
                $this->string .= '<span class="label label-default label-rounded">Бүгд</span>';
            }

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

                $this->string .= ' <a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            }
        }

        return ($this->showResetBtn ? '<div class="_search-result-inner"><form action="javascript:;" id="form-nifs-scene"> Хайлтын үр дүн: ' . $this->string . '</form></div>' : '');
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cat_id,
                create_number,
                department_id,
                partner_id,
                scene_expert,
                in_date,
                out_date,
                scene_type_id,
                scene_value,
                is_trace,
                finger_print,
                finger_print_count,
                finger_print_type_id,
                boot_print,
                boot_print_count,
                boot_print_type_id,
                transport_print,
                transport_print_count,
                transport_print_type_id,
                other_print,
                other_print_count,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id,
                order_num,
                year,
                expert,
                param,
                extra_expert_value,
                photo_count
            FROM `gaz_nifs_scene`
            WHERE id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }

        return false;
    }

    public function export_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $queryString = $queryStringJoinDepartment = $queryStringDepartment = '';

        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {

            $queryString .= '';
        } elseif ($auth->our->read and ! $auth->your->read) {

            $queryString .= ' AND NS.created_user_id = ' . $this->session->adminUserId;
        } else {

            $queryString .= ' AND NS.created_user_id = -1';
        }

        if ($this->session->adminDepartmentRoleId == 2) {

            $queryString .= ' AND NS.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
        } else {

            if ($param['departmentId'] != 0) {

                $queryString .= ' AND NS.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            } else {

                $queryString .= ' AND NS.department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->nifsDepartmentId) . ')';
            }
        }

        if ($param['catId'] != 0) {
            $queryString .= ' AND NS.cat_id = ' . $param['catId'];
        }

        if ($param['partnerId'] != 0) {

            $queryString .= ' AND NS.partner_id = ' . $param['partnerId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NS.in_date) AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NS.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NS.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NS.in_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryString .= ' AND NS.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['sceneTypeId'] != 0) {
            $queryString .= ' AND NS.scene_type_id = ' . $param['sceneTypeId'];
        }

        if ($param['createNumber'] != '') {

            $queryString .= ' AND NS.create_number = ' . $param['createNumber'];
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Албан тушаалтны нэр
                        $queryString .= ' AND LOWER(NS.scene_expert) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Тайлбар
                        $queryString .= ' AND LOWER(NS.scene_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;
                default: {
                        $queryString .= ' 
                        AND (LOWER(NS.scene_expert) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
                        OR LOWER(NS.scene_value) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
                    };
                    break;
            }
        }

        if ($param['expertId'] != 0) {

            $query = $this->db->query('
                SELECT 
                    NS.id,
                    NS.mod_id,
                    NS.create_number,
                    DATE_FORMAT(NS.in_date, \'%Y-%m-%d %H:%i\') AS in_date,
                    DATE_FORMAT(NS.out_date, \'%Y-%m-%d %H:%i\') AS out_date,
                    NS.partner_id,
                    NS.scene_expert,
                    NS.expert,
                    NS.scene_value,
                    IF(NS.is_trace = 1, \'Тийм\', \'Үгүй\') AS is_trace,
                    NS.finger_print,
                    NS.finger_print_count,
                    NS.finger_print_type_id,
                    NS.boot_print,
                    NS.boot_print_count,
                    NS.boot_print_type_id,
                    NS.transport_print,
                    NS.transport_print_count,
                    NS.transport_print_type_id,
                    NS.other_print,
                    NS.other_print_count,
                    (NS.finger_print_count + NS.boot_print_count + NS.transport_print_count + NS.other_print_count) AS all_count,
                    NS.created_date,
                    NS.modified_date,
                    NS.created_user_id,
                    NS.modified_user_id,
                    NS.order_num,
                    NS.year,
                    NS.expert,
                    NS.param,
                    NS.extra_expert_value,
                    NS.photo_count
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_scene` AS NC ON NE.cont_id = NC.id AND NE.mod_id = NC.mod_id 
                WHERE NE.expert_id = ' . $param['expertId'] . $queryString . '
                ORDER BY NE.create_number DESC');
        } else {
            //CONCAT(NC.protocol_number, "<br>")
            $query = $this->db->query('
                SELECT 
                    NS.id,
                    NS.mod_id,
                    NS.create_number,
                    DATE_FORMAT(NS.in_date, \'%Y-%m-%d %H:%i\') AS in_date,
                    DATE_FORMAT(NS.out_date, \'%Y-%m-%d %H:%i\') AS out_date,
                    NS.partner_id,
                    NS.scene_expert,
                    NS.expert,
                    NS.scene_value,
                    IF(NS.is_trace = 1, \'Тийм\', \'Үгүй\') AS is_trace,
                    NS.finger_print,
                    NS.finger_print_count,
                    NS.finger_print_type_id,
                    NS.boot_print,
                    NS.boot_print_count,
                    NS.boot_print_type_id,
                    NS.transport_print,
                    NS.transport_print_count,
                    NS.transport_print_type_id,
                    NS.other_print,
                    NS.other_print_count,
                    (NS.finger_print_count + NS.boot_print_count + NS.transport_print_count + NS.other_print_count) AS all_count,
                    NS.created_date,
                    NS.modified_date,
                    NS.created_user_id,
                    NS.modified_user_id,
                    NS.order_num,
                    NS.year,
                    NS.expert,
                    NS.param,
                    NS.extra_expert_value,
                    NS.photo_count
                FROM `gaz_nifs_scene` AS NS
                WHERE 1 = 1 ' . $queryString . '
                ORDER BY NS.create_number DESC');
        }

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function dataUpdate_model($param = array()) {

        $this->query = $this->db->query('
            SELECT 
                NE.id, 
                NE.partner_id, 
                NE.cat_id, 
                NE.scene_type_id,
                NE.finger_print_type_id,
                NE.boot_print_type_id,
                NE.transport_print_type_id
            FROM `gaz_nifs_scene` AS NE ');

        foreach ($this->query->result() as $key => $row) {

            $this->partnerData = $this->partner->getData_model(array('selectedId' => $row->partner_id));
            $this->categoryData = $this->category->getData_model(array('selectedId' => $row->cat_id));
            $this->nifsSceneTypeData = $this->nifsSceneType->getData_model(array('selectedId' => $row->scene_type_id));
            $this->fingerPrintTypeData = $this->nifsSceneFingerType->getData_model(array('selectedId' => $row->finger_print_type_id));
            $this->bootPrintTypeData = $this->nifsSceneFingerType->getData_model(array('selectedId' => $row->boot_print_type_id));
            $this->transportPrintTypData = $this->nifsSceneFingerType->getData_model(array('selectedId' => $row->transport_print_type_id));

            $this->db->where('id', $row->id);
            $this->db->update('gaz_nifs_scene', array('param' => json_encode(array(
                    'partner' => ($this->partnerData != false ? $this->partnerData->title : ''),
                    'category' => ($this->categoryData != false ? $this->categoryData->title : ''),
                    'sceneType' => ($this->nifsSceneTypeData != false ? $this->nifsSceneTypeData->title : ''),
                    'fingerPrintType' => ($this->fingerPrintTypeData != false ? $this->fingerPrintTypeData->title : ''),
                    'bootPrintType' => ($this->bootPrintTypeData != false ? $this->bootPrintTypeData->title : ''),
                    'transportPrintType' => ($this->transportPrintTypData != false ? $this->transportPrintTypData->title : '')))));
        }
    }

    public function getReportWorkInformationData_model($param = array()) {

        $htmlData = $queryStringData = $queryStringYearData = $isAjaxDepartmentUrl = '';

        $countTsogtsos = 0;
        if ($param['departmentId'] > 0) {

            $queryStringData .= ' AND NS.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $queryStringYearData .= ' AND NS.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NS.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NS.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NS.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NS.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NS.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $query = $this->db->query('
            SELECT
                C.id,
                C.title,
                NS.date_count,
                IF(NS.date_count > 0, \'_row-more\', \'\') AS date_count_class,
                FINGER_PRINT_TYPE_1.finger_print_type_1_count,
                FINGER_PRINT_TYPE_2.finger_print_type_2_count,
                FINGER_PRINT_TYPE_3.finger_print_type_3_count,
                BOOT_PRINT_TYPE_3.boot_print_type_3_count,
                BOOT_PRINT_TYPE_4.boot_print_type_4_count,
                TRANSPORT_PRINT_TYPE_5.transport_print_type_5_count,
                TRANSPORT_PRINT_TYPE_6.transport_print_type_6_count,
                NS.other_print_count,
                NS.photo_count
            FROM `gaz_category` AS C
            LEFT JOIN (
                SELECT
                    NS.cat_id,
                    COUNT(NS.cat_id) AS date_count,
                    SUM(NS.other_print_count) AS other_print_count,
                    SUM(NS.photo_count) AS photo_count
                FROM `gaz_nifs_scene` AS NS 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NS.cat_id
            ) AS NS ON NS.cat_id = C.id
            LEFT JOIN (
                SELECT
                    NS.cat_id,
                    SUM(NS.finger_count) AS finger_print_type_1_count
                FROM `gaz_nifs_scene` AS NS 
                WHERE NS.finger_print_type_id = 1 ' . $queryStringData . '
                GROUP BY NS.cat_id
            ) AS FINGER_PRINT_TYPE_1 ON FINGER_PRINT_TYPE_1.cat_id = C.id
            LEFT JOIN (
                SELECT
                    NS.cat_id,
                    SUM(NS.finger_count) AS finger_print_type_2_count
                FROM `gaz_nifs_scene` AS NS 
                WHERE NS.finger_print_type_id = 2 ' . $queryStringData . '
                GROUP BY NS.cat_id
            ) AS FINGER_PRINT_TYPE_2 ON FINGER_PRINT_TYPE_2.cat_id = C.id
            LEFT JOIN (
                SELECT
                    NS.cat_id,
                    SUM(NS.finger_print_count) AS finger_print_type_3_count
                FROM `gaz_nifs_scene` AS NS 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NS.cat_id
            ) AS FINGER_PRINT_TYPE_3 ON FINGER_PRINT_TYPE_3.cat_id = C.id
            LEFT JOIN (
                SELECT
                    NS.cat_id,
                    SUM(NS.boot_print_count) AS boot_print_type_3_count
                FROM `gaz_nifs_scene` AS NS 
                WHERE NS.boot_print_type_id = 3 ' . $queryStringData . '
                GROUP BY NS.cat_id
            ) AS BOOT_PRINT_TYPE_3 ON BOOT_PRINT_TYPE_3.cat_id = C.id
            LEFT JOIN (
                SELECT
                    NS.cat_id,
                    SUM(NS.boot_print_count) AS boot_print_type_4_count
                FROM `gaz_nifs_scene` AS NS 
                WHERE NS.boot_print_type_id = 4 ' . $queryStringData . '
                GROUP BY NS.cat_id
            ) AS BOOT_PRINT_TYPE_4 ON BOOT_PRINT_TYPE_4.cat_id = C.id
            LEFT JOIN (
                SELECT
                    NS.cat_id,
                    SUM(NS.transport_print_count) AS transport_print_type_5_count
                FROM `gaz_nifs_scene` AS NS 
                WHERE NS.transport_print_type_id = 5 ' . $queryStringData . '
                GROUP BY NS.cat_id
            ) AS TRANSPORT_PRINT_TYPE_5 ON TRANSPORT_PRINT_TYPE_5.cat_id = C.id
            LEFT JOIN (
                SELECT
                    NS.cat_id,
                    SUM(NS.transport_print_count) AS transport_print_type_6_count
                FROM `gaz_nifs_scene` AS NS 
                WHERE NS.transport_print_type_id = 6 ' . $queryStringData . '
                GROUP BY NS.cat_id
            ) AS TRANSPORT_PRINT_TYPE_6 ON TRANSPORT_PRINT_TYPE_6.cat_id = C.id
            WHERE C.mod_id = ' . $this->modId . '
            ORDER BY C.title DESC');


        if ($query->num_rows() > 0) {

            $i = $sumTotalDateCount = $sumTotalFingerPrintType1Count = $sumTotalFingerPrintType2Count = $sumTotalFingerPrintType3Count = $sumtTotalBootPrintType3Count = $sumtTotalBootPrintType4Count = $sumTotalTransportPrintType5Count = $sumTotalTransportPrintType6Count = $sumTotalOtherPrintCount = $sumTotalPhotoCount = 0;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="2" style="width:30px;">#</th>';
            $htmlData .= '<th rowspan="2">Үзлэгийн төрөл</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Нийт</th>';
            $htmlData .= '<th colspan="3" style="width:160px;" class="text-center">Гарын мөр</th>';
            $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Гутлын мөр</th>';
            $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Тээврийн хэрэгслийн мөр</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Бусад</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Гэрэл зураг</th>';
            $htmlData .= '</tr>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:80px;" class="text-center">Нунтаг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Гэрэл зураг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Дардас</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Гэрэл зураг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Гипс</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Гэрэл зураг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Гипс</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $sumTotalDateCount = $sumTotalDateCount + $row->date_count;
                $sumTotalFingerPrintType1Count = $sumTotalFingerPrintType1Count + $row->finger_print_type_1_count;
                $sumTotalFingerPrintType2Count = $sumTotalFingerPrintType2Count + $row->finger_print_type_2_count;
                $sumTotalFingerPrintType3Count = $sumTotalFingerPrintType3Count + $row->finger_print_type_3_count;
                $sumtTotalBootPrintType3Count = $sumtTotalBootPrintType3Count + $row->boot_print_type_3_count;
                $sumtTotalBootPrintType4Count = $sumtTotalBootPrintType4Count + $row->boot_print_type_4_count;
                $sumTotalTransportPrintType5Count = $sumTotalTransportPrintType5Count + $row->transport_print_type_5_count;
                $sumTotalTransportPrintType6Count = $sumTotalTransportPrintType6Count + $row->transport_print_type_6_count;
                $sumTotalOtherPrintCount = $sumTotalOtherPrintCount + $row->other_print_count;
                $sumTotalPhotoCount = $sumTotalPhotoCount + $row->photo_count;

                if ($row->id == 307) {
                    $countTsogtsos = $row->date_count;
                }
                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->date_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'catId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->date_count . '</a></td>';
                $htmlData .= '<td class="text-center">' . ($row->finger_print_type_1_count > 0 ? $row->finger_print_type_1_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->finger_print_type_2_count > 0 ? $row->finger_print_type_2_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->finger_print_type_3_count > 0 ? $row->finger_print_type_3_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->boot_print_type_3_count > 0 ? $row->boot_print_type_3_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->boot_print_type_4_count > 0 ? $row->boot_print_type_4_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->transport_print_type_5_count > 0 ? $row->transport_print_type_5_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->transport_print_type_6_count > 0 ? $row->transport_print_type_6_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->other_print_count > 0 ? $row->other_print_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->photo_count > 0 ? $row->photo_count : '') . '</td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right">Нийт</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'catId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalDateCount > 0 ? $sumTotalDateCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalFingerPrintType1Count > 0 ? $sumTotalFingerPrintType1Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalFingerPrintType2Count > 0 ? $sumTotalFingerPrintType2Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalFingerPrintType3Count > 0 ? $sumTotalFingerPrintType3Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumtTotalBootPrintType3Count > 0 ? $sumtTotalBootPrintType3Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumtTotalBootPrintType4Count > 0 ? $sumtTotalBootPrintType4Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalTransportPrintType5Count > 0 ? $sumTotalTransportPrintType5Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalTransportPrintType6Count > 0 ? $sumTotalTransportPrintType6Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalOtherPrintCount > 0 ? $sumTotalOtherPrintCount : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalPhotoCount > 0 ? $sumTotalPhotoCount : '') . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNot = $this->db->query('
            SELECT
                NS.id,
                NS.create_number
            FROM `gaz_nifs_scene` AS NS
            WHERE NS.cat_id = 0 ' . $queryStringData . '
            ORDER BY NS.create_number ASC');

            if ($queryNot->num_rows() > 0) {
                $htmlNot = 'Үзлэгийн төрөл сонгоогдоогүй үзлэг (' . $queryNot->num_rows() . '): ';
                foreach ($queryNot->result() as $key => $row) {
                    $htmlNot .= '<a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'createNumber=' . $row->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $row->create_number . '</a>';
                }
                $htmlData .= '<div class="table-responsive-inside-description">' . $htmlNot . '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }

        $query = $this->db->query('
            SELECT
                NST.id,
                NST.title,
                NS.date_count,
                IF(NS.date_count > 0, \'_row-more\', \'\') AS date_count_class,
                FINGER_PRINT_TYPE_1.finger_print_type_1_count,
                FINGER_PRINT_TYPE_2.finger_print_type_2_count,
                FINGER_PRINT_TYPE_3.finger_print_type_3_count,
                BOOT_PRINT_TYPE_3.boot_print_type_3_count,
                BOOT_PRINT_TYPE_4.boot_print_type_4_count,
                TRANSPORT_PRINT_TYPE_5.transport_print_type_5_count,
                TRANSPORT_PRINT_TYPE_6.transport_print_type_6_count,
                NS.other_print_count,
                NS.photo_count
            FROM `gaz_nifs_scene_type` AS NST
            LEFT JOIN (
                SELECT
                    NS.scene_type_id,
                    COUNT(NS.scene_type_id) AS date_count,
                    SUM(NS.other_print_count) AS other_print_count,
                    SUM(NS.photo_count) AS photo_count
                FROM `gaz_nifs_scene` AS NS 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NS.scene_type_id
            ) AS NS ON NS.scene_type_id = NST.id
            LEFT JOIN (
                SELECT
                    NS.scene_type_id,
                    SUM(NS.finger_count) AS finger_print_type_1_count
                FROM `gaz_nifs_scene` AS NS 
                WHERE NS.finger_print_type_id = 1 ' . $queryStringData . '
                GROUP BY NS.scene_type_id
            ) AS FINGER_PRINT_TYPE_1 ON FINGER_PRINT_TYPE_1.scene_type_id = NST.id
            LEFT JOIN (
                SELECT
                    NS.scene_type_id,
                    SUM(NS.finger_count) AS finger_print_type_2_count
                FROM `gaz_nifs_scene` AS NS 
                WHERE NS.finger_print_type_id = 2 ' . $queryStringData . '
                GROUP BY NS.scene_type_id
            ) AS FINGER_PRINT_TYPE_2 ON FINGER_PRINT_TYPE_2.scene_type_id = NST.id
            LEFT JOIN (
                SELECT
                    NS.scene_type_id,
                    SUM(NS.finger_print_count) AS finger_print_type_3_count
                FROM `gaz_nifs_scene` AS NS 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NS.scene_type_id
            ) AS FINGER_PRINT_TYPE_3 ON FINGER_PRINT_TYPE_3.scene_type_id = NST.id
            LEFT JOIN (
                SELECT
                    NS.scene_type_id,
                    SUM(NS.boot_print_count) AS boot_print_type_3_count
                FROM `gaz_nifs_scene` AS NS 
                WHERE NS.boot_print_type_id = 3 ' . $queryStringData . '
                GROUP BY NS.scene_type_id
            ) AS BOOT_PRINT_TYPE_3 ON BOOT_PRINT_TYPE_3.scene_type_id = NST.id
            LEFT JOIN (
                SELECT
                    NS.scene_type_id,
                    SUM(NS.boot_print_count) AS boot_print_type_4_count
                FROM `gaz_nifs_scene` AS NS 
                WHERE NS.boot_print_type_id = 4 ' . $queryStringData . '
                GROUP BY NS.scene_type_id
            ) AS BOOT_PRINT_TYPE_4 ON BOOT_PRINT_TYPE_4.scene_type_id = NST.id
            LEFT JOIN (
                SELECT
                    NS.scene_type_id,
                    SUM(NS.transport_print_count) AS transport_print_type_5_count
                FROM `gaz_nifs_scene` AS NS 
                WHERE NS.transport_print_type_id = 5 ' . $queryStringData . '
                GROUP BY NS.scene_type_id
            ) AS TRANSPORT_PRINT_TYPE_5 ON TRANSPORT_PRINT_TYPE_5.scene_type_id = NST.id
            LEFT JOIN (
                SELECT
                    NS.scene_type_id,
                    SUM(NS.transport_print_count) AS transport_print_type_6_count
                FROM `gaz_nifs_scene` AS NS 
                WHERE NS.transport_print_type_id = 6 ' . $queryStringData . '
                GROUP BY NS.scene_type_id
            ) AS TRANSPORT_PRINT_TYPE_6 ON TRANSPORT_PRINT_TYPE_6.scene_type_id = NST.id
            WHERE 1 = 1 
            ORDER BY NST.title DESC');


        if ($query->num_rows() > 0) {

            $i = $sumTotalDateCount = $sumTotalFingerPrintType1Count = $sumTotalFingerPrintType2Count = $sumTotalFingerPrintType3Count = $sumtTotalBootPrintType3Count = $sumtTotalBootPrintType4Count = $sumTotalTransportPrintType5Count = $sumTotalTransportPrintType6Count = $sumTotalOtherPrintCount = $sumTotalPhotoCount = 0;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="2" style="width:30px;">#</th>';
            $htmlData .= '<th rowspan="2">Гэмт хэргийн төрөл</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Нийт</th>';
            $htmlData .= '<th colspan="3" style="width:160px;" class="text-center">Гарын мөр</th>';
            $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Гутлын мөр</th>';
            $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Тээврийн хэрэгслийн мөр</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Бусад</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Гэрэл зураг</th>';
            $htmlData .= '</tr>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:80px;" class="text-center">Нунтаг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Гэрэл зураг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Дардас</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Гэрэл зураг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Гипс</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Гэрэл зураг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Гипс</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                if ($row->id == 1) {
                    $row->date_count = $row->date_count - $countTsogtsos;
                }
                
                $sumTotalDateCount = $sumTotalDateCount + $row->date_count;
                $sumTotalFingerPrintType1Count = $sumTotalFingerPrintType1Count + $row->finger_print_type_1_count;
                $sumTotalFingerPrintType2Count = $sumTotalFingerPrintType2Count + $row->finger_print_type_2_count;
                $sumTotalFingerPrintType3Count = $sumTotalFingerPrintType3Count + $row->finger_print_type_3_count;
                $sumtTotalBootPrintType3Count = $sumtTotalBootPrintType3Count + $row->boot_print_type_3_count;
                $sumtTotalBootPrintType4Count = $sumtTotalBootPrintType4Count + $row->boot_print_type_4_count;
                $sumTotalTransportPrintType5Count = $sumTotalTransportPrintType5Count + $row->transport_print_type_5_count;
                $sumTotalTransportPrintType6Count = $sumTotalTransportPrintType6Count + $row->transport_print_type_6_count;
                $sumTotalOtherPrintCount = $sumTotalOtherPrintCount + $row->other_print_count;
                $sumTotalPhotoCount = $sumTotalPhotoCount + $row->photo_count;

                
                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->date_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'' . ($row->id == 1 ? 'catId=306&' : '') . 'sceneTypeId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->date_count . '</a></td>';
                $htmlData .= '<td class="text-center">' . ($row->finger_print_type_1_count > 0 ? $row->finger_print_type_1_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->finger_print_type_2_count > 0 ? $row->finger_print_type_2_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->finger_print_type_3_count > 0 ? $row->finger_print_type_3_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->boot_print_type_3_count > 0 ? $row->boot_print_type_3_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->boot_print_type_4_count > 0 ? $row->boot_print_type_4_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->transport_print_type_5_count > 0 ? $row->transport_print_type_5_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->transport_print_type_6_count > 0 ? $row->transport_print_type_6_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->other_print_count > 0 ? $row->other_print_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->photo_count > 0 ? $row->photo_count : '') . '</td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right">Нийт</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'sceneTypeId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalDateCount > 0 ? $sumTotalDateCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalFingerPrintType1Count > 0 ? $sumTotalFingerPrintType1Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalFingerPrintType2Count > 0 ? $sumTotalFingerPrintType2Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalFingerPrintType3Count > 0 ? $sumTotalFingerPrintType3Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumtTotalBootPrintType3Count > 0 ? $sumtTotalBootPrintType3Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumtTotalBootPrintType4Count > 0 ? $sumtTotalBootPrintType4Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalTransportPrintType5Count > 0 ? $sumTotalTransportPrintType5Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalTransportPrintType6Count > 0 ? $sumTotalTransportPrintType6Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalOtherPrintCount > 0 ? $sumTotalOtherPrintCount : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalPhotoCount > 0 ? $sumTotalPhotoCount : '') . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNot = $this->db->query('
            SELECT
                NS.id,
                NS.create_number
            FROM `gaz_nifs_scene` AS NS
            WHERE NS.scene_type_id = 0 ' . $queryStringData . '
            ORDER BY NS.create_number ASC');

            if ($queryNot->num_rows() > 0) {
                $htmlNot = 'Гэмт хэргийн төрөл сонгоогдоогүй үзлэг (' . $queryNot->num_rows() . '): ';
                foreach ($queryNot->result() as $key => $row) {
                    $htmlNot .= '<a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'createNumber=' . $row->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $row->create_number . '</a>';
                }
                $htmlData .= '<div class="table-responsive-inside-description">' . $htmlNot . '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }

        return $htmlData;
    }

    public function getReportWeightData_model($param = array()) {

        $queryStringYearData = $queryStringData = $htmlData = $isAjaxDepartmentUrl = '';

        if ($param['departmentId'] > 0) {

            $queryStringData .= ' AND NS.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $queryStringYearData .= ' AND NS.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NS.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NS.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NS.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NS.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NS.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $query = $this->db->query('
            SELECT 
                NE.expert_id
            FROM `gaz_nifs_expert` AS NE
            INNER JOIN `gaz_nifs_scene` AS NS ON NE.mod_id = NS.mod_id AND NE.cont_id = NS.id
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
            $queryStringData .= ' AND NS.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $queryStringYearData .= ' AND NS.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        $query = $this->db->query('
            SELECT 
                HP.id,
                IF(HP.is_active = 1, CONCAT(\'<strong>\',SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname, \'</strong><br><div style="font-size:90%">\', HPP.title, \', \', HPD.title, \'</div>\'), CONCAT(\'<strong>\', HP.fname, \'</strong>\')) AS full_name,
                NS_YEAR.year_count,
                IF(NS_YEAR.year_count > 0, \'_row-more\', \'\') AS year_count_class,
                NS.date_count,
                IF(NS.date_count > 0, \'_row-more\', \'\') AS date_count_class,
                FINGER_PRINT_TYPE_1.finger_print_type_1_count,
                FINGER_PRINT_TYPE_2.finger_print_type_2_count,
                FINGER_PRINT_TYPE_3.finger_print_type_3_count,
                BOOT_PRINT_TYPE_3.boot_print_type_3_count,
                BOOT_PRINT_TYPE_4.boot_print_type_4_count,
                TRANSPORT_PRINT_TYPE_5.transport_print_type_5_count,
                TRANSPORT_PRINT_TYPE_6.transport_print_type_6_count,
                NS.other_print_count,
                NS.photo_count
            FROM `gaz_hr_people` AS HP
            INNER JOIN `gaz_hr_people_work` AS HPW ON HP.id = HPW.people_id AND HPW.is_currenty = 1
            INNER JOIN `gaz_hr_people_position` AS HPP ON HPP.id = HPW.position_id 
            INNER JOIN `gaz_hr_people_department` AS HPD ON HPD.id = HPW.department_id 
            LEFT JOIN (
                SELECT
                    NE.expert_id,
                    COUNT(NE.expert_id) AS year_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_scene` AS NS ON NE.mod_id = NS.mod_id AND NE.cont_id = NS.id
                WHERE NS.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NE.expert_id
            ) AS NS_YEAR ON NS_YEAR.expert_id = HP.id
            LEFT JOIN (
                SELECT
                    NE.expert_id,
                    COUNT(NE.expert_id) AS date_count,
                    SUM(NS.other_print_count) AS other_print_count,
                    SUM(NS.photo_count) AS photo_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_scene` AS NS ON NE.mod_id = NS.mod_id AND NE.cont_id = NS.id
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) AS NS ON NS.expert_id = HP.id
            LEFT JOIN (
                SELECT
                    NE.expert_id,
                    SUM(NS.finger_count) AS finger_print_type_1_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_scene` AS NS ON NE.mod_id = NS.mod_id AND NE.cont_id = NS.id
                WHERE NS.finger_print_type_id = 1 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) AS FINGER_PRINT_TYPE_1 ON FINGER_PRINT_TYPE_1.expert_id = HP.id
            LEFT JOIN (
                SELECT
                    NE.expert_id,
                    SUM(NS.finger_count) AS finger_print_type_2_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_scene` AS NS ON NE.mod_id = NS.mod_id AND NE.cont_id = NS.id
                WHERE NS.finger_print_type_id = 2 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) AS FINGER_PRINT_TYPE_2 ON FINGER_PRINT_TYPE_2.expert_id = HP.id
            LEFT JOIN (
                SELECT
                    NE.expert_id,
                    SUM(NS.finger_print_count) AS finger_print_type_3_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_scene` AS NS ON NE.mod_id = NS.mod_id AND NE.cont_id = NS.id
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) AS FINGER_PRINT_TYPE_3 ON FINGER_PRINT_TYPE_3.expert_id = HP.id
            LEFT JOIN (
                SELECT
                    NE.expert_id,
                    SUM(NS.boot_print_count) AS boot_print_type_3_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_scene` AS NS ON NE.mod_id = NS.mod_id AND NE.cont_id = NS.id
                WHERE NS.boot_print_type_id = 3 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) AS BOOT_PRINT_TYPE_3 ON BOOT_PRINT_TYPE_3.expert_id = HP.id
            LEFT JOIN (
                SELECT
                    NE.expert_id,
                    SUM(NS.boot_print_count) AS boot_print_type_4_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_scene` AS NS ON NE.mod_id = NS.mod_id AND NE.cont_id = NS.id
                WHERE NS.boot_print_type_id = 4 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) AS BOOT_PRINT_TYPE_4 ON BOOT_PRINT_TYPE_4.expert_id = HP.id
            LEFT JOIN (
                SELECT
                    NE.expert_id,
                    SUM(NS.transport_print_count) AS transport_print_type_5_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_scene` AS NS ON NE.mod_id = NS.mod_id AND NE.cont_id = NS.id
                WHERE NS.transport_print_type_id = 5 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) AS TRANSPORT_PRINT_TYPE_5 ON TRANSPORT_PRINT_TYPE_5.expert_id = HP.id
            LEFT JOIN (
                SELECT
                    NE.expert_id,
                    SUM(NS.transport_print_count) AS transport_print_type_6_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_scene` AS NS ON NE.mod_id = NS.mod_id AND NE.cont_id = NS.id
                WHERE NS.transport_print_type_id = 6 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) AS TRANSPORT_PRINT_TYPE_6 ON TRANSPORT_PRINT_TYPE_6.expert_id = HP.id
            WHERE HP.id IN(' . $inPeopleId . ')
            ORDER BY HP.fname ASC');

        if ($query->num_rows() > 0) {

            $i = $sumTotalYearCount = $sumTotalDateCount = $sumTotalFingerPrintType1Count = $sumTotalFingerPrintType2Count = $sumTotalFingerPrintType3Count = $sumtTotalBootPrintType3Count = $sumtTotalBootPrintType4Count = $sumTotalTransportPrintType5Count = $sumTotalTransportPrintType6Count = $sumTotalOtherPrintCount = $sumTotalPhotoCount = 0;

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="3" style="width:30px;">#</th>';
            $htmlData .= '<th rowspan="3">Шинжээч</th>';
            $htmlData .= '<th rowspan="3" style="width:80px;" class="text-center">' . $this->session->adminCloseYear . ' он</th>';
            $htmlData .= '<th colspan="10" style="width:80px;" class="text-center">' . $param['inDate'] . ' - ' . $param['outDate'] . '</th>';
            $htmlData .= '</tr>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Нийт</th>';
            $htmlData .= '<th colspan="3" style="width:160px;" class="text-center">Гарын мөр</th>';
            $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Гутлын мөр</th>';
            $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Тээврийн хэрэгслийн мөр</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Бусад</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Гэрэл зураг</th>';
            $htmlData .= '</tr>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:80px;" class="text-center">Нунтаг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Гэрэл зураг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Дардас</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Гэрэл зураг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Гипс</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Гэрэл зураг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Гипс</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            $i = 0;
            foreach ($query->result() as $key => $row) {

                $sumTotalYearCount = $sumTotalYearCount + $row->year_count;
                $sumTotalDateCount = $sumTotalDateCount + $row->date_count;
                $sumTotalFingerPrintType1Count = $sumTotalFingerPrintType1Count + $row->finger_print_type_1_count;
                $sumTotalFingerPrintType2Count = $sumTotalFingerPrintType2Count + $row->finger_print_type_2_count;
                $sumTotalFingerPrintType3Count = $sumTotalFingerPrintType3Count + $row->finger_print_type_3_count;
                $sumtTotalBootPrintType3Count = $sumtTotalBootPrintType3Count + $row->boot_print_type_3_count;
                $sumtTotalBootPrintType4Count = $sumtTotalBootPrintType4Count + $row->boot_print_type_4_count;
                $sumTotalTransportPrintType5Count = $sumTotalTransportPrintType5Count + $row->transport_print_type_5_count;
                $sumTotalTransportPrintType6Count = $sumTotalTransportPrintType6Count + $row->transport_print_type_6_count;
                $sumTotalOtherPrintCount = $sumTotalOtherPrintCount + $row->other_print_count;
                $sumTotalPhotoCount = $sumTotalPhotoCount + $row->photo_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->full_name . '</td>';
                $htmlData .= '<td class="text-center ' . $row->year_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '\'});">' . $row->year_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->date_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->date_count . '</a></td>';
                $htmlData .= '<td class="text-center">' . ($row->finger_print_type_1_count > 0 ? $row->finger_print_type_1_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->finger_print_type_2_count > 0 ? $row->finger_print_type_2_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->finger_print_type_3_count > 0 ? $row->finger_print_type_3_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->boot_print_type_3_count > 0 ? $row->boot_print_type_3_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->boot_print_type_4_count > 0 ? $row->boot_print_type_4_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->transport_print_type_5_count > 0 ? $row->transport_print_type_5_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->transport_print_type_6_count > 0 ? $row->transport_print_type_6_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->other_print_count > 0 ? $row->other_print_count : '') . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->photo_count > 0 ? $row->photo_count : '') . '</td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';

            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td>Нийт</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalYearCount > 0 ? $sumTotalYearCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalDateCount > 0 ? $sumTotalDateCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalFingerPrintType1Count > 0 ? $sumTotalFingerPrintType1Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalFingerPrintType2Count > 0 ? $sumTotalFingerPrintType2Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalFingerPrintType3Count > 0 ? $sumTotalFingerPrintType3Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumtTotalBootPrintType3Count > 0 ? $sumtTotalBootPrintType3Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumtTotalBootPrintType4Count > 0 ? $sumtTotalBootPrintType4Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalTransportPrintType5Count > 0 ? $sumTotalTransportPrintType5Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalTransportPrintType6Count > 0 ? $sumTotalTransportPrintType6Count : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalOtherPrintCount > 0 ? $sumTotalOtherPrintCount : '') . '</td>';
            $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalPhotoCount > 0 ? $sumTotalPhotoCount : '') . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';
            
            $queryNotExpert = $this->db->query('
            SELECT 
                NE.expert_id,
                NS.create_number
            FROM `gaz_nifs_expert` AS NE
            INNER JOIN `gaz_nifs_scene` AS NS ON NE.mod_id = NS.mod_id AND NE.cont_id = NS.id 
            WHERE NE.expert_id = 0 ' . $queryStringData . '
            ORDER BY NS.create_number ASC');

            if ($queryNotExpert->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Шинжээч сонгоогүй шинжилгээ (' . $queryNotExpert->num_rows() . '): ';

                foreach ($queryNotExpert->result() as $rowNotExpert) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'createNumber=' . $rowNotExpert->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNotExpert->create_number . '</a>';
                }
                $htmlData .= '</div>';
            } else {
                $htmlData .= '<br>';
            }
            
        }


        $query = $this->db->query('
            SELECT 
                HP.id,
                IF(HP.is_active = 1, CONCAT(\'<strong>\',SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname, \'</strong><br><div style="font-size:90%">\', HPP.title, \', \', HPD.title, \'</div>\'), CONCAT(\'<strong>\', HP.fname, \'</strong>\')) AS full_name,
                NS_YEAR.year_count,
                IF(NS_YEAR.year_count > 0, \'_row-more\', \'\') AS year_count_class,
                NS.date_count,
                IF(NS.date_count > 0, \'_row-more\', \'\') AS date_count_class,
                SCENE_TYPE_1.scene_type_1_count,
                IF(SCENE_TYPE_1.scene_type_1_count > 0, \'_row-more\', \'\') AS scene_type_1_count_class,
                SCENE_TYPE_2.scene_type_2_count,
                IF(SCENE_TYPE_2.scene_type_2_count > 0, \'_row-more\', \'\') AS scene_type_2_count_class,
                SCENE_TYPE_3.scene_type_3_count,
                IF(SCENE_TYPE_3.scene_type_3_count > 0, \'_row-more\', \'\') AS scene_type_3_count_class,
                SCENE_TYPE_4.scene_type_4_count,
                IF(SCENE_TYPE_4.scene_type_4_count > 0, \'_row-more\', \'\') AS scene_type_4_count_class,
                SCENE_TYPE_5.scene_type_5_count,
                IF(SCENE_TYPE_5.scene_type_5_count > 0, \'_row-more\', \'\') AS scene_type_5_count_class,
                SCENE_TYPE_6.scene_type_6_count,
                IF(SCENE_TYPE_6.scene_type_6_count > 0, \'_row-more\', \'\') AS scene_type_6_count_class,
                SCENE_TYPE_7.scene_type_7_count,
                IF(SCENE_TYPE_7.scene_type_7_count > 0, \'_row-more\', \'\') AS scene_type_7_count_class
            FROM `gaz_hr_people` AS HP
            INNER JOIN `gaz_hr_people_work` AS HPW ON HP.id = HPW.people_id AND HPW.is_currenty = 1
            INNER JOIN `gaz_hr_people_position` AS HPP ON HPP.id = HPW.position_id 
            INNER JOIN `gaz_hr_people_department` AS HPD ON HPD.id = HPW.department_id 
            LEFT JOIN (
                SELECT
                    NE.expert_id,
                    COUNT(NE.expert_id) AS year_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_scene` AS NS ON NE.mod_id = NS.mod_id AND NE.cont_id = NS.id
                WHERE NS.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NE.expert_id
            ) AS NS_YEAR ON NS_YEAR.expert_id = HP.id
            LEFT JOIN (
                SELECT
                    NE.expert_id,
                    COUNT(NE.expert_id) AS date_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_scene` AS NS ON NE.mod_id = NS.mod_id AND NE.cont_id = NS.id
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) AS NS ON NS.expert_id = HP.id
            LEFT JOIN (
                SELECT
                    NE.expert_id,
                    COUNT(NE.expert_id) AS scene_type_1_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_scene` AS NS ON NE.mod_id = NS.mod_id AND NE.cont_id = NS.id
                WHERE NS.scene_type_id = 1 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) AS SCENE_TYPE_1 ON SCENE_TYPE_1.expert_id = HP.id
            LEFT JOIN (
                SELECT
                    NE.expert_id,
                    COUNT(NE.expert_id) AS scene_type_2_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_scene` AS NS ON NE.mod_id = NS.mod_id AND NE.cont_id = NS.id
                WHERE NS.scene_type_id = 2 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) AS SCENE_TYPE_2 ON SCENE_TYPE_2.expert_id = HP.id
            LEFT JOIN (
                SELECT
                    NE.expert_id,
                    COUNT(NE.expert_id) AS scene_type_3_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_scene` AS NS ON NE.mod_id = NS.mod_id AND NE.cont_id = NS.id
                WHERE NS.scene_type_id = 3 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) AS SCENE_TYPE_3 ON SCENE_TYPE_3.expert_id = HP.id
            LEFT JOIN (
                SELECT
                    NE.expert_id,
                    COUNT(NE.expert_id) AS scene_type_4_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_scene` AS NS ON NE.mod_id = NS.mod_id AND NE.cont_id = NS.id
                WHERE NS.scene_type_id = 4 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) AS SCENE_TYPE_4 ON SCENE_TYPE_4.expert_id = HP.id
            LEFT JOIN (
                SELECT
                    NE.expert_id,
                    COUNT(NE.expert_id) AS scene_type_5_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_scene` AS NS ON NE.mod_id = NS.mod_id AND NE.cont_id = NS.id
                WHERE NS.scene_type_id = 5 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) AS SCENE_TYPE_5 ON SCENE_TYPE_5.expert_id = HP.id
            LEFT JOIN (
                SELECT
                    NE.expert_id,
                    COUNT(NE.expert_id) AS scene_type_6_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_scene` AS NS ON NE.mod_id = NS.mod_id AND NE.cont_id = NS.id
                WHERE NS.scene_type_id = 6 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) AS SCENE_TYPE_6 ON SCENE_TYPE_6.expert_id = HP.id
            LEFT JOIN (
                SELECT
                    NE.expert_id,
                    COUNT(NE.expert_id) AS scene_type_7_count
                FROM `gaz_nifs_expert` AS NE 
                INNER JOIN `gaz_nifs_scene` AS NS ON NE.mod_id = NS.mod_id AND NE.cont_id = NS.id
                WHERE NS.scene_type_id = 7 ' . $queryStringData . '
                GROUP BY NE.expert_id
            ) AS SCENE_TYPE_7 ON SCENE_TYPE_7.expert_id = HP.id
            WHERE HP.id IN(' . $inPeopleId . ')
            ORDER BY HP.fname ASC');

        if ($query->num_rows() > 0) {

            $htmlData .= '<br><br>';
            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="2" style="width:30px;">#</th>';
            $htmlData .= '<th rowspan="2">Шинжээч</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">' . $this->session->adminCloseYear . ' он</th>';
            $htmlData .= '<th colspan="8" style="width:80px;" class="text-center">' . $param['inDate'] . ' - ' . $param['outDate'] . '</th>';
            $htmlData .= '</tr>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:80px;" class="text-center">Нийт</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хүн амь, учрал</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хулгай</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Дээрэм</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Зам тээврийн осол</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хүчин</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">ИЭЧЭМЭ, танхайь булаалт</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Бусад</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            $i = $sumTotalYearCount = $sumTotalDateCount = $sumTotalSceneType1Count = $sumTotalSceneType2Count = $sumTotalSceneType3Count = $sumTotalSceneType4Count = $sumTotalSceneType5Count = $sumTotalSceneType6Count = $sumTotalSceneType7Count = 0;
            foreach ($query->result() as $key => $row) {
                
                $sumTotalYearCount = $sumTotalYearCount + $row->year_count;
                $sumTotalDateCount = $sumTotalDateCount + $row->date_count;
                $sumTotalSceneType1Count = $sumTotalSceneType1Count + $row->scene_type_1_count;
                $sumTotalSceneType2Count = $sumTotalSceneType2Count + $row->scene_type_2_count;
                $sumTotalSceneType3Count = $sumTotalSceneType3Count + $row->scene_type_3_count;
                $sumTotalSceneType4Count = $sumTotalSceneType4Count + $row->scene_type_4_count;
                $sumTotalSceneType5Count = $sumTotalSceneType5Count + $row->scene_type_5_count;
                $sumTotalSceneType6Count = $sumTotalSceneType6Count + $row->scene_type_6_count;
                $sumTotalSceneType7Count = $sumTotalSceneType7Count + $row->scene_type_7_count;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->full_name . '</td>';
                $htmlData .= '<td class="text-center ' . $row->year_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '\'});">' . $row->year_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->date_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->date_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->scene_type_1_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=' . $row->id . '&sceneTypeId=1&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_1_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->scene_type_2_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=' . $row->id . '&sceneTypeId=2&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_2_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->scene_type_3_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=' . $row->id . '&sceneTypeId=3&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_3_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->scene_type_4_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=' . $row->id . '&sceneTypeId=4&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_4_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->scene_type_5_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=' . $row->id . '&sceneTypeId=5&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_5_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->scene_type_6_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=' . $row->id . '&sceneTypeId=6&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_6_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->scene_type_7_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=' . $row->id . '&sceneTypeId=7&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_7_count . '</a></td>';
                $htmlData .= '</tr>';
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';

            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td>Нийт</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalYearCount > 0 ? $sumTotalYearCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalDateCount > 0 ? $sumTotalDateCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=all&sceneTypeId=1&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSceneType1Count > 0 ? $sumTotalSceneType1Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=all&sceneTypeId=2&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSceneType2Count > 0 ? $sumTotalSceneType2Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=all&sceneTypeId=3&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSceneType3Count > 0 ? $sumTotalSceneType3Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=all&sceneTypeId=4&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSceneType4Count > 0 ? $sumTotalSceneType4Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=all&sceneTypeId=5&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSceneType5Count > 0 ? $sumTotalSceneType5Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=all&sceneTypeId=6&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSceneType6Count > 0 ? $sumTotalSceneType6Count : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'expertId=all&sceneTypeId=7&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSceneType7Count > 0 ? $sumTotalSceneType7Count : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $queryNotExpert = $this->db->query('
            SELECT 
                NS.create_number
            FROM `gaz_nifs_scene` AS NS
            WHERE NS.scene_type_id = 0 ' . $queryStringData . '
            ORDER BY NS.create_number ASC');

            if ($queryNotExpert->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Шинжээч сонгоогүй шинжилгээ (' . $queryNotExpert->num_rows() . '): ';

                foreach ($queryNotExpert->result() as $rowNotExpert) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'createNumber=' . $rowNotExpert->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNotExpert->create_number . '</a>';
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
            $queryStringData .= ' AND NS.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $queryStringYearData .= ' AND NS.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NS.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NS.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NS.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NS.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NS.year = \'' . $this->session->adminCloseYear . '\'';
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
                    NS.partner_id,
                    COUNT(NS.partner_id) AS year_partner_count
                FROM `gaz_nifs_scene` AS NS
                WHERE NS.partner_id != 0 AND NS.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NS.partner_id
            ) AS YEAR_PARTNER_COUNT ON YEAR_PARTNER_COUNT.partner_id = P.id
            LEFT JOIN (
                SELECT
                    NS.partner_id,
                    COUNT(NS.partner_id) AS date_partner_count
                FROM `gaz_nifs_scene` AS NS
                WHERE NS.partner_id != 0 ' . $queryStringData . '
                GROUP BY NS.partner_id
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
                $htmlData .= '<td class="text-center ' . $row->year_partner_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . $row->year_partner_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->date_partner_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->date_partner_count . '</a></td>';
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
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'partnerId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalYearPartnerCount > 0 ? $sumTotalYearPartnerCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'partnerId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalDatePartnerCount > 0 ? $sumTotalDatePartnerCount : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';


            $queryNotPartner = $this->db->query('
            SELECT 
                NS.create_number
            FROM `gaz_nifs_scene` AS NS 
            WHERE NS.partner_id = 0 ' . $queryStringData . '
            ORDER BY NS.create_number ASC');

            if ($queryNotPartner->num_rows() > 0) {
                $htmlData .= '<div class="table-responsive-inside-description">Шинжээч сонгоогүй шинжилгээ (' . $queryNotPartner->num_rows() . '): ';

                foreach ($queryNotPartner->result() as $rowNotPartner) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'createNumber=' . $rowNotPartner->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNotPartner->create_number . '</a>';
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

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NS.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NS.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NS.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NS.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NS.year = \'' . $this->session->adminCloseYear . '\'';
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
                    NS.partner_id,
                    COUNT(NS.partner_id) AS year_partner_count
                FROM `gaz_nifs_scene` AS NS
                WHERE NS.partner_id != 0 AND NS.year = \'' . $this->session->adminCloseYear . '\' ' . $queryStringYearData . '
                GROUP BY NS.partner_id
            ) AS YEAR_PARTNER_COUNT ON YEAR_PARTNER_COUNT.partner_id = P.id
            LEFT JOIN (
                SELECT
                    NS.partner_id,
                    COUNT(NS.partner_id) AS date_partner_count
                FROM `gaz_nifs_scene` AS NS
                WHERE NS.partner_id != 0 ' . $queryStringData . '
                GROUP BY NS.partner_id
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
                $htmlData .= '<td class="text-center ' . $row->year_partner_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '\'});">' . $row->year_partner_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->date_partner_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'partnerId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->date_partner_count . '</a></td>';
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

}
