<?php
echo form_open('', array('class' => 'form-horizontal col-12', 'id' => 'form-doc-file', 'enctype' => 'multipart/form-data'));
echo form_hidden('docDetialId', $docDetialId);
echo form_hidden('modId', $modId);
?>

<div class="form-group row">
    <div class="col-12">
        <?php echo $controlHrPeopleDepartmentDropdown;?>
    </div>
</div>

<div class="form-group row">
    <div class="col-12">
        <?php echo $controlHrPeopleListDropdown;?>
    </div>
</div>

<?php echo form_close(); ?>

