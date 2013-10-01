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
 * Table tl_shop_attribute_values
 */
$GLOBALS['TL_DCA']['tl_shop_attribute_values'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ptable'                      => 'tl_shop_attribute',
        'enableVersioning'            => true,
        'switchToEdit'                => true,
    		'sql' => array
    		(
    			'keys' => array
    			(
    				'id'  => 'primary',
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
            'fields'                  => array('sorting'),
            'headerFields'            => array('bezeichnung', 'feldtyp'),
            'flag'                    => 11,
            'panelLayout'             => 'search,limit',
            'disableGrouping'         => true,
            'child_record_callback'   => array('tl_shop_attribute_values', 'listItems')
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
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_attribute_values']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_attribute_values']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
      			'cut' => array
      			(
        				'label'               => &$GLOBALS['TL_LANG']['tl_shop_attribute_values']['cut'],
        				'href'                => 'act=paste&amp;mode=cut',
        				'icon'                => 'cut.gif',
        				'attributes'          => 'onclick="Backend.getScrollOffset()"'
      			),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_attribute_values']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_attribute_values']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => array('type'),
        'default'                     => '{title_legend},type,bezeichnung;{costs_legend},preise;',
        'normal'                      => '{title_legend},type,bezeichnung;{costs_legend},preise;',
        'counter'                     => '{title_legend},type,bezeichnung;{costs_legend},preise;{counter_legend},from_value,to_value,steps_value,dezimal;',
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
    		'sorting' => array
    		(
    			'sql'                     => "int(10) unsigned NOT NULL default '0'"
    		),
    		'tstamp' => array
    		(
      			'sql'                     => "int(10) unsigned NOT NULL default '0'"
    		),
        'type' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_attribute_values']['type'],
            'inputType'               => 'select',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50', 'submitOnChange'=>true),
            'options'                 => array('item' => &$GLOBALS['TL_LANG']['tl_shop_attribute_values']['item'], 'counter' => &$GLOBALS['TL_LANG']['tl_shop_attribute_values']['counter']),
      			'sql'                     => "char(64) NOT NULL default ''"
        ),
        'bezeichnung' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_attribute_values']['bezeichnung'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'from_value' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_attribute_values']['from_value'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
      			'sql'                     => "float NOT NULL default '0'"
        ),
        'to_value' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_attribute_values']['to_value'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
      			'sql'                     => "float NOT NULL default '0'"
        ),
        'steps_value' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_attribute_values']['steps_value'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
      			'sql'                     => "float NOT NULL default '0'"
        ),
        'dezimal' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_attribute_values']['dezimal'],
            'inputType'               => 'text',
            'default'                 => '0',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>3, 'tl_class'=>'w50'),
      			'sql'                     => "char(3) NOT NULL default ''"
        ),
        'preise' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_attribute_values']['preise'],
            'inputType'               => 'preisWizard',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false),
      			'sql'                     => "text NOT NULL"
        )
    )
);

class tl_shop_attribute_values extends Backend
{
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    public function listItems($arrRow) {
        if($arrRow['type'] == 'counter') {
            $ausdruck = "%01." . $arrRow['dezimal'] . "f";
            if(substr_count($arrRow['bezeichnung'], '%s')) {
                $ausdruck = sprintf($arrRow['bezeichnung'], $ausdruck);
            }

            $strReturn  = "<b>" . $GLOBALS['TL_LANG']['tl_shop_attribute_values']['counter'] . ":</b>";
            $strReturn .= "<ul>";

            for($nI = $arrRow['from_value']; $nI < $arrRow['to_value']; $nI = $nI + $arrRow['steps_value']) {
                $strReturn .= "<li>" . sprintf($ausdruck, $nI) . "</li>";
            }

            $strReturn .= "</ul>";
            return $strReturn;
        } else {
            return "<b>" . $GLOBALS['TL_LANG']['tl_shop_attribute_values']['item'] . ":</b> " . $arrRow['bezeichnung'];
        }
    }
}

?>