<div class="row">
    <div class="col-md-12">

        <div class="panel panel-primary" data-collapsed="0">

            <div class="panel-body">

                <form role="form" class="form-horizontal form-groups" action="<?= site_url('HR/patient/create'); ?>" 
                    method="post" enctype="multipart/form-data">
                    <!-- name -->
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?= get_phrase('name'); ?></label>

                        <div class="col-sm-7">
                            <input type="text" name="name" class="form-control" id="field-1" required>
                        </div>
                    </div>
                    <!-- father name -->
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?= get_phrase('father_name'); ?></label>

                        <div class="col-sm-7">
                            <input type="text" name="father_name" class="form-control" id="field-1" required>
                        </div>
                    </div>
                    <!-- phone -->
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?= get_phrase('phone'); ?></label>

                        <div class="col-sm-7">
                            <input type="text" name="phone" class="form-control" id="field-1" >
                        </div>
                    </div>
                    <!-- address -->
                    <div class="form-group">
                        <label for="field-ta" class="col-sm-3 control-label"><?= get_phrase('address'); ?></label>

                        <div class="col-sm-7">
                            <textarea name="address" class="form-control" id="field-ta" rows="5"></textarea>
                        </div>
                    </div>
                    <!-- gender -->
                    <div class="form-group">
                        <label for="field-ta" class="col-sm-3 control-label"><?= get_phrase('gender'); ?></label>

                        <div class="col-sm-7">
                            <select name="gender" class="form-control">
                                <option value=""><?= get_phrase('select_gender'); ?></option>
                                <option value="male"><?= get_phrase('male'); ?></option>
                                <option value="female"><?= get_phrase('female'); ?></option>
                            </select>
                        </div>
                    </div>
                    <!-- age -->
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?= get_phrase('age'); ?></label>

                        <div class="col-sm-7">
                            <input type="number" name="age" class="form-control" id="field-1" >
                        </div>
                    </div>
                    <!-- blood group -->
                    <div class="form-group">
                        <label for="field-ta" class="col-sm-3 control-label"><?= get_phrase('blood_group'); ?></label>
                        <div class="col-sm-7">
                            <select name="blood_group" class="form-control">
                                <option value=""><?= get_phrase('select_blood_group'); ?></option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
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
