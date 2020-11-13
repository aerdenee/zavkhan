<?php

class ScontentComment_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('Slog_model', 'slog');
    }

    public function lists_model($param = array()) {
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
                IF(C.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name,
                (case when (HP.pic is null OR HP.pic = \'\' OR HP.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', HP.pic) end) as pic
            FROM `gaz_comment` AS C
            LEFT JOIN `gaz_hr_people` AS HP ON C.people_id = HP.id
            WHERE parent_id = 0 AND C.is_active = 1 AND C.mod_id = ' . $param['modId'] . ' AND C.cont_id = ' . $param['contId'] . '
            ORDER BY C.created_date DESC');

        if ($this->query->num_rows() > 0) {

            $html .= '<div class="table-responsive">';
            $html .= '<table class="table _dg">';
            $html .= '<thead>';
            $html .= '<tr class="_gridTitle">';
            $html .= '<th style="width:30px;">#</th>';
            $html .= '<th>Сэтгэгдэл</th>';
            $html .= '<th style="width:150px;">Автор</th>';
            $html .= '<th style="width:60px;" class="text-center">Төлөв</th>';
            $html .= '<th style="width:60px;" class="text-center"><i class="icon-cog7"></i></th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody data-link="row" class="rowlink">';

            $i = 1;

            foreach ($this->query->result() as $key => $row) {

                $html .= '<tr data-id="' . $row->id . '" data-uid="' . $row->people_id . '">';
                $html .= '<td>' . $i . '</td>';
                $html .= '<td class="context-menu-content-comment-selected-row">' . $row->created_date . '  - <strong>' . $row->ip_address . '</strong><br> ' . $row->comment . '</td>';
                $html .= '<td class="context-menu-content-comment-selected-row"><img src="' . UPLOADS_USER_PATH . $row->pic . '" style="max-width:30px; max-height:30px; border-radius:100%; border: 1px solid rgba(0,0,0,0.2); background-color: rgba(0,0,0,0.1);"> ' . $row->full_name . '</td>';
                $html .= '<td class="context-menu-content-comment-selected-row text-center">' . $row->is_active . '</td>';
                $html .= '<td class="text-center">';
                $html .= '<div class="list-icons">';

                $html .= '<div onclick="_deleteContentComment({elem: this, id: ' . $row->id . '});" class="list-icons-item"><i class="icon-trash"></i></div>';

                $html .= '</td>';
                $html .= '</tr>';

                $html .= $this->listsChild_model(array('parentId' => $row->id, 'modId' => $row->mod_id, 'contId' => $row->cont_id, 'space' => 30, 'autoNumber' => $i));
                $i++;
            }

            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '</div>';
        } 

        return $html;
    }

    public function listsChild_model($param = array()) {

        $childHtml = '';

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
                IF(C.is_active > 0, "<span class=\"badge badge-success\"><span class=\"fa fa-check\"></span></span>", "<span class=\"badge badge-danger\"><span class=\"fa fa-lock\"></span></span>") AS is_active,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name,
                (case when (HP.pic is null OR HP.pic = \'\' OR HP.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', HP.pic) end) as pic
            FROM `gaz_comment` AS C
            LEFT JOIN `gaz_hr_people` AS HP ON C.people_id = HP.id
            WHERE parent_id = ' . $param['parentId'] . ' AND C.is_active = 1 AND C.mod_id = ' . $param['modId'] . ' AND C.cont_id = ' . $param['contId'] . '
            ORDER BY C.created_date DESC');

        if ($this->query->num_rows() > 0) {

            $j = 1;
            foreach ($this->query->result() as $key => $row) {

                $childHtml .= '<tr data-id="' . $row->id . '" data-uid="' . $row->people_id . '">';
                $childHtml .= '<td>' . $param['autoNumber'] . '.' . $j . '</td>';
                $childHtml .= '<td class="context-menu-content-comment-selected-row" style="padding-left:' . $param['space'] . 'px;">' . $row->created_date . '  - <strong>' . $row->ip_address . '</strong><br> ' . $row->comment . '</td>';
                $childHtml .= '<td class="context-menu-content-comment-selected-row"><img src="' . UPLOADS_USER_PATH . $row->pic . '" style="max-width:30px; max-height:30px; border-radius:100%; border: 1px solid rgba(0,0,0,0.2); background-color: rgba(0,0,0,0.1);"> ' . $row->full_name . '</td>';
                $childHtml .= '<td class="context-menu-content-comment-selected-row text-center">' . $row->is_active . '</td>';
                $childHtml .= '<td class="text-center">';
                $childHtml .= '<div class="list-icons">';

                $childHtml .= '<div onclick="_deleteContentComment({elem: this, id: ' . $row->id . '});" class="list-icons-item"><i class="icon-trash"></i></div>';

                $childHtml .= '</td>';
                $childHtml .= '</tr>';
                
                $childHtml .= self::listsChild_model(array('parentId' => $row->id, 'modId' => $row->mod_id, 'contId' => $row->cont_id, 'space' => ($param['space'] + 20), 'autoNumber' => $param['autoNumber'] . '.' . $j));
                $j++;
            }
        }

        return $childHtml;
    }

    public function delete_model() {
        if ($this->input->post('id') != NULL) {

            $queryComment = $this->db->query('SELECT `id`, `mod_id`, `cont_id` FROM `gaz_comment` WHERE `id` IN (' . $this->getChildComents_model($this->input->post('id')) . ')');

            if ($queryComment->num_rows() > 0) {
                
                foreach ($queryComment->result() as $key => $rowComment) {
                    
                    $this->db->where('id', $rowComment->id);

                    if ($this->db->delete('gaz_comment')) {

                        $this->queryModule = $this->db->query('SELECT `table` FROM `gaz_module` WHERE `id` = ' . $rowComment->mod_id);

                        if ($this->queryModule->num_rows() > 0) {

                            $this->module = $this->queryModule->row();

                            $this->queryComment = $this->db->query('SELECT count(id) AS count FROM `gaz_comment` WHERE `mod_id` = ' . $rowComment->mod_id . ' AND cont_id = ' . $rowComment->cont_id . ' AND is_active = 1');

                            $this->comment = $this->queryComment->row();

                            $this->db->where('id', $rowComment->cont_id);
                            
                            $this->db->update($this->db->dbprefix . $this->module->table, array('comment_count' => $this->comment->count));
                        }
                    }
                }
            }
        }

        return array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай устгалаа');
    }

    public function getChildComents_model($param = array()) {
        
        $data = '';
        if (is_array($param) and count($param) > 0) {
            foreach ($param as $commentId) {
                $this->query = $this->db->query('
                    SELECT 
                        C.id
                    FROM `' . $this->db->dbprefix . 'comment` AS C 
                    WHERE is_active = 1 AND parent_id = ' . $commentId);

                if ($this->query->num_rows() > 0) {
                    foreach ($this->query->result() as $key => $row) {
                        $this->data .= $row->id . ',';
                    }
                    $this->data .= $commentId . ',';
                } else {
                    $this->data .= $commentId . ',';
                }
                $this->data .= $this->getChildComents_model($commentId);
            }
        } else {
            $this->query = $this->db->query('
                    SELECT 
                        C.id
                    FROM `' . $this->db->dbprefix . 'comment` AS C 
                    WHERE is_active = 1 AND parent_id = ' . $param);

            if ($this->query->num_rows() > 0) {
                foreach ($this->query->result() as $key => $row) {
                    $data .= $row->id . ',';
                    $data .= $this->getChildComents_model($row->id);
                }
                $data .= $param . ',';
                
            } else {
                $data .= $param . ',';
            }
        }

        return rtrim($data, ',');
    }

}
