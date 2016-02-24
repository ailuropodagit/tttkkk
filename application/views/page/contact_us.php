<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="contact-us">
    <h1>Contact Us</h1>
    <div id="contact-us-left">
        <div id="contact-us-left-map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3984.4512792517303!2d101.58267209999997!3d2.9721945000000067!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cdb3c617d10d9d%3A0xd1f9334298ee1c49!2s4%2C+Jalan+Industri+Mas+5%2C+Taman+Mas%2C+47100+Puchong%2C+Selangor!5e0!3m2!1sen!2smy!4v1439815764511" width="100%" height="300" frameborder="0" style="border: 0; display: block;" allowfullscreen></iframe>
        </div>
        <p>
            <b><?php
            $keppo_company_name = $this->m_custom->web_setting_get('keppo_company_name', 'set_desc');
            //echo $keppo_company_name;
            ?></b> Keppo eMarketing PLT (LLP0007098-LGN) <br/>
            <?php
                $keppo_company_address = $this->m_custom->web_setting_get('keppo_company_address', 'set_desc');
                echo $keppo_company_address;
            ?>
        </p>
        <p>
            Tel: <?php
                    $keppo_company_phone = $this->m_custom->web_setting_get('keppo_company_phone', 'set_desc');
                    echo $keppo_company_phone;
                ?> <br/>
            Fax: <?php
                $keppo_company_fax = $this->m_custom->web_setting_get('keppo_company_fax', 'set_desc');
                echo $keppo_company_fax;
            ?> <br/>
            E-mail: <?php
                $keppo_admin_email = $this->m_custom->web_setting_get('keppo_admin_email', 'set_desc');
                echo $keppo_admin_email;
            ?> <br/>
            Website: <a href='home'>http://www.keppo.my</a>
        </p>
        <div id="contact-us-left-social-media-icon">
            <div id="contact-us-left-social-media-icon-facebook">
                <a href="https://www.facebook.com/" target="_blank"><i class="fa fa-facebook-square"></i></a>
            </div>
            <div id="contact-us-left-social-media-icon-instagram">
                <a href="https://instagram.com" target="_blank"><i class="fa fa-instagram"></i></a>
            </div>
            <div id="contact-us-left-social-media-icon-linkedin">
                <a href="https://www.linkedin.com/" target="_blank"><i class="fa fa-linkedin-square"></i></a>
            </div>
            <div id="float-fix"></div>
        </div>
    </div>
    <div id="contact-us-right">
        <div id="contact-us-right-form">
            <?php echo form_open(uri_string()); ?>      
                <div id="contact-us-right-form-each">
                    <input type="text" placeholder="Name*" name="name">
                </div>
                <div id="contact-us-right-form-each">
                    <input type="text" placeholder="E-mail*" name="email">
                </div>
                <div id="contact-us-right-form-each">
                    <input type="text" placeholder="Contact Number" name="phone">
                </div>
                <div id="contact-us-right-form-each">
                    <input type="text" placeholder="Subject*" name="subject">
                </div>
                <div id="contact-us-right-form-each">
                    <textarea placeholder="Message" name="message"></textarea>
                </div>
                <button name="button_action" type="submit" value="send" style="float:right">Send</button>
            <?php echo form_close(); ?>
            <div id="float-fix"></div>
        </div>
    </div>
    <div id="float-fix"></div>
</div>