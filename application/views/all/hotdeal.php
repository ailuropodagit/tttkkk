<script type="text/javascript" src="http://localhost/keppo/js/jquery.countdown.js"></script>
<script type="text/javascript" src="http://localhost/keppo/js/js_custom.js"></script>

<script type="text/javascript">
    $( document ).ready(function() {
        $('[data-countdown]').each(function() {
        var $this = $(this), finalDate = $(this).data('countdown');
        
        $this.countdown(finalDate)
        .on('update.countdown', function(event) {
        var format = '%H:%M:%S';
        if(event.offset.days > 0) {
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

<h1>Hot Deal</h1>
<br/>

<h2><?php  echo $name . "</br>"; ?></h2>

<?php
if(!empty($previous_url)){
echo "<a href='" . $previous_url . "' >Previous</a> ";
}
if(!empty($next_url)){
echo "<a href='" . $next_url . "' >Next</a> ";
}
?>
<br/>


 <?php   echo "People Reached <br/>".$this->m_custom->activity_view_count($advertise_id)." users"; ?>
<div id='hot-deal-photo-box'>
    <?php
    echo "<img src='" . $image_url . "' id='hotdeal-img'>";
    echo $title . "</br>";
    echo "<div><div data-countdown='".$end_time."'></div></div>";
    ?>
</div>

<div id="float-fix"></div>
<br/>
<br/>
<?php echo "Category : " . $sub_category . "<br/>"; ?>

Description :
    <?php   echo $description . "</br>";     ?>
</br>
<?php
    echo $like_url;
    echo "Comment : 34 ";
    echo "Share : <br/>";
    ?>

<br/>

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
        <div id="profile-bottom-link-right">
            <div id="profile-bottom-link-right-each">
                <a href='<?php echo base_url() . "merchant/edit_hotdeal/" . $advertise_id ?>' >Edit Hot Deal</a>
            </div>
            <div id='float-fix'></div>
        </div>
        <?php
    }
}
?>