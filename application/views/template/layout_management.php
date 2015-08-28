<?php $this->load->view('template/header'); ?>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php foreach ($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach ($js_files as $file): ?>
    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

<div id='wrapper'>
    <div id='body'>
        <div id='body-content' style="min-height:600px">
            <?php $this->load->view('template/sidebar_right'); ?>

            <!--BODY LEFT MAIN-->
            <div id="body-left-main">
                <div id="body-left-main-content">
                    <?php echo $output; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="float-fix"></div>
<?php $this->load->view('template/footer'); ?>