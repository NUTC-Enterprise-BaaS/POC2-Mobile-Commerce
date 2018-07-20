<?php
/**------------------------------------------------------------------------
 * com_vikbooking - VikBooking
* ------------------------------------------------------------------------
* author    Alessio Gaggii - e4j - Extensionsforjoomla.com
* copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
* @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* Websites: http://www.extensionsforjoomla.com
* Technical Support:  tech@extensionsforjoomla.com
* ------------------------------------------------------------------------
*/

defined('_JEXEC') OR die('Restricted Area');

class VikBookingTranslator {
	
	public $current_lang;
	public $default_lang;
	public $error;
	private $xml;
	private $all_langs;
	private $dbo;
	private $translations_path_file;
	private $translations_buffer;
	
	public function __construct() {
		$this->current_lang = $this->getCurrentLang();
		$this->default_lang = $this->getDefaultLang();
		$this->error = '';
		$this->xml = '';
		$this->all_langs = array();
		$this->dbo = JFactory::getDBO();
		$this->translations_path_file = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'fields'.DS.'translations.xml';
		$this->translations_buffer = array();
	}
	
	public function getCurrentLang() {
		return JFactory::getLanguage()->getTag();
	}

	public function getDefaultLang($section = 'site') {
		return JComponentHelper::getParams('com_languages')->get($section);
	}

	public function getIniFiles() {
		//Keys = Lang Def composed as VBINIEXPL.strtoupper(Key)
		//Values = Paths to INI Files
		return array(
			'com_vikbooking_front' => array('path' => JPATH_SITE.DS.'language'.DS.'en-GB'.DS.'en-GB.com_vikbooking.ini'),
			'com_vikbooking_admin' => array('path' => JPATH_ADMINISTRATOR.DS.'language'.DS.'en-GB'.DS.'en-GB.com_vikbooking.ini'),
			'com_vikbooking_admin_sys' => array('path' => JPATH_ADMINISTRATOR.DS.'language'.DS.'en-GB'.DS.'en-GB.com_vikbooking.sys.ini'),
			'mod_vikbooking_search' => array('path' => JPATH_SITE.DS.'language'.DS.'en-GB'.DS.'en-GB.mod_vikbooking_search.ini'),
			'mod_vikbooking_horizontalsearch' => array('path' => JPATH_SITE.DS.'language'.DS.'en-GB'.DS.'en-GB.mod_vikbooking_horizontalsearch.ini')
		);
	}

	public function getLanguagesList() {
		$known_langs = JLanguage::getKnownLanguages();
		$langs = array();
		foreach($known_langs as $ltag => $ldet) {
			if($ltag == $this->default_lang) {
				$langs = array($ltag => $ldet) + $langs;
			}else {
				$langs[$ltag] = $ldet;
			}
		}
		$this->all_langs = $langs;
		return $this->all_langs;
	}

	public function getLanguagesTags() {
		return array_keys($this->all_langs);
	}

	public function replacePrefix($str) {
		return $this->dbo->replacePrefix($str);
	}

	public function getTranslationTables() {
		$xml = $this->getTranslationsXML();
		if($xml === false) {
			return false;
		}
		$tables = array();
		foreach ($xml->Translation as $translation) {
			$attr = $translation->attributes();
			$tables[(string)$attr->table] = JText::_((string)$attr->name);
		}
		return $tables;
	}

	/**
	* Returns the translated name of the table given the prefix
	* @param table string
	*/
	public function getTranslationTableName($table) {
		$xml = $this->getTranslationsXML();
		$table_name = '';
		foreach ($xml->Translation as $translation) {
			$attr = $translation->attributes();
			if((string)$attr->table == $table) {
				return JText::_((string)$attr->name);
			}
		}
		return $table_name;
	}

	/**
	* Returns an array with the XML Columns of the given table
	* @param table string
	*/
	public function getTableColumns($table) {
		$xml = $this->getTranslationsXML();
		$cols = array();
		foreach ($xml->Translation as $translation) {
			$attr = $translation->attributes();
			if((string)$attr->table == $table) {
				foreach ($translation->Column as $column) {
					$col_attr = $column->attributes();
					if(!property_exists($col_attr, 'name')) {
						continue;
					}
					$ind = (string)$col_attr->name;
					$cols[$ind]['jlang'] = JText::_((string)$column);
					foreach ($col_attr as $key => $val) {
						$cols[$ind][(string)$key] = (string)$val;
					}
				}
			}
		}
		return $cols;
	}

	/**
	* Returns the db column marked as reference, of the record. Ex. the name of the Room in this record
	* @param cols array
	* @param record array
	*/
	public function getRecordReferenceName($cols, $record) {
		foreach ($cols as $key => $values) {
			if(array_key_exists('reference', $values)) {
				if(array_key_exists($key, $record)) {
					return $record[$key];
				}
			}
		}
		//if not found, not present or empty, return first value of the record
		return $record[key($record)];
	}

	/**
	* Returns the current records for the default language and this table
	* @param table string
	* @param cols array containing the db fields to fetch, result of array_keys($this->getTableColumns())
	*/
	public function getTableDefaultDbValues($table, $cols = array()) {
		$def_vals = array();
		if(!(count($cols) > 0)) {
			$cols = array_keys($this->getTableColumns($table));
			if(!(count($cols) > 0)) {
				$this->setError("Table $table has no Columns.");
			}
		}
		if(count($cols) > 0) {
			$q = "SELECT `id`,".implode(',', $cols)." FROM ".$table." ORDER BY `".$table."`.`id` ASC;";
			$this->dbo->setQuery($q);
			$this->dbo->Query($q);
			if($this->dbo->getNumRows() > 0) {
				$records = $this->dbo->loadAssocList();
				foreach ($records as $record) {
					$ref_id = $record['id'];
					unset($record['id']);
					$def_vals[$ref_id] = $record;
				}
			}else {
				$this->setError("Table ".$this->getTranslationTableName($table)." has no Records.");
			}
		}
		return $def_vals;
	}

	/**
	* Returns the translated records for this table and language
	* @param table string
	* @param lang string
	*/
	public function getTranslatedTable($table, $lang) {
		$translated = array();
		$q = "SELECT * FROM `#__vikbooking_translations` WHERE `table`=".$this->dbo->quote($this->replacePrefix($table))." AND `lang`=".$this->dbo->quote($lang)." ORDER BY `#__vikbooking_translations`.`reference_id` ASC;";
		$this->dbo->setQuery($q);
		$this->dbo->Query($q);
		if($this->dbo->getNumRows() > 0) {
			$records = $this->dbo->loadAssocList();
			foreach ($records as $record) {
				$record['content'] = json_decode($record['content'], true);
				$translated[$record['reference_id']] = $record;
			}
		}
		return $translated;
	}

	/**
	* Main function to translate contents saved in the database
	* @param content array
	* @param table string
	* @param alias_keys array - Key_Values pairs where Key is the ALIAS used and Value is the original field name. Opposite instead for the ID (reference_id)
	* @param ids array - the reference_IDs to be translated, the IDs of the records
	* @param lang string - force the translation to a specific language tag like it-IT
	*/
	public function translateContents(&$content, $table, $alias_keys = array(), $ids = array(), $lang = null) {
		$to_lang = is_null($lang) ? $this->current_lang : $lang;
		//Multilang may be disabled
		if(!$this->allowMultiLanguage()) {
			return $content;
		}
		//Check that requested lang is not the default lang
		if($to_lang == $this->default_lang) {
			return $content;
		}
		//Get all translatable columns of this table
		$cols = $this->getTableColumns($table);
		//Get the reference IDs to be translated
		if(!(count($ids) > 0)) {
			$ids = $this->getReferencesFromContents($content, $alias_keys);
		}
		//Load translations buffer for this table or set the var to an empty array
		$translated = $this->getTranslationsBuffer($table, $ids);
		if(!(count($translated) > 0)) {
			//Load translations from db
			$q = "SELECT * FROM `#__vikbooking_translations` WHERE `table`=".$this->dbo->quote($this->replacePrefix($table))." AND `lang`=".$this->dbo->quote($to_lang). (count($ids) > 0 ? " AND `reference_id` IN (".implode(",", $ids).")" : "") .";";
			$this->dbo->setQuery($q);
			$this->dbo->Query($q);
			if($this->dbo->getNumRows() > 0) {
				$records = $this->dbo->loadAssocList();
				foreach ($records as $record) {
					$record['content'] = json_decode($record['content'], true);
					if(count($record['content']) > 0) {
						$translated[$record['reference_id']] = $record['content'];
					}
				}
			}
		}
		if(count($translated) > 0) {
			//Set translations buffer
			$this->translations_buffer[$table] = $translated;
			//Fetch reference_id to be translated and replace default lang values
			$reference_key = array_key_exists('id', $alias_keys) ? $alias_keys['id'] : 'id';
			foreach ($content as $ckey => $cvals) {
				$reference_id = 0;
				if (is_array($cvals)) {
					foreach ($cvals as $subckey => $subcvals) {
						if($subckey == $reference_key) {
							$reference_id = (int)$subcvals;
							break;
						}
					}
					$content[$ckey] = $this->translateArrayValues($cvals, $cols, $reference_id, $alias_keys, $translated);
				}elseif($ckey == $reference_key) {
					$reference_id = (int)$cvals;
					$content = $this->translateArrayValues($content, $cols, $reference_id, $alias_keys, $translated);
					break;
				}
			}
		}
		return $content;
	}

	/**
	* Compares the array to be translated with the translation and replaces the array values if not empty
	* @param content array - default lang values to be translated
	* @param alias_keys array - Key_Values pairs where Key is the ALIAS used and Value is the original field name. Opposite instead for the ID (reference_id)
	*/
	private function getReferencesFromContents($content, $alias_keys) {
		$references = array();
		$reference_key = array_key_exists('id', $alias_keys) ? $alias_keys['id'] : 'id';
		foreach ($content as $ckey => $cvals) {
			if (is_array($cvals)) {
				foreach ($cvals as $subckey => $subcvals) {
					if($subckey == $reference_key) {
						$references[] = (int)$subcvals;
						break;
					}
				}
			}elseif($ckey == $reference_key) {
				$references[] = (int)$cvals;
				break;
			}
		}
		if(count($references) > 0) {
			$references = array_unique($references);
		}

		return $references;
	}

	/**
	* Check whether these reference IDs were already fetched from the db for this table
	* @param table string
	* @param ids array
	*/
	private function getTranslationsBuffer($table, $ids) {
		if(count($this->translations_buffer) && array_key_exists($table, $this->translations_buffer)) {
			$missing = false;
			foreach ($ids as $id) {
				if(!array_key_exists($id, $this->translations_buffer[$table])) {
					$missing = true;
					break;
				}
			}
			if($missing === false) {
				return $this->translations_buffer[$table];
			}
		}

		return array();
	}

	/**
	* Compares the array to be translated with the translation and replaces the array values if not empty
	* @param content array - default lang values to be translated
	* @param cols array - the columns of this table
	* @param reference_id int
	* @param alias_keys array - Key_Values pairs where Key is the ALIAS used and Value is the original field name. Opposite instead for the ID (reference_id)
	* @param translated array
	*/
	private function translateArrayValues($content, $cols, $reference_id, $alias_keys, $translated) {
		if(empty($reference_id)) {
			return $content;
		}
		if(!array_key_exists($reference_id, $translated)) {
			return $content;
		}
		foreach ($content as $key => $value) {
			$native_key = $key;
			if(count($alias_keys) > 0 && array_key_exists($key, $alias_keys) && $key != 'id') {
				$key = $alias_keys[$key];
			}
			if(!array_key_exists($key, $cols)) {
				continue;
			}
			if(array_key_exists($key, $translated[$reference_id]) && strlen($translated[$reference_id][$key]) > 0) {
				$type = $cols[$key]['type'];
				if($type == 'json') {
					//only the translated and not empty keys will be taken from the translation 
					$tn_json = json_decode($translated[$reference_id][$key], true);
					$content_json = json_decode($value, true);
					$jkeys = !empty($cols[$key]['keys']) ? explode(',', $cols[$key]['keys']) : array();
					if(count($content_json) > 0 && count($tn_json) > 0) {
						foreach ($content_json as $jk => $jv) {
							if(array_key_exists($jk, $tn_json) && strlen($tn_json[$jk]) > 0) {
								$content_json[$jk] = $tn_json[$jk];
							}
						}
						$content[$native_key] = json_encode($content_json);
					}
				}else {
					//Field is a text type or a text-derived one
					$content[$native_key] = $translated[$reference_id][$key];
				}
			}
		}
		return $content;
	}

	/**
	* Sets and Returns the SimpleXML object for the translations
	*/
	public function getTranslationsXML() {
		if(!file_exists($this->translations_path_file)) {
			$this->setError($this->translations_path_file.' does not exist or is not readable');
			return false;
		}
		if(!function_exists('simplexml_load_file')) {
			$this->setError('Function simplexml_load_file is not available on the server.');
			return false;
		}
		if(is_object($this->xml)) {
			return $this->xml;
		}
		libxml_use_internal_errors(true);
		if(($xml = simplexml_load_file($this->translations_path_file)) === false) {
			$this->setError("Error reading XML:\n".$this->libxml_display_errors());
			return false;
		}
		$this->xml = $xml;
		return $xml;
	}

	private function allowMultiLanguage($skipsession = false) {
		if(!class_exists('vikbooking')) {
			require_once(JPATH_SITE . DS ."components". DS ."com_vikbooking". DS . "helpers" . DS ."lib.vikbooking.php");
		}
		return vikbooking::allowMultiLanguage($skipsession);
	}

	/**
	* Explanation of the XML error
	* @param error
	*/
	public function libxml_display_error($error) {
		$return = "\n";
		switch ($error->level) {
			case LIBXML_ERR_WARNING :
				$return .= "Warning ".$error->code.": ";
				break;
			case LIBXML_ERR_ERROR :
				$return .= "Error ".$error->code.": ";
				break;
			case LIBXML_ERR_FATAL :
				$return .= "Fatal Error ".$error->code.": ";
				break;
		}
		$return .= trim($error->message);
		if ($error->file) {
			$return .= " in ".$error->file;
		}
		$return .= " on line ".$error->line."\n";
		return $return;
	}

	/**
	* Get the XML errors occurred
	*/
	public function libxml_display_errors() {
		$errorstr = "";
		$errors = libxml_get_errors();
		foreach ($errors as $error) {
			$errorstr .= $this->libxml_display_error($error);
		}
		libxml_clear_errors();
		return $errorstr;
	}

	private function setError($str) {
		$this->error .= $str."\n";
	}

	public function getError() {
		return nl2br(rtrim($this->error, "\n"));
	}
	
}

?>