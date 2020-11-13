<?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-confim-reservation', 'enctype' => 'multipart/form-data')); ?>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <?php echo form_label('Харилцагч', 'Харилцагч', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php echo $controlPartnerList; ?>
            </div>
        </div>
        <div class="form-group hide" id="partnername">
            <?php echo form_label('Байгууллагын нэр', 'Байгууллагын нэр', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'partnerTitle',
                    'id' => 'partnerTitle',
                    'placeholder' => 'Байгууллагын нэр',
                    'maxlength' => '100',
                    'class' => 'form-control',
                    'required' => 'required'
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Менежерийн нэр', 'Менежерийн нэр', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'managerName',
                    'id' => 'managerName',
                    'placeholder' => 'Менежерийн нэр',
                    'maxlength' => '100',
                    'class' => 'form-control',
                    'required' => 'required'
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Утас', 'Утас', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'managerPhone',
                    'id' => 'managerPhone',
                    'placeholder' => 'Утас',
                    'maxlength' => '100',
                    'class' => 'form-control',
                    'required' => 'required'
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Мэйл хаяг', 'Мэйл хаяг', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'managerEmail',
                    'id' => 'managerEmail',
                    'placeholder' => 'Мэйл хаяг',
                    'maxlength' => '100',
                    'class' => 'form-control',
                    'required' => 'required'
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Аялалын код', 'Аялалын код', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'travelCode',
                    'id' => 'travelCode',
                    'placeholder' => 'Аялалын код',
                    'maxlength' => '100',
                    'class' => 'form-control',
                    'required' => 'required'
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Тайлбар', 'Тайлбар', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_textarea(array(
                    'name' => 'description',
                    'id' => 'description',
                    'placeholder' => 'Нэмэлт мэдээлэл',
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    function _setPartnerValue(elem) {
        if ($(elem).val() == '0' || $(elem).val() == '') {
            $('#partnername', '#form-confim-reservation').fadeOut().removeClass('hide').addClass('show');
            $('#partnerTitle', '#form-confim-reservation').val('');
            $('#managerName', '#form-confim-reservation').val('');
            $('#managerPhone', '#form-confim-reservation').val('');
            $('#managerEmail', '#form-confim-reservation').val('');
        } else {
            $('#partnername', '#form-confim-reservation').fadeOut().removeClass('show').addClass('hide');
            $.ajax({
                type: 'post',
                url: '<?php echo Sreservation::$path; ?>getPartnerInformation',
                dataType: "json",
                data: {partnerId: $(elem).val()},
                beforeSend: function () {
                    $.blockUI({message: null});
                },
                success: function (data) {
                    $('#partnerTitle', '#form-confim-reservation').val(data.title);
                    $('#managerName', '#form-confim-reservation').val(data.manager_name);
                    $('#managerPhone', '#form-confim-reservation').val(data.manager_phone);
                    $('#managerEmail', '#form-confim-reservation').val(data.email);
                    $.unblockUI();
                }
            });
        }
    }
</script>