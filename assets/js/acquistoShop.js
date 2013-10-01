/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class Request.acquistoShop
 *
 * Extend the basic Request.JSON class.
 * @copyright  Leo Feyer 2011-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Backend
 */

/**
 * Class AjaxRequest
 *
 * Provide methods to handle Ajax requests.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Backend
 */
var acquistoShop =
{
    /**
     * Toggle the page tree input field
     * @param object
     * @param string
     * @param string
     * @param string
     * @param integer
     * @return boolean
     */
    toggleCategorietree: function (el, id, field, name, level) {
        el.blur();
        var item = $(id);
        var image = $(el).getFirst('img');

        if (item) {
            if (item.getStyle('display') == 'none') {
                item.setStyle('display', 'inline');
                image.src = image.src.replace('folPlus.gif', 'folMinus.gif');
                $(el).title = CONTAO_COLLAPSE;
                new Request.Contao({field:el}).post({'action':'toggleCategorietree', 'id':id, 'state':1, 'REQUEST_TOKEN':REQUEST_TOKEN});
            } else {
                item.setStyle('display', 'none');
                image.src = image.src.replace('folMinus.gif', 'folPlus.gif');
                $(el).title = CONTAO_EXPAND;
                new Request.Contao({field:el}).post({'action':'toggleCategorietree', 'id':id, 'state':0, 'REQUEST_TOKEN':REQUEST_TOKEN});
            }
            return false;
        }

        new Request.Contao({
            field: el,
            evalScripts: true,
            onRequest: AjaxRequest.displayBox(CONTAO_LOADING + ' …'),
            onSuccess: function(txt, json) {
                var li = new Element('li', {
                    'id': id,
                    'class': 'parent',
                    'styles': {
                        'display': 'inline'
                    }
                });

                var ul = new Element('ul', {
                    'class': 'level_' + level,
                    'html': txt
                }).inject(li, 'bottom');

                li.inject($(el).getParent('li'), 'after');
                $(el).title = CONTAO_COLLAPSE;
                image.src = image.src.replace('folPlus.gif', 'folMinus.gif');
                AjaxRequest.hideBox();

                // HOOK
                window.fireEvent('ajax_change');
               }
        }).post({'action':'loadCategorietree', 'id':id, 'level':level, 'field':field, 'name':name, 'state':1, 'REQUEST_TOKEN':REQUEST_TOKEN});

        return false;
    },
    
	/**
	 * Show all pagetree and filetree nodes
	 * @param object
	 * @param string
	 */
	showTreeBody: function(el, id) {
		el.blur();
		$(id).setStyle('display', ($(el).checked ? 'inline' : 'none'));
	},

	/**
	 * Hide all pagetree and filetree nodes
	 */
	hideTreeBody: function() {
		var lists = $$('ul');
		var parent = null;

		for (var i=0; i<lists.length; i++) {
			if (lists[i].hasClass('mandatory')) {
				$('ctrl_' + lists[i].id).checked = 'checked';
			} else if (lists[i].hasClass('tl_listing') && (parent = lists[i].getFirst('li').getNext('li')) && parent.hasClass('parent')) {
				parent.setStyle('display', 'none');
			}
		}
	}    
};