<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Mobilemobilecarrier controller
 *
 * @author ashpakov
 */
class Mobilecarrier extends CI_Controller {

    function __construct() {
        parent::__construct();
        // URL Helper file contains functions for working with URLs.
        $this->load->helper('url');
        $config['base_url'] = site_url('mobilecarrier/index/');
    }

    function index() {
        $this->load->model('mobilecarrier_model');
        $mobilecarriers = $this->mobilecarrier_model->get_data();

        // generate table data
        $this->load->library('table');
        $this->table->set_empty("&nbsp;");
        $this->table->set_heading('ID', 'Text Address', 'Updated Date', 'Updated By', 'Actions');
        foreach ($mobilecarriers as $mobilecarrier) {
            $this->table->add_row($mobilecarrier->carrier_id, $mobilecarrier->text_address, $mobilecarrier->update_dt_tm, $mobilecarrier->update_user_id, anchor('mobilecarrier/update/' . $mobilecarrier->carrier_id, 'update', array('class' => 'update')) . '  ' .
                    anchor('mobilecarrier/delete/' . $mobilecarrier->carrier_id, 'delete', array('class' => 'delete', 'onclick' =>
                        "return confirm('Are you sure want to delete this record?')"))
            );
        }
        $lkup_data['mobilecarrier_table'] = $this->table->generate();

        // load view
        $this->load->view('mobilecarrier_view', $lkup_data);
    }

    function add() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        // reset common properties
        $datetime = date('Y-m-d H:i:s');

        $data['title'] = 'Add new Carrier';
        $data['action'] = site_url('mobilecarrier/add');
        $data['message'] = validation_errors();
        $data['carrier_id'] = '';
        $data['text_address'] = '';
        $data['update_dt_tm'] = $datetime;
        $data['update_user_id'] = '';
        $data['link_back'] = anchor('mobilecarrier/index/', 'Back to the Carriers Table', array('class' => 'back'));

        // run validation
        $this->form_validation->set_rules('carrier_id', 'Carrier ID');
        $this->form_validation->set_rules('text_address', 'Text Address', 'trim|min_length[3]|max_length[64]|required');
        $this->form_validation->set_rules('update_dt_tm', 'Updated Date');
        $this->form_validation->set_rules('update_user_id', 'Updating User');

        // show errors
        $this->form_validation->set_message('required', '* required');
        $this->form_validation->set_message('isset', '* required');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            // load view again
            $data['message'] = validation_errors();
            $this->load->view('mobilecarrier_edit', $data);
        } else {
            // save data to DB
            $mobilecarrier = array('text_address' => $this->input->post('text_address'));

            $this->load->model('mobilecarrier_model');
            $this->mobilecarrier_model->save($mobilecarrier);
            // set user message
            $data['message'] = '<div class="success">New address was successfuly added!</div>';
            $this->load->view('mobilecarrier_edit', $data);
        }
    }

    function delete($id) {
        $this->load->model('mobilecarrier_model');
        $this->mobilecarrier_model->delete($id);

        // redirect to the main view
        redirect('mobilecarrier/index/', 'refresh');
    }

    function update($id) {
        // load librares
        $this->load->helper('form');
        $this->load->library('form_validation');

        // get values for form population
        $this->load->model('mobilecarrier_model');
        $mobilecarrier = $this->mobilecarrier_model->get_by_id($id);

        // set common properties
        $data['title'] = 'Update a Carrier';
        $data['message'] = '';
        $data['carrier_id'] = $mobilecarrier->carrier_id;
        $data['text_address'] = $mobilecarrier->text_address;
        $data['update_dt_tm'] = $mobilecarrier->update_dt_tm;
        $data['update_user_id'] = $mobilecarrier->update_user_id;
        $data['action'] = site_url('mobilecarrier/update/' . $id);
        $data['link_back'] = anchor('mobilecarrier/index/', 'Back to the Carriers Table', array('class' => 'back'));

        // run validation
        $this->form_validation->set_rules('text_address', 'Text Address', 'trim|min_length[3]|max_length[64]|required');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            // load view again
            $data['message'] = validation_errors();
            $this->load->view('mobilecarrier_edit', $data);
        } else {
            // save data to DB
            $mobilecarriers = array('text_address' => $this->input->post('text_address'));
            $this->mobilecarrier_model->update($id, $mobilecarriers);

            // set user message
            $data['message'] = '<div class="success">' .
                    'Selected mobilecarrier was successfuly updated!</div>';
            $this->load->view('mobilecarrier_edit', $data);
        }
    }

}
