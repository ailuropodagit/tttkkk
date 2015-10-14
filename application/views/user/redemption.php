<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="voucher">
    <h1><?php echo $title; ?></h1>
    <div id='voucher-content'>

        <div id='voucher-navigator'>
            <div id='voucher-navigation-each'><a href="<?php echo $candie_url; ?>" >Candies Balance</a></div>
            <div id='voucher-navigation-each-separator'>|</div> 
            <div id='voucher-navigation-each'><a href="<?php echo $voucher_active_url; ?>" ><?php echo $voucher_active_count; ?></a></div>
            <div id='voucher-navigation-each-separator'>|</div>
            <div id='voucher-navigation-each'><a href="<?php echo $voucher_used_url; ?>" ><?php echo $voucher_used_count; ?></a></div>
            <div id='voucher-navigation-each-separator'>|</div>
            <div id='voucher-navigation-each'><a href="<?php echo $voucher_expired_url; ?>" ><?php echo $voucher_expired_count; ?></a></div>
        </div>
        <div id="float-fix"></div>
        
        <div id="voucher-go">
            <?php echo form_open(uri_string()) ?>
            <span id="voucher-go-label"><?php echo "Category "; ?></span>
            <span id="voucher-go-dropdown"><?php echo form_dropdown($sub_category, $sub_category_list, $sub_category_selected); ?></span>
            <span id="voucher-go-button"><button name="button_action" type="submit" value="search_by_subcategory">Search</button></span>
            <?php echo form_close() ?>
        </div>
            
        <div id="advertise-list">
            <?php
            foreach ($redemption as $row)
            {
                $advertise_type = $row['advertise_type'];
                if ($advertise_type == "adm")
                {
                    $image_url = base_url().$this->config->item('album_admin') . $row['image'];
                }
                else
                {               
                    $image_url = base_url().$this->config->item('album_merchant') . $row['image'];
                }
                $merchant_link = $this->m_custom->generate_merchant_link($row['merchant_id']);
                $advertise_detail_url = base_url() . "all/voucher/" . $row['advertise_id'] . "/" . $row['redeem_id'];
                $top_up_phone = $row['top_up_phone'];
                ?>
                <div id='advertise-list-box'>
                    <div id="advertise-list-title1">
                        <?php echo $merchant_link; ?>
                    </div>
                    <div id="advertise-list-photo">
                        <div id="advertise-list-photo-box">
                            <a href='<?php echo $advertise_detail_url; ?>' target='_blank'><img src='<?php echo $image_url; ?>'></a>
                        </div>
                    </div>
                    <div id="advertise-list-title2">
                        <a href='<?php echo $advertise_detail_url; ?>' target='_blank'><?php echo $row['title'] ?></a>
                    </div>                
                    <div id="advertise-list-dynamic-time">
                        <i class="fa fa-bullseye"></i><span id="advertise-list-dynamic-time-label"><?php echo $row['voucher_candie'] ?> candies</span>
                    </div>
                    <div id="advertise-list-dynamic-time">
                        <span id="advertise-list-dynamic-time-label"><?php echo "Voucher : ".$row['voucher'] ?></span>
                    </div>
                    <div id="advertise-list-info">
                        <table border="0" cellpadding="4px" cellspacing="0px">                       
                            <?php if (($advertise_type == 'pro' || $advertise_type == 'adm') && !empty($row['voucher_worth'])) { ?>
                                <tr>
                                    <td>Worth</td>
                                    <td>:</td>
                                    <td>
                                        <div id="advertise-list-voucher-worth"><?php echo "RM " . $row['voucher_worth']; ?></div>
                                    </td>
                                </tr>    
                            <?php } ?>
                            <tr>
                                <td>Category</td>
                                <td>:</td>
                                <td>
                                    <div id="advertise-list-info-category"><?php echo $this->m_custom->display_category($row['sub_category_id']) ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td>Expire Date</td>
                                <td>:</td>
                                <td>
                                    <div id="advertise-list-info-category"><?php echo displayDate($row['expired_date']) ?></div>
                                </td>
                            </tr>
                            <?php 
                            if ($advertise_type != 'adm'){
                            ?>
                            <tr>
                                <td>Like</td>
                                <td>:</td>
                                <td><?php echo $this->m_custom->generate_like_list_link($row['advertise_id'], 'adv'); ?></td>
                            </tr>
                            <tr>
                                <td>Comment</td>
                                <td>:</td>
                                <td><?php echo $this->m_custom->activity_comment_count($row['advertise_id'], 'adv'); ?></td>
                            </tr>
                            <?php } ?>
                            <?php 
                            if (!empty($top_up_phone)){
                            ?>
                            <tr>
                                <td>Top Up To</td>
                                <td>:</td>
                                <td><?php echo $top_up_phone; ?></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>               
                </div>
                <?php
            }
            ?>
        </div>
        <div id="advertise-list-empty-bottom-fix"></div>

    </div>
</div>