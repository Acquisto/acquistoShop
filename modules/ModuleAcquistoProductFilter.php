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

class ModuleAcquistoProductFilter extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_produktfilter';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO PRODUCTFILTER ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        if(!$this->Input->Get('warengruppe') && !$this->Input->Get('suchbegriff')) {
            return '';
        }

        return parent::generate();
    }


    /**
     * Generate module
     */
    protected function compile()
    {
        global $objPage;

        $this->Import('AcquistoShop\acquistoShopProduktLoader', 'Produkt');
        $this->Import('AcquistoShop\acquistoShopProduktList', 'Liste');

        $objWarengruppePage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($objPage->id);
        $objWarengruppe = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE alias = ?")->limit(1)->execute($this->Input->Get('warengruppe'));

        $strUrl = '/warengruppe/' . $objWarengruppe->alias;
        $strUrl = $this->generateFrontendUrl($objWarengruppePage->fetchAssoc(), $strUrl . '/%s/%s');

        $objProdukte = $this->Liste->newlist()->groups($objWarengruppe->id)->rows();

        if(is_array($objProdukte)) {
            foreach($objProdukte as $Produkt) {
                $objHersteller = $this->Database->prepare("SELECT id, bezeichnung FROM tl_shop_hersteller WHERE id = ?")->limit(1)->execute($Produkt->hersteller);

                if($objHersteller->id) {
                    $objCounter = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_shop_produkte WHERE warengruppen LIKE ? && hersteller = ? && aktiv = 1")->execute('%:"' . $objWarengruppe->id . '"%', $objHersteller->id);

                    $objObjekt = new \stdClass();
                    $objObjekt->id          = $objHersteller->id;
                    $objObjekt->bezeichnung = $objHersteller->bezeichnung;
                    $objObjekt->url         = sprintf($strUrl, 'hersteller', $objHersteller->id);
                    $objObjekt->counter     = $objCounter->total;

                    if($this->Input->Get('hersteller') == $objHersteller->id) {
                        $objObjekt->css = 'active';
                        $intActive = true;
                    }

                    $arrHersteller[$objHersteller->id] = $objObjekt;
                }
            }


            foreach($objProdukte as $Produkt) {
                $Produkt = $this->Produkt->load($Produkt->id);
                $arrProdukte[] = $Produkt;

                $itemCosts = $Produkt->getCosts(0)->costs;
                $currency  = $Produkt->getCosts(0)->pricelist->currency;                
                if($Produkt->getCosts(0)->special) {
                    $itemCosts = $Produkt->getCosts(0)->special;
                }
                
                $arrPreise[]   = $itemCosts;
            }
                                  
            if(is_array($arrPreise)) 
            {
                asort($arrPreise);
                for($nI = 0; $nI < end($arrPreise); $nI = $nI * 10) 
                {
                    if(!$nI) 
                    {
                        $nI = 1;
                    }
                }
                
                if(end($arrPreise))
                {
                    $intTeiler = $nI / 10;
                    $intSteps = round(end($arrPreise) / $intTeiler, 0) + 1;

                    for($nI = 0; $nI < $intSteps; $nI++) {                    
                        $intCounter = null;
                        if(is_array($arrProdukte)) {
                            foreach($arrProdukte as $Produkt) {
    
                                $itemCosts = $Produkt->getCosts(0)->costs;
                                if($Produkt->getCosts(0)->special) {
                                    $itemCosts = $Produkt->getCosts(0)->special;
                                }
    
                                if(($itemCosts >= ($nI * $intTeiler)) && ($itemCosts < (($nI + 1) * $intTeiler))) {
                                    $intCounter++;
                                }
                            }
                        }
    
                        if($intCounter) {
                            $strCSS = null;
                            if($this->Input->Get('preis') == ($nI * $intTeiler) . "-" . (($nI + 1) * $intTeiler)) {
                                $strCSS = 'active';
                                $intActive = true;
                            }
    
                            $arrPreisStruktur[] = (object) array(
                                'von'      => $nI * $intTeiler,
                                'bis'      => ($nI + 1) * $intTeiler,
                                'url'      => sprintf($strUrl, 'preis', ($nI * $intTeiler) . "-" . (($nI + 1) * $intTeiler)),
                                'css'      => $strCSS,
                                'counter'  => $intCounter,
                                'currency' => $currency
                            );                            
                        }
                    }
                }
            }            
        }

        if($intActive) {
            $objDefault = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($objPage->id);
            $this->Template->Clear = sprintf($this->generateFrontendUrl($objDefault->fetchAssoc(), '/warengruppe/%s'), $objWarengruppe->alias);
        }

        $boolFilter = false;
        // Prüfen ob es Preisfiltermöglichenkeiten gibt
        if(is_array($arrPreisStruktur)) {
            if(count($arrPreisStruktur) > 1) {
                $this->Template->Preise = $arrPreisStruktur;
                $boolFilter = true;
            }
        }

        // Prüfen ob es Herstellerfiltermöglichenkeiten gibt
        if(is_array($arrHersteller)) {
            if(count($arrHersteller) > 1) {
                $this->Template->Hersteller = $arrHersteller;
                $boolFilter = true;
            }
        }

        // Wenn keiner Filter verfügbar sind Template ausblenden
        if($boolFilter == false) {
            $this->Template = new \FrontendTemplate();
        }
    }

}

?>