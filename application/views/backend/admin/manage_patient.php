 
<div>
<button onclick="showAjaxModal('<?=site_url('modal/popup/add_patient');?>');" 
    class="btn btn-primary pull-right">
        <i class="fas fa-plus"></i>&nbsp;<?=get_phrase('add_patient'); ?>
</button>
<div style="clear:both;"></div>
<br>

<table class="table table-bordered table-striped datatable" id="table-2">
    <thead>
        <tr>
            <th><?=get_phrase('ID');?></th>
            <th><?=get_phrase('name');?></th>
            <th><?=get_phrase('father_name');?></th>
            <th><?=get_phrase('phone');?></th>
            <th><?=get_phrase('gender');?></th>
            <th><?=get_phrase('age');?></th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($patient_info as $row) { ?>   
            <tr>
                <td><?=$row['patient_id']?></td>
                <td><?=$row['name']?></td>
                <td><?=$row['father_name']?></td>
                <td><?=$row['phone']?></td>
                <td><?=$row['gender']?></td>
                <td><?=$row['age']?></td>
            </tr>
        <?php } ?>

    </tbody>
</table>
</div>
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