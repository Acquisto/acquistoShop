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

class acquistoShopGutschein extends \Controller {
    public function __construct()
    {
        parent::__construct();
        $this->Import('Database');
    }

    public function validateGutschein($id, $member_id = 0) {
        $objGutschein = $this->Database->prepare("SELECT * FROM tl_shop_gutscheine WHERE id = ?")->execute($id);

        $boolUseable = true;
        if($objGutschein->kunden_id) {
            if($objGutschein->kunden_id != $member_id) {
                $boolUseable = false;
            }
        }

        if($objGutschein->zeitgrenze) {
            if($objGutschein->gueltig_von > time() OR $objGutschein->gueltig_bis < time()) {
                $boolUseable = false;
            }
        }

        if($objGutschein->using_counter) {
            if($objGutschein->max_using <= $objGutschein->is_used) {
                $boolUseable = false;
            }
        }

        if(!$objGutschein->using_counter && $objGutschein->is_used) {
            $boolUseable = false;
        }

        if(!$objGutschein->using_counter && $objGutschein->is_used) {
            $boolUseable = false;
        }

        if(!$objGutschein->aktiv) {
            $boolUseable = false;
        }

        return $boolUseable;
    }

    public function addGutschein2Use($id) {
        $_SESSION['acquistoGutscheine'][$id] = $id;
    }

    public function removeGutschein($id) {
        unset($_SESSION['acquistoGutscheine'][$id]);
    }

    public function getGutschein($id) {
        $objGutschein = $this->Database->prepare("SELECT * FROM tl_shop_gutscheine WHERE id = ?")->limit(1)->execute($id);
        $objObject = new stdClass();
        $objObject = (object) $objGutschein->row();
        $objObject->gueltig_von = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objObject->gueltig_von);
        $objObject->gueltig_bis = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objObject->gueltig_bis);
        $objObject->preis       = sprintf("%01.2f", $objObject->preis);

        return $objObject;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function getList() {
        if(is_array($_SESSION['acquistoGutscheine'])) {
            foreach($_SESSION['acquistoGutscheine'] as $id) {
                $arrGutscheine[] = $this->getGutschein($id);
            }
        }

        return $arrGutscheine;
    }

    public function Checkout($member_id) {
        if(is_array($_SESSION['acquistoGutscheine'])) {
            foreach($_SESSION['acquistoGutscheine'] as $id) {
                if($this->validateGutschein($id, $member_id)) {
                    $this->Database->prepare("UPDATE tl_shop_gutscheine SET is_used = (is_used + 1) WHERE id = ?")->execute($id);
                    $arrReturn[] = $objGutschein = $this->getGutschein($id);

                }

                $this->removeGutschein($id);
            }

            return $arrReturn;
        }
    }
}

?>