<?php 
$hr_id = $this->session->userdata('login_user_id');
$paid = $this->crud_model->total_count('paid', array('status' => 'paid', 'hr_id' => $hr_id ), 'invoice')->paid;
$p = $paid ? $paid : 0;
$paid_accountant = $this->crud_model->total_count('paid', array('status' => 'paid'), 'invoice')->paid;
$pa = $paid_accountant ? $paid_accountant : 0;
$department = $this->session->userdata('department');
$count_invoice = $this->crud_model->count_invoice();
?>
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
    <?php if($department == 'Accountant'){?>
    <div class="col-sm-3">
        <a href="<?= site_url('HR/invoice_manage'); ?>">
            <div class="tile-stats tile-white-red">
                <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
                <div class="num" data-start="0" data-end="<?= $pa ?>" data-duration="1500" data-delay="0"><?= $pa ; ?></div>
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
    <?php if( $department == 'Receptionist') { ?>
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

    <?php if( $department == 'Pharmacist') { ?>
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
