<?php
$department_info = $this->db->get('department')->result_array();
$staff = $this->db->get_where('staff', array('staff_id' => $param2))->result_array();
foreach ($staff as $row) {
?>
<div class="row">
    <div class="col-md-12">

        <div class="panel panel-primary" data-collapsed="0">

            <div class="panel-heading">
                <div class="panel-title">
                    <h3><?php echo get_phrase('add_staff'); ?></h3>
                </div>
            </div>

            <div class="panel-body">

                <form role="form" class="form-horizontal form-groups validate" action="<?php echo site_url('Admin/staff/update/'.$row["staff_id"]); ?>" 
                    method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('tazkira_id'); ?></label>

                        <div class="col-sm-7">
                            <input type="text" name="tazkira_id" class="form-control" id="field-1" value="<?php echo $row['tazkira_id']; ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('name'); ?></label>

                        <div class="col-sm-7">
                            <input type="text" name="name" class="form-control" id="field-1" value="<?php echo $row['name']; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="field-ta" class="col-sm-3 control-label"><?php echo get_phrase('salary'); ?></label>

                        <div class="col-sm-7">
                            <input type="number" name="salary" class="form-control" id="field-1" value="<?php echo $row['salary']; ?>" required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-ta" class="col-sm-3 control-label"><?php echo get_phrase('address'); ?></label>

                        <div class="col-sm-7">
                            <textarea name="address" class="form-control" id="field-ta" rows="5" value="<?php echo $row['address']; ?>" required><?php echo $row['address']; ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('phone'); ?></label>

                        <div class="col-sm-7">
                            <input type="text" name="phone" class="form-control" id="field-1" value="<?php echo $row['phone']; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-ta" class="col-sm-3 control-label"><?php echo get_phrase('department'); ?></label>

                        <div class="col-sm-7">
                            <select name="department_id" class="form-control" required>
                                <?php foreach ($department_info as $row2) { ?>
                                    <option value="<?php echo $row2['department_id']; ?>" <?php if ($row['department_id'] == $row2['department_id']) echo 'selected'; ?>>
                                        <?php echo $row2['name']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3 control-label col-sm-offset-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> <?php echo get_phrase('save');?>
                        </button>
                    </div>
                </form>

            </div>

        </div>

    </div>
</div>
<?php } ?>
