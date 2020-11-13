<?php

class Seducation_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function addFormData_model() {
        return array(
            'id' => 0,
            'mod_id' => 0,
            'cat_id' => 0,
            'title_mn' => '',
            'title_en' => '',
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => 0,
            'modified_user_id' => 0,
            'is_active_mn' => 1,
            'is_active_en' => 0,
            'order_num' => getOrderNum(array('table' => 'education', 'field' => 'order_num'))
        );
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cat_id,
                title_mn,
                title_en,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id,
                is_active_mn,
                is_active_en,
                order_num
            FROM `gaz_education`
            WHERE id = ' . $param['id']);
        $this->result = $this->query->result();

        if (count($this->result) > 0) {
            return (array) $this->result['0'];
        }
        return self::addFormData_model();
    }

    public function listsCount_model($param = array()) {
        $this->queryString = '';
        $this->organization = 0;

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND LOWER(title_mn) LIKE LOWER(\'' . $param['keyword'] . '%\')';
        }

        $this->query = $this->db->query('
            SELECT 
                C.id
            FROM `gaz_education` AS C 
            WHERE 1 = 1 ' . $this->queryString . ' AND C.mod_id = ' . $param['modId'] . ' 
            ORDER BY C.id DESC');

        $this->result = $this->query->result();

        return count($this->result);
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0)) {

        $this->queryString = $this->getString = '';

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND LOWER(title_mn) LIKE LOWER(\'' . $param['keyword'] . '%\')';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.title_mn,
                C.order_num,
                C.created_date,
                C.modified_date,
                C.created_user_id,
                C.modified_user_id,
                C.is_active_mn as is_active
            FROM `gaz_education` AS C
            WHERE 1 = 1 ' . $this->queryString . ' AND C.mod_id = ' . $param['modId'] . ' 
            ORDER BY C.order_num DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        $this->result = $this->query->result();
        $this->resultCount = count($this->result);

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
        $this->data['html'] .= '<li>' . anchor(Seducation::$path . 'index/' . $param['modId'], '<i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i>', array()) . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="panel-body" style="padding-bottom:5px;">';
        $this->data['html'] .= '<div class="pull-right">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';

        $this->data['html'] .= '<li style="padding-right:10px;">' . anchor(Seducation::$path . 'add/' . $param['modId'], '<i class="fa fa-plus"></i> Нэмэх', 'class="btn btn-info btn-rounded btn-xs"') . '</li>';
        $this->data['html'] .= '<li style="padding-right:10px;">' . form_button('send', '<i class="fa fa-download"></i> Экспорт', 'class="btn btn-info btn-rounded btn-xs" onclick="_export(this);" ' . (empty($resultCount) ? ' disabled="disabled"' : ''), 'button') . '</li>';
        $this->data['html'] .= '<li>' . form_button('send', '<i class="fa fa-search"></i> хайх', 'class="btn btn-info btn-rounded btn-xs" onclick="_advensedSearch(this);"', 'button') . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        if ($this->resultCount > 0) {

            $this->data['html'] .= '<div class="table-responsive">';
            $this->data['html'] .= '<table class="table table-bordered table-custom">';
            $this->data['html'] .= '<thead>';
            $this->data['html'] .= '<tr>';
            $this->data['html'] .= '<th style="width:30px;">#</th>';
            $this->data['html'] .= '<th>Гарчиг</th>';
            $this->data['html'] .= '<th style="width:100px;">Огноо</th>';
            $this->data['html'] .= '<th style="width:100px;">Төлөв</th>';
            $this->data['html'] .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';
            $i = 1;
            foreach ($this->result as $value) {
                $row = (array) $value;
                $this->data['html'] .= '<tr data-id="' . $row['id'] . '">';
                $this->data['html'] .= '<td>' . $i . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row">' . $row['title_mn'] . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row text-center">' . dateLastModified(array('createdDate' => $row['created_date'], 'modifiedDate' => $row['modified_date'])) . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row text-center">' . (intval($row['is_active']) == 1 ? '<span class="label bg-teal dropdown-toggle">Идэвхтэй</span>' : '<span class="label label-danger">Идэвхгүй</span>') . '</td>';
                $this->data['html'] .= '<td class="text-center">';
                $this->data['html'] .= '<ul class="icons-list">';
                $this->data['html'] .= '<li><a href="' . Seducation::$path . 'edit/' . $param['modId'] . '/' . $row['id'] . '"><i class="icon-pencil7"></i></a></li>';
                $this->data['html'] .= '<li><a href="javascript:;" onclick="_removeItem(' . $row['id'] . ');"><i class="icon-trash"></i></a></li>';
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
        $data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'title_mn' => $this->input->post('titleMn'),
                'title_en' => $this->input->post('titleEn'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => '0000-00-00 00:00:00',
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'is_active_mn' => $this->input->post('isActiveMn'),
                'is_active_en' => $this->input->post('isActiveEn'),
                'order_num' => $this->input->post('orderNum')
            )
        );
        $result = $this->db->insert_batch($this->db->dbprefix . 'education', $data);

        if ($result) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {
        $data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'title_mn' => $this->input->post('titleMn'),
            'title_en' => $this->input->post('titleEn'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'is_active_mn' => $this->input->post('isActiveMn'),
            'is_active_en' => $this->input->post('isActiveEn'),
            'order_num' => $this->input->post('orderNum')
        );
        $this->db->where('id', $this->input->post('id'));
        $result = $this->db->update($this->db->dbprefix . 'education', $data);
        if ($result) {
            $result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $result;
    }

    public function isActive_model() {
        $data = array(
            'is_active' => $this->input->post('isActive')
        );
        $this->db->where('id', $this->input->post('id'));
        $result = $this->db->update($this->db->dbprefix . 'education', $data);
        if ($result) {
            $result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $result;
    }

    public function delete_model() {
        $data = $this->input->post('id');
        foreach ($data as $id) {
            $this->db->where('id', $id);
            $this->db->delete($this->db->dbprefix . 'education');
        }
        return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cat_id,
                title_mn AS title,
                title_en,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id,
                is_active_mn,
                is_active_en,
                order_num
            FROM `gaz_education`
            WHERE id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            $this->result = $this->query->result();
            return $this->result['0'];
        }
        return false;
    }
}

?>