<a href="<?= site_url('Admin/add_department');?>" >
    <button class="btn btn-primary pull-right">
        <i class="fas fa-plus"></i>&nbsp;<?= get_phrase('add_department'); ?>
    </button>
</a>
<div style="clear:both;"></div>
<br>
<table class="table table-bordered table-striped datatable" id="table-2">
    <thead>
        <tr>
            <th><?= get_phrase('icon');?></th>
            <th><?= get_phrase('name'); ?></th>
            <th width="50%"><?= get_phrase('description'); ?></th>
            <th><?= get_phrase('options'); ?></th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($department_info as $row) { ?>   
            <tr>
                <td>
                    <img src="<?= base_url();?>uploads/frontend/department_images/<?= $row['department_id'];?>.png"
                        width="40">
                </td>
                <td><?= $row['name']; ?></td>
                <td><?= substr($row['description'], 0, 200); ?> ...</td>
                <td>
                    <a  href="<?= site_url('Admin/edit_department/'.$row['department_id']); ?>" 
                        class="btn btn-info btn-sm">
                        <i class="fas fa-pencil-alt"></i>&nbsp;<?= get_phrase('edit');?>
                    </a>
                    <a onclick="confirm_modal('<?= site_url('Admin/department/delete/'.$row['department_id']); ?>')"
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