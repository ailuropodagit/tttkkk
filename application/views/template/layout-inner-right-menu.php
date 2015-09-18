<div id="layout-inner-right-menu">
    <ul>
        <?php
        if ($this->session->userdata('user_group_id') == $this->config->item('group_id_merchant'))
        {            
            //MERCHANT SIDEBAR MENU
            $dashboard = base_url() . 'all/merchant_dashboard/' . generate_slug($this->session->userdata('company_name'));
            $merchant_album = base_url() . 'all/album_merchant/' . generate_slug($this->session->userdata('company_name'));
            ?>
            <li><a href='<?php echo $dashboard ?>' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'merchant_dashboard' || $this->router->fetch_method() == 'merchant_outlet' || $this->router->fetch_method() == 'map'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Dashboard</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/profile' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'profile' || $this->router->fetch_method() == 'upload_ssm' || $this->router->fetch_method() == 'branch' || $this->router->fetch_method() == 'supervisor'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Profile</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/change_password' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'change_password'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Change Password</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/upload_hotdeal' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'upload_hotdeal' || $this->router->fetch_method() == 'edit_hotdeal'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Hot Deal Advertise</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/candie_promotion' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'candie_promotion'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Candie Promotion</a></li>
            <li><a href='<?php echo $merchant_album; ?>' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'album_merchant'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Picture</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/merchant_redemption_page' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'merchant_redemption_page'){ echo "layout-inner-right-menu-bar-active"; } ?>'>User Redemption</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/analysis_report' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'analysis_report'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Analysis Report</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/payment_page' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'payment_page'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Payment</a></li>
            <?php
        }
        else if ($this->session->userdata('user_group_id') == $this->config->item('group_id_supervisor'))
        {
            //SUPERVISOR SIDEBAR MENU
            $the_row = $this->m_custom->get_parent_table_record('users', 'id', $this->session->userdata('user_id'), 'su_merchant_id', 'users', 'id');
            $dashboard = base_url() . 'all/merchant_dashboard/' . generate_slug($the_row->company);
            $merchant_album = base_url() . 'all/album_merchant/' . generate_slug($the_row->company);
            ?>
            <li><a href='<?php echo $dashboard; ?>' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'merchant_dashboard' || $this->router->fetch_method() == 'merchant_outlet' || $this->router->fetch_method() == 'map'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Dashboard</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/profile' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'profile'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Profile</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/upload_hotdeal' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'upload_hotdeal' || $this->router->fetch_method() == 'edit_hotdeal'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Hot Deal Advertise</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/candie_promotion' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'candie_promotion'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Candie Promotion</a></li>
            <li><a href='<?php echo $merchant_album; ?>' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'album_merchant'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Picture</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/merchant_redemption_page' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'merchant_redemption_page'){ echo "layout-inner-right-menu-bar-active"; } ?>'>User Redemption</a></li>
            <?php
        }
        else
        {
            //USER SIDEBAR MENU
            $login_user_id = $this->session->userdata('user_id');
            $dashboard = base_url() . 'all/user_dashboard/' . $login_user_id;
            $user_review = base_url() . 'all/user_review/' . $login_user_id;   
            $user_picture = base_url() . 'all/album_user/' . $login_user_id;  
            $user_candie = base_url() . 'user/candie_page'; 
            ?>
            <li><a href='<?php echo $dashboard; ?>' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'user_dashboard'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Dashboard</a></li>
            <li><a href='<?php echo base_url(); ?>user/profile' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'profile'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Profile</a></li>
            <li><a href='<?php echo base_url(); ?>user/change_password' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'change_password'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Change Password</a></li>
            <li><a href='#' class='layout-inner-right-menu-bar'>Follower</a></li>
            <li><a href='<?php echo $user_review; ?>' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'user_review'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Review</a></li>
            <li><a href='<?php echo $user_picture; ?>' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'album_user'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Picture</a></li>
            <li><a href='<?php echo $user_candie; ?>' class='layout-inner-right-menu-bar <?php if ($this->router->fetch_method() == 'album_user'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Candies (<?php echo $this->m_user->candie_check_balance($login_user_id); ?>)</a></li>
            <li><a href='#' class='layout-inner-right-menu-bar'>Redemption</a></li>
            <li><a href='#' class='layout-inner-right-menu-bar'>Invite Friend</a></li>
            <?php
        }
        ?>
    </ul>
</div>
