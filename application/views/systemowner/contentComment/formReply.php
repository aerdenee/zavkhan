<div class="form-group" style="padding-top: 10px;">
    <?php
    echo form_textarea(array(
        'name' => 'replyCommentMn',
        'id' => 'replyCommentMn',
        'maxlength' => '250',
        'rows' => 3,
        'placeholder' => 'Сэтгэгдэл үлдээх...',
        'required' => 'required',
        'class' => 'form-control'
    ));
    ?>
</div>

<div class="pull-left">
    <?php echo form_button('send', 'Сэтгэгдэл бичих', 'class="btn btn-primary btn-md" onclick="_replySaveThemeComment({elem: this});"', 'button'); ?>
</div>
<div class="clearfix"></div>
