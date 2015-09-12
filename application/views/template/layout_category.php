<?php
// HEADER
$this->load->view('template/header');
?>

<div id='wrapper'>
    <div id='body'>
        <div id='body-content'>
            
            <!--LAYOUT CATEGORY-->
            <div id="layout-category">
                
                <!--LAYOUT CATEGORY LEFT-->
                <div id="layout-category-left">
                    <?php
                    //PRESET VAR
                    $tab_counter = 0;
                    //GET CURRENT CATEGORY ID
                    $page_category_id = $this->uri->segment('3');
                    //GET MAIN CATEGORY
                    $main_category_object = $this->m_custom->getCategory();
                    //MAIN CATEGORY
                    foreach ($main_category_object as $main_category)
                    {
                        //DATA
                        $main_category_id = $main_category->category_id;
                        $main_category_label = $main_category->category_label;
                        ?>

                        <div id="layout-category-left-label">
                            <?php echo $main_category_label ?>
                        </div>

                        <?php 
                        //GET SUB CATEGORY
                        $subcat_list = $this->m_custom->getSubCategory($main_category_id); 
                        //SUB CATEGORY
                        foreach ($subcat_list as $t_subcat)
                        {
                            //DATA
                            $sub_category_id = $t_subcat->category_id;
                            $sub_category_label = $t_subcat->category_label;
                            //NAVIGATE TO
                            if ($this->router->fetch_method() == 'promotion_list')
                            {
                                //PROMOTION LIST
                                $navigate_to = base_url() . "all/promotion-list/" . $sub_category_id . "/" . $tab_counter;
                            }
                            if ($this->router->fetch_method() == 'hotdeal_list')
                            {
                                //HOTDEAL LIST
                                $navigate_to = base_url()."all/hotdeal-list/" . $sub_category_id . "/" . $tab_counter;
                            }
                            ?>                    
                            <div id="layout-category-left-nav" class="<?php if ($page_category_id == $sub_category_id) { echo 'layout-category-left-nav-active'; } ?>">
                                <a href="<?php echo $navigate_to ?>">
                                    <span id="layout-category-left-nav-icon"><i class="fa fa-caret-right"></i></span>
                                    <?php echo $sub_category_label ?>
                                </a>                        
                            </div>
                            <?php 
                        }
                        //COUNTER ++
                        $tab_counter = $tab_counter + 1;
                    }
                    ?>
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
    </div>
</div>

<?php
//FOOTER
$this->load->view('template/footer');
