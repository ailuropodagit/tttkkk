<?php
$page_name = $this->router->fetch_method();
if($page_name != 'user_dashboard' && $page_name != 'merchant_dashboard')
{
    if (check_correct_login_type($this->config->item('group_id_user')))
    {
        $user_id = $this->ion_auth->user()->row()->id;
        ?>
        <div id="album-user-navigation">
            <div id="album-user-navigation">
                <div id="album-user-navigation-each">
                    <a href="<?php echo base_url() ?>user/main_album/<?php echo $user_id ?>">My Album</a>
                </div>
                <?php
                if($page_name != 'main_album_change')
                {
                    ?>
                    <div id='album-user-navigation-separater'>|</div>
                    <div id="album-user-navigation-each">
                        <a href="<?php echo base_url() ?>all/album_user_merchant/<?php echo $user_id ?>">Merchant Album</a>
                    </div>
                    <?php
                }
                ?>
                <div id="float-fix"></div>
            </div>
        </div>
        <?php
    }
}
