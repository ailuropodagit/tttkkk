<h1>Who Like It?</h1></br>
<?php

echo "<h3><i class='fa fa-heart'></i> ".$post_title . " <i class='fa fa-heart'></i></h3></br></br>";

foreach ($like_list as $row)
{
    echo $row . "</br></br>";
}
?>