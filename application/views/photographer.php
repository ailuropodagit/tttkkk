<?php
//CONFIG DATA
$user_profile_path = $this->config->item('album_user_profile');
$empty_image = $this->config->item('empty_image');
?>

<div id="photographer">
    <h1>Photographer</h1>
    <div id="photographer-content">
        <!--SEARCH-->
        <div id="photographer-search">
            <?php echo form_open() ?>
            <div id="photographer-search-input"><?php echo form_input($keyword) ?></div>
            <div id="photographer-search-dropdown"><?php echo form_dropdown($the_type, $photography_list, $the_type_selected); ?></div>
            <div id="photographer-search-submit"><input type="submit" name="search" value="Search"></div>
            <div id="photographer-search-clear"><a href='<?php echo current_url() ?>' class="a-href-button">Clear</a></div>
            <?php echo form_close() ?>
            <div class="float-fix"></div>
        </div>
        <!--PHOTOGRAPHER-->
        <div id="photographer-box">
            <?php
            //QUERY USER
            $result_array_blogger = $query_blogger->result_array();
            $num_rows_blogger = $query_blogger->num_rows();
            if ($num_rows_blogger)
            {
                foreach($result_array_blogger as $blogger)
                {
                    $user_id = $blogger['id'];
                    $user_name = $blogger['first_name'] . ' ' . $blogger['last_name'];
                    $user_profile_image = $blogger['profile_image'];
                    $user_blog_url = $blogger['us_photography_url'];
                    $photography_list = $this->m_custom->many_get_childlist_detail('photography',$user_id,'dynamic_option','option_desc', 1);
                    ?>
                    <div id="photographer-box-each">
                        <table border="0" style="table-layout: fixed; width: 100%;">
                            <tr>
                                <td valign="top" style="width: 80px;">
                                    <div id="photographer-box-each-photo-box">
                                        <a href="<?php echo base_url("all/user_dashboard/$user_id") ?>">
                                            <?php 
                                            if($user_profile_image)
                                            {
                                                echo img("$user_profile_path/$user_profile_image");
                                            }
                                            else
                                            {
                                                if ($photographer['us_gender_id'] == $this->config->item('gender_id_male'))
                                                {
                                                    $image = $this->config->item('empty_image_male');
                                                }
                                                else
                                                {
                                                    $image = $this->config->item('empty_image_female');
                                                }
                                                echo img($image);
                                            }
                                            ?>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <div id="photographer-box-each-info">
                                        <div id="photographer-box-each-info-name">
                                            <a href="<?php echo base_url("all/user_dashboard/$user_id") ?>">
                                                <?php echo $user_name ?>
                                            </a>
                                        </div>
                                        <div id="photographer-box-each-info-blog-url">
                                            <a href='<?php echo display_url($user_blog_url) ?>' target="_blank"><?php echo $user_blog_url ?></a>
                                        </div>
                                        <div id="photographer-box-each-info-blog-url" style='white-space:initial'>
                                            <?php echo $photography_list ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php
                }
                ?><div id="photographer-box-bottom-empty-fix"></div><?php
            }
            else
            {
                ?><div id="empty-message">No Photographer</div><?php
            }
            ?>
        </div>
    </div>
</div>