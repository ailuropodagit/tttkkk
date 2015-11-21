<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_custom extends CI_Model
{

    //Get all the static option of an option type
    public function get_static_option_array($option_type = NULL, $default_value = NULL, $default_text = NULL, $want_array = 0)
    {
        $query = $this->db->get_where('static_option', array('option_type' => $option_type));
        $return = array();
        if ($default_value != NULL)
        {
            $return[$default_value] = $default_text;
        }
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $return[$row['option_id']] = $row['option_text'];
            }
        }
        if ($want_array == 1)
        {
            return $query->result_array();
        }
        else
        {
            return $return;
        }
    }

    public function explode_year_month($year_month)
    {
        $pieces = explode("-", $year_month);
        $return = array(
            'year' => $pieces[0],
            'month' => $pieces[1],
            'month_text' => $this->m_custom->display_static_option($pieces[1]),
            'month_year_text' => $this->m_custom->display_static_option($pieces[1]) . ' ' . $pieces[0],
            'month_first_date' => displayDate(displayFirstDay($pieces[0], $pieces[1]), 0, 1),
            'month_last_date' => displayDate(displayLastDay($pieces[0], $pieces[1]), 0, 1),
        );
        return $return;
    }

    //to do todo
    public function activity_check_access($act_history_id, $allow_other = 0)
    {
        $have_access = FALSE;

        if ($this->ion_auth->logged_in())
        {
            $login_id = $this->ion_auth->user()->row()->id;
            $login_type = $this->session->userdata('user_group_id');

            if (check_correct_login_type($this->config->item('group_id_admin')) || check_correct_login_type($this->config->item('group_id_worker')))
            {
                $have_access = TRUE;
            }

            $activity_query = $this->db->get_where('activity_history', array('act_history_id' => $act_history_id));
            if ($activity_query->num_rows() == 1)
            {
                $activity_row = $activity_query->row_array();
                $act_refer_type = $activity_row['act_refer_type'];
                $act_refer_id = $activity_row['act_refer_id'];
                $act_by_id = $activity_row['act_by_id'];
                $act_by_type = $activity_row['act_by_type'];

                if (check_correct_login_type($this->config->item('group_id_merchant')) || check_correct_login_type($this->config->item('group_id_supervisor')))
                {
                    $merchant_id = $login_id;
                    if ($act_by_id == $merchant_id && $act_by_type == $login_type)
                    {
                        $have_access = TRUE;
                    }

                    $sup_under_this_mer = $this->get_list_of_allow_id('users', 'su_merchant_id', $merchant_id, 'id');
                    if (in_array($act_by_id, $sup_under_this_mer) && $allow_other == 0)
                    {
                        $have_access = TRUE;
                    }

                    if (check_correct_login_type($this->config->item('group_id_supervisor')))
                    {
                        $merchant_id = $this->ion_auth->user()->row()->su_merchant_id;
                    }

                    if ($act_refer_type == 'adv')
                    {
                        $advertise_query = $this->db->get_where('advertise', array('advertise_id' => $act_refer_id));
                        if ($advertise_query->num_rows() == 1)
                        {
                            $advertise_row = $advertise_query->row_array();
                            if ($advertise_row['merchant_id'] == $merchant_id && $allow_other == 0)
                            {
                                $have_access = TRUE;
                            }
                        }
                    }
                    if ($act_refer_type == 'mua')
                    {
                        $mua_query = $this->db->get_where('merchant_user_album', array('merchant_user_album_id' => $act_refer_id));
                        if ($mua_query->num_rows() == 1)
                        {
                            $mua_row = $mua_query->row_array();
                            if ($mua_row['merchant_id'] == $merchant_id && $allow_other == 0)
                            {
                                $have_access = TRUE;
                            }
                        }
                    }
                    if ($act_refer_type == 'usa')
                    {
                        
                    }
                }

                if (check_correct_login_type($this->config->item('group_id_user')))
                {
                    if ($act_by_id == $login_id && $act_by_type == $login_type)
                    {
                        $have_access = TRUE;
                    }
                    if ($act_refer_type == 'mua')
                    {
                        $mua_query = $this->db->get_where('merchant_user_album', array('merchant_user_album_id' => $act_refer_id));
                        if ($mua_query->num_rows() == 1)
                        {
                            $mua_row = $mua_query->row_array();
                            if ($mua_row['user_id'] == $login_id && $allow_other == 0)
                            {
                                $have_access = TRUE;
                            }
                        }
                    }
                    if ($act_refer_type == 'usa')
                    {
                        
                    }
                }
            }
        }
        return $have_access;
    }

    public function check_role($the_table, $the_column, $the_value, $wanted_column)
    {
        $have_role = 0;
        $this->db->select($wanted_column);
        $this->db->from($the_table);
        $this->db->where($the_column, $the_value);
        $query = $this->db->get();
        //$query = $this->db->get_where($the_table, array($the_column => $the_value));
        $result = $query->row_array();
        if ($query->num_rows() > 0)
        {
            $have_role = $result[$wanted_column];
        }
        return $have_role;
    }

    public function check_role_su_can_uploadhotdeal()
    {
        $have_role = 1;
        if (check_correct_login_type($this->config->item('group_id_supervisor')))
        {
            $login_id = $this->ion_auth->user()->row()->id;
            $have_role = $this->m_custom->check_role('users', 'id', $login_id, 'su_can_uploadhotdeal');
        }
        return $have_role;
    }

    public function check_is_any_merchant()
    {
        if (check_correct_login_type($this->config->item('group_id_merchant')) || check_correct_login_type($this->config->item('group_id_supervisor')))
        {
            return TRUE;
        }
        return FALSE;
    }
    
    public function check_is_superuser($check_merchant_id = 0, $check_admin_only = 0)
    {
        $is_superuser = 0;
        $group_id_admin = $this->config->item('group_id_admin');
        $group_id_worker = $this->config->item('group_id_worker');
        $group_id_merchant = $this->config->item('group_id_merchant');
        $group_id_supervisor = $this->config->item('group_id_supervisor');
        if (check_correct_login_type($group_id_admin) || check_correct_login_type($group_id_worker))
        {
            $is_superuser = 1;
            if($check_admin_only == 1){
                return $is_superuser;
            }
        }
        if (check_correct_login_type($group_id_merchant) || check_correct_login_type($group_id_supervisor))
        {
            $merchant_id = $this->ion_auth->user()->row()->id;
            if (check_correct_login_type($group_id_supervisor))
            {
                $merchant_id = $this->ion_auth->user()->row()->su_merchant_id;
            }
            if ($merchant_id == $check_merchant_id)
            {
                $is_superuser = 1;
            }
        }
        return $is_superuser;
    }

    //To find many records in DB with one keyword
    public function get_list_of_allow_id($the_table, $the_column, $the_value, $wanted_column, $second_column = NULL, $second_value = NULL)
    {
        $query = $this->db->get_where($the_table, array($the_column => $the_value));

        if (!IsNullOrEmptyString($second_column) && !IsNullOrEmptyString($second_value))
        {
            $query = $this->db->get_where($the_table, array($the_column => $the_value, $second_column => $second_value));
        }

        $return = array();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $return[] = $row[$wanted_column];
            }
        }
        return $return;
    }

    public function month_group_list()
    {
        $month_list = $this->ion_auth->get_static_option_list('month');
        $month_list['q1'] = 'Quarter: Jan - Mar';
        $month_list['q2'] = 'Quarter: Apr - Jun';
        $month_list['q3'] = 'Quarter: Jul - Sep';
        $month_list['q4'] = 'Quarter: Oct - Dec';
        $month_list['h1'] = 'Semiannual: Jan - Jun';
        $month_list['h2'] = 'Semiannual: Jul - Dec';
        $month_list['y1'] = 'Yearly: Jan - Dec';
        return $month_list;
    }

    //Get one static option text by it option id
    public function display_category($category_id = NULL)
    {
        if (IsNullOrEmptyString($category_id))
        {
            return '';
        }

        $query = $this->db->get_where('category', array('category_id' => $category_id));
        if ($query->num_rows() !== 1)
        {
            return '';
        }
        return $query->row()->category_label;
    }

    //Get one static option text by it option id
    public function display_notification_message($id, $title = '', $public_show = 0, $noti_to_id = 0)
    {
        $query = $this->db->get_where('notification_message', array('msg_id' => $id));
        if ($query->num_rows() !== 1)
        {
            return '';
        }
        $result = $query->row_array();
        $msg_prefix = $result['msg_prefix'] == NULL ? "" : $result['msg_prefix'];
        $msg_front = $result['msg_front'] == NULL ? "" : $result['msg_front'];
        $msg_middle = $result['msg_middle'] == NULL ? "" : $result['msg_middle'];
        $msg_back = $result['msg_back'] == NULL ? "" : $result['msg_back'];
        $msg_postfix = $result['msg_postfix'] == NULL ? "" : $result['msg_postfix'];
        $msg_last = $result['msg_last'] == NULL ? "" : $result['msg_last'];
        
        if($public_show == 1){
            $msg_middle = $msg_middle == NULL ? "" : $this->m_custom->display_users($noti_to_id);
            $msg_last = $msg_last == NULL ? "" : $this->m_custom->display_users($noti_to_id);
        }
        
        $message = $msg_prefix . $msg_front . $msg_middle . $msg_back . $title . $msg_postfix . $msg_last;
        return $message;
    }

    //GET MAIN CATEGORY BY SUB CATEGORY ID
    public function display_main_category($category_id = NULL)
    {
        $this->db->select('');
        $this->db->from('category');
        $this->db->where('category_id', $category_id);
        $query = $this->db->get();
        $main_category_id = $query->row()->main_category_id;

        $this->db->select('category_label');
        $this->db->from('category');
        $this->db->where('category_id', $main_category_id);
        $query = $this->db->get();
        $main_category_label = $query->row()->category_label;
        return $main_category_label;
    }

    //Get one static option text by it option id
    public function display_users($user_id = NULL, $with_icon = 0, $want_supervisor = 0, $icon_only = 0)
    {
        if (IsNullOrEmptyString($user_id))
        {
            return '';
        }

        $prefix = '';
        $middle = '';
        $postfix = '';

        $query = $this->db->get_where('users', array('id' => $user_id));
        if ($query->num_rows() !== 1)
        {
            if ($with_icon == 1)
            {
                $prefix = $this->m_custom->display_user_profile_image($user_id);
            }
            if ($icon_only == 0)
            {
                $middle = 'User Deleted';
            }
            goto no_user;
        }

        $return = $query->row();

        if ($with_icon == 1)
        {
            $prefix = $this->m_custom->display_user_profile_image($user_id);
            if ($return->main_group_id == $this->config->item('group_id_merchant') && $icon_only == 0)
            {
                $postfix = ' <i class="fa fa-star"></i> ';
            }
        }

        if ($icon_only == 0)
        {
            if ($return->main_group_id == $this->config->item('group_id_merchant'))
            {
                $middle = $return->company;
            }
            else if ($return->main_group_id == $this->config->item('group_id_supervisor'))
            {
                if ($want_supervisor == 1)
                {
                    $middle = 'Supervisor: ' . $return->username;
                }
                else
                {
                    $merchant_query = $this->db->get_where('users', array('id' => $return->su_merchant_id));
                    $merchant_row = $merchant_query->row_array();
                    $middle = $merchant_row['company'];
                }
            }
            else
            {
                $middle = $return->first_name . ' ' . $return->last_name;
            }
        }
        no_user:
        return $prefix . $middle . $postfix;
    }

    public function display_user_profile_image($user_id)
    {
        $this->db->select('main_group_id,profile_image,su_merchant_id');
        $query = $this->db->get_where('users', array('id' => $user_id));
        if ($query->num_rows() > 0)
        {
            $return = $query->row_array();
            if ($return['main_group_id'] == $this->config->item('group_id_merchant'))
            {
                $image_path = $this->config->item('album_merchant_profile');
                $image = $return['profile_image'];
            }
            else if ($return['main_group_id'] == $this->config->item('group_id_supervisor'))
            {
                $image_path = $this->config->item('album_merchant_profile');
                $this->db->select('profile_image');
                $merchant_query = $this->db->get_where('users', array('id' => $return['su_merchant_id']));
                $merchant_row = $merchant_query->row_array();
                $image = $merchant_row['profile_image'];
            }
            else if ($return['main_group_id'] == $this->config->item('group_id_user'))
            {
                $image_path = $this->config->item('album_user_profile');
                $image = $return['profile_image'];
            }
        }
        else
        {
            return "<div id='notification-table-photo-box' style='display:inline-block'><img src='#' ></div> ";
        }
        return "<div id='notification-table-photo-box' style='display:inline-block'><img src=" . base_url() . $image_path . $image . " ></div> ";
    }

    public function display_trans_config($trans_conf_id = NULL)
    {
        $return_amount = 0;
        if (IsNullOrEmptyString($trans_conf_id))
        {
            return $return_amount;
        }

        $query = $this->db->get_where('transaction_config', array('trans_conf_id' => $trans_conf_id));
        if ($query->num_rows() !== 1)
        {
            return $return_amount;
        }
        $result = $query->row_array();
        $amount = $result['amount_change'];
        switch ($result['conf_type'])
        {
            case 'can':
                $return_amount = number_format($amount, 0);
                break;
            case 'bal':
            case 'uba':
                $return_amount = number_format($amount, 2);
                break;
        }
        return $return_amount;
    }

    //Get one static option text by it option id
    public function display_static_option($option_id = NULL)
    {
        if (IsNullOrEmptyString($option_id))
        {
            return '';
        }

        $query = $this->db->get_where('static_option', array('option_id' => $option_id));
        if ($query->num_rows() !== 1)
        {
            return '';
        }
        return $query->row()->option_text;
    }

    //Get one static option text by it option id
    public function display_dynamic_option($option_id = NULL, $prefix = NULL, $postfix = NULL, $use_option_title = 0)
    {
        if (IsNullOrEmptyString($option_id))
        {
            return '';
        }

        $query = $this->db->get_where('dynamic_option', array('option_id' => $option_id));
        if ($query->num_rows() !== 1)
        {
            return '';
        }

        $row = $query->row_array();
        if ($use_option_title == 1)
        {
            $the_text = $row['option_title'];
        }
        else
        {
            $the_text = $row['option_desc'];
        }

        if (!IsNullOrEmptyString($prefix) && $row['option_special'] == 1)
        {
            $the_text = $prefix . ' ' . $the_text;
        }
        if (!IsNullOrEmptyString($postfix) && $row['option_special'] == 2)
        {
            $the_text = $the_text . ' ' . $postfix;
        }

        return $the_text;
    }

    //Get all the dynamic option of an option type
    public function get_dynamic_option_array($option_type, $default_value = NULL, $default_text = NULL, $prefix = NULL, $postfix = NULL, $use_option_title = 0, $option_level = 0)
    {
        switch ($option_level)
        {
            case '0':
                $this->db->where_in('option_level', array(0, 1, 2));
                break;
            case '1':
                $this->db->where_in('option_level', array(0, 1));
                break;
            case '2':
                $this->db->where_in('option_level', array(0, 2));
                break;
            case '3':
                $this->db->where_in('option_level', array(0));
                break;
        }
        $query = $this->db->get_where('dynamic_option', array('option_type' => $option_type, 'hide_flag' => 0));

        $return = array();
        if (!IsNullOrEmptyString($default_value))
        {
            $return[$default_value] = $default_text;
        }
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                if ($use_option_title == 1)
                {
                    $the_text = $row['option_title'];
                }
                else
                {
                    $the_text = $row['option_desc'];
                }

                if (!IsNullOrEmptyString($prefix) && $row['option_special'] == 1)
                {
                    $the_text = $prefix . ' ' . $the_text;
                }
                if (!IsNullOrEmptyString($postfix) && $row['option_special'] == 2)
                {
                    $the_text = $the_text . ' ' . $postfix;
                }

                $return[$row['option_id']] = $the_text;
            }
        }

        return $return;
    }

    function web_setting_get($set_type, $field_name = 'set_int', $want_row = 0)
    {
        $query = $this->db->get_where('web_setting', array('set_type' => $set_type));
        $return = "0";
        if ($query->num_rows() == 1)
        {
            $result = $query->row_array();
            $return = $result[$field_name];
            if ($want_row == 1)
            {
                $return = $result;
            }
        }
        return $return;
    }

    function web_setting_set($set_type, $update_value = NULL, $field_name = 'set_int')
    {
        if (!IsNullOrEmptyString($update_value))
        {
            $the_data = array(
                $field_name => $update_value,
            );

            if ($this->m_custom->compare_before_update('web_setting', $the_data, 'set_type', $set_type))
            {
                if ($this->ion_auth->logged_in())
                {
                    $login_id = $this->ion_auth->user()->row()->id;
                    $the_data = array(
                        $field_name => $update_value,
                        'last_modify_by' => $login_id,
                    );
                }

                $this->db->where('set_type', $set_type);
                $this->db->update('web_setting', $the_data);
            }
        }
    }

    function get_keyarray_list($the_table, $id_column, $id_value, $key_column, $value_column, $default_value = NULL, $default_text = NULL)
    {
        $query = $this->db->get_where($the_table, array($id_column => $id_value));
        $return = array();
        if (!IsNullOrEmptyString($default_value))
        {
            $return[$default_value] = $default_text;
        }
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $return[$row[$key_column]] = $row[$value_column];
            }
        }
        return $return;
    }

    //To check is this value is unique in DB
    public function check_is_value_unique($the_table, $the_column, $the_value, $the_id_column = NULL, $the_id = NULL)
    {
        if (empty($the_value))
        {
            return FALSE;
        }

        if (!empty($the_id) && is_numeric($the_id))
        {
            $username_old = $this->db->where($the_id_column, $the_id)->get($the_table)->row()->$the_column;
            $this->db->where($the_column . "!=", $username_old);
        }

        $num_row = $this->db->where($the_column, $the_value)->get($the_table)->num_rows();
        if ($num_row > 0)
        {
            return FALSE;
        }
        return TRUE;
    }

    //To find one record in DB with one keyword
    public function get_one_table_record($the_table, $the_column, $the_value, $want_array = 0, $show_not_hide_only = 0)
    {
        if (empty($the_value))
        {
            return FALSE;
        }

        if ($show_not_hide_only == 1)
        {
            $this->db->where('hide_flag', 0);
        }

        $query = $this->db->get_where($the_table, array($the_column => $the_value), 1);
        if ($query->num_rows() !== 1)
        {
            return FALSE;
        }

        if ($want_array == 1)
        {
            return $query->row_array();
        }
        else
        {
            return $query->row();
        }
    }

    public function get_one_field_by_key($the_table, $key_column, $key_value, $wanted_column)
    {
        $query = $this->db->get_where($the_table, array($key_column => $key_value), 1);
        if ($query->num_rows() == 0)
        {
            return FALSE;
        }
        else
        {
            $result = $query->row_array();
            return $result[$wanted_column];
        }
    }

    //To find many records in DB with one keyword
    public function get_many_table_record($the_table, $the_column, $the_value, $want_array = 0, $second_column = NULL, $second_value = NULL)
    {
        if (!IsNullOrEmptyString($second_column) && !IsNullOrEmptyString($second_value))
        {
            $this->db->where($second_column, $second_value);
        }
        
        $query = $this->db->get_where($the_table, array($the_column => $the_value));

        if ($want_array == 1)
        {
            return $query->result_array();
        }
        else
        {
            return $query->result();
        }
    }

    //To find one record in DB of parent table with one keyword
    public function get_parent_table_record($the_table, $the_column, $the_value, $foreign_column, $parent_table, $primary_column, $want_array = 0)
    {
        if (empty($the_value))
        {
            return FALSE;
        }
        $query = $this->db->get_where($the_table, array($the_column => $the_value), 1);
        if ($query->num_rows() !== 1)
        {
            return FALSE;
        }

        $foreign_row = $query->row();
        $foreign_key = $foreign_row->$foreign_column;
        $parent_query = $this->db->get_where($parent_table, array($primary_column => $foreign_key), 1);
        if ($parent_query->num_rows() !== 1)
        {
            return FALSE;
        }

        if ($want_array == 1)
        {
            return $parent_query->row_array();
        }
        else
        {
            return $parent_query->row();
        }
    }

    public function get_image_url($the_table = NULL, $the_id = NULL)
    {
        if (empty($the_table) || empty($the_id))
        {
            return FALSE;
        }
        $key_column = $this->m_custom->table_id_column($the_table);
        $query = $this->db->get_where($the_table, array($key_column => $the_id), 1);
        if ($query->num_rows() == 0)
        {
            return FALSE;
        }
        else
        {
            $result = $query->row_array();           
            $image_url = '';
            switch ($the_table)
            {
                case 'advertise':
                    $image_name = $result['image'];
                    if ($result['advertise_type'] == 'adm')
                    {
                        $image_url = base_url($this->config->item('album_admin') . $image_name);
                    }
                    else
                    {
                        $image_url = base_url($this->config->item('album_merchant') . $image_name);
                    }
                    break;
                case 'merchant_user_album':
                    $image_name = $result['image'];
                    $image_url = base_url($this->config->item('album_user_merchant') . $image_name);
                    break;
                case 'user_album':
                    $image_name = $result['image'];
                    $image_url = base_url($this->config->item('album_user') . $image_name);
                    break;
                case 'users':
                    $image_name = $result['profile_image'];
                    if ($result['main_group_id'] == $this->config->item('group_id_merchant'))
                    {
                        $image_url = base_url($this->config->item('album_merchant_profile') . $image_name);
                    }
                    else if ($result['main_group_id'] == $this->config->item('group_id_user'))
                    {
                        $image_url = base_url($this->config->item('album_user_profile') . $image_name);
                    }
            }

            return $image_url;
        }
    }

    //To find one advertise record in DB
    public function getOneAdvertise($advertise_id, $ignore_have_money = 0, $ignore_hide = 0, $ignore_startend = 0)
    {
        if ($ignore_hide == 0)
        {
            $this->db->where('hide_flag', 0);
        }
        if ($ignore_startend == 0)
        {
            $this->db->where('start_time is not null AND end_time is not null');
        }
        $query = $this->db->get_where('advertise', array('advertise_id' => $advertise_id), 1);
        if ($query->num_rows() !== 1)
        {
            return FALSE;
        }

        $return = $query->row_array();
        if (($this->m_merchant->have_money($return['merchant_id']) && $ignore_have_money == 0) || $ignore_have_money != 0)
        {
            $valid_row = $this->m_custom->check_can_show_advertise($return);
            if ($valid_row == 1)
            {
                return $return;
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            return FALSE;
        }
    }

    //To find one record in DB with one keyword
    public function getOneMUA($mua_id, $ignore_have_money = 0)
    {
        $return = $this->m_custom->get_one_table_record('merchant_user_album', 'merchant_user_album_id', $mua_id, 1, 1);

        if (($this->m_merchant->have_money($return['merchant_id']) && $ignore_have_money == 0) || $ignore_have_money != 0)
        {
            return $return;
        }
        else
        {
            return FALSE;
        }
    }

    //To find one record in DB with one keyword
    public function getOneUserPicture($picture_id)
    {
        $the_row = $this->m_custom->get_one_table_record('user_album', 'user_album_id', $picture_id, 1, 1);

        return $the_row;
    }

    public function getOneUserRedemption($redeem_id)
    {
        $return = $this->m_custom->get_one_table_record('user_redemption', 'redeem_id', $redeem_id, 1);
        return $return;
    }
    
    //set $hot_popular_only = 1 to get the popular hotdeal or redemption only
    //Get popular hotdeal that have at least 3 like, can change number in database web setting table
    //$popular_hotdeal_list = $this->m_custom->getAdvertise('hot', NULL, NULL, 0, NULL, NULL, 1)
    //Get popular redemption that have at least 3 user's redemption, can change number in database web setting table
    //$popular_redemption_list = $this->m_custom->getAdvertise('pro', NULL, NULL, 0, NULL, NULL, 1)
    function getAdvertise($advertise_type, $sub_category_id = NULL, $merchant_id = NULL, $show_expired = 0, $limit = NULL, $start = NULL, $hot_popular_only = 0, $ignore_startend = 0, $ignore_hide = 0)
    {
        if (!IsNullOrEmptyString($sub_category_id))
        {
            $this->db->where('sub_category_id', $sub_category_id);
        }
        if (!IsNullOrEmptyString($merchant_id))
        {
            $this->db->where('merchant_id', $merchant_id);
        }
        if ($show_expired == 0)
        {
            $this->db->where('end_time >=', get_part_of_date('all'));
        }               
        if($ignore_startend == 0){
            $this->db->where('start_time is not null AND end_time is not null');
        }
        if ($ignore_hide == 0)
        {
            $this->db->where('hide_flag', 0);
        }
        $this->db->order_by("advertise_id", "desc");
        
        if (!IsNullOrEmptyString($limit) && !IsNullOrEmptyString($start))
        {
            if ($start == 1)
            {
                $start = 0;
            } //For fix skip first index problem on pagination
            $this->db->limit($limit, $start);
        }

        if ($advertise_type == 'all')
        {
            $query = $this->db->get_where('advertise', array());
        }
        else
        {
            $query = $this->db->get_where('advertise', array('advertise_type' => $advertise_type));
        }
        //var_dump($query->result_array());
        $return = $query->result_array();
        $return_final = array();
        foreach ($return as $row)
        {
            $valid_row = 0;
            $get_row = $this->m_custom->check_can_show_advertise($row);
            if ($get_row == 1)
            {
                $valid_row = 1;
                //For get popular hotdeal or redemption only
                if ($hot_popular_only == 1)
                {
                    $the_advertise_id = $row['advertise_id'];
                    $the_advertise_type = $row['advertise_type'];
                    switch ($the_advertise_type)
                    {
                        case 'hot':
                            $like_count = $this->m_custom->activity_like_count($the_advertise_id, 'adv');
                            $popular_hotdeal_number = $this->m_custom->web_setting_get('popular_hotdeal_number');
                            if ($like_count >= $popular_hotdeal_number)
                            {
                                $valid_row = 1;
                            }
                            else
                            {
                                $valid_row = 0;
                            }
                            break;
                        case 'pro':
                            $redeem_count = $this->m_custom->promotion_redeem_count($the_advertise_id);
                            $popular_redemption_number = $this->m_custom->web_setting_get('popular_redemption_number');
                            if ($redeem_count >= $popular_redemption_number)
                            {
                                $valid_row = 1;
                            }
                            else
                            {
                                $valid_row = 0;
                            }
                            break;
                    }
                }
            }

            if($valid_row == 1){
                $return_final[] = $row;
            }
        }
        
        return $return_final;
    }

    //To get merchant promotion list with branch filter or history only
    function getPromotion($merchant_id, $supervisor_id = 0, $show_history = 0)
    {
        $branch_id = 0;
        if ($supervisor_id != 0)
        {
            $supervisor = $this->getUser($supervisor_id);
            $branch_id = $supervisor['su_branch_id'];
        }

        if ($show_history == 0)
        {
            $this->db->where('voucher_expire_date >=', get_part_of_date('all'));
        }
        else
        {
            $this->db->where('voucher_expire_date <', get_part_of_date('all'));
        }

        $this->db->order_by("advertise_id", "desc");
        $this->db->where('start_time is not null AND end_time is not null');

        $original_query = $this->db->get_where('advertise', array('advertise_type' => 'pro', 'hide_flag' => 0, 'merchant_id' => $merchant_id));
        $original_result = $original_query->result_array();
        $advertise_list[] = NULL;
        foreach ($original_result as $original_row)
        {
            $advertise_id = $original_row['advertise_id'];
            if ($branch_id == 0)
            {
                $advertise_list[] = $advertise_id;
            }
            else
            {
                $many_list = $this->many_get_childlist('candie_branch', $advertise_id);
                if (in_array($branch_id, $many_list))
                {
                    //var_dump($many_list);
                    $advertise_list[] = $advertise_id;
                }
            }
        }

        $this->db->order_by("advertise_id", "desc");
        if (!empty($advertise_list))
        {
            $this->db->where_in('advertise_id', $advertise_list);
        }
        $advertise_query = $this->db->get_where('advertise', array('advertise_type' => 'pro', 'hide_flag' => 0, 'merchant_id' => $merchant_id));

        return $advertise_query->result_array();
    }

    function getAlbumUserMerchant($user_id = NULL, $merchant_id = NULL)
    {
        if (!IsNullOrEmptyString($user_id))
        {
            $this->db->where('user_id', $user_id);
        }
        if (!IsNullOrEmptyString($merchant_id))
        {
            $this->db->where('merchant_id', $merchant_id);
        }

        $this->db->order_by("merchant_user_album_id", "desc");
        $query = $this->db->get_where('merchant_user_album', array('post_type' => 'mer', 'hide_flag' => 0));

        $return = $query->result_array();
        $return_final = array();
        foreach ($return as $row)
        {
            if ($this->m_merchant->have_money($row['merchant_id']))
            {
                $return_final[] = $row;
            }
        }

        return $return_final;
    }

    function getAlbumUser($user_id = NULL)
    {
        if (!IsNullOrEmptyString($user_id))
        {
            $this->db->where('user_id', $user_id);
        }

        $this->db->order_by("user_album_id", "desc");
        $query = $this->db->get_where('user_album', array('hide_flag' => 0));
        return $query->result_array();
    }

    //Get all the main category
    public function getCategoryList($default_value = NULL, $default_text = NULL, $get_special = 0, $ignore_hide = 0)
    {
        if ($get_special == 0)
        {
            $this->db->where('hide_special', 0);
        }
        if ($ignore_hide == 0)
        {
            $this->db->where('hide_flag', 0);
        }
        $query = $this->db->get_where('category', array('category_level' => '0'));
        $return = array();
        if ($default_value != NULL)
        {
            $return[$default_value] = $default_text;
        }
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $return[$row['category_id']] = $row['category_label'];
            }
        }
        return $return;
    }

    //Get all the static option of an option type
    public function getSubCategoryList($default_value = NULL, $default_text = NULL, $id = 0)
    {
        $query = $this->db->get_where('category', array('category_level' => '1', 'hide_flag' => 0, 'main_category_id' => $id));
        $return = array();
        if ($default_value != NULL)
        {
            $return[$default_value] = $default_text;
        }
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $return[$row['category_id']] = $row['category_label'];
            }
        }
        return $return;
    }

    //To get all main category
    function getCategory($get_special = 0, $ignore_hide = 0, $get_all = 0, $want_array = 0, $main_id = 0)
    {
        if ($get_special == 0)
        {
            $this->db->where('hide_special', 0);
        }
        if ($ignore_hide == 0)
        {
            $this->db->where('hide_flag', 0);
        }
        if ($get_all == 0)
        {
            $this->db->where('category_level', 0);
        }
        if ($main_id != 0)
        {
            $this->db->where('main_category_id', $main_id);
        }
        
        $this->db->order_by('category_label');
        $this->db->from('category');
        $query = $this->db->get();
        if ($want_array == 1)
        {
            return $query->result_array();
        }
        else
        {
            return $query->result();
        }
    }
    
    function getAllUser(){
        $this->db->order_by('first_name');
        $query = $this->db->get_where('users', array('main_group_id' => $this->config->item('group_id_user')));
        $result = $query->result_array();
        return $result;
    }
    
    function getAllMerchant(){
        $this->db->order_by('company');
        $query = $this->db->get_where('users', array('main_group_id' => $this->config->item('group_id_merchant')));
        $result = $query->result_array();
        return $result;
    }
    
    function getUser($user_id, $main_group_id = NULL)
    {
        if (!IsNullOrEmptyString($main_group_id))
        {
            $this->db->where('main_group_id', $main_group_id);
        }

        $query = $this->db->get_where('users', array('id' => $user_id));
        return $query->row_array();
    }

    function getUserLoginInfo($user_id)
    {
        $this->db->select('id, username, email, password_visible, main_group_id, us_register_type, us_fb_id, slug, hide_flag');
        $query = $this->db->get_where('users', array('id' => $user_id));
        if ($query->num_rows() == 1)
        {
            return $query->row_array();
        }
        else
        {
            return FALSE;
        }
    }

    function getUserInfo($user_id)
    {
        $query = $this->db->get_where('users', array('id' => $user_id, 'main_group_id' => $this->config->item('group_id_user')));
        $user = $query->row_array();
        $user_info = array(
            'id' => $user['id'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'address' => $user['address'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'name' => $user['first_name'] . ' ' . $user['last_name'],
            'profile_image' => $user['profile_image'],
            'us_ic' => $user['us_ic'],
            'us_race_id' => $user['us_race_id'],
            'us_race_id_name' => $this->m_custom->display_static_option($user['us_race_id']),
            'us_race_other' => $user['us_race_other'],
            'us_age' => $user['us_age'],
            'us_gender_id' => $user['us_gender_id'],
            'us_gender_id_name' => $this->m_custom->display_static_option($user['us_gender_id']),
            'us_birthday' => $user['us_birthday'],
            'us_birthday_text' => displayDate($user['us_birthday']),
            'us_blog_url' => $user['us_blog_url'],
            'us_instagram_url' => $user['us_instagram_url'],
            'us_facebook_url' => $user['us_facebook_url'],
            'us_register_type' => $user['us_register_type'],
            'us_fb_id' => $user['us_fb_id'],
            'user_dashboard_url' => base_url() . "all/user-dashboard/" . $user_id,
            'user_dashboard_link' => $this->m_custom->generate_user_link($user_id),
        );
        return $user_info;
    }

    function getMerchantInfo($merchant_id)
    {
        $query = $this->db->get_where('users', array('id' => $merchant_id));
        $user = $query->row_array();
        $merchant = array(
            'id' => $user['id'],
            'email' => $user['email'],
            'company' => $user['company'],
            'slug' => $user['slug'],
            'phone' => $user['phone'],
            'address' => $user['address'],
            'profile_image' => $user['profile_image'],
            'me_ssm' => $user['me_ssm'],
            'me_ssm_file' => $user['me_ssm_file'],
            'me_category_id' => $user['me_category_id'],
            'me_state_id' => $user['me_state_id'],
            'me_google_map_url' => $user['me_google_map_url'],
            'me_website_url' => $user['me_website_url'],
            'me_facebook_url' => $user['me_facebook_url'],
            'me_category_name' => $this->m_custom->display_category($user['me_category_id']),
            'me_state_name' => $this->m_custom->display_static_option($user['me_state_id']),
            'merchant_dashboard_url' => base_url() . "all/merchant-dashboard/" . $user['slug'],
            'merchant_dashboard_link' => $this->m_custom->generate_merchant_link($merchant_id),
        );
        return $merchant;
    }

    //To get related sub category by pass in the main category id
    function getSubCategory($id, $ignore_hide = 0)
    {
        if ($ignore_hide == 0)
        {
            $this->db->where('hide_flag', 0);
        }
        $query = $this->db->get_where('category', array('main_category_id' => $id, 'category_level' => '1'));
        return $query->result();
    }

    function getBranchList($id, $want_array = 0)
    {
        $query = $this->db->get_where('merchant_branch', array('merchant_id' => $id));
        if ($want_array == 1)
        {
            return $query->result_array();
        }
        else
        {
            return $query->result();
        }
    }

    public function getBranchList_with_search($id, $search_word)
    {
        if (IsNullOrEmptyString($search_word))
        {
            return $this->getBranchList($id);
        }
        $search_word = $this->db->escape('%' . $search_word . '%');
        $this->db->where("(`name` LIKE $search_word OR `address` LIKE $search_word)");
        $query = $this->db->get_where('merchant_branch', array('merchant_id' => $id));
        if ($query->num_rows() == 0)
        {
            return $this->getBranchList(0);
        }
        return $query->result();
    }

    public function get_id_after_insert($the_table, $the_data)
    {
        if ($this->db->insert($the_table, $the_data))
        {
            $new_id = $this->db->insert_id();
            return $new_id;
        }
        return FALSE;
    }

    public function many_insert_or_remove($the_type, $parent_id, $new_child_list)
    {
        $query = $this->db->get_where('many_to_many', array('many_type' => $the_type, 'many_parent_id' => $parent_id))->result();
        $current_child_list = array();
        foreach ($query as $value)
        {
            $current_child_list[] = $value->many_child_id;
        }
        $child_to_insert = array_diff($new_child_list, $current_child_list);
        if (!empty($child_to_insert))
        {
            foreach ($child_to_insert as $value)
            {
                $the_data = array(
                    'many_parent_id' => $parent_id,
                    'many_child_id' => $value,
                    'many_type' => $the_type,
                );
                $this->db->insert('many_to_many', $the_data);
            }
        }

        $child_to_remove = array_diff($current_child_list, $new_child_list);
        if (!empty($child_to_remove))
        {
            foreach ($child_to_remove as $value)
            {
                $this->db->where(array('many_parent_id' => $parent_id, 'many_child_id' => $value));
                $this->db->delete('many_to_many');
            }
        }
    }

    //To get the childlist id from many table by the type and parent id
    public function many_get_childlist($the_type, $parent_id, $want_array = 0)
    {
        $query = $this->db->get_where('many_to_many', array('many_type' => $the_type, 'many_parent_id' => $parent_id));

        $return = array();
        if ($query->num_rows() > 0)
        {
            if ($want_array == 0)
            {
                foreach ($query->result_array() as $row)
                {
                    $return[] = $row['many_child_id'];
                }
            }
            else
            {
                return $query->result_array();
            }
        }
        return $return;
    }

    //To get the childlist id from many table by the type and parent id
    public function many_get_childlist_detail($the_type, $parent_id, $child_table, $child_wanted_column = NULL, $want_string = 0, $separator = ',')
    {        
        $query = $this->db->get_where('many_to_many', array('many_type' => $the_type, 'many_parent_id' => $parent_id));        
        $return = array();
        if ($query->num_rows() > 0)
        {
            $child_id_column = $this->m_custom->table_id_column($child_table);            
            foreach ($query->result_array() as $row)
            {
                $many_child_id = $row['many_child_id'];
                $child = $this->db->get_where($child_table, array($child_id_column => $many_child_id), 1);
                if ($child->num_rows() == 1)
                {
                    if($child_wanted_column == NULL){
                        $return[$many_child_id] = $child->row_array();
                    }else{
                        $result = $child->row_array();
                        $return[$many_child_id] = $result[$child_wanted_column];
                    }
                }
            }
        }
        if($want_string == 1 && $child_wanted_column != NULL){
            $return = arraylist_to_string($return, $separator);
        }
        return $return;
    }

    //To get the childlist id from many table by the type and parent id
    public function many_get_child_count($the_type, $parent_id)
    {
        $query = $this->db->get_where('many_to_many', array('many_type' => $the_type, 'many_parent_id' => $parent_id));

        return $query->num_rows();
    }

    public function many_check_and_insert($the_type, $parent_id, $child_id, $remark = NULL)
    {
        if (!IsNullOrEmptyString($remark))
        {
            $this->db->where('many_remark', $remark);
        }

        $query = $this->db->get_where('many_to_many', array('many_type' => $the_type, 'many_parent_id' => $parent_id, 'many_child_id' => $child_id));
        if ($query->num_rows() == 0)
        {
            $the_data = array(
                'many_type' => $the_type,
                'many_parent_id' => $parent_id,
                'many_child_id' => $child_id,
                'many_remark' => $remark,
            );
            $this->db->insert('many_to_many', $the_data);
            $insert_id = $this->db->insert_id();
            $merchant_id = $this->m_merchant->get_merchant_id_from_advertise($parent_id);
            switch ($the_type)
            {
                case 'view_advertise':
                    $this->m_user->candie_history_insert(1, $insert_id, 'many_to_many');
                    $this->m_merchant->transaction_history_insert($merchant_id, 11, $insert_id, 'many_to_many');
                    break;
            }
        }
    }

    // refer type = 'adv', 'mua'
    public function activity_view($advertise_id, $refer_type)
    {
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            $user_id = $this->ion_auth->user()->row()->id;
            $this->many_check_and_insert('view_advertise', $advertise_id, $user_id, $refer_type);
        }
    }

    public function activity_view_count($advertise_id)
    {
        return $this->many_get_child_count('view_advertise', $advertise_id);
    }

    public function activity_hide($act_history_id)
    {
        if ($this->ion_auth->logged_in())
        {
            $login_id = $this->ion_auth->user()->row()->id;
            $login_type = $this->session->userdata('user_group_id');
            $the_data = array(
                'hide_flag' => 1,
                'hide_time' => get_part_of_date('all'),
                'hide_by' => $login_id,
                'hide_by_type' => $login_type,
            );
            $this->db->where('act_history_id', $act_history_id);
            $this->db->update('activity_history', $the_data);
        }
    }

    public function activity_check_and_insert($the_type, $refer_id, $refer_type, $by_id, $by_type, $allow_duplicate = 0, $rating = NULL, $comment = NULL)
    {
        $search_data = array(
            'act_type' => $the_type,
            'act_refer_id' => $refer_id,
            'act_refer_type' => $refer_type,
            'act_by_id' => $by_id,
            'act_by_type' => $by_type,
        );
        $query = $this->db->get_where('activity_history', $search_data);
        if (($query->num_rows() == 0 && $allow_duplicate == 0) || $allow_duplicate != 0)
        {
            $the_data = array(
                'act_type' => $the_type,
                'act_refer_id' => $refer_id,
                'act_refer_type' => $refer_type,
                'act_by_id' => $by_id,
                'act_by_type' => $by_type,
                'rating' => $rating == NULL ? NULL : $rating,
                'comment' => $comment == NULL ? NULL : $comment,
            );
            $this->db->insert('activity_history', $the_data);
            $insert_id = $this->db->insert_id();
            if ($refer_type == 'mua')
            {
                $merchant_id = $this->m_merchant->get_merchant_id_from_mua($refer_id);
            }
            else
            {
                $merchant_id = $this->m_merchant->get_merchant_id_from_advertise($refer_id);
            }
            switch ($the_type)
            {
                case 'like':
                    $this->m_user->candie_history_insert(2, $insert_id);
                    $this->m_merchant->transaction_history_insert($merchant_id, 12, $insert_id);
                    $this->m_custom->notification_process('activity_history', $insert_id);
                    break;
                case 'rating':
                    $this->m_user->candie_history_insert(3, $insert_id);
                    $this->m_merchant->transaction_history_insert($merchant_id, 13, $insert_id);
                    $this->m_custom->notification_process('activity_history', $insert_id);
                    break;
                case 'comment':
                    $this->m_custom->notification_process('activity_history', $insert_id);
            }
        }
    }

    public function activity_check_is_exist($the_type, $refer_id, $refer_type, $by_id, $by_type)
    {
        $search_data = array(
            'act_type' => $the_type,
            'act_refer_id' => $refer_id,
            'act_refer_type' => $refer_type,
            'act_by_id' => $by_id,
            'act_by_type' => $by_type,
        );
        $query = $this->db->get_where('activity_history', $search_data);
        if ($query->num_rows() == 0)
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function activity_rating_is_exist($refer_id, $refer_type)
    {
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            $user_id = $this->ion_auth->user()->row()->id;
            return $this->activity_check_is_exist('rating', $refer_id, $refer_type, $user_id, $this->config->item('group_id_user'));
            ;
        }
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function activity_rating($refer_id, $refer_type, $rating)
    {
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            $user_id = $this->ion_auth->user()->row()->id;
            $this->activity_check_and_insert('rating', $refer_id, $refer_type, $user_id, $this->config->item('group_id_user'), 0, $rating);
            return TRUE;
        }
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function activity_rating_this_user($refer_id, $refer_type)
    {
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            $user_id = $this->ion_auth->user()->row()->id;
            $the_data = array(
                'act_type' => 'rating',
                'act_refer_id' => $refer_id,
                'act_refer_type' => $refer_type,
                'act_by_id' => $user_id,
                'act_by_type' => $this->config->item('group_id_user'),
            );
            $query = $this->db->get_where('activity_history', $the_data);
            if ($query->num_rows() == 1)
            {
                $result = $query->row_array();
                return $result['rating'];
            }
        }
        return 'NA';
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function activity_rating_average($refer_id, $refer_type)
    {
        $query = $this->db->get_where('activity_history', array('act_type' => 'rating', 'act_refer_id' => $refer_id, 'act_refer_type' => $refer_type));
        $rate_count = $query->num_rows();
        $total_rate = 0;
        foreach ($query->result_array() as $row)
        {
            $total_rate += $row['rating'];
        }
        if ($rate_count == 0)
        {
            return 0;
        }
        else
        {
            return ($total_rate / $rate_count);
        }
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function activity_rating_count($refer_id, $refer_type)
    {
        $query = $this->db->get_where('activity_history', array('act_type' => 'rating', 'act_refer_id' => $refer_id, 'act_refer_type' => $refer_type));
        $rate_count = $query->num_rows();
        return $rate_count;
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function activity_comment($refer_id, $refer_type, $comment)
    {
        if ($this->ion_auth->logged_in())
        {
            $login_id = $this->ion_auth->user()->row()->id;
            $login_type = $this->session->userdata('user_group_id');
            $this->activity_check_and_insert('comment', $refer_id, $refer_type, $login_id, $login_type, 1, NULL, $comment);
            return TRUE;
        }
    }

    public function activity_comment_select($act_history_id)
    {
        if ($this->ion_auth->logged_in())
        {
            $login_id = $this->ion_auth->user()->row()->id;
            $login_type = $this->session->userdata('user_group_id');
            $query = $this->db->get_where('activity_history', array('act_history_id' => $act_history_id, 'act_type' => 'comment', 'act_by_id' => $login_id, 'act_by_type' => $login_type));
            if ($query->num_rows() == 1)
            {
                return $query->row_array();
            }
            else
            {
                return FALSE;
            }
        }
    }

    public function activity_comment_update($act_history_id, $comment)
    {
        if ($this->ion_auth->logged_in())
        {
            $login_id = $this->ion_auth->user()->row()->id;
            $login_type = $this->session->userdata('user_group_id');
            $query = $this->db->get_where('activity_history', array('act_history_id' => $act_history_id, 'act_type' => 'comment', 'act_by_id' => $login_id, 'act_by_type' => $login_type));
            if ($query->num_rows() == 1)
            {
                $data = array(
                    'comment' => $comment,
                );
                $this->m_custom->simple_update('activity_history', $data, 'act_history_id', $act_history_id);
            }
            else
            {
                return FALSE;
            }
        }
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function activity_comment_count($refer_id, $refer_type, $want_array = 0)
    {
        $this->db->order_by("act_history_id", "asc");
        $query = $this->db->get_where('activity_history', array('act_type' => 'comment', 'act_refer_id' => $refer_id, 'act_refer_type' => $refer_type, 'hide_flag' => 0));

        if ($want_array == 0)
        {
            return $query->num_rows();
        }
        else
        {
            return $query->result_array();
        }
    }

    public function activity_comment_hide($act_history_id)
    {
        $this->m_custom->activity_hide($act_history_id);
        if ($this->ion_auth->logged_in())
        {
            $activity = $this->db->get_where('activity_history', array('act_history_id' => $act_history_id), 1)->row_array();

            $login_id = $this->ion_auth->user()->row()->id;
            $login_type = $this->session->userdata('user_group_id');

            if ($activity['act_by_id'] == $login_id && $activity['act_by_type'] == $login_type)
            {
                
            }
            else
            {
                $mon_hide_type = 'com';
                $mon_table_id = $act_history_id;
                $mon_table = 'activity_history';
                $this->m_custom->insert_row_monitor_process($mon_hide_type, $mon_table_id, $mon_table, $login_type);
            }
        }
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function activity_like_is_exist($refer_id, $refer_type)
    {
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            $user_id = $this->ion_auth->user()->row()->id;
            return $this->activity_check_is_exist('like', $refer_id, $refer_type, $user_id, $this->config->item('group_id_user'));
            ;
        }
    }

    public function activity_like_user_list($refer_id, $refer_type)
    {
        $query = $this->m_custom->activity_like_count($refer_id, $refer_type, 1);

        $return = array();
        foreach ($query as $row)
        {
            $return[] = $this->m_custom->generate_user_link($row['act_by_id'], 1);
        }

        return $return;
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function activity_like($refer_id, $refer_type)
    {
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            $user_id = $this->ion_auth->user()->row()->id;
            $this->m_custom->activity_check_and_insert('like', $refer_id, $refer_type, $user_id, $this->config->item('group_id_user'));
        }
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function activity_like_count($refer_id, $refer_type, $want_array = 0)
    {
        $query = $this->db->get_where('activity_history', array('act_type' => 'like', 'act_refer_id' => $refer_id, 'act_refer_type' => $refer_type));

        if ($want_array == 0)
        {
            return $query->num_rows();
        }
        else
        {
            return $query->result_array();
        }
    }

    public function activity_get_one_row($activity_id)
    {
        $query = $this->db->get_where('activity_history', array('act_history_id' => $activity_id));
        if ($query->num_rows() == 1)
        {
            return $query->row_array();
        }
        else
        {
            return FALSE;
        }
    }

    public function promotion_redeem_count($advertise_id = NULL, $want_array = 0)
    {
        $query = $this->db->get_where('user_redemption', array('advertise_id' => $advertise_id));

        if ($want_array == 0)
        {
            return $query->num_rows();
        }
        else
        {
            return $query->result_array();
        }
    }
    
    public function notification_process($noti_refer_table = NULL, $noti_refer_table_id = NULL, $user_follow_id = NULL)
    {
        switch ($noti_refer_table)
        {
            case 'activity_history':
                $this->m_custom->notification_process_activity($noti_refer_table, $noti_refer_table_id);
                break;
            case 'merchant_user_album':
                $result = $this->m_custom->getOneMUA($noti_refer_table_id, 1);
                $noti_url = 'all/merchant_user_picture/' . $noti_refer_table_id;
                $this->m_custom->notification_insert($result['merchant_id'], 10, $noti_url, $noti_refer_table, 'merchant_user_album_id', $noti_refer_table_id);
                break;
            case 'user_redemption':
                $result = $this->m_custom->get_one_table_record('user_redemption', 'redeem_id', $noti_refer_table_id, 1);
                $noti_url = 'all/advertise/' . $result['advertise_id'];
                $advertise = $this->m_custom->getOneAdvertise($result['advertise_id']);
                $this->m_custom->notification_insert($advertise['merchant_id'], 11, $noti_url, 'advertise', 'advertise_id', $result['advertise_id']);
                break;
            case 'advertise':
                $noti_url = 'all/advertise/' . $noti_refer_table_id;
                $advertise = $this->m_custom->getOneAdvertise($noti_refer_table_id);
                $this->m_custom->notification_insert($advertise['merchant_id'], 12, $noti_url, 'advertise', 'advertise_id', $noti_refer_table_id);
                break;
            case 'user_follow':
                $result = $this->m_custom->get_one_table_record('user_follow', 'follow_id', $noti_refer_table_id, 1);
                $query = $this->db->get_where('users', array('id' => $user_follow_id));
                $user_row = $query->row_array();
                $noti_url = '';
                switch ($user_row['main_group_id']){
                    case $this->config->item('group_id_merchant'):
                        $noti_url = 'all/merchant_dashboard/' . $user_row['slug'];
                        break;
                    case $this->config->item('group_id_user'):
                        $noti_url = 'all/user_dashboard/' . $user_follow_id;
                        break;
                }
                $this->m_custom->notification_insert($result['following_id'], 13, $noti_url, 'users', 'id', $user_follow_id);
                break;
        }
    }

    //todo to do
    public function notification_process_activity($noti_refer_table = NULL, $noti_refer_table_id = NULL)
    {
        $query = $this->m_custom->activity_get_one_row($noti_refer_table_id);
        $act_type = $query['act_type'];
        $refer_type = $query['act_refer_type'];
        $refer_id = $query['act_refer_id'];
        switch ($act_type)
        {
            case "like":
                if ($refer_type == "mua")
                {
                    $result = $this->m_custom->getOneMUA($refer_id, 1);
                    $noti_url = 'all/merchant_user_picture/' . $result['merchant_user_album_id'];
                    $this->m_custom->notification_insert($result['merchant_id'], 3, $noti_url, 'merchant_user_album', 'merchant_user_album_id', $result['merchant_user_album_id']);
                }
                else if ($refer_type == "usa")
                {
                    $result = $this->m_custom->getOneUserPicture($refer_id);
                    $noti_url = 'all/user_picture/' . $result['user_album_id'];
                    $this->m_custom->notification_insert($result['user_id'], 3, $noti_url, 'user_album', 'user_album_id', $result['user_album_id']);
                }
                else if ($refer_type == "adv")
                {
                    $result = $this->m_custom->getOneAdvertise($refer_id);
                    $noti_url = 'all/advertise/' . $result['advertise_id'];
                    if ($result['advertise_type'] == "hot")
                    {
                        $this->m_custom->notification_insert($result['merchant_id'], 1, $noti_url, 'advertise', 'advertise_id', $result['advertise_id']);
                    }
                    else if ($result['advertise_type'] == "pro")
                    {
                        $this->m_custom->notification_insert($result['merchant_id'], 2, $noti_url, 'advertise', 'advertise_id', $result['advertise_id']);
                    }
                }
                break;
            case "rating":
                if ($refer_type == "mua")
                {
                    $result = $this->m_custom->getOneMUA($refer_id, 1);
                    $noti_url = 'all/merchant_user_picture/' . $result['merchant_user_album_id'];
                    $this->m_custom->notification_insert($result['merchant_id'], 6, $noti_url, 'merchant_user_album', 'merchant_user_album_id', $result['merchant_user_album_id'], $query['rating']);
                }
                else if ($refer_type == "usa")
                {
                    $result = $this->m_custom->getOneUserPicture($refer_id);
                    $noti_url = 'all/user_picture/' . $result['user_album_id'];
                    $this->m_custom->notification_insert($result['user_id'], 6, $noti_url, 'user_album', 'user_album_id', $result['user_album_id'], $query['rating']);
                }
                else if ($refer_type == "adv")
                {
                    $result = $this->m_custom->getOneAdvertise($refer_id);
                    $noti_url = 'all/advertise/' . $result['advertise_id'];
                    if ($result['advertise_type'] == "hot")
                    {
                        $this->m_custom->notification_insert($result['merchant_id'], 4, $noti_url, 'advertise', 'advertise_id', $result['advertise_id'], $query['rating']);
                    }
                    else if ($result['advertise_type'] == "pro")
                    {
                        $this->m_custom->notification_insert($result['merchant_id'], 5, $noti_url, 'advertise', 'advertise_id', $result['advertise_id'], $query['rating']);
                    }
                }
                break;
            case "comment":
                if ($refer_type == "mua")
                {
                    $result = $this->m_custom->getOneMUA($refer_id, 1);
                    $noti_url = 'all/merchant_user_picture/' . $result['merchant_user_album_id'];
                    $this->m_custom->notification_insert($result['merchant_id'], 9, $noti_url, 'merchant_user_album', 'merchant_user_album_id', $result['merchant_user_album_id']);
                }
                else if ($refer_type == "usa")
                {
                    $result = $this->m_custom->getOneUserPicture($refer_id);
                    $noti_url = 'all/user_picture/' . $result['user_album_id'];
                    $this->m_custom->notification_insert($result['user_id'], 9, $noti_url, 'user_album', 'user_album_id', $result['user_album_id']);
                }
                else if ($refer_type == "adv")
                {
                    $result = $this->m_custom->getOneAdvertise($refer_id);
                    $noti_url = 'all/advertise/' . $result['advertise_id'];
                    if ($result['advertise_type'] == "hot")
                    {
                        $this->m_custom->notification_insert($result['merchant_id'], 7, $noti_url, 'advertise', 'advertise_id', $result['advertise_id']);
                    }
                    else if ($result['advertise_type'] == "pro")
                    {
                        $this->m_custom->notification_insert($result['merchant_id'], 8, $noti_url, 'advertise', 'advertise_id', $result['advertise_id']);
                    }
                }
                break;
        }
    }

    public function notification_insert($noti_to_id, $noti_msg_id, $noti_url, $noti_refer_table = NULL, $noti_refer_table_column = NULL, $noti_refer_table_id = NULL, $noti_remark = NULL)
    {
        if ($this->ion_auth->logged_in())
        {
            $login_id = $this->ion_auth->user()->row()->id;
            $login_type = $this->session->userdata('user_group_id');

            if ($noti_to_id != $login_id)
            {
                $the_data = array(
                    'noti_to_id' => $noti_to_id,
                    'noti_by_id' => $login_id,
                    'noti_msg_id' => $noti_msg_id,
                    'noti_url' => $noti_url,
                    'noti_refer_table' => $noti_refer_table,
                    'noti_refer_table_column' => $noti_refer_table_column,
                    'noti_refer_table_id' => $noti_refer_table_id,
                    'noti_remark' => $noti_remark,
                );
                $this->db->insert('notification', $the_data);
            }
        }
    }

    public function notification_hide($noti_id)
    {
        if ($this->ion_auth->logged_in())
        {
            $the_data = array(
                'hide_flag' => 1,
            );
            $this->db->where('noti_id', $noti_id);
            $this->db->update('notification', $the_data);
        }
    }

    public function notification_admin_read()
    {
        if ($this->ion_auth->logged_in())
        {
            $the_data = array(
                'admin_read_already' => 1,
            );
            $this->db->where('admin_read_already', 0);
            $this->db->update('notification', $the_data);
        }
    }
    
    public function notification_read($noti_to_id)
    {
        if ($this->ion_auth->logged_in())
        {
            $the_data = array(
                'noti_read_already' => 1,
            );
            $this->db->where('noti_to_id', $noti_to_id);
            $this->db->where('noti_read_already', 0);
            $this->db->update('notification', $the_data);
        }
    }

    public function notification_read_toggle($noti_id)
    {
        if ($this->ion_auth->logged_in())
        {
            $query = $this->db->get_where('notification', array('noti_id' => $noti_id))->row_array();
            if ($this->m_admin->check_is_any_admin())
            {
                if ($query['admin_read_already'] == 0)
                {
                    $the_data = array(
                        'admin_read_already' => 1,
                    );
                }
                else
                {
                    $the_data = array(
                        'admin_read_already' => 0,
                    );
                }
            }
            else
            {
                if ($query['noti_read_already'] == 0)
                {
                    $the_data = array(
                        'noti_read_already' => 1,
                    );
                }
                else
                {
                    $the_data = array(
                        'noti_read_already' => 0,
                    );
                }
            }
            $this->db->where('noti_id', $noti_id);
            $this->db->update('notification', $the_data);
        }
    }

    public function notification_count($noti_to_id, $noti_read_already = 0)
    {
        if ($this->m_admin->check_is_any_admin())
        {
            $query_list = $this->db->get_where('notification', array('hide_flag' => 0, 'admin_read_already' => $noti_read_already))->num_rows();
        }
        else
        {
            if ($noti_to_id != 0)
            {
                $this->db->where('noti_to_id', $noti_to_id);
            }
            $query_list = $this->db->get_where('notification', array('hide_flag' => 0, 'noti_read_already' => $noti_read_already))->num_rows();
        }
        return $query_list;
    }

    //Set $public_show = 1 and $certain_type = 'like' to filter public view and type
    //Get all notification about which user like which merchant image
    //$notification_list_like = $this->m_custom->notification_display(0, 1, 'like')
    //Get all notification about which user rating which merchant image
    //$notification_list_rating = $this->m_custom->notification_display(0, 1, 'rating');
    //Get all notification about which user upload image for which merchant image
    //$notification_list_upload = $this->m_custom->notification_display(0, 1, 'upload_image');
    public function notification_display($noti_to_id, $public_show = 0, $certain_type = NULL)
    {
        if ($public_show == 1)
        {
            if (!IsNullOrEmptyString($certain_type))
            {
                switch ($certain_type)
                {
                    case 'like':
                        $this->db->where_in('noti_msg_id', array(1, 2, 3));
                        break;
                    case 'rating':
                        $this->db->where_in('noti_msg_id', array(4, 5, 6));
                        break;
                    case 'comment':
                        $this->db->where_in('noti_msg_id', array(7, 8, 9));
                        break;
                    case 'upload_image':
                        $this->db->where_in('noti_msg_id', array(10));
                        break;
                }
            }
        }
        else
        {
            $this->db->where('noti_to_id', $noti_to_id);
        }
        
        $this->db->order_by("noti_id", "desc");
        $query_list = $this->db->get_where('notification', array('hide_flag' => 0), 100)->result_array();
        $notification_list = array();
        foreach ($query_list as $notification)
        {
            $title = '';
            $noti_to_id = $notification['noti_to_id'];
            $msg_type = $notification['noti_msg_id'];
            $table_name = $notification['noti_refer_table'];
            $table_column = $notification['noti_refer_table_column'];
            $table_id = $notification['noti_refer_table_id'];
            
            if ($table_name == 'users')
            {
                $record = $this->m_custom->get_one_table_record($table_name, $table_column, $table_id, 1);
            }
            else
            {
                $record = $this->m_custom->get_one_table_record($table_name, $table_column, $table_id, 1, 1);
                if(empty($record)){
                    continue;
                }
            }

            switch ($table_name)
            {
                case 'advertise':
                case 'merchant_user_album':
                case 'user_album':
                    $title = "<b>" . $record['title'] . "</b>";
                    break;
            }

            //If it is rating, then add the rating remark
            if (in_array($msg_type, array(4, 5, 6)))
            {
                $title = $title . " as " . $notification['noti_remark'] . " star";
            }

            $noti_message = $this->m_custom->display_notification_message($msg_type, $title, $public_show, $noti_to_id);

            $notification_list[] = array(
                'noti_id' => $notification['noti_id'],
                'noti_by_id' => $notification['noti_by_id'],
                'noti_user_url' => $this->m_custom->generate_user_link($notification['noti_by_id'], 0, 1),
                'noti_user_image' => $this->m_custom->generate_user_link($notification['noti_by_id'], 1, 1, 1),
                'noti_message' => $noti_message,
                'noti_url' => $notification['noti_url'],
                'noti_read_already' => $notification['noti_read_already'],
                'admin_read_already' => $notification['admin_read_already'],
                'noti_time' => displayDate($notification['noti_time'], 1),
                'noti_image_url' => $this->m_custom->get_image_url($notification['noti_refer_table'], $notification['noti_refer_table_id']),
            );
        }
        return $notification_list;
    }

    public function trans_extra_insert($user_id = NULL, $trans_conf_id = NULL, $amount_change = NULL, $admin_id = NULL, $trans_bank = NULL, $trans_date = NULL, $trans_no = NULL, $trans_remark = NULL)
    {
        if ($user_id == NULL || $trans_conf_id == NULL || $amount_change == NULL || $admin_id == NULL)
        {
            return FALSE;
        }
        else
        {
            $the_data = array(
                'user_id' => $user_id,
                'trans_conf_id' => $trans_conf_id,
                'amount_change' => check_is_decimal($amount_change),
                'admin_id' => $admin_id,
                'trans_bank' => $trans_bank,
                'trans_date' => validateDate($trans_date),
                'trans_no' => $trans_no,
                'trans_remark' => $trans_remark,
            );
            $this->db->insert('transaction_extra', $the_data);
            $new_id = $this->db->insert_id();
            return $new_id;
        }
    }

    public function trans_extra_update($extra_id = NULL, $amount_change = NULL, $trans_bank = NULL, $trans_date = NULL, $trans_no = NULL, $trans_remark = NULL)
    {
        if ($extra_id != NULL)
        {
            $the_data = array(
                'amount_change' => check_is_decimal($amount_change),
                'trans_bank' => $trans_bank,
                'trans_date' => validateDate($trans_date),
                'trans_no' => $trans_no,
                'trans_remark' => $trans_remark,
            );
            $this->db->where('extra_id', $extra_id);
            $this->db->update('transaction_extra', $the_data);
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function merchant_like_count($merchant_id, $refer_type, $include_mua = 0)
    {
        $advertise_list = $this->m_custom->getAdvertise('all', NULL, $merchant_id, 1);
        $advertise_list_id = array();

        foreach ($advertise_list as $row)
        {
            $advertise_list_id[] = $row['advertise_id'];
        }

        $counter = 0;

        if (!empty($advertise_list_id))
        {
            $this->db->where_in('act_refer_id', $advertise_list_id);
            $query = $this->db->get_where('activity_history', array('act_type' => 'like', 'act_refer_type' => $refer_type));
            $counter = $query->num_rows();
        }

        if ($include_mua == 1)
        {
            $counter += $this->m_custom->merchant_mua_activity_count($merchant_id, 'like');
            ;
        }

        return $counter;
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function merchant_comment_count($merchant_id, $refer_type, $include_mua = 0)
    {
        $advertise_list = $this->m_custom->getAdvertise('all', NULL, $merchant_id, 1);
        $advertise_list_id = array();

        foreach ($advertise_list as $row)
        {
            $advertise_list_id[] = $row['advertise_id'];
        }

        $counter = 0;

        if (!empty($advertise_list_id))
        {
            $this->db->where_in('act_refer_id', $advertise_list_id);
            $query = $this->db->get_where('activity_history', array('act_type' => 'comment', 'act_refer_type' => $refer_type));
            $counter = $query->num_rows();
        }

        if ($include_mua == 1)
        {
            $counter += $this->m_custom->merchant_mua_activity_count($merchant_id, 'comment');
            ;
        }

        return $counter;
    }

    public function merchant_mua_activity_count($merchant_id, $act_type)
    {
        $mua_list = $this->m_custom->getAlbumUserMerchant(NULL, $merchant_id);
        $mua_list_id = array();

        foreach ($mua_list as $row)
        {
            $mua_list_id[] = $row['merchant_user_album_id'];
        }

        $counter = 0;

        if (!empty($mua_list_id))
        {
            $this->db->where_in('act_refer_id', $mua_list_id);
            $query = $this->db->get_where('activity_history', array('act_type' => $act_type, 'act_refer_type' => 'mua'));
            $counter = $query->num_rows();
        }
        return $counter;
    }

    public function merchant_picture_count($merchant_id, $include_adv = 0)
    {
        $query = $this->db->get_where('merchant_user_album', array('merchant_id' => $merchant_id, 'post_type' => 'mer', 'hide_flag' => 0));
        $counter = $query->num_rows();

        if ($include_adv == 1)
        {
            $this->db->where('start_time is not null AND end_time is not null');
            $query = $this->db->get_where('advertise', array('merchant_id' => $merchant_id, 'hide_flag' => 0));
            $counter += $query->num_rows();
        }

        return $counter;
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function merchant_rating_average($merchant_id, $refer_type, $want_count = 0)
    {
        $advertise_list = $this->m_custom->getAdvertise('all', NULL, $merchant_id, 1);
        $total_rate = 0;
        $counter = 0;
        $user_count = 0;
        foreach ($advertise_list as $row)
        {
            $temp_rate = $this->m_custom->activity_rating_average($row['advertise_id'], $refer_type);
            $user_count += $this->m_custom->activity_rating_count($row['advertise_id'], $refer_type);
            $total_rate += $temp_rate;
            if ($temp_rate != 0)
            {
                $counter++;
            }
        }
        if ($counter != 0)
        {
            $average_rate = $total_rate / $counter;
            if ($want_count == 1)
            {
                return $user_count;
            }
            else
            {
                return $average_rate;
            }
        }
        else
        {
            return 0;
        }
    }

    public function generate_supervisor_link($supervisor_id = NULL, $with_icon = 0, $want_supervisor = 0, $icon_only = 0)
    {
        $user_name = $this->m_custom->display_users($supervisor_id, $with_icon, $want_supervisor, $icon_only);
        $query = $this->db->get_where('users', array('id' => $supervisor_id));
        $user_row = $query->row_array();
        $merchant = $this->m_merchant->getMerchant($user_row['su_merchant_id']);
        return "<a target='_blank' href='" . base_url() . "all/merchant_dashboard/" . $merchant['slug'] . "' style='color:black'>" . $user_name . "</a>";
    }

    public function generate_merchant_link($merchant_id = NULL, $with_icon = 0, $want_supervisor = 0, $icon_only = 0)
    {
        //If is post by admin
        if ($merchant_id == $this->config->item('keppo_admin_id'))
        {
            return "<a style='color:black'>" . $this->m_custom->web_setting_get('keppo_company_name', 'set_desc') . "</a>";
        }
        else
        {
            $user_name = $this->m_custom->display_users($merchant_id, $with_icon, $want_supervisor, $icon_only);
            $merchant = $this->m_merchant->getMerchant($merchant_id);
            return "<a target='_blank' href='" . base_url() . "all/merchant_dashboard/" . $merchant['slug'] . "' style='color:black'>" . $user_name . "</a>";
        }
    }

    public function generate_user_link($user_id = NULL, $with_icon = 0, $want_supervisor = 0, $icon_only = 0)
    {
        $user_name = $this->m_custom->display_users($user_id, $with_icon, $want_supervisor, $icon_only);
        $query = $this->db->get_where('users', array('id' => $user_id));
        if ($query->num_rows() !== 1)
        {
            return $user_name;
        }

        $user_row = $query->row_array();
        if ($user_row['main_group_id'] == $this->config->item('group_id_merchant'))
        {
            return $this->m_custom->generate_merchant_link($user_id, $with_icon, $want_supervisor, $icon_only);
        }
        else if ($user_row['main_group_id'] == $this->config->item('group_id_supervisor'))
        {
            return $this->m_custom->generate_supervisor_link($user_id, $with_icon, $want_supervisor, $icon_only);
        }
        else if ($user_row['main_group_id'] == $this->config->item('group_id_user'))
        {
            return "<a target='_blank' href='" . base_url() . "all/user_dashboard/" . $user_id . "' style='color:black'>" . $user_name . "</a>";
        }
    }

    public function generate_image_link($image, $act_refer_type)
    {
        $refer_link = '';
        switch ($act_refer_type)
        {
            case 'adm':
                $refer_link = base_url($this->config->item('album_admin') . $image);
                break;
            case 'hot':
                $refer_link = base_url($this->config->item('album_merchant') . $image);
                break;
            case 'pro':
                $refer_link = base_url($this->config->item('album_merchant') . $image);
                break;
            case 'adv':
                $refer_link = base_url($this->config->item('album_merchant') . $image); //Because most advertise image is put in album_merchant, but not necessary 
                break;
            case 'mua':
                $refer_link = base_url($this->config->item('album_user_merchant') . $image);
                break;
            case 'usa':
                $refer_link = base_url($this->config->item('album_user') . $image);
                break;
        }
        return $refer_link;
    }

    public function generate_act_refer_type_link($id, $act_refer_type)
    {
        $refer_link = '';
        switch ($act_refer_type)
        {
            case 'adv':
                $refer_link = $this->m_custom->generate_advertise_link($id);
                break;
            case 'mua':
                $refer_link = $this->m_custom->generate_mua_link($id);
                break;
            case 'usa':
                $refer_link = $this->m_custom->generate_usa_link($id);
                break;
        }
        return $refer_link;
    }

    public function generate_advertise_link($advertise_id = NULL)
    {
        $adv_row = $this->m_custom->get_one_table_record('advertise', 'advertise_id', $advertise_id, 1);
        return "<a target='_blank' href='" . base_url() . "all/advertise/" . $advertise_id . "'>" . $adv_row['title'] . "</a>";
    }

    public function generate_mua_link($mua_id = NULL)
    {
        $adv_row = $this->m_custom->get_one_table_record('merchant_user_album', 'merchant_user_album_id', $mua_id, 1);
        return "<a target='_blank' href='" . base_url() . "all/merchant-user-picture/" . $mua_id . "'>" . $adv_row['title'] . "</a>";
    }

    public function generate_usa_link($usa_id = NULL)
    {
        $adv_row = $this->m_custom->get_one_table_record('user_album', 'user_album_id', $usa_id, 1);
        return "<a target='_blank' href='" . base_url() . "all/user-picture/" . $usa_id . "'>" . $adv_row['title'] . "</a>";
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function generate_like_link($refer_id, $refer_type)
    {
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            return "<span class='like-it' ><button onclick='click_like(" . $refer_id . ");'> Like </button> : " . $this->generate_like_list_link($refer_id, $refer_type) . " </span>";
        }
        else
        {
            return "Like : " . $this->generate_like_list_link($refer_id, $refer_type) . " ";
        }
    }

    public function generate_like_list_link($refer_id, $refer_type)
    {
        return "<a target='_blank' href='" . base_url() . "all/like_list/" . $refer_id . "/" . $refer_type . "'>" . $this->activity_like_count($refer_id, $refer_type) . "</a>";
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    public function generate_comment_link($refer_id, $refer_type)
    {
        return "Comment : " . $this->activity_comment_count($refer_id, $refer_type) . " ";
    }

    public function check_is_new_user($user_id, $month_id = NULL, $year = NULL)
    {
        $the_user = $this->getUser($user_id);
        $created_on = date($this->config->item('keppo_format_date_time_db'), $the_user['created_on']);
        $search_date = date_for_db_search($month_id, $year);
        if (strpos($created_on, $search_date) !== false)
        {
            //return $created_on . " is new";
            return TRUE;
        }
        else
        {
            //return $created_on . " is old";
            return FALSE;
        }
    }

    public function check_can_show_advertise($row)
    {
        $valid_row = 0;

        if ($this->m_merchant->have_money($row['merchant_id']))
        {
            $valid_row = 1;
        }
        else
        {
            $valid_row = 0;
        }

        if ($row['frozen_flag'] == 1)
        {
            if ($this->m_custom->check_is_superuser($row['merchant_id']))
            {
                $valid_row = 1;
            }
            else
            {
                $valid_row = 0;
            }
        }
        return $valid_row;
    }

    public function home_search_merchant($search_value = NULL, $state_id = 0)
    {
        if ($state_id != 0)
        {
            $have_branch_at_this_state = $this->m_custom->get_list_of_allow_id('merchant_branch', 'state_id', $state_id, 'merchant_id');
        }

        if (!IsNullOrEmptyString($search_value))
        {
            $search_word = $this->db->escape('%' . $search_value . '%');
            $this->db->where("(`company` LIKE $search_word OR `slug` LIKE $search_word OR `address` LIKE $search_word)");
        }

        if ($state_id != 0)
        {
            if (!empty($have_branch_at_this_state))
            {
                $this->db->where_in('id', $have_branch_at_this_state);
            }
            $this->db->or_where('me_state_id', $state_id);
        }

        $this->db->order_by("company", "asc");
        $query = $this->db->get_where('users', array('main_group_id' => $this->config->item('group_id_merchant'), 'hide_flag' => 0));
        $result = $query->result_array();
        $return = array();
        foreach ($result as $row)
        {
            $return[] = $this->m_custom->getMerchantInfo($row['id']);
        }
        return $return;
    }

    public function home_search_hotdeal($search_value = NULL, $state_id = 0)
    {
        if (!IsNullOrEmptyString($search_value))
        {
            $merchant_list_id = $this->m_merchant->searchMerchant($search_value, 1);
            $search_word = $this->db->escape('%' . $search_value . '%');
            if (!empty($merchant_list_id))
            {
                $merchant_string = implode(',', $merchant_list_id);
                $this->db->where("((`title` LIKE $search_word OR `description` LIKE $search_word) OR `merchant_id` IN ($merchant_string))");
            }
            else
            {
                $this->db->where("(`title` LIKE $search_word OR `description` LIKE $search_word)");
            }
        }

        $this->db->where('end_time >=', get_part_of_date('all'));
        $this->db->where('start_time is not null AND end_time is not null');
        $this->db->order_by("advertise_id", "desc");
        $query = $this->db->get_where('advertise', array('advertise_type' => 'hot', 'hide_flag' => 0));
        $result = $query->result_array();
        $return = array();
        foreach ($result as $row)
        {
            $merchant_info = $this->m_custom->getMerchantInfo($row['merchant_id']);

            $valid_row = 0;
            if ($state_id != 0)
            {
                if ($merchant_info['me_state_id'] == $state_id)
                {
                    $valid_row = 1;
                }
            }
            else
            {
                $valid_row = 1;
            }
            
            $valid_row = $this->m_custom->check_can_show_advertise($row);
            
            if ($valid_row == 1)
            {
                $return[] = $row;
            }
        }
        return $return;
    }

    public function home_search_promotion($search_value = NULL, $state_id = 0)
    {
        if (!IsNullOrEmptyString($search_value))
        {
            $merchant_list_id = $this->m_merchant->searchMerchant($search_value, 1);
            $search_word = $this->db->escape('%' . $search_value . '%');
            if (!empty($merchant_list_id))
            {
                $merchant_string = implode(',', $merchant_list_id);
                $this->db->where("((`title` LIKE $search_word OR `description` LIKE $search_word) OR `merchant_id` IN ($merchant_string))");
            }
            else
            {
                $this->db->where("(`title` LIKE $search_word OR `description` LIKE $search_word)");
            }
        }

        $this->db->where('end_time >=', get_part_of_date('all'));
        $this->db->where('start_time is not null AND end_time is not null');
        $this->db->order_by("advertise_id", "desc");
        $query = $this->db->get_where('advertise', array('advertise_type' => 'pro', 'hide_flag' => 0));
        $result = $query->result_array();
        $return = array();
        foreach ($result as $row)
        {
            $advertise_id = $row['advertise_id'];
            if ($state_id != 0)
            {
                $branch_list = $this->m_custom->many_get_childlist('candie_branch', $advertise_id, 1);
                foreach ($branch_list as $branch)
                {
                    $branch_query = $this->db->get_where('merchant_branch', array('branch_id' => $branch['many_child_id']))->row_array();
                    if ($state_id == $branch_query['state_id'])
                    {
                        $return[] = $row;
                    }
                }
                $return = array_unique($return, SORT_REGULAR);
            }
            else
            {
                $return[] = $row;
            }
        }
        return $return;
    }

    public function post_title($refer_id, $refer_type)
    {
        $return_title = '';
        switch ($refer_type)
        {
            case 'adv':
                $result = $this->m_custom->getOneAdvertise($refer_id);
                $return_title = "<a target='_blank' href='" . base_url() . "all/advertise/" . $result['advertise_id'] . "'>" . $result['title'] . "</a>";
                break;

            case 'mua':
                $result = $this->m_custom->getOneMUA($refer_id);
                $return_title = "<a target='_blank' href='" . base_url() . "all/merchant_user_picture/" . $result['merchant_user_album_id'] . "'>" . $result['title'] . "</a>";
                break;

            case 'usa':
                $result = $this->m_custom->getOneUserPicture($refer_id);
                $return_title = "<a target='_blank' href='" . base_url() . "all/user_picture/" . $result['user_album_id'] . "'>" . $result['title'] . "</a>";
                break;
        }
        return $return_title;
    }

    public function compare_before_update($the_table, $the_data, $id_column, $id_value)
    {
        $record = $this->get_one_table_record($the_table, $id_column, $id_value, 1);
        $result = array_diff_assoc($the_data, $record);
        if (empty($result))
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function simple_update($the_table, $the_data, $id_column, $id_value)
    {
        if ($this->m_custom->compare_before_update($the_table, $the_data, $id_column, $id_value))
        {
            $this->db->where($id_column, $id_value);
            if ($this->db->update($the_table, $the_data))
            {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function remove_image_temp()
    {
        //$temp_folder = $this->config->item('folder_image_temp_phy');  //For security purpose i don't use the config temp folder name, but hard code again in code, for prevent hacking
        $temp_folder = realpath(APPPATH . '..\folder_upload\temp_image');
        $files = glob($temp_folder . '\*');
        //var_dump($files);
        $this->load->helper('file');
        foreach ($files as $file)
        {
            if (is_file($file))
            {
                unlink($file); // delete file
            }
        }
    }

    public function display_row_monitor($want_count = 0)
    {
        if ($this->ion_auth->logged_in())
        {
            $login_id = $this->ion_auth->user()->row()->id;
            $login_type = $this->session->userdata('user_group_id');

            $condition = "(mon_is_public = true or (mon_is_public = false and mon_for_id = " . $login_id . "))";
            $this->db->where($condition);
            $mon_query = $this->db->get_where('monitoring', array('mon_for_type' => $login_type, 'mon_status' => 0));

            if ($want_count == 1)
            {
                return $mon_query->num_rows();
            }

            $mon_result = $mon_query->result_array();
            $result = array();
            foreach ($mon_result as $row)
            {
                $mon_hide_type = $row['mon_hide_type'];
                $hide_item_desc = "";
                $hide_item_type = "";
                $post_image = "";
                switch ($mon_hide_type)
                {
                    case 'com':
                        $refer_row = $this->m_custom->activity_get_one_row($row['mon_table_id']);
                        $hide_item_type = "Comment";
                        $hide_item_desc = '<table style="border:none">' .
                                '<tr><td>Content</td><td>:</td><td>' . nl2br($refer_row['comment']) . '</td></tr>' .
                                '<tr><td>Comment At</td><td>:</td><td>' . $this->m_custom->generate_act_refer_type_link($refer_row['act_refer_id'], $refer_row['act_refer_type']) . '</td></tr>' .
                                '<tr><td>Comment By</td><td>:</td><td>' . $this->m_custom->generate_user_link($refer_row['act_by_id']) . ' (' . $this->m_custom->display_users_groups($refer_row['act_by_type'], 'description') . ')' . '</td></tr>' .
                                '<tr><td>Time</td><td>:</td><td>' . displayDate($refer_row['act_time'], 1) . '</td></tr>' .
                                '</table>';

                        break;
                    case 'mua':
                        $refer_row = $this->m_custom->get_one_table_record('merchant_user_album', 'merchant_user_album_id', $row['mon_table_id'], 1);
                        $hide_item_type = "Picture Upload For Merchant";
                        $hide_item_desc = '<table style="border:none">' .
                                '<tr><td>Title</td><td>:</td><td>' . $refer_row['title'] . '</td></tr>' .
                                '<tr><td>Description</td><td>:</td><td>' . nl2br($refer_row['description']) . '</td></tr>' .
                                '<tr><td>Upload By</td><td>:</td><td>' . $this->m_custom->generate_user_link($refer_row['user_id']) . '</td></tr>' .
                                '<tr><td>Post Date</td><td>:</td><td>' . displayDate($refer_row['create_date'], 1) . '</td></tr>' .
                                '<tr><td>Removed Reason</td><td>:</td><td>' . $refer_row['hide_remark'] . '</td></tr>' .
                                '</table>';
                        $post_image = $this->m_custom->generate_image_link($refer_row['image'], $mon_hide_type);
                        break;
                    case 'adv':
                        $refer_row = $this->m_custom->getOneAdvertise($row['mon_table_id'], 1, 1);
                        $hide_item_type = "Merchant Picture";
                        $hide_item_desc = '<table style="border:none">' .
                                '<tr><td>Title</td><td>:</td><td>' . $refer_row['title'] . '</td></tr>' .
                                '<tr><td>Description</td><td>:</td><td>' . nl2br($refer_row['description']) . '</td></tr>' .
                                '<tr><td>Post Date</td><td>:</td><td>' . displayDate($refer_row['create_date'], 1) . '</td></tr>' .
                                '</table>';
                        $post_image = $this->m_custom->generate_image_link($refer_row['image'], $mon_hide_type);
                        break;
                }

                if ($refer_row != FALSE)
                {
                    $extra_info = array(
                        'hide_time_text' => displayDate($row['hide_time'], 1),
                        'hide_item_type' => $hide_item_type,
                        'hide_item_desc' => $hide_item_desc,
                        'post_image' => $post_image,
                        'hide_by_text' => $this->m_custom->display_users($row['hide_by'], 0, 1),
                        'hide_by_type_text' => $this->m_custom->display_users_groups($row['hide_by_type'], 'description'),
                    );
                    $result[] = $row + $extra_info + $refer_row;
                }
            }
            return $result;
        }
    }

    public function insert_row_monitor_process($mon_hide_type, $mon_table_id, $mon_table, $login_type)
    {
        $group_id_merchant = $this->config->item('group_id_merchant');
        $group_id_supervisor = $this->config->item('group_id_supervisor');
        $group_id_admin = $this->config->item('group_id_admin');
        $group_id_worker = $this->config->item('group_id_worker');

        if ($login_type == $group_id_supervisor)
        {
            $merchant_id = $this->ion_auth->user()->row()->su_merchant_id;
            $this->m_custom->insert_row_monitor($mon_hide_type, false, $merchant_id, $group_id_merchant, $mon_table_id, $mon_table);        //for this supervisor merchant monitor     
            $this->m_custom->insert_row_monitor($mon_hide_type, true, 0, $group_id_admin, $mon_table_id, $mon_table);            //for all admin monitor
            $this->m_custom->insert_row_monitor($mon_hide_type, true, 0, $group_id_worker, $mon_table_id, $mon_table);           //for all worker monitor
        }
        else if ($login_type == $group_id_merchant)
        {
            $this->m_custom->insert_row_monitor($mon_hide_type, true, 0, $group_id_admin, $mon_table_id, $mon_table);            //for all admin monitor
            $this->m_custom->insert_row_monitor($mon_hide_type, true, 0, $group_id_worker, $mon_table_id, $mon_table);           //for all worker monitor
        }
    }

    public function insert_row_monitor($mon_hide_type, $mon_is_public, $mon_for_id, $mon_for_type, $mon_table_id, $mon_table)
    {
        if ($this->ion_auth->logged_in())
        {
            $login_id = $this->ion_auth->user()->row()->id;
            $login_type = $this->session->userdata('user_group_id');
            $the_data = array(
                'mon_hide_type' => $mon_hide_type,
                'mon_is_public' => $mon_is_public,
                'mon_for_id' => $mon_for_id,
                'mon_for_type' => $mon_for_type,
                'mon_table_id' => $mon_table_id,
                'mon_table' => $mon_table,
                'hide_by' => $login_id,
                'hide_by_type' => $login_type,
            );
            $this->db->insert('monitoring', $the_data);
        }
    }

    public function approve_row_monitor($mon_id)
    {
        $main_row = $this->db->get_where('monitoring', array('mon_id' => $mon_id))->row_array();
        $mon_table_id = $main_row['mon_table_id'];
        $mon_table = $main_row['mon_table'];
        $mon_is_public = $main_row['mon_is_public'];
        $all_row = $this->db->get_where('monitoring', array('mon_table_id' => $mon_table_id, 'mon_table' => $mon_table, 'mon_is_public' => $mon_is_public))->result_array();
        foreach ($all_row as $row)
        {
            $this->m_custom->update_row_monitor($row['mon_id'], 1);
        }
    }

    public function recover_row_monitor($mon_id)
    {
        $main_row = $this->db->get_where('monitoring', array('mon_id' => $mon_id))->row_array();
        $mon_table_id = $main_row['mon_table_id'];
        $mon_table = $main_row['mon_table'];
        $this->m_custom->update_hide_flag(0, $mon_table, $mon_table_id);
        $all_row = $this->db->get_where('monitoring', array('mon_table_id' => $mon_table_id, 'mon_table' => $mon_table))->result_array();
        foreach ($all_row as $row)
        {
            $this->m_custom->update_row_monitor($row['mon_id'], 2);
        }
    }

    public function update_row_monitor($mon_id, $mon_status, $mon_remark = NULL)
    {
        if ($this->ion_auth->logged_in())
        {
            $login_id = $this->ion_auth->user()->row()->id;
            $login_type = $this->session->userdata('user_group_id');
            $query = $this->db->get_where('monitoring', array('mon_id' => $mon_id), 1);
            if ($query->num_rows() == 1)
            {
                $the_data = array(
                    'mon_status' => $mon_status,
                    'mon_remark' => $mon_remark,
                    'review_by' => $login_id,
                    'review_by_type' => $login_type,
                );
                $this->db->where('mon_id', $mon_id);
                $this->db->update('monitoring', $the_data);
            }
        }
    }

    public function promo_code_insert_user($code_user_id)
    {
        $query = $this->db->get_where('promo_code', array('code_type' => 'user', 'code_user_id' => $code_user_id), 1);
        if ($query->num_rows() == 0)
        {
            $user_info = $this->m_custom->getUserInfo($code_user_id);
            $code_candie = $this->m_custom->web_setting_get('register_promo_code_get_candie');
            $code_money = $this->m_custom->web_setting_get('friend_success_register_get_money', 'set_decimal');
            $name = substr(generate_code($user_info['first_name'] . $user_info['last_name']), 0, 5);
            $postfix = str_pad($code_user_id, 4, '0', STR_PAD_LEFT);
            if ($user_info['us_gender_id'] = $this->config->item('gender_id_male'))
            {
                $prefix = '2';
            }
            else
            {
                $prefix = '3';
            }
            $code_no = $prefix . $name . $postfix;
            $new_id = $this->m_custom->promo_code_insert($code_no, 'user', $code_user_id, $code_candie, $code_money);
            if ($new_id)
            {
                return $new_id;
            }
        }
        return FALSE;
    }

    public function promo_code_insert_merchant($code_user_id)
    {
        $query = $this->db->get_where('promo_code', array('code_type' => 'merchant', 'code_user_id' => $code_user_id), 1);
        if ($query->num_rows() == 0)
        {
            $user_info = $this->m_custom->getMerchantInfo($code_user_id);
            $code_candie = $this->m_custom->web_setting_get('merchant_promo_code_get_candie');
            $name = substr(generate_code($user_info['slug']), 0, 5);
            $postfix = str_pad($code_user_id, 4, '0', STR_PAD_LEFT);
            $code_no = '5' . $name . $postfix;
            $new_id = $this->m_custom->promo_code_insert($code_no, 'merchant', $code_user_id, $code_candie);
            if ($new_id)
            {
                return $new_id;
            }
        }
        return FALSE;
    }
    
    public function promo_code_insert_event($code_no, $code_candie = NULL, $code_money = NULL, $code_event_name = NULL)
    {
        $login_id = $this->ion_auth->user()->row()->id;
        $query = $this->db->get_where('promo_code', array('code_type' => 'event', 'code_no' => $code_no));
        if ($query->num_rows() == 0)
        {
            $new_id = $this->m_custom->promo_code_insert($code_no, 'event', $login_id, $code_candie, $code_money, 1, 1, $code_event_name);
            if ($new_id)
            {
                return $new_id;
            }
        }

        return FALSE;
    }

    public function promo_code_insert($code_no, $code_type, $code_user_id, $code_candie = NULL, $code_money = NULL, $code_candie_overwrite = 0, $code_money_overwrite = 0, $code_event_name = NULL, $code_remark = NULL)
    {
        $login_id = NULL;
        if ($this->ion_auth->logged_in())
        {
            $login_id = $this->ion_auth->user()->row()->id;
        }

        $the_data = array(
            'code_no' => $code_no,
            'code_type' => $code_type,
            'code_user_id' => $code_user_id,
            'code_candie' => $code_candie,
            'code_money' => $code_money,
            'code_candie_overwrite' => $code_candie_overwrite,
            'code_money_overwrite' => $code_money_overwrite,
            'code_event_name' => $code_event_name,
            'code_remark' => $code_remark,
            'last_modify_by' => $login_id,
        );
        $new_id = $this->m_custom->get_id_after_insert('promo_code', $the_data);
        if ($new_id)
        {
            return $new_id;
        }
        else
        {
            return FALSE;
        }
    }

    public function promo_code_get($code_type, $code_user_id, $code_only = 0)
    {
        $query = $this->db->get_where('promo_code', array('code_type' => $code_type, 'code_user_id' => $code_user_id));
        if ($query->num_rows() == 1)
        {
            $result = $query->row_array();
            if ($code_only == 1)
            {
                return $result['code_no'];
            }
            else
            {
                return $result;
            }
        }

        return FALSE;
    }

    public function promo_code_history_insert($promo_code = NULL)
    {
        $message_info = '';
        $code_no = strtolower(trim($promo_code));
        $query = $this->db->get_where('promo_code', array('code_no' => $code_no, 'hide_flag' => 0));
        if ($query->num_rows() == 1 && $this->ion_auth->logged_in())
        {
            $result = $query->row_array();
            $login_id = $this->ion_auth->user()->row()->id;
            $code_id = $result['code_id'];
            $code_type = $result['code_type'];
            $code_user_id = $result['code_user_id'];
            $code_candie = $result['code_candie'];
            $code_money = $result['code_money'];
            $code_event_name = $result['code_event_name'];
            $code_candie_overwrite = $result['code_candie_overwrite'];
            $code_money_overwrite = $result['code_money_overwrite'];
            $friend_success_register_get_money = $this->m_custom->web_setting_get('friend_success_register_get_money', 'set_decimal');
            $register_promo_code_get_candie = $this->m_custom->web_setting_get('register_promo_code_get_candie');
            $merchant_promo_code_get_candie = $this->m_custom->web_setting_get('merchant_promo_code_get_candie');

            switch ($code_type)
            {
                case 'merchant':
                    if ($code_candie_overwrite == 0)
                    {
                        $code_candie = $merchant_promo_code_get_candie;
                    }
                    $new_id = $this->m_custom->promo_code_trans_extra_insert($login_id, 33, $code_candie, $code_id);
                    if ($new_id)
                    {
                        $this->m_user->candie_history_insert(33, $new_id, 'transaction_extra', 0, $code_candie);
                        $message_info = 'Success get ' . $code_candie . ' candie from merchant ' . $this->m_custom->display_users($code_user_id) . ' promo code';
                    }
                    else
                    {
                        $message_info = 'Cannot get promo code candie again from merchant ' . $this->m_custom->display_users($code_user_id) . ' , only can get 1 time from same merchant';
                    }
                    break;
                case 'user':
                    $check_get_already = $this->db->get_where('transaction_extra', array('trans_conf_id' => 32, 'user_id' => $login_id));
                    if ($check_get_already->num_rows() == 0)
                    {
                        if ($code_candie_overwrite == 0)
                        {
                            $code_candie = $register_promo_code_get_candie;
                        }
                        if ($code_money_overwrite == 0)
                        {
                            $code_money = $friend_success_register_get_money;
                        }
                        $new_id = $this->m_custom->promo_code_trans_extra_insert($login_id, 32, $code_candie, $code_id);
                        if ($new_id)
                        {
                            $this->m_user->candie_history_insert(32, $new_id, 'transaction_extra', 0, $code_candie);
                            $message_info = 'Success get ' . $code_candie . ' candie from user ' . $this->m_custom->display_users($code_user_id) . ' register promo code';

                            //Insert cash back for this promo code user because his friend success key in refer promo code
                            $new_id2 = $this->m_custom->promo_code_trans_extra_insert($code_user_id, 25, $code_money, $code_id);
                            $this->m_user->user_trans_history_insert($code_user_id, 25, $new_id2, 'transaction_extra', 0, $code_money);
                        }
                        else
                        {
                            $message_info = 'Cannot get promo code candie again from user ' . $this->m_custom->display_users($code_user_id) . ' , only can get 1 time from same user';
                        }
                    }
                    else
                    {
                        $get_already = $check_get_already->row_array();
                        $get_already_row = $this->m_custom->get_one_field_by_key('promo_code', 'code_id', $get_already['refer_id'], 'code_user_id');
                        $message_info = 'You already get register promo code candie from user ' . $this->m_custom->display_users($get_already_row) . ' before, register promo code candie only can get 1 time';
                    }
                    break;
                case 'event':
                    $new_id = $this->m_custom->promo_code_trans_extra_insert($login_id, 34, $code_candie, $code_id);
                    if ($new_id)
                    {
                        $this->m_user->candie_history_insert(34, $new_id, 'transaction_extra', 0, $code_candie);
                        $message_info = 'Success get ' . $code_candie . ' candie from event ' . $code_event_name . ' special promo code';
                    }
                    else
                    {
                        $message_info = 'Cannot get special promo code candie again from event ' . $code_event_name . ' , only can get 1 time from same event';
                    }
                    break;
            }
        }
        else
        {
            $message_info = 'Promo code is not valid or no longer active.';
        }

        return $message_info;
    }

    public function promo_code_trans_extra_insert($user_id = NULL, $trans_conf_id = NULL, $amount_change = NULL, $refer_id = NULL, $allow_duplicate = 0, $trans_remark = NULL)
    {
        if ($user_id == NULL || $trans_conf_id == NULL || $amount_change == NULL || $refer_id == NULL)
        {
            return FALSE;
        }
        else
        {
            $search_data = array(
                'user_id' => $user_id,
                'trans_conf_id' => $trans_conf_id,
                'refer_id' => $refer_id,
            );
            $query = $this->db->get_where('transaction_extra', $search_data);
            if (($query->num_rows() == 0 && $allow_duplicate == 0) || $allow_duplicate != 0)
            {
                $the_data = array(
                    'user_id' => $user_id,
                    'trans_conf_id' => $trans_conf_id,
                    'amount_change' => check_is_decimal($amount_change),
                    'refer_id' => $refer_id,
                    'trans_remark' => $trans_remark,
                );
                $this->db->insert('transaction_extra', $the_data);
                $new_id = $this->db->insert_id();
                return $new_id;
            }
            else
            {
                return FALSE;
            }
        }
    }

    //For register the promo code that user insert during sign up, then this field will be make null
    public function promo_code_temp_register($user_id)
    {
        $us_promo_code_temp = $this->m_custom->get_one_field_by_key('users', 'id', $user_id, 'us_promo_code_temp');
        if ($us_promo_code_temp != NULL)
        {
            $this->m_custom->promo_code_history_insert($us_promo_code_temp);
            $data = array(
                'us_promo_code_temp' => NULL,
            );
            $this->m_custom->simple_update('users', $data, 'id', $user_id);
        }
    }

    public function table_id_column($table){
        $fields = $this->db->list_fields($table);
        foreach ($fields as $field)
        {
            $field_name[] = $field;
        }
        return $field_name[0];
    }
    
    public function update_hide_flag($hide_flag, $table, $table_id)
    {
        $fields = $this->db->list_fields($table);
        foreach ($fields as $field)
        {
            $field_name[] = $field;
        }
        $the_data = array(
            'hide_flag' => $hide_flag,
        );
        $this->db->where($field_name[0], $table_id);
        $this->db->update($table, $the_data);
    }

    public function update_frozen_flag($frozen_flag, $table, $table_id)
    {
        $fields = $this->db->list_fields($table);
        foreach ($fields as $field)
        {
            $field_name[] = $field;
        }
        $the_data = array(
            'frozen_flag' => $frozen_flag,
        );
        $this->db->where($field_name[0], $table_id);
        $this->db->update($table, $the_data);
    }

    public function insert_row_log($the_table, $new_id, $do_by = NULL, $do_by_type = NULL)
    {
        $the_data = array(
            'table_type' => $the_table,
            'table_row_id' => $new_id,
            'create_time' => get_part_of_date('all'),
            'create_by' => $do_by,
            'create_by_type' => $do_by_type,
        );
        $this->db->insert('table_row_activity', $the_data);
    }

    public function update_row_log($the_table, $the_id, $do_by = NULL, $do_by_type = NULL)
    {

        $query = $this->db->get_where('table_row_activity', array('table_type' => $the_table, 'table_row_id' => $the_id), 1);
        if ($query->num_rows() == 0)
        {
            $this->insert_row_log($the_table, $the_id, $do_by, $do_by_type);
            $query = $this->db->get_where('table_row_activity', array('table_type' => $the_table, 'table_row_id' => $the_id), 1);
        }
        $activity_row = $query->row();

        $the_data = array(
            'last_modify_by' => $do_by,
            'last_modify_by_type' => $do_by_type,
        );

        $this->db->where('activity_id', $activity_row->activity_id);
        $this->db->update('table_row_activity', $the_data);
    }

    public function remove_row_log($the_table, $the_id, $do_by = NULL, $do_by_type = NULL)
    {

        $query = $this->db->get_where('table_row_activity', array('table_type' => $the_table, 'table_row_id' => $the_id), 1);
        if ($query->num_rows() == 0)
        {
            $this->insert_row_log($the_table, $the_id, $do_by, $do_by_type);
            $query = $this->db->get_where('table_row_activity', array('table_type' => $the_table, 'table_row_id' => $the_id), 1);
        }
        $activity_row = $query->row();

        $the_data = array(
            'hide_time' => get_part_of_date('all'),
            'hide_by' => $do_by,
            'hide_by_type' => $do_by_type,
        );

        $this->db->where('activity_id', $activity_row->activity_id);
        $this->db->update('table_row_activity', $the_data);
    }

    public function check_valid_phone($str)
    {
        if (preg_match("/^([\.+\s-0-9_-])+$/i", $str))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function home_search_get_merchant($search_word)
    {
        $this->db->select('company');
        $this->db->like('company', $search_word);
        $query = $this->db->get_where('users', array('main_group_id' => $this->config->item('group_id_merchant'), 'hide_flag' => 0));
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $row_set[] = stripslashes($row['company']); //build an array
            }
            echo json_encode($row_set); //format the array into json data
        }
    }

    //wanted display: name, description, short
    public function display_users_groups($groups_id, $wanted_display)
    {
        $return = '';
        $query = $this->db->get_where('groups', array('id' => $groups_id));
        if ($query->num_rows() == 1)
        {
            $result = $query->row_array();
            $return = $result[$wanted_display];
        }
        return $return;
    }

}
