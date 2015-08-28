<?php 
$this->load->view('template/header'); 
?>

<div id='wrapper'>
    <div id='body'>
        <div id='body-content'>
            
            <div id="layout-advertisement">
                
                <div id="layout-advertisement-left">
                    <div id="layout-advertisement-left-content">
                        <?php
                        $this->load->view($page_path_name); 
                        ?>
                    </div>
                </div>
                
                <div id="layout-advertisement-right">
                    <div id="layout-advertisement-right-banner-box">
                        Advertisement
                    </div>
                    <br/>
                    <div id="layout-advertisement-right-banner-box">
                        Advertisement
                    </div>
                </div>
                
                <div id="float-fix"></div>
                
            </div>
            
        </div>
    </div>
</div>

<?php 
$this->load->view('template/footer');
