<?php $this->load->view('template/header'); ?>

<div id='wrapper'>
    <div id='body'>
        <div id='body-content'>
            
            <div id="layout-left-category">
                <div id="layout-left-category">
                    <?php $this->load->view($left_path_name) ?>    
                </div>
            </div>
            
            <div id="layout-left-category-right">
                <div id="layout-left-category-right-content">
                    <?php $this->load->view($page_path_name) ?>
                </div>
            </div>
            
            <div id="float-fix"></div>
                                    
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
