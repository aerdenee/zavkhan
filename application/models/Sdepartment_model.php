<?php
class Sdepartment_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Slog_model', 'slog');
    }

    public function addFormData_model() {
        return array(
            'id' => 0,
            'mod_id' => 0,
            'cat_id' => '',
            'parent_id' => '',
            'title_mn' => '',
            'title_en' => 1,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => 0,
            'modified_user_id' => 0,
            'is_active_mn' => 1,
            'is_active_en' => 0,
            'order_num' => getOrderNum(array('table' => 'category', 'field' => 'order_num'))
        );
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cat_id,
                parent_id,
                title_mn,
                title_en,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id,
                is_active_mn,
                is_active_en,
                order_num
            FROM `gaz_department`
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

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND LOWER(C.title_mn) LIKE LOWER(\'' . $param['keyword'] . '%\')';
        }

        $this->query = $this->db->query('
            SELECT 
                C.id
            FROM `gaz_department` AS C 
            WHERE 1 = 1 ' . $this->queryString . ' AND C.mod_id = ' . $param['modId'] . ' 
            ORDER BY C.id DESC');

        $this->result = $this->query->result();

        return count($this->result);
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '')) {

        $this->queryString = $this->getString = '';

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND LOWER(C.title_mn) LIKE LOWER(\'' . $param['keyword'] . '%\')';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        $this->query = $this->db->query('
            SELECT 
                D.id,
                D.mod_id,
                D.cat_id,
                D.parent_id,
                D.title_mn AS title,
                D.title_en,
                D.created_date,
                D.modified_date,
                D.created_user_id,
                D.modified_user_id,
                D.is_active_mn,
                D.is_active_en,
                D.order_num
            FROM `gaz_department` AS D
            WHERE D.parent_id = ' . $param['parentId'] . $this->queryString . ' AND D.mod_id = ' . $param['modId'] . ' 
            ORDER BY D.id DESC
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
        $this->data['html'] .= '<li>' . anchor(Sdepartment::$path . 'index/' . $param['modId'], '<i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i>', array()) . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="panel-body" style="padding-bottom:5px;">';
        $this->data['html'] .= '<div class="pull-left">' . self::searchKeywordView_model(array('modId' => $param['modId'])) . '</div>';
        $this->data['html'] .= '<div class="pull-right">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';

        $this->data['html'] .= '<li style="padding-right:10px;">' . anchor(Sdepartment::$path . 'add/' . $param['modId'], '<i class="fa fa-plus"></i> Нэмэх', 'class="btn btn-info btn-rounded btn-xs"') . '</li>';
        $this->data['html'] .= '<li style="padding-right:10px;">' . form_button('send', '<i class="fa fa-download"></i> Экспорт', 'class="btn btn-info btn-rounded btn-xs" onclick="_export(this);" ' . ($this->query->num_rows()!=0 ? ' disabled="disabled"' : ''), 'button') . '</li>';
        $this->data['html'] .= '<li>' . form_button('send', '<i class="fa fa-search"></i> хайх', 'class="btn btn-info btn-rounded btn-xs" onclick="_advensedSearch(this);"', 'button') . '</li>';
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
            $this->data['html'] .= '<th style="width:100px;">Огноо</th>';
            $this->data['html'] .= '<th style="width:100px;">Төлөв</th>';
            $this->data['html'] .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';

            $i = 1;
            foreach ($this->result as $row) {

                $this->data['html'] .= '<tr data-id="' . $row->id . '">';
                $this->data['html'] .= '<td>' . $i . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row">(' . $row->id . ') - ' . $row->title . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row text-center">' . dateLastModified(array('createdDate' => $row->created_date, 'modifiedDate' => $row->modified_date)) . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row text-center">' . (intval($row->is_active_mn) == 1 ? '<span class="label bg-teal dropdown-toggle">Идэвхтэй</span>' : '<span class="label label-danger">Идэвхгүй</span>') . '</td>';
                $this->data['html'] .= '<td class="text-center">';
                $this->data['html'] .= '<ul class="icons-list">';
                $this->data['html'] .= '<li><a href="' . Sdepartment::$path . 'edit/' . $param['modId'] . '/' . $row->id . '"><i class="icon-pencil7"></i></a></li>';
                $this->data['html'] .= '<li><a href="javascript:;" onclick="_removeItem(' . $row->id . ');"><i class="icon-trash"></i></a></li>';
                $this->data['html'] .= '</ul>';
                $this->data['html'] .= '</td>';
                $this->data['html'] .= '</tr>';
                $this->data['html'] .= self::listsChild_model(array('parentId' => $row->id, 'space' => 40, 'childHtml' => ''));
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
    
    public function listsChild_model($param = array('parentId' => 0, 'space' => '', 'childHtml' => '')) {

        $this->html = $this->queryString = $this->getString = '';

        if ($this->input->get('keyword')) {
            $this->queryString .= ' AND LOWER(C.title_mn) LIKE LOWER(\'' . $this->input->get('keyword') . '%\')';
        }
        
        $this->query = $this->db->query('
            SELECT 
                D.id,
                D.mod_id,
                D.cat_id,
                D.parent_id,
                D.title_mn AS title,
                D.title_en,
                D.created_date,
                D.modified_date,
                D.created_user_id,
                D.modified_user_id,
                D.is_active_mn,
                D.is_active_en,
                D.order_num
            FROM `gaz_department` AS D
            WHERE D.parent_id = ' . $param['parentId'] . ' 
            ORDER BY D.order_num DESC');

        if ($this->query->num_rows() > 0) {

            $this->result = $this->query->result();
            
            $i = 1;
            foreach ($this->result as $row) {
                $param['childHtml'] .= '<tr data-id="' . $row->id . '">';
                $param['childHtml'] .= '<td>' . $i . '</td>';
                $param['childHtml'] .= '<td class="context-menu-selected-row" style="padding-left:' . $param['space'] . 'px;">(' . $row->id . ') - ' . $row->title . '</td>';
                $param['childHtml'] .= '<td class="context-menu-selected-row text-center">' . dateLastModified(array('createdDate' => $row->created_date, 'modifiedDate' => $row->modified_date)) . '</td>';
                $param['childHtml'] .= '<td class="context-menu-selected-row text-center">' . (intval($row->is_active_mn) == 1 ? '<span class="label bg-teal dropdown-toggle">Идэвхтэй</span>' : '<span class="label label-danger">Идэвхгүй</span>') . '</td>';
                $param['childHtml'] .= '<td class="text-center">';
                $param['childHtml'] .= '<ul class="icons-list">';
                $param['childHtml'] .= '<li><a href="' . Sdepartment::$path . 'edit/' . $row->mod_id . '/' . $row->id . '"><i class="icon-pencil7"></i></a></li>';
                $param['childHtml'] .= '<li><a href="javascript:;" onclick="_removeItem(' . $row->id . ');"><i class="icon-trash"></i></a></li>';
                $param['childHtml'] .= '</ul>';
                $param['childHtml'] .= '</td>';
                $param['childHtml'] .= '</tr>';
                $param['childHtml'] .= self::listsChild_model(array('parentId' => $row->id, 'space' => (intval($param['space']) + 20), 'childHtml' => ''));
                $i++;
            }

        }
        return $param['childHtml'];
    }

    public function controlDepartmentCategoryDropdown_model($param = array('selectedId' => 0, 'disabled' => false)) {
        $this->html = $this->disabled = '';
        
        if (isset($param['disabled']) && $param['disabled'] == true) {
            $this->disabled = 'disabled="' . $param['disabled'] . '"';
        }
        
        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.title_mn AS title
            FROM `gaz_category` AS C
            WHERE C.mod_id = 29
            ORDER BY C.order_num DESC');
        $this->html .= '<select class="form-control select2" name="departmentCatId" id="departmentCatId" required="required" ' . $this->disabled . '>';
        
        $this->html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '>' . ' - Сонгох - </option>';
        
        if ($this->query->num_rows() > 0) {
            
            foreach ($this->query->result() as $row) {
                
                if ($param['selectedId'] == $row->id) {
                    $this->html .= '<option value="' . $row->id . '" selected="selected">' . $row->title . '</option>';
                } else {
                    $this->html .= '<option value="' . $row->id . '">' . $row->title . '</option>';
                }
            }
        }
        
        $this->html .= '</select>';
        return $this->html;
        
    }
    
    public function controlDepartmentDropdown_model($param = array('catId' => 0, 'selectedId' => 0)) {
        
        $this->html = $this->queryString = $this->disabled = $name = '';
        
        if (isset($param['disabled']) && $param['disabled'] == true) {
            $this->disabled = 'disabled="' . $param['disabled'] . '"';
        }
        
        if (isset($param['required']) && $param['required'] == true) {
            $this->required = 'required="' . $param['required'] . '"';
        }
        
        if (isset($param['catId']) != 0) {
            $this->queryString .= ' AND D.cat_id = ' . $param['catId'];
        }
        
        if (isset($param['name'])) {
            $name = $param['name'];
        } else {
            $name = 'departmentId';
        }
        
        if ($this->session->userdata('adminAccessTypeId') == 2) {
            $this->queryString .= ' AND D.cat_id = ' . $this->session->userdata('adminDepartmentCatId');
        }
        $this->query = $this->db->query('
            SELECT 
                D.id,
                D.mod_id,
                D.title_mn AS title
            FROM `gaz_department` AS D
            WHERE 1 = 1 ' . $this->queryString . '
            ORDER BY D.order_num ASC');
        
        
        
        $this->html .= '<select class="form-control select2" name="' . $name . '" id="' . $name . '" ' . $this->required . ' ' . $this->disabled . '>';
        
        $this->html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '>' . ' - Сонгох - </option>';
        
        if ($this->query->num_rows() > 0) {
            
            foreach ($this->query->result() as $row) {
                
                if ($param['selectedId'] == $row->id) {
                    $this->html .= '<option value="' . $row->id . '" selected="selected">' . $row->title . '</option>';
                } else {
                    $this->html .= '<option value="' . $row->id . '">' . $row->title . '</option>';
                }
            }
        }
        
        $this->html .= '</select>';
        return $this->html;
        
    }
    
    public function controlDepartmentChildDropdown_model($param = array('modId' => 0, 'selectedId' => 0, 'editId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1)) {
        $this->html = '';
        $this->query = $this->db->query('
            SELECT 
                D.id,
                D.mod_id,
                D.title_mn AS title
            FROM `gaz_department` AS D
            WHERE D.parent_id = 0
            ORDER BY D.order_num DESC');
        $this->html .= '<select class="form-control select2" name="departmentChildId" id="departmentChildId" required="required">';
        
        $this->html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '>' . ' - Сонгох - </option>';
        
        if ($this->query->num_rows() > 0) {
            
            foreach ($this->query->result() as $row) {
                
                if ($param['selectedId'] == $row->id) {
                    $this->html .= '<option value="' . $row->id . '" selected="selected">' . $row->title . '</option>';
                } else {
                    $this->html .= '<option value="' . $row->id . '">' . $row->title . '</option>';
                }
                $this->html .= self::controlDepartmentChildDropdown_model(array('modId' => $row->mod_id, 'selectedId' => $param['selectedId'], 'editId' => 0, 'parentId' => $row->id, 'space' => '&nbsp; &nbsp;  &nbsp;  &nbsp; ', 'counter' => 1));
            }
        }
        
        $this->html .= '</select>';
        return $this->html;
    }
    
    public function controlDepartmentParentDropdown_model($param = array('modId' => 0, 'selectedId' => 0, 'editId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1)) {
        $this->html = '';
        $this->query = $this->db->query('
            SELECT 
                D.id,
                D.title_mn AS title
            FROM `gaz_department` AS D
            WHERE D.parent_id = ' . $param['parentId'] . ' AND D.id != ' . $param['editId'] . '
            ORDER BY D.order_num DESC');
        $this->html .= '<select class="form-control" name="parentId" id="parentId" size="10" required="required">';
        
        if ($param['counter'] == 1) {
            $this->html .= '<option value="0" selected="selected">' . ' - Сонгох - </option>';
        }
        
        if ($this->query->num_rows() > 0) {
            
            foreach ($this->query->result() as $row) {
                
                if ($param['selectedId'] == $row->id) {
                    $this->html .= '<option value="' . $row->id . '" selected="selected">' . $param['space'] . $row->title . '</option>';
                } else {
                    $this->html .= '<option value="' . $row->id . '">' . $param['space'] . $row->title . '</option>';
                }
                $param['counter'] = $param['counter'] + 1;
                //$this->html .= self::departmentChildDropdown_model(array('modId' => $param['modId'], 'selectedId' => $param['selectedId'], 'editId' => $param['editId'], 'parentId' => $row->id, 'space' => '&nbsp; &nbsp;  &nbsp;  &nbsp; ' . $param['space'], 'counter' => $param['counter']));
            }
        }
        
        $this->html .= '</select>';
        return $this->html;
        
    }
    
    public function departmentChildDropdown_model($param = array('modId' => 0, 'selectedId' => 0, 'editId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1)) {
        $this->htmlChild = '';
        $this->query = $this->db->query('
            SELECT 
                D.id,
                D.title_mn AS title
            FROM `gaz_department` AS D
            WHERE D.parent_id = ' . $param['parentId'] . ' 
            ORDER BY D.order_num DESC');
        
        
        if ($param['counter'] == 1) {
            $this->htmlChild .= '<option value="0" selected="selected">' . ' - Сонгох - </option>';
        }
        
        if ($this->query->num_rows() > 0) {
            
            foreach ($this->query->result() as $row) {
                
                if ($param['selectedId'] == $row->id) {
                    $this->htmlChild .= '<option value="' . $row->id . '" selected="selected">' . $param['space'] . $row->title . '</option>';
                } else {
                    $this->htmlChild .= '<option value="' . $row->id . '">' . $param['space'] . $row->title . '</option>';
                }
                $param['counter'] = $param['counter'] + 1;
                $this->htmlChild .= self::controlDepartmentChildDropdown_model(array('modId' => $param['modId'], 'selectedId' => $param['selectedId'], 'editId' => $param['editId'], 'parentId' => $row->id, 'space' => '&nbsp; &nbsp;  &nbsp;  &nbsp; ' . $param['space'], 'counter' => $param['counter']));
            }
        }
        
        return $this->htmlChild;
        
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
        $str = '<select class="form-control select2" name="catId" id="catId" ' . (isset($param['required']) ? 'required="required"' : '') . '>';
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
        
        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));
        
        $data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'parent_id' => $this->input->post('parentId'),
                'title_mn' => $this->input->post('titleMn'),
                'title_en' => $this->input->post('titleMn'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => '0000-00-00 00:00:00',
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'is_active_mn' => $this->input->post('isActiveMn'),
                'is_active_en' => $this->input->post('isActiveEn'),
                'order_num' => $this->input->post('orderNum')
            )
        );
        $result = $this->db->insert_batch($this->db->dbprefix . 'department', $data);

        if ($result) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {
        
        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_UPDATE,
            'data' => json_encode($_POST)));
        
        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'parent_id' => $this->input->post('parentId'),
            'title_mn' => $this->input->post('titleMn'),
            'title_en' => $this->input->post('titleMn'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'is_active_mn' => $this->input->post('isActiveMn'),
            'is_active_en' => $this->input->post('isActiveEn'),
            'order_num' => $this->input->post('orderNum')
        );
        $this->db->where('id', $this->input->post('id'));
        if ($this->db->update($this->db->dbprefix . 'department', $this->data)) {
            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $this->result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $this->result;
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
        
        foreach ($this->input->post('id') as $key => $row) {
            
            $this->db->where('id', $this->input->post('id[' . $key . ']'));
            $this->db->delete($this->db->dbprefix . 'department');
            
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
                created_date,
                modified_date,
                created_user_id,
                modified_user_id
            FROM `gaz_department`
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
            $this->string .= ' <a href="' . Sdepartment::$path . 'index/' . $param['modId'] . '"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }
        
        return $this->string;
    }
    
}
