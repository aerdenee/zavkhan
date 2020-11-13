<?php

class Tcomment_model extends CI_Model {

    function __construct() {
        /* Call the Model constructor */
        parent::__construct();
    }

    public function lists_model($param = array('contId'=>0, 'modId'=>0, 'orderBy' => 'desc')) {
        
         $query = $this->db->query('
            SELECT 
                C.id,
                C.title,
                C.comment,
                C.created_date,
                C.ip_address
            FROM `gaz_comment` AS C
            WHERE C.is_active = 1 AND C.mod_id = ' . $param['modId'] .  ' AND  C.cont_id = ' . $param['contId'] . ' AND C.lang_id = ' . $this->session->userdata['themeLanguage']['id'] . '
            ORDER BY C.created_date DESC');
         

        $commentCount = $query->num_rows();
        
        if ($commentCount > 0) {
            $html = '<ul class="_theme-comment-list">';
            foreach ($query->result() as $row) {
                $html .= '<li>';
                $html .= '<div class="_theme-comment-list-item">';
                $html .= '<div class="_theme-comment-author-photo"><img src="/assets/system/icons/emotion/006_food.svg"></div>';
                $html .= '<div class="_theme-comment-text">';
                $html .= '<h4>';
                $html .= $row->title . ' <span>[' . $row->ip_address . ']</span>';
                $html .= '<span class="date">' . dateDiff($row->created_date) .  '</span>';
                $html .= '</h4>';
                $html .= '<p>' . $row->comment . '</p>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</li>';
            }
            $html .= '</ul>';

            return array('status' => 'success', 'html' => $html, 'commentCount' => $commentCount);
            
        }
        
        return array('status' => 'empty', 'html' => 'Та анхны сэтгэгдэлийг үлдээх боломжтой');
    }

    public function insert_model($param = array()) {

        $data = array(
            array(
                'id' => getUID('comment'),
                'mod_id' => $param['modId'],
                'cont_id' => $param['contId'],
                'parent_id' => $param['parentId'],
                'title' => $param['title'],
                'comment' => $param['comment'],
                'created_date' => date('Y-m-d H:i:s'),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'is_active' => 1,
                'lang_id' => $this->session->userdata['themeLanguage']['id']));

        if ($this->db->insert_batch($this->db->dbprefix . 'comment', $data)) {

            if ($this->contentUpdate_model(array('modId' => $param['modId'], 'contId' => $param['contId']))) {
                return array('status' => 'success', 'message' => 'Сэтгэгдэл амжилттай нэмэгдлээ');
            }
            
        }
        
        return array('status' => 'error', 'message' => 'Сэтгэгдэл нэмэх үед алдаа гарлаа');
        
    }

    public function contentUpdate_model($param = array('modId' => 0, 'contId' => 0)) {
        
        $queryModule = $this->db->query('SELECT `table` FROM `gaz_module` WHERE `id` = ' . $param['modId']);

        if ($queryModule->num_rows() > 0) {

            $module = $queryModule->row();
            $queryComment = $this->db->query('SELECT id FROM `gaz_comment` WHERE is_active = 1 AND `mod_id` = ' . $param['modId'] . ' AND cont_id = ' . $param['contId']);

            $this->db->where('id', $param['contId']);
            if ($this->db->update($this->db->dbprefix . $module->table, array('comment_count' => $queryComment->num_rows()))) {
                return true;
            }
        }
        return false;
    }
    
}

?>