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

class acquistoShopProduktController extends \Controller 
{
    var $intId           = 0;
    var $arrAttributes   = array();
    var $arrPreise       = array();
    var $strTemplate     = 'acquisto_produkt_default';
    
    /**
     * Preislisten variablen
     */         
    var $defaultCostlist = 0;
    var $costLists       = array();
    var $memberGroups    = array();

    public function __construct() 
    {
        parent::__construct();
        $this->Import('Database');
        
#        $this->Import('AcquistoShop\acquistoShopMember', 'Member');        
        $this->Import('AcquistoShop\acquistoShopCosts', 'Costs');
        
        if(FE_USER_LOGGED_IN) 
        {
            $this->import('FrontendUser', 'Member');
        }        
    }

    public function getCosts($menge = 0) {
        return $this->Costs->getCosts($menge);
    }

    public function sortCosts()     
    {
        $this->Costs->setGroups($this->Member->groups);
        $this->Costs->setTax($this->steuer);        
        $this->Costs->buildCostsData(unserialize($this->preise), $this->inhalt, $this->berechnungsmenge);
#    public function buildCostsData($costsArray, $volumen = null, $calculate = null) 
    }

    public function buildCss() {
        $this->css = isset($this->produkt->zustand) ? $this->zustand . ' ' : null;
        if($this->marked) {
            $this->css .= 'marked ';
        }
    }

    public function getArrPreise()
    {
        return $this->arrPreise;
    }
}

?>