<!--BODY RIGHT SIDEBAR-->
<div id="body-right-sidebar">
    <ul>
        <?php
        if ($this->session->userdata('user_group_id') == $this->config->item('group_id_merchant'))
        {            
            //MERCHANT SIDEBAR MENU
            $dashboard = base_url() . 'all/merchant_dashboard/' . generate_slug($this->session->userdata('company_name'));
            ?>
            <li><a href='<?php echo $dashboard ?>' class='body-right-sidebar-bar <?php if ($this->router->fetch_method() == 'dashboard' || $this->router->fetch_method() == 'outlet' || $this->router->fetch_method() == 'map'){ echo "body-right-sidebar-bar-active"; } ?>'>Dashboard</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/profile' class='body-right-sidebar-bar <?php if ($this->router->fetch_method() == 'profile' || $this->router->fetch_method() == 'upload_ssm' || $this->router->fetch_method() == 'branch' || $this->router->fetch_method() == 'supervisor'){ echo "body-right-sidebar-bar-active"; } ?>'>Profile</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/change-password' class='body-right-sidebar-bar <?php if ($this->router->fetch_method() == 'change_password'){ echo "body-right-sidebar-bar-active"; } ?>'>Change Password</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/upload_hotdeal' class='body-right-sidebar-bar <?php if ($this->router->fetch_method() == 'upload_hotdeal'){ echo "body-right-sidebar-bar-active"; } ?>'>Hot Deal Advertise</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/candie_promotion' class='body-right-sidebar-bar <?php if ($this->router->fetch_method() == 'candie_promotion'){ echo "body-right-sidebar-bar-active"; } ?>'>Candie Promotion</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>Picture</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>User Redemption</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>Analysis Report</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>Payment</a></li>
            <?php
        }
        else if ($this->session->userdata('user_group_id') == $this->config->item('group_id_supervisor'))
        {
            //SUPERVISOR SIDEBAR MENU
            $the_row = $this->m_custom->get_parent_table_record('users', 'id', $this->session->userdata('user_id'), 'su_merchant_id', 'users', 'id');
            $dashboard = base_url() . 'all/merchant_dashboard/' . generate_slug($the_row->company);
            ?>
            <li><a href='<?php echo $dashboard ?>' class='body-right-sidebar-bar <?php if ($this->router->fetch_method() == 'dashboard'){ echo "body-right-sidebar-bar-active"; } ?>'>Dashboard</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/profile' class='body-right-sidebar-bar <?php if ($this->router->fetch_method() == 'profile'){ echo "body-right-sidebar-bar-active"; } ?>'>Profile</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/upload_hotdeal' class='body-right-sidebar-bar <?php if ($this->router->fetch_method() == 'upload_hotdeal'){ echo "body-right-sidebar-bar-active"; } ?>'>Hot Deal Advertise</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/candie_promotion' class='body-right-sidebar-bar <?php if ($this->router->fetch_method() == 'candie_promotion'){ echo "body-right-sidebar-bar-active"; } ?>'>Candie Promotion</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>Picture</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>User Redemption</a></li>
            <?php
        }
        else
        {
            //USER SIDEBAR MENU
            $dashboard = base_url() . 'all/user_dashboard/' . $this->session->userdata('user_id');
            ?>
            <li><a href='<?php echo $dashboard ?>' class='body-right-sidebar-bar <?php if ($this->router->fetch_method() == 'dashboard'){ echo "body-right-sidebar-bar-active"; } ?>'>Dashboard</a></li>
            <li><a href='<?php echo base_url(); ?>user/profile' class='body-right-sidebar-bar <?php if ($this->router->fetch_method() == 'profile'){ echo "body-right-sidebar-bar-active"; } ?>'>Profile</a></li>
            <li><a href='<?php echo base_url(); ?>user/change_password' class='body-right-sidebar-bar <?php if ($this->router->fetch_method() == 'change_password'){ echo "body-right-sidebar-bar-active"; } ?>'>Change Password</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>Follower</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>Review</a></li>
            <li><a href='<?php echo base_url(); ?>all/album_user' class='body-right-sidebar-bar <?php if ($this->router->fetch_method() == 'album_user'){ echo "body-right-sidebar-bar-active"; } ?>'>Picture</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>Candies</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>Redemption</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>Invite Friend</a></li>
            <?php
        }
        ?>
    </ul>
</div>
