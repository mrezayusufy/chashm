<?php
$hrs = $this->crud_model->select_hr_by_tazkira_id();
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h3><?= get_phrase('add_salary'); ?></h3>
                </div>
            </div>
            <div class="panel-body">
              <form role="form" class="form-horizontal form-groups" action="<?= site_url('Admin/salary/create'); ?>" 
                    method="post" enctype="multipart/form-data">
                    
                    <div class="form-group">
                        <label for="tazkira_id" class="col-sm-3 control-label"><?= get_phrase('tazkira_id'); ?></label>

                        <div class="col-sm-7">
                            <select name="tazkira_id" class="select2" id="tazkira_id" required class="form-control">
                                <option value=""><?= get_phrase('select_tazkira_id'); ?></option>
                                <?php foreach ($hrs as $row) { ?>
                                    <option value="<?= $row['tazkira_id']; ?>"> <?= "#".$row['tazkira_id']." _ ".$row['name']; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="salary" class="col-sm-3 control-label"><?= get_phrase('salary'); ?></label>

                        <div class="col-sm-7">
                            <input type="text" name="salary" class="form-control" id="salary" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="date" class="col-sm-3 control-label"><?= get_phrase('date'); ?></label>

                        <div class="col-sm-7">
                            <input type="text" name="date" class="form-control datepicker"
                                   data-format="D, dd MM yyyy" placeholder="<?= get_phrase('date');?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label"><?php echo get_phrase('status'); ?></label>

                        <div class="col-sm-7">
                            <select name="status" class="select2" id = "status" required>
                                <option value= ""><?php echo get_phrase('select_a_status'); ?></option>
                                <option value="paid"><?php echo get_phrase('paid'); ?></option>
                                <option value="unpaid"><?php echo get_phrase('unpaid'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3 control-label col-sm-offset-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> <?= get_phrase('save');?>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<script >
var h = <?= json_encode($hrs); ?>;
$(document).ready(function () {
    $("#select").change(function(){
        var selectedItem = $(this).children("option:selected").val();
        console.log('selectedItem', selectedItem);
        var index = h.findIndex((i) => i.tazkira_id == selectedItem);
        $("input[name='salary'").val( index != -1 ? h[index].salary : 0 );
    });
});

</script>