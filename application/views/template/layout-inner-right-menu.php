<div id="layout-inner-right-menu">
    <ul>
        <?php
        if ($this->session->userdata('user_group_id') == $this->config->item('group_id_merchant'))
        {            
            //MERCHANT SIDEBAR MENU
            $login_user_id = $this->session->userdata('user_id');
            $dashboard = base_url() . 'all/merchant_dashboard/' . generate_slug($this->session->userdata('company_name'));
            $album_merchant = base_url() . 'all/album_merchant/' . generate_slug($this->session->userdata('company_name'));
            $album_redemption = base_url() . 'all/album_redemption/' . generate_slug($this->session->userdata('company_name'));
            $fetch_method = $this->router->fetch_method();
            $merchant_balance = $this->m_merchant->merchant_check_balance($login_user_id);
            if($merchant_balance<$this->config->item('merchant_minimum_balance')){
                $merchant_balance_text = '<span style="color:red" >(RM '.$merchant_balance . ')</span>';
            }else{
                $merchant_balance_text = '<span style="color:green" >(RM '.$merchant_balance . ')</span>';
            }
            //FOLLOWER COUNT
            $where_user_follower = array('following_main_id' => $login_user_id);
            $follower_num_rows = $this->albert_model->read_follower($where_user_follower)->num_rows();
            ?>
            <li><a href='<?php echo $dashboard ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'merchant_dashboard' || $fetch_method == 'merchant_outlet' || $fetch_method == 'map'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Dashboard</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/profile' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'profile' || $fetch_method == 'upload_ssm' || $fetch_method == 'branch' || $fetch_method == 'supervisor'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Profile</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/change_password' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'change_password'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Change Password</a></li>
            <li><a href='<?php echo base_url(); ?>all/notification' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'notification'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Notification (<?php echo $this->m_custom->notification_count($login_user_id); ?> new)</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/follower/user/<?php echo $login_user_id ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'follower' || $fetch_method == 'following'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Follower (<?php echo $follower_num_rows ?>)</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/upload_hotdeal' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'upload_hotdeal' || $fetch_method == 'edit_hotdeal'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Hot Deal Advertise</a></li>
            <li><a href='<?php echo $album_merchant; ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'album_merchant'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Hot Deal Advertise's Album</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/candie_promotion' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'candie_promotion'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Candie Voucher</a></li>
            <li><a href='<?php echo $album_redemption; ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'album_redemption'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Candie Voucher's Album</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/merchant_redemption_page' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'merchant_redemption_page'){ echo "layout-inner-right-menu-bar-active"; } ?>'>User's Redemption</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/analysis_report' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'analysis_report'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Insights</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/payment_page' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'payment_page'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Payment <?php echo $merchant_balance_text; ?></a></li>
            <?php
        }
        else if ($this->session->userdata('user_group_id') == $this->config->item('group_id_supervisor'))
        {
            //SUPERVISOR SIDEBAR MENU
            $login_user_id = $this->session->userdata('user_id');
            $the_row = $this->m_custom->get_parent_table_record('users', 'id', $this->session->userdata('user_id'), 'su_merchant_id', 'users', 'id');
            $dashboard = base_url() . 'all/merchant_dashboard/' . generate_slug($the_row->company);
            $album_merchant = base_url() . 'all/album_merchant/' . generate_slug($the_row->company);
            $album_redemption = base_url() . 'all/album_redemption/' . generate_slug($the_row->company);
            $merchant_id = $this->ion_auth->user()->row()->su_merchant_id;
            $fetch_method = $this->router->fetch_method();
            //FOLLOWER COUNT
            $where_read_user = array('id'=>$login_user_id);
            $login_main_merchant_id = $this->albert_model->read_users($where_read_user)->row()->su_merchant_id;
            $where_user_follower = array('following_main_id' => $login_main_merchant_id);
            $follower_num_rows = $this->albert_model->read_follower($where_user_follower)->num_rows();
            ?>
            <li><a href='<?php echo $dashboard; ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'merchant_dashboard' || $fetch_method == 'merchant_outlet' || $fetch_method == 'map'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Dashboard</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/profile' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'profile'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Profile</a></li>
            <li><a href='<?php echo base_url(); ?>all/notification' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'notification'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Notification (<?php echo $this->m_custom->notification_count($merchant_id); ?> new)</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/follower/user/<?php echo $login_user_id ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'follower' || $fetch_method == 'following'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Follower (<?php echo $follower_num_rows ?>)</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/upload_hotdeal' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'upload_hotdeal' || $fetch_method == 'edit_hotdeal'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Hot Deal Advertise</a></li>
            <li><a href='<?php echo $album_merchant; ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'album_merchant'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Hot Deal Advertise's Album</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/candie_promotion' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'candie_promotion'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Candie Voucher</a></li>
            <li><a href='<?php echo $album_redemption; ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'album_redemption'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Candie Voucher's Album</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/merchant_redemption_page' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'merchant_redemption_page'){ echo "layout-inner-right-menu-bar-active"; } ?>'>User's Redemption</a></li>
            <?php
        }
        else
        {
            //USER SIDEBAR MENU
            $login_user_id = $this->session->userdata('user_id');
            $dashboard = base_url() . 'all/user_dashboard/' . $login_user_id;
            $review_merchant = base_url() . 'user/review_merchant';   
            $user_picture = base_url() . 'all/album_user/' . $login_user_id;  
            $user_candie = base_url() . 'user/candie_page'; 
            $user_redemption = base_url() . 'user/redemption/' . $this->config->item('voucher_active');  
            $fetch_method = $this->router->fetch_method();
            //FOLLOWER COUNT
            $where_user_follower = array('following_main_id' => $login_user_id);
            $follower_num_rows = $this->albert_model->read_follower($where_user_follower)->num_rows();
            ?>
            <li><a href='<?php echo $dashboard; ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'user_dashboard'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Dashboard</a></li>
            <li><a href='<?php echo base_url(); ?>user/profile' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'profile'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Profile</a></li>
            <li><a href='<?php echo base_url(); ?>user/change_password' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'change_password'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Change Password</a></li>
            <li><a href='<?php echo base_url(); ?>all/notification' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'notification'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Notification (<?php echo $this->m_custom->notification_count($login_user_id); ?> new)</a></li>
            <li><a href='<?php echo base_url(); ?>user/follower/user/<?php echo $login_user_id ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'follower' || $fetch_method == 'following'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Follower (<?php echo $follower_num_rows ?>)</a></li>
            <li><a href='<?php echo $review_merchant; ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'review_merchant'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Reviews</a></li>
            <li><a href='<?php echo $user_picture; ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'album_user' || $fetch_method == 'upload_image' || $fetch_method == 'upload_for_merchant' || $fetch_method == 'album_user_merchant'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Album</a></li>
            <li><a href='<?php echo $user_candie; ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'candie_page'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Candies (<?php echo $this->m_user->candie_check_balance($login_user_id); ?>)</a></li>
            <li><a href='<?php echo $user_redemption; ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'redemption'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Vouchers</a></li>
            <li><a href='<?php echo base_url() ?>user/invite_friend' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'invite_friend'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Invite Friend</a></li>
            <li><a href='<?php echo base_url(); ?>user/balance_page' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'balance_page'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Balance (<?php echo 'RM '.$this->m_user->user_check_balance($login_user_id); ?>)</a></li>
            <?php
        }
        ?>
    </ul>
</div>
