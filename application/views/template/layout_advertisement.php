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
                        <table style="width: 100%; height: 100%; vertical-align: middle;">
                            <tr>
                                <td>Advertisement <br/> 280px (W) x 180px (H)</td>
                            </tr>
                        </table>
                    </div>
                    <br/>
                    <div id="layout-advertisement-right-banner-box">
                        <table style="width: 100%; height: 100%; vertical-align: middle;">
                            <tr>
                                <td>Advertisement <br/> 280px (W) x 180px (H)</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div id="float-fix"></div>
                
            </div>
            
        </div>
    </div>
</div>

<?php 
$this->load->view('template/footer');
