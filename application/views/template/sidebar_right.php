<!--BODY RIGHT SIDEBAR-->
<div id="body-right-sidebar">
    <ul>
        <?php if ($this->session->userdata('user_group_id') == $this->config->item('group_id_merchant')) {
            
            //DASHBOARD
            $dashboard = base_url() . 'merchant/dashboard/' . generate_slug($this->session->userdata('company_name'));
            ?>
        
            <li><a href='<?php echo $dashboard ?>' class='body-right-sidebar-bar <?php if($this->router->fetch_method() == 'dashboard'){ echo "body-right-sidebar-bar-active"; } ?>'>Dashboard</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/profile' class='body-right-sidebar-bar <?php if($this->router->fetch_method() == 'profile'){ echo "body-right-sidebar-bar-active"; } ?>'>Profile</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/change_password' class='body-right-sidebar-bar <?php if($this->router->fetch_method() == 'change_password'){ echo "body-right-sidebar-bar-active"; } ?>'>Password</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/upload_hotdeal' class='body-right-sidebar-bar <?php if($this->router->fetch_method() == 'upload_hotdeal'){ echo "body-right-sidebar-bar-active"; } ?>'>Hot Deal Advertise</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/candie_promotion' class='body-right-sidebar-bar <?php if($this->router->fetch_method() == 'candie_promotion'){ echo "body-right-sidebar-bar-active"; } ?>'>Candie Promotion</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>Picture</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>User Redemption</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>Analysis Report</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>Payment</a></li>

            <?php
            
        } else if ($this->session->userdata('user_group_id') == $this->config->item('group_id_supervisor')) {
            
            //DASHBOARD
            $the_row = $this->m_custom->get_parent_table_record('users', 'id', $this->session->userdata('user_id'), 'su_merchant_id', 'users', 'id');
            $dashboard = base_url() . 'merchant/dashboard/' . generate_slug($the_row->company);
            ?>

            <li><a href='<?php echo $dashboard ?>' class='body-right-sidebar-bar <?php if($this->router->fetch_method() == 'dashboard'){ echo "body-right-sidebar-bar-active"; } ?>'>Dashboard</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/profile' class='body-right-sidebar-bar <?php if($this->router->fetch_method() == 'profile'){ echo "body-right-sidebar-bar-active"; } ?>'>Profile</a></li>
            <li><a href='<?php echo base_url(); ?>merchant/upload_hotdeal' class='body-right-sidebar-bar <?php if($this->router->fetch_method() == 'upload_hotdeal'){ echo "body-right-sidebar-bar-active"; } ?>'>Hot Deal Advertise</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>Candie Promotion</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>Picture</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>User Redemption</a></li>
            
            <?php
            
        } else {
            
            //DASHBOARD
            $dashboard = base_url() . 'user/dashboard/' . generate_slug($this->session->userdata('user_id'));
            ?>
            
            <li><a href='<?php echo $dashboard ?>' class='body-right-sidebar-bar <?php if($this->router->fetch_method() == 'dashboard'){ echo "body-right-sidebar-bar-active"; } ?>'>Dashboard</a></li>
            <li><a href='<?php echo base_url(); ?>user/profile' class='body-right-sidebar-bar <?php if($this->router->fetch_method() == 'profile'){ echo "body-right-sidebar-bar-active"; } ?>'>Profile</a></li>
            <li><a href='<?php echo base_url(); ?>user/change_password' class='body-right-sidebar-bar <?php if($this->router->fetch_method() == 'change_password'){ echo "body-right-sidebar-bar-active"; } ?>'>Password</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>Hot Deal Advertise?</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>Candie Promotion?</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>Picture</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>User Redemption?</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>Analysis Report?</a></li>
            <li><a href='#' class='body-right-sidebar-bar'>Payment?</a></li>
            
            <?php
        }
        ?>
    </ul>
</div>