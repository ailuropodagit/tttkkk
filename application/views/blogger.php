<?php
$result_array_user = $query_user->result_array();
foreach($result_array_user as $user)
{
    echo $user['id'];
    echo "<br/>";
    
    
}
?>

<input type='text'>