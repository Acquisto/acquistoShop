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
 * Table tl_shop_attribute
 */
$GLOBALS['TL_DCA']['tl_shop_attribute'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ctable'                      => array('tl_shop_attribute_values'),
        'enableVersioning'            => true,
        'switchToEdit'                => true,
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
            'fields'                  => array('bezeichnung'),
            'flag'                    => 1,
            'panelLayout'             => 'search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('bezeichnung'),
            'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[]</span>'
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
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_attribute']['edit'],
                'href'                => 'table=tl_shop_attribute_values',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_attribute']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_attribute']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_attribute']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{title_legend},bezeichnung,feldtyp,preisberechnung;',
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
        'bezeichnung' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_attribute']['bezeichnung'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'feldtyp' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_attribute']['feldtyp'],
            'inputType'               => 'select',
            'search'                  => true,
            'options'                 => array('select' => 'Dropdown', 'radio' => 'Radiobuttons', 'checkbox' => 'Checkboxen'),
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
      			'sql'                     => "char(32) NOT NULL default ''"
        ),
        'preisberechnung' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_attribute']['preisberechnung'],
            'inputType'               => 'select',
            'search'                  => true,
            'options'                 => array('addieren' => 'Addieren', 'endpreis' => 'Endpreis'),
            'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50', 'includeBlankOption'=>true),
      			'sql'                     => "char(64) NOT NULL default ''"
        )
    )
);

?>