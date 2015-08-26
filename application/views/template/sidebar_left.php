<?php
$cat_list = $this->m_custom->getCategory();
foreach ($cat_list as $t_cat) {
?>

    <div style="font-size: 18px; font-weight: bold;">
        <?php echo $t_cat->category_label ?>
    </div>
    
    <div style="margin: 0px 0px 20px 0px;">
        <?php 
        $subcat_list = $this->m_custom->getSubCategory($t_cat->category_id); 
        foreach ($subcat_list as $t_subcat) { 
            ?>
            <div class="accordion_child"><a href="#"></a>
                <div>
                    <div><a href="#"><?= $t_subcat->category_label ?></a></div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>

<?php
} 
?>

