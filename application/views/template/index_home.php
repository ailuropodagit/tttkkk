<?php
$this->load->view('template/header');
?>

<div id="index-home">
    <?php
    $this->load->view($page_path_name) 
    ?>
</div>

<?php 
$this->load->view('template/footer');
