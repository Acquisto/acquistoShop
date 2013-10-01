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

class acquistoShopMember extends \Controller {
    private $Member = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->Import('Database');
        
        if(FE_USER_LOGGED_IN) 
        {
            $this->import('FrontendUser', 'Member');
            $this->memberGroups = $this->Member->groups;
        }        
    }
}

?>