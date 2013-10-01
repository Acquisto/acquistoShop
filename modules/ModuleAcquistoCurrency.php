<?php 

/**
 * The AcquistoShop extension allows to create an online 
 * store with the OpenSource Contao CMS. If you have
 * questions our website: http://www.contao-acquisto.de  
 *
 * PHP version 5
 * @package	   AcquistoShop
 * @subpackage Product
 * @author     Sascha Brandhoff <brandhoff@contao-acquisto.de>
 * @copyright  AcquistoShop
 * @license    LGPL.
 * @filesource
 */
 
namespace AcquistoShop\Frontend;

class ModuleAcquistoCurrency extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_currency';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO CURRENCY ###';
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
        if($this->Input->Post('FORM_SUBMIT') == 'tl_shop_currency')
        {
            $_SESSION['ACQUISTO']['CONFIG']['CURRENCY'] = $this->Input->Post('Currency');
            \AcquistoShop\acquistoShop::reload();
        }
        
        if(FE_USER_LOGGED_IN)
        {
            $this->import('FrontendUser', 'Member');
            foreach($this->Member->groups as $Group)
            {
                $arrLists[] = \AcquistoShop\acquistoShopCosts::getPricelistsByGroup($Group);            
            }
            
            if(is_array($arrLists)) 
            {
                foreach($arrLists as $listArray)                
                {
                    if(is_array($listArray))
                    {
                        foreach($listArray as $List)
                        {                  
                            $arrPricelists[$List->id] = $List->currency;
                            
                        }
                    }
                }            
            }
            
            if(count($arrPricelists))
            {
                $objDatabase = $this->Database->prepare("SELECT * FROM tl_shop_currency WHERE guests = 1 OR id IN(" . implode(",", $arrPricelists) . ")")->execute();
            }
            else
            {
                $objDatabase = $this->Database->prepare("SELECT * FROM tl_shop_currency WHERE guests = 1")->execute();            
            }            
        }
        else
        {
            $objDatabase = $this->Database->prepare("SELECT * FROM tl_shop_currency WHERE guests = 1")->execute();        
        }        
                
        while($objDatabase->next())
        {
            $objCurrency   = \AcquistoShop\acquistoShopCosts::getCurrency($objDatabase->id);
            
            if($objCurrency->id == $_SESSION['ACQUISTO']['CONFIG']['CURRENCY'])
            {
                $objCurrency->active = true;
            }
            
            $arrCurrency[] = $objCurrency;
        } 
        
        $this->Template->Currency = $arrCurrency;        
    }
}

?>