<?php
//CONFIG DATA
$page_name = $this->router->fetch_method();

//USER ID
$user_id = $this->uri->segment(3);
?>

<div id="album-user">
    <div id='album-user-title'><?php echo $title ?></div>
    <?php
    if (check_correct_login_type($this->config->item('group_id_user')))
    {
        $fetch_method = $this->router->fetch_method();
        $merchant_slug = '';
        if($fetch_method == 'merchant_dashboard'){
            $merchant_slug = $this->uri->segment(3);
        }
        ?>
        <div id='album-user-title-upload'>
            <a href='<?php echo base_url() ?>user/upload_for_merchant/<?php echo $merchant_slug ?>'><i class="fa fa-upload album-user-title-upload-icon"></i>Upload Picture</a>
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
            ?><div id='album-user-empty'>No Picture</div><?php
        }
        else
        {
            foreach ($album_list as $row)
            {
                if ($this->router->fetch_method() == 'album_user_merchant')
                {
                    $picture_detail_url = base_url() . "all/merchant_user_picture/" . $row['merchant_user_album_id'] . "/" . $row['user_id'];
                }
                else if ($this->router->fetch_method() == 'merchant_dashboard')
                {
                    $picture_detail_url = base_url() . "all/merchant_user_picture/" . $row['merchant_user_album_id'] . "/0/" . $row['merchant_id'];
                }
                else if ($this->router->fetch_method() == 'user_dashboard')
                {
                    $picture_detail_url = base_url() . "all/merchant_user_picture/" . $row['merchant_user_album_id'] . "/" . $row['user_id'];
                }
                else
                {
                    $picture_detail_url = base_url() . "all/merchant_user_picture/" . $row['merchant_user_album_id'];
                }
                $merchant_name = $this->m_custom->display_users($row['merchant_id']);
                $merchant_dashboard_url = base_url() . "all/merchant-dashboard/" . generate_slug($merchant_name);
                ?>
                <div id='album-user-box'>
                    <div id='album-user-main-title'>
                        <a href='<?php echo $merchant_dashboard_url ?>'><?php echo $merchant_name ?></a>
                    </div>
                    <a href='<?php echo $picture_detail_url ?>'>
                        <div id='album-user-photo'>
                            <div id='album-user-photo-box'>
                                <img src='<?php echo base_url($this->album_user_merchant . $row['image']) ?>'>
                            </div>
                        </div>
                    </a>
                    <div id='album-user-sub-title' style="display:none">
                        <a href='<?php echo $picture_detail_url ?>'><?php echo $row['title'] ?></a>
                    </div>
                    <div id="album-user-info">
                        <table border="0" cellpadding="4px" cellspacing="0px">
                            <tr>
                                <td>Like</td>
                                <td>:</td>
                                <td><?php echo $this->m_custom->generate_like_list_link($row['merchant_user_album_id'], 'mua'); ?></td>
                            </tr>
                            <tr>
                                <td>Comment</td>
                                <td>:</td>
                                <td><?php echo $this->m_custom->activity_comment_count($row['merchant_user_album_id'], 'mua'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div id='album-user-upload-by'>
                        Upload by : <?php echo $this->m_custom->generate_user_link($row['user_id']); ?>
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
