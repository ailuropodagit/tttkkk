        <div id='footer'>
            <div id='footer-navigation'>
                <div id='wrapper'>
                    <div id='footer-navigation-block1'>
                        <div id='footer-navigation-block1-logo'><?php echo img('image/logo-white.png') ?></div>
                        <div id='footer-navigation-block-content'>
                            Keppo.my is publish on 19 July 2015, Founder by Jimmy See. His create this to help's SME Corp 
                            to increasing sale on advertising. Keppo.my is used hybird technilogy on media social faster 
                            way to user's to know more product and services.
                        </div>
                    </div>
                    <div id='footer-navigation-block2'>
                        <div id='footer-navigation-block2-up'>
                            <div id="footer-navigation-block-title">Contact</div>
                            <div id="footer-navigation-block-title-bottom-line"></div>
                            <div id='footer-navigation-block-content'>
                                <div id='footer-navigation-block2-row1'>
                                    <div id='footer-navigation-block2-icon'><i class="fa fa-envelope"></i></div>
                                    <div id='footer-navigation-block2-text'>
                                        <div id='footer-navigation-block2-text-label'>E-mail</div>
                                        <div id='footer-navigation-block2-text-data'>
                                        <?php
                                            $keppo_admin_email = $this->m_custom->web_setting_get('keppo_admin_email', 'set_desc');
                                            echo $keppo_admin_email;
                                        ?>
                                        </div>
                                    </div>
                                </div>
                                <div id='footer-navigation-block2-row2'>
                                    <div id='footer-navigation-block2-icon'><i class="fa fa-phone"></i></div>
                                    <div id='footer-navigation-block2-text'>
                                        <div id='footer-navigation-block2-text-label'>Phone</div>
                                        <div id='footer-navigation-block2-text-data'>
                                        <?php
                                            $keppo_company_phone = $this->m_custom->web_setting_get('keppo_company_phone', 'set_desc');
                                            echo $keppo_company_phone;
                                        ?>
                                        </div>
                                    </div>
                                </div>
                                <div id='footer-navigation-block2-row3'>
                                    <div id='footer-navigation-block2-icon'><i class="fa fa-home"></i></div>
                                    <div id='footer-navigation-block2-text'>
                                        <div id='footer-navigation-block2-text-label'>Address</div>
                                        <div id='footer-navigation-block2-text-data'>
                                        <?php
                                            $keppo_company_address = $this->m_custom->web_setting_get('keppo_company_address', 'set_desc');
                                            echo $keppo_company_address;
                                        ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id='footer-navigation-block3'>
                        <div id="footer-navigation-block-title">Information</div>
                        <div id="footer-navigation-block-title-bottom-line"></div>
                        <div id='footer-navigation-block3-navigation'>
                             <ul>
                                <li>
                                    <a href='<?php echo base_url() ?>about-us'>
                                        <div id='footer-navigation-block3-navigation-icon'><i class="fa fa-caret-right"></i></div>
                                        <div id='footer-navigation-block3-navigation-text'>About us</div>
                                    </a>
                                </li>
                                <li>
                                    <a href='<?php echo base_url() ?>contact-us'>
                                        <div id='footer-navigation-block3-navigation-icon'><i class="fa fa-caret-right"></i></div> 
                                        <div id='footer-navigation-block3-navigation-text'>Contact us</div>
                                    </a>
                                </li>
                                <li>
                                    <a href='<?php echo base_url() ?>faq'>
                                        <div id='footer-navigation-block3-navigation-icon'><i class="fa fa-caret-right"></i></div> 
                                        <div id='footer-navigation-block3-navigation-text'>FAQ</div>
                                    </a>
                                </li>
                                <li>
                                    <a href='<?php echo base_url() ?>terms-of-service'>
                                        <div id='footer-navigation-block3-navigation-icon'><i class="fa fa-caret-right"></i></div> 
                                        <div id='footer-navigation-block3-navigation-text'>Terms of service</div>
                                    </a>
                                </li>
                                <li>
                                    <a href='<?php echo base_url() ?>privacy-policy'>
                                        <div id='footer-navigation-block3-navigation-icon'><i class="fa fa-caret-right"></i></div> 
                                        <div id='footer-navigation-block3-navigation-text'>Privacy policy</div>
                                    </a>
                                </li>
                                <li>
                                    <a href='<?php echo base_url() ?>merchant/register'>
                                        <div id='footer-navigation-block3-navigation-icon'><i class="fa fa-caret-right"></i></div> 
                                        <div id='footer-navigation-block3-navigation-text'>Merchant Register</div>
                                    </a>
                                </li>
                                <li>
                                    <a href='<?php echo base_url() ?>merchant/login'>
                                        <div id='footer-navigation-block3-navigation-icon'><i class="fa fa-caret-right"></i></div> 
                                        <div id='footer-navigation-block3-navigation-text'>Merchant Login</div>
                                    </a>
                                </li>
                             </ul>
                         </div>
                    </div>
                    <div id='footer-navigation-block4'>
                        <div id="footer-navigation-block-title">Like Our Facebook Page</div>
                        <div id="footer-navigation-block-title-bottom-line"></div>
                        <br/>
                        <div id="fb-root"></div>
                        <script>
                        var fb_appID = "<?php echo fb_appID(); ?>";            
                        (function(d, s, id) {
                          var js, fjs = d.getElementsByTagName(s)[0];
                          if (d.getElementById(id)) return;
                          js = d.createElement(s); js.id = id;
                          js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5&appId=1682555468669559";
                          fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));
                        </script>

            <div class="fb-page" data-href="https://www.facebook.com//keppo.my" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/keppo.my"><a href="https://www.facebook.com/keppo.my">Keppo.my</a></blockquote></div></div>
                    </div>
                    <div id="float-fix"></div>                    
                </div>
            </div>
            <div id="footer-bar">
                <div id="wrapper">
                    &copy 2015 Fuyoo Advertising and Services. All Rights Reserved.
                </div>
            </div>
        </div>

        <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url('js/slick-slider/slick.min.js') ?>"></script>

    </body>
</html>