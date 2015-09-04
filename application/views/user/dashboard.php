<div id="infoMessage"><?php echo $message; ?></div>

<div id="dashboard">
    <h1>Dashboard</h1>
    <div id="dashboard-content">
        <div id="dashboard-photo">
            <?php            
            if($image === "")
            {
                ?>
                <img src="<?php echo base_url() ?>image/image-empty.jpg">
                <?php
            }
            else
            {
                ?>
                <img src="<?php echo base_url() . $image_path . $image ?>">
                <?php
            }
            ?>
        </div>
        <div id="dashboard-info">
            <div id="dashboard-info-title">
                <?php echo $first_name.' '.$last_name; ?>
            </div>
        </div>
    </div>    
</div>