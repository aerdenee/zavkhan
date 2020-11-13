<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class snifsPreCrime extends CI_Controller {

    public static $path = "snifsPreCrime/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('SnifsPreCrime_model', 'preCrime');


        $this->perPage = 2;
        $this->header = $this->body = $this->footer = array();
    }

    public function lists() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            //get posts data
            echo json_encode($this->preCrime->lists_model(array(
                        'modId' => $this->input->get('modId'),
                        'contId' => $this->input->get('contId'))));
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->preCrime->addFormData_model();
            
            echo json_encode(array(
                'title' => 'Өмнөх хэргийн тухай - нэмэх',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/nifsPreCrime/form', $this->body, TRUE)
            ));
            
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            echo json_encode(array(
                'title' => 'Өмнөх хэргийн тухай - засах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 700,
                'html' => $this->load->view(MY_ADMIN . '/nifsPreCrime/formEdit', array(
                    'row' => array(
                        'key' => $this->input->post('preCrimeKey'), 
                        'create_number' => $this->input->post('preCrimeCreateNumber'), 
                        'expert' => $this->input->post('preCrimeExpert'), 
                        'crime_value' => $this->input->post('preCrimeCrimeValue'))), TRUE)
            ));
            
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->nifsMotive->insert_model(array('getUID' => getUID('nifs_pre_crime'))));
    }

    public function update() {
        echo json_encode($this->nifsMotive->update_model());
    }

    public function delete() {
        echo json_encode($this->nifsMotive->delete_model());
    }

}
