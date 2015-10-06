<?php
//CONFIG DATA
$empty_image = $this->config->item('empty_image');
$group_id_user = $this->config->item('group_id_user');
$group_id_merchant = $this->config->item('group_id_merchant');
$group_id_supervisor = $this->config->item('group_id_supervisor');
$image_path_user_profile = $this->config->item('album_user_profile');
$image_path_merchant_profile = $this->config->item('album_merchant_profile'); 

//URL
$user_page = $this->router->fetch_method();
$user_type = $this->uri->segment(3);
$user_id = $this->uri->segment(4);

//NUM ROWS
$num_rows_user_follower = $query_user_follower->num_rows();
$num_rows_merchant_follower = $query_merchant_follower->num_rows();
$num_rows_user_following = $query_user_following->num_rows();
$num_rows_merchant_following = $query_merchant_following->num_rows();
?>

<?php
if($user_page == 'follower' && $user_type == 'user')
{
    $query_user_follow = $query_user_follower;
    $empty_message_follow = 'No user followers';
}
if($user_page == 'following' && $user_type == 'user')
{
    $query_user_follow = $query_user_following;
    $empty_message_follow = 'No user following';
}
if($user_page == 'follower' && $user_type == 'merchant')
{
    $query_user_follow = $query_merchant_follower;
    $empty_message_follow = 'No merchant follower';
}
if($user_page == 'following' && $user_type == 'merchant')
{
    $query_user_follow = $query_merchant_following;
    $empty_message_follow = 'No merchant following';
}
?>

<div id="follow">
    <h1><?php echo $page_title ?></h1>
    <div id='follow-content'>
        <!--NAVIGATION-->
        <div id="follow-navigation">
            <div id="follow-navigation-each">
                <a href="<?php echo base_url() ?>user/follower/user/<?php echo $user_id ?>">User Followers (<?php echo $num_rows_user_follower ?>)</a>
            </div>
            <div id="follow-navigation-separator">|</div>
            <div id="follow-navigation-each">
                <a href="<?php echo base_url() ?>user/following/user/<?php echo $user_id ?>">User Following (<?php echo $num_rows_user_following ?>)</a>
            </div>
            <div id="follow-navigation-separator">|</div>
            <div id="follow-navigation-each">
                <a href="<?php echo base_url() ?>user/follower/merchant/<?php echo $user_id ?>">Merchant Followers (<?php echo $num_rows_merchant_follower ?>)</a>
            </div>
            <div id="follow-navigation-separator">|</div>
            <div id="follow-navigation-each">
                <a href="<?php echo base_url() ?>user/following/merchant/<?php echo $user_id ?>">Merchant Following (<?php echo $num_rows_merchant_following ?>)</a>
            </div>
            <div id="float-fix"></div>
        </div>
    </div>    
</div>

<?php
$result_array_user_follow = $query_user_follow->result_array();
$num_rows_user_follow = $query_user_follow->num_rows();
if($num_rows_user_follow)
{
    foreach($result_array_user_follow as $user_follow)
    {
        $profile_image = $user_follow['profile_image'];
        $main_group_id = $user_follow['main_group_id'];
        //DEFINE IMAGE PATH
        if($main_group_id == $group_id_user)
        {
            //USER
            $id = $user_follow['id'];
            $name = $user_follow['first_name'] . ' ' . $user_follow['last_name'];
            $image_path = $image_path_user_profile;
            $dashboard_url = "user_dashboard/$id";
        }
        if($main_group_id == $group_id_merchant || $main_group_id == $group_id_supervisor)
        {
            //MERCHANT or SUPERVISOR
            $slug = $user_follow['slug'];
            $name = $user_follow['company'];
            $image_path = $image_path_merchant_profile;
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
}
else
{
    ?><div id='empty-message'><?php echo $empty_message_follow; ?></div><?php
}
