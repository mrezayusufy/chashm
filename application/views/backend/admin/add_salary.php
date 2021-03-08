<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h3><?php echo get_phrase('add_salary'); ?></h3>
                </div>
            </div>
            <div class="panel-body">
              <form role="form" class="form-horizontal form-groups" action="<?php echo site_url('admin/salary/create'); ?>" 
                    method="post" enctype="multipart/form-data">
                    
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('tazkira_id'); ?></label>

                        <div class="col-sm-7">
                            <input type="text" name="tazkira_id" class="form-control" id="field-1" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('salary'); ?></label>

                        <div class="col-sm-7">
                            <input type="text" name="salary" class="form-control" id="field-1" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('date'); ?></label>

                        <div class="col-sm-7">
                            <input type="text" name="date" class="form-control datepicker"
                                   data-format="D, dd MM yyyy" placeholder="<?php echo get_phrase('date');?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('status'); ?></label>

                        <div class="col-sm-7">
                            <input type="text" name="status" class="form-control" id="field-1" required>
                        </div>
                    </div>
                    <div class="col-sm-3 control-label col-sm-offset-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-check"></i> <?php echo get_phrase('save');?>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
