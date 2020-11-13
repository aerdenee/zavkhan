<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tcontact extends CI_Controller {

    public static $path = "tcontact/";

    function __construct() {
        parent::__construct();
        $this->load->model('Tcontact_model', 'tcontact');
    }
    
    public function sendMail() {

//        $this->load->library('email');
//        $email['name'] = $this->input->post('name');
//        $email['email'] = $this->input->post('email');
//        $email['message'] = $this->input->post('message');
//        $email['phone'] = $this->input->post('phone');
//        $result = $this->tcontact->getItem_model(array('selectedId'=>6));
//        
//        $message = 'Нэр : ' . $email['name'] . '
//                    Утас : ' . $email['phone'] . '
//                    E-mail : ' . $email['email'] . '
//                    Илгээсэн : ' . date('Y-m-d H:i:s') . '
//                    IP Address : ' . $_SERVER['REMOTE_ADDR'] . '
//                    Захиа : ' . $email['message'];
//        $this->email->from($email['email'], $email['phone']);
//        $this->email->to($result->email_to);
//        $this->email->subject('Вэб сайт холбоо барих хэсгээс ирсэн - ' . $email['phone']);
//        $this->email->message($message);
//        $this->email->set_newline("\r\n");
        //var_dump($this->email->send());
        //echo $this->email->print_debugger();
//        if ($this->email->send()) {
//            echo json_encode(array('status'=>'success', 'class'=>'note note-success', 'message'=>'Таны мэйлийг хүлээж авлаа.'));
//        } else {
//            echo json_encode(array('status'=>'error', 'class'=>'note note-danger', 'message'=>'Мэйл хүлээж авах үед алдаа гарлаа. Та дахин илгээнэ үү'));
//        }
        echo json_encode(array('status'=>'success', 'class'=>'note note-success', 'message'=>'せ内容を送信しました。24時間内に担当よりご連絡を差し上げます。.'));
    }
    
    public function requestForm() {
        echo json_encode(
                array(
                    "title" => "Та яг одоо хүсэлтээ илгээнэ үү!",
                    "html" => $this->load->view(DEFAULT_THEME . '/contact/contact', '', true),
                    "btn_ok" => "Хүсэлт илгээх"));
    }
    
}