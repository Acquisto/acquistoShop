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
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('acquisto_productdetails', 'acquistoShop_Produktdetails');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('acquisto_coupon',         'acquistoShop_Gutschein');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('acquisto_tagcloud',       'acquistoShop_TagCloud');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('acquisto_orderlist',      'acquistoShop_Bestellliste');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('acquisto_orderdetails',   'acquistoShop_Bestelldetails');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('acquisto_basketwidget',   'acquistoShop_WarenkorbWidget');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('acquisto_recently',       'acquistoShop_Recent');
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('acquisto_productlist',    'acquistoShop_Produktliste');            
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('acquisto_breadcrumb',     'acquistoShop_Breadcrumb');            
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('acquisto_search',         'acquistoShop_Suche');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('acquisto_categories',     'acquistoShop_Warengruppen');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('acquisto_productfilter',  'acquistoShop_Produktfiler');            
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('acquisto_shipping',       'acquistoShop_Versand');
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('acquisto_basket',         'acquistoShop_Warenkorb');        
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('acquisto_filterlist',     'acquistoShop_Filterliste');    

        
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('acquisto_basket',         'ModuleAcquistoBasket');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('acquisto_filterlist',     'ModuleFilterList');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('acquisto_terms',          'ModuleAcquistoAGB');    
        $objDatabase->prepare("UPDATE tl_module SET type=? WHERE type=?")->execute('acquisto_shipping',       'ModuleAcquistoVersand');            
        
        
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
    
    static function rebuildCss()
    {
        $objDatabase = self::DB();
        $objCss = $objDatabase->prepare("SELECT id,selector FROM tl_style WHERE selector LIKE '%.mod_acquistoShop%' OR selector LIKE '%.mod_ModuleAcquisto%' OR selector LIKE '%.mod_ModuleFilterList%'")->execute();
        while($objCss->next()) 
        {
            $selector = $objCss->selector;

            if(substr_count($selector, 'mod_acquistoShop_Warengruppen'))
            {
                $selector = str_replace('mod_acquistoShop_Warengruppen', 'mod_acquisto_categories', $selector);            
            }
            
            if(substr_count($selector, 'mod_acquistoShop_Produktliste'))
            {
                $selector = str_replace('mod_acquistoShop_Produktliste', 'mod_acquisto_productlist', $selector);            
            }
            
            if(substr_count($selector, 'mod_acquistoShop_Suche'))
            {
                $selector = str_replace('mod_acquistoShop_Suche', 'mod_acquisto_search', $selector);            
            }
            
            if(substr_count($selector, 'mod_acquistoShop_Produktfiler'))
            {
                $selector = str_replace('mod_acquistoShop_Produktfiler', 'mod_acquisto_productfilter', $selector);            
            }
            
            if(substr_count($selector, 'mod_acquistoShop_WarenkorbWidget'))
            {
                $selector = str_replace('mod_acquistoShop_WarenkorbWidget', 'mod_acquisto_basketwidget', $selector);            
            }
            
            if(substr_count($selector, 'mod_acquistoShop_Warenkorb'))
            {
                $selector = str_replace('mod_acquistoShop_Warenkorb', 'mod_acquisto_basket', $selector);            
            }
            if(substr_count($selector, 'mod_acquistoShop_Produktdetails'))
            {
                $selector = str_replace('mod_acquistoShop_Produktdetails', 'mod_acquisto_productdetails', $selector);            
            }
            
            if(substr_count($selector, 'mod_acquistoShop_Breadcrumb'))
            {
                $selector = str_replace('mod_acquistoShop_Breadcrumb', 'mod_acquisto_breadcrumb', $selector);            
            }
            
            if(substr_count($selector, 'mod_acquistoShop_TagCloud'))
            {
                $selector = str_replace('mod_acquistoShop_TagCloud', 'mod_acquisto_tagcloud', $selector);            
            }
            
            if(substr_count($selector, 'mod_acquistoShop_Gutschein'))
            {
                $selector = str_replace('mod_acquistoShop_Gutschein', 'mod_acquisto_coupon', $selector);            
            }
            
            if(substr_count($selector, 'mod_acquistoShop_Bestellliste'))
            {
                $selector = str_replace('mod_acquistoShop_Bestellliste', 'mod_acquisto_orderlist', $selector);            
            }
            
            if(substr_count($selector, 'mod_acquistoShop_Bestelldetails'))
            {
                $selector = str_replace('mod_acquistoShop_Bestelldetails', 'mod_acquisto_orderdetails', $selector);            
            }
            
            if(substr_count($selector, 'mod_acquistoShop_Recent'))
            {
                $selector = str_replace('mod_acquistoShop_Recent', 'mod_acquisto_recent', $selector);            
            }
            
            if(substr_count($selector, 'mod_ModuleAcquistoAGB'))
            {
                $selector = str_replace('mod_ModuleAcquistoAGB', 'mod_acquisto_terms', $selector);            
            }
            
            if(substr_count($selector, 'mod_ModuleFilterList'))
            {
                $selector = str_replace('mod_ModuleFilterList', 'mod_acquisto_filterlist', $selector);            
            }
            
            if(substr_count($selector, 'mod_acquistoShop_Filterliste'))
            {
                $selector = str_replace('mod_acquistoShop_Filterliste', 'mod_acquisto_filterlist', $selector);            
            }
            
            if(substr_count($selector, 'mod_ModuleAcquistoVersand'))
            {
                $selector = str_replace('mod_ModuleAcquistoVersand', 'mod_acquisto_shipping', $selector);            
            }

            $objDatabase->prepare("UPDATE tl_style SET selector = '" . $selector . "' WHERE id = " . $objCss->id . "")->execute();
        }
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