<?php
 
/**
 * The AcquistoShop extension allows the creation a OnlineStore width 
 * the OpenSource Contao CMS. For Question visit our Website under
 * http://www.contao-acquisto.de  
 *
 * PHP version 5
 * @package	   AcquistoShop
 * @subpackage Controller
 * @author     Sascha Brandhoff <brandhoff@contao-acquisto.de>
 * @copyright  AcquistoShop
 * @license    LGPL.
 * @filesource
 */
 
namespace AcquistoShop;  

class acquistoShopNavigation extends \Controller
{
    private $shopTrail;

    public function __construct()
    {
        parent::__construct();
        $this->Import('Database');

        if($this->Input->Get('warengruppe') OR $this->Input->Get('produkt')) 
        {
            if($this->Input->Get('warengruppe'))
            {
                $objWarengruppe = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE alias=?")->limit(1)->execute($this->Input->Get('warengruppe'));
                $arrWarengruppen = $this->generateTrail($objWarengruppe->id);
            }
            else
            {
                $objProdukt = $this->Database->prepare("SELECT * FROM tl_shop_produkte WHERE alias=?")->limit(1)->execute($this->Input->Get('produkt'));
                $objWarengruppe = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE id=?")->limit(1)->execute($objProdukt->warengruppen);
                $arrWarengruppen = $this->generateTrail($objWarengruppe->id);
            }
        }

        if(!is_array($this->shopTrail)) 
        {
            $this->shopTrail = array();
        }
    }

    public function loadTrail() 
    {
        return $this->shopTrail;
    }

    public function renderGroups($startlevel, $stoplevel, $hardlimit = 0, $startGroup = 0, $now = 0) 
    {    
        if($startlevel && $now) 
        {
            if(count($this->shopTrail)) 
            {
                $startGroup = $this->shopTrail[$startlevel - 1];            
            } 
            else 
            {
                return '';
            }
        } 
        elseif($startlevel && !$now) 
        {
            if(count($this->shopTrail)) 
            {
                return '';                        
            }            
        }       

        $objGroups = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE pid=? && published=? ORDER BY sorting ASC")->execute($startGroup, 1);
        while($objGroups->next()) 
        {
            $arrItem = $objGroups->row();

            $subItems = null;
            
            if($stoplevel > $now OR (!$hardlimit && in_array($objGroups->id, $this->shopTrail))) 
            {
                $objSummary = $this->Database->prepare("SELECT COUNT(*) AS totalCount FROM tl_shop_warengruppen WHERE pid=?")->execute($objGroups->id);
                if($objSummary->totalCount) 
                {
                    $subItems = $this->renderGroups($startlevel, $stoplevel, $hardlimit, $objGroups->id, ($now + 1));
                }
            }

            $arrItem['subitems'] = $subItems;
            $arrItems[$objGroups->id] = (object) $arrItem;
        }
        
        return $arrItems;        
    }

    public function generateTrail($id)
    {
        if(!$id)
        {
            return '';
        }

        $objGroup = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE id=?")->limit(1)->execute($id);
        $objNext  = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE id=?")->limit(1)->execute($objGroup->pid);

        if($objNext->id)
        {
            $this->generateTrail($objGroup->pid);
        }

        $this->shopTrail[] = $objGroup->id;
    }
}

?>