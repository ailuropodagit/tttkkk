<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_user extends CI_Model
{

    public function candie_balance_update($user_id, $month_id = NULL, $year = NULL)
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
            'user_id' => $user_id,
        );
        $this->db->where($history_condition);
        $history_query = $this->db->get_where('candie_history', $history_search_data);
        $history_result = $history_query->result_array();
        if ($history_query->num_rows() != 0)
        {
            $monthly_balance = 0;
            foreach ($history_result as $history_row)
            {
                $monthly_balance = $monthly_balance + $history_row['candie_plus'] - $history_row['candie_minus'];
            }

            $balance_search_data = array(
                'user_id' => $user_id,
                'month_id' => $month_id,
                'year' => $year,
            );
            $balance_query = $this->db->get_where('candie_balance', $balance_search_data);
            if ($balance_query->num_rows() == 0)
            {
                $insert_data = array(
                    'user_id' => $user_id,
                    'balance' => $monthly_balance,
                    'month_id' => $month_id,
                    'year' => $year,
                );
                $this->db->insert('candie_balance', $insert_data);
            }
            else
            {
                $balance_result = $balance_query->row_array();
                $update_data = array(
                    'balance' => $monthly_balance,
                );
                $this->db->where('balance_id', $balance_result['balance_id']);
                $this->db->update('candie_balance', $update_data);
            }
        }
    }

    public function user_redemption_insert($advertise_id, $top_up_phone = NULL)
    {
        $redeem_status = FALSE;
        $redeem_message = '';
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            $user_id = $this->ion_auth->user()->row()->id;
            $promotion_row = $this->m_custom->getOneAdvertise($advertise_id);
            if ($promotion_row)
            {
                $voucher_candie = $promotion_row['voucher_candie'];
                $current_balance = $this->m_user->candie_check_balance($user_id);
                $new_balance = $this->m_user->candie_enough($user_id, $voucher_candie, 1);
                $voucher = $this->m_merchant->generate_voucher($promotion_row['merchant_id'],$user_id);
                $merchant_name = $this->m_custom->display_users($promotion_row['merchant_id']);
                
                //If is admin promotin, overwrite some info
                if($promotion_row['advertise_type'] == "adm"){
                    $merchant_name = $this->config->item('keppo_company_name');
                }
                if ($new_balance >= 0)
                {
                    $the_data = array(
                        'user_id' => $user_id,
                        'advertise_id' => $advertise_id,
                        'status_id' => $this->config->item('voucher_active'),
                        'voucher' => $voucher,
                        'expired_date' => $promotion_row['end_time'],
                        'top_up_phone' => $top_up_phone,
                    );
                    $this->db->insert('user_redemption', $the_data);
                    $insert_id = $this->db->insert_id();
                    $this->m_user->candie_history_insert(8, $insert_id, 'user_redemption', 0, $voucher_candie);
                    $this->m_merchant->transaction_history_insert($promotion_row['merchant_id'], 18, $insert_id, 'user_redemption');
                    $redeem_message = "Success Redeem This Voucher <br/>" .
                            "Previous Candie : " . $current_balance . " <br/>" .
                            "Voucher Required Candie : " . $voucher_candie . " <br/>" .
                            "Remain Candie : " . $new_balance . " <br/>";
                    $redeem_status = TRUE;
                    $this->m_user->candie_balance_update($user_id);
                    $this->m_custom->notification_process('user_redemption', $insert_id);
                }
                else
                {
                    $redeem_message = "Not enough candie to redeem this voucher!<br/>" .
                            "Current Candie : " . $current_balance . " <br/>" .
                            "Voucher Required Candie : " . $voucher_candie . " <br/>";
                }
            }
        }
        $redeem_info = array(
            'redeem_status' => $redeem_status,
            'redeem_message' => $redeem_message,
            'redeem_voucher' => $voucher,
            'redeem_voucher_not_need' => $promotion_row['voucher_not_need'],
            'redeem_title' => $promotion_row['title'],
            'redeem_merchant' => $merchant_name,
            'redeem_expire' => displayDate($promotion_row['voucher_expire_date']),
            'redeem_email_subject' => $merchant_name . ' Keppo Voucher Success Redeem : ' . $voucher,
            'redeem_top_up_phone' => $top_up_phone,
        );
        return $redeem_info;
    }

    public function candie_check_balance($user_id, $exclude_this_month = 0)
    {
        $this->m_user->candie_balance_update($user_id);
        if ($exclude_this_month == 0)
        {
            $history_query = $this->db->get_where('candie_balance', array('user_id' => $user_id));
        }
        else
        {
            $current_month = ltrim(get_part_of_date('month'), '0');
            $current_year = get_part_of_date('year');
            $condition = "(month_id !=" . $current_month . " or year !=" . $current_year . ")";
            $this->db->where($condition);
            $history_query = $this->db->get_where('candie_balance', array('user_id' => $user_id));
        }
        $current_balance = 0;
        $history_result = $history_query->result_array();
        foreach ($history_result as $history_row)
        {
            $current_balance += $history_row['balance'];
        }
        return $current_balance;
    }

    public function user_this_month_candie_gain($user_id)
    {
        $search_date = date_for_db_search();
        $condition = "trans_time like '%" . $search_date . "%'";
        $this->db->where($condition);
        $this->db->select("SUM(candie_plus) AS plus");
        $this->db->group_by('user_id');
        $query = $this->db->get_where('candie_history', array('user_id' => $user_id));
        $result = $query->row_array();
        return $result['plus'];
    }

    public function user_this_month_candie($user_id)
    {
        $search_date = date_for_db_search();
        $condition = "trans_time like '%" . $search_date . "%'";
        $this->db->where($condition);
        $this->db->select("trans_conf_id, SUM(candie_plus) AS plus, SUM(candie_minus) AS minus, COUNT(trans_history_id) As quantity");
        $this->db->group_by('trans_conf_id');
        $this->db->order_by('trans_conf_id', 'asc');
        $query = $this->db->get_where('candie_history', array('user_id' => $user_id));
        $result = $query->result_array();
        return $result;
    }

    public function user_this_month_redemption($user_id)
    {
        $search_date = date_for_db_search();
        $condition = "redeem_time like '%" . $search_date . "%'";
        $this->db->where($condition);
        $this->db->select("*");
        $this->db->from('user_redemption');
        //$this->db->join('candie_history', 'user_redemption.redeem_id = candie_history.get_from_table_id', 'inner');
        $this->db->order_by('redeem_id', 'asc');
        $this->db->where('user_redemption.user_id', $user_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_candie_history_from_redemption($redeem_id)
    {
        $query = $this->db->get_where('candie_history', array('get_from_table_id' => $redeem_id, 'get_from_table' => 'user_redemption'));
        return $query->row_array();
    }

    public function user_redemption_check($user_id, $advertise_id){
        $query = $this->db->get_where('user_redemption', array('user_id' => $user_id, 'advertise_id' => $advertise_id));
        if($query->num_rows() == 0){
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    public function user_redemption($user_id, $status_id = NULL, $sub_category = NULL)
    {
        if (!IsNullOrEmptyString($status_id))
        {
            $this->db->where('status_id', $status_id);
        }
        $this->db->order_by('redeem_id', 'desc');
        $red_query = $this->db->get_where('user_redemption', array('user_id' => $user_id));
        $red_result = $red_query->result_array();

        $result = array();
        foreach ($red_result as $row)
        {
            $advertise = $this->m_custom->getOneAdvertise($row['advertise_id']);
            if ($advertise != FALSE)
            {
                $candie = $this->m_user->get_candie_history_from_redemption($row['redeem_id']);

                if (!IsNullOrEmptyString($sub_category))
                {
                    if ($advertise['sub_category_id'] == $sub_category)
                    {
                        $result[] = $advertise + $candie + $row;
                    }
                }
                else
                {
                    $result[] = $advertise + $candie + $row;
                }
            }
        }

        return $result;
    }

    public function user_redemption_sub_category_list($user_id, $status_id = NULL)
    {
        if (!IsNullOrEmptyString($status_id))
        {
            $this->db->where('status_id', $status_id);
        }
        $this->db->order_by('redeem_id', 'desc');
        $red_query = $this->db->get_where('user_redemption', array('user_id' => $user_id));
        $red_result = $red_query->result_array();

        $result = array();
        foreach ($red_result as $row)
        {
            $advertise = $this->m_custom->getOneAdvertise($row['advertise_id']);
            if ($advertise != FALSE)
            {
                if (!in_array($advertise['sub_category_id'], $result))
                {
                    $result[$advertise['sub_category_id']] = $this->m_custom->display_category($advertise['sub_category_id']);
                }
            }
        }

        return $result;
    }

    public function user_review_list($act_type, $user_id, $category = NULL)
    {
        $act_type_name = $this->m_custom->display_static_option($act_type);
        $group_id_user = $this->config->item('group_id_user');

        $this->db->where('act_refer_type', 'adv');
        $act_query = $this->db->get_where('activity_history', array('act_by_id' => $user_id, 'act_type' => $act_type_name, 'act_by_type' => $group_id_user));
        $act_result = $act_query->result_array();

        $result = array();
        foreach ($act_result as $row)
        {
            switch ($row['act_refer_type'])
            {
                case 'adv':
                    $refer_row = $this->m_custom->getOneAdvertise($row['act_refer_id']);
                    break;
                case 'mua':
                    $refer_row = $this->m_custom->getOneMUA($row['act_refer_id']);
                    break;
                case 'usa':
                    $refer_row = $this->m_custom->getOneUserPicture($row['act_refer_id']);
                    break;
            }

            if ($refer_row != FALSE)
            {
                $result[] = $row + $refer_row;
            }
        }

        return $result;
    }

    public function user_review_merchant_list($act_type, $user_id, $category = NULL)
    {
        $act_type_name = $this->m_custom->display_static_option($act_type);
        $group_id_user = $this->config->item('group_id_user');

        $this->db->where_in('act_refer_type', array('adv', 'mua'));
        //$this->db->where('act_refer_type', 'adv');  //current hardcode only get analysis from advertisement(hot deal and promotion), dint include analysis for picture upload by user for merchant
        $act_query = $this->db->get_where('activity_history', array('act_by_id' => $user_id, 'act_type' => $act_type_name, 'act_by_type' => $group_id_user));
        $act_result = $act_query->result_array();

        $merchant_list = array();
        $result_merchant = array();
        foreach ($act_result as $row)
        {
            $refer_row = $this->m_custom->getOneAdvertise($row['act_refer_id']);

            if ($refer_row != FALSE && !in_array($refer_row['merchant_id'], $merchant_list))
            {
                $merchant_id = $refer_row['merchant_id'];
                $merchant_info = $this->m_custom->getMerchantInfo($merchant_id);
                if ($category == NULL)
                {
                    $result_merchant[] = $merchant_info;
                    $merchant_list[] = $merchant_id;
                }
                else
                {
                    if ($merchant_info['me_category_id'] == $category)
                    {
                        $result_merchant[] = $merchant_info;
                        $merchant_list[] = $merchant_id;
                    }
                }
            }
        }

        return $result_merchant;
    }

    public function candie_enough($user_id, $spend_candie = 0, $return_new_balance = 0)
    {
        $current_balance = $this->m_user->candie_check_balance($user_id);
        $new_balance = $current_balance - $spend_candie;
        if ($return_new_balance == 0)
        {
            if ($new_balance >= 0)
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            return $new_balance;
        }
    }

    //Type: race, gender, age, to do , todo
    public function getUserAnalysisGroup($user_id, $the_type)
    {
        $the_user = $this->m_custom->getUser($user_id);
        switch ($the_type)
        {
            case 'race':
                return $the_user['us_race_id'];
                break;
            case 'gender':
                return $the_user['us_gender_id'];
                break;
            case 'age':
                return $the_user['us_age'];
                break;
        }
    }

    public function get_user_today_upload_count($user_id, $date = NULL)
    {
        if (!IsNullOrEmptyString($date))
        {
            $search_date = $date;
        }
        else
        {
            $search_date = date(format_date_server());
        }

        $condition = "create_date like '%" . $search_date . "%'";
        $this->db->where($condition);
        $this->db->where('hide_flag', 0);
        $mua_query = $this->db->get_where('merchant_user_album', array('user_id' => $user_id));
        $total_count = $mua_query->num_rows();

        $this->db->where($condition);
        $this->db->where('hide_flag', 0);
        $usa_query = $this->db->get_where('user_album', array('user_id' => $user_id));
        $total_count += $usa_query->num_rows();

        return $total_count;
    }

    public function candie_history_insert($trans_conf_id, $get_from_table_id, $get_from_table = 'activity_history', $allow_duplicate = 0, $candie_overwrite = 0)
    {
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            $user_id = $this->ion_auth->user()->row()->id;
            $search_data = array(
                'user_id' => $user_id,
                'trans_conf_id' => $trans_conf_id,
                'get_from_table' => $get_from_table,
                'get_from_table_id' => $get_from_table_id,
            );
            $query = $this->db->get_where('candie_history', $search_data);
            if (($query->num_rows() == 0 && $allow_duplicate == 0) || $allow_duplicate != 0)
            {
                $config_query = $this->db->get_where('transaction_config', array('trans_conf_id' => $trans_conf_id, 'conf_type' => 'can'));
                if ($config_query->num_rows() == 1)
                {
                    $config_result = $config_query->row_array();
                    $candie_plus = 0;
                    $candie_minus = 0;
                    if ($config_result['change_type'] == 'inc')
                    {
                        $candie_plus = $config_result['amount_change'];
                    }
                    else
                    {
                        $candie_minus = $config_result['amount_change'];
                        //If is redeemption, need to minus candie
                        if ($trans_conf_id == 8)
                        {
                            $candie_minus = $candie_overwrite;
                        }
                    }
                    $the_data = array(
                        'user_id' => $user_id,
                        'trans_conf_id' => $trans_conf_id,
                        'candie_plus' => $candie_plus,
                        'candie_minus' => $candie_minus,
                        'get_from_table' => $get_from_table,
                        'get_from_table_id' => $get_from_table_id,
                    );
                    $this->db->insert('candie_history', $the_data);
                }
            }
        }
    }

    public function user_balance_update($user_id, $month_id = NULL, $year = NULL)
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
            'user_id' => $user_id,
        );
        $this->db->where($history_condition);
        $history_query = $this->db->get_where('user_trans_history', $history_search_data);
        $history_result = $history_query->result_array();
        if ($history_query->num_rows() != 0)
        {
            $monthly_balance = 0;
            foreach ($history_result as $history_row)
            {
                $monthly_balance = $monthly_balance + $history_row['amount_plus'] - $history_row['amount_minus'];
            }

            $balance_search_data = array(
                'user_id' => $user_id,
                'month_id' => $month_id,
                'year' => $year,
            );
            $balance_query = $this->db->get_where('user_balance', $balance_search_data);
            if ($balance_query->num_rows() == 0)
            {
                $insert_data = array(
                    'user_id' => $user_id,
                    'balance' => $monthly_balance,
                    'month_id' => $month_id,
                    'year' => $year,
                );
                $this->db->insert('user_balance', $insert_data);
            }
            else
            {
                $balance_result = $balance_query->row_array();
                $update_data = array(
                    'balance' => $monthly_balance,
                );
                $this->db->where('balance_id', $balance_result['balance_id']);
                $this->db->update('user_balance', $update_data);
            }
        }
    }
    
    public function user_check_balance($user_id, $exclude_this_month = 0)
    {
        $this->m_user->user_balance_update($user_id);
        if ($exclude_this_month == 0)
        {
            $history_query = $this->db->get_where('user_balance', array('user_id' => $user_id));
        }
        else
        {
            $current_month = ltrim(get_part_of_date('month'), '0');
            $current_year = get_part_of_date('year');
            $condition = "(month_id !=" . $current_month . " or year !=" . $current_year . ")";
            $this->db->where($condition);
            $history_query = $this->db->get_where('user_balance', array('user_id' => $user_id));
        }
        $current_balance = 0;
        $history_result = $history_query->result_array();
        foreach ($history_result as $history_row)
        {
            $current_balance += $history_row['balance'];
        }
        return number_format($current_balance, 2);
    }
    
    public function user_this_month_transaction($user_id)
    {
        $search_date = date_for_db_search();
        $condition = "trans_time like '%" . $search_date . "%'";
        $this->db->where($condition);
        $this->db->select("trans_conf_id, SUM(amount_plus) AS plus, SUM(amount_minus) AS minus, COUNT(trans_history_id) As quantity");
        $this->db->group_by('trans_conf_id');
        $this->db->order_by('trans_conf_id', 'asc');
        $query = $this->db->get_where('user_trans_history', array('user_id' => $user_id));
        $result = $query->result_array();
        return $result;
    }
    
    public function user_trans_history_insert($user_id, $trans_conf_id, $get_from_table_id, $get_from_table = 'merchant_user_album', $allow_duplicate = 0, $amount_overwrite = 0)
    {
        $search_data = array(
            'user_id' => $user_id,
            'trans_conf_id' => $trans_conf_id,
            'get_from_table' => $get_from_table,
            'get_from_table_id' => $get_from_table_id,
        );
        $query = $this->db->get_where('user_trans_history', $search_data);
        if (($query->num_rows() == 0 && $allow_duplicate == 0) || $allow_duplicate != 0)
        {
            $config_query = $this->db->get_where('transaction_config', array('trans_conf_id' => $trans_conf_id, 'conf_type' => 'uba'));
            if ($config_query->num_rows() == 1)
            {
                $config_result = $config_query->row_array();
                $amount_plus = 0;
                $amount_minus = 0;
                if ($config_result['change_type'] == 'dec')
                {
                    $amount_minus = $config_result['amount_change'];
                    //If is User Use Money, then the amount is key in by user
                    if ($trans_conf_id == 23)
                    {
                        $amount_minus = $amount_overwrite;
                    }
                }
                else
                {
                    $amount_plus = $config_result['amount_change'];
                }
                $the_data = array(
                    'user_id' => $user_id,
                    'trans_conf_id' => $trans_conf_id,
                    'amount_plus' => $amount_plus,
                    'amount_minus' => $amount_minus,
                    'get_from_table' => $get_from_table,
                    'get_from_table_id' => $get_from_table_id,
                );
                $this->db->insert('user_trans_history', $the_data);
            }
        }
    }
    
}
