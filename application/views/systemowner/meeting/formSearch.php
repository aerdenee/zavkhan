<?php
echo form_open(Sdocument::$path . 'index/' . $modId, array('class' => 'form-horizontal', 'id' => 'form-check-date', 'enctype' => 'multipart/form-data', 'method' => 'get'));
echo form_hidden('isActive', 0);
?>
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
                'name' => 'generateDate',
                'id' => 'generateDate',
                'placeholder' => 'Эхлэх огноо',
                'maxlength' => '10',
                'class' => 'form-control init-date control-date',
                'readonly' => true,
                'required' => true
            ));
            ?>

        </div>
        <div class="col-md-6">
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
    <?php echo $controlSoumDropdown; ?>
    <div class="clearfix" style="margin-bottom: 20px;"></div>
</div>
<div class="col-md-4">
    <?php echo form_label('Хороо/баг', 'Хороо/баг', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
    <?php echo $controlStreetDropdown; ?>
    <div class="clearfix" style="margin-bottom: 20px;"></div>
</div>
<div class="clearfix"></div>
<div class="col-md-5">
    <?php echo form_label('Ирсэн/явуулсан байгууллага', 'Ирсэн/явуулсан байгууллага', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
    <?php echo $controlPartnerDropdown; ?>
    <div class="clearfix" style="margin-bottom: 20px;"></div>
</div>
<div class="col-md-7">
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

<?php echo form_close(); ?>

<script type="text/javascript">
    function _setActive(elem) {
        $('input[name="isActive"]').val($(elem).val());
    }
</script>