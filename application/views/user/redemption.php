<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="payment">
    <h1><?php echo $title; ?></h1>
    <div id='payment-content'>

        <div id='payment-print'>
            <a href="<?php echo $candie_url; ?>" >Candies Balance</a> | 
            <a href="<?php echo $voucher_active_url; ?>" >Active Voucher</a> | 
            <a href="<?php echo $voucher_used_url; ?>" >Used Voucher</a> | 
            <a href="<?php echo $voucher_expired_url; ?>" >Expired Voucher</a>
        </div><br/>
            <?php echo form_open(uri_string());?>
            <span id="candie-promotion-form-go-label"><?php echo "Category "; ?></span>
            <span id="candie-promotion-form-go-year"><?php echo form_dropdown($sub_category, $sub_category_list, $sub_category_selected); ?></span>
            <span id="candie-promotion-form-go-button"><button name="button_action" type="submit" value="search_by_subcategory">Search</button></span>
            <?php echo form_close(); ?><br/>
        <?php
        //var_dump($redemption);
        foreach ($redemption as $row)
        {
            $advertise_detail_url = base_url() . "all/advertise/" . $row['advertise_id'];
            ?>
            <div id='advertise-list-box'>

                <div id="advertise-list-title1">
                    <?php echo $this->m_custom->generate_merchant_link($row['merchant_id']); ?>
                </div>

                <div id="advertise-list-photo">
                    <div id="advertise-list-photo-box">
                        <a href='<?php echo $advertise_detail_url; ?>'><img src='<?php echo base_url().$this->config->item('album_merchant') . $row['image']; ?>'></a>
                    </div>
                </div>
                <div id="advertise-list-title2">
                    <a href='<?php echo $advertise_detail_url; ?>'><?php echo $row['title'] ?></a>
                </div>
                <div id="advertise-list-info">
                    <table border="0" cellpadding="4px" cellspacing="0px">
                        <tr>
                            <td>Category</td>
                            <td>:</td>
                            <td>
                                <div id="advertise-list-info-category"><?php echo $this->m_custom->display_category($row['sub_category_id']) ?></div>
                            </td>
                        </tr>
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
                    </table>
                </div>
                <div id="advertise-list-dynamic-time">
                    <i class="fa fa-bullseye"></i><span id="advertise-list-dynamic-time-label"><?php echo $row['voucher_candie'] ?> candies</span>
                </div>
            </div>
            <?php
        }
        ?>

    </div>
</div>