
<?php
$edit_data = $this->db->get_where('invoice', array('invoice_id' => $param2))->result_array();
$total = 0;
$medicines = $this->db->get('medicine')->result_array();

foreach ($edit_data as $row):
$total = $this->crud_model->calculate_invoice_total_amount($row['invoice_id']);
?>
    <div class="row">
        {{ chooseInvoice.invoice_id }}
        <div class="col-md-12">
            <div class="panel panel-primary" data-collapsed="0">
                <!-- start panel-body -->
                <div class="panel-body">
                    <?php echo form_open(site_url('HR/invoice_manage/update/' . $param2), array('class' => 'form-horizontal form-groups invoice-edit', 'enctype' => 'multipart/form-data')); ?>

                        <!-- invoice title -->
                        <div class="form-group">
                            <label for="field-1" class="col-sm-2 control-label"><?= get_phrase('invoice_title'); ?></label>

                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="title" id="title" data-validate="required"
                                    data-message-required="<?= get_phrase('value_required'); ?>"
                                    value="<?= $row['title']; ?>" required>
                            </div>
                        </div>
                        <!-- patient -->
                        <div class="form-group">
                            <label for="field-1" class="col-sm-2 control-label"><?= get_phrase('patient'); ?></label>

                            <div class="col-sm-9">
                                <select name="patient_id" class="select2" required>
                                    <option value=""><?= get_phrase('select_a_patient'); ?></option>
                                    <?php $patients = $this->db->get('patient')->result_array();
                                    foreach ($patients as $row2): ?>
                                        <option value="<?= $row2['patient_id']; ?>"
                                            <?php if ($row['patient_id'] == $row2['patient_id']) echo 'selected'; ?>>
                                                <?= "ID: ". $row2['patient_id'] . " - ". $row2['name'] . " - " . $row2['father_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <!-- doctor -->
                        <div class="form-group">
                            <label for="field-1" class="col-sm-2 control-label"><?= get_phrase('doctor'); ?></label>

                            <div class="col-sm-9">
                                <select name="hr_id" class="select2" id="hr" required>
                                    <option value=""><?= get_phrase('select_a_doctor'); ?></option>
                                    <?php $hrs = $this->db->get('hr')->result_array();
                                    foreach ($hrs as $row2): ?>
                                        <option value="<?= $row2['hr_id']; ?>" <?php if ($row2['hr_id'] == $row['hr_id']) echo 'selected'; ?>>
                                            <?= "ID: ". $row2['hr_id'] . " - " . $row2['first_name']. " " .$row2['last_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <!-- paid -->
                        <div class="form-group">
                            <label for="field-1" class="col-sm-2 control-label"><?= get_phrase('paid'); ?></label>

                            <div class="col-sm-9">
                                    <input type="number" id="paid" onChange="paid_calc(this)" class="form-control" name="paid" data-validate="required"
                                    data-message-required="<?= get_phrase('value_required'); ?>" value="<?= $row['paid']; ?>" >
                            </div>
                        </div>
                        <hr>
                        <!-- INVOICE ENTRY STARTS HERE-->
                        <div id="invoice_entry">
                            <?php $invoice_entries = json_decode($row['invoice_entries']);
                            foreach ($invoice_entries as $invoice_entry): ?>
                                <div class="form-group">
                                    <label for="field-1" class="col-sm-2 control-label">
                                        <?= get_phrase('invoice_entry'); ?></label>

                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="item[]"
                                            value="<?= $invoice_entry->item; ?>"
                                            placeholder="<?= get_phrase('item'); ?>" >
                                    </div>

                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" name="quantity[]"
                                            value="<?= $invoice_entry->quantity; ?>"
                                            placeholder="<?= get_phrase('quantity'); ?>" >
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="number" class="form-control" name="amount[]"
                                            value="<?= $invoice_entry->amount; ?>"
                                            placeholder="<?= get_phrase('amount'); ?>" min=0>
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" class="btn btn-danger" onclick="deleteParentElement(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- INVOICE ENTRY ENDS HERE-->

                        <!-- TEMPORARY INVOICE ENTRY STARTS HERE-->
                        <div id="invoice_entry_temp">
                            <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label"> <?= get_phrase('invoice_entry'); ?></label>

                                <?php if ($this->session->userdata('department') == 'Pharmacist') { ?>
                                <!-- medicine item  -->
                                <select name="item[1]" class="form-control" id="select" value="<?= $row3['invoice_entries']; ?>" required>
                                    <?php foreach($medicines as $row3) { ?>
                                    <option value="<?= $row3['medicine_id']; ?>" > 
                                        <?= $row3['name'] . " - " . $row['manufacturing_company']; ?> 
                                    </option>
                                    <?php } ?>
                                </select>
                                <!-- medicine quantity -->
                                <div class="col-sm-1">
                                    <input type="number" class="form-control" name="quantity[]"  value=1 min=1 placeholder="<?= get_phrase('quantity'); ?>" >
                                </div>
                                <!-- medicine amount -->
                                <div class="col-sm-2">
                                    <input type="number" class="form-control" name="amount[]"  value="" placeholder="<?= get_phrase('amount'); ?>" min=0 >
                                </div>
                                <?php } else { ?>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="item[]"  value="" placeholder="<?= get_phrase('item'); ?>" >
                                </div>
                                <div class="col-sm-1">
                                    <input type="number" class="form-control" name="quantity[]"  value=1 min=1 placeholder="<?= get_phrase('quantity'); ?>" >
                                </div>
                                <div class="col-sm-2">
                                    <input type="number" class="form-control" name="amount[]"  value="" placeholder="<?= get_phrase('amount'); ?>" min=0>
                                </div>
                                <?php } ?>
                                <div class="col-sm-3">
                                    <button type="button" class="btn btn-danger" onclick="deleteParentElement(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- TEMPORARY INVOICE ENTRY ENDS HERE-->
                        <!-- add invoice entry button -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-8">
                                <button type="button" class="btn btn-default btn-sm"
                                        onClick="add_entry()">
                                    <i class="fas fa-plus"></i> &nbsp;
                                    <?= get_phrase('add_invoice_entry'); ?>
                                </button>
                            </div>
                        </div>
                        <hr>
                        <!-- submit -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-8">
                                <button type="submit" class="btn btn-info" id="submit-button">
                                    <?= get_phrase('update_invoice'); ?></button>
                                <span id="preloader-form"></span>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                </div><!-- end panel-body-->
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script>
    // CREATING BLANK INVOICE ENTRY
    var blank_invoice_entry = '';
    $(document).ready(function () {
        blank_invoice_entry = $('#invoice_entry_temp').html();
        $('#invoice_entry_temp').remove();
        $('[class^="col-sm-"]*').css({"padding-left":"3px","padding-right":"3px"});
    }); 
    function paid_calc() {
      var paid = $('input[name="paid"]').val();
      var total = <?= $total ?>;
      var status = $("select[name='status']");
      if(paid == total) {
        status.val("1");
        status.text("paid");
        status.prop("selected", true);
      }
    }
    function add_entry()
    {
        $("#invoice_entry").append(blank_invoice_entry);
    }

    // REMOVING INVOICE ENTRY
    function deleteParentElement(n) {
        n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
    }

</script>
