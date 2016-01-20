<?php
//CONFIG DATA
$page_name = $this->router->fetch_method();

//USER ID
$user_id = $this->uri->segment(3);
$album_id = $this->uri->segment(4);
$album_title = $this->m_custom->get_one_field_by_key('main_album', 'album_id', $album_id, 'album_title');
$user_name = $this->m_custom->display_users($user_id);
$user_url = base_url() . 'all/user_dashboard/' . $user_id . '#dashboard-navigation';
$title = "<a href='$user_url' style='color:#244964' >$user_name Album</a>";
if (check_correct_login_type($this->config->item('group_id_user')))
{
    $login_id = $this->ion_auth->user()->row()->id;
    if($login_id == $user_id){
        $title = 'My Album';
    }
}
?>

<div id="album-user-combine"></div>

<div id="album-user">
    <div id='album-user-title'><?php echo $title ?> - <?php echo $album_title ?></div>
    <?php
    if (check_correct_login_type($this->config->item('group_id_user')))
    {
        ?>
        <div id='album-user-title-upload'>
            <a href='<?php echo base_url() ?>user/upload_image/<?php echo $album_id ?>'><i class="fa fa-upload album-user-title-upload-icon"></i>Upload Picture</a>
        </div>
        <?php
    }
    ?>
    <div id='float-fix'></div>
    <div id='album-user-title-bottom-line'></div>
    
    <div id="album-user-content">
        <?php
        $this->load->view('all/album_user_sub_menu');
        ?>
        
        <?php        
        if(empty($album_list))
        {            
            ?><div id='empty-message'>No Picture</div><?php
        }
        else
        {
            foreach ($album_list as $row)
            {
                if ($this->router->fetch_method() == 'album_user' || $this->router->fetch_method() == 'user_dashboard')
                {
                    $picture_detail_url = base_url() . "all/user_picture/" . $row['user_album_id'] . "/" . $row['user_id'] . "/" . $album_id;
                }
                else
                {
                    $picture_detail_url = base_url() . "all/user_picture/" . $row['user_album_id'];
                }
                ?>
                <div id='album-user-box'>
                    <div id='album-user-main-title' style="display:none">
                        <a href='<?php echo $picture_detail_url ?>'><?php echo $row['title'] ?></a> 
                    </div>
                    <a href='<?php echo $picture_detail_url ?>'>
                        <div id='album-user-photo'>
                            <div id='album-user-photo-box'>
                                <img src='<?php echo base_url($this->album_user . $row['image']) ?>'>
                            </div>
                        </div>
                    </a>
                    <div id='album-user-sub-title'></div>
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
