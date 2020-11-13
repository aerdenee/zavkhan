<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-tour-itinerary', 'enctype' => 'multipart/form-data'));
echo form_hidden('contId', $contId);
echo form_hidden('modId', $modId);
echo form_hidden('id', $row['id']);
echo form_hidden('type', $type);
echo form_hidden('mode', $type);
?>
<div style="padding: 10px; display: block;"></div>
<div class="tabbable">
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="active"><a href="#tabContentTourItineraryMongolia" data-toggle="tab">Монгол</a></li>
        <li><a href="#tabContentTourItineraryEnglish" data-toggle="tab">English</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tabContentTourItineraryMongolia">
            <div class="form-group">
                <div class="col-md-3">
                    <div class="row">
                        <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-md-12', 'defined' => TRUE)); ?>
                        <div class="col-md-12">
                            <div class="radio-list">
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'isActiveMn', 'name' => 'isActiveMn', 'class' => 'radio'), 1, ($row['is_active_mn'] == 1 ? TRUE : '')); ?>
                                    Нээх </label>
                                <label class="radio-inline">
                                    <?php echo form_radio(array('id' => 'isActiveMn', 'name' => 'isActiveMn', 'class' => 'radio'), 0, ($row['is_active_mn'] == 0 ? TRUE : '')); ?>
                                    Хаах </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <?php if ($row['id'] == ''): ?>
                        <div class="form-group">
                            <?php echo form_label('Эрэмбэ', 'Эрэмбэ', array('required' => 'required', 'class' => 'control-label col-md-12', 'defined' => TRUE)); ?>
                            <div class="col-md-12">
                                <?php
                                echo form_input(array(
                                    'name' => 'orderNum',
                                    'id' => 'orderNum',
                                    'value' => $row['order_num'],
                                    'maxlength' => '4',
                                    'class' => 'form-control integer order-num',
                                    'required' => 'required'
                                ));
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="form-group">
                <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-lg-12', 'defined' => TRUE)); ?>
                <div class="col-lg-12">
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
            <div class="clearfix"></div>
            <div class="form-group">
                <?php echo form_label('Тайлбар /Монгол/', 'Тайлбар /Монгол/', array('required' => 'required', 'class' => 'control-label col-md-12', 'defined' => TRUE)); ?>
                <div class="col-md-12">
                    <?php
                    echo form_textarea(array(
                        'name' => 'introTextMn',
                        'id' => 'introTextMn',
                        'value' => $row['intro_text_mn'],
                        'rows' => 4,
                        'class' => 'form-control'
                    ));
                    ?>
                </div>
            </div>

        </div>
        <div class="tab-pane" id="tabContentTourItineraryEnglish">
            <div class="form-group">
                <?php echo form_label('Нийтлэх', 'Нийтлэх', array('required' => 'required', 'class' => 'control-label col-md-12', 'defined' => TRUE)); ?>
                <div class="col-md-12">
                    <div class="radio-list">
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'isActiveEn', 'name' => 'isActiveEn', 'class' => 'radio'), 1, ($row['is_active_en'] == 1 ? TRUE : '')); ?>
                            Нээх </label>
                        <label class="radio-inline">
                            <?php echo form_radio(array('id' => 'isActiveEn', 'name' => 'isActiveEn', 'class' => 'radio'), 0, ($row['is_active_en'] == 0 ? TRUE : '')); ?>
                            Хаах </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-lg-12', 'defined' => TRUE)); ?>
                <div class="col-lg-12">
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
                <?php echo form_label('Тайлбар /English/', 'Тайлбар /English/', array('required' => 'required', 'class' => 'control-label col-md-12', 'defined' => TRUE)); ?>
                <div class="col-md-12">
                    <?php
                    echo form_textarea(array(
                        'name' => 'introTextEn',
                        'id' => 'introTextEn',
                        'value' => $row['intro_text_en'],
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