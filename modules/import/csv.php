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
 * @package    News
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

class csv extends Controller {
    protected $exportModule = 'CSV';
    public $exportID;

    public function __construct()
    {
        parent::__construct();
    }

    public function getSettingsData() {

    }

    public function getImportInfo() {
        return $this->exportModule;
    }

    public function getImportForm() {
        $this->loadLanguageFile('tl_newsletter_recipients');

		    if ($this->Input->Get('key') != 'importData')
		    {
            return '';
		    }

		    $this->import('BackendUser', 'User');
		    $class = $this->User->uploader;

    		// See #4086
    		if (!class_exists($class))
    		{
    			$class = 'FileUpload';
    		}

    		$objUploader = new $class();

		    // Import CSS
		    if ($this->Input->Post('FORM_SUBMIT') == 'tl_produkte_import')
		    {
            $arrUploaded = $objUploader->uploadTo('system/tmp');

            if(empty($arrUploaded))
			      {
				        // \Message::addError($GLOBALS['TL_LANG']['ERR']['all_fields']);
				        $this->reload();
            }

            $time = time();
            $intTotal = 0;
            $intInvalid = 0;

      			foreach ($arrUploaded as $strCsvFile)
            {
                $objFile = new File($strCsvFile);

				        if ($objFile->extension != 'csv')
        				{
          					// \Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $objFile->extension));
          					continue;
        				}

        				// Get separator
        				switch ($this->Input->Post('separator'))
        				{
        					case 'semicolon':
        						$strSeparator = ';';
        						break;

        					case 'tabulator':
        						$strSeparator = "\t";
        						break;

        					case 'linebreak':
        						$strSeparator = "\n";
        						break;

        					default:
        						$strSeparator = ',';
        						break;
        				}

				$arrRecipients = array();
				$resFile = $objFile->handle;

				while(($arrRow = @fgetcsv($resFile, null, $strSeparator)) !== false)
				{
					$arrRecipients = array_merge($arrRecipients, $arrRow);
				}

				$arrRecipients = array_filter(array_unique($arrRecipients));

				foreach ($arrRecipients as $strRecipient)
				{
					// Skip invalid entries
					if (!\Validator::isEmail($strRecipient))
					{
						$this->log('Recipient address "' . $strRecipient . '" seems to be invalid and has been skipped', 'Newsletter importRecipients()', TL_ERROR);

						++$intInvalid;
						continue;
					}

					// Check whether the e-mail address exists
					$objRecipient = $this->Database->prepare("SELECT COUNT(*) AS count FROM tl_newsletter_recipients WHERE pid=? AND email=?")
												   ->execute(\Input::get('id'), $strRecipient);

					if ($objRecipient->count < 1)
					{
						$this->Database->prepare("INSERT INTO tl_newsletter_recipients SET pid=?, tstamp=$time, email=?, active=1")
									   ->execute(\Input::get('id'), $strRecipient);

						++$intTotal;
					}
				}
			}

			\Message::addConfirmation(sprintf($GLOBALS['TL_LANG']['tl_newsletter_recipients']['confirm'], $intTotal));

			if ($intInvalid > 0)
			{
				\Message::addInfo(sprintf($GLOBALS['TL_LANG']['tl_newsletter_recipients']['invalid'], $intInvalid));
			}

			setcookie('BE_PAGE_OFFSET', 0, 0, '/');
			$this->reload();
		}

		// Return form
		return '
<div id="tl_buttons">
<a href="'.ampersand(str_replace('&key=import', '', \Environment::get('request'))).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']).'" accesskey="b">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['tl_newsletter_recipients']['import'][1].'</h2>
'.\Message::generate().'
<form action="'.ampersand(\Environment::get('request'), true).'" id="tl_produkte_import" class="tl_form" method="post" enctype="multipart/form-data">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_recipients_import">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">
<input type="hidden" name="MAX_FILE_SIZE" value="'.$GLOBALS['TL_CONFIG']['maxFileSize'].'">

<div class="tl_tbox">
  <h3><label for="separator">'.$GLOBALS['TL_LANG']['MSC']['separator'][0].'</label></h3>
  <select name="separator" id="separator" class="tl_select" onfocus="Backend.getScrollOffset()">
    <option value="comma">'.$GLOBALS['TL_LANG']['MSC']['comma'].'</option>
    <option value="semicolon">'.$GLOBALS['TL_LANG']['MSC']['semicolon'].'</option>
    <option value="tabulator">'.$GLOBALS['TL_LANG']['MSC']['tabulator'].'</option>
    <option value="linebreak">'.$GLOBALS['TL_LANG']['MSC']['linebreak'].'</option>
  </select>'.(($GLOBALS['TL_LANG']['MSC']['separator'][1] != '') ? '
  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['MSC']['separator'][1].'</p>' : '').'
  <h3>'.$GLOBALS['TL_LANG']['MSC']['source'][0].'</h3>'.$objUploader->generateMarkup().(isset($GLOBALS['TL_LANG']['MSC']['source'][1]) ? '
  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['MSC']['source'][1].'</p>' : '').'
</div>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
  <input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_newsletter_recipients']['import'][0]).'">
</div>

</div>
</form>';


    }

    public function compile()
    {

    }
}


?>