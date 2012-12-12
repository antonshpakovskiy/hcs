<?php

/**
 * Description of Person_model
 *
 * @author ashpakov
 */
class Person_model extends CI_Model {

    public function get_data() {
        $query = $this->db->get('person');
        return $query->result();
    }

    // get person by id
    function get_by_id($id) {
        $this->db->from('person');
        $this->db->where('person_id', $id);
        $result = $this->db->get();
        $text = $result->row();
        return $text;
    }
    
    // get first and last names of a person by id
    function get_name_by_id($id){
        $this->db->from('person');
        $this->db->where('person_id', $id);
        $result = $this->db->get();
        $text = $result->row();
        return "{$text->first_name} {$text->last_name}";
    }

    // add new person
    function save($skill) {
        $this->db->insert('person', $skill);
//        return $this->db->insert_id();
    }

    // update person by id
    function update($id, $skill) {
        $this->db->where('person_id', $id);
        $this->db->update('person', $skill);
    }

    // delete person by id
    function delete($id) {
        $this->db->where('person_id', $id);
        $this->db->delete('person');
    }

}

?>
