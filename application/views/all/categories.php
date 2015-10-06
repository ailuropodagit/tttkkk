<?php
//PRESET VAR
$tab_counter = 0;

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
            //DATA
            $sub_category_id = $sub_category->category_id;
            $sub_category_label = $sub_category->category_label;
            ?>
            <div id="category-each-nav">
                <a href="<?php echo base_url() ?>all/merchant-category/<?php echo $sub_category_id ?>">
                    <span id="category-each-nav-icon"><i class="fa fa-caret-right"></i></span>
                    <?php echo $sub_category_label ?>
                </a> (<?php echo $this->m_merchant->getMerchantCount_by_subcategory($sub_category_id); ?>)
            </div>
            <?php
        }
        //COUNTER ++
        $tab_counter = $tab_counter + 1;
        ?>
    </div>

    <?php
}
?>

<div id="category-advertisement">
    <div id="category-advertisement-box">
        <table style="width: 100%; height: 100%; vertical-align: middle;">
            <tr>
                <td>Advertisement <br/> 160px (W) x 100px (H)</td>
            </tr>
        </table>
    </div>
    <div id="category-advertisement-box">
        <table style="width: 100%; height: 100%; vertical-align: middle;">
            <tr>
                <td>Advertisement <br/> 160px (W) x 100px (H)</td>
            </tr>
        </table>
    </div>
    <div id="category-advertisement-box">
        <table style="width: 100%; height: 100%; vertical-align: middle;">
            <tr>
                <td>Advertisement <br/> 160px (W) x 100px (H)</td>
            </tr>
        </table>
    </div>
    <div id="category-advertisement-box">
        <table style="width: 100%; height: 100%; vertical-align: middle;">
            <tr>
                <td>Advertisement <br/> 160px (W) x 100px (H)</td>
            </tr>
        </table>
    </div>
    <div id="category-advertisement-box">
        <table style="width: 100%; height: 100%; vertical-align: middle;">
            <tr>
                <td>Advertisement <br/> 160px (W) x 100px (H)</td>
            </tr>
        </table>
    </div>
    <div id="category-advertisement-box">
        <table style="width: 100%; height: 100%; vertical-align: middle;">
            <tr>
                <td>Advertisement <br/> 160px (W) x 100px (H)</td>
            </tr>
        </table>
    </div>
    <div id="category-advertisement-box">
        <table style="width: 100%; height: 100%; vertical-align: middle;">
            <tr>
                <td>Advertisement <br/> 160px (W) x 100px (H)</td>
            </tr>
        </table>
    </div>
    <div id="category-advertisement-box">
        <table style="width: 100%; height: 100%; vertical-align: middle;">
            <tr>
                <td>Advertisement <br/> 160px (W) x 100px (H)</td>
            </tr>
        </table>
    </div>
    <div id="category-advertisement-box">
        <table style="width: 100%; height: 100%; vertical-align: middle;">
            <tr>
                <td>Advertisement <br/> 160px (W) x 100px (H)</td>
            </tr>
        </table>
    </div>
    <div id="category-advertisement-box">
        <table style="width: 100%; height: 100%; vertical-align: middle;">
            <tr>
                <td>Advertisement <br/> 160px (W) x 100px (H)</td>
            </tr>
        </table>
    </div>
    <div id="category-advertisement-box">
        <table style="width: 100%; height: 100%; vertical-align: middle;">
            <tr>
                <td>Advertisement <br/> 160px (W) x 100px (H)</td>
            </tr>
        </table>
    </div>
</div>