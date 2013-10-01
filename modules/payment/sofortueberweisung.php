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

$this->loadLanguageFile('payment_sofortueberweisung');

class sofortueberweisung extends \Module {
    protected $paymentName = 'Sofort&uuml;berweisung';
    protected $strTemplate = 'cao_sofortueberweisung';
    private $SofortLib = null;

    public function __construct()
    {
    }

    public function getConfigData() {
        $arrConfig = array(
            'user_id' => array
            (
                'label'                   => &$GLOBALS['TL_LANG']['payment_sofortueberweisung']['user_id'],
                'inputType'               => 'text',
                'search'                  => true,
                'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
            ),
            'project_id' => array
            (
                'label'                   => &$GLOBALS['TL_LANG']['payment_sofortueberweisung']['project_id'],
                'inputType'               => 'text',
                'search'                  => true,
                'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
            ),
            'api_key' => array
            (
                'label'                   => &$GLOBALS['TL_LANG']['payment_sofortueberweisung']['api_key'],
                'inputType'               => 'text',
                'search'                  => true,
                'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
            )
        );

        return $arrConfig;
    }

    public function getPaymentInfo() {
        return $this->paymentName;
    }

    public function generateForm($payment_id) {
        $paymentForm =        
        '<form method="post" action="%s">
         <input type="hidden" name="FORM_SUBMIT" value="tl_acquistoShop_warenkorb">
         <input type="hidden" name="REQUEST_TOKEN" value="' . REQUEST_TOKEN . '">
        <input type="hidden" value="%01.2f" name="paymentAmount">
         <input type="hidden" name="do" value="pay">
        <input type="submit" value="Kaufen">
        </form>';
        
        return $paymentForm;

    }

    public function getConfig($payment_id) {
        $this->Import('Database');
        $arrConfig = $this->Database->prepare("SELECT * FROM tl_shop_zahlungsarten WHERE id = ?")->limit(1)->execute($payment_id);
        return unserialize($arrConfig->configData);
    }

    public function pay($payment_id) {
        $arrConfig = $this->getConfig($payment_id);
        $objCurrency = \AcquistoShop\acquistoShopCosts::getCurrency(\AcquistoShop\acquistoShopCosts::getSelectedCurrency());      
        $this->Import('Input');

        require_once(dirname(__FILE__) . '/sofortueberweisung/sofortLib.php');               
        $this->SofortLib = new SofortLib_Multipay($arrConfig['user_id'] . ":" . $arrConfig['project_id'] . ":" . $arrConfig['api_key']);
        $this->SofortLib->setSofortueberweisung();
        $this->SofortLib->setAmount($this->Input->Post('paymentAmount'), $objCurrency->iso_code);
        $this->SofortLib->setReason('testueberweisung', 'verwendungszweck 2');        
        $this->SofortLib->setSuccessUrl(str_replace("?do=check-and-order", "?do=pay&returnState=true", $this->Environment->url . $this->Environment->requestUri));
        $this->SofortLib->setAbortUrl($this->Environment->url . $this->Environment->requestUri);
        $this->SofortLib->setTimeoutUrl($this->Environment->url . $this->Environment->requestUri);
        $this->SofortLib->setNotificationUrl($this->Environment->url . $this->Environment->requestUri);
        $this->SofortLib->sendRequest();

        if($this->SofortLib->isError()) {
        	//PNAG-API didn't accept the data
        	echo $this->SofortLib->getError();
        } else {
        	//buyer must be redirected to $paymentUrl else payment cannot be successfully completed!
        	$paymentUrl = $this->SofortLib->getPaymentUrl();
        	header('Location: '.$paymentUrl);
        }

        $this->paymentState = false;
        return true;
    }

    public function compile()
    {

    }
}

?>