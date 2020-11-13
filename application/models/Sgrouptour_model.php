<?php
class Sgrouptour_model extends CI_Model {

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
            'cont_id' => 0,
            'group_size' => 0,
            'group_date' => date('Y-m-d'),
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'created_user_id' => 0,
            'modified_user_id' => 0,
            'is_active' => 1,
            'modified_user_id' => 0,
            'order_num' => getOrderNum(array('table' => 'group_tour', 'field' => 'order_num')),
            'intro_text' => ''
        );
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                cat_id,
                cont_id,
                group_size,
                group_date,
                created_date,
                modified_date,
                created_user_id,
                modified_user_id,
                is_active,
                modified_user_id,
                order_num,
                intro_text
            FROM `gaz_group_tour`
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
            $this->queryString .= ' AND GT.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND GT.created_user_id = -1';
        }
        
        $this->query = $this->db->query('
            SELECT 
                GT.id
            FROM `gaz_group_tour` AS GT 
            WHERE 1 = 1 ' . $this->queryString . ' AND GT.mod_id = ' . $param['modId'] . ' 
            ORDER BY GT.id DESC');

        $this->result = $this->query->result();

        return count($this->result);
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '')) {

        $this->queryString = $this->getString = '';

        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'modId' => $param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND GT.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND GT.created_user_id = -1';
        }
        
        $this->query = $this->db->query('
            SELECT 
                GT.id,
                GT.mod_id,
                GT.cat_id,
                CAT.title_mn AS category_title,
                GT.cont_id,
                C.title_mn AS title,
                GT.group_size,
                GT.guest,
                GT.group_date,
                GT.created_date,
                GT.modified_date,
                GT.created_user_id,
                GT.modified_user_id,
                GT.is_active,
                GT.modified_user_id,
                GT.order_num
            FROM `gaz_group_tour` AS GT
            LEFT JOIN `gaz_content` AS C ON GT.cont_id = C.id
            LEFT JOIN `gaz_category` AS CAT ON GT.cat_id = CAT.id
            WHERE 1 = 1 ' . $this->queryString . ' AND GT.mod_id = ' . $param['modId'] . ' 
            ORDER BY GT.id DESC
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
        $this->data['html'] .= '<li>' . anchor(Sgrouptour::$path . 'index/' . $param['modId'], '<i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i>', array()) . '</li>';
        $this->data['html'] .= '</ul>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '</div>';
        $this->data['html'] .= '<div class="panel-body" style="padding-bottom:5px;">';
        $this->data['html'] .= '<div class="pull-left">' . self::searchKeywordView_model(array('modId' => $param['modId'])) . '</div>';
        $this->data['html'] .= '<div class="pull-right">';
        $this->data['html'] .= '<ul class="list-inline heading-text">';

        $this->data['html'] .= '<li style="padding-right:10px;">' . anchor(Sgrouptour::$path . 'add/' . $param['modId'], '<i class="fa fa-plus"></i> Нэмэх', 'class="btn btn-info btn-rounded btn-xs"') . '</li>';
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
            $this->data['html'] .= '<th>Аялал</th>';
            $this->data['html'] .= '<th style="width:250px;">Төрөл</th>';
            $this->data['html'] .= '<th style="width:100px;">Эхлэх</th>';
            $this->data['html'] .= '<th style="width:100px;">Жуулчин тоо</th>';
            $this->data['html'] .= '<th style="width:100px;">Төлөв</th>';
            $this->data['html'] .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';

            $i = 1;
            foreach ($this->result as $row) {

                $this->data['html'] .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '">';
                $this->data['html'] .= '<td>' . $i . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-grouptour">' . $row->title . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-grouptour text-center">' . $row->category_title . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-grouptour text-center">' . dateLastModified(array('createdDate' => $row->group_date, 'modifiedDate' => $row->group_date)) . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-grouptour text-center">' . $row->guest . '/' . $row->group_size . '</td>';
                $this->data['html'] .= '<td class="context-menu-selected-row-grouptour text-center">' . (intval($row->is_active) == 1 ? '<span class="label bg-teal dropdown-toggle">Идэвхтэй</span>' : '<span class="label label-danger">Идэвхгүй</span>') . '</td>';
                $this->data['html'] .= '<td class="text-center">';
                $this->data['html'] .= '<ul class="icons-list">';
                $this->data['html'] .= '<li><a href="' . Sgrouptour::$path . 'edit/' . $param['modId'] . '/' . $row->id . '"><i class="icon-pencil7"></i></a></li>';
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
    
    public function controlGroupTourListDropdown_model($param = array('modId' => 0, 'selectedId' => 0, 'required' => true)) {
        $this->query = $this->db->query('
            SELECT 
                id,
                title_mn AS title
            FROM `gaz_content`
            WHERE is_active_mn = 1 AND  mod_id = ' . $param['modId'] . ' 
            ORDER BY order_num ASC');
        
        $this->html = '<select class="form-control select2" name="contId" id="contId" ' . (isset($param['required']) ? 'required="required"' : '') . ' ' . (isset($param['isDisabled']) ? 'disabled="disabled"' : '') . '>';
        if ($param['selectedId'] == 0) {
            $this->html .= '<option value="0" selected="selected">' . ' - Сонгох - </option>';
        } else {
            $this->html .= '<option value="0">' . ' - Сонгох - </option>';
        }
        if ($this->query->num_rows() > 0) {
            foreach ($this->query->result() as $row) {
                $this->html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';
            }
        }
        $this->html .= '</select>';
        return $this->html;
    }
    
    public function controlGroupSizeListDropdown_model($param = array('modId' => 0, 'selectedId' => 0, 'required' => true)) {
        
        
        $this->html = '<select class="form-control select2" name="groupSize" id="groupSize" ' . (isset($param['required']) ? 'required="required"' : '') . ' ' . (isset($param['isDisabled']) ? 'disabled="disabled"' : '') . '>';
        if ($param['selectedId'] == 0) {
            $this->html .= '<option value="0" selected="selected">' . ' - Сонгох - </option>';
        } else {
            $this->html .= '<option value="0">' . ' - Сонгох - </option>';
        }
        
        for ($i = 1; $i<=20; $i++) {
            $this->html .= '<option value="' . $i . '" ' . ($param['selectedId'] == $i ? 'selected="selected"' : '') . '>' . $i . ' хүн</option>';
        }
        
        $this->html .= '</select>';
        return $this->html;
    }
    
    public function insert_model($param = array()) {
        $this->data = array(
            array(
                'id' => $param['getUID'],
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'cont_id' => $this->input->post('contId'),
                'group_size' => $this->input->post('groupSize'),
                'group_date' => $this->input->post('groupDate'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => '0000-00-00 00:00:00',
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'is_active' => $this->input->post('isActive'),
                'order_num' => $this->input->post('orderNum'),
                'intro_text' => $this->input->post('introText')
            )
        );
        $result = $this->db->insert_batch($this->db->dbprefix . 'group_tour', $this->data);

        if ($result) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }

        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model($param = array()) {
        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'cont_id' => $this->input->post('contId'),
            'group_size' => $this->input->post('groupSize'),
            'group_date' => $this->input->post('groupDate'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'is_active' => $this->input->post('isActive'),
            'order_num' => $this->input->post('orderNum'),
            'intro_text' => $this->input->post('introText')
        );
        $this->db->where('id', $this->input->post('id'));
        
        if ($this->db->update($this->db->dbprefix . 'group_tour', $this->data)) {
            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $this->result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $this->result;
    }

    public function delete_model() {
        $data = $this->input->post('id');
        foreach ($data as $id) {
            $this->db->where('id', $id);
            $this->db->delete($this->db->dbprefix . 'group_tour');
        }
        return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                GT.id,
                GT.mod_id,
                GT.cat_id,
                GT.cont_id,
                GT.group_size,
                GT.group_date,
                GT.created_date,
                GT.modified_date,
                GT.created_user_id,
                GT.modified_user_id,
                GT.is_active,
                GT.modified_user_id,
                GT.order_num,
                C.title_mn AS title
            FROM `' . $this->db->dbprefix . 'group_tour` AS GT
            LEFT JOIN `' . $this->db->dbprefix . 'content` AS C ON GT.cont_id = C.id
            WHERE GT.id = ' . $param['selectedId']);
        
        if ($this->query->num_rows() > 0) {
            return $this->query->result();
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
            $this->string .= ' <a href="' . Sgrouptour::$path . 'index/' . $param['modId'] . '"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }
        
        return $this->string;
    }
    
}
