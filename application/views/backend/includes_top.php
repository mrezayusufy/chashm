<link rel="stylesheet" href="<?= base_url('assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css');?>">
<link rel="stylesheet" href="<?= base_url('assets/css/font-icons/entypo/css/entypo.css');?>">
<!--<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">-->
<!--<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700" rel="stylesheet">-->
<link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.css');?>">
<link rel="stylesheet" href="<?= base_url('assets/css/neon-core.css');?>">
<link rel="stylesheet" href="<?= base_url('assets/css/neon-theme.css');?>">
<link rel="stylesheet" href="<?= base_url('assets/css/neon-forms.css');?>">
<link rel="stylesheet" href="<?= base_url('assets/css/custom.css');?>">
<link rel="stylesheet" href="<?= base_url('assets/css/vue-select.css');?>">
<link rel="stylesheet" href="<?= base_url('assets/izitoast/dist/css/iziToast.min.css');?>">
<script src="<?= base_url()?>assets/js/vue.min.js"></script>
<script src="<?= base_url()?>assets/js/vue-select.js"></script>
<!-- <script src="<?= base_url()?>assets/js/vuex.js"></script> -->
<script src="<?= base_url()?>assets/js/axios.min.js"></script>
<script src="<?php echo base_url('assets/js/chart.js');?>"></script>
<script src="<?php echo base_url('assets/js/vue-chart.js');?>"></script>
<script src="<?php echo base_url('assets/js/moment/moment.js');?>"></script>

<?php if ($text_align == 'right-to-left') : ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/neon-rtl.css');?>">
<?php endif; ?>

<style> 
    .modal-mask {
        display: table;
        position: fixed;
        padding: 10px 0;
        z-index: 9998;
        top: 0;
        bottom: 0;
        right: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        transition: opacity 0.3s ease;
    }
    .modal-wrapper {
        overflow: auto;
        display: table-cell;
        vertical-align: middle;
        margin: 30px 0;
    }
    .modal-container {
        width: 700px;
        margin: 0px auto;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
        transition: all 0.3s ease;
        font-family: Helvetica, Arial, sans-serif;
    }
    .modal-header h3 {
        margin-top: 0;
        color: #42b983;
    }
    .modal-body {
        margin:0 0 20px 0;
    }
    .modal-default-button {
        float: right;
    }
    .modal-enter {
        opacity: 0;
    }
    .modal-leave-active {
        opacity: 0;
    }

    .modal-enter .modal-container,
    .modal-leave-active .modal-container {
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }
    .spinner-border {
        display: inline-block;
        width: 2rem;
        height: 2rem;
        vertical-align: text-bottom;
        border: .25em solid currentColor;
        border-right-color: #0000;
        border-radius: 50%;
        -webkit-animation: spinner-border .75s linear infinite;
        animation: spinner-border .75s linear infinite;
        color: #000;
    }
    @keyframes spinner-border {
        from {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
    .loading {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 30px;
    }
</style>

<script src="<?= base_url('assets/js/jquery-1.11.0.min.js');?>"></script>
        <!--[if lt IE 9]><script src="<?= base_url('assets/js/ie8-responsive-file-warning.js');?>"></script><![endif]-->
<script src="<?= base_url()?>assets/izitoast/dist/js/iziToast.min.js"></script>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<link rel="shortcut icon" href="<?= base_url('uploads/favicon.png');?>">
<link rel="stylesheet" href="<?= base_url('assets/css/font-icons/fontawesome/css/all.min.css');?>">
<link rel="stylesheet" href="<?= base_url('assets/js/vertical-timeline/css/component.css');?>">
<link rel="stylesheet" href="<?= base_url('assets/js/datatables/responsive/css/datatables.responsive.css');?>">

<!--<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.2.25/angular.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.25/angular-route.js"></script>
<script src="script.js"></script>-->

<!-- vue modal -->
<script type="text/x-template" id="modal-template">
    <transition name="modal">
        <div class="modal-mask" style="">

            <div class="modal-wrapper">

                <div class="modal-container">
                
                    <div class="modal-header">
                        <button type="button" class="close" @click="$emit('close')">&times;</button>
                        <slot name="header">
                            default header
                        </slot>
                    </div>

                    <slot name="body" class="modal-body">
                        default body
                    </slot>
                    <slot name="footer" class="modal-footer">
                    </slot>
                </div>
            </div>
        </div>
    </transition>
</script>

<script>
    function checkDelete()
    {
        var chk=confirm("Are You Sure To Delete This !");
        if(chk)
        {
          return true;  
        }
        else{
            return false;
        }
    }
</script>

<style>
    .tile-stats .icon
    {
        bottom: 30px;
    }
</style>