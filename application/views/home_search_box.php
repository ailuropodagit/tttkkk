<script>
    var keppo_path = '<?php echo $this->config->item('keppo_path'); ?>';
    $(function () {
        //AUTO COMPLETE
        $(".search_word_input").each(function () {
            $(this).autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: keppo_path + "search_suggestion/get_merchant_list/",
                        data: {term: request.term},
                        dataType: "json",
                        type: "POST",
                        success: function (data) {
                            var resp = $.map(data, function (obj) {
                                return obj.tag;
                            });
                            response(data);
                        }
                    });
                }
            });
        });
    });

    function set_halal(dep_selected)
    {
        //var dep_selected = $('select[name=is_halal]').val();
        var post_url = "<?php echo base_url(); ?>" + 'all/set_halal/' + dep_selected;
        $.ajax({
            type: 'POST',
            url: post_url,
            dataType: 'html',
            success: function (data)
            {
                $(location).attr('href', '<?php echo base_url() . uri_string(); ?>')
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                //alert(textStatus);
                //alert(errorThrown);
            }
        });
    }
</script>

<style>
    .ui-autocomplete {
        z-index:200;
    }
</style>

<?php echo form_open('all/home_search') ?>
<div id="header-logo-bar-search-block1">
    <input type="text" placeholder="Search: advertisement, redemption" name="search_word" id="search_word" class="search_word_input">
</div>
<div id="header-logo-bar-search-block2">
    <?php
    $header_search_state_list = $this->m_custom->get_static_option_array('state', '0', 'All');
    $header_search_me_state_id = array(
        'name' => 'me_state_id',
        'id' => 'me_state_id',
    );
    $header_search_selected_state = $this->uri->segment(4);
    if (!empty($header_search_selected_state) && $this->router->fetch_method() == 'home_search')
    {
        echo form_dropdown($header_search_me_state_id, $header_search_state_list, $header_search_selected_state);
    } else
    {
        echo form_dropdown($header_search_me_state_id, $header_search_state_list);
    }

    $is_halal = 0;
    if ($this->session->userdata('is_halal'))
    {
        $is_halal = $this->session->userdata('is_halal');
    }
    $is_all_text = $is_halal == 0 ? 'selected="selected"' : '';
    $is_halal_text = $is_halal == 191 ? 'selected="selected"' : '';
    $is_porkfree_text = $is_halal == 192 ? 'selected="selected"' : '';
    ?>
</div>
<div id="header-logo-bar-search-block3">
    <button name="button_action" type="submit" value="search">Search</button>
</div>
<select name="is_halal" id="is_halal" style="width:100px;background-color:#9BED99" onchange="set_halal(this.value)">
    <option value="0" <?php echo $is_all_text; ?> >Non-Halal</option>
    <option value="191" <?php echo $is_halal_text; ?> >Halal</option>
    <option value="192" <?php echo $is_porkfree_text; ?> >Pork-Free</option>
</select>
<?php echo form_close();