<?php
//GET MAIN CATEGORY
$main_category_object = $this->m_custom->getCategory();

//LOOP MAIN CATEGORY
foreach ($main_category_object as $main_category) 
{ 
    //GET DATA
    $main_category_id = $main_category->category_id;
    $main_category_label = $main_category->category_label;
    ?>

    <div id="category-each">
        <div id="category-each-label"><?php echo $main_category_label ?></div>
        <?php 
        //GET SUB CATEGORY
        $sub_category_object = $this->m_custom->getSubCategory($main_category_id); 
        
        //LOOP SUB CATEGORY
        foreach ($sub_category_object as $sub_category)
        { 
            //GET DATE
            $sub_category_label = $sub_category->category_label;
            ?>
            <div id="category-each-nav">
                <a href="#">
                    <span id="category-each-nav-icon"><i class="fa fa-caret-right"></i></span>
                    <?php echo $sub_category_label ?>
                </a>
            </div>
            <?php
        }
        ?>
    </div>

    <?php
}
