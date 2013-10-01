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

class acquistoShopCosts extends \Controller {

    /**
     * Speichert die Default Preisliste als Objekt
     */         
    private $defaultList   = null;
    
    /**
     * Speicher den Array mit allen Preisen
     */         
    private $costsStructur = array();
    
    /**
     * Speicher den Steuersatz
     */         
    private $tax           = null;
    
    /**
     * Speichert ob die Steuer schon aufgeschlagen wurde
     */         
    private $taxAdded      = false;
    
    /**
     * Speichert die Gruppen des eingeloggten Mitglieds
     */
    private $memberList  = array();
    
    public function __construct()
    {
        parent::__construct();
        $this->Import('Database');
        
        $this->defaultList = $this->Database->prepare("SELECT * FROM tl_shop_pricelists WHERE default_list = ?")->execute(1);
        $this->defaultList = (object) $this->defaultList->row();
        
        $this->getSelectedCurrency();                
    }

    static function getDefaultCurrency()
    {
        $objDatabase = \Database::getInstance();
        $objCurrency = $objDatabase->prepare("SELECT * FROM tl_shop_currency WHERE default_currency = ?")->execute(1);
        return $objCurrency->id;    
    }

    static function getSelectedCurrency()
    {
        $objDatabase = \Database::getInstance();

        if(!$_SESSION['ACQUISTO']['CONFIG']['CURRENCY'])
        {
            $objCurrency = $objDatabase->prepare("SELECT * FROM tl_shop_currency WHERE default_currency = ?")->execute(1);
            $_SESSION['ACQUISTO']['CONFIG']['CURRENCY'] = $objCurrency->id;
        }
    
        return $_SESSION['ACQUISTO']['CONFIG']['CURRENCY'];
    }

    static function getSelectedPricelist($Groups = null)
    {
        $objDatabase = \Database::getInstance();

        $objDatabase = $objDatabase->prepare("SELECT * FROM tl_shop_pricelists WHERE default_list = ?")->execute(1);
        $selectedGroup = (object) $objDatabase->row();                    

        if($Groups)
        {
            foreach($Groups as $Group)
            {
                $arrGroups[] = \AcquistoShop\acquistoShopCosts::getPricelistsByGroup($Group);
            }
                        
            foreach($arrGroups as $groupLists)
            {
                if(is_array($groupLists))
                {
                    foreach($groupLists as $Group)
                    {
                        $newGroups[] = $Group;
                    }
                }
            }

            if(count($newGroups))
            {
                $selectedGroup = reset($newGroups);
            }
        }
        
        return $selectedGroup;
    }
    
    static function getCurrency($Id) 
    {
        $objDatabase = \Database::getInstance();
        $objDatabase = $objDatabase->prepare("SELECT * FROM tl_shop_currency WHERE id = ?")->execute($Id);
        return (object) $objDatabase->row();
    }
    
    static function getCurrencyByPricelist() 
    {
        $objDatabase = \Database::getInstance();    
    }
    
    static function costsCalculator($costs, $fromCurrency, $toCurrency)
    {
        $objDatabase  = \Database::getInstance();
        $fromCurrency = \AcquistoShop\acquistoShopCosts::getCurrency($fromCurrency);        
        $toCurrency   = \AcquistoShop\acquistoShopCosts::getCurrency($toCurrency);

        if($fromCurrency->id != $toCurrency->id)
        {
            if($fromCurrency->default_currency)
            {
                $costs = $costs * $toCurrency->exchange_ratio;
            }
            else 
            {
                if($toCurrency->default_currency)
                {
                    $costs = ($costs / $fromCurrency->exchange_ratio);                
                }
                else
                {
                    $costs = ($costs / $fromCurrency->exchange_ratio) * $toCurrency->exchange_ratio;
                }   
            }
        }        
        
        return $costs;        
    }
    
    static function getPricelistsByGroup($GroupId) 
    {
        $objDatabase = \Database::getInstance();
        $objPricelists = $objDatabase->prepare("SELECT * FROM tl_shop_pricelists")->execute();
        
        while($objPricelists->next()) 
        {
            if(is_array(unserialize($objPricelists->groups)))
            { 
                if(in_array($GroupId, unserialize($objPricelists->groups))) 
                {
                    $arrPricelists[] = (object) $objPricelists->row();
                }
            }
        }
        
        return $arrPricelists;    
    }
    
    public function setTax($id) 
    {
        $objSteuer = $this->Database->prepare("SELECT * FROM tl_shop_steuersaetze WHERE pid=? ORDER BY gueltig_ab ASC")->limit(1)->execute($id);
        $this->tax = $objSteuer->satz;
    }
    
    public function setGroups($arrayGroups)
    {
        $objDatabase = $this->Database->prepare("SELECT * FROM tl_shop_pricelists")->execute();
        while($objDatabase->next()) 
        {
            if(is_array($arrayGroups))
            {
                foreach($arrayGroups as $Group)            
                {
                    $arrGroups = unserialize($objDatabase->groups);
                    if(is_array($arrGroups))
                    {
                        if(in_array($Group, $arrGroups) && !array_key_exists($objDatabase->id, $this->memberList))
                        {
                            $this->memberList[$objDatabase->id] = (object) $objDatabase->row();                                
                        }
                    }            
                }
            }
        }
    }
    
    private function addTax($costItem) 
    {        
        $tax = ($costItem->tax + 100) / 100;
        
        if($costItem->basecots && !$this->taxAdded) 
        {
            $costItem->basecots = $costItem->basecots * $tax;
        }

        if($costItem->costs && !$this->taxAdded) 
        {
            $costItem->costs = $costItem->costs * $tax;
        }

        if($costItem->special && !$this->taxAdded) 
        {
            $costItem->special = $costItem->special * $tax;
        }
        
        $this->taxAdded = true;        
        return $costItem;
    }
    
    private function listCalculator($from, $to)    
    {
        $newCosts = new \stdClass();
                    
        if($from->basecots) 
        {
            if($to->default_currency) 
            {
                $newCosts->basecots = $from->basecots / $from->pricelist->currency->exchange_ratio;
            }
            else
            {
                if($from->pricelist->currency->default_currency)
                {
                    $newCosts->basecots = $from->basecots * $to->exchange_ratio;
                }            
                else
                {
                    $newCosts->basecots = ($from->basecots / $from->pricelist->currency->exchange_ratio) * $to->exchange_ratio;                
                }                
            }        
        }

        if($from->costs) 
        {
            if($to->default_currency) 
            {
                $newCosts->costs = $from->costs / $from->pricelist->currency->exchange_ratio;
            }
            else
            {
                if($from->pricelist->currency->default_currency)
                {
                    $newCosts->costs = $from->costs * $to->exchange_ratio;
                }
                else
                {
                    $newCosts->costs = ($from->costs / $from->pricelist->currency->exchange_ratio) * $to->exchange_ratio;
                }                
            }
        }

        if($from->special) 
        {
            if($to->default_currency) 
            {
                $newCosts->special = $from->special / $from->pricelist->currency->exchange_ratio;
            }
            else
            {
                if($from->pricelist->currency->default_currency)
                {
                    $newCosts->special = $from->special * $to->exchange_ratio;
                }
                else
                {
                    $newCosts->special = ($from->special / $from->pricelist->currency->exchange_ratio) * $to->exchange_ratio;
                }                
            }
        }

        $pricelist = (array) $from->pricelist;
        $pricelist['currency'] = $to;

        $newCosts->amount    = $from->amount;
        $newCosts->tax       = $from->tax;
        $newCosts->pricelist = (object) $pricelist;
        
        return $newCosts;    
    }
    
    private function formatCosts($costItem)
    {
        if($costItem->basecots) 
        {
            $costItem->basecots = sprintf("%01.2f", round($costItem->basecots, 2));
        }

        if($costItem->costs) 
        {
            $costItem->costs = sprintf("%01.2f", round($costItem->costs, 2));
        }

        if($costItem->special) 
        {
            $costItem->special = sprintf("%01.2f", round($costItem->special, 2));
        }
    
        return $costItem;    
    } 
    
    private function getPricelist($id) 
    {
        $objList = $this->Database->prepare("SELECT * FROM tl_shop_pricelists WHERE id = ?")->execute($id);
        $objList = (object) $objList->row();
        
        $objCurrency = $this->getCurrency($objList->currency);
        $objList->currency = $objCurrency;
                
        return $objList;                    
    }
    
    public function getCosts($quantity, $addTax = true, $defaultCosts = false)
    {
        if(count($this->memberList) && !$defaultCosts)
        {
            $pricelist = $this->memberList;
            $loadMemberList = true;    
        }
        else
        {
            $pricelist = array($this->defaultList->id => $this->defaultList);
            $loadMemberList = false;    
        }
        
        if(is_array($this->costsStructur)) 
        {                        
            foreach($this->costsStructur as $costItem) 
            {
                if(array_key_exists($costItem->pricelist->id, $pricelist) && ($quantity >= $costItem->amount))
                {
                    $returnValue = $costItem;
                }
            }            
        }
                               
        if($loadMemberList)
        {
            if(!$returnValue)
            {            
                $defaultValue = $this->getCosts($quantity, false, true);                
                $returnValue  = $defaultValue;  
            }                        
        }
        
        $firstElement     = reset($this->memberList);
        $defaultGroupList = $this->getPricelist($firstElement->id);
        
        if($defaultGroupList->type)
        {
            $pchange            = (array) $returnValue->pricelist;
            $pchange['type']    = $defaultGroupList->type;

            $newValue            = new \stdClass();
            $newValue->tax       = $returnValue->tax; 
            $newValue->basecots  = $returnValue->basecots;
            $newValue->costs     = $returnValue->costs;
            $newValue->amount    = $returnValue->amount;
            $newValue->special   = $returnValue->special;
            $newValue->pricelist = (object) $pchange;
                         
            $returnValue = $newValue;
        }
       
        if($_SESSION['ACQUISTO']['CONFIG']['CURRENCY'] != $returnValue->pricelist->currency->id)
        {
            $returnValue = $this->listCalculator($returnValue, $this->getCurrency($_SESSION['ACQUISTO']['CONFIG']['CURRENCY']));    
        }
        
        if($returnValue->pricelist->type == 'brutto' && $addTax)
        {            
            $returnValue = $this->addTax($returnValue);
        }

        return $this->formatCosts($returnValue);    
    }
    
    public function buildCostsData($costsArray, $volumen = null, $calculate = null) 
    {
        if(is_array($costsArray)) 
        {
            foreach($costsArray as $costItem)            
            {
                
                if((array_key_exists('group', $costItem) && $costItem['group'] == 0) OR !array_key_exists('group', $costItem) && !$costItem['pricelist']) 
                {
                    $costItem['pricelist'] = $this->defaultList->id;
                    unset($costItem['group']);
                }
                
                if(array_key_exists('pricelist', $costItem))
                {
                    if(empty($costItem['specialcosts']) OR !$costItem['specialcosts']) 
                    {
                        $costItem['specialcosts'] = false;
                    } 
                    else 
                    {
                        $costItem['specialcosts'] = $costItem['specialcosts'];
                    }

                    $costItem['basecosts'] = null;    
                    if($calculate && $volumen) 
                    {
                        $costItem['basecosts'] = $costItem['label'] * $calculate / $volumen;
                    }                    
                    
                    $objItem = (object) array(
                        'tax'       => $this->tax,
                        'basecots'  => $costItem['basecosts'],
                        'pricelist' => $this->getPricelist($costItem['pricelist']),
                        'costs'     => $costItem['label'],
                        'amount'    => $costItem['value'],
                        'special'   => $costItem['specialcosts']
                    );
                    
                    $arrSortCosts[] = $objItem;
                }
            }
            
            $this->costsStructur = $arrSortCosts;            
        }
    }
}

?>