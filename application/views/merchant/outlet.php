<head>
<!--    <link rel="stylesheet" href="<?php //echo base_url()   ?>assets/datatables/css/jquery.dataTables.css">
<script type="text/javascript" src='<?php //echo base_url()   ?>assets/datatables/js/jquery.dataTables.js'></script>
<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script> -->

</head>

<div id="infoMessage"><?php echo $message; ?></div>

<img src="<?php echo base_url($logo_url); ?>" id='header-logo-img'><br/>

<div id="success-message-title">
    <?php echo $company_name; ?>
</div>
<div id="success-message-subtitle">
    <?php echo $address; ?>
</div>
<div id="success-message-paragraph">
    <?php echo 'Phone: ' . $phone; ?>
</div>
<div id="success-message-paragraph">
    <?php echo 'Website: ' . anchor_popup($website_url, $website_url); ?>
</div>
<div id="success-message-paragraph">
    <?php echo 'Facebook URL: ' . anchor_popup($facebook_url, $facebook_url); ?>
</div>

<?php echo form_open(uri_string()); ?>
<div id="search">
    <div id="search-content">
        <div id="search-content-box">
            <div id="search-content-box-content">
                <div id="search-box-block1">
                    <input type="text" placeholder="Search Location" name="search_word">
                    <span id="search-icon"><i class="fa fa-search"></i></span>
                </div>
                <div id="search-box-block3">
                    <button name="button_action" type="submit" value="search_branch" >Search</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<div id="float-fix"></div>
<style>
    .leftbox{
        width:50%;
        float:left;
        margin:10px;
        border:1px solid black;
    }
    .rightbox{
        width:10%;
        float:left;
        margin:15px;
    }
</style>


<?php
foreach ($branch_list as $one_row) {
    echo '<div>';
    echo '<div class="leftbox"><b>' . $one_row->name . '</b><br/>';
    echo $one_row->address . '<br/>';
    echo '<a style="color:blue" href="#">Tel. ' . $one_row->phone . '</a></div>';
    echo '<div class="rightbox">' . anchor_popup(base_url() . 'merchant/map/' . $one_row->branch_id, 'View Map') . '</div>';
    echo '</div><br/><br/>';
}
?>
</tbody>
