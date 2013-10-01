<?php

/**
 * The AcquistoShop extension allows to create an online 
 * store with the OpenSource Contao CMS. If you have
 * questions our website: http://www.contao-acquisto.de  
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

class ModuleAcquistoBasketWidget extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_warenkorb_widget';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO BASKET WIDGET ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        $this->Import('AcquistoShop\acquistoShopBasket', 'Basket');
        $this->Import('FrontendUser', 'Member');
                
        $this->Basket->cardType = \AcquistoShop\acquistoShopCosts::getSelectedPricelist($this->Member->groups)->type;
         
        return parent::generate();
    }


    /**
     * Generate module
     */
    protected function compile()
    {
        if (FE_USER_LOGGED_IN) 
        {
            $this->import('FrontendUser', 'Member');
        }
                
        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
        $objData = (object) $this->Basket->itemNum();

        $this->Template->WarenkorbUrl = $this->generateFrontendUrl($objPage->fetchAssoc());
        $this->Template->Endpreis     = $objData->summe;
        $this->Template->Menge        = $objData->items;
                
        $this->Template->Pricelist = \AcquistoShop\acquistoShopCosts::getSelectedPricelist($this->Member->groups);
        $this->Template->Currency  = \AcquistoShop\acquistoShopCosts::getCurrency(\AcquistoShop\acquistoShopCosts::getSelectedCurrency());
    }

}

?>