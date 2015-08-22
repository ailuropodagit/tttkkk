<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_custom extends Ion_auth_model {
    
public function get_static_option_list($option_type = NULL) {
    $this->trigger_events('get_static_option_list');
    $this->db->from('static_option')
            ->where('option_type', $option_type)
            ->order_by('option_id');
    $result = $this->db->get();
    $return = array();
    if ($result->num_rows() > 0) {
        foreach ($result->result_array() as $row) {
            $return[$row['option_id']] = $row['option_text'];
        }
    }
    return $return;
}

}