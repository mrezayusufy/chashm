<?php
$patients = $this->db->get('patient')->result_array();
$hrs = $this->db->get('hr')->result_array();
$medicines = $this->db->get_where('medicine', array('status' => 1))->result_array();
$login_user_id = $this->session->userdata('login_user_id');
$name = $this->session->userdata('name');
$index = 1;
?>
<div id="add-invoice">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary" >
                <div class="panel-body">
                    
                    <div class="form-horizontal form-groups">

                    <div class="form-group">
                        <label for="field-1" class="col-sm-2 control-label"><?= get_phrase('invoice_title'); ?></label>

                        <div class="col-sm-7">
                            <title-select 
                                id="title" 
                                :options="invoiceTitle" 
                                name="title" 
                                label="title" 
                                v-model="newInvoice.title" 
                                required>
                            </title-select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-2 control-label"><?= get_phrase('patient'); ?></label>

                        <div class="col-sm-7">
                            <patient-select id="patient" :options="patients" label="patient" :reduce="o => `${o.patient_id}`" :get-option-label="o => `${o.patient_id} ${o.name}`" :create-option="o => ({ name: name, father_name: father_name, patient_id: patient_id })" v-model="newInvoice.patient_id">
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
                        <label for="field-1" class="col-sm-2 control-label"><?= get_phrase('doctor'); ?></label>

                        <div class="col-sm-7">
                            <hr-select id="hr_id" :options="hrs" label="hr_id" :reduce="o => `${o.hr_id}`" :get-option-label="o => `${o.hr_id} ${o.first_name} ${o.last_name}`" :create-option="o => ({ first_name: first_name, last_name: last_name, hr_id: hr_id })" v-model="newInvoice.hr_id">
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

                    <hr>
                    <!-- invoice entry id -->
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label class="  control-label"><?= get_phrase('item'); ?></label>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label"><?= get_phrase('quantity'); ?></label>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label"><?= get_phrase('amount'); ?></label>
                        </div>
                    </div>
                    <div id="invoice_entries">
                        <invoice-entries 
                            v-for="(invoice_entry, index) in newInvoice.invoice_entries" 
                            :invoice_entry="invoice_entry"
                            :key="index"
                            @remove-entry="removeEntry(this)">
                        </invoice-entries>
                    </div>
                    <!-- TODO FORM ENTRY STARTS HERE-->
                   
                    <!-- FORM ENTRY ENDS HERE-->
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
                            <button type="button" class="btn btn-primary btn-sm" @Click="addEntry">
                                <i class="fas fa-plus"></i> &nbsp;
                                <?= get_phrase('add_invoice_entry'); ?>
                            </button>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
                            <button @click="createInvoice" class="btn btn-info" id="submit-button">
                                <?= get_phrase('create_new_invoice'); ?></button>
                            <span id="preloader-form"></span>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/x-template" id="invoice-entry-template">
    <div class="form-group">
    
        <?php if ($this->session->userdata('department') == 'Accountant') : ?>
                            
            <div class="col-sm-4">
                 
                <medicine-select id="medicine_id" 
                    :options="medicines"
                    label="medicine_id" 
                    :reduce="o => `${o.name}:${o.manufacturing_company}:${o.medicine_id}:${o.price}`" 
                    :get-option-label="o => `${o.medicine_id} ${o.name} ${o.total_quantity} ${o.manufacturing_company} ${o.price}`" 
                    :create-option="o => ({ name: name, medicine_id: medicine_id, total_quantity: total_quantity, manufacturing_company: manufacturing_company, price: price })" 
                    @input="setAmountMedicine"
                    v-model="invoice_entry.item">
                    <template slot="option" slot-scope="option">
                        ID:{{ option.medicine_id }} _ {{ option.name }} _ {{ option.manufacturing_company }}
                    </template>
                    <template slot="selected-option" slot-scope="option">
                        <div class="selected d-center">
                            ID:{{ option.medicine_id }} _ {{ option.name }} _ {{ option.manufacturing_company }}
                        </div>
                    </template>
                </medicine-select>
            </div>
            <!-- medicine qty -->
            <div class="col-sm-2">
                <input type="number" class="form-control" name="quantity" name="quantity" v-model="invoice_entry.quantity" min=1 placeholder="<?= get_phrase('quantity'); ?>">
            </div>
            <!-- medicine amount -->
            <div class="col-sm-2">
                <input type="number" class="form-control" name="amount" name="amount" v-model="amount" min=0 placeholder="<?= get_phrase('amount'); ?>">
            </div>
        <?php else : ?>
        <div class="col-sm-3">
            <input type="text" class="form-control" v-model="invoice_entry.item" >
        </div>
        <div class="col-sm-2">
            <input type="text" class="form-control"  v-model="invoice_entry.quantity">
        </div>
        <div class="col-sm-2">
            <input type="number"  class="form-control" v-model="invoice_entry.amount" min=0>
        </div>
        <?php endif; ?>
        <div class="col-sm-1">
            <button type="button"  class="btn btn-danger" @click="$emit('remove-entry')">
                <i class="fas fa-trash"></i>
            </button>
        </div>

    </div>
</script>
<script>
    Vue.component('invoice-entries', {
        props: ['invoice_entry'],
        data: function(){
            return {
                medicines: <?= json_encode($medicines); ?>,
                amount: "0"
            }
        },
        methods: {
            setAmountMedicine(val) {
                console.log('val', val);
                var amount = val.split(':');
                this.amount = amount[3];
            },  
        },
        template: "#invoice-entry-template"
    }); 
    Vue.component("title-select", VueSelect.VueSelect);
    Vue.component("medicine-select", VueSelect.VueSelect);
    Vue.component("patient-select", VueSelect.VueSelect);
    Vue.component("hr-select", VueSelect.VueSelect); 
    var app = new Vue({
        el: "#add-invoice",
        data: { 
            medicines: <?= json_encode($medicines); ?>,
            newInvoice: {
                title: "",
                patient_id: "",
                hr_id: "<?= $login_user_id ?>",
                invoice_entries: [
                    {
                        item: "",
                        quantity: "1",
                        amount: "0"
                    }
                ],
            },
            api: '<?= site_url('hr/invoice/'); ?>',
            hrs: <?= json_encode($hrs); ?>,
            formValidate: [],
            patients: <?= json_encode($patients); ?>,
            invoiceTitle: ['Pharmacist','Checkout','Optician','Surgery','BScan','Pharmacist']
        },  
        methods: { 
            createInvoice() {
                var i = app.newInvoice; 
                var invoice_entries = JSON.stringify(i.invoice_entries);
                i.invoice_entries = invoice_entries;
                var formData = app.formData(i);
                axios
                    .post(this.api + "add", formData)
                    .then(function (response) {
                        if (response.data.error) {
                            app.formValidate = response.data.msg;
                            iziToast.success({
                               title: 'Successs', 
                               message: 'Invoice Paid successfully.', 
                               position: 'topRight'
                           });
                        } else {
                           app.clearAll();
                        }
                    })
                    .catch(function(error){
                        console.error(error);
                        alert(error);
                    });
            },
            addEntry(){
                var i = this.newInvoice.invoice_entries;
                var o = { item: "", quantity: "1", amount: "0"};
                i.push(o);
            },
            removeEntry(row){
                var i = this.newInvoice.invoice_entries;
                var index = i.indexOf(row);
                i.splice(index, 1);
            },
            formData(obj){
                var formData = new FormData();
                for (var key in obj) {
                    formData.append(key, obj[key]);
                }
                return formData;
            }, 
            clearAll(){
                app.newInvoice = {
                title: "",
                patient_id: "",
                hr_id: "<?= $login_user_id ?>",
                invoice_entries: [
                        {
                            item: "",
                            quantity: "1",
                            amount: "0"
                        }
                    ]
                };
            }
        },

    });
</script>