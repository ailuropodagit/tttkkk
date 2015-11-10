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

    public function merchant_low_balance_count(){
        
        $query = $this->db->get_where('users', array('main_group_id' => $this->config->item('group_id_merchant')));
        $the_result = $query->result_array();

        $low_balance_count = 0;
        foreach ($the_result as $row)
        {
            $merchant_balance = $this->m_merchant->merchant_check_balance($row['id']);
            $merchant_minimum_balance = $this->m_custom->web_setting_get('merchant_minimum_balance', 'set_decimal');
            if ($merchant_balance < $merchant_minimum_balance)
            {
                $low_balance_count++;
            }
        }
        return $low_balance_count;
    }
    
    public function banner_expired_count(){
        $this->db->where('end_time >=', get_part_of_date('all'));
        $query = $this->db->get_where('banner', array('hide_flag' => 0, 'expired_flag' => 0));
        return $query->num_rows();
    }
    
}
