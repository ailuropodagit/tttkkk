<div id="faq">
    <h1>FAQ</h1>
    <?php
    $result_array_faq = $query_faq->result_array();
    $num_rows_faq = $query_faq->num_rows();
    if ($num_rows_faq)
    {
        foreach($result_array_faq as $faq)
        {
            $faq_question = $faq['faq_question'];
            $faq_answer = $faq['faq_answer']; 
            ?>
            <p id="faq-question">
                <b><?php echo $faq_question ?></b>
            </p>
            <p id="faq-answer">
                <?php echo $faq_answer ?>
            </p>
            <?php
        }
    }
    ?>
    For further more enquiry email: help@keppo.my or <a href='http://keppo.my/contact-us'>http://keppo.my/contact-us</a>
</div>