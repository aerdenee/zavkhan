<?php

class SdocClose_model extends CI_Model {

    private static $list = array();

    function __construct() {

        parent::__construct();

        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Suser_model', 'user');
        $this->load->model('SreportMenu_model', 'sreportMenu');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('ShrPeoplePosition_model', 'hrPeoplePosition');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Schart_model', 'chart');
        $this->load->model('Sdoc_model', 'sdoc');


        $this->modId = 17;
    }

    public function listsCount_model($param = array()) {

        $queryString = $query = '';

        if ($param['docTypeId'] != 0) {
            $queryString .= ' AND D.doc_type_id = ' . $param['docTypeId'];
        }

        if ($param['docNumber'] != '') {

            $queryString .= ' AND D.doc_number LIKE (\'%' . $param['docNumber'] . '%\')';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(D.doc_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(D.doc_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(D.doc_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(D.doc_date)';
        }

        if ($param['keyword'] != '') {

            $queryString .= ' AND LOWER(D.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
        }

        if ($param['type'] == 'doc-out') {

            $queryString .= ' AND DD.to_department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ')';

            if ($param['departmentId'] != 0) {

                $queryString .= ' AND DD.to_department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            }

            if ($param['partnerId'] != 0) {

                $queryString .= ' AND DD.to_partner_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['partnerId']) . ')';
            }

            if ($param['peopleId'] != 0) {

                $queryString .= ' AND DD.to_people_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['peopleId']) . ')';
            }
        } else if ($param['type'] == 'doc-in') {

            $queryString .= ' AND DD.from_department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ')';

            if ($param['departmentId'] != 0) {

                $queryString .= ' AND DD.from_department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            }

            if ($param['partnerId'] != 0) {

                $queryString .= ' AND DD.from_partner_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['partnerId']) . ')';
            }

            if ($param['peopleId'] != 0) {

                $queryString .= ' AND DD.from_people_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['peopleId']) . ')';
            }
        }

        $query = $this->db->query('
                SELECT 
                    DD.id
                FROM `gaz_doc_detail` AS DD
                INNER JOIN `gaz_doc` AS D ON DD.doc_id = D.id
                WHERE D.lang_id = ' . $this->session->userdata['adminLangId'] . $queryString);

        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $data = array();
        $queryString = $query = '';

        if ($param['docTypeId'] != 0) {
            $queryString .= ' AND D.doc_type_id = ' . $param['docTypeId'];
        }

        if ($param['docNumber'] != '') {

            $queryString .= ' AND D.doc_number LIKE (\'%' . $param['docNumber'] . '%\')';
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(D.doc_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(D.doc_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryString .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(D.doc_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryString .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(D.doc_date)';
        }

        if ($param['keyword'] != '') {

            $queryString .= ' AND LOWER(D.description) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
        }

        if ($param['type'] == 'doc-out') {

            if ($param['departmentId'] != 0) {

                $queryString .= ' AND DD.to_department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            }

            if ($param['partnerId'] != 0) {

                $queryString .= ' AND DD.to_partner_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['partnerId']) . ')';
            }

            if ($param['peopleId'] != 0) {

                $queryString .= ' AND DD.to_people_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['peopleId']) . ')';
            }
            
            $query = $this->db->query('
                SELECT 
                    DD.id,
                    DD.doc_id,
                    IF(DD.from_department_id = 51, P.title, CONCAT(HPD.title, \' \', SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname)) AS department,
                    DD.created_user_id,
                    D.doc_date,
                    D.doc_number,
                    D.description
                FROM `gaz_doc_detail` AS DD
                INNER JOIN `gaz_doc` AS D ON DD.doc_id = D.id
                LEFT JOIN `gaz_hr_people_department` AS HPD ON DD.from_department_id = HPD.id
                LEFT JOIN `gaz_partner` AS P ON DD.from_partner_id = P.id
                LEFT JOIN `gaz_hr_people` AS HP ON DD.from_people_id = HP.id
                WHERE DD.to_department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ') AND D.lang_id = ' . $this->session->userdata['adminLangId'] . $queryString . '
                ORDER BY D.id DESC
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
            
        } else if ($param['type'] == 'doc-in') {

            if ($param['departmentId'] != 0) {

                $queryString .= ' AND DD.from_department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            }

            if ($param['partnerId'] != 0) {

                $queryString .= ' AND DD.from_partner_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['partnerId']) . ')';
            }

            if ($param['peopleId'] != 0) {

                $queryString .= ' AND DD.from_people_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['peopleId']) . ')';
            }

            $query = $this->db->query('
                SELECT 
                    DD.id,
                    DD.doc_id,
                    IF(DD.from_department_id = 51, P.title, CONCAT(HPD.title, \' \', SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname)) AS department,
                    DD.created_user_id,
                    D.doc_date,
                    D.doc_number,
                    D.description
                FROM `gaz_doc_detail` AS DD
                INNER JOIN `gaz_doc` AS D ON DD.doc_id = D.id
                LEFT JOIN `gaz_hr_people_department` AS HPD ON DD.from_department_id = HPD.id
                LEFT JOIN `gaz_partner` AS P ON DD.from_partner_id = P.id
                LEFT JOIN `gaz_hr_people` AS HP ON DD.from_people_id = HP.id
                WHERE DD.from_department_id IN (' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($this->session->adminDepartmentId) . ') AND D.lang_id = ' . $this->session->userdata['adminLangId'] . $queryString . '
                ORDER BY D.id DESC
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);
        }

        if ($query->num_rows() > 0) {

            $i = 0;
            foreach ($query->result() as $key => $row) {

                array_push($data, array(
                    'id' => $row->id,
                    'num' => ++$i,
                    'doc_id' => $row->doc_id,
                    'doc_number' => $row->doc_number,
                    'doc_date' => $row->doc_date,
                    'doc_number_date' => $row->doc_number . '/' . $row->doc_date,
                    'department' => $row->department,
                    'created_user_id' => $row->created_user_id,
                    'description' => $row->description
                ));
            }
        }
        return array('data' => $data);
    }

    public function getData_model($param = array()) {
        $query = $this->db->query('
            SELECT 
                D.doc_type_id,
                D.doc_date,
                D.doc_number,
                D.description,
                D.page_number,
                D.is_reply,
                D.reply_date
            FROM `gaz_doc` AS D
            WHERE D.id = ' . $param['selectedId']);
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    public function update_model($param = array()) {

        $dataDocDetail = array('doc_close_id' => $this->input->post('docCloseId'));
        $this->db->where('id', $this->input->post('docDetialId'));
        if ($this->db->update($this->db->dbprefix . 'doc_detail', $dataDocDetail)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

}
