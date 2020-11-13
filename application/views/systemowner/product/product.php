<!-- BEGIN PAGE CONTENT -->
<div class="page-content">
    <div class="container">

        <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
        <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Modal title</h4>
                    </div>
                    <div class="modal-body">
                        Widget settings form goes here
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn blue">Save changes</button>
                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->

        <!-- BEGIN PAGE CONTENT INNER -->

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN VALIDATION STATES-->
                <div class="portlet light">

                    <div class="portlet-body form">
<?php echo form_open('sproduct/' . $modId . '/' . $formType . '/' . $rowEdit['id'], array('class' => 'form-horizontal', 'id' => 'frmGaz', 'enctype' => 'multipart/form-data')); ?>
<div class="clearfix margin-top-20"></div>
<div class="form-group">
    <?php echo form_label('', '', array('class' => 'control-label col-md-3')); ?>
    <div class="col-md-4">
        <button type="submit" class="btn green btn-xs formSubmit"><i class="fa fa-save"></i> Хадгалах</button>
    </div>

</div>
<hr>

<div class="form-group">
    <input type="hidden" name="oldPic" id="oldPic" value="<?php echo $rowEdit['pic']; ?>">
    <input type="hidden" name="pic" id="pic">
    <input type="hidden" name="crop_x" id="crop_x">
    <input type="hidden" name="crop_y" id="crop_y">
    <input type="hidden" name="crop_width" id="crop_width">
    <input type="hidden" name="crop_height" id="crop_height">
    <div id="picField">
        <?php echo form_label('Зураг', 'Зураг', array('class' => 'control-label col-md-3')); ?>
        <div class="col-md-4">
            <?php
            echo form_upload(array(
                'name' => 'picUpload',
                'id' => 'picUpload',
                'class' => 'pull-left'
            ));
            if ($rowEdit['pic'] != '') {
                echo '<img src="' . UPLOADS_CONTENT_PATH . CROP_SMALL . $rowEdit['pic'] . '" class="margin-top-20">';
            }
            ?>
            <div id="progress" style="display: none;">
                <div id="bar"></div>
                <div id="percent">0%</div >
            </div>
            <div id="message"></div>
        </div>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Ангилал', 'Ангилал', array('required' => 'required', 'class' => 'control-label col-md-3')); ?>
    <div class="col-md-4">
        <select class="form-control select2me" name="catid" id="catid" data-placeholder="Сонгох..." required="required">
            <?php
            echo '<option value=""></option>';
            echo $categoryListData;
            ?>
        </select>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Байгууллага', 'Байгууллага', array('required' => 'required', 'class' => 'control-label col-md-3')); ?>
    <div class="col-md-4">
        <select class="form-control select2me" name="organizationid" id="organizationid" data-placeholder="Сонгох..." required="required">
            <?php
            echo '<option value=""></option>';
            foreach ($organizationList as $key => $value) {
                if ($value['id'] == $rowEdit['organizationid']) {
                    echo '<option value="' . $value['id'] . '" selected>' . $value['title'] . '</option>';
                }else{
                    echo '<option value="' . $value['id'] . '">' . $value['title'] . '</option>';
                }
                
            }
            ?>
        </select>
    </div>
</div>

<div class="form-group">
    <?php echo form_label('Гарчиг', 'Гарчиг', array('required' => 'required', 'class' => 'control-label col-md-3')); ?>

    <div class="col-md-4">
        <?php
        echo form_input(array(
            'name' => 'title',
            'id' => 'title',
            'value' => $rowEdit['title'],
            'maxlength' => '100',
            'size' => '50',
            'class' => 'form-control',
            'required' => 'required'
        ));
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Дэлгүүрийн үнэ', 'Дэлгүүрийн үнэ', array('class' => 'control-label col-md-3')); ?>

    <div class="col-md-4">
        <?php
        echo form_input(array(
            'name' => 'price',
            'id' => 'price',
            'value' => $rowEdit['price'],
            'maxlength' => '100',
            'size' => '50',
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Онлайн үнэ', 'Онлайн үнэ', array('class' => 'control-label col-md-3')); ?>

    <div class="col-md-4">
        <?php
        echo form_input(array(
            'name' => 'priceonline',
            'id' => 'priceonline',
            'value' => $rowEdit['priceonline'],
            'maxlength' => '100',
            'size' => '50',
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">Эрэмбэ</label>
    <div class="col-md-4">
        <?php
        echo form_input(array(
            'name' => 'ordering',
            'id' => 'ordering',
            'value' => $rowEdit['ordering'],
            'maxlength' => '100',
            'size' => '50',
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">Нийтлэх</label>
    <div class="col-md-4">
        <div class="radio-list">
            <label class="radio-inline">
                <?php echo form_radio(array('id' => 'publish', 'name' => 'publish'), 1, ($rowEdit['publish'] == 1 ? TRUE : '')); ?>
                Нээх </label>
            <label class="radio-inline">
                <?php echo form_radio(array('id' => 'publish', 'name' => 'publish'), '0', ($rowEdit['publish'] == 0 ? TRUE : '')); ?>
                Хаах </label>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">Сэтгэгдэл</label>
    <div class="col-md-4">
        <div class="radio-list">
            <label class="radio-inline">
                <?php echo form_radio(array('id' => 'commentshow', 'name' => 'commentshow'), 1, ($rowEdit['commentshow'] == 1 ? TRUE : '')); ?>
                Нээх </label>
            <label class="radio-inline">
                <?php echo form_radio(array('id' => 'commentshow', 'name' => 'commentshow'), '0', ($rowEdit['commentshow'] == 0 ? TRUE : '')); ?>
                Хаах </label>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">СОШИАЛ</label>
    <div class="col-md-4">
        <div class="radio-list">
            <label class="radio-inline">
                <?php echo form_radio(array('id' => 'showsocial', 'name' => 'showsocial'), 1, ($rowEdit['showsocial'] == 1 ? TRUE : '')); ?>
                Нээх </label>
            <label class="radio-inline">
                <?php echo form_radio(array('id' => 'showsocial', 'name' => 'showsocial'), '0', ($rowEdit['showsocial'] == 0 ? TRUE : '')); ?>
                Хаах </label>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">Агуулга (Товч) </label>
    <div class="col-md-9">
        <textarea class="form-control" name="introtext" rows="3"><?php echo $rowEdit['introtext']; ?></textarea>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">Агуулга </label>
    <div class="col-md-9">
        <textarea class="ckeditor form-control" name="fulltext" rows="6" data-error-container="#fulltext_error"><?php echo $rowEdit['fulltext']; ?></textarea>
    </div>
</div>
<div class="clearfix"></div>
<hr/>
<div class="clearfix"></div>
<div class="form-group">
    <div class="col-md-6">
        <span class="help-block">Хайлтын системд тохируулах түлхүүр үг</span>
        <?php
        echo form_textarea(array(
            'name' => 'metakey',
            'id' => 'metakey',
            'value' => $rowEdit['metakey'],
            'rows' => '3',
            'cols' => '10',
            'class' => 'form-control tags'
        ));
        ?>
        <span class="help-block"><i>Meta keyword</i></span>
    </div>
    <div class="col-md-6">
        <span class="help-block">Хуудасны гарчиг</span>
        <?php
        echo form_textarea(array(
            'name' => 'pagetitle',
            'id' => 'pagetitle',
            'value' => $rowEdit['pagetitle'],
            'rows' => '3',
            'cols' => '10',
            'class' => 'form-control'
        ));
        ?>
        <span class="help-block"><i>Page title</i></span>
    </div>
    <div class="col-md-6">
        <span class="help-block">Агуулгыг хайлтын системд таниулах товч нэг өгүүлбэр</span>
        <?php
        echo form_textarea(array(
            'name' => 'metadesc',
            'id' => 'metadesc',
            'value' => $rowEdit['metadesc'],
            'rows' => '3',
            'cols' => '10',
            'class' => 'form-control'
        ));
        ?>
        <span class="help-block"><i>Meta description</i></span>
    </div>
    <div class="col-md-6">
        <span class="help-block">Агуулгыг хайлтын системд таниулах товч нэг өгүүлбэр</span>
        <?php
        echo form_textarea(array(
            'name' => 'h1text',
            'id' => 'h1text',
            'value' => $rowEdit['h1text'],
            'rows' => '3',
            'cols' => '10',
            'class' => 'form-control'
        ));
        ?>
        <span class="help-block"><i>Meta description</i></span>
    </div>
</div>
<div class="clearfix"></div>
<hr>
<div class="form-group">
    <?php echo form_label('', '', array('class' => 'control-label col-md-3')); ?>
    <div class="col-md-4">
        <button type="submit" class="btn green btn-xs formSubmit"><i class="fa fa-save"></i> Хадгалах</button>
    </div>

</div>

<?php echo form_close(); ?>

                    </div>
                    <!-- END VALIDATION STATES-->
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
</div>
<!-- END PAGE CONTENT -->