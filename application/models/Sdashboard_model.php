<?php

class Sdashboard_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');

        $this->learningModId = 57;
    }

    public function getCrimeData_model($param = array()) {

        $query = $this->db->query('
            SELECT 
                NC.id
            FROM `gaz_nifs_crime` AS NC 
            WHERE DATE(NC.in_date) >= DATE(\'' . $this->getYear_model() . '\')');

        return $query->num_rows();
    }

    public function getExtraCount_model($param = array()) {

        $query = $this->db->query('
            SELECT 
                NC.id
            FROM `gaz_nifs_extra` AS NC 
            WHERE DATE(NC.in_date) >= DATE(\'' . $this->getYear_model() . '\')');

        return $query->num_rows();
    }

    public function getEconomyCount_model($param = array()) {

        $query = $this->db->query('
            SELECT 
                NC.id
            FROM `gaz_nifs_economy` AS NC 
            WHERE DATE(NC.in_date) >= DATE(\'' . $this->getYear_model() . '\')');

        return $query->num_rows();
    }

    public function getSendDocumentCount_model($param = array()) {

        $query = $this->db->query('
            SELECT 
                NC.id
            FROM `gaz_nifs_send_doc` AS NC 
            WHERE DATE(NC.in_date) >= DATE(\'' . $this->getYear_model() . '\')');

        return $query->num_rows();
    }

    public function getFileFolderCount_model($param = array()) {

        $query = $this->db->query('
            SELECT 
                NC.id
            FROM `gaz_nifs_file_folder` AS NC 
            WHERE DATE(NC.in_date) >= DATE(\'' . $this->getYear_model() . '\')');

        return $query->num_rows();
    }

    public function getAnatomyCount_model($param = array()) {

        $query = $this->db->query('
            SELECT 
                NC.id
            FROM `gaz_nifs_anatomy` AS NC 
            WHERE DATE(NC.in_date) >= DATE(\'' . $this->getYear_model() . '\')');

        return $query->num_rows();
    }

    public function getDoctorViewCount_model($param = array()) {

        $query = $this->db->query('
            SELECT 
                NC.id
            FROM `gaz_nifs_doctor_view` AS NC 
            WHERE DATE(NC.in_date) >= DATE(\'' . $this->getYear_model() . '\')');

        return $query->num_rows();
    }

    public function getCrimeCount_model($param = array()) {

        $query = $this->db->query('
            SELECT 
                NC.id
            FROM `gaz_nifs_crime` AS NC 
            WHERE DATE(NC.in_date) >= DATE(\'' . $this->getYear_model() . '\')');

        return $query->num_rows();
    }

    public function getYear_model() {

        $query = $this->db->query('
            SELECT 
                DATE(NC.close_date) AS close_date
            FROM `gaz_nifs_close_year` AS NC
            WHERE NC.close_year = ' . date('Y', strtotime($this->session->userdata['adminCloseYear'])));

        $row = $query->row();

        return $row->close_date;
    }

    public function learningLists_model($param = array('modId' => 0, 'catId' => 0)) {

        $html = '';
        $sortType = array('DESC', 'ASC');
        $sortField = array('title', 'order_num', 'id', 'order_num', 'click', 'created_date');

        $this->query = $this->db->query('
            SELECT 
                C.id,
                C.cat_id,
                CAT.title AS cat_title,
                C.title,
                C.intro_text,
                (case when (C.pic is null or C.pic = \'\') then \'default.svg\' else concat(\'s_\', C.pic) end) as pic,
                C.click,
                COMM.comment_count,
                C.is_active_date,
                C.order_num,
                C.created_date,
                C.modified_date,
                C.created_user_id,
                C.modified_user_id,
                C.is_active,
                C.mod_id,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS author
            FROM `gaz_content` AS C
            INNER JOIN `gaz_category` AS CAT ON C.cat_id = CAT.id
            INNER JOIN `gaz_hr_people` AS HP ON C.people_id = HP.id
            LEFT JOIN ( 
                SELECT 
                    COM.mod_id, COM.cont_id, COUNT(COM.id) AS comment_count 
                FROM `gaz_comment` AS COM 
                GROUP BY COM.cont_id, COM.mod_id 
            ) AS COMM ON C.mod_id = COMM.mod_id AND C.id = COMM.cont_id
            WHERE 1 = 1 AND C.mod_id = ' . $this->learningModId . ' 
            ORDER BY C.' . $sortField[rand(0, 5)] . ' ' . $sortType[rand(0, 1)] . ' 
            LIMIT 0, 3');

        if ($this->query->num_rows() > 0) {

            foreach ($this->query->result() as $key => $row) {

                $html .= '<div class="col-md-4">';
                $html .= '<div class="card animated bounceInRight">';
                $html .= '<div class="card-body _gridNewsBody">';
                $html .= '<div class="card-img-actions mb-3">';
                $html .= '<img class="card-img img-fluid" src="' . UPLOADS_CONTENT_PATH . $row->pic . '" alt="">';
                $html .= '<div class="card-img-actions-overlay card-img">';
                $html .= '<a href="/scontent/show/87/' . $row->id . '" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round">';
                $html .= '<i class="icon-link"></i>';
                $html .= '</a>';
                $html .= '</div>';
                $html .= '</div>';

                $html .= '<h5 class="font-weight-semibold mb-1"><a href="/scontent/show/87/' . $row->id . '" class="text-default">' . $row->title . '</a></h5>';

                $html .= '<ul class="list-inline list-inline-dotted text-muted mb-3">';
                $html .= '<li class="list-inline-item"> ' . $row->author . '</a>, (' . $row->cat_title . ')</li>';
                $html .= '</ul>';
                $html .= word_limiter($row->intro_text, 20);
                $html .= '</div>';

                $html .= '<div class="card-footer d-flex">';
                $html .= '<span class="text-default mr-2"><i class="icon-comments"></i> ' . ($row->comment_count != 0 ? $row->comment_count : 'Сэтгэгдэл') . '</span> ';
                $html .= '<span class="text-default mr-2"><i class="icon-eye"></i> ' . ($row->click != 0 ? $row->click : 'Шинэ') . '</span>';
                $html .= '<span class="ml-auto text-left"><i class="icon-calendar"></i> ' . date('Y-m-d', strtotime($row->is_active_date)) . ' </span>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';




//                $html .= '<div class="panel-footer panel-footer-transparent">';
//                $html .= '<div class="heading-elements">';
//                $html .= '<ul class="_icon-accessories">';
//                $html .= '<li><i class="icon-eye8"></i> ' . ($row->click != 0 ? $row->click : 'Шинэ') . '</li>';
//                $html .= '<li><i class="icon-calendar52"></i> ' . date('Y-m-d', strtotime($row->is_active_date)) . '</li>';
//                $html .= '</ul>';
//
//
//                $html .= '<ul class="_icon-accessories pull-right">';
//                $html .= '<li><i class="icon-comment-discussion"></i> ' . ($row->comment_count != 0 ? $row->comment_count : 'Байхгүй') . '</li>';
//                $html .= '</ul>';
//                $html .= '<div class="clearfix"></div>';
//                $html .= '</div>';
//                $html .= '</div>';
//                $html .= '</div>';
//                $html .= '</div>';
            }
        }

        return $html;
    }

    public function generalGraphicCrimeData_model() {

        $chartData = array();
        $query = $this->db->query('
            SELECT
                C.id,
                C.title,
                NCA.cat_count_year
           FROM `gaz_category` AS C
            LEFT JOIN (
                SELECT
                    NC.cat_id,
                    COUNT(NC.cat_id) AS cat_count_year
                FROM `gaz_nifs_crime` AS NC 
                WHERE NC.year = \'' . $this->session->adminCloseYear . '\'
                GROUP BY NC.cat_id
            ) AS NCA ON NCA.cat_id = C.id
            WHERE C.mod_id = 33
            ORDER BY NCA.cat_count_year DESC');

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                array_push($chartData, array('value' => ($row->cat_count_year > 0 ? $row->cat_count_year : 0), 'name' => $row->title));
            }

            return $chartData;
        }
    }

    public function generalGraphicExtraData_model() {

        $chartData = array();
        $query = $this->db->query('
            SELECT 
                NCT.id,
                NCT.title,
                TYPE_COUNT.type_count
            FROM `gaz_nifs_crime_type` AS NCT
            LEFT JOIN (
                SELECT 
                    NC.type_id,
                    COUNT(NC.type_id) AS type_count,
                    SUM(NC.object_count) AS object_count,
                    SUM(NC.weight) AS weight
                FROM `gaz_nifs_extra` AS NC
                WHERE 1 = 1 AND NC.year = \'' . $this->session->adminCloseYear . '\'
                GROUP BY NC.type_id
            ) AS TYPE_COUNT ON TYPE_COUNT.type_id = NCT.id
            WHERE NCT.is_active = 1 AND NCT.cat_id = 354');

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                array_push($chartData, array('value' => ($row->type_count > 0 ? $row->type_count : 0), 'name' => $row->title));
            }

            return $chartData;
        }
    }

    public function generalGraphicDoctorViewData_model() {

        $chartData = array();
        $query = $this->db->query('
            SELECT
                NCSV.id,
                NCSV.title,
                SV1.short_value_count
            FROM `gaz_nifs_crime_short_value` AS NCSV
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS short_value_count
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE 1 = 1 AND NDV.year = \'' . $this->session->adminCloseYear . '\'
                GROUP BY NDV.short_value_id
            ) AS SV1 ON SV1.short_value_id = NCSV.id
            WHERE NCSV.is_active = 1
            ORDER BY NCSV.order_num ASC
            ');

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                array_push($chartData, array('value' => ($row->short_value_count > 0 ? $row->short_value_count : 0), 'name' => $row->title));
            }

            return $chartData;
        }
    }

    public function chartGeneralData_model($param = array()) {
        $html = '';


        $query = $this->db->query('
                SELECT 
                    NCG.parent_id,
                    NCG.created_date
                FROM `gaz_nifs_chart_general` AS NCG
                WHERE NCG.parent_id = ' . $param['parentId'] . '
                GROUP BY NCG.parent_id, NCG.created_date');
        $generalDate = $query->row();
        $hrPeopleDepartment = $this->hrPeopleDepartment->getData_model(array('selectedId' => $param['parentId']));
        $chartData = array();
        $html .= '<div class="card">';
        $html .= '<div class="card-header header-elements-inline">';
        $html .= '<h5 class="card-title">' . ($hrPeopleDepartment ? $hrPeopleDepartment->title : '') . '</h5>';
        $html .= '<div class="header-elements">';
        $html .= '<span class="badge bg-success badge-pill">' . date('Y оны m сарын d өдөр H:i', strtotime($generalDate->created_date)) . '-д шинэчилсэн</span>';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="card-body">';
        $html .= '<div class="chart-container">';
        $html .= '<div class="chart has-fixed-height" id="' . $param['chartId'] . '"></div>';

        $chartData['color'] = array('#2ec7c9', '#b6a2de', '#5ab1ef', '#ffb980', '#d87a80', '#ab4242', '#1a0ab1');
        $chartData['textStyle'] = array('fontFamily' => 'Roboto, Arial, Verdana, sans-serif', 'fontSize' => 13);
        $chartData['animationDuration'] = 750;
        $chartData['grid'] = array('left' => 0, 'right' => 40, 'top' => 35, 'bottom' => 0, 'containLabel' => true);
        $chartData['legend'] = array(
            'data' => array('Кримналистик', 'Шүүх эмнэлэг', 'Тусгай шинжилгээ', 'Эдийн засаг', 'Илгээх бичиг'),
            'itemHeight' => 8,
            'itemGap' => 20);
        $chartData['tooltip'] = array('trigger' => 'axis', 'backgroundColor' => 'rgba(0,0,0,0.75)', 'padding' => array(10, 15), 'textStyle' => array('fontSize' => 13, 'fontFamily' => 'Roboto, sans-serif'));
        $chartData['xAxis'] = array(
            'type' => 'category',
            'boundaryGap' => false,
            //'data' => array('Баянгол', 'Баянзүрх', 'Сүхбаатар', 'Сонгинохайрхан', 'Хан-Уул', 'Чингэлтэй', 'Налайх', 'Багануур'),
            'axisLable' => array('color' => '#333'),
            'axisLine' => array('lineStyle' => array('color' => '#999')),
            'splitLine' => array(
                'show' => true,
                'lineStyle' => array(
                    'color' => '#eee',
                    'type' => 'dashed')));
        $chartData['yAxis'] = array(
            'type' => 'value',
            'axisLabel' => array(
                'color' => '#333'),
            'axisLine' => array('lineStyle' => array('color' => '#999')),
            'splitLine' => array('lineStyle' => array('color' => '#ee')),
            'splitArea' => array('show' => true, 'areaStyle' => array('color' => array('rgba(250,250,250,0.1)', 'rgba(0,0,0,0.01)'))));

        $chartData['series'] = array(
            array(
                'name' => 'Кримналистик',
                'type' => 'line',
                'stack' => 'Total',
                'areaStyle' => array('normal' => array('opacity' => 0.25)),
                'smooth' => true,
                'symbolSize' => 7,
                'itemStyle' => array('normal' => array('borderWidth' => 2))),
            array(
                'name' => 'Шүүх эмнэлэг',
                'type' => 'line',
                'stack' => 'Total',
                'areaStyle' => array('normal' => array('opacity' => 0.25)),
                'smooth' => true,
                'symbolSize' => 7,
                'itemStyle' => array('normal' => array('borderWidth' => 2))),
            array(
                'name' => 'Тусгай шинжилгээ',
                'type' => 'line',
                'stack' => 'Total',
                'areaStyle' => array('normal' => array('opacity' => 0.25)),
                'smooth' => true,
                'symbolSize' => 7,
                'itemStyle' => array('normal' => array('borderWidth' => 2))),
            array(
                'name' => 'Эдийн засаг',
                'type' => 'line',
                'stack' => 'Total',
                'areaStyle' => array('normal' => array('opacity' => 0.25)),
                'smooth' => true,
                'symbolSize' => 7,
                'itemStyle' => array('normal' => array('borderWidth' => 2))),
            array(
                'name' => 'Илгээх бичиг',
                'type' => 'line',
                'stack' => 'Total',
                'areaStyle' => array('normal' => array('opacity' => 0.25)),
                'smooth' => true,
                'symbolSize' => 7,
                'itemStyle' => array('normal' => array('borderWidth' => 2)))
        );
        $queryData = $this->db->query('
                SELECT 
                    HPD.short_title,
                    NCG.crime,
                    NCG.forensic,
                    NCG.extra,
                    NCG.economy,
                    NCG.send_document
                FROM `gaz_nifs_chart_general` AS NCG
                INNER JOIN `gaz_hr_people_department` AS HPD ON NCG.department_id = HPD.id
                WHERE NCG.parent_id = ' . $param['parentId']);
        $crimData = $forensicData = $extraData = $economyData = $sendDocument = array();
        foreach ($queryData->result() as $keyData => $rowData) {
            $chartData['xAxis']['data'][$keyData] = $rowData->short_title;
            $crimData[$keyData] = $rowData->crime;
            $forensicData[$keyData] = $rowData->forensic;
            $extraData[$keyData] = $rowData->extra;
            $economyData[$keyData] = $rowData->economy;
            $sendDocument[$keyData] = $rowData->send_document;
        }

        $chartData['series'][0]['data'] = $crimData;
        $chartData['series'][1]['data'] = $forensicData;
        $chartData['series'][2]['data'] = $extraData;
        $chartData['series'][3]['data'] = $economyData;
        $chartData['series'][4]['data'] = $sendDocument;


        $html .= '<script type="text/javascript">
                    var area_stacked_element = document.getElementById(\'' . $param['chartId'] . '\');
                    if (area_stacked_element) {
                        var area_stacked = echarts.init(area_stacked_element);
                        area_stacked.setOption(' . json_encode($chartData) . ');
                    }
                </script>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    public function chartCenterGeneralData_model($param = array()) {
        $html = '';
        $chartData = array();

        $query = $this->db->query('
                SELECT 
                    NCG.parent_id,
                    NCG.created_date
                FROM `gaz_nifs_chart_general` AS NCG
                WHERE NCG.department_id = ' . $param['selectedId']);
        $generalDate = $query->row();

        $html .= '<div class="card">';
        $html .= '<div class="card-header header-elements-inline">';
        $html .= '<h5 class="card-title">Шүүх шинжилгээний үндэсний хүрээлэнгийн төв байр</h5>';
        $html .= '<div class="header-elements">';
        $html .= '<span class="badge bg-success badge-pill">' . date('Y оны m сарын d өдөр H:i', strtotime($generalDate->created_date)) . '-д шинэчилсэн</span>';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="card-body">';
        $html .= '<div class="chart-container">';
        $html .= '<div class="chart has-fixed-height" id="' . $param['chartId'] . '"></div>';

        $chartData['color'] = array('#2ec7c9', '#b6a2de', '#5ab1ef', '#ffb980', '#d87a80', '#ab4242', '#1a0ab1');
        $chartData['textStyle'] = array('fontFamily' => 'Roboto, Arial, Verdana, sans-serif', 'fontSize' => 13);
        $chartData['animationDuration'] = 750;
        $chartData['grid'] = array('left' => 0, 'right' => 40, 'top' => 35, 'bottom' => 0, 'containLabel' => true);
        $chartData['legend'] = array(
            'data' => array('Хүрээлэн'),
            'itemHeight' => 8,
            'itemGap' => 20,
            'textStyle' => array(0, 5));
        $chartData['tooltip'] = array('trigger' => 'axis', 'backgroundColor' => 'rgba(0,0,0,0.75)', 'padding' => array(10, 15), 'textStyle' => array('fontSize' => 13, 'fontFamily' => 'Roboto, sans-serif'));
        $chartData['xAxis'] = array(
            'type' => 'category',
            'boundaryGap' => false,
            'data' => array('Кримналистик', 'Шүүх эмнэлэг', 'Тусгай шинжилгээ', 'Эдийн засаг', 'Илгээх бичиг'),
            'axisLable' => array('color' => '#333'),
            'axisLine' => array('lineStyle' => array('color' => '#999')),
            'splitLine' => array(
                'show' => true,
                'lineStyle' => array(
                    'color' => '#eee',
                    'type' => 'dashed')));
        $chartData['yAxis'] = array(
            'type' => 'value',
            'axisLabel' => array(
                'color' => '#333'),
            'axisLine' => array('lineStyle' => array('color' => '#999')),
            'splitLine' => array('lineStyle' => array('color' => '#ee')),
            'splitArea' => array('show' => true, 'areaStyle' => array('color' => array('rgba(250,250,250,0.1)', 'rgba(0,0,0,0.01)'))));

        $chartData['series'] = array(
            array(
                'name' => 'Хүрээлэн',
                'type' => 'line',
                'stack' => 'Total',
                'areaStyle' => array('normal' => array('opacity' => 0.25)),
                'smooth' => true,
                'symbolSize' => 7,
                'itemStyle' => array('normal' => array('borderWidth' => 2)))
        );
        $queryData = $this->db->query('
                SELECT 
                    NCG.crime,
                    NCG.forensic,
                    NCG.extra,
                    NCG.economy,
                    NCG.send_document
                FROM `gaz_nifs_chart_general` AS NCG
                WHERE NCG.department_id = ' . $param['selectedId']);
        $rowData = $queryData->row();
        $chartData['series'][0]['data'] = array($rowData->crime, $rowData->forensic, $rowData->extra, $rowData->economy, $rowData->send_document);


        $html .= '<script type="text/javascript">
                    var columns_basic_element = document.getElementById(\'' . $param['chartId'] . '\');
                    if (columns_basic_element) {
                        var columns_basic = echarts.init(columns_basic_element);
                        columns_basic.setOption(' . json_encode($chartData) . ');
                    }
                </script>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

}
