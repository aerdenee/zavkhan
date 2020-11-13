<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title"><?php echo $module->title; ?></h5>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a href="<?php echo Sevent::$path . 'index/' . $modId; ?>"><i class="glyphicon glyphicon-chevron-left"></i> <i class="icon icon-primitive-dot" style="font-size:10px; margin-left:-5px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i><i class="icon icon-primitive-dot" style="font-size:10px;"></i></a></li>
            </ul>
        </div>
        <a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

    <div class="panel-body">
        <div class="tabbable">
            
                <?php 
                echo '<ul class="nav nav-tabs nav-tabs-bottom">';
                echo '<li class="active"><a href="#event-main" data-toggle="tab">Үндсэн контент</a></li>';
                echo '<li><a href="#content-vertical-photo" data-toggle="tab">Босоо зураг</a></li>';
                echo '<li><a href="#content-media" data-toggle="tab">Зураг, видео</a></li>';
                echo '<li><a href="#content-map" data-toggle="tab">Газрын зураг</a></li>';
                echo '<li><a href="#content-comment" data-toggle="tab">Сэтгэгдэл</a></li>';
                echo '</ul>';
                ?>
            <div class="tab-content">
                <div class="tab-pane active" id="event-main"><?php $this->load->view(MY_ADMIN . '/event/formMain', $row); ?></div>
                <div class="tab-pane" id="content-vertical-photo">
                    <?php
                    if ($mode == 'update') {
                        $this->load->view(MY_ADMIN . '/event/picVertical', $row);
                    } else {
                        echo $row['emptyTabContent'];
                    }
                    ?>
                </div>
                <div class="tab-pane" id="content-media">
                    <?php
                    if ($mode == 'update') {
                        $this->load->view(MY_ADMIN . '/event/listMedia', $param);
                    } else {
                        echo $row['emptyTabContent'];
                    }
                    ?>
                </div>
                <div class="tab-pane" id="content-map">

                    <?php
                    if ($mode == 'update') {

                        $this->load->view(MY_ADMIN . '/map/google/index', $param);
                    } else {
                        echo $row['emptyTabContent'];
                    }
                    ?>
                </div>
                <div class="tab-pane" id="content-comment">
                <?php
                if ($mode == 'update') {
                    $this->load->view(MY_ADMIN . '/comment/index', $param);
                } else {
                    echo $row['emptyTabContent'];
                }
                ?>
                </div>

            </div>
        </div>
    </div>
</div>


