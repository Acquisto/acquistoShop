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

class acquistoShopMessages extends \Backend
{
    private $html;
    protected $feeds = array(
        'http://www.contao-acquisto.de/blog.xml', 
//        'https://api.twitter.com/1/statuses/user_timeline.rss?screen_name=acquistoshop', 
        'http://feeds.feedburner.com/shopbetreiberblog'
    );

    public function __construct() {
        parent::__construct();
        $this->import('AcquistoShop\acquistoShop', 'Shop');
    }
    
    private function clear() {
        $this->html = null;
    }


    public function checkAcquistoState() {
        $this->clear();

        $objDatabase = $this->Database->prepare("SELECT COUNT(*) as totalSum FROM tl_module WHERE type = '?'")->execute('acquistoShop_Warenkorb');         
        if($objDatabase->totalSum) {
            $html .= '<p class="tl_error">Wir haben das Modul acquistoShop_Warenkorb in ModuleAcquistoBasket umbenannt, bitte aktualisieren Sie dieses.';
        }        

        /**
         * Basisdatensätze anlegen
         */
        
        
        $objSummary = $this->Database->prepare("SHOW TABLES LIKE 'tl_shop_currency'")->execute();
        if($objSummary->count())
        {        
            $objSummary = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_shop_currency WHERE id=?")->execute(1);
            if(!$objSummary->total)
            { 
                $this->Database->prepare("INSERT INTO tl_shop_currency (id, tstamp, title, iso_code, exchange_ratio, default_currency, guests) VALUES(1, NOW(), 'Euro', 'EUR', 0, 1, 1)")->execute();
                $this->log('Added A new Currency', __CLASS__.'::'.__FUNCTION__, TL_CONFIGURATION);
                $html .= '<p class="tl_info">Es wurde eine neue W&auml;hrung angelegt.</p>';        
            }
        }
        
        $objSummary = $this->Database->prepare("SHOW TABLES LIKE 'tl_shop_pricelists'")->execute();
        if($objSummary->count())
        {        
            $objSummary = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_shop_pricelists WHERE id=?")->execute(1);
            if(!$objSummary->total)
            { 
                $this->Database->prepare("INSERT INTO tl_shop_pricelists (id, tstamp, title, type, guests, currency, default_list) VALUES(1, NOW(), 'Standard', 'brutto', 1, 1, 1)")->execute();        
                $this->log('Added a new Pricelist', __CLASS__.'::'.__FUNCTION__, TL_CONFIGURATION);
                $html .= '<p class="tl_info">Es wurde eine neue Preisliste angelegt.</p>';        
            }
        }

        
        $objSummary = $this->Database->prepare("SHOW FIELDS FROM tl_shop_orders WHERE Field LIKE 'calculateTax'")->execute();
        if($objSummary->count())
        {
            $objSummary = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_shop_produkte WHERE calculateTax = ''")->execute();
            if($objSummary->total)
            {
    
                \AcquistoShop\Helper\AcquistoUpdate::taxProducts();
                $this->log('Calculate tax', __CLASS__.'::'.__FUNCTION__, TL_CONFIGURATION);
                $html .= '<p class="tl_info">Die Produkte wurden manipuliert (Preise sind Netto).</p>';        
            }
        }

        $objSummary = $this->Database->prepare("SHOW FIELDS FROM tl_shop_orders WHERE Field LIKE 'currency_default'")->execute();
        if($objSummary->total)
        {
            $objSummary = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_shop_orders WHERE currency_default = '' OR currency_selected = ''")->execute();
            if($objSummary->total)
            {
    
                \AcquistoShop\Helper\AcquistoUpdate::cardCurrency();
                $this->log('Orders updatet', __CLASS__.'::'.__FUNCTION__, TL_CONFIGURATION);
                $html .= '<p class="tl_info">Bestellungen wurden aktualisiet.</p>';        
            }
        }        
        
        if(!$GLOBALS['TL_CONFIG']['agb']) {
            $html .= '<p class="tl_error">Sie haben noch keine Allgemeinen Gesch&auml;ftsbedingungen eingegeben <a href="' . ampersand($this->Environment->request) . '?do=acquistoShopEinstellungen">(bearbeiten)</a>.</p>';        
        }

        if(!$GLOBALS['TL_CONFIG']['widerruf']) {
            $html .= '<p class="tl_error">Sie haben noch keine Widerrufsbelehrung eingegeben <a href="' . ampersand($this->Environment->request) . '?do=acquistoShopEinstellungen">(bearbeiten)</a>.</p>';        
        }

        if(!$GLOBALS['TL_CONFIG']['versandPage']) {
            $html .= '<p class="tl_error">Sie haben die Seite f&uuml;r Versand &amp; Zahlungsinformationen noch nicht ausgew&auml;hlt <a href="' . ampersand($this->Environment->request) . '?do=acquistoShopEinstellungen">(bearbeiten)</a>.</p>';        
        }
        
#        $html .= '<p class="tl_new">Jetzt verf&uuml;gbar das Acquisto Auktionsmodul. Mehr Informationen in unserem <a href="http://www.contao-acquisto.de">Webshop</a>.</p>';
#        $html .= '<p class="tl_new">Bald wieder verf&uuml;gbar Acquisto PDF-Billing. Mehr Informationen in unserem <a href="http://www.contao-acquisto.de">Webshop</a>.</p>';
#        $html .= '<p class="tl_new">Wir empfehlen das Abo unserer Update-Newsletter. Besuchen Sie dazu unsere <a href="http://www.contao-acquisto.de">Webseite</a> und tragen Sie sich ein.</p>';

        return '<h2>Systemnachrichten Acquisto Webshop f&uuml;r Contao</h2>' . $html;         
    }
}

?>