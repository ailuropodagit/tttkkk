<div id="infoMessage"><?php echo $message; ?></div>

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
<div id="success-message-paragraph">
    <?php echo 'Website: ' . anchor_popup($website_url, $website_url); ?>
</div>
<div id="success-message-paragraph">
    <?php echo 'Facebook URL: ' . anchor_popup($facebook_url, $facebook_url); ?>
</div>
<div id="search-content-box-content">
    <div id="search-box-block1">
        <input type="text" placeholder="Search Location">
        <span id="search-icon"><i class="fa fa-search"></i></span>
    </div>
    <div id="search-box-block3">
        <input type='submit' value='Search'>
    </div>
</div>
<div id="float-fix"></div>
<div style="margin: 0px 0px 20px 0px;">
    <?php
    foreach ($branch_list as $one_row) {
        $this->table->add_row(array($one_row->name, $one_row->address, $one_row->phone, anchor_popup(base_url() . 'merchant/map/' . $one_row->branch_id, 'View Map')));
    }
    echo $this->table->generate();
    ?>
</div>