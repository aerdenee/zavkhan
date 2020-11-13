<?php
echo form_open('javascript:;', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-doc-out-search', 'enctype' => 'multipart/form-data', 'method' => 'get'));
echo form_hidden('isActive', 0);
?>
<div class="form-group row">
    <div class="col-5">
        <?php echo form_label('Төрөл', 'Төрөл', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
        <?php echo $controlMasterDocTypeListDropdown; ?>
    </div>
    <div class="col-7">

        <?php
        echo form_label('Дугаар/Огноо', 'Дугаар/Огноо', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE));
        echo '<div class="clearfix"></div>';
        echo form_input(array(
            'name' => 'docNumber',
            'id' => 'docNumber',
            'class' => 'form-control text-right mr-1',
            'required' => 'required',
            'style' => 'width:100px; display:inline-block;'
        ));
        echo form_input(array(
            'name' => 'inDate',
            'id' => 'inDate',
            'placeholder' => '____-__-__',
            'maxlength' => '10',
            'class' => 'form-control init-date control-date mr-1',
            'readonly' => true,
            'required' => true
        ));

        echo form_input(array(
            'name' => 'outDate',
            'id' => 'outDate',
            'placeholder' => '____-__-__',
            'maxlength' => '10',
            'class' => 'form-control init-date',
            'readonly' => true,
            'required' => true
        ));
        ?>
    </div>
</div>
<div class="form-group row">

    <div class="col-5">
        <?php echo form_label('Хэнд явуулсан', 'Хэнд явуулсан', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
        <?php echo $controlToHrPeopleDepartmentDropdown; ?>
    </div>
    <div class="col-7">
        <?php echo form_label('Хэнд явуулсан', 'Хэнд явуулсан', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => FALSE)); ?>
        <div data-partner-people-doc-out-bind="init">
            <?php
            if ($controlToPartnerDropdown) {
                echo $controlToPartnerDropdown;
                echo '<input type="hidden" name="toPeopleId" value="' . $row->to_people_id . '">';
            } else if ($controlToHrPeopleListDropdown) {
                echo $controlToHrPeopleListDropdown;
                echo '<input type="hidden" name="toPartnerId" value="' . $row->to_partner_id . '">';
            } else {
                echo '<input type="hidden" name="toPartnerId" value="0">';
                echo '<input type="hidden" name="toPeopleId" value="0">';
                echo '<select class="select2" disabled="disabled"><option> - Сонгох -</option></select>';
            }
            ?>
        </div>
    </div>
</div>
<div class="form-group row mb-0">

    <div class="col-12">
        <?php echo form_label('Түлхүүр үг', 'Түлхүүр үг', array('required' => 'required', 'class' => 'control-label text-left', 'defined' => TRUE)); ?>
        <?php
        echo form_input(array(
            'name' => 'keyword',
            'id' => 'keyword',
            'placeholder' => 'Товч агуулга',
            'maxlength' => '50',
            'class' => 'form-control',
            'required' => 'required'
        ));
        ?>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    function _setActive(elem) {
        $('input[name="isActive"]').val($(elem).val());
    }
</script>