<?php

/**
 * Description of Facility_model
 *
 * @author ashpakov
 */
class Facility_model extends CI_Model {

    public function get_data() {
        $query = $this->db->get('facility');
        return $query->result();
    }
    
    function get_name_by_id($id){
        $this->db->from('facility');
        $this->db->where('facility_id', $id);
        $result = $this->db->get();
        $text = $result->row();
        return $text->name;
    }

    // get facility by id
    function get_by_id($id) {
        $this->db->from('facility');
        $this->db->where('facility_id', $id);
        $result = $this->db->get();
        $text = $result->row(); 
        return $text;
    }

    // add new facility
    function save($facility) {
        $this->db->insert('facility', $facility);
    }

    // update facility by id
    function update($id, $facility) {
        $this->db->where('facility_id', $id);
        $this->db->update('facility', $facility);
    }

    // delete facility by id
    function delete($id) {
        $this->db->where('facility_id', $id);
        $this->db->delete('facility');
    }

}

?>
