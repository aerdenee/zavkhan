<?php

class Schart_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
    }

    public function show_model($param = array()) {

        $chartHtml = '';
        switch ($param['catId']) {
            default : {
                    //echart харуулах
                    $chartHtml .= '
                    <div class="chart has-fixed-height" id="' . $param['initId'] . '"></div>
                    
                    <script type="text/javascript">
                        var ' . $param['initId'] . 'element = document.getElementById(\'' . $param['initId'] . '\');
                        if (' . $param['initId'] . 'element) {
                            var ' . $param['initId'] . 'basic = echarts.init(' . $param['initId'] . 'element);
                            ' . $param['initId'] . 'basic.setOption(' . $this->eChartData_model(array('chartId' => $param['chartId'], 'initId' => $param['initId'], 'data' => $param['data'])) . ');
                        }
                        
                    </script>';

                    return $chartHtml;
                }
        }

        exit();
    }

    public function getData_model($param = array()) {
        switch ($param['catId']) {
            default : {
                    return $this->eChartData_model(array('chartId' => $param['chartId'], 'initId' => $param['initId'], 'data' => $param['data']));
                }
        }

        exit();
    }

    function eChartData_model($param = array()) {

        switch ($param['chartId']) {
            case 1: {
                    //Шугаман /line/
                    return $this->eChartLine_model(array('data' => $param['data']));
                };
                break;
            case 2: {
                    //Баганан /column/
                    return $this->eChartColumn_model(array('data' => $param['data']));
                };
                break;
            case 3: {
                    //Хэвтээ багана /bars/
                    return $this->eChartBars_model(array('data' => $param['data']));
                };
                break;
            case 4: {
                    //Нягтшил /Scatter/
                    return $this->eChartScatter_model(array('data' => $param['data']));
                };
                break;
            case 5: {
                    //Бялуу /pie/
                    return $this->eChartPie_model(array('data' => $param['data']));
                };
                break;
            case 6: {
                    //Юүлүүр /funnel/
                    return $this->eChartFunnel_model(array('data' => $param['data']));
                };
                break;
            case 7: {
                    //Радар /Radar/
                    return $this->eChartRadar_model(array('data' => $param['data']));
                };
                break;
            default : {
                    return $this->eChartLine_model(array('data' => $param['data']));
                }
        }
    }

    public function eChartLine_model($param = array()) {

        $initData = array(
            'grid' => array('x' => 40, 'x2' => 40, 'y' => 35, 'y2' => 25),
            'tooltip' => array('trigger' => 'axis'),
            'legend' => array('data' => array('Maximum', 'Minimum')),
            'color' => array('#2ec7c9', '#b6a2de', '#5ab1ef', '#ffb980', '#d87a80', '#8d98b3', '#e5cf0d', '#97b552', '#95706d', '#dc69aa', '#07a2a4', '#9a7fd1', '#588dd5', '#f5994e', '#c05050', '#59678c', '#c9ab00', '#7eb00a', '#6f5553', '#c14089'),
            'title' => array('textStyle' => array('fontWeight' => 'normal', 'fontSize' => 17, 'color' => '#008acd')),
            'calculable' => true,
            'xAxis' => array(array('type' => 'category', 'boundaryGap' => false, 'data' => array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'))),
            'yAxis' => array(array('type' => 'value', 'axisLabel' => array('formatter' => '{value}'))),
            'series' => array(
                array(
                    'name' => 'Maximum',
                    'type' => 'line',
                    'data' => array(11, 11, 15, 13, 12, 13, 10),
                    'markLine' => array('data' => array(array('type' => 'average', 'name' => 'Average')))),
                array(
                    'name' => 'Minimum',
                    'type' => 'line',
                    'data' => array(1, -2, 2, 5, 3, 2, 0),
                    'markLine' => array('data' => array(array('type' => 'average', 'name' => 'Average'))))));

        return $initData;
    }

    public function eChartColumn_model($param = array()) {

        $initData = array(
            'tooltip' => array('trigger' => 'item', 'formatter' => '{a} <br/>{b}: {c} ({d}%)'),
            'legend' => array('orient' => 'vertical', 'x' => 'left', 'data' => array('IE', 'Opera', 'Safari', 'Firefox', 'Chrome')),
            'color' => array('#EF5350', '#66BB6A'),
            'calculable' => true,
            'series' => array(
                array(
                    'name' => 'Browsers',
                    'type' => 'pie',
                    'radius' => '70%',
                    'center' => array('50%', '57.5%'),
                    'data' => array(
                        array('value' => 335, 'name' => 'IE'),
                        array('value' => 310, 'name' => 'Opera'),
                        array('value' => 234, 'name' => 'Safari'),
                        array('value' => 135, 'name' => 'Firefox'),
                        array('value' => 1548, 'name' => 'Chrome')))));
        return $initData;
    }

    public function eChartBars_model($param = array()) {
        $tempDataName = $tempDataValueName = array();

        if ($param['data']) {
            foreach ($param['data'] as $key => $row) {
                $tempDataName[$key] = $row['name'];
                $tempDataValueName[$key] = array('value' => $row['value'], 'name' => $row['name']);
            }
        }

        $legentName = $data = $value = '';
        foreach ($param['data'] as $key => $row) {
            $legentName .= '"' . $row['name'] . '",';
            $value .= $row['value'] . ',';
            $data .= '{value: ' . $row['value'] . ', name: "' . $row['name'] . '"},';
        }

        $legentName = rtrim($legentName, ',');
        $data = rtrim($data, ',');
        $value = rtrim($value, ',');

        $initData = '{
            color: [\'#3398DB\'],
            tooltip : {
                trigger: \'axis\',
                axisPointer : {
                    type : \'shadow\'
                }
            },
            grid: {
                left: \'3%\',
                right: \'4%\',
                bottom: \'3%\',
                containLabel: true
            },
            xAxis : [
                {
                    type : \'category\',
                    data : [' . $legentName . '],
                    axisTick: {
                        alignWithLabel: true
                    }
                }
            ],
            yAxis : [
                {
                    type : \'value\'
                }
            ],
            series : [
                {
                    name:\'Тоон утга\',
                    type:\'bar\',
                    barWidth: \'60%\',
                    data:[' . $value . ']
                }
            ]
}';

        return $initData;

        $data = '
            chartInitOption = {

                // Add tooltip
                tooltip: {
                    trigger: \'item\',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                legend: {
                    orient: \'vertical\',
                    x: \'left\',
                    data: [\'IE\', \'Opera\', \'Safari\', \'Firefox\', \'Chrome\']
                },

                // Enable drag recalculate
                calculable: true,

                // Add series
                series: [{
                        name: \'Browsers\',
                        type: \'pie\',
                        radius: \'70%\',
                        center: [\'50%\', \'57.5%\'],
                        data: [
                            {value: 335, name: \'IE\'},
                            {value: 310, name: \'Opera\'},
                            {value: 234, name: \'Safari\'},
                            {value: 135, name: \'Firefox\'},
                            {value: 1548, name: \'Chrome\'}
                        ]
                    }]
            };';
        return $data;
    }

    public function eChartScatter_model($param = array()) {
        $data = '
            chartInitOption = {

                // Add tooltip
                tooltip: {
                    trigger: \'item\',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                legend: {
                    orient: \'vertical\',
                    x: \'left\',
                    data: [\'IE\', \'Opera\', \'Safari\', \'Firefox\', \'Chrome\']
                },

                // Enable drag recalculate
                calculable: true,

                // Add series
                series: [{
                        name: \'Browsers\',
                        type: \'line\',
                        radius: \'70%\',
                        center: [\'50%\', \'57.5%\'],
                        data: [
                            {value: 335, name: \'IE\'},
                            {value: 310, name: \'Opera\'},
                            {value: 234, name: \'Safari\'},
                            {value: 135, name: \'Firefox\'},
                            {value: 1548, name: \'Chrome\'}
                        ]
                    }]
            };';
        return $data;
    }

    public function eChartPie_model($param = array()) {

        $tempDataName = $tempDataValueName = array();

        if (is_array($param['data'])) {
            foreach ($param['data'] as $key => $row) {
                $tempDataName[$key] = $row['name'];
                $tempDataValueName[$key] = array('value' => $row['value'], 'name' => $row['name']);
            }
        }

        $legentName = $data = '';
        foreach ($param['data'] as $key => $row) {
            $legentName .= '"' . $row['name'] . '",';
            $data .= '{value: ' . $row['value'] . ', name: "' . $row['name'] . '"},';
        }

        $legentName = rtrim($legentName, ',');
        $data = rtrim($data, ',');

        $initData = '{// Add tooltip
                        tooltip: {
                            trigger: \'item\',
                            backgroundColor: \'rgba(0,0,0,0.75)\',
                            padding: [10, 15],
                            textStyle: {
                                fontSize: 11,
                                fontFamily: \'Roboto, sans-serif\'
                            },
                            formatter: "{a} <br/>{b}: {c} ({d}%)"
                        },
                        
/*

                        legend: {
                            orient: \'vertical\',
                            top: \'center\',
                            left: 0,
                            data: [' . $legentName . '],
                            itemHeight: 8,
                            itemWidth: 8
                        },
*/
                        "color": [\'#2ec7c9\', \'#b6a2de\', \'#5ab1ef\', \'#ffb980\', \'#d87a80\',
                            \'#8d98b3\', \'#e5cf0d\', \'#97b552\', \'#95706d\', \'#dc69aa\',
                            \'#07a2a4\', \'#9a7fd1\', \'#588dd5\', \'#f5994e\', \'#c05050\',
                            \'#59678c\', \'#c9ab00\', \'#7eb00a\', \'#6f5553\', \'#c14089\'],
                        // Global text styles
                        textStyle: {
                            fontFamily: \'Roboto, Arial, Verdana, sans-serif\',
                            fontSize: 11
                        },
                        "calculable": true,
                        // Add series
                        series: [{
                                name: \'Тайлбар\',
                                type: \'pie\',
                                radius: \'70%\',
                                center: [\'50%\', \'57.5%\'],
                                itemStyle: {
                                    normal: {
                                        borderWidth: 1,
                                        borderColor: \'#fff\'
                                    }
                                },
                                data: [' . $data . ']
                            }]
                    }';

        return $initData;
    }

    public function eChartFunnel_model($param = array()) {
        $data = '
            chartInitOption = {

                // Add tooltip
                tooltip: {
                    trigger: \'item\',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                legend: {
                    orient: \'vertical\',
                    x: \'left\',
                    data: [\'IE\', \'Opera\', \'Safari\', \'Firefox\', \'Chrome\']
                },

                // Enable drag recalculate
                calculable: true,

                // Add series
                series: [{
                        name: \'Browsers\',
                        type: \'pie\',
                        radius: \'70%\',
                        center: [\'50%\', \'57.5%\'],
                        data: [
                            {value: 335, name: \'IE\'},
                            {value: 310, name: \'Opera\'},
                            {value: 234, name: \'Safari\'},
                            {value: 135, name: \'Firefox\'},
                            {value: 1548, name: \'Chrome\'}
                        ]
                    }]
            };';
        return $data;
    }

    public function eChartRadar_model($param = array()) {
        $data = '
            chartInitOption = {

                // Add tooltip
                tooltip: {
                    trigger: \'item\',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                legend: {
                    orient: \'vertical\',
                    x: \'left\',
                    data: [\'IE\', \'Opera\', \'Safari\', \'Firefox\', \'Chrome\']
                },

                // Enable drag recalculate
                calculable: true,

                // Add series
                series: [{
                        name: \'Browsers\',
                        type: \'pie\',
                        radius: \'70%\',
                        center: [\'50%\', \'57.5%\'],
                        data: [
                            {value: 335, name: \'IE\'},
                            {value: 310, name: \'Opera\'},
                            {value: 234, name: \'Safari\'},
                            {value: 135, name: \'Firefox\'},
                            {value: 1548, name: \'Chrome\'}
                        ]
                    }]
            };';
        return $data;
    }

    public function controlChartListDropdown_model($param = array('modId' => 0, 'selectedId' => 0, 'required' => true)) {

        $this->queryString = $this->string = '';

        $controlName = 'catId';
//        if ($this->session->adminAccessTypeId == 2) {
//            $this->queryString .= ' AND partner_id = ' . $this->session->adminPartnerId;
//        } else {
//            $this->queryString .= ' AND parent_id = ' . $param['parentId'];
//        }

        if (isset($param['name'])) {
            $controlName .= ' name="' . $param['name'] . '"';
        }

        if (isset($param['disabled']) and $param['disabled'] == true) {
            $this->string .= ' disabled="true"';
        }

        if (isset($param['required']) and $param['required'] == true) {
            $this->string .= ' required="true"';
        }

        if (isset($param['readonly']) and $param['readonly'] == true) {
            $this->string .= ' disabled="true"';
        }

        $this->query = $this->db->query('
            SELECT 
                id,
                title
            FROM `gaz_chart`
            WHERE parent_id = 0 AND cat_id = ' . $param['catId'] . ' AND is_active = 1
            ORDER BY order_num ASC');

        $this->html = '<select class="form-control select2" name="' . $controlName . '" id="' . $controlName . '" ' . $this->string . ' onchange="' . $param['onChangeFunction'] . '">';

        $this->html .= '<option value="0" ' . ($param['selectedId'] == 0 ? 'selected="selected"' : '') . '>' . ' - Сонгох - </option>';

        if ($this->query->num_rows() > 0) {
            foreach ($this->query->result() as $row) {
                $this->html .= '<option value="' . $row->id . '" ' . ($param['selectedId'] == $row->id ? 'selected="selected"' : '') . '>&nbsp; &nbsp;' . $row->title . '</option>';
            }
        }
        $this->html .= '</select>';
        return $this->html;
    }

}
