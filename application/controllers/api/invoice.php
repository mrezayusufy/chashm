<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Invoice extends CI_Controller{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
    $this->load->model('crud_model');
    if ($this->session->userdata('hr_login') != 1 || $this->session->userdata('admin_login') != 1 ) {
      $this->session->set_userdata('last_page', current_url());
      $result = array("message" => "You are not allowed.");
      echo json_encode($result);
      redirect(site_url(), 'refresh');
    }
  }
  function index(){ 
    $data['invoices'] = $this->crud_model->select_invoice();
    $data['message'] = "invoice list retrieved successfully.";
    echo json_encode($data);
  }
  function get($invoice_id=""){
    $data = $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row();
    $invoice_entry_id = array();
    $invoice_entry_id = json_decode($data->invoice_entry_id);
    $invoice_entry = array();
    foreach ($invoice_entry_id as $i) {
        $invoice_entry[] = $this->db->get_where('invoice_entry', array('invoice_entry_id' => strval($i)))->row();
    }
    $data->patient = $this->db->get_where('patient', array('patient_id' => $data->patient_id))->row();
    $hr = $this->db->get_where('hr', array('hr_id' => $data->hr_id))->row();
    $data->hr = array('first_name' => $hr->first_name, 'last_name' => $hr->last_name, 'hr_id' => $hr->hr_id);
    $data->invoice_entries = $invoice_entry;
    echo json_encode($data);
  }
  function edit($invoice_id = ""){
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
  }
  function delete($invoice_id){
    $this->crud_model->delete_invoice($invoice_id);
    $data['msg'] = "Invoice " . $invoice_id . " deleted successfully.";
    echo json_encode($data);
  }
}
