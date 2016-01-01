<div id="candie-navigation">
            <?php 
            $noti_to_id = $this->ion_auth->user()->row()->id;
            $notification_url = base_url() . "all/notification";
            if (check_correct_login_type($this->config->item('group_id_supervisor')))
            {
                $noti_to_id = $this->ion_auth->user()->row()->su_merchant_id;
            }
            if ($this->m_admin->check_is_any_admin())
            {
                $noti_to_id = 0;
                $notification_url = base_url() . "admin/admin_dashboard";
            }
            $notification_count = $this->m_custom->notification_count($noti_to_id);
            ?>
            <div id='candie-navigation-each'><a href='<?php echo $notification_url; ?>' >Notification (<?php echo $notification_count; ?> new)</a></div>
            <?php
            if (check_correct_login_type($this->group_id_merchant) || $this->m_admin->check_is_any_admin(68))
            {
                $monitor_count = $this->m_custom->display_row_monitor(1);               
            ?>
                <div id='candie-navigation-each-separator'>|</div>
                <div id='candie-navigation-each'><a href='<?php echo base_url() . "all/monitor-remove" ?>' >Monitoring Remove Action (<?php echo $monitor_count; ?> new)</a></div>
            <?php } ?>
            <?php
            //to do todo
            if ($this->m_admin->check_is_any_admin(69))
            {
                $banner_expire_count = $this->m_admin->banner_select(1, 1);               
            ?>
                <div id='candie-navigation-each-separator'>|</div>
                <div id='candie-navigation-each'><a href='<?php echo base_url() . "admin/banner-management/1" ?>' >Banner Expired (<?php echo $banner_expire_count; ?>)</a></div>
            <?php } ?>    
            <?php
            if ($this->m_admin->check_is_any_admin(67))
            {
                $merchant_low_balance_count = $this->m_admin->merchant_low_balance_count(1);               
            ?>
                <div id='candie-navigation-each-separator'>|</div>
                <div id='candie-navigation-each'><a href='<?php echo base_url() . "admin/merchant-management/1" ?>' >Merchant Insufficient Fund (<?php echo $merchant_low_balance_count; ?>)</a></div>
            <?php } ?>    
            <?php
            if (check_correct_login_type($this->group_id_merchant) || check_correct_login_type($this->config->item('group_id_supervisor')))
            {
                $merchant_hotdeal_expired_count = $this->m_custom->getAdvertise_expired($noti_to_id, 1);               
            ?>
                <div id='candie-navigation-each-separator'>|</div>
                <div id='candie-navigation-each'><a href='<?php echo base_url() . "merchant/hotdeal-expired/1" ?>' >Merchant Hot Deal Expired (<?php echo $merchant_hotdeal_expired_count; ?>)</a></div>
            <?php } ?>   
</div>        
        <div id="float-fix"></div>
        <br/><br/>