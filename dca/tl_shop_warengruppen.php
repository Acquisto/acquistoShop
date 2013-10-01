<?php 

/**
 * The AcquistoShop extension allows the creation a OnlineStore width 
 * the OpenSource Contao CMS. For Question visit our Website under
 * http://www.contao-acquisto.de  
 *
 * PHP version 5
 * @package	   AcquistoShop
 * @subpackage Backend
 * @author     Sascha Brandhoff <brandhoff@contao-acquisto.de>
 * @copyright  AcquistoShop
 * @license    LGPL.
 * @filesource
 */

/**
 * Table tl_shop_warengruppen
 */
$GLOBALS['TL_DCA']['tl_shop_warengruppen'] = array
(

    // Config
    'config' => array
    (
        'label'                       => 'acquistoShop - ' . $GLOBALS['TL_LANG']['MOD']['acquistoShopWarengruppen'][0],
        'dataContainer'               => 'Table',
        'enableVersioning'            => true,
    		'sql' => array
    		(
    			'keys' => array
    			(
    				'id' => 'primary',
    				'pid' => 'index',
    				'alias' => 'index'
    			)
    		)
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 5
        ),
        'label' => array
        (
            'fields'                  => array('title'),
            'format'                  => '%s',
            'label_callback'          => array('tl_shop_warengruppen', 'addIcon')
        ),
        'global_operations' => array

        (
            'toggleNodes' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['toggleNodes'],
                'href'                => 'ptg=all',
                'class'               => 'header_toggle'
            ),
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_warengruppen']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif',
//                'button_callback'     => array('tl_shop_warengruppen', 'editPage')
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_warengruppen']['copy'],
                'href'                => 'act=paste&amp;mode=copy',
                'icon'                => 'copy.gif',
                'attributes'          => 'onclick="Backend.getScrollOffset();"',
            ),
//            'copyChilds' => array
//            (
//                'label'               => &$GLOBALS['TL_LANG']['tl_shop_warengruppen']['copyChilds'],
//                'href'                => 'act=paste&amp;mode=copy&amp;childs=1',
//                'icon'                => 'copychilds.gif',
//                'attributes'          => 'onclick="Backend.getScrollOffset();"',
//                'button_callback'     => array('tl_shop_warengruppen', 'copyPageWithSubpages')
//            ),
            'cut' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_warengruppen']['cut'],
                'href'                => 'act=paste&amp;mode=cut',
                'icon'                => 'cut.gif',
                'attributes'          => 'onclick="Backend.getScrollOffset();"'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_warengruppen']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
                'button_callback'     => array('tl_shop_warengruppen', 'deletePage')
            ),
//            'toggle' => array
//            (
//                'label'               => &$GLOBALS['TL_LANG']['tl_shop_warengruppen']['toggle'],
//                'icon'                => 'visible.gif',
//                'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
//                'button_callback'     => array('tl_shop_warengruppen', 'toggleIcon')
//            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_warengruppen']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => array('type'),
        'default'                     => '{title_legend},title,alias,type,beschreibung,imageSrc;{seo_options},seo_keywords,seo_description;{state},member_groups;{expert_legend},cssID,published;',
        'categorie'                   => '{title_legend},title,alias,type,beschreibung,imageSrc;{seo_options},seo_keywords,seo_description;{state},member_groups;{expert_legend},cssID,published;',
        'redirect'                    => '{title_legend},title,alias,type;{weiterleitungsziel},jumpTo;{state},member_groups;{expert_legend},cssID,published;',
        'page'                        => '{title_legend},title,alias,type;{weiterleitungsziel},jumpToPage;{expert_legend},cssID,published;',
    ),

    // Fields
    'fields' => array
    (
        'id' => array
    		(
    			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
    		),
        'pid' => array
    		(
    			'sql'                     => "int(10) NOT NULL default '0'"
    		),
    		'tstamp' => array
    		(
    			'sql'                     => "int(10) unsigned NOT NULL default '0'"
    		),
    		'sorting' => array
    		(
    			'sql'                     => "int(10) NOT NULL default '0'"
    		),
    		'language' => array
    		(
          'default'                 => 'de',
     			'sql'                     => "varchar(5) NOT NULL default ''"
    		),
        'title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_warengruppen']['title'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'decodeEntities'=>true),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'alias' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_warengruppen']['alias'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'alnum', 'doNotCopy'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
      			'sql'                     => "varchar(255) NOT NULL default ''",
            'save_callback' => array
            (
                array('tl_shop_warengruppen', 'generateAlias')
            )
        ),
        'type' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_warengruppen']['type'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('categorie' => 'Kategorie', 'redirect' => 'Weiterleitung', 'page'=>'Regul&auml;re Seite'),
            'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'w50'),
      			'sql'                     => "varchar(32) NOT NULL default ''"
        ),
        'published' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_warengruppen']['published'],
            'default'                 => 1,
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('doNotCopy'=>true, 'tl_class'=>'w50 m12'),
      			'sql'                     => "char(1) NOT NULL default ''"
        ),
        'seo_keywords' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_warengruppen']['seo_keywords'],
            'inputType'               => 'textarea',
            'search'                  => false,
            'eval'                    => array('style'=>'height: 60px;', 'mandatory'=>false, 'tl_class'=>'clr'),
      			'sql'                     => "text NOT NULL"
        ),
        'seo_description' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_warengruppen']['seo_description'],
            'inputType'               => 'textarea',
            'search'                  => false,
            'eval'                    => array('style'=>'height: 60px;', 'mandatory'=>false, 'tl_class'=>'clr'),
      			'sql'                     => "text NOT NULL"
        ),
        'beschreibung' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_warengruppen']['beschreibung'],
            'inputType'               => 'textarea',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'rte'=>'tinyMCE', 'tl_class'=>'clr'),
      			'sql'                     => "text NOT NULL"
        ),
        'imageSrc' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_warengruppen']['imageSrc'],
            'inputType'               => 'fileTree',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false,'fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true,'extensions'=>'jpg,png,gif'),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'jumpTo' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_warengruppen']['jumpTo'],
            'exclude'                 => true,
            'inputType'               => 'categorieTree',
            'eval'                    => array('mandatory'=>true, 'fieldType'=>'radio'),
      			'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'jumpToPage' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_warengruppen']['jumpToPage'],
            'exclude'                 => true,
            'inputType'               => 'pageTree',
            'eval'                    => array('mandatory'=>true, 'fieldType'=>'radio'),
      			'sql'                     => "int(10) NOT NULL default '0'"
        ),
        'member_groups' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_warengruppen']['member_groups'],
            'inputType'               => 'checkbox',
            'foreignKey'              => 'tl_member_group.name',
            'search'                  => false,
            'eval'                    => array('multiple'=>true, 'mandatory'=>false),
      			'sql'                     => "text NOT NULL"
        ),
        'cssID' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_shop_warengruppen']['cssID'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50'),
      			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
    )
);


/**
 * Class tl_shop_warengruppen
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class tl_shop_warengruppen extends Backend
{

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    /**
     * Add the breadcrumb menu
     */
    public function addBreadcrumb()
    {
        // Set a new node
        if (isset($_GET['node']))
        {
            $this->Session->set('tl_shop_warengruppen_node', $this->Input->get('node'));
            $this->redirect(preg_replace('/&node=[^&]*/', '', $this->Environment->request));
        }

        $intNode = $this->Session->get('tl_shop_warengruppen_node');

        if ($intNode < 1)
        {
            return;
        }

        $arrIds = array();
        $arrLinks = array();

        // Generate breadcrumb trail
        if ($intNode)
        {
            $intId = $intNode;

            do
            {
                $objPage = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE id=?")
                                ->limit(1)
                                ->execute($intId);

                if ($objPage->numRows < 1)
                {
                    // Currently selected page does not exits
                    if ($intId == $intNode)
                    {
                        $this->Session->set('tl_shop_warengruppen_node', 0);
                        return;
                    }

                    break;
                }

                $arrIds[] = $intId;

                // No link for the active page
                if ($objPage->id == $intNode)
                {
                    $arrLinks[] = $this->addIcon($objPage->row(), '', null, '', true) . ' ' . $objPage->title;
                }
                else
                {
                    $arrLinks[] = $this->addIcon($objPage->row(), '', null, '', true) . ' <a href="' . $this->addToUrl('node='.$objPage->id) . '">' . $objPage->title . '</a>';
                }

                // Do not show the mounted pages
                if (!$this->User->isAdmin && $this->User->hasAccess($objPage->id, 'pagemounts'))
                {
                    break;
                }

                $intId = $objPage->pid;
            }
            while ($intId > 0 && $objPage->type != 'catroot');
        }

        // Check whether the node is mounted
        if (!$this->User->isAdmin && !$this->User->hasAccess($arrIds, 'pagemounts'))
        {
            $this->Session->set('tl_shop_warengruppen_node', 0);

            $this->log('Page ID '.$intNode.' was not mounted', 'tl_shop_warengruppen addBreadcrumb', TL_ERROR);
            $this->redirect('contao/main.php?act=error');
        }

        // Limit tree
        $GLOBALS['TL_DCA']['tl_shop_warengruppen']['list']['sorting']['root'] = array($intNode);

        // Add root link
        $arrLinks[] = '<img src="system/themes/' . $this->getTheme() . '/images/pagemounts.gif" width="18" height="18" alt="" /> <a href="' . $this->addToUrl('node=0') . '">' . $GLOBALS['TL_LANG']['MSC']['filterAll'] . '</a>';
        $arrLinks = array_reverse($arrLinks);

        // Insert breadcrumb menu
        $GLOBALS['TL_DCA']['tl_shop_warengruppen']['list']['sorting']['breadcrumb'] .= '

<ul id="tl_breadcrumb">
  <li>' . implode(' &gt; </li><li>', $arrLinks) . '</li>
</ul>';
    }


    /**
     * Apply the root page language to new pages
     */
    public function setDefaultLanguage()
    {
        if ($this->Input->get('act') != 'create')
        {
            return;
        }

        if ($this->Input->get('pid') == 0)
        {
            $GLOBALS['TL_DCA']['tl_shop_warengruppen']['fields']['language']['default'] = $GLOBALS['TL_LANGUAGE'];
        }
        else
        {
            $objPage = $this->getPageDetails($this->Input->get('pid'));
            $GLOBALS['TL_DCA']['tl_shop_warengruppen']['fields']['language']['default'] = $objPage->rootLanguage;
        }
    }


    /**
     * Autogenerate a page alias if it has not been set yet
     * @param mixed
     * @param object
     * @return string
     */
    public function generateAlias($varValue, DataContainer $dc)
    {
        $autoAlias = false;

        // Generate alias if there is none
        if (!strlen($varValue))
        {
            $autoAlias = true;
            $varValue = standardize($dc->activeRecord->title);
        }

        $objAlias = $this->Database->prepare("SELECT id FROM tl_shop_warengruppen WHERE id=? OR alias=?")
                                   ->execute($dc->id, $varValue);

        // Check whether the page alias exists
        if ($objAlias->numRows > 1)
        {
            $arrDomains = array();

            while ($objAlias->next())
            {
                $_pid = $objAlias->id;
                $_type = '';

                do
                {
                    $objParentPage = $this->Database->prepare("SELECT id, pid, alias, type, dns FROM tl_shop_warengruppen WHERE id=?")
                                                    ->limit(1)
                                                    ->execute($_pid);

                    if ($objParentPage->numRows < 1)
                    {
                        break;
                    }

                    $_pid = $objParentPage->pid;
                    $_type = $objParentPage->type;
                }
                while ($_pid > 0 && $_type != 'catroot');

                $arrDomains[] = ($objParentPage->numRows && ($objParentPage->type == 'catroot' || $objParentPage->pid > 0)) ? $objParentPage->dns : '';
            }

            $arrUnique = array_unique($arrDomains);

            if (count($arrDomains) != count($arrUnique))
            {
                if (!$autoAlias)
                {
                    throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
                }

                $varValue .= '-' . $dc->id;
            }
        }

        return $varValue;
    }


    /**
     * Convert language tags to lowercase letters
     * @param mixed
     * @return string
     */
    public function languageToLower($varValue)
    {
        return strtolower($varValue);
    }

    /**
     * Check the sitemap alias
     * @param object
     * @param mixed
     * @throws Exception
     */
    public function checkFeedAlias($varValue, DataContainer $dc)
    {
        // No change or empty value
        if ($varValue == $dc->value || $varValue == '')
        {
            return $varValue;
        }

        $arrFeeds = $this->removeOldFeeds(true);

        // Alias exists
        if (array_search($varValue, $arrFeeds) !== false)
        {
            throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
        }

        return $varValue;
    }


    /**
     * Prevent circular references
     * @param mixed
     * @param object
     */
    public function checkJumpTo($varValue, DataContainer $dc)
    {
        if ($varValue == $dc->id)
        {
            throw new Exception($GLOBALS['TL_LANG']['ERR']['circularReference']);
        }

        return $varValue;
    }

    /**
     * Return all page layouts grouped by theme
     * @return array
     */
    public function getPageLayouts()
    {
        $objLayout = $this->Database->execute("SELECT l.id, l.name, t.name AS theme FROM tl_layout l LEFT JOIN tl_theme t ON l.pid=t.id ORDER BY t.name, l.name");

        if ($objLayout->numRows < 1)
        {
            return array();
        }

        $return = array();

        while ($objLayout->next())
        {
            $return[$objLayout->theme][$objLayout->id] = $objLayout->name;
        }

        return $return;
    }


    /**
     * Add an image to each page in the tree
     * @param array
     * @param string
     * @param object
     * @param string
     * @param boolean
     * @return string
     */
    public function addIcon($row, $label, DataContainer $dc=null, $imageAttribute='', $blnReturnImage=false)
    {
        $sub = 0;
        $image = $row['type'] . '.png';

        if(!$row['published']) {
            $image = 'hide.png';
        }

        $image = '/system/modules/acquistoShop/assets/gfx/' . $image;
        return '<a href="'.$this->generateFrontendUrl($row).'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['view']).'"' . (($dc->table != 'tl_page') ? ' class="tl_gray"' : '') . LINK_NEW_WINDOW . '>'.$this->generateImage($image, '', $imageAttribute).'</a> '.$label;
    }


    /**
     * Return the edit page button
     * @param array
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @return string
     */
    public function editPage($row, $href, $label, $title, $icon, $attributes)
    {
        return ($this->User->isAdmin || ($this->User->hasAccess($row['type'], 'alpty') && $this->User->isAllowed(1, $row))) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
    }


    /**
     * Return the copy page button
     * @param array
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @return string
     */
    public function copyPageWithSubpages($row, $href, $label, $title, $icon, $attributes, $table)
    {
        if ($GLOBALS['TL_DCA'][$table]['config']['closed'])
        {
            return '';
        }

        $objSubpages = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE pid=?")
                                      ->limit(1)
                                      ->execute($row['id']);

        return ($objSubpages->numRows && ($this->User->isAdmin || ($this->User->hasAccess($row['type'], 'alpty') && $this->User->isAllowed(2, $row)))) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
    }


    /**
     * Return the cut page button
     * @param array
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @return string
     */
    public function cutPage($row, $href, $label, $title, $icon, $attributes)
    {
        return ($this->User->isAdmin || ($this->User->hasAccess($row['type'], 'alpty') && $this->User->isAllowed(2, $row))) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
    }


    /**
     * Return the paste page button
     * @param object
     * @param array
     * @param string
     * @param boolean
     * @param array
     * @return string
     */
    public function pastePage(DataContainer $dc, $row, $table, $cr, $arrClipboard=false)
    {
        $disablePA = false;
        $disablePI = false;

        // Disable all buttons if there is a circular reference
        if ($arrClipboard !== false && ($arrClipboard['mode'] == 'cut' && ($cr == 1 || $arrClipboard['id'] == $row['id']) || $arrClipboard['mode'] == 'cutAll' && ($cr == 1 || in_array($row['id'], $arrClipboard['id']))))
        {
            $disablePA = true;
            $disablePI = true;
        }

        // Check permissions if the user is not an administrator
        if (!$this->User->isAdmin)
        {
            // Disable "paste into" button if there is no permission 2 for the current page
            if (!$disablePI && !$this->User->isAllowed(2, $row))
            {
                $disablePI = true;
            }

            $objPage = $this->Database->prepare("SELECT * FROM " . $table . " WHERE id=?")
                                      ->limit(1)
                                      ->execute($row['pid']);

            // Disable "paste after" button if there is no permission 2 for the parent page
            if (!$disablePA && $objPage->numRows)
            {
                if (!$this->User->isAllowed(2, $objPage->row()))
                {
                    $disablePA = true;
                }
            }

            // Disable "paste after" button if the parent page is a root page and the user is not an administrator
            if (!$disablePA && ($row['pid'] < 1 || in_array($row['id'], $dc->rootIds)))
            {
                $disablePA = true;
            }
        }

        // Return the buttons
        $imagePasteAfter = $this->generateImage('pasteafter.gif', sprintf($GLOBALS['TL_LANG'][$table]['pasteafter'][1], $row['id']), 'class="blink"');
        $imagePasteInto = $this->generateImage('pasteinto.gif', sprintf($GLOBALS['TL_LANG'][$table]['pasteinto'][1], $row['id']), 'class="blink"');

        if ($row['id'] > 0)
        {
            $return = $disablePA ? $this->generateImage('pasteafter_.gif', '', 'class="blink"').' ' : '<a href="'.$this->addToUrl('act='.$arrClipboard['mode'].'&amp;mode=1&amp;pid='.$row['id'].(!is_array($arrClipboard['id']) ? '&amp;id='.$arrClipboard['id'] : '')).'" title="'.specialchars(sprintf($GLOBALS['TL_LANG'][$table]['pasteafter'][1], $row['id'])).'" onclick="Backend.getScrollOffset();">'.$imagePasteAfter.'</a> ';
        }

        return $return.($disablePI ? $this->generateImage('pasteinto_.gif', '', 'class="blink"').' ' : '<a href="'.$this->addToUrl('act='.$arrClipboard['mode'].'&amp;mode=2&amp;pid='.$row['id'].(!is_array($arrClipboard['id']) ? '&amp;id='.$arrClipboard['id'] : '')).'" title="'.specialchars(sprintf($GLOBALS['TL_LANG'][$table]['pasteinto'][1], $row['id'])).'" onclick="Backend.getScrollOffset();">'.$imagePasteInto.'</a> ');
    }


    /**
     * Return the delete page button
     * @param array
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @return string
     */
    public function deletePage($row, $href, $label, $title, $icon, $attributes)
    {
        $root = func_get_arg(7);
        return ($this->User->isAdmin || ($this->User->hasAccess($row['type'], 'alpty') && $this->User->isAllowed(3, $row) && !in_array($row['id'], $root))) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
    }

    /**
     * Recursively add pages to a sitemap
     * @param object
     */
    public function updateSitemap(DataContainer $dc)
    {
        $this->import('Automator');
        $this->Automator->generateSitemap($dc->id);
    }


    /**
     * Return the "toggle visibility" button
     * @param array
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (strlen($this->Input->get('tid')))
        {
            $this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
            $this->redirect($this->getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_shop_warengruppen::published', 'alexf'))
        {
            return '';
        }

        $href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

        if (!$row['published'])
        {
            $icon = 'invisible.gif';
        }

        $objPage = $this->Database->prepare("SELECT * FROM tl_shop_warengruppen WHERE id=?")
                                  ->limit(1)
                                  ->execute($row['id']);

        if (!$this->User->isAdmin && !$this->User->isAllowed(2, $objPage->row()))
        {
            return $this->generateImage($icon) . ' ';
        }

        return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
    }


    /**
     * Disable/enable a user group
     * @param integer
     * @param boolean
     */
    public function toggleVisibility($intId, $blnVisible)
    {
        // Check permissions to edit
        $this->Input->setGet('id', $intId);
        $this->Input->setGet('act', 'toggle');
        $this->checkPermission();

        // Check permissions to publish
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_shop_warengruppen::published', 'alexf'))
        {
            $this->log('Not enough permissions to publish/unpublish page ID "'.$intId.'"', 'tl_shop_warengruppen toggleVisibility', TL_ERROR);
            $this->redirect('contao/main.php?act=error');
        }

        $this->createInitialVersion('tl_shop_warengruppen', $intId);

        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_shop_warengruppen']['fields']['published']['save_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_shop_warengruppen']['fields']['published']['save_callback'] as $callback)
            {
                $this->import($callback[0]);
                $blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
            }
        }

        // Update the database
        $this->Database->prepare("UPDATE tl_shop_warengruppen SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
                       ->execute($intId);

        $this->createNewVersion('tl_shop_warengruppen', $intId);
    }
}

?>