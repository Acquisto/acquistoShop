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
 * Table tl_shop_versandzonen
 */
$GLOBALS['TL_DCA']['tl_shop_versandzonen'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ctable'                      => array('tl_shop_versandzonen_varten'),
        'enableVersioning'            => true,
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
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_versandzonen']['edit'],
                'href'                => 'table=tl_shop_versandzonen_varten',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_versandzonen']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_versandzonen']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_versandzonen']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{title_legend},bezeichnung,calculate_tax,description;{country_legend},laender;',
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
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_versandzonen']['bezeichnung'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'calculate_tax' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_versandzonen']['calculate_tax'],
            'inputType'               => 'checkbox',
            'default'                 => true,
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'isBoolean'=>true),
      			'sql'                     => "char(1) NOT NULL default ''"
        ),
        'description' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_versandzonen']['description'],
            'inputType'               => 'textarea',
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'tl_class' => 'clr'),
      			'sql'                     => "text NOT NULL"
        ),
        'laender' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_versandzonen']['laender'],
            'inputType'               => 'checkbox',
            'search'                  => true,
            'options'                 => $this->getCountries(),
            'eval'                    => array('mandatory'=>true, 'multiple'=>true),
      			'sql'                     => "text NOT NULL"
        ),
    )
);

?>