<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');


/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */

/**
 * Class mod_acquistoShop_AGB
 *
 * Front end module "mod_acquistoShop_AGB".
 * @copyright  Sascha Brandhoff 2011
 * @author     Sascha Brandhoff <http://www.contao-acquisto.org>
 * @package    Controller
 */

class ModuleAcquistoOrderList extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_acquisto_bestellliste';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ACQUISTO BESTELLLISTE ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        if (!FE_USER_LOGGED_IN) {
            return '';
        }

        return parent::generate();
    }


    /**
     * Generate module
     */
    protected function compile()
    {
        if (FE_USER_LOGGED_IN) {
            $this->import('FrontendUser', 'User');
            $objBestellungen = $this->Database->prepare("SELECT * FROM tl_shop_orders WHERE member_id = ? ORDER BY tstamp DESC")->execute($this->User->id);

            $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->contaoShop_jumpTo);
            $strUrl = $this->generateFrontendUrl($objPage->fetchAssoc(), '/order/%s');

            while($objBestellungen->next()) {
                $intCounter++;

                $objPositionen   = $this->Database->prepare("SELECT SUM(menge * preis) AS endpreis FROM tl_shop_orders_items WHERE pid = ?")->execute($objBestellungen->id);
                $objZahlungsart  = $this->Database->prepare("SELECT * FROM tl_shop_zahlungsarten WHERE id = ?")->execute($objBestellungen->zahlungsart_id);
                $objVersandzonen = $this->Database->prepare("SELECT * FROM tl_shop_versandzonen WHERE id = ?")->execute($objBestellungen->versandzonen_id);

                $arrBestellung = $objBestellungen->row();
                $arrBestellung['versandpreis'] = sprintf("%01.2f", $arrBestellung['versandpreis']);
                $arrBestellung['versandzone']  = $objVersandzonen->bezeichnung;
                $arrBestellung['tstamp']       = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $arrBestellung['tstamp']);
                $arrBestellung['zahlungsart']  = $objZahlungsart->bezeichnung;
                $arrBestellung['url']          = sprintf($strUrl, $arrBestellung['id']);

                if($arrBestellung['gutscheine']) {
                    $arrGutscheine = unserialize($arrBestellung['gutscheine']);
                    $dblGutscheine = 0;
                    if(is_array($arrGutscheine)) {

                        foreach($arrGutscheine as $objGutschein) {
                            $dblGutscheine = $dblGutscheine + $objGutschein->preis;
                        }
                    }
                }

                if(($intCounter % 2) == 1) {
                    $arrBestellung['css']  = 'even';
                } else {
                    $arrBestellung['css']  = 'odd';
                }

                $arrBestellung['endpreis']     = sprintf("%01.2f", ($objPositionen->endpreis + $arrBestellung['versandpreis']) - $dblGutscheine);
                $arrBestellungen[] = (object) $arrBestellung;
            }

            $this->Template->Bestellungen = $arrBestellungen;
        }
    }
}

?>