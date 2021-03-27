<?php
$system_name = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
$address = $this->db->get_where('settings', array('type' => 'address'))->row()->description;
$phone = $this->db->get_where('settings', array('type' => 'phone'))->row()->description;
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">

            <div class="panel-body">

                <table width="100%" border="0">
                    <tr>
                        <td width="50%"><img src="<?= site_url('uploads/logo.png'); ?>" style="max-height:80px;"></td>
                        <td align="right">
                            <h5><?= get_phrase('issue_date'); ?> : {{ chooseInvoice.creation_timestamp }}</h5>
                            <h5><?= get_phrase('status'); ?> : {{ chooseInvoice.status }}</h5>
                        </td>
                    </tr>
                </table>
            <hr>
                <table width="100%" border="0">
                    <tr>
                        <td align="left">
                            <h4><?= get_phrase('payment_to'); ?> </h4>
                        </td>
                        <td align="right">
                            <h4><?= get_phrase('bill_to'); ?> </h4>
                        </td>
                    </tr>

                    <tr>
                        <td align="left" valign="top">
                            <?= $system_name; ?><br>
                            <?= $address; ?><br>
                            <?= $phone; ?><br>
                            <h4><?= get_phrase('Doctor'); ?>:</h4> <span>{{ chooseInvoice.hr.first_name }} {{ chooseInvoice.hr.last_name }}</span>
                        </td>
                        <td align="right" valign="top">
                            ID: {{ chooseInvoice.patient.patient_id }} _ {{ chooseInvoice.patient.name }} _ {{ chooseInvoice.patient.father_name }}<br />
                            {{ chooseInvoice.patient.address }}<br />
                            {{ chooseInvoice.patient.phone }}<br />
                        </td>
                    </tr>
                </table>
                <hr>
                <h4><?= get_phrase('invoice_entries'); ?></h4>
                <table class="table table-bordered" width="100%" border="1" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th width="60%"><?= get_phrase('entry'); ?></th>
                            <th width="60%"><?= get_phrase('qty'); ?></th>
                            <th><?= get_phrase('price'); ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- INVOICE ENTRY STARTS HERE-->
                        <div id="invoice_entries">
                            <tr v-for="(i, index) in chooseInvoice.invoice_entries">
                                <td class="text-center">{{ index }}</td>
                                <td> {{ i.item | itemName }}</td>
                                <td> {{ i.quantity }} </td>
                                <td class="text-right"> {{ i.amount }} </td>
                            </tr>
                        </div>
                        <!-- INVOICE ENTRY ENDS HERE-->
                    </tbody>
                </table>
                <table width="100%" border="0">
                    <tr>
                        <td align="right" width="80%"><?= get_phrase('subtotal'); ?> :</td>
                        <td align="right">{{ chooseInvoice.total }}</td>
                    </tr>
                    <tr>
                        <td align="right" width="80%"><?= get_phrase('amount_paid'); ?> :</td>
                        <td align="right">{{ chooseInvoice.paid }}</td>
                    </tr>
                    <tr>
                        <td align="right" width="80%"><?= get_phrase('amount_remain'); ?> :</td>
                        <td align="right">{{ chooseInvoice.total - chooseInvoice.paid }}</td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <hr style="margin:0px;">
                        </td>
                    </tr>
                    <tr>
                        <td align="right" width="80%">
                            <h4><?= get_phrase('grand_total'); ?> :</h4>
                        </td>
                        <td align="right">
                            <h4>{{ chooseInvoice.total }}</h4>
                        </td>
                    </tr>
                </table>
            <!-- panel-body -->
            </div>

        </div>
    </div>
</div>
