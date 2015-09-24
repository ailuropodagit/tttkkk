<?php
//CONFIG DATA
$empty_image = $this->config->item('empty_image');

//URL PARAMETER
$page_type = $this->router->fetch_method();
$user_type = $this->uri->segment(3);
?>

<div id="follow">
    <h1><?php echo $page_title ?></h1>
    <div id='follow-content'>
        
        <div id="follow-left-navigation">
            <?php
            if($page_type == 'follower')
            {
                $follow_empty = 'No Follower';
                ?><span id='follow-left-navigation-current'>Follower</span><?php
            }
            else
            {
                ?><a href="<?php echo base_url() ?>all/follower/<?php echo $user_type ?>/<?php echo $users_id ?>">Follower</a><?php
            }
            ?>
            <span id='follow-left-navigation-separator'>|</span>
            <?php 
            if($page_type == 'following')
            {
                $follow_empty = 'No Following';
                ?><span id='follow-left-navigation-current'>Following</span><?php
            }
            else
            {
                ?><a href="<?php echo base_url() ?>all/following/<?php echo $user_type ?>/<?php echo $users_id ?>">Following</a><?php
            }
            ?>
        </div>
        <div id='follow-right-navigation'>
            <?php            
            if($user_type == 'all')
            {
                ?><span id='follow-left-navigation-current'>All</span><?php
            }
            else
            {
                ?><a href="<?php echo base_url() ?>all/<?php echo $page_type ?>/all/<?php echo $users_id ?>">All</a><?php
            }
            ?>
            <span id='follow-left-navigation-separator'>|</span>
            <?php            
            if($user_type == 'user')
            {
                ?><span id='follow-left-navigation-current'>User</span><?php
            }
            else
            {
                ?><a href="<?php echo base_url() ?>all/<?php echo $page_type ?>/user/<?php echo $users_id ?>">User</a><?php
            }
            ?>
            <span id='follow-left-navigation-separator'>|</span>
            <?php            
            if($user_type == 'merchant')
            {
                ?><span id='follow-left-navigation-current'>Merchant</span><?php
            }
            else
            {
                ?><a href="<?php echo base_url() ?>all/<?php echo $page_type ?>/merchant/<?php echo $users_id ?>">Merchant</a><?php
            }
            ?>
        </div>
        <div id='float-fix'></div>
        
        
        <?php
        $num_rows_user_follow = $query_user_follow->num_rows();
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
                    $name = $user_follow['first_name'] . ' ' . $user_follow['last_name'];
                    $image_path = $this->album_user_profile;
                }
                elseif($user_type == 3 || $user_type == 4)
                {
                    $name = $user_follow['company'];
                    $image_path = $this->album_merchant_profile;
                }
                ?>
                <div id='follow-box'>
                    <div id="follow-box-photo">
                        <div id="follow-box-photo-box">
                            <a href='<?php echo base_url() ?>all/user_dashboard/<?php echo $id ?>'>
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
            ?><div id='empty-message'><?php echo $follow_empty; ?></div><?php
        }
        ?>
    </div>
</div>