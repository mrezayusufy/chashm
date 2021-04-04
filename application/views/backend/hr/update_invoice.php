<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-body">
                <form role="form" class="form-horizontal form-groups" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="patient_id" class="col-sm-2 control-label"><?= get_phrase('patient'); ?></label>
                        <div class="col-sm-9">
                            <patient-select disabled id="patient" :options="patients" label="patient" :reduce="o => `${o.patient_id}`" :get-option-label="o => `${o.patient_id} ${o.name}`" :create-option="o => ({ name: name, father_name: father_name, patient_id: patient_id })" @input="setSelected" v-model="chooseInvoice.patient">
                                <template slot="option" slot-scope="option">
                                    ID:{{ option.patient_id }} _ {{ option.name }} _ {{ option.father_name }}
                                </template>
                                <template slot="selected-option" slot-scope="option">
                                    <div class="selected d-center">
                                        ID:{{ option.patient_id }} _ {{ option.name }} _ {{ option.father_name }}
                                    </div>
                                </template>
                            </patient-select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="hr_id" class="col-sm-2 control-label"><?= get_phrase('doctor'); ?></label>
                        <div class="col-sm-9">
                            <hr-select id="hr_id" disabled :options="hrs" label="hr_id" :reduce="o => `${o.hr_id}`" :get-option-label="o => `${o.hr_id} ${o.first_name} ${o.last_name}`" :create-option="o => ({ first_name: first_name, last_name: last_name, hr_id: hr_id })" @input="setSelected" v-model="chooseInvoice.hr">
                                <template slot="option" slot-scope="option">
                                    ID:{{ option.hr_id }} _ {{ option.first_name }} _ {{ option.last_name }}
                                </template>
                                <template slot="selected-option" slot-scope="option">
                                    <div class="selected d-center">
                                        ID:{{ option.hr_id }} _ {{ option.first_name }} _ {{ option.last_name }}
                                    </div>
                                </template>
                            </hr-select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="total" class="col-sm-2 control-label"><?= get_phrase('total'); ?></label>
                        <div class="col-sm-9">
                            <input type="text" id="total" class="form-control" name="total" disabled v-model="chooseInvoice.total">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="paid" class="col-sm-2 control-label"><?= get_phrase('paid'); ?></label>
                        <div class="col-sm-9">
                            <input type="number" id="paid" class="form-control" name="paid" :class="{'is-invalid': formValidate.paid}" v-model="chooseInvoice.paid">
                            <div class="text-danger" v-html="formValidate.paid"> </div>
                        </div>
                    </div>
                    <hr>
                    <!-- invoice entry id -->
                    <div id="invoice_entries">
                        <div class="form-group" v-for="i in chooseInvoice.invoice_entries" :key="i.invoice_entry_id">
                            <label for="invoice_entries" class="col-sm-2 control-label"><?= get_phrase('invoice_entry'); ?></label>
                            <div class="col-sm-4">
                                <input type="text" disabled class="form-control" name="item[]" :value="i.item | itemName">
                            </div>

                            <div class="col-sm-2">
                                <input type="text" disabled class="form-control" name="quantity[]" :value="i.quantity">
                            </div>
                            <div class="col-sm-3">
                                <input type="number" disabled class="form-control" name="amount[]" :value="i.amount" min=0>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>