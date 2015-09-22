<div id="infoMessage"><?php echo $message; ?></div>

<div id="dashboard">
    <h1>Dashboard</h1>
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
            <div id="dashboard-info-outlet-address">
                <a href="<?php echo $show_outlet ?>">Show outlet Address <i class="fa fa-map-o"></i></a>
            </div>
            <div id="dashboard-info-table">
                <table border="0px" cellspacing="0px" cellpadding="5px" style="width: 100%; table-layout: fixed;">
                    <colgroup style="width:118px;"></colgroup>
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
        </div>
        <div id="float-fix"></div>
        
        <div id="dashboard-navigation">
            <a href="<?php echo $offer_deal; ?>" >Offer deal</a> &nbsp; | &nbsp;
            <a href="<?php echo $candie_promotion; ?>" >Redemption</a> &nbsp; | &nbsp;
            <a href="<?php echo $user_picture; ?>" > User's Picture</a>
            <?php
            if (check_correct_login_type($this->config->item('group_id_user')))
            {
                ?>
                &nbsp; | &nbsp;
                <a href='<?php echo $user_upload_for_merchant ?>'> Upload Picture </a>
                <?php
            }
            ?>
        </div>
        <div id="float-fix"></div>
        
    </div>
</div>
