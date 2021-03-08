<!-- TODO: 1 change file name to manage_hr and change table -->
<button onclick="showAjaxModal('<?php echo site_url('modal/popup/add_hr');?>');" 
    class="btn btn-primary pull-right">
        <i class="fa fa-plus"></i>&nbsp;<?php echo get_phrase('add_hr'); ?>
</button>
<?php $query = $this->db->select("department.name as department_name")->join('department','department.department_id=hr.department_id')->get_where("hr", array( 'email' => 'hamedhamidi@gmail.com'));
echo $query->row()->department_name;
?>
<div style="clear:both;"></div>
<br>
<table class="table table-bordered table-striped datatable" id="table-2">
    <thead>
        <tr>
            <th><?php echo get_phrase('HR ID');?></th>
            <th><?php echo get_phrase('tazkira_id');?></th>
            <th><?php echo get_phrase('name');?></th>
            <th><?php echo get_phrase('email');?></th>
            <th><?php echo get_phrase('phone');?></th>
            <th><?php echo get_phrase('department');?></th>
            <th><?php echo get_phrase('options');?></th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($hr_info as $row) { ?>   
            <tr>
                <td><?php echo $row['hr_id']?></td>
                <td><?php echo $row['tazkira_id']?></td>
                <td><?php echo $row['first_name']. " " . $row['last_name'];?></td>
                <td><?php echo $row['email']?></td>
                <td><?php echo $row['phone']?></td>
                <td>
                    <?php $name = $this->db->get_where('department' , array('department_id' => $row['department_id'] ))->row()->name;
                        echo $name;?>
                </td>
                <td>
                    <a  onclick="showAjaxModal('<?php echo site_url('modal/popup/edit_hr/'.$row['hr_id']);?>');" 
                        class="btn btn-info btn-sm">
                        <i class="fa fa-pencil"></i>&nbsp;<?php echo get_phrase('edit');?>
                    </a>
                    <a onclick="confirm_modal('<?php echo site_url('admin/hr/delete/'.$row['hr_id']); ?>')"
                        class="btn btn-danger btn-sm">
                        <i class="fa fa-trash-o"></i>&nbsp;<?php echo get_phrase('delete');?>
                    </a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script type="text/javascript">
    jQuery(window).load(function ()
    {
        var $ = jQuery;

        $("#table-2").dataTable({
            "sPaginationType": "bootstrap",
            "sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>"
        });

        $(".dataTables_wrapper select").select2({
            minimumResultsForSearch: -1
        });

        // Highlighted rows
        $("#table-2 tbody input[type=checkbox]").each(function (i, el)
        {
            var $this = $(el),
                    $p = $this.closest('tr');

            $(el).on('change', function ()
            {
                var is_checked = $this.is(':checked');

                $p[is_checked ? 'addClass' : 'removeClass']('highlight');
            });
        });

        // Replace Checboxes
        $(".pagination a").click(function (ev)
        {
            replaceCheckboxes();
        });
    });
</script>