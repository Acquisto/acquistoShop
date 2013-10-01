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
 * Table tl_shop_produkte_varianten
 */
 
$this->loadLanguageFile('tl_shop_produkte');

$GLOBALS['TL_DCA']['tl_shop_produkte_varianten'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ptable'                      => 'tl_shop_produkte_attribute',
        'enableVersioning'            => true,
    		'sql' => array
    		(
    			'keys' => array
    			(
    				'id' => 'primary',
            'pid' => 'index'
    			)
    		)
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 4,
            'fields'                  => array('sorting'),
            'headerFields'            => array('attribut_id'),
            'flag'                    => 12,
            'panelLayout'             => 'search,limit',
            'disableGrouping'         => true,
            'child_record_callback'   => array('tl_shop_produkte_varianten', 'listItems'),
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
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_produkte_varianten']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_produkte_varianten']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
      			'cut' => array
      			(
        				'label'               => &$GLOBALS['TL_LANG']['tl_shop_produkte_varianten']['cut'],
        				'href'                => 'act=paste&amp;mode=cut',
        				'icon'                => 'cut.gif',
        				'attributes'          => 'onclick="Backend.getScrollOffset()"'
      			),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_produkte_varianten']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_produkte_varianten']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{title_legend},bezeichnung,produkt_attribut_id;{costs_legend},preise;{image_legend},imageSrc;',
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
        'bezeichnung' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte_varianten']['bezeichnung'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'produkt_attribut_id' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_produkte_varianten']['produkt_attribut_id'],
            'inputType'               => 'select',
            'search'                  => true,
            'options_callback'        => array('tl_shop_produkte_varianten', 'getOptions'),
            'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50', 'includeBlankOption'=>true),
      			'sql'                     => "int(10) NOT NULL default '0'"
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
                				'inputType' 		        => 'text',
                				'eval'                  => array('mandatory'=>false, 'style'=>'width:130px')
              			),
              			'label' => array
              			(
                				'label' 		            => &$GLOBALS['TL_LANG']['tl_shop_produkte']['preise_label'],
                				'inputType' 		        => 'text',
                				'eval'                  => array('mandatory'=>false, 'style'=>'width:130px')
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
        )
    )
);

class tl_shop_produkte_varianten extends Backend
{
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    public function buildStructure($previous_id)
    {
        if($previous_id)
        {
            $objParent = $this->Database->prepare("SELECT * FROM tl_shop_produkte_varianten WHERE id=?")->execute($previous_id);

            if($objParent->produkt_attribut_id)
            {
                $strAdd = $this->buildStructure($objParent->produkt_attribut_id);
            }
        }

        return $strAdd . " &raquo; " . $objParent->bezeichnung;
    }

    public function listItems($arrRow)
    {
        if($arrRow['produkt_attribut_id'])
        {
            $strAdd = $this->buildStructure($arrRow['produkt_attribut_id']) . " &raquo; ";
        }

        return "<span style=\"color:#b3b3b3; padding-left:3px;\">" . $strAdd . "</span>" . $arrRow['bezeichnung'];
    }

    public function getOptions($objData)
    {
        $objParent   = $this->Database->prepare("SELECT * FROM tl_shop_produkte_varianten WHERE id=?")->execute($objData->id);
        $objAttribut = $this->Database->prepare("SELECT * FROM tl_shop_produkte_attribute WHERE id=?")->execute($objParent->pid);

        if($objAttribut->pattribut_id)
        {
            $objWhile = $this->Database->prepare("SELECT * FROM tl_shop_produkte_varianten WHERE pid=?")->execute($objAttribut->pattribut_id);
            while($objWhile->next())
            {
                if($objWhile->produkt_attribut_id)
                {
                    $strAdd = $this->buildStructure($objWhile->produkt_attribut_id) . " &raquo; ";
                }

                $arrOptions[$objWhile->id] = $strAdd . $objWhile->bezeichnung;
            }
        }

        return $arrOptions;
    }
}

?>