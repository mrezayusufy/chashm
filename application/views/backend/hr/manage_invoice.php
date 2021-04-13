<?php 
$department = $this->session->userdata('department');

if($department == 'Pharmacist')
    $medicines = $this->db->get('medicine')->result_array();
$hrs = $this->db->get('hr')->result_array();
?>
<div id="app">
    <button @click="AddInvoice" class="btn btn-primary pull-right">
        <i class="fas fa-plus"></i>&nbsp;<?= get_phrase('add_invoice'); ?>
    </button>
    <div style="clear:both;"></div>
    <br>
    <div v-if="loading" class="loading">
        <div class="spinner-border"></div>
    </div>
     
    <table class="table table-bordered table-striped datatable" id="table-2">
        <thead>
            <tr>
                <th><?= get_phrase('invoice_id'); ?></th>
                <th><?= get_phrase('title'); ?></th>
                <th><?= get_phrase('patient'); ?></th>
                <th><?= get_phrase('doctor'); ?></th>
                <th><?= get_phrase('creation_date'); ?></th>
                <th><?= get_phrase('total'); ?></th>
                <th><?= get_phrase('paid'); ?></th>
                <th><?= get_phrase('remain'); ?></th>
                <th><?= get_phrase('status'); ?></th>
                <th><?= get_phrase('options'); ?></th>
            </tr>
        </thead>

        <tbody>
            <tr v-for="i in invoices">
                <td>{{ i.invoice_id }} </td>
                <td>{{ i.title }}</td>
                <td>#{{ i.patient_name }}</td>
                <td>{{ i.hr_name }}</td>
                <td>{{ i.creation_timestamp | formatDate }}</td>
                <td>{{ i.total }}</td>
                <td>{{ i.paid }}</td>
                <td>{{ i.total - i.paid }}</td>
                <td>
                    <div class="status btn btn-sm" :class="[i.status == 'paid' ? 'btn-primary' : 'btn-danger']">{{ i.status }}</div>
                </td>
                <td>
                    <?php if ($department == "Accountant") : ?>
                        <a @click="editModal=true;selectInvoice(i);" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i></a>
                    <?php endif; ?>
                    <a @click="selectInvoice(i); viewInvoice=true" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                    <a @click="deleteModal=true;selectInvoice(i)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                </td>
            </tr>

        </tbody>
    </table>

    <view-modal v-if="viewInvoice" @close="clearAll" >
        <div slot="header">
            <h4 class="modal-title"><?= get_phrase('view_invoice')?> : {{chooseInvoice.invoice_id}}</h4>
        </div>
        <div slot="body" class="modal-body" :style="modalStyle">
            <?php include 'show_invoice.php';?>
        </div>
        <div slot="footer">
            <div slot="footer" class="modal-footer">
                <div class="col-sm-3 control-label col-sm-offset-2">
                    <button @click="pos" class="btn btn-success">
                        <i class="fas fa-print"></i> <?= get_phrase('print'); ?>
                    </button>
                </div>
            </div>
        </div>
    </view-modal>
    <!-- edit invoice modal -->
    <edit-modal v-if="editModal" @close="clearAll" >
        <div slot="header">
            <h4 class="modal-title"><?= get_phrase('edit_invoice')?> : {{ chooseInvoice.invoice_id }}</h4>
        </div>
        <div slot="body" class="modal-body" :style="modalStyle">
            <?php include 'update_invoice.php';?>
        </div>
        <div slot="footer">
            <!-- submit -->
            <div class="modal-footer">
                <div class="col-sm-offset-3 col-sm-8">
                    <button class="btn btn-primary" @click="updateInvoice(chooseInvoice)">
                        <?= get_phrase('update_invoice'); ?></button>
                </div>
            </div>
        </div>
    </edit-modal>
    </view-modal>
    <!-- add invoice modal -->
    <add-modal v-if="addModal" @close="clearAll" >
        <div slot="header">
            <h4 class="modal-title"><?= get_phrase('add_invoice')?> : {{ chooseInvoice.invoice_id }}</h4>
        </div>
        <div slot="body" class="modal-body" :style="modalStyle">
            <?php include 'add_invoice.php';?>
        </div>
        
    </add-modal>
    <!-- delete modal -->
    <delete-modal v-if="deleteModal" @close="clearAll" >
        <div slot="header">
            <h4 class="modal-title" style="text-align:center;"><?= get_phrase('Are_you_sure_to_delete_this_information?')?></h4>
        </div>
        <div slot="body" class="modal-body" :style="{textAlign: 'center'}">
            <h4 class="modal-title"><?= get_phrase('delete_invoice')?> : {{ chooseInvoice.invoice_id }}</h4>
        </div>
        <div slot="footer">
            <!-- submit -->
            <div class="modal-footer">
                <div class="col-sm-offset-3 col-sm-8">
                    <button class="btn btn-danger" @click="deleteInvoice">
                        <?= get_phrase('delete_invoice'); ?></button>
                </div>
            </div>
        </div>
    </delete-modal> 
</div>

<script type="text/javascript">
    Vue.component("view-modal", {
        template: "#modal-template"
    });
    Vue.component("add-modal", {
        template: "#modal-template"
    });
    Vue.component("edit-modal", {
        template: "#modal-template"
    });
    Vue.component("delete-modal", {
        template: "#modal-template"
    });
    Vue.component("patient-select", VueSelect.VueSelect);
    Vue.component("hr-select", VueSelect.VueSelect);
    var app = new Vue({
        el: "#app",
        data: {
            modalStyle: {
                height: screen.height - 280 + 'px',
                overflow: 'auto'
            },
            loading: true,
            viewInvoice: false,
            home: '<?= site_url('HR/');?>',
            editModal: false,
            addModal: false,
            deleteModal: false,
            myHeight: screen.height - 250,
            invoices: [],
            patients: [],
            hrs: <?= json_encode($hrs) ?>,
            api: '<?= site_url('HR/invoice/'); ?>',
            chooseInvoice: {},
            formValidate: [],
            number: 1,
            total: "",
            medicines: <?= json_encode($medicines);?>,
            pos_api: "<?= site_url('/hr/pos/')?>",
        },
        created() {
            this.showAll();
            this.getPatients();
        },
        mounted() {
            this.showAll();
        },
        updated() {
            this.showAll();
        },
        methods: {
            showAll() {
                axios.get(`${this.api}/list`)
                    .then((response)=> app.setData(response.data))
                    .catch((error)=> console.log('error', error));
            },
            setSelected(option) {
                console.log('option', option);
            },
            getPatients(){
                axios.get(this.home + 'patient_api/get')
                    .then((response) => {
                        patients = response.data.patients;
                        app.patients = patients;
                        app.loading = false;
                    })
                    .catch((error) => {
                        iziToast.error({
                            title: 'Error',
                            message: ': ' + error,
                            position: 'topRight'
                        })
                    });
            },
            updateInvoice(invoice) {
                var formData = app.formData(invoice);
                console.log('i', formData);
                axios
                    .post(this.api + "edit/" + invoice.invoice_id, formData)
                    .then((response) => {
                        if (response.data.error) {
                            app.formValidate = response.data.msg;
                        } else {
                            iziToast.success({
                               title: 'Successs', 
                               message: 'Invoice Paid successfully.', 
                               position: 'topRight'
                           });
                           app.clearAll();
                        }
                    });
            },
            deleteInvoice(){
                axios
                    .delete(this.api + "delete/" + app.chooseInvoice.invoice_id)
                    .then((response) => {
                        iziToast.success({
                               title: 'Delete', 
                               message: ': ' + response.data.msg, 
                               position: 'topRight'
                           });
                           app.clearAll();
                     })
                    .catch((error) => { 
                        iziToast.error({
                               title: 'Not Deleted', 
                               message: ': ' + error, 
                               position: 'topRight'
                           });
                    });
            }, 
            AddInvoice(){
                window.location.replace("/HR/invoice_add");
            },
            selectInvoice(invoice){
                axios
                    .get(this.api+"get/"+invoice.invoice_id)
                    .then((response) => app.chooseInvoice = response.data.invoice)
                    .catch((error) => console.log('error', error));
            },
            formData(obj){
                var formData = new FormData();
                for (var key in obj) {
                    formData.append(key, obj[key]);
                }
                console.log('formData', formData);
                return formData;
            },
            setData(data) {
                app.invoices = data.invoices;
                app.total = data.total;
                app.loading = false;
            },
            pos(){
                printJS({
                    style: "#print table{ width: 75mm !important;} tr { border: 1px solid !important; border-collapse: collapse;} h4 { padding: 0; margin:10px 0 0 0;} hr {margin:0px;padding:0px;}",
                    printable: "print",
                    type: "html",
                });    
            },            
            getRandomInt(max) {
                return Math.floor(Math.random() * Math.floor(max));
            },
            clearAll(){
                app.loading = false;
                app.formValidate = false;
                app.chooseInvoice = {};
                app.editModal = false;
                app.addModal = false;
                app.deleteModal = false;
                app.viewInvoice = false;
                app.refresh();
                $(".page-body").removeClass("modal-open");
            },
            refresh(){
                app.showAll();
            }

        },
        computed: {
            itemName: function(){
                return this.chooseInvoice
            }
        },
        filters: {
            formatDate(date) {
                return moment.unix(date).format('L');
            },
            itemName(item) {
                var name = item.split(":");
                var res = `${name[0]} ${name[1] ? name[1] : " "}`;
                return name[0];
            }
        }
    });
    jQuery(window).load(function() {
        
        var $ = jQuery;
         
        $('.modal-body').css("height", screen.height - 250);
        $('.modal-body').css("overflow", "auto");
        $("#table-2").dataTable({
            "sPaginationType": "bootstrap",
            "aaSorting": [
                [4, "desc"]
            ],
            "aoColumnDefs": [
                { "bSearchable": false, "aTargets": [ 3,4,5,6 ] }
            ],
            "sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>"
        });

        $(".dataTables_wrapper select").select2({
            minimumResultsForSearch: -1
        });

        // Highlighted rows
        $("#table-2 tbody input[type=checkbox]").each(function(i, el) {
            var $this = $(el),
                $p = $this.closest('tr');

            $(el).on('change', function() {
                var is_checked = $this.is(':checked');

                $p[is_checked ? 'addClass' : 'removeClass']('highlight');
            });
        });

        $(".pagination a").click(function(ev) {
            replaceCheckboxes();
        });
        $('[class^="col-sm-"]*').css({
            "padding-left": "3px",
            "padding-right": "3px"
        });
    });
</script>