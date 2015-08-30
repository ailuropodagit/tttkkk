<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['group_id_admin'] = '1';
$config['group_id_worker'] = '2';
$config['group_id_merchant'] = '3';
$config['group_id_supervisor'] = '4';
$config['group_id_user'] = '5';

$config['album_merchant_phy'] = realpath(APPPATH . '..\folder_upload\album_merchant');
$config['album_merchant_profile_phy'] = realpath(APPPATH . '..\folder_upload\album_merchant_profile');
$config['album_user_phy'] = realpath(APPPATH . '..\folder_upload\album_user');
$config['album_user_profile_phy'] = realpath(APPPATH . '..\folder_upload\album_user_profile');
$config['album_user_merchant_phy'] = realpath(APPPATH . '..\folder_upload\album_user_merchant');
$config['folder_merchant_ssm_phy'] = realpath(APPPATH . '..\folder_upload\merchant_ssm');

$config['album_merchant'] =  'folder_upload/album_merchant/';
$config['album_merchant_profile'] =  'folder_upload/album_merchant_profile/';
$config['album_user'] = 'folder_upload/album_user/';
$config['album_user_profile'] = 'folder_upload/album_user_profile/';
$config['album_user_merchant'] = 'folder_upload/album_user_merchant/';
$config['folder_merchant_ssm'] = 'folder_upload/merchant_ssm/';

$config['user_default_image'] = 'demo-profile-user.png';
$config['merchant_default_image'] = 'demo-logo-company.png';

$config['allowed_types_image']        = 'gif|jpg|png|bmp|ico|jpeg|jpe';
$config['max_size']             = 10240;  //10mb = 10240kb
$config['max_width']            = 1000;
$config['max_height']           = 1000;
$config['upload_guide_image']   = '10MB, 1000x1000 size image file';

$config['allowed_types_file']   = 'gif|jpg|png|jpeg|pdf|doc|docx|txt';
$config['upload_guide_file']   = '10MB Image/PDF/Doc/Txt file';

$config['keppo_date_format'] = 'd-m-Y';
$config['keppo_email_domain'] = '@keppo.my';
$config['hotdeal_per_day'] = 5;