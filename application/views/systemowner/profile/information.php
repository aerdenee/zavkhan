<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">Хувийн мэдээлэл</h5>

    </div>
    <div class="card-body">
        <?php
        echo form_open('javascript:;', array('id' => 'form-profile', 'enctype' => 'multipart/form-data'));
        ?>
        <div class="row">
            <div class="col-md-12">
                <?php
                if ($flash != NULL) {
                    echo '<div class="row">';

                    if ($flash['status'] == 'success') {
                        echo '<div class="alert alert-success alert-styled-left alert-arrow-left alert-dismissible w-100">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                            ' . $flash['message'] . '
                        </div>';
                    } else {
                        echo '<div class="alert alert-danger alert-styled-left alert-dismissible w-100">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                            ' . $flash['message'] . '
                        </div>';
                    }
                    echo '</div>';
                }
                ?>

            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php
                    echo form_label('Эцгийн нэр', 'Эцгийн нэр', array('required' => 'required', 'defined' => TRUE));
                    echo form_input(array(
                        'name' => 'lname',
                        'id' => 'lname',
                        'value' => $row->lname,
                        'maxlength' => '100',
                        'class' => 'form-control',
                        'required' => 'required'
                    ));
                    ?>
                </div>

                <div class="form-group">
                    <?php echo form_label('Хүйс', 'Хүйс', array('required' => 'required', 'class' => 'control-label', 'defined' => TRUE)); ?>
                    <br>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <?php echo form_radio(array('class' => 'radio', 'name' => 'sex'), 1, ($row->sex == 1 ? TRUE : '')); ?>
                            Эрэгтэй
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <?php echo form_radio(array('class' => 'radio', 'name' => 'sex'), 0, ($row->sex == 0 ? TRUE : '')); ?>
                            Эмэгтэй
                        </label>
                    </div>

                </div>

                <div class="form-group">
                    <?php
                    echo form_label('Утас', 'Утас', array('required' => 'required', 'defined' => TRUE));
                    echo form_input(array(
                        'name' => 'phone',
                        'id' => 'phone',
                        'value' => $row->phone,
                        'maxlength' => '100',
                        'class' => 'form-control',
                        'required' => 'required'
                    ));
                    ?>
                </div>

            </div>

            <div class="col-md-6">

                <div class="form-group">
                    <?php
                    echo form_label('Өөрийн нэр', 'Өөрийн нэр', array('required' => 'required', 'defined' => TRUE));
                    echo form_input(array(
                        'name' => 'fname',
                        'id' => 'fname',
                        'value' => $row->fname,
                        'maxlength' => '100',
                        'class' => 'form-control',
                        'required' => 'required'
                    ));
                    ?>
                </div>

                <div class="form-group">
                    <?php echo form_label('Төрсөн он, сар, өдөр', 'Төрсөн он, сар, өдөр', array('required' => 'required', 'defined' => TRUE)); ?>


                    <?php
                    echo form_input(array(
                        'name' => 'birthday',
                        'id' => 'birthday',
                        'value' => date('Y-m-d', strtotime($row->birthday)),
                        'maxlength' => '11',
                        'class' => 'form-control init-date',
                        'required' => 'required',
                        'readonly' => true
                    ));
                    ?>
                </div>

                <div class="form-group">
                    <?php
                    echo form_label('Мэйл', 'Мэйл', array('required' => 'required', 'defined' => TRUE));
                    echo form_input(array(
                        'name' => 'email',
                        'id' => 'email',
                        'value' => $row->email,
                        'maxlength' => '100',
                        'class' => 'form-control',
                        'required' => 'required'
                    ));
                    ?>
                </div>

            </div>
        </div>

        <div class="text-right">
            <?php
            echo form_button(array(
                'class' => 'btn btn-primary',
                'content' => 'Хадгалах <i class="icon-paperplane ml-1"></i>',
                'onclick' => '_updateUserData({elem:this});'));
            ?>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>