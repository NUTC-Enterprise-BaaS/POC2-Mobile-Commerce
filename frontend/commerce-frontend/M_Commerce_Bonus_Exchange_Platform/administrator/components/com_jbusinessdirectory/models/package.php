<?php


defined('_JEXEC') or die;
jimport('joomla.application.component.modeladmin');
/**
 * Company Model for Companies.
 *
 */
class JBusinessDirectoryModelPackage extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_JBUSINESSDIRECTORY_PACKAGE';

	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	protected $_context		= 'com_jbusinessdirectory.package';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object	A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 */
	protected function canDelete($record)
	{
		return true;
	}

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object	A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 */
	protected function canEditState($record)
	{
		return true;
	}

	/**
	 * Returns a Table object, always creating it
	 *
	 * @param   type	The table type to instantiate
	 * @param   string	A prefix for the table class name. Optional.
	 * @param   array  Configuration array for model. Optional.
	 * @return  JTable	A database object
	*/
	public function getTable($type = 'Package', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');

		// Load the User state.
		$id = JRequest::getInt('id');
		$this->setState('package.id', $id);
	}

	/**
	 * Method to get a menu item.
	 *
	 * @param   integer	The id of the menu item to get.
	 *
	 * @return  mixed  Menu item data object on success, false on failure.
	 */
	public function &getItem($itemId = null)
	{
		$itemId = (!empty($itemId)) ? $itemId : (int) $this->getState('package.id');
		$false	= false;

		// Get a menu item row instance.
		$table = $this->getTable();

		// Attempt to load the row.
		$return = $table->load($itemId);

		// Check for a table object error.
		if ($return === false && $table->getError())
		{
			$this->setError($table->getError());
			return $false;
		}

		$properties = $table->getProperties(1);
		$value = JArrayHelper::toObject($properties, 'JObject');
		
		
		$value->special_from_date = JBusinessUtil::convertToFormat($value->special_from_date);
		$value->special_to_date = JBusinessUtil::convertToFormat($value->special_to_date);
		
		return $value;
	}
	
	
	/**
	 * Method to get the menu item form.
	 *
	 * @param   array  $data		Data for the form.
	 * @param   boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return  JForm	A JForm object on success, false on failure
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		exit;
		// The folder and element vars are passed when saving the form.
		if (empty($data))
		{
			$item		= $this->getItem();
			// The type should already be set.
		}
		// Get the form.
		$form = $this->loadForm('com_jbusinessdirectory.package', 'item', array('control' => 'jform', 'load_data' => $loadData), true);
		if (empty($form))
		{
			return false;
		}
		
		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_jbusinessdirectory.edit.package.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}
	
	
	/**
	 * Method to save the form data.
	 *
	 * @param   array  The form data.
	 * @return  boolean  True on success.
	 */
	public function save($data)
	{
		
		$id	= (!empty($data['id'])) ? $data['id'] : (int) $this->getState('package.id');
		$isNew = true;
		
		$data["special_from_date"] = JBusinessUtil::convertToMysqlFormat($data["special_from_date"]);
		$data["special_to_date"] = JBusinessUtil::convertToMysqlFormat($data["special_to_date"]);
		
		if($data["price"]==0){
			$data["days"] = 0;
			$data["time_amount"] = 0;
		}else{
			$days = 0;
			
			switch($data["time_unit"]){
				case "D":
					$days = $data["time_amount"]; 
					break;
				case "W":
					$days = $data["time_amount"] * 7;
					break;
				case "M":
					$days = $data["time_amount"] * 30;
					break;
				case "Y":
					$days = $data["time_amount"] * 365;
					break;
				default:
					$days = $data["time_amount"];		
			}
			
			$data["days"] = $days;
			
		}
		
		$defaultLng = JFactory::getLanguage()->getTag();
		$description = 	JRequest::getVar( 'description_'.$defaultLng, '', 'post', 'string', JREQUEST_ALLOWHTML);
		$name = JRequest::getVar( 'name_'.$defaultLng, '', 'post', 'string', JREQUEST_ALLOWHTML);
		
		if(!empty($description) && empty($data["description"]))
			$data["description"] = $description;
		
		if(!empty($name) && empty($data["name"]))
			$data["name"] = $name;
		
		// Get a row instance.
		$table = $this->getTable();

		// Load the row if saving an existing item.
		if ($id > 0)
		{
			$table->load($id);
			$isNew = false;
		}

		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($table->getError());
			return false;
		}

		// Check the data.
		if (!$table->check())
		{
			$this->setError($table->getError());
			return false;
		}

		// Store the data.
		if (!$table->store())
		{
			$this->setError($table->getError());
			return false;
		}

		$this->setState('package.id', $table->id);
				
		
		$table->insertRelations( $table->id,$data["features"]);

		JBusinessDirectoryTranslations::saveTranslations(PACKAGE_TRANSLATION, $table->id, 'description_');
		
		// Clean the cache
		$this->cleanCache();

		return true;
	}

	
	function changeState(){
		$this->populateState();
		$packagesTable = $this->getTable("Package");
		return $packagesTable->changeState($this->getState('package.id'));
	}

	function changePopularState(){
		$this->populateState();
		$packagesTable = $this->getTable("Package");
		return $packagesTable->changePopularState($this->getState('package.id'));
	}
	

	function getStates(){
		$states = array();
		$state = new stdClass();
		$state->value = 0;
		$state->text = JTEXT::_("LNG_INACTIVE");
		$states[] = $state;
		$state = new stdClass();
		$state->value = 1;
		$state->text = JTEXT::_("LNG_ACTIVE");
		$states[] = $state;
	
		return $states;
	}

	function getSelectedFeatures(){
		$packagesTable = $this->getTable("Package");
		$features = $packagesTable->getSelectedFeatures($this->getState('package.id'));
		$result = array();
		foreach($features as $feature){
			$result[]=$feature->feature;
		}

		return $result;
	}
	
	/**
	 * Method to delete groups.
	 *
	 * @param   array  An array of item ids.
	 * @return  boolean  Returns true on success, false on failure.
	 */
	public function delete(&$itemIds)
	{
		// Sanitize the ids.
		$itemIds = (array) $itemIds;
		JArrayHelper::toInteger($itemIds);
	
		// Get a group row instance.
		$table = $this->getTable();
	
		// Iterate the items to delete each one.
		foreach ($itemIds as $itemId)
		{
	
			if (!$table->delete($itemId))
			{
				$this->setError($table->getError());
				return false;
			}
		}
	
		// Clean the cache
		$this->cleanCache();
	
		return true;
	}
	
	
}
