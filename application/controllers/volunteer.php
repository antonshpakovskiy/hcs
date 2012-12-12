<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Volunteer controller
 *
 * @author ashpakov
 */
class Volunteer extends CI_Controller {

    function __construct() {
        parent::__construct();
        $config['base_url'] = site_url('volunteer/index/');
    }

    function index(){
        $this->load->model('Person_model');
        $this->load->model('Volunteer_model');
        $volunteers = $this->Volunteer_model->get_data();

        // generate table data
        $this->load->library('table');
        $this->table->set_empty("&nbsp;");
        $this->table->set_heading('ID', 'Person ID', 'Start Date', 'End Date', 'Update Date', 'Updated By', 'Actions');
        foreach ($volunteers as $volunteer) {
            $this->table->add_row($volunteer->volunteer_id, $this->Person_model->get_name_by_id($volunteer->person_id), 
                        $volunteer->start_date, $volunteer->end_date, 
                        $volunteer->update_dt_tm, 
                        ($volunteer->update_user_id != 0) ? $this->Person_model->get_name_by_id($volunteer->update_user_id) : 0,
                    anchor('volunteer/update/' . $volunteer->volunteer_id, 'update', 
                        array('class' => 'update')) . '  ' .
                    anchor('volunteer/delete/' . $volunteer->volunteer_id, 'delete', 
                        array('class' => 'delete', 'onclick' =>"return confirm('Are you sure want to delete this volunteer?')"))
            );
        }
        $db_data['volunteer_table'] = $this->table->generate();

        // load view
        $this->load->view('volunteer_view', $db_data);
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

        $data['title'] = 'Add new Volunteer';
        $data['action'] = site_url('volunteer/add');
        $data['message'] = validation_errors();
        $data['volunteer_id'] = '';
        $data['dd_person'] = form_dropdown('person_id', $person_array);
        $data['start_date'] = '';
        $data['end_date'] = '';
        $data['update_dt_tm'] = $datetime;
        $data['update_user_id'] = 0;
        $data['link_back'] = anchor('volunteer/index/', 
            'Back to the Volunteer Table', array('class' => 'back'));

        // run validation
        $this->form_validation->set_rules('volunteer_id', 'Employee ID');
        $this->form_validation->set_rules('person_id', 'Person ID');
        $this->form_validation->set_rules('start_date', 'Start Date');
        $this->form_validation->set_rules('end_date', 'End Date');
        $this->form_validation->set_rules('update_dt_tm', 'Updated Date');
        $this->form_validation->set_rules('update_user_id', 'Updating User');

        // show errors
        $this->form_validation->set_message('required', '* required');
        $this->form_validation->set_message('isset', '* required');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            // load view again
            $data['message'] = validation_errors();
            $this->load->view('volunteer_edit', $data);
        } else {
            // save data to DB
            $volunteer = array('person_id' => $this->input->post('person_id'),
                                     'start_date' => $this->input->post('start_date'),
                                     'end_date' => $this->input->post('end_date'),
                                     'update_user_id' => $this->input->post('update_user_id'),
                                     );

            $this->load->model('Volunteer_model');
            $this->Volunteer_model->save($volunteer);
            // set user message
            $data['message'] = '<div class="success">New volunteer was successfuly added!</div>';
            $this->load->view('volunteer_edit', $data);
        }
    }

    function delete($id) {
        $this->load->model('Volunteer_model');
        $this->Volunteer_model->delete($id);

        // redirect to the main view
        redirect('volunteer/index/', 'refresh');
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
        $this->load->model('Volunteer_model');
        $volunteer = $this->Volunteer_model->get_by_id($id);

        // set common properties
        $data['title'] = 'Update Volunteer';
        $data['message'] = '';
        $data['volunteer_id'] = $volunteer->volunteer_id;
        $data['dd_person'] = form_dropdown('person_id', $person_array, $volunteer->person_id);
        $data['start_date'] = $volunteer->start_date;
        $data['end_date'] = $volunteer->end_date;
        $data['update_dt_tm'] = $volunteer->update_dt_tm;
        $data['update_user_id'] = ($volunteer->update_user_id != 0) ? $this->Person_model->get_name_by_id($volunteer->update_user_id) : 0;
            $upd = $volunteer->update_user_id;
        $data['action'] = site_url('volunteer/update/' . $id);
        $data['link_back'] = anchor('volunteer/index/', 'Back to the Volunteer Table', array('class' => 'back'));

        // run validation
        $this->form_validation->set_rules('volunteer_id', 'Volunteer ID');
        $this->form_validation->set_rules('person_id', 'Person ID');
        $this->form_validation->set_rules('start_date', 'Start Date');
        $this->form_validation->set_rules('end_date', 'End Date');
        $this->form_validation->set_rules('update_dt_tm', 'Updated Date');
        $this->form_validation->set_rules('update_user_id', 'Updating User');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            // load view again
            $data['message'] = validation_errors();
            $this->load->view('volunteer_edit', $data);
        } else {
            // save data to DB
            $volunteer = array('volunteer_id' => $this->input->post('volunteer_id'),
                                     'person_id' => $this->input->post('person_id'),
                                     'start_date' => $this->input->post('start_date'),
                                     'end_date' => $this->input->post('end_date'),
//                                     'update_user_id' => $this->input->post('update_user_id'),
                                     'update_user_id' => $upd
                                     );
            $this->Volunteer_model->update($id, $volunteer);

// set user message
            $data['message'] = '<div class="success">' .
                    'Selected Volunteer was successfuly updated!</div>';
            $this->load->view('volunteer_edit', $data);
        }
    }

}
