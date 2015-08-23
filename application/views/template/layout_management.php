<?php
$this->load->view('template/header');

?>

<div id='wrapper'>
    <div id='body'>
        <div id='body-content'>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <?php foreach ($css_files as $file): ?>
                <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
            <?php endforeach; ?>
            <?php foreach ($js_files as $file): ?>
                <script src="<?php echo $file; ?>"></script>
            <?php endforeach; ?>
            <div>
                <?php echo $output; ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view('template/footer');
?>