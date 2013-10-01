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

class acquistoPClassVariant extends \acquistoShopProduktController
{
    var $produkt_id          = 0;
    var $produktArray        = array();
    var $default_image       = '';
    var $image_properties    = array();
    var $strTemplate         = 'acquisto_produkt_default';
    public $attribute_list   = array();
    var $hasAttributes       = true;
    private $attribute       = null;




    public $attributeList  = array();

    public function __construct($id, $additional = null)
    {
        parent::__construct();
        #print_r($attributes);
        $this->Import('Database');

        $this->intId         = $id;
        $this->arrAttributes = $attributes;

        $this->produkt_id = $id;
        $this->produkt_attributes = $additional;
        $this->loadProdukt();
        $this->filterArray();

//        print_r($additional);
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

    public function loadProdukt() {
        $objLoad = $this->Database->prepare("SELECT * FROM tl_shop_produkte WHERE id = ?")->limit(1)->execute($this->intId);
        $arrLoad = $objLoad->row();

        if(is_array($arrLoad)) {
            foreach($arrLoad as $key => $value) {
                $this->__set($key, $value);
            }
        }

        $this->buildCss();

        #$this->varianten = $this->buildVariant($this->intId, 0, $this->arrAttributes);
        $this->add2form = $this->loadAttriutes();
        $this->attribute = str_replace("\"", "'", serialize($this->arrAttributes));
        $this->sortCosts();
    }

    private function loadAttriutes($previous = 0) 
    {        
        $objAttributes = $this->Database->prepare("SELECT tl_shop_attribute.id AS definedId, tl_shop_attribute.feldtyp, tl_shop_attribute.bezeichnung, tl_shop_produkte_attribute.id AS selectedId FROM tl_shop_attribute, tl_shop_produkte_attribute WHERE tl_shop_produkte_attribute.pid=? && tl_shop_produkte_attribute.pattribut_id=? && tl_shop_produkte_attribute.attribut_id = tl_shop_attribute.id ORDER BY tl_shop_produkte_attribute.sorting ASC")->execute($this->id, $previous);
    
        while($objAttributes->next()) 
        {

            $objItem = (object) $objAttributes->row();

            if($this->isDefinedAttribute($objItem->definedId)) 
            {
                $objItem->attributeItems = $this->getDefinedAttributeItems($objItem->definedId);
            } 
            else 
            {
                $objItem->attributeItems = $this->getAttributeItems($objItem->selectedId);
            }


            if($objItem->feldtyp && count($objItem->attributeItems)) 
            {
                $objTemplate = new \FrontendTemplate('variant_' . $objItem->feldtyp);
                $objTemplate->ID = $objItem->id;
                $objTemplate->Bezeichnung = $objItem->bezeichnung;
                $objTemplate->Options = $objItem->attributeItems;

                $html .= $objTemplate->parse();
            }

            $this->attributeList[] = $objItem;
        }

        return $html;
    }

    /**
     * Prüft ob das Attribut vordefiniert ist.
     **/
    private function isDefinedAttribute($attributeID) {
          $attributeCount = $this->Database->prepare("SELECT COUNT(*) AS numRows FROM tl_shop_attribute_values WHERE pid=?")->limit(1)->execute($attributeID);
          $attributeCount = (object) $attributeCount->row();

          $returnValue = false;
          if($attributeCount->numRows) {
              $returnValue = true;
          }

          return $returnValue;
    }

    /**
     * Gibt die vordefinierten Varianten eines Attributes als Array zurück
     **/
    private function getDefinedAttributeItems($attributeID) {
          $attributeItems = $this->Database->prepare("SELECT * FROM tl_shop_attribute_values WHERE pid=? ORDER BY sorting ASC")->execute($attributeID);

          while($attributeItems->next()) {
              $objItem = (object) $attributeItems->row();


              if($objItem->type == 'counter') {
                  $ausdruck = "%01." . $objItem->dezimal . "f";
                  if(substr_count($objItem->bezeichnung, '%s')) {
                      $ausdruck = sprintf($objItem->bezeichnung, $ausdruck);
                  }

                  for($nI = $objItem->from_value; $nI < $objItem->to_value; $nI = $nI + $objItem->steps_value) {
//                       #echo sprintf($ausdruck, $nI);
//                       $objItem->bezeichnung = sprintf($ausdruck, $nI);
//                       $objItems[] = $objItem;
                  }
              } else {
                  if($this->produkt_attributes[$attributeID] == $objItem->id) {
                      $objItem->selected = true;
                  }

                  $objItems[] = $objItem;
              }

          }

          return $objItems;
    }

    /**
     * Gibt die _nicht_ vordefinierten Varianten eines Attributes als Array zurück
     **/
    private function getAttributeItems($attributeID) {
          $attributeItems = $this->Database->prepare("SELECT * FROM tl_shop_produkte_varianten WHERE pid=? ORDER BY sorting ASC")->execute($attributeID);          

          while($attributeItems->next()) {
              $objItem = (object) $attributeItems->row();
              
              if($this->produkt_attributes[$attributeID] == $objItem->id) {                  
                  $objItem->selected = true;
              }

              $objItems[] = $objItem;
          }
          
          return $objItems;
    }

    private function formatUrl($url) {
        $this->url = sprintf($url, $this->alias);
    }
// 
//     private function attributeItems($attributeID) {
// 
//     }
// 
//     private function buildVariant($pid, $pattribut_id = 0, $filter = null) {
//         $objProdukt_Attrib = $this->Database->prepare("SELECT * FROM tl_shop_produkte_attribute WHERE pid=? && pattribut_id=?;")->execute($pid, $pattribut_id);
//         $objAttribute      = $this->Database->prepare("SELECT * FROM tl_shop_attribute WHERE id=?")->limit(1)->execute($objProdukt_Attrib->attribut_id);
//         $objBefore         = $this->Database->prepare("SELECT * FROM tl_shop_produkte_attribute WHERE id=?;")->execute($objProdukt_Attrib->id);
//         $objVarianten      = $this->Database->prepare("SELECT * FROM tl_shop_produkte_varianten WHERE (pid=? && produkt_attribut_id=0) OR (pid=? && produkt_attribut_id=?)")->execute($objProdukt_Attrib->id, $objProdukt_Attrib->id, $filter[$objBefore->pattribut_id]);
//         $objSumme      = $this->Database->prepare("SELECT COUNT(*) AS numRows FROM tl_shop_produkte_varianten WHERE (pid=? && produkt_attribut_id=0) OR (pid=? && produkt_attribut_id=?)")->execute($objProdukt_Attrib->id, $objProdukt_Attrib->id, $filter[$objBefore->pattribut_id]);
// 
//         while($objVarianten->next()) {
//             $objItem = (object) $objVarianten->row();
// 
//             if(isset($filter[$objProdukt_Attrib->id])) {
//                 if($filter[$objProdukt_Attrib->id] == $objVarianten->id) {
//                     $objItem->selected = true;
//                     $arrPreise = unserialize($objVarianten->preise);
//                     $filter[$objProdukt_Attrib->id] = $objItem->id;
//                 }
//             } else {
//                 if($boolSet == false) {
//                     $boolSet = true;
//                     $objItem->selected = true;
//                     $arrPreise = unserialize($objVarianten->preise);
//                     $filter[$objProdukt_Attrib->id] = $objItem->id;
//                  }
//             }
// 
//             $arrVarianten[] = $objItem;
//         }
// 
//         // Preise nachbauen
//         if(is_array($arrPreise)) {
//             foreach($arrPreise as $value) {
//                 if($value['label']) {
//                     $this->preise = serialize($arrPreise);
//                 }
//             }
//         }
// 
//         // Building Template
//         if($objAttribute->feldtyp && count($arrVarianten)) {
//             $objTemplate = new FrontendTemplate('variant_' . $objAttribute->feldtyp);
//             $objTemplate->ID = $objProdukt_Attrib->id;
//             $objTemplate->Bezeichnung = $objAttribute->bezeichnung;
//             $objTemplate->Options = $arrVarianten;
// 
//             $html = $objTemplate->parse();
//         }
// 
//         $this->arrAttributes = $filter;
// 
//         // Weitere Attribute und Varianten abholen
//         $objSummary = $this->Database->prepare("SELECT COUNT(*) AS Summary FROM tl_shop_produkte_attribute WHERE pid=? && pattribut_id=?;")->execute($pid, $pattribut_id);
//         if($objSummary->Summary) {
//             $html .= $this->buildVariant($pid, $objProdukt_Attrib->id, $filter);
//         }
// 
//         return $html;
//     }
// //
    /**
     * Erstellt die Attributliste für den Warenkorb aus dem Auswahl Array
     **/

    public function filterArray() {
        if(is_array($this->produkt_attributes) && count($this->produkt_attributes)) {
            foreach($this->produkt_attributes as $attribute => $selection) {
                $objAttribute = $this->Database->prepare("SELECT tl_shop_attribute.* FROM tl_shop_attribute, tl_shop_produkte_attribute WHERE tl_shop_produkte_attribute.id=? && tl_shop_produkte_attribute.attribut_id = tl_shop_attribute.id")->execute($attribute);

                if($this->isDefinedAttribute($attribute)) {
                    $objVariante  = $this->Database->prepare("SELECT * FROM tl_shop_attribute_values WHERE id=?")->execute($selection);
                    $arrAttributes[] = (object) array(
                        'title'     => $objAttribute->bezeichnung,
                        'selection' => $objVariante->bezeichnung
                    );
                } else {
                    $objVariante  = $this->Database->prepare("SELECT * FROM tl_shop_produkte_varianten WHERE id=?")->execute($selection);
                    $arrAttributes[] = (object) array(
                        'title'     => $objAttribute->bezeichnung,
                        'selection' => $objVariante->bezeichnung
                    );
                }
            }
        }

        $this->attribute_list = $arrAttributes;
    }
}

?>