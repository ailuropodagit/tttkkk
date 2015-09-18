<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_user extends CI_Model
{

    public function candie_balance_update($user_id, $month_id = NULL, $year = NULL)
    {
        $search_date = date_for_db_search($month_id, $year);

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

    public function user_redemption_insert($advertise_id)
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
                $voucher = $promotion_row['voucher'];
                $merchant_name = $this->m_custom->display_users($promotion_row['merchant_id']);
                if ($new_balance >= 0)
                {
                    $the_data = array(
                        'user_id' => $user_id,
                        'advertise_id' => $advertise_id,
                        'status_id' => $this->config->item('voucher_active'),
                        'expired_date' => $promotion_row['end_time'],
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
            'redeem_title' => $promotion_row['title'],
            'redeem_merchant' => $merchant_name,
            'redeem_expire' => displayDate($promotion_row['voucher_expire_date']),
            'redeem_email_subject' => $merchant_name . ' Keppo Voucher Success Redeem : ' . $voucher,
        );
        return $redeem_info;
    }

    public function candie_check_balance($user_id)
    {
        $this->m_user->candie_balance_update($user_id);
        $history_query = $this->db->get_where('candie_balance', array('user_id' => $user_id));
        $current_balance = 0;
        $history_result = $history_query->result_array();
        foreach ($history_result as $history_row)
        {
            $current_balance += $history_row['balance'];
        }
        return $current_balance;
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

}