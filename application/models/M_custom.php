<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class M_custom extends CI_Model
{
    //Get all the static option of an option type
    public function get_static_option_array($option_type = NULL, $default_value = NULL, $default_text = NULL)
    {
        $query = $this->db->get_where('static_option', array('option_type' => $option_type));
        $return = array();
        if ($default_value != NULL)
        {
            $return[$default_value] = $default_text;
        }
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $return[$row['option_id']] = $row['option_text'];
            }
        }
        return $return;
    }   
     
    //to do todo
    public function activity_check_access($act_history_id)
    {
        $have_access = FALSE;

        if ($this->ion_auth->logged_in())
        {
            $login_id = $this->ion_auth->user()->row()->id;
            $login_type = $this->session->userdata('user_group_id');

            if (check_correct_login_type($this->config->item('group_id_admin')) || check_correct_login_type($this->config->item('group_id_worker')))
            {
                $have_access = TRUE;
            }

            $activity_query = $this->db->get_where('activity_history', array('act_history_id' => $act_history_id));
            if ($activity_query->num_rows() == 1)
            {
                $activity_row = $activity_query->row_array();
                $act_refer_type = $activity_row['act_refer_type'];
                $act_refer_id = $activity_row['act_refer_id'];
                $act_by_id = $activity_row['act_by_id'];
                $act_by_type = $activity_row['act_by_type'];
                
                if (check_correct_login_type($this->config->item('group_id_merchant')) || check_correct_login_type($this->config->item('group_id_supervisor')))
                {
                    $merchant_id = $login_id;
                    if($act_by_id == $merchant_id && $act_by_type == $login_type){
                        $have_access = TRUE;
                    }
                    
                    $sup_under_this_mer = $this->get_list_of_allow_id('users', 'su_merchant_id', $merchant_id, 'id');
                    if(in_array($act_by_id,$sup_under_this_mer)){
                        $have_access = TRUE;
                    }
                    
                    if (check_correct_login_type($this->config->item('group_id_supervisor')))
                    {
                        $merchant_id = $this->ion_auth->user()->row()->su_merchant_id;
                    }

                    if($act_refer_type == 'adv'){
                        $advertise_query = $this->db->get_where('advertise', array('advertise_id' => $act_refer_id));
                        if($advertise_query->num_rows() == 1){
                             $advertise_row = $advertise_query->row_array();
                             if($advertise_row['merchant_id'] == $merchant_id){
                                 $have_access = TRUE;
                             }
                        }
                    }
                    if($act_refer_type == 'mua'){
                        $mua_query = $this->db->get_where('merchant_user_album', array('merchant_user_album_id' => $act_refer_id));
                        if($mua_query->num_rows() == 1){
                             $mua_row = $mua_query->row_array();
                             if($mua_row['merchant_id'] == $merchant_id){
                                 $have_access = TRUE;
                             }
                        }
                    }
                    if($act_refer_type == 'usa'){
                        
                    }
                }
                
                if (check_correct_login_type($this->config->item('group_id_user')))
                {
                    if($act_by_id == $login_id && $act_by_type == $login_type){
                        $have_access = TRUE;
                    }
                    if($act_refer_type == 'mua'){
                        $mua_query = $this->db->get_where('merchant_user_album', array('merchant_user_album_id' => $act_refer_id));
                        if($mua_query->num_rows() == 1){
                             $mua_row = $mua_query->row_array();
                             if($mua_row['user_id'] == $login_id){
                                 $have_access = TRUE;
                             }
                        }
                    }
                    if($act_refer_type == 'usa'){
                        
                    }
                }
            }
        }
        return $have_access;
    }

    //To find many records in DB with one keyword
    public function get_list_of_allow_id($the_table, $the_column, $the_value, $wanted_column, $second_column = NULL, $second_value = NULL)
    {      
        $query = $this->db->get_where($the_table, array($the_column => $the_value));

        if (!IsNullOrEmptyString($second_column) && !IsNullOrEmptyString($second_value))
        {
            $query = $this->db->get_where($the_table, array($the_column => $the_value, $second_column => $second_value));
        }
        
        $return = array();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $return[] = $row[$wanted_column];
            }
        }
        return $return;
    }
    
     //Get one static option text by it option id
    public function display_category($category_id = NULL)
    {
        if (IsNullOrEmptyString($category_id))
        {
            return '';
        }

        $query = $this->db->get_where('category', array('category_id' => $category_id));
        if ($query->num_rows() !== 1)
        {
            return '';
        }
        return $query->row()->category_label;
    }
    
    //GET MAIN CATEGORY BY SUB CATEGORY ID
    public function display_main_category($category_id = NULL)
    {
        $this->db->select('');
        $this->db->from('category');
        $this->db->where('category_id',$category_id);
        $query = $this->db->get();
        $main_category_id = $query->row()->main_category_id;
        
        $this->db->select('category_label');
        $this->db->from('category');
        $this->db->where('category_id',$main_category_id);
        $query = $this->db->get();
        $main_category_label = $query->row()->category_label;
        return $main_category_label;
    }
    
    //Get one static option text by it option id
    public function display_users($user_id = NULL, $with_icon = 0)
    {
        if (IsNullOrEmptyString($user_id))
        {
            return '';
        }

        $query = $this->db->get_where('users', array('id' => $user_id));
        if ($query->num_rows() !== 1)
        {
            return '';
        }

        $return = $query->row();
        
        $prefix = '';
        $postfix = '';
        if($with_icon == 1){
            if ($return->main_group_id == $this->config->item('group_id_merchant')){
                $prefix = '<i class="fa fa-user-secret"></i> ';
                $postfix = ' <i class="fa fa-star"></i> ';
            }else if($return->main_group_id == $this->config->item('group_id_supervisor')){
                $prefix = '<i class="fa fa-user-secret"></i> ';
            }
            else if($return->main_group_id == $this->config->item('group_id_user')){
                if($return->us_gender_id == $this->config->item('gender_id_male')){
                    $prefix = '<i class="fa fa-male"></i> ';
                }else{
                    $prefix = '<i class="fa fa-female"></i> ';
                }
            }
            else{
                $prefix = '<i class="fa fa-user"></i> ';
            }
        }
        
        if ($return->main_group_id == $this->config->item('group_id_merchant'))
        {
            return $prefix .$return->company . $postfix;
        }
        else if($return->main_group_id == $this->config->item('group_id_supervisor')){
            $merchant_query = $this->db->get_where('users', array('id' => $return->su_merchant_id ));
            $merchant_row = $merchant_query->row_array();
            return $prefix . $merchant_row['company'];
        }
        else
        {
            return $prefix . $return->first_name . ' ' . $return->last_name;
        }
    }

    //Get one static option text by it option id
    public function display_static_option($option_id = NULL)
    {
        if (IsNullOrEmptyString($option_id))
        {
            return '';
        }

        $query = $this->db->get_where('static_option', array('option_id' => $option_id));
        if ($query->num_rows() !== 1)
        {
            return '';
        }
        return $query->row()->option_text;
    }

    //Get one static option text by it option id
    public function display_dynamic_option($option_id = NULL)
    {
        if (IsNullOrEmptyString($option_id))
        {
            return '';
        }

        $query = $this->db->get_where('dynamic_option', array('option_id' => $option_id));
        if ($query->num_rows() !== 1)
        {
            return '';
        }
        return $query->row()->option_desc;
    }
    
    //Get all the dynamic option of an option type
    public function get_dynamic_option_array($option_type, $default_value = NULL, $default_text = NULL, $prefix = NULL, $postfix = NULL, $use_option_title = 0)
    {

        $query = $this->db->get_where('dynamic_option', array('option_type' => $option_type, 'hide_flag' => 0));
        
        $return = array();
        if (!IsNullOrEmptyString($default_value))
        {
            $return[$default_value] = $default_text;
        }
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                if($use_option_title == 1){
                    $the_text = $row['option_title'];
                }else{
                    $the_text = $row['option_desc'];
                }
                
                if(!IsNullOrEmptyString($prefix) && $row['option_special'] == 1){
                    $the_text = $prefix. ' '. $the_text;
                }
                if(!IsNullOrEmptyString($postfix) && $row['option_special'] == 2){
                    $the_text = $the_text . ' ' . $postfix;
                }
                
                $return[$row['option_id']] = $the_text;
            }
        }
        
        return $return;
    }

    function get_keyarray_list($the_table, $id_column, $id_value, $key_column, $value_column, $default_value = NULL, $default_text = NULL)
    {
        $query = $this->db->get_where($the_table, array($id_column => $id_value));
        $return = array();
        if (!IsNullOrEmptyString($default_value))
        {
            $return[$default_value] = $default_text;
        }
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $return[$row[$key_column]] = $row[$value_column];
            }
        }
        return $return;
    }
    
    //To check is this value is unique in DB
    public function check_is_value_unique($the_table, $the_column, $the_value, $the_id_column = NULL, $the_id = NULL)
    {
        if (empty($the_value))
        {
            return FALSE;
        }

        if (!empty($the_id) && is_numeric($the_id))
        {
            $username_old = $this->db->where($the_id_column, $the_id)->get($the_table)->row()->$the_column;
            $this->db->where($the_column . "!=", $username_old);
        }

        $num_row = $this->db->where($the_column, $the_value)->get($the_table)->num_rows();
        if ($num_row > 0)
        {
            return FALSE;
        }
        return TRUE;
    }

    //To find one record in DB with one keyword
    public function get_one_table_record($the_table, $the_column, $the_value, $want_array = 0)
    {
        if (empty($the_value))
        {
            return FALSE;
        }
        $query = $this->db->get_where($the_table, array($the_column => $the_value), 1);
        if ($query->num_rows() !== 1)
        {
            return FALSE;
        }

        if ($want_array == 1)
        {
            return $query->row_array();
        }
        else
        {
            return $query->row();
        }
    }

    public function get_one_field_by_key($the_table, $key_column, $key_value, $wanted_column){
        $query = $this->db->get_where($the_table, array($key_column => $key_value), 1);
        if ($query->num_rows() == 0)
        {
            return FALSE;
        }else{
            $result = $query->row_array();
            return $result[$wanted_column];
        }
    }
    
    //To find many records in DB with one keyword
    public function get_many_table_record($the_table, $the_column, $the_value, $want_array = 0)
    {
        $query = $this->db->get_where($the_table, array($the_column => $the_value));

        if ($want_array == 1)
        {
            return $query->result_array();
        }
        else
        {
            return $query->result();
        }
    }

    public function generate_voucher($id)
    {
        $result = $this->get_one_table_record('users', 'id', $id, 1);
        $voucher = '';
        $web_setting = $this->get_one_table_record('web_setting', 'set_type', 'voucher_counter', 1);
        $counter = $web_setting['set_value'];
        if ($result)
        {
            do
            {
                $voucher = strtoupper(substr($result['slug'], 0, 3)) . date('Ymd') . str_pad($counter, 3, "0", STR_PAD_LEFT);
                $check_unique = $this->check_is_value_unique('advertise', 'voucher', $voucher);
                $counter += 1;
            } while (!$check_unique);
            if($counter == '1000') {$counter = '1';}
            $the_data = array(
                'set_value' => $counter,
            );       
            $this->db->where('set_id', $web_setting['set_id']);
            $this->db->update('web_setting', $the_data);
        }
        return $voucher;
    }

    //To find one record in DB of parent table with one keyword
    public function get_parent_table_record($the_table, $the_column, $the_value, $foreign_column, $parent_table, $primary_column, $want_array = 0)
    {
        if (empty($the_value))
        {
            return FALSE;
        }
        $query = $this->db->get_where($the_table, array($the_column => $the_value), 1);
        if ($query->num_rows() !== 1)
        {
            return FALSE;
        }

        $foreign_row = $query->row();
        $foreign_key = $foreign_row->$foreign_column;
        $parent_query = $this->db->get_where($parent_table, array($primary_column => $foreign_key), 1);
        if ($parent_query->num_rows() !== 1)
        {
            return FALSE;
        }

        if ($want_array == 1)
        {
            return $parent_query->row_array();
        }
        else
        {
            return $parent_query->row();
        }
    }

    //To find one record in DB with one keyword
    public function getOneAdvertise($advertise_id)
    {
        $this->db->where('start_time is not null AND end_time is not null');
        $query = $this->db->get_where('advertise', array('advertise_id' => $advertise_id, 'hide_flag' => 0), 1);
        if ($query->num_rows() !== 1)
        {
            return FALSE;
        }

        return $query->row_array();
    }
    
    //To get all main category
    function getAdvertise($advertise_type, $sub_category_id = NULL, $merchant_id = NULL, $show_expired = 0, $limit = NULL, $start = NULL)
    {       
        if(!IsNullOrEmptyString($sub_category_id)){
            $this->db->where('sub_category_id', $sub_category_id);
        }
        if(!IsNullOrEmptyString($merchant_id)){
            $this->db->where('merchant_id', $merchant_id);
        }
        if($show_expired == 0){
            $this->db->where('end_time >=',get_part_of_date('all'));
        }                     
        $this->db->order_by("advertise_id", "desc"); 
        $this->db->where('start_time is not null AND end_time is not null');
        
        if(!IsNullOrEmptyString($limit) && !IsNullOrEmptyString($start)){
            if($start == 1) {$start = 0;} //For fix skip first index problem on pagination
            $this->db->limit($limit, $start);
        }
        
        if($advertise_type == 'all'){
            $query = $this->db->get_where('advertise', array('hide_flag' => 0));
        }else{
            $query = $this->db->get_where('advertise', array('advertise_type' => $advertise_type, 'hide_flag' => 0));
        }
        //var_dump($query->result_array());
        return $query->result_array();
    }
    
    //To get merchant promotion list with branch filter or history only
    function getPromotion($merchant_id, $supervisor_id = 0, $show_history = 0)
    {
        $branch_id = 0;
        if ($supervisor_id != 0)
        {
            $supervisor = $this->getUser($supervisor_id);
            $branch_id = $supervisor['su_branch_id'];
        }

        if ($show_history == 0)
        {
            $this->db->where('voucher_expire_date >=', get_part_of_date('all'));
        }
        else
        {
            $this->db->where('voucher_expire_date <', get_part_of_date('all'));
        }

        $this->db->order_by("advertise_id", "desc");
        $this->db->where('start_time is not null AND end_time is not null');

        $original_query = $this->db->get_where('advertise', array('advertise_type' => 'pro', 'hide_flag' => 0, 'merchant_id' => $merchant_id));
        $original_result = $original_query->result_array();
        $advertise_list[] = NULL;
        foreach ($original_result as $original_row)
        {
            $advertise_id = $original_row['advertise_id'];
            if ($branch_id == 0)
            {
                $advertise_list[] = $advertise_id;
            }
            else
            {
                $many_list = $this->many_get_childlist('candie_branch', $advertise_id);  
                if (in_array($branch_id, $many_list))
                {
                    //var_dump($many_list);
                    $advertise_list[] = $advertise_id;
                }
            }
        }
        
        $this->db->order_by("advertise_id", "desc");
        $this->db->where_in('advertise_id', $advertise_list);
        $advertise_query = $this->db->get_where('advertise', array('advertise_type' => 'pro', 'hide_flag' => 0, 'merchant_id' => $merchant_id));

        return $advertise_query->result_array();
    }

    //To get merchant promotion list with branch filter or history only
    function getUserRedemption($promotion_id, $status_id, $hide_expired = 0)
    {
        if ($hide_expired == 1)
        {
            $this->db->where('expired_date >=', get_part_of_date('all'));
        }
        $redeem_query = $this->db->get_where('user_redemption', array('advertise_id' => $promotion_id, 'status_id' => $status_id));
        return $redeem_query->result_array();
    }
    
    //To get all main category
    function getAlbumUserMerchant($user_id = NULL, $merchant_id = NULL)
    {
        if(!IsNullOrEmptyString($user_id)){
            $this->db->where('user_id', $user_id);
        }
        if(!IsNullOrEmptyString($merchant_id)){
            $this->db->where('merchant_id', $merchant_id);
        }

        $this->db->order_by("merchant_user_album_id", "desc"); 
        $query = $this->db->get_where('merchant_user_album', array('post_type' => 'mer', 'hide_flag' => 0));
        return $query->result_array();
    }
    
    //Get all the static option of an option type
    public function getCategoryList($default_value = NULL, $default_text = NULL)
    {
        $query = $this->db->get_where('category', array('category_level' => '0', 'hide_flag' => 0));
        $return = array();
        if ($default_value != NULL)
        {
            $return[$default_value] = $default_text;
        }
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $return[$row['category_id']] = $row['category_label'];
            }
        }
        return $return;
    }
    
    //To get all main category
    function getCategory()
    {
        $query = $this->db->get_where('category', array('category_level' => '0'));
        return $query->result();
    }

    //To get all main category
    function getMerchant($merchant_id = 0, $slug = NULL, $company = NULL)
    {
        $group_id = $this->config->item('group_id_merchant');
        if($merchant_id != 0){
            $query = $this->db->get_where('users', array('id' => $merchant_id, 'main_group_id' => $group_id));
        }else if($slug != NULL){
            $query = $this->db->get_where('users', array('slug' => $slug, 'main_group_id' => $group_id));
        }else if($company != NULL){
            $query = $this->db->get_where('users', array('company' => $company, 'main_group_id' => $group_id));
        }
        return $query->row_array();
    }
    
    function getUser($user_id){
        $query = $this->db->get_where('users', array('id' => $user_id));
        return $query->row_array();
    }
    
    //To get related sub category by pass in the main category id
    function getSubCategory($id)
    {
        $query = $this->db->get_where('category', array('main_category_id' => $id, 'category_level' => '1'));
        return $query->result();
    }

    function getBranchList($id, $want_array = 0)
    {
        $query = $this->db->get_where('merchant_branch', array('merchant_id' => $id));
        if ($want_array == 1)
        {
            return $query->result_array();
        }
        else
        {
            return $query->result();
        }
    }

    public function getBranchList_with_search($id, $search_word)
    {
        if (IsNullOrEmptyString($search_word))
        {
            return $this->getBranchList($id);
        }
        $search_word = $this->db->escape('%' . $search_word . '%');
        $this->db->where("(`name` LIKE $search_word OR `address` LIKE $search_word)");
        $query = $this->db->get_where('merchant_branch', array('merchant_id' => $id));
        if ($query->num_rows() == 0)
        {
            return $this->getBranchList(0);
        }
        return $query->result();
    }

    //Get all the static option of an option type
    public function getMerchantList($default_value = NULL, $default_text = NULL)
    {
        $query = $this->db->get_where('users', array('main_group_id' => $this->config->item('group_id_merchant'), 'hide_flag' => 0));
        $return = array();
        if ($default_value != NULL)
        {
            $return[$default_value] = $default_text;
        }
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $return[$row['id']] = $row['company'];
            }
        }
        return $return;
    }
    
    public function getMerchantList_by_category($category_id = 0, $category_level = 0, $return_empty = 0)
    {
        if ($category_level == 1)
        {
            $sub_category = $this->get_one_table_record('category', 'category_id', $category_id);
            if ($sub_category)
            {
                $category_id = $sub_category->main_category_id;
            }
        }
        $query = $this->db->get_where('users', array('me_category_id' => $category_id));
        if ($query->num_rows() == 0 && $return_empty == 0)
        {
            $query = $this->db->get_where('users', array('main_group_id' => $this->config->item('group_id_merchant'), 'hide_flag' => 0));
        }
        return $query->result();
    }

    public function get_id_after_insert($the_table, $the_data)
    {
        if ($this->db->insert($the_table, $the_data))
        {
            $new_id = $this->db->insert_id();
            return $new_id;
        }
        return FALSE;
    }

    public function many_insert_or_remove($the_type, $parent_id, $new_child_list)
    {
        $query = $this->db->get_where('many_to_many', array('many_type' => $the_type, 'many_parent_id' => $parent_id))->result();
        $current_child_list = array();
        foreach ($query as $value)
        {
            $current_child_list[] = $value->many_child_id;
        }
        $child_to_insert = array_diff($new_child_list, $current_child_list);
        if (!empty($child_to_insert))
        {
            foreach ($child_to_insert as $value)
            {
                $the_data = array(
                    'many_parent_id' => $parent_id,
                    'many_child_id' => $value,
                    'many_type' => $the_type,
                );
                $this->db->insert('many_to_many', $the_data);
            }
        }

        $child_to_remove = array_diff($current_child_list, $new_child_list);
        if (!empty($child_to_remove))
        {
            foreach ($child_to_remove as $value)
            {
                $this->db->where(array('many_parent_id' => $parent_id, 'many_child_id' => $value));
                $this->db->delete('many_to_many');
            }
        }
    }

    //To get the childlist id from many table by the type and parent id
    public function many_get_childlist($the_type, $parent_id)
    {
        $query = $this->db->get_where('many_to_many', array('many_type' => $the_type, 'many_parent_id' => $parent_id));

        $return = array();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $return[] = $row['many_child_id'];
            }
        }
        return $return;
    }
    
    //Not Yet Full Test // To Do  //ToDo
    //To get the childlist id from many table by the type and parent id
    public function many_get_childlist_detail($the_type, $parent_id, $child_table, $child_id_column)
    {
        $query = $this->db->get_where('many_to_many', array('many_type' => $the_type, 'many_parent_id' => $parent_id));

        $return = array();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $child = $this->db->get_where($child_table, array($child_id_column => $row['many_child_id']), 1);
                if ($child->num_rows() == 1)
                 {
                    $return[] = $child->row_array();
                 }
                
            }
        }
        return $return;
    }
    
    //To get the childlist id from many table by the type and parent id
    public function many_get_child_count($the_type, $parent_id)
    {
        $query = $this->db->get_where('many_to_many', array('many_type' => $the_type, 'many_parent_id' => $parent_id));

        return $query->num_rows();
    }
    
    public function many_check_and_insert($the_type, $parent_id, $child_id)
    {
        $query = $this->db->get_where('many_to_many', array('many_type' => $the_type, 'many_parent_id' => $parent_id, 'many_child_id' => $child_id));
        if ($query->num_rows() == 0)
        {
            $the_data = array(
                'many_type' => $the_type,
                'many_parent_id' => $parent_id,
                'many_child_id' => $child_id,
            );
            $this->db->insert('many_to_many', $the_data);
            $insert_id = $this->db->insert_id();
            $merchant_id = $this->get_merchant_id_from_advertise($parent_id);
            switch($the_type){
                case 'view_advertise': 
                    $this->candie_history_insert(1, $insert_id, 'many_to_many');                  
                    $this->transaction_history_insert($merchant_id, 11, $insert_id, 'many_to_many');
                    break;
            }
        }
    }

    public function activity_view($advertise_id){
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            $user_id = $this->ion_auth->user()->row()->id;
            $this->many_check_and_insert('view_advertise',$advertise_id,$user_id);
        }       
    }
    
    public function activity_view_count($advertise_id){
            return $this->many_get_child_count('view_advertise',$advertise_id);
    }
    
    public function activity_hide($act_history_id)
    {
        if ($this->ion_auth->logged_in())
        {
            $login_id = $this->ion_auth->user()->row()->id;
            $login_type = $this->session->userdata('user_group_id');
            $the_data = array(
                'hide_flag' => 1,
                'hide_time' => get_part_of_date('all'),
                'hide_by' => $login_id,
                'hide_by_type' => $login_type,
            );
            $this->db->where('act_history_id', $act_history_id);
            $this->db->update('activity_history', $the_data);
        }
    }
    
    public function activity_check_and_insert($the_type, $refer_id, $refer_type, $by_id, $by_type, $allow_duplicate = 0, $rating = NULL, $comment = NULL)
    {
        $search_data = array(
            'act_type' => $the_type,
            'act_refer_id' => $refer_id,
            'act_refer_type' => $refer_type,
            'act_by_id' => $by_id,
            'act_by_type' => $by_type,
        );
        $query = $this->db->get_where('activity_history', $search_data);
        if (($query->num_rows() == 0 && $allow_duplicate == 0) || $allow_duplicate != 0)
        {
            $the_data = array(
                'act_type' => $the_type,
                'act_refer_id' => $refer_id,
                'act_refer_type' => $refer_type,
                'act_by_id' => $by_id,
                'act_by_type' => $by_type,
                'rating' => $rating == NULL? NULL : $rating,
                'comment' => $comment == NULL? NULL : $comment,
            );
            $this->db->insert('activity_history', $the_data);
            $insert_id = $this->db->insert_id();
            if($refer_type == 'mua'){
                $merchant_id = $this->get_merchant_id_from_mua($refer_id);
            }else{
                $merchant_id = $this->get_merchant_id_from_advertise($refer_id);
            }
            switch($the_type){
                case 'like': 
                    $this->candie_history_insert(2, $insert_id);                   
                    $this->transaction_history_insert($merchant_id, 12, $insert_id);
                    break;
                case 'rating': 
                    $this->candie_history_insert(3, $insert_id);
                    $this->transaction_history_insert($merchant_id, 13, $insert_id);
                    break;
            }
        }

    }
    
    public function activity_check_is_exist($the_type, $refer_id, $refer_type, $by_id, $by_type)
    {
        $search_data = array(
            'act_type' => $the_type,
            'act_refer_id' => $refer_id,
            'act_refer_type' => $refer_type,
            'act_by_id' => $by_id,
            'act_by_type' => $by_type,
        );
        $query = $this->db->get_where('activity_history', $search_data);
        if ($query->num_rows() == 0)
        {
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function activity_rating_is_exist($refer_id, $refer_type)
    {
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            $user_id = $this->ion_auth->user()->row()->id;           
            return $this->activity_check_is_exist('rating', $refer_id, $refer_type, $user_id, $this->config->item('group_id_user'));;
        }
    }
    
    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function activity_rating($refer_id, $refer_type, $rating)
    {
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            $user_id = $this->ion_auth->user()->row()->id;
            $this->activity_check_and_insert('rating', $refer_id, $refer_type, $user_id, $this->config->item('group_id_user'), 0, $rating);
            return TRUE;
        }
    }
    
    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function activity_rating_this_user($refer_id, $refer_type)
    {
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            $user_id = $this->ion_auth->user()->row()->id;
            $the_data = array(
                'act_type' => 'rating',
                'act_refer_id' => $refer_id,
                'act_refer_type' => $refer_type,
                'act_by_id' => $user_id,
                'act_by_type' => $this->config->item('group_id_user'),
            );
            $query = $this->db->get_where('activity_history', $the_data);
            if($query->num_rows()==1){
                $result = $query->row_array();
                return $result['rating'];
            }
        }
        return 'NA';
    }
    
    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function activity_rating_average($refer_id, $refer_type)
    {
        $query = $this->db->get_where('activity_history', array('act_type' => 'rating', 'act_refer_id' => $refer_id, 'act_refer_type' => $refer_type));
        $rate_count = $query->num_rows();
        $total_rate = 0;
        foreach ($query->result_array() as $row)
        {
            $total_rate += $row['rating'];
        }
        if ($rate_count == 0)
        {
            return 0;
        }
        else
        {
            return ($total_rate / $rate_count);
        }
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function activity_comment($refer_id, $refer_type, $comment)
    {
        if ($this->ion_auth->logged_in())
        {
            $login_id = $this->ion_auth->user()->row()->id;
            $login_type = $this->session->userdata('user_group_id');
            $this->activity_check_and_insert('comment', $refer_id, $refer_type, $login_id, $login_type, 1, NULL, $comment);
            return TRUE;
        }
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function activity_comment_count($refer_id, $refer_type, $want_array = 0)
    {
        $this->db->order_by("act_history_id", "asc"); 
        $query = $this->db->get_where('activity_history', array('act_type' => 'comment', 'act_refer_id' => $refer_id, 'act_refer_type' => $refer_type, 'hide_flag' => 0));

        if ($want_array == 0)
        {
            return $query->num_rows();
        }
        else
        {
            return $query->result_array();
        }
    }
    
    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function activity_like_is_exist($refer_id, $refer_type)
    {
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            $user_id = $this->ion_auth->user()->row()->id;           
            return $this->activity_check_is_exist('like', $refer_id, $refer_type, $user_id, $this->config->item('group_id_user'));;
        }
    }
    
    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function activity_like($refer_id, $refer_type)
    {
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            $user_id = $this->ion_auth->user()->row()->id;
            $this->activity_check_and_insert('like', $refer_id, $refer_type, $user_id, $this->config->item('group_id_user'));
        }
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function activity_like_count($refer_id, $refer_type, $want_array = 0)
    {
        $query = $this->db->get_where('activity_history', array('act_type' => 'like', 'act_refer_id' => $refer_id, 'act_refer_type' => $refer_type));

        if ($want_array == 0)
        {
            return $query->num_rows();
        }
        else
        {
            return $query->result_array();
        }
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function generate_like_link($refer_id, $refer_type)
    {
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            return "<span class='like-it' ><button onclick='click_like(" . $refer_id . ");'> Like </button> : " . $this->activity_like_count($refer_id, $refer_type) . " </span>";
        }else{
            return "Like : ". $this->activity_like_count($refer_id, $refer_type) . " ";
        }
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function generate_comment_link($refer_id, $refer_type)
    {
        return "Comment : ". $this->activity_comment_count($refer_id, $refer_type) . " ";
    }
    
    public function candie_history_insert($trans_conf_id, $get_from_table_id, $get_from_table = 'activity_history', $allow_duplicate = 0, $candie_overwrite = 0)
    {
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            $user_id = $this->ion_auth->user()->row()->id;
            $search_data = array(
                'user_id' => $user_id,
                'trans_conf_id' => $trans_conf_id,
                'get_from_table' => $get_from_table,
                'get_from_table_id' => $get_from_table_id,
            );                               
            $query = $this->db->get_where('candie_history', $search_data);
            if (($query->num_rows() == 0 && $allow_duplicate == 0) || $allow_duplicate != 0)
            {
                $config_query = $this->db->get_where('transaction_config', array('trans_conf_id' => $trans_conf_id, 'conf_type' => 'can'));
                if ($config_query->num_rows() == 1)
                {
                    $config_result = $config_query->row_array();                  
                    $candie_plus = 0;
                    $candie_minus = 0;
                    if($config_result['change_type'] == 'inc'){
                        $candie_plus = $config_result['amount_change'];
                    }else{
                        $candie_minus = $config_result['amount_change'];
                        //If is redeemption, need to minus candie
                        if($trans_conf_id == 8){
                            $candie_minus = $candie_overwrite;                          
                        }
                    }
                    $the_data = array(
                        'user_id' => $user_id,
                        'trans_conf_id' => $trans_conf_id,
                        'candie_plus' => $candie_plus,
                        'candie_minus' => $candie_minus,
                        'get_from_table' => $get_from_table,
                        'get_from_table_id' => $get_from_table_id,
                    );
                    $this->db->insert('candie_history', $the_data);
                }
            }
        }
    }
    
    public function candie_enough($user_id, $spend_candie = 0, $return_new_balance = 0)
    {
        $current_balance = $this->candie_check_balance($user_id);
        $new_balance = $current_balance - $spend_candie;
        if ($return_new_balance == 0)
        {
            if ($new_balance >= 0)
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            return $new_balance;
        }
    }

    public function candie_check_balance($user_id){
        $this->candie_balance_update($user_id);
        $history_query = $this->db->get_where('candie_balance', array('user_id' => $user_id));
        $current_balance = 0;
        $history_result = $history_query->result_array();
        foreach ($history_result as $history_row)
        {
            $current_balance += $history_row['balance'];
        }
        return $current_balance;
    }
      
    public function candie_balance_update($user_id, $month_id = NULL, $year = NULL)
    {
        $search_date = date_for_db_search($month_id,$year);

        $history_condition = "trans_time like '%" . $search_date . "%'";
        $history_search_data = array(
            'user_id' => $user_id,
        );
        $this->db->where($history_condition);
        $history_query = $this->db->get_where('candie_history', $history_search_data);
        $history_result = $history_query->result_array();
        if ($history_query->num_rows() != 0)
        {
            $monthly_balance = 0;
            foreach ($history_result as $history_row)
            {
                $monthly_balance = $monthly_balance + $history_row['candie_plus'] - $history_row['candie_minus'];
            }

            $balance_search_data = array(
                'user_id' => $user_id,
                'month_id' => $month_id,
                'year' => $year,
            );
            $balance_query = $this->db->get_where('candie_balance', $balance_search_data);
            if ($balance_query->num_rows() == 0)
            {
                $insert_data = array(
                    'user_id' => $user_id,
                    'balance' => $monthly_balance,
                    'month_id' => $month_id,
                    'year' => $year,
                );
                $this->db->insert('candie_balance', $insert_data);
            }
            else
            {
                $balance_result = $balance_query->row_array();
                $update_data = array(
                    'balance' => $monthly_balance,
                );
                $this->db->where('balance_id', $balance_result['balance_id']);
                $this->db->update('candie_balance', $update_data);
            }
        }
    }
    
    public function get_merchant_id_from_advertise($advertise_id)
    {
        $query = $this->db->get_where('advertise', array('advertise_id' => $advertise_id));
        if ($query->num_rows() == 1)
        {
            $result = $query->row_array();
            return $result['merchant_id'];
        }
        return 0;
    }

    public function get_merchant_id_from_mua($picture_id)
    {
        $query = $this->db->get_where('merchant_user_album', array('merchant_user_album_id' => $picture_id));
        if ($query->num_rows() == 1)
        {
            $result = $query->row_array();
            return $result['merchant_id'];
        }
        return 0;
    }
    
    public function transaction_history_insert($merchant_id, $trans_conf_id, $get_from_table_id, $get_from_table = 'activity_history', $allow_duplicate = 0, $amount_overwrite = 0)
    {
        $search_data = array(
            'merchant_id' => $merchant_id,
            'trans_conf_id' => $trans_conf_id,
            'get_from_table' => $get_from_table,
            'get_from_table_id' => $get_from_table_id,
        );
        $query = $this->db->get_where('transaction_history', $search_data);
        if (($query->num_rows() == 0 && $allow_duplicate == 0) || $allow_duplicate != 0)
        {
            $config_query = $this->db->get_where('transaction_config', array('trans_conf_id' => $trans_conf_id, 'conf_type' => 'bal'));
            if ($config_query->num_rows() == 1)
            {
                $config_result = $config_query->row_array();
                $amount_plus = 0;
                $amount_minus = 0;
                if ($config_result['change_type'] == 'dec')
                {
                    $amount_minus = $config_result['amount_change'];
                    //If is Banner, then the amount is key in by admin
                    if ($trans_conf_id == 17)
                    {
                        $amount_minus = $amount_overwrite;
                    }
                }
                else
                {
                    $amount_plus = $config_result['amount_change'];
                    //If is Top up, then the amount is key in by admin
                    if ($trans_conf_id == 19)
                    {
                        $amount_plus = $amount_overwrite;
                    }
                }
                $the_data = array(
                    'merchant_id' => $merchant_id,
                    'trans_conf_id' => $trans_conf_id,
                    'amount_plus' => $amount_plus,
                    'amount_minus' => $amount_minus,
                    'get_from_table' => $get_from_table,
                    'get_from_table_id' => $get_from_table_id,
                );
                $this->db->insert('transaction_history', $the_data);
            }
        }
    }

    public function merchant_balance_update($merchant_id, $month_id = NULL, $year = NULL)
    {
        if (empty($month_id))
        {
            $month_id = get_part_of_date('month');
        }
        if (empty($year))
        {
            $year = get_part_of_date('year');
        }
        $search_date = $year . '-' . str_pad($month_id, 2, "0", STR_PAD_LEFT);

        $history_condition = "trans_time like '%" . $search_date . "%'";
        $history_search_data = array(
            'merchant_id' => $merchant_id,
        );
        $this->db->where($history_condition);
        $history_query = $this->db->get_where('transaction_history', $history_search_data);
        $history_result = $history_query->result_array();
        if ($history_query->num_rows() != 0)
        {
            $monthly_balance = 0;
            foreach ($history_result as $history_row)
            {
                $monthly_balance = $monthly_balance + $history_row['amount_plus'] - $history_row['amount_minus'];
            }

            $balance_search_data = array(
                'merchant_id' => $merchant_id,
                'month_id' => $month_id,
                'year' => $year,
            );
            $balance_query = $this->db->get_where('merchant_balance', $balance_search_data);
            if ($balance_query->num_rows() == 0)
            {
                $insert_data = array(
                    'merchant_id' => $merchant_id,
                    'balance' => $monthly_balance,
                    'month_id' => $month_id,
                    'year' => $year,
                );
                $this->db->insert('merchant_balance', $insert_data);
            }
            else
            {
                $balance_result = $balance_query->row_array();
                $update_data = array(
                    'balance' => $monthly_balance,
                );
                $this->db->where('balance_id', $balance_result['balance_id']);
                $this->db->update('merchant_balance', $update_data);
            }
        }
    }
    
    public function merchant_this_month_transaction($merchant_id){
        $search_date = date_for_db_search();
        $condition = "trans_time like '%" . $search_date . "%'";
        $this->db->where($condition);
        $this->db->select("trans_conf_id, SUM(amount_plus) AS plus, SUM(amount_minus) AS minus, COUNT(trans_history_id) As quantity");
        $this->db->group_by('trans_conf_id');
        $this->db->order_by('trans_conf_id', 'asc');
        $query = $this->db->get_where('transaction_history', array('merchant_id' => $merchant_id));
        $result = $query->result_array();
        return $result;
    }
    
    public function merchant_check_balance($merchant_id, $exclude_this_month = 0){
        $this->candie_balance_update($merchant_id);
        if($exclude_this_month==0){
            $history_query = $this->db->get_where('merchant_balance', array('merchant_id' => $merchant_id));
        }else{
            $current_month = ltrim(get_part_of_date('month'), '0');
            $current_year = get_part_of_date('year');
            $condition = "(month_id !=" . $current_month . " or year !=" . $current_year . ")";
            $this->db->where($condition);          
            $history_query = $this->db->get_where('merchant_balance', array('merchant_id' => $merchant_id));
        }
        $current_balance = 0;
        $history_result = $history_query->result_array();
        foreach ($history_result as $history_row)
        {
            $current_balance += $history_row['balance'];
        }
        return number_format($current_balance,2);
    }
    
    public function user_redemption_done($redeem_id, $mark_expired = 0)
    {
        if (check_correct_login_type($this->group_id_merchant) || check_correct_login_type($this->group_id_supervisor))
        {
            $login_id = $this->ion_auth->user()->row()->id;
            $login_type = $this->session->userdata('user_group_id');
            
            $branch_id = 0;
            if(check_correct_login_type($this->group_id_supervisor)){
                $supervisor = $this->m_custom->getUser($login_id);
                $branch_id = $supervisor['su_branch_id'];
            }
            
            $status_id = $this->config->item('voucher_used');
            if ($mark_expired == 1)
            {
                $status_id = $this->config->item('voucher_expired');
            }

            $the_data = array(
                'status_id' => $status_id,
                'redeem_at_date' => get_part_of_date('all'),
                'redeem_at_branch' => $branch_id,               
                'done_by' => $login_id,
                'done_by_type' => $login_type,
            );
            $this->db->where('redeem_id', $redeem_id);
            if($this->db->update('user_redemption', $the_data)){
                return TRUE;
            }           
        }
        return FALSE;
    }
    
    public function user_redemption_insert($advertise_id)
    {
        $redeem_status = FALSE;
        $redeem_message = '';
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            $user_id = $this->ion_auth->user()->row()->id;
            $promotion_row = $this->getOneAdvertise($advertise_id);
            if ($promotion_row)
            {
                $voucher_candie = $promotion_row['voucher_candie'];
                $current_balance = $this->candie_check_balance($user_id);
                $new_balance = $this->candie_enough($user_id, $voucher_candie, 1);
                $voucher = $promotion_row['voucher'];
                $merchant_name = $this->display_users($promotion_row['merchant_id']);
                if ($new_balance >= 0)
                {
                    $the_data = array(
                        'user_id' => $user_id,
                        'advertise_id' => $advertise_id,
                        'status_id' => $this->config->item('voucher_active'),
                        'expired_date' => $promotion_row['end_time'],
                    );
                    $this->db->insert('user_redemption', $the_data);
                    $insert_id = $this->db->insert_id();
                    $this->candie_history_insert(8, $insert_id, 'user_redemption', 0, $voucher_candie);
                    $this->transaction_history_insert($promotion_row['merchant_id'], 18, $insert_id, 'user_redemption');
                    $redeem_message = "Success Redeem This Voucher <br/>" .
                            "Previous Candie : " . $current_balance . " <br/>" .
                            "Voucher Required Candie : " . $voucher_candie . " <br/>" .
                            "Remain Candie : " . $new_balance . " <br/>";
                    $redeem_status = TRUE;
                    $this->candie_balance_update($user_id);
                }
                else
                {
                    $redeem_message = "Not enough candie to redeem this voucher!<br/>" .
                            "Current Candie : " . $current_balance . " <br/>" .
                            "Voucher Required Candie : " . $voucher_candie . " <br/>";
                }
            }
        }
        $redeem_info = array(
            'redeem_status' => $redeem_status,
            'redeem_message' => $redeem_message,
            'redeem_voucher' => $voucher,
            'redeem_title' => $promotion_row['title'],
            'redeem_merchant' => $merchant_name,
            'redeem_expire' => displayDate($promotion_row['voucher_expire_date']),
            'redeem_email_subject' => $merchant_name . ' Keppo Voucher Success Redeem : '.$voucher,
        );
        return $redeem_info;
    }

    public function compare_before_update($the_table, $the_data, $id_column, $id_value)
    {
        $record = $this->get_one_table_record($the_table, $id_column, $id_value, 1);
        $result = array_diff_assoc($the_data, $record);
        if (empty($result))
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function simple_update($the_table, $the_data, $id_column, $id_value)
    {
        if ($this->compare_before_update($the_table, $the_data, $id_column, $id_value))
        {
            $this->db->where($id_column, $id_value);
            if ($this->db->update($the_table, $the_data))
            {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function insert_row_log($the_table, $new_id, $do_by = NULL, $do_by_type = NULL)
    {
        $the_data = array(
            'table_type' => $the_table,
            'table_row_id' => $new_id,
            'create_time' => get_part_of_date('all'),
            'create_by' => $do_by,
            'create_by_type' => $do_by_type,
        );
        $this->db->insert('table_row_activity', $the_data);
    }

    public function update_row_log($the_table, $the_id, $do_by = NULL, $do_by_type = NULL)
    {

        $query = $this->db->get_where('table_row_activity', array('table_type' => $the_table, 'table_row_id' => $the_id), 1);
        if ($query->num_rows() == 0)
        {
            $this->insert_row_log($the_table, $the_id, $do_by, $do_by_type);
            $query = $this->db->get_where('table_row_activity', array('table_type' => $the_table, 'table_row_id' => $the_id), 1);
        }
        $activity_row = $query->row();

        $the_data = array(
            'last_modify_by' => $do_by,
            'last_modify_by_type' => $do_by_type,
        );

        $this->db->where('activity_id', $activity_row->activity_id);
        $this->db->update('table_row_activity', $the_data);
    }

    public function remove_row_log($the_table, $the_id, $do_by = NULL, $do_by_type = NULL)
    {

        $query = $this->db->get_where('table_row_activity', array('table_type' => $the_table, 'table_row_id' => $the_id), 1);
        if ($query->num_rows() == 0)
        {
            $this->insert_row_log($the_table, $the_id, $do_by, $do_by_type);
            $query = $this->db->get_where('table_row_activity', array('table_type' => $the_table, 'table_row_id' => $the_id), 1);
        }
        $activity_row = $query->row();

        $the_data = array(
            'hide_time' => get_part_of_date('all'),
            'hide_by' => $do_by,
            'hide_by_type' => $do_by_type,
        );

        $this->db->where('activity_id', $activity_row->activity_id);
        $this->db->update('table_row_activity', $the_data);
    }

    public function get_merchant_monthly_promotion($merchant_id, $month=NULL, $year=NULL, $advertise_id = NULL)
    {
        if (empty($merchant_id))
        {
            return FALSE;
        }
        if (empty($month))
        {
            $month = get_part_of_date('month');
        }
        if (empty($year))
        {
            $year = get_part_of_date('year');
        }
        $get_by_advertise_id = 0;
        if($advertise_id != NULL){
            $query = $this->db->get_where('advertise', array('merchant_id' => $merchant_id, 'advertise_id' => $advertise_id), 1);
            if($query->num_rows() == 1){
                $get_by_advertise_id = 1;
            }
        }
        if($get_by_advertise_id == 0){
            $query = $this->db->get_where('advertise', array('merchant_id' => $merchant_id, 'month_id' => $month, 'year' => $year, 'advertise_type' => 'pro'), 1);
        }
        if ($query->num_rows() !== 1)
        {
            return FALSE;
        }

        return $query->row_array();
    }
    
    public function get_merchant_today_hotdeal($merchant_id, $counter_only = 0, $date = NULL)
    {
        if (!IsNullOrEmptyString($date))
        {
            $search_date = $date;
        }
        else
        {
            $search_date = date(format_date_server());
        }
        $condition = "start_time like '%" . $search_date . "%'";
        $this->db->where('advertise_type', 'hot');
        $this->db->where($condition);
        $this->db->where('hide_flag', 0);
        $query = $this->db->get_where('advertise', array('merchant_id' => $merchant_id));
        if ($query->num_rows() == 0)
        {
            if ($counter_only == 0)
            {
                return FALSE;
            }
            else
            {
                return 0;
            }
        }
        if ($counter_only == 0)
        {
            return $query->result_array();
        }
        else
        {
            return $query->num_rows();
        }
    }

    //wanted display: name, description, short
    public function display_users_groups($groups_id, $wanted_display)
    {
        $return = '';
        $query = $this->db->get_where('groups', array('id' => $groups_id));
        if ($query->num_rows() == 1)
        {
            $result = $query->row_array();
            $return = $result[$wanted_display];
        }
        return $return;
    }

}
