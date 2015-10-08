<?php
//CONFIG DATA
$user_profile_path = $this->config->item('album_user_profile');
$empty_image = $this->config->item('empty_image');
?>

<div id="blogger">
    <h1>Blogger</h1>
    <div id="blogger-content">
        <div id="blogger-search">
            <?php echo form_open() ?>
            <div id="blogger-search-input"><?php echo form_input($keyword) ?></div>
            <div id="blogger-search-submit"><input type="submit" name="search" value="Search"></div>
            <div id="blogger-search-clear"><a href='<?php echo base_url('blogger') ?>' class="a-href-button">Clear</a></div>
            <?php echo form_close() ?>
        </div>
        <?php
        //QUERY USER
        $result_array_blogger = $query_blogger->result_array();
        foreach($result_array_blogger as $blogger)
        {
            $user_id = $blogger['id'];
            $user_name = $blogger['first_name'] . ' ' . $blogger['last_name'];
            $user_profile_image = $blogger['profile_image'];
            $user_blog_url = $blogger['us_blog_url'];
            ?>
            <div id="blogger-box">
                <div id="blogger-box-photo-box">
                    <a href="<?php echo base_url("all/user_dashboard/$user_id") ?>">
                        <?php 
                        if($user_profile_image)
                        {
                            echo img("$user_profile_path/$user_profile_image");
                        }
                        else
                        {
                            echo img("$empty_image");
                        }
                        ?>
                    </a>
                </div>
                <div id="blogger-box-info">
                    <div id="blogger-box-info-name">
                        <a href="<?php echo base_url("all/user_dashboard/$user_id") ?>">
                            <?php echo $user_name ?>
                        </a>
                    </div>
                    <div id="blogger-box-info-blog-url">
                        <a href='<?php echo $user_blog_url ?>' target="_blank"><?php echo $user_blog_url ?></a>
                    </div>
                </div>
            </div>
            <?php
        }        
        ?>
        <div id="blogger-box-bottom-empty-fix"></div>
    </div>
</div>