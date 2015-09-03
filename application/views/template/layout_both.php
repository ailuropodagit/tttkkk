<?php $this->load->view('template/header'); ?>

<div id='wrapper'>
    <div id='body'>
        <div id='body-content'>
            
            <div style="float:left;width:20%">
                <?php $this->load->view($left_path_name) ?>
            </div>
            
            <div style="float:left;width:60%">
                <?php $this->load->view($page_path_name) ?>
            </div>
            
            <div style="float:left;width:20%">
                <?php $this->load->view($right_path_name) ?>
            </div>
            
            <div id="float-fix"></div>
            
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>