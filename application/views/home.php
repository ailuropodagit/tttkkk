<div id="home-navigation">    
    <div id="home-navigation-c1">
        <?php
        //CATEGORY ROW
        $row_category = $query_category->result_array();
        foreach ($row_category as $category)
        {
            //CATEGORY DATA
            $category_id = $category['category_id'];
            $category_label = $category['category_label'];
            ?>
            <div id="home-navigation-c1-bar">
                <a href="<?php echo base_url() . 'home/index/' . $category_id ?>" class="home-navigation-c1-bar-a <?php if($category_id == $main_category_id){ echo 'home-navigation-c1-bar-a-active'; } ?>"><?php echo $category_label; ?></a>
            </div>
            <?php
        }
        ?>
        <div id="home-navigation-c1-bar">
            <a href='<?php echo base_url(); ?>categories' class="home-navigation-c1-bar-a">Categories</a>
        </div>   
    </div>
    <div id="home-navigation-c2">
        <div id="home-navigation-c2-c1">
            <div id="home-navigation-c2-c1-content">
                <?php     
                //MERCHANT ROW
                $row_merchant = $query_merchant->result_array();
                foreach ($row_merchant as $merchant)
                {
                    $merchant_company = $merchant['company'];
                    $merchant_slug = $merchant['slug'];
                    ?>
                    <div id="home-navigation-c2-c1-bar">
                        <a href='<?php echo base_url() ?>all/merchant_dashboard/<?php echo $merchant_slug ?>'><?php echo $merchant_company ?></a>
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
                            <?php 
                            //BANNER POSITION 1
                            $row_banner_position_1 = $query_banner_position_1->row_array();
                            $num_rows_banner_position_1 = $query_banner_position_1->num_rows();
                            if($num_rows_banner_position_1)
                            {
                                $banner_image_position_1 = $row_banner_position_1['banner_image'];
                                $banner_url_position_1 = $row_banner_position_1['banner_url'];
                                ?>
                                <a href='<?php echo $banner_url_position_1 ?>'>
                                    <img src="<?php echo base_url() ?>folder_upload/home_banner/<?php echo $banner_image_position_1 ?>">
                                </a>
                                <?php
                            }
                            else
                            {
                                ?><img src="<?php echo base_url() ?>folder_upload/home_banner/0-empty-banner-1.jpg"><?php
                            }
                            ?>
                        </td>
                        <td colspan="6">
                            <?php 
                            //BANNER POSITION 2
                            $row_banner_position_2 = $query_banner_position_2->row_array();
                            $num_rows_banner_position_2 = $query_banner_position_2->num_rows();
                            if($num_rows_banner_position_2)
                            {
                                $banner_image_position_2 = $row_banner_position_2['banner_image'];
                                $banner_url_position_2 = $row_banner_position_2['banner_url'];
                                ?>
                                <a href='<?php echo $banner_url_position_2 ?>'>
                                    <img src="<?php echo base_url() ?>folder_upload/home_banner/<?php echo $banner_image_position_2 ?>">
                                </a>
                                <?php
                            }
                            else
                            {
                                ?><img src="<?php echo base_url() ?>folder_upload/home_banner/0-empty-banner-2.jpg"><?php
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8"> 
                            <?php 
                            //BANNER POSITION 3
                            $row_banner_position_3 = $query_banner_position_3->row_array();
                            $num_rows_banner_position_3 = $query_banner_position_3->num_rows();
                            if($num_rows_banner_position_3)
                            {
                                $banner_image_position_3 = $row_banner_position_3['banner_image'];
                                $banner_url_position_3 = $row_banner_position_3['banner_url'];
                                ?>
                                <a href='<?php echo $banner_url_position_3 ?>'>
                                    <img src="<?php echo base_url() ?>folder_upload/home_banner/<?php echo $banner_image_position_3 ?>">
                                </a>
                                <?php
                            }
                            else
                            {
                                ?><img src="<?php echo base_url() ?>folder_upload/home_banner/0-empty-banner-3.jpg"><?php
                            }
                            ?>
                        </td>
                        <td colspan="4" rowspan="2">                               
                            <?php 
                            //BANNER POSITION 4
                            $row_banner_position_4 = $query_banner_position_4->row_array();
                            $num_rows_banner_position_4 = $query_banner_position_4->num_rows();
                            if($num_rows_banner_position_4)
                            {
                                $banner_image_position_4 = $row_banner_position_4['banner_image'];
                                $banner_url_position_4 = $row_banner_position_4['banner_url'];
                                ?>
                                <a href='<?php echo $banner_url_position_4 ?>'>
                                    <img src="<?php echo base_url() ?>folder_upload/home_banner/<?php echo $banner_image_position_4 ?>">
                                </a>
                                <?php
                            }
                            else
                            {
                                ?><img src="<?php echo base_url() ?>folder_upload/home_banner/0-empty-banner-4.jpg"><?php
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <?php 
                            //BANNER POSITION 5
                            $row_banner_position_5 = $query_banner_position_5->row_array();
                            $num_rows_banner_position_5 = $query_banner_position_5->num_rows();
                            if($num_rows_banner_position_5)
                            {
                                $banner_image_position_5 = $row_banner_position_5['banner_image'];
                                $banner_url_position_5 = $row_banner_position_5['banner_url'];
                                ?>
                                <a href='<?php echo $banner_url_position_5 ?>'>
                                    <img src="<?php echo base_url() ?>folder_upload/home_banner/<?php echo $banner_image_position_5 ?>">
                                </a>
                                <?php
                            }
                            else
                            {
                                ?><img src="<?php echo base_url() ?>folder_upload/home_banner/0-empty-banner-5.jpg"><?php
                            }
                            ?>
                        </td>
                        <td colspan="4" rowspan="2">
                            <?php
                            //BANNER POSITION 6
                            $row_banner_position_6 = $query_banner_position_6->row_array();
                            $num_rows_banner_position_6 = $query_banner_position_6->num_rows();
                            if($num_rows_banner_position_6)
                            {
                                $banner_image_position_6 = $row_banner_position_6['banner_image'];
                                $banner_url_position_6 = $row_banner_position_6['banner_url'];
                                ?>
                                <a href='<?php echo $banner_url_position_6 ?>'>
                                    <img src="<?php echo base_url() ?>folder_upload/home_banner/<?php echo $banner_image_position_6 ?>">
                                </a>
                                <?php
                            }
                            else
                            {
                                ?><img src="<?php echo base_url() ?>folder_upload/home_banner/0-empty-banner-6.jpg"><?php
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <?php
                            //BANNER POSITION 7
                            $row_banner_position_7 = $query_banner_position_7->row_array();
                            $num_rows_banner_position_7 = $query_banner_position_7->num_rows();
                            if($num_rows_banner_position_7)
                            {
                                $banner_image_position_7 = $row_banner_position_7['banner_image'];
                                $banner_url_position_7 = $row_banner_position_7['banner_url'];
                                ?>
                                <a href='<?php echo $banner_url_position_7 ?>'>
                                    <img src="<?php echo base_url() ?>folder_upload/home_banner/<?php echo $banner_image_position_7 ?>">
                                </a>
                                <?php
                            }
                            else
                            {
                                ?><img src="<?php echo base_url() ?>folder_upload/home_banner/0-empty-banner-7.jpg"><?php
                            }
                            ?>
                        </td>
                        <td colspan="4">
                            <?php
                            //BANNER POSITION 8
                            $row_banner_position_8 = $query_banner_position_8->row_array();
                            $num_rows_banner_position_8 = $query_banner_position_8->num_rows();
                            if($num_rows_banner_position_8)
                            {
                                $banner_image_position_8 = $row_banner_position_8['banner_image'];
                                $banner_url_position_8 = $row_banner_position_8['banner_url'];
                                ?>
                                <a href='<?php echo $banner_url_position_8 ?>'>
                                    <img src="<?php echo base_url() ?>folder_upload/home_banner/<?php echo $banner_image_position_8 ?>">
                                </a>
                                <?php
                            }
                            else
                            {
                                ?><img src="<?php echo base_url() ?>folder_upload/home_banner/0-empty-banner-8.jpg"><?php
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div id="float-fix"></div>
</div>