<?php

class Sclass_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function addFormData_model() {
        return (object)array(
            'id' => 0,
            'mod_id' => 0,
            'cat_id' => 0,
            'teacher_id' => 0,
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d'),
            'price' => 0,
            'intro_text' => '',
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'is_active' => 1,
            'order_num' => getOrderNum(array('table' => 'class', 'field' => 'order_num'))
        );
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cat_id,
                teacher_id,
                start_date,
                end_date,
                price,
                intro_text,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id,
                is_active,
                order_num
            FROM `' . $this->db->dbprefix . 'class`
            WHERE id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function listsCount_model($param = array()) {
        $this->queryString = '';

        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND CD.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND CD.created_user_id = -1';
        }

//        if ($param['keyword'] != '') {
//            $this->queryString .= ' AND LOWER(C.title) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
//        }

        $this->query = $this->db->query('
            SELECT 
                C.id
            FROM `' . $this->db->dbprefix . 'class` AS C 
            WHERE 1 = 1 ' . $this->queryString . ' AND C.mod_id = ' . $param['modId'] . ' 
            ORDER BY C.id DESC');

        $this->result = $this->query->result();

        return count($this->result);
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '')) {

        $this->queryString = $this->getString = '';

        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND CD.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND CD.created_user_id = -1';
        }

//        if ($param['keyword'] != '') {
//            $this->queryString .= ' AND LOWER(CD.title) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
//            $this->getString .= form_hidden('keyword', $param['keyword']);
//        }

        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.mod_id,
                C.cat_id,
                C.teacher_id,
                CONCAT(SUBSTRING(U.lname_mn, 1, 1), \'.\', U.fname_mn) AS teacher_full_name,
                C.start_date,
                C.end_date,
                C.price,
                C.intro_text,
                C.created_date,
                C.modified_date,
                C.created_user_id,
                C.modified_user_id,
                C.is_active,
                C.order_num,
                CAT.title_mn  AS category_title
            FROM `' . $this->db->dbprefix . 'class` AS C
            INNER JOIN `gaz_category` CAT ON C.cat_id = CAT.id
            INNER JOIN `gaz_user` U ON C.teacher_id = U.id
            WHERE C.mod_id = ' . $param['modId'] . $this->queryString . ' 
            ORDER BY C.id DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        $this->data = array();
        $this->data['html'] = '';
        $this->data['html'] .= form_open('', array('class' => 'form-horizontal', 'id' => 'form-reservation-export', 'enctype' => 'multipart/form-data'));
        $this->data['html'] .= form_hidden('modId', $param['modId']);
        $this->data['html'] .= form_hidden('limit', $param['limit']);
        $this->data['html'] .= form_hidden('page', $param['page']);
        $this->data['html'] .= form_hidden('our[\'create\']', $this->auth->our->create);
        $this->data['html'] .= form_hidden('our[\'read\']', $this->auth->our->read);
        $this->data['html'] .= form_hidden('our[\'update\']', $this->auth->our->update);
        $this->data['html'] .= form_hidden('our[\'delete\']', $this->auth->our->delete);
        $this->data['html'] .= form_hidden('your[\'read\']', $this->auth->your->read);
        $this->data['html'] .= form_hidden('your[\'update\']', $this->auth->your->update);
        $this->data['html'] .= form_hidden('your[\'delete\']', $this->auth->your->delete);
        $this->data['html'] .= $this->getString;

        $this->data['html'] .= '<div class="panel panel-flat">';
        $this->data['html'] .= '<div class="panel-heading">';
        $this->data['html'] .= '<h5 class="panel-title">' . $param['title'] . '</h5>';
        $this->data['html'] .= '<div class="heading-elements">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';
        $this->data['html'] .= '<li>' . anchor(Sclass::$path . 'index/' . $param['modId'], '<i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i>', array()) . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="panel-body" style="padding-bottom:5px;">';
        $this->data['html'] .= '<div class="pull-left">' . self::searchKeywordView_model(array('modId' => $param['modId'])) . '</div>';
        $this->data['html'] .= '<div class="pull-right">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';

        if ($this->auth->our->create == 1) {
            $this->data['html'] .= '<li style="padding-right:10px;"><a href="javascript:;" class="btn btn-info btn-rounded btn-xs" onclick="_formClass({modId:' . $param['modId'] . ',elem:this, mode:\'add\', id:0});"><i class="fa fa-plus"></i> Нэмэх</a>';
            //$this->data['html'] .= anchor(Sclass::$path . 'add/' . $param['modId'], '<i class="fa fa-plus"></i> Нэмэх', 'class="btn btn-info btn-rounded btn-xs"') . '</li>';
        } else {
            $this->data['html'] .= '<li style="padding-right:10px;"><a href="javascript:;" class="btn btn-info btn-rounded btn-xs" disabled="disabled"><i class="fa fa-plus"></i> Нэмэх</a></li>';
        }

        //$this->data['html'] .= '<li style="padding-right:10px;">' . form_button('send', '<i class="fa fa-download"></i> Экспорт', 'class="btn btn-info btn-rounded btn-xs" onclick="_export(this);" ' . (($this->query->num_rows() != 0) ? ' disabled="disabled"' : ''), 'button') . '</li>';
        $this->data['html'] .= '<li>' . form_button('send', '<i class="fa fa-search"></i> хайх', 'class="btn btn-info btn-rounded btn-xs" onclick="_advensedSearch({elem:this, modId:' . $param['modId'] . '});"', 'button') . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';

        if ($this->query->num_rows() > 0) {

            $this->data['html'] .= '<div class="table-responsive">';
            $this->data['html'] .= '<table class="table table-bordered table-custom">';
            $this->data['html'] .= '<thead>';
            $this->data['html'] .= '<tr>';
            $this->data['html'] .= '<th style="width:30px;">#</th>';
            $this->data['html'] .= '<th>Гарчиг</th>';
            $this->data['html'] .= '<th style="width:120px;">Хичээл заах багш</th>';
            $this->data['html'] .= '<th style="width:100px;">Эхлэх</th>';
            $this->data['html'] .= '<th style="width:100px;">Дуусах</th>';
            $this->data['html'] .= '<th style="width:100px;">Төлбөр</th>';
            $this->data['html'] .= '<th style="width:100px;">Төлөв</th>';
            $this->data['html'] .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';

            $i = 1;
            foreach ($this->query->result() as $row) {

                $this->data['html'] .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '">';
                $this->data['html'] .= '<td>' . $i . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row">' . $row->category_title . '<div>' . word_limiter($row->intro_text, 10) . '</div></td>';
                $this->data['html'] .= '<td class="context-menu-selected-row">' . $row->teacher_full_name . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row text-center">' . $row->start_date . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row text-center">' . $row->end_date . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row text-center">' . $row->price . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row text-center">' . (intval($row->is_active) == 1 ? '<span class="label bg-teal dropdown-toggle">Идэвхтэй</span>' : '<span class="label label-danger">Идэвхгүй</span>') . '</td>';
                $this->data['html'] .= '<td class="text-center">';
                $this->data['html'] .= '<ul class="icons-list">';

                if (($this->auth->our->update == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->update == 1 and $row->created_user_id != $this->session->adminUserId)) {
                    $this->data['html'] .= '<li><a href="javascript:;" onclick="_formClass({modId: ' . $param['modId'] . ', elem: this, mode: \'edit\', id: ' . $row->id . '});"><i class="icon-pencil7"></i></a></li>';
                } else {
                    $this->data['html'] .= '<li><a href="javascript:;" class="disabled"><i class="icon-pencil7 disabled"></i></a></li>';
                }
                if (($this->auth->our->delete == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->delete == 1 and $row->created_user_id != $this->session->adminUserId)) {
                    $this->data['html'] .= '<li><a href="javascript:;" onclick="_removeItem({modId:' . $row->mod_id . ', id:' . $row->id . ', elem:this});"><i class="icon-trash"></i></a></li>';
                } else {
                    $this->data['html'] .= '<li><a href="javascript:;" class="disabled"><i class="icon-trash"></i></a></li>';
                }
                $this->data['html'] .= '</ul>';
                $this->data['html'] .= '</td>';
                $this->data['html'] .= '</tr>';
                $i++;
            }

            $this->data['html'] .= '</tbody>';
            $this->data['html'] .= '</table>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<div class="panel-footer">';
            $this->data['html'] .= '<div class="heading-elements">';
            $this->data['html'] .= '<span class="heading-text text-semibold"></span>';
            $this->data['html'] .= '<div class="heading-btn pull-right">';
            $this->data['html'] .= $param['paginationHtml'];
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<a class="heading-elements-toggle"><i class="icon-more"></i></a>';
            $this->data['html'] .= '</div>';
        } else {

            $this->data['html'] .= '<div class="panel-body">';
            $this->data['html'] .= '<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Бичлэг байхгүй</div>';
            $this->data['html'] .= '</div>';
        }


        $this->data['html'] .= '</div>';
        $this->data['html'] .= form_close();
        return $this->data['html'];
    }

    public function insert_model($param = array()) {
        $this->data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'start_date' => $this->input->post('startDate'),
                'end_date' => $this->input->post('endDate'),
                'price' => $this->input->post('price'),
                'teacher_id' => $this->input->post('teacherId'),
                'intro_text' => $this->input->post('introText'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => '0000-00-00 00:00:00',
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'is_active' => $this->input->post('isActive'),
                'order_num' => $this->input->post('orderNum')
            )
        );
        $result = $this->db->insert_batch($this->db->dbprefix . 'class', $this->data);

        if ($result) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {
        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'start_date' => $this->input->post('startDate'),
            'end_date' => $this->input->post('endDate'),
            'price' => $this->input->post('price'),
            'teacher_id' => $this->input->post('teacherId'),
            'intro_text' => $this->input->post('introText'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'is_active' => $this->input->post('isActive'),
            'order_num' => $this->input->post('orderNum')
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'class', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {

        foreach ($this->input->post('id') as $key => $row) {
            $this->db->where('id', $this->input->post('id[' . $key . ']'));
            $this->db->delete($this->db->dbprefix . 'class');
        }

        return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.mod_id,
                C.cat_id,
                CAT.title_mn AS title,
                C.intro_text,
                C.created_date,
                C.modified_date,
                C.created_user_id,
                C.modified_user_id,
                C.is_active,
                C.order_num,
                C.start_date,
                C.end_date,
                C.price
            FROM `' . $this->db->dbprefix . 'class` AS C
            INNER JOIN `' . $this->db->dbprefix . 'category` AS CAT ON C.cat_id = CAT.id
            WHERE C.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

    public function searchKeywordView_model($param = array('modId' => 0)) {
        $this->string = '';
        $this->showResetBtn = FALSE;
        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            $this->string .= ' <a href="' . Sclass::$path . 'index/' . $param['modId'] . '"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return $this->string;
    }

    public function controlClassListDropdown_model($param = array('modId' => 0, 'selectedId' => 0, 'required' => true)) {
        
        $this->isDisabled = $this->isRequired = '';
        if (isset($param['isDisabled']) and $param['isDisabled'] == true) {
            $this->isDisabled = 'disabled="disabled"';
        }
        if (isset($param['isRequired']) and $param['isRequired'] == true) {
            $this->isRequired = 'required="required"';
        }
        
        if (isset($param['studentId'])) {
            $this->query = $this->db->query('
                SELECT 
                    C.id,
                    C.start_date,
                    C.end_date,
                    CAT.title_mn AS category_title,
                    C.price
                FROM `' . $this->db->dbprefix . 'student_class` AS SC 
                INNER JOIN `' . $this->db->dbprefix . 'class` AS C ON SC.class_id = C.id
                INNER JOIN `' . $this->db->dbprefix . 'category` AS CAT ON C.cat_id = CAT.id
                WHERE SC.student_id = ' . $param['studentId'] . ' 
                GROUP BY SC.class_id');
        } else {
            $this->query = $this->db->query('
                SELECT 
                    C.id,
                    C.mod_id,
                    C.cat_id,
                    C.start_date,
                    C.end_date,
                    C.intro_text,
                    C.created_date,
                    C.modified_date,
                    C.created_user_id,
                    C.modified_user_id,
                    C.is_active,
                    C.order_num,
                    C.price,
                    CAT.title_mn  AS category_title
                FROM `' . $this->db->dbprefix . 'class` AS C
                INNER JOIN `' . $this->db->dbprefix . 'category` CAT ON C.cat_id = CAT.id
                WHERE C.is_active = 1 AND C.mod_id = ' . $param['modId'] . ' 
                ORDER BY C.id DESC');
        }
        
        
        
        $this->html = '<select class="form-control select2" name="classId" id="classId" ' . $this->isDisabled . ' ' . $this->isRequired . '>';
        $this->html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '>' . ' - Сонгох - </option>';
        if ($this->query->num_rows() > 0) {
            foreach ($this->query->result() as $key => $row) {
                $this->dateBetweenInfo = dateBetweenInfo(array('dateIn'=>$row->start_date, 'dateOut'=>$row->end_date));
                $this->html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->category_title . ' - ' . $this->dateBetweenInfo['dateString'] . ' (' . $this->dateBetweenInfo['dateDiff'] . ' өдөр)</option>';
            }
        }
        $this->html .= '</select>';
        return $this->html;
    }
}
