<?php
echo "<h1>User Upload Image</h1>";
if (check_correct_login_type($this->config->item('group_id_user')))
{
    $user_id = $this->ion_auth->user()->row()->id;

    echo "<a href='" . base_url() . "all/album_user/" . $user_id . "'>Picture Album</a><br/>";
}
?>
user uploaded their self image