<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;

class HR extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->model('crud_model');
        $this->load->model('frontend_model');
    }
    
    function index()
    {
        if ($this->session->userdata('hr_login') != 1 ) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        
        $data['page_name']  = 'dashboard';
        $data['page_title'] = $this->session->userdata('department')." ".get_phrase('dashboard');
        $this->load->view('backend/index', $data);
    }
    // TODO: patient crud operation
    function get($hr_id = ""){
        if ($this->session->userdata('hr_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        if(!$hr_id) 
            $data['hrs'] = $this->crud_model->select_hr_info();
        else 
            $data['hr'] = $this->crud_model->select_hr_info_by_id($hr_id);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    function salary($task = "", $salary_id = ""){
        if ($this->session->userdata('hr_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        header('Content-Type: application/json');
        $department = $this->session->userdata('department');
        if($department == "Accountant") {
            switch($task){
                case "add":
                    $config = array(
                        array('field' => 'salary', 'label' => 'salary', 'rules' => 'trim|required'),
                        array('field' => 'status', 'label' => 'status', 'rules' => 'trim|required'),
                        array('field' => 'date', 'label' => 'date', 'rules' => 'trim'),
                        array('field' => 'tazkira_id', 'label' => 'tazkira_id', 'rules' => 'trim|required'),
                    );
                    $this->form_validation->set_rules($config);
                    if($this->form_validation->run() == false) {
                        $result['error'] = true;
                        $result['msg'] = array(
                            'salary' => form_error('salary'),
                            'status' => form_error('status'),
                            'date' => form_error('date'),
                            'tazkira_id' => form_error('tazkira_id'),
                        );
                    } else {
                        $newSalary = $this->crud_model->save_salary_info();
                        $result['error'] = false;
                        $result['msg'] = 'The salary info is not submitted.';
                        $result['salary'] = $newSalary;
                    }
                    echo $result;
                    echo json_encode($result);
                    break;
                case "edit":
                    $config = array(
                        array('field' => 'salary', 'label' => 'salary', 'rules' => 'trim|required'),
                        array('field' => 'status', 'label' => 'status', 'rules' => 'trim|required'),
                        array('field' => 'tazkira_id', 'label' => 'tazkira_id', 'rules' => 'trim|required'),
                    );
                    $this->form_validation->set_rules($config);
                    if($this->form_validation->run() == false) {
                        $result['error'] = true;
                        $result['msg'] = array(
                            'salary' => form_error('salary'),
                            'status' => form_error('status'),
                            'tazkira_id' => form_error('tazkira_id'),
                        );
                    } else {
                        $this->crud_model->update_salary_info($salary_id);
                        $result['error'] = false;
                        $result['msg'] = 'The salary info was updated successfully.';
                    }
                    echo json_encode($result);
                    break;
                case "list":
                    $data['salaries'] = $this->crud_model->select_salary();
                    $data['msg'] = "salary list";
                    echo json_encode($data);
                    break;
                case "get": 
                    $data['salary'] = $this->crud_model->select_salary_by_id($salary_id);
                    $data['msg'] = "Salary retrived successfully";
                    echo json_encode($data);
                    break;
                case "delete": 
                    $this->crud_model->delete_salary_info($salary_id);
                    $data['msg'] = "salary " . $salary_id . " deleted successfully.";
                    echo json_encode($data);
                    break;
            }
        } else {
            $data['msg'] = "You are not allowed!";
            echo json_encode($data);
        }
    }
    function salary_manage(){
        if ($this->session->userdata('hr_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        $department = $this->session->userdata('department');
        if($department == "Accountant") {
            $data['page_name']    = 'manage_salary';
            $data['page_title']   = get_phrase('salary');
            $this->load->view('backend/index', $data);
        } else {
            redirect(site_url(), 'refresh');
        }
    }
    function patient_api($task= "" ,  $patient_id = "") {
        header('Content-Type: application/json');

        if ($this->session->userdata('hr_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        switch($task) {
            case "add":
                $config = array(
                    array('field' => 'name', 'label' => 'name', 'rules' => 'trim|required'),
                    array('field' => 'father_name', 'label' =>'father_name', 'rules' => 'trim|required'),
                    array('field' => 'address', 'label' =>'address', 'rules' => 'trim|required'),
                    array('field' => 'phone', 'label' =>'phone', 'rules' => 'trim|required'),
                    array('field' => 'age', 'label' =>'age', 'rules' => 'required'),
                    array('field' => 'gender', 'label' =>'gender', 'rules' => 'required'),
                );
                $this->form_validation->set_rules($config);
                if($this->form_validation->run() == false) {
                    $result['error'] = true;
                    $result['msg'] = array(
                        'name' => form_error('name'),
                        'father_name' => form_error('father_name'),
                        'address' => form_error('address'),
                        'phone' => form_error('phone'),
                        'age' => form_error('age'),
                        'gender' => form_error('gender'),
                    );
                } else {
                    $newPatient = $this->crud_model->save_patient_info();
                    $result['error'] = false;
                    $result['msg'] = 'The patient info was created successfully.';
                    $result['patient'] = $newPatient;
                }
                echo json_encode($result);
                break;
            case "edit":
                $config = array(
                    array('field' => 'name', 'label' => 'name', 'rules' => 'trim|required'),
                    array('field' => 'father_name', 'label' =>'father_name', 'rules' => 'trim|required'),
                    array('field' => 'address', 'label' =>'address', 'rules' => 'trim|required'),
                    array('field' => 'phone', 'label' =>'phone', 'rules' => 'trim|required'),
                    array('field' => 'age', 'label' =>'age', 'rules' => 'required'),
                    array('field' => 'gender', 'label' =>'gender', 'rules' => 'required'),
                );
                $this->form_validation->set_rules($config);
                if($this->form_validation->run() == false) {
                    $result['error'] = true;
                    $result['msg'] = array(
                        'name' => form_error('name'),
                        'father_name' => form_error('father_name'),
                        'address' => form_error('address'),
                        'phone' => form_error('phone'),
                        'age' => form_error('age'),
                        'gender' => form_error('gender'),
                    );
                } else {
                    $this->crud_model->update_patient_info($patient_id);
                    $result['error'] = false;
                    $result['msg'] = 'The patient info was updated successfully.';
                }
                echo json_encode($result);
                break;
            case "list":
                $data['patients'] = $this->crud_model->select_patient_info();
                echo json_encode($data);
                break;
            case "get":
                $data['patient'] = $this->crud_model->select_patient_info_by_id($patient_id);
                echo json_encode($data);
                break;
            case "delete":
                $this->crud_model->delete_patient_info($patient_id);
                $data['msg'] = 'Patient info was deleted successfully.';
                echo json_encode($data);
                break;
        }
    }
    function patient($task = "", $patient_id = "")
    {
        if ($this->session->userdata('department') != 'Accountant') {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        
        $data['patient_info']   = $this->crud_model->select_patient_info();
        $data['page_name']      = 'manage_patient';
        $data['page_title']     = get_phrase('patient');
        $this->load->view('backend/index', $data);
    }
    function pos($invoice_id = ""){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods:POST,GET");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $data = new class{};
        $system_name = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        $address = $this->db->get_where('settings', array('type' => 'address'))->row()->description;
        $phone = $this->db->get_where('settings', array('type' => 'phone'))->row()->description;
        $invoice = $this->crud_model->select_invoice_by_id($invoice_id);
        $status = '';
        $data->contents = (object)[
            'invoice' => $invoice,
            'system_name' => $system_name,
            'address' => $address,
            'phone' => $phone,
        ];
        
        $connector = new WindowsPrintConnector("A8Printer");
        $p = new Printer($connector);
        $p->initialize();
        $contents = $data->contents;
        // // // Title of 
        $logo = EscposImage::load("uploads/logo.png", false);
        $p->feed();
        $p->setJustification(Printer::JUSTIFY_CENTER);
        $p->graphics($logo);
        $p->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $p->text($contents->system_name);
        $p->selectPrintMode();
        $p->feed();
        $p->feed();
        $invoice = $contents->invoice;
        $status = $invoice->status;
        $invoice_no = $invoice->invoice_id;
        $p->setEmphasis(true);
        $p->setJustification(Printer::JUSTIFY_LEFT);
        $p->text("Invoice NO: ".$invoice_no);
        $p->setEmphasis(false);
        $p->feed();
        $p->setJustification(Printer::JUSTIFY_RIGHT);
        $now_time = date('F j, Y, g:i a', now('Asia/Kabul'));
        $p->text("issue date: ".$now_time);
        $p->feed();
        $p->text("status: ".$status);
        $p->feed();
        $p->setJustification(Printer::JUSTIFY_LEFT);
        $p->text("──────────────────────────────────────────");
        $p->feed();
        $patient = $this->db->get_where('patient', array('patient_id' => $invoice->patient_id))->row();
        $p->text("Bill to: #".$patient->patient_id." _ ".$patient->name." _ ".$patient->father_name);
        $p->feed();
        $p->text("──────────────────────────────────────────\n");

        $t1 = str_pad("Item", 26);
        $t2 = str_pad("Qty ", 5, " ", STR_PAD_LEFT);
        $t3 = str_pad("Price ", 5, " ", STR_PAD_LEFT);
        $t4 = str_pad("Total", 5, " ", STR_PAD_LEFT);
        $p->setUnderLine(true);
        $p->text("$t1$t2$t3$t4\n");
        $p->setUnderLine(false);
        $invoice_entries = $invoice->invoice_entries; 
        foreach($invoice_entries as $i){
            $res = preg_split("/\:/",$i->item);
            $left = str_pad($res['0'], 26);
            $m1 = str_pad($i->quantity, 4, " ", STR_PAD_LEFT);
            $m2 = str_pad($i->amount, 6, " ", STR_PAD_LEFT);
            $right = str_pad(($i->amount * $i->quantity), 6, " ", STR_PAD_LEFT);
            $item = "$left$m1$m2$right\n";
            $p->text($item);
        }
        $subtotal = new item('Subtotal', $invoice->total);
        $paid = new item('Paid', $invoice->paid);
        $remain = new item('Remain', ($invoice->total - $invoice->paid));
        $p->text("──────────────────────────────────────────");
        $p->feed();
        $p->setJustification(Printer::JUSTIFY_RIGHT);
        $p -> setEmphasis(true);
        $p->text($subtotal->getAsString(12));
        $p->text($paid->getAsString(12));
        $p->text($remain->getAsString(12));
        $p -> setEmphasis(false);
        $p->cut();
        $p->close();
        echo json_encode($data, true);
    }
    // TODO: invoice crud
    function invoice_add($task = "")
    {
        if ($this->session->userdata('hr_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        
        if ($task == "create") {
            $data = $this->crud_model->create_invoice();
            $this->session->set_flashdata('message', get_phrase('invoice_info_saved_successfuly'));
            redirect(site_url('hr/invoice_manage'), 'refresh');
        }
        
        $data['page_name']  = 'add_invoice';
        $data['page_title'] = get_phrase('invoice');
        $this->load->view('backend/index', $data);
    }
    function invoice($task = "", $invoice_id=""){
        if ($this->session->userdata('hr_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        header('Content-Type: application/json');
        $department = $this->session->userdata('department');
        $hr_id = $this->session->userdata('login_user_id');

        switch($task){
            case "add":
                $config = array(
                    array('field' => 'title', 'label' => 'title', 'rules' => 'trim|required'),
                    array('field' => 'patient_id', 'label' => 'patient_id', 'rules' => 'trim|required'),
                    array('field' => 'hr_id', 'label' => 'hr_id', 'rules' => 'trim|required'),
                    array('field' => 'invoice_entries', 'label' => 'invoice_entries', 'rules' => 'trim|required'),
                );
                $this->form_validation->set_rules($config);
                if($this->form_validation->run() == false) {
                    $result['error'] = true;
                    $result['msg'] = array(
                        'title' => form_error('title'),
                        'patient_id' => form_error('patient_id'),
                        'hr_id' => form_error('hr_id'),
                        'invoice_entries' => form_error('invoice_entries')
                    );
                } else {
                    $this->crud_model->add_invoice($invoice_id);
                    $result['error'] = false;
                    $result['msg'] = 'The invoice info was updated successfully.';
                    redirect(site_url('hr/invoice_manage'), 'refresh');
                }

                echo json_encode($result);
                break;
            case "edit":
                $config = array(
                    array('field' => 'paid', 'label' => 'paid', 'rules' => 'trim|required'),
                );
                $this->form_validation->set_rules($config);
                if($this->form_validation->run() == false) {
                    $result['error'] = true;
                    $result['msg'] = array(
                        'paid' => form_error('paid'),
                    );
                } else {
                    $this->crud_model->update_invoice($invoice_id);
                    $result['error'] = false;
                    $result['msg'] = 'The invoice info was updated successfully.';
                }
                echo json_encode($result);
                break;
            case "list":
                 if($department == "Accountant" || $department == "Receptionist" ) {
                    $data['invoices'] = $this->crud_model->select_invoice();
                    $data['msg'] = "invoice list";
                 } else {
                    $data['invoices'] = $this->crud_model->select_invoice_by_hr($hr_id);
                    $data['msg'] = "Invoice list by HR.";
                 }
                echo json_encode($data);
                break;
            case "get": 
                $data['invoice'] = $this->crud_model->select_invoice_by_id($invoice_id);
                $data['msg'] = "Invoice ";
                echo json_encode($data);
                break;
            case "delete": 
                $this->crud_model->delete_invoice($invoice_id);
                $data['msg'] = "Invoice " . $invoice_id . " deleted successfully.";
                echo json_encode($data);
                break;
        }
         
    }

    function invoice_manage($task = "", $invoice_id = "")
    {
        if ($this->session->userdata('hr_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }

        if ($task == "delete") {
            $this->crud_model->delete_invoice($invoice_id);
            redirect(site_url('hr/invoice_manage'), 'refresh');
        }
        
        $department = $this->session->userdata('department');
        if($department == "Receptionist" || $department == "Accountant") {
            $data['invoices'] = $this->crud_model->select_invoice();
        } else {
            $data['invoices'] = $this->crud_model->select_invoice_info_by_hr_id();
        }
        $data['page_name']    = 'manage_invoice';
        $data['page_title']   = get_phrase('invoice');
        $this->load->view('backend/index', $data);
    }
    // # TODO Medicine
    function medicine($task = "", $medicine_id = "")
    {
        if ($this->session->userdata('hr_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        
        if ($task == "create") {
            $this->crud_model->save_medicine_info();
            $this->session->set_flashdata('message', get_phrase('medicine_info_saved_successfuly'));
            redirect(site_url('hr/medicine'), 'refresh');
        }
        
        if ($task == "update") {
            $this->crud_model->update_medicine_info($medicine_id);
            $this->session->set_flashdata('message', get_phrase('medicine_info_updated_successfuly'));
            redirect(site_url('hr/medicine'), 'refresh');
        }
        
        if ($task == "delete") {
            $this->crud_model->delete_medicine_info($medicine_id);
            redirect(site_url('hr/medicine'), 'refresh');
        }
        
        $data['medicine_info'] = $this->crud_model->select_medicine_info();
        $data['page_name']     = 'manage_medicine';
        $data['page_title']    = get_phrase('medicine');
        $this->load->view('backend/index', $data);
    }
    function medicine_category($task = "", $medicine_category_id = "")
    {
        if ($this->session->userdata('hr_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        
        if ($task == "create") {
            $this->crud_model->save_medicine_category_info();
            $this->session->set_flashdata('message', get_phrase('medicine_category_info_saved_successfuly'));
            redirect(site_url('hr/medicine_category'), 'refresh');
        }
        
        if ($task == "update") {
            $this->crud_model->update_medicine_category_info($medicine_category_id);
            $this->session->set_flashdata('message', get_phrase('medicine_category_info_updated_successfuly'));
            redirect(site_url('hr/medicine_category'), 'refresh');
        }
        
        if ($task == "delete") {
            $this->crud_model->delete_medicine_category_info($medicine_category_id);
            redirect(site_url('hr/medicine_category'), 'refresh');
        }
        
        $data['medicine_category_info'] = $this->crud_model->select_medicine_category_info();
        $data['page_name']              = 'manage_medicine_category';
        $data['page_title']             = get_phrase('medicine_category');
        $this->load->view('backend/index', $data);
    }
    function medication_history($param1 = "", $prescription_id = "")
    {
        if ($this->session->userdata('hr_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        
        $patient_name              = $this->db->get_where('patient', array('patient_id' => $param1))->row()->name; // $param1 = $patient_id
        $data['prescription_info'] = $this->crud_model->select_medication_history($param1); // $param1 = $patient_id
        $data['menu_check']        = 'from_patient';
        $data['page_name']         = 'manage_prescription';
        $data['page_title']        = get_phrase('medication_history_of_:_') . $patient_name;
        $this->load->view('backend/index', $data);
    }
    
    function report($task = "", $report_id = "", $param3 = '')
    {
        if ($this->session->userdata('hr_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        
        if ($task == "create") {
            $this->crud_model->save_report_info();
            $this->session->set_flashdata('message', get_phrase('report_info_saved_successfuly'));
            redirect(site_url('hr/report'), 'refresh');
        }
        
        if ($task == "update") {
            $this->crud_model->update_report_info($report_id);
            $this->session->set_flashdata('message', get_phrase('report_info_updated_successfuly'));
            redirect(site_url('hr/report'), 'refresh');
        }
        
        if ($task == "delete") {
            $this->crud_model->delete_report_info($report_id);
            $this->session->set_flashdata('message', get_phrase('report_info_deleted_successfuly'));
            redirect(site_url('hr/report'), 'refresh');
        }
        
        if ($task == "delete_report_file") {
            $this->crud_model->delete_report_file($report_id, $param3);
            $this->session->set_flashdata('message', get_phrase('file_deleted_successfuly'));
            redirect(site_url('hr/report'), 'refresh');
        }
        
        $data['page_name']  = 'manage_report';
        $data['page_title'] = get_phrase('report');
        $this->load->view('backend/index', $data);
    }

    function operation_report_download(){
        if ($this->session->userdata('hr_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        
        $all_report = $this->db->get_where('report', array('type' => 'operation'))->result_array();
        $data = 'Type'.','.'Description'.','.'Patient'.','.'hr'.','.'Date'."\n";
        foreach($all_report as $row1){
            $hr_id = $row1['hr_id'];
            $patient_id = $row1['patient_id'];
            $date = date('M d Y',$row1['timestamp']);
            $hr = $this->db->get_where('hr', array('hr_id' => $hr_id))->row('first_name');
            $patient = $this->db->get_where('patient', array('patient_id' => $patient_id))->row('name');
            $data .= $row1['type'].','.$row1['description'].','.$patient.','.$hr.','.$date."\n";
        }

        $file = file_put_contents('assets/report.xlsx', $data);
        redirect(base_url('assets/report.xlsx'));
    }

    function birth_report_download(){
        if ($this->session->userdata('hr_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        
        $all_report = $this->db->get_where('report', array('type' => 'birth'))->result_array();
        $data = 'Type'.','.'Description'.','.'Patient'.','.'hr'.','.'Date'."\n";
        foreach($all_report as $row1){
            $hr_id = $row1['hr_id'];
            $patient_id = $row1['patient_id'];
            $date = date('M d Y',$row1['timestamp']);
            $hr = $this->db->get_where('hr', array('hr_id' => $hr_id))->row('name');
            $patient = $this->db->get_where('patient', array('patient_id' => $patient_id))->row('name');
            $data .= $row1['type'].','.$row1['description'].','.$patient.','.$hr.','.$date."\n";
        }

        $file = file_put_contents('assets/report.xlsx', $data);
        redirect(base_url('assets/report.xlsx'));
    }

    function death_report_download(){
        if ($this->session->userdata('hr_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        
        $all_report = $this->db->get_where('report', array('type' => 'death'))->result_array();
        $data = 'Type'.','.'Description'.','.'Patient'.','.'hr'.','.'Date'."\n";
        foreach($all_report as $row1){
            $hr_id = $row1['hr_id'];
            $patient_id = $row1['patient_id'];
            $date = date('M d Y',$row1['timestamp']);
            $hr = $this->db->get_where('hr', array('hr_id' => $hr_id))->row('name');
            $patient = $this->db->get_where('patient', array('patient_id' => $patient_id))->row('name');
            $data .= $row1['type'].','.$row1['description'].','.$patient.','.$hr.','.$date."\n";
        }

        $file = file_put_contents('assets/report.xlsx', $data);
        redirect(base_url('assets/report.xlsx'));
    }
    
    function manage_profile($task = "")
    {
      
        $hr_id = $this->session->userdata('login_user_id');
        if ($task == "update") {
            $this->crud_model->update_hr_info($hr_id);
            redirect(site_url('hr/manage_profile'), 'refresh');
        }
        
        if ($task == "change_password") {
            $password             = $this->db->get_where('hr', array(
                'hr_id' => $hr_id
            ))->row()->password;
            $old_password         = sha1($this->input->post('old_password'));
            $new_password         = $this->input->post('new_password');
            $confirm_new_password = $this->input->post('confirm_new_password');
            
            if ($password == $old_password && $new_password == $confirm_new_password) {
                $data['password'] = sha1($new_password);
                
                $this->db->where('hr_id', $hr_id);
                $this->db->update('hr', $data);
                
                $this->session->set_flashdata('message', get_phrase('password_info_updated_successfuly'));
                redirect(site_url('hr/manage_profile'), 'refresh');
            } else {
                $this->session->set_flashdata('message', get_phrase('password_update_failed'));
                redirect(site_url('hr/manage_profile'), 'refresh');
            }
        }
        $page_data['edit_data']  = $this->db->get_where('hr', array(
            'hr_id' => $this->session->userdata('login_user_id')
        ))->result_array();
        $data['page_name']  = 'manage_profile';
        $data['page_title'] = get_phrase('profile');
        $this->load->view('backend/index', $data);
    }
    
    function prescription($task = "", $prescription_id = "", $menu_check = '', $patient_id = '')
    {
        if ($this->session->userdata('hr_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        
        if ($task == "create") {
            $this->crud_model->save_prescription_info();
            $this->session->set_flashdata('message', get_phrase('prescription_info_saved_successfuly'));
            redirect(site_url('hr/prescription'), 'refresh');
        }
        
        if ($task == "update") {
            $this->crud_model->update_prescription_info($prescription_id);
            $this->session->set_flashdata('message', get_phrase('prescription_info_updated_successfuly'));
            if ($menu_check == 'from_prescription')
                redirect(site_url('hr/prescription'), 'refresh');
            else
                redirect(site_url('hr/medication_history/' . $patient_id), 'refresh');
        }
        
        if ($task == "delete") {
            $this->crud_model->delete_prescription_info($prescription_id);
            if ($menu_check == 'from_prescription')
                redirect(site_url('hr/prescription'), 'refresh');
            else
                redirect(site_url('hr/medication_history/' . $patient_id), 'refresh');
        }
        
        $data['prescription_info'] = $this->crud_model->select_prescription_info_by_hr_id();
        $data['menu_check']        = 'from_prescription';
        $data['page_name']         = 'manage_prescription';
        $data['page_title']        = get_phrase('prescription');
        $this->load->view('backend/index', $data);
    }
    
    function diagnosis_report($task = "", $diagnosis_report_id = "")
    {
        if ($this->session->userdata('hr_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(site_url(), 'refresh');
        }
        
        if ($task == "create") {
            $this->crud_model->save_diagnosis_report_info();
            $this->session->set_flashdata('message', get_phrase('diagnosis_report_info_saved_successfuly'));
            redirect(site_url('hr/prescription'), 'refresh');
        }
        
        if ($task == "delete") {
            $this->crud_model->delete_diagnosis_report_info($diagnosis_report_id);
            $this->session->set_flashdata('message', get_phrase('diagnosis_report_info_deleted_successfuly'));
            redirect(site_url('hr/prescription'), 'refresh');
        }
    }
    
    /* private messaging */
    
    function message($param1 = 'message_home', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('hr_login') != 1)
            redirect(site_url(), 'refresh');
        
        if ($param1 == 'send_new') {
            $message_thread_code = $this->crud_model->send_new_private_message();
            $this->session->set_flashdata('message', get_phrase('message_sent!'));
            redirect(site_url('hr/message/message_read/' . $message_thread_code), 'refresh');
        }
        
        if ($param1 == 'send_reply') {
            $this->crud_model->send_reply_message($param2); //$param2 = message_thread_code
            $this->session->set_flashdata('message', get_phrase('message_sent!'));
            redirect(site_url('hr/message/message_read/' . $param2), 'refresh');
        }
        
        if ($param1 == 'message_read') {
            $page_data['current_message_thread_code'] = $param2; // $param2 = message_thread_code
            $this->crud_model->mark_thread_messages_read($param2);
        }
        
        $page_data['message_inner_page_name'] = $param1;
        $page_data['page_name']               = 'message';
        $page_data['page_title']              = get_phrase('private_messaging');
        $this->load->view('backend/index', $page_data);
    }
    
    function payroll_list($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('hr_login') != 1)
            redirect(site_url(), 'refresh');
        
        $page_data['page_name']  = 'payroll_list';
        $page_data['page_title'] = get_phrase('payroll_list');
        $this->load->view('backend/index', $page_data);
    }
    
    function send_sms($message = '', $reciever_phone = '')
    {
        //$to = "[\"<$reciever_phone>\"]";
        echo $to = $reciever_phone;
        echo $text = $message;
        $authToken = "ZaD9vMp5S3imW39pM77PEg==";
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, "https://api.clickatell.com/rest/message");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"text\":\"$text\",\"to\":$to}");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "X-Version: 1",
            "Content-Type: application/json",
            "Accept: application/json",
            "Authorization: Bearer $authToken"
        ));
        
        $result = curl_exec($ch);
        
        echo $result;
    }
    
}
class item
{
    private $name;
    private $price;
    private $qty;

    public function __construct($name = '', $price = '', $qty = '')
    {
        $this->name = $name;
        $this->price = $price;
        $this->qty = $qty;
    }

    public function getAsString($width = 48)
    {
        $rightCols = 10;
        $leftCols = $width - $rightCols;
        if ($this->qty) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this->name, $leftCols);

        $sign = ($this->qty ? '*' . $this->qty.'='.($this->qty*$this->price) : '');
        $right = str_pad($this->price , $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }

    public function __toString()
    {
        return $this->getAsString();
    }

}