<div id="infoMessage"><?php echo $message; ?></div>

<div id="dashboard">
    <h1>Headquaters</h1>
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
            <!--<div id="dashboard-info-outlet-address">
                <a href="<?php //echo $show_outlet ?>">Show outlet Address <i class="fa fa-map-o"></i></a>
            </div>-->
            <table border="0px" cellspacing="0px" cellpadding="5px" style="width: 100%; table-layout: fixed;">
                <colgroup style="width:120px;"></colgroup>
                <colgroup style="width:15px;"></colgroup>
                <tr>
                    <td>Phone</td>
                    <td>:</td>
                    <td><div class="text-ellipsis"><?php echo "<a href='tel:".$phone."' >".$phone."</a>"; ?></div></td>
                </tr>
                <tr>
                    <td>Website</td>
                    <td>:</td>
                    <td><div class="text-ellipsis"><?php echo anchor_popup($website_url, $website_url); ?></div></td>
                </tr>
                <tr>
                    <td>Facebook URL</td>
                    <td>:</td>
                    <td><div class="text-ellipsis"><?php echo anchor_popup($facebook_url, $facebook_url); ?></div></td>
                </tr>
            </table>
        </div>
        <div id="float-fix"></div>
    </div>
</div>

<div id="outlet">
    <h1>Outlet</h1>
    <div id="outlet-content">
        <?php echo form_open(uri_string()); ?>
        <div id="outlet-search">
            <input type="text" placeholder="Search Outlet" name="search_word" id="outlet-search-text">
            <button name="button_action" type="submit" value="search_branch" id="outlet-search-button">Search</button>
        </div>
        <?php echo form_close(); ?>
        <?php
        foreach ($branch_list as $one_row)
        {
            ?>
            <div id="outlet-info">
                <div id="outlet-info-name"><?php echo $one_row->name ?></div>
                <div id="outlet-info-address"><?php echo $one_row->address ?></div>
                <div id="outlet-info-tel"><?php echo "<a href='tel:".$phone."' >".$one_row->phone."</a>"; ?></div>
                <div id="outlet-info-view-map"><a href="<?php echo base_url() . $view_map_path . $one_row->branch_id ?>">View Map</a></div>
            </div>
            <?php
        }
        ?>
    </div>
</div>