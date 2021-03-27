<div class="row">
    <div class="col-md-12 col-sm-12 clearfix" style="text-align:center;">
        <h2 style="font-weight:200; margin:0px;"><?php echo $system_name; ?></h2>
    </div>
    <!-- Raw Links -->
    <div class="col-md-12 col-sm-12 clearfix ">

        <ul class="list-inline links-list pull-left">
            <!-- Language Selector -->			
            <li class="dropdown language-selector">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true">
                    <i class="entypo-user"></i> <?php echo $this->session->userdata('department'); ?>
                </a>
            </li>
            
        </ul>

        <ul class="list-inline links-list pull-right">
            <li class="sep"></li>
            <li>
                <a href="<?php echo site_url('login/logout'); ?>">
                    <?php echo get_phrase('logout');?> &nbsp;<i class="fas fa-sign-out"></i>
                </a>
            </li>
        </ul>
    </div>

</div>

<hr style="margin-top:0px;" />