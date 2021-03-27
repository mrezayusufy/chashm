<div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary" data-collapsed="0">
                <div class="panel-body">
                    <!-- form -->
                    <div class="form-horizontal form-groups"> 
                        <!-- name -->
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label"><?= get_phrase('name'); ?></label>
                            <div class="col-sm-7">
                                <input type="text" name="name" class="form-control" :class="{'is-invalid': formValidate.name}" id="name" v-model="newPatient.name" >
                                <div class="text-danger" v-html="formValidate.name"> </div>
                            </div>
                        </div>
                        <!-- father name -->
                        <div class="form-group">
                            <label for="father_name" class="col-sm-3 control-label"><?= get_phrase('father_name'); ?></label>
                            <div class="col-sm-7">
                                <input type="text" name="father_name" class="form-control" :class="{'is-invalid': formValidate.father_name}" id="father_name" v-model="newPatient.father_name" >
                                <div class="text-danger" v-html="formValidate.father_name"> </div>
                            </div>
                        </div>
                        <!-- phone -->
                        <div class="form-group">
                            <label for="phone" class="col-sm-3 control-label"><?= get_phrase('phone'); ?></label>
                            <div class="col-sm-7">
                                <input type="text" name="phone" class="form-control" :class="{'is-invalid': formValidate.phone}" id="phone" v-model="newPatient.phone">
                                <div class="text-danger" v-html="formValidate.phone"> </div>
                            </div>
                        </div>
                        <!-- address -->
                        <div class="form-group">
                            <label for="address" class="col-sm-3 control-label"><?= get_phrase('address'); ?></label>
                            <div class="col-sm-7">
                                <textarea name="address" class="form-control" :class="{'is-invalid': formValidate.address}" id="address" rows="5" v-model="newPatient.address">{{ newPatient.address }}</textarea>
                                <div class="text-danger" v-html="formValidate.address"> </div>
                            </div>
                        </div>
                        <!-- gender -->
                        <div class="form-group">
                            <label for="gender" class="col-sm-3 control-label"><?= get_phrase('gender')?></label>
                            <div class="col-sm-7">
                                <div class="btn-group" id="gender" >
                                    <button class="btn btn-outline-dark" :class="{'active btn-primary': (newPatient.gender == 'male')}" @click="pickGender('male')"> <i class="fas fa-mars"></i> <?= get_phrase('male'); ?></button>
                                    <button class="btn btn-outline-dark" :class="{'active btn-primary': (newPatient.gender == 'female')}" @click="pickGender('female')"> <i class="fas fa-venus"></i> <?= get_phrase('female'); ?></button>
                                </div>
                            </div>
                        </div>
                        <!-- age -->
                        <div class="form-group">
                            <label for="age" class="col-sm-3 control-label"><?= get_phrase('age'); ?></label>
                            <div class="col-sm-7">
                                <input type="number" name="age" class="form-control" :class="{'is-invalid': formValidate.age}" id="age" v-model="newPatient.age">
                                <div class="text-danger" v-html="formValidate.age"></div>
                            </div>
                        </div>
                        
                    </div>

                </div>

            </div>

        </div>
    </div>