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
 * Table tl_shop_gutscheine
 */
$GLOBALS['TL_DCA']['tl_shop_gutscheine'] = array
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
            'fields'                  => array('code'),
            'flag'                    => 1,
            'panelLayout'             => 'search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('code'),
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
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => array('zeitgrenze', 'using_counter'),
        'default'                     => '{allgemein},code,preis,kunden_id;{config_legend},using_counter,zeitgrenze;{state},aktiv;',
    ),

    'subpalettes' => array
    (
        'zeitgrenze'               => 'gueltig_von,gueltig_bis',
        'using_counter'            => 'max_using'
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
    		'bestellung_id' => array
    		(
    			'sql'                     => "int(10) NOT NULL default '0'"
    		),
    		'is_used' => array
    		(
    			'sql'                     => "int(10) NOT NULL default '0'"
    		),
        'code' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['code'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>20),
      			'sql'                     => "char(20) NOT NULL default ''"
        ),
        'preis' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['preis'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>20, 'tl_class'=>'w50'),
      			'sql'                     => "float NOT NULL default '0'"
        ),
        'kunden_id' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['kunden_id'],
            'inputType'               => 'select',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50', 'includeBlankOption'=>true),
            'options_callback'        => array('tl_shop_gutscheine', 'getMember'),
      			'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'zeitgrenze' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['zeitgrenze'],
            'inputType'               => 'checkbox',
            'default'                 => '',
            'eval'                    => array('mandatory'=>false, 'isBoolean' => true, 'submitOnChange' => true),
      			'sql'                     => "char(1) NOT NULL default ''"
        ),
        'using_counter' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['using_counter'],
            'inputType'               => 'checkbox',
            'default'                 => '',
            'eval'                    => array('mandatory'=>false, 'isBoolean' => true, 'submitOnChange' => true),
      			'sql'                     => "char(1) NOT NULL default ''"

        ),
        'max_using' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['max_using'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>20),
      			'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'gueltig_von' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['gueltig_von'],
            'default'                 => time(),
            'inputType'               => 'text',
            'exclude'                  => true,
            'eval'                    => array('rgxp'=>'date', 'mandatory'=>true, 'maxlength'=>20, 'datepicker'=>$this->getDatePickerString(),'tl_class'=>'w50 wizard'),
      			'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'gueltig_bis' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['gueltig_bis'],
            'default'                 => time(),
            'inputType'               => 'text',
            'exclude'                  => true,
            'eval'                    => array('rgxp'=>'date', 'mandatory'=>true, 'maxlength'=>20, 'datepicker'=>$this->getDatePickerString(),'tl_class'=>'w50 wizard'),
      			'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'aktiv' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_gutscheine']['aktiv'],
            'inputType'               => 'checkbox',
            'search'                  => true,
            'eval'                    => array('mandatory'=>false),
      			'sql'                     => "char(1) NOT NULL default ''"
        )
    )
);

class tl_shop_gutscheine extends Backend {
    public function __construct() {
        parent::__construct();
    }

    public function getMember() {
        $objMember = $this->Database->prepare("SELECT * FROM tl_member")->execute();
        while($objMember->next()) {
            $arrMember[$objMember->id] = $objMember->firstname . " " . $objMember->lastname;
        }

        return $arrMember;
    }
}
?>