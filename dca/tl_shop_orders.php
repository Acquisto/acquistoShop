<?php 

/**
 * The AcquistoShop extension allows the creation a OnlineStore width 
 * the OpenSource Contao CMS. For Question visit our Website under
 * http://www.contao-acquisto.de  
 *
 * PHP version 5
 * @package	   AcquistoShop
 * @subpackage Backend
 * @author     Sascha Brandhoff <brandhoff@contao-acquisto.de>
 * @copyright  AcquistoShop
 * @license    LGPL.
 * @filesource
 */

/**
 * Table tl_shop_orders
 */
$GLOBALS['TL_DCA']['tl_shop_orders'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ctable'                      => array('tl_shop_orders_items'),
        'enableVersioning'            => true,
        'switchToEdit'                => true,
        'closed'                      => true,
    		'sql' => array
    		(
    			'keys' => array
    			(
    				'id' => 'primary'
    			)
    		),
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'fields'                  => array('tstamp'),
            'flag'                    => 6,
            'panelLayout'             => 'search,limit',
            'label_callback'          => array('tl_shop_orders', 'listItems')
        ),
        'label' => array
        (
            'fields'                  => array('tstamp'),
            'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[]</span>',
            'group_callback'          => array('tl_shop_orders', 'formatGroup'),
            'label_callback'          => array('tl_shop_orders', 'formatLabel'),
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset();"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_orders']['edit'],
                'href'                => 'table=tl_shop_orders_items',
                'icon'                => 'edit.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_orders']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_orders']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            ),            
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{title_legend},order_id,versandzonen_id,zahlungsart_id,payed,calculate_tax;{customer},customerData;{deliver},deliverAddress;{custom_legend},customData;',
    ),


    // Fields
    'fields' => array
    (
        'id' => array
    		(
    			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
    		),
    		'tstamp' => array
    		(
    			'sql'                     => "int(10) unsigned NOT NULL default '0'"
    		),
    		'member_id' => array
    		(
    			'sql'                     => "int(10) NOT NULL default '0'"
    		),
    		'versandart_id' => array
    		(
    			'sql'                     => "int(10) NOT NULL default '0'"
    		),
        'order_id' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_orders']['order_id'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64),
      			'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'versandzonen_id' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_orders']['versandzonen_id'],
            'inputType'               => 'select',
            'search'                  => false,
            'foreignKey'              => 'tl_shop_versandzonen.bezeichnung',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
      			'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'zahlungsart_id' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_orders']['zahlungsart_id'],
            'inputType'               => 'select',
            'search'                  => false,
            'foreignKey'              => 'tl_shop_zahlungsarten.bezeichnung',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
      			'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'payed' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_orders']['payed'],
            'inputType'               => 'checkbox',
            'search'                  => true,
            'eval'                    => array('mandatory'=>false,'tl_class' => 'w50', 'isBoolean'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'calculate_tax' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_orders']['calculate_tax'],
            'inputType'               => 'checkbox',
            'search'                  => true,
            'eval'                    => array('mandatory'=>false,'tl_class' => 'w50', 'isBoolean'=>true),
      			'sql'                     => "char(1) NOT NULL default ''"
        ),
        'customerData' => array
        (
            'label'                   => '',
            'input_field_callback'    => array('tl_shop_orders', 'callback_customerData'),
            'sql'                     => "longtext NOT NULL"
        ),
        'deliverAddress' => array
        (
            'label'                   => '',
            'input_field_callback'    => array('tl_shop_orders', 'callback_deliverAddress'),
            'sql'                     => "longtext NOT NULL"
        ),
        'customData' => array
        (
            'label'                   => '',
            'input_field_callback'    => array('tl_shop_orders', 'callback_customData'),
            'sql'                     => "longtext NOT NULL"
        ),  
        'customFields' => array
        (
            'sql'                     => "longtext NOT NULL"
        ),                        
    		'gutscheine' => array
    		(
    			'sql'                       => "longtext NOT NULL"
    		),
    		'versandpreis' => array
    		(
    			'sql'                       => "float NOT NULL default '0'"
    		),
    		'currency_default' => array
    		(
            'sql'                     => "text NOT NULL"
    		),
    		'currency_selected' => array
    		(
            'sql'                     => "text NOT NULL"
    		)
    )
);

class tl_shop_orders extends Backend {
    protected $defaultFields = array(
        'firstname' => '',
        'lastname'  => '',
        'company'   => '',
        'street'    => '',
        'postal'    => '',
        'city'      => '',
        'email'     => '',
        'phone'     => '',
        'fax'       => ''
    );
    
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
        $this->import('acquistoShop', 'Shop');
        $this->import('acquistoShopProduktLoader', 'Produkt');
    }

    public function formatGroup($arrRow) {
        return date("d.m.Y", strtotime($arrRow));
    }

    public function formatLabel($arrRow) {
        $this->loadLanguageFile('tl_member');
        $this->Import('AcquistoShop\acquistoShopOrders', 'Order');
        
        $objOrder = $this->Order->getComplettOrder($arrRow['id']);
        $objSummary = $this->Order->getSummary($arrRow['id']);
        $objTaxes = $this->Order->getTaxes($arrRow['id']);
                
        $html  = null;
        $html .= '<div class="limit_height' . (!$GLOBALS['TL_CONFIG']['doNotCollapse'] ? ' h64' : '') . ' block">';
        $html .= '<h3>Bestellung ' .         $this->Shop->generateOrderID($objOrder->order_id) . ' um ' . date("H:i", $arrRow['tstamp']) . '</h3>';
        
//         if(!is_array($arrCustomer = unserialize($objBestellung->customerData))) {
//             $arrCustomer = $this->oldCustomerData($objBestellung->id);
//         }

        if(!is_array($arrCustomer)) {
            $arrCustomer = $this->defaultFields;
        }
        
        if($objOrder->customerData) 
        {
            foreach((array) $objOrder->customerData as $key => $value) 
            {
                if($value) 
                {
                    $htmlCustomer .= '<span class="label">' . $GLOBALS['TL_LANG']['tl_member'][$key][0] . ':</span> ' . $value . '<br>';
                }
            }        
        }
        
        if(!is_array($arrDeliver)) {
            $arrCustomer = $this->defaultFields;
        }
            
        if($objOrder->deliverAddress) 
        {
            foreach((array) $objOrder->deliverAddress as $key => $value) 
            {
                if($value && $key != 'alternate') 
                {
                    $htmlDeliver .=  '<span class="label">' . $GLOBALS['TL_LANG']['tl_member'][$key][0] . ':</span> ' . $value . '<br>';
                }
            }        
        }

        if($htmlCustomer OR $htmlDeliver) {
            $html .= '<table cellpadding="0" cellspacing="0" width="100%">';
            $html .= '    <tr>';
            
            if($htmlCustomer) {
                $html .= '      <td width="50%" valign="top"><b>Rechnungsadresse</b><br>';
                $html .= $htmlCustomer; 
                $html .= '      </td>';
            }
            
            if($htmlDeliver) {
                $html .= '      <td width="50%" valign="top"><b>Versandadresse</b><br>';
                $html .= $htmlDeliver; 
                $html .= '      </td>';
            }
    
            $html .= '    </tr>';
            $html .= '</table>';
        }

        $html .= '<table cellpadding="0" cellspacing="0" width="100%">';
        $html .= '    <tr>';
        $html .= '        <td colspan="4"><hr></td>';
        $html .= '    </tr>';
        $html .= '    <tr>';
        $html .= '        <td><b>Bezeichnung</b></td>';
        $html .= '        <td align="right"><b>Menge</b></td>';
        $html .= '        <td align="right"><b>EP</b></td>';
        $html .= '        <td align="right"><b>Summe</b></td>';
        $html .= '    </tr>';

        foreach($objOrder->items as $Item) {
            $Item->attribute = unserialize($Item->attribute);
            $objProdukt  = $this->Produkt->load($Item->produkt_id, $Item->attribute);
            
            $html .= '    <tr>';
            $html .= '        <td>' . $Item->bezeichnung . '</td>';
            $html .= '        <td align="right">' . $Item->menge . '</td>';
            $html .= '        <td align="right">' . sprintf("%01.2f", $Item->preis) . ' ' . $objOrder->currency_default->iso_code . '</td>';
            $html .= '        <td align="right">' . sprintf("%01.2f", ($Item->menge * $Item->preis)) . ' ' . $objOrder->currency_default->iso_code . '</td>';
            $html .= '    </tr>';
            
            if(is_array($objProdukt->attribute_list)) {
                $html .= '    <tr>';
                $html .= '        <td colspan="4">';
                foreach($objProdukt->attribute_list as $item) {
                    $html .= '- ' . $item->title . ': ' . $item->selection . '<br>';                    
                }                            

                $html .= '        </td>';
                $html .= '    </tr>';
            } 
        }

        $html .= '    <tr>';
        $html .= '        <td colspan="4"><hr></td>';
        $html .= '    </tr>';
        $html .= '    <tr>';
        $html .= '        <td align="right" colspan="3">Zwischensumme:</td>';
        $html .= '        <td align="right">' . sprintf("%01.2f", $objSummary->subtotal) . ' ' . $objOrder->currency_default->iso_code . '</td>';
        $html .= '    </tr>';

        if($objOrder->shipping->calculate_tax) 
        {
            if(is_array($objTaxes)) 
            {
                foreach($objTaxes as $Satz => $Steuer) 
                {
                    $html .= '    <tr>';
                    $html .= '        <td align="right" colspan="3">zzgl. MwSt. ' . $Satz . '% auf ' . sprintf("%01.2f", $Steuer->total) .  '' . $objOrder->currency_default->iso_code . ':</td>';
                    $html .= '        <td align="right">' . sprintf("%01.2f", $Steuer->tax) . ' ' . $objOrder->currency_default->iso_code . '</td>';
                    $html .= '    </tr>';
                }    
            }
        }
        else
        {
            $html .= '    <tr>';
            $html .= '        <td align="right" colspan="3">Es werden keine Steuern in diese Versandzone berechnet.</td>';
            $html .= '        <td align="right">' . $abzgl . sprintf("%01.2f", 0) . ' ' . $objOrder->currency_default->iso_code . '</td>';
            $html .= '    </tr>';        
        }

        $html .= '    <tr>';
        $html .= '        <td align="right" colspan="3">zzgl. Versandkosten (' . $objOrder->shipping->bezeichnung . ' / ' . $objOrder->payment->bezeichnung . '):</td>';
        $html .= '        <td align="right">' . sprintf("%01.2f", $objSummary->shipping) . ' ' . $objOrder->currency_default->iso_code . '</td>';
        $html .= '    </tr>';
        $html .= '    <tr>';
        $html .= '        <td align="right" colspan="3">Endpreis:</td>';
        $html .= '        <td align="right">' . sprintf("%01.2f", $objSummary->total) . ' ' . $objOrder->currency_default->iso_code . '</td>';
        $html .= '    </tr>';
        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }
    
    public function callback_customerData(DataContainer $DC) {
        $this->loadDataContainer('tl_member');
        $this->loadLanguageFile('tl_member');

        if($this->Input->post('FORM_SUBMIT') == 'tl_shop_orders') {
            $this->Database->prepare("UPDATE tl_shop_orders SET customerData = '" . serialize($this->Input->Post('customerData')) . "' WHERE id = ?")->execute($DC->id);
        }

        $objOrder = $this->Database->prepare("SELECT * FROM tl_shop_orders WHERE id = ?")->limit(1)->execute($DC->id);
        
        if(!is_array($arrConfig = unserialize($objOrder->customerData))) {
#            $arrConfig = $this->oldCustomerData($DC->id);
        }
                
        if(!is_array($arrConfig)) {
            $arrConfig = $this->defaultFields;
        }

        if(is_array($arrConfig)) {
            foreach($arrConfig as $key => $value) {
                $objElement = $GLOBALS['BE_FFL'][$GLOBALS['TL_DCA']['tl_member']['fields'][$key]['inputType']];
                $objElement = new $objElement($this->prepareForWidget($GLOBALS['TL_DCA']['tl_member']['fields'][$key], 'customerData[' . $key . ']'));
                $objElement->value = $value;
                $htmlData .= '<div class="w50">';
                $htmlData .= sprintf('<h3><label for="%s">%s</label></h3>', 'customerData[' . $key . ']', $GLOBALS['TL_LANG']['tl_member'][$key][0]);
                $htmlData .= $objElement->generate();
                $htmlData .= sprintf('<p class="tl_help">%s</p>', $GLOBALS['TL_LANG']['tl_member'][$key][1]);
                $htmlData .= '</div>';
            } 
        } else {
            $htmlData = '<h3>Keine Konfigurationparameter</h3><p class="tl_help">Entweder wurd keine Rechnungsadresse ausgew&auml;hlt oder es gibt keine Konfigurationwerte.</p>';
        }
                
        return $htmlData;
    }
    
    public function callback_deliverAddress(DataContainer $DC) {
        $this->loadDataContainer('tl_member');
        $this->loadLanguageFile('tl_member');

        if($this->Input->post('FORM_SUBMIT') == 'tl_shop_orders') {
            $this->Database->prepare("UPDATE tl_shop_orders SET deliverAddress = '" . serialize($this->Input->Post('deliverAddress')) . "' WHERE id = ?")->execute($DC->id);
        }

        $objOrder = $this->Database->prepare("SELECT * FROM tl_shop_orders WHERE id = ?")->limit(1)->execute($DC->id);
        
        if(!is_array($arrConfig)) {
            $arrConfig = $this->defaultFields;
        }

        if(is_array($arrConfig)) {
            foreach($arrConfig as $key => $value) {
                if($key != 'alternate') {
                    $objElement = $GLOBALS['BE_FFL'][$GLOBALS['TL_DCA']['tl_member']['fields'][$key]['inputType']];
                    $objElement = new $objElement($this->prepareForWidget($GLOBALS['TL_DCA']['tl_member']['fields'][$key], 'deliverAddress[' . $key . ']'));
                    $objElement->value = $value;
                    $htmlData .= '<div class="w50">';
                    $htmlData .= sprintf('<h3><label for="%s">%s</label></h3>', 'deliverAddress[' . $key . ']', $GLOBALS['TL_LANG']['tl_member'][$key][0]);
                    $htmlData .= $objElement->generate();
                    $htmlData .= sprintf('<p class="tl_help">%s</p>', $GLOBALS['TL_LANG']['tl_member'][$key][1]);
                    $htmlData .= '</div>';
                }
            } 
        } else {
            $htmlData = '<h3>Keine Konfigurationparameter</h3><p class="tl_help">Entweder wurde keine Versandadresse ausgew&auml;hlt oder es gibt keine Konfigurationwerte.</p>';
        }
                
        return $htmlData;
    }
    
    public function callback_customData(DataContainer $DC) {
        $this->loadDataContainer('tl_member');
        $this->loadLanguageFile('tl_member');

        if($this->Input->post('FORM_SUBMIT') == 'tl_shop_orders') {
            $this->Database->prepare("UPDATE tl_shop_orders SET customData = '" . serialize($this->Input->Post('customData')) . "' WHERE id = ?")->execute($DC->id);
        }

        $objOrder = $this->Database->prepare("SELECT * FROM tl_shop_orders WHERE id = ?")->limit(1)->execute($DC->id);

        $dataValues = unserialize($objOrder->customData);
        $arrConfig  = unserialize($objOrder->customFields);
                        
        if(is_array($arrConfig)) {
            foreach($arrConfig as $key => $value) {
                if($value->fieldtype) {
                    $objElement = $GLOBALS['BE_FFL'][$value->fieldtype];
                    $fieldData = $this->fieldData($value);
                    $objElement = new $objElement($this->prepareForWidget($fieldData, 'customData[' . $value->fieldname . ']'));                    
                    $objElement->value = $dataValues[$value->fieldname];
                                                                
                    $htmlData .= '<div class="' . $fieldData['eval']['tl_class'] . '">';
                    $htmlData .= sprintf('<h3><label for="%s">%s</label></h3>', 'customData[' . $value->fieldname . ']', $value->fieldtitle);
                    $htmlData .= $objElement->generate();
                    $htmlData .= sprintf('<p class="tl_help">%s</p>', $GLOBALS['TL_LANG']['tl_member'][$key][1]);
                    $htmlData .= '</div>';
                } else {
                    $htmlData = '<h3>Keine Konfigurationparameter</h3><p class="tl_help">Entweder wurd keine Zusatzfelder ausgew&auml;hlt oder es gibt keine Konfigurationwerte.</p>';                
                }
            } 
        } else {
            $htmlData = '<h3>Keine Konfigurationparameter</h3><p class="tl_help">Entweder wurd keine Zusatzfelder ausgew&auml;hlt oder es gibt keine Konfigurationwerte.</p>';
        }
                
        return $htmlData;    
    }
    
    private function buildDataArray($data) {
        $explode = explode(",", $data);
        foreach($explode as $explodeValue) {
            if(substr_count($explodeValue, "&#61;") OR substr_count($explodeValue, "=")) {
                if(substr_count($explodeValue, "&#61;")) {
                    $explodeSign = "&#61;";
                } else {
                    $explodeSign = "=";
                }
                
                $explodeValue = explode($explodeSign, $explodeValue);
                $explodeNew[$explodeValue[0]] = $explodeValue[1];                    
            } else {
                $explodeNew[$explodeValue] = $explodeValue;                                
            }
        }
        
        return $explodeNew;
    }
    
    private function fieldData($buildData) {
        switch($buildData->fieldtype) {
            case "select":
                $field = array(
                    'label'                   => array($buildData->fieldtitle, ''),
                    'inputType'               => 'select',
                    'search'                  => false,
                    'options'                 => $this->buildDataArray($buildData->value), 
                    'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50'),
                );            
                break;;
            case "checkbox":
                $field = array(
                    'label'                   => array($buildData->fieldtitle, ''),
                    'inputType'               => 'checkbox',
                    'search'                  => false,
                    'options'                 => $this->buildDataArray($buildData->value), 
                    'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'multiple' => true, 'tl_class'=>'clr'),
                );            
                break;;
            case "radio":
                $field = array(
                    'label'                   => array($buildData->fieldtitle, ''),
                    'inputType'               => 'select',
                    'search'                  => false,
                    'options'                 => $this->buildDataArray($buildData->value), 
                    'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'clr'),
                );            
                break;;
            case "text":
                $field = array(
                    'label'                   => array($buildData->fieldtitle, ''),
                    'inputType'               => 'text',
                    'search'                  => false,
                    'options'                 => array(), 
                    'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50'),
                );            
                break;;                
            case "textarea":
                $field = array(
                    'label'                   => array($buildData->fieldtitle, ''),
                    'inputType'               => 'text',
                    'search'                  => false,
                    'options'                 => array(), 
                    'eval'                    => array('mandatory'=>false, 'tl_class'=>'clr'),
                );            
                break;;                
        }
        
        
        return $field;    
    }
}

?>