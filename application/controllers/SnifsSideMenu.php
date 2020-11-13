<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SnifsSideMenu extends CI_Controller {

    public static $path = "snifsContact/";

    function __construct() {

        parent::__construct();
        
    }

    public function contact() {

        echo json_encode(array(
            array('title'=> 'Хувийн мэдээлэл', 'value' => 'profile', 'icon' => 'glyphicon-user'),
            array('title'=> 'Хувилбар', 'value' => '1.0', 'icon' => 'glyphicon-qrcode'),
            array('title'=> 'Мэйл хаяг', 'value' => 'support@nifs.gov.mn', 'icon' => 'glyphicon-envelope'),
            array('title'=> 'Вэб сайт', 'value' => 'http://forensics.gov.mn', 'icon' => 'glyphicon-globe'),
            array('title'=> 'Утас', 'value' => '70000000', 'icon' => 'glyphicon glyphicon-earphone')
            
        ));
    }

}
