<?php

class Smodule_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getData_model($param = array('id' => 0)) {

        $this->query = $this->db->query('
            SELECT 
                `id`,
                `title`,
                `table`,
                `is_active`,
                `order_num`,
                `path`,
                `path_list`,
                `path_item`,
                `is_category`,
                `icon_class`,
                `permission`
            FROM `gaz_module`
            WHERE id = ' . $param['id']);

        if ($this->query->num_rows() > 0) {

            return $this->query->row();
        }

        return false;
    }

    public function controlModuleListDropdown_model($param = array('selectedId' => 0)) {

        $string = '';

        if (isset($param['disabled']) and strtolower($param['disabled']) == 'true') {
            $string .= ' disabled="true"';
        }

        if (isset($param['required']) and strtolower($param['required']) == 'true') {
            $string .= ' required="true"';
        }

        if (isset($param['readonly']) and strtolower($param['readonly']) == 'true') {
            $string .= ' disabled="true"';
            $html .= form_hidden($param['name'], $param['selectedId']);
        }

        $query = $this->db->query('
            SELECT 
                id,
                title
            FROM `gaz_module`
            WHERE is_active = 1
            ORDER BY order_num ASC');

        $html = '<select class="form-control select2" name="modId" id="modId" ' . $string . '>';

        $html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '>' . ' - Сонгох - </option>';

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';
                
            }
        }

        $html .= '</select>';

        return $html;
    }

    public function updateThemeLayout_model() {

        $this->query = $this->db->query('
            SELECT 
                `id`,
                `title`,
                `table`
            FROM `gaz_module`
            WHERE `is_active` = 1');

        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $key => $row) {

                $this->checkThemeLayout_model(array('row' => $row));
            }
        }
    }

    public function checkThemeLayout_model($param = array()) {

        $queryCategory = $this->db->query('
            SELECT 
                `id`,
                `title`,
                `mod_id`,
                `theme`
            FROM `gaz_theme_layout`
            WHERE `is_active` = 1 AND is_category = 1 AND mod_id = ' . $param['row']->id);

        if (empty($queryCategory->num_rows())) {

            $this->db->insert_batch($this->db->dbprefix . 'theme_layout', array(
                array(
                    'id' => getUID('theme_layout'),
                    'title' => 'Жагсаалт',
                    'is_active' => 1,
                    'mod_id' => $param['row']->id,
                    'is_category' => 1,
                    'theme' => 'lists')));
        }

        $queryMain = $this->db->query('
            SELECT 
                `id`,
                `title`,
                `mod_id`,
                `theme`
            FROM `gaz_theme_layout`
            WHERE `is_active` = 1 AND is_category = 0 AND mod_id = ' . $param['row']->id);

        if (empty($queryMain->num_rows())) {

            $this->db->insert_batch($this->db->dbprefix . 'theme_layout', array(
                array(
                    'id' => getUID('theme_layout'),
                    'title' => 'Дэлгэрэнгүй',
                    'is_active' => 1,
                    'mod_id' => $param['row']->id,
                    'is_category' => 0,
                    'theme' => 'item')));
        }
    }

    public function setThemeLayout_model() {

        $this->query = $this->db->query('
            SELECT 
                L.id,
                L.mod_id,
                M.table
            FROM gaz_theme_layout AS L
            INNER JOIN gaz_module AS M ON L.mod_id = M.id
            WHERE L.is_active = 1 AND M.is_active = 1');

        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $key => $row) {

                $this->setTableThemeLayout_model(array('row' => $row));
            }
        }
    }

    public function setTableThemeLayout_model($param = array()) {

        $query = $this->db->query('
            SELECT 
                `id`,
                `mod_id`,
                `theme_layout_id`
            FROM `gaz_' . $param['row']->table . '`
            WHERE mod_id = ' . $param['row']->id);

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                if ($row->theme_layout_id == 0) {

                    $this->db->where('id', $row->id);
                    $this->db->update($this->db->dbprefix . $param['row']->table, array('theme_layout_id' => $param['row']->id));
                }
            }
        }
    }

    public function updatePermission_model() {

        $this->query = $this->db->query('
            SELECT 
                `id`,
                `title`,
                `table`,
                `permission`
            FROM `gaz_module`');

        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $key => $row) {

                $permission = json_decode($row->permission);

                $p0 = (array) $permission->custom[0];
                $p1 = (array) $permission->custom[1];
                $p2 = (array) $permission->custom[2];
                
                $permission->custom[0] = $p2;
                $permission->custom[1] = $p1;
                $permission->custom[2] = $p0;
                
                $this->db->where('id', $row->id);
                $this->db->update($this->db->dbprefix . 'module', array('permission' => json_encode($permission)));
            }
        }
    }

}
