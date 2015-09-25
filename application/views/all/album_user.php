<?php
if($this->router->fetch_method() == 'user_dashboard')
{
    $user_id = $this->uri->segment(3);  
    ?>
    <div id="dashboard-navigation" style="margin:0px 0px 30px 0px;">
        <div id="dashboard-navigation-each"><a href="<?php echo base_url() ?>all/user_dashboard/<?php echo $user_id ?>">User Album</a></div>
        <div id="dashboard-navigation-separater">|</div>
        <div id="dashboard-navigation-each"><a href="<?php echo base_url() ?>all/user_dashboard/<?php echo $user_id ?>/merchant_album">Merchant Album</a></div>
        <?php
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            ?>
            <div id="dashboard-navigation-separater">|</div>
            <div id="dashboard-navigation-each"><a href='<?php echo base_url() ?>user/upload_image'>Upload Picture</a></div>
            <?php
        }
        ?>
        <div id="float-fix"></div>
    </div>
    <?php
}
?>

<div id="album-user">
    <h1><?php echo $title ?></h1>
    <div id="album-user-content">
        
        <?php
        if($this->router->fetch_method() != 'user_dashboard')
        {
            if (check_correct_login_type($this->config->item('group_id_user')))
            {
                $user_id = $this->ion_auth->user()->row()->id;
                ?>
                <div id="album-user-navigation">
                    <div id="album-user-navigation-upload">
                        <a href="<?php echo base_url() ?>all/album_user_merchant/<?php echo $user_id ?>">Merchant Album</a>
                    </div>
                    <div id="album-user-navigation-separater">|</div>
                    <div id="album-user-navigation-merchant-album">
                        <a href="<?php echo base_url() ?>user/upload_image">Upload Picture</a>
                    </div>
                    <div id="float-fix"></div>
                </div>
                <?php
            }
        }
        ?>
        
        <?php        
        if(empty($album_list))
        {
            if ($this->router->fetch_method() == 'album_user')
            {
                $empty_data_message = "No user's pictures";
            }
            if ($this->router->fetch_method() == 'user_dashboard')
            {
                $empty_data_message = "No user's pictures";
            }            
            ?><div id='empty-message'><?php echo $empty_data_message ?></div><?php
        }
        else
        {
            foreach ($album_list as $row)
            {
                if ($this->router->fetch_method() == 'album_user' || $this->router->fetch_method() == 'user_dashboard')
                {
                    $picture_detail_url = base_url() . "all/user_picture/" . $row['user_album_id'] . "/" . $row['user_id'];
                }
                else
                {
                    $picture_detail_url = base_url() . "all/user_picture/" . $row['user_album_id'];
                }
                ?>
                <div id='album-user-box'>
                    <div id='album-user-main-title'>
                        <a href='<?php echo $picture_detail_url ?>'><?php echo $row['title'] ?></a>
                    </div>
                    <a href='<?php echo $picture_detail_url ?>'>
                        <div id='album-user-photo'>
                            <div id='album-user-photo-box'>
                                <img src='<?php echo base_url($this->album_user . $row['image']) ?>'>
                            </div>
                        </div>
                    </a>
                    <div id='album-user-sub-title'>
                        
                    </div>
                    <div id="album-user-info">
                        <table border="0" cellpadding="4px" cellspacing="0px">
                            <tr>
                                <td>Like</td>
                                <td>:</td>
                                <td><?php echo $this->m_custom->generate_like_list_link($row['user_album_id'], 'usa'); ?></td>
                            </tr>
                            <tr>
                                <td>Comment</td>
                                <td>:</td>
                                <td><?php echo $this->m_custom->activity_comment_count($row['user_album_id'], 'usa'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <?php
                    if($this->ion_auth->user()->num_rows())
                    {
                        $logged_main_group_id = $this->ion_auth->user()->row()->main_group_id;   
                        if($logged_main_group_id == $user_id)
                        {
                            ?>
                            <div id='album-user-upload-by'>
                                Upload by : <?php echo $this->m_custom->generate_user_link($row['user_id']); ?>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <?php
            }
            ?>
            <div id='float-fix'></div>
            <div id='album-user-bottom-empty-fix'>&nbsp;</div>
            <?php
        }
        ?>
    </div>
</div>