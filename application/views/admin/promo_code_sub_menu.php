<div id="candie-navigation">
    <?php if ($this->m_admin->check_is_any_admin(77))
    { ?>
    <div id='candie-navigation-each'><a href="<?php echo base_url() . "admin/promo_code_management"; ?>" >Manage Special Promo Code</a></div>
    <div id='candie-navigation-each-separator'>|</div>
    <div id='candie-navigation-each'><a href="<?php echo base_url() . "admin/promo_code_management_user"; ?>" >Manage User Promo Code</a></div>
    <div id='candie-navigation-each-separator'>|</div>
    <div id='candie-navigation-each'><a href="<?php echo base_url() . "admin/promo_code_management_merchant"; ?>" >Manage Merchant Promo Code</a></div>
    <?php } ?>
</div>
<div id="float-fix"></div><br/><br/>