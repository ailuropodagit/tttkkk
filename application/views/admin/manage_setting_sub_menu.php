<div id="candie-navigation">
            <?php if ($this->m_admin->check_is_any_admin(73))
            { ?>
            <div id='candie-navigation-each'><a href="<?php echo base_url() . "admin/manage_web_setting"; ?>" >Manage Web Setting</a></div>
            <div id='candie-navigation-each-separator'>|</div>
            <div id='candie-navigation-each'><a href="<?php echo base_url() . "admin/manage_candie_term"; ?>" >Manage Redemption Term & Condition</a></div>
            <div id='candie-navigation-each-separator'>|</div>
            <div id='candie-navigation-each'><a href="<?php echo base_url() . "admin/manage_photography"; ?>" >Manage Photography/Blogger Type</a></div>
            <?php } ?>
            <?php if ($this->m_admin->check_is_any_admin(76))
            { ?>
            <div id='candie-navigation-each-separator'>|</div>
            <div id='candie-navigation-each'><a href="<?php echo base_url() . "admin/manage_trans_config";; ?>" >Manage Transaction Config</a></div>
            <div id='candie-navigation-each-separator'>|</div>
            <div id='candie-navigation-each'><a href="<?php echo base_url() . "admin/manage_merchant_fee";; ?>" >Manage Merchant Fee Charge Type</a></div>
            <?php } ?>
        </div>
        <div id="float-fix"></div><br/><br/>