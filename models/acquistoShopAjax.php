<?php
 
/**
 * The AcquistoShop extension allows the creation a OnlineStore width 
 * the OpenSource Contao CMS. For Question visit our Website under
 * http://www.contao-acquisto.de  
 *
 * PHP version 5
 * @package	   AcquistoShop
 * @subpackage Controller
 * @author     Sascha Brandhoff <brandhoff@contao-acquisto.de>
 * @copyright  AcquistoShop
 * @license    LGPL.
 * @filesource
 */
 
namespace AcquistoShop;  

class acquistoShopAjax extends \Backend
{

    /**
     * Ajax action
     * @var string
     */
    protected $strAction;

    /**
     * Ajax id
     * @var string
     */
    protected $strAjaxId;

    /**
     * Ajax key
     * @var string
     */
    protected $strAjaxKey;

    /**
     * Ajax name
     * @var string
     */
    protected $strAjaxName;

    /**
     * Ajax actions that do not require a data container object
     */
    public function PRECategorieActions($strAction)
    {
        $this->strAjaxId = preg_replace('/.*_([0-9a-zA-Z]+)$/i', '$1', $this->Input->post('id'));
        $this->strAjaxKey = str_replace('_' . $this->strAjaxId, '', $this->Input->post('id'));

        if ($this->Input->get('act') == 'editAll')
        {
            $this->strAjaxKey = preg_replace('/(.*)_[0-9a-zA-Z]+$/i', '$1', $this->strAjaxKey);
            $this->strAjaxName = preg_replace('/.*_([0-9a-zA-Z]+)$/i', '$1', $this->Input->post('name'));
        }

        $nodes = $this->Session->get($this->strAjaxKey);
        $nodes[$this->strAjaxId] = intval($this->Input->post('state'));
        $this->Session->set($this->strAjaxKey, $nodes);
    }


    /**
     * Ajax actions that do require a data container object
     * @param DataContainer
     */
    public function POSTCategorieActions($strAction, $dc)
    {
        $arrData['strTable'] = $dc->table;
        $arrData['id'] = strlen($this->strAjaxName) ? $this->strAjaxName : $dc->id;
        $arrData['name'] = $this->Input->post('name');

        $objWidget = new $GLOBALS['BE_FFL']['categorieTree']($arrData, $dc);

        echo $objWidget->generateAjax($this->strAjaxId, $this->Input->post('field'), intval($this->Input->post('level')));
    }
}

?>