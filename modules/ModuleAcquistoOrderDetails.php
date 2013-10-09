<?php 

/**
 * The AcquistoShop extension allows to create an online 
 * store with the OpenSource Contao CMS. If you have
 * questions our website: http://www.contao-acquisto.de  
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
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO ORDERDETAILS ###';
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
        if (FE_USER_LOGGED_IN) 
        {
            $this->import('FrontendUser', 'User');
            $this->Import('AcquistoShop\acquistoShopProduktLoader', 'Produkt');
            $this->import('AcquistoShop\acquistoShopOrders', 'Order');

            if($this->Input->Get('file')) 
            {
                $objFile = new \File($this->Input->Get('file'));
                $this->sendFileToBrowser($this->Input->Get('file', true));

            }

            $objBestellung = $this->Order->getComplettOrder($this->Input->Get('order'));
            $arrDownloads    = array();

            foreach($objBestellung->items as $Item) 
            {
                $objProdukt = $this->Produkt->load($Item->produkt_id);

                if(is_array($downloadArray = unserialize($objProdukt->digital_product))) 
                {                    
                    foreach($downloadArray as $value)
                    {
                        $objFile = \FilesModel::findByPk($value);
                        $arrDownloads[] = $objFile->path;

                    }                    
                }
            }

            $this->Template->DownloadUrl = substr($this->Environment->requestUri, 1);
            $this->Template->Downloads   = $arrDownloads;
            
            $this->Template->Total = $this->Order->getSummary($this->Input->Get('order'));
            $this->Template->Order = $objBestellung;
            $this->Template->Taxes = $this->Order->getTaxes($this->Input->Get('order'));
        }
    }

}

?>