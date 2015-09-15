<!--<style>
    .hot-deal-box{
        float: left;
        width:250px;
        margin:20px;
        height:400px;
        border:1px solid black;
    }
    .image-hot-deal{
        max-height: 200px;
        max-width: 200px;
    }
</style>-->

<div id="album-user-merchant">
    <h1><?php echo $title ?></h1>
    <div id="album-user-merchant-content">
        
        <?php
        if (check_correct_login_type($this->config->item('group_id_user')) && $this->router->fetch_method() == 'album_user_merchant')
        {
            $user_id = $this->ion_auth->user()->row()->id;

            echo "<a href='" . base_url() . "all/album_user/" . $user_id . "'>Picture Album</a><br/>";
            echo "<a href='" . base_url() . "user/upload_for_merchant'>Upload</a><br/>";
        }
        ?>
        
        <?php        
        if(empty($album_list))
        {
            if ($this->router->fetch_method() == 'album_user_merchant')
            {
                $empty_data_message = "No user's pictures in the moment";
            }
            else if ($this->router->fetch_method() == 'merchant_dashboard')
            {
                $empty_data_message = "No user's pictures in the moment";
            }
            
            ?><div id='album-user-merchant-empty-message'><?php echo $empty_data_message ?></div><?php
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
                else
                {
                    $picture_detail_url = base_url() . "all/merchant_user_picture/" . $row['merchant_user_album_id'];
                }
                $merchant_name = $this->m_custom->display_users($row['merchant_id']);
                $merchant_dashboard_url = base_url() . "all/merchant-dashboard/" . generate_slug($merchant_name);
                ?>
                <div id='album-user-merchant-box'>
                    <div id='album-user-merchant-main-title'>
                        <a href='<?php echo $merchant_dashboard_url ?>'><?php echo $merchant_name ?></a>
                    </div>
                    <a href='<?php echo $picture_detail_url ?>'>
                        <div id='album-user-merchant-photo'>
                            <div id='album-user-merchant-photo-box'>
                                <img src='<?php echo base_url($this->album_user_merchant . $row['image']) ?>'>
                            </div>
                        </div>
                    </a>
                    <div id='album-user-merchant-sub-title'>
                        <a href='<?php echo $picture_detail_url ?>'><?php echo $row['title'] ?></a>
                    </div>
                    <div id="album-user-merchant-info">
                        <table border="0" cellpadding="4px" cellspacing="0px">
                            <tr>
                                <td>Like</td>
                                <td>:</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <td>Comment</td>
                                <td>:</td>
                                <td>10</td>
                            </tr>
                        </table>
                    </div>
                    <div id='album-user-merchant-upload-by'>
                        Upload by : <?php echo $this->m_custom->display_users($row['user_id']) ?>
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