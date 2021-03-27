<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class Crud_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper("date");
    }

    function clear_cache()
    {
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function get_type_name_by_id($type, $type_id = '', $field = 'name')
    {
        $this->db->where($type . '_id', $type_id);
        $query = $this->db->get($type);
        $result = $query->result_array();
        foreach ($result as $row)
            return $row[$field];
        //return	$this->db->get_where($type,array($type.'_id'=>$type_id))->row()->$field;
    } 
    function total_count($column_name, $where, $table_name)
    {

        $this->db->select_sum($column_name);
        // If Where is not NULL
        if (!empty($where) && count($where) > 0) {
            $this->db->where($where);
        }

        $this->db->from($table_name);
        // Return Count Column
        return $this->db->get()->row(); //table_name array sub 0

    }
    function create_invoice()
    {
        $data = array(
            'title'              => $this->input->post('title'),
            'patient_id'         => $this->input->post('patient_id'),
            'hr_id'              => $this->input->post('hr_id'),
            'paid'               => 0,
            'status'             => 'unpaid',
            'creation_timestamp' => now('Asia/Kabul')
        );
        $invoice_entry_id = array();
        $items                      = $this->input->post('item');
        $quantities                 = $this->input->post('quantity');
        $amounts                    = $this->input->post('amount');
        $total = 0;
        for ($i = 0; $i <= count($items); $i++) {
            if ($items[$i] != "" && $amounts[$i] != "" && $quantities[$i] != "") {
                $new_entry          = array('item' => $items[$i], 'quantity' => $quantities[$i], 'amount' => $amounts[$i]);
                $this->db->insert('invoice_entry', $new_entry);
                $invoice_entry_id_item = $this->db->insert_id();
                if ($this->session->userdata('department') == "Pharmacist") {
                    $medicine_items = explode(":", $items[$i]);
                    $medicine = $this->db->get_where("medicine", array("medicine_id" => $medicine_items["2"]))->row();
                    $update_medicine = array("total_quantity" => ($medicine->total_quantity - $quantities[$i]));
                    $this->db->update("medicine", $update_medicine, array("medicine_id" => $medicine->medicine_id));
                }
                $total += $amounts[$i] * $quantities[$i];
                $invoice_entry_id[] = $invoice_entry_id_item;
            }
        }
        $data['invoice_entry_id']    = json_encode($invoice_entry_id);
        $data['total']              = $total;
        $returned_array = null_checking($data);
        $this->db->insert('invoice', $returned_array);
    }

    function update_invoice($invoice_id)
    {
        $invoice = $this->db->get_where('invoice', array('invoice_id'=>$invoice_id))->row();
        $data['title']              = $this->input->post('title');
        $data['patient_id']         = $this->input->post('patient_id');
        $data['hr_id']              = $this->input->post('hr_id');
        $data['paid']               = $this->input->post('paid');
        $data['creation_timestamp'] = now('Asia/Kabul');
        $data['status']             = $invoice->total == $data['paid'] ? 'paid' : 'unpaid';

        $this->db->where('invoice_id', $invoice_id);
        $this->db->update('invoice', $data);
    }
    function select_invoice_by_id($invoice_id)
    {
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
        return $data;
    }
    function select_invoice()
    {
        $query = 'i.invoice_id as invoice_id, i.total as total, i.invoice_entry_id as invoice_entry_id, i.title as title, concat_ws(" ", h.hr_id, h.first_name, h.last_name) as hr_name, concat_ws(" ", p.patient_id, p.name, p.father_name) as patient_name, i.creation_timestamp as creation_timestamp, i.status as status, i.paid as paid';
        $this->db->select($query)
            ->from('invoice i')
            ->join('hr h', 'i.hr_id=h.hr_id', 'left')
            ->join('patient p', 'i.patient_id=p.patient_id', 'left')
            ->join('invoice_entry ie', 'i.invoice_entry_id=ie.invoice_entry_id', 'left')
            ->order_by('i.creation_timestamp', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }
    function count_invoice()
    {
        $hr_id = $this->session->userdata('login_user_id');
        $query = 0;
        if ($this->session->userdata('department') == "Accountant") {
            $query = $this->db->count_all_results('invoice');
        } else {
            $this->db->where('hr_id', $hr_id);
            $this->db->from('invoice');
            $query = $this->db->count_all_results();
        }
        return $query;
    }
    function select_invoice_by_hr($hr_id)
    {
        $query = 'i.invoice_id as invoice_id, i.total as total, i.invoice_entry_id as invoice_entry_id,  i.title as title, concat_ws(" ", h.hr_id, h.first_name, h.last_name) as hr_name, concat_ws(" ", p.patient_id, p.name, p.father_name) as patient_name, i.creation_timestamp as creation_timestamp, i.status as status, i.paid as paid';
        $this->db->select($query)
            ->from('invoice i')
            ->join('hr h', 'i.hr_id=h.hr_id', 'left')
            ->join('patient p', 'i.patient_id=p.patient_id', 'left')
            ->join('invoice_entry ie', 'i.invoice_entry_id=ie.invoice_entry_id', 'left')
            ->where('i.hr_id', $hr_id)
            ->order_by('invoice_id', 'desc');
        return $this->db->get()->result_array();
    }
    function select_invoice_info_by_hr_id()
    {
        $hr_id = $this->session->userdata('login_user_id');
        return $this->db->get_where('invoice', array('hr_id' => $hr_id))->result_array();
    }

    function delete_invoice($invoice_id)
    {
        $data = $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row();
        $invoice_entry_id = array();
        $invoice_entry_id = json_decode($data->invoice_entry_id);
        foreach ($invoice_entry_id as $i) {
            $this->db->where('invoice_entry_id', strval($i));
            $this->db->delete('invoice_entry');
        }

        $this->db->where('invoice_id', $invoice_id);
        $this->db->delete('invoice');
    }
    function calculate_invoice_total_amount($invoice_id)
    {
        $total_amount           = 0;
        $invoice                = $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->result_array();
        foreach ($invoice as $row) {
            $invoice_entries    = json_decode($row['invoice_entries']);
            foreach ($invoice_entries as $invoice_entry)
                $total_amount  += $invoice_entry->amount * $invoice_entry->quantity;

            $grand_total        = $total_amount;
        }
        return $grand_total;
    }

    //////system settings//////
    function update_system_settings()
    {
        $data['description'] = $this->input->post('system_name');
        $returned_array = null_checking($data);
        $this->db->where('type', 'system_name');
        $this->db->update('settings', $returned_array);

        $data['description'] = $this->input->post('system_title');
        $returned_array = null_checking($data);
        $this->db->where('type', 'system_title');
        $this->db->update('settings', $returned_array);

        $data['description'] = $this->input->post('address');
        $returned_array = null_checking($data);
        $this->db->where('type', 'address');
        $this->db->update('settings', $returned_array);

        $data['description'] = $this->input->post('phone');
        $returned_array = null_checking($data);
        $this->db->where('type', 'phone');
        $this->db->update('settings', $returned_array);

        $data['description'] = $this->input->post('paypal_email');
        $returned_array = null_checking($data);
        $this->db->where('type', 'paypal_email');
        $this->db->update('settings', $returned_array);

        $data['description'] = $this->input->post('currency');
        $returned_array = null_checking($data);
        $this->db->where('type', 'currency');
        $this->db->update('settings', $returned_array);

        $data['description'] = $this->input->post('system_email');
        $returned_array = null_checking($data);
        $this->db->where('type', 'system_email');
        $this->db->update('settings', $returned_array);

        $data['description'] = $this->input->post('buyer');
        $returned_array = null_checking($data);
        $this->db->where('type', 'buyer');
        $this->db->update('settings', $returned_array);

        $data['description'] = $this->input->post('purchase_code');
        $returned_array = null_checking($data);
        $this->db->where('type', 'purchase_code');
        $this->db->update('settings', $returned_array);

        $data['description'] = $this->input->post('language');
        $returned_array = null_checking($data);
        $this->db->where('type', 'language');
        $this->db->update('settings', $returned_array);

        $data['description'] = $this->input->post('text_align');
        $returned_array = null_checking($data);
        $this->db->where('type', 'text_align');
        $this->db->update('settings', $returned_array);

        move_uploaded_file($_FILES['logo']['tmp_name'], 'uploads/logo.png');
    }

    // SMS settings.
    function update_sms_settings()
    {

        $data['description'] = $this->input->post('clickatell_user');
        $returned_array = null_checking($data);
        $this->db->where('type', 'clickatell_user');
        $this->db->update('settings', $returned_array);

        $data['description'] = $this->input->post('clickatell_password');
        $returned_array = null_checking($data);
        $this->db->where('type', 'clickatell_password');
        $this->db->update('settings', $returned_array);

        $data['description'] = $this->input->post('clickatell_api_id');
        $returned_array = null_checking($data);
        $this->db->where('type', 'clickatell_api_id');
        $this->db->update('settings', $returned_array);
    }

    /////creates log/////
    function create_log($data)
    {
        $data['timestamp'] = strtotime(date('Y-m-d') . ' ' . date('H:i:s'));
        $data['ip'] = $_SERVER["REMOTE_ADDR"];
        $location = new SimpleXMLElement(file_get_contents('http://freegeoip.net/xml/' . $_SERVER["REMOTE_ADDR"]));
        $data['location'] = $location->City . ' , ' . $location->CountryName;
        $this->db->insert('log', $data);
    }

    ////////BACKUP RESTORE/////////
    function create_backup($type)
    {
        $this->load->dbutil();

        $options = array(
            'format' => 'txt', // gzip, zip, txt
            'add_drop' => TRUE, // Whether to add DROP TABLE statements to backup file
            'add_insert' => TRUE, // Whether to add INSERT data to backup file
            'newline' => "\n"               // Newline character used in backup file
        );

        if ($type == 'all') {
            $tables = array('');
            $file_name = 'system_backup';
        } else {
            $tables = array('tables' => array($type));
            $file_name = 'backup_' . $type;
        }

        $backup = &$this->dbutil->backup(array_merge($options, $tables));


        $this->load->helper('download');
        force_download($file_name . '.sql', $backup);
    }

    /////////RESTORE TOTAL DB/ DB TABLE FROM UPLOADED BACKUP SQL FILE//////////
    function restore_backup()
    {
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/backup.sql');
        $this->load->dbutil();


        $prefs = array(
            'filepath' => 'uploads/backup.sql',
            'delete_after_upload' => TRUE,
            'delimiter' => ';'
        );
        $restore = &$this->dbutil->restore($prefs);
        unlink($prefs['filepath']);
    }

    /////////DELETE DATA FROM TABLES///////////////
    function truncate($type)
    {
        if ($type == 'all') {
            $this->db->truncate('student');
            $this->db->truncate('mark');
            $this->db->truncate('teacher');
            $this->db->truncate('subject');
            $this->db->truncate('class');
            $this->db->truncate('exam');
            $this->db->truncate('grade');
        } else {
            $this->db->truncate($type);
        }
    }

    ////////IMAGE URL//////////
    function get_image_url($type = '', $id = '')
    {
        if (file_exists('uploads/' . $type . '_image/' . $id . '.jpg'))
            $image_url = base_url() . 'uploads/' . $type . '_image/' . $id . '.jpg';
        else
            $image_url = base_url() . 'uploads/user.jpg';

        return $image_url;
    }

    function save_salary_info()
    {
        $data['tazkira_id'] = $this->input->post('tazkira_id');
        $data['salary'] = $this->input->post('salary');
        $data['date'] = now("Asia/Kabul");
        $data['status']         = $this->input->post('status');
        $this->db->insert('salary', $data);
        $salary_id              = $this->db->insert_id();
        $data['salary_id'] = $salary_id;
        return $data;
    }
    function select_salary_info()
    {
        $this->db->select('s.salary_id, s.tazkira_id, concat_ws(" ", hr.first_name, hr.last_name, staff.name) as name, concat_ws(" ", hr.department_id, staff.department_id) as department_id, s.date, s.status, s.salary')
            ->from('salary s')
            ->join('hr', 's.tazkira_id=hr.tazkira_id', 'left')
            ->join('staff', 's.tazkira_id=staff.tazkira_id', 'left')
            ->order_by('s.salary_id', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    function select_salary()
    {
        $query = 's.salary_id, s.tazkira_id, concat_ws(" ",hr.first_name, hr.last_name, ss.name) as name, concat_ws(" ",hr.salary, ss.salary) as hr_salary, d.name as department_name, s.date, s.status, s.salary';
        $this->db->select($query)
            ->from('salary s')
            ->join('hr', 's.tazkira_id=hr.tazkira_id', 'left')
            ->join('staff ss', 's.tazkira_id=ss.tazkira_id', 'left')
            ->join('department d', 'ss.department_id=d.department_id OR hr.department_id=d.department_id', 'left')
            ->order_by('s.salary_id', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    function select_salary_by_id($salary_id)
    {
        $query = 's.salary_id, s.tazkira_id, concat_ws(" ",hr.first_name, hr.last_name, ss.name) as name, concat_ws(" ",hr.salary, ss.salary) as hr_salary, d.name as department_name, s.date, s.status, s.salary';
        $this->db->select($query)
            ->from('salary s')
            ->where('salary_id', $salary_id)
            ->join('hr', 's.tazkira_id=hr.tazkira_id', 'left')
            ->join('staff ss', 's.tazkira_id=ss.tazkira_id', 'left')
            ->join('department d', 'ss.department_id=d.department_id OR hr.department_id=d.department_id', 'left')
            ->order_by('s.salary_id', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function update_salary_info($salary_id)
    {
        $type                   = $this->session->userdata('login_type');
        $data['tazkira_id']     = $this->input->post('tazkira_id');
        $data['salary']         = $this->input->post('salary');
        if ($this->input->post('date') != '')
            $data['date']    = strtotime($this->input->post('date'));
        else
            $data['date']    = '';
        $data['status']        = $this->input->post('status');
        $returned_array        = null_checking($data);
        $this->db->where('salary_id', $salary_id);
        $this->db->update('salary', $returned_array);
        $this->session->set_flashdata('message', get_phrase('updated_successfuly'));
    }
    function delete_salary_info($salary_id)
    {
        $this->db->where('salary_id', $salary_id);
        $this->db->delete('salary');
    }
    // crud staff
    function save_staff_info()
    {
        $data['tazkira_id']     = $this->input->post('tazkira_id');
        $data['name']           = $this->input->post('name');
        $data['address']        = $this->input->post('address');
        $data['salary']        = $this->input->post('salary');
        $data['phone']          = $this->input->post('phone');
        $data['department_id']       = $this->input->post('department_id');

        $returned_array = null_checking($data);
        $this->db->insert('staff', $returned_array);
        $staff_id  =   $this->db->insert_id();
        move_uploaded_file($_FILES["image"]["tmp_name"], 'uploads/doctor_image/' . $staff_id . '.jpg');
    }
    function select_staff_info()
    {
        return $this->db->get('staff')->result_array();
    }
    function update_staff_info($staff_id)
    {
        $type = $this->session->userdata('login_type');
        $data['tazkira_id']     = $this->input->post('tazkira_id');
        $data['name']             = $this->input->post('name');
        $data['address']         = $this->input->post('address');
        $data['salary']         = $this->input->post('salary');
        $data['phone']          = $this->input->post('phone');
        $data['department_id']         = $this->input->post('department_id');

        $validation = tazkira_id_validation_on_edit($data['tazkira_id'], $staff_id, 'staff');
        if ($validation == 1) {
            $returned_array = null_checking($data);
            $this->db->where('staff_id', $staff_id);
            $this->db->update('staff', $returned_array);
            move_uploaded_file($_FILES["image"]["tmp_name"], 'uploads/staff_image/' . $staff_id . '.jpg');
            $this->session->set_flashdata('message', get_phrase('updated_successfuly'));
        } else {
            $this->session->set_flashdata('error_message', get_phrase('duplicate_email'));
        }
    }

    function delete_staff_info($staff_id)
    {
        $this->db->where('staff_id', $staff_id);
        $this->db->delete('staff');
    }
    // department
    function save_department_info()
    {
        $data['name']            = $this->input->post('name');
        $data['description']   = $this->input->post('description');
        $returned_array        = null_checking($data);
        $this->db->insert('department', $returned_array);

        $department_id = $this->db->insert_id();
        move_uploaded_file($_FILES['dept_icon']['tmp_name'], 'uploads/frontend/department_images/' . $department_id . '.png');
    }

    function select_department_info()
    {
        return $this->db->get('department')->result_array();
    }

    function update_department_info($department_id)
    {
        $data['name']         = $this->input->post('name');
        $data['description']     = $this->input->post('description');
        $returned_array = null_checking($data);
        $this->db->where('department_id', $department_id);
        $this->db->update('department', $returned_array);
        move_uploaded_file($_FILES['dept_icon']['tmp_name'], 'uploads/frontend/department_images/' . $department_id . '.png');
    }

    function delete_department_info($department_id)
    {
        if (file_exists(base_url('uploads/frontend/department_images/' . $department_id . '.png'))) {
            unlink(base_url('uploads/frontend/department_images/' . $department_id . '.png'));
        }
        $this->db->where('department_id', $department_id);
        $this->db->delete('department');
    }
    function select_hr_by_tazkira_id(){
        $hr= $this->db->get("hr")->result_array();
        $staff= $this->db->get("staff")->result_array();
        $hrs = array();
        foreach($hr as $h){
            array_push($hrs, array(
                "tazkira_id" => $h["tazkira_id"],
                "name" => $h["first_name"]." ". $h["last_name"],
                "salary" => $h["salary"],
            ));
        }
        foreach($staff as $ss){
            array_push($hrs, array(
                "tazkira_id" => $ss["tazkira_id"],
                "name" => $ss["name"],
                "salary" => $ss["salary"],
            ));
        }
        return $hrs;
    }
    function save_hr_info()
    {
        $data['tazkira_id']         = $this->input->post('tazkira_id');
        $data['first_name']         = $this->input->post('first_name');
        $data['last_name']          = $this->input->post('last_name');
        $data['email']              = $this->input->post('email');
        $data['password']           = sha1($this->input->post('password'));
        $data['address']            = $this->input->post('address');
        $data['salary']            = $this->input->post('salary');
        $data['phone']              = $this->input->post('phone');
        $data['department_id']      = $this->input->post('department_id');
        $hr_id = $this->db->insert_id();
        $validation = email_validation_on_create($data['email'], $hr_id, 'hr');
        $tazkira_validation = tazkira_id_validation_on_create($data['tazkira_id'], $hr_id, 'hr');
        if ($validation == 1 && $tazkira_validation == 1) {
            $returned_array = null_checking($data);
            $this->db->insert('hr', $returned_array);
            $this->email_model->account_opening_email('hr', $data['email'], $this->input->post('password'));
            move_uploaded_file($_FILES["image"]["tmp_name"], 'uploads/hr_image/' . $hr_id . '.jpg');
        } else {
            $this->session->set_flashdata('error_message', get_phrase('duplicate_email'));
            $email = $this->session->set_flashdata('error_message', get_phrase('duplicate_email'));
            $tazkira = $this->session->set_flashdata('error_message', get_phrase('duplicate_tazkira'));
            $validation == 0 ? $email : $tazkira;
            redirect(site_url('admin/hr'), 'refresh');
        }
    }

    function select_hr_info()
    {
        return $this->db->get('hr')->result_array();
    }
    function select_hr_info_by_id($hr_id)
    {
        return $this->db->get_where('hr', array('hr_id' => $hr_id))->row();
    }

    function update_hr_info($hr_id)
    {
        $type  = $this->session->userdata('login_type');
        $data['tazkira_id']    = $this->input->post('tazkira_id');
        $data['first_name']    = $this->input->post('first_name');
        $data['last_name']     = $this->input->post('last_name');
        $data['email']         = $this->input->post('email');
        $data['address']       = $this->input->post('address');
        $data['salary']        = $this->input->post('salary');
        $data['phone']         = $this->input->post('phone');
        $data['department_id'] = $this->input->post('department_id');

        $validation = email_validation_on_edit($data['email'], $hr_id, 'hr');
        $tazkira_validation = tazkira_id_validation_on_edit($data['tazkira_id'], $hr_id, 'hr');

        if ($validation == 1 && $tazkira_validation == 1) {
            $returned_array = null_checking($data);
            $this->db->where('hr_id', $hr_id);
            $this->db->update('hr', $returned_array);
            if (!is_dir('uploads/hr_image')) {
                mkdir('./uploads/hr_image', 0777, true);
            }
            move_uploaded_file($_FILES["image"]["tmp_name"], 'uploads/hr_image/' . $hr_id . '.jpg');
            $this->session->set_flashdata('message', get_phrase('updated_successfuly'));
        } else {
            $email      = $this->session->set_flashdata('error_message', get_phrase('duplicate_email'));
            $tazkira    = $this->session->set_flashdata('error_message', get_phrase('duplicate_tazkira'));
            $validation == 0 ? $email : $tazkira;
            redirect(site_url('admin/hr'), 'refresh');
        }
    }

    function delete_hr_info($hr_id)
    {
        $data = $this->db->where('hr_id', $hr_id);
        $this->create_log($data);
        $this->db->delete('hr');
    }
    function save_patient_info()
    {
        $data['name']                 = $this->input->post('name');
        $data['father_name']          = $this->input->post('father_name');
        $data['address']              = $this->input->post('address');
        $data['phone']                = $this->input->post('phone');
        $data['gender']               = $this->input->post('gender');
        $data['age']                  = $this->input->post('age');
        $data['created_at']           = now('Asia/Kabul');
        $this->db->insert('patient', $data);
        $patient_id = $this->db->insert_id();
        $data['patient_id'] = $patient_id;
        return $data;
    }

    function update_patient_info($patient_id)
    {
        $data['name']               = $this->input->post('name');
        $data['father_name']        = $this->input->post('father_name');
        $data['address']            = $this->input->post('address');
        $data['phone']              = $this->input->post('phone');
        $data['gender']             = $this->input->post('gender');
        $data['age']                = $this->input->post('age');
        $data['updated_at']         = now('Asia/Kabul');
        $this->db->where('patient_id', $patient_id);
        $this->db->update('patient', $data);
    }
    function select_patient_info()
    {
        $this->db->select("*")->from('patient');
        $this->db->order_by('patient_id', 'DESC');
        return $this->db->get()->result_array();
    }

    function select_patient_info_by_id($patient_id = '')
    {
        return $this->db->get_where('patient', array('patient_id' => $patient_id))->result_array();
    }


    function delete_patient_info($patient_id)
    {
        $this->db->where('patient_id', $patient_id);
        $this->db->delete('patient');
    }

    function save_medicine_info()
    {
        $data['name']                   = $this->input->post('name');
        $data['description']            = $this->input->post('description');
        $data['price']                  = $this->input->post('price');
        $data['manufacturing_company']  = $this->input->post('manufacturing_company');
        $data['medicine_category_id']   = $this->input->post('medicine_category_id');
        $data['total_quantity']         = $this->input->post('total_quantity');
        $data['sold_quantity']          = 0;
        $returned_array = null_checking($data);
        $this->db->insert('medicine', $returned_array);
    }

    function select_medicine_info()
    {
        return $this->db->get('medicine')->result_array();
    }
    function update_medicine($medicine_id, $field = array())
    {
        $this->db->where('medicine_id', $medicine_id);
        $this->db->update('medicine', $field);
    }
    function update_medicine_info($medicine_id)
    {
        $data['name']                   = $this->input->post('name');
        $data['description']            = $this->input->post('description');
        $data['price']                  = $this->input->post('price');
        $data['manufacturing_company']  = $this->input->post('manufacturing_company');
        $data['medicine_category_id']   = $this->input->post('medicine_category_id');
        $data['total_quantity']         = $this->input->post('total_quantity');
        $returned_array = null_checking($data);
        $this->db->where('medicine_id', $medicine_id);
        $this->db->update('medicine', $returned_array);
    }

    function delete_medicine_info($medicine_id)
    {
        $this->db->where('medicine_id', $medicine_id);
        $this->db->delete('medicine');
    }

    function save_medicine_category_info()
    {
        $data['name']         = $this->input->post('name');
        $data['description']    = $this->input->post('description');
        $returned_array = null_checking($data);
        $this->db->insert('medicine_category', $returned_array);
    }

    function select_medicine_category_info()
    {
        return $this->db->get('medicine_category')->result_array();
    }

    function update_medicine_category_info($medicine_category_id)
    {
        $data['name']         = $this->input->post('name');
        $data['description']     = $this->input->post('description');
        $returned_array = null_checking($data);
        $this->db->where('medicine_category_id', $medicine_category_id);
        $this->db->update('medicine_category', $returned_array);
    }

    function delete_medicine_category_info($medicine_category_id)
    {
        $this->db->where('medicine_category_id', $medicine_category_id);
        $this->db->delete('medicine_category');
    }
    // FIXME Doctor list patients by doctor id
    function select_patient_info_by_doctor_id()
    {
        $doctor_id = $this->session->userdata('login_user_id');
        return $this->db->get_where('invoice', array(
            'hr_id' => $doctor_id, 'status' => 'paid'
        ))->result_array();
    }

    function select_prescription_info_by_doctor_id()
    {
        $doctor_id = $this->session->userdata('login_user_id');
        return $this->db->get_where('prescription', array('doctor_id' => $doctor_id))->result_array();
    }

    function select_medication_history($patient_id = '')
    {
        return $this->db->get_where('prescription', array('patient_id' => $patient_id))->result_array();
    }

    function select_prescription_info_by_patient_id()
    {
        $patient_id = $this->session->userdata('login_user_id');
        return $this->db->get_where('prescription', array('patient_id' => $patient_id))->result_array();
    }

    function update_prescription_info($prescription_id)
    {
        $data['timestamp']      = strtotime($this->input->post('date_timestamp') . ' ' . $this->input->post('time_timestamp'));
        $data['patient_id']     = $this->input->post('patient_id');
        $data['case_history']   = $this->input->post('case_history');
        $data['medication']     = $this->input->post('medication');
        $data['note']           = $this->input->post('note');
        $data['doctor_id']      = $this->session->userdata('login_user_id');
        $returned_array = null_checking($data);
        $this->db->where('prescription_id', $prescription_id);
        $this->db->update('prescription', $returned_array);
    }

    function delete_prescription_info($prescription_id)
    {
        $this->db->where('prescription_id', $prescription_id);
        $this->db->delete('prescription');
    }

    function save_diagnosis_report_info()
    {
        $data['timestamp']          = strtotime($this->input->post('date_timestamp') . ' ' . $this->input->post('time_timestamp'));
        $data['report_type']        = $this->input->post('report_type');
        $data['file_name']          = $_FILES["file_name"]["name"];
        $data['document_type']      = $this->input->post('document_type');
        $data['description']        = $this->input->post('description');
        $data['prescription_id']    = $this->input->post('prescription_id');

        $this->db->insert('diagnosis_report', $data);

        $diagnosis_report_id        = $this->db->insert_id();
        move_uploaded_file($_FILES["file_name"]["tmp_name"], 'uploads/diagnosis_report/' . $_FILES["file_name"]["name"]);
    }

    function select_diagnosis_report_info()
    {
        return $this->db->get('diagnosis_report')->result_array();
    }

    function delete_diagnosis_report_info($diagnosis_report_id)
    {
        $this->db->where('diagnosis_report_id', $diagnosis_report_id);
        $this->db->delete('diagnosis_report');
    }

    function save_notice_info()
    {
        $data['title']              = $this->input->post('title');
        $data['description']        = $this->input->post('description');
        if ($this->input->post('start_timestamp') != '')
            $data['start_timestamp']    = strtotime($this->input->post('start_timestamp'));
        else
            $data['start_timestamp']    = '';
        if ($this->input->post('end_timestamp') != '')
            $data['end_timestamp']      = strtotime($this->input->post('end_timestamp'));
        else
            $data['end_timestamp']      = $data['start_timestamp'];

        $returned_array = null_checking($data);
        $this->db->insert('notice', $returned_array);
    }

    function select_notice_info()
    {
        return $this->db->get('notice')->result_array();
    }

    function update_notice_info($notice_id)
    {
        $data['title']              = $this->input->post('title');
        $data['description']        = $this->input->post('description');
        if ($this->input->post('start_timestamp') != '')
            $data['start_timestamp']    = strtotime($this->input->post('start_timestamp'));
        else
            $data['start_timestamp']    = '';
        if ($this->input->post('end_timestamp') != '')
            $data['end_timestamp']      = strtotime($this->input->post('end_timestamp'));
        else
            $data['end_timestamp']      = $data['start_timestamp'];

        $returned_array = null_checking($data);
        $this->db->where('notice_id', $notice_id);
        $this->db->update('notice', $returned_array);
    }

    function delete_notice_info($notice_id)
    {
        $this->db->where('notice_id', $notice_id);
        $this->db->delete('notice');
    }

    // TODO notification
    function send_notification()
    {
    }
    ////////private message//////
    function send_new_private_message()
    {
        $message    = $this->input->post('message');
        $timestamp  = strtotime(date("Y-m-d H:i:s"));

        $reciever   = $this->input->post('reciever');
        $sender     = $this->session->userdata('login_type') . '-' . $this->session->userdata('login_user_id');

        //check if the thread between those 2 users exists, if not create new thread
        $num1 = $this->db->get_where('message_thread', array('sender' => $sender, 'reciever' => $reciever))->num_rows();
        $num2 = $this->db->get_where('message_thread', array('sender' => $reciever, 'reciever' => $sender))->num_rows();
        if ($num1 == 0 && $num2 == 0) {
            $message_thread_code                        = substr(md5(rand(100000000, 20000000000)), 0, 15);
            $data_message_thread['message_thread_code'] = $message_thread_code;
            $data_message_thread['sender']              = $sender;
            $data_message_thread['reciever']            = $reciever;
            $this->db->insert('message_thread', $data_message_thread);
        }
        if ($num1 > 0)
            $message_thread_code = $this->db->get_where('message_thread', array('sender' => $sender, 'reciever' => $reciever))->row()->message_thread_code;
        if ($num2 > 0)
            $message_thread_code = $this->db->get_where('message_thread', array('sender' => $reciever, 'reciever' => $sender))->row()->message_thread_code;


        $data_message['message_thread_code']    = $message_thread_code;
        $data_message['message']                = $message;
        $data_message['sender']                 = $sender;
        $data_message['timestamp']              = $timestamp;
        $this->db->insert('message', $data_message);

        return $message_thread_code;
    }

    function send_reply_message($message_thread_code)
    {
        $message    = $this->input->post('message');
        $timestamp  = strtotime(date("Y-m-d H:i:s"));
        $sender     = $this->session->userdata('login_type') . '-' . $this->session->userdata('login_user_id');

        $data_message['message_thread_code']    = $message_thread_code;
        $data_message['message']                = $message;
        $data_message['sender']                 = $sender;
        $data_message['timestamp']              = $timestamp;
        $this->db->insert('message', $data_message);
    }

    function mark_thread_messages_read($message_thread_code)
    {
        // mark read only the oponnent messages of this thread, not currently logged in user's sent messages
        $current_user = $this->session->userdata('login_type') . '-' . $this->session->userdata('login_user_id');
        $this->db->where('sender !=', $current_user);
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->update('message', array('read_status' => 1));
    }

    function count_unread_message_of_thread($message_thread_code)
    {
        $unread_message_counter = 0;
        $current_user = $this->session->userdata('login_type') . '-' . $this->session->userdata('login_user_id');
        $messages = $this->db->get_where('message', array('message_thread_code' => $message_thread_code))->result_array();
        foreach ($messages as $row) {
            if ($row['sender'] != $current_user && $row['read_status'] == '0')
                $unread_message_counter++;
        }
        return $unread_message_counter;
    }

    // TODO CREATE MEDICINE SALE
    function create_medicine_sale()
    {
        $data['patient_id']     = $this->input->post('patient_id');
        $data['total_amount']   = $this->input->post('total_amount');
        $medicines              = array();
        $medicine_ids           = $this->input->post('medicine_id');
        $medicine_quantities    = $this->input->post('medicine_quantity');
        $number_of_entries      = sizeof($medicine_ids);

        for ($i = 0; $i < $number_of_entries; $i++) {
            if ($medicine_ids[$i] != "" && $medicine_quantities[$i] != "") {
                $new_entry = array('medicine_id' => $medicine_ids[$i], 'quantity' => $medicine_quantities[$i]);
                array_push($medicines, $new_entry);

                // UPDATE MEDICINE INVENTORY
                $sold_quantity = $this->db->get_where('medicine', array('medicine_id' => $medicine_ids[$i]))->row()->sold_quantity;

                $data2['sold_quantity'] = $sold_quantity + $medicine_quantities[$i];

                $this->db->update('medicine', $data2, array('medicine_id' => $medicine_ids[$i]));
            }
        }
        $data['medicines']     = json_encode($medicines);
        $returned_array = null_checking($data);
        $this->db->insert('medicine_sale', $returned_array);
    }

    //upload pathology_report
    function save_pathology_report($param1 = "")
    {
        if (!file_exists('uploads/pathology_reports/')) {
            $oldmask = umask(0);  // helpful when used in linux server
            mkdir('uploads/pathology_reports/', 0777);
        }

        $data['code']       = substr(md5(rand(0, 1000000000)), 0, 10);
        $data['patient_id'] = $param1;
        $data['test_name']  = $this->input->post('pathology_test_name');
        $file_name = $_FILES['pathology_report']['name'];
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $modified_file_name = $data['code'] . '.' . $extension;
        move_uploaded_file($_FILES['pathology_report']['tmp_name'], 'uploads/pathology_reports/' . $modified_file_name);
        $data['pathology_report'] = $modified_file_name;
        $this->db->insert('pathology_report', $data);
    }
}
