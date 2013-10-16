<?php 

/**
 * The AcquistoShop extension allows to create an online 
 * store with the OpenSource Contao CMS. If you have
 * questions our website: http://www.contao-acquisto.de  
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
 
class ModuleAcquistoBasket extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate          = 'mod_warenkorb';
    protected $strVersandberechnung = null;
    protected $floatBerechnung      = 0;
    private $basketData             = array();


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate() 
    {
        if (TL_MODE == 'BE') 
        {
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO BASKET ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        /**
         * Import der entsprechenden Klassen die für den Warenkorb 
         * benötigt werden.
         */                  
        $this->Import('AcquistoShop\acquistoShop',              'Shop');
        $this->Import('AcquistoShop\acquistoShopBasket',        'Basket');
        $this->Import('AcquistoShop\acquistoShopPayment',       'Payment');
        $this->Import('AcquistoShop\acquistoShopProduktLoader', 'Produkt');
//         $this->Import('AcquistoShop\acquistoShopGutschein',     'Gutschein');
        $this->Import('AcquistoShop\acquistoShopOrders',        'Orders');
        $this->import('FrontendUser',                           'Member');
        
        /**
         * Setzen des Warenkorb-Typs (brutto, netto, durch Preisliste definiert)
         */
        
        $cardType = $this->acquistoShop_basketType;
        if($cardType == 'pricelist') 
        {
             $objPricelist = \AcquistoShop\acquistoShopCosts::getSelectedPricelist($this->Member->groups);
             $cardType = $objPricelist->type;
        }
                         
        $this->Basket->cardType = $cardType;
        
        if($this->Input->Post('FORM_SUBMIT') == 'tl_acquistoShop_warenkorb_update' && is_array($this->Input->Post('menge'))) 
        {
            foreach($this->Input->Post('menge') as $keyId => $menge) 
            {
                $this->Basket->update($keyId, $menge);
            }    
        }

        if($this->Input->Get('remove')) 
        {
            $this->Basket->remove($this->Input->Get('remove'));
        }                

        /**
         * Setzen der Berechnungsvariante für Versandkosten (Gewicht, Endpreis)
         */                 
        switch(strtolower($GLOBALS['TL_CONFIG']['versandberechnung'])) 
        {
            case "gewicht":
                $this->strVersandberechnung = "gewicht";
                $this->floatBerechnung = $this->Basket->getWeight();
                break;;
            default:
                $this->strVersandberechnung = "preis";
                $this->floatBerechnung = $this->Basket->getCosts();
                break;;
        }

        return parent::generate();
    }

    private function checkfields($fields = array(), $mandatory = array()) 
    {
        $this->loadDataContainer('tl_member');
        $dataArray = $this->buildfields($mandatory);
        $error = null;
        
        if(is_array($dataArray)) 
        {
            $this->Import('Validator');
            
            foreach($dataArray as $key => $value) 
            {                
                $arrData = &$GLOBALS['TL_DCA']['tl_member']['fields'][$key];
                $isValue = null;                    

                if($value->mandatory == 2 && !$fields[$key]) 
                {
                    $error[$key] = true;                                    
                }
                               
                if($fields[$key]) 
                {
                    switch($arrData['eval']['rgxp']) 
                    {
                        case "digit":
                            $isValue = "isNumeric";                
                            break;;
                        case "alpha":
                            $isValue = "isAlphabetic";                
                            break;;
                        case "alnum":
                            $isValue = "isAlphanumeric";                
                            break;;
                        case "prcnt":
                            $isValue = "isPercent";                
                            break;;
                        case "date":
                            $isValue = "isDate";                
                            break;;
                        case "time":
                            $isValue = "isTime";                
                            break;;
                        case "dateim":
                            $isValue = "isDatim";                
                            break;;
                        case "email":
                            $isValue = "isEmail";                
                            break;;
                        case "url":
                            $isValue = "isUrl";                
                            break;;
                        case "phone":
                            $isValue = "isPhone";                                    
                            break;;
                    }
                }
                
                if($isValue) 
                {                
                    if(!$this->Validator->$isValue($fields[$key])) 
                    {
                        $error[$key] = true;
                    }
                }
            }
        }
                
        return $error;    
    }
    
    private function buildfields($fields = array(), $error = array()) 
    {
        $this->loadLanguageFile('tl_member');
        
        
        if(is_array($fields)) 
        {
            foreach($fields as $value) 
            {
                if($value['field']) 
                {
                    $mandatorySelect = false;
                    if($value['mandatory'] == 2) 
                    {
                            $mandatorySelect = true;                                                        
                    }
    
                    $rebuildFields[$value['field']] = (object) array(                
                        'label'     => $GLOBALS['TL_LANG']['tl_member'][$value['field']][0],
                        'key'       => $value['field'],
                        'mandatory' => $mandatorySelect,
                        'error'     => $error[$value['field']],
                        'errorMessage' => $value['error'],
                        'infoMessage'  => $value['info']
                    );
                }
            }
        }

        return $rebuildFields;        
    }
    
    private function explodeValues($values, $selected = null) {
        $explodeValue = explode(",", $values);
        
        if(is_array($explodeValue) && count($explodeValue)) {
            foreach($explodeValue as $value) {
                $selection = false;
                
                if(substr_count($value, "&#61;") OR substr_count($value, "=")) {
                    if(substr_count($value, "&#61;")) {
                        $explodeSign = "&#61;";
                    } else {
                        $explodeSign = "=";
                    }
                    
                    $explodeSub = explode($explodeSign, $value);
                    
                    if(is_array($selected)) {
                        if(in_array($explodeSub[0], $selected)) {
                            $selection = true;                                            
                        }
                    } elseif($selected == $explodeSub[0]) {
                        $selection = true;                    
                    }
                       
                    $valueNew = (object) array(
                        'value'    => $explodeSub[0],
                        'label'    => $explodeSub[1],
                        'selected' => $selection
                    );
                } else {
                    if(is_array($selected)) {
                        if(in_array($value, $selected)) {
                            $selection = true;                                            
                        }
                    } elseif($selected == $value) {
                        $selection = true;                    
                    }

                    $valueNew = (object) array(
                        'value'    => $value,
                        'label'    => $value,
                        'selected' => $selection
                    );
                }          
    
                $return[] = $valueNew;
            }            
        }

        return $return;            
    }
    
    private function checkAddonFields($values = null, $fields) {
        $error = false;
        
        if(count($fields)) {
            foreach($fields as $value) {
                if($value['mandatory'] == 2 && !$values[$value['fieldname']]) {                    
                    $error = true;
                }
            }
        }
        
        return $error;
    }
    
    private function buildAddonFields($fields, $values = null) {
        if($this->Input->Post('custom')) {
            $fieldsPost = $this->Input->Post('custom');
        }
        
        if(count($fields)) {
            foreach($fields as $key => $value) {
                $value = (object) $value;
                if($value->fieldname && $value->fieldtype && $value->fieldtitle) {
                    $htmlOptions  = null;
                    $explodeValue = null;
                    $mandatory    = null;
                    $selected     = null;
                    $selectedText = null;
    
                    if($value->mandatory == 2) {
                        $mandatory = '*';
                        if(!$fieldsPost[$value->fieldname] && $fieldsPost) {
                            $value->error = true;
                        }    
                    }
                    
                    
                    switch($value->fieldtype) {                   
                        case "select":
                            $explodeValue = $this->explodeValues($value->value, $values[$value->fieldname]);
                            
                            foreach($explodeValue as $optionValue) {
                                if($optionValue->selected) {
                                    $selected = ' selected="selected"';
                                    $selectedText = $optionValue->label;
                                }
                                
                                $htmlOptions .= '<option value="' . $optionValue->value . '"' . $selected . '>' . $optionValue->label . '</option>';
                            }
                        
                            $htmlObject = (object) array(
                                'label' => '<label for="addon_' . $key . '_' . $value->fieldname . '">' . $value->fieldtitle . $mandatory . '</label>',
                                'input' => '<select name="custom[' . $value->fieldname . ']" id="addon_' . $key . '_' . $value->fieldname . '" tabindex="' . $key . '">' . $htmlOptions . '</select>'
                            );
                            
                            $value->selected = $selectedText;                                            
                            break;;
                        case "text":
                            if($values[$value->fieldname]) {
                                $value->value = $values[$value->fieldname];
                            }
                        
                            $htmlObject = (object) array(
                                'label' => '<label for="addon_' . $key . '_' . $value->fieldname . '">' . $value->fieldtitle . $mandatory . '</label>',
                                'input' => '<input type="text" name="custom[' . $value->fieldname . ']" id="addon_' . $key . '_' . $value->fieldname . '" value="' . $value->value . '" tabindex="' . $key . '">'
                            );
                            
                            $value->selected = $values[$value->fieldname];                                            
                            break;;
                        case "checkbox":
                            $explodeValue = $this->explodeValues($value->value, $values[$value->fieldname]);
                            
                            foreach($explodeValue as $optionValue) {
                                $selected = null;
                                if($optionValue->selected) {
                                    $selected = 'checked="checked"';
                                    $selectedText[] = $optionValue->label;
                                }
    
                                $htmlOptions .= '<input type="checkbox" name="custom[' . $value->fieldname . '][]" id="addon_' . $key . '_' . $value->fieldname . '" value="' . $optionValue->value . '" tabindex="' . $key . '"' . $selected . '>' . $optionValue->label;
                            }
                        
                            $htmlObject = (object) array(
                                'label' => '<label for="addon_' . $key . '_' . $value->fieldname . '">' . $value->fieldtitle . $mandatory . '</label>',
                                'input' => $htmlOptions
                            );

                            if($selectedText) {
                                $value->selected = implode(", ", $selectedText);
                            }                                            
                            break;;
                        case "radio":
                            $explodeValue = $this->explodeValues($value->value, $values[$value->fieldname]);
    
                            foreach($explodeValue as $optionValue) {
                                if($optionValue->selected) {
                                    $selected = 'checked="checked"';
                                    $selectedText = $optionValue->label;
                                }
    
                                $htmlOptions .= '<input type="radio" name="custom[' . $value->fieldname . ']" id="addon_' . $key . '_' . $value->fieldname . '" value="' . $optionValue->value . '" tabindex="' . $key . '"' . $selected . '>' . $optionValue->label;
                            }
                            $htmlObject = (object) array(
                                'label' => '<label for="addon_' . $key . '_' . $value->fieldname . '">' . $value->fieldtitle . $mandatory . '</label>',
                                'input' => $htmlOptions
                            );
                            
                            $value->selected = $selectedText;                                            
                            break;;
                        case "textarea":
                            if($values[$value->fieldname]) {
                                $value->value = $values[$value->fieldname];
                            }
    
                            $htmlObject = (object) array(
                                'label' => '<label for="addon_' . $key . '_' . $value->fieldname . '">' . $value->fieldtitle . $mandatory . '</label>',
                                'input' => '<textarea name="custom[' . $value->fieldname . ']" id="addon_' . $key . '_' . $value->fieldname . '" tabindex="' . $key . '">' . $value->value . '</textarea>'
                            );
                            
                            $value->selected = $values[$value->fieldname];                                            
                            break;;
                    }
                    
                    if(is_array($values[$value->fieldname])) {
                        $values[$value->fieldname] = implode(", ", $values[$value->fieldname]);
                    }
                    
                    $value->html = $htmlObject;
                    $return[$key] = $value;            
                }
            }
        }
    
        return $return;
    }
    
    /**
     * Generate module
     */
    protected function compile() {
        if($this->acquistoShop_guestOrder) 
        {
            
        } 
        elseif(FE_USER_LOGGED_IN && !$this->acquistoShop_guestOrder) 
        {
        
        } 
        else 
        {
            $this->Template = new \FrontendTemplate('mod_warenkorb_access_denied');                
        }
    
        $this->basketData = $this->Session->get('basket_data');
        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->replaceInsertTags($this->Shop->getInsertTagPID()));

        switch(strtolower($this->Input->Get('do')))
        {
            case 'customer':
                $this->basketCustomer();                 
                break;;
            case 'payment-and-shipping':
                $this->basketPaymentAndShipping();
                break;;
            case 'agb':
                $this->basketTermsAndConditions();
                break;;
            case 'check-and-order':
                $this->basketCheckAndOrder();                
                break;;                
            case 'pay':
                $this->basketPay();
                break;;
            case 'key':                
//                 if($this->Input->Get('template')) {
//                     $this->Template = new \FrontendTemplate($this->Input->Get('template'));
//                     $this->Template->BasketUrl = $this->getBasketUrl();
//                 }
//                 
//                 if($this->Input->Get('function')) {
//                     $objVersandart    = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen_varten WHERE id = ?;")->execute($this->basketData['paymentMethod']);
//                     $objVersandzone   = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen WHERE id = ?;")->execute($objVersandart->pid);
//                     $objZahlungsarten = $this->Database->prepare("SELECT * FROM tl_shop_zahlungsarten WHERE id = ?;")->execute($objVersandart->zahlungsart_id);
//                                 
//                     $this->Template->Data = $this->Payment->openCallback($this->Input->Get('function'), $objZahlungsarten->payment_module, $objZahlungsarten->id);
//                 }
                break;;
            default:
                $this->basketDefault();
                break;;
        }
        
        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->replaceInsertTags($this->Shop->getInsertTagPID()));
        $arrWidgetData = $this->Basket->itemNum();

        /**
         * Variablen die in das Template geschrieben werden.
         */                 
        $this->Template->WarenkorbUrl = $this->generateFrontendUrl($objPage->fetchAssoc());
        $this->Template->cardType     = $this->Basket->cardType;
        $this->Template->Currency     = \AcquistoShop\acquistoShopCosts::getCurrency(\AcquistoShop\acquistoShopCosts::getSelectedCurrency());
    }

    public function getBasketUrl() 
    {
        return substr($this->Environment->requestUri, 1, strpos($this->Environment->requestUri, "?") - 1);
    }
    
    private function basketDefault() 
    {        
        if($this->Basket->cardType == 'netto')
        {
            $this->Template->Zwischensumme = $this->Basket->getCosts(false);        
        }

        if($this->contaoShop_jumpTo) 
        {
            $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
            $strUrl  = $this->generateFrontendUrl($objPage->fetchAssoc(), '/produkt/%s');
        }
        
        $this->Template->Steuern       = $this->Basket->getTaxes();        
        $this->Template->Endpreis      = $this->Basket->getCosts(true);
        $this->Template->Produktliste  = $this->Basket->loadProducts($strUrl);
    
        $this->Template->Bestellwert = true;
        $this->Template->Bestellzahl = \AcquistoShop\acquistoShopCosts::costsCalculator($this->Basket->orderValue(1), \AcquistoShop\acquistoShopCosts::getDefaultCurrency(), \AcquistoShop\acquistoShopCosts::getSelectedCurrency());

        if(\AcquistoShop\acquistoShopCosts::costsCalculator($this->Basket->orderValue(1), \AcquistoShop\acquistoShopCosts::getDefaultCurrency(), \AcquistoShop\acquistoShopCosts::getSelectedCurrency()) >= $this->Basket->getCosts(true)) 
        {
            $this->Template->Bestellwert = false;
            $this->Template->productsInBasket = count($this->Basket->loadProducts($strUrl));
        }     
    }
    
    private function basketCustomer() 
    {
        if($this->Input->Post('action')) 
        {
            $arrCustomer = $this->Input->Post('customer');
            $arrDeliver  = $this->Input->Post('deliver');
            $arrCustom   = $this->Input->Post('custom');
    
            $this->Session->set('Customer', $arrCustomer);
            $this->Session->set('Deliver', $arrDeliver);
            $this->Session->set('Custom', $arrCustom);

            if($arrDeliver['alternate']) 
            {
                $errorDeliver = $this->checkfields($arrDeliver, unserialize($this->acquistoShop_selDeliver));                
            }
            
            if($arrCustom) 
            {
                $errorCustom = $this->checkAddonFields($arrCustom, unserialize($this->acquistoShop_fieldsAddon));
            }
    
            $errorBasket = $this->checkfields($arrCustomer, unserialize($this->acquistoShop_selFields));
                                                  
            if(!$errorDeliver && !$errorBasket && !$errorCustom) 
            {
                $this->redirect($this->getBasketUrl()  . "?do=payment-and-shipping");
            }
        }
    
    
        if (FE_USER_LOGGED_IN) 
        {
            $this->import('FrontendUser', 'User');
            $objMember = $this->Database->prepare("SELECT * FROM tl_member WHERE id=?")->limit(1)->execute($this->User->id);
            $arrMember = $objMember->row();
        } 
        else 
        {
            $arrMember = $this->Session->get('Customer');
        }                                               
        
        $this->Template = new \FrontendTemplate('mod_warenkorb_customer');
        $this->Template->Member           = $arrMember;
        $this->Template->Fields           = $this->buildfields(unserialize($this->acquistoShop_selFields), $errorBasket);
        $this->Template->addonFields      = $this->buildAddonFields(unserialize($this->acquistoShop_fieldsAddon), $this->Session->get('Custom'));
        $this->Template->deliverFields    = $this->buildfields(unserialize($this->acquistoShop_selDeliver), $errorDeliver);
        $this->Template->alternateAddress = $arrDeliver['alternate'];
        $this->Template->Customer         = $this->Session->get('Customer');    
        $this->Template->Deliver          = $this->Session->get('Deliver');    
    }
    
    private function basketPaymentAndShipping() 
    {
        $arrWidgetData = $this->Basket->itemNum();
  
        if($this->Input->Post('paymentMethod') OR $this->Input->Post('shippingMethod')) 
        {
            $this->basketData['paymentMethod']  = $this->Input->Post('paymentMethod');
            $this->basketData['shippingMethod'] = $this->Input->Post('shippingMethod');
  
            $this->Session->set('basket_data', $this->basketData);
  
            if($this->Input->Post('action')) 
            {
                $this->redirect($this->getBasketUrl()  . "?do=agb");
            }
        }
  
  
        $objVersandzonen = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen;")->execute();
  
        while($objVersandzonen->next())
        {
            $arrItem = $objVersandzonen->row();
            if(!$this->basketData['shippingMethod']) 
            {
                $this->basketData['shippingMethod'] = $arrItem['id'];
            }
  
            $arrVersandzonen[] = $arrItem;
        }
        
        $floatBerechnung = \AcquistoShop\acquistoShopCosts::costsCalculator($this->floatBerechnung, \AcquistoShop\acquistoShopCosts::getSelectedCurrency(), \AcquistoShop\acquistoShopCosts::getDefaultCurrency());
        $objVersandarten = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen_varten WHERE pid = ? && ab_einkaufpreis < ? ORDER BY ab_einkaufpreis ASC;")->execute($this->basketData['shippingMethod'], $floatBerechnung);
        
  
        while($objVersandarten->next()) 
        {
            if($objVersandarten->ab_einkaufpreis <= $floatBerechnung) 
            {
                $objZahlungsarten = $this->Database->prepare("SELECT * FROM tl_shop_zahlungsarten WHERE id = ?;")->execute($objVersandarten->zahlungsart_id);
                while($objZahlungsarten->next())
                {
                    $arrZahlungsart = $objZahlungsarten->row();
                    $arrZahlungsart['preis']                 = \AcquistoShop\acquistoShopCosts::costsCalculator($objVersandarten->preis, \AcquistoShop\acquistoShopCosts::getDefaultCurrency(), \AcquistoShop\acquistoShopCosts::getSelectedCurrency());
                    $arrZahlungsart['ab_einkaufpreis']       = \AcquistoShop\acquistoShopCosts::costsCalculator($objVersandarten->ab_einkaufpreis, \AcquistoShop\acquistoShopCosts::getDefaultCurrency(), \AcquistoShop\acquistoShopCosts::getSelectedCurrency());                     
                    $arrZahlungsart['sendID']                = $objVersandarten->id;
                    $arrZahlungsarten[$objZahlungsarten->id] = $arrZahlungsart;
                }
            }
        }
  
        $this->Template = new \FrontendTemplate('mod_warenkorb_payment_and_shipping');
        $this->Template->Zahlungsarten = $arrZahlungsarten;
        $this->Template->Versandzonen  = $arrVersandzonen;
  
        $this->Template->sel_pm = $this->basketData['paymentMethod'];
        $this->Template->sel_sm = $this->basketData['shippingMethod'];    
    }
    
    private function basketCheckAndOrder() 
    {
        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->replaceInsertTags($this->Shop->getInsertTagPID()));
        $objWidget  = (object) $this->Basket->itemNum();
        $arrSteuern = $this->Basket->getTaxes();
        
        $objVersandart    = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen_varten WHERE id = ?;")->execute($this->basketData['paymentMethod']);
        $objVersandzone   = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen WHERE id = ?;")->execute($objVersandart->pid);
        $objZahlungsarten = $this->Database->prepare("SELECT * FROM tl_shop_zahlungsarten WHERE id = ?;")->execute($objVersandart->zahlungsart_id);
    
        $this->Template                = new \FrontendTemplate('mod_warenkorb_check_and_order');   
        $this->Template->Steuern       = $arrSteuern;
        $this->Template->Produktliste  = $this->Basket->loadProducts($strUrl);  
        $this->Template->Zahlungsart   = (object) $objZahlungsarten->row();
        $this->Template->Versandart    = (object) $objVersandart->row();
        $this->Template->Versandzone   = (object) $objVersandzone->row();
        $this->Template->Customer      = (object) $this->Session->get('Customer');
        $this->Template->Deliver       = (object) $this->Session->get('Deliver');
        $this->Template->Custom        = (object) $this->buildAddonFields(unserialize($this->acquistoShop_fieldsAddon), $this->Session->get('Custom'));    
        $this->Template->Versandpreis  = \AcquistoShop\acquistoShopCosts::costsCalculator($objVersandart->preis, \AcquistoShop\acquistoShopCosts::getDefaultCurrency(), \AcquistoShop\acquistoShopCosts::getSelectedCurrency());
    
        if($objVersandzone->calculate_tax && $this->Basket->cardType == 'brutto') 
        {
            $this->Template->Gesamtpreis   = $this->Basket->getCosts();
            $this->Template->Endpreis      = ($this->Basket->getCosts()) + \AcquistoShop\acquistoShopCosts::costsCalculator($objVersandart->preis, \AcquistoShop\acquistoShopCosts::getDefaultCurrency(), \AcquistoShop\acquistoShopCosts::getSelectedCurrency());
        } 
        elseif($this->Basket->cardType == 'netto') 
        {
            if($objVersandzone->calculate_tax)
            {
                $this->Template->Gesamtpreis   = $this->Basket->getCosts() + $this->Basket->getTaxesSummary();
                $this->Template->Endpreis      = ($this->Basket->getCosts() + $this->Basket->getTaxesSummary()) + \AcquistoShop\acquistoShopCosts::costsCalculator($objVersandart->preis, \AcquistoShop\acquistoShopCosts::getDefaultCurrency(), \AcquistoShop\acquistoShopCosts::getSelectedCurrency());            
            }
            else
            {
                $this->Template->Gesamtpreis   = $this->Basket->getCosts();
                $this->Template->Endpreis      = ($this->Basket->getCosts()) + \AcquistoShop\acquistoShopCosts::costsCalculator($objVersandart->preis, \AcquistoShop\acquistoShopCosts::getDefaultCurrency(), \AcquistoShop\acquistoShopCosts::getSelectedCurrency());
            }
        }
        elseif($this->Basket->cardType == 'brutto')
        {
            if($objVersandzone->calculate_tax)
            {
                $this->Template->Gesamtpreis   = $this->Basket->getCosts() + $this->Basket->getTaxesSummary();
                $this->Template->Endpreis      = ($this->Basket->getCosts() + $this->Basket->getTaxesSummary()) + \AcquistoShop\acquistoShopCosts::costsCalculator($objVersandart->preis, \AcquistoShop\acquistoShopCosts::getDefaultCurrency(), \AcquistoShop\acquistoShopCosts::getSelectedCurrency());            
            }
            else
            {
                $this->Template->Gesamtpreis   = $this->Basket->getCosts() - $this->Basket->getTaxesSummary();
                $this->Template->Endpreis      = ($this->Basket->getCosts() - $this->Basket->getTaxesSummary()) + \AcquistoShop\acquistoShopCosts::costsCalculator($objVersandart->preis, \AcquistoShop\acquistoShopCosts::getDefaultCurrency(), \AcquistoShop\acquistoShopCosts::getSelectedCurrency());
            }        
        }
    
        $this->Template->paymentModule = sprintf($this->Payment->frontendTemplate($objZahlungsarten->payment_module, $objZahlungsarten->id), $this->generateFrontendUrl($objPage->fetchAssoc()) . "?do=pay", ($this->Basket->getCosts() + $objVersandart->preis));  
    }
    
    private function basketPay() 
    {
        $objVersandart  = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen_varten WHERE id = ?;")->execute($this->basketData['paymentMethod']);
        $objZahlungsart = $this->Database->prepare("SELECT * FROM tl_shop_zahlungsarten WHERE id = ?;")->execute($objVersandart->zahlungsart_id);
        $boolReturn     = $this->Payment->pay($objZahlungsart->payment_module, $objZahlungsart->id);
        
        $payState = 'N';
        if($boolReturn) {
            $payState = 'Y';                
        }
        
        if($boolReturn) 
        {
            $orderId = $this->Basket->writeOrder($this->Member->id, $this->basketData['paymentMethod'], $this->buildAddonFields(unserialize($this->acquistoShop_fieldsAddon), $this->Session->get('Custom')));
            $orderObject = $this->Orders->getComplettOrder($orderId);
            
            $arrCardMail = array(
                'templateVars' => array(
                    'Coupons'    => $orderObject->gutscheine,
                    'Shopping'   => $orderObject->items,
                    'Customer'   => $orderObject->customerData,
                    'Deliver'    => $orderObject->deliverAddress,
                    'Addon'      => $orderObject->customFields,
                    'Total'      => $this->Orders->getSummary($orderId),
                    'Taxes'      => $this->Orders->getTaxes($orderId),
                    'Settings'   => $GLOBALS['TL_CONFIG'],
                    'Shipping'   => $orderObject->shipping,
                    'Payment'    => $orderObject->payment,
                ),
                'emailTemplate' => $this->acquistoShop_emailTemplate,
                'emailFrom'     => array(
                    'fromMail'  => $GLOBALS['TL_CONFIG']['bestell_email'],
                    'fromName'  => $GLOBALS['TL_CONFIG']['firmenname'],
                ),
                'sendTo'        => array(
                    'to'        => array(),
                    'cc'        => array(),
                    'bcc'       => array($GLOBALS['TL_CONFIG']['bestell_email'])
                ), 
                'emailSubject'  => null,
                'emailTyp'      => $this->acquistoShop_emailTyp,
                'agbFile'       => $this->acquistoShop_AGBFile,
                'attachments'   => array()                                                                                   
            );
                
            $arrCardMail['sendTo']['to'] = array($orderObject->customerData->email);
            $arrCardMail['emailSubject'] = sprintf($this->acquistoShop_emailSubject_Buyer, $this->Shop->generateOrderID($orderID));
            $this->Basket->sendOrderMail($this->acquistoShop_emailTemplate, $orderObject->customerData->email, $arrCardMail);
            
            $arrCardMail['sendTo']['to'] = array($GLOBALS['TL_CONFIG']['bestell_email']);
            $arrCardMail['emailSubject'] = sprintf($this->acquistoShop_emailSubject_Seller, $this->Shop->generateOrderID($orderID));
            $this->Basket->sendOrderMail($this->acquistoShop_emailTemplate_seller, $GLOBALS['TL_CONFIG']['bestell_email'], $arrCardMail);
            
            $this->Template = new \FrontendTemplate('mod_warenkorb_pay');
        }    
    }
    
    private function basketTermsAndConditions() 
    {
        if($this->Input->Post('agb'))
        {
            $this->basketData['agb'] = 1;
            $this->Session->set('basket_data', $this->basketData);
            $this->redirect($this->getBasketUrl()  . "?do=check-and-order");
        }
    
        $this->Template = new \FrontendTemplate('mod_warenkorb_agb');
        $this->Template->AGB = $GLOBALS['TL_CONFIG']['agb'];
        $this->Template->Widerruf = $GLOBALS['TL_CONFIG']['widerruf'];
        $this->Template->sel_agb = $this->basketData['agb'];    
    }
}

?>