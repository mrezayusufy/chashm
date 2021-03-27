

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-body">
                <div class="form-horizontal form-groups">
                    <!-- tazkira id -->
                    <div class="form-group">
                        <label for="tazkira_id" class="col-sm-3 control-label"><?= get_phrase('HR'); ?></label>
                        <div class="col-sm-7">
                            <hr-select id="tazkira_id" :options="hrs" label="tazkira_id" :reduce="o => o.tazkira_id" :get-option-label="o => `${o.tazkira_id} ${o.name}`" :create-option="o => ({ name: name, tazkira_id: tazkira_id, salary: salary })" @input="setSelected" v-model="newSalary.tazkira_id">
                                <template slot="option" slot-scope="option">
                                    ID:{{ option.tazkira_id }} _ {{ option.name }}
                                </template>
                                <template slot="selected-option" slot-scope="option">
                                    <div class="selected d-center">
                                        ID:{{ option.tazkira_id }} _ {{ option.name }}
                                    </div>
                                </template>
                            </hr-select>
                            <div class="text-danger" v-html="formValidate.tazkira_id"> </div>

                        </div>
                    </div>
                    <!-- hr salary -->
                    <div class="form-group">
                        <label for="hr_salary" class="col-sm-3 control-label"><?= get_phrase('hr_salary'); ?></label>
                        <div class="col-sm-7">
                            <input type="text" id="hr_salary" class="form-control" name="hr_salary" disabled v-model="newSalary.hr_salary">
                        </div>
                    </div>
                    <!-- salary -->
                    <div class="form-group">
                        <label for="salary" class="col-sm-3 control-label"><?= get_phrase('salary'); ?></label>
                        <div class="col-sm-7">
                            <input type="number" id="salary" class="form-control" name="salary" v-model="newSalary.salary">
                            <div class="text-danger" v-html="formValidate.salary"> </div>
                        </div>
                    </div>
                    <!-- status -->
                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label"><?= get_phrase('status')?></label>
                        <div class="col-sm-7">
                            <div class="btn-group" id="status" >
                                <button class="btn btn-outline-dark" :class="{'active btn-primary': (newSalary.status == 'paid')}" @click="pickStatus('paid')">  <?= get_phrase('paid'); ?></button>
                                <button class="btn btn-outline-dark" :class="{'active btn-danger': (newSalary.status == 'unpaid')}" @click="pickStatus('unpaid')">  <?= get_phrase('unpaid'); ?></button>
                            </div>
                          <div class="text-danger" v-html="formValidate.status"> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
