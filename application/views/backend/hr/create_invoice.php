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
                                    <input type="number" class="form-control" name="quantity[n]" value="1" min=1 placeholder="<?= get_phrase('quantity'); ?>">
                                </div>
                                <!-- medicine amount -->
                                <div class="col-sm-2">
                                    <input type="number" class="form-control" name="amount[n]" value="0" placeholder="<?= get_phrase('amount'); ?>" min=0>
                                </div>

                            <?php else : ?>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control"   v-model="newInvoice.invoice_entries[0].item" placeholder="<?= get_phrase('item'); ?>">
                                </div>
                                <div class="col-sm-2">
                                    <input type="number" hidden class="form-control"   v-model="newInvoice.invoice_entries[0].quantity" min=1 placeholder="<?= get_phrase('quantity'); ?>">
                                </div>
                                <div class="col-sm-2">
                                    <input type="number" class="form-control"   v-model="newInvoice.invoice_entries[0].amount" placeholder="<?= get_phrase('amount'); ?>" min=0>
                                </div>
                            <?php endif; ?>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-danger" @click="removeEntry(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                        </div>
                    </div>