
<?php
$hrs = $this->crud_model->select_hr_by_tazkira_id();
$hr_list = json_encode($hrs);
?>
<div id="app">
  <button @click="addSalary=true;" class="btn btn-primary pull-right">
    <i class="fas fa-plus"></i>&nbsp;<?= get_phrase('add_salary'); ?>
  </button>
  <div style="clear:both;"></div>
  <br>
  <div v-if="loading" class="loading">
    <div class="spinner-border"></div>
  </div>
  <table class="table table-bordered table-striped datatable" id="table-2">
    <thead>
      <tr>
        <th><?= get_phrase('salary_id'); ?></th>
        <th><?= get_phrase('tazkira_id'); ?></th>
        <th><?= get_phrase('name'); ?></th>
        <th><?= get_phrase('department'); ?></th>
        <th><?= get_phrase('date'); ?></th>
        <th><?= get_phrase('salary'); ?></th>
        <th><?= get_phrase('status'); ?></th>
        <th><?= get_phrase('options'); ?></th>
      </tr>
    </thead>

    <tbody>
      <tr v-for="i in salaries">
        <td>{{ i.salary_id }} </td>
        <td>{{ i.tazkira_id }}</td>
        <td>{{ i.name }}</td>
        <td>{{ i.department_name }}</td>
        <td>{{ i.date | formatDate }}</td>
        <td>{{ i.salary }}</td>
        <td>
          <div class="status btn btn-sm" :class="[i.status == 'paid' ? 'btn-primary' : 'btn-danger']">{{ i.status }}</div>
        </td>
        <td>
          <?php if ($this->session->userdata('department') == "Accountant") : ?>
            <a @click="editSalary=true;selectSalary(i);" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i></a>
          <?php endif; ?>
          <a @click="selectSalary(i); viewSalary=true" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
          <a @click="selectSalary(i); deleteSalary=true" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
        </td>
      </tr>

    </tbody>
  </table>
  <!-- add modal -->
  <add-modal v-if="addSalary" @close="clearAll">
    <div slot="header">
      <h4 class="modal-title"><?= get_phrase('add_salary') ?></h4>
    </div>
    <div slot="body" class="modal-body" :style="modalStyle">
      <?php include 'create_salary.php'; ?>
    </div> 
    <div slot="footer" class="modal-footer">
      <div class="col-sm-3 control-label col-sm-offset-2">
        <button @click="createSalary" class="btn btn-success">
          <?= get_phrase('create'); ?>
        </button>
      </div>
    </div> 
  </add-modal>
  <!-- view modal salary -->
  <view-modal v-if="viewSalary" @close="clearAll">
    <div slot="header">
      <h4 class="modal-title"><?= get_phrase('salary') ?> : {{chooseSalary.salary_id}}</h4>
    </div>
    <div slot="body" class="modal-body" :style="modalStyle">
      <?php include 'view_salary.php'; ?>
    </div>
    <div slot="footer">
      <div slot="footer" class="modal-footer">
        <div class="col-sm-3 control-label col-sm-offset-2">
          <button @click="clearAll" class="btn btn-info">
            <i class="fas fa-times"></i> <?= get_phrase('close'); ?>
          </button>
        </div>
      </div>
    </div>
  </view-modal>
  <!-- edit salary modal -->
  <edit-modal v-if="editSalary" @close="clearAll">
    <div slot="header">
      <h4 class="modal-title"><?= get_phrase('edit_salary') ?> : {{ chooseSalary.salary_id }}</h4>
    </div>
    <div slot="body" class="modal-body" :style="modalStyle">
      <?php include 'update_salary.php'; ?>
    </div>
    <div slot="footer">
      <!-- submit -->
      <div class="modal-footer">
        <div class="col-sm-offset-3 col-sm-8">
          <button class="btn btn-primary" @click="updateSalary">
            <?= get_phrase('update_salary'); ?></button>
        </div>
      </div>
    </div>
  </edit-modal>
  <!-- delete salary -->
  <delete-modal v-if="deleteSalary" @close="clearAll">
    <div slot="header">
      <h4 class="modal-title" style="text-align:center;"><?= get_phrase('Are_you_sure_to_delete_this_information?') ?></h4>
    </div>
    <div slot="body" class="modal-body" :style="{textAlign: 'center'}">
      <h4 class="modal-title"><?= get_phrase('delete_salary') ?> : {{ chooseSalary.salary_id }}</h4>
    </div>
    <div slot="footer">
      <!-- submit -->
      <div class="modal-footer">
        <div class="col-sm-offset-3 col-sm-8">
          <button class="btn btn-danger" @click="removeSalary">
            <?= get_phrase('delete_salary'); ?></button>
        </div>
      </div>
    </div>
  </delete-modal>

</div>

<script type="text/javascript">
  Vue.component("view-modal", {
    template: "#modal-template"
  });
  Vue.component("add-modal", {
    template: "#modal-template"
  });
  Vue.component("edit-modal", {
    template: "#modal-template"
  });
  Vue.component("delete-modal", {
    template: "#modal-template"
  });
  Vue.component("patient-select", VueSelect.VueSelect);
  Vue.component("hr-select", VueSelect.VueSelect);
  var app = new Vue({
    el: "#app",
    data: {
      modalStyle: {
        height: screen.height - 300 + 'px',
        overflow: 'auto'
      },
      loading: true,
      viewSalary: false,
      addSalary: false,
      editSalary: false,
      deleteSalary: false,
      salaries: [],
      hrs: <?= $hr_list ?>,
      newSalary: {
        tazkira_id : "",
        salary : "",
        hr_salary: "",
        date: "",
        status: "",
      },
      api: '<?= site_url('HR/salary/'); ?>',
      chooseSalary: {},
      formValidate: [],
    },
    created() {
      this.showAll();
    },
    mounted() {
      this.showAll();
    },
    updated() {
      this.showAll();
    },
    methods: {
      showAll() {
        axios
          .get(this.api + 'list')
          .then(function(response) {
            app.salaries = response.data.salaries;
            app.loading = false;
          })
          .catch(function(error) {
            console.log('error', error);
          });
      },
      setSelected(tazkira_id) {
        var index = app.hrs.findIndex(i => i.tazkira_id == tazkira_id);
        app.newSalary.hr_salary = app.hrs[index].salary;
        app.newSalary.salary = app.hrs[index].salary;
      },
      updateSalary() {
        var i = app.chooseSalary;
        var formData = app.formData(i);
        axios
          .post(this.api + "edit/" + i.salary_id, formData)
          .then(function(response) {
            if (response.data.error) {
              app.formValidate = response.data.msg;
            } else {
              iziToast.success({
                title: 'Successs',
                message: 'Salary updated successfully.',
                position: 'topRight'
              });
              app.clearAll();
            }
          });
      },
      createSalary() {
        app.loading = true;
        var i = app.newSalary;
        var formData = app.formData(i);
        axios
          .post(this.api + "add", formData)
          .then(function(response) {
            if (response.data.error) {
              app.formValidate = response.data.msg;
            } else {
              iziToast.success({
                title: 'Successs',
                message: 'Salary created successfully.',
                position: 'topRight'
              });
              app.clearAll();
            }
          }).catch(function(error){ alert(error)});
      },
      removeSalary() {
        axios
          .delete(this.api + "delete/" + app.chooseSalary.salary_id)
          .then(function(response) {
            iziToast.success({
              title: 'Delete',
              message: ': ' + response.data.msg,
              position: 'topRight'
            });
            app.clearAll();
          })
          .catch(function() {
            iziToast.error({
              title: 'Not Deleted',
              message: ': ' + response.data.msg,
              position: 'topRight'
            });
            app.clearAll();
          });
      },
      selectSalary(salary) {
        axios
          .get(this.api + "get/" + salary.salary_id)
          .then(function(response) {
            try {
              var i = response.data.salary;
              app.chooseSalary = i;
            } catch (error) {
              console.log('error', error);
            }
          })
          .catch(function(error) {
            console.log(error)
          });
      },
      formData(obj) {
        var formData = new FormData();
        for (var key in obj) {
          formData.append(key, obj[key]);
        }
        return formData;
      },
      clearAll() {
        app.newSalary = {}; 
        app.chooseSalary = {};
        app.loading = false;
        app.formValidate = false;
        app.editSalary = false;
        app.addSalary = false;
        app.deleteSalary = false;
        app.viewSalary = false;
        app.refresh();
        $(".page-body").removeClass("modal-open");
      },
      changeStatus(status) {
        return (app.chooseSalary.status = status); //update status
      },
      pickStatus(status) {
        return (app.newSalary.status = status); //update status
      },
      refresh() {
        app.showAll();
      }

    },
    filters: {
      formatDate(date) {
        return moment.unix(date).format('L');
      }, 
    }
  });
  jQuery(window).load(function() {

    var $ = jQuery;
    $(".select2").select2({
      placeholder: 'This is my placeholder',
      allowClear: true
    });
    $('.modal-body').css("height", screen.height - 250);
    $('.modal-body').css("overflow", "auto");
    $("#table-2").dataTable({
      "sPaginationType": "bootstrap",
      "aaSorting": [
        [4, "desc"]
      ],
      "aoColumnDefs": [{
        "bSearchable": false,
        "aTargets": [3, 4, 5, 6]
      }],
      "sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>"
    });

    $(".dataTables_wrapper select").select2({
      minimumResultsForSearch: -1
    });

    // Highlighted rows
    $("#table-2 tbody input[type=checkbox]").each(function(i, el) {
      var $this = $(el),
        $p = $this.closest('tr');

      $(el).on('change', function() {
        var is_checked = $this.is(':checked');

        $p[is_checked ? 'addClass' : 'removeClass']('highlight');
      });
    });

    // Replace Checboxes
    $(".pagination a").click(function(ev) {
      replaceCheckboxes();
    });
    $('[class^="col-sm-"]*').css({
      "padding-left": "3px",
      "padding-right": "3px"
    });
  });
</script>