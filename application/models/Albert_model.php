<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class Albert_model extends CI_Model
{
    /* GET MAIN CATEGORY
    ***************************************************/
    public function get_main_category()
    {
        //QUERY
        $this->db->select('category_id, category_label');
        $this->db->from('category');
        $this->db->where('main_category_id', NULL);
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /* GET SUB CATEGORY
    ***************************************************/
    public function get_sub_category()
    {
        //QUERY
        $this->db->select('category_id, category_label');
        $this->db->from('category');
        $this->db->where('main_category_id', !NULL);
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    /* GET BANNER
    ***************************************************/
    public function get_banner($where)
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
    
    /* GET MERCHANT BY MAIN CATEGORY ID
    ***************************************************/
    public function get_merchant($where)
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
    
    /* GET USER
    ***************************************************/
    public function get_users($where)
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
    
    /* GET USER_FOLLOW
    *************************************************************/
    public function get_user_follow($where)
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
    
    /* GET USER_FOLLOW INNER JOIN USERS
    *************************************************************/
    public function get_user_follow_inner_join_users($where)
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
    
    /* GET ACTIVITY_HISTORY INNER JOIN ADVERTISE
    *************************************************************/
    public function get_activity_history_inner_join_advertise($where) 
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