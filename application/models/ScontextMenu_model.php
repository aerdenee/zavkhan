<?php

class ScontextMenu_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();

        $this->load->model('Simage_model', 'simage');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('Slog_model', 'slog');


        $this->isActiveDepartment = 'is_active_control';
    }

    public function menuData_model($param = array()) {

        $html = '<div class="context-data-menu">';
        $html .= '<div class="dropdown-menu">';
        if ($param['auth']->our->create == 1) {
            $html .= '<a href="#" class="dropdown-item"><i class="icon-pencil7"></i>Edit entry</a>';
        } else {
            
        }

        
        $html .= '<a href="#" class="dropdown-item"><i class="icon-bin"></i>Remove entry</a>';
        $html .= '<div class="dropdown-header">Export</div>';
        $html .= '<a href="#" class="dropdown-item disabled"><i class="icon-file-pdf"></i> Export to .pdf</a>';
        $html .= '<a href="#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>';
        $html .= '<a href="#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

}
