<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class Albert_model extends CI_Model
{
    /* READ MAIN CATEGORY
    ***************************************************/
    public function read_main_category($get_special = 0)
    {
        //QUERY
        $this->db->select('category_id, category_label');
        $this->db->from('category');
        $this->db->where('main_category_id', NULL);
        //WHERE
        if ($get_special == 0)
        {
            $this->db->where('hide_special', 0);
        }
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /* READ SUB CATEGORY
    ***************************************************/
    public function read_sub_category()
    {
        //QUERY
        $this->db->select('category_id, category_label');
        $this->db->from('category');
        $this->db->where('main_category_id', !NULL);
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
    
    /* READ MERCHANT BY MAIN CATEGORY ID
    ***************************************************/
    public function read_merchant($where)
    {
        //QUERY
        $this->db->select('company, slug');
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
    
    /* READ USER
    ***************************************************/
    public function read_users($where)
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
        
    /* READ FOLLOWER
    *************************************************************/
    public function read_follower($where)
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
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /* READ FOLLOWER MERCHANT
    *************************************************************/
    public function read_follower_merchant($where)
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
        $main_group_id_array = array(3,4);
        $this->db->where_in('follower_group_id', $main_group_id_array); 
        $query = $this->db->get();
        //RETURN
        return $query;
    }
        
    /* READ FOLLOWING
    *************************************************************/
    public function read_following($where)
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
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /* READ FOLLOWING MERCHANT
    *************************************************************/
    public function read_following_merchant($where)
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
