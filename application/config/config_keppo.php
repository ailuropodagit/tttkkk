<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['group_id_admin'] = '1';
$config['group_id_worker'] = '2';
$config['group_id_merchant'] = '3';
$config['group_id_supervisor'] = '4';
$config['group_id_user'] = '5';

$config['album_merchant'] = realpath(APPPATH . '..\image\album_merchant');
$config['album_user'] = realpath(APPPATH . '..\image\album_user');
$config['album_user_merchant'] = realpath(APPPATH . '..\image\album_user_merchant');

