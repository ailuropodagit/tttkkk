<?php
// HEADER
$this->load->view('template/header');
?>

<div id="wrapper">
    <div id="layout-category">

        <!--LAYOUT CATEGORY LEFT-->
        <div id="layout-category-left">
            <div id="layout-category-left-title">
                <span id="layout-category-left-title-icon"><i class="fa fa-bars"></i></span>
                <span id="layout-category-left-title-label">Categories</span>
            </div>
            <div id="layout-category-left-content">
                <?php
                //GET CURRENT CATEGORY ID
                $page_category_id = $this->uri->segment('3');
                $fetch_method = $this->router->fetch_method();
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
                    ?>
                    <div id="layout-category-left-label">
                        <?php echo $main_category_label ?>
                    </div>
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
                        ?>                    
                        <div id="layout-category-left-nav" class="<?php if ($page_category_id == $sub_category_id){ echo 'layout-category-left-nav-active'; } ?>">
                            <a href="<?php echo $navigate_to ?>">
                                <span id="layout-category-left-nav-icon"><i class="fa fa-caret-right"></i></span>
                                <?php echo $sub_category_label ?>
                            </a>                        
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>

        <!--LAYOUT CATEGORY RIGHT-->
        <div id="layout-category-right">
            <?php
            //VIEW PAGE
            $this->load->view($page_path_name);
            ?>
        </div>

        <div id="float-fix"></div>
    </div>
</div>

<?php
//FOOTER
$this->load->view('template/footer');
