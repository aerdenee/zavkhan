<?php

class Surgudul_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Saddress_model', 'address');
        $this->load->model('Surguduldirect_model', 'urguduldirect');
    }

    public function addFormData_model() {
        return array(
            'id' => 0,
            'generate_date' => date('Y-m-d H:i:s'),
            'lname' => '',
            'fname' => '',
            'address' => '',
            'contact' => '',
            'cat_id' => 1,
            'mod_id' => 0,
            'description' => '',
            'page' => 1,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'create_number' => '',
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'is_active' => 1,
            'city_id' => 12,
            'soum_id' => 0,
            'street_id' => 0,
            'order_num' => getOrderNum(array('table' => 'urgudul', 'field' => 'order_num'))
        );
    }

    public function editFormData_model($param = array('id' => 0)) {
        $this->query = $this->db->query('
            SELECT 
                U.id,
                U.generate_date,
                U.lname,
                U.fname,
                U.address,
                U.contact,
                U.cat_id,
                U.mod_id,
                U.description,
                U.page,
                U.created_date,
                U.modified_date,
                U.create_number,
                U.created_user_id,
                U.modified_user_id,
                U.is_active,
                U.city_id,
                U.soum_id,
                U.street_id,
                U.order_num,
                U.close_description,
                U.close_author,
                U.close_date,
                U.close_user_id,
                U.urgudul_direct_id
            FROM `gaz_urgudul` AS U 
            WHERE U.id = ' . $param['id'] . ' 
            ORDER BY U.id DESC');

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
                'generate_date' => $this->input->post('generateDate') . ' ' . $this->input->post('generateDateTime'),
                'lname' => $this->input->post('lname'),
                'fname' => $this->input->post('fname'),
                'address' => $this->input->post('address'),
                'contact' => $this->input->post('contact'),
                'cat_id' => $this->input->post('catId'),
                'mod_id' => $this->input->post('modId'),
                'description' => $this->input->post('description'),
                'page' => 0,
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => '0000-00-00 00:00:00',
                'create_number' => self::generateCreateNumber_model(),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'is_active' => 1,
                'city_id' => $this->input->post('cityId'),
                'soum_id' => $this->input->post('soumId'),
                'street_id' => $this->input->post('streetId'),
                'order_num' => getOrderNum(array('table' => 'urgudul', 'field' => 'order_num'))
            )
        );
        if ($this->db->insert_batch($this->db->dbprefix . 'urgudul', $this->query)) {
            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $this->result = array('status' => 'success', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $this->result;
    }

    public function update_model() {
        $this->data = array(
            'generate_date' => $this->input->post('generateDate') . ' ' . $this->input->post('generateDateTime'),
            'lname' => $this->input->post('lname'),
            'fname' => $this->input->post('fname'),
            'address' => $this->input->post('address'),
            'contact' => $this->input->post('contact'),
            'cat_id' => $this->input->post('catId'),
            'mod_id' => $this->input->post('modId'),
            'description' => $this->input->post('description'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'is_active' => 1,
            'city_id' => $this->input->post('cityId'),
            'soum_id' => $this->input->post('soumId'),
            'street_id' => $this->input->post('streetId')
        );
        $this->db->where('id', $this->input->post('id'));
        if ($this->db->update($this->db->dbprefix . 'urgudul', $this->data)) {
            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $this->result = array('status' => 'success', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $this->result;
    }

    public function updateClose_model() {
        $this->data = array(
            'close_description' => $this->input->post('closeDescription'),
            'close_author' => $this->input->post('closeAuthor'),
            'close_date' => $this->input->post('closeDate') . ' ' . $this->input->post('closeDateTime'),
            'close_user_id' => $this->session->adminUserId,
            'is_active' => $this->input->post('isActive'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'urgudul_direct_id' => $this->input->post('urgudulDirectId')
        );
        $this->db->where('id', $this->input->post('id'));
        if ($this->db->update($this->db->dbprefix . 'urgudul', $this->data)) {
            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $this->result = array('status' => 'success', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $this->result;
    }

    public function listsCount_model($param = array()) {
        $this->queryString = '';

        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND U.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND U.created_user_id = -1';
        }


        if ($param['isActive'] != 0) {
            $this->queryString .= ' AND U.is_active = ' . $param['isActive'];
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND U.cat_id = ' . $param['catId'];
        }

        if ($param['startDate'] != '' and $param['endDate'] != '') {
            $this->queryString .= ' AND \'' . $param['startDate'] . '\' >= DATE(U.created_date) AND \'' . $param['endDate'] . '\' <= DATE(U.created_date)';
        } elseif ($param['startDate'] != '' and $param['endDate'] == '') {
            $this->queryString .= ' AND \'' . $param['startDate'] . '\' >= DATE(U.created_date)';
        }

        if ($param['cityId'] != 0 and $param['soumId'] != 0 and $param['streetId'] != 0) {
            $this->queryString .= ' AND U.city_id = ' . $param['cityId'] . ' AND U.soum_id = ' . $param['soumId'] . ' AND U.street_id = ' . $param['streetId'];
        } elseif ($param['cityId'] != 0 and $param['soumId'] != 0 and $param['streetId'] == 0) {
            $this->queryString .= ' AND U.city_id = ' . $param['cityId'] . ' AND U.soum_id = ' . $param['soumId'];
        } elseif ($param['cityId'] != 0 and $param['soumId'] == 0 and $param['streetId'] == 0) {
            $this->queryString .= ' AND U.city_id = ' . $param['cityId'];
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(U.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(U.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(U.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(U.contact) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(U.address) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        if ($param['urgudulDirectId'] != 0) {
            $this->queryString .= ' AND U.urgudul_direct_id = ' . $param['urgudulDirectId'];
        }

        if ($param['createNumber'] != '') {
            $this->queryString .= ' AND U.create_number = \'' . $param['createNumber'] . '\'';
        }

        $this->query = $this->db->query('
            SELECT 
                U.id
            FROM `gaz_urgudul` AS U 
            WHERE 1 = 1 ' . $this->queryString . ' AND U.mod_id = ' . $param['modId']);

        return $this->query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $this->queryString = $this->getString = '';
        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND U.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND U.created_user_id = -1';
        }

        if ($param['isActive'] != 0) {
            $this->queryString .= ' AND U.is_active = ' . $param['isActive'];
            $this->getString .= form_hidden('isActive', $param['isActive']);
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND U.cat_id = ' . $param['catId'];
            $this->getString .= form_hidden('catId', $param['catId']);
        }

        $this->getString .= form_hidden('startDate', $param['startDate']);
        $this->getString .= form_hidden('endDate', $param['endDate']);
        $this->getString .= form_hidden('cityId', $param['cityId']);
        $this->getString .= form_hidden('soumId', $param['soumId']);
        $this->getString .= form_hidden('streetId', $param['streetId']);

        if ($param['startDate'] != '' and $param['endDate'] != '') {
            $this->queryString .= ' AND \'' . $param['startDate'] . '\' <= DATE(U.created_date) AND \'' . $param['endDate'] . '\' >= DATE(U.created_date)';
        } elseif ($param['startDate'] != '' and $param['endDate'] == '') {
            $this->queryString .= ' AND \'' . $param['startDate'] . '\' <= DATE(U.created_date)';
        }

        if ($param['cityId'] != 0 and $param['soumId'] != 0 and $param['streetId'] != 0) {
            $this->queryString .= ' AND U.city_id = ' . $param['cityId'] . ' AND U.soum_id = ' . $param['soumId'] . ' AND U.street_id = ' . $param['streetId'];
        } elseif ($param['cityId'] != 0 and $param['soumId'] != 0 and $param['streetId'] == 0) {
            $this->queryString .= ' AND U.city_id = ' . $param['cityId'] . ' AND U.soum_id = ' . $param['soumId'];
        } elseif ($param['cityId'] != 0 and $param['soumId'] == 0 and $param['streetId'] == 0) {
            $this->queryString .= ' AND U.city_id = ' . $param['cityId'];
        }

        if ($param['createNumber'] != '') {
            $this->queryString .= ' AND U.create_number = \'' . $param['createNumber'] . '\'';
            $this->getString .= form_hidden('createNumber', $param['createNumber']);
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(U.fname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(U.lname) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(U.description) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(U.contact) LIKE LOWER(\'%' . $param['keyword'] . '%\') OR LOWER(U.address) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        if ($param['urgudulDirectId'] != 0) {
            $this->queryString .= ' AND U.urgudul_direct_id = ' . $param['urgudulDirectId'];
            $this->getString .= form_hidden('urgudulDirectId', $param['urgudulDirectId']);
        }

        $this->query = $this->db->query('
            SELECT 
                U.id,
                U.generate_date,
                U.lname,
                U.fname,
                U.address,
                U.contact,
                U.cat_id,
                U.mod_id,
                U.description,
                U.page,
                U.created_date,
                U.modified_date,
                U.create_number,
                U.created_user_id,
                U.modified_user_id,
                U.is_active,
                U.city_id,
                C.title_mn AS city_title,
                U.soum_id,
                S.title_mn AS soum_title,
                U.street_id,
                G.title_mn AS street_title,
                U.order_num,
                U.urgudul_direct_id
            FROM `gaz_urgudul` AS U
            LEFT JOIN `gaz_address` AS C ON U.city_id = C.id
            LEFT JOIN `gaz_address` AS S ON U.soum_id = S.id
            LEFT JOIN `gaz_address` AS G ON U.street_id = G.id
            WHERE 1 = 1 ' . $this->queryString . ' AND U.mod_id = ' . $param['modId'] . ' 
            ORDER BY U.create_number DESC
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
        $this->data['html'] .= form_hidden('our[\'close\']', $this->auth->our->close);
        $this->data['html'] .= form_hidden('our[\'track\']', $this->auth->our->track);
        $this->data['html'] .= form_hidden('your[\'read\']', $this->auth->your->read);
        $this->data['html'] .= form_hidden('your[\'update\']', $this->auth->your->update);
        $this->data['html'] .= form_hidden('your[\'delete\']', $this->auth->your->delete);
        $this->data['html'] .= form_hidden('your[\'close\']', $this->auth->your->close);
        $this->data['html'] .= form_hidden('your[\'track\']', $this->auth->your->track);
        $this->data['html'] .= $this->getString;

        $this->data['html'] .= '<div class="panel panel-flat">';
        $this->data['html'] .= '<div class="panel-heading">';
        $this->data['html'] .= '<h5 class="panel-title">' . $param['title'] . '</h5>';
        $this->data['html'] .= '<div class="heading-elements">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';
        $this->data['html'] .= '<li>' . anchor(Surgudul::$path . 'index/' . $param['modId'], '<i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i>', array()) . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="panel-body" style="padding-bottom:5px;">';
        $this->data['html'] .= '<div class="pull-left search-keyword-view">' . self::searchKeywordView_model(array('modId' => $param['modId'], 'path' => $param['path'])) . '</div>';
        $this->data['html'] .= '<div class="pull-right">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';

        $this->data['html'] .= '<li style="padding-right:10px;">' . anchor(Surgudul::$path . 'add/' . $param['modId'], '<i class="fa fa-plus"></i> Нэмэх', 'class="btn btn-info btn-rounded btn-xs"') . '</li>';
        $this->data['html'] .= '<li style="padding-right:10px;">' . form_button('send', '<i class="fa fa-download"></i> Экспорт', 'class="btn btn-info btn-rounded btn-xs" onclick="_export({elem: this});" ' . (empty($this->query->num_rows()) ? ' disabled="disabled"' : ''), 'button') . '</li>';
        $this->data['html'] .= '<li>' . form_button('send', '<i class="fa fa-search"></i> хайх', 'class="btn btn-info btn-rounded btn-xs" onclick="_advensedSearch({modId:' . $param['modId'] . ', elem: this});"', 'button') . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        if ($this->query->num_rows() > 0) {

            $this->data['html'] .= '<div class="table-responsive">';
            $this->data['html'] .= '<table class="table table-bordered table-custom">';
            $this->data['html'] .= '<thead>';
            $this->data['html'] .= '<tr>';
            $this->data['html'] .= '<th style="width:30px;">#</th>';
            $this->data['html'] .= '<th style="width:150px;">Овог, Нэр</th>';
            $this->data['html'] .= '<th style="width:350px;">Хаяг</th>';
            $this->data['html'] .= '<th>Товч агуулга</th>';
            $this->data['html'] .= '<th style="width:100px;">Хавсралт</th>';
            $this->data['html'] .= '<th style="width:100px;">Огноо</th>';
            $this->data['html'] .= '<th style="width:100px;">Төлөв</th>';
            $this->data['html'] .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';
            $i = 1;
            foreach ($this->query->result() as $row) {
                $this->location = $this->closeClose = '';

                if ($row->city_title != '') {
                    $this->location .= $row->city_title . ', ';
                }
                if ($row->soum_title != '') {
                    $this->location .= $row->soum_title . ', ';
                }
                if ($row->street_title != '') {
                    $this->location .= $row->street_title . ', ';
                }
                if ($row->urgudul_direct_id != 0) {
                    $this->closeClose = 'bg-success';
                }
                $this->data['html'] .= '<tr class="' . $this->closeClose . '" data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '" data-create-number="' . $row->create_number . '">';
                $this->data['html'] .= '<td>' . $i . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row">' . mb_substr($row->lname, 0, 1, 'UTF-8') . '.' . $row->fname . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row">' . $this->location . $row->address . ', ' . $row->contact . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row"><strong>' . $row->create_number . '</strong>, ' . word_limiter($row->description, 15, '...') . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row text-center">' . ($row->page != 0 ? '<i class="fa fa-paperclip"></i> (' . $row->page . ') ' : '-') . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row text-center">' . dateLastModified(array('createdDate' => $row->created_date, 'modifiedDate' => $row->modified_date)) . '</td>';

                $this->active = '';
                if ($row->is_active == 1) {
                    $this->active .= '<span class="label label-default">Бүртгэгдэсэн</span>';
                } elseif ($row->is_active == 2) {
                    $this->active .= '<span class="label label-warning">Судлагдаж байгаа</span>';
                } else {
                    $this->active .= '<span class="label label-success">Хаагдсан</span>';
                }

                $this->data['html'] .= '<td class="context-menu-selected-row text-center">' . $this->active . '</td>';
                $this->data['html'] .= '<td class="text-center">';
                $this->data['html'] .= '<a href="' . Surgudul::$path . 'read/' . $param['modId'] . '/' . $row->id . '"><i class="icon-file-eye"></i> Дэлгэрэнгүй</a>';
//                $this->data['html'] .= '<ul class="icons-list">';
//                $this->data['html'] .= '<li><a href="' . Surgudul::$path . 'edit/' . $param['modId'] . '/' . $row->id . '"><i class="icon-pencil7"></i></a></li>';
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
            $this->data['html'] .= '<span class="heading-text text-semibold">Нийт ' . $param['rowCount'] . ' бичлэг байна</span>';
            
            $this->data['html'] .= '<div class="heading-btn pull-right">';
            $this->data['html'] .= $param['paginationHtml'];
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '</div>';
            //$this->data['html'] .= '<a class="heading-elements-toggle"><i class="icon-more"></i></a>';
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
            $result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $result = array('status' => 'success', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $result;
    }

    public function delete_model() {
        foreach ($this->input->post('id') as $key => $id) {

            $this->db->where('cont_id', $this->input->post('id[' . $key . ']'));
            $this->db->delete($this->db->dbprefix . 'urgudul_media');

            $this->db->where('cont_id', $this->input->post('id[' . $key . ']'));
            $this->db->delete($this->db->dbprefix . 'urgudul_track');

            $this->db->where('id', $this->input->post('id[' . $key . ']'));
            $this->db->delete($this->db->dbprefix . 'urgudul');
            if (is_dir($_SERVER['DOCUMENT_ROOT'] . UPLOADS_URGUDUL_PATH . $this->input->post('createNumber'))) {
                delete_files($_SERVER['DOCUMENT_ROOT'] . UPLOADS_URGUDUL_PATH . $this->input->post('createNumber'), TRUE);
                rmdir($_SERVER['DOCUMENT_ROOT'] . UPLOADS_URGUDUL_PATH . $this->input->post('createNumber'));
            }
        }
        return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
    }

    public function mediaInsert_model($param = array('pic' => '')) {

        $this->getUID = getUID('urgudul_media');

        $this->query = $this->db->query('
            SELECT 
                U.id,
                U.page
            FROM `gaz_urgudul` AS U
            WHERE U.id = ' . $param['contId']);
        $this->result = $this->query->result();
        $this->row = $this->result[0];
        $this->page = intval($this->row->page) + 1;

        $this->db->where('id', $param['contId']);
        $this->db->update($this->db->dbprefix . 'urgudul', array('page' => $this->page));

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
        if ($this->db->insert_batch($this->db->dbprefix . 'urgudul_media', $this->query)) {
            $this->result = array('status' => 'success', 'pic' => $param['pic'], 'id' => $this->getUID, 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $this->result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $this->result;
    }

    public function mediaList_model($param = array('modId' => 0, 'contId' => 0)) {

        $this->html = '';
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
                UM.modified_user_id
            FROM `gaz_urgudul_media` AS UM
            WHERE UM.mod_id = ' . $param['modId'] . ' AND UM.cont_id = ' . $param['contId'] . '
            ORDER BY UM.id DESC');

        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $row) {
                $this->html .= '<div class="col-md-2">';
                $this->html .= '<div class="thumbnail" style="position: relative; display: inline-block;">';
                $this->html .= '<a href="' . UPLOADS_URGUDUL_PATH . $param['createNumber'] . '/' . CROP_BIG . $row->attach_file . '" class="fancybox" data-fancybox-group="gallery" style="height:160px; overfloaw:hidden;">';
                $this->html .= '<img src="' . UPLOADS_URGUDUL_PATH . $param['createNumber'] . '/' . CROP_SMALL . $row->attach_file . '" style="width: 100%; height: 100%;" class="img-rounded">';
                $this->html .= '</a>';

                if (strtolower($param['controller']) != 'read') {
                    $this->html .= '<span class="badge bg-blue" style="position: absolute; bottom: 8px; right: 40px; border: 2px solid #fcfcfc; cursor:pointer;" onclick="_printImage({id: ' . $row->id . ', elem: this});"><i class="fa fa-print"></i></span>';
                    $this->html .= '<span class="badge bg-danger" style="position: absolute; bottom: 8px; right: 8px; border: 2px solid #fcfcfc; cursor:pointer;" onclick="_removeImage({modId: ' . $row->mod_id . ', contId: ' . $row->cont_id . ', id:' . $row->id . ', createNumber:\'' . $param['createNumber'] . '\', controller: \'' . $this->uri->segment(2) . '\', elem: this});"><i class="fa fa-close"></i></span>';
                } else {
                    $this->html .= '<span class="badge bg-blue" style="position: absolute; bottom: 8px; right: 8px; border: 2px solid #fcfcfc; cursor:pointer;" onclick="_printImage({id: ' . $row->id . ', elem: this});"><i class="fa fa-print"></i></span>';
                }
                $this->html .= '</div>';
                $this->html .= '</div>';
            }
        }

        return array('title' => $param['createNumber'], 'html' => $this->html);
    }

    public function mediaDelete_model() {
        foreach ($this->input->post('id') as $key => $id) {

            $this->mediaQuery = $this->db->query('
                SELECT 
                    U.id,
                    U.page
                FROM `gaz_urgudul` AS U
                WHERE U.id = ' . $this->input->post('contId'));
            $this->mediaResult = $this->mediaQuery->result();
            $this->mediaRow = $this->mediaResult[0];
            $this->page = intval($this->mediaRow->page) - 1;
            if ($this->page < 0) {
                $this->page = 0;
            }

            $this->db->where('id', $this->input->post('contId'));
            $this->db->update($this->db->dbprefix . 'urgudul', array('page' => $this->page));

            $this->query = $this->db->query('
            SELECT 
                UM.attach_file,
                U.create_number
            FROM `gaz_urgudul_media` AS UM
            INNER JOIN `gaz_urgudul` AS U ON UM.cont_id = U.id
            WHERE UM.id = ' . $this->input->post('id[' . $key . ']') . ' 
            ORDER BY U.id DESC');
            $this->result = $this->query->result();
            $row = $this->result['0'];

            removeImageSource(array('fieldName' => $row->attach_file, 'path' => UPLOADS_URGUDUL_PATH . $row->create_number . '/'));

            $this->db->where('id', $this->input->post('id[' . $key . ']'));
            $this->db->delete($this->db->dbprefix . 'urgudul_media');
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
            FROM `gaz_urgudul_media` AS UM
            INNER JOIN `gaz_urgudul` AS U ON UM.cont_id = U.id
            WHERE UM.id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            $this->result = $this->query->result();
            return $this->result['0'];
        }
        return false;
    }

    public function getDataMediaList_model($param = array('contId' => 0)) {

        $this->html = '';
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
                UM.modified_user_id
            FROM `gaz_urgudul_media` AS UM
            WHERE UM.cont_id = ' . $param['contId'] . '
            ORDER BY UM.id DESC');

        if ($this->query->num_rows() > 0) {

            return $this->query->result();
        }

        return false;
    }

    public function generateCreateNumber_model() {
        $this->query = $this->db->query('
            SELECT 
                id
            FROM `gaz_urgudul`
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
            return 'U' . date('Ymd') . $number;
        } else {
            return 'U' . date('Ymd001');
        }
    }

    public function urgudulTrackList_model($param = array('modId' => 0, 'contId' => 0)) {

        $this->queryString = $this->getString = '';
        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        $this->html = '';
        $this->query = $this->db->query('
            SELECT 
                UD.id,
                UD.mod_id,
                UD.cont_id,
                UD.description,
                UD.is_active,
                UD.order_num,
                UD.created_date,
                UD.modified_date,
                UD.created_user_id,
                UD.modified_user_id,
                U.fname_mn AS fname,
                U.lname_mn AS lname,
                U.pic
            FROM `gaz_urgudul_track` AS UD
            LEFT JOIN `gaz_user` AS U ON UD.created_user_id = U.id
            WHERE UD.mod_id = ' . $param['modId'] . ' AND UD.cont_id = ' . $param['contId'] . '
            ORDER BY UD.id DESC');

        if ($this->query->num_rows() > 0) {
            $this->html .= '<div class="clearfix"></div>';
            $this->html .= '<div class="panel panel-flat">';
            $this->html .= '<div class="panel-body">';

            $this->html .= '<ul class="media-list content-group-lg stack-media-on-mobile">';

            foreach ($this->query->result() as $row) {
                $this->author = mb_substr($row->lname, 0, 1, 'UTF-8') . '.' . $row->fname;

                $this->html .= '<li class="media">';
                $this->html .= '<div class="media-left">';
                $this->html .= '<img src="' . UPLOADS_USER_PATH . CROP_SMALL . $row->pic . '" class="img-circle" alt="' . $this->author . '">';
                $this->html .= '</div>';

                $this->html .= '<div class="media-body">';
                $this->html .= '<div class="media-heading">';
                $this->html .= '<span class="text-semibold">' . $this->author . '</span>';
                $this->html .= '<span class="media-annotation dotted">' . $row->created_date . '</span>';
                $this->html .= '</div>';

                $this->html .= $row->description;

                if (strtolower($param['controller']) != 'read') {
                    if ($this->session->adminUserId == $row->created_user_id) {
                        $this->html .= '<ul class="list-inline list-inline-separate text-size-small">';
                        $this->html .= '<li><a href="javascript:;" onclick="_updateUrgudulTrack({modId: ' . $row->mod_id . ', contId: ' . $row->cont_id . ', id: ' . $row->id . ', controller: \'' . $param['controller'] . '\'});"><i class="fa fa-edit"></i> Засварлах</a></li>';
                        $this->html .= '<li><a href="javascript:;" onclick="_removUrgudulTrack({modId: ' . $row->mod_id . ', contId: ' . $row->cont_id . ', id: ' . $row->id . ', controller: \'' . $param['controller'] . '\'});"><i class="fa fa-trash"></i> Устгах</a></li>';
                        $this->html .= '</ul>';
                    }
                }

                $this->html .= '</div>';
                $this->html .= '</li>';
            }

            $this->html .= '</ul>';
            $this->html .= '</div>';
            $this->html .= '</div>';
        }

        return $this->html;
    }

    public function urgudulTrackInsert_model() {
        $this->query = array(
            array(
                'id' => getUID('urgudul_track'),
                'mod_id' => $this->input->post('modId'),
                'cont_id' => $this->input->post('contId'),
                'description' => $this->input->post('description'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'created_date' => date('Y-m-d H:i:S'),
                'modified_date' => '0000-00-00 00:00:00',
                'order_num' => getOrderNum(array('table' => 'urgudul', 'field' => 'order_num')),
                'is_active' => 1
            )
        );
        if ($this->db->insert_batch($this->db->dbprefix . 'urgudul_track', $this->query)) {
            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $this->result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $this->result;
    }

    public function urgudulTrackDelete_model() {

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->delete($this->db->dbprefix . 'urgudul_track')) {
            $this->result = array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        } else {
            $this->result = array('status' => 'error', 'message' => 'Алдаа гарлаа');
        }
        return $this->result;
    }

    public function urgudulTrackGetData_model($param = array('id' => 0)) {
        $this->query = $this->db->query('
            SELECT 
                UD.id,
                UD.mod_id,
                UD.cont_id,
                UD.description,
                UD.created_user_id,
                UD.modified_user_id,
                UD.created_date,
                UD.modified_date,
                UD.order_num,
                UD.is_active
            FROM `gaz_urgudul_track` AS UD
            WHERE UD.id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            $this->result = $this->query->result();
            return $this->result['0'];
        }
        return false;
    }

    public function urgudulTrackUpdate_model() {
        $this->data = array(
            'description' => $this->input->post('description'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId
        );
        $this->db->where('id', $this->input->post('id'));
        if ($this->db->update($this->db->dbprefix . 'urgudul_track', $this->data)) {
            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $this->result = array('status' => 'success', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $this->result;
    }

    public function searchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {


        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('isActive')) {
            if ($this->input->get('isActive') == 1) {
                $this->string .= '<span class="label label-default label-rounded">Хаагдаагүй өргөдөл</span>';
            }
            if ($this->input->get('isActive') == 3) {
                $this->string .= '<span class="label label-default label-rounded">Хаагдсан өргөдөл</span>';
            }
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('catId')) {
            $this->cat = $this->category->getData_model(array('selectedId' => $this->input->get('catId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->cat->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('startDate') and $this->input->get('endDate')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('startDate') . '-' . $this->input->get('endDate') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('departmentId')) {
            $this->departmentData = $this->department->getData_model(array('selectedId' => $this->input->get('departmentId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->departmentData->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('departmentCatId')) {
            $this->categoryData = $this->category->getData_model(array('selectedId' => $this->input->get('departmentCatId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->categoryData->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('closeTypeId')) {
            $this->crimeCloseTypeData = $this->getScrimeCloseTypeData_model(array('selectedId' => $this->input->get('closeTypeId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->crimeCloseTypeData->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('createNumber')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('createNumber') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('cityId') != false and $this->input->get('soumId') != false and $this->input->get('streetId') != false) {

            $this->cityData = $this->address->getData_model(array('id' => $this->input->get('cityId')));
            $this->soumData = $this->address->getData_model(array('id' => $this->input->get('soumId')));
            $this->streetData = $this->address->getData_model(array('id' => $this->input->get('streetId')));

            $this->string .= '<span class="label label-default label-rounded">' . $this->cityData->title . ', ' . $this->soumData->title . ', ' . $this->streetData->title . '</span>';
            $this->showResetBtn = TRUE;
        } elseif ($this->input->get('cityId') != 0 and $this->input->get('soumId') != 0 and $this->input->get('streetId') == 0) {

            $this->cityData = $this->address->getData_model(array('id' => $this->input->get('cityId')));
            $this->soumData = $this->address->getData_model(array('id' => $this->input->get('soumId')));

            $this->string .= '<span class="label label-default label-rounded">' . $this->cityData->title . ', ' . $this->soumData->title . '</span>';
            $this->showResetBtn = TRUE;
        } elseif ($this->input->get('cityId') != false and $this->input->get('soumId') == false and $this->input->get('streetId') == false) {
            $this->cityData = $this->address->getData_model(array('id' => $this->input->get('cityId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->cityData->title . '</span>';
            $this->showResetBtn = TRUE;
        }


        if ($this->input->get('urgudulDirectId')) {
            $this->urguduldirectData = $this->urguduldirect->getData_model(array('id' => $this->input->get('urgudulDirectId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->urguduldirectData->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            $this->string .= ' <a href="' . Surgudul::$path . $param['path'] . '/' . $param['modId'] . '"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return $this->string;
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
            $this->queryString .= ' AND U.is_active = ' . $param['isActive'];
        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND U.cat_id = ' . $param['catId'];
        }

        if ($param['startDate'] != '' and $param['endDate'] != '') {
            $this->queryString .= ' AND \'' . $param['startDate'] . '\' >= DATE(U.created_date) AND \'' . $param['endDate'] . '\' <= DATE(U.created_date)';
        } elseif ($param['startDate'] != '' and $param['endDate'] == '') {
            $this->queryString .= ' AND \'' . $param['startDate'] . '\' >= DATE(U.created_date)';
        }

        if ($param['cityId'] != 0 and $param['soumId'] != 0 and $param['streetId'] != 0) {
            $this->queryString .= ' AND U.city_id = ' . $param['cityId'] . ' AND U.soum_id = ' . $param['soumId'] . ' AND U.street_id = ' . $param['streetId'];
        } elseif ($param['cityId'] != 0 and $param['soumId'] != 0 and $param['streetId'] == 0) {
            $this->queryString .= ' AND U.city_id = ' . $param['cityId'] . ' AND U.soum_id = ' . $param['soumId'];
        } elseif ($param['cityId'] != 0 and $param['soumId'] == 0 and $param['streetId'] == 0) {
            $this->queryString .= ' AND U.city_id = ' . $param['cityId'];
        }

        if ($param['createNumber'] != '') {
            $this->queryString .= ' AND U.create_number = ' . $param['createNumber'];
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(U.urg_fname) LIKE LOWER(\'' . $param['keyword'] . '%\') OR LOWER(U.urg_lname) LIKE LOWER(\'' . $param['keyword'] . '%\') OR LOWER(U.urg_intro) LIKE LOWER(\'' . $param['keyword'] . '%\'))';
        }

        $this->query = $this->db->query('
            SELECT 
                U.id,
                U.generate_date,
                U.lname,
                U.fname,
                U.address,
                U.contact,
                U.cat_id,
                U.mod_id,
                U.description,
                U.page,
                U.created_date,
                U.modified_date,
                U.create_number,
                U.created_user_id,
                U.modified_user_id,
                U.is_active,
                U.city_id,
                C.title_mn AS city_title,
                U.soum_id,
                S.title_mn AS soum_title,
                U.street_id,
                G.title_mn AS street_title,
                U.order_num,
                U.close_description
            FROM `gaz_urgudul` AS U
            LEFT JOIN `gaz_address` AS C ON U.city_id = C.id
            LEFT JOIN `gaz_address` AS S ON U.soum_id = S.id
            LEFT JOIN `gaz_address` AS G ON U.street_id = G.id
            WHERE 1 = 1 ' . $this->queryString . ' AND U.mod_id = ' . $param['modId'] . ' 
            ORDER BY U.id DESC');

        if ($this->query->num_rows() > 0) {
            return $this->query->result();
        }
        return false;
    }

    public function import_model($param = array('getUID' => 0)) {

        $this->query = array(
            array(
                'generate_date' => $param['generateDate'],
                'lname' => $param['lname'],
                'fname' => $param['fname'],
                'address' => $param['address'],
                'contact' => $param['contact'],
                'cat_id' => $param['catId'],
                'mod_id' => $param['modId'],
                'description' => $param['description'],
                'page' => $param['page'],
                'created_date' => $param['createdDate'],
                'modified_date' => $param['modifiedDate'],
                'create_number' => $param['createNumber'],
                'created_user_id' => $param['createdUserId'],
                'modified_user_id' => $param['modifiedUserId'],
                'is_active' => $param['isActive'],
                'city_id' => $param['cityId'],
                'soum_id' => $param['soumId'],
                'street_id' => $param['streetId'],
                'order_num' => $param['orderNum'],
                'close_description' => $param['closeDescription'],
                'close_author' => $param['closeAuthor'],
                'close_date' => $param['closeDate'],
                'close_user_id' => $param['closeUserId'],
                'urgudul_direct_id' => $param['urgudulDirectId']
            )
        );

        if ($this->db->insert_batch($this->db->dbprefix . 'urgudul', $this->query)) {
            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $this->result = array('status' => 'success', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $this->result;
    }

}
