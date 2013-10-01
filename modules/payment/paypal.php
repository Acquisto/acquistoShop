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
 
$this->loadLanguageFile('payment_paypal');

define('USE_PROXY',FALSE);
define('PROXY_HOST', '127.0.0.1');
define('PROXY_PORT', '808');

class paypal extends Module {
    protected $paymentName   = 'Paypal';
    protected $strTemplate   = 'cao_paypal';
    protected $paypalVersion = '53.0';
    private $API_SIGNATURE   = null;
    private $API_USERNAME    = null;
    private $API_PASSWORD    = null;
    private $PAYPAL_URL      = null;
    private $API_ENDPOINT    = null;
    

    public function __construct()
    {
#        parent::__consturct();
    }

    public function getConfigData() {
        $arrConfig = array(
            'API_USERNAME' => array
            (
                'label'                   => &$GLOBALS['TL_LANG']['payment_paypal']['API_USERNAME'],
                'inputType'               => 'text',
                'search'                  => true,
                'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
            ),
            'API_PASSWORD' => array
            (
                'label'                   => &$GLOBALS['TL_LANG']['payment_paypal']['API_PASSWORD'],
                'inputType'               => 'text',
                'search'                  => true,
                'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
            ),
            'API_SIGNATURE' => array
            (
                'label'                   => &$GLOBALS['TL_LANG']['payment_paypal']['API_SIGNATURE'],
                'inputType'               => 'text',
                'search'                  => true,
                'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
            ),
            'PAYPAL_TESTMODUS' => array
            (
                'label'                   => &$GLOBALS['TL_LANG']['payment_paypal']['PAYPAL_TESTMODUS'],
                'inputType'               => 'checkbox',
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
        $objCurrency = \AcquistoShop\acquistoShopCosts::getCurrency(\AcquistoShop\acquistoShopCosts::getSelectedCurrency());
        $paymentForm  = '<form method="post" action="%s">';
        $paymentForm .= '<input type="hidden" name="FORM_SUBMIT" value="tl_acquistoShop_warenkorb">';
        $paymentForm .= '<input type="hidden" name="REQUEST_TOKEN" value="' . REQUEST_TOKEN . '">';
        $paymentForm .= '<input type="hidden" name="paymentType" value="Sale" >';
        $paymentForm .= '<input type="hidden" value="%01.2f" name="paymentAmount">';
        $paymentForm .= '<input type="hidden" value="' . $objCurrency->iso_code . '" name="currencyCodeType">';
        $paymentForm .= '<input type="submit" value="Kaufen">';
        $paymentForm .= '</form>';

        return $paymentForm;
    }

    public function getConfig($payment_id) {
        $this->Import('Database');
        $arrConfig = $this->Database->prepare("SELECT * FROM tl_shop_zahlungsarten WHERE id = ?")->limit(1)->execute($payment_id);
        return unserialize($arrConfig->configData);
    }
    
    public function ReviewOrder($payment_id) {
        $this->setConfig($payment_id);
		 /** 
          * At this point, the buyer has completed in authorizing payment
		  * at PayPal.  The script will now call PayPal with the details
		  * of the authorization, incuding any shipping information of the
		  * buyer.  Remember, the authorization is not a completed transaction
		  * at this state - the buyer still needs an additional step to finalize
		  * the transaction
		  **/
        $token = urlencode($_REQUEST['token']);

 
		 /* Build a second API request to PayPal, using the token as the
			ID to get the details on the payment authorization
			*/
        $nvpstr = "&TOKEN=" . $token;

		 /* Make the API call and store the results in an array.  If the
			call was a success, show the authorization details, and provide
			an action to complete the payment.  If failed, show the error
			*/
        $resArray            = $this->hash_call("GetExpressCheckoutDetails", $nvpstr);
        $_SESSION['reshash'] = $resArray;
        $ack                 = strtoupper($resArray["ACK"]);

        if($ack=="SUCCESS"){			
            $_SESSION['token']    = $_REQUEST['token'];
            $_SESSION['payer_id'] = $_REQUEST['PayerID'];
            
            $_SESSION['paymentAmount'] = $_REQUEST['paymentAmount'];
            $_SESSION['currCodeType']  = $_REQUEST['currencyCodeType'];
            $_SESSION['paymentType']   = $_REQUEST['paymentType'];
            
            $resArray  = $_SESSION['reshash'];
            $resArray['token'] = $_REQUEST['token'];
            $resArray['payer_id'] = $_REQUEST['PayerID'];
            $resArray['paymentAmount'] = $_REQUEST['paymentAmount'];
            $resArray['currencyCodeType'] = $_REQUEST['currencyCodeType'];
            $resArray['paymentType'] = $_REQUEST['paymentType'];
            
            return $resArray;
        } else  {
            //Redirecting to APIError.php to display errors. 
            $location = "APIError.php";
            header("Location: $location");
        }
    }
    
    public function setConfig($payment_id) {
        $arrConfig = $this->getConfig($payment_id);
        $this->API_USERNAME  = $arrConfig['API_USERNAME'];
        $this->API_PASSWORD  = $arrConfig['API_PASSWORD'];
        $this->API_SIGNATURE = $arrConfig['API_SIGNATURE']; 
        $this->PAYPAL_URL    = 'https://www.paypal.com/webscr&cmd=_express-checkout&token=';
        $this->API_ENDPOINT  = 'https://api-3t.paypal.com/nvp';

        if($arrConfig['PAYPAL_TESTMODUS']) {
            $this->API_USERNAME  = 'sdk-three_api1.sdk.com';
            $this->API_PASSWORD  = 'QFZCWN5HZM8VBG7Q';
            $this->API_SIGNATURE = 'A.d9eRKfd1yVkRrtmMfCFLTqa6M9AyodL0SJkhYztxUi8W9pCXF6.4NI';
            $this->PAYPAL_URL    = 'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=';
            $this->API_ENDPOINT  = 'https://api-3t.sandbox.paypal.com/nvp';
        }   
    }
    
    public function pay($payment_id) {
        $this->setConfig($payment_id);
        $this->Import('Input');
        
        if($this->Input->Get('state') == 'closed') {
            $this->paymentState = false;
            return true;        
        } else {
    		/**
             * The servername and serverport tells PayPal where the buyer
    		 * should be directed back to after authorizing payment.
    		 * In this case, its the local webserver that is running this script
    		 * Using the servername and serverport, the return URL is the first
    		 * portion of the URL that buyers will return to after authorizing payment
    		 **/
            $serverName = $_SERVER['SERVER_NAME'];
            $serverPort = $_SERVER['SERVER_PORT'];
            $url        = dirname('http://'.$serverName.':'.$serverPort.$_SERVER['REQUEST_URI']);
    
            $paymentAmount    = $_REQUEST['paymentAmount'];
            $currencyCodeType = $_REQUEST['currencyCodeType'];
            $paymentType      = $_REQUEST['paymentType'];                      		 
    
    		 /**
              * The returnURL is the location where buyers return when a
    		  * payment has been succesfully authorized.
    		  * The cancelURL is the location buyers are sent to when they hit the
    		  * cancel button during authorization of payment during the PayPal flow
    		  **/
    		   
            $returnURL =urlencode('http://' . $_SERVER['SERVER_NAME'] . str_replace("?do=pay", "?do=key&template=mod_warenkorb_paypal_revieworder&function=ReviewOrder", $_SERVER['REQUEST_URI']) . '&currencyCodeType='.$currencyCodeType.'&paymentType='.$paymentType.'&paymentAmount='.$paymentAmount);
            $cancelURL =urlencode('http://' . $_SERVER['SERVER_NAME'] . str_replace("?do=pay", "?do=key&function=SetExpressCheckout", $_SERVER['REQUEST_URI']) . '&paymentType=' . $paymentType);
               
    
    		 /* Construct the parameter string that describes the PayPal payment
    			the varialbes were set in the web form, and the resulting string
    			is stored in $nvpstr
    			*/
    		  
            $nvpstr = "&Amt=" . $paymentAmount . "&PAYMENTACTION=" . $paymentType . "&ReturnUrl=" . $returnURL . "&CANCELURL=" . $cancelURL . "&CURRENCYCODE=" . $currencyCodeType;
     
    		 /* Make the call to PayPal to set the Express Checkout token
    			If the API call succeded, then redirect the buyer to PayPal
    			to begin to authorize payment.  If an error occured, show the
    			resulting errors
    			*/
            $resArray            = $this->hash_call("SetExpressCheckout", $nvpstr);
            $_SESSION['reshash'] = $resArray;
    
            $ack = strtoupper($resArray["ACK"]);
     
            if($ack=="SUCCESS"){
                $token     = urldecode($resArray["TOKEN"]);
                $payPalURL = $this->PAYPAL_URL . $token;
                header("Location: ".$payPalURL);
            } else  {
                $location = "APIError.php";
                header("Location: $location");
    		}
        }    
    }

    
    public function compile() {
    
    }
    
    public function DoExpressCheckoutPayment($payment_id) {
        $this->setConfig($payment_id);

        $token         = urlencode($_SESSION['token']);
        $paymentAmount = urlencode($_SESSION['paymentAmount']);
        $paymentType   = urlencode($_SESSION['paymentType']);
        $currCodeType  = urlencode($_SESSION['currCodeType']);
        $payerID       = urlencode($_SESSION['payer_id']);
        $serverName    = urlencode($_SERVER['SERVER_NAME']);

        $nvpstr='&TOKEN=' . $token . '&PAYERID=' . $payerID . '&PAYMENTACTION=' . $paymentType . '&AMT=' . $paymentAmount . '&CURRENCYCODE=' . $currCodeType . '&IPADDRESS=' . $serverName;
        
        /**
         * Make the call to PayPal to finalize payment
         * If an error occured, show the resulting errors
         **/
        $resArray = $this->hash_call("DoExpressCheckoutPayment", $nvpstr);
        
        /**
         * Display the API response back to the browser.
         * If the response from PayPal was a success, display the response parameters'
         * If the response was an error, display the errors received using APIError.php.
         **/
        $ack = strtoupper($resArray["ACK"]);


        if($ack!="SUCCESS"){
            $_SESSION['reshash'] = $resArray;
            $location            = "APIError.php";
            header("Location:" . $location);
        } else {
            header('Location: http://' . $_SERVER['SERVER_NAME'] . substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], "?")) . '?do=pay&state=closed');
        }    
    }
    
    function hash_call($methodName, $nvpStr) {
    	//declaring of global variables
    	global $nvp_Header;
    
    	//setting the curl parameters.
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $this->API_ENDPOINT);
    	curl_setopt($ch, CURLOPT_VERBOSE, 1);
    
    	//turning off the server and peer verification(TrustManager Concept).
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    	curl_setopt($ch, CURLOPT_POST, 1);
        //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
       //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
    	if(USE_PROXY) {
    	   curl_setopt ($ch, CURLOPT_PROXY, PROXY_HOST.":".PROXY_PORT);
        } 
    
    	//NVPRequest for submitting to server
    	$nvpreq="METHOD=".urlencode($methodName)."&VERSION=".urlencode($this->paypalVersion)."&PWD=".urlencode($this->API_PASSWORD)."&USER=".urlencode($this->API_USERNAME)."&SIGNATURE=".urlencode($this->API_SIGNATURE).$nvpStr;
    
    	//setting the nvpreq as POST FIELD to curl
    	curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);
    
    	//getting response from server
    	$response = curl_exec($ch);
    
    	//convrting NVPResponse to an Associative Array
    	$nvpResArray=$this->deformatNVP($response);
    	$nvpReqArray=$this->deformatNVP($nvpreq);
    	$_SESSION['nvpReqArray']=$nvpReqArray;
    
    	if (curl_errno($ch)) {
    		// moving to display page to display curl errors
    		  $_SESSION['curl_error_no']=curl_errno($ch) ;
    		  $_SESSION['curl_error_msg']=curl_error($ch);
    		  $location = "APIError.php";
    		  header("Location: $location");
    	 } else {
    		 //closing the curl
    			curl_close($ch);
    	  }
    
        return $nvpResArray;
    }

    /** This function will take NVPString and convert it to an Associative Array and it will decode the response.
      * It is usefull to search for a particular key and displaying arrays.
      * @nvpstr is NVPString.
      * @nvpArray is Associative Array.
      */

    function deformatNVP($nvpstr) {    
    	$intial   = 0;
     	$nvpArray = array();
        
    	while(strlen($nvpstr)) {
    		//postion of Key
    		$keypos = strpos($nvpstr,'=');
    		
            //position of value
    		$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
    
    		/*getting the Key and Value values and storing in a Associative Array*/
    		$keyval = substr($nvpstr, $intial, $keypos);
    		$valval = substr($nvpstr, $keypos + 1, $valuepos - $keypos - 1);
            
    		//decoding the respose
    		$nvpArray[urldecode($keyval)] = urldecode($valval);
    		$nvpstr                       = substr($nvpstr, $valuepos + 1, strlen($nvpstr));
        }
         
    	return $nvpArray;
    }    
}

?>