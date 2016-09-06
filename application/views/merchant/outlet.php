<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="outlet-dashboard">
    <h1>Headquaters</h1>
    <div id="outlet-dashboard-content">
        <div id="outlet-dashboard-photo">
            <div id="outlet-dashboard-photo-box">
            <?php
            $merchant_slug = $this->uri->segment(3);
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
        <div id="outlet-dashboard-info">
            <div id="outlet-dashboard-info-title">
                <?php echo "<a href='".$merchant_url."'>".$company_name."</a>"; ?>
                <div class="float-fix"></div>
            </div>
            <div id="outlet-dashboard-info-address">
                <?php echo $address; ?>
            </div>
            <!--<div id="dashboard-info-outlet-address">
                <a href="<?php //echo $show_outlet ?>">Show outlet Address <i class="fa fa-map-o"></i></a>
            </div>-->
            <table border="0px" cellspacing="0px" cellpadding="5px" style="width: 100%; table-layout: fixed;">
                <colgroup style="width:120px;"></colgroup>
                <colgroup style="width:15px;"></colgroup>
                <?php if(!IsNullOrEmptyString($phone)){ ?>
                <tr>
                    <td>Phone</td>
                    <td>:</td>
                    <td><div class="text-ellipsis"><?php echo "<a href='tel:".$phone."' >".$phone."</a>"; ?></div></td>
                </tr>
                <?php } ?>
                <?php if(!IsNullOrEmptyString($facebook_url)){ ?>
                <tr>
                    <td>Facebook URL</td>
                    <td>:</td>
                    <td><div class="text-ellipsis"><?php echo "<a target='_blank' href='".display_url($facebook_url)."' >".$facebook_url."</a>"; ?></div></td>
                </tr>
                <?php } ?>
                <?php if(!IsNullOrEmptyString($website_url)){ ?>
                <tr>
                    <td>Website</td>
                    <td>:</td>
                    <td><div class="text-ellipsis"><?php echo "<a target='_blank' href='".display_url($website_url)."' >".$website_url."</a>";?></div></td>
                </tr>  
                <?php } ?>
            </table>
        </div>
        <div id="float-fix"></div>
    </div>
</div>

<div id="outlet">
    <h1>Outlet</h1>
    <div id="outlet-content">
        
        <!--SEARCH-->
        <div id="outlet-search">
            <?php echo form_open(uri_string()) ?>
            <div id="outlet-search-input"><input type="text" placeholder="Search Outlet" name="search_word"></div>
            <div id="outlet-search-submit"><button name="button_action" type="submit" value="search_branch">Search</button></div>
            <div id="outlet-search-clear"><a href='<?php echo current_url() ?>' class="a-href-button">Clear</a></div>
            <?php echo form_close() ?>
        </div>
        
        <?php
        foreach ($branch_list as $one_row)
        {
            $address = $one_row->address;
            $address_with_plus_symbol = str_replace(' ', '+', $address); 
            ?>
            <div id="outlet-info">
                <div id="outlet-info-name"><?php echo $one_row->name ?></div>
                <div id="outlet-info-address">
                    <a href="https://maps.google.com?saddr=Current+Location&daddr=<?php echo $address_with_plus_symbol ?>">
                        <?php echo $address ?>
                    </a>
                </div>
                <div id="outlet-info-tel"><?php echo "<a href='tel:".$phone."' >".$one_row->phone."</a>"; ?></div>
                <div id="outlet-info-view-map"><a href="<?php echo base_url() . $view_map_path . $one_row->branch_id ?>">View Map</a></div>
            </div>
            <?php
        }
        ?>
    </div>
</div>