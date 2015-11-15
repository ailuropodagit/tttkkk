<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="profile">
    <h1>Manage Redemption Term & Condition</h1>
    <div id='profile-content'>  
        <?php
        $this->load->view('admin/manage_setting_sub_menu');
        ?>
        
    </div>
</div>