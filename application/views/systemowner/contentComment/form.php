<?php 
echo form_open('javascript:;', array('class' => 'comment-form ', 'id' => 'form-comment', 'enctype' => 'multipart/form-data')); 
echo form_hidden('contId', $comment['contId']);
echo form_hidden('modId', $comment['modId']);
echo form_hidden('parentId', 0);
?>
<div class="form-group">
    <label>Таны нэр:</label>
    <?php
    echo form_input(array(
        'name' => 'titleMn',
        'id' => 'titleMn',
        'maxlength' => '50',
        'placeholder' => '...',
        'required' => 'required',
        'class' => 'form-control'
    ));
    ?>
</div>

<div class="form-group">
    <label>Сэтгэгдэл:</label>
    <?php
    echo form_textarea(array(
        'name' => 'commentMn',
        'id' => 'commentMn',
        'maxlength' => '500',
        'rows' => 3,
        'placeholder' => '...',
        'required' => 'required',
        'class' => 'form-control'
    ));
    ?>
</div>

<div class="pull-left">
    <?php echo form_button('send', 'Сэтгэгдэл бичих', 'class="btn btn-primary btn-md" onclick="_saveComment({elem: this});"', 'button'); ?>
</div>
<div class="pull-right">
    <select onchange="javascript:;">
        <option value="DESC">Сүүлд нэмэгдсэн</option>
        <option value="ASC">Эхэнд нэмэгдсэн</option>
    </select>
</div>
<div class="clearfix"></div>

<?php echo form_close(); ?>
