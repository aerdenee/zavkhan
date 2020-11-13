<?php

class SnifsQuestion_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Suser_model', 'user');
    }

    public function addFormData_model() {
        return json_decode(json_encode(array(
            'id' => 0,
            'mod_id' => 75,
            'cat_id' => 0,
            'title' => '',
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s'),
            'created_user_id' => $this->session->adminUserId,
            'modified_user_id' => 0,
            'order_num' => getOrderNum(array('table' => 'nifs_question', 'field' => 'order_num')),
            'is_active' => 1)));
    }

    public function editFormData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                NQ.id,
                NQ.mod_id,
                NQ.cat_id,
                NQ.title,
                NQ.created_date,
                NQ.modified_date,
                NQ.created_user_id,
                NQ.modified_user_id,
                NQ.order_num,
                NQ.is_active
            FROM `gaz_nifs_question` AS NQ
            WHERE NQ.id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return self::addFormData_model();
    }

    public function listsCount_model($param = array()) {
        $this->queryString = $this->getString = '';

        $this->auth = authentication(array('authentication' => $this->session->authentication, 'moduleMenuId' => $param['moduleMenuId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 1) {
            $this->queryString .= '';
        } elseif ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND NQ.created_user_id = ' . $this->session->adminUserId;
        } else {
            $this->queryString .= ' AND NQ.created_user_id = -1';
        }

//        if (isset($this->session->adminPartnerId)) {
//            $this->queryString .= ' AND K.user_partner_id IN (' . $this->partner->getChildPartners_model(array($this->session->adminPartnerId)) . ')';
//        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND NQ.cat_id = ' . $param['catId'];
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(NQ.title) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
        }

        $this->query = $this->db->query('
            SELECT 
                NQ.id
            FROM `gaz_nifs_question` AS NQ 
            WHERE 1 = 1 ' . $this->queryString);

        return $this->query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $this->queryString = $this->getString = '';

        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'moduleMenuId' => $param['moduleMenuId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 1) {
            $this->queryString .= '';
        } elseif ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND NQ.created_user_id = ' . $this->session->adminUserId;
        } else {
            $this->queryString .= ' AND NQ.created_user_id = -1';
        }
//        if (isset($this->session->adminPartnerId)) {
//            $this->queryString .= ' AND K.user_partner_id IN (' . $this->partner->getChildPartners_model(array($this->session->adminPartnerId)) . ')';
//        }

        if ($param['catId'] != 0) {
            $this->queryString .= ' AND NQ.cat_id = ' . $param['catId'];
            $this->getString .= form_hidden('catId', $param['catId']);
        }

        if ($param['keyword'] != '') {
            $this->queryString .= ' AND (LOWER(NQ.title) LIKE LOWER(\'%' . $param['keyword'] . '%\'))';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        $this->query = $this->db->query('
            SELECT 
                NQ.id,
                NQ.mod_id,
                NQ.cat_id,
                C.title AS cat_title,
                NQ.title,
                NQ.created_date,
                NQ.modified_date,
                NQ.created_user_id,
                NQ.modified_user_id,
                NQ.order_num,
                NQ.is_active
            FROM `gaz_nifs_question` AS NQ
            LEFT JOIN `gaz_category` AS C ON NQ.cat_id = C.id
            WHERE 1 = 1 ' . $this->queryString . '
            ORDER BY NQ.order_num DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        $this->html = form_open('', array('class' => 'form-horizontal', 'id' => 'form-address-init', 'enctype' => 'multipart/form-data'));
        $this->html .= form_hidden('modId', $this->auth->modId);
        $this->html .= form_hidden('limit', $param['limit']);
        $this->html .= form_hidden('page', $param['page']);
        $this->html .= $this->auth->hidden;
        $this->html .= $this->getString;

        $this->html .= '<div class="panel panel-white">';
        $this->html .= '<div class="panel-heading">';
        $this->html .= '<h6 class="panel-title">' . $param['title'] . '</h6>';
        $this->html .= '<div class="heading-elements not-collapsible">';
        //$this->html .= ($param['totalRows'] > 0 ? '<span class="label bg-blue heading-text">Нийт ' . $param['totalRows'] . ' бичлэг байна</span>' : '');
        $this->html .= $param['paginationHtml'];
        $this->html .= '</div>';
        $this->html .= '</div>';

        $this->html .= '<div class="panel-toolbar panel-toolbar-inbox">';
        $this->html .= '<div class="navbar navbar-default">';
        $this->html .= '<ul class="nav navbar-nav visible-xs-block no-border">';
        $this->html .= '<li>';
        $this->html .= '<a class="text-center collapsed legitRipple" data-toggle="collapse" data-target="#inbox-toolbar-toggle-single">';
        $this->html .= '<i class="icon-circle-down2"></i>';
        $this->html .= '</a>';
        $this->html .= '</li>';
        $this->html .= '</ul>';

        $this->html .= '<div class="navbar-collapse collapse" id="inbox-toolbar-toggle-single">';

        $this->html .= '<div class="navbar-btn navbar-left">' . self::searchKeywordView_model() . '</div>';

        $this->html .= '<div class="btn-group navbar-btn navbar-right">';
        if ($this->auth->our->create == 1) {
            $this->html .= form_button('addNifsQuestion', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Шинэ (F2)</span>', 'class="btn btn-default legitRipple" onclick="_addFormNifsQuestion({elem: this});"', 'button');
        } else {
            $this->html .= form_button('addNifsQuestion', '<i class="icon-pencil7"></i> <span class="hidden-xs position-right">Шинэ (F2)</span>', 'class="btn btn-default legitRipple" disabled="true"', 'button');
        }

        $this->html .= form_button('searchNifsQuestion', '<i class="icon-search4"></i> <span class="hidden-xs position-right">Хайх (F3)</span>', 'class="btn btn-default legitRipple" onclick="_advensedSearchNifsQuestion({elem: this});"', 'button');


        $this->html .= '</div>';

        $this->html .= '</div>';
        $this->html .= '</div>';
        $this->html .= '</div>';

        if ($this->query->num_rows() > 0) {

            $this->html .= '<div class="table-responsive">';
            $this->html .= '<table class="table">';
            $this->html .= '<thead>';
            $this->html .= '<tr>';
            $this->html .= '<th style="width:30px;">#</th>';
            $this->html .= '<th>Гарчиг</th>';
            $this->html .= '<th style="width:200px;">Ангилал</th>';
            $this->html .= '<th style="width:60px;">Төлөв</th>';
            $this->html .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->html .= '</tr>';
            $this->html .= '</thead>';
            $this->html .= '<tbody data-link="row" class="rowlink">';

            $i = 0;

            foreach ($this->query->result() as $key => $row) {
                $this->html .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '">';
                $this->html .= '<td>' . ++$i . '</td>';
                $this->html .= '<td class="context-menu-nifs-question-selected-row" style="padding-left:' . $param['space'] . 'px;">' . $row->title . '</td>';
                $this->html .= '<td class="context-menu-nifs-question-selected-row">' . $row->cat_title . '</td>';
                $this->html .= '<td class="context-menu-nifs-question-selected-row text-center">' . ($row->is_active == 1 ? '<span class="label label-success"><i class="fa fa-check"></i></span>' : '<span class="label label-danger"><i class="fa fa-lock"></i></span>') . '</td>';
                $this->html .= '<td class="text-center">';
                $this->html .= '<ul class="icons-list">';

                if (($this->auth->our->update == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->update == 1 and $row->created_user_id != $this->session->adminUserId)) {
                    $this->html .= '<li><a href="javascript:;" onclick="_editFormNifsQuestion({elem: this, id:' . $row->id . '});" title="Засах" data-action="edit"></a></li>';
                } else {
                    $this->html .= '<li><a href="javascript:;" class="disabled" title="Засах" data-action="edit"></a></li>';
                }

                if (($this->auth->our->delete == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->delete == 1 and $row->created_user_id != $this->session->adminUserId)) {
                    $this->html .= '<li><a href="javascript:;" onclick="_removeNifsQuestion({elem: this, id:' . $row->id . '});" title="Устгах" data-action="delete"></a></li>';
                } else {
                    $this->html .= '<li><a href="javascript:;" class="disabled" title="Устгах" data-action="delete"></a></li>';
                }

                $this->html .= '</ul>';
                $this->html .= '</td>';
                $this->html .= '</tr>';

                $this->html .= self::listsChild_model(array('parentId' => $row->id, 'space' => 50, 'autoNumber' => $i, 'moduleMenuId' => $param['moduleMenuId']));
            }


            $this->html .= '</tbody>';
            $this->html .= '</table>';
            $this->html .= '</div>';
            $this->html .= '<div class="panel-footer">';
            $this->html .= '<div class="navbar text-right">' . $param['paginationHtml'] . '</div>';
            $this->html .= '</div>';
        } else {
            $this->html .= '<div class="panel-body">';
            $this->html .= '<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Бичлэг байхгүй</div>';
            $this->html .= '</div>';
        }

        $this->html .= '</div>';


        $this->html .= form_close();
        return $this->html;
    }

    public function listsChild_model($param = array('parentId' => 0, 'space' => 10, 'autoNumber' => 1)) {

        $html = '';
        $this->auth = authentication(array('authentication' => $this->session->userdata['authentication'], 'moduleMenuId' => $param['moduleMenuId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 1) {
            $this->queryString .= '';
        } else if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND NQ.created_user_id = ' . $this->session->adminUserId;
        } else if ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND NQ.created_user_id = -1';
        }

        $this->query = $this->db->query('
            SELECT 
                NQ.id,
                NQ.mod_id,
                NQ.cat_id,
                C.title AS cat_title,
                NQ.title,
                NQ.created_date,
                NQ.modified_date,
                NQ.created_user_id,
                NQ.modified_user_id,
                NQ.order_num,
                NQ.is_active
            FROM `gaz_nifs_question` AS NQ
            LEFT JOIN `gaz_category` AS C ON NQ.cat_id = C.id
            WHERE NQ.parent_id = ' . $param['parentId'] . '
            ORDER BY NQ.order_num ASC');
        if ($this->query->num_rows() > 0) {

            $j = 0;
            foreach ($this->query->result() as $row) {
                $html .= '<tr data-mod-id="' . $row->mod_id . '" data-id="' . $row->id . '">';
                $html .= '<td>' . $param['autoNumber'] . '.' . ++$j . '</td>';
                $html .= '<td class="context-menu-nifs-question-selected-row" style="padding-left:' . $param['space'] . 'px;">' . $row->title . '</td>';
                $html .= '<td class="context-menu-nifs-question-selected-row">' . $row->cat_title . '</td>';
                $html .= '<td class="context-menu-nifs-question-selected-row text-center">' . ($row->is_active == 1 ? '<span class="label label-success"><i class="fa fa-check"></i></span>' : '<span class="label label-danger"><i class="fa fa-lock"></i></span>') . '</td>';
                $html .= '<td class="text-center">';
                $html .= '<ul class="icons-list">';

                if (($this->auth->our->update == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->update == 1 and $row->created_user_id != $this->session->adminUserId)) {
                    $html .= '<li><a href="javascript:;" onclick="_editFormNifsQuestion({elem: this, id:' . $row->id . '});" title="Засах" data-action="edit"></a></li>';
                } else {
                    $html .= '<li><a href="javascript:;" class="disabled" title="Засах" data-action="edit"></a></li>';
                }

                if (($this->auth->our->delete == 1 and $row->created_user_id == $this->session->adminUserId) or ( $this->auth->your->delete == 1 and $row->created_user_id != $this->session->adminUserId)) {
                    $html .= '<li><a href="javascript:;" onclick="_removeNifsQuestion({elem: this, id:' . $row->id . '});" title="Устгах" data-action="delete"></a></li>';
                } else {
                    $html .= '<li><a href="javascript:;" class="disabled" title="Устгах" data-action="delete"></a></li>';
                }

                $html .= '</ul>';
                $html .= '</td>';
                $html .= '</tr>';
                $html .= self::listsChild_model(array('parentId' => $row->id, 'space' => (intval($param['space']) + 30), 'autoNumber' => $param['autoNumber'] . '.' . $j, 'moduleMenuId' => $param['moduleMenuId']));
            }
        }
        return $html;
    }

    public function insert_model() {
        $this->data = array(
            array(
                'id' => getUID('nifs_question'),
                'mod_id' => $this->input->post('modId'),
                'cat_id' => $this->input->post('catId'),
                'title' => $this->input->post('title'),
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => 0,
                'order_num' => $this->input->post('orderNum'),
                'is_active' => $this->input->post('isActive')));

        if ($this->db->insert_batch($this->db->dbprefix . 'nifs_question', $this->data)) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
    }

    public function update_model() {

        $this->data = array(
            'mod_id' => $this->input->post('modId'),
            'cat_id' => $this->input->post('catId'),
            'title' => $this->input->post('title'),
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_user_id' => $this->session->adminUserId,
            'order_num' => $this->input->post('orderNum'),
            'is_active' => $this->input->post('isActive')
        );
        $this->db->where('id', $this->input->post('id'));

        if ($this->db->update($this->db->dbprefix . 'nifs_question', $this->data)) {
            return array('status' => 'success', 'message' => 'Өөрчлөлтийг амжилттай хадгаллаа...');
        }
        return array('status' => 'error', 'message' => 'Өөрчлөлтийн түүхийг хадгалах үед алдаа гарлаа...');
    }

    public function delete_model() {
        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete($this->db->dbprefix . 'nifs_question')) {
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function updateQuestionParam_model($param = array('modId' => 0, 'contId' => 0, 'expertId' => array())) {

        $this->qeustionString = '';

        $this->db->where('mod_id', $param['modId']);
        $this->db->where('cont_id', $param['contId']);
        $this->db->delete($this->db->dbprefix . 'nifs_question_detail');


        if (is_array($param['questionId']) and $param['questionId']['0'] > 0) {

            foreach ($param['questionId'] as $value) {

                if ($value > 0) {

                    $this->nifsQuestionData = $this->nifsQuestion->getData_model(array('selectedId' => $value));

                    if ($this->nifsQuestionData) {
                        $this->qeustionString .= $this->nifsQuestionData->title . ', ';
                    }

                    $this->data = array(
                        array(
                            'id' => getUID('nifs_question_detail'),
                            'mod_id' => $param['modId'],
                            'cont_id' => $param['contId'],
                            'question_id' => $value,
                            'created_date' => date('Y-m-d H:i:s'),
                            'modified_date' => date('Y-m-d H:i:s'),
                            'created_user_id' => $this->session->adminUserId,
                            'modified_user_id' => $this->session->adminUserId
                    ));

                    if (!$this->db->insert_batch($this->db->dbprefix . 'nifs_question_detail', $this->data)) {
                        return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
                    }
                }
            }
            $this->qeustionString = rtrim($this->qeustionString, ', ');


            $this->db->where('id', $param['contId']);

            if ($this->db->update($this->db->dbprefix . $param['table'], array('question' => $this->qeustionString))) {
                return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
            }
        } else if (is_array($param['questionId']) and $param['questionId']['0'] == 0) {

            $this->data = array(
                array(
                    'id' => getUID('nifs_question_detail'),
                    'mod_id' => $param['modId'],
                    'cont_id' => $param['contId'],
                    'question_id' => 0,
                    'created_date' => date('Y-m-d H:i:s'),
                    'modified_date' => date('Y-m-d H:i:s'),
                    'created_user_id' => $this->session->adminUserId,
                    'modified_user_id' => $this->session->adminUserId
            ));

            if ($this->db->insert_batch($this->db->dbprefix . 'nifs_question_detail', $this->data)) {
                return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
            }
        }

        $this->qeustionString = rtrim($this->qeustionString, ', ');


        $this->db->where('id', $param['contId']);

        if ($this->db->update($this->db->dbprefix . $param['table'], array('question' => $this->qeustionString))) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        }
    }

    public function searchKeywordView_model() {
        $this->string = '';
        $this->showResetBtn = FALSE;

        if ($this->input->get('catId')) {
            $this->cat = $this->category->getData_model(array('selectedId' => $this->input->get('catId')));
            $this->string .= '<span class="label label-default label-rounded">' . $this->cat->title . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('keyword')) {
            $this->string .= '<span class="label label-default label-rounded">' . $this->input->get('keyword') . '</span>';
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            //' . Scrime::$path . $param['path'] . '/' . $param['modId'] . '
            $this->string .= ' <a href="javascript:;" onclick="_initNifsQuestion({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return $this->string;
    }

    public function controlNifsQuestionMultipleDropDown_model($param = array('modId' => 0, 'contId' => 0)) {

        $htmlExtra = $string = $isDisabled = '';

        if (!isset($param['name'])) {
            $param['name'] = 'questionId[]';
        }

        if (!isset($param['readonly'])) {
            $param['readonly'] = 'false';
        }

        if (!isset($param['disabled'])) {
            $param['disabled'] = 'false';
        }

        if (!isset($param['required'])) {
            $param['required'] = 'false';
        }

        $query = $this->db->query('
            SELECT 
                NQD.question_id
            FROM gaz_nifs_question_detail AS NQD 
            WHERE NQD.mod_id = ' . $param['modId'] . ' AND NQD.cont_id = ' . $param['contId']);

        $numRows = $query->num_rows();
        if ($numRows > 0) {
            $i = 1;
            foreach ($query->result() as $key => $row) {

                if ($key > 0) {
                    $param['isDeleteButton'] = 1;
                }

                if ($i == 2) {
                    $htmlExtra .= '<div id="' . $param['initControlHtml'] . '">';
                }

                $htmlExtra .= '<div class="form-group row" data-question-row="question-row">';
                $htmlExtra .= form_label('Асуулт', 'Асуулт', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE));
                $htmlExtra .= '<div class="col-8">';

                $htmlExtra .= '<div class="input-group">';
                $htmlExtra .= '<span class="select2-group">';

                $htmlExtra .= self::controlNifsQuestionDropDown_model(array(
                            'selectedId' => $row->question_id,
                            'сatId' => $param['catId'],
                            'name' => $param['name'],
                            'readonly' => $param['readonly'],
                            'disabled' => $param['disabled'],
                            'required' => $param['required']));

                $htmlExtra .= '</span>';
                $htmlExtra .= '<span class="input-group-append">';


                if (isset($param['isDeleteButton']) and $param['isDeleteButton'] == '1') {
                    $htmlExtra .= '<span class="input-group-text bg-primary border-primary text-white cursor-pointer" onclick="_removeControlNifsQuestion({elem:this, modId:' . $param['modId'] . ', contId:' . $param['contId'] . ', catId: ' . $param['catId'] . '});"><i class="icon-cancel-circle2"></i></span>';
                } else {
                    $htmlExtra .= '<span class="input-group-text bg-primary border-primary text-white cursor-pointer" onclick="_addControlNifsQuestion({elem:this, modId:0, contId:0, catId: ' . $param['catId'] . ', initControlHtml: \'' . $param['initControlHtml'] . '\'});" ' . $isDisabled . '><i class="icon-plus-circle2"></i></span>';
                }

                $htmlExtra .= '</span>';
                $htmlExtra .= '</div>';
                $htmlExtra .= '</div>';
                $htmlExtra .= '</div>';

                if ($numRows == 1) {
                    $htmlExtra .= '<div id="' . $param['initControlHtml'] . '">';
                }
                if ($i == $numRows) {
                    $htmlExtra .= '</div>';
                }

                $i++;
            }
        } else {
            $htmlExtra .= '<div class="form-group row" data-question-row="question-row">';
            $htmlExtra .= form_label('Асуулт', 'Асуулт', array('required' => 'required', 'class' => 'col-4 col-form-label text-right', 'defined' => TRUE));
            $htmlExtra .= '<div class="col-8">';

            $htmlExtra .= '<div class="input-group">';
            $htmlExtra .= '<span class="select2-group">';

            $htmlExtra .= self::controlNifsQuestionDropDown_model(array(
                        'selectedId' => 0,
                        'сatId' => $param['catId'],
                        'name' => $param['name'],
                        'readonly' => $param['readonly'],
                        'disabled' => $param['disabled'],
                        'required' => $param['required']));

            $htmlExtra .= '</span>';
            $htmlExtra .= '<span class="input-group-append">';


            if (isset($param['isDeleteButton']) and $param['isDeleteButton'] == '1') {
                $htmlExtra .= '<span class="input-group-text bg-primary border-primary text-white cursor-pointer" onclick="_removeControlNifsQuestion({elem:this, modId:' . $param['modId'] . ', contId:' . $param['contId'] . ', catId: ' . $param['catId'] . '});"><i class="icon-cancel-circle2"></i></span>';
            } else {
                $htmlExtra .= '<span class="input-group-text bg-primary border-primary text-white cursor-pointer" onclick="_addControlNifsQuestion({elem:this, modId:0, contId:0, catId: ' . $param['catId'] . ', initControlHtml: \'' . $param['initControlHtml'] . '\'});" ' . $isDisabled . '><i class="icon-plus-circle2"></i></span>';
            }

            $htmlExtra .= '</span>';
            $htmlExtra .= '</div>';
            $htmlExtra .= '</div>';
            $htmlExtra .= '</div>';
            $htmlExtra .= '<div id="' . $param['initControlHtml'] . '"></div>';
        }

        return $htmlExtra;
    }

    public function controlNifsQuestionDropDown_model($param = array('parentId' => 0, 'selectedId' => 0, 'name' => '')) {
        $this->html = $this->string = $name = $queryString = '';

        if (isset($param['name'])) {
            $name = $param['name'];
        } else {
            $name = 'questionId';
        }

        if (isset($param['disabled']) and strtolower($param['disabled']) == 'true') {
            $this->string .= ' disabled="true"';
        }

        if (isset($param['required']) and strtolower($param['required']) == 'true') {
            $this->string .= ' required="true"';
        }

        if (isset($param['readonly']) and strtolower($param['readonly']) == 'true') {
            $this->string .= ' disabled="true"';
            $this->html .= form_hidden($param['readonly'], $param['selectedId']);
        }

        if (isset($param['catId']) and $param['catId'] != 0) {
            $queryString .= ' AND NQ.cat_id = ' . $param['catId'];
        }

        $this->html .= '<select name="' . $name . '" id="' . $name . '" class="select2" ' . $this->string . '>';
        $this->html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';

        $this->query = $this->db->query('
                SELECT 
                    NQ.id,
                    NQ.title
                FROM `gaz_nifs_question` AS NQ
                WHERE 
                    NQ.is_active = 1 ' . $queryString . '
                ORDER BY NQ.order_num ASC');

        if ($this->query->num_rows() > 0) {
            foreach ($this->query->result() as $row) {
                $this->html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';
            }
        }

        $this->html .= '</select>';

        return $this->html;
    }

    public function getData_model($param = array('selectedId' => 0)) {
        $this->query = $this->db->query('
             SELECT 
                NQ.id,
                NQ.mod_id,
                NQ.cat_id,
                NQ.title,
                NQ.created_date,
                NQ.modified_date,
                NQ.created_user_id,
                NQ.modified_user_id,
                NQ.order_num,
                NQ.is_active
            FROM `gaz_nifs_question` AS NQ
            WHERE NQ.id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

}
