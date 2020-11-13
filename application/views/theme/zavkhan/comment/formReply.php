<div class="form-group" style="padding-top: 10px;">
    <?php
    echo form_textarea(array(
        'name' => 'reply',
        'id' => 'reply',
        'maxlength' => '255',
        'rows' => 3,
        'placeholder' => 'Сэтгэгдэл үлдээх...',
        'required' => 'required',
        'class' => 'form-control maxlength-textarea'
    ));
    ?>
</div>

<div class="pull-left">
    <!--    -->
    <?php echo form_button('send', 'Сэтгэгдэл бичих', 'class="btn btn-primary btn-md btn-ladda btn-ladda-spinner ladda-button" onclick="_replySaveComment({elem: this});"', 'button'); ?>
    <?php echo form_button('send', 'Хаах', 'class="btn btn-default btn-ladda btn-ladda-spinner ladda-button" onclick="_replyClose({elem: this});"', 'button'); ?>
</div>
<div class="clearfix"></div>
