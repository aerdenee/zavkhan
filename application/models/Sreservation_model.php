<?php

class Sreservation_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function addFormData_model() {
        return array(
            'id' => 0,
            'mod_id' => 21,
            'cat_id' => 0,
            'organization_id' => 0,
            'date_in' => date('Y-m-d H:i:s'),
            'date_out' => date('Y-m-d H:i:s'),
            'adult' => 0,
            'staff' => 0,
            'breakfast_adult' => 0,
            'breakfast_staff' => 0,
            'lunch_adult' => 0,
            'lunch_staff' => 0,
            'dinner_adult' => 0,
            'dinner_staff' => 0,
            'status' => 0,
            'description' => 0,
            'travel_code' => '',
            'partner_id' => 0,
            'partner_title' => '',
            'partner_manager_name' => '',
            'partner_manager_phone' => '',
            'partner_manager_email' => '',
            'currency_type_id' => 0,
            'created_date' => date('Y-m-d H:i:s'),
            'modified_date' => '0000-00-00 00:00:00',
            'pay' => '',
            'deposit' => 0,
            'paid' => 0,
            'mail_html' => '',
            'is_active_mn' => 1,
            'order_num' => getOrderNum(array('table' => 'reservation', 'field' => 'order_num')),
            'ip_address' => '',
            'bed_night' => 0,
            'created_user_id' => 0,
            'modified_user_id' => 0
        );
    }

    public function editFormData_model($param = array('id' => 0)) {
        $this->data = array();
        $query = $this->db->query('
            SELECT 
                R.id,
                R.mod_id,
                R.cat_id,
                R.organization_id,
                C.title_mn,
                R.date_in,
                R.date_out,
                R.adult,
                R.staff,
                R.breakfast_adult,
                R.breakfast_staff,
                R.lunch_adult,
                R.lunch_staff,
                R.dinner_adult,
                R.dinner_staff,
                R.status,
                R.description,
                R.travel_code,
                R.partner_id,
                R.partner_title,
                R.partner_manager_name,
                R.partner_manager_phone,
                R.partner_manager_email,
                R.currency_type_id,
                R.created_date,
                R.modified_date,
                R.pay,
                R.deposit,
                R.paid,
                R.mail_html,
                R.is_active_mn,
                R.order_num,
                R.ip_address,
                R.bed_night,
                R.created_user_id,
                R.modified_user_id,
                U.lname_mn,
                U.fname_mn
            FROM `gaz_reservation` AS R 
            LEFT JOIN `gaz_content` AS C ON R.organization_id = C.id
            LEFT JOIN `gaz_user` AS U ON R.modified_user_id = U.id
            WHERE R.id=' . $param['id']);

        $result = $query->result();
        if (count($result) > 0) {

            $row = (array) $result['0'];

            $this->date = dateBetweenInfo(array('dateIn' => $row['date_in'], 'dateOut' => $row['date_out']));

            $this->data['html'] = form_open('', array('class' => 'form-horizontal', 'id' => 'form-reservation', 'enctype' => 'multipart/form-data'));
            $this->data['html'] .= '<input type="hidden" name="reservationId" value="' . $row['id'] . '">';
            $this->data['html'] .= '<input type="hidden" name="organizationId" value="' . $row['organization_id'] . '">';
            $this->data['html'] .= '<input type="hidden" name="dateIn" value="' . $row['date_in'] . '">';
            $this->data['html'] .= '<input type="hidden" name="dateOut" value="' . $row['date_out'] . '">';
            $this->data['html'] .= '<input type="hidden" name="status" value="' . $row['status'] . '">';

            $this->data['html'] .= '<div class="panel panel-flat">';
            $this->data['html'] .= '<div class="panel-heading">';
            $this->data['html'] .= '<h5 class="panel-title">Захиалга засварлах хуудас</h5>';
            $this->data['html'] .= '<div class="heading-elements">';
            $this->data['html'] .= '<ul class="list-inline heading-text">';
            $this->data['html'] .= '<li>' . anchor(Sreservation::$path . 'index/' . $param['modId'], '<i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i>', array()) . '</li>';
            $this->data['html'] .= '</ul>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<a class="heading-elements-toggle"><i class="icon-more"></i></a>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<div class="panel-body" style="padding-bottom:0;">';
            $this->data['html'] .= '<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">';
            $this->data['html'] .= '<h6 class="alert-heading text-semibold">' . $row['partner_title'] . '</h6>';


            $this->data['html'] .= $this->date['dateString'] . ' өдрүүдэд (' . $this->date['dateDiff'] . ' шөнө, ' . (intval($this->date['dateDiff']) + 1) . ' өдөр) <strong>' . $row['title_mn'] . '</strong>-д хийсэн захиалгын мэдээлэл';

            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<fieldset>';
            $this->data['html'] .= '<legend> </legend>';
            $this->data['html'] .= '<div class="row">';
            $this->data['html'] .= '<div class="col-md-6">';
            $this->data['html'] .= '<div class="form-group">';
            $this->data['html'] .= form_label('Харилцагч', 'Харилцагч', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE));
            $this->data['html'] .= '<div class="col-md-8">';
            $this->data['html'] .= $this->reservation->controlPartnerList_model(array('partnerId' => $row['partner_id']));
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<div class="form-group hide" id="partnername">';
            $this->data['html'] .= form_label('Байгууллагын нэр', 'Байгууллагын нэр', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE));
            $this->data['html'] .= '<div class="col-md-8">';
            $this->data['html'] .= form_input(array(
                'name' => 'partnerTitle',
                'id' => 'partnerTitle',
                'placeholder' => 'Байгууллагын нэр',
                'maxlength' => '100',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $row['partner_title']
            ));
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<div class="form-group">';
            $this->data['html'] .= form_label('Менежерийн нэр', 'Менежерийн нэр', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE));
            $this->data['html'] .= '<div class="col-md-8">';
            $this->data['html'] .= form_input(array(
                'name' => 'managerName',
                'id' => 'managerName',
                'placeholder' => 'Менежерийн нэр',
                'maxlength' => '100',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $row['partner_manager_name']
            ));
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<div class="form-group">';
            $this->data['html'] .= form_label('Утас', 'Утас', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE));
            $this->data['html'] .= '<div class="col-md-8">';
            $this->data['html'] .= form_input(array(
                'name' => 'managerPhone',
                'id' => 'managerPhone',
                'placeholder' => 'Утас',
                'maxlength' => '100',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $row['partner_manager_phone']
            ));
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<div class="form-group">';
            $this->data['html'] .= form_label('Мэйл', 'Мэйл', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE));
            $this->data['html'] .= '<div class="col-md-8">';
            $this->data['html'] .= form_input(array(
                'name' => 'managerEmail',
                'id' => 'managerEmail',
                'placeholder' => 'Мэйл',
                'maxlength' => '100',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $row['partner_manager_email']
            ));
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '</div>';

            $this->data['html'] .= '<div class="form-group">';
            $this->data['html'] .= form_label(' ', ' ', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE));
            $this->data['html'] .= '<div class="col-md-8">';
            $this->data['html'] .= '<label><input type="checkbox" name="isStatus" class="styled" onclick="_reservationStatusChange(this);" ' . ($row['status'] == 2 ? 'checked="checked"' : '') . '> Захиалга батлах</label>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '</div>';

            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<div class="col-md-6">';
            $this->data['html'] .= '<div class="form-group">';
            $this->data['html'] .= form_label('Аялалын код', 'Аялалын код', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE));
            $this->data['html'] .= '<div class="col-md-8">';
            $this->data['html'] .= form_input(array(
                'name' => 'travelCode',
                'id' => 'travelCode',
                'placeholder' => 'Утас',
                'maxlength' => '100',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $row['travel_code']
            ));
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<div class="form-group">';
            $this->data['html'] .= form_label('Тайлбар', 'Тайлбар', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE));
            $this->data['html'] .= '<div class="col-md-8">';
            $this->data['html'] .= form_textarea(array(
                'name' => 'description',
                'id' => 'description',
                'placeholder' => 'Нэмэлт мэдээлэл',
                'class' => 'form-control',
                'value' => $row['description'],
                'style' => 'height:150px;'
            ));
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<div class="pull-right">';
            $this->data['html'] .= '<ul class="list-inline heading-text">';
            $this->data['html'] .= '<li>' . form_button('send', '<i class="fa fa-plus"></i> Сууц нэмж бүртгэх', 'class="btn btn-info btn-rounded btn-xs" onclick="_reSearchAccommodation(' . $row['id'] . ', ' . $row['organization_id'] . ', \'' . $row['date_in'] . '\', \'' . $row['date_out'] . '\');"', 'button') . '</li>';
            $this->data['html'] .= '</ul>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<div style="clearfix"></div>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '</fieldset>';



            $this->data['html'] .= '</div>';

            $this->data['html'] .= '<div class="table-responsive">';
            $this->data['html'] .= '<table class="table table-bordered table-custom">';
            $this->data['html'] .= '<thead>';
            $this->data['html'] .= '<tr>';
            $this->data['html'] .= '<th class="ckbox">#</th>';
            $this->data['html'] .= '<th class="control-select">Ор хоног</th>';
            $this->data['html'] .= '<th class="control-input-cook" style="width:80px;">Өглөө</th>';
            $this->data['html'] .= '<th class="control-input-cook" style="width:80px;">Өдөр</th>';
            $this->data['html'] .= '<th class="control-input-cook" style="width:80px;">Орой</th>';
            $this->data['html'] .= '<th class="ckbox">Хөтөч</th>';
            $this->data['html'] .= '<th colspan="2"> Сууцны мэдээлэл</th>';
            $this->data['html'] .= '<th style="width:100px;" class="text-center"><i class="icon-cog7"></i></th>';
            $this->data['html'] .= '</tr>';
            $this->data['html'] .= '</thead>';
            $this->data['html'] .= '<tbody>';
            $i = 0;
            $queryRD = $this->db->query('SELECT 
                        A.id, A.mod_id, A.accommodation_code_mn, 
                        A.accommodation_bed_id, B.title_mn AS bed_title_mn, B.bed_count, 
                        A.accommodation_type_id, T.title_mn AS type_title_mn, 
                        A.accommodation_class_id, C.title_mn AS class_title_mn,
                        A.price, A.description_mn, A.pic, A.pic_vertical, A.is_active, A.order_num,
                        RD.id AS reservation_dtl_id, RD.adult, RD.breakfast, RD.lunch, RD.dinner, RD.is_staff
                    FROM `gaz_reservation_dtl` AS RD 
                    INNER JOIN gaz_accommodation as A ON RD.accommodation_id = A.id
                    INNER JOIN gaz_accommodation_bed as B ON A.accommodation_bed_id = B.id
                    INNER JOIN gaz_accommodation_type as T ON A.accommodation_type_id = T.id
                    INNER JOIN gaz_accommodation_class as C ON A.accommodation_class_id = C.id
                    WHERE RD.reservation_id = ' . $param['id']);
            $resultRD = $queryRD->result();

            foreach ($resultRD as $valueRD) {
                $rowRD = (array) $valueRD;
                $i++;
                $this->data['html'] .= '<tr data-id="' . $rowRD['reservation_dtl_id'] . '" data-reservation-id="' . $param['id'] . '">';
                $this->data['html'] .= '<td>' . $i . '</td>';
                $this->data['html'] .= '<td class="reservation-dtl-selected-row" class="text-center"><input type="hidden" name="adult[]" value="' . $rowRD['adult'] . '" required="required">';
                $this->data['html'] .= '<select class="select adult-guide" onchange="_bld(this)" required="required">';
                $this->data['html'] .= '<option value="0"> - Сонгох - </option>';
                for ($x = 1; $x <= $rowRD['bed_count']; $x++) {
                    $this->data['html'] .= '<option value="' . $x . '" ' . ($rowRD['adult'] == $rowRD['bed_count'] ? ' selected="selected"' : '') . '>' . $x . ' хүн</option>';
                }
                $this->data['html'] .= '</select>';
                $this->data['html'] .= '</td>';
                $this->data['html'] .= '<td class="reservation-dtl-selected-row" class="text-center"><input type="text" class="form-control cook" maxlength="1" name="breakfast[]" value="' . $rowRD['breakfast'] . '" required="required"></td>';
                $this->data['html'] .= '<td class="reservation-dtl-selected-row" class="text-center"><input type="text" class="form-control cook" maxlength="1" name="lunch[]" value="' . $rowRD['lunch'] . '" required="required"></td>';
                $this->data['html'] .= '<td class="reservation-dtl-selected-row" class="text-center"><input type="text" class="form-control cook" maxlength="1" name="dinner[]" value="' . $rowRD['dinner'] . '" required="required"></td>';
                $this->data['html'] .= '<td class="reservation-dtl-selected-row" class="ckbox text-center">';
                $this->data['html'] .= '<input type="hidden" name="reservationDtlId[]" value="' . $rowRD['reservation_dtl_id'] . '">';
                $this->data['html'] .= '<input type="hidden" name="accommodationId[]" value="' . $rowRD['id'] . '">';
                $this->data['html'] .= '<input type="hidden" name="isStaff[]" value="' . $rowRD['is_staff'] . '">';
                $this->data['html'] .= '<input type="checkbox" class="styled" onclick="_isStaff(this)"  ' . (!empty($rowRD['is_staff']) ? ' checked="checked"' : '') . ' >';
                $this->data['html'] .= '</td>';
                $this->data['html'] .= '<td class="reservation-dtl-selected-row" class="text-center" style="width:100px;"><img src="/upload/image/s_' . $rowRD['pic'] . '" style="max-width:60px;"></td>';
                $this->data['html'] .= '<td class="reservation-dtl-selected-row">' . $rowRD['bed_title_mn'] . ' ' . $rowRD['class_title_mn'] . ' ' . $rowRD['type_title_mn'] . ' - (#' . $rowRD['accommodation_code_mn'] . ')</td>';
                $this->data['html'] .= '<td class="text-center"><ul class="icons-list"><li><a href="javascript:;" onclick="_removeReservationDtlItem(' . $rowRD['reservation_dtl_id'] . ',' . $param['id'] . ');"><i class="icon-trash"></i></a></li></ul></td>';
                $this->data['html'] .= '</tr>';
            }

            $this->data['html'] .= '</tbody>';
            $this->data['html'] .= '</table>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<div class="panel-footer">';
            $this->data['html'] .= '<div class="heading-elements">';
            $this->data['html'] .= '<span class="heading-text text-semibold"></span>';
            $this->data['html'] .= '<div class="heading-btn pull-right">';
            $this->data['html'] .= form_button('send', '<i class="fa fa-save"></i> Хадгалах', 'class="btn btn-info" onclick="_updateReservation(this);"', 'button');
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '<a class="heading-elements-toggle"><i class="icon-more"></i></a>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= '</div>';
            $this->data['html'] .= form_close();
            return $this->data;
        }
        return self::addFormData_model();
    }

    public function insert_model($param = array('modId' => 0, 'organizationId' => 0)) {

        $this->adult = $this->staff = $this->adultBreakfast = $this->adultLunch = $this->adultDinner = $this->staffBreakfast = $this->staffLunch = $this->staffDinner = 0;
        $this->getUID = getUID('reservation');

        for ($i = 0; $i < count($this->input->post('adult')); $i++) {
            if (intval($this->input->post('isStaff[' . $i . ']')) === 1) {
                $this->staff = $this->staff + intval($this->input->post('adult[' . $i . ']'));
                $this->staffBreakfast = $this->staffBreakfast + intval($this->input->post('breakfast[' . $i . ']'));
                $this->staffLunch = $this->staffLunch + intval($this->input->post('lunch[' . $i . ']'));
                $this->staffDinner = $this->staffDinner + intval($this->input->post('dinner[' . $i . ']'));
            } else {
                $this->adult = $this->adult + intval($this->input->post('adult[' . $i . ']'));
                $this->adultBreakfast = $this->adultBreakfast + intval($this->input->post('breakfast[' . $i . ']'));
                $this->adultLunch = $this->adultLunch + intval($this->input->post('lunch[' . $i . ']'));
                $this->adultDinner = $this->adultDinner + intval($this->input->post('dinner[' . $i . ']'));
            }
        }

        $this->data = array(
            array(
                'id' => $this->getUID,
                'organization_id' => $this->input->post('organizationId'),
                'date_in' => $this->input->post('dateIn'),
                'date_out' => $this->input->post('dateOut'),
                'adult' => $this->adult,
                'staff' => $this->staff,
                'breakfast_adult' => $this->adultBreakfast,
                'breakfast_staff' => $this->staffBreakfast,
                'lunch_adult' => $this->adultLunch,
                'lunch_staff' => $this->staffLunch,
                'dinner_adult' => $this->adultDinner,
                'dinner_staff' => $this->staffDinner,
                'status' => 2,
                'description' => $this->input->post('description'),
                'travel_code' => $this->input->post('travelCode'),
                'partner_id' => $this->input->post('partnerId'),
                'partner_title' => $this->input->post('partnerTitle'),
                'partner_manager_name' => $this->input->post('managerName'),
                'partner_manager_phone' => $this->input->post('managerPhone'),
                'partner_manager_email' => $this->input->post('managerEmail'),
                'created_user_id' => $this->session->adminUserId,
                'modified_user_id' => $this->session->adminUserId,
                'created_date' => date('Y-m-d H:i:s'),
                'modified_date' => date('Y-m-d H:i:s'),
                'currency_type_id' => ''
            )
        );
        
        $this->result = $this->db->insert_batch($this->db->dbprefix . 'reservation', $this->data);
        if ($this->result) {
            for ($i = 0; $i < count($this->input->post('adult')); $i++) {
                if (intval($this->input->post('adult[' . $i . ']')) > 0) {
                    $this->dataDtl = array(
                        array(
                            'id' => getUID('reservation_dtl'),
                            'accommodation_id' => $this->input->post('accommodationId[' . $i . ']'),
                            'reservation_id' => $this->getUID,
                            'adult' => $this->input->post('adult[' . $i . ']'),
                            'breakfast' => $this->input->post('breakfast[' . $i . ']'),
                            'lunch' => $this->input->post('lunch[' . $i . ']'),
                            'dinner' => $this->input->post('dinner[' . $i . ']'),
                            'is_staff' => $this->input->post('isStaff[' . $i . ']')
                        )
                    );
                }
                $this->db->insert_batch($this->db->dbprefix . 'reservation_dtl', $this->dataDtl);
            }
            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $this->result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        
        return $this->result;
        
    }

    public function update_model($param = array()) {

        $this->bedNight = array();
        $this->bedNightString = $this->adult = $this->staff = $this->adultBreakfast = $this->adultLunch = $this->adultDinner = $this->staffBreakfast = $this->staffLunch = $this->staffDinner = 0;

        for ($i = 0; $i < count($this->input->post('adult')); $i++) {
            if (intval($this->input->post('adult[' . $i . ']')) > 0) {
                $this->dataDtl = array(
                    'accommodation_id' => $this->input->post('accommodationId[' . $i . ']'),
                    'adult' => $this->input->post('adult[' . $i . ']'),
                    'breakfast' => $this->input->post('breakfast[' . $i . ']'),
                    'lunch' => $this->input->post('lunch[' . $i . ']'),
                    'dinner' => $this->input->post('dinner[' . $i . ']'),
                    'is_staff' => $this->input->post('isStaff[' . $i . ']')
                );
            }
            $this->db->where('id', $this->input->post('reservationDtlId[' . $i . ']'));
            $this->db->update($this->db->dbprefix . 'reservation_dtl', $this->dataDtl);
        }

        $this->query = $this->db->query('
            SELECT 
                RD.id,
                RD.accommodation_id,
                RD.reservation_id,
                RD.adult,
                RD.breakfast,
                RD.lunch,
                RD.dinner,
                RD.is_staff,
                A.accommodation_bed_id,
                A.accommodation_class_id,
                AB.code AS bed_code,
                AC.code AS class
            FROM `gaz_reservation_dtl` AS RD 
            INNER JOIN `gaz_accommodation` AS A ON RD.accommodation_id = A.id
            INNER JOIN `gaz_accommodation_bed` AS AB ON A.accommodation_bed_id = AB.id
            INNER JOIN `gaz_accommodation_class` AS AC ON A.accommodation_class_id = AC.id
            WHERE RD.reservation_id=' . $this->input->post('reservationId'));

        if ($this->query->num_rows() > 0) {

            $accommodationBedId = $accommodationClassId = 0;

            foreach ($this->query->result() as $key => $row) {
                if (intval($row->is_staff) === 1) {
                    $this->staff = $this->staff + intval($row->adult);
                    $this->staffBreakfast = $this->staffBreakfast + intval($row->breakfast);
                    $this->staffLunch = $this->staffLunch + intval($row->lunch);
                    $this->staffDinner = $this->staffDinner + intval($row->dinner);
                } else {
                    $this->adult = $this->adult + intval($row->adult);
                    $this->adultBreakfast = $this->adultBreakfast + intval($row->breakfast);
                    $this->adultLunch = $this->adultLunch + intval($row->lunch);
                    $this->adultDinner = $this->adultDinner + intval($row->dinner);
                }
            }
        }

        $this->data = array(
            'adult' => $this->adult,
            'staff' => $this->staff,
            'breakfast_adult' => $this->adultBreakfast,
            'breakfast_staff' => $this->staffBreakfast,
            'lunch_adult' => $this->adultLunch,
            'lunch_staff' => $this->staffLunch,
            'dinner_adult' => $this->adultDinner,
            'dinner_staff' => $this->staffDinner,
            'bed_night' => $this->bedNightString,
            'status' => $this->input->post('status'),
            'description' => $this->input->post('description'),
            'travel_code' => $this->input->post('travelCode'),
            'partner_id' => $this->input->post('partnerId'),
            'partner_title' => $this->input->post('partnerTitle'),
            'partner_manager_name' => $this->input->post('managerName'),
            'partner_manager_phone' => $this->input->post('managerPhone'),
            'partner_manager_email' => $this->input->post('managerEmail'),
            'modified_user_id' => $this->session->adminUserId,
            'modified_date' => date('Y-m-d H:i:s'),
            'currency_type_id' => ''
        );
        $this->db->where('id', $this->input->post('reservationId'));
        $this->result = $this->db->update($this->db->dbprefix . 'reservation', $this->data);
        if ($this->result) {
            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $this->result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $this->result;
    }

    public function listsCount_model($param = array()) {

        $this->queryString = '';
        $this->organization = 0;
        
        $this->auth = authentication(array('authentication'=>$this->session->userdata['authentication'], 'modId'=>$param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND R.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) { 
            $this->queryString .= ' AND R.created_user_id = -1';
        }
        
        if (count($param['organizationId'])!=0) {
            foreach ($param['organizationId'] as $key => $value) {
                $this->organization .= ',' . $value;
            }
            $this->queryString = ' AND R.organization_id IN (' . $this->organization . ')';
        }
        if ($param['dateIn'] != '' and $param['dateOut'] != '') {
            $this->queryString .= ' AND \'' . $param['dateIn'] . '\' <= DATE(R.date_in) AND \'' . $param['dateOut'] . '\' >= DATE(R.date_in)';
        }

        if ($param['travelCode'] != '') {
            $this->queryString .= ' AND LOWER(R.travel_code) LIKE LOWER(\'' . $param['travelCode'] . '%\')';
        }
        if ($param['keyword'] != '') {
            $this->queryString .= ' AND LOWER(R.description) LIKE LOWER(\'' . $param['keyword'] . '%\')';
        }

        if ($param['partnerId'] != 0) {
            $this->queryString .= ' AND R.partner_id = ' . $param['partnerId'];
        }

        $query = $this->db->query('
            SELECT 
                R.id
            FROM `gaz_reservation` AS R 
            LEFT JOIN gaz_content as C ON R.organization_id = C.id
            LEFT JOIN gaz_user as U ON R.modified_user_id = U.id
            WHERE 1 = 1 ' . $this->queryString . '
            ORDER BY R.id DESC');
        $result = $query->result();
        return count($result);
    }

    public function lists_model($param = array('modId' => 0, 'organizationId' => 0)) {
        
        $this->queryString = $this->getString = '';
        $this->organization = 0;
        
        $this->auth = authentication(array('authentication'=>$this->session->userdata['authentication'], 'modId'=>$param['modId']));

        if ($this->auth->our->read == 1 and $this->auth->your->read == 0) {
            $this->queryString .= ' AND R.created_user_id = ' . $this->session->adminUserId;
        } elseif ($this->auth->our->read == 0 and $this->auth->your->read == 0) { 
            $this->queryString .= ' AND R.created_user_id = -1';
        }
        
        if (count($param['organizationId'])!=0) {
            foreach ($param['organizationId'] as $key => $value) {
                $this->organization .= ',' . $value;
                $this->getString .= form_hidden('campId[]', $value);
            }
            $this->queryString = ' AND R.organization_id IN (' . $this->organization . ')';
        }
        
        if ($param['dateIn'] != '' and $param['dateOut'] != '') {
            $this->queryString .= ' AND \'' . $param['dateIn'] . '\' <= DATE(R.date_in) AND \'' . $param['dateOut'] . '\' >= DATE(R.date_in)';
            $this->getString .= form_hidden('dateIn', $param['dateIn']);
            $this->getString .= form_hidden('dateOut', $param['dateOut']);
        }

        if ($param['travelCode'] != '') {
            $this->queryString .= ' AND LOWER(R.travel_code) LIKE LOWER(\'' . $param['travelCode'] . '%\')';
            $this->getString .= form_hidden('travelCode', $param['travelCode']);
        }
        
        if ($param['keyword'] != '') {
            $this->queryString .= ' AND LOWER(R.description) LIKE LOWER(\'' . $param['keyword'] . '%\')';
            $this->getString .= form_hidden('keyword', $param['keyword']);
        }

        if ($param['partnerId'] != 0) {
            $this->queryString .= ' AND R.partner_id = ' . $param['partnerId'];
            $this->getString .= form_hidden('partnerId', $param['partnerId']);
        }

        $query = $this->db->query('
            SELECT 
                R.id,
                R.organization_id,
                C.title_mn,
                R.date_in,
                R.date_out,
                R.adult,
                R.staff,
                R.bed_night,
                R.breakfast_adult,
                R.breakfast_staff,
                R.lunch_adult,
                R.lunch_staff,
                R.dinner_adult,
                R.dinner_staff,
                R.description,
                R.travel_code,
                R.partner_title,
                R.partner_manager_name,
                R.partner_manager_phone,
                R.modified_user_id,
                R.modified_date,
                R.status,
                U.lname_mn AS lname,
                U.fname_mn AS fname,
                P.color
            FROM `gaz_reservation` AS R 
            LEFT JOIN gaz_content as C ON R.organization_id = C.id
            LEFT JOIN gaz_user as U ON R.modified_user_id = U.id
            LEFT JOIN gaz_partner as P ON R.partner_id = P.id
            WHERE 1 = 1 ' . $this->queryString . '
            ORDER BY R.id DESC
            LIMIT ' . $param['page'] . ', ' . $param['limit']);
        $result = $query->result();
        $resultCount = count($result);
        $data = array();
        $data['html'] = '';
        $data['html'] .= form_open('', array('class' => 'form-horizontal', 'id' => 'form-reservation-export', 'enctype' => 'multipart/form-data'));
        $data['html'] .= form_hidden('organizationId', $param['organizationId']);
        $data['html'] .= form_hidden('modId', $param['modId']);
        $data['html'] .= form_hidden('limit', $param['limit']);
        $data['html'] .= form_hidden('page', $param['page']);
        $data['html'] .= $this->getString;

        $data['html'] .= '<div class="panel panel-flat">';
        $data['html'] .= '<div class="panel-heading">';
        $data['html'] .= '<h5 class="panel-title">Захиалгын хуудас</h5>';
        $data['html'] .= '<div class="heading-elements">';
        $data['html'] .= '<ul class="list-inline heading-text">';
        $data['html'] .= '<li>' . anchor(Sreservation::$path . 'index/' . $param['modId'], '<i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i>', array()) . '</li>';
        $data['html'] .= '</ul>';
        $data['html'] .= '</div>';
        $data['html'] .= '</div>';
        $data['html'] .= '<div class="panel-body" style="padding-bottom:5px;">';
        $data['html'] .= '<div class="pull-right">';
        $data['html'] .= '<ul class="list-inline heading-text">';

        $data['html'] .= '<li style="padding-right:10px;">' . anchor(Sreservation::$path . 'add/' . $param['modId'], '<i class="fa fa-plus"></i> Нэмэх', 'class="btn btn-info btn-rounded btn-xs"') . '</li>';
        $data['html'] .= '<li style="padding-right:10px;">' . form_button('send', '<i class="fa fa-download"></i> Экспорт', 'class="btn btn-info btn-rounded btn-xs" onclick="_export(this);" ' . (empty($resultCount) ? ' disabled="disabled"' : ''), 'button') . '</li>';
        $data['html'] .= '<li>' . form_button('send', '<i class="fa fa-search"></i> хайх', 'class="btn btn-info btn-rounded btn-xs" onclick="_advensedSearch(this);"', 'button') . '</li>';
        $data['html'] .= '</ul>';
        $data['html'] .= '</div>';
        $data['html'] .= '</div>';
        if ($resultCount > 0) {

            $data['html'] .= '<div class="table-responsive">';
            $data['html'] .= '<table class="table table-bordered table-custom">';
            $data['html'] .= '<thead>';
            $data['html'] .= '<tr>';
            $data['html'] .= '<th rowspan="2" style="width:30px;">#</th>';
            $data['html'] .= '<th colspan="2" style="width:200px;">Сар, өдөр</th>';
            $data['html'] .= '<th colspan="2">Ор, хоног</th>';
            $data['html'] .= '<th rowspan="2" style="width:150px;">Ор, хоног</th>';
            $data['html'] .= '<th colspan="2">Өглөөний цай</th>';
            $data['html'] .= '<th colspan="2">Өдрийн хоол</th>';
            $data['html'] .= '<th colspan="2">Оройн хоол</th>';
            $data['html'] .= '<th rowspan="2">Тайлбар</th>';
            $data['html'] .= '<th colspan="2">Захиалагч компани</th>';
            $data['html'] .= '<th rowspan="2">Баталсан</th>';
            $data['html'] .= '<th rowspan="2" style="width:100px;"><i class="icon-cog7"></i></th>';
            $data['html'] .= '</tr>';
            $data['html'] .= '<tr>';
            $data['html'] .= '<th style="width:100px;">Ирэх</th>';
            $data['html'] .= '<th style="width:100px;">Буцах</th>';
            $data['html'] .= '<th style="width:40px;">Жуу</th>';
            $data['html'] .= '<th style="width:40px;">Ү/А</th>';
            $data['html'] .= '<th style="width:40px;">Жуу</th>';
            $data['html'] .= '<th style="width:40px;">Ү/А</th>';
            $data['html'] .= '<th style="width:40px;">Жуу</th>';
            $data['html'] .= '<th style="width:40px;">Ү/А</th>';
            $data['html'] .= '<th style="width:40px;">Жуу</th>';
            $data['html'] .= '<th style="width:40px;">Ү/А</th>';
            $data['html'] .= '<th style="width:80px;">Код</th>';
            $data['html'] .= '<th style="width:200px;">Холбоо барих</th>';
            $data['html'] .= '</tr>';
            $data['html'] .= '</thead>';
            $data['html'] .= '<tbody>';

            $i = 1;
            foreach ($result as $value) {
                $row = (array) $value;
                $data['html'] .= '<tr data-id="' . $row['id'] . '" style="background-color: ' . $row['color'] . ';">';
                $data['html'] .= '<td class="text-center">' . $i . '</td>';
                $data['html'] .= '<td class="context-menu-selected-row">' . dateFormatMonth(array('date' => $row['date_in'])) . '</td>';
                $data['html'] .= '<td class="context-menu-selected-row">' . dateFormatMonth(array('date' => $row['date_out'])) . '</td>';
                $data['html'] .= '<td class="text-center context-menu-selected-row">' . $row['adult'] . '</td>';
                $data['html'] .= '<td class="text-center context-menu-selected-row">' . $row['staff'] . '</td>';
                $data['html'] .= '<td class="text-center context-menu-selected-row">' . $row['bed_night'] . '</td>';
                $data['html'] .= '<td class="text-center context-menu-selected-row">' . $row['breakfast_adult'] . '</td>';
                $data['html'] .= '<td class="text-center context-menu-selected-row">' . $row['breakfast_staff'] . '</td>';
                $data['html'] .= '<td class="text-center context-menu-selected-row">' . $row['lunch_adult'] . '</td>';
                $data['html'] .= '<td class="text-center context-menu-selected-row">' . $row['lunch_staff'] . '</td>';
                $data['html'] .= '<td class="text-center context-menu-selected-row">' . $row['dinner_adult'] . '</td>';
                $data['html'] .= '<td class="text-center context-menu-selected-row">' . $row['dinner_staff'] . '</td>';
                $data['html'] .= '<td class="context-menu-selected-row">' . $row['description'] . '</td>';
                $data['html'] .= '<td class="context-menu-selected-row">' . $row['travel_code'] . '</td>';
                $data['html'] .= '<td class="context-menu-selected-row">' . $row['partner_title'] . ' - ' . $row['partner_manager_name'] . '  /' . $row['partner_manager_phone'] . '/</td>';
                
                $data['html'] .= '<td class="context-menu-selected-row">' . ($row['status'] == 2 ? mb_strtoupper(mb_substr($row['lname'], 0, 1, 'utf-8'), 'utf-8') . '.' . mb_ucfirst($row['fname']) . ' /' . dateFormatMonth(array('date' => $row['modified_date'])) : '') . '</td>';
                $data['html'] .= '<td class="text-center">';
                $data['html'] .= '<span class="label label-default" style="cursor:pointer" onclick="_viewReservation(' . $row['id'] . ', ' . $param['modId'] . ')"> Захиалга</span>';
//                $data['html'] .= '<div class="btn-group">';
//                $data['html'] .= '<a href="javascript:;" class="label label-info dropdown-toggle" data-toggle="dropdown">Тохиргоо <span class="caret"></span></a>';
//                $data['html'] .= '<ul class="dropdown-menu dropdown-menu-right">';
//                $data['html'] .= '<li><a href="#"><span class="status-mark bg-danger position-left"></span> High priority</a></li>';
//                $data['html'] .= '<li><a href="#"><span class="status-mark bg-info position-left"></span> Normal priority</a></li>';
//                $data['html'] .= '</ul>';
//                $data['html'] .= '</div>';
                $data['html'] .= '</td>';
                $data['html'] .= '</tr>';
                $i++;
            }

            $data['html'] .= '</tbody>';
            $data['html'] .= '</table>';
            $data['html'] .= '</div>';
            $data['html'] .= '<div class="panel-footer">';
            $data['html'] .= '<div class="heading-elements">';
            $data['html'] .= '<span class="heading-text text-semibold"></span>';
            $data['html'] .= '<div class="heading-btn pull-right">';
            $data['html'] .= $param['paginationHtml'];
            $data['html'] .= '</div>';
            $data['html'] .= '</div>';
            $data['html'] .= '<a class="heading-elements-toggle"><i class="icon-more"></i></a>';
            $data['html'] .= '</div>';
        } else {
            $data['html'] .= '<div class="panel-body">';
            $data['html'] .= '<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">Захиалга хийгдээгүй байна</div>';
            $data['html'] .= '</div>';
        }


        $data['html'] .= '</div>';
        $data['html'] .= form_close();
        return $data['html'];
    }

    public function isActive_model() {
        $data = array(
            'is_active' => $this->input->post('isActive')
        );
        $this->db->where('id', $this->input->post('id'));
        $result = $this->db->update($this->db->dbprefix . 'accommodation', $data);
        if ($result) {
            $result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $result;
    }

    public function deleteReservationDtl_model() {

        $this->db->where('id', $this->input->post('id'));

        if ($this->db->delete('gaz_reservation_dtl')) {

            $this->query = $this->db->query('
            SELECT 
                R.id
            FROM `gaz_reservation_dtl` AS R 
            WHERE R.reservation_id=' . $this->input->post('reservationId'));

            $this->result = $this->query->result();
            if ($this->query->num_rows() > 0) {

                return self::updateReservationHeader();
                
            } else {

                $this->db->where('id', $this->input->post('reservationId'));
                if ($this->db->delete($this->db->dbprefix . 'reservation')) {
                    return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
                }
            }
        }

        return array('status' => 'error', 'message' => 'Сууцны мэдээлэл устгах үед алдаа гарлаа');
    }

    public function insertReservationDtl_model($param = array()) {

        for ($i = 0; $i < count($this->input->post('adult')); $i++) {
            if (intval($this->input->post('adult[' . $i . ']')) > 0) {
                $this->dataDtl = array(
                    array(
                        'id' => getUID('reservation_dtl'),
                        'accommodation_id' => $this->input->post('accommodationId[' . $i . ']'),
                        'reservation_id' => $this->input->post('reservationId'),
                        'adult' => $this->input->post('adult[' . $i . ']'),
                        'breakfast' => $this->input->post('breakfast[' . $i . ']'),
                        'lunch' => $this->input->post('lunch[' . $i . ']'),
                        'dinner' => $this->input->post('dinner[' . $i . ']'),
                        'is_staff' => $this->input->post('isStaff[' . $i . ']')
                    )
                );
            }
            $this->db->insert_batch($this->db->dbprefix . 'reservation_dtl', $this->dataDtl);
        }

        return self::updateReservationHeader();
        
    }
    
    public function updateReservationHeader() {
        
        $this->bedNight = array();
        $this->bedNightString = $this->adult = $this->staff = $this->adultBreakfast = $this->adultLunch = $this->adultDinner = $this->staffBreakfast = $this->staffLunch = $this->staffDinner = 0;
        
        $this->query = $this->db->query('
            SELECT 
                RD.id,
                RD.accommodation_id,
                RD.reservation_id,
                RD.adult,
                RD.breakfast,
                RD.lunch,
                RD.dinner,
                RD.is_staff,
                A.accommodation_bed_id,
                A.accommodation_class_id,
                AB.code AS bed_code,
                AC.code AS class
            FROM `gaz_reservation_dtl` AS RD 
            INNER JOIN `gaz_accommodation` AS A ON RD.accommodation_id = A.id
            INNER JOIN `gaz_accommodation_bed` AS AB ON A.accommodation_bed_id = AB.id
            INNER JOIN `gaz_accommodation_class` AS AC ON A.accommodation_class_id = AC.id
            WHERE RD.reservation_id=' . $this->input->post('reservationId'));

        if ($this->query->num_rows() > 0) {

            $accommodationBedId = $accommodationClassId = 0;

            foreach ($this->query->result() as $key => $row) {
                if (intval($row->is_staff) === 1) {
                    $this->staff = $this->staff + intval($row->adult);
                    $this->staffBreakfast = $this->staffBreakfast + intval($row->breakfast);
                    $this->staffLunch = $this->staffLunch + intval($row->lunch);
                    $this->staffDinner = $this->staffDinner + intval($row->dinner);
                } else {
                    $this->adult = $this->adult + intval($row->adult);
                    $this->adultBreakfast = $this->adultBreakfast + intval($row->breakfast);
                    $this->adultLunch = $this->adultLunch + intval($row->lunch);
                    $this->adultDinner = $this->adultDinner + intval($row->dinner);
                }
            }
        }

        $this->data = array(
            'adult' => $this->adult,
            'staff' => $this->staff,
            'breakfast_adult' => $this->adultBreakfast,
            'breakfast_staff' => $this->staffBreakfast,
            'lunch_adult' => $this->adultLunch,
            'lunch_staff' => $this->staffLunch,
            'dinner_adult' => $this->adultDinner,
            'dinner_staff' => $this->staffDinner,
            'bed_night' => $this->bedNightString,
            'modified_user_id' => $this->session->adminUserId,
            'modified_date' => date('Y-m-d H:i:s'),
            'currency_type_id' => ''
        );
        $this->db->where('id', $this->input->post('reservationId'));
        $this->result = $this->db->update($this->db->dbprefix . 'reservation', $this->data);
        if ($this->result) {
            $this->result = array('status' => 'success', 'message' => 'Амжилттай хадгаллаа...');
        } else {
            $this->result = array('status' => 'error', 'message' => 'Хадгалах үед алдаа гарлаа...');
        }
        return $this->result;
    }
    
    public function delete_model() {
        foreach ($this->input->post('id') as $key => $id) {
            $this->db->where('reservation_id', $this->input->post('id[' . $key . ']'));
            if ($this->db->delete($this->db->dbprefix . 'reservation_dtl')) {
                $this->db->where('id', $this->input->post('id[' . $key . ']'));
                $this->db->delete($this->db->dbprefix . 'reservation');
            } else {
                break;
                return array('status' => 'error', 'title' => 'Анхааруулга', 'message' => 'Захиалга цуцлах үед алдаа гарлаа. Та дахин хийнэ үү!!!');
            }
        }
        return array('status' => 'success', 'title' => 'Амжилттай', 'message' => 'Амжилттай устгалаа');
    }

    public function controlRadioBtnCampList_model($param = array()) {

        $this->db->where('mod_id', 9);
        $this->db->where('is_active_' . $this->session->adminLangCode, 1);
        $this->db->order_by('order_num', 'ASC');
        $query = $this->db->get($this->db->dbprefix . 'content');
        $result = $query->result();
        $this->html = '';
        if (count($result) > 0) {
            foreach ($result as $k => $value) {
                $row = (Array) $value;
                $this->check = '';
                if ($row['id'] == $param['organizationId']) {
                    $this->check = 'checked="checked"';
                }
                $this->html .= '<label> <input type="radio" class="styled" name="campId" value="' . $row['id'] . '" ' . $this->check . ' onclick="setOrganizationId(' . $row['id'] . ');"> ' . $row['title_mn'] . '</label> ';
            }
        }
        return $this->html;
    }

    public function controlCheckBoxCampList_model($param = array()) {

        $this->db->where('mod_id', 9);
        $this->db->where('is_active_' . $this->session->adminLangCode, 1);
        $this->db->order_by('order_num', 'ASC');
        $query = $this->db->get($this->db->dbprefix . 'content');
        $result = $query->result();
        $this->html = '';
        if (count($result) > 0) {
            foreach ($result as $k => $value) {
                $row = (Array) $value;
                $this->check = '';
                if ($row['id'] == $param['organizationId']) {
                    $this->check = 'checked="checked"';
                }
                $this->html .= '<label> <input type="checkbox" class="styled" name="campId[]" value="' . $row['id'] . '" ' . $this->check . ' onclick="setOrganizationId(' . $row['id'] . ');"> ' . $row['title_mn'] . '</label> ';
            }
        }
        return $this->html;
    }

    function getOrganizationInfo_model($param = array()) {
        $query = $this->db->query(
                'SELECT 
                C.id, C.title_mn AS title, C.intro_text_mn AS intro_text 
            FROM `gaz_content` AS C 
            WHERE C.id=' . $param['organizationId']
        );
        $result = $query->result();
        return (array) $result['0'];
    }

    function resultCheckDate_model($param = array()) {

        $this->queryString = $this->accommodation = '';
        $this->accommodation = 0;

        if (!empty($param['organizationId'])) {
            $this->queryString = ' WHERE A.organization_id = ' . $param['organizationId'];
        }

        $queryAccommodation = $this->db->query('
            SELECT 
                RD.accommodation_id 
            FROM `gaz_reservation` AS R 
            INNER JOIN gaz_reservation_dtl as RD ON R.id = RD.reservation_id 
            WHERE 
                R.organization_id = ' . $param['organizationId'] . ' AND 
                \'' . $param['dateIn'] . '\' <= DATE(R.date_in) AND 
                \'' . $param['dateOut'] . '\' >= DATE(R.date_in)');

        $resultAccommodation = $queryAccommodation->result();

        if (count($resultAccommodation) > 0) {
            foreach ($resultAccommodation as $key => $value) {
                $row = (Array) $value;
                $this->accommodation .= ',' . $row['accommodation_id'];
            }
            $this->queryString .= ' AND A.id NOT IN (' . $this->accommodation . ')';
        }

        $data = array('organizationId' => $param['organizationId']);

        $data['title'] = 'Захиалгын хуудас';
        $data['reservation'] = true;
        $data['btn_yes'] = 'Захиалах';
        $data['btn_no'] = 'Болих';


        $query = $this->db->query(
                'SELECT 
            A.id, A.mod_id, A.accommodation_code_mn, A.accommodation_code_en, 
            A.accommodation_bed_id, B.title_mn AS bed_title_mn, B.title_en AS bed_title_en, B.bed_count, 
            A.accommodation_type_id, T.title_mn AS type_title_mn, T.title_en AS type_title_en, 
            A.accommodation_class_id, C.title_mn AS class_title_mn, C.title_en AS class_title_en,
            A.organization_id AS camp_id, CAMP.title AS camp_title_mn, CAMP.title_en AS camp_title_en,
            A.price, A.description_mn, A.description_en, A.pic, A.pic_vertical, A.is_active, A.order_num
        FROM `gaz_accommodation` AS A 
        INNER JOIN gaz_accommodation_bed as B ON A.accommodation_bed_id = B.id
        INNER JOIN gaz_accommodation_type as T ON A.accommodation_type_id = T.id
        INNER JOIN gaz_accommodation_class as C ON A.accommodation_class_id = C.id
        INNER JOIN gaz_content as CAMP ON A.organization_id = CAMP.id ' . $this->queryString . ' ORDER BY A.order_num DESC');
        $result = $query->result();
        $this->organization = $this->getOrganizationInfo_model(array('organizationId' => $param['organizationId']));
        $this->date = dateBetweenInfo(array('dateIn' => $param['dateIn'], 'dateOut' => $param['dateOut']));
        if (count($result) > 0) {
            
            $data['html'] = form_open('', array('class' => 'form-horizontal', 'id' => 'form-reservation', 'enctype' => 'multipart/form-data'));
            $data['html'] .= '<input type="hidden" name="organizationId" value="' . $param['organizationId'] . '">';
            $data['html'] .= '<input type="hidden" name="dateIn" value="' . $param['dateIn'] . '">';
            $data['html'] .= '<input type="hidden" name="dateOut" value="' . $param['dateOut'] . '">';
            $data['html'] .= '<input type="hidden" name="reservationId" value="' . $param['reservationId'] . '">';
            $data['html'] .= '<input type="hidden" name="dateString" value="' . $this->date['dateString'] . '">';
            
            $data['html'] .= '<div class="panel panel-flat">';
            $data['html'] .= '<div class="panel-heading">';
            $data['html'] .= '<h5 class="panel-title">Захиалгын хуудас</h5>';
            $data['html'] .= '<div class="heading-elements">';
            $data['html'] .= '<ul class="list-inline heading-text">';
            $data['html'] .= '<li>' . anchor(Sreservation::$path . 'index/' . $param['modId'], '<i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i>', array()) . '</li>';
            $data['html'] .= '</ul>';
            $data['html'] .= '</div>';
            $data['html'] .= '<a class="heading-elements-toggle"><i class="icon-more"></i></a>';
            $data['html'] .= '</div>';
            
            $data['html'] .= '<div class="panel-body">';
            $data['html'] .= '<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">
                <h6 class="alert-heading text-semibold">' . $this->organization['title'] . '</h6>
                ' . $this->date['dateString'] . ' өдрүүдэд захиалга хийх боломжтой сууцны жагсаалт</div>';
            $data['html'] .= '</div>';

            $data['html'] .= '<div class="table-responsive">';
            $data['html'] .= '<table class="table table-bordered table-sm table-custom">';
            $data['html'] .= '<thead>';
            $data['html'] .= '<tr>';
            $data['html'] .= '<th class="ckbox">#</th>';
            $data['html'] .= '<th class="control-select">Ор хоног</th>';
            $data['html'] .= '<th class="control-input-cook" style="width:80px;">Өглөө</th>';
            $data['html'] .= '<th class="control-input-cook" style="width:80px;">Өдөр</th>';
            $data['html'] .= '<th class="control-input-cook" style="width:80px;">Орой</th>';
            $data['html'] .= '<th class="ckbox">Хөтөч</th>';
            $data['html'] .= '<th colspan="2"> Сууцны мэдээлэл</th>';
            $data['html'] .= '</tr>';
            $data['html'] .= '</thead>';
            $data['html'] .= '<tbody>';
            $i = 0;
            foreach ($result as $value) {
                $row = (array) $value;
                $i++;
                $data['html'] .= '<tr>';
                $data['html'] .= '<td>' . $i . '</td>';
                $data['html'] .= '<td><input type="hidden" name="adult[]" disabled="true" required="required">';
                $data['html'] .= '<select class="select adult-guide" onchange="_bld(this)" required="required">';
                $data['html'] .= '<option value="0"> - Сонгох - </option>';
                for ($x = 1; $x <= $row['bed_count']; $x++) {
                    $data['html'] .= '<option value="' . $x . '">' . $x . ' хүн</option>';
                }
                $data['html'] .= '</select>';
                $data['html'] .= '</td>';
                $data['html'] .= '<td><input type="text" class="form-control cook" maxlength="1" name="breakfast[]" disabled="true" required="required"></td>';
                $data['html'] .= '<td><input type="text" class="form-control cook" maxlength="1" name="lunch[]" disabled="true" required="required"></td>';
                $data['html'] .= '<td><input type="text" class="form-control cook" maxlength="1" name="dinner[]" disabled="true" required="required"></td>';
                $data['html'] .= '<td class="ckbox text-center"><input type="hidden" name="accommodationId[]" value="' . $row['id'] . '"> <input type="hidden" name="isStaff[]" disabled="true"><input type="checkbox" class="styled" onclick="_isStaff(this)" disabled="true"></td>';
                $data['html'] .= '<td style="width:100px;"><img src="/upload/image/s_' . $row['pic'] . '" style="max-width:60px;"></td>';
                $data['html'] .= '<td>' . $row['bed_title_mn'] . ' ' . $row['class_title_mn'] . ' ' . $row['type_title_mn'] . ' - (#' . $row['accommodation_code_mn'] . ')</td>';
                $data['html'] .= '</tr>';
            }
            $data['html'] .= '</tbody>';
            $data['html'] .= '</table>';
            $data['html'] .= '</div>';
            $data['html'] .= '<div class="panel-footer">';
            $data['html'] .= '<div class="heading-elements">';
            $data['html'] .= '<span class="heading-text text-semibold"></span>';
            $data['html'] .= '<div class="heading-btn pull-right">';
            $data['html'] .= form_button('send', '<i class="fa fa-check"></i> Батлах', 'class="btn btn-info" onclick="' . ($param['reservationId'] == 0 ? '_confirmForm(this);' : '_insertReservationDtl(this);') . '"', 'button');
            $data['html'] .= '</div>';
            $data['html'] .= '</div>';
            $data['html'] .= '<a class="heading-elements-toggle"><i class="icon-more"></i></a>';
            $data['html'] .= '</div>';
            $data['html'] .= '</div>';
            $data['html'] .= form_close();
        } else {
            $data['title'] = 'Санамж';
            $data['reservation'] = false;
            $data['html'] = $this->date['dateString'] . ' өдрүүдэд захиалга хийх боломжгүй байна';
        }
        return $data;
    }

    public function controlPartnerList_model($param = array()) {

        $query = $this->db->query('
            SELECT 
                id, title_mn as title, manager_name, manager_phone 
            FROM `gaz_partner` 
            WHERE is_active_mn = 1');

        $result = $query->result();

        $this->html = '<select class="select" name="partnerId" onchange="_setPartnerValue(this);" required="required">';
        $this->html .= '<option value=""> - Сонгох - </option>';
        if (count($result) > 0) {
            foreach ($result as $k => $value) {
                $row = (Array) $value;
                $this->check = '';
                if ($row['id'] == $param['partnerId']) {
                    $this->check = 'selected="selected"';
                }
                $this->html .= '<option value="' . $row['id'] . '" ' . $this->check . ' data-partner="' . $row['title'] . '" data-manager-name="' . $row['manager_name'] . '" data-manager-phone="' . $row['manager_phone'] . '"> ' . $row['title'] . ' </option>';
            }
        }
        $this->html .= '<option value="0"> - Бусад - </option>';
        $this->html .= '</select>';

        return $this->html;
    }

    public function getPartnerInformation_model($param = array()) {
        $query = $this->db->query('
            SELECT 
                id, title_mn AS title, manager_name, manager_phone, email 
            FROM `gaz_partner` 
            WHERE id = ' . $param['partnerId']);
        $result = $query->result();
        return (Array) $result['0'];
    }

    public function export_model($param = array('modId' => 0, 'organizationId' => 0)) {
        $this->queryString = '';
        $this->organization = 0;

        if (count($param['organizationId'])!=0) {
            foreach ($param['organizationId'] as $key => $value) {
                $this->organization .= ',' . $value;
            }
            $this->queryString = ' AND R.organization_id IN (' . $this->organization . ')';
        }
        if ($param['dateIn'] != '' and $param['dateOut'] != '') {
            $this->queryString .= ' AND \'' . $param['dateIn'] . '\' <= DATE(R.date_in) AND \'' . $param['dateOut'] . '\' >= DATE(R.date_in)';
        }

        if ($param['travelCode'] != '') {
            $this->queryString .= ' AND LOWER(R.travel_code) LIKE LOWER(\'' . $param['travelCode'] . '%\')';
        }
        if ($param['keyword'] != '') {
            $this->queryString .= ' AND LOWER(R.description) LIKE LOWER(\'' . $param['keyword'] . '%\')';
        }

        if ($param['partnerId'] != 0) {
            $this->queryString .= ' AND R.partner_id = ' . $param['partnerId'];
        }

        $query = $this->db->query('
            SELECT 
                R.id,
                R.organization_id,
                C.title_mn,
                R.date_in,
                R.date_out,
                R.adult,
                R.staff,
                \'\' AS bed_night,
                R.breakfast_adult,
                R.breakfast_staff,
                R.lunch_adult,
                R.lunch_staff,
                R.dinner_adult,
                R.dinner_staff,
                R.description,
                R.travel_code,
                R.partner_title,
                R.partner_manager_name,
                R.partner_manager_phone,
                R.modified_user_id,
                R.modified_date,
                R.status,
                U.lname_mn AS lname,
                U.fname_mn AS fname,
                P.color
            FROM `gaz_reservation` AS R 
            LEFT JOIN gaz_content as C ON R.organization_id = C.id
            LEFT JOIN gaz_user as U ON R.modified_user_id = U.id
            LEFT JOIN gaz_partner as P ON R.partner_id = P.id
            WHERE 1 = 1 ' . $this->queryString . '
            ORDER BY R.id DESC');
        $result = $query->result();
        $resultCount = count($result);
        if ($resultCount > 0) {
            return $result;
        }
        return false;
    }

    public function viewReservation_model($param = array('id' => 0)) {
        $this->query = $this->db->query('
            SELECT 
                R.id,
                R.organization_id,
                C.title_mn,
                R.date_in,
                R.date_out,
                R.description,
                R.travel_code,
                R.status,
                R.partner_id,
                R.partner_title,
                R.partner_manager_name,
                R.partner_manager_phone,
                R.partner_manager_email,
                R.modified_user_id,
                R.modified_date,
                U.lname_mn,
                U.fname_mn,
                R.mail_html
            FROM `gaz_reservation` AS R 
            LEFT JOIN `gaz_content` AS C ON R.organization_id = C.id
            LEFT JOIN `gaz_user` AS U ON R.modified_user_id = U.id
            WHERE R.id=' . $param['id']);
        if ($this->query->num_rows() > 0) {
            $this->result = $this->query->result();
            return $this->result[0];
        }
        return false;
    }
    
    public function getData_model($param = array('selectedId' => 0)) {
        $this->query = $this->db->query('
            SELECT 
                *
            FROM `gaz_reservation`
            WHERE id = ' . $param['selectedId']);

        if ($this->query->num_rows() > 0) {
            $this->result = $this->query->result();
            return $this->result['0'];
        }
        return false;
    }
    
}
