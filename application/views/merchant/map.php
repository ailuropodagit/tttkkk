
<head><?php echo $map['js']; ?></head>

<img src="<?php echo base_url($logo_url); ?>" id='header-logo-img'><br/>

<div id="success-message-title">
    <?php echo $company_name; ?>
</div>
<div id="success-message-subtitle">
    <?php echo $address; ?>
</div>
<div id="success-message-paragraph">
    <?php echo 'Phone: ' . $phone; ?>
</div>
<div style="width:80%"><?php echo $map['html']; ?></div>

<div id="success-message-paragraph">
    <?php echo anchor_popup($googlemap_url, 'Google Map'); ?>
</div>


