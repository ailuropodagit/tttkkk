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
    <style type="text/css">
        .tg  {border-collapse:collapse;border-spacing:0;border-color:#aabcfe;border:none;}
        .tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#aabcfe;color:#669;background-color:#e8edff;}
        .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#aabcfe;color:#039;background-color:#b9c9fe;}
        .tg .tg-vn4c{background-color:#D2E4FC}
    </style>
    <table class="tg">
        <tr>
            <th class="tg-031e">Name</th>
            <th class="tg-031e">Address</th>
            <th class="tg-031e">Phone</th>
            <th class="tg-031e">View Map</th>
        </tr>
        <?php
        foreach ($branch_list as $one_row) {
            echo '<tr>';
            echo '<td class="tg-vn4c">' . $one_row->name . '</td>';
            echo '<td class="tg-vn4c">' . $one_row->address . '</td>';
            echo '<td class="tg-vn4c">' . $one_row->phone . '</td>';
            echo '<td class="tg-vn4c">' . anchor_popup(base_url() . 'merchant/map/' . $one_row->branch_id, 'View Map') . '</td>';
            echo '</tr>';
        }
        ?>
    </table>

</div>