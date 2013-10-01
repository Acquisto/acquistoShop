<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 *
 * The TYPOlight webCMS is an accessible web content management system that
 * specializes in accessibility and generates W3C-compliant HTML code. It
 * provides a wide range of functionality to develop professional websites
 * including a built-in search engine, form generator, file and user manager,
 * CSS engine, multi-language support and many more. For more information and
 * additional TYPOlight applications like the TYPOlight MVC Framework please
 * visit the project website http://www.typolight.org.
 *
 * English language file for table tl_cds.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2007
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Language
 * @license    GPL
 * @filesource
 */


/**
 * Headlines
 **/

$GLOBALS['TL_LANG']['tl_shop_orders']['title_legend'] = 'Allgemein';
$GLOBALS['TL_LANG']['tl_shop_orders']['customer']     = 'Rechnungsadresse';
$GLOBALS['TL_LANG']['tl_shop_orders']['deliver']      = 'Versandadresse';

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_shop_orders']['order_id']        = array('Bestellnummer', '');
$GLOBALS['TL_LANG']['tl_shop_orders']['versandzonen_id'] = array('Versandzone', '');
$GLOBALS['TL_LANG']['tl_shop_orders']['zahlungsart_id']  = array('Zahlungsart', '');
$GLOBALS['TL_LANG']['tl_shop_orders']['payed']           = array('Bezahlt', '');
$GLOBALS['TL_LANG']['tl_shop_orders']['calculate_tax']   = array('Steuern berechnen', 'W&auml;hlen Sie ob die Steuer in diees Land berechnet werden soll.');

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_shop_orders']['edit']   = array('Bearbeiten', 'Bestellung ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_shop_orders']['delete'] = array('L&ouml;schen', 'Bestellung ID %s l&ouml;schen');
$GLOBALS['TL_LANG']['tl_shop_orders']['show']   = array('Details', 'Details der Bestellung ID %s anzeigen');

?>