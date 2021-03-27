
<?php
$single_patient_info = $this->db->get_where('patient', array('patient_id' => $param2))->result_array();
foreach ($single_patient_info as $row) :
?>

    <div class="row">
        <div class="col-md-12">
            
            <div class="panel panel-primary" data-collapsed="0">

                <div class="panel-body">
                    {{ choosePatient.name }}
                    <form role="form" class="form-horizontal form-groups" method="post" enctype="multipart/form-data">
                        <!-- name -->
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label"><?= get_phrase('name'); ?></label>
                            <div class="col-sm-7">
                                <input type="text" name="name" class="form-control" id="field-1" v-model="choosePatient.name" >
                            </div>
                        </div>
                        <!-- father name -->
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label"><?= get_phrase('father_name'); ?></label>

                            <div class="col-sm-7">
                                <input type="text" name="father_name" class="form-control" id="field-1" v-model="choosePatient.father_name" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label"><?= get_phrase('phone'); ?></label>

                            <div class="col-sm-7">
                                <input type="text" name="phone" class="form-control" id="field-1" v-model="choosePatient.phone">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-ta" class="col-sm-3 control-label"><?= get_phrase('address'); ?></label>

                            <div class="col-sm-7">
                                <textarea name="address" class="form-control" id="field-ta" rows="5" v-model="choosePatient.address"><?= $row['address']; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="field-ta" class="col-sm-3 control-label">Gender</label><br>
                            <div class="col-sm-7">
                                <div class="btn-group">
                                    <button class="btn btn-outline-dark" :class="{'active':(choosePatient.gender == 'male')}" @click="changeGender('male')"> <i class="fas fa-mars"></i>  <?= get_phrase('male'); ?></button>
                                    <button class="btn btn-outline-dark" :class="{'active': (choosePatient.gender == 'female')}" @click="changeGender('female')"> <i class="fas fa-venus"></i>  <?= get_phrase('female'); ?></button>
                                </div>
                            </div>
                            <div class="has-text-danger" v-html="formValidate.gender"></div>
                        </div>
                        <div class="form-group">
                            <label for="field-ta" class="col-sm-3 control-label"><?= get_phrase('gender'); ?></label>
                            <div class="col-sm-7">
                                <select name="gender" class="form-control">
                                    <option value=""><?= get_phrase('select_gender'); ?></option>
                                    <option value="male" <?php if ($row['gender'] == 'male')echo 'selected';?>>
                                        <?= get_phrase('male'); ?>
                                    </option>
                                    <option value="female" <?php if ($row['gender'] == 'female')echo 'selected';?>>
                                        <?= get_phrase('female'); ?>
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label"><?= get_phrase('age'); ?></label>

                            <div class="col-sm-7">
                                <input type="number" name="age" class="form-control" id="field-1" value="<?= $row['age']; ?>">
                            </div>
                        </div>

                        <div slot="footer" class="modal-footer">
                            <div class="col-sm-3 control-label col-sm-offset-2">
                                <button type="submit" class="btn btn-success" @click="">
                                    <i class="fas fa-check"></i> <?= get_phrase('update');?>
                                </button>
                            </div>
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </div>
<?php endforeach ?>
