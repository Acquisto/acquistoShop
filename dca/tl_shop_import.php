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
 * Table tl_shop_import
 */
$GLOBALS['TL_DCA']['tl_shop_import'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ctable'                      => array('tl_shop_import_log'),
        'enableVersioning'            => true,
        'switchToEdit'                => true,
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
            'fields'                  => array('bezeichnung'),
            'flag'                    => 1,
            'panelLayout'             => 'search,limit'
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
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_import']['edit'],
                'href'                => 'table=tl_shop_import_log',
                'icon'                => 'edit.gif'
            ),
            'import' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_import']['edit'],
                'href'                => 'key=importData',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_import']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_import']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_import']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{title_legend},bezeichnung,importModule;',
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
        'bezeichnung' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_import']['bezeichnung'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
    			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'importModule' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_export']['exportModule'],
            'inputType'               => 'select',
            'search'                  => false,
            'options_callback'        => array('tl_shop_import', 'getImortModules'),
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50', 'includeBlankOption'=>true, 'submitOnChange'=>true),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        )
    )
);

class tl_shop_import extends Backend
{
    public function __construct() {
        parent::__construct();
        $this->Import('BackendUser', 'User');
        $this->Import('acquistoShop');
    }

    public function getImortModules() {
        return $this->acquistoShop->getImportModules();
    }

    public function getConfig(DataContainer $DC) {
        /**
         * Speicherung des Feldes
         **/
        if($this->Input->post('FORM_SUBMIT') == 'tl_shop_export') {
            $this->Database->prepare("UPDATE tl_shop_export SET exportSettings = '" . serialize($this->Input->Post('exportSettings')) . "' WHERE id = ?")->execute($DC->id);
        }


        $objExport = $this->Database->prepare("SELECT * FROM tl_shop_export WHERE id = ?")->limit(1)->execute($DC->id);
        if($objExport->exportModule) {

            if(file_exists(TL_ROOT . '/system/modules/acquistoShop/modules/export/' . $objExport->exportModule . '.php')) {
                include_once(TL_ROOT . '/system/modules/acquistoShop/modules/export/' . $objExport->exportModule . '.php');
                $exportModule = new $objExport->exportModule();
            }



            $arrConfig = unserialize($objExport->exportSettings);
            if(is_array($exportModule->getSettingsData())) {
                foreach($exportModule->getSettingsData() as $name => $element) {
                    $objElement = $GLOBALS['BE_FFL'][$element['inputType']];
                    $objElement = new $objElement($this->prepareForWidget($element, 'exportSettings[' . $name . ']'));
                    $objElement->value = $arrConfig[$name];
                    $htmlData .= sprintf('<h3><label for="%s">%s</label></h3>', 'exportSettings[' . $name . ']', $element['label'][0]);
                    $htmlData .= $objElement->generate();
                    $htmlData .= sprintf('<p class="tl_help">%s</p>', $element['label'][1]);
                }
            } else {
                $htmlData = '<h3>Keine Konfigurationparameter</h3><p class="tl_help">Entweder wurd keine Zahlungsart ausgew&auml;hlt oder es gibt keine Konfigurationwerte.</p>';
            }

            return $htmlData;
        }
    }

    public function exportData() {
        if($this->Input->Get('id')) {
            $objExport = $this->Database->prepare("SELECT * FROM tl_shop_export WHERE id = ?")->limit(1)->execute($this->Input->Get('id'));

            if(file_exists(TL_ROOT . '/system/modules/acquistoShop/modules/export/' . $objExport->exportModule . '.php')) {
                require_once(TL_ROOT . '/system/modules/acquistoShop/modules/export/' . $objExport->exportModule . '.php');
                $objModule = new $objExport->exportModule();
                $objModule->exportID = $this->Input->Get('id');

                $objFile = new File($objExport->exportFile . '.xml');
                $objFile->write('');
                $objFile->append($objModule->compile());
                $objFile->close();
            }
        }

        $this->redirect(ampersand(str_replace('&key=export&id=' . $this->Input->Get('id'), '', $this->Environment->request)));
    }
}

?>