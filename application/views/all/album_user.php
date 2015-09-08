<?php
echo "<h1>".$title."</h1>";
if (check_correct_login_type($this->config->item('group_id_user')))
{
    $user_id = $this->ion_auth->user()->row()->id;

    echo "<a href='" . base_url() . "all/album_user_merchant/" . $user_id . "'>Merchant Album</a><br/>";
    echo "<a href='" . base_url() . "user/upload_image'>Upload</a><br/>";
}
?>

user image album

