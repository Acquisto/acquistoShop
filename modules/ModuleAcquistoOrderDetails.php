<?php 

/**
 * The AcquistoShop extension allows to create an online 
 * store with the OpenSource Contao CMS. If you have
 * questions visit our website: http://www.contao-acquisto.de  
*
 * PHP version 5
 * @package	   AcquistoShop
 * @subpackage Frontend
 * @author     Sascha Brandhoff <brandhoff@contao-acquisto.de>
 * @copyright  AcquistoShop
 * @license    LGPL.
 * @filesource
 */

namespace AcquistoShop\Frontend;
 
class ModuleAcquistoOrderDetails extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_bestelldetails';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO BESTELLDETAILS ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        if (!FE_USER_LOGGED_IN) {
            return '';
        }

        return parent::generate();
    }


    /**
     * Generate module
     */
    protected function compile()
    {
        global $objPage;

        if (FE_USER_LOGGED_IN) {
            $this->import('FrontendUser', 'User');
            $this->import('AcquistoShop\acquistoShopProduktLoader', 'Produkt');

            if($this->Input->Get('file')) {
                $objFile = new File($this->Input->Get('file'));
                $this->sendFileToBrowser($this->Input->Get('file', true));

            }

            $objBestellungen = $this->Database->prepare("SELECT * FROM tl_shop_orders WHERE member_id = ? && id = ?")->limit(1)->execute($this->User->id, $this->Input->Get('order'));
            $objCustomer     = (object) unserialize($objBestellungen->customerData);
            $objDeliver      = (object) unserialize($objBestellungen->deliverAddress);
            $objDetails      = $this->Database->prepare("SELECT * FROM tl_shop_orders_items WHERE pid = ? ORDER BY tstamp DESC")->execute($objBestellungen->id);

            $objPositionen   = $this->Database->prepare("SELECT SUM(menge * preis) AS endpreis FROM tl_shop_orders_items WHERE pid = ?")->execute($objBestellungen->id);
            $objZahlungsart  = $this->Database->prepare("SELECT * FROM tl_shop_zahlungsarten WHERE id = ?")->limit(1)->execute($objBestellungen->zahlungsart_id);
            $objVersandzonen = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen WHERE id = ?")->limit(1)->execute($objBestellungen->versandzonen_id);

//            $objBestellungen->endpreis = $objPositionen->endpreis + $objBestellungen->versandpreis;

            $arrDownloads    = array();

            while($objDetails->next()) {
                $intCounter++;

                $objElement = (object) $objDetails->row();
//                $objProdukt = new Produkt($objElement->produkt_id);

                if(is_array($objProdukt->digital_product)) {
                    $arrDownloads = array_merge($arrDownloads, $objProdukt->digital_product);
                }

                if(($intCounter % 2) == 1) {
                    $objElement->css  = 'even';
                } else {
                    $objElement->css  = 'odd';
                }

                $objElement->summe = $objElement->preis * $objElement->menge;
                $dblSumme = $dblSumme + $objElement->summe;

                $arrElements[] = $objElement;
            }

            if($objBestellungen->gutscheine) {
                $this->Template->Gutscheine = ($arrGutscheine = unserialize($objBestellungen->gutscheine));
                if(is_array($arrGutscheine)) {

                    foreach($arrGutscheine as $objGutschein) {
                        $dblGutscheine = $dblGutscheine + $objGutschein->preis;
                    }
                }
            }

            $objDefault = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($objPage->id);
            $this->Template->DownloadUrl = sprintf($this->generateFrontendUrl($objDefault->fetchAssoc(), '/order/%s'), $this->Input->Get('order'));

            $this->Template->Endpreise    = (object) array('gesamtsumme' => sprintf("%01.2f", $dblSumme), 'gutscheine' => sprintf("%01.2f", $dblGutscheine), 'endsumme' => sprintf("%01.2f", $dblSumme - $dblGutscheine));
            $this->Template->Downloads    = $arrDownloads;
            $this->Template->Elements     = $arrElements;
            $this->Template->Bestellungen = (object) $objBestellungen->row();
            $this->Template->Kunde        = $objCustomer;
            $this->Template->Zahlungsart  = (object) $objZahlungsart->row();
            $this->Template->Versandzonen = (object) $objVersandzonen->row();
        }
    }

}

?>