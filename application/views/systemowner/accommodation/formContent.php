<?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-main', 'enctype' => 'multipart/form-data')); ?>
<?php
echo form_hidden('id', $row['id']);
echo form_hidden('modId', $row['mod_id']);
echo form_hidden('oldPic', $row['pic']);
echo form_hidden('organizationId', $row['organization_id']);
echo form_hidden('accommodationTypeId', $row['accommodation_type_id']);
echo form_hidden('accommodationClassId', $row['accommodation_class_id']);
echo form_hidden('accommodationBedId', $row['accommodation_bed_id']);
echo form_hidden('pic');
echo form_hidden('crop_x');
echo form_hidden('crop_y');
echo form_hidden('crop_width');
echo form_hidden('crop_height');
?>
<div class="clearfix margin-top-20"></div>
<div class="row">
    <div class="col-md-6 text-left">

    </div>
    <div class="col-md-6 text-right">
        <?php
        echo form_button('send', '<i class="fa fa-save"></i> Хадгалах', 'class="btn btn-success btn-xs" onclick="saveForm();"', 'button');
        ?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="tabbable">
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="active"><a href="#tabContentMongolia" data-toggle="tab">Монгол</a></li>
        <li><a href="#tabContentEnglish" data-toggle="tab">English</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tabContentMongolia">
            <div class="form-group" id="picField">
                <?php echo form_label('Зураг', 'Зураг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined'=>TRUE)); ?>
                <div class="col-lg-6">
                    <?php
                    echo form_upload(array(
                        'name' => 'picUpload',
                        'id' => 'picUpload',
                        'class' => 'pull-left',
                        'onchange' => 'uploadImage()'
                    ));
                    if ($row['pic'] != '') {
                        echo '<div class="clearfix"></div>';
                        $pic = UPLOADS_CONTENT_PATH . CROP_SMALL . $row['pic'];
                        echo '<img src="' . (is_file($_SERVER['DOCUMENT_ROOT'] . $pic) ? $pic : '/assets/images/icon-pic.png') . '" class="margin-top-20" style="max-width:100%; width:auto;">';
                    }
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Хамаарах байгууллага', 'Хамаарах байгууллага', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined'=>TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo $controlRadioBtnCamp;
                    ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo form_label('Сууцны төрөл', 'Сууцны төрөл', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined'=>TRUE)); ?>
                <div class="col-lg-5">
                    <?php echo $controlRadioBtnAccommodationType;?>
                </div>
            </div>
            
            <div class="form-group">
                <?php echo form_label('Сууцны ангилал', 'Сууцны ангилал', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined'=>TRUE)); ?>
                <div class="col-lg-5">
                    <?php echo $controlRadioBtnAccommodationClass;?>
                </div>
            </div>
            
            <div class="form-group">
                <?php echo form_label('Орны тоо', 'Орны тоо', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined'=>TRUE)); ?>
                <div class="col-lg-5">
                    <?php echo $controlRadioBtnAccommodationBed;?>
                </div>
            </div>
            
            
            <div class="form-group">
                <?php echo form_label('Сууцны дугаар', 'Сууцны дугаар', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined'=>TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'accommodationCodeMn',
                        'id' => 'accommodationCodeMn',
                        'value' => $row['accommodation_code_mn'],
                        'maxlength' => '250',
                        'class' => 'form-control',
                        'required' => 'required'
                    ));
                    ?>
                </div>
            </div>
            
            <div class="form-group">
                <?php echo form_label('Сууцны үнэ', 'Сууцны үнэ', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined'=>TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'price',
                        'id' => 'price',
                        'value' => $row['price'],
                        'maxlength' => '10',
                        'class' => 'form-control',
                        'required' => 'required'
                    ));
                    ?>
                </div>
            </div>
            
            <div class="form-group">
                <?php echo form_label('Эрэмбэ', 'Эрэмбэ', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined'=>TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'orderNum',
                        'id' => 'orderNum',
                        'value' => $row['order_num'],
                        'maxlength' => '10',
                        'class' => 'form-control integer',
                        'required' => 'required'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined'=>TRUE)); ?>
                <div class="col-lg-5">
                    <div class="radio-list">
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive'), '1', ($row['is_active'] == 1 ? TRUE : '')); ?>
                            Нээх </label>
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive'), '0', ($row['is_active'] == 0 ? TRUE : '')); ?>
                            Хаах </label>
                    </div>
                    <div id="form_2_membership_error">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <?php echo form_label('Тайлбар', 'Тайлбар', array('class' => 'control-label col-lg-2 text-right', 'defined'=>TRUE)); ?>
                <div class="col-lg-10">
                    <?php
                    echo form_textarea(array(
                        'name' => 'descriptionMn',
                        'id' => 'descriptionMn',
                        'value' => $row['description_mn'],
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6 text-left">
                </div>
                <div class="col-md-6 text-right">
                    <?php
                    echo form_button('send', '<i class="fa fa-save"></i> Хадгалах', 'class="btn btn-success btn-xs" onclick="saveForm();"', 'button');
                    ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="tab-pane" id="tabContentEnglish">
            <div class="form-group">
                <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined'=>TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'accommodationCodeEn',
                        'id' => 'accommodationCodeEn',
                        'value' => $row['accommodation_code_en'],
                        'maxlength' => '250',
                        'class' => 'form-control'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Агуулга', 'Агуулга', array('class' => 'control-label col-lg-2 text-right', 'defined'=>TRUE)); ?>
                <div class="col-lg-10">
                    <?php
                    echo form_textarea(array(
                        'name' => 'descriptionEn',
                        'id' => 'descriptionEn',
                        'value' => $row['description_en'],
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php echo form_close(); ?>

<script type="text/javascript">
    var windowId = '#content-main';
    var formId = '#form-main';
    $(function () {
        // Full featured editor
        CKEDITOR.replace('descriptionMn', {
            height: '400px',
            extraPlugins: 'forms'
        });
        CKEDITOR.replace('descriptionEn', {
            height: '400px',
            extraPlugins: 'forms'
        });
    });
    function uploadImage() {
        $(formId).ajaxSubmit({
            type: 'post',
            url: '<?php echo Saccommodation::$path; ?>uploadImage',
            dataType: 'json',
            data: {fieldName: 'picUpload'},
            beforeSend: function () {
                $.blockUI({message: null});
            },
            success: function (data) {

                if (data.status === 'success') {
                    var oldData = $('#picField').html();
                    var _html = '<div style="height: ' + data.height + 'px; width: 100%; overflow: hidden;"><img src="<?php echo UPLOADS_CONTENT_PATH; ?>' + data.response + '" id="main-photo" style="max-width:' + data.width + 'px;"><br><div class="btn red btn-xs red removeUploadPhoto" onclick="removeImage(\'/upload/image/' + data.response + '\')"><i class="fa fa-trash"></i> Болих</div></div>';
                    $('input[name="pic"]').val(data.response);
                    $('#picField').html(_html);
                    $('#main-photo').cropper({
                        aspectRatio: 4 / 2,
                        done: function (data) {
                            $('input[name="crop_x"]').val(data.x);
                            $('input[name="crop_y"]').val(data.y);
                            $('input[name="crop_width"]').val(data.width);
                            $('input[name="crop_height"]').val(data.height);
                        }
                    });

                    $(".removeUploadPhoto", windowId).on("click", function () {
                        $('#picField', windowId).html(oldData);
                    });
                } else {
                    alert(data.response);
                }
                $.unblockUI();
            }
        });
    }
    function saveForm() {
        $(formId, windowId).validate({errorPlacement: function () {}});
        if ($(formId, windowId).valid()) {
            CKEDITOR.instances['descriptionMn'].updateElement();
            $("#descriptionMn").val(CKEDITOR.instances['descriptionMn'].getData());
            
            CKEDITOR.instances['descriptionEn'].updateElement();
            $("#descriptionEn").val(CKEDITOR.instances['descriptionEn'].getData());
            $.ajax({
                type: 'post',
                url: '<?php echo Saccommodation::$path . $mode; ?>',
                data: $(formId, windowId).serialize(),
                dataType: 'json',
                beforeSend: function () {
                    $.blockUI({message: null});
                },
                success: function (data) {
                    if (data.status === 'success') {
                        new PNotify({
                            title: data.title,
                            text: data.message,
                            addclass: 'bg-success'
                        });
                        window.location.href = '<?php echo Saccommodation::$path . 'index/' . $modId; ?>';
                    } else {
                        new PNotify({
                            title: data.title,
                            text: data.message,
                            addclass: 'bg-danger'
                        });
                    }
                    $.unblockUI();
                }
            });
        }
    }
    function removeImage(image) {
        $.ajax({
            type: 'post',
            url: '<?php echo Saccommodation::$path; ?>/removeImage',
            data: {image: image},
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({message: null});
            },
            success: function (data) {
                if (data.status === 'success') {
                    new PNotify({
                        title: data.title,
                        text: data.message,
                        addclass: 'bg-success'
                    });
                } else {
                    new PNotify({
                        title: data.title,
                        text: data.message,
                        addclass: 'bg-danger'
                    });
                }
                $.unblockUI();
            }
        });
    }
    function changeValueRadio(elem, controlName) {
        $('input[name="' + controlName + '"]').val($(elem).val());
    }
</script>
