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

            $objTemplate->wildcard = '### ACQUISTO ORDERLIST ###';
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
        if (FE_USER_LOGGED_IN) 
        {
            $this->import('FrontendUser', 'User');
            $this->import('AcquistoShop\acquistoShopOrders', 'Order');

            $objBestellungen = $this->Database->prepare("SELECT id FROM tl_shop_orders WHERE member_id = ? ORDER BY tstamp DESC")->execute($this->User->id);

            $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
            $strUrl = $this->generateFrontendUrl($objPage->fetchAssoc(), '/order/%s');

            while($objBestellungen->next()) 
            {
                $intCounter++;
                $objBestellung = $this->Order->getComplettOrder($objBestellungen->id);
                $objSummary    = $this->Order->getSummary($objBestellungen->id);
                
                $objBestellung->url = sprintf($strUrl, $objBestellungen->id);

                if(($intCounter % 2) == 1) 
                {
                    $objBestellung->css = 'even';
                } 
                else 
                {
                    $objBestellung->css = 'odd';
                }

                $arrBestellungen[] = (object) array(
                    'Order'   => $objBestellung,
                    'Summary' => $objSummary
                );
            }

            $this->Template->Bestellungen = $arrBestellungen;
        }
    }
}

?>