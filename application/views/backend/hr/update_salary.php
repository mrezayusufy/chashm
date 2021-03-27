
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-body">
                <div class="form-horizontal form-groups">
                    
                    <div class="form-group">
                        <label for="tazkira_id" class="col-sm-3 control-label"><?= get_phrase('tazkira_id'); ?></label>
                        <div class="col-sm-9">
                            <input type="text" id="tazkira_id" class="form-control" name="tazkira_id" disabled v-model="chooseSalary.tazkira_id">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label"><?= get_phrase('name'); ?></label>
                        <div class="col-sm-9">
                            <input type="text" id="name" class="form-control" name="name" disabled v-model="chooseSalary.name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="department_name" class="col-sm-3 control-label"><?= get_phrase('department_name'); ?></label>
                        <div class="col-sm-9">
                            <input type="text" id="department_name" class="form-control" name="department_name" disabled v-model="chooseSalary.department_name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="salary" class="col-sm-3 control-label"><?= get_phrase('salary'); ?></label>
                        <div class="col-sm-9">
                            <input type="number" id="salary" class="form-control" name="salary" disabled v-model="chooseSalary.salary">
                        </div>
                    </div>
                    <!-- status -->
                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label"><?= get_phrase('status')?></label>
                        <div class="col-sm-9">
                            <div class="btn-group" id="status" >
                                <button class="btn btn-outline-dark" :class="{'active btn-primary': (chooseSalary.status == 'paid')}" @click="changeStatus('paid')">  <?= get_phrase('paid'); ?></button>
                                <button class="btn btn-outline-dark" :class="{'active btn-danger': (chooseSalary.status == 'unpaid')}" @click="changeStatus('unpaid')">  <?= get_phrase('unpaid'); ?></button>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
