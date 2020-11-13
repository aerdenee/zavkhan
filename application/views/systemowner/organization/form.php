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
                        
<div id="window-category">
    <?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-organization', 'enctype' => 'multipart/form-data')); ?>
    <?php
    echo form_hidden('id', $row['id']);
    echo form_hidden('modId', $modId);
    ?>
    <div class="clearfix margin-top-20"></div>
    <div class="row">
        <div class="col-md-6 text-left">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase"><?php echo $title; ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-right">
            <?php
            echo form_button('send', '<i class="fa fa-save"></i> Хадгалах', 'class="btn green btn-xs" onclick="saveForm();"', 'button');
            echo anchor(Sorganization::$path . 'index/' . $modId, '<i class="fa fa-angle-left"></i> Буцах', array('class' => 'btn grey-cascade btn-xs formBack'));
            ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <hr>
    <div class="form-body">
        <div class="form-group">
            <?php echo form_label('Монгол нэр', 'Монгол нэр', array('required' => 'required', 'class' => 'control-label col-md-3')); ?>

            <div class="col-md-4">
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
            <?php echo form_label('Монгол агуулга', 'Монгол агуулга', array('required' => 'required', 'class' => 'control-label col-md-3')); ?>

            <div class="col-md-4">
                <?php
                echo form_input(array(
                    'name' => 'mnCont',
                    'id' => 'mnCont',
                    'value' => $row['mn_cont'],
                    'maxlength' => '500',
                    'class' => 'form-control',
                    'required' => 'required'
                ));
                ?>
                <?php
                echo form_input(array(
                    'name' => 'mnContId',
                    'id' => 'mnContId',
                    'value' => $row['mn_cont_id'],
                    'maxlength' => '500',
                    'class' => 'form-control',
                    'required' => 'required'
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('English name', 'English name', array('required' => 'required', 'class' => 'control-label col-md-3')); ?>

            <div class="col-md-4">
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
            <?php echo form_label('English description', 'English description', array('required' => 'required', 'class' => 'control-label col-md-3')); ?>

            <div class="col-md-4">
                <?php
                echo form_input(array(
                    'name' => 'enCont',
                    'id' => 'enCont',
                    'value' => $row['en_cont'],
                    'maxlength' => '500',
                    'class' => 'form-control',
                    'required' => 'required',
                    'onkeyup' => 'callOrganizationInfo(\'enCont\', \'title_en\')'
                ));
                ?>
                <?php
                echo form_input(array(
                    'name' => 'enContId',
                    'id' => 'enContId',
                    'value' => $row['en_cont_id'],
                    'maxlength' => '500',
                    'class' => 'form-control',
                    'required' => 'required'
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Эрэмбэ</label>
            <div class="col-md-4">
                <?php
                echo form_input(array(
                    'name' => 'orderNum',
                    'id' => 'orderNum',
                    'value' => $row['order_num'],
                    'maxlength' => '4',
                    'class' => 'form-control integer',
                    'required' => 'required'
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Нийтлэх</label>
            <div class="col-md-4">
                <div class="radio-list">
                    <label class="radio-inline">
                        <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive'), 1, ($row['is_active'] == 1 ? TRUE : '')); ?>
                        Нээх </label>
                    <label class="radio-inline">
                        <?php echo form_radio(array('id' => 'isActive', 'name' => 'isActive'), '0', ($row['is_active'] == 0 ? TRUE : '')); ?>
                        Хаах </label>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6 text-left">
        </div>
        <div class="col-md-6 text-right">
            <?php
            echo form_button('send', '<i class="fa fa-save"></i> Хадгалах', 'class="btn green btn-xs" onclick="saveForm();"', 'button');
            echo anchor(Sorganization::$path . 'index/' . $modId, '<i class="fa fa-angle-left"></i> Буцах', array('class' => 'btn grey-cascade btn-xs formBack'));
            ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php echo form_close(); ?>
</div>
                        
                        
                    </div>
                    <!-- END VALIDATION STATES-->
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
</div>
<!-- END PAGE CONTENT -->
<script type="text/javascript">
    var windowId = '#window-category';
    var formId = '#form-organization';
    $(function(){
       var custom = new Bloodhound({
          datumTokenizer: function(d) { return d.tokens; },
          queryTokenizer: Bloodhound.tokenizers.whitespace,
          remote: '/sorganization/searchContent/?fieldName=title_en&query=%QUERY'
        });
         
        custom.initialize();
         
        if (Metronic.isRTL()) {
          $('#titleEn').attr("dir", "rtl");  
        }  
        $('#titleEn').typeahead(null, {
          name: 'datypeahead_example_3',
          displayKey: 'value',
          source: custom.ttAdapter(),
          hint: (Metronic.isRTL() ? false : true),
          templates: {
            suggestion: Handlebars.compile([
              '<div class="media">',
                    '<div class="pull-left">',
                        '<div class="media-object">',
                            '<img src="{{img}}" width="50" height="50"/>',
                        '</div>',
                    '</div>',
                    '<div class="media-body">',
                        '<h4 class="media-heading">{{value}}</h4>',
                        '<p>{{desc}}</p>',
                    '</div>',
              '</div>',
            ].join(''))
          }
        });
    });
    function saveForm() {
        $(formId, windowId).validate({errorPlacement: function () {
            }});
        if ($(formId, windowId).valid()) {
            $.ajax({
                type: 'post',
                url: '<?php echo Sorganization::$path . $mode; ?>',
                data: $(formId, windowId).serialize(),
                dataType: 'json',
                beforeSend: function () {
                    $.blockUI({message: null});
                },
                success: function (data) {
                    if (data.status === 'success') {
                        $.growl.notice({title: data.title, message: data.message});
                        window.location.href = '<?php echo Sorganization::$path . 'index/' . $modId; ?>';
                    } else {
                        $.growl.error({title: data.title, message: data.message});
                    }
                    $.unblockUI();
                }
            });
        }
    }
    function callOrganizationInfo(fieldId, fieldName) {
        
    }
</script>
