 
<button onclick="showAjaxModal('<?php echo site_url('modal/popup/add_staff');?>');" 
    class="btn btn-primary pull-right">
        <i class="fas fa-plus"></i>&nbsp;<?php echo get_phrase('add_staff'); ?>
</button>
<div style="clear:both;"></div>
<br>

<table class="table table-bordered table-striped datatable" id="table-2">
    <thead>
        <tr>
            <th><?php echo get_phrase('ID');?></th>
            <th><?php echo get_phrase('name');?></th>
            <th><?php echo get_phrase('tazkira_id');?></th>
            <th><?php echo get_phrase('phone');?></th>
            <th><?php echo get_phrase('options');?></th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($staff_info as $row) { ?>   
            <tr>
                <td><?php echo $row['staff_id']?></td>
                <td><?php echo $row['name']?></td>
                <td><?php echo $row['tazkira_id']?></td>
                <td><?php echo $row['phone']?></td>
                <td>
                    <a  onclick="showAjaxModal('<?php echo site_url('modal/popup/edit_staff/'.$row['staff_id']);?>');" 
                        class="btn btn-info btn-sm">
                            <i class="fas fa-pencil-alt"></i>&nbsp;
                            <?php echo get_phrase('edit');?>
                    </a>
                    <a onclick="confirm_modal('<?php echo site_url('admin/staff/delete/'.$row['staff_id']); ?>')"
                        class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>&nbsp;
                            <?php echo get_phrase('delete');?>
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