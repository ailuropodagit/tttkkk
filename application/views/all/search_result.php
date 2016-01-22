<div id="search-result">
    <h1>Search Result<?php echo $state_name ?></h1>
    <div id='search-result-content'>
        <!--MERCHANT RESULT-->
        <div id="search-result-merchant">
            <?php
//            $data['title'] = 'Retailer';
//            $data['review_list'] = $home_search_merchant;
//            $this->load->view('share/merchant_grid_list5', $data);
            ?>
        </div>
        <!--HOT DEAL RESULT-->
        <div id="search-result-hot-deal">
            <?php
            $data['title'] = 'Hot Deal';            
            $data['share_hotdeal_redemption_list'] = $home_search_hotdeal;
            $this->load->view('share/hot_deal_grid_list5', $data);
            ?>
        </div>
        <!--PROMOTION RESULT-->
        <div id="search-result-promotion">
            <?php
//            $data['title'] = 'Redemption';
//            $data['share_hotdeal_redemption_list'] = $home_search_promotion;
//            $this->load->view('share/redemption_grid_list5', $data);
            ?>
        </div>
    </div>
</div>