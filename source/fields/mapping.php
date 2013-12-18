<?php
/**
 * @package 	Plugin TemplateAssigner for Joomla! 3.X
 * @version 	0.0.1
 * @author 		Function90.com
 * @copyright 	C) 2013- Function90.com
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
**/
defined('_JEXEC') or die;

class FTAFormFieldMapping extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'Mapping';

	/**
	 * Method to get the user group field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		$groups = $this->getJoomlaUserGroups();
		$styles = $this->getTemplateStyles();
		
		$html = '';
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('PLG_SYSTEM_TEMPLATE_ASSIGNER_SELECT_OPTION'));
		foreach ($styles as $style)
		{
				$options[] = JHtml::_('select.option', $style->id, $style->title);
		}
			
		foreach($groups as $group){
			$html .= '<div class="control-group">
						<div class="control-label">
							<label>'.$group->title.'</label>					
						</div>
						<div class="controls">';
			$value = isset($this->value[$group->id]) ? $this->value[$group->id] : '';
			
			$html .= JHtml::_('select.genericlist', $options, $this->name.'['.$group->id.']', null, 'value', 'text', $value, $this->id.'_'.$group->id);
			$html .="</div></div>";
		}
		
		return $html;
	}
	
	public function getJoomlaUserGroups()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.*, COUNT(DISTINCT b.id) AS level')
			->from($db->quoteName('#__usergroups') . ' AS a')
			->join('LEFT', $db->quoteName('#__usergroups') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt')
			->group('a.id, a.title, a.lft, a.rgt, a.parent_id')
			->order('a.lft ASC');
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	public function getTemplateStyles()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Build the query.
		$query->select('s.id, s.title, e.name as name, s.template')
			->from('#__template_styles as s')
			->where('s.client_id = 0')
			->order('template')
			->order('title');
		
		$query->join('LEFT', '#__extensions as e on e.element=s.template')
			->where('e.enabled=1')
			->where($db->quoteName('e.type') . '=' . $db->quote('template'));

		// Set the query and load the styles.
		$db->setQuery($query);
		return $db->loadObjectList();
	}
}
