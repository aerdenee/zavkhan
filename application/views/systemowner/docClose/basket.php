<style type="text/css">
    .col-12,
    .col-3, 
    .col-6, 
    .col-9 {
        padding-right: 0.1rem;
        padding-left: 0.1rem;
    }
    .row {
        margin-right: -0.1rem;
        margin-left: -0.1rem;
    }
    label {
        margin-bottom: 0.2rem;
    }
    .form-group {
        margin-bottom: 10px;
    }
</style>
<?php
echo form_open('', array('class' => 'col-12', 'id' => 'form-doc-close', 'enctype' => 'multipart/form-data'));
echo form_hidden('docDetialId', $docDetialId);
echo form_hidden('docCloseId', $docCloseId);
echo form_hidden('type', $type);
?>
<div class="row">
    <div class="col-3">
        <div class="form-group row">
            <?php echo form_label('Огноо', 'Огноо', array('required' => 'required', 'class' => 'control-label text-left col-12', 'defined' => FALSE)); ?>
            <div class="col-12">
                <?php
                echo form_input(array(
                    'name' => 'inDate',
                    'id' => 'inDate',
                    'placeholder' => '____-__-__',
                    'maxlength' => '10',
                    'class' => 'form-control init-date control-date mr-1',
                    'readonly' => true
                ));

                echo form_input(array(
                    'name' => 'outDate',
                    'id' => 'outDate',
                    'placeholder' => '____-__-__',
                    'maxlength' => '10',
                    'class' => 'form-control init-date mr-2',
                    'readonly' => true
                ));
                ?>    
            </div>
        </div>
        <div class="form-group row">
            <?php echo form_label('Төрөл', 'Төрөл', array('required' => 'required', 'class' => 'control-label text-left col-12', 'defined' => FALSE)); ?>
            <div class="col-12"><?php echo $controlMasterDocTypeListDropdown; ?></div>
        </div>
        <div class="form-group row">
            <?php echo form_label('Албан бичгийн дугаар', 'Албан бичгийн дугаар', array('required' => 'required', 'class' => 'control-label col-12', 'defined' => FALSE)); ?>
            <div class="col-12">
                <?php
                echo form_input(array(
                    'name' => 'docNumber',
                    'id' => 'docNumber',
                    'class' => 'form-control'
                ));
                ?>    
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Хаанаас', 'Хаанаас', array('required' => 'required', 'class' => 'control-label text-left col-12', 'defined' => TRUE)); ?>
            <div class="col-12"><?php echo $controlHrPeopleDepartmentDropdown; ?></div>
        </div>
        <div class="form-group row">
            <div class="col-12" id="init-control-partner-people-doc-close-html">
                <?php
                if ($controlPartnerDropdown) {
                    echo $controlPartnerDropdown;
                    echo '<input type="hidden" name="peopleId" value="' . $row->from_people_id . '">';
                } else if ($controlHrPeopleListDropdown) {
                    echo $controlHrPeopleListDropdown;
                    echo '<input type="hidden" name="partnerId" value="' . $row->from_partner_id . '">';
                } else {
                    echo '<input type="hidden" name="partnerId" value="0">';
                    echo '<input type="hidden" name="peopleId" value="0">';
                    echo '<select class="select2" disabled="disabled"><option> - Сонгох -</option></select>';
                }
                ?>
            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Албан бичгийн агуулга', 'Албан бичгийн агуулга', array('required' => 'required', 'class' => 'control-label text-left col-12', 'defined' => FALSE)); ?>
            <div class="col-12">
                <?php
                echo form_input(array(
                    'name' => 'keyword',
                    'id' => 'keyword',
                    'placeholder' => 'Товч агуулга...',
                    'class' => 'form-control'));
                ?>
            </div>

        </div>

        <div class="form-group row">
            <div class="col-12">
                <?php
                echo form_button(array(
                    'name' => 'search',
                    'type' => 'button',
                    'class' => 'btn btn-primary mr-2',
                    'content' => '<i class="fa fa-search"></i> Хайх'));

                echo form_button(array(
                    'name' => 'reset',
                    'type' => 'button',
                    'class' => 'btn btn-default',
                    'content' => '<i class="fa fa-search"></i> Цэвэрлэх'));
                ?>
            </div>
        </div>

    </div>
    <div class="col-9 pl-2">
        <div class="form-group">
            <label>Хаалт хийсэн албан бичиг:</label>
            <span id="selected-basket-doc"></span>

        </div>
        <table id="dgDocClose" style="width:100%;"></table>
    </div>
</div>

<?php echo form_close(); ?>