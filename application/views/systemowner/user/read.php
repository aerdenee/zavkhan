<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-user', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('catId', $row->cat_id);
echo form_hidden('partnerId', $row->partner_id);
echo form_hidden('modId', $row->mod_id);
echo form_hidden('oldPic', ltrim($row->pic, 's_'));
echo form_hidden('pic');
echo form_hidden('introTextMn');
echo form_hidden('introTextEn');
?>
<fieldset>
    <div class="form-group">
        <?php echo form_label('Газар, хэлтэс', 'Газар, хэлтэс', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-9">
            <?php echo $controlHrPeopleDepartmentDropdown; ?>
        </div>
    </div>
    <div id="hr-people-department-people-list-dropdown-html">
        <div class="form-group">
            <?php echo form_label('Ажилтан', 'Ажилтан', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-9">
                <?php echo $controlHrPeopleDepartmentPeopleListDropdown; ?>
            </div>
        </div>

    </div>

    <div class="form-group">
        <?php echo form_label('Эцгийн нэр', 'Эцгийн нэр', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                'name' => 'lnameMn',
                'id' => 'lnameMn',
                'value' => $row->lname_mn,
                'maxlength' => '100',
                'class' => 'form-control',
                'readonly' => true
            ));
            echo form_hidden('lnameEn');
            ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo form_label('Өөрийн нэр', 'Өөрийн нэр', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                'name' => 'fnameMn',
                'id' => 'fnameMn',
                'value' => $row->fname_mn,
                'maxlength' => '100',
                'class' => 'form-control',
                'readonly' => true
            ));
            echo form_hidden('fnameEn');
            ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo form_label('Мэйл хаяг', 'Мэйл хаяг', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                'name' => 'email',
                'id' => 'email',
                'value' => $row->email,
                'maxlength' => '100',
                'class' => 'form-control',
                'readonly' => true
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo form_label('Утас', 'Утас', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                'name' => 'phone',
                'id' => 'phone',
                'value' => $row->phone,
                'maxlength' => '100',
                'class' => 'form-control',
                'readonly' => true
            ));
            ?>
        </div>
    </div>
</fieldset>
<fieldset>
    <legend>Нэвтрэх мэдээлэл:</legend>
    <div class="form-group">
        <?php echo form_label('Нэвтрэх нэр', 'Нэвтрэх нэр', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                'name' => 'user',
                'id' => 'user',
                'value' => $row->user,
                'maxlength' => '50',
                'class' => 'form-control',
                'readonly' => true
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo form_label('Нэвтрэх эрх', 'Нэвтрэх эрх', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-9">
            <?php echo $controlUserAccessTypeRadioButton; ?>
        </div>
    </div>

    <?php echo form_hidden('isActiveMn'); ?>
    <?php echo form_hidden('isActiveEn'); ?>
</fieldset>
<?php echo form_close(); ?>
