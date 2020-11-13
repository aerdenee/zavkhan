<?php

class Scontact_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('Simage_model', 'simage');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('Slog_model', 'slog');
        $this->load->model('Ssocial_model', 'social');



        $this->isActiveDepartment = 'is_active_control';
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => 12,
            'cat_id' => 0,
            'show_title' => 1,
            'title' => '',
            'link_title' => '',
            'show_fax' => 1,
            'fax' => '',
            'show_phone' => 1,
            'phone' => '',
            'show_mobile' => 1,
            'mobile' => '',
            'show_address' => 1,
            'address' => '',
            'show_intro_text' => '',
            'intro_text' => '',
            'show_pic_outside' => 0,
            'show_pic_inside' => 0,
            'pic' => $this->simage->returnDefaultImage_model(array('table' => 'contact')),
            'show_email' => 1,
            'email' => '',
            'show_post_address' => 1,
            'post_address' => '',
            'h1_text' => '',
            'page_title' => '',
            'meta_key' => '',
            'meta_desc' => '',
            'show_click' => 1,
            'click' => 0,
            'click_real' => 0,
            'is_active' => 1,
            'order_num' => getOrderNum(array('table' => 'contact', 'field' => 'order_num')),
            'email_to' => '',
            'parent_id' => 0,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => 0,
            'modified_user_id' => 0,
            'show_social' => 1,
            'social' => $this->social->socialDefault_model(),
            'param' => '',
            'partner_id' => 0,
            'lang_id' => '',
            'url' => getOrderNum(array('table' => 'contact', 'field' => 'order_num')),
            'theme_layout_id' => 1,
            'show_comment' => 1)));
    }

    public function editFormData_model($param = array('id' => 0)) {
        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.mod_id,
                C.cat_id,
                C.show_title,
                C.title,
                C.link_title,
                C.show_fax,
                C.fax,
                C.show_phone,
                C.phone,
                C.show_mobile,
                C.mobile,
                C.show_address,
                C.address,
                C.show_intro_text,
                C.intro_text,
                C.show_pic_outside,
                C.show_pic_inside,
                C.pic,
                (case when (C.pic is null or C.pic = \'\' or C.pic = \'default.svg\') then \'' . $this->simage->returnDefaultImage_model(array('table' => 'contact')) . '\' else concat(\'' . UPLOADS_CONTENT_PATH . 's_\', C.pic) end) as pic,
                C.show_email,
                C.email,
                C.show_post_address,
                C.post_address,
                C.h1_text,
                C.page_title,
                C.meta_key,
                C.meta_desc,
                C.show_click,
                C.click,
                C.click_real,
                C.is_active,
                C.order_num,
                C.email_to,
                C.parent_id,
                C.created_date,
                C.modified_date,
                C.created_user_id,
                C.modified_user_id,
                C.show_social,
                C.social,
                C.param,
                C.partner_id,
                C.lang_id,
                U.url,
                C.show_comment,
                C.theme_layout_id,
                C.department_id
        FROM `gaz_contact` AS C
        LEFT JOIN `gaz_url` AS U ON C.mod_id = U.mod_id AND C.id = U.cont_id
        WHERE C.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {

            return $this->query->row();
        }

        return self::addFormData_model();
    }

    public function insert_model($param = array('pic' => '', 'getUID' => '')) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));

        $data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'show_title' => $this->input->post('showTitle'),
                'title' => $this->input->post('title'),
                'link_title' => $this->input->post('linkTitle'),
                'show_fax' => $this->input->post('showFax'),
                'fax' => $this->input->post('fax'),
                'show_phone' => $this->input->post('showPhone'),
                'phone' => $this->input->post('phone'),
                'show_mobile' => $this->input->post('showMobile'),
                'mobile' => $this->input->post('mobile'),
                'show_address' => $this->input->post('showAddress'),
                'address' => $this->input->post('address'),
                'show_intro_text' => $this->input->post('showIntroText'),
                'intro_text' => $this->input->post('introText'),
                'show_pic_outside' => $this->input->post('showPicOutside'),
                'show_pic_inside' => $this->input->post('showPicInside'),
                'pic' => ($this->input->post('contactPic') != '' ? $this->input->post('contactPic') : $this->input->post('contactOldPic')),
                'show_email' => $this->input->post('showEmail'),
                'email' => $this->input->post('email'),
                'show_post_address' => $this->input->post('showPostAddress'),
                'post_address' => $this->input->post('postAddress'),
                'h1_text' => $this->input->post('h1Text'),
                'page_title' => $this->input->post('pageTitle'),
                'meta_key' => $this->input->post('metaKey'),
                'meta_desc' => $this->input->post('metaDesc'),
                'show_click' => $this->input->post('showClick'),
                'click' => $this->input->post('click'),
                'is_active' => $this->input->post('isActive'),
                'order_num' => $this->input->post('orderNum'),
                'email_to' => $this->input->post('emailTo'),
                'parent_id' => 0,
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'show_social' => $this->input->post('showSocial'),
                'social' => $this->social->postToJson_model(),
                'param' => '',
                'show_comment' => $this->input->post('showComment'),
                'partner_id' => $this->input->post('partnerId'),
                'lang_id' => $this->session->userdata['adminLangId'],
                'theme_layout_id' => $this->input->post('themeLayoutId')));

        if ($this->db->insert_batch($this->db->dbprefix . 'contact', $data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'success', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array('pic' => '')) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_UPDATE,
            'data' => json_encode($_POST)));

        $data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'show_title' => $this->input->post('showTitle'),
            'title' => $this->input->post('title'),
            'link_title' => $this->input->post('linkTitle'),
            'show_fax' => $this->input->post('showFax'),
            'fax' => $this->input->post('fax'),
            'show_phone' => $this->input->post('showPhone'),
            'phone' => $this->input->post('phone'),
            'show_mobile' => $this->input->post('showMobile'),
            'mobile' => $this->input->post('mobile'),
            'show_address' => $this->input->post('showAddress'),
            'address' => $this->input->post('address'),
            'show_intro_text' => $this->input->post('showIntroText'),
            'intro_text' => $this->input->post('introText'),
            'show_pic_outside' => $this->input->post('showPicOutside'),
            'show_pic_inside' => $this->input->post('showPicInside'),
            'pic' => ($this->input->post('contactPic') != '' ? $this->input->post('contactPic') : $this->input->post('contactOldPic')),
            'show_email' => $this->input->post('showEmail'),
            'email' => $this->input->post('email'),
            'show_post_address' => $this->input->post('showPostAddress'),
            'post_address' => $this->input->post('postAddress'),
            'h1_text' => $this->input->post('h1Text'),
            'page_title' => $this->input->post('pageTitle'),
            'meta_key' => $this->input->post('metaKey'),
            'meta_desc' => $this->input->post('metaDesc'),
            'show_click' => $this->input->post('showClick'),
            'click' => $this->input->post('click'),
            'is_active' => $this->input->post('isActive'),
            'order_num' => $this->input->post('orderNum'),
            'email_to' => $this->input->post('emailTo'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'show_social' => $this->input->post('showSocial'),
            'social' => $this->social->postToJson_model(),
            'show_comment' => $this->input->post('showComment'),
            'partner_id' => $this->input->post('partnerId'),
            'theme_layout_id' => $this->input->post('themeLayoutId'));

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'contact', $data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'success', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function listsCount_model($param = array()) {

        $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and ! $auth->your->read) {
            $queryString .= ' AND C.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND C.created_user_id = -1';
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND LOWER(title) LIKE LOWER(\'' . $param['keyword'] . '%\')';
        }

        $query = $this->db->query('
            SELECT 
                C.id
            FROM `gaz_contact` AS C 
            WHERE C.lang_id = ' . $this->session->userdata['adminLangId'] . $queryString . ' AND C.mod_id = ' . $auth->modId . ' 
            ORDER BY C.id DESC');

        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $data = array();
        $queryString = '';
        $auth = $param['auth'];

        if ($auth->our->read and $auth->your->read) {
            $queryString .= '';
        } else if ($auth->our->read and ! $auth->your->read) {
            $queryString .= ' AND C.created_user_id = ' . $this->session->adminUserId;
        } else {
            $queryString .= ' AND C.created_user_id = -1';
        }

        if ($param['keyword'] != '') {
            $queryString .= ' AND LOWER(title) LIKE LOWER(\'' . $param['keyword'] . '%\')';
        }

        $query = $this->db->query('
            SELECT 
                C.id,
                C.mod_id,
                C.cat_id,
                C.title,
                CAT.title AS cat_title,
                CONCAT(\'phone: \', C.phone, \' fax: \', C.fax, \' mobile: \', C.mobile, \' address: \', C.address, \' email: \', C.email, \'шуудан хаяг: \', C.post_address) AS address,
                DATE(C.created_date) AS created_date,
                IF(C.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active
            FROM `gaz_contact` AS C
            LEFT JOIN `gaz_category` AS CAT ON C.cat_id = CAT.id
            WHERE C.lang_id = ' . $this->session->userdata['adminLangId'] . $queryString . ' AND C.mod_id = ' . $auth->modId . ' 
            ORDER BY C.id DESC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);

        if ($query->num_rows() > 0) {

            $i = 0;
            foreach ($query->result() as $key => $row) {

                array_push($data, array(
                    'id' => $row->id,
                    'num' => ++$i,
                    'title' => $row->title,
                    'cat_title' => $row->cat_title,
                    'address' => $row->address,
                    'created_date' => $row->created_date,
                    'is_active' => $row->is_active
                ));
            }
        }
        return array('data' => $data, 'search' => self::searchKeywordView_model());
    }

    public function delete_model() {
        $this->db->where('id', $this->input->post('id'));
        $query = $this->db->get($this->db->dbprefix . 'contact');
        $row = $query->row();

        $this->slog->log_model(array(
            'modId' => $row->mod_id,
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_DELETE,
            'data' => json_encode($row)));

        $this->simage->removeUploadImage_model(array('uploadImage' => $row->pic, 'uploadPath' => UPLOADS_CONTENT_PATH));
        generateUrl(array('modId' => $row->mod_id, 'contId' => $row->id, 'mode' => 'delete', 'langId' => $this->session->userdata['adminLangId']));

        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'contact')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function searchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {
        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            $this->string .= ' <a href="javascript:;" onclick="_initContact({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return $this->string;
    }

}
