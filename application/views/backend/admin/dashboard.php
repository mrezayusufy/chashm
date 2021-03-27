<?php 

$paid_accountant = $this->crud_model->total_count('paid', array('status' => 'paid'), 'invoice')->paid;
$pa = $paid_accountant ? $paid_accountant : 0;
$salary_paid = $this->crud_model->total_count('salary', array('status' => 'paid'), 'salary')->salary;
$sp = $salary_paid ? $salary_paid : 0;
?>
<div class="row">
    <div class="col-sm-3">
        <a href="<?= site_url('admin/doctor'); ?>">
            <div class="tile-stats tile-white tile-white-primary">
                <div class="icon"><i class="fas fa-user-md"></i></div>
                <div class="num" data-start="0" data-end="<?= $this->db->count_all('hr'); ?>"
                     data-duration="1500" data-delay="0"><?= $this->db->count_all('hr'); ?></div>
                <h3><?= get_phrase('HR') ?></h3>
            </div>
        </a>
    </div>

    <div class="col-sm-3">
        <a href="<?= site_url('admin/patient'); ?>">
            <div class="tile-stats tile-white-red">
                <div class="icon"><i class="fas fa-user"></i></div>
                <div class="num" data-start="0" data-end="<?= $this->db->count_all('patient'); ?>" 
                     data-duration="1500" data-delay="0"><?= $this->db->count_all('patient'); ?></div>
                <h3><?= get_phrase('patient') ?></h3>
            </div>
        </a>
    </div>

    <div class="col-sm-3">
        <a href="<?= site_url('admin/invoice'); ?>">
            <div class="tile-stats tile-white-aqua">
                <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
                <div class="num" data-start="0" data-end="<?= $pa; ?>" 
                     data-duration="1500" data-delay="0"><?= $pa; ?></div>
                <h3><?= get_phrase('income') ?></h3>
            </div>
        </a>
    </div>

    <div class="col-sm-3">
        <a href="<?= site_url('admin/invoice'); ?>">
            <div class="tile-stats tile-white-blue">
                <div class="icon"><i class="fas fa-money-bill-alt"></i></div>
                <div class="num" data-start="0" data-end="<?= $this->db->count_all('invoice'); ?>" 
                     data-duration="1500" data-delay="0"><?= $this->db->count_all('invoice'); ?></div>
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
                <div class="num" data-start="0" data-end="<?= $this->db->count_all('salary'); ?>" 
                     data-duration="1500" data-delay="0"><?= $this->db->count_all('salary'); ?></div>
                <h3><?= get_phrase('salary') ?></h3>
            </div>
        </a>
    </div>
    <div class="col-sm-3">
        <a href="<?= site_url('admin/salary'); ?>">
            <div class="tile-stats tile-white-cyan">
                <div class="icon"><i class="fas fa-money-bill-alt"></i></div>
                <div class="num" data-start="0" data-end="<?= $sp ?>" 
                     data-duration="1500" data-delay="0"><?= $sp ?></div>
                <h3><?= get_phrase('salary_paid') ?></h3>
            </div>
        </a>
    </div>

    <div class="col-sm-3">
        <a href="<?= site_url('admin/department'); ?>">
            <div class="tile-stats tile-white-purple">
                <div class="icon"><i class="fas fa-book"></i></div>
                <div class="num" data-start="0" data-end="<?= $this->db->count_all('department'); ?>" 
                     data-duration="1500" data-delay="0"><?= $this->db->count_all('department'); ?></div>
                <h3><?= get_phrase('department') ?></h3>
            </div>
        </a>
    </div>


    <div class="col-sm-3">
        <a href="<?= site_url('admin/medicine'); ?>">
            <div class="tile-stats tile-white-orange">
                <div class="icon"><i class="fas fa-medkit"></i></div>
                <div class="num" data-start="0" data-end="<?= $this->db->count_all('medicine'); ?>" 
                     data-duration="1500" data-delay="0"><?= $this->db->count_all('medicine'); ?></div>
                <h3><?= get_phrase('medicine') ?></h3>
            </div>
        </a>
    </div>
</div>

<br />
