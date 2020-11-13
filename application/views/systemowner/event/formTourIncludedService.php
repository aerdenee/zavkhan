<?php
echo form_open('', array('class' => 'form-horizontal', 'id' => 'form-tour-included-service', 'enctype' => 'multipart/form-data'));
echo form_hidden('id', $row['id']);
?>
<div class="tabbable">
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="active"><a href="#tabTourIncludedServiceMongolia" data-toggle="tab">Монгол</a></li>
        <li><a href="#tabTourIncludedServiceEnglish" data-toggle="tab">English</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tabTourIncludedServiceMongolia">

            <div class="form-group">
                <?php echo form_label('Аялалд багтах үйлчилгээ', 'Аялалд багтах үйлчилгээ', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <ul class="imageiconlist">
                        <?php
                        foreach ($row['tour_param_mn'] as $imageIcon) {
                            echo '<li>';
                            echo '<label>';
                            echo '<div class="imageicon imageicon-' . $imageIcon->icon . '"></div>';
                            echo '<input type="hidden" name="imageIconName[]" value="' . $imageIcon->icon . '">';
                            echo '<input type="hidden" name="imageIconTitleMn[]" value="' . $imageIcon->title_mn . '">';
                            echo '<input type="hidden" name="imageIconTitleEn[]" value="' . $imageIcon->title_en . '">';
                            echo '<input type="hidden" name="imageIconIsChecked[]" value="' . $imageIcon->isChecked . '">';
                            echo form_checkbox(array('class' => 'radio', 'onclick' => '_imageIcon({elem:this});'), 1, ($imageIcon->isChecked == 1 ? TRUE : ''));
                            echo '</label>';
                            echo '</li>';
                        }
                        ?>
                    </ul>

                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Хугацаа /өдрөөр/', 'Хугацаа /өдрөөр/', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'tourDays',
                        'id' => 'tourDays',
                        'value' => $row['tour_days'],
                        'maxlength' => '500',
                        'class' => 'form-control',
                        'required' => 'required',
                        'style' => 'width:100px;'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Үнэ US$', 'Үнэ US$', array('required' => 'required', 'class' => 'control-label col-lg-2 text-right', 'defined' => TRUE)); ?>
                <div class="col-lg-5">
                    <?php
                    echo form_input(array(
                        'name' => 'tourPrice',
                        'id' => 'tourPrice',
                        'value' => $row['tour_price'],
                        'maxlength' => '500',
                        'class' => 'form-control',
                        'required' => 'required',
                        'style' => 'width:100px;'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_label('Аялалд багтах үйлчилгээ', 'Аялалд багтах үйлчилгээ', array('class' => 'control-label col-lg-12', 'defined' => TRUE)); ?>
                <div class="col-lg-12">
                    <?php
                    echo form_textarea(array(
                        'name' => 'tourIncludedServiceMn',
                        'id' => 'tourIncludedServiceMn',
                        'value' => $row['tour_included_service_mn'],
                        'rows' => 4,
                        'class' => 'form-control ckeditor'
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="tabTourIncludedServiceEnglish">
            <div class="form-group">
                <?php echo form_label('Included service', 'Included service', array('class' => 'control-label col-lg-12', 'defined' => TRUE)); ?>
                <div class="col-lg-12">
                    <?php
                    echo form_textarea(array(
                        'name' => 'tourIncludedServiceEn',
                        'id' => 'tourIncludedServiceEn',
                        'value' => $row['tour_included_service_en'],
                        'rows' => 4,
                        'class' => 'form-control ckeditor'
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>    

        <?php echo form_button('send', '<i class="glyphicon glyphicon-saved"></i> Хадгалах', 'class="btn btn-primary btn-md" onclick="_saveTourIncludedServiceForm({elem:this});"', 'button'); ?>

        <div class="clearfix"></div>
    </div>
</div>

<?php echo form_close(); ?>
<script type="text/javascript">
    $(function () {
        CKEDITOR.replace('tourIncludedServiceMn', {height: '400px'});
        CKEDITOR.replace('tourIncludedServiceEn', {height: '400px'});
    });

</script>
