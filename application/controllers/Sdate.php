<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sdate extends CI_Controller {

    public static $path = "sdate/";

    function __construct() {

        parent::__construct();
    }

    function getNumDateDiff($start_ts, $end_ts, $day, $include_start_end = false) {

        $day = strtolower($day);
        $current_ts = $start_ts;
        $days = 0;
        // loop next $day until timestamp past $end_ts
        while ($current_ts < $end_ts) {

            if (( $current_ts = strtotime('next ' . $day, $current_ts) ) < $end_ts) {
                $days++;
            }
        }

        // include start/end days
        if ($include_start_end) {
            if (strtolower(date('l', $start_ts)) == $day) {
                $days++;
            }
            if (strtolower(date('l', $end_ts)) == $day) {
                $days++;
            }
        }

        return (int) $days;
    }

    public function getNumWorkDay() {

        if ($this->input->post('inDate') != '' and $this->input->post('outDate')) {
            $inDate = strtotime($this->input->post('inDate'));
            $outDate = strtotime($this->input->post('outDate'));
            $numSunday = $this->getNumDateDiff($inDate, $outDate, 'sunday', false);
            $numSaturday = $this->getNumDateDiff($inDate, $outDate, 'saturday', false);

            $workDay = (($outDate - $inDate) / 86400) - $numSunday - $numSaturday + 1;

            echo json_encode('Шинжилгээ хийх боломжтой ажлын ' . ($workDay == 0 ? 1 : $workDay) . ' өдөр байна.');
            
        } else {
            
            echo json_encode('Шинжилгээг зөвхөн ажлын өдрүүдэд хийнэ.');
            
        }
        
        exit();
        
    }

}
