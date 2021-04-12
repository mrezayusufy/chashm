<!-- FIXME: date and time -->
<button onclick="showAjaxModal('<?= site_url('modal/popup/add_salary');?>');" 
    class="btn btn-primary pull-right">
        <i class="fas fa-plus"></i>&nbsp;<?= get_phrase('add_salary'); ?>
</button>
<div style="clear:both;"></div>
<br>

<table class="table table-bordered table-striped datatable" id="table-2">
    <thead>
        <tr>
            <th><?= get_phrase('ID');?></th>
            <th><?= get_phrase('tazkira_id');?></th>
            <th><?= get_phrase('name');?></th>
            <th><?= get_phrase('department');?></th>
            <th><?= get_phrase('salary');?></th>
            <th><?= get_phrase('date');?></th>
            <th><?= get_phrase('status');?></th>
            <th><?= get_phrase('options');?></th>
        </tr>
    </thead>

    <tbody>
    <?php 
    
    foreach ($salary_info as $row) { 
        $department = $this->db->get_where("department", array("department_id" => $row["department_id"]))->row();
    ?>
    
            <tr>
                <td><?= $row['salary_id']?></td>
                <td><?= $row['tazkira_id']?></td>
                <td><?= $row['name']?></td>
                <td><?= $department->name ?></td>
                <td><?= $row['salary']?></td>
                <td><?= date('d M,Y', $row['date']);?></td>
                <td><div class="btn <?= $row['status'] == 'paid'? 'btn-primary' : 'btn-danger'?> btn-sm"><?= $row['status']?></div></td>
                <td>
                    <a onclick="showAjaxModal('<?= site_url('modal/popup/edit_salary/'.$row['salary_id']);?>');" 
                        class="btn btn-info btn-sm">
                            <i class="fas fa-pencil-alt"></i>&nbsp;
                            <?= get_phrase('edit');?>
                    </a>
                    <a onclick="confirm_modal('<?= site_url('Admin/salary/delete/'.$row['salary_id']); ?>')"
                        class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>&nbsp;
                            <?= get_phrase('delete');?>
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