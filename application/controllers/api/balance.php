<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Balance extends CI_Controller{
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
    // }
  }
  function index(){ 
    $data['message'] = "balance list retrieved successfully.";
    echo json_encode($data);
  }
}
