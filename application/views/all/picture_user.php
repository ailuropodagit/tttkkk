<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/jgrowl/jquery.jgrowl.js"></script>
<?php echo link_tag('js/jgrowl/jquery.jgrowl.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<div id='picture-user'>
    <h1><?php echo $page_title; ?></h1>
    <div id='picture-user-content'>
        <div id="picture-user-edit-link">
            <?php
            if (check_is_login())
            {
                $user_id = $this->ion_auth->user()->row()->id;
                $allowed_list = $this->m_custom->get_list_of_allow_id('user_album', 'user_id', $user_id, 'user_album_id');
                if (check_correct_login_type($this->config->item('group_id_user'), $allowed_list, $picture_id))
                {
                    ?>
                    <a href='<?php echo base_url() . "user/edit_user_picture/" . $picture_id ?>' >Edit Picture</a>
                    <?php
                }
            }
            ?>
        </div>
        <div id="float-fix"></div>
        <div id='picture-user-table'>
            <div id='picture-user-table-row'>
                <div id='picture-user-table-row-cell' class='picture-user-left-cell'>
                    <div id='picture-user-left'>
                        <?php
                        if (!empty($previous_url))
                        {
                            ?><a href="<?php echo $previous_url ?>"><i class="fa fa-angle-double-left"></i></a><?php
                        }
                        else 
                        {
                            ?><div id='picture-user-left-gray'><i class="fa fa-angle-double-left"></i></div><?php
                        }
                        ?>
                    </div>
                </div>
                <div id='picture-user-table-row-cell' class='picture-user-center-cell'>
                    <div id='picture-user-center'>
                        <div id="picture-user-title">
                            <?php echo $title ?>
                        </div>
                        <div id='picture-user-photo-box'>
                            <img src='<?php echo $image_url ?>'>
                        </div>
                        <div id="picture-user-rate-upload-by">
                            <div id="picture-user-rate">
                                <?php
                                echo form_input($item_id);
                                echo form_input($item_type);
                                for ($i = 1; $i <= 5; $i++)
                                {
                                    if ($i == round($average_rating))
                                    {
                                        echo "<input class='auto-submit-star' type='radio' name='rating' " . $radio_level . " value='" . $i . "' checked='checked'/>";
                                    }
                                    else
                                    {
                                        echo "<input class='auto-submit-star' type='radio' name='rating' " . $radio_level . " value='" . $i . "'/>";
                                    }
                                }
                                ?>
                            </div>
                            <div id="picture-user-upload-by">
                                Upload by : <?php echo $user_name_url; ?>
                            </div>
                            <div id="float-fix"></div>
                        </div>
                        <div id="picture-user-description">
                            <?php echo $description ?>
                        </div>
                        <div id="picture-user-like-comment-share">
                            <div id="picture-user-like">
                                <?php echo $like_url; ?>
                            </div>
                            <div id="picture-user-comment">
                                <?php echo $comment_url; ?>
                            </div>
                            <div id="picture-user-share">
                                Share :
                                <span id="picture-user-share-facebook">
                                    <i class="fa fa-facebook-square"></i>
                                </span>
                            </div>
                            <div id="float-fix"></div>
                        </div>
                        <div id="picture-user-comment-list">
                            <?php
                            $this->load->view('all/comment_form');
                            ?>
                        </div>
                    </div>
                </div>
                <div id='picture-user-table-row-cell' class='picture-user-right-cell'>
                    <div id='picture-user-right'>
                        <?php
                        if (!empty($next_url))
                        {
                            ?><a href="<?php echo $next_url ?>"><i class="fa fa-angle-double-right"></i></a><?php
                        }
                        else 
                        {
                            ?><div id='picture-user-right-gray'><i class="fa fa-angle-double-right"></i></div><?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>