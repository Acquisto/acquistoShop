<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Class ContentText
 *
 * Front end content element "text".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ContentProduct extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_productdetails';

	public function generate()
	{
    if(!$this->product_id) {
        return '';
    }
        
		return parent::generate();       
  }
  
	/**
	 * Generate the content element
	 */
	protected function compile()
	{
        $this->Import('AcquistoShop\acquistoShopProduktLoader', 'Produkt');
        $this->Import('AcquistoShop\acquistoShopBasket', 'Basket');

        if($this->Input->Post('action') == 'tl_shop_orders') {
            $this->Basket->add($this->Input->Post('id'), $this->Input->Post('menge'), $this->Input->Post('additionalBasket'));
            $this->Input->setPost('action', null);
        }

        if($this->contaoShop_socialFacebook) {
            $this->Template->Facebook = urlencode('http://' . $this->Environment->httpHost  . $this->Environment->requestUri);
        }

        if($this->contaoShop_socialTwitter) {
            $this->Template->Twitter = urlencode('http://' . $this->Environment->httpHost  . $this->Environment->requestUri);
        }


        $objProdukt = $this->Produkt->load($this->product_id, $this->Input->Post('additionalBasket'));

        $produktTemplate = new FrontendTemplate($objProdukt->strTemplate);

        /**
         * Versand & Zahlungs Seite
         */
        if($GLOBALS['TL_CONFIG']['versandPage']) {
            $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($GLOBALS['TL_CONFIG']['versandPage']);            
            $produktTemplate->Versand = $this->generateFrontendUrl($objPage->fetchAssoc(), '');
        }

        if($objProdukt->preview_image OR $this->contaoShop_imageSrc)
        {
            if($objProdukt->preview_image)
            {
                $objFile = FilesModel::findByPk($objProdukt->preview_image);
            }
            else
            {
                $objFile = FilesModel::findByPk($this->contaoShop_imageSrc);
            }


            $objImage = new stdClass();
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
                    $objFile = FilesModel::findByPk($value);

                    $objGalerie = new stdClass();
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
}
