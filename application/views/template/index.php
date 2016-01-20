<?php
$this->load->view('template/header');
?>

<div id='wrapper'>
    <div id='body'>
        <div id='body-content'>
            <?php
            //NORMAL PATH
            $this->load->view($page_path_name);
            //BOTTOM PATH
            if (!empty($bottom_path_name))
            {
                $this->load->view($bottom_path_name);
            }
            ?>
        </div>
    </div>
</div>

<?php 
$this->load->view('template/footer');
