<?php $this->load->view('template/header'); ?>

<div >
    <?php $this->load->view($left_path_name) ?>
</div>
<div id='wrapper'>
    <div id='body'>
        <div id='body-content'>
            <?php $this->load->view($page_path_name) ?>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>