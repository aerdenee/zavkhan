<div class="form-body" id="content-vertical">
    <div class="portlet-body">
        <?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-vertical-photo', 'enctype' => 'multipart/form-data')); ?>
        <?php
        echo form_hidden('id', $row['id']);
        echo form_hidden('modId', $modId);
        echo form_hidden('verticalOldPic', $row['pic_vertical']);
        echo form_hidden('verticalPic');
        echo form_hidden('verticalCropX');
        echo form_hidden('verticalCropY');
        echo form_hidden('verticalCropWidth');
        echo form_hidden('verticalCropHeight');
        ?>
        <div class="form-group" id="verticalPicField">
            <?php echo form_label('Зураг', 'Зураг', array('required' => 'required', 'class' => 'control-label col-lg-2 col-md-2', 'defined'=>TRUE)); ?>
            <div class="col-lg-2">
                <?php
                echo form_upload(array(
                    'name' => 'picVerticalUpload',
                    'id' => 'picVerticalUpload',
                    'class' => 'pull-left'
                ));
                if ($row['pic_vertical'] != '') {
                    echo '<div class="clearfix"></div>';
                    $pic = UPLOADS_CONTENT_PATH . CROP_SMALL . $row['pic_vertical'];
                    echo '<img src="' . (is_file($_SERVER['DOCUMENT_ROOT'] . $pic) ? $pic : '/assets/images/icon-pic.png') . '" class="margin-top-20">';
                }
                ?>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>


<script type="text/javascript">
    var verticalPhotoWindowId = '#content-vertical';
    var verticalPhotoFormId = '#form-vertical-photo';
    $(function () {
        submitPhoto();
    });
    function submitPhoto() {
        $('#picVerticalUpload', verticalPhotoWindowId).on('change', function () {
            $(verticalPhotoFormId, verticalPhotoWindowId).ajaxSubmit({
                type: 'post',
                url: '<?php echo Saccommodation::$path; ?>uploadImage',
                dataType: 'json',
                data: {fieldName: 'picVerticalUpload'},
                beforeSend: function () {
                    $.blockUI({message: null});
                },
                success: function (data) {
                    if (data.status === 'success') {
                        var oldData = $('#verticalPicField', verticalPhotoWindowId).html();
                        var _html = '<div style="height: ' + data.height + 'px; width: 100%; overflow: hidden;"><img src="/upload/image/' + data.response + '" id="main-photo"><br><div class="btn green btn-xs" onclick="saveUploadVerticalPhoto()"><i class="fa fa-save"></i> Хадгалах</div> <div class="btn red btn-xs" onclick="backPhoto();"><i class="fa fa-trash"></i> Болих</div></div>';
                        $('input[name="verticalPic"]', verticalPhotoWindowId).val(data.response);
                        $('#verticalPicField', verticalPhotoWindowId).html(_html);
                        $('#main-photo').cropper({
                            aspectRatio: 3 / 4,
                            done: function (data) {
                                $('input[name="verticalCropX"]', verticalPhotoWindowId).val(data.x);
                                $('input[name="verticalCropY"]', verticalPhotoWindowId).val(data.y);
                                $('input[name="verticalCropWidth"]', verticalPhotoWindowId).val(data.width);
                                $('input[name="verticalCropHeight"]', verticalPhotoWindowId).val(data.height);
                            }
                        });
                        $(".removeUploadVerticalPhoto", verticalPhotoWindowId).on("click", function () {
                            $('#verticalPicField', verticalPhotoWindowId).html(oldData);
                        });
                        $.unblockUI();
                    } else {
                        alert("error");
                    }
                }
            });
        });
    }
    function saveUploadVerticalPhoto() {
        if ($(verticalPhotoFormId, verticalPhotoWindowId).valid()) {
            $.ajax({
                type: 'post',
                url: '<?php echo Saccommodation::$path; ?>updateVerticalImage',
                data: $(verticalPhotoFormId, verticalPhotoWindowId).serialize(),
                dataType: 'json',
                beforeSend: function () {
                    $.blockUI({message: null});
                },
                success: function (data) {
                    if (data.status === 'success') {
                        initVerticalPhoto();
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
    }

    function initVerticalPhoto() {
        $.ajax({
            type: 'post',
            url: '<?php echo Saccommodation::$path; ?>initVerticalPhoto',
            data: {contId: '<?php echo $row['id']; ?>'},
            dataType: 'json',
            beforeSend: function () {
                $.blockUI({message: null});
            },
            success: function (data) {
                var html = '';
                html += '<label for="Зураг" required="required" class="control-label col-md-3">Зураг</label>';
                html += '<div class="col-md-4">';
                html += '<input type="file" name="picVerticalUpload" id="picVerticalUpload" class="pull-left">';
                html += '<div class="clearfix"></div>';
                html += '<img src="<?php echo UPLOADS_CONTENT_PATH . CROP_SMALL; ?>' + data.pic_vertical + '" class="margin-top-20">';
                html += '</div>';
                $("#verticalPicField").html(html);
                $.unblockUI();
            }
        }).done(function () {
            submitPhoto();
        });
    }
</script>
