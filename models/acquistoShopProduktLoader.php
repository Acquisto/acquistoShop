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

class acquistoShopProduktLoader extends \Controller {
    var $Produkt;

    public function __construct() {
        parent::__construct();
        $this->Import('Database');
    }

    public function load($id, $attributes = null) {
        $objProdukt = $this->Database->prepare("SELECT id, type FROM tl_shop_produkte WHERE id=?")->execute($id);
        if($GLOBALS['ACQUISTO_PCLASS'][$objProdukt->type][0]) 
        {
            $this->Produkt = new $GLOBALS['ACQUISTO_PCLASS'][$objProdukt->type][0]($id, $attributes);
        }

        /** Hook **/
        if (isset($GLOBALS['TL_HOOKS']['modifyProduct']) && is_array($GLOBALS['TL_HOOKS']['modifyProduct'])) {
            foreach ($GLOBALS['TL_HOOKS']['modifyProduct'] as $callback) {
						    $this->import($callback[0]);
                $this->Produkt = $this->$callback[0]->$callback[1]($this->Produkt);
            }
        }

        return $this->Produkt;
    }
}

?>