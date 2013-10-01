<?php
 
/**
 * The AcquistoShop extension allows the creation a OnlineStore width 
 * the OpenSource Contao CMS. For Question visit our Website under
 * http://www.contao-acquisto.de  
 *
 * PHP version 5
 * @package	   AcquistoShop
 * @subpackage Controller
 * @author     Sascha Brandhoff <brandhoff@contao-acquisto.de>
 * @copyright  AcquistoShop
 * @license    LGPL.
 * @filesource
 */
 
namespace AcquistoShop;  

class acquistoShopPayment extends \Controller {
    public $paymentState;

    public function __construct()
    {
        parent::__construct();
        $this->Import('Database');
    }

    public function frontendTemplate($paymentModule, $payment_id) {
        if($paymentModule) {
            require_once(TL_ROOT . '/system/modules/acquistoShop/modules/payment/' . $paymentModule . '.php');
            $objModule = new $paymentModule();
            $objTemplate = new \FrontendTemplate($objModule->strTemplate);
            return $objModule->generateForm($payment_id);
        } else {
            return '';
        }
    }

    public function pay($paymentModule, $payment_id) {
        require_once(TL_ROOT . '/system/modules/acquistoShop/modules/payment/' . $paymentModule . '.php');
        $objModule = new $paymentModule();
        $returnVal = $objModule->pay($payment_id);

        if($returnVal) {

        }

        return $returnVal;
    }
    
    public function openCallback($callback, $paymentModule, $payment_id) {
        require_once(TL_ROOT . '/system/modules/acquistoShop/modules/payment/' . $paymentModule . '.php');
        $objModule = new $paymentModule();
        $returnVal = $objModule->$callback($payment_id);
        return $returnVal;
    
    }

    public function getModules() {
        if ($handle = opendir(TL_ROOT . '/system/modules/acquistoShop/modules/payment/'))
        {
            while (false !== ($file = readdir($handle)))
            {
                if ($file != "." && $file != ".." && is_file(TL_ROOT . '/system/modules/acquistoShop/modules/payment/' . $file))
                {
                    require_once(TL_ROOT . '/system/modules/acquistoShop/modules/payment/' . $file);
                    $strPaymod = substr($file, 0, -4);
                    $objPaymod = new $strPaymod();
                    $arrModules[$strPaymod] = $objPaymod->getPaymentInfo();
                }
            }

            closedir($handle);
        }

        return $arrModules;
    }
}

?>