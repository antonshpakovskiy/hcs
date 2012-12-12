<?php

/**
 * Description of Backgroundcheck_model
 *
 * @author ashpakov
 */
class Backgroundcheck_model extends CI_Model {

    public function get_data() {
        $query = $this->db->get('backgroundcheck');
        return $query->result();
    }

    // get report by id
    function get_by_id($id) {
        $this->db->from('backgroundcheck');
        $this->db->where('bg_check_id', $id);
        $result = $this->db->get();
        $text = $result->row();
        return $text;
    }

    // add new report
    function save($report) {
        $this->db->insert('backgroundcheck', $report);
    }

    // update report by id
    function update($id, $report) {
        $this->db->where('bg_check_id', $id);
        $this->db->update('backgroundcheck', $report);
    }

    // delete report by id
    function delete($id) {
        $this->db->where('bg_check_id', $id);
        $this->db->delete('backgroundcheck');
    }

}

?>
