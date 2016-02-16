<?php echo $map['js']; ?>

<div id="dashboard">
    <h1>Map</h1>
    <div id="dashboard-content">
        <div id="dashboard-photo">
            <div id="dashboard-photo-box">
            <?php    
            $merchant_slug = generate_slug($company_name);
            $merchant_url = base_url() . 'all/merchant_dashboard/' . $merchant_slug . '//' . $merchant_id;
            if(IsNullOrEmptyString($image))
            {
                ?>
                <img src="<?php echo base_url().$this->config->item('empty_image'); ?>">
                <?php
            }
            else
            {
                ?>
                <a href="<?php echo $merchant_url; ?>" ><img src="<?php echo base_url() . $image_path . $image ?>"></a>
                <?php
            }
            ?>
            </div>
        </div>
        <div id="dashboard-info">
            <div id="dashboard-info-title">
                <?php echo "<a href='".$merchant_url."'>".$company_name."</a>"; ?>
            </div>
            <div id="dashboard-info-address">
                <?php echo $address; ?>
            </div>
            <table border="0px" cellspacing="0px" cellpadding="5px" style="width: 100%; table-layout: fixed;">
                <colgroup style="width:60px;"></colgroup>
                <colgroup style="width:15px;"></colgroup>
                <tr>
                    <td>Phone</td>
                    <td>:</td>
                    <td><div class="text-ellipsis"><?php echo "<a href='tel:".$phone."' >".$phone."</a>"; ?></div></td>
                </tr>
            </table>
        </div>
        <div id="float-fix"></div>
    </div>
</div>

<div id="merchant-map">
    <div id="merchant-map-google"><?php echo $map['html']; ?></div>
    <div id="merchant-map-google-link"><?php echo anchor_popup($googlemap_url, 'Go to Google Map'); ?></div>
    <div id="float-fix"></div>
</div>