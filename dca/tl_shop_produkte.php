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

$this->loadLanguageFile('tl_acquisto_pclasses');

/**
 * Table tl_shop_produkte
 */
$GLOBALS['TL_DCA']['tl_shop_produkte'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ctable'                      => array('tl_shop_produkte_attribute'),
        'enableVersioning'            => true,
        'onsubmit_callback'           => array
        (
            array('tl_shop_produkte', 'onsubmit_callback')
        ),
    		'sql' => array
    		(
    			'keys' => array
    			(
    				'id'    => 'primary',
            'pid'   => 'index',
            'alias' => 'index'
    			)
    		),
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'fields'                  => array('bezeichnung'),
            'flag'                    => 1,
            'panelLayout'             => 'filter;search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('bezeichnung', 'produktnummer', 'type'),
            'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s / %s]</span>'
,           'label_callback'          => array('tl_shop_produkte', 'listProdukte')
        ),
        'global_operations' => array
        (
//             'importData' => array
//             (
//                 'label'               => &$GLOBALS['TL_LANG']['tl_shop_produkte']['importData'],
//                 'href'                => 'key=importData',
//                 'class'               => 'header_edit_all',
//                 'attributes'          => 'onclick="Backend.getScrollOffset();"'
//             ),
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
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_produkte']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif',
                'button_callback'     => array('tl_shop_produkte', 'changeButton')
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_produkte']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_produkte']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_page']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
//				'button_callback'     => array('tl_page', 'toggleIcon')
			),            
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_produkte']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => array('type'),
        'default'                     => '{title},type,produktnummer,bezeichnung,alias,ean,template;{extended_options},steuer,zustand,hersteller,gewicht,tags,teaser,beschreibung;{grundpreis},mengeneinheit,inhalt,berechnungsmenge;{staffelpreise},preise;{groups:hide},warengruppen;{images:hide},preview_image,galerie;{catalog_legend},catalogMode,catalogMoney;{state},marked,aktiv,startDate,endDate;',
        'normal'                      => '{title},type,produktnummer,bezeichnung,alias,ean,template;{extended_options},steuer,zustand,hersteller,gewicht,tags,teaser,beschreibung;{grundpreis},mengeneinheit,inhalt,berechnungsmenge;{staffelpreise},preise;{groups:hide},warengruppen;{images:hide},preview_image,galerie;{catalog_legend},catalogMode,catalogMoney;{state},marked,aktiv,startDate,endDate;',
        'digital'                     => '{title},type,produktnummer,bezeichnung,alias,ean,template;{extended_options},steuer,zustand,hersteller,tags,teaser,beschreibung;{staffelpreise},preise;{groups:hide},warengruppen;{images:hide},preview_image,galerie;{digital},digital_product;{catalog_legend},catalogMode,catalogMoney;{state},marked,aktiv,startDate,endDate;',
        'variant'                     => '{title},type,produktnummer,bezeichnung,alias,ean,template;{extended_options},steuer,zustand,hersteller,tags,teaser,beschreibung;{staffelpreise},preise;{groups:hide},warengruppen;{images:hide},preview_image,galerie;{catalog_legend},catalogMode,catalogMoney;{state},marked,aktiv,startDate,endDate;'
    ),


    // Fields
    'fields' => array
    (
        'id' => array
    		(
    			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
    		),
        'pid' => array
    		(
    			'sql'                     => "int(10) NOT NULL default '0'"
    		),
    		'tstamp' => array
    		(
    			'sql'                     => "int(10) unsigned NOT NULL default '0'"
    		),
        'type' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['type'],
            'inputType'               => 'select',
            'exclude'                 => true,
            'search'                  => false,
            'filter'                  => true,
            'options_callback'        => array('tl_shop_produkte', 'listPClasses'),
            'eval'                    => array('mandatory'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50', 'maxlength'=>64),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'bezeichnung' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['bezeichnung'],
            'inputType'               => 'text',
            'exclude'                 => true,
            'search'                  => true,
            'filter'                  => false,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50', 'acquistoSearch'=>true),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'alias' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['alias'],
            'exclude'                 => true,
            'search'                  => false,
            'filter'                  => false,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'alnum', 'doNotCopy'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
      			'sql'                     => "varchar(128) NOT NULL default ''",
            'save_callback' => array
            (
                array('tl_shop_produkte', 'generateAlias')
            )
        ),
        'template' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['template'],
            'exclude'                 => true,
            'search'                  => false,
            'filter'                  => false,
            'inputType'               => 'select',
            'options_callback'        => array('tl_shop_produkte', 'getTemplates'),
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50', 'includeBlankOption'=>true),
      			'sql'                     => "varchar(255) NOT NULL default ''",
        ),
        'ean' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['ean'],
            'inputType'               => 'text',
            'exclude'                 => true,
            'search'                  => true,
            'filter'                  => false,
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50', 'acquistoSearch'=>true),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'produktnummer' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['produktnummer'],
            'inputType'               => 'text',
            'exclude'                 => true,
            'search'                  => true,
            'filter'                  => false,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50', 'acquistoSearch'=>true),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'gewicht' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['gewicht'],
            'inputType'               => 'text',
            'default'                 => 0,
            'exclude'                 => true,
            'search'                  => true,
            'filter'                  => false,
            'eval'                    => array('mandatory'=>false, 'rgxp'=>'digit', 'tl_class'=>'w50'),
      			'sql'                     => "float NOT NULL default '0'"
        ),
        'zustand' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['zustand'],
            'inputType'               => 'select',
            'options'                 => array('new' => 'Neu', 'used'=>'Gebraucht', 'refurbished' => 'Erneuert'),
            'exclude'                 => true,
            'search'                  => false,
            'filter'                  => true,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50', 'includeBlankOption'=>true),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'steuer' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['steuer'],
            'default'                 => 0,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_shop_steuer.bezeichnung',
            'exclude'                 => true,
            'search'                  => false,
            'filter'                  => true,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
      			'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'mengeneinheit' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['mengeneinheit'],
            'default'                 => 0,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_shop_mengeneinheit.bezeichnung',
            'exclude'                 => true,
            'search'                  => false,
            'filter'                  => false,
            'eval'                    => array('mandatory'=>false, 'includeBlankOption'=>true),
      			'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'inhalt' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['inhalt'],
            'inputType'               => 'text',
            'exclude'                 => true,
            'search'                  => true,
            'filter'                  => false,
            'eval'                    => array('mandatory'=>false, 'rgxp'=> 'digit ', 'maxlength'=>10, 'tl_class'=>'w50'),
      			'sql'                     => "char(10) NOT NULL default ''"
        ),
        'berechnungsmenge' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['berechnungsmenge'],
            'inputType'               => 'text',
            'exclude'                 => true,
            'search'                  => true,
            'filter'                  => false,
            'eval'                    => array('mandatory'=>false, 'rgxp'=> 'digit ', 'maxlength'=>10, 'tl_class'=>'w50'),
      			'sql'                     => "char(10) NOT NULL default ''"
        ),
        'hersteller' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['hersteller'],
            'inputType'               => 'select',
            'foreignKey'              => 'tl_shop_hersteller.bezeichnung',
            'exclude'                 => true,
            'search'                  => false,
            'filter'                  => true,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50', 'includeBlankOption'=>true),
      			'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'teaser' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['teaser'],
            'inputType'               => 'textarea',
            'exclude'                 => true,
            'search'                  => false,
            'eval'                    => array('style'=>'height: 60px;', 'mandatory'=>false, 'tl_class'=>'clr', 'acquistoSearch'=>true),
      			'sql'                     => "text NOT NULL"
        ),
        'tags' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['tags'],
            'inputType'               => 'text',
            'exclude'                 => true,
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'long  clr', 'acquistoSearch'=>true),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'beschreibung' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['beschreibung'],
            'inputType'               => 'textarea',
            'exclude'                 => true,
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'rte'=>'tinyMCE', 'tl_class'=>'clr', 'acquistoSearch'=>true),
      			'sql'                     => "text NOT NULL"
        ),
        'preise' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['preise'],
            'exclude'                 => true,
        	  'inputType'               => 'multiColumnWizard',
            'eval'                    =>array
          	(
            		'columnFields' => array
            		(
              			'value' => array
              			(
                				'label' 		            => &$GLOBALS['TL_LANG']['tl_shop_produkte']['preise_value'],
                        'default'               => '0',
                				'inputType' 		        => 'text',
                				'eval'                  => array('mandatory'=>true, 'style'=>'width:130px')
              			),
              			'label' => array
              			(
                				'label' 		            => &$GLOBALS['TL_LANG']['tl_shop_produkte']['preise_label'],
                        'default'               => '0',
                				'inputType' 		        => 'text',
                				'eval'                  => array('mandatory'=>true, 'style'=>'width:130px')
              			),
              			'specialcosts' => array
              			(
                				'label'                 => &$GLOBALS['TL_LANG']['tl_shop_produkte']['preise_specialcosts'],
                				'inputType'             => 'text',
                				'eval'                  => array('mandatory'=>false, 'style'=>'width:130px')
              			),
              			'pricelist' => array
              			(
                				'label'                 => &$GLOBALS['TL_LANG']['tl_shop_produkte']['preise_pricelist'],
                				'exclude'               => true,
                				'inputType'             => 'select',
                        'foreignKey'            => 'tl_shop_pricelists.title',
                				'eval' 		             	=> array('style'=>'width:160px', 'rgxp'=>'digit')
              			),
            		)
          	),
            'sql'                     => "text NOT NULL"
        ),
        'warengruppen' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['warengruppen'],
            'inputType'               => 'categorieTree',
            'exclude'                 => true,
            'search'                  => false,
            'eval'                    => array('mandatory'=>false,'fieldType'=>'checkbox', 'multiple'=>true),
      			'sql'                     => "text NOT NULL"
        ),
        'preview_image' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['preview_image'],
            'inputType'               => 'fileTree',
            'exclude'                 => true,
            'search'                  => false,
            'eval'                    => array('mandatory'=>false,'fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true,'extensions'=>'jpg,png,gif'),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'galerie' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['galerie'],
            'inputType'               => 'fileTree',
            'exclude'                 => true,
            'search'                  => false,
            'eval'                    => array('multiple'=>true, 'orderField'=>'orderSRC', 'mandatory'=>false,'fieldType'=>'checkbox', 'files'=>true, 'filesOnly'=>true,'extensions'=>'jpg,png,gif'),
      			'sql'                     => "blob NULL"
        ),
    		'orderSRC' => array
    		(
    			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['orderSRC'],
    			'sql'                     => "text NULL"
    		),        
        'digital_product' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['digital_product'],
            'inputType'               => 'fileTree',
            'exclude'                 => true,
            'search'                  => false,
            'eval'                    => array('mandatory'=>false,'fieldType'=>'checkbox', 'files'=>true,'filesOnly'=>true,'extensions'=>strtolower($GLOBALS['TL_CONFIG']['allowedDownload'])),
      			'sql'                     => "blob NOT NULL"
        ),
        'marked' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['marked'],
            'inputType'               => 'checkbox',
            'exclude'                 => true,
            'search'                  => false,
            'filter'                  => true,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'m12 w50'),
      			'sql'                     => "char(1) NOT NULL default ''"
        ),
        'aktiv' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['aktiv'],
            'inputType'               => 'checkbox',
            'exclude'                 => true,
            'search'                  => false,
            'filter'                  => true,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'m12 w50'),
      			'sql'                     => "char(1) NOT NULL default ''"
        ),
        'catalogMoney' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['catalogMoney'],
            'inputType'               => 'checkbox',
            'exclude'                 => true,
            'search'                  => false,
            'filter'                  => true,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'m12 w50'),
      			'sql'                     => "char(1) NOT NULL default ''"
        ),
        'catalogMode' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['catalogMode'],
            'inputType'               => 'checkbox',
            'exclude'                 => true,
            'search'                  => false,
            'filter'                  => true,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'m12 w50'),
      			'sql'                     => "char(1) NOT NULL default ''"
        ),
        'startDate' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['startDate'],
      			'default'                 => '',
      			'exclude'                 => true,
      			'inputType'               => 'text',
      			'eval'                    => array('rgxp'=>'date', 'mandatory'=>false, 'doNotCopy'=>true, 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
      			'sql'                     => "varchar(10) NOT NULL default ''"
      	),
        'endDate' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte']['endDate'],
      			'default'                 => '',
      			'exclude'                 => true,
      			'inputType'               => 'text',
      			'eval'                    => array('rgxp'=>'date', 'mandatory'=>false, 'doNotCopy'=>true, 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
      			'sql'                     => "varchar(10) NOT NULL default ''"
        ),
        'calculateTax' => array
        (
      			'sql'                     => "char(1) NOT NULL default ''"
        )
    )
);

/**
 * Class tl_style
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class tl_shop_produkte extends Backend
{

    /**
     * Add the mooRainbow scripts to the page
     */
    public function __construct()
    {
        parent::__construct();

        $GLOBALS['TL_CSS'][] = 'plugins/mootools/rainbow.css?'. MOO_RAINBOW . '|screen';
        $GLOBALS['TL_JAVASCRIPT'][] = 'plugins/mootools/rainbow.js?' . MOO_RAINBOW;

        $this->import('BackendUser', 'User');
    }
    
    public function onsubmit_callback($dc) 
    {
        $this->Database->prepare("UPDATE tl_shop_produkte SET calculateTax = '1' WHERE id=?")->execute($this->Input->Get('id'));
    }

    public function listProdukte($arrRow) 
    {
        return sprintf('%s <span style="color:#b3b3b3; padding-left:3px;">[%s / %s]</span>', $arrRow['bezeichnung'], $arrRow['produktnummer'], $GLOBALS['TL_LANG']['tl_acquisto_pclasses'][$arrRow['type']][0]);
    }

    public function changeButton($row, $href, $label, $title, $icon, $attributes)
    {
        if($GLOBALS['ACQUISTO_PCLASS'][$row['type']][0]) 
        {        
            $objProdukt = new $GLOBALS['ACQUISTO_PCLASS'][$row['type']][0]($row['id']);
    
            if($objProdukt->hasAttributes) 
            {
                return '<a title="edit" href="' . $this->addToUrl(str_replace("act=edit", "table=tl_shop_produkte_attribute", $href)) . "&id=" . $row['id'] . '"><img width="12" height="16" alt="' . $label . '" title="' . $title . '" src="system/themes/default/images/' . $icon . '"></a>&nbsp;';
            } 
            elseif($objProdukt->subTable) 
            {
                return '<a title="edit" href="' . $this->addToUrl(str_replace("act=edit", "table=" . $objProdukt->subTable, $href)) . "&id=" . $row['id'] . '"><img width="12" height="16" alt="' . $label . '" title="' . $title . '" src="system/themes/default/images/' . $icon . '"></a>&nbsp;';
            } 
            else 
            {
                return '<a title="edit" href="' . $this->addToUrl($href) . "&id=" . $row['id'] . '"><img width="12" height="16" alt="' . $label . '" title="' . $title . '" src="system/themes/default/images/' . $icon . '"></a>&nbsp;';
            }
        }
        else
        {
            return '<a title="edit" href="' . $this->addToUrl($href) . "&id=" . $row['id'] . '"><img width="12" height="16" alt="' . $label . '" title="' . $title . '" src="system/themes/default/images/' . $icon . '"></a>&nbsp;';        
        }
    }

    /**
     * Autogenerate a page alias if it has not been set yet
     * @param mixed
     * @param object
     * @return string
     */
    public function generateAlias($varValue, DataContainer $dc)
    {
        $autoAlias = false;

        // Generate alias if there is none
        if (!strlen($varValue))
        {
            $autoAlias = true;
            $varValue = standardize($dc->activeRecord->bezeichnung);
        }

        $objAlias = $this->Database->prepare("SELECT id FROM tl_shop_produkte WHERE id=? OR alias=?")
                                   ->execute($dc->id, $varValue);

        return $varValue;
    }
    
    public function listPClasses() {
        if(is_array($GLOBALS['ACQUISTO_PCLASS'])) {
            foreach($GLOBALS['ACQUISTO_PCLASS'] as $key => $value) {
                $arrPClasses[$key] = $GLOBALS['TL_LANG']['tl_acquisto_pclasses'][$key][0];
            }
        }
        
        return $arrPClasses;
    }


    public function getTemplates(DataContainer $dc)
    {
        return $this->getTemplateGroup('acquisto_produkt_', $dc->activeRecord->pid);
    }
}

?>