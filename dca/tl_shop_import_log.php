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
 * Table tl_shop_import_log
 */
$GLOBALS['TL_DCA']['tl_shop_import_log'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ptable'                      => 'tl_shop_import',
        'enableVersioning'            => true,
    		'sql' => array
    		(
    		  'keys' => array
    			(
    				'id'                      => 'primary',
            'pid'                     => 'index'
    			)
    		),
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 4,
            'fields'                  => array('gueltig_ab'),
            'headerFields'            => array('bezeichnung'),
            'flag'                    => 12,
            'panelLayout'             => 'search,limit',
            'child_record_callback'   => array('tl_shop_import_log', 'listItems'),
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
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_import_log']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_import_log']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_import_log']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_import_log']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{settings},satz,gueltig_ab;',
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
        'satz' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_import_log']['satz'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
    			  'sql'                     => "float NOT NULL default '0'"
        ),
        'gueltig_ab' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_import_log']['gueltig_ab'],
            'default'                 => time(),
            'inputType'               => 'text',
            'exclude'                  => true,
            'eval'                    => array('rgxp'=>'date', 'mandatory'=>true, 'maxlength'=>20, 'datepicker'=>$this->getDatePickerString(),'tl_class'=>'w50 wizard'),
      			'sql'                     => "int(10) NOT NULL default '0'"
        ),
    )
);

class tl_shop_import_log extends Backend {
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    public function listItems($arrRow) {
        $row = sprintf("%01.2f", $arrRow['satz']) . '% MwSt. <span style="color:#b3b3b3; padding-left:3px;">[' . date("d.m.Y", $arrRow['gueltig_ab']) . ']</span>';
        return "<div>" . $row . "</div>\n";
    }


}

?>