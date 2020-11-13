<?php 
echo form_open(Surgudul::$path . 'index/' . $modId, array('class' => 'form-horizontal', 'id' => 'form-check-date', 'enctype' => 'multipart/form-data', 'method' => 'get')); 
echo form_hidden('isActive', 0);
?>
<div class="col-md-12">
    <div class="radio-list">
        <label class="radio-inline"><?php echo form_radio(array('name' => 'setActive', 'class' => 'radio'), 1, (0 == 1 ? TRUE : ''), 'onclick="_setActive(this);"'); ?> Хаагдаагүй өргөдөл </label>
        <label class="radio-inline"><?php echo form_radio(array('name' => 'setActive', 'class' => 'radio'), 3, (3 == 1 ? TRUE : ''), 'onclick="_setActive(this);"'); ?> Хаагдсан өргөдөл </label>
        <label class="radio-inline"><?php echo form_radio(array('name' => 'setActive', 'class' => 'radio'), 0, (0 == 0 ? TRUE : ''), 'onclick="_setActive(this);"'); ?> Бүх өргөдөл </label>
    </div>
    <div class="clearfix" style="margin-bottom: 20px;"></div>
</div>
<div class="col-md-5">
    <?php echo form_label('Ангилал', 'Ангилал', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
    <?php echo $controlCategoryListDropdown; ?>
    <div class="clearfix" style="margin-bottom: 20px;"></div>
</div>
<div class="col-md-7">
    <div class="row">
        <div class="col-md-6">
            <?php
            echo form_label('Огноо /эхлэл/', 'Огноо /эхлэл/', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
            echo form_input(array(
                'name' => 'startDate',
                'id' => 'startDate',
                'placeholder' => 'Эхлэх огноо',
                'maxlength' => '10',
                'class' => 'form-control init-date control-date',
                'readonly' => true,
                'required' => true
            ));
            ?>

        </div>
        <div class="col-md-6">
            <?php
            echo form_label('Огноо /дуусах/', 'Огноо /дуусах/', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
            echo form_input(array(
                'name' => 'endDate',
                'id' => 'endDate',
                'placeholder' => 'Дуусах огноо',
                'maxlength' => '10',
                'class' => 'form-control init-date',
                'readonly' => true,
                'required' => true
            ));
            ?>
        </div>
    </div>
    <div class="clearfix" style="margin-bottom: 20px;"></div>
</div>
<div class="clearfix"></div>
<div class="col-md-4">
    <?php echo form_label('Нийслэл/аймаг', 'Нийслэл/аймаг', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
    <?php echo $controlCityDropdown; ?>
    <div class="clearfix" style="margin-bottom: 20px;"></div>
</div>
<div class="col-md-4">
    <?php echo form_label('Дүүрэг/сум', 'Дүүрэг/сум', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
    <span id="address-soum-html"><?php echo $controlSoumDropdown; ?></span>
    <div class="clearfix" style="margin-bottom: 20px;"></div>
</div>
<div class="col-md-4">
    <?php echo form_label('Хороо/баг', 'Хороо/баг', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
    <span id="address-street-html"><?php echo $controlStreetDropdown; ?></span>
    <div class="clearfix" style="margin-bottom: 20px;"></div>
</div>
<div class="clearfix"></div>
<div class="col-md-4">
    <?php echo form_label('Бүртгэлийн дугаар', 'Бүртгэлийн дугаар', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE)); ?>
    <?php
    echo form_input(array(
        'name' => 'createNumber',
        'id' => 'createNumber',
        'placeholder' => date('Ymd') . '001 гэх мэт',
        'maxlength' => '50',
        'class' => 'form-control',
        'required' => 'required'
    ));
    ?>
    <div class="clearfix" style="margin-bottom: 20px;"></div>
</div>
<div class="col-md-8">
    <?php echo form_label('Түлхүүр үг', 'Түлхүүр үг', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE)); ?>
    <?php
    echo form_input(array(
        'name' => 'keyword',
        'id' => 'keyword',
        'placeholder' => 'Овог, нэр, утасны дугаар, бусад үгээр хайлт хийнэ',
        'maxlength' => '50',
        'class' => 'form-control',
        'required' => 'required'
    ));
    ?>
    <div class="clearfix" style="margin-bottom: 20px;"></div>
</div>
<div class="col-md-6">
    <?php echo form_label('Өргөдөл шилжүүлжсэн байгууллага', 'Өргөдөл шилжүүлжсэн байгууллага', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
    <?php echo $controlUrgudulDirectDropDown; ?>
    <div class="clearfix" style="margin-bottom: 20px;"></div>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    function _setActive(elem) {
        $('input[name="isActive"]').val($(elem).val());
    }
</script>