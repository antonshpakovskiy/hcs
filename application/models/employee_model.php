<?php

/**
 * Description of Employee_model
 *
 * @author ashpakov
 */
class Employee_model extends CI_Model {

    public function get_data() {
        $query = $this->db->get('employee');
        return $query->result();
    }

    // get report by id
    function get_by_id($id) {
        $this->db->from('employee');
        $this->db->where('employee_id', $id);
        $result = $this->db->get();
        $text = $result->row();
        return $text;
    }
    
    // get person by id
    function get_person_by_id($id) {
        $this->db->from('employee');
        $this->db->where('employee_id', $id);
        $result = $this->db->get();
        $text = $result->row();
        return $text->person_id;
    }

    // add new report
    function save($report) {
        $this->db->insert('employee', $report);
    }

    // update report by id
    function update($id, $report) {
        $this->db->where('employee_id', $id);
        $this->db->update('employee', $report);
    }

    // delete report by id
    function delete($id) {
        $this->db->where('employee_id', $id);
        $this->db->delete('employee');
    }

}

?>
