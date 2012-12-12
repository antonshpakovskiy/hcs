<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Room controller
 *
 * @author ashpakov
 */
class Room extends CI_Controller {

    function __construct() {
        parent::__construct();
        $config['base_url'] = site_url('room/index/');
    }

    function index(){
        $this->load->model('Person_model');
        $this->load->model('Facility_model');
        $this->load->model('Room_model');
        $rooms = $this->Room_model->get_data();

        // generate table data
        $this->load->library('table');
        $this->table->set_empty("&nbsp;");
        $this->table->set_heading('ID', 'Facility ID', 'Room Number', 'Description', 
                                    'Update Date', 'Updated By', 'Actions');
        foreach ($rooms as $room) {
            $this->table->add_row($room->room_id, $this->Facility_model->get_name_by_id($room->facility_id), 
                        $room->room_number_txt, $room->description, 
                        $room->update_dt_tm, 
                        ($room->update_user_id != 0) ? $this->Person_model->get_name_by_id($room->update_user_id) : 0,
                    anchor('room/update/' . $room->room_id, 'update', 
                        array('class' => 'update')) . '  ' .
                    anchor('room/delete/' . $room->room_id, 'delete', 
                        array('class' => 'delete', 'onclick' =>"return confirm('Are you sure want to delete this room?')"))
            );
        }
        $db_data['room_table'] = $this->table->generate();

        // load view
        $this->load->view('room_view', $db_data);
    }
    
    function get_facility(){
        $this->load->model('Facility_model');
        $facilities = $this->Facility_model->get_data();
        $facility_array = array();
        foreach($facilities as $facility)
            $facility_array[$facility->facility_id] = $facility->name;
        return $facility_array;
    }

    function add() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        // get Persons list
        $this->load->model('Person_model');

        // reset common properties
        $datetime = date('Y-m-d H:i:s');

        $data['title'] = 'Add new Room';
        $data['action'] = site_url('room/add');
        $data['message'] = validation_errors();
        $data['room_id'] = '';
        $data['facility_id'] = form_dropdown('facility_id', $this->get_facility());
        $data['room_number_txt'] = '';
        $data['description'] = '';
        $data['update_dt_tm'] = $datetime;
        $data['update_user_id'] = 0;
        $data['link_back'] = anchor('room/index/', 
            'Back to the Rooms Table', array('class' => 'back'));

        // run validation
        $this->form_validation->set_rules('room_id', 'Room ID');
        $this->form_validation->set_rules('facility_id', 'Facility ID');
        $this->form_validation->set_rules('room_number_txt', 'Room Number', 'trim|min_length[1]|max_length[10]|required');
        $this->form_validation->set_rules('description', 'Room Description', 'max_length[40]');
        $this->form_validation->set_rules('update_dt_tm', 'Updated Date');
        $this->form_validation->set_rules('update_user_id', 'Updating User');

        // show errors
        $this->form_validation->set_message('required', '* required');
        $this->form_validation->set_message('isset', '* required');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            // load view again
            $data['message'] = validation_errors();
            $this->load->view('room_edit', $data);
        } else {
            // save data to DB
            $room = array('facility_id' => $this->input->post('facility_id'),
                                     'room_number_txt' => $this->input->post('room_number_txt'),
                                     'description' => $this->input->post('description'),
                                     'update_user_id' => $this->input->post('update_user_id'),
                                     );

            $this->load->model('Room_model');
            $this->Room_model->save($room);
            // set user message
            $data['message'] = '<div class="success">New room was successfuly added!</div>';
            $this->load->view('room_edit', $data);
        }
    }

    function delete($id) {
        $this->load->model('Room_model');
        $this->Room_model->delete($id);

        // redirect to the main view
        redirect('room/index/', 'refresh');
    }

    function update($id) {
        // load librares
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        // get Persons model
        $this->load->model('Person_model');

        // get values for form population
        $this->load->model('Room_model');
        $room = $this->Room_model->get_by_id($id);

        // set common properties
        $data['title'] = 'Update Room';
        $data['message'] = '';
        $data['room_id'] = $room->room_id;
        $data['facility_id'] = form_dropdown('facility_id', $this->get_facility(), $room->facility_id);
        $data['room_number_txt'] = $room->room_number_txt;
        $data['description'] = $room->description;
        $data['update_dt_tm'] = $room->update_dt_tm;
        $data['update_user_id'] = ($room->update_user_id != 0) ? $this->Person_model->get_name_by_id($room->update_user_id) : 0;
            $upd = $room->update_user_id;
        $data['action'] = site_url('room/update/' . $id);
        $data['link_back'] = anchor('room/index/', 'Back to the Room Table', array('class' => 'back'));

        // run validation
        $this->form_validation->set_rules('room_id', 'Report ID');
        $this->form_validation->set_rules('facility_id', 'Facility ID');
        $this->form_validation->set_rules('room_number_txt', 'Room Number', 'trim|min_length[1]|max_length[10]|required');
        $this->form_validation->set_rules('description', 'Room Description', 'max_length[40]');
        $this->form_validation->set_rules('update_dt_tm', 'Updated Date');
        $this->form_validation->set_rules('update_user_id', 'Updating User');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            // load view again
            $data['message'] = validation_errors();
            $this->load->view('room_edit', $data);
        } else {
            // save data to DB
            $room = array('facility_id' => $this->input->post('facility_id'),
                                     'room_number_txt' => $this->input->post('room_number_txt'),
                                     'description' => $this->input->post('description'),
                                     'update_user_id' => $upd
                                     );
            $this->Room_model->update($id, $room);

// set user message
            $data['message'] = '<div class="success">' .
                    'Selected Room was successfuly updated!</div>';
            $this->load->view('room_edit', $data);
        }
    }

}
