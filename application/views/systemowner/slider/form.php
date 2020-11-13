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
<?php 
    $rowEdit = $contentEdit; 
    $data['rowEdit'] = $rowEdit;
?>
<div class="row">
    <div class="col-md-6">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cogs font-green-sharp"></i>
                <span class="caption-subject font-green-sharp bold uppercase">Ангилал</span>
            </div>
        </div>
    </div>
    <div class="col-md-6 text-right">
        <button type="button" class="btn grey-cascade btn-xs formBack"><i class="fa fa-arrow-left"></i> Буцах</button>
    </div>
    <div class="clearfix"></div>    
</div>

<hr>
<!-- BEGIN FORM-->

<div class="form-body">
    <?php echo form_open('sslider/' . $modId . '/' . $formType . '/' . $rowEdit['id'], array('class' => 'form-horizontal', 'id' => 'frmGaz', 'enctype' => 'multipart/form-data')); ?>
<div class="clearfix margin-top-20"></div>
<div class="form-group">
    <?php echo form_label('', '', array('class' => 'control-label col-md-3')); ?>
    <div class="col-md-4">
        <button type="submit" class="btn green btn-xs formSubmit"><i class="fa fa-save"></i> Хадгалах</button>
    </div>

</div>
<hr>

<div class="form-group">
    <input type="hidden" name="oldFile" id="oldFile" value="<?php echo $rowEdit['file']; ?>">
    <div id="picField">
        <?php echo form_label('Зураг', 'Зураг', array('class' => 'control-label col-md-3')); ?>
        <div class="col-md-4">
            <?php
            echo form_upload(array(
                'name' => 'file',
                'id' => 'file',
                'class' => 'pull-left'
            ));
            if ($rowEdit['file'] != '') {
                echo '<br><img src="' . UPLOADS_SLIDER_PATH . $rowEdit['file'] . '" class="margin-top-20 text-left" style="max-width:600px;">';
            }
            ?>
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
    <?php echo form_label('Гарчиг тайлбар', 'Гарчиг тайлбар', array('class' => 'control-label col-md-3')); ?>

    <div class="col-md-4">
        <?php
        echo form_input(array(
            'name' => 'linktitle',
            'id' => 'linktitle',
            'value' => $rowEdit['linktitle'],
            'maxlength' => '100',
            'size' => '50',
            'class' => 'form-control'
        ));
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('ХАЯГ (URL)', 'ХАЯГ (URL)', array('class' => 'control-label col-md-3')); ?>

    <div class="col-md-4">
        <?php
        echo form_input(array(
            'name' => 'url',
            'id' => 'url',
            'value' => $rowEdit['url'],
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
    <label class="control-label col-md-3">Тайлбар </label>
    <div class="col-md-9">
        <textarea class="ckeditor form-control" name="description" rows="6"><?php echo $rowEdit['description']; ?></textarea>
    </div>
</div>
<div class="clearfix"></div>
<?php echo form_close(); ?>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9 text-right">
            <button type="button" class="btn grey-cascade btn-xs formBack"><i class="fa fa-arrow-left"></i> Буцах</button>
        </div>
    </div>
</div>

<!-- END FORM-->


                    </div>
                    <!-- END VALIDATION STATES-->
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
</div>
<!-- END PAGE CONTENT -->
<script>
    var frmName = '#frmGaz';
    var frmPostUrl = $(frmName).attr('action');
    $(function () {
        $("#catid").select2();
<?php if ($rowEdit['catid'] != 0): ?>
            $("#catid").select2('val',<?php echo $rowEdit['catid']; ?>).trigger('change');
<?php endif; ?>
        $('.formBack').on('click', function () {
            window.location = '/sslider/<?php echo $modId; ?>';
        });

        $(".formSubmit").on("click", function () {
            $(frmName).validate({
                messages: {
                    catid: "Ангилал сонгоно уу",
                    title: "Мэдээллийн гарчгийг бичнэ үү"
                }
            });
            if ($(frmName).valid()) {
                $(frmName).submit();
            }
        });
    });
</script>
