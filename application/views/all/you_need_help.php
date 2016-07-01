<?php
$video_link = $_POST['video_link'];
?>

<script>
    $(function () {   
       // install flowplayer into all elements with CSS class="player"
       $(".flowplayer-manual-active").flowplayer({
           autoplay: true
       });
    });
</script>
  
<div class="flowplayer-manual-active">
    <video>
        <source type="video/flv" src="<?php echo base_url() ?>video/<?php echo $video_link ?>">
    </video>
</div>