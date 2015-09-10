<?php 
//$this->load->view('template/sidebar_left');
//        
////Example of show all company under the Category in Banner Home Page
//$abc = $this->m_custom->getMerchantList_by_category(4, 1);
//foreach ($abc as $one_row) {
//    echo "<a href=".base_url()."merchant/dashboard/".$one_row->slug.">".$one_row->company."</a><br/>";
//}
?>

<div id="home-navigation">
    
    <?php 
    if($slug == NULL){
        $slug = '1';
    } 
    ?>
    
    <div id="home-navigation-c1">
        <?php
        foreach ($category_array as $category) {
            $category_id = $category->category_id;
            $category_label = $category->category_label;
            ?>
            <div id="home-navigation-c1-bar">
                <a href="<?php echo base_url() . 'home/index/' . $category_id ?>" class="home-navigation-c1-bar-a <?php if($category_id == $slug){ echo 'home-navigation-c1-bar-a-active'; } ?>"><?php echo $category_label; ?></a>
            </div>
            <?php
        }
        ?>
    </div>
    <div id="home-navigation-c2">
        
        <div id="home-navigation-c2-c1">
            <div id="home-navigation-c2-c1-content">
                <?php
                foreach ($category_merchant_array as $category_merchant) {
                    $company = $category_merchant->company;
                    $slug = $category_merchant->slug;
                    ?>
                    <div id="home-navigation-c2-c1-bar">
                        <a href='<?php echo base_url() ?>all/merchant-dashboard/<?php echo $slug ?>'><?php echo $company ?></a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

        <div id="home-navigation-c2-c2">
            <div id="home-navigation-c2-c2-content">

                <table border="0px" cellpadding="0px" cellspacing="5px" style="width: 100%; table-layout: fixed; border-collapse: separate">
                    <tr>
                        <td colspan="6">
                            <img src="<?php echo base_url() ?>folder_upload/home_banner/banner1.jpg" style="width: 100%; display: block;">
                        </td>
                        <td colspan="6">
                            <img src="<?php echo base_url() ?>folder_upload/home_banner/banner2.jpg" style="width: 100%; display: block">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8"> 
                            <img src="<?php echo base_url() ?>folder_upload/home_banner/banner3.jpg" style="width: 100%; display: block">
                        </td>
                        <td colspan="4" rowspan="2">                               
                            <img src="<?php echo base_url() ?>folder_upload/home_banner/banner4.jpg" style="width: 100%; display: block">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <img src="<?php echo base_url() ?>folder_upload/home_banner/banner5.jpg" style="width: 100%; display: block">
                        </td>
                        <td colspan="4" rowspan="2">
                            <img src="<?php echo base_url() ?>folder_upload/home_banner/banner6.jpg" style="width: 100%; display: block">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <img src="<?php echo base_url() ?>folder_upload/home_banner/banner7.jpg" style="width: 100%; display: block">
                        </td>
                        <td colspan="4">
                            <img src="<?php echo base_url() ?>folder_upload/home_banner/banner8.jpg" style="width: 100%; display: block">
                        </td>
                    </tr>
                </table>

            </div>
        </div>
        
    </div>

    <div id="float-fix"></div>
    
</div>