<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Facility controller
 *
 * @author ashpakov
 */
class Facility extends CI_Controller {

    function __construct() {
        parent::__construct();
        $config['base_url'] = site_url('facility/index/');
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
    
    public 

    function index() {
        $this->load->model('Person_model');
        $this->load->model('Facility_model');
        $facilitys = $this->Facility_model->get_data();

        // generate table data
        $this->load->library('table');
        $this->table->set_empty("&nbsp;");
        $this->table->set_heading('ID', 'Name', 'Address', 'City', 'State', 
                                'ZIP Code', 'Phone', 'FAX', 'Updated Date', 'Updated By', 'Actions');
        foreach ($facilitys as $facility) {
            $this->table->add_row($facility->facility_id, $facility->name, $facility->address, $facility->city, 
                                $facility->state, $facility->zip, $facility->phone, $facility->fax, 
                                $facility->update_dt_tm, 
                                ($facility->update_user_id != 0) ? $this->Person_model->get_name_by_id($facility->update_user_id) : 0, 
                    anchor('facility/update/'.$facility->facility_id, 'update', array('class' => 'update')).'  '.
                    anchor('facility/delete/'.$facility->facility_id, 'delete', array('class' => 'delete', 'onclick' =>
                        "return confirm('Are you sure want to delete this facility?')"))
            );
        }
        $lkup_data['facility_table'] = $this->table->generate();

        // load view
        $this->load->view('facility_view', $lkup_data);
    }

    function add() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        // reset common properties
        $datetime = date('Y-m-d H:i:s');

        $data['title'] = 'Add new Facility';
        $data['action'] = site_url('facility/add');
        $data['message'] = validation_errors();
        $data['facility_id'] = '';
        $data['name'] = '';
        $data['address'] = '';
        $data['city'] = '';
        $data['state'] = form_dropdown('state', $this->get_state());
        $data['zip'] = '';
        $data['phone'] = '';
        $data['fax'] = '';
        $data['update_dt_tm'] = $datetime;
        $data['update_user_id'] = '';
        $data['link_back'] = anchor('facility/index/', 'Back to the Facilities Table', array('class' => 'back'));

        // run validation
        $this->form_validation->set_rules('facility_id', 'Facility ID');
        $this->form_validation->set_rules('name', 'Facility Name', 'trim|min_length[3]|max_length[64]|required');
        $this->form_validation->set_rules('address', 'Facility Address', 'trim|min_length[3]|max_length[30]|required');
        $this->form_validation->set_rules('city', 'City', 'trim|min_length[3]|max_length[30]|required');
        $this->form_validation->set_rules('state', 'State', 'trim|exact_length[2]|required');
        $this->form_validation->set_rules('zip', 'ZIP', 'trim|min_length[5]|max_length[10]|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|exact_length[10]|numeric|required');
        $this->form_validation->set_rules('fax', 'Fax', 'trim|exact_length[10]|numeric|required');
        $this->form_validation->set_rules('update_dt_tm', 'Updated Date');
        $this->form_validation->set_rules('update_user_id', 'Updating User');

        // show errors
        $this->form_validation->set_message('required', '* required');
        $this->form_validation->set_message('isset', '* required');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            // load view again
            $data['message'] = validation_errors();
            $this->load->view('facility_edit', $data);
        } else {
            // save data to DB
            $facility = array('name' => $this->input->post('name'),
                            'address' => $this->input->post('address'),
                            'city' => $this->input->post('city'),
                            'state' => $this->input->post('state'),
                            'zip' => $this->input->post('zip'),
                            'phone' => $this->input->post('phone'),
                            'fax' => $this->input->post('fax')
                            );

            $this->load->model('Facility_model');
            $this->Facility_model->save($facility);
            // set user message
            $data['message'] = '<div class="success">New facility was successfuly added!</div>';
            $this->load->view('facility_edit', $data);
        }
    }

    function delete($id) {
        $this->load->model('Facility_model');
        $this->Facility_model->delete($id);

        // redirect to the main view
        redirect('facility/index/', 'refresh');
    }

    function update($id) {
        // load librares
        $this->load->helper('form');
        $this->load->library('form_validation');

        // get values for form population
        $this->load->model('Person_model');
        $this->load->model('Facility_model');
        $facility = $this->Facility_model->get_by_id($id);

        // set common properties
        $data['title'] = 'Update a Carrier';
        $data['message'] = '';
        $data['facility_id'] = $facility->facility_id;
        $data['name'] = $facility->name;
        $data['address'] = $facility->address;
        $data['city'] = $facility->city;
        $data['state'] = form_dropdown('state', $this->get_state(), $facility->state);
        $data['zip'] = $facility->zip;
        $data['phone'] = $facility->phone;
        $data['fax'] = $facility->fax;
        $data['update_dt_tm'] = $facility->update_dt_tm;
        $data['update_user_id'] = ($facility->update_user_id != 0) ? $this->Person_model->get_name_by_id($facility->update_user_id) : 0;
            $upd = $facility->update_user_id;
        $data['action'] = site_url('facility/update/'.$id);
        $data['link_back'] = anchor('facility/index/', 'Back to the Facilities Table', array('class' => 'back'));

        // run validation
        $this->form_validation->set_rules('facility_id', 'Facility ID');
        $this->form_validation->set_rules('name', 'Facility Name', 'trim|min_length[3]|max_length[64]|required');
        $this->form_validation->set_rules('address', 'Facility Address', 'trim|min_length[3]|max_length[30]|required');
        $this->form_validation->set_rules('city', 'City', 'trim|min_length[3]|max_length[30]|required');
        $this->form_validation->set_rules('state', 'State', 'trim|exact_length[2]|required');
        $this->form_validation->set_rules('zip', 'ZIP', 'trim|min_length[5]|max_length[10]|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|exact_length[10]|numeric|required');
        $this->form_validation->set_rules('fax', 'Fax', 'trim|exact_length[10]|numeric|required');
        $this->form_validation->set_rules('update_dt_tm', 'Updated Date');
        $this->form_validation->set_rules('update_user_id', 'Updating User');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            // load view again
            $data['message'] = validation_errors();
            $this->load->view('facility_edit', $data);
        } else {
            // save data to DB
            $facilitys = array('name' => $this->input->post('name'),
            'address' => $this->input->post('address'),
            'city' => $this->input->post('city'),
            'state' => $this->input->post('state'),
            'zip' => $this->input->post('zip'),
            'phone' => $this->input->post('phone'),
            'fax' => $this->input->post('fax'),
            'update_user_id' => $upd
            );
            $this->Facility_model->update($id, $facilitys);

            // set user message
            $data['message'] = '<div class="success">' .
                    'Selected facility was successfuly updated!</div>';
            $this->load->view('facility_edit', $data);
        }
    }

}
