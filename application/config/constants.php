<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


/*
 * 
 *  My config
 * 
 */

define('MY_ADMIN', '/systemowner'); // highest automatically-assigned error code
define('IS_MULTIPLE_PARTNER', FALSE); // highest automatically-assigned error code
define('IS_DEFAULT_SYSTEM_USER', TRUE); // Үндсэн контентуудыг удирдана
define('IS_MULTI_LANGUAGE', FALSE); //Олон хэлний сонголттой бол true байх ёстой

define('PAGINATION_PER_PAGE', 20);  //Хуудаслалт үүсгэхэд нэг хуудаст харуулах мэдээллийн тоо
define('PAGINATION_NUM_LINKS', 3); //хуудаслалт үүсгэх дугаарлалтыг харуулах лимит. Жишээ нь: [1][2][3][4][4][6][7][8][9][10] гэх мэт

define('THEME_PAGINATION_PER_PAGE', 10);  //Хуудаслалт үүсгэхэд нэг хуудаст харуулах мэдээллийн тоо
define('THEME_PAGINATION_NUM_LINKS', 5); //хуудаслалт үүсгэх дугаарлалтыг харуулах лимит. Жишээ нь: [1][2][3][4][4][6][7][8][9][10] гэх мэт

define('UPLOADS_CONTENT_PATH','/upload/image/');
define('UPLOADS_MEDIA_PATH','/upload/media/');
define('UPLOADS_USER_PATH','/upload/user/');
define('UPLOADS_URGUDUL_PATH','/upload/urgudul/');
define('UPLOADS_DOCUMENT_PATH','/upload/document/');

define('IS_IMAGE_WATERMARK', TRUE);

define('IS_LOG', FALSE);
define('LOG_TYPE_INSERT', 'insert');
define('LOG_TYPE_UPDATE', 'update');
define('LOG_TYPE_DELETE', 'delete');
define('LOG_TYPE_CLOSE', 'close');

define('CROP_SMALL','s_');
define('SMALL_WIDTH',300);
define('SMALL_HEIGHT',180);

define('CROP_MEDIUM','m_');
define('MEDIUM_WIDTH',630);
define('MEDIUM_HEIGHT',380);

define('CROP_LARGE','l_');
define('LARGE_WIDTH',944);
define('LARGE_HEIGHT',570);

define('CROP_BIG','b_');
define('BIG_WIDTH',1024);
define('BIG_HEIGHT',768);

define('USER_WIDTH',500);
define('USER_HEIGHT',500);

define('UPLOAD_MEDIA_TYPE','mp4|gif|jpg|png|jpeg|swf|mp4');
define('UPLOAD_ALL_FILE_TYPE','pdf|docx|doc|ppt|pptx|xls|xlsx|gif|jpg|png|jpeg|swf|mp4');
define('UPLOAD_OFFICE_TYPE','pdf|docx|doc|ppt|pptx|xls|xlsx');
define('UPLOAD_IMAGE_TYPE','gif|jpg|png|jpeg');
define('UPLOAD_FILE_MAX_SIZE','300000000');
define('UPLOAD_PROFILE_PHOTO_MAX_SIZE','10000000');
define('UPLOAD_IMAGE_MAX_WIDTH','10000');
define('UPLOAD_IMAGE_MAX_HEIGHT','10000');

define('DEFAULT_LATITUDE',45.76566361753729);
define('DEFAULT_LONGITUDE',106.26942002832186);
define('DEFAULT_THEME', '/theme/zavkhan/');
define('DEFAULT_GOOGLE_MAP_APK_KEY', 'AIzaSyC8GOHNpQnOPqV1XIjx2rGi98XxS_5zW-o');
define('CSS_JS_VERSION', date('20200206001'));


define('DEFAULT_GOOGLE_ANALYTICS', '<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119543832-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag(\'js\', new Date());

  gtag(\'config\', \'UA-119543832-1\');
</script>
');

define('GOOGLE_API_KEY', 'AIzaSyDnW-ZYG1H3cd214w28hpWTTOWTnbzuxQc');
define('SENDER_ID', '552670708791');


define('NIFS_PEOPLE_POSITION_NOT_IN', '9,11,18,19,20,21,22,32,33,38,39,40,44');
define('NIFS_EXTRA_EXPERT', '643,644,645,646,');