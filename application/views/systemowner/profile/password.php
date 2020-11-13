<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">Нууц үг солих</h5>

    </div>
    <div class="card-body">
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
        <?php
        echo form_open('javascript:;', array('id' => 'form-profile', 'enctype' => 'multipart/form-data'));
        ?>
        <div class="col-md-10 offset-md-1">
            
            <div class="form-group row">
                <?php
                    echo form_label('Одоогийн нууц үг', 'Одоогийн нууц үг', array('required' => 'required', 'class' => 'col-md-3 col-form-label', 'defined' => TRUE));
                    echo '<div class="col-md-5">';
                    echo form_password(array(
                        'name' => 'currentPassword',
                        'id' => 'currentPassword',
                        'maxlength' => '30',
                        'class' => 'form-control',
                        'required' => 'required'
                    ));
                    echo '</div>';
                    ?>
            </div>
            
            <div class="form-group row">
                <?php
                    echo form_label('Шинэ нууц үг', 'Шинэ нууц үг', array('required' => 'required', 'class' => 'col-md-3 col-form-label', 'defined' => TRUE));
                    echo '<div class="col-md-5">';
                    echo form_password(array(
                        'name' => 'newPassword',
                        'id' => 'newPassword',
                        'maxlength' => '30',
                        'class' => 'form-control',
                        'required' => 'required'
                    ));
                    echo '</div>';
                    ?>
            </div>

            <div class="form-group row">
                <?php
                    echo form_label('Шинэ нууц үг (давтах)', 'Шинэ нууц үг (давтах)', array('required' => 'required', 'class' => 'col-md-3 col-form-label', 'defined' => TRUE));
                    echo '<div class="col-md-5">';
                    echo form_password(array(
                        'name' => 'confirmPassword',
                        'id' => 'confirmPassword',
                        'maxlength' => '30',
                        'class' => 'form-control',
                        'required' => 'required'
                    ));
                    echo '</div>';
                    ?>
            </div>
            
            <div class="form-group row">
                <?php
                    echo form_label('&nbsp;', '&nbsp;', array('required' => 'required', 'class' => 'col-md-3 col-form-label', 'defined' => TRUE));
                    echo '<div class="col-md-5">';
                    echo form_button(array(
                    'class' => 'btn btn-primary',
                    'content' => 'Хадгалах <i class="icon-paperplane ml-1"></i>',
                    'onclick' => '_updatePassword({elem:this});'));
                    echo '</div>';
                    ?>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>