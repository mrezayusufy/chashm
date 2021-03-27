 
<div id="app">
<button onclick="showAjaxModal('<?php echo site_url('modal/popup/add_patient');?>');" 
    class="btn btn-primary pull-right">
        <i class="fas fa-plus"></i>&nbsp;<?php echo get_phrase('add_patient'); ?>
</button>
<div style="clear:both;"></div>
<br>

<table class="table table-bordered table-striped datatable" id="table-2">
    <thead>
        <tr>
            <th><?php echo get_phrase('ID');?></th>
            <th><?php echo get_phrase('name');?></th>
            <th><?php echo get_phrase('father_name');?></th>
            <th><?php echo get_phrase('phone');?></th>
            <th><?php echo get_phrase('gender');?></th>
            <th><?php echo get_phrase('age');?></th>
            <th><?php echo get_phrase('options');?></th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($patient_info as $row) { ?>   
            <tr>
                <td><?php echo $row['patient_id']?></td>
                <td><?php echo $row['name']?></td>
                <td><?php echo $row['father_name']?></td>
                <td><?php echo $row['phone']?></td>
                <td><?php echo $row['gender']?></td>
                <td><?php echo $row['age']?></td>
                <td>
                    <a  onclick="showAjaxModal('<?php echo site_url('modal/popup/edit_patient/'.$row['patient_id']);?>');" 
                        class="btn btn-info btn-sm">
                            <i class="fas fa-pencil-alt"></i>&nbsp;
                            <?php echo get_phrase('edit');?>
                    </a>
                    <a onclick="confirm_modal('<?php echo site_url('admin/patient/delete/'.$row['patient_id']); ?>')"
                        class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>&nbsp;
                            <?php echo get_phrase('delete');?>
                    </a>
                </td>
            </tr>
        <?php } ?>

    </tbody>
</table>
</div>
<script type="text/javascript">

var v = new Vue({
    el: "#app",
    data: {
        url: "cheshm.test",
        patients: [],
        url:  '<?= site_url('admin/patient_api'); ?>',
        edit_url: '<?= site_url('modal/popup/edit_patient/'); ?>',
        view_url: '<?= site_url('modal/popup/view_patient/'); ?>',
        delete_url: '<?= site_url('admin/patient/delete/'); ?>',
        addModal: true,
        editModal: false,
        deleteModal: false,
        search: { text: ''},
        emptyResult: false,
        newPatient:{
            name: '',
            phone: '',
            address: '',
            gender: '',
            age: '',
            blood_group: '',
        },
        choosePatient: {},
        formValidate: [],
        successMSG: 'reza',
        currentPage: 0,
        rowCountPage: 10,
        totalPatients: 0,
        pageRange: 2,
    }, 
    created() {
        this.showAll();
    },
    methods: {
        showAll(){
            $.ajax({
               url: this.url,
               success: function(response) {
                   app.setData(response.patients);
               }
           });
        },
        searchPatient(){},
        addPatient(){},
        updatePatient(){},
        deletePatient(){},
        formData(obj){},
        setData(data) {
            app.patients = data;
            app.loading = false;
        },
        getData(patients){
            v.emptyResult = false;
            v.totalPatients = patients.length;
            v.patients = patients.slice(v.currentPage * v.rowCountPage, (v.currentPage * v.rowCountPage ) + v.rowCountPage);

            if(v.patients.length == 0 && v.currentPage > 0) {
                v.pageUpdate(v.currentPage - 1);
                v.clearAll();
            }

        },
        selectPatient(patient){
            v.choosePatient = patient;
        },
        clearMsg(){
            setTimeout(function(){
                v.successMSG = '';
            }, 3000);
        },
        clearAll(){
            v.newPatient = {
                name: '',
                phone: '',
                address: '',
                gender: '',
                birthday: '',
                age: '',
                blood_group: '',
            },
            v.formValidate = false;
            v.addModal = false;
            v.editModal = false;
            v.deleteModal = false;
            v.refresh();
        },
        noResult(){
            v.emptyResult = true;
            v.patients = null;
            v.totalPatients = 0;
        },
        pickGender(gender){},
        changeGender(gender){},
        pageUpdate(pageNumber){},
        refresh(){
            v.search.text ? v.searchPatient() : v.showAll();
        }
    }
});
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