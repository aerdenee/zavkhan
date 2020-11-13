<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sauthentication extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Sauthentication_model', 'authentication');
        $this->load->model('Slanguage_model', 'language');
        $this->load->model('Suser_model', 'user');
        $this->load->model('Sreport_model', 'report');
        
    }

    public function index() {

        $this->body['jsFile'] = array('/assets/system/core/_authentication.js');

        if ($this->session->isLogin === TRUE) {
            redirect(base_url('dashboard'));
        }
        $this->body['controlSystemLangLoginDropdown'] = $this->language->controlSystemLangLoginDropdown_model(array('selectedId' => 1));
        if (IS_DEFAULT_SYSTEM_USER) {
            $this->load->view(MY_ADMIN . '/authentication/default', $this->body);
        } else {
            $this->load->view(MY_ADMIN . '/authentication/nifs', $this->body);
        }
        
    }

    public function login() {

        $this->user = $this->authentication->authentication_model();

        if ($this->user['isLogin']) {
            
            $this->session->set_userdata($this->user);
            
            echo json_encode(array('isLogin' => true));
            
            
        } else {
            echo json_encode(array('isLogin' => false));
        }
        
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect(base_url('systemowner'));
    }

    public function checkLogin() {
        if (!isset($_SESSION['isLogin']) or $_SESSION['isLogin'] === false) {
            redirect(base_url());
        }
    }

    public function passwordResetForm() {

        echo json_encode(array(
            'title' => 'Нууц үг солих',
            'btn_yes' => 'Илгээх',
            'width' => 300,
            'html' => $this->load->view(MY_ADMIN . '/authentication/passwordResetForm', '', TRUE)
        ));
    }

    public function passwordReset() {
        echo json_encode($this->authentication->passwordReset_model(array('email' => $this->input->post('email'))));
    }

    public function mLogin() {
        ///systemowner/mLogin

        echo json_encode($this->authentication->authentication_model());
    }

    public function sendMail() {
        $this->load->library('email');

        $config = array();
        
        $config['protocol']    = 'smtp';
        $config['smtp_host']    = 'ssl://email.gov.mn';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '7';
        $config['smtp_user']    = 'support@nifs.gov.mn';
        $config['smtp_pass']    = 'eNifs2@18';
        $config['charset']    = 'utf-8';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'text'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not    
        
        $this->email->initialize($config);

//        $config['protocol'] = 'smtp';
//        $config['smtp_host'] = 'email.gov.mn';
//        $config['smtp_user'] = 'support@nifs.gov.mn';
//        $config['smtp_pass'] = 'eNifs2@18';
//        $config['smtp_port'] = 25;
//        $this->email->initialize($config);
//        $this->email->set_newline("\r\n");

        $this->email->from('support@nifs.gov.mn', 'E-Nifs цахим систем');
        $this->email->to('a.erdenebaatar@gmail.com');

        $this->email->subject('E-Nifs цахим систем - нууц үг');
        $this->email->message('Шинэ нууц үг: fdsa4fa5656');

        var_dump($this->email->send());

        echo $this->email->print_debugger();
        
        $to = "a.erdenebaatar@gmail.com";
        $subject = "This is subject";

        $message = "<b>This is HTML message.</b>";
        $message .= "<h1>This is headline.</h1>";

        $header = "From:support@nifs.gov.mn \r\n";
        $retval = mail($to, $subject, $message, $header);
        if ($retval == true) {
            echo "Message sent successfully...";
        } else {
            echo "Message could not be sent...";
        }
    }

}
