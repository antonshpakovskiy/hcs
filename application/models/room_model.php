<?php

/**
 * Description of Room_model
 *
 * @author ashpakov
 */
class Room_model extends CI_Model{

    public function get_data() {
        $query = $this->db->get('room');
        return $query->result();
    }

    // get room by id
    function get_by_id($id){
        $this->db->from('room');
        $this->db->where('room_id', $id);
        $result = $this->db->get();
        $text = $result->row();
        return $text;
    }
    
    // get room number by id
    function get_number_by_id($id){
        $this->db->from('room');
        $this->db->where('room_id', $id);
        $result = $this->db->get();
        $text = $result->row();
        return $text->room_number_txt;
    }

    // add new room
    function save($room){
        $this->db->insert('room', $room);
    }

    // update room by id
    function update($id, $room){
        $this->db->where('room_id', $id);
        $this->db->update('room', $room);
    }

    // delete room by id
    function delete($id){
        $this->db->where('room_id', $id);
        $this->db->delete('room');
    }
}

?>
