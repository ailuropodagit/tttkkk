<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_custom extends CI_Model {

    public function get_static_option_array($option_type = NULL, $default_value = NULL, $default_text = NULL) {

        $query = $this->db->get_where('static_option', array('option_type' => $option_type));
        $return = array();
        if ($default_value != NULL) {

            $return[$default_value] = $default_text;
        }
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $return[$row['option_id']] = $row['option_text'];
            }
        }
        return $return;
    }

    //To check is this value is unique in DB
    public function check_is_value_unique($the_table, $the_column, $the_value, $the_id_column = NULL, $the_id = NULL ) {
        if (empty($the_value)) {
            return FALSE;
        }
        
        if (!empty($the_id) && is_numeric($the_id)) {
            $username_old = $this->db->where($the_id_column, $the_id)->get($the_table)->row()->$the_column;
            $this->db->where($the_column. "!=", $username_old);
        }
        
        $num_row = $this->db->where($the_column, $the_value)->get($the_table)->num_rows();
        if ($num_row > 0) {
            return FALSE;
        }
        return TRUE;
    }

    //To find a record in DB with one keyword
    public function get_one_table_record($the_table, $the_column, $the_value){
       if (empty($the_value)) {
            return FALSE;
        }
        $query = $this->db->get_where($the_table, array($the_column => $the_value), 1);
        if ($query->num_rows() !== 1) {
            return FALSE;
        }
        return $query->row();
    }
    
}
