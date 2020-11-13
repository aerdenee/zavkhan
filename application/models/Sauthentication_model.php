<?php

class Sauthentication_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('SnifsCloseYear_model', 'nifsCloseYear');
        $this->load->model('Spermission_model', 'permission');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('Slanguage_model', 'language');
    }

    public function authentication_model() {

        if ($this->input->post('user') != '' and $this->input->post('password') != '') {

            $query = $this->db->query('
                SELECT 
                    U.id,
                    U.people_id,
                    U.email,
                    (case when (U.pic is null or U.pic = \'\') then \'default.svg\' else (case when (U.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', U.pic) end) end) as pic,
                    U.lname,
                    U.fname,
                    CONCAT(SUBSTRING(U.lname, 1, 1), \'.\', U.fname) AS full_name,
                    U.access_type_id,
                    U.visit_date,
                    U.department_id,
                    U.people_id
                FROM `gaz_user` AS U
                LEFT JOIN `gaz_hr_people` AS HP ON U.people_id = HP.id
                WHERE U.access_type_id NOT IN(1) AND LOWER(TRIM(U.user)) = LOWER(TRIM(\'' . mb_strtolower(trim($this->input->post('user'), ' '), 'UTF-8') . '\')) AND U.password = \'' . md5(trim($this->input->post('password'), ' ')) . '\'');

            if ($query->num_rows() > 0) {

                $user = $query->row();
                
                $this->db->where('id', $user->id);
                $this->db->update($this->db->dbprefix . 'user', array('visit_date' => date('Y-m-d H:i:s')));
                $this->setToken_model(array('userId' => $user->id, 'token' => $this->input->post('token')));
                $adminLang = $this->language->getData_model(array('selectedId' => $this->input->post('adminLangId')));

                $queryWorkCurrent = $this->db->query('
                    SELECT 
                        HPW.id,
                        HPW.people_id,
                        HPW.is_currenty,
                        HPW.department_id,
                        HPW.position_id,
                        HPW.rank_id,
                        HPW.title,
                        HPW.in_date,
                        HPW.out_date,
                        HPP.title AS position_title,
                        HPD.title AS department_title,
                        HPR.title AS rank_title
                    FROM `gaz_hr_people_work` AS HPW
                    INNER JOIN `gaz_hr_people_position` AS HPP ON HPP.id = HPW.position_id 
                    INNER JOIN `gaz_hr_people_department` AS HPD ON HPD.id = HPW.department_id 
                    INNER JOIN `gaz_hr_people_rank` AS HPR ON HPR.id = HPW.rank_id 
                    WHERE HPW.is_currenty = 1 AND HPW.people_id = ' . $user->people_id);

                if ($queryWorkCurrent->num_rows() > 0) {
                    foreach ($queryWorkCurrent->result() as $workKey => $workRow) {

                        if ($workRow->is_currenty == 1) {

                            $user->department_id = $workRow->department_id;
                            $user->department_title = $workRow->department_title;
                            $user->position_id = $workRow->position_id;
                            $user->position_title = $workRow->position_title;
                            $user->rank_id = $workRow->rank_id;
                            $user->rank_title = $workRow->rank_title;
                        }
                    }
                } else {
                    $user->department_id = $user->department_id;
                    $user->department_title = 'Бусад';
                    $user->position_id = 1;
                    $user->position_title = 'Бусад';
                    $user->rank_id = 1;
                    $user->rank_title = 'Бусад';
                }

                $closeYear = $this->nifsCloseYear->closeYear_model();
                $sessionData = array(
                    'adminUserId' => $user->id,
                    'adminDepartmentId' => $user->department_id,
                    'adminPositionId' => $user->position_id,
                    'adminRankId' => $user->rank_id,
                    'adminPeopleId' => $user->people_id,
                    'adminEmail' => $user->email,
                    'adminLname' => $user->lname,
                    'adminFname' => $user->fname,
                    'adminFullName' => $user->full_name,
                    'adminPositionTitle' => $user->position_title,
                    'adminDepartmentTitle' => $user->department_title,
                    'adminAccessTypeId' => $user->access_type_id,
                    'adminPic' => UPLOADS_USER_PATH . $user->pic,
                    'adminEmail' => $user->email,
                    'authentication' => $this->permission->getUserPermissionData_model(array('selectedId' => $user->id)),
                    'isLogin' => TRUE,
                    'adminCloseYear' => $closeYear,
                    'adminContsCloseYear' => $closeYear,
                    'adminCloseDate' => $this->nifsCloseYear->getCloseDate_model(array('closeYear' => $closeYear)),
                    'adminLangId' => $adminLang->id,
                    'adminLangCode' => $adminLang->code,
                    'adminLangTitle' => $adminLang->title,
                    'lastVisitDate' => $user->visit_date
                );

                return $sessionData;
            }
        }

        return array('isLogin' => FALSE, 'load_login_page' => FALSE);
    }

    public function passwordReset_model($param = array()) {

        $this->query = $this->db->query('
                SELECT 
                    U.id
                FROM `gaz_user` AS U
                WHERE U.access_type_id NOT IN(1) AND LOWER(TRIM(U.email)) = LOWER(TRIM(\'' . mb_strtolower(trim($this->input->post('email'), ' '), 'UTF-8') . '\'))');

        if ($this->query->num_rows() > 0) {

            $newPassword = generatePassword(array('lenght' => 4));
            $data = array('password' => md5($newPassword));
            $this->db->where('email', $param['email']);

            if ($this->db->update($this->db->dbprefix . 'user', $data)) {

                $this->load->library('email');

                $this->email->from('support@nifs.gov.mn', 'E-Nifs цахим систем');
                $this->email->to($param['email']);

                $this->email->subject('E-Nifs цахим систем - нууц үг');
                $this->email->message('Шинэ нууц үг: ' . $newPassword);

                $this->email->send();

                return array('status' => 'success', 'message' => 'Шинэ нууц үгийг бүртгэлтэй мэйл хаяг руу илгээсэн');
            }
        }

        return array('status' => 'error', 'message' => 'Бүртгэлгүй мэйл хаяг байна...');
    }

    public function setToken_model($param = array()) {


        $query = $this->db->query('
            SELECT 
                U.id,
                U.token
            FROM `gaz_user` AS U
            WHERE U.id = ' . $param['userId'] . ' AND U.token = \'' . $param['token'] . '\'');

        if ($query->num_rows() > 0) {

            return array('status' => 'success', 'message' => 'Бүртгэлтэй token байна');
        } else {

            $data = array('token' => $param['token']);
            $this->db->where('id', $param['userId']);

            if ($this->db->update($this->db->dbprefix . 'user', $data)) {
                return array('status' => 'success', 'message' => 'Token амжилттай бүртгэлээ');
            }
        }

        return array('status' => 'error', 'message' => 'Token бүртгэх үед алдаа гарлаа...');
    }

}
