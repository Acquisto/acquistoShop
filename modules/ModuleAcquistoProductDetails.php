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

class ModuleAcquistoProductDetails extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_produktdetails';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO PRODUCTDETAILS ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        return parent::generate();
    }


    /**
     * Generate module
     */
    protected function compile()
    {
        $this->Import('AcquistoShop\acquistoShopProduktLoader', 'Produkt');
        $this->Import('AcquistoShop\acquistoShopBasket', 'Basket');

        if($this->Input->Post('action') == 'tl_shop_orders') 
        {
            $this->Basket->add($this->Input->Post('id'), $this->Input->Post('menge'), $this->Input->Post('additionalBasket'));

            if($this->acquistoShop_setRedirect && $this->contaoShop_jumpTo) {
                $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
                $strUrl  = $this->generateFrontendUrl($objPage->fetchAssoc(), '');
            }

            \AcquistoShop\acquistoShop::reload($strUrl);                    
        }

        if($this->contaoShop_socialFacebook) 
        {
            $this->Template->Facebook = urlencode('http://' . $this->Environment->httpHost  . $this->Environment->requestUri);
        }

        if($this->contaoShop_socialTwitter) 
        {
            $this->Template->Twitter = urlencode('http://' . $this->Environment->httpHost  . $this->Environment->requestUri);
        }


        $objProdukt = $this->Database->prepare("SELECT id FROM tl_shop_produkte WHERE alias=?")->execute($this->Input->Get('produkt'));
        $objProdukt = $this->Produkt->load($objProdukt->id, $this->Input->Post('additionalBasket'));

        if($objProdukt->template) 
        {
            $objProdukt->strTemplate = $objProdukt->template;
        }
        
        $produktTemplate = new \FrontendTemplate($objProdukt->strTemplate);

        /**
         * Versand & Zahlungs Seite
         */
        if($GLOBALS['TL_CONFIG']['versandPage']) 
        {
            $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($GLOBALS['TL_CONFIG']['versandPage']);            
            $produktTemplate->Versand = $this->generateFrontendUrl($objPage->fetchAssoc(), '');
        }

        $this->pageTitle($objProdukt->bezeichnung);

        if($objProdukt->preview_image OR $this->contaoShop_imageSrc)
        {
            if($objProdukt->preview_image)
            {
                $objFile = \FilesModel::findByPk($objProdukt->preview_image);
            }
            else
            {
                $objFile = \FilesModel::findByPk($this->contaoShop_imageSrc);
            }


            $objImage = new \stdClass();
            $this->addImageToTemplate($objImage, array
                (
                    'addImage'    => 1,
                    'singleSRC'   => $objFile->path,
                    'alt'         => null,
                    'size'        => $this->contaoShop_imageSize,
                    'imagemargin' => $this->contaoShop_imageMargin,
                    'imageUrl'    => $objProdukt->url,
                    'caption'     => null,
                    'floating'    => $this->contaoShop_imageFloating,
                    'fullsize'    => $this->contaoShop_imageFullsize
                )
            , null,'id' . rand(1000, 9999));

            $objProdukt->preview_image = $objImage;
        }

        /**
         * Prüfen ob Galerie angelegt werden muss
         **/
        if($objProdukt->galerie) {
            $arrGalerie = deserialize($objProdukt->galerie);

            if(is_array($arrGalerie)) {
                foreach($arrGalerie as $value) {
                    $objFile = \FilesModel::findByPk($value);

                    $objGalerie = new \stdClass();
                    $this->addImageToTemplate($objGalerie, array
                        (
                            'addImage'    => 1,
                            'singleSRC'   => $objFile->path,
                            'alt'         => null,
                            'size'        => $this->acquistoShop_galerie_imageSize,
                            'imagemargin' => $this->acquistoShop_galerie_imageMargin,
                            'imageUrl'    => $objProdukt->url,
                            'caption'     => null,
                            'floating'    => $this->acquistoShop_galerie_imageFloating,
                            'fullsize'    => $this->acquistoShop_galerie_imageFullsize
                        )
                    , null, $objProdukt->id);

                    $arrGalerieItems[] = $objGalerie;
                }


                $objProdukt->galerie = $arrGalerieItems;
            }
        }


        /**
         * Comments
         **/
        /**
        global $objPage;

        $this->import('Comments');
        $objConfig = new stdClass();

        $objConfig->perPage = $this->perPage;
        $objConfig->order = $this->com_order;
        $objConfig->template = $this->com_template;
        $objConfig->requireLogin = $this->com_requireLogin;
        $objConfig->disableCaptcha = $this->com_disableCaptcha;
        $objConfig->bbcode = $this->com_bbcode;
        $objConfig->moderate = $this->com_moderate;

        $this->Comments->addCommentsToTemplate($this->Template, $objConfig, 'tl_shop_produkte', $objPage->id, $GLOBALS['TL_ADMIN_EMAIL']);
        **/        

        $produktTemplate->Produkt = $objProdukt;
        $this->Template->produktTemplate = $produktTemplate->parse();
    }

    protected function pageTitle($strTitle)
    {
        global $objPage;
        $objPage->title = $strTitle;
        return;
    }
}

?>