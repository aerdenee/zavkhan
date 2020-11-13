<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('authentication')) {

    function authentication($param = array('permission' => array(), 'moduleMenuId' => 0, 'createdUserId' => 0)) {

        $ourData = array('create' => false, 'read' => false, 'update' => false, 'delete' => false);
        $yourData = array('create' => false, 'read' => false, 'update' => false, 'delete' => false);
        $customData = array('report' => false, 'export' => false, 'close' => false);

        $status = false;
        $modId = 0;
        foreach ($param['permission'] as $key => $row) {

            if ($row->id == $param['moduleMenuId']) {


                if ($row->isModule == 1) {
                    $status = true;
                    $modId = $row->modId;
                }

                foreach ($row->crudOur as $ourKey => $ourRow) {

                    if ($ourRow->mode == 'create' && $ourRow->status == 1) {
                        $ourData['create'] = true;
                    }
                    if ($ourRow->mode == 'read' && $ourRow->status == 1) {
                        $ourData['read'] = true;
                    }
                    if ($ourRow->mode == 'update' && $ourRow->status == 1) {
                        $ourData['update'] = true;
                    }
                    if ($ourRow->mode == 'delete' && $ourRow->status == 1) {
                        $ourData['delete'] = true;
                    }
                }

                foreach ($row->crudYour as $yourKey => $yourRow) {
                    if ($yourRow->mode == 'create' && $yourRow->status == 1) {
                        $yourData['create'] = true;
                    }
                    if ($yourRow->mode == 'read' && $yourRow->status == 1) {
                        $yourData['read'] = true;
                    }
                    if ($yourRow->mode == 'update' && $yourRow->status == 1) {
                        $yourData['update'] = true;
                    }
                    if ($yourRow->mode == 'delete' && $yourRow->status == 1) {
                        $yourData['delete'] = true;
                    }
                }

                foreach ($row->custom as $customKey => $customRow) {
                    if ($customRow->mode == 'report' && $customRow->status == 1) {
                        $customData['report'] = true;
                        $status = true;
                    }
                    if ($customRow->mode == 'export' && $customRow->status == 1) {
                        $customData['export'] = true;
                        $status = true;
                    }
                    if ($customRow->mode == 'close' && $customRow->status == 1) {
                        $customData['close'] = true;
                        $status = true;
                    }
                }

                if ($param['createdUserId'] == $param['currentUserId']) {

                    if ($param['role'] == 'create') {
                        $status = $ourData['create'];
                    }
                    if ($param['role'] == 'read') {
                        $status = $ourData['read'];
                    }
                    if ($param['role'] == 'update') {
                        $status = $ourData['update'];
                    }
                    if ($param['role'] == 'delete') {
                        $status = $ourData['delete'];
                    }
                }

                if ($param['createdUserId'] != $param['currentUserId']) {

                    if ($param['role'] == 'read') {
                        $status = $yourData['read'];
                    }
                    if ($param['role'] == 'update') {
                        $status = $yourData['update'];
                    }
                    if ($param['role'] == 'delete') {
                        $status = $yourData['delete'];
                    }
                }
            }
        }

        return json_decode(json_encode(array(
            'permission' => $status,
            'modId' => $modId,
            'moduleMenuId' => $param['moduleMenuId'],
            'our' => $ourData,
            'your' => $yourData,
            'custom' => $customData)));
    }

}
if (!function_exists('controlSystemLangDropdown')) {

    function controlSystemLangDropdown($param = array('selectedId' => 0)) {

        $ci = & get_instance();

        $html = '';

        $query = $ci->db->query('
                SELECT 
                    L.id,
                    L.title,
                    L.path,
                    L.code,
                    L.is_default
                FROM `gaz_language` AS L
                WHERE L.is_active = 1');

        if ($query->num_rows() > 1 AND IS_MULTI_LANGUAGE) {


            $html .= '<div class="nav-item dropdown">';
            $html .= '<a href="javascript:;" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <img src="/assets/system/img/lang/' . $ci->session->adminLangCode . '.png" class="img-flag mr-2" alt="' . $ci->session->adminLangTitle . '">
                            ' . $ci->session->adminLangTitle . '
                        </a>';
            $html .= '<div class="dropdown-menu dropdown-menu-right">';

            foreach ($query->result() as $row) {
                $html .= '<a href="slanguage/changeLang/' . $row->id . '" class="dropdown-item ' . ($param['selectedId'] == $row->id ? 'active' : '') . '"><img src="/assets/system/img/lang/' . $row->code . '.png" class="img-flag" alt="' . $row->title . '"> ' . $row->title . '</a>';
            }

            $html .= '</div>';
            $html .= '</div>';
        } else if ($query->num_rows() == 1 AND IS_MULTI_LANGUAGE) {
            $html .= '<div class="nav-item dropdown">';
            $html .= '<a href="javascript:;" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <img src="/assets/system/img/lang/' . $ci->session->adminLangCode . '.png" class="img-flag mr-2" alt="' . $ci->session->adminLangTitle . '">
                            ' . $ci->session->adminLangTitle . '
                        </a>';
            $html .= '<div class="dropdown-menu dropdown-menu-right">';

            foreach ($query->result() as $row) {
                $html .= '<a href="slanguage/changeLang/' . $row->id . '" class="dropdown-item ' . ($param['selectedId'] == $row->id ? 'active' : '') . '"><img src="/assets/system/img/lang/' . $row->code . '.png" class="img-flag" alt="' . $row->title . '"> ' . $row->title . '</a>';
            }

            $html .= '</div>';
            $html .= '</div>';
        }

        return $html;
    }

}
if (!function_exists('controlSystemCloseYearDropdown')) {

    function controlSystemCloseYearDropdown($param = array('selectedId' => 0)) {


        $ci = & get_instance();

        $html = '';

        if (!IS_DEFAULT_SYSTEM_USER) {
            $query = $ci->db->query('
                SELECT 
                    NCY.id,
                    NCY.close_year,
                    NCY.close_date
                FROM `gaz_nifs_close_year` AS NCY
                WHERE NCY.is_active = 1');

            if ($query->num_rows() > 1) {

                $html .= '<div class="nav-item dropdown">';
                $html .= '<a href="javascript:;" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="min-width:105px;">
                            <i class="icon-database mr-1"></i>
                            ' . $ci->session->adminCloseYear . '
                        </a>';
                $html .= '<div class="dropdown-menu dropdown-menu-right">';

                foreach ($query->result() as $row) {
                    $html .= '<a href="sarchive/change/' . $row->close_year . '" class="dropdown-item ' . ($ci->session->adminCloseYear == $row->close_year ? 'active' : '') . '" title="' . date('Y оны m сарын d', strtotime($row->close_date)) . ' өдрөөс хойш"><i class="icon-database"></i> ' . $row->close_year . '</a>';
                }

                $html .= '</div>';
                $html .= '</div>';
            }
        }

        return $html;
    }

}

if (!function_exists('dateDiff')) {

    function dateDiff($date = '') {
        $string = '';
        $date1 = date('Y-m-d H:i:s');
        $date2 = $date;

        $diff = abs(strtotime($date2) - strtotime($date1));

        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

        $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));

        $minuts = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);

        $seconds = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minuts * 60));
        if (empty($years) and empty($months) and empty($days) and empty($hours) and ! empty($minuts)) {
            if ($minuts < 5) {
                $string = 'Cаяхан';
            } else {
                $string = $minuts . ' минутын өмнө';
            }
        } elseif (empty($years) and empty($months) and empty($days) and ! empty($hours) and $hours <= 23) {
            $string = $hours . ' цагийн өмнө';
//            if (!empty($minuts)) {
//                $string = $hours . ' цаг ' . $minuts . ' минут';
//            } else {
//                $string = $hours . ' цаг';
//            }
        } elseif (empty($years) and empty($months) and ! empty($days)) {
            if ($days == 1) {
                $string = 'Өчигдөр';
            } elseif ($days == 2) {
                $string = 'Уржигдар';
            } else if (intval($days) < 6) {
                $string = $days . ' өдрийн өмнө ';
//                if (!empty($hours)) {
//                    $string = $days . ' өдөр ' . $hours . ' цаг';
//                } else {
//                    $string = $days . ' өдөр ';
//                }
            } else {
                $string = date("Y оны m сарын d", strtotime($date2));
            }
        } else {
            $string = date("Y оны m сарын d", strtotime($date2));
        }
        return $string;
    }

}

if (!function_exists('getUID')) {

    function getUID($table) {
        $ci = & get_instance();

        $query = $ci->db->query('SELECT id FROM ' . $ci->db->dbprefix . $table . ' ORDER BY id DESC');

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->id + 1;
        }
        return 1;
    }

}

if (!function_exists('checkUrl')) {

    function checkUrl($param = array('url' => '', 'langId' => 1)) {

        $ci = & get_instance();
        if (isset($param['url']) and trim($param['url'])) {

            $searchString = array("/", "_", " ", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "+", "|", "`", "~", ".", ",", "“", "”", "'", "\"", "’");
            $replaceString = array("-", "-", "-", "", "", "", "", "", "", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "", "", "", "", "");
            $param['url'] = str_replace($searchString, $replaceString, mb_strtolower(stripslashes($param['url']), 'UTF-8'));

            $query = $ci->db->query('SELECT url FROM `' . $ci->db->dbprefix . 'url` WHERE url ="' . $param['url'] . '" AND  lang_id ="' . $param['langId'] . '"');

            if ($query->num_rows() > 0) {
                return array('status' => true, 'url' => $param['url']);
            }

            return array('status' => false, 'url' => $param['url']);
        }

        return array('status' => false, 'url' => '');
    }

}

if (!function_exists('createUrl')) {

    function createUrl($param = array('url' => '', 'langId' => 1)) {

        $checkUrl = checkUrl(array('url' => $param['url'], 'langId' => $param['langId']));

        if ($checkUrl['status']) {
            if ($checkUrl['url'] != 'index' or $checkUrl['url'] != 'home') {

                return $checkUrl['url'] . '-' . rand(0, 99) . getUID('url');
            } else {
                return $checkUrl['url'];
            }
        } else if (!$checkUrl['status'] and $checkUrl['url'] != '') {
            return $checkUrl['url'];
        }
        return rand(0, 99) . getUID('url');
    }

}

if (!function_exists('generateUrl')) {

    function generateUrl($param = array('modId' => 0, 'contId' => 0, 'url' => '', 'mode' => 'update')) {

        $ci = & get_instance();
        $ci->db->where('mod_id', $param['modId']);
        $ci->db->where('cont_id', $param['contId']);
        $ci->db->where('lang_id', $param['langId']);
        $ci->db->delete($ci->db->dbprefix . 'url');

        if ($param['mode'] != 'delete') {

            $urlData = array(
                array(
                    'id' => getUID('url'),
                    'mod_id' => $param['modId'],
                    'cont_id' => $param['contId'],
                    'lang_id' => $param['langId'],
                    'url' => createUrl(array('url' => ($param['url'] != '' ? $param['url'] : $param['contId']), 'langId' => $param['langId']))
                )
            );

            $ci->db->insert_batch($ci->db->dbprefix . 'url', $urlData);
        }
    }

}

if (!function_exists('getOrderNum')) {

    function getOrderNum($param = array('table' => 'content', 'field' => 'order_num')) {

        $ci = & get_instance();
        $field = $param['field'];
        $query = $ci->db->query('SELECT ' . $param['field'] . ' FROM ' . $ci->db->dbprefix . $param['table'] . ' ORDER BY ' . $param['field'] . ' DESC');

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->$field + 1;
        }

        return 1;
    }

}

if (!function_exists('getYoutubeId')) {

    function getYoutubeId($data = array()) {
        if ($data['url'] != '') {
            $urlString = explode('?', $data['url']);
            $arr = array();
            parse_str($urlString[1], $arr);
            return $arr['v'];
        }
        return false;
    }

}

if (!function_exists('urlToId')) {

    function urlToId($param = array('url' => '')) {

        $ci = & get_instance();

        $ci->db->where('url', strtolower($param['url']));

        $ci->query = $ci->db->get($ci->db->dbprefix . 'url');
        $ci->result = $ci->query->result();
        $ci->rowUrl = (Array) $ci->result['0'];

        $ci->db->where('id', $ci->rowUrl['mod_id']);
        $ci->query = $ci->db->get($ci->db->dbprefix . 'module');
        $ci->result = $ci->query->result();
        $ci->rowModule = (Array) $ci->result['0'];

//        $ci->db->where('url_id', $ci->rowUrl['id']);
//        $ci->query = $ci->db->get($ci->db->dbprefix . $ci->rowModule['table']);
//        $ci->result = $ci->query->result();
//        $ci->row = (Array) $ci->result['0'];
        return $ci->rowUrl['id'];
    }

}

if (!function_exists('chooseThemeLanguage')) {

    function chooseThemeLanguage($param) {

        $html = '';
        if (isset($param['type']) and $param['type'] == 'googleTranslate') {
            $html .= '<ul class="_theme-choose-lang">';
            $html .= '
		  <li><a href="#googtrans(jp|en)" class="lang-en lang-select" data-lang="en"><img src="assets/theme/tour/img/flag_en.png" alt="USA"></a></li>
		  <li><a href="#googtrans(jp|es)" class="lang-es lang-select" data-lang="es"><img src="assets/theme/tour/img/flag_es.png" alt="MEXICO"></a></li>
		  <li><a href="#googtrans(jp|fr)" class="lang-es lang-select" data-lang="fr"><img src="assets/theme/tour/img/flag_en.png" alt="FRANCE"></a></li>
		  <li><a href="#googtrans(jp|zh-CN)" class="lang-es lang-select" data-lang="zh-CN"><img src="assets/theme/tour/img/flag_cn.png" alt="CHINA"></a></li>
		  <li><a href="#googtrans(jp|ja)" class="lang-es lang-select" data-lang="ja"><img src="assets/theme/tour/img/flag_jp.png" alt="JAPAN"></a></li>
                  ';
            $html .= '
<script type="text/javascript">
    function googleTranslateElementInit() {
      new google.translate.TranslateElement({pageLanguage: \'jp\', layout: google.translate.TranslateElement.FloatPosition.TOP_LEFT}, \'google_translate_element\');
    }

	function triggerHtmlEvent(element, eventName) {
	  var event;
	  if (document.createEvent) {
		event = document.createEvent(\'HTMLEvents\');
		event.initEvent(eventName, true, true);
		element.dispatchEvent(event);
	  } else {
		event = document.createEventObject();
		event.eventType = eventName;
		element.fireEvent(\'on\' + event.eventType, event);
	  }
	}

	jQuery(\'.lang-select\').click(function() {
	  var theLang = jQuery(this).attr(\'data-lang\');
	  jQuery(\'.goog-te-combo\').val(theLang);

	  //alert(jQuery(this).attr(\'href\'));
	  window.location = jQuery(this).attr(\'href\');
	  location.reload();

	});
  </script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

';
        } else {
            $ci = & get_instance();

            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

            $ci->query = $ci->db->query('
            SELECT 
                `id`,
                `title`,
                `path`,
                `code` AS lang_code,
                `is_default`
            FROM `gaz_language`
            WHERE is_active = 1
            ORDER BY `order_num` ASC');

            if ($ci->query->num_rows() > 0) {

                $html .= '<ul class="_theme-choose-lang">';
                foreach ($ci->query->result() as $key => $row) {

                    if ($ci->session->langShortCode == $row->lang_code or $row->is_default == 1) {
                        $html .= '<li><a class="active" href="' . $protocol . $_SERVER['HTTP_HOST'] . '"><img src="assets/theme/tour/img/flag_' . $row->lang_code . '.png"> ' . $row->title . '</a></li>';
                    } else {
                        // . '/language' . $row->path
                        $html .= '<li><a href="' . $protocol . $_SERVER['HTTP_HOST'] . '"><img src="assets/theme/tour/img/flag_' . $row->lang_code . '.png"> ' . $row->title . '</a></li>';
                    }
                }
                $html .= '</ul>';
            }
        }

        return $html;
    }

}

if (!function_exists('mToMonth')) {

    function mToMonth($date) {
        $month = '';
        switch ($date) {
            case '01': $month = 'Jan';
                break;
            case '02': $month = 'Feb';
                break;
            case '03': $month = 'Mar';
                break;
            case '04': $month = 'Apr';
                break;
            case '05': $month = 'May';
                break;
            case '06': $month = 'Jun';
                break;
            case '07': $month = 'Jul';
                break;
            case '08': $month = 'Aug';
                break;
            case '09': $month = 'Sep';
                break;
            case '10': $month = 'Oct';
                break;
            case '11': $month = 'Nov';
                break;
            case '12': $month = 'Dec';
                break;
        }
        return $month;
    }

}

if (!function_exists('mb_ucfirst')) {

    function mb_ucfirst($str, $encoding = "UTF-8", $lower_str_end = false) {
        $first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
        $str_end = "";
        if ($lower_str_end) {
            $str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
        } else {
            $str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
        }
        $str = $first_letter . $str_end;
        return $str;
    }

}

if (!function_exists('clickCount')) {

    function clickCount($param = array('table' => 'content', 'id' => 0)) {
        $ci = & get_instance();
        $query = $ci->db->query('SELECT click, click_real FROM ' . $ci->db->dbprefix . $param['table'] . ' WHERE id=' . $param['id']);

        if ($query->num_rows() > 0) {
            $row = $query->row();
            $click = intval($row->click) + rand(1, 2);
            $clickReal = intval($row->click_real) + 1;
            $data = array(
                'click' => $click,
                'click_real' => $clickReal
            );
            $ci->db->where('id', $param['id']);
            $ci->db->update($ci->db->dbprefix . $param['table'], $data);
        }
    }

}

if (!function_exists('formatInBytes')) {

    function formatInBytes($bytes) {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

}

if (!function_exists('formatInFileExtension')) {

    function formatInFileExtension($extension) {
        $extension = explode('|', $extension);
        $string = '';
        foreach ($extension as $key => $value) {
            $string .= $value . ', ';
        }

        return substr($string, 0, -1);
    }

}

if (!function_exists('generatePassword')) {

    function generatePassword($param = array('lenght' => 5, 'uniqId' => 0)) {
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
                . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                . '0123456789!@#$%^&*()'); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = '';
        foreach (array_rand($seed, $param['lenght']) as $k)
            $rand .= $seed[$k];

        return $rand . (isset($param['uniqId']) ? $param['uniqId'] : '');
    }

}

if (!function_exists('getFileUID')) {

    function getFileUID() {
        return md5(date('YmdHis') . rand(1, 9) . rand(5, 9) . rand(1, 9) . rand(1, 9)) . rand(1, 9) . rand(1, 9);
    }

}

if (!function_exists('getCreateNumber')) {

    function getCreateNumber($param = array('createNumber' => 0, 'table' => '', 'departmentId' => 0)) {

        $ci = & get_instance();

        if ($param['createNumber'] == 0) {
            $ci->query = $ci->db->query('
            SELECT 
                `create_number`
            FROM `' . $ci->db->dbprefix . $param['table'] . '`
            WHERE department_id = ' . $param['departmentId'] . ' AND year = ' . $ci->session->adminCloseYear . '
            ORDER BY `create_number` DESC');
            if ($ci->query->num_rows() > 0) {
                $row = $ci->query->row();
                return intval($row->create_number) + 1;
            }
            return 1;
        } else {
            $ci->query = $ci->db->query('
            SELECT
                `create_number`
            FROM `' . $ci->db->dbprefix . $param['table'] . '`
            WHERE department_id = ' . $param['departmentId'] . ' AND create_number = ' . $param['createNumber'] . ' AND year = ' . $ci->session->adminCloseYear . '
            ORDER BY `create_number` DESC');
            if ($ci->query->num_rows() > 0) {
                $row = $ci->query->row();
                return intval($row->create_number) + 1;
            }
            return $param['createNumber'];
        }
    }

}

if (!function_exists('checkCreateNumber')) {

    function checkCreateNumber($param = array('createNumber' => 0, 'table' => '', 'departmentId' => 0, 'createNumber' => '')) {

        $ci = & get_instance();

        $ci->query = $ci->db->query('
            SELECT 
                `create_number`
            FROM `' . $ci->db->dbprefix . $param['table'] . '`
            WHERE department_id = ' . $param['departmentId'] . ' AND year = ' . $ci->session->adminContsCloseYear . ' AND create_number = \'' . $param['createNumber'] . '\'');
        if ($ci->query->num_rows() > 0) {
            return false;
        }
        return true;
    }

}