<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Scomment extends CI_Controller {

    public static $path = "scomment/";

    function __construct() {
        parent::__construct();
        $this->load->model('Scomment_model', 'scomment');
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            

        } else {
            redirect(MY_ADMIN);
        }
    }

    public function delete() {
        echo json_encode($this->scomment->delete_model(array(
            'id' => $this->input->post('id'),
            'modId' => $this->input->post('modId'),
            'contId' => $this->input->post('contId'))));
    }

    public function lists() {
        if ($this->session->isLogin === TRUE) {
            
            $commentCount = $this->scomment->listsCount_model(array('modId' => $this->input->get('modId'), 'contId' => $this->input->get('contId')));
            
            echo json_encode(array(
                'count' => ($commentCount == 0 ? '<i class="icon-comment text-size-base text-blue-300"></i> Та анхны сэтгэгдэл үлдээх боломжтой' : '<i class="icon-comment text-size-base text-blue-300"></i> <span class="text-muted position-right"> ' . $commentCount . ' сэтгэгдэл</span>'),
                'form' => $this->load->view(MY_ADMIN . '/comment/form', array(
                    'count' => ($commentCount == 0 ? 'Та анхны сэтгэгдэл үлдээх боломжтой' : $commentCount . ' сэтгэгдэл'),
                    'sortType' => $this->input->get('sortType'),
                    'modId' => $this->input->get('modId'), 
                    'contId' => $this->input->get('contId')), TRUE),
                'lists' => $this->scomment->lists_model(array('modId' => $this->input->get('modId'), 'contId' => $this->input->get('contId'), 'sortType' => $this->input->get('sortType'), 'isDelete' => $this->input->get('isDelete')))
                ));
        }
    }
    
    public function insert() {
        echo json_encode($this->scomment->insert_model());
    }
    
    public function replyForm() {
        
        echo json_encode(array(
            'html' => $this->load->view(MY_ADMIN . '/comment/formReply', array('parentId' => $this->input->post('parentId')), TRUE)));
        
    }
    
    public function mInsert() {
        echo json_encode($this->scomment->mInsert_model());
    }
    
    public function mLists() {
        //http://nifs.local/scomment/mLists/?modId=57&contId=295&sortType=DESC
            
        echo json_encode(array(
            'total' => $this->scomment->listsCount_model(array('modId' => $this->input->get('modId'), 'contId' => $this->input->get('contId'))),
            'result' =>  $this->scomment->mLists_model(array(
                'modId' => $this->input->get('modId'), 
                'contId' => $this->input->get('contId'), 
                'sortType' => $this->input->get('sortType'), 
                'isDelete' => $this->input->get('isDelete')))));

    }
    
    public function mDelete() {
        echo json_encode($this->scomment->delete_model(array(
            'id' => $this->input->get('id'),
            'modId' => $this->input->get('modId'),
            'contId' => $this->input->get('contId'))));
    }
}
