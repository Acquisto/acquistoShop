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

$GLOBALS['TL_LANG']['tl_shop_gutscheine']['allgemein']     = 'Allgemein';
$GLOBALS['TL_LANG']['tl_shop_gutscheine']['config_legend'] = 'Experten-Einstellungen';
$GLOBALS['TL_LANG']['tl_shop_gutscheine']['state']         = 'Status';

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_shop_gutscheine']['code']          = array('Code', 'Geben Sie bitte den Gutscheincode ein.');
$GLOBALS['TL_LANG']['tl_shop_gutscheine']['preis']         = array('Preis', 'Geben Sie bitte den Wert des Gutscheins ein.');
$GLOBALS['TL_LANG']['tl_shop_gutscheine']['kunden_id']     = array('Kunde', 'Hier k&ouml;nnen Sie den Gutschein an einen Kunden binden.');
$GLOBALS['TL_LANG']['tl_shop_gutscheine']['gueltig_von']   = array('G&uuml;ltig von', 'W&auml;hlen Sie ein Datum aus ab dem der Gutschein g&uuml;ltig ist.');
$GLOBALS['TL_LANG']['tl_shop_gutscheine']['gueltig_bis']   = array('G&uuml;ltig bis', 'W&auml;hlen Sie ein Datum aus ab dem der Gutschein nicht mehr g&uuml;ltig ist.');
$GLOBALS['TL_LANG']['tl_shop_gutscheine']['aktiv']         = array('Aktiv', '');
$GLOBALS['TL_LANG']['tl_shop_gutscheine']['zeitgrenze']    = array('Zeitlich begrenzen', 'Hier k&ouml;nnen Sie den G&uuml;ltigkeitszeitraum begrenzen.');
$GLOBALS['TL_LANG']['tl_shop_gutscheine']['using_counter'] = array('Anzahl limitieren', 'Hier k&ouml;nnen Sie die Nutzung des Gutscheins limitieren.');
$GLOBALS['TL_LANG']['tl_shop_gutscheine']['max_using']     = array('Nutzung maximal', 'Geben Sie bitte ein wie oft der Gutschein genutzt werden darf');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_shop_gutscheine']['new']    = array('Neuen Gutschein', 'Einen neuen Gutschein erstellen');
$GLOBALS['TL_LANG']['tl_shop_gutscheine']['edit']   = array('Bearbeiten', 'Gutschein ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_shop_gutscheine']['copy']   = array('Duplizieren', 'Gutschein ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_shop_gutscheine']['delete'] = array('L&ouml;schen', 'Gutschein ID %s l&ouml;schen');
$GLOBALS['TL_LANG']['tl_shop_gutscheine']['show']   = array('Details', 'Die Details des Gutschein ID %s anzeigen');


?>