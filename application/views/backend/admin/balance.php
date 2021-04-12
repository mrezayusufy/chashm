 
<table class="table table-bordered table-striped datatable" id="table-2">
    <thead>
        <tr>
            <th><?= get_phrase('ID');?></th>
            <th><?= get_phrase('owner');?></th>
            <th><?= get_phrase('amount');?></th>
            <th><?= get_phrase('timestamp');?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($balances as $row) { ?>   
            <tr>
                <td><?= $row->balance_id?></td>
                <td>Admin</td>
                <td><?= $row->amount?></td>
                <td><?= date("d M,Y", $row->timestamp)?></td>
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