<?php $this->load->view('template/header'); ?>

<div id='wrapper'>
    <div id='body'>
        <div id='body-content'>
            
            <div style="float:left">
                <?php $this->load->view($left_path_name) ?>
            </div>
            
            <div style="float:right">
                <?php $this->load->view($page_path_name) ?>
            </div>
            
            <div id="float-fix"></div>
            
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>