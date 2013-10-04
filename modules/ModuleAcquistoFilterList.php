<?php

/**
 * The AcquistoShop extension allows the creation a OnlineStore width 
 * the OpenSource Contao CMS. For Question visit our Website under
 * http://www.contao-acquisto.de  
 *
 * PHP version 5
 * @package	   AcquistoShop
 * @subpackage Module
 * @author     Sascha Brandhoff <brandhoff@contao-acquisto.de>
 * @copyright  AcquistoShop
 * @license    LGPL.
 * @filesource
 */

namespace AcquistoShop\Frontend;

class ModuleAcquistoFilterList extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_produktliste';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO FILTERLIST ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        $this->Import('AcquistoShop\acquistoShopProduktLoader', 'Produkt');
        $this->Import('AcquistoShop\acquistoShopProduktList', 'Liste');
        $this->Import('AcquistoShop\acquistoShop', 'Shop');
        return parent::generate();
    }


    /**
     * Generate module
     */
    protected function compile()
    {
        /**
         * Versand & Zahlungs Seite
         */

        if($GLOBALS['TL_CONFIG']['versandPage']) {
            $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($GLOBALS['TL_CONFIG']['versandPage']);
            $strVersandpage = $this->generateFrontendUrl($objPage->fetchAssoc(), '');
         }

        /**
         * Weiterleitungsseite ermitteln für Produktanzeige
         */

        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
        $strUrl  = $this->generateFrontendUrl($objPage->fetchAssoc(), '/produkt/%s');

        $objProdukte = $this->Liste->newlist();
        
        if($this->acquistoShop_warengruppe) {
            $objProdukte->groups($this->acquistoShop_warengruppe);
        }

        if($this->acquistoShop_hersteller) {
            $objProdukte->manufacturer($this->acquistoShop_hersteller);
        }

        if($this->acquistoShop_tags) {            
      	    $objProdukte->tags(explode(",", $this->acquistoShop_tags));
        }
 

        if($this->acquistoShop_zustand) {
            $objProdukte->fields(array('zustand'), $this->acquistoShop_zustand); 
        }

        if($this->acquistoShop_produkttype) {
            $objProdukte->fields(array('type'), $this->acquistoShop_produkttype); 
        }

        if($this->acquistoShop_markedProducts) {
            $objProdukte->fields(array('marked'), $this->acquistoShop_markedProducts); 
        }

        /**
         * Limit Page ermitteln
         **/
        if(!$this->Input->Get('Page'))
        {
            $this->Input->setGet('Page', 0);
        }

        if((($this->contaoShop_numberOfItems - $this->Input->Get('Page')) < $this->perPage) && $this->contaoShop_numberOfItems) {
            $intShow = ($this->contaoShop_numberOfItems - $this->Input->Get('Page'));
        } elseif($this->contaoShop_numberOfItems && !$this->perPage) {
            $intShow = $this->contaoShop_numberOfItems;
        } else {
            $intShow = $this->perPage;
        }

        /**
         * Produkte auslesen
         */
//         $objProdukte = $this->Database->prepare("SELECT * FROM tl_shop_produkte" . $strSQL)->limit($intShow, $this->Input->Get('Page'))->execute();
//         $objTotal = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_shop_produkte" . $strSQL)->execute($objWarengruppe->id);

        /**
         * Pagniation erstellen
         **/
        $objPageListe = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->replaceInsertTags($this->Shop->getInsertTagPID()));
        $strUrlListe  = $this->generateFrontendUrl($objPageListe->fetchAssoc(), '/Page/%s');

        if(($this->contaoShop_numberOfItems && $this->perPage) && ($this->contaoShop_numberOfItems - $this->perPage)) {
            $intPages = ceil($this->contaoShop_numberOfItems / $this->perPage);
        } elseif(!$this->contaoShop_numberOfItems && $this->perPage) {
            $intPages = ceil($objProdukte->count() / $this->perPage);
        } else {
            $intPages = 0;
        }
 
        if($this->perPage OR $objProdukte->count()) {
            if(($objProdukte->count() > ($this->Input->Get('Page') + $this->perPage)) && ($this->Input->Get('Page') < (($intPages - 1) * $this->perPage))) {
                $this->Template->Next = sprintf($strUrlListe, ($this->Input->Get('Page') + $this->perPage));
            }

            if(0 <= ($this->Input->Get('Page') - $this->perPage) && $intPages) {
                $this->Template->Prev = sprintf($strUrlListe, ($this->Input->Get('Page') - $this->perPage));
            }

            if($this->perPage) {
                for($nI = 0; $nI < $intPages; $nI++) {
                    $strClass = null;
                    if($this->Input->Get('Page') == ($nI * $this->perPage))
                    {
                        $strClass = "selected";
                    }

                    $arrPages[] = array(
                        'Page'  => ($nI + 1),
                        'Url'   => sprintf($strUrlListe, ($nI * $this->perPage)),
                        'Class' => $strClass
                    );
                }
            }

            $this->Template->Pages = $arrPages;
        }
        
        if($objProdukte->count()) {
            $Items = $objProdukte->limit($this->Input->Get('page'), $intShow)->rows();
                
            foreach($Items as $Item) {
                $intCounter++;
    
                $Produkt                = $this->Produkt->load($Item->id);
                $Produkt->url           = $strUrl;
                $Produkt->default_image = $this->contaoShop_imageSrc;
    
                $objFile = \FilesModel::findByPk($Produkt->preview_image);
    
                if($Produkt->preview_image) {
                    $objImage = new \stdClass();
                    $this->addImageToTemplate($objImage, array (
                            'addImage'    => 1,
                            'singleSRC'   => $objFile->path,
                            'alt'         => null,
                            'size'        => $this->acquistoShop_galerie_imageSize,
                            'imagemargin' => $this->acquistoShop_galerie_imageMargin,
                            'imageUrl'    => $Produkt->url,
                            'caption'     => null,
                            'floating'    => $this->acquistoShop_galerie_imageFloating,
                            'fullsize'    => $this->acquistoShop_galerie_imageFullsize
                        )
                    );
    
                    $Produkt->preview_image = $objImage;
                }
    
                /**
                 * CSS Attribute setzen
                 **/
    
                if($this->acquistoShop_elementsPerRow) {
                    if(($intCounter % $this->acquistoShop_elementsPerRow) == 0) {
                        $Produkt->css .= 'break ';
                    }
                }
    
                if($intCounter == 1) {
                    $Produkt->css .= 'first ';
                }
    
                if($intCounter == $objProdukte->numRows) {
                    $Produkt->css .= 'last ';
                }
     
                $arrProdukte[] = $Produkt;
            }
        }

        if(!$this->acquistoShop_listTemplate) {
            $this->acquistoShop_listTemplate = 'acquisto_list_default';
        }

        $Template = new \FrontendTemplate($this->acquistoShop_listTemplate);
        $Template->Versand  = $strVersandpage;
        $Template->Produkte = $arrProdukte;
        $Template->Catalog      = $GLOBALS['TL_CONFIG']['catalogFunction'];
        $Template->CatalogMoney = $GLOBALS['TL_CONFIG']['catalogMoney'];

        $this->Template->Produkte = $Template->parse();
    }
}

?>