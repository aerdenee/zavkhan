<?php
class Sstudent_finance_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function addFormData_model() {
        return array(
            'id' => 0,
            'student_id' => 0,
            'class_id' => 0,
            'income' => 0,
            'outlet' => 0,
            'creaed_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'mod_id' => '',
            'cat_id' => '',
            'order_num' => getOrderNum(array('table' => 'student_finance', 'field' => 'order_num')),
            'is_active' => 1,
            'created_user_id' => 0,
            'modified_user_id' => 0
        );
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                student_id,
                class_id,
                income,
                outlet,
                creaed_user_id,
                modified_user_id,
                created_date,
                modified_date,
                mod_id,
                cat_id,
                order_num,
                is_active,
                created_user_id,
                modified_user_id
            FROM `gaz_student_finance`
            WHERE id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            $this->result = $this->query->result();
            return (array) $this->result['0'];
        }
        return self::addFormData_model();
    }

    public function listsCount_model($param = array()) {
        $this->queryString = '';
        $this->organization = 0;

        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND C.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND C.created_user_id = -1';
        }
        
        if ($param['keyword'] != '') {
            $this->queryString .= ' AND LOWER(C.title_mn) LIKE LOWER(\'' . $param['keyword'] . '%\')';
        }

        $this->query = $this->db->query('
            SELECT 
                C.id
            FROM `gaz_student_finance` AS C 
            WHERE 1 = 1 ' . $this->queryString . ' AND C.mod_id = ' . $param['modId'] . ' 
            ORDER BY C.id DESC');

        $this->result = $this->query->result();

        return count($this->result);
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '')) {

        $this->queryString = $this->getString = '';

        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND C.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND C.created_user_id = -1';
        }
        
        if ($param['keyword'] != '') {
            $this->queryString .= ' AND LOWER(C.title_mn) LIKE LOWER(\'' . $param['keyword'] . '%\')';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.title_mn AS title,
                C.order_num,
                C.mod_id,
                C.theme_layout_id,
                L.title_mn AS layout_title,
                C.created_date,
                C.modified_date,
                C.created_user_id,
                C.modified_user_id,
                C.is_active
            FROM `gaz_category` AS C
            LEFT JOIN `gaz_theme_layout` AS L ON C.theme_layout_id = L.id
            WHERE C.parent_id = ' . $param['parentId'] . $this->queryString . ' AND C.mod_id = ' . $param['modId'] . ' 
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
        $this->data['html'] .= '<li>' . anchor(Scategory::$path . 'index/' . $param['modId'], '<i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i>', array()) . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="panel-body" style="padding-bottom:5px;">';
        $this->data['html'] .= '<div class="pull-left">' . self::searchKeywordView_model(array('modId' => $param['modId'])) . '</div>';
        $this->data['html'] .= '<div class="pull-right">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';

        $this->data['html'] .= '<li style="padding-right:10px;">' . anchor(Scategory::$path . 'add/' . $param['modId'], '<i class="fa fa-plus"></i> Нэмэх', 'class="btn btn-info btn-rounded btn-xs"') . '</li>';
        //$this->data['html'] .= '<li style="padding-right:10px;">' . form_button('send', '<i class="fa fa-download"></i> Экспорт', 'class="btn btn-info btn-rounded btn-xs" onclick="_export(this);" ' . (($this->query->num_rows() != 0) ? ' disabled="disabled"' : ''), 'button') . '</li>';
        $this->data['html'] .= '<li>' . form_button('send', '<i class="fa fa-search"></i> хайх', 'class="btn btn-info btn-rounded btn-xs" onclick="_advensedSearch({elem:this, modId:' . $param['modId'] . '});"', 'button') . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        
        if ($this->query->num_rows() > 0) {

            $this->result = $this->query->result();
            
            $this->data['html'] .= '<div class="table-responsive">';
            $this->data['html'] .= '<table class="table table-bordered table-custom">';
            $this->data['html'] .= '<thead>';
            $this->data['html'] .= '<tr>';
            $this->data['html'] .= '<th style="width:30px;">#</th>';
            $this->data['html'] .= '<th>Гарчиг</th>';
            $this->data['html'] .= '<th style="width:250px;">Төрөл</th>';
            $this->data['html'] .= '<th style="width:100px;">Огноо</th>';
            $this->data['html'] .= '<th style="width:100px;">Төлөв</th>';
            $this->data['html'] .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';

            $i = 1;
            foreach ($this->result as $row) {

                $this->data['html'] .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '">';
                $this->data['html'] .= '<td>' . $i . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row">(' . $row->id . ') - ' . $row->title . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row text-center">' . $row->layout_title . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row text-center">' . dateLastModified(array('createdDate' => $row->created_date, 'modifiedDate' => $row->modified_date)) . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row text-center">' . (intval($row->is_active) == 1 ? '<span class="label bg-teal dropdown-toggle">Идэвхтэй</span>' : '<span class="label label-danger">Идэвхгүй</span>') . '</td>';
                $this->data['html'] .= '<td class="text-center">';
                $this->data['html'] .= '<ul class="icons-list">';
                $this->data['html'] .= '<li><a href="' . Scategory::$path . 'edit/' . $param['modId'] . '/' . $row->id . '"><i class="icon-pencil7"></i></a></li>';
                $this->data['html'] .= '<li><a href="javascript:;" onclick="_removeItem({modId:' . $row->mod_id . ', id:' . $row->id . ', elem:this});"><i class="icon-trash"></i></a></li>';
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
    
    public function categoryParentList_model($param = array('modId' => 0, 'selectedId' => 0, 'editId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1)) {
        $this->db->where('id != ', $param['editId']);
        $this->db->where('mod_id', $param['modId']);
        $this->db->where('parent_id', $param['parentId']);
        $this->db->order_by('order_num', 'DESC');
        $query = $this->db->get($this->db->dbprefix . 'category');
        $result = $query->result();
        $str = '';
        if ($param['counter'] == 1) {
            $str .= '<option value="0" selected="selected">' . ' - Сонгох - </option>';
        }
        if (count($result) > 0) {
            foreach ($result as $key => $value) {
                $row = (array) $value;
                if ($param['selectedId'] == $row['id']) {
                    $str .= '<option value="' . $row['id'] . '" selected="selected">' . $param['space'] . $row['title_mn'] . '</option>';
                } else {
                    $str .= '<option value="' . $row['id'] . '">' . $param['space'] . $row['title_mn'] . '</option>';
                }
                $param['counter'] = $param['counter'] + 1;
                $str .= self::categoryParentList_model(array('modId' => $param['modId'], 'selectedId' => $param['selectedId'], 'editId' => $param['editId'], 'parentId' => $row['id'], 'space' => '&nbsp; &nbsp;  &nbsp;  &nbsp; ' . $param['space'], 'counter' => $param['counter']));
            }
        }
        return $str;
    }

    public function controlLocationListDropdown_model($param = array('modId' => 0, 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1)) {
        $this->query = $this->db->query('
            SELECT 
                id,
                title_mn
            FROM `gaz_category`
            WHERE mod_id = ' . $param['modId'] . ' AND parent_id = ' . $param['parentId'] . ' 
            ORDER BY order_num ASC');
        
        $result = $this->query->result();
        $str = '<select class="form-control select2" name="locationId" id="locationId">';
        if ($param['selectedId'] == 0 and $param['counter'] == 1) {
            $str .= '<option value="0" selected="selected">' . ' - Сонгох - </option>';
        }
        if (count($result) > 0) {
            foreach ($result as $value) {
                $row = (array) $value;
                $str .= '<option value="' . $row['id'] . '" ' . ($param['selectedId'] == $row['id'] ? 'selected="selected"' : '') . '>' . '&nbsp; &nbsp; ' . $param['counter'] . '. ' . $row['title_mn'] . '</option>';
                $param['counter'] = $param['counter'] + 1;
                $str .= self::categoryListChild_model(array('selectedId' => $param['selectedId'], 'parentId' => $row['id'], 'space' => '&nbsp; &nbsp; ' . $param['space'], 'counter' => $param['counter'], 'childHtml' => ''));
            }
        }
        $str .= '</select>';
        return $str;
    }
    
    public function controlCategoryListDropdown_model($param = array('modId' => 0, 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1, 'required' => true)) {
        $this->query = $this->db->query('
            SELECT 
                id,
                title_mn
            FROM `gaz_category`
            WHERE mod_id = ' . $param['modId'] . ' AND parent_id = ' . $param['parentId'] . ' 
            ORDER BY order_num ASC');
        
        $result = $this->query->result();
        $str = '<select class="form-control select2" name="catId" id="catId" ' . (isset($param['required']) ? 'required="required"' : '') . ' ' . (isset($param['isDisabled']) ? 'disabled="disabled"' : '') . '>';
        if ($param['counter'] == 1) {
            $str .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '>' . ' - Сонгох - </option>';
        }
        if (count($result) > 0) {
            foreach ($result as $value) {
                $row = (array) $value;
                $str .= '<option value="' . $row['id'] . '" ' . ($param['selectedId'] == $row['id'] ? 'selected="selected"' : '') . '>' . '&nbsp; &nbsp; ' . $param['counter'] . '. ' . $row['title_mn'] . '</option>';
                $param['counter'] = $param['counter'] + 1;
                $str .= self::categoryListChild_model(array('selectedId' => $param['selectedId'], 'parentId' => $row['id'], 'space' => '&nbsp; &nbsp; ' . $param['space'], 'counter' => $param['counter'], 'childHtml' => ''));
            }
        }
        $str .= '</select>';
        return $str;
    }
    
    public function categoryListChild_model($param = array('selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1, 'childHtml' => '')) {

        $str = '';
        
        $this->query = $this->db->query('
            SELECT 
                id,
                title_mn
            FROM `gaz_category`
            WHERE parent_id = ' . $param['parentId'] . ' 
            ORDER BY order_num ASC');
        
        $result = $this->query->result();
        if (count($result) > 0) {
            foreach ($result as $value) {
                $row = (array) $value;
                $param['childHtml'] .= '<option value="' . $row['id'] . '" ' . ($param['selectedId'] == $row['id'] ? 'selected="selected"' : '') . '>' . '&nbsp; &nbsp; ' . $param['space'] . $param['counter'] . '. ' . $row['title_mn'] . '</option>';
                $param['counter'] = $param['counter'] + 1;
                $param['childHtml'] .= self::categoryListChild_model(array('selectedId' => $param['selectedId'], 'parentId' => $row['id'], 'space' => '&nbsp; &nbsp; ' . $param['space'], 'counter' => $param['counter'], 'childHtml' => ''));
            }
        }
        return $param['childHtml'];
    }

    public function insert_model($param = array()) {
        $data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'title_mn' => $this->input->post('titleMn'),
                'intro_text_mn' => $this->input->post('introTextMn'),
                'parent_id' => $this->input->post('parentId'),
                'is_active' => $this->input->post('isActive'),
                'order_num' => $this->input->post('orderNum'),
                'cont_count' => 0,
                'show_pic' => $this->input->post('showPic'),
                'pic' => $param['pic'],
                'theme_layout_id' => $this->input->post('themeLayoutId'),
                'class' => $this->input->post('class'),
                'title_en' => $this->input->post('titleEn'),
                'intro_text_en' => $this->input->post('introTextEn'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => '0000-00-00 00:00:00',
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0
            )
        );
        $result = $this->db->insert_batch($this->db->dbprefix . 'category', $data);

        if ($result) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {
        $data = array(
            'mod_id' => $this->input->post('modId'),
            'title_mn' => $this->input->post('titleMn'),
            'intro_text_mn' => $this->input->post('introTextMn'),
            'parent_id' => $this->input->post('parentId'),
            'is_active' => $this->input->post('isActive'),
            'order_num' => $this->input->post('orderNum'),
            'show_pic' => $this->input->post('showPic'),
            'pic' => $param['pic'],
            'theme_layout_id' => $this->input->post('themeLayoutId'),
            'class' => $this->input->post('class'),
            'title_en' => $this->input->post('titleEn'),
            'intro_text_en' => $this->input->post('introTextEn'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId
        );
        $this->db->where('id', $this->input->post('id'));
        $result = $this->db->update($this->db->dbprefix . 'category', $data);
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
        $result = $this->db->update($this->db->dbprefix . 'category', $data);
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
            $this->db->delete($this->db->dbprefix . 'category');
        }
        return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
    }

    public function isChild_model($catId) {
        $this->db->where('parent_id', $catId);
        $this->db->where('is_active', 1);
        $cateogry = $this->db->get($this->db->dbprefix . 'category');
        $cat = $cateogry->result();
        if (count($cat) > 0) {
            return true;
        }
        return false;
    }

    public function getChildCategores_model($param = array('catId' => 0)) {
        $this->db->where('parent_id', $param['catId']);
        $this->db->where('is_active', 1);
        $cateogry = $this->db->get($this->db->dbprefix . 'category');
        $result = $cateogry->result();
        $data = array();
        if (count($result) > 0) {
            foreach ($result as $key => $value) {
                $row = (Array) $value;
                array_push($data, $row['id']);
            }
        }
        return $data;
    }
    
    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                title_mn AS title,
                intro_text_mn AS intro_text,
                parent_id,
                is_active,
                order_num,
                cont_count,
                show_pic,
                pic,
                theme_layout_id,
                class,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id
            FROM `' . $this->db->dbprefix . 'category`
            WHERE id = ' . $param['selectedId']);
        
        if ($this->query->num_rows() > 0) {
            $this->result = $this->query->result();
            return $this->result['0'];
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
            $this->string .= ' <a href="' . Scategory::$path . 'index/' . $param['modId'] . '"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }
        
        return $this->string;
    }
    
}
