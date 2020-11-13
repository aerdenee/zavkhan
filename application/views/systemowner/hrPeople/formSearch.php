<?php
echo form_open('javascript:;', array('class' => 'col-12 pt-2 pb-2', 'id' => 'form-check-date', 'enctype' => 'multipart/form-data', 'method' => 'get'));
echo form_hidden('isActive', 0);
?>
<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Эцэг/эх-ийн нэр', 'Эцэг/эх-ийн нэр', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'lname',
                    'id' => 'lname',
                    'placeholder' => 'Эцэг/эх-ийн нэр',
                    'maxlength' => '100',
                    'class' => 'form-control',
                    'required' => 'required'
                ));
                ?>
            </div>
        </div>

    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Өөрийн нэр', 'Өөрийн нэр', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'fname',
                    'id' => 'fname',
                    'placeholder' => 'Өөрийн нэр',
                    'maxlength' => '100',
                    'class' => 'form-control',
                    'required' => 'required'
                ));
                ?>
            </div>
        </div>

    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Хүйс', 'Хүйс', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-8">
                <select class="select2" name="sex">
                    <option value="0"> - Бүгд - </option>
                    <option value="1"> - Эр - </option>
                    <option value="2"> - Эм - </option>
                </select>
            </div>

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Төрсөн он, сар, өдөр', 'Төрсөн он, сар, өдөр', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8">
                <div class="pull-left">
                    <?php
                    echo form_input(array(
                        'name' => 'birthday',
                        'id' => 'birthday',
                        'maxlength' => '10',
                        'placeholder' => '____-__-__',
                        'class' => 'form-control init-date',
                        'readonly' => true
                    ));
                    ?>
                </div>
                <div class="pull-left ml-2" style="width: 120px;">
                    <div class="input-group">
                        <span class="select2-group">
                            <select name="birthdayOperator" class="select2">
                                <option value="-" selected="selected"> - Сонгох - </option>
                                <option value="="> = &nbsp;&nbsp;Тэнцүү</option>
                                <option value="<"> < &nbsp;&nbsp;Бага</option>
                                <option value="<="> <= Бага юм уу тэнцүү</option>
                                <option value=">"> > &nbsp;&nbsp;Их</option>
                                <option value=">="> >= Их юм уу тэнцүү</option>
                            </select>
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Одоо оршин суугаа хот, аймаг', 'Одоо оршин суугаа хот, аймаг', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8">
                <?php echo $controlLiveCityDropDown; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Одоо оршин суугаа сум, дүүрэг', 'Одоо оршин суугаа сум, дүүрэг', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8 _init-live-soum-html">
                <?php echo $controlLiveSoumDropDown; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Одоо оршин суугаа баг, хороо', 'Одоо оршин суугаа баг, хороо', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8 _init-live-street-html">
                <?php echo $controlLiveStreetDropDown; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Одоо оршин суугаа хаяг', 'Одоо оршин суугаа хаяг', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'liveAddress',
                    'id' => 'liveAddress',
                    'maxlength' => '100',
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Удмын овог', 'Удмын овог', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'familyName',
                    'id' => 'familyName',
                    'maxlength' => '100',
                    'class' => 'form-control'
                ));
                ?>    
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Үндэс угсаа', 'Үндэс угсаа', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'nationality',
                    'id' => 'nationality',
                    'maxlength' => '100',
                    'class' => 'form-control'
                ));
                ?>                
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Нийгмийн гарал', 'Нийгмийн гарал', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'socialOrigin',
                    'id' => 'socialOrigin',
                    'maxlength' => '100',
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Регистрийн дугаар', 'Регистрийн дугаар', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'register',
                    'id' => 'register',
                    'maxlength' => '14',
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Нийгмийн даатгалын дэвтэр', 'Нийгмийн даатгалын дэвтэр', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'socialInsurance',
                    'id' => 'socialInsurance',
                    'maxlength' => '50',
                    'class' => 'form-control'
                ));
                ?>
            </div>

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Эрүүл мэндийн дэвтэр', 'Эрүүл мэндийн дэвтэр', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'healthInsurance',
                    'id' => 'healthInsurance',
                    'maxlength' => '50',
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Утас', 'Утас', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'phone',
                    'id' => 'phone',
                    'maxlength' => '50',
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Факс', 'Факс', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'fax',
                    'id' => 'fax',
                    'maxlength' => '50',
                    'class' => 'form-control'
                ));
                ?>    
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Мэйл хаяг', 'Мэйл хаяг', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'email',
                    'id' => 'email',
                    'maxlength' => '50',
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Шуудангийн хаяг', 'Шуудангийн хаяг', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'postAddress',
                    'id' => 'postAddress',
                    'maxlength' => '50',
                    'class' => 'form-control'
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Онцгой шаардлага гарвал харилцах хүний нэр', 'Онцгой шаардлага гарвал харилцах хүний нэр', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'specialPeople',
                    'id' => 'specialPeople',
                    'maxlength' => '100',
                    'class' => 'form-control'
                ));
                ?>
            </div>

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Онцгой шаардлага гарвал харилцах хүний утас', 'Онцгой шаардлага гарвал харилцах хүний утас', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8">
                <?php
                echo form_input(array(
                    'name' => 'specialPhone',
                    'id' => 'specialPhone',
                    'maxlength' => '100',
                    'class' => 'form-control'
                ));
                ?>
            </div>

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Одоо ажиллаж буй газар, хэлтэс', 'Одоо ажиллаж буй газар, хэлтэс', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8">
                <?php echo $controlHrPeopleDepartmentDropDown; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Албан тушаал', 'Албан тушаал', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8">
                <?php echo $controlHrPeoplePositionDropDown; ?>
            </div>

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Цол', 'Цол', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8">
                <?php echo $controlHrPeopleRankDropDown; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <?php echo form_label('Ажилтны төлөв', 'Ажилтны төлөв', array('required' => 'required', 'class' => 'control-label col-md-4 text-right', 'defined' => FALSE)); ?>
            <div class="col-md-8">
                <?php echo $controlStatusListDropdown; ?>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>