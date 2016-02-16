<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/jgrowl/jquery.jgrowl.js"></script>
<?php echo link_tag('js/jgrowl/jquery.jgrowl.css') ?>

<script type="text/javascript" language="javascript">
    $(function () {
        $('#form-rate :radio.star').rating();
    });
</script>

<?php
//MESSAGE
if (isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="user-review">
    <h1><?php echo $title; ?></h1>
    <div id='user-review-content'>
        <div id='user-review-navigation'>
            <div id='user-review-navigation-each'>
                <a href="<?php echo $user_review_like; ?>" >Like</a> 
            </div>
            <div id='user-review-navigation-separater'>|</div> 
            <div id='user-review-navigation-each'>
                <a href="<?php echo $user_review_comment; ?>" >Comment</a>
            </div>
            <div id='user-review-navigation-separater'>|</div> 
            <div id='user-review-navigation-each'>
                <a href="<?php echo $user_review_rating; ?>" >Rating</a>
            </div>
        </div>
        <?php
        foreach ($review_list as $row)
        {
            $act_refer_type = $row['act_refer_type'];
            $advertise_id = $row['act_refer_id'];
            $sub_category_id = $row['sub_category_id'];
            $merchant_id = $row['merchant_id'];
            $merchant_name = $this->m_custom->display_users($merchant_id);
            $merchant_dashboard_url = base_url() . "all/merchant-dashboard/" . generate_slug($merchant_name) . '//' . $merchant_id;
            $advertise_detail_url = base_url() . "all/advertise/" . $advertise_id;
            $average_rating = $this->m_custom->activity_rating_average($advertise_id, 'adv');
            $rating_count = $this->m_custom->activity_rating_count($advertise_id, 'adv');
            $user_rating = $this->m_custom->activity_rating_this_user($advertise_id, $act_refer_type);
            ?>
            <div id='advertise-list-box'>
                <?php 
                if ($this->router->fetch_method() != 'merchant_dashboard')
                { 
                    ?>
                    <div id="advertise-list-title1">
                        <a href='<?php echo $merchant_dashboard_url ?>'> <?php echo $merchant_name ?></a>
                    </div>
                    <?php
                } 
                ?>
                <div id="advertise-list-photo">
                    <div id="advertise-list-photo-box">
                        <a href='<?php echo $advertise_detail_url ?>'><img src='<?php echo base_url($this->config->item('album_merchant') . $row['image']) ?>'></a>
                    </div>
                </div>
                <div id="advertise-list-title2">
                    <a href='<?php echo $advertise_detail_url ?>'><?php echo $row['title'] ?></a>
                </div>
                <div id="advertise-list-info">
                    <table border="0" cellpadding="4px" cellspacing="0px">
                        <tr>
                            <td>Category</td>
                            <td>:</td>
                            <td>
                                <?php echo $this->m_custom->display_category($row['sub_category_id']) ?>
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
                        <tr>
                            <td>Your Rating</td>
                            <td>:</td>
                            <td>
                                <?php
                                for ($i = 1; $i <= 5; $i++)
                                {
                                    if ($i == round($user_rating))
                                    {
                                        echo "<input class='star' type='radio' name='rating-$advertise_id' disabled='disabled' value='" . $i . "' checked='checked'/>";
                                    }
                                    else
                                    {
                                        echo "<input class='star' type='radio' name='rating-$advertise_id' disabled='disabled' value='" . $i . "'/>";
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Average Rating</td>
                            <td>:</td>
                            <td>
                                <div id="form-rate2">
                                    <?php
                                    for ($i = 1; $i <= 5; $i++)
                                    {
                                        if ($i == round($average_rating))
                                        {
                                            echo "<input class='star' type='radio' name='a-rating-$advertise_id' disabled='disabled' value='" . $i . "' checked='checked'/>";
                                        }
                                        else
                                        {
                                            echo "<input class='star' type='radio' name='a-rating-$advertise_id' disabled='disabled' value='" . $i . "'/>";
                                        }
                                    } //end of for
                                    echo $rating_count . " reviews";
                                    ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
    
<div id='advertise-list-empty-bottom-fix'></div>