<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function range_two_digit_associative_array($start, $end)
{
    $day_array = array();
    for($i = $start; $i <= $end; $i++)
    {
        $ii = sprintf("%02d", $i);
        $day_array[$ii] = $ii;
    }     
    return $day_array;
}