<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * The AcquistoShop extension allows to create an online 
 * store with the OpenSource Contao CMS. If you have
 * questions our website: http://www.contao-acquisto.de  
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    News
 * @license    LGPL
 * @filesource
 */

/**
 * Class mod_acquistoShop_AGB
 *
 * Front end module "mod_acquistoShop_AGB".
 * @copyright  Sascha Brandhoff 2011
 * @author     Sascha Brandhoff <http://www.contao-acquisto.org>
 * @package    Controller
 */

class mod_acquistoShop_Breadcrumb extends Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_breadcrumb';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO BREADCRUMB ###';
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
#        global $objPage;
#        echo $objPage->id;
        $this->Import('acquistoShopNavigation');
        if(is_array($this->acquistoShopNavigation->shopTrail))
        {
            $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
            $strUrl  = $this->generateFrontendUrl($objPage->fetchAssoc(), '/warengruppe/%s');

            if($this->Input->Get('produkt'))
            {
                foreach($this->acquistoShopNavigation->shopTrail as $value)
                {
                    $objWarengruppe = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE id = ?")->limit(1)->execute($value);
                    $arrBreadcrumb[] = array(
                        'alias' => $objWarengruppe->alias,
                        'title' => $objWarengruppe->title,
                        'url'   => sprintf($strUrl, $objWarengruppe->alias)
                    );
                }
            }
            else
            {
                for($intCount = 0; $intCount < count($this->acquistoShopNavigation->shopTrail) - 1; $intCount++)
                {
                    $objWarengruppe = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE id = ?")->limit(1)->execute($this->acquistoShopNavigation->shopTrail[$intCount]);
                    $arrBreadcrumb[] = array(
                        'alias' => $objWarengruppe->alias,
                        'title' => $objWarengruppe->title,
                        'url'   => sprintf($strUrl, $objWarengruppe->alias)
                    );
                }

                $objWarengruppe = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE id = ?")->limit(1)->execute($this->acquistoShopNavigation->shopTrail[(count($this->acquistoShopNavigation->shopTrail) - 1)]);
                $arrBreadcrumb[] = array(
                    'alias' => $objWarengruppe->alias,
                    'title' => $objWarengruppe->title
                );
            }
        }

        if($this->Input->Get('produkt'))
        {
            $objProdukt = $this->Database->prepare("SELECT * FROM tl_shop_produkte WHERE alias=?")->limit(1)->execute($this->Input->Get('produkt'));
            $arrBreadcrumb[] = array(
                'alias' => $objProdukt->alias,
                'title' => $objProdukt->bezeichnung
            );
        }

        $this->Template->Breadcrumb = $arrBreadcrumb;

#        print_r($arrBreadcrumb);

#

        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
        $strUrl  = $this->generateFrontendUrl($objPage->fetchAssoc(), '/warengruppe/%s');
        if($this->Input->Get('warengruppe') OR $this->Input->Get('produkt'))
        {
            if($this->Input->Get('warengruppe'))
            {
                $objWarengruppe = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE alias=?")->limit(1)->execute($this->Input->Get('warengruppe'));
                $arrWarengruppen = $this->renderWarengruppen1($objWarengruppe->id, $this->contaoShop_levelOffset);
            }
            else
            {
                $objProdukt = $this->Database->prepare("SELECT * FROM tl_shop_produkte WHERE alias=?")->limit(1)->execute($this->Input->Get('produkt'));
                $objWarengruppe = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE id=?")->limit(1)->execute($pbjProdukt->id);
                $arrWarengruppen = $this->renderWarengruppen1($objWarengruppe->pid, $this->contaoShop_levelOffset);
            }
        }
        else
        {
            $arrWarengruppen = $this->renderWarengruppen1(0, $this->contaoShop_levelOffset);
        }

        $this->Template->items = $arrWarengruppen;
    }

    public function getAlias($categorie) {

        if($arrElement['type'] == "redirect") {
            $objRedirect = $this->Database->prepare("SELECT alias FROM tl_shop_warengruppen WHERE id = ?")->limit(1)->execute($arrElement['jumpTo']);
            $arrElement['alias'] = $objRedirect->alias;
        }
    }        
    /*
    public function renderWarengruppen($pid) {

    }

    public function generateTrail($id)
    {

    }
    */
    public function renderWarengruppen1($pid, $level=0)
    {
#        echo $this->name . "-" . $pid . "-" . $offset . "-" . $show;
#        echo "<br />";

#        if($this->contaoShop_showLevel <= $show) {
#            return '';
#        }

        if($level) {
            $start = $this->acquistoShopNavigation->shopTrail[($level-1)];
        } else {
            $start = 0;
        }

        $objTemplate = new FrontendTemplate('warengruppen_default');

        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
        $strUrl  = $this->generateFrontendUrl($objPage->fetchAssoc(), '/warengruppe/%s');

        $objWarengruppen = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE pid=? && published=1")->execute($start);
        while($objWarengruppen->next()) {
            $arrElement = $objWarengruppen->row();
            $arrSubItems = null;
            $objSubpages = null;
            if($level < $this->contaoShop_showLevel && $arrElement['id'] == $this->acquistoShopNavigation->shopTrail[($level)]) {
                $objSubpages = $this->Database->prepare("SELECT COUNT(*) AS totalC FROM tl_shop_warengruppen WHERE pid=?")->execute($objWarengruppen->id);

                if($objSubpages->totalC)
                {
                    $arrSubItems = $this->renderWarengruppen1($objWarengruppen->id, ($level + 1));
                }
            }

            $arrElement['subitems'] = $arrSubItems;
            $arrElement['url']      = sprintf($strUrl, $arrElement['alias']);

            if($arrElement['id'] == $this->acquistoShopNavigation->shopTrail[($level)]) {
                $arrElement['class'] = "trail";
            }

            if($this->Input->Get('warengruppe') == $arrElement['alias'])
            {
                $arrElement['isActive'] = 1;
            }

            $arrItems[] = $arrElement;
        }


        if (count($arrItems))
        {
            $last = count($arrItems) - 1;

            $arrItems[0]['class'] = trim($arrItems[0]['class'] . ' first');
            $arrItems[$last]['class'] = trim($arrItems[$last]['class'] . ' last');
        }

        $objTemplate->level = "level_" . $level;
        $objTemplate->items = $arrItems;
        return count($arrItems) ? $objTemplate->parse() : '';
    }
}

?>