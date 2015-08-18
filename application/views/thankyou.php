  <?php    $this->load->view('template/header');?>

<div id='wrapper'>
    <div id='body'>
        <div id='body-content'>
            
        <h1> Thank you! </h1>

<p>An email will be sent to your registered email address.<br/>
    If you don't receive in the next 10 minutes, please check your spam folder and if you still haven't received it please try again...</br></br>
    
 <?php echo anchor($back_page, 'Go to Log In Page'); ?>

   </div>
    </div>
</div>


       <?php      $this->load->view('template/footer');?>