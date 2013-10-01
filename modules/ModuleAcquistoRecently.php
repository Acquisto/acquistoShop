<?php

/**
 * The AcquistoShop extension allows to create an online 
 * store with the OpenSource Contao CMS. If you have
 * questions our website: http://www.contao-acquisto.de  
 *
 * PHP version 5
 * @package	   AcquistoShop
 * @subpackage Frontend
 * @author     Sascha Brandhoff <brandhoff@contao-acquisto.de>
 * @copyright  AcquistoShop
 * @license    LGPL.
 * @filesource
 */

namespace AcquistoShop\Frontend;

class ModuleAcquistoRecently extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_recent';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO LETZTE PRODUKTE ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        $this->Import('AcquistoShop\acquistoShop', 'Shop');
        $this->Import('AcquistoShop\acquistoShopProduktLoader', 'Produkt');

        return parent::generate();
    }


    /**
     * Generate module
     */
    protected function compile()
    {
        /**
         * Weiterleitungsseite ermitteln für Produktanzeige
         */

        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
        $strUrl  = $this->generateFrontendUrl($objPage->fetchAssoc(), '/produkt/%s');

        if($this->Input->Get('produkt')) {
            $objSelected = $this->Database->prepare("SELECT id FROM tl_shop_produkte WHERE alias = ?")->limit(1)->execute($this->Input->Get('produkt'));
            $arrRecently[] = $objSelected->id;
        }

        if(is_array($_SESSION['acquistoRescent'])) {
            foreach($_SESSION['acquistoRescent'] as $produkt_id) {
                $objProdukt = $this->Produkt->load($produkt_id);

                $intCounter++;

                if($this->contaoShop_numberOfItems >= $intCounter) {

                    /**
                     * Bildeinstellungen
                     **/

                    if($objProdukt->preview_image OR $this->contaoShop_imageSrc)
                    {
                        if($objProdukt->preview_image)
                        {
                            $strImage = $objProdukt->preview_image;
                        }
                        else
                        {
                            $strImage = $this->contaoShop_imageSrc;
                        }

                        $objFile = \FilesModel::findByPk($strImage);

                        $objImage = new \stdClass();
                        $this->addImageToTemplate($objImage, array
                            (
                                'addImage'    => 1,
                                'singleSRC'   => $objFile->path,
                                'alt'         => null,
                                'size'        => $this->acquistoShop_galerie_imageSize,
                                'imagemargin' => $this->acquistoShop_galerie_imageMargin,
                                'imageUrl'    => sprintf($strUrl, $objProdukt->alias),
                                'caption'     => null,
                                'floating'    => $this->acquistoShop_galerie_imageFloating,
                                'fullsize'    => $this->acquistoShop_galerie_imageFullsize
                            )
                        );
                    } else {
                        $objImage = null;
                    }

                    /**
                     * CSS Elemente
                     **/

                    $strCss = isset($objProdukt->zustand) ? $objProdukt->zustand . ' ' : null;

                    if($intCounter == 1) {
                        $strCss .= 'first ';
                    }

                    if($intCounter == $this->contaoShop_numberOfItems) {
                        $strCss .= 'last ';
                    }

                    if($objProdukt->marked) {
                        $strCss .= 'marked ';
                    }

                    $itemCosts = $objProdukt->getCosts(0)->costs;
                    if($objProdukt->getCosts(0)->special) {
                        $itemCosts = $objProdukt->getCosts(0)->special;
                    }

                    $objProdukt->url = sprintf($strUrl, $objProdukt->alias);
                    $objProdukt->css = $strCss;
                    $objProdukt->preview_image = $objImage;
                    
                    $arrCurrent[] = $objProdukt;
                }

                if($objSelected->id != $produkt_id) {
                    $arrRecently[] = $objProdukt->id;
                }

            }
        }


        $_SESSION['acquistoRescent'] = $arrRecently;
        $this->Template->Viewed = $arrCurrent;
    }

}

?>