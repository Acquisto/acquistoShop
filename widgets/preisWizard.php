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
 * Class OptionWizard
 *
 * Provide methods to handle form field option.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class preisWizard extends Widget
{

    /**
     * Submit user input
     * @var boolean
     */
    protected $blnSubmitInput = true;

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'be_widget';

    /**
     * Template
     * @var array
     */
    private $memberGroupsArray = array();


    /**
     * Add specific attributes
     * @param string
     * @param mixed
     */
    public function __set($strKey, $varValue)
    {
        switch ($strKey)
        {
            case 'value':
                $this->varValue = deserialize($varValue);
                break;

            case 'mandatory':
                $this->arrConfiguration['mandatory'] = $varValue ? true : false;
                break;

            case 'maxlength':
                $this->arrAttributes[$strKey] = ($varValue > 0) ? $varValue : '';
                break;

            default:
                parent::__set($strKey, $varValue);
                break;
        }
    }


    /**
     * Validate input and set value
     */
    public function validate()
    {
        $mandatory = $this->mandatory;
        $options = deserialize($this->getPost($this->strName));

        // Check labels only (values can be empty)
        if (is_array($options))
        {
            foreach ($options as $key=>$option)
            {
                $options[$key]['label'] = trim($option['label']);
                $options[$key]['value'] = trim($option['value']);
                $options[$key]['specialcosts'] = trim($option['specialcosts']);

                if (strlen($options[$key]['label']))
                {
                    $this->mandatory = false;
                }
            }
        }

        $varInput = $this->validator($options);

        if (!$this->hasErrors())
        {
            $this->varValue = $varInput;
        }

        // Reset the property
        if ($mandatory)
        {
            $this->mandatory = true;
        }
    }


    /**
     * Generate the widget and return it as string
     * @return string
     */
    public function generate()
    {
        $this->Import('Database');

        $arrButtons = array('copy', 'up', 'down', 'delete');
        $strCommand = 'cmd_' . $this->strField;

        // Change the order
        if ($this->Input->get($strCommand) && is_numeric($this->Input->get('cid')) && $this->Input->get('id') == $this->currentRecord)
        {
            switch ($this->Input->get($strCommand))
            {
                case 'copy':
                    array_insert($this->varValue, $this->Input->get('cid'), array($this->varValue[$this->Input->get('cid')]));
                    break;

                case 'up':
                    $this->varValue = array_move_up($this->varValue, $this->Input->get('cid'));
                    break;

                case 'down':
                    $this->varValue = array_move_down($this->varValue, $this->Input->get('cid'));
                    break;

                case 'delete':
                    $this->varValue = array_delete($this->varValue, $this->Input->get('cid'));
                    break;
            }

            $this->Database->prepare("UPDATE " . $this->strTable . " SET " . $this->strField . "=? WHERE id=?")
                           ->execute(serialize($this->varValue), $this->currentRecord);

            $this->redirect(preg_replace('/&(amp;)?cid=[^&]*/i', '', preg_replace('/&(amp;)?' . preg_quote($strCommand, '/') . '=[^&]*/i', '', $this->Environment->request)));
        }

        // Make sure there is at least an empty array
        if (!is_array($this->varValue) || !$this->varValue[0])
        {
            $this->varValue = array(array(''));
        }

        // Begin table
        $return .= '<table cellspacing="0" cellpadding="0" class="tl_optionwizard" id="ctrl_'.$this->strId.'" summary="Field wizard">
  <thead>
    <tr>
      <th>Menge</th>
      <th>Preis</th>
      <th>Sonderpreis</th>
      <th>Mitgliedergruppe</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody>';

        $tabindex = 0;

        // Add fields
        for ($i=0; $i<count($this->varValue); $i++)
        {
            $return .= '
    <tr>
      <td><input type="text" name="'.$this->strId.'['.$i.'][value]" id="'.$this->strId.'_value_'.$i.'" class="tl_text_2" tabindex="'.++$tabindex.'" value="'.specialchars($this->varValue[$i]['value']).'" /></td>
      <td><input type="text" name="'.$this->strId.'['.$i.'][label]" id="'.$this->strId.'_label_'.$i.'" class="tl_text_2" tabindex="'.++$tabindex.'" value="'.specialchars($this->varValue[$i]['label']).'" /></td>
      <td><input type="text" name="'.$this->strId.'['.$i.'][specialcosts]" id="'.$this->strId.'_specialcosts_'.$i.'" class="tl_text_2" tabindex="'.++$tabindex.'" value="'.specialchars($this->varValue[$i]['specialcosts']).'" /></td>
      <td><select name="'.$this->strId.'['.$i.'][group]" class="tl_select" style="width: 140px;">' . $this->memberGroups($this->varValue[$i]['group']) . '</select></td>
';
//      <td><input type="checkbox" name="'.$this->strId.'['.$i.'][default]" id="'.$this->strId.'_default_'.$i.'" class="fw_checkbox" tabindex="'.$tabindex++.'" value="1"'.($this->varValue[$i]['default'] ? ' checked="checked"' : '').'> <label for="'.$this->strId.'_default_'.$i.'">Sonderpreis</label></td>

            // Add row buttons
            $return .= '
      <td style="white-space:nowrap; padding-left:3px;">';

            foreach ($arrButtons as $button)
            {
                $return .= '<a href="'.$this->addToUrl('&amp;'.$strCommand.'='.$button.'&amp;cid='.$i.'&amp;id='.$this->currentRecord).'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['ow_'.$button]).'" onclick="Backend.optionsWizard(this, \''.$button.'\', \'ctrl_'.$this->strId.'\'); return false;">'.$this->generateImage($button.'.gif', $GLOBALS['TL_LANG']['MSC']['ow_'.$button]).'</a> ';
            }

            $return .= '</td>
    </tr>';
        }

        return $return.'
  </tbody>
  </table>';
    }

    private function memberGroups($selectedID) {
        if(!count($this->memberGroupsArray)) {
            $this->memberGroupsArray[] = (object) array('id' => 0, 'name' => 'G&auml;ste');

            $memberGroupsSQL = $this->Database->prepare("SELECT id,name FROM tl_member_group")->execute();
            while($memberGroupsSQL->next()) {
                $this->memberGroupsArray[] = (object) array('id' => $memberGroupsSQL->id, 'name' => $memberGroupsSQL->name);
            }
        }

        if(count($this->memberGroupsArray)) {
            foreach($this->memberGroupsArray as $value) {
                if($selectedID == $value->id) {
                    $optionsStr .= '<option value="'.$value->id.'" selected="selected">'.$value->name.'</option>';
                } else {
                    $optionsStr .= '<option value="'.$value->id.'">'.$value->name.'</option>';
                }
            }
        }

        return $optionsStr;
    }
}

?>