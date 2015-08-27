<!--BODY RIGHT SIDEBAR-->
<div id="body-right-sidebar">
    <ul>
        <?php
        if($this->session->userdata('user_group_id')==$this->config->item('group_id_merchant')){
        $dashboard = base_url() . 'merchant/dashboard/' . generate_slug($this->session->userdata('company_name'));
        ?>
        <li><a href='<?php echo $dashboard ?>' class='body-right-sidebar-bar body-right-sidebar-bar-active'>Dashboard</a></li>
        <li><a href='<?php echo base_url(); ?>merchant/profile' class='body-right-sidebar-bar'>Profile</a></li>
        <li><a href='<?php echo base_url(); ?>merchant/change_password' class='body-right-sidebar-bar'>Password</a></li>
        <li><a href='' class='body-right-sidebar-bar'>Hot Deal Advertise</a></li>
        <li><a href='' class='body-right-sidebar-bar'>Candie Promotion</a></li>
        <li><a href='' class='body-right-sidebar-bar'>Picture</a></li>
        <li><a href='' class='body-right-sidebar-bar'>User Redemption</a></li>
        <li><a href='' class='body-right-sidebar-bar'>Analysis Report</a></li>
        <li><a href='' class='body-right-sidebar-bar'>Payment</a></li>
        
        
        <?php
        }else{
        $dashboard = base_url() . 'user/dashboard/' . generate_slug($this->session->userdata('user_id'));
        ?>
        <li><a href='<?php echo $dashboard ?>' class='body-right-sidebar-bar body-right-sidebar-bar-active'>Dashboard</a></li>
        <li><a href='<?php echo base_url(); ?>user/profile' class='body-right-sidebar-bar'>Profile</a></li>
        <li><a href='<?php echo base_url(); ?>user/change_password' class='body-right-sidebar-bar'>Password</a></li>
        <li><a href='' class='body-right-sidebar-bar'>Hot Deal Advertise?</a></li>
        <li><a href='' class='body-right-sidebar-bar'>Candie Promotion?</a></li>
        <li><a href='' class='body-right-sidebar-bar'>Picture</a></li>
        <li><a href='' class='body-right-sidebar-bar'>User Redemption?</a></li>
        <li><a href='' class='body-right-sidebar-bar'>Analysis Report?</a></li>
        <li><a href='' class='body-right-sidebar-bar'>Payment?</a></li>
        <?php
        }?>
    </ul>
</div>