<?php

class SlinkType_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function controlNifsIsMixxDropdown_model($param = array('selectedId' => 0)) {

        $this->html = $this->string = '';

        $this->data = array(
            array('id' => 1, 'title' => 'Бүрэлдэхүүнтэй'),
            array('id' => 2, 'title' => 'Бүрэлдэхүүнгүй')
        );

        $this->html .= '<select name="isMixx" id="isMixx" class="select2">';
        $this->html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '> - Бүгд - </option>';

        foreach ($this->data as $key => $row) {
            $this->html .= '<option value="' . $row['id'] . '" ' . ($param['selectedId'] == $row['id'] ? 'selected="selected"' : '') . '>' . $row['title'] . '</option>';
        }

        $this->html .= '</select>';

        return $this->html;
    }

    public function controlLinkTypeRadioBox_model($param = array()) {
        $html = $string = '';
        $data = array('name' => 'type', 'class' => 'radio', 'onclick' => '_linkType({elem:this});');

        if (isset($param['disabled']) and $param['disabled'] == 'true') {
            $data['disabled'] = true;
        }

        if (isset($param['required']) and $param['required'] == 'true') {
            $data['required'] = true;
        }

        if (isset($param['readonly']) and $param['readonly'] == 'true') {
            $data['disabled'] = true;
        }
        $query = $this->db->query('
            SELECT 
                LT.id,
                    LT.title
            FROM `gaz_link_type` AS LT
            WHERE LT.is_active = 1 ORDER BY LT.order_num ASC');

        if ($query->num_rows() > 0) {
            
            $html .= form_hidden('linkTypeId', $param['selectedId']);
            foreach ($query->result() as $key => $row) {
                $html .= '<div class="form-check form-check-inline">';
                $html .= '<label class="form-check-label">';
                $html .= form_radio($data, $row->id, ($param['selectedId'] == $row->id ? true : false));
                $html .= $row->title;
                $html .= '</label>';
                $html .= '</div>';
            }
            
        }

        return $html;
    }

}
