<?php 

/**
 * The AcquistoShop extension allows the creation a OnlineStore width 
 * the OpenSource Contao CMS. For Question visit our Website under
 * http://www.contao-acquisto.de  
 *
 * PHP version 5
 * @package	   AcquistoShop
 * @subpackage Module
 * @author     Sascha Brandhoff <brandhoff@contao-acquisto.de>
 * @copyright  AcquistoShop
 * @license    LGPL.
 * @filesource
 */
 
namespace AcquistoShop\Frontend;

class ModuleAcquistoShipping extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_versand';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO PAYMENT &amp; SHIPPING ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        return parent::generate();
    }


    /**
     * Generate module
     */
    protected function compile()
    {
        $arrCountries = $this->getCountries();

        if($GLOBALS['TL_CONFIG']['versandberechnung'] == "gewicht") {
            $this->Template->Symbol = "kg";
        } else {
            $this->Template->Symbol = $GLOBALS['TL_CONFIG']['currency_symbol'];
        }

        $objVersandzonen = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen;")->execute();
        while($objVersandzonen->next()) {
            $objVersandarten = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen_varten WHERE pid = ? ORDER BY pid, zahlungsart_id")->execute($objVersandzonen->id);
            $strCountrys = null;

            if($objVersandzonen->laender) {
                $arrLaender = unserialize($objVersandzonen->laender);
                foreach($arrLaender as $value) {
                    $strCountrys .= $arrCountries[$value] . ", ";
                }
            }

            $arrZahlungsart = null;
            while($objVersandarten->next()) {
                $objZahlungsart = $this->Database->prepare("SELECT * FROM tl_shop_zahlungsarten WHERE id = ?")->limit(1)->execute($objVersandarten->zahlungsart_id);
                $arrZahlungsart[$objZahlungsart->id]['bezeichnung'] = $objZahlungsart->bezeichnung;
                $arrZahlungsart[$objZahlungsart->id]['infotext']    = $objZahlungsart->infotext;
                $arrZahlungsart[$objZahlungsart->id]['versandkosten'][] = array(
                    'ab_einkaufspreis' => sprintf("%01.2f", \AcquistoShop\acquistoShopCosts::costsCalculator($objVersandarten->ab_einkaufpreis, \AcquistoShop\acquistoShopCosts::getDefaultCurrency(), \AcquistoShop\acquistoShopCosts::getSelectedCurrency())),
                    'preis'            => sprintf("%01.2f", \AcquistoShop\acquistoShopCosts::costsCalculator($objVersandarten->preis, \AcquistoShop\acquistoShopCosts::getDefaultCurrency(), \AcquistoShop\acquistoShopCosts::getSelectedCurrency()))

                );
            }

            $arrVersandzonen[$objVersandzonen->id]['bezeichnung']   = $objVersandzonen->bezeichnung;
            $arrVersandzonen[$objVersandzonen->id]['laender']       = substr($strCountrys, 0, -2);
            $arrVersandzonen[$objVersandzonen->id]['zahlungsarten'] = $arrZahlungsart;
        }

        $this->Template->Versandkosten = $arrVersandzonen;
        $this->Template->Currency      = \AcquistoShop\acquistoShopCosts::getCurrency(\AcquistoShop\acquistoShopCosts::getSelectedCurrency());
    }
}

?>