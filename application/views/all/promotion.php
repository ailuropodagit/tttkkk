<script type="text/javascript" src="http://localhost/keppo/js/jquery.countdown.js"></script>

<h1>Redemption</h1>
<br/>

<img src="<?php echo $voucher_barcode; ?>"  alt="not show" style="margin-top:20px; margin-left:70%;"/>

<h2><?php  echo $name . "</br>"; ?></h2>
<div id='hot-deal-photo-box'>
    <?php
    echo "<img src='" . $image_url . "' id='hotdeal-img'>";
    echo $title . "</br>";
    echo $voucher_candie . " Candies</br>";
    ?>
</div>
<br/><br/>
<div id="float-fix"></div>
<?php echo "Category : " . $sub_category . "<br/>"; ?>
<?php echo "Redeem Period : " . $start_date . " to " . $end_date . "<br/>"; ?>

Description :
    <?php   echo $description . "</br>";     ?>

Terms & Condition :
<ul>
    
</ul>

<?php echo "Expiry Date : " . $expire_date . "<br/>"; ?>

Available Branch : 
