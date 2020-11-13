<?php

class Sfeedback_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function addFormData_model() {
        return array(
            'id' => 0,
            'mod_id' => 0,
            'cat_id' => 0,
            'parent_id' => 0,
            'title_mn' => '',
            'intro_text_mn' => '',
            'is_active_mn' => 1,
            'is_active_date' => date('Y-m-d H:i:S'),
            'ip_address' => $this->input->ip_address(),
            'created_date' => date('Y-m-d H:i:S'),
            'created_user_id' => 0,
            'order_num' => getOrderNum(array('table' => 'feedback', 'field' => 'order_num')),
            'return' => '',
            'fname_mn' => '',
            'lname_mn' => '',
            'phone' => '',
            'email' => '',
            'pic' => '',
            'title_en' => '',
            'intro_text_en' => '',
            'fname_en' => '',
            'lname_en' => '',
            'is_active_en' => 0,
            'modified_date' => '0000-00-00 00:00:00',
            'show_date' => 1,
            'url' => '',
            'url_id' => 0
        );
    }

    public function editFormData_model($param = array('id' => 0)) {
        $this->query = $this->db->query('
            SELECT 
                F.id,
                F.mod_id,
                F.cat_id,
                F.parent_id,
                F.title_mn,
                F.intro_text_mn,
                F.is_active_mn,
                F.is_active_date,
                F.ip_address,
                F.created_date,
                F.created_user_id,
                F.order_num,
                F.return,
                F.fname_mn,
                F.lname_mn,
                F.phone,
                F.email,
                F.pic,
                F.title_en,
                F.intro_text_en,
                F.fname_en,
                F.lname_en,
                F.is_active_en,
                F.modified_date,
                F.show_date,
                F.param,
                U.id AS url_id,
                U.url
            FROM `gaz_feedback` AS F
            LEFT JOIN `gaz_url` AS U ON F.mod_id = U.mod_id AND F.id = U.cont_id
            WHERE F.id = ' . $param['id']);
        $this->result = $this->query->result();

        if (count($this->result) > 0) {
            $row = (array) $this->result['0'];
            return $row;
        }
        return self::addFormData_model();
    }

    public function insert_model($param = array('pic' => '', 'getUID' => 0)) {

        $this->param = array();
        if ($this->input->post('organizationId')) {
            foreach ($this->input->post('organizationId') as $key => $value) {
                $this->param['organization']['org' . $this->input->post('organizationId[' . $key . ']')] = $this->input->post('organizationCheckValue[' . $key . ']');
            }
        }
        $data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'parent_id' => $this->input->post('parentId'),
                'title_mn' => $this->input->post('titleMn'),
                'intro_text_mn' => $this->input->post('introTextMn'),
                'is_active_mn' => $this->input->post('isActiveMn'),
                'is_active_date' => $this->input->post('isActiveDate') . ' 00:00:00',
                'ip_address' => $this->input->post('ipAddress'),
                'created_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'order_num' => getOrderNum(array('table' => 'feedback', 'field' => 'order_num')),
                'return' => $this->input->post('return'),
                'fname_mn' => $this->input->post('fnameMn'),
                'lname_mn' => $this->input->post('lnameMn'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'pic' => $param['pic'],
                'title_en' => $this->input->post('titleEn'),
                'intro_text_en' => $this->input->post('introTextEn'),
                'fname_en' => $this->input->post('fnameEn'),
                'lname_en' => $this->input->post('lnameEn'),
                'is_active_en' => $this->input->post('isActiveEn'),
                'modified_date' => '0000-00-00 00:00:00',
                'show_date' => $this->input->post('showDate'),
                'param' => json_encode($this->param)));
        $result = $this->db->insert_batch($this->db->dbprefix . 'feedback', $data);

        if ($result) {
            $result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $result = array('status' => 'success', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $result;
    }

    public function update_model($param = array('pic' => '')) {

        $this->param = array();

        if ($this->input->post('organizationId')) {
            foreach ($this->input->post('organizationId') as $key => $value) {
                $this->param['organization']['org' . $this->input->post('organizationId[' . $key . ']')] = $this->input->post('organizationCheckValue[' . $key . ']');
            }
        }
        $data = array(
            'cat_id' => $this->input->post('catId'),
            'parent_id' => $this->input->post('parentId'),
            'title_mn' => $this->input->post('titleMn'),
            'intro_text_mn' => $this->input->post('introTextMn'),
            'is_active_mn' => $this->input->post('isActiveMn'),
            'is_active_date' => $this->input->post('isActiveDate') . ' 00:00:00',
            'ip_address' => $this->input->post('ipAddress'),
            'modified_user_id' => $this->session->adminUserId,
            'order_num' => $this->input->post('orderNum'),
            'return' => $this->input->post('return'),
            'fname_mn' => $this->input->post('fnameMn'),
            'lname_mn' => $this->input->post('lnameMn'),
            'phone' => $this->input->post('phone'),
            'email' => $this->input->post('email'),
            'pic' => $param['pic'],
            'title_en' => $this->input->post('titleEn'),
            'intro_text_en' => $this->input->post('introTextEn'),
            'fname_en' => $this->input->post('fnameEn'),
            'lname_en' => $this->input->post('lnameEn'),
            'is_active_en' => $this->input->post('isActiveEn'),
            'modified_date' => date('Y-m-d H:i:s'),
            'show_date' => $this->input->post('showDate'),
            'param' => json_encode($this->param)
        );

        $this->db->where('id', $this->input->post('id'));
        $result = $this->db->update($this->db->dbprefix . 'feedback', $data);
        if ($result) {
            $result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $result = array('status' => 'success', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $result;
    }

    public function listsCount_model($param = array()) {
        $this->queryString = '';
        $this->organization = 0;

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND LOWER(title_mn) LIKE LOWER(\'' . $param['keyword'] . '%\')';
        }

        $this->query = $this->db->query('
            SELECT 
                id
            FROM `gaz_feedback` 
            WHERE 1 = 1 ' . $this->queryString);

        $this->result = $this->query->result();

        return count($this->result);
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $this->queryString = $this->getString = '';

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND LOWER(F.fname_mn) LIKE LOWER(\'' . $param['keyword'] . '%\')';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        $query = $this->db->query('
            SELECT 
                F.id,
                F.mod_id,
                F.cat_id,
                F.parent_id,
                F.title_mn,
                F.intro_text_mn,
                F.is_active_mn,
                F.is_active_date,
                F.ip_address,
                F.created_date,
                F.created_user_id,
                F.order_num,
                F.return,
                F.fname_mn,
                F.lname_mn,
                F.phone,
                F.email,
                F.pic,
                F.title_en,
                F.intro_text_en,
                F.fname_en,
                F.lname_en,
                F.is_active_en,
                F.modified_date,
                F.show_date
            FROM `gaz_feedback` AS F 
            WHERE 1 = 1 ' . $this->queryString . '
            ORDER BY F.id DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        $result = $query->result();
        $resultCount = count($result);

        $data = array();
        $data['html'] = '';
        $data['html'] .= form_open('', array('class' => 'form-horizontal', 'id' => 'form-reservation-export', 'enctype' => 'multipart/form-data'));
        $data['html'] .= form_hidden('modId', $param['modId']);
        $data['html'] .= form_hidden('limit', $param['limit']);
        $data['html'] .= form_hidden('page', $param['page']);
        $data['html'] .= $this->getString;

        $data['html'] .= '<div class="panel panel-flat">';
        $data['html'] .= '<div class="panel-heading">';
        $data['html'] .= '<h5 class="panel-title">' . $param['title'] . '</h5>';
        $data['html'] .= '<div class="heading-elements">';
        $data['html'] .= '<ul class="list-inline heading-text">';
        $data['html'] .= '<li>' . anchor(Sfeedback::$path . 'index/' . $param['modId'], '<i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i>', array()) . '</li>';
        $data['html'] .= '</ul>';
        $data['html'] .= '</div>';
        $data['html'] .= '</div>';
        $data['html'] .= '<div class="panel-body" style="padding-bottom:5px;">';
        $data['html'] .= '<div class="pull-right">';
        $data['html'] .= '<ul class="list-inline heading-text">';

        $data['html'] .= '<li style="padding-right:10px;">' . anchor(Sfeedback::$path . 'add/' . $param['modId'], '<i class="fa fa-plus"></i> Нэмэх', 'class="btn btn-info btn-rounded btn-xs"') . '</li>';
        $data['html'] .= '<li style="padding-right:10px;">' . form_button('send', '<i class="fa fa-download"></i> Экспорт', 'class="btn btn-info btn-rounded btn-xs" onclick="_export(this);" ' . (empty($resultCount) ? ' disabled="disabled"' : ''), 'button') . '</li>';
        $data['html'] .= '<li>' . form_button('send', '<i class="fa fa-search"></i> хайх', 'class="btn btn-info btn-rounded btn-xs" onclick="_advensedSearch(this);"', 'button') . '</li>';
        $data['html'] .= '</ul>';
        $data['html'] .= '</div>';
        $data['html'] .= '</div>';
        if ($resultCount > 0) {

            $data['html'] .= '<div class="table-responsive">';
            $data['html'] .= '<table class="table table-bordered table-custom">';
            $data['html'] .= '<thead>';
            $data['html'] .= '<tr>';
            $data['html'] .= '<th style="width:30px;">#</th>';
            $data['html'] .= '<th style="width:100px;">Зураг</th>';
            $data['html'] .= '<th>Сэтгэгдэл</th>';
            $data['html'] .= '<th style="width:150px;" class="text-center">Төлөв</th>';
            $data['html'] .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $data['html'] .= '</tr>';
            $data['html'] .= '</thead>';
            $data['html'] .= '<tbody>';

            $i = 1;
            foreach ($result as $value) {
                $row = (array) $value;
                $data['html'] .= '<tr data-id="' . $row['id'] . '" data-mod-id="' . $row['mod_id'] . '">';
                $data['html'] .= '<td>' . $i . '</td>';
                $data['html'] .= '<td class="context-menu-selected-row text-center"><img src="' . UPLOADS_CONTENT_PATH . CROP_SMALL . $row['pic'] . '" style="width:50px;"></td>';
                $data['html'] .= '<td class="context-menu-selected-row"><strong>' . $row['title_mn'] . '</strong><br> ' . $row['lname_mn'] . ', ' . $row['phone'] . ', ' . $row['email'] . '</td>';
                $data['html'] .= '<td class="text-center">';
                $isLoginHtml = '';
                if (intval($row['is_active_mn']) == 1) {
                    $isLoginHtml = '<span class="label label-sm label-success pointer"><i class="fa fa-check"></i> Идэвхтэй </span>';
                } else {
                    $isLoginHtml = '<span class="label label-sm label-danger pointer"><i class="fa fa-close"></i> Идэвхгүй </span>';
                }
                $data['html'] .= $isLoginHtml;
                $data['html'] .= '</td>';
                $data['html'] .= '<td class="text-center">';
                $data['html'] .= '<ul class="icons-list">';
                $data['html'] .= '<li><a href="' . Sfeedback::$path . 'edit/' . $param['modId'] . '/' . $row['id'] . '"><i class="icon-pencil7"></i></a></li>';
                $data['html'] .= '<li><a href="javascript:;" onclick="_removeItem({id:' . $row['id'] . ', modId:' . $param['modId'] . '});"><i class="icon-trash"></i></a></li>';
                $data['html'] .= '</ul>';
                $data['html'] .= '</td>';
                $data['html'] .= '</tr>';
                $i++;
            }

            $data['html'] .= '</tbody>';
            $data['html'] .= '</table>';
            $data['html'] .= '</div>';
            $data['html'] .= '<div class="panel-footer">';
            $data['html'] .= '<div class="heading-elements">';
            $data['html'] .= '<span class="heading-text text-semibold"></span>';
            $data['html'] .= '<div class="heading-btn pull-right">';
            $data['html'] .= $param['paginationHtml'];
            $data['html'] .= '</div>';
            $data['html'] .= '</div>';
            $data['html'] .= '<a class="heading-elements-toggle"><i class="icon-more"></i></a>';
            $data['html'] .= '</div>';
        } else {
            $data['html'] .= '<div class="panel-body">';
            $data['html'] .= '<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Бичлэг байхгүй</div>';
            $data['html'] .= '</div>';
        }


        $data['html'] .= '</div>';
        $data['html'] .= form_close();
        return $data['html'];
    }

    public function isActive_model() {
        $data = array(
            'is_active' => $this->input->post('isActive')
        );
        $this->db->where('id', $this->input->post('id'));
        $result = $this->db->update($this->db->dbprefix . 'feedback', $data);
        if ($result) {
            $result = array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $result = array('status' => 'success', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $result;
    }

    public function delete_model() {

        $data = $this->input->post('id');
        foreach ($data as $key => $id) {

            $this->db->where('id', $this->input->post('id[' . $key . ']'));
            $query = $this->db->get($this->db->dbprefix . 'feedback');
            $result = $query->result();
            $row = (Array) $result['0'];

            removeImageSource(array('fieldName' => $row['pic'], 'path' => UPLOADS_CONTENT_PATH));

            $this->db->where('id', $this->input->post('id[' . $key . ']'));
            $this->db->delete($this->db->dbprefix . 'feedback');
        }

        return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
    }

}

?>