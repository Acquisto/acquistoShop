<?php 

/**
 * The AcquistoShop extension allows the creation a OnlineStore width 
 * the OpenSource Contao CMS. For Question visit our Website under
 * http://www.contao-acquisto.de  
 *
 * PHP version 5
 * @package	   AcquistoShop
 * @subpackage Export
 * @author     Sascha Brandhoff <brandhoff@contao-acquisto.de>
 * @copyright  AcquistoShop
 * @license    LGPL.
 * @filesource
 */
 
class billigerde extends \Controller {
    protected $exportModule = 'billiger.de';
    public $exportID;

    public function __construct()
    {
        parent::__construct();
    }

    public function getSettingsData() {

    }

    public function getExportInfo() {
        return $this->exportModule;
    }

    public function compile()
    {
        $this->Import('Database');
        $this->Import('Environment');
        $this->Import('acquistoShop', 'Shop');

        $objExport = $this->Database->prepare("SELECT * FROM tl_shop_export WHERE id = ?")->limit(1)->execute($this->exportID);
        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($objExport->produktDetails);
        $strUrl  = $this->generateFrontendUrl($objPage->fetchAssoc(), '/produkt/%s');

        $htmlData .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $htmlData .= "<products>\n";

        $objProdukte = $this->Database->prepare("SELECT * FROM tl_shop_produkte WHERE aktiv = ?;")->execute(1);
        while($objProdukte->next()) {
            $htmlData .= "    <product>\n";
            $htmlData .= "        <aid>" . $objProdukte->id . $objProdukte->produktnummer . "</aid>\n";
            $htmlData .= "        <name>" . $objProdukte->bezeichnung . "</name>\n";
            $htmlData .= "        <desc>" . $objProdukte->teaser . "</desc>\n";
            $htmlData .= "        <price>" . $this->Shop->getProductPrice($objProdukte->id, 0) . "</price>\n";
            $htmlData .= "        <link>http://" . $this->Environment->httpHost . sprintf($strUrl, $objProdukte->alias) . "</link>\n";

            if($objProdukte->hersteller) {
                $objHersteller = $this->Database->prepare("SELECT * FROM tl_shop_hersteller WHERE id = ?")->limit(1)->execute($objProdukte->hersteller);
                $htmlData .= "        <brand>" . $objHersteller->bezeichnung . "</brand>\n";
            }

//            $htmlData .= "        <ean>6417182976753</ean>\n";

            if($arrGrundpreis = $this->Shop->getProductGrundpreis($objProdukte->id, 0)) {
                $htmlData .= "        <ppu>" . str_replace(".", ",", $arrGrundpreis['grundpreis']) . " € / " . $arrGrundpreis['berechnungsmenge'] . " " . $arrGrundpreis['mengeneinheit'] . "</ppu>\n";
            }

//            $htmlData .= "        <shop_cat>Bücher;Geschichte</shop_cat>\n";

            if($objProdukte->preview_image) {
                $htmlData .= "            <image>http://" . $this->Environment->httpHost . "/" . $objProdukte->preview_image . "</image>\n";
            }

//            $htmlData .= "        <dlv_time>sofort lieferbar</dlv_time>\n";
//            $htmlData .= "        <dlv_cost>0,0</dlv_cost>\n";
            $htmlData .= "    </product>\n";

        }

        $htmlData .= "</products>\n";
        return $htmlData;
    }
}


?>