<?php 
$this->load->view('template/sidebar_left');
        
        //Example of show all company under the Category in Banner Home Page
        $abc = $this->m_custom->getMerchantList_by_category(2);
        foreach ($abc as $one_row) {
            echo "<a href=".base_url()."merchant/dashboard/".$one_row->slug.">".$one_row->company."</a><br/>";
        }
?>