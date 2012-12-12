<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Person controller
 *
 * @author ashpakov
 */
class Person extends CI_Controller {

    function __construct() {
        parent::__construct();
        $config['base_url'] = site_url('person/index/');
    }
    
    function get_state(){
        $state_values = array('AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California",
                'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida",
                'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa",
                'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts",
                'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska",
                'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",
                'NC'=>"North Carolina", 'ND'=>"North Dakota", 'OH'=>"Ohio", 'OK'=>"Oklahoma", 'OR'=>"Oregon",
                'PA'=>"Pennsylvania", 'RI'=>"Rhode Island", 'SC'=>"South Carolina", 'SD'=>"South Dakota", 'TN'=>"Tennessee",
                'TX'=>"Texas", 'UT'=>"Utah", 'VT'=>"Vermont", 'VA'=>"Virginia", 'WA'=>"Washington", 'WV'=>"West Virginia",
                'WI'=>"Wisconsin", 'WY'=>"Wyoming");
        return $state_values;
    }
    
    function get_yesno(){
        $choce = array('Y'=>"Yes", 'N'=>"No");
        return $choce;
    }
    
    function get_gender(){
        $gender = array('M'=>"Male", 'F'=>"Female");
        return $gender;
    }
    
    function get_carrier(){
        $carrier = array();
        $this->load->model('Mobilecarrier_model');
        $carriers = $this->Mobilecarrier_model->get_data();
        foreach($carriers as $operator)
            $carrier[$operator->carrier_id] = $operator->text_address;
        return $carrier;
    }

    function index(){
        $this->load->model('Person_model');
        $persons = $this->Person_model->get_data();

        // generate table data
        $this->load->library('table');
        $this->table->set_empty("&nbsp;");
        $this->table->set_heading('ID', 'Active', 'First Name', 'Last Name', 'Middle Name', 'Preferred Name', 'DOB',
                                    'Gender', 'Address 1', 'Address 2', 'City', 'State', 'ZIP Code', 'Home Phone',
                                    'Mobile Phone', 'SMS', 'SMS Address', 'E-mail',
                                    'Update Date', 'Updated By', 'Actions');
        foreach ($persons as $person) {
            $this->table->add_row($person->person_id, $person->is_active, $person->first_name, $person->last_name, 
                        $person->middle_name, $person->preferred_name, $person->date_of_birth, $person->gender,
                        $person->address_1, $person->address_2, $person->city, $person->state, $person->postal_code,
                        $person->home_phone, $person->mobile_phone, $person->text_ok, $person->mobile_carrier_id,
                        $person->email_address, $person->update_dt_tm, 
                        ($person->update_user_id != 0) ? $this->Person_model->get_name_by_id($person->update_user_id) : 0,
                    anchor('person/update/'.$person->person_id, 'update', 
                        array('class' => 'update')).'  '.
                    anchor('person/delete/'.$person->person_id, 'delete', 
                        array('class' => 'delete', 'onclick' =>"return confirm('Are you sure want to delete this person?')"))
            );
        }
        $db_data['person_table'] = $this->table->generate();

        // load view
        $this->load->view('person_view', $db_data);
    }

    function add() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        // reset common properties
        $datetime = date('Y-m-d H:i:s');

        $data['title'] = 'Add new Person';
        $data['action'] = site_url('person/add');
        $data['message'] = validation_errors();
        $data['person_id'] = '';
        $data['first_name'] = '';
        $data['last_name'] = '';
        $data['middle_name'] = '';
        $data['preferred_name'] = '';
        $data['date_of_birth'] = '';
        $data['gender'] = form_dropdown('gender', $this->get_gender());
        $data['address_1'] = '';
        $data['address_2'] = '';
        $data['city'] = '';
        $data['state'] = form_dropdown('state', $this->get_state());
        $data['postal_code'] = '';
        $data['home_phone'] = '';
        $data['mobile_phone'] = '';
        $data['text_ok'] = form_dropdown('text_ok', $this->get_yesno(), 'N');
        $data['mobile_carrier_id'] = form_dropdown('mobile_carrier_id', $this->get_carrier());
        $data['email_address'] = '';
        $data['is_active'] = form_dropdown('is_active', $this->get_yesno(), 'N');
        $data['update_dt_tm'] = $datetime;
        $data['update_user_id'] = 0;
        $data['link_back'] = anchor('person/index/', 
            'Back to the Person Table', array('class' => 'back'));

        // run validation
        $this->form_validation->set_rules('person_id', 'Person ID');
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|min_length[3]|max_length[30]|alpha|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|min_length[3]|max_length[30]|alpha|required');
        $this->form_validation->set_rules('middle_name', 'Middle Name', 'trim|max_length[30]|alpha');
        $this->form_validation->set_rules('preferred_name', 'Preferred Name', 'trim|max_length[30]|alpha');
        $this->form_validation->set_rules('date_of_birth', 'DOB');
        $this->form_validation->set_rules('gender', 'Gender', 'matches["M"|"F"]');
        $this->form_validation->set_rules('address_1', 'Address 1', 'trim|max_length[50]');
        $this->form_validation->set_rules('address_2', 'Address 2', 'trim|max_length[30]');
        $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]');
        $this->form_validation->set_rules('state', 'State');
        $this->form_validation->set_rules('postal_code', 'ZIP Code', 'max_length[10]');
        $this->form_validation->set_rules('home_phone', 'Home Phone', 'max_length[10]');
        $this->form_validation->set_rules('mobile_phone', 'Modile Phone', 'max_length[10]');
        $this->form_validation->set_rules('text_ok', 'SMS', 'matches["Y"|"N"]');
        $this->form_validation->set_rules('mobile_carrier_id', 'Mobile Carrier');
        $this->form_validation->set_rules('email_address', 'E-mail', 'valid_email');
        $this->form_validation->set_rules('is_active', 'Active', 'matches["Y"|"N"]');
        $this->form_validation->set_rules('update_dt_tm', 'Updated Date');
        $this->form_validation->set_rules('update_user_id', 'Updating User');

        // show errors
        $this->form_validation->set_message('required', '* required');
        $this->form_validation->set_message('isset', '* required');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            // load view again
            $data['message'] = validation_errors();
            $this->load->view('person_edit', $data);
        } else {
            // save data to DB
            $person = array('first_name' => $this->input->post('first_name'),
                                     'last_name' => $this->input->post('last_name'),
                                     'middle_name' => $this->input->post('middle_name'),
                                     'preferred_name' => $this->input->post('preferred_name'),
                                     'date_of_birth' => $this->input->post('date_of_birth'),
                                     'gender' => $this->input->post('gender'),
                                     'address_1' => $this->input->post('address_1'),
                                     'address_2' => $this->input->post('address_2'),
                                     'city' => $this->input->post('city'),
                                     'state' => $this->input->post('state'),
                                     'postal_code' => $this->input->post('postal_code'),
                                     'home_phone' => $this->input->post('home_phone'),
                                     'mobile_phone' => $this->input->post('mobile_phone'),
                                     'text_ok' => $this->input->post('text_ok'),
                                     'mobile_carrier_id' => $this->input->post('mobile_carrier_id'),
                                     'email_address' => $this->input->post('email_address'),
                                     'is_active' => $this->input->post('is_active'),
                                     'update_user_id' => $this->input->post('update_user_id')
                                     );

            $this->load->model('Person_model');
            $this->Person_model->save($person);
            // set user message
            $data['message'] = '<div class="success">New person was successfuly added!</div>';
            $this->load->view('person_edit', $data);
        }
    }

    function delete($id) {
        $this->load->model('Person_model');
        $this->Person_model->delete($id);

        // redirect to the main view
        redirect('person/index/', 'refresh');
    }

    function update($id) {
        // load librares
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        // get Persons model
        $this->load->model('Person_model');

        // get values for form population
        $this->load->model('Employee_model');
        $person = $this->Employee_model->get_by_id($id);

        // set common properties
        $data['title'] = 'Update Employee';
        $data['message'] = '';
        $data['person_id'] = $person->person_id;
        $data['first_name'] = $person->first_name;
        $data['last_name'] = $person->last_name;
        $data['middle_name'] = $person->middle_name;
        $data['preferred_name'] = $person->preferred_name;
        $data['date_of_birth'] = $person->date_of_birth;
        $data['gender'] = form_dropdown('gender', $this->get_gender(), $person->gender);
        $data['address_1'] = $person->address_1;
        $data['address_2'] = $person->address_2;
        $data['city'] = $person->city;
        $data['state'] = form_dropdown('state', $this->get_state(), $person->state);
        $data['postal_code'] = $person->postal_code;
        $data['home_phone'] = $person->home_phone;
        $data['mobile_phone'] = $person->mobile_phone;
        $data['text_ok'] = form_dropdown('text_ok', $this->get_yesno(), $person->text_ok);
        $data['mobile_carrier_id'] = form_dropdown('mobile_carrier_id', $this->get_carrier(), $person->mobile_carrier_id);
        $data['email_address'] = $person->email_address;
        $data['is_active'] = form_dropdown('is_active', $this->get_yesno(), $person->is_active);
        $data['update_dt_tm'] = $person->update_dt_tm;
        $data['update_user_id'] = ($person->update_user_id != 0) ? $this->Person_model->get_name_by_id($person->update_user_id) : 0;
            $upd = $person->update_user_id;
        $data['action'] = site_url('person/update/'.$id);
        $data['link_back'] = anchor('person/index/', 'Back to the Person Table', array('class' => 'back'));

        // run validation
        $this->form_validation->set_rules('person_id', 'Person ID');
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|min_length[3]|max_length[30]|alpha|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|min_length[3]|max_length[30]|alpha|required');
        $this->form_validation->set_rules('middle_name', 'Middle Name', 'trim|max_length[30]|alpha');
        $this->form_validation->set_rules('preferred_name', 'Preferred Name', 'trim|max_length[30]|alpha');
        $this->form_validation->set_rules('date_of_birth', 'DOB');
        $this->form_validation->set_rules('gender', 'Gender', 'matches["M"|"F"]');
        $this->form_validation->set_rules('address_1', 'Address 1', 'trim|max_length[50]');
        $this->form_validation->set_rules('address_2', 'Address 2', 'trim|max_length[30]');
        $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]');
        $this->form_validation->set_rules('state', 'State');
        $this->form_validation->set_rules('postal_code', 'ZIP Code', 'max_length[10]');
        $this->form_validation->set_rules('home_phone', 'Home Phone', 'max_length[10]');
        $this->form_validation->set_rules('mobile_phone', 'Modile Phone', 'max_length[10]');
        $this->form_validation->set_rules('text_ok', 'SMS', 'matches["Y"|"N"]');
        $this->form_validation->set_rules('mobile_carrier_id', 'Mobile Carrier');
        $this->form_validation->set_rules('email_address', 'E-mail', 'valid_email');
        $this->form_validation->set_rules('is_active', 'Active', 'matches["Y"|"N"]');
        $this->form_validation->set_rules('update_dt_tm', 'Updated Date');
        $this->form_validation->set_rules('update_user_id', 'Updating User');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            // load view again
            $data['message'] = validation_errors();
            $this->load->view('person_edit', $data);
        } else {
            // save data to DB
            $person = array('first_name' => $this->input->post('first_name'),
                            'last_name' => $this->input->post('last_name'),
                            'middle_name' => $this->input->post('middle_name'),
                            'preferred_name' => $this->input->post('preferred_name'),
                            'date_of_birth' => $this->input->post('date_of_birth'),
                            'gender' => $this->input->post('gender'),
                            'address_1' => $this->input->post('address_1'),
                            'address_2' => $this->input->post('address_2'),
                            'city' => $this->input->post('city'),
                            'state' => $this->input->post('state'),
                            'postal_code' => $this->input->post('postal_code'),
                            'home_phone' => $this->input->post('home_phone'),
                            'mobile_phone' => $this->input->post('mobile_phone'),
                            'text_ok' => $this->input->post('text_ok'),
                            'mobile_carrier_id' => $this->input->post('mobile_carrier_id'),
                            'email_address' => $this->input->post('email_address'),
                            'is_active' => $this->input->post('is_active'),
                            'update_user_id' => $upd
                            );
            $this->Person_model->update($id, $person);

// set user message
            $data['message'] = '<div class="success">' .
                    'Selected Person was successfuly updated!</div>';
            $this->load->view('person_edit', $data);
        }
    }

}
