<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class Albert_model extends CI_Model
{
    //GET USER
    public function get_users($users_id = NULL)
    {
        //QUERY
        $this->db->select('profile_image, first_name, last_name, us_blog_url, us_instagram_url, us_facebook_url');
        $this->db->from('users');
        //WHERE id
        if($users_id)
        {
            $this->db->where('id',$users_id);
        }
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    //GET ACTIVITY HISTORY INNER JOIN ADVERTISE
    public function get_activity_history_inner_join_advertise($users_id = NULL) 
    {
        //QUERY
        $this->db->select("*");
        $this->db->from('advertise');
        $this->db->join('activity_history', 'advertise.advertise_id = activity_history.act_refer_id', 'inner');
        //WHERE act_by_id
        if($users_id)
        {
            $this->db->where('act_by_id', $users_id);
        }
        $this->db->group_by("advertise_id"); 
        $query = $this->db->get();
        return $query;
    }
}