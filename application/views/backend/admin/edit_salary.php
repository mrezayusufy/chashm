<!-- FIXME 4: default value for date -->
<?php
$single_salary_info = $this->db->get_where('salary', array('salary_id' => $param2))->result_array();
foreach ($single_salary_info as $row) {
?>
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-primary" data-collapsed="0">

                <div class="panel-heading">
                    <div class="panel-title">
                        <h3><?php echo get_phrase('edit_salary'); ?></h3>
                    </div>
                </div>

                <div class="panel-body">

                    <form role="form" class="form-horizontal form-groups" action="<?php echo site_url('Admin/salary/update/'.$row['salary_id']); ?>" 
                        method="post" enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('tazkira_id'); ?></label>

                            <div class="col-sm-7">
                                <input type="text" name="tazkira_id" class="form-control" id="field-1" value="<?php echo $row['tazkira_id']; ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('salary'); ?></label>

                            <div class="col-sm-7">
                                <input type="number" name="salary" class="form-control" id="field-1" value="<?php echo $row['salary']; ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('date'); ?></label>

                            <div class="col-sm-7">
                                <input type="text" name="date" class="form-control datepicker" data-format="D, dd MM yyyy"
                                       placeholder="<?php echo get_phrase('date');?>" value="<?php echo date("D, d M Y", $row['date']); ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('status'); ?></label>

                            <div class="col-sm-7">
                                <select name="status" class="select2" id = "status" defaultValue="<?= $row["status"];?>" required>
                                    <option value="paid"><?php echo get_phrase('paid'); ?></option>
                                    <option value="unpaid"><?php echo get_phrase('unpaid'); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3 control-label col-sm-offset-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> <?php echo get_phrase('update');?>
                            </button>
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </div>
<?php } ?>
