<style>
    #c {
        margin: 0px 0px 20px 0px;
    }
    
    #c:last-child {
        margin: 0px;
    }
    
    #cc {
        margin: 0px 0px 2px 0px;
    }
</style>

<?php
$cat_list = $this->m_custom->getCategory();
?>

<?php foreach ($cat_list as $t_cat) { ?>

    <div style="font-size: 18px; font-weight: bold;">
        <?php echo $t_cat->category_label ?>
        <?php echo $t_cat->category_id ?>
    </div>
    
    <div id="c">
        <?php 
        $subcat_list = $this->m_custom->getSubCategory($t_cat->category_id); 
        foreach ($subcat_list as $t_subcat) {
            ?>
            <div id="cc">
                <a href="#"><?php echo $t_subcat->category_label ?></a>
            </div>
            <?php
        }
        ?>
    </div>

<?php }