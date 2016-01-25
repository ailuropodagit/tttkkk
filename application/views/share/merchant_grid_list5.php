<!--RATING-->
<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>
<!--JGROWL-->
<script type="text/javascript" src="<?php echo base_url() ?>js/jgrowl/jquery.jgrowl.js"></script>
<?php echo link_tag('js/jgrowl/jquery.jgrowl.css') ?>

<?php
//URI
$fetch_method = $this->router->fetch_method();
?>

<?php
//MESSAGE
if (isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="share-merchant-grid-list5">
    <div id="share-merchant-grid-list5-header">
        <div id='share-merchant-grid-list5-header-table'>
            <div id='share-merchant-grid-list5-header-table-row'>
                <div id='share-merchant-grid-list5-header-table-row-cell'>
                    <div id="share-merchant-grid-list5-header-title"><?php echo $title ?></div>
                </div>
            </div>
        </div>
        <?php 
        if ($this->router->fetch_method() == 'review_merchant')
        {
            ?>
            <div id='share-merchant-grid-list5-header-navigation'>
                <div id='share-merchant-grid-list5-header-navigation-each'>
                    <a href="<?php echo $user_review_like; ?>" >Like</a> 
                </div>
                <div id='share-merchant-grid-list5-header-navigation-separater'>|</div> 
                <div id='share-merchant-grid-list5-header-navigation-each'>
                    <a href="<?php echo $user_review_comment; ?>" >Comment</a>
                </div>
                <div id='share-merchant-grid-list5-header-navigation-separater'>|</div> 
                <div id='share-merchant-grid-list5-header-navigation-each'>
                    <a href="<?php echo $user_review_rating; ?>" >Rating</a>
                </div>
                <div id='float-fix'></div>
            </div>
            <?php 
            if( !empty($category_list))
            {
                ?>
                <div id='share-merchant-grid-list5-category-list'>
                    <?php
                    foreach ($category_list as $cat_row)
                    {
                        echo $cat_row;
                    }
                    ?>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <div id="share-merchant-grid-list5-container">
        <?php            
        if ($review_list != null)
        {
            //var_dump($review_list);
            foreach ($review_list as $row)
            {
                $merchant_id = $row['id'];
                $merchant_profile_image = $row['profile_image'];
                $merchant_name = $row['company'];
                $merchant_dashboard_url = $row['merchant_dashboard_url'];
                $average_rating = $this->m_custom->merchant_rating_average($merchant_id, 'adv');
                $rating_count = $this->m_custom->merchant_rating_average($merchant_id, 'adv', 1);
                ?>
                <div class='share-merchant-grid-list5-box'>
                    <a href='<?php echo $merchant_dashboard_url ?>'>
                        <div class="share-merchant-grid-list5-box-photo">
                            <div class="share-merchant-grid-list5-box-photo-box">
                                <?php
                                if($merchant_profile_image)
                                {
                                    ?><img src='<?php echo base_url($this->config->item('album_merchant_profile') . $merchant_profile_image) ?>'><?php
                                }
                                else
                                {
                                    echo img($this->config->item('empty_image'));
                                }
                                ?>
                            </div>
                        </div>
                        <div class='share-merchant-grid-list5-box-separator'></div>
                        <div class="share-merchant-grid-list5-box-information">
                            <div class="share-merchant-grid-list5-box-information-title">
                                <?php echo $merchant_name ?>
                            </div>
                            <div class="share-merchant-grid-list5-box-information-rating">
                                <?php
                                for ($i = 1; $i <= 5; $i++)
                                {
                                    if ($i == round($average_rating))
                                    {
                                        echo "<input class='star' type='radio' name='a-rating-m$merchant_id' disabled='disabled' value='" . $i . "' checked='checked'/>";
                                    }
                                    else
                                    {
                                        echo "<input class='star' type='radio' name='a-rating-m$merchant_id' disabled='disabled' value='" . $i . "'/>";
                                    }
                                }
                                ?>
                                <div id='float-fix'></div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php
            }
        }
        else
        {
            ?>
            <div id='share-merchant-grid-list5-empty'>
                <?php
                if ($fetch_method == 'review_merchant')
                {
                    echo 'No Review';
                }
                else if ($fetch_method == 'merchant_category' || $fetch_method == 'home_search')
                {
                    echo 'No Merchant';
                }
                else
                {

                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>