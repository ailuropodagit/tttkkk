<?php $this->load->view('template/header'); ?>

<div id='wrapper'>
    <div id='body'>
        <div id='body-content'>
           
            <?php $this->load->view('template/sidebar_right'); ?>
            
            <!--BODY RIGHT SIDEBAR-->
<!--            <div id="body-right-sidebar">
                <ul>
                    <li><a href='<?php echo base_url(); ?>merchant/dashboard/<?php echo generate_slug($this->session->userdata('company_name')) ?>' class='body-right-sidebar-bar <?php if($this->router->fetch_method() == 'dashboard'){ echo "body-right-sidebar-bar-active"; } ?>'>Dashboard</a></li>
                    <li><a href='<?php echo base_url(); ?>merchant/profile' class='body-right-sidebar-bar <?php if($this->router->fetch_method() == 'profile'){ echo "body-right-sidebar-bar-active"; } ?>'>Profile</a></li>
                    <li><a href='' class='body-right-sidebar-bar'>Password</a></li>
                    <li><a href='' class='body-right-sidebar-bar'>Hot Deal Advertise</a></li>
                    <li><a href='' class='body-right-sidebar-bar'>Candie Promotion</a></li>
                    <li><a href='' class='body-right-sidebar-bar'>Picture</a></li>
                    <li><a href='' class='body-right-sidebar-bar'>User Redemption</a></li>
                    <li><a href='' class='body-right-sidebar-bar'>Analysis Report</a></li>
                    <li><a href='' class='body-right-sidebar-bar'>Payment</a></li>
                </ul>
            </div>-->

            <!--BODY LEFT MAIN-->
            <div id="body-left-main">
                <div id="body-left-main-content">
                    <?php $this->load->view($page_path_name) ?>
                </div>
            </div>
            
            <div id="float-fix"></div>
                                    
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
