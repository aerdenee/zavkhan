<?php

class Saccommodation_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function addFormData_model() {
        return array(
            'id' => 0,
            'mod_id' => 21,
            'pic' => '',
            'accommodation_code_mn' => '',
            'accommodation_code_en' => '',
            'accommodation_type_id' => 1,
            'accommodation_class_id' => 1,
            'accommodation_bed_id' => 1,
            'organization_id' => 31,
            'order_num' => getOrderNum(array('table' => 'accommodation', 'field' => 'order_num')),
            'is_active' => 1,
            'price' => 0,
            'description_mn' => '',
            'description_en' => '',
            'created_user_id' => 0,
            'modified_user_id' => 0,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00'
        );
    }

    public function editFormData_model($param = array('id' => 0)) {
        
        $this->db->where('id', $param['id']);
        $query = $this->db->get($this->db->dbprefix . 'accommodation');
        $result = $query->result();
        if (count($result) > 0) {
            $row = (array) $result['0'];
            return $row;
        }
        return self::addFormData_model();
    }

    public function insert_model($param = array('pic' => '', 'getUID' => 0)) {
        $result = array();
        $data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'pic' => $param['pic'],
                'accommodation_code_mn' => $this->input->post('accommodationCodeMn'),
                'accommodation_code_en' => $this->input->post('accommodationCodeEn'),
                'accommodation_type_id' => $this->input->post('accommodationTypeId'),
                'accommodation_class_id' => $this->input->post('accommodationClassId'),
                'accommodation_bed_id' => $this->input->post('accommodationBedId'),
                'organization_id' => $this->input->post('organizationId'),
                'order_num' => $this->input->post('orderNum'),
                'is_active' => $this->input->post('isActive'),
                'price' => $this->input->post('price'),
                'description_mn' => $this->input->post('descriptionMn'),
                'description_en' => $this->input->post('descriptionEn')
            )
        );
        $result = $this->db->insert_batch($this->db->dbprefix . 'accommodation', $data);
        if ($result) {
            $result = array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $result = array('status' => 'success', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $result;
    }

    public function update_model($param = array()) {
        $data = array(
            'pic' => $param['pic'],
            'accommodation_code_mn' => $this->input->post('accommodationCodeMn'),
            'accommodation_code_en' => $this->input->post('accommodationCodeEn'),
            'accommodation_type_id' => $this->input->post('accommodationTypeId'),
            'accommodation_class_id' => $this->input->post('accommodationClassId'),
            'accommodation_bed_id' => $this->input->post('accommodationBedId'),
            'organization_id' => $this->input->post('organizationId'),
            'order_num' => $this->input->post('orderNum'),
            'is_active' => $this->input->post('isActive'),
            'price' => $this->input->post('price'),
            'description_mn' => $this->input->post('descriptionMn', TRUE),
            'description_en' => $this->input->post('descriptionEn', TRUE)
        );
        $this->db->where('id', $this->input->post('id'));
        $result = $this->db->update($this->db->dbprefix . 'accommodation', $data);
        if ($result) {
            $result = array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $result = array('status' => 'success', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $result;
    }

    public function lists_model($param = array('modId' => 0, 'organizationId' => 0)) {
        $this->queryString = '';
        if (!empty($param['organizationId'])) {
            $this->queryString = ' WHERE A.organization_id = ' . $param['organizationId'];
        }
        $query = $this->db->query( 
            'SELECT 
                A.id, A.mod_id, A.accommodation_code_mn, A.accommodation_code_en, 
                A.accommodation_bed_id, B.title_mn AS bed_title_mn,  B.title_en AS bed_title_en, 
                A.accommodation_type_id, T.title_mn AS type_title_mn, T.title_en AS type_title_en, 
                A.accommodation_class_id, C.title_mn AS class_title_mn, C.title_en AS class_title_en,
                A.organization_id AS camp_id, CAMP.title_mn AS camp_title_mn, CAMP.title_en AS camp_title_en,
                A.price, A.description_mn, A.description_en, A.pic, A.pic_vertical, A.is_active, A.order_num
            FROM `gaz_accommodation` AS A 
            INNER JOIN gaz_accommodation_bed as B ON A.accommodation_bed_id = B.id
            INNER JOIN gaz_accommodation_type as T ON A.accommodation_type_id = T.id
            INNER JOIN gaz_accommodation_class as C ON A.accommodation_class_id = C.id
            INNER JOIN gaz_content as CAMP ON A.organization_id = CAMP.id ' . $this->queryString . ' ORDER BY A.order_num DESC'
        );
        $result = $query->result();
        $data = array();
        if (count($result) > 0) {
            foreach ($result as $value) {
                $row = (array) $value;
                array_push($data, array(
                    '<input type="checkbox" name="id[]" value="' . $row['id'] . '">',
                    '<a href="' . Saccommodation::$path . 'edit/' . $row['mod_id'] . '/' . $row['id'] . '"><img src="/upload/image/s_' . $row['pic'] . '" style="max-width:60px;"></a>',
                    '<a href="' . Saccommodation::$path . 'edit/' . $row['mod_id'] . '/' . $row['id'] . '">' . $row['bed_title_mn'] . ' ' . $row['class_title_mn'] . ' ' . $row['type_title_mn'] . ' - (#' . $row['accommodation_code_mn'] . ')</a>',
                    $row['camp_title_mn'],
                    ($row['is_active'] === '1' ? '<span class="label label-sm label-success pointer" onclick="_active(0, ' . $row['id'] . ')"><i class="fa fa-check"></i> Нийтэлсэн </span>' : '<span class="label label-sm label-danger pointer" onclick="_active(1, ' . $row['id'] . ')"><i class="fa fa-times"></i> Хүлээлгэнд </span>'),
                    $row['order_num'],
                    '<div class="text-center"><a href="javascript:;" title="Устгах" class="label label-sm label-danger pointer" onclick="_removeItem(' . $row['id'] . ')"><i class="fa fa-trash"></i></a></div>'
                        )
                );
            }
        }
        return $data;
    }

    public function isActive_model() {
        $data = array(
            'is_active' => $this->input->post('isActive')
        );
        $this->db->where('id', $this->input->post('id'));
        $result = $this->db->update($this->db->dbprefix . 'accommodation', $data);
        if ($result) {
            $result = array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $result = array('status' => 'success', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $result;
    }

    public function delete_model() {
        $data = $this->input->post('id');
        foreach ($data as $id) {
            $this->db->where('id', $id);
            $query = $this->db->get($this->db->dbprefix . 'accommodation');
            $result = $query->result();
            $row = (Array) $result['0'];
            $this->small = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH . CROP_SMALL . $row['pic'];
            $this->medium = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH . CROP_MEDIUM . $row['pic'];
            $this->large = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH . CROP_LARGE . $row['pic'];
            $this->big = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH . CROP_BIG . $row['pic'];
            (is_file($this->small) ? unlink($this->small) : '');
            (is_file($this->medium) ? unlink($this->medium) : '');
            (is_file($this->large) ? unlink($this->large) : '');
            (is_file($this->big) ? unlink($this->big) : '');
            $this->db->where('id', $id);
            $this->db->delete($this->db->dbprefix . 'content');
        }
        return array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай устгалаа');
    }

    public function insertMedias_model($param = array('fileNameMn' => '', 'fileNameEn' => '')) {
        $data = array(array(
                'mod_id' => $this->input->post('modId'),
                'cont_id' => $this->input->post('contId'),
                'intro_text_mn' => $this->input->post('introTextMn'),
                'intro_text_en' => $this->input->post('introTextEn'),
                'attach_file_mn' => ($this->input->post('type') === '3' ? $this->input->post('videoId') : $param['fileNameMn']),
                'attach_file_en' => ($this->input->post('type') === '3' ? $this->input->post('videoId') : $param['fileNameEn']),
                'is_active' => $this->input->post('isActive'),
                'order_num' => getOrderNum(array('table' => 'content_media', 'field' => 'order_num')),
                'created_date' => date('Y-m-d H:i:s'),
                'type' => $this->input->post('type'),
                'user_id' => $this->session->adminUserId,
                'lang_id' => $this->session->adminLangId
        ));
        $result = $this->db->insert_batch($this->db->dbprefix . 'content_media', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function updateMedia_model($param = array('fileNameMn' => '', 'fileNameEn' => '')) {
        $data = array(
            'mod_id' => $this->input->post('modId'),
            'cont_id' => $this->input->post('contId'),
            'intro_text_mn' => $this->input->post('introTextMn'),
            'attach_file_mn' => $param['fileNameMn'],
            'intro_text_en' => $this->input->post('introTextEn'),
            'attach_file_en' => $param['fileNameEn'],
            'is_active' => $this->input->post('isActive'),
            'order_num' => $this->input->post('orderNum'),
            'modified_date' => date('Y-m-d H:i:s'),
            'type' => $this->input->post('type')
        );
        $this->db->where('id', $this->input->post('id'));
        $result = $this->db->update($this->db->dbprefix . 'content_media', $data);
        if ($result) {
            $result = array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $result = array('status' => 'success', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $result;
    }

    public function listsMedia_model($param = array('modId' => 0, 'contId' => 0)) {
        $this->db->where('mod_id', $param['modId']);
        $this->db->where('lang_id', $this->session->adminLangId);
        $this->db->where('cont_id', $param['contId']);
        $this->db->where_in('type', array(1, 3));
        $this->db->order_by('order_num', 'DESC');
        $query = $this->db->get($this->db->dbprefix . 'content_media');
        $result = $query->result();
        $data = array();
        if (count($result) > 0) {
            foreach ($result as $value) {
                $row = (array) $value;
                array_push($data, array(
                    '<input type="checkbox" name="id[]" value="' . $row['id'] . '">',
                    '<a href="javascript:;" onclick="formMediaDialog(' . $row['id'] . ', \'updateMedia\')"><img src="' . ($row['type'] === '1' ? UPLOADS_CONTENT_PATH . CROP_SMALL . $row['attach_file_mn'] : '/assets/images/icon-youtube.png') . '" style="max-width:60px;"></a>',
                    '<a href="javascript:;" onclick="formMediaDialog(' . $row['id'] . ', \'updateMedia\')">' . $row['intro_text_mn'] . '</a>',
                    ($row['is_active'] === '1' ? '<span class="label label-sm label-success pointer" onclick="isActiveMedia(0, ' . $row['id'] . ')"><i class="fa fa-check"></i> Нийтэлсэн </span>' : '<span class="label label-sm label-danger pointer" onclick="isActiveMedia(1, ' . $row['id'] . ')"><i class="fa fa-times"></i> Хүлээлгэнд </span>'),
                    $row['order_num'],
                    '<div class="text-center"><a href="javascript:;" title="Устгах" class="btn red btn-xs margin-right-0" onclick="removeItemMedia(' . $row['id'] . ')"><i class="fa fa-trash"></i></a></div>'
                ));
            }
        }
        return $data;
    }

    public function isActiveMedia_model() {
        $data = array(
            'is_active' => $this->input->post('isActive')
        );
        $this->db->where('id', $this->input->post('id'));
        $result = $this->db->update($this->db->dbprefix . 'content_media', $data);
        if ($result) {
            $result = array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $result = array('status' => 'success', 'title' => 'Алдаа', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $result;
    }

    public function deleteMedia_model() {
        $data = $this->input->post('id');
        $path = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
        foreach ($data as $id) {
            $this->db->where('id', $id);
            $query = $this->db->get($this->db->dbprefix . 'content_media');
            $result = $query->result();
            $row = (Array) $result['0'];

            if ($row['type'] === '1') {
                (is_file($path . CROP_SMALL . $row['attach_file_mn']) ? unlink($path . CROP_SMALL . $row['attach_file_mn']) : '');
                (is_file($path . CROP_MEDIUM . $row['attach_file_mn']) ? unlink($path . CROP_MEDIUM . $row['attach_file_mn']) : '');
                (is_file($path . CROP_LARGE . $row['attach_file_mn']) ? unlink($path . CROP_LARGE . $row['attach_file_mn']) : '');

                (is_file($path . CROP_SMALL . $row['attach_file_en']) ? unlink($path . CROP_SMALL . $row['attach_file_en']) : '');
                (is_file($path . CROP_MEDIUM . $row['attach_file_en']) ? unlink($path . CROP_MEDIUM . $row['attach_file_en']) : '');
                (is_file($path . CROP_LARGE . $row['attach_file_en']) ? unlink($path . CROP_LARGE . $row['attach_file_en']) : '');
            }

            if ($row['type'] === '2') {
                (is_file($path . $row['attach_file_mn']) ? unlink($path . $row['attach_file_mn']) : '');
                (is_file($path . $row['attach_file_en']) ? unlink($path . $row['attach_file_en']) : '');
            }
            $this->db->where('id', $id);
            $this->db->delete($this->db->dbprefix . 'content_media');
        }
        return array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай устгалаа');
    }

    public function formMediaData_model($param = array('mediaId' => 0)) {
        if (!empty($param['mediaId'])) {
            $this->db->where('id', $param['mediaId']);
            $query = $this->db->get($this->db->dbprefix . 'content_media');
            $result = $query->result();
            if (count($result) > 0) {
                return (array) $result['0'];
            }
        } else {
            return array(
                'id' => '',
                'mod_id' => '',
                'cont_id' => '',
                'intro_text_mn' => '',
                'intro_text_en' => '',
                'attach_file_mn' => '',
                'attach_file_en' => '',
                'is_active' => '1',
                'order_num' => getOrderNum(array('table' => 'content_media', 'field' => 'order_num')),
                'created_date' => '',
                'modified_date' => '',
                'type' => '1',
            );
        }
    }

    public function updateVerticalPic_model($param = array()) {
        $data = array(
            'pic_vertical' => $param['verticalPic']
        );
        $this->db->where('id', $param['contId']);
        if ($this->db->update($this->db->dbprefix . 'accommodation', $data)) {
            return array('status' => 'success', 'message' => 'Амжилттай');
        } else {
            return array('status' => 'error', 'message' => 'Алдаа гарлаа');
        }
    }

    public function controlRadioBtnCamp_model($param = array()) {

        $this->db->where('mod_id', 9);
        $this->db->where('is_active_' . $this->session->adminLangCode, 1);
        $this->db->order_by('order_num', 'ASC');
        $query = $this->db->get($this->db->dbprefix . 'content');
        $result = $query->result();
        $this->html = '';
        if (count($result) > 0) {
            foreach ($result as $k => $value) {
                $row = (Array) $value;
                $this->check = '';
                if ($row['id'] == $param['campId']) {
                    $this->check = 'checked="checked"';
                }
                $this->html .= '<label> <input type="radio" name="campId" value="' . $row['id'] . '" ' . $this->check . ' onclick="changeValueRadio(this, \'organizationId\');"> ' . $row['title_mn'] . '</label> ';
            }
        }
        return $this->html;
    }
    
    public function controlRadioBtnAccommodationType_model($param = array()) {

        $this->db->order_by('order_num', 'ASC');
        $query = $this->db->get($this->db->dbprefix . 'accommodation_type');
        $result = $query->result();
        $this->html = '';
        if (count($result) > 0) {
            foreach ($result as $k => $value) {
                $row = (Array) $value;
                $this->check = '';
                if ($row['id'] == $param['accommodationTypeId']) {
                    $this->check = 'checked="checked"';
                }
                $this->html .= '<label> <input type="radio" name="accomTypeId" value="' . $row['id'] . '" ' . $this->check . ' onclick="changeValueRadio(this, \'accommodationTypeId\');"> ' . $row['title_mn'] . '</label> ';
            }
        }
        return $this->html;
    }
    
    public function controlRadioBtnAccommodationClass_model($param = array()) {

        $this->db->order_by('order_num', 'ASC');
        $query = $this->db->get($this->db->dbprefix . 'accommodation_class');
        $result = $query->result();
        $this->html = '';
        if (count($result) > 0) {
            foreach ($result as $k => $value) {
                $row = (Array) $value;
                $this->check = '';
                if ($row['id'] == $param['accommodationClassId']) {
                    $this->check = 'checked="checked"';
                }
                $this->html .= '<label> <input type="radio" name="accomClassId" value="' . $row['id'] . '" ' . $this->check . ' onclick="changeValueRadio(this, \'accommodationClassId\');"> ' . $row['title_mn'] . '</label> ';
            }
        }
        return $this->html;
    }
    
    public function controlRadioBtnAccommodationBed_model($param = array()) {

        $this->db->order_by('order_num', 'ASC');
        $query = $this->db->get($this->db->dbprefix . 'accommodation_bed');
        $result = $query->result();
        $this->html = '';
        if (count($result) > 0) {
            foreach ($result as $k => $value) {
                $row = (Array) $value;
                $this->check = '';
                if ($row['id'] == $param['accommodationBedId']) {
                    $this->check = 'checked="checked"';
                }
                $this->html .= '<label> <input type="radio" name="accomBedId" value="' . $row['id'] . '" ' . $this->check . ' onclick="changeValueRadio(this, \'accommodationBedId\');"> ' . $row['title_mn'] . '</label> ';
            }
        }
        return $this->html;
    }
    
    public function controlComboCampList_model($param = array('selectedId'=>0)) {
        $param['counter'] = 1;
        $this->db->where('mod_id', 9);
        $this->db->order_by('order_num', 'DESC');
        $query = $this->db->get($this->db->dbprefix . 'content');
        $result = $query->result();
        $str = '';
        if ($param['selectedId'] == 0 and $param['counter'] == 1) {
            $str .= '<option value="0" selected="selected">' . ' - Сонгох - </option>';
        }
        if (count($result) > 0) {
            foreach ($result as $value) {
                $row = (array) $value;
                $str .= '<option value="' . $row['id'] . '" ' . ($param['selectedId'] == $row['id'] ? 'selected="selected"' : '') . '>' . '&nbsp; &nbsp; ' . $param['counter'] . '. ' . $row['title_mn'] . '</option>';
                $param['counter'] = $param['counter'] + 1;
            }
        }
        return $str;
    }

}