<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller
{
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('crud_model');
        $this->load->model('email_model');
        $this->load->database();
        $this->load->library('session');
        /* cache control */
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");
    }
    
    //Default function, redirects to logged in user area
    public function index()
    {
        if ($this->session->userdata('admin_login') == 1)
            redirect(site_url('admin/dashboard'), 'refresh');
        else if ($this->session->userdata('hr_login') == 1)
            redirect(site_url('HR/dashboard'), 'refresh');

        $this->load->view('backend/login');
    }
    
    //Ajax login function 
    function do_login()
    {
        $this->load->library('form_validation');  
        $this->form_validation->set_rules('email', 'Email', 'required');  
        $this->form_validation->set_rules('password', 'Password', 'required');  
        //Validating login
        if($this->form_validation->run())  
        {  
            //true  
            $email = $this->input->post('email');  
            $password = $this->input->post('password');
            $login_status = $this->validate_login($email, $password);
            if ($login_status == 'success') {
                redirect(site_url('login'), 'refresh');
            } else {
                $this->session->set_flashdata('error_message', get_phrase('login_failed'));
                redirect(site_url('login'), 'refresh');
            }
        }
    }
    
    //Validating login from ajax request
    function validate_login($email = '', $password = '')
    {
        $credential = array(
            'email' => $email,
            'password' => sha1($password)
        );
        try {
            // admin ...
            $query = $this->db->get_where('admin', $credential);
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $this->session->set_userdata('admin_login', '1');
                $this->session->set_userdata('login_user_id', $row->admin_id);
                $this->session->set_userdata('name', $row->name);
                $this->session->set_userdata('login_type', 'admin');
                $this->session->set_userdata('department', 'Admin');

                return 'success';
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('error_message', 'Caught exception: ' .  $e->getMessage());
        }
        
        try {
            //hr...
            $join = array('department', 'department.department_id=hr.department_id');
            $select = 'hr.hr_id as hr_id, department.name as department_name, concat_ws(" ",hr.first_name, hr.last_name) as name';
            $query = $this->db->select($select)->join($join[0], $join[1])->get_where('hr', $credential)->row();
            $department = $query->department_name;
            $hr_id = $query->hr_id;
            $name = $query->name;
            $this->session->set_userdata('hr_login', '1');
            $this->session->set_userdata('login_user_id', $hr_id);
            $this->session->set_userdata('name', $name);
            $this->session->set_userdata('login_type', 'hr');
            $this->session->set_userdata('department', $department);
            return 'success';
        } catch (Exception $e) {
            $this->session->set_flashdata('error_message', 'Caught exception: ' .  $e->getMessage());
        }
        
        return 'invalid';
    }
    
    /*     * *DEFAULT NOT FOUND PAGE**** */
    
    function four_zero_four()
    {
        $this->load->view('four_zero_four');
    }
    
    /*     * *RESET AND SEND PASSWORD TO REQUESTED EMAIL*** */
    
    function forgot_password()
    {
        $this->load->view('backend/forgot_password');
    }
    
    function reset_password()
    {
        $email              = $this->input->post('email');
        $reset_account_type = '';
        $new_password       = substr(md5(rand(100000000, 20000000000)), 0, 7);
        // checking credential for admin
        $query              = $this->db->get_where('admin', array(
            'email' => $email
        ));
        if ($query->num_rows() > 0) {
            $reset_account_type = 'admin';
            $this->db->where('email', $email);
            $this->db->update('admin', array(
                'password' => sha1($new_password)
            ));
        }
        // checking credential for hr
        $query = $this->db->get_where('hr', array(
            'email' => $email
        ));
        if ($query->num_rows() > 0) {
            $reset_account_type = 'hr';
            $this->db->where('email', $email);
            $this->db->update('hr', array(
                'password' => sha1($new_password)
            ));
        }
        
        $result = $this->email_model->password_reset_email($reset_account_type, $email, $new_password);
        if ($result == true) {
            $this->session->set_flashdata('success_message', 'Please check your email for the new password');
        } else {
            $this->session->set_flashdata('error_message', 'Could not find the email that you have entered');
        }
        redirect(site_url('login/forgot_password'), 'refresh');
    }
    
    /*     * *****LOGOUT FUNCTION ****** */
    
    function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata('logout_notification', 'logged_out');
        redirect(site_url('login'), 'refresh');
    }
}
