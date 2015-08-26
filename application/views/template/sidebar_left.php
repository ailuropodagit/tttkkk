<link href="http://localhost/keppo/js/jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="http://localhost/keppo/js/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<script>
    $(function () {
        $("#basic-accordian").menu();
    });
</script>

<div id="basic-accordian" style="width:240px" >
    <!--Parent of the Accordion-->
    <!--Start of each accordion item-->

    <?php
    $cat_list = $this->m_custom->getCategory();
    ?>
    <div id="0-header"><a href="http://localhost/keppo/home/category">Categories</a></div>
    <?php
    foreach ($cat_list as $t_cat):
        ?>

        <div id="<?= $t_cat->category_id ?>-header" ><a href="#"><?= $t_cat->category_label ?></a></div>

    <?php endforeach ?>
    <!--End of each accordion item-->
</div>  

