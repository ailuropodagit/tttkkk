<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/jgrowl/jquery.jgrowl.js"></script>
<?php echo link_tag('js/jgrowl/jquery.jgrowl.css') ?>

<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.countdown.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('[data-countdown]').each(function () {
            var $this = $(this), finalDate = $(this).data('countdown');
            $this.countdown(finalDate).on('update.countdown', function (event) {
                var format = '%H:%M:%S';
                if (event.offset.days > 0) {
                    format = '%-d day%!d ' + format;
                }
                if (event.offset.weeks > 0) {
                    format = '%-w week%!w ' + format;
                }
                $this.html(event.strftime(format));
            })
            .on('finish.countdown', function (event) {
                $this.html('Expired!');
            });
        });
    });
    $(function () {
        $('#form-rate :radio.star').rating();
    });
</script>

<?php
//CONFIG DATA
$merhant_profile_album = $this->config->item('album_merchant_profile');
$profile_image_empty = $this->config->item('empty_image');
?>

<div id="search-result">
    <h1>Search Result<?php echo $state_name ?></h1>
    <div id='search-result-content'>
        <!--MERCHANT RESULT-->
        <div id="search-result-merchant">
            <?php
            $data['title'] = 'Retailer';
            $data['review_list'] = $home_search_merchant;
            $this->load->view('share/merchant_grid_list5', $data);
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
            $data['title'] = 'Redemption';
            $data['share_hotdeal_redemption_list'] = $home_search_promotion;
            $this->load->view('share/redemption_grid_list5', $data);
            ?>
        </div>
    </div>
</div>