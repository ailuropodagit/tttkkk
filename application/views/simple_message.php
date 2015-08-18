  <?php    $this->load->view('template/header');?>

<div id='wrapper'>
    <div id='body'>
        <div id='body-content'>
            
        <h1>  <?php echo $title; ?></h1>

<p><?php echo $sentence1; ?>
    <?php echo $sentence2; ?></br></p>
    
 <?php echo anchor($back_page_url, $back_page); ?>

   </div>
    </div>
</div>


       <?php      $this->load->view('template/footer');?>