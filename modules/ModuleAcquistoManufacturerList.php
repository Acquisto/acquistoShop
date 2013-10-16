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

class ModuleAcquistoManufacturerList extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_manufacturer_list';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO MANUFACTURERLIST ###';
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
        if($this->contaoShop_jumpTo) 
        {
            $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
            $strUrl  = $this->generateFrontendUrl($objPage->fetchAssoc(), '/hersteller/%s');
        }
    
    
        $objDatabase = $this->Database->prepare("SELECT * FROM tl_shop_hersteller")->execute();
        while($objDatabase->next()) 
        {
            $objItem = (object) $objDatabase->row();
            $objItem->url = sprintf($strUrl, $objItem->id);
            if($objItem->imageSrc) {
                $objImage = new \stdClass();
                $objFile = \FilesModel::findByPk($objItem->imageSrc);

                $this->addImageToTemplate($objImage, array
                    (
                        'addImage'    => 1,
                        'singleSRC'   => $objFile->path,
                        'alt'         => null,
                        'size'        => $this->acquistoShop_imageSize,
                        'imagemargin' => $this->acquistoShop_imageMargin,
                        'imageUrl'    => $objItem->url,
                        'caption'     => null,
                        'floating'    => $this->acquistoShop_imageFloating,
                        'fullsize'    => $this->acquistoShop_imageFullsize
                    )
                );
                
                $objItem->imageSrc = $objImage;
            }

            $arrHersteller[] = $objItem;
        }
        
        $this->Template->Manufacturer = $arrHersteller;
    }
}

?>