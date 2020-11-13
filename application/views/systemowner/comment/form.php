<?php
echo form_open('javascript:;', array('class' => 'comment-form ', 'id' => 'form-comment', 'enctype' => 'multipart/form-data'));
echo form_hidden('contId', $contId);
echo form_hidden('modId', $modId);
echo form_hidden('parentId', 0);
echo form_hidden('replyComment', '');
?>


    <?php
    echo form_textarea(array(
        'name' => 'comment',
        'id' => 'comment',
        'maxlength' => '255',
        'rows' => 3,
        'placeholder' => 'Сэтгэгдэл үлдээх...',
        'required' => 'required',
        'class' => 'form-control maxlength-textarea mb-2'
    ));
    ?>

<div class="panel-body" style="border: none; padding-top: 0;">
    <div class="pull-left">
        <?php echo form_button('send', 'Сэтгэгдэл бичих', 'class="btn btn-primary btn-md" onclick="_saveComment({elem: this});"', 'button'); ?>
    </div>
    <div class="pull-right">

        <select onchange="_sortByComment({elem: this});">
            <?php
            $data = array(
                array('sortType' => 'DESC', 'title' => 'Сүүлд нэмэгдсэн'),
                array('sortType' => 'ASC', 'title' => 'Эхэнд нэмэгдсэн')
            );
            foreach ($data as $key => $row) {
                echo '<option value="' . $row['sortType'] . '" ' . ($sortType == $row['sortType'] ? ' selected="selected"' : '') . '>' . $row['title'] . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="clearfix"></div>
</div>
<?php echo form_close(); ?>
