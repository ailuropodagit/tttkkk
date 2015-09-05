<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Barcode extends CI_Controller
{
    public function index()
    {                
        //<img src="<?php echo base_url('barcode/generate/1234');"  alt="not show" style="margin-top:20px; margin-left:90%;"/>
    }

    public function generate($code)
    {
        //load library
        $this->load->library('zend');
        //load in folder Zend
        $this->zend->load('Zend/Barcode');
        //generate barcode
        //Zend_Barcode::render('code128', 'image', array('text'=>$code), array());
        $barcodeOptions = array('text' => $code);
        $rendererOptions = array('imageType'          =>'png', 
                                 'horizontalPosition' => 'center', 
                                 'verticalPosition'   => 'middle');
        $imageResource= Zend_Barcode::factory('code39', 'image', $barcodeOptions, $rendererOptions)->render();
        return $imageResource; 
    }
}

