<?php
class Sbreadcrumb_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function breadcrumb_model($param = array('title' => '', 'mode' => '')) {
        
        $this->html = '';
        
        $this->html .= '<ul class="breadcrumb">';
            $this->html .= '<li><a href="' . MY_ADMIN . '"><i class="icon-home2 position-left"></i> Home</a></li>';
            if (isset($param['title']) and $param['title'] != '') {
                $this->html .= '<li>' . $param['title'] . '</li>';
            }
            
            if (isset($param['mode']) and $param['mode'] != '') {
                if ($param['mode'] == 'insert') {
                    $this->html .= '<li class="active">Нэмэх</li>';
                } else if ($param['mode'] == 'update') {
                    $this->html .= '<li class="active">Засах</li>';
                } else {
                    $this->html .= '<li class="active">Жагсаалт</li>';
                }
            }
                                
        $this->html .= '</ul>';

        return $this->html;
        
    }
    
}
