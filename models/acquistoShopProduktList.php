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

class acquistoShopProduktList extends \Controller 
{
    private $filterData        = array();
    private $itemResult        = array();
    private $boolChange        = false;

    public function __construct()
    {
        parent::__construct();
        $this->Import('Database');
    }

    public function newlist() 
    {
        $this->itemResult        = array();
        $this->boolChange        = false;
        return $this;
    }

    public function groups() 
    {
        $this->boolChange = true;
        $this->filterData['groups'] = func_get_args();

        return $this;
    }

    public function manufacturer() 
    {
        $this->boolChange = true;
        $this->filterData['manufacturer'] = func_get_args();

        return $this;
    }

    public function fields($fields, $keyword) 
    {
        $this->boolField     = true;
        $this->boolChange    = true;

        $this->filterData['search'][] = (object) array(
            'fields'   => $fields,
            'keyword'  => $keyword
        );
                 
        return $this;
    }

    public function rows() 
    {
        if($this->boolChange) 
        {
            $this->formQuery();
        }

        $returnValue = $this->itemResult;
        if($this->filterData['limit']['limit']) 
        {
            $returnValue = array_slice($returnValue, $this->filterData['limit']['start'], $this->filterData['limit']['limit'], true);
        }

        return $returnValue;
    }

    public function tags() 
    {
        $this->boolChange = true;

        for($nI = 0; $nI < func_num_args(); $nI++) 
        {
            $getData = func_get_arg($nI);
            
            if(is_array($getData)) 
            {
                foreach($getData as $item) 
                {
                    $this->filterData['tags'][] = $item;                
                }
            } 
            else 
            {
                $this->filterData['tags'][] = $getData;                
            }
        }
                        
        return $this;
    }
    
    private function getArrayData($array) 
    {
        if(is_array($array)) 
        {
            
        }
    }
    
    public function count() 
    {
        if($this->boolChange) 
        {
            $this->formQuery();
        }

        return count($this->itemResult);
    }

    private function formQuery() {
        // Standard String zum Abruf
        $strSQL  = "SELECT * FROM tl_shop_produkte WHERE aktiv = 1";


        if(count($this->filterData['manufacturer'])) {
            $strSQL .= " && hersteller IN (" . implode(",", $this->filterData['manufacturer']) . ")";
        }

        if(count($this->filterData['groups'])) {
            foreach($this->filterData['groups'] as $group) {
                $strGroups .= "warengruppen LIKE '%:\"" . $group . "\";%' && ";
            }

            $strSQL .= " && (" . substr($strGroups, 0, -4) . ")";
        }

        if(count($this->filterData['search'])) {
            foreach($this->filterData['search'] as $searchItem) {
                $keywordString = " LIKE '%" . $searchItem->keyword . "%' OR ";
                $strSQL .= " && (" . substr(implode($keywordString, $searchItem->fields) . $keywordString, 0, -4) . ")";
            }
        }

        if(count($this->filterData['tags'])) {                        
            foreach($this->filterData['tags'] as $value) {
                $strTags .= "tags LIKE '%" . trim($value) . "%' OR ";
            }

            $strSQL .= " && (" . substr($strTags, 0, -4) . ")";
        }

        $strSQL .= " && ((startDate < '" . time() . "' && endDate > '" . time() . "') OR (startDate < '" . time() . "' && endDate = '') OR (startDate = '' && endDate > '" . time() . "') OR (startDate = '' && endDate = ''))";

        $objProdukte = $this->Database->prepare($strSQL)->execute();
        while($objProdukte->next()) {
            $arrItems[] = (object) $objProdukte->row();
        }

        if($this->filterData['costs']['max']) 
        {
            $this->Import('AcquistoShop\acquistoShopCosts', 'Costs');
            
            foreach($arrItems as $item) 
            {
                $this->Costs->setGroups($this->Member->groups);
                $this->Costs->setTax($item->steuer);        
                $this->Costs->buildCostsData(unserialize($item->preise));

                $itemCosts = $this->Costs->getCosts(0)->costs;
                if($this->Costs->getCosts(0)->special)
                {
                    $itemCosts = $this->Costs->getCosts(0)->special;
                }
                
                if(($itemCosts >= $this->filterData['costs']['min']) && ($itemCosts < $this->filterData['costs']['max']) OR ($itemCosts >= $this->filterData['costs']['min']) && ($itemCosts < $this->filterData['costs']['max'])) 
                {
                    $newArray[] = $item;
                }
            }

            $arrItems = $newArray;
        }
        
        $this->itemResult = $arrItems;
    }

    public function costs($min, $max) 
    {
        $this->boolChange = true;

        $this->filterData['costs']['min'] = $min;                
        $this->filterData['costs']['max'] = $max;                

        return $this;
    }

    public function limit($start, $limit) 
    {
        $this->boolChange = true;

        $this->filterData['limit']['start'] = $start;                
        $this->filterData['limit']['limit'] = $limit;                

        return $this;
    }
}

?>