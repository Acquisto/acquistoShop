<?php 

/**
 * The AcquistoShop extension allows the creation a OnlineStore width 
 * the OpenSource Contao CMS. For Question visit our Website under
 * http://www.contao-acquisto.de  
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
 
class ModuleAcquistoCoupon extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_gutschein';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO COUPON ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        if($this->Input->Get('do'))
        {
            return '';
        }

        if (FE_USER_LOGGED_IN) {
            $this->Import('FrontendUser', 'User');
        }

        $this->Import('AcquistoShop\acquistoShopGutschein', 'Gutschein');
        $this->Import('AcquistoShop\acquistoShop', 'Shop');
        return parent::generate();
    }


    /**
     * Generate module
     */
    protected function compile()
    {
        if (FE_USER_LOGGED_IN) {
            $objGutscheine = $this->Database->prepare("SELECT id FROM tl_shop_gutscheine WHERE kunden_id = ?")->execute($this->User->id);

            while($objGutscheine->next()) {
                $arrGutscheine[] = $this->Gutschein->getGutschein($objGutscheine->id);
            }

            $this->Template->Gutscheine = $arrGutscheine     ;
        }

        $this->Template->UseList = $this->Gutschein->getList();

        if($this->Input->Post('FORM_SUBMIT') == 'tl_acquistoShop_gutschein') {
            $objGutschein = $this->Database->prepare("SELECT * FROM tl_shop_gutscheine WHERE code = ?")->limit(1)->execute($this->Input->Post('code'));

            if($this->Gutschein->validateGutschein($objGutschein->id, $this->User->id)) {
                $this->Gutschein->addGutschein2Use($objGutschein->id);

                $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->replaceInsertTags($this->Shop->getInsertTagPID()));
                $this->redirect($this->generateFrontendUrl($objPage->fetchAssoc()));
            }
        }
    }

}

?>