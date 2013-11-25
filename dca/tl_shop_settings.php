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
 * System configuration
 */
$GLOBALS['TL_DCA']['tl_shop_settings'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'File',
        'closed'                      => true
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{shopdaten},firmentyp,firmenname,inhaber,strasse,postleitzahl,ort,telefon,telefax,bestell_email;{bankverbindung},bank,bank_inhaber,blz,kto,iban,bic;{rechnungs_format},rechnungsformat,costs_input;{rechtangaben},steuernummer,umsatzsteuerindent,handelsregister;{settings},steuer,versandberechnung,agb,widerruf,versandPage;'
    ),

    // Fields
    'fields' => array
    (
        'steuer' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['steuer'],
            'inputType'               => 'select',
            'foreignKey'              => 'tl_shop_steuer.bezeichnung',
            'search'                  => false,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50')
        ),
        'versandberechnung' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['versandberechnung'],
            'inputType'               => 'select',
            'options'                 => array('preis' => 'Preis', 'gewicht' => 'Gewicht'),
            'search'                  => false,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50')
        ),
        'agb' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['agb'],
            'inputType'               => 'textarea',
            'eval'                    => array('mandatory'=>true, 'rte'=>'tinyMCE', 'tl_class'=>'clr')
        ),
        'widerruf' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['widerruf'],
            'inputType'               => 'textarea',
            'eval'                    => array('mandatory'=>true, 'rte'=>'tinyMCE', 'tl_class'=>'clr')
        ),
        'firmenname' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['firmenname'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50')
        ),
        'firmentyp' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['firmentyp'],
            'inputType'               => 'select',
            'search'                  => false,
            'options'                 => array('Einzelunternehmen', 'Einzelunternehmen e.K.', 'GbR', 'OHG', 'OHG mbh', 'gGmbH', 'GmbH', 'GmbH &amp; Co. KG', 'GmbH &amp; Co. KGaA', 'UG', 'gAG', 'AG', 'AG &amp; Co. KGaA', 'Privat'),
            'eval'                    => array('mandatory'=>true)
        ),
        'inhaber' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['inhaber'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50')
        ),
        'steuernummer' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['steuernummer'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false)
        ),
        'costs_input' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['costs_input'],
            'inputType'               => 'select',
            'default'                 => '',
            'options'                 => array('brutto' => 'brutto', 'netto' => 'netto'),
            'search'                  => false,
            'eval'                    => array('mandatory'=>false)
        ),
        'rechnungsformat' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['rechnungsformat'],
            'inputType'               => 'text',
            'default'                 => '[C][C5]',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false)
        ),
        'umsatzsteuerindent' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['umsatzsteuerindent'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50')
        ),
        'handelsregister' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['handelsregister'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50')
        ),
        'strasse' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['strasse'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50')
        ),
        'postleitzahl' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['postleitzahl'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50')
        ),
        'ort' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['ort'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50')
        ),
        'telefon' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['telefon'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50')
        ),
        'telefax' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['telefax'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50')
        ),
        'bestell_email' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['bestell_email'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50')
        ),
        'bank' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['bank'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50')
        ),
        'bank_inhaber' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['bank_inhaber'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50')
        ),
        'blz' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['blz'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50')
        ),
        'kto' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['kto'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50')
        ),
        'iban' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['iban'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50')
        ),
        'bic' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['bic'],
            'inputType'               => 'text',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'tl_class'=>'w50')
        ),
        'versandPage' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_settings']['versandPage'],
            'exclude'                 => true,
            'inputType'               => 'pageTree',
            'eval'                    => array('mandatory'=>true, 'fieldType'=>'radio')
        )             
    )
);

?>