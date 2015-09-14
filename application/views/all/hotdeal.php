<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.countdown.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/jgrowl/jquery.jgrowl.js"></script>
<?php echo link_tag('js/jgrowl/jquery.jgrowl.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<script type="text/javascript">
    $( document ).ready(function() {
        $('[data-countdown]').each(function() {
        var $this = $(this), finalDate = $(this).data('countdown');
        
        $this.countdown(finalDate)
        .on('update.countdown', function(event) {
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

</script>

<div id='hot-deal'>
    <h1>Hot Deal</h1>
    <div id='hot-deal-content'>
        
        <?php echo "Category : " . $sub_category . "<br/>"; ?>
        
        <div id='hot-deal-table'>
            <div id='hot-deal-table-row'>
                <div id='hot-deal-table-row-cell' class='hot-deal-left-cell'>
                    <div id='hot-deal-left'>
                        <?php
                        if (!empty($previous_url))
                        {
                            ?><a href="<?php echo $previous_url ?>"><i class="fa fa-angle-double-left"></i></a><?php
                        }
                        else 
                        {
                            ?><div id='hot-deal-left-gray'><i class="fa fa-angle-double-left"></i></div><?php
                        }
                        ?>
                    </div>
                </div>
                <div id='hot-deal-table-row-cell' class='hot-deal-center-cell'>
                    <div id='hot-deal-center'>
                        <div id="hot-deal-title">
                            <a href='<?php echo $merchant_dashboard_url ?>'> <?php echo $merchant_name ?></a>
                        </div>
                        <div id='hot-deal-photo-box'>
                            <img src='<?php echo $image_url ?>'>
                        </div>
                        <div id="hot-deal-sub-title">
                            <?php echo $title ?>
                        </div>
                        <div id="hot-deal-rate-time">
                            <div id="hot-deal-rate">
                                <div style="display:inline;">
                                    <?php
                                    echo form_input($item_id);
                                    echo form_input($item_type);
                                    for ($i = 1; $i <= 5; $i++)
                                    {
                                        if ($i == round($average_rating))
                                        {
                                            echo "<input class='auto-submit-star' type='radio' name='rating' " . $radio_level . " value='" . $i . "' checked='checked'/>";
                                        }
                                        else
                                        {
                                            echo "<input class='auto-submit-star' type='radio' name='rating' " . $radio_level . " value='" . $i . "'/>";
                                        }
                                    } //end of for
                                    ?>
                                </div>
                            </div>
                            <div id="hot-deal-time">
                                <div data-countdown='<?php echo $end_time ?>'></div>
                            </div>
                            <div id="float-fix"></div>
                        </div>
                        <div id="hot-deal-description">
                            <?php echo $description ?>
                        </div>
                        <div id="hot-deal-like-comment-share">
                            <?php
                            echo $like_url;
                            echo $comment_url;
                            echo "Share : <br/>";
                            ?>
                        </div>
                        <div id="hot-deal-people-reach">
                            <?php echo "People Reached " . $this->m_custom->activity_view_count($advertise_id) . " users"; ?>
                        </div>
                        <div id="hot-deal-comment">
                            <?php
                            $this->load->view('all/comment_form');
                            ?>
                        </div>
                    </div>
                </div>
                <div id='hot-deal-table-row-cell' class='hot-deal-right-cell'>
                    <div id='hot-deal-right'>
                        <?php
                        if (!empty($next_url))
                        {
                            ?><a href="<?php echo $next_url ?>"><i class="fa fa-angle-double-right"></i></a><?php
                        }
                        else 
                        {
                            ?><div id='hot-deal-right-gray'><i class="fa fa-angle-double-right"></i></div><?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>





<?php
if (check_is_login())
{
    $merchant_id = $this->ion_auth->user()->row()->id;
    if (check_correct_login_type($this->config->item('group_id_supervisor')))
    {
        $merchant_id = $this->ion_auth->user()->row()->su_merchant_id;
    }
    $allowed_list = $this->m_custom->get_list_of_allow_id('advertise', 'merchant_id', $merchant_id, 'advertise_id');
    if (check_correct_login_type($this->config->item('group_id_merchant'), $allowed_list, $advertise_id) || check_correct_login_type($this->config->item('group_id_supervisor'), $allowed_list, $advertise_id))
    {
        ?>
        <a href='<?php echo base_url() . "merchant/edit_hotdeal/" . $advertise_id ?>' >Edit Hot Deal</a>
        <?php
    }
}
