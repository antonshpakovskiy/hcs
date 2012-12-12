<?php

/**
 * Description of Guardian_model
 *
 * @author ashpakov
 */
class Guardian_model extends CI_Model{

    public function get_data() {
        $query = $this->db->get('guardian');
        return $query->result();
    }

    // get guardian by id
    function get_person_by_id($id){
        $this->db->from('guardian');
        $this->db->where('person_id', $id);
        $result = $this->db->get();
        $text = $result->row();
        return $text;
    }
    
    // get guardian number by id
    function get_student_by_id($id){
        $this->db->from('guardian');
        $this->db->where('student_id', $id);
        $result = $this->db->get();
        $text = $result->row();
        return $text;
    }

    // add new guardian
    function save($guardian){
        $this->db->insert('guardian', $guardian);
    }

    // update guardian by id
    function update($id, $guardian){
        $this->db->where('guardian_id', $id);
        $this->db->update('guardian', $guardian);
    }

    // delete guardian by id
    function delete($id){
        $this->db->where('guardian_id', $id);
        $this->db->delete('guardian');
    }
}

?>
