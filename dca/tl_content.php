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
 * Add a palette to tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['product']    = '{title_legend},type,product_id;{config_legend};{expert_legend:hide},cssID,space';

/**
 * Add fields to tl_module
 */

$GLOBALS['TL_DCA']['tl_content']['fields']['product_id'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['product_id'],
    'default'                 => 0,
    'exclude'                 => true,
    'inputType'               => 'select',
    'foreignKey'              => 'tl_shop_produkte.bezeichnung',
    'eval'                    => array('rgxp'=>'digit', 'chosen' => true, 'mandatory'=>true, 'tl_class'=>'w50'),
    'sql'                     => "int(10) NOT NULL default '0'"
);

?>