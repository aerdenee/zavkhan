var _URL = window.location.pathname.split("/");
var _MODULE_MENU_ID = _URL['3'];
var _DATE = new Date();

var _OUR_PERMISSION_DATA = _YOUR_PERMISSION_DATA = _REPORT_PERMISSION_DATA = _CUSTOM_PERMISSION = [];
var _IS_EXPORT = false;

var UPLOADS_CONTENT_PATH = '/upload/image/';
var UPLOADS_MEDIA_PATH = '/upload/media/';
var UPLOADS_USER_PATH = '/upload/user/';
var UPLOADS_URGUDUL_PATH = '/upload/urgudul/';
var UPLOADS_DOCUMENT_PATH = '/upload/document/';
var UPLOADS_TEMP_PATH = '/upload/temp/';
var UPLOADS_HR_PATH = '/upload/hr/';
var UPLOADS_NIFS_SEND_PHOTO_PATH = '/upload/nifssendphoto/';

var CROP_SMALL = 's_';
var SMALL_WIDTH = 300;
var SMALL_HEIGHT = 180;

var CROP_MEDIUM = 'm_';
var MEDIUM_WIDTH = 630;
var MEDIUM_HEIGHT = 380;

var CROP_LARGE = 'l_';
var LARGE_WIDTH = 944;
var LARGE_HEIGHT = 570;

var CROP_BIG = 'b_';
var BIG_WIDTH = 1024;
var BIG_HEIGHT = 768;

var USER_WIDTH = 500;
var USER_HEIGHT = 500;

var _html = '';
var _dateFormat = "yy-mm-dd";
var _uIdCurrent = 0;

var _dialogAlertTitle = 'Сануулга';
var _dialogAlertWidth = 330;
var _dialogAlertBtnYes = 'Тийм';
var _dialogAlertBtnNo = 'Үгүй';
var _dialogAlertBtnClose = 'Хаах';
var _dialogAlertDeleteMessage = '<div class="_alert"><i class="icon-question3 _icon _green"></i><div class="_text">Та энэ мэдээллийг устгахдаа итгэлтэй байна уу?</div></div>';
var _dialogAlertNoChoosePhotoMessage = '<div class="_alert"><i class="icon-question3 _icon _green"></i><div class="_text">Та зураг сонгоогүй байна уу?</div></div>';
var _dialogAlertDialogId = '#dialog-alert';
var _dialogAlertNoSelectedRowMessage = '<div class="_alert"><i class="icon-alert _icon"></i><div class="_text">Та бичлэг сонгоогүй байна</div></div>';
var _dialogAlertNoSelectExpertMessage = '<div class="_alert"><i class="icon-alert _icon"></i><div class="_text">Та шинжээч сонгоогүй байна</div></div>';
var _dialogAlertNoSelectDepartmentMessage = '<div class="_alert"><i class="icon-alert _icon"></i><div class="_text">Та салбар хэлтэс, илгээгчийг сонгоогүй байна</div></div>';

var _jqueryBlockUiIcon = '<span class="text-semibold"><i class="icon-spinner4 spinner position-left"></i></span>';
var _jqueryBlockUiMessage = '<span class="text-semibold"><i class="icon-spinner4 spinner position-left"></i>&nbsp; Боловсруулж байна...</span>';
var _jqueryBlockUiOverlayCSS = {backgroundColor: '#ababab', opacity: 0.9, cursor: 'wait'};
var _jqueryBlockUiMessageCSS = {border: 0, padding: 15, backgroundColor: '#283133', color: '#fff', width: 200, height: 40, lineHeight: 1, '-webkit-border-radius': '2px', '-moz-border-radius': '2px', opacity: 0.95, left: '45%'};


var _rootContainerId = '#root-container';

/*
 * Reaction module id нь тогтмол хувьсаж байна. Тухайн нэг модуль орох үедээ тодорхой утгатай болно.
 */
var _reactionModId = 0;
var _reactionWindowId = "#window-category";
var _reactionModRootPath = 'sreaction/';
var _reactionFormMainId = '#form-category';
var _reactionDialogId = '#dialog-category';


/*
 * Category module id нь тогтмол хувьсаж байна. Тухайн нэг модуль орох үедээ тодорхой утгатай болно.
 */
var _categoryModId = 0;
var _categoryWindowId = "#window-category";
var _categoryModRootPath = 'scategory/';
var _categoryFormMainId = '#form-category';
var _categoryDialogId = '#dialog-category';


/*
 * 0 - Сэтгэгдэл
 */
var _commentWindowId = "#window-comment";
var _commentModRootPath = 'scomment/';
var _commentFormMainId = '#form-comment';
var _commentDialogId = '#dialog-comment';

/*
 * 0 - Зураг
 */
var _imageModRootPath = 'simage/';
var _imageDialogId = '#dialog-image';

/*
 * 0 - Файл
 */
var _fileModRootPath = 'sfile/';
var _fileDialogId = '#dialog-file';


/*
 * 0 - Report general
 */
var _reportGeneralModId = 0;
var _reportGeneralWindowId = "#window-report-general";
var _reportGeneralInitWindowId = '#window-report-general-init';
var _reportGeneralModRootPath = 'sreportGeneral/';
var _reportGeneralFormMainId = '#form-report-general';
var _reportGeneralDialogId = '#dialog-report-general';

/*
 * 
 * 0 - Profile
 */
var _profileModId = 0;
var _profileWindowId = "#window-profile";
var _profileModRootPath = 'sprofile/';
var _profileFormMainId = '#form-profile';
var _profileDialogId = '#dialog-profile';
var _profileFlashMessage = '#profile-flash-message';
/*
 * 1 - Меню
 */
var _menuModId = 1;
var _menuWindowId = "#window-menu";
var _menuModRootPath = 'smenu/';
var _menuFormMainId = '#form-menu';
var _menuDialogId = '#dialog-menu';

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
 * 57 - Интерактив хичээл
 */
var _contentModId = 2;
var _contentWindowId = "#window-content";
var _contentModRootPath = 'scontent/';
var _contentFormMainId = '#form-content';
var _contentReportFormMainId = '#form-content-report';
var _contentDialogId = '#dialog-content';

var _contentMediaModId = 0;
var _contentMediaWindowId = "#window-content-media";
var _contentMediaModRootPath = 'scontentMedia/';
var _contentMediaFormMainId = '#form-content-media';
var _contentMediaReportFormMainId = '#form-content-media-report';
var _contentMediaDialogId = '#dialog-content-media';

var _contentCommentModId = 0;
var _contentCommentWindowId = "#window-content-comment";
var _contentCommentModRootPath = 'scontentComment/';
var _contentCommentFormMainId = '#form-content-comment';
var _contentCommentReportFormMainId = '#form-content-comment-report';
var _contentCommentDialogId = '#dialog-content-comment';

var _contentGmapModId = 0;
var _contentGmapWindowId = "#window-content-gmap";
var _contentGmapModRootPath = 'scontentGmap/';
var _contentGmapFormMainId = '#form-content-gmap';
var _contentGmapReportFormMainId = '#form-content-gmap-report';
var _contentGmapDialogId = '#dialog-content-gmap';

/*
 * 3 - Арга хэмжээ
 */
var _eventModId = 3;
var _eventWindowId = "#window-event";
var _eventModRootPath = 'sevent/';
var _eventFormMainId = '#form-event';
var _eventReportFormMainId = '#form-event-report';
var _eventDialogId = '#dialog-event';

/*
 * 4 - Баннер
 * 5 - Слайдер
 */
var _mediaModId = 4;
var _mediaWindowId = "#window-media";
var _mediaModRootPath = 'smedia/';
var _mediaFormMainId = '#form-media';
var _mediaReportFormMainId = '#form-media-report';
var _mediaDialogId = '#dialog-media';

/*
 * 8 - Сэтгэгдэл
 */
var _commentModId = 8;
var _commentWindowId = "#window-comment";
var _commentModRootPath = 'scomment/';
var _commentFormMainId = '#form-comment';
var _commentReportFormMainId = '#form-comment-report';
var _commentDialogId = '#dialog-comment';

/*
 * 12 - Холбоо барих
 */
var _contactModId = 12;
var _contactWindowId = "#window-contact";
var _contactModRootPath = 'scontact/';
var _contactFormMainId = '#form-contact';
var _contactReportFormMainId = '#form-contact-report';
var _contactDialogId = '#dialog-contact';

/*
 * 13 - Feedback
 */
var _feedbackModId = 13;
var _feedbackWindowId = "#window-feedback";
var _feedbackModRootPath = 'sfeedback/';
var _feedbackFormMainId = '#form-feedback';
var _feedbackReportFormMainId = '#form-feedback-report';
var _feedbackDialogId = '#dialog-feedback';

/*
 * 14 - Layout
 */
var _layoutModId = 14;
var _layoutWindowId = "#window-layout";
var _layoutModRootPath = 'slayout/';
var _layoutFormMainId = '#form-layout';
var _layoutReportFormMainId = '#form-layout-report';
var _layoutDialogId = '#dialog-layout';

/*
 * 15 - Зар
 */
var _advertisementModId = 15;
var _advertisementWindowId = "#window-advertisement";
var _advertisementModRootPath = 'sadvertisement/';
var _advertisementFormMainId = '#form-advertisement';
var _advertisementReportFormMainId = '#form-advertisement-report';
var _advertisementDialogId = '#dialog-advertisement';

/*
 * 16 - Өргөдөл
 */
var _urgudulModId = 16;
var _urgudulWindowId = "#window-urgudul";
var _urgudulModRootPath = 'surgudul/';
var _urgudulFormMainId = '#form-urgudul';
var _urgudulReportFormMainId = '#form-urgudul-report';
var _urgudulDialogId = '#dialog-urgudul';

/*
 * 17 - Ирсэн бичиг
 */
var _docInModId = 17;
var _docInWindowId = "#window-doc-in";
var _docInModRootPath = 'sdocIn/';
var _docInFormMainId = '#form-doc-in';
var _docInReportFormMainId = '#form-doc-in-report';
var _docInDialogId = '#dialog-doc-in';

var _docOutModId = 18;
var _docOutWindowId = "#window-doc-out";
var _docOutModRootPath = 'sdocOut/';
var _docOutFormMainId = '#form-doc-out';
var _docOutReportFormMainId = '#form-doc-out-report';
var _docOutDialogId = '#dialog-doc-out';
var _docDetailWindowId = "#window-doc-detail";
var _docDetailFormMainId = '#form-doc-detail';
var _docDetailDialogId = '#dialog-doc-detail';

var _docFileModId = 17;
var _docFileWindowId = "#window-doc-file";
var _docFileModRootPath = 'sdocFile/';
var _docFileFormMainId = '#form-doc-file';
var _docFileReportFormMainId = '#form-doc-file-report';
var _docFileDialogId = '#dialog-doc-file';

var _docCloseModId = 17;
var _docCloseWindowId = "#window-doc-close";
var _docCloseModRootPath = 'sdocClose/';
var _docCloseFormMainId = '#form-doc-close';
var _docCloseReportFormMainId = '#form-doc-close-report';
var _docCloseDialogId = '#dialog-doc-close';

var _docTransferModId = 92;
var _docTransferWindowId = "#window-doc-transfer";
var _docTransferModRootPath = 'sdocTransfer/';
var _docTransferFormMainId = '#form-doc-transfer';
var _docTransferReportFormMainId = '#form-doc-transfer-report';
var _docTransferDialogId = '#dialog-doc-transfer';

/*
 * 19 - Аялал
 */
var _tourModId = 19;
var _tourWindowId = "#window-tour";
var _tourModRootPath = 'stour/';
var _tourFormMainId = '#form-tour';
var _tourReportFormMainId = '#form-tour-report';
var _tourDialogId = '#dialog-tour';


var _tourItineraryModId = 19;
var _tourItineraryWindowId = "#window-tour-itinerary";
var _tourItineraryModRootPath = 'stourItinerary/';
var _tourItineraryFormMainId = '#form-tour-itinerary';
var _tourItineraryReportFormMainId = '#form-tour-itinerary-report';
var _tourItineraryDialogId = '#dialog-tour-itinerary';


var _tourCalendarModId = 19;
var _tourCalendarWindowId = "#window-tour-calendar";
var _tourCalendarModRootPath = 'stourCalendar/';
var _tourCalendarFormMainId = '#form-tour-calendar';
var _tourCalendarReportFormMainId = '#form-tour-calendar-report';
var _tourCalendarDialogId = '#dialog-tour-calendar';


/*
 * 21 - Гэр, байшин сууцны бүртгэл
 */
var _accommodationModId = 21;
var _accommodationWindowId = "#window-accommodation";
var _accommodationModRootPath = 'saccommodation/';
var _accommodationFormMainId = '#form-accommodation';
var _accommodationReportFormMainId = '#form-accommodation-report';
var _accommodationDialogId = '#dialog-accommodation';

/*
 * 22 - Хэрэглэгч
 */
var _userModId = 22;
var _userWindowId = "#window-user";
var _userModRootPath = 'suser/';
var _userFormMainId = '#form-user';
var _userReportFormMainId = '#form-user-report';
var _userDialogId = '#dialog-user';

/*
 * 23 - Захиалга
 */
var _accommodationReservationModId = 23;
var _accommodationReservationWindowId = "#window-accommodation-reservation";
var _accommodationReservationModRootPath = 'saccommodationreservation/';
var _accommodationReservationFormMainId = '#form-accommodation-reservation';
var _accommodationReservationReportFormMainId = '#form-accommodation-reservation-report';
var _accommodationReservationDialogId = '#dialog-accommodation-reservation';

/*
 * 24 - Харилцагч байгууллага
 */
var _partnerModId = 24;
var _partnerWindowId = "#window-partner";
var _partnerModRootPath = 'spartner/';
var _partnerFormMainId = '#form-partner';
var _partnerReportFormMainId = '#form-partner-report';
var _partnerDialogId = '#dialog-partner';

/*
 * 25 - Хаягийн лавлах
 */
var _addressModId = 25;
var _addressWindowId = "#window-address";
var _addressModRootPath = 'saddress/';
var _addressFormMainId = '#form-address';
var _addressReportFormMainId = '#form-address-report';
var _addressDialogId = '#dialog-address';

/*
 * 26 - Боловсролын лавлах
 */
var _educationModId = 26;
var _educationWindowId = "#window-education";
var _educationModRootPath = 'seducation/';
var _educationFormMainId = '#form-education';
var _educationReportFormMainId = '#form-education-report';
var _educationDialogId = '#dialog-education';
/*
 * 27 - Ажил эрхлэлтийн лавлах
 */
var _careerModId = 27;
var _careerWindowId = "#window-career";
var _careerModRootPath = 'scareer/';
var _careerFormMainId = '#form-career';
var _careerReportFormMainId = '#form-career-report';
var _careerDialogId = '#dialog-career';

/*
 * 28 - Зэрэг дэвийн лавлах
 */
var _degreeModId = 28;
var _degreeWindowId = "#window-degree";
var _degreeModRootPath = 'sdegree/';
var _degreeFormMainId = '#form-degree';
var _degreeReportFormMainId = '#form-degree-report';
var _degreeDialogId = '#dialog-degree';

/*
 * 29 - Системд хандах эрхийн тохиргоо
 */
var _permissionModId = 29;
var _permissionWindowId = "#window-permission";
var _permissionModRootPath = 'spermission/';
var _permissionFormMainId = '#form-permission';
var _permissionReportFormMainId = '#form-permission-report';
var _permissionDialogId = '#dialog-permission';

/*
 * 32 - Ажил, уулзалт
 */
var _meetingModId = 32;
var _meetingWindowId = "#window-meeting";
var _meetingModRootPath = 'smeeting/';
var _meetingFormMainId = '#form-meeting';
var _meetingReportFormMainId = '#form-meeting-report';
var _meetingDialogId = '#dialog-meeting';

/*
 * 33 - Криминалистикийн шинжилгээний бүртгэл
 */
var _nifsCrimeModId = 33;
var _nifsCrimeWindowId = "#window-nifs-crime";
var _nifsCrimeModRootPath = 'snifsCrime/';
var _nifsCrimeFormMainId = '#form-nifs-crime';
var _nifsCrimeReportFormMainId = '#form-nifs-crime-report';
var _nifsCrimeDialogId = '#dialog-nifs-crime';
var _nifsCrimePositionId = '10,5,6,7';

/*
 * 34 - Хэргийн газрын үзлэг
 */
var _nifsSceneModId = 34;
var _nifsSceneWindowId = "#window-nifs-scene";
var _nifsSceneModRootPath = 'snifsScene/';
var _nifsSceneFormMainId = '#form-nifs-scene';
var _nifsSceneReportFormMainId = '#form-nifs-scene-report';
var _nifsSceneDialogId = '#dialog-nifs-scene';

/*
 * 35 - Оюутны ирц
 */
var _eduStudentAttendanceModId = 35;
var _eduStudentAttendanceWindowId = "#window-edu-student-attendance";
var _eduStudentAttendanceModRootPath = 'seduStudentAttendance/';
var _eduStudentAttendanceFormMainId = '#form-edu-student-attendance';
var _eduStudentAttendanceReportFormMainId = '#form-edu-student-attendance-report';
var _eduStudentAttendanceDialogId = '#dialog-edu-student-attendance';

/*
 * 37 - Оюутны анги
 */
var _eduClassModId = 37;
var _eduClassWindowId = "#window-edu-class";
var _eduClassModRootPath = 'seduClass/';
var _eduClassFormMainId = '#form-edu-class';
var _eduClassReportFormMainId = '#form-edu-class-report';
var _eduClassDialogId = '#dialog-edu-class';

/*
 * 38 - Санхүү /оюутан/
 */
var _eduStudentFinanceModId = 38;
var _eduStudentFinanceWindowId = "#window-edu-student-finance";
var _eduStudentFinanceModRootPath = 'seduStudentFinance/';
var _eduStudentFinanceFormMainId = '#form-edu-student-finance';
var _eduStudentFinanceReportFormMainId = '#form-edu-student-finance-report';
var _eduStudentFinanceDialogId = '#dialog-edu-student-finance';

/*
 * 39 - Оюутан
 */
var _eduStudentModId = 39;
var _eduStudentWindowId = "#window-edu-student";
var _eduStudentModRootPath = 'seduStudent/';
var _eduStudentFormMainId = '#form-edu-student';
var _eduStudentReportFormMainId = '#form-edu-student-report';
var _eduStudentDialogId = '#dialog-edu-student';

/*
 * 40 - Хууль, эрх зүй
 */
var _lawModId = 40;
var _lawWindowId = "#window-law";
var _lawModRootPath = 'slaw/';
var _lawFormMainId = '#form-law';
var _lawReportFormMainId = '#form-law-report';
var _lawDialogId = '#dialog-law';

/*
 * 45 - Байгууллагын лавлах
 */
var _organizationDirectoryModId = 45;
var _organizationDirectoryWindowId = "#window-organization-directory";
var _organizationDirectoryModRootPath = 'sorganizationDirectory/';
var _organizationDirectoryFormMainId = '#form-organization-directory';
var _organizationDirectoryReportFormMainId = '#form-organization-directory-report';
var _organizationDirectoryDialogId = '#dialog-organization-directory';
/*
 * 46 - Санал асуулга
 */
var _pollModId = 46;
var _pollWindowId = "#window-poll";
var _pollModRootPath = 'spoll/';
var _pollFormMainId = '#form-poll';
var _pollReportFormMainId = '#form-poll-report';
var _pollDialogId = '#dialog-poll';

/*
 * 47 - Газрын зураг
 */
var _mapModId = 47;
var _mapWindowId = "#window-map";
var _mapModRootPath = 'smap/';
var _mapFormId = '#form-map';
var _mapReportFormId = '#form-map-report';
var _mapDialogId = '#dialog-map';
var _mapGoogle = '';
/*
 * 48 - Жилийн хаалт
 */
var _nifsCloseYearModId = 48;
var _nifsCloseYearWindowId = "#window-nifs-close-year";
var _nifsCloseYearModRootPath = 'snifsCloseYear/';
var _nifsCloseYearFormMainId = '#form-nifs-close-year';
var _nifsCloseYearReportFormMainId = '#form-nifs-close-year-report';
var _nifsCloseYearDialogId = '#dialog-nifs-close-year';
/*
 * 49 - Хэргийн зураг
 */
var _nifsCrimePhotoModId = 49;
var _nifsCrimePhotoWindowId = "#window-nifs-crime-photo";
var _nifsCrimePhotoModRootPath = 'snifsCrimePhoto/';
var _nifsCrimePhotoFormMainId = '#form-nifs-crime-photo';
var _nifsCrimePhotoReportFormMainId = '#form-nifs-crime-photo-report';
var _nifsCrimePhotoDialogId = '#dialog-nifs-crime-photo';

/*
 * 50 - Шинжилгээний бүртгэл
 */
var _nifsFileFolderModId = 50;
var _nifsFileFolderWindowId = "#window-nifs-file-folder";
var _nifsFileFolderModRootPath = 'snifsFileFolder/';
var _nifsFileFolderFormMainId = '#form-nifs-file-folder';
var _nifsFileFolderReportFormMainId = '#form-nifs-file-folder-report';
var _nifsFileFolderDialogId = '#dialog-nifs-file-folder';

/*
 * 51 - Үзлэгийн бүртгэл
 */
var _nifsDoctorViewModId = 51;
var _nifsDoctorViewWindowId = "#window-nifs-doctor-view";
var _nifsDoctorViewModRootPath = 'snifsDoctorView/';
var _nifsDoctorViewFormMainId = '#form-nifs-doctor-view';
var _nifsDoctorViewReportFormMainId = '#form-nifs-doctor-view-report';
var _nifsDoctorViewDialogId = '#dialog-nifs-doctor-view';

/*
 * 52 - Задлан шинжилгээний бүртгэл
 */
var _nifsAnatomyModId = 52;
var _nifsAnatomyWindowId = "#window-nifs-anatomy";
var _nifsAnatomyModRootPath = 'snifsAnatomy/';
var _nifsAnatomyFormMainId = '#form-nifs-anatomy';
var _nifsAnatomyReportFormMainId = '#form-nifs-anatomy-report';
var _nifsAnatomyDialogId = '#dialog-nifs-anatomy';

/*
 * 53 - File folder solution
 */
var _nifsFileFolderSolutionModId = 53;
var _nifsFileFolderSolutionWindowId = "#window-nifs-file-folder-solution";
var _nifsFileFolderSolutionModRootPath = 'snifsFileFolderSolution/';
var _nifsFileFolderSolutionFormMainId = '#form-nifs-file-folder-solution';
var _nifsFileFolderSolutionReportFormMainId = '#form-nifs-file-folder-solution-report';
var _nifsFileFolderSolutionDialogId = '#dialog-nifs-file-folder-solution';

/*
 * 54 - Module menu
 */
var _moduleMenuModId = 54;
var _moduleMenuWindowId = "#window-module-menu";
var _moduleMenuModRootPath = 'smoduleMenu/';
var _moduleMenuFormMainId = '#form-module-menu';
var _moduleMenuReportFormMainId = '#form-module-menu-report';
var _moduleMenuDialogId = '#dialog-module-menu';

/*
 * 55 - Тусгай шинжилгээ
 */
var _nifsExtraModId = 55;
var _nifsExtraWindowId = "#window-nifs-extra";
var _nifsExtraModRootPath = 'snifsExtra/';
var _nifsExtraFormMainId = '#form-nifs-extra';
var _nifsExtraReportFormMainId = '#form-nifs-extra-report';
var _nifsExtraDialogId = '#dialog-nifs-extra';
var _hrPeopleExtraDepartmentId = 5;
var _hrPeopleExtraAcceptModuleId = 2;

/*
 * 56 - Эдийн засгийн шинжилгээ
 */
var _nifsEconomyModId = 56;
var _nifsEconomyWindowId = "#window-nifs-economy";
var _nifsEconomyModRootPath = 'snifsEconomy/';
var _nifsEconomyFormMainId = '#form-nifs-economy';
var _nifsEconomyReportFormMainId = '#form-nifs-economy-report';
var _nifsEconomyDialogId = '#dialog-nifs-economy';

/*
 * 58 - Интерактив хичээл үзэх
 */
var _learningModId = 57;    /*modId = 57 дээр нэмсэн агуулгыг 58 дээр харуулахын тулд 57 болгосон*/
var _learningWindowId = "#window-learning";
var _learningModRootPath = 'slearning/';
var _learningFormMainId = '#form-learning';
var _learningReportFormMainId = '#form-learning-report';
var _learningDialogId = '#dialog-learning';

/*
 * 59 - Тайлан
 */
var _reportModId = 59;
var _reportWindowId = "#window-report";
var _reportModRootPath = 'sreport/';
var _reportFormMainId = '#form-report';
var _reportDialogId = '#dialog-report';

/*
 * 60 - Хүний нөөц
 */
var _hrPeopleModId = 60;
var _hrPeopleWindowId = "#window-hr-people";
var _hrPeopleModRootPath = 'shrPeople/';
var _hrPeopleFormMainId = '#form-hr-people';
var _hrPeopleDialogId = '#dialog-hr-people';

/*
 * 61 - Албан тушаалын бүртгэл
 */
var _hrPeoplePositionModId = 61;
var _hrPeoplePositionWindowId = "#window-hr-people-position";
var _hrPeoplePositionModRootPath = 'shrPeoplePosition/';
var _hrPeoplePositionFormMainId = '#form-hr-people-position';
var _hrPeoplePositionDialogId = '#dialog-hr-people-position';


/*
 * 62 - Цолны бүртгэл
 */
var _hrPeopleRankModId = 62;
var _hrPeopleRankWindowId = "#window-hr-people-rank";
var _hrPeopleRankModRootPath = 'shrPeopleRank/';
var _hrPeopleRankFormMainId = '#form-hr-people-rank';
var _hrPeopleRankDialogId = '#dialog-hr-people-rank';


/*
 * 63 - Хүний нөөцийн бүртгэлд зөвшөөрөгдсөн модуль
 */
var _hrPeopleAcceptModuleModId = 63;
var _hrPeopleAcceptModuleWindowId = "#window-hr-people-accept-module";
var _hrPeopleAcceptModuleModRootPath = 'shrPeopleAcceptModule/';
var _hrPeopleAcceptModuleFormMainId = '#form-hr-people-accept-module';
var _hrPeopleAcceptModuleDialogId = '#dialog-hr-people-accept-module';



/*
 * 64 - Боловсролын зэргийн лавлах
 */
var _hrPeopleEducationRankMasterDataModId = 64;
var _hrPeopleEducationRankMasterDataWindowId = "#window-hr-people-education-rank-master-data";
var _hrPeopleEducationRankMasterDataModRootPath = 'shrPeopleEducationRankMasterData/';
var _hrPeopleEducationRankMasterDataFormMainId = '#form-hr-people-education-rank-master-data';
var _hrPeopleEducationRankMasterDataDialogId = '#dialog-hr-people-education-rank-master-data';

/*
 * 65 - Албан тушаалын зэрэг дэв
 */
var _hrPeoplePositionRankModId = 65;
var _hrPeoplePositionRankWindowId = "#window-hr-people-position-rank";
var _hrPeoplePositionRankModRootPath = 'shrPeoplePositionRank/';
var _hrPeoplePositionRankFormMainId = '#form-hr-people-position-rank';
var _hrPeoplePositionRankDialogId = '#dialog-hr-people-position-rank';

/*
 * 66 - Мэргэшлийн бэлтгэл
 */
var _hrPeopleCourseModId = 66;
var _hrPeopleCourseWindowId = "#window-hr-people-course";
var _hrPeopleCourseModRootPath = 'shrPeopleCourse/';
var _hrPeopleCourseFormMainId = '#form-hr-people-course';
var _hrPeopleCourseDialogId = '#dialog-hr-people-course';

/*
 * 67 - Мэргэшлийн бэлтгэл
 */
var _hrPeopleEducationRankModId = 67;
var _hrPeopleEducationRankWindowId = "#window-hr-people-education-rank";
var _hrPeopleEducationRankModRootPath = 'shrPeopleEducationRank/';
var _hrPeopleEducationRankFormMainId = '#form-hr-people-education-rank';
var _hrPeopleEducationRankDialogId = '#dialog-hr-people-education-rank';

/*
 * 68 - Шагнагдсан байдал
 */
var _hrPeopleAwardModId = 68;
var _hrPeopleAwardWindowId = "#window-hr-people-award";
var _hrPeopleAwardModRootPath = 'shrPeopleAward/';
var _hrPeopleAwardFormMainId = '#form-hr-people-award';
var _hrPeopleAwardDialogId = '#dialog-hr-people-award';

/*
 * 69 - Газар, хэлтсийн бүртгэл
 */
var _hrPeopleDepartmentModId = 69;
var _hrPeopleDepartmentWindowId = "#window-hr-people-department";
var _hrPeopleDepartmentModRootPath = 'shrPeopleDepartment/';
var _hrPeopleDepartmentFormMainId = '#form-hr-people-department';
var _hrPeopleDepartmentDialogId = '#dialog-hr-people-department';


/*
 * 70 - Хэргийн төрөл
 */
var _nifsCrimeTypeModId = 70;
var _nifsCrimeTypeWindowId = "#window-nifs-crime-type";
var _nifsCrimeTypeModRootPath = 'snifsCrimeType/';
var _nifsCrimeTypeFormMainId = '#form-nifs-crime-type';
var _nifsCrimeTypeDialogId = '#dialog-nifs-crime-type';


/*
 * 71 - Шинжилгээний хамтарсан, дагнасан төрөл
 */
var _nifsResearchTypeModId = 71;
var _nifsResearchTypeWindowId = "#window-nifs-research-type";
var _nifsResearchTypeModRootPath = 'snifsResearchType/';
var _nifsResearchTypeFormMainId = '#form-nifs-research-type';
var _nifsResearchTypeDialogId = '#dialog-nifs-research-type';


/*
 * 72 - Хэрэг бүртгэх үндэслэл
 */
var _nifsMotiveModId = 72;
var _nifsMotiveWindowId = "#window-nifs-motive";
var _nifsMotiveModRootPath = 'snifsMotive/';
var _nifsMotiveFormMainId = '#form-nifs-motive';
var _nifsMotiveDialogId = '#dialog-nifs-motive';


/*
 * 73 - Шинжилгээг хаах үндэслэл
 */
var _nifsSolutionModId = 73;
var _nifsSolutionWindowId = "#window-nifs-solution";
var _nifsSolutionModRootPath = 'snifsSolution/';
var _nifsSolutionFormMainId = '#form-nifs-solution';
var _nifsSolutionDialogId = '#dialog-nifs-solution';


/*
 * 74 - Шинжилгээг хаасан байдал
 */
var _nifsCloseTypeModId = 74;
var _nifsCloseTypeWindowId = "#window-nifs-close-type";
var _nifsCloseTypeModRootPath = 'snifsCloseType/';
var _nifsCloseTypeFormMainId = '#form-nifs-close-type';
var _nifsCloseTypeDialogId = '#dialog-nifs-close-type';


/*
 * 75 - Шинжээчид тавьсан асуултын бүртгэл
 */
var _nifsQuestionModId = 75;
var _nifsQuestionWindowId = "#window-nifs-question";
var _nifsQuestionModRootPath = 'snifsQuestion/';
var _nifsQuestionFormMainId = '#form-nifs-question';
var _nifsQuestionDialogId = '#dialog-nifs-question';


/*
 * 76 - Бүртгэл хаана
 */
var _nifsWhereModId = 76;
var _nifsWhereWindowId = "#window-nifs-where";
var _nifsWhereModRootPath = 'snifsWhere/';
var _nifsWhereFormMainId = '#form-nifs-where';
var _nifsWhereDialogId = '#dialog-nifs-where';


/*
 * 77 - Зар мэдээ хүний нөөцийн бүртгэлээр дамжин түгээх
 */
var _hrAdsModId = 77;
var _hrAdsWindowId = "#window-hr-ads";
var _hrAdsModRootPath = 'shrAds/';
var _hrAdsFormMainId = '#form-hr-ads';
var _hrAdsDialogId = '#dialog-hr-ads';


/*
 * 78 - Зар мэдээ хүний нөөцийн бүртгэлээр дамжин түгээх
 */
var _nifsSendPhotoModId = 78;
var _nifsSendPhotoWindowId = "#window-nifs-send-photo";
var _nifsSendPhotoModRootPath = 'snifsSendPhoto/';
var _nifsSendPhotoFormMainId = '#form-nifs-send-photo';
var _nifsSendPhotoDialogId = '#dialog-nifs-send-photo';

/*
 * 79 - Бүх төрлийн график
 */
var _chartModId = 79;
var _chartWindowId = "#window-chart";
var _chartModRootPath = 'schart/';
var _chartFormMainId = '#form-chart';
var _chartDialogId = '#dialog-chart';

/*
 * 80 - Гэмтлийн зэрэг
 */
var _nifsInjuryModId = 80;
var _nifsInjuryWindowId = "#window-nifs-injury";
var _nifsInjuryModRootPath = 'snifsInjury/';
var _nifsInjuryFormMainId = '#form-nifs-injury';
var _nifsInjuryDialogId = '#dialog-nifs-injury';


/*
 * 81 - Илгээх бичиг
 */
var _nifsSendDocumentModId = 81;
var _nifsSendDocumentWindowId = "#window-nifs-send-document";
var _nifsSendDocumentModRootPath = 'snifsSendDocument/';
var _nifsSendDocumentFormMainId = '#form-nifs-send-document';
var _nifsSendDocumentDialogId = '#dialog-nifs-send-document';


/*
 * 82 - Report general
 */
var _nifsReportGeneralModId = 82;
var _nifsReportGeneralWindowId = "#window-nifs-report-general";
var _nifsReportGeneralModRootPath = 'snifsReportGeneral/';
var _nifsReportGeneralFormMainId = '#form-nifs-report-general';
var _nifsReportGeneralDialogId = '#dialog-nifs-report-general';


/*
 * 83 - Hr мэдээлэл үзэх
 */
var _hrAdsViewsModId = 83;    /*modId = 57 дээр нэмсэн агуулгыг 58 дээр харуулахын тулд 57 болгосон*/
var _hrAdsViewsWindowId = "#window-hr-ads-views";
var _hrAdsViewsModRootPath = 'shrAdsViews/';
var _hrAdsViewsFormMainId = '#form-hr-ads-views';
var _hrAdsViewsReportFormMainId = '#form-hr-ads-views-report';
var _hrAdsViewsDialogId = '#dialog-hr-ads-views';


/*
 * 84 - Хүний нөөцийн мэдээлэлд тулгуурлаж утансы жагсаалт харуулж байгаа
 */
var _hrContactModId = 84;
var _hrContactWindowId = "#window-hr-contact";
var _hrContactModRootPath = 'shrContact/';
var _hrContactFormMainId = '#form-hr-contact';
var _hrContactReportFormMainId = '#form-hr-contact-report';
var _hrContactDialogId = '#dialog-hr-contact';


/*
 * 85 - Хэргийн газрын үзлэг ангилал
 */
var _nifsSceneTypeModId = 85;
var _nifsSceneTypeWindowId = "#window-nifs-scene-type";
var _nifsSceneTypeModRootPath = 'snifsSceneType/';
var _nifsSceneTypeFormMainId = '#form-nifs-scene-type';
var _nifsSceneTypeReportFormMainId = '#form-nifs-scene-type-report';
var _nifsSceneTypeDialogId = '#dialog-nifs-scene-type';


/*
 * 86 - Хэргийн газрын үзлэг хээний лавлах
 */
var _nifsSceneFingerTypeModId = 86;
var _nifsSceneFingerTypeWindowId = "#window-nifs-scene-finger-type";
var _nifsSceneFingerTypeModRootPath = 'snifsSceneFingerType/';
var _nifsSceneFingerTypeFormMainId = '#form-nifs-scene-finger-type';
var _nifsSceneFingerTypeReportFormMainId = '#form-nifs-scene-finger-type-report';
var _nifsSceneFingerTypeDialogId = '#dialog-nifs-scene-finger-type';


/*
 * 87 - Системийн статус
 */
var _statusModId = 87;
var _statusWindowId = "#window-status";
var _statusModRootPath = 'sstatus/';
var _statusFormMainId = '#form-status';
var _statusDialogId = '#dialog-status';


/*
 * 88 - Мэдлэгийн сан
 */
var _knowledgebaseModId = 88;
var _knowledgebaseWindowId = "#window-knowledgebase";
var _knowledgebaseModRootPath = 'sknowledgebase/';
var _knowledgebaseFormMainId = '#form-knowledgebase';
var _knowledgebaseReportFormMainId = '#form-knowledgebase-report';
var _knowledgebaseDialogId = '#dialog-knowledgebase';


/*
 * 89 - Log system
 */
var _logModId = 89;
var _logWindowId = "#window-log";
var _logModRootPath = 'slog/';
var _logFormMainId = '#form-log';
var _logReportFormMainId = '#form-log-report';
var _logDialogId = '#dialog-log';



/*
 * 90 - Медиа төрөл
 */
var _masterMediaTypeModId = 0;
var _masterMediaTypeWindowId = "#window-master-media-type";
var _masterMediaTypeModRootPath = 'smasterMediaType/';
var _masterMediaTypeFormMainId = '#form-master-media-type';
var _masterMediaTypeReportFormMainId = '#form-master-media-type-report';
var _masterMediaTypeDialogId = '#dialog-master-media-type';


/*
 * 00 - Эксперт
 */
var _nifsExpertModId = 00;
var _nifsExpertWindowId = "#window-nifs-expert";
var _nifsExpertModRootPath = 'snifsExpert/';
var _nifsExpertFormMainId = '#form-nifs-expert';
var _nifsExpertReportFormMainId = '#form-nifs-expert-report';
var _nifsExpertDialogId = '#dialog-nifs-expert';

/*
 * 0 - Өмнөх хэрэг
 */
var _nifsPreCrimeModId = 00;
var _nifsPreCrimeWindowId = "#window-nifs-pre-crime";
var _nifsPreCrimeModRootPath = 'snifsPreCrime/';
var _nifsPreCrimeFormMainId = '#form-nifs-pre-crime';
var _nifsPreCrimeDialogId = '#dialog-nifs-pre-crime';

