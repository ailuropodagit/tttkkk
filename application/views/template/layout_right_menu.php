<?php $this->load->view('template/header'); ?>

<div id='wrapper'>
    <div id='body'>
        <div id='body-content'>
           
            <?php $this->load->view('template/sidebar_right'); ?>
            
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
