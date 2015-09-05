<link href="http://localhost/keppo/js/jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="http://localhost/keppo/js/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<script type="text/javascript">
    $(function () {
        var which_tab = parseInt(location.pathname.split('/').pop());
        $("#basic-accordian").accordion({
        active: which_tab
        });
    });

</script>

<style>
    .A_menu_item{
        font-size:smaller;
    }
</style>

<div id="basic-accordian" style="width:240px" >
    <!--Parent of the Accordion-->
    <!--Start of each accordion item-->

    <?php
    $tab_counter = 0;
    $cat_list = $this->m_custom->getCategory();
    foreach ($cat_list as $t_cat):
        ?>

        <div id="<?= $t_cat->category_id ?>-header" class="accordion_headings" ><?= $t_cat->category_label ?></div>
        <!--Heading of the accordion ( clicked to show n hide ) -->
        <!--Prefix of heading (the DIV above this) and content (the DIV below this) to be same... eg. foo-header & foo-content-->
        <div id="<?= $t_cat->category_id ?>-content">
            <!--DIV which show/hide on click of header-->
            <!--This DIV is for inline styling like padding...-->
            <?php $subcat_list = $this->m_custom->getSubCategory($t_cat->category_id); ?>
            <?php foreach ($subcat_list as $t_subcat): ?>
                <div class="accordion_child"><a href="#"></a>

                    <div>
                        <div class="A_menu_item">                  
                            <?php 
                            if ($this->router->fetch_method() == 'promotion_list'){
                                $gotoURL = base_url()."all/promotion-list/".$t_subcat->category_id."/".$tab_counter;
                            }else{
                                $gotoURL = base_url()."all/hotdeal-list/".$t_subcat->category_id."/".$tab_counter;
                            }
                            echo "<a href='".$gotoURL."'> ".$t_subcat->category_label."</a>"; 
                            ?>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
        <!--End of each accordion item-->
        <!--Start of each accordion item-->
    <?php 
    $tab_counter = $tab_counter+1;
    endforeach ?>
    <!--End of each accordion item-->
</div>  

