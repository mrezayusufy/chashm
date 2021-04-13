<div id="app">
    <button @click="addModal=true" class="btn btn-primary pull-right">
        <i class="fas fa-plus"></i>&nbsp;<?= get_phrase('add_patient'); ?>
    </button>
    <div style="clear:both;"></div>
    <br>
    <div v-if="loading" class="loading">
        <div class="spinner-border"></div>
    </div>
    <table class="table table-bordered table-striped datatable" id="table-2">
        <thead>
            <tr>
                <th><?= get_phrase('patient_ID'); ?></th>
                <th><?= get_phrase('name'); ?></th>
                <th><?= get_phrase('father_name'); ?></th>
                <th><?= get_phrase('phone'); ?></th>
                <th><?= get_phrase('gender'); ?></th>
                <th><?= get_phrase('age'); ?></th>
                <th><?= get_phrase('created_at'); ?></th>
                <th><?= get_phrase('options'); ?></th>
            </tr>
        </thead>

        <tbody>
            <tr v-for="i in patients">
                <td>{{ i.patient_id }} </td>
                <td>{{ i.name }} </td>
                <td>{{ i.father_name }}</td>
                <td>{{ i.phone }}</td>
                <td>{{ i.gender }}</td>
                <td>{{ i.age }}</td>
                <td>{{ i.created_at | formatDate }}</td>
                <td>
                    <a @click="print(i)" class="btn btn-success btn-sm"><i class="fas fa-print"></i></a>
                    <a @click="editModal=true; selectPatient(i)" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i></a>
                    <a @click="deleteModal=true; selectPatient(i)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> </a>
                </td>
            </tr>
        </tbody>
    </table>

    <edit-modal v-if="editModal" @close="clearAll();">
        <div slot="header">
            <h4 class="modal-title"><?= get_phrase('edit_patient') ?> : {{choosePatient.patient_id}}</h4>
        </div>
        <div slot="body" class="modal-body" :style="modalStyle">
            <?php include 'update_patient.php'; ?>
        </div>
        <div slot="footer" class="modal-footer">
            <div class="col-sm-3 control-label col-sm-offset-2">
                <button type="submit" class="btn btn-success" @click="updatePatient(choosePatient.patient_id)">
                    <i class="fas fa-check"></i> <?= get_phrase('update'); ?>
                </button>
            </div>
        </div>
    </edit-modal>

    <add-modal v-if="addModal" @close="clearAll();">
        <div slot="header">
            <h4 class="modal-title"><?= get_phrase('add_patient') ?></h4>
        </div>
        <div slot="body" class="modal-body" :style="modalStyle">
            <?php include 'create_patient.php'; ?>
        </div>
        <!-- create -->
        <div slot="footer" class="modal-footer">
            <div class="col-sm-3 control-label col-sm-offset-2">
                <button type="submit" class="btn btn-success" @click="createPatient()">
                    <i class="fas fa-check"></i> <?= get_phrase('create');?>
                </button>
            </div>
        </div>
    </add-modal>

    <delete-modal v-if="deleteModal" @close="clearAll();" >
        <div slot="header">
            <h4 class="modal-title" style="text-align:center;"><?= get_phrase('Are_you_sure_to_delete_this_information?')?></h4>
        </div>
        <div slot="body" class="modal-body" :style="{textAlign: 'center'}">
            <h4 class="modal-title"><?= get_phrase('delete_patient')?> : {{ choosePatient.patient_id }}</h4>
        </div>
        <div slot="footer">
            <!-- submit -->
            <div class="modal-footer">
                <div class="col-sm-offset-3 col-sm-8">
                    <button class="btn btn-danger" @click="deletePatient(choosePatient.patient_id)">
                        <?= get_phrase('delete_patient'); ?></button>
                </div>
            </div>
        </div>
    </delete-modal>
</div>

<script type="text/javascript">
    Vue.component("edit-modal", {
        template: "#modal-template"
    });
    Vue.component("add-modal", {
        template: "#modal-template"
    });
    Vue.component("delete-modal", {
        template: "#modal-template"
    });
    var app = new Vue({
        el: "#app",
        data: {
            modalStyle: {
                height: screen.height - 280 + 'px',
                overflow: 'auto'
            },
            loading: true,
            patients: [],
            api: '<?= site_url('HR/patient_api/'); ?>',
            editModal: false,
            addModal: false,
            deleteModal: false,
            myHeight: screen.height - 250,
            choosePatient: {},
            newPatient: {
                name: "",
                father_name: "",
                address: "",
                phone: "",
                gender: "",
                age: "",
            },
            title: "",
            content: "content",
            formValidate: [],
        },
        created() {
            this.showAll();
        },
        mounted() {
            this.showAll();
        },
        updated() {
            this.showAll();
        },
        methods: {
            print(patient) {
                var mywindow = window.open('', 'Patient Info', 'height=400,width=600');
                mywindow.document.write('<html><head>');
                mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/neon-theme.css'); ?>" type="text/css" />');
                mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/print.css'); ?>" type="text/css" />');
                mywindow.document.write('</head><body>');
                mywindow.document.write('<table>');
                mywindow.document.write('<tr><td></td><td>' + patient.patient_id + '</td><td></td><td>23</td></tr>');
                mywindow.document.write('<tr><td></td><td>' + moment().format('LT') + '</td><td></td><td>' + moment().format('LL') + ' </td></tr>');
                mywindow.document.write('<tr><td></td><td>' + patient.name + " _ " + patient.father_name + '</td><td></td><td>' + patient.age + ' </td></tr>');
                mywindow.document.write('<tr><td></td><td>' + patient.address + '</td><td></td><td>' + patient.gender + ' </td></tr>');
                mywindow.document.write('</table>');
                mywindow.document.write('</body></html>');
                mywindow.print();
            },
            showAll() {
                axios
                    .get(this.api + "list")
                    .then(function(response) {
                        app.setData(response.data.patients);
                    })
                    .catch(function(error) {
                        console.log('error', error);
                    });
            },
            createPatient() {
                app.loading = true;
                var formData = app.formData(app.newPatient);
                axios
                    .post(this.api + "add", formData)
                    .then(function(response) {
                        if (response.data.error) {
                            app.formValidate = response.data.msg;
                        } else {
                            iziToast.success({
                                title: 'Successs',
                                message: response.data.msg,
                                position: 'topRight'
                            });
                            app.clearAll();
                        }
                    })
                    .catch(function(error) {
                        alert(error);
                        iziToast.error({
                            title: 'Error',
                            message: "An Error has been aquered: " + error,
                            position: 'topRight'
                        });
                    });
            },
            deletePatient(id){
                axios
                    .delete(this.api + "delete/" + id)
                    .then(function(response) {
                        iziToast.success({
                               title: 'Delete', 
                               message: ': ' + response.data.msg, 
                               position: 'topRight'
                           });
                           app.clearAll();
                     })
                    .catch(function(){ 
                        iziToast.error({
                               title: 'Not Deleted', 
                               message: ': ' + response.data.msg, 
                               position: 'topRight'
                           });
                           app.clearAll();
                    });
            }, 
            updatePatient(id) {
                var formData = app.formData(app.choosePatient);
                axios
                    .post(this.api + "edit/" + id, formData)
                    .then((response) => {
                        if (response.data.error) {
                            app.formValidate = response.data.msg;
                        } else {
                            iziToast.success({
                                title: 'Successs',
                                message: response.data.msg,
                                position: 'topRight'
                            });
                            app.clearAll();
                        }
                    })
                    .catch((error) => {
                        alert(error);
                        iziToast.error({
                            title: 'Error',
                            message: "An Error has been aquered: " + error,
                            position: 'topRight'
                        });
                    });
            },
            formData(obj) {
                var formData = new FormData();
                for (var key in obj) {
                    formData.append(key, obj[key]);
                }
                return formData;
            },
            setData(data) {
                app.patients = data;
                app.loading = false;
            },
            changeGender(gender) {
                return (app.choosePatient.gender = gender); //update gender
            },
            pickGender(gender) {
                return (app.newPatient.gender = gender); //update gender
            },
            selectPatient(patient) {
                app.choosePatient = patient;
            },
            clearAll() {
                app.newPatient = {};
                app.choosePatient = {};
                app.loading = false;
                app.formValidate = false;
                app.editModal = false;
                app.addModal = false;
                app.deleteModal = false;
                app.refresh();
                $(".page-body").removeClass("modal-open");
            },
            refresh() {
                app.showAll();
            }
        },
        filters: {
            formatDate(date) {
                return moment.unix(date).format('L');
            }
        },
    });
    jQuery(window).load(function() {
        var $ = jQuery;
        $('.modal-body').css("height", screen.height - 250);
        $('.modal-body').css("overflow", "auto");
        $("#table-2").dataTable({
            "sPaginationType": "bootstrap",
            "aaSorting": [
                [0, "desc"]
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

        // Replace Checboxes
        $(".pagination a").click(function(ev) {
            replaceCheckboxes();
        });
    });
</script>