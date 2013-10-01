<?php

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
 * Register the namespaces
 */
 
ClassLoader::addNamespaces(array
(
	'AcquistoShop',
	'AcquistoShop\Frontend',
	'AcquistoShop\Payment',
  'AcquistoShop\Product',
	'AcquistoShop\Helper'
));
 
/**
 * Register the classes
 */

ClassLoader::addClasses(array
(
	// ExportModules
	'googlemerchant'                      => 'system/modules/acquistoShop/modules/export/googlemerchant.php',
	'billigerde'                          => 'system/modules/acquistoShop/modules/export/billigerde.php',

    // ProductClasses
	'AcquistoShop\Product\acquistoPClassNormal'               => 'system/modules/acquistoShop/modules/product/acquistoPClassNormal.php',
	'AcquistoShop\Product\acquistoPClassDigital'              => 'system/modules/acquistoShop/modules/product/acquistoPClassDigital.php',
	'AcquistoShop\Product\acquistoPClassVariant'              => 'system/modules/acquistoShop/modules/product/acquistoPClassVariant.php',

	// Widgets
	'categorieTree'                    => 'system/modules/acquistoShop/widgets/categorieTree.php',
	'memberWizard'                     => 'system/modules/acquistoShop/widgets/memberWizard.php',
	'preisWizard'                      => 'system/modules/acquistoShop/widgets/preisWizard.php',
  

	// Models
	'AcquistoShop\acquistoShop'                               => 'system/modules/acquistoShop/models/acquistoShop.php',
	'AcquistoShop\acquistoShopAjax'                           => 'system/modules/acquistoShop/models/acquistoShopAjax.php',
	'AcquistoShop\acquistoShopPayment'                        => 'system/modules/acquistoShop/models/acquistoShopPayment.php',
	'AcquistoShop\acquistoShopMessages'                       => 'system/modules/acquistoShop/models/acquistoShopMessages.php',
	'AcquistoShop\acquistoShopMember'                         => 'system/modules/acquistoShop/models/acquistoShopMember.php',
	'AcquistoShop\acquistoShopCosts'                          => 'system/modules/acquistoShop/models/acquistoShopCosts.php',
	'AcquistoShop\acquistoShopOrders'                         => 'system/modules/acquistoShop/models/acquistoShopOrders.php',
	'AcquistoShop\acquistoShopGutschein'                      => 'system/modules/acquistoShop/models/acquistoShopGutschein.php',
	'AcquistoShop\acquistoShopBasket'                         => 'system/modules/acquistoShop/models/acquistoShopBasket.php',
	'AcquistoShop\acquistoShopBackend'                        => 'system/modules/acquistoShop/models/acquistoShopBackend.php',
	'AcquistoShop\acquistoShopNavigation'                     => 'system/modules/acquistoShop/models/acquistoShopNavigation.php',
	'AcquistoShop\acquistoShopProduktLoader'                  => 'system/modules/acquistoShop/models/acquistoShopProduktLoader.php',
	'AcquistoShop\acquistoShopProduktController'              => 'system/modules/acquistoShop/models/acquistoShopProduktController.php',
  'AcquistoShop\acquistoShopProduktList'                    => 'system/modules/acquistoShop/models/acquistoShopProduktList.php',
  'AcquistoShop\acquistoShopVariantenController'            => 'system/modules/acquistoShop/models/acquistoShopVariantenController.php',

	// Modules
	'AcquistoShop\Frontend\ModuleAcquistoProductDetails'      => 'system/modules/acquistoShop/modules/ModuleAcquistoProductDetails.php',
 	'AcquistoShop\Frontend\ModuleAcquistoProductFilter'       => 'system/modules/acquistoShop/modules/ModuleAcquistoProductFilter.php',
	'AcquistoShop\Frontend\ModuleAcquistoBasketWidget'        => 'system/modules/acquistoShop/modules/ModuleAcquistoBasketWidget.php',
	'AcquistoShop\Frontend\ModuleAcquistoProductList'         => 'system/modules/acquistoShop/modules/ModuleAcquistoProductList.php',
	'AcquistoShop\Frontend\ModuleAcquistoRecently'            => 'system/modules/acquistoShop/modules/ModuleAcquistoRecently.php',
	'AcquistoShop\Frontend\ModuleAcquistoTagCloud'            => 'system/modules/acquistoShop/modules/ModuleAcquistoTagCloud.php',
	'AcquistoShop\Frontend\ModuleAcquistoShipping'            => 'system/modules/acquistoShop/modules/ModuleAcquistoShipping.php',
	'AcquistoShop\Frontend\ModuleAcquistoBasket'              => 'system/modules/acquistoShop/modules/ModuleAcquistoBasket.php',
	'AcquistoShop\Frontend\ModuleAcquistoTerms'               => 'system/modules/acquistoShop/modules/ModuleAcquistoTerms.php',
	'AcquistoShop\Frontend\ModuleAcquistoFilterList'          => 'system/modules/acquistoShop/modules/ModuleAcquistoFilterList.php',
	'AcquistoShop\Frontend\ModuleAcquistoCoupon'              => 'system/modules/acquistoShop/modules/ModuleAcquistoCoupon.php',
	'AcquistoShop\Frontend\ModuleAcquistoOrderList'           => 'system/modules/acquistoShop/modules/ModuleAcquistoOrderList.php',
	'AcquistoShop\Frontend\ModuleAcquistoOrderDetails'        => 'system/modules/acquistoShop/modules/ModuleAcquistoOrderDetails.php',
	'AcquistoShop\Frontend\ModuleAcquistoBreadcrumb'          => 'system/modules/acquistoShop/modules/mod_acquistoShop_Breadcrumb.php',
	'AcquistoShop\Frontend\ModuleAcquistoCategories'          => 'system/modules/acquistoShop/modules/ModuleAcquistoCategories.php',
	'AcquistoShop\Frontend\ModuleAcquistoSearch'              => 'system/modules/acquistoShop/modules/ModuleAcquistoSearch.php',
	'AcquistoShop\Frontend\ModuleAcquistoCurrency'            => 'system/modules/acquistoShop/modules/ModuleAcquistoCurrency.php',

	// PaymentModules
	'vorkasse'                           => 'system/modules/acquistoShop/modules/payment/vorkasse.php',
	'nachname'                           => 'system/modules/acquistoShop/modules/payment/nachname.php',
	'rechnung'                           => 'system/modules/acquistoShop/modules/payment/rechnung.php',
	'sofortueberweisung'                 => 'system/modules/acquistoShop/modules/payment/sofortueberweisung.php',
	'paypal'                             => 'system/modules/acquistoShop/modules/payment/paypal.php',
  
  // Helper
  'AcquistoShop\Helper\AcquistoUpdate'                      => 'system/modules/acquistoShop/models/helper/AcquistoUpdate.php',

	// Content Elements
	'ContentProduct'                                          => 'system/modules/acquistoShop/elements/ContentProduct.php'
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_acquisto_agb'                   => 'system/modules/acquistoShop/templates/modules',
	'acquisto_produkt_default'           => 'system/modules/acquistoShop/templates/modules',
	'acquisto_list_default'              => 'system/modules/acquistoShop/templates/modules',
	'be_acquistoshop_portal'             => 'system/modules/acquistoShop/templates/modules',
	'com_default_gravatar'               => 'system/modules/acquistoShop/templates/modules',
	'mod_acquisto_bestelldetails'        => 'system/modules/acquistoShop/templates/modules',
	'mod_acquisto_bestellliste'          => 'system/modules/acquistoShop/templates/modules',
	'mod_acquisto_breadcrumb'            => 'system/modules/acquistoShop/templates/modules',
	'mod_acquisto_gutschein'             => 'system/modules/acquistoShop/templates/modules',
	'mod_acquisto_produktdetails'        => 'system/modules/acquistoShop/templates/modules',
	'mod_acquisto_produktfilter'         => 'system/modules/acquistoShop/templates/modules',
	'mod_acquisto_produktliste'          => 'system/modules/acquistoShop/templates/modules',
	'mod_acquisto_recent'                => 'system/modules/acquistoShop/templates/modules',
	'mod_acquisto_suchergebnis'          => 'system/modules/acquistoShop/templates/modules',
	'mod_acquisto_suchformular'          => 'system/modules/acquistoShop/templates/modules',
	'mod_acquisto_tagcloud'              => 'system/modules/acquistoShop/templates/modules',
	'mod_acquisto_versand'               => 'system/modules/acquistoShop/templates/modules',
	'mod_acquisto_warengruppen'          => 'system/modules/acquistoShop/templates/modules',
	'mod_warenkorb'                      => 'system/modules/acquistoShop/templates/modules',
	'mod_warenkorb_agb'                  => 'system/modules/acquistoShop/templates/modules',
	'mod_warenkorb_check_and_order'      => 'system/modules/acquistoShop/templates/modules',
	'mod_warenkorb_customer'             => 'system/modules/acquistoShop/templates/modules',
	'mod_warenkorb_pay'                  => 'system/modules/acquistoShop/templates/modules',
	'mod_warenkorb_payment_and_shipping' => 'system/modules/acquistoShop/templates/modules',
	'mod_warenkorb_widget'               => 'system/modules/acquistoShop/templates/modules',
	'variant_radio'                      => 'system/modules/acquistoShop/templates/modules',
	'variant_select'                     => 'system/modules/acquistoShop/templates/modules',
	'warengruppen_default'               => 'system/modules/acquistoShop/templates/modules',
	'warengruppen_nav_default'           => 'system/modules/acquistoShop/templates/modules',
  'mod_warenkorb_paypal_revieworder'   => 'system/modules/acquistoShop/templates/modules',
  'mod_warenkorb_access_denied'        => 'system/modules/acquistoShop/templates/modules',
  'mod_acquisto_currency'              => 'system/modules/acquistoShop/templates/modules',

	'warenkorb_email_default'            => 'system/modules/acquistoShop/templates/email',

  'variant_radio'                      => 'system/modules/acquistoShop/templates/forms',
  'variant_select'                     => 'system/modules/acquistoShop/templates/forms',
  
  'ce_productdetails'                  => 'system/modules/acquistoShop/templates/elements'
));

?>
