<?php
//CONFIG DATA
$merchant_group_id = $this->config->item('group_id_merchant');
$empty_image = $this->config->item('empty_image');
$user_profile_image_path = $this->album_user_profile;
$merchant_profile_image_path = $this->album_merchant_profile;

//URL PARAMETER
$page_type = $this->router->fetch_method();
$user_type = $this->uri->segment(3);
$user_id = $this->uri->segment(4);

//USER MAIN GROUP ID
$where_get_users = array('id' => $user_id);
$user_main_group_id = $this->albert_model->read_users($where_get_users)->row()->main_group_id;

$num_rows_user_follower_all = $query_user_follower_all->num_rows();
$num_rows_user_following_all = $query_user_following_all->num_rows();

//SPECIAL FOR MERCHANT
if($user_main_group_id == $merchant_group_id)
{
    if($page_type == 'following' && $user_type == 'user')
    {
        $page_title = str_replace("User","Merchants",$page_title);
    }
}

//PAGE
if ($page_type == 'follower')
{
    $query_user_follow = $query_user_follower;
    $num_rows_user_follow = $query_user_follower_all->num_rows();
    $num_rows_user_follow_all = $num_rows_user_follower_all;
    $num_rows_user_follow_user = $query_user_follower_user->num_rows();
    $num_rows_user_follow_merchant = $query_user_follower_merchant->num_rows();
}
elseif ($page_type == 'following')
{
    $query_user_follow = $query_user_following;
    $num_rows_user_follow = $query_user_following_all->num_rows();
    $num_rows_user_follow_all = $num_rows_user_following_all;
    $num_rows_user_follow_user = $query_user_following_user->num_rows();
    $num_rows_user_follow_merchant = $query_user_following_merchant->num_rows();
}
?>

<div id="follow">
    <h1><?php echo $page_title ?></h1>
    <div id='follow-content'>
        
        <div id="follow-left-navigation">
            <?php
            if($page_type == 'follower' && $user_type == 'user')
            {
                $follow_empty = 'No Follower';
                ?><span id='follow-left-navigation-current'>User Followers ()</span><?php
            }
            else
            {
                ?><a href="<?php echo base_url() ?>all/follower/user/<?php echo $users_id ?>">User Followers ()</a><?php
            }
            ?>
            <span id='follow-left-navigation-separator'>|</span>            
            <?php 
            //SPECIAL FOR MERCHANT
            if($user_main_group_id == $merchant_group_id)
            {
                if($page_type == 'following' && $user_type == 'user')
                {
                    $follow_empty = 'No Following';
                    ?><span id='follow-left-navigation-current'>Merchants Following ()</span><?php
                }
                else
                {
                    ?><a href="<?php echo base_url() ?>all/following/user/<?php echo $users_id ?>">Merchants Following ()</a><?php
                }
            }
            else
            {
                if($page_type == 'following' && $user_type == 'user')
                {
                    $follow_empty = 'No Following';
                    ?><span id='follow-left-navigation-current'>User Following ()</span><?php
                }
                else
                {
                    ?><a href="<?php echo base_url() ?>all/following/user/<?php echo $users_id ?>">User Following ()</a><?php
                }
            }
            ?>
            <?php
            if($user_main_group_id != $merchant_group_id)
            {
                ?>
                <span id='follow-left-navigation-separator'>|</span>
                <?php
                if($page_type == 'follower' && $user_type == 'merchant')
                {
                    $follow_empty = 'No Follower';
                    ?><span id='follow-left-navigation-current'>Merchant Followers ()</span><?php
                }
                else
                {
                    ?><a href="<?php echo base_url() ?>all/follower/merchant/<?php echo $users_id ?>">Merchant Followers ()</a><?php
                }
                ?>
                <span id='follow-left-navigation-separator'>|</span>
                <?php 
                if($page_type == 'following' && $user_type == 'merchant')
                {
                    $follow_empty = 'No Following';
                    ?><span id='follow-left-navigation-current'>Merchants Following ()</span><?php
                }
                else
                {
                    ?><a href="<?php echo base_url() ?>all/following/merchant/<?php echo $users_id ?>">Merchants Following ()</a><?php
                }
            }
            ?>
        </div>

        <div id='float-fix'></div>
        
        <?php
        if($num_rows_user_follow)
        {
            //USER FOLLOW INNER JOIN USERS ROW
            $row_user_follow = $query_user_follow->result_array();
            //USER FOLLOW INNER JOIN USERS LOOP
            foreach($row_user_follow as $user_follow)
            {
                //DATA
                $id = $user_follow['id'];
                $profile_image = $user_follow['profile_image'];
                $user_type = $user_follow['main_group_id'];
                if($user_type == 5)
                {
                    //USER
                    $name = $user_follow['first_name'] . ' ' . $user_follow['last_name'];
                    $image_path = $user_profile_image_path;
                    $dashboard_url = "user_dashboard/$id";
                }
                elseif($user_type == 3 || $user_type == 4)
                {
                    //MERCHANT
                    $name = $user_follow['company'];
                    $slug = $user_follow['slug'];
                    $image_path = $merchant_profile_image_path;
                    $dashboard_url = "merchant_dashboard/$slug";
                }
                ?>
                <div id='follow-box'>
                    <div id="follow-box-photo">
                        <div id="follow-box-photo-box">
                            <a href='<?php echo base_url() ?>all/<?php echo $dashboard_url ?>'>
                                <?php
                                if(IsNullOrEmptyString($profile_image))
                                {
                                    ?>
                                    <img src="<?php echo base_url() . $empty_image; ?>">
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <img src="<?php echo base_url() . $image_path . $profile_image ?>">
                                    <?php
                                }
                                ?>
                            </a>
                        </div>
                    </div>
                    <div id='follow-box-name'>
                        <?php echo $name ?>
                    </div>
                </div>
                <?php
            }
            ?>
            <div id="float-fix"></div>
            <div id="follow-bottom-empty-fix">&nbsp;</div>
            <?php
        }
        else
        {
            ?><div id='empty-message'><?php echo $follow_empty; ?></div><?php
        }
        ?>            
    </div>
</div>