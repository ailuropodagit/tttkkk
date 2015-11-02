<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Search_suggestion extends CI_Controller
{
    public function get_merchant_list()
    {
        if (isset($_POST) && !empty($_POST))
        {
            $term = $this->input->post('term', TRUE);
            $this->m_custom->home_search_get_merchant($term);
        }
    }
}
