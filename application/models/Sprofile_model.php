<?php

class Sprofile_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function information_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT
                HP.id,
                HPW.department_id,
                HPD.title AS department_title,
                HP.position_id,
                HPP.title AS position_title,
                HP.rank_id,
                HPR.title AS rank_title,
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
                HP.param,
                HP.order_num,
                HP.user_partner_id
            FROM `gaz_hr_people` AS HP
            LEFT JOIN `gaz_hr_people_work` AS HPW ON HP.id = HPW.people_id AND HPW.is_currenty = 1
            LEFT JOIN `gaz_hr_people_department` AS HPD ON HPW.department_id = HPD.id
            LEFT JOIN `gaz_hr_people_position` AS HPP ON HPW.position_id = HPP.id
            LEFT JOIN `gaz_hr_people_rank` AS HPR ON HPW.rank_id = HPR.id
            WHERE HP.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

    public function menu_model($param = array()) {

        $menuData = json_decode(json_encode(array(
            array('id' => 1, 'icon' => 'icon-vcard', 'path' => 'about', 'title' => 'Хувийн мэдээлэл'),
            array('id' => 2, 'icon' => 'icon-graduation2', 'path' => 'education', 'title' => 'Боловсрол'),
            array('id' => 3, 'icon' => 'icon-compass4', 'path' => 'profission', 'title' => 'Мэргэшил'),
            array('id' => 4, 'icon' => 'icon-medal2', 'path' => 'award', 'title' => 'Шагнал'),
            array('id' => 5, 'icon' => 'icon-stack4', 'path' => 'report', 'title' => 'Тайлан'),
            array('id' => 6, 'icon' => 'icon-unlink', 'path' => 'conviction', 'title' => 'Шийтгэл'),
            array('id' => 7, 'icon' => 'icon-cogs', 'path' => 'password', 'title' => 'Нууц үг солих'))));

        $menuSystemData = json_decode(json_encode(array(
            array('id' => 1, 'icon' => 'icon-stats-growth', 'path' => 'forensics', 'title' => 'Миний шинжилгээ'))));

        $html = '<ul class="list-group">';
        $html .= '<li class="list-group-item pb-3 pt-3">
                            <img src="' . UPLOADS_USER_PATH . $param['userData']->pic . '" class="rounded-circle mr-2" height="34" alt="" style="float: left;">
                                <div style="float: left; display: inline;">
                                    <span>' . $param['userData']->full_name . '</span>
                                </div>
                        </li>';
        foreach ($menuData as $key => $row) {

            $html .= '<li class="list-group-item ' . ($row->path == $this->uri->segment(2) ? 'active' : '') . '"><a href="sprofile/' . $row->path . '"><i class="' . $row->icon . ' mr-2"></i> ' . $row->title . '</a></li>';
        }
        $html .= '</ul>';

        $html .= '<ul class="list-group">';

        foreach ($menuSystemData as $key => $row) {

            $html .= '<li class="list-group-item ' . ($row->path == $this->uri->segment(2) ? 'active' : '') . '"><a href="sprofile/' . $row->path . '"><i class="' . $row->icon . ' mr-2"></i> ' . $row->title . '</a></li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public function updateUserData_model($param = array()) {
        $this->data = array(
            'email' => $this->input->post('email'),
            'lname' => $this->input->post('lname'),
            'fname' => $this->input->post('fname'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'sex' => $this->input->post('sex'),
            'birthday' => $this->input->post('birthday'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId);

        $this->db->where('id', $this->session->adminUserId);

        if ($this->db->update($this->db->dbprefix . 'user', $this->data)) {

            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function updatePhoto_model($param = array()) {
        $this->data = array(
            'pic' => ($this->input->post('pic') ? $this->input->post('pic') : $this->input->post('oldPic')),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId);

        $this->db->where('id', $this->session->adminUserId);

        if ($this->db->update($this->db->dbprefix . 'user', $this->data)) {

            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function nifsCrimeListsCount_model($param = array()) {

        $query = $queryString = $getString = '';

        if ($param['catId'] != 0) {

            $queryString .= ' AND NC.cat_id = ' . $param['catId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NC.in_date) AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NC.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NC.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NC.out_date)';
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

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeDate'])) . '\' >= DATE(NC.close_date)';
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

        $query = $this->db->query('
                SELECT 
                    NE.expert_id 
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_crime` AS NC ON NE.cont_id = NC.id AND NE.mod_id = NC.mod_id
                WHERE NE.expert_id = ' . $param['expertId'] . ' AND NE.mod_id = 33' . $queryString);

        return $query->num_rows();
    }

    public function nifsCrimeLists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $data = array();
        $query = $queryString = $queryStringJoinDepartment = $queryStringDepartment = $getString = '';

        if ($param['catId'] != 0) {

            $queryString .= ' AND NC.cat_id = ' . $param['catId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NC.in_date) AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NC.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NC.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NC.out_date)';
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

            $queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeDate'])) . '\' >= DATE(NC.close_date)';
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
            WHERE NE.expert_id = ' . $param['expertId'] . ' AND NE.mod_id = 33' . $queryString . '
            ORDER BY NC.create_number DESC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);

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
        return array('data' => $data, 'search' => self::nifsCrimeSearchKeywordView_model());
    }

    public function nifsCrimeSearchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
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
            $this->cat = $this->category->getData_model(array('selectedId' => $this->input->get('catId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->cat->title . '</span>';
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
            $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->get('partnerId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->partnerData->title . '</span>';
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

//        if ($this->input->get('expertId')) {
//            $this->expertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->get('expertId')));
//            $this->string .= '<span class="label label-default label-rounded">' . mb_substr($this->expertData->lname, 0, 1, 'UTF-8') . '.' . $this->expertData->fname . '</span>';
//            $this->string .= form_hidden('expertId', $this->input->get('expertId'));
//            $this->showResetBtn = TRUE;
//        }

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

        if ($this->showResetBtn) {
            //' . Scrime::$path . $param['path'] . '/' . $param['modId'] . '

            if ($this->input->get('isReportMenu') == 1) {

                $this->string .= ' <a href="javascript:;" onclick="_reportItem({reportMenuId: ' . $this->input->get('reportMenuId') . ', reportModId: ' . $this->input->get('reportModId') . '});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            } else {

                $this->string .= ' <a href="javascript:;" onclick="_initMyCrime({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            }
        }

        return ($this->showResetBtn ? '<td class="_search-result-td"><div class="datagrid-btn-separator"></div></td><td class="_search-result-td"><form action="javascript:;" id="form-nifs-crime"> Хайлтын үр дүн: ' . $this->string . '</form></td>' : '');
    }

    public function nifsExtraListsCount_model($param = array()) {

        $this->queryString = $this->getString = '';

        if ($param['createNumber'] != 0) {
            $this->queryString .= ' AND NEX.create_number = ' . $param['createNumber'];
        }

        if ($param['protocolNumber'] != 0) {
            $this->queryString .= ' AND NEX.protocol_number = ' . $param['protocolNumber'];
        }

        if ($param['researchTypeId'] != 0) {
            $this->queryString .= ' AND NEX.research_type_id = ' . $param['researchTypeId'];
        }

        if ($param['isMixx'] == 1) {
            $this->queryString .= ' AND NEX.is_mixx = 1';
        }

        if ($param['isMixx'] == 2) {
            $this->queryString .= ' AND NEX.is_mixx = 0';
        }

        if ($param['motiveId'] != 0) {
            $this->queryString .= ' AND NEX.motive_id = ' . $param['motiveId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NEX.in_date) AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NEX.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NEX.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NEX.out_date)';
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NEX.protocol_in_date) AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NEX.protocol_out_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NEX.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NEX.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NEX.close_date) AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NEX.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NEX.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NEX.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $this->queryString .= ' AND NEX.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND NEX.cat_id = ' . $param['catId'];
        }

        /*
         * ӨМНӨХ ХЭРГИЙН ДУГААРЫГ ХАЙХ ХЭСЭГ ЭНД ОРЖ ИРНЭ. ГЭХДНЭЭ ӨМНӨХ ХЭРЭГТЭЙ ХОЛБООТОЙ ХЭСГИЙГ БҮХ БҮРТГЭЛИЙН ХУВЬД БАГЦААР НЬ НЭГ АЯТАЙХАН ШИЙДЭЛ ОЛЖ ХИЙХ ХЭРЭГТЭЙ БАЙГАА
         */

        if ($param['isAgeInfinitive'] == 1) {

            $this->queryString .= ' AND NEX.is_age_infinitive = ' . $param['isAgeInfinitive'];
        } else {

            if ($param['age1'] != '' and $param['age2'] != '') {

                $this->queryString .= ' AND (NEX.age >= ' . floatval($param['age1']) . ' AND NEX.age <= ' . floatval($param['age2']) . ') AND NEX.is_age_infinitive = 0';
            } else if ($param['age1'] != '' and $param['age2'] == '') {

                $this->queryString .= ' AND \'' . floatval($param['age1']) . '\' >= NEX.age AND K.is_age_infinitive = 0';
            } elseif ($param['age1'] == '' and $param['age2'] != '') {

                $this->queryString .= ' AND \'' . floatval($param['age2']) . '\' <= NEX.age AND K.is_age_infinitive = 0';
            }
        }

        if ($param['questionId'] != 0) {
            $this->queryString .= ' AND NEX.question_id = ' . $param['questionId'];
        }

        if ($param['partnerId'] != 0) {
            $this->queryString .= ' AND NEX.partner_id IN(' . $this->partner->getChildPartners_model(array($param['partnerId'])) . ')';
        }

        if ($param['expertDoctorId'] != 0) {
            $this->queryString .= ' AND NEX.expert_doctor_id = ' . $param['expertDoctorId'];
        }

        if ($param['protocolNumber'] != 0) {
            $this->queryString .= ' AND NEX.protocol_number = ' . $param['protocolNumber'];
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $this->queryString .= ' AND LOWER(NEX.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $this->queryString .= ' AND LOWER(NEX.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Албан тушаалтны нэр
                        $this->queryString .= ' AND LOWER(NEX.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Тайлбар
                        $this->queryString .= ' AND LOWER(NEX.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Регистрийн дугаар
                        $this->queryString .= ' AND LOWER(NEX.register) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 6: {   //Хэргийн утга
                        $this->queryString .= ' AND LOWER(NEX.crime_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 7: {   //Нэмэлт асуулт
                        $this->queryString .= ' AND LOWER(NEX.question) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 8: {   //Дүгнэлтийн утга
                        $this->queryString .= ' AND LOWER(NEX.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $this->queryString .= ' AND (
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
            $this->queryString .= ' AND NEX.solution_id = ' . $param['solutionId'];
        }

        if ($param['weight'] != 0) {
            $this->queryString .= ' AND NEX.weight = ' . $param['weight'];
        }

        if ($param['typeId'] != 0) {
            $this->queryString .= ' AND NEX.type_id = ' . $param['typeId'];
        }

        if ($param['closeTypeId'] > 0) {

            $this->queryString .= ' AND NEX.close_type_id = ' . $param['closeTypeId'];
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $this->queryString .= ' AND NEX.solution_id != 0 AND NEX.close_date <= NEX.out_date';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $this->queryString .= ' AND NEX.solution_id = 0 AND NOW() <= NEX.out_date';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $this->queryString .= ' AND NEX.solution_id != 0 AND NEX.close_date > NEX.out_date';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $this->queryString .= ' AND NEX.solution_id = 0 AND NOW() > NEX.out_date';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $this->queryString .= ' AND NEX.protocol_out_date < NEX.in_date';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $this->queryString .= ' AND NEX.solution_id = 0';
        }

        if ($param['closeDescription'] != '') {

            $this->queryString .= ' AND (LOWER(NEX.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        $this->query = $this->db->query('
                SELECT 
                    NE.expert_id 
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_extra` AS NEX ON NE.cont_id = NEX.id AND NE.mod_id = NEX.mod_id
                WHERE NE.expert_id = ' . $param['expertId'] . $this->queryString);

        return $this->query->num_rows();
    }

    public function nifsExtraLists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $data = array();

        $this->queryString = $this->getString = '';

        if ($param['createNumber'] != 0) {
            $this->queryString .= ' AND NEX.create_number = ' . $param['createNumber'];
            $this->getString .= form_hidden('createNumber', $param['createNumber']);
        }

        if ($param['protocolNumber'] != 0) {
            $this->queryString .= ' AND NEX.protocol_number = ' . $param['protocolNumber'];
            $this->getString .= form_hidden('protocolNumber', $param['protocolNumber']);
        }

        if ($param['researchTypeId'] != 0) {
            $this->queryString .= ' AND NEX.research_type_id = ' . $param['researchTypeId'];
            $this->getString .= form_hidden('researchTypeId', $param['researchTypeId']);
        }

        if ($param['isMixx'] == 1) {
            $this->queryString .= ' AND NEX.is_mixx = 1';
            $this->getString .= form_hidden('isMixx', $param['isMixx']);
        }

        if ($param['isMixx'] == 2) {
            $this->queryString .= ' AND NEX.is_mixx = 0';
            $this->getString .= form_hidden('isMixx', $param['isMixx']);
        }

        if ($param['motiveId'] != 0) {
            $this->queryString .= ' AND NEX.motive_id = ' . $param['motiveId'];
            $this->getString .= form_hidden('motiveId', $param['motiveId']);
        }

        $this->getString .= form_hidden('inDate', $param['inDate']);
        $this->getString .= form_hidden('outDate', $param['outDate']);

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NEX.in_date) AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NEX.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NEX.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NEX.out_date)';
        }

        $this->getString .= form_hidden('protocolInDate', $param['protocolInDate']);
        $this->getString .= form_hidden('protocolOutDate', $param['protocolOutDate']);

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NEX.protocol_in_date) AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NEX.protocol_out_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NEX.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NEX.protocol_out_date)';
        }

        $this->getString .= form_hidden('closeInDate', $param['closeInDate']);
        $this->getString .= form_hidden('closeOutDate', $param['closeOutDate']);

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $this->queryString .= ' AND \'' . $param['closeInDate'] . '\' <= DATE(NEX.close_date) AND \'' . $param['closeOutDate'] . '\' >= DATE(NEX.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $this->queryString .= ' AND \'' . $param['closeInDate'] . '\' <= DATE(NEX.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $this->queryString .= ' AND \'' . $param['closeOutDate'] . '\' >= DATE(NEX.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $this->queryString .= ' AND NEX.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND NEX.cat_id = ' . $param['catId'];
            $this->getString .= form_hidden('catId', $param['catId']);
        }

        /*
         * ӨМНӨХ ХЭРГИЙН ДУГААРЫГ ХАЙХ ХЭСЭГ ЭНД ОРЖ ИРНЭ. ГЭХДНЭЭ ӨМНӨХ ХЭРЭГТЭЙ ХОЛБООТОЙ ХЭСГИЙГ БҮХ БҮРТГЭЛИЙН ХУВЬД БАГЦААР НЬ НЭГ АЯТАЙХАН ШИЙДЭЛ ОЛЖ ХИЙХ ХЭРЭГТЭЙ БАЙГАА
         */

        if ($param['isAgeInfinitive'] == 1) {

            $this->queryString .= ' AND NEX.is_age_infinitive = ' . $param['isAgeInfinitive'];
            $this->getString .= form_hidden('isAgeInfinitive', $param['isAgeInfinitive']);
        } else {

            $this->getString .= form_hidden('age1', $param['age1']);
            $this->getString .= form_hidden('age2', $param['age2']);

            if ($param['age1'] != '' and $param['age2'] != '') {

                $this->queryString .= ' AND (NEX.age >= ' . floatval($param['age1']) . ' AND NEX.age <= ' . floatval($param['age2']) . ') AND NEX.is_age_infinitive = 0';
            } else if ($param['age1'] != '' and $param['age2'] == '') {

                $this->queryString .= ' AND \'' . floatval($param['age1']) . '\' >= NEX.age AND NEX.is_age_infinitive = 0';
            } else if ($param['age1'] == '' and $param['age2'] != '') {

                $this->queryString .= ' AND \'' . floatval($param['age2']) . '\' <= NEX.age AND NEX.is_age_infinitive = 0';
            }
        }

//        if ($param['sex'] == 0 or $param['sex'] == 1) {
//            $this->queryString .= ' AND K.sex = ' . $param['sex'];
//        }

        if ($param['questionId'] != 0) {
            $this->queryString .= ' AND NEX.question_id = ' . $param['questionId'];
            $this->getString .= form_hidden('questionId', $param['questionId']);
        }

        if ($param['partnerId'] != 0) {
            $this->queryString .= ' AND NEX.partner_id IN(' . $this->partner->getChildPartners_model(array($param['partnerId'])) . ')';
            $this->getString .= form_hidden('partnerId', $param['partnerId']);
        }

        if ($param['expertDoctorId'] != 0) {
            $this->queryString .= ' AND NEX.expert_doctor_id = ' . $param['expertDoctorId'];
            $this->getString .= form_hidden('expertDoctorId', $param['expertDoctorId']);
        }

        if ($param['protocolNumber'] != 0) {
            $this->queryString .= ' AND NEX.protocol_number = ' . $param['protocolNumber'];
            $this->getString .= form_hidden('protocolNumber', $param['protocolNumber']);
        }

        if ($param['keyword'] != '') {

            $this->getString .= form_hidden('keyword', $param['keyword']);
            $this->getString .= form_hidden('keywordTypeId', $param['keywordTypeId']);

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $this->queryString .= ' AND LOWER(NEX.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $this->queryString .= ' AND LOWER(NEX.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Албан тушаалтны нэр
                        $this->queryString .= ' AND LOWER(NEX.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Тайлбар
                        $this->queryString .= ' AND LOWER(NEX.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Регистрийн дугаар
                        $this->queryString .= ' AND LOWER(NEX.register) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 6: {   //Хэргийн утга
                        $this->queryString .= ' AND LOWER(NEX.crime_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 7: {   //Нэмэлт асуулт
                        $this->queryString .= ' AND LOWER(NEX.question) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 8: {   //Дүгнэлтийн утга
                        $this->queryString .= ' AND LOWER(NEX.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $this->queryString .= ' AND (
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
            $this->queryString .= ' AND NEX.solution_id = ' . $param['solutionId'];
            $this->getString .= form_hidden('solutionId', $param['solutionId']);
        }

        if ($param['weight'] != 0) {
            $this->queryString .= ' AND NEX.weight = ' . $param['weight'];
            $this->getString .= form_hidden('weight', $param['weight']);
        }

        if ($param['typeId'] != 0) {
            $this->queryString .= ' AND NEX.type_id = ' . $param['typeId'];
            $this->getString .= form_hidden('typeId', $param['typeId']);
        }

        if ($param['closeTypeId'] != 0) {

            $this->queryString .= ' AND NEX.close_type_id = ' . $param['closeTypeId'];
            $this->getString .= form_hidden('closeTypeId', $param['closeTypeId']);
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $this->queryString .= ' AND NEX.solution_id != 0 AND NEX.close_date <= NEX.out_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $this->queryString .= ' AND NEX.solution_id = 0 AND NOW() <= NEX.out_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $this->queryString .= ' AND NEX.solution_id != 0 AND NEX.close_date > NEX.out_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $this->queryString .= ' AND NEX.solution_id = 0 AND NOW() > NEX.out_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $this->queryString .= ' AND NEX.protocol_out_date < NEX.in_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $this->queryString .= ' AND NEX.solution_id = 0';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        }

        if ($param['closeDescription'] != '') {

            $this->getString .= form_hidden('closeDescription', $param['closeDescription']);
            $this->queryString .= ' AND (LOWER(NEX.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        $this->query = $this->db->query('
                SELECT 
                    NEX.id,
                    NEX.create_number,
                    IF(NEX.is_mixx = 1, "<span class=\'fa fa-users\'></span>", "") AS is_mixx,
                    CONCAT("И:", DATE(NEX.in_date), "<br>", "Д:", DATE(NEX.out_date)) AS in_out_date,
                    CONCAT(NEX.agent_name, \' \', IF(DATE(NEX.protocol_in_date) != \'0000-00-00\', CONCAT("<br>И:", DATE(NEX.protocol_in_date)), \'\'), IF(DATE(NEX.protocol_out_date) != \'0000-00-00\', CONCAT("<br>Д:", DATE(NEX.protocol_out_date), ""), \'\')) AS partner_agent_date,
                    
                    (IF(NEX.lname != \'\', CONCAT(NEX.lname, "-н ", "<strong>", NEX.fname, "</strong>"), NEX.fname)) AS full_name,
                    (CASE 
                        WHEN (NEX.is_age_infinitive = 1) THEN \', нас тодорхойгүй\'
                        WHEN (NEX.is_age_infinitive = 0 AND NEX.age < 1) THEN CONCAT(", ", CONVERT(SUBSTRING(NEX.age, 3, 2),UNSIGNED INTEGER), " сартай")
                        ELSE IF(NEX.is_age_infinitive = 0 AND NEX.age >= 1, CONCAT(", ", CONVERT(NEX.age,UNSIGNED INTEGER), " настай"), "")
                    END) AS age_calc,
                    (IF(NEX.sex = 1, \', эр\', \', эм\')) AS sex,
                    
                    CONCAT(NEX.pre_create_number, \' \', NEX.crime_value) AS pre_create_number_value,
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
                WHERE NE.expert_id = ' . $param['expertId'] . ' AND NE.mod_id = 55 ' . $this->queryString . '
                ORDER BY NEX.create_number DESC
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);


        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $key => $row) {

                $param = json_decode($row->param);

                array_push($data, array(
                    'id' => $row->id,
                    'create_number' => $row->create_number,
                    'in_out_date' => $row->in_out_date,
                    'is_mixx' => $row->is_mixx,
                    'partner_agent_date' => $row->partner_agent_date,
                    'who_is' => $row->full_name . $row->age_calc . $row->sex,
                    'pre_create_number_value' => $row->pre_create_number_value,
                    'object' => $row->object,
                    'question' => $row->question,
                    'expert_type' => $row->expert_type,
                    'expert_status' => $row->expert_status,
                    'weight' => $row->weight,
                    'report' => ($row->solution_id > 0 ? $param->solution . '(' . $row->close_date . ')<br>' . $row->close_description : ''),
                    'description' => $row->description,
                    'row_status' => $row->row_status
                ));
            }
        } else {
            array_push($data, array(
                'id' => 0,
                'create_number' => '',
                'in_out_date' => '',
                'is_mixx' => '',
                'partner_agent_date' => '',
                'who_is' => '',
                'pre_create_number_value' => '',
                'object' => '',
                'question' => '',
                'expert_type' => '',
                'expert_status' => '',
                'weight' => '',
                'report' => '',
                'description' => '',
                'row_status' => '',
                'config' => ''
            ));
        }
        return array('data' => $data, 'search' => self::nifsExtraSearchKeywordView_model());
    }

    public function nifsExtraSearchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
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
            $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->get('partnerId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->partnerData->title . '</span>';
            $this->string .= form_hidden('partnerId', $this->input->get('partnerId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('expertDoctorId')) {
            $this->expertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->get('expertDoctorId')));
            $this->string .= '<span class="label label-default label-rounded">Шинжээч эмч: ' . mb_substr($this->expertData->lname, 0, 1, 'UTF-8') . '.' . $this->expertData->fname . '</span>';
            $this->string .= form_hidden('expertDoctorId', $this->input->get('expertDoctorId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('solutionId')) {
            $this->nifsSolution = $this->nifsSolution->getData_model(array('selectedId' => $this->input->get('solutionId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsSolution->title . '</span>';
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
            $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $this->input->get('motiveId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsMotiveData->title . '</span>';
            $this->string .= form_hidden('motiveId', $this->input->get('motiveId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('catId')) {
            $this->cat = $this->category->getData_model(array('selectedId' => $this->input->get('catId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->cat->title . '</span>';
            $this->string .= form_hidden('catId', $this->input->get('catId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('questionId')) {
            $this->questionData = $this->nifsQuestion->getData_model(array('selectedId' => $this->input->get('questionId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->questionData->title . '</span>';
            $this->string .= form_hidden('questionId', $this->input->get('questionId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('typeId')) {
            $this->nifsCrimeType = $this->nifsCrimeType->getData_model(array('selectedId' => $this->input->get('typeId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCrimeType->title . '</span>';
            $this->string .= form_hidden('typeId', $this->input->get('typeId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('closeTypeId')) {
            $this->nifsCloseType = $this->nifsCloseType->getData_model(array('selectedId' => $this->input->get('closeTypeId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCloseType->title . '</span>';
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

            $this->hrPeopleDepartmentData = $this->hrPeopleDepartment->getData_model(array('selectedId' => $this->input->get('departmentId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->hrPeopleDepartmentData->title . '</span>';
            $this->string .= form_hidden('departmentId', $this->input->get('departmentId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {

            if ($this->input->get('isReportMenu') == 1) {

                $this->string .= ' <a href="javascript:;" onclick="_reportItem({reportMenuId: ' . $this->input->get('reportMenuId') . ', reportModId: ' . $this->input->get('reportModId') . '});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            } else {

                $this->string .= ' <a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            }
        }

        return ($this->showResetBtn ? '<td class="_search-result-td"><div class="datagrid-btn-separator"></div></td><td class="_search-result-td"><form action="javascript:;" id="form-nifs-extra"> Хайлтын үр дүн: ' . $this->string . '</form></td>' : '');
    }

    public function nifsEconomyListsCount_model($param = array()) {

        $this->queryString = $this->getString = '';

        if ($param['createNumber'] != 0) {
            $this->queryString .= ' AND NEC.create_number = ' . $param['createNumber'];
        }

        if ($param['protocolNumber'] != 0) {
            $this->queryString .= ' AND NEC.protocol_number = ' . $param['protocolNumber'];
        }

        if ($param['researchTypeId'] != 0) {
            $this->queryString .= ' AND NEC.research_type_id = ' . $param['researchTypeId'];
        }

        if ($param['isMixx'] == 1) {
            $this->queryString .= ' AND NEC.is_mixx = 1';
        }

        if ($param['isMixx'] == 2) {
            $this->queryString .= ' AND NEC.is_mixx = 0';
        }

        if ($param['motiveId'] != 0) {
            $this->queryString .= ' AND NEC.motive_id = ' . $param['motiveId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NEC.in_date) AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NEC.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NEC.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NEC.out_date)';
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND NEC.cat_id = ' . $param['catId'];
        }

        if ($param['questionId'] != 0) {
            $this->queryString .= ' AND NEC.question_id = ' . $param['questionId'];
        }

        if ($param['partnerId'] != 0) {
            $this->queryString .= ' AND NEC.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
        }

        if ($param['protocolNumber'] != 0) {
            $this->queryString .= ' AND NEC.protocol_number = ' . $param['protocolNumber'];
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NEC.protocol_in_date) AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NEC.protocol_in_date)';
        } elseif ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NEC.protocol_in_date)';
        } elseif ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NEC.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NEC.close_date) AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NEC.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NEC.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NEC.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $this->queryString .= ' AND NEC.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Албан тушаалтны нэр
                        $this->queryString .= ' AND LOWER(NEC.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Тайлбар
                        $this->queryString .= ' AND LOWER(NEC.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Хэргийн утга
                        $this->queryString .= ' AND LOWER(NEC.protocol_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Дүгнэлтийн утга
                        $this->queryString .= ' AND LOWER(NEC.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $this->queryString .= ' 
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
            $this->queryString .= ' AND NEC.solution_id = ' . $param['solutionId'];
        }

        if ($param['weight'] != 0) {
            $this->queryString .= ' AND NEC.weight = ' . $param['weight'];
        }

        if ($param['closeTypeId'] != 0) {
            $this->queryString .= ' AND NEC.close_type_id = ' . $param['closeTypeId'];
        }

        if ($param['typeId'] != 0) {
            $this->queryString .= ' AND NEC.type_id = ' . $param['typeId'];
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $this->queryString .= ' AND NEC.solution_id != 0 AND NEC.close_date < NEC.out_date';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $this->queryString .= ' AND NEC.solution_id = 0 AND NOW() <= NEC.out_date';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $this->queryString .= ' AND NEC.solution_id != 0 AND NEC.close_date > NEC.out_date';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $this->queryString .= ' AND NEC.solution_id = 0 AND DATE(NOW()) > DATE(NEC.out_date)';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $this->queryString .= ' AND NEC.protocol_out_date < NEC.in_date';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $this->queryString .= ' AND NEC.solution_id = 0';
        }

        if ($param['closeDate'] != '') {
            $this->queryString .= ' AND DATE(NEC.close_date) = DATE(\'' . $param['closeDate'] . '\')';
        }

        $this->query = $this->db->query('
                SELECT 
                    NE.expert_id 
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_economy` AS NEC ON NE.cont_id = NEC.id AND NE.mod_id = NEC.mod_id
                WHERE NE.expert_id = ' . $param['expertId'] . $this->queryString . '
                GROUP BY NEC.id');
        return $this->query->num_rows();
    }

    public function nifsEconomyLists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $data = array();

        $this->queryString = $this->getString = '';

        if ($param['createNumber'] != 0) {
            $this->queryString .= ' AND NEC.create_number = ' . $param['createNumber'];
            $this->getString .= form_hidden('createNumber', $param['createNumber']);
        }

        if ($param['protocolNumber'] != 0) {
            $this->queryString .= ' AND NEC.protocol_number = ' . $param['protocolNumber'];
            $this->getString .= form_hidden('protocolNumber', $param['protocolNumber']);
        }

        if ($param['researchTypeId'] != 0) {
            $this->queryString .= ' AND NEC.research_type_id = ' . $param['researchTypeId'];
            $this->getString .= form_hidden('researchTypeId', $param['researchTypeId']);
        }

        if ($param['isMixx'] == 1) {
            $this->queryString .= ' AND NEC.is_mixx = 1';
            $this->getString .= form_hidden('isMixx', $param['isMixx']);
        }

        if ($param['isMixx'] == 2) {
            $this->queryString .= ' AND NEC.is_mixx = 0';
            $this->getString .= form_hidden('isMixx', $param['isMixx']);
        }

        if ($param['motiveId'] != 0) {
            $this->queryString .= ' AND NEC.motive_id = ' . $param['motiveId'];
            $this->getString .= form_hidden('motiveId', $param['motiveId']);
        }

        $this->getString .= form_hidden('inDate', $param['inDate']);
        $this->getString .= form_hidden('outDate', $param['outDate']);

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NEC.in_date) AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NEC.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NEC.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NEC.out_date)';
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND NEC.cat_id = ' . $param['catId'];
            $this->getString .= form_hidden('catId', $param['catId']);
        }

        /*
         * ӨМНӨХ ХЭРГИЙН ДУГААРЫГ ХАЙХ ХЭСЭГ ЭНД ОРЖ ИРНЭ. ГЭХДНЭЭ ӨМНӨХ ХЭРЭГТЭЙ ХОЛБООТОЙ ХЭСГИЙГ БҮХ БҮРТГЭЛИЙН ХУВЬД БАГЦААР НЬ НЭГ АЯТАЙХАН ШИЙДЭЛ ОЛЖ ХИЙХ ХЭРЭГТЭЙ БАЙГАА
         */

        if ($param['questionId'] != 0) {
            $this->queryString .= ' AND NEC.question_id = ' . $param['questionId'];
            $this->getString .= form_hidden('questionId', $param['questionId']);
        }

        if ($param['partnerId'] != 0) {
            $this->queryString .= ' AND NEC.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
            $this->getString .= form_hidden('partnerId', $param['partnerId']);
        }

        if ($param['protocolNumber'] != 0) {
            $this->queryString .= ' AND NEC.protocol_number = ' . $param['protocolNumber'];
            $this->getString .= form_hidden('protocolNumber', $param['protocolNumber']);
        }

        $this->getString .= form_hidden('protocolInDate', $param['protocolInDate']);
        $this->getString .= form_hidden('protocolOutDate', $param['protocolOutDate']);

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NEC.protocol_in_date) AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NEC.protocol_in_date)';
        } elseif ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NEC.protocol_in_date)';
        } elseif ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NEC.protocol_out_date)';
        }

        $this->getString .= form_hidden('closeInDate', $param['closeInDate']);
        $this->getString .= form_hidden('closeOutDate', $param['closeOutDate']);

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NEC.close_date) AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NEC.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NEC.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NEC.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $this->queryString .= ' AND NEC.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['keyword'] != '') {

            $this->getString .= form_hidden('keyword', $param['keyword']);
            $this->getString .= form_hidden('keywordTypeId', $param['keywordTypeId']);

            switch ($param['keywordTypeId']) {
                case 1: {   //Албан тушаалтны нэр
                        $this->queryString .= ' AND LOWER(NEC.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Тайлбар
                        $this->queryString .= ' AND LOWER(NEC.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Хэргийн утга
                        $this->queryString .= ' AND LOWER(NEC.protocol_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Дүгнэлтийн утга
                        $this->queryString .= ' AND LOWER(NEC.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $this->queryString .= ' 
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
            $this->queryString .= ' AND NEC.solution_id = ' . $param['solutionId'];
            $this->getString .= form_hidden('solutionId', $param['solutionId']);
        }

        if ($param['weight'] != 0) {
            $this->queryString .= ' AND NEC.weight = ' . $param['weight'];
            $this->getString .= form_hidden('weight', $param['weight']);
        }

        if ($param['closeTypeId'] != 0) {
            $this->queryString .= ' AND NEC.close_type_id = ' . $param['closeTypeId'];
            $this->getString .= form_hidden('closeTypeId', $param['closeTypeId']);
        }

        if ($param['typeId'] != 0) {
            $this->queryString .= ' AND NEC.type_id = ' . $param['typeId'];
            $this->getString .= form_hidden('typeId', $param['typeId']);
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $this->queryString .= ' AND NEC.solution_id != 0 AND NEC.close_date < NEC.out_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $this->queryString .= ' AND NEC.solution_id = 0 AND NOW() <= NEC.out_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $this->queryString .= ' AND NEC.solution_id != 0 AND NEC.close_date > NEC.out_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $this->queryString .= ' AND NEC.solution_id = 0 AND DATE(NOW()) > DATE(NEC.out_date)';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $this->queryString .= ' AND NEC.protocol_out_date < NEC.in_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $this->queryString .= ' AND NEC.solution_id = 0';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        }

        if ($param['closeDescription'] != '') {

            $this->getString .= form_hidden('closeDescription', $param['closeDescription']);
            $this->queryString .= ' AND (LOWER(NEC.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        $this->query = $this->db->query('
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
                WHERE NE.expert_id = ' . $param['expertId'] . $this->queryString . '
                GROUP BY NEC.id
                ORDER BY NEC.create_number DESC
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);

        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $key => $row) {

                $param = json_decode($row->param);

                array_push($data, array(
                    'id' => $row->id,
                    'create_number' => $row->create_number,
                    'is_mixx' => $row->is_mixx,
                    'in_out_date' => $row->in_out_date,
                    'partner_agent_date' => ('<strong>' . $row->protocol_number . '</strong> <br>' . $param->partner . ' ' . $row->agent_name . ' ' . $row->protocol_in_date . ' ' . $row->protocol_out_date),
                    'protocol_value' => $row->protocol_value,
                    'object' => $row->object,
                    'question' => $row->question,
                    'expert_status' => $row->expert_status,
                    'expert' => $row->expert,
                    'weight' => $row->weight,
                    'report' => ($row->solution_id > 0 ? ($param->solution . ' ' . $row->close_description . ' ' . $row->close_date) : ''),
                    'description' => $row->description,
                    'row_status' => $row->row_status
                ));
            }
        } else {
            array_push($data, array(
                'id' => 0,
                'create_number' => '',
                'is_mixx' => '',
                'in_out_date' => '',
                'partner_agent_date' => '',
                'protocol_value' => '',
                'object' => '',
                'question' => '',
                'expert_status' => '',
                'expert' => '',
                'weight' => '',
                'report' => '',
                'description' => '',
                'row_status' => ''
            ));
        }
        return array('data' => $data, 'search' => self::nifsEconomySearchKeywordView_model());
    }

    public function nifsEconomySearchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
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
            $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->get('partnerId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->partnerData->title . '</span>';
            $this->string .= form_hidden('partnerId', $this->input->get('partnerId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('createExpertId')) {
            $this->createExpertData = $this->nifsExpert->getData_model(array('selectedId' => $this->input->get('createExpertId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->createExpertData->full_name . '</span>';
            $this->string .= form_hidden('createExpertId', $this->input->get('createExpertId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('solutionId')) {
            $this->nifsSolution = $this->nifsSolution->getData_model(array('selectedId' => $this->input->get('solutionId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsSolution->title . '</span>';
            $this->string .= form_hidden('solutionId', $this->input->get('solutionId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->string .= form_hidden('keyword', $this->input->get('keyword'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('motiveId')) {
            $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $this->input->get('motiveId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsMotiveData->title . '</span>';
            $this->string .= form_hidden('motiveId', $this->input->get('motiveId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('catId')) {
            $this->cat = $this->category->getData_model(array('selectedId' => $this->input->get('catId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->cat->title . '</span>';
            $this->string .= form_hidden('catId', $this->input->get('catId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('questionId')) {
            $this->nifsQuestionData = $this->nifsQuestion->getData_model(array('selectedId' => $this->input->get('questionId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsQuestionData->title . '</span>';
            $this->string .= form_hidden('questionId', $this->input->get('questionId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('typeId')) {
            $this->nifsCrimeType = $this->nifsCrimeType->getData_model(array('selectedId' => $this->input->get('typeId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCrimeType->title . '</span>';
            $this->string .= form_hidden('typeId', $this->input->get('typeId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('closeTypeId')) {
            $this->nifsCloseType = $this->nifsCloseType->getData_model(array('selectedId' => $this->input->get('closeTypeId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCloseType->title . '</span>';
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

                $this->string .= ' <a href="javascript:;" onclick="_initMyNifsEconomy({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            }
        }

        return ($this->showResetBtn ? '<td class="_search-result-td"><div class="datagrid-btn-separator"></div></td><td class="_search-result-td"><form action="javascript:;" id="form-nifs-economy"> Хайлтын үр дүн: ' . $this->string . '</form></td>' : '');
    }

    public function nifsFileFolderListsCount_model($param = array()) {

        $this->queryString = $this->getString = '';

        if ($param['createNumber'] != 0) {
            $this->queryString .= ' AND NFF.create_number = ' . $param['createNumber'];
        }

        if ($param['researchTypeId'] != 0) {
            $this->queryString .= ' AND NFF.research_type_id = ' . $param['researchTypeId'];
        }

        if ($param['isMixx'] == 1) {
            $this->queryString .= ' AND NFF.is_mixx = 1';
        }

        if ($param['isMixx'] == 2) {
            $this->queryString .= ' AND NFF.is_mixx = 0';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NFF.in_date) AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NFF.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NFF.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NFF.out_date)';
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NFF.protocol_in_date) AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NFF.protocol_in_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NFF.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NFF.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NFF.close_date) AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NFF.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NFF.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NFF.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $this->queryString .= ' AND NFF.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['partnerId'] != 0) {
            $this->queryString .= ' AND NFF.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
        }

        if ($param['questionId'] != 0) {

            $this->queryString .= ' AND NFF.question_id = ' . $param['questionId'];

            if ($param['questionId'] == 38) {

                if ($param['age1'] != '' and $param['age2'] != '') {

                    $this->queryString .= ' AND (NFF.age >= ' . floatval($param['age1']) . ' AND NFF.age <= ' . floatval($param['age2']) . ')';
                } else if ($param['age1'] != '' and $param['age2'] == '') {

                    $this->queryString .= ' AND \'' . floatval($param['age1']) . '\' <= NFF.age';
                } else if ($param['age1'] == '' and $param['age2'] != '') {

                    $this->queryString .= ' AND \'' . floatval($param['age2']) . '\' >= NFF.age';
                }
            }
        }

        if ($param['preCreateNumber'] != 0) {
            $this->queryString .= ' AND NFF.pre_create_number = ' . $param['preCreateNumber'];
        }

        if ($param['preExpertId'] != 0) {
            $this->queryString .= ' AND NFF.pre_expert_id = ' . $param['preExpertId'];
        }

        if ($param['motiveId'] != 0) {
            $this->queryString .= ' AND NFF.motive_id = ' . $param['motiveId'];
        }

        if ($param['solutionId'] != 0) {
            $this->queryString .= ' AND NFF.solution_id = ' . $param['solutionId'];
        }

        if ($param['closeTypeId'] != 0) {
            $this->queryString .= ' AND NFF.close_type_id = ' . $param['closeTypeId'];
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND NFF.cat_id = ' . $param['catId'];
        }

        if ($param['seniorExpertId'] != 0) {
            $this->queryString .= ' AND NFF.senior_expert_id = ' . $param['seniorExpertId'];
        }

        if ($param['createExpertId'] != 0) {
            $this->queryString .= ' AND NFF.create_expert_id = ' . $param['createExpertId'];
        }

        if ($param['weight'] != 0) {
            $this->queryString .= ' AND NFF.weight = ' . $param['weight'];
        }

        if ($param['protocolNumber'] != 0) {
            $this->queryString .= ' AND NFF.protocol_number = ' . $param['protocolNumber'];
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $this->queryString .= ' AND LOWER(NFF.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $this->queryString .= ' AND LOWER(NFF.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Албан тушаалтны нэр
                        $this->queryString .= ' AND LOWER(NEX.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Тайлбар
                        $this->queryString .= ' AND LOWER(NEX.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Дүгнэлтийн утга
                        $this->queryString .= ' AND LOWER(NEX.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $this->queryString .= ' AND (
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
            $this->queryString .= ' AND (LOWER(NFF.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        if ($param['preCrime'] != '') {

            $this->queryString .= ' AND LOWER(NFF.pre_crime) LIKE LOWER(\'%' . json_encode($param['preCrime']) . '%\')';
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $this->queryString .= ' AND NFF.solution_id != 0 AND NFF.close_type_id != 0 AND NFF.close_date <= NFF.out_date';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $this->queryString .= ' AND NFF.solution_id = 0 AND NFF.close_type_id = 0 AND NOW() <= NFF.out_date';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $this->queryString .= ' AND NFF.solution_id != 0 AND NFF.close_type_id != 0 AND NFF.close_date > NFF.out_date';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $this->queryString .= ' AND NFF.solution_id = 0 AND NFF.close_type_id = 0 AND NOW() > NFF.out_date';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $this->queryString .= ' AND NFF.protocol_out_date < NFF.in_date';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $this->queryString .= ' AND NFF.solution_id = 0 AND NFF.close_type_id = 0';
        }

        if ($param['expertId'] != 0) {
            $this->query = $this->db->query('
                SELECT 
                    NE.expert_id 
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_file_folder` AS NFF ON NE.cont_id = NFF.id AND NE.mod_id = NFF.mod_id
                WHERE NE.expert_id = ' . $param['expertId'] . $this->queryString);
        } else {
            $this->query = $this->db->query('
                SELECT 
                    NFF.id
                FROM `gaz_nifs_file_folder` AS NFF
                WHERE 1 = 1 ' . $this->queryString);
        }

        return $this->query->num_rows();
    }

    public function nifsFileFolderLists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $data = array();

        $this->queryString = $this->getString = '';

        if ($param['createNumber'] != 0) {
            $this->queryString .= ' AND NFF.create_number = ' . $param['createNumber'];
            $this->getString .= form_hidden('createNumber', $param['createNumber']);
        }

        if ($param['researchTypeId'] != 0) {
            $this->queryString .= ' AND NFF.research_type_id = ' . $param['researchTypeId'];
            $this->getString .= form_hidden('researchTypeId', $param['researchTypeId']);
        }

        if ($param['isMixx'] == 1) {
            $this->queryString .= ' AND NFF.is_mixx = 1';
            $this->getString .= form_hidden('isMixx', $param['isMixx']);
        }

        if ($param['isMixx'] == 2) {
            $this->queryString .= ' AND NFF.is_mixx = 0';
            $this->getString .= form_hidden('isMixx', $param['isMixx']);
        }

        $this->getString .= form_hidden('inDate', $param['inDate']);
        $this->getString .= form_hidden('outDate', $param['outDate']);

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NFF.in_date) AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NFF.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NFF.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NFF.out_date)';
        }

        $this->getString .= form_hidden('protocolInDate', $param['protocolInDate']);
        $this->getString .= form_hidden('protocolOutDate', $param['protocolOutDate']);

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NFF.protocol_in_date) AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NFF.protocol_out_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NFF.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NFF.protocol_out_date)';
        }

        $this->getString .= form_hidden('closeInDate', $param['closeInDate']);
        $this->getString .= form_hidden('closeOutDate', $param['closeOutDate']);

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NFF.close_date) AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NFF.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NFF.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NFF.close_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $this->queryString .= ' AND NFF.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['partnerId'] != 0) {
            $this->queryString .= ' AND NFF.partner_id IN(' . $this->partner->getChildPartners_model($param['partnerId']) . ')';
            $this->getString .= form_hidden('partnerId', $param['partnerId']);
        }

        if ($param['questionId'] != 0) {

            $this->queryString .= ' AND NFF.question_id = ' . $param['questionId'];
            $this->getString .= form_hidden('questionId', $param['questionId']);

            if ($param['questionId'] == 38) {

                $this->getString .= form_hidden('age1', $param['age1']);
                $this->getString .= form_hidden('age2', $param['age2']);

                if ($param['age1'] != '' and $param['age2'] != '') {

                    $this->queryString .= ' AND (NFF.age >= ' . floatval($param['age1']) . ' AND NFF.age <= ' . floatval($param['age2']) . ')';
                } else if ($param['age1'] != '' and $param['age2'] == '') {

                    $this->queryString .= ' AND \'' . floatval($param['age1']) . '\' <= NFF.age';
                } else if ($param['age1'] == '' and $param['age2'] != '') {

                    $this->queryString .= ' AND \'' . floatval($param['age2']) . '\' >= NFF.age';
                }
            }
        }

        if ($param['preCreateNumber'] != 0) {
            $this->queryString .= ' AND NFF.pre_create_number = ' . $param['preCreateNumber'];
            $this->getString .= form_hidden('preCreateNumber', $param['preCreateNumber']);
        }

        if ($param['preExpertId'] != 0) {
            $this->queryString .= ' AND NFF.pre_expert_id = ' . $param['preExpertId'];
            $this->getString .= form_hidden('preExpertId', $param['preExpertId']);
        }

        if ($param['motiveId'] != 0) {
            $this->queryString .= ' AND NFF.motive_id = ' . $param['motiveId'];
            $this->getString .= form_hidden('motiveId', $param['motiveId']);
        }

        if ($param['solutionId'] != 0) {
            $this->queryString .= ' AND NFF.solution_id = ' . $param['solutionId'];
            $this->getString .= form_hidden('solutionId', $param['solutionId']);
        }

        if ($param['closeTypeId'] != 0) {
            $this->queryString .= ' AND NFF.close_type_id = ' . $param['closeTypeId'];
            $this->getString .= form_hidden('closeTypeId', $param['closeTypeId']);
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND NFF.cat_id = ' . $param['catId'];
            $this->getString .= form_hidden('catId', $param['catId']);
        }

        if ($param['seniorExpertId'] != 0) {
            $this->queryString .= ' AND NFF.senior_expert_id = ' . $param['seniorExpertId'];
            $this->getString .= form_hidden('seniorExpertId', $param['seniorExpertId']);
        }

        if ($param['createExpertId'] != 0) {
            $this->queryString .= ' AND NFF.create_expert_id = ' . $param['createExpertId'];
            $this->getString .= form_hidden('createExpertId', $param['createExpertId']);
        }

        if ($param['weight'] != 0) {
            $this->queryString .= ' AND NFF.weight = ' . $param['weight'];
            $this->getString .= form_hidden('weight', $param['weight']);
        }

        if ($param['protocolNumber'] != 0) {
            $this->queryString .= ' AND NFF.protocol_number = ' . $param['protocolNumber'];
            $this->getString .= form_hidden('protocolNumber', $param['protocolNumber']);
        }

        if ($param['keyword'] != '') {

            $this->getString .= form_hidden('keyword', $param['keyword']);

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $this->queryString .= ' AND LOWER(NFF.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $this->queryString .= ' AND LOWER(NFF.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Албан тушаалтны нэр
                        $this->queryString .= ' AND LOWER(NEX.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Тайлбар
                        $this->queryString .= ' AND LOWER(NEX.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Дүгнэлтийн утга
                        $this->queryString .= ' AND LOWER(NEX.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $this->queryString .= ' AND (
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
            $this->queryString .= ' AND (LOWER(NFF.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
            $this->getString .= form_hidden('closeDescription', $param['closeDescription']);
        }

        if ($param['preCrime'] != '') {

            $this->queryString .= ' AND LOWER(NFF.pre_crime) LIKE LOWER(\'%' . json_encode($param['preCrime']) . '%\')';
            $this->getString .= form_hidden('preCrime', $param['preCrime']);
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $this->queryString .= ' AND NFF.solution_id != 0 AND NFF.close_type_id != 0 AND NFF.close_date <= NFF.out_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $this->queryString .= ' AND NFF.solution_id = 0 AND NFF.close_type_id = 0 AND NOW() <= NFF.out_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $this->queryString .= ' AND NFF.solution_id != 0 AND NFF.close_type_id != 0 AND NFF.close_date > NFF.out_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $this->queryString .= ' AND NFF.solution_id = 0 AND NFF.close_type_id = 0 AND NOW() > NFF.out_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $this->queryString .= ' AND NFF.protocol_out_date < NFF.in_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $this->queryString .= ' AND NFF.solution_id = 0 AND NFF.close_type_id = 0';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        }

        //seniorExpertId
        //createExpertId
        ////expertId

        if ($param['expertId'] != 0) {

            $this->getString .= form_hidden('expertId', $param['expertId']);

            $this->query = $this->db->query('
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
                    (IF(NFF.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:#ffffff;\')) AS expert_status,
                    IF(NFF.solution_id != 0, CONCAT(NFF.close_description, \'<br>\', DATE(NFF.close_date)), \'\') AS report,
                    IF(NFF.close_type_id = 22, CONCAT(\'(<strong>Зөрсөн:</strong> \', NFF.close_type_description, \'), \', NFF.description),\'\') AS description,
                    (CASE 
                        WHEN (NFF.solution_id != 0 AND NFF.close_date > NFF.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                        WHEN NFF.solution_id = 0 AND NOW() > NFF.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                        ELSE \'\'
                    END) AS row_status,
                    NFF.param
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_file_folder` AS NFF ON NE.cont_id = NFF.id AND NE.mod_id = NFF.mod_id
                WHERE NE.expert_id = ' . $param['expertId'] . $this->queryString . '
                ORDER BY NFF.create_number DESC
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        } else {
            $this->query = $this->db->query('
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
                    (IF(NFF.expert != \'\', \'background-color: transparent;\', \'background-color: #2196F3; color:#ffffff;\')) AS expert_status,
                    IF(NFF.solution_id != 0, CONCAT(NFF.close_description, \'<br>\', DATE(NFF.close_date)), \'\') AS report,
                    IF(NFF.close_type_id = 22, CONCAT(\'(<strong>Зөрсөн:</strong> \', NFF.close_type_description, \'), \', NFF.description),\'\') AS description,
                    (CASE 
                        WHEN (NFF.solution_id != 0 AND NFF.close_date > NFF.out_date) THEN \'background-color: #4CAF50; color:rgba(255,255,255,0.8);\'
                        WHEN NFF.solution_id = 0 AND NOW() > NFF.out_date THEN \'background-color: #F44336; color:rgba(255,255,255,0.8);\'
                        ELSE \'\'
                    END) AS row_status,
                    NFF.param
                FROM `gaz_nifs_file_folder` AS NFF
                WHERE 1 = 1 ' . $this->queryString . '
                ORDER BY NFF.create_number DESC
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        }

        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $key => $row) {

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
                    'create_number' => $row->create_number,
                    'is_mixx' => $row->is_mixx,
                    'in_out_date' => $row->in_out_date,
                    'full_name' => $row->full_name,
                    'partner' => $param->partner . ' ' . $row->agent_name,
                    'protocol' => '<strong>' . $row->protocol_number . '</strong> <br>' . $param->researchType,
                    'object' => $row->object,
                    'pre' => $preCrimeHtml,
                    'expert' => $row->expert . ' ' . $param->question,
                    'expert_status' => $row->expert_status,
                    'report' => $row->report,
                    'description' => $row->description,
                    'row_status' => $row->row_status
                ));
            }
        } else {
            array_push($data, array(
                'id' => 0,
                'create_number' => '',
                'is_mixx' => '',
                'in_out_date' => '',
                'full_name' => '',
                'partner' => '',
                'protocol' => '',
                'object' => '',
                'pre' => '',
                'expert' => '',
                'expert_status' => '',
                'report' => '',
                'description' => '',
                'row_status' => '',
                'config' => ''
            ));
        }
        return array('data' => $data, 'search' => self::nifsFileFolderSearchKeywordView_model());
    }
    
    public function nifsFileFolderSearchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
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
            $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->get('partnerId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->partnerData->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('createExpertId')) {

            $this->string .= form_hidden('createExpertId', $this->input->get('createExpertId'));

            if ($this->input->get('createExpertId') == 1000001) {
                $this->string .= '<span class="label label-default label-rounded">Шинжээч эмч: Тусгай</span>';
            } else if ($this->input->get('createExpertId') == 1000002) {
                $this->string .= '<span class="label label-default label-rounded">Шинжээч эмч: Кримналистик</span>';
            } else if ($this->input->get('createExpertId') == 1000003) {
                $this->string .= '<span class="label label-default label-rounded">Гадны мэргэжилтэн</span>';
            } else if ($this->input->get('createExpertId') == 1000004) {
                $this->string .= '<span class="label label-default label-rounded">Орон нутгийн шинжээч эмч</span>';
            } else {
                $this->createExpertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->get('createExpertId')));
                $this->string .= '<span class="label label-default label-rounded">Бичсэн шинжээч: ' . $this->createExpertData->full_name . '</span>';
            }
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('solutionId')) {
            $this->nifsSolution = $this->nifsSolution->getData_model(array('selectedId' => $this->input->get('solutionId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsSolution->title . '</span>';
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
            $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $this->input->get('motiveId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsMotiveData->title . '</span>';
            $this->string .= form_hidden('motiveId', $this->input->get('motiveId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('catId')) {
            $this->cat = $this->category->getData_model(array('selectedId' => $this->input->get('catId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->cat->title . '</span>';
            $this->string .= form_hidden('catId', $this->input->get('catId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('seniorExpertId')) {
            $this->seniorExpertData = $this->hrPeople->getData_model(array('selectedId' => $this->input->get('seniorExpertId')));
            $this->string .= '<span class="label label-default label-rounded">Ахалсан шинжээч: ' . $this->seniorExpertData->full_name . '</span>';
            $this->string .= form_hidden('seniorExpertId', $this->input->get('seniorExpertId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('statusId')) {
            $this->statusData = $this->nifsStatus->getData_model(array('selectedId' => $this->input->get('statusId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->statusData->title . '</span>';
            $this->string .= form_hidden('statusId', $this->input->get('statusId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('typeId')) {
            $this->nifsCrimeType = $this->nifsCrimeType->getData_model(array('selectedId' => $this->input->get('typeId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCrimeType->title . '</span>';
            $this->string .= form_hidden('typeId', $this->input->get('typeId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('questionId')) {

            $this->nifsQuestion = $this->nifsQuestion->getData_model(array('selectedId' => $this->input->get('questionId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsQuestion->title . '</span>';
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
            $this->nifsCloseType = $this->nifsCloseType->getData_model(array('selectedId' => $this->input->get('closeTypeId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCloseType->title . '</span>';
            $this->string .= form_hidden('closeTypeId', $this->input->get('closeTypeId'));
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

                $this->string .= ' <a href="javascript:;" onclick="_initMyNifsFileFolder({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            }
        }

        return ($this->showResetBtn ? '<td class="_search-result-td"><div class="datagrid-btn-separator"></div></td><td class="_search-result-td"><form action="javascript:;" id="form-nifs-file-folder"> Хайлтын үр дүн: ' . $this->string . '</form></td>' : '');
    }
    
    public function nifsAnatomyListsCount_model($param = array()) {

        $this->queryString = $this->getString = '';

        if ($param['createNumber'] != '') {

            $this->queryString .= ' AND NA.create_number = ' . $param['createNumber'];
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $this->queryString .= ' AND LOWER(NA.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $this->queryString .= ' AND LOWER(NA.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Регистрийн дугаар
                        $this->queryString .= ' AND LOWER(NA.register) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Хаяг
                        $this->queryString .= ' AND LOWER(NA.address) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Албан хаагч
                        $this->queryString .= ' AND LOWER(NA.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 6: {   //Болсон хэргийн товч
                        $this->queryString .= ' AND LOWER(NA.short_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 7: {   //Тайлбар
                        $this->queryString .= ' AND LOWER(NA.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 8: {   //Дүгнэлтийн утга
                        $this->queryString .= ' AND LOWER(NA.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $this->queryString .= ' AND (
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

            $this->queryString .= ' AND NA.work_id = ' . $param['workId'];
        }

        if ($param['partnerId'] != 0) {

            $this->queryString .= ' AND NA.partner_id IN(' . $this->partner->getChildPartners_model(array($param['partnerId'])) . ')';
        }

        if ($param['shortValueId'] != 0) {

            $this->queryString .= ' AND NA.short_value_id = ' . $param['shortValueId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NA.in_date) AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NA.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NA.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NA.out_date)';
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NA.protocol_in_date) AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NA.protocol_in_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NA.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NA.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NA.end_date) AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NA.end_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NA.end_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NA.end_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $this->queryString .= ' AND NA.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['isAgeInfinitive'] == 1) {

            $this->queryString .= ' AND NA.is_age_infinitive = ' . $param['isAgeInfinitive'];
        } else {

            if ($param['age1'] != '' and $param['age2'] != '') {

                $this->queryString .= ' AND (NA.age >= ' . $param['age1'] . ' AND NA.age <= ' . $param['age2'] . ') AND NA.is_age_infinitive = 0';
            } else if ($param['age1'] != '' and $param['age2'] == '') {

                $this->queryString .= ' AND \'' . $param['age1'] . '\' >= NA.age AND NA.is_age_infinitive = 0';
            } else if ($param['age1'] == '' and $param['age2'] != '') {

                $this->queryString .= ' AND \'' . $param['age2'] . '\' <= NA.age AND NA.is_age_infinitive = 0';
            }
        }

        if ($param['whereId'] != 0) {

            $this->queryString .= ' AND NA.where_id = ' . $param['whereId'];
        }

        if ($param['catId'] != 0) {

            $this->queryString .= ' AND NA.cat_id = ' . $param['catId'];
        }

        if ($param['motiveId'] != 0) {

            $this->queryString .= ' AND NA.motive_id = ' . $param['motiveId'];
        }

        if ($param['isMixx'] == 1) {

            $this->queryString .= ' AND NA.is_mixx = 1';
        }

        if ($param['isMixx'] == 2) {

            $this->queryString .= ' AND NA.is_mixx = 0';
        }

        if ($param['closeDate'] != '') {

            $this->queryString .= ' AND DATE(NA.close_date) = DATE(\'' . $param['closeDate'] . '\')';
        }

        if ($param['solutionId'] > 0) {

            $this->queryString .= ' AND NA.solution_id = ' . $param['solutionId'];
        }

        if ($param['closeTypeId'] > 0) {

            $this->queryString .= ' AND NA.close_type_id = ' . $param['closeTypeId'];
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $this->queryString .= ' AND NA.solution_id != 0 AND NA.end_date <= NA.out_date';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $this->queryString .= ' AND NA.solution_id = 0 AND NOW() <= NA.out_date';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $this->queryString .= ' AND NA.solution_id != 0 AND NA.end_date > NA.out_date';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $this->queryString .= ' AND NA.solution_id = 0 AND NOW() > NA.out_date';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $this->queryString .= ' AND NA.protocol_out_date < NA.in_date';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $this->queryString .= ' AND NA.solution_id = 0';
        }

        if ($param['closeDescription'] != '') {

            $this->queryString .= ' AND (LOWER(NA.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        if ($param['sex'] == 1) {

            $this->queryString .= ' AND NA.sex = 1';
        } else if ($param['sex'] == 2) {

            $this->queryString .= ' AND NA.sex = 0';
        }

        if ($param['expertId'] != 0) {
            $this->query = $this->db->query('
                SELECT 
                    NE.expert_id 
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_anatomy` AS NA ON NE.cont_id = NA.id AND NE.mod_id = NA.mod_id
                WHERE NE.expert_id = ' . $param['expertId'] . $this->queryString);
        } else {
            $this->query = $this->db->query('
                SELECT 
                    NA.id
                FROM `gaz_nifs_anatomy` AS NA
                WHERE 1 = 1 ' . $this->queryString);
        }
        return $this->query->num_rows();
    }

    public function nifsAnatomyLists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $data = array();

        $this->queryString = $this->getString = '';

        if ($param['createNumber'] != '') {
            $this->queryString .= ' AND NA.create_number = ' . $param['createNumber'];
            $this->getString .= form_hidden('createNumber', $param['createNumber']);
        }

        if ($param['keyword'] != '') {

            $this->getString .= form_hidden('keyword', $param['keyword']);
            $this->getString .= form_hidden('keywordTypeId', $param['keywordTypeId']);

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $this->queryString .= ' AND LOWER(NA.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $this->queryString .= ' AND LOWER(NA.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Регистрийн дугаар
                        $this->queryString .= ' AND LOWER(NA.register) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Хаяг
                        $this->queryString .= ' AND LOWER(NA.address) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Албан хаагч
                        $this->queryString .= ' AND LOWER(NA.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 6: {   //Болсон хэргийн товч
                        $this->queryString .= ' AND LOWER(NA.short_value) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 7: {   //Тайлбар
                        $this->queryString .= ' AND LOWER(NA.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 8: {   //Дүгнэлтийн утга
                        $this->queryString .= ' AND LOWER(NA.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $this->queryString .= ' AND (
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
            $this->queryString .= ' AND NA.work_id = ' . $param['workId'];
            $this->getString .= form_hidden('workId', $param['workId']);
        }

        if ($param['partnerId'] != 0) {
            $this->queryString .= ' AND NA.partner_id IN(' . $this->partner->getChildPartners_model(array($param['partnerId'])) . ')';
            $this->getString .= form_hidden('partnerId', $param['partnerId']);
        }

        if ($param['shortValueId'] != 0) {
            $this->queryString .= ' AND NA.short_value_id = ' . $param['shortValueId'];
            $this->getString .= form_hidden('shortValueId', $param['shortValueId']);
        }

        $this->getString .= form_hidden('inDate', $param['inDate']);
        $this->getString .= form_hidden('outDate', $param['outDate']);

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NA.in_date) AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NA.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['inDate'])) . '\' <= DATE(NA.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['outDate'])) . '\' >= DATE(NA.out_date)';
        }


        $this->getString .= form_hidden('protocolInDate', $param['protocolInDate']);
        $this->getString .= form_hidden('protocolOutDate', $param['protocolOutDate']);

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NA.protocol_in_date) AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NA.protocol_out_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolInDate'])) . '\' <= DATE(NA.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['protocolOutDate'])) . '\' >= DATE(NA.protocol_out_date)';
        }

        $this->getString .= form_hidden('closeInDate', $param['closeInDate']);
        $this->getString .= form_hidden('closeOutDate', $param['closeOutDate']);

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NA.end_date) AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NA.end_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeInDate'])) . '\' <= DATE(NA.end_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $this->queryString .= ' AND \'' . date('Y-m-d', strtotime($param['closeOutDate'])) . '\' >= DATE(NA.end_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '') {

            $this->queryString .= ' AND NA.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['isAgeInfinitive'] == 1) {

            $this->queryString .= ' AND NA.is_age_infinitive = ' . $param['isAgeInfinitive'];
            $this->getString .= form_hidden('isAgeInfinitive', $param['isAgeInfinitive']);
        } else {

            $this->getString .= form_hidden('age1', $param['age1']);
            $this->getString .= form_hidden('age2', $param['age2']);

            if ($param['age1'] != '' and $param['age2'] != '') {
                $this->queryString .= ' AND (NA.age >= ' . $param['age1'] . ' AND NA.age <= ' . $param['age2'] . ') AND NA.is_age_infinitive = 0';
            } elseif ($param['age1'] != '' and $param['age2'] == '') {
                $this->queryString .= ' AND \'' . $param['age1'] . '\' >= NA.age AND NA.is_age_infinitive = 0';
            } elseif ($param['age1'] == '' and $param['age2'] != '') {
                $this->queryString .= ' AND \'' . $param['age2'] . '\' <= NA.age AND NA.is_age_infinitive = 0';
            }
        }

        if ($param['whereId'] != 0) {
            $this->queryString .= ' AND NA.where_id = ' . $param['whereId'];
            $this->getString .= form_hidden('whereId', $param['whereId']);
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND NA.cat_id = ' . $param['catId'];
            $this->getString .= form_hidden('catId', $param['catId']);
        }

        if ($param['motiveId'] != 0) {

            $this->queryString .= ' AND NA.motive_id = ' . $param['motiveId'];
            $this->getString .= form_hidden('motiveId', $param['motiveId']);
        }

        if ($param['isMixx'] == 1) {
            $this->queryString .= ' AND NA.is_mixx = 1';
            $this->getString .= form_hidden('isMixx', $param['isMixx']);
        }

        if ($param['isMixx'] == 2) {
            $this->queryString .= ' AND NA.is_mixx = 0';
            $this->getString .= form_hidden('isMixx', $param['isMixx']);
        }

        if ($param['closeDate'] != '') {

            $this->queryString .= ' AND DATE(NA.close_date) = DATE(\'' . $param['closeDate'] . '\')';
            $this->getString .= form_hidden('closeDate', $param['closeDate']);
        }

        if ($param['solutionId'] > 0) {

            $this->queryString .= ' AND NA.solution_id = ' . $param['solutionId'];
            $this->getString .= form_hidden('solutionId', $param['solutionId']);
        }

        if ($param['closeTypeId'] > 0) {

            $this->queryString .= ' AND NA.close_type_id = ' . $param['closeTypeId'];
            $this->getString .= form_hidden('closeTypeId', $param['closeTypeId']);
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $this->queryString .= ' AND NA.solution_id != 0 AND NA.end_date <= NA.out_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $this->queryString .= ' AND NA.solution_id = 0 AND NOW() <= NA.out_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)   
            $this->queryString .= ' AND NA.solution_id != 0 AND NA.end_date > NA.out_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $this->queryString .= ' AND NA.solution_id = 0 AND NOW() > NA.out_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $this->queryString .= ' AND NA.protocol_out_date < NA.in_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $this->queryString .= ' AND NA.solution_id = 0';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        }

        if ($param['closeDescription'] != '') {

            $this->getString .= form_hidden('closeDescription', $param['closeDescription']);
            $this->queryString .= ' AND (LOWER(NA.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        if ($param['sex'] == 1) {

            $this->queryString .= ' AND NA.sex = 1';
            $this->getString .= form_hidden('sex', 1);
        } else if ($param['sex'] == 2) {

            $this->queryString .= ' AND NA.sex = 0';
            $this->getString .= form_hidden('sex', 2);
        }

        if ($param['expertId'] != 0) {

            $this->getString .= form_hidden('expertId', $param['expertId']);

            $this->query = $this->db->query('
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
                    NA.short_value,
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
			WHEN (NSD.id != \'\' AND NSD.solution_id != \'\') THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/send-document-blue.svg" style="width:16px;">\'
			WHEN NSD.id != \'\' AND NSD.solution_id = \'\' THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/send-document-grey.svg" style="width:16px;">\'
			ELSE \'\'
                    END) AS send_document,
                    NSD.id AS send_document_id,
                    NA.param
                FROM 
                    `gaz_nifs_expert` AS NE
                INNER JOIN `gaz_nifs_anatomy` AS NA ON NE.cont_id = NA.id AND NE.mod_id = NA.mod_id
                LEFT JOIN `gaz_nifs_send_doc` AS NSD ON NA.mod_id = NSD.mod_id AND NA.id = NSD.cont_id
                WHERE NE.expert_id = ' . $param['expertId'] . $this->queryString . '
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        } else {
            $this->query = $this->db->query('
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
                    NA.short_value,
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
			WHEN (NSD.id != \'\' AND NSD.solution_id != \'\') THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/send-document-blue.svg" style="width:16px;">\'
			WHEN NSD.id != \'\' AND NSD.solution_id = \'\' THEN \'<img src="/assets/system/plugins/jquery-easyui-1.8.1/themes/icons/send-document-grey.svg" style="width:16px;">\'
			ELSE \'\'
                    END) AS send_document,
                    NSD.id AS send_document_id,
                    NA.param
                FROM `gaz_nifs_anatomy` AS NA 
                LEFT JOIN `gaz_nifs_send_doc` AS NSD ON NA.mod_id = NSD.mod_id AND NA.id = NSD.cont_id
                WHERE 1 = 1 ' . $this->queryString . '
                ORDER BY NA.create_number DESC
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        }

        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $key => $row) {

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
                    'expert' => $row->expert_name,
                    'is_where' => $param->where . '<br>' . $row->payment,
                    'diagnosis' => '<strong>' . $param->solution . '</strong> ' . $row->close_description . '<br>' . $row->end_date,
                    'row_status' => $row->row_status,
                    'send_document' => $row->send_document,
                    'send_document_id' => $row->send_document_id
                ));
            }
        } else {
            array_push($data, array(
                'id' => 0,
                'mod_id' => 0,
                'create_number' => '',
                'in_out_date' => '',
                'be_date' => '',
                'resolution' => '',
                'full_name' => '',
                'is_work' => '',
                'partner' => '',
                'short_value' => '',
                'expert_status' => '',
                'expert' => '',
                'is_where' => '',
                'diagnosis' => '',
                'row_status' => '',
                'config' => '',
                'send_document' => '',
                'send_document_id' => ''
            ));
        }
        return array('data' => $data, 'search' => self::nifsAnatomySearchKeywordView_model());
    }
    
    public function nifsAnatomySearchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
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
            $this->nifsWorkData = $this->nifsWork->getData_model(array('selectedId' => $this->input->get('workId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsWorkData->title . '</span>';
            $this->string .= form_hidden('workId', $this->input->get('workId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('partnerId')) {
            $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->get('partnerId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->partnerData->title . '</span>';
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
            $this->nifsCrimeShortValueData = $this->nifsCrimeShortValue->getData_model(array('selectedId' => $this->input->get('shortValueId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCrimeShortValueData->title . '</span>';
            $this->string .= form_hidden('shortValueId', $this->input->get('shortValueId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('sex')) {
            $this->sexData = $this->hrPeopleSex->getData_model(array('selectedId' => $this->input->get('sex')));
            $this->string .= '<span class="label label-default label-rounded">Хүйс: ' . $this->sexData->title . '</span>';
            $this->string .= form_hidden('sex', $this->input->get('sex'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('whereId')) {
            $this->nifsWhereData = $this->nifsWhere->getData_model(array('selectedId' => $this->input->get('whereId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsWhereData->title . '</span>';
            $this->string .= form_hidden('whereId', $this->input->get('whereId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('motiveId')) {
            $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $this->input->get('motiveId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsMotiveData->title . '</span>';
            $this->string .= form_hidden('motiveId', $this->input->get('motiveId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('solutionId') and $this->input->get('solutionId') > 0) {
            $this->nifsSolution = $this->nifsSolution->getData_model(array('selectedId' => $this->input->get('solutionId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsSolution->title . '</span>';
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
            $this->cat = $this->category->getData_model(array('selectedId' => $this->input->get('catId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->cat->title . '</span>';
            $this->string .= form_hidden('catId', $this->input->get('catId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('closeTypeId')) {
            $this->nifsCloseType = $this->nifsCloseType->getData_model(array('selectedId' => $this->input->get('closeTypeId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCloseType->title . '</span>';
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

                $this->string .= ' <a href="javascript:;" onclick="_initMyNifsAnatomy({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            }
        }


        return ($this->showResetBtn ? '<td class="_search-result-td"><div class="datagrid-btn-separator"></div></td><td class="_search-result-td"><form action="javascript:;" id="form-nifs-anatomy"> Хайлтын үр дүн: ' . $this->string . '</form></td>' : '');
    }
    
    public function nifsDoctorViewListsCount_model($param = array()) {
        $this->queryString = $this->getString = '';

        if ($param['createNumber'] != '') {
            $this->queryString .= ' AND NDV.create_number = ' . $param['createNumber'];
        }

        if ($param['keyword'] != '') {

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $this->queryString .= ' AND LOWER(NDV.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $this->queryString .= ' AND LOWER(NDV.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Регистрийн дугаар
                        $this->queryString .= ' AND LOWER(NDV.register) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Утас
                        $this->queryString .= ' AND LOWER(NDV.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Тайлбар
                        $this->queryString .= ' AND LOWER(NDV.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 6: {   //Албан хаагч
                        $this->queryString .= ' AND LOWER(NDV.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 7: {   //Дүгнэлтийн утга
                        $this->queryString .= ' AND LOWER(NDV.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $this->queryString .= ' AND (
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
            $this->queryString .= ' AND LOWER(NDV.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\')';
        }

        if ($param['workId'] != 0) {
            $this->queryString .= ' AND NDV.work_id = ' . $param['workId'];
        }

        if ($param['motiveId'] != 0) {
            $this->queryString .= ' AND NDV.motive_id = ' . $param['motiveId'];
        }

        if ($param['partnerId'] != 0) {
            $this->queryString .= ' AND NDV.partner_id IN(' . $this->partner->getChildPartners_model(array($param['partnerId'])) . ')';
        }

        if ($param['shortValueId'] != 0) {
            $this->queryString .= ' AND NDV.short_value_id = ' . $param['shortValueId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $this->queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(NDV.in_date) AND \'' . $param['outDate'] . '\' >= DATE(NDV.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $this->queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(NDV.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $this->queryString .= ' AND \'' . $param['outDate'] . '\' >= DATE(NDV.out_date)';
        }

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $this->queryString .= ' AND \'' . $param['protocolInDate'] . '\' <= DATE(NDV.protocol_in_date) AND \'' . $param['protocolOutDate'] . '\' >= DATE(NDV.protocol_in_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $this->queryString .= ' AND \'' . $param['protocolInDate'] . '\' <= DATE(NDV.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $this->queryString .= ' AND \'' . $param['protocolOutDate'] . '\' >= DATE(NDV.protocol_out_date)';
        }

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $this->queryString .= ' AND \'' . $param['closeInDate'] . '\' <= DATE(NDV.close_date) AND \'' . $param['closeOutDate'] . '\' >= DATE(NDV.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $this->queryString .= ' AND \'' . $param['closeInDate'] . '\' <= DATE(NDV.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $this->queryString .= ' AND \'' . $param['closeOutDate'] . '\' >= DATE(NDV.close_date)';
        }

        if ($param['crimeInDate'] != '' and $param['crimeOutDate'] != '') {

            $this->queryString .= ' AND \'' . $param['crimeInDate'] . '\' <= DATE(NDV.crime_date) AND \'' . $param['crimeOutDate'] . '\' >= DATE(NDV.crime_date)';
        } else if ($param['crimeInDate'] != '' and $param['crimeOutDate'] == '') {

            $this->queryString .= ' AND \'' . $param['crimeInDate'] . '\' <= DATE(NDV.crime_date)';
        } else if ($param['crimeInDate'] == '' and $param['crimeOutDate'] != '') {

            $this->queryString .= ' AND \'' . $param['crimeOutDate'] . '\' >= DATE(NDV.crime_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '' and $param['crimeInDate'] == '' and $param['crimeOutDate'] == '') {

            $this->queryString .= ' AND NDV.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['isAgeInfinitive'] == 1) {
            $this->queryString .= ' AND NDV.is_age_infinitive = ' . $param['isAgeInfinitive'];
        } else {
            if ($param['age1'] != '' and $param['age2'] != '') {
                $this->queryString .= ' AND (NDV.age >= ' . $param['age1'] . ' AND NDV.age <= ' . $param['age2'] . ')';
            } elseif ($param['age1'] != '' and $param['age2'] == '') {
                $this->queryString .= ' AND \'' . $param['age1'] . '\' >= NDV.age';
            } elseif ($param['age1'] == '' and $param['age2'] != '') {
                $this->queryString .= ' AND \'' . $param['age2'] . '\' <= NDV.age';
            }
        }

        if ($param['isAgeInfinitive'] != 0) {
            $this->queryString .= ' AND NDV.is_age_infinitive = ' . $param['isAgeInfinitive'];
        }

        if ($param['crimeShortValueId'] != 0) {
            $this->queryString .= ' AND NDV.crime_short_value_id = ' . $param['crimeShortValueId'];
        }

        if ($param['expertId'] != 0) {
            $this->queryString .= ' AND NDV.expert_id = ' . $param['expertId'];
        }

        if ($param['whereId'] != 0) {

            $this->queryString .= ' AND NDV.where_id = ' . $param['whereId'];
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND NDV.cat_id = ' . $param['catId'];
        }

        if ($param['payment'] == 1) {
            $this->queryString .= ' AND NDV.payment = 1';
        }

        if ($param['payment'] == 2) {
            $this->queryString .= ' AND NDV.payment = 0';
        }

        if ($param['isSperm'] == 1) {
            $this->queryString .= ' AND NDV.is_sperm = 1';
        }

        if ($param['isSperm'] == 2) {
            $this->queryString .= ' AND NDV.is_sperm = 0';
        }

        if ($param['sex'] == 1) {
            $this->queryString .= ' AND NDV.sex = 1';
        }

        if ($param['sex'] == 2) {
            $this->queryString .= ' AND NDV.sex = 0';
        }

        if ($param['closeTypeId'] > 0) {
            $this->queryString .= ' AND NDV.close_type_id = ' . $param['closeTypeId'];
        }

        if ($param['isCrimeShip'] == 1) {
            $this->queryString .= ' AND NDV.is_crime_ship = 1';
        }

        if ($param['isCrimeShip'] == 2) {
            $this->queryString .= ' AND NDV.is_crime_ship = 0';
        }


        if ($param['closeDescription'] != '') {

            $this->queryString .= ' AND (LOWER(NDV.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\'))';
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $this->queryString .= ' AND NDV.close_type_id != 0 AND NDV.close_date <= NDV.out_date';
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $this->queryString .= ' AND NDV.close_type_id = 0 AND NOW() <= NDV.out_date';
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $this->queryString .= ' AND NDV.close_type_id != 0 AND NDV.close_date > NDV.out_date';
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $this->queryString .= ' AND NDV.close_type_id = 0 AND NOW() > NDV.out_date';
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $this->queryString .= ' AND NDV.protocol_out_date < NDV.in_date';
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $this->queryString .= ' AND NDV.close_type_id = 0';
        }

        $this->query = $this->db->query('
            SELECT 
                NDV.id
            FROM `gaz_nifs_doctor_view` AS NDV
            WHERE 1 = 1 ' . $this->queryString);

        return $this->query->num_rows();
    }

    public function nifsDoctorViewLists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $data = array();

        $this->queryString = $this->getString = '';

        if ($param['catId'] != 0) {

            $this->queryString .= ' AND NDV.cat_id = ' . $param['catId'];
            $this->getString .= form_hidden('catId', $param['catId']);
        }

        $this->getString .= form_hidden('inDate', $param['inDate']);
        $this->getString .= form_hidden('outDate', $param['outDate']);

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $this->queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(NDV.in_date) AND \'' . $param['outDate'] . '\' >= DATE(NDV.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $this->queryString .= ' AND \'' . $param['inDate'] . '\' <= DATE(NDV.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $this->queryString .= ' AND \'' . $param['outDate'] . '\' >= DATE(NDV.out_date)';
        }

        $this->getString .= form_hidden('protocolInDate', $param['protocolInDate']);
        $this->getString .= form_hidden('protocolOutDate', $param['protocolOutDate']);

        if ($param['protocolInDate'] != '' and $param['protocolOutDate'] != '') {

            $this->queryString .= ' AND \'' . $param['protocolInDate'] . '\' <= DATE(NDV.protocol_in_date) AND \'' . $param['protocolOutDate'] . '\' >= DATE(NDV.protocol_in_date)';
        } else if ($param['protocolInDate'] != '' and $param['protocolOutDate'] == '') {

            $this->queryString .= ' AND \'' . $param['protocolInDate'] . '\' <= DATE(NDV.protocol_in_date)';
        } else if ($param['protocolInDate'] == '' and $param['protocolOutDate'] != '') {

            $this->queryString .= ' AND \'' . $param['protocolOutDate'] . '\' >= DATE(NDV.protocol_out_date)';
        }

        $this->getString .= form_hidden('closeInDate', $param['closeInDate']);
        $this->getString .= form_hidden('closeOutDate', $param['closeOutDate']);

        if ($param['closeInDate'] != '' and $param['closeOutDate'] != '') {

            $this->queryString .= ' AND \'' . $param['closeInDate'] . '\' <= DATE(NDV.close_date) AND \'' . $param['closeOutDate'] . '\' >= DATE(NDV.close_date)';
        } else if ($param['closeInDate'] != '' and $param['closeOutDate'] == '') {

            $this->queryString .= ' AND \'' . $param['closeInDate'] . '\' <= DATE(NDV.close_date)';
        } else if ($param['closeInDate'] == '' and $param['closeOutDate'] != '') {

            $this->queryString .= ' AND \'' . $param['closeOutDate'] . '\' >= DATE(NDV.close_date)';
        }


        $this->getString .= form_hidden('crimeInDate', $param['crimeInDate']);
        $this->getString .= form_hidden('crimeOutDate', $param['crimeOutDate']);

        if ($param['crimeInDate'] != '' and $param['crimeOutDate'] != '') {

            $this->queryString .= ' AND \'' . $param['crimeInDate'] . '\' <= DATE(NDV.crime_date) AND \'' . $param['crimeOutDate'] . '\' >= DATE(NDV.crime_date)';
        } else if ($param['crimeInDate'] != '' and $param['crimeOutDate'] == '') {

            $this->queryString .= ' AND \'' . $param['crimeInDate'] . '\' <= DATE(NDV.crime_date)';
        } else if ($param['crimeInDate'] == '' and $param['crimeOutDate'] != '') {

            $this->queryString .= ' AND \'' . $param['crimeOutDate'] . '\' >= DATE(NDV.crime_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '' and $param['protocolInDate'] == '' and $param['protocolOutDate'] == '' and $param['closeInDate'] == '' and $param['closeOutDate'] == '' and $param['crimeInDate'] == '' and $param['crimeOutDate'] == '') {

            $this->queryString .= ' AND NDV.year = \'' . $this->session->adminCloseYear . '\'';
        }

        if ($param['isAgeInfinitive'] == 1) {
            $this->queryString .= ' AND NDV.is_age_infinitive = ' . $param['isAgeInfinitive'];
            $this->getString .= form_hidden('isAgeInfinitive', $param['isAgeInfinitive']);
        } else {

            $this->getString .= form_hidden('age1', $param['age1']);
            $this->getString .= form_hidden('age2', $param['age2']);

            if ($param['age1'] != '' and $param['age2'] != '') {
                $this->queryString .= ' AND (NDV.age >= ' . $param['age1'] . ' AND NDV.age <= ' . $param['age2'] . ')';
            } elseif ($param['age1'] != '' and $param['age2'] == '') {
                $this->queryString .= ' AND \'' . $param['age1'] . '\' >= NDV.age';
            } elseif ($param['age1'] == '' and $param['age2'] != '') {
                $this->queryString .= ' AND \'' . $param['age2'] . '\' <= NDV.age';
            }
        }

        if ($param['shortValueId'] != 0) {
            $this->queryString .= ' AND NDV.short_value_id = ' . $param['shortValueId'];
            $this->getString .= form_hidden('shortValueId', $param['shortValueId']);
        }

        if ($param['partnerId'] != 0) {
            $this->queryString .= ' AND NDV.partner_id IN(' . $this->partner->getChildPartners_model(array($param['partnerId'])) . ')';
            $this->getString .= form_hidden('partnerId', $param['partnerId']);
        }

        if ($param['motiveId'] != 0) {
            $this->queryString .= ' AND NDV.motive_id = ' . $param['motiveId'];
            $this->getString .= form_hidden('motiveId', $param['motiveId']);
        }

        if ($param['closeTypeId'] > 0) {
            $this->queryString .= ' AND NDV.close_type_id = ' . $param['closeTypeId'];
            $this->getString .= form_hidden('closeTypeId', $param['closeTypeId']);
        }

        if ($param['isCrimeShip'] == 1) {

            $this->queryString .= ' AND NDV.is_crime_ship = 1';
            $this->getString .= form_hidden('isCrimeShip', $param['isCrimeShip']);
        }

        if ($param['isCrimeShip'] == 2) {

            $this->queryString .= ' AND NDV.is_crime_ship = 0';
            $this->getString .= form_hidden('isCrimeShip', $param['isCrimeShip']);
        }

        if ($param['expertId'] != 0) {
            $this->queryString .= ' AND NDV.expert_id = ' . $param['expertId'];
            $this->getString .= form_hidden('expertId', $param['expertId']);
        }

        if ($param['createNumber'] != '') {
            $this->queryString .= ' AND NDV.create_number = ' . $param['createNumber'];
            $this->getString .= form_hidden('createNumber', $param['createNumber']);
        }

        if ($param['keyword'] != '') {

            $this->getString .= form_hidden('keyword', $param['keyword']);
            $this->getString .= form_hidden('keywordTypeId', $param['keywordTypeId']);

            switch ($param['keywordTypeId']) {
                case 1: {   //Эцэг/эх/-ийн нэр
                        $this->queryString .= ' AND LOWER(NDV.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 2: {   //Өөрийн нэр
                        $this->queryString .= ' AND LOWER(NDV.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 3: {   //Регистрийн дугаар
                        $this->queryString .= ' AND LOWER(NDV.register) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 4: {   //Утас
                        $this->queryString .= ' AND LOWER(NDV.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 5: {   //Тайлбар
                        $this->queryString .= ' AND LOWER(NDV.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 6: {   //Албан хаагч
                        $this->queryString .= ' AND LOWER(NDV.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                case 7: {   //Дүгнэлтийн утга
                        $this->queryString .= ' AND LOWER(NDV.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
                    };
                    break;

                default: {
                        $this->queryString .= ' AND (
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

            $this->getString .= form_hidden('closeDescription', $param['closeDescription']);
            $this->queryString .= ' AND LOWER(NDV.close_description) LIKE LOWER(\'%' . $param['closeDescription'] . '%\')';
        }

        if ($param['whereId'] != 0) {

            $this->queryString .= ' AND NDV.where_id = ' . $param['whereId'];
            $this->getString .= form_hidden('whereId', $param['whereId']);
        }

        if ($param['payment'] == 1) {

            $this->queryString .= ' AND NDV.payment = 1';
            $this->getString .= form_hidden('payment', $param['payment']);
        }

        if ($param['payment'] == 2) {

            $this->queryString .= ' AND NDV.payment = 0';
            $this->getString .= form_hidden('payment', $param['payment']);
        }

        if ($param['isSperm'] == 1) {

            $this->queryString .= ' AND NDV.is_sperm = 1';
            $this->getString .= form_hidden('isSperm', $param['isSperm']);
        }

        if ($param['isSperm'] == 2) {

            $this->queryString .= ' AND NDV.is_sperm = 0';
            $this->getString .= form_hidden('isSperm', $param['isSperm']);
        }

        if ($param['sex'] == 1) {

            $this->queryString .= ' AND NDV.sex = 1';
            $this->getString .= form_hidden('sex', $param['sex']);
        }

        if ($param['sex'] == 2) {

            $this->queryString .= ' AND NDV.sex = 0';
            $this->getString .= form_hidden('sex', $param['sex']);
        }

        if ($param['statusId'] == 1) {

            //Хэвийн шинжилгээ (хугацаандаа хаагдасан)
            $this->queryString .= ' AND NDV.close_type_id != 0 AND NDV.close_date <= NDV.out_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 2) {

            //Хэвийн шинжилгээ (гар дээр байгаа)
            $this->queryString .= ' AND NDV.close_type_id = 0 AND NOW() <= NDV.out_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 3) {

            //Хугацаа хэтэрсэн (хаагдсан шинжилгээ)
            $this->queryString .= ' AND NDV.close_type_id != 0 AND NDV.close_date > NDV.out_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 4) {

            //Хугацаа хэрэтсэн (хаагдаагүй шинжилгээ)
            $this->queryString .= ' AND NDV.close_type_id = 0 AND NOW() > NDV.out_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 5) {

            //Тогтоолын хугацаа дуусаж ирсэн шинжилгээ
            $this->queryString .= ' AND NDV.protocol_out_date < NDV.in_date';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        } else if ($param['statusId'] == 6) {

            //Хаагдаагүй шинжилгээ бүгд
            $this->queryString .= ' AND NDV.close_type_id = 0';
            $this->getString .= form_hidden('statusId', $param['statusId']);
        }

        $this->query = $this->db->query('
            SELECT
                NDV.id,
                NDV.mod_id,
                NDV.cat_id,
                NDV.created_user_id,
                NDV.create_number,
                CONCAT("Э: ", DATE_FORMAT(NDV.in_date, \'%Y-%m-%d %H:%i\'), "<br>", "Д: ", DATE_FORMAT(NDV.out_date, \'%Y-%m-%d %H:%i\')) AS in_out_date,
                (IF(NDV.lname != \'\', CONCAT(NDV.lname, "-н ", "<strong>", NDV.fname, "</strong>"), NDV.fname)) AS full_name,
                (IF(NDV.register != \'\', CONCAT(", ", NDV.register), \'\')) AS register,
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
                (IF(NDV.expert_id != 0, \'background-color: transparent;\', \'background-color: #2196F3; color:rgba(255,255,255,0.8);\')) AS expert_status,
                NDV.where_id,
                NDV.close_type_id,
                DATE(NDV.close_date) AS close_date,

                NDV.close_description,
                IF(NDV.payment = 1, CONCAT(\'Төлбөр төлсөн\', \'<br>\', NDV.description), CONCAT(\'Төлбөр төлөөгүй\', \'<br>\', NDV.description)) AS payment,
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
            WHERE 1 = 1 ' . $this->queryString . '
            ORDER BY NDV.create_number DESC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);

        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $key => $row) {

                $param = json_decode($row->param);

                array_push($data, array(
                    'id' => $row->id,
                    'mod_id' => $row->mod_id,
                    'create_number' => $row->create_number,
                    'in_out_date' => $row->in_out_date,
                    'full_name' => $row->full_name . $row->register . $row->age . $row->sex,
                    'is_work' => $param->work,
                    'partner' => $param->partner . ' ' . $row->expert_name,
                    'short_value' => $param->shortValue,
                    'expert_status' => $row->expert_status,
                    'expert' => $param->expert,
                    'is_where' => $param->where,
                    'category' => $param->category,
                    'close_type' => $param->closeType,
                    'description' => $row->payment,
                    'row_status' => $row->row_status,
                    'send_document' => $row->send_document,
                    'send_document_id' => $row->send_document_id
                ));
            }
        } else {
            array_push($data, array(
                'id' => 0,
                'mod_id' => 0,
                'create_number' => '',
                'in_out_date' => '',
                'full_name' => '',
                'is_work' => '',
                'partner' => '',
                'short_value' => '',
                'expert_status' => '',
                'expert' => '',
                'is_where' => '',
                'close_type' => '',
                'description' => '',
                'row_status' => '',
                'send_document' => '',
                'send_document_id' => 0
            ));
        }
        return array('data' => $data, 'search' => self::nifsDoctorViewSearchKeywordView_model());
    }
    
    public function nifsDoctorViewSearchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
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
            $this->nifsWorkData = $this->nifsWork->getData_model(array('selectedId' => $this->input->get('workId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsMotiveData->title . '</span>';
            $this->string .= form_hidden('workId', $this->input->get('workId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('motiveId')) {
            $this->nifsMotiveData = $this->nifsMotive->getData_model(array('selectedId' => $this->input->get('motiveId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsMotiveData->title . '</span>';
            $this->string .= form_hidden('motiveId', $this->input->get('motiveId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('partnerId')) {
            $this->partnerData = $this->partner->getData_model(array('selectedId' => $this->input->get('partnerId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->partnerData->title . '</span>';
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

        if ($this->input->get('isAgeInfinitive')) {
            $this->string .= '<span class="label label-default label-rounded">Нас тодорхойлох боломжгүй</span>';
            $this->string .= form_hidden('isAgeInfinitive', $this->input->get('isAgeInfinitive'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('shortValueId')) {
            $this->nifsCrimeShortValueData = $this->nifsCrimeShortValue->getData_model(array('selectedId' => $this->input->get('shortValueId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCrimeShortValueData->title . '</span>';
            $this->string .= form_hidden('shortValueId', $this->input->get('shortValueId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('whereId')) {
            $this->nifsWhereData = $this->nifsWhere->getData_model(array('selectedId' => $this->input->get('whereId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsWhereData->title . '</span>';
            $this->string .= form_hidden('whereId', $this->input->get('whereId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('catId')) {
            $this->cat = $this->category->getData_model(array('selectedId' => $this->input->get('catId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->cat->title . '</span>';
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
        }

        if ($this->input->get('closeTypeId')) {
            $this->nifsCloseType = $this->nifsCloseType->getData_model(array('selectedId' => $this->input->get('closeTypeId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->nifsCloseType->title . '</span>';
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

                $this->string .= ' <a href="javascript:;" onclick="_initMyNifsDoctorView({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
            }
        }

        return ($this->showResetBtn ? '<td class="_search-result-td"><div class="datagrid-btn-separator"></div></td><td class="_search-result-td"><form action="javascript:;" id="form-nifs-doctor-view"> Хайлтын үр дүн: ' . $this->string . '</form></td>' : '');
    }

}
