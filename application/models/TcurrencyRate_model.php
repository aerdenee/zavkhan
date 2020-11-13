<?php

class TcurrencyRate_model extends CI_Model {

    function __construct() {
        /* Call the Model constructor */
        parent::__construct();
    }

    public function mongolBank_model() {

        $html = '';
        //212
        $mongolBankUrl = 'https://www.mongolbank.mn/dblistofficialdailyrate.aspx';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $mongolBankUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
        $data = curl_exec($ch);

        echo '<pre>';
        echo $data;
        echo '</pre>';
        return $html;
    }

}
