<?php

class Sorganization_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function organizationDefaultValue_model() {
        
        $this->result = self::getOrganizationAllData();
        $data = array();
        if ($this->result) {
            foreach ($this->result as $k=>$row) {
                $data['organization']['org' . $row->id] = 0;
            }
        }
        return json_encode($data);
    }
    
    public function getOrganizationAllData() {
        $this->query = $this->db->query('
            SELECT 
                id,
                title_mn
            FROM `gaz_content`
            WHERE mod_id = 9 AND is_active_mn = 1
            ORDER BY order_num ASC');
                
        $result = $this->query->result();
        if (count($result) > 0) {
            return $result;
        }
        return false;
    }


    public function controlOrganizationCheckBoxList_model($param = array()) {
        $this->result = self::getOrganizationAllData();
        $this->orgValue = json_decode($param['org']);
        $html = '';
        if (DEFAULT_MULTI_ORGANIZATION) {
            $html .= '<div class="form-group">';
            foreach ($this->result as $k=>$row) {
                $orgKey = 'org' . $row->id;
                $html .= '<label><input type="hidden" name="organizationId[]" value="' . $row->id  . '"><input type="hidden" name="organizationCheckValue[]" value="' . $this->orgValue->organization->$orgKey  . '"> <input type="checkbox" name="orgId[]" value="' . $this->orgValue->organization->$orgKey . '" class="checkbox" ' . (intval($this->orgValue->organization->$orgKey) == 1 ? 'checked="checked"' : '') . ' onclick="_setOrganizationId(this);"> ' . $row->title_mn . '</label> ';
            }
            $html .= '</div>';
            $html .= '<script type="text/javascript">
                function _setOrganizationId(elem) {
                    var _this = $(elem);
                    var _organizationCheckValue = _this.parents(\'label\').find(\'input[name="organizationCheckValue[]"]\');
                    if (_this.prop(\'checked\')) {
                        _organizationCheckValue.val(1);
                    } else {
                        _organizationCheckValue.val(0);
                    }
                }
                </script>';
        }
        return $html;
    }
    
}

?>