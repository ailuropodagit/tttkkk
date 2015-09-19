<div id="album-user-merchant">
    <h1><?php echo $title ?></h1>
    <div id="album-user-merchant-content">
        
        <?php
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            $user_id = $this->ion_auth->user()->row()->id;

            echo "<a href='" . base_url() . "all/album_user_merchant/" . $user_id . "'>Merchant Album</a><br/>";
            echo "<a href='" . base_url() . "user/upload_image'>Upload</a><br/>";
        }
        ?>
        
        <?php        
        if(empty($album_list))
        {
            if ($this->router->fetch_method() == 'album_user')
            {
                $empty_data_message = "No user's pictures in the moment";
            }
            
            ?><div id='album-user-merchant-empty-message'><?php echo $empty_data_message ?></div><?php
        }
        else
        {
            foreach ($album_list as $row)
            {
                if ($this->router->fetch_method() == 'album_user')
                {
                    $picture_detail_url = base_url() . "all/user_picture/" . $row['user_album_id'] . "/" . $row['user_id'];
                }
                else
                {
                    $picture_detail_url = base_url() . "all/user_picture/" . $row['user_album_id'];
                }
                ?>
                <div id='album-user-merchant-box'>
                    <div id='album-user-merchant-main-title'>
                        <a href='<?php echo $picture_detail_url ?>'><?php echo $row['title'] ?></a>
                    </div>
                    <a href='<?php echo $picture_detail_url ?>'>
                        <div id='album-user-merchant-photo'>
                            <div id='album-user-merchant-photo-box'>
                                <img src='<?php echo base_url($this->album_user . $row['image']) ?>'>
                            </div>
                        </div>
                    </a>
                    <div id='album-user-merchant-sub-title'>
                        
                    </div>
                    <div id="album-user-merchant-info">
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
                    <div id='album-user-merchant-upload-by'>
                        Upload by : <?php echo $this->m_custom->generate_user_link($row['user_id']); ?>
                    </div>
                </div>
                <?php
            }
            ?>
            <div id='float-fix'></div>
            <div id='album-user-merchant-bottom-empty-fix'>&nbsp;</div>
            <?php
        }
        ?>
    </div>
</div>