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
 * Table tl_shop_hersteller
 */
$GLOBALS['TL_DCA']['tl_shop_hersteller'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
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
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_hersteller']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_hersteller']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_hersteller']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_hersteller']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{title_legend},bezeichnung;{address_legend},street,postal,city;{contact_legend},phone,fax,email,website;{description_legend},description;{logo_legend},imageSrc;',
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
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_hersteller']['bezeichnung'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'postal' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_hersteller']['postal'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'maxlength'=>32, 'rgxp' => 'digit', 'tl_class'=>'w50'),
      			'sql'                     => "char(32) NOT NULL default ''"
        ),
        'street' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_hersteller']['street'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'maxlength'=>64),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'city' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_hersteller']['city'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50'),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'phone' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_hersteller']['phone'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'rgxp' => 'phone', 'tl_class'=>'w50'),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'fax' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_hersteller']['fax'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'rgxp' => 'phone', 'tl_class'=>'w50'),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'website' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_hersteller']['website'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'rgxp' => 'url', 'tl_class'=>'w50'),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'email' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_hersteller']['email'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'rgxp' => 'email', 'tl_class'=>'w50'),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'description' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_hersteller']['description'],
            'inputType'               => 'textarea',
            'exclude'                 => true,
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'rte'=>'tinyMCE', 'tl_class'=>'clr', 'acquistoSearch'=>true),
      			'sql'                     => "text NOT NULL"
        ),
        'imageSrc' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_hersteller']['imageSrc'],
            'inputType'               => 'fileTree',
            'exclude'                 => true,
            'search'                  => false,
            'eval'                    => array('mandatory'=>false,'fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true,'extensions'=>'jpg,png,gif'),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        )        
    )
);

?>