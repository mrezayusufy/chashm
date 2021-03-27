<?php 
$department = $this->session->userdata('department');
?>
<div class="sidebar-menu">
    <header class="logo-env" >

        <!-- logo -->
        <div class="logo" style="">
            <a href="<?php echo site_url('login'); ?>">
                <img src="<?php echo base_url('uploads/logo.png');?>"  style="max-height:60px;"/>
            </a>
        </div>

        <!-- logo collapse icon -->
        <div class="sidebar-collapse" style="">
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
                <img src="<?php echo $this->crud_model->get_image_url($this->session->userdata('login_type'), $this->session->userdata('login_user_id'));?>" alt="" class="img-circle" style="height:44px;">

                <span><?php echo get_phrase('welcome'); ?>,</span>
                <strong><?php
                    echo $this->session->userdata('name');
                    ?>
                </strong>
            </a>
        </div>

        <div class="sui-hover inline-links animate-in"><!-- You can remove "inline-links" class to make links appear vertically, class "animate-in" will make A elements animateable when click on user profile -->				
            <a href="<?php echo site_url('hr/manage_profile');?>">
                <i class="entypo-pencil"></i>
                <?php echo get_phrase('edit_profile'); ?>
            </a>

            <a href="<?php echo site_url('hr/manage_profile');?>">
                <i class="entypo-lock"></i>
                <?php echo get_phrase('change_password'); ?>
            </a>

            <span class="close-sui-popup">×</span><!-- this is mandatory -->			
        </div>
    </div>

    <ul id="main-menu" class="">

        <!-- DASHBOARD -->
        <li class="<?php if ($page_name == 'dashboard') echo 'active'; ?> ">
            <a href="<?php echo site_url('hr');?>">
                <i class="fas fa-desktop"></i>
                <span><?php echo get_phrase('dashboard'); ?></span>
            </a>
        </li>
        <!-- Receptionist -->
        <?php if ($department == 'Receptionist') {?>
        <li class="<?php if ($page_name == 'manage_patient' ||
            ($page_name == 'patient' && $menu_check == 'from_patient')) echo 'active'; ?> ">
                <a href="<?php echo site_url('hr/patient');?>">
                    <i class="fas fa-user"></i>
                    <span><?php echo get_phrase('patient'); ?></span>
                </a>
        </li>
        <?php } ?>
        <!-- accountant -->
        <?php if ($department == 'Accountant') {?>
        <li class="<?php if ($page_name == 'manage_salary' ||
            ($page_name == 'salary' && $menu_check == 'from_salary')) echo 'active'; ?> ">
                <a href="<?php echo site_url('hr/salary_manage');?>">
                    <i class="fas fa-user"></i>
                    <span><?php echo get_phrase('salary'); ?></span>
                </a>
        </li>
        <?php } ?>
        <!-- Pharmacist -->
        <?php if ($department == 'Pharmacist') {?>
        <li class="<?php if ($page_name == 'manage_medicine' || $page_name == 'medicine_category' ) echo 'opened active'; ?> ">
            <a href="#">
                <i class="fas fa-medkit"></i>
                <span><?php echo get_phrase('medicine'); ?></span>
            </a>
            <ul>
                <li class="<?php if ($page_name == 'manage_medicine') echo 'active'; ?> ">
                    <a href="<?php echo site_url('hr/medicine'); ?>">
                        <i class="entypo-dot"></i>
                        <span><?php echo get_phrase('manage_medicines'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'manage_medicine_category') echo 'active'; ?> ">
                    <a href="<?php echo site_url('hr/medicine_category'); ?>">
                        <i class="entypo-dot"></i>
                        <span><?php echo get_phrase('manage_medicine_category'); ?></span>
                    </a>
                </li>
            </ul>
        </li>
        <?php } ?>
        <li class="<?php if ($page_name == 'add_invoice' || $page_name == 'manage_invoice') echo 'opened active has-sub'; ?> ">
            <a href="#">
                <i class="fas fa-list-alt"></i>
                <span><?php echo get_phrase('invoice'); ?></span>
            </a>
            <ul>
                <li class="<?php if ($page_name == 'add_invoice') echo 'active'; ?>">
                    <a href="<?php echo site_url('hr/invoice_add');?>">
                        <i class="fas fa-plus"></i>
                        <span><?php echo get_phrase('add_invoice'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'manage_invoice') echo 'active'; ?>">
                    <a href="<?php echo site_url('hr/invoice_manage');?>">
                        <i class="fas fa-align-justify"></i>
                        <span><?php echo get_phrase('manage_invoice'); ?></span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="<?php if ($page_name == 'edit_profile') echo 'active'; ?> ">
            <a href="<?php echo site_url('hr/manage_profile');?>">
                <i class="fas fa-user"></i>
                <span><?php echo get_phrase('profile'); ?></span>
            </a>
        </li>

    </ul>

</div>