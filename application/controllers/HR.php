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
        // $this->load->database();
        // $this->load->library('session');
        // $this->load->model('crud_model');
        // $this->load->model('frontend_model');
        // cache control
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }
    
    function index()
    {
        // if ($this->session->userdata('hr_login') != 1 ) 
        //     redirect(site_url(), 'refresh');
        // if ($this->session->userdata('hr_login') == 1 ) 
        //     redirect(site_url('hr/dashboard'), 'refresh');
    } 
    function dashboard(){
        // if ($this->session->userdata('hr_login') != 1 ){
        //     redirect(site_url(), 'refresh');
        //     $this->session->set_userdata('last_page', current_url());
        // } 
        $data['page_name']  = 'dashboard';
        // $data['page_title'] = $this->session->userdata('department');
        $this->load->view('backend/index', $data);
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
     
    
}
