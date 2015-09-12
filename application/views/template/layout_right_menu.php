<?php 
//HEADER
$this->load->view('template/header');
?>

<div id='wrapper'>
    <div id='body'>
        <div id='body-content'>
           
            <?php 
            //RIGHT MENU
            $this->load->view('template/layout-inner-right-menu');
            ?>           

            <!--BODY LEFT MAIN-->
            <div id="body-left-main">
                <div id="body-left-main-content">
                    
                    <?php
                    //VIEW PAGE
                    $this->load->view($page_path_name)
                    ?>
                    
                    <?php if (!empty($bottom_path_name))
                    {
                        $this->load->view($bottom_path_name);
                    }
                    ?>
                    
                </div>
            </div>
            <div id="float-fix"></div>
                                    
        </div>
    </div>
</div>

<?php 
//FOOTER
$this->load->view('template/footer');
