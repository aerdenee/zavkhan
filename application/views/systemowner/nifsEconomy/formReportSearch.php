<?php
$this->html = '';
$this->html .= form_open(Scrime::$path . $path . '/' . $modId, array('class' => 'form-horizontal', 'id' => 'form-check-date', 'enctype' => 'multipart/form-data', 'method' => 'get'));
$this->html .= '<div class="row">';
if ($this->session->userdata('adminAccessTypeId') == 1) {
    $this->html .= '<div class="col-md-5">';
        $this->html .= form_label('Дүүрэг', 'Дүүрэг', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
        $this->html .= $controlDepartmentCategoryDropdown;
        $this->html .= '<div class="clearfix" style="margin-bottom: 20px;"></div>';
    $this->html .= '</div>';
    $this->html .= '<div class="col-md-7">';
        $this->html .= '<div class="row">';
            $this->html .= '<div class="col-md-6">';
                $this->html .= form_label('Огноо /эхлэл/', 'Огноо /эхлэл/', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
                $this->html .= form_input(array(
                        'name' => 'startDate',
                        'id' => 'startDate',
                        'placeholder' => 'Эхлэх огноо',
                        'maxlength' => '10',
                        'class' => 'form-control',
                        'readonly' => true,
                        'required' => true));
                $this->html .= '</div>';
                $this->html .= '<div class="col-md-6">';
                    $this->html .= form_label('Огноо /дуусах/', 'Огноо /дуусах/', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
                    $this->html .= form_input(array(
                        'name' => 'endDate',
                        'id' => 'endDate',
                        'placeholder' => 'Дуусах огноо',
                        'maxlength' => '10',
                        'class' => 'form-control',
                        'readonly' => true,
                        'required' => true));
                $this->html .= '</div>';
            $this->html .= '</div>';
            $this->html .= '<div class="clearfix" style="margin-bottom: 20px;"></div>';
    $this->html .= '</div>';
} else {
    $this->html .= '<div class="col-md-6">';
        $this->html .= form_label('Огноо /эхлэл/', 'Огноо /эхлэл/', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
        $this->html .= form_input(array(
            'name' => 'startDate',
            'id' => 'startDate',
            'placeholder' => 'Эхлэх огноо',
            'maxlength' => '10',
            'class' => 'form-control init-date control-date',
            'readonly' => true,
            'required' => true));
    $this->html .= '</div>';
    $this->html .= '<div class="col-md-6">';
        $this->html .= form_label('Огноо /дуусах/', 'Огноо /дуусах/', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
        $this->html .= form_input(array(
            'name' => 'endDate',
            'id' => 'endDate',
            'placeholder' => 'Дуусах огноо',
            'maxlength' => '10',
            'class' => 'form-control init-date',
            'readonly' => true,
            'required' => true));
    $this->html .= '</div>';
}
$this->html .= '</div>';
$this->html .= form_close();

echo $this->html;