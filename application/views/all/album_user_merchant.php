<?php
echo "<h1>".$title."</h1>";
if (check_correct_login_type($this->config->item('group_id_user')) && $this->router->fetch_method() == 'album_user_merchant')
{
    $user_id = $this->ion_auth->user()->row()->id;

    echo "<a href='" . base_url() . "all/album_user/" . $user_id . "'>Picture Album</a><br/>";
    echo "<a href='" . base_url() . "user/upload_for_merchant'>Upload</a><br/>";
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

<?php
foreach ($album_list as $row)
{
    $advertise_detail_url = base_url()."all/user_merchant_picture/".$row['merchant_user_album_id'];
    $merchant_name = $this->m_custom->display_users($row['merchant_id']);
    $merchant_dashboard_url = base_url()."all/merchant-dashboard/".generate_slug($merchant_name);
    echo "<div class='hot-deal-box'>";
    echo "<h2><a href='".$merchant_dashboard_url."'>".$merchant_name."</a></h2><br/>";
    echo "<a href='".$advertise_detail_url."'><img src='" . base_url($this->album_user_merchant.$row['image']) . "' class='image-hot-deal' ></a><br/>";
    echo "<a href='".$advertise_detail_url."'>".$row['title']."</a><br/>";
    echo "Upload by : " . $this->m_custom->display_users($row['user_id'])."<br/>";
    echo "Like : 30 ";
    echo "Comment : 10";
    echo "<br/>";
    
    echo "</div>";
}

?>