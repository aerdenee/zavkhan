<?php

class Slog_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();

        $this->db2 = $this->load->database('log_db', TRUE);
    }

    public function updateTable_modle() {

        $this->db->query('DELETE FROM `gaz_module` WHERE `id` = 89');
        if ($this->db->query('INSERT INTO `gaz_module` (`id`, `title`, `category_title`, `main_title`, `table`, `is_active`, `order_num`, `path`, `path_list`, `path_item`, `is_category`, `icon_class`, `permission`, `theme_layout_id`) VALUES
(89, \'Log system\', \'Log system category\', \'Log system\', \'nifs_log\', 1, 88, \'slog\', \'slogCatList\', \'slogShow\', 1, \'icon-stack2\', \'{\"crudOur\":[{\"mode\":\"create\",\"status\":0,\"title\":\"Create\"},{\"mode\":\"read\",\"status\":0,\"title\":\"Read\"},{\"mode\":\"update\",\"status\":0,\"title\":\"Update\"},{\"mode\":\"delete\",\"status\":0,\"title\":\"Delete\"}],\"crudYour\":[{\"mode\":\"read\",\"status\":0,\"title\":\"Read\"},{\"mode\":\"update\",\"status\":0,\"title\":\"Update\"},{\"mode\":\"delete\",\"status\":0,\"title\":\"Delete\"}],\"report\":[{\"mode\":\"read\",\"status\":0,\"title\":\"Read\"}],\"custom\":[{\"mode\":\"report\",\"status\":0,\"title\":\"Report\"},{\"mode\":\"export\",\"status\":0,\"title\":\"Export\"},{\"mode\":\"close\",\"status\":0,\"title\":\\"Close\"}]}\', 0);')) {

            return true;
        }

        return false;
    }

    public function checkLogTable_model($param = array('tableName' => '')) {

        if (!$this->db2->table_exists('gaz_' . $param['tableName'])) {

            if ($this->db2->query('CREATE TABLE `gaz_' . $param['tableName'] . '` (
                    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    `mod_id` INT(11) NOT NULL,
                    `created_user_id` INT(11) NOT NULL,
                    `people_id` INT(11) NOT NULL,
                    `full_name` VARCHAR(50) NOT NULL,
                    `department` VARCHAR(100) NOT NULL,
                    `position` VARCHAR(100) NOT NULL,
                    `created_date` DATETIME DEFAULT NULL,
                    `type` VARCHAR(30) NOT NULL,
                    `ip_address` VARCHAR(15) NOT NULL,
                    `data` LONGTEXT NOT NULL
                    )')) {

                return true;
            }

            return false;
        }

        return true;
    }

    public function log_model($param = array()) {

        if (IS_LOG) {
            $tableName = 'log_' . date('Ym');

            if ($this->checkLogTable_model(array('tableName' => $tableName))) {

                $data = array(
                    array(
                        'mod_id' => $param['modId'],
                        'created_date' => date('Y-m-d H:i:s'),
                        'created_user_id' => $this->session->userdata['adminUserId'],
                        'people_id' => $this->session->userdata['adminPeopleId'],
                        'full_name' => $this->session->userdata['adminFullName'],
                        'department' => $this->session->userdata['adminDepartmentTitle'],
                        'position' => $this->session->userdata['adminPositionTitle'],
                        'type' => $param['type'],
                        'ip_address' => $_SERVER['REMOTE_ADDR'],
                        'data' => $param['data']
                    )
                );

                if ($this->db2->insert_batch($tableName, $data)) {
                    return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
                }
            }

            return array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
    }

    public function listsCount_model($param = array()) {

        $queryString = $tableDate = '';

        $tableDate = date('Ym');

        if ($param['logYear'] > 0 and $param['logMonth'] > 0) {
            $tableDate = $param['logYear'] . $param['logMonth'];
        } else if ($param['logYear'] > 0 and $param['logMonth'] == '') {
            $tableDate = $param['logYear'] . date('m');
        }

        if ($param['logYear'] > 0 and $param['logMonth'] > 0 and $param['beginDay'] > 0 and $param['endDay'] > 0) {
            $queryString .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['logYear'] . $param['logMonth'] . $param['beginDay'])) . '\') <= DATE(L.created_date) AND DATE(\'' . date('Y-m-d', strtotime($param['logYear'] . $param['logMonth'] . $param['endDay'])) . '\') >= DATE(L.created_date)';
        } else if ($param['logYear'] > 0 and $param['logMonth'] > 0 and $param['beginDay'] > 0 and $param['endDay'] == 0) {
            $queryString .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['logYear'] . $param['logMonth'] . $param['beginDay'])) . '\') <= DATE(L.created_date)';
        } else if ($param['logYear'] > 0 and $param['logMonth'] > 0 and $param['beginDay'] == 0 and $param['endDay'] > 0) {
            $queryString .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['logYear'] . $param['logMonth'] . $param['endDay'])) . '\') >= DATE(L.created_date)';
        }

        if ($param['crudType'] != '') {
            $queryString .= ' AND L.type = \'' . $param['crudType'] . '\'';
        }

        if ($param['userId'] > 0) {
            $queryString .= ' AND L.created_user_id = ' . $param['userId'];
        }

        if ($this->session->userdata['adminAccessTypeId'] != 5) {
            $queryString .= ' AND L.created_user_id = ' . $this->session->adminUserId;
        }

        if ($param['ipAddress'] != '') {

            $queryString .= ' AND L.ip_address LIKE LOWER(\'%' . $param['ipAddress'] . '%\')';
        }

        $query = $this->db->query('
                SELECT 
                    L.id 
                FROM `nifs_log`.`gaz_log_' . $tableDate . '` AS L
                WHERE 1 = 1 ' . $queryString);

        return $query->num_rows();
    }

    public function lists_model($param = array('modId' => 0, 'catId' => 0, 'space' => '', 'path' => '')) {

        $data = array();

        $queryString = $tableDate = '';
        $tableDate = date('Ym');

        if ($param['logYear'] > 0 and $param['logMonth'] > 0) {
            $tableDate = $param['logYear'] . $param['logMonth'];
        } else if ($param['logYear'] > 0 and $param['logMonth'] == '') {
            $tableDate = $param['logYear'] . date('m');
        }

        if ($param['logYear'] > 0 and $param['logMonth'] > 0 and $param['beginDay'] > 0 and $param['endDay'] > 0) {
            $queryString .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['logYear'] . $param['logMonth'] . $param['beginDay'])) . '\') <= DATE(L.created_date) AND DATE(\'' . date('Y-m-d', strtotime($param['logYear'] . $param['logMonth'] . $param['endDay'])) . '\') >= DATE(L.created_date)';
        } else if ($param['logYear'] > 0 and $param['logMonth'] > 0 and $param['beginDay'] > 0 and $param['endDay'] == 0) {
            $queryString .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['logYear'] . $param['logMonth'] . $param['beginDay'])) . '\') <= DATE(L.created_date)';
        } else if ($param['logYear'] > 0 and $param['logMonth'] > 0 and $param['beginDay'] == 0 and $param['endDay'] > 0) {
            $queryString .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['logYear'] . $param['logMonth'] . $param['endDay'])) . '\') >= DATE(L.created_date)';
        }

        if ($param['crudType'] != '') {
            $queryString .= ' AND L.type = \'' . $param['crudType'] . '\'';
        }

        if ($param['userId'] > 0) {
            $queryString .= ' AND L.created_user_id = ' . $param['userId'];
        }

        if ($this->session->userdata['adminAccessTypeId'] != 5) {
            $queryString .= ' AND L.created_user_id = ' . $this->session->adminUserId;
        }

        if ($param['ipAddress'] != '') {

            $queryString .= ' AND L.ip_address LIKE LOWER(\'%' . $param['ipAddress'] . '%\')';
        }
        $this->query = $this->db->query('
            SELECT 
                L.id, 
                L.mod_id, 
                M.title AS module, 
                L.created_user_id,
                L.full_name,
                L.department,
                L.position,
                (case when (U.pic is null or U.pic = \'\') then \'default.svg\' else (case when (U.pic = \'default.svg\') then \'default.svg\' else concat(\'s_\', U.pic) end) end) as pic,
                L.created_date, 
                L.type, 
                L.ip_address, 
                L.data
            FROM `nifs_log`.`gaz_log_' . $tableDate . '` AS L 
            INNER JOIN `nifs`.`gaz_module` AS M ON L.mod_id = M.id
            INNER JOIN `nifs`.`gaz_user` AS U ON L.created_user_id = U.id
            WHERE 1 = 1 ' . $queryString . '
            ORDER BY L.`id` DESC
            LIMIT ' . ($param['page'] > 1 ? (($param['page'] - 1) * $param['rows']) : 0) . ', ' . $param['rows']);

        if ($this->query->num_rows() > 0) {

            $i = 1;
            foreach ($this->query->result() as $key => $row) {

                array_push($data, array(
                    'id' => $row->id,
                    'auto_number' => $i,
                    'type' => $row->type,
                    'module' => $row->module,
                    'full_name' => $row->full_name . ', ' . $row->position,
                    'department' => $row->department,
                    'created_date' => $row->created_date,
                    'data' => $row->data,
                    'ip_address' => $row->ip_address
                ));
                $i++;
            }
        }
        return array('data' => $data, 'search' => $this->searchKeywordView_model());
    }

    public function searchKeywordView_model($param = array('modId' => 0, 'path' => 'index')) {

        $this->string = '';
        $this->showResetBtn = FALSE;

        $this->string .= form_hidden('logYear', $this->input->get('logYear'));
        $this->string .= form_hidden('logMonth', $this->input->get('logMonth'));
        $this->string .= form_hidden('beginDay', $this->input->get('beginDay'));
        $this->string .= form_hidden('endDay', $this->input->get('endDay'));

        $logYear = (int) $this->input->get('logYear');
        $logMonth = (int) $this->input->get('logMonth');
        $beginDay = (int) $this->input->get('beginDay');
        $endDay = (int) $this->input->get('endDay');

        if (!empty($logYear) and ! empty($logMonth) and ! empty($beginDay) and ! empty($endDay)) {

            $this->string .= ' Огноо: ' . $this->input->get('logYear') . ' оны ' . $this->input->get('logMonth') . ' сарын ' . $this->input->get('beginDay') . ' - ' . $this->input->get('endDay') . ' өдрүүд ';
            $this->showResetBtn = TRUE;
        } else if (!empty($logYear) and ! empty($logMonth) and ! empty($beginDay) and empty($endDay)) {

            $this->string .= ' Огноо: ' . $this->input->get('logYear') . ' оны ' . $this->input->get('logMonth') . ' сарын ' . $this->input->get('beginDay') . ' өдрөөс хойш ';
            $this->showResetBtn = TRUE;
        } else if (!empty($logYear) and ! empty($logMonth) and empty($beginDay) and ! empty($endDay)) {

            $this->string .= ' Огноо: ' . $this->input->get('logYear') . ' оны ' . $this->input->get('logMonth') . ' сарын ' . $this->input->get('endDay') . ' өдрөөс өмнөх ';
            $this->showResetBtn = TRUE;
        } else if (!empty($logYear) and ! empty($logMonth) and empty($beginDay) and empty($endDay)) {

            $this->string .= ' Огноо: ' . $this->input->get('logYear') . ' оны ' . $this->input->get('logMonth') . ' сар';
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('ipAddress')) {
            $this->string .= ' <span class="label label-default label-rounded">IP хаяг: ' . $this->input->get('ipAddress') . '</span>';
            $this->string .= form_hidden('ipAddress', $this->input->get('ipAddress'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('userId')) {
            $this->userData = $this->user->getData_model(array('selectedId' => $this->input->get('userId')));
            $this->string .= ' <span class="label label-default label-rounded">' . $this->userData->full_name . '</span>';
            $this->string .= form_hidden('userId', $this->input->get('userId'));
            $this->showResetBtn = TRUE;
        }

        if ($this->input->get('crudType')) {
            $this->string .= ' <span class="label label-default label-rounded">Үйлдэл: ' . $this->input->get('crudType') . '</span>';
            $this->string .= form_hidden('crudType', $this->input->get('crudType'));
            $this->showResetBtn = TRUE;
        }

        if ($this->showResetBtn) {
            //' . Scrime::$path . $param['path'] . '/' . $param['modId'] . '

            $this->string .= ' <a href="javascript:;" onclick="_initLog({page: 0, searchQuery: {}});"><i class="icon-cancel-circle2 search-keyword-reset-btn"></i></a>';
        }

        return ($this->showResetBtn ? '<td class="_search-result-td"><div class="datagrid-btn-separator"></div></td><td class="_search-result-td"><form action="javascript:;" id="form-log"> Хайлтын үр дүн: ' . $this->string . '</form></td>' : '');
    }

    public function getData_model($param = array('selectedId' => 0)) {

        $date = date('Ym', strtotime($param['createdDate']));
        $this->query = $this->db->query('
            SELECT 
                id,
                mod_id,
                created_user_id,
                created_date,
                type,
                ip_address,
                data
            FROM `nifs_log`.`gaz_log_' . $date . '`
            WHERE id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            return $this->query->row();
        }
        return false;
    }

    public function controlLogDateYearDropdown_model($param = array('name' => '', 'selectedId' => 0)) {

        $html = $string = $class = $name = '';

        if (isset($param['name'])) {
            $name = $param['name'];
        } else {
            $name = 'year';
        }

        if (isset($param['disabled']) and $param['disabled'] == true) {
            $string .= ' disabled="true"';
        }

        if (isset($param['required']) and $param['required'] == true) {
            $string .= ' required="true"';
            $class .= ' required';
        }

        if (isset($param['readonly']) and $param['readonly'] == true) {
            $string .= ' disabled="true"';
        }

        $html .= '<select name="' . $name . '" id="' . $name . '" class="select2" ' . $string . '>';
        $html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';


        for ($year = 2018; $year <= date('Y'); $year++) {
            $html .= '<option value="' . $year . '" ' . ($param['selectedId'] == $year ? 'selected="selected"' : '') . '>' . $year . ' он</option>';
        }

        $html .= '</select>';

        return $html;
    }

    public function controlLogDateMonthDropdown_model($param = array('name' => '', 'selectedId' => 0)) {

        $html = $string = $class = $name = '';

        if (isset($param['name'])) {
            $name = $param['name'];
        } else {
            $name = 'monthId';
        }

        if (isset($param['disabled']) and $param['disabled'] == true) {
            $string .= ' disabled="true"';
        }

        if (isset($param['required']) and $param['required'] == true) {
            $string .= ' required="true"';
            $class .= ' required';
        }

        if (isset($param['readonly']) and $param['readonly'] == true) {
            $string .= ' disabled="true"';
        }

        $html .= '<select name="' . $name . '" id="' . $name . '" class="select2" ' . $string . '>';
        $html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';

        for ($month = 1; $month <= 12; $month++) {
            $html .= '<option value="' . $month . '" ' . ($param['selectedId'] == $month ? 'selected="selected"' : '') . '>' . $month . ' сар</option>';
        }

        $html .= '</select>';

        return $html;
    }

    public function controlLogDateDayDropdown_model($param = array('controlName' => '', 'selectedId' => 0)) {

        $html = $string = $class = $controlName = '';

        if (isset($param['controlName'])) {
            $controlName = $param['controlName'];
        } else {
            $controlName = 'day';
        }

        $lastDay = date('d', strtotime(DateTime::createFromFormat("Y-m-d", $param['logYear'] . "-" . $param['logMonth'] . "-01")->format("Y-m-t")));

        if (isset($param['disabled']) and $param['disabled'] == true) {
            $string .= ' disabled="true"';
        }

        if (isset($param['required']) and $param['required'] == true) {
            $string .= ' required="true"';
            $class .= ' required';
        }

        if (isset($param['readonly']) and $param['readonly'] == true) {
            $string .= ' disabled="true"';
        }

        $html .= '<select name="' . $controlName . '" id="' . $controlName . '" class="select2" ' . $string . '>';
        $html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Сонгох - </option>';

        for ($day = 1; $day <= $lastDay; $day++) {
            $html .= '<option value="' . $day . '" ' . ($param['selectedId'] == $day ? 'selected="selected"' : '') . '>' . $day . ' өдөр</option>';
        }

        $html .= '</select>';

        return $html;
    }

}
