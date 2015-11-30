<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_admin extends CI_Model
{

    function getAllTopup($merchant_id = 0)
    {
        if ($merchant_id != 0)
        {
            $this->db->where('merchant_id', $merchant_id);
        }

        $this->db->order_by('topup_time', 'desc');
        $this->db->from('merchant_topup');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function check_is_any_admin($check_worker_role = NULL)
    {
        $have_role = 0;
        if (check_correct_login_type($this->config->item('group_id_admin')) || check_correct_login_type($this->config->item('group_id_worker')))
        {
            $have_role = 1;
        }
        if (!IsNullOrEmptyString($check_worker_role))
        {
            $have_role = $this->m_admin->check_worker_role($check_worker_role);
        }
        return $have_role;
    }

    function getAllWorker()
    {
        $this->db->order_by('first_name');
        $query = $this->db->get_where('users', array('main_group_id' => $this->config->item('group_id_worker')));
        $result = $query->result_array();
        return $result;
    }

    function banner_select($ignore_hide = 0, $want_count = 0)
    {
        if ($ignore_hide == 0)
        {
            $this->db->where('hide_flag', 0);
        }
        else if ($ignore_hide == 1)  //show expire only
        {
            $this->db->where('end_time <=', get_part_of_date('all'));
            $this->db->where('hide_flag', 0);
        }
        else if ($ignore_hide == 2)
        {
            $this->db->where('hide_flag', 1);
        }
        $this->db->order_by('banner_position');
        $query = $this->db->get_where('banner');
        $result = $query->result_array();

        if ($want_count == 1)
        {
            return $query->num_rows();
        }
        else
        {
            return $result;
        }
    }

//    $banner_info = $this->m_admin->banner_select_one(101);
//    $banner_image_url = $banner_info['banner_image_url'];
//    $banner_website_url = $banner_info['banner_website_url'];
//    $default_image_url = $banner_info['default_image_url'];
    function banner_select_one($banner_position, $full_info = 0)
    {
        $search_data = array(
            //'category_id' => $category_id,
            'banner_position' => $banner_position,
            'hide_flag' => 0,
        );

        $query = $this->db->get_where('banner', $search_data, 1);
        if ($query->num_rows() == 0)
        {
            return FALSE;
        }
        else
        {
            $result = $query->row_array();

            if ($full_info == 0)
            {
                $final_result = array(
                    'banner_id' => $result['banner_id'],
                    'merchant_id' => $result['merchant_id'],      
                    'banner_image' => $result['banner_image'],                   
                    'banner_image_url' => base_url() . $this->config->item('album_banner') . $result['banner_image'],
                    'banner_website_url' => $result['banner_url'],
                    'banner_position' => $result['banner_position'],
                    'banner_position_name' => $this->m_custom->display_static_option($result['banner_position']),     
                    'default_image_url' => base_url() . $this->config->item('album_banner') . $this->m_custom->display_static_option($result['banner_position'], 'option_desc'),
                );                  
            }
            else
            {
                $additional_info = array(
                    'banner_image_url' => base_url() . $this->config->item('album_banner') . $result['banner_image'],
                    'banner_website_url' => $result['banner_url'],
                    'banner_position_name' => $this->m_custom->display_static_option($result['banner_position']),
                    'default_image_url' => base_url() . $this->config->item('album_banner') . $this->m_custom->display_static_option($result['banner_position'], 'option_desc'),
                );
                $final_result = $result + $additional_info;
            }
            return $final_result;
        }
    }

    public function check_worker_role($check_worker_role = NULL)
    {
        $have_role = 0;
        if (check_correct_login_type($this->config->item('group_id_admin')))
        {
            $have_role = 1;
        }
        else if (check_correct_login_type($this->config->item('group_id_worker')))
        {
            if (!IsNullOrEmptyString($check_worker_role))
            {
                $login_id = $this->ion_auth->user()->row()->id;
                $query = $this->db->get_where('many_to_many', array('many_type' => 'admin_role', 'many_parent_id' => $login_id, 'many_child_id' => $check_worker_role));
                if ($query->num_rows() > 0)
                {
                    $have_role = 1;
                }
            }
        }
        return $have_role;
    }

    //todo to do half, don't have update yet
    public function trans_extra_bonus_candie($user_id, $amount_change, $trans_remark = NULL, $is_update = 0, $trans_date = NULL, $edit_id = NULL)
    {
        if ($this->m_admin->check_is_any_admin(74))
        {
            $login_id = $this->ion_auth->user()->row()->id;
            if ($is_update == 0)
            {
                $new_id = $this->m_custom->trans_extra_insert($user_id, 31, $amount_change, $login_id, NULL, $trans_date, NULL, $trans_remark);
                $this->m_user->candie_history_insert(31, $new_id, 'transaction_extra', 0, $amount_change, $user_id);
                return $new_id;
            }
            else
            {
                if (check_is_positive_numeric($edit_id))
                {
                    return $this->m_custom->trans_extra_update($edit_id, $amount_change, NULL, $trans_date, NULL, $trans_remark);
                }
            }
        }
    }

    //todo to do half, don't have update yet
    public function trans_extra_balance_adjust($user_id, $amount_change, $trans_remark = NULL, $is_update = 0, $trans_date = NULL, $edit_id = NULL)
    {
        if ($this->m_admin->check_is_any_admin(75))
        {
            $login_id = $this->ion_auth->user()->row()->id;
            if ($is_update == 0)
            {
                $new_id = $this->m_custom->trans_extra_insert($user_id, 23, $amount_change, $login_id, NULL, $trans_date, NULL, $trans_remark);
                $this->m_user->user_trans_history_insert($user_id, 23, $new_id, 'transaction_extra', 0, $amount_change);
                return $new_id;
            }
            else
            {
                if (check_is_positive_numeric($edit_id))
                {
                    return $this->m_custom->trans_extra_update($edit_id, $amount_change, NULL, $trans_date, NULL, $trans_remark);
                }
            }
        }
    }
    
    public function merchant_low_balance_count($want_count = 0){
        
        $query = $this->db->get_where('users', array('main_group_id' => $this->config->item('group_id_merchant')));
        $the_result = $query->result_array();

        $low_balance_count = 0;
        $return_final = array();
        foreach ($the_result as $row)
        {
            $merchant_balance = $this->m_merchant->merchant_check_balance($row['id']);
            $merchant_minimum_balance = $this->m_custom->web_setting_get('merchant_minimum_balance', 'set_decimal');
            if ($merchant_balance < $merchant_minimum_balance)
            {
                $low_balance_count++;
                $return_final[] = $row;
            }
        }
        if($want_count == 1){
            return $low_balance_count;
        }else{
            return $return_final;
        }
    }
    
    public function banner_expired_count(){
        $this->db->where('end_time <=', get_part_of_date('all'));
        $query = $this->db->get_where('banner', array('hide_flag' => 0));
        return $query->num_rows();
    }
    
    public function getAdminAnalysisReportMerchant($month_id = NULL, $year = NULL)
    {          
        $return = array();
        $start_time = getFirstLastTime($year, $month_id);
        $end_time = getFirstLastTime($year, $month_id, 'last');
        $start_timestamp = strtotime($start_time);
        $end_timestamp = strtotime($end_time);
        $group_id_user = $this->config->item('group_id_merchant');
              
        $condition = "created_on >= '" . $start_timestamp . "' AND created_on <= '" . $end_timestamp . "'";
        $this->db->where($condition);
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where(array('main_group_id' => $group_id_user));
        $query_new = $this->db->get();
        $result_new = $query_new->result_array();
               
        $new_count_active = 0;
        $new_count_hide = 0;
        foreach($result_new as $row){
            if($row['hide_flag'] == 0){
                $new_count_active++;
            }else{
                $new_count_hide++;
            }
        }

        $return['new_count'] = $query_new->num_rows();      
        $return['new_count_active'] = $new_count_active;
        $return['new_count_hide'] = $new_count_hide;
        
        //var_dump($result_new);
        
        $condition = "created_on < '" . $start_timestamp . "'";
        $this->db->where($condition);
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where(array('main_group_id' => $group_id_user));
        $query_old = $this->db->get();
        $result_old = $query_old->result_array();       
        
        $old_count_active = 0;
        $old_count_hide = 0;
        foreach($result_old as $row){
            if($row['hide_flag'] == 0){
                $old_count_active++;
            }else{
                $old_count_hide++;
            }
        }
        
        $return['old_count'] = $query_old->num_rows();       
        $return['old_count_active'] = $old_count_active;
        $return['old_count_hide'] = $old_count_hide;
        
        //var_dump($result_old);      
        //var_dump($return);
        return $return;
    }
    
    public function getAdminAnalysisReportUser($the_type, $month_id = NULL, $year = NULL)
    {          
        $return = array();
        $start_time = getFirstLastTime($year, $month_id);
        $end_time = getFirstLastTime($year, $month_id, 'last');
        $start_timestamp = strtotime($start_time);
        $end_timestamp = strtotime($end_time);
        $group_id_user = $this->config->item('group_id_user');
        
        $type_list = $this->m_custom->get_static_option_array($the_type, NULL, NULL, 1);
        $type_list_intial_new = array();
        $type_list_intial_old = array();
        foreach ($type_list as $row)
        {
            $row['option_desc'] = 0;    //Use option_desc as a counter, set to 0
            $type_list_intial_new[] = $row;
            $type_list_intial_old[] = $row;
        }

        $field_name = '';
        switch ($the_type)
        {
            case 'gender':
                $field_name = 'us_gender_id';
                break;
            case 'race':
                $field_name = 'us_race_id';
                break;
            case 'age_group':
                $field_name = 'us_age';
                break;
        }

        $condition = "created_on >= '" . $start_timestamp . "' AND created_on <= '" . $end_timestamp . "'";
        $this->db->where($condition);
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where(array('main_group_id' => $group_id_user));
        $query_new = $this->db->get();         
        $result_new = $query_new->result_array();
               
        foreach ($result_new as $row)
        {
            //echo $row['username'] . " : ";
            $age_group_min = 0;
            foreach ($type_list_intial_new as &$row_type)
            {
                if ($the_type == 'age_group')
                {
                    $us_age = $row[$field_name];
                    
                    $age_group_max = $row_type['option_value'];
                    if($us_age >= $age_group_min && $us_age < $age_group_max){
                        $row_type['option_desc'] ++;      
                        //echo $us_age . "  , ";
                    }
                    $age_group_min = $row_type['option_value'];
                }
                else
                {
                    if ($row_type['option_id'] == $row[$field_name])
                    {
                        $row_type['option_desc'] ++;       
                        //echo $row_type['option_value'] . "  , ";
                    }
                }
                
            }
        }

        //var_dump($type_list_intial_new);
        
        $condition = "created_on < '" . $start_timestamp . "'";
        $this->db->where($condition);
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where(array('main_group_id' => $group_id_user));
        $query_old = $this->db->get();           
        $result_old = $query_old->result_array();
        
        foreach ($result_old as $row)
        {
            //echo $row['username'] . " : ";
            $age_group_min = 0;
            foreach ($type_list_intial_old as &$row_type)
            {
                if ($the_type == 'age_group')
                {
                    $us_age = $row[$field_name];
                    
                    $age_group_max = $row_type['option_value'];
                    if($us_age >= $age_group_min && $us_age < $age_group_max){
                        $row_type['option_desc'] ++;      
                        //echo $us_age . "  , ";
                    }
                    $age_group_min = $row_type['option_value'];
                }
                else
                {
                    if ($row_type['option_id'] == $row[$field_name])
                    {
                        $row_type['option_desc'] ++;       
                        //echo $row_type['option_value'] . "  , ";
                    }
                }
                
            }
        }
        //var_dump($type_list_intial_old);    

        $return['new_list'] = $type_list_intial_new;
        $return['old_list'] = $type_list_intial_old;
        return $return;
    }
    
    public function trans_config_get_all($the_column, $the_value)
    {
        $this->db->order_by('trans_conf_id');
        $query = $this->db->get_where('transaction_config', array($the_column => $the_value));

        return $query->result_array();
    }
    
    function trans_config_get($set_id, $field_name = 'amount_change', $want_row = 0)
    {
        $query = $this->db->get_where('transaction_config', array('trans_conf_id' => $set_id));
        $return = "0";
        if ($query->num_rows() == 1)
        {
            $result = $query->row_array();
            $return = $result[$field_name];
            if($field_name == 'amount_change'){
                if ($result['conf_type'] == 'can')
                {
                    $return = round($return);
                }
            }
            if ($want_row == 1)
            {
                $return = $result;
            }
        }
        return $return;
    }

    function trans_config_set($set_id, $update_value = NULL, $field_name = 'amount_change')
    {
        if (!IsNullOrEmptyString($update_value))
        {
            $the_data = array(
                $field_name => $update_value,
            );

            if ($this->m_custom->compare_before_update('transaction_config', $the_data, 'trans_conf_id', $set_id))
            {
                if ($this->ion_auth->logged_in())
                {
                    $login_id = $this->ion_auth->user()->row()->id;
                    $the_data = array(
                        $field_name => $update_value,
                        'last_modify_by' => $login_id,
                    );
                }

                $this->db->where('trans_conf_id', $set_id);
                $this->db->update('transaction_config', $the_data);
            }
        }
    }
    
    public function banner_insert($merchant_id = NULL, $category_id = NULL, $start_time = NULL, $end_time = NULL, $banner_image = NULL, $banner_url = NULL, $banner_position = NULL)
    {
        if ($this->m_admin->check_is_any_admin(69))
        {
            $login_id = $this->ion_auth->user()->row()->id;

            $search_data = array(
                //'category_id' => $category_id,
                'banner_position' => $banner_position,
                'hide_flag' => 0,
            );
            $query = $this->db->get_where('banner', $search_data);  //To check is the position already have active banner

            if ($query->num_rows() == 0)
            {
                $the_data = array(
                    'merchant_id' => $merchant_id,
                    'category_id' => $category_id,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'banner_image' => $banner_image,
                    'banner_url' => $banner_url,
                    'banner_position' => $banner_position,
                    'last_modify_by' => $login_id,
                );
                $this->db->insert('banner', $the_data);
                $new_id = $this->db->insert_id();
                return $new_id;
            }
        }
        return FALSE;
    }

    public function banner_update($merchant_id = NULL, $category_id = NULL, $start_time = NULL, $end_time = NULL, $banner_image = NULL, $banner_url = NULL, $banner_position = NULL, $edit_id = NULL, $hide_flag = 0)
    {
        if ($this->m_admin->check_is_any_admin(69))
        {
            $login_id = $this->ion_auth->user()->row()->id;

            if ($hide_flag == 0)  //if already is hide banner, then not need do banner position checking
            {
                $search_data = array(
                    //'category_id' => $category_id,
                    'banner_position' => $banner_position,
                    'hide_flag' => 0,
                );
                $this->db->where("banner_id !=", $edit_id);   //avoid check back it self row
                $query = $this->db->get_where('banner', $search_data);  //To check is the position already have active banner
            }
            
            if (($hide_flag == 0 && $query->num_rows() == 0) || $hide_flag == 1)   
            {
                $the_data = array(
                    'merchant_id' => $merchant_id,
                    'category_id' => $category_id,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'banner_image' => $banner_image,
                    'banner_url' => $banner_url,
                    'banner_position' => $banner_position,
                    'last_modify_by' => $login_id,
                );
                $this->db->where('banner_id', $edit_id);
                $this->db->update('banner', $the_data);
                return TRUE;
            }
        }
        return FALSE;
    }
    
    public function banner_recover($edit_id)
    {
        if ($this->m_admin->check_is_any_admin(69))
        {
            $login_id = $this->ion_auth->user()->row()->id;
            $query_row = $this->db->get_where('banner', array('banner_id' => $edit_id))->row_array();   //get the original banner info

            $search_data = array(
                'category_id' => $query_row['category_id'],
                'banner_position' => $query_row['banner_position'],
                'hide_flag' => 0,
            );
            $this->db->where("banner_id !=", $edit_id);   //avoid check back it self row
            $query = $this->db->get_where('banner', $search_data);  //To check is the position already have active banner

            if ($query->num_rows() == 0)
            {
                $this->m_custom->update_hide_flag(0, 'banner', $edit_id, $login_id);
                return TRUE;
            }
        }
        return FALSE;
    }

    public function promo_code_result_list($code_type)
    {
        $code_candie = $this->m_custom->web_setting_get('register_promo_code_get_candie');
        $code_money = $this->m_custom->web_setting_get('friend_success_register_get_money', 'set_decimal');

        $this->db->select('code_id,code_no,code_user_id,code_candie,code_money,code_candie_overwrite,code_money_overwrite,last_modify_by,email');
        $this->db->where('code_type', $code_type);
        $this->db->where('users.hide_flag', '0');
        $this->db->from('promo_code');
        $this->db->join('users', 'promo_code.code_user_id = users.id', 'inner');
        $result = $this->db->get()->result_array();
        $final_result = array();
        foreach ($result as $row)
        {
            $code_candie_update = $code_candie;
            if ($row['code_candie_overwrite'] == 1)
            {
                $code_candie_update = "<i>" . $row['code_candie'] . "</i>";
            }
            $code_money_update = $code_money;
            if ($row['code_money_overwrite'] == 1)
            {
                $code_money_update = "<i>" . $row['code_money'] . "</i>";
            }
            $display_name = $this->m_custom->generate_user_link($row['code_user_id']);
            $update_data = array(
                'display_name' => $display_name,
                'code_id' => $row['code_id'],
                'code_no' => $row['code_no'],
                'code_user_id' => $row['code_user_id'],
                'code_candie' => $code_candie_update,
                'code_money' => $code_money_update,
                'last_modify_by' => $row['last_modify_by'],
                'email' => $row['email'],
            );
            $final_result[] = $update_data;
        }
        return $final_result;
    }

}
