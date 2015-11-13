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

    //todo to do half
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
        $this->db->where('end_time >=', get_part_of_date('all'));
        $query = $this->db->get_where('banner', array('hide_flag' => 0, 'expired_flag' => 0));
        return $query->num_rows();
    }
    
    public function getAdminAnalysisReportMerchant($month_id = NULL, $year = NULL)
    {          
        $return = array();
        $start_time = getFirstLastTime($year, $month_id);
        $end_time = getFirstLastTime($year, $month_id, 'last');
        $start_timestamp = strtotime($start_time);
        $end_timestamp = strtotime($end_time);
        $group_id_merchant = $this->config->item('group_id_merchant');
              
        $condition = "created_on >= '" . $start_timestamp . "' AND created_on <= '" . $end_timestamp . "'";
        $this->db->where($condition);
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where(array('main_group_id' => $group_id_merchant));
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
        $this->db->where(array('main_group_id' => $group_id_merchant));
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
    
}
