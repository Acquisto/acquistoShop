<?php

/**
 * The AcquistoShop extension allows the creation a OnlineStore width 
 * the OpenSource Contao CMS. For Question visit our Website under
 * http://www.contao-acquisto.de  
 *
 * PHP version 5
 * @package	   AcquistoShop
 * @subpackage 
 * @author     Sascha Brandhoff <brandhoff@contao-acquisto.de>
 * @copyright  AcquistoShop
 * @license    LGPL.
 * @filesource
 */                                
 
namespace AcquistoShop\Helper;

class AcquistoUpdate {
       
  	protected static function DB()
  	{
  		// TODO: do we need to ensure the existance of the Contao object stack before somehow?
  		return \Database::getInstance();
  	} 
  
    static function upgradeVersion() 
    {
        $objDatabase = self::DB();
        
        /**
         * Module umbennen
         */                 
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('ModuleAcquistoProductDetails', 'acquistoShop_Produktdetails');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('ModuleAcquistoCoupon',         'acquistoShop_Gutschein');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('ModuleAcquistoTagCloud',       'acquistoShop_TagCloud');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('ModuleAcquistoOrderList',      'acquistoShop_Bestellliste');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('ModuleAcquistoOrderDetails',   'acquistoShop_Bestelldetails');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('ModuleAcquistoBasketWidget',   'acquistoShop_WarenkorbWidget');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('ModuleAcquistoRecently',       'acquistoShop_Recent');
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('ModuleAcquistoProductList',    'acquistoShop_Produktliste');            
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('ModuleAcquistoBreadcrumb',     'acquistoShop_Breadcrumb');            
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('ModuleAcquistoSearch',         'acquistoShop_Suche');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('ModuleAcquistoCategories',     'acquistoShop_Warengruppen');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('ModuleAcquistoProductFilter',  'acquistoShop_Produktfiler');            
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('ModuleAcquistoShipping',       'acquistoShop_Versand');
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('ModuleAcquistoBasket',         'acquistoShop_Warenkorb');        
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('ModuleAcquistoFilterList',     'acquistoShop_Filterliste');    

        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('ModuleAcquistoFilterList',     'ModuleFilterList');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('ModuleAcquistoTerms',          'ModuleAcquistoAGB');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('ModuleAcquistoShipping',       'ModuleAcquistoVersand');            
        
        
        /**
         * Steuerberechnungsfeld umstellen
         */                 
        $objDatabase->prepare("UPDATE tl_shop_orders SET calculate_tax = '1' WHERE calculate_tax = 'Y'")->execute();    
        $objDatabase->prepare("UPDATE tl_shop_orders SET calculate_tax = '' WHERE calculate_tax = 'N'")->execute();    
    }
    
    static function cardCurrency()
    {
        $objDatabase = self::DB();
        $objDatabase->prepare("UPDATE tl_shop_orders SET currency_default = '" . serialize(\AcquistoShop\acquistoShopCosts::getCurrency(\AcquistoShop\acquistoShopCosts::getDefaultCurrency())) . "' WHERE currency_default = ''")->execute();                     
        $objDatabase->prepare("UPDATE tl_shop_orders SET currency_selected = '" . serialize(\AcquistoShop\acquistoShopCosts::getCurrency(\AcquistoShop\acquistoShopCosts::getDefaultCurrency())) . "' WHERE currency_selected = ''")->execute();                     
    }
    
    static function taxProducts()
    {
        $objDatabase = self::DB();

        $objProdukte = $objDatabase->prepare("SELECT id,preise FROM tl_shop_produkte WHERE calculateTax = ''")->execute();
        while($objProdukte->next()) 
        {
            $arrayCosts = unserialize($objProdukte->preise);
            $newCosts   = array();
            $Tax   = $objDatabase->prepare("SELECT * FROM tl_shop_steuersaetze WHERE pid=? ORDER  BY tstamp ASC")->limit(1)->execute($objProdukte->steuer);           
            
            if(is_array($arrayCosts))
            {
                foreach($arrayCosts as $Costs)
                {
                    $Costs = (object) $Costs; 
                    $pricelist = false;
                                
                    if(!$Costs->group == 0 OR !$Costs->group) 
                    {
                        $pricelist = 1;
                    }
                                
                    if($pricelist)
                    {            
                        $newCosts[] = array(
                            'value'        => $Costs->value,
                            'label'        => round($Costs->label / (($Tax->satz + 100) / 100), 2),          
                            'specialcosts' => self::calculate($Tax->satz, $Costs->specialcosts),
                            'pricelist'    => $pricelist
                        );                
                    }
                    
                    $objDatabase->prepare("UPDATE tl_shop_produkte SET preise = ?, calculateTax = '1' WHERE id = ?")->execute(serialize($newCosts), $objProdukte->id);
                }
            }
            else
            {
                $objDatabase->prepare("UPDATE tl_shop_produkte SET calculateTax = '1' WHERE id = ?")->execute($objProdukte->id);
            }            
        }                                      
    } 
    
    protected static function calculate($tax, $costs)
    {
        if($costs && $tax)
        {
            return round($costs / (($tax + 100) / 100), 2);
        }
    }  
}

?>