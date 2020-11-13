<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">Зураг солих</h5>

    </div>
    <div class="card-body">
        <?php
        echo form_open('javascript:;', array('id' => 'form-profile', 'enctype' => 'multipart/form-data'));
        echo form_hidden('oldPic', ltrim($row->pic, CROP_SMALL));
        echo form_hidden('pic');
        ?>
        <div class="row">
            <div class="col-md-12">
                <?php
                if ($flash != NULL) {
                    echo '<div class="row">';

                    if ($flash['status'] == 'success') {
                        echo '<div class="alert alert-success alert-styled-left alert-arrow-left alert-dismissible w-100">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                            ' . $flash['message'] . '
                        </div>';
                    } else {
                        echo '<div class="alert alert-danger alert-styled-left alert-dismissible w-100">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                            ' . $flash['message'] . '
                        </div>';
                    }
                    echo '</div>';
                }
                ?>

                <div class="row">
                    <div class="pull-left mr-3 _profile-image-show">

                        <div class="_delete-button" onclick="_imageDelete({
                                    table: 'user',
                                    formId: _profileFormMainId,
                                    appendHtmlClass: '._profile-image',
                                    selectedId: <?php echo $row->id; ?>,
                                    uploadPath: UPLOADS_USER_PATH,
                                    fileName: $('._profile-image').attr('data-image').replace('s_', '')});"></div>

                        <div class="_photo"><img src="<?php echo UPLOADS_USER_PATH . $row->pic; ?>" data-image="<?php echo ltrim($row->pic, CROP_SMALL);?>" class="_profile-image"></div>

                    </div>

                    <div class="pull-left">

                        <?php
                        echo '<div style="position: relative; display: inline-block;" class="_profile-image-control">';

                        echo '<span class="_upload-button">';
                        echo '<div class="uploader">';
                        echo form_upload(array(
                            'name' => 'picUpload',
                            'id' => 'picUpload',
                            'class' => 'pull-left file-styled',
                            'onchange' => '_imageProfileUpload({elem: this, uploadPath: UPLOADS_USER_PATH, formId: _profileFormMainId, appendHtmlClass: \'._profile-image\'});',
                        ));
                        echo '</div>';
                        echo '</span>';

                        echo '</div>';
                        ?>
                        <span class="help-block">Хуулах зургийн хэмжээ: <?php echo formatInBytes(UPLOAD_PROFILE_PHOTO_MAX_SIZE); ?></span>
                        <?php
                        echo form_button(array(
                            'class' => 'btn btn-primary mt-2',
                            'content' => 'Хадгалах <i class="icon-paperplane ml-1"></i>',
                            'onclick' => '_updatePhoto({elem:this});'));
                        ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

        </div>

        <?php echo form_close(); ?>
    </div>
</div>