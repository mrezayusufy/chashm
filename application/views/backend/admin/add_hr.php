<div class="row">
    <div class="col-md-12">

        <div class="panel panel-primary" data-collapsed="0">

            <div class="panel-body">

                <form role="form" class="form-horizontal form-groups validate" action="<?=site_url('Admin/hr/create'); ?>" 
                    method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?=get_phrase('tazkira_id'); ?></label>

                        <div class="col-sm-7">
                            <input type="text" name="tazkira_id" class="form-control" id="field-1" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?=get_phrase('first_name'); ?></label>

                        <div class="col-sm-7">
                            <input type="text" name="first_name" class="form-control" id="field-1" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?=get_phrase('last_name'); ?></label>

                        <div class="col-sm-7">
                            <input type="text" name="last_name" class="form-control" id="field-1" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?=get_phrase('email'); ?></label>

                        <div class="col-sm-7">
                            <input type="email" name="email" class="form-control" id="field-1" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?=get_phrase('password'); ?></label>

                        <div class="col-sm-7">
                            <input type="password" name="password" class="form-control" id="field-1" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-ta" class="col-sm-3 control-label"><?=get_phrase('salary'); ?></label>

                        <div class="col-sm-7">
                            <input type="number" name="salary" class="form-control" id="field-1" required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-ta" class="col-sm-3 control-label"><?=get_phrase('address'); ?></label>

                        <div class="col-sm-7">
                            <textarea name="address" class="form-control" id="field-ta" rows="5"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?=get_phrase('phone'); ?></label>

                        <div class="col-sm-7">
                            <input type="text" name="phone" class="form-control" id="field-1" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-ta" class="col-sm-3 control-label"><?=get_phrase('department'); ?></label>

                        <div class="col-sm-7">
                            <select name="department_id" class="form-control" required
                                class="form-control">
                                <option value=""><?=get_phrase('select_department'); ?></option>
                                <?php foreach ($department_info as $row) { ?>
                                    <option value="<?=$row['department_id']; ?>"><?=$row['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?=get_phrase('image'); ?></label>

                        <div class="col-sm-5">

                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;" data-trigger="fileinput">
                                    <img src="http://placehold.it/200x150" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                                <div>
                                    <span class="btn btn-white btn-file">
                                        <span class="fileinput-new">Select image</span>
                                        <span class="fileinput-exists">Change</span>
                                        <input type="file" name="image" accept="image/*">
                                    </span>
                                    <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-3 control-label col-sm-offset-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> <?=get_phrase('save');?>
                        </button>
                    </div>
                </form>

            </div>

        </div>

    </div>
</div>

