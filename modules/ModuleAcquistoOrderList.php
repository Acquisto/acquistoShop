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
 
class ModuleAcquistoOrderList extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_bestellliste';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO BESTELLLISTE ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        if (!FE_USER_LOGGED_IN) 
        {
            return '';
        }

        return parent::generate();
    }


    /**
     * Generate module
     */
    protected function compile()
    {
        if (FE_USER_LOGGED_IN) {
            $this->import('FrontendUser', 'User');
            $objBestellungen = $this->Database->prepare("SELECT * FROM tl_shop_orders WHERE member_id = ? ORDER BY tstamp DESC")->execute($this->User->id);

            $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
            $strUrl = $this->generateFrontendUrl($objPage->fetchAssoc(), '/order/%s');

            while($objBestellungen->next()) {
                $intCounter++;

                $objPositionen   = $this->Database->prepare("SELECT SUM(menge * preis) AS endpreis FROM tl_shop_orders_items WHERE pid = ?")->execute($objBestellungen->id);
                $objZahlungsart  = $this->Database->prepare("SELECT * FROM tl_shop_zahlungsarten WHERE id = ?")->execute($objBestellungen->zahlungsart_id);
                $objVersandzonen = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen WHERE id = ?")->execute($objBestellungen->versandzonen_id);

                $arrBestellung = $objBestellungen->row();
                $arrBestellung['versandpreis'] = sprintf("%01.2f", $arrBestellung['versandpreis']);
                $arrBestellung['versandzone']  = $objVersandzonen->bezeichnung;
                $arrBestellung['tstamp']       = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $arrBestellung['tstamp']);
                $arrBestellung['zahlungsart']  = $objZahlungsart->bezeichnung;
                $arrBestellung['url']          = sprintf($strUrl, $arrBestellung['id']);

                if($arrBestellung['gutscheine']) {
                    $arrGutscheine = unserialize($arrBestellung['gutscheine']);
                    $dblGutscheine = 0;
                    if(is_array($arrGutscheine)) {

                        foreach($arrGutscheine as $objGutschein) {
                            $dblGutscheine = $dblGutscheine + $objGutschein->preis;
                        }
                    }
                }

                if(($intCounter % 2) == 1) {
                    $arrBestellung['css']  = 'even';
                } else {
                    $arrBestellung['css']  = 'odd';
                }

                $arrBestellung['endpreis']     = sprintf("%01.2f", ($objPositionen->endpreis + $arrBestellung['versandpreis']) - $dblGutscheine);
                $arrBestellungen[] = (object) $arrBestellung;
            }

            $this->Template->Bestellungen = $arrBestellungen;
        }
    }
}

?>