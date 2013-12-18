<?php
/**
 * @package 	Plugin TemplateAssigner for Joomla! 3.X
 * @version 	0.0.1
 * @author 		Function90.com
 * @copyright 	C) 2013- Function90.com
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
**/

defined('_JEXEC') or die;

class plgSystemTemplateassigner extends JPlugin
{
	public function onAfterInitialise()
	{
		$mapping = $this->params->get('group_template_mapping', '');
		if(empty($mapping)){
			return true;  // do nothing
		}
		
		$user = JFactory::getUser();
		
		
			// only first user group
			$groups = $user->groups;
			$group = array_shift($groups);
		if(!isset($mapping->$group)){
			return true;
		}
		
		JFactory::getApplication()->input->set('templateStyle', $mapping->$group);
	}
}
