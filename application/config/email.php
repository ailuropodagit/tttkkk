<?php 
defined('BASEPATH') OR exit('No direct script access allowed.');

//$config['smtp_host'] = 'ssl://fuyoo-173-164.fuyoo.com';
$config['smtp_host'] = 'ssl://mail.keppo.my';
$config['smtp_user'] = 'no-reply@keppo.my';
$config['smtp_pass'] = '!admin123!';  
switch($_SERVER["SERVER_NAME"]) {
case "localhost":
$config['smtp_host'] = 'ssl://smtp.googlemail.com';
$config['smtp_user'] = 'kepposend@gmail.com';
$config['smtp_pass'] = 'keppo12345';
break;
case "www.keppo.my":
//$config['smtp_host'] = 'ssl://fuyoo-173-164.fuyoo.com';
$config['smtp_host'] = 'ssl://mail.keppo.my';
$config['smtp_user'] = 'no-reply@keppo.my';
$config['smtp_pass'] = '!admin123!';  
break;
case "www.keppo.ml":
$config['smtp_host'] = 'ssl://mail.keppo.ml';
$config['smtp_user'] = 'no-reply@keppo.ml';
$config['smtp_pass'] = '!admin123!';  
break;
}

$config['protocol'] = 'smtp';
$config['smtp_port'] = '465';
$config['from_name'] = 'Keppo';
        
$config['useragent']        = 'CodeIgniter';              // Mail engine switcher: 'CodeIgniter' or 'PHPMailer'
//$config['protocol']         = 'mail';                   // 'mail', 'sendmail', or 'smtp'
//$config['mailpath']         = '/usr/sbin/sendmail';
//$config['smtp_host']        = 'localhost';
//$config['smtp_user']        = '';
//$config['smtp_pass']        = '';
//$config['smtp_port']        = 25;
$config['smtp_timeout']     = 5;                        // (in seconds)
$config['smtp_crypto']      = '';                       // '' or 'tls' or 'ssl'
$config['smtp_debug']       = 0;                        // PHPMailer's SMTP debug info level: 0 = off, 1 = commands, 2 = commands and data, 3 = as 2 plus connection status, 4 = low level data output.
$config['wordwrap']         = true;
$config['wrapchars']        = 76;
$config['mailtype']         = 'html';                   // 'text' or 'html'
$config['charset']          = 'ISO-8859-15';                     // 'UTF-8', 'ISO-8859-15', ...; NULL (preferable) means config_item('charset'), i.e. the character set of the site.
$config['validate']         = true;
$config['priority']         = 3;                        // 1, 2, 3, 4, 5; on PHPMailer useragent NULL is a possible option, it means that X-priority header is not set at all, see https://github.com/PHPMailer/PHPMailer/issues/449
$config['crlf']             = "\r\n";                     // "\r\n" or "\n" or "\r"
$config['newline']          = "\r\n";                     // "\r\n" or "\n" or "\r"
$config['bcc_batch_mode']   = false;
$config['bcc_batch_size']   = 200;
$config['encoding']         = '8bit';                   // The body encoding. For CodeIgniter: '8bit' or '7bit'. For PHPMailer: '8bit', '7bit', 'binary', 'base64', or 'quoted-printable'.