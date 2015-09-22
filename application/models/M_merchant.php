<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_merchant extends CI_Model
{

    function getMerchant($merchant_id = 0, $slug = NULL, $company = NULL)
    {
        $group_id = $this->config->item('group_id_merchant');
        if ($merchant_id != 0)
        {
            $query = $this->db->get_where('users', array('id' => $merchant_id, 'main_group_id' => $group_id));
        }
        else if ($slug != NULL)
        {
            $query = $this->db->get_where('users', array('slug' => $slug, 'main_group_id' => $group_id));
        }
        else if ($company != NULL)
        {
            $query = $this->db->get_where('users', array('company' => $company, 'main_group_id' => $group_id));
        }
        return $query->row_array();
    }

    function searchMerchant($search_value = NULL, $want_id = 0)
    {
        $return = array();
        if (!IsNullOrEmptyString($search_value))
        {
            $search_word = $this->db->escape('%' . $search_value . '%');
            $this->db->where("(`company` LIKE $search_word OR `slug` LIKE $search_word)");
            $query = $this->db->get_where('users', array('main_group_id' => $this->config->item('group_id_merchant'), 'hide_flag' => 0));
            $result = $query->result_array();

            if ($want_id == 1)
            {
                foreach ($result as $row)
                {
                    $return[] = $row['id'];
                }
            }
            else
            {
                $return = $result;
            }
        }
        return $return;
    }

    //Type: view, like, rating, redeem, choose by show race, gender, age, to do, todo
    public function getMerchantAnalysisReport($merchant_id, $the_type, $month_id = NULL, $year = NULL, $advertise_type = NULL)
    {
        $group_id_user = $this->config->item('group_id_user');
        $search_date = date_for_db_search($month_id, $year);
        if ($advertise_type == NULL)
        {
            $advertise_of_merchant = $this->m_custom->get_list_of_allow_id('advertise', 'merchant_id', $merchant_id, 'advertise_id');
        }
        else
        {
            $advertise_of_merchant = $this->m_custom->get_list_of_allow_id('advertise', 'merchant_id', $merchant_id, 'advertise_id', 'advertise_type', $advertise_type);
        }

        switch ($the_type)
        {
            case 'view':
                $condition = "many_time like '%" . $search_date . "%'";
                $this->db->where($condition);
                if (!empty($advertise_of_merchant))
                {
                    $this->db->where_in('many_parent_id', $advertise_of_merchant);
                }
                $query = $this->db->get_where('many_to_many', array('many_type' => 'view_advertise'));
                break;
            case 'like':
                $condition = "act_time like '%" . $search_date . "%'";
                $this->db->where($condition);
                //$this->db->where_in('act_refer_type', array('adv', 'mua'));
                $this->db->where_in('act_refer_type', array('adv'));  //current hardcode only get analysis from advertisement(hot deal and promotion), dint include analysis for picture upload by user for merchant
                if (!empty($advertise_of_merchant))
                {
                    $this->db->where_in('act_refer_id', $advertise_of_merchant);
                }
                $query = $this->db->get_where('activity_history', array('act_type' => 'like', 'act_by_type' => $group_id_user, 'hide_flag' => 0));
                break;
            case 'rating':
                $condition = "act_time like '%" . $search_date . "%'";
                $this->db->where($condition);
                //$this->db->where_in('act_refer_type', array('adv', 'mua'));
                $this->db->where_in('act_refer_type', array('adv'));  //current hardcode only get analysis from advertisement(hot deal and promotion), dint include analysis for picture upload by user for merchant
                if (!empty($advertise_of_merchant))
                {
                    $this->db->where_in('act_refer_id', $advertise_of_merchant);
                }
                $query = $this->db->get_where('activity_history', array('act_type' => 'rating', 'act_by_type' => $group_id_user, 'hide_flag' => 0));
                break;
            case 'redeem':
                $condition = "redeem_time like '%" . $search_date . "%'";
                $this->db->where($condition);
                if (!empty($advertise_of_merchant))
                {
                    $this->db->where_in('advertise_id', $advertise_of_merchant);
                }
                $query = $this->db->get_where('user_redemption');
                break;
        }

        return $query->result_array();
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
            $sub_category = $this->m_custom->get_one_table_record('category', 'category_id', $category_id);
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

    public function get_merchant_link_from_advertise($advertise_id)
    {
        return $this->m_custom->generate_merchant_link($this->m_merchant->get_merchant_id_from_advertise($advertise_id));
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

    public function get_merchant_monthly_promotion($merchant_id, $month = NULL, $year = NULL, $advertise_id = NULL)
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
        if ($advertise_id != NULL)
        {
            $query = $this->db->get_where('advertise', array('merchant_id' => $merchant_id, 'advertise_id' => $advertise_id), 1);
            if ($query->num_rows() == 1)
            {
                $get_by_advertise_id = 1;
            }
        }
        if ($get_by_advertise_id == 0)
        {
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

    public function merchant_balance_update($merchant_id, $month_id = NULL, $year = NULL)
    {
        $search_date = date_for_db_search($month_id, $year);

        if (empty($month_id))
        {
            $month_id = get_part_of_date('month');
        }
        if (empty($year))
        {
            $year = get_part_of_date('year');
        }

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

    public function merchant_this_month_transaction($merchant_id)
    {
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

    public function merchant_check_balance($merchant_id, $exclude_this_month = 0)
    {
        $this->m_user->candie_balance_update($merchant_id);
        if ($exclude_this_month == 0)
        {
            $history_query = $this->db->get_where('merchant_balance', array('merchant_id' => $merchant_id));
        }
        else
        {
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
        return number_format($current_balance, 2);
    }

    public function have_money($merchant_id){       
        if($this->m_merchant->merchant_check_balance($merchant_id)<$this->config->item('merchant_minimum_balance') && $this->config->item('froze_account_activate')==1){
            return FALSE;
        }else{
            return TRUE;
        }        
    }
    
    public function user_redemption_done($redeem_id, $mark_expired = 0)
    {
        if (check_correct_login_type($this->config->item('group_id_merchant')) || check_correct_login_type($this->config->item('group_id_supervisor')))
        {
            $login_id = $this->ion_auth->user()->row()->id;
            $login_type = $this->session->userdata('user_group_id');

            $branch_id = 0;
            if (check_correct_login_type($this->config->item('group_id_supervisor')))
            {
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
            if ($this->db->update('user_redemption', $the_data))
            {
                return TRUE;
            }
        }
        return FALSE;
    }

    function getUserRedemption($promotion_id, $status_id, $hide_expired = 0)
    {
        if ($hide_expired == 1)
        {
            $this->db->where('expired_date >=', get_part_of_date('all'));
        }
        $redeem_query = $this->db->get_where('user_redemption', array('advertise_id' => $promotion_id, 'status_id' => $status_id));
        return $redeem_query->result_array();
    }

    public function generate_voucher($id)
    {
        $result = $this->m_custom->get_one_table_record('users', 'id', $id, 1);
        $voucher = '';
        $web_setting = $this->m_custom->get_one_table_record('web_setting', 'set_type', 'voucher_counter', 1);
        $counter = $web_setting['set_value'];
        if ($result)
        {
            do
            {
                $voucher = strtoupper(substr($result['slug'], 0, 3)) . date('Ymd') . str_pad($counter, 3, "0", STR_PAD_LEFT);
                $check_unique = $this->m_custom->check_is_value_unique('advertise', 'voucher', $voucher);
                $counter += 1;
            } while (!$check_unique);
            if ($counter == '1000')
            {
                $counter = '1';
            }
            $the_data = array(
                'set_value' => $counter,
            );
            $this->db->where('set_id', $web_setting['set_id']);
            $this->db->update('web_setting', $the_data);
        }
        return $voucher;
    }

    public function hotdeal_hide($advertise_id)
    {
        if ($this->ion_auth->logged_in())
        {
            $activity = $this->db->get_where('table_row_activity', array('table_row_id' => $advertise_id, 'table_type' => 'advertise'), 1)->row_array();
            $login_id = $this->ion_auth->user()->row()->id;
            $login_type = $this->session->userdata('user_group_id');

            if ($activity['create_by'] == $login_id && $activity['create_by_type'] == $login_type)
            {
                
            }
            else
            {
                $mon_hide_type = 'adv';
                $mon_table_id = $advertise_id;
                $mon_table = 'advertise';
                $this->m_custom->insert_row_monitor_process($mon_hide_type, $mon_table_id, $mon_table, $login_type);
            }
        }
    }
    
    public function mua_hide($mua_id)
    {
        if ($this->ion_auth->logged_in())
        {
            $activity = $this->db->get_where('table_row_activity', array('table_row_id' => $mua_id, 'table_type' => 'merchant_user_album'), 1)->row_array();
            $login_id = $this->ion_auth->user()->row()->id;
            $login_type = $this->session->userdata('user_group_id');

            if ($activity['create_by'] == $login_id && $activity['create_by_type'] == $login_type)  //mostly will not happen because the creater of mua is user
            {
                
            }
            else
            {
                $mon_hide_type = 'mua';
                $mon_table_id = $mua_id;
                $mon_table = 'merchant_user_album';
                $this->m_custom->insert_row_monitor_process($mon_hide_type, $mon_table_id, $mon_table, $login_type);
            }
        }
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

}
