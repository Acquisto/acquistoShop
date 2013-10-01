<?php 

/**
 * The AcquistoShop extension allows the creation a OnlineStore width 
 * the OpenSource Contao CMS. For Question visit our Website under
 * http://www.contao-acquisto.de  
 *
 * PHP version 5
 * @package	   AcquistoShop
 * @subpackage Payment
 * @author     Sascha Brandhoff <brandhoff@contao-acquisto.de>
 * @copyright  AcquistoShop
 * @license    LGPL.
 * @filesource
 */
 
#namespace AcquistoShop\Payment;

class vorkasse extends \Module {
    protected $paymentName = 'Vorkasse';

    public function __construct()
    {
#        parent::__construct();
    }

    public function getConfigData() {
    }

    public function getPaymentInfo() {
        return $this->paymentName;
    }

    public function generateForm($payment_id) {
        return '<form method="get" action="%s">
         <input type="hidden" name="FORM_SUBMIT" value="tl_acquistoShop_warenkorb">
         <input type="hidden" name="REQUEST_TOKEN" value="' . REQUEST_TOKEN . '">
         <input type="hidden" name="do" value="pay">
         <input type="submit" value="Kaufen">
         </form>';
    }

    public function pay($payment_id) {
        $this->paymentState = false;
        return true;
    }

    public function compile()
    {

    }
}


?>