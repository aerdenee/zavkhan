<?php
echo form_open('', array('class' => 'form-horizontal col-md-12', 'id' => 'form-user', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('catId', $row->cat_id);
echo form_hidden('partnerId', $row->partner_id);
echo form_hidden('modId', $row->mod_id);
echo form_hidden('oldPic', ltrim($row->pic, CROP_SMALL));
echo form_hidden('pic');
echo form_hidden('introText');
?>
<fieldset>
    <div class="form-group row">
        <?php echo form_label('Газар, хэлтэс', 'Газар, хэлтэс', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-9">
            <?php echo $controlHrPeopleDepartmentDropdown; ?>
        </div>
    </div>
    <div id="hr-people-department-people-list-dropdown-html"></div>

    <div class="form-group row">
        <?php echo form_label('Эцгийн нэр', 'Эцгийн нэр', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                'name' => 'lname',
                'id' => 'lname',
                'value' => $row->lname,
                'maxlength' => '100',
                'class' => 'form-control',
                'required' => 'required'
            ));
            ?>
        </div>
    </div>
    <div class="form-group row">
        <?php echo form_label('Өөрийн нэр', 'Өөрийн нэр', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                'name' => 'fname',
                'id' => 'fname',
                'value' => $row->fname,
                'maxlength' => '100',
                'class' => 'form-control',
                'required' => 'required'
            ));
            ?>
        </div>
    </div>
    <div class="form-group row">
        <?php echo form_label('Мэйл хаяг', 'Мэйл хаяг', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                'name' => 'email',
                'id' => 'email',
                'value' => $row->email,
                'maxlength' => '100',
                'class' => 'form-control',
                'required' => 'required'
            ));
            ?>
        </div>
    </div>
    <div class="form-group row">
        <?php echo form_label('Утас', 'Утас', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                'name' => 'phone',
                'id' => 'phone',
                'value' => $row->phone,
                'maxlength' => '100',
                'class' => 'form-control',
                'required' => 'required'
            ));
            ?>
        </div>
    </div>
</fieldset>
<fieldset>
    <legend>Нэвтрэх мэдээлэл:</legend>
    <div class="form-group row">
        <?php echo form_label('Нэвтрэх нэр', 'Нэвтрэх нэр', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                'name' => 'user',
                'id' => 'user',
                'value' => $row->user,
                'maxlength' => '50',
                'class' => 'form-control',
                'required' => 'required',
                'readonly' => true
            ));
            ?>
        </div>
    </div>
    
    <div class="form-group row">
        <?php echo form_label('Нууц үг', 'Нууц үг', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-9"><a href="javascript:;" class="btn btn-link" onclick="_setUserPassword({userId:<?php echo $row->id; ?>, _this: this});" id="change-password" data-type="password" data-inputclass="form-control" data-pk="<?php echo $row->id; ?>" data-title="Нууц үг солих"><i class="fa fa-user"></i> Нууц үг солих</a></div>
    </div>
    <div class="form-group row">
        <?php echo form_label('Permission', 'Permission', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-9">
            <a href="javascript:;" class="btn btn-link editable editable-click" data-title="Системд нэвтрэх" onclick="_setUserPermissionForm({userId: <?php echo $row->id; ?>, elem: this, modId: <?php echo $row->mod_id; ?>, createdUserId: <?php echo $row->created_user_id; ?>});"><i class="fa fa-cog"></i> Системд нэвтрэх</a>
            <a href="javascript:;" class="btn btn-link editable editable-click" data-title="Системд нэвтрэх" onclick="_removeUserPermission({id: <?php echo $row->id; ?>, elem: this, modId: <?php echo $row->mod_id; ?>, createdUserId: <?php echo $row->created_user_id; ?>});"><i class="fa fa-trash"></i> Бүх тохиргоог цэвэрлэх</a>
        </div>
    </div>
    <div class="form-group row">
        <?php echo form_label('Нэвтрэх эрх', 'Нэвтрэх эрх', array('required' => 'required', 'class' => 'control-label col-md-3 text-right', 'defined' => TRUE)); ?>
        <div class="col-md-9">
            <?php echo $controlUserAccessDropdown; ?>
        </div>
    </div>

</fieldset>
<?php echo form_close(); ?>
