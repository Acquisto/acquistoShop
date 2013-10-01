<?php 

/**
 * The AcquistoShop extension allows to create an online 
 * store with the OpenSource Contao CMS. If you have
 * questions our website: http://www.contao-acquisto.de  
 *
 * PHP version 5
 * @package	   AcquistoShop
 * @subpackage Product
 * @author     Sascha Brandhoff <brandhoff@contao-acquisto.de>
 * @copyright  AcquistoShop
 * @license    LGPL.
 * @filesource
 */
 
namespace AcquistoShop\Product;

class acquistoPClassDigital extends \acquistoShopProduktController
{
    var $produkt_id       = 0;
    var $produktArray     = array();
    var $default_image    = '';
    var $image_properties = array();
    var $strTemplate      = 'acquisto_produkt_default';
    var $hasAttributes    = false;
    private $attribute    = null;

    public function __construct($id, $attributes = null)
    {
        parent::__construct();
        $this->Import('Database');

        $this->intId         = $id;
        $this->arrAttributes = unserialize(str_replace("'", "\"", $attributes));

        #$this->produkt_id = $id;
        #$this->produkt_attributes = unserialize(str_replace("'", "\"", $attributes));

        $objLoad = $this->Database->prepare("SELECT * FROM tl_shop_produkte WHERE id = ?")->limit(1)->execute($this->intId);
        $arrLoad = $objLoad->row();

        if(is_array($arrLoad)) {
            foreach($arrLoad as $key => $value) {
                $this->__set($key, $value);
            }
        }

        $this->buildCss();

        switch($this->type) {
            case 'digital':
                $this->sortCosts();
                break;;
        }

        if($this->template) {
            $this->strTemplate = $this->template;
        }

	if($this->mengeneinheit) {
	    $objMengeneinheit = $this->Database->prepare("SELECT * FROM tl_shop_mengeneinheit WHERE id=?")->execute($this->mengeneinheit);
	    $this->mengeneinheit = $objMengeneinheit->einheit;
	}
    }

    public function __set($name, $value) {
        switch($name) {
            case "url":
                $this->formatUrl($value);
                break;;
            case "default_image":
                if(!$this->preview_image) {
                    $this->preview_image = $value;
                }
                break;;
            default:
                $this->$name = $value;
                break;;
        }
    }

    #public function __get($name) {
    #    return $this->$name;
    #}

    private function formatUrl($url) {
        $this->url = sprintf($url, $this->alias);
    }
}

?>