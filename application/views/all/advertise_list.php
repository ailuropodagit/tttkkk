<script type="text/javascript" src="http://localhost/keppo/js/jquery.countdown.js"></script>

<h1><?php echo $title; ?></h1>
<h2><?php 
if(!empty($sub_title)){
echo $sub_title; 
}
?></h2>
<br/>
<?php
if(!empty($upload_hotdeal_button)){
echo $upload_hotdeal_button; 
}
?>
<?php
if(!empty($links)){
echo "<p>".$links."<p>"; 
}
?>
<style>
    .hot-deal-box{
        float: left;
        width:250px;
        margin:20px;
        height:400px;
        border:1px solid black;
    }
    .image-hot-deal{
        max-height: 200px;
        max-width: 200px;
    }
</style>

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
<?php
$this->album_merchant = $this->config->item('album_merchant');

foreach ($hotdeal_list as $row)
{
    if ($this->router->fetch_method() == 'hotdeal_list'){
        $advertise_detail_url = base_url()."all/advertise/".$row['advertise_id'] . "/hot/" . $row['sub_category_id'];
    }else if($this->router->fetch_method() == 'merchant_dashboard'){
        $advertise_detail_url = base_url()."all/advertise/".$row['advertise_id'] . "/all/0/" . $row['merchant_id'];
    }else if($this->router->fetch_method() == 'album_merchant' ){
        $advertise_detail_url = base_url()."all/advertise/".$row['advertise_id'] . "/all/0/" . $row['merchant_id'] . '/1';
    }else if($this->router->fetch_method() == 'promotion_list'){
        $advertise_detail_url = base_url()."all/advertise/".$row['advertise_id'] . "/pro/" . $row['sub_category_id'];
    }else{
        $advertise_detail_url = base_url()."all/advertise/".$row['advertise_id'];
    }
    $merchant_name = $this->m_custom->display_users($row['merchant_id']);
    $merchant_dashboard_url = base_url()."all/merchant-dashboard/".generate_slug($merchant_name);
    echo "<div class='hot-deal-box'>";
    echo "<h2><a href='".$merchant_dashboard_url."'>".$merchant_name."</a></h2><br/>";
    echo "<a href='".$advertise_detail_url."'><img src='" . base_url($this->album_merchant.$row['image']) . "' class='image-hot-deal' ></a><br/>";
    echo "<a href='".$advertise_detail_url."'>".$row['title']."</a><br/>";
    echo "Category : " . $this->m_custom->display_category($row['sub_category_id'])."<br/>";
    echo "Like : ". $this->m_custom->activity_like_count($row['advertise_id'], 'adv')."<br/>";
    echo "Comment : 10";
    echo "<br/>";
    if($row['advertise_type'] == 'hot'){
    echo "<div><div data-countdown='".$row['end_time']."'></div></div>";
    }
    
    if($row['advertise_type'] == 'pro'){
        echo $row['voucher_candie']." candies";
    }
    
    echo "</div>";
}