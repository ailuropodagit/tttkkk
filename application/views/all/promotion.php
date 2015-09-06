<h1>Redemption</h1>
<br/>

<h2><?php  echo $name . "</br>"; ?></h2>

<?php
if(!empty($previous_url)){
echo "<a href='" . $previous_url . "' >Previous</a> ";
}
if(!empty($next_url)){
echo "<a href='" . $next_url . "' >Next</a> ";
}
?>
<br/>

<img src="<?php echo $voucher_barcode; ?>"  alt="not show" style="margin-top:20px; margin-left:70%;"/>
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
<?php
    echo "Like : 12 ";
    echo "Comment : 34 ";
    echo "Share : <br/>";
    ?>
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
<br/>
<?php 
if (check_is_login()) {
if (check_correct_login_type($this->config->item('group_id_merchant')))
{ ?>
    <div id="profile-bottom-link-right">
        <div id="profile-bottom-link-right-each">
            <a href='<?php echo base_url() . "merchant/candie_promotion/" . $advertise_id ?>' >Edit Redemption</a>
        </div>
        <div id='float-fix'></div>
    </div>
<?php } } ?>