<?php
 
/**
 * The AcquistoShop extension allows to create an online 
 * store with the OpenSource Contao CMS. If you have
 * questions our website: http://www.contao-acquisto.de  
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

class acquistoShopBasket extends \Controller 
{
    private $dataArray = array();
    private $memberPricelist = null;
    public $cardType   = 'brutto';
    
    public function __construct()
    {
        parent::__construct();
        $this->Import('Database');
        $this->Import('AcquistoShop\acquistoShopProduktLoader', 'Produkt');
        $this->Import('AcquistoShop\acquistoShopCosts', 'Costs');
        $this->Import('FrontendUser', 'Member');
        
        $this->memberPricelist = \AcquistoShop\acquistoShopCosts::getSelectedPricelist($this->Member->groups);
        $this->buildBasket();               
    }
    
    /**
     * Baut aus den Angaben der Session die Produktliste auf
     */         
    private function buildBasket() 
    {
        $this->dataArray = array();
        if(is_array($_SESSION['acquistoShop'])) 
        {
            foreach($_SESSION['acquistoShop'] as $id => $data) 
            {
                if(is_array($data)) 
                {
                    foreach($data as $attributes => $anzahl) 
                    {
                        $this->dataArray[md5($id . $attributes)] = array(
                            'id'         => $id,
                            'attributes' => unserialize($attributes),
                            'menge'      => $anzahl
                        );
                    }
                } 
                else 
                {
                    $this->dataArray[md5($id)] = array(
                        'id'         => $id,
                        'menge'      => $data
                    );
                }        
            }
        }    
    }
    
    public function orderValue($guests) 
    {
        $objMinverkaufwert = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen_varten ORDER BY ab_einkaufpreis ASC")->limit(1)->execute();
        return $objMinverkaufwert->ab_einkaufpreis; 
        
    }

    public function loadProducts($urlParameter = null) 
    {
        $objPricelist = \AcquistoShop\acquistoShopCosts::getSelectedPricelist($this->Member->groups);

        if(is_array($this->dataArray)) 
        {
            foreach($this->dataArray as $hash => $data) 
            {
                $objElement = $this->Produkt->load($data['id'], $data['attributes']);

                $itemCosts = $objElement->getCosts($data['menge'])->costs;
                if($objElement->getCosts($data['menge'])->special) 
                {
                    $itemCosts = $objElement->getCosts($data['menge'])->special;
                }
                
                if($objPricelist->type == 'netto' && $this->cardType == 'brutto')
                {
                    $itemCosts = $itemCosts * (($objElement->getCosts($data['menge'])->tax + 100) / 100); 
                }
                elseif($objPricelist->type == 'brutto' && $this->cardType == 'netto')
                {
                    $itemCosts = $itemCosts / (($objElement->getCosts($data['menge'])->tax + 100) / 100);                 
                }
                
                $arrElement['id']            = $data['id'];
                $arrElement['bezeichnung']   = $objElement->bezeichnung;
                $arrElement['produktnummer'] = $objElement->produktnummer;
                $arrElement['attributes']    = $data['attributes'];
                $arrElement['attributelist'] = $objElement->attribute_list;
                $arrElement['hash']          = $hash;
                $arrElement['menge']         = $data['menge'];
                $arrElement['costsObject']   = $objElement->getCosts($data['menge']);
                $arrElement['summe']         = ($arrElement['menge'] * $itemCosts);
                $arrElement['steuer']        = $objElement->steuer;
                $arrElement['preise']        = $objElement->getCosts($arrElement['menge']);
                $arrElement['preis']         = $itemCosts;
                
                if($urlParameter) 
                {
                    $arrElement['url']       = sprintf($urlParameter, $objElement->alias);
                }               

                $arrBasket[$hash] = $arrElement;
            }
        }
        
        if (isset($GLOBALS['TL_HOOKS']['modifyBasket']) && is_array($GLOBALS['TL_HOOKS']['modifyBasket'])) 
        {
            foreach ($GLOBALS['TL_HOOKS']['modifyBasket'] as $callback) 
            {
                $this->Import($callback[0]);
                $arrBasket = $this->$callback[0]->$callback[1]($arrBasket);
            }
        }        

        return $arrBasket;
    }

    /**
     * Fügt ein Produkt dem Warenkorb hinzu
     */         
    public function add($id, $menge, $additionalBasket = null) 
    {
        if($additionalBasket) 
        {
            $_SESSION['acquistoShop'][$id][serialize($additionalBasket)] = $menge;
        } 
        else 
        {
            $_SESSION['acquistoShop'][$id] = $menge;
        }
        
        $this->buildBasket();
    }

    /**
     * Entfernt ein Produkt aus dem Warenkorb
     */         
    public function remove($hash) 
    {
        if($this->dataArray[$hash]['attributes']) 
        {
            unset($_SESSION['acquistoShop'][$this->dataArray[$hash]['id']][serialize($this->dataArray[$hash]['attributes'])]);
        } 
        else 
        {
            unset($_SESSION['acquistoShop'][$this->dataArray[$hash]['id']]);
        }

        $this->buildBasket();
        \AcquistoShop\acquistoShop::reload(substr($this->Environment->requestUri, 1, strpos($this->Environment->requestUri, '?remove') - 1));        
    }

    /**
     * Überschrift die Menge eines Produkts im Warenkorb
     */         
    public function update($hash, $menge) 
    {
        if($this->dataArray[$hash]['attributes']) 
        {
            $_SESSION['acquistoShop'][$this->dataArray[$hash]['id']][serialize($this->dataArray[$hash]['attributes'])] = $menge;
        } 
        else 
        {
            $_SESSION['acquistoShop'][$this->dataArray[$hash]['id']] = $menge;
        }

        $this->buildBasket();    
        \AcquistoShop\acquistoShop::reload();
    }

    public function getWeight() 
    {
        if(is_array($_SESSION['acquistoShop'])) 
        {
            foreach($this->dataArray as $data) 
            {
                $Produkt = $this->Produkt->load($data['id'], $data['attributes']);
                $floatWeight = $floatWeight + ($Produkt->gewicht * $data['menge']);
            }
        }

        return $floatWeight;
    }

    private function identifyCostsByCardType($objProduct) 
    {
        if($this->cardType == 'brutto') 
        {

        } 
        elseif($this->cardType == 'netto') 
        {
            $itemCosts = $objProduct->getCosts($data['menge'])->costs_netto;                  
            if($objProduct->getCosts($data['menge'])->special_netto) 
            {
                $itemCosts = $objProduct->getCosts($data['menge'])->special_netto;
            }                    
        }

        return $itemCosts;   
    }

    /**
     * Gibt den Endpreis des Warenkorb zurück
     */         
    public function getCosts($taxAdd = false) 
    {
        $objPricelist = \AcquistoShop\acquistoShopCosts::getSelectedPricelist($this->Member->groups);

        if(is_array($_SESSION['acquistoShop'])) 
        {
            foreach($this->dataArray as $data) 
            {
                $objProdukt = $this->Produkt->load($data['id'], $data['attributes']);
                $itemCosts = $objProdukt->getCosts($data['menge'])->costs;
                if($objProdukt->getCosts($data['menge'])->special) 
                {
                    $itemCosts = $objProdukt->getCosts($data['menge'])->special;
                }
                                                
                if($objPricelist->type == 'netto' && $this->cardType == 'brutto')
                {
                    $itemCosts = $itemCosts * (($objProdukt->getCosts($data['menge'])->tax + 100) / 100); 
                }
                elseif($objPricelist->type == 'brutto' && $this->cardType == 'netto')
                {
                    $itemCosts = $itemCosts / (($objProdukt->getCosts($data['menge'])->tax + 100) / 100);                 
                }                                
                
                $floatCosts = $floatCosts + ($itemCosts * $data['menge']);
                
            }
            
            if($this->cardType == 'netto' && $taxAdd) 
            {
                $arrSteuern = $this->getTaxes();
                foreach($arrSteuern as $taxValue) 
                {
                    $floatCosts = $floatCosts + $taxValue['wert'];
                }
            }            
        }

        return $floatCosts;
    }

    public function itemNum() 
    {    
        $arrData = array(
            'summe' => 0,
            'items' => 0
        );

        if(is_array($_SESSION['acquistoShop'])) 
        {
            foreach($this->dataArray as $data) 
            {
                $items  = $items + $data['menge'];
            }

            if($this->memberPricelist->type == 'netto')
            {
                $summaryCosts = $this->getCosts(false);
            }
            elseif($this->memberPricelist->type == 'brutto')
            {
                $summaryCosts = $this->getCosts(true);
            }

            $arrData = array(
                'summe' => $summaryCosts,
                'items' => $items
            );
        }

        return $arrData;
    }

    /**
     * Berechnet die Steuersätze
     */         
    public function getTaxes() 
    {
        if(is_array($this->loadProducts())) 
        {
            foreach($this->loadProducts() as $data) 
            {
                $objSteuer  = $this->Database->prepare("SELECT * FROM tl_shop_steuersaetze WHERE pid=? && gueltig_ab < NOW() ORDER BY gueltig_ab ASC")->limit(1)->execute($data['steuer']);
                $arrSteuern[$objSteuer->satz ] = $arrSteuern[$objSteuer->satz ] + $data['summe'];
            }
            
            if(is_array($arrSteuern)) 
            {
                foreach($arrSteuern as $steuersatz => $wert) 
                {
                    $dblSteuer = ($steuersatz + 100) / 100;

                    if($this->cardType == 'brutto') 
                    {
                        $arrSteuerberechnung[$steuersatz]['satz']  = $steuersatz;
                        $arrSteuerberechnung[$steuersatz]['wert']  = round($wert - ($wert / $dblSteuer), 2);
                        $arrSteuerberechnung[$steuersatz]['summe'] = $wert;
                    
                    }
                    elseif($this->cardType == 'netto')
                    {
                        $arrSteuerberechnung[$steuersatz]['satz']  = $steuersatz;
                        $arrSteuerberechnung[$steuersatz]['wert']  = round(($wert * $dblSteuer) - $wert, 2);
                        $arrSteuerberechnung[$steuersatz]['summe'] = $wert;                    
                    }
                }
            }
        }

        return $arrSteuerberechnung;
    }

    public function getTaxesSummary() 
    {        
        if(is_array($arrSteuern = $this->getTaxes())) 
        {
            foreach($arrSteuern as $steuersatz => $wert) 
            {
                $taxSummary = $taxSummary + $wert['wert'];
            }
        }
        
        return $taxSummary;
    }

    public function writeOrder($memberId = 0, $shippingId = 0, $fieldsAddon = null) {
        if(!$memberId)
        {
            $memberId = 0;
        }
                
        $objVersandart    = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen_varten WHERE id = ?;")->execute($shippingId);
        $objVersandzone   = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen WHERE id = ?;")->execute($objVersandart->pid);
        
        $this->Database->prepare("INSERT INTO tl_shop_orders (tstamp, calculate_tax, member_id, versandzonen_id, zahlungsart_id, versandart_id, gutscheine, payed, versandpreis, customerData, customFields, customData, deliverAddress, currency_default, currency_selected) VALUES(" . time() . ", '" . $objVersandzone->calculate_tax . "', " . $memberId . ", " . $objVersandart->pid . ", " . $objVersandart->zahlungsart_id . ", " . $shippingId . ", '', '" . $payState . "', " . $objVersandart->preis . ", '" . serialize($this->Session->get('Customer')) . "', '" . serialize($this->Session->get('Custom')) . "', '" . serialize($this->Session->get('Custom')) . "', '" . serialize($this->Session->get('Deliver')) . "', '" . serialize(\AcquistoShop\acquistoShopCosts::getCurrency(\AcquistoShop\acquistoShopCosts::getDefaultCurrency())) . "', '" . serialize(\AcquistoShop\acquistoShopCosts::getCurrency(\AcquistoShop\acquistoShopCosts::getSelectedCurrency())) . "')")->execute();
        #" . serialize($this->Gutschein->Checkout($this->User->id)) . "

        $objOrder   = $this->Database->prepare("SELECT LAST_INSERT_ID() AS lid FROM tl_shop_orders")->execute();
        $objCounter = $this->Database->prepare("SELECT COUNT(*) AS totalNumbers FROM tl_shop_orders WHERE tstamp > ?")->execute(mktime(0, 0, 0, 1, 1, date("Y")));
        
        $orderID = 1;
        if($objCounter->totalNumbers) 
        {
            $objCounter = $this->Database->prepare("SELECT MAX(order_id) AS maxID FROM tl_shop_orders WHERE tstamp > ?")->execute(mktime(0, 0, 0, 1, 1, date("Y")));
            $orderID = $objCounter->maxID + 1;
        }

        $this->Database->prepare("UPDATE tl_shop_orders SET order_id = " . $orderID . " WHERE id = ?")->execute($objOrder->lid);

        foreach($this->loadProducts($strUrl) as $hash => $data) 
        {
            $objProdukt = $this->Produkt->load($data['id'], $data['attributes']);
            
            $this->Costs->buildCostsData(unserialize($objProdukt->preise));
            $this->Costs->setTax($data['steuer']);
            $this->Costs->setGroups($this->Member->groups);

            $itemCosts = $this->Costs->getCosts($data['menge'], false)->costs;
            if($this->Costs->getCosts($data['menge'], false)->special) 
            {
                $itemCosts = $this->Costs->getCosts($data['menge'], false)->special;
            }
            
            $this->Database->prepare("INSERT INTO tl_shop_orders_items (pid, tstamp, produkt_id, bezeichnung, menge, preis, attribute, steuersatz_id) VALUES(" . $objOrder->lid . ", " . time() . ", " . $data['id'] . ", '" . $data['bezeichnung'] . "', '" . $data['menge'] . "', '" . round(\AcquistoShop\acquistoShopCosts::costsCalculator($itemCosts, \AcquistoShop\acquistoShopCosts::getSelectedCurrency(), \AcquistoShop\acquistoShopCosts::getDefaultCurrency()), 2) . "', '" . serialize($data['attributes'])  . "', '" . $data['steuer'] . "')")->execute();
        }
        
        $this->clear();                                        
        return $objOrder->lid;                                        
    }
    
    public function sendOrderMail($mailTemplate, $mailRecipient, $cardData) 
    {
        if(isset($GLOBALS['TL_HOOKS']['modfiyCardMail']) && is_array($GLOBALS['TL_HOOKS']['modfiyCardMail'])) 
        {
            foreach ($GLOBALS['TL_HOOKS']['modfiyCardMail'] as $callback) 
            {
                $this->Import($callback[0]);
                $arrCardMail = $this->$callback[0]->$callback[1]($cardData);
            }
        }     

        $mailTemplate = new \FrontendTemplate($mailTemplate);
        if(is_array($cardData['templateVars']) && count($cardData['templateVars']))
        {
            foreach($cardData['templateVars'] as $key => $values)
            {
                $mailTemplate->$key = $values;
            }
        }

        $objEmail = new \Email();
        
        if($cardData['emailFrom']['fromMail']) {
            $objEmail->from     = $cardData['emailFrom']['fromMail'];
        }
        
        if($cardData['emailFrom']['fromName']) {
            $objEmail->fromName = $cardData['emailFrom']['fromName'];
        }
        
        $objEmail->subject  = $cardData['emailSubject'];

        if(!$cardData['emailTyp'] OR $cardData['emailTyp']) {
            $objEmail->text = $mailTemplate->parse();       
        } elseif($cardData['emailTyp'] == 'html') {
            $objEmail->html = $mailTemplate->parse();
        }

        if($cardData['agbFile']) {
            $objFile = FilesModel::findByPk($cardData['agbFile']);
            $objEmail->attachFile(TL_ROOT . '/' . $objFile->path);
        }

        if(is_array($cardData['attachments']) && count($cardData['attachments'])) {
            foreach($cardData['attachments'] as $attachment) {
                $objFile = FilesModel::findByPk($attachment);
                $objEmail->attachFile(TL_ROOT . '/' . $objFile->path);
            }
        }
                              
        if(count($cardData['sendTo']['bcc'])) {
            foreach($cardData['sendTo']['bcc'] as $bcc) {
                if(trim($bcc)) {
                    $objEmail->sendBcc($bcc);
                }
            }
        }

        if(count($cardData['sendTo']['to'])) {
            if(!in_array($mailRecipient, $cardData['sendTo']['to'])) {
                $cardData['sendTo']['to'][] = $mailRecipient;
            }
            
            foreach($cardData['sendTo']['to'] as $to) {
                if(trim($to)) {
                    $objEmail->sendTo($to);
                }
            }
        }        
    }
    
    public function clear() 
    {
        unset($_SESSION['acquistoShop']);
        $this->buildBasket();
    }

    public function __set($name, $value) 
    {
        $this->$name = $value;
    }
}

?>