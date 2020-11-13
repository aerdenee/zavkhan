<?php

class ShrContact_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();

        $this->load->model('Simage_model', 'simage');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');

        $this->modId = 77;
        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->isActiveDepartment = 'is_active_control';
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {
        $html = '';

        $html .= '<div>';
        $html .= '<div class="main-search">';
        $html .= '<div class="input-group">
                        <input type="text" class="form-control border-right-0" name="hrContactFname" placeholder="Нэрээр хайлт хийнэ...">
                        <span class="input-group-append">
                                <button class="btn bg-primary" type="button" name="hrContactButton">Хайх</button>
                        </span>
                </div>
                </div>
                                                        ';

        $html .= '<div style="clear:both; height:1px; width:100%;"></div>';
        $html .= '<div class="" id="window-hr-contact" style="display:block; width:100%;">' . self::listsData_model(array('keyword' => '')) . '</div>';
        $html .= '</div>';
        return $html;
    }

    public function listsData_model($param = array('modId' => 0, 'catId' => 0)) {


        $this->queryString = $this->getString = $this->html = '';

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND LOWER(HP.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
            $this->getString .= form_hidden('keyword', $param['keyword']);

            $this->html .= '<div style="margin:10px;">Түлхүүр үг: <span class="label label-default label-rounded">' . $param['keyword'] . '</span> <a href="javascript:;" onclick="_initHrContact({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a></div><div class="clearfix"></div>';
        }

        $this->queryDepartment = $this->db->query('
            SELECT 
                HPD.id, 
                HPD.parent_id, 
                HPD.title
            FROM `gaz_hr_people_department` AS HPD
            WHERE HPD.is_active = 1
            ORDER BY HPD.order_num ASC');
        if ($this->queryDepartment->num_rows() > 0) {

            foreach ($this->queryDepartment->result() as $keyDepartment => $rowDepartment) {

                $this->query = $this->db->query('
                    SELECT 
                        HP.id,
                        CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name,
                        (case when (HP.pic is null or HP.pic = \'\') then \'default.svg\' else (case when (HP.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', HP.pic) end) end) as pic,
                        HP.phone,
                        HP.fax,
                        HP.email,
                        HPP.title AS position_title,
                        HPD.title AS department_title
                    FROM `gaz_hr_people` AS HP
                    INNER JOIN `gaz_hr_people_position` AS HPP ON HPP.id = HP.position_id 
                    INNER JOIN `gaz_hr_people_department` AS HPD ON HPD.id = HP.department_id 
                    WHERE HP.department_id = ' . $rowDepartment->id . ' AND HP.phone != \'\' ' . $this->queryString . '
                    ORDER BY HPP.order_num ASC');

                if ($this->query->num_rows() > 0) {

                    $this->html .= '<h2 class="_hr-contact-department-title"><i class="icon-phone mr-2 icon-2x"></i> ' . $rowDepartment->title . '</h2>';

                    foreach ($this->query->result() as $key => $row) {

                        //$this->html .= '<div class="col-md-4">';
                        $this->html .= '<div class="_hr-contact">';
                        //$this->html .= '<div class="media">';
                        $this->html .= '<div class="_profile-image" style="background-image: url(' . UPLOADS_USER_PATH . $row->pic . ');"></div>';
                        $this->html .= '<div class="_information">';
                        $this->html .= '<h6>' . $row->full_name . '</h6>';
                        $this->html .= '<div class="_number">' . $row->phone . '</div>';
                        $this->html .= '<div class="clearfix"></div>';
                        $this->html .= '<span class="_hr-contact-department">' . $row->position_title . '</span>';
                        $this->html .= '</div>';

                        //$this->html .= '<div class="media-right media-middle">' . $row->phone . '</div>';
                        $this->html .= '</div>';
                        //$this->html .= '</div>';
                        //$this->html .= '</div>';
                    }
                }
            }
        }



        return $this->html;
    }

    public function mLists_model($param = array('modId' => 0, 'catId' => 0)) {


        $this->queryString = $this->getString = '';

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND LOWER(HP.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        $this->query = $this->db->query('
            SELECT 
                HP.id,
                HP.lname,
                HP.fname,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name,
                (case when (HP.pic is null or HP.pic = \'\') then \'default.svg\' else (case when (HP.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', HP.pic) end) end) as pic,
                HP.phone,
                HPP.title AS position_title,
                HPD.title AS department_title
            FROM `gaz_hr_people` AS HP
            INNER JOIN `gaz_hr_people_position` AS HPP ON HPP.id = HP.position_id 
            INNER JOIN `gaz_hr_people_department` AS HPD ON HPD.id = HP.department_id 
            WHERE HP.phone != \'\' ' . $this->queryString . '
            ORDER BY HP.fname ASC');

        if ($this->query->num_rows() > 0) {

            return $this->query->result();
        }

        return false;
    }

    public function mGetData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                HA.id,
                HA.mod_id,
                HA.cat_id,
                HA.department_id,
                HA.show_pic_outside,
                HA.show_pic_inside,
                (case when (HA.pic is null or HA.pic = \'\') then \'default.svg\' else concat(\'s_\', HA.pic) end) as pic,
                HA.show_title,
                HA.title,
                HA.link_title,
                HA.intro_text,
                HA.full_text,
                HA.page_title,
                HA.meta_key,
                HA.meta_desc,
                HA.h1_text,
                HA.show_date,
                HA.created_date,
                HA.modified_date,
                HA.is_active_date,
                HA.show_people,
                HA.people_id,
                HA.created_user_id,
                HA.modified_user_id,
                HA.show_comment,
                HA.comment_count,
                HA.show_click,
                HA.click,
                HA.click_real,
                HA.is_active,
                HA.order_num,
                HA.show_social,
                HA.param,
                HA.lang_id,
                HA.theme_layout_id
            FROM `gaz_hr_ads` AS HA
            WHERE HA.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }

        return false;
    }

    public function mListsData_model($param = array('modId' => 0, 'catId' => 0)) {


        $this->queryString = $this->getString = $this->html = '';

        $data = array();

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND LOWER(HP.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
        }

        $this->queryDepartment = $this->db->query('
            SELECT 
                HPD.id, 
                HPD.parent_id, 
                HPD.title
            FROM `gaz_hr_people_department` AS HPD
            WHERE HPD.is_active = 1
            ORDER BY HPD.order_num ASC');
        if ($this->queryDepartment->num_rows() > 0) {


            foreach ($this->queryDepartment->result() as $keyDepartment => $rowDepartment) {

                $this->query = $this->db->query('
                    SELECT 
                        HP.id,
                        CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name,
                        (case when (HP.pic is null or HP.pic = \'\') then \'default.svg\' else (case when (HP.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', HP.pic) end) end) as pic,
                        HP.phone,
                        HP.fax,
                        HP.email,
                        HPP.title AS position_title
                    FROM `gaz_hr_people` AS HP
                    INNER JOIN `gaz_hr_people_position` AS HPP ON HPP.id = HP.position_id 
                    WHERE HP.department_id = ' . $rowDepartment->id . ' AND HP.phone != \'\' ' . $this->queryString . '
                    ORDER BY HPP.order_num ASC');

                if ($this->query->num_rows() > 0) {

                    foreach ($this->query->result() as $key => $row) {
                        $row->pic = base_url(UPLOADS_USER_PATH . $row->pic);
                        $rowDepartment->people[$key] = $row;
                    }
                }

                array_push($data, $rowDepartment);
            }
        }



        return $data;
    }

    public function dashboardContactData_model($param = array('modId' => 0, 'catId' => 0)) {


        $this->queryString = $this->getString = $this->html = '';
        $sortType = array('DESC', 'ASC');
        $sortField = array('fname', 'lname', 'id', 'phone', 'order_num');
        $this->queryCount = $this->db->query('
                    SELECT 
                        HP.id
                    FROM `gaz_hr_people` AS HP
                    WHERE HP.phone != \'\'');
        
        $this->query = $this->db->query('
                    SELECT 
                        HP.id,
                        CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name,
                        (case when (HP.pic is null or HP.pic = \'\') then \'default.svg\' else (case when (HP.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', HP.pic) end) end) as pic,
                        HP.phone,
                        HP.fax,
                        HP.email,
                        HPP.title AS position_title,
                        HPD.title AS department_title
                    FROM `gaz_hr_people` AS HP
                    INNER JOIN `gaz_hr_people_position` AS HPP ON HPP.id = HP.position_id 
                    INNER JOIN `gaz_hr_people_department` AS HPD ON HPD.id = HP.department_id 
                    WHERE HP.phone != \'\'
                    ORDER BY HP.' . $sortField[rand(0, 4)] . ' ' . $sortType[rand(0, 1)] . ' 
                    LIMIT 0, 14');
        if ($this->query->num_rows() > 0) {

            $this->html .= '<div class="card">
                    <div class="card-header header-elements-inline">
                        <h6 class="card-title">Утасны жагсаалт</h6>
                        <div class="header-elements">

                            <span class="badge bg-danger-400 badge-pill"><a href="/shrContact/index/74">' . $this->queryCount->num_rows() . ' албан хаагч</a></span>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="chart mb-3" id="bullets"></div>

                        <ul class="media-list">';
            foreach ($this->query->result() as $key => $row) {

                $this->html .= '<li class="media">
                                <div class="mr-3 position-relative" style="width:36px; height:36px; border-radius: 50% !important; overflow:hidden; border:1px solid rgba(0,0,0,0.05);">
                                    <img src="' . UPLOADS_USER_PATH . $row->pic . '" width="36" height="36" alt="' . $row->full_name . '">
                                </div>

                                <div class="media-body">
                                    <div class="d-flex justify-content-between">
                                        <a href="javascript:;">' . $row->full_name . '</a>
                                        <span class="font-size-sm text-muted">' . $row->phone . '</span>
                                    </div>
                                    ' . $row->position_title . '
                                </div>
                            </li>';
            }
            $this->html .= '</ul>
                    </div>
                </div>';
        }

        return $this->html;
    }

}
