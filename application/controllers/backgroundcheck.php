<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Backgroundcheck controller
 *
 * @author ashpakov
 */
class Backgroundcheck extends CI_Controller {

    function __construct() {
        parent::__construct();
        $config['base_url'] = site_url('backgroundcheck/index/');
    }

    function index(){
        $this->load->model('Person_model');
        $this->load->model('Backgroundcheck_model');
        $backgroundchecks = $this->Backgroundcheck_model->get_data();

        // generate table data
        $this->load->library('table');
        $this->table->set_empty("&nbsp;");
        $this->table->set_heading('ID', 'Person ID', 'Request Date', 'Receive Date', 'Passed', 
                                    'Required', 'Update Date', 'Updated By', 'Actions');
        foreach ($backgroundchecks as $backgroundcheck) {
            $this->table->add_row($backgroundcheck->bg_check_id, $this->Person_model->get_name_by_id($backgroundcheck->person_id), 
                        $backgroundcheck->request_date, $backgroundcheck->receive_date, 
                        $backgroundcheck->check_passed, $backgroundcheck->required, 
                        $backgroundcheck->update_dt_tm, 
                        ($backgroundcheck->update_user_id != 0) ? $this->Person_model->get_name_by_id($backgroundcheck->update_user_id) : 0,
                    anchor('backgroundcheck/update/' . $backgroundcheck->bg_check_id, 'update', 
                        array('class' => 'update')) . '  ' .
                    anchor('backgroundcheck/delete/' . $backgroundcheck->bg_check_id, 'delete', 
                        array('class' => 'delete', 'onclick' =>"return confirm('Are you sure want to delete this report?')"))
            );
        }
        $db_data['report_table'] = $this->table->generate();

        // load view
        $this->load->view('report_view', $db_data);
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

        // create yes/no array
        $yesno_array = array('Y' => 'Yes', 'N' => 'No');
        
        // reset common properties
        $datetime = date('Y-m-d H:i:s');

        $data['title'] = 'Add new Background Report';
        $data['action'] = site_url('backgroundcheck/add');
        $data['message'] = validation_errors();
        $data['bg_check_id'] = '';
        $data['dd_person'] = form_dropdown('person_id', $person_array);
        $data['request_date'] = '';
        $data['receive_date'] = '';
        $data['check_passed'] = form_dropdown('check_passed', $yesno_array, 'N');
        $data['required'] = form_dropdown('required', $yesno_array, 'N');
        $data['update_dt_tm'] = $datetime;
        $data['update_user_id'] = 0;
        $data['link_back'] = anchor('backgroundcheck/index/', 
            'Back to the Background check reports Table', array('class' => 'back'));

        // run validation
        $this->form_validation->set_rules('bg_check_id', 'Report ID');
        $this->form_validation->set_rules('person_id', 'Person ID');
        $this->form_validation->set_rules('request_date', 'Request Date');
        $this->form_validation->set_rules('receive_date', 'Receive Date');
        $this->form_validation->set_rules('check_passed', 'Passed', 'matches["Y"|"N"]');
        $this->form_validation->set_rules('required', 'Required', 'matches["Y"|"N"]');
        $this->form_validation->set_rules('update_dt_tm', 'Updated Date');
        $this->form_validation->set_rules('update_user_id', 'Updating User');

        // show errors
        $this->form_validation->set_message('required', '* required');
        $this->form_validation->set_message('matches', 'Y or N allowed only');
        $this->form_validation->set_message('isset', '* required');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            // load view again
            $data['message'] = validation_errors();
            $this->load->view('report_edit', $data);
        } else {
            // save data to DB
            $backgroundcheck = array('bg_check_id' => $this->input->post('bg_check_id'),
                                     'person_id' => $this->input->post('person_id'),
                                     'request_date' => $this->input->post('request_date'),
                                     'receive_date' => $this->input->post('receive_date'),
                                     'check_passed' => $this->input->post('check_passed'),
                                     'required' => $this->input->post('required'),
                                     'update_user_id' => $this->input->post('update_user_id'),
                                     );

            $this->load->model('Backgroundcheck_model');
            $this->Backgroundcheck_model->save($backgroundcheck);
            // set user message
            $data['message'] = '<div class="success">New backgroundcheck was successfuly added!</div>';
            $this->load->view('report_edit', $data);
        }
    }

    function delete($id) {
        $this->load->model('Backgroundcheck_model');
        $this->Backgroundcheck_model->delete($id);

        // redirect to the main view
        redirect('backgroundcheck/index/', 'refresh');
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

        // create yes/no array
        $yesno_array = array('Y' => 'Yes', 'N' => 'No');

        // get values for form population
        $this->load->model('Backgroundcheck_model');
        $backgroundcheck = $this->Backgroundcheck_model->get_by_id($id);

        // set common properties
        
        $data['title'] = 'Update Record';
        $data['message'] = '';
        $data['bg_check_id'] = $backgroundcheck->bg_check_id;
        $data['dd_person'] = form_dropdown('person_id', $person_array, $backgroundcheck->person_id);
        $data['request_date'] = $backgroundcheck->request_date;
        $data['receive_date'] = $backgroundcheck->receive_date;
        $data['check_passed'] = form_dropdown('check_passed', $yesno_array, $backgroundcheck->check_passed);
        $data['required'] = form_dropdown('required', $yesno_array, $backgroundcheck->required);
        $data['update_dt_tm'] = $backgroundcheck->update_dt_tm;
        $data['update_user_id'] = ($backgroundcheck->update_user_id != 0) ? $this->Person_model->get_name_by_id($backgroundcheck->update_user_id) : 0;
//        $data['update_user_id'] = $backgroundcheck->update_user_id;
        $upd = $backgroundcheck->update_user_id;
        $data['action'] = site_url('backgroundcheck/update/' . $id);
        $data['link_back'] = anchor('backgroundcheck/index/', 'Back to the Background check reports Table', array('class' => 'back'));

        // run validation
        $this->form_validation->set_rules('bg_check_id', 'Report ID');
        $this->form_validation->set_rules('person_id', 'Person ID');
        $this->form_validation->set_rules('request_date', 'Request Date');
        $this->form_validation->set_rules('receive_date', 'Receive Date');
        $this->form_validation->set_rules('check_passed', 'Passed', 'matches["Y"|"N"]');
        $this->form_validation->set_rules('required', 'Required', 'matches["Y"|"N"]');
        $this->form_validation->set_rules('update_dt_tm', 'Updated Date');
        $this->form_validation->set_rules('update_user_id', 'Updating User');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            // load view again
            $data['message'] = validation_errors();
            $this->load->view('report_edit', $data);
        } else {
            // save data to DB
            $backgroundcheck = array('person_id' => $this->input->post('person_id'),
                                     'person_id' => $this->input->post('person_id'),
                                     'request_date' => $this->input->post('request_date'),
                                     'receive_date' => $this->input->post('receive_date'),
                                     'check_passed' => $this->input->post('check_passed'),
                                     'required' => $this->input->post('required'),
//                                     'update_user_id' => $this->input->post('update_user_id'),
                                     'update_user_id' => $upd
                                     );
            $this->Backgroundcheck_model->update($id, $backgroundcheck);

// set user message
            $data['message'] = '<div class="success">' .
                    'Selected Record was successfuly updated!</div>';
            $this->load->view('report_edit', $data);
        }
    }

}
