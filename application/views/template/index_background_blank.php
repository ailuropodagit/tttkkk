<?php
$this->load->view('template/header');
?>

<div id='wrapper'>
    <div id='index-background-blank'>
        <?php
        $this->load->view($page_path_name) 
        ?>
    </div>
</div>

<?php 
$this->load->view('template/footer');
