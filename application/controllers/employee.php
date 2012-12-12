<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Employee controller
 *
 * @author ashpakov
 */
class Employee extends CI_Controller {

    function __construct() {
        parent::__construct();
        $config['base_url'] = site_url('employee/index/');
    }

    function index(){
        $this->load->model('Person_model');
        $this->load->model('Employee_model');
        $employees = $this->Employee_model->get_data();

        // generate table data
        $this->load->library('table');
        $this->table->set_empty("&nbsp;");
        $this->table->set_heading('ID', 'Person ID', 'Hire Date', 'Term Date', 
                                    'Update Date', 'Updated By', 'Actions');
        foreach ($employees as $employee) {
            $this->table->add_row($employee->employee_id, $this->Person_model->get_name_by_id($employee->person_id), 
                        $employee->hire_date, $employee->term_date, 
                        $employee->update_dt_tm, 
                        ($employee->update_user_id != 0) ? $this->Person_model->get_name_by_id($employee->update_user_id) : 0,
                    anchor('employee/update/' . $employee->employee_id, 'update', 
                        array('class' => 'update')) . '  ' .
                    anchor('employee/delete/' . $employee->employee_id, 'delete', 
                        array('class' => 'delete', 'onclick' =>"return confirm('Are you sure want to delete this employee?')"))
            );
        }
        $db_data['employee_table'] = $this->table->generate();

        // load view
        $this->load->view('employee_view', $db_data);
    }

    function add() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        // get Persons list
        $this->load->model('Person_model');
        $persons = $this->Person_model->get_data();
        
        // create Person array
        $person_array = array();
        foreach($persons as $person)
            $person_array[$person->person_id] = "{$person->first_name} {$person->last_name}";
        
        // reset common properties
        $datetime = date('Y-m-d H:i:s');

        $data['title'] = 'Add new Employee';
        $data['action'] = site_url('employee/add');
        $data['message'] = validation_errors();
        $data['employee_id'] = '';
        $data['dd_person'] = form_dropdown('person_id', $person_array);
        $data['hire_date'] = '';
        $data['term_date'] = '';
        $data['update_dt_tm'] = $datetime;
        $data['update_user_id'] = 0;
        $data['link_back'] = anchor('employee/index/', 
            'Back to the Employee Table', array('class' => 'back'));

        // run validation
        $this->form_validation->set_rules('employee_id', 'Employee ID');
        $this->form_validation->set_rules('person_id', 'Person ID');
        $this->form_validation->set_rules('hire_date', 'Hire Date');
        $this->form_validation->set_rules('term_date', 'Term Date');
        $this->form_validation->set_rules('update_dt_tm', 'Updated Date');
        $this->form_validation->set_rules('update_user_id', 'Updating User');

        // show errors
        $this->form_validation->set_message('required', '* required');
        $this->form_validation->set_message('isset', '* required');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            // load view again
            $data['message'] = validation_errors();
            $this->load->view('employee_edit', $data);
        } else {
            // save data to DB
            $employee = array('person_id' => $this->input->post('person_id'),
                                     'person_id' => $this->input->post('person_id'),
                                     'hire_date' => $this->input->post('hire_date'),
                                     'term_date' => $this->input->post('term_date'),
                                     'update_user_id' => $this->input->post('update_user_id'),
                                     );

            $this->load->model('Employee_model');
            $this->Employee_model->save($employee);
            // set user message
            $data['message'] = '<div class="success">New employee was successfuly added!</div>';
            $this->load->view('employee_edit', $data);
        }
    }

    function delete($id) {
        $this->load->model('Employee_model');
        $this->Employee_model->delete($id);

        // redirect to the main view
        redirect('employee/index/', 'refresh');
    }

    function update($id) {
        // load librares
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        // get Persons list
        $this->load->model('Person_model');
        $persons = $this->Person_model->get_data();
        
        // create Person array
        $person_array = array();
        foreach($persons as $person)
            $person_array[$person->person_id] = "{$person->first_name} {$person->last_name}";

        // get values for form population
        $this->load->model('Employee_model');
        $employee = $this->Employee_model->get_by_id($id);

        // set common properties
        $data['title'] = 'Update Employee';
        $data['message'] = '';
        $data['employee_id'] = $employee->employee_id;
        $data['dd_person'] = form_dropdown('person_id', $person_array, $employee->person_id);
        $data['hire_date'] = $employee->hire_date;
        $data['term_date'] = $employee->term_date;
        $data['update_dt_tm'] = $employee->update_dt_tm;
        $data['update_user_id'] = ($employee->update_user_id != 0) ? $this->Person_model->get_name_by_id($employee->update_user_id) : 0;
            $upd = $employee->update_user_id;
        $data['action'] = site_url('employee/update/' . $id);
        $data['link_back'] = anchor('employee/index/', 'Back to the Employee Table', array('class' => 'back'));

        // run validation
        $this->form_validation->set_rules('employee_id', 'Report ID');
        $this->form_validation->set_rules('person_id', 'Person ID');
        $this->form_validation->set_rules('hire_date', 'Request Date');
        $this->form_validation->set_rules('term_date', 'Receive Date');
        $this->form_validation->set_rules('update_dt_tm', 'Updated Date');
        $this->form_validation->set_rules('update_user_id', 'Updating User');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            // load view again
            $data['message'] = validation_errors();
            $this->load->view('employee_edit', $data);
        } else {
            // save data to DB
            $employee = array('person_id' => $this->input->post('person_id'),
                                     'person_id' => $this->input->post('person_id'),
                                     'hire_date' => $this->input->post('hire_date'),
                                     'term_date' => $this->input->post('term_date'),
//                                     'update_user_id' => $this->input->post('update_user_id'),
                                     'update_user_id' => $upd
                                     );
            $this->Employee_model->update($id, $employee);

// set user message
            $data['message'] = '<div class="success">' .
                    'Selected Employee was successfuly updated!</div>';
            $this->load->view('employee_edit', $data);
        }
    }

}
