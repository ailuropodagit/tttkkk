<div id="infoMessage"><?php echo $message; ?></div>

<div id="payment">
    <h1><?php echo $title; ?></h1>
    <div id='payment-content'>
        
        <div id='payment-print'>
            <a href="<?php echo $candie_url; ?>" >Candies Balance</a> | 
            <a href="<?php echo $voucher_active_url; ?>" >Active Voucher</a> | 
            <a href="<?php echo $voucher_used_url; ?>" >Used Voucher</a> | 
            <a href="<?php echo $voucher_expired_url; ?>" >Expired Voucher</a>
        </div>

<?php

var_dump($redemption);

?>

            </div>
</div>