<?php
//CONFIG DATA
$page_name = $this->router->fetch_method();

//USER ID
$user_id = $this->uri->segment(3);
?>

<div id="album-user-combine"></div>

<div id="album-user">
    <div id="album-user-content">
        <div id='album-user-title'><?php echo $title ?> (All)</div>
        <?php
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            ?>
            <div id='album-user-title-upload'>
                <a href='<?php echo base_url() ?>user/main_album_change'><i class="fa fa-plus-square album-user-title-upload-icon"></i>Create Album</a>
            </div>
            <?php
        }
        ?>
        <div id='float-fix'></div>
        <div id='album-user-title-bottom-line'></div>
        <?php
        $this->load->view('all/album_user_sub_menu');
        if (empty($album_list))
        {
            ?><div id='empty-message'>No Picture</div><?php
        }
        else
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
                    <a href='<?php echo $picture_detail_url ?>'>
                        <div id='album-user-photo'>
                            <div id='album-user-photo-box'>
                                <img src='<?php echo $url_image ?>'>
                            </div>
                        </div>
                    </a>
                    <div id='album-user-main-title'>
                        <a href='<?php echo $picture_detail_url ?>'><?php echo $row['album_title'] ?></a> 
                    </div>
                    <div id='album-user-sub-title'></div>
                    <div id="album-user-info">
                        <div style="float:left">
                            <?php echo $count_image . " " . $count_image_text; ?>
                        </div>
                        <div style="float:right">
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
                        <div id="float-fix"></div>
                    </div>
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
