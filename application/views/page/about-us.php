<div id="about-us">
    <h1>About Us</h1>
    <div id="about-us-logo">
        <img src="<?php echo base_url(); ?>image/logo-red.png" id="about-us-logo-img">
    </div>

    <p id="about-us-title">
        <b>How it all Began</b>
    </p>
    <p id="about-us-content">
        This page was created for SME corps to enhance sales
        via advertising. Keppo.my currently using latest-faster hybrid technology on social media
        that can factitive users to discover more product and services.
    </p>
    <p id="about-us-title">
        <b>why name Keppo.my?</b>
    </p>
    <p id="about-us-content">
        K = Knowing, E = Every, P = People, P = Particular, O = Object & MY = Malaysia.
        It's Indonesian slang, which is comes from Hokkien language (usually used by some
        communities in Medan, Palembang, and Pekanbaru) and then become a loanword in Singlish
        (Singaporean-English)
    </p>
    <p id="about-us-content">
        "Kappo" which means "really curious" defines a condition when a person is want to know
        about everything.
    <p>
    <p id="about-us-title">
        <b>"Keppo.my" Core Business Overview, Aims and Objectives</b>
    </p>
    <p id="about-us-content">
        “Keppo.my” core business as illustrated in the executive summary appended above would
        essentially focus on providing marketing (web, e-business & mobile) solutions to SME’s
        targeted around hotspots in Malaysia. Essentially the crust of the business entails a hybrid
        approach combining elements of Customer Relationship Management (CRM) with that of
        enterprise advertising solutions.
    </p>
    <p id="about-us-content">
        The baseline target market of “keppo.my” will initially be targeted SME’s around Malaysia
        providing them the above solutions with the sole intention of generating revenue for the
        client (retailer’s) and to allow them access to CRM infrastructure previously unavailable to
        them due to the cost implications and expertise required to run such initiatives.
    </p>




    <div id='float-fix'></div>

    <p>
        <b><?php
            $keppo_company_name = $this->m_custom->web_setting_get('keppo_company_name', 'set_desc');
            echo $keppo_company_name;
            ?></b> (LLP0007098-LGN) <br/>
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