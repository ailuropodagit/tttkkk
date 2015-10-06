<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('set_simple_message'))
{

    function set_simple_message($title = '', $sentence1 = '', $sentence2 = '', $back_page_url = '', $back_page = '', $maintain_page = '', $have_session = 1, $redirect_second = 10)
    {
        $ci = & get_instance();
        $simple_info = array(
            'title' => $title,
            'sentence1' => $sentence1 . "</br>",
            'sentence2' => $sentence2 . "</br>",
            'back_page_url' => $back_page_url,
            'back_page' => $back_page,
            'maintain_page' => $maintain_page,
            'redirect_second' => $redirect_second*1000,
        );

        if ($have_session == 1)
        {
            $ci->session->set_flashdata('simple_info', $simple_info);
        }
        redirect($maintain_page, 'refresh');
    }

}

if (!function_exists('display_simple_message'))
{

    function display_simple_message()
    {
        $ci = & get_instance();
        $simple_info = $ci->session->flashdata('simple_info');
        if (!empty($simple_info))
        {
            $redirect_url = $simple_info['back_page_url'];
            $pos = strpos($redirect_url, 'http://');
            if ($pos === false)
            {
                $ci->data['redirect_url'] = base_url() . $redirect_url;
            }
            else
            {
                $ci->data['redirect_url'] = $redirect_url;
            }

            if($simple_info['back_page_url'])
            $ci->data['simple_info'] = $simple_info;
            $ci->data['page_path_name'] = 'simple_message';
            $ci->load->view('template/layout', $ci->data);
        }
        else
        {
            redirect('/', 'refresh');
        }
    }

}
if (!function_exists('check_is_positive_numeric'))
{

    function check_is_positive_numeric($i)
    {
        if (!is_numeric($i) || $i < 0 || $i != round($i))
        {
            return 0;
        }
        else
        {
            return $i;
        }
    }

}

if (!function_exists('check_correct_login_type'))
{

    function check_correct_login_type($desired_group_id, $allowed_list = NULL, $check_id = NULL)
    {
        $ci = & get_instance();

        //Check is it login
        if (!$ci->ion_auth->logged_in())
        {
            return FALSE;
        }

        $id = $ci->ion_auth->user()->row()->id;
        //Check is the url id is same with login session id
        if (!($ci->ion_auth->user()->row()->id == $id))
        {
            return FALSE;
        }

        $user = $ci->ion_auth->user($id)->row();
        //Check is this user type can go in this page or not
        if ($user->main_group_id != $desired_group_id)
        {
            return FALSE;
        }

        if (!empty($allowed_list) && !IsNullOrEmptyString($check_id))
        {
            if (!in_array($check_id, $allowed_list))
            {
                return FALSE;
            }
        }
        return TRUE;
    }

}

if (!function_exists('check_allowed_list'))
{

    function check_allowed_list($allowed_list = NULL, $check_id = NULL)
    {
        if (!empty($allowed_list) && !IsNullOrEmptyString($check_id))
        {
            if (in_array($check_id, $allowed_list))
            {
                return TRUE;
            }
        }
        return FALSE;
    }

}

if (!function_exists('check_is_login'))
{

    function check_is_login()
    {
        $ci = & get_instance();
        $ci->load->library('ion_auth');

        //Check is it login
        if (!$ci->ion_auth->logged_in())
        {
            return FALSE;
        }
        return TRUE;
    }

}

if (!function_exists('send_mail_simple'))
{

    function send_mail_simple($to_email = '', $to_subject = '', $to_message = '', $success_message = '', $have_session = 1)
    {
        $ci = & get_instance();
        $ci->load->library('email'); // Note: no $config param needed
        $ci->email->from($ci->config->item('smtp_user'), $ci->config->item('from_name'));
        $ci->email->to($to_email);
        $ci->email->subject($to_subject);
        $ci->email->message($to_message);
        if ($ci->email->send())
        {
            if ($have_session == 1)
            {
                $ci->ion_auth->set_message($success_message);
            }
            return TRUE;
        }
        else
        {
            //show_error($this->email->print_debugger());
            if ($have_session == 1)
            {
                $ci->ion_auth->set_error('fail_to_send_email');
            }
            return False;
        }
    }

}

if (!function_exists('date_for_db_search'))
{
    function date_for_db_search($month_id = NULL, $year = NULL){
        if (empty($month_id))
        {
            $month_id = get_part_of_date('month');
        }
        if (empty($year))
        {
            $year = get_part_of_date('year');
        }
        $search_date = $year . '-' . str_pad($month_id, 2, "0", STR_PAD_LEFT);
        return $search_date;
    }
}

if (!function_exists('generate_options'))
{

    function generate_slug($value = '')
    {
        return url_title($value, 'dash', TRUE);
    }

}
if (!function_exists('generate_options'))
{

    function generate_options($from, $to, $callback = false)
    {
        $reverse = false;
        if ($from > $to)
        {
            $tmp = $from;
            $from = $to;
            $to = $tmp;
            $reverse = true;
        }
        $return_string = array();
        for ($i = $from; $i <= $to; $i++)
        {
            $return_string[] = '<option value="' . $i . '">' . ($callback ? $callback($i) : $i) . '</option>';
        }

        if ($reverse)
        {
            $return_string = array_reverse($return_string);
        }
        return join('', $return_string);
    }

}
if (!function_exists('generate_number_option'))
{

    function generate_number_option($from, $to)
    {
        return array_combine(range($from, $to), range($from, $to));
    }

}
if (!function_exists('callback_month'))
{

    function callback_month($month)
    {
        return date('F', mktime(0, 0, 0, $month, 1));
    }

}

if (!function_exists('get_part_of_date'))
{

    function get_part_of_date($part, $date = NULL, $is_timestamp = 0)
    {
        if (IsNullOrEmptyString($date))
        {
            switch ($part)
            {
                case "year":
                    return date('Y');
                case "month":
                    return date('m');
                case "day":
                    return date('d');
                case "hour":
                    return date('H');
                case "minute":
                    return date('i');
                default:
                    return date('Y-m-d H:i:s');
            }
        }
        else
        {
            if($is_timestamp==1){
                $date = date('Y-m-d',$date);
            }
            $the_date = explode('-', $date);
            switch ($part)
            {
                case "year":
                    return $the_date[0];
                case "month":
                    return $the_date[1];
                case "day":
                    return $the_date[2];
                default:
                    return 0;
            }
        }
    }

}

if (!function_exists('add_hour_to_date'))
{

    function add_hour_to_date($hour_add, $date = NULL)
    {

        if (IsNullOrEmptyString($date))
        {
            $the_date = date(format_date_time_server());
        }
        else
        {
            $the_date = $date;
        }

        $argument = '+' . $hour_add . ' hours';
        $return_date = date(format_date_time_server(), strtotime($argument, strtotime($the_date)));
        return $return_date;
    }

}

if (!function_exists('relative_time'))
{

    function relative_time($datetime)
    {
        if (!$datetime)
        {
            return "no data";
        }

        if (!is_numeric($datetime))
        {
            $val = explode(" ", $datetime);
            $date = explode("-", $val[0]);
            $time = explode(":", $val[1]);
            $datetime = mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
        }

        $difference = time() - $datetime;
        $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

        if ($difference > 0)
        {
            $ending = 'ago';
        }
        else
        {
            $difference = -$difference;
            $ending = 'to go';
        }
        for ($j = 0; $difference >= $lengths[$j]; $j++)
        {
            $difference /= $lengths[$j];
        }
        $difference = round($difference);

        if ($difference != 1)
        {
            $period = strtolower($periods[$j] . 's');
        }
        else
        {
            $period = strtolower($periods[$j]);
        }

        return "$difference $period $ending";
    }

}

if (!function_exists('format_date_time_server'))
{

    function format_date_time_server()
    {
        $ci = & get_instance();
        return $ci->config->item('keppo_format_date_time_db');
    }

}

if (!function_exists('format_date_server'))
{

    function format_date_server()
    {
        $ci = & get_instance();
        return $ci->config->item('keppo_format_date_db');
    }

}

if (!function_exists('format_year_month_server'))
{

    function format_year_month_server()
    {
        $ci = & get_instance();
        return $ci->config->item('keppo_format_year_month_db');
    }

}

if (!function_exists('date_server_format'))
{

    function date_server_format($date = NULL)
    {
        $ci = & get_instance();
        $the_format = $ci->config->item('keppo_format_date_db');
        if (!IsNullOrEmptyString($date))
        {
            $date=date_create($date);
            return date_format($date,$the_format);
        }else{
            return date($the_format);
        }
    }

}

if (!function_exists('toggle_date_format'))
{

    function toggle_date_format($date = NULL)
    {
        $change_date = $date;
        if (!IsNullOrEmptyString($date))
        {
            $the_date = explode('-', $date);
            $change_date = $the_date[2] . '-' . $the_date[1] . '-' . $the_date[0];
        }
        return $change_date;
    }
}

if (!function_exists('format_date_english_month'))
{

    function format_date_english_month($date = NULL)
    {
        if (IsNullOrEmptyString($date))
        {
            $the_date = date("Y-m-d H:i:s");
        }
        else
        {
            $the_date = $date;
        }
        $parts = explode('-', $the_date);
        $return_date = date('F j, Y', mktime(0, 0, 0, $parts[1], $parts[2], $parts[0]));
        return $return_date;
    }

}

if (!function_exists('delete_file'))
{

    function delete_file($path)
    {
        if (unlink($path))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

}

if (!function_exists('add_message_info'))
{

    function add_message_info($message_info, $new_info, $title = '')
    {

        if (!IsNullOrEmptyString($new_info))
        {
            if (IsNullOrEmptyString($title))
            {
                $message_info = $message_info . "<p>" . $new_info . "</p>";
            }
            else
            {
                $message_info = $message_info . "<p>" . $title . " : " . $new_info . "</p>";
            }
        }

        return $message_info;
    }

}

if (!function_exists('age_count'))
{

// input $date string format: YYYY-MM-DD
    function age_count($date)
    {
        list($year, $month, $day) = explode("-", $date);
        $year_diff = date("Y") - $year;
        $month_diff = date("m") - $month;
        $day_diff = date("d") - $day;
        if ($day_diff < 0 || $month_diff < 0)
            $year_diff--;
        return $year_diff;
    }

}

if (!function_exists('get_key_array_from_list_array'))
{

    function get_key_array_from_list_array($list_array, $column_wanted)
    {
        $key_array = array();
        foreach ($list_array as $row)
        {
            $key_array[] = $row[$column_wanted];
        }
        return $key_array;
    }

}

if (!function_exists('get_previous_id'))
{

    function get_previous_id($current_id, $list_array)
    {
        $index = array_search($current_id, $list_array);
        if ($index !== FALSE)
        {
            if (array_key_exists($index - 1, $list_array))
            {
                $previous = $list_array[$index - 1];
            }
        }
        if (!empty($previous))
        {
            return $previous;
        }
        return FALSE;
    }

}
if (!function_exists('get_current_url'))
{

    function get_current_url()
    {
        $pageURL = 'http';
        if (!empty($_SERVER["HTTPS"]))
        {
            if ($_SERVER["HTTPS"] == "on")
            {
                $pageURL .= "s";
            }
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80")
        {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        }
        else
        {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

}

if (!function_exists('get_next_id'))
{

    function get_next_id($current_id, $list_array)
    {
        $index = array_search($current_id, $list_array);
        if ($index !== FALSE)
        {
            if (array_key_exists($index + 1, $list_array))
            {
                $next = $list_array[$index + 1];
            }
        }
        if (!empty($next))
        {
            return $next;
        }
        return FALSE;
    }

}

if (!function_exists('IsNullOrEmptyString'))
{

    function IsNullOrEmptyString($value)
    {
        return (!isset($value) || trim($value) === '' || empty($value));
    }

}

if (!function_exists('RemoveLastComma'))
{

    function RemoveLastComma($value)
    {
        if (substr($value, -1, 1) == ',')
        {
            $value = substr($value, 0, -1);
        }
        return $value;
    }

}

if (!function_exists('displayDate'))
{

    function displayFirstDay($year = '', $month = '')
    {
        if (empty($month))
        {
            $month = date('m');
        }
        if (empty($year))
        {
            $year = date('Y');
        }
        $result = strtotime("{$year}-{$month}-01");
        return date('d-m-Y', $result);
    }

}

if (!function_exists('displayDate'))
{

    function displayLastDay($year = '', $month = '', $until_today = 0)
    {
        if (empty($month))
        {
            $month = date('m');
        }
        if (empty($year))
        {
            $year = date('Y');
        }
        $add_argument = '+1 month';
        if($until_today == 1 && $month == date('m') && $year == date('Y')){
            $add_argument = "+" . get_part_of_date('day') . " day";
        }
        $result = strtotime("{$year}-{$month}-01");
        $result = strtotime('-1 second', strtotime($add_argument, $result));
        return date('d-m-Y', $result);
    }

}

if (!function_exists('displayDate'))
{
   function displayDate( $date , $with_time = 0, $db_format = 0)
    {
       if(IsNullOrEmptyString($date)){
           return '';
       }
        $ci = & get_instance();
        $return_date = date_create($date);
        if($with_time == 0 && $db_format == 0){
            return $return_date->format($ci->config->item('keppo_format_date_display'));
        }else if($with_time != 0 && $db_format == 0){
            return $return_date->format($ci->config->item('keppo_format_date_time_display'));
        }else if($with_time == 0 && $db_format != 0){
            return $return_date->format($ci->config->item('keppo_format_date_db'));
        }else if($with_time != 0 && $db_format != 0){
            return $return_date->format($ci->config->item('keppo_format_date_time_db'));
        }else{
            return '';
        }
   }
}

if (!function_exists('validateDate'))
{
   function validateDate( $date, $format='DD-MM-YYYY')
    {
       if(IsNullOrEmptyString($date)){
           return NULL;
       }
        switch( $format )
        {
            case 'YYYY/MM/DD':
            case 'YYYY-MM-DD':
            list( $y, $m, $d ) = preg_split( '/[-\.\/ ]/', $date );
            break;

            case 'YYYY/DD/MM':
            case 'YYYY-DD-MM':
            list( $y, $d, $m ) = preg_split( '/[-\.\/ ]/', $date );
            break;

            case 'DD-MM-YYYY':
            case 'DD/MM/YYYY':
            list( $d, $m, $y ) = preg_split( '/[-\.\/ ]/', $date );
            break;

            case 'MM-DD-YYYY':
            case 'MM/DD/YYYY':
            list( $m, $d, $y ) = preg_split( '/[-\.\/ ]/', $date );
            break;

            case 'YYYYMMDD':
            $y = substr( $date, 0, 4 );
            $m = substr( $date, 4, 2 );
            $d = substr( $date, 6, 2 );
            break;

            case 'YYYYDDMM':
            $y = substr( $date, 0, 4 );
            $d = substr( $date, 4, 2 );
            $m = substr( $date, 6, 2 );
            break;

            default:
            throw new Exception( "Invalid Date Format" );
        }
        
        if(checkdate( $m, $d, $y )){
            return $y.'-'.$m.'-'.$d;
        }
        else{
            return NULL;
        }
    }
}