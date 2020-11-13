<?php

class Spage_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function deny_model($param = array('moduleMenuId' => 0, 'mode' => 'lists', 'createdUserId' => 0)) {

        $this->auth = authentication(array('authentication' => $this->session->authentication, 'moduleMenuId' => $param['moduleMenuId']));
        $isDenyPage = false;
        if ($this->auth->isModule == 1) {
            
            if ($param['mode'] == 'insert') {

                if ($this->auth->our->create == 0) {
                    
                    $isDenyPage = true;
                    
                }
                
            } else if ($param['mode'] == 'update') {

                if ($this->auth->our->update == 0 and $this->auth->your->update == 0) {
                    
                    $isDenyPage = true;
                    
                } else if ($param['createdUserId'] != $this->session->adminUserId and $this->auth->our->update == 1 and $this->auth->your->update == 0) {
                    
                    $isDenyPage = true;
                    
                }
            } else if ($param['mode'] == 'read') {

                if ($this->auth->our->read == 0 and $this->auth->your->read == 0) {
                    
                    $isDenyPage = true;
                    
                }
                
            } else if ($param['mode'] == 'close') {

                if ($this->auth->custom->close == 0) {
                    
                    $isDenyPage = true;

                }
                
            }
            
        } else {
            
            $isDenyPage = true;
            
        }
        return $isDenyPage;
    } 
    
    public function overlay_model($param = array('modId' => 0)) {
        echo '<script type="text/javascript">_pageDeny({modId: ' . $param['modId'] . '});</script>';
        exit();
    }
    
    function meta_model($param = array('title' => '', 'keywords' => '', 'description' => '', 'pic' => '', 'author' => '')) {
        
        $metaData = array(
            'pageTitle' => 'E-NIFS цахим систем',
            'favicon' => 'assets/system/img/favicon.png',
            'contentTitle' => 'E-NIFS цахим систем',
            'keywords' => 'aru', 
            'description' => 'E-NIFS цахим систем',
            'contentImage' => 'assets/images/favicon.png',
            'author' => 'A.Erdenebaatar');

        if (isset($param['pageTitle']) and $param['pageTitle'] != '') {
            $metaData['pageTitle'] = $param['pageTitle'];
        }
        
        if (isset($param['contentTitle']) and $param['contentTitle'] != '') {
            $metaData['contentTitle'] = $param['contentTitle'];
        }
        
        if (isset($param['keywords']) and $param['keywords'] != '') {
            $metaData['keywords'] = $param['keywords'];
        }
        
        if (isset($param['description']) and $param['description'] != '') {
            $metaData['description'] = $param['description'];
        }
        
        if (isset($param['contentImage']) and $param['contentImage'] != '') {
            $metaData['contentImage'] = $param['contentImage'];
        }
        
        if (isset($param['author']) and $param['author'] != '') {
            $metaData['author'] = $param['author'];
        }
        
        return json_decode(json_encode($metaData));
        
    }

}
