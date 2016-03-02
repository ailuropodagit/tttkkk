<?php
//CONFIG DATA
$fetch_method = $this->router->fetch_method();

//USER ID
$user_id = $this->uri->segment(3);
?>

<?php
//COMBINE WITH DASHBOARD
if ($fetch_method == 'user_dashboard')
{
    ?><div id="album-user-combine"></div><?php
}
?>

<div id="album-user">
    <div id="album-user-header">
        <div id='album-user-header-title'><?php echo $title ?></div>
        <?php
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            ?>
            <div id='album-user-header-title-upload'>
                <a href='<?php echo base_url() ?>user/main_album_change'><i class="fa fa-plus-square album-user-header-title-upload-icon"></i>Create Album</a>
            </div>
            <?php
        }
        ?>
        <div id='float-fix'></div>
        <div id='album-user-header-title-bottom-line'></div>
    </div>
    
    <?php
    //ALBUM USER NAVIGATION
    $this->load->view('all/album_user_sub_menu');
    ?>
    
    <div id="album-user-content">
        <?php
        if (!empty($album_list))
        {
            foreach ($album_list as $row)
            {
                $picture_detail_url = base_url() . "all/album_user/" . $row['user_id'] . "/" . $row['album_id'];
                $count_image = $this->m_custom->getMainAlbum_CountImage($row['album_id']);
                $latest_image = $this->m_custom->getMainAlbum_LatestImage($row['album_id']);
                $count_image_text = $count_image > 1 ? 'photos' : 'photo';
                if ($count_image > 0)
                {
                    $url_image = base_url() . $this->album_user . $latest_image[0]['image'];
                }
                else
                {
                    $url_image = base_url() . $this->config->item('empty_image');
                }
                $url_edit = base_url() . "user/main_album_change/" . $row['album_id'];
                ?>
                <div id='album-user-box'>    
                    <div id='album-user-main-title'>
                        <a href='<?php echo $picture_detail_url ?>'><?php echo $row['album_title'] ?></a> 
                    </div>
                    <a href='<?php echo $picture_detail_url ?>'>
                        <div id='album-user-box-photo-box'>
                            <img src='<?php echo $url_image ?>'>
                        </div>
                    </a>
                    <div id="album-user-info">
                        <div id="album-user-info-count">
                            <?php echo $count_image . " " . $count_image_text; ?>
                        </div>
                        <div id="album-user-info-edit">
                            <?php
                            if (check_is_login())
                            {
                                $login_id = $this->ion_auth->user()->row()->id;
                                $allowed_list = $this->m_custom->get_list_of_allow_id('main_album', 'user_id', $login_id, 'album_id');
                                if (check_correct_login_type($this->config->item('group_id_user'), $allowed_list, $row['album_id']))
                                {
                                    ?>
                                    <a href='<?php echo $url_edit ?>'><i class="fa fa-pencil-square-o"></i>Edit</a>
                                    <?php
                                }
                            }
                            ?>                            
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        else
        {
            ?><div id='empty-message'>No Picture</div><?php
        }
        ?>
    </div>
</div>
