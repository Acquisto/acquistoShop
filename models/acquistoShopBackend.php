<?php
 
/**
 * The AcquistoShop extension allows the creation a OnlineStore width 
 * the OpenSource Contao CMS. For Question visit our Website under
 * http://www.contao-acquisto.de  
 *
 * PHP version 5
 * @package	   AcquistoShop
 * @subpackage Controller
 * @author     Sascha Brandhoff <brandhoff@contao-acquisto.de>
 * @copyright  AcquistoShop
 * @license    LGPL.
 * @filesource
 */
 
namespace AcquistoShop;  

class acquistoShopBackend extends \Backend
{
    public function exportGoogleBase() {

    }

    public function importData() {
        $objDatabase = $this->Database->prepare("SELECT * FROM tl_shop_import WHERE id=?")->limit(1)->execute($this->Input->Get('id'));
        include_once(TL_ROOT . '/system/modules/acquistoShop/modules/import/' . $objDatabase->importModule . '.php');
        $objModule = new $objDatabase->importModule();
        return $objModule->getImportForm();

    }
}
