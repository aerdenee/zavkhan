<div class="w-100">

    <!-- Invoice template -->
    <div class="card">
        <?php
        echo form_open('javascript:;', array('id' => 'form-report-general', 'enctype' => 'multipart/form-data'));
        echo form_hidden('reportMenuId', $reportMenuId);
        echo form_hidden('reportModId', $reportModId);
        echo form_hidden('reportIsClose', 0);
        ?>
        <div class="card-header bg-transparent header-elements-inline">
            <h6 class="card-title"><?php echo $title; ?></h6>
            <div class="header-elements">
                <?php
                echo '<span class="input-group mr-2" style="width: 300px;"><span class="select2-group">' . $control . '</span></span>';

                echo form_input(array(
                    'name' => 'inDate',
                    'id' => 'inDate',
                    'maxlength' => '10',
                    'class' => 'form-control init-date mr-2',
                    'required' => 'required',
                    'readonly' => true,
                    'placeholder' => '____-__-__',
                    'value' => $inDate
                ));

                echo form_input(array(
                    'name' => 'outDate',
                    'id' => 'outDate',
                    'maxlength' => '10',
                    'class' => 'form-control init-date mr-2',
                    'readonly' => true,
                    'placeholder' => '____-__-__',
                    'value' => $outDate
                ));

                $data = array('name' => 'isClose',
                    'class' => 'checkbox',
                    'style' => 'margin:10px; margin-right:0;',
                    'onclick' => '_reportSetControlIsClose({elem:this});'
                );

                echo '<span class="pr-2">';
                echo '<style>.uniform-checker {margin-top:0px; margin-right:0px;}</style>';
                echo '<label class="form-check-label">';
                echo form_checkbox($data);
                echo 'Хаагдсан огноо';
                echo '</label>';
                echo '</span>';


                echo form_button('search', 'Хайх', 'class="btn btn-light btn-sm" onclick="' . $embedFunction . '"', 'button');
                ?>

            </div>
        </div>
        <?php echo form_close(); ?>

        <div id="window-report-general-init">
            <?php echo $reportData; ?>
        </div>

        <div class="card-footer">
            <span class="text-muted"></span>
        </div>
    </div>
    <!-- /invoice template -->

</div>