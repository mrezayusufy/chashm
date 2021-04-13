<div class="sidebar-menu">
    <header class="logo-env" >

        <!-- logo -->
        <div class="logo">
            <a href="<?= site_url('login'); ?>">
                <img src="<?= base_url('uploads/logo.png');?>"  style="max-height:60px;"/>
            </a>
        </div>

        <!-- logo collapse icon -->
        <div class="sidebar-collapse">
            <a href="#" class="sidebar-collapse-icon with-animation">

                <i class="entypo-menu"></i>
            </a>
        </div>

        <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
        <div class="sidebar-mobile-menu visible-xs">
            <a href="#" class="with-animation">
                <i class="entypo-menu"></i>
            </a>
        </div>
    </header>
    <div class="sidebar-user-info">

        <div class="sui-normal">
            <a href="#" class="user-link">
                <img src="<?= $this->crud_model->get_image_url($this->session->userdata('login_type'), $this->session->userdata('login_user_id'));?>" alt="" class="img-circle" style="height:44px;">

                <span><?= get_phrase('welcome'); ?>,</span>
                <strong><?php
                    echo $this->db->get_where($this->session->userdata('login_type'), array($this->session->userdata('login_type') . '_id' =>
                        $this->session->userdata('login_user_id')))->row()->name;
                    ?>
                </strong>
            </a>
        </div>

        <div class="sui-hover inline-links animate-in"><!-- You can remove "inline-links" class to make links appear vertically, class "animate-in" will make A elements animateable when click on user profile -->
            <a href="<?= site_url('Admin/manage_profile');?>">
                <i class="entypo-pencil"></i>
                <?= get_phrase('edit_profile'); ?>
            </a>

            <a href="<?= site_url('Admin/manage_profile');?>">
                <i class="entypo-lock"></i>
                <?= get_phrase('change_password'); ?>
            </a>

            <span class="close-sui-popup">×</span><!-- this is mandatory -->
        </div>
    </div>

    <ul id="main-menu" class="">
        <!-- add class "multiple-expanded" to allow multiple submenus to open -->
        <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->


        <!-- DASHBOARD -->
        <li class="<?php if ($page_name == 'dashboard') echo 'active'; ?> ">
            <a href="<?= site_url('Admin/dashboard');?>">
                <i class="fas fa-desktop"></i>
                <span><?= get_phrase('dashboard'); ?></span>
            </a>
        </li>

        <li class="<?php if ($page_name == 'manage_department' || $page_name == 'department_facilities') echo 'active'; ?> ">
            <a href="<?= site_url('Admin/department');?>">
                <i class="fas fa-sitemap"></i>
                <span><?= get_phrase('department'); ?></span>
            </a>
        </li>

        <li class="<?php if ($page_name == 'manage_hr') echo 'active'; ?>">
            <a href="<?= site_url('Admin/hr');?>">
                <i class="fas fa-user-md"></i>
                <span><?= get_phrase('HR'); ?></span>
            </a>
        </li>

        <li class="<?php if ($page_name == 'staff') echo 'active'; ?> ">
            <a href="<?= site_url('Admin/staff');?>">
                <i class="fas fa-user"></i>
                <span><?= get_phrase('staff'); ?></span>
            </a>
        </li>

        <li class="<?php if ($page_name == 'salary') echo 'active'; ?> ">
            <a href="<?= site_url('Admin/salary');?>">
                <i class="fas fa-money-bill-alt"></i>
                <span><?= get_phrase('salary'); ?></span>
            </a>
        </li>

        <li class="<?php if ($page_name == 'invoice') echo 'active'; ?> ">
            <a href="<?= site_url('Admin/invoice');?>">
                <i class="fas fa-receipt"></i>
                <span><?= get_phrase('invoice'); ?></span>
            </a>
        </li>

        <li class="<?php if ($page_name == 'balance') echo 'active'; ?> ">
            <a href="<?= site_url('Admin/balance');?>">
                <i class="fas fa-receipt"></i>
                <span><?= get_phrase('balance'); ?></span>
            </a>
        </li>

        <li class="<?php if ($page_name == 'transaction') echo 'active'; ?> ">
            <a href="<?= site_url('Admin/transaction');?>">
                <i class="fas fa-receipt"></i>
                <span><?= get_phrase('transaction'); ?></span>
            </a>
        </li>

        <li class="<?php if ($page_name == 'report') echo 'active'; ?> ">
            <a href="<?= site_url('Admin/report');?>">
                <i class="fas fa-receipt"></i>
                <span><?= get_phrase('report'); ?></span>
            </a>
        </li>

        <li class="<?php if ($page_name == 'manage_patient') echo 'active'; ?> ">
            <a href="<?= site_url('Admin/patient');?>">
                <i class="fas fa-user"></i>
                <span><?= get_phrase('patient'); ?></span>
            </a>
        </li>

        <li class="<?php if ($page_name == 'show_medicine') echo 'active'; ?> ">
            <a href="<?= site_url('Admin/medicine');?>">
                <i class="fas fa-capsules"></i>
                <span><?= get_phrase('medicine'); ?></span>
            </a>
        </li>

        <li class="<?php if ($page_name == 'system_settings') echo 'active'; ?> ">
            <a href="<?= site_url('Admin/system_settings');?>">
                <i class="fas fa-wrench"></i>
                <span><?= get_phrase('system_settings'); ?></span>
            </a>
        </li>

        <!-- ACCOUNT -->
        <li class="<?php if ($page_name == 'manage_profile') echo 'active'; ?> ">
            <a href="<?= site_url('Admin/manage_profile');?>">
                <i class="fas fa-user"></i>
                <span><?= get_phrase('account'); ?></span>
            </a>
        </li>



    </ul>

</div>
