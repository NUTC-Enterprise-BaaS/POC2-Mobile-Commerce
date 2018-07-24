<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('text');

class JFormFieldFSSStatic extends JFormFieldText
{
	protected $type = 'FSSStatic';

	protected function getLabel()
	{
		return "<div class='fsj_form_subsection_header'>".parent::getLabel() . "</div>";	
	}
	protected function getInput()
	{
		if (isset($this->element->content)) return $this->element->content;
	}
}
