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
        <?php 
        if ($this->router->fetch_method() == 'review_merchant')
        { 
            ?>
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
                <div id='float-fix'></div>
            </div>
            <div id='user-review-category-list'>
                <?php
                foreach ($category_list as $cat_row)
                {
                    echo $cat_row;
                }
                ?>
            </div>
            <?php 
        }
        ?>
        
        <?php
        if ($review_list != null)
        {
            //var_dump($review_list);
            foreach ($review_list as $row)
            {
                $merchant_id = $row['id'];
                $merchant_name = $row['company'];
                $merchant_dashboard_url = $row['merchant_dashboard_url'];
                $average_rating = $this->m_custom->merchant_rating_average($merchant_id, 'adv');
                $rating_count = $this->m_custom->merchant_rating_average($merchant_id, 'adv', 1);
                ?>
                <div id='advertise-list-box'>
                    <div id="advertise-list-title1">
                        <a href='<?php echo $merchant_dashboard_url ?>'> <?php echo $merchant_name ?></a>
                    </div>
                    <div id="advertise-list-photo">
                        <div id="advertise-list-photo-box">
                            <a href='<?php echo $merchant_dashboard_url ?>'><img src='<?php echo base_url($this->config->item('album_merchant_profile') . $row['profile_image']) ?>'></a>
                        </div>
                    </div>
                    <div id="advertise-list-info">
                        <table border="0" cellpadding="4px" cellspacing="0px">
                            <tr valign='top'>
                                <td>Category</td>
                                <td>:</td>
                                <td><?php echo $row['me_category_name'] ?></td>
                            </tr>
                            <tr valign='top'>
                                <td>Like</td>
                                <td>:</td>
                                <td><?php echo $this->m_custom->merchant_like_count($merchant_id, 'adv', 1); ?></td>
                            </tr>
                            <tr valign='top'>
                                <td>Comment</td>
                                <td>:</td>
                                <td><?php echo $this->m_custom->merchant_comment_count($merchant_id, 'adv', 1); ?></td>
                            </tr>
                            <tr valign='top'>
                                <td>Picture</td>
                                <td>:</td>
                                <td><a href='<?php echo $merchant_dashboard_url . "/picture"; ?>'><?php echo $this->m_custom->merchant_picture_count($merchant_id, 1); ?></a></td>
                            </tr>
                            <tr valign='top'>
                                <td>Average Rating</td>
                                <td>:</td>
                                <td>
                                    <?php
                                    for ($i = 1; $i <= 5; $i++)
                                    {
                                        if ($i == round($average_rating))
                                        {
                                            echo "<input class='star' type='radio' name='a-rating-$merchant_id' disabled='disabled' value='" . $i . "' checked='checked'/>";
                                        }
                                        else
                                        {
                                            echo "<input class='star' type='radio' name='a-rating-$merchant_id' disabled='disabled' value='" . $i . "'/>";
                                        }
                                    }
                                    ?>
                                    <div id='float-fix'></div>
                                    <?php echo $rating_count ?> reviews
                                </td>
                            </tr>
                            <tr valign='top'>
                                <td>Share</td>
                                <td>:</td>
                                <td>
                                    <span id="user-review-share-facebook">
                                        <i class="fa fa-facebook-square"></i>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>
    
<div id="advertise-list-empty-bottom-fix"></div>