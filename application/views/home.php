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
    
    <div id="home-navigation-c1">
        <?php
        foreach ($category_array as $category) {
            $category_id = $category->category_id;
            $category_label = $category->category_label;
            echo $category_label;
            echo "<br/>";
        }
        ?>
    </div>
    <div id="home-navigation-c2">
        <?php
        foreach ($category_merchant_array as $category_merchant) {
            $company = $category_merchant->company;
            ?>
            <div id="home-navigation-right-menu-each">
                <a href='#'><?php echo $company ?></a>
            </div>
            <?php
        }
        ?>
    </div>
    <div id="home-navigation-c3">
        <div id="home-navigation-c3-content">
            <table border="0" cellpadding="0px" cellspacing="0px" style="width: 100%;">
                <tr>
                    <td colspan="3" style="width: 50%;">
                        
                        <div style="padding-bottom: 30%; margin: 6px; border: 1px solid ">
                            banner 1
                        </div>
                        
                    </td>
                    <td colspan="3" style="width: 50%;">
                        
                        <div style="padding-bottom: 27%; margin: 6px; border: 1px solid ">
                            banner 2
                        </div>
                        
                    </td>
                </tr>
                <tr>                    
                    <td colspan="4" style="width: 30%;">

                        <div style="padding-bottom: 30%; margin: 6px; border: 1px solid ">
                            banner 3
                        </div>
                        
                    </td>
                    <td colspan="2" rowspan="2">
                        
                        <div style="padding-bottom: 120%; margin: 6px; border: 1px solid ">
                            banner 4
                        </div>
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="width: 30%;">
                        
                        <div style="padding-bottom: 60%; margin: 6px; border: 1px solid ">
                            banner 5
                        </div>
                        
                    </td>
                    <td colspan="2" rowspan="2" style="width: 30%;">

                        <div style="padding-bottom: 130%; margin: 6px; border: 1px solid ">
                            banner 6
                        </div>
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="2">

                        <div style="padding-bottom: 60%; margin: 6px; border: 1px solid ">
                            banner 7
                        </div>
                        
                    </td>
                    <td colspan="2">

                        <div style="padding-bottom: 50%; margin: 6px; border: 1px solid ">
                            banner 8
                        </div>
                        
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <div id="float-fix"></div>
    
</div>