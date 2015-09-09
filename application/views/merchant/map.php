<?php echo $map['js']; ?>

<div id="dashboard">
    <h1>Map</h1>
    <div id="dashboard-content">
        <div id="dashboard-photo">
            <?php            
            if(IsNullOrEmptyString($image))
            {
                ?>
                <img src="<?php echo base_url().$this->config->item('empty_image'); ?>">
                <?php
            }
            else
            {
                ?>
                <img src="<?php echo base_url() . $image_path . $image ?>">
                <?php
            }
            ?>
        </div>
        <div id="dashboard-info">
            <div id="dashboard-info-title">
                <?php echo $company_name; ?>
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