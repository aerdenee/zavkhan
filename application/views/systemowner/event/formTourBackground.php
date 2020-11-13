<?php 
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-tour-background', 'enctype' => 'multipart/form-data')); 
echo form_hidden('id', $row['id']);
?>
<div class="tabbable">
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="active"><a href="#tabTourBackgroundMongolia" data-toggle="tab">Монгол</a></li>
        <li><a href="#tabTourBackgroundEnglish" data-toggle="tab">English</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tabTourBackgroundMongolia">
            <div class="form-group">
                <?php echo form_label('Агуулга', 'Агуулга', array('class' => 'control-label col-lg-12', 'defined' => TRUE)); ?>
                <div class="col-lg-12">
                    <?php
                    echo form_textarea(array(
                        'name' => 'tourBackgroundMn',
                        'id' => 'tourBackgroundMn',
                        'value' => $row['tour_background_mn'],
                        'rows' => 4,
                        'class' => 'form-control ckeditor'
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="tabTourBackgroundEnglish">
            <div class="form-group">
                <?php echo form_label('Агуулга', 'Агуулга', array('class' => 'control-label col-lg-12', 'defined' => TRUE)); ?>
                <div class="col-lg-12">
                    <?php
                    echo form_textarea(array(
                        'name' => 'tourBackgroundEn',
                        'id' => 'tourBackgroundEn',
                        'value' => $row['tour_background_en'],
                        'rows' => 4,
                        'class' => 'form-control ckeditor'
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>    
                
                    <?php echo form_button('send', '<i class="glyphicon glyphicon-saved"></i> Хадгалах', 'class="btn btn-primary btn-md" onclick="_saveTourBackgroundForm({elem:this});"', 'button'); ?>
                
                <div class="clearfix"></div>
    </div>
</div>

<?php echo form_close(); ?>
<script type="text/javascript">
    $(function () {
        CKEDITOR.replace('tourBackgroundMn', {height: '400px'});
        CKEDITOR.replace('tourBackgroundEn', {height: '400px'});
    });

</script>
