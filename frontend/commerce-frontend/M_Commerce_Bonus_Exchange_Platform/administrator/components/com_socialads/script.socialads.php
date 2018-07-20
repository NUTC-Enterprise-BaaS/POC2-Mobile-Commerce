<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

define('MODIFIED', 1);
define('NOT_MODIFIED', 2);

/**
 * Updates the database structure of the component
 *
 * @version  Release: 0.2b
 * @author   Component Creator <support@component-creator.com>
 * @since    0.1b
 */
class Com_SocialadsInstallerScript
{
	/** @var array Obsolete files and folders to remove*/
	private $removeFilesAndFolders = array(
		'files'	=> array(
		),
		'folders' => array(
		'administrator/components/com_socialads/views/approveads',
		'administrator/components/com_socialads/views/buildad',
		'administrator/components/com_socialads/views/ignoreads',
		'administrator/components/com_socialads/views/lightbox',
		'administrator/components/com_socialads/views/managecoupon',
		'administrator/components/com_socialads/views/managezone',
		'components/com_socialads/views/billing',
		'components/com_socialads/views/buildad',
		'components/com_socialads/views/checkout',
		'components/com_socialads/views/ignoreads',
		'components/com_socialads/views/lightbox',
		'components/com_socialads/views/managead',
		)
	);

	/**
	 * Method called before install/update the component. Note: This method won't be called during uninstall process.
	 *
	 * @param   string  $type    Type of process [install | update]
	 * @param   mixed   $parent  Object who called this method
	 *
	 * @return  boolean True if the process should continue, false otherwise
	 */
	public function preflight($type, $parent)
	{
		$db    = JFactory::getDBO();
		$config   = JFactory::getConfig();
		$dbprefix = $config->get('dbprefix');
		$query = "SHOW TABLES LIKE '" . $dbprefix . "ad_camp_transc';";

		$db->setQuery($query);

		$table_exists = $db->loadResult();

		if ($table_exists)
		{
			// Rename table name
			$query = "RENAME TABLE #__ad_camp_transc TO #__ad_wallet_transc";
			$db->setQuery($query);
			$db->execute();
		}

		$query = "SHOW TABLES LIKE '" . $dbprefix . "ad_contextual_target';";
		$db->setQuery($query);
		$table_exists = $db->loadResult();

		if ($table_exists)
		{
			// Rename table name
			$query = "ALTER TABLE #__ad_contextual_target ENGINE = MYISAM";
			$db->setQuery($query);
			$db->execute();
		}

		$query = "SHOW TABLES LIKE '" . $dbprefix . "ad_contextual_terms';";
		$db->setQuery($query);
		$table_exists = $db->loadResult();

		if ($table_exists)
		{
			// Rename table name
			$query = "ALTER TABLE #__ad_contextual_terms ENGINE = MYISAM";
			$db->setQuery($query);
			$db->execute();
		}
	}


	/**
	 * Method to get plugin params
	 *
	 * @param   string  $group     group of a plugin
	 * @param   string  $api       api of a plugin
	 * @param   string  $paramstr  paramstr of a plugin
	 *
	 * @return  array
	 *
	 * @since  0.2b
	 */
	public function getpluginparams($group,$api,$paramstr)
	{
		if (!$group and !$api	and !$paramstr)
		{
			return '';
		}

		if (JVERSION >= 1.6)
		{
			$plugin = JPluginHelper::getPlugin($group, $api);
			$pluginParams = new JRegistry;

			if (!empty($plugin->params))
			{
				$pluginParams->loadString($plugin->params);
			}
		}
		else
		{
			$plugin = &JPluginHelper::getPlugin($group, $api);

			if (!empty($plugin->params))
			{
				$pluginParams = new JParameter($plugin->params);
			}
		}

		if ($pluginParams)
		{
			$params = explode(',', $paramstr);
			$params_data = array();

			foreach ($params as $param)
			{
				if ($pluginParams->get($param))
				{
					$params_data[$param] = $pluginParams->get($param);
				}
			}
		}

		return $params_data;
	}

	/**
	 * Method to install extension
	 *
	 * @param   string  $type    type of extension
	 * @param   array   $parent  parentof a plugin
	 *
	 * @return  void
	 *
	 * @since  0.2b
	 */
	public function postflight($type, $parent)
	{
		// Remove obsolete files and folders
		$removeFilesAndFolders = $this->removeFilesAndFolders;
		$this->_removeObsoleteFilesAndFolders($removeFilesAndFolders);
	}

	/**
	 * Method to install the component
	 *
	 * @param   mixed  $parent  Object who called this method.
	 *
	 * @return  void
	 *
	 * @since  0.2b
	 */
	public function install($parent)
	{
		// $this->installDb($parent);

		/*Create folder in images for storing media files for Ads*/
		if(!JFolder::exists(JPATH_ROOT.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'socialads'))
		{
			$data = '<html><head><title></title></head><body></body></html>';
			JFile::write(JPATH_ROOT.'/'.'images'.'/'.'socialads'.'/'.'index.html',$data);
		}

		// $this->installPlugins($parent);
		// $this->installModules($parent);
	}

	/**
	 * Method to update the DB of the component
	 *
	 * @param   mixed  $parent  Object who started the upgrading process
	 *
	 * @return  void
	 *
	 * @since  0.2b
	 */
	private function installDb($parent)
	{
		$installation_folder = $parent->getParent()->getPath('source');

		$app = JFactory::getApplication();

		if (function_exists('simplexml_load_file') && file_exists($installation_folder . '/installer/structure.xml'))
		{
			$component_data = simplexml_load_file($installation_folder . '/installer/structure.xml');

			// Check if there are tables to import.
			foreach ($component_data->children() as $table)
			{
				$this->processTable($app, $table);
			}
		}
		else
		{
			if (!function_exists('simplexml_load_file'))
			{
				$app->enqueueMessage(JText::_('This script needs \'simplexml_load_file\' to update the component'));
			}
			else
			{
				$app->enqueueMessage(JText::_('Structure file was not found.'));
			}
		}
	}

	/**
	 * Process a table
	 *
	 * @param   JApplicationCms   $app    Application object
	 * @param   SimpleXMLElement  $table  Table to process
	 *
	 * @return  void
	 *
	 * @since  0.2b
	 */
	private function processTable($app, $table)
	{
		$db = JFactory::getDbo();

		$table_added = false;

		if (isset($table['action']))
		{
			switch ($table['action'])
			{
				case 'add':

					// Check if the table exists before create the statement
					if (!$this->existsTable($table['table_name']))
					{
						$create_statement = $this->generateCreateTableStatement($table);
						$db->setQuery($create_statement);

						try
						{
							$db->execute();
							$app->enqueueMessage(
								JText::sprintf(
									'Table `%s` has been successfully created',
									(string) $table['table_name']
								)
							);
							$table_added = true;
						}
						catch (Exception $ex)
						{
							$app->enqueueMessage(
								JText::sprintf(
									'There was an error creating the table `%s`. Error: %s',
									(string) $table['table_name'],
									$ex->getMessage()
								), 'error'
							);
						}
					}
					break;
				case 'change':

					// Check if the table exists first to avoid errors.
					if ($this->existsTable($table['old_name']) && !$this->existsTable($table['new_name']))
					{
						try
						{
							$db->renameTable($table['old_name'], $table['new_name']);
							$app->enqueueMessage(
								JText::sprintf(
									'Table `%s` was successfully renamed to `%s`',
									$table['old_name'],
									$table['new_name']
								)
							);
						}
						catch (Exception $ex)
						{
							$app->enqueueMessage(
								JText::sprintf(
									'There was an error renaming the table `%s`. Error: %s',
									$table['old_name'],
									$ex->getMessage()
								), 'error'
							);
						}
					}
					else
					{
						if (!$this->existsTable($table['table_name']))
						{
							// If the table does not exists, let's create it.
							$create_statement = $this->generateCreateTableStatement($table);
							$db->setQuery($create_statement);

							try
							{
								$db->execute();
								$app->enqueueMessage(
									JText::sprintf('Table `%s` has been successfully created', $table['table_name'])
								);
								$table_added = true;
							}
							catch (Exception $ex)
							{
								$app->enqueueMessage(
									JText::sprintf(
										'There was an error creating the table `%s`. Error: %s',
										$table['table_name'],
										$ex->getMessage()
									), 'error'
								);
							}
						}
					}
					break;
				case 'remove':

					try
					{
						// We make sure that the table will be removed only if it exists specifying ifExists argument as true.
						$db->dropTable($table['table_name'], true);
						$app->enqueueMessage(
							JText::sprintf('Table `%s` was successfully deleted', $table['table_name'])
						);
					}
					catch (Exception $ex)
					{
						$app->enqueueMessage(
							JText::sprintf(
								'There was an error deleting Table `%s`. Error: %s',
								$table['table_name'], $ex->getMessage()
							), 'error'
						);
					}

					break;
			}
		}

		// If the table wasn't added before, let's process the fields of the table
		if (!$table_added)
		{
			if ($this->existsTable($table['table_name']))
			{
				$this->executeFieldsUpdating($app, $table);
			}
		}
	}

	/**
	 * Checks if a certain exists on the current database
	 *
	 * @param   string  $table_name  Name of the table
	 *
	 * @return  boolean True if it exists, false if it does not.
	 */
	private function existsTable($table_name)
	{
		$db = JFactory::getDbo();

		$table_name = str_replace('#__', $db->getPrefix(), (string) $table_name);

		return in_array($table_name, $db->getTableList());
	}

	/**
	 * Generates a 'CREATE TABLE' statement for the tables passed by argument.
	 *
	 * @param   SimpleXMLElement  $table  Table of the database
	 *
	 * @return  string 'CREATE TABLE' statement
	 */
	private function generateCreateTableStatement($table)
	{
		$create_table_statement = '';

		if (isset($table->field))
		{
			$fields = $table->children();

			$fields_definitions = array ();
			$indexes            = array ();

			$db = JFactory::getDbo();

			foreach ($fields as $field)
			{
				$field_definition = $this->generateColumnDeclaration($field);

				if ($field_definition !== false)
				{
					$fields_definitions[] = $field_definition;
				}

				if ($field['index'] == 'index')
				{
					$indexes[] = $field['field_name'];
				}
			}

			foreach ($indexes as $index)
			{
				$fields_definitions[] = JText::sprintf(
					'INDEX %s (%s ASC)',
					$db->quoteName((string) $index), $index
				);
			}

			$fields_definitions[]   = 'PRIMARY KEY (`id`)';
			$create_table_statement = JText::sprintf(
				'CREATE TABLE IF NOT EXISTS %s (%s)',
				$table['table_name'],
				implode(',', $fields_definitions)
			);
		}

		return $create_table_statement;
	}

	/**
	 * Generate a column declaration
	 *
	 * @param   SimpleXMLElement  $field  Field data
	 *
	 * @return  string Column declaration
	 */
	private function generateColumnDeclaration($field)
	{
		$db        = JFactory::getDbo();
		$col_name  = $db->quoteName((string) $field['field_name']);
		$data_type = $this->getFieldType($field);

		if ($data_type !== false)
		{
			$default_value = (isset($field['default'])) ? 'DEFAULT ' . $field['default'] : '';

			$other_data = '';

			if (isset($field['is_autoincrement']) && $field['is_autoincrement'] == 1)
			{
				$other_data .= ' AUTO_INCREMENT';
			}

			$comment_value = (isset($field['description'])) ? 'COMMENT ' . $db->quote((string) $field['description']) : '';

			return JText::sprintf(
				'%s %s NOT NULL %s %s %s', $col_name, $data_type,
				$default_value, $other_data, $comment_value
			);
		}

		return false;
	}

	/**
	 * Generates SQL field type of a field.
	 *
	 * @param   SimpleXMLElement  $field  Field information
	 *
	 * @return  string  SQL data type
	 */
	private function getFieldType($field)
	{
		$data_type = (string) $field['field_type'];

		if (isset($field['field_length']) && $this->allowsLengthField($data_type))
		{
			$data_type .= '(' . ((string) $field['field_length']) . ')';
		}

		if (empty($data_type))
		{
			return false;
		}

		return (string) $data_type;
	}

	/**
	 * Check if a SQL type allows length values.
	 *
	 * @param   string  $field_type  SQL type
	 *
	 * @return  boolean  True if it allows length values, false if it does not.
	 */
	private function allowsLengthField($field_type)
	{
		$allow_length = array (
			'INT', 'VARCHAR', 'CHAR',
			'TINYINT', 'SMALLINT', 'MEDIUMINT',
			'INTEGER', 'BIGINT', 'FLOAT',
			'DOUBLE', 'DECIMAL', 'NUMERIC'
		);

		return (in_array((string) $field_type, $allow_length));
	}

	/**
	 * Updates all the fields related to a table.
	 *
	 * @param   JApplicationCms   $app    Application Object
	 * @param   SimpleXMLElement  $table  Table information.
	 *
	 * @return  void
	 */
	private function executeFieldsUpdating($app, $table)
	{
		if (isset($table->field))
		{
			foreach ($table->children() as $field)
			{
				$this->processField($app, $table['table_name'], $field);
			}
		}
	}

	/**
	 * Process a certain field.
	 *
	 * @param   JApplicationCms   $app         Application object
	 * @param   string            $table_name  The name of the table that contains the field.
	 * @param   SimpleXMLElement  $field       Field Information.
	 *
	 * @return  void
	 */
	private function processField($app, $table_name, $field)
	{
		$db = JFactory::getDbo();

		if (isset($field['action']))
		{
			switch ($field['action'])
			{
				case 'add':
					$result = $this->addField($table_name, $field);

					if ($result === MODIFIED)
					{
						$app->enqueueMessage(
							JText::sprintf('Field `%s` has been successfully added', $field['field_name'])
						);
					}
					else
					{
						if ($result !== NOT_MODIFIED)
						{
							$app->enqueueMessage(
								JText::sprintf(
									'There was an error adding the field `%s`. Error: %s',
									$field['field_name'], $result
								), 'error'
							);
						}
					}
					break;
				case 'change':

					if (isset($field['old_name']) && isset($field['new_name']))
					{
						if ($this->existsField($table_name, $field['old_name']))
						{
							$renaming_statement = JText::sprintf(
								'ALTER TABLE %s CHANGE %s %s %s',
								$table_name, $field['old_name'],
								$field['new_name'],
								$this->getFieldType($field)
							);
							$db->setQuery($renaming_statement);

							try
							{
								$db->execute();
								$app->enqueueMessage(
									JText::sprintf('Field `%s` has been successfully modified', $field['old_name'])
								);
							}
							catch (Exception $ex)
							{
								$app->enqueueMessage(
									JText::sprintf(
										'There was an error modifying the field `%s`. Error: %s',
										$field['field_name'],
										$ex->getMessage()
									), 'error'
								);
							}
						}
						else
						{
							$result = $this->addField($table_name, $field);

							if ($result === MODIFIED)
							{
								$app->enqueueMessage(
									JText::sprintf('Field `%s` has been successfully modified', $field['field_name'])
								);
							}
							else
							{
								if ($result !== NOT_MODIFIED)
								{
									$app->enqueueMessage(
										JText::sprintf(
											'There was an error modifying the field `%s`. Error: %s',
											$field['field_name'], $result
										), 'error'
									);
								}
							}
						}
					}
					else
					{
						$result = $this->addField($table_name, $field);

						if ($result === MODIFIED)
						{
							$app->enqueueMessage(
								JText::sprintf('Field `%s` has been successfully added', $field['field_name'])
							);
						}
						else
						{
							if ($result !== NOT_MODIFIED)
							{
								$app->enqueueMessage(
									JText::sprintf(
										'There was an error adding the field `%s`. Error: %s',
										$field['field_name'], $result
									), 'error'
								);
							}
						}
					}

					break;
				case 'remove':

					// Check if the field exists first to prevent issue removing the field
					if ($this->existsField($table_name, $field['field_name']))
					{
						$drop_statement = JText::sprintf(
							'ALTER TABLE %s DROP COLUMN %s',
							$table_name, $field['field_name']
						);
						$db->setQuery($drop_statement);

						try
						{
							$db->execute();
							$app->enqueueMessage(
								JText::sprintf('Field `%s` has been successfully deleted', $field['field_name'])
							);
						}
						catch (Exception $ex)
						{
							$app->enqueueMessage(
								JText::sprintf(
									'There was an error deleting the field `%s`. Error: %s',
									$field['field_name'],
									$ex->getMessage()
								), 'error'
							);
						}
					}

					break;
			}
		}
		else
		{
			$result = $this->addField($table_name, $field);

			if ($result === MODIFIED)
			{
				$app->enqueueMessage(
					JText::sprintf('Field `%s` has been successfully added', $field['field_name'])
				);
			}
			else
			{
				if ($result !== NOT_MODIFIED)
				{
					$app->enqueueMessage(
						JText::sprintf(
							'There was an error adding the field `%s`. Error: %s',
							$field['field_name'], $result
						), 'error'
					);
				}
			}
		}
	}

	/**
	 * Add a field if it does not exists or modify it if it does.
	 *
	 * @param   string            $table_name  Table name
	 * @param   SimpleXMLElement  $field       Field Information
	 *
	 * @return  mixed  Constant on success(self::$MODIFIED | self::$NOT_MODIFIED), error message if an error occurred
	 */
	private function addField($table_name, $field)
	{
		$db = JFactory::getDbo();

		$query_generated = false;

		// Check if the field exists first to prevent issues adding the field
		if ($this->existsField($table_name, $field['field_name']))
		{
			if ($this->needsToUpdate($table_name, $field))
			{
				$change_statement = $this->generateChangeFieldStatement($table_name, $field);
				$db->setQuery($change_statement);
				$query_generated = true;
			}
		}
		else
		{
			$add_statement = $this->generateAddFieldStatement($table_name, $field);
			$db->setQuery($add_statement);
			$query_generated = true;
		}

		if ($query_generated)
		{
			try
			{
				$db->execute();

				return MODIFIED;
			}
			catch (Exception $ex)
			{
				return $ex->getMessage();
			}
		}

		return NOT_MODIFIED;
	}

	/**
	 * Checks if a field exists on a table
	 *
	 * @param   string  $table_name  Table name
	 * @param   string  $field_name  Field name
	 *
	 * @return  boolean True if exists, false if it do
	 */
	private function existsField($table_name, $field_name)
	{
		$db = JFactory::getDbo();

		return in_array((string) $field_name, array_keys($db->getTableColumns($table_name)));
	}

	/**
	 * Check if a field needs to be updated.
	 *
	 * @param   string            $table_name  Table name
	 * @param   SimpleXMLElement  $field       Field information
	 *
	 * @return  boolean True if the field has to be updated, false otherwise
	 */
	private function needsToUpdate($table_name, $field)
	{
		$db = JFactory::getDbo();

		$query = JText::sprintf(
			'SHOW FULL COLUMNS FROM `%s` WHERE Field LIKE %s', $table_name, $db->quote((string) $field['field_name'])
		);
		$db->setQuery($query);

		$field_info = $db->loadObject();

		if (strripos($field_info->Type, $this->getFieldType($field)) === false)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Generates an change column statement
	 *
	 * @param   string            $table_name  Table name
	 * @param   SimpleXMLElement  $field       Field Information
	 *
	 * @return  string Change column statement
	 */
	private function generateChangeFieldStatement($table_name, $field)
	{
		$column_declaration = $this->generateColumnDeclaration($field);

		return JText::sprintf('ALTER TABLE %s MODIFY %s', $table_name, $column_declaration);
	}

	/**
	 * Generates an add column statement
	 *
	 * @param   string            $table_name  Table name
	 * @param   SimpleXMLElement  $field       Field Information
	 *
	 * @return  string Add column statement
	 */
	private function generateAddFieldStatement($table_name, $field)
	{
		$column_declaration = $this->generateColumnDeclaration($field);

		return JText::sprintf('ALTER TABLE %s ADD %s', $table_name, $column_declaration);
	}

	/**
	 * Check if an extension is already installed in the system
	 *
	 * @param   string  $type    Extension type
	 * @param   string  $name    Extension name
	 * @param   mixed   $folder  Extension folder(for plugins)
	 *
	 * @return  boolean
	 */
	private function isAlreadyInstalled($type, $name, $folder = null)
	{
		$result = false;

		switch ($type)
		{
			case 'plugin':
				$result = file_exists(JPATH_PLUGINS . '/' . $folder . '/' . $name);
				break;
			case 'module':
				$result = file_exists(JPATH_SITE . '/modules/' . $name);
				break;
		}

		return $result;
	}

	/**
	 * Method to update the component
	 *
	 * @param   String  $parent  String
	 *
	 * @return void
	 */
	public function update($parent)
	{
		$this->_migrationScript($parent);
		$this->fix_db_on_update();
		$this->changePluginParams();
	}

	/**
	 * Migrate the socialads 3.0 or less version database with latest one
	 *
	 * @param   String  $parent  Component installation path
	 *
	 * @return void
	 *
	 * @since  3.1
	 */
	public function _migrationScript($parent)
	{
		$db       = JFactory::getDBO();
		$config   = JFactory::getConfig();
		$dbprefix = $config->get('dbprefix');

		// Delete unnecessary column from ad order table
		$query = "SHOW COLUMNS FROM #__ad_payment_info WHERE `Field` = 'subscription_id'";
		$db->setQuery($query);
		$check = $db->loadResult();

		// If older column exist means we need to migrate
		if ($check)
		{
			$query = "SHOW TABLES LIKE '".$dbprefix."ad_payment_info_backup';";
			$db->setQuery($query);
			$backup_exists = $db->loadResult();

			if (!$backup_exists)
			{
				$query = "CREATE TABLE IF NOT EXISTS #__ad_payment_info_backup LIKE #__ad_payment_info;";
				$db->setQuery($query);

				if ($db->execute())
				{
					$query = "INSERT INTO  #__ad_payment_info_backup SELECT * FROM #__ad_payment_info";
					$db->setQuery($query);

					if ($db->execute())
					{
						// Add code here
					}
				}

				$query = "ALTER TABLE `#__ad_payment_info`
						CHANGE ad_amount amount float NOT NULL COMMENT 'Amount of a payment',
						CHANGE ad_original_amt original_amount float NOT NULL COMMENT 'Amount needs to paid by a user',
						CHANGE ad_coupon coupon varchar(100) NOT NULL COMMENT 'Coupon Id',
						CHANGE ad_tax tax float(10,2) NOT NULL COMMENT 'Tax if applied',
						CHANGE ad_tax_details tax_details text NOT NULL COMMENT 'Infromation about a tax',
						ADD `prefix` VARCHAR( 23 ) NOT NULL,
						ADD `payment_info_id` int(11) NOT NULL COMMENT 'Payment id'";

				$db->setQuery($query);
				$db->execute();

				$query = "RENAME TABLE #__ad_payment_info TO #__ad_orders";
				$db->setQuery($query);
				$db->execute();

				// Create table ad_payment_info
				$this->runSQL($parent,'install.mysql.utf8.sql');

				$query = "SHOW TABLES LIKE '" . $dbprefix . "ad_payment_info';";
				$db->setQuery($query);

				$table_exists = $db->loadResult();

				if ($table_exists)
				{
					$query = "SELECT * FROM #__ad_orders";
					$db->setQuery($query);

					$recs = $db->loadObjectList();

					foreach ($recs as $key => $row)
					{
						$obj = new stdClass;

						$obj->id                  = '';
						$obj->order_id            = $row->id;
						$obj->ad_id               = $row->ad_id;
						$obj->subscr_id           = $row->subscription_id;
						$obj->ad_credits_qty      = $row->ad_credits_qty;
						$obj->cdate               = $row->cdate;

						// If wallet mode there are no entries in payment info table
						if ($obj->ad_id != 0)
						{
							if (!$db->insertObject('#__ad_payment_info', $obj, 'id'))
							{
								$app     = JFactory::getApplication();
								$app->enqueueMessage($db->stderr(), 'error');

								return false;
							}
						}
					}
				}

				// Delete unnecessary column from ad order table
				$query = "ALTER TABLE `#__ad_orders`
						DROP subscription_id,
						DROP ad_id,
						DROP ad_credits_qty";

				$db->setQuery($query);
				$db->execute();
			}

			// Text and media ad type changed migration
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id', 'ad_type')));
			$query->from($db->quoteName('#__ad_zone'));
			$query->where($db->quoteName('ad_type') . 'LIKE "%img%"');

			$db->setQuery($query);
			$adTypeMigration = $db->loadObjectList();

			foreach ($adTypeMigration as $adZone)
			{
				$obj = new stdclass;
				$obj->id = $adZone->id;
				$obj->ad_type = str_replace("|img|", "|media|", $adZone->ad_type);
				$obj->ad_type = str_replace("|text_img|", "|text_media|", $obj->ad_type);

				if (!$db->updateObject('#__ad_zone', $obj, 'id'))
				{
					return false;
				}
			}
		}

		// Delete unnecessary column from ad order table
		$query = "SHOW COLUMNS FROM #__ad_data WHERE `Field` = 'clicks'";
		$db->setQuery($query);
		$check = $db->loadResult();

		// If column not exist in ad_data table then Add the column
		if (!$check)
		{
			// Add column in ad data table
			$newColumns = array(
				'clicks' => 'float NOT NULL COMMENT "for number of clicks of perticular ad"',
				'impressions' => 'float NOT NULL COMMENT "For number of impressions of perticular ad"',
			);

			$this->alterTables("#__ad_data", $newColumns);

			// Now, we need to fill the value of impression column
			$query = $db->getQuery(true);

			$query->select('as.ad_id, COUNT(as.ad_id) as impressions_cnt');
			$query->from($db->quoteName('#__ad_stats', 'as'));
			$query->join('LEFT', $db->quoteName('#__ad_data', 'ad') . ' ON (' . $db->quoteName('ad.ad_id') . ' = ' . $db->quoteName('as.ad_id') . ')');

			$query->where($db->quoteName('display_type') . ' = 0');
			$query->group('as.ad_id');

			$db->setQuery($query);
			$impressions = $db->loadObjectList();

			foreach ($impressions as $impression)
			{
				$obj =  new stdClass;
				$obj->ad_id = $impression->ad_id;
				$obj->impressions = $impression->impressions_cnt;

				$db->updateObject("#__ad_data", $obj, "ad_id");
			}

			// Now, we need to fill the value of Clicks column
			$query = $db->getQuery(true);

			$query->select('as.ad_id, COUNT(as.ad_id) as clicks_cnt');
			$query->from($db->quoteName('#__ad_stats', 'as'));
			$query->join('LEFT', $db->quoteName('#__ad_data', 'ad') . ' ON (' . $db->quoteName('ad.ad_id') . ' = ' . $db->quoteName('as.ad_id') . ')');

			$query->where($db->quoteName('display_type') . ' = 1');
			$query->group('as.ad_id');

			$db->setQuery($query);
			$clicks = $db->loadObjectList();

			foreach ($clicks as $click)
			{
				$obj =  new stdClass;
				$obj->ad_id = $click->ad_id;
				$obj->clicks = $click->clicks_cnt;

				$db->updateObject("#__ad_data", $obj, "ad_id");
			}
		}

		// Migrate country database
		$query = 'SHOW COLUMNS FROM `#__ad_users` WHERE `Field` ="country_code" AND `Type`="varchar(50)"';
		$db->setQuery($query);
		$check = $db->loadResult();

		if ($check)
		{
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id', 'country_code', 'state_code')));
			$query->from($db->quoteName('#__ad_users'));

			$db->setQuery($query);
			$country_state_mgr = $db->loadObjectList();

			foreach($country_state_mgr as $row )
			{
				if ($row->country_code)
				{
					// Get the country Id
					$query = $db->getQuery(true);
					$query->select($db->quoteName('id'));
					$query->from($db->quoteName('#__tj_country'));
					$query->where($db->quoteName('country') . 'LIKE "' . $row->country_code . '"');

					$db->setQuery($query);
					$country = $db->loadResult();

					if ($country)
					{
						// Update country Id in table
						$country_object = new stdClass;
						$country_object->id = $row->id;
						$country_object->country_code = $country;

						if (!$db->updateObject('#__ad_users', $country_object, 'id'))
						{
							return false;
						}

						if ($row->state_code && $country_object->country_code)
						{
							// Get the state Id
							$query = $db->getQuery(true);
							$query->select($db->quoteName('r.id'));
							$query->from($db->quoteName('#__tj_region', 'r'));
							$query->join('LEFT', $db->quoteName('#__tj_country', 'c') . ' ON r.country_id = c.id');
							$query->where($db->quoteName('c.id') . '= "' . $country_object->country_code . '"');
							$query->where($db->quoteName('r.region') . '= "' . $row->state_code . '"');
							$db->setQuery($query);
							$region_id = $db->loadResult();

							if ($region_id)
							{
								// Update State id in ad users table
								$region_object = new stdClass;
								$region_object->id = $row->id;
								$region_object->state_code = $region_id;

								if (!$db->updateObject('#__ad_users', $region_object, 'id'))
								{
									return false;
								}
							}
						}
					}
				}
			}

			// Alter the Country Table
			$columns = array
			(
				array("old"=>"country_code","new"=>"country_code","type"=>"VARCHAR(51)  NOT NULL COMMENT 'Country code of user'"),
			);

			$this->_renameColumn("#__ad_users", $columns);
		}

		$uninstall_queue = array(
		'plugins' => array('socialadspromote' => array('plug_promote_cb' => 0, 'plug_promote_esprofile' => 0, 'plug_promote_jsevents' => 0, 'plug_promote_jsprofile'=>0 , 'plug_promote_sobi' => 0, 'plug_promote_jsgroups' =>0)));

		// Uninstall unwanted plugins
		$this->_uninstallSubextensions($parent, $uninstall_queue);

		// Migrate Menu link
		$this->migrateMenu();
	}

	/**
	 * Change old menu link with new one
	 *
	 * @param  void
	 *
	 * @since  1.0
	 */
	public function migrateMenu()
	{
		$menus = array
		(
			// Create Ad
			array("old"=>"index.php?option=com_socialads&view=buildad","new"=>"index.php?option=com_socialads&view=adform"),

			// Manage Ad
			array("old"=>"index.php?option=com_socialads&view=managead","new"=>"index.php?option=com_socialads&view=ads"),

			// Payment view
			array("old"=>"index.php?option=com_socialads&view=billing","new"=>"index.php?option=com_socialads&view=wallet"),

			array("old"=>"index.php?option=com_socialads&view=campaign","new"=>"index.php?option=com_socialads&view=campaigns")
		);

		foreach ($menus as $key => $menu)
		{
			$db = JFactory::getDBO();

			$query = $db->getQuery(true);

			$query->select($db->quoteName('id'));
			$query->from($db->quoteName('#__menu'));
			$query->where($db->quoteName('link') . " LIKE '%" . $menu['old'] . "%'");

			$db->setQuery($query);
			$itemids = $db->loadColumn();

			foreach ($itemids as $itemid)
			{
				$obj       =  new stdClass;
				$obj->id   = $itemid;
				$obj->link = $menu['new'];

				$db->updateObject("#__menu", $obj, "id");
			}
		}
	}

	/**
	 * Uninstalls subextensions (modules, plugins) bundled with the main extension
	 *
	 * @param JInstaller $parent
	 * @return JObject The subextension uninstallation status
	 */
	private function _uninstallSubextensions($parent, $uninstall_queue)
	{
		jimport('joomla.installer.installer');

		$db = JFactory::getDBO();

		$status          = new JObject();
		$status->modules = array();
		$status->plugins = array();

		$src = $parent->getParent()->getPath('source');

		// Plugins uninstallation
		if (count($uninstall_queue['plugins']))
		{
			foreach ($uninstall_queue['plugins'] as $folder => $plugins)
			{
				if (count($plugins))
				{
					foreach ($plugins as $plugin => $published)
					{
						$sql = $db->getQuery(true)->select($db->qn('extension_id'))->from($db->qn('#__extensions'))->where($db->qn('type') . ' = ' . $db->q('plugin'))->where($db->qn('element') . ' = ' . $db->q($plugin))->where($db->qn('folder') . ' = ' . $db->q($folder));
						$db->setQuery($sql);

						$id = $db->loadResult();

						if ($id)
						{
							$installer         = new JInstaller;
							$result            = $installer->uninstall('plugin', $id);
							$status->plugins[] = array(
								'name' => 'plg_' . $plugin,
								'group' => $folder,
								'result' => $result
							);
						}
					}
				}
			}
		}

		return $status;
	}

	public function runSQL($parent,$sqlfile)
	{
		$db = JFactory::getDBO();

		// Obviously you may have to change the path and name if your installation SQL file ;)
		if (method_exists($parent, 'extension_root'))
		{
			$sqlfile = $parent->getPath('extension_root').DS.'administrator'.DS.'sql'.DS.$sqlfile;
		}
		else
		{
			$sqlfile = $parent->getParent()->getPath('extension_root').DS.'sql'.DS.$sqlfile;
		}

		// Don't modify below this line
		$buffer = file_get_contents($sqlfile);

		if ($buffer !== false)
		{
			jimport('joomla.installer.helper');
			$queries = JInstallerHelper::splitSql($buffer);

			if (count($queries) != 0)
			{
				foreach ($queries as $query)
				{
					$query = trim($query);

					if ($query != '' && $query{0} != '#')
					{
						$db->setQuery($query);

						if (!$db->execute())
						{
							JError::raiseWarning(1, JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $db->stderr(true)));
							return false;
						}
					}
				}
			}
		}
	}

	/**
	 * Get updated table modified
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function fix_db_on_update()
	{
		$db       = JFactory::getDBO();
		$config   = JFactory::getConfig();
		$dbprefix = $config->get('dbprefix');

		// Alter table to add New Column
		$newColumns = array(
			'ordering' => 'INT(11)  NOT NULL',
			'checked_out' => 'INT(11)  NOT NULL',
			'checked_out_time' => 'DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00"',
		);

		$this->alterTables("#__ad_campaign", $newColumns);
		$this->alterTables("#__ad_zone", $newColumns);
		$this->alterTables("#__ad_data", $newColumns);

		// Alter table to add New Column
		$newColumns = array(
			'prefix_oid' => 'VARCHAR( 23 ) NOT NULL',
		);

		$this->alterTables("#__ad_orders", $newColumns);

		// Alter coupon table
		$newColumns = array(
			'checked_out' => 'INT(11)  NOT NULL',
			'ordering' => 'INT(11)  NOT NULL',
			'checked_out_time' => 'DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00"',
			'created_by' => 'INT(11)  NOT NULL',
		);

		$this->alterTables("#__ad_coupon", $newColumns);

		// Alter Campaign table
		$columns = array
		(
			array("old"=>"camp_id","new"=>"id","type"=>"INT(11) UNSIGNED NOT NULL AUTO_INCREMENT"),
			array("old"=>"camp_published","new"=>"state","type"=>"TINYINT(1) NOT NULL"),
			array("old"=>"user_id","new"=>"created_by","type"=>"INT(11) NOT NULL"),
		);

		$this->_renameColumn("#__ad_campaign", $columns);

		// Add Data table
		$columns = array
		(
			array("old"=>"ad_published","new"=>"state","type"=>"TINYINT(1) NOT NULL"),
			array("old"=>"ad_creator","new"=>"created_by","type"=>"INT(11) NOT NULL"),
		);

		$this->_renameColumn("#__ad_data", $columns);

		// Alter Ad zone table
		$columns = array
		(
			array("old"=>"zone_type","new"=>"orientation","type"=>" TINYINT(2)  NOT NULL COMMENT 'Orientation for a specific zone Horizontal or Vertical'"),
			array("old"=>"published","new"=>"state","type"=>"TINYINT(1) NOT NULL"),
		);

		$this->_renameColumn("#__ad_zone", $columns);

		// Alter Ad coupon table
		$columns = array
		(
			array("old"=>"published","new"=>"state","type"=>"TINYINT(1) NOT NULL"),
		);

		$this->_renameColumn("#__ad_coupon", $columns);
	}

	/**
	 * alterTables
	 *
	 * @param   STRING  $table   Table name
	 * @param   ARRAY   $colums  colums name
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function _renameColumn($table, $columns)
	{
		$db    = JFactory::getDBO();
		$query = "SHOW COLUMNS FROM {$table}";
		$db->setQuery($query);

		$res = $db->loadColumn();

		foreach ($columns as $c => $t)
		{
			if (in_array($t['old'], $res))
			{
				$query = "ALTER TABLE {$table} CHANGE " . $t['old'] . " ". $t['new'] . " " . $t['type'];
				$db->setQuery($query);
				$db->execute();
			}
		}
	}

	/**
	 * alterTables
	 *
	 * @param   STRING  $table   Table name
	 * @param   ARRAY   $colums  colums name
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function alterTables($table, $colums)
	{
		$db    = JFactory::getDBO();
		$query = "SHOW COLUMNS FROM {$table}";
		$db->setQuery($query);

		$res = $db->loadColumn();

		foreach ($colums as $c => $t)
		{
			if (!in_array($c, $res))
			{
				$query = "ALTER TABLE {$table} add column $c $t;";
				$db->setQuery($query);
				$db->execute();
			}
		}
	}

	/**
	 * Removes obsolete files and folders
	 *
	 * @param array $removeFilesAndFolders
	 */
	private function _removeObsoleteFilesAndFolders($removeFilesAndFolders)
	{
		// Remove files
		jimport('joomla.filesystem.file');
		if (!empty($removeFilesAndFolders['files'])) foreach ($removeFilesAndFolders['files'] as $file) {
			$f = JPATH_ROOT.'/'.$file;
			if (!JFile::exists($f)) continue;
			JFile::delete($f);
		}

		// Remove folders
		jimport('joomla.filesystem.file');
		if (!empty($removeFilesAndFolders['folders'])) foreach ($removeFilesAndFolders['folders'] as $folder) {
			$f = JPATH_ROOT.'/'.$folder;
			if (!JFolder::exists($f)) continue;
			JFolder::delete($f);
		}
	}

	/**
	 * Function to change plugin parameters after migration from old SocialAds to new SocialAds
	 *
	 * @return  void
	 *
	 * @since  3.1
	 */
	public function changePluginParams()
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$data = '{"layout_type":"Text And Media"}';

		$fields = $db->quoteName('params') . "='" . $data . "'";
		$condition = $db->quoteName('element') . ' = "plug_layout1"';
		$query->update($db->quoteName('#__extensions'))->set($fields)->where($condition);
		$db->setquery($query);
		$db->execute();

		$query = $db->getQuery(true);
		$data = '{"layout_type":"Text And Media"}';

		$fields = $db->quoteName('params') . "='" . $data . "'";
		$condition = $db->quoteName('element') . ' = "plug_layout3"';
		$query->update($db->quoteName('#__extensions'))->set($fields)->where($condition);
		$db->setquery($query);
		$db->execute();

		$query = $db->getQuery(true);
		$data = '{"layout_type":"Media"}';

		$fields = $db->quoteName('params') . "='" . $data . "'";
		$condition = $db->quoteName('element') . ' = "plug_layout5"';
		$query->update($db->quoteName('#__extensions'))->set($fields)->where($condition);
		$db->setquery($query);
		$db->execute();
	}
}
