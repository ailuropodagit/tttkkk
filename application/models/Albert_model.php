<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class Albert_model extends CI_Model
{
    /* READ ADVERTISE HOT DEAL SUGGESTION
    ***************************************************/
    public function read_advertise_hot_deal_suggestion($array_sub_category_id)
    {
        //QUERY        
        $this->db->select('*');
        $this->db->from('advertise');
        $this->db->where('advertise_type', 'hot');
        $this->db->where('hide_flag', '0');
        $this->db->where('end_time >=', date("Y-m-d H:i:s"));
        $this->db->where_in('sub_category_id', $array_sub_category_id);
        $this->db->limit($this->config->item('suggest_list_number'));
        $this->db->order_by('category_id', 'RANDOM');
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /* READ ADVERTISE REDEMPTION SUGGESTION
    ***************************************************/
    public function read_advertise_redemption_suggestion($array_sub_category_id)
    {      
        //QUERY        
        $this->db->select('*');
        $this->db->from('advertise');
        $this->db->where('advertise_type', 'pro');
        $this->db->where('hide_flag', '0');
        $this->db->where('end_time >=', date("Y-m-d H:i:s"));
        $this->db->where_in('sub_category_id', $array_sub_category_id);
        $this->db->limit($this->config->item('suggest_list_number'));
        $this->db->order_by('category_id', 'RANDOM');
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /* READ CATEGORY
    ***************************************************/
    public function read_category($where)
    {
        //QUERY
        $this->db->select('*');
        $this->db->from('category');
        //WHERE
        if ($where)
        {
            $this->db->where($where);
        }
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /* READ MAIN CATEGORY
    ***************************************************/
    public function read_main_category()
    {
        //QUERY
        $this->db->select('category_id, category_label');
        $this->db->from('category');
        $this->db->where('main_category_id', NULL);
        $this->db->where('hide_special !=', '1');
        $this->db->where('hide_flag !=', '1');
        $this->db->order_by('category_label');
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /* READ SUB CATEGORY
    ***************************************************/
    public function read_sub_category($where)
    {
        //QUERY
        $this->db->select('category_id, category_label');
        $this->db->from('category');
        $this->db->where('hide_special !=', '1');
        //WHERE
        if(!empty($where))
        {
            $this->db->where($where);
        }
        else
        {
            $this->db->where('main_category_id', !NULL);
        }
        $this->db->order_by('category_label');
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /* READ BANNER
    ***************************************************/
    public function read_banner($where)
    {
        //QUERY
        $this->db->select('banner_image, banner_url');
        $this->db->from('banner');
        //WHERE
        if(!empty($where))
        {
            $this->db->where($where);
        }
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /* READ MERCHANT
    ***************************************************/
    public function read_merchant($where)
    {
        //QUERY
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('main_group_id', 3);
        //WHERE
        if($where)
        {
            $this->db->where($where);
        }
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /* READ MERCHANT GROUP SUB CATEGORY
    ***************************************************/
    public function read_merchant_in($where, $where_in)
    {
        //QUERY
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('main_group_id', 3);
        //WHERE
        if($where)
        {
            $this->db->where($where);
        }
        //WHERE IN
        if($where_in)
        {
            $this->db->where_in('me_sub_category_id', $where_in);
        }
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /*  READ MERCHANT MAIN 
    ***************************************************/
    public function read_merchant_supervisor_as_merchant($where) 
    {
        //QUERY 
        $this->db->select('*');
        $this->db->from('users');
        $main_group_id_array = array(3,4);
        $this->db->where_in('main_group_id', $main_group_id_array);
        //WHERE
        if($where)
        {
            $this->db->where($where);
        }
        $query = $this->db->get();
        $num_rows = $query->num_rows();
        if($num_rows != 0)
        {
            $main_group_id = $query->row()->main_group_id;
            if($main_group_id == 3)
            {
                $user_id = $query->row()->id;
                $this->db->select('*');
                $this->db->from('users');
                $this->db->where('id', $user_id);
                $query = $this->db->get();
            }
            if($main_group_id == 4)
            {
                $su_merchant_id = $query->row()->su_merchant_id;
                $this->db->select('*');
                $this->db->from('users');
                $this->db->where('id', $su_merchant_id);
                $query = $this->db->get();
            }
        }
        //RETURN
        RETURN $query;
    }
        
    /* READ USER
    ***************************************************/
    public function read_user($where)
    {
        //QUERY
        $this->db->select('*');
        $this->db->from('users');
        //WHERE
        if($where)
        {
            $this->db->where($where);
        }
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /* UPDATE USERS
    ***************************************************/
    public function update_user($where, $data)
    {
        //WHERE
        if($where)
        {
            $this->db->where($where);
        }
        //UPDATE
        if($data)
        {
            $this->db->update('users', $data);
        }
    }
    
    /* CREATE USER_FOLLOW
    *************************************************************/
    public function create_user($data)
    {
        return $this->db->insert('users', $data);
    }
    
    /* READ BLOGGER
    ***************************************************/
    public function read_blogger($search, $search_type = NULL)
    {
        //QUERY
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('us_is_blogger =', "1");
        $this->db->where('remove_flag =', "0");
        //SEARCH
        if($search)
        {
            $this->db->where("concat_ws(' ', first_name, last_name) LIKE", '%'.$search.'%');
            $this->db->or_where('us_blog_url LIKE', '%'.$search.'%');
        }
        $this->db->order_by('first_name', 'asc');
        $query = $this->db->get();    
        
        //TO SEARCH IS THERE ANY USER HAVE THIS PHOTOGRAPHER TYPE
        if($search_type){
            $query_process = $query->result_array();
            $user_have_this_type = array();
            foreach($query_process as $row){
                $user_id = $row['id'];
                $result_type_list = $this->m_custom->get_list_of_allow_id('many_to_many', 'many_parent_id', $user_id, 'many_child_id', 'many_type', 'blogger');
                if (check_allowed_list($result_type_list, $search_type))
                {
                    $user_have_this_type[] = $user_id;
                }
                if (!empty($user_have_this_type))
                {
                    $this->db->where_in('id', $user_have_this_type);
                }
                else
                {
                    $this->db->where_in('id', '0');  //If don't have any user is belong to this type
                }
                
                //Generate a new query to overwrite previous search result
                $this->db->select('*');
                $this->db->from('users');
                $this->db->order_by('first_name', 'asc');
                $query = $this->db->get();
            }
        }
        
        //RETURN
        return $query;
    }
    
    /* READ PHOTOGRAPHER
    ***************************************************/
    public function read_photographer($search, $search_type = NULL)
    {
        //QUERY
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('us_is_photographer =', "1");
        $this->db->where('remove_flag =', "0");
        //SEARCH
        if($search)
        {
            $this->db->where("concat_ws(' ', first_name, last_name) LIKE", '%'.$search.'%');
            $this->db->or_where('us_photography_url LIKE', '%'.$search.'%');
        }
        $this->db->order_by('first_name', 'asc');
        $query = $this->db->get();   
        
        //TO SEARCH IS THERE ANY USER HAVE THIS PHOTOGRAPHER TYPE
        if($search_type){
            $query_process = $query->result_array();
            $user_have_this_type = array();
            foreach($query_process as $row){
                $user_id = $row['id'];
                $result_type_list = $this->m_custom->get_list_of_allow_id('many_to_many', 'many_parent_id', $user_id, 'many_child_id', 'many_type', 'photography');
                if (check_allowed_list($result_type_list, $search_type))
                {
                    $user_have_this_type[] = $user_id;
                }
                if (!empty($user_have_this_type))
                {
                    $this->db->where_in('id', $user_have_this_type);
                }
                else
                {
                    $this->db->where_in('id', '0');  //If don't have any user is belong to this type
                }
                
                //Generate a new query to overwrite previous search result
                $this->db->select('*');
                $this->db->from('users');
                $this->db->order_by('first_name', 'asc');
                $query = $this->db->get();
            }
        }
        
        //RETURN
        return $query;
    }
    
    /* READ USER_ALBUM
    *************************************************************/
    public function read_user_album($where)
    {
        //QUERY
        $this->db->select('*');
        $this->db->order_by("user_album_id", "desc");
        $this->db->from('user_album');
        //WHERE
        if($where)
        {
            $this->db->where($where);
        }
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /* READ MERCHANT_USER_ALBUM
    *************************************************************/
    public function read_merchant_user_album($where)
    {
        //QUERY
        $this->db->select('*');
        $this->db->order_by("merchant_user_album_id", "desc");
        $this->db->from('merchant_user_album');
        //WHERE
        if($where)
        {
            $this->db->where($where);
        }
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /* READ USER_FOLLOW
    *************************************************************/
    public function read_user_follow($where)
    {
        //QUERY 
        $this->db->select('*');
        $this->db->from('user_follow');
        //WHERE
        if($where)
        {
            $this->db->where($where);
        }
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /* CREATE USER_FOLLOW
    *************************************************************/
    public function create_user_follow($data)
    {
        return $this->db->insert('user_follow', $data);
    }
    
    /* DELETE USER_FOLLOW
    *************************************************************/
    public function delete_user_follow($where)
    {
        $this->db->where($where);
        $this->db->delete('user_follow');
    }
    
    /* FOLLOWER COUNT
    *************************************************************/
    public function follower_count($user_id) 
    {
        //QUERY
        $this->db->from('user_follow');
        $this->db->where('following_main_id', $user_id);
        //COUNT
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /* USER FOLLOWER COUNT
    *************************************************************/
    public function user_follower_count($user_id) 
    {
        //QUERY
        $this->db->from('user_follow');
        $this->db->where('following_main_id', $user_id);
        $this->db->where('follower_group_id', '5');
        //COUNT
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /* MERCHANT FOLLOWER COUNT
    *************************************************************/
    public function merchant_follower_count($user_id) 
    {
        //QUERY
        $this->db->from('user_follow');
        $this->db->where('following_main_id', $user_id);
        $main_group_id_array = array('3','4');
        $this->db->where_in('follower_group_id', $main_group_id_array);
        //COUNT
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /* FOLLOWING COUNT
    *************************************************************/
    public function following_count($user_id) 
    {
        //QUERY
        $this->db->from('user_follow');
        $this->db->where('follower_main_id', $user_id);
        //COUNT
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /* USER FOLLOWING COUNT
    *************************************************************/
    public function user_following_count($user_id) 
    {
        //QUERY
        $this->db->from('user_follow');
        $this->db->where('follower_main_id', $user_id);
        $this->db->where('following_group_id', '5');
        //COUNT
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /* FOLLOWING COUNT
    *************************************************************/
    public function merchant_following_count($user_id) 
    {
        //QUERY
        $this->db->from('user_follow');
        $this->db->where('follower_main_id', $user_id);
        $main_group_id_array = array('3','4');
        $this->db->where_in('following_group_id', $main_group_id_array);
        //COUNT
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /* READ FOLLOWER
    *************************************************************/
    public function read_follower($where, $search)
    {
        //QUERY
        $this->db->select('*');
        $this->db->from('user_follow');
        $this->db->join('users', 'user_follow.follower_id = users.id', 'inner');
        //WHERE
        if($where)
        {
            $this->db->where($where);
        }
        //SEARCH
        if($search)
        {
            $this->db->where("concat_ws(' ', first_name, last_name) LIKE", '%'.$search.'%');
        }
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /* READ FOLLOWER MERCHANT
    *************************************************************/
    public function read_follower_merchant($where, $search)
    {
        //QUERY
        $this->db->select('*');
        $this->db->from('user_follow');
        $this->db->join('users', 'user_follow.follower_main_id = users.id', 'inner');
        //WHERE
        if($where)
        {
            $this->db->where($where);
        }
        //SEARCH
        if($search)
        {
            $this->db->where('company LIKE', '%'.$search.'%');
        }
        $main_group_id_array = array(3,4);
        $this->db->where_in('follower_group_id', $main_group_id_array); 
        $query = $this->db->get();
        //RETURN
        return $query;
    }
        
    /* READ FOLLOWING
    *************************************************************/
    public function read_following($where, $search)
    {
        //QUERY
        $this->db->select('*');
        $this->db->from('user_follow');
        $this->db->join('users', 'user_follow.following_id = users.id', 'inner');
        //WHERE
        if($where)
        {
            $this->db->where($where);
        }
        //SEARCH
        if($search)
        {
            $this->db->where("concat_ws(' ', first_name, last_name) LIKE", '%'.$search.'%');
        }
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /* READ FOLLOWING MERCHANT
    *************************************************************/
    public function read_following_merchant($where, $search)
    {
        //QUERY
        $this->db->select('*');
        $this->db->from('user_follow');
        $this->db->join('users', 'user_follow.following_main_id = users.id', 'inner');
        //WHERE
        if($where)
        {
            $this->db->where($where);
        }
        //SEARCH
        if($search)
        {
            $this->db->where('company LIKE', '%'.$search.'%');
        }
        $main_group_id_array = array(3,4);
        $this->db->where_in('following_group_id', $main_group_id_array); 
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /* READ CANDIE_BALANCE
    *************************************************************/
    public function read_candie_balance($where)
    {
        //QUERY
        $this->db->select('*');
        $this->db->from('candie_balance');
        if($where)
        {
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query;
    }
    
    /* INSERT CANDIE_BALANCE
    *************************************************************/
    public function insert_candie_balance($data)
    {
        $this->db->insert('candie_balance', $data);
    }
    
    /* UPDATE CANDIE BALANCE INVITE FRIEND COUNT INCREMENT
    *************************************************************/
    public function update_candie_balance_invite_friend_count_increment($where)
    {
        $this->db->set('invite_friend_count', 'invite_friend_count+1', FALSE);
        if($where)
        {
            $this->db->where($where);
        }
        $this->db->update('candie_balance');
    }    
    
    /* READ USER INVITE FRIEND
    * *************************************************************/
    public function read_user_invite_friend($where)
    {
        $this->db->select('*');
        $this->db->from('user_invite_friend');
        if($where)
        {
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query;
    }
    
    /* INSERT USER INVITE FRIEND
    *************************************************************/
    public function insert_user_invite_friend($data)
    {
        $this->db->insert('user_invite_friend', $data);
    }
        
    /* READ ACTIVITY_HISTORY INNER JOIN ADVERTISE
    *************************************************************/
    public function read_activity_history_inner_join_advertise($where) 
    {
        //QUERY
        $this->db->select("*");
        $this->db->from('advertise');
        $this->db->join('activity_history', 'advertise.advertise_id = activity_history.act_refer_id', 'inner');
        //WHERE
        if($where)
        {
            $this->db->where($where);
        }
        $this->db->group_by("advertise_id"); 
        $query = $this->db->get();
        return $query;
    }
    
    /* READ SUB CATEGORY WITH MERCHANT
    ***************************************************/
    public function read_sub_category_with_merchant($where)
    {
        //QUERY 1
        $this->db->select("*");
        $this->db->from('users');
        $this->db->join('category', 'users.me_sub_category_id = category.category_id', 'inner');
        $this->db->where('users.main_group_id', 3);
        $this->db->where('category.hide_flag', 0);
        //WHERE
        if($where)
        {
            $this->db->where($where);
        }
        $this->db->group_by('users.me_sub_category_id');
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /* READ STATIC OPTION MONTH ASSOCIATIVE ARRAY
    *************************************************************/
    public function read_static_option_month_associative_array() {
        //QUERY
        $this->db->select("*");
        $this->db->from("static_option");
        $this->db->where('option_type', 'month');
        $query = $this->db->get();
        $associative_array = array();
        foreach ($query->result_array() as $row)
        {
            $associative_array[$row['option_value']] = $row['option_text'];
        }
        return $associative_array;
    }
    
    /* READ STATIC OPTION RACE ASSOCIATIVE ARRAY
    *************************************************************/
    public function read_static_option_race_associative_array() {
        //QUERY
        $this->db->select("*");
        $this->db->from("static_option");
        $this->db->where('option_type', 'race');
        $query = $this->db->get();
        $associative_array = array();
        $associative_array['0'] = 'Please Select';
        foreach ($query->result_array() as $row)
        {
            $associative_array[$row['option_id']] = $row['option_text'];
        }
        return $associative_array;
    }
    
    /* READ STATIC OPTION GENDER ASSOCIATIVE ARRAY
    *************************************************************/
    public function read_static_option_gender_associative_array() {
        //QUERY
        $this->db->select("*");
        $this->db->from("static_option");
        $this->db->where('option_type', 'gender');
        $query = $this->db->get();
        $associative_array = array();
        $associative_array['0'] = 'Please Select';
        foreach ($query->result_array() as $row)
        {
            $associative_array[$row['option_id']] = $row['option_text'];
        }
        return $associative_array;
    }
}
