<?php

/**
 * Description of Student_model
 *
 * @author ashpakov
 */
class Student_model extends CI_Model {

    public function get_data() {
        $query = $this->db->get('student');
        return $query->result();
    }

    // get student by id
    function get_by_id($id) {
        $this->db->from('student');
        $this->db->where('student_id', $id);
        $result = $this->db->get();
        $text = $result->row();
        return $text;
    }

    // add new student
    function save($student) {
        $this->db->insert('student', $student);
    }

    // update student by id
    function update($id, $student) {
        $this->db->where('student_id', $id);
        $this->db->update('student', $student);
    }

    // delete student by id
    function delete($id) {
        $this->db->where('student_id', $id);
        $this->db->delete('student');
    }

}

?>
