<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_custom extends CI_Model {

    //Get all the static option of an option type
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

    //Get one static option text by it option id
    public function get_one_static_option_text($option_id = NULL) {
        if (IsNullOrEmptyString($option_id)) {           
            return '';
        }
        
        $query = $this->db->get_where('static_option', array('option_id' => $option_id));
        if ($query->num_rows() !== 1) {
            return '';
        }
        return $query->row()->option_text;
    }
    
    //To check is this value is unique in DB
    public function check_is_value_unique($the_table, $the_column, $the_value, $the_id_column = NULL, $the_id = NULL) {
        if (empty($the_value)) {
            return FALSE;
        }

        if (!empty($the_id) && is_numeric($the_id)) {
            $username_old = $this->db->where($the_id_column, $the_id)->get($the_table)->row()->$the_column;
            $this->db->where($the_column . "!=", $username_old);
        }

        $num_row = $this->db->where($the_column, $the_value)->get($the_table)->num_rows();
        if ($num_row > 0) {
            return FALSE;
        }
        return TRUE;
    }

    //To find one record in DB with one keyword
    public function get_one_table_record($the_table, $the_column, $the_value) {
        if (empty($the_value)) {
            return FALSE;
        }
        $query = $this->db->get_where($the_table, array($the_column => $the_value), 1);
        if ($query->num_rows() !== 1) {
            return FALSE;
        }
        return $query->row();
    }
    
    //To find many records in DB with one keyword
    public function get_many_table_record($the_table, $the_column, $the_value){
        $query = $this->db->get_where($the_table, array($the_column => $the_value));
        return $query->result();
    }
    
    //To find one record in DB of parent table with one keyword
    public function get_parent_table_record($the_table, $the_column, $the_value, $foreign_column, $parent_table, $primary_column) {
        if (empty($the_value)) {
            return FALSE;
        }
        $query = $this->db->get_where($the_table, array($the_column => $the_value), 1);
        if ($query->num_rows() !== 1) {
            return FALSE;
        }
        
        $foreign_row = $query->row();
        $foreign_key = $foreign_row->$foreign_column;
        $parent_query = $this->db->get_where($parent_table, array($primary_column => $foreign_key), 1);
        if ($parent_query->num_rows() !== 1) {
            return FALSE;
        }
        
        return $parent_query->row();
    }
    
    //To get all main category
    function getCategory() {
        $query = $this->db->get_where('category', array('category_level' => '0'));
        return $query->result();
    }

    //To get related sub category by pass in the main category id
    function getSubCategory($id) {
        $query = $this->db->get_where('category', array('main_category_id' => $id, 'category_level' => '1'));
        return $query->result();
    }

    function getBranchList($id){
        $query = $this->db->get_where('merchant_branch', array('merchant_id' => $id));
        return $query->result();
    }
    
    public function getBranchList_with_search($id, $search_word) {
        if (IsNullOrEmptyString($search_word)) {           
            return $this->getBranchList($id);
        }
        $this->db->like('name', $search_word);
        $this->db->or_like('address', $search_word);
        $query = $this->db->get_where('merchant_branch', array('merchant_id' => $id));
        if ($query->num_rows() == 0) {
            return $this->getBranchList(0);
        }
        return $query->result();
    }
    
}
