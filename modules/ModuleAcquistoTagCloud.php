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

class ModuleAcquistoTagCloud extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_tagcloud';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO TAG-CLOUD ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        $objSummary = $this->Database->prepare("SELECT COUNT(*) AS totalCount FROM tl_shop_produkte WHERE tags!=''")->execute();
        if(!$objSummary->totalCount) {
            return '';
        }
        
        return parent::generate();
    }


    /**
     * Generate module
     */
    protected function compile()
    {
        /**
         * Weiterleitungsseite ermitteln für Sucheergbniss
         */

        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
        $strUrl  = $this->generateFrontendUrl($objPage->fetchAssoc(), '/tag/%s');

        $objProdukte = $this->Database->prepare("SELECT tags FROM tl_shop_produkte WHERE tags != '' && aktiv = 1")->execute();

        /**
         * Pagniation erstellen
         **/

        if($this->acquistoShop_excludeTags) {
            $arrExclude = explode(",", $this->acquistoShop_excludeTags);
        }

        while($objProdukte->next())
        {
            $arrTags = explode(",", $objProdukte->tags);
            if(is_array($arrTags)) {
                foreach($arrTags as $item) {
                    $arrCloud[trim($item)]++;
                }
            }
        }

        if(is_array($arrCloud)) {
            if(is_array($arrExclude)) {
                foreach($arrExclude as $Tag) {
                    if(array_key_exists(trim($Tag), $arrCloud)) {
                        unset($arrCloud[trim($Tag)]);
                    }
                }
            }

            foreach($arrCloud as $item) {
                if($max < $item) {
                    $max = $item;
                }
            }

            foreach($arrCloud as $item) {
                if($max > $item) {
                    $min = $item;
                }
            }

            foreach($arrCloud as $key => $item) {
                $arrBuild[] = array(
                    'prozent' => round((((100 / $max) * $item) + 50), 0),
                    'num'     => $item,
                    'tag'     => $key,
                    'url'     => sprintf($strUrl, urlencode($key))
                );
            }
        }

        $this->Template->Cloud = $arrBuild;
    }
}

?>