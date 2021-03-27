
<!-- SCRIPTS -->
<a href="#" class="back-to-top btn-back-to-top"></a>

<!-- Core -->
<script src="<?= base_url('assets/frontend/' . $theme . '/vendor/popper/popper.min.js');?>"></script>
<script src="<?= base_url('assets/frontend/' . $theme . '/vendor/bootstrap/js/bootstrap.min.js');?>"></script>
<script src="<?= base_url('assets/frontend/' . $theme . '/js/vendor/jquery.easing.js');?>"></script>
<script src="<?= base_url('assets/frontend/' . $theme . '/js/ie10-viewport-bug-workaround.js');?>"></script>
<script src="<?= base_url('assets/frontend/' . $theme . '/js/slidebar/slidebar.js');?>"></script>
<script src="<?= base_url('assets/frontend/' . $theme . '/js/classie.js');?>"></script>

<!-- Bootstrap Extensions -->
<script src="<?= base_url('assets/frontend/' . $theme . '/vendor/bootstrap-dropdown-hover/js/bootstrap-dropdown-hover.js');?>"></script>

<!-- Plugins -->
<script src="<?= base_url('assets/frontend/' . $theme . '/vendor/flatpickr/flatpickr.min.js');?>"></script>
<script src="<?= base_url('assets/frontend/' . $theme . '/vendor/swiper/js/swiper.min.js');?>"></script>
<script src="<?= base_url('assets/common/izitoast/js/iziToast.min.js');?>"></script>

<!-- App JS -->
<script src="<?= base_url('assets/frontend/' . $theme . '/js/wpx.app.js');?>"></script>

<?php if ($this->session->flashdata('error_message') != '') { ?>
    <script>
        iziToast.error({
            title: '<?= get_phrase('error');?>',
            message: '<?= $this->session->flashdata('error_message');?>',
        });
    </script>
<?php } ?>

<?php if ($this->session->flashdata('success_message') != '') { ?>
    <script>
        iziToast.success({
            title: '<?= get_phrase('success');?>',
            message: '<?= $this->session->flashdata('success_message');?>',
        });
    </script>
<?php } ?>
