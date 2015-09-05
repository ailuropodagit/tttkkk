<script type="text/javascript" src="http://localhost/keppo/js/jquery.countdown.js"></script>

<h1><?php echo $advertise_title; ?></h1>
<h2><?php 
if(!empty($sub_title)){
echo $sub_title; 
}
?></h2>
<br/>
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
    echo "<div class='hot-deal-box'>";
    echo "<img src='" . base_url($this->album_merchant.$row['image']) . "' class='image-hot-deal' ><br/>";
    echo $row['title']."<br/>";
    echo "Category : " . $this->m_custom->display_category($row['sub_category_id'])."<br/>";
    echo "<a href=''>Like</a> : 30";
    echo "<a href='".base_url()."all/advertise/".$row['advertise_id']."'>Comment</a> : 10";
    
    if($row['advertise_type'] == 'hot'){
    echo "<div><div data-countdown='".$row['end_time']."'></div></div>";
    }
    
    if($row['advertise_type'] == 'pro'){
        echo $row['voucher_candie']." candies";
    }
    
    echo "</div>";
}