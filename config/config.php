<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * The AcquistoShop extension allows to create an online 
 * store with the OpenSource Contao CMS. If you have
 * questions our website: http://www.contao-acquisto.de  
 *
 * PHP version 5
 * @package	   AcquistoShop
 * @subpackage Config
 * @author     Sascha Brandhoff <brandhoff@contao-acquisto.de>
 * @copyright  AcquistoShop
 * @license    LGPL.
 * @filesource
 */

/**
 * Backend module
 */
$GLOBALS['BE_MOD']['acquisto'] = array(
    'acquistoShopHersteller' => array
    (
        'tables'     => array('tl_shop_hersteller'),
        'icon'       => 'system/modules/acquistoShop/assets/gfx/cog.png',
    ),
    'acquistoShopProdukte' => array
    (
        'tables'           => array('tl_shop_produkte', 'tl_shop_produkte_attribute', 'tl_shop_produkte_varianten'),
        'icon'             => 'system/modules/acquistoShop/assets/gfx/controller.png',

    ),
    'acquistoShopWarengruppen' => array
    (
        'tables'     => array('tl_shop_warengruppen'),
        'icon'       => 'system/modules/acquistoShop/assets/gfx/application_cascade.png',
    ),
    'acquistoShopGutscheine' => array
    (
        'tables'     => array('tl_shop_gutscheine'),
        'icon'       => 'system/modules/acquistoShop/assets/gfx/lightbulb.png',
    )
);


$GLOBALS['BE_MOD']['acquisto_Orders'] = array(
    'acquistoShopOrders' => array
    (
        'tables'     => array('tl_shop_orders', 'tl_shop_orders_items', 'tl_shop_orders_customer'),
        'icon'       => 'system/modules/acquistoShop/assets/gfx/basket.png',
    )
);

$GLOBALS['BE_MOD']['acquisto_Settings'] = array(
    'acquistoShopAttribute' => array
    (
        'tables'     => array('tl_shop_attribute', 'tl_shop_attribute_values'),
        'icon'       => 'system/modules/acquistoShop/assets/gfx/book_link.png',
    ),
    'acquistoShopEinstellungen' => array
    (
        'tables'     => array('tl_shop_settings'),
        'icon'       => 'system/modules/acquistoShop/assets/gfx/wrench.png',
    ),
    'acquistoShopMengeneinheit' => array
    (
        'tables'     => array('tl_shop_mengeneinheit'),
        'icon'       => 'system/modules/acquistoShop/assets/gfx/table.png',
    ),
    'acquistoShopSteuern' => array
    (
        'tables'     => array('tl_shop_steuer', 'tl_shop_steuersaetze'),
        'icon'       => 'system/modules/acquistoShop/assets/gfx/ruby.png',
    ),
    'acquistoShopShippingZones' => array
    (
        'tables'     => array('tl_shop_versandzonen', 'tl_shop_versandzonen_varten'),
        'icon'       => 'system/modules/acquistoShop/assets/gfx/package.png',
    ),
    'acquistoShopZahlungsarten' => array
    (
        'tables'     => array('tl_shop_zahlungsarten'),
        'icon'       => 'system/modules/acquistoShop/assets/gfx/money.png',
    ),
    'acquistoShopCurrency' => array
    (
        'tables'     => array('tl_shop_currency'),
        'icon'       => 'system/modules/acquistoShop/assets/gfx/coins.png',
    ),    
    'acquistoShopPricelists' => array
    (
        'tables'     => array('tl_shop_pricelists'),
        'icon'       => 'system/modules/acquistoShop/assets/gfx/page_paste.png',
    ),    
    'acquistoShopExport' => array
    (
        'tables'     => array('tl_shop_export'),
        'icon'       => 'system/modules/acquistoShop/assets/gfx/page_code.png',
        'export'     => array('tl_shop_export', 'exportData'),
    ),
//     'acquistoShopImport' => array
//     (
//         'tables'     => array('tl_shop_import', 'tl_shop_import_log'),
//         'icon'       => 'system/modules/acquistoShop/assets/gfx/page_code.png',
//         'importData' => array('acquistoShopBackend', 'importData'),
//     )
);


/**
 * Frontend module
 */

array_insert($GLOBALS['FE_MOD']['acquisto'], 0, array
(
    'acquisto_productdetails' => 'ModuleAcquistoProductDetails',
    'acquisto_productlist'    => 'ModuleAcquistoProductList',
    'acquisto_breadcrumb'     => 'ModuleAcquistoBreadcrumb',
    'acquisto_search'         => 'ModuleAcquistoSearch',
    'acquisto_shipping'       => 'ModuleAcquistoShipping',
    'acquisto_currency'       => 'ModuleAcquistoCurrency',
    'acquisto_categories'     => 'ModuleAcquistoCategories',
    'acquisto_basket'         => 'ModuleAcquistoBasket',
    'acquisto_tagcloud'       => 'ModuleAcquistoTagCloud',
    'acquisto_coupon'         => 'ModuleAcquistoCoupon',
    'acquisto_orderlist'      => 'ModuleAcquistoOrderList',
    'acquisto_orderdetails'   => 'ModuleAcquistoOrderDetails',
    'acquisto_filterlist'     => 'ModuleAcquistoFilterList',
    'acquisto_terms'          => 'ModuleAcquistoTerms'
));

array_insert($GLOBALS['FE_MOD']['acquisto_widget'], 0, array
(
    'acquisto_basketwidget'   => 'ModuleAcquistoBasketWidget',
    'acquisto_productfilter'  => 'ModuleAcquistoProductFilter',
    'acquisto_recently'       => 'ModuleAcquistoRecently'
));


/**
 * Form fields
 **/
$GLOBALS['BE_FFL']['categorieTree'] = 'categorieTree';
$GLOBALS['BE_FFL']['preisWizard']   = 'preisWizard';

/**
 * Content Element
 */
$GLOBALS['TL_CTE']['acquistoShop']['product'] = 'ContentProduct';

$GLOBALS['TL_JAVASCRIPT'][] = '/system/modules/acquistoShop/assets/js/acquistoShop.js';

$GLOBALS['TL_HEAD'][] = '<!--

    Acquisto Webshop - http://contao-acquisto.de :: Licensed under GNU/LGPL
    Copyright (c)2012 by Sascha Brandhoff :: Extensions of Acquisto are copyright of their respective owners
    Visit the project website at http://www.contao-acquisto.de for more information

//-->';

$GLOBALS['TL_HOOKS']['getSystemMessages']['loadSystemmessages'] = array('AcquistoShop\acquistoShopMessages', 'checkAcquistoState');
$GLOBALS['TL_HOOKS']['executePreActions']['loadCategorietree']  = array('AcquistoShop\acquistoShopAjax', 'PRECategorieActions');
$GLOBALS['TL_HOOKS']['executePostActions']['loadCategorietree'] = array('AcquistoShop\acquistoShopAjax', 'POSTCategorieActions');

$GLOBALS['TL_CONFIG']['acquistoShopVersion'] = '1.3.3';

if (TL_MODE == 'BE') {
    $GLOBALS['TL_CSS'][] = 'system/modules/acquistoShop/assets/css/backend.css|all';

    if($GLOBALS['TL_CONFIG']['serialnumber']) {
        $GLOBALS['BE_MOD']['acquisto_Settings']['acquistoShopPortal'] = array
        (
           'callback'   => 'ModuleAcquistoPortal',
           'icon'       => 'system/modules/acquistoShop/html/assets/gfx/plugin.png',
        );
    }

//     AcquistoShop\Helper\AcquistoUpdate::upgradeVersion();
}

$GLOBALS['ACQUISTO_PCLASS']['normal']  = array('AcquistoShop\Product\acquistoPClassNormal', 'acquistoShop');
$GLOBALS['ACQUISTO_PCLASS']['digital'] = array('AcquistoShop\Product\acquistoPClassDigital', 'acquistoShop');
$GLOBALS['ACQUISTO_PCLASS']['variant'] = array('AcquistoShop\Product\acquistoPClassVariant', 'acquistoShop');

?>