<?php
/**
 * @version    SVN: <svn_id>
 * @package    Tjfields
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die();

jimport('joomla.application.component.modeladmin');

/**
 * Item Model for an City.
 *
 * @package     Tjfields
 * @subpackage  com_tjfields
 * @since       2.2
 */
class TjfieldsModelCity extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_TJFIELDS';

	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable    A database object
	 */
	public function getTable($type = 'City', $prefix = 'TjfieldsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      An optional ordering field.
	 * @param   boolean  $loadData  An optional direction (asc|desc).
	 *
	 * @return  JForm    $form      A JForm object on success, false on failure
	 *
	 * @since   2.2
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_tjfields.city', 'city', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	$data  The data for the form.
	 *
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_tjfields.edit.city.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  $item  Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			// Do any procesing on fields here if needed
		}

		return $item;
	}

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   JTable  $table  A JTable object.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id))
		{
			// Set ordering to the last item if not set
			if (@$table->ordering === '')
			{
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__tj_city');
				$max = $db->loadResult();
				$table->ordering = $max + 1;
			}
		}
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return   mixed		The user id on success, false on failure.
	 *
	 * @since	1.6
	 */

	public function save($data)
	{
		$com_params = JComponentHelper::getParams('com_tjfields');
		$id = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('city.id');
		$state = (!empty($data['com_tjfields'])) ? 1 : 0;

		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		if ($id)
		{
			// Check the user can edit this item.
			$authorised = $user->authorise('core.edit', 'com_tjfields') || $authorised = $user->authorise('core.edit.own', 'com_tjfields');

			// The user cannot edit the state of the item.
			if ($user->authorise('core.edit.state', 'com_tjfields') !== true && $state == 1)
			{
				$data['com_tjfields'] = 0;
			}
		}
		else
		{
			// Check the user can create new items in this section.
			$authorised = $user->authorise('core.create', 'com_tjfields');

			// The user cannot edit the state of the item.
			if ($user->authorise('core.edit.state', 'com_tjfields') !== true && $state == 1)
			{
				$data['com_tjfields'] = 0;
			}
		}

		if ($authorised !== true)
		{
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));

			return false;
		}

		$table = $this->getTable();

		// Bind data
		if (!$table->bind($data))
		{
			$this->setError($table->getError());

			return false;
		}

		// Validate
		if (!$table->check())
		{
			$this->setError($table->getError());

			return false;
		}

		// Validate to check for duplication
		if (!$table->checkDuplicateCity())
		{
			$app->enqueueMessage(JText::_('COM_TJFIELDS_CITY_EXISTS_IN_REGION_COUNTRY'), 'warning');
		}

		// Attempt to save data
		if (parent::save($data))
		{
			return true;
		}

		return false;
	}

	public function getRegionsList($countryId)
	{
		if (!$countryId)
		{
			return false;
		}

		$db = JFactory::getDBO();

		$query = "SELECT r.id, r.region, r.region_jtext
		 FROM #__tj_region AS r
		 LEFT JOIN #__tj_country AS c ON r.country_id = c.id
		 WHERE c.id=" . $countryId . "
		ORDER BY r.ordering, r.region";

		$db->setQuery($query);
		$regions = $db->loadObjectList();

		// Load lang file for regions
		$lang = JFactory::getLanguage();
		$lang->load('tjgeo.regions', JPATH_SITE, null, false, true);

		foreach ($regions as $r)
		{
			if ($lang->hasKey(strtoupper($r->region_jtext)))
			{
				$r->region = JText::_($r->region_jtext);
			}
		}

		return $regions;
	}

	public function fixDB2()
	{
		ini_set('memory_limit', '512M');

		if (!ini_get('safe_mode'))
		{
			set_time_limit ('300');
		}

		$db = JFactory::getDBO();

		/*$query = "SELECT c.id, c.country_code
		 FROM #__tj_country AS c
		ORDER BY c.country";
		$db->setQuery($query);
		$countries = $db->loadObjectList();

		foreach ($countries as $c)
		{
			echo $c->country_code  . ' - ';

			echo $query = "UPDATE #__tj_city3 AS ct
			SET country_id = " . $c->id . "
		    WHERE ct.country_code = '" . $c->country_code . "'";

			echo ' <br/> ';

			$db->setQuery($query);
			$db->query();
		}*/

		$query = "SELECT ct.*
		 FROM #__tj_city3 AS ct";
		$db->setQuery($query);

		$cities = $db->loadObjectList();

		foreach ($cities as $ct)
		{
			//echo $ct->city  . ' - ';

			//echo
			$query = 'INSERT INTO `#__tj_city4`
			(`id`, `city_id`, `city`, `country_id`, `region_id`)
			VALUES
			( ' . $ct->city_id . ', ' . $ct->city_id . ', ' . $db->quote($ct->city) . ', ' . $ct->country_id . ', ' . $ct->region_id . ')';

			//echo ' <br/> ';

			$db->setQuery($query);
			$db->query();
		}

	}
}
