<ul class="nav nav-tabs nav-tabs-bottom mb-0">
    <li class="nav-item"><a href="#menu-main" class="nav-link active" data-toggle="tab">Үндсэн</a></li>
    <li class="nav-item"><a href="#window-content-media" class="nav-link" data-toggle="tab">Медиа</a></li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="menu-main">
        <?php
        echo form_open('', array('class' => 'form-horizontal col-md-12', 'id' => 'form-menu', 'enctype' => 'multipart/form-data'));

        echo form_hidden('id', $row->id);
        echo form_hidden('menuPic', '');
        if ($row->id != 0) {
            echo form_hidden('menuOldPic', ltrim($row->pic, UPLOADS_CONTENT_PATH . CROP_SMALL));
        } else {
            echo form_hidden('menuOldPic', ltrim($row->pic, UPLOADS_CONTENT_PATH));
        }
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
                            <?php echo form_label('Байрлал', 'Байрлал', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
                                <?php echo $controlLoocationListDropdown; ?>
                            </div>
                        </div>
                        <?php if (IS_MULTIPLE_PARTNER) { ?>
                            <div class="form-group row">
                                <?php echo form_label('Харилцагч', 'Харилцагч', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                                <div class="col-md-9">
                                    <?php
                                    echo $controlPartnerDropdown;
                                    ?>
                                </div>
                            </div>
                            <?php
                        } else {
                            echo form_hidden('partnerId', $row->partner_id);
                        }
                        ?>

                        <div class="form-group row">
                            <?php echo form_label('Хамаарал', 'Хамаарал', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
                                <select class="form-control" name="parentId" id="parentId" required="required" size="6">
                                    <?php echo $menuParentList; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
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
                            <?php echo form_label('Хаяг дуудах төлөв', 'Хаяг дуудах төлөв', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'target'), '_parent', ($row->target == '_parent' ? TRUE : '')); ?>
                                        Энэ цонхонд
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'target'), '_blank', ($row->target == '_blank' ? TRUE : '')); ?>
                                        Шинэ цонхонд
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo form_label('Цэсний төрөл', 'Цэсний төрөл', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
                                <?php echo $controlLinkTypeRadioBox; ?>
                            </div>
                        </div>
                        <span id="linkTypeInput" class="<?php echo ($row->link_type_id == 1 ? 'show' : 'hide'); ?>">
                            <div class="form-group row">
                                <?php echo form_label('Модуль', 'Модуль', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                                <div class="col-md-9">
                                    <?php echo $controlModuleListDropdown; ?>
                                </div>
                            </div>

                            <div class="form-group row">
                                <?php echo form_label('Ангилал', 'Ангилал', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                                <div class="col-md-9" id="categoryListHtml">
                                    <?php echo $controlCategoryListDropdown; ?>
                                </div>
                            </div>

                            <div class="form-group row">
                                <?php echo form_label('Агуулга', 'Агуулга', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                                <div class="col-md-9" id="contentListHtml">
                                    <?php echo $controlContentListDropdown; ?>
                                </div>
                            </div>

                        </span>
                        <span id="linkTypeOutput" class="<?php echo ($row->link_type_id == 2 ? 'show' : 'hide'); ?>">
                            <div class="form-group row">
                                <?php echo form_label('Холбоос хаяг', 'Холбоос хаяг', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                                <div class="col-md-9">
                                    <?php
                                    echo form_input(array(
                                        'name' => 'directUrl',
                                        'id' => 'directUrl',
                                        'value' => $row->direct_url,
                                        'maxlength' => '100',
                                        'size' => '50',
                                        'class' => 'form-control'
                                    ));
                                    ?>
                                </div>
                            </div>

                        </span>
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
                            <?php echo form_label('Гарчиг тайлбар', 'Гарчиг тайлбар', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
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
                            <?php echo form_label('Хуудасны гарчиг', 'Хуудасны гарчиг', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
                                <?php
                                echo form_textarea(array(
                                    'name' => 'pageTitle',
                                    'id' => 'pageTitle',
                                    'value' => $row->page_title,
                                    'maxlength' => '500',
                                    'rows' => 3,
                                    'class' => 'form-control'
                                ));
                                ?>
                                <span class="help-block">Web browser-н title bar дээр харагдах үг</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo form_label('Хуудасны гол агуулга', 'Хуудасны гол агуулга', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
                                <?php
                                echo form_textarea(array(
                                    'name' => 'h1Text',
                                    'id' => 'h1Text',
                                    'value' => $row->h1_text,
                                    'maxlength' => '500',
                                    'rows' => 3,
                                    'class' => 'form-control'
                                ));
                                ?>
                                <span class="help-block">h1 text</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo form_label('Түлхүүр үг', 'Түлхүүр үг', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => FALSE)); ?>
                            <div class="col-md-9">
                                <?php
                                echo form_textarea(array(
                                    'name' => 'metaKey',
                                    'id' => 'metaKey',
                                    'value' => $row->meta_key,
                                    'maxlength' => '500',
                                    'rows' => 3,
                                    'class' => 'form-control'
                                ));
                                ?>
                                <span class="help-block">Хайлтын системд бүртгүүлэх түлхүүр үгийг таслалаар тусгаарлан бичнэ.</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo form_label('Хуудасны тайлбар', 'Хуудасны тайлбар', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => FALSE)); ?>
                            <div class="col-md-9">
                                <?php
                                echo form_textarea(array(
                                    'name' => 'metaDesc',
                                    'id' => 'metaDesc',
                                    'value' => $row->meta_desc,
                                    'maxlength' => '500',
                                    'rows' => 3,
                                    'class' => 'form-control'
                                ));
                                ?>
                                <span class="help-block">Энэ хуудасны тухай товчхон 1 өгүүлбэр</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo form_label('Вэб хаяг /url/', 'Вэб хаяг /url/', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
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
                            <?php echo form_label('Фото зураг', 'Фото зураг', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">

                                <?php
                                echo '<div style="position: relative; display: inline-block;" class="_user-image-box">';
                                echo '<a href="javascript:;">';
                                echo '<img src="' . $row->pic . '" class="_menu-image">';
                                echo '<span class="_user-image-delete-button" onclick="_imageDelete({table: \'menu\', formId: _menuFormMainId, appendHtmlClass: \'._menu-image\', selectedId: ' . $row->id . ', uploadPath: UPLOADS_CONTENT_PATH, prefix: \'menu\'});">';
                                echo '<i class="fa fa-trash-o" style="user-select: none;"></i>';
                                echo '</span>';
                                echo '<span class="_user-image-upload-button">';
                                echo '<div class="uploader">';
                                echo form_upload(array(
                                    'name' => 'menuPicUpload',
                                    'id' => 'menuPicUpload',
                                    'class' => 'pull-left file-styled',
                                    'onchange' => '_imageUpload({
                                        elem: this, 
                                        uploadPath: UPLOADS_CONTENT_PATH, 
                                        formId: _menuFormMainId, 
                                        appendHtmlClass: \'._menu-image\', 
                                        prefix: \'menu\'});',
                                ));
                                echo '<i class="icon-camera" style="user-select: none;"> <span class="_icon-text">Зураг хуулах</span></i>';
                                echo '</div>';
                                echo '</span>';
                                echo '</a>';
                                echo '</div>';
                                ?>

                                <span class="help-block">Хуулах зургийн хэмжээ: <?php echo formatInBytes(UPLOAD_PROFILE_PHOTO_MAX_SIZE); ?></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo form_label('Зураг харуулах', 'Зураг харуулах', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showPic'), 1, ($row->show_pic == 1 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'showPic'), '0', ($row->show_pic == 0 ? TRUE : '')); ?>
                                        Хаах
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo form_label('Эрэмбэ', 'Эрэмбэ', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
                                <?php
                                echo form_input(array(
                                    'name' => 'orderNum',
                                    'id' => 'orderNum',
                                    'value' => $row->order_num,
                                    'maxlength' => 3,
                                    'class' => 'form-control order-num',
                                    'required' => 'required'
                                ));
                                ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'isActive'), 1, ($row->is_active == 1 ? TRUE : '')); ?>
                                        Нээх
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'isActive'), '0', ($row->is_active == 0 ? TRUE : '')); ?>
                                        Хаах
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo form_label('Эхлэл цэс', 'Эхлэл цэс', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'isHome'), 1, ($row->is_home == 1 ? TRUE : '')); ?>
                                        Мөн
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <?php echo form_radio(array('class' => 'radio', 'name' => 'isHome'), '0', ($row->is_home == 0 ? TRUE : '')); ?>
                                        Биш
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php echo form_label('Хэлбэржүүлэх /class/', 'Хэлбэржүүлэх /class/', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
                                <?php
                                echo form_input(array(
                                    'name' => 'class',
                                    'id' => 'class',
                                    'value' => $row->class,
                                    'maxlength' => '500',
                                    'class' => 'form-control'
                                ));
                                ?>
                                <span class="help-block">Өмнө бичигдсэн хэвжүүлэлтийн зөвхөн нэрийг бичиж ашиглана. Жишээ нь: col-md-12 гэх мэт</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo form_label('Хэлбэржүүлэх /css/', 'Хэлбэржүүлэх /css/', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
                                <?php
                                echo form_input(array(
                                    'name' => 'style',
                                    'id' => 'style',
                                    'value' => $row->style,
                                    'maxlength' => '500',
                                    'class' => 'form-control'
                                ));
                                ?>
                                <span class="help-block">padding:10px; font-size:12px; гэх мэт</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo form_label('Харуулах багана', 'Харуулах багана', array('required' => 'required', 'class' => 'col-md-3 col-form-label text-md-right', 'defined' => TRUE)); ?>
                            <div class="col-md-9">
                                <?php
                                echo form_input(array(
                                    'name' => 'columnCount',
                                    'id' => 'columnCount',
                                    'value' => $row->column_count,
                                    'maxlength' => '2',
                                    'class' => 'form-control',
                                    'style' => 'width:100px;'
                                ));
                                ?>
                                <span class="help-block">Том менюнээс хүү меню хэдэн багананд харуулахыг тохируулна</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>






        <?php echo form_close(); ?>
    </div>
    <div class="tab-pane fade" id="window-content-media" data-cont-id="<?php echo $row->id;?>" data-mod-id="<?php echo $row->module_id;?>">
        Засварын горимд ашиглах боломжтой.
    </div>
</div>