<?php

class SnifsSearch_model extends CI_Model {

    private static $list = array();

    function __construct() {

        parent::__construct();
    }

    public function listsCount_model($param = array()) {

        $queryCrime = $queryStringCrime = '';
        $queryAnatomy = $queryStringAnatomy = '';
        $queryFileFolder = $queryStringFileFolder = '';
        $queryExtra = $queryStringExtra = '';
        $queryDoctorView = $queryStringDoctorView = '';
        $queryEconomy = $queryStringEconomy = '';

        if ($param['protocolNumber'] != '') {
            $queryStringCrime .= ' AND LOWER(NC.protocol_number) LIKE LOWER(\'%' . $param['protocolNumber'] . '%\')';

            $queryStringAnatomy .= ' AND LOWER(NA.protocol_number) LIKE LOWER(\'%' . $param['protocolNumber'] . '%\')';

            $queryStringFileFolder .= ' AND LOWER(NFF.protocol_number) LIKE LOWER(\'%' . $param['protocolNumber'] . '%\')';

            $queryStringExtra .= ' AND LOWER(NEX.protocol_number) LIKE LOWER(\'%' . $param['protocolNumber'] . '%\')';

            $queryStringDoctorView .= ' AND LOWER(NDV.protocol_number) LIKE LOWER(\'%' . $param['protocolNumber'] . '%\')';
            
            $queryStringEconomy .= ' AND LOWER(NE.protocol_number) LIKE LOWER(\'%' . $param['protocolNumber'] . '%\')';
        }
        
        if ($param['keyword'] != '') {
            $queryStringCrime .= ' 
            AND (LOWER(NC.question) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
            OR LOWER(NC.crime_object) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
            OR LOWER(NC.crime_value) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
            OR LOWER(NC.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')
            OR LOWER(NC.short_info) LIKE LOWER(\'%' . $param['keyword'] . '%\')
            OR LOWER(NC.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')
            OR LOWER(NC.description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';

            $queryStringAnatomy .= ' AND (
            LOWER(NA.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NA.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NA.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NA.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NA.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NA.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
            LOWER(NA.short_value) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
            LOWER(NA.address) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';

            $queryStringFileFolder .= ' AND (
            LOWER(NFF.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NFF.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NFF.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NFF.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NFF.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';

            $queryStringExtra .= ' AND (
            LOWER(NEX.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NEX.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NEX.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NEX.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
            LOWER(NEX.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NEX.crime_value) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NEX.question) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NEX.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';

            $queryStringDoctorView .= ' AND (
            LOWER(NDV.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NDV.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NDV.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NDV.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
            LOWER(NDV.description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
            
            $queryStringEconomy .= ' AND (
            LOWER(NE.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NE.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NE.protocol_value) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NE.question) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        $queryCrime = '
                SELECT 
                    NC.id
                FROM `gaz_nifs_crime` AS NC
                WHERE 1 = 1 ' . $queryStringCrime;


        $queryAnatomy = '
                SELECT 
                    NA.id
                FROM `gaz_nifs_anatomy` AS NA
                WHERE 1 = 1 ' . $queryStringAnatomy;

        $queryFileFolder = '(
                SELECT 
                    NFF.id
                FROM `gaz_nifs_file_folder` AS NFF
                WHERE 1 = 1 ' . $queryStringFileFolder . ')';

        $queryExtra = '(
                SELECT 
                    NEX.id
                FROM `gaz_nifs_extra` AS NEX
                WHERE 1 = 1 ' . $queryStringExtra . ')';

        $queryDoctorView = '(
                SELECT 
                    NDV.id
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE 1 = 1 ' . $queryStringDoctorView . ')';

        $queryEconomy = '(
                SELECT 
                    NE.id
                FROM `gaz_nifs_economy` AS NE
                WHERE 1 = 1 ' . $queryStringEconomy . ')';

        $query = $this->db->query(
                $queryCrime .
                ' UNION ' .
                $queryAnatomy .
                ' UNION ' .
                $queryFileFolder .
                ' UNION ' .
                $queryExtra .
                ' UNION ' .
                $queryDoctorView .
                ' UNION ' .
                $queryEconomy);

        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {
        $data = array();
        $queryCrime = $queryStringCrime = '';
        $queryAnatomy = $queryStringAnatomy = '';
        $queryFileFolder = $queryStringFileFolder = '';
        $queryExtra = $queryStringExtra = '';
        $queryDoctorView = $queryStringDoctorView = '';
        $queryEconomy = $queryStringEconomy = '';

        if ($param['protocolNumber'] != '') {
            $queryStringCrime .= ' AND LOWER(NC.protocol_number) LIKE LOWER(\'%' . $param['protocolNumber'] . '%\')';

            $queryStringAnatomy .= ' AND LOWER(NA.protocol_number) LIKE LOWER(\'%' . $param['protocolNumber'] . '%\')';

            $queryStringFileFolder .= ' AND LOWER(NFF.protocol_number) LIKE LOWER(\'%' . $param['protocolNumber'] . '%\')';

            $queryStringExtra .= ' AND LOWER(NEX.protocol_number) LIKE LOWER(\'%' . $param['protocolNumber'] . '%\')';

            $queryStringDoctorView .= ' AND LOWER(NDV.protocol_number) LIKE LOWER(\'%' . $param['protocolNumber'] . '%\')';
            
            $queryStringEconomy .= ' AND LOWER(NE.protocol_number) LIKE LOWER(\'%' . $param['protocolNumber'] . '%\')';
        }
        
        if ($param['keyword'] != '') {
            $queryStringCrime .= ' 
            AND (LOWER(NC.question) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
            OR LOWER(NC.crime_object) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
            OR LOWER(NC.crime_value) LIKE LOWER(\'%' . $param['keyword'] . '%\') 
            OR LOWER(NC.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\')
            OR LOWER(NC.short_info) LIKE LOWER(\'%' . $param['keyword'] . '%\')
            OR LOWER(NC.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\')
            OR LOWER(NC.description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';

            $queryStringAnatomy .= ' AND (
            LOWER(NA.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NA.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NA.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NA.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NA.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NA.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
            LOWER(NA.short_value) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
            LOWER(NA.address) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';

            $queryStringFileFolder .= ' AND (
            LOWER(NFF.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NFF.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NFF.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NFF.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NFF.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';

            $queryStringExtra .= ' AND (
            LOWER(NEX.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NEX.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NEX.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NEX.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
            LOWER(NEX.register) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NEX.crime_value) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NEX.question) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NEX.close_description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';

            $queryStringDoctorView .= ' AND (
            LOWER(NDV.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NDV.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NDV.phone) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NDV.expert_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR
            LOWER(NDV.description) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
            
            $queryStringEconomy .= ' AND (
            LOWER(NE.agent_name) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NE.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NE.protocol_value) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR 
            LOWER(NE.question) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        $queryCrime = '
                (SELECT 
                    NC.id,
                    NC.mod_id,
                    NC.department_id,
                    NC.create_number,
                    CONCAT(\'Криминалистик №: \', NC.create_number) AS create_number_html,
                    CONCAT("И: ", DATE(NC.in_date), "<br>", "Д: ", DATE(NC.out_date)) AS in_out_date,
                    NC.in_date,
                    NC.out_date,
                    CONCAT(NC.crime_value, \' \', NC.short_info) AS full_name,
                    CONCAT(DATE(NC.close_date), \' \', NC.close_description) AS close_info,
                    NC.description AS description,
                    NC.param,
                    NC.agent_name AS partner_people
                FROM `gaz_nifs_crime` AS NC
                WHERE 1 = 1 ' . $queryStringCrime . ') ';


        $queryAnatomy = '
                (SELECT 
                    NA.id,
                    NA.mod_id,
                    NA.department_id,
                    NA.create_number,
                    CONCAT(\'Задлан №: \', NA.create_number) AS create_number_html,
                    CONCAT("И: ", DATE(NA.in_date), "<br>", "Д: ", DATE(NA.out_date)) AS in_out_date,
                    DATE(NA.in_date) AS in_date,
                    DATE(NA.out_date) AS out_date,
                    CONCAT(IF(NA.lname != \'\', CONCAT(NA.lname, "-н ", "<strong>", NA.fname, "</strong>"), NA.fname), \' \', (CASE 
                            WHEN (NA.is_age_infinitive = 1) THEN \', нас тодорхойгүй\'
                            WHEN (NA.is_age_infinitive = 0 AND NA.age < 1) THEN CONCAT(", ", CONVERT(SUBSTRING(NA.age, 3, 2),UNSIGNED INTEGER), " сартай")
                            ELSE IF(NA.is_age_infinitive = 0 AND NA.age >= 1, CONCAT(", ", CONVERT(NA.age,UNSIGNED INTEGER), " настай"), "")
                    END), \' \', IF(NA.sex = 1, \', эр\', \', эм\'), \' \', IF(NA.register != \'\', CONCAT(\', \', NA.register, \'\'), \'\')) AS full_name,
                    CONCAT(DATE(NA.end_date), "<br>", NA.close_description) AS close_info,
                    NA.description AS description,
                    NA.param,
                    NA.expert_name AS partner_people
                FROM `gaz_nifs_anatomy` AS NA
                WHERE 1 = 1 ' . $queryStringAnatomy . ')';

        $queryFileFolder = '(
                SELECT 
                    NFF.id,
                    NFF.mod_id,
                    NFF.department_id,
                    NFF.create_number,
                    CONCAT(\'Хавтаст хэрэг №: \', NFF.create_number) AS create_number_html,
                    CONCAT("И:", DATE(NFF.in_date), "<br>", "И:", DATE(NFF.out_date)) AS in_out_date,
                    DATE(NFF.in_date) AS in_date,
                    DATE(NFF.out_date) AS out_date,
                    CONCAT(IF(NFF.lname != \'\', CONCAT(NFF.lname, \'-н \'), \'\'), \' <span style="">\', NFF.fname, \'</span>\') AS full_name,
                    CONCAT(DATE(NFF.close_date), "<br>", NFF.close_description) AS close_info,
                    NFF.description AS description,
                    NFF.param,
                    NFF.agent_name AS partner_people
                FROM `gaz_nifs_file_folder` AS NFF
                WHERE 1 = 1 ' . $queryStringFileFolder . ')';

        $queryExtra = '(
                SELECT 
                    NEX.id,
                    NEX.mod_id,
                    NEX.department_id,
                    NEX.create_number,
                    CONCAT(\'Тусгай №: \', NEX.create_number) AS create_number_html,
                    CONCAT("И:", DATE(NEX.in_date), "<br>", "И:", DATE(NEX.out_date)) AS in_out_date,
                    DATE(NEX.in_date) AS in_date,
                    DATE(NEX.out_date) AS out_date,
                    CONCAT(IF(NEX.lname != \'\', CONCAT(NEX.lname, "-н ", "<strong>", NEX.fname, "</strong>"), NEX.fname), \' \',
                    (CASE 
                        WHEN (NEX.is_age_infinitive = 1) THEN \'\'
                        WHEN (NEX.is_age_infinitive = 0 AND NEX.age < 1) THEN CONCAT(", ", CONVERT(SUBSTRING(NEX.age, 3, 2),UNSIGNED INTEGER), " сартай")
                        ELSE IF(NEX.is_age_infinitive = 0 AND NEX.age >= 1, CONCAT(", ", CONVERT(NEX.age,UNSIGNED INTEGER), " настай"), "")
                    END), \' \',
                    IF(NEX.sex = 1, \', эр\', \', эм\'), \' \', NEX.crime_value) AS full_name,
                    CONCAT(DATE(NEX.close_date), "<br>", NEX.close_description) AS close_info,
                    NEX.description,
                    NEX.param,
                    NEX.agent_name AS partner_people
                FROM `gaz_nifs_extra` AS NEX
                WHERE 1 = 1 ' . $queryStringExtra . ')';
        
        $queryDoctorView = '(
                SELECT 
                    NDV.id,
                    NDV.mod_id,
                    NDV.department_id,
                    NDV.create_number,
                    CONCAT(\'Эмчийн үзлэг №: \', NDV.create_number) AS create_number_html,
                    CONCAT("И:", DATE(NDV.in_date), "<br>", "И:", DATE(NDV.out_date)) AS in_out_date,
                    DATE(NDV.in_date) AS in_date,
                    DATE(NDV.out_date) AS out_date,
                    CONCAT(IF(NDV.lname != \'\', CONCAT(NDV.lname, "-н ", "<strong>", NDV.fname, "</strong>"), NDV.fname), \' \', IF(NDV.sex = 1, \', эр\', \', эм\'), \' \', NDV.register) AS full_name,
                    DATE(NDV.close_date) AS close_info,
                    NDV.description,
                    NDV.param,
                    NDV.expert_name AS partner_people
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE 1 = 1 ' . $queryStringDoctorView . ')';
        
        $queryEconomy = '(
                SELECT 
                    NE.id,
                    NE.mod_id,
                    NE.department_id,
                    NE.create_number,
                    CONCAT(\'Эдийн засаг №: \', NE.create_number) AS create_number_html,
                    CONCAT("И:", DATE(NE.in_date), "<br>", "И:", DATE(NE.out_date)) AS in_out_date,
                    DATE(NE.in_date) AS in_date,
                    DATE(NE.out_date) AS out_date,
                    NE.protocol_value AS full_name,
                    CONCAT(DATE(NE.close_date), "<br>", NE.close_description) AS close_info,
                    NE.description,
                    NE.param,
                    NE.agent_name AS partner_people
                FROM `gaz_nifs_economy` AS NE
                WHERE 1 = 1 ' . $queryStringEconomy . ')';

        $query = $this->db->query('
            SELECT
                UNIONALL.id,
                UNIONALL.mod_id,
                UNIONALL.department_id,
                UNIONALL.create_number,
                UNIONALL.create_number_html,
                UNIONALL.in_out_date,
                UNIONALL.in_date,
                UNIONALL.out_date,
                UNIONALL.full_name,
                UNIONALL.close_info,
                UNIONALL.description,
                UNIONALL.param,
                HPD.title AS department_title,
                UNIONALL.partner_people
            FROM (' .
                $queryCrime .
                ' UNION ' .
                $queryAnatomy .
                ' UNION ' .
                $queryFileFolder .
                ' UNION ' .
                $queryExtra .
                ' UNION ' .
                $queryDoctorView .
                ' UNION ' .
                $queryEconomy . ') AS UNIONALL
                LEFT JOIN `gaz_hr_people_department` AS HPD ON UNIONALL.department_id = HPD.id
                ORDER BY UNIONALL.in_date DESC 
                LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);

        if ($query->num_rows() > 0) {

            $i = 0;
            foreach ($query->result() as $key => $row) {

                $param = json_decode($row->param);
                array_push($data, array(
                    'number' => ++$i,
                    'id' => $row->id,
                    'mod_id' => $row->mod_id,
                    'department_id' => $row->department_id,
                    'create_number' => $row->create_number,
                    'create_number_html' => '<strong>' . $row->create_number_html . '</strong><br>' . $row->department_title,
                    'in_out_date' => $row->in_out_date,
                    'in_date' => $row->in_date,
                    'out_date' => $row->out_date,
                    'full_name' => $row->full_name,
                    'partner' => $param->partner . '<br>' . $row->partner_people,
                    'description' => $row->description,
                    'close_info' => $row->close_info));
            }
        }
        return array('data' => $data, 'search' => self::allSearchKeywordView_model());
    }

    public function allSearchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
        $string = '';
        $showResetBtn = FALSE;

        if ($this->input->get('keyword')) {
            $string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $string .= form_hidden('keyword', $this->input->get('keyword'));
            $showResetBtn = TRUE;
        }

        if ($this->input->get('protocolNumber')) {
            $string .= '<span class="label label-default label-rounded">Дугаар: ' . $this->input->get('protocolNumber') . '</span>';
            $string .= form_hidden('protocolNumber', $this->input->get('protocolNumber'));
            $showResetBtn = TRUE;
        }


        if ($showResetBtn) {
            $string .= ' <a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }


        return ($showResetBtn ? '<div class="_search-result-inner"><form action="javascript:;" id="form-nifs-anatomy"> Хайлтын үр дүн: ' . $string . '</form></div>' : '');
    }

}
