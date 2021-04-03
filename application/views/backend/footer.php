<?php 
$system_name = $this->db->get_where('settings', array('type' => 'system_title'))->row()->description;

$version = $this->db->get_where('settings', array('type' => 'version'))->row()->description;?>
<!-- Footer -->
<footer class="main">

	&copy; 2021 <strong><?= $system_name?></strong>
    <strong class="pull-right"> VERSION <?php echo $version;?></strong>
    Developed by
	<a href="http://hoshzareen.com"
    	target="_blank">Hosh Zareen</a>
</footer>
