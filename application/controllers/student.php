<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Student controller
 *
 * @author ashpakov
 */
class Student extends CI_Controller {

    function __construct() {
        parent::__construct();
        $config['base_url'] = site_url('student/index/');
    }
    
    function get_persons(){
        // get Persons list
        $this->load->model('Person_model');
        $persons = $this->Person_model->get_data();
        
        // create Person array
        $person_array = array();
        foreach($persons as $person)
            $person_array[$person->person_id] = "{$person->first_name} {$person->last_name}";
        return $person_array;
    }
    
    function get_rooms(){
        // get Rooms list
        $this->load->model('Room_model');
        $rooms = $this->Room_model->get_data();
        
        // create Room array
        $room_array = array();
        foreach($rooms as $room)
            $room_array[$room->room_id] = $room->room_number_txt;
        return $room_array;
    }
    
    function get_employees(){
        // get Employee list
        $this->load->model('Employee_model');
        $employees = $this->Employee_model->get_data();
        
        // create Employees array
        $this->load->model('Person_model');
        $employee_array = array();
        foreach($employees as $employee)
            $employee_array[$employee->employee_id] = $this->Person_model->get_name_by_id($employee->person_id);
        return $employee_array;
    }

    function index(){
        $this->load->model('Person_model');
        $this->load->model('Room_model');
        $this->load->model('Employee_model');
        
        $this->load->model('Student_model');
        $students = $this->Student_model->get_data();

        // generate table data
        $this->load->library('table');
        $this->table->set_empty("&nbsp;");
        $this->table->set_heading('ID', 'Person ID', 'Room ID', 'Teacher ID', 'Enroll Start Date', 'Enroll End Date', 
                                    'Update Date', 'Updated By', 'Actions');
        foreach ($students as $student) {
            $this->table->add_row($student->student_id, $this->Person_model->get_name_by_id($student->person_id), 
                        $this->Room_model->get_number_by_id($student->room_id), 
                        $this->Person_model->get_name_by_id($this->Employee_model->get_person_by_id($student->teacher_id)),
                        $student->enroll_start_date, $student->enroll_end_date, 
                        $student->update_dt_tm, 
                        ($student->update_user_id != 0) ? $this->Person_model->get_name_by_id($student->update_user_id) : 0,
                    anchor('student/update/' . $student->student_id, 'update', 
                        array('class' => 'update')) . '  ' .
                    anchor('student/delete/' . $student->student_id, 'delete', 
                        array('class' => 'delete', 'onclick' =>"return confirm('Are you sure want to delete this student?')"))
            );
        }
        $db_data['student_table'] = $this->table->generate();

        // load view
        $this->load->view('student_view', $db_data);
    }

    function add() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        // reset common properties
        $datetime = date('Y-m-d H:i:s');

        $data['title'] = 'Add new Student';
        $data['action'] = site_url('student/add');
        $data['message'] = validation_errors();
        $data['student_id'] = '';
        $data['person_id'] = form_dropdown('person_id', $this->get_persons());
        $data['room_id'] = form_dropdown('room_id', $this->get_rooms());
        $data['teacher_id'] = form_dropdown('teacher_id', $this->get_employees());
        $data['enroll_start_date'] = '';
        $data['enroll_end_date'] = '';
        $data['update_dt_tm'] = $datetime;
        $data['update_user_id'] = 0;
        $data['link_back'] = anchor('student/index/', 
            'Back to the Student Table', array('class' => 'back'));

        // run validation
        $this->form_validation->set_rules('student_id', 'Employee ID');
        $this->form_validation->set_rules('person_id', 'Person ID');
        $this->form_validation->set_rules('room_id', 'Room');
        $this->form_validation->set_rules('teacher_id', 'Teacher');
        $this->form_validation->set_rules('enroll_start_date', 'Start Date');
        $this->form_validation->set_rules('enroll_end_date', 'End Date');
        $this->form_validation->set_rules('update_dt_tm', 'Updated Date');
        $this->form_validation->set_rules('update_user_id', 'Updating User');

        // show errors
        $this->form_validation->set_message('required', '* required');
        $this->form_validation->set_message('isset', '* required');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            // load view again
            $data['message'] = validation_errors();
            $this->load->view('student_edit', $data);
        } else {
            // save data to DB
            $student = array('person_id' => $this->input->post('person_id'),
                                     'room_id' => $this->input->post('room_id'),
                                     'teacher_id' => $this->input->post('teacher_id'),
                                     'enroll_start_date' => $this->input->post('enroll_start_date'),
                                     'enroll_end_date' => $this->input->post('enroll_end_date'),
                                     'update_user_id' => $this->input->post('update_user_id'),
                                     );

            $this->load->model('Student_model');
            $this->Student_model->save($student);
            // set user message
            $data['message'] = '<div class="success">New student was successfuly added!</div>';
            $this->load->view('student_edit', $data);
        }
    }

    function delete($id) {
        $this->load->model('Student_model');
        $this->Student_model->delete($id);

        // redirect to the main view
        redirect('student/index/', 'refresh');
    }

    function update($id) {
        // load librares
        $this->load->helper('form');
        $this->load->library('form_validation');

        // get values for form population
        $this->load->model('Person_model');
        $this->load->model('Student_model');
        $student = $this->Student_model->get_by_id($id);

        // set common properties
        $data['title'] = 'Update Student';
        $data['message'] = '';
        $data['student_id'] = $student->student_id;
        $data['person_id'] = form_dropdown('person_id', $this->get_persons(), $student->person_id);
        $data['room_id'] = form_dropdown('room_id', $this->get_rooms(), $student->room_id);
        $data['teacher_id'] = form_dropdown('teacher_id', $this->get_employees(), $student->teacher_id);
        $data['enroll_start_date'] = $student->enroll_start_date;
        $data['enroll_end_date'] = $student->enroll_end_date;
        $data['update_dt_tm'] = $student->update_dt_tm;
        $data['update_user_id'] = ($student->update_user_id != 0) ? $this->Person_model->get_name_by_id($student->update_user_id) : 0;
            $upd = $student->update_user_id;
        $data['action'] = site_url('student/update/'.$id);
        $data['link_back'] = anchor('student/index/', 'Back to the Employee Table', array('class' => 'back'));

        // run validation
        $this->form_validation->set_rules('student_id', 'Student ID');
        $this->form_validation->set_rules('person_id', 'Person ID');
        $this->form_validation->set_rules('room_id', 'Room ID');
        $this->form_validation->set_rules('teacher_id', 'Teacher ID');
        $this->form_validation->set_rules('enroll_end_date', 'End Date');
        $this->form_validation->set_rules('enroll_start_date', 'Start Date');
        $this->form_validation->set_rules('update_dt_tm', 'Updated Date');
        $this->form_validation->set_rules('update_user_id', 'Updating User');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            // load view again
            $data['message'] = validation_errors();
            $this->load->view('student_edit', $data);
        } else {
            // save data to DB
            $student = array('person_id' => $this->input->post('person_id'),
                                     'room_id' => $this->input->post('room_id'),
                                     'teacher_id' => $this->input->post('teacher_id'),
                                     'enroll_start_date' => $this->input->post('enroll_start_date'),
                                     'enroll_end_date' => $this->input->post('enroll_end_date'),
                                     'update_user_id' => $upd
                                     );
            $this->Student_model->update($id, $student);

// set user message
            $data['message'] = '<div class="success">' .
                    'Selected Student was successfuly updated!</div>';
            $this->load->view('student_edit', $data);
        }
    }

}
