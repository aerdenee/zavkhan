<?php
   
class Sreaction extends CI_Controller {
    
	  /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
       parent::__construct();
       
       $this->load->model('Sreaction_model', 'sreaction');
    }
       
    public function lists() {
         echo json_encode($this->sreaction->lists_model(array(
             'modId' => $this->input->post('modId'),
             'contId' => $this->input->post('contId')
         )));
    }
    	
}