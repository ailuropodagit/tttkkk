<?php
// HEADER
$this->load->view('template/header');
?>

<div id="wrapper">

    <!--LAYOUT CATEGORY LEFT-->
    <div id="index-left-category">
        <div id="index-left-category-title">
            <span id="index-left-category-title-icon"><i class="fa fa-bars"></i></span>
            <?php 
            $fetch_method = $this->router->fetch_method();
            $left_category_title = 'Categories';
            if ($fetch_method == 'hotdeal_list')
            {
                $left_category_title = 'Food & Beverage';
            }else if($fetch_method == 'promotion_list')
            {
                $left_category_title = 'Redemption';
            }
                    ?>
            <span id="index-left-category-title-label"><?php echo $left_category_title; ?></span>
        </div>
        <div id="index-left-category-content">
            <?php
            //GET CURRENT CATEGORY ID
            $page_category_id = $this->uri->segment('3');
            
            //GET MAIN CATEGORY
            if ($fetch_method == 'promotion_list' || $fetch_method == 'redemption_list')
            {
                $main_category_object = $this->m_custom->getCategory(1);
            }
            else
            {
                $main_category_object = $this->m_custom->getCategory();
            }
            //MAIN CATEGORY
            foreach ($main_category_object as $main_category)
            {
                $main_category_id = $main_category->category_id;
                $main_category_label = $main_category->category_label;
                if ($fetch_method == 'promotion_list' || $fetch_method == 'redemption_list')
                {
                    //PROMOTION LIST
                    $main_navigate_to = base_url() . "all/promotion-list/" . $main_category_id;
                    if ($main_category->hide_special == 1)
                    {
                        $main_navigate_to = base_url() . "all/redemption-list/" . $main_category_id;
                    }
                }
                if ($fetch_method == 'hotdeal_list')
                {
                    //HOTDEAL LIST
                    $main_navigate_to = base_url() . "all/hotdeal-list/" . $main_category_id;
                }
                if ($fetch_method == 'merchant_category')
                {
                    //HOTDEAL LIST
                    $main_navigate_to = base_url() . "all/merchant-category/" . $main_category_id;
                }
                ?>
                <div id="index-left-category-label"
                    <?php if($main_category_id == $page_category_id){ echo 'class="index-left-category-label-active"'; } ?>
                    <?php if(strtolower($main_category_label) == 'food & beverage'){ echo 'class="header-mobile-navigation-food-n-beverage"'; } ?>
                    <?php if(strtolower($main_category_label) == 'keppo voucher'){ echo 'class="header-mobile-navigation-keppo-voucher"'; } ?>
                    <?php if(strtolower($main_category_label) == 'others'){ echo 'class="header-mobile-navigation-others"'; } ?>
                >
                    <!--<a href="<?php echo $main_navigate_to ?>">-->
                        <?php echo $main_category_label ?>
                        <span id="index-left-category-label-plus">+</span>
                    <!--</a>-->
                </div>
                <div id="index-left-category-label-sub-label">
                    <?php
                    //GET SUB CATEGORY
                    $subcat_list = $this->m_custom->getSubCategory($main_category_id);
                    foreach ($subcat_list as $t_subcat)
                    {
                        $sub_category_id = $t_subcat->category_id;
                        $sub_category_label = $t_subcat->category_label;
                        //NAVIGATE TO
                        if ($fetch_method == 'promotion_list' || $fetch_method == 'redemption_list')
                        {
                            //PROMOTION LIST
                            $navigate_to = base_url() . "all/promotion-list/" . $sub_category_id;
                            if ($t_subcat->hide_special == 1)
                            {
                                $navigate_to = base_url() . "all/redemption-list/" . $sub_category_id;
                            }
                        }
                        if ($fetch_method == 'hotdeal_list')
                        {
                            //HOTDEAL LIST
                            $navigate_to = base_url() . "all/hotdeal-list/" . $sub_category_id;
                        }
                        if ($fetch_method == 'merchant_category')
                        {
                            //MERCHANT LIST
                            $navigate_to = base_url() . "all/merchant-category/" . $sub_category_id;
                        }
                        ?>                    
                        <div id="index-left-category-nav" class="<?php if ($page_category_id == $sub_category_id){ echo 'index-left-category-nav-active'; } ?>">
                            <a href="<?php echo $navigate_to ?>">
                                <span id="index-left-category-nav-icon"><i class="fa fa-caret-right"></i></span>
                                <?php echo $sub_category_label ?>
                            </a>                        
                        </div>
                        <?php
                    }
                    ?>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <!--LAYOUT CATEGORY RIGHT-->
    <div id="index-left-category-right-content">
        <?php
        $this->load->view($page_path_name);
        ?>
    </div>

    <div id="float-fix"></div>
</div>

<?php
//FOOTER
$this->load->view('template/footer');
