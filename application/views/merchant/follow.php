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
?>

<?php
if($user_page == 'follower' && $user_type == 'user')
{
    $empty_message_follow = 'No user followers';
}
if($user_page == 'following' && $user_type == 'user')
{
    $empty_message_follow = 'No merchant following';
}
?>

<div id="follow">
    <h1><?php echo $page_title ?></h1>
    <div id='follow-content'>
        <!--NAVIGATION-->
        <div id="follow-navigation">
            <div id="follow-navigation-each">
                <a href="<?php echo base_url() ?>merchant/follower/user/<?php echo $user_id ?>">User Followers (<?php echo $user_follower_count ?>)</a>
            </div>
            <div id="follow-navigation-separator">|</div>
            <div id="follow-navigation-each">
                <a href="<?php echo base_url() ?>merchant/following/user/<?php echo $user_id ?>">Merchant Following (<?php echo $user_following_count ?>)</a>
            </div>
            <div id="float-fix"></div>
        </div>
    </div>    
</div>

<?php
$result_array_follow = $query_follow->result_array();
$num_rows_follow = $query_follow->num_rows();
if($num_rows_follow)
{
    foreach($result_array_follow as $follow)
    {
        $profile_image = $follow['profile_image'];
        $main_group_id = $follow['main_group_id'];
        //DEFINE IMAGE PATH
        if($main_group_id == $group_id_user)
        {
            //USER
            $id = $follow['id'];
            $name = $follow['first_name'] . ' ' . $follow['last_name'];
            $image_path = $image_path_user_profile;
            $dashboard_url = "user_dashboard/$id";
        }
        if($main_group_id == $group_id_merchant || $main_group_id == $group_id_supervisor)
        {
            //MERCHANT or SUPERVISOR
            $slug = $follow['slug'];
            $name = $follow['company'];
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
