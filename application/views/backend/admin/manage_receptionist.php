<div id="app">
<button onclick="showAjaxModal('<?php echo site_url('modal/popup/add_receptionist');?>');" 
    class="btn btn-primary pull-right">
        <i class="fa fa-plus"></i>&nbsp;<?php echo get_phrase('add_receptionist'); ?>
</button>
  <div v-for="receptionist in receptionists">
    <p>{{ receptionist.name }}</p>
  </div>

<div style="clear:both;"></div>
<br>
<table class="table table-bordered table-striped datatable" id="table-2">
    <thead>
        <tr>
            <th><?php echo get_phrase('ID');?></th>
            <th><?php echo get_phrase('name');?></th>
            <th><?php echo get_phrase('email');?></th>
            <th><?php echo get_phrase('address');?></th>
            <th><?php echo get_phrase('phone');?></th>
            <th><?php echo get_phrase('options');?></th>
        </tr>
    </thead>

    <tbody>
            <?php foreach ($receptionist_info as $row) { ?>   
            <tr>
                <td><?php echo $row['receptionist_id']?></td>
                <td><?php echo $row['name']?></td>
                <td><?php echo $row['email']?></td>
                <td><?php echo $row['address']?></td>
                <td><?php echo $row['phone']?></td>
                <td>
                    <a  onclick="showAjaxModal('<?php echo site_url('modal/popup/edit_receptionist/'.$row['receptionist_id']);?>');" 
                        class="btn btn-info btn-sm">
                            <i class="fa fa-pencil"></i>&nbsp;
                            <?php echo get_phrase('edit');?>
                    </a>
                    <a onclick="confirm_modal('<?php echo site_url('admin/receptionist/delete/'.$row['receptionist_id']); ?>')"
                        class="btn btn-danger btn-sm">
                            <i class="fa fa-trash-o"></i>&nbsp;
                        <?php echo get_phrase('delete');?>
                    </a>
                </td>
            </tr>
        <?php } ?>
        
    </tbody>
</table>
</div>
<script type="text/javascript">
var app = new Vue({
    el: '#app',
    data: {
        url: 'http://chashm.test',
        receptionists: [],
        newReceptionist: {
            image: '',
            name: '',
            email: '',
            phone: '',
            address: '',
        },
        successMSG: '',
        currentPage: 0,
        rowCountPage: 5,
        totalReceptionists: 0,
        pageRange: 2,
        emptyResult: false,
    },
    created(){
        this.showAll();
    },
    methods: {
        showAll() { axios.get(this.url+"/admin/receptionist/receptionist_info").then(function(response){
            if(response.data.receptionists == null) {
                app.noResult();
            } else {
                console.log('response', response);
                app.getData(response.data.receptionists);
            }
        })},
        noResult(){
            app.emptyResult = true;
            app.receptionists = null;
            app.totalReceptionists = 0;
        },
        getData(receptionists){
            app.emptyResult = false;
            app.totalReceptionists = receptionists.length;
            app.receptionists = receptionists.slice(app.currentPage * app.rowCountPage, (app.rowCountPage) + app.rowCountPage);
        },
        addUser(){
            var formData = app.formData(app.newReceptionist);
            axios.post(this.url+"/admin/receptionist/create", formData).then(function(response){
                if(response.data.error) {
                    app.formValidate = response.data.error_message;
                } else {
                    app.success
                }
            })
        },
        formData(obj){
            var formData = new FormData();
            for(var key in obj) {
                formData.append(key, obj[key])
            }
            return formData;
        }
    },
    // end
});
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