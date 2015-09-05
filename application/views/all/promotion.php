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
</br>
Terms & Condition :
<ul>
        <?php
        foreach ($candie_term as $value) {
                echo "<li>".$value['option_desc'] . "</li>";
        }
        ?>  
</ul>

<?php echo "Expiry Date : " . $expire_date . "<br/><br/>"; ?>

Available Branch : 
<ul>
        <?php
        foreach ($candie_branch as $value) {
                echo "<li>";
                echo "<a href='".base_url() . "all/merchant-map/". $value['branch_id']."' target='_blank'>". $value['name']."</a><br/>";
                echo $value['address'] . "<br/>";
                echo "Tel : <a href='tel:".$value['phone']."' >".$value['phone']."</a><br/>";
                echo "</li><br/>";
        }
        ?>  
</ul>