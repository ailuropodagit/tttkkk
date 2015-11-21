<h1>Who Redeem This Promo Code?</h1></br>
<?php
echo "<h3><i class='fa fa-heart'></i> " . $post_title . " <i class='fa fa-heart'></i></h3></br></br>";

if (!empty($result_list))
{
    foreach ($result_list as $row)
    {
        echo $this->m_custom->generate_user_link($row['user_id'], 1) . "</br></br>";
    }
}
?>