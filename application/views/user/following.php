<?php
//CONFIG DATA
$empty_image = $this->config->item('empty_image');
$image_path = $this->album_user_profile;
?>

<div id="following">
    <h1>Following</h1>
    <div id='following-content'>
        <?php
        //USER FOLLOW INNER JOIN USERS ROW
        $row_user_follow_inner_join_users = $query_user_follow_inner_join_users->result_array();
        //USER FOLLOW INNER JOIN USERS LOOP
        foreach($row_user_follow_inner_join_users as $user_follow_inner_join_users)
        {
            //DATA
            $id = $user_follow_inner_join_users['id'];
            $profile_image = $user_follow_inner_join_users['profile_image'];
            $name = $user_follow_inner_join_users['first_name'] . ' ' . $user_follow_inner_join_users['last_name'];
            ?>
            <div id='following-box'>
                <div id="following-box-photo">
                    <div id="following-box-photo-box">
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
                <div id='following-box-name'>
                    <?php echo $name ?>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>