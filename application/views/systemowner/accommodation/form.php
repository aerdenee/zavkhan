<div class="panel panel-flat" id="window-menu">
    <div class="panel-heading">
        <h5 class="panel-title"><?php echo $module->title;?></h5>
        <div class="heading-elements">
            <?php
            echo anchor(Saccommodation::$path . 'index/' . $modId, '<i class="fa fa-angle-left"></i> Буцах', array('class' => 'btn btn-default btn-xs formBack'));
            ?>
        </div>
    </div>

    <div class="panel-body">
        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-bottom">
                <li class="active"><a href="#content-main" data-toggle="tab">Үндсэн контент</a></li>
                <li><a href="#content-vertical-photo" data-toggle="tab">Босоо зураг</a></li>
                <li><a href="#content-media" data-toggle="tab">Зураг, видео</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="content-main"><?php $this->load->view(MY_ADMIN . '/accommodation/formContent', $row); ?></div>
                <div class="tab-pane" id="content-vertical-photo"><?php ($mode == 'update' ? $this->load->view(MY_ADMIN . '/accommodation/verticalPhoto', $row) : ''); ?></div>
                <div class="tab-pane" id="content-media"><?php ($mode == 'update' ? $this->load->view(MY_ADMIN . '/accommodation/media', $row) : ''); ?></div>
            </div>
        </div>
    </div>
</div>

