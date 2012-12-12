<?php

class Account_model extends CI_Model {

    function login($username, $password) {
        $this->db->select('account_id, person_id, username, password');
        $this->db->from('account');
        $this->db->where("username = '$username'");
        $this->db->where("password = AES_ENCRYPT('$password', 'hf43R65wqc9urT3Vax4j5n2ty928aLs7')");
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() == 1)
            return $query->result();
        else
            return false;
    }

}

?>
