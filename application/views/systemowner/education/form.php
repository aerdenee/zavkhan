<?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-main', 'enctype' => 'multipart/form-data')); ?>

<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title"><?php echo $module->title;?></h5>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a href="<?php echo Seducation::$path . 'index/' . $modId; ?>"><i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i></a></li>
            </ul>
        </div>
        <a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

    <div class="panel-body">
        <?php
        echo form_hidden('id', $row['id']);
        echo form_hidden('modId', $modId);
        ?>
        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-bottom">
                <li class="active"><a href="#tabContentMongolia" data-toggle="tab">Монгол</a></li>
                <li><a href="#tabContentEnglish" data-toggle="tab">English</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tabContentMongolia">
                    <div class="form-group">
                        <?php echo form_label('Байрлал', 'Байрлал', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <?php echo $controlCategoryListDropdown;?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <?php
                            echo form_input(array(
                                'name' => 'titleMn',
                                'id' => 'titleMn',
                                'value' => $row['title_mn'],
                                'maxlength' => '500',
                                'class' => 'form-control',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label('Эрэмбэ', 'Эрэмбэ', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-2">
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
                        <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <div class="radio-list">
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'isActiveMn', 'name' => 'isActiveMn', 'class' => 'radio'), '1', (intval($row['is_active_mn']) == 1 ? TRUE : '')); ?>
                                    Нээх </label>
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'isActiveMn', 'name' => 'isActiveMn', 'class' => 'radio'), '0', (intval($row['is_active_mn']) == 0 ? TRUE : '')); ?>
                                    Хаах </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label(' ', ' ', array('class' => 'control-label col-lg-2 text-right', 'defined' => FALSE)); ?>
                        <div class="col-lg-10 text-left">
                            <?php
                            echo form_button('send', '<i class="glyphicon glyphicon-saved"></i> Хадгалах', 'class="btn btn-primary btn-md" onclick="_saveForm();"', 'button');
                            ?>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tabContentEnglish">
                    <div class="form-group">
                        <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <div class="radio-list">
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'isActiveEn', 'name' => 'isActiveEn', 'class' => 'radio'), '1', (intval($row['is_active_en']) == 1 ? TRUE : '')); ?>
                                    Нээх </label>
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'isActiveEn', 'name' => 'isActiveEn', 'class' => 'radio'), '0', (intval($row['is_active_en']) == 0 ? TRUE : '')); ?>
                                    Хаах </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                        <div class="col-lg-5">
                            <?php
                            echo form_input(array(
                                'name' => 'titleEn',
                                'id' => 'titleEn',
                                'value' => $row['title_en'],
                                'maxlength' => '500',
                                'class' => 'form-control',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label(' ', ' ', array('class' => 'control-label col-lg-2 text-right', 'defined' => FALSE)); ?>
                        <div class="col-lg-10 text-left">
                            <?php
                            echo form_button('send', '<i class="glyphicon glyphicon-saved"></i> Хадгалах', 'class="btn btn-primary btn-md" onclick="_saveForm();"', 'button');
                            ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    var formId = '#form-main';
    $(function () {
        $('.radio, .checkbox').uniform({radioClass: 'choice'});
        $('.select2').select2();
        $('.integer').formatter({pattern: '{{999}}'});
    });
    function _saveForm() {
        $(formId).validate({errorPlacement: function () {}});
        if ($(formId).valid()) {
            $.ajax({
                type: 'post',
                url: '<?php echo Seducation::$path . $mode; ?>',
                data: $(formId).serialize(),
                dataType: 'json',
                beforeSend: function () {
                    $.blockUI({message: null});
                },
                success: function (data) {
                    if (data.status === 'success') {
                        new PNotify({
                            text: data.message,
                            addclass: 'bg-success'
                        });
                        window.location.href = '<?php echo Seducation::$path . 'index/' . $modId; ?>';
                    } else {
                        new PNotify({
                            text: data.message,
                            addclass: 'bg-danger'
                        });
                    }
                    $.unblockUI();
                }
            });
        }
    }
</script>


