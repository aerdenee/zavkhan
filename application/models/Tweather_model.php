<?php

class Tweather_model extends CI_Model {

    function __construct() {
        /* Call the Model constructor */
        parent::__construct();
        $this->load->database();
    }

    public function forecast_model() {

        $this->html = '';
        //212
        $this->xml = simplexml_load_file('http://tsag-agaar.gov.mn/forecast_xml/212') or die("Error: Cannot create XML object");

        $this->xml = json_decode(json_encode($this->xml));
        $this->codeToName = $this->codeToName_model();
        if ($this->xml->forecast5day->data) {
            $this->html .= '<div class="wpaddons-weather-wraper wpaddons-box-shadow">';
            $this->html .= '<div class="wpaddons-overlay-color"></div>';
            $this->html .= '<div class="wpaddons-weather-wrap">';
            foreach ($this->xml->forecast5day->data->weather as $key => $row) {

                if ($key == 0) {

                    $this->html .= '<div class="wpaddons-weather-icon-and-city">';
                    $this->html .= '<div class="wpaddons-weather-icon"><img src="assets/plugins/weather/mongolia/' . $row->phenoIdDay . '_d.png"></div>';
                    //$this->html .= '<h3 class="wpaddons-weather-name">Улаангом</h3>';
                    $this->html .= '<h3 class="wpaddons-weather-name">' . $this->xml->forecast5day->city . '</h3>';
                    $this->html .= '<div class="wpaddons-weather-desc">' . $this->codeToName[$row->phenoIdDay] . '</div>';
                    $this->html .= '</div>';
                    $this->html .= '<div class="wpaddons-weather-todays-stats">';
                    $this->html .= '<div class="wpaddons-weather-current-temp">' . $row->temperatureDay . '<sup>℃</sup></div>';
                    $this->html .= '<div class="wpaddons-weather-more-todays-stats">';
                    $this->html .= '<div class="wpaddons-weather-wind"><span class="screen-reader-text"></span>Өдрийн салхины хурд ' . $row->windDay . ' м/с</div>';

                    $this->html .= '<div class="wpaddons-weather-highlow">Шөнийн темпратур ' . $row->temperatureNight . ' ℃</div>';
                    $this->html .= '<div class="wpaddons-weather-humidty">Шөнийн салхины хурд ' . $row->windNight . ' м/с</div>';
                    
                    $this->html .= '</div>';
                    $this->html .= '</div>';
                    $this->html .= '<div class="wpaddons-weather-forecast wpaddons-weather-days-5">';
                } else {

                    $this->html .= '<div class="wpaddons-weather-forecast-day">';
                        $this->html .= '<div class="wpaddons-weather-icon"><img src="assets/plugins/weather/mongolia/' . $row->phenoIdDay . '_d.png"></div>';
                        $this->html .= '<div class="wpaddons-weather-forecast-day-temp">Өдөр: ' . $row->temperatureDay . ' ℃</div>';
                        $this->html .= '<div class="wpaddons-weather-forecast-day-temp">Шөнө: ' . $row->temperatureNight . ' ℃</div>';
                        $this->html .= '<div class="wpaddons-weather-forecast-day-abbr">' . $row->date . '</div>';
                    $this->html .= '</div>';
                }
            }
            $this->html .= '</div>';

            $this->html .= '</div>';

            $this->html .= '</div>';
        }

        return $this->html;
    }

    public function codeToName_model($param = array()) {
        return array(
            '3' => 'Үүлэрхэг',
            '5' => 'Багавтар үүлтэй',
            '7' => 'Багавтар үүлтэй',
            '9' => 'Үүлшинэ',
            '10' => 'Үүлшинэ',
            '20' => 'Үүл багаснa',
            '21' => 'Бороо шиврэнэ',
            '22' => 'Бороо шиврэнэ',
            '23' => 'Ялимгүй цас',
            '24' => 'Ялимгүй цас',
            '27' => 'Ялимгүй хур тунадас',
            '28' => 'Ялимгүй хур тунадас',
            '60' => 'Бага зэргийн бороо',
            '61' => 'Бороо',
            '63' => 'Их бороо',
            '64' => 'Бага зэргийн хур тунадас',
            '65' => 'Хур тунадас',
            '70' => 'Бага зэргийн цас',
            '71' => 'Цас',
            '73' => 'Их цас',
            '75' => 'Аадар их цас',
            '80' => 'Хүчтэй аадар бороо',
            '90' => 'Түр зуурын бороо',
            '95' => 'Аадар хур тунадас'
        );
    }

}
