<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Salary extends CI_Controller{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
    $this->load->model('crud_model');
    // if ($this->session->userdata('hr_login') != 1 || $this->session->userdata('admin_login') != 1 ) {
    //   $this->session->set_userdata('last_page', current_url());
    //   $result = array("message" => "You are not allowed.");
    //   echo json_encode($result);
    //   redirect(site_url(), 'refresh');
    // }
  }
  function index(){ 
    $data['salaries'] = $this->crud_model->select_salary();
    $data['message'] = "salary list retrieved successfully.";
    echo json_encode($data);
  }
  function get($salary_id=""){
    $data = $this->db->get_where('salary', array('salary_id' => $salary_id))->row();
    $salary_entry_id = array();
    $salary_entry_id = json_decode($data->salary_entry_id);
    $salary_entry = array();
    foreach ($salary_entry_id as $i) {
        $salary_entry[] = $this->db->get_where('salary_entry', array('salary_entry_id' => strval($i)))->row();
    }
    $data->patient = $this->db->get_where('patient', array('patient_id' => $data->patient_id))->row();
    $hr = $this->db->get_where('hr', array('hr_id' => $data->hr_id))->row();
    $data->hr = array('first_name' => $hr->first_name, 'last_name' => $hr->last_name, 'hr_id' => $hr->hr_id);
    $data->salary_entries = $salary_entry;
    echo json_encode($data);
  }
  function edit($salary_id = ""){
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
        $this->crud_model->update_salary($salary_id);
        $result['error'] = false;
        $result['msg'] = 'The salary info was updated successfully.';
    }
    echo json_encode($result);
  }
  function delete($salary_id){
    $this->crud_model->delete_salary($salary_id);
    $data['msg'] = "Salary " . $salary_id . " deleted successfully.";
    echo json_encode($data);
  }
}
