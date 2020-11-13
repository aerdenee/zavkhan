<?php

class Scomment_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('Slog_model', 'slog');
    }

    public function insert_model($param = array()) {

        $this->slog->log_model(array(
            'modId' => $this->input->post('modId'),
            'createdUserId' => $this->session->adminUserId,
            'type' => LOG_TYPE_INSERT,
            'data' => json_encode($_POST)));
        
        $this->data = array(
            array(
                'id' => getUID('comment'),
                'mod_id' => $this->input->post('modId'),
                'cont_id' => $this->input->post('contId'),
                'people_id' => $this->session->userdata['adminPeopleId'],
                'user_id' => $this->session->userdata['adminUserId'],
                'parent_id' => $this->input->post('parentId'),
                'comment' => ($this->input->post('parentId') != 0 ? $this->input->post('replyComment') : $this->input->post('comment')),
                'created_date' => date('Y-m-d H:i:s'),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'is_active' => 1,
                'lang_id' => $this->session->userdata['adminLangId']));

        if ($this->db->insert_batch($this->db->dbprefix . 'comment', $this->data)) {

            if ($this->contentUpdate_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('contId')))) {
                return array('status' => 'success', 'message' => 'Сэтгэгдэл амжилттай нэмэгдлээ');
            }
            
        }
        
        return array('status' => 'error', 'message' => 'Сэтгэгдэл нэмэх үед алдаа гарлаа');
        
    }

    public function contentUpdate_model($param = array('modId' => 0, 'contId' => 0)) {
        
        $this->queryModule = $this->db->query('SELECT `table` FROM `gaz_module` WHERE `id` = ' . $param['modId']);

        if ($this->queryModule->num_rows() > 0) {

            $this->module = $this->queryModule->row();
            $this->queryComment = $this->db->query('SELECT id FROM `gaz_comment` WHERE is_active = 1 AND `mod_id` = ' . $param['modId'] . ' AND cont_id = ' . $param['contId']);

            $this->db->where('id', $param['contId']);
            if ($this->db->update($this->db->dbprefix . $this->module->table, array('comment_count' => $this->queryComment->num_rows()))) {
                return true;
            }
        }
        return false;
    }

    public function listsCount_model($param = array()) {

        $this->query = $this->db->query('
            SELECT 
                C.id
            FROM `gaz_comment` AS C 
            WHERE C.is_active = 1 AND C.mod_id = ' . $param['modId'] . ' AND C.cont_id = ' . $param['contId']);

        return $this->query->num_rows();
    }

    public function lists_model($param = array()) {

        $this->html = '';

        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.mod_id,
                C.cont_id,
                C.people_id,
                C.title,
                C.comment,
                DATE_FORMAT(C.created_date,\'%Y-%m-%d %H:%i\') AS created_date,
                C.ip_address,
                C.is_active,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name,
                (case when (HP.pic is null or HP.pic = \'\') then \'default.svg\' else concat(\'s_\', HP.pic) end) as pic
            FROM `gaz_comment` AS C
            LEFT JOIN `gaz_hr_people` AS HP ON C.people_id = HP.id
            WHERE parent_id = 0 AND C.is_active = 1 AND C.mod_id = ' . $param['modId'] . ' AND C.cont_id = ' . $param['contId'] . '
            ORDER BY C.created_date ' . $param['sortType']);

        $this->html .= '<hr>
                <div class="well mt-3">
                    Та байгууллагын дотоод журмыг баримтлан ёс зүйтэй сэтгэгдэл үлдээнэ үү.
                </div>
                <div class="clearfix"></div>
                <h4 class="text-muted"><span class="comment-count-html"></span></h4>';
        if ($this->query->num_rows() > 0) {
            $this->html .= '<div id="comment_list" style="display: block;">';
            $this->html .= '<div class="new_comment">';
            $this->html .= '<ol class="comment-list m-0 p-0">';
            $i = 1;
            foreach ($this->query->result() as $key => $row) {

                $this->html .= '<li>';
                    $this->html .= '<div class="comment-body">';
                        $this->html .= '<div id="comment-meta">';
                            $this->html .= '<div class="jk vcard">';
                                $this->html .= '<div class="avatar-wrapper"><img src="' . UPLOADS_USER_PATH . $row->pic . '"></div>';
                                $this->html .= '<div class="comment-metadata">';
                                    $this->html .= '<p class="author-name m-0">';
                                        $this->html .= $row->full_name;
                                        $this->html .= '<span class="time">' . $row->created_date . '</span>';
                                        $this->html .= '<span class="time">' . $row->ip_address . '</span>';
                                    $this->html .= '</p>';
                                $this->html .= '</div>';
                                $this->html .= '<div class="comment-content comment-reply-wrapper">';
                                    $this->html .= '<p class="m-0">' . $row->comment . '</p>';
                                    $this->html .= '<ul class="list-inline mt-1">';
                                        $this->html .= '<li>';
                                            $this->html .= '<a class="comment_vote noajax like" href="javascript:void(0);" data-art="116879" data-com="45323">';
                                                $this->html .= '<i class="fa fa-fw fa-thumbs-o-up"></i> ';
                                                $this->html .= '<span class="like-count">0</span>';
                                            $this->html .= '</a>';
                                        $this->html .= '</li>';
                                        $this->html .= '<li>';
                                            $this->html .= '<a class="comment_vote noajax dislike" href="javascript:void(0);" data-art="116879" data-com="45323">';
                                                $this->html .= '<i class="fa fa-fw fa-thumbs-o-down"></i>';
                                                $this->html .= '<span class="dislike-count">0</span>';
                                            $this->html .= '</a>';
                                        $this->html .= '</li>';
                                        $this->html .= '<li class="reply-wrapper">';
                                            $this->html .= '<a id="idOfButton" class="comment_vote reply" href="javascript:void(0);" onclick="_replyFormComment({elem: this, parentId: ' . $row->id . '});" style="margin-left: 10px;">';
                                                $this->html .= '<i class="fa fa-reply fa-rotate-180" aria-hidden="true"></i>';
                                                $this->html .= ' Хариулах';
                                            $this->html .= '</a>';
                                        $this->html .= '</li>';
                                    $this->html .= '</ul>';
                                    $this->html .= '<span class="reply-form-html-' . $row->id . ' reply-form-close"></span>';
                                $this->html .= '</div>';
                            $this->html .= '</div>';
                        $this->html .= '</div>';
                    $this->html .= '</div>';
                $this->html .= '</li>';
                
                
                $this->html .= self::listsChild_model(array('parentId' => $row->id, 'modId' => $row->mod_id, 'contId' => $row->cont_id, 'space' => 55, 'sortType' => $param['sortType'], 'level' => ++$i, 'isDelete' => $param['isDelete']));
            }

            $this->html .= '</ol>';
            //$this->html .= '<script type="text/javascript">_initComment({modId: ' . $param['modId'] . ', contId: ' . $param['contId'] . ', sortType: ' . $param['sortType'] . '});</script>';
            
            
        }

        return $this->html;
    }

    public function listsChild_model($param = array()) {

        $html = '';

        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.mod_id,
                C.cont_id,
                C.people_id,
                C.title,
                C.comment,
                DATE_FORMAT(C.created_date,\'%Y-%m-%d %H:%i\') AS created_date,
                C.ip_address,
                C.is_active,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name,
                (case when (HP.pic is null or HP.pic = \'\') then \'default.svg\' else concat(\'s_\', HP.pic) end) as pic
            FROM `gaz_comment` AS C
            LEFT JOIN `gaz_hr_people` AS HP ON C.people_id = HP.id
            WHERE parent_id = ' . $param['parentId'] . ' AND C.is_active = 1 AND C.mod_id = ' . $param['modId'] . ' AND C.cont_id = ' . $param['contId'] . '
            ORDER BY C.created_date ' . $param['sortType']);

        if ($this->query->num_rows() > 0) {

            $html .= '<ol>';
            foreach ($this->query->result() as $key => $row) {

                $html .= '<li>';
                    $html .= '<div class="comment-body">';
                        $html .= '<div id="comment-meta">';
                            $html .= '<div class="jk vcard">';
                                $html .= '<div class="avatar-wrapper"><img src="' . UPLOADS_USER_PATH . $row->pic . '"></div>';
                                $html .= '<div class="comment-metadata">';
                                    $html .= '<p class="author-name m-0">';
                                        $html .= $row->full_name;
                                        $html .= '<span class="time">' . $row->created_date . '</span>';
                                        $html .= '<span class="time">' . $row->ip_address . '</span>';
                                    $html .= '</p>';
                                $html .= '</div>';
                                $html .= '<div class="comment-content comment-reply-wrapper">';
                                    $html .= '<p class="m-0">' . $row->comment . '</p>';
                                    $html .= '<ul class="list-inline mt-1">';
                                        $html .= '<li>';
                                            $html .= '<a class="comment_vote noajax like" href="javascript:void(0);" data-art="116879" data-com="45323">';
                                                $html .= '<i class="fa fa-fw fa-thumbs-o-up"></i> ';
                                                $html .= '<span class="like-count">0</span>';
                                            $html .= '</a>';
                                        $html .= '</li>';
                                        $html .= '<li>';
                                            $html .= '<a class="comment_vote noajax dislike" href="javascript:void(0);" data-art="116879" data-com="45323">';
                                                $html .= '<i class="fa fa-fw fa-thumbs-o-down"></i>';
                                                $html .= '<span class="dislike-count">0</span>';
                                            $html .= '</a>';
                                        $html .= '</li>';
                                        if ($param['level'] <= 3) {
                                        $html .= '<li class="reply-wrapper">';
                                            $html .= '<a id="idOfButton" class="comment_vote reply" href="javascript:void(0);" onclick="_replyFormComment({elem: this, parentId: ' . $row->id . '});" style="margin-left: 10px;">';
                                                $html .= '<i class="fa fa-reply fa-rotate-180" aria-hidden="true"></i>';
                                                $html .= ' Хариулах';
                                            $html .= '</a>';
                                        $html .= '</li>';
                                        }
//                                        if ($row->people_id == $this->session->adminPeopleId) {
//                                            $html .= '<li data-method="reply" class="_reply"><a href="javascript:;" onclick="_deleteComment({id: ' . $row->id . ', modId: ' . $param['modId'] . ', contId: ' . $param['contId'] . '});">Устгах</a></li>';
//                                        }
                                    $html .= '</ul>';
                                    $html .= '<span class="reply-form-html-' . $row->id . ' reply-form-close"></span>';
                                $html .= '</div>';
                            $html .= '</div>';
                        $html .= '</div>';
                    $html .= '</div>';
                $html .= '</li>';
                
                $html .= self::listsChild_model(array('parentId' => $row->id, 'modId' => $row->mod_id, 'contId' => $row->cont_id, 'space' => ($param['space'] + 55), 'sortType' => $param['sortType'], 'level' => ($param['level'] + 1), 'isDelete' => $param['isDelete']));
            }
            $html .= '</ol>';
        }

        return $html;
    }

    public function delete_model($param = array()) {
        if ($param['id'] != 0) {
            
            $deleteId = explode(',', $this->getChildComments_model($param['id']));
            
            $this->db->where_in('id', $deleteId);

            if ($this->db->delete($this->db->dbprefix . 'comment')) {

                $this->queryModule = $this->db->query('SELECT `table` FROM `gaz_module` WHERE `id` = ' . $param['modId']);

                if ($this->queryModule->num_rows() > 0) {

                    $this->module = $this->queryModule->row();

                    $this->queryComment = $this->db->query('SELECT count(id) AS count FROM `gaz_comment` WHERE `mod_id` = ' . $param['modId'] . ' AND cont_id = ' . $param['contId'] . ' AND is_active = 1');

                    $this->comment = $this->queryComment->row();

                    $this->db->where('id', $param['contId']);

                    $this->db->update($this->db->dbprefix . $this->module->table, array('comment_count' => $this->comment->count));
                }
            }
        }

        return array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай устгалаа');
    }
    
    public function getChildComments_model($param = array()) {

         $this->data = '';
        if ($param != '') {
            
            $param = explode(',', $param);

            if (is_array($param) and count($param) > 0) {
                foreach ($param as $commentKey => $commentId) {
                    $this->query = $this->db->query('
                    SELECT 
                        C.id
                    FROM `' . $this->db->dbprefix . 'comment` AS C 
                    WHERE C.is_active = 1 AND C.parent_id = ' . $commentId);

                    if ($this->query->num_rows() > 0) {
                        foreach ($this->query->result() as $key => $row) {
                            $this->data .= $row->id . ',';
                        }
                        $this->data .= $commentId . ',';
                    } else {
                        $this->data .= $commentId . ',';
                    }
                }
            } else {
                $this->query = $this->db->query('
                    SELECT 
                        C.id
                    FROM `' . $this->db->dbprefix . 'comment` AS C 
                    WHERE C.is_active = 1 AND C.parent_id = ' . $param);

                if ($this->query->num_rows() > 0) {
                    foreach ($this->query->result() as $key => $row) {
                        $this->data .= $row->id . ',';
                    }
                    $this->data .= $param . ',';
                } else {
                    $this->data .= $param . ',';
                }
            }
            return rtrim($this->data, ',');
        }
        
        return 0;
    }
    
    public function mInsert_model($param = array()) {

        $this->data = array(
            array(
                'id' => getUID('comment'),
                'mod_id' => $this->input->get('modId'),
                'cont_id' => $this->input->get('contId'),
                'people_id' => $this->input->get('peopleId'),
                'parent_id' => $this->input->get('parentId'),
                'comment' => ($this->input->get('parentId') != 0 ? $this->input->get('replyComment') : $this->input->get('comment')),
                'created_date' => date('Y-m-d H:i:s'),
                'ip_address' => $this->input->get('ipAddress'),
                'is_active' => 1,
                'lang_id' => 1));

        if ($this->db->insert_batch($this->db->dbprefix . 'comment', $this->data)) {

            if ($this->contentUpdate_model(array('modId' => $this->input->get('modId'), 'contId' => $this->input->get('contId')))) {
                return array('status' => 'success', 'message' => 'Сэтгэгдэл амжилттай нэмэгдлээ');
            }
            
        }
        
        return array('status' => 'error', 'message' => 'Сэтгэгдэл нэмэх үед алдаа гарлаа');
        
    }

    public function mLists_model($param = array()) {

        $data = array();

        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.mod_id,
                C.cont_id,
                C.people_id,
                C.title,
                C.comment,
                C.created_date,
                C.ip_address,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name,
                (case when (HP.pic is null or HP.pic = \'\') then \'/upload/user/default.svg\' else concat(\'/upload/user/s_\', HP.pic) end) as pic
            FROM `gaz_comment` AS C
            LEFT JOIN `gaz_hr_people` AS HP ON C.people_id = HP.id
            WHERE C.is_active = 1 AND C.mod_id = ' . $param['modId'] . ' AND C.cont_id = ' . $param['contId'] . '
            ORDER BY C.created_date ' . $param['sortType']);

        if ($this->query->num_rows() > 0) {

            $i = 1;
            foreach ($this->query->result() as $key => $row) {
                
                array_push($data, $row);
                //$this->html .= self::listsChild_model(array('parentId' => $row->id, 'modId' => $row->mod_id, 'contId' => $row->cont_id, 'space' => 55, 'sortType' => $param['sortType'], 'level' => ++$i, 'isDelete' => $param['isDelete']));
            }

        }

        return $data;
    }

    public function mListsChild_model($param = array()) {

        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.mod_id,
                C.cont_id,
                C.people_id,
                C.title,
                C.comment,
                C.created_date,
                C.ip_address,
                C.is_active,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name,
                (case when (HP.pic is null or HP.pic = \'\') then \'default.svg\' else concat(\'s_\', HP.pic) end) as pic
            FROM `gaz_comment` AS C
            LEFT JOIN `gaz_hr_people` AS HP ON C.people_id = HP.id
            WHERE parent_id = ' . $param['parentId'] . ' AND C.is_active = 1 AND C.mod_id = ' . $param['modId'] . ' AND C.cont_id = ' . $param['contId'] . '
            ORDER BY C.created_date ' . $param['sortType']);

        if ($this->query->num_rows() > 0) {
            return $this->query->result();
        }

        return false;
    }
}
