<?php

class Ssocial_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function socialDefault_model() {
        return json_encode(array(
            'facebook' => array('label' => 'Facebook', 'url' => '', 'show' => 1, 'class' => 'facebook'),
            'twitter' => array('label' => 'Twitter', 'url' => '', 'show' => 1, 'class' => 'twitter'),
            'youtube' => array('label' => 'Youtube', 'url' => '', 'show' => 1, 'class' => 'youtube'),
            'instagram' => array('label' => 'Instagram', 'url' => '', 'show' => 1, 'class' => 'instagram'),
            'soundCloud' => array('label' => 'Sound Cloud', 'url' => '', 'show' => 1, 'class' => 'cloud')));
    }
    
    public function postToJson_model() {
        
        $socialQuery = array();
        foreach ($this->input->post('socialLabel') as $key => $value) {
            $socialQuery[$key] = array(
                'class' => $this->input->post('socialClass[' . $key . ']'),
                'label' => $this->input->post('socialLabel[' . $key . ']'),
                'url' => $this->input->post('socialUrl[' . $key . ']'),
                'show' => $this->input->post('socialShow[' . $key . ']'));
        }
        return json_encode($socialQuery);
    }

}
