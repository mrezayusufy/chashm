<div class="row">
    <div class="col-md-12">
        <?= form_open(site_url('admin/system_settings/do_update'), array(
            'class' => 'form-horizontal form-groups validate', 'target' => '_top', 'enctype' => 'multipart/form-data'
        ));
        ?>
        <div class="panel panel-primary">

            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?= get_phrase('general_settings'); ?></h4>
                </div>
            </div>

            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= get_phrase('system_name'); ?></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="system_name" value="<?= $this->db->get_where('settings', array('type' => 'system_name'))->row()->description; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= get_phrase('system_title'); ?></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="system_title" value="<?= $this->db->get_where('settings', array('type' => 'system_title'))->row()->description; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= get_phrase('address'); ?></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="address" value="<?= $this->db->get_where('settings', array('type' => 'address'))->row()->description; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= get_phrase('phone'); ?></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="phone" value="<?= $this->db->get_where('settings', array('type' => 'phone'))->row()->description; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= get_phrase('system_email'); ?></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="system_email" value="<?= $this->db->get_where('settings', array('type' => 'system_email'))->row()->description; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= get_phrase('logo'); ?></label>

                    <div class="col-sm-5">

                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;" data-trigger="fileinput">
                                <img src="<?= base_url(); ?>uploads/logo.png">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                            <div>
                                <span class="btn btn-white btn-file">
                                    <span class="fileinput-new">Select image</span>
                                    <span class="fileinput-exists">Change</span>
                                    <input type="file" name="logo" accept="image/*">
                                </span>
                                <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> <?= get_phrase('update'); ?>
                        </button>
                    </div>
                </div>

            </div>

        </div>
        <?= form_close();?>
    </div>
</div>