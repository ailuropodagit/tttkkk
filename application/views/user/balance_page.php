<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="payment">
    <h1><?php echo "Balance"; ?></h1>
    <h1 style="float:right;">Current Balance : RM <?php echo $this_month_balance; ?></h1>
    <div id='payment-content'>
        
        YOUR ACCUMULATED BALANCE STILL COUNT!<br/>
        COMING SOON...
        
        
    </div>
</div>