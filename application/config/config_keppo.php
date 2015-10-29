<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['group_id_admin'] = '1';
$config['group_id_worker'] = '2';
$config['group_id_merchant'] = '3';
$config['group_id_supervisor'] = '4';
$config['group_id_user'] = '5';

$config['album_admin_phy'] = realpath(APPPATH . '..\folder_upload\album_admin');
$config['album_merchant_phy'] = realpath(APPPATH . '..\folder_upload\album_merchant');
$config['album_merchant_profile_phy'] = realpath(APPPATH . '..\folder_upload\album_merchant_profile');
$config['album_user_phy'] = realpath(APPPATH . '..\folder_upload\album_user');
$config['album_user_profile_phy'] = realpath(APPPATH . '..\folder_upload\album_user_profile');
$config['album_user_merchant_phy'] = realpath(APPPATH . '..\folder_upload\album_user_merchant');
$config['folder_merchant_ssm_phy'] = realpath(APPPATH . '..\folder_upload\merchant_ssm');
$config['folder_image_temp_phy'] = realpath(APPPATH . '..\folder_upload\temp_image');

$config['album_admin'] = 'folder_upload/album_admin/';
$config['album_merchant'] = 'folder_upload/album_merchant/';
$config['album_merchant_profile'] = 'folder_upload/album_merchant_profile/';
$config['album_user'] = 'folder_upload/album_user/';
$config['album_user_profile'] = 'folder_upload/album_user_profile/';
$config['album_user_merchant'] = 'folder_upload/album_user_merchant/';
$config['folder_merchant_ssm'] = 'folder_upload/merchant_ssm/';
$config['folder_image'] = 'image/';
$config['folder_image_temp'] = 'folder_upload/temp_image/';

$config['user_default_image'] = 'demo-profile-user.png';
$config['empty_image'] = 'image/image-empty.jpg';
$config['merchant_default_image'] = 'demo-logo-company.png';

$config['max_size'] = 10240;  //10mb = 10240kb
$config['max_width'] = 2736;
$config['max_height'] = 2648;

$config['allowed_types_image'] = 'gif|jpg|png|bmp|ico|jpeg|jpe';
$config['upload_guide_image'] = '10MB, '.$config['max_width'].'x'.$config['max_height'].' size image file';

$config['allowed_types_file'] = 'gif|jpg|png|jpeg|pdf|doc|docx|txt';
$config['upload_guide_file'] = '10MB Image/PDF/Doc/Txt file';

$config['keppo_format_date_display'] = 'd-m-Y';
$config['keppo_format_date_time_display'] = 'd-m-Y H:i';
$config['keppo_format_date_db'] = 'Y-m-d';
$config['keppo_format_date_time_db'] = 'Y-m-d H:i:s';
$config['keppo_format_year_month_db'] = 'Y-m';

$config['hotdeal_per_day'] = 25;
$config['merchant_upload_box_per_page'] = 5;
$config['user_upload_box_per_page'] = 5;
$config['user_max_picture_per_day'] = 15;
$config['user_max_invitation_get_candie_per_month'] = 5;

$config['keppo_admin_id'] = '1';
$config['keppo_company_name'] = 'Fuyoo Advertising & Services';
$config['keppo_email_domain'] = '@keppo.my';
$config['keppo_admin_email'] = 'wilkinwilly999@gmail.com,weechiat.teo@hotmail.com';
$config['merchant_minimum_balance'] = 50;
$config['froze_account_activate'] = 0;
$config['notification_auto_mark_as_read'] = 1;

$config['custom_per_page'] = '8';

$config['gender_id_male'] = '13';
$config['gender_id_female'] = '14';

$config['race_id_malay'] = '16';
$config['race_id_chinese'] = '17';
$config['race_id_india'] = '18';
$config['race_id_other'] = '19';

$config['voucher_active'] = '21';
$config['voucher_used'] = '22';
$config['voucher_expired'] = '23';

$config['user_activity_like'] = '45';
$config['user_activity_rating'] = '46';
$config['user_activity_comment'] = '47';

$config['popular_hotdeal_number'] = '3';
$config['popular_redemption_number'] = '3';