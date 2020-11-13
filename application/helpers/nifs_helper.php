<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('getDepartmentId')) {

    function getDepartmentId($param = array('userDepartmentId' => 0, 'modId' => 0)) {

        $departmentId = $param['userDepartmentId'];

        switch ($param['modId']) {
            case 33: {
                    //Криминалистикийн шинжилгээний газар
                    if ($param['userDepartmentId'] == 7) {
                        $departmentId = 3;
                    }
                };
                break;
            case 34: {
                    //Криминалистикийн шинжилгээний газар
                    if ($param['userDepartmentId'] == 7) {
                        $departmentId = 3;
                    }
                };
                break;
            case 50: {
                    //Хавтаст хэрэг
                    if ($param['userDepartmentId'] == 7) {
                        //Шүүх эмнэлэгийн шинжилгээний газар
                        $departmentId = 4;
                    }
                };
                break;
            case 51: {
                    //Эмчийн үзлэг
                    if ($param['userDepartmentId'] == 7) {
                        //Шүүх эмнэлэгийн шинжилгээний газар
                        $departmentId = 4;
                    }
                };
                break;
            case 52: {
                    //Задлан шинжилгээ
                    if ($param['userDepartmentId'] == 7) {
                        //Шүүх эмнэлэгийн шинжилгээний газар
                        $departmentId = 4;
                    }
                };
                break;
            case 55: {
                    //Тусгай шинжилгээ
                    if ($param['userDepartmentId'] == 7) {
                        //Криминалистикийн шинжилгээний газар
                        $departmentId = 5;
                    }
                };
                break;
            case 56: {
                    //Эдийн засгийн шинжилгээ
                    if ($param['userDepartmentId'] == 7) {
                        //Эдийн засгийн шинжилгээний хэлтэс болгон хувиргаж байна.
                        $departmentId = 6;
                    }
                };
                break;
            case 81: {
                    //Илгээх бичиг
                    if ($param['userDepartmentId'] == 7) {
                        //Криминалистикийн шинжилгээний газар
                        $departmentId = 5;
                    }
                };
                break;
            default : {
                    $departmentId = $param['userDepartmentId'];
                };
                break;
        }



        return $departmentId;
    }

}



if (!function_exists('controlSearchTypeDropdown')) {

    function controlSearchTypeDropdown($param = array('modId' => 0)) {

        $html = '';
        $html .= '<select class="form-control select2" name="keywordTypeId" id="keywordTypeId">';

        switch ($param['modId']) {
            
            case 33: {  //Кримналистик
                    $html .= '<option value="0" selected="selected">' . ' - Бүгд - </option>';

                    $html .= '<option value="1">Албан тушаалтны нэр</option>';

                    $html .= '<option value="2">Тайлбар</option>';

//                    $html .= '<option value="3">Холбогдох мэдээлэл</option>';

                    $html .= '<option value="4">Хэргийн утга</option>';

                    $html .= '<option value="5">Дүгнэлтийн утга</option>';
                };
                break;
            
            case 34: {  //Хэргийн газрын үзлэг
                    $html .= '<option value="0" selected="selected">' . ' - Бүгд - </option>';

                    $html .= '<option value="1">Албан тушаалтны нэр</option>';

                    $html .= '<option value="2">Хэргийн утга</option>';

                };
                break;            

            case 50: {  //Хавтаст хэрэг
                    $html .= '<option value="0" selected="selected">' . ' - Бүгд - </option>';

                    $html .= '<option value="1">Эцэг/эх/-ийн нэр</option>';

                    $html .= '<option value="2">Өөрийн нэр</option>';

                    $html .= '<option value="3">Албан тушаалтны нэр</option>';

                    $html .= '<option value="4">Тайлбар</option>';

                    $html .= '<option value="5">Дүгнэлтийн утга</option>';
                };
                break;

            case 51: {  //Эмчийн үзлэг
                    $html .= '<option value="0" selected="selected">' . ' - Бүгд - </option>';

                    $html .= '<option value="1">Эцэг/эх/-ийн нэр</option>';

                    $html .= '<option value="2">Өөрийн нэр</option>';

                    $html .= '<option value="3">Регистрийн дугаар</option>';

                    $html .= '<option value="4">Утас</option>';

                    $html .= '<option value="5">Тайлбар</option>';

                    $html .= '<option value="6">Албан хаагч</option>';

                    $html .= '<option value="7">Дүгнэлтийн утга</option>';
                };
                break;

            case 52: {  //Задлан шинжилгээ
                    $html .= '<option value="0" selected="selected">' . ' - Бүгд - </option>';

                    $html .= '<option value="1">Эцэг/эх/-ийн нэр</option>';

                    $html .= '<option value="2">Өөрийн нэр</option>';

                    $html .= '<option value="3">Регистрийн дугаар</option>';

                    $html .= '<option value="4">Хаяг</option>';

                    $html .= '<option value="5">Албан хаагч</option>';

                    $html .= '<option value="6">Болсон хэргийн товч</option>';

                    $html .= '<option value="7">Тайлбар</option>';

                    $html .= '<option value="8">Дүгнэлтийн утга</option>';
                };
                break;

            case 55: {
                    $html .= '<option value="0" selected="selected">' . ' - Бүгд - </option>';

                    $html .= '<option value="1">Эцэг/эх/-ийн нэр</option>';

                    $html .= '<option value="2">Өөрийн нэр</option>';

                    $html .= '<option value="3">Албан тушаалтны нэр</option>';

                    $html .= '<option value="4">Тайлбар</option>';

                    $html .= '<option value="5">Регистрийн дугаар</option>';

                    $html .= '<option value="6">Хэргийн утга</option>';

                    $html .= '<option value="7">Нэмэлт асуулт</option>';

                    $html .= '<option value="8">Дүгнэлтийн утга</option>';
                };
                break;

            case 56: {
                    $html .= '<option value="0" selected="selected">' . ' - Бүгд - </option>';

                    $html .= '<option value="1">Албан тушаалтны нэр</option>';

                    $html .= '<option value="2">Тайлбар</option>';

                    $html .= '<option value="3">Хэргийн утга</option>';

                    $html .= '<option value="4">Дүгнэлтийн утга</option>';
                };
                break;

            case 81: {
                
                    $html .= '<option value="0" selected="selected">' . ' - Бүгд - </option>';
                    
                    $html .= '<option value="1">Эцэг/эх/-ийн нэр</option>';

                    $html .= '<option value="2">Өөрийн нэр</option>';

                    $html .= '<option value="3">Регистрийн дугаар</option>';

                    $html .= '<option value="4">Утас</option>';

                    $html .= '<option value="5">Тайлбар</option>';

                    $html .= '<option value="6">Албан хаагч</option>';

                    $html .= '<option value="7">Дүгнэлтийн утга</option>';

                    $html .= '<option value="8">Объект</option>';

                    $html .= '<option value="9">Нэмэлт асуулт</option>';
                    
                    $html .= '<option value="10">Хариу тайлбар</option>';

                };
                break;
            
            case '81old': {
                
                    $html .= '<option value="0" selected="selected">' . ' - Бүгд - </option>';

                    $html .= '<option value="1">Эцэг/эх/-ийн нэр</option>';

                    $html .= '<option value="2">Өөрийн нэр</option>';

                    $html .= '<option value="3">Албан тушаалтны нэр</option>';

                    $html .= '<option value="4">Тайлбар</option>';

                    $html .= '<option value="5">Регистрийн дугаар</option>';

                    $html .= '<option value="6">Хэргийн утга</option>';

                    $html .= '<option value="7">Нэмэлт асуулт</option>';

                    $html .= '<option value="8">Дүгнэлтийн утга</option>';

                };
                break;

            default:
                break;
        }




        $html .= '</select>';
        return $html;
    }

}
