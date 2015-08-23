<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['group_id_admin'] = '1';
$config['group_id_worker'] = '2';
$config['group_id_merchant'] = '3';
$config['group_id_supervisor'] = '4';
$config['group_id_user'] = '5';

$config['album_merchant_phy'] = realpath(APPPATH . '..\image\album_merchant');
$config['album_user_phy'] = realpath(APPPATH . '..\image\album_user');
$config['album_user_merchant_phy'] = realpath(APPPATH . '..\image\album_user_merchant');

$config['album_merchant'] =  'image/album_merchant/';
$config['album_user'] = 'image/album_user/';
$config['album_user_merchant'] = 'image/album_user_merchant/';

$config['allowed_types']        = 'gif|jpg|png|bmp|ico|jpeg|jpe';
$config['max_size']             = 10240;  //10mb = 10240kb
$config['max_width']            = 1000;
$config['max_height']           = 1000;

$config['keppo_email_domain'] = '@keppo.my';