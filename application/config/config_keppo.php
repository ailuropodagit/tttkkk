<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['group_id_admin'] = '1';
$config['group_id_worker'] = '2';
$config['group_id_merchant'] = '3';
$config['group_id_supervisor'] = '4';
$config['group_id_user'] = '5';

$config['album_admin_phy'] = realpath(APPPATH . '..\folder_upload\album_admin');
$config['album_admin_profile_phy'] = realpath(APPPATH . '..\folder_upload\album_admin_profile');
$config['album_merchant_phy'] = realpath(APPPATH . '..\folder_upload\album_merchant');
$config['album_merchant_profile_phy'] = realpath(APPPATH . '..\folder_upload\album_merchant_profile');
$config['album_user_phy'] = realpath(APPPATH . '..\folder_upload\album_user');
$config['album_user_profile_phy'] = realpath(APPPATH . '..\folder_upload\album_user_profile');
$config['album_user_merchant_phy'] = realpath(APPPATH . '..\folder_upload\album_user_merchant');
$config['album_banner_phy'] = realpath(APPPATH . '..\folder_upload\album_banner');
$config['folder_merchant_ssm_phy'] = realpath(APPPATH . '..\folder_upload\merchant_ssm');
$config['folder_image_temp_phy'] = realpath(APPPATH . '..\folder_upload\temp_image');
$config['folder_image_temp_cut_phy'] = realpath(APPPATH . '..\folder_upload\temp_image_cut');

$config['album_admin'] = 'folder_upload/album_admin/';
$config['album_admin_profile'] = 'folder_upload/album_admin_profile/';
$config['album_merchant'] = 'folder_upload/album_merchant/';
$config['album_merchant_profile'] = 'folder_upload/album_merchant_profile/';
$config['album_user'] = 'folder_upload/album_user/';
$config['album_user_profile'] = 'folder_upload/album_user_profile/';
$config['album_user_merchant'] = 'folder_upload/album_user_merchant/';
$config['album_banner'] = 'folder_upload/album_banner/';
$config['folder_merchant_ssm'] = 'folder_upload/merchant_ssm/';
$config['folder_image'] = 'image/';
$config['folder_image_temp'] = 'folder_upload/temp_image/';
$config['folder_image_temp_cut'] = 'folder_upload/temp_image_cut/';

$config['user_default_image'] = 'demo-profile-user.png';
$config['empty_image'] = 'image/image-empty.jpg';
$config['merchant_default_image'] = 'demo-logo-company.png';
$config['empty_image_male'] = 'image/default-image-user-gender-male.png';
$config['empty_image_female'] = 'image/default-image-user-gender-female.png';

$config['max_size'] = 10240;  //10mb = 10240kb
$config['max_width'] = 6000;
$config['max_height'] = 6000;

$config['allowed_types_image'] = 'gif|jpg|png|bmp|ico|jpeg|jpe';
$config['upload_guide_image'] = '10MB, '.$config['max_width'].'x'.$config['max_height'].' size image file';

$config['allowed_types_file'] = 'gif|jpg|png|jpeg|pdf|doc|docx|txt';
$config['upload_guide_file'] = '10MB Image/PDF/Doc/Txt file';

$config['keppo_format_date_display'] = 'd-m-Y';
$config['keppo_format_date_time_display'] = 'd-m-Y H:i';
$config['keppo_format_date_db'] = 'Y-m-d';
$config['keppo_format_date_time_db'] = 'Y-m-d H:i:s';
$config['keppo_format_year_month_db'] = 'Y-m';
$config['keppo_format_time_display'] = 'H:i';
$config['keppo_format_date_display_eng'] = 'd M Y';
$config['keppo_format_date_time_display_eng'] = 'd M Y H:i';

$config['merchant_upload_box_per_page'] = 5;
$config['user_upload_box_per_page'] = 5;

$config['keppo_admin_id'] = '1';
$config['keppo_email_domain'] = '@keppo.my';
$config['froze_account_activate'] = 0;
$config['notification_auto_mark_as_read'] = 1;
$config['strong_password'] = 1;

$config['custom_per_page'] = '10';
$config['candie_minimum'] = '30';

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

$config['category_keppo_voucher'] = '27';
$config['category_epay'] = '28';
$config['category_mytaxsi'] = '29';
$config['category_uber'] = '30';
$config['minimum_withdraw_amount'] = '50';
$config['first_candie_remind'] = 30;
$config['first_candie_remind_time'] = 2;

$config['food_category_id'] = 1;

$config['suggest_list_number'] = '5';
$config['password_example'] = 'keppo123';
$config['password_example_encrypt'] = '$2y$08$xZgTvC/QvRc0xeqzncsJ3O8q.EO/ik2Gqvaz.IT9d8LD1UF.HySM6';
$config['no_reply_email'] = 'no-reply@keppo.my';

$config['sensitive_word'] = array("fuck", "sex", "gay", "hemosexual", "lanci", "cibai", "dick", "virgin");

switch($_SERVER["SERVER_NAME"]) {
case "www.keppo.ml":
$config['keppo_email_domain'] = '@keppo.ml';
$config['no_reply_email'] = 'no-reply@keppo.ml';  
break;
}