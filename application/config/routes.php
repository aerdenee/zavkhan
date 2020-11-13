<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'troute/manage';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['assets/theme.min.css'] = 'tassets/minifyCss';
$route['assets/theme.min.js'] = 'tassets/minifyJs';

$route['assets/global.min.(:any).css'] = 'sassets/minifyCss';
$route['assets/global.min.(:any).js'] = 'sassets/minifyJs';

$route['jsonHome'] = 'troute/myControllerJsonHome';

$route['systemowner'] = 'sauthentication/index';
$route['systemowner/passwordResetForm'] = 'sauthentication/passwordResetForm';
$route['systemowner/passwordReset'] = 'sauthentication/passwordReset';
$route['systemowner/sendMail'] = 'sauthentication/sendMail';
$route['systemowner/login'] = 'sauthentication/login';
$route['systemowner/logout'] = 'sauthentication/logout';
$route['dashboard'] = 'sdashboard/index';
$route['systemowner/dashboard'] = 'sdashboard/index';


$route['systemowner/mLogin'] = 'sauthentication/mLogin';

$route['srest/(:any)'] = 'srest/$1';
$route['srest/(:any)/(:any)'] = 'srest/$1/$2';

/*
 * Reaction module
 */
$route['sreaction/(:any)'] = 'sreaction/$1';
$route['sreaction/(:any)/(:any)'] = 'sreaction/$1/$2';


/*
 * Log system
 */
$route['slog/(:any)'] = 'slog/$1';
$route['slog/(:any)/(:any)'] = 'slog/$1/$2';

/*
 * 0 - Зураг
 */
$route['simage/(:any)'] = 'simage/$1';
$route['simage/(:any)/(:any)'] = 'simage/$1/$2';


/*
 * 0 - Зураг
 */
$route['smodule/(:any)'] = 'smodule/$1';
$route['smodule/(:any)/(:any)'] = 'smodule/$1/$2';

/*
 * 0 - Файл
 */
$route['sfile/(:any)'] = 'sfile/$1';
$route['sfile/(:any)/(:any)'] = 'sfile/$1/$2';

$route['sprofile/(:any)'] = 'sprofile/$1';
$route['sprofile/(:any)/(:any)'] = 'sprofile/$1/$2';


/*
 * 1 - Меню
 */
$route['smenu/(:any)'] = 'smenu/$1';
$route['smenu/(:any)/(:any)'] = 'smenu/$1/$2';


/*
 * 2 - Агуулга
 * 6 - Хүмүүс
 * 7 - Бүтээгдэхүүн
 * 9 - Гэр баазын мэдээлэл
 * 10 - Нэмэлт үйлчилгээ
 * 11 - Үзэх газар
 * 19 - Аялал
 * 20 - Ресторан
 * 41 - Төрийн үйлчилгээ
 * 42 - Сум
 * 43 - Ангентлаг
 * 44 - Ил тод байдал
 * 57 - Интеактив хичээл
 */
$route['scontent/(:any)'] = 'scontent/$1';
$route['scontent/(:any)/(:any)'] = 'scontent/$1/$2';

$route['scontentMedia/(:any)'] = 'scontentMedia/$1';
$route['scontentMedia/(:any)/(:any)'] = 'scontentMedia/$1/$2';

$route['scontentComment/(:any)'] = 'scontentComment/$1';
$route['scontentComment/(:any)/(:any)'] = 'scontentComment/$1/$2';

$route['scontentGmap/(:any)'] = 'scontentGmap/$1';
$route['scontentGmap/(:any)/(:any)'] = 'scontentGmap/$1/$2';

/*
 * 3 - Арга хэмжээ
 */
$route['sevent/(:any)'] = 'sevent/$1';
$route['sevent/(:any)/(:any)'] = 'sevent/$1/$2';

/*
 * 4 - Газрын зураг
 */
$route['smap/(:any)'] = 'smap/$1';
$route['smap/(:any)/(:any)'] = 'smap/$1/$2';

/*
 * 8 - Сэтгэгдэл
 */
$route['scomment/(:any)'] = 'scomment/$1';
$route['scomment/(:any)/(:any)'] = 'scomment/$1/$2';

/*
 * 12 - Холбоо барих
 */
$route['scontact/(:any)'] = 'scontact/$1';
$route['scontact/(:any)/(:any)'] = 'scontact/$1/$2';

/*
 * 13 - Feedback
 */
$route['sfeedback/(:any)'] = 'sfeedback/$1';
$route['sfeedback/(:any)/(:any)'] = 'sfeedback/$1/$2';

/*
 * 14 - Layout
 */
$route['slayout/(:any)'] = 'slayout/$1';
$route['slayout/(:any)/(:any)'] = 'slayout/$1/$2';

/*
 * 15 - Зар
 */
$route['sadvertisement/(:any)'] = 'sadvertisement/$1';
$route['sadvertisement/(:any)/(:any)'] = 'sadvertisement/$1/$2';

/*
 * 16 - Өргөдөл
 */
$route['surgudul/(:any)'] = 'surgudul/$1';
$route['surgudul/(:any)/(:any)'] = 'surgudul/$1/$2';

/*
 * 17 - Албан бичиг
 */
$route['sdocIn/(:any)'] = 'sdocIn/$1';
$route['sdocIn/(:any)/(:any)'] = 'sdocIn/$1/$2';

$route['sdocOut/(:any)'] = 'sdocOut/$1';
$route['sdocOut/(:any)/(:any)'] = 'sdocOut/$1/$2';

$route['sdocFile/(:any)'] = 'sdocFile/$1';
$route['sdocFile/(:any)/(:any)'] = 'sdocFile/$1/$2';

$route['sdocClose/(:any)'] = 'sdocClose/$1';
$route['sdocClose/(:any)/(:any)'] = 'sdocClose/$1/$2';

$route['sdocTransfer/(:any)'] = 'sdocTransfer/$1';
$route['sdocTransfer/(:any)/(:any)'] = 'sdocTransfer/$1/$2';


/*
 * 19 - Аялал
 */
$route['stour/(:any)'] = 'stour/$1';
$route['stour/(:any)/(:any)'] = 'stour/$1/$2';

$route['stourItinerary/(:any)'] = 'stourItinerary/$1';
$route['stourItinerary/(:any)/(:any)'] = 'stourItinerary/$1/$2';

$route['stourCalendar/(:any)'] = 'stourCalendar/$1';
$route['stourCalendar/(:any)/(:any)'] = 'stourCalendar/$1/$2';


/*
 * 21 - Гэр, байшин сууцны бүртгэл
 */
$route['saccommodation/(:any)'] = 'saccommodation/$1';
$route['saccommodation/(:any)/(:any)'] = 'saccommodation/$1/$2';

/*
 * 22 - Хэрэглэгч
 */
$route['suser/(:any)'] = 'suser/$1';
$route['suser/(:any)/(:any)'] = 'suser/$1/$2';

/*
 * 23 - Захиалга
 */
$route['sreservation/(:any)'] = 'sreservation/$1';
$route['sreservation/(:any)/(:any)'] = 'sreservation/$1/$2';

/*
 * 24 - Харилцагч байгууллага
 */
$route['spartner/(:any)'] = 'spartner/$1';
$route['spartner/(:any)/(:any)'] = 'spartner/$1/$2';

/*
 * 25 - Хаягийн лавлах
 */
$route['saddress/(:any)'] = 'saddress/$1';
$route['saddress/(:any)/(:any)'] = 'saddress/$1/$2';

/*
 * 29 - Системд хандах эрхийн тохиргоо
 */
$route['spermission/(:any)'] = 'spermission/$1';
$route['spermission/(:any)/(:any)'] = 'spermission/$1/$2';

/*
 * 33 - Криминалистик бүртгэл
 */
$route['snifsCrime/(:any)'] = 'SnifsCrime/$1';
$route['snifsCrime/(:any)/(:any)'] = 'SnifsCrime/$1/$2';


/*
 * 34 - Хэргийн газрын үзлэг
 */
$route['snifsScene/(:any)'] = 'snifsScene/$1';
$route['snifsScene/(:any)/(:any)'] = 'snifsScene/$1/$2';


/*
 * 50 - Хавтаст хэргийн үзлэг
 */
$route['snifsFileFolder/(:any)'] = 'snifsFileFolder/$1';
$route['snifsFileFolder/(:any)/(:any)'] = 'snifsFileFolder/$1/$2';

/*
 * 51 - Үзлэгийн бүртгэл
 */
$route['snifsDoctorView/(:any)'] = 'snifsDoctorView/$1';
$route['snifsDoctorView/(:any)/(:any)'] = 'snifsDoctorView/$1/$2';

/*
 * 52 - Задлан шинжилгээний бүртгэл
 */
$route['snifsAnatomy/(:any)'] = 'snifsAnatomy/$1';
$route['snifsAnatomy/(:any)/(:any)'] = 'snifsAnatomy/$1/$2';

/*
 * 54 - Module menu
 */
$route['smoduleMenu/(:any)'] = 'smoduleMenu/$1';
$route['smoduleMenu/(:any)/(:any)'] = 'smoduleMenu/$1/$2';

/*
 * 55 - Тусгай шинжилгээ
 */
$route['snifsExtra/(:any)'] = 'snifsExtra/$1';
$route['snifsExtra/(:any)/(:any)'] = 'snifsExtra/$1/$2';

/*
 * 56 - Эдийн засгийн шинжилгээ
 */
$route['snifsEconomy/(:any)'] = 'snifsEconomy/$1';
$route['snifsEconomy/(:any)/(:any)'] = 'snifsEconomy/$1/$2';

/*
 * 58 - Интерактив хичээл үзэх
 */
$route['slearning/(:any)'] = 'slearning/$1';
$route['slearning/(:any)/(:any)'] = 'slearning/$1/$2';

/*
 * 59 - Тайлан
 */
$route['sreport/(:any)'] = 'sreport/$1';
$route['sreport/(:any)/(:any)'] = 'sreport/$1/$2';

/*
 * 60 - Хүний нөөц
 */
$route['shrPeople/(:any)'] = 'shrPeople/$1';
$route['shrPeople/(:any)/(:any)'] = 'shrPeople/$1/$2';


/*
 * 61 - Албан тушаалын бүртгэл
 */
$route['shrPeoplePosition/(:any)'] = 'shrPeoplePosition/$1';
$route['shrPeoplePosition/(:any)/(:any)'] = 'shrPeoplePosition/$1/$2';


/*
 * 62 - Цолны бүртгэл
 */
$route['shrPeopleRank/(:any)'] = 'shrPeopleRank/$1';
$route['shrPeopleRank/(:any)/(:any)'] = 'shrPeopleRank/$1/$2';


/*
 * 63 - Хүний нөөцийн бүртгэлд зөвшөөрөгдсөн модуль
 */
$route['shrPeopleAcceptModule/(:any)'] = 'shrPeopleAcceptModule/$1';
$route['shrPeopleAcceptModule/(:any)/(:any)'] = 'shrPeopleAcceptModule/$1/$2';


/*
 * 64 - Боловсролын зэргийн лавлах
 */
$route['shrPeopleEducationRankMasterData/(:any)'] = 'shrPeopleEducationRankMasterData/$1';
$route['shrPeopleEducationRankMasterData/(:any)/(:any)'] = 'shrPeopleEducationRankMasterData/$1/$2';

/*
 * 65 - Албан тушаалын зэрэг, дэв
 */
$route['shrPeoplePositionRank/(:any)'] = 'shrPeoplePositionRank/$1';
$route['shrPeoplePositionRank/(:any)/(:any)'] = 'shrPeoplePositionRank/$1/$2';

/*
 * 66 - Албан тушаалын зэрэг, дэв
 */
$route['shrPeopleCourse/(:any)'] = 'shrPeopleCourse/$1';
$route['shrPeopleCourse/(:any)/(:any)'] = 'shrPeopleCourse/$1/$2';

/*
 * 67 - Эрдмийн цол
 */
$route['shrPeopleEducationRank/(:any)'] = 'shrPeopleEducationRank/$1';
$route['shrPeopleEducationRank/(:any)/(:any)'] = 'shrPeopleEducationRank/$1/$2';


/*
 * 68 - Шагнагдсан байдал
 */
$route['shrPeopleAward/(:any)'] = 'shrPeopleAward/$1';
$route['shrPeopleAward/(:any)/(:any)'] = 'shrPeopleAward/$1/$2';


/*
 * 69 - Газар, хэлтсийн бүртгэл
 */
$route['shrPeopleDepartment/(:any)'] = 'shrPeopleDepartment/$1';
$route['shrPeopleDepartment/(:any)/(:any)'] = 'shrPeopleDepartment/$1/$2';


/*
 * 70 - Хэргийн төрөл
 */
$route['snifsCrimeType/(:any)'] = 'snifsCrimeType/$1';
$route['snifsCrimeType/(:any)/(:any)'] = 'snifsCrimeType/$1/$2';


/*
 * 71 - Шинжилгээний хамтарсан, дагнасан төрөл
 */
$route['snifsResearchType/(:any)'] = 'snifsResearchType/$1';
$route['snifsResearchType/(:any)/(:any)'] = 'snifsResearchType/$1/$2';


/*
 * 72 - Хэрэг бүртгэх үндэслэл
 */
$route['snifsMotive/(:any)'] = 'snifsMotive/$1';
$route['snifsMotive/(:any)/(:any)'] = 'snifsMotive/$1/$2';


/*
 * 73 - Шинжилгээ хаах үндэслэл
 */
$route['snifsSolution/(:any)'] = 'snifsSolution/$1';
$route['snifsSolution/(:any)/(:any)'] = 'snifsSolution/$1/$2';


/*
 * 74 - Шинжилгээг хаасан байдал
 */
$route['snifsCloseType/(:any)'] = 'snifsCloseType/$1';
$route['snifsCloseType/(:any)/(:any)'] = 'snifsCloseType/$1/$2';


/*
 * 75 - Шинжээчид тавьсан асуултын бүртгэл
 */
$route['snifsQuestion/(:any)'] = 'snifsQuestion/$1';
$route['snifsQuestion/(:any)/(:any)'] = 'snifsQuestion/$1/$2';


/*
 * 76 - Бүртгэл хаана
 */
$route['snifsWhere/(:any)'] = 'snifsWhere/$1';
$route['snifsWhere/(:any)/(:any)'] = 'snifsWhere/$1/$2';


/*
 * 77 - Зар мэдээ, хүний нөөц
 */
$route['shrAds/(:any)'] = 'shrAds/$1';
$route['shrAds/(:any)/(:any)'] = 'shrAds/$1/$2';



/*
 * 78 - Хэргийн зураг илгээх
 */
$route['snifsSendPhoto/(:any)'] = 'snifsSendPhoto/$1';
$route['snifsSendPhoto/(:any)/(:any)'] = 'snifsSendPhoto/$1/$2';


/*
 * 79 - Хэргийн зураг илгээх
 */
$route['schart/(:any)'] = 'schart/$1';
$route['schart/(:any)/(:any)'] = 'schart/$1/$2';

/*
 * 80 - Гэмтлийн зэрэг
 */
$route['snifsInjury/(:any)'] = 'snifsInjury/$1';
$route['snifsInjury/(:any)/(:any)'] = 'snifsInjury/$1/$2';

/*
 * 81 - Илгээх бичиг
 */
$route['snifsSendDocument/(:any)'] = 'snifsSendDocument/$1';
$route['snifsSendDocument/(:any)/(:any)'] = 'snifsSendDocument/$1/$2';


/*
 * 82 - Илгээх бичиг
 */
$route['snifsReportGeneral/(:any)'] = 'snifsReportGeneral/$1';
$route['snifsReportGeneral/(:any)/(:any)'] = 'snifsReportGeneral/$1/$2';


/*
 * 83 - Зар мэдээлэл үзэх
 */
$route['shrAdsViews/(:any)'] = 'shrAdsViews/$1';
$route['shrAdsViews/(:any)/(:any)'] = 'shrAdsViews/$1/$2';


/*
 * 84 - Хүний нөөцийн мэдээлэлд тулгуурлаж утасны жагсаалтыг харуулж байгаа
 */
$route['shrContact/(:any)'] = 'shrContact/$1';
$route['shrContact/(:any)/(:any)'] = 'shrContact/$1/$2';


/*
 * 85 - Хэргийн газрын үзлэг ангилал
 */
$route['snifsSceneType/(:any)'] = 'snifsSceneType/$1';
$route['snifsSceneType/(:any)/(:any)'] = 'snifsSceneType/$1/$2';


/*
 * 86 - Хэргийн газрын үзлэг ангилал
 */
$route['snifsSceneFingerType/(:any)'] = 'snifsSceneFingerType/$1';
$route['snifsSceneFingerType/(:any)/(:any)'] = 'snifsSceneFingerType/$1/$2';


/*
 * 87 - Системийн төлөв
 */
$route['sstatus/(:any)'] = 'sstatus/$1';
$route['sstatus/(:any)/(:any)'] = 'sstatus/$1/$2';

/*
 * 88 - Мэдлэгийн сан
 */
$route['sknowledgebase/(:any)'] = 'sknowledgebase/$1';
$route['sknowledgebase/(:any)/(:any)'] = 'sknowledgebase/$1/$2';

/*
 * 89 - Лог систем
 */
$route['sknowledgebase/(:any)'] = 'sknowledgebase/$1';
$route['sknowledgebase/(:any)/(:any)'] = 'sknowledgebase/$1/$2';


/*
 * 90 - Албан бичгийн төрөл
 */
$route['smasterDocType/(:any)'] = 'smasterDocType/$1';
$route['smasterDocType/(:any)/(:any)'] = 'smasterDocType/$1/$2';

/*
 * 91 - Албан бичгийн мастер дата
 */
$route['sdoc/(:any)'] = 'sdoc/$1';
$route['sdoc/(:any)/(:any)'] = 'sdoc/$1/$2';



$route['snifsSideMenu/(:any)'] = 'snifsSideMenu/$1';
$route['snifsSideMenu/(:any)'] = 'snifsSideMenu/$1';

$route['spage/(:any)'] = 'spage/$1';
$route['sdate/(:any)'] = 'sdate/$1';

/*
 * 00 - Өмнөх хэрэг
 */
$route['snifsPreCrime/(:any)'] = 'snifsPreCrime/$1';
$route['snifsPreCrime/(:any)/(:any)'] = 'snifsPreCrime/$1/$2';



/*
 * 00 - Эксперт
 */
$route['snifsExpert/(:any)'] = 'snifsExpert/$1';
$route['snifsExpert/(:any)/(:any)'] = 'snifsExpert/$1/$2';


/**
 * Any
 * **/
$route['shrPeopleRelation/(:any)'] = 'shrPeopleRelation/$1';
$route['shrPeopleRelation/(:any)/(:any)'] = 'shrPeopleRelation/$1/$2';

$route['snifsKeywords/(:any)'] = 'snifsKeywords/$1';
$route['snifsKeywords/(:any)/(:any)'] = 'snifsKeywords/$1/$2';


$route['sstudentfinance/(:any)'] = 'sstudentfinance/$1';
$route['sstudentfinance/(:any)/(:any)'] = 'sstudentfinance/$1/$2';

$route['sstudentattendance/(:any)'] = 'sstudentattendance/$1';
$route['sstudentattendance/(:any)/(:any)'] = 'sstudentattendance/$1/$2';

$route['sstudent/(:any)'] = 'sstudent/$1';
$route['sstudent/(:any)/(:any)'] = 'sstudent/$1/$2';

$route['sscene/(:any)'] = 'sscene/$1';
$route['sscene/(:any)/(:any)'] = 'sscene/$1/$2';


$route['snifsSearch'] = 'snifsSearch/index';
$route['snifsSearch/(:any)'] = 'snifsSearch/$1';
$route['snifsSearch/(:any)/(:any)'] = 'snifsSearch/$1/$2';



$route['smeeting/(:any)'] = 'smeeting/$1';
$route['smeeting/(:any)/(:any)'] = 'smeeting/$1/$2';

$route['smedia/(:any)'] = 'smedia/$1';
$route['smedia/(:any)/(:any)'] = 'smedia/$1/$2';



$route['slaw/(:any)'] = 'slaw/$1';
$route['slaw/(:any)/(:any)'] = 'slaw/$1/$2';



$route['seducation/(:any)'] = 'seducation/$1';
$route['seducation/(:any)/(:any)'] = 'seducation/$1/$2';




$route['sdepartment/(:any)'] = 'sdepartment/$1';
$route['sdepartment/(:any)/(:any)'] = 'sdepartment/$1/$2';

$route['sdegree/(:any)'] = 'sdegree/$1';
$route['sdegree/(:any)/(:any)'] = 'sdegree/$1/$2';









$route['sclass/(:any)'] = 'sclass/$1';
$route['sclass/(:any)/(:any)'] = 'sclass/$1/$2';

$route['scareer/(:any)'] = 'scareer/$1';
$route['scareer/(:any)/(:any)'] = 'scareer/$1/$2';





$route['scategory/(:any)'] = 'scategory/$1';
$route['scategory/(:any)/(:any)'] = 'scategory/$1/$2';

$route['sservice/(:any)'] = 'sservice/$1';
$route['sservice/(:any)/(:any)'] = 'sservice/$1/$2';

$route['sorgdirectory/(:any)'] = 'sorgdirectory/$1';
$route['sorgdirectory/(:any)/(:any)'] = 'sorgdirectory/$1/$2';


$route['spoll/(:any)'] = 'spoll/$1';
$route['spoll/(:any)/(:any)'] = 'spoll/$1/$2';



$route['language/(mn|en)'] = 'tlanguage/changeLang/$1';

$route['tmenu/(:any)'] = 'tmenu/$1';
$route['tmenu/(:any)/(:any)'] = 'tmenu/$1/$2';

$route['tnews/(:any)'] = 'tnews/$1';
$route['tnews/(:any)/(:any)'] = 'tnews/$1/$2';

$route['ttour/(:any)'] = 'ttour/$1';
$route['ttour/(:any)/(:any)'] = 'ttour/$1/$2';

$route['tcontact/(:any)'] = 'tcontact/$1';
$route['tcontact/(:any)/(:any)'] = 'tcontact/$1/$2';

$route['tcomment/(:any)'] = 'tcomment/$1';
$route['tcomment/(:any)/(:any)'] = 'tcomment/$1/$2';

$route['currencyRate/(:any)'] = 'troute/_myControllerCurrencyRate';
$route['tsearch/(:any)'] = 'tsearch/search';
$route['tfeedback/(:any)'] = 'tfeedback/form';
$route['download/(:any)'] = 'tnews/downloadFile';
$route['home'] = 'troute/myControllerHome2';
$route['travel'] = 'troute/myControllerTravelHome';

$route['home'] = 'troute/myControllerHome2';
$route['travel'] = 'troute/myControllerTravelHome';

$route['(mn|en)/home'] = 'troute/myControllerHome2';
$route['(mn|en)/travel'] = 'troute/myControllerTravelHome';

$route['tpoll/pollFormItem'] = 'tpoll/pollFormItem';
$route['tpoll/vote'] = 'tpoll/vote';

$route['(:any)'] = 'troute/manage';
$route['en/(:any)'] = 'troute/manage';

$route['(:any)/(:any)'] = 'troute/manage';
$route['en/(:any)/(:any)'] = 'troute/manage';
