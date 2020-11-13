<?php

class Ttour_model extends CI_Model {

    function __construct() {
        /* Call the Model constructor */
        parent::__construct();

        $this->load->model('Scategory_model', 'category');
        $this->load->model('Tmedia_model', 'tmedia');
        $this->load->model('Tmenu_model', 'tmenu');
    }

    public function controlDropdownTourList_model() {
        $html = '';
        $query = $this->db->query('
            SELECT 
                T.id,
                T.title,
                U.url,
                CALENDAR.day
            FROM `gaz_tour` AS T
            INNER JOIN ( 
                SELECT 
                    N.cont_id, 
                    COUNT(N.cont_id) AS day
                FROM `gaz_tour_calendar` AS N 
                WHERE N.mod_id = 19
                GROUP BY N.cont_id 
            ) AS CALENDAR ON T.id = CALENDAR.cont_id
            INNER JOIN `gaz_url` AS U ON T.mod_id = U.mod_id AND T.id = U.cont_id
            WHERE T.lang_id = ' . $this->session->userdata['themeLanguage']['id'] . ' AND T.is_active = 1
            ORDER BY T.title ASC');

        if ($query->num_rows() > 0) {

            $html .= '<select name="tourId" id="tourId" class="select2">';
            $html .= '<option value=""> --- ツアーをお選び下さい --- </option>';
            foreach ($query->result() as $row) {
                $html .= '<option value="' . $row->id . '">' . $row->title . ' (' . $row->day . ' days)</option>';
            }
            $html .= '</select>';
            return $html;
        }

        return false;
    }

    public function controlDropdownTourMonth_model() {

        $html = '<select name="monthId" id="monthId" class="select2">';
        $html .= '<option value=""> --- 出発月を選択して下さい --- </option>';
        $html .= '<option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>';
        $html .= '</select>';

        return $html;
    }

    public function tourCalendar_model($param = array('tourId' => 0, 'monthId' => 0)) {

        $html = $queryString = '';

        if ($param['tourId'] != 0) {
            $queryString = ' AND TC.cont_id = ' . $param['tourId'];
        }

        if ($param['monthId'] != 0) {
            $queryString = ' AND TC.in_month = ' . $param['monthId'];
        }
        $queryYear = $this->db->query('
            SELECT 
                TC.in_year
            FROM gaz_tour_calendar AS TC 
            WHERE TC.is_active = 1 
            GROUP BY TC.in_year ASC');

        if ($queryYear->num_rows() > 0) {

            $html .= '<div class="_theme-trip-calendar col-md-12 mb-3">';
            $html .= '<div class="table">';

            $html .= '<div class="row thead hidden-xs"> ';
            $html .= '<div class="col-md-3 col-sm-3"><div class="td text-left">催行日付</div></div>';
            $html .= '<div class="col-md-4 col-sm-4"><div class="td text-left">モンゴルバイクツアー名</div></div>';
            $html .= '<div class="col-md-1 col-sm-1"><div class="td text-left">日数</div></div>';
            $html .= '<div class="col-md-2 col-sm-2"><div class="td text-center">料金</div></div>';
            $html .= '<div class="col-md-2 hidden-sm"><div class="td text-center">状況</div></div>';
            $html .= '</div>';
            foreach ($queryYear->result() as $keyYear => $rowYear) {

                $queryMonth = $this->db->query('
                    SELECT 
                        TC.in_month,
                        IF(TC.is_active = 1, \'open\', \'close\') AS is_active_class,
                        IF(TC.is_active = 1, \'Bookings open\', \'Fully booked\') AS is_active_name
                    FROM gaz_tour_calendar AS TC
                    WHERE TC.in_year = ' . $rowYear->in_year . $queryString . ' 
                    GROUP BY TC.in_month ASC');

                if ($queryMonth->num_rows() > 0) {

                    foreach ($queryMonth->result() as $keyMonth => $rowMonth) {

                        $html .= '<div class="row brow bg-gray ' . (empty($keyMonth) != 0 ? '' : 'mt-4') . '">';
                        $html .= '<div class="col-md-12">';
                        $html .= '<div class="row-title">';
                        $html .= $rowYear->in_year . ' 年 ' . $rowMonth->in_month . ' 月';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '</div>';

                        $query = $this->db->query('
                            SELECT 
                                TC.in_date,
                                TC.out_date,
                                T.title,
                                T.code,
                                TI.count_day,
                                TC.price,
                                U.url
                            FROM gaz_tour_calendar AS TC 
                            INNER JOIN gaz_tour AS T ON TC.mod_id = T.mod_id AND TC.cont_id = T.id
                            INNER JOIN `gaz_url` AS U ON T.mod_id = U.mod_id AND T.id = U.cont_id
                            INNER JOIN (
                                SELECT
                                        TI.cont_id,
                                        COUNT(TI.cont_id) AS count_day
                                FROM `gaz_tour_itinerary` AS TI 
                                WHERE TI.is_active = 1
                                GROUP BY TI.cont_id
                            ) AS TI ON TI.cont_id = T.id
                            WHERE TC.is_active = 1 AND TC.in_year = ' . $rowYear->in_year . ' AND TC.in_month = ' . $rowMonth->in_month . $queryString . ' 
                            ORDER BY TC.in_date DESC');

                        if ($query->num_rows() > 0) {

                            foreach ($query->result() as $row) {

                                $inDate = explode('-', $row->in_date);
                                $garag = date('D', strtotime($row->in_date));
                                switch ($garag) {
                                    case 'Mon': {
                                        $garag = '月';
                                    };break;
                                case 'Tue': {
                                        $garag = '火';
                                    };break;
                                case 'Wed': {
                                        $garag = '水';
                                    };break;
                                case 'Thu': {
                                        $garag = '木';
                                    };break;
                                case 'Fri': {
                                        $garag = '金';
                                    };break;
                                case 'Sat': {
                                        $garag = '土';
                                    };break;
                                case 'Sun': {
                                        $garag = '日';
                                    };break;
                                }
                                $outDate = explode('-', $row->out_date);

                                //$html .= '<div class="">';
                                    $html .= '<div class="m-departures row brow">';
                                        $html .= '<div class="col-md-3 col-sm-3 col hidden-xs">';
                                            $html .= '<div class="td text-left">' . $inDate['1'] . ' 月 ' . $inDate['2'] . '（土） - ' . $outDate['1'] . ' 月 ' . $outDate['2'] . ' 日（' . $garag . '）</div>';
                                        $html .= '</div>';
                                        $html .= '<div class="col-md-4 col-sm-4 col">';
                                            $html .= '<div class="td text-left"><a href="' . $row->url . '" target="_blank">' . $row->code . ' - ' . $row->title . ' (' . $row->count_day . ' 日間)</a></div>';
                                        $html .= '</div>';
                                        $html .= '<div class="col-xs-6 col visible-xs">';
                                            $html .= '<div class="td text-left">' . $inDate['1'] . ' 月 ' . $inDate['2'] . '（土） - ' . $outDate['1'] . ' 月 ' . $outDate['2'] . ' 日（水） (' . $row->count_day . ' 日間)</div>';
                                        $html .= '</div>';
                                        $html .= '<div class="col-md-1 col-sm-2 col hidden-xs">';
                                            $html .= '<div class="td text-left">' . $row->count_day . ' 日間</div>';
                                        $html .= '</div>';
                                        $html .= '<div class="col-md-2 col-sm-3 col-xs-6 col">';
                                            $html .= '<div class="td text-center">';
                                                $html .= '<span class="tour-calendar-price">' . $row->price . ' 円</span>';
                                            $html .= '</div>';
                                        $html .= '</div>';
                                        $html .= '<div class="col-md-2 col tour-link hidden-sm hidden-xs">';
                                            $html .= '<div class="td text-center"><a href="' . $row->url . '" target="_blank" class="_read-more  ' . $rowMonth->is_active_class . '">' . $rowMonth->is_active_name . '</a></div>';
                                        $html .= '</div>';
                                    $html .= '</div>';
                                //$html .= '</div>';
                            }
                        }
                    }
                }
            }
            $html .= '</div>';
            $html .= '</div>';

//            $html .= '<div class="col-md-12">';
//            $html .= '<div class="row _theme-trip-calendar mt-3">
//                <div class="table">
//                   <h4>Calendar note:</h4>
//                    <div class="td p-0"><span class="_read-more open">Bookings open</span> Guaranteed departure.</div>
//                    <div class="td p-0"><span class="_read-more close">Fully booked</span> Fully booked.</div>
//                </div>
//            </div>';
//            $html .= '</div>';
            $html .= '<br>';
            return $html;
        }

        return false;
    }

    public function listsCount_model($param = array()) {

        $queryString = '';

        $query = $this->db->query('
            SELECT 
                T.id
            FROM `gaz_tour` AS T
            WHERE T.lang_id = ' . $this->session->userdata['themeLanguage']['id'] . ' AND T.cat_id IN(' . $this->category->getChildCategores_model($param['catId']) . ') AND T.is_active = 1' . $queryString);

        return $query->num_rows();
    }

    public function lists_model($param = array('catId' => 0, 'page' => 0, 'limit' => 0)) {


        $html = $queryString = '';

        $query = $this->db->query('
            SELECT 
                T.id,
                T.mod_id,
                MEDIA.pic,
                T.title,
                T.link_title,
                T.intro_text,
                T.meta_desc,
                T.comment_count,
                T.click,
                T.is_active_date,
                U.url,
                CAT.title AS cat_title,
                T.theme_layout_id,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS author,
                T.code,
                CALENDAR.day,
                IF ((CALENDAR.day - 1) > 0, (CALENDAR.day - 1), \'\') AS night,
                T.calendar
            FROM `gaz_tour` AS T
            INNER JOIN `gaz_url` AS U ON T.mod_id = U.mod_id AND T.id = U.cont_id
            INNER JOIN `gaz_category` AS CAT ON T.mod_id = CAT.mod_id AND T.cat_id = CAT.id
            INNER JOIN `gaz_hr_people` AS HP ON T.people_id = HP.id
            INNER JOIN (
                SELECT
                    N.cont_id,
                    N.pic
                FROM `gaz_content_media` AS N
                WHERE N.mod_id = 19 AND N.pic != \'\'
                GROUP BY N.cont_id
                ORDER BY N.id ASC
            ) AS MEDIA ON MEDIA.cont_id = T.id
            INNER JOIN ( 
                SELECT 
                    N.cont_id, 
                    COUNT(N.cont_id) AS day
                FROM `gaz_tour_calendar` AS N 
                WHERE N.mod_id = 19
                GROUP BY N.cont_id 
            ) AS CALENDAR ON T.id = CALENDAR.cont_id
            WHERE T.lang_id = ' . $this->session->userdata['themeLanguage']['id'] . ' AND T.cat_id IN(' . $this->category->getChildCategores_model($param['catId']) . ') AND T.is_active = 1 ' . $queryString . '
            ORDER BY T.order_num ASC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $html .= '<div class="col-md-4">';
                    $html .= '<div class="tour-list animated bounceInRight">';
                        $html .= '<div class="_theme-tour-media">';
                            $html .= '<div class="imglink">';
                                $html .= '<img class="in_shadow" src="' . UPLOADS_CONTENT_PATH . $row->pic . '" alt="' . $row->link_title . '">';
                            $html .= '</div>';
                            $html .= '<div class="fill_absolute in_shadow60">';
                                $html .= '<a title="' . $row->link_title . '" href="' . $this->session->themeLanguage['path'] . $row->url . '"></a>';
                            $html .= '</div>';
                            $html .= '<div class="tour-code">';
                                $html .= '<a title="' . $row->link_title . '" href="' . $this->session->themeLanguage['path'] . $row->url . '">' . $row->code . '</a>';
                            $html .= '</div>';
                            $html .= '<div class="tour-photo-back">';
                                $html .= '<a title="' . $row->link_title . '" href="' . $this->session->themeLanguage['path'] . $row->url . '">' . $row->title . '</a>';
                            $html .= '</div>';
                        $html .= '</div>';
                        $html .= '<div class="tour-list-body">';
                            $html .= '<div class="title">';
                                $html .= '<p class="duration">期間: ' . $row->day . ($row->day > 0 ? '日目' : '') . ' ' . ($row->night > 0 ? ($row->night . ' nights') : '') . '</p>';
                            $html .= '</div>';
                            
                            $queryCalendar = $this->db->query('
                            SELECT 
                                TC.cont_id,
                                TC.in_date,
                                TC.out_date,
                                TC.price
                            FROM gaz_tour_calendar AS TC 
                            WHERE TC.is_active = 1 AND TC.cont_id = ' . $row->id . ' AND TC.mod_id = ' . $row->mod_id . ' 
                            ORDER BY TC.in_date DESC');

                            $dateStr = '';
                            
                            if ($queryCalendar->num_rows() > 0) {
                                foreach ($queryCalendar->result() as $rowCalendar) {
                                    $dateStr .= date('m 月 d 日', strtotime($rowCalendar->in_date)) . ', ';
                                }
                            }
                            
                            $html .= '<div class="text">' . $dateStr . ' ' . word_limiter($row->meta_desc, 20, '...') . '</div>';
                            $html .= '<div class="group-btn-tours"><a href="' . $this->session->themeLanguage['path'] . $row->url . '" class="left-btn">続きを読む</a></div>';
                        $html .= '</div>';
                    $html .= '</div>';
                $html .= '</div>';
                
            }
            
            $html .= '<div class="clearfix"></div>';
            
            return $html;
            
        }

        return false;
    }

    public function getItem_model($param = array('contId' => 0)) {

        $query = $this->db->query('
            SELECT 
                T.id,
                T.mod_id,
                T.cat_id,
                T.show_pic_inside,
                T.pic,
                T.title,
                T.code,
                T.link_title,
                T.intro_text,
                T.full_text,
                T.page_title,
                T.meta_key,
                T.meta_desc,
                T.h1_text,
                T.show_date,
                T.is_active_date,
                T.show_people,
                T.people_id,
                T.created_user_id,
                T.show_comment,
                T.comment_count,
                T.show_click,
                T.click,
                T.click_real,
                T.is_active,
                T.order_num,
                T.show_social,
                T.param,
                IF(T.theme_layout_id > 0, TL.theme, \'item\') AS theme,
                T.partner_id,
                U.id AS url_id,
                U.url,
                CAT.title AS cat_title,
                CONCAT(SUBSTRING(HP.lname, 1, 1), \'.\', HP.fname) AS full_name,
                T.details,
                CALENDAR.day
            FROM `gaz_tour` AS T
            INNER JOIN ( 
                SELECT 
                    N.cont_id, 
                    COUNT(N.cont_id) AS day
                FROM `gaz_tour_calendar` AS N 
                WHERE N.mod_id = 19
                GROUP BY N.cont_id 
            ) AS CALENDAR ON T.id = CALENDAR.cont_id
            LEFT JOIN `gaz_url` U on T.mod_id = U.mod_id AND T.id = U.cont_id 
            LEFT JOIN `gaz_hr_people` HP ON T.people_id = HP.id
            LEFT JOIN `gaz_theme_layout` TL ON T.theme_layout_id = TL.id
            INNER JOIN `gaz_category` AS CAT ON T.mod_id = CAT.mod_id AND T.cat_id = CAT.id
            WHERE T.lang_id = ' . $this->session->userdata['themeLanguage']['id'] . ' AND T.id = ' . $param['contId'] . ' AND T.is_active = 1');

        if ($query->num_rows() > 0) {

            return $query->row();
        }

        return false;
    }

    public function tourItinerary_model($param = array('contId' => 0)) {

        $query = $this->db->query('
            SELECT 
                TI.id,
                TI.order_num AS day,
                TI.title,
                TI.food,
                TI.transportation,
                TI.accommodation,
                TI.other,
                TI.intro_text,
                TI.pic
            FROM `gaz_tour_itinerary` AS TI
            WHERE TI.cont_id = ' . $param['contId'] . ' AND TI.mod_id = ' . $param['modId']);

        if ($query->num_rows() > 0) {

            return $query->result();
        }

        return false;
    }

}
