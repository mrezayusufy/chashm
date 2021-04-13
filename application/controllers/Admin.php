<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends CI_Controller
{
    
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->model('crud_model');
        $this->load->model('email_model');
        // $this->load->model('frontend_model');
        
        // cache control
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }
    
    public function index()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(site_url('login'), 'refresh');
        if ($this->session->userdata('admin_login') == 1)
            redirect(site_url('Admin/dashboard'), 'refresh'); 
    }
    
    // ADMIN DASHBOARD
    function dashboard() {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        $paid_accountant = $this->crud_model->total_count('paid', array('status' => 'paid'), 'invoice')->paid;
        $pa = $paid_accountant ? $paid_accountant : 0;
        $hrs = $this->crud_model->get_hr();
        $hr = $this->db->get('hr')->first_row();
        $salary_paid = $this->crud_model->total_count('salary', array('status' => 'paid'), 'salary')->salary;
        $sp = $salary_paid ? $salary_paid : 0;
        $sm = $this->db->select('sum(salary) as y, month(from_unixtime(date)) as x')->where('status', 'paid')->group_by('month(from_unixtime(date))')->get('salary')->result_array();
        $ii = $this->db->select('sum(paid) as y, date(from_unixtime(creation_timestamp)) as x')->where('status', 'paid')->group_by('date(from_unixtime(creation_timestamp))')->get('invoice')->result_array();
        $im = $this->db->select('sum(paid) as y, month(from_unixtime(creation_timestamp)) as x')->where('status', 'paid')->group_by('month(from_unixtime(creation_timestamp))')->get('invoice')->result_array();
        $patient = $this->db->select('count(patient_id) as y, date(from_unixtime(created_at)) as x')->group_by('date(from_unixtime(created_at))')->get('patient')->result_array();
        $page_data['pa'] = $pa;
        $page_data['hrs'] = $hrs;
        $page_data['hr'] = $hr;
        $page_data['sp'] = $sp;
        $page_data['sm'] = $sm;
        $page_data['ii'] = $ii;
        $page_data['im'] = $im;
        $page_data['patient'] = $patient;
        $page_data['page_name']  = 'dashboard';
        $page_data['page_title'] = get_phrase('admin_dashboard');
        $this->load->view('backend/index', $page_data);
    }
    
    function balance()
    {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        $page_data['balances'] = $this->crud_model->select_balance();
        $page_data['page_name']  = 'balance';
        $page_data['page_title'] = get_phrase('balance');
        $this->load->view('backend/index', $page_data);
    }
    function transaction()
    {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        $page_data['transactions'] = $this->crud_model->select_transaction();
        $page_data['page_name']  = 'transaction';
        $page_data['page_title'] = get_phrase('manage_transaction');
        $this->load->view('backend/index', $page_data);
    }
    function report(){
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        $page_data['reports'] = $this->crud_model->select_report();
        $page_data['page_name']  = 'report';
        $page_data['page_title'] = get_phrase('manage_report');
        $this->load->view('backend/index', $page_data);
    }
    // SYSTEM SETTINGS
    function system_settings($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        
        if ($param1 == 'do_update') {
            $this->crud_model->update_system_settings();
            $this->session->set_flashdata('message', get_phrase('settings_updated'));
            redirect(site_url('Admin/system_settings'), 'refresh');
        }
        
        $page_data['page_name']  = 'system_settings';
        $page_data['page_title'] = get_phrase('system_settings');
        $page_data['settings']   = $this->db->get('settings')->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    function manage_profile($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        
        if ($param1 == 'update_profile_info') {
            $data['name']  = $this->input->post('name');
            $data['email'] = $this->input->post('email');
            $validation    = email_validation_on_edit($data['email'], $this->session->userdata('login_user_id'), 'admin');
            if ($validation == 1) {
                $returned_array = null_checking($data);
                $this->db->where('admin_id', $this->session->userdata('login_user_id'));
                $this->db->update('admin', $returned_array);
                $this->session->set_flashdata('message', get_phrase('profile_info_updated_successfuly'));
                redirect(site_url('Admin/manage_profile'), 'refresh');
            } else {
                $this->session->set_flashdata('error_message', get_phrase('duplicate_email'));
                redirect(site_url('Admin/manage_profile'), 'refresh');
            }
            
        }
        if ($param1 == 'change_password') {
            $current_password_input = sha1($this->input->post('password'));
            $new_password           = sha1($this->input->post('new_password'));
            $confirm_new_password   = sha1($this->input->post('confirm_new_password'));
            
            $current_password_db = $this->db->get_where('admin', array(
                'admin_id' => $this->session->userdata('login_user_id')
            ))->row()->password;
            
            if ($current_password_db == $current_password_input && $new_password == $confirm_new_password) {
                $this->db->where('admin_id', $this->session->userdata('login_user_id'));
                $this->db->update('admin', array(
                    'password' => $new_password
                ));
                
                $this->session->set_flashdata('message', get_phrase('password_info_updated_successfuly'));
                redirect(site_url('Admin/manage_profile'), 'refresh');
            } else {
                $this->session->set_flashdata('message', get_phrase('password_update_failed'));
                redirect(site_url('Admin/manage_profile'), 'refresh');
            }
        }
        $page_data['page_name']  = 'manage_profile';
        $page_data['page_title'] = get_phrase('manage_profile');
        $page_data['edit_data']  = $this->db->get_where('admin', array(
            'admin_id' => $this->session->userdata('login_user_id')
        ))->result_array();
        $this->load->view('backend/index', $page_data);
    }
    // department
    function add_department($task = "")
    {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        $data['page_name']       = 'add_department';
        $data['page_title']      = get_phrase('add_department');
        $this->load->view('backend/index', $data);
    }
    function edit_department($department_id = "")
    {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        $data['department_id']   = $department_id;
        $data['page_name']       = 'edit_department';
        $data['page_title']      = get_phrase('edit_department');
        $this->load->view('backend/index', $data);
    }
    function department($task = "", $department_id = "")
    {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        
        if ($task == "create") {
            $this->crud_model->save_department_info();
            $this->session->set_flashdata('message', get_phrase('department_info_saved_successfuly'));
            redirect(site_url('Admin/department'), 'refresh');
        }
        
        if ($task == "update") {
            $this->crud_model->update_department_info($department_id);
            $this->session->set_flashdata('message', get_phrase('department_info_updated_successfuly'));
            redirect(site_url('Admin/department'), 'refresh');
        }
        
        if ($task == "delete") {
            $this->crud_model->delete_department_info($department_id);
            redirect(site_url('Admin/department'), 'refresh');
        }
        
        $data['department_info'] = $this->crud_model->select_department_info();
        $data['page_name']       = 'manage_department';
        $data['page_title']      = get_phrase('department');
        $this->load->view('backend/index', $data);
    }
    
    function department_facilities($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        
        if ($param1 == 'add') {
            $this->frontend_model->add_department_facility($param2);
            $this->session->set_flashdata('message', get_phrase('facility_saved_successfully'));
            redirect(site_url('Admin/department_facilities/' . $param2), 'refresh');
        }
        
        if ($param1 == 'edit') {
            $this->frontend_model->edit_department_facility($param2);
            $this->session->set_flashdata('message', get_phrase('facility_updated_successfully'));
            redirect(site_url('Admin/department_facilities/' . $param3), 'refresh');
        }
        
        if ($param1 == 'delete') {
            $this->frontend_model->delete_department_facility($param2);
            $this->session->set_flashdata('message', get_phrase('facility_deleted_successfully'));
            redirect(site_url('Admin/department_facilities/' . $param3), 'refresh');
        }
        
        $data['department_info'] = $this->frontend_model->get_department_info($param1);
        $data['facilities']      = $this->frontend_model->get_department_facilities($param1);
        $data['page_name']       = 'department_facilities';
        $data['page_title']      = get_phrase('department_facilities') . ' | ' . $data['department_info']->name . ' ' . get_phrase('department');
        $this->load->view('backend/index', $data);
    }
    // invoice
    function show_invoice($invoice_id = "")
    {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        $data['invoice_id']   = $invoice_id;
        $data['page_name']   = 'show_invoice';
        $data['page_title']  = get_phrase('show_invoice');
        $this->load->view('backend/index', $data);
    }
    function invoice(){
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }

        $data['invoice_info'] = $this->crud_model->select_invoice();
        $data['page_name']   = 'manage_invoice';
        $data['page_title']  = get_phrase('invoice');
        $this->load->view('backend/index', $data);
    }
    // salary
    function salary($task = "", $salary_id = ""){
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        if ($task == "create") {
            $this->crud_model->save_salary_info();
            $this->session->set_flashdata('message', get_phrase('salary_info_saved_successfuly'));
            redirect(site_url('Admin/salary'), 'refresh');
        }
        if ($task == "update") {
            $this->crud_model->update_salary_info($salary_id);
            redirect(site_url('Admin/salary'), 'refresh');
        }
        if ($task == "delete") {
            $this->crud_model->delete_salary_info($salary_id);
            redirect(site_url('Admin/salary'), 'refresh');
        }
        $data['salary_info'] = $this->crud_model->select_salary_info();
        $data['page_name']   = 'manage_salary';
        $data['page_title']  = get_phrase('salary');
        $this->load->view('backend/index', $data);

    }
    // staff 
    function add_staff()
    {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        $data['page_name']   = 'add_staff';
        $data['page_title']  = get_phrase('add_staff');
        $this->load->view('backend/index', $data);
    }
    function edit_staff($staff_id = "")
    {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        $data['staff_id']   = $staff_id;
        $data['page_name']   = 'edit_staff';
        $data['page_title']  = get_phrase('edit_staff');
        $this->load->view('backend/index', $data);
    }
    function staff($task = "", $staff_id = "")
    {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        if ($task == "create") {
            $this->crud_model->save_staff_info();
            $this->session->set_flashdata('message', get_phrase('staff_info_saved_successfuly'));
            redirect(site_url('Admin/staff'), 'refresh');
        }
        if ($task == "update") {
            $this->crud_model->update_staff_info($staff_id);
            redirect(site_url('Admin/staff'), 'refresh');
        }
        
        if ($task == "delete") {
            $this->crud_model->delete_staff_info($staff_id);
            redirect(site_url('Admin/staff'), 'refresh');
        }
        $data['staff_info'] = $this->crud_model->select_staff_info();
        $data['page_name']   = 'manage_staff';
        $data['page_title']  = get_phrase('staff');
        $this->load->view('backend/index', $data);
    }
    // hr
    function add_hr()
    {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        $department_info = $this->crud_model->select_department_info();
        $data['department_info'] = $department_info;
        $data['page_name']   = 'add_hr';
        $data['page_title']  = get_phrase('add_hr');
        $this->load->view('backend/index', $data);
    }
    function edit_hr($hr_id = "")
    {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        $department_info = $this->db->get('department')->result_array();
        $single_hr_info = $this->db->get_where('hr', array('hr_id' => $hr_id))->result_array();
        $data['department_info'] = $department_info;
        $data['single_hr_info'] = $single_hr_info;
        $data['page_name']   = 'edit_hr';
        $data['page_title']  = get_phrase('edit_hr');
        $this->load->view('backend/index', $data);
    }
    function hr($task = "", $hr_id = "")
    {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        if ($task == "create") {
            $this->crud_model->save_hr_info();
            $this->session->set_flashdata('message', get_phrase('hr_info_saved_successfuly'));
            redirect(site_url('Admin/hr'), 'refresh');
        }
        if ($task == "update") {
            $this->crud_model->update_hr_info($hr_id);
            redirect(site_url('Admin/hr'), 'refresh');
        }
        
        if ($task == "delete") {
            $data = array();
            $data['title'] = $hr_id." The Doctor info is deleted";
            $this->crud_model->create_log($data);
            $this->crud_model->delete_hr_info($hr_id);
            redirect(site_url('Admin/hr'), 'refresh');
        }
        $data['hr_info'] = $this->crud_model->get_hr();
        $data['page_name']   = 'manage_hr';
        $data['page_title']  = get_phrase('hr');
        $this->load->view('backend/index', $data);
    }
    // patient 
    function patient_api() {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        $query = $this->crud_model->select_patient_info();

        $data['patients'] = $query;
        
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    function add_patient()
    {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        $data['page_name']   = 'add_patient';
        $data['page_title']  = get_phrase('add_patient');
        $this->load->view('backend/index', $data);
    }
    function patient($task = "", $patient_id = "")
    {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        
        if ($task == "create") {
            $this->crud_model->save_patient_info();
            $this->session->set_flashdata('message', get_phrase('patient_info_saved_successfuly'));
            redirect(site_url('Admin/patient'), 'refresh');
        }
        
        if ($task == "update") {
            $this->crud_model->update_patient_info($patient_id);
            redirect(site_url('Admin/patient'), 'refresh');
        }
        
        if ($task == "delete") {
            $this->crud_model->delete_patient_info($patient_id);
            redirect(site_url('Admin/patient'), 'refresh');
        }
        
        $data['patient_info'] = $this->crud_model->select_patient_info();
        $data['page_name']    = 'manage_patient';
        $data['page_title']   = get_phrase('patient');
        $this->load->view('backend/index', $data);
    }
    
    function medicine()
    {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        
        $data['medicine_info'] = $this->crud_model->select_medicine_info();
        $data['page_name']     = 'show_medicine';
        $data['page_title']    = get_phrase('medicine');
        $this->load->view('backend/index', $data);
    }
} 