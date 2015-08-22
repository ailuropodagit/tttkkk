<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_custom extends CI_Model {
    
    public function get_static_option_array($option_type = NULL,$default_value = NULL, $default_text = NULL) {

        $query = $this->db->get_where('static_option', array('option_type' => $option_type));
        $return = array();
        if($default_value != NULL){
            
            $return[$default_value] = $default_text;
        }
            if($query->num_rows() > 0) {
                foreach($query->result_array() as $row) {
                    $return[$row['option_id']] = $row['option_text'];
                }
            }
            return $return;
            
        
    }

}