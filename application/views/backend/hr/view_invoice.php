<div id="print">
<?php
$system_name = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
$address = $this->db->get_where('settings', array('type' => 'address'))->row()->description;
$phone = $this->db->get_where('settings', array('type' => 'phone'))->row()->description;
?>
<div id="log">
    <table width="100%" border="0">
        <tr>
            <td width="50%"><img src="<?= site_url('uploads/logo.png'); ?>" style="max-height:80px;"></td>
            <td align="right">
                <h5><?= get_phrase('issue_date'); ?> : {{ chooseInvoice.creation_timestamp | formatDate}}</h5>
                <h5><?= get_phrase('status'); ?> : {{ chooseInvoice.status }}</h5>
            </td>
        </tr>
    </table>
    <hr>
</div>

<?php

$data = array();
$invoice = $this->db->get_where('invoice', array('invoice_id' => $param2))->result_array();

$inv = $this->db->get_where('invoice', array('invoice_id' => $param2))->row();

$status = '';
$data['invoice'] = $inv;
$data['system_name'] = $system_name;
$data['address'] = $address;
$data['phone'] = $phone;
foreach ($invoice as $row):
$patient = $this->db->get_where('patient', array('patient_id' => $row['patient_id']))->row();
$hr = $this->db->get_where('hr', array('hr_id' => $row['hr_id']))->row();
$creation_timestamp = date('F j, Y, g:i a', $row['creation_timestamp']);
$now_time = date('F j, Y, g:i a', now('Asia/Kabul'));
$status = $row['status'];

?>
    <div id="log">
        <table width="100%" border="0">
            <tr>
                <td width="50%"><img src="<?= site_url('uploads/logo.png'); ?>" style="max-height:80px;"></td>
                <td align="right">
                    <h5><?= get_phrase('issue_date'); ?> : <?= $creation_timestamp; ?></h5>
                    <h5><?= get_phrase('status'); ?> : <?= $status ?></h5>
                </td>
            </tr>
        </table>
        <hr>
        <table width="100%" border="0">    
            <tr>
                <td align="left"><h4><?= get_phrase('payment_to'); ?> </h4></td>
                <td align="right"><h4><?= get_phrase('bill_to'); ?> </h4></td>
            </tr>

            <tr>
                <td align="left" valign="top">
                    <?= $system_name; ?><br>
                    <?= $address; ?><br>
                    <?= $phone; ?><br>            
                    <h4><?= get_phrase('Doctor'); ?>:</h4><span><?= $hr->first_name. " " . $hr->last_name;?></span>          
                </td>
                <td align="right" valign="top">
                    <?= "ID: " . $patient->patient_id . " - " . $patient->name . " - " . $patient->father_name; ?><br>
                    <?= $patient->address; ?><br>
                    <?= $patient->phone; ?><br>
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
            <div id="invoice_entry">
                <?php
                $system_currency_id = $this->db->get_where('settings', array('type' => 'system_currency_id'))->row()->description;
                $currency_symbol    = $this->db->get_where('currency', array('currency_id' => $system_currency_id))->row()->currency_symbol;
                $total_amount       = 0;
                $invoice_entries    = json_decode($row['invoice_entries']);
                $i = 1;
                foreach ($invoice_entries as $invoice_entry)
                {
                    $total_amount += $invoice_entry->amount * $invoice_entry->quantity; ?>
                    <tr>
                        <td class="text-center"><?= $i++; ?></td>
                        <td> <?php $res = preg_split("/\:/",$invoice_entry->item); echo $res['0']." ".$res['1']?> </td>
                        <td> <?= $invoice_entry->quantity; ?> </td>
                        <td class="text-right"> <?= $currency_symbol . $invoice_entry->amount; ?> </td>
                    </tr>
                <?php } 
                $grand_total = $this->crud_model->calculate_invoice_total_amount($row['invoice_id']); ?>
            </div>
            <!-- INVOICE ENTRY ENDS HERE-->
            </tbody>
        </table>
        <table width="100%" border="0" >    
            <tr>
                <td align="right" width="80%"><?= get_phrase('subtotal'); ?> :</td>
                <td align="right"><?= $currency_symbol . $row['total']; ?></td>
            </tr>
            <tr>
                <td align="right" width="80%"><?= get_phrase('amount_paid'); ?> :</td>
                <td align="right"><?= $currency_symbol . $row['paid']; ?></td>
            </tr>
            <tr>
                <td align="right" width="80%"><?= get_phrase('amount_remain'); ?> :</td>
                <td align="right"><?= $currency_symbol . $row['total'] - $row['paid']; ?></td>
            </tr>
            
            <tr>
                <td colspan="2"><hr style="margin:0px;"></td>
            </tr>
            <tr>
                <td align="right" width="80%"><h4><?= get_phrase('grand_total'); ?> :</h4></td>
                <td align="right"><h4><?= $currency_symbol . $grand_total; ?> </h4></td>
            </tr>
        </table>

    </div>
    <br>
    <a @click="pos" class="btn btn-primary hidden-print">
        <i class="fas fa-print"></i> &nbsp;
        print text
    </a>
    <br><br>


<?php endforeach; ?>

</div><!-- vue end -->
<script type="text/javascript">
value = {
  "invoice": [
    {
      "invoice_id": "29",
      "patient_id": "7",
      "hr_id": "9",
      "title": "Laboratorist",
      "paid": "0",
      "invoice_entries": "[{\"item\":\"3:Amoxicillin:India\",\"quantity\":\"6\",\"amount\":\"20\"}]",
      "total": "120",
      "creation_timestamp": "1615824962",
      "status": "unpaid"
    }
  ],
  "system_name": "Chashm Noor Hospital",
  "address": "Ghazni",
  "phone": "0700279779"
};
var print = new Vue({
    el: "#print",
    data: {
        url: "<?= site_url('/hr/pos')?>",
        contents: {
            "contents" : <?= json_encode($data); ?>,
        }
    },
    methods: {
        pos(){
            console.log('this.content', this.contents)
            printJS({
                printable: this.contents.invoice_entries,
                type: 'json',
                properties: ['item', 'quantity', 'amount'],
                header: '<h4 class="custom-h3">My custom header</h4>',
                style: '.custom-h3 { color: black; }'
            });
            // axios.post(this.url, this.contents)
            // .then(function(response) {
            //     console.log(response);
            // })
            // .catch(function (error) {
            //     alert("error", error);
            // })
        }
    }
});
    function PrintElem(elem)
    {
        printContent($(elem).html());
    }

    function printContent(data)
    {
        content = `
        <html>
            <head></head>
            <body>
                ${data}
            </body>
        </html>
        `;
        return content;
    }

</script>