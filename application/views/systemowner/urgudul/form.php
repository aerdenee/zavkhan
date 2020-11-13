<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-main', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row['id']);
echo form_hidden('modId', $modId);
?>

<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title"><?php echo form_label('Бүртгэлийн дугаар' . ($mode == 'update' ? ': ' . $row['create_number'] : ''), 'Бүртгэлийн дугаар' . ($mode == 'update' ? ': ' . $row['create_number'] : ''), array('required' => 'required', 'class' => 'control-label', 'defined' => FALSE)); ?> </h5>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a href="<?php echo Surgudul::$path . 'index/' . $modId; ?>"><i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i></a></li>
            </ul>
        </div>
        <a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

    <div class="panel-body">
        <div class="col-md-6">
            <div class="row">
                <div class="form-group">
                    <?php echo form_label('Эцгийн нэр', 'Эцгийн нэр', array('required' => 'required', 'class' => 'control-label col-lg-3 text-right', 'defined' => TRUE)); ?>
                    <div class="col-lg-7">
                        <?php
                        echo form_input(array(
                            'name' => 'lname',
                            'id' => 'lname',
                            'value' => $row['lname'],
                            'rows' => 4,
                            'class' => 'form-control',
                            'placeholder' => 'Эцгийн нэр',
                            'required' => true
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_label('Өөрийн нэр', 'Өөрийн нэр', array('required' => 'required', 'class' => 'control-label col-lg-3 text-right', 'defined' => TRUE)); ?>
                    <div class="col-lg-7">
                        <?php
                        echo form_input(array(
                            'name' => 'fname',
                            'id' => 'fname',
                            'value' => $row['fname'],
                            'rows' => 4,
                            'class' => 'form-control',
                            'placeholder' => 'Өөрийн нэр',
                            'required' => true
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_label('Төрөл', 'Төрөл', array('required' => 'required', 'class' => 'control-label col-lg-3 text-right', 'defined' => TRUE)); ?>
                    <div class="col-lg-7">
                        <?php echo $controlCategoryListDropdown; ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_label('Нийслэл, аймаг', 'Нийслэл, аймаг', array('required' => 'required', 'class' => 'control-label col-lg-3 text-right', 'defined' => TRUE)); ?>
                    <div class="col-lg-7">
                        <?php echo $controlCityDropdown; ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_label('Сум, дүүрэг', 'Сум, дүүрэг', array('required' => 'required', 'class' => 'control-label col-lg-3 text-right', 'defined' => TRUE)); ?>
                    <div class="col-lg-7" id="address-soum-html">
                        <?php echo $controlSoumDropdown; ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_label('Баг, хороо', 'Баг, хороо', array('required' => 'required', 'class' => 'control-label col-lg-3 text-right', 'defined' => TRUE)); ?>
                    <div class="col-lg-7" id="address-street-html">
                        <?php echo $controlStreetDropdown; ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_label('Дэлгэрэнгүй хаяг', 'Дэлгэрэнгүй хаяг', array('required' => 'required', 'class' => 'control-label col-lg-3 text-right', 'defined' => TRUE)); ?>
                    <div class="col-lg-7">
                        <?php
                        echo form_input(array(
                            'name' => 'address',
                            'id' => 'address',
                            'value' => $row['address'],
                            'rows' => 4,
                            'class' => 'form-control',
                            'placeholder' => 'Дэлгэрэнгүй хаяг'
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="form-group">
                    <?php echo form_label('Өргөдөл бичсэн огноо', 'Өргөдөл бичсэн огноо', array('required' => 'required', 'class' => 'control-label col-lg-3 text-right', 'defined' => TRUE)); ?>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="col-lg-5 col-md-5" style="padding-left: 0;">
                                <?php $generateDate = explode(' ', $row['generate_date']); ?>
                                <div class="input-group date date-time" id="event_start_date">
                                    <?php
                                    echo form_input(array(
                                        'name' => 'generateDate',
                                        'id' => 'generateDate',
                                        'value' => $generateDate['0'],
                                        'maxlength' => '10',
                                        'class' => 'form-control init-date',
                                        'readonly' => true,
                                        'required' => true
                                    ));
                                    ?>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="input-group date date-time" id="event_start_date">
                                    <?php
                                    $generateDateTime = explode(':', $generateDate['1']);
                                    echo form_input(array(
                                        'name' => 'generateDateTime',
                                        'id' => 'generateDateTime',
                                        'value' => $generateDateTime['0'] . ':' . $generateDateTime['1'],
                                        'maxlength' => '8',
                                        'class' => 'form-control init-time',
                                        'readonly' => true,
                                        'required' => true
                                    ));
                                    ?>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_label('Утас', 'Утас', array('required' => 'required', 'class' => 'control-label col-lg-3 text-right', 'defined' => TRUE)); ?>
                    <div class="col-lg-7">
                        <?php
                        echo form_input(array(
                            'name' => 'contact',
                            'id' => 'contact',
                            'value' => $row['contact'],
                            'rows' => 4,
                            'class' => 'form-control',
                            'placeholder' => 'Холбоо барих утас'
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_label('Товч агуулга', 'Товч агуулга', array('required' => 'required', 'class' => 'control-label col-lg-3 text-right', 'defined' => TRUE)); ?>
                    <div class="col-lg-7">
                        <?php
                        echo form_textarea(array(
                            'name' => 'description',
                            'id' => 'description',
                            'value' => $row['description'],
                            'rows' => 5,
                            'class' => 'form-control',
                            'placeholder' => 'Дэлгэрэнгүй хаяг',
                            'style' => 'height:200px;',
                            'required' => true
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($mode == 'update') {?>
        <div class="clearfix"></div>
        <h2>Өргөдөл</h2>
        <hr>

        <div class="col-md-2">
            <?php
            echo '<div class="uploader">';
            echo form_upload(array(
                'name' => 'picUpload',
                'id' => 'picUpload',
                'class' => 'pull-left file-styled',
                'onchange' => '_mediaInsert({modId: ' . $row['mod_id'] . ', contId: ' . $row['id'] . ', createNumber: \'' . $row['create_number'] . '\', controller: \'' . $this->uri->segment(2) .'\'});',
            ));
            echo '<span class="filename" style="user-select: none;">Файл сонгох</span><span class="btn btn-primary btn-file" style="user-select: none;"><i class="icon-file-plus"></i> Файл хуулах</span>';
            echo '</div>';
            ?>
            <span class="help-block">Хуулах боломжтой зураг: <?php echo formatInFileExtension(UPLOAD_IMAGE_TYPE); ?>  Хуулах файлын хэмжээ: <?php echo formatInBytes(UPLOAD_FILE_MAX_SIZE); ?></span>

        </div>
        <span id="mediaFile"></span>
        <?php }?>
        <div class="clearfix"></div>
        <hr>
        <div class="row">
            <div class="col-md-6 text-left">
            </div>
            <div class="col-md-6 text-right">
                <?php echo form_button('send', '<i class="glyphicon glyphicon-saved"></i> Хадгалах', 'class="btn btn-primary btn-md" onclick="_saveForm({modId: ' . $modId . ', mode: \'' . $mode . '\'});"', 'button'); ?>
            </div>
            <div class="clearfix"></div>
        </div>

    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(function () {
        $('.radio, .checkbox').uniform({radioClass: 'choice'});
        $('.select2').select2();
        $('.integer').formatter({pattern: '{{999}}'});
        $('.fancybox').fancybox({
            helpers: {
                title: null,
                overlay: {
                    speedOut: 0
                }
            }
        });
        $('.init-date').pickadate({
            format: 'yyyy-mm-dd',
            formatSubmit: 'yyyy-mm-dd',
            today: '',
            close: '',
            clear: ''
        });
        $('.init-time').pickatime({
            format: 'HH:i',
            formatLabel: 'HH:i',
            interval: 30,
            min: [0, 0],
            max: [23, 59]
        });
        $('#cityId').on('change', function () {
            _selectSoum(this);
        });
        $('#soumId').on('change', function () {
            _selectStreet(this);
        });
        _mediaInit({modId: <?php echo $modId;?>, contId: <?php echo $row['id'];?>, createNumber: '<?php echo $row['create_number'];?>', controller: '<?php echo $this->uri->segment(2);?>'});
    });
    
</script>
