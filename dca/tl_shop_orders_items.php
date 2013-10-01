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
 * Table tl_shop_orders_items
 */
$GLOBALS['TL_DCA']['tl_shop_orders_items'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ptable'                      => 'tl_shop_orders',
        'enableVersioning'            => true,
    		'sql' => array
    		(
    			'keys' => array
    			(
    				'id' => 'primary',
            'pid' => 'index'
    			)
    		),
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 4,
            'fields'                  => array('bezeichnung'),
            'headerFields'            => array('tstamp', 'versandzonen_id', 'zahlungsart_id', 'payed'),
            'flag'                    => 12,
            'panelLayout'             => 'search,limit',
            'child_record_callback'   => array('tl_shop_orders_items', 'listItems'),
            'disableGrouping'         => true
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
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_orders_items']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_orders_items']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_orders_items']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_orders_items']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{settings},bezeichnung,menge,preis,steuersatz_id;',
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
        'produkt_id' => array
    		(
    			'sql'                     => "int(10) NOT NULL default '0'"
    		),
        'bezeichnung' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_orders_items']['satz'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'steuersatz_id' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_orders_items']['steuer'],
            'default'                 => $GLOBALS['TL_CONFIG']['steuer'],
            'inputType'               => 'select',
            'foreignKey'              => 'tl_shop_steuer.bezeichnung',
            'search'                  => false,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
      			'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'menge' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_orders_items']['satz'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
      			'sql'                     => "float NOT NULL default '0'"
        ),
        'preis' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_orders_items']['satz'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
      			'sql'                     => "float NOT NULL default '0'"
        ),
        'attribute' => array
    		(
    			'sql'                      => "text NOT NULL"
    		),
    )
);

class tl_shop_orders_items extends Backend {
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    public function listItems($arrRow) {
        $row = $arrRow['bezeichnung'] . ' ' . $GLOBALS['TL_CONFIG']['currency_symbol'] . ' <span style="color:#b3b3b3; padding-left:3px;">[' . $arrRow['menge'] . ' x ' . sprintf("%01.2f", $arrRow['preis']) . ' ' . $GLOBALS['TL_CONFIG']['currency_symbol'] . ' = ' . sprintf("%01.2f", $arrRow['menge'] * $arrRow['preis']) . ' ' . $GLOBALS['TL_CONFIG']['currency_symbol'] . ']</span>';
        return "<div>" . $row . "</div>\n";
    }


}

?>