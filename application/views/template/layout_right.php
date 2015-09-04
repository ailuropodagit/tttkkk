<?php $this->load->view('template/header'); ?>

<div id='wrapper'>
    <div id='body'>
        <div id='body-content'>
           <div style="float:left;width:22%">
            <?php $this->load->view($left_path_name) ?>    
                </div>
            <!--BODY LEFT MAIN-->
            <div id="body-left-main" style="width:75%">
                <div id="body-left-main-content">
                    <?php $this->load->view($page_path_name) ?>
                </div>
            </div>
            
            <div id="float-fix"></div>
                                    
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
