<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class Albert_model extends CI_Model
{
    /* READ MAIN CATEGORY
    ***************************************************/
    public function read_main_category()
    {
        //QUERY
        $this->db->select('category_id, category_label');
        $this->db->from('category');
        $this->db->where('main_category_id', NULL);
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
        $this->db->select('profile_image, first_name, last_name, us_blog_url, us_instagram_url, us_facebook_url');
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
    public function create_user_follow()
    {
        $follow_from_id = $this->input->get_post('follow_from_id');
        $follow_to_id = $this->input->get_post('follow_to_id');
        $data = array(
            'follow_from_id' => $follow_from_id,
            'follow_to_id' => $follow_to_id
        );
        return $this->db->insert('user_follow', $data);
    }
    
    /* DELETE USER_FOLLOW
    *************************************************************/
    public function delete_user_follow()
    {
        $follow_from_id = $this->input->get_post('follow_from_id');
        $follow_to_id = $this->input->get_post('follow_to_id');
        $this->db->where('follow_from_id', $follow_from_id);
        $this->db->where('follow_to_id', $follow_to_id);
        $this->db->delete('user_follow');
    }
    
    /* EXISTS USER_FOLLOW
    *************************************************************/
    public function exists_user_follow($follow_from_id, $follow_to_id)
    {
        //QUERY
        $this->db->select('*');
        $this->db->from('user_follow');
        $this->db->where('follow_from_id', $follow_from_id);
        $this->db->where('follow_to_id', $follow_to_id);
        $query = $this->db->get();
        $num_rows = $query->num_rows();
        return $num_rows;
    }
    
    /* READ FOLLOWER
    *************************************************************/
    public function read_follower($where)
    {
        //QUERY
        $this->db->select('*');
        $this->db->from('user_follow');
        $this->db->join('users', 'user_follow.follow_from_id = users.id', 'inner');
        //WHERE
        if($where)
        {
            $this->db->where($where);
        }
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
        $this->db->join('users', 'user_follow.follow_to_id = users.id', 'inner');
        //WHERE
        if($where)
        {
            $this->db->where($where);
        }
        $query = $this->db->get();
        //RETURN
        return $query;
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
}