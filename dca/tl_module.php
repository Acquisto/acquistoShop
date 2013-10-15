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
 * Add a palette to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquisto_productdetails']   = '{title_legend},name,type;{contaoShop_image},contaoShop_imageSrc,contaoShop_imageSize,contaoShop_imageMargin,contaoShop_imageFloating,contaoShop_imageFullsize;{galerie_options:hide},acquistoShop_galerie_imageSize,acquistoShop_galerie_imageMargin,acquistoShop_galerie_imageFloating,acquistoShop_galerie_imageFullsize;{socialmedia},contaoShop_socialFacebook,contaoShop_socialTwitter;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquisto_productfilter']    = '{title_legend},name,headline,type;{config_legend};{expert_legend:hide},cssID,space;';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquisto_basketwidget']     = '{title_legend},name,headline,type;{config_legend},contaoShop_jumpTo;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquisto_productlist']      = '{title_legend},name,type;{config_legend},contaoShop_jumpTo,contaoShop_numberOfItems,perPage,acquistoShop_elementsPerRow,acquistoShop_listTemplate;{list_images:hide},contaoShop_imageSrc,acquistoShop_galerie_imageSize,acquistoShop_galerie_imageMargin,acquistoShop_galerie_imageFloating;{categorie_image},contaoShop_imageSize,contaoShop_imageMargin,contaoShop_imageFloating;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquisto_filterlist']       = '{title_legend},name,headline,type;{config_legend},acquistoShop_tags,acquistoShop_zustand,acquistoShop_hersteller,acquistoShop_produkttype,acquistoShop_warengruppe,acquistoShop_markedProducts;{list_settings},contaoShop_jumpTo,contaoShop_numberOfItems,perPage,acquistoShop_elementsPerRow,acquistoShop_listTemplate;{list_images:hide},contaoShop_imageSrc,acquistoShop_galerie_imageSize,acquistoShop_galerie_imageMargin,acquistoShop_galerie_imageFloating;{expert_legend:hide},cssID,space;';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquisto_recently']         = '{title_legend},name,headline,type;{config_legend},contaoShop_jumpTo,contaoShop_numberOfItems;{list_images:hide},contaoShop_imageSrc,acquistoShop_galerie_imageSize,acquistoShop_galerie_imageMargin,acquistoShop_galerie_imageFloating;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquisto_tagcloud']         = '{title_legend},name,headline,type;{config_legend},acquistoShop_excludeTags,contaoShop_jumpTo;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquisto_shipping']         = '{title_legend},name,headline,type;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquisto_basket']           = '{title_legend},name,headline,type;{config_legend},acquistoShop_basketType,acquistoShop_guestOrder,contaoShop_jumpTo,acquistoShop_AGBFile;{email_legend},acquistoShop_emailTyp,acquistoShop_emailTemplate,acquistoShop_emailTemplate_seller,acquistoShop_emailSubject_Buyer,acquistoShop_emailSubject_Seller;{fields:hide},acquistoShop_selFields;{deliver:hide},acquistoShop_selDeliver;{addon_legend},acquistoShop_fieldsAddon;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquisto_terms']            = '{title_legend},name,headline,type;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquisto_manufacturerlist'] = '{title_legend},name,headline,type;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquisto_breadcrumb']       = '{title_legend},name,headline,type;{config_legend},contaoShop_jumpTo;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquisto_search']           = '{title_legend},name,headline,type;{config_legend},acquistoShop_Searchfields,contaoShop_imageSrc,contaoShop_jumpTo;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquisto_categories']       = '{title_legend},name,headline,type;{config_legend},contaoShop_levelOffset,contaoShop_showLevel,contaoShop_hardLimit,contaoShop_jumpTo;[reference:hide},contaoShop_reference,contaoShop_referencePage;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquisto_orderlist']        = '{title_legend},name,headline,type;{config_legend},contaoShop_jumpTo;{expert_legend:hide},cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquisto_coupon']           = '{title_legend},name,headline,type;{expert_legend:hide},cssID,space;';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquisto_orderdetails']     = '{title_legend},name,headline,type;{config_legend};{expert_legend:hide},cssID,space;';
$GLOBALS['TL_DCA']['tl_module']['palettes']['acquisto_currency']         = '{title_legend},name,headline,type;{config_legend};{expert_legend:hide},cssID,space;';

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'acquistoShop_allowComments';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['acquistoShop_allowComments'] = 'com_disableCaptcha,com_requireLogin,com_bbcode,com_moderate,com_template,acquistoShop_commentsPerPage,com_order,acquistoShop_commentsNotify';

/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_jumpTo'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_jumpTo'],
    'exclude'                 => true,
    'inputType'               => 'pageTree',
    'eval'                    => array('mandatory'=>true, 'fieldType'=>'radio', 'tl_class' => 'clr'),
    'sql'                     => "int(10) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_numberOfItems'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_numberOfItems'],
    'default'                 => 0,
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'digit', 'mandatory'=>true, 'tl_class'=>'w50'),
    'sql'                     => "smallint(5) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_excludeTags'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_excludeTags'],
    'default'                 => 0,
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('mandatory'=>false, 'tl_class'=>'long'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_emailSubject_Buyer'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_emailSubject_Buyer'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_emailSubject_Seller'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_emailSubject_Seller'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_imageSize'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['size'],
    'exclude'                 => true,
    'inputType'               => 'imageSize',
    'options'                 => array('crop', 'proportional', 'box'),
    'reference'               => &$GLOBALS['TL_LANG']['MSC'],
    'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_imageMargin'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['imagemargin'],
    'exclude'                 => true,
    'inputType'               => 'trbl',
    'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
    'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(128) NOT NULL default ''"
);


$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_imageFloating'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['floating'],
    'exclude'                 => true,
    'inputType'               => 'radioTable',
    'options'                 => array('above', 'left', 'right', 'below'),
    'eval'                    => array('cols'=>4, 'tl_class'=>'w50'),
    'reference'               => &$GLOBALS['TL_LANG']['MSC'],
    'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_imageFullsize'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_imageFullsize'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_Searchfields'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_Searchfields'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'options_callback'        => array('tl_module_acquistoShop', 'getSearchfields'),
    'eval'                    => array('multiple'=>true),
    'sql'                     => "text NOT NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_levelOffset'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_levelOffset'],
    'default'                 => 0,
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'digit', 'mandatory'=>true, 'tl_class'=>'w50'),
    'sql'                     => "smallint(5) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_showLevel'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_showLevel'],
    'default'                 => 0,
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'digit', 'mandatory'=>true, 'tl_class'=>'w50'),
    'sql'                     => "smallint(5) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_menuTyp'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_menuTyp'],
    'default'                 => 0,
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('root' => 'Root', 'sub' => 'Sub'),
    'eval'                    => array('mandatory'=>true),
    'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_basketType'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_basketType'],
    'default'                 => 0,
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('brutto' => 'Brutto (inkl. MwSt.)', 'netto' => 'Netto (zzgl. MwSt.)', 'pricelist' => 'Preislisten definiert'),
    'eval'                    => array('mandatory'=>true),
    'sql'                     => "char(10) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_guestOrder'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_guestOrder'],
    'default'                 => 0,
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('mandatory'=>false, 'isBoolean'=>true),
    'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_imageSrc'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_imageSrc'],
    'inputType'               => 'fileTree',
    'search'                  => false,
    'eval'                    => array('mandatory'=>false,'fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true,'extensions'=>'jpg,png,gif', 'tl_class' => 'clr'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_socialFacebook'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_socialFacebook'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "smallint(1) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_socialTwitter'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_socialTwitter'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "smallint(1) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_AGBFile'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_AGBFile'],
    'inputType'               => 'fileTree',
    'search'                  => false,
    'eval'                    => array('mandatory'=>false,'fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true,'extensions'=>'pdf'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_emailTemplate'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_emailTemplate'],
    'default'                 => 'warenkorb_email_default',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_module_acquistoShop', 'get_emailTemplate'),
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_emailTemplate_seller'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_emailTemplate_seller'],
    'default'                 => 'warenkorb_email_default',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_module_acquistoShop', 'get_emailTemplate'),
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_listTemplate'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_listTemplate'],
    'default'                 => 'acquisto_list_default',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_module_acquistoShop', 'get_listTemplates'),
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_emailTyp'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_emailTyp'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('text' => 'Text E-Mail', 'html' => 'HTML E-Mail'),
    'eval'                    => array(),
    'sql'                     => "char(4) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_elementsPerRow'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_elementsPerRow'],
    'default'                 => 0,
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'digit', 'mandatory'=>true, 'tl_class'=>'w50'),
    'sql'                     => "int(10) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_galerie_imageSize'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['size'],
    'exclude'                 => true,
    'inputType'               => 'imageSize',
    'options'                 => array('crop', 'proportional', 'box'),
    'reference'               => &$GLOBALS['TL_LANG']['MSC'],
    'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_galerie_imageMargin'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['imagemargin'],
    'exclude'                 => true,
    'inputType'               => 'trbl',
    'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
    'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(128) NOT NULL default ''"
);


$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_galerie_imageFloating'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['floating'],
    'exclude'                 => true,
    'inputType'               => 'radioTable',
    'options'                 => array('above', 'left', 'right', 'below'),
    'eval'                    => array('cols'=>4, 'tl_class'=>'w50'),
    'reference'               => &$GLOBALS['TL_LANG']['MSC'],
    'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_galerie_imageFullsize'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_imageFullsize'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_markedProducts'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_markedProducts'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'w50 m12'),
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_tags'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_tags'],
    'default'                 => '',
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_hersteller'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_hersteller'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'foreignKey'              => 'tl_shop_hersteller.bezeichnung',
    'eval'                    => array('tl_class'=>'w50', 'includeBlankOption' => true),
    'sql'                     => "int(10) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_produkttype'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_produkttype'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_module_acquistoShop', 'listPClasses'),
    'eval'                    => array('tl_class'=>'w50', 'includeBlankOption' => true),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_zustand'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_zustand'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('new' => 'Neu', 'used'=>'Gebraucht', 'refurbished' => 'Erneuert'),
    'eval'                    => array('tl_class'=>'w50', 'includeBlankOption' => true),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_allowComments'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_allowComments'],
    'exclude'                 => true,
    'filter'                  => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('submitOnChange'=>true),
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_commentsNotify'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_commentsNotify'],
    'default'                 => 'notify_admin',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('notify_admin', 'notify_author', 'notify_both'),
    'reference'               => &$GLOBALS['TL_LANG']['tl_news_archive'],
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_commentsPerPage'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_commentsPerPage'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50'),
    'sql'                     => "char(10) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_warengruppe'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_warengruppe'],
    'exclude'                 => true,
    'inputType'               => 'categorieTree',
    'foreignKey'              => 'tl_shop_warengruppen.title',
    'eval'                    => array('tl_class'=>'clr', 'includeBlankOption' => true, 'fieldType'=>'radio'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_selFields'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_selFields'],
    'exclude'                 => true,
	  'inputType'               => 'multiColumnWizard',
    'eval'                    =>array
  	(
    		'columnFields' => array
    		(
      			'field' => array
      			(
        				'label'                 => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_selFields_field'],
        				'exclude'               => true,
        				'inputType'             => 'select',
        				'options_callback'      => array('tl_module_acquistoShop', 'getEditableMemberProperties'),
        				'eval' 			            => array('style' => 'width:150px', 'includeBlankOption'=>true)
      			),
      			'mandatory' => array
      			(
        				'label'                 => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_selFields_mandatory'],
        				'exclude'               => true,
        				'inputType'             => 'select',
                'options'               => array(1 => 'Nein', 2 => 'Ja'),
        				'eval' 		             	=> array('style'=>'width:80px', 'rgxp'=>'digit')
      			),
      			'info' => array
      			(
        				'label'                 => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_selFields_info'],
        				'inputType'             => 'text',
        				'eval'                  => array('mandatory'=>false, 'style'=>'width:165px')
      			),
      			'error' => array
      			(
        				'label' 		            => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_selFields_error'],
        				'inputType' 		        => 'text',
        				'eval'                  => array('mandatory'=>false, 'style'=>'width:165px')
      			),
    		)
  	),
    'sql'                     => "text NOT NULL"
);


$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_selDeliver'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_selDeliver'],
    'exclude'                 => true,
	  'inputType'               => 'multiColumnWizard',
    'eval'                    =>array
  	(
    		'columnFields' => array
    		(
      			'field' => array
      			(
        				'label'                 => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_selFields_field'],
        				'exclude'               => true,
        				'inputType'             => 'select',
        				'options_callback'      => array('tl_module_acquistoShop', 'getEditableMemberProperties'),
        				'eval' 			            => array('style' => 'width:150px', 'includeBlankOption'=>true)
      			),
      			'mandatory' => array
      			(
        				'label'                 => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_selFields_mandatory'],
        				'exclude'               => true,
        				'inputType'             => 'select',
                'options'               => array(1 => 'Nein', 2 => 'Ja'),
        				'eval' 		             	=> array('style'=>'width:80px', 'rgxp'=>'digit')
      			),
      			'info' => array
      			(
        				'label'                 => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_selFields_info'],
        				'inputType'             => 'text',
        				'eval'                  => array('mandatory'=>false, 'style'=>'width:165px')
      			),
      			'error' => array
      			(
        				'label' 		            => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_selFields_error'],
        				'inputType' 		        => 'text',
        				'eval'                  => array('mandatory'=>false, 'style'=>'width:165px')
      			),
    		)
  	),
    'sql'                     => "text NOT NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['acquistoShop_fieldsAddon'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_fieldsAddon'],
    'exclude'                 => true,
	  'inputType'               => 'multiColumnWizard',
    'eval'                    =>array
  	(
    		'columnFields' => array
    		(
      			'fieldname' => array
      			(
        				'label'                 => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_fieldsAddon_fieldname'],
        				'inputType'             => 'text',
        				'eval'                  => array('mandatory'=>false, 'style'=>'width:130px')
      			),
      			'fieldtitle' => array
      			(
        				'label'                 => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_fieldsAddon_fieldtitle'],
        				'inputType'             => 'text',
        				'eval'                  => array('mandatory'=>false, 'style'=>'width:130px')
      			),
      			'fieldtype' => array
      			(
        				'label'                 => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_fieldsAddon_fieldtype'],
        				'exclude'               => true,
        				'inputType'             => 'select',
        				'options'               => array('text' => $GLOBALS['TL_LANG']['tl_module']['acquistoShop_fieldsAddon_fieldtype_opt_text'][0], 'select' => $GLOBALS['TL_LANG']['tl_module']['acquistoShop_fieldsAddon_fieldtype_opt_select'][0], 'checkbox' => $GLOBALS['TL_LANG']['tl_module']['acquistoShop_fieldsAddon_fieldtype_opt_checkbox'][0], 'radio' => $GLOBALS['TL_LANG']['tl_module']['acquistoShop_fieldsAddon_fieldtype_opt_radio'][0], 'textarea' => $GLOBALS['TL_LANG']['tl_module']['acquistoShop_fieldsAddon_fieldtype_opt_textarea'][0]),
        				'eval' 			            => array('style' => 'width:75px', 'includeBlankOption'=>true)
      			),
      			'mandatory' => array
      			(
        				'label'                 => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_selFields_mandatory'],
        				'exclude'               => true,
        				'inputType'             => 'select',
                'options'               => array(1 => 'Nein', 2 => 'Ja'),
        				'eval' 		             	=> array('style'=>'width:75px', 'rgxp'=>'digit')
      			),
      			'value' => array
      			(
        				'label'                 => &$GLOBALS['TL_LANG']['tl_module']['acquistoShop_fieldsAddon_value'],
        				'inputType'             => 'text',
        				'eval'                  => array('mandatory'=>false, 'style'=>'width:136px')
      			),
    		)
  	),
    'sql'                     => "text NOT NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_hardLimit'] = array
(          
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_hardLimit'],
    'exclude'                 => true,
	  'inputType'               => 'checkbox',
	  'inputType'               => 'checkbox',
    'eval'                    => array('mandatory'=>false, 'isBoolean'=>true),
    'sql'                     => "smallint(1) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_reference'] = array
(          
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_reference'],
    'exclude'                 => true,
	  'inputType'               => 'checkbox',
    'eval'                    => array('mandatory'=>false, 'isBoolean'=>true),
    'sql'                     => "smallint(1) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['contaoShop_referencePage'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contaoShop_referencePage'],
    'exclude'                 => true,
    'inputType'               => 'categorieTree',
    'foreignKey'              => 'tl_shop_warengruppen.title',
    'eval'                    => array('tl_class'=>'', 'includeBlankOption' => true,'fieldType'=>'radio'),
    'sql'                     => "int(10) NOT NULL default '0'"
);

class tl_module_acquistoShop extends Backend
{
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }
    
    public function listPClasses() 
    {
        $this->loadLanguageFile('tl_acquisto_pclasses');
        if(is_array($GLOBALS['ACQUISTO_PCLASS'])) 
        {
            foreach($GLOBALS['ACQUISTO_PCLASS'] as $key => $value) 
            {
                $arrPClasses[$key] = $GLOBALS['TL_LANG']['tl_acquisto_pclasses'][$key][0];
            }
        }
        
        return $arrPClasses;    
    }

    public function get_contaoShopTemplates(DataContainer $dc)
    {
        return $this->getTemplateGroup('cs_', $dc->activeRecord->pid);
    }

    public function get_listTemplates(DataContainer $dc)
    {
        return $this->getTemplateGroup('acquisto_list_', $dc->activeRecord->pid);
    }

    public function get_emailTemplate(DataContainer $dc)
    {
        return $this->getTemplateGroup('warenkorb_email', $dc->activeRecord->pid);
    }
    
    public function getSearchfields() 
    {
        $this->loadLanguageFile('tl_shop_produkte');
        $this->loadDataContainer('tl_shop_produkte');
        
        foreach($GLOBALS['TL_DCA']['tl_shop_produkte']['fields'] as $key => $value) {
            if($value['eval']['acquistoSearch']) {
                $returnVal[$key] = $value['label'][0];
            }
        }
        
        return $returnVal;       
    }
    
  	public function getEditableMemberProperties()
  	{
        $objModule = new tl_module();
        $arrFields = $objModule->getEditableMemberProperties();
        
        unset($arrFields['groups']);
        unset($arrFields['username']);
        unset($arrFields['password']);
        unset($arrFields['newsletter']);
        
        return $arrFields;
  	}     
}

?>