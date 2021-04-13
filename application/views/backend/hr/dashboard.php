<div id="app">
    <div class="row">

        <div class="col-sm-3">
            <a href="<?= site_url('HR/invoice_manage'); ?>">
                <div class="tile-stats tile-white-red">
                    <div class="icon"><i class="fas fa-money-bill-alt"></i></div>
                    <div class="num" data-start="0" data-end="<?= $count_invoice ?>" data-duration="1500" data-delay="0"><?= $count_invoice; ?></div>
                    <h3><?= get_phrase('invoice') ?></h3>
                </div>
            </a>
        </div>
        <?php if ($department == 'Accountant') { ?>
            <div class="col-sm-3">
                <a href="<?= site_url('HR/invoice_manage'); ?>">
                    <div class="tile-stats tile-white-red">
                        <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
                        <div class="num" data-start="0" data-end="<?= $pa ?>" data-duration="1500" data-delay="0"><?= $pa; ?></div>
                        <h3><?= get_phrase('paid_invoice') ?></h3>
                    </div>
                </a>
            </div>
        <?php } else { ?>
            <div class="col-sm-3">
                <a href="<?= site_url('HR/invoice_manage'); ?>">
                    <div class="tile-stats tile-white-red">
                        <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
                        <div class="num" data-start="0" data-end="<?= $p ?>" data-duration="1500" data-delay="0"><?= $p ?></div>
                        <h3><?= get_phrase('paid_invoice') ?></h3>
                    </div>
                </a>
            </div>
        <?php } ?>
        <?php if ($department == 'Accountant') { ?>
            <div class="col-sm-3">
                <a href="<?= site_url('HR/patient_manage'); ?>">
                    <div class="tile-stats tile-white-red">
                        <div class="icon"><i class="fas fa-user"></i></div>
                        <div class="num" data-start="0" data-end="<?= $this->db->count_all('patient'); ?>" data-duration="1500" data-delay="0"><?= $this->db->count_all('patient'); ?></div>
                        <h3><?= get_phrase('patient') ?></h3>
                    </div>
                </a>
            </div>
        <?php } ?>

        <?php if ($department == 'Pharmacist') { ?>
            <div class="col-sm-3">
                <a href="<?= site_url('HR/medicine_manage'); ?>">
                    <div class="tile-stats tile-white-red">
                        <div class="icon"><i class="fas fa-user"></i></div>
                        <div class="num" data-start="0" data-end="<?= $this->db->count_all('medicine'); ?>" data-duration="1500" data-delay="0"><?= $this->db->count_all('patient'); ?></div>
                        <h3><?= get_phrase('medicine') ?></h3>
                    </div>
                </a>
            </div>
        <?php } ?>
    </div>
    <div style="clear:both;"></div>
    <br>
    <!-- hr invoice -->
    <?php if($department == 'Accountant'): ?>
    <div class="col-sm-12">
        <hr-select id="hr_id" :options="$store.state.hrs" label="hr_id" :reduce="o => `${o.hr_id}`" :get-option-label="o => `${o.hr_id} ${o.first_name} ${o.last_name}`" :create-option="o => ({ first_name: first_name, last_name: last_name, hr_id: hr_id })" @input="setActiveHr" :value="$store.state.hr">
            <template slot="option" slot-scope="option">
                ID:{{ option.hr_id }} _ {{ option.first_name }} _ {{ option.last_name }} _ {{ option.name }}
            </template>
            <template slot="selected-option" slot-scope="option">
                <div class="selected d-center">
                    ID:{{ option.hr_id }} _ {{ option.first_name }} _ {{ option.last_name }} _ {{ option.name }}
                </div>
            </template>
        </hr-select>
        <div style="clear:both;"></div>
        <br>
        <div v-if="$store.state.loading==='pending'" class="loading">
            <div class="spinner-border"></div>
        </div>
        <div v-if="$store.state.loading==='idle' || $store.state.invoiceData.length === 0" class="col-md-12">
            <div>No data</div>
        </div>
        <table 
            v-if="$store.state.loading==='finished' && $store.state.invoiceData.length > 0"
            class="table table-bordered table-striped datatable display" id="invoice-table">
            <thead>
                <tr>
                    <th><?= get_phrase('index'); ?></th>
                    <th><?= get_phrase('doctor'); ?></th>
                    <th><?= get_phrase('date'); ?></th>
                    <th><?= get_phrase('total'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(i, index) in $store.state.invoiceData" :key="index">
                    <td>{{ index }} </td>
                    <td>{{ i.hr_name }}</td>
                    <td>{{ i.daily }}</td>
                    <td>{{ i.total }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align:right">Total: </th>
                    <th>{{ $store.state.totalInvoice }}</th>
                </tr>
            </tfoot>
        </table>
        
    </div>
    <?php endif; ?>
</div>

<script>
    Vue.component('hr-select', VueSelect.VueSelect);
    const store = new Vuex.Store({
        state: {
            loading: 'idle',
            hrs: <?= json_encode($hrs) ?>,
            invoiceData: [],
            hr: "10",
            limit: "30",
            totalInvoice: 0,
            message: ""
        },
        mutations: {
            setActiveHr(state, hr) {
                state.hr = hr;
            },
            setInvoice(state, data) {
                state.loading = 'pending';
                var invoices = data.invoices;
                state.invoiceData = invoices;
                if(invoices.length > 0) {
                    var invoiceTotal = [];
                    invoices.forEach(i=> invoiceTotal.push(parseInt(i.total)) );
                    state.totalInvoice = invoiceTotal.reduce((a,c) => a + c);
                } else {
                    state.totalInvoice = 0;
                }
                state.loading = 'finished';
            },
        }
    });
    var app = new Vue({
        el: "#app",
        store,
        computed: {
            store() {
                return this.$store.state;
            }
        },
        data: {
            api: '<?= site_url('HR/invoice'); ?>',
        },
        created() {
            this.getInvoice();
        },
        updated() {
            this.getInvoice();
        },
        methods: {
            getInvoice() {
                var limit = this.$store.state.limit;
                var hr = this.$store.state.hr;
                axios.get(`${this.api}/all/0/${limit}/0/${hr}`)
                    .then((res) => app.setInvoice(res.data))
                    .catch((err) => console.log('err', err));
            },
            setActiveHr(val) {
                this.$store.commit('setActiveHr', val);
            },
            setInvoice(data) {
                this.$store.commit('setInvoice', data);
            }, 
        }
    });
    jQuery(window).load(function(){
        var $ = jQuery;
        $(document).ready(function() {
            $("#invoice-table").DataTable({
                "sPaginationType": "bootstrap",
                "sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>"
            });
            $("#invoice-table tbody input[type=checkbox]").each(function(i, el) {
                var $this = $(el),
                    $p = $this.closest('tr');

                $(el).on('change', function() {
                    var is_checked = $this.is(':checked');

                    $p[is_checked ? 'addClass' : 'removeClass']('highlight');
                });
            }); 
        });
    });
</script>