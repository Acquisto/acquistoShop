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

class ModuleAcquistoCategories extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_warengruppen';
    private $objGroups = null;


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO WARENGRUPPEN ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        if(FE_USER_LOGGED_IN) 
        {
            $this->import('FrontendUser', 'User');
        }

        $this->Import('AcquistoShop\acquistoShopNavigation', 'Navigation');

        if($this->contaoShop_reference) 
        {
            /**
             * mit Referenzseite
             */
             $this->objGroups = $this->Navigation->renderGroups($this->contaoShop_levelOffset, $this->contaoShop_showLevel, $this->contaoShop_hardLimit, $this->contaoShop_reference);
        }
        else
        {
            /**
             * ohne Referenzseite
             */
             $this->objGroups = $this->Navigation->renderGroups($this->contaoShop_levelOffset, $this->contaoShop_showLevel, $this->contaoShop_hardLimit);                                 
        }
        
        if(!$this->objGroups) 
        {
            return '';
        }

        return parent::generate();
    }


    /**
     * Generate module
     */
    protected function compile() {       
        $this->Template->items = $this->renderWarengruppen($this->objGroups);
    }
    
    public function renderWarengruppen($groups, $level = 0) 
    {
        $objTemplate = new \FrontendTemplate('warengruppen_default');
        $objTemplate->level = "level_" . ($level = $level + 1);

        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
        $strUrl  = $this->generateFrontendUrl($objPage->fetchAssoc(), '/warengruppe/%s');

        if($groups) 
        {
            foreach($groups as $item) 
            {
                if($this->checkPermessions($item->member_groups) OR !$item->member_groups) 
                {
                    $strSprint = $strUrl;
                    if($item->type == "redirect") 
                    {
                        $objRedirect = $this->Database->prepare("SELECT alias FROM tl_shop_warengruppen WHERE id = ?")->limit(1)->execute($item->jumpTo);
                        $item->alias = $objRedirect->alias;
                    } 
                    elseif($item->type == "page") 
                    {
                        $objRedirect = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($item->jumpToPage);
                        $item->alias = $objPage->alias;
                        $strSprint = $this->generateFrontendUrl($objRedirect->fetchAssoc(), '');
                    }

                    $item->url      = sprintf($strSprint, $item->alias);

                    if($this->Input->Get('warengruppe') == $item->alias) 
                    {
                        $item->isActive = 1;
                    }

                    if(is_array($item->subitems)) 
                    {
                        $item->subitems = $this->renderWarengruppen($item->subitems, $level);
                    }

                    $rebuild[] = $item;
                }
            }

            if (count($rebuild)) 
            {
                $last = count($rebuild) - 1;

                $rebuild[0]->class = trim($rebuild[0]->class . ' first');
                $rebuild[$last]->class = trim($rebuild[$last]->class . ' last');
            }

            $objTemplate->items = $rebuild;
            return $objTemplate->parse();
        }
    }
    
    function checkPermessions($groupRights) 
    {
        $groupRights = unserialize($groupRights);
        $access = false;
        
        if(FE_USER_LOGGED_IN && is_array($groupRights)) 
        {
            foreach($this->User->groups as $group) 
            {
                if(in_array($group, $groupRights)) 
                {
                    $access = true;
                }
            }
        }
        
        return $access;   
    }    
}

?>