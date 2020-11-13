<?php

class SnifsReportGeneral_model extends CI_Model {

    private static $list = array();

    function __construct() {

        parent::__construct();

        $this->load->model('Scategory_model', 'category');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Suser_model', 'user');
        $this->load->model('SnifsMotive_model', 'nifsMotive');
        $this->load->model('SnifsResearchType_model', 'nifsResearchType');
        $this->load->model('SnifsCrimeType_model', 'nifsCrimeType');
        $this->load->model('SreportMenu_model', 'sreportMenu');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('SnifsStatus_model', 'nifsStatus');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('SnifsSolution_model', 'nifsSolution');
        $this->load->model('Schart_model', 'chart');
        $this->load->model('SnifsCloseYear_model', 'nifsCloseYear');



        $this->modId = 33;
        $this->chartCatId = 399;

        $this->nifsCrimeTypeId = 353;
        $this->nifsQuestionCatId = 371;
        $this->nifsSolutionCatId = 359;
        $this->nifsCloseTypeCatId = 365;
        $this->nifsResearchTypeCatId = 380;
        $this->nifsMotiveCatId = 386;
        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->reportDefaultDayInterval = 7;
        $this->isActiveDepartment = 'is_active_nifs_file_folder';
        $this->nifsForensicMedicineDepartmentId = 0;
    }

    public function getReportGeneralData_model($param = array()) {

        $showIcon = $queryStringData = $htmlData = $allTableHtml = '';

        $sumTotalAllCrime = $sumTotalAllForensic = $sumTotalAllExtra = $sumTotalAllEconomy = $sumTotalAllSendDocument = $sumTotalAllRow = $sumTotalAllScene = 0;

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $queryParent = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title
            FROM `gaz_hr_people_department` AS HPD
            WHERE HPD.is_active = 1 AND HPD.id IN (8,18)');
        //3,4,5,6,
        if ($queryParent->num_rows() > 0) {

            $i = $sumTotalCrime0Dep = $sumTotalCrime1Dep = 0;
            foreach ($queryParent->result() as $keyParent => $rowParent) {

                ++$i;
                $j = $sumTotalCrime = $sumTotalForensic = $sumTotalExtra = $sumTotalEconomy = $sumTotalSendDocument = $sumRow = $sumTotalRow = $sumTotalScene = 0;

                $htmlData .= '<h6>' . $i . '.' . $rowParent->title . '</h6>';

                $query = $this->db->query('
                    SELECT 
                        HPD.id,
                        HPD.title,
                        NC.crime,
                        IF(NC.crime > 0, \'_row-more\', \'\') AS crime_class,
                        DOCTOR_VIEW.doctor_view,
                        FILE_FOLDER.file_folder,
                        ANATOMY.anatomy,
                        EXTRA.extra,
                        IF(EXTRA.extra > 0, \'_row-more\', \'\') AS extra_class,
                        ECONOMY.economy,
                        IF(ECONOMY.economy > 0, \'_row-more\', \'\') AS economy_class,
                        SEND_DOC.send_document,
                        IF(SEND_DOC.send_document > 0, \'_row-more\', \'\') AS send_document_class,
                        SCENE.scene,
                        IF(SCENE.scene > 0, \'_row-more\', \'\') AS scene_class
                    FROM `gaz_hr_people_department` AS HPD
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            COUNT(N.department_id) AS crime
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.department_id != 0 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS NC ON NC.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            COUNT(N.department_id) AS doctor_view
                        FROM `gaz_nifs_doctor_view` AS N 
                        WHERE 1 = 1 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS DOCTOR_VIEW ON DOCTOR_VIEW.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            COUNT(N.department_id) AS file_folder
                        FROM `gaz_nifs_file_folder` AS N 
                        WHERE 1 = 1 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS FILE_FOLDER ON FILE_FOLDER.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            COUNT(N.department_id) AS anatomy
                        FROM `gaz_nifs_anatomy` AS N 
                        WHERE 1 = 1 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS ANATOMY ON ANATOMY.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            COUNT(N.department_id) AS extra
                        FROM `gaz_nifs_extra` AS N
                        WHERE 1 = 1 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS EXTRA ON EXTRA.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            COUNT(N.type_id) AS economy
                        FROM `gaz_nifs_economy` AS N
                        WHERE 1 = 1 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS ECONOMY ON ECONOMY.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            COUNT(N.type_id) AS send_document
                        FROM `gaz_nifs_send_doc` AS N
                        WHERE 1 = 1 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SEND_DOC ON SEND_DOC.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            COUNT(N.scene_type_id) AS scene
                        FROM `gaz_nifs_scene` AS N
                        WHERE 1 = 1 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SCENE ON SCENE.department_id = HPD.id
                    WHERE HPD.is_active = 1 AND HPD.parent_id = ' . $rowParent->id);

                if ($query->num_rows() > 0) {

                    $htmlData .= '<div class="table-responsive">';
                    $htmlData .= '<table class="table _report">';
                    $htmlData .= '<thead>';
                    $htmlData .= '<tr>';
                    $htmlData .= '<th style="width:30px;">#</th>';
                    $htmlData .= '<th style="min-width:300px;">Гарчиг</th>';
                    $htmlData .= '<th class="text-center" style="width:80px; cursor:pointer;" onclick="_nifsReportGeneralDetail({modId:33, departmentId:' . $rowParent->id . ', inDate: ' . $param['inDate'] . ', outDate: ' . $param['outDate'] . '});">Кримналистик</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;" onclick="_nifsReportGeneralDetail({modId:\'forensic\', departmentId:' . $rowParent->id . ', inDate: ' . $param['inDate'] . ', outDate: ' . $param['outDate'] . '});">Шүүх эмнэлэг</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;" onclick="_nifsReportGeneralDetail({modId:55, departmentId:' . $rowParent->id . ', inDate: ' . $param['inDate'] . ', outDate: ' . $param['outDate'] . '});">Тусгай шинжилгээ</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;" onclick="_nifsReportGeneralDetail({modId:56, departmentId:' . $rowParent->id . ', inDate: ' . $param['inDate'] . ', outDate: ' . $param['outDate'] . '});">Эдийн засаг</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;" onclick="_nifsReportGeneralDetail({modId:81, departmentId:' . $rowParent->id . ', inDate: ' . $param['inDate'] . ', outDate: ' . $param['outDate'] . '});">Илгээх бичиг</th>';
                    $htmlData .= '<th class="text-center" style="width:80px;">Нийт</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;" onclick="_nifsReportGeneralDetail({modId:34, departmentId:' . $rowParent->id . ', inDate: ' . $param['inDate'] . ', outDate: ' . $param['outDate'] . '});">Хэргийн газрын үзлэг</th>';
                    $htmlData .= '</tr>';

                    $htmlData .= '</thead>';
                    $htmlData .= '<tbody>';
                    foreach ($query->result() as $key => $row) {

                        $row->forensic = $row->doctor_view + $row->file_folder + $row->anatomy;

                        $j++;
                        $sumTotalCrime = $sumTotalCrime + $row->crime;
                        $sumTotalForensic = $sumTotalForensic + $row->forensic;
                        $sumTotalExtra = $sumTotalExtra + $row->extra;
                        $sumTotalEconomy = $sumTotalEconomy + $row->economy;
                        $sumTotalSendDocument = $sumTotalSendDocument + $row->send_document;
                        $sumRow = $row->crime + $row->forensic + $row->extra + $row->economy + $row->send_document;
                        $sumTotalRow = $sumTotalRow + $sumRow;

                        $sumTotalScene = $sumTotalScene + $row->scene;

                        $this->updateChartGeneralData_model(array(
                            'departmentId' => $row->id,
                            'parentId' => $rowParent->id,
                            'crime' => $row->crime,
                            'forensic' => $row->forensic,
                            'extra' => $row->extra,
                            'economy' => $row->economy,
                            'sendDocument' => $row->send_document,
                            'scene' => $row->scene));

                        $isChildData = self::getReportGeneralChildData_model(array(
                                    'crime' => $row->crime,
                                    'forensic' => $row->forensic,
                                    'extra' => $row->extra,
                                    'economy' => $row->economy,
                                    'send_document' => $row->send_document,
                                    'scene' => $row->scene,
                                    'reportMenuId' => $param['reportMenuId'],
                                    'reportModId' => $param['reportModId'],
                                    'parentId' => $row->id,
                                    'space' => 40,
                                    'num' => $j,
                                    'inDate' => $param['inDate'],
                                    'outDate' => $param['outDate']));

                        if ($isChildData) {
                            $sumTotalCrime = $sumTotalCrime + $isChildData['crime'];
                            $sumTotalForensic = $sumTotalForensic + $isChildData['forensic'];
                            $sumTotalExtra = $sumTotalExtra + $isChildData['extra'];
                            $sumTotalEconomy = $sumTotalEconomy + $isChildData['economy'];
                            $sumTotalSendDocument = $sumTotalSendDocument + $isChildData['send_document'];
                            $sumRow = $isChildData['crime'] + $isChildData['forensic'] + $isChildData['extra'] + $isChildData['economy'] + $isChildData['send_document'];
                            $sumTotalRow = $sumTotalRow + $sumRow;
                            $sumTotalScene = $sumTotalScene + $isChildData['scene'];

                            if ($isChildData['crime'] > 0) {
                                $row->crime_class = '_row-more';
                                $row->crime = $isChildData['crime'];
                            }

                            if ($isChildData['forensic'] > 0) {
                                $row->forensic = $isChildData['forensic'];
                            }

                            if ($isChildData['extra'] > 0) {
                                $row->extra_class = '_row-more';
                                $row->extra = $isChildData['extra'];
                            }

                            if ($isChildData['economy'] > 0) {
                                $row->economy_class = '_row-more';
                                $row->economy = $isChildData['economy'];
                            }

                            if ($isChildData['send_document'] > 0) {
                                $row->send_document_class = '_row-more';
                                $row->send_document = $isChildData['send_document'];
                            }

                            if ($isChildData['scene'] > 0) {
                                $row->scene_class = '_row-more';
                                $row->scene = $isChildData['scene'];
                            }

                            $this->updateChartGeneralData_model(array(
                                'departmentId' => $row->id,
                                'parentId' => $rowParent->id,
                                'crime' => $row->crime,
                                'forensic' => $row->forensic,
                                'extra' => $row->extra,
                                'economy' => $row->economy,
                                'sendDocument' => $row->send_document,
                                'scene' => $row->scene));

                            $showIcon = '<i class="icon-plus2" onclick="_nifsReportCheckRow({elem:this, class: \'child-' . $row->id . '\'});" style="cursor:pointer;"></i> ';
                        }

                        $htmlData .= '<tr>';
                        $htmlData .= '<td>' . $j . '</td>';
                        $htmlData .= '<td class="text-left">' . $showIcon . $row->title . '</td>';
                        $htmlData .= '<td class="text-center ' . $row->crime_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime . '</a></td>';
                        $htmlData .= '<td class="text-center">' . ($row->forensic > 0 ? $row->forensic : '') . '</td>';
                        $htmlData .= '<td class="text-center ' . $row->extra_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->extra . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->economy_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'departmentId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->economy . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->send_document_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'departmentId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->send_document . '</a></td>';
                        $htmlData .= '<td class="text-center">' . ($sumRow > 0 ? $sumRow : '') . '</td>';
                        $htmlData .= '<td class="text-center ' . $row->scene_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'departmentId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene . '</a></td>';

                        $htmlData .= '</tr>';

                        if ($isChildData) {
                            $htmlData .= $isChildData['html'];
                        }
                    }
                    $htmlData .= '</tbody>';
                    $htmlData .= '<tfoot>';
                    $htmlData .= '<tr>';

                    $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт</td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $rowParent->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrime > 0 ? $sumTotalCrime : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalForensic > 0 ? $sumTotalForensic : '') . '</td>';
                    $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalExtra > 0 ? $sumTotalExtra : '') . '</td>';
                    $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalEconomy > 0 ? $sumTotalEconomy : '') . '</td>';
                    $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalSendDocument > 0 ? $sumTotalSendDocument : '') . '</td>';
                    $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalRow > 0 ? $sumTotalRow : '') . '</td>';
                    $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalScene > 0 ? $sumTotalScene : '') . '</td>';
                    $htmlData .= '</tr>';

                    $htmlData .= '</tfoot>';
                    $htmlData .= '</table>';
                    $htmlData .= '</div>';
                }

                $sumTotalAllCrime = $sumTotalAllCrime + $sumTotalCrime;
                $sumTotalAllForensic = $sumTotalAllForensic + $sumTotalForensic;
                $sumTotalAllExtra = $sumTotalAllExtra + $sumTotalExtra;
                $sumTotalAllEconomy = $sumTotalAllEconomy + $sumTotalEconomy;
                $sumTotalAllScene = $sumTotalAllScene + $sumTotalScene;
                $sumTotalAllSendDocument = $sumTotalAllSendDocument + $sumTotalSendDocument;
                $sumTotalAllRow = $sumTotalAllRow + $sumTotalRow;

                $allTableHtml .= '<tr>';
                $allTableHtml .= '<td class="text-center">' . $i . '</td>';
                $allTableHtml .= '<td>' . $rowParent->title . '</td>';
                $allTableHtml .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $rowParent->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrime > 0 ? $sumTotalCrime : '') . '</a></td>';
                $allTableHtml .= '<td class="text-center">' . ($sumTotalForensic > 0 ? $sumTotalForensic : '') . '</td>';
                $allTableHtml .= '<td class="text-center ' . ($sumTotalExtra > 0 ? ' _row-more' : '') . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $rowParent->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalExtra > 0 ? $sumTotalExtra : '') . '</a></td>';
                $allTableHtml .= '<td class="text-center ' . ($sumTotalEconomy > 0 ? ' _row-more' : '') . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'departmentId=' . $rowParent->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalEconomy > 0 ? $sumTotalEconomy : '') . '</a></td>';
                $allTableHtml .= '<td class="text-center ' . ($sumTotalSendDocument > 0 ? ' _row-more' : '') . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'departmentId=' . $rowParent->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSendDocument > 0 ? $sumTotalSendDocument : '') . '</a></td>';
                $allTableHtml .= '<td class="text-center">' . ($sumTotalRow > 0 ? $sumTotalRow : '') . '</td>';
                $allTableHtml .= '<td class="text-center ' . ($sumTotalScene > 0 ? ' _row-more' : '') . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'departmentId=' . $rowParent->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalScene > 0 ? $sumTotalScene : '') . '</a></td>';
                $allTableHtml .= '</tr>';
            }
        }


        $htmlData .= '<h6>3.Улсын нэгдсэн дүн</h6>';
        $htmlData .= '<div class="table-responsive">';
        $htmlData .= '<table class="table _report">';
        $htmlData .= '<thead>';
        $htmlData .= '<tr>';
        $htmlData .= '<th style="width:30px;">#</th>';
        $htmlData .= '<th style="min-width:300px;">Гарчиг</th>';
        $htmlData .= '<th class="text-center" style="width:80px; cursor:pointer;">Кримналистик</th>';
        $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Шүүх эмнэлэг</th>';
        $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Тусгай шинжилгээ</th>';
        $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Эдийн засаг</th>';
        $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Илгээх бичиг</th>';
        $htmlData .= '<th class="text-center" style="width:80px;">Нийт</th>';
        $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer; border-left:1px solid rgba(0,0,0,1);">Хэргийн газрын үзлэг</th>';
        $htmlData .= '</tr>';

        $htmlData .= '</thead>';
        $htmlData .= '<tbody>';

        $htmlData .= $allTableHtml;

        $query = $this->db->query('
                    SELECT 
                        HPD.id,
                        HPD.title,
                        NC.crime,
                        IF(NC.crime > 0, \'_row-more\', \'\') AS crime_class,
                        (DOCTOR_VIEW.doctor_view + FILE_FOLDER.file_folder + ANATOMY.anatomy) AS forensic,
                        IF((DOCTOR_VIEW.doctor_view + FILE_FOLDER.file_folder + ANATOMY.anatomy) > 0, \'_row-more\', \'\') AS forensic_class,
                        EXTRA.extra,
                        IF(EXTRA.extra > 0, \'_row-more\', \'\') AS extra_class,
                        ECONOMY.economy,
                        IF(ECONOMY.economy > 0, \'_row-more\', \'\') AS economy_class,
                        SCENE.scene,
                        IF(SCENE.scene > 0, \'_row-more\', \'\') AS scene_class,
                        SEND_DOC.send_document,
                        IF(SEND_DOC.send_document > 0, \'_row-more\', \'\') AS send_document_class
                    FROM `gaz_hr_people_department` AS HPD
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            COUNT(N.department_id) AS crime
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.department_id = 3 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS NC ON NC.department_id = 3
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            COUNT(N.department_id) AS doctor_view
                        FROM `gaz_nifs_doctor_view` AS N 
                        WHERE N.department_id = 4 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS DOCTOR_VIEW ON DOCTOR_VIEW.department_id = 4
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            COUNT(N.department_id) AS file_folder
                        FROM `gaz_nifs_file_folder` AS N 
                        WHERE N.department_id = 4 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS FILE_FOLDER ON FILE_FOLDER.department_id = 4
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            COUNT(N.department_id) AS anatomy
                        FROM `gaz_nifs_anatomy` AS N 
                        WHERE N.department_id = 4 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS ANATOMY ON ANATOMY.department_id = 4
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            COUNT(N.department_id) AS extra
                        FROM `gaz_nifs_extra` AS N
                        WHERE N.department_id = 5 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS EXTRA ON EXTRA.department_id = 5
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            COUNT(N.type_id) AS economy
                        FROM `gaz_nifs_economy` AS N
                        WHERE N.department_id = 6 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS ECONOMY ON ECONOMY.department_id = 6
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            COUNT(N.scene_type_id) AS scene
                        FROM `gaz_nifs_scene` AS N
                        WHERE N.department_id = 4 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SCENE ON SCENE.department_id = 4
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            COUNT(N.type_id) AS send_document
                        FROM `gaz_nifs_send_doc` AS N
                        WHERE  N.department_id = 5 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SEND_DOC ON SEND_DOC.department_id = 5
                    WHERE HPD.id = 1');

        if ($query->num_rows() > 0) {
            $sumRow = 0;
            foreach ($query->result() as $key => $row) {

                $sumTotalAllCrime = $sumTotalAllCrime + $row->crime;
                $sumTotalAllForensic = $sumTotalAllForensic + $row->forensic;
                $sumTotalAllExtra = $sumTotalAllExtra + $row->extra;
                $sumTotalAllEconomy = $sumTotalAllEconomy + $row->economy;
                $sumTotalAllScene = $sumTotalAllScene + $sumTotalScene;
                $sumTotalAllSendDocument = $sumTotalAllSendDocument + $row->send_document;
                $sumRow = $row->crime + $row->forensic + $row->extra + $row->economy + $row->send_document;
                $sumTotalAllRow = $sumTotalAllRow + $sumRow;

                $htmlData .= '<tr>';
                $htmlData .= '<td>3</td>';
                $htmlData .= '<td class="text-left">Хүрээлэн</td>';
                $htmlData .= '<td class="text-center ' . $row->crime_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=3&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime . '</a></td>';
                $htmlData .= '<td class="text-center">' . $row->forensic . '</td>';
                $htmlData .= '<td class="text-center ' . $row->extra_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=5&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->extra . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->economy_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'departmentId=6&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->economy . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->send_document_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'departmentId=5&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->send_document . '</a></td>';
                $htmlData .= '<td class="text-center">' . ($sumRow > 0 ? $sumRow : '') . '</td>';
                $htmlData .= '<td class="text-center ' . $row->scene_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'departmentId=6&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene . '</a></td>';
                $htmlData .= '</tr>';

                $this->updateChartGeneralData_model(array(
                    'departmentId' => 99,
                    'parentId' => 99,
                    'crime' => $row->crime,
                    'forensic' => $row->forensic,
                    'extra' => $row->extra,
                    'economy' => $row->economy,
                    'sendDocument' => $row->send_document,
                    'scene' => $row->scene));
            }
        }

        $htmlData .= '</tbody>';
        $htmlData .= '<tfoot>';
        $htmlData .= '<td class="text-right _custom-foot" colspan="2">Нийт</td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalAllCrime > 0 ? $sumTotalAllCrime : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalAllForensic > 0 ? $sumTotalAllForensic : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalAllExtra > 0 ? $sumTotalAllExtra : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalAllEconomy > 0 ? $sumTotalAllEconomy : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalAllSendDocument > 0 ? $sumTotalAllSendDocument : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalAllRow > 0 ? $sumTotalAllRow : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalAllScene > 0 ? $sumTotalAllScene : '') . '</td>';
        $htmlData .= '</tfoot>';
        $htmlData .= '</table>';
        $htmlData .= '</div>';

        return $htmlData;
    }

    public function getReportGeneralChildData_model($param = array('parentId' => 0)) {

        $queryStringData = $htmlData = '';

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                NC.crime,
                IF(NC.crime > 0, \'_row-more\', \'\') AS crime_class,
                (DOCTOR_VIEW.doctor_view + FILE_FOLDER.file_folder + ANATOMY.anatomy) AS forensic,
                IF((DOCTOR_VIEW.doctor_view + FILE_FOLDER.file_folder + ANATOMY.anatomy) > 0, \'_row-more\', \'\') AS forensic_class,
                EXTRA.extra,
                IF(EXTRA.extra > 0, \'_row-more\', \'\') AS extra_class,
                ECONOMY.economy,
                IF(ECONOMY.economy > 0, \'_row-more\', \'\') AS economy_class,
                SCENE.scene,
                IF(SCENE.scene > 0, \'_row-more\', \'\') AS scene_class,
                SEND_DOC.send_document,
                IF(SEND_DOC.send_document > 0, \'_row-more\', \'\') AS send_document_class
            FROM `gaz_hr_people_department` AS HPD
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS crime
                FROM `gaz_nifs_crime` AS N 
                WHERE N.department_id != 0 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC ON NC.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS doctor_view
                FROM `gaz_nifs_doctor_view` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS DOCTOR_VIEW ON DOCTOR_VIEW.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS file_folder
                FROM `gaz_nifs_file_folder` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS FILE_FOLDER ON FILE_FOLDER.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS anatomy
                FROM `gaz_nifs_anatomy` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS ANATOMY ON ANATOMY.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS extra
                FROM `gaz_nifs_extra` AS N
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS EXTRA ON EXTRA.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS economy
                FROM `gaz_nifs_economy` AS N
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS ECONOMY ON ECONOMY.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.scene_type_id) AS scene
                FROM `gaz_nifs_scene` AS N
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS SCENE ON SCENE.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS send_document
                FROM `gaz_nifs_send_doc` AS N
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS SEND_DOC ON SEND_DOC.department_id = HPD.id
            WHERE HPD.is_active = 1 AND HPD.parent_id = ' . $param['parentId']);


        $j = $sumTotalCrime = $sumTotalForensic = $sumTotalExtra = $sumTotalEconomy = $sumTotalScene = $sumTotalSendDocument = $sumRow = $sumTotalRow = 0;

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $j++;
                $sumTotalCrime = $sumTotalCrime + $row->crime;
                $sumTotalForensic = $sumTotalForensic + $row->forensic;
                $sumTotalExtra = $sumTotalExtra + $row->extra;
                $sumTotalEconomy = $sumTotalEconomy + $row->economy;
                $sumTotalScene = $sumTotalScene + $row->scene;
                $sumTotalSendDocument = $sumTotalSendDocument + $row->send_document;
                $sumRow = $row->crime + $row->forensic + $row->extra + $row->economy + $row->scene + $row->send_document;
                $sumTotalRow = $sumTotalRow + $sumRow;

                $htmlData .= '<tr class="child-' . $param['parentId'] . '" style="display:none;">';
                $htmlData .= '<td>' . $param['num'] . '.' . $j . '</td>';
                $htmlData .= '<td class="text-left" style="padding-left:' . $param['space'] . 'px;">' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->crime_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime . '</a></td>';
                $htmlData .= '<td class="text-center">' . $row->forensic . '</td>';
                $htmlData .= '<td class="text-center ' . $row->extra_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->extra . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->economy_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'departmentId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->economy . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->send_document_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'departmentId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '\'});">' . $row->send_document . '</a></td>';
                $htmlData .= '<td class="text-center">' . ($sumRow > 0 ? $sumRow : '') . '</td>';
                $htmlData .= '<td class="text-center ' . $row->scene_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'departmentId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene . '</a></td>';

                $htmlData .= '</tr>';


                $isChildData = self::getReportGeneralChildData_model(array(
                            'crime' => $row->crime,
                            'forensic' => $row->forensic,
                            'extra' => $row->extra,
                            'economy' => $row->economy,
                            'scene' => $row->scene,
                            'send_document' => $row->send_document,
                            'parentId' => $row->id,
                            'space' => ($param['space'] + 40),
                            'num' => $param['num'] . '.' . $j,
                            'inDate' => $param['inDate'],
                            'outDate' => $param['outDate']));
                if ($isChildData) {
                    $htmlData .= $isChildData['html'];
                    $sumTotalCrime = $sumTotalCrime + $isChildData['crime'];
                    $sumTotalForensic = $sumTotalForensic + $isChildData['forensic'];
                    $sumTotalExtra = $sumTotalExtra + $isChildData['extra'];
                    $sumTotalEconomy = $sumTotalEconomy + $isChildData['economy'];
                    $sumTotalScene = $sumTotalScene + $isChildData['scene'];
                    $sumTotalSendDocument = $sumTotalSendDocument + $isChildData['send_document'];
                    $sumRow = $isChildData['crime'] + $isChildData['forensic'] + $isChildData['extra'] + $isChildData['economy'] + $isChildData['scene'] + $isChildData['send_document'];
                    $sumTotalRow = $sumTotalRow + $sumRow;
                }
            }

            return array('html' => $htmlData, 'crime' => $sumTotalCrime, 'forensic' => $sumTotalForensic, 'extra' => $sumTotalExtra, 'economy' => $sumTotalEconomy, 'scene' => $sumTotalScene, 'send_document' => $sumTotalSendDocument, 'sum_total_row' => $sumTotalRow);
        }

        return false;
    }

    public function getReportGeneralDetailCrimeData_model($param = array()) {

        $queryStringData = $htmlData = $allTableHtml = '';

        $sumTotalAllCrime = $sumTotalAllForensic = $sumTotalAllExtra = $sumTotalAllEconomy = $sumTotalAllScene = $sumTotalAllSendDocument = $sumTotalAllRow = 0;

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $queryParent = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title
            FROM `gaz_hr_people_department` AS HPD
            WHERE HPD.is_active = 1 AND HPD.id IN (' . $param['departmentId'] . ')');

        if ($queryParent->num_rows() > 0) {

            $i = $sumTotalCrime0Dep = $sumTotalCrime1Dep = 0;
            foreach ($queryParent->result() as $keyParent => $rowParent) {

                ++$i;
                $j = $sumTotalCrimeCat280 = $sumTotalCrimeCat281 = $sumTotalCrimeCat282 = $sumTotalCrimeCat283 = $sumTotalCrimeCat284 = $sumTotalCrimeCat285 = $sumTotalCrimeCat286 = $sumTotalCrimeCat342 = $sumTotalCrimeCat343 = $sumTotalCrimeCat344 = $sumTotalCrimeCat345 = $sumTotalCrimeCat346 = $sumTotalCrimeCat347 = $sumTotalCrimeCat348 = $sumTotalCrimeCat437 = $sumRowCrimeCatAll = $sumTotalCrimeCatAll = 0;

                $htmlData .= '<h6>' . $i . '.' . $rowParent->title . '</h6>';

                $query = $this->db->query('
                    SELECT 
                        HPD.id,
                        HPD.title,
                        CRIME_CAT_280.crime_cat_280,
                        IF(CRIME_CAT_280.crime_cat_280 > 0, \'_row-more\', \'\') AS crime_cat_280_class,
                        CRIME_CAT_281.crime_cat_281,
                        IF(CRIME_CAT_281.crime_cat_281 > 0, \'_row-more\', \'\') AS crime_cat_281_class,
                        CRIME_CAT_282.crime_cat_282,
                        IF(CRIME_CAT_282.crime_cat_282 > 0, \'_row-more\', \'\') AS crime_cat_282_class,
                        CRIME_CAT_283.crime_cat_283,
                        IF(CRIME_CAT_283.crime_cat_283 > 0, \'_row-more\', \'\') AS crime_cat_283_class,
                        CRIME_CAT_284.crime_cat_284,
                        IF(CRIME_CAT_284.crime_cat_284 > 0, \'_row-more\', \'\') AS crime_cat_284_class,
                        CRIME_CAT_285.crime_cat_285,
                        IF(CRIME_CAT_285.crime_cat_285 > 0, \'_row-more\', \'\') AS crime_cat_285_class,
                        CRIME_CAT_286.crime_cat_286,
                        IF(CRIME_CAT_286.crime_cat_286 > 0, \'_row-more\', \'\') AS crime_cat_286_class,
                        CRIME_CAT_342.crime_cat_342,
                        IF(CRIME_CAT_342.crime_cat_342 > 0, \'_row-more\', \'\') AS crime_cat_342_class,
                        CRIME_CAT_343.crime_cat_343,
                        IF(CRIME_CAT_343.crime_cat_343 > 0, \'_row-more\', \'\') AS crime_cat_343_class,
                        CRIME_CAT_344.crime_cat_344,
                        IF(CRIME_CAT_344.crime_cat_344 > 0, \'_row-more\', \'\') AS crime_cat_344_class,
                        CRIME_CAT_345.crime_cat_345,
                        IF(CRIME_CAT_345.crime_cat_345 > 0, \'_row-more\', \'\') AS crime_cat_345_class,
                        CRIME_CAT_346.crime_cat_346,
                        IF(CRIME_CAT_346.crime_cat_346 > 0, \'_row-more\', \'\') AS crime_cat_346_class,
                        CRIME_CAT_347.crime_cat_347,
                        IF(CRIME_CAT_347.crime_cat_347 > 0, \'_row-more\', \'\') AS crime_cat_347_class,
                        CRIME_CAT_348.crime_cat_348,
                        IF(CRIME_CAT_348.crime_cat_348 > 0, \'_row-more\', \'\') AS crime_cat_348_class,
                        CRIME_CAT_437.crime_cat_437,
                        IF(CRIME_CAT_437.crime_cat_437 > 0, \'_row-more\', \'\') AS crime_cat_437_class,
                        (CRIME_CAT_280.crime_cat_280 + CRIME_CAT_281.crime_cat_281 + CRIME_CAT_282.crime_cat_282 + CRIME_CAT_283.crime_cat_283 + CRIME_CAT_284.crime_cat_284 + CRIME_CAT_285.crime_cat_285 + CRIME_CAT_286.crime_cat_286 + CRIME_CAT_342.crime_cat_342 + CRIME_CAT_343.crime_cat_343 + CRIME_CAT_344.crime_cat_344 + CRIME_CAT_345.crime_cat_345 + CRIME_CAT_346.crime_cat_346 + CRIME_CAT_347.crime_cat_347 + CRIME_CAT_348.crime_cat_348 + CRIME_CAT_437.crime_cat_437) AS crime_cat_all
                    FROM `gaz_hr_people_department` AS HPD
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_280_in_date,
                            COUNT(N.cat_id) AS crime_cat_280
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 280 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_280 ON CRIME_CAT_280.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_281_in_date,
                            COUNT(N.cat_id) AS crime_cat_281
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 281 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_281 ON CRIME_CAT_281.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_282_in_date,
                            COUNT(N.cat_id) AS crime_cat_282
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 282 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_282 ON CRIME_CAT_282.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_283_in_date,
                            COUNT(N.cat_id) AS crime_cat_283
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 283 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_283 ON CRIME_CAT_283.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_284_in_date,
                            COUNT(N.cat_id) AS crime_cat_284
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 284 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_284 ON CRIME_CAT_284.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_285_in_date,
                            COUNT(N.cat_id) AS crime_cat_285
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 285 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_285 ON CRIME_CAT_285.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_286_in_date,
                            COUNT(N.cat_id) AS crime_cat_286
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 286 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_286 ON CRIME_CAT_286.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_342_in_date,
                            COUNT(N.cat_id) AS crime_cat_342
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 342 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_342 ON CRIME_CAT_342.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_343_in_date,
                            COUNT(N.cat_id) AS crime_cat_343
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 343 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_343 ON CRIME_CAT_343.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_344_in_date,
                            COUNT(N.cat_id) AS crime_cat_344
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 344 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_344 ON CRIME_CAT_344.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_345_in_date,
                            COUNT(N.cat_id) AS crime_cat_345
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 345 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_345 ON CRIME_CAT_345.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_346_in_date,
                            COUNT(N.cat_id) AS crime_cat_346
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 346 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_346 ON CRIME_CAT_346.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_347_in_date,
                            COUNT(N.cat_id) AS crime_cat_347
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 347 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_347 ON CRIME_CAT_347.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_348_in_date,
                            COUNT(N.cat_id) AS crime_cat_348
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 348 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_348 ON CRIME_CAT_348.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_437_in_date,
                            COUNT(N.cat_id) AS crime_cat_437
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 437 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_437 ON CRIME_CAT_437.department_id = HPD.id
                    WHERE HPD.is_active = 1 AND HPD.parent_id = ' . $rowParent->id);

                if ($query->num_rows() > 0) {

                    $htmlData .= '<div class="table-responsive">';
                    $htmlData .= '<table class="table _report">';
                    $htmlData .= '<thead>';
                    $htmlData .= '<tr>';
                    $htmlData .= '<th style="width:30px;">#</th>';
                    $htmlData .= '<th style="min-width:300px;">Гарчиг</th>';
                    $htmlData .= '<th class="text-center" style="width:80px; cursor:pointer;">Дүрс бичлэг</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Дүр зураг</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Мөр судлал</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Гарын мөр</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Бичиг техник</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Галт зэвсэг</th>';

                    $htmlData .= '<th class="text-center" style="width:80px; cursor:pointer;">Бичиг судлал</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Авто техникийн шинжилгээ</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Тэсрэх төхөөрөмж</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Компьютер техник</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Дуу авиа</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Полиграф</th>';

                    $htmlData .= '<th class="text-center" style="width:80px; cursor:pointer;">Инженер техник</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Материал судлал</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Бусад</th>';
                    $htmlData .= '<th class="text-center" style="width:80px;">Нийт</th>';
                    $htmlData .= '</tr>';

                    $htmlData .= '</thead>';
                    $htmlData .= '<tbody>';
                    foreach ($query->result() as $key => $row) {

                        $j++;

                        $sumTotalCrimeCat280 = $sumTotalCrimeCat280 + $row->crime_cat_280;
                        $sumTotalCrimeCat281 = $sumTotalCrimeCat281 + $row->crime_cat_281;
                        $sumTotalCrimeCat282 = $sumTotalCrimeCat282 + $row->crime_cat_282;
                        $sumTotalCrimeCat283 = $sumTotalCrimeCat283 + $row->crime_cat_283;
                        $sumTotalCrimeCat284 = $sumTotalCrimeCat284 + $row->crime_cat_284;
                        $sumTotalCrimeCat285 = $sumTotalCrimeCat285 + $row->crime_cat_285;
                        $sumTotalCrimeCat286 = $sumTotalCrimeCat286 + $row->crime_cat_286;
                        $sumTotalCrimeCat342 = $sumTotalCrimeCat342 + $row->crime_cat_342;
                        $sumTotalCrimeCat343 = $sumTotalCrimeCat343 + $row->crime_cat_343;
                        $sumTotalCrimeCat344 = $sumTotalCrimeCat344 + $row->crime_cat_344;
                        $sumTotalCrimeCat345 = $sumTotalCrimeCat345 + $row->crime_cat_345;
                        $sumTotalCrimeCat346 = $sumTotalCrimeCat346 + $row->crime_cat_346;
                        $sumTotalCrimeCat347 = $sumTotalCrimeCat347 + $row->crime_cat_347;
                        $sumTotalCrimeCat348 = $sumTotalCrimeCat348 + $row->crime_cat_348;
                        $sumTotalCrimeCat437 = $sumTotalCrimeCat437 + $row->crime_cat_437;
                        $sumRowCrimeCatAll = $row->crime_cat_280 + $row->crime_cat_281 + $row->crime_cat_282 + $row->crime_cat_283 + $row->crime_cat_284 + $row->crime_cat_285 + $row->crime_cat_286 + $row->crime_cat_342 + $row->crime_cat_343 + $row->crime_cat_344 + $row->crime_cat_345 + $row->crime_cat_346 + $row->crime_cat_347 + $row->crime_cat_348 + $row->crime_cat_437;

                        $htmlData .= '<tr>';
                        $htmlData .= '<td>' . $j . '</td>';
                        $htmlData .= '<td class="text-left">' . $row->title . '</td>';
                        $htmlData .= '<td class="text-center ' . $row->crime_cat_280_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=280&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_280 . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->crime_cat_281_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=281&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_281 . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->crime_cat_282_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=282&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_282 . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->crime_cat_283_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=283&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_283 . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->crime_cat_284_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=284&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_284 . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->crime_cat_285_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=285&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_285 . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->crime_cat_286_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=286&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_286 . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->crime_cat_342_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=342&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_342 . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->crime_cat_343_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=343&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_343 . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->crime_cat_344_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=344&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_344 . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->crime_cat_345_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=345&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_345 . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->crime_cat_346_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=346&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_346 . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->crime_cat_347_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=347&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_347 . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->crime_cat_348_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=348&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_348 . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->crime_cat_437_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=437&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_437 . '</a></td>';
                        $htmlData .= '<td class="text-center ' . ($sumRowCrimeCatAll > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumRowCrimeCatAll > 0 ? $sumRowCrimeCatAll : '') . '</a></td>';
                        $htmlData .= '</tr>';

                        $isChildData = self::getReportGeneralDetailCrimeChildData_model(array(
                                    'crime_cat_280' => $row->crime_cat_280,
                                    'crime_cat_281' => $row->crime_cat_281,
                                    'crime_cat_282' => $row->crime_cat_282,
                                    'crime_cat_283' => $row->crime_cat_283,
                                    'crime_cat_284' => $row->crime_cat_284,
                                    'crime_cat_285' => $row->crime_cat_285,
                                    'crime_cat_286' => $row->crime_cat_286,
                                    'crime_cat_342' => $row->crime_cat_342,
                                    'crime_cat_343' => $row->crime_cat_343,
                                    'crime_cat_344' => $row->crime_cat_344,
                                    'crime_cat_345' => $row->crime_cat_345,
                                    'crime_cat_346' => $row->crime_cat_346,
                                    'crime_cat_347' => $row->crime_cat_347,
                                    'crime_cat_348' => $row->crime_cat_348,
                                    'crime_cat_437' => $row->crime_cat_437,
                                    'inDate' => $param['inDate'],
                                    'outDate' => $param['outDate'],
                                    'reportMenuId' => $param['reportMenuId'],
                                    'reportModId' => $param['reportModId'],
                                    'parentId' => $row->id,
                                    'space' => 40,
                                    'num' => $j
                        ));

                        if ($isChildData) {
                            $htmlData .= $isChildData['html'];
                            $sumTotalCrimeCat280 = $sumTotalCrimeCat280 + $isChildData['crime_cat_280'];
                            $sumTotalCrimeCat281 = $sumTotalCrimeCat281 + $isChildData['crime_cat_281'];
                            $sumTotalCrimeCat282 = $sumTotalCrimeCat282 + $isChildData['crime_cat_282'];
                            $sumTotalCrimeCat283 = $sumTotalCrimeCat283 + $isChildData['crime_cat_283'];
                            $sumTotalCrimeCat284 = $sumTotalCrimeCat284 + $isChildData['crime_cat_284'];
                            $sumTotalCrimeCat285 = $sumTotalCrimeCat285 + $isChildData['crime_cat_285'];
                            $sumTotalCrimeCat286 = $sumTotalCrimeCat286 + $isChildData['crime_cat_286'];
                            $sumTotalCrimeCat342 = $sumTotalCrimeCat342 + $isChildData['crime_cat_342'];
                            $sumTotalCrimeCat343 = $sumTotalCrimeCat343 + $isChildData['crime_cat_343'];
                            $sumTotalCrimeCat344 = $sumTotalCrimeCat344 + $isChildData['crime_cat_343'];
                            $sumTotalCrimeCat345 = $sumTotalCrimeCat345 + $isChildData['crime_cat_343'];
                            $sumTotalCrimeCat346 = $sumTotalCrimeCat346 + $isChildData['crime_cat_346'];
                            $sumTotalCrimeCat347 = $sumTotalCrimeCat347 + $isChildData['crime_cat_347'];
                            $sumTotalCrimeCat348 = $sumTotalCrimeCat348 + $isChildData['crime_cat_348'];
                            $sumTotalCrimeCat437 = $sumTotalCrimeCat437 + $isChildData['crime_cat_437'];
                            $sumRowCrimeCatAll = $isChildData['crime_cat_280'] + $isChildData['crime_cat_281'] + $isChildData['crime_cat_282'] + $isChildData['crime_cat_283'] + $isChildData['crime_cat_284'] + $isChildData['crime_cat_285'] + $isChildData['crime_cat_286'] + $isChildData['crime_cat_342'] + $isChildData['crime_cat_343'] + $isChildData['crime_cat_344'] + $isChildData['crime_cat_345'] + $isChildData['crime_cat_346'] + $isChildData['crime_cat_347'] + $isChildData['crime_cat_348'] + $isChildData['crime_cat_437'];
                        }

                        $sumTotalCrimeCatAll = $sumTotalCrimeCatAll + $sumRowCrimeCatAll;
                    }
                    $htmlData .= '</tbody>';
                    $htmlData .= '<tfoot>';
                    $htmlData .= '<tr>';

                    $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт</td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=all&catId=280&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrimeCat280 > 0 ? $sumTotalCrimeCat280 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=all&catId=281&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrimeCat281 > 0 ? $sumTotalCrimeCat281 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=all&catId=282&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrimeCat282 > 0 ? $sumTotalCrimeCat282 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=all&catId=283&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrimeCat283 > 0 ? $sumTotalCrimeCat283 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=all&catId=284&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrimeCat284 > 0 ? $sumTotalCrimeCat284 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=all&catId=285&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrimeCat285 > 0 ? $sumTotalCrimeCat285 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=all&catId=286&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrimeCat286 > 0 ? $sumTotalCrimeCat286 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=all&catId=342&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrimeCat342 > 0 ? $sumTotalCrimeCat342 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=all&catId=343&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrimeCat343 > 0 ? $sumTotalCrimeCat343 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=all&catId=344&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrimeCat344 > 0 ? $sumTotalCrimeCat344 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=all&catId=345&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrimeCat345 > 0 ? $sumTotalCrimeCat345 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=all&catId=346&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrimeCat346 > 0 ? $sumTotalCrimeCat346 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=all&catId=347&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrimeCat347 > 0 ? $sumTotalCrimeCat347 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=all&catId=348&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrimeCat348 > 0 ? $sumTotalCrimeCat348 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=all&catId=437&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrimeCat437 > 0 ? $sumTotalCrimeCat437 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=all&catId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalCrimeCatAll > 0 ? $sumTotalCrimeCatAll : '') . '</a></td>';
                    $htmlData .= '</tr>';

                    $htmlData .= '</tfoot>';
                    $htmlData .= '</table>';
                    $htmlData .= '</div>';
                }

                return array(
                    'title' => $rowParent->title . ' нэгдсэн дүн',
                    'width' => 1300,
                    'html' => $htmlData,
                    'status' => true);
            }
        }
        return array('status' => false);
    }

    public function getReportGeneralDetailCrimeChildData_model($param = array()) {

        $queryStringData = $htmlData = $allTableHtml = '';

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $j = $sumTotalCrimeCat280 = $sumTotalCrimeCat281 = $sumTotalCrimeCat282 = $sumTotalCrimeCat283 = $sumTotalCrimeCat284 = $sumTotalCrimeCat285 = $sumTotalCrimeCat286 = $sumTotalCrimeCat342 = $sumTotalCrimeCat343 = $sumTotalCrimeCat344 = $sumTotalCrimeCat345 = $sumTotalCrimeCat346 = $sumTotalCrimeCat347 = $sumTotalCrimeCat348 = $sumTotalCrimeCat437 = $sumRowCrimeCatAll = $sumTotalCrimeCatAll = 0;

        $query = $this->db->query('
                    SELECT 
                        HPD.id,
                        HPD.title,
                        CRIME_CAT_280.crime_cat_280,
                        IF(CRIME_CAT_280.crime_cat_280 > 0, \'_row-more\', \'\') AS crime_cat_280_class,
                        CRIME_CAT_281.crime_cat_281,
                        IF(CRIME_CAT_281.crime_cat_281 > 0, \'_row-more\', \'\') AS crime_cat_281_class,
                        CRIME_CAT_282.crime_cat_282,
                        IF(CRIME_CAT_282.crime_cat_282 > 0, \'_row-more\', \'\') AS crime_cat_282_class,
                        CRIME_CAT_283.crime_cat_283,
                        IF(CRIME_CAT_283.crime_cat_283 > 0, \'_row-more\', \'\') AS crime_cat_283_class,
                        CRIME_CAT_284.crime_cat_284,
                        IF(CRIME_CAT_284.crime_cat_284 > 0, \'_row-more\', \'\') AS crime_cat_284_class,
                        CRIME_CAT_285.crime_cat_285,
                        IF(CRIME_CAT_285.crime_cat_285 > 0, \'_row-more\', \'\') AS crime_cat_285_class,
                        CRIME_CAT_286.crime_cat_286,
                        IF(CRIME_CAT_286.crime_cat_286 > 0, \'_row-more\', \'\') AS crime_cat_286_class,
                        CRIME_CAT_342.crime_cat_342,
                        IF(CRIME_CAT_342.crime_cat_342 > 0, \'_row-more\', \'\') AS crime_cat_342_class,
                        CRIME_CAT_343.crime_cat_343,
                        IF(CRIME_CAT_343.crime_cat_343 > 0, \'_row-more\', \'\') AS crime_cat_343_class,
                        CRIME_CAT_344.crime_cat_344,
                        IF(CRIME_CAT_344.crime_cat_344 > 0, \'_row-more\', \'\') AS crime_cat_344_class,
                        CRIME_CAT_345.crime_cat_345,
                        IF(CRIME_CAT_345.crime_cat_345 > 0, \'_row-more\', \'\') AS crime_cat_345_class,
                        CRIME_CAT_346.crime_cat_346,
                        IF(CRIME_CAT_346.crime_cat_346 > 0, \'_row-more\', \'\') AS crime_cat_346_class,
                        CRIME_CAT_347.crime_cat_347,
                        IF(CRIME_CAT_347.crime_cat_347 > 0, \'_row-more\', \'\') AS crime_cat_347_class,
                        CRIME_CAT_348.crime_cat_348,
                        IF(CRIME_CAT_348.crime_cat_348 > 0, \'_row-more\', \'\') AS crime_cat_348_class,
                        CRIME_CAT_437.crime_cat_437,
                        IF(CRIME_CAT_437.crime_cat_437 > 0, \'_row-more\', \'\') AS crime_cat_437_class,
                        (CRIME_CAT_280.crime_cat_280 + CRIME_CAT_281.crime_cat_281 + CRIME_CAT_282.crime_cat_282 + CRIME_CAT_283.crime_cat_283 + CRIME_CAT_284.crime_cat_284 + CRIME_CAT_285.crime_cat_285 + CRIME_CAT_286.crime_cat_286 + CRIME_CAT_342.crime_cat_342 + CRIME_CAT_343.crime_cat_343 + CRIME_CAT_344.crime_cat_344 + CRIME_CAT_345.crime_cat_345 + CRIME_CAT_346.crime_cat_346 + CRIME_CAT_347.crime_cat_347 + CRIME_CAT_348.crime_cat_348 + CRIME_CAT_437.crime_cat_437) AS crime_cat_all
                    FROM `gaz_hr_people_department` AS HPD
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_280_in_date,
                            COUNT(N.cat_id) AS crime_cat_280
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 280 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_280 ON CRIME_CAT_280.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_281_in_date,
                            COUNT(N.cat_id) AS crime_cat_281
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 281 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_281 ON CRIME_CAT_281.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_282_in_date,
                            COUNT(N.cat_id) AS crime_cat_282
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 282 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_282 ON CRIME_CAT_282.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_283_in_date,
                            COUNT(N.cat_id) AS crime_cat_283
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 283 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_283 ON CRIME_CAT_283.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_284_in_date,
                            COUNT(N.cat_id) AS crime_cat_284
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 284 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_284 ON CRIME_CAT_284.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_285_in_date,
                            COUNT(N.cat_id) AS crime_cat_285
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 285 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_285 ON CRIME_CAT_285.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_286_in_date,
                            COUNT(N.cat_id) AS crime_cat_286
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 286 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_286 ON CRIME_CAT_286.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_342_in_date,
                            COUNT(N.cat_id) AS crime_cat_342
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 342 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_342 ON CRIME_CAT_342.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_343_in_date,
                            COUNT(N.cat_id) AS crime_cat_343
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 343 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_343 ON CRIME_CAT_343.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_344_in_date,
                            COUNT(N.cat_id) AS crime_cat_344
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 344 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_344 ON CRIME_CAT_344.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_345_in_date,
                            COUNT(N.cat_id) AS crime_cat_345
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 345 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_345 ON CRIME_CAT_345.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_346_in_date,
                            COUNT(N.cat_id) AS crime_cat_346
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 346 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_346 ON CRIME_CAT_346.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_347_in_date,
                            COUNT(N.cat_id) AS crime_cat_347
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 347 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_347 ON CRIME_CAT_347.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_348_in_date,
                            COUNT(N.cat_id) AS crime_cat_348
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 348 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_348 ON CRIME_CAT_348.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS crime_cat_437_in_date,
                            COUNT(N.cat_id) AS crime_cat_437
                        FROM `gaz_nifs_crime` AS N 
                        WHERE N.cat_id = 437 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS CRIME_CAT_437 ON CRIME_CAT_437.department_id = HPD.id
                    WHERE HPD.is_active = 1 AND HPD.parent_id = ' . $param['parentId']);

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $j++;

                $param['crime_cat_280'] = $param['crime_cat_280'] + $row->crime_cat_280;
                $param['crime_cat_281'] = $param['crime_cat_281'] + $row->crime_cat_281;
                $param['crime_cat_282'] = $param['crime_cat_282'] + $row->crime_cat_282;
                $param['crime_cat_283'] = $param['crime_cat_283'] + $row->crime_cat_283;
                $param['crime_cat_284'] = $param['crime_cat_284'] + $row->crime_cat_284;
                $param['crime_cat_285'] = $param['crime_cat_285'] + $row->crime_cat_285;
                $param['crime_cat_286'] = $param['crime_cat_286'] + $row->crime_cat_286;
                $param['crime_cat_342'] = $param['crime_cat_342'] + $row->crime_cat_342;
                $param['crime_cat_343'] = $param['crime_cat_343'] + $row->crime_cat_343;
                $param['crime_cat_344'] = $param['crime_cat_344'] + $row->crime_cat_344;
                $param['crime_cat_345'] = $param['crime_cat_345'] + $row->crime_cat_345;
                $param['crime_cat_346'] = $param['crime_cat_346'] + $row->crime_cat_346;
                $param['crime_cat_347'] = $param['crime_cat_347'] + $row->crime_cat_347;
                $param['crime_cat_348'] = $param['crime_cat_348'] + $row->crime_cat_348;
                $param['crime_cat_437'] = $param['crime_cat_437'] + $row->crime_cat_437;
                $sumRowCrimeCatAll = $row->crime_cat_280 + $row->crime_cat_281 + $row->crime_cat_282 + $row->crime_cat_283 + $row->crime_cat_284 + $row->crime_cat_285 + $row->crime_cat_286 + $row->crime_cat_342 + $row->crime_cat_343 + $row->crime_cat_344 + $row->crime_cat_345 + $row->crime_cat_346 + $row->crime_cat_347 + $row->crime_cat_348 + $row->crime_cat_437;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $param['num'] . '.' . $j . '</td>';
                $htmlData .= '<td class="text-left" style="padding-left: ' . $param['space'] . 'px;">' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_280_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=280&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_280 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_281_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=281&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_281 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_282_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=282&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_282 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_283_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=283&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_283 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_284_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=284&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_284 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_285_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=285&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_285 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_286_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=286&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_286 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_342_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=342&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_342 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_343_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=343&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_343 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_344_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=344&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_344 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_345_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=345&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_345 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_346_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=346&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_346 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_347_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=347&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_347 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_348_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=348&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_348 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_437_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=437&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_437 . '</a></td>';
                $htmlData .= '<td class="text-center ' . ($sumRowCrimeCatAll > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumRowCrimeCatAll > 0 ? $sumRowCrimeCatAll : '') . '</a></td>';
                $htmlData .= '</tr>';

                $isChildData = self::getReportGeneralDetailCrimeChildData_model(array(
                            'crime_cat_280' => $row->crime_cat_280,
                            'crime_cat_281' => $row->crime_cat_281,
                            'crime_cat_282' => $row->crime_cat_282,
                            'crime_cat_283' => $row->crime_cat_283,
                            'crime_cat_284' => $row->crime_cat_284,
                            'crime_cat_285' => $row->crime_cat_285,
                            'crime_cat_286' => $row->crime_cat_286,
                            'crime_cat_342' => $row->crime_cat_342,
                            'crime_cat_343' => $row->crime_cat_343,
                            'crime_cat_344' => $row->crime_cat_344,
                            'crime_cat_345' => $row->crime_cat_345,
                            'crime_cat_346' => $row->crime_cat_346,
                            'crime_cat_347' => $row->crime_cat_347,
                            'crime_cat_348' => $row->crime_cat_348,
                            'crime_cat_437' => $row->crime_cat_437,
                            'inDate' => $param['inDate'],
                            'outDate' => $param['outDate'],
                            'reportMenuId' => $param['reportMenuId'],
                            'reportModId' => $param['reportModId'],
                            'parentId' => $row->id,
                            'space' => ($param['space'] + 40),
                            'num' => $j
                ));

                if ($isChildData) {
                    $htmlData .= $isChildData['html'];
                    $sumTotalCrimeCat280 = $sumTotalCrimeCat280 + $isChildData['crime_cat_280'];
                    $sumTotalCrimeCat281 = $sumTotalCrimeCat281 + $isChildData['crime_cat_281'];
                    $sumTotalCrimeCat282 = $sumTotalCrimeCat282 + $isChildData['crime_cat_282'];
                    $sumTotalCrimeCat283 = $sumTotalCrimeCat283 + $isChildData['crime_cat_283'];
                    $sumTotalCrimeCat284 = $sumTotalCrimeCat284 + $isChildData['crime_cat_284'];
                    $sumTotalCrimeCat285 = $sumTotalCrimeCat285 + $isChildData['crime_cat_285'];
                    $sumTotalCrimeCat286 = $sumTotalCrimeCat286 + $isChildData['crime_cat_286'];
                    $sumTotalCrimeCat342 = $sumTotalCrimeCat342 + $isChildData['crime_cat_342'];
                    $sumTotalCrimeCat343 = $sumTotalCrimeCat343 + $isChildData['crime_cat_343'];
                    $sumTotalCrimeCat344 = $sumTotalCrimeCat344 + $isChildData['crime_cat_343'];
                    $sumTotalCrimeCat345 = $sumTotalCrimeCat345 + $isChildData['crime_cat_343'];
                    $sumTotalCrimeCat346 = $sumTotalCrimeCat346 + $isChildData['crime_cat_346'];
                    $sumTotalCrimeCat347 = $sumTotalCrimeCat347 + $isChildData['crime_cat_347'];
                    $sumTotalCrimeCat348 = $sumTotalCrimeCat348 + $isChildData['crime_cat_348'];
                    $sumTotalCrimeCat437 = $sumTotalCrimeCat437 + $isChildData['crime_cat_437'];
                }
            }

            return array(
                'html' => $htmlData,
                'numRows' => $query->num_rows(),
                'crime_cat_280' => $param['crime_cat_280'],
                'crime_cat_281' => $param['crime_cat_281'],
                'crime_cat_282' => $param['crime_cat_282'],
                'crime_cat_283' => $param['crime_cat_283'],
                'crime_cat_284' => $param['crime_cat_284'],
                'crime_cat_285' => $param['crime_cat_285'],
                'crime_cat_286' => $param['crime_cat_286'],
                'crime_cat_342' => $param['crime_cat_342'],
                'crime_cat_343' => $param['crime_cat_343'],
                'crime_cat_344' => $param['crime_cat_344'],
                'crime_cat_345' => $param['crime_cat_345'],
                'crime_cat_346' => $param['crime_cat_346'],
                'crime_cat_347' => $param['crime_cat_347'],
                'crime_cat_348' => $param['crime_cat_348'],
                'crime_cat_437' => $param['crime_cat_437']);
        }
    }

    public function getReportGeneralDetailSceneData_model($param = array()) {

        $queryStringData = $htmlData = $allTableHtml = '';

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $queryParent = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title
            FROM `gaz_hr_people_department` AS HPD
            WHERE HPD.is_active = 1 AND HPD.id IN (' . $param['departmentId'] . ')');

        if ($queryParent->num_rows() > 0) {

            $i = 0;
            foreach ($queryParent->result() as $keyParent => $rowParent) {

                ++$i;
                $j = $sumTotalSceneType1 = $sumTotalSceneType2 = $sumTotalSceneType3 = $sumTotalSceneType4 = $sumTotalSceneType5 = $sumTotalSceneType6 = $sumTotalSceneType7 = $sumTotalRowAll = $sumRowAll = 0;

                $htmlData .= '<h6>' . $i . '.' . $rowParent->title . '</h6>';

                $query = $this->db->query('
                    SELECT 
                        HPD.id,
                        HPD.title,
                        SCENE_TYPE_1.scene_type_1_count,
                        IF(SCENE_TYPE_1.scene_type_1_count > 0, \'_row-more\', \'\') AS scene_type_1_count_class,
                        SCENE_TYPE_2.scene_type_2_count,
                        IF(SCENE_TYPE_2.scene_type_2_count > 0, \'_row-more\', \'\') AS scene_type_2_count_class,
                        SCENE_TYPE_3.scene_type_3_count,
                        IF(SCENE_TYPE_3.scene_type_3_count > 0, \'_row-more\', \'\') AS scene_type_3_count_class,
                        SCENE_TYPE_4.scene_type_4_count,
                        IF(SCENE_TYPE_4.scene_type_4_count > 0, \'_row-more\', \'\') AS scene_type_4_count_class,
                        SCENE_TYPE_5.scene_type_5_count,
                        IF(SCENE_TYPE_5.scene_type_5_count > 0, \'_row-more\', \'\') AS scene_type_5_count_class,
                        SCENE_TYPE_6.scene_type_6_count,
                        IF(SCENE_TYPE_6.scene_type_6_count > 0, \'_row-more\', \'\') AS scene_type_6_count_class,
                        SCENE_TYPE_7.scene_type_7_count,
                        IF(SCENE_TYPE_7.scene_type_7_count > 0, \'_row-more\', \'\') AS scene_type_7_count_class
                    FROM `gaz_hr_people_department` AS HPD
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS scene_type_1_in_date,
                            COUNT(N.scene_type_id) AS scene_type_1_count
                        FROM `gaz_nifs_scene` AS N
                        WHERE N.scene_type_id = 1 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SCENE_TYPE_1 ON SCENE_TYPE_1.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS scene_type_2_in_date,
                            COUNT(N.scene_type_id) AS scene_type_2_count
                        FROM `gaz_nifs_scene` AS N
                        WHERE N.scene_type_id = 2 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SCENE_TYPE_2 ON SCENE_TYPE_2.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS scene_type_3_in_date,
                            COUNT(N.scene_type_id) AS scene_type_3_count
                        FROM `gaz_nifs_scene` AS N
                        WHERE N.scene_type_id = 3 ' . $queryStringData . '
                            GROUP BY N.department_id
                    ) AS SCENE_TYPE_3 ON SCENE_TYPE_3.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS scene_type_4_in_date,
                            COUNT(N.scene_type_id) AS scene_type_4_count
                        FROM `gaz_nifs_scene` AS N
                        WHERE N.scene_type_id = 4 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SCENE_TYPE_4 ON SCENE_TYPE_4.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS scene_type_5_in_date,
                            COUNT(N.scene_type_id) AS scene_type_5_count
                        FROM `gaz_nifs_scene` AS N
                        WHERE N.scene_type_id = 5 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SCENE_TYPE_5 ON SCENE_TYPE_5.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS scene_type_6_in_date,
                            COUNT(N.scene_type_id) AS scene_type_6_count
                        FROM `gaz_nifs_scene` AS N
                        WHERE N.scene_type_id = 6 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SCENE_TYPE_6 ON SCENE_TYPE_6.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS scene_type_7_in_date,
                            COUNT(N.scene_type_id) AS scene_type_7_count
                        FROM `gaz_nifs_scene` AS N
                        WHERE N.scene_type_id = 7 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SCENE_TYPE_7 ON SCENE_TYPE_7.department_id = HPD.id
                    WHERE HPD.is_active = 1 AND HPD.parent_id = ' . $rowParent->id);

                if ($query->num_rows() > 0) {

                    $htmlData .= '<div class="table-responsive">';
                    $htmlData .= '<table class="table _report">';
                    $htmlData .= '<thead>';
                    $htmlData .= '<tr>';
                    $htmlData .= '<th style="width:30px;">#</th>';
                    $htmlData .= '<th style="min-width:300px;">Гарчиг</th>';
                    $htmlData .= '<th class="text-center" style="width:80px; cursor:pointer;">Хүчин</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Хүн амь, учрал</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Хулгай</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">ИЭЧЭМЭ, танхай, булаалт</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Зам тээврийн осол</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Дээрэм</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Бусад</th>';
                    $htmlData .= '<th class="text-center" style="width:80px;">Нийт</th>';
                    $htmlData .= '</tr>';

                    $htmlData .= '</thead>';
                    $htmlData .= '<tbody>';
                    foreach ($query->result() as $key => $row) {

                        $j++;

                        $sumTotalSceneType1 = $sumTotalSceneType1 + $row->scene_type_1_count;
                        $sumTotalSceneType2 = $sumTotalSceneType2 + $row->scene_type_2_count;
                        $sumTotalSceneType3 = $sumTotalSceneType3 + $row->scene_type_3_count;
                        $sumTotalSceneType4 = $sumTotalSceneType4 + $row->scene_type_4_count;
                        $sumTotalSceneType5 = $sumTotalSceneType5 + $row->scene_type_5_count;
                        $sumTotalSceneType6 = $sumTotalSceneType6 + $row->scene_type_6_count;
                        $sumTotalSceneType7 = $sumTotalSceneType7 + $row->scene_type_7_count;
                        $sumRowAll = $row->scene_type_1_count + $row->scene_type_2_count + $row->scene_type_3_count + $row->scene_type_4_count + $row->scene_type_5_count + $row->scene_type_6_count + $row->scene_type_7_count;

                        $htmlData .= '<tr>';
                        $htmlData .= '<td>' . $j . '</td>';
                        $htmlData .= '<td class="text-left">' . $row->title . '</td>';
                        $htmlData .= '<td class="text-center ' . $row->scene_type_1_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=1&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_1_count . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->scene_type_2_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=2&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_2_count . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->scene_type_3_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=3&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_3_count . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->scene_type_4_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=4&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_4_count . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->scene_type_5_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=5&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_5_count . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->scene_type_6_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=6&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_6_count . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->scene_type_7_count_class . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=7&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_7_count . '</a></td>';
                        $htmlData .= '<td class="text-center ' . ($sumRowAll > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumRowAll > 0 ? $sumRowAll : '') . '</a></td>';
                        $htmlData .= '</tr>';

                        $isChildData = self::getReportGeneralDetailSceneChildData_model(array(
                                    'scene_type_1_count' => $row->scene_type_1_count,
                                    'scene_type_2_count' => $row->scene_type_2_count,
                                    'scene_type_3_count' => $row->scene_type_3_count,
                                    'scene_type_4_count' => $row->scene_type_4_count,
                                    'scene_type_5_count' => $row->scene_type_5_count,
                                    'scene_type_6_count' => $row->scene_type_6_count,
                                    'scene_type_7_count' => $row->scene_type_7_count,
                                    'inDate' => $param['inDate'],
                                    'outDate' => $param['outDate'],
                                    'reportMenuId' => $param['reportMenuId'],
                                    'reportModId' => $param['reportModId'],
                                    'parentId' => $row->id,
                                    'space' => 40,
                                    'num' => $j
                        ));

                        if ($isChildData) {
                            $htmlData .= $isChildData['html'];
                            $sumTotalSceneType1 = $sumTotalSceneType1 + $row->scene_type_1_count;
                            $sumTotalSceneType2 = $sumTotalSceneType2 + $row->scene_type_2_count;
                            $sumTotalSceneType3 = $sumTotalSceneType3 + $row->scene_type_3_count;
                            $sumTotalSceneType4 = $sumTotalSceneType4 + $row->scene_type_4_count;
                            $sumTotalSceneType5 = $sumTotalSceneType5 + $row->scene_type_5_count;
                            $sumTotalSceneType6 = $sumTotalSceneType6 + $row->scene_type_6_count;
                            $sumTotalSceneType7 = $sumTotalSceneType7 + $row->scene_type_7_count;
                            $sumRowAll = $isChildData['scene_type_1_count'] + $isChildData['scene_type_2_count'] + $isChildData['scene_type_3_count'] + $isChildData['scene_type_4_count'] + $isChildData['scene_type_5_count'] + $isChildData['scene_type_6_count'] + $isChildData['scene_type_7_count'];
                        }

                        $sumTotalRowAll = $sumTotalRowAll + $sumRowAll;
                    }
                    $htmlData .= '</tbody>';
                    $htmlData .= '<tfoot>';
                    $htmlData .= '<tr>';

                    $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт</td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'departmentId=all&typeId=1&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSceneType1 > 0 ? $sumTotalSceneType1 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'departmentId=all&typeId=2&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSceneType2 > 0 ? $sumTotalSceneType2 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'departmentId=all&typeId=3&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSceneType3 > 0 ? $sumTotalSceneType3 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'departmentId=all&typeId=4&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSceneType4 > 0 ? $sumTotalSceneType4 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'departmentId=all&typeId=5&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSceneType5 > 0 ? $sumTotalSceneType5 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'departmentId=all&typeId=6&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSceneType6 > 0 ? $sumTotalSceneType6 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'departmentId=all&typeId=7&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSceneType7 > 0 ? $sumTotalSceneType7 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsScene({page: 0, searchQuery: \'departmentId=all&typeId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalRowAll > 0 ? $sumTotalRowAll : '') . '</a></td>';
                    $htmlData .= '</tr>';

                    $htmlData .= '</tfoot>';
                    $htmlData .= '</table>';
                    $htmlData .= '</div>';
                }

                return array(
                    'title' => $rowParent->title . ' нэгдсэн дүн',
                    'width' => 1300,
                    'html' => $htmlData,
                    'status' => true);
            }
        }
        return array('status' => false);
    }

    public function getReportGeneralDetailSceneChildData_model($param = array()) {

        $queryStringData = $htmlData = $allTableHtml = '';

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $j = $sumTotalSceneType1 = $sumTotalSceneType2 = $sumTotalSceneType3 = $sumTotalSceneType4 = $sumTotalSceneType5 = $sumTotalSceneType6 = $sumTotalSceneType7 = $sumTotalRowAll = $sumRowAll = 0;

        $query = $this->db->query('
                    SELECT 
                        HPD.id,
                        HPD.title,
                        SCENE_TYPE_1.scene_type_1_count,
                        IF(SCENE_TYPE_1.scene_type_1_count > 0, \'_row-more\', \'\') AS scene_type_1_count_class,
                        SCENE_TYPE_2.scene_type_2_count,
                        IF(SCENE_TYPE_2.scene_type_2_count > 0, \'_row-more\', \'\') AS scene_type_2_count_class,
                        SCENE_TYPE_3.scene_type_3_count,
                        IF(SCENE_TYPE_3.scene_type_3_count > 0, \'_row-more\', \'\') AS scene_type_3_count_class,
                        SCENE_TYPE_4.scene_type_4_count,
                        IF(SCENE_TYPE_4.scene_type_4_count > 0, \'_row-more\', \'\') AS scene_type_4_count_class,
                        SCENE_TYPE_5.scene_type_5_count,
                        IF(SCENE_TYPE_5.scene_type_5_count > 0, \'_row-more\', \'\') AS scene_type_5_count_class,
                        SCENE_TYPE_6.scene_type_6_count,
                        IF(SCENE_TYPE_6.scene_type_6_count > 0, \'_row-more\', \'\') AS scene_type_6_count_class,
                        SCENE_TYPE_7.scene_type_7_count,
                        IF(SCENE_TYPE_7.scene_type_7_count > 0, \'_row-more\', \'\') AS scene_type_7_count_class
                    FROM `gaz_hr_people_department` AS HPD
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS scene_type_1_in_date,
                            COUNT(N.scene_type_id) AS scene_type_1_count
                        FROM `gaz_nifs_scene` AS N
                        WHERE N.scene_type_id = 1 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SCENE_TYPE_1 ON SCENE_TYPE_1.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS scene_type_2_in_date,
                            COUNT(N.scene_type_id) AS scene_type_2_count
                        FROM `gaz_nifs_scene` AS N
                        WHERE N.scene_type_id = 2 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SCENE_TYPE_2 ON SCENE_TYPE_2.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS scene_type_3_in_date,
                            COUNT(N.scene_type_id) AS scene_type_3_count
                        FROM `gaz_nifs_scene` AS N
                        WHERE N.scene_type_id = 3 ' . $queryStringData . '
                            GROUP BY N.department_id
                    ) AS SCENE_TYPE_3 ON SCENE_TYPE_3.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS scene_type_4_in_date,
                            COUNT(N.scene_type_id) AS scene_type_4_count
                        FROM `gaz_nifs_scene` AS N
                        WHERE N.scene_type_id = 4 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SCENE_TYPE_4 ON SCENE_TYPE_4.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS scene_type_5_in_date,
                            COUNT(N.scene_type_id) AS scene_type_5_count
                        FROM `gaz_nifs_scene` AS N
                        WHERE N.scene_type_id = 5 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SCENE_TYPE_5 ON SCENE_TYPE_5.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS scene_type_6_in_date,
                            COUNT(N.scene_type_id) AS scene_type_6_count
                        FROM `gaz_nifs_scene` AS N
                        WHERE N.scene_type_id = 6 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SCENE_TYPE_6 ON SCENE_TYPE_6.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS scene_type_7_in_date,
                            COUNT(N.scene_type_id) AS scene_type_7_count
                        FROM `gaz_nifs_scene` AS N
                        WHERE N.scene_type_id = 7 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SCENE_TYPE_7 ON SCENE_TYPE_7.department_id = HPD.id
                    WHERE HPD.is_active = 1 AND HPD.parent_id = ' . $param['parentId']);

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $j++;

                $param['scene_type_1_count'] = $param['scene_type_1_count'] + $row->scene_type_1_count;
                $param['scene_type_2_count'] = $param['scene_type_2_count'] + $row->scene_type_2_count;
                $param['scene_type_3_count'] = $param['scene_type_3_count'] + $row->scene_type_3_count;
                $param['scene_type_4_count'] = $param['scene_type_4_count'] + $row->scene_type_4_count;
                $param['scene_type_5_count'] = $param['scene_type_5_count'] + $row->scene_type_5_count;
                $param['scene_type_6_count'] = $param['scene_type_6_count'] + $row->scene_type_6_count;
                $param['scene_type_7_count'] = $param['scene_type_7_count'] + $row->scene_type_7_count;
                $sumRowAll = $row->scene_type_1_count + $row->scene_type_2_count + $row->scene_type_3_count + $row->scene_type_4_count + $row->scene_type_5_count + $row->scene_type_6_count + $row->scene_type_7_count;


                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $param['num'] . '.' . $j . '</td>';
                $htmlData .= '<td class="text-left" style="padding-left: ' . $param['space'] . 'px;">' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->scene_type_1_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=280&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_1_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->scene_type_2_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=281&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_2_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->scene_type_3_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=282&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_3_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->scene_type_4_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=283&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_4_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->scene_type_5_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=284&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_5_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->scene_type_6_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=285&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_6_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->scene_type_7_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=286&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->scene_type_7_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . ($sumRowAll > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'departmentId=' . $row->id . '&catId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumRowAll > 0 ? $sumRowAll : '') . '</a></td>';
                $htmlData .= '</tr>';

                $isChildData = self::getReportGeneralDetailSceneChildData_model(array(
                            'scene_type_1_count' => $row->scene_type_1_count,
                            'scene_type_2_count' => $row->scene_type_2_count,
                            'scene_type_3_count' => $row->scene_type_3_count,
                            'scene_type_4_count' => $row->scene_type_4_count,
                            'scene_type_5_count' => $row->scene_type_5_count,
                            'scene_type_6_count' => $row->scene_type_6_count,
                            'scene_type_7_count' => $row->scene_type_7_count,
                            'inDate' => $param['inDate'],
                            'outDate' => $param['outDate'],
                            'reportMenuId' => $param['reportMenuId'],
                            'reportModId' => $param['reportModId'],
                            'parentId' => $row->id,
                            'space' => ($param['space'] + 40),
                            'num' => $j
                ));

                if ($isChildData) {
                    $htmlData .= $isChildData['html'];
                    $sumTotalSceneType1 = $sumTotalSceneType1 + $isChildData['scene_type_1_count'];
                    $sumTotalSceneType2 = $sumTotalSceneType2 + $isChildData['scene_type_2_count'];
                    $sumTotalSceneType3 = $sumTotalSceneType3 + $isChildData['scene_type_3_count'];
                    $sumTotalSceneType4 = $sumTotalSceneType4 + $isChildData['scene_type_4_count'];
                    $sumTotalSceneType5 = $sumTotalSceneType5 + $isChildData['scene_type_5_count'];
                    $sumTotalSceneType6 = $sumTotalSceneType6 + $isChildData['scene_type_6_count'];
                    $sumTotalSceneType7 = $sumTotalSceneType7 + $isChildData['scene_type_7_count'];
                }
            }

            return array(
                'html' => $htmlData,
                'numRows' => $query->num_rows(),
                'scene_type_1_count' => $param['scene_type_1_count'],
                'scene_type_2_count' => $param['scene_type_2_count'],
                'scene_type_3_count' => $param['scene_type_3_count'],
                'scene_type_4_count' => $param['scene_type_4_count'],
                'scene_type_5_count' => $param['scene_type_5_count'],
                'scene_type_6_count' => $param['scene_type_6_count'],
                'scene_type_7_count' => $param['scene_type_7_count']);
        }

        return false;
    }

    public function getReportGeneralDetailForensicData_model($param = array()) {

        $queryStringData = $htmlData = $allTableHtml = '';

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $queryParent = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title
            FROM `gaz_hr_people_department` AS HPD
            WHERE HPD.is_active = 1 AND HPD.id IN (' . $param['departmentId'] . ')');

        if ($queryParent->num_rows() > 0) {

            $i = $sumTotalCrime0Dep = $sumTotalCrime1Dep = 0;
            foreach ($queryParent->result() as $keyParent => $rowParent) {

                ++$i;
                $j = $sumTotalFileFolder = $sumTotalAnatomy = $sumTotalDoctorView = $sumRow = $sumTotalAllRow = 0;

                $htmlData .= '<h6>' . $i . '.' . $rowParent->title . '</h6>';

                $query = $this->db->query('
                    SELECT 
                        HPD.id,
                        HPD.title,
                        DOCTOR_VIEW.doctor_view_count,
                        IF(DOCTOR_VIEW.doctor_view_count > 0, \'_row-more\', \'\') AS doctor_view_count_class,
                        FILE_FOLDER.file_folder_count,
                        IF(FILE_FOLDER.file_folder_count > 0, \'_row-more\', \'\') AS file_folder_count_class,
                        ANATOMY.anatomy_count,
                        IF(ANATOMY.anatomy_count > 0, \'_row-more\', \'\') AS anatomy_count_class
                    FROM `gaz_hr_people_department` AS HPD
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS doctor_view_in_date,
                            COUNT(N.department_id) AS doctor_view_count
                        FROM `gaz_nifs_doctor_view` AS N 
                        WHERE 1 = 1 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS DOCTOR_VIEW ON DOCTOR_VIEW.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS file_folder_in_date,
                            COUNT(N.department_id) AS file_folder_count
                        FROM `gaz_nifs_file_folder` AS N 
                        WHERE 1 = 1 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS FILE_FOLDER ON FILE_FOLDER.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS anatomy_in_date,
                            COUNT(N.department_id) AS anatomy_count
                        FROM `gaz_nifs_anatomy` AS N 
                        WHERE 1 = 1 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS ANATOMY ON ANATOMY.department_id = HPD.id
                    WHERE HPD.is_active = 1 AND HPD.parent_id = ' . $rowParent->id);

                if ($query->num_rows() > 0) {

                    $htmlData .= '<div class="table-responsive">';
                    $htmlData .= '<table class="table _report">';
                    $htmlData .= '<thead>';
                    $htmlData .= '<tr>';
                    $htmlData .= '<th style="width:30px;">#</th>';
                    $htmlData .= '<th style="min-width:300px;">Гарчиг</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Эмчийн үзлэг</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Хавтаст хэрэг</th>';
                    $htmlData .= '<th class="text-center" style="width:120px; cursor:pointer;">Задлан</th>';
                    $htmlData .= '<th class="text-center" style="width:80px;">Нийт</th>';
                    $htmlData .= '</tr>';

                    $htmlData .= '</thead>';
                    $htmlData .= '<tbody>';
                    foreach ($query->result() as $key => $row) {

                        $j++;

                        $sumTotalDoctorView = $sumTotalDoctorView + $row->doctor_view_count;
                        $sumTotalFileFolder = $sumTotalFileFolder + $row->file_folder_count;
                        $sumTotalAnatomy = $sumTotalAnatomy + $row->anatomy_count;
                        $sumRow = $row->doctor_view_count + $row->file_folder_count + $row->anatomy_count;
                        $sumTotalAllRow = $sumTotalAllRow + $sumRow;

                        $htmlData .= '<tr>';
                        $htmlData .= '<td>' . $j . '</td>';
                        $htmlData .= '<td class="text-left">' . $row->title . '</td>';
                        $htmlData .= '<td class="text-center ' . $row->doctor_view_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'departmentId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->doctor_view_count . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->file_folder_count_class . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'departmentId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->file_folder_count . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->anatomy_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'departmentId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->anatomy_count . '</a></td>';

                        $htmlData .= '<td class="text-center">' . ($sumRow > 0 ? $sumRow : '') . '</td>';
                        $htmlData .= '</tr>';

                        $isChildData = self::getReportGeneralDetailForensicChildData_model(array(
                                    'doctor_view_count' => $row->doctor_view_count,
                                    'file_folder_count' => $row->file_folder_count,
                                    'anatomy_count' => $row->anatomy_count,
                                    'inDate' => $param['inDate'],
                                    'outDate' => $param['outDate'],
                                    'reportMenuId' => $param['reportMenuId'],
                                    'reportModId' => $param['reportModId'],
                                    'parentId' => $row->id,
                                    'space' => 40,
                                    'num' => $j
                        ));

                        if ($isChildData) {
                            $htmlData .= $isChildData['html'];
                            $sumTotalDoctorView = $sumTotalDoctorView + $isChildData['doctor_view_count'];
                            $sumTotalFileFolder = $sumTotalFileFolder + $isChildData['file_folder_count'];
                            $sumTotalAnatomy = $sumTotalAnatomy + $isChildData['anatomy_count'];
                            $sumTotalAllRow = $sumTotalAllRow + $isChildData['doctor_view_count'] + $isChildData['file_folder_count'] + $isChildData['anatomy_count'];
                        }
                    }
                    $htmlData .= '</tbody>';
                    $htmlData .= '<tfoot>';
                    $htmlData .= '<tr>';
                    $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт</td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'departmentId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalDoctorView > 0 ? $sumTotalDoctorView : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'departmentId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalFileFolder > 0 ? $sumTotalFileFolder : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'departmentId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalAnatomy > 0 ? $sumTotalAnatomy : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot">' . ($sumTotalAllRow > 0 ? $sumTotalAllRow : '') . '</td>';
                    $htmlData .= '</tr>';

                    $htmlData .= '</tfoot>';
                    $htmlData .= '</table>';
                    $htmlData .= '</div>';
                }

                return array(
                    'title' => $rowParent->title . ' нэгдсэн дүн',
                    'width' => 1300,
                    'html' => $htmlData,
                    'status' => true);
            }
        }
        return array('status' => false);
    }

    public function getReportGeneralDetailForensicChildData_model($param = array()) {

        $queryStringData = $htmlData = $allTableHtml = '';

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $j = $sumTotalFileFolder = $sumTotalAnatomy = $sumTotalDoctorView = $sumRow = $sumTotalAllRow = 0;

        $query = $this->db->query('
                    SELECT 
                        HPD.id,
                        HPD.title,
                        DOCTOR_VIEW.doctor_view_count,
                        IF(DOCTOR_VIEW.doctor_view_count > 0, \'_row-more\', \'\') AS doctor_view_count_class,
                        FILE_FOLDER.file_folder_count,
                        IF(FILE_FOLDER.file_folder_count > 0, \'_row-more\', \'\') AS file_folder_count_class,
                        ANATOMY.anatomy_count,
                        IF(ANATOMY.anatomy_count > 0, \'_row-more\', \'\') AS anatomy_count_class
                    FROM `gaz_hr_people_department` AS HPD
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS doctor_view_in_date,
                            COUNT(N.department_id) AS doctor_view_count
                        FROM `gaz_nifs_doctor_view` AS N 
                        WHERE 1 = 1 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS DOCTOR_VIEW ON DOCTOR_VIEW.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS file_folder_in_date,
                            COUNT(N.department_id) AS file_folder_count
                        FROM `gaz_nifs_file_folder` AS N 
                        WHERE 1 = 1 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS FILE_FOLDER ON FILE_FOLDER.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS anatomy_in_date,
                            COUNT(N.department_id) AS anatomy_count
                        FROM `gaz_nifs_anatomy` AS N 
                        WHERE 1 = 1 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS ANATOMY ON ANATOMY.department_id = HPD.id
                    WHERE HPD.is_active = 1 AND HPD.parent_id = ' . $param['parentId']);

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $j++;

                $sumTotalDoctorView = $sumTotalDoctorView + $row->doctor_view_count;
                $sumTotalFileFolder = $sumTotalFileFolder + $row->file_folder_count;
                $sumTotalAnatomy = $sumTotalAnatomy + $row->anatomy_count;
                $sumRow = $row->doctor_view_count + $row->file_folder_count + $row->anatomy_count;
                $sumTotalAllRow = $sumTotalAllRow + $sumRow;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $param['num'] . '.' . $j . '</td>';
                $htmlData .= '<td class="text-left" style="padding-left:' . $param['space'] . 'px;">' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->doctor_view_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'departmentId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->doctor_view_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->file_folder_count_class . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'departmentId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->file_folder_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->anatomy_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'departmentId=' . $row->id . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->anatomy_count . '</a></td>';

                $htmlData .= '<td class="text-center">' . ($sumRow > 0 ? $sumRow : '') . '</td>';
                $htmlData .= '</tr>';

                $isChildData = self::getReportGeneralDetailForensicChildData_model(array(
                            'doctor_view_count' => $row->doctor_view_count,
                            'file_folder_count' => $row->file_folder_count,
                            'anatomy_count' => $row->anatomy_count,
                            'inDate' => $param['inDate'],
                            'outDate' => $param['outDate'],
                            'reportMenuId' => $param['reportMenuId'],
                            'reportModId' => $param['reportModId'],
                            'parentId' => $row->id,
                            'space' => 40,
                            'num' => $j
                ));

                if ($isChildData) {
                    $htmlData .= $isChildData['html'];
                    $sumTotalDoctorView = $sumTotalDoctorView + $isChildData['doctor_view_count'];
                    $sumTotalFileFolder = $sumTotalFileFolder + $isChildData['file_folder_count'];
                    $sumTotalAnatomy = $sumTotalAnatomy + $isChildData['anatomy_count'];
                    $sumTotalAllRow = $sumTotalAllRow + $isChildData['doctor_view_count'] + $isChildData['file_folder_count'] + $isChildData['anatomy_count'];
                }
            }

            return array('html' => $htmlData, 'doctor_view_count' => $sumTotalDoctorView, 'file_folder_count' => $sumTotalFileFolder, 'anatomy_count' => $sumTotalAnatomy);
        }
        return false;
    }

    public function getReportGeneralDetailExtraData_model($param = array()) {

        $queryStringData = $htmlData = $allTableHtml = '';

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $queryParent = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title
            FROM `gaz_hr_people_department` AS HPD
            WHERE HPD.is_active = 1 AND HPD.id IN (' . $param['departmentId'] . ')');

        if ($queryParent->num_rows() > 0) {

            $i = 0;
            foreach ($queryParent->result() as $keyParent => $rowParent) {

                ++$i;
                $j = $sumTotalExtraType8 = $sumTotalExtraType9 = $sumTotalExtraType10 = $sumTotalExtraType11 = $sumTotalExtraType12 = $sumTotalExtraType13 = $sumRow = $sumTotalAllRow = 0;

                $htmlData .= '<h6>' . $i . '.' . $rowParent->title . '</h6>';

                $query = $this->db->query('
                    SELECT 
                        HPD.id,
                        HPD.title,
                        EXTRA_TYPE_8.extra_type_8_count,
                        IF(EXTRA_TYPE_8.extra_type_8_count > 0, \'_row-more\', \'\') AS extra_type_8_count_class,
                        EXTRA_TYPE_9.extra_type_9_count,
                        IF(EXTRA_TYPE_9.extra_type_9_count > 0, \'_row-more\', \'\') AS extra_type_9_count_class,
                        EXTRA_TYPE_10.extra_type_10_count,
                        IF(EXTRA_TYPE_10.extra_type_10_count > 0, \'_row-more\', \'\') AS extra_type_10_count_class,
                        EXTRA_TYPE_11.extra_type_11_count,
                        IF(EXTRA_TYPE_11.extra_type_11_count > 0, \'_row-more\', \'\') AS extra_type_11_count_class,
                        EXTRA_TYPE_12.extra_type_12_count,
                        IF(EXTRA_TYPE_12.extra_type_12_count > 0, \'_row-more\', \'\') AS extra_type_12_count_class,
                        EXTRA_TYPE_13.extra_type_13_count,
                        IF(EXTRA_TYPE_13.extra_type_13_count > 0, \'_row-more\', \'\') AS extra_type_13_count_class
                    FROM `gaz_hr_people_department` AS HPD
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS extra_type_8_in_date,
                            COUNT(N.type_id) AS extra_type_8_count
                        FROM `gaz_nifs_extra` AS N
                        WHERE N.type_id = 8 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS EXTRA_TYPE_8 ON EXTRA_TYPE_8.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS extra_type_9_in_date,
                            COUNT(N.type_id) AS extra_type_9_count
                        FROM `gaz_nifs_extra` AS N
                        WHERE N.type_id = 9 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS EXTRA_TYPE_9 ON EXTRA_TYPE_9.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS extra_type_10_in_date,
                            COUNT(N.type_id) AS extra_type_10_count
                        FROM `gaz_nifs_extra` AS N
                        WHERE N.type_id = 10 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS EXTRA_TYPE_10 ON EXTRA_TYPE_10.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS extra_type_11_in_date,
                            COUNT(N.type_id) AS extra_type_11_count
                        FROM `gaz_nifs_extra` AS N
                        WHERE N.type_id = 11 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS EXTRA_TYPE_11 ON EXTRA_TYPE_11.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS extra_type_12_in_date,
                            COUNT(N.type_id) AS extra_type_12_count
                        FROM `gaz_nifs_extra` AS N
                        WHERE N.type_id = 12 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS EXTRA_TYPE_12 ON EXTRA_TYPE_12.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS extra_type_13_in_date,
                            COUNT(N.type_id) AS extra_type_13_count
                        FROM `gaz_nifs_extra` AS N
                        WHERE N.type_id = 13 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS EXTRA_TYPE_13 ON EXTRA_TYPE_13.department_id = HPD.id
                    WHERE HPD.is_active = 1 AND HPD.parent_id = ' . $rowParent->id);

                if ($query->num_rows() > 0) {

                    $htmlData .= '<div class="table-responsive">';
                    $htmlData .= '<table class="table _report">';
                    $htmlData .= '<thead>';
                    $htmlData .= '<tr>';
                    $htmlData .= '<th style="width:30px;">#</th>';
                    $htmlData .= '<th style="min-width:300px;">Гарчиг</th>';
                    $htmlData .= '<th class="text-center" style="width:100px; cursor:pointer;">Биологи</th>';
                    $htmlData .= '<th class="text-center" style="width:100px; cursor:pointer;">ДНХ</th>';
                    $htmlData .= '<th class="text-center" style="width:100px; cursor:pointer;">Бактериологи</th>';
                    $htmlData .= '<th class="text-center" style="width:100px; cursor:pointer;">Хими</th>';
                    $htmlData .= '<th class="text-center" style="width:100px; cursor:pointer;">Физик</th>';
                    $htmlData .= '<th class="text-center" style="width:100px; cursor:pointer;">Бусад</th>';
                    $htmlData .= '<th class="text-center" style="width:80px;">Нийт</th>';
                    $htmlData .= '</tr>';

                    $htmlData .= '</thead>';
                    $htmlData .= '<tbody>';
                    foreach ($query->result() as $key => $row) {

                        $j++;

                        $sumTotalExtraType8 = $sumTotalExtraType8 + $row->extra_type_8_count;
                        $sumTotalExtraType9 = $sumTotalExtraType9 + $row->extra_type_9_count;
                        $sumTotalExtraType10 = $sumTotalExtraType10 + $row->extra_type_10_count;
                        $sumTotalExtraType11 = $sumTotalExtraType11 + $row->extra_type_11_count;
                        $sumTotalExtraType12 = $sumTotalExtraType12 + $row->extra_type_12_count;
                        $sumTotalExtraType13 = $sumTotalExtraType13 + $row->extra_type_13_count;
                        $sumRow = $row->extra_type_8_count + $row->extra_type_9_count + $row->extra_type_10_count + $row->extra_type_11_count + $row->extra_type_12_count + $row->extra_type_13_count;
                        $sumTotalAllRow = $sumTotalAllRow + $sumRow;

                        $htmlData .= '<tr>';
                        $htmlData .= '<td>' . $j . '</td>';
                        $htmlData .= '<td class="text-left">' . $row->title . '</td>';
                        $htmlData .= '<td class="text-center ' . $row->extra_type_8_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=8&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->extra_type_8_count . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->extra_type_9_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=9&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->extra_type_9_count . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->extra_type_10_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=10&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->extra_type_10_count . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->extra_type_11_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=11&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->extra_type_11_count . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->extra_type_12_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=12&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->extra_type_12_count . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->extra_type_13_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=13&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->extra_type_13_count . '</a></td>';
                        $htmlData .= '<td class="text-center ' . ($sumRow > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumRow > 0 ? $sumRow : '') . '</a></td>';
                        $htmlData .= '</tr>';

                        $isChildData = self::getReportGeneralDetailExtraChildData_model(array(
                                    'extra_type_8_count' => $row->extra_type_8_count,
                                    'extra_type_9_count' => $row->extra_type_9_count,
                                    'extra_type_10_count' => $row->extra_type_10_count,
                                    'extra_type_11_count' => $row->extra_type_11_count,
                                    'extra_type_12_count' => $row->extra_type_12_count,
                                    'extra_type_13_count' => $row->extra_type_13_count,
                                    'inDate' => $param['inDate'],
                                    'outDate' => $param['outDate'],
                                    'reportMenuId' => $param['reportMenuId'],
                                    'reportModId' => $param['reportModId'],
                                    'parentId' => $row->id,
                                    'space' => 40,
                                    'num' => $j
                        ));

                        if ($isChildData) {
                            $htmlData .= $isChildData['html'];
                            $sumTotalExtraType8 = $sumTotalExtraType8 + $isChildData['extra_type_8_count'];
                            $sumTotalExtraType9 = $sumTotalExtraType9 + $isChildData['extra_type_9_count'];
                            $sumTotalExtraType10 = $sumTotalExtraType10 + $isChildData['extra_type_10_count'];
                            $sumTotalExtraType11 = $sumTotalExtraType11 + $isChildData['extra_type_11_count'];
                            $sumTotalExtraType12 = $sumTotalExtraType12 + $isChildData['extra_type_12_count'];
                            $sumTotalExtraType13 = $sumTotalExtraType13 + $isChildData['extra_type_13_count'];

                            $sumTotalAllRow = $sumTotalAllRow + $isChildData['extra_type_8_count'] + $isChildData['extra_type_9_count'] + $isChildData['extra_type_10_count'] + $isChildData['extra_type_11_count'] + $isChildData['extra_type_12_count'] + $isChildData['extra_type_13_count'];
                        }
                    }
                    $htmlData .= '</tbody>';
                    $htmlData .= '<tfoot>';
                    $htmlData .= '<tr>';
                    $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт</td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $rowParent->id . '&typeId=8&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalExtraType8 > 0 ? $sumTotalExtraType8 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $rowParent->id . '&typeId=9&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalExtraType9 > 0 ? $sumTotalExtraType9 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $rowParent->id . '&typeId=10&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalExtraType10 > 0 ? $sumTotalExtraType10 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $rowParent->id . '&typeId=11&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalExtraType11 > 0 ? $sumTotalExtraType11 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $rowParent->id . '&typeId=12&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalExtraType12 > 0 ? $sumTotalExtraType12 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $rowParent->id . '&typeId=13&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalExtraType13 > 0 ? $sumTotalExtraType13 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $rowParent->id . '&typeId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalAllRow > 0 ? $sumTotalAllRow : '') . '</a></td>';
                    $htmlData .= '</tr>';

                    $htmlData .= '</tfoot>';
                    $htmlData .= '</table>';
                    $htmlData .= '</div>';
                }

                return array(
                    'title' => $rowParent->title . ' нэгдсэн дүн',
                    'width' => 1200,
                    'html' => $htmlData,
                    'status' => true);
            }
        }
        return array('status' => false);
    }

    public function getReportGeneralDetailExtraChildData_model($param = array()) {

        $queryStringData = $htmlData = $allTableHtml = '';

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $j = $sumTotalExtraType8 = $sumTotalExtraType9 = $sumTotalExtraType10 = $sumTotalExtraType11 = $sumTotalExtraType12 = $sumTotalExtraType13 = $sumRow = $sumTotalAllRow = 0;

        $query = $this->db->query('
                    SELECT 
                        HPD.id,
                        HPD.title,
                        EXTRA_TYPE_8.extra_type_8_count,
                        IF(EXTRA_TYPE_8.extra_type_8_count > 0, \'_row-more\', \'\') AS extra_type_8_count_class,
                        EXTRA_TYPE_9.extra_type_9_count,
                        IF(EXTRA_TYPE_9.extra_type_9_count > 0, \'_row-more\', \'\') AS extra_type_9_count_class,
                        EXTRA_TYPE_10.extra_type_10_count,
                        IF(EXTRA_TYPE_10.extra_type_10_count > 0, \'_row-more\', \'\') AS extra_type_10_count_class,
                        EXTRA_TYPE_11.extra_type_11_count,
                        IF(EXTRA_TYPE_11.extra_type_11_count > 0, \'_row-more\', \'\') AS extra_type_11_count_class,
                        EXTRA_TYPE_12.extra_type_12_count,
                        IF(EXTRA_TYPE_12.extra_type_12_count > 0, \'_row-more\', \'\') AS extra_type_12_count_class,
                        EXTRA_TYPE_13.extra_type_13_count,
                        IF(EXTRA_TYPE_13.extra_type_13_count > 0, \'_row-more\', \'\') AS extra_type_13_count_class
                    FROM `gaz_hr_people_department` AS HPD
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS extra_type_8_in_date,
                            COUNT(N.type_id) AS extra_type_8_count
                        FROM `gaz_nifs_extra` AS N
                        WHERE N.type_id = 8 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS EXTRA_TYPE_8 ON EXTRA_TYPE_8.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS extra_type_9_in_date,
                            COUNT(N.type_id) AS extra_type_9_count
                        FROM `gaz_nifs_extra` AS N
                        WHERE N.type_id = 9 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS EXTRA_TYPE_9 ON EXTRA_TYPE_9.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS extra_type_10_in_date,
                            COUNT(N.type_id) AS extra_type_10_count
                        FROM `gaz_nifs_extra` AS N
                        WHERE N.type_id = 10 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS EXTRA_TYPE_10 ON EXTRA_TYPE_10.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS extra_type_11_in_date,
                            COUNT(N.type_id) AS extra_type_11_count
                        FROM `gaz_nifs_extra` AS N
                        WHERE N.type_id = 11 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS EXTRA_TYPE_11 ON EXTRA_TYPE_11.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS extra_type_12_in_date,
                            COUNT(N.type_id) AS extra_type_12_count
                        FROM `gaz_nifs_extra` AS N
                        WHERE N.type_id = 12 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS EXTRA_TYPE_12 ON EXTRA_TYPE_12.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS extra_type_13_in_date,
                            COUNT(N.type_id) AS extra_type_13_count
                        FROM `gaz_nifs_extra` AS N
                        WHERE N.type_id = 13 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS EXTRA_TYPE_13 ON EXTRA_TYPE_13.department_id = HPD.id
                    WHERE HPD.is_active = 1 AND HPD.parent_id = ' . $param['parentId']);

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $j++;

                $sumTotalExtraType8 = $sumTotalExtraType8 + $row->extra_type_8_count;
                $sumTotalExtraType9 = $sumTotalExtraType9 + $row->extra_type_9_count;
                $sumTotalExtraType10 = $sumTotalExtraType10 + $row->extra_type_10_count;
                $sumTotalExtraType11 = $sumTotalExtraType11 + $row->extra_type_11_count;
                $sumTotalExtraType12 = $sumTotalExtraType12 + $row->extra_type_12_count;
                $sumTotalExtraType13 = $sumTotalExtraType13 + $row->extra_type_13_count;
                $sumRow = $row->extra_type_8_count + $row->extra_type_9_count + $row->extra_type_10_count + $row->extra_type_11_count + $row->extra_type_12_count + $row->extra_type_13_count;
                $sumTotalAllRow = $sumTotalAllRow + $sumRow;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $param['num'] . '.' . $j . '</td>';
                $htmlData .= '<td class="text-left" style="padding-left:' . $param['space'] . 'px;">' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->extra_type_8_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=8&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->extra_type_8_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->extra_type_9_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=9&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->extra_type_9_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->extra_type_10_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=10&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->extra_type_10_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->extra_type_11_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=11&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->extra_type_11_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->extra_type_12_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=12&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->extra_type_12_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->extra_type_13_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=13&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->extra_type_13_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . ($sumRow > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumRow > 0 ? $sumRow : '') . '</a></td>';
                $htmlData .= '</tr>';

                $isChildData = self::getReportGeneralDetailExtraChildData_model(array(
                            'extra_type_8_count' => $row->extra_type_8_count,
                            'extra_type_9_count' => $row->extra_type_9_count,
                            'extra_type_10_count' => $row->extra_type_10_count,
                            'extra_type_11_count' => $row->extra_type_11_count,
                            'extra_type_12_count' => $row->extra_type_12_count,
                            'extra_type_13_count' => $row->extra_type_13_count,
                            'inDate' => $param['inDate'],
                            'outDate' => $param['outDate'],
                            'reportMenuId' => $param['reportMenuId'],
                            'reportModId' => $param['reportModId'],
                            'parentId' => $row->id,
                            'space' => ($param['space'] + 40),
                            'num' => $param['num'] . '.' . $j
                ));

                if ($isChildData) {
                    $htmlData .= $isChildData['html'];
                    $sumTotalExtraType8 = $sumTotalExtraType8 + $isChildData['extra_type_8_count'];
                    $sumTotalExtraType9 = $sumTotalExtraType9 + $isChildData['extra_type_9_count'];
                    $sumTotalExtraType10 = $sumTotalExtraType10 + $isChildData['extra_type_10_count'];
                    $sumTotalExtraType11 = $sumTotalExtraType11 + $isChildData['extra_type_11_count'];
                    $sumTotalExtraType12 = $sumTotalExtraType12 + $isChildData['extra_type_12_count'];
                    $sumTotalExtraType13 = $sumTotalExtraType13 + $isChildData['extra_type_13_count'];

                    $sumTotalAllRow = $sumTotalAllRow + $isChildData['doctor_view_count'] + $isChildData['file_folder_count'] + $isChildData['anatomy_count'];
                }
            }

            return array('html' => $htmlData, 'extra_type_8_count' => $sumTotalExtraType8, 'extra_type_9_count' => $sumTotalExtraType9, 'extra_type_10_count' => $sumTotalExtraType10, 'extra_type_11_count' => $sumTotalExtraType11, 'extra_type_11_count' => $sumTotalExtraType11, 'extra_type_12_count' => $sumTotalExtraType12, 'extra_type_13_count' => $sumTotalExtraType13);
        }
        return false;
    }

    public function getReportGeneralDetailEconomyData_model($param = array()) {

        $queryStringData = $htmlData = $allTableHtml = '';

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $queryParent = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title
            FROM `gaz_hr_people_department` AS HPD
            WHERE HPD.is_active = 1 AND HPD.id IN (' . $param['departmentId'] . ')');

        if ($queryParent->num_rows() > 0) {

            $i = 0;
            foreach ($queryParent->result() as $keyParent => $rowParent) {

                ++$i;
                $j = $sumTotalEconomyType21 = $sumRow = $sumTotalAllRow = 0;

                $htmlData .= '<h6>' . $i . '.' . $rowParent->title . '</h6>';

                $query = $this->db->query('
                    SELECT 
                        HPD.id,
                        HPD.title,
                        ECONOMY_TYPE_21.economy_type_21_count,
                        IF(ECONOMY_TYPE_21.economy_type_21_count > 0, \'_row-more\', \'\') AS economy_type_21_count_class
                    FROM `gaz_hr_people_department` AS HPD
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS economy_type_21_in_date,
                            COUNT(N.type_id) AS economy_type_21_count
                        FROM `gaz_nifs_economy` AS N
                        WHERE N.type_id = 21 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS ECONOMY_TYPE_21 ON ECONOMY_TYPE_21.department_id = HPD.id
                    WHERE HPD.is_active = 1 AND HPD.parent_id = ' . $rowParent->id);

                if ($query->num_rows() > 0) {

                    $htmlData .= '<div class="table-responsive">';
                    $htmlData .= '<table class="table _report">';
                    $htmlData .= '<thead>';
                    $htmlData .= '<tr>';
                    $htmlData .= '<th style="width:30px;">#</th>';
                    $htmlData .= '<th style="min-width:300px;">Гарчиг</th>';
                    $htmlData .= '<th class="text-center" style="width:100px; cursor:pointer;">Эдийн засаг</th>';
                    $htmlData .= '<th class="text-center" style="width:80px;">Нийт</th>';
                    $htmlData .= '</tr>';

                    $htmlData .= '</thead>';
                    $htmlData .= '<tbody>';
                    foreach ($query->result() as $key => $row) {

                        $j++;

                        $sumTotalEconomyType21 = $sumTotalEconomyType21 + $row->economy_type_21_count;
                        $sumRow = $row->economy_type_21_count;
                        $sumTotalAllRow = $sumTotalAllRow + $sumRow;

                        $htmlData .= '<tr>';
                        $htmlData .= '<td>' . $j . '</td>';
                        $htmlData .= '<td class="text-left">' . $row->title . '</td>';
                        $htmlData .= '<td class="text-center ' . $row->economy_type_21_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=8&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->economy_type_21_count . '</a></td>';
                        $htmlData .= '<td class="text-center ' . ($sumRow > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumRow > 0 ? $sumRow : '') . '</a></td>';
                        $htmlData .= '</tr>';

                        $isChildData = self::getReportGeneralDetailEconomyChildData_model(array(
                                    'economy_type_21_count' => $row->economy_type_21_count,
                                    'inDate' => $param['inDate'],
                                    'outDate' => $param['outDate'],
                                    'reportMenuId' => $param['reportMenuId'],
                                    'reportModId' => $param['reportModId'],
                                    'parentId' => $row->id,
                                    'space' => 40,
                                    'num' => $j
                        ));

                        if ($isChildData) {
                            $htmlData .= $isChildData['html'];
                            $sumTotalEconomyType21 = $sumTotalEconomyType21 + $isChildData['economy_type_21_count'];

                            $sumTotalAllRow = $sumTotalAllRow + $isChildData['economy_type_21_count'];
                        }
                    }
                    $htmlData .= '</tbody>';
                    $htmlData .= '<tfoot>';
                    $htmlData .= '<tr>';
                    $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт</td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $rowParent->id . '&typeId=8&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalEconomyType21 > 0 ? $sumTotalEconomyType21 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $rowParent->id . '&typeId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalAllRow > 0 ? $sumTotalAllRow : '') . '</a></td>';
                    $htmlData .= '</tr>';

                    $htmlData .= '</tfoot>';
                    $htmlData .= '</table>';
                    $htmlData .= '</div>';
                }

                return array(
                    'title' => $rowParent->title . ' нэгдсэн дүн',
                    'width' => 1200,
                    'html' => $htmlData,
                    'status' => true);
            }
        }
        return array('status' => false);
    }

    public function getReportGeneralDetailEconomyChildData_model($param = array()) {

        $queryStringData = $htmlData = $allTableHtml = '';

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $j = $sumTotalEconomyType21 = $sumRow = $sumTotalAllRow = 0;

        $query = $this->db->query('
                    SELECT 
                        HPD.id,
                        HPD.title,
                        ECONOMY_TYPE_21.economy_type_21_count,
                        IF(ECONOMY_TYPE_21.economy_type_21_count > 0, \'_row-more\', \'\') AS economy_type_21_count_class
                    FROM `gaz_hr_people_department` AS HPD
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS economy_type_21_in_date,
                            COUNT(N.type_id) AS economy_type_21_count
                        FROM `gaz_nifs_economy` AS N
                        WHERE N.type_id = 21 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS ECONOMY_TYPE_21 ON ECONOMY_TYPE_21.department_id = HPD.id
                    WHERE HPD.is_active = 1 AND HPD.parent_id = ' . $param['parentId']);

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $j++;

                $sumTotalEconomyType21 = $sumTotalEconomyType21 + $row->economy_type_21_count;
                $sumRow = $row->economy_type_21_count;
                $sumTotalAllRow = $sumTotalAllRow + $sumRow;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $param['num'] . '.' . $j . '</td>';
                $htmlData .= '<td class="text-left" style="padding-left:' . $param['space'] . 'px;">' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->economy_type_21_count_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=8&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->economy_type_21_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . ($sumRow > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumRow > 0 ? $sumRow : '') . '</a></td>';
                $htmlData .= '</tr>';

                $isChildData = self::getReportGeneralDetailEconomyChildData_model(array(
                            'economy_type_21_count' => $row->economy_type_21_count,
                            'inDate' => $param['inDate'],
                            'outDate' => $param['outDate'],
                            'reportMenuId' => $param['reportMenuId'],
                            'reportModId' => $param['reportModId'],
                            'parentId' => $row->id,
                            'space' => ($param['space'] + 40),
                            'num' => $param['num'] . '.' . $j
                ));

                if ($isChildData) {
                    $htmlData .= $isChildData['html'];
                    $sumTotalEconomyType21 = $sumTotalEconomyType21 + $isChildData['economy_type_21_count'];
                }
            }

            return array('html' => $htmlData, 'economy_type_21_count' => $sumTotalEconomyType21);
        }
        return false;
    }

    public function getReportGeneralDetailSendDocumentData_model($param = array()) {

        $queryStringData = $htmlData = $allTableHtml = '';

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $queryParent = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title
            FROM `gaz_hr_people_department` AS HPD
            WHERE HPD.is_active = 1 AND HPD.id IN (' . $param['departmentId'] . ')');

        if ($queryParent->num_rows() > 0) {

            $i = 0;
            foreach ($queryParent->result() as $keyParent => $rowParent) {

                ++$i;
                $j = $sumTotalSendDocumentType8 = $sumTotalSendDocumentType10 = $sumTotalSendDocumentType11 = $sumRow = $sumTotalAllRow = 0;

                $htmlData .= '<h6>' . $i . '.' . $rowParent->title . '</h6>';

                $query = $this->db->query('
                    SELECT 
                        HPD.id,
                        HPD.title,
                        SEND_DOC_TYPE_8.send_document_type_8_count,
                        IF(SEND_DOC_TYPE_8.send_document_type_8_count > 0, \'_row-more\', \'\') AS send_document_type_8_count_class,
                        SEND_DOC_TYPE_10.send_document_type_10_count,
                        IF(SEND_DOC_TYPE_10.send_document_type_10_count > 0, \'_row-more\', \'\') AS send_document_type_10_count_class,
                        SEND_DOC_TYPE_11.send_document_type_11_count,
                        IF(SEND_DOC_TYPE_11.send_document_type_11_count > 0, \'_row-more\', \'\') AS send_document_type_11_count_class
                    FROM `gaz_hr_people_department` AS HPD
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS send_document_type_8_in_date,
                            COUNT(N.type_id) AS send_document_type_8_count
                        FROM `gaz_nifs_send_doc` AS N
                        WHERE N.type_id = 8 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SEND_DOC_TYPE_8 ON SEND_DOC_TYPE_8.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS send_document_type_10_in_date,
                            COUNT(N.type_id) AS send_document_type_10_count
                        FROM `gaz_nifs_send_doc` AS N
                        WHERE N.type_id = 10 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SEND_DOC_TYPE_10 ON SEND_DOC_TYPE_10.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS send_document_type_11_in_date,
                            COUNT(N.type_id) AS send_document_type_11_count
                        FROM `gaz_nifs_send_doc` AS N
                        WHERE N.type_id = 11  ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SEND_DOC_TYPE_11 ON SEND_DOC_TYPE_11.department_id = HPD.id
                    WHERE HPD.is_active = 1 AND HPD.parent_id = ' . $rowParent->id);

                if ($query->num_rows() > 0) {

                    $htmlData .= '<div class="table-responsive">';
                    $htmlData .= '<table class="table _report">';
                    $htmlData .= '<thead>';
                    $htmlData .= '<tr>';
                    $htmlData .= '<th style="width:30px;">#</th>';
                    $htmlData .= '<th style="min-width:300px;">Гарчиг</th>';
                    $htmlData .= '<th class="text-center" style="width:100px; cursor:pointer;">Хими</th>';
                    $htmlData .= '<th class="text-center" style="width:100px; cursor:pointer;">Биологи</th>';
                    $htmlData .= '<th class="text-center" style="width:100px; cursor:pointer;">Бактериологи</th>';
                    $htmlData .= '<th class="text-center" style="width:80px;">Нийт</th>';
                    $htmlData .= '</tr>';

                    $htmlData .= '</thead>';
                    $htmlData .= '<tbody>';
                    foreach ($query->result() as $key => $row) {

                        $j++;

                        $sumTotalSendDocumentType8 = $sumTotalSendDocumentType8 + $row->send_document_type_8_count;
                        $sumTotalSendDocumentType10 = $sumTotalSendDocumentType10 + $row->send_document_type_10_count;
                        $sumTotalSendDocumentType11 = $sumTotalSendDocumentType11 + $row->send_document_type_11_count;
                        $sumRow = $row->send_document_type_8_count + $row->send_document_type_10_count + $row->send_document_type_11_count;
                        $sumTotalAllRow = $sumTotalAllRow + $sumRow;

                        $htmlData .= '<tr>';
                        $htmlData .= '<td>' . $j . '</td>';
                        $htmlData .= '<td class="text-left">' . $row->title . '</td>';
                        $htmlData .= '<td class="text-center ' . $row->send_document_type_8_count_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=8&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->send_document_type_8_count . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->send_document_type_10_count_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=10&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->send_document_type_10_count . '</a></td>';
                        $htmlData .= '<td class="text-center ' . $row->send_document_type_11_count_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=11&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->send_document_type_11_count . '</a></td>';
                        $htmlData .= '<td class="text-center ' . ($sumRow > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumRow > 0 ? $sumRow : '') . '</a></td>';
                        $htmlData .= '</tr>';

                        $isChildData = self::getReportGeneralDetailSendDocumentChildData_model(array(
                                    'send_document_type_8_count' => $row->send_document_type_8_count,
                                    'send_document_type_10_count' => $row->send_document_type_10_count,
                                    'send_document_type_11_count' => $row->send_document_type_11_count,
                                    'inDate' => $param['inDate'],
                                    'outDate' => $param['outDate'],
                                    'reportMenuId' => $param['reportMenuId'],
                                    'reportModId' => $param['reportModId'],
                                    'parentId' => $row->id,
                                    'space' => 40,
                                    'num' => $j
                        ));

                        if ($isChildData) {
                            $htmlData .= $isChildData['html'];
                            $sumTotalSendDocumentType8 = $sumTotalSendDocumentType8 + $isChildData['send_document_type_8_count'];
                            $sumTotalSendDocumentType10 = $sumTotalSendDocumentType10 + $isChildData['send_document_type_10_count'];
                            $sumTotalSendDocumentType11 = $sumTotalSendDocumentType11 + $isChildData['send_document_type_11_count'];

                            $sumTotalAllRow = $sumTotalAllRow + $isChildData['send_document_type_8_count'] + $isChildData['send_document_type_10_count'] + $isChildData['send_document_type_11_count'];
                        }
                    }
                    $htmlData .= '</tbody>';
                    $htmlData .= '<tfoot>';
                    $htmlData .= '<tr>';
                    $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт</td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'departmentId=' . $rowParent->id . '&typeId=8&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSendDocumentType8 > 0 ? $sumTotalSendDocumentType8 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'departmentId=' . $rowParent->id . '&typeId=10&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSendDocumentType10 > 0 ? $sumTotalSendDocumentType10 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'departmentId=' . $rowParent->id . '&typeId=11&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalSendDocumentType11 > 0 ? $sumTotalSendDocumentType11 : '') . '</a></td>';
                    $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $rowParent->id . '&typeId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumTotalAllRow > 0 ? $sumTotalAllRow : '') . '</a></td>';
                    $htmlData .= '</tr>';

                    $htmlData .= '</tfoot>';
                    $htmlData .= '</table>';
                    $htmlData .= '</div>';
                }

                return array(
                    'title' => $rowParent->title . ' нэгдсэн дүн',
                    'width' => 1200,
                    'html' => $htmlData,
                    'status' => true);
            }
        }
        return array('status' => false);
    }

    public function getReportGeneralDetailSendDocumentChildData_model($param = array()) {

        $queryStringData = $htmlData = $allTableHtml = '';

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $j = $sumTotalSendDocumentType8 = $sumTotalSendDocumentType10 = $sumTotalSendDocumentType11 = $sumRow = $sumTotalAllRow = 0;

        $query = $this->db->query('
                    SELECT 
                        HPD.id,
                        HPD.title,
                        SEND_DOC_TYPE_8.send_document_type_8_count,
                        IF(SEND_DOC_TYPE_8.send_document_type_8_count > 0, \'_row-more\', \'\') AS send_document_type_8_count_class,
                        SEND_DOC_TYPE_10.send_document_type_10_count,
                        IF(SEND_DOC_TYPE_10.send_document_type_10_count > 0, \'_row-more\', \'\') AS send_document_type_10_count_class,
                        SEND_DOC_TYPE_11.send_document_type_11_count,
                        IF(SEND_DOC_TYPE_11.send_document_type_11_count > 0, \'_row-more\', \'\') AS send_document_type_11_count_class
                    FROM `gaz_hr_people_department` AS HPD
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS send_document_type_8_in_date,
                            COUNT(N.type_id) AS send_document_type_8_count
                        FROM `gaz_nifs_send_doc` AS N
                        WHERE N.type_id = 8 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SEND_DOC_TYPE_8 ON SEND_DOC_TYPE_8.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS send_document_type_10_in_date,
                            COUNT(N.type_id) AS send_document_type_10_count
                        FROM `gaz_nifs_send_doc` AS N
                        WHERE N.type_id = 10 ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SEND_DOC_TYPE_10 ON SEND_DOC_TYPE_10.department_id = HPD.id
                    LEFT JOIN (
                        SELECT
                            N.department_id,
                            DATE(N.in_date) AS send_document_type_11_in_date,
                            COUNT(N.type_id) AS send_document_type_11_count
                        FROM `gaz_nifs_send_doc` AS N
                        WHERE N.type_id = 11  ' . $queryStringData . '
                        GROUP BY N.department_id
                    ) AS SEND_DOC_TYPE_11 ON SEND_DOC_TYPE_11.department_id = HPD.id
                    WHERE HPD.is_active = 1 AND HPD.parent_id = ' . $param['parentId']);

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $j++;

                $sumTotalSendDocumentType8 = $sumTotalSendDocumentType8 + $row->send_document_type_8_count;
                $sumTotalSendDocumentType10 = $sumTotalSendDocumentType10 + $row->send_document_type_10_count;
                $sumTotalSendDocumentType11 = $sumTotalSendDocumentType11 + $row->send_document_type_11_count;
                $sumRow = $row->send_document_type_8_count + $row->send_document_type_10_count + $row->send_document_type_11_count;
                $sumTotalAllRow = $sumTotalAllRow + $sumRow;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $param['num'] . '.' . $j . '</td>';
                $htmlData .= '<td class="text-left" style="padding-left:' . $param['space'] . 'px;">' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->send_document_type_8_count_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=8&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->send_document_type_8_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->send_document_type_10_count_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=10&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->send_document_type_10_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->send_document_type_11_count_class . '"><a href="javascript:;" onclick="_initNifsSendDocument({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=11&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->send_document_type_11_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . ($sumRow > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'departmentId=' . $row->id . '&typeId=all&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($sumRow > 0 ? $sumRow : '') . '</a></td>';
                $htmlData .= '</tr>';

                $isChildData = self::getReportGeneralDetailSendDocumentChildData_model(array(
                            'send_document_type_8_count' => $row->send_document_type_8_count,
                            'send_document_type_10_count' => $row->send_document_type_10_count,
                            'send_document_type_11_count' => $row->send_document_type_11_count,
                            'inDate' => $param['inDate'],
                            'outDate' => $param['outDate'],
                            'reportMenuId' => $param['reportMenuId'],
                            'reportModId' => $param['reportModId'],
                            'parentId' => $row->id,
                            'space' => ($param['space'] + 40),
                            'num' => $param['num'] . '.' . $j
                ));

                if ($isChildData) {
                    $htmlData .= $isChildData['html'];
                    $sumTotalSendDocumentType8 = $sumTotalSendDocumentType8 + $isChildData['send_document_type_8_count'];
                    $sumTotalSendDocumentType10 = $sumTotalSendDocumentType10 + $isChildData['send_document_type_10_count'];
                    $sumTotalSendDocumentType11 = $sumTotalSendDocumentType11 + $isChildData['send_document_type_11_count'];
                }
            }

            return array('html' => $htmlData, 'send_document_type_8_count' => $sumTotalSendDocumentType8, 'send_document_type_10_count' => $sumTotalSendDocumentType10, 'send_document_type_11_count' => $sumTotalSendDocumentType11);
        }
        return false;
    }

    public function getReportGeneralOldData_model($param = array()) {

        $queryStringYearData = $queryStringData = $htmlData = '';

        $tableClassProvince = 'province-';
        $tableClassCity = 'city-';
        $tableClassCenter = 'center-';
        $tableClassAll = 'all-';

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['inDate'])) . '\') <= DATE(N.in_date) AND DATE(\'' . date('Y-m-d', strtotime($param['outDate'])) . '\') >= DATE(N.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['inDate'])) . '\') <= DATE(N.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['outDate'])) . '\') >= DATE(N.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $provinceSumCrime = $provinceSumDoctorView = $provinceSumAnatomy = $provinceSumCrimeCatCount280 = $provinceSumCrimeCatCount281 = $provinceSumCrimeCatCount282 = $provinceSumCrimeCatCount283 = $provinceSumCrimeCatCount284 = $provinceSumCrimeCatCount285 = $provinceSumCrimeCatCount286 = $provinceSumCrimeCatCount342 = $provinceSumCrimeCatCount343 = $provinceSumCrimeCatCount344 = $provinceSumCrimeCatCount345 = $provinceSumCrimeCatCount346 = $provinceSumCrimeCatCount347 = $provinceSumCrimeCatCount348 = $provinceSumCrimeCatCount437 = $provinceSumExtraTypeCount8 = $provinceSumExtraTypeCount9 = $provinceSumExtraTypeCount10 = $provinceSumExtraTypeCount11 = $provinceSumExtraTypeCount12 = $provinceSumExtraTypeCount13 = $provinceSumEconomyTypeCount21 = $provinceSumTotal = 0;


        $citySumCrime = $citySumDoctorView = $citySumAnatomy = $citySumCrimeCatCount280 = $citySumCrimeCatCount281 = $citySumCrimeCatCount282 = $citySumCrimeCatCount283 = $citySumCrimeCatCount284 = $citySumCrimeCatCount285 = $citySumCrimeCatCount286 = $citySumCrimeCatCount342 = $citySumCrimeCatCount343 = $citySumCrimeCatCount344 = $citySumCrimeCatCount345 = $citySumCrimeCatCount346 = $citySumCrimeCatCount347 = $citySumCrimeCatCount348 = $citySumCrimeCatCount437 = $citySumExtraTypeCount8 = $citySumExtraTypeCount9 = $citySumExtraTypeCount10 = $citySumExtraTypeCount11 = $citySumExtraTypeCount12 = $citySumExtraTypeCount13 = $citySumEconomyTypeCount21 = $citySumTotal = 0;


        $centerSumCrime = $centerSumDoctorView = $centerSumAnatomy = $centerSumCrimeCatCount280 = $centerSumCrimeCatCount281 = $centerSumCrimeCatCount282 = $centerSumCrimeCatCount283 = $centerSumCrimeCatCount284 = $centerSumCrimeCatCount285 = $centerSumCrimeCatCount286 = $centerSumCrimeCatCount342 = $centerSumCrimeCatCount343 = $centerSumCrimeCatCount344 = $centerSumCrimeCatCount345 = $centerSumCrimeCatCount346 = $centerSumCrimeCatCount347 = $centerSumCrimeCatCount348 = $centerSumCrimeCatCount437 = $centerSumExtraTypeCount8 = $centerSumExtraTypeCount9 = $centerSumExtraTypeCount10 = $centerSumExtraTypeCount11 = $centerSumExtraTypeCount12 = $centerSumExtraTypeCount13 = $centerSumEconomyTypeCount21 = $centerSumTotal = 0;

        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                NC.crime_count,
                IF(NC.crime_count > 0, \'_row-more\', \'\') AS crime_count_class,
                NC_CAT_280.cat_count_280,
                IF(NC_CAT_280.cat_count_280 > 0, \'_row-more\', \'\') AS cat_count_280_class,
                NC_CAT_281.cat_count_281,
                IF(NC_CAT_281.cat_count_281 > 0, \'_row-more\', \'\') AS cat_count_281_class,
                NC_CAT_282.cat_count_282,
                IF(NC_CAT_282.cat_count_282 > 0, \'_row-more\', \'\') AS cat_count_282_class,
                NC_CAT_283.cat_count_283,
                IF(NC_CAT_283.cat_count_283 > 0, \'_row-more\', \'\') AS cat_count_283_class,
                NC_CAT_284.cat_count_284,
                IF(NC_CAT_284.cat_count_284 > 0, \'_row-more\', \'\') AS cat_count_284_class,
                NC_CAT_285.cat_count_285,
                IF(NC_CAT_285.cat_count_285 > 0, \'_row-more\', \'\') AS cat_count_285_class,
                NC_CAT_286.cat_count_286,
                IF(NC_CAT_286.cat_count_286 > 0, \'_row-more\', \'\') AS cat_count_286_class,
                NC_CAT_342.cat_count_342,
                IF(NC_CAT_342.cat_count_342 > 0, \'_row-more\', \'\') AS cat_count_342_class,
                NC_CAT_343.cat_count_343,
                IF(NC_CAT_343.cat_count_343 > 0, \'_row-more\', \'\') AS cat_count_343_class,
                NC_CAT_344.cat_count_344,
                IF(NC_CAT_344.cat_count_344 > 0, \'_row-more\', \'\') AS cat_count_344_class,
                NC_CAT_345.cat_count_345,
                IF(NC_CAT_345.cat_count_345 > 0, \'_row-more\', \'\') AS cat_count_345_class,
                NC_CAT_346.cat_count_346,
                IF(NC_CAT_346.cat_count_346 > 0, \'_row-more\', \'\') AS cat_count_346_class,
                NC_CAT_347.cat_count_347,
                IF(NC_CAT_347.cat_count_347 > 0, \'_row-more\', \'\') AS cat_count_347_class,
                NC_CAT_348.cat_count_348,
                IF(NC_CAT_348.cat_count_348 > 0, \'_row-more\', \'\') AS cat_count_348_class,
                NC_CAT_437.cat_count_437,
                IF(NC_CAT_437.cat_count_437 > 0, \'_row-more\', \'\') AS cat_count_437_class,
                NDV.doctor_view_count,
                IF(NDV.doctor_view_count > 0, \'_row-more\', \'\') AS doctor_view_count_class,
                NA.anatomy_count,
                IF(NA.anatomy_count > 0, \'_row-more\', \'\') AS anatomy_count_class,
                E_TYPE_COUNT_8.type_count_8,
                IF(E_TYPE_COUNT_8.type_count_8 > 0, \'_row-more\', \'\') AS type_count_8_class,
                E_TYPE_COUNT_9.type_count_9,
                IF(E_TYPE_COUNT_9.type_count_9 > 0, \'_row-more\', \'\') AS type_count_9_class,
                E_TYPE_COUNT_10.type_count_10,
                IF(E_TYPE_COUNT_10.type_count_10 > 0, \'_row-more\', \'\') AS type_count_10_class,
                E_TYPE_COUNT_11.type_count_11,
                IF(E_TYPE_COUNT_11.type_count_11 > 0, \'_row-more\', \'\') AS type_count_11_class,
                E_TYPE_COUNT_12.type_count_12,
                IF(E_TYPE_COUNT_12.type_count_12 > 0, \'_row-more\', \'\') AS type_count_12_class,
                E_TYPE_COUNT_13.type_count_13,
                IF(E_TYPE_COUNT_13.type_count_13 > 0, \'_row-more\', \'\') AS type_count_13_class,
                ECONOMY_TYPE_COUNT_21.type_count_21,
                IF(ECONOMY_TYPE_COUNT_21.type_count_21 > 0, \'_row-more\', \'\') AS type_count_21_class
            FROM `gaz_hr_people_department` AS HPD
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS crime_count
                FROM `gaz_nifs_crime` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC ON NC.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_280
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 280 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_280 ON NC_CAT_280.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_281
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 281 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_281 ON NC_CAT_281.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_282
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 282 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_282 ON NC_CAT_282.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_283
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 283 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_283 ON NC_CAT_283.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_284
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 284 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_284 ON NC_CAT_284.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_285
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 285 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_285 ON NC_CAT_285.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_286
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 286 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_286 ON NC_CAT_286.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_342
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 342 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_342 ON NC_CAT_342.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_343
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 343 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_343 ON NC_CAT_343.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_344
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 344 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_344 ON NC_CAT_344.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_345
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 345 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_345 ON NC_CAT_345.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_346
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 346 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_346 ON NC_CAT_346.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_347
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 347 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_347 ON NC_CAT_347.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_348
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 348 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_348 ON NC_CAT_348.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_437
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 437 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_437 ON NC_CAT_437.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS doctor_view_count
                FROM `gaz_nifs_doctor_view` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NDV ON NDV.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS anatomy_count
                FROM `gaz_nifs_anatomy` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NA ON NA.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_8
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 8' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_8 ON E_TYPE_COUNT_8.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_9
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 9' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_9 ON E_TYPE_COUNT_9.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_10
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 10' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_10 ON E_TYPE_COUNT_10.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_11
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 11' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_11 ON E_TYPE_COUNT_11.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_12
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 12' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_12 ON E_TYPE_COUNT_12.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_13
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 13' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_13 ON E_TYPE_COUNT_13.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_21
                FROM `gaz_nifs_economy` AS N
                WHERE N.type_id = 21' . $queryStringData . '
                GROUP BY N.department_id
            ) AS ECONOMY_TYPE_COUNT_21 ON ECONOMY_TYPE_COUNT_21.department_id = HPD.id
            WHERE HPD.is_active = 1 AND HPD.parent_id = 18');

        if ($query->num_rows() > 0) {

            $i = 1;
            $htmlData .= '<h6>Орон нутаг</h6>';
            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="2" style="width:30px;">#</th>';
            $htmlData .= '<th rowspan="2" style="min-width:300px;">Гарчиг</th>';
            $htmlData .= '<th colspan="15" style="width:900px;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">Криминалистик</th>';
            $htmlData .= '<th rowspan="2" style="width:80px; cursor:pointer;" class="text-center" data-type="plus" onclick="_reportCrimeFieldCheck({elem: this, prefix: \'' . $tableClassProvince . '\'});"><i class="icon-plus2"></i> <br> Криминалистик нийт</th>';
            $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Шүүх эмнэлэг</th>';
            $htmlData .= '<th colspan="6" style="width:480px;" class="text-center">Тусгай шинжилгээ</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Эдийн засаг</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Нийт</th>';
            $htmlData .= '</tr>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">Дүрс бичлэг</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">Дүр зураг</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">Мөр судлал</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">Гарын мөр</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">Бичиг техник</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">Галт зэвсэг</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">Бичиг судлал</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">Авто техникийн шинжилгээ</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">Тэсрэх төхөөрөмж</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">Компьютер техник</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">Дуу авиа</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">Полиграф</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">Инженер техник</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">Материал судлал</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">Аман зураг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Эмчийн үзлэг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Задлан</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Биологи</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">ДНХ</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Бактериологи</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хими</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Физик</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Бусад</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $rowSum = $row->doctor_view_count + $row->anatomy_count + $row->cat_count_280 + $row->cat_count_281 + $row->cat_count_282 + $row->cat_count_283 + $row->cat_count_284 + $row->cat_count_285 + $row->cat_count_286 + $row->cat_count_342 + $row->cat_count_343 + $row->cat_count_344 + $row->cat_count_345 + $row->cat_count_346 + $row->cat_count_347 + $row->cat_count_348 + $row->cat_count_437 + $row->type_count_8 + $row->type_count_9 + $row->type_count_10 + $row->type_count_11 + $row->type_count_12 + $row->type_count_13 + $row->type_count_21;

                $childData = self::getReportGeneralOldChildData_model(array(
                            'rootRow' => $row,
                            'parentId' => $row->id,
                            'rowNumber' => $i,
                            'inDate' => $param['inDate'],
                            'outDate' => $param['outDate'],
                            'space' => 30,
                            'icon' => '',
                            'prefix' => $tableClassProvince,
                            'reportMenuId' => $param['reportMenuId'],
                            'reportModId' => $param['reportModId'],
                            'catCount280' => $row->cat_count_280,
                            'catCount281' => $row->cat_count_281,
                            'catCount282' => $row->cat_count_282,
                            'catCount283' => $row->cat_count_283,
                            'catCount284' => $row->cat_count_284,
                            'catCount285' => $row->cat_count_285,
                            'catCount286' => $row->cat_count_286,
                            'catCount342' => $row->cat_count_342,
                            'catCount343' => $row->cat_count_343,
                            'catCount344' => $row->cat_count_344,
                            'catCount345' => $row->cat_count_345,
                            'catCount346' => $row->cat_count_346,
                            'catCount347' => $row->cat_count_347,
                            'catCount348' => $row->cat_count_348,
                            'catCount437' => $row->cat_count_437,
                            'crimeCount' => $row->crime_count,
                            'doctorViewCount' => $row->doctor_view_count,
                            'anatomyCount' => $row->anatomy_count,
                            'typeCount8' => $row->type_count_8,
                            'typeCount9' => $row->type_count_9,
                            'typeCount10' => $row->type_count_10,
                            'typeCount11' => $row->type_count_11,
                            'typeCount12' => $row->type_count_12,
                            'typeCount13' => $row->type_count_13,
                            'typeCount21' => $row->type_count_21,
                            'rowSum' => $rowSum));

                $provinceSumDoctorView = $provinceSumDoctorView + $childData['data']['doctorViewCount'];
                $provinceSumAnatomy = $provinceSumAnatomy + $childData['data']['anatomyCount'];
                $provinceSumCrimeCatCount280 = $provinceSumCrimeCatCount280 + $childData['data']['catCount280'];
                $provinceSumCrimeCatCount281 = $provinceSumCrimeCatCount281 + $childData['data']['catCount281'];
                $provinceSumCrimeCatCount282 = $provinceSumCrimeCatCount282 + $childData['data']['catCount282'];
                $provinceSumCrimeCatCount283 = $provinceSumCrimeCatCount283 + $childData['data']['catCount283'];
                $provinceSumCrimeCatCount284 = $provinceSumCrimeCatCount284 + $childData['data']['catCount284'];
                $provinceSumCrimeCatCount285 = $provinceSumCrimeCatCount285 + $childData['data']['catCount285'];
                $provinceSumCrimeCatCount286 = $provinceSumCrimeCatCount286 + $childData['data']['catCount286'];
                $provinceSumCrimeCatCount342 = $provinceSumCrimeCatCount342 + $childData['data']['catCount342'];
                $provinceSumCrimeCatCount343 = $provinceSumCrimeCatCount343 + $childData['data']['catCount343'];
                $provinceSumCrimeCatCount344 = $provinceSumCrimeCatCount344 + $childData['data']['catCount344'];
                $provinceSumCrimeCatCount345 = $provinceSumCrimeCatCount345 + $childData['data']['catCount345'];
                $provinceSumCrimeCatCount346 = $provinceSumCrimeCatCount346 + $childData['data']['catCount346'];
                $provinceSumCrimeCatCount347 = $provinceSumCrimeCatCount347 + $childData['data']['catCount347'];
                $provinceSumCrimeCatCount348 = $provinceSumCrimeCatCount348 + $childData['data']['catCount348'];
                $provinceSumCrimeCatCount437 = $provinceSumCrimeCatCount437 + $childData['data']['catCount437'];
                $provinceSumCrime = $provinceSumCrime + $childData['data']['crimeCount'];
                $provinceSumExtraTypeCount8 = $provinceSumExtraTypeCount8 + $childData['data']['typeCount8'];
                $provinceSumExtraTypeCount9 = $provinceSumExtraTypeCount9 + $childData['data']['typeCount9'];
                $provinceSumExtraTypeCount10 = $provinceSumExtraTypeCount10 + $childData['data']['typeCount10'];
                $provinceSumExtraTypeCount11 = $provinceSumExtraTypeCount11 + $childData['data']['typeCount11'];
                $provinceSumExtraTypeCount12 = $provinceSumExtraTypeCount12 + $childData['data']['typeCount12'];
                $provinceSumExtraTypeCount13 = $provinceSumExtraTypeCount13 + $childData['data']['typeCount13'];
                $provinceSumEconomyTypeCount21 = $provinceSumEconomyTypeCount21 + $childData['data']['typeCount21'];
                $provinceSumTotal = $provinceSumTotal + $childData['data']['rowSum'];

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i . '</td>';
                $htmlData .= '<td> ' . $childData['icon'] . ' ' . $row->title . '</td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_280_class . ' ' . $tableClassProvince . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=280&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['catCount280'] > 0 ? $childData['data']['catCount280'] : '') . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_281_class . ' ' . $tableClassProvince . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=281&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['catCount281'] > 0 ? $childData['data']['catCount281'] : '') . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_282_class . ' ' . $tableClassProvince . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=282&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['catCount282'] > 0 ? $childData['data']['catCount282'] : '') . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_283_class . ' ' . $tableClassProvince . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=283&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['catCount283'] > 0 ? $childData['data']['catCount283'] : '') . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_284_class . ' ' . $tableClassProvince . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=284&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['catCount284'] > 0 ? $childData['data']['catCount284'] : '') . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_285_class . ' ' . $tableClassProvince . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=285&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['catCount285'] > 0 ? $childData['data']['catCount285'] : '') . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_286_class . ' ' . $tableClassProvince . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=286&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['catCount286'] > 0 ? $childData['data']['catCount286'] : '') . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_342_class . ' ' . $tableClassProvince . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=342&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['catCount342'] > 0 ? $childData['data']['catCount342'] : '') . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_343_class . ' ' . $tableClassProvince . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=343&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['catCount343'] > 0 ? $childData['data']['catCount343'] : '') . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_344_class . ' ' . $tableClassProvince . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=344&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['catCount344'] > 0 ? $childData['data']['catCount344'] : '') . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_345_class . ' ' . $tableClassProvince . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=345&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['catCount345'] > 0 ? $childData['data']['catCount345'] : '') . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_346_class . ' ' . $tableClassProvince . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=346&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['catCount346'] > 0 ? $childData['data']['catCount346'] : '') . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_347_class . ' ' . $tableClassProvince . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=347&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['catCount347'] > 0 ? $childData['data']['catCount347'] : '') . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_348_class . ' ' . $tableClassProvince . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=348&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['catCount347'] > 0 ? $childData['data']['catCount347'] : '') . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_437_class . ' ' . $tableClassProvince . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=437&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['catCount437'] > 0 ? $childData['data']['catCount437'] : '') . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['crimeCount'] > 0 ? $childData['data']['crimeCount'] : '') . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->doctor_view_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['doctorViewCount'] > 0 ? $childData['data']['doctorViewCount'] : '') . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->anatomy_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['anatomyCount'] > 0 ? $childData['data']['anatomyCount'] : '') . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_8_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=8&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['typeCount8'] > 0 ? $childData['data']['typeCount8'] : '') . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_9_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=9&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['typeCount9'] > 0 ? $childData['data']['typeCount9'] : '') . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_10_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=10&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['typeCount10'] > 0 ? $childData['data']['typeCount10'] : '') . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_11_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=11&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['typeCount11'] > 0 ? $childData['data']['typeCount11'] : '') . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_12_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=12&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['typeCount12'] > 0 ? $childData['data']['typeCount12'] : '') . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_13_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=13&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['typeCount13'] > 0 ? $childData['data']['typeCount13'] : '') . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_21_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=21&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['typeCount21'] > 0 ? $childData['data']['typeCount21'] : '') . '</a></td>';

                $htmlData .= '<td class="text-center">' . ($childData['data']['rowSum'] > 0 ? $childData['data']['rowSum'] : '') . '</td>';
                $htmlData .= '</tr>';

                $htmlData .= $childData['html'];

                $i++;
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td class="text-right">Нийт дүн</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassProvince . 'report-crime-field-check"">' . ($provinceSumCrimeCatCount280 > 0 ? $provinceSumCrimeCatCount280 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">' . ($provinceSumCrimeCatCount281 > 0 ? $provinceSumCrimeCatCount281 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">' . ($provinceSumCrimeCatCount282 > 0 ? $provinceSumCrimeCatCount282 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">' . ($provinceSumCrimeCatCount283 > 0 ? $provinceSumCrimeCatCount283 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">' . ($provinceSumCrimeCatCount284 > 0 ? $provinceSumCrimeCatCount284 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">' . ($provinceSumCrimeCatCount285 > 0 ? $provinceSumCrimeCatCount285 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">' . ($provinceSumCrimeCatCount286 > 0 ? $provinceSumCrimeCatCount286 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">' . ($provinceSumCrimeCatCount342 > 0 ? $provinceSumCrimeCatCount342 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">' . ($provinceSumCrimeCatCount343 > 0 ? $provinceSumCrimeCatCount343 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">' . ($provinceSumCrimeCatCount344 > 0 ? $provinceSumCrimeCatCount344 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">' . ($provinceSumCrimeCatCount345 > 0 ? $provinceSumCrimeCatCount345 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">' . ($provinceSumCrimeCatCount346 > 0 ? $provinceSumCrimeCatCount346 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">' . ($provinceSumCrimeCatCount347 > 0 ? $provinceSumCrimeCatCount347 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">' . ($provinceSumCrimeCatCount348 > 0 ? $provinceSumCrimeCatCount348 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassProvince . 'report-crime-field-check">' . ($provinceSumCrimeCatCount437 > 0 ? $provinceSumCrimeCatCount437 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($provinceSumCrime > 0 ? $provinceSumCrime : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($provinceSumDoctorView > 0 ? $provinceSumDoctorView : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($provinceSumAnatomy > 0 ? $provinceSumAnatomy : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($provinceSumExtraTypeCount8 > 0 ? $provinceSumExtraTypeCount8 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($provinceSumExtraTypeCount9 > 0 ? $provinceSumExtraTypeCount9 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($provinceSumExtraTypeCount10 > 0 ? $provinceSumExtraTypeCount10 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($provinceSumExtraTypeCount11 > 0 ? $provinceSumExtraTypeCount11 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($provinceSumExtraTypeCount12 > 0 ? $provinceSumExtraTypeCount12 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($provinceSumExtraTypeCount13 > 0 ? $provinceSumExtraTypeCount13 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($provinceSumEconomyTypeCount21 > 0 ? $provinceSumEconomyTypeCount21 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($provinceSumTotal > 0 ? $provinceSumTotal : '') . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';
        }

        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                NC.crime_count,
                IF(NC.crime_count > 0, \'_row-more\', \'\') AS crime_count_class,
                NC_CAT_280.cat_count_280,
                IF(NC_CAT_280.cat_count_280 > 0, \'_row-more\', \'\') AS cat_count_280_class,
                NC_CAT_281.cat_count_281,
                IF(NC_CAT_281.cat_count_281 > 0, \'_row-more\', \'\') AS cat_count_281_class,
                NC_CAT_282.cat_count_282,
                IF(NC_CAT_282.cat_count_282 > 0, \'_row-more\', \'\') AS cat_count_282_class,
                NC_CAT_283.cat_count_283,
                IF(NC_CAT_283.cat_count_283 > 0, \'_row-more\', \'\') AS cat_count_283_class,
                NC_CAT_284.cat_count_284,
                IF(NC_CAT_284.cat_count_284 > 0, \'_row-more\', \'\') AS cat_count_284_class,
                NC_CAT_285.cat_count_285,
                IF(NC_CAT_285.cat_count_285 > 0, \'_row-more\', \'\') AS cat_count_285_class,
                NC_CAT_286.cat_count_286,
                IF(NC_CAT_286.cat_count_286 > 0, \'_row-more\', \'\') AS cat_count_286_class,
                NC_CAT_342.cat_count_342,
                IF(NC_CAT_342.cat_count_342 > 0, \'_row-more\', \'\') AS cat_count_342_class,
                NC_CAT_343.cat_count_343,
                IF(NC_CAT_343.cat_count_343 > 0, \'_row-more\', \'\') AS cat_count_343_class,
                NC_CAT_344.cat_count_344,
                IF(NC_CAT_344.cat_count_344 > 0, \'_row-more\', \'\') AS cat_count_344_class,
                NC_CAT_345.cat_count_345,
                IF(NC_CAT_345.cat_count_345 > 0, \'_row-more\', \'\') AS cat_count_345_class,
                NC_CAT_346.cat_count_346,
                IF(NC_CAT_346.cat_count_346 > 0, \'_row-more\', \'\') AS cat_count_346_class,
                NC_CAT_347.cat_count_347,
                IF(NC_CAT_347.cat_count_347 > 0, \'_row-more\', \'\') AS cat_count_347_class,
                NC_CAT_348.cat_count_348,
                IF(NC_CAT_348.cat_count_348 > 0, \'_row-more\', \'\') AS cat_count_348_class,
                NC_CAT_437.cat_count_437,
                IF(NC_CAT_437.cat_count_437 > 0, \'_row-more\', \'\') AS cat_count_437_class,
                NDV.doctor_view_count,
                IF(NDV.doctor_view_count > 0, \'_row-more\', \'\') AS doctor_view_count_class,
                NA.anatomy_count,
                IF(NA.anatomy_count > 0, \'_row-more\', \'\') AS anatomy_count_class,
                E_TYPE_COUNT_8.type_count_8,
                IF(E_TYPE_COUNT_8.type_count_8 > 0, \'_row-more\', \'\') AS type_count_8_class,
                E_TYPE_COUNT_9.type_count_9,
                IF(E_TYPE_COUNT_9.type_count_9 > 0, \'_row-more\', \'\') AS type_count_9_class,
                E_TYPE_COUNT_10.type_count_10,
                IF(E_TYPE_COUNT_10.type_count_10 > 0, \'_row-more\', \'\') AS type_count_10_class,
                E_TYPE_COUNT_11.type_count_11,
                IF(E_TYPE_COUNT_11.type_count_11 > 0, \'_row-more\', \'\') AS type_count_11_class,
                E_TYPE_COUNT_12.type_count_12,
                IF(E_TYPE_COUNT_12.type_count_12 > 0, \'_row-more\', \'\') AS type_count_12_class,
                E_TYPE_COUNT_13.type_count_13,
                IF(E_TYPE_COUNT_13.type_count_13 > 0, \'_row-more\', \'\') AS type_count_13_class,
                ECONOMY_TYPE_COUNT_21.type_count_21,
                IF(ECONOMY_TYPE_COUNT_21.type_count_21 > 0, \'_row-more\', \'\') AS type_count_21_class
            FROM `gaz_hr_people_department` AS HPD
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS crime_count
                FROM `gaz_nifs_crime` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC ON NC.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_280
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 280 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_280 ON NC_CAT_280.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_281
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 281 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_281 ON NC_CAT_281.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_282
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 282 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_282 ON NC_CAT_282.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_283
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 283 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_283 ON NC_CAT_283.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_284
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 284 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_284 ON NC_CAT_284.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_285
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 285 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_285 ON NC_CAT_285.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_286
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 286 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_286 ON NC_CAT_286.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_342
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 342 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_342 ON NC_CAT_342.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_343
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 343 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_343 ON NC_CAT_343.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_344
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 344 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_344 ON NC_CAT_344.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_345
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 345 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_345 ON NC_CAT_345.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_346
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 346 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_346 ON NC_CAT_346.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_347
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 347 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_347 ON NC_CAT_347.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_348
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 348 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_348 ON NC_CAT_348.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_437
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 437 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_437 ON NC_CAT_437.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS doctor_view_count
                FROM `gaz_nifs_doctor_view` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NDV ON NDV.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS anatomy_count
                FROM `gaz_nifs_anatomy` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NA ON NA.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_8
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 8' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_8 ON E_TYPE_COUNT_8.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_9
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 9' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_9 ON E_TYPE_COUNT_9.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_10
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 10' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_10 ON E_TYPE_COUNT_10.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_11
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 11' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_11 ON E_TYPE_COUNT_11.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_12
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 12' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_12 ON E_TYPE_COUNT_12.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_13
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 13' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_13 ON E_TYPE_COUNT_13.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_21
                FROM `gaz_nifs_economy` AS N
                WHERE N.type_id = 21' . $queryStringData . '
                GROUP BY N.department_id
            ) AS ECONOMY_TYPE_COUNT_21 ON ECONOMY_TYPE_COUNT_21.department_id = HPD.id
            WHERE HPD.is_active = 1 AND HPD.parent_id = 8');

        if ($query->num_rows() > 0) {

            $i = 1;
            $htmlData .= '<h6>Нийслэл</h6>';
            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="2" style="width:30px;">#</th>';
            $htmlData .= '<th rowspan="2" style="min-width:300px;">Гарчиг</th>';
            $htmlData .= '<th colspan="15" style="width:900px;" class="text-center ' . $tableClassCity . 'report-crime-field-check">Криминалистик</th>';
            $htmlData .= '<th rowspan="2" style="width:80px; cursor:pointer;" class="text-center" data-type="plus" onclick="_reportCrimeFieldCheck({elem: this, prefix: \'' . $tableClassCity . '\'});"><i class="icon-plus2"></i> <br> Криминалистик нийт</th>';
            $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Шүүх эмнэлэг</th>';
            $htmlData .= '<th colspan="6" style="width:480px;" class="text-center">Тусгай шинжилгээ</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Эдийн засаг</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Нийт</th>';
            $htmlData .= '</tr>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCity . 'report-crime-field-check">Дүрс бичлэг</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCity . 'report-crime-field-check">Дүр зураг</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCity . 'report-crime-field-check">Мөр судлал</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCity . 'report-crime-field-check">Гарын мөр</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCity . 'report-crime-field-check">Бичиг техник</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCity . 'report-crime-field-check">Галт зэвсэг</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCity . 'report-crime-field-check">Бичиг судлал</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCity . 'report-crime-field-check">Авто техникийн шинжилгээ</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCity . 'report-crime-field-check">Тэсрэх төхөөрөмж</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCity . 'report-crime-field-check">Компьютер техник</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCity . 'report-crime-field-check">Дуу авиа</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCity . 'report-crime-field-check">Полиграф</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCity . 'report-crime-field-check">Инженер техник</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCity . 'report-crime-field-check">Материал судлал</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCity . 'report-crime-field-check">Аман зураг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Эмчийн үзлэг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Задлан</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Биологи</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">ДНХ</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Бактериологи</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хими</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Физик</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Бусад</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $rowSum = $row->doctor_view_count + $row->anatomy_count + $row->cat_count_280 + $row->cat_count_281 + $row->cat_count_282 + $row->cat_count_283 + $row->cat_count_284 + $row->cat_count_285 + $row->cat_count_286 + $row->cat_count_342 + $row->cat_count_343 + $row->cat_count_344 + $row->cat_count_345 + $row->cat_count_346 + $row->cat_count_347 + $row->cat_count_348 + $row->cat_count_437 + $row->type_count_8 + $row->type_count_9 + $row->type_count_10 + $row->type_count_11 + $row->type_count_12 + $row->type_count_13 + $row->type_count_21;

                $citySumDoctorView = $citySumDoctorView + $row->doctor_view_count;
                $citySumAnatomy = $citySumAnatomy + $row->anatomy_count;
                $citySumCrimeCatCount280 = $citySumCrimeCatCount280 + $row->cat_count_280;
                $citySumCrimeCatCount281 = $citySumCrimeCatCount281 + $row->cat_count_281;
                $citySumCrimeCatCount282 = $citySumCrimeCatCount282 + $row->cat_count_282;
                $citySumCrimeCatCount283 = $citySumCrimeCatCount283 + $row->cat_count_283;
                $citySumCrimeCatCount284 = $citySumCrimeCatCount284 + $row->cat_count_284;
                $citySumCrimeCatCount285 = $citySumCrimeCatCount285 + $row->cat_count_285;
                $citySumCrimeCatCount286 = $citySumCrimeCatCount286 + $row->cat_count_286;
                $citySumCrimeCatCount342 = $citySumCrimeCatCount342 + $row->cat_count_342;
                $citySumCrimeCatCount343 = $citySumCrimeCatCount343 + $row->cat_count_343;
                $citySumCrimeCatCount344 = $citySumCrimeCatCount344 + $row->cat_count_344;
                $citySumCrimeCatCount345 = $citySumCrimeCatCount345 + $row->cat_count_345;
                $citySumCrimeCatCount346 = $citySumCrimeCatCount346 + $row->cat_count_346;
                $citySumCrimeCatCount347 = $citySumCrimeCatCount347 + $row->cat_count_347;
                $citySumCrimeCatCount348 = $citySumCrimeCatCount348 + $row->cat_count_348;
                $citySumCrimeCatCount437 = $citySumCrimeCatCount437 + $row->cat_count_437;
                $citySumCrime = $citySumCrime + $row->crime_count;
                $citySumExtraTypeCount8 = $citySumExtraTypeCount8 + $row->type_count_8;
                $citySumExtraTypeCount9 = $citySumExtraTypeCount9 + $row->type_count_9;
                $citySumExtraTypeCount10 = $citySumExtraTypeCount10 + $row->type_count_10;
                $citySumExtraTypeCount11 = $citySumExtraTypeCount11 + $row->type_count_11;
                $citySumExtraTypeCount12 = $citySumExtraTypeCount12 + $row->type_count_12;
                $citySumExtraTypeCount13 = $citySumExtraTypeCount13 + $row->type_count_13;
                $citySumEconomyTypeCount21 = $citySumEconomyTypeCount21 + $row->type_count_21;
                $citySumTotal = $citySumTotal + $rowSum;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i . '</td>';
                $htmlData .= '<td style="height:40px;">' . $row->title . '</td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_280_class . ' ' . $tableClassCity . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=280&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_280 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_281_class . ' ' . $tableClassCity . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=281&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_281 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_282_class . ' ' . $tableClassCity . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=282&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_282 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_283_class . ' ' . $tableClassCity . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=283&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_283 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_284_class . ' ' . $tableClassCity . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=284&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_284 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_285_class . ' ' . $tableClassCity . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=285&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_285 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_286_class . ' ' . $tableClassCity . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=286&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_286 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_342_class . ' ' . $tableClassCity . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=342&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_342 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_343_class . ' ' . $tableClassCity . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=343&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_343 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_344_class . ' ' . $tableClassCity . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=344&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_344 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_345_class . ' ' . $tableClassCity . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=345&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_345 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_346_class . ' ' . $tableClassCity . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=346&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_346 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_347_class . ' ' . $tableClassCity . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=347&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_347 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_348_class . ' ' . $tableClassCity . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=348&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_348 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_437_class . ' ' . $tableClassCity . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=437&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_437 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->doctor_view_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->doctor_view_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->anatomy_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['anatomyCount'] > 0 ? $childData['data']['anatomyCount'] : '') . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_8_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=8&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_8 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_9_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=9&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_9 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_10_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=10&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_10 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_11_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=11&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_11 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_12_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=12&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_12 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_13_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=13&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_13 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_21_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=21&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_21 . '</a></td>';

                $htmlData .= '<td class="text-center">' . $rowSum . '</td>';
                $htmlData .= '</tr>';

                $i++;
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td class="text-right">Нийт дүн</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCity . 'report-crime-field-check"">' . ($citySumCrimeCatCount280 > 0 ? $citySumCrimeCatCount280 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCity . 'report-crime-field-check">' . ($citySumCrimeCatCount281 > 0 ? $citySumCrimeCatCount281 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCity . 'report-crime-field-check">' . ($citySumCrimeCatCount282 > 0 ? $citySumCrimeCatCount282 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCity . 'report-crime-field-check">' . ($citySumCrimeCatCount283 > 0 ? $citySumCrimeCatCount283 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCity . 'report-crime-field-check">' . ($citySumCrimeCatCount284 > 0 ? $citySumCrimeCatCount284 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCity . 'report-crime-field-check">' . ($citySumCrimeCatCount285 > 0 ? $citySumCrimeCatCount285 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCity . 'report-crime-field-check">' . ($citySumCrimeCatCount286 > 0 ? $citySumCrimeCatCount286 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCity . 'report-crime-field-check">' . ($citySumCrimeCatCount342 > 0 ? $citySumCrimeCatCount342 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCity . 'report-crime-field-check">' . ($citySumCrimeCatCount343 > 0 ? $citySumCrimeCatCount343 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCity . 'report-crime-field-check">' . ($citySumCrimeCatCount344 > 0 ? $citySumCrimeCatCount344 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCity . 'report-crime-field-check">' . ($citySumCrimeCatCount345 > 0 ? $citySumCrimeCatCount345 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCity . 'report-crime-field-check">' . ($citySumCrimeCatCount346 > 0 ? $citySumCrimeCatCount346 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCity . 'report-crime-field-check">' . ($citySumCrimeCatCount347 > 0 ? $citySumCrimeCatCount347 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCity . 'report-crime-field-check">' . ($citySumCrimeCatCount348 > 0 ? $citySumCrimeCatCount348 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCity . 'report-crime-field-check">' . ($citySumCrimeCatCount437 > 0 ? $citySumCrimeCatCount437 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($citySumCrime > 0 ? $citySumCrime : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($citySumDoctorView > 0 ? $citySumDoctorView : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($citySumAnatomy > 0 ? $citySumAnatomy : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($citySumExtraTypeCount8 > 0 ? $citySumExtraTypeCount8 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($citySumExtraTypeCount9 > 0 ? $citySumExtraTypeCount9 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($citySumExtraTypeCount10 > 0 ? $citySumExtraTypeCount10 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($citySumExtraTypeCount11 > 0 ? $citySumExtraTypeCount11 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($citySumExtraTypeCount12 > 0 ? $citySumExtraTypeCount12 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($citySumExtraTypeCount13 > 0 ? $citySumExtraTypeCount13 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($citySumEconomyTypeCount21 > 0 ? $citySumEconomyTypeCount21 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($citySumTotal > 0 ? $citySumTotal : '') . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';
        }

        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                NC.crime_count,
                IF(NC.crime_count > 0, \'_row-more\', \'\') AS crime_count_class,
                NC_CAT_280.cat_count_280,
                IF(NC_CAT_280.cat_count_280 > 0, \'_row-more\', \'\') AS cat_count_280_class,
                NC_CAT_281.cat_count_281,
                IF(NC_CAT_281.cat_count_281 > 0, \'_row-more\', \'\') AS cat_count_281_class,
                NC_CAT_282.cat_count_282,
                IF(NC_CAT_282.cat_count_282 > 0, \'_row-more\', \'\') AS cat_count_282_class,
                NC_CAT_283.cat_count_283,
                IF(NC_CAT_283.cat_count_283 > 0, \'_row-more\', \'\') AS cat_count_283_class,
                NC_CAT_284.cat_count_284,
                IF(NC_CAT_284.cat_count_284 > 0, \'_row-more\', \'\') AS cat_count_284_class,
                NC_CAT_285.cat_count_285,
                IF(NC_CAT_285.cat_count_285 > 0, \'_row-more\', \'\') AS cat_count_285_class,
                NC_CAT_286.cat_count_286,
                IF(NC_CAT_286.cat_count_286 > 0, \'_row-more\', \'\') AS cat_count_286_class,
                NC_CAT_342.cat_count_342,
                IF(NC_CAT_342.cat_count_342 > 0, \'_row-more\', \'\') AS cat_count_342_class,
                NC_CAT_343.cat_count_343,
                IF(NC_CAT_343.cat_count_343 > 0, \'_row-more\', \'\') AS cat_count_343_class,
                NC_CAT_344.cat_count_344,
                IF(NC_CAT_344.cat_count_344 > 0, \'_row-more\', \'\') AS cat_count_344_class,
                NC_CAT_345.cat_count_345,
                IF(NC_CAT_345.cat_count_345 > 0, \'_row-more\', \'\') AS cat_count_345_class,
                NC_CAT_346.cat_count_346,
                IF(NC_CAT_346.cat_count_346 > 0, \'_row-more\', \'\') AS cat_count_346_class,
                NC_CAT_347.cat_count_347,
                IF(NC_CAT_347.cat_count_347 > 0, \'_row-more\', \'\') AS cat_count_347_class,
                NC_CAT_348.cat_count_348,
                IF(NC_CAT_348.cat_count_348 > 0, \'_row-more\', \'\') AS cat_count_348_class,
                NC_CAT_437.cat_count_437,
                IF(NC_CAT_437.cat_count_437 > 0, \'_row-more\', \'\') AS cat_count_437_class,
                NDV.doctor_view_count,
                IF(NDV.doctor_view_count > 0, \'_row-more\', \'\') AS doctor_view_count_class,
                NA.anatomy_count,
                IF(NA.anatomy_count > 0, \'_row-more\', \'\') AS anatomy_count_class,
                E_TYPE_COUNT_8.type_count_8,
                IF(E_TYPE_COUNT_8.type_count_8 > 0, \'_row-more\', \'\') AS type_count_8_class,
                E_TYPE_COUNT_9.type_count_9,
                IF(E_TYPE_COUNT_9.type_count_9 > 0, \'_row-more\', \'\') AS type_count_9_class,
                E_TYPE_COUNT_10.type_count_10,
                IF(E_TYPE_COUNT_10.type_count_10 > 0, \'_row-more\', \'\') AS type_count_10_class,
                E_TYPE_COUNT_11.type_count_11,
                IF(E_TYPE_COUNT_11.type_count_11 > 0, \'_row-more\', \'\') AS type_count_11_class,
                E_TYPE_COUNT_12.type_count_12,
                IF(E_TYPE_COUNT_12.type_count_12 > 0, \'_row-more\', \'\') AS type_count_12_class,
                E_TYPE_COUNT_13.type_count_13,
                IF(E_TYPE_COUNT_13.type_count_13 > 0, \'_row-more\', \'\') AS type_count_13_class,
                ECONOMY_TYPE_COUNT_21.type_count_21,
                IF(ECONOMY_TYPE_COUNT_21.type_count_21 > 0, \'_row-more\', \'\') AS type_count_21_class
            FROM `gaz_hr_people_department` AS HPD
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS crime_count
                FROM `gaz_nifs_crime` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC ON NC.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_280
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 280 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_280 ON NC_CAT_280.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_281
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 281 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_281 ON NC_CAT_281.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_282
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 282 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_282 ON NC_CAT_282.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_283
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 283 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_283 ON NC_CAT_283.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_284
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 284 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_284 ON NC_CAT_284.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_285
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 285 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_285 ON NC_CAT_285.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_286
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 286 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_286 ON NC_CAT_286.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_342
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 342 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_342 ON NC_CAT_342.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_343
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 343 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_343 ON NC_CAT_343.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_344
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 344 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_344 ON NC_CAT_344.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_345
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 345 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_345 ON NC_CAT_345.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_346
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 346 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_346 ON NC_CAT_346.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_347
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 347 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_347 ON NC_CAT_347.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_348
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 348 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_348 ON NC_CAT_348.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_437
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 437 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_437 ON NC_CAT_437.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS doctor_view_count
                FROM `gaz_nifs_doctor_view` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NDV ON NDV.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS anatomy_count
                FROM `gaz_nifs_anatomy` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NA ON NA.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_8
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 8' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_8 ON E_TYPE_COUNT_8.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_9
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 9' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_9 ON E_TYPE_COUNT_9.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_10
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 10' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_10 ON E_TYPE_COUNT_10.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_11
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 11' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_11 ON E_TYPE_COUNT_11.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_12
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 12' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_12 ON E_TYPE_COUNT_12.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_13
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 13' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_13 ON E_TYPE_COUNT_13.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_21
                FROM `gaz_nifs_economy` AS N
                WHERE N.type_id = 21' . $queryStringData . '
                GROUP BY N.department_id
            ) AS ECONOMY_TYPE_COUNT_21 ON ECONOMY_TYPE_COUNT_21.department_id = HPD.id
            WHERE HPD.is_active = 1 AND HPD.id IN(3,4,5,6)');

        if ($query->num_rows() > 0) {

            $i = 1;
            $htmlData .= '<h6>Хүрээлэн</h6>';
            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="2" style="width:30px;">#</th>';
            $htmlData .= '<th rowspan="2" style="min-width:300px;">Гарчиг</th>';
            $htmlData .= '<th colspan="15" style="width:900px;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">Криминалистик</th>';
            $htmlData .= '<th rowspan="2" style="width:80px; cursor:pointer;" class="text-center" data-type="plus" onclick="_reportCrimeFieldCheck({elem: this, prefix: \'' . $tableClassCenter . '\'});"><i class="icon-plus2"></i> <br> Криминалистик нийт</th>';
            $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Шүүх эмнэлэг</th>';
            $htmlData .= '<th colspan="6" style="width:480px;" class="text-center">Тусгай шинжилгээ</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Эдийн засаг</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Нийт</th>';
            $htmlData .= '</tr>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">Дүрс бичлэг</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">Дүр зураг</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">Мөр судлал</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">Гарын мөр</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">Бичиг техник</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">Галт зэвсэг</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">Бичиг судлал</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">Авто техникийн шинжилгээ</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">Тэсрэх төхөөрөмж</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">Компьютер техник</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">Дуу авиа</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">Полиграф</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">Инженер техник</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">Материал судлал</th>';
            $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">Аман зураг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Эмчийн үзлэг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Задлан</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Биологи</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">ДНХ</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Бактериологи</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хими</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Физик</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Бусад</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $rowSum = $row->doctor_view_count + $row->anatomy_count + $row->cat_count_280 + $row->cat_count_281 + $row->cat_count_282 + $row->cat_count_283 + $row->cat_count_284 + $row->cat_count_285 + $row->cat_count_286 + $row->cat_count_342 + $row->cat_count_343 + $row->cat_count_344 + $row->cat_count_345 + $row->cat_count_346 + $row->cat_count_347 + $row->cat_count_348 + $row->cat_count_437 + $row->type_count_8 + $row->type_count_9 + $row->type_count_10 + $row->type_count_11 + $row->type_count_12 + $row->type_count_13 + $row->type_count_21;

                $centerSumDoctorView = $centerSumDoctorView + $row->doctor_view_count;
                $centerSumAnatomy = $centerSumAnatomy + $row->anatomy_count;
                $centerSumCrimeCatCount280 = $centerSumCrimeCatCount280 + $row->cat_count_280;
                $centerSumCrimeCatCount281 = $centerSumCrimeCatCount281 + $row->cat_count_281;
                $centerSumCrimeCatCount282 = $centerSumCrimeCatCount282 + $row->cat_count_282;
                $centerSumCrimeCatCount283 = $centerSumCrimeCatCount283 + $row->cat_count_283;
                $centerSumCrimeCatCount284 = $centerSumCrimeCatCount284 + $row->cat_count_284;
                $centerSumCrimeCatCount285 = $centerSumCrimeCatCount285 + $row->cat_count_285;
                $centerSumCrimeCatCount286 = $centerSumCrimeCatCount286 + $row->cat_count_286;
                $centerSumCrimeCatCount342 = $centerSumCrimeCatCount342 + $row->cat_count_342;
                $centerSumCrimeCatCount343 = $centerSumCrimeCatCount343 + $row->cat_count_343;
                $centerSumCrimeCatCount344 = $centerSumCrimeCatCount344 + $row->cat_count_344;
                $centerSumCrimeCatCount345 = $centerSumCrimeCatCount345 + $row->cat_count_345;
                $centerSumCrimeCatCount346 = $centerSumCrimeCatCount346 + $row->cat_count_346;
                $centerSumCrimeCatCount347 = $centerSumCrimeCatCount347 + $row->cat_count_347;
                $centerSumCrimeCatCount348 = $centerSumCrimeCatCount348 + $row->cat_count_348;
                $centerSumCrimeCatCount437 = $centerSumCrimeCatCount437 + $row->cat_count_437;
                $centerSumCrime = $citySumCrime + $row->crime_count;
                $centerSumExtraTypeCount8 = $citySumExtraTypeCount8 + $row->type_count_8;
                $centerSumExtraTypeCount9 = $citySumExtraTypeCount9 + $row->type_count_9;
                $centerSumExtraTypeCount10 = $citySumExtraTypeCount10 + $row->type_count_10;
                $centerSumExtraTypeCount11 = $citySumExtraTypeCount11 + $row->type_count_11;
                $centerSumExtraTypeCount12 = $citySumExtraTypeCount12 + $row->type_count_12;
                $centerSumExtraTypeCount13 = $citySumExtraTypeCount13 + $row->type_count_13;
                $centerSumEconomyTypeCount21 = $centerSumEconomyTypeCount21 + $row->type_count_21;
                $centerSumTotal = $centerSumTotal + $rowSum;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i . '</td>';
                $htmlData .= '<td style="height:40px;">' . $row->title . '</td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_280_class . ' ' . $tableClassCenter . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=280&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_280 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_281_class . ' ' . $tableClassCenter . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=281&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_281 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_282_class . ' ' . $tableClassCenter . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=282&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_282 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_283_class . ' ' . $tableClassCenter . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=283&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_283 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_284_class . ' ' . $tableClassCenter . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=284&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_284 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_285_class . ' ' . $tableClassCenter . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=285&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_285 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_286_class . ' ' . $tableClassCenter . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=286&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_286 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_342_class . ' ' . $tableClassCenter . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=342&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_342 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_343_class . ' ' . $tableClassCenter . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=343&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_343 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_344_class . ' ' . $tableClassCenter . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=344&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_344 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_345_class . ' ' . $tableClassCenter . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=345&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_345 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_346_class . ' ' . $tableClassCenter . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=346&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_346 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_347_class . ' ' . $tableClassCenter . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=347&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_347 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_348_class . ' ' . $tableClassCenter . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=348&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_348 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_437_class . ' ' . $tableClassCenter . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=437&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_437 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->doctor_view_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->doctor_view_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->anatomy_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($childData['data']['anatomyCount'] > 0 ? $childData['data']['anatomyCount'] : '') . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_8_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=8&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_8 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_9_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=9&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_9 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_10_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=10&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_10 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_11_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=11&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_11 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_12_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=12&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_12 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_13_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=13&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_13 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_21_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=21&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_21 . '</a></td>';

                $htmlData .= '<td class="text-center">' . $rowSum . '</td>';
                $htmlData .= '</tr>';

                $i++;
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td class="text-right">Нийт дүн</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCenter . 'report-crime-field-check"">' . ($centerSumCrimeCatCount280 > 0 ? $centerSumCrimeCatCount280 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">' . ($centerSumCrimeCatCount281 > 0 ? $centerSumCrimeCatCount281 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">' . ($centerSumCrimeCatCount282 > 0 ? $centerSumCrimeCatCount282 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">' . ($centerSumCrimeCatCount283 > 0 ? $centerSumCrimeCatCount283 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">' . ($centerSumCrimeCatCount284 > 0 ? $centerSumCrimeCatCount284 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">' . ($centerSumCrimeCatCount285 > 0 ? $centerSumCrimeCatCount285 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">' . ($centerSumCrimeCatCount286 > 0 ? $centerSumCrimeCatCount286 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">' . ($centerSumCrimeCatCount342 > 0 ? $centerSumCrimeCatCount342 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">' . ($centerSumCrimeCatCount343 > 0 ? $centerSumCrimeCatCount343 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">' . ($centerSumCrimeCatCount344 > 0 ? $centerSumCrimeCatCount344 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">' . ($centerSumCrimeCatCount345 > 0 ? $centerSumCrimeCatCount345 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">' . ($centerSumCrimeCatCount346 > 0 ? $centerSumCrimeCatCount346 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">' . ($centerSumCrimeCatCount347 > 0 ? $centerSumCrimeCatCount347 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">' . ($centerSumCrimeCatCount348 > 0 ? $centerSumCrimeCatCount348 : '') . '</td>';
            $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassCenter . 'report-crime-field-check">' . ($centerSumCrimeCatCount437 > 0 ? $centerSumCrimeCatCount437 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($centerSumCrime > 0 ? $centerSumCrime : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($centerSumDoctorView > 0 ? $centerSumDoctorView : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($centerSumAnatomy > 0 ? $centerSumAnatomy : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($centerSumExtraTypeCount8 > 0 ? $centerSumExtraTypeCount8 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($centerSumExtraTypeCount9 > 0 ? $centerSumExtraTypeCount9 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($centerSumExtraTypeCount10 > 0 ? $centerSumExtraTypeCount10 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($centerSumExtraTypeCount11 > 0 ? $centerSumExtraTypeCount11 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($centerSumExtraTypeCount12 > 0 ? $centerSumExtraTypeCount12 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($centerSumExtraTypeCount13 > 0 ? $centerSumExtraTypeCount13 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($centerSumEconomyTypeCount21 > 0 ? $centerSumEconomyTypeCount21 : '') . '</td>';
            $htmlData .= '<td class="text-center">' . ($centerSumTotal > 0 ? $centerSumTotal : '') . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';
        }

        $htmlData .= '<h6>Улсын нэгдсэн дүн</h6>';
        $htmlData .= '<div class="table-responsive">';
        $htmlData .= '<table class="table _report">';
        $htmlData .= '<thead>';

        $htmlData .= '<tr>';
        $htmlData .= '<th rowspan="2" style="width:30px;">#</th>';
        $htmlData .= '<th rowspan="2" style="min-width:300px;">Гарчиг</th>';
        $htmlData .= '<th colspan="15" style="width:900px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">Криминалистик</th>';
        $htmlData .= '<th rowspan="2" style="width:80px; cursor:pointer;" class="text-center" data-type="plus" onclick="_reportCrimeFieldCheck({elem: this, prefix: \'' . $tableClassAll . '\'});"><i class="icon-plus2"></i> <br> Криминалистик нийт</th>';
        $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Шүүх эмнэлэг</th>';
        $htmlData .= '<th colspan="6" style="width:480px;" class="text-center">Тусгай шинжилгээ</th>';
        $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Эдийн засаг</th>';
        $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Нийт</th>';
        $htmlData .= '</tr>';

        $htmlData .= '<tr>';
        $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassAll . 'report-crime-field-check">Дүрс бичлэг</th>';
        $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassAll . 'report-crime-field-check">Дүр зураг</th>';
        $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassAll . 'report-crime-field-check">Мөр судлал</th>';
        $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassAll . 'report-crime-field-check">Гарын мөр</th>';
        $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassAll . 'report-crime-field-check">Бичиг техник</th>';
        $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassAll . 'report-crime-field-check">Галт зэвсэг</th>';
        $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassAll . 'report-crime-field-check">Бичиг судлал</th>';
        $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassAll . 'report-crime-field-check">Авто техникийн шинжилгээ</th>';
        $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassAll . 'report-crime-field-check">Тэсрэх төхөөрөмж</th>';
        $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassAll . 'report-crime-field-check">Компьютер техник</th>';
        $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassAll . 'report-crime-field-check">Дуу авиа</th>';
        $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassAll . 'report-crime-field-check">Полиграф</th>';
        $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassAll . 'report-crime-field-check">Инженер техник</th>';
        $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassAll . 'report-crime-field-check">Материал судлал</th>';
        $htmlData .= '<th style="width:60px; height:41px; padding:0;" class="text-center ' . $tableClassAll . 'report-crime-field-check">Аман зураг</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">Эмчийн үзлэг</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">Задлан</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">Биологи</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">ДНХ</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">Бактериологи</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">Хими</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">Физик</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">Бусад</th>';
        $htmlData .= '</tr>';

        $htmlData .= '</thead>';

        $htmlData .= '<tbody>';


        $allSumCrime = $allSumDoctorView = $allSumAnatomy = $allSumTotal = 0;

        $allSumCrime = $provinceSumCrime + $citySumCrime + $centerSumCrime;
        $allSumCrimeCatCount280 = $provinceSumCrimeCatCount280 + $citySumCrimeCatCount280 + $centerSumCrimeCatCount280;
        $allSumCrimeCatCount281 = $provinceSumCrimeCatCount281 + $citySumCrimeCatCount281 + $centerSumCrimeCatCount281;
        $allSumCrimeCatCount282 = $provinceSumCrimeCatCount282 + $citySumCrimeCatCount282 + $centerSumCrimeCatCount282;
        $allSumCrimeCatCount283 = $provinceSumCrimeCatCount283 + $citySumCrimeCatCount283 + $centerSumCrimeCatCount283;
        $allSumCrimeCatCount284 = $provinceSumCrimeCatCount284 + $citySumCrimeCatCount284 + $centerSumCrimeCatCount284;
        $allSumCrimeCatCount285 = $provinceSumCrimeCatCount285 + $citySumCrimeCatCount285 + $centerSumCrimeCatCount285;
        $allSumCrimeCatCount286 = $provinceSumCrimeCatCount286 + $citySumCrimeCatCount286 + $centerSumCrimeCatCount286;
        $allSumCrimeCatCount342 = $provinceSumCrimeCatCount342 + $citySumCrimeCatCount342 + $centerSumCrimeCatCount342;
        $allSumCrimeCatCount343 = $provinceSumCrimeCatCount343 + $citySumCrimeCatCount343 + $centerSumCrimeCatCount343;
        $allSumCrimeCatCount344 = $provinceSumCrimeCatCount344 + $citySumCrimeCatCount344 + $centerSumCrimeCatCount344;
        $allSumCrimeCatCount345 = $provinceSumCrimeCatCount345 + $citySumCrimeCatCount345 + $centerSumCrimeCatCount345;
        $allSumCrimeCatCount346 = $provinceSumCrimeCatCount346 + $citySumCrimeCatCount346 + $centerSumCrimeCatCount346;
        $allSumCrimeCatCount347 = $provinceSumCrimeCatCount347 + $citySumCrimeCatCount347 + $centerSumCrimeCatCount347;
        $allSumCrimeCatCount348 = $provinceSumCrimeCatCount348 + $citySumCrimeCatCount348 + $centerSumCrimeCatCount348;
        $allSumCrimeCatCount437 = $provinceSumCrimeCatCount437 + $citySumCrimeCatCount437 + $centerSumCrimeCatCount437;

        $allSumDoctorView = $provinceSumDoctorView + $citySumDoctorView + $centerSumDoctorView;
        $allSumAnatomy = $provinceSumAnatomy + $citySumAnatomy + $centerSumAnatomy;
        $allSumExtraTypeCount8 = $provinceSumExtraTypeCount8 + $citySumExtraTypeCount8 + $centerSumExtraTypeCount8;
        $allSumExtraTypeCount9 = $provinceSumExtraTypeCount9 + $citySumExtraTypeCount9 + $centerSumExtraTypeCount9;
        $allSumExtraTypeCount10 = $provinceSumExtraTypeCount10 + $citySumExtraTypeCount10 + $centerSumExtraTypeCount10;
        $allSumExtraTypeCount11 = $provinceSumExtraTypeCount11 + $citySumExtraTypeCount11 + $centerSumExtraTypeCount11;
        $allSumExtraTypeCount12 = $provinceSumExtraTypeCount12 + $citySumExtraTypeCount12 + $centerSumExtraTypeCount12;
        $allSumExtraTypeCount13 = $provinceSumExtraTypeCount13 + $citySumExtraTypeCount13 + $centerSumExtraTypeCount13;
        $allSumEconomyTypeCount21 = $provinceSumEconomyTypeCount21 + $citySumEconomyTypeCount21 + $centerSumEconomyTypeCount21;
        $allSumTotal = $provinceSumTotal + $citySumTotal + $centerSumTotal;

        $htmlData .= '<tbody>';

        $htmlData .= '<tr>';
        $htmlData .= '<td>1</td>';
        $htmlData .= '<td>Орон нутаг</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($provinceSumCrimeCatCount280 > 0 ? $provinceSumCrimeCatCount280 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($provinceSumCrimeCatCount281 > 0 ? $provinceSumCrimeCatCount281 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($provinceSumCrimeCatCount282 > 0 ? $provinceSumCrimeCatCount282 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($provinceSumCrimeCatCount283 > 0 ? $provinceSumCrimeCatCount283 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($provinceSumCrimeCatCount284 > 0 ? $provinceSumCrimeCatCount284 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($provinceSumCrimeCatCount285 > 0 ? $provinceSumCrimeCatCount285 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($provinceSumCrimeCatCount286 > 0 ? $provinceSumCrimeCatCount286 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($provinceSumCrimeCatCount342 > 0 ? $provinceSumCrimeCatCount342 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($provinceSumCrimeCatCount343 > 0 ? $provinceSumCrimeCatCount343 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($provinceSumCrimeCatCount344 > 0 ? $provinceSumCrimeCatCount344 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($provinceSumCrimeCatCount345 > 0 ? $provinceSumCrimeCatCount345 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($provinceSumCrimeCatCount346 > 0 ? $provinceSumCrimeCatCount346 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($provinceSumCrimeCatCount347 > 0 ? $provinceSumCrimeCatCount347 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($provinceSumCrimeCatCount348 > 0 ? $provinceSumCrimeCatCount348 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($provinceSumCrimeCatCount437 > 0 ? $provinceSumCrimeCatCount437 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($provinceSumCrime > 0 ? $provinceSumCrime : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($provinceSumDoctorView > 0 ? $provinceSumDoctorView : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($provinceSumAnatomy > 0 ? $provinceSumAnatomy : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($provinceSumExtraTypeCount8 > 0 ? $provinceSumExtraTypeCount8 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($provinceSumExtraTypeCount9 > 0 ? $provinceSumExtraTypeCount9 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($provinceSumExtraTypeCount10 > 0 ? $provinceSumExtraTypeCount10 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($provinceSumExtraTypeCount11 > 0 ? $provinceSumExtraTypeCount11 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($provinceSumExtraTypeCount12 > 0 ? $provinceSumExtraTypeCount12 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($provinceSumExtraTypeCount13 > 0 ? $provinceSumExtraTypeCount13 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($provinceSumEconomyTypeCount21 > 0 ? $provinceSumEconomyTypeCount21 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($provinceSumTotal > 0 ? $provinceSumTotal : '') . '</td>';
        $htmlData .= '</tr>';

        $htmlData .= '<tr>';
        $htmlData .= '<td>2</td>';
        $htmlData .= '<td>Нийслэл</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($citySumCrimeCatCount280 > 0 ? $citySumCrimeCatCount280 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($citySumCrimeCatCount281 > 0 ? $citySumCrimeCatCount281 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($citySumCrimeCatCount282 > 0 ? $citySumCrimeCatCount282 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($citySumCrimeCatCount283 > 0 ? $citySumCrimeCatCount283 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($citySumCrimeCatCount284 > 0 ? $citySumCrimeCatCount284 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($citySumCrimeCatCount285 > 0 ? $citySumCrimeCatCount285 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($citySumCrimeCatCount286 > 0 ? $citySumCrimeCatCount286 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($citySumCrimeCatCount342 > 0 ? $citySumCrimeCatCount342 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($citySumCrimeCatCount343 > 0 ? $citySumCrimeCatCount343 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($citySumCrimeCatCount344 > 0 ? $citySumCrimeCatCount344 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($citySumCrimeCatCount345 > 0 ? $citySumCrimeCatCount345 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($citySumCrimeCatCount346 > 0 ? $citySumCrimeCatCount346 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($citySumCrimeCatCount347 > 0 ? $citySumCrimeCatCount347 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($citySumCrimeCatCount348 > 0 ? $citySumCrimeCatCount348 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($citySumCrimeCatCount437 > 0 ? $citySumCrimeCatCount437 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($citySumCrime > 0 ? $citySumCrime : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($citySumDoctorView > 0 ? $citySumDoctorView : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($citySumAnatomy > 0 ? $citySumAnatomy : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($citySumExtraTypeCount8 > 0 ? $citySumExtraTypeCount8 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($citySumExtraTypeCount9 > 0 ? $citySumExtraTypeCount9 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($citySumExtraTypeCount10 > 0 ? $citySumExtraTypeCount10 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($citySumExtraTypeCount11 > 0 ? $citySumExtraTypeCount11 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($citySumExtraTypeCount12 > 0 ? $citySumExtraTypeCount12 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($citySumExtraTypeCount13 > 0 ? $citySumExtraTypeCount13 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($citySumEconomyTypeCount21 > 0 ? $citySumEconomyTypeCount21 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($citySumTotal > 0 ? $citySumTotal : '') . '</td>';
        $htmlData .= '</tr>';

        $htmlData .= '<tr>';
        $htmlData .= '<td>3</td>';
        $htmlData .= '<td>Хүрээлэн</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($centerSumCrimeCatCount280 > 0 ? $centerSumCrimeCatCount280 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($centerSumCrimeCatCount281 > 0 ? $centerSumCrimeCatCount281 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($centerSumCrimeCatCount282 > 0 ? $centerSumCrimeCatCount282 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($centerSumCrimeCatCount283 > 0 ? $centerSumCrimeCatCount283 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($centerSumCrimeCatCount284 > 0 ? $centerSumCrimeCatCount284 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($centerSumCrimeCatCount285 > 0 ? $centerSumCrimeCatCount285 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($centerSumCrimeCatCount286 > 0 ? $centerSumCrimeCatCount286 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($centerSumCrimeCatCount342 > 0 ? $centerSumCrimeCatCount342 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($centerSumCrimeCatCount343 > 0 ? $centerSumCrimeCatCount343 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($centerSumCrimeCatCount344 > 0 ? $centerSumCrimeCatCount344 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($centerSumCrimeCatCount345 > 0 ? $centerSumCrimeCatCount345 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($centerSumCrimeCatCount346 > 0 ? $centerSumCrimeCatCount346 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($centerSumCrimeCatCount347 > 0 ? $centerSumCrimeCatCount347 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($centerSumCrimeCatCount348 > 0 ? $centerSumCrimeCatCount348 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($centerSumCrimeCatCount437 > 0 ? $centerSumCrimeCatCount437 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($centerSumCrime > 0 ? $centerSumCrime : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($centerSumDoctorView > 0 ? $centerSumDoctorView : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($centerSumAnatomy > 0 ? $centerSumAnatomy : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($centerSumExtraTypeCount8 > 0 ? $centerSumExtraTypeCount8 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($centerSumExtraTypeCount9 > 0 ? $centerSumExtraTypeCount9 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($centerSumExtraTypeCount10 > 0 ? $centerSumExtraTypeCount10 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($centerSumExtraTypeCount11 > 0 ? $centerSumExtraTypeCount11 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($centerSumExtraTypeCount12 > 0 ? $centerSumExtraTypeCount12 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($centerSumExtraTypeCount13 > 0 ? $centerSumExtraTypeCount13 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($centerSumEconomyTypeCount21 > 0 ? $centerSumEconomyTypeCount21 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($centerSumTotal > 0 ? $centerSumTotal : '') . '</td>';
        $htmlData .= '</tr>';

        $htmlData .= '</tbody>';
        $htmlData .= '<tfoot>';
        $htmlData .= '<tr>';
        $htmlData .= '<td></td>';
        $htmlData .= '<td>Нийт дүн:</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($allSumCrimeCatCount280 > 0 ? $allSumCrimeCatCount280 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($allSumCrimeCatCount281 > 0 ? $allSumCrimeCatCount281 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($allSumCrimeCatCount282 > 0 ? $allSumCrimeCatCount282 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($allSumCrimeCatCount283 > 0 ? $allSumCrimeCatCount283 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($allSumCrimeCatCount284 > 0 ? $allSumCrimeCatCount284 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($allSumCrimeCatCount285 > 0 ? $allSumCrimeCatCount285 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($allSumCrimeCatCount286 > 0 ? $allSumCrimeCatCount286 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($allSumCrimeCatCount342 > 0 ? $allSumCrimeCatCount342 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($allSumCrimeCatCount343 > 0 ? $allSumCrimeCatCount343 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($allSumCrimeCatCount344 > 0 ? $allSumCrimeCatCount344 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($allSumCrimeCatCount345 > 0 ? $allSumCrimeCatCount345 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($allSumCrimeCatCount346 > 0 ? $allSumCrimeCatCount346 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($allSumCrimeCatCount347 > 0 ? $allSumCrimeCatCount347 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($allSumCrimeCatCount348 > 0 ? $allSumCrimeCatCount348 : '') . '</td>';
        $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $tableClassAll . 'report-crime-field-check">' . ($allSumCrimeCatCount437 > 0 ? $allSumCrimeCatCount437 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($allSumCrime > 0 ? $allSumCrime : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($allSumDoctorView > 0 ? $allSumDoctorView : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($allSumAnatomy > 0 ? $allSumAnatomy : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($allSumExtraTypeCount8 > 0 ? $allSumExtraTypeCount8 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($allSumExtraTypeCount9 > 0 ? $allSumExtraTypeCount9 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($allSumExtraTypeCount10 > 0 ? $allSumExtraTypeCount10 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($allSumExtraTypeCount11 > 0 ? $allSumExtraTypeCount11 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($allSumExtraTypeCount12 > 0 ? $allSumExtraTypeCount12 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($allSumExtraTypeCount13 > 0 ? $allSumExtraTypeCount13 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($allSumEconomyTypeCount21 > 0 ? $allSumEconomyTypeCount21 : '') . '</td>';
        $htmlData .= '<td class="text-center">' . ($allSumTotal > 0 ? $allSumTotal : '') . '</td>';
        $htmlData .= '</tr>';
        $htmlData .= '</tfoot>';
        $htmlData .= '</table>';
        $htmlData .= '</div>';

        return $htmlData;
    }

    public function getReportGeneralOldChildData_model($param = array('parentId' => 0)) {

        $queryStringData = $htmlData = $rootIcon = '';

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['inDate'])) . '\') <= DATE(N.in_date) AND DATE(\'' . date('Y-m-d', strtotime($param['outDate'])) . '\') >= DATE(N.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['inDate'])) . '\') <= DATE(N.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['outDate'])) . '\') >= DATE(N.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $param['year'] . '\'';
        }

        $queryChild = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                NC.crime_count,
                IF(NC.crime_count > 0, \'_row-more\', \'\') AS crime_count_class,
                NC_CAT_280.cat_count_280,
                IF(NC_CAT_280.cat_count_280 > 0, \'_row-more\', \'\') AS cat_count_280_class,
                NC_CAT_281.cat_count_281,
                IF(NC_CAT_281.cat_count_281 > 0, \'_row-more\', \'\') AS cat_count_281_class,
                NC_CAT_282.cat_count_282,
                IF(NC_CAT_282.cat_count_282 > 0, \'_row-more\', \'\') AS cat_count_282_class,
                NC_CAT_283.cat_count_283,
                IF(NC_CAT_283.cat_count_283 > 0, \'_row-more\', \'\') AS cat_count_283_class,
                NC_CAT_284.cat_count_284,
                IF(NC_CAT_284.cat_count_284 > 0, \'_row-more\', \'\') AS cat_count_284_class,
                NC_CAT_285.cat_count_285,
                IF(NC_CAT_285.cat_count_285 > 0, \'_row-more\', \'\') AS cat_count_285_class,
                NC_CAT_286.cat_count_286,
                IF(NC_CAT_286.cat_count_286 > 0, \'_row-more\', \'\') AS cat_count_286_class,
                NC_CAT_342.cat_count_342,
                IF(NC_CAT_342.cat_count_342 > 0, \'_row-more\', \'\') AS cat_count_342_class,
                NC_CAT_343.cat_count_343,
                IF(NC_CAT_343.cat_count_343 > 0, \'_row-more\', \'\') AS cat_count_343_class,
                NC_CAT_344.cat_count_344,
                IF(NC_CAT_344.cat_count_344 > 0, \'_row-more\', \'\') AS cat_count_344_class,
                NC_CAT_345.cat_count_345,
                IF(NC_CAT_345.cat_count_345 > 0, \'_row-more\', \'\') AS cat_count_345_class,
                NC_CAT_346.cat_count_346,
                IF(NC_CAT_346.cat_count_346 > 0, \'_row-more\', \'\') AS cat_count_346_class,
                NC_CAT_347.cat_count_347,
                IF(NC_CAT_347.cat_count_347 > 0, \'_row-more\', \'\') AS cat_count_347_class,
                NC_CAT_348.cat_count_348,
                IF(NC_CAT_348.cat_count_348 > 0, \'_row-more\', \'\') AS cat_count_348_class,
                NC_CAT_437.cat_count_437,
                IF(NC_CAT_437.cat_count_437 > 0, \'_row-more\', \'\') AS cat_count_437_class,
                NDV.doctor_view_count,
                IF(NDV.doctor_view_count > 0, \'_row-more\', \'\') AS doctor_view_count_class,
                NA.anatomy_count,
                IF(NA.anatomy_count > 0, \'_row-more\', \'\') AS anatomy_count_class,
                E_TYPE_COUNT_8.type_count_8,
                IF(E_TYPE_COUNT_8.type_count_8 > 0, \'_row-more\', \'\') AS type_count_8_class,
                E_TYPE_COUNT_9.type_count_9,
                IF(E_TYPE_COUNT_9.type_count_9 > 0, \'_row-more\', \'\') AS type_count_9_class,
                E_TYPE_COUNT_10.type_count_10,
                IF(E_TYPE_COUNT_10.type_count_10 > 0, \'_row-more\', \'\') AS type_count_10_class,
                E_TYPE_COUNT_11.type_count_11,
                IF(E_TYPE_COUNT_11.type_count_11 > 0, \'_row-more\', \'\') AS type_count_11_class,
                E_TYPE_COUNT_12.type_count_12,
                IF(E_TYPE_COUNT_12.type_count_12 > 0, \'_row-more\', \'\') AS type_count_12_class,
                E_TYPE_COUNT_13.type_count_13,
                IF(E_TYPE_COUNT_13.type_count_13 > 0, \'_row-more\', \'\') AS type_count_13_class,
                ECONOMY_TYPE_COUNT_21.type_count_21,
                IF(ECONOMY_TYPE_COUNT_21.type_count_21 > 0, \'_row-more\', \'\') AS type_count_21_class
            FROM `gaz_hr_people_department` AS HPD
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS crime_count
                FROM `gaz_nifs_crime` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC ON NC.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_280
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 280 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_280 ON NC_CAT_280.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_281
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 281 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_281 ON NC_CAT_281.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_282
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 282 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_282 ON NC_CAT_282.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_283
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 283 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_283 ON NC_CAT_283.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_284
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 284 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_284 ON NC_CAT_284.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_285
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 285 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_285 ON NC_CAT_285.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_286
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 286 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_286 ON NC_CAT_286.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_342
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 342 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_342 ON NC_CAT_342.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_343
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 343 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_343 ON NC_CAT_343.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_344
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 344 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_344 ON NC_CAT_344.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_345
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 345 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_345 ON NC_CAT_345.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_346
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 346 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_346 ON NC_CAT_346.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_347
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 347 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_347 ON NC_CAT_347.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_348
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 348 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_348 ON NC_CAT_348.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS cat_count_437
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 437 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_437 ON NC_CAT_437.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS doctor_view_count
                FROM `gaz_nifs_doctor_view` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NDV ON NDV.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS anatomy_count
                FROM `gaz_nifs_anatomy` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NA ON NA.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_8
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 8' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_8 ON E_TYPE_COUNT_8.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_9
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 9' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_9 ON E_TYPE_COUNT_9.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_10
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 10' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_10 ON E_TYPE_COUNT_10.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_11
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 11' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_11 ON E_TYPE_COUNT_11.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_12
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 12' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_12 ON E_TYPE_COUNT_12.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_13
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 13' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_13 ON E_TYPE_COUNT_13.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_21
                FROM `gaz_nifs_economy` AS N
                WHERE N.type_id = 21' . $queryStringData . '
                GROUP BY N.department_id
            ) AS ECONOMY_TYPE_COUNT_21 ON ECONOMY_TYPE_COUNT_21.department_id = HPD.id
            WHERE HPD.is_active = 1 AND HPD.parent_id = ' . $param['parentId']);

        $childNumRows = $queryChild->num_rows();
        if ($childNumRows > 0) {

            $i = 1;

            foreach ($queryChild->result() as $key => $row) {

                $rowSum = $row->doctor_view_count + $row->anatomy_count + $row->cat_count_280 + $row->cat_count_281 + $row->cat_count_282 + $row->cat_count_283 + $row->cat_count_284 + $row->cat_count_285 + $row->cat_count_286 + $row->cat_count_342 + $row->cat_count_343 + $row->cat_count_344 + $row->cat_count_345 + $row->cat_count_346 + $row->cat_count_347 + $row->cat_count_348 + $row->cat_count_437 + $row->type_count_8 + $row->type_count_9 + $row->type_count_10 + $row->type_count_11 + $row->type_count_12 + $row->type_count_13 + $row->type_count_21;
                $childRowClass = $param['prefix'] . 'row-report-crime-child-' . $param['parentId'];
                $rootIcon = '<i class="icon-plus2" onclick="_nifsReportShowHideRow({elem:this, class: \'' . $childRowClass . '\'});" style="cursor:pointer;"></i>';

                if (empty($key)) {

                    $htmlData .= '<tr class="' . $childRowClass . '" style="display:none; border-top:2px solid rgba(0,0,0,.6);">';
                    $htmlData .= '<td>' . $param['rowNumber'] . '.' . $i . '</td>';
                    $htmlData .= '<td style="padding-left: ' . $param['space'] . 'px; padding-bottom:1px;"><i>' . $param['rootRow']->title . '</i></td>';
                    $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $param['rootRow']->cat_count_280_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=280&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->cat_count_280 . '</a></td>';
                    $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $param['rootRow']->cat_count_281_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=281&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->cat_count_281 . '</a></td>';
                    $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $param['rootRow']->cat_count_282_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=282&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->cat_count_282 . '</a></td>';
                    $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $param['rootRow']->cat_count_283_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=283&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->cat_count_283 . '</a></td>';
                    $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $param['rootRow']->cat_count_284_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=284&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->cat_count_284 . '</a></td>';
                    $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $param['rootRow']->cat_count_285_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=285&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->cat_count_285 . '</a></td>';
                    $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $param['rootRow']->cat_count_286_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=286&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->cat_count_286 . '</a></td>';
                    $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $param['rootRow']->cat_count_342_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=342&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->cat_count_342 . '</a></td>';
                    $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $param['rootRow']->cat_count_343_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=343&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->cat_count_343 . '</a></td>';
                    $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $param['rootRow']->cat_count_344_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=344&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->cat_count_344 . '</a></td>';
                    $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $param['rootRow']->cat_count_345_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=345&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->cat_count_345 . '</a></td>';
                    $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $param['rootRow']->cat_count_346_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=346&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->cat_count_346 . '</a></td>';
                    $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $param['rootRow']->cat_count_347_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=347&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->cat_count_347 . '</a></td>';
                    $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $param['rootRow']->cat_count_348_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=348&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->cat_count_348 . '</a></td>';
                    $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $param['rootRow']->cat_count_437_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=437&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->cat_count_437 . '</a></td>';
                    $htmlData .= '<td class="text-center ' . $param['rootRow']->crime_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->crime_count . '</a></td>';
                    $htmlData .= '<td class="text-center ' . $param['rootRow']->doctor_view_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->doctor_view_count . '</a></td>';
                    $htmlData .= '<td class="text-center ' . $param['rootRow']->anatomy_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->anatomy_count . '</a></td>';
                    $htmlData .= '<td class="text-center ' . $param['rootRow']->type_count_8_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=8&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->type_count_8 . '</a></td>';
                    $htmlData .= '<td class="text-center ' . $param['rootRow']->type_count_9_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=9&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->type_count_9 . '</a></td>';
                    $htmlData .= '<td class="text-center ' . $param['rootRow']->type_count_10_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=10&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->type_count_10 . '</a></td>';
                    $htmlData .= '<td class="text-center ' . $param['rootRow']->type_count_11_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=11&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->type_count_11 . '</a></td>';
                    $htmlData .= '<td class="text-center ' . $param['rootRow']->type_count_12_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=12&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->type_count_12 . '</a></td>';
                    $htmlData .= '<td class="text-center ' . $param['rootRow']->type_count_13_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=13&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->type_count_13 . '</a></td>';
                    $htmlData .= '<td class="text-center ' . $param['rootRow']->type_count_21_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=21&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $param['rootRow']->type_count_21 . '</a></td>';

                    $htmlData .= '<td class="text-center">---</td>';
                    $htmlData .= '</tr>';
                    $i++;
                }

                $htmlData .= '<tr class="' . $childRowClass . '" style="display:none;">';
                $htmlData .= '<td>' . $param['rowNumber'] . '.' . $i . '</td>';
                $htmlData .= '<td style="padding-left: ' . $param['space'] . 'px;"><i>' . $row->title . '</i></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_280_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=280&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_280 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_281_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=281&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_281 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_282_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=282&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_282 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_283_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=283&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_283 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_284_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=284&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_284 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_285_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=285&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_285 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_286_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=286&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_286 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_342_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=342&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_342 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_343_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=343&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_343 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_344_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=344&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_344 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_345_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=345&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_345 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_346_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=346&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_346 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_347_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=347&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_347 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_348_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=348&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_348 . '</a></td>';
                $htmlData .= '<td style="width:60px; height:40px;" class="text-center ' . $row->cat_count_437_class . ' ' . $param['prefix'] . 'report-crime-field-check"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'catId=437&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->cat_count_437 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_count_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->doctor_view_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->doctor_view_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->anatomy_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->anatomy_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_8_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=8&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_8 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_9_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=9&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_9 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_10_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=10&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_10 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_11_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=11&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_11 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_12_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=12&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_12 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_13_class . '"><a href="javascript:;" onclick="_initNifsExtra({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=13&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_13 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->type_count_21_class . '"><a href="javascript:;" onclick="_initNifsEconomy({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&typeId=21&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->type_count_21 . '</a></td>';

                $htmlData .= '<td class="text-center">' . $rowSum . '</td>';
                $htmlData .= '</tr>';

                $param['catCount280'] = $param['catCount280'] + $row->cat_count_280;
                $param['catCount281'] = $param['catCount281'] + $row->cat_count_281;
                $param['catCount282'] = $param['catCount282'] + $row->cat_count_282;
                $param['catCount283'] = $param['catCount283'] + $row->cat_count_283;
                $param['catCount284'] = $param['catCount284'] + $row->cat_count_284;
                $param['catCount285'] = $param['catCount285'] + $row->cat_count_285;
                $param['catCount286'] = $param['catCount286'] + $row->cat_count_286;
                $param['catCount342'] = $param['catCount342'] + $row->cat_count_342;
                $param['catCount343'] = $param['catCount343'] + $row->cat_count_343;
                $param['catCount344'] = $param['catCount344'] + $row->cat_count_344;
                $param['catCount345'] = $param['catCount345'] + $row->cat_count_345;
                $param['catCount346'] = $param['catCount346'] + $row->cat_count_346;
                $param['catCount347'] = $param['catCount347'] + $row->cat_count_347;
                $param['catCount348'] = $param['catCount348'] + $row->cat_count_348;
                $param['catCount437'] = $param['catCount437'] + $row->cat_count_437;
                $param['crimeCount'] = $param['crimeCount'] + $row->crime_count;
                $param['doctorViewCount'] = $param['doctorViewCount'] + $row->doctor_view_count;
                $param['anatomyCount'] = $param['anatomyCount'] + $row->anatomy_count;
                $param['typeCount8'] = $param['typeCount8'] + $row->type_count_8;
                $param['typeCount9'] = $param['typeCount9'] + $row->type_count_9;
                $param['typeCount10'] = $param['typeCount10'] + $row->type_count_10;
                $param['typeCount11'] = $param['typeCount11'] + $row->type_count_11;
                $param['typeCount12'] = $param['typeCount12'] + $row->type_count_12;
                $param['typeCount13'] = $param['typeCount13'] + $row->type_count_13;
                $param['typeCount21'] = $param['typeCount21'] + $row->type_count_21;
                $param['rowSum'] = $param['rowSum'] + $rowSum;

                $i++;
            }
        }

        return array('html' => $htmlData, 'data' => $param, 'numRows' => $childNumRows, 'icon' => $rootIcon);
    }

    public function getExtrnalReportGeneralData_model($param = array()) {

        $queryStringData = $htmlData = '';

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['inDate'])) . '\') <= DATE(N.in_date) AND DATE(\'' . date('Y-m-d', strtotime($param['outDate'])) . '\') >= DATE(N.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['inDate'])) . '\') <= DATE(N.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['outDate'])) . '\') >= DATE(N.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $param['year'] . '\'';
        }

        $provinceSumCrime = $provinceSumDoctorView = $provinceSumAnatomy = $provinceSumCrimeCatCount280 = $provinceSumCrimeCatCount281 = $provinceSumCrimeCatCount282 = $provinceSumCrimeCatCount283 = $provinceSumCrimeCatCount284 = $provinceSumCrimeCatCount285 = $provinceSumCrimeCatCount286 = $provinceSumCrimeCatCount342 = $provinceSumCrimeCatCount343 = $provinceSumCrimeCatCount344 = $provinceSumCrimeCatCount345 = $provinceSumCrimeCatCount346 = $provinceSumCrimeCatCount347 = $provinceSumCrimeCatCount348 = $provinceSumCrimeCatCount437 = $provinceSumExtraTypeCount8 = $provinceSumExtraTypeCount9 = $provinceSumExtraTypeCount10 = $provinceSumExtraTypeCount11 = $provinceSumExtraTypeCount12 = $provinceSumExtraTypeCount13 = $provinceSumEconomyTypeCount21 = $provinceSumTotal = 0;


        $citySumCrime = $citySumDoctorView = $citySumAnatomy = $citySumCrimeCatCount280 = $citySumCrimeCatCount281 = $citySumCrimeCatCount282 = $citySumCrimeCatCount283 = $citySumCrimeCatCount284 = $citySumCrimeCatCount285 = $citySumCrimeCatCount286 = $citySumCrimeCatCount342 = $citySumCrimeCatCount343 = $citySumCrimeCatCount344 = $citySumCrimeCatCount345 = $citySumCrimeCatCount346 = $citySumCrimeCatCount347 = $citySumCrimeCatCount348 = $citySumCrimeCatCount437 = $citySumExtraTypeCount8 = $citySumExtraTypeCount9 = $citySumExtraTypeCount10 = $citySumExtraTypeCount11 = $citySumExtraTypeCount12 = $citySumExtraTypeCount13 = $citySumEconomyTypeCount21 = $citySumTotal = 0;


        $centerSumCrime = $centerSumDoctorView = $centerSumAnatomy = $centerSumCrimeCatCount280 = $centerSumCrimeCatCount281 = $centerSumCrimeCatCount282 = $centerSumCrimeCatCount283 = $centerSumCrimeCatCount284 = $centerSumCrimeCatCount285 = $centerSumCrimeCatCount286 = $centerSumCrimeCatCount342 = $centerSumCrimeCatCount343 = $centerSumCrimeCatCount344 = $centerSumCrimeCatCount345 = $centerSumCrimeCatCount346 = $centerSumCrimeCatCount347 = $centerSumCrimeCatCount348 = $centerSumCrimeCatCount437 = $centerSumExtraTypeCount8 = $centerSumExtraTypeCount9 = $centerSumExtraTypeCount10 = $centerSumExtraTypeCount11 = $centerSumExtraTypeCount12 = $centerSumExtraTypeCount13 = $centerSumEconomyTypeCount21 = $centerSumTotal = 0;
        $htmlData .= '<style>.text-center {text-align:center !important;}</style>';
        $htmlData .= '<div class="workplace">';

        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                NC.crime_count,
                NDV.doctor_view_count,
                NA.anatomy_count,
                E_TYPE_COUNT_8.type_count_8,
                E_TYPE_COUNT_9.type_count_9,
                E_TYPE_COUNT_10.type_count_10,
                E_TYPE_COUNT_11.type_count_11,
                E_TYPE_COUNT_12.type_count_12,
                E_TYPE_COUNT_13.type_count_13,
                ECONOMY_TYPE_COUNT_21.type_count_21
            FROM `gaz_hr_people_department` AS HPD
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS crime_count
                FROM `gaz_nifs_crime` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC ON NC.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS doctor_view_count
                FROM `gaz_nifs_doctor_view` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NDV ON NDV.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS anatomy_count
                FROM `gaz_nifs_anatomy` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NA ON NA.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_8
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 8' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_8 ON E_TYPE_COUNT_8.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_9
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 9' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_9 ON E_TYPE_COUNT_9.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_10
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 10' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_10 ON E_TYPE_COUNT_10.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_11
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 11' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_11 ON E_TYPE_COUNT_11.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_12
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 12' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_12 ON E_TYPE_COUNT_12.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_13
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 13' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_13 ON E_TYPE_COUNT_13.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_21
                FROM `gaz_nifs_economy` AS N
                WHERE N.type_id = 21' . $queryStringData . '
                GROUP BY N.department_id
            ) AS ECONOMY_TYPE_COUNT_21 ON ECONOMY_TYPE_COUNT_21.department_id = HPD.id
            WHERE HPD.is_active = 1 AND HPD.parent_id = 18');

        if ($query->num_rows() > 0) {

            $i = 1;

            $htmlData .= '<div class="row-fluid">';
            $htmlData .= '<div class="span12">';
            $htmlData .= '<div class="head clearfix">';
            $htmlData .= '<div class="isw-grid"></div>';
            $htmlData .= '<h1>Орон нутаг</h1>';
            $htmlData .= '</div>';

            $htmlData .= '<div class="block-fluid table-sorting clearfix">';

            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="2" style="width:30px;">#</th>';
            $htmlData .= '<th rowspan="2" style="min-width:300px;">Гарчиг</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Криминалистик</th>';
            $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Шүүх эмнэлэг</th>';
            $htmlData .= '<th colspan="6" style="width:480px;" class="text-center">Тусгай шинжилгээ</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Эдийн засаг</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Нийт</th>';
            $htmlData .= '</tr>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:80px;" class="text-center">Эмчийн үзлэг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Задлан</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Биологи</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">ДНХ</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Бактериологи</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хими</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Физик</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Бусад</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';
            $htmlData .= '</tbody>';

            foreach ($query->result() as $key => $row) {

                $rowSum = $row->crime_count + $row->doctor_view_count + $row->anatomy_count + $row->type_count_8 + $row->type_count_9 + $row->type_count_10 + $row->type_count_11 + $row->type_count_12 + $row->type_count_13 + $row->type_count_21;

//                $htmlData .= '<tr>';
//                $htmlData .= '<td>' . $i . '</td>';
//                $htmlData .= '<td>' . $row->title . '</td>';
//                $htmlData .= '<td class="text-center">' . $row->crime_count . '</td>';
//                $htmlData .= '<td class="text-center">' . $row->doctor_view_count . '</td>';
//                $htmlData .= '<td class="text-center">' . $row->anatomy_count . '</td>';
//                $htmlData .= '<td class="text-center">' . $row->type_count_8 . '</td>';
//                $htmlData .= '<td class="text-center">' . $row->type_count_9 . '</td>';
//                $htmlData .= '<td class="text-center">' . $row->type_count_10 . '</td>';
//                $htmlData .= '<td class="text-center">' . $row->type_count_11 . '</td>';
//                $htmlData .= '<td class="text-center">' . $row->type_count_12 . '</td>';
//                $htmlData .= '<td class="text-center">' . $row->type_count_13 . '</td>';
//                $htmlData .= '<td class="text-center">' . $row->type_count_21 . '</td>';
//
//                $htmlData .= '<td class="text-center">' . $rowSum . '</td>';
//                
//                $htmlData .= '</tr>';
                //getExtrnalReportGeneralChildData_model
                $childData = self::getExtrnalReportGeneralChildData_model(array(
                            'parentId' => $row->id,
                            'rowNumber' => $i,
                            'inDate' => $param['inDate'],
                            'outDate' => $param['outDate'],
                            'space' => 30,
                            'crimeCount' => $row->crime_count,
                            'doctorViewCount' => $row->doctor_view_count,
                            'anatomyCount' => $row->anatomy_count,
                            'typeCount8' => $row->type_count_8,
                            'typeCount9' => $row->type_count_9,
                            'typeCount10' => $row->type_count_10,
                            'typeCount11' => $row->type_count_11,
                            'typeCount12' => $row->type_count_12,
                            'typeCount13' => $row->type_count_13,
                            'typeCount21' => $row->type_count_21,
                            'rowSum' => $rowSum));

                $provinceSumDoctorView = $provinceSumDoctorView + $childData['data']['doctorViewCount'];
                $provinceSumAnatomy = $provinceSumAnatomy + $childData['data']['anatomyCount'];
                $provinceSumCrime = $provinceSumCrime + $childData['data']['crimeCount'];
                $provinceSumExtraTypeCount8 = $provinceSumExtraTypeCount8 + $childData['data']['typeCount8'];
                $provinceSumExtraTypeCount9 = $provinceSumExtraTypeCount9 + $childData['data']['typeCount9'];
                $provinceSumExtraTypeCount10 = $provinceSumExtraTypeCount10 + $childData['data']['typeCount10'];
                $provinceSumExtraTypeCount11 = $provinceSumExtraTypeCount11 + $childData['data']['typeCount11'];
                $provinceSumExtraTypeCount12 = $provinceSumExtraTypeCount12 + $childData['data']['typeCount12'];
                $provinceSumExtraTypeCount13 = $provinceSumExtraTypeCount13 + $childData['data']['typeCount13'];
                $provinceSumEconomyTypeCount21 = $provinceSumEconomyTypeCount21 + $childData['data']['typeCount21'];
                $provinceSumTotal = $provinceSumTotal + $childData['data']['rowSum'];

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center">' . $childData['data']['crimeCount'] . '</td>';
                $htmlData .= '<td class="text-center">' . $childData['data']['doctorViewCount'] . '</td>';
                $htmlData .= '<td class="text-center">' . $childData['data']['anatomyCount'] . '</td>';
                $htmlData .= '<td class="text-center">' . $childData['data']['typeCount8'] . '</td>';
                $htmlData .= '<td class="text-center">' . $childData['data']['typeCount9'] . '</td>';
                $htmlData .= '<td class="text-center">' . $childData['data']['typeCount10'] . '</td>';
                $htmlData .= '<td class="text-center">' . $childData['data']['typeCount11'] . '</td>';
                $htmlData .= '<td class="text-center">' . $childData['data']['typeCount12'] . '</td>';
                $htmlData .= '<td class="text-center">' . $childData['data']['typeCount13'] . '</td>';
                $htmlData .= '<td class="text-center">' . $childData['data']['typeCount21'] . '</td>';

                $htmlData .= '<td class="text-center">' . $childData['data']['rowSum'] . '</td>';

                $htmlData .= '</tr>';

                $htmlData .= $childData['html'];

                $i++;
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td class="text-right">Нийт дүн</td>';
            $htmlData .= '<td class="text-center">' . $provinceSumCrime . '</td>';
            $htmlData .= '<td class="text-center">' . $provinceSumDoctorView . '</td>';
            $htmlData .= '<td class="text-center">' . $provinceSumAnatomy . '</td>';
            $htmlData .= '<td class="text-center">' . $provinceSumExtraTypeCount8 . '</td>';
            $htmlData .= '<td class="text-center">' . $provinceSumExtraTypeCount9 . '</td>';
            $htmlData .= '<td class="text-center">' . $provinceSumExtraTypeCount10 . '</td>';
            $htmlData .= '<td class="text-center">' . $provinceSumExtraTypeCount11 . '</td>';
            $htmlData .= '<td class="text-center">' . $provinceSumExtraTypeCount12 . '</td>';
            $htmlData .= '<td class="text-center">' . $provinceSumExtraTypeCount13 . '</td>';
            $htmlData .= '<td class="text-center">' . $provinceSumEconomyTypeCount21 . '</td>';
            $htmlData .= '<td class="text-center">' . $provinceSumTotal . '</td>';
            $htmlData .= '</tr>';

            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';

            $htmlData .= '</div>';
            $htmlData .= '</div>';
            $htmlData .= '</div>';
        }

        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                NC.crime_count,
                NDV.doctor_view_count,
                IF(NDV.doctor_view_count > 0, \'_row-more\', \'\') AS doctor_view_count_class,
                NA.anatomy_count,
                IF(NA.anatomy_count > 0, \'_row-more\', \'\') AS anatomy_count_class,
                E_TYPE_COUNT_8.type_count_8,
                IF(E_TYPE_COUNT_8.type_count_8 > 0, \'_row-more\', \'\') AS type_count_8_class,
                E_TYPE_COUNT_9.type_count_9,
                IF(E_TYPE_COUNT_9.type_count_9 > 0, \'_row-more\', \'\') AS type_count_9_class,
                E_TYPE_COUNT_10.type_count_10,
                IF(E_TYPE_COUNT_10.type_count_10 > 0, \'_row-more\', \'\') AS type_count_10_class,
                E_TYPE_COUNT_11.type_count_11,
                IF(E_TYPE_COUNT_11.type_count_11 > 0, \'_row-more\', \'\') AS type_count_11_class,
                E_TYPE_COUNT_12.type_count_12,
                IF(E_TYPE_COUNT_12.type_count_12 > 0, \'_row-more\', \'\') AS type_count_12_class,
                E_TYPE_COUNT_13.type_count_13,
                IF(E_TYPE_COUNT_13.type_count_13 > 0, \'_row-more\', \'\') AS type_count_13_class,
                ECONOMY_TYPE_COUNT_21.type_count_21,
                IF(ECONOMY_TYPE_COUNT_21.type_count_21 > 0, \'_row-more\', \'\') AS type_count_21_class
            FROM `gaz_hr_people_department` AS HPD
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS crime_count
                FROM `gaz_nifs_crime` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC ON NC.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS doctor_view_count
                FROM `gaz_nifs_doctor_view` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NDV ON NDV.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS anatomy_count
                FROM `gaz_nifs_anatomy` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NA ON NA.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_8
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 8' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_8 ON E_TYPE_COUNT_8.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_9
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 9' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_9 ON E_TYPE_COUNT_9.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_10
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 10' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_10 ON E_TYPE_COUNT_10.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_11
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 11' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_11 ON E_TYPE_COUNT_11.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_12
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 12' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_12 ON E_TYPE_COUNT_12.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_13
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 13' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_13 ON E_TYPE_COUNT_13.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_21
                FROM `gaz_nifs_economy` AS N
                WHERE N.type_id = 21' . $queryStringData . '
                GROUP BY N.department_id
            ) AS ECONOMY_TYPE_COUNT_21 ON ECONOMY_TYPE_COUNT_21.department_id = HPD.id
            WHERE HPD.is_active = 1 AND HPD.parent_id = 8');

        if ($query->num_rows() > 0) {

            $i = 1;

            $htmlData .= '<div class="row-fluid">';
            $htmlData .= '<div class="span12">';
            $htmlData .= '<div class="head clearfix">';
            $htmlData .= '<div class="isw-grid"></div>';
            $htmlData .= '<h1>Нийслэл</h1>';
            $htmlData .= '</div>';

            $htmlData .= '<div class="block-fluid table-sorting clearfix">';

            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="2" style="width:30px;">#</th>';
            $htmlData .= '<th rowspan="2" style="min-width:300px;">Гарчиг</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Криминалистик</th>';
            $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Шүүх эмнэлэг</th>';
            $htmlData .= '<th colspan="6" style="width:480px;" class="text-center">Тусгай шинжилгээ</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Эдийн засаг</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Нийт</th>';
            $htmlData .= '</tr>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:80px;" class="text-center">Эмчийн үзлэг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Задлан</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Биологи</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">ДНХ</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Бактериологи</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хими</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Физик</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Бусад</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $rowSum = $row->crime_count + $row->doctor_view_count + $row->anatomy_count + $row->type_count_8 + $row->type_count_9 + $row->type_count_10 + $row->type_count_11 + $row->type_count_12 + $row->type_count_13 + $row->type_count_21;
//                $citySumCrime = $citySumCrime + $row->crime_count;
//                $citySumDoctorView = $citySumDoctorView + $row->doctor_view_count;
//                $citySumAnatomy = $citySumAnatomy + $row->anatomy_count;
//                $citySumExtraTypeCount8 = $citySumExtraTypeCount8 + $row->type_count_8;
//                $citySumExtraTypeCount9 = $citySumExtraTypeCount9 + $row->type_count_9;
//                $citySumExtraTypeCount10 = $citySumExtraTypeCount10 + $row->type_count_10;
//                $citySumExtraTypeCount11 = $citySumExtraTypeCount11 + $row->type_count_11;
//                $citySumExtraTypeCount12 = $citySumExtraTypeCount12 + $row->type_count_12;
//                $citySumExtraTypeCount13 = $citySumExtraTypeCount13 + $row->type_count_13;
//                $citySumEconomyTypeCount21 = $citySumEconomyTypeCount21 + $row->type_count_21;
//                $citySumTotal = $citySumTotal + $rowSum;

                $childData = self::getExtrnalReportGeneralChildData_model(array(
                            'parentId' => $row->id,
                            'rowNumber' => $i,
                            'inDate' => $param['inDate'],
                            'outDate' => $param['outDate'],
                            'space' => 30,
                            'crimeCount' => $row->crime_count,
                            'doctorViewCount' => $row->doctor_view_count,
                            'anatomyCount' => $row->anatomy_count,
                            'typeCount8' => $row->type_count_8,
                            'typeCount9' => $row->type_count_9,
                            'typeCount10' => $row->type_count_10,
                            'typeCount11' => $row->type_count_11,
                            'typeCount12' => $row->type_count_12,
                            'typeCount13' => $row->type_count_13,
                            'typeCount21' => $row->type_count_21,
                            'rowSum' => $rowSum));

                $citySumDoctorView = $citySumDoctorView + $childData['data']['doctorViewCount'];
                $citySumAnatomy = $citySumAnatomy + $childData['data']['anatomyCount'];
                $citySumCrime = $citySumCrime + $childData['data']['crimeCount'];
                $citySumExtraTypeCount8 = $citySumExtraTypeCount8 + $childData['data']['typeCount8'];
                $citySumExtraTypeCount9 = $citySumExtraTypeCount9 + $childData['data']['typeCount9'];
                $citySumExtraTypeCount10 = $citySumExtraTypeCount10 + $childData['data']['typeCount10'];
                $citySumExtraTypeCount11 = $citySumExtraTypeCount11 + $childData['data']['typeCount11'];
                $citySumExtraTypeCount12 = $citySumExtraTypeCount12 + $childData['data']['typeCount12'];
                $citySumExtraTypeCount13 = $citySumExtraTypeCount13 + $childData['data']['typeCount13'];
                $citySumEconomyTypeCount21 = $citySumEconomyTypeCount21 + $childData['data']['typeCount21'];
                $citySumTotal = $citySumTotal + $childData['data']['rowSum'];

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center">' . $childData['data']['crimeCount'] . '</td>';
                $htmlData .= '<td class="text-center">' . $childData['data']['doctorViewCount'] . '</td>';
                $htmlData .= '<td class="text-center">' . $childData['data']['anatomyCount'] . '</td>';
                $htmlData .= '<td class="text-center">' . $childData['data']['typeCount8'] . '</td>';
                $htmlData .= '<td class="text-center">' . $childData['data']['typeCount9'] . '</td>';
                $htmlData .= '<td class="text-center">' . $childData['data']['typeCount10'] . '</td>';
                $htmlData .= '<td class="text-center">' . $childData['data']['typeCount11'] . '</td>';
                $htmlData .= '<td class="text-center">' . $childData['data']['typeCount12'] . '</td>';
                $htmlData .= '<td class="text-center">' . $childData['data']['typeCount13'] . '</td>';
                $htmlData .= '<td class="text-center">' . $childData['data']['typeCount21'] . '</td>';

                $htmlData .= '<td class="text-center">' . $childData['data']['rowSum'] . '</td>';

                $htmlData .= '</tr>';

                $i++;
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td class="text-right">Нийт дүн</td>';
            $htmlData .= '<td class="text-center">' . $citySumCrime . '</td>';
            $htmlData .= '<td class="text-center">' . $citySumDoctorView . '</td>';
            $htmlData .= '<td class="text-center">' . $citySumAnatomy . '</td>';
            $htmlData .= '<td class="text-center">' . $citySumExtraTypeCount8 . '</td>';
            $htmlData .= '<td class="text-center">' . $citySumExtraTypeCount9 . '</td>';
            $htmlData .= '<td class="text-center">' . $citySumExtraTypeCount10 . '</td>';
            $htmlData .= '<td class="text-center">' . $citySumExtraTypeCount11 . '</td>';
            $htmlData .= '<td class="text-center">' . $citySumExtraTypeCount12 . '</td>';
            $htmlData .= '<td class="text-center">' . $citySumExtraTypeCount13 . '</td>';
            $htmlData .= '<td class="text-center">' . $citySumEconomyTypeCount21 . '</td>';
            $htmlData .= '<td class="text-center">' . $citySumTotal . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';
            $htmlData .= '</div>';
            $htmlData .= '</div>';
        }


        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                NC.crime_count,
                NDV.doctor_view_count,
                NA.anatomy_count,
                E_TYPE_COUNT_8.type_count_8,
                E_TYPE_COUNT_9.type_count_9,
                E_TYPE_COUNT_10.type_count_10,
                E_TYPE_COUNT_11.type_count_11,
                E_TYPE_COUNT_12.type_count_12,
                E_TYPE_COUNT_13.type_count_13,
                ECONOMY_TYPE_COUNT_21.type_count_21
            FROM `gaz_hr_people_department` AS HPD
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS crime_count
                FROM `gaz_nifs_crime` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC ON NC.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS doctor_view_count
                FROM `gaz_nifs_doctor_view` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NDV ON NDV.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS anatomy_count
                FROM `gaz_nifs_anatomy` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NA ON NA.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_8
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 8' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_8 ON E_TYPE_COUNT_8.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_9
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 9' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_9 ON E_TYPE_COUNT_9.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_10
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 10' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_10 ON E_TYPE_COUNT_10.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_11
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 11' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_11 ON E_TYPE_COUNT_11.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_12
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 12' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_12 ON E_TYPE_COUNT_12.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_13
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 13' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_13 ON E_TYPE_COUNT_13.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_21
                FROM `gaz_nifs_economy` AS N
                WHERE N.type_id = 21' . $queryStringData . '
                GROUP BY N.department_id
            ) AS ECONOMY_TYPE_COUNT_21 ON ECONOMY_TYPE_COUNT_21.department_id = HPD.id
            WHERE HPD.is_active = 1 AND HPD.id IN(3,4,5,6)');

        if ($query->num_rows() > 0) {

            $i = 1;
            $htmlData .= '<div class="row-fluid">';
            $htmlData .= '<div class="span12">';
            $htmlData .= '<div class="head clearfix">';
            $htmlData .= '<div class="isw-grid"></div>';
            $htmlData .= '<h1>Хүрээлэн</h1>';
            $htmlData .= '</div>';

            $htmlData .= '<div class="block-fluid table-sorting clearfix">';

            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th rowspan="2" style="width:30px;">#</th>';
            $htmlData .= '<th rowspan="2" style="min-width:300px;">Гарчиг</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Криминалистик</th>';
            $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Шүүх эмнэлэг</th>';
            $htmlData .= '<th colspan="6" style="width:480px;" class="text-center">Тусгай шинжилгээ</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Эдийн засаг</th>';
            $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Нийт</th>';
            $htmlData .= '</tr>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:80px;" class="text-center">Эмчийн үзлэг</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Задлан</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Биологи</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">ДНХ</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Бактериологи</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Хими</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Физик</th>';
            $htmlData .= '<th style="width:80px;" class="text-center">Бусад</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $rowSum = $row->crime_count + $row->doctor_view_count + $row->anatomy_count + $row->type_count_8 + $row->type_count_9 + $row->type_count_10 + $row->type_count_11 + $row->type_count_12 + $row->type_count_13 + $row->type_count_21;
                $centerSumCrime = $centerSumCrime + $row->crime_count;
                $centerSumDoctorView = $centerSumDoctorView + $row->doctor_view_count;
                $centerSumAnatomy = $centerSumAnatomy + $row->anatomy_count;
                $centerSumExtraTypeCount8 = $centerSumExtraTypeCount8 + $row->type_count_8;
                $centerSumExtraTypeCount9 = $centerSumExtraTypeCount9 + $row->type_count_9;
                $centerSumExtraTypeCount10 = $centerSumExtraTypeCount10 + $row->type_count_10;
                $centerSumExtraTypeCount11 = $centerSumExtraTypeCount11 + $row->type_count_11;
                $centerSumExtraTypeCount12 = $centerSumExtraTypeCount12 + $row->type_count_12;
                $centerSumExtraTypeCount13 = $centerSumExtraTypeCount13 + $row->type_count_13;
                $centerSumEconomyTypeCount21 = $centerSumEconomyTypeCount21 + $row->type_count_21;
                $centerSumTotal = $centerSumTotal + $rowSum;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center">' . $row->crime_count . '</td>';
                $htmlData .= '<td class="text-center">' . $row->doctor_view_count . '</td>';
                $htmlData .= '<td class="text-center">' . $row->anatomy_count . '</td>';
                $htmlData .= '<td class="text-center">' . $row->type_count_8 . '</td>';
                $htmlData .= '<td class="text-center">' . $row->type_count_9 . '</td>';
                $htmlData .= '<td class="text-center">' . $row->type_count_10 . '</td>';
                $htmlData .= '<td class="text-center">' . $row->type_count_11 . '</td>';
                $htmlData .= '<td class="text-center">' . $row->type_count_12 . '</td>';
                $htmlData .= '<td class="text-center">' . $row->type_count_13 . '</td>';
                $htmlData .= '<td class="text-center">' . $row->type_count_21 . '</td>';

                $htmlData .= '<td class="text-center">' . $rowSum . '</td>';
                $htmlData .= '</tr>';

                $i++;
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td class="text-right">Нийт дүн</td>';
            $htmlData .= '<td class="text-center">' . $centerSumCrime . '</td>';
            $htmlData .= '<td class="text-center">' . $centerSumDoctorView . '</td>';
            $htmlData .= '<td class="text-center">' . $centerSumAnatomy . '</td>';
            $htmlData .= '<td class="text-center">' . $centerSumExtraTypeCount8 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerSumExtraTypeCount9 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerSumExtraTypeCount10 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerSumExtraTypeCount11 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerSumExtraTypeCount12 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerSumExtraTypeCount13 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerSumEconomyTypeCount21 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerSumTotal . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';
            $htmlData .= '</div>';
            $htmlData .= '</div>';
        }

        $htmlData .= '<div class="row-fluid">';
        $htmlData .= '<div class="span12">';
        $htmlData .= '<div class="head clearfix">';
        $htmlData .= '<div class="isw-grid"></div>';
        $htmlData .= '<h1>Улсын нэгдсэн дүн</h1>';
        $htmlData .= '</div>';

        $htmlData .= '<div class="block-fluid table-sorting clearfix">';

        $htmlData .= '<table class="table _report">';
        $htmlData .= '<thead>';

        $htmlData .= '<tr>';
        $htmlData .= '<th rowspan="2" style="width:30px;">#</th>';
        $htmlData .= '<th rowspan="2" style="min-width:300px;">Гарчиг</th>';
        $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Криминалистик</th>';
        $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Шүүх эмнэлэг</th>';
        $htmlData .= '<th colspan="6" style="width:480px;" class="text-center">Тусгай шинжилгээ</th>';
        $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Эдийн засаг</th>';
        $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Нийт</th>';
        $htmlData .= '</tr>';

        $htmlData .= '<tr>';
        $htmlData .= '<th style="width:80px;" class="text-center">Эмчийн үзлэг</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">Задлан</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">Биологи</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">ДНХ</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">Бактериологи</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">Хими</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">Физик</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">Бусад</th>';
        $htmlData .= '</tr>';

        $htmlData .= '</thead>';

        $htmlData .= '<tbody>';


        $allSumCrime = $allSumDoctorView = $allSumAnatomy = $allSumTotal = 0;

        $allSumCrime = $provinceSumCrime + $citySumCrime + $centerSumCrime;

        $allSumDoctorView = $provinceSumDoctorView + $citySumDoctorView + $centerSumDoctorView;
        $allSumAnatomy = $provinceSumAnatomy + $citySumAnatomy + $centerSumAnatomy;
        $allSumExtraTypeCount8 = $provinceSumExtraTypeCount8 + $citySumExtraTypeCount8 + $centerSumExtraTypeCount8;
        $allSumExtraTypeCount9 = $provinceSumExtraTypeCount9 + $citySumExtraTypeCount9 + $centerSumExtraTypeCount9;
        $allSumExtraTypeCount10 = $provinceSumExtraTypeCount10 + $citySumExtraTypeCount10 + $centerSumExtraTypeCount10;
        $allSumExtraTypeCount11 = $provinceSumExtraTypeCount11 + $citySumExtraTypeCount11 + $centerSumExtraTypeCount11;
        $allSumExtraTypeCount12 = $provinceSumExtraTypeCount12 + $citySumExtraTypeCount12 + $centerSumExtraTypeCount12;
        $allSumExtraTypeCount13 = $provinceSumExtraTypeCount13 + $citySumExtraTypeCount13 + $centerSumExtraTypeCount13;
        $allSumEconomyTypeCount21 = $provinceSumEconomyTypeCount21 + $citySumEconomyTypeCount21 + $centerSumEconomyTypeCount21;
        $allSumTotal = $provinceSumTotal + $citySumTotal + $centerSumTotal;

        $htmlData .= '<tbody>';

        $htmlData .= '<tr>';
        $htmlData .= '<td>1</td>';
        $htmlData .= '<td>Орон нутаг</td>';
        $htmlData .= '<td class="text-center">' . $provinceSumCrime . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceSumDoctorView . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceSumAnatomy . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceSumExtraTypeCount8 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceSumExtraTypeCount9 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceSumExtraTypeCount10 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceSumExtraTypeCount11 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceSumExtraTypeCount12 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceSumExtraTypeCount13 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceSumEconomyTypeCount21 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceSumTotal . '</td>';
        $htmlData .= '</tr>';

        $htmlData .= '<tr>';
        $htmlData .= '<td>2</td>';
        $htmlData .= '<td>Нийслэл</td>';
        $htmlData .= '<td class="text-center">' . $citySumCrime . '</td>';
        $htmlData .= '<td class="text-center">' . $citySumDoctorView . '</td>';
        $htmlData .= '<td class="text-center">' . $citySumAnatomy . '</td>';
        $htmlData .= '<td class="text-center">' . $citySumExtraTypeCount8 . '</td>';
        $htmlData .= '<td class="text-center">' . $citySumExtraTypeCount9 . '</td>';
        $htmlData .= '<td class="text-center">' . $citySumExtraTypeCount10 . '</td>';
        $htmlData .= '<td class="text-center">' . $citySumExtraTypeCount11 . '</td>';
        $htmlData .= '<td class="text-center">' . $citySumExtraTypeCount12 . '</td>';
        $htmlData .= '<td class="text-center">' . $citySumExtraTypeCount13 . '</td>';
        $htmlData .= '<td class="text-center">' . $citySumEconomyTypeCount21 . '</td>';
        $htmlData .= '<td class="text-center">' . $citySumTotal . '</td>';
        $htmlData .= '</tr>';

        $htmlData .= '<tr>';
        $htmlData .= '<td>3</td>';
        $htmlData .= '<td>Хүрээлэн</td>';
        $htmlData .= '<td class="text-center">' . $centerSumCrime . '</td>';
        $htmlData .= '<td class="text-center">' . $centerSumDoctorView . '</td>';
        $htmlData .= '<td class="text-center">' . $centerSumAnatomy . '</td>';
        $htmlData .= '<td class="text-center">' . $centerSumExtraTypeCount8 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerSumExtraTypeCount9 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerSumExtraTypeCount10 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerSumExtraTypeCount11 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerSumExtraTypeCount12 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerSumExtraTypeCount13 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerSumEconomyTypeCount21 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerSumTotal . '</td>';
        $htmlData .= '</tr>';

        $htmlData .= '</tbody>';
        $htmlData .= '<tfoot>';
        $htmlData .= '<tr>';
        $htmlData .= '<td></td>';
        $htmlData .= '<td>Нийт дүн:</td>';
        $htmlData .= '<td class="text-center">' . $allSumCrime . '</td>';
        $htmlData .= '<td class="text-center">' . $allSumDoctorView . '</td>';
        $htmlData .= '<td class="text-center">' . $allSumAnatomy . '</td>';
        $htmlData .= '<td class="text-center">' . $allSumExtraTypeCount8 . '</td>';
        $htmlData .= '<td class="text-center">' . $allSumExtraTypeCount9 . '</td>';
        $htmlData .= '<td class="text-center">' . $allSumExtraTypeCount10 . '</td>';
        $htmlData .= '<td class="text-center">' . $allSumExtraTypeCount11 . '</td>';
        $htmlData .= '<td class="text-center">' . $allSumExtraTypeCount12 . '</td>';
        $htmlData .= '<td class="text-center">' . $allSumExtraTypeCount13 . '</td>';
        $htmlData .= '<td class="text-center">' . $allSumEconomyTypeCount21 . '</td>';
        $htmlData .= '<td class="text-center">' . $allSumTotal . '</td>';
        $htmlData .= '</tr>';
        $htmlData .= '</tfoot>';
        $htmlData .= '</table>';
        $htmlData .= '</div>';
        $htmlData .= '</div>';
        $htmlData .= '</div>';


        $htmlData .= '</div>';

        return $htmlData;
    }

    public function getExtrnalReportGeneralChildData_model($param = array('parentId' => 0)) {

        $queryStringData = $htmlData = '';

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['inDate'])) . '\') <= DATE(N.in_date) AND DATE(\'' . date('Y-m-d', strtotime($param['outDate'])) . '\') >= DATE(N.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['inDate'])) . '\') <= DATE(N.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . date('Y-m-d', strtotime($param['outDate'])) . '\') >= DATE(N.out_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $param['year'] . '\'';
        }

        $queryChild = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                NC.crime_count,
                NDV.doctor_view_count,
                NA.anatomy_count,
                E_TYPE_COUNT_8.type_count_8,
                E_TYPE_COUNT_9.type_count_9,
                E_TYPE_COUNT_10.type_count_10,
                E_TYPE_COUNT_11.type_count_11,
                E_TYPE_COUNT_12.type_count_12,
                E_TYPE_COUNT_13.type_count_13,
                ECONOMY_TYPE_COUNT_21.type_count_21
            FROM `gaz_hr_people_department` AS HPD
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS crime_count
                FROM `gaz_nifs_crime` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC ON NC.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS doctor_view_count
                FROM `gaz_nifs_doctor_view` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NDV ON NDV.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS anatomy_count
                FROM `gaz_nifs_anatomy` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NA ON NA.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_8
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 8' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_8 ON E_TYPE_COUNT_8.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_9
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 9' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_9 ON E_TYPE_COUNT_9.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_10
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 10' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_10 ON E_TYPE_COUNT_10.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_11
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 11' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_11 ON E_TYPE_COUNT_11.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_12
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 12' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_12 ON E_TYPE_COUNT_12.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_13
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 13' . $queryStringData . '
                GROUP BY N.department_id
            ) AS E_TYPE_COUNT_13 ON E_TYPE_COUNT_13.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.type_id) AS type_count_21
                FROM `gaz_nifs_economy` AS N
                WHERE N.type_id = 21' . $queryStringData . '
                GROUP BY N.department_id
            ) AS ECONOMY_TYPE_COUNT_21 ON ECONOMY_TYPE_COUNT_21.department_id = HPD.id
            WHERE HPD.is_active = 1 AND HPD.parent_id = ' . $param['parentId']);

        if ($queryChild->num_rows() > 0) {

            $i = 1;

            foreach ($queryChild->result() as $key => $row) {

                $rowSum = $row->crime_count + $row->doctor_view_count + $row->anatomy_count + $row->type_count_8 + $row->type_count_9 + $row->type_count_10 + $row->type_count_11 + $row->type_count_12 + $row->type_count_13 + $row->type_count_21;

//                $htmlData .= '<tr>';
//                $htmlData .= '<td>' . $param['rowNumber'] . '.' .$i . '</td>';
//                $htmlData .= '<td style="padding-left: ' . $param['space'] . 'px;">' . $row->title . '</td>';
//                $htmlData .= '<td class="text-center">' . $row->crime_count . '</td>';
//                $htmlData .= '<td class="text-center">' . $row->doctor_view_count . '</td>';
//                $htmlData .= '<td class="text-center">' . $row->anatomy_count . '</td>';
//                $htmlData .= '<td class="text-center">' . $row->type_count_8 . '</td>';
//                $htmlData .= '<td class="text-center">' . $row->type_count_9 . '</td>';
//                $htmlData .= '<td class="text-center">' . $row->type_count_10 . '</td>';
//                $htmlData .= '<td class="text-center">' . $row->type_count_11 . '</td>';
//                $htmlData .= '<td class="text-center">' . $row->type_count_12 . '</td>';
//                $htmlData .= '<td class="text-center">' . $row->type_count_13 . '</td>';
//                $htmlData .= '<td class="text-center">' . $row->type_count_21 . '</td>';
//
//                $htmlData .= '<td class="text-center">' . $rowSum . '</td>';
//                $htmlData .= '</tr>';

                $param['crimeCount'] = $param['crimeCount'] + $row->crime_count;
                $param['doctorViewCount'] = $param['doctorViewCount'] + $row->doctor_view_count;
                $param['anatomyCount'] = $param['anatomyCount'] + $row->anatomy_count;
                $param['typeCount8'] = $param['typeCount8'] + $row->type_count_8;
                $param['typeCount9'] = $param['typeCount9'] + $row->type_count_9;
                $param['typeCount10'] = $param['typeCount10'] + $row->type_count_10;
                $param['typeCount11'] = $param['typeCount11'] + $row->type_count_11;
                $param['typeCount12'] = $param['typeCount12'] + $row->type_count_12;
                $param['typeCount13'] = $param['typeCount13'] + $row->type_count_13;
                $param['typeCount21'] = $param['typeCount21'] + $row->type_count_21;
                $param['rowSum'] = $param['rowSum'] + $rowSum;

                $i++;
            }
        }

        return array('html' => $htmlData, 'data' => $param);
    }

    public function getReportCrimeGeneralData_model($param = array()) {

        $queryStringData = $htmlData = '';

        if ($param['reportIsClose'] == 1) {

            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.close_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.close_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.close_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.close_date)';
            }
        } else {

            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.in_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.in_date)';
            }
        }


        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $provinceCrimeCatCount280 = $provinceCrimeCatCount281 = $provinceCrimeCatCount282 = $provinceCrimeCatCount283 = $provinceCrimeCatCount284 = $provinceCrimeCatCount285 = $provinceCrimeCatCount286 = $provinceCrimeCatCount342 = $provinceCrimeCatCount343 = $provinceCrimeCatCount344 = $provinceCrimeCatCount345 = $provinceCrimeCatCount346 = $provinceCrimeCatCount347 = $provinceCrimeCatCount348 = $provinceCrimeCatCount437 = $provinceSumTotal = 0;
        $cityCrimeCatCount280 = $cityCrimeCatCount281 = $cityCrimeCatCount282 = $cityCrimeCatCount283 = $cityCrimeCatCount284 = $cityCrimeCatCount285 = $cityCrimeCatCount286 = $cityCrimeCatCount342 = $cityCrimeCatCount343 = $cityCrimeCatCount344 = $cityCrimeCatCount345 = $cityCrimeCatCount346 = $cityCrimeCatCount347 = $cityCrimeCatCount348 = $cityCrimeCatCount437 = $citySumTotal = 0;
        $centerCrimeCatCount280 = $centerCrimeCatCount281 = $centerCrimeCatCount282 = $centerCrimeCatCount283 = $centerCrimeCatCount284 = $centerCrimeCatCount285 = $centerCrimeCatCount286 = $centerCrimeCatCount342 = $centerCrimeCatCount343 = $centerCrimeCatCount344 = $centerCrimeCatCount345 = $centerCrimeCatCount346 = $centerCrimeCatCount347 = $centerCrimeCatCount348 = $centerCrimeCatCount437 = $centerSumTotal = 0;

        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                NC_CAT_COUNT_280.crime_cat_count_280,
                IF(NC_CAT_COUNT_280.crime_cat_count_280 > 0, \'_row-more\', \'\') AS crime_cat_count_280_class,
                NC_CAT_COUNT_281.crime_cat_count_281,
                IF(NC_CAT_COUNT_281.crime_cat_count_281 > 0, \'_row-more\', \'\') AS crime_cat_count_281_class,
                NC_CAT_COUNT_282.crime_cat_count_282,
                IF(NC_CAT_COUNT_282.crime_cat_count_282 > 0, \'_row-more\', \'\') AS crime_cat_count_282_class,
                NC_CAT_COUNT_283.crime_cat_count_283,
                IF(NC_CAT_COUNT_283.crime_cat_count_283 > 0, \'_row-more\', \'\') AS crime_cat_count_283_class,
                NC_CAT_COUNT_284.crime_cat_count_284,
                IF(NC_CAT_COUNT_284.crime_cat_count_284 > 0, \'_row-more\', \'\') AS crime_cat_count_284_class,
                NC_CAT_COUNT_285.crime_cat_count_285,
                IF(NC_CAT_COUNT_285.crime_cat_count_285 > 0, \'_row-more\', \'\') AS crime_cat_count_285_class,
                NC_CAT_COUNT_286.crime_cat_count_286,
                IF(NC_CAT_COUNT_286.crime_cat_count_286 > 0, \'_row-more\', \'\') AS crime_cat_count_286_class,
                NC_CAT_COUNT_342.crime_cat_count_342,
                IF(NC_CAT_COUNT_342.crime_cat_count_342 > 0, \'_row-more\', \'\') AS crime_cat_count_342_class,
                NC_CAT_COUNT_343.crime_cat_count_343,
                IF(NC_CAT_COUNT_343.crime_cat_count_343 > 0, \'_row-more\', \'\') AS crime_cat_count_343_class,
                NC_CAT_COUNT_344.crime_cat_count_344,
                IF(NC_CAT_COUNT_344.crime_cat_count_344 > 0, \'_row-more\', \'\') AS crime_cat_count_344_class,
                NC_CAT_COUNT_345.crime_cat_count_345,
                IF(NC_CAT_COUNT_345.crime_cat_count_345 > 0, \'_row-more\', \'\') AS crime_cat_count_345_class,
                NC_CAT_COUNT_346.crime_cat_count_346,
                IF(NC_CAT_COUNT_346.crime_cat_count_346 > 0, \'_row-more\', \'\') AS crime_cat_count_346_class,
                NC_CAT_COUNT_347.crime_cat_count_347,
                IF(NC_CAT_COUNT_347.crime_cat_count_347 > 0, \'_row-more\', \'\') AS crime_cat_count_347_class,
                NC_CAT_COUNT_348.crime_cat_count_348,
                IF(NC_CAT_COUNT_348.crime_cat_count_348 > 0, \'_row-more\', \'\') AS crime_cat_count_348_class,
                NC_CAT_COUNT_437.crime_cat_count_437,
                IF(NC_CAT_COUNT_437.crime_cat_count_437 > 0, \'_row-more\', \'\') AS crime_cat_count_437_class
            FROM `gaz_hr_people_department` AS HPD
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_280
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 280 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_280 ON NC_CAT_COUNT_280.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_281
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 281 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_281 ON NC_CAT_COUNT_281.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_282
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 282 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_282 ON NC_CAT_COUNT_282.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_283
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 283 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_283 ON NC_CAT_COUNT_283.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_284
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 284 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_284 ON NC_CAT_COUNT_284.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_285
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 285 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_285 ON NC_CAT_COUNT_285.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_286
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 286 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_286 ON NC_CAT_COUNT_286.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_342
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 342 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_342 ON NC_CAT_COUNT_342.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_343
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 343 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_343 ON NC_CAT_COUNT_343.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_344
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 344 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_344 ON NC_CAT_COUNT_344.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_345
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 345 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_345 ON NC_CAT_COUNT_345.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_346
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 346 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_346 ON NC_CAT_COUNT_346.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_347
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 347 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_347 ON NC_CAT_COUNT_347.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_348
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 348 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_348 ON NC_CAT_COUNT_348.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_437
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 437 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_437 ON NC_CAT_COUNT_437.department_id = HPD.id
            WHERE HPD.is_active = 1 AND HPD.parent_id = 18');


        if ($query->num_rows() > 0) {

            $i = 1;
            $htmlData .= '<h6>Орон нутаг</h6>';
            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Гарчиг</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Дүрс бичлэг</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Дүр зураг</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Мөр судлал</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Гарын мөр</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Бичиг техник</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Галт зэвсэг</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Бичиг судлал</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Авто техникийн шинжилгээ</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Тэсрэх төхөөрөмж</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Компьютер техник</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Дуу авиа</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Полиграф</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Инженер техник</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Материал судлал</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Аман зураг</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Нийт</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $provinceCrimeCatCount280 = $provinceCrimeCatCount280 = $row->crime_cat_count_280;
                $provinceCrimeCatCount281 = $provinceCrimeCatCount281 + $row->crime_cat_count_281;
                $provinceCrimeCatCount282 = $provinceCrimeCatCount282 + $row->crime_cat_count_282;
                $provinceCrimeCatCount283 = $provinceCrimeCatCount283 + $row->crime_cat_count_283;
                $provinceCrimeCatCount284 = $provinceCrimeCatCount284 + $row->crime_cat_count_284;
                $provinceCrimeCatCount285 = $provinceCrimeCatCount285 + $row->crime_cat_count_285;
                $provinceCrimeCatCount286 = $provinceCrimeCatCount286 + $row->crime_cat_count_286;
                $provinceCrimeCatCount342 = $provinceCrimeCatCount342 + $row->crime_cat_count_342;
                $provinceCrimeCatCount343 = $provinceCrimeCatCount343 + $row->crime_cat_count_343;
                $provinceCrimeCatCount344 = $provinceCrimeCatCount344 + $row->crime_cat_count_344;
                $provinceCrimeCatCount345 = $provinceCrimeCatCount345 + $row->crime_cat_count_345;
                $provinceCrimeCatCount346 = $provinceCrimeCatCount346 + $row->crime_cat_count_346;
                $provinceCrimeCatCount347 = $provinceCrimeCatCount347 + $row->crime_cat_count_347;
                $provinceCrimeCatCount348 = $provinceCrimeCatCount348 + $row->crime_cat_count_348;
                $provinceCrimeCatCount437 = $provinceCrimeCatCount437 + $row->crime_cat_count_437;
                $rowSum = $row->crime_cat_count_280 + $row->crime_cat_count_281 + $row->crime_cat_count_282 + $row->crime_cat_count_283 + $row->crime_cat_count_284 + $row->crime_cat_count_285 + $row->crime_cat_count_286 + $row->crime_cat_count_342 + $row->crime_cat_count_343 + $row->crime_cat_count_344 + $row->crime_cat_count_345 + $row->crime_cat_count_346 + $row->crime_cat_count_347 + $row->crime_cat_count_348 + $row->crime_cat_count_437;
                $provinceSumTotal = $provinceSumTotal + $rowSum;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_280_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=280&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_280 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_281_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=281&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_281 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_282_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=282&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_282 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_283_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=283&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_283 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_284_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=284&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_284 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_285_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=285&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_285 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_286_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=286&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_286 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_342_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=342&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_342 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_343_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=343&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_343 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_344_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=344&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_344 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_345_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=345&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_345 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_346_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=346&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_346 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_347_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=347&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_347 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_348_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=348&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_348 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_437_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=437&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_437 . '</a></td>';

                if ($rowSum > 0) {
                    $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $rowSum . '</a></td>';
                } else {
                    $htmlData .= '<td class="text-center"></td>';
                }
                $htmlData .= '</tr>';

                $i++;
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td>Нийт дүн</td>';
            if ($provinceCrimeCatCount280 > 0) {
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=280&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $provinceCrimeCatCount280 . '</a></td>';
            } else {
                $htmlData .= '<td class="text-center"></td>';
            }

            if ($provinceCrimeCatCount281 > 0) {
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=281&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $provinceCrimeCatCount281 . '</a></td>';
            } else {
                $htmlData .= '<td class="text-center"></td>';
            }

            if ($provinceCrimeCatCount282 > 0) {
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=282&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $provinceCrimeCatCount282 . '</a></td>';
            } else {
                $htmlData .= '<td class="text-center"></td>';
            }

            if ($provinceCrimeCatCount283 > 0) {
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=283&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $provinceCrimeCatCount283 . '</a></td>';
            } else {
                $htmlData .= '<td class="text-center"></td>';
            }

            if ($provinceCrimeCatCount284 > 0) {
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=284&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $provinceCrimeCatCount284 . '</a></td>';
            } else {
                $htmlData .= '<td class="text-center"></td>';
            }

            if ($provinceCrimeCatCount285 > 0) {
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=285&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $provinceCrimeCatCount285 . '</a></td>';
            } else {
                $htmlData .= '<td class="text-center"></td>';
            }

            if ($provinceCrimeCatCount286 > 0) {
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=286&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $provinceCrimeCatCount286 . '</a></td>';
            } else {
                $htmlData .= '<td class="text-center"></td>';
            }

            if ($provinceCrimeCatCount342 > 0) {
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=342&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $provinceCrimeCatCount342 . '</a></td>';
            } else {
                $htmlData .= '<td class="text-center"></td>';
            }

            if ($provinceCrimeCatCount343 > 0) {
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=343&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $provinceCrimeCatCount343 . '</a></td>';
            } else {
                $htmlData .= '<td class="text-center"></td>';
            }

            if ($provinceCrimeCatCount344 > 0) {
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=344&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $provinceCrimeCatCount344 . '</a></td>';
            } else {
                $htmlData .= '<td class="text-center"></td>';
            }

            if ($provinceCrimeCatCount345 > 0) {
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=345&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $provinceCrimeCatCount345 . '</a></td>';
            } else {
                $htmlData .= '<td class="text-center"></td>';
            }

            if ($provinceCrimeCatCount346 > 0) {
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=346&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $provinceCrimeCatCount346 . '</a></td>';
            } else {
                $htmlData .= '<td class="text-center"></td>';
            }

            if ($provinceCrimeCatCount347 > 0) {
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=347&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $provinceCrimeCatCount347 . '</a></td>';
            } else {
                $htmlData .= '<td class="text-center"></td>';
            }

            if ($provinceCrimeCatCount348 > 0) {
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=348&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $provinceCrimeCatCount348 . '</a></td>';
            } else {
                $htmlData .= '<td class="text-center"></td>';
            }

            if ($provinceCrimeCatCount437 > 0) {
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=437&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $provinceCrimeCatCount437 . '</a></td>';
            } else {
                $htmlData .= '<td class="text-center"></td>';
            }

            $htmlData .= '<td class="text-center">' . ($provinceSumTotal > 0 ? $provinceSumTotal : '') . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';
        }

        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                NC_CAT_COUNT_280.crime_cat_count_280,
                IF(NC_CAT_COUNT_280.crime_cat_count_280 > 0, \'_row-more\', \'\') AS crime_cat_count_280_class,
                NC_CAT_COUNT_281.crime_cat_count_281,
                IF(NC_CAT_COUNT_281.crime_cat_count_281 > 0, \'_row-more\', \'\') AS crime_cat_count_281_class,
                NC_CAT_COUNT_282.crime_cat_count_282,
                IF(NC_CAT_COUNT_282.crime_cat_count_282 > 0, \'_row-more\', \'\') AS crime_cat_count_282_class,
                NC_CAT_COUNT_283.crime_cat_count_283,
                IF(NC_CAT_COUNT_283.crime_cat_count_283 > 0, \'_row-more\', \'\') AS crime_cat_count_283_class,
                NC_CAT_COUNT_284.crime_cat_count_284,
                IF(NC_CAT_COUNT_284.crime_cat_count_284 > 0, \'_row-more\', \'\') AS crime_cat_count_284_class,
                NC_CAT_COUNT_285.crime_cat_count_285,
                IF(NC_CAT_COUNT_285.crime_cat_count_285 > 0, \'_row-more\', \'\') AS crime_cat_count_285_class,
                NC_CAT_COUNT_286.crime_cat_count_286,
                IF(NC_CAT_COUNT_286.crime_cat_count_286 > 0, \'_row-more\', \'\') AS crime_cat_count_286_class,
                NC_CAT_COUNT_342.crime_cat_count_342,
                IF(NC_CAT_COUNT_342.crime_cat_count_342 > 0, \'_row-more\', \'\') AS crime_cat_count_342_class,
                NC_CAT_COUNT_343.crime_cat_count_343,
                IF(NC_CAT_COUNT_343.crime_cat_count_343 > 0, \'_row-more\', \'\') AS crime_cat_count_343_class,
                NC_CAT_COUNT_344.crime_cat_count_344,
                IF(NC_CAT_COUNT_344.crime_cat_count_344 > 0, \'_row-more\', \'\') AS crime_cat_count_344_class,
                NC_CAT_COUNT_345.crime_cat_count_345,
                IF(NC_CAT_COUNT_345.crime_cat_count_345 > 0, \'_row-more\', \'\') AS crime_cat_count_345_class,
                NC_CAT_COUNT_346.crime_cat_count_346,
                IF(NC_CAT_COUNT_346.crime_cat_count_346 > 0, \'_row-more\', \'\') AS crime_cat_count_346_class,
                NC_CAT_COUNT_347.crime_cat_count_347,
                IF(NC_CAT_COUNT_347.crime_cat_count_347 > 0, \'_row-more\', \'\') AS crime_cat_count_347_class,
                NC_CAT_COUNT_348.crime_cat_count_348,
                IF(NC_CAT_COUNT_348.crime_cat_count_348 > 0, \'_row-more\', \'\') AS crime_cat_count_348_class,
                NC_CAT_COUNT_437.crime_cat_count_437,
                IF(NC_CAT_COUNT_437.crime_cat_count_437 > 0, \'_row-more\', \'\') AS crime_cat_count_437_class
            FROM `gaz_hr_people_department` AS HPD
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_280
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 280 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_280 ON NC_CAT_COUNT_280.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_281
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 281 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_281 ON NC_CAT_COUNT_281.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_282
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 282 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_282 ON NC_CAT_COUNT_282.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_283
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 283 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_283 ON NC_CAT_COUNT_283.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_284
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 284 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_284 ON NC_CAT_COUNT_284.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_285
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 285 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_285 ON NC_CAT_COUNT_285.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_286
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 286 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_286 ON NC_CAT_COUNT_286.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_342
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 342 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_342 ON NC_CAT_COUNT_342.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_343
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 343 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_343 ON NC_CAT_COUNT_343.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_344
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 344 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_344 ON NC_CAT_COUNT_344.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_345
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 345 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_345 ON NC_CAT_COUNT_345.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_346
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 346 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_346 ON NC_CAT_COUNT_346.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_347
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 347 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_347 ON NC_CAT_COUNT_347.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_348
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 348 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_348 ON NC_CAT_COUNT_348.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_437
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 437 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_437 ON NC_CAT_COUNT_437.department_id = HPD.id
            WHERE HPD.is_active = 1 AND HPD.parent_id = 8');


        if ($query->num_rows() > 0) {

            $i = 1;
            $htmlData .= '<h6>Нийслэл</h6>';
            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Гарчиг</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Дүрс бичлэг</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Дүр зураг</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Мөр судлал</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Гарын мөр</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Бичиг техник</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Галт зэвсэг</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Бичиг судлал</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Авто техникийн шинжилгээ</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Тэсрэх төхөөрөмж</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Компьютер техник</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Дуу авиа</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Полиграф</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Инженер техник</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Материал судлал</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Аман зураг</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Нийт</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $cityCrimeCatCount280 = $cityCrimeCatCount280 = $row->crime_cat_count_280;
                $cityCrimeCatCount281 = $cityCrimeCatCount281 + $row->crime_cat_count_281;
                $cityCrimeCatCount282 = $cityCrimeCatCount282 + $row->crime_cat_count_282;
                $cityCrimeCatCount283 = $cityCrimeCatCount283 + $row->crime_cat_count_283;
                $cityCrimeCatCount284 = $cityCrimeCatCount284 + $row->crime_cat_count_284;
                $cityCrimeCatCount285 = $cityCrimeCatCount285 + $row->crime_cat_count_285;
                $cityCrimeCatCount286 = $cityCrimeCatCount286 + $row->crime_cat_count_286;
                $cityCrimeCatCount342 = $cityCrimeCatCount342 + $row->crime_cat_count_342;
                $cityCrimeCatCount343 = $cityCrimeCatCount343 + $row->crime_cat_count_343;
                $cityCrimeCatCount344 = $cityCrimeCatCount344 + $row->crime_cat_count_344;
                $cityCrimeCatCount345 = $cityCrimeCatCount345 + $row->crime_cat_count_345;
                $cityCrimeCatCount346 = $cityCrimeCatCount346 + $row->crime_cat_count_346;
                $cityCrimeCatCount347 = $cityCrimeCatCount347 + $row->crime_cat_count_347;
                $cityCrimeCatCount348 = $cityCrimeCatCount348 + $row->crime_cat_count_348;
                $cityCrimeCatCount437 = $cityCrimeCatCount437 + $row->crime_cat_count_437;
                $rowSum = $row->crime_cat_count_280 + $row->crime_cat_count_281 + $row->crime_cat_count_282 + $row->crime_cat_count_283 + $row->crime_cat_count_284 + $row->crime_cat_count_285 + $row->crime_cat_count_286 + $row->crime_cat_count_342 + $row->crime_cat_count_343 + $row->crime_cat_count_344 + $row->crime_cat_count_345 + $row->crime_cat_count_346 + $row->crime_cat_count_347 + $row->crime_cat_count_348 + $row->crime_cat_count_437;
                $citySumTotal = $citySumTotal + $rowSum;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_280_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=280&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_280 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_281_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=281&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_281 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_282_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=282&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_282 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_283_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=283&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_283 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_284_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=284&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_284 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_285_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=285&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_285 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_286_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=286&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_286 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_342_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=342&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_342 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_343_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=343&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_343 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_344_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=344&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_344 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_345_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=345&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_345 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_346_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=346&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_346 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_347_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=347&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_347 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_348_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=348&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_348 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->crime_cat_count_437_class . '"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=437&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_437 . '</a></td>';
                $htmlData .= '<td class="text-center">' . $rowSum . '</td>';
                $htmlData .= '</tr>';

                $i++;
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td>Нийт дүн</td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=280&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($cityCrimeCatCount280 > 0 ? $cityCrimeCatCount280 : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=281&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($cityCrimeCatCount281 > 0 ? $cityCrimeCatCount281 : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=282&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($cityCrimeCatCount282 > 0 ? $cityCrimeCatCount282 : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=283&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($cityCrimeCatCount283 > 0 ? $cityCrimeCatCount283 : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=284&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($cityCrimeCatCount284 > 0 ? $cityCrimeCatCount284 : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=285&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($cityCrimeCatCount285 > 0 ? $cityCrimeCatCount285 : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=286&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($cityCrimeCatCount286 > 0 ? $cityCrimeCatCount286 : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=342&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($cityCrimeCatCount342 > 0 ? $cityCrimeCatCount342 : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=343&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($cityCrimeCatCount343 > 0 ? $cityCrimeCatCount343 : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=343&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($cityCrimeCatCount344 > 0 ? $cityCrimeCatCount344 : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=343&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($cityCrimeCatCount345 > 0 ? $cityCrimeCatCount345 : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=346&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($cityCrimeCatCount346 > 0 ? $cityCrimeCatCount346 : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=347&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($cityCrimeCatCount347 > 0 ? $cityCrimeCatCount347 : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=348&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($cityCrimeCatCount348 > 0 ? $cityCrimeCatCount348 : '') . '</a></td>';
            $htmlData .= '<td class="text-center"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&catId=437&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($cityCrimeCatCount437 > 0 ? $cityCrimeCatCount437 : '') . '</a></td>';
            $htmlData .= '<td class="text-center">' . ($citySumTotal > 0 ? $citySumTotal : '') . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';
        }

        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                NC_CAT_COUNT_280.crime_cat_count_280,
                NC_CAT_COUNT_281.crime_cat_count_281,
                NC_CAT_COUNT_282.crime_cat_count_282,
                NC_CAT_COUNT_283.crime_cat_count_283,
                NC_CAT_COUNT_284.crime_cat_count_284,
                NC_CAT_COUNT_285.crime_cat_count_285,
                NC_CAT_COUNT_286.crime_cat_count_286,
                NC_CAT_COUNT_342.crime_cat_count_342,
                NC_CAT_COUNT_343.crime_cat_count_343,
                NC_CAT_COUNT_344.crime_cat_count_344,
                NC_CAT_COUNT_345.crime_cat_count_345,
                NC_CAT_COUNT_346.crime_cat_count_346,
                NC_CAT_COUNT_347.crime_cat_count_347,
                NC_CAT_COUNT_348.crime_cat_count_348,
                NC_CAT_COUNT_437.crime_cat_count_437
            FROM `gaz_hr_people_department` AS HPD
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_280
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 280 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_280 ON NC_CAT_COUNT_280.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_281
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 281 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_281 ON NC_CAT_COUNT_281.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_282
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 282 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_282 ON NC_CAT_COUNT_282.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_283
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 283 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_283 ON NC_CAT_COUNT_283.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_284
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 284 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_284 ON NC_CAT_COUNT_284.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_285
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 285 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_285 ON NC_CAT_COUNT_285.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_286
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 286 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_286 ON NC_CAT_COUNT_286.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_342
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 342 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_342 ON NC_CAT_COUNT_342.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_343
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 343 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_343 ON NC_CAT_COUNT_343.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_344
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 344 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_344 ON NC_CAT_COUNT_344.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_345
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 345 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_345 ON NC_CAT_COUNT_345.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_346
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 346 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_346 ON NC_CAT_COUNT_346.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_347
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 347 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_347 ON NC_CAT_COUNT_347.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_348
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 348 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_348 ON NC_CAT_COUNT_348.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.cat_id) AS crime_cat_count_437
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 437 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC_CAT_COUNT_437 ON NC_CAT_COUNT_437.department_id = HPD.id
            WHERE HPD.is_active = 1 AND HPD.id IN(3,4,5,6)');


        if ($query->num_rows() > 0) {

            $i = 1;
            $htmlData .= '<h6>Хүрээлэн</h6>';
            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Гарчиг</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Дүрс бичлэг</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Дүр зураг</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Мөр судлал</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Гарын мөр</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Бичиг техник</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Галт зэвсэг</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Бичиг судлал</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Авто техникийн шинжилгээ</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Тэсрэх төхөөрөмж</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Компьютер техник</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Дуу авиа</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Полиграф</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Инженер техник</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Материал судлал</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Аман зураг</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Нийт</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $centerCrimeCatCount280 = $centerCrimeCatCount280 = $row->crime_cat_count_280;
                $centerCrimeCatCount281 = $centerCrimeCatCount281 + $row->crime_cat_count_281;
                $centerCrimeCatCount282 = $centerCrimeCatCount282 + $row->crime_cat_count_282;
                $centerCrimeCatCount283 = $centerCrimeCatCount283 + $row->crime_cat_count_283;
                $centerCrimeCatCount284 = $centerCrimeCatCount284 + $row->crime_cat_count_284;
                $centerCrimeCatCount285 = $centerCrimeCatCount285 + $row->crime_cat_count_285;
                $centerCrimeCatCount286 = $centerCrimeCatCount286 + $row->crime_cat_count_286;
                $centerCrimeCatCount342 = $centerCrimeCatCount342 + $row->crime_cat_count_342;
                $centerCrimeCatCount343 = $centerCrimeCatCount343 + $row->crime_cat_count_343;
                $centerCrimeCatCount344 = $centerCrimeCatCount344 + $row->crime_cat_count_344;
                $centerCrimeCatCount345 = $centerCrimeCatCount345 + $row->crime_cat_count_345;
                $centerCrimeCatCount346 = $centerCrimeCatCount346 + $row->crime_cat_count_346;
                $centerCrimeCatCount347 = $centerCrimeCatCount347 + $row->crime_cat_count_347;
                $centerCrimeCatCount348 = $centerCrimeCatCount348 + $row->crime_cat_count_348;
                $centerCrimeCatCount437 = $centerCrimeCatCount437 + $row->crime_cat_count_437;
                $rowSum = $row->crime_cat_count_280 + $row->crime_cat_count_281 + $row->crime_cat_count_282 + $row->crime_cat_count_283 + $row->crime_cat_count_284 + $row->crime_cat_count_285 + $row->crime_cat_count_286 + $row->crime_cat_count_342 + $row->crime_cat_count_343 + $row->crime_cat_count_344 + $row->crime_cat_count_345 + $row->crime_cat_count_346 + $row->crime_cat_count_347 + $row->crime_cat_count_348 + $row->crime_cat_count_437;
                $centerSumTotal = $centerSumTotal + $rowSum;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=280&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_280 . '</a></td>';
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=281&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_281 . '</a></td>';
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=282&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_282 . '</a></td>';
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=283&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_283 . '</a></td>';
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=284&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_284 . '</a></td>';
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=285&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_285 . '</a></td>';
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=286&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_286 . '</a></td>';
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=342&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_342 . '</a></td>';
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=343&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_343 . '</a></td>';
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=344&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_344 . '</a></td>';
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=345&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_345 . '</a></td>';
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=346&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_346 . '</a></td>';
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=347&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_347 . '</a></td>';
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=348&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_348 . '</a></td>';
                $htmlData .= '<td class="text-center _row-more"><a href="javascript:;" onclick="_initNifsCrime({page: 0, searchQuery: \'isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . '&departmentId=' . $row->id . '&catId=437&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . $row->crime_cat_count_437 . '</a></td>';
                $htmlData .= '<td class="text-center">' . $rowSum . '</td>';
                $htmlData .= '</tr>';

                $i++;
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td>Нийт дүн</td>';
            $htmlData .= '<td class="text-center">' . $centerCrimeCatCount280 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerCrimeCatCount281 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerCrimeCatCount282 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerCrimeCatCount283 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerCrimeCatCount284 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerCrimeCatCount285 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerCrimeCatCount286 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerCrimeCatCount342 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerCrimeCatCount343 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerCrimeCatCount344 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerCrimeCatCount345 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerCrimeCatCount346 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerCrimeCatCount347 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerCrimeCatCount348 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerCrimeCatCount437 . '</td>';
            $htmlData .= '<td class="text-center">' . $centerSumTotal . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';
        }

        $htmlData .= '<h6>Улсын нэгдсэн дүн</h6>';
        $htmlData .= '<div class="table-responsive">';
        $htmlData .= '<table class="table _report">';
        $htmlData .= '<thead>';
        $htmlData .= '<tr>';
        $htmlData .= '<th style="width:30px;">#</th>';
        $htmlData .= '<th>Гарчиг</th>';
        $htmlData .= '<th style="width:60px;" class="text-center">Дүрс бичлэг</th>';
        $htmlData .= '<th style="width:60px;" class="text-center">Дүр зураг</th>';
        $htmlData .= '<th style="width:60px;" class="text-center">Мөр судлал</th>';
        $htmlData .= '<th style="width:60px;" class="text-center">Гарын мөр</th>';
        $htmlData .= '<th style="width:60px;" class="text-center">Бичиг техник</th>';
        $htmlData .= '<th style="width:60px;" class="text-center">Галт зэвсэг</th>';
        $htmlData .= '<th style="width:60px;" class="text-center">Бичиг судлал</th>';
        $htmlData .= '<th style="width:60px;" class="text-center">Авто техникийн шинжилгээ</th>';
        $htmlData .= '<th style="width:60px;" class="text-center">Тэсрэх төхөөрөмж</th>';
        $htmlData .= '<th style="width:60px;" class="text-center">Компьютер техник</th>';
        $htmlData .= '<th style="width:60px;" class="text-center">Дуу авиа</th>';
        $htmlData .= '<th style="width:60px;" class="text-center">Полиграф</th>';
        $htmlData .= '<th style="width:60px;" class="text-center">Инженер техник</th>';
        $htmlData .= '<th style="width:60px;" class="text-center">Материал судлал</th>';
        $htmlData .= '<th style="width:60px;" class="text-center">Аман зураг</th>';
        $htmlData .= '<th style="width:60px;" class="text-center">Нийт</th>';
        $htmlData .= '</tr>';
        $htmlData .= '</thead>';
        $htmlData .= '<tbody>';
        $htmlData .= '<tr>';
        $htmlData .= '<td>1</td>';
        $htmlData .= '<td>Орон нутаг</td>';
        $htmlData .= '<td class="text-center">' . $provinceCrimeCatCount280 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceCrimeCatCount281 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceCrimeCatCount282 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceCrimeCatCount283 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceCrimeCatCount284 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceCrimeCatCount285 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceCrimeCatCount286 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceCrimeCatCount342 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceCrimeCatCount343 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerCrimeCatCount344 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceCrimeCatCount345 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceCrimeCatCount346 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceCrimeCatCount347 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceCrimeCatCount348 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceCrimeCatCount437 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceSumTotal . '</td>';
        $htmlData .= '</tr>';
        $htmlData .= '<tr>';
        $htmlData .= '<td>2</td>';
        $htmlData .= '<td>Нийслэл</td>';
        $htmlData .= '<td class="text-center">' . $cityCrimeCatCount280 . '</td>';
        $htmlData .= '<td class="text-center">' . $cityCrimeCatCount281 . '</td>';
        $htmlData .= '<td class="text-center">' . $cityCrimeCatCount282 . '</td>';
        $htmlData .= '<td class="text-center">' . $cityCrimeCatCount283 . '</td>';
        $htmlData .= '<td class="text-center">' . $cityCrimeCatCount284 . '</td>';
        $htmlData .= '<td class="text-center">' . $cityCrimeCatCount285 . '</td>';
        $htmlData .= '<td class="text-center">' . $cityCrimeCatCount286 . '</td>';
        $htmlData .= '<td class="text-center">' . $cityCrimeCatCount342 . '</td>';
        $htmlData .= '<td class="text-center">' . $cityCrimeCatCount343 . '</td>';
        $htmlData .= '<td class="text-center">' . $cityCrimeCatCount344 . '</td>';
        $htmlData .= '<td class="text-center">' . $cityCrimeCatCount345 . '</td>';
        $htmlData .= '<td class="text-center">' . $cityCrimeCatCount346 . '</td>';
        $htmlData .= '<td class="text-center">' . $cityCrimeCatCount347 . '</td>';
        $htmlData .= '<td class="text-center">' . $cityCrimeCatCount348 . '</td>';
        $htmlData .= '<td class="text-center">' . $cityCrimeCatCount437 . '</td>';
        $htmlData .= '<td class="text-center">' . $citySumTotal . '</td>';
        $htmlData .= '</tr>';
        $htmlData .= '<tr>';
        $htmlData .= '<td>3</td>';
        $htmlData .= '<td>Хүрээлэн</td>';
        $htmlData .= '<td class="text-center">' . $centerCrimeCatCount280 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerCrimeCatCount281 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerCrimeCatCount282 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerCrimeCatCount283 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerCrimeCatCount284 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerCrimeCatCount285 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerCrimeCatCount286 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerCrimeCatCount342 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerCrimeCatCount343 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerCrimeCatCount344 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerCrimeCatCount345 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerCrimeCatCount346 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerCrimeCatCount347 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerCrimeCatCount348 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerCrimeCatCount437 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerSumTotal . '</td>';
        $htmlData .= '</tr>';
        $htmlData .= '</tbody>';

        $allCrimeCatCount280 = $provinceCrimeCatCount280 + $cityCrimeCatCount280 + $centerCrimeCatCount280;
        $allCrimeCatCount281 = $provinceCrimeCatCount281 + $cityCrimeCatCount281 + $centerCrimeCatCount281;
        $allCrimeCatCount282 = $provinceCrimeCatCount282 + $cityCrimeCatCount282 + $centerCrimeCatCount282;
        $allCrimeCatCount283 = $provinceCrimeCatCount283 + $cityCrimeCatCount283 + $centerCrimeCatCount283;
        $allCrimeCatCount284 = $provinceCrimeCatCount284 + $cityCrimeCatCount284 + $centerCrimeCatCount284;
        $allCrimeCatCount285 = $provinceCrimeCatCount285 + $cityCrimeCatCount285 + $centerCrimeCatCount285;
        $allCrimeCatCount286 = $provinceCrimeCatCount286 + $cityCrimeCatCount286 + $centerCrimeCatCount286;
        $allCrimeCatCount342 = $provinceCrimeCatCount342 + $cityCrimeCatCount342 + $centerCrimeCatCount342;
        $allCrimeCatCount343 = $provinceCrimeCatCount343 + $cityCrimeCatCount343 + $centerCrimeCatCount343;
        $allCrimeCatCount344 = $provinceCrimeCatCount344 + $cityCrimeCatCount344 + $centerCrimeCatCount344;
        $allCrimeCatCount345 = $provinceCrimeCatCount345 + $cityCrimeCatCount345 + $centerCrimeCatCount345;
        $allCrimeCatCount346 = $provinceCrimeCatCount346 + $cityCrimeCatCount346 + $centerCrimeCatCount346;
        $allCrimeCatCount347 = $provinceCrimeCatCount347 + $cityCrimeCatCount347 + $centerCrimeCatCount347;
        $allCrimeCatCount348 = $provinceCrimeCatCount348 + $cityCrimeCatCount348 + $centerCrimeCatCount348;
        $allCrimeCatCount437 = $provinceCrimeCatCount437 + $cityCrimeCatCount437 + $centerCrimeCatCount437;
        $allCrimeSumTotal = $provinceSumTotal + $citySumTotal + $centerSumTotal;

        $htmlData .= '<tfoot>';
        $htmlData .= '<tr>';
        $htmlData .= '<td></td>';
        $htmlData .= '<td>Нийт дүн</td>';
        $htmlData .= '<td class="text-center">' . $allCrimeCatCount280 . '</td>';
        $htmlData .= '<td class="text-center">' . $allCrimeCatCount281 . '</td>';
        $htmlData .= '<td class="text-center">' . $allCrimeCatCount282 . '</td>';
        $htmlData .= '<td class="text-center">' . $allCrimeCatCount283 . '</td>';
        $htmlData .= '<td class="text-center">' . $allCrimeCatCount284 . '</td>';
        $htmlData .= '<td class="text-center">' . $allCrimeCatCount285 . '</td>';
        $htmlData .= '<td class="text-center">' . $allCrimeCatCount286 . '</td>';
        $htmlData .= '<td class="text-center">' . $allCrimeCatCount342 . '</td>';
        $htmlData .= '<td class="text-center">' . $allCrimeCatCount343 . '</td>';
        $htmlData .= '<td class="text-center">' . $allCrimeCatCount344 . '</td>';
        $htmlData .= '<td class="text-center">' . $allCrimeCatCount345 . '</td>';
        $htmlData .= '<td class="text-center">' . $allCrimeCatCount346 . '</td>';
        $htmlData .= '<td class="text-center">' . $allCrimeCatCount347 . '</td>';
        $htmlData .= '<td class="text-center">' . $allCrimeCatCount348 . '</td>';
        $htmlData .= '<td class="text-center">' . $allCrimeCatCount437 . '</td>';
        $htmlData .= '<td class="text-center">' . $allCrimeSumTotal . '</td>';
        $htmlData .= '</tr>';
        $htmlData .= '</tfoot>';
        $htmlData .= '</table>';
        $htmlData .= '</div>';


        return $htmlData;
    }

    public function getReportDoctorViewGeneralData_model($param = array()) {

        $htmlData = $queryStringData = $queryStringYearData = $queryStringInDateData = $queryStringObjectData = $queryStringMotiveData = $isAjaxDepartmentUrl = '';

        if ($param['departmentId'] > 0) {

            $queryStringData .= ' AND NDV.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        if ($param['reportIsClose'] == 1) {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NDV.close_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NDV.close_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NDV.close_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NDV.close_date)';
            }
        } else {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NDV.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NDV.in_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NDV.in_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NDV.out_date)';
            }
        }


        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NDV.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $query = $this->db->query('
            SELECT
                NCSV.id,
                NCSV.title,
                SV1.short_value_count,
                IF(SV1.short_value_count > 0, \'_row-more\', \'\') AS short_value_count_class,
                SVSEX1.sex_1,
                IF(SVSEX1.sex_1 > 0, \'_row-more\', \'\') AS sex_1_class,
                SVSEX0.sex_0,
                IF(SVSEX0.sex_0 > 0, \'_row-more\', \'\') AS sex_0_class,
                SVAGE_0_1.age_0_1,
                IF(SVAGE_0_1.age_0_1 > 0, \'_row-more\', \'\') AS age_0_1_class,
                SVAGE_2_17.age_2_17,
                IF(SVAGE_2_17.age_2_17 > 0, \'_row-more\', \'\') AS age_2_17_class,
                SVAGE_18.age_18,
                IF(SVAGE_18.age_18 > 0, \'_row-more\', \'\') AS age_18_class,
                SVAGE.age_infinitive,
                IF(SVAGE.age_infinitive > 0, \'_row-more\', \'\') AS age_infinitive_class,
                SVAGE_1_4.age_1_4,
                IF(SVAGE_1_4.age_1_4 > 0, \'_row-more\', \'\') AS age_1_4_class,
                SVAGE_5_9.age_5_9,
                IF(SVAGE_5_9.age_5_9 > 0, \'_row-more\', \'\') AS age_5_9_class,
                SVAGE_10_14.age_10_14,
                IF(SVAGE_10_14.age_10_14 > 0, \'_row-more\', \'\') AS age_10_14_class,
                SVAGE_15_19.age_15_19,
                IF(SVAGE_15_19.age_15_19 > 0, \'_row-more\', \'\') AS age_15_19_class,
                SVAGE_20_24.age_20_24,
                IF(SVAGE_20_24.age_20_24 > 0, \'_row-more\', \'\') AS age_20_24_class,
                SVAGE_25_29.age_25_29,
                IF(SVAGE_25_29.age_25_29 > 0, \'_row-more\', \'\') AS age_25_29_class,
                SVAGE_30_34.age_30_34,
                IF(SVAGE_30_34.age_30_34 > 0, \'_row-more\', \'\') AS age_30_34_class,
                SVAGE_35_39.age_35_39,
                IF(SVAGE_35_39.age_35_39 > 0, \'_row-more\', \'\') AS age_35_39_class,
                SVAGE_40_44.age_40_44,
                IF(SVAGE_40_44.age_40_44 > 0, \'_row-more\', \'\') AS age_40_44_class,
                SVAGE_45_49.age_45_49,
                IF(SVAGE_45_49.age_45_49 > 0, \'_row-more\', \'\') AS age_45_49_class,
                SVAGE_50_54.age_50_54,
                IF(SVAGE_50_54.age_50_54 > 0, \'_row-more\', \'\') AS age_50_54_class,
                SVAGE_55_59.age_55_59,
                IF(SVAGE_55_59.age_55_59 > 0, \'_row-more\', \'\') AS age_55_59_class,
                SVAGE_60_64.age_60_64,
                IF(SVAGE_60_64.age_60_64 > 0, \'_row-more\', \'\') AS age_60_64_class,
                SVAGE_65.age_65,
                IF(SVAGE_65.age_65 > 0, \'_row-more\', \'\') AS age_65_class
            FROM `gaz_nifs_crime_short_value` AS NCSV
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS short_value_count
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SV1 ON SV1.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.short_value_id,
                    COUNT(NDV.sex) AS sex_1
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.sex = 1 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVSEX1 ON SVSEX1.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.short_value_id,
                    COUNT(NDV.sex) AS sex_0
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.sex = 0 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVSEX0 ON SVSEX0.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_0_1
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 0 and NDV.age < 1 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVAGE_0_1 ON SVAGE_0_1.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_2_17
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 1 and NDV.age <= 17 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVAGE_2_17 ON SVAGE_2_17.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_18
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 18 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVAGE_18 ON SVAGE_18.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.short_value_id,
                    COUNT(NDV.is_age_infinitive) AS age_infinitive
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.is_age_infinitive = 1 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVAGE ON SVAGE.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_1_4
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 1 AND NDV.age <= 4 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVAGE_1_4 ON SVAGE_1_4.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_5_9
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 5 AND NDV.age <= 9 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVAGE_5_9 ON SVAGE_5_9.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_10_14
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 10 AND NDV.age <= 14 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVAGE_10_14 ON SVAGE_10_14.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_15_19
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 15 AND NDV.age <= 19 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVAGE_15_19 ON SVAGE_15_19.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_20_24
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 20 AND NDV.age <= 24 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVAGE_20_24 ON SVAGE_20_24.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_25_29
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 25 AND NDV.age <= 29 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVAGE_25_29 ON SVAGE_25_29.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_30_34
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 30 AND NDV.age <= 34 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVAGE_30_34 ON SVAGE_30_34.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_35_39
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 35 AND NDV.age <= 39 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVAGE_35_39 ON SVAGE_35_39.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_40_44
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 40 AND NDV.age <= 44 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVAGE_40_44 ON SVAGE_40_44.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_45_49
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 45 AND NDV.age <= 49 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVAGE_45_49 ON SVAGE_45_49.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_50_54
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.age >= 50 AND NDV.age <= 54 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVAGE_50_54 ON SVAGE_50_54.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_55_59
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 55 AND NDV.age <= 59 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVAGE_55_59 ON SVAGE_55_59.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_60_64
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 60 AND NDV.age <= 64 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVAGE_60_64 ON SVAGE_60_64.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS age_65
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 65 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVAGE_65 ON SVAGE_65.short_value_id = NCSV.id
            WHERE NCSV.is_active = 1
            ORDER BY NCSV.order_num ASC');

        if ($query->num_rows() > 0) {

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Үзлэгийн хэлбэр, шалтгаан</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">Бүгд</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">Эр</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">Эм</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">0-1 хүртэл</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">1-17 нас</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">18 дээш нас</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">Нас тодорхойгүй</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">1-4</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">5-9</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">10-14</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">15-19</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">20-24</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">25-29</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">30-34</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">35-39</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">40-44</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">45-49</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">50-54</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">55-59</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">60-64</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">65 дээш</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            $i = $sumTotalShortValueCount = $sumTotalSex_1 = $sumTotalSex_0 = $sumTotalAge_0_1 = $sumTotalAge_2_17 = $sumTotalAge_18 = $sumTotalAgeInfinitive = $sumTotalAge_1_4 = $sumTotalAge_5_9 = $sumTotalAge_10_14 = $sumTotalAge_15_19 = $sumTotalAge_20_24 = $sumTotalAge_25_29 = $sumTotalAge_30_34 = $sumTotalAge_35_39 = $sumTotalAge_40_44 = $sumTotalAge_45_49 = $sumTotalAge_50_54 = $sumTotalAge_55_59 = $sumTotalAge_60_64 = $sumTotalAge_65 = 0;

            foreach ($query->result() as $keyCategory => $row) {

                $sumTotalShortValueCount = $sumTotalShortValueCount + $row->short_value_count;
                $sumTotalSex_1 = $sumTotalSex_1 + $row->sex_1;
                $sumTotalSex_0 = $sumTotalSex_0 + $row->sex_0;
                $sumTotalAge_0_1 = $sumTotalAge_0_1 + $row->age_0_1;
                $sumTotalAge_2_17 = $sumTotalAge_2_17 + $row->age_2_17;
                $sumTotalAge_18 = $sumTotalAge_18 + $row->age_18;
                $sumTotalAgeInfinitive = $sumTotalAgeInfinitive + $row->age_infinitive;
                $sumTotalAge_1_4 = $sumTotalAge_1_4 + $row->age_1_4;
                $sumTotalAge_5_9 = $sumTotalAge_5_9 + $row->age_5_9;
                $sumTotalAge_10_14 = $sumTotalAge_10_14 + $row->age_10_14;
                $sumTotalAge_15_19 = $sumTotalAge_15_19 + $row->age_15_19;
                $sumTotalAge_20_24 = $sumTotalAge_20_24 + $row->age_20_24;
                $sumTotalAge_25_29 = $sumTotalAge_25_29 + $row->age_25_29;
                $sumTotalAge_30_34 = $sumTotalAge_30_34 + $row->age_30_34;
                $sumTotalAge_35_39 = $sumTotalAge_35_39 + $row->age_35_39;
                $sumTotalAge_40_44 = $sumTotalAge_40_44 + $row->age_40_44;
                $sumTotalAge_45_49 = $sumTotalAge_45_49 + $row->age_45_49;
                $sumTotalAge_50_54 = $sumTotalAge_50_54 + $row->age_50_54;
                $sumTotalAge_55_59 = $sumTotalAge_55_59 + $row->age_55_59;
                $sumTotalAge_60_64 = $sumTotalAge_60_64 + $row->age_60_64;
                $sumTotalAge_65 = $sumTotalAge_65 + $row->age_65;


                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->short_value_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->short_value_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->sex_1_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&sex=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->sex_1 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->sex_0_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&sex=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->sex_0 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_0_1_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&age1=0&age2=1&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_0_1 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_2_17_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&age1=2&age2=17&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_2_17 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_18_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&age1=18&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_18 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_infinitive_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&isAgeInfinitive=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_infinitive . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_1_4_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&age1=1&age2=4&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_1_4 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_5_9_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&age1=5&age2=9&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_5_9 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_10_14_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&age1=10&age2=14&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_10_14 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_15_19_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&age1=15&age2=19&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_15_19 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_20_24_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&age1=20&age2=24&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_20_24 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_25_29_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&age1=25&age2=29&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_25_29 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_30_34_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&age1=30&age2=34&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_30_34 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_35_39_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&age1=35&age2=39&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_35_39 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_40_44_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&age1=40&age2=44&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_40_44 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_45_49_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&age1=45&age2=49&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_45_49 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_50_54_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&age1=45&age2=49&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_50_54 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_55_59_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&age1=55&age2=59&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_55_59 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_60_64_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&age1=60&age2=64&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_60_64 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_65_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&age1=65&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_65 . '</a></td>';
                $htmlData .= '</tr>';

                $i++;
            }
            $htmlData .= '</tbody>';

            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalShortValueCount > 0 ? $sumTotalShortValueCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&sex=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalSex_1 > 0 ? $sumTotalSex_1 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&sex=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalSex_0 > 0 ? $sumTotalSex_0 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&age1=0&age2=1&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_0_1 > 0 ? $sumTotalAge_0_1 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&age1=2&age2=17&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_2_17 > 0 ? $sumTotalAge_2_17 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&age1=18&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_18 > 0 ? $sumTotalAge_18 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&isAgeInfinitive=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAgeInfinitive > 0 ? $sumTotalAgeInfinitive : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&age1=1&age2=4&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_1_4 > 0 ? $sumTotalAge_1_4 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&age1=5&age2=9&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_5_9 > 0 ? $sumTotalAge_5_9 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&age1=10&age2=14&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_10_14 > 0 ? $sumTotalAge_10_14 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&age1=15&age2=19&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_15_19 > 0 ? $sumTotalAge_15_19 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&age1=20&age2=24&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_20_24 > 0 ? $sumTotalAge_20_24 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&age1=25&age2=29&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_25_29 > 0 ? $sumTotalAge_25_29 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&age1=30&age2=34&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_30_34 > 0 ? $sumTotalAge_30_34 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&age1=35&age2=39&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_35_39 > 0 ? $sumTotalAge_35_39 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&age1=40&age2=44&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_40_44 > 0 ? $sumTotalAge_40_44 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&age1=45&age2=49&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_45_49 > 0 ? $sumTotalAge_45_49 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&age1=45&age2=49&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_50_54 > 0 ? $sumTotalAge_50_54 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&age1=55&age2=59&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_55_59 > 0 ? $sumTotalAge_55_59 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&age1=60&age2=64&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_60_64 > 0 ? $sumTotalAge_60_64 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&age1=65&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_65 > 0 ? $sumTotalAge_65 : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';
        }

        $query = $this->db->query('
            SELECT
                NCSV.id,
                NCSV.title,
                SV1.short_value_count,
                IF(SV1.short_value_count > 0, \'_row-more\', \'\') AS short_value_count_class,
                SVWORK1.work_count_1,
                IF(SVWORK1.work_count_1 > 0, \'_row-more\', \'\') AS work_count_1_class,
                SVWORK2.work_count_2,
                IF(SVWORK2.work_count_2 > 0, \'_row-more\', \'\') AS work_count_2_class,
                SVWORK3.work_count_3,
                IF(SVWORK3.work_count_3 > 0, \'_row-more\', \'\') AS work_count_3_class,
                SVWORK4.work_count_4,
                IF(SVWORK4.work_count_4 > 0, \'_row-more\', \'\') AS work_count_4_class,
                SVWORK5.work_count_5,
                IF(SVWORK5.work_count_5 > 0, \'_row-more\', \'\') AS work_count_5_class,
                SVWORK6.work_count_6,
                IF(SVWORK6.work_count_6 > 0, \'_row-more\', \'\') AS work_count_6_class,
                SVWORK7.work_count_7,
                IF(SVWORK7.work_count_7 > 0, \'_row-more\', \'\') AS work_count_7_class,
                SVWORK8.work_count_8,
                IF(SVWORK8.work_count_8 > 0, \'_row-more\', \'\') AS work_count_8_class,
                SVPAYMENT0.payment_0,
                IF(SVPAYMENT0.payment_0 > 0, \'_row-more\', \'\') AS payment_0_class,
                SVPAYMENT1.payment_1,
                IF(SVPAYMENT1.payment_1 > 0, \'_row-more\', \'\') AS payment_1_class
            FROM `gaz_nifs_crime_short_value` AS NCSV
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS short_value_count
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SV1 ON SV1.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS work_count_1
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.work_id = 1 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVWORK1 ON SVWORK1.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS work_count_2
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.work_id = 2 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVWORK2 ON SVWORK2.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS work_count_3
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.work_id = 3 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVWORK3 ON SVWORK3.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS work_count_4
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.work_id = 4 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVWORK4 ON SVWORK4.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS work_count_5
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.work_id = 5 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVWORK5 ON SVWORK5.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS work_count_6
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.work_id = 6 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVWORK6 ON SVWORK6.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS work_count_7
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.work_id = 7 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVWORK7 ON SVWORK7.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS work_count_8
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.work_id = 8 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVWORK8 ON SVWORK8.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS payment_0
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.payment = 0 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVPAYMENT0 ON SVPAYMENT0.short_value_id = NCSV.id
            LEFT JOIN (
                SELECT 
                    NDV.short_value_id,
                    COUNT(NDV.short_value_id) AS payment_1
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.payment = 1 ' . $queryStringData . '
                GROUP BY NDV.short_value_id
            ) AS SVPAYMENT1 ON SVPAYMENT1.short_value_id = NCSV.id
            WHERE NCSV.is_active = 1
            ORDER BY NCSV.order_num ASC');

        if ($query->num_rows() > 0) {

            $htmlData .= '<br><br>';
            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Үзлэгийн хэлбэр, шалтгаан</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Бүгд</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Ажилчин</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Албан хаагч</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Малчин</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Тэтгэвэр</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Оюутан</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">0-15 нас</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Ажилгүй</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Бусад</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Төлбөртэй</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Төлбөргүй</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            $i = 1;
            $sumShortValueCount = $sumWorkCount_1 = $sumWorkCount_2 = $sumWorkCount_3 = $sumWorkCount_4 = $sumWorkCount_5 = $sumWorkCount_6 = $sumWorkCount_7 = $sumWorkCount_8 = $sumPayment_0 = $sumPayment_1 = 0;

            foreach ($query->result() as $keyCategory => $row) {

                $sumShortValueCount = $sumShortValueCount + $row->short_value_count;
                $sumWorkCount_1 = $sumWorkCount_1 + $row->work_count_1;
                $sumWorkCount_2 = $sumWorkCount_2 + $row->work_count_2;
                $sumWorkCount_3 = $sumWorkCount_3 + $row->work_count_3;
                $sumWorkCount_4 = $sumWorkCount_4 + $row->work_count_4;
                $sumWorkCount_5 = $sumWorkCount_5 + $row->work_count_5;
                $sumWorkCount_6 = $sumWorkCount_6 + $row->work_count_6;
                $sumWorkCount_7 = $sumWorkCount_7 + $row->work_count_7;
                $sumWorkCount_8 = $sumWorkCount_8 + $row->work_count_8;
                $sumPayment_0 = $sumPayment_0 + $row->payment_0;
                $sumPayment_1 = $sumPayment_1 + $row->payment_1;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->short_value_count_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->short_value_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->work_count_1_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&workId=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->work_count_1 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->work_count_2_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&workId=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->work_count_2 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->work_count_3_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&workId=3&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->work_count_3 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->work_count_4_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&workId=4&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->work_count_4 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->work_count_5_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&workId=5&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->work_count_5 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->work_count_6_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&workId=6&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->work_count_6 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->work_count_7_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&workId=7&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->work_count_7 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->work_count_8_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&workId=8&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->work_count_8 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->payment_1_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&payment=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->payment_1 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->payment_0_class . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=' . $row->id . '&payment=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->payment_0 . '</a></td>';
                $htmlData .= '</tr>';

                $i++;
            }
            $htmlData .= '</tbody>';

            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right">Нийт</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumShortValueCount > 0 ? $sumShortValueCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&workId=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumWorkCount_1 > 0 ? $sumWorkCount_1 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&workId=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumWorkCount_2 > 0 ? $sumWorkCount_2 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&workId=3&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumWorkCount_3 > 0 ? $sumWorkCount_3 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&workId=4&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumWorkCount_4 > 0 ? $sumWorkCount_4 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&workId=5&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumWorkCount_5 > 0 ? $sumWorkCount_5 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&workId=6&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumWorkCount_6 > 0 ? $sumWorkCount_6 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&workId=7&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumWorkCount_7 > 0 ? $sumWorkCount_7 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&workId=8&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumWorkCount_8 > 0 ? $sumWorkCount_8 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&payment=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumPayment_1 > 0 ? $sumPayment_1 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'shortValueId=all&payment=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumPayment_0 > 0 ? $sumPayment_0 : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';
        }


        $queryNot = $this->db->query('
                SELECT 
                    NDV.create_number
                FROM `gaz_nifs_doctor_view` AS NDV
                WHERE NDV.short_value_id = 0 ' . $queryStringData);

        if ($queryNot->num_rows() > 0) {

            if ($queryNot->num_rows() > 0) {

                $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ (' . $queryNot->num_rows() . '): ';

                foreach ($queryNot->result() as $rowNot) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'createNumber=' . $rowNot->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNot->create_number . '</a>';
                }

                $htmlData .= '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }

        return $htmlData;
    }

    public function getReportFileFolderGeneralData_model($param = array()) {

        $queryStringData = $htmlData = '';

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND \'' . $param['inDate'] . '\' <= DATE(N.in_date) AND \'' . $param['outDate'] . '\' >= DATE(N.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND \'' . $param['inDate'] . '\' <= DATE(N.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND \'' . $param['outDate'] . '\' >= DATE(N.in_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $provinceSumCrime = $provinceSumDoctorView = $provinceSumAnatomy = $provinceSumTotal = 0;
        $citySumCrime = $citySumDoctorView = $citySumAnatomy = $citySumTotal = 0;
        $centerSumCrime = $centerSumDoctorView = $centerSumAnatomy = $centerSumTotal = 0;

        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                NC.crime_count,
                NDV.doctor_view_count,
                NA.anatomy_count
            FROM `gaz_hr_people_department` AS HPD
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS crime_count
                FROM `gaz_nifs_crime` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC ON NC.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS doctor_view_count
                FROM `gaz_nifs_doctor_view` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NDV ON NDV.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS anatomy_count
                FROM `gaz_nifs_anatomy` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NA ON NA.department_id = HPD.id
            WHERE HPD.is_active = 1 AND HPD.parent_id = 18');


        if ($query->num_rows() > 0) {

            $i = 1;
            $htmlData .= '<h6>Орон нутаг</h6>';
            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Аймаг</th>';
            $htmlData .= '<th style="width:150px;" class="text-center">Криминалистик</th>';
            $htmlData .= '<th style="width:150px;" class="text-center">Эмчийн үзлэг</th>';
            $htmlData .= '<th style="width:150px;" class="text-center">Задлан шинжилгээ</th>';
            $htmlData .= '<th style="width:150px;" class="text-center">Нийт</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            $sumTotal = $sumCrimeCount = $sumDoctorViewCount = $sumCrimeCount = 0;
            foreach ($query->result() as $key => $row) {

                $provinceSumCrime = $provinceSumCrime + $row->crime_count;
                $provinceSumDoctorView = $provinceSumDoctorView + $row->doctor_view_count;
                $provinceSumAnatomy = $provinceSumAnatomy + $row->anatomy_count;
                $provinceSumTotal = $provinceSumTotal + ($row->crime_count + $row->doctor_view_count + $row->anatomy_count);

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center">' . $row->crime_count . '</td>';
                $htmlData .= '<td class="text-center">' . $row->doctor_view_count . '</td>';
                $htmlData .= '<td class="text-center">' . $row->anatomy_count . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->crime_count + $row->doctor_view_count + $row->anatomy_count) . '</td>';
                $htmlData .= '</tr>';

                $i++;
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td>Нийт дүн</td>';
            $htmlData .= '<td class="text-center">' . $provinceSumCrime . '</td>';
            $htmlData .= '<td class="text-center">' . $provinceSumDoctorView . '</td>';
            $htmlData .= '<td class="text-center">' . $provinceSumAnatomy . '</td>';
            $htmlData .= '<td class="text-center">' . $provinceSumTotal . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';
        }

        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                NC.crime_count,
                NDV.doctor_view_count,
                NA.anatomy_count
            FROM `gaz_hr_people_department` AS HPD
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS crime_count
                FROM `gaz_nifs_crime` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC ON NC.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS doctor_view_count
                FROM `gaz_nifs_doctor_view` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NDV ON NDV.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS anatomy_count
                FROM `gaz_nifs_anatomy` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NA ON NA.department_id = HPD.id
            WHERE HPD.is_active = 1 AND HPD.parent_id = 8');


        if ($query->num_rows() > 0) {

            $i = 1;
            $htmlData .= '<h6>Нийслэл</h6>';
            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Аймаг</th>';
            $htmlData .= '<th style="width:150px;" class="text-center">Криминалистик</th>';
            $htmlData .= '<th style="width:150px;" class="text-center">Эмчийн үзлэг</th>';
            $htmlData .= '<th style="width:150px;" class="text-center">Задлан шинжилгээ</th>';
            $htmlData .= '<th style="width:150px;" class="text-center">Нийт</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            $sumTotal = $sumCrimeCount = $sumDoctorViewCount = $sumCrimeCount = 0;
            foreach ($query->result() as $key => $row) {

                $citySumCrime = $citySumCrime + $row->crime_count;
                $citySumDoctorView = $citySumDoctorView + $row->doctor_view_count;
                $citySumAnatomy = $citySumAnatomy + $row->anatomy_count;
                $citySumTotal = $citySumTotal + ($row->crime_count + $row->doctor_view_count + $row->anatomy_count);

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center">' . $row->crime_count . '</td>';
                $htmlData .= '<td class="text-center">' . $row->doctor_view_count . '</td>';
                $htmlData .= '<td class="text-center">' . $row->anatomy_count . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->crime_count + $row->doctor_view_count + $row->anatomy_count) . '</td>';
                $htmlData .= '</tr>';

                $i++;
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td>Нийт дүн</td>';
            $htmlData .= '<td class="text-center">' . $citySumCrime . '</td>';
            $htmlData .= '<td class="text-center">' . $citySumDoctorView . '</td>';
            $htmlData .= '<td class="text-center">' . $citySumAnatomy . '</td>';
            $htmlData .= '<td class="text-center">' . $citySumTotal . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';
        }


        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                NC.crime_count,
                NDV.doctor_view_count,
                NA.anatomy_count
            FROM `gaz_hr_people_department` AS HPD
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS crime_count
                FROM `gaz_nifs_crime` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC ON NC.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS doctor_view_count
                FROM `gaz_nifs_doctor_view` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NDV ON NDV.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS anatomy_count
                FROM `gaz_nifs_anatomy` AS N 
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NA ON NA.department_id = HPD.id
            WHERE HPD.is_active = 1 AND HPD.id IN(3,4,5,6)');


        if ($query->num_rows() > 0) {

            $i = 1;
            $htmlData .= '<h6>Хүрээлэн</h6>';
            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Аймаг</th>';
            $htmlData .= '<th style="width:150px;" class="text-center">Криминалистик</th>';
            $htmlData .= '<th style="width:150px;" class="text-center">Эмчийн үзлэг</th>';
            $htmlData .= '<th style="width:150px;" class="text-center">Задлан шинжилгээ</th>';
            $htmlData .= '<th style="width:150px;" class="text-center">Нийт</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            $sumTotal = $sumCrimeCount = $sumDoctorViewCount = $sumCrimeCount = 0;
            foreach ($query->result() as $key => $row) {

                $centerSumCrime = $centerSumCrime + $row->crime_count;
                $centerSumDoctorView = $centerSumDoctorView + $row->doctor_view_count;
                $centerSumAnatomy = $centerSumAnatomy + $row->anatomy_count;
                $centerSumTotal = $centerSumTotal + ($row->crime_count + $row->doctor_view_count + $row->anatomy_count);

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center">' . $row->crime_count . '</td>';
                $htmlData .= '<td class="text-center">' . $row->doctor_view_count . '</td>';
                $htmlData .= '<td class="text-center">' . $row->anatomy_count . '</td>';
                $htmlData .= '<td class="text-center">' . ($row->crime_count + $row->doctor_view_count + $row->anatomy_count) . '</td>';
                $htmlData .= '</tr>';

                $i++;
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td>Нийт дүн</td>';
            $htmlData .= '<td class="text-center">' . $centerSumCrime . '</td>';
            $htmlData .= '<td class="text-center">' . $centerSumDoctorView . '</td>';
            $htmlData .= '<td class="text-center">' . $centerSumAnatomy . '</td>';
            $htmlData .= '<td class="text-center">' . $centerSumTotal . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';
        }

        $htmlData .= '<h6>Улсын нэгдсэн дүн</h6>';
        $htmlData .= '<div class="table-responsive">';
        $htmlData .= '<table class="table _report">';
        $htmlData .= '<thead>';
        $htmlData .= '<tr>';
        $htmlData .= '<th style="width:30px;">#</th>';
        $htmlData .= '<th>Гарчиг</th>';
        $htmlData .= '<th style="width:150px;" class="text-center">Криминалистик</th>';
        $htmlData .= '<th style="width:150px;" class="text-center">Эмчийн үзлэг</th>';
        $htmlData .= '<th style="width:150px;" class="text-center">Задлан шинжилгээ</th>';
        $htmlData .= '<th style="width:150px;" class="text-center">Нийт</th>';
        $htmlData .= '</tr>';

        $htmlData .= '</thead>';

        $allSumCrime = $allSumDoctorView = $allSumAnatomy = $allSumTotal = 0;

        $allSumCrime = $provinceSumCrime + $citySumCrime + $centerSumCrime;
        $allSumDoctorView = $provinceSumDoctorView + $citySumDoctorView + $centerSumDoctorView;
        $allSumAnatomy = $provinceSumAnatomy + $citySumAnatomy + $centerSumAnatomy;
        $allSumTotal = $provinceSumTotal + $citySumTotal + $centerSumTotal;

        $htmlData .= '<tbody>';

        $htmlData .= '<tr>';
        $htmlData .= '<td>1</td>';
        $htmlData .= '<td>Хүрээлэн</td>';
        $htmlData .= '<td class="text-center">' . $provinceSumCrime . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceSumDoctorView . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceSumAnatomy . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceSumTotal . '</td>';
        $htmlData .= '</tr>';

        $htmlData .= '<tr>';
        $htmlData .= '<td>1</td>';
        $htmlData .= '<td>Нийслэл</td>';
        $htmlData .= '<td class="text-center">' . $citySumCrime . '</td>';
        $htmlData .= '<td class="text-center">' . $citySumDoctorView . '</td>';
        $htmlData .= '<td class="text-center">' . $citySumAnatomy . '</td>';
        $htmlData .= '<td class="text-center">' . $citySumTotal . '</td>';
        $htmlData .= '</tr>';

        $htmlData .= '<tr>';
        $htmlData .= '<td>1</td>';
        $htmlData .= '<td>Орон нутаг</td>';
        $htmlData .= '<td class="text-center">' . $centerSumCrime . '</td>';
        $htmlData .= '<td class="text-center">' . $centerSumDoctorView . '</td>';
        $htmlData .= '<td class="text-center">' . $centerSumAnatomy . '</td>';
        $htmlData .= '<td class="text-center">' . $centerSumTotal . '</td>';
        $htmlData .= '</tr>';

        $htmlData .= '</tbody>';
        $htmlData .= '<tfoot>';
        $htmlData .= '<tr>';
        $htmlData .= '<td></td>';
        $htmlData .= '<td>Нийт дүн:</td>';
        $htmlData .= '<td class="text-center">' . $allSumCrime . '</td>';
        $htmlData .= '<td class="text-center">' . $allSumDoctorView . '</td>';
        $htmlData .= '<td class="text-center">' . $allSumAnatomy . '</td>';
        $htmlData .= '<td class="text-center">' . $allSumTotal . '</td>';
        $htmlData .= '</tr>';
        $htmlData .= '</tfoot>';
        $htmlData .= '</table>';
        $htmlData .= '</div>';

        return $htmlData;
    }

    public function getReportAnatomyGeneralData_model($param = array()) {

        $htmlData = $queryStringData = $queryStringYearData = $queryStringInDateData = $queryStringObjectData = $queryStringMotiveData = $isAjaxDepartmentUrl = '';

        if ($param['departmentId'] > 0) {

            $queryStringData .= ' AND NDV.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        if ($param['reportIsClose'] == 1) {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NDV.close_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NDV.close_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NDV.close_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NDV.close_date)';
            }
        } else {
            if ($param['inDate'] != '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NDV.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NDV.in_date)';
            } else if ($param['inDate'] != '' and $param['outDate'] == '') {

                $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NDV.in_date)';
            } else if ($param['inDate'] == '' and $param['outDate'] != '') {

                $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NDV.out_date)';
            }
        }


        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NDV.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $query = $this->db->query('
            SELECT
                NCSV.id,
                NCSV.title,
                SV1.short_value_count,
                IF(SV1.short_value_count > 0, \'_row-more\', \'\') AS short_value_count_class,
                SVSEX1.sex_1,
                IF(SVSEX1.sex_1 > 0, \'_row-more\', \'\') AS sex_1_class,
                SVSEX0.sex_0,
                IF(SVSEX0.sex_0 > 0, \'_row-more\', \'\') AS sex_0_class,
                SVAGE_0_1.age_0_1,
                IF(SVAGE_0_1.age_0_1 > 0, \'_row-more\', \'\') AS age_0_1_class,
                SVAGE_2_17.age_2_17,
                IF(SVAGE_2_17.age_2_17 > 0, \'_row-more\', \'\') AS age_2_17_class,
                SVAGE_18.age_18,
                IF(SVAGE_18.age_18 > 0, \'_row-more\', \'\') AS age_18_class,
                SVAGE.age_infinitive,
                IF(SVAGE.age_infinitive > 0, \'_row-more\', \'\') AS age_infinitive_class,
                SVAGE_1_4.age_1_4,
                IF(SVAGE_1_4.age_1_4 > 0, \'_row-more\', \'\') AS age_1_4_class,
                SVAGE_5_9.age_5_9,
                IF(SVAGE_5_9.age_5_9 > 0, \'_row-more\', \'\') AS age_5_9_class,
                SVAGE_10_14.age_10_14,
                IF(SVAGE_10_14.age_10_14 > 0, \'_row-more\', \'\') AS age_10_14_class,
                SVAGE_15_19.age_15_19,
                IF(SVAGE_15_19.age_15_19 > 0, \'_row-more\', \'\') AS age_15_19_class,
                SVAGE_20_24.age_20_24,
                IF(SVAGE_20_24.age_20_24 > 0, \'_row-more\', \'\') AS age_20_24_class,
                SVAGE_25_29.age_25_29,
                IF(SVAGE_25_29.age_25_29 > 0, \'_row-more\', \'\') AS age_25_29_class,
                SVAGE_30_34.age_30_34,
                IF(SVAGE_30_34.age_30_34 > 0, \'_row-more\', \'\') AS age_30_34_class,
                SVAGE_35_39.age_35_39,
                IF(SVAGE_35_39.age_35_39 > 0, \'_row-more\', \'\') AS age_35_39_class,
                SVAGE_40_44.age_40_44,
                IF(SVAGE_40_44.age_40_44 > 0, \'_row-more\', \'\') AS age_40_44_class,
                SVAGE_45_49.age_45_49,
                IF(SVAGE_45_49.age_45_49 > 0, \'_row-more\', \'\') AS age_45_49_class,
                SVAGE_50_54.age_50_54,
                IF(SVAGE_50_54.age_50_54 > 0, \'_row-more\', \'\') AS age_50_54_class,
                SVAGE_55_59.age_55_59,
                IF(SVAGE_55_59.age_55_59 > 0, \'_row-more\', \'\') AS age_55_59_class,
                SVAGE_60_64.age_60_64,
                IF(SVAGE_60_64.age_60_64 > 0, \'_row-more\', \'\') AS age_60_64_class,
                SVAGE_65.age_65,
                IF(SVAGE_65.age_65 > 0, \'_row-more\', \'\') AS age_65_class
            FROM `gaz_nifs_solution` AS NCSV
            LEFT JOIN (
                SELECT 
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS short_value_count
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SV1 ON SV1.solution_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.solution_id,
                    COUNT(NDV.sex) AS sex_1
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.sex = 1 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVSEX1 ON SVSEX1.solution_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.solution_id,
                    COUNT(NDV.sex) AS sex_0
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.sex = 0 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVSEX0 ON SVSEX0.solution_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS age_0_1
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 0 and NDV.age < 1 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVAGE_0_1 ON SVAGE_0_1.solution_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS age_2_17
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 1 and NDV.age <= 17 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVAGE_2_17 ON SVAGE_2_17.solution_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS age_18
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 18 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVAGE_18 ON SVAGE_18.solution_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.solution_id,
                    COUNT(NDV.is_age_infinitive) AS age_infinitive
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.is_age_infinitive = 1 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVAGE ON SVAGE.solution_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS age_1_4
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 1 AND NDV.age <= 4 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVAGE_1_4 ON SVAGE_1_4.solution_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS age_5_9
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 5 AND NDV.age <= 9 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVAGE_5_9 ON SVAGE_5_9.solution_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS age_10_14
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 10 AND NDV.age <= 14 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVAGE_10_14 ON SVAGE_10_14.solution_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS age_15_19
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 15 AND NDV.age <= 19 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVAGE_15_19 ON SVAGE_15_19.solution_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS age_20_24
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 20 AND NDV.age <= 24 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVAGE_20_24 ON SVAGE_20_24.solution_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS age_25_29
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 25 AND NDV.age <= 29 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVAGE_25_29 ON SVAGE_25_29.solution_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS age_30_34
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 30 AND NDV.age <= 34 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVAGE_30_34 ON SVAGE_30_34.solution_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS age_35_39
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 35 AND NDV.age <= 39 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVAGE_35_39 ON SVAGE_35_39.solution_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS age_40_44
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 40 AND NDV.age <= 44 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVAGE_40_44 ON SVAGE_40_44.solution_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS age_45_49
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 45 AND NDV.age <= 49 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVAGE_45_49 ON SVAGE_45_49.solution_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS age_50_54
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.age >= 50 AND NDV.age <= 54 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVAGE_50_54 ON SVAGE_50_54.solution_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS age_55_59
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 55 AND NDV.age <= 59 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVAGE_55_59 ON SVAGE_55_59.solution_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS age_60_64
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 60 AND NDV.age <= 64 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVAGE_60_64 ON SVAGE_60_64.solution_id = NCSV.id
            LEFT JOIN (
                SELECT
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS age_65
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.is_age_infinitive = 0 AND NDV.age >= 65 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVAGE_65 ON SVAGE_65.solution_id = NCSV.id
            WHERE NCSV.is_active = 1 AND NCSV.cat_id = 363
            ORDER BY NCSV.order_num ASC');

        if ($query->num_rows() > 0) {

            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Үзлэгийн хэлбэр, шалтгаан</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">Бүгд</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">Эр</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">Эм</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">0-1 хүртэл</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">1-17 нас</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">18 дээш нас</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">Нас тодорхойгүй</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">1-4</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">5-9</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">10-14</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">15-19</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">20-24</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">25-29</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">30-34</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">35-39</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">40-44</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">45-49</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">50-54</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">55-59</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">60-64</th>';
            $htmlData .= '<th style="width:50px;" class="text-center">65 дээш</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            $i = $sumTotalShortValueCount = $sumTotalSex_1 = $sumTotalSex_0 = $sumTotalAge_0_1 = $sumTotalAge_2_17 = $sumTotalAge_18 = $sumTotalAgeInfinitive = $sumTotalAge_1_4 = $sumTotalAge_5_9 = $sumTotalAge_10_14 = $sumTotalAge_15_19 = $sumTotalAge_20_24 = $sumTotalAge_25_29 = $sumTotalAge_30_34 = $sumTotalAge_35_39 = $sumTotalAge_40_44 = $sumTotalAge_45_49 = $sumTotalAge_50_54 = $sumTotalAge_55_59 = $sumTotalAge_60_64 = $sumTotalAge_65 = 0;

            foreach ($query->result() as $keyCategory => $row) {

                $sumTotalShortValueCount = $sumTotalShortValueCount + $row->short_value_count;
                $sumTotalSex_1 = $sumTotalSex_1 + $row->sex_1;
                $sumTotalSex_0 = $sumTotalSex_0 + $row->sex_0;
                $sumTotalAge_0_1 = $sumTotalAge_0_1 + $row->age_0_1;
                $sumTotalAge_2_17 = $sumTotalAge_2_17 + $row->age_2_17;
                $sumTotalAge_18 = $sumTotalAge_18 + $row->age_18;
                $sumTotalAgeInfinitive = $sumTotalAgeInfinitive + $row->age_infinitive;
                $sumTotalAge_1_4 = $sumTotalAge_1_4 + $row->age_1_4;
                $sumTotalAge_5_9 = $sumTotalAge_5_9 + $row->age_5_9;
                $sumTotalAge_10_14 = $sumTotalAge_10_14 + $row->age_10_14;
                $sumTotalAge_15_19 = $sumTotalAge_15_19 + $row->age_15_19;
                $sumTotalAge_20_24 = $sumTotalAge_20_24 + $row->age_20_24;
                $sumTotalAge_25_29 = $sumTotalAge_25_29 + $row->age_25_29;
                $sumTotalAge_30_34 = $sumTotalAge_30_34 + $row->age_30_34;
                $sumTotalAge_35_39 = $sumTotalAge_35_39 + $row->age_35_39;
                $sumTotalAge_40_44 = $sumTotalAge_40_44 + $row->age_40_44;
                $sumTotalAge_45_49 = $sumTotalAge_45_49 + $row->age_45_49;
                $sumTotalAge_50_54 = $sumTotalAge_50_54 + $row->age_50_54;
                $sumTotalAge_55_59 = $sumTotalAge_55_59 + $row->age_55_59;
                $sumTotalAge_60_64 = $sumTotalAge_60_64 + $row->age_60_64;
                $sumTotalAge_65 = $sumTotalAge_65 + $row->age_65;


                $htmlData .= '<tr>';
                $htmlData .= '<td>' . ++$i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->short_value_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->short_value_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->sex_1_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&sex=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->sex_1 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->sex_0_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&sex=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->sex_0 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_0_1_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&age1=0&age2=1&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_0_1 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_2_17_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&age1=2&age2=17&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_2_17 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_18_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&age1=18&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_18 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_infinitive_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&isAgeInfinitive=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_infinitive . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_1_4_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&age1=1&age2=4&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_1_4 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_5_9_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&age1=5&age2=9&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_5_9 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_10_14_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&age1=10&age2=14&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_10_14 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_15_19_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&age1=15&age2=19&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_15_19 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_20_24_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&age1=20&age2=24&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_20_24 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_25_29_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&age1=25&age2=29&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_25_29 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_30_34_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&age1=30&age2=34&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_30_34 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_35_39_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&age1=35&age2=39&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_35_39 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_40_44_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&age1=40&age2=44&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_40_44 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_45_49_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&age1=45&age2=49&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_45_49 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_50_54_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&age1=45&age2=49&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_50_54 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_55_59_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&age1=55&age2=59&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_55_59 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_60_64_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&age1=60&age2=64&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_60_64 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->age_65_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&age1=65&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->age_65 . '</a></td>';
                $htmlData .= '</tr>';

                $i++;
            }

            $htmlData .= '</tbody>';

            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalShortValueCount > 0 ? $sumTotalShortValueCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&sex=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalSex_1 > 0 ? $sumTotalSex_1 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&sex=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalSex_0 > 0 ? $sumTotalSex_0 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&age1=0&age2=1&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_0_1 > 0 ? $sumTotalAge_0_1 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&age1=2&age2=17&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_2_17 > 0 ? $sumTotalAge_2_17 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&age1=18&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_18 > 0 ? $sumTotalAge_18 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&isAgeInfinitive=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAgeInfinitive > 0 ? $sumTotalAgeInfinitive : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&age1=1&age2=4&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_1_4 > 0 ? $sumTotalAge_1_4 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&age1=5&age2=9&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_5_9 > 0 ? $sumTotalAge_5_9 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&age1=10&age2=14&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_10_14 > 0 ? $sumTotalAge_10_14 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&age1=15&age2=19&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_15_19 > 0 ? $sumTotalAge_15_19 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&age1=20&age2=24&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_20_24 > 0 ? $sumTotalAge_20_24 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&age1=25&age2=29&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_25_29 > 0 ? $sumTotalAge_25_29 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&age1=30&age2=34&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_30_34 > 0 ? $sumTotalAge_30_34 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&age1=35&age2=39&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_35_39 > 0 ? $sumTotalAge_35_39 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&age1=40&age2=44&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_40_44 > 0 ? $sumTotalAge_40_44 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&age1=45&age2=49&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_45_49 > 0 ? $sumTotalAge_45_49 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&age1=45&age2=49&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_50_54 > 0 ? $sumTotalAge_50_54 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&age1=55&age2=59&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_55_59 > 0 ? $sumTotalAge_55_59 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&age1=60&age2=64&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_60_64 > 0 ? $sumTotalAge_60_64 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&age1=65&isAgeInfinitive=0&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumTotalAge_65 > 0 ? $sumTotalAge_65 : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';
        }

        $query = $this->db->query('
            SELECT
                NCSV.id,
                NCSV.title,
                SV1.short_value_count,
                IF(SV1.short_value_count > 0, \'_row-more\', \'\') AS short_value_count_class,
                SVWORK1.work_count_1,
                IF(SVWORK1.work_count_1 > 0, \'_row-more\', \'\') AS work_count_1_class,
                SVWORK2.work_count_2,
                IF(SVWORK2.work_count_2 > 0, \'_row-more\', \'\') AS work_count_2_class,
                SVWORK3.work_count_3,
                IF(SVWORK3.work_count_3 > 0, \'_row-more\', \'\') AS work_count_3_class,
                SVWORK4.work_count_4,
                IF(SVWORK4.work_count_4 > 0, \'_row-more\', \'\') AS work_count_4_class,
                SVWORK5.work_count_5,
                IF(SVWORK5.work_count_5 > 0, \'_row-more\', \'\') AS work_count_5_class,
                SVWORK6.work_count_6,
                IF(SVWORK6.work_count_6 > 0, \'_row-more\', \'\') AS work_count_6_class,
                SVWORK7.work_count_7,
                IF(SVWORK7.work_count_7 > 0, \'_row-more\', \'\') AS work_count_7_class,
                SVWORK8.work_count_8,
                IF(SVWORK8.work_count_8 > 0, \'_row-more\', \'\') AS work_count_8_class,
                SVPAYMENT0.payment_0,
                IF(SVPAYMENT0.payment_0 > 0, \'_row-more\', \'\') AS payment_0_class,
                SVPAYMENT1.payment_1,
                IF(SVPAYMENT1.payment_1 > 0, \'_row-more\', \'\') AS payment_1_class
            FROM `gaz_nifs_solution` AS NCSV
            LEFT JOIN (
                SELECT 
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS short_value_count
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE 1 = 1 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SV1 ON SV1.solution_id = NCSV.id
            LEFT JOIN (
                SELECT 
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS work_count_1
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.work_id = 1 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVWORK1 ON SVWORK1.solution_id = NCSV.id
            LEFT JOIN (
                SELECT 
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS work_count_2
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.work_id = 2 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVWORK2 ON SVWORK2.solution_id = NCSV.id
            LEFT JOIN (
                SELECT 
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS work_count_3
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.work_id = 3 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVWORK3 ON SVWORK3.solution_id = NCSV.id
            LEFT JOIN (
                SELECT 
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS work_count_4
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.work_id = 4 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVWORK4 ON SVWORK4.solution_id = NCSV.id
            LEFT JOIN (
                SELECT 
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS work_count_5
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.work_id = 5 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVWORK5 ON SVWORK5.solution_id = NCSV.id
            LEFT JOIN (
                SELECT 
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS work_count_6
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.work_id = 6 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVWORK6 ON SVWORK6.solution_id = NCSV.id
            LEFT JOIN (
                SELECT 
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS work_count_7
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.work_id = 7 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVWORK7 ON SVWORK7.solution_id = NCSV.id
            LEFT JOIN (
                SELECT 
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS work_count_8
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.work_id = 8 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVWORK8 ON SVWORK8.solution_id = NCSV.id
            LEFT JOIN (
                SELECT 
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS payment_0
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.payment = 0 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVPAYMENT0 ON SVPAYMENT0.solution_id = NCSV.id
            LEFT JOIN (
                SELECT 
                    NDV.solution_id,
                    COUNT(NDV.solution_id) AS payment_1
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.payment = 1 ' . $queryStringData . '
                GROUP BY NDV.solution_id
            ) AS SVPAYMENT1 ON SVPAYMENT1.solution_id = NCSV.id
            WHERE NCSV.is_active = 1 AND NCSV.cat_id = 363
            ORDER BY NCSV.order_num ASC');

        if ($query->num_rows() > 0) {

            $htmlData .= '<br><br>';
            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';

            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Үзлэгийн хэлбэр, шалтгаан</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Бүгд</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Ажилчин</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Албан хаагч</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Малчин</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Тэтгэвэр</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Оюутан</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">0-15 нас</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Ажилгүй</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Бусад</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Төлбөртэй</th>';
            $htmlData .= '<th style="width:60px;" class="text-center">Төлбөргүй</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            $i = 1;
            $sumShortValueCount = $sumWorkCount_1 = $sumWorkCount_2 = $sumWorkCount_3 = $sumWorkCount_4 = $sumWorkCount_5 = $sumWorkCount_6 = $sumWorkCount_7 = $sumWorkCount_8 = $sumPayment_0 = $sumPayment_1 = 0;

            foreach ($query->result() as $keyCategory => $row) {

                $sumShortValueCount = $sumShortValueCount + $row->short_value_count;
                $sumWorkCount_1 = $sumWorkCount_1 + $row->work_count_1;
                $sumWorkCount_2 = $sumWorkCount_2 + $row->work_count_2;
                $sumWorkCount_3 = $sumWorkCount_3 + $row->work_count_3;
                $sumWorkCount_4 = $sumWorkCount_4 + $row->work_count_4;
                $sumWorkCount_5 = $sumWorkCount_5 + $row->work_count_5;
                $sumWorkCount_6 = $sumWorkCount_6 + $row->work_count_6;
                $sumWorkCount_7 = $sumWorkCount_7 + $row->work_count_7;
                $sumWorkCount_8 = $sumWorkCount_8 + $row->work_count_8;
                $sumPayment_0 = $sumPayment_0 + $row->payment_0;
                $sumPayment_1 = $sumPayment_1 + $row->payment_1;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center ' . $row->short_value_count_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->short_value_count . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->work_count_1_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&workId=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->work_count_1 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->work_count_2_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&workId=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->work_count_2 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->work_count_3_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&workId=3&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->work_count_3 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->work_count_4_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&workId=4&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->work_count_4 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->work_count_5_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&workId=5&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->work_count_5 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->work_count_6_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&workId=6&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->work_count_6 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->work_count_7_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&workId=7&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->work_count_7 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->work_count_8_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&workId=8&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->work_count_8 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->payment_1_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&payment=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->payment_1 . '</a></td>';
                $htmlData .= '<td class="text-center ' . $row->payment_0_class . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=' . $row->id . '&payment=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . $row->payment_0 . '</a></td>';
                $htmlData .= '</tr>';

                $i++;
            }
            $htmlData .= '</tbody>';

            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td colspan="2" class="text-right">Нийт</td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumShortValueCount > 0 ? $sumShortValueCount : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&workId=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumWorkCount_1 > 0 ? $sumWorkCount_1 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&workId=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumWorkCount_2 > 0 ? $sumWorkCount_2 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&workId=3&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumWorkCount_3 > 0 ? $sumWorkCount_3 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&workId=4&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumWorkCount_4 > 0 ? $sumWorkCount_4 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&workId=5&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumWorkCount_5 > 0 ? $sumWorkCount_5 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&workId=6&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumWorkCount_6 > 0 ? $sumWorkCount_6 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&workId=7&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumWorkCount_7 > 0 ? $sumWorkCount_7 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&workId=8&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumWorkCount_8 > 0 ? $sumWorkCount_8 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&payment=1&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumPayment_1 > 0 ? $sumPayment_1 : '') . '</a></td>';
            $htmlData .= '<td class="text-center _custom-foot"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'solutionId=all&payment=2&isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . $isAjaxDepartmentUrl . '\'});">' . ($sumPayment_0 > 0 ? $sumPayment_0 : '') . '</a></td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';
        }


        $queryNot = $this->db->query('
                SELECT 
                    NDV.create_number
                FROM `gaz_nifs_anatomy` AS NDV
                WHERE NDV.solution_id = 0 ' . $queryStringData);

        if ($queryNot->num_rows() > 0) {

            if ($queryNot->num_rows() > 0) {

                $htmlData .= '<div class="table-responsive-inside-description">Шинжилгээ (' . $queryNot->num_rows() . '): ';

                foreach ($queryNot->result() as $rowNot) {
                    $htmlData .= '<a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'createNumber=' . $rowNot->create_number . '&isReportMenu=1&reportMenuId=' . $param['reportMenuId'] . '&reportModId=' . $param['reportModId'] . $isAjaxDepartmentUrl . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});" class="label-not-selected-content">№: ' . $rowNot->create_number . '</a>';
                }

                $htmlData .= '</div>';
            } else {
                $htmlData .= '<br>';
            }
        }

        return $htmlData;
    }

    public function getReportEconomyGeneralData_model($param = array()) {

        $queryStringData = $htmlData = '';

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND \'' . $param['inDate'] . '\' <= DATE(N.in_date) AND \'' . $param['outDate'] . '\' >= DATE(N.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND \'' . $param['inDate'] . '\' <= DATE(N.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND \'' . $param['outDate'] . '\' >= DATE(N.in_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND N.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $provinceSumCrime = $provinceSumDoctorView = $provinceSumAnatomy = $provinceSumTotal = 0;
        $citySumCrime = $citySumDoctorView = $citySumAnatomy = $citySumTotal = 0;
        $centerSumCrime = $centerSumDoctorView = $centerSumAnatomy = $centerSumTotal = 0;

        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                NC21.type_count_21
            FROM `gaz_hr_people_department` AS HPD
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS type_count_21
                FROM `gaz_nifs_economy` AS N 
                WHERE N.type_id = 21 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC21 ON NC21.department_id = HPD.id
            WHERE HPD.is_active = 1 AND HPD.parent_id = 18');

        $provinceTypeCount21 = $provinceRowSumTypeCount = 0;
        $typeCount21 = $rowSumTypeCount = 0;

        if ($query->num_rows() > 0) {

            $i = 1;
            $htmlData .= '<h6>Орон нутаг</h6>';
            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Гарчиг</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Эдийн засаг</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Нийт</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $rowTypeCount = $row->type_count_21;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center">' . $row->type_count_21 . '</td>';
                $htmlData .= '<td class="text-center">' . $rowTypeCount . '</td>';
                $htmlData .= '</tr>';

                $i++;

                $typeCount21 = $typeCount21 + $row->type_count_21;

                $rowSumTypeCount = $rowSumTypeCount + $rowTypeCount;
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td>Нийт дүн</td>';
            $htmlData .= '<td class="text-center">' . $typeCount21 . '</td>';
            $htmlData .= '<td class="text-center">' . $rowSumTypeCount . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $provinceTypeCount21 = $typeCount21;
            $provinceRowSumTypeCount = $rowSumTypeCount;
        }

        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                NC21.type_count_21
            FROM `gaz_hr_people_department` AS HPD
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS type_count_21
                FROM `gaz_nifs_economy` AS N 
                WHERE N.type_id = 21 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC21 ON NC21.department_id = HPD.id
            WHERE HPD.is_active = 1 AND HPD.parent_id = 8');

        $cityTypeCount21 = $cityRowSumTypeCount = 0;
        $typeCount21 = $rowSumTypeCount = 0;

        if ($query->num_rows() > 0) {

            $i = 1;
            $htmlData .= '<h6>Нийслэл</h6>';
            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Гарчиг</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Эдийн засаг</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Нийт</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $rowTypeCount = $row->type_count_21;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center">' . $row->type_count_21 . '</td>';
                $htmlData .= '<td class="text-center">' . $rowTypeCount . '</td>';
                $htmlData .= '</tr>';

                $i++;

                $typeCount21 = $typeCount21 + $row->type_count_21;

                $rowSumTypeCount = $rowSumTypeCount + $rowTypeCount;
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td>Нийт дүн</td>';
            $htmlData .= '<td class="text-center">' . $typeCount21 . '</td>';
            $htmlData .= '<td class="text-center">' . $rowSumTypeCount . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $cityTypeCount21 = $typeCount21;
            $cityRowSumTypeCount = $rowSumTypeCount;
        }



        $query = $this->db->query('
            SELECT 
                HPD.id,
                HPD.title,
                NC21.type_count_21
            FROM `gaz_hr_people_department` AS HPD
            LEFT JOIN (
                SELECT
                    N.department_id,
                    COUNT(N.department_id) AS type_count_21
                FROM `gaz_nifs_economy` AS N 
                WHERE N.type_id = 21 ' . $queryStringData . '
                GROUP BY N.department_id
            ) AS NC21 ON NC21.department_id = HPD.id
            WHERE HPD.is_active = 1 AND HPD.id IN(3,4,5,6)');

        $centerTypeCount21 = $centerRowSumTypeCount = 0;
        $typeCount21 = $rowSumTypeCount = 0;

        if ($query->num_rows() > 0) {

            $i = 1;
            $htmlData .= '<h6>Нийслэл</h6>';
            $htmlData .= '<div class="table-responsive">';
            $htmlData .= '<table class="table _report">';
            $htmlData .= '<thead>';
            $htmlData .= '<tr>';
            $htmlData .= '<th style="width:30px;">#</th>';
            $htmlData .= '<th>Гарчиг</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Эдийн засаг</th>';
            $htmlData .= '<th style="width:100px;" class="text-center">Нийт</th>';
            $htmlData .= '</tr>';

            $htmlData .= '</thead>';

            $htmlData .= '<tbody>';

            foreach ($query->result() as $key => $row) {

                $rowTypeCount = $row->type_count_21;

                $htmlData .= '<tr>';
                $htmlData .= '<td>' . $i . '</td>';
                $htmlData .= '<td>' . $row->title . '</td>';
                $htmlData .= '<td class="text-center">' . $row->type_count_21 . '</td>';
                $htmlData .= '<td class="text-center">' . $rowTypeCount . '</td>';
                $htmlData .= '</tr>';

                $i++;

                $typeCount21 = $typeCount21 + $row->type_count_21;

                $rowSumTypeCount = $rowSumTypeCount + $rowTypeCount;
            }

            $htmlData .= '</tbody>';
            $htmlData .= '<tfoot>';
            $htmlData .= '<tr>';
            $htmlData .= '<td></td>';
            $htmlData .= '<td>Нийт дүн</td>';
            $htmlData .= '<td class="text-center">' . $typeCount21 . '</td>';
            $htmlData .= '<td class="text-center">' . $rowSumTypeCount . '</td>';
            $htmlData .= '</tr>';
            $htmlData .= '</tfoot>';
            $htmlData .= '</table>';
            $htmlData .= '</div>';

            $centerTypeCount21 = $typeCount21;
            $centerRowSumTypeCount = $rowSumTypeCount;
        }

        $htmlData .= '<h6>Улсын нэгдсэн дүн</h6>';
        $htmlData .= '<div class="table-responsive">';
        $htmlData .= '<table class="table _report">';
        $htmlData .= '<thead>';
        $htmlData .= '<tr>';
        $htmlData .= '<th style="width:30px;">#</th>';
        $htmlData .= '<th>Гарчиг</th>';
        $htmlData .= '<th style="width:100px;" class="text-center">Эдийн засаг</th>';
        $htmlData .= '<th style="width:100px;" class="text-center">Нийт</th>';
        $htmlData .= '</tr>';

        $htmlData .= '</thead>';

        $htmlData .= '<tbody>';

        $htmlData .= '<tr>';
        $htmlData .= '<td>1</td>';
        $htmlData .= '<td>Орон нутаг</td>';
        $htmlData .= '<td class="text-center">' . $provinceTypeCount21 . '</td>';
        $htmlData .= '<td class="text-center">' . $provinceRowSumTypeCount . '</td>';
        $htmlData .= '</tr>';

        $htmlData .= '<tr>';
        $htmlData .= '<td>2</td>';
        $htmlData .= '<td>Нийслэл</td>';
        $htmlData .= '<td class="text-center">' . $cityTypeCount21 . '</td>';
        $htmlData .= '<td class="text-center">' . $cityRowSumTypeCount . '</td>';
        $htmlData .= '</tr>';

        $htmlData .= '<tr>';
        $htmlData .= '<td>3</td>';
        $htmlData .= '<td>Хүрээлэн</td>';
        $htmlData .= '<td class="text-center">' . $centerTypeCount21 . '</td>';
        $htmlData .= '<td class="text-center">' . $centerRowSumTypeCount . '</td>';
        $htmlData .= '</tr>';

        $htmlData .= '</tbody>';
        $htmlData .= '<tfoot>';
        $htmlData .= '<tr>';
        $htmlData .= '<td></td>';
        $htmlData .= '<td>Нийт дүн:</td>';
        $htmlData .= '<td class="text-center">' . ($provinceTypeCount21 + $cityTypeCount21 + $centerTypeCount21) . '</td>';
        $htmlData .= '<td class="text-center">' . ($provinceRowSumTypeCount + $cityRowSumTypeCount + $centerRowSumTypeCount) . '</td>';
        $htmlData .= '</tr>';
        $htmlData .= '</tfoot>';
        $htmlData .= '</table>';
        $htmlData .= '</div>';

        return $htmlData;
    }

    public function getReportForensicMedicineDateIntervalData_model($param = array()) {

        $htmlData = $queryStringData = $queryStringYearData = $queryStringInDateData = $queryStringObjectData = $queryStringMotiveData = $isAjaxDepartmentUrl = '';

        if ($param['departmentId'] > 0) {

            $queryStringData .= ' AND NC.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $queryStringYearData .= ' AND NC.department_id IN(' . $this->hrPeopleDepartment->getChildHrPeopleDepartment_model($param['departmentId']) . ')';
            $isAjaxDepartmentUrl .= '&departmentId=' . $param['departmentId'];
        }

        if ($param['inDate'] != '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NC.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(NC.in_date)';
        } else if ($param['inDate'] != '' and $param['outDate'] == '') {

            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(NC.in_date)';
        } else if ($param['inDate'] == '' and $param['outDate'] != '') {

            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(NC.in_date)';
        }

        if ($param['inDate'] == '' and $param['outDate'] == '') {

            $queryStringData .= ' AND NC.year = \'' . $this->session->adminCloseYear . '\'';
        }

        $col1 = $col2 = $col3 = $col4 = $col5 = $col6 = $col7 = $col8 = 0;

        $htmlData .= '<div class="table-responsive">';
        $htmlData .= '<table class="table _report">';
        $htmlData .= '<thead>';

        $htmlData .= '<tr>';
        $htmlData .= '<th rowspan="3" style="width:30px;">#</th>';
        $htmlData .= '<th rowspan="3">Шинжилгээний төрөл</th>';
        $htmlData .= '<th colspan="2" class="text-center">' . $this->session->adminCloseYear . ' он</th>';
        $htmlData .= '<th colspan="7" style="width:180px;" class="text-center">' . date('Y.m.d', strtotime($param['inDate'])) . ' - ' . date('Y.m.d', strtotime($param['outDate'])) . '</th>';
        $htmlData .= '</tr>';

        $htmlData .= '<tr>';
        $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Нийт шинжилгээ</th>';
        $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Объект</th>';

        $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Ирсэн шинжилгээ</th>';
        $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Татгалзсан</th>';
        $htmlData .= '<th rowspan="2" style="width:80px;" class="text-center">Объект</th>';

        $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Хэвийн шинжилгээ</th>';
        $htmlData .= '<th colspan="2" style="width:160px;" class="text-center">Хугацаа хэтэрсэн</th>';

        $htmlData .= '</tr>';

        $htmlData .= '<tr>';

        $htmlData .= '<th style="width:80px;" class="text-center">Хаагдсан</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">Хийж байгаа</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">Хаагдсан</th>';
        $htmlData .= '<th style="width:80px;" class="text-center">Хийж байгаа</th>';


        $htmlData .= '</tr>';

        $htmlData .= '</thead>';

        $htmlData .= '<tbody>';

        $fileFolderYearAllCount = $fileFolderYearObjectCount = $fileFolderDateAllCount = $fileFolderDateReturnCount = $fileFolderDateObjectCount = $fileFolderDateNormalCloseCount = $fileFolderDateNormalHandCount = $fileFolderDateCrashCloseCount = $fileFolderDateCrashHandCount = 0;

        /* Хавтаст хэргийн тоо жилийн эхнээс мөн объектын хамт харуулна */
        $queryFileFolderYear = $this->db->query('
                SELECT
                    COUNT(NC.id) AS year_count,
                    SUM(NC.object_count) AS year_object
                FROM `gaz_nifs_file_folder` AS NC
                WHERE NC.year = \'' . $this->session->adminCloseYear . '\'' . $queryStringYearData);
        if ($queryFileFolderYear->num_rows() > 0) {
            $row = $queryFileFolderYear->row();
            $fileFolderYearAllCount = $row->year_count;
            $fileFolderYearObjectCount = $row->year_object;
        }

        /* Хавтаст хэргийн тоо хугацааны интербалаар мөн объектын хамт харуулна */
        $queryFileFolderDate = $this->db->query('
                SELECT
                    COUNT(NC.id) AS date_count,
                    SUM(NC.object_count) AS date_object
                FROM `gaz_nifs_file_folder` AS NC
                WHERE 1 = 1 ' . $queryStringData);
        if ($queryFileFolderDate->num_rows() > 0) {
            $row = $queryFileFolderDate->row();
            $fileFolderDateAllCount = $row->date_count;
            $fileFolderDateObjectCount = $row->date_object;
        }

        /* Буцаасан хавтаст хэрэг */
        $queryFileFolderDateReturn = $this->db->query('
                SELECT
                    COUNT(NC.id) AS return_count
                FROM `gaz_nifs_file_folder` AS NC
                WHERE NC.solution_id = 47 ' . $queryStringData);
        if ($queryFileFolderDateReturn->num_rows() > 0) {
            $row = $queryFileFolderDateReturn->row();
            $fileFolderDateReturnCount = $row->return_count;
        }

        /* Хэвийн хаагдсан шинжилгээ - хавтаст хэрэг */
        $queryFileFolderNormalClose = $this->db->query('
                SELECT
                    COUNT(NC.id) AS normal_count_hand
                FROM `gaz_nifs_file_folder` AS NC
                WHERE NC.solution_id != 0 AND NC.close_type_id != 0 AND DATE(NC.close_date) <= DATE(NC.out_date)' . $queryStringData);
        if ($queryFileFolderNormalClose->num_rows() > 0) {
            $row = $queryFileFolderNormalClose->row();
            $fileFolderDateNormalCloseCount = $row->normal_count_hand;
        }

        /* Хэвийн хийгдэж байгаа шинжилгээ - хавтаст хэрэг */
        $queryFileFolderNormalHandCount = $this->db->query('
                SELECT
                    COUNT(NC.id) AS normal_count_hand
                FROM `gaz_nifs_file_folder` AS NC
                WHERE NC.solution_id = 0 AND NC.close_type_id = 0 AND CURDATE() <= DATE(NC.out_date)' . $queryStringData);
        if ($queryFileFolderNormalHandCount->num_rows() > 0) {
            $row = $queryFileFolderNormalHandCount->row();
            $fileFolderDateNormalHandCount = $row->normal_count_hand;
        }

        /* Хугацаа хэтэрч хаагдсан шинжилгээ */
        $queryFileFolderCrashCloseCount = $this->db->query('
                SELECT
                    COUNT(NC.id) AS crash_count_hand
                FROM `gaz_nifs_file_folder` AS NC
                WHERE NC.solution_id != 0 AND NC.close_type_id != 0 AND DATE(NC.close_date) > DATE(NC.out_date)' . $queryStringData);
        if ($queryFileFolderCrashCloseCount->num_rows() > 0) {
            $row = $queryFileFolderCrashCloseCount->row();
            $fileFolderDateCrashCloseCount = $row->crash_count_hand;
        }

        /* Хугацаа хэтэрсэн хаагдаагүй шинжилгээ */
        $queryFileFolderCrashHandCount = $this->db->query('
                SELECT
                    COUNT(NC.id) AS crash_count_hand
                FROM `gaz_nifs_file_folder` AS NC
                WHERE NC.solution_id = 0 AND NC.close_type_id = 0 AND  DATE(\'' . $param['outDate'] . '\') >= DATE(NC.out_date)' . $queryStringData);
        if ($queryFileFolderCrashHandCount->num_rows() > 0) {
            $row = $queryFileFolderCrashHandCount->row();
            $fileFolderDateCrashHandCount = $row->crash_count_hand;
        }

        $htmlData .= '<tr>';
        $htmlData .= '<td>1</td>';
        $htmlData .= '<td>Хавтаст хэрэг</td>';
        $htmlData .= '<td class="text-center ' . ($fileFolderYearAllCount > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&departmentId=' . $param['departmentId'] . '\'});">' . ($fileFolderYearAllCount > 0 ? $fileFolderYearAllCount : '') . '</a></td>';
        $htmlData .= '<td class="text-center">' . ($fileFolderYearObjectCount > 0 ? $fileFolderYearObjectCount : '') . '</td>';
        $htmlData .= '<td class="text-center ' . ($fileFolderDateAllCount > 0 ? '_row-more' : '') . '""><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&departmentId=' . $param['departmentId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($fileFolderDateAllCount > 0 ? $fileFolderDateAllCount : '') . '</a></td>';
        $htmlData .= '<td class="text-center ' . ($fileFolderDateReturnCount > 0 ? '_row-more' : '') . '""><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&departmentId=' . $param['departmentId'] . '&solutionId=47&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($fileFolderDateReturnCount > 0 ? $fileFolderDateReturnCount : '') . '</a></td>';
        $htmlData .= '<td class="text-center">' . ($fileFolderDateObjectCount > 0 ? $fileFolderDateObjectCount : '') . '</td>';
        $htmlData .= '<td class="text-center ' . ($fileFolderDateNormalCloseCount > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=1&departmentId=' . $param['departmentId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($fileFolderDateNormalCloseCount > 0 ? $fileFolderDateNormalCloseCount : '') . '</a></td>';
        $htmlData .= '<td class="text-center ' . ($fileFolderDateNormalHandCount > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsFileFolder({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=2&departmentId=' . $param['departmentId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($fileFolderDateNormalHandCount > 0 ? $fileFolderDateNormalHandCount : '') . '</a></td>';
        $htmlData .= '<td class="text-center ' . ($fileFolderDateCrashCloseCount > 0 ? '_row-more' : '') . '"><a href="javascript:;" class="_crime-crash-done" onclick="_initNifsFileFolder({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=3&departmentId=' . $param['departmentId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($fileFolderDateCrashCloseCount > 0 ? $fileFolderDateCrashCloseCount : '') . '</a></td>';
        $htmlData .= '<td class="text-center ' . ($fileFolderDateCrashHandCount > 0 ? '_row-more' : '') . '"><a href="javascript:;" class="_crime-crash-hand" onclick="_initNifsFileFolder({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=4&departmentId=' . $param['departmentId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($fileFolderDateCrashHandCount > 0 ? $fileFolderDateCrashHandCount : '') . '</a></td>';
        $htmlData .= '</tr>';

        $anatomyYearAllCount = $anatomyYearObjectCount = $anatomyDateAllCount = $anatomyDateReturnCount = $anatomyDateObjectCount = $anatomyDateNormalCloseCount = $anatomyDateNormalHandCount = $anatomyDateCrashCloseCount = $anatomyDateCrashHandCount = 0;

        /* Задлан шинжилгээний тоо жилийн эхнээс мөн объектын хамт харуулна */
        $queryAnatomyYear = $this->db->query('
                SELECT
                    COUNT(NC.id) AS year_count
                FROM `gaz_nifs_anatomy` AS NC
                WHERE NC.year = \'' . $this->session->adminCloseYear . '\'' . $queryStringYearData);
        if ($queryAnatomyYear->num_rows() > 0) {
            $row = $queryAnatomyYear->row();
            $anatomyYearAllCount = $row->year_count;
        }

        /* Задлан шинжилгээний тоо хугацааны интербалаар мөн объектын хамт харуулна */
        $queryAnatomyDate = $this->db->query('
                SELECT
                    COUNT(NC.id) AS date_count
                FROM `gaz_nifs_anatomy` AS NC
                WHERE 1 = 1 ' . $queryStringData);
        if ($queryAnatomyDate->num_rows() > 0) {
            $row = $queryAnatomyDate->row();
            $anatomyDateAllCount = $row->date_count;
        }

        /* Хэвийн хаагдсан шинжилгээ - хавтаст хэрэг */
        $queryAnatomyNormalClose = $this->db->query('
                SELECT
                    COUNT(NC.id) AS normal_count_hand
                FROM `gaz_nifs_anatomy` AS NC
                WHERE NC.solution_id != 0 AND DATE(NC.close_date) <= DATE(NC.out_date)' . $queryStringData);
        if ($queryAnatomyNormalClose->num_rows() > 0) {
            $row = $queryAnatomyNormalClose->row();
            $anatomyDateNormalCloseCount = $row->normal_count_hand;
        }

        /* Хэвийн хийгдэж байгаа шинжилгээ - хавтаст хэрэг */
        $queryAnatomyNormalHandCount = $this->db->query('
                SELECT
                    COUNT(NC.id) AS normal_count_hand
                FROM `gaz_nifs_anatomy` AS NC
                WHERE NC.solution_id = 0 AND CURDATE() <= DATE(NC.out_date)' . $queryStringData);
        if ($queryAnatomyNormalHandCount->num_rows() > 0) {
            $row = $queryAnatomyNormalHandCount->row();
            $anatomyDateNormalHandCount = $row->normal_count_hand;
        }

        /* Хугацаа хэтэрч хаагдсан шинжилгээ */
        $queryAnatomyCrashCloseCount = $this->db->query('
                SELECT
                    COUNT(NC.id) AS crash_count_hand
                FROM `gaz_nifs_anatomy` AS NC
                WHERE NC.solution_id != 0 AND DATE(NC.end_date) > DATE(NC.out_date)' . $queryStringData);
        if ($queryAnatomyCrashCloseCount->num_rows() > 0) {
            $row = $queryAnatomyCrashCloseCount->row();
            $anatomyDateCrashCloseCount = $row->crash_count_hand;
        }

        /* Хугацаа хэтэрсэн хаагдаагүй шинжилгээ */
        $queryAnatomyCrashHandCount = $this->db->query('
                SELECT
                    COUNT(NC.id) AS crash_count_hand
                FROM `gaz_nifs_anatomy` AS NC
                WHERE NC.solution_id = 0 AND DATE(\'' . $param['outDate'] . '\') >= DATE(NC.out_date)' . $queryStringData);
        if ($queryAnatomyCrashHandCount->num_rows() > 0) {
            $row = $queryAnatomyCrashHandCount->row();
            $anatomyDateCrashHandCount = $row->crash_count_hand;
        }

        $htmlData .= '<tr>';
        $htmlData .= '<td>2</td>';
        $htmlData .= '<td>Задлан шинжилгээ</td>';
        $htmlData .= '<td class="text-center ' . ($anatomyYearAllCount > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&departmentId=' . $param['departmentId'] . '\'});">' . ($anatomyYearAllCount > 0 ? $anatomyYearAllCount : '') . '</a></td>';
        $htmlData .= '<td class="text-center">' . ($anatomyYearObjectCount > 0 ? $anatomyYearObjectCount : '') . '</td>';
        $htmlData .= '<td class="text-center ' . ($anatomyDateAllCount > 0 ? '_row-more' : '') . '""><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&departmentId=' . $param['departmentId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($anatomyDateAllCount > 0 ? $anatomyDateAllCount : '') . '</a></td>';
        $htmlData .= '<td class="text-center ' . ($anatomyDateReturnCount > 0 ? '_row-more' : '') . '""><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&departmentId=' . $param['departmentId'] . '&solutionId=47&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($anatomyDateReturnCount > 0 ? $anatomyDateReturnCount : '') . '</a></td>';
        $htmlData .= '<td class="text-center">' . ($anatomyDateObjectCount > 0 ? $anatomyDateObjectCount : '') . '</td>';
        $htmlData .= '<td class="text-center ' . ($anatomyDateNormalCloseCount > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=1&departmentId=' . $param['departmentId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($anatomyDateNormalCloseCount > 0 ? $anatomyDateNormalCloseCount : '') . '</a></td>';
        $htmlData .= '<td class="text-center ' . ($anatomyDateNormalHandCount > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsAnatomy({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=2&departmentId=' . $param['departmentId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($anatomyDateNormalHandCount > 0 ? $anatomyDateNormalHandCount : '') . '</a></td>';
        $htmlData .= '<td class="text-center ' . ($anatomyDateCrashCloseCount > 0 ? '_row-more' : '') . '"><a href="javascript:;" class="_crime-crash-done" onclick="_initNifsAnatomy({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=3&departmentId=' . $param['departmentId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($anatomyDateCrashCloseCount > 0 ? $anatomyDateCrashCloseCount : '') . '</a></td>';
        $htmlData .= '<td class="text-center ' . ($anatomyDateCrashHandCount > 0 ? '_row-more' : '') . '"><a href="javascript:;" class="_crime-crash-hand" onclick="_initNifsAnatomy({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=4&departmentId=' . $param['departmentId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($anatomyDateCrashHandCount > 0 ? $anatomyDateCrashHandCount : '') . '</a></td>';
        $htmlData .= '</tr>';


        $doctorViewYearAllCount = $doctorViewYearObjectCount = $doctorViewDateAllCount = $doctorViewDateReturnCount = $doctorViewDateObjectCount = $doctorViewDateNormalCloseCount = $doctorViewDateNormalHandCount = $doctorViewDateCrashCloseCount = $doctorViewDateCrashHandCount = 0;

        /* Эмчийн үзлэгийн тоо жилийн эхнээс харуулна */
        $queryDoctorViewYear = $this->db->query('
                SELECT
                    COUNT(NC.id) AS year_count
                FROM `gaz_nifs_doctor_view` AS NC
                WHERE NC.year = \'' . $this->session->adminCloseYear . '\'' . $queryStringYearData);
        if ($queryDoctorViewYear->num_rows() > 0) {
            $row = $queryDoctorViewYear->row();
            $doctorViewYearAllCount = $row->year_count;
        }

        /* Эмчийн үзлэгийн тоо хугацааны интербалаар харуулна */
        $queryDoctorViewDate = $this->db->query('
                SELECT
                    COUNT(NC.id) AS date_count
                FROM `gaz_nifs_doctor_view` AS NC
                WHERE 1 = 1 ' . $queryStringData);
        if ($queryDoctorViewDate->num_rows() > 0) {
            $row = $queryDoctorViewDate->row();
            $doctorViewDateAllCount = $row->date_count;
        }

        /* Буцаасан эмчийн үзлэг */
        $queryDoctorViewDateReturn = $this->db->query('
                SELECT
                    COUNT(NC.id) AS return_count
                FROM `gaz_nifs_doctor_view` AS NC
                WHERE NC.close_type_id = 17 ' . $queryStringData);
        if ($queryDoctorViewDateReturn->num_rows() > 0) {
            $row = $queryDoctorViewDateReturn->row();
            $doctorViewDateReturnCount = $row->return_count;
        }

        /* Хэвийн хаагдсан шинжилгээ - эмчийн үзлэг */
        $queryDoctorViewNormalClose = $this->db->query('
                SELECT
                    COUNT(NC.id) AS normal_count_hand
                FROM `gaz_nifs_doctor_view` AS NC
                WHERE NC.close_type_id != 0 AND DATE(NC.close_date) <= DATE(NC.out_date)' . $queryStringData);
        if ($queryDoctorViewNormalClose->num_rows() > 0) {
            $row = $queryDoctorViewNormalClose->row();
            $doctorViewDateNormalCloseCount = $row->normal_count_hand;
        }

        /* Хэвийн хийгдэж байгаа шинжилгээ - хавтаст хэрэг */
        $queryDoctorViewNormalHandCount = $this->db->query('
                SELECT
                    COUNT(NC.id) AS normal_count_hand
                FROM `gaz_nifs_doctor_view` AS NC
                WHERE NC.close_type_id = 0 AND NC.close_type_id = 0 AND CURDATE() <= DATE(NC.out_date)' . $queryStringData);
        if ($queryDoctorViewNormalHandCount->num_rows() > 0) {
            $row = $queryDoctorViewNormalHandCount->row();
            $doctorViewDateNormalHandCount = $row->normal_count_hand;
        }

        /* Хугацаа хэтэрч хаагдсан шинжилгээ */
        $queryDoctorViewCrashCloseCount = $this->db->query('
                SELECT
                    COUNT(NC.id) AS crash_count_hand
                FROM `gaz_nifs_doctor_view` AS NC
                WHERE NC.close_type_id != 0 AND NC.close_type_id != 0 AND DATE(NC.close_date) > DATE(NC.out_date)' . $queryStringData);
        if ($queryDoctorViewCrashCloseCount->num_rows() > 0) {
            $row = $queryDoctorViewCrashCloseCount->row();
            $doctorViewDateCrashCloseCount = $row->crash_count_hand;
        }

        /* Хугацаа хэтэрсэн хаагдаагүй шинжилгээ */
        $queryDoctorViewCrashHandCount = $this->db->query('
                SELECT
                    COUNT(NC.id) AS crash_count_hand
                FROM `gaz_nifs_doctor_view` AS NC
                WHERE NC.close_type_id = 0 AND DATE(\'' . $param['outDate'] . '\') >= DATE(NC.out_date)' . $queryStringData);
        if ($queryDoctorViewCrashHandCount->num_rows() > 0) {
            $row = $queryDoctorViewCrashHandCount->row();
            $doctorViewDateCrashHandCount = $row->crash_count_hand;
        }

        $htmlData .= '<tr>';
        $htmlData .= '<td>3</td>';
        $htmlData .= '<td>Эмчийн үзлэг</td>';
        $htmlData .= '<td class="text-center ' . ($doctorViewYearAllCount > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&departmentId=' . $param['departmentId'] . '\'});">' . ($doctorViewYearAllCount > 0 ? $doctorViewYearAllCount : '') . '</a></td>';
        $htmlData .= '<td class="text-center">' . ($doctorViewYearObjectCount > 0 ? $doctorViewYearObjectCount : '') . '</td>';
        $htmlData .= '<td class="text-center ' . ($doctorViewDateAllCount > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&departmentId=' . $param['departmentId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($doctorViewDateAllCount > 0 ? $doctorViewDateAllCount : '') . '</a></td>';
        $htmlData .= '<td class="text-center ' . ($doctorViewDateReturnCount > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&departmentId=' . $param['departmentId'] . '&solutionId=47&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($doctorViewDateReturnCount > 0 ? $doctorViewDateReturnCount : '') . '</a></td>';
        $htmlData .= '<td class="text-center">' . ($doctorViewDateObjectCount > 0 ? $doctorViewDateObjectCount : '') . '</td>';
        $htmlData .= '<td class="text-center ' . ($doctorViewDateNormalCloseCount > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=1&departmentId=' . $param['departmentId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($doctorViewDateNormalCloseCount > 0 ? $doctorViewDateNormalCloseCount : '') . '</a></td>';
        $htmlData .= '<td class="text-center ' . ($doctorViewDateNormalHandCount > 0 ? '_row-more' : '') . '"><a href="javascript:;" onclick="_initNifsDoctorView({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=2&departmentId=' . $param['departmentId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($doctorViewDateNormalHandCount > 0 ? $doctorViewDateNormalHandCount : '') . '</a></td>';
        $htmlData .= '<td class="text-center ' . ($doctorViewDateCrashCloseCount > 0 ? '_row-more' : '') . '"><a href="javascript:;" class="_crime-crash-done" onclick="_initNifsDoctorView({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=3&departmentId=' . $param['departmentId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($doctorViewDateCrashCloseCount > 0 ? $doctorViewDateCrashCloseCount : '') . '</a></td>';
        $htmlData .= '<td class="text-center ' . ($doctorViewDateCrashHandCount > 0 ? '_row-more' : '') . '"><a href="javascript:;" class="_crime-crash-hand" onclick="_initNifsDoctorView({page: 0, searchQuery: \'isReportMenu=1&reportModId=' . $param['reportModId'] . '&reportMenuId=' . $param['reportMenuId'] . '&statusId=4&departmentId=' . $param['departmentId'] . '&inDate=' . $param['inDate'] . '&outDate=' . $param['outDate'] . '\'});">' . ($doctorViewDateCrashHandCount > 0 ? $doctorViewDateCrashHandCount : '') . '</a></td>';
        $htmlData .= '</tr>';

        $totalYearAllCount = $fileFolderYearAllCount + $anatomyYearAllCount + $doctorViewYearAllCount;
        $totalYearObjectCount = $fileFolderYearObjectCount + $anatomyYearObjectCount + $doctorViewYearObjectCount;
        $totalDateAllCount = $fileFolderDateAllCount + $anatomyDateAllCount + $doctorViewDateAllCount;
        $totalDateReturnCount = $fileFolderDateReturnCount + $anatomyDateReturnCount + $doctorViewDateReturnCount;
        $totalDateObjectCount = $fileFolderDateObjectCount + $anatomyDateObjectCount + $doctorViewDateObjectCount;
        $totalDateNormalCloseCount = $fileFolderDateNormalCloseCount + $anatomyDateNormalCloseCount + $doctorViewDateNormalCloseCount;
        $totalDateNormalHandCount = $fileFolderDateNormalHandCount + $anatomyDateNormalHandCount + $doctorViewDateNormalHandCount;
        $totalDateCrashCloseCount = $fileFolderDateCrashCloseCount + $anatomyDateCrashCloseCount + $doctorViewDateCrashCloseCount;
        $totalDateCrashHandCount = $fileFolderDateCrashHandCount + $anatomyDateCrashHandCount + $doctorViewDateCrashHandCount;

        $htmlData .= '</tbody>';
        $htmlData .= '<tfoot>';
        $htmlData .= '<tr>';
        $htmlData .= '<td colspan="2" class="text-right _custom-foot">Нийт</td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($totalYearAllCount > 0 ? $totalYearAllCount : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($totalYearObjectCount > 0 ? $totalYearObjectCount : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($totalDateAllCount > 0 ? $totalDateAllCount : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($totalDateReturnCount > 0 ? $totalDateReturnCount : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($totalDateObjectCount > 0 ? $totalDateObjectCount : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($totalDateNormalCloseCount > 0 ? $totalDateNormalCloseCount : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot">' . ($totalDateNormalHandCount > 0 ? $totalDateNormalHandCount : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot _crime-crash-done">' . ($totalDateCrashCloseCount > 0 ? $totalDateCrashCloseCount : '') . '</td>';
        $htmlData .= '<td class="text-center _custom-foot _crime-crash-hand">' . ($totalDateCrashHandCount > 0 ? $totalDateCrashHandCount : '') . '</td>';
        $htmlData .= '</tr>';
        $htmlData .= '</tfoot>';
        $htmlData .= '</table>';

        return $htmlData;
    }

    public function updateAllData_model($param = array()) {


        $queryStringData = $htmlData = '';

//        if ($param['inDate'] != '' and $param['outDate'] != '') {
//
//            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date) AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.in_date)';
//        } else if ($param['inDate'] != '' and $param['outDate'] == '') {
//
//            $queryStringData .= ' AND DATE(\'' . $param['inDate'] . '\') <= DATE(N.in_date)';
//        } else if ($param['inDate'] == '' and $param['outDate'] != '') {
//
//            $queryStringData .= ' AND DATE(\'' . $param['outDate'] . '\') >= DATE(N.out_date)';
//        }

        $query = $this->db->query('
            SELECT 
                HPD.id,
                (CASE
                    WHEN CRIME_CAT_280.crime_cat_280 > 0 THEN CRIME_CAT_280.crime_cat_280_in_date
                    WHEN CRIME_CAT_281.crime_cat_281 > 0 THEN CRIME_CAT_281.crime_cat_281_in_date
                    WHEN CRIME_CAT_282.crime_cat_282 > 0 THEN CRIME_CAT_282.crime_cat_282_in_date
                    WHEN CRIME_CAT_283.crime_cat_283 > 0 THEN CRIME_CAT_283.crime_cat_283_in_date
                    WHEN CRIME_CAT_284.crime_cat_284 > 0 THEN CRIME_CAT_284.crime_cat_284_in_date
                    WHEN CRIME_CAT_285.crime_cat_285 > 0 THEN CRIME_CAT_285.crime_cat_285_in_date
                    WHEN CRIME_CAT_286.crime_cat_286 > 0 THEN CRIME_CAT_286.crime_cat_286_in_date
                    WHEN CRIME_CAT_342.crime_cat_342 > 0 THEN CRIME_CAT_342.crime_cat_342_in_date
                    WHEN CRIME_CAT_343.crime_cat_343 > 0 THEN CRIME_CAT_343.crime_cat_343_in_date
                    WHEN CRIME_CAT_344.crime_cat_344 > 0 THEN CRIME_CAT_344.crime_cat_344_in_date
                    WHEN CRIME_CAT_345.crime_cat_345 > 0 THEN CRIME_CAT_345.crime_cat_345_in_date
                    WHEN CRIME_CAT_346.crime_cat_346 > 0 THEN CRIME_CAT_346.crime_cat_346_in_date
                    WHEN CRIME_CAT_437.crime_cat_437 > 0 THEN CRIME_CAT_437.crime_cat_437_in_date
                    WHEN DOCTOR_VIEW.doctor_view > 0 THEN DOCTOR_VIEW.doctor_view_in_date
                    WHEN FILE_FOLDER.file_folder > 0 THEN FILE_FOLDER.file_folder_in_date
                    WHEN ANATOMY.anatomy > 0 THEN ANATOMY.anatomy_in_date
                    WHEN EXTRA_TYPE_8.extra_type_8 > 0 THEN EXTRA_TYPE_8.extra_type_8_in_date
                    WHEN EXTRA_TYPE_9.extra_type_9 > 0 THEN EXTRA_TYPE_9.extra_type_9_in_date
                    WHEN EXTRA_TYPE_10.extra_type_10 > 0 THEN EXTRA_TYPE_10.extra_type_10_in_date
                    WHEN EXTRA_TYPE_11.extra_type_11 > 0 THEN EXTRA_TYPE_11.extra_type_11_in_date
                    WHEN EXTRA_TYPE_12.extra_type_12 > 0 THEN EXTRA_TYPE_12.extra_type_12_in_date
                    WHEN EXTRA_TYPE_13.extra_type_13 > 0 THEN EXTRA_TYPE_13.extra_type_13_in_date
                    WHEN ECONOMY_TYPE_21.economy_type_21 > 0 THEN ECONOMY_TYPE_21.economy_type_21_in_date
                    WHEN SCENE_TYPE_1.scene_type_1 > 0 THEN SCENE_TYPE_1.scene_type_1_in_date
                    WHEN SCENE_TYPE_2.scene_type_2 > 0 THEN SCENE_TYPE_2.scene_type_2_in_date
                    WHEN SCENE_TYPE_3.scene_type_3 > 0 THEN SCENE_TYPE_3.scene_type_3_in_date
                    WHEN SCENE_TYPE_4.scene_type_4 > 0 THEN SCENE_TYPE_4.scene_type_4_in_date
                    WHEN SCENE_TYPE_5.scene_type_5 > 0 THEN SCENE_TYPE_5.scene_type_5_in_date
                    WHEN SCENE_TYPE_6.scene_type_6 > 0 THEN SCENE_TYPE_6.scene_type_6_in_date
                    WHEN SCENE_TYPE_7.scene_type_7 > 0 THEN SCENE_TYPE_7.scene_type_7_in_date
                    WHEN SEND_DOC_TYPE_8.send_document_type_8 > 0 THEN SEND_DOC_TYPE_8.send_document_type_8_in_date
                    WHEN SEND_DOC_TYPE_10.send_document_type_10 > 0 THEN SEND_DOC_TYPE_10.send_document_type_10_in_date
                    WHEN SEND_DOC_TYPE_11.send_document_type_11 > 0 THEN SEND_DOC_TYPE_11.send_document_type_11_in_date
                END) AS in_date,
                CRIME_CAT_280.crime_cat_280,
                CRIME_CAT_281.crime_cat_281,
                CRIME_CAT_282.crime_cat_282,
                CRIME_CAT_283.crime_cat_283,
                CRIME_CAT_284.crime_cat_284,
                CRIME_CAT_285.crime_cat_285,
                CRIME_CAT_286.crime_cat_286,
                CRIME_CAT_342.crime_cat_342,
                CRIME_CAT_343.crime_cat_343,
                CRIME_CAT_344.crime_cat_344,
                CRIME_CAT_345.crime_cat_345,
                CRIME_CAT_346.crime_cat_346,
                CRIME_CAT_347.crime_cat_347,
                CRIME_CAT_348.crime_cat_348,
                CRIME_CAT_437.crime_cat_437,
                DOCTOR_VIEW.doctor_view,
                FILE_FOLDER.file_folder,
                ANATOMY.anatomy,
                EXTRA_TYPE_8.extra_type_8,
                EXTRA_TYPE_9.extra_type_9,
                EXTRA_TYPE_10.extra_type_10,
                EXTRA_TYPE_11.extra_type_11,
                EXTRA_TYPE_12.extra_type_12,
                EXTRA_TYPE_13.extra_type_13,
                ECONOMY_TYPE_21.economy_type_21,
                SCENE_TYPE_1.scene_type_1,
                SCENE_TYPE_2.scene_type_2,
                SCENE_TYPE_3.scene_type_3,
                SCENE_TYPE_4.scene_type_4,
                SCENE_TYPE_5.scene_type_5,
                SCENE_TYPE_6.scene_type_6,
                SCENE_TYPE_7.scene_type_7,
                SEND_DOC_TYPE_8.send_document_type_8,
                SEND_DOC_TYPE_10.send_document_type_10,
                SEND_DOC_TYPE_11.send_document_type_11
                
            FROM `gaz_hr_people_department` AS HPD
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS crime_cat_280_in_date,
                    COUNT(N.cat_id) AS crime_cat_280
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 280 
                GROUP BY N.department_id
            ) AS CRIME_CAT_280 ON CRIME_CAT_280.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS crime_cat_281_in_date,
                    COUNT(N.cat_id) AS crime_cat_281
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 281 
                GROUP BY N.department_id
            ) AS CRIME_CAT_281 ON CRIME_CAT_281.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS crime_cat_282_in_date,
                    COUNT(N.cat_id) AS crime_cat_282
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 282 
                GROUP BY N.department_id
            ) AS CRIME_CAT_282 ON CRIME_CAT_282.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS crime_cat_283_in_date,
                    COUNT(N.cat_id) AS crime_cat_283
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 283 
                GROUP BY N.department_id
            ) AS CRIME_CAT_283 ON CRIME_CAT_283.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS crime_cat_284_in_date,
                    COUNT(N.cat_id) AS crime_cat_284
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 284 
                GROUP BY N.department_id
            ) AS CRIME_CAT_284 ON CRIME_CAT_284.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS crime_cat_285_in_date,
                    COUNT(N.cat_id) AS crime_cat_285
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 285 
                GROUP BY N.department_id
            ) AS CRIME_CAT_285 ON CRIME_CAT_285.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS crime_cat_286_in_date,
                    COUNT(N.cat_id) AS crime_cat_286
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 286 
                GROUP BY N.department_id
            ) AS CRIME_CAT_286 ON CRIME_CAT_286.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS crime_cat_342_in_date,
                    COUNT(N.cat_id) AS crime_cat_342
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 342 
                GROUP BY N.department_id
            ) AS CRIME_CAT_342 ON CRIME_CAT_342.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS crime_cat_343_in_date,
                    COUNT(N.cat_id) AS crime_cat_343
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 343 
                GROUP BY N.department_id
            ) AS CRIME_CAT_343 ON CRIME_CAT_343.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS crime_cat_344_in_date,
                    COUNT(N.cat_id) AS crime_cat_344
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 344 
                GROUP BY N.department_id
            ) AS CRIME_CAT_344 ON CRIME_CAT_344.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS crime_cat_345_in_date,
                    COUNT(N.cat_id) AS crime_cat_345
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 345 
                GROUP BY N.department_id
            ) AS CRIME_CAT_345 ON CRIME_CAT_345.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS crime_cat_346_in_date,
                    COUNT(N.cat_id) AS crime_cat_346
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 346 
                GROUP BY N.department_id
            ) AS CRIME_CAT_346 ON CRIME_CAT_346.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS crime_cat_347_in_date,
                    COUNT(N.cat_id) AS crime_cat_347
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 347 
                GROUP BY N.department_id
            ) AS CRIME_CAT_347 ON CRIME_CAT_347.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS crime_cat_348_in_date,
                    COUNT(N.cat_id) AS crime_cat_348
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 348 
                GROUP BY N.department_id
            ) AS CRIME_CAT_348 ON CRIME_CAT_348.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS crime_cat_437_in_date,
                    COUNT(N.cat_id) AS crime_cat_437
                FROM `gaz_nifs_crime` AS N 
                WHERE N.cat_id = 437 
                GROUP BY N.department_id
            ) AS CRIME_CAT_437 ON CRIME_CAT_437.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS doctor_view_in_date,
                    COUNT(N.department_id) AS doctor_view
                FROM `gaz_nifs_doctor_view` AS N 
                WHERE 1 = 1 
                GROUP BY N.department_id
            ) AS DOCTOR_VIEW ON DOCTOR_VIEW.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS file_folder_in_date,
                    COUNT(N.department_id) AS file_folder
                FROM `gaz_nifs_file_folder` AS N 
                WHERE 1 = 1 
                GROUP BY N.department_id
            ) AS FILE_FOLDER ON FILE_FOLDER.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS anatomy_in_date,
                    COUNT(N.department_id) AS anatomy
                FROM `gaz_nifs_anatomy` AS N 
                WHERE 1 = 1 
                GROUP BY N.department_id
            ) AS ANATOMY ON ANATOMY.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS extra_type_8_in_date,
                    COUNT(N.type_id) AS extra_type_8
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 8
                GROUP BY N.department_id
            ) AS EXTRA_TYPE_8 ON EXTRA_TYPE_8.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS extra_type_9_in_date,
                    COUNT(N.type_id) AS extra_type_9
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 9
                GROUP BY N.department_id
            ) AS EXTRA_TYPE_9 ON EXTRA_TYPE_9.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS extra_type_10_in_date,
                    COUNT(N.type_id) AS extra_type_10
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 10
                GROUP BY N.department_id
            ) AS EXTRA_TYPE_10 ON EXTRA_TYPE_10.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS extra_type_11_in_date,
                    COUNT(N.type_id) AS extra_type_11
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 11
                GROUP BY N.department_id
            ) AS EXTRA_TYPE_11 ON EXTRA_TYPE_11.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS extra_type_12_in_date,
                    COUNT(N.type_id) AS extra_type_12
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 12
                GROUP BY N.department_id
            ) AS EXTRA_TYPE_12 ON EXTRA_TYPE_12.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS extra_type_13_in_date,
                    COUNT(N.type_id) AS extra_type_13
                FROM `gaz_nifs_extra` AS N
                WHERE N.type_id = 13
                GROUP BY N.department_id
            ) AS EXTRA_TYPE_13 ON EXTRA_TYPE_13.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS economy_type_21_in_date,
                    COUNT(N.type_id) AS economy_type_21
                FROM `gaz_nifs_economy` AS N
                WHERE N.type_id = 21
                GROUP BY N.department_id
            ) AS ECONOMY_TYPE_21 ON ECONOMY_TYPE_21.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS scene_type_1_in_date,
                    COUNT(N.scene_type_id) AS scene_type_1
                FROM `gaz_nifs_scene` AS N
                WHERE N.scene_type_id = 1
                GROUP BY N.department_id
            ) AS SCENE_TYPE_1 ON SCENE_TYPE_1.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS scene_type_2_in_date,
                    COUNT(N.scene_type_id) AS scene_type_2
                FROM `gaz_nifs_scene` AS N
                WHERE N.scene_type_id = 2
                GROUP BY N.department_id
            ) AS SCENE_TYPE_2 ON SCENE_TYPE_2.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS scene_type_3_in_date,
                    COUNT(N.scene_type_id) AS scene_type_3
                FROM `gaz_nifs_scene` AS N
                WHERE N.scene_type_id = 3
                GROUP BY N.department_id
            ) AS SCENE_TYPE_3 ON SCENE_TYPE_3.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS scene_type_4_in_date,
                    COUNT(N.scene_type_id) AS scene_type_4
                FROM `gaz_nifs_scene` AS N
                WHERE N.scene_type_id = 4
                GROUP BY N.department_id
            ) AS SCENE_TYPE_4 ON SCENE_TYPE_4.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS scene_type_5_in_date,
                    COUNT(N.scene_type_id) AS scene_type_5
                FROM `gaz_nifs_scene` AS N
                WHERE N.scene_type_id = 5
                GROUP BY N.department_id
            ) AS SCENE_TYPE_5 ON SCENE_TYPE_5.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS scene_type_6_in_date,
                    COUNT(N.scene_type_id) AS scene_type_6
                FROM `gaz_nifs_scene` AS N
                WHERE N.scene_type_id = 6
                GROUP BY N.department_id
            ) AS SCENE_TYPE_6 ON SCENE_TYPE_6.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS scene_type_7_in_date,
                    COUNT(N.scene_type_id) AS scene_type_7
                FROM `gaz_nifs_scene` AS N
                WHERE N.scene_type_id = 7
                GROUP BY N.department_id
            ) AS SCENE_TYPE_7 ON SCENE_TYPE_7.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS send_document_type_8_in_date,
                    COUNT(N.type_id) AS send_document_type_8
                FROM `gaz_nifs_send_doc` AS N
                WHERE N.type_id = 8
                GROUP BY N.department_id
            ) AS SEND_DOC_TYPE_8 ON SEND_DOC_TYPE_8.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS send_document_type_10_in_date,
                    COUNT(N.type_id) AS send_document_type_10
                FROM `gaz_nifs_send_doc` AS N
                WHERE N.type_id = 10
                GROUP BY N.department_id
            ) AS SEND_DOC_TYPE_10 ON SEND_DOC_TYPE_10.department_id = HPD.id
            LEFT JOIN (
                SELECT
                    N.department_id,
                    DATE(N.in_date) AS send_document_type_11_in_date,
                    COUNT(N.type_id) AS send_document_type_11
                FROM `gaz_nifs_send_doc` AS N
                WHERE N.type_id = 11
                GROUP BY N.department_id
            ) AS SEND_DOC_TYPE_11 ON SEND_DOC_TYPE_11.department_id = HPD.id
            WHERE 1 = 1');

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $row) {
                $data = array(
                    array(
                        'year' => date('Y', strtotime($row->in_date)),
                        'month' => date('m', strtotime($row->in_date)),
                        'day' => date('d', strtotime($row->in_date)),
                        'department_id' => $row->id)
                );
                $this->db->insert_batch($this->db->dbprefix . 'nifs_report_general', $data);
            }

            return array('status' => 'success', 'message' => 'Мэдээлэл шинэчлэгдсэн');
        }

        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function updateChartGeneralData_model($param = array()) {

        $this->db->where('department_id', $param['departmentId']);
        if ($this->db->delete($this->db->dbprefix . 'nifs_chart_general')) {

            $data = array(
                array(
                    'department_id' => $param['departmentId'],
                    'parent_id' => $param['parentId'],
                    'crime' => (intval($param['crime']) > 0 ? $param['crime'] : 0),
                    'forensic' => (intval($param['forensic']) > 0 ? $param['forensic'] : 0),
                    'extra' => (intval($param['extra']) > 0 ? $param['extra'] : 0),
                    'economy' => (intval($param['economy']) > 0 ? $param['economy'] : 0),
                    'send_document' => (intval($param['sendDocument']) > 0 ? $param['sendDocument'] : 0),
                    'scene' => (intval($param['scene']) > 0 ? $param['scene'] : 0),
                    'created_date' => date('Y-m-d H:i:s')));
            $this->db->insert_batch($this->db->dbprefix . 'nifs_chart_general', $data);
        }
    }

}
