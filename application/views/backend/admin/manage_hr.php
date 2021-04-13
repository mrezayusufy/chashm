<a href="<?= site_url('Admin/add_hr'); ?>">
    <button class="btn btn-primary pull-right">
            <i class="fas fa-plus"></i>&nbsp;<?= get_phrase('add_hr'); ?>
    </button>
</a>

<div style="clear:both;"></div>
<br>
<table class="table table-bordered table-striped datatable" id="table-2">
    <thead>
        <tr>
            <th><?= get_phrase('HR ID');?></th>
            <th><?= get_phrase('tazkira_id');?></th>
            <th><?= get_phrase('name');?></th>
            <th><?= get_phrase('email');?></th>
            <th><?= get_phrase('phone');?></th>
            <th><?= get_phrase('department');?></th>
            <th><?= get_phrase('options');?></th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($hr_info as $row) { ?>   
            <tr>
                <td><?= $row['hr_id']?></td>
                <td><?= $row['tazkira_id']?></td>
                <td><?= $row['first_name']. " " . $row['last_name'];?></td>
                <td><?= $row['email']?></td>
                <td><?= $row['phone']?></td>
                <td><?= $row['name']?></td>
                <td>
                    <a href="<?= site_url('Admin/edit_hr/'.$row['hr_id']);?>" class="btn btn-info btn-sm">
                        <i class="fas fa-pencil-alt"></i>&nbsp;<?= get_phrase('edit');?>
                    </a>
                    <a onclick="confirm_modal('<?= site_url('Admin/hr/delete/'.$row['hr_id']); ?>')"
                        class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>&nbsp;<?= get_phrase('delete');?>
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
            "aaSorting": [
                [0, "desc"]
            ],
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