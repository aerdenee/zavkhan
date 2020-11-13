<?php

class Smeeting_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function addFormData_model() {
        return array(
            'id' => 0,
            'mod_id' => 0,
            'cat_id' => 0,
            'title' => '',
            'description' => '',
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'is_active' => 1,
            'order_num' => getOrderNum(array('table' => 'meeting', 'field' => 'order_num')),
            'meeting_date' => date('Y-m-d H:i:s'),
            'city_id' => 12,
            'soum_id' => 0,
            'street_id' => 0
        );
    }

    public function editFormData_model($param = array('id' => 0)) {
        $this->query = $this->db->query('
            SELECT 
                M.id,
                M.mod_id,
                M.cat_id,
                M.title,
                M.description,
                M.created_date,
                M.modified_date,
                M.created_user_id,
                M.modified_user_id,
                M.is_active,
                M.order_num,
                M.meeting_date,
                M.city_id,
                M.soum_id,
                M.street_id
            FROM `gaz_meeting` AS M 
            WHERE M.id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            $this->result = $this->query->result();
            return (array) $this->result['0'];
        }

        return self::addFormData_model();
    }

    public function insert_model($param = array('getUID' => 0)) {

        $this->query = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => '0000-00-00 00:00:00',
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'is_active' => 1,
                'order_num' => getOrderNum(array('table' => 'meeting', 'field' => 'order_num')),
                'meeting_date' => $this->input->post('meetingDate') . ' ' . $this->input->post('meetingDateTime'),
                'city_id' => $this->input->post('cityId'),
                'soum_id' => $this->input->post('soumId'),
                'street_id' => $this->input->post('streetId')
            )
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'meeting', $this->query)) {
            foreach ($this->input->post('fname') as $keyDtl => $rowDtl) {
                $this->queryDtl = array(
                    array(
                        'id' => getUID('meeting_dtl'),
                        'cont_id' => $param['getUID'],
                        'fname' => $this->input->post('fname[' . $keyDtl . ']'),
                        'lname' => $this->input->post('lname[' . $keyDtl . ']'),
                        'register' => $this->input->post('register[' . $keyDtl . ']'),
                        'phone' => $this->input->post('phone[' . $keyDtl . ']'),
                        'created_date' => date('Y-m-d H:i:s'),
                        'modified_date' => '0000-00-00 00:00:00',
                        'created_user_id' => $this->session->adminUserId,
                        'modified_user_id' => 0,
                        'is_active' => 1,
                        'order_num' => getOrderNum(array('table' => 'meeting_dtl', 'field' => 'order_num'))
                    )
                );

                $this->db->insert_batch($this->db->dbprefix . 'meeting_dtl', $this->queryDtl);
            }


            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {

            $this->result = array('status' => 'success', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }

        return $this->result;
    }

    public function update_model() {
        
        $this->query = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'title' => $this->input->post('title'),
            'description' => $this->input->post('description'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'meeting_date' => $this->input->post('meetingDate') . ' ' . $this->input->post('meetingDateTime'),
            'city_id' => $this->input->post('cityId'),
            'soum_id' => $this->input->post('soumId'),
            'street_id' => $this->input->post('streetId')
        );

        $this->db->where('id', $this->input->post('id'));
        if ($this->db->update($this->db->dbprefix . 'meeting', $this->query)) {
            
            $this->db->where('cont_id', $this->input->post('id'));
            $this->db->delete($this->db->dbprefix . 'meeting_dtl');
            
            foreach ($this->input->post('fname') as $keyDtl => $rowDtl) {
                $this->queryDtl = array(
                    array(
                        'id' => getUID('meeting_dtl'),
                        'cont_id' => $this->input->post('id'),
                        'fname' => $this->input->post('fname[' . $keyDtl . ']'),
                        'lname' => $this->input->post('lname[' . $keyDtl . ']'),
                        'register' => $this->input->post('register[' . $keyDtl . ']'),
                        'phone' => $this->input->post('phone[' . $keyDtl . ']'),
                        'created_date' => date('Y-m-d H:i:s'),
                        'modified_date' => '0000-00-00 00:00:00',
                        'created_user_id' => $this->session->adminUserId,
                        'modified_user_id' => 0,
                        'is_active' => 1,
                        'order_num' => getOrderNum(array('table' => 'meeting_dtl', 'field' => 'order_num'))
                    )
                );

                $this->db->insert_batch($this->db->dbprefix . 'meeting_dtl', $this->queryDtl);
            }


            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
            
        } else {

            $this->result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
            
        }
        
        return $this->result;
        
    }

    public function listsCount_model($param = array()) {
        $this->queryString = '';

        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND D.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND D.created_user_id = -1';
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND D.cat_id = ' . $param['catId'];
        }

        if ($param['meetingDate'] != '') {
            $this->queryString .= ' AND \'' . $param['meetingDate'] . '\' = DATE(D.meeting_date)';
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(M.title) LIKE LOWER(\'' . $param['keyword'] . '%\' OR LOWER(M.description) LIKE LOWER(\'' . $param['keyword'] . '%\')';
        }

        $this->query = $this->db->query('
            SELECT 
                M.id
            FROM `gaz_meeting` AS M 
            WHERE 1 = 1 ' . $this->queryString . ' AND M.mod_id = ' . $param['modId']);

        return $this->query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $this->queryString = $this->getString = '';
        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND D.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND D.created_user_id = -1';
        }

        if ($param['isActive'] != 0) {
            $this->queryString .= ' AND D.is_active = ' . $param['isActive'];
            $this->getString .= form_hidden('isActive', $param['isActive']);
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND D.cat_id = ' . $param['catId'];
            $this->getString .= form_hidden('catId', $param['catId']);
        }

        if ($param['meetingDate'] != '') {
            $this->queryString .= ' AND \'' . $param['meetingDate'] . '\' = DATE(D.meeting_date)';
            $this->getString .= form_hidden('meetingDate', $param['meetingDate']);
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(M.title) LIKE LOWER(\'' . $param['keyword'] . '%\' OR LOWER(M.description) LIKE LOWER(\'' . $param['keyword'] . '%\')';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        $this->query = $this->db->query('
            SELECT 
                M.id,
                M.mod_id,
                M.cat_id,
                C.title_mn AS cat_title,
                M.title,
                M.description,
                M.created_date,
                M.modified_date,
                M.created_user_id,
                M.modified_user_id,
                M.is_active,
                M.order_num,
                M.meeting_date,
                M.city_id,
                M.soum_id,
                M.street_id
            FROM `gaz_meeting` AS M
            LEFT JOIN `gaz_category` AS C ON M.cat_id = C.id
            WHERE 1 = 1 ' . $this->queryString . ' AND m.mod_id = ' . $param['modId'] . ' 
            ORDER BY M.id DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        $this->data = array();
        $this->data['html'] = '';
        $this->data['html'] .= form_open('', array('class' => 'form-horizontal', 'id' => 'form-reservation-export', 'enctype' => 'multipart/form-data'));
        $this->data['html'] .= form_hidden('modId', $param['modId']);
        $this->data['html'] .= form_hidden('limit', $param['limit']);
        $this->data['html'] .= form_hidden('page', $param['page']);
        $this->data['html'] .= $this->getString;

        $this->data['html'] .= '<div class="panel panel-flat">';
        $this->data['html'] .= '<div class="panel-heading">';
        $this->data['html'] .= '<h5 class="panel-title">' . $param['title'] . '</h5>';
        $this->data['html'] .= '<div class="heading-elements">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';
        $this->data['html'] .= '<li>' . anchor(Smeeting::$path . 'index/' . $param['modId'], '<i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i>', array()) . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="panel-body" style="padding-bottom:5px;">';
        $this->data['html'] .= '<div class="pull-right">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';

        $this->data['html'] .= '<li style="padding-right:10px;">' . anchor(Smeeting::$path . 'add/' . $param['modId'], '<i class="fa fa-plus"></i> Нэмэх', 'class="btn btn-info btn-rounded btn-xs"') . '</li>';
        $this->data['html'] .= '<li style="padding-right:10px;">' . form_button('send', '<i class="fa fa-download"></i> Экспорт', 'class="btn btn-info btn-rounded btn-xs" onclick="_export(this);" ' . (empty($this->query->num_rows()) ? ' disabled="disabled"' : ''), 'button') . '</li>';
        $this->data['html'] .= '<li>' . form_button('send', '<i class="fa fa-search"></i> хайх', 'class="btn btn-info btn-rounded btn-xs" onclick="_advensedSearch(this);"', 'button') . '</li>';
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
            $this->data['html'] .= '<th style="width:200px;">Төрөл</th>';
            $this->data['html'] .= '<th style="width:100px;">Ирсэн огноо</th>';
            $this->data['html'] .= '<th style="width:50px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';
            $i = 1;
            foreach ($this->query->result() as $row) {

                $this->data['html'] .= '<tr data-id="' . $row->id . '">';
                $this->data['html'] .= '<td>' . $i . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row"><strong>' . $row->title . '</strong>, ' . word_limiter($row->description, 50, '...') . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row">' . $row->cat_title . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row text-center">' . dateFormatMonth(array('date' => $row->meeting_date)) . '</td>';
                $this->data['html'] .= '<td class="text-center">';
                $this->data['html'] .= '<a href="' . Smeeting::$path . 'view/' . $param['modId'] . '/' . $row->id . '"><i class="icon-file-eye"></i> Дэлгэрэнгүй</a>';
//                $this->data['html'] .= '<ul class="icons-list">';
//                $this->data['html'] .= '<li><a href="' . Smeeting::$path . 'edit/' . $param['modId'] . '/' . $row->id . '"><i class="icon-pencil7"></i></a></li>';
//                $this->data['html'] .= '<li><a href="javascript:;" onclick="_removeItem(' . $row->id . ');"><i class="icon-trash"></i></a></li>';
//                $this->data['html'] .= '</ul>';
                $this->data['html'] .= '</td>';
                $this->data['html'] .= '</tr>';
                $i++;
            }//076757

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

    public function isActive_model() {
        $data = array(
            'is_active' => $this->input->post('isActive')
        );
        $this->db->where('id', $this->input->post('id'));
        $result = $this->db->update($this->db->dbprefix . 'urgudul', $data);
        if ($result) {
            $result = array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $result = array('status' => 'success', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $result;
    }

    public function delete_model() {
        foreach ($this->input->post('id') as $key => $id) {

            $this->db->where('cont_id', $this->input->post('id[' . $key . ']'));
            $this->db->delete($this->db->dbprefix . 'meeting_dtl');
            
            $this->db->where('id', $this->input->post('id[' . $key . ']'));
            $this->db->delete($this->db->dbprefix . 'meeting');
            
        }
        
        return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        
    }

    public function mediaInsert_model($param = array('pic' => '')) {
        $this->getUID = getUID('document_media');
        $this->query = array(
            array(
                'id' => $this->getUID,
                'mod_id' => $this->input->post('modId'),
                'cont_id' => $this->input->post('contId'),
                'attach_file' => $param['pic'],
                'is_active' => 1,
                'order_num' => getOrderNum(array('table' => 'urgudul_media', 'field' => 'order_num')),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => '0000-00-00 00:00:00',
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0
            )
        );
        if ($this->db->insert_batch($this->db->dbprefix . 'document_media', $this->query)) {
            $this->result = array('status' => 'success', 'pic' => $param['pic'], 'id' => $this->getUID, 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $this->result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $this->result;
    }

    public function meetingDtlList_model($param = array('contId' => 0)) {

        $this->html = '';
        $this->query = $this->db->query('
            SELECT 
                MD.id,
                MD.cont_id,
                MD.fname,
                MD.lname,
                MD.register,
                MD.phone,
                MD.created_date,
                MD.modified_date,
                MD.created_user_id,
                MD.modified_user_id,
                MD.is_active,
                MD.order_num,
                MD.description
            FROM `gaz_meeting_dtl` AS MD
            WHERE MD.cont_id = ' . $param['contId'] . '
            ORDER BY MD.id DESC');

        if ($this->query->num_rows() > 0) {
            $i = 1;
            foreach ($this->query->result() as $row) {
                $this->html .= '<tr class="row-people">';
                    $this->html .= '<td>' . $i . '</td>';
                    $this->html .= '<td style="padding: 0;"><input type="text" name="lname[]" value="' . $row->lname . '" class="form-control" style="margin: 0; border: none;" placeholder="Эцгийн нэр"></td>';
                    $this->html .= '<td style="padding: 0;"><input type="text" name="fname[]" value="' . $row->fname . '" class="form-control" style="margin: 0; border: none;" placeholder="Өөрийн нэр"></td>';
                    $this->html .= '<td style="padding: 0;"><input type="text" name="register[]" value="' . $row->register . '" class="form-control" style="margin: 0; border: none;" placeholder="Регистр"></td>';
                    $this->html .= '<td style="padding: 0;"><input type="text" name="phone[]" value="' . $row->phone . '" class="form-control" style="margin: 0; border: none;" placeholder="Холбоо барих утасны дугаар"></td>';
                    $this->html .= '<td style="padding: 0;" class="text-center"><a href="javascript:;" onclick="_removeItemDtl(' . $row->id . ', this);"><i class="icon-trash"></i></a></td>';
                $this->html .= '</tr>';
                $i++;
            }

            return $this->html;
            
        } 

        $this->html = '<tr class="row-people">';
            $this->html .= '<td>1</td>';
            $this->html .= '<td style="padding: 0;"><input type="text" name="lname[]" class="form-control" style="margin: 0; border: none;" placeholder="Эцгийн нэр"></td>';
            $this->html .= '<td style="padding: 0;"><input type="text" name="fname[]" class="form-control" style="margin: 0; border: none;" placeholder="Өөрийн нэр"></td>';
            $this->html .= '<td style="padding: 0;"><input type="text" name="register[]" class="form-control" style="margin: 0; border: none;" placeholder="Регистр"></td>';
            $this->html .= '<td style="padding: 0;"><input type="text" name="phone[]" class="form-control" style="margin: 0; border: none;" placeholder="Холбоо барих утасны дугаар"></td>';
            $this->html .= '<td style="padding: 0;" class="text-center"><a href="javascript:;" onclick="_removeItemDtl(0, this);"><i class="icon-trash"></i></a></td>';
        $this->html .= '</tr>';
        return $this->html;
    }

    public function meetingDtlDelete_model() {
        foreach ($this->input->post('id') as $key => $id) {
            $this->db->where('id', $this->input->post('id[' . $key . ']'));
            $this->db->delete($this->db->dbprefix . 'meeting_dtl');
        }
        return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
    }

    public function mediaGetData_model($param = array('id' => 0)) {
        $this->query = $this->db->query('
            SELECT 
                UM.id,
                UM.mod_id,
                UM.cont_id,
                UM.attach_file,
                UM.is_active,
                UM.order_num,
                UM.created_date,
                UM.modified_date,
                UM.created_user_id,
                UM.modified_user_id,
                U.create_number
            FROM `gaz_document_media` AS UM
            INNER JOIN `gaz_document` AS U ON UM.cont_id = U.id
            WHERE UM.id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            $this->result = $this->query->result();
            return $this->result['0'];
        }
        return false;
    }

    public function mediaListsCount_model($param = array('modId' => 0, 'contId' => 0)) {
        $this->query = $this->db->query('
            SELECT 
                UM.id
            FROM `gaz_document_media` AS UM 
            WHERE UM.mod_id = ' . $param['modId'] . ' AND UM.cont_id = ' . $param['contId']);

        return $this->query->num_rows();
    }

    public function generateCreateNumber_model() {
        $this->query = $this->db->query('
            SELECT 
                id
            FROM `gaz_document`
            WHERE created_date BETWEEN \'' . date('Y-m-d') . ' 00:00:00\' AND \'' . date('Y-m-d') . ' 23:59:59\'
            ORDER BY id DESC');

        if ($this->query->num_rows() > 0) {
            $this->result = $this->query->result();
            $row = $this->result[0];
            $number = intval($row->id);
            if ($number < 10) {
                $number = '00' . $number;
            } elseif ($number > 9 and $number < 100) {
                $number = '0' . $number;
            }
            return 'D' . date('Ymd') . $number;
        } else {
            return 'D' . date('Ymd001');
        }
    }

    public function export_model($param = array('modId' => 0, 'catId' => 0)) {

        $this->queryString = $this->getString = '';
        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND U.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND U.created_user_id = -1';
        }

        if ($param['isActive'] != 0) {
            $this->queryString .= ' AND D.is_active = ' . $param['isActive'];
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND D.cat_id = ' . $param['catId'];
        }

        if ($param['generateDate'] != '') {
            $this->queryString .= ' AND \'' . $param['generateDate'] . '\' = DATE(D.generate_date)';
        }

        if ($param['createNumber'] != '') {
            $this->queryString .= ' AND D.create_number = \'' . $param['createNumber'] . '\'';
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(D.description) LIKE LOWER(\'' . $param['keyword'] . '%\')';
        }


        $this->query = $this->db->query('
            SELECT 
                D.id,
                D.generate_date,
                D.partner_id,
                P.title AS partner_title,
                D.document_number,
                D.description,
                D.cat_id,
                C.title_mn AS cat_title,
                D.mod_id,
                D.page,
                D.is_reply,
                D.created_date,
                D.modified_date,
                D.create_number,
                D.created_user_id,
                D.modified_user_id,
                D.is_active,
                D.order_num,
                D.reply_organization_id,
                D.reply_title,
                D.reply_description,
                D.parent_id,
                D.city_id,
                CC.title_mn AS city_title,
                D.soum_id,
                S.title_mn AS soum_title,
                D.street_id,
                G.title_mn AS street_title
            FROM `gaz_document` AS D
            LEFT JOIN `gaz_category` AS C ON D.cat_id = C.id
            LEFT JOIN `gaz_partner` AS P ON D.partner_id = P.id
            LEFT JOIN `gaz_address` AS CC ON D.city_id = CC.id
            LEFT JOIN `gaz_address` AS S ON D.soum_id = S.id
            LEFT JOIN `gaz_address` AS G ON D.street_id = G.id
            WHERE 1 = 1 ' . $this->queryString . ' AND D.mod_id = ' . $param['modId'] . ' 
            ORDER BY D.id DESC');

        if ($this->query->num_rows() > 0) {
            return $this->query->result();
        }
        return false;
    }

}
