<?php

/**
 * Description of Volunteer_model
 *
 * @author ashpakov
 */
class Volunteer_model extends CI_Model {

    public function get_data() {
        $query = $this->db->get('volunteer');
        return $query->result();
    }

    // get employee by id
    function get_by_id($id) {
        $this->db->from('volunteer');
        $this->db->where('volunteer_id', $id);
        $result = $this->db->get();
        $text = $result->row();
        return $text;
    }

    // add new employee
    function save($report) {
        $this->db->insert('volunteer', $report);
    }

    // update employee by id
    function update($id, $report) {
        $this->db->where('volunteer_id', $id);
        $this->db->update('volunteer', $report);
    }

    // delete employee by id
    function delete($id) {
        $this->db->where('volunteer_id', $id);
        $this->db->delete('volunteer');
    }

}

?>
