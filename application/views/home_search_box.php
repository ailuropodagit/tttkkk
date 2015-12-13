<?php echo form_open('all/home_search') ?>
    <div id="header-logo-bar-search-block1">
        <input type="text" placeholder="Search: shop name, product, hot deal, redemption" name="search_word" id="search_word">
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
