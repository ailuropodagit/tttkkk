<?php
//CONFIG DATA
$empty_image = $this->config->item('empty_image');
$image_path = $this->album_user_profile;
?>

<div id="follow">
    <h1><?php echo $page_title ?></h1>
    <div id='follow-content'>
        
        <div id="follow-navigation">
            <?php
            if($this->router->fetch_method() == 'follower')
            {
                $follow_empty = 'No Follower';
                ?><a href="#">Follower</a><?php
            }
            else
            {
                ?><a href="<?php echo base_url() ?>user/follower">Follower</a><?php
            }
            ?>
            &nbsp;|&nbsp;
            <?php 
            if($this->router->fetch_method() == 'following')
            {
                $follow_empty = 'No Following';
                ?><a href='#'>Following</a><?php
            }
            else
            {
                ?><a href="<?php echo base_url() ?>user/following">Following</a><?php
            }
            ?>
        </div>
        
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
                $name = $user_follow['first_name'] . ' ' . $user_follow['last_name'];
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