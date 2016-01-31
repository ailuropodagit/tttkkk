<div id="about-us">
    <h1>About Us</h1>
    <div id="about-us-logo">
        <img src="<?php echo base_url(); ?>image/logo-red.png" id="about-us-logo-img">
    </div>
    <p>
        Keppo.my is publish on 19 July 2015, Founder by Jimmy See. His create this to help's SME Corp to increasing
        sale on advertising. Keppo.my is used hybird technilogy on media social faster way to user's to know more
        product and services.
    </p>
    <p>
        Keppo.my is B2B2C marketplace on media social to boots up Malaysia economic. This platform is face-to-face
        delivering information to user's. User;s able to collect candies from merchants (example: likes, Rating, view 
        advertisement, share on facebook or instagram). 
    </p>    
    
    <div id='float-fix'></div>
    
    <p>
        <b><?php
            $keppo_company_name = $this->m_custom->web_setting_get('keppo_company_name', 'set_desc');
            echo $keppo_company_name;
        ?></b> ( 002422825-U) <br/>
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
        Website: <a href='home'>www.keppo.my</a>
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