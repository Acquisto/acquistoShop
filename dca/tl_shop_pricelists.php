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
 * Table tl_shop_pricelists
 */
$GLOBALS['TL_DCA']['tl_shop_pricelists'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => true,
        'switchToEdit'                => true,
        'onsubmit_callback'           => array
        (
            array('tl_shop_pricelists', 'onsubmit_callback')
        ),
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
            'fields'                  => array('title'),
            'flag'                    => 1,
            'panelLayout'             => 'search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('title', 'type'),
            'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>'
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
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_pricelists']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_pricelists']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_pricelists']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_pricelists']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{title_legend},title,default_list;{config_legend},type,currency,exchange_ratio;{show_legend},guests,groups;',
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
        'title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_pricelists']['title'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'type' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_pricelists']['type'],
            'inputType'               => 'select',
            'search'                  => true,
            'options'                 => array('brutto', 'netto'),
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50', 'includeBlankOption' => true),
      			'sql'                     => "char(10) NOT NULL default ''"
        ),
        'currency' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_pricelists']['currency'],
            'inputType'               => 'select',
            'search'                  => true,
            'foreignKey'              => 'tl_shop_currency.title',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50', 'includeBlankOption' => true),
      			'sql'                     => "int(10) NOT NULL default '0'"
        ),                      
        'groups' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_pricelists']['groups'],
            'inputType'               => 'checkbox',
            'foreignKey'              => 'tl_member_group.name',
            'search'                  => false,
            'eval'                    => array('multiple'=>true, 'mandatory'=>false, 'tl_class'=>'w50'),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'guests' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_pricelists']['guests'],
            'inputType'               => 'checkbox',
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50'),
      			'sql'                     => "char(1) NOT NULL default ''"
        ),
        'default_list' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_pricelists']['default_list'],
            'inputType'               => 'checkbox',
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50'),
      			'sql'                     => "char(1) NOT NULL default ''"
        )        
    )
);

class tl_shop_pricelists extends Backend{
    public function onsubmit_callback($dc){
        if($this->Input->Post('default_list') && $this->Input->Post('FORM_SUBMIT') == 'tl_shop_pricelists') {
            $this->Database->prepare("UPDATE tl_shop_pricelists SET default_list = ''")->execute();
            $this->Database->prepare("UPDATE tl_shop_pricelists SET default_list = '1' WHERE id=?")->execute($this->Input->Get('id'));
        }       
    }
}  

?>