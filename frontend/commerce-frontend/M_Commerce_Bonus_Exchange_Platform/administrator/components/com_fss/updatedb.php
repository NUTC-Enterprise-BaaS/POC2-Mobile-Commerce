<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php
jimport('joomla.filesystem.file');
jimport( 'joomla.version' );
jimport( 'joomla.installer.installer' );
jimport('joomla.filesystem.path');
jimport('joomla.filesystem.folder');

require_once (JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_fss'.DS.'adminhelper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_users.php');

global $fsjjversion;

class FSSUpdater
{
	function Process($path = "")
	{
		set_time_limit(360);
		
		$log = array();
		
		$log[] = array('name' => 'Updating database', 'log' => $this->UpdateDatabase($path));
		$log[] = array('name' => 'Force MyISAM table format', 'log' => $this->ForceMyISAM($path));
		$log[] = array('name' => 'Published field on cats and depts', 'log' => $this->AddCatDeptPublished());	
		$log[] = array('name' => 'Copy Category Images', 'log' => $this->CopyImages());
		$log[] = array('name' => 'Copy Menu Images', 'log' => $this->CopyMenuImages());
		$log[] = array('name' => 'Process Comments and Testimonials', 'log' => $this->ConvertComments());
		$log[] = array('name' => 'Process Templates Tables', 'log' => $this->ConvertTemplatesTable());
		$log[] = array('name' => 'Sort any missing db entries', 'log' => $this->DataEntries($path));
		$log[] = array('name' => 'Sort missing Language and access entries', 'log' => $this->LangAccess());
		//$log[] = array('name' => 'Validate Joomla Admin Menu Entries', 'log' => $this->ValidateMenus());
		$log[] = array('name' => 'Process Settings', 'log' => $this->SortSettings());
		$log[] = array('name' => 'Sort product assignment', 'log' => $this->SortInProd());
		$log[] = array('name' => 'Relinking any orphaned frontend menus', 'log' => $this->RelinkMenuItems());
		//$log[] = array('name' => 'Move plugin files', 'log' => $this->MovePluginFiles());
		if ($path == "")
			$log[] = array('name' => 'Setup plugin DB entries', 'log' => $this->SetupPluginDBEntries());
		//$log[] = array('name' => 'Add admin user to handle support etc', 'log' => $this->AddAdminUser());
		$log[] = array('name' => 'Refreshing manifest', 'log' => $this->RefreshManifest());
		$log[] = array('name' => 'Link ticket attachments to messages', 'log' => $this->LinkTicketAttach());
		$log[] = array('name' => 'Registering new modules', 'log' => $this->RegisterModules($path));
		$log[] = array('name' => 'Check content author', 'log' => $this->AddArticlesToAuthor());
		$log[] = array('name' => 'Update version number', 'log' => $this->UpdateVersion($path));
		$log[] = array('name' => 'Fix broken admin ids', 'log' => $this->FixBrokenAdmins());
		
		// We need to do this only ONCE!
		$log[] = array('name' => 'Converting data to v2', 'log' => $this->Convertv2());
		$log[] = array('name' => 'Remove Old Tables', 'log' => $this->RemoveOldTables());
		$log[] = array('name' => 'Sort API Key', 'log' => $this->SortAPIKey());
		$log[] = array('name' => 'Misc', 'log' => $this->Misc());
		$log[] = array('name' => 'Update Plugins', 'log' => $this->UpdatePlugins());
		return $log;	
	}


 	function DBIs16()
	{
		global $fsjjversion;
		if (empty($fsjjversion))
		{
			$version = new JVersion;
			$fsjjversion = 1;
			if ($version->RELEASE == "1.5")
				$fsjjversion = 0;
		}
		return $fsjjversion;
	}
	
	function GetExistingTables()
	{
		if (empty($this->existingtables))
		{
			$this->existingtables = array();
			$db	= JFactory::getDBO();

			$qry = "SHOW TABLES";
			$db->setQuery($qry);
			$existingtables_ = $db->loadAssocList();
			//print_r($existingtables_);
			$existingtables = array();
			foreach($existingtables_ as $existingtable_2)
			{
				foreach ($existingtable_2 as $existingtable)
				{
					$existingtable = str_replace(FSS_Helper::dbPrefix(),'#__',$existingtable);
					$this->existingtables[$existingtable] = $existingtable;
				}
			}
		}
		
		return $this->existingtables;
	}
	
	function RestoreTableData($table, &$stuff, $checkexisting = true)
	{
		//print_p($stuff['data']);
		//exit;
		$db	= JFactory::getDBO();

		$log = "inserting data where missing\n";
		$prikeys = array();
		if (array_key_exists('index',$stuff))
		{
			foreach ($stuff['index'] as $index)
			{
				if ($index['Key_name'] == "PRIMARY")
				{
					$prikeys[$index['Seq_in_index']] = $index['Column_name'];		
				}
			}
		}
	
		if (array_key_exists('data',$stuff))
		{
			foreach($stuff['data'] as $id => $data)
			{
				if ($checkexisting)
				{
					$qry = "SELECT * FROM `$table` WHERE ";
			
					$where = array();
			
					foreach($prikeys as $prikey)
					{
						$where[] = "`$prikey` = '" . FSSJ3Helper::getEscaped($db, $data[$prikey]) ."'";		
					}
			
					if (count($where) > 0)
					{
						$qry .= implode(" AND ",$where);
						$db->setQuery($qry);
						$existing = $db->loadAssocList();
					} else {
						$existing = array();
					}
				} else {
					$existing = array();
				}
			
				if (count($existing) == 0)
				{
					$fields = array();
					$values = array();
			
					foreach ($data as $field => $value)
					{
						$fields[] = "`" . $field ."`";
						$values[] = "'" . FSSJ3Helper::getEscaped($db, $value) . "'";
					}
			
					$fieldlist = implode(", ", $fields);
					$valuelist = implode(", ", $values);
			
					$qry = "INSERT INTO `$table` ($fieldlist) VALUES ($valuelist)";
					$log .= $qry."\n";
					$db->setQuery($qry);$db->Query();
			
				}				
			}
		}
	
		return $log;
	}

	function CompareFields($table, &$stuff)
	{
		$db	= JFactory::getDBO();
		$log = "";

		$qry = "DESCRIBE $table";
		$db->setQuery($qry);
		$existing_ = $db->loadAssocList();
		$existing = array();
		
		foreach ($existing_ as $field)
		{
			$existing[$field['Field']] = $field;	
		}
		
		foreach ($stuff['fields'] as $field)
		{
			$fieldname = $field['Field'];
			if (array_key_exists($fieldname,$existing))
			{
				//$log .= "Compare field $fieldname\n";	
				$existingfield = $existing[$fieldname];
				$same = true;
				
				if ($existingfield['Type'] != $field['Type'])
					$same = false;
				if ($existingfield['Null'] != $field['Null'])
					$same = false;
				if ($existingfield['Default'] != $field['Default'])
					$same = false;
				if ($existingfield['Extra'] != $field['Extra'])
					$same = false;

				if (!$same)
				{
					$change = "ALTER TABLE `$table` CHANGE `$fieldname` `$fieldname` " . $field['Type'];
					if ($field['Null'] == "NO")
						$change .= " NOT NULL ";
					if ($field['Extra'] == "auto_increment")
						$change .= " AUTO_INCREMENT ";
					if ($field['Default'] != "")
						$change .= " DEFAULT '" . $field['Default'] . "'";
					$log .= "<code class='sql'>".$change."</code>" . "\n";
					try {
						$db->SetQuery($change);
						$db->Query();
					} catch (Exception $e) {
						$log .= "ERROR SQL: " . "<code class='sql'>".$change."</code>" . "\n";
						$log .= "ERROR MSG: " . $e->getMessage() . "\n";
					}
				}

				//ALTER TABLE `jos_fss_ticket_field` CHANGE `gfda` `iuytoiuyt` INT( 8 ) NOT NULL 
			} else {
				$log .= "New field $fieldname\n";	
				
				$change = "ALTER TABLE `$table` ADD `$fieldname` " . $field['Type'];
				if ($field['Null'] == "NO")
					$change .= " NOT NULL ";
				if ($field['Extra'] == "auto_increment")
					$change .= " AUTO_INCREMENT ";
				$log .= "<code class='sql'>".$change."</code>" . "\n";
				try {
					$db->SetQuery($change);
					$db->Query();
				} catch (Exception $e) {
					$log .= "ERROR SQL: " . "<code class='sql'>".$change."</code>" . "\n";
					$log .= "ERROR MSG: " . $e->getMessage() . "\n";
				}
			}
		}

		return $log;
	}

	function CompareIndexes($table, &$stuff)
	{
		$db	= JFactory::getDBO();
		$log = "";

		$indexs = array();
		if (array_key_exists('index', $stuff))
		{
			foreach ($stuff['index'] as $index)
			{
				$indexs[$index['Key_name']][$index['Seq_in_index']] = $index;
			}
		}
	
		$qry = "SHOW INDEX FROM $table";
		$db->setQuery($qry);
		$existing_ = $db->loadAssocList();
		$existing = array();
		foreach ($existing_ as $index)
		{
			$existing[$index['Key_name']][$index['Seq_in_index']] = $index;
		}
		
		foreach ($indexs as $index)
		{
			$createindex = false;
			$name = $index[1]['Key_name'];
			//$log .= "";
			if (array_key_exists($name,$existing))
			{
				$index_change = "Index " . $name . "\n";
				// compare indexes and their fields. BORING
				$same = true;
				foreach ($index as $id => $field)
				{
					if (!array_key_exists($id,$existing[$name]))
					{
						$index_change .= "index offset $id not exist\n";
						$same = false;
					} else {
						if ($field['Non_unique'] != $existing[$name][$id]['Non_unique'])
						{
							$index_change .= "Non_unique different\n";
							$same = false;
						}
						if ($field['Column_name'] != $existing[$name][$id]['Column_name'])
						{
							$index_change .= "Column_name different\n";
							$same = false;
						}
					}
				}
				
				if (count($existing[$name]) != count($index))
					$same = false;
				
				if (!$same)
				{
					$log .= $index_change;
					$log .= "Index different.. dropping\n";
					$drop = "ALTER TABLE `$table` DROP INDEX `" . $name . "`";
					$log .= "<code class='sql'>".$drop."</code>" . "\n";
					try {
						$db->SetQuery($drop);
						$db->Query();
					} catch (Exception $e) {
						$log .= "ERROR SQL: " . "<code class='sql'>".$drop."</code>" . "\n";
						$log .= "ERROR MSG: " . $e->getMessage() . "\n";
					}

					$createindex = true;
				}
				
			} else {
				$createindex = true;
			}
			
			if ($createindex)
			{
				$log .= "Creating index $name\n";
				
				$fieldlist = array();
				foreach ($index as $id => $field)
				{
					$fieldlist[] = "`" . $field['Column_name'] . "`";	
				}
				//print_p($index);
								
				$fieldlist = implode(", ",$fieldlist);
				
				$create = "ALTER TABLE `$table` ADD ";
				
				if ($index[1]['Key_name'] == "PRIMARY")
				{
					$create .= " PRIMARY KEY ";					
				} else if ($index[1]['Index_type'] == "FULLTEXT")
				{
					$create .= " FULLTEXT `$name` ";	
				} else if ($index[1]['Non_unique'] == 1)
				{
					$create .= " INDEX `$name` ";	
				} else {
					$create .= " UNIQUE `$name` ";	
				}
				
				$create .= "( " . $fieldlist . ")";

				try {
					$db->SetQuery($create);
					$db->Query();
				} catch (Exception $e) {
					$log .= "ERROR SQL: " . "<code class='sql'>".$create."</code>" . "\n";
					$log .= "ERROR MSG: " . $e->getMessage() . "\n";
				}
				
				$log .= "<code class='sql'>".$create."</code>" . "\n";
			}
		}

		return $log;
	}

	function CompareTable($table, &$stuff)
	{
		$db	= JFactory::getDBO();

		$log = $this->CompareFields($table, $stuff);
		$log .= $this->CompareIndexes($table, $stuff);

		// second field compare incase auto increment has been removed or similar
		$log .= $this->CompareFields($table, $stuff);

		return $log;
	}

	function CreateTable($table, &$stuff)
	{
		$db	= JFactory::getDBO();

		$log = "New table\n";
	
		$create = "CREATE TABLE IF NOT EXISTS `$table` (\n";
	
		$parts = array();
	
		foreach ($stuff['fields'] as $field)
		{
			$part = "`" . $field['Field'] . "` " . $field['Type'];
			if ($field['Null'] == "NO")
				$part .= " NOT NULL ";
			if ($field['Extra'] == "auto_increment")
				$part .= " AUTO_INCREMENT ";
			$parts[] = $part;
		}
	
	
		$indexs = array();
		foreach ($stuff['index'] as $index)
		{
			$indexs[$index['Key_name']][$index['Seq_in_index']] = $index;
		}
	
		if (array_key_exists("PRIMARY",$indexs))
		{
			$fields = "";
			foreach ($indexs['PRIMARY'] as $index)
			{
				$fields[] = "`" . $index['Column_name'] . "`";	
			}
			$fields = implode(", ",$fields);
		
			$part = "PRIMARY KEY (" . $fields . ")";
			$parts[] = $part;
		}
	
		foreach ($indexs as $name => $index)
		{
			if ($name == "PRIMARY")
				continue;
			
			$part = "UNIQUE KEY ";
		
			if ($index[1]['Index_type'] == "FULLTEXT")
				$part = " FULLTEXT ";		
			elseif ($index[1]['Non_unique'])
				$part = "KEY ";
		
			$part .= "`" . $index[1]['Key_name'] . "` (";
		
			$fields = array();
		
			foreach ($index as $field)
			{
				$fields[] = "`" . $field['Column_name'] . "`";	
			}
		
			$part .= implode(", ",$fields) . ")";
			$parts[] = $part;
		}
	
	
		$create = $create . implode(",\n",$parts) . "\n) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		try {
			$db->SetQuery($create);
			$db->Query();
		} catch (Exception $e) {
			$log .= "ERROR SQL: " . "<code class='sql'>".$create."</code>" . "\n";
			$log .= "ERROR MSG: " . $e->getMessage() . "\n";
		}
		
		//echo $create."<br>";
		
		$log .= "<code class='sql'>".$create."</code>"."\n\n";

		return $log;
	}
	
	function LangAccess()
	{
		$log = "";
		$db	= JFactory::getDBO();
		
		$tables = array(
			
			'#__fss_faq_cat' => 1,
			'#__fss_faq_faq' => 1,
			'#__fss_prod' => 0,
			'#__fss_announce' => 1,
			'#__fss_glossary' => 1,
			'#__fss_kb_art' => 1,
			'#__fss_kb_cat' => 1,
			'#__fss_main_menu' => 1,
			'#__fss_ticket_cat' => 0,
			'#__fss_ticket_dept' => 0,
			'#__fss_field' => 0
		);
		
		foreach ($tables as $table => $langs)
		{
			if ($langs)
			{
				$query = "UPDATE $table SET language = '*' WHERE language = ''";
				$db->setQuery($query);
				$db->Query();
				$count = $db->getAffectedRows();
			
				if ($count > 0)
					$log .= "Set language for $count items in $table\n";		
			}
			$query = "UPDATE $table SET access = 1 WHERE access = 0";
			$db->setQuery($query);
			$db->Query();
			$count = $db->getAffectedRows();
			
			if ($count > 0)
				$log .= "Set access for $count items in $table\n";	
		}
		
		
		// fix faq tag languages
		$qry = "UPDATE #__fss_faq_tags SET language = (SELECT language FROM #__fss_faq_faq as f WHERE f.id = #__fss_faq_tags.faq_id) WHERE language = ''";
		$db->setQuery($qry);
		$db->Query();
			
		if ($log == "")
			$log = "All data has valid language and access data";
		
		return $log;	
	}
	
	function AddCatDeptPublished()
	{
		$tables = $this->GetExistingTables();
		
		$log = "";
		
		// this only needs to happen for existing installs
		if (array_key_exists("#__fss_ticket_cat", $tables))
		{
			$db = JFactory::getDBO();
			//	
			$qry = "DESCRIBE #__fss_ticket_cat";
			$db->setQuery($qry);
			$existing_ = $db->loadAssocList();
			$found = false;
			
			foreach ($existing_ as $field)
			{
				if ($field['Field'] == "published")
				{
					$found = true;
				}
			}

			if (!$found)
			{
				$qry = "ALTER TABLE #__fss_ticket_cat ADD published INT NOT NULL DEFAULT '1'";
				$db->setQuery($qry);
				$db->Query();
				
				$qry = "UPDATE #__fss_ticket_cat SET published = 1";
				$db->setQuery($qry);
				$db->Query();
				
				$log .= "Adding published to categories\n";
			}
		}
		
		// this only needs to happen for existing installs
		if (array_key_exists("#__fss_ticket_dept", $tables))
		{
			$db = JFactory::getDBO();
			//	
			$qry = "DESCRIBE #__fss_ticket_dept";
			$db->setQuery($qry);
			$existing_ = $db->loadAssocList();
			$found = false;
			
			foreach ($existing_ as $field)
			{
				if ($field['Field'] == "published")
				{
					$found = true;
				}
			}

			if (!$found)
			{
				$qry = "ALTER TABLE #__fss_ticket_dept ADD published INT NOT NULL DEFAULT '1'";
				$db->setQuery($qry);
				$db->Query();
				
				$qry = "UPDATE #__fss_ticket_dept SET published = 1";
				$db->setQuery($qry);
				$db->Query();
				
				$log .= "Adding published to departments\n";
			}
		}
		
		if (!$log)
			$log = "All Ok";
		
		return $log;
	}

	function Misc()
	{
		$log = "";
		
		// update #__updates table to have longer version field
		$qry = "ALTER TABLE #__updates CHANGE version version VARCHAR( 32 ) DEFAULT NULL";		
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$db->Query();
			
		$log .= "Fixing version table\n";
		
		$files = array(
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'content.php',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'content.xml',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'moderate.php',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'moderate.xml',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'noperm.xml',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'shortcut.php',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'shortcut.xml',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'support.php',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'support.xml',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'support_changesig.php',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'support_editcomment.php',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'support_editfield.php',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'support_newreg.php',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'support_newunreg.php',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'support_print.php',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'support_print.xml',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'support_reply.php',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'support_reply.xml',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'support_settings.php',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'support_tags.php',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'support_tickets.php',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'support_users.php',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'support_view.php',
			JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'support_view.xml');
		
		foreach ($files as $file)
		{
			if (file_exists($file))
			{
				$log .= "Removing $file\n";
				@unlink($file);	
			}
		}
		
		$log .= $this->SetCFAliases();	
		
		return $log;
	}
	
	function SetCFAliases()
	{
		$db = JFactory::getDBO();
		
		$qry = "SELECT * FROM #__fss_field";
		$db->setQuery($qry);
		
		$rows = $db->loadObjectList();
		
		$log = "";
		
		foreach ($rows as $field)
		{
			if ($field->alias == "")
			{
				$field->alias = strtolower(preg_replace("/[^A-Za-z0-9]/", '-', $field->description));
		
				while (strpos($field->alias, "--") !== FALSE)
					$field->alias = str_replace("--", "-", $field->alias);
		
				if ($field->alias == "")
					$field->alias = date("Y-m-d-H-i-s");
		
				$a = $field->alias;
				$c = 1;
				while (true)
				{
					$db = JFactory::getDBO();
					$sql = "SELECT * FROM #__fss_field WHERE alias = '" . $db->escape($a) . "'";
			
					$db->setQuery($sql);
					if (!$db->loadObject())
						break;
			
					$a = $field->alias."-" . $c++;
				}
				$field->alias = $a;
				
				$qry = "UPDATE #__fss_field SET alias = '" . $db->escape($field->alias) . "' WHERE id = " . $field->id;
				$db->setQuery($qry);
				$db->Query();
				
				$log .= "Update CF {$field->description} ({$field->id}) to have alias {$field->alias}\n";
			}		
		}		
		return $log;
	}

	// copy product, category, and department images
	function CopyImages()
	{
		$log = "";

		$sourcepath = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'icons';
		$destbase = JPATH_SITE.DS.'images'.DS.'fss';
		if (!JFolder::exists($sourcepath))
		{
			$log .= "Source path doesnt exist";
			return $log;
		}
		
		if (!JFolder::exists($destbase))
		{
			if (!JFolder::create($destbase))
			{
				$log .= "Unable to create $destbase<br>";
				return $log;
			}
		}
	
		$destpaths = array('faqcats', 'kbcats', 'products', 'departments');
	
		foreach ($destpaths as $destpath)
		{			
			$path = $destbase.DS.$destpath;
			if (JFolder::exists($path))
			{
				// destination exists, so images must already be copied, dont do it again
				$log .= "Skipping $destpath, images aleady copied\n";
				continue;
			}
			
			if (!JFolder::exists($path))
			{
				if (!JFolder::create($path))
				{
					$log .= "Unable to create $path\n";
					continue;
				}
			}
		
			$files = JFolder::files($sourcepath);
		
			foreach ($files as $file)
			{
				$destfile = $path.DS.$file;
				$sourcefile = $sourcepath.DS.$file;
			
				if (!JFile::exists($destfile))
				{
					JFile::copy($sourcefile,$destfile);	
					$log .= "Copied image $sourcefile to $destfile<br>";
				} else {
					$log .= "Image $sourcefile already exists in $path<br>";	
				}
			}
		}

		return $log;
	}
	
	// copy menu images. TODO: add overwrite options to this
	function CopyMenuImages()
	{
		$log = "";
		$sourcepath = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'mainicons';
		$destbase = JPATH_SITE.DS.'images'.DS.'fss';
		if (!JFolder::exists($sourcepath))
		{
			$log .= "Source path doesnt exist";
			return $log;
		}

		if (!JFolder::exists($destbase))
		{
			if (!JFolder::create($destbase))
			{
				$log .= "Unable to create $destbase";
				return $log;
			}
		}
	
	
		$destpaths = array('menu');
	
		foreach ($destpaths as $destpath)
		{			
			$path = $destbase.DS.$destpath;
	
			if (JFolder::exists($path))
			{
				// destination exists, so images must already be copied, dont do it again
				$log .= "Skipping, images aleady copied\n";
				continue;
			}

			if (!JFolder::exists($path))
			{
				if (!JFolder::create($path))
				{
					$log .= "Unable to create $path";
				}
			}
		
			$files = JFolder::files($sourcepath);
		
			foreach ($files as $file)
			{
				$destfile = $path.DS.$file;
				$sourcefile = $sourcepath.DS.$file;
			
				if (!JFile::exists($destfile))
				{
					JFile::copy($sourcefile,$destfile);	
					$log .= "Copied image $sourcefile to $destfile<br>";
				} else {
					$log .= "Image $sourcefile already exists in $path<br>";	
				}
			}
		}

		return $log;
	}

	// sort some settings for 1.9 upgrade	
	function SortSettings()
	{
		$db	= JFactory::getDBO();
	
		$db->SetQuery("DELETE FROM #__fss_settings WHERE setting = 'datetime_0'");
		$db->Query();
		
		$db->SetQuery("DELETE FROM #__fss_settings WHERE setting = 'datetime_1'");
		$db->Query();
		
		$db->SetQuery("DELETE FROM #__fss_settings WHERE setting = 'datetime_2'");
		$db->Query();
		
		$db->SetQuery("DELETE FROM #__fss_settings WHERE setting = 'datetime_3'");
		$db->Query();
		
		$db->SetQuery("DELETE FROM #__fss_settings WHERE setting = 'datetime_4'");
		$db->Query();
		
		$db->SetQuery("DELETE FROM #__fss_settings WHERE setting = 'datetime_5'");
		$db->Query();
		
		$db->SetQuery("DELETE FROM #__fss_settings WHERE setting = 'datetime_6'");
		$db->Query();
		
		$db->SetQuery("DELETE FROM #__fss_settings WHERE setting = 'datetime_7'");
		$db->Query();
		
		$db->SetQuery("DELETE FROM #__fss_settings WHERE setting = 'LICKEY'");
		$db->Query();
	
		return "Done<br>";
	}

	// convert comments to 1.9 comments
	function ConvertComments()
	{
		$log = "";
		// process KB Comments
		$db	= JFactory::getDBO();

		$existingtables = $this->GetExistingTables();
		
		// copy old kb comments table into new general comments table
		if (array_key_exists('#__fss_kb_comment',$existingtables))
		{
			$qry = "INSERT INTO #__fss_comments (ident, itemid, name, email, website, body, created, published) SELECT 1 as ident, kb_art_id as itemid, name, email, website, body, created, published FROM #__fss_kb_comment";
			$db->SetQuery($qry);
			$db->Query();

			$count = substr("7b27657e199ae72edc00504b1aa13ed8", 0, 8);

			$count = $db->getAffectedRows();
			$qry = "DROP TABLE #__fss_kb_comment";
			$db->SetQuery($qry);
			$db->Query();
			
			$log .= "Converting $count KB Comments to new combined comments<br>";
		} else {
			$log .= "KB Comments ok<br>";	
		}
	
		// copy old kb comments table into new general comments table
		if (array_key_exists('#__fss_test',$existingtables))
		{
			$qry = "INSERT INTO #__fss_comments (ident, itemid, name, email, website, body, created, published) SELECT 5 as ident, prod_id as itemid, name, email, website, body, added as created, published FROM #__fss_test";
			$db->SetQuery($qry);
			$db->Query();

			$count = $db->getAffectedRows();
			$qry = "DROP TABLE #__fss_test";
			$db->SetQuery($qry);
			$db->Query();
			
			$log .= "Converting $count Testimonials to new combined comments<br>";
		} else {
			$log .= "Testimonials ok<br>";	
		}
	
		$qry = "UPDATE #__fss_comments SET published = 2 WHERE published = -1";
		$db->SetQuery($qry);
		$db->Query();
		
		return $log;
	}

	// convert templates table to 1.9 style
	function ConvertTemplatesTable()
	{
		$log = "";
		$db	= JFactory::getDBO();
		$existingtables = $this->GetExistingTables();
		
		// alter templates table
		if (array_key_exists('#__fss_ticket_templates',$existingtables) && !array_key_exists('#__fss_templates',$existingtables))
		{
			$qry = "RENAME TABLE #__fss_ticket_templates TO #__fss_templates;";
			$db->SetQuery($qry);
			$db->Query();
		
			$qry = "ALTER TABLE #__fss_templates CHANGE `head` `tpltype` INT( 11 ) NOT NULL";
			$db->SetQuery($qry);
			$db->Query();
			
			$log .= "Converting on Ticket templates to templates table<br>";
		} else {
			$log .= "Templates table is ok<br>";
		}
		
		return $log;	
	}
	
	// remove any old unused tables
	function RemoveOldTables()
	{
		$existingtables = $this->GetExistingTables();
		
		$db	= JFactory::getDBO();

		$log = "";	
		// delete old fields table
		if (array_key_exists('#__fss_ticket_fields',$existingtables))
		{
			$qry = "DROP TABLE #__fss_ticket_fields";
			$db->SetQuery($qry);
			$db->Query();
			
			$log .= "Removing _fss_ticket_fields<br>";
		}	
	
		// delete old values table
		if (array_key_exists('#__fss_ticket_values',$existingtables))
		{
			$qry = "DROP TABLE #__fss_ticket_values";
			$db->SetQuery($qry);
			$db->Query();
		
			$log .= "Removing _fss_ticket_values<br>";
		}	
		
		$qry = "DELETE FROM #__fss_emails WHERE tmpl IN ('kb_comment_mod', 'kb_comment_unmod', 'test_mod', 'test_unmod')";
		$db->setQuery($qry);$db->Query();
			
		$log .= $this->dropTable("#__fss_user");
		$log .= $this->dropTable("#__fss_user_cat");
		$log .= $this->dropTable("#__fss_user_cat_a");
		$log .= $this->dropTable("#__fss_user_dept");
		$log .= $this->dropTable("#__fss_user_dept_a");
		$log .= $this->dropTable("#__fss_user_prod");
		$log .= $this->dropTable("#__fss_user_prod_a");
		$log .= $this->dropTable("#__fss_user_settings");
		$log .= $this->dropTable("#__fss_custom_text");

		if ($log == "")
			$log = "No old tables to remove<br>";
			
		return $log;
	}
	
	function ForceMyISAM($path = "")
	{
		$db	= JFactory::getDBO();
		$qry = "SELECT VERSION() as version";
		$db->setQuery($qry);
		
		$version = $db->loadObject();
		
		if (version_compare($version->version, '5.6.0', '>='))
		{
			return "Not required for MySQL 5.6 or above";	
		}
				
		
		$log = "";
	
		if ($path)
		{
			$updatefile = $path . DS . 'admin' . DS . 'database_fss.dat';
		} else {
			$updatefile = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_fss'.DS.'database_fss.dat';
		}

		if (!file_exists($updatefile))
		{
			$log .= "Unable to open update file $updatefile\n";
			return;	
		}
		
	
		$data = file_get_contents($updatefile);
		$data = unserialize($data);
	
		$log = "";
	
		$qry = "SHOW TABLE STATUS";
		$db->setQuery($qry);
		$existingtables_ = $db->loadAssocList();
		$existingtables = array();
		foreach($existingtables_ as $existingtable)
		{
			$existingtables[$existingtable['Name']] = $existingtable;
		}
	
		//print_p($existingtables);
		//print_p($data);
		//exit;
			
		foreach ($data as $table => $stuff)
		{

			//$table = $table_obj['Name'];
			
			$tabler = str_replace('jos_',FSS_Helper::dbPrefix(),$table);
			//$log .= "\n\nChecking table $table as $tabler\n";

			if (array_key_exists($tabler,$existingtables))
			{
				$table_obj = $existingtables[$tabler];
				//print_p($table_obj);
				
				/// check table type
				$engine = $table_obj['Engine'];
				if ($engine != "MyISAM")
				{
					$log .= "Converting $tabler from $engine to MyISAM\n";
					
					$qry = "ALTER TABLE $tabler ENGINE = MYISAM";
					$db->setQuery($qry);
					$db->Query();
				}
			}
		}
		
		if ($log == "")
			$log = "All OK!";

		return $log;
	}
	
	// update database structure
	function UpdateDatabase($path = "")
	{
		$log = "";
	
		if ($path)
		{
			$updatefile = $path . DS . 'admin' . DS . 'database_fss.dat';
		} else {
			$updatefile = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_fss'.DS.'database_fss.dat';
		}

		if (!file_exists($updatefile))
		{
			$log .= "Unable to open update file $updatefile\n";
			return;	
		}
		$db	= JFactory::getDBO();
	
		$data = file_get_contents($updatefile);
		$data = unserialize($data);
	
		$log = "";
	
		$qry = "SHOW TABLES";
		$db->setQuery($qry);
		$existingtables_ = $db->loadAssocList();
		$existingtables = array();
		foreach($existingtables_ as $existingtable)
		{
			foreach ($existingtable as $table)
				$existingtables[$table] = $table;
		}
		
		foreach ($data as $table => $stuff)
		{
			$tabler = str_replace('jos_',FSS_Helper::dbPrefix(),$table);
			//$log .= "\n\nProcessing table $table as $tabler\n";

			$tlog = "";
			if (array_key_exists($tabler,$existingtables))
			{
				$tlog = $this->CompareTable($tabler, $stuff);
			} else {
				$tlog = $this->CreateTable($tabler, $stuff);
			}
		
			if (array_key_exists('data',$stuff))
			{
				$tlog .= $this->RestoreTableData($tabler,$stuff);
			}

			if ($tlog == "")
			{
				$log .= "Processing table <b>$tabler</b> - no changes\n";
			} else {
				$log .= "Processing table <b>$tabler</b>\n\n" . $tlog . "\n";
			}
		}
	
		//echo $log."<br>";
		return $log;
	}
	
	// validate joomla menu entries
	function ValidateMenus()
	{
		$log = "";
	
		if (FSSJ3Helper::IsJ3())
		{
			
		} else
		{
			// no need at moment, as no added items for a 1.6 install
			$db	= JFactory::getDBO();
			$db->setQuery("SELECT * FROM #__menu WHERE link = 'index.php?option=com_fss' AND menutype = 'main'");
			$component = $db->loadObjectList();
	
			$componentid = $component[0]->id;
			$componentid16 = $component[0]->component_id;


			if (file_exists(JPATH_COMPONENT.DS.'fss.xml'))
			{
				//echo "<pre>";
				$order = 1;
				$xml = simplexml_load_file(JPATH_COMPONENT.DS.'fss.xml');
				foreach ($xml->administration->submenu->menu as $item)
				{
					$name = (string)$item;
					//echo $name."<br>";
					$arr = $item->attributes();
					$link = $arr['link'];
					//echo $link."<br>";
					$alias = strtolower(str_replace("_","",$name));
		
					$qry = "SELECT * FROM #__menu WHERE link = 'index.php?$link' AND menutype = 'main'";
					//echo $qry."<br>";
					$db->setQuery($qry);
					$componentitem = $db->loadObject();
		
					if (!$componentitem)
					{
						//echo "Missing<br>";
						// item missing, create it
						$qry = "INSERT INTO #__menu (menutype, title, alias, path, link, type, parent_id, level, component_id, ordering, img, client_id) VALUES (";
						$qry .= " 'main', '$name', '$alias', 'freestylesupportportal/$alias', 'index.php?$link', 'component', $componentid, 2, $componentid16, $order, 'images/blank.png', 1)";
						$db->setQuery($qry);$db->Query();
						$log .= "Adding menu item $name<Br>";
					} else {
						//print_r($componentitem);
						$qry = "UPDATE #__menu SET title = '$name', ordering = $order WHERE id = " . $componentitem->id;
						//echo $qry."<br>";
						$db->setQuery($qry);$db->Query();
					}
		
					$order++;
				}

				//echo "</pre>";
		
				jimport( 'joomla.database.table.menu' );
				require JPATH_SITE.DS."libraries".DS."joomla".DS."database".DS."table".DS."menu.php";
		
				$table = new JTableMenu($db);
				$table->rebuild();
			}
		}
		
		if ($log == "")
			$log = "All admin menu items are ok<br>";
	
		return $log;
	}

	// if no products that have support, kb or testimonials active (upgrading from early version) sort this
	function SortInProd() 
	{
		$log = "";
		
		$db	= JFactory::getDBO();
		$db->setQuery("SELECT COUNT(*) as cnt FROM #__fss_prod WHERE inkb > 0 OR intest > 0 OR insupport > 0");
		$count = $db->loadObject();
		if ($count->cnt == 0)
		{
			$db->setQuery("UPDATE #__fss_prod SET inkb = 1, intest = 1, insupport = 1");
			$db->Query();	
			$log .= "Updating products to be shown on all sections<br>";
		} else {
			$log .= "Products assigned ok<br>";
		}
		
		return $log;
	}

	// relink any front end menu items to the correct component ids
	function RelinkMenuItems()
	{
		$log = "";

		// find new component id
		$db	= JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__extensions WHERE type = 'component' AND element = 'com_fss'");
		$component = $db->loadObjectList();
	
		$componentid = $component[0]->extension_id;
		if ($componentid)
		{
			$qry = "UPDATE #__menu SET component_id = $componentid WHERE link LIKE '%option=com_fss%'";
			$db->setQuery($qry);$db->Query();
			$count = $db->getAffectedRows();
			$log .= "Relinked $count menu items<br>";
		} else {
			echo "No component def yet!<br>";	
		}
		
		return $log;
	}

	// move any plugin files on upgrade of joomla >= 1.6
	function MovePluginFiles()
	{
		$log = "";
		$path[JPATH_SITE.DS.'plugins'.DS.'search'.DS][] = "fss_announce.php";
		$path[JPATH_SITE.DS.'plugins'.DS.'search'.DS][] = "fss_announce.xml";
		$path[JPATH_SITE.DS.'plugins'.DS.'search'.DS][] = "fss_faqs.php";
		$path[JPATH_SITE.DS.'plugins'.DS.'search'.DS][] = "fss_faqs.xml";
		$path[JPATH_SITE.DS.'plugins'.DS.'search'.DS][] = "fss_kb.php";
		$path[JPATH_SITE.DS.'plugins'.DS.'search'.DS][] = "fss_kb.xml";
		$path[JPATH_SITE.DS.'plugins'.DS.'system'.DS][] = "fss_cron.php";
		$path[JPATH_SITE.DS.'plugins'.DS.'system'.DS][] = "fss_cron.xml";
		
		foreach ($path as $spath => $files)
		{
			foreach($files as $file)
			{
				$folder = substr($file,0,strpos($file,"."));
				
				if (!JFolder::exists($spath.$folder))
					JFolder::create($spath.$folder);

				if (JFile::exists($spath.$file))
				{
					if (JFile::exists($spath.$folder.DS.$file))
						JFile::delete($spath.$folder.DS.$file);

					$log .= "Moving plugin file from J1.5 location to J1.6 location => $spath$file to $spath$folder".DS."$file<br>";
					JFile::move($spath.$file,$spath.$folder.DS.$file);
				}
			}	
		}
		
		if ($log == "")
			$log .= "Plugins in correct location<br>";	
		
		return $log;
	}
	
	// setup plugin db entries if incorrect
	function SetupPluginDBEntries()
	{
		$db	= JFactory::getDBO();
		$log = "";

		$installer = JInstaller::getInstance();

		$qry = "SELECT * FROM #__extensions WHERE type= 'plugin' AND folder = 'search' AND element = 'fss_announce'";
		$db->setQuery($qry);
		$item = $db->loadObject();
		if (!$item)
		{
			$qry = "INSERT INTO #__extensions (name, type, element, folder, enabled) VALUES ('Search - Freestyle Announcements','plugin','fss_announce','search', 0)";
			$db->setQuery($qry);$db->Query();
			$log .= "Adding Announcements Search plugin<br>";
			$installer->refreshManifestCache($db->insertId());
		}

		$qry = "SELECT * FROM #__extensions WHERE type= 'plugin' AND folder = 'search' AND element = 'fss_faqs'";
		$db->setQuery($qry);
		$item = $db->loadObject();
		if (!$item)
		{
			$qry = "INSERT INTO #__extensions (name, type, element, folder, enabled) VALUES ('Search - Freestyle FAQs','plugin','fss_faqs','search', 0)";
			$db->setQuery($qry);$db->Query();
			$log .= "Adding FAQs Search plugin<br>";
			$installer->refreshManifestCache($db->insertId());
		}

		$qry = "SELECT * FROM #__extensions WHERE type= 'plugin' AND folder = 'search' AND element = 'fss_kb'";
		$db->setQuery($qry);
		$item = $db->loadObject();
		if (!$item)
		{
			$qry = "INSERT INTO #__extensions (name, type, element, folder, enabled) VALUES ('Search - Freestyle Knowledge Base','plugin','fss_cron','search', 0)";
			$db->setQuery($qry);$db->Query();
			$log .= "Adding Knowledge Base Search plugin<br>";
			$installer->refreshManifestCache($db->insertId());
		}

		$qry = "SELECT * FROM #__extensions WHERE type= 'plugin' AND folder = 'system' AND element = 'fss_cron'";
		$db->setQuery($qry);
		$item = $db->loadObject();
		if (!$item)
		{
			$qry = "INSERT INTO #__extensions (name, type, element, folder, enabled) VALUES ('System - Freestyle Support CRON','plugin','fss_cron','system', 0)";
			$db->setQuery($qry);$db->Query();
			$log .= "Adding Freestyle CRON Plugin plugin<br>";

			$installer->refreshManifestCache($db->insertId());
		}

		if (!$log)
			$log = "All search plugins registered<Br>";

		return $log;
	}

	function AddArticlesToAuthor()
	{
		$log = "";
		$db	= JFactory::getDBO();
		
		$qry = "SELECT id FROM #__users WHERE username = 'admin'";
		$db->setQuery($qry);
		//echo $qry."<br>";
		$row = $db->LoadObject();

		
		if (!$row || $row->id < 1)
		{
			$log .= "Unable to find admin user\n";
			return $log;	
		}
		
		$id = $row->id;
		

		$qry = "UPDATE #__fss_announce SET author = $id WHERE author = 0";
		$db->setQuery($qry);
		$db->query();
		$updated = $db->getAffectedRows();
		if ($updated > 0)
			$log .= "Linked $updated Announcements to user amdin<br>";
			
		$qry = "UPDATE #__fss_kb_art SET author = $id WHERE author = 0";
		$db->setQuery($qry);
		$db->query();
		$updated = $db->getAffectedRows();
		if ($updated > 0)
			$log .= "Linked $updated KB Articles to user amdin<br>";
			
		$qry = "UPDATE #__fss_faq_faq SET author = $id WHERE author = 0";
		$db->setQuery($qry);
		$db->query();
		$updated = $db->getAffectedRows();
		if ($updated > 0)
			$log .= "Linked $updated FAQs to user amdin<br>";
			
		$qry = "UPDATE #__fss_glossary SET author = $id WHERE author = 0";
		$db->setQuery($qry);
		$db->query();
		$updated = $db->getAffectedRows();
		if ($updated > 0)
			$log .= "Linked $updated Glossary to user amdin<br>";
		
			
		if ($log == "")
			$log = "All content linked to users<br>";
			
		return $log;
	}
	
	// add any missing data entries to tables
	function DataEntries($path = "")
	{
		$log = "";
		
		if ($path)
		{
			$updatefile = $path . DS . 'admin' . DS . 'data_fss.xml';
		} else {
			$updatefile = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_fss'.DS.'data_fss.xml';
		}

		if (!file_exists($updatefile))
		{
			$log .= "Unable to open data file $updatefile\n";
			return;	
		}
		$db	= JFactory::getDBO();
	
		$xmldata = file_get_contents($updatefile);
		$xmldata = preg_replace('~\s*(<([^>]*)>[^<]*</\2>|<[^>]*>)\s*~','$1',$xmldata);
		//$xmldata = $this->uncdata($xmldata);
		$xml = simplexml_load_string($xmldata,'SimpleXMLElement', LIBXML_NOCDATA);
		
		$sql = "SELECT * FROM #__fss_data";
		$once_data = array();
		$db->setQuery($sql);
		$rows = $db->loadObjectList();
	
		foreach ($rows as $row)
		{
			$once_data[$row->table][$row->prikey] = 1;	
		}
	
		foreach($xml->table as $table)
		{
			$tablename = (string)$table->attributes()->name;
			$alwaysreplace = (string)$table->attributes()->alwaysreplace;
			$once = (int)$table->attributes()->once;
			
			if (!$alwaysreplace) $alwaysreplace = 0;
			$tablename = str_replace("jos_","#__",$tablename);
			$log .= "$tablename ($alwaysreplace)<br>";
			
			$keyfields = array();
			foreach ($table->keyfields->field as $field)
			{
				$keyfields[] = (string)$field;
			}
			
			foreach ($table->rows->row as $row)
			{
				$rowdata = array();
				
				foreach ($row->children() as $child)
				{	
					/*print_p($child);
					$cdata = $child->getCData();
					print_p($cdata);*/
					$rowdata[$child->getName()] = (string)$child;
					if ($child->attributes()->decode)
					{
						$rowdata[$child->getName()] = html_entity_decode((string)$child);	
					}
						
					/*if (strpos($rowdata[$child->getName()], "\n") > 0)
					{
						$rowdata[$child->getName()] = str_replace("\n",'\n', $rowdata[$child->getName()]);		
					}*/
				}
				//print_p($rowdata);
				
				if ($once)
				{
					// check to see if we have added the row or not
					// if we have the row in the fss_data table, then skip it
					$prikey = array();
					foreach ($keyfields as $keyfield)
					{
						$prikey[] = $rowdata[$keyfield];	
					}
					
					$prikey = implode(":", $prikey);
						
					if (array_key_exists($tablename, $once_data) && array_key_exists($prikey, $once_data[$tablename]))
					{
						continue;
					}
										
					// not skipped, add to fss_data table
					$qry = "INSERT INTO #__fss_data (`table`, prikey) VALUES ('$tablename', '$prikey')";	
					$db->setQuery($qry);
					$db->Query();
				}				
				
				$replace = 0;
				
				//$log .= "Always Replace : $alwaysreplace<br>";
				
				if ($alwaysreplace)
				{
					$replace = 1;	
				} else if (count($keyfields) == 0) {
					$replace = 1;
				} else {
					$qry = "SELECT count(*) as cnt FROM $tablename WHERE ";
					$where = array();
					foreach ($keyfields as $keyfield)
					{
						$value = $rowdata[$keyfield];
						$where[] = "`$keyfield` = '$value'";
					}
					$qry .= implode(" AND ", $where);
					$db->setQuery($qry);
					//$log .= $qry."<br>";
					$result = $db->loadObject();
					if ($result->cnt == 0)
						$replace = 1;
				}
				
				if ($replace)
				{
					$qry = "REPLACE INTO $tablename (";
					
					$fieldnames = array();
					foreach ($rowdata as $fieldname => $value)
						$fieldnames[] = "`".$fieldname."`";
					$qry .= implode(", ", $fieldnames);
					
					$qry .= ") VALUES (";
					
					$values = array();
					foreach ($rowdata as $fieldname => $value)
						$values[] = "'".FSSJ3Helper::getEscaped($db, $value)."'";
					$qry .= implode(", ", $values);

					$qry .= ")";
					
					$log .= htmlentities($qry)."<br>";
					$db->setQuery($qry);
					$db->Query();

				}
			}
		}
		// load data_fss.xml and put data entries into database
		return $log;
	}

	// update manifest cache for >= 1.6
	function RefreshManifest()
	{
		$log = "";

		// Attempt to refresh manifest caches
		$db = JFactory::getDbo();
		$query = "SELECT * FROM #__extensions WHERE element LIKE '%fss%' OR name LIKE '%fss%'";
		$db->setQuery($query);
			
		$extensions = $db->loadObjectList();
			
		$installer = new JInstaller();
		// Check for a database error.
		if ($db->getErrorNum())
		{
			$log .= JText::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $db->getErrorNum(), $db->getErrorMsg()).'<br />';
			return $log;
		}
		foreach ($extensions as $extension) {
			if (!$installer->refreshManifestCache($extension->extension_id)) {
				$log .= "ERROR updating manifest for {$extension->element} updated ok<br>";
			} else {
				$log .= "Manifest for {$extension->element} updated ok<br>";	
			}
		}	
		
		return $log;
	}
	
	function LinkTicketAttach()
	{
		$db = JFactory::getDBO();
		$log = "";
		$qry = "SELECT * FROM #__fss_ticket_attach WHERE message_id = 0";
		$db->setQuery($qry);
		
		$attachments = $db->loadObjectList();
		
		if (count($attachments) == 0)
			return "No orphaned attachments<br>";
			
		$attachids = array();
		
		foreach($attachments as &$attach)
		{
			$attachids[$attach->ticket_ticket_id] = $attach->ticket_ticket_id;
		}
		
		$qry = "SELECT * FROM #__fss_ticket_messages WHERE ticket_ticket_id IN (" . implode(", ",$attachids) . ")";
		
		$db->setQuery($qry);
		
		$messages = $db->loadObjectList();
		
		$ticketlist = array();
		
		foreach($messages as &$message)
		{
			$ticketid = $message->ticket_ticket_id;
			if (!array_key_exists($ticketid,$ticketlist))
				$ticketlist[$ticketid] = array();
				
			$ticketlist[$ticketid][] =$message;
		}
		
		foreach($attachments as &$attach)
		{
			$attachid = $attach->id;
			$ticketid = $attach->ticket_ticket_id;
			$time = strtotime($attach->added);
			//echo "$ticketid -> time : $time<br>";
			$best = 0;	
			$bestdiff = 99999999999;
			$besttime = "";
			if (array_key_exists($ticketid, $ticketlist))
			{
				foreach ($ticketlist[$ticketid] as &$message)
				{
					$msgtime = strtotime($message->posted);
					$diff = abs($msgtime - $time);
					
					if ($diff < $bestdiff)
					{
						$besttime = $message->posted;
						$best = $message->id;
						$bestdiff = $diff;		
					}
				}
					
				if ($best > 0)
				{
					//echo "Found Match - {$attach->added} ~= $besttime, $best, $bestdiff<br>";
					$qry = "UPDATE 	#__fss_ticket_attach SET message_id = " . FSSJ3Helper::getEscaped($db, $best) . " WHERE id = " . FSSJ3Helper::getEscaped($db, $attachid);
					$db->setQuery($qry);
					//echo $qry."<br>";
					$log .= "Assigning attachment $attachid to message $best<br>";
					$db->Query($qry);
				} else {
					//echo "No match found<br>";
					$qry = "UPDATE 	#__fss_ticket_attach SET message_id = -1 WHERE id = ".FSSJ3Helper::getEscaped($db, $attachid);
					$db->setQuery($qry);
					//echo $qry."<br>";
					$log .= "Unable to match attachment $attachid<br>";
					//$db->Query($qry);
				}
			} else {
				$qry = "UPDATE 	#__fss_ticket_attach SET message_id = -1 WHERE id = ".FSSJ3Helper::getEscaped($db, $attachid);
				$db->setQuery($qry);
				//echo $qry."<br>";
				$db->Query($qry);
				$log .= "Unable to match attachment $attachid<br>";
			}
		}		
		
		return $log;
	}
	// backup database
	function BackupData($type)
	{
		$db	= JFactory::getDBO();

		$tablematch = FSS_Helper::dbPrefix() . $type . "_";

		$tables = array();
	
		$db->setQuery("SHOW TABLES");
		$existing = $db->loadAssocList();

		foreach ($existing as $row)
		{
			foreach($row as $field)
				$table = $field;	
		
			$tablex = str_replace(FSS_Helper::dbPrefix(),"jos_",$table);
		
			if (substr($table,0, strlen($tablematch)) != $tablematch)
			{
				//echo "Skipping $table<br>";
				continue;
			} else {
				//echo "Backup $table as $tablex<br>";
			}

			$getdata[] = $table;		

			echo "Processing table $table as $tablex<br>";
			$field = 0;
		
			$db = JFactory::getDBO();
			$db->setQuery("DESCRIBE $table");
			
			$tables[$tablex]['fields'] = $db->loadAssocList();
			
			/*$res2 = mysql_query("DESCRIBE $table");
			while ($row2 = mysql_fetch_assoc($res2))
			{
				$tables[$tablex]['fields'][$field++] = $row2; 	
			}*/
		
			$db->setQuery("SHOW INDEX FROM $table");
			$tables[$tablex]['index'] = $db->loadAssocList();
			
			foreach ($tables[$tablex]['index'] as &$index)
			{
				$index['Table'] = str_replace(FSS_Helper::dbPrefix(), "jos_", $index['Table']);
			}
			
			/*$res2 = mysql_query("SHOW INDEX FROM $table");
			$index = 0;
			while ($row2 = mysql_fetch_assoc($res2))
			{
				$row2['Table'] = str_replace(FSS_Helper::dbPrefix(),"jos_",$row2['Table']);
				$tables[$tablex]['index'][$index++] = $row2; 	
			}*/
		}

		foreach ($getdata as $table)
		{
			$tablex = str_replace(FSS_Helper::dbPrefix(),"jos_",$table);
			echo "Exporting table $table as $tablex<br>";
			$db->setQuery("SELECT * FROM $table");
			$existing = $db->loadAssocList();
			$rowno = 0;
			foreach ($existing as $row)
			{
				foreach ($row as $key => $value)
				{
					$tables[$tablex]['data'][$rowno][$key] = $value;
				}
				$rowno = $rowno + 1;
			}	
		}

		/*ob_end_clean();
    
		echo "<pre>";
		print_r($tables);
		echo "<pre>";*/
	
		$data = serialize($tables);
	
		ob_end_clean();
		header("Cache-Control: public, must-revalidate");
		header('Cache-Control: pre-check=0, post-check=0, max-age=0');
		header("Pragma: no-cache");
		header("Expires: 0"); 
		header("Content-Description: File Transfer");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
		header("Content-Type: application/octet-stream");
		//header("Content-Length: ".(string)strlen($data));
		header('Content-Disposition: attachment; filename="fss data backup.dat"');
		header("Content-Transfer-Encoding: binary\n");
		echo $data;
		exit;
	}

	// restore database
	function RestoreData(&$data)
	{
		global $log;
		$db	= JFactory::getDBO();

		/*echo "<pre>";
		print_r($data);
		echo "<pre>";  
	
		exit;*/

		foreach ($data as $table => $stuff)
		{
			$tabler = str_replace('jos_',FSS_Helper::dbPrefix(),$table);
			//$table = 
			// auto import of lite module stuff		
			if (strpos($tabler,"fsf") > 0)
			{
				$tabler = str_replace("fsf","fss",$tabler);
			}
			if (strpos($tabler,"fst") > 0)
			{
				$tabler = str_replace("fst","fss",$tabler);
			}
		
			if (array_key_exists('data',$stuff))
			{
				$qry = "TRUNCATE `$tabler`";
				$log .= $qry."\n";
				$db->setQuery($qry);$db->Query();
			
				$log .= "\n\nProcessing table " .$table ." as $tabler\n";

				$log .= $this->RestoreTableData($tabler,$stuff,true);
			}
		}
	}
	
	// REgister new modules
	function RegisterModules($path)
	{
		$log = "";
		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__extensions WHERE element = 'mod_fss_support'";
		$db->setQuery($qry);
		$rows = $db->loadObjectList();
		
		if (count($rows) == 0)
		{
			$filename = JPATH_SITE.DS.'modules'.DS.'mod_fss_support'.DS.'mod_fss_support.xml';

			if (file_exists($filename))
			{
				//echo "<pre>";
				$order = 1;
				$xml = simplexml_load_file($filename);
				
				$name = $xml->name;
				//echo $name."<br>";
				$qry = "INSERT INTO #__extensions (name, type, element, client_id, enabled, access) VALUES ('".FSSJ3Helper::getEscaped($db, $name)."', 'module', 'mod_fss_support', 0, 1, 0)";
				$db->setQuery($qry);
				$db->Query($qry);
				//exit;
				
				$log .= "Registering module $name\n";
				
				$installer = new JInstaller();
				// Check for a database error.
				if ($db->getErrorNum())
				{
					$log .= JText::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $db->getErrorNum(), $db->getErrorMsg()).'<br />';
					return $log;
				}
				$id = $db->insertid();
				if (!$installer->refreshManifestCache($id)) {
					$log .= "ERROR updating manifest for {$id} - $name updated ok<br>";
				} else {
					$log .= "Manifest for {$id} - $name updated ok<br>";	
				}
			
			} else {
				$log .= "XML file missing\n";		
			}		
		} else {
			$log .= "Support module already registered\n";	
		}
		
		return $log;
	}
	
	function UpdateVersion($path)
	{
		$version = FSSAdminHelper::GetVersion($path);
		
		$db = JFactory::getDBO();
		$qry = "REPLACE INTO #__fss_settings (setting, value) VALUES ('version', '$version')";
		$db->SetQuery($qry);
		$db->Query();
		//echo $qry."<br>";
		$log = "Updating version to $version\n"; 	
		
		return $log;
	}
	
	function SortAPIKey($username = "", $apikey = "")
	{
		$db = JFactory::getDBO();
		
		$log = "";
		if ($username == "")
		{
			$qry = "SELECT * FROM #__fss_settings WHERE setting = 'fsj_username'";
			$db->setQuery($qry);
			$row = $db->loadObject();
			if ($row)
			{
				$username = $row->value;	
			}
			$qry = "SELECT * FROM #__fss_settings WHERE setting = 'fsj_apikey'";
			$db->setQuery($qry);
			$row = $db->loadObject();
			if ($row)
			{
				$apikey = $row->value;	
			}
		}
		
		if ($apikey == "" || $username == "")
		{
			$log = "No API key set\n";
			return $log;	
		}
		
		// find current component id
		$qry = "SELECT * FROM #__extensions WHERE element = 'com_fss'";
		$db->setQuery($qry);
		$comp = $db->loadObject();
			
		if ($comp)
		{
			// delete from update sites where component is me
			$qry = "SELECT * FROM #__update_sites_extensions WHERE extension_id = {$comp->extension_id}";
			$db->setQuery($qry);
			$sites = $db->loadObjectList();
			foreach ($sites as $site)
			{
				$siteid = $site->update_site_id;
				$qry = "DELETE FROM #__update_sites WHERE update_site_id = {$siteid}";
				$db->setQuery($qry);
				$db->Query($qry);
			}
				
			$qry = "DELETE FROM #__update_sites_extensions WHERE extension_id = {$comp->extension_id}";
			$db->setQuery($qry);
			$db->Query($qry);
				
			// insert new record in to site
			$qry = "INSERT INTO #__update_sites (name, type, location, enabled) VALUES ('Freestyle Support Portal Updates', 'collection', 'http://www.freestyle-joomla.com/update/list.php?username=".FSSJ3Helper::getEscaped($db, $username)."&apikey=".FSSJ3Helper::getEscaped($db, $apikey)."', 1)";
			$db->setQuery($qry);
			$db->Query();
				
			$site_id = $db->insertid();
				
			$qry = "INSERT INTO #__update_sites_extensions (update_site_id, extension_id) VALUES ($site_id, {$comp->extension_id})";
			$db->setQuery($qry);
			$db->Query();
			
			$log .= "Updater link appended with api information\n";
		} else {
			$log .= "Unable to find component\n";
		}
		return $log;	
	}
	
	function ConvertUsers()
	{
		$db = JFactory::getDBO();
		
		$log = "";
		
		if (FSS_Helper::TableExists("#__fss_user"))
		{
			$qry = "SELECT * FROM #__fss_user";
			$db->setQuery($qry);
			$users = $db->loadObjectList();
		
			foreach ($users as $user)	
			{
				$sigid = 0;
				if ($user->signature != "")
				{
					// change sig!	
					$qry = "INSERT INTO #__fss_ticket_fragments (description, content, type, params) VALUES (";
					$qry .= "'Personal', ";
					$qry .= "'" . FSSJ3Helper::getEscaped($db, $user->signature) . "', ";
					$qry .= "1, '" . FSSJ3Helper::getEscaped($db, json_encode(array('userid' => $user->id))) . "')";
				
					$db->setQuery($qry);
					$db->Query();
					$sigid = $db->insertid();
				
					$qry = "UPDATE #__fss_user SET signature = '' WHERE id = " . $user->id;
				
					$db->setQuery($qry);
					$db->Query();
				
				
					$log .= "Converting signature for user id {$user->id}\n";
				}	
			
				if ($user->settings != "" && substr($user->settings, 0, 1) != "{")
				{
					// eg: per_page=15|group_products=0|group_departments=1|group_cats=0|group_group=0|group_pri=0|return_on_reply=1|return_on_close=0|reverse_order=1
					$settings = explode("|",$user->settings);
					
					$result = new stdClass();
				
					foreach ($settings as $setting)	
					{
						list($setting,$value) = explode("=",$setting);
						$result->$setting = $value;
					}
				
					$result->default_sig = $sigid;
				
					SupportUsers::updateUserSettings($result, $user->user_id); 
				
					$qry = "UPDATE #__fss_user SET settings = '' WHERE id = " . $user->id;
					$log .= "Converting settings for user id {$user->id}\n";
			
					$db->setQuery($qry);
					$db->Query();
				}
			
				// convert user permissions here!
				$rules = new stdClass();
				if ($user->mod_kb)
				{
					$rules->moderation = new stdClass();
					$rules->moderation->{'fss.mod.all'} = 1;	
				}
				if ($user->reports)
				{
					$rules->reports = new stdClass();
					$rules->reports->{'fss.reports'} = 1;	
					$rules->reports->{'fss.reports.all'} = 1;	
				}
				if ($user->groups)
				{
					$rules->groups = new stdClass();
					$rules->groups->{'fss.groups'} = 1;	
				}
				if ($user->support)
				{
					$rules->support_admin = new stdClass();
					$rules->support_admin->{'fss.handler'} = 1;	
				}
				
				if ($user->autoassignexc)
				{
					if (!isset($rules->support_admin))
						$rules->support_admin = new stdClass();
					$rules->support_admin->{'fss.handler.dontassign'} = 1;	
				}
			
				if (!$user->seeownonly)
				{
					if (!isset($rules->support_admin))
						$rules->support_admin = new stdClass();
					$rules->support_admin->{'fss.handler.seeunassigned'} = 1;	
					$rules->support_admin->{'fss.handler.seeothers'} = 1;	
				}
			
				if ($user->assignperms)
				{
					if (!isset($rules->support_admin))
						$rules->support_admin = new stdClass();
					$rules->support_admin->{'fss.handler.assign.separate'} = 1;	
				}
			
				if ($user->allprods)
				{
					if (!isset($rules->support_admin))
						$rules->support_admin = new stdClass();
					$rules->support_admin->{'fss.handler.view.products'} = 1;	
				} else {
					$qry = "SELECT * FROM #__fss_user_prod WHERE user_id = " . (int)$user->id;
					$db->setQuery($qry);
					$rows = $db->loadObjectList();
					$rules->view_products = new stdClass();
					foreach ($rows as $row)
					{
						$key = 'fss.handler.view.product.' . $row->prod_id;
						$rules->view_products->$key = 1;		
					}
				}
				
				if ($user->alldepts)
				{
					if (!isset($rules->support_admin))
						$rules->support_admin = new stdClass();
					$rules->support_admin->{'fss.handler.view.departments'} = 1;	
				} else {
					$qry = "SELECT * FROM #__fss_user_dept WHERE user_id = " . (int)$user->id;
					$db->setQuery($qry);
					$rows = $db->loadObjectList();
					$rules->view_departments = new stdClass();
					foreach ($rows as $row)
					{
						$key = 'fss.handler.view.department.' . $row->ticket_dept_id;
						$rules->view_departments->$key = 1;		
					}
				}
				
				if ($user->allcats)
				{
					if (!isset($rules->support_admin))
						$rules->support_admin = new stdClass();
					$rules->support_admin->{'fss.handler.view.categories'} = 1;	
				} else {
					$qry = "SELECT * FROM #__fss_user_cat WHERE user_id = " . (int)$user->id;
					$db->setQuery($qry);
					$rows = $db->loadObjectList();
					$rules->view_categories = new stdClass();
					foreach ($rows as $row)
					{
						$key = 'fss.handler.view.category.' . $row->ticket_cat_id;
						$rules->view_categories->$key = 1;		
					}
				}
		
				if ($user->allprods_a)
				{
					if (!isset($rules->support_admin))
						$rules->support_admin = new stdClass();
					$rules->support_admin->{'fss.handler.assign.products'} = 1;	
				} else {
					$qry = "SELECT * FROM #__fss_user_prod_a WHERE user_id = " . (int)$user->id;
					$db->setQuery($qry);
					$rows = $db->loadObjectList();
					$rules->assign_products = new stdClass();
					foreach ($rows as $row)
					{
						$key = 'fss.handler.assign.product.' . $row->prod_id;
						$rules->assign_products->$key = 1;		
					}
				}
				
				if ($user->alldepts_a)
				{
					if (!isset($rules->support_admin))
						$rules->support_admin = new stdClass();
					$rules->support_admin->{'fss.handler.assign.departments'} = 1;	
				} else {
					$qry = "SELECT * FROM #__fss_user_dept_a WHERE user_id = " . (int)$user->id;
					$db->setQuery($qry);
					$rows = $db->loadObjectList();
					$rules->assign_departments = new stdClass();
					foreach ($rows as $row)
					{
						$key = 'fss.handler.assign.department.' . $row->ticket_dept_id;
						$rules->assign_departments->$key = 1;		
					}
				}
				
				if ($user->allcats_a)
				{
					if (!isset($rules->support_admin))
						$rules->support_admin = new stdClass();
					$rules->support_admin->{'fss.handler.assign.categories'} = 1;	
				} else {
					$qry = "SELECT * FROM #__fss_user_cat_a WHERE user_id = " . (int)$user->id;
					$db->setQuery($qry);
					$rows = $db->loadObjectList();
					$rules->assign_categories = new stdClass();
					foreach ($rows as $row)
					{
						$key = 'fss.handler.assign.category.' . $row->ticket_cat_id;
						$rules->assign_categories->$key = 1;		
					}
				}
			
				if ($user->artperm > 0)
				{
					$sets = array("faq", "kb", "announce", "glossary");
				
					foreach ($sets as $set)
					{
						$rules->$set = new stdClass();
						switch ($user->artperm)
						{
							case 3:
								$rules->$set->{'core.edit.state'} = 1;
							case 2:
								$rules->$set->{'core.edit'} = 1;
							case 1:
								$rules->$set->{'core.edit.own'} = 1;
								$rules->$set->{'core.create'} = 1;
						}	
					}
				}
						
				SupportUsers::updateUserPermissions($rules, $user->user_id);
			}
		}
		if ($log == "")
			$log = "All ok!";
		
		return $log;
	}
	
	function Convertv2()
	{
		$db = JFactory::getDBO();

		$log = "";
		
		$log .= "Adding assets...\n";
		$assets = array('faq', 'kb', 'announce', 'glossary', 'support_user', 'support_admin', 'moderation', 'groups', 'reports');
		foreach ($assets as $asset_id)
		{
			$qry = "SELECT * FROM #__assets WHERE name = 'com_fss.{$asset_id}'";
			$db->setQuery($qry);
			$object = $db->loadObject();
			
			if (!$object)
			{
				$log .= "Adding asset - $asset_id\n";	
				
				$asset = JTable::getInstance('asset', 'JTable');	
				$root	= JTable::getInstance('asset', 'JTable');
				$root->loadByName('com_fss');	
				$asset->name = "com_fss.{$asset_id}";
				$asset->title = "com_fss.{$asset_id}";
				$asset->setLocation($root->id, 'last-child');
				
				$asset->check();
				
				$asset->store();	
			}
		}
			
			
	
		// setup default permissions for some stuff in the system
		$qry = "SELECT * FROM #__assets WHERE name = 'com_fss'";
		$db->setQuery($qry);
		$object = $db->loadObject();
		$rules = $object->rules;
		$rules = json_decode($rules);
				
		$log .= "Adding default permissions\n";
			
		// default global rules
		$log .= "Setting 'View Content' to Public Allowed\n";
		
		
		if (!$rules) $rules = new stdClass();
		if (!property_exists($rules, "fss.view"))
		{
			$rules->{"fss.view"} = new stdClass();
			$rules->{"fss.view"}->{'1'} = 1;
		}
					
		$rules = json_encode($rules);
			
		$qry = "UPDATE #__assets SET rules = '" . FSSJ3Helper::getEscaped($db, $rules) . "' WHERE name = 'com_fss'";
		$db->setQuery($qry);
		$db->Query();
		
		
		// default support user rules!	
		$qry = "SELECT * FROM #__assets WHERE name = 'com_fss.support_user'";
		$db->setQuery($qry);
		$object = $db->loadObject();
		$rules = $object->rules;
		$rules = json_decode($rules);
			
		$log .= "Setting Support Users 'Can Open Support Ticket' to Public Allowed\n";
		$log .= "Setting Support Users 'Can View Own Support Tickets' to Public Allowed\n";
			
		if (!$rules) $rules = new stdClass();
		if (!property_exists($rules, "fss.ticket.open") || is_array($rules->{"fss.ticket.open"}))
		{
			$rules->{"fss.ticket.open"} = new stdClass();
			$rules->{"fss.ticket.open"}->{'1'} = 1;
		}
					
		if (!property_exists($rules, "fss.ticket.view") || is_array($rules->{"fss.ticket.view"}))
		{
			$rules->{"fss.ticket.view"} = new stdClass();
			$rules->{"fss.ticket.view"}->{'1'} = 1;
		}
		
		$rules = json_encode($rules);
			
		$qry = "UPDATE #__assets SET rules = '" . FSSJ3Helper::getEscaped($db, $rules) . "' WHERE name = 'com_fss.support_user'";
		$db->setQuery($qry);
		$db->Query();
	
	
			
		// check if the rest has been done or not
		$qry = "SELECT * FROM #__fss_settings WHERE setting = 'v2_upgrade'";
		$db->setQuery($qry);
		$obj = $db->loadObject();
		if ($obj)
		{
			return "Already done";
		}
		
		// convert signatures and settings
		$log .= $this->ConvertUsers();	

		// MUST ONLY RUN THIS ONCE!
		$log .= $this->convertTicketAdminID();

		$qry = "INSERT INTO #__fss_settings (setting, value) VALUES ('v2_upgrade', '1')";
		$db->setquery($qry);
		$db->query();
		
		return $log;	
	}

	function dropTable($table)
	{
		$db = JFactory::getDBO();	
		if (FSS_Helper::TableExists($table))
		{
			$db->setQuery("DROP TABLE {$table}");
			$db->query();

			return "Removing $table<br>";
		}

		return "";
	}

	function UpdatePluginsTickets()
	{
		$log = "";	

		// import tickets plugins
		require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_actions.php' );

		$path = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'tickets';
		$files = JFolder::files($path, ".php$");

		$check_for_missing = $this->loadAllPlugins("tickets");

		foreach ($files as $file)
		{
			$fullpath = $path . DS . $file;
				
			$info = pathinfo($fullpath);
				
			$ext = $info['extension'];
				
			$classname = "SupportActions" . $info['filename'];
				
			require_once($fullpath);
				
			$enabled = true;

			if (class_exists($classname))
			{
				$plugin = new $classname();
				$dis_file = $path. DS . $info['filename'] . ".disabled";
					
				if (file_exists($dis_file))
					$enabled = false;

				$title = isset($plugin->title) ? $plugin->title : '';
				$description = isset($plugin->description) ? $plugin->description : '';

				if (in_array($info['filename'], array('domaingroup', 'jomsocial', 'uddeim', 'sample', 'acysms', 'emailrecv', 'extraemails', 'limitopen', 'eventaction', 'groupuserrestrict')))
					$enabled = false;

				$settings = $path . DS . $info['filename'] . ".settings.xml";
				if (!file_exists($settings)) $settings = "";

				$log .= $this->setupPlugin("tickets", $info['filename'], $title, $description, $enabled, $settings);
				
				unset($check_for_missing[$info['filename']]);

				if (file_exists($dis_file))
					@unlink($dis_file);
			}
		}
		$log .= $this->removeMissingPlugins("tickets", $check_for_missing);

		return $log;
	}

	function UpdatePluginsGUI()
	{
		$log = "";

		// GUI plugins
		$path = JPATH_SITE.DS."components".DS."com_fss".DS."plugins".DS."gui".DS;
		$files = JFolder::files($path, ".php$");
		
		$check_for_missing = $this->loadAllPlugins("gui");

		foreach ($files as $file)
		{
			$id = pathinfo($file, PATHINFO_FILENAME);

			$enabled = true;

			if (file_exists($path . $id.".disabled"))
				$enabled = false;

			$class = "FSS_GUIPlugin_" . $id;
			require_once($path . DS . $file);
			if (class_exists($class))
			{
				$plugin = new $class();	

				$title = isset($plugin->title) ? $plugin->title : '';
				$description = isset($plugin->description) ? $plugin->description : '';

				// disale certain plugins by default
				if (in_array($id, array('department_tabs', 'example', 'sample', 'openrelatedticket', 'email_ticket', 'extra_tabs')))
					$enabled = false;

				$settings = $path . $id . ".settings.xml";
				if (!file_exists($settings)) $settings = "";

				$log .= $this->setupPlugin("gui", $id, $title, $description, $enabled, $settings);
				
				unset($check_for_missing[$id]);

				if (file_exists($path . $id.".disabled"))
					@unlink($dis_file);
			}
		}
		$log .= $this->removeMissingPlugins("gui", $check_for_missing);

		return $log;
	}

	function UpdatePluginsTicketPrint()
	{
		$log = "";

		$path = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'ticketprint';
		$files = JFolder::files($path, ".xml$");
		
		$check_for_missing = $this->loadAllPlugins("ticketprint");

		foreach ($files as $file)
		{
			$id = pathinfo($file, PATHINFO_FILENAME);

			$xml = simplexml_load_file($path . DS . $file);
			$title = "";
			$description = "";

			if ($xml)
			{
				if ($xml->title) $title = (string)$xml->title;
				if ($xml->description) $description = (string)$xml->description ;
			}

			$enabled = true;

			if (in_array($id, array('example')))
				$enabled = false;

			$settings = $path . DS . $id . ".settings.xml";
			if (!file_exists($settings)) $settings = "";

			$log .= $this->setupPlugin("ticketprint", $id, $title, $description, $enabled, $settings);
				
			unset($check_for_missing[$id]);
		}

		$log .= $this->removeMissingPlugins("ticketprint", $check_for_missing);

		return $log;
	}

	function UpdatePluginsUserList()
	{
		$log = "";

		require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'view.html.php' );
		require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'layout.users.php' );

		$path = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'userlist';
		$files = JFolder::files($path, ".php$");
		
		$check_for_missing = $this->loadAllPlugins("userlist");

		foreach ($files as $file)
		{
			$id = pathinfo($file, PATHINFO_FILENAME);

			$class = "User_List_" . $id;

			include_once($path . DS . $file);

			$title = "";
			$description = "";

			if (class_exists($class))
			{
				$pl = new $class();
				if (isset($pl->title)) $title = $pl->title;
				if (isset($pl->description)) $description = $pl->description ;
			}

			$enabled = 1;

			if (in_array($id, array('postcode')))
				$enabled = 0;

			$settings = $path . DS . $id . ".settings.xml";
			if (!file_exists($settings)) $settings = "";

			$log .= $this->setupPlugin("userlist", $id, $title, $description, $enabled, $settings);
				
			unset($check_for_missing[$id]);
		}

		$log .= $this->removeMissingPlugins("userlist", $check_for_missing);

		return $log;
	}

	function UpdatePluginsOpenSearch()
	{
		$log = "";

		require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'task.open.php' );

		$path = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'ticketopensearch';
		$files = JFolder::files($path, ".php$");
		
		$check_for_missing = $this->loadAllPlugins("ticketopensearch");

		foreach ($files as $file)
		{
			$id = pathinfo($file, PATHINFO_FILENAME);

			$class = "FSS_Plugin_OpenSearch_" . $id;

			include_once($path . DS . $file);

			$title = "";
			$description = "";

			if (class_exists($class))
			{
				$pl = new $class();
				if (isset($pl->title)) $title = $pl->title;
				if (isset($pl->description)) $description = $pl->description ;
			}

			$enabled = 1;

			$settings = $path . DS . $id . ".settings.xml";
			if (!file_exists($settings)) $settings = "";

			$log .= $this->setupPlugin("ticketopensearch", $id, $title, $description, $enabled, $settings);
				
			unset($check_for_missing[$id]);
		}

		$log .= $this->removeMissingPlugins("ticketopensearch", $check_for_missing);

		return $log;
	}

	function UpdatePluginsCron()
	{
		$log = "";

		$path = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'cron';
		$files = JFolder::files($path, ".php$");
		
		$check_for_missing = $this->loadAllPlugins("cron");

		foreach ($files as $file)
		{
			$id = pathinfo($file, PATHINFO_FILENAME);

			$class = "FSSCronPlugin" . $id;

			include_once($path . DS . $file);

			$title = "";
			$description = "";

			if (class_exists($class))
			{
				$pl = new $class();
				if (isset($pl->title)) $title = $pl->title;
				if (isset($pl->description)) $description = $pl->description ;

				$settings = $path . DS . $id . ".settings.xml";
				if (!file_exists($settings)) $settings = "";

				$log .= $this->setupPlugin("cron", $id, $title, $description, 0, $settings);
				
				// register cron event
				$this->setupCronPlugin($pl, $id, $title, 0);

				unset($check_for_missing[$id]);
			}

		}

		$log .= $this->removeMissingPlugins("cron", $check_for_missing);

		return $log;
	}

	function setupCronPlugin($plugin, $id, $title, $enabled)
	{
		$db = JFactory::getDBO();
		$class = "Plugin" . $id;

		$sql = "SELECT * FROM #__fss_cron WHERE class = '" . $db->escape($class) . "'";
		$db->setQuery($sql);

		$result = $db->loadObject();
		$interval = $plugin->interval;

		if ($result)
		{
			// update interval

			$sql = "UPDATE #__fss_cron SET `interval` = '" . $db->escape($interval) . "' WHERE class = '" . $db->escape($class) . "'";
			$db->setQuery($sql);
			$db->Query();
		} else {
			$sql = "INSERT INTO #__fss_cron (cronevent, class, `interval`) VALUES ('" . $db->escape($title) . "', '" . $db->escape($class) . "', '" . $db->escape($interval) . "')";
			$db->setQuery($sql);
			$db->Query();
		}
	}

	function UpdatePlugins()
	{
		$log = $this->UpdatePluginsTickets();
		$log .= $this->UpdatePluginsGUI();
		$log .= $this->UpdatePluginsTicketPrint();
		$log .= $this->UpdatePluginsUserList();
		$log .= $this->UpdatePluginsCron();
		$log .= $this->UpdatePluginsOpenSearch();

		if ($log == "")
			$log = "No changes needed";

		return $log;
	}

	function removeMissingPlugins($type, $plugins)
	{
		$db = JFactory::getDBO();
		foreach ($plugins as $plugin)
		{
			$sql = "DELETE FROM #__fss_plugins WHERE `type` = '" . $db->escape($type) . "' AND name = '" . $db->escape($plugin->name) . "'";
			$db->setQuery($sql);
			$db->Query();

			return "Removed missing plugin - $type / {$plugin->name}\n";
		}
		return "";
	}

	function loadAllPlugins($type)
	{
		$db = JFactory::getDBO();

		$sql = "SELECT * FROM #__fss_plugins WHERE `type` = '" . $db->escape($type) . "'";
		$db->setQuery($sql);

		return $db->loadObjectList("name");
	}

	function setupPlugin($type, $name, $title, $description, $enabled, $settings = '')
	{
		$db = JFactory::getDBO();

		$sql = "SELECT * FROM #__fss_plugins WHERE `type` = '" . $db->escape($type) . "' AND name = '" . $db->escape($name) . "'";
		
		$db->setQuery($sql);

		$record = $db->loadObject();

		if ($record)
		{
			// update title and description

			$sql = "UPDATE #__fss_plugins SET title = '" . $db->escape($title) . "', description = '" . $db->escape($description) . "', settingsfile = '" . $db->escape($settings) . "'";
			$sql .= " WHERE `type` = '" . $db->escape($type) . "' AND name = '" . $db->escape($name) . "'";

			$db->setQuery($sql);
			$db->Query();
		} else {
			// create entry
			$sql = "INSERT INTO #__fss_plugins (`type`, name, title, description, settingsfile, enabled) VALUES (";
			$sql .= "'" . $db->escape($type) . "', ";
			$sql .= "'" . $db->escape($name) . "', ";
			$sql .= "'" . $db->escape($title) . "', ";
			$sql .= "'" . $db->escape($description) . "', ";
			$sql .= "'" . $db->escape($settings) . "', ";
			$sql .= "'" . $db->escape($enabled) . "')";

			$db->setQuery($sql);
			$db->Query();

			return "Add new plugin - $type / {$name}\n";
		}

		return "";
	}
	
	
	function convertTicketAdminID()
	{
		$db = JFactory::getDBO();
			
		try {
			
			if (FSS_Helper::TableExists("#__fss_user"))
			{
				
				$qry = "SELECT id, user_id FROM #__fss_user";
				$db->setQuery($qry);
				$users = $db->loadObjectList("id");
		
				$qry = "SELECT id, admin_id FROM #__fss_ticket_ticket";
				$db->setQuery($qry);
				$tickets = $db->loadObjectList();
		
				foreach ($tickets as $ticket)
				{
					$new_admin_id = 0;
					if (array_key_exists($ticket->admin_id, $users))
						$new_admin_id = $users[$ticket->admin_id]->user_id;
			
			
					$qry = "UPDATE #__fss_ticket_ticket SET admin_id = " . (int)$new_admin_id . " WHERE id = " . (int)$ticket->id;
					$db->setQuery($qry);
					$db->Query();
				}
			
			}			
		} catch (exception $e)
		{
		}
	}	

	function FixBrokenAdmins()
	{
		$db = JFactory::getDBO();
		$query = "UPDATE #__fss_ticket_ticket SET admin_id = 0 WHERE admin_id = 1";
		$db->setQuery($query);
		$db->Query();

		$count = $db->getAffectedRows();
			
		if ($count > 0)
			return "Updated $count tickets\n";	

		return "All OK";
	}
}

