<?php

class M_state extends CI_Model {

    public function get_state() {

        $query = $this->db->get_where('static_option', array('option_type' => 'state'));
        return $query->result_array();
        
    }

}