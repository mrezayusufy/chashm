<!-- FIXME: date and time -->

<div style="clear:both;"></div>
<br>

<table class="table table-bordered table-striped datatable" id="table-2">
    <thead>
        <tr>
            <th><?= get_phrase('ID');?></th>
            <th><?= get_phrase('title');?></th>
            <th><?= get_phrase('patient');?></th>
            <th><?= get_phrase('Doctor');?></th>
            <th><?= get_phrase('paid');?></th>
            <th><?= get_phrase('total');?></th>
            <th><?= get_phrase('remain');?></th>
            <th><?= get_phrase('status');?></th>
            <th><?= get_phrase('options');?></th>
        </tr>
    </thead>

    <tbody>
    <?php 
    
    foreach ($invoice_info as $row) { 
    ?>
    
            <tr>
                <td><?= $row['invoice_id']?></td>
                <td><?= $row['title']?></td>
                <td><?= $row['patient_name']?></td>
                <td><?= $row['hr_name']?></td>
                <td><?= $row['paid'] ?></td>
                <td><?= $row['total']?></td>
                <td><?= $row['total'] - $row['paid']?></td>
                <td><div class="btn <?= $row['status'] == 'paid'? 'btn-primary' : 'btn-danger'?> btn-sm"><?= $row['status']?></div></td>
                <td>
                    <a onclick="showAjaxModal('<?= site_url('modal/popup/show_invoice/'.$row['invoice_id']);?>');" 
                        class="btn btn-info btn-sm">
                            <i class="fas fa-pencil-alt"></i>&nbsp;
                            <?= get_phrase('show');?>
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
            "aaSorting": [[ 0, "desc" ]],
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