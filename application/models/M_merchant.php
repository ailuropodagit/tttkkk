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

    //Type: view, like, rating, redeem, choose by show race, gender, age
    public function getMerchantAnalysisReport($merchant_id, $the_type, $month_id = NULL, $year = NULL, $advertise_type = NULL)
    {
        $group_id_user = $this->config->item('group_id_user');
        //$search_date = date_for_db_search($month_id, $year);
        
        $start_time = getFirstLastTime($year, $month_id);
        $end_time = getFirstLastTime($year, $month_id, 'last');
        
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
                //$condition = "many_time like '%" . $search_date . "%'";
                $condition = "many_time >= '" . $start_time . "' AND many_time <= '" . $end_time . "'";
                $this->db->where($condition);
                if (!empty($advertise_of_merchant))
                {
                    $this->db->where_in('many_parent_id', $advertise_of_merchant);
                }
                $query = $this->db->get_where('many_to_many', array('many_type' => 'view_advertise'));
                break;
            case 'like':
                //$condition = "act_time like '%" . $search_date . "%'";
                $condition = "act_time >= '" . $start_time . "' AND act_time <= '" . $end_time . "'";
                $this->db->where($condition);
                $this->db->where_in('act_refer_type', array('adv', 'mua'));
                //$this->db->where_in('act_refer_type', array('adv')); 
                if (!empty($advertise_of_merchant))
                {
                    $this->db->where_in('act_refer_id', $advertise_of_merchant);
                }
                $query = $this->db->get_where('activity_history', array('act_type' => 'like', 'act_by_type' => $group_id_user, 'hide_flag' => 0));
                break;
            case 'rating':
                //$condition = "act_time like '%" . $search_date . "%'";
                $condition = "act_time >= '" . $start_time . "' AND act_time <= '" . $end_time . "'";
                $this->db->where($condition);
                $this->db->where_in('act_refer_type', array('adv', 'mua'));
                //$this->db->where_in('act_refer_type', array('adv'));
                if (!empty($advertise_of_merchant))
                {
                    $this->db->where_in('act_refer_id', $advertise_of_merchant);
                }
                $query = $this->db->get_where('activity_history', array('act_type' => 'rating', 'act_by_type' => $group_id_user, 'hide_flag' => 0));
                break;
            case 'redeem':
                //$condition = "redeem_time like '%" . $search_date . "%'";
                $condition = "redeem_time >= '" . $start_time . "' AND redeem_time <= '" . $end_time . "'";
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

    
    public function getMerchantAnalysisReportRedeem($merchant_id, $status_id, $month_id = NULL, $year = NULL)
    {    
        $start_time = getFirstLastTime($year, $month_id);
        $end_time = getFirstLastTime($year, $month_id, 'last');
        
        $condition = "redeem_time >= '" . $start_time . "' AND redeem_time <= '" . $end_time . "'";
        $this->db->where($condition);

        $this->db->select('*');
        $this->db->from('user_redemption');
        $this->db->join('advertise', 'user_redemption.advertise_id = advertise.advertise_id', 'inner');
        $this->db->where(array('advertise.advertise_type' => 'pro', 'advertise.merchant_id' => $merchant_id, 'user_redemption.status_id' => $status_id));
        $query = $this->db->get();

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
        $this->db->order_by('company', 'asc');
        $query = $this->db->get_where('users', array('me_category_id' => $category_id));
        if ($query->num_rows() == 0 && $return_empty == 0)
        {
            $query = $this->db->get_where('users', array('main_group_id' => $this->config->item('group_id_merchant'), 'hide_flag' => 0));
        }
        return $query->result();
    }

    public function getMerchantList_by_subcategory($sub_category_id = 0)
    {
        $this->db->where('me_sub_category_id', $sub_category_id);
        $query = $this->db->get_where('users', array('main_group_id' => $this->config->item('group_id_merchant'), 'hide_flag' => 0));

        $result = $query->result_array();
        $return = array();
        foreach ($result as $row)
        {
            $return[] = $this->m_custom->getMerchantInfo($row['id']);
        }
        return $return;
    }

    public function getMerchantCount_by_subcategory($sub_category_id = 0)
    {
        $this->db->where('me_sub_category_id', $sub_category_id);
        $query = $this->db->get_where('users', array('main_group_id' => $this->config->item('group_id_merchant'), 'hide_flag' => 0));

        return $query->num_rows();
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

    public function get_merchant_today_hotdeal($merchant_id, $counter_only = 0, $date = NULL, $ignore_hide = 0)
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
        if($ignore_hide == 0){
            $this->db->where('hide_flag', 0);
        }
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

    public function get_merchant_today_hotdeal_removed($merchant_id, $date = NULL)
    {
        if (!IsNullOrEmptyString($date))
        {
            $search_date = $date;
        }
        else
        {
            $search_date = date(format_date_server());
        }
        $this->db->where('hide_flag', 1);
        $condition = "start_time like '%" . $search_date . "%'";
        $this->db->where('advertise_type', 'hot');
        $this->db->where($condition);
        $query = $this->db->get_where('advertise', array('merchant_id' => $merchant_id));
        return $query->num_rows();
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
                    'month_last_date' => displayDate(displayLastDay($year, $month_id), 0, 1),
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

    public function merchant_this_month_transaction($merchant_id, $search_year_month = NULL)
    {
        $search_date = $search_year_month == NULL ? date_for_db_search() : $search_year_month;
        $condition = "trans_time like '%" . $search_date . "%'";
        $this->db->where($condition);
        $this->db->select("trans_conf_id, SUM(amount_plus) AS plus, SUM(amount_minus) AS minus, COUNT(trans_history_id) As quantity");
        $this->db->group_by('trans_conf_id');
        $this->db->order_by('trans_conf_id', 'asc');
        $query = $this->db->get_where('transaction_history', array('merchant_id' => $merchant_id));
        $result = $query->result_array();
        return $result;
    }

    public function merchant_check_balance($merchant_id, $exclude_this_month = 0, $search_year_month = NULL)
    {
        $this->m_merchant->merchant_balance_update($merchant_id);
        if ($exclude_this_month == 0)
        {
            $history_query = $this->db->get_where('merchant_balance', array('merchant_id' => $merchant_id));
        }
        else
        {
//            $current_month = ltrim(get_part_of_date('month'), '0');
//            $current_year = get_part_of_date('year');
//            $condition = "(month_id !=" . $current_month . " or year !=" . $current_year . ")";
            $search_date = $search_year_month == NULL ? date_for_db_search() : $search_year_month;
            $condition = "month_last_date <= '" . $search_date . "'";
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

    //to do todo
    public function money_spend_on_list($merchant_id, $filter_type = NULL)
    {
        if ($filter_type == NULL || $filter_type == 'pro' || $filter_type == 'hot')
        {
            if (!empty($filter_type))
            {
                $this->db->where('advertise_type', $filter_type);
            }
            $list_advertise = $this->db->get_where('advertise', array('merchant_id' => $merchant_id))->result_array();

            $return = array();
            foreach ($list_advertise as $row)
            {
                $type_text = '';
                if ($row['advertise_type'] == 'pro')
                {
                    $type_text = 'Candie Voucher';
                }
                elseif ($row['advertise_type'] == 'hot')
                {
                    $type_text = 'Hot Deal Advertise';
                }
                $return[] = array(
                    'create_date' => $row['create_date'],
                    'id' => $row['advertise_id'],
                    'type' => $row['advertise_type'],
                    'type_text' => $type_text,
                    'table' => 'advertise',
                    'title' => $row['title'],
                    'title_url' => $this->m_custom->generate_advertise_link($row['advertise_id']),                 
                    'hide_flag' => $row['hide_flag'],
                );
            }
        }
        if ($filter_type == NULL || $filter_type == 'mua')
        {
            $list_mua = $this->db->get_where('merchant_user_album', array('post_type' => 'mer', 'merchant_id' => $merchant_id))->result_array();

            foreach ($list_mua as $row)
            {
                $return[] = array(
                    'create_date' => $row['create_date'],
                    'id' => $row['merchant_user_album_id'],
                    'type' => 'mua',
                    'type_text' => 'User Upload Picture',
                    'table' => 'merchant_user_album',
                    'title' => $row['title'],
                    'title_url' => $this->m_custom->generate_mua_link($row['merchant_user_album_id']),                  
                    'hide_flag' => $row['hide_flag'],
                );
            }
        }

        $return_final = array();
        foreach ($return as $row)
        {
            $row_id = $row['id'];
            $row_type = $row['type'];
            $row_table = $row['table'];
            $total_amount = 0;

            $this->db->select('many_id, many_parent_id, amount_minus');
            $this->db->from('many_to_many');
            $this->db->join('transaction_history', 'many_to_many.many_id = transaction_history.get_from_table_id', 'inner');
            $this->db->where(array('many_to_many.many_parent_id' => $row_id, 'many_to_many.many_type' => 'view_advertise', 'transaction_history.get_from_table' => 'many_to_many', 'transaction_history.trans_conf_id' => 11));
            $list_view = $this->db->get()->result_array();
            $view_count = count($list_view);
            $view_amount = 0;
            foreach ($list_view as $row_small)
            {
                $view_amount += $row_small['amount_minus'];
            }
            $total_amount += $view_amount;
            //var_dump($list_view);

            $this->db->select('act_history_id, act_refer_id, amount_minus');
            $this->db->from('activity_history');
            $this->db->join('transaction_history', 'activity_history.act_history_id = transaction_history.get_from_table_id', 'inner');
            $this->db->where(array('activity_history.act_refer_id' => $row_id, 'activity_history.act_type' => 'like', 'transaction_history.get_from_table' => 'activity_history', 'transaction_history.trans_conf_id' => 12));
            $list_like = $this->db->get()->result_array();
            $like_count = count($list_like);
            $like_amount = 0;
            foreach ($list_like as $row_small)
            {
                $like_amount += $row_small['amount_minus'];
            }
            $total_amount += $like_amount;
            //var_dump($list_like);

            $this->db->select('act_history_id, act_refer_id, amount_minus');
            $this->db->from('activity_history');
            $this->db->join('transaction_history', 'activity_history.act_history_id = transaction_history.get_from_table_id', 'inner');
            $this->db->where(array('activity_history.act_refer_id' => $row_id, 'activity_history.act_type' => 'rating', 'transaction_history.get_from_table' => 'activity_history', 'transaction_history.trans_conf_id' => 13));
            $list_rating = $this->db->get()->result_array();
            $rating_count = count($list_rating);
            $rating_amount = 0;
            foreach ($list_rating as $row_small)
            {
                $rating_amount += $row_small['amount_minus'];
            }
            $total_amount += $rating_amount;
            //var_dump($list_rating);

            $redeem_count = NULL;
            $redeem_amount = NULL;
            if ($row_type == 'pro')
            {
                $this->db->select('*');
                $this->db->from('user_redemption');
                $this->db->join('transaction_history', 'user_redemption.redeem_id = transaction_history.get_from_table_id', 'inner');
                $this->db->where(array('user_redemption.advertise_id' => $row_id, 'transaction_history.get_from_table' => 'user_redemption', 'transaction_history.trans_conf_id' => 18));
                $list_redeem = $this->db->get()->result_array();
                $redeem_count = count($list_redeem);
                $redeem_amount = 0;
                foreach ($list_redeem as $row_small)
                {
                    $redeem_amount += $row_small['amount_minus'];
                }
                $total_amount += $redeem_amount;
                //var_dump($list_redeem);
            }

            $userupload_count = NULL;
            $userupload_amount = NULL;
            if ($row_type == 'mua')
            {
                $this->db->select('*');
                $this->db->from('transaction_history');
                $this->db->where(array('get_from_table_id' => $row_id, 'get_from_table' => 'merchant_user_album', 'transaction_history.trans_conf_id' => 14));
                $list_userupload = $this->db->get()->result_array();
                $userupload_count = count($list_userupload);
                $userupload_amount = 0;
                foreach ($list_userupload as $row_small)
                {
                    $userupload_amount += $row_small['amount_minus'];
                }
                $total_amount += $userupload_amount;
                //var_dump($list_userupload);
            }

            $combine_result = array(
                'view_count' => $view_count,
                'view_amount' => $view_amount,
                'like_count' => $like_count,
                'like_amount' => $like_amount,
                'rating_count' => $rating_count,
                'rating_amount' => $rating_amount,
                'redeem_count' => $redeem_count,
                'redeem_amount' => $redeem_amount,
                'userupload_count' => $userupload_count,
                'userupload_amount' => $userupload_amount,
                'total_amount' => $total_amount,
            );
            $return_final[] = $row + $combine_result;
        }

        return $return_final;
    }

    public function have_money($merchant_id)
    {
        if ($this->m_merchant->merchant_check_balance($merchant_id) < $this->config->item('merchant_minimum_balance') && $this->config->item('froze_account_activate') == 1)
        {
            return FALSE;
        }
        else
        {
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

    function getUserRedemption($promotion_id, $status_id, $hide_expired = 0, $search_word = '')
    {
        $this->db->order_by('voucher', 'asc');
        if ($hide_expired == 1)
        {
            $this->db->where('expired_date >=', get_part_of_date('all'));
        }
        $redeem_query = $this->db->get_where('user_redemption', array('advertise_id' => $promotion_id, 'status_id' => $status_id));
        $result = $redeem_query->result_array();
        $return = array();
        foreach ($result as $row)
        {
            if (!empty($search_word))
            {
                $user_info = $this->m_custom->getUserInfo($row['user_id']);
                if ((searchWord($user_info['name'], $search_word)) || (searchWord($user_info['email'], $search_word)) || (searchWord($row['voucher'], $search_word)))
                {
                    $return[] = $row;
                }
            }
            else
            {
                $return[] = $row;
            }
        }

        return $return;
    }

    public function generate_voucher($id, $user_id = 0)
    {
        $result = $this->m_custom->get_one_table_record('users', 'id', $id, 1);
        $voucher = '';
        $web_setting = $this->m_custom->get_one_table_record('web_setting', 'set_type', 'voucher_counter', 1);
        $counter = $web_setting['set_value'];
        if ($result)
        {
            do
            {
                $voucher = strtoupper(substr($result['slug'], 0, 3)) . date('Ym') . str_pad($user_id, 4, "0", STR_PAD_LEFT) . str_pad($counter, 3, "0", STR_PAD_LEFT);
                $check_unique = $this->m_custom->check_is_value_unique('user_redemption', 'voucher', $voucher);
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
