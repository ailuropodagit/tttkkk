<div id="infoMessage"><?php echo $message; ?></div>

<img src="<?php echo base_url($logo_url); ?>" id='header-logo-img'>

<div id="success-message-title">
    <?php echo $company_name; ?>
</div>
<div id="success-message-subtitle">
    <?php echo $address; ?>
</div>
<div id="success-message-paragraph">
    <?php echo 'Phone: ' . $phone; ?>
</div>
<div id="success-message-link">
    <?php echo anchor($show_outlet, 'Show outlet Address >>'); ?>
</div>
<div id="success-message-paragraph">
    <?php echo 'Website: ' . anchor_popup($website_url, $website_url); ?>
</div>
<div id="success-message-paragraph">
    <?php echo 'Facebook URL: ' . anchor_popup($facebook_url, $facebook_url); ?>
</div>