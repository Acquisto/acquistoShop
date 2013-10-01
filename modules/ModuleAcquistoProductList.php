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

class ModuleAcquistoProductList extends \Module
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

            $objTemplate->wildcard = '### ACQUISTO PRODUCTLIST ###';
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

    private function setShippingAndPayment() {
        /**
         * Versand & Zahlungs Seite
         */

        if($GLOBALS['TL_CONFIG']['versandPage']) {
            $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($GLOBALS['TL_CONFIG']['versandPage']);
            return $this->generateFrontendUrl($objPage->fetchAssoc(), '');
        }
    }


    /**
     * Generate module
     */
    protected function compile()
    {
        if($this->Input->Get('warengruppe')) {
            $objWarengruppe = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE alias = ?")->limit(1)->execute($this->Input->Get('warengruppe'));

        }

        $objProdukte = $this->Liste->newlist();

        if($this->Input->Get('warengruppe')) {
            /**
             * Warengruppe suchen
             */

            $objWarengruppe = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE alias = ?")->limit(1)->execute($this->Input->Get('warengruppe'));
            $arrWarengruppe = $objWarengruppe->row();

            if($objWarengruppe->imageSrc) {
                $arrWarengruppe['imageSrc'] = new \stdClass();
                $objFile = \FilesModel::findByPk($objWarengruppe->imageSrc);

                $this->addImageToTemplate($arrWarengruppe['imageSrc'], array
                    (
                        'addImage'    => 1,
                        'singleSRC'   => $objFile->path,
                        'alt'         => null,
                        'size'        => $this->contaoShop_imageSize,
                        'imagemargin' => $this->contaoShop_imageMargin,
                        'imageUrl'    => $arrElement['url'],
                        'caption'     => null,
                        'floating'    => $this->contaoShop_imageFloating,
                        'fullsize'    => $this->contaoShop_imageFullsize
                    )
                );
            }

            $this->Template->Warengruppe = (object) $arrWarengruppe;
            $objProdukte->groups($objWarengruppe->id);

            /**
             * Keywords und Description füllen
             **/

            $this->addKeywords($objWarengruppe->seo_keywords);
            $this->addDescription($objWarengruppe->seo_description);
            $this->pageTitle($objWarengruppe->title);
            $this->Template->title = $objWarengruppe->title;
        }

        /**
         * Weiterleitungsseite ermitteln für Produktanzeige
         */

        if($this->contaoShop_jumpTo) {
            $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
            $strUrl  = $this->generateFrontendUrl($objPage->fetchAssoc(), '/produkt/%s');
        }

        /**
         * Weiterleitungsseite ermitteln für Produktliste
         */
        $strUrlListe = '/page/%s';

        if($this->Input->Get('warengruppe')) {
            $strUrlListe .= '/warengruppe/' . $this->Input->Get('warengruppe');
        }
        if($this->Input->Get('preis')) {
            $strUrlListe .= '/preis/' . $this->Input->Get('preis');
        }
        if($this->Input->Get('hersteller')) {
            $strUrlListe .= '/hersteller/' . $this->Input->Get('hersteller');
        }
        if($this->Input->Get('suchbegriff')) {
            $strUrlListe .= '/suchbegriff/' . $this->Input->Get('suchbegriff') . '/mode/' . $this->Input->Get('mode');
        }

        if($this->Input->Get('tag')) {
            $strUrlListe .= '/tag/' . $this->Input->Get('tag');
        }

        $objPageListe = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->replaceInsertTags($this->Shop->getInsertTagPID()));
        $strUrlListe  = $this->generateFrontendUrl($objPageListe->fetchAssoc(), $strUrlListe);

        /**
         * Limit Page ermitteln
         **/
        if(!$this->Input->Get('page'))
        {
            $this->Input->setGet('page', 0);
        }

        if((($this->contaoShop_numberOfItems - $this->Input->Get('page')) < $this->perPage) && $this->contaoShop_numberOfItems) {
            $intShow = ($this->contaoShop_numberOfItems - $this->Input->Get('page'));
        } elseif($this->contaoShop_numberOfItems && !$this->perPage) {
            $intShow = $this->contaoShop_numberOfItems;
        } else {
            $intShow = $this->perPage;
        }

        /**
         * Suche ausführen
         */
        if($this->Input->Get('suchbegriff')) {
            $objSettings = $this->Database->prepare("SELECT * FROM tl_module WHERE id=?")->execute($this->Input->Get('mode'));

            $arrSearchfields = unserialize($objSettings->acquistoShop_Searchfields);
            if(is_array($arrSearchfields)) {
                $objProdukte->fields($arrSearchfields, $this->Input->Get('suchbegriff'));
            }

            $this->pageTitle('Suche nach: ' . $this->Input->Get('suchbegriff'));
            $this->Template->title = 'Suche nach: ' . $this->Input->Get('suchbegriff');
        }

        /**
         * Produkte auslesen
         */

        if($this->Input->Get('hersteller')) {
            $objProdukte->manufacturer($this->Input->Get('hersteller'));
        }

        if($this->Input->Get('preis')) {
      	    $arrPreise = explode("-", $this->Input->Get('preis'));
      	    $objProdukte->costs($arrPreise[0], $arrPreise[1]);
        }

        if($this->Input->Get('tag')) {
            #echo $this->Input->Get('tag');
      	    $objProdukte->tags($this->Input->Get('tag'));
        }

        if($objProdukte->count()) {

            /**
             * Pagniation erstellen
             **/
            if(($this->contaoShop_numberOfItems && $this->perPage) && ($this->contaoShop_numberOfItems - $this->perPage)) {
                $intPages = ceil($this->contaoShop_numberOfItems / $this->perPage);
            } elseif(!$this->contaoShop_numberOfItems && $this->perPage) {
                $intPages = ceil($objProdukte->count() / $this->perPage);
            } else {
                $intPages = 0;
            }

            if($this->perPage OR $objProdukte->count()) {
                if(($objProdukte->count() > ($this->Input->Get('page') + $this->perPage)) && ($this->Input->Get('page') < (($intPages - 1) * $this->perPage))) {
                    $this->Template->Next = sprintf($strUrlListe, ($this->Input->Get('page') + $this->perPage));
                }

                if(0 <= ($this->Input->Get('page') - $this->perPage) && $intPages) {
                    $this->Template->Prev = sprintf($strUrlListe, ($this->Input->Get('page') - $this->perPage));
                }

                if($this->perPage) {
                    for($nI = 0; $nI < $intPages; $nI++) {
                        $strClass = null;
                        if($this->Input->Get('page') == ($nI * $this->perPage)) {
                            $strClass = "selected";
                        }

                        $arrPages[] = array(
                            'Page'  => ($nI + 1),
                            'Url'   => sprintf($strUrlListe, ($nI * $this->perPage)),
                            'Class' => $strClass
                        );
                    }
                }
            }

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
                    , null,'id' . rand(1000, 9999));

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

            if(!$this->acquistoShop_listTemplate) {
                $this->acquistoShop_listTemplate = 'acquisto_list_default';
            }

            $Template = new \FrontendTemplate($this->acquistoShop_listTemplate);
            $Template->Versand  = $this->setShippingAndPayment();
            $Template->Produkte = $arrProdukte;
            $Template->Catalog      = $GLOBALS['TL_CONFIG']['catalogFunction'];
            $Template->CatalogMoney = $GLOBALS['TL_CONFIG']['catalogMoney'];

            $this->Template->Produkte = $Template->parse();
            $this->Template->Pages = $arrPages;
        }
    }

    protected function addDescription($strDescription)
    {
        global $objPage;
        $objPage->description = $strDescription;
        return;
    }

    protected function addKeywords($strKeywords)
    {
        $GLOBALS['TL_KEYWORDS'] .= (strlen($GLOBALS['TL_KEYWORDS']) ? ', ' : '') . $strKeywords;
        return;
    }

    protected function pageTitle($strTitle)
    {
        global $objPage;
        $objPage->title = $strTitle;
        return;
    }

    public function productByCategorie($categorieID) {
        $objProdukte = $this->Database->prepare("SELECCT id,warengruppen FROM tl_shop_produkte WHERE aktiv=?")->execute(1);
        while($objProdukte->next()) {
            $arrWarengruppen = unserialize($objProdukte->warengruppen);
            if(is_array($arrWarengruppen)) {
                if(in_array($categorieID, $arrWarengruppen)) {
                    $arrProdukte[] = $objProdukte->id;
                }
            }
        }

        return $arrProdukte;
    }
}

?>