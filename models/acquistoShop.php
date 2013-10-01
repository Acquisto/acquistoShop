<?php 

/**
 * The AcquistoShop extension allows to create an online 
 * store with the OpenSource Contao CMS. If you have
 * questions our website: http://www.contao-acquisto.de  
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

class acquistoShop extends \Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->Import('Database');
    }

    public function getExportModules() {
        if ($handle = opendir(TL_ROOT . '/system/modules/acquistoShop/modules/export/'))
        {
            while (false !== ($file = readdir($handle)))
            {
                if ($file != "." && $file != ".." && is_file(TL_ROOT . '/system/modules/acquistoShop/modules/export/' . $file))
                {
                    require_once(TL_ROOT . '/system/modules/acquistoShop/modules/export/' . $file);
                    $strModule = substr($file, 0, -4);
                    $objModule = new $strModule();
                    $arrModules[$strModule] = $objModule->getExportInfo();
                }
            }

            closedir($handle);
        }

        return $arrModules;
    }

    public function getImportModules() {
        if ($handle = opendir(TL_ROOT . '/system/modules/acquistoShop/modules/import/'))
        {
            while (false !== ($file = readdir($handle)))
            {
                if ($file != "." && $file != ".." && is_file(TL_ROOT . '/system/modules/acquistoShop/modules/import/' . $file))
                {
                    require_once(TL_ROOT . '/system/modules/acquistoShop/modules/import/' . $file);
                    $strModule = substr($file, 0, -4);
                    $objModule = new $strModule();
                    $arrModules[$strModule] = $objModule->getImportInfo();
                }
            }

            closedir($handle);
        }

        return $arrModules;
    }

    /**
     * Ermittelt Grundpreis anhand eines Produkts anhand der Menge
     **/
    public function getProductGrundpreis($id, $menge) {
        $objProdukt = $this->Database->prepare("SELECT id, inhalt, berechnungsmenge, mengeneinheit FROM tl_shop_produkte WHERE id = ?")
                           ->limit(1)
                           ->execute($id);
        $objEinheit = $this->Database->prepare("SELECT * FROM tl_shop_mengeneinheit WHERE id = ?")
                           ->limit(1)
                           ->execute($objProdukt->mengeneinheit);

        $dblPreis   = $this->getProductPrice($objProdukt->id, $menge);

        if($objProdukt->inhalt && $objProdukt->berechnungsmenge && $dblPreis) {
            $dblGrundpreis = ($dblPreis / $objProdukt->inhalt) * $objProdukt->berechnungsmenge;

            return array(
                'preis'            => sprintf("%01.2f", $dblPreis),
                'grundpreis'       => sprintf("%01.2f", $dblGrundpreis),
                'inhalt'           => $objProdukt->inhalt,
                'berechnungsmenge' => $objProdukt->berechnungsmenge,
                'mengeneinheit'    => $objEinheit->einheit
            );
        }
    }

    /**
     * Ermittelt Produktpreis anhand der Menge
     **/
    public function getProductPrice($id, $menge) {
        $objProdukt = $this->Database->prepare("SELECT type, preise FROM tl_shop_produkte WHERE id = ?")
                           ->limit(1)
                           ->execute($id);

        if(is_array($arrPreise = unserialize($objProdukt->preise))) {
            if(count($arrPreise) == 1) {
                $endpreis = $arrPreise[0]['label'];
            } else {
                foreach($arrPreise as $value) {
                    if($menge >= $value['value']) {
                        $endpreis = $value['label'];
                    }
                }
            }
        }

        return sprintf("%01.2f", $endpreis);
    }

    public function checkGroupPermissions($member_group, $groups = array()) {

    }

    public function generateOrderID($orderNumber) {
        $orderID = $GLOBALS['TL_CONFIG']['rechnungsformat'];
        $orderID = preg_replace("/\[Y]/", date("Y"), $orderID);
        $orderID = preg_replace("/\[y]/", date("y"), $orderID);
        $orderID = preg_replace("/\[m]/", date("m"), $orderID);
        $orderID = preg_replace("/\[d]/", date("d"), $orderID);

        preg_match("/\[C(.*)]/", $orderID, $arrMatch);
        $orderID = preg_replace("/\[C(.*)]/", sprintf("%0" . $arrMatch[1] . "d", $orderNumber), $orderID);

        return $orderID;
    }

    public function getInsertTagPID() {
        switch(VERSION) {
            case "2.10":
                return "{{env::page_id}}";
                break;;
            case "2.11":
                return "{{page::id}}";
                break;;
            default:
                return "{{page::id}}";
                break;;
        }
    }
    
    static function reload($url = null) 
    {
        if(!$url)
        {
            $url = substr(\Environment::get('requestUri'), 1);
        }
        
        \Controller::redirect($url);
    }
}

?>