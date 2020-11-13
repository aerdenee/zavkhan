<?php
echo form_open('', array('class' => 'form-horizontal p-0', 'id' => 'form-partner', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
echo form_hidden('oldPic', ltrim($row->pic, CROP_SMALL));
echo form_hidden('pic');
echo form_hidden('oldCover', ltrim($row->cover, CROP_SMALL));
echo form_hidden('cover');
echo form_hidden('catId', $row->cat_id);
?>
<div id="accordion-group">
    <div class="card mb-0 card rounded-top-0 rounded-bottom-0">
        <div class="card-header">
            <h6 class="card-title">
                <a data-toggle="collapse" class="text-default" href="#accordion-item-group1">Агуулга</a>
            </h6>
        </div>

        <div id="accordion-item-group1" class="collapse show" data-parent="#accordion-group">
            <div class="card-body col-md-12">

                <div class="form-group row">
                    <?php echo form_label('Хамаарал', 'Хамаарал', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-10">
                        <?php echo $controlPartnerParentMultiRowDropdown; ?>

                    </div>
                </div>
                <div class="form-group row">
                    <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-10">
                        <?php
                        echo form_input(array(
                            'name' => 'title',
                            'id' => 'title',
                            'value' => $row->title,
                            'maxlength' => '500',
                            'class' => 'form-control',
                            'required' => 'required'
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <?php echo form_label('Гарчиг тайлбар', 'Гарчиг тайлбар', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-10">
                        <?php
                        echo form_input(array(
                            'name' => 'linkTitle',
                            'id' => 'linkTitle',
                            'value' => $row->link_title,
                            'maxlength' => '500',
                            'class' => 'form-control'
                        ));
                        ?>
                    </div>
                </div>

                <div class="form-group row">
                    <?php echo form_label('Менежерийн нэр', 'Менежерийн нэр', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-10">
                        <?php
                        echo form_input(array(
                            'name' => 'managerName',
                            'id' => 'managerName',
                            'value' => $row->manager_name,
                            'maxlength' => '500',
                            'class' => 'form-control'
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <?php echo form_label('Менежерийн утас', 'Менежерийн утас', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-10">
                        <?php
                        echo form_input(array(
                            'name' => 'managerPhone',
                            'id' => 'managerPhone',
                            'value' => $row->manager_phone,
                            'maxlength' => '500',
                            'class' => 'form-control'
                        ));
                        ?>
                        <span class="help-block">Олон дугаар бичиж болно. Жишээ нь: 9911xxxx, 8811xxxx, 9111xxxx</span>
                    </div>
                </div>
                <div class="form-group row">
                    <?php echo form_label('Байгууллагын мэйл хаяг', 'Байгууллагын мэйл хаяг', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-10">
                        <?php
                        echo form_input(array(
                            'name' => 'email',
                            'id' => 'email',
                            'value' => $row->email,
                            'maxlength' => '500',
                            'class' => 'form-control'
                        ));
                        ?>
                        <span class="help-block">Зөвхөн нэг мэйл хаяг бичнэ</span>
                    </div>
                </div>
                <div class="form-group row">
                    <?php echo form_label('Байгууллагын утас', 'Байгууллагын утас', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-10">
                        <?php
                        echo form_input(array(
                            'name' => 'phone',
                            'id' => 'phone',
                            'value' => $row->phone,
                            'maxlength' => '500',
                            'class' => 'form-control'
                        ));
                        ?>
                        <span class="help-block">Олон дугаар бичиж болно. Жишээ нь: 9911xxxx, 8811xxxx, 9111xxxx</span>
                    </div>
                </div>

                <div class="form-group row">
                    <?php echo form_label('Хаяг', 'Хаяг', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-10">
                        <div class="pull-left address-city-html" style="padding-right: 20px; min-width: 200px;">
                            <?php echo $controlCityDropdown; ?>
                            <span class="help-block">Нийслэл, аймаг</span>
                        </div>
                        <div class="pull-left address-soum-html" style="padding-right: 20px; min-width: 200px;">
                            <?php echo $controlSoumDropdown; ?>
                            <span class="help-block">Дүүрэг, сум</span>
                        </div>
                        <div class="pull-left address-street-html" style="padding-right: 20px; min-width: 200px;">
                            <?php echo $controlStreetDropdown; ?>
                            <span class="help-block">Хороо, баг</span>
                        </div>
                    </div>
                </div>
                <div class="form-group row <?php echo ($row->address != '' ? '' : 'hide'); ?> address-detial-html">
                    <?php echo form_label(' ', ' ', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => FALSE)); ?>
                    <div class="col-10">
                        <?php
                        echo form_textarea(array(
                            'name' => 'address',
                            'id' => 'address',
                            'value' => $row->address,
                            'maxlength' => '500',
                            'class' => 'form-control',
                            'row' => 3,
                            'style' => 'max-height: 150px;'
                        ));
                        ?>
                        <span class="help-block">Хаягийн талаар дэлгэрэнгүй мэдээлэл</span>
                    </div>
                </div>

                <div class="form-group row">
                    <?php echo form_label('Тайлбар', 'Тайлбар', array('class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-10">
                        <?php
                        echo form_textarea(array(
                            'name' => 'description',
                            'id' => 'description',
                            'value' => $row->description,
                            'rows' => 4,
                            'class' => 'ckeditor'
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-0 rounded-0 border-y-0">
        <div class="card-header">
            <h6 class="card-title">
                <a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-group2">Хайлтын систем</a>
            </h6>
        </div>

        <div id="accordion-item-group2" class="collapse" data-parent="#accordion-group">
            <div class="card-body">

                <div class="form-group row">
                    <?php echo form_label('Хуудасны гарчиг', 'Хуудасны гарчиг', array('required' => 'required', 'class' => 'control-label col-2', 'defined' => FALSE)); ?>
                    <div class="col-10">
                        <?php
                        echo form_textarea(array(
                            'name' => 'pageTitle',
                            'id' => 'pageTitle',
                            'value' => $row->page_title,
                            'maxlength' => '500',
                            'rows' => 4,
                            'class' => 'form-control'
                        ));
                        ?>
                        <span class="help-block">Web browser-н title bar дээр харагдах үг</span>
                    </div>
                </div>
                <div class="form-group row">
                    <?php echo form_label('Хуудасны гол агуулга', 'Хуудасны гол агуулга', array('required' => 'required', 'class' => 'control-label text-right col-2', 'defined' => FALSE)); ?>
                    <div class="col-10">
                        <?php
                        echo form_textarea(array(
                            'name' => 'h1Text',
                            'id' => 'h1Text',
                            'value' => $row->h1_text,
                            'maxlength' => '500',
                            'rows' => 4,
                            'class' => 'form-control'
                        ));
                        ?>
                        <span class="help-block">h1 text</span>
                    </div>
                </div>
                <div class="form-group row">
                    <?php echo form_label('Түлхүүр үг', 'Түлхүүр үг', array('required' => 'required', 'class' => 'control-label text-right col-2', 'defined' => FALSE)); ?>
                    <div class="col-10">
                        <?php
                        echo form_textarea(array(
                            'name' => 'metaKey',
                            'id' => 'metaKey',
                            'value' => $row->meta_key,
                            'maxlength' => '500',
                            'rows' => 4,
                            'class' => 'form-control'
                        ));
                        ?>
                        <span class="help-block">Хайлтын системд бүртгүүлэх түлхүүр үгийг таслалаар тусгаарлан бичнэ.</span>
                    </div>
                </div>
                <div class="form-group row">
                    <?php echo form_label('Хуудасны тайлбар', 'Хуудасны тайлбар', array('required' => 'required', 'class' => 'control-label text-right col-2', 'defined' => FALSE)); ?>
                    <div class="col-10">
                        <?php
                        echo form_textarea(array(
                            'name' => 'metaDesc',
                            'id' => 'metaDesc',
                            'value' => $row->meta_desc,
                            'maxlength' => '500',
                            'rows' => 4,
                            'class' => 'form-control'
                        ));
                        ?>
                        <span class="help-block">Энэ хуудасны тухай товчхон 1 өгүүлбэр</span>
                    </div>
                </div>
                <div class="form-group row">
                    <?php echo form_label('Вэб хаяг /url/', 'Вэб хаяг /url/', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-10">
                        <?php
                        echo form_input(array(
                            'name' => 'url',
                            'id' => 'url',
                            'value' => $row->url,
                            'maxlength' => '500',
                            'class' => 'form-control',
                            'required' => 'required'
                        ));
                        ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card rounded-top-0 rounded-bottom-0 mb-0">
        <div class="card-header">
            <h6 class="card-title">
                <a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-group3">Тохиргоо</a>
            </h6>
        </div>

        <div id="accordion-item-group3" class="collapse" data-parent="#accordion-group">
            <div class="card-body">
                <div class="form-group row">
                    <?php echo form_label('Профайл зураг', 'Профайл зураг', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-10">
                        <div class="pull-left mr-3 _profile-image-show">

                            <div class="_delete-button" onclick="_imageDelete({
                                        table: 'partner',
                                        formId: _partnerFormMainId,
                                        appendHtmlClass: '._profile-image',
                                        selectedId: <?php echo $row->id; ?>,
                                        uploadPath: UPLOADS_CONTENT_PATH,
                                        fileName: $('._profile-image').attr('data-image').replace('s_', '')});"></div>

                            <div class="_photo"><img src="<?php echo UPLOADS_CONTENT_PATH . $row->pic; ?>" data-image="<?php echo ltrim($row->pic, CROP_SMALL); ?>" class="_profile-image"></div>

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
                                'onchange' => '_imageProfileUpload({elem: this, uploadPath: UPLOADS_CONTENT_PATH, formId: _partnerFormMainId, appendHtmlClass: \'._profile-image\'});',
                            ));
                            echo '</div>';
                            echo '</span>';

                            echo '</div>';
                            ?>
                            <span class="help-block">Хуулах зургийн хэмжээ: <?php echo formatInBytes(UPLOAD_PROFILE_PHOTO_MAX_SIZE); ?></span>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="form-group row">
                    <?php echo form_label('Ковер зураг', 'Ковер зураг', array('required' => 'required', 'class' => 'control-label col-md-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-md-10">
                        <div class="pull-left mr-3 _profile-image-show">

                            <div class="_delete-button" onclick="_imageDelete({
                                        table: 'partner',
                                        formId: _partnerFormMainId,
                                        appendHtmlClass: '._cover-image',
                                        selectedId: <?php echo $row->id; ?>,
                                        uploadPath: UPLOADS_CONTENT_PATH,
                                        fileName: $('._cover-image').attr('data-image').replace('s_', ''),
                                        uploadType: 'cover'});"></div>

                            <div class="_photo"><img src="<?php echo UPLOADS_CONTENT_PATH . $row->cover; ?>" data-image="<?php echo ltrim($row->cover, CROP_SMALL); ?>" class="_cover-image"></div>

                        </div>

                        <div class="pull-left">

                            <?php
                            echo '<div style="position: relative; display: inline-block;" class="_profile-image-control">';

                            echo '<span class="_upload-button">';
                            echo '<div class="uploader">';
                            echo form_upload(array(
                                'name' => 'coverUpload',
                                'id' => 'coverUpload',
                                'class' => 'pull-left file-styled',
                                'onchange' => '_imageBigUpload({elem: this, uploadPath: UPLOADS_CONTENT_PATH, formId: _partnerFormMainId, appendHtmlClass: \'._cover-image\', uploadType: \'cover\'});',
                            ));
                            echo '</div>';
                            echo '</span>';

                            echo '</div>';
                            ?>
                            <span class="help-block">Хуулах зургийн хэмжээ: <?php echo formatInBytes(UPLOAD_PROFILE_PHOTO_MAX_SIZE); ?></span>
                        </div>


                    </div>
                </div>

                <div class="form-group row">
                    <?php echo form_label('Эрэмбэ', 'Эрэмбэ', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-10">
                        <?php
                        echo form_input(array(
                            'name' => 'orderNum',
                            'id' => 'orderNum',
                            'value' => $row->order_num,
                            'maxlength' => '10',
                            'class' => 'form-control integer',
                            'required' => 'required'
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <?php echo form_label('Сэтгэгдэл', 'Сэтгэгдэл', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-10">
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <?php echo form_radio(array('class' => 'radio', 'name' => 'showComment'), 1, ($row->show_comment == 1 ? TRUE : '')); ?>
                                Нээх
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <?php echo form_radio(array('class' => 'radio', 'name' => 'showComment'), 0, ($row->show_comment == 0 ? TRUE : '')); ?>
                                Нээх
                            </label>
                        </div>

                    </div>
                </div>
                <div class="form-group row">
                    <?php echo form_label('Сошиал', 'Сошиал', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-10">
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <?php echo form_radio(array('class' => 'radio', 'name' => 'showSocial'), 1, ($row->show_social == 1 ? TRUE : '')); ?>
                                Нээх
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <?php echo form_radio(array('class' => 'radio', 'name' => 'showSocial'), 0, ($row->show_social == 0 ? TRUE : '')); ?>
                                Нээх
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <?php echo form_label('Агуулгын гарчиг', 'Агуулгын гарчиг', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-10">

                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <?php echo form_radio(array('class' => 'radio', 'name' => 'showTitle'), 1, ($row->show_title == 1 ? TRUE : '')); ?>
                                Нээх
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <?php echo form_radio(array('class' => 'radio', 'name' => 'showTitle'), 0, ($row->show_title == 0 ? TRUE : '')); ?>
                                Нээх
                            </label>
                        </div>

                    </div>
                </div>
                <div class="form-group row">
                    <?php echo form_label('Агуулгыг харуулах загвар', 'Агуулгыг харуулах загвар', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-10">
                        <?php echo $controlThemeLayoutRadio; ?>
                    </div>
                </div>

                <?php
                $this->socialArray = json_decode($row->social);

                if ($this->socialArray) {
                    foreach ($this->socialArray as $paramKey => $paramRow) {

                        echo '<div class="form-group row">';
                        echo form_label($paramRow->label, $paramRow->label, array('required' => 'required', 'class' => 'form-check-label col-2 text-right', 'defined' => TRUE));
                        echo '<div class="col-6">';

                        echo form_hidden('socialLabel[' . $paramKey . ']', $paramRow->label);
                        echo form_hidden('socialClass[' . $paramKey . ']', $paramRow->class);
                        echo form_input(array(
                            'name' => 'socialUrl[' . $paramKey . ']',
                            'id' => 'socialUrl[' . $paramKey . ']',
                            'value' => $paramRow->url,
                            'maxlength' => '500',
                            'class' => 'form-control'
                        ));
                        echo '</div>';

                        echo '<div class="col-4">';
                        echo '<div class="form-check form-check-inline">';
                        echo '<label class="form-check-label">';
                        echo form_radio(array('name' => 'socialShow[' . $paramKey . ']', 'class' => 'radio'), 1, ($paramRow->show == 1 ? TRUE : ''));
                        echo 'Нээх </label>';
                        echo '</div>';
                        echo '<div class="form-check form-check-inline">';
                        echo '<label class="form-check-label">';
                        echo form_radio(array('name' => 'socialShow[' . $paramKey . ']', 'class' => 'radio'), 0, ($paramRow->show == 0 ? TRUE : ''));
                        echo 'Хаах </label>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
                ?>
                <div class="form-group row">
                    <?php echo form_label('Үзсэн тоо', 'Үзсэн тоо', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => FALSE)); ?>
                    <div class="col-10">
                        <div class="input-group group-indicator">
                            <span class="input-group-append">
                                <span class="input-group-text">Бодит хандалт: <?php echo $row->click_real; ?>, Зохимол хандалт: </span>
                            </span>
                            <?php
                            echo form_input(array(
                                'name' => 'click',
                                'id' => 'click',
                                'value' => $row->click,
                                'maxlength' => '10',
                                'class' => 'form-control',
                                'required' => 'required',
                                'style' => 'max-width: 100px;'
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <?php echo form_label('Үзсэн тоо', 'Үзсэн тоо', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-10">
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <?php echo form_radio(array('id' => 'showClick', 'name' => 'showClick', 'class' => 'radio'), 1, ($row->show_click == 1 ? TRUE : '')); ?>
                                Нээх </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <?php echo form_radio(array('id' => 'showClick', 'name' => 'showClick', 'class' => 'radio'), 0, ($row->show_click == 0 ? TRUE : '')); ?>
                                Хаах </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <?php echo form_label('Өнгө', 'Өнгө', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-10">
                        <?php
                        echo form_input(array(
                            'name' => 'color',
                            'id' => 'color',
                            'value' => $row->color,
                            'maxlength' => '10',
                            'class' => 'form-control colorpicker-show-input',
                            'data-preferred-format' => 'HSL'
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-2 text-right', 'defined' => TRUE)); ?>
                    <div class="col-10">
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive', 'class' => 'radio'), '1', ($row->is_active == 1 ? TRUE : '')); ?>
                                Нээх </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive', 'class' => 'radio'), '0', ($row->is_active == 0 ? TRUE : '')); ?>
                                Хаах </label>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo form_close(); ?>