<link href="http://localhost/keppo/js/jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="http://localhost/keppo/js/jquery-ui-1.11.4.custom/jquery-ui.js"></script>

<style>
    .cat_box{
        width:23%;
        margin: 10px;
        float:left;
        border:solid blue thin;
    }
</style>

    <?php
    $cat_list = $this->m_custom->getCategory();
    foreach ($cat_list as $t_cat):
        ?>

<div>
   <div id="<?= $t_cat->category_id ?>-header" class="cat_box" ><?= $t_cat->category_label ?>
        <!--Heading of the accordion ( clicked to show n hide ) -->
        <!--Prefix of heading (the DIV above this) and content (the DIV below this) to be same... eg. foo-header & foo-content-->
        <div id="<?= $t_cat->category_id ?>-content">
            <!--DIV which show/hide on click of header-->
            <!--This DIV is for inline styling like padding...-->
            <?php $subcat_list = $this->m_custom->getSubCategory($t_cat->category_id); ?>
            <?php foreach ($subcat_list as $t_subcat): ?>
                <div class="accordion_child"><a href="#"></a>

                    <div>
                        <div class="A_menu_item"><a href="#"><?= $t_subcat->category_label ?></a></div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
        </div>
        <!--End of each accordion item-->
        <!--Start of each accordion item-->
    <?php endforeach ?>
    <!--End of each accordion item-->
</div>