<?php
$patients = $this->db->get('patient')->result_array();
$hrs = $this->db->get('hr')->result_array();
$medicines = $this->db->get('medicine')->result_array();
$login_user_id = $this->session->userdata('login_user_id');
$name = $this->session->userdata('name');
$index = 1;
?>
<div id="app">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <i class="fas fa-plus"></i> &nbsp;
                        <?= get_phrase('add_invoice'); ?>
                    </div>
                </div>
                <div class="panel-body">

                    <?= form_open(site_url('hr/invoice_add/create'), array('class' => 'form-horizontal form-groups invoice-add', 'enctype' => 'multipart/form-data')); ?>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?= get_phrase('invoice_title'); ?></label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="title" id="title" data-validate="required" data-message-required="<?= get_phrase('value_required'); ?>" value="" autofocus required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?= get_phrase('patient'); ?></label>

                        <div class="col-sm-7">
                            <select name="patient_id" class="select2" id="patient" required>
                                <option value=""><?= get_phrase('select_a_patient'); ?></option>
                                <?php
                                foreach ($patients as $row2) :
                                ?>
                                    <option value="<?= $row2['patient_id']; ?>">
                                        <?= "ID: " . $row2['patient_id'] . " - " . $row2['name'] . " - " . $row2['father_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?= get_phrase('doctor'); ?></label>

                        <div class="col-sm-7">
                            <select name="hr_id" class="select2" id="hr" value="<?= $login_user_id; ?>" required>
                                <option value="<?= $login_user_id; ?>"><?= $name; ?></option>
                                <?php
                                foreach ($hrs as $row2) :
                                ?>
                                    <option value="<?= $row2['hr_id']; ?>">
                                        <?= "ID: " . $row2['hr_id'] . " - " . $row2['first_name'] . " - " . $row2['last_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <!-- TODO FORM ENTRY STARTS HERE-->
                    <div id="invoice_entry">
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label"><?= get_phrase('invoice_entry'); ?></label>

                            <?php if ($this->session->userdata('department') == 'Pharmacist') : ?>
                            
                                <div class="col-sm-3">
                                    <select name="item[1]" class="form-control" id="select" required>
                                        <option value=""><?= get_phrase('select_a_item') ?></option>
                                        <?php foreach ($medicines as $m) : ?>
                                            <option value="<?= $m['name'] . ":" . $m['manufacturing_company'] . ":" . $m['medicine_id']; ?>"> <?= $m['name'] . " - " . $m['manufacturing_company'] ?> </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input type="number" class="form-control" name="quantity[1]" value="1" min=1 placeholder="<?= get_phrase('quantity'); ?>">
                                </div>
                                <!-- medicine amount -->
                                <div class="col-sm-2">
                                    <input type="number" class="form-control" name="amount[1]" value="0" placeholder="<?= get_phrase('amount'); ?>" min=0>
                                </div>

                            <?php else : ?>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="item[1]" value="" placeholder="<?= get_phrase('item'); ?>">
                                </div>
                                <div class="col-sm-2">
                                    <input type="number" hidden class="form-control" name="quantity[1]" value=1 min=1 placeholder="<?= get_phrase('quantity'); ?>">
                                </div>
                                <div class="col-sm-2">
                                    <input type="number" class="form-control" name="amount[1]" value="" placeholder="<?= get_phrase('amount'); ?>" min=0>
                                </div>
                            <?php endif; ?>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-danger" onclick="deleteParentElement(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                    <!-- FORM ENTRY ENDS HERE-->
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
                            <button type="button" class="btn btn-primary btn-sm" onClick="add_entry()">
                                <i class="fas fa-plus"></i> &nbsp;
                                <?= get_phrase('add_invoice_entry'); ?>
                            </button>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
                            <button type="submit" class="btn btn-info" id="submit-button">
                                <?= get_phrase('create_new_invoice'); ?></button>
                            <span id="preloader-form"></span>
                        </div>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- <medicine-select id="medicine_id" :options="medicines" label="medicine_id" :reduce="o => o.medicine_id" :get-option-label="o => `${o.medicine_id} ${o.name} ${o.total_quantity} ${o.manufacturing_company} ${o.price}`" :create-option="o => ({ name: name, medicine_id: medicine_id, total_quantity: total_quantity, manufacturing_company: manufacturing_company, price: price })" @input="setSelected" v-model="newInvoice.medicine_id">
        <template slot="option" slot-scope="option">
            ID:{{ option.medicine_id }} _ {{ option.name }} _ {{ option.manufacturing_company }}
        </template>
        <template slot="selected-option" slot-scope="option">
            <div class="selected d-center">
                ID:{{ option.medicine_id }} _ {{ option.name }} _ {{ option.manufacturing_company }}
            </div>
        </template>
    </medicine-select> -->
</div>
<script>
    // Vue.component("medicine-select", VueSelect.VueSelect);
    Vue.component("medicine-select", VueSelect.VueSelect);
    var m = <?= json_encode($medicines); ?>;
    var app = new Vue({
        el: "#app",
        data: {
            medicines: <?= json_encode($medicines); ?>,
            newInvoice: {

            },
        },
        methods: {
            setSelected(){},
        }
    });
    // CREATING BLANK INVOICE ENTRY
    let i = 2;
    $(document).ready(function() {
        blank_invoice_entry = $('#invoice_entry').html();
        $("#select").change(function() {
            var selectedItem = $(this).children("option:selected").val();
            var medicine_array = selectedItem.split(':');
            var index = m.findIndex((i) => i.medicine_id == medicine_array[2]);
            $("input[name='amount[1]'").val(index != -1 ? m[index].price : 0);
        }); 
    });

    function add_amount(id) {
        var v = $(`select[name='item[${id}]'`).val();
        var medicine_array = v.split(':');
        var index = m.findIndex((i) => i.medicine_id == medicine_array[2]);
        $(`input[name='amount[${id}]'`).val(index != -1 ? m[index].price : 0);
    }

    function add_entry() {
        let template = `
            <div class="form-group">
            <label for="field-1" class="col-sm-3 control-label"><?= get_phrase('invoice_entry'); ?></label>
            <?php if ($this->session->userdata('department') == 'Pharmacist') : ?>
            <div class="col-sm-3">
                <select name="item[${i}]" class="form-control" onclick="add_amount(${i})" required>
                    <option value=""><?= get_phrase('select_a_item') ?></option>
                    <?php foreach ($medicines as $m) : ?>
                    <option value="<?= $m['name'] . ":" . $m['manufacturing_company'] . ":" . $m['medicine_id']; ?>"> <?= $m['name'] . " - " . $m['manufacturing_company'] ?> </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-2">
                <input type="number" class="form-control" name="quantity[${i}]" value="1" min=1
                        placeholder="<?= get_phrase('quantity'); ?>" >
            </div>
            <!-- medicine amount -->
            <div class="col-sm-2">
                <input type="number" class="form-control" name="amount[${i}]" value="0"
                        placeholder="<?= get_phrase('amount'); ?>" min=0>
            </div>
            <?php else : ?>
            <div class="col-sm-3">
                <input type="text" class="form-control" name="item[${i}]"  value=""
                        placeholder="<?= get_phrase('item'); ?>" >
            </div>
            <div class="col-sm-2">
                <input type="number" hidden class="form-control" name="quantity[${i}]"  value=1 min=1
                        placeholder="<?= get_phrase('quantity'); ?>" >
            </div>
            <div class="col-sm-2">
                <input type="number" class="form-control" name="amount[${i}]"  value=""
                        placeholder="<?= get_phrase('amount'); ?>" min=0>
            </div>
            <?php endif; ?>
            <div class="col-sm-2">
                <button type="button" class="btn btn-danger" onclick="deleteParentElement(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            </div>
            `;
        $("#invoice_entry").append(template);

        i++;
    }
    // REMOVING INVOICE ENTRY
    function deleteParentElement(n) {
        n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
    }
</script>