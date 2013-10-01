<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Calendar
 * @license    LGPL
 * @filesource
 */


/**
 * Back end modules
 */
$GLOBALS['TL_LANG']['MOD']['acquisto']                            = 'Acquisto';
$GLOBALS['TL_LANG']['MOD']['acquistoShopHersteller']              = array('Hersteller', 'Hersteller verwalten um diese Produkten zuzuordnen.');
$GLOBALS['TL_LANG']['MOD']['acquistoShopProdukte']                = array('Produkte', 'Produkte f&uuml; acquistoShop verwalten.');
$GLOBALS['TL_LANG']['MOD']['acquistoShopWarengruppen']            = array('Warengruppen', 'Warengruppen anlegen und verwalten um Produkte in diese einzuordenen.');
$GLOBALS['TL_LANG']['MOD']['acquistoShopGutscheine']              = array('Gutscheine', 'Gutscheine anlegen und verwalten.');

$GLOBALS['TL_LANG']['MOD']['acquisto_Orders']                     = 'Acquisto Bestellungen';
$GLOBALS['TL_LANG']['MOD']['acquistoShopOrders']                  = array('Bestellungen', 'Eingegangene Bestellungen verwalten.');

$GLOBALS['TL_LANG']['MOD']['acquisto_Settings']                   = 'Acquisto Einstellungen';
$GLOBALS['TL_LANG']['MOD']['acquistoShopSteuern']                 = array('Steuern', 'Hier verwalten Sie Steuertypen und Steuers&auml;tze des acquistoShop.');
$GLOBALS['TL_LANG']['MOD']['acquistoShopEinstellungen']           = array('Einstellungen', 'Basiseinstellungen f&uuml;r den acquistoShop.');
$GLOBALS['TL_LANG']['MOD']['acquistoShopShippingZones']           = array('Versandzonen', 'Versandzonen und Versandpreise f&uuml;r verwalten.');
$GLOBALS['TL_LANG']['MOD']['acquistoShopDispatcher']              = array('Versandarten', 'Hier k&ouml;nnen Sie die Versandarten verwalten.');
$GLOBALS['TL_LANG']['MOD']['acquistoShopZahlungsarten']           = array('Zahlungsarten', 'Zahlungsarten f&uuml;r Warenkorb verwalten.');
$GLOBALS['TL_LANG']['MOD']['acquistoShopMengeneinheit']           = array('Mengeneinheiten', 'Mengeneinheiten f&uuml;r Grundpreisberechnung');
$GLOBALS['TL_LANG']['MOD']['acquistoShopAttribute']               = array('Attribute', 'Attributklassen f&uuml;r Produkte verwalten.');
$GLOBALS['TL_LANG']['MOD']['acquistoShopPricelists']              = array('Preislisten', 'Preislisten f&uuml;r Acquisto anlegen und verwalten.');
$GLOBALS['TL_LANG']['MOD']['acquistoShopExport']                  = array('Export-Module', 'Export-Module f&uuml;r Preissuchmaschinen anlegen.');
$GLOBALS['TL_LANG']['MOD']['acquistoShopImport']                  = array('Import-Module', 'Import-Module f&uuml;r das Importieren anlegen.');
$GLOBALS['TL_LANG']['MOD']['acquistoShopPortal']                  = array('Partner-Portal', 'Das Partner-Portal versorgt Sie mit aktuellen Informationen rund um Acquisto und die eCommerce Branche.');
$GLOBALS['TL_LANG']['MOD']['acquistoShopCurrency']                = array('W&auml;hrungen', 'Erstellen Sie W&auml;hrungen f&uuml;r Preislisten.');

/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['acquisto']                            = 'Acquisto';
$GLOBALS['TL_LANG']['FMD']['acquisto_widget']                     = 'Acquisto Widgets';

$GLOBALS['TL_LANG']['FMD']['ModuleAcquistoProductDetails']        = array('Produktdetails', '');
$GLOBALS['TL_LANG']['FMD']['ModuleAcquistoProductList']           = array('Produktliste', '');
$GLOBALS['TL_LANG']['FMD']['ModuleAcquistoCurrency']              = array('W&auml;hrungswechsel');
$GLOBALS['TL_LANG']['FMD']['ModuleAcquistoSearch']                = array('Suchformular', '');
$GLOBALS['TL_LANG']['FMD']['ModuleAcquistoBasket']                = array('Warenkorb', '');
$GLOBALS['TL_LANG']['FMD']['ModuleAcquistoBasketWidget']          = array('Warenkorb Widget', '');
$GLOBALS['TL_LANG']['FMD']['ModuleAcquistoCategories']            = array('Warengruppen', '');
$GLOBALS['TL_LANG']['FMD']['ModuleAcquistoTerms']                 = array('Allgemeine Gesch&auml;ftsbedingungen', '');
$GLOBALS['TL_LANG']['FMD']['ModuleAcquistoBreadcrumb']            = array('Breadcrumb', '');
$GLOBALS['TL_LANG']['FMD']['ModuleAcquistoTagCloud']              = array('TagCloud', '');
$GLOBALS['TL_LANG']['FMD']['ModuleAcquistoShipping']              = array('Versand und Zahlung', '');
$GLOBALS['TL_LANG']['FMD']['ModuleAcquistoRecently']              = array('Zuletzt angeschaut', '');
$GLOBALS['TL_LANG']['FMD']['ModuleAcquistoCoupon']                = array('Gutschein', '');
$GLOBALS['TL_LANG']['FMD']['ModuleAcquistoProductFilter']         = array('Produktfilter', '');
$GLOBALS['TL_LANG']['FMD']['ModuleAcquistoFilterList']            = array('Filterliste', '');
$GLOBALS['TL_LANG']['FMD']['ModuleAcquistoOrderDetails']          = array('Bestelldetails', '');
$GLOBALS['TL_LANG']['FMD']['ModuleAcquistoOrderList']             = array('Bestellliste', '');
                      

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['list_settings']                    = 'Listeneinstellungen';
$GLOBALS['TL_LANG']['tl_module']['deliver']                          = 'Alternative Lieferadresse';
$GLOBALS['TL_LANG']['tl_module']['fields']                           = 'Kundenfelder';
$GLOBALS['TL_LANG']['tl_module']['list_images']                      = 'Bildeinstellungen (Liste)';
$GLOBALS['TL_LANG']['tl_module']['categorie_image']                  = 'Bildeinstellungen (Kategorie)';
$GLOBALS['TL_LANG']['tl_module']['contaoShop_image']                 = 'Bildeinstellungen';
$GLOBALS['TL_LANG']['tl_module']['galerie_options']                  = 'Galerie-Einstellungen';
$GLOBALS['TL_LANG']['tl_module']['addon_legend']                     = 'Zustazfelder';

$GLOBALS['TL_LANG']['tl_module']['contaoShop_hardLimit']             = array('Hardlimit', '');
$GLOBALS['TL_LANG']['tl_module']['contaoShop_jumpTo']                = array('Weiterleitungsziel','');
$GLOBALS['TL_LANG']['tl_module']['contaoShop_imageSrc']              = array('Standardbild','');
$GLOBALS['TL_LANG']['tl_module']['contaoShop_numberOfItems']         = array('Gesamtzahl der Produkte','Hier k&ouml;nnen Sie die Gesamtzahl der Beitr&auml;ge festlegen.');
$GLOBALS['TL_LANG']['tl_module']['contaoShop_imageFullsize']         = array('Gro&szlig;ansicht/Neues Fenster', 'Gro&szlig;ansicht des Bildes in einer Lightbox bzw. den Link in einem neuen Browserfenster &ouml;ffnen.');
$GLOBALS['TL_LANG']['tl_module']['contaoShop_Template']              = array('Template','W&auml;hlen Sie ein Template aus.');
$GLOBALS['TL_LANG']['tl_module']['contaoShop_levelOffset']           = array('Startlevel', '');
$GLOBALS['TL_LANG']['tl_module']['contaoShop_showLevel']             = array('Stoplevel', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_elementsPerRow']      = array('Elemente pro Zeile', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_tags']                = array('Tags', 'Sie m&uuml;ssen die Tags komma getrennt eingeben. Bsp. Tag 1, Tag 2, Tag 3');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_zustand']             = array('Produktzustand', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_hersteller']          = array('Hersteller', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_produkttype']         = array('Produkttyp', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_markedProducts']      = array('Hervorgehobene', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_warengruppe']         = array('Warengruppe', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_listTemplate']        = array('Auflistungstemplate', 'W&auml;hlen Sie das Auflistungstemplate aus.');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_selFields']           = array('Anzeigefelder', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_selFields_field']     = array('Benutzerfeld', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_selFields_mandatory'] = array('Pflichtfeld', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_selFields_info']      = array('Infotext', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_selFields_error']     = array('Fehlermeldung', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_selDeliver']          = array('Anzeigefelder', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_basketType']          = array('Warenkorb-Typ', 'W&auml;hlen Sie hier ob der Warenkorb Brutto, Netto oder die Anzeige durch die Preisliste definiert wird.');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_guestOrder']          = array('G&auml;stebestellung', 'Definieren Sie ob G&auml;ste im Shop bestellen k&ouml;nnen.');



$GLOBALS['TL_LANG']['tl_module']['acquistoShop_fieldsAddon']            = array('Zusatzfelder', 'Definieren Sie hier Zusatzfelder die bei der Eingabe der Kundendaten abgefragt werden. Wenn Sie ein Select-, Checkbox- oder Optionfeld w&auml;hlen geben Sie die Feldoptionen wie folgt ein: key=value,key1=value1 usw...');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_fieldsAddon_fieldname']  = array('Feldname', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_fieldsAddon_fieldtitle'] = array('Bezeichnung', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_fieldsAddon_fieldtype']  = array('Feldtype', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_fieldsAddon_value']      = array('Wert', '');

$GLOBALS['TL_LANG']['tl_module']['acquistoShop_fieldsAddon_fieldtype_opt_text']     = array('Text', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_fieldsAddon_fieldtype_opt_select']   = array('Select', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_fieldsAddon_fieldtype_opt_checkbox'] = array('Checkbox', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_fieldsAddon_fieldtype_opt_radio']    = array('Radio', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_fieldsAddon_fieldtype_opt_textarea'] = array('Textarea', '');

$GLOBALS['TL_LANG']['tl_module']['acquistoShop_Searchfields']     = array('Suchfelder', 'Hier definieren Sie die Felder in dennen nach dem Suchbegeriff gesucht wird.');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_excludeTags']      = array('Tags ausnehmen', 'Geben Sie hier eine Komma getrennte Liste von Tags ein die nicht angezeigt werden sollen.');


$GLOBALS['TL_LANG']['tl_module']['socialmedia']                   = 'SocialMedia';
$GLOBALS['TL_LANG']['tl_module']['contaoShop_socialFacebook']     = array('Facebook Gef&auml;llt mir Button', '');
$GLOBALS['TL_LANG']['tl_module']['contaoShop_socialTwitter']      = array('Tweet This - Button', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_emailTemplate']    = array('E-Mail Template f&uuml;r K&auml;ufer', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_emailTemplate_seller']    = array('E-Mail Template f&uuml;r Verk&auml;ufer', '');

$GLOBALS['TL_LANG']['tl_module']['acquistoShop_emailTyp']         = array('E-Mail Typ', '');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_AGBFile']          = array('AGB Datei', '');

/**
 * Subplattes Fields
 */
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_allowComments']    = array('Kommentare aktivieren', 'Besuchern das Kommentieren von Nachrichtenbeitr&auml;gen erlauben.');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_commentsPerPage']  = array('Kommentare pro Seite', 'Anzahl an Kommentaren pro Seite. Geben Sie 0 ein, um den automatischen Seitenumbruch zu deaktivieren.');
$GLOBALS['TL_LANG']['tl_module']['acquistoShop_commentsNotify']   = array('Benachrichtigung an', 'Bitte legen Sie fest, wer beim Hinzuf&uuml;gen neuer Kommentare benachrichtigt wird.');

?>