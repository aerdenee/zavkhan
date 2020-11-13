<?php echo form_open(Scontent::$path . 'index/' . $modId, array('class' => 'form-horizontal', 'id' => 'form-check-date', 'enctype' => 'multipart/form-data', 'method'=>'get')); ?>
<style type="text/css">
    .form-group,
    .ui-dialog-content .form-group:last-child, .ui-dialog-content p:last-child {
        margin-bottom: 20px;
    }
</style>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <?php echo form_label('Гэр бааз', 'Гэр бааз', array('required' => 'required', 'class' => 'control-label col-md-2 col-sm-2 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-10">
                <?php echo $controlOrganization; ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Ангилал', 'Ангилал', array('required' => 'required', 'class' => 'control-label col-md-2 col-sm-2 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-10">
                <?php echo $controlCategoryListDropdown; ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Түлхүүр үг', 'Түлхүүр үг', array('required' => 'required', 'class' => 'control-label col-md-2 col-sm-2 text-right', 'defined' => TRUE)); ?>
            <div class="col-md-10">
                <?php
                echo form_input(array(
                    'name' => 'keyword',
                    'id' => 'keyword',
                    'placeholder' => 'Түлхүүр үг',
                    'maxlength' => '50',
                    'class' => 'form-control',
                    'required' => 'required'
                ));
                ?>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    $(function () {
        var _dateFormat = "yy-mm-dd",
                from = $("#dateIn")
                .datepicker({
                    changeMonth: true,
                    numberOfMonths: 1,
                    dateFormat: _dateFormat
                })
                .on("change", function () {
                    to.datepicker("option", "minDate", getDate(this));
                }),
                to = $("#dateOut").datepicker({
            changeMonth: true,
            numberOfMonths: 1,
            dateFormat: _dateFormat
        })
                .on("change", function () {
                    from.datepicker("option", "maxDate", getDate(this));
                });

        function getDate(element) {
            var date;
            try {
                date = $.datepicker.parseDate(_dateFormat, element.value);
            } catch (error) {
                date = null;
            }

            return date;
        }
    });
    function setOrganizationId(organizationId) {
        $('input[name="organizationId"]', '#form-check-date').val(organizationId);
    }

</script>