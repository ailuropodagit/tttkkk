<script>
            var keppo_path = '<?php echo $this->config->item('keppo_path'); ?>'; 
            $(function(){
                //AUTO COMPLETE
                $("#search_word").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: keppo_path + "search_suggestion/get_merchant_list/",
                            data: { term: $("#search_word").val()},
                            dataType: "json",
                            type: "POST",
                            success: function(data){
                                var resp = $.map(data,function(obj){                     
                                    return obj.tag;                  
                                });
                                response(data);
                            }
                        });
                    }
                });
            });  
</script>

<style>
.ui-autocomplete {
    z-index:200;
}
</style>

<?php echo form_open('all/home_search') ?>
    <div id="header-logo-bar-search-block1">
        <input type="text" placeholder="Search: advertisement, redemption" name="search_word" id="search_word">
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
        }
        else
        {
            echo form_dropdown($header_search_me_state_id, $header_search_state_list);
        }
        ?>
    </div>
    <div id="header-logo-bar-search-block3">
        <button name="button_action" type="submit" value="search">Search</button>
    </div>
<?php echo form_close() ?>
