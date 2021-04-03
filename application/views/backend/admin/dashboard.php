<?php
$paid_accountant = $this->crud_model->total_count('paid', array('status' => 'paid'), 'invoice')->paid;
$pa = $paid_accountant ? $paid_accountant : 0;
$salary_paid = $this->crud_model->total_count('salary', array('status' => 'paid'), 'salary')->salary;
$sp = $salary_paid ? $salary_paid : 0;
$sm = $this->db->select('sum(salary) as y, month(from_unixtime(date)) as x')->where('status', 'paid')->group_by('month(from_unixtime(date))')->get('salary')->result_array();
$ii = $this->db->select('sum(paid) as y, date(from_unixtime(creation_timestamp)) as x')->where('status', 'paid')->group_by('date(from_unixtime(creation_timestamp))')->get('invoice')->result_array();
$im = $this->db->select('sum(paid) as y, month(from_unixtime(creation_timestamp)) as x')->where('status', 'paid')->group_by('month(from_unixtime(creation_timestamp))')->get('invoice')->result_array();
$patient = $this->db->select('count(patient_id) as y, date(from_unixtime(created_at)) as x')->group_by('date(from_unixtime(created_at))')->get('patient')->result_array();
?>
<div id="app">
    <div class="row">
        <div class="col-sm-3">
            <a href="<?= site_url('admin/doctor'); ?>">
                <div class="tile-stats tile-white tile-white-primary">
                    <div class="icon"><i class="fas fa-user-md"></i></div>
                    <div class="num" data-start="0" data-end="<?= $this->db->count_all('hr'); ?>" data-duration="1500" data-delay="0"><?= $this->db->count_all('hr'); ?></div>
                    <h3><?= get_phrase('HR') ?></h3>
                </div>
            </a>
        </div>

        <div class="col-sm-3">
            <a href="<?= site_url('admin/patient'); ?>">
                <div class="tile-stats tile-white-red">
                    <div class="icon"><i class="fas fa-user"></i></div>
                    <div class="num" data-start="0" data-end="<?= $this->db->count_all('patient'); ?>" data-duration="1500" data-delay="0"><?= $this->db->count_all('patient'); ?></div>
                    <h3><?= get_phrase('patient') ?></h3>
                </div>
            </a>
        </div>

        <div class="col-sm-3">
            <a href="<?= site_url('admin/invoice'); ?>">
                <div class="tile-stats tile-white-aqua">
                    <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    <div class="num" data-start="0" data-end="<?= $pa; ?>" data-duration="1500" data-delay="0"><?= $pa; ?></div>
                    <h3><?= get_phrase('income') ?></h3>
                </div>
            </a>
        </div>

        <div class="col-sm-3">
            <a href="<?= site_url('admin/invoice'); ?>">
                <div class="tile-stats tile-white-blue">
                    <div class="icon"><i class="fas fa-money-bill-alt"></i></div>
                    <div class="num" data-start="0" data-end="<?= $this->db->count_all('invoice'); ?>" data-duration="1500" data-delay="0"><?= $this->db->count_all('invoice'); ?></div>
                    <h3><?= get_phrase('total_invoice') ?></h3>
                </div>
            </a>
        </div>
    </div>

    <br />

    <div class="row">
        <div class="col-sm-3">
            <a href="<?= site_url('admin/salary'); ?>">
                <div class="tile-stats tile-white-cyan">
                    <div class="icon"><i class="fas fa-money-bill"></i></div>
                    <div class="num" data-start="0" data-end="<?= $this->db->count_all('salary'); ?>" data-duration="1500" data-delay="0"><?= $this->db->count_all('salary'); ?></div>
                    <h3><?= get_phrase('salary') ?></h3>
                </div>
            </a>
        </div>
        <div class="col-sm-3">
            <a href="<?= site_url('admin/salary'); ?>">
                <div class="tile-stats tile-white-cyan">
                    <div class="icon"><i class="fas fa-money-bill-alt"></i></div>
                    <div class="num" data-start="0" data-end="<?= $sp ?>" data-duration="1500" data-delay="0"><?= $sp ?></div>
                    <h3><?= get_phrase('salary_paid') ?></h3>
                </div>
            </a>
        </div>

        <div class="col-sm-3">
            <a href="<?= site_url('admin/department'); ?>">
                <div class="tile-stats tile-white-purple">
                    <div class="icon"><i class="fas fa-book"></i></div>
                    <div class="num" data-start="0" data-end="<?= $this->db->count_all('department'); ?>" data-duration="1500" data-delay="0"><?= $this->db->count_all('department'); ?></div>
                    <h3><?= get_phrase('department') ?></h3>
                </div>
            </a>
        </div>

        <div class="col-sm-3">
            <a href="<?= site_url('admin/medicine'); ?>">
                <div class="tile-stats tile-white-orange">
                    <div class="icon"><i class="fas fa-medkit"></i></div>
                    <div class="num" data-start="0" data-end="<?= $this->db->count_all('medicine'); ?>" data-duration="1500" data-delay="0"><?= $this->db->count_all('medicine'); ?></div>
                    <h3><?= get_phrase('medicine') ?></h3>
                </div>
            </a>
        </div>

        <div class="col-sm-12">
            <invoice-chart></invoice-chart>
        </div>
        <div class="col-sm-12">
            <invoice-monthly-chart></invoice-monthly-chart>
        </div>
        <div class="col-sm-12">
            <salary-monthly-chart></salary-monthly-chart>
        </div>
        <div class="col-sm-12">
            <patient-chart></patient-chart>
        </div>
    </div>
</div>
<script>
    var invoiceData = <?= json_encode($ii); ?>;
    var invoiceX = [];
    invoiceData.forEach(i=> {
        var x = moment(i.x).format('LL');
        invoiceX.push(x);
    });
    
    var invoiceY = invoiceData.forEach(i=> i.y);
    Vue.component('invoice-chart', {
        extends: VueChartJs.Bar,
        mounted() {
            this.renderChart({
                labels: invoiceX,
                datasets: [{
                    label: 'Invoice daily',
                    backgroundColor: "#00c0ef",
                    data: invoiceData,
                }]
            }, {
                responsive: true,
                maintainAspectRatio: false
            });
        }
    });
    var invoiceMData = <?= json_encode($im); ?>;
    var invoiceMX = [];
    invoiceMData.forEach(i=> {
        var x = moment(i.x).format('MMM');
        invoiceMX.push(x);
    });
     Vue.component('invoice-monthly-chart', {
        extends: VueChartJs.Bar,
        mounted() {
            this.renderChart({
                labels: invoiceMX,
                datasets: [{
                    label: 'Invoice monthly',
                    backgroundColor: "#00c0ef",
                    data: invoiceMData,
                }]
            }, {
                responsive: true,
                maintainAspectRatio: false
            });
        }
    });
     
    var salaryMData = <?= json_encode($sm); ?>;
    var salaryMX = [];
    salaryMData.forEach(i=> {
        var x = moment(i.x).format('MMM');
        salaryMX.push(x);
    });
     Vue.component('salary-monthly-chart', {
        extends: VueChartJs.Bar,
        mounted() {
            this.renderChart({
                labels: salaryMX,
                datasets: [{
                    label: 'Salary monthly',
                    backgroundColor: "#00b29e",
                    data: salaryMData,
                }]
            }, {
                responsive: true,
                maintainAspectRatio: false
            });
        }
    });
    var patientData = <?= json_encode($patient); ?>;
    var patientX = [];
    patientData.forEach(i=> {
        var x = moment(i.x).format('MMM');
        patientX.push(x);
    });
    Vue.component('patient-chart', {
        extends: VueChartJs.Bar,
        mounted() {
            this.renderChart({
                labels: patientX,
                datasets: [{
                    label: 'Patient daily',
                    backgroundColor: "#f56954",
                    data: patientData,
                }]
            }, {
                responsive: true,
                maintainAspectRatio: false
            });
        }
    });
    var app = new Vue({
        el: '#app',
        data: {
            loading: true,
            invoices: [],
            salaries: [],
            api: '<?= site_url(); ?>',
        },
        created(){
            this.getInvoiceList();
            this.getSalaryList();
            console.log('app.invoiceData', this.invoiceData);
        },
        methods: {
            getInvoiceList(){
                axios.get(this.api + 'api/invoice').then(r=>app.setInvoices(r.data.invoices)).catch(e=>alert(e));
            },
            getSalaryList(){
                axios.get(this.api + 'api/salary').then(r=>app.setSalaries(r.data.salaries)).catch(e=>alert(e));
            },
            setInvoices(response){
                app.invoices = response;
                app.loading = false;
            },
            setSalaries(response){
                app.invoices = response;
                app.loading = false;
            }
        }
    });
    // vue chart js bar by days for invoice income
   
</script>