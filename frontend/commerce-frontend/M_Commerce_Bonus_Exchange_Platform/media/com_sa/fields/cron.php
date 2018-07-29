<?php
/**
 * @version    SVN: <svn_id>
 * @package    SocialAd
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die();

jimport('joomla.form.formfield');
/**
 * Class for custom cron element
 *
 * @since  1.0.0
 */
class JFormFieldCron extends JFormField
{
	protected $type = 'cron';

		/**
		 * Function to genarate html of custom element
		 *
		 * @return  HTML
		 *
		 * @since  1.0.0
		 */
	public function getInput()
	{
		$params = JComponentHelper::getParams('com_socialads');
		$this->cron_key = $params->get('cron_key');
		$value = $this->hint;
		$value1 = str_replace("|", "&", $value);
		$cron = JRoute::_(JUri::root() . 'index.php?option=com_socialads' . $value1 . '&tmpl=component&pkey=' . $this->cron_key);

		$return = '<input type="text" class="input input-xxlarge" onclick="this.select();" value="'.$cron.'" aria-invalid="false">';

		return $return;
	}
}
