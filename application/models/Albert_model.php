<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class Albert_model extends CI_Model
{
    //GET USER
    public function get_user($user_id = NULL)
    {
        //QUERY
        $this->db->select('profile_image, first_name, last_name, us_blog_url, us_instagram_url, us_facebook_url');
        $this->db->from('users');
        //WHERE id
        if($user_id)
        {
            $this->db->where('id',$user_id);
        }
        $query = $this->db->get();
        //RETURN
        return $query;
    }
    
    //GET ACTIVITY HISTORY
    public function get_activity_history()
    {
        //QUERY
    }
}