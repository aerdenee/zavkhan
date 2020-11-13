<?php

class Spoll_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => 0,
            'cat_id' => 0,
            'partner_id' => 0,
            'title' => '',
            'pic_mn' => '',
            'show_pic_outside' => 1,
            'show_pic_inside' => 1,
            'is_active' => 1,
            'order_num' => getOrderNum(array('table' => 'poll', 'field' => 'order_num')),
            'created_user_id' => 0,
            'modified_user_id' => 0,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s'),
            'show_click' => 1,
            'click' => 0,
            'click_real' => 0,
            'is_active_date' => date('Y-m-d H:i:s'),
            'show_date' => 1,
            'url_id' => 0,
            'url' => getUID('url'),
            'theme_layout_id' => 1,
            'author_id' => 0)));
    }

    public function editFormData_model($param = array('id' => 0)) {
        $this->query = $this->db->query('
            SELECT 
                P.id,
                P.mod_id,
                P.cat_id,
                P.partner_id,
                P.title,
                P.pic,
                P.show_pic_outside,
                P.show_pic_inside,
                P.is_active,
                P.order_num,
                P.created_user_id,
                P.modified_user_id,
                P.created_date,
                P.modified_date,
                P.show_click,
                P.click,
                P.click_real,
                P.is_active_date,
                P.show_date,
                P.theme_layout_id,
                P.author_id,
                U.id AS url_id,
                U.url
            FROM `gaz_poll` AS P
            LEFT JOIN `gaz_url` AS U ON P.mod_id = U.mod_id AND P.id = U.cont_id
            WHERE P.id = ' . $param['id']);


        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function insert_model($param = array('pic' => '', 'getUID' => 0)) {

        $this->data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'partner_id' => ($this->session->userdata['adminAccessTypeId'] == 1 ? $this->input->post('partnerId') : $this->session->userdata['adminPartnerId']),
                'title' => $this->input->post('title'),
                'pic_mn' => $param['pic'],
                'show_pic_outside' => $this->input->post('showPicOutside'),
                'show_pic_inside' => $this->input->post('showPicInside'),
                'is_active' => $this->input->post('isActive'),
                'order_num' => getOrderNum(array('table' => 'poll', 'field' => 'order_num')),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => '0000-00-00 00:00:00',
                'show_click' => $this->input->post('showClick'),
                'click_mn' => $this->input->post('clickMn'),
                'click_en' => $this->input->post('clickEn'),
                'click_real' => 0,
                'is_active_date' => $this->input->post('isActiveDate') . ' ' . $this->input->post('isActiveTime'),
                'show_date' => $this->input->post('showDate'),
                'theme_layout_id' => $this->input->post('themeLayoutId'),
                'author_id' => $this->input->post('authorId')));

        if ($this->db->insert_batch($this->db->dbprefix . 'poll', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'success', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array('pic' => '')) {

        $this->param = array();

        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'partner_id' => ($this->session->userdata['adminAccessTypeId'] == 1 ? $this->input->post('partnerId') : $this->session->userdata['adminPartnerId']),
            'title_mn' => $this->input->post('titleMn'),
            'title_en' => $this->input->post('titleEn'),
            'pic_mn' => $param['picMn'],
            'pic_en' => $param['picEn'],
            'show_pic_outside_mn' => $this->input->post('showPicOutsideMn'),
            'show_pic_inside_mn' => $this->input->post('showPicInsideMn'),
            'show_pic_outside_en' => $this->input->post('showPicOutsideEn'),
            'show_pic_inside_en' => $this->input->post('showPicInsideEn'),
            'is_active_mn' => $this->input->post('isActiveMn'),
            'is_active_en' => $this->input->post('isActiveEn'),
            'order_num' => $this->input->post('orderNum'),
            'modified_user_id' => $this->session->adminUserId,
            'modified_date' => date('Y-m-d H:i:s'),
            'show_click' => $this->input->post('showClick'),
            'click_mn' => $this->input->post('clickMn'),
            'click_en' => $this->input->post('clickEn'),
            'is_active_date' => $this->input->post('isActiveDate') . ' ' . $this->input->post('isActiveTime'),
            'show_date' => $this->input->post('showDate'),
            'author_id' => $this->input->post('authorId'));

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'poll', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }
        return array('status' => 'success', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function listsCount_model($param = array()) {
        $this->queryString = '';
        $this->organization = 0;

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND LOWER(title_mn) LIKE LOWER(\'' . $param['keyword'] . '%\')';
        }
        
        if ($param['catId'] != '') {
            $this->queryString .= ' AND cat_id=' . $param['catId'];
        }

        $this->query = $this->db->query('
            SELECT 
                id
            FROM `gaz_poll` 
            WHERE 1 = 1 ' . $this->queryString);

        return $this->query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $this->queryString = $this->getString = '';

        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND P.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND P.created_user_id = -1';
        }

        if ($this->session->userdata['adminAccessTypeId'] == 2) {
            $this->queryString .= ' AND P.partner_id = ' . $this->session->userdata['adminPartnerId'];
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND P.cat_id = ' . $param['catId'];
            $this->getString .= form_hidden('catId', $param['catId']);
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND LOWER(P.title_mn) LIKE LOWER(\'%' . $param['keyword'] . '%\')';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        $this->query = $this->db->query('
            SELECT 
                P.id,
                P.mod_id,
                P.cat_id,
                P.partner_id,
                P.title,
                P.title_en,
                P.pic_mn,
                P.pic_en,
                P.show_pic_outside_mn,
                P.show_pic_inside_mn,
                P.show_pic_outside_en,
                P.show_pic_inside_en,
                P.is_active_mn,
                P.is_active_en,
                P.order_num,
                P.created_user_id,
                P.modified_user_id,
                P.created_date,
                P.modified_date,
                P.show_click,
                P.click_mn,
                P.click_en,
                P.click_real_mn,
                P.click_real_en,
                P.is_active_date,
                P.show_date
            FROM `gaz_poll` AS P 
            WHERE 1 = 1 ' . $this->queryString . '
            ORDER BY P.id DESC
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
        $this->data['html'] .= '<li>' . anchor(Spoll::$path . 'index/' . $param['modId'], '<i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i>', array()) . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="panel-body" style="padding-bottom:5px;">';
        $this->data['html'] .= '<div class="pull-right">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';

        $this->data['html'] .= '<li style="padding-right:10px;">' . anchor(Spoll::$path . 'add/' . $param['modId'], '<i class="fa fa-plus"></i> Нэмэх', 'class="btn btn-info btn-rounded btn-xs"') . '</li>';
        $this->data['html'] .= '<li>' . form_button('send', '<i class="fa fa-search"></i> хайх', 'class="btn btn-info btn-rounded btn-xs" onclick="_advensedSearch({modId:' . $param['modId'] . ', elem:this});"', 'button') . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        if ($this->query->num_rows() > 0) {

            $this->data['html'] .= '<div class="table-responsive">';
            $this->data['html'] .= '<table class="table table-bordered table-custom">';
            $this->data['html'] .= '<thead>';
            $this->data['html'] .= '<tr>';
            $this->data['html'] .= '<th style="width:30px;">#</th>';
            $this->data['html'] .= '<th>Сэтгэгдэл</th>';
            $this->data['html'] .= '<th style="width:150px;" class="text-center">Төлөв</th>';
            $this->data['html'] .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';

            $i = 1;
            foreach ($this->query->result() as $key => $row) {

                $this->data['html'] .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '" data-uid="' . $row->created_user_id . '">';
                $this->data['html'] .= '<td>' . $i . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row">' . $row->title_mn . '</td>';
                $this->data['html'] .= '<td class="text-center">';
                $isLoginHtml = '';
                if (intval($row->is_active_mn) == 1) {
                    $isLoginHtml = '<span class="label label-sm label-success pointer"><i class="fa fa-check"></i> Идэвхтэй </span>';
                } else {
                    $isLoginHtml = '<span class="label label-sm label-danger pointer"><i class="fa fa-close"></i> Идэвхгүй </span>';
                }
                $this->data['html'] .= $isLoginHtml;
                $this->data['html'] .= '</td>';
                $this->data['html'] .= '<td class="text-center">';
                $this->data['html'] .= '<ul class="icons-list">';
                $this->data['html'] .= '<li><a href="' . Spoll::$path . 'edit/' . $param['modId'] . '/' . $row->id . '"><i class="icon-pencil7"></i></a></li>';
                $this->data['html'] .= '<li><a href="javascript:;" onclick="_removeItem({id:' . $row->id . ', modId:' . $row->mod_id . '});"><i class="icon-trash"></i></a></li>';
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

    public function delete_model() {

        $data = $this->input->post('id');
        foreach ($data as $key => $id) {

            $this->db->where('id', $this->input->post('id[' . $key . ']'));
            $query = $this->db->get($this->db->dbprefix . 'poll');
            $result = $query->result();
            $row = (Array) $result['0'];

            removeImageSource(array('fieldName' => $row['pic'], 'path' => UPLOADS_CONTENT_PATH));

            $this->db->where('id', $this->input->post('id[' . $key . ']'));
            $this->db->delete($this->db->dbprefix . 'poll');
        }

        return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
    }

    public function mediaAddFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => 0,
            'poll_id' => 0,
            'title_mn' => '',
            'title_en' => '',
            'order_num' => getOrderNum(array('table' => 'poll_detail', 'field' => 'order_num')),
            'created_user_id' => 0,
            'modified_user_id' => 0,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'is_active_mn' => 1,
            'is_active_en' => 0,
            'type_id' => 1,
            'result' => 0)));
    }

    public function mediaEditFormData_model($param = array('id')) {
        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                poll_id,
                title_mn,
                title_en,
                order_num,
                created_user_id,
                modified_user_id,
                created_date,
                modified_date,
                is_active_mn,
                is_active_en,
                type_id,
                result
            FROM `gaz_poll_detail` 
            WHERE id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::mediaAddFormData();
    }

    public function mediaList_model($param = array('modId' => 0, 'contId' => 0)) {

        $this->data = array();

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                poll_id,
                title_mn,
                title_en,
                order_num,
                created_user_id,
                modified_user_id,
                created_date,
                modified_date,
                is_active_mn,
                is_active_en,
                type_id,
                result
            FROM `gaz_poll_detail` 
            WHERE poll_id = ' . $param['pollId'] . '
            ORDER BY id DESC');

        $this->data['html'] = '<div class="pull-left">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';
        $this->data['html'] .= '<li style="padding-right:10px;"><a href="javascript:;" onclick="_formMediaDialog({modId: ' . $param['modId'] . ', pollId: ' . $param['pollId'] . ', id:0, mode:\'mediaInsert\', elem:this});" class="btn btn-info btn-rounded btn-xs"><i class="fa fa-plus"></i> Нэмэх</a></li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="clearfix"></div>';

        if ($this->query->num_rows() > 0) {

            $this->data['html'] .= '<div class="table-responsive">';
            $this->data['html'] .= '<table class="table table-bordered table-custom">';
            $this->data['html'] .= '<thead>';
            $this->data['html'] .= '<tr>';
            $this->data['html'] .= '<th style="width:30px;">#</th>';
            $this->data['html'] .= '<th>Хариулт</th>';
            $this->data['html'] .= '<th style="width:100px;">Огноо</th>';
            $this->data['html'] .= '<th style="width:100px;">Төлөв</th>';
            $this->data['html'] .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';
            $i = 1;
            foreach ($this->query->result() as $row) {

                $this->data['html'] .= '<tr data-mod-id="' . $row->mod_id . '"  data-poll-id="' . $row->poll_id . '" data-id="' . $row->id . '">';
                $this->data['html'] .= '<td>' . $i . '</td>';
                $this->data['html'] .= '<td class="content-media-context-menu-selected-row text-left">' . $row->title_mn . '</td>';
                $this->data['html'] .= '<td class="content-media-context-menu-selected-row text-center">' . dateLastModified(array('createdDate' => $row->created_date, 'modifiedDate' => $row->modified_date)) . '</td>';
                $this->data['html'] .= '<td class="content-media-context-menu-selected-row text-center">' . (intval($row->is_active_mn) == 1 ? '<span class="label bg-teal dropdown-toggle">Идэвхтэй</span>' : '<span class="label label-danger">Идэвхгүй</span>') . '</td>';
                $this->data['html'] .= '<td class="text-center">';
                $this->data['html'] .= '<ul class="icons-list">';
                $this->data['html'] .= '<li><a href="javascript:;"  onclick="_formMediaDialog({modId:' . $row->mod_id . ', pollId:' . $row->poll_id . ', id:' . $row->id . ', mode:\'mediaUpdate\'});"><i class="icon-pencil7"></i></a></li>';
                $this->data['html'] .= '<li><a href="javascript:;" onclick="_removeMeidaItem({modId:' . $row->mod_id . ', pollId:' . $row->poll_id . ', id:' . $row->id . '});"><i class="icon-trash"></i></a></li>';
                $this->data['html'] .= '</ul>';
                $this->data['html'] .= '</td>';
                $this->data['html'] .= '</tr>';
                $i++;
            }

            $this->data['html'] .= '</tbody>';
            $this->data['html'] .= '</table>';
            $this->data['html'] .= '</div>';
        } else {

            $this->data['html'] .= '<br><div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Бичлэг байхгүй</div>';
        }
        return $this->data['html'];
    }

    public function mediaInsert_model($param = array('id' => 0)) {
        $this->data = array(array(
            'id' => $param['id'],
            'mod_id' => $this->input->post('modId'),
            'poll_id' => $this->input->post('pollId'),
            'title_mn' => $this->input->post('titleMn'),
            'title_en' => $this->input->post('titleEn'),
            'order_num' => getOrderNum(array('table' => 'poll_detail', 'field' => 'order_num')),
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'is_active_mn' => $this->input->post('isActiveMn'),
            'is_active_en' => $this->input->post('isActiveEn'),
            'type_id' => $this->input->post('typeId'),
            'result' => 0
        ));

        if ($this->db->insert_batch($this->db->dbprefix . 'poll_detail', $this->data)) {
            return true;
        } else {
            return false;
        }
    }

    public function mediaUpdate_model($param = array('fileNameMn' => '', 'fileNameEn' => '')) {
        $this->data = array(
            'poll_id' => $this->input->post('pollId'),
            'mod_id' => $this->input->post('modId'),
            'title_mn' => $this->input->post('titleMn'),
            'title_en' => $this->input->post('titleEn'),
            'order_num' => $this->input->post('orderNum'),
            'modified_user_id' => $this->session->adminUserId,
            'modified_date' => date('Y-m-d H:i:s'),
            'is_active_mn' => $this->input->post('isActiveMn'),
            'is_active_en' => $this->input->post('isActiveEn'),
            'type_id' => $this->input->post('typeId'),
            'result' => $this->input->post('result')
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'poll_detail', $this->data)) {
            return array('status' => 'success', 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...');
        }
        return array('status' => 'success', 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function mediaDelete_model() {

        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'poll_detail')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Устгах үед алдаа гарлаа');
    }
    
}