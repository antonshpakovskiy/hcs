<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Language controller
 *
 * @author ashpakov
 */
class Language extends CI_Controller {

    function __construct() {
        parent::__construct();
        // URL Helper file contains functions for working with URLs.
        $this->load->helper('url');
        $config['base_url'] = site_url('language/index/');
    }

    function index() {
        $this->load->model('language_model');
        $languages = $this->language_model->get_data();

        // generate table data
        $this->load->library('table');
        $this->table->set_empty("&nbsp;");
        $this->table->set_heading('ID', 'Language', 'Updated Date', 'Updated By', 'Actions');
        foreach ($languages as $language) {
            $this->table->add_row($language->language_id, $language->language_txt, $language->update_dt_tm, $language->update_user_id, anchor('language/update/' . $language->language_id, 'update', array('class' => 'update')) . '  ' .
                    anchor('language/delete/' . $language->language_id, 'delete', array('class' => 'delete', 'onclick' =>
                        "return confirm('Are you sure want to delete this language?')"))
            );
        }
        $lkup_data['language_table'] = $this->table->generate();

        // load view
        $this->load->view('language_view', $lkup_data);
    }

    function add() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        // reset common properties
        $datetime = date('Y-m-d H:i:s');

        $data['title'] = 'Add new language';
        $data['action'] = site_url('language/add');
        $data['message'] = validation_errors();
        $data['language_id'] = '';
        $data['language_txt'] = '';
        $data['update_dt_tm'] = $datetime;
        $data['update_user_id'] = '';
        $data['link_back'] = anchor('language/index/', 'Back to the Languages Table', array('class' => 'back'));

        // run validation
        $this->form_validation->set_rules('language_id', 'Language ID');
        $this->form_validation->set_rules('language_txt', 'Language Name', 'trim|min_length[3]|max_length[30]|alpha|required');
        $this->form_validation->set_rules('update_dt_tm', 'Updated Date');
        $this->form_validation->set_rules('update_user_id', 'Updating User');

        // show errors
        $this->form_validation->set_message('required', '* required');
        $this->form_validation->set_message('isset', '* required');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            // load view again
            $data['message'] = validation_errors();
            $this->load->view('language_edit', $data);
        } else {
            // save data to DB
            $language = array('language_txt' => $this->input->post('language_txt'));

            $this->load->model('language_model'); // has to be a global function. (use library?)
            $this->language_model->save($language);
            // set user message
            $data['message'] = '<div class="success">New language was successfuly added!</div>';
            $this->load->view('language_edit', $data);
        }
    }

    function delete($id) {
        $this->load->model('language_model');
        $this->language_model->delete($id);

        // redirect to the main view
        redirect('language/index/', 'refresh');
    }

    function update($id) {
        // load librares
        $this->load->helper('form');
        $this->load->library('form_validation');

        // get values for form population
        $this->load->model('language_model');
        $language = $this->language_model->get_by_id($id);

        // set common properties
        $data['title'] = 'Update language';
        $data['message'] = '';
        $data['language_id'] = $language->language_id;
        $data['language_txt'] = $language->language_txt;
        $data['update_dt_tm'] = $language->update_dt_tm;
        $data['update_user_id'] = $language->update_user_id;
        $data['action'] = site_url('language/update/' . $id);
        $data['link_back'] = anchor('language/index/', 'Back to the Languages Table', array('class' => 'back'));

        // run validation
        $this->form_validation->set_rules('language_txt', 'Language Name', 'trim|min_length[3]|max_length[30]|alpha|required');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            // load view again
            $data['message'] = validation_errors();
            $this->load->view('language_edit', $data);
        } else {
            // save data to DB
            $language = array('language_txt' => $this->input->post('language_txt'));
            $test_id = $data['language_id'];
            $this->language_model->update($id, $language);

            // set user message
            $data['message'] = '<div class="success">' .
                    'Selected language was successfuly updated!</div>';
            $this->load->view('language_edit', $data);
        }
    }

}
