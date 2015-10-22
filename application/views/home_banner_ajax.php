<?php 
//POST VALUE
$category_id = $_POST['category_id'];
?>

<?php
if ($category_id == 0)
{
    ?>
    <table border="0" style="height:400px; width:100%; text-align:center;">
        <tr>
            <td>
                Main Page
            </td>
        </tr>
    </table>
    <?php
}
else
{
    ?>
    <div id="home-navigation-c2-c1">
        <div id="home-navigation-c2-c1-content">
            <?php     
//            //MERCHANT ROW
//            $row_merchant = $query_merchant->result_array();
//            foreach ($row_merchant as $merchant)
//            {
//                $merchant_company = $merchant['company'];
//                $merchant_slug = $merchant['slug'];
//                ?>
<!--                <div id="home-navigation-c2-c1-bar">
                    <a href='//<?php echo base_url() ?>all/merchant_dashboard/<?php // echo $merchant_slug ?>'><?php // echo $merchant_company ?></a>
                </div>-->
            <?php
//            }
                
            $result_array_read_sub_category_with_merchant = $query_read_sub_category_with_merchant->result_array();
            foreach($result_array_read_sub_category_with_merchant as $result_array_read_sub_category_with_merchant)
            {
                $category_id = $result_array_read_sub_category_with_merchant['category_id'];
                $category_label = $result_array_read_sub_category_with_merchant['category_label'];
                ?>
                <div id="home-navigation-c2-c1-company-label"><?php echo $category_label ?></div>
                <?php
                //READ MERCHANT
                $where_read_merchant = array('me_sub_category_id'=>$category_id);
                $query_read_merchant = $this->albert_model->read_merchant($where_read_merchant);
                $result_array_read_merchant = $query_read_merchant->result_array();
                foreach($result_array_read_merchant as $read_merchant)
                {
                    $merchant_slug = $read_merchant['slug'];
                    $merchant_company = $read_merchant['company'];
                    ?>
                    <div id="home-navigation-c2-c1-company-title">
                        <a href="<?php echo base_url("all/merchant_dashboard/$merchant_slug") ?>">
                            <table border='0' cellpadding='0' cellspacing='0'>
                                <tr>
                                    <td valign='top'>
                                        <i class="fa fa-caret-right home-navigation-c2-c1-company-title-icon"></i>
                                    </td>
                                    <td>
                                        <?php echo $merchant_company ?>
                                    </td>
                                </tr>
                            </table>
                        </a>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <div id="home-navigation-c2-c2">
        <div id="home-navigation-c2-c2-content">
            <table border="0px" cellpadding="0px" cellspacing="5px" style="width: 100%; table-layout: fixed; border-collapse: separate">
                <tr>
                    <td colspan="8" rowspan="2">
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
                    <td colspan="4">
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
                    <td colspan="4" rowspan="2">                               
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
                </tr>
                <tr>
                    <td colspan="4">
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
                    <td colspan="4" rowspan="2">
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
                </tr>
                <tr>
                    <td colspan="4">
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
                </tr>
            </table>
        </div>
    </div>
    <?php
}
