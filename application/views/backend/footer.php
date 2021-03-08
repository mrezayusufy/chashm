<?php $version = $this->db->get_where('settings', array('type' => 'version'))->row()->description;?>
<!-- Footer -->
<footer class="main">
	&copy; 2021 <strong>Afghan Hospital Management System</strong>
    <strong class="pull-right"> VERSION <?php echo $version;?></strong>
    Developed by
	<a href="http://hoshzareen.com"
    	target="_blank">Hoshzareen</a>
</footer>
