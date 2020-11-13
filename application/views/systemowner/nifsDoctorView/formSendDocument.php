<?php
echo form_open('', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-nifs-doctor-view', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
?>
<div class="row">

    <div class="col-6">

        <div class="form-group row">
            <?php echo form_label('Илгээсэн', 'Илгээсэн', array('required' => 'required', 'class' => 'col-md-4 col-form-label text-md-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <div style="width: 120px; float: left;">
                    <div class="input-group">
                        <?php
                        echo form_input(array(
                            'name' => 'sendCreatedDate',
                            'id' => 'sendCreatedDate',
                            'value' => date('Y-m-d', strtotime($row->send_created_date)),
                            'maxlength' => '10',
                            'class' => 'form-control init-date',
                            'required' => 'required',
                            'readonly' => true,
                            'tabindex' => 3
                        ));
                        ?>
                    </div>
                </div>
                <div style="width: 120px; float:left; margin-left: 20px;">
                    <div class="input-group">
                        <?php
                        echo form_input(array(
                            'name' => 'sendCreatedTime',
                            'id' => 'sendCreatedTime',
                            'value' => date('H:i', strtotime($row->send_created_date)),
                            'maxlength' => '10',
                            'class' => 'form-control init-pickatime',
                            'required' => 'required',
                            'readonly' => true,
                            'tabindex' => 4
                        ));
                        ?>
                    </div>
                </div>

            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Ирүүлсэн обьект' . ($row->object_count > 0 ? ' (' . $row->object_count . ')' : ''), 'Ирүүлсэн обьект' . ($row->object_count > 0 ? ' ' . $row->object_count . '' : ''), array('required' => 'required', 'class' => 'col-4 col-form-label text-right object-count', 'defined' => TRUE)); ?>
            <div class="col-8">
                <?php
                echo form_hidden('objectCount', $row->object_count);
                echo form_textarea(array(
                    'name' => 'sendObject',
                    'id' => 'sendObject',
                    'value' => $row->send_object,
                    'rows' => 2,
                    'class' => 'form-control'
                ));
                ?>
                <span class="help-block">
                    <i class="icon-help"></i> <span>Обьектоо бичээд дундуур зуруус татаад тоог бичнэ. Бичиж дуусаад ENTER товч дараад дараагийн обьектыг бичнэ. <i>/Бээлий-2/</i></span>
                </span>
            </div>
        </div>

    </div><!-- end col -->

    <div class="col-6">

        <?php echo $controlNifsQuestionDropDown;?>
        
        <div class="form-group row">
            <?php echo form_label('Нэмэлт асуулт', 'Нэмэлт асуулт', array('required' => 'required', 'class' => 'col-md-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'question',
                    'id' => 'question',
                    'value' => $row->question,
                    'rows' => 2,
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>
        
        <?php echo $controlHrPeopleExpertMultiListDropdown; ?>

    </div><!-- end col -->
    <div class="clearfix"></div>
    <hr>
    <div class="col-6">
        fdasfdaf<Br><br>
    </div>
    <div class="col-6">
        fdsafafa
    </div>
</div>

<?php echo form_close(); ?>
