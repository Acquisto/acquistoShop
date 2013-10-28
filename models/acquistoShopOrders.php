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

class acquistoShopOrders extends \Controller {
    public function __construct() {
        parent::__construct();
        $this->Import('Database');
        $this->Import('AcquistoShop\acquistoShopProduktLoader', 'Produkt');
        $this->Import('AcquistoShop\acquistoShop', 'Shop');        
    }
    
//     public function list($member_id) {
//         $objOrders = $this->Database->prepare("SELECT * FROM tl_shop_orders WHERE member_id=?")->execute($member_id);
//         while($objOrders->next()) {
//             $objOrder = (object) $objOrders->row();
//         
//         }
//         
//         return $arrOrders;
//     }
    
    public function getComplettOrder($orderId)
    {
        $objOrder    = $this->Database->prepare("SELECT * FROM tl_shop_orders WHERE id=?")->execute($orderId);
        $objShipping = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen WHERE id = ?")->limit(1)->execute($objOrder->versandzonen_id);
        $objPayment  = $this->Database->prepare("SELECT * FROM tl_shop_zahlungsarten WHERE id = ?")->limit(1)->execute($objOrder->zahlungsart_id);

        $objOrder = $objOrder->row();
        
        $objOrder['items']             = $this->getOrderItems($orderId);
        $objOrder['customData']        = (object) unserialize($objOrder['customData']);
        $objOrder['customFields']      = (object) unserialize($objOrder['customFields']);
        $objOrder['customerData']      = (object) unserialize($objOrder['customerData']);
        $objOrder['deliverAddress']    = (object) unserialize($objOrder['deliverAddress']);
        $objOrder['currency_default']  = (object) unserialize($objOrder['currency_default']);
        $objOrder['currency_selected'] = (object) unserialize($objOrder['currency_selected']);
        $objOrder['payment']           = (object) $objPayment->row();
        $objOrder['shipping']          = (object) $objShipping->row();
        $objOrder['versandpreis']      = sprintf("%01.2f", $objOrder['versandpreis']);
        $objOrder['orderDate']         = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objOrder['tstamp']);
        
        return (object) $objOrder;        
    }
    
    public function getOrderItems($orderId) 
    {
        $objOrder    = $this->Database->prepare("SELECT tstamp FROM tl_shop_orders WHERE id=?")->execute($orderId);
        $objOrderItems = $this->Database->prepare("SELECT * FROM tl_shop_orders_items WHERE pid=?")->execute($orderId);
        while($objOrderItems->next()) 
        {
            $objItem = (object) $objOrderItems->row();
            $objSteuer = $this->Database->prepare("SELECT * FROM tl_shop_steuersaetze WHERE pid=? && tstamp<? ORDER  BY tstamp ASC")->limit(1)->execute($objItem->steuersatz_id, $objOrder->tstamp);                       

            $objItem->preis = round($objItem->preis * ($objSteuer->satz / 100 + 1), 2) / ($objSteuer->satz / 100 + 1);
            $objItem->summe = sprintf("%01.2f", $objItem->preis * $objItem->menge);  
            
            $arrItems[] = $objItem; 
        }
        
        return $arrItems;
    }    

    public function getSummary($orderId) 
    {
        $objOrder = $this->Database->prepare("SELECT * FROM tl_shop_orders WHERE id=?")->execute($orderId);
        $arrData  = $this->getTaxes($orderId);
        
        if(is_array($arrData)) 
        {
            foreach($arrData as $key => $data) 
            {
                $arrSummary['subtotal'] = $arrSummary['subtotal'] + $data->total;
                $arrSummary['total'] = $arrSummary['total'] + ($data->total + $data->tax);
            }
            
            if(!$objOrder->calculate_tax)
            {
                $arrSummary['total'] = $arrSummary['total'] - ($arrSummary['total'] - $arrSummary['subtotal']);
            }
            
            $arrSummary['subtotal'] = sprintf("%01.2f", $arrSummary['subtotal']);
            $arrSummary['shipping'] = sprintf("%01.2f", $objOrder->versandpreis);            
            $arrSummary['total']    = sprintf("%01.2f", $arrSummary['total'] + $objOrder->versandpreis);
            
        }
        
        return (object) $arrSummary;
    }    

    public function getTaxes($orderId) 
    {
        $objOrder = $this->Database->prepare("SELECT * FROM tl_shop_orders WHERE id=?")->execute($orderId);
        $objOrderItems = $this->getOrderItems($orderId);
        
        if(is_array($objOrderItems))
        {
            foreach($objOrderItems as $Item) 
            {
                $objSteuer = $this->Database->prepare("SELECT * FROM tl_shop_steuersaetze WHERE pid=? && tstamp<? ORDER  BY tstamp ASC")->limit(1)->execute($Item->steuersatz_id, $objOrder->tstamp);                       
                $arrSteuer[$objSteuer->satz]['total'] = sprintf("%01.2f", $arrSteuer[$objSteuer->satz]['total'] + ($Item->menge * $Item->preis));
                $arrSteuer[$objSteuer->satz]['tax'] = sprintf("%01.2f", round(($arrSteuer[$objSteuer->satz]['total'] * (($objSteuer->satz + 100) / 100)) - $arrSteuer[$objSteuer->satz]['total'], 2));
            }
            
            foreach($arrSteuer as $key => $tax)
            {
                $taxArray[$key] = (object) $tax;
            }             
        }
        
        return $taxArray;
    }    
}

?>