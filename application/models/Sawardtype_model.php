<?php
class Sawardtype_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function controlAwardTypeDropdown_model($param = array('selectedId' => 0, 'required' => true)) {
        $this->query = $this->db->query('
            SELECT 
                id,
                title
            FROM `' . $this->db->dbprefix . 'award_type`
            WHERE is_active = 1
            ORDER BY order_num ASC');
        
        $result = $this->query->result();
        $str = '<select class="form-control select2" name="awardTypeId" id="awardTypeId" ' . (isset($param['required']) ? 'required="required"' : '') . ' ' . (isset($param['isDisabled']) ? 'disabled="disabled"' : '') . '>';
        $str .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '>' . ' - Сонгох - </option>';
        if (count($result) > 0) {
            foreach ($result as $row) {
                $str .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>' . $row->title . '</option>';
            }
        }
        $str .= '</select>';
        return $str;
    }
    
    public function getData_model($param = array('selectedId' => 0)) {
        $this->query = $this->db->query('
            SELECT 
                id,
                title
            FROM `' . $this->db->dbprefix . 'award_type`
            WHERE id = ' . $param['selectedId']);
        
        if ($this->query->num_rows() > 0) {
            $result = $this->query->result();
            return $result['0'];
        }
        return false;
    }
    
}
