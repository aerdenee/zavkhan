<?php
echo form_open('', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-doc-out', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row->id);
echo form_hidden('modId', $row->mod_id);
echo form_hidden('isReply', $row->is_reply);
echo form_hidden('docId', $row->doc_id);
echo form_hidden('docCloseId', $row->doc_close_id);
?>
<div class="row">
    <div class="col-6">
        <div class="form-group row">
            <?php echo form_label('Дугаар/Огноо', 'Дугаар/Огноо', array('required' => 'required', 'class' => 'col-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-8">
                <div class="row">
                    <div class="col-6">
                        <?php
                        echo form_input(array_merge($controlDisabled, array(
                            'name' => 'docNumber',
                            'id' => 'docNumber',
                            'value' => $row->doc_number,
                            'class' => 'form-control text-right',
                            'required' => 'required',
                            'tabindex' => 1)));
                        ?>        
                    </div>

                    <div class="col-6 taxt-right">
                        <?php
                        echo form_input(array_merge($controlDisabled, array(
                            'name' => 'docDate',
                            'id' => 'docDate',
                            'value' => $row->doc_date,
                            'maxlength' => '10',
                            'class' => 'form-control init-date',
                            'required' => 'required',
                            'readonly' => true,
                            'placeholder' => '____-__-__')));
                        ?>        
                    </div>
                </div>


            </div>
        </div>

        <div class="form-group row">
            <?php echo form_label('Төрөл/Хуудас', 'Төрөл/Хуудас', array('required' => 'required', 'class' => 'col-4 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-8">
                <div class="row">
                    <div class="col-8">
                        <?php echo $controlMasterDocTypeListDropdown; ?>
                    </div>
                    <div class="col-4">
                        <?php
                        echo form_input(array_merge($controlDisabled, array(
                            'name' => 'pageNumber',
                            'id' => 'pageNumber',
                            'value' => ($row->page_number > 0 ? $row->page_number : ''),
                            'maxlength' => 5,
                            'class' => 'form-control text-right',
                            'required' => 'required')));
                        ?>
                    </div>
                </div>

            </div>
        </div>

    </div><!-- end col -->

    <div class="col-6">

        <div id="htmlControlFromHrPeople">
            <?php
            if ($controlFromHrPeopleListDropdown) {
                echo '<div class="form-group row">';
                echo form_label('Илгээгч', 'Илгээгч', array('required' => 'required', 'class' => 'col-3 control-label text-right', 'defined' => FALSE));
                echo '<div class="col-9">';
                echo $controlFromHrPeopleListDropdown;
                echo '</div>';
                echo '</div>';
            } else {
                echo form_hidden('fromPeopleId', 0);
            }
            ?>
        </div>

        <div class="form-group row">
            <?php echo form_label('Хариу', 'Хариу', array('required' => 'required', 'class' => 'control-label col-3 text-right', 'defined' => TRUE)); ?>
            <div class="col-9">
                <div class="row">
                    <div class="col-6">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text bg-success">
                                    <?php echo form_checkbox(array_merge($controlDisabled, array('class' => 'radio', 'onclick' => '_setIsReply({this: this});')), 1, (intval($row->is_reply) == 1 ? TRUE : '')); ?>
                                </span>
                            </span>
                            <?php
                            if ($row->is_reply == 1) {
                                echo form_input(array_merge($controlDisabled, array(
                                    'name' => 'replyDate',
                                    'id' => 'replyDate',
                                    'value' => $row->reply_date,
                                    'maxlength' => '10',
                                    'class' => 'form-control init-date',
                                    'placeholder' => '____-__-__',
                                    'readonly' => true)));
                            } else {
                                echo form_input(array(
                                    'name' => 'replyDate',
                                    'id' => 'replyDate',
                                    'value' => $row->reply_date,
                                    'maxlength' => '10',
                                    'class' => 'form-control init-date',
                                    'placeholder' => '____-__-__',
                                    'readonly' => true,
                                    'disabled' => true
                                ));
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-6">

                    </div>
                </div>


            </div>
        </div>

    </div><!-- end col -->
    <div class="clearfix"></div>

    <div class="col-12">
        <div class="form-group row">
            <?php echo form_label('Тэргүү/Товч агуулга', 'Тэргүү/Товч агуулга', array('required' => 'required', 'class' => 'col-2 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-10">
                <?php
                echo form_textarea(array_merge($controlDisabled, array(
                    'name' => 'description',
                    'id' => 'description',
                    'value' => $row->description,
                    'class' => 'form-control',
                    'placeholder' => 'Албан бичгийн тэргүү, товч агуулгыг энд бичнэ үү...',
                    'style' => 'height:120px;',
                    'required' => true)));
                ?>
            </div>
        </div>
        <div class="form-group row">
            <?php echo form_label('Хэнд', 'Хэнд', array('required' => 'required', 'class' => 'col-2 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-10 init-doc-out-common-control">
                <div class="row doc-out-common-control-row" data-row="1">
                    <?php
                    if (empty($row->id)) {
                        echo '<div class="col-5">';
                        echo $controlToHrPeopleDepartmentDropdown;
                        echo '</div>';
                        echo '<div class="col-5" data-partner-people-doc-out-bind="1">';
                        if ($controlToPartnerDropdown) {
                            echo $controlToPartnerDropdown;
                            echo '<input type="hidden" name="toPeopleId" value="' . $row->to_people_id . '">';
                        } else if ($controlToHrPeopleListDropdown) {
                            echo $controlToHrPeopleListDropdown;
                            echo '<input type="hidden" name="toPartnerId" value="' . $row->to_partner_id . '">';
                        } else if ($row->id != 0) {
                            echo '<input type="hidden" name="toPartnerId" value="0">';
                            echo '<input type="hidden" name="toPeopleId" value="0">';
                            echo '<select class="select2" disabled="disabled"><option> - Сонгох -</option></select>';
                        } else {
                            echo '<input type="hidden" name="toPartnerId[]" value="0">';
                            echo '<input type="hidden" name="toPeopleId[]" value="0">';
                            echo '<select class="select2" disabled="disabled"><option> - Сонгох -</option></select>';
                        }
                        echo '</div>';
                        echo '<div class="col-2">';
                        echo form_button(array(
                            'name' => 'button',
                            'id' => 'button',
                            'value' => 'true',
                            'type' => 'button',
                            'content' => '<i class="icon-plus22"></i>',
                            'class' => 'btn btn-primary',
                            'onclick' => '_addToDocOutCommonControl({elem:this});'));
                        echo '</div>';
                    } else {
                        echo '<div class="col-6">';
                        echo $controlToHrPeopleDepartmentDropdown;
                        echo '</div>';
                        echo '<div class="col-6" data-partner-people-doc-out-bind="1">';
                        if ($controlToPartnerDropdown) {
                            echo $controlToPartnerDropdown;
                            echo '<input type="hidden" name="toPeopleId" value="' . $row->to_people_id . '">';
                        } else if ($controlToHrPeopleListDropdown) {
                            echo $controlToHrPeopleListDropdown;
                            echo '<input type="hidden" name="toPartnerId" value="' . $row->to_partner_id . '">';
                        } else if ($row->id != 0) { 
                            echo '<input type="hidden" name="toPartnerId" value="0">';
                            echo '<input type="hidden" name="toPeopleId" value="0">';
                            echo '<select class="select2" disabled="disabled"><option> - Сонгох -</option></select>';
                        } else {
                            echo '<input type="hidden" name="toPartnerId[]" value="0">';
                            echo '<input type="hidden" name="toPeopleId[]" value="0">';
                            echo '<select class="select2" disabled="disabled"><option> - Сонгох -</option></select>';
                        }
                        echo '</div>';
                    }
                    ?>

                </div>

            </div>
        </div>
        <div class="form-group row">
            <?php echo form_label('Хавсралт', 'Хавсралт', array('required' => 'required', 'class' => 'col-2 control-label text-right', 'defined' => TRUE)); ?>
            <div class="col-10">
                <?php
                echo $initDocFile;
                ?>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>