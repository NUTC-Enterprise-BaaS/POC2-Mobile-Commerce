<?php
/*------------------------------------------------------------------------
# helper.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
defined('_JEXEC') or die();

class OSBHelper
{
	/**
	 * Display Copyright information
	 * 
	 */
	public static function displayCopyRight()
	{
		echo '<div class="copyright" style="text-align:center;margin-top: 5px;"><strong><a href="http://joomdonation.com/joomla-extensions/joomla-services-appointment-booking.html" target="_blank">OS Services Booking</a></strong> version <strong>2.4.6</strong>, Copyright (C) 2015 <strong><a href="http://joomdonation.com" target="_blank">Ossolution Team</a></strong></div>';
	}

	public static function renderSubmenu($task){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__app_sch_menus')
			->where('published = 1')
			->where('parent_id = 0')
			->order('ordering');
		$db->setQuery($query);
		$menus = $db->loadObjectList();
		$html = '';
		$html .= '<div id="submenu-box"><div class="m"><ul class="nav nav-tabs">';
		for ($i = 0; $n = count($menus), $i < $n; $i++)
		{
			$menu = $menus[$i];
			$query->clear();
			$query->select('*')
				->from('#__app_sch_menus')
				->where('published = 1')
				->where('parent_id = ' . intval($menu->id))
				->order('ordering');
			$db->setQuery($query);
			$subMenus = $db->loadObjectList();
			if (!count($subMenus))
			{
				$class = '';
				if ($menu->menu_task == $task)
				{
					$class = ' class="active"';
				}				
				$html .= '<li' . $class . '>' ;
				$html .= '<a href="index.php?option=com_osservicesbooking&task=' . $menu->menu_task . '">';
				if($menu->menu_icon != ""){
					$html .= '<i class="'.$menu->menu_icon.'"></i>&nbsp;';
				}
				$html .= JText::_($menu->menu_name) . '</a></li>';
			}
			else
			{
				$class = ' class="dropdown"';
				for ($j = 0; $m = count($subMenus), $j < $m; $j++)
				{
					$subMenu = $subMenus[$j];
					$lName = JRequest::getVar('layout');
					if ( $task == $subMenu->menu_task )
					{
						$class = ' class="dropdown active"';
						break;
					}else{
						$taskArr = explode("_",$task);
						$task1   = $taskArr[0];
						$taskArr = explode("_",$subMenu->menu_task);
						$task2   = $taskArr[0];
						if (( $task1 == $task2 ) and ($task != "cpanel_list"))
						{
							$class = ' class="dropdown active"';
							break;
						}
					}
				}
				$html .= '<li' . $class . '>';
				$html .= '<a id="drop_' . $menu->id . '" href="#" data-toggle="dropdown" role="button" class="dropdown-toggle">' ;
				if($menu->menu_icon != ""){
					$html .= '<i class="'.$menu->menu_icon.'"></i>&nbsp;';
				}
				$html .= JText::_($menu->menu_name) . ' <b class="caret"></b></a>';
				$html .= '<ul aria-labelledby="drop_' . $menu->id . '" role="menu" class="dropdown-menu" id="menu_' . $menu->id . '">';
				for ($j = 0; $m = count($subMenus), $j < $m; $j++)
				{
					$subMenu = $subMenus[$j];
					$layoutLink = '';
					$class = '';
					$lName = JRequest::getVar('layout');
					if ((!$subMenu->menu_layout && $task == $subMenu->menu_task ) || ($lName != '' && $lName == $subMenu->menu_layout))
					{
						$class = ' class="active"';
					}
					$html .= '<li' . $class . '><a href="index.php?option=com_osservicesbooking&task=' .
						 $subMenu->menu_task . $layoutLink . '" tabindex="-1">' . JText::_($subMenu->menu_name) . '</a></li>';
				}
				$html .= '</ul>';
				$html .= '</li>';
			}
		}
		$html .= '</ul></div></div>';
		if (version_compare(JVERSION, '3.0', 'le'))
		{
			JFactory::getDocument()->setBuffer($html, array('type' => 'modules', 'name' => 'submenu'));
		}
		else
		{
			echo $html;
		}
	}

	public static function loadBootstrap($loadJs = true)
	{
		$document = JFactory::getDocument();
		if ($loadJs)
		{
			$document->addScript(JUri::root() . 'components/com_osservicesbooking/style/bootstrap/js/jquery.min.js');
			$document->addScript(JUri::root() . 'components/com_osservicesbooking/style/bootstrap/js/jquery-noconflict.js');
			$document->addScript(JUri::root() . 'components/com_osservicesbooking/style/bootstrap/js/bootstrap.js');
		}
		$document->addStyleSheet(JURI::root() . 'components/com_osservicesbooking/style/bootstrap/css/bootstrap.css');
		$document->addStyleSheet(JURI::root() . 'components/com_osservicesbooking/style/bootstrap/css/bootstrap-responsive.css');
		
	}
	
	public static function loadBootstrapStylesheet(){
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root() . 'components/com_osservicesbooking/style/bootstrap/css/bootstrap.css');
		$document->addStyleSheet(JURI::root() . 'components/com_osservicesbooking/style/bootstrap/css/bootstrap-responsive.css');
	}
	
	/**
	 * This function is used to load Config and return the Configuration Variable
	 *
	 */
	public static function loadConfig(){
		$db = Jfactory::getDbo();
		$db->setQuery("Select * from #__app_sch_configuation");
		$configs = $db->loadObjectList();
		$configClass = array();
		foreach ($configs as $config) {
			$configClass[$config->config_key] = $config->config_value;
		}
		if($configClass['currency_format'] == ""){
			$configClass['currency_format'] = "USD";
		}
		$db->setQuery("Select currency_symbol from #__app_sch_currencies where currency_code like '".$configClass['currency_format']."'");
		$currency_symbol = $db->loadResult();
		
		$configClass['currency_symbol'] = $currency_symbol;
		return $configClass;
	}
	
/**
	 * Get field suffix used in sql query
	 *
	 * @return string
	 */
	public static function getFieldSuffix($activeLanguage = null)
	{
		$prefix = '';
		if (JLanguageMultilang::isEnabled())
		{
			if (!$activeLanguage)
				$activeLanguage = JFactory::getLanguage()->getTag();
			if ($activeLanguage != self::getDefaultLanguage())
			{
				$prefix = '_' . substr($activeLanguage, 0, 2);
			}
		}
		return $prefix;
	}
	
	
	/**
	 *
	 * Function to get all available languages except the default language
	 * @return languages object list
	 */
	public static function getAllLanguages()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$default = self::getDefaultLanguage();
		$query->select('lang_id, lang_code, title, `sef`')
			->from('#__languages')
			->where('published = 1')
			->order('ordering');
		$db->setQuery($query);
		$languages = $db->loadObjectList();
		return $languages;
	}

	/**
	 *
	 * Function to get all available languages except the default language
	 * @return languages object list
	 */
	public static function getLanguages()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$default = self::getDefaultLanguage();
		$query->select('lang_id, lang_code, title, `sef`')
			->from('#__languages')
			->where('published = 1')
			->where('lang_code != "' . $default . '"')
			->order('ordering');
		$db->setQuery($query);
		$languages = $db->loadObjectList();
		return $languages;
	}

	/**
	 * Get front-end default language
	 * @return string
	 */
	public static function getDefaultLanguage()
	{
		$params = JComponentHelper::getParams('com_languages');
		return $params->get('site', 'en-GB');
	}
	
	/**
	 * Get default language of user
	 *
	 */
	public static function getUserLanguage($user_id){
		$default_language = self::getDefaultLanguage();
		if($user_id > 0){
			$user = JFactory::getUser($user_id);
			$default_language = $user->getParam('language',$default_language);
		}else{
			return $default_language;
		}
		return $default_language;
	}
	
	public static function getLanguageFieldValue($obj,$fieldname){
		global $languages;
		$lgs = self::getLanguages();
		$translatable = JLanguageMultilang::isEnabled() && count($lgs);
		if($translatable){
			$suffix = self::getFieldSuffix();
			$returnValue = $obj->{$fieldname.$suffix};
			if($returnValue == ""){
				$returnValue = $obj->{$fieldname};
			}
		}else{
			$returnValue = $obj->{$fieldname};
		}
		return $returnValue;
	}
	
	public static function getLanguageFieldValueOrder($obj,$fieldname,$lang){
		global $languages;
		$lgs = self::getLanguages();
		$translatable = JLanguageMultilang::isEnabled() && count($lgs);
		$default_language = self::getDefaultLanguage();
		if($lang == ""){
			$lang = $default_language;
		}
		if($translatable){
			//$suffix = self::getFieldSuffix();
			if($default_language != $lang){
				$langugeArr = explode("-",$lang);
				$suffix = "_".$langugeArr[0];
			}
			$returnValue = $obj->{$fieldname.$suffix};
			if($returnValue == ""){
				$returnValue = $obj->{$fieldname};
			}
		}else{
			$returnValue = $obj->{$fieldname};
		}
		return $returnValue;
	}
	
	public static function getLanguageFieldValueBackend($obj,$fieldname,$suffix){
		global $languages;
		$lgs = self::getLanguages();
		$translatable = JLanguageMultilang::isEnabled() && count($lgs);
		if($translatable){
			$returnValue = $obj->{$fieldname.$suffix};
			if($returnValue == ""){
				$returnValue = $obj->{$fieldname};
			}
		}else{
			$returnValue = $obj->{$fieldname};
		}
		return $returnValue;
	}
	
	/**
	 * This function is used to check to see whether we need to update the database to support multilingual or not
	 *
	 * @return boolean
	 */
	public static function isSyncronized()
	{
		$db = JFactory::getDbo();
		//#__osrs_tags
		$fields = array_keys($db->getTableColumns('#__app_sch_venues'));
		$extraLanguages = self::getLanguages();
		if (count($extraLanguages))
		{
			foreach ($extraLanguages as $extraLanguage)
			{
				$prefix = $extraLanguage->sef;
				if (!in_array('address_' . $prefix, $fields))
				{
					return false;
				}
			}
		}
		
		//app_sch_emails
		$fields = array_keys($db->getTableColumns('#__app_sch_emails'));
		$extraLanguages = self::getLanguages();
		if (count($extraLanguages))
		{
			foreach ($extraLanguages as $extraLanguage)
			{
				$prefix = $extraLanguage->sef;
				if (!in_array('email_subject_' . $prefix, $fields))
				{
					return false;
				}
			}
		}
		
		//app_sch_services
		$fields = array_keys($db->getTableColumns('#__app_sch_services'));
		$extraLanguages = self::getLanguages();
		if (count($extraLanguages))
		{
			foreach ($extraLanguages as $extraLanguage)
			{
				$prefix = $extraLanguage->sef;
				if (!in_array('service_name_' . $prefix, $fields))
				{
					return false;
				}
			}
		}
		
		//app_sch_categories
		$fields = array_keys($db->getTableColumns('#__app_sch_categories'));
		$extraLanguages = self::getLanguages();
		if (count($extraLanguages))
		{
			foreach ($extraLanguages as $extraLanguage)
			{
				$prefix = $extraLanguage->sef;
				if (!in_array('category_name_' . $prefix, $fields))
				{
					return false;
				}
			}
		}
		
		//app_sch_fields
		$fields = array_keys($db->getTableColumns('#__app_sch_fields'));
		$extraLanguages = self::getLanguages();
		if (count($extraLanguages))
		{
			foreach ($extraLanguages as $extraLanguage)
			{
				$prefix = $extraLanguage->sef;
				if (!in_array('field_label_' . $prefix, $fields))
				{
					return false;
				}
			}
		}
		
		return true;
	}
	
	/**
	 * Syncronize OS Services Booking database to support multilingual
	 */
	public static function setupMultilingual()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$languages = self::getLanguages();
		if (count($languages))
		{
			//venue table
			$db->setQuery("SHOW COLUMNS FROM #__app_sch_venues");
			$fields = $db->loadObjectList();
			if(count($fields) > 0){
				$fieldArr = array();
				for($i=0;$i<count($fields);$i++){
					$field = $fields[$i];
					$fieldname = $field->Field;
					$fieldArr[$i] = $fieldname;
				}
			}
			foreach ($languages as $language)
			{
				#Process for #__osrs_states table
				$prefix = $language->sef;
				if (!in_array('address_' . $prefix, $fieldArr))
				{
					$fieldName = 'address_' . $prefix;
					$sql = "ALTER TABLE  `#__app_sch_venues` ADD  `$fieldName` VARCHAR( 255 );";
					$db->setQuery($sql);
					$db->query();
					
					$fieldName = 'city_' . $prefix;
					$sql = "ALTER TABLE  `#__app_sch_venues` ADD  `$fieldName` VARCHAR( 50 );";
					$db->setQuery($sql);
					$db->query();
					
					$fieldName = 'state_' . $prefix;
					$sql = "ALTER TABLE  `#__app_sch_venues` ADD  `$fieldName` VARCHAR( 50 );";
					$db->setQuery($sql);
					$db->query();
				}
			}
			
			$db->setQuery("SHOW COLUMNS FROM #__app_sch_emails");
			$fields = $db->loadObjectList();
			if(count($fields) > 0){
				$fieldArr = array();
				for($i=0;$i<count($fields);$i++){
					$field = $fields[$i];
					$fieldname = $field->Field;
					$fieldArr[$i] = $fieldname;
				}
			}
			foreach ($languages as $language)
			{
				#Process for #__osrs_states table
				$prefix = $language->sef;
				if (!in_array('email_subject_' . $prefix, $fieldArr))
				{
					$fieldName = 'email_subject_' . $prefix;
					$sql = "ALTER TABLE  `#__app_sch_emails` ADD  `$fieldName` VARCHAR( 255 );";
					$db->setQuery($sql);
					$db->query();
					
					$fieldName = 'email_content_' . $prefix;
					$sql = "ALTER TABLE  `#__app_sch_emails` ADD  `$fieldName` TEXT;";
					$db->setQuery($sql);
					$db->query();
				}
			}
			
			
			$db->setQuery("SHOW COLUMNS FROM #__app_sch_services");
			$fields = $db->loadObjectList();
			if(count($fields) > 0){
				$fieldArr = array();
				for($i=0;$i<count($fields);$i++){
					$field = $fields[$i];
					$fieldname = $field->Field;
					$fieldArr[$i] = $fieldname;
				}
			}
			foreach ($languages as $language)
			{
				#Process for #__osrs_states table
				$prefix = $language->sef;
				if (!in_array('service_name_' . $prefix, $fieldArr))
				{
					$fieldName = 'service_name_' . $prefix;
					$sql = "ALTER TABLE  `#__app_sch_services` ADD  `$fieldName` VARCHAR( 255 );";
					$db->setQuery($sql);
					$db->query();
					
					$fieldName = 'service_description_' . $prefix;
					$sql = "ALTER TABLE  `#__app_sch_services` ADD  `$fieldName` TEXT;";
					$db->setQuery($sql);
					$db->query();
				}
			}
			
			$db->setQuery("SHOW COLUMNS FROM #__app_sch_categories");
			$fields = $db->loadObjectList();
			if(count($fields) > 0){
				$fieldArr = array();
				for($i=0;$i<count($fields);$i++){
					$field = $fields[$i];
					$fieldname = $field->Field;
					$fieldArr[$i] = $fieldname;
				}
			}
			foreach ($languages as $language)
			{
				#Process for #__osrs_states table
				$prefix = $language->sef;
				if (!in_array('category_name_' . $prefix, $fieldArr))
				{
					$fieldName = 'category_name_' . $prefix;
					$sql = "ALTER TABLE  `#__app_sch_categories` ADD  `$fieldName` VARCHAR( 255 );";
					$db->setQuery($sql);
					$db->query();
					
					$fieldName = 'category_description_' . $prefix;
					$sql = "ALTER TABLE  `#__app_sch_categories` ADD  `$fieldName` TEXT;";
					$db->setQuery($sql);
					$db->query();
				}
			}
			
			
			$db->setQuery("SHOW COLUMNS FROM #__app_sch_fields");
			$fields = $db->loadObjectList();
			if(count($fields) > 0){
				$fieldArr = array();
				for($i=0;$i<count($fields);$i++){
					$field = $fields[$i];
					$fieldname = $field->Field;
					$fieldArr[$i] = $fieldname;
				}
			}
			foreach ($languages as $language)
			{
				#Process for #__osrs_states table
				$prefix = $language->sef;
				if (!in_array('field_label_' . $prefix, $fieldArr))
				{
					$fieldName = 'field_label_' . $prefix;
					$sql = "ALTER TABLE  `#__app_sch_fields` ADD  `$fieldName` VARCHAR( 255 );";
					$db->setQuery($sql);
					$db->query();
				}
			}
			
			$db->setQuery("SHOW COLUMNS FROM #__app_sch_field_options");
			$fields = $db->loadObjectList();
			if(count($fields) > 0){
				$fieldArr = array();
				for($i=0;$i<count($fields);$i++){
					$field = $fields[$i];
					$fieldname = $field->Field;
					$fieldArr[$i] = $fieldname;
				}
			}
			foreach ($languages as $language)
			{
				#Process for #__osrs_states table
				$prefix = $language->sef;
				if (!in_array('field_option_' . $prefix, $fieldArr))
				{
					$fieldName = 'field_option_' . $prefix;
					$sql = "ALTER TABLE  `#__app_sch_field_options` ADD  `$fieldName` VARCHAR( 255 );";
					$db->setQuery($sql);
					$db->query();
				}
			}
		}
	}
	
	public static function getCurrentDate(){
		$config = new JConfig();
		$offset = $config->offset;
		$ctoday = strtotime(JFactory::getDate('now',$offset));
		return $ctoday;
	}
	
	public static function checkDate($date_type){
		$ctoday = self::getCurrentDate();
		$return = array();
		switch ($date_type){
			case "today":
				$start_time = strtotime(date("Y-m-d",$ctoday)." 00:00:01");
				$end_time   = strtotime(date("Y-m-d",$ctoday)." 23:59:59");
				$return[0]  = $start_time;
				$return[1]  = $end_time;
			break;
			case "yesterday":
				$yesterday  = $ctoday -  3600*24;
				$start_time = strtotime(date("Y-m-d",$yesterday)." 00:00:01");
				$end_time   = strtotime(date("Y-m-d",$yesterday)." 23:59:59");
				$return[0]  = $start_time;
				$return[1]  = $end_time;
			break;
			case "current_month": 
				$cmonth		= date("m",$ctoday);
				$start_time = strtotime(date("Y",$ctoday)."-".$cmonth."-01 00:00:01");
				$end_time   = $ctoday;
				$return[0]  = $start_time;
				$return[1]  = $end_time;
			break;
			case "last_month":
				$cmonth		= intval(date("m",$ctoday));
				$cyear		= date("Y",$ctoday);
				if($cmonth == 1){
					$lmonth = 12;
					$lyear  = $cyear-1;
				}else{
					$lmonth = $cmonth - 1;
					$lyear  = $cyear;
				}
				$start_time = strtotime($lyear."-".$lmonth."-01 00:00:01");
				$starttimethismonth = strtotime($cyear."-".$cmonth."-01 00:00:00");
				$end_time   = $starttimethismonth - 1;
				$return[0]  = $start_time;
				$return[1]  = $end_time;
			break;
			case "current_year":
				$cyear		= date("Y",$ctoday);
				$start_time = strtotime($cyear."-01-01 00:00:01");
				$end_time   = $ctoday;
				$return[0]  = $start_time;
				$return[1]  = $end_time;
			break;
			case "last_year":
				$cyear		= date("Y",$ctoday);
				$lyear		= $cyear - 1;
				$start_time = strtotime($lyear."-01-01 00:00:01");
				$starttimethisyear = strtotime($cyear."-01-01 00:00:00");
				$end_time   = strtotime($starttimethisyear-1);
				$return[0]  = $start_time;
				$return[1]  = $end_time;
			break;
		}
		return $return;
	}
	
	/**
	 * Init Availability calendar for Employee In Backend
	 *
	 * @param unknown_type $pid
	 * @param unknown_type $year
	 * @param unknown_type $month
	 */
	function initCalendarInBackend($eid,$year,$month){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);	
		include_once(JPATH_COMPONENT_ADMINISTRATOR.DS."classes".DS."ajax.php");
		$today						= self::getCurrentDate();
		$current_month 				= intval(date("m",$today));
		$current_year				= intval(date("Y",$today));
		$current_date				= intval(date("d",$today));
		//set up the first date
		$start_date_current_month 	= strtotime($year."-".$month."-01");
		$start_date_in_week			= date("N",$start_date_current_month);
		
		$number_days_in_month		= cal_days_in_month(CAL_GREGORIAN,$month,$year);
		
		$monthArr = array( JText::_('OS_JANUARY'), JText::_('OS_FEBRUARY'), JText::_('OS_MARCH'), JText::_('OS_APRIL'), JText::_('OS_MAY'), JText::_('OS_JUNE'), JText::_('OS_JULY'), JText::_('OS_AUGUST'), JText::_('OS_SEPTEMBER'), JText::_('OS_OCTOBER'), JText::_('OS_NOVEMBER'), JText::_('OS_DECEMBER'));
		
		$suffix = "";
		if(!$mainframe->isAdmin()){
			$suffix = "_front";
		}
		?>
		<div id="cal<?php echo intval($month)?><?php echo $year?>">
			<table  width="100%" class="apptable">
				<tr>
					<td width="40%" align="right" style="font-weight:bold;font-size:15px;">
						<a href="javascript:prevBigCal<?php echo $suffix;?>(2,'<?php echo $eid?>')" class="applink">
						<b><</b>
						</a>
					</td>
					<td width="20%" align="center" style="height:25px;font-weight:bold;">
						<?php
						echo $monthArr[$month-1];
						?>
						&nbsp;
						<?php echo $year;?>
					</td>
					<td width="40%" align="left" style="font-weight:bold;font-size:15px;">
						<a href="javascript:nextBigCal<?php echo $suffix;?>(2,'<?php echo $eid?>')" class="applink">
						<b>></b>
						</a>
					</td>
				</tr>
				<tr>
					<td width="100%" colspan="3" style="padding:3px;text-align:center;">
						<select name="ossm" class="input-small" id="ossm" onchange="javascript:updateMonth(this.value)">
							<?php							
							for($i=0;$i<count($monthArr);$i++){
								if(intval($month) == $i + 1){
									$selected = "selected";
								}else{
									$selected = "";
								}
								?>
								<option value="<?php echo $i + 1?>" <?php echo $selected?>><?php echo $monthArr[$i]?></option>
								<?php
							}
							?>
						</select>
						<select name="ossy" class="input-small" id="ossy" onchange="javascript:updateYear(this.value)">
							<?php
							for($i=date("Y",$today);$i<=date("Y",$today)+3;$i++){
								if(intval($year) == $i){
									$selected = "selected";
								}else{
									$selected = "";
								}
								?>
								<option value="<?php echo $i?>" <?php echo $selected?>><?php echo $i?></option>
								<?php
							}
							?>
						</select>
						<input type="button" class="button" value="<?php echo JText::_('OS_GO');?>" onclick="javascript:calendarMovingBigCal<?php echo $suffix;?>(2,'<?php echo $eid?>','<?php echo $item?>');">
					</td>
				</tr>
			</table>
			<table  width="100%">
				<tr>
					<td  width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_MON')?>
						</span>
					</td>
					<td width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_TUE')?>
						</span>
					</td>
					<td width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_WED')?>
						</span>
					</td>
					<td width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_THU')?>
						</span>
					</td>
					<td width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_FRI')?>
						</span>
					</td>
					<td width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_SAT')?>
						</span>
					</td>
					<td width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_SUN')?>
						</span>
					</td>
				</tr>
				<tr>
					<?php
					for($i=1;$i<$start_date_in_week;$i++){
						//empty
						?>
						<td>
						
						</td>
						<?php
					}
					$j = $start_date_in_week-1;
					
					$m = "";
					if(intval($month) < 10){
						$m = "0".$month;
					}else{
						$m = $month;
					}
					$month = $m;
					
					for($i=1;$i<=$number_days_in_month;$i++){
						$j++;
						$nolink = 0;
						//check to see if today
						if(($i == $current_date) and ($month == $current_month) and ($year == $current_year)){
							$bgcolor = "pink";
						}else{
							$bgcolor = "#F1F1F1";
						}
						
						if($i < 10){
							$day = "0".$i;
						}else{
							$day = $i;
						}
						$tempdate1 = strtotime($year."-".$month."-".$day);
						$tempdate2 = strtotime($current_year."-".$current_month."-".$current_date);
						
						if($tempdate1 < $tempdate2){
							$bgcolor = "#ABAAB2";
							$nolink = 4;
						}
						
						if($i < 10){
							$day = "0".$i;
						}else{
							$day = $i;
						}
						$date = $year."-".$month."-".$day;
						?>
						<td id="td_cal_<?php echo $i?>"  align="center" class="td_date" style="text-align:center;" valign="top">
							<div id="a<?php echo $i;?>" style="border:1px solid #efefef;" class="div-rounded">
								<?php
								self::calendarItemAjax($i,$eid,$date);
								?>
							</div>
						</td>
						<?php
						if($j >= 7){
							$j = 0;
							echo "</tr><tr>";
						}
						
					}
					?>
				</tr>
			</table>
			<input type="hidden" name="current_item_value" id="current_item_value" value="" />
		</div>
		<?php
	}
	
	/**
	 * Init Availability calendar for Employee In Backend
	 *
	 * @param unknown_type $pid
	 * @param unknown_type $year
	 * @param unknown_type $month
	 */
	function initEmployeeCalendar($eid,$year,$month){
		global $mainframe,$configClass;
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);
		JHTML::_('behavior.modal','a.osmodal');
		$db = JFactory::getDbo();
		include_once(JPATH_COMPONENT_ADMINISTRATOR.DS."classes".DS."ajax.php");
		$today						= self::getCurrentDate();
		$current_month 				= intval(date("m",$today));
		$current_year				= intval(date("Y",$today));
		$current_date				= intval(date("d",$today));
		//set up the first date
		$start_date_current_month 	= strtotime($year."-".$month."-01");
		$start_date_in_week			= date("N",$start_date_current_month);
		
		$number_days_in_month		= cal_days_in_month(CAL_GREGORIAN,$month,$year);
		
		$monthArr = array( JText::_('OS_JANUARY'), JText::_('OS_FEBRUARY'), JText::_('OS_MARCH'), JText::_('OS_APRIL'), JText::_('OS_MAY'), JText::_('OS_JUNE'), JText::_('OS_JULY'), JText::_('OS_AUGUST'), JText::_('OS_SEPTEMBER'), JText::_('OS_OCTOBER'), JText::_('OS_NOVEMBER'), JText::_('OS_DECEMBER'));
		//$monthArr = array("January","February","March","April","May","June","July","August","September","October","November","December");
		?>
		<div id="cal<?php echo intval($month)?><?php echo $year?>">
			<table  width="100%" class="apptable">
				<tr>
					<td width="40%" align="right" style="font-weight:bold;font-size:15px;">
						<a href="javascript:prevBigCal(1,'<?php echo $eid?>')" class="applink">
						<b><</b>
						</a>
					</td>
					<td width="20%" align="center" style="height:25px;font-weight:bold;">
						<?php
						echo $monthArr[$month-1];
						?>
						&nbsp;
						<?php echo $year;?>
					</td>
					<td width="40%" align="left" style="font-weight:bold;font-size:15px;">
						<a href="javascript:nextBigCal(1,'<?php echo $eid?>')" class="applink">
						<b>></b>
						</a>
					</td>
				</tr>
				<tr>
					<td width="100%" colspan="3" style="padding:3px;text-align:center;">
						<select name="ossm" class="input-small" id="ossm" onchange="javascript:updateMonth(this.value)">
							<?php							
							for($i=0;$i<count($monthArr);$i++){
								if(intval($month) == $i + 1){
									$selected = "selected";
								}else{
									$selected = "";
								}
								?>
								<option value="<?php echo $i + 1?>" <?php echo $selected?>><?php echo $monthArr[$i]?></option>
								<?php
							}
							?>
						</select>
						<select name="ossy" class="input-small" id="ossy" onchange="javascript:updateYear(this.value)">
							<?php
							for($i=date("Y",$today);$i<=date("Y",$today)+3;$i++){
								if(intval($year) == $i){
									$selected = "selected";
								}else{
									$selected = "";
								}
								?>
								<option value="<?php echo $i?>" <?php echo $selected?>><?php echo $i?></option>
								<?php
							}
							?>
						</select>
						<input type="button" class="button" value="Go" onclick="javascript:calendarMovingBigCal(1,'<?php echo $eid?>','<?php echo $item?>');">
					</td>
				</tr>
			</table>
			<table  width="100%">
				<tr>
					<td  width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_MON')?>
						</span>
					</td>
					<td width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_TUE')?>
						</span>
					</td>
					<td width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_WED')?>
						</span>
					</td>
					<td width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_THU')?>
						</span>
					</td>
					<td width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_FRI')?>
						</span>
					</td>
					<td width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_SAT')?>
						</span>
					</td>
					<td width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_SUN')?>
						</span>
					</td>
				</tr>
				<tr>
					<?php
					for($i=1;$i<$start_date_in_week;$i++){
						//empty
						?>
						<td>
						
						</td>
						<?php
					}
					$j = $start_date_in_week-1;
					
					$m = "";
					if(intval($month) < 10){
						$m = "0".$month;
					}else{
						$m = $month;
					}
					$month = $m;
					
					for($i=1;$i<=$number_days_in_month;$i++){
						$j++;
						$nolink = 0;
						//check to see if today
						if(($i == $current_date) and ($month == $current_month) and ($year == $current_year)){
							$bgcolor = "pink";
						}else{
							$bgcolor = "#F1F1F1";
						}
						
						if($i < 10){
							$day = "0".$i;
						}else{
							$day = $i;
						}
						$tempdate1 = strtotime($year."-".$month."-".$day);
						$tempdate2 = strtotime($current_year."-".$current_month."-".$current_date);
						
						if($tempdate1 < $tempdate2){
							$bgcolor = "#ABAAB2";
							$nolink = 4;
						}
						
						if($i < 10){
							$day = "0".$i;
						}else{
							$day = $i;
						}
						$date = $year."-".$month."-".$day;
						?>
						<td id="td_cal_<?php echo $i?>"  align="center" class="td_date" style="text-align:center;" valign="top">
							<?php
							$db->setQuery("SELECT COUNT(id) FROM #__app_sch_employee_rest_days WHERE eid = '$eid' AND rest_date <= '$date' AND rest_date_to >= '$date'");
							$rest = $db->loadResult();
							if($rest > 0){
								$divname = "div-rounded-rest";
							}else if($date == date("Y-m-d",OSBHelper::getCurrentDate())){
								$divname = "div-rounded-current";
							}else{
								$divname = "div-rounded";
							}
							?>
							<div id="a<?php echo $i;?>" style="border:1px solid #efefef;width:90%" class="<?php echo $divname?>">
								<?php
								self::calendarEmployeeItemAjax($i,$eid,$date);
								?>
							</div>
						</td>
						<?php
						if($j >= 7){
							$j = 0;
							echo "</tr><tr>";
						}
						
					}
					?>
				</tr>
			</table>
			<input type="hidden" name="current_item_value" id="current_item_value" value="" />
			<input type="hidden" name="current_td" id="current_td" value="" />
			<input type="hidden" name="date" id="date" value="" />
			<input type="hidden" name="month" id="month" value="<?php echo Jrequest::getVar('month',intval(date("m",HelperOSappscheduleCommon::getRealTime())));?>" />
			<input type="hidden" name="year" id="year" value="<?php echo Jrequest::getVar('year',intval(date("Y",HelperOSappscheduleCommon::getRealTime())));?>" />
		</div>
		<?php
	}
	
	function initCustomerCalendar($year,$month){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);
		include_once(JPATH_COMPONENT_ADMINISTRATOR.DS."classes".DS."ajax.php");
		$today						= self::getCurrentDate();
		$current_month 				= intval(date("m",$today));
		$current_year				= intval(date("Y",$today));
		$current_date				= intval(date("d",$today));
		//set up the first date
		$start_date_current_month 	= strtotime($year."-".$month."-01");
		$start_date_in_week			= date("N",$start_date_current_month);
		$number_days_in_month		= cal_days_in_month(CAL_GREGORIAN,$month,$year);
		$monthArr = array( JText::_('OS_JANUARY'), JText::_('OS_FEBRUARY'), JText::_('OS_MARCH'), JText::_('OS_APRIL'), JText::_('OS_MAY'), JText::_('OS_JUNE'), JText::_('OS_JULY'), JText::_('OS_AUGUST'), JText::_('OS_SEPTEMBER'), JText::_('OS_OCTOBER'), JText::_('OS_NOVEMBER'), JText::_('OS_DECEMBER'));
		//$monthArr = array("January","February","March","April","May","June","July","August","September","October","November","December");
		?>
		<div id="cal<?php echo intval($month)?><?php echo $year?>">
			<table  width="100%" class="apptable">
				<tr>
					<td width="40%" align="right" style="font-weight:bold;font-size:15px;">
						<a href="javascript:prevBigCal(0,'<?php echo $eid?>')" class="applink">
						<b><</b>
						</a>
					</td>
					<td width="20%" align="center" style="height:25px;font-weight:bold;">
						<?php
						echo $monthArr[$month-1];
						?>
						&nbsp;
						<?php echo $year;?>
					</td>
					<td width="40%" align="left" style="font-weight:bold;font-size:15px;">
						<a href="javascript:nextBigCal(0,'<?php echo $eid?>')" class="applink">
						<b>></b>
						</a>
					</td>
				</tr>
				<tr>
					<td width="100%" colspan="3" style="padding:3px;text-align:center;">
						<select name="ossm" class="input-small" id="ossm" onchange="javascript:updateMonth(this.value)">
							<?php							
							for($i=0;$i<count($monthArr);$i++){
								if(intval($month) == $i + 1){
									$selected = "selected";
								}else{
									$selected = "";
								}
								?>
								<option value="<?php echo $i + 1?>" <?php echo $selected?>><?php echo $monthArr[$i]?></option>
								<?php
							}
							?>
						</select>
						<select name="ossy" class="input-small" id="ossy" onchange="javascript:updateYear(this.value)">
							<?php
							for($i=date("Y",$today);$i<=date("Y",$today)+3;$i++){
								if(intval($year) == $i){
									$selected = "selected";
								}else{
									$selected = "";
								}
								?>
								<option value="<?php echo $i?>" <?php echo $selected?>><?php echo $i?></option>
								<?php
							}
							?>
						</select>
						<input type="button" class="btn" value="Go" onclick="javascript:calendarMovingBigCal(0,'<?php echo $eid?>','<?php echo $item?>');">
					</td>
				</tr>
			</table>
			<table width="100%">
				<tr>
					<td  width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_MON')?>
						</span>
					</td>
					<td width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_TUE')?>
						</span>
					</td>
					<td width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_WED')?>
						</span>
					</td>
					<td width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_THU')?>
						</span>
					</td>
					<td width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_FRI')?>
						</span>
					</td>
					<td width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_SAT')?>
						</span>
					</td>
					<td width="14%">
						<span class="header_rounded">
							<?php echo JText::_('OS_SUN')?>
						</span>
					</td>
				</tr>
				<tr>
					<?php
					for($i=1;$i<$start_date_in_week;$i++){
						//empty
						?>
						<td>
						
						</td>
						<?php
					}
					$j = $start_date_in_week-1;
					
					$m = "";
					if(intval($month) < 10){
						$m = "0".$month;
					}else{
						$m = $month;
					}
					$month = $m;
					
					for($i=1;$i<=$number_days_in_month;$i++){
						$j++;
						$nolink = 0;
						//check to see if today
						if(($i == $current_date) and ($month == $current_month) and ($year == $current_year)){
							$bgcolor = "pink";
						}else{
							$bgcolor = "#F1F1F1";
						}
						
						if($i < 10){
							$day = "0".$i;
						}else{
							$day = $i;
						}
						$tempdate1 = strtotime($year."-".$month."-".$day);
						$tempdate2 = strtotime($current_year."-".$current_month."-".$current_date);
						
						if($tempdate1 < $tempdate2){
							$bgcolor = "#ABAAB2";
							$nolink = 4;
						}
						
						if($i < 10){
							$day = "0".$i;
						}else{
							$day = $i;
						}
						$date = $year."-".$month."-".$day;
						?>
						<td id="td_cal_<?php echo $i?>"  align="center" class="td_date" style="text-align:center;" valign="top">
							<div id="a<?php echo $i;?>" style="border:1px solid #efefef;" class="div-rounded">
								<?php
								self::calendarCustomerItemAjax($i,$date);
								?>
							</div>
						</td>
						<?php
						if($j >= 7){
							$j = 0;
							echo "</tr><tr>";
						}
						
					}
					?>
				</tr>
			</table>
			<input type="hidden" name="current_item_value" id="current_item_value" value="" />
			<input type="hidden" name="current_td" id="current_td" value="" />
			<input type="hidden" name="date" id="date" value="" />
			<input type="hidden" name="month" id="month" value="<?php echo Jrequest::getVar('month',intval(date("m",HelperOSappscheduleCommon::getRealTime())));?>" />
			<input type="hidden" name="year" id="year" value="<?php echo Jrequest::getVar('year',intval(date("Y",HelperOSappscheduleCommon::getRealTime())));?>" />
		</div>
		<?php
	}
	
	public static function calendarCustomerItemAjax($i,$day){
		global $mainframe,$configClass;
		$user = JFactory::getUser();
		$db  = JFactory::getDbo();
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);
		$db->setQuery("SELECT a.*,c.service_name FROM #__app_sch_order_items AS a INNER JOIN #__app_sch_orders AS b ON b.id = a.order_id INNER JOIN #__app_sch_services AS c ON c.id = a.sid WHERE b.user_id = '$user->id' AND b.order_status IN ('P','S') AND a.booking_date = '$day'");
		$rows = $db->loadObjectList();
		$class = "";
		?>
		<b>
			<?php
			if($day == date("Y-m-d",OSBHelper::getCurrentDate())){
				echo "<font color='#E3462D'>";
				echo $i;
				echo "</font>";
			}else{
				echo $i;
			}
			?>
		</b>
		<BR />
		<div class="div-schedule">
		<?php
		if(count($rows) > 0){
			for($k=0;$k<count($rows);$k++){
				$row = $rows[$k];
				?>
				<i class="icon-ok"></i>
				<span class="hasTip" title="<?php echo self::generateBookingItem($row,1);?>">
				<?php
				echo date($configClass['time_format'],$row->start_time);
				echo "-";
				echo date($configClass['time_format'],$row->end_time);
				echo "  [".$row->service_name."]";
				?>
				</span>
				<?php if($configClass['allow_cancel_request'] == 1){?>
				<a href="javascript:removeItemCalendar(<?php echo $row->order_id?>,<?php echo $row->id?>,<?php echo $row->sid?>,<?php echo $row->eid?>,<?php echo $i?>,'<?php echo $day?>','<?php echo JText::_('OS_DO_YOU_WANT_T0_REMOVE_ORDER_ITEM')?>','<?php echo JURI::root()?>');" title="<?php echo JText::_('OS_CLICK_HERE_TO_REMOVE_ITEM')?>">
					<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/icon-16-deny.png" border="0"/>
				</a>
				<BR />
				<?php
				}
			}
		}
		?>
		</div>
		<?php
	}
	
	public static function calendarItemAjax($i,$eid,$day){
		global $mainframe,$configClass;
		$db  = JFactory::getDbo();
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);
		$db->setQuery("SELECT COUNT(id) FROM #__app_sch_employee_rest_days WHERE eid = '$eid' AND rest_date = '$day'");
		$rest = $db->loadResult();
		$db->setQuery("SELECT a.*,c.service_name FROM #__app_sch_order_items AS a INNER JOIN #__app_sch_orders AS b ON b.id = a.order_id INNER JOIN #__app_sch_services AS c ON c.id = a.sid WHERE a.eid = '$eid' AND b.order_status IN ('P','S') AND a.booking_date = '$day'");
		$rows = $db->loadObjectList();
		?>
		<b>
			<?php
			if($day == date("Y-m-d",OSBHelper::getCurrentDate())){
				echo "<font color='#E3462D'>";
				echo $i;
				echo "</font>";
			}else{
				echo $i;
			}
			?>
		</b>
		<BR />
		<?php
		if($rest > 0){ //is not avaiable
			?>
			<a href="javascript:removerestday(<?php echo $i?>,'<?php echo $day?>',<?php echo $eid?>,'<?php echo JURI::root()?>');" title="<?php echo JText::_('OS_CLICK_TO_REMOVE_THE_REST_DAY');?>">
			<img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/unpublish.png" />
			</a>
			<?php
			echo  "<font color='#7E1432'>[".JText::_('OS_UNAVAILABLE')."]</font>";
		}else{
			?>
			<a  href="javascript:addrestday(<?php echo $i?>,'<?php echo $day?>',<?php echo $eid?>,'<?php echo JURI::root()?>');" title="<?php echo JText::_('OS_CLICK_TO_ADD_THE_REST_DAY');?>">
				<img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/publish.png" />
			</a>
			<?php	
			echo  "<font color='#2BA396'>[".JText::_('OS_AVAILABLE')."]</font>";
		}
		if($mainframe->isAdmin()){
			?>
			<div class="div-schedule">
			<?php
			if(count($rows) > 0){
				for($k=0;$k<count($rows);$k++){
					$row = $rows[$k];
					echo $k + 1;
					?>
					<span class="hasTip" title="">
					<?php
					echo ". ";
					echo date($configClass['time_format'],$row->start_time);
					echo "-";
					echo date($configClass['time_format'],$row->end_time);
					echo "  [".$row->service_name."]";
					
					echo "<BR />";
					?>
					</span>
					<?php
				}
			}else{
				echo JText::_('OS_NO_BOOKING_REQUEST');
			}
			?>
			</div>
			<?php
		}else{
			?>
			<style>
			.div-rounded{
				min-height:60px !important;
			}
			</style>
			<?php
		}
	}
	
	public static function calendarEmployeeItemAjax($i,$eid,$day){
		global $mainframe,$configClass;
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);
		$db  = JFactory::getDbo();
		$db->setQuery("SELECT COUNT(id) FROM #__app_sch_employee_rest_days WHERE eid = '$eid' AND rest_date = '$day'");
		$rest = $db->loadResult();
		$db->setQuery("SELECT a.*,c.service_name,b.order_status FROM #__app_sch_order_items AS a INNER JOIN #__app_sch_orders AS b ON b.id = a.order_id INNER JOIN #__app_sch_services AS c ON c.id = a.sid WHERE a.eid = '$eid' AND b.order_status IN ('P','S') AND a.booking_date = '$day'");
		$rows = $db->loadObjectList();
		?>
		<div style="float:left;width:50%;">
		<b>
			<?php
			if($day == date("Y-m-d",OSBHelper::getCurrentDate())){
				echo "<font color='#E3462D'>";
				echo $i;
				echo "</font>";
			}else{
				echo $i;
			}
			?>
		</b>
		</div>
		<div style="float:left;width:50%;text-align:right;">
			<?php
			if($rest == 1){
				
			}elseif(count($rows) == 0){
				
			}else{
			?>
			<a href='<?php echo JURI::root()?>index.php?option=com_osservicesbooking&task=calendar_dateinfo&date=<?php echo $day?>' class='osmodal'  title="<?php echo JText::_('OS_CLICK_HERE_TO_VIEW_CALENDAR_DETAILS');?>" rel="{handler: 'iframe', size: {x: 600, y: 400}}">
				<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/orderdetails.png" border="0"/>
			</a>
			<?php
			}
			?>
		</div>
		<div style="clear: both;"></div>
		<div class="div-schedule">
		<?php
		if(count($rows) > 0){
			for($k=0;$k<count($rows);$k++){
				$config = new JConfig();
				$offset = $config->offset;
				date_default_timezone_set($offset);
				$row = $rows[$k];
				echo $k + 1;
				?>
				<span class="hasTip" title="<?php echo self::generateBookingItem($row,0);?>">
				<?php
				echo ". ";
				//echo "<a href='".JURI::root()."index.php?option=com_osservicesbooking&task=calendar_dateinfo&date=$day' class='modal'>";
				echo date($configClass['time_format'],$row->start_time);
				echo "-";
				echo date($configClass['time_format'],$row->end_time);
				//echo "</a>";
				echo "  [".$row->service_name."]";
				echo '<span class="label">'.OSBHelper::orderStatus(0,$row->order_status).'</span>';
				echo "<BR />";
				?>
				</span>
				<?php
			}
		}else{
			if($rest > 0){
				echo JText::_('OS_REST_DAY');	
			}else{
				echo JText::_('OS_NO_BOOKING_REQUEST');
			}
		}
		?>
		</div>
		<?php
	}
	
	public static function generateBookingItem($row,$isEmployee){
		global $mainframe,$configClass;
		$data = self::generateData($row);
		$return = $data[0]->service_name."::";
		$return.= "<br />".JText::_('OS_FROM').": ".date($configClass['date_format'],$data[5]);
		$return.= "  <br />".JText::_('OS_TO').": ".date($configClass['time_format'],$data[6]);
		$return.= "  <br />".JText::_('OS_ON').": ".date($configClass['time_format'],$data[5]);
		if($isEmployee == 1){
			$return.= "  <br />".JText::_('OS_EMPLOYEE').": ".$data[1]->employee_name;
		}
		if($field_data != ""){
			$return.= "  <br />".JText::_('OS_ADDITIONAL_INFORMATION').": ".$data[4];
		}
		return $return;
	}
	
	public static function generateData($row){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$sid = $row->sid;
		$db->setQuery("SELECT * FROM #__app_sch_services WHERE id = '$sid'");
		$service = $db->loadObject();
		if($service->service_time_type == 1){
			$nslots = $row->nslots;
		}else{
			$nslots = 0;
		}
		$order_item_id		= $row->id;
		$start_booking_date = $row->start_time;
		$week_date			= date("N",$start_booking_date);
		$end_booking_date   = $row->end_time;
		$eid				= $row->eid;
		$db->setQuery("SELECT field_id FROM #__app_sch_temp_order_field_options WHERE order_item_id = '$order_item_id' GROUP BY field_id");
		$fields = $db->loadObjectList();
		//calculate option value and additional price
		if(count($fields) > 0){
			//prepare the field array
			$fieldArr = array();
			for($i=0;$i<count($fields);$i++){
				$field = $fields[$i];
				if(!in_array($field->field_id,$fieldArr)){
					$fieldArr[count($fieldArr)] = $field->field_id;
				}
			}
			$field_amount = 0;
			$field_data   = "";
			for($i=0;$i<count($fieldArr);$i++){
				$fieldid = $fieldArr[$i];
				$db->setQuery("Select id,field_label,field_type from #__app_sch_fields where  id = '$fieldid'");
				$field = $db->loadObject();
				$field_type = $field->field_type;
				if($field_type == 1){
					//get field value
					$db->setQuery("SELECT option_id FROM #__app_sch_temp_order_field_options WHERE order_item_id= '$order_item_id'");
					$fieldvalue = $db->loadResult();
					$db->setQuery("Select * from #__app_sch_field_options where id = '$fieldvalue'");
					$fieldOption = $db->loadObject();
					if($fieldOption->additional_price > 0){
						$field_amount += $fieldOption->additional_price;
					}
					
					$field_data .= "<b>$field->field_label:</b>: ".$fieldOption->field_option;
					if($fieldOption->additional_price > 0){
						$field_data.= " - ".$fieldOption->additional_price." ".$configClass['currency_format'];
					}
					$field_data .= "<BR />";
				}elseif($field_type == 2){
					$db->setQuery("SELECT option_id FROM #__app_sch_temp_order_field_options WHERE order_item_id= '$order_item_id' and field_id = '$fieldid'");
					$fieldValueArr = $db->loadObjectList();
					if(count($fieldValueArr) > 0){
						$fieldValue = array();
						for($j=0;$j<count($fieldValueArr);$j++){
							$fieldValue[$j] = $fieldValueArr[$j]->option_id;
						}
					}
					if(count($fieldValue) > 0){
						$field_data .= "<b>$field->field_label:</b>: ";
						for($j=0;$j<count($fieldValue);$j++){
							$temp = $fieldValue[$j];
							$db->setQuery("Select * from #__app_sch_field_options where id = '$temp'");
							$fieldOption = $db->loadObject();
							if($fieldOption->additional_price > 0){
								$field_amount += $fieldOption->additional_price;
							}
							$field_data .= $fieldOption->field_option;
							if($fieldOption->additional_price > 0){
								$field_data.= " - ".$fieldOption->additional_price." ".$configClass['currency_format'];
							}
							$field_data .= ",";
						}
						$field_data = substr($field_data,0,strlen($field_data)-1);
						$field_data .= "<BR />";
					}
				}
			}
		}
		
		$db->setQuery("Select a.*,b.additional_price from #__app_sch_employee as a inner join #__app_sch_employee_service as b on a.id = b.employee_id where a.id = '$eid' and b.service_id = '$sid'");
		$employee = $db->loadObject();
		
		//get extra cost
		$db->setQuery("Select * from #__app_sch_employee_extra_cost where eid = '$eid' and (week_date = '$week_date' or week_date = '0')");
		//echo $db->getQuery();
		$extras = $db->loadObjectList();
		$extra_cost = 0;
		if(count($extras) > 0){
			for($j=0;$j<count($extras);$j++){
				$extra = $extras[$j];
				$stime = $extra->start_time;
				$etime = $extra->end_time;
				$stime = date($configClass['date_format'],$start_booking_date)." ".$stime.":00";
				$etime = date($configClass['date_format'],$start_booking_date)." ".$etime.":00";
				$stime = strtotime($stime);
				$etime = strtotime($etime);
				if(($start_booking_date >= $stime) and ($start_booking_date <= $etime)){
					$extra_cost += $extra->extra_cost;
				}
			}
		}
		
		$return[0] = $service;
		$return[1] = $employee;
		$return[2] = $stime;
		$return[3] = $etime;
		$return[4] = $field_data;
		$return[5] = $start_booking_date;
		$return[6] = $end_booking_date;
		$return[7] = $nslots;
		return $return;
	}
	
	/**
	 * Show money with currency symbol and currency code
	 *
	 * @param unknown_type $amount
	 */
	function showMoney($amount,$showCode){
		global $mainframe,$configClass;
		$money = "";
		if($configClass['currency_symbol_position'] == 0){
			$money = $configClass['currency_symbol']." ";
		}
		$money .= number_format($amount,2,'.','')." ";
		if($configClass['currency_symbol_position'] == 1){
			$money .= $configClass['currency_symbol']." ";
		}
		if($showCode==1){
			$money .= $configClass['currency_format'];
		}
		return $money;
	}
	
	/**
	 * Generate Order PDF layout
	 *
	 * @param unknown_type $id
	 */
	function generateOrderPdf($id){
		global $configClass;
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);
		$mainframe = JFactory::getApplication ();
		$sitename = $configClass['business_name'];
		$db = JFactory::getDBO();
		$query  = $db->getQuery(true);
		
		$query->select("*")->from("#__app_sch_orders")->where("id='$id'");
		$db->setQuery($query);
		$order = $db->loadObject();
		$order_lang = $order->order_lang;
		$query->clear();
		$default_lang = self::getDefaultLanguage();
		if($order_lang == ""){
			$order_lang = $default_lang;
		}
		if($order_lang == $default_lang){
			$lang_suffix = "";
		}else{
			$order_lang_arr = explode("-",$order_lang);
			$lang_suffix = "_".$order_lang_arr[0];
		}
		
		require_once JPATH_ROOT . "/components/com_osservicesbooking/tcpdf/tcpdf.php";
		require_once JPATH_ROOT . "/components/com_osservicesbooking/tcpdf/config/lang/eng.php";
		$row = &JTable::getInstance ( 'Order', 'OsAppTable' );
		$row->load ( ( int ) $id );
		$query->select ( 'a.*, c.service_name'.$lang_suffix.' as service_name,c.service_time_type, b.employee_name' )->from ( '#__app_sch_order_items AS a' )->join ( 'INNER', '#__app_sch_employee AS b ON a.eid = b.id' )->join ( 'INNER', '#__app_sch_services AS c ON a.sid = c.id' )->where ( "a.order_id='" . $id ."'");
		//$db->setQuery("Select a.*, c.service_name,c.service_time_type, b.employee_name from #__app_sch_order_items AS a  INNER JOIN #__app_sch_employee AS b ON a.eid = b.id INNER JOIN #__app_sch_services AS c ON a.sid = c.id WHERE a.order_id='$row->id'");
		
		$db->setQuery ( $query );
		$rows = $db->loadObjectList ();
		
		$pdf = new TCPDF ( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
		$pdf->SetCreator ( PDF_CREATOR );
		$pdf->SetAuthor ( $sitename );
		$pdf->SetTitle ( 'Invoice' );
		$pdf->SetSubject ( 'Invoice' );
		$pdf->SetKeywords ( 'Invoice' );
		$pdf->setHeaderFont ( Array (PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN ) );
		$pdf->setFooterFont ( Array (PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA ) );
		$pdf->setPrintHeader ( false );
		$pdf->setPrintFooter ( false );
		$pdf->SetMargins ( PDF_MARGIN_LEFT, 0, PDF_MARGIN_RIGHT );
		$pdf->SetHeaderMargin ( PDF_MARGIN_HEADER );
		$pdf->SetFooterMargin ( PDF_MARGIN_FOOTER );
		
		//set auto page breaks
		$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
		
		//set image scale factor
		$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
		$pdf->SetFont ( 'times', '', 8 );
		$pdf->AddPage ();
		
		//get html details
		$html .= '<div style="width=100%">';
		$html .= '<table width="100%">';
		$html .= '<tr>';
		$html .= '<td class="key">' . JText::_ ( 'OS_ORDER_NUMBER' ) . '</td>';
		$html .= '<td>' . ServiceDowloadinvoice::formatInvoiceNumber ( $id, $configClass ) . '</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td class="key">' . JText::_ ( 'OS_NAME' ) . '</td>';
		$html .= '<td>' . $row->order_name . '</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td class="key">' . JText::_ ( 'OS_EMAIL' ) . '</td>';
		$html .= '<td>' . $row->order_email . '</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td class="key">' . JText::_ ( 'OS_PHONE' ) . '</td>';
		if($row->dial_code != ""){
			$dial_code = $row->dial_code."-";
		}else{
			$dial_code = "";
		}
		$html .= '<td>' .$dial_code. $row->order_phone . '</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td class="key">' . JText::_ ( 'OS_COUNTRY' ) . '</td>';
		$html .= '<td>' . $row->order_country . '</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td class="key">' . JText::_ ( 'OS_CITY' ) . '</td>';
		$html .= '<td>' . $row->order_city . '</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td class="key">' . JText::_ ( 'OS_STATE' ) . '</td>';
		$html .= '<td>' . $row->order_state . '</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td class="key">' . JText::_ ( 'OS_ZIP' ) . '</td>';
		$html .= '<td>' . $row->order_zip . '</td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td class="key">' . JText::_ ( 'OS_ADDRESS' ) . '</td>';
		$html .= '<td>' . $row->order_address . '</td>';
		$html .= '</tr>';
		$query->clear();
		
		$query->select ( '*' )->from ( '#__app_sch_fields' )->where ( 'field_area = "1"' )->where ( 'published = "1"' );
		$db->setQuery ( $query );
	
		$fields = $db->loadObjectList ();
		
		if (count ( $fields ) > 0) {
			for($i = 0; $i < count ( $fields ); $i ++) {
				$field = $fields [$i];
				if ($field->field_type == 1) {
					$query->clear ();
					$query->select ('fvalue')->from('#__app_sch_field_data')->where('order_id = '.$row->id)->where('fid='.$field->id);
					$db->setQuery($query);
					$fvalue = $db->loadResult();
					if($fvalue != ""){
						$html .= '<tr>';
						$html .= '<td class="key">' . self::getLanguageFieldValueOrder($field,'field_label',$order_lang) . '</td>';
						$html .= '<td>' . $fvalue . '</td>';
						$html .= '</tr>';
					}
				}
				$query->clear ();
				$query->select ( 'count(id)' )->from ( '#__app_sch_order_options' )->where ( 'order_id=' . $row->id )->where ( 'field_id=' . $field->id );
				$db->setQuery ( $query );
				$count = $db->loadResult ();
				if ($count > 0) {
					if ($field->field_type == 1) {
						$query->clear ();
						$query->select ( 'option_id' )->from ( '#__app_sch_order_options' )->where ( 'order_id=' . $row->id )->where ( "field_id='" . $field->id ."'");
						$db->setQuery ( $query );
						
						$option_id = $db->loadResult ();
						$query->clear ();
						$query->select ( '*' )->from ( '#__app_sch_field_options' )->where ( 'id=' . $option_id );
						$db->setQuery ( $query );
						$optionvalue = $db->loadObject ();
						$field_data = self::getLanguageFieldValueOrder($optionvalue,'field_option',$order_lang);
						if ($optionvalue->additional_price > 0) {
							$field_data .= " - " . $optionvalue->additional_price . " " . $configClass ['currency_format'];
						}
						$html .= '<tr>';
						$html .= '<td class="key">' .self::getLanguageFieldValueOrder($field,'field_label',$order_lang). '</td>';
						$html .= '<td>' . $field_data . '</td>';
						$html .= '</tr>';
					} elseif ($field->field_type == 2) {
						$query->clear ();
						$query->select ( 'option_id' )->from ( '#__app_sch_order_options' )->where ( 'order_id=' . $row->id )->where ( "field_id='" . $field->id ."'" );
						$db->setQuery ( $query );
						
						$option_ids = $db->loadObjectList ();
						$fieldArr = array ();
						for($j = 0; $j < count ( $option_ids ); $j ++) {
							$oid = $option_ids [$j];
							$query->clear ();
							$query->select ( '*' )->from ( '#__app_sch_field_options' )->where ( 'id=' . $oid->option_id );
							$db->setQuery ( $query );
							$optionvalue = $db->loadObject ();
							$field_data = self::getLanguageFieldValueOrder($optionvalue,'field_option',$order_lang);
							if ($optionvalue->additional_price > 0) {
								$field_data .= " - " . $optionvalue->additional_price . " " . $configClass ['currency_format'];
							}
							$fieldArr [] = $field_data;
						}
						$html .= '<tr>';
						$html .= '<td class="key">' .self::getLanguageFieldValueOrder($field,'field_label',$order_lang). '</td>';
						$html .= '<td>' . implode ( ", ", $fieldArr ) . '</td>';
						$html .= '</tr>';
					}
				}
			}
		}
		$html .= '<tr>';
		$html .= '<td class="key">' . JText::_ ( 'Notes' ) . '</td>';
		$html .= '<td>' . $row->order_notes . '</td>';
		$html .= '</tr>';
		
		if($configClass['disable_payment'] == 0){
		
			$html .= '<tr>';
			$html .= '<td class="key">' . JText::_ ( 'OS_PAYMENT' ) . '</td>';
			$html .= '<td>';
			$order_payment = $row->order_payment;
			if ($order_payment != "") {
				echo JText::_ ( os_payments::loadPaymentMethod ( $order_payment )->title );
			}
			
			$html .= '</td>';
			$html .= '</tr>';
			
			$html .= '<tr>';
			$html .= '<td class="key">' . JText::_ ( 'OS_TOTAL' ) . '</td>';
			$html .= '<td>' . OSBHelper::showMoney($row->order_total,1). '</td>';
			$html .= '</tr>';
			
			if($configClass['enable_tax'] == 1){
				$html .= '<tr>';
				$html .= '<td class="key">' . JText::_ ( 'OS_TAX' ) . '</td>';
				$html .= '<td>' .  OSBHelper::showMoney( $row->order_tax,1). '</td>';
				$html .= '</tr>';
			}
			
			if($row->coupon_id > 0){
				$html .= '<tr>';
				$html .= '<td class="key">' . JText::_ ( 'OS_DEPOSIT' ) . '</td>';
				$html .= '<td>' .  OSBHelper::showMoney( $row->order_discount,1). '</td>';
				$html .= '</tr>';
			}
			
			$html .= '<tr>';
			$html .= '<td class="key">' . JText::_ ( 'OS_FINAL_COST' ) . '</td>';
			$html .= '<td>' . OSBHelper::showMoney( $row->order_final_cost,1). '</td>';
			$html .= '</tr>';
			
			$html .= '<tr>';
			$html .= '<td class="key">' . JText::_ ( 'OS_UPFRONT' ) . '</td>';
			$html .= '<td>' . OSBHelper::showMoney( $row->order_upfront,1) . '</td>';
			$html .= '</tr>';
		}
		
		$html .= '<tr>';
		$html .= '<td class="key">' . JText::_ ( 'OS_DATE' ) . '</td>';
		$html .= '<td>' . $row->order_date . '</td>';
		$html .= '</tr>';
		
		$html .= '<tr>';
		$html .= '<td class="key">' . JText::_ ( 'OS_STATUS' ) . '</td>';
		$html .= '<td>'.OSBHelper::orderStatus(0,$row->order_status).'</td>';
		$html .= '</tr>';
		
		$html .= '</table>';
		
		$html .= '<BR /><BR /><B>'.JText::_('OS_ORDER_DETAILS').'</B><BR />';
		$html .= '<table style="width:680px;">';
		$html .= '<tr>';
		$html .= '<td class="key" style="width:30px;font-weight:bold;padding-bottom:10px;padding-top:10px;border-bottom:1px solid #CCC;">' . JText::_ ( '#' ) . '</td>';
		$html .= '<td class="key" style="width:90px;font-weight:bold;padding-bottom:10px;padding-top:10px;border-bottom:1px solid #CCC;">' . JText::_ ( 'OS_SERVICES' ) . '</td>';
		$html .= '<td class="key" style="width:90px;font-weight:bold;padding-bottom:10px;padding-top:10px;border-bottom:1px solid #CCC;">' . JText::_ ( 'OS_EMPLOYEE' ) . '</td>';
		$html .= '<td class="key" style="width:70px;font-weight:bold;padding-bottom:10px;padding-top:10px;border-bottom:1px solid #CCC;">' . JText::_ ( 'OS_WORKTIME_START_TIME' ) . '</td>';
		$html .= '<td class="key" style="width:70px;font-weight:bold;padding-bottom:10px;padding-top:10px;border-bottom:1px solid #CCC;">' . JText::_ ( 'OS_WORKTIME_END_TIME' ) . '</td>';
		$html .= '<td class="key" style="width:70px;font-weight:bold;padding-bottom:10px;padding-top:10px;border-bottom:1px solid #CCC;">' . JText::_ ( 'OS_DATE' ) . '</td>';
		$html .= '<td class="key" style="width:150px;font-weight:bold;padding-bottom:10px;padding-top:10px;border-bottom:1px solid #CCC;">' . JText::_ ( 'OS_OTHER_INFORMATION' ) . '</td>';
		$html .= '</tr>';
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);	
		if (count($rows) > 0) {
			for($i=0;$i<count($rows);$i++) {
				$item = &$rows[$i];
				$ordering = $i + 1;
				if($i % 2 == 0){
					$bgcolor = "#ffffff";
				}else{
					$bgcolor = "#efefef";
				}
				$html .= '<tr>';
				$html .= '<td style="width:30px;background-color:'.$bgcolor.';">' . $ordering . '</td>';
				$html .= '<td style="width:90px;background-color:'.$bgcolor.';">' . $item->service_name . '</td>';
				$html .= '<td style="width:90px;background-color:'.$bgcolor.';">' . $item->employee_name . '</td>';
				$html .= '<td style="width:70px;background-color:'.$bgcolor.';">' . date ( $configClass ['time_format'], $item->start_time ) . '</td>';
				$html .= '<td style="width:70px;background-color:'.$bgcolor.';">' . date ( $configClass ['time_format'], $item->end_time ) . '</td>';
				$html .= '<td style="width:70px;background-color:'.$bgcolor.';">' . date ( $configClass ['date_format'], strtotime ( $item->booking_date ) ) . '</td>';
				$html .= '<td style="width:150px;background-color:'.$bgcolor.';">';
				if($item->service_time_type == 1){
					$html .= JText::_('OS_NUMBER_SLOT').": ".$item->nslots."<BR />";
				}
				$query->clear();
				$query->select ( '*' )->from ( '#__app_sch_fields' )->where ( 'field_area= "0"' )->where ( 'published = 1' );
				$db->setQuery ( $query );
				$fields = $db->loadObjectList ();
				if (count ( $fields ) > 0) {
					for($i1 = 0; $i1 < count ( $fields ); $i1 ++) {
						$field = $fields [$i1];
						$query->clear ();
						$query->select ( 'count(id)' )->from ( '#__app_sch_order_field_options' )->where ( 'order_item_id=' . $item->id )->where ( 'field_id=' . $field->id );
						$db->setQuery ( $query );
						$count = $db->loadResult ();
						if ($count > 0) {
							if ($field->field_type == 1) {
								$query->clear ();
								$query->select ( 'option_id' )->from ( '#__app_sch_order_field_options' )->where ( 'order_item_id=' . $item->id )->where ( 'field_id=' . $field->id );
								$db->setQuery ( $query );
								$option_id = $db->loadResult ();
								$query->clear ();
								$query->select ( '*' )->from ( '#__app_sch_field_options' )->where ( 'id=' . $option_id );
								$db->setQuery ( $query );
								$optionvalue = $db->loadObject ();
								?>
								<?php
								$html .= self::getLanguageFieldValueOrder($field,'field_label',$order_lang).":";
								?>
								<?php
								$field_data = self::getLanguageFieldValueOrder($optionvalue,'field_option',$order_lang);
								if ($optionvalue->additional_price > 0) {
									$field_data .= " - " . $optionvalue->additional_price . " " . $configClass ['currency_format'];
								}
								$html .= $field_data;
								$html .= "<BR />";
							} elseif ($field->field_type == 2) {
								$query->clear ();
								$query->select ( 'option_id' )->from ( '#__app_sch_order_field_options' )->where ( 'order_item_id=' . $item->id )->where ( 'field_id=' . $field->id );
								$db->setQuery ( $query );
								$option_ids = $db->loadObjectList ();
								$fieldArr = array ();
								for($j = 0; $j < count ( $option_ids ); $j ++) {
									$oid = $option_ids [$j];
									$query->clear ();
									$query->select ( '*' )->from ( '#__app_sch_field_options' )->where ( 'id=' . $oid->option_id );
									$db->setQuery ( $query );
									$optionvalue = $db->loadObject ();
									$field_data = self::getLanguageFieldValueOrder($optionvalue,'field_option',$order_lang);
									if ($optionvalue->additional_price > 0) {
										$field_data .= " - " . $optionvalue->additional_price . " " . $configClass ['currency_format'];
									}
									$fieldArr [] = $field_data;
								}
								
								$html .= self::getLanguageFieldValueOrder($field,'field_label',$order_lang).":";
								?>
								<?php
								$html .= implode ( ",", $fieldArr );
								$html .= "<BR />";
							}
						}
					}
				}
				$html .= '</td>';
				$html .= '</tr>';
			}
		}
		$html .= '</table>';
		$html .= '</div>';
		$replaces = array ();
		$replaces ['name'] = $row->order_name;
		$replaces ['phone'] = $row->order_phone;
		$replaces ['email'] = $row->order_email;
		$replaces ['details'] = $html;
		foreach ( $replaces as $key => $value ) {
			$key = strtoupper ( $key );
			$configClass ['invoice_format'] = str_replace ( "[$key]", $value, $configClass ['invoice_format'] );
		}
		
		$invoiceOutput = self::convertImgTags ( $configClass ['invoice_format'] );
		$pdf->writeHTML ( $invoiceOutput, true, false, false, false, '' );
		
		
		//Filename
		$pdf_root = JPATH_ROOT . '/media/com_osservicesbooking/invoices/';
		$invoicePath = $pdf_root . ServiceDowloadinvoice::formatInvoiceNumber ( $id, $configClass ) . '.pdf';
		$fileName = ServiceDowloadinvoice::formatInvoiceNumber ( $id, $configClass ) . '.pdf';
		$pdf->Output ( $pdf_root . $fileName, 'F' );
		$returnArr = array();
		$returnArr[0] = $pdf_root . $fileName;
		$returnArr[1] = $fileName;
		return $returnArr;
	}
	
	//check multiple work list of one employee
	function checkMultipleEmployees($sid,$eid,$start_time,$end_time){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$db->setQuery("Select count(a.id) from #__app_sch_order_items as a inner join #__app_sch_orders as b on b.id = a.order_id where b.order_status in ('S','P') and a.sid <> '$sid' and a.eid = '$eid' and ((a.start_time >= '$start_time' and a.end_time <= '$end_time') or (a.start_time <= '$start_time' and a.end_time >= '$end_time') or (a.start_time <= '$end_time' and a.start_time >= '$start_time') or (a.end_time <= '$end_time' and a.end_time >= '$start_time') or (a.start_time >= '$start_time' and a.end_time <= '$end_time'))");
		$count = $db->loadResult();
		if($count > 0){
			$db->setQuery("Select count(a.id) from #__app_sch_order_items as a inner join #__app_sch_orders as b on b.id = a.order_id where b.order_status in ('S','P') and a.sid <> '$sid' and a.eid = '$eid' and a.end_time = '$start_time'");
			$count1 = $db->loadResult();
			if(($count1 > 0) and ($count1 == $count)){
				return true;
			}else{
				$db->setQuery("Select count(a.id) from #__app_sch_order_items as a inner join #__app_sch_orders as b on b.id = a.order_id where b.order_status in ('S','P') and a.sid <> '$sid' and a.eid = '$eid' and a.start_time = '$end_time'");
				$count2 = $db->loadResult();
				if(($count2 > 0) and ($count2 == $count)){
					return true;
				}else{
					return false;		
				}
			}
			return false;
		}else{
			return true;
		}
	}
	
	//check multiple work list of one employee
	function checkMultipleEmployeesInTempOrderTable($sid,$eid,$start_time,$end_time){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		//$unique_cookie = $_COOKIE['unique_cookie'];
		$unique_cookie = self::getUniqueCookie();
		$db->setQuery("Select count(a.id) from #__app_sch_temp_order_items as a inner join #__app_sch_temp_orders as b on b.id = a.order_id where b.unique_cookie like '$unique_cookie' and a.sid <> '$sid' and a.eid = '$eid' and ((a.start_time >= '$start_time' and a.end_time <= '$end_time') or (a.start_time <= '$start_time' and a.end_time >= '$end_time') or (a.start_time <= '$end_time' and a.start_time >= '$start_time') or (a.end_time <= '$end_time' and a.end_time >= '$start_time') or (a.start_time >= '$start_time' and a.end_time <= '$end_time'))");
		
		$count = $db->loadResult();
		//if($count > 0){
		//	return false;
		//}else{
		//	return true;
		//}
		if($count > 0){
			$db->setQuery("Select count(a.id) from #__app_sch_temp_order_items as a inner join #__app_sch_temp_orders as b on b.id = a.order_id where b.unique_cookie like '$unique_cookie' and a.sid <> '$sid' and a.eid = '$eid' and a.end_time = '$start_time'");
			$count1 = $db->loadResult();
			if(($count1 > 0) and ($count1 == $count)){
				return true;
			}else{
				$db->setQuery("Select count(a.id) from #__app_sch_temp_order_items as a inner join #__app_sch_temp_orders as b on b.id = a.order_id where b.unique_cookie like '$unique_cookie' and a.sid <> '$sid' and a.eid = '$eid' and a.start_time = '$end_time'");
				$count2 = $db->loadResult();
				if(($count2 > 0) and ($count2 == $count)){
					return true;
				}else{
					return false;		
				}
			}
			return false;
		}else{
			return true;
		}
	}

	//check multiple work list of one employee
	function checkMultipleServices($sid,$eid,$start_time,$end_time){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$db->setQuery("Select count(a.id) from #__app_sch_order_items as a inner join #__app_sch_orders as b on b.id = a.order_id where b.order_status in ('S','P') and a.sid = '$sid' and a.eid <> '$eid' and ((a.start_time >= '$start_time' and a.end_time <= '$end_time') or (a.start_time <= '$start_time' and a.end_time >= '$end_time') or (a.start_time <= '$end_time' and a.start_time >= '$start_time') or (a.end_time <= '$end_time' and a.end_time >= '$start_time') or (a.start_time >= '$start_time' and a.end_time <= '$end_time'))");
		$count = $db->loadResult();
		if($count > 0){
			$db->setQuery("Select count(a.id) from #__app_sch_order_items as a inner join #__app_sch_orders as b on b.id = a.order_id where b.order_status in ('S','P') and a.sid = '$sid' and a.eid <> '$eid' and a.end_time = '$start_time'");
			$count1 = $db->loadResult();
			if(($count1 > 0) and ($count1 == $count)){
				return true;
			}else{
				$db->setQuery("Select count(a.id) from #__app_sch_order_items as a inner join #__app_sch_orders as b on b.id = a.order_id where b.order_status in ('S','P') and a.sid = '$sid' and a.eid <> '$eid' and a.start_time = '$end_time'");
				$count2 = $db->loadResult();
				if(($count2 > 0) and ($count2 == $count)){
					return true;
				}else{
					return false;		
				}
			}
			return false;
		}else{
			return true;
		}
	}

	//check multiple work list of one employee
	function checkMultipleServicesInTempOrderTable($sid,$eid,$start_time,$end_time){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		//$unique_cookie = $_COOKIE['unique_cookie'];
		$unique_cookie = self::getUniqueCookie();
		$db->setQuery("Select count(a.id) from #__app_sch_temp_order_items as a inner join #__app_sch_temp_orders as b on b.id = a.order_id where b.unique_cookie like '$unique_cookie' and a.sid = '$sid' and a.eid <> '$eid' and ((a.start_time >= '$start_time' and a.end_time <= '$end_time') or (a.start_time <= '$start_time' and a.end_time >= '$end_time') or (a.start_time <= '$end_time' and a.start_time >= '$start_time') or (a.end_time <= '$end_time' and a.end_time >= '$start_time') or (a.start_time >= '$start_time' and a.end_time <= '$end_time'))");
		
		$count = $db->loadResult();
		//if($count > 0){
		//	return false;
		//}else{
		//	return true;
		//}
		if($count > 0){
			$db->setQuery("Select count(a.id) from #__app_sch_temp_order_items as a inner join #__app_sch_temp_orders as b on b.id = a.order_id where b.unique_cookie like '$unique_cookie' and a.sid = '$sid' and a.eid <> '$eid' and a.end_time = '$start_time'");
			$count1 = $db->loadResult();
			if(($count1 > 0) and ($count1 == $count)){
				return true;
			}else{
				$db->setQuery("Select count(a.id) from #__app_sch_temp_order_items as a inner join #__app_sch_temp_orders as b on b.id = a.order_id where b.unique_cookie like '$unique_cookie' and a.sid = '$sid' and a.eid <> '$eid' and a.start_time = '$end_time'");
				$count2 = $db->loadResult();
				if(($count2 > 0) and ($count2 == $count)){
					return true;
				}else{
					return false;		
				}
			}
			return false;
		}else{
			return true;
		}
	}
	
	/**
	 * Find Address
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 * @return unknown
	 */
	public static function findAddress($address){
		global $mainframe;
		$address = trim($address);
		$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&sensor=false";
		if(self::_iscurlinstalled()){
			$ch = curl_init();
		    curl_setopt ($ch, CURLOPT_URL, $url);
		    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 0);
		    $return_data = curl_exec($ch);
		    curl_close($ch);
		}else{
			$return_data = file_get_contents($url) or die("url not loading");
		}
		$return_data = json_decode($return_data);
		$return_location = $return_data->results;
		$return = array();
		if($return_data->status == "OK"){
			$return[0] = $return_location[0]->geometry->location->lat;
			$return[1] = $return_location[0]->geometry->location->lng;
			$return[2] = $return_data->status;
		}
		return $return;
	}
	
	/**
	 * Check curl existing
	 *
	 * @return unknown
	 */
	function _iscurlinstalled() {
		if  (in_array  ('curl', get_loaded_extensions())) {
			return true;
		}
		else{
			return false;
		}
	}
	
	/**
	 * Add event on Google Calendar
	 *
	 * @param unknown_type $orderId
	 */
	public static function updateGoogleCalendar($orderId){
		global $mainframe,$configClass;
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		//echo JPATH_ROOT."/components/com_osservicesbooking/google-api-php-client-master/src/Google/Client.php";
		if(($configClass['integrate_gcalendar'] == 1) and (JFile::exists(JPATH_ROOT."/components/com_osservicesbooking/google-api-php-client-master/src/Google/Client.php"))){
			$db = JFactory::getDbo();
			$db->setQuery("Select a.id, a.eid,c.service_name, a.start_time,a.end_time,a.booking_date,e.client_id,e.app_name,e.app_email_address,e.p12_key_filename,e.gcalendarid from #__app_sch_order_items as a inner join #__app_sch_orders as b on b.id = a.order_id inner join #__app_sch_services as c on c.id = a.sid inner join #__app_sch_employee as e on e.id = a.eid where b.id = '$orderId' and b.order_status in ('S','P')");
			$eids = $db->loadObjectList();
			
			if(count($eids) > 0){
				for($i=0;$i<count($eids);$i++){
					$item = $eids[$i];
					$service_name = $item->service_name;
					$start_time = $item->start_time;
					$end_time = $item->end_time;
					$booking_date = $item->booking_date;
					$eid = $item->eid;
					$client_id = $item->client_id;
					$app_name = $item->app_name;
					$app_email_address = $item->app_email_address;
					$p12_key_filename = $item->p12_key_filename;
					$gcalendarid = $item->gcalendarid;
					if(($client_id != "") and ($app_name != "")and ($app_email_address != "") and ($gcalendarid != "") and ($p12_key_filename != "") and (JFile::exists(JPATH_COMPONENT_SITE."/".$p12_key_filename)) ){
						self::addEventonGCalendar(trim($client_id),trim($app_name),trim($app_email_address),trim($p12_key_filename),trim($gcalendarid),$service_name,$start_time,$end_time,$booking_date,$item->id,$orderId);
					}
				}
			}
		}
	}
	
	
	/**
	 * Add event into GCalendar
	 *
	 * @param unknown_type $gusername
	 * @param unknown_type $gpassword
	 * @param unknown_type $gcalendarid
	 * @param unknown_type $service_name
	 * @param unknown_type $start_time
	 * @param unknown_type $end_time
	 * @param unknown_type $booking_date
	 */
	public static function addEventonGCalendar($client_id,$app_name,$app_email_address,$p12_key_filename,$gcalendarid,$service_name,$start_time,$end_time,$booking_date,$order_item_id,$orderId){
		global $mainframe,$configClass;
		$current = self::getCurrentDate();
		$gmttime =  strtotime(JFactory::getDate('now'));
		$distance = round(($current - $gmttime)/3600);
		if($distance <= 0){
			$distance = str_replace("-","",$distance);
			$distance = intval($distance);
			if($distance < 10){
				$distance = "0".$distance;
			}
			$distance = "-".$distance;
		}
		if($distance > 0){
			if($distance < 10){
				$distance = "0".$distance;
			}
			$distance = "+".$distance;
		}
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_orders where id = '$orderId'");
		$order = $db->loadObject();
		$desc = "";
		if($order->order_name != ""){
			$desc .= $order->order_name;
		}
		if($order->order_email != ""){
			$desc .= ", ".$order->order_email;
		}
		if($order->order_phone != ""){
			$desc .= ", ".$order->order_phone;
		}
		$db->setQuery("Select * from #__app_sch_order_items where id = '$order_item_id'");
		$item = $db->loadObject();
		$location = "";
		if($item->vid > 0){
			$db->setQuery("Select * from #__app_sch_venues where id = '$item->vid'");
			$venue = $db->loadObject();
			if($venue->address != ""){
				$location .= $venue->address;
			}
			if($venue->city != ""){
				$location .= ", ".$venue->city;
			}
			if($venue->state != ""){
				$location .= ", ".$venue->state;
			}
			if($venue->country != ""){
				$location .= ", ".$venue->country;
			}
		}
		
		//$gusername = str_replace("@gmail.com","",$gusername)."@gmail.com";
		// load classes
		//require_once 'Zend/Loader.php';
		//Zend_Loader::loadClass('Zend_Gdata');
		//Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
		//Zend_Loader::loadClass('Zend_Gdata_Calendar');
		//Zend_Loader::loadClass('Zend_Http_Client');
		
		// connect to service
		//$gcal = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
		//$client = Zend_Gdata_ClientLogin::getHttpClient($gusername, $gpassword, $gcal);
		//$gcal = new Zend_Gdata_Calendar($client);
		$path = JPATH_COMPONENT_SITE.DS."google-api-php-client-master".DS."src".DS."Google";
		set_include_path(get_include_path() . PATH_SEPARATOR . $path);
		//echo $path;
		if(!file_exists ( $path.DS.'Client.php' )){
			echo "OSB set to use Google Calendar but the Google Library is not installed.";
			exit;
		}	
		require_once $path.DS."Client.php";
	    require_once $path.DS."Service.php";
		
		try {
	 	    $client = new Google_Client();
			$client->setApplicationName($app_name);
			$client->setClientId($client_id);
			$client->setAssertionCredentials( 
				new Google_Auth_AssertionCredentials(
					$app_email_address,
					array("https://www.googleapis.com/auth/calendar"),
					file_get_contents(JPATH_COMPONENT_SITE.DS.$p12_key_filename),
					'notasecret','http://oauth.net/grant_type/jwt/1.0/bearer',false,false
				)
			);
		}
		catch (RuntimeException $e) {
		    return 'Problem authenticating Google Calendar:'.$e->getMessage();
		}
		
		// validate input
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);
		
		$title 			= $service_name;
		$start_date 	= date("d",$start_time);
		$start_month 	= date("m",$start_time);
		$start_year 	= date("Y",$start_time);
		
		$end_date 		= $start_date;
		$end_month 		= $start_month;
		$end_year 		= $start_year;
		
		$start_hour 	= date("H",$start_time);
		$start_min		= date("i",$start_time);
		$end_hour		= date("H",$end_time);
		$end_min		= date("i",$end_time);
		
		$start =  $start_year."-".$start_month."-".$start_date."T".$start_hour.":".$start_min.":00".$distance.":00";
		$end   =  $start_year."-".$end_month."-".$end_date."T".$end_hour.":".$end_min.":00".$distance.":00";
		// construct event object
		// save to server    
		
		$service = new Google_Service_Calendar($client);		
		$newEvent = new Google_Service_Calendar_Event();
		$newEvent->setSummary($title);
		$newEvent->setLocation($location);
		$newEvent->setDescription($desc);
		$event_start = new Google_Service_Calendar_EventDateTime();
		$event_start->setDateTime($start);
		$newEvent->setStart($event_start);
		$event_end = new Google_Service_Calendar_EventDateTime();
		$event_end->setDateTime($end);
		$newEvent->setEnd($event_end);
		
		$createdEvent = null;
		//if($this->cal_id != ""){
		try {
			$createdEvent = $service->events->insert($gcalendarid, $newEvent);
			$createdEvent_id= $createdEvent->getId();
			
			$db->setQuery("Update #__app_sch_order_items set gcalendar_event_id = '$createdEvent_id' where id = '$order_item_id'");
			$db->query();
		} catch (Google_ServiceException $e) {
			//logIt("svgcal_v3,".$e->getMessage()); 
			echo $e->getMessage();
			exit;
		}	
	}
	
	/**
	 * Remove events of Order on Google calendar
	 *
	 * @param unknown_type $id
	 */
	function removeEventOnGCalendar($id){
		global $mainframe;
		jimport('joomla.filesystem.file');	
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_order_items where order_id = '$id'");
		$items = $db->loadObjectList();
		if(count($items) > 0){
			for($i=0;$i<count($items);$i++){
				$item = $items[$i];
				$db->setQuery("Select * from #__app_sch_employee where id = '$item->eid'");
				$employee = $db->loadObject();
				$client_id = $employee->client_id;
				$app_name = $employee->app_name;
				$app_email_address = $employee->app_email_address;
				$p12_key_filename = $employee->p12_key_filename;
				$gcalendarid = $employee->gcalendarid;
				if(($client_id != "") and ($app_name != "")and ($app_email_address != "") and ($gcalendarid != "") and ($p12_key_filename != "") and (JFile::exists(JPATH_COMPONENT_SITE."/".$p12_key_filename)) ){
					self::removeEventsonGCalendar(trim($client_id),trim($app_name),trim($app_email_address),trim($p12_key_filename),trim($gcalendarid),$item->id,$item->gcalendar_event_id);
				}
			}
		}
	}
	
	
	/**
	 * Remove events of Order on Google calendar
	 *
	 * @param unknown_type $id
	 */
	function removeOneEventOnGCalendar($id){
		global $mainframe;
		jimport('Joomla.FileSystem.File');	
		$db = JFactory::getDbo();
		//$item = $items[$i];
		$db->setQuery("Select * from #__app_sch_order_items where id = '$id'");
		$item = $db->loadObject();
		$db->setQuery("Select * from #__app_sch_employee where id = '$item->eid'");
		$employee = $db->loadObject();
		$client_id = $employee->client_id;
		$app_name = $employee->app_name;
		$app_email_address = $employee->app_email_address;
		$p12_key_filename = $employee->p12_key_filename;
		$gcalendarid = $employee->gcalendarid;
		if(($client_id != "") and ($app_name != "")and ($app_email_address != "") and ($gcalendarid != "") and ($p12_key_filename != "") and (JFile::exists(JPATH_COMPONENT_SITE."/".$p12_key_filename)) ){
			self::removeEventsonGCalendar(trim($client_id),trim($app_name),trim($app_email_address),trim($p12_key_filename),trim($gcalendarid),$item->id,$item->gcalendar_event_id);
		}
	}
	
	/**
	 * Remove Event on Google calendar
	 *
	 * @param unknown_type $gusername
	 * @param unknown_type $gpassword
	 * @param unknown_type $gcalendarid
	 * @param unknown_type $id
	 * @param unknown_type $gcalendar_event_id
	 */
	public static function removeEventsonGCalendar($client_id,$app_name,$app_email_address,$p12_key_filename,$gcalendarid,$id,$gcalendar_event_id){
		global $mainframe,$configClass;
		// load classes
		$path = JPATH_COMPONENT_SITE.DS."google-api-php-client-master".DS."src".DS."Google";
		set_include_path(get_include_path() . PATH_SEPARATOR . $path);
		//echo $path;
		
		if(!file_exists ( $path.DS.'Client.php' )){
			echo "OSB set to use Google Calendar but the Google Library is not installed.";
			exit;
		}	
		require_once $path.DS."Client.php";
	    require_once $path.DS."Service.php";
		
		try {
	 	    $client = new Google_Client();
			$client->setApplicationName($app_name);
			$client->setClientId($client_id);
			$client->setAssertionCredentials( 
				new Google_Auth_AssertionCredentials(
					$app_email_address,
					array("https://www.googleapis.com/auth/calendar"),
					file_get_contents(JPATH_COMPONENT_SITE.DS.$p12_key_filename),
					'notasecret','http://oauth.net/grant_type/jwt/1.0/bearer',false,false
				)
			);
		}
		catch (RuntimeException $e) {
		    return 'Problem authenticating Google Calendar:'.$e->getMessage();
		}
		
		
		$service = new Google_Service_Calendar($client);
		if($gcalendar_event_id != ""){
			try {
			$service->events->delete($gcalendarid, $gcalendar_event_id);
				//$event->delete();
			} catch (Exception $e) {
				//logIt("svgcal_v3 (del 1),".$e->getMessage()); 
				//echo $e->getMessage();
				//exit;
			}
		}
	}
	
	/**
	 * Check available date
	 *
	 * @param unknown_type $sid
	 * @param unknown_type $eid
	 * @param unknown_type $date
	 * @return unknown
	 */
	public static function checkAvailableDate($sid,$eid,$date){
		global $mainframe;
		$db = JFactory::getDbo();
		$date_int = strtotime($date);
		$date_we  = date("N",$date_int);
		$db->setQuery("Select `is_day_off` from #__app_sch_working_time where id = '$date_we'");
		$is_day_off = $db->loadResult();
		if($is_day_off == 0){
			$db->setQuery("Select count(id) from #__app_sch_working_time_custom where `worktime_date` = '$date'");
			$count = $db->loadResult();
			if($count > 0){
				$db->setQuery("Select `is_day_off` from #__app_sch_working_time_custom where `worktime_date` = '$date'");
				$vl = $db->loadResult();
				if($vl == 0){
					return false;
				}else{
					return true;
				}
			}
		}else{
			return true;
		}
	}
	
	public static function isEmployeeAvailableInSpecificDate($sid,$eid,$date){
		$db = Jfactory::getDbo();
		$date_int = strtotime($date);
		$date_in_week = date("D",$date_int);
		$date_in_week = strtolower($date_in_week);
		$date_in_week = substr($date_in_week,0,2);
		
		$query = $db->getQuery(true);
		$query->select('count(id)')->from('#__app_sch_employee_service')->where('employee_id = "'.$eid.'" and service_id = "'.$sid.'" and `'.$date_in_week.'` = 1');
		$db->setQuery($query);
		$count = $db->loadResult();
		
		if($count == 0){
			return false;
		}else{
			return true;
		}
	}
	
	/**
	 * Load time slots
	 *
	 * @param unknown_type $sid
	 * @param unknown_type $eid
	 * @param unknown_type $date
	 */
	public static function loadTimeSlots($sid,$eid,$date){
		global $mainframe;
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);
		$db = JFactory::getDbo();
		//include_once(JPATH_ROOT.'/components/com_osservicesbooking/helpers/common.php');
		//echo date("H:i",HelperOSappscheduleCommon::getRealTime());
		//check to see if employee work on this day
		$date_int = strtotime($date);
		$date_in_week = date("D",$date_int);
		$date_in_week = strtolower($date_in_week);
		$date_in_week = substr($date_in_week,0,2);
		
		$query = $db->getQuery(true);
		$query->select('count(id)')->from('#__app_sch_employee_service')->where('employee_id = "'.$eid.'" and service_id = "'.$sid.'" and `'.$date_in_week.'` = 1');
		$db->setQuery($query);
		$count = $db->loadResult();
		
		if($count > 0){
		
			$query = $db->getQuery(true);
			$query->select("service_time_type");
			$query->from("#__app_sch_services");
			$query->where("id = '$sid'");
			$db->setQuery($query);
			$service_time_type = $db->loadResult();
			if($service_time_type == 0){
				//load normal time slot
				self::loadNormalTimeSlots($sid,$eid,$date);
			}else{
				self::loadCustomTimeSlots($sid,$eid,$date);
			}
			
		}else{
			echo "<h3>".Jtext::_('OS_UNAVAILABLE')."</h3>";
		}
	}
	
	/**
	 * Load Normal time slots
	 *
	 * @param unknown_type $sid
	 * @param unknown_type $eid
	 * @param unknown_type $date
	 */
	public static function loadNormalTimeSlots($sid,$eid,$date){
		global $mainframe,$configClass;
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);	
		
		$date_int = strtotime($date);
		$date_in_week = date("N",$date_int);
		
		$db = JFactory::getDbo();
		if($configClass['multiple_work']  == 1){
			$db->setQuery("SELECT a.* FROM #__app_sch_order_items AS a INNER JOIN #__app_sch_orders AS b ON a.order_id = b.id WHERE a.eid = '$eid' AND a.sid = '$sid' and a.booking_date = '$date' AND b.order_status IN ('P','S')");
		}else{
			$db->setQuery("SELECT a.* FROM #__app_sch_order_items AS a INNER JOIN #__app_sch_orders AS b ON a.order_id = b.id WHERE a.eid = '$eid' and a.booking_date = '$date' AND b.order_status IN ('P','S')");
		}
		//echo $db->getQuery();
		$employees = $db->loadObjectList();
		$tempEmployee = array();
		if(count($employees) > 0){
			for($i=0;$i<count($employees);$i++){
				$employee = $employees[$i];
				$tempEmployee[$i]->start_time = $employees[$i]->start_time;
				$tempEmployee[$i]->end_time   = $employees[$i]->end_time;
			}
		}
		
		$db->setQuery("Select * from #__app_sch_employee_service_breaktime where sid = '$sid' and eid = '$eid' and date_in_week = '$date_in_week'");
		$breaks = $db->loadObjectList();

		for($i=0;$i<count($breaks);$i++){
			$break_time_start = $date." ".$breaks[$i]->break_from;
			$break_time_sint  = strtotime($break_time_start);
			$break_time_end   = $date." ".$breaks[$i]->break_to;
			$break_time_eint  = strtotime($break_time_end);
			$count = count($tempEmployee);
			$tempEmployee[$count]->start_time = $break_time_sint;
			$tempEmployee[$count]->end_time   = $break_time_eint;
			$tempEmployee[$count]->show 	  = 0;
		}
		//print_r($tempEmployee);
		
		$db->setQuery("SELECT * FROM #__app_sch_services WHERE id = '$sid'");
		$service = $db->loadObject();
		$service_length  = $service->service_total;
		$service_total   = $service->service_total;
		$service_total_int = $service_total*60;

		$dateArr = explode("-",$date);
		$dateArr1[0] = $dateArr[2];
		$dateArr1[1] = $dateArr[1];
		$dateArr1[2] = $dateArr[0];
		$time = HelperOSappscheduleCalendar::getAvailableTime($option,$dateArr1);
		$starttimetoday  = strtotime($date." ".$time->start_time);
		$endtimetoday    = strtotime($date." ".$time->end_time);
		$cannotbookstart = $endtimetoday - $service_total_int;

		//$amount	 = $configClass['step_format']*60;

		$step_in_minutes = $service->step_in_minutes;
		if($step_in_minutes == 0){
			$amount	 = $configClass['step_format']*60;
		}elseif($step_in_minutes == 1){
			$amount  = $service_total_int;
		}else{
			$amount  = $step_in_minutes*60;
		}
		?>
		<div class="row-fluid">
		<?php
		$j = 0;
		
		$str = "";
		
		for($inctime = $starttimetoday;$inctime<=$endtimetoday;$inctime = $inctime + $amount){
			
			$start_booking_time = $inctime;
			$end_booking_time	= $inctime + $service_length*60;
			//Modify on 1st May to add the start time from break time
			foreach ($breakTime as $break){
				if(($inctime >= $break->start_time) and ($inctime <= $break->end_time)){
					$inctime = $break->end_time;
					$start_booking_time = $inctime;
					$end_booking_time	= $inctime + $service_length*60;
				}
			}

			$arr1 = array();
			$arr2 = array();
			$arr3 = array();

			if(count($tempEmployee) > 0){
				for($i=0;$i<count($tempEmployee);$i++){
					$employee = $tempEmployee[$i];
					$before_service = $employee->start_time - $service->service_total*60;
					$after_service  = $employee->end_time + $service->service_total*60;
					if(($employee->start_time < $inctime) and ($inctime < $employee->end_time) and ($inctime + $service->service_total*60 == $employee->end_time)){
						//echo "1";
						$arr1[] = $inctime;
						$bgcolor = $configClass['timeslot_background'];
						$nolink = true;
					}elseif(($employee->start_time > $inctime) and ($employee->start_time < $end_booking_time)){
	
						//echo "4";
						$arr2[] = $inctime;
						$bgcolor = "gray";
						$nolink = true;
					}elseif(($employee->end_time > $inctime) and ($employee->end_time < $end_booking_time)){
						//echo "5";
	
						$arr2[] = $inctime;
						$bgcolor = "gray";
						$nolink = true;
					}elseif(($employee->start_time > $inctime) and ($employee->end_time < $end_booking_time)){
	
						//echo "6";
						$arr2[] = $inctime;
						$bgcolor = "gray";
						$nolink = true;
					}elseif(($employee->start_time < $inctime) and ($employee->end_time > $end_booking_time)){
						//echo "7";
	
						$arr2[] = $inctime;
						$bgcolor = "gray";
						$nolink = true;
					}elseif(($employee->start_time == $inctime) or ($employee->end_time == $end_booking_time)){
						//echo "7";
	
						$arr2[] = $inctime;
						$bgcolor = "gray";
						$nolink = true;
					}else{
						//echo "8";
						$arr3[] = $inctime;
						$bgcolor = $configClass['timeslot_background'];
						$nolink = false;
					}
				}
			}else{
				$arr3[] = $inctime;
				$bgcolor = $configClass['timeslot_background'];
				$nolink = false;
			}
			//echo $bgcolor;

			$gray =  0;
			if($inctime + $service->service_total*60 > $endtimetoday){
			//if($inctime >= $cannotbookstart){
				
				$bgcolor = "gray";
				$nolink  = true;

				$gray = 1;
			}
			if($configClass['multiple_work'] == 0){
				if(!OSBHelper::checkMultipleEmployees($sid,$eid,$start_booking_time,$end_booking_time)){
					$bgcolor = "gray";
					$nolink  = true;
				}
			}
				
			if(($date[2] == date("Y",$realtime) and ($date[1] == intval(date("m",$realtime))) and ($date[0] == intval(date("d",$realtime))))){

				//today
				if($inctime <= $realtime){
					$bgcolor = "gray";
					$nolink  = true;

					$gray = 1;
				}
			}
			
			if($gray == 0){
				if(in_array($inctime,$arr2)){
					$bgcolor = "gray";
					$nolink = true;
				}elseif(in_array($inctime,$arr1)){
					$bgcolor = "#FA4876";
					$nolink = true;
				}else{
					$bgcolor = "#7BA1EB";
					$nolink = false;
				}
			}elseif($gray == 1){
				$bgcolor = "gray";
				$nolink  = true;
			}
			if($disable_booking_before > 1){
				if($inctime < $disable_time){
					$bgcolor = "gray";
					$nolink  = true;
				}
			}
			if($disable_booking_after > 1){
				if($inctime > $disable_time_after){
					$bgcolor = "gray";
					$nolink  = true;
				}
			}
			//if(!$nolink){
			if($end_booking_time <= $endtimetoday){
				$j++;
			?>
				<div class="span6" style="border-bottom:1px solid #efefef !important;background-color:<?php echo $bgcolor?> !important;padding:2px;color:white;padding-left:10px;margin-left:1px;">
					<?php
					if(!$nolink){
						$text = JText::_('OS_BOOK_THIS_EMPLOYEE_FROM')."[".date($configClass['date_time_format'],$inctime)."] to [".date($configClass['date_time_format'],$end_booking_time)."]";
						?>
						<input type="checkbox" name="<?php echo $eid?>[]" id="<?php echo $eid?>_<?php echo $inctime?>" onclick="javascript:addBackendBooking('<?php echo $eid?>_<?php echo $inctime?>','<?php echo $inctime?>','<?php echo $end_booking_time;?>');">
						<?php
						$str .= "<option value='".$inctime."-".$end_booking_time."'>".$inctime."</option>";
					}else{
						?>
						<font color="White"><?php echo JText::_('OS_OCCUPIED')?></font>
						<?php
					}
					?>
					&nbsp;&nbsp;&nbsp;
					<?php
					echo date($configClass['time_format'],$inctime);
					?>
					&nbsp; - &nbsp;
					<?php
					echo date($configClass['time_format'],$end_booking_time);
					?>
				</div>
				<?php	
				if($j==2){
					?>
					</div><div class="row-fluid">
					<?php
					$j = 0;
				}
			}
		}
		if($j == 1){
			?>
			</div>
			<?php
		}
		if($j==0){
		?>
		</div>
		<?php
		}
		?>
		<select style="display:none;" name="selected_timeslots[]" id="selected_timeslots" multiple>
			<?php 
			echo $str;
			?>
		</select>
		<?php
	}
	
	
	/**
	 * Load Custom time slots
	 *
	 * @param unknown_type $sid
	 * @param unknown_type $eid
	 * @param unknown_type $date
	 */
	public static function loadCustomTimeSlots($sid,$eid,$date){
		global $mainframe,$configClass;
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);	
		$db = JFactory::getDbo();
		
		$date_int = strtotime($date);
		$date_in_week = date("N",$date_int);
		
		if($configClass['multiple_work']  == 1){
			$db->setQuery("SELECT a.* FROM #__app_sch_order_items AS a INNER JOIN #__app_sch_orders AS b ON a.order_id = b.id WHERE a.eid = '$eid' AND a.sid = '$sid' and a.booking_date = '$date' AND b.order_status IN ('P','S')");
		}else{
			$db->setQuery("SELECT a.* FROM #__app_sch_order_items AS a INNER JOIN #__app_sch_orders AS b ON a.order_id = b.id WHERE a.eid = '$eid' and a.booking_date = '$date' AND b.order_status IN ('P','S')");
		}
		
		$employees = $db->loadObjectList();
		$tempEmployee = array();
		if(count($employees) > 0){
			for($i=0;$i<count($employees);$i++){
				$employee = $employees[$i];
				$tempEmployee[$i]->start_time = $employees[$i]->start_time;
				$tempEmployee[$i]->end_time   = $employees[$i]->end_time;
			}
		}
		
		$db->setQuery("Select * from #__app_sch_employee_service_breaktime where sid = '$sid' and eid = '$eid' and date_in_week = '$date_in_week'");
		$breaks = $db->loadObjectList();

		for($i=0;$i<count($breaks);$i++){
			$break_time_start = $date." ".$breaks[$i]->break_from;
			$break_time_sint  = strtotime($break_time_start);
			$break_time_end   = $date." ".$breaks[$i]->break_to;
			$break_time_eint  = strtotime($break_time_end);
			$count = count($tempEmployee);
			$tempEmployee[$count]->start_time = $break_time_sint;
			$tempEmployee[$count]->end_time   = $break_time_eint;
			$tempEmployee[$count]->show 	  = 0;
		}
		
		$db->setQuery("SELECT * FROM #__app_sch_services WHERE id = '$sid'");
		$service = $db->loadObject();
		$service_length  = $service->service_total;
		$service_total   = $service->service_total;
		$service_total_int = $service_total*60;

		$dateArr = explode("-",$date);
		$dateArr1[0] = $dateArr[2];
		$dateArr1[1] = $dateArr[1];
		$dateArr1[2] = $dateArr[0];
		
		$time = HelperOSappscheduleCalendar::getAvailableTime($option,$dateArr1);
		$starttimetoday  = strtotime($date." ".$time->start_time);
		$endtimetoday    = strtotime($date." ".$time->end_time);
		$cannotbookstart = $endtimetoday - $service_total_int;

		$amount	 = $configClass['step_format']*60;
		$db->setQuery("Select * from #__app_sch_custom_time_slots where sid = '$sid' order by start_hour,start_min");
		$rows = $db->loadObjectList();
		?>
		<div class="row-fluid">
			<?php
			$j = 0;
			$str = "";
			for($i=0;$i<count($rows);$i++){
				$config = new JConfig();
				$offset = $config->offset;
				date_default_timezone_set($offset);	
				$row = $rows[$i];
				$start_hour = $row->start_hour;
				if($start_hour < 10){
					$start_hour = "0".$start_hour;
				}
				$start_min = $row->start_min;
				if($start_min < 10){
					$start_min = "0".$start_min;
				}

				$start_time = $dateArr1[2]."-".$dateArr1[1]."-".$dateArr1[0]." ".$start_hour.":".$start_min.":00";
				//echo $start_time;
				$start_time_int = strtotime($start_time);

				$end_hour = $row->end_hour;
				if($end_hour < 10){
					$end_hour = "0".$end_hour;
				}
				$end_min = $row->end_min;
				if($end_min < 10){
					$end_min = "0".$end_min;
				}

				$end_time = $dateArr1[2]."-".$dateArr1[1]."-".$dateArr1[0]." ".$end_hour.":".$end_min.":00";
				$end_time_int = strtotime($end_time);

				$db->setQuery("Select SUM(a.nslots) as nslots from #__app_sch_order_items as a inner join #__app_sch_orders as b on b.id = a.order_id where b.order_status in ('P','S') and a.start_time =  '$start_time_int' and a.end_time = '$end_time_int' and a.sid = '$sid' and a.eid = '$eid'");
				//$count = $db->loadResult();
				$nslotsbooked = $db->loadObject();
				$count = intval($nslotsbooked->nslots);
				$temp_start_hour = $row->start_hour;
				$temp_start_min  = $row->start_min;
				$temp_end_hour 	 = $row->end_hour;
				$temp_end_min    = $row->end_min;

				$db->setQuery("Select nslots from #__app_sch_custom_time_slots where sid = '$service->id' and start_hour = '$temp_start_hour' and start_min = '$temp_start_min' and end_hour = '$temp_end_hour' and end_min = '$temp_end_min'");
				//echo $db->getQuery();
				$nslots = $db->loadResult();

				//get the number count of the cookie table
				$query = "SELECT SUM(a.nslots) as bnslots FROM #__app_sch_temp_order_items AS a INNER JOIN #__app_sch_temp_orders AS b ON a.order_id = b.id WHERE a.sid = '$sid' AND a.eid = '$eid' AND a.start_time =  '$start_time_int' and a.end_time = '$end_time_int'";
				$db->setQuery($query);
				$bslots = $db->loadObject();
				$count_book = $bslots->bnslots;
				$avail = $nslots - $count - $count_book;

				if($avail <= 0){
					$bgcolor = "#FA4876";
					$nolink = true;
				}else{
					$bgcolor = "#7BA1EB";
					$nolink = false;
				}
				if($configClass['multiple_work'] == 0){
					if(!OSBHelper::checkMultipleEmployees($sid,$eid,$start_time_int,$end_time_int)){
						$bgcolor = "gray";
						$nolink  = true;
					}
				}
				
				if(($dateArr1[2] == date("Y",$realtime) and ($dateArr1[1] == intval(date("m",$realtime))) and ($dateArr1[0] == intval(date("d",$realtime))))){
					//today
					if($start_time_int <= $realtime){
						$bgcolor = "gray";
						$nolink  = true;
					}
				}
				
				if(count($tempEmployee) > 0){
					//print_r($tempEmployee);
					for($k=0;$k<count($tempEmployee);$k++){
						$employee = $tempEmployee[$k];

						$before_service = $employee->start_time;
						//echo date("H:i",$after_service);
						//echo "<BR />";
						$after_service  = $employee->end_time;
						//echo date("H:i",$employee->start_time)."-".date("H:i",$employee->end_time)."  ".date("H:i",$start_time_int)."-".date("H:i",$end_time_int);
						//echo "<BR>";
						//echo date("H:i",$employee->end_time)." - ".date("H:i",$start_time_int);
						//echo "<BR />";
						
						//echo date("H:i",$employee->end_time)."*".$employee->end_time." - ".date("H:i",$end_time_int)."*".$end_time_int;
						//echo "<BR />";
						//echo $employee->end_time." - ".$start_time_int;
						//echo "(";
						///echo date("H:i",$employee->end_time)." - ".date("H:i",$start_time_int);
						//echo ")";
						//echo "<BR />";
						
						//if($employee->end_time > $start_time_int){
							///echo date("H:i",$start_time_int);
						//}
						if(($employee->start_time < $start_time_int) and ($end_time_int < $employee->end_time)){
							//echo "1";
							if(($avail <= 0) or ($employee->show == 0)){
								$bgcolor = "gray";
								$nolink = true;
							}
						}elseif(($employee->start_time > $start_time_int) and ($employee->start_time < $end_time_int)){
						
							//echo "2";
							if(($avail <= 0) or ($employee->show == 0)){
								$bgcolor = "gray";
								$nolink = true;
							}
						}elseif(($employee->end_time > $start_time_int) and ($employee->end_time < $end_time_int)){
							
							//echo "3";
							if(($avail <= 0) or ($employee->show == 0)){
								$bgcolor = "gray";
								$nolink = true;
							}
						}elseif(($employee->start_time <= $start_time_int) and ($employee->end_time >= $start_time_int)){
							//echo "4 ".$avail;
							//echo date("H:i",$employee->start_time)." - ".date("H:i",$start_time_int);
							//echo "<BR />";
							//echo date("H:i",$employee->end_time)." - ".date("H:i",$end_time_int);
							//echo "<BR />";
							//echo "dasdasda";
							if(($avail <= 0) or ($employee->show == 0)){
								$bgcolor = "gray";
								$nolink = true;
							}
						}elseif($end_time_int <= $employee->start_time){
							if($bgcolor != "gray"){
								$bgcolor = $configClass['timeslot_background'];
								$nolink = false;
							}
						}else{
							if($bgcolor != "gray"){
								$bgcolor = $configClass['timeslot_background'];
								$nolink = false;
							}
						}
					}
				}
				//echo $bgcolor;
				if($disable_booking_before > 1){
					if($start_time_int < $disable_time){
						$bgcolor = "gray";
						$nolink  = true;
					}
				}
				if($disable_booking_after > 1){
					if($start_time_int > $disable_time_after){
						$bgcolor = "gray";
						$nolink  = true;
					}
				}
				if((($nolink) and (($configClass['show_occupied'] == 1)) or (!$nolink))){
					if(($end_time_int <= $endtimetoday) and ($start_time_int >= $starttimetoday)){
						$j++;
						?>
						<div class="span6" style="border-bottom:1px solid #efefef !important;background-color:<?php echo $bgcolor?> !important;padding:2px;color:white;padding-left:10px;margin-left:1px;">
							<?php
							if(!$nolink){
								$text = "Book this employee from [".date($configClass['date_time_format'],$start_time_int)."] to [".date($configClass['date_time_format'],$end_time_int)."]";
								?>
								<input type="checkbox" name="<?php echo $eid?>[]" id="<?php echo $row->id?>" onclick="javascript:addBackendBooking(<?php echo $row->id?>,'<?php echo $start_time_int?>','<?php echo $end_time_int;?>');">
							<?php
								$str .= "<option value='".$start_time_int."-".$end_time_int."'>".$start_time_int."</option>";
							}else{
								?>
								<font color="White"><?php echo JText::_('OS_OCCUPIED')?></font>
								<?php
							}
							?>
							&nbsp;&nbsp;
							<?php
							$start_hour = $row->start_hour;
							if($start_hour < 10){
								$start_hour = "0".$start_hour;
							}
							//echo ":";
							$start_min = $row->start_min;
							if($start_min < 10){
								$start_min = "0".$start_min;
							}
							
							echo date($configClass['time_format'],strtotime(date("Y-m-d",$start_time_int)." ".$start_hour.":".$start_min.":00"));
							?>		
							&nbsp;-&nbsp;
							<?php
							$end_hour = $row->end_hour;
							if($end_hour < 10){
								$end_hour = "0".$end_hour;
							}
							$end_min = $row->end_min;
							if($end_min < 10){
								$end_min = "0".$end_min;
							}
							echo date($configClass['time_format'],strtotime(date("Y-m-d",$start_time_int)." ".$end_hour.":".$end_min.":00"));
							?>	
							<BR />
							<?php
							echo JText::_('OS_NUMBER_SITS').": ";
							?>
							<select name="nslots<?php echo $start_time_int."-".$end_time_int?>" id="nslots<?php echo $start_time_int."-".$end_time_int?>" class="input-mini">
								<?php
								for($k=1;$k<=$avail;$k++){
									?>
									<option value="<?php echo $k?>"><?php echo $k?></option>
									<?php
								}
								?>
							</select>
						</div>
						<?php
					}
					if($j == 2){
						$j = 0;
						?>
						</div><div class="row-fluid">
						<?php
					}
				}
			}
			if($j==1){
			?>
			</div>
			<?php
			?>
			<?php
		}
		if($j==0){
		?>
		</div>
		<?php
		}
		?>
		<select style="display:none;" name="selected_timeslots[]" id="selected_timeslots" multiple>
			<?php 
			echo $str;
			?>
		</select>
		<?php
	}
	
	/**
	 * Get time zone
	 *
	 * @param unknown_type $remote_tz
	 * @param unknown_type $origin_tz
	 * @return unknown
	 */
	public static function get_timezone_offset($remote_tz, $origin_tz = null) {
	    if($origin_tz === null) {
	        if(!is_string($origin_tz = date_default_timezone_get())) {
	            return false; // A UTC timestamp was returned -- bail out!
	        }
	    }
	    $origin_dtz = new DateTimeZone($origin_tz);
	    $remote_dtz = new DateTimeZone($remote_tz);
	    $origin_dt = new DateTime("now", $origin_dtz);
	    $remote_dt = new DateTime("now", $remote_dtz);
	    $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
	    return $offset;
	}


	/**
	 * Show time
	 *
	 * @param position $timezone
	 * @param int $timevalue
	 */
	function showTime($timezone,$timevalue1,$timevalue2){
		global $configClass;
		$config = new JConfig();
		$rs = "";
		if($timezone  != ""){
			$timevalue1a    = date("Y-m-d H:i:s",$timevalue1);
			$timevalue2a   	= date("Y-m-d H:i:s",$timevalue2);
			
			$offset 		= $config->offset;
			$userTimezone 	= new DateTimeZone($offset);
			$gmtTimezone 	= new DateTimeZone('GMT');
			$myDateTime1 	= new DateTime($timevalue1a, $gmtTimezone);
			$myDateTime2 	= new DateTime($timevalue2a, $gmtTimezone);
			
			$offset 		= self::get_timezone_offset($timezone,$offset);
			
			$timevalue1 	-= $offset;
			$timevalue2 	-= $offset;
			$rs .= date($configClass['date_format'],$timevalue1).' : ';
			$rs .= date($configClass['time_format'],$timevalue1);
			$rs .= "-";
			$rs .= date($configClass['time_format'],$timevalue2);
		}
		return $rs;
	}
	
	
	
	
	/**
	 * Check coupon available
	 *
	 * @param unknown_type $sid
	 */
	function checkCouponAvailable(){
		global $mainframe;
		$db = JFactory::getDbo();
		$current_date = HelperOSappscheduleCommon::getRealTime();
		$current_date = date("Y-m-d H:i:s",$current_date);
		$db->setQuery("Select count(a.id) from #__app_sch_coupons as a where a.published = '1' and ((a.expiry_date = '' or a.expiry_date = '0000-00-00 00:00:00') or (a.expiry_date <> '' and a.expiry_date > '$current_date'))");
		$ncoupons = $db->loadResult();
		if($ncoupons > 0){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Convert all img tags to use absolute URL
	 * @param string $html_content
	 */
	public static function convertImgTags($html_content)
	{
		$patterns = array();
		$replacements = array();
		$i = 0;
		$src_exp = "/src=\"(.*?)\"/";
		$link_exp = "[^http:\/\/www\.|^www\.|^https:\/\/|^http:\/\/]";
		$siteURL = JURI::root();
		preg_match_all($src_exp, $html_content, $out, PREG_SET_ORDER);
		foreach ($out as $val)
		{
			$links = preg_match($link_exp, $val[1], $match, PREG_OFFSET_CAPTURE);
			if ($links == '0')
			{
				$patterns[$i] = $val[1];
				$patterns[$i] = "\"$val[1]";
				$replacements[$i] = $siteURL . $val[1];
				$replacements[$i] = "\"$replacements[$i]";
			}
			$i++;
		}
		$mod_html_content = str_replace($patterns, $replacements, $html_content);
		
		return $mod_html_content;
	}
	
	public static function getUserTimeZone(){
		global $configClass;
		$config = new JConfig();
		$offset = $config->offset;
		if($configClass['allow_multiple_timezones'] == 1){
			$user = JFactory::getUser();
			if($user->id > 0){
				 $timezone = $user->getParam('timezone', $offset);
			}
		}
		return $timezone;
	}
	
	public static function getConfigTimeZone(){
		$config = new JConfig();
		$offset = $config->offset;
		return $offset;
	}
	
	public static function convertTimezone($int_time){
		$datetime = new DateTime(date("Y-m-d H:i:s",$int_time), new DateTimeZone(self::getConfigTimeZone()));
		$la_time = new DateTimeZone(self::getUserTimeZone());
		$datetime->setTimezone($la_time);
		return strtotime($datetime->format('Y-m-d H:i:s'));
		
	}
	
	public static function isOffDay($date_int)
	{
		$date_we  = date("N",$date_int);
		$db = JFactory::getDbo();
		$db->setQuery("Select `is_day_off` from #__app_sch_working_time where id = '$date_we'");
		$is_day_off = $db->loadResult();
		if($is_day_off == 0){
			$db->setQuery("Select count(id) from #__app_sch_working_time_custom where (`worktime_date` <= '$date' and `worktime_date_to` >= '$date')");
			$count = $db->loadResult();
			if($count > 0){
				$db->setQuery("Select `is_day_off` from #__app_sch_working_time_custom where (`worktime_date` <= '$date' and `worktime_date_to` >= '$date')");
				$vl = $db->loadResult();
				if($vl == 0){
					$is_day_off = 0;
				}else{
					$is_day_off = 1;
				}
			}
		}
		if($is_day_off == 0)
		{
			return false;
		}else {
			return true;
		}
	}
	
	public static function getServices($category_id, $employee_id,$vid)
	{
		$db = JFactory::getDbo();
		$catSql = "";
		if($category_id > 0){
			$catSql = " and category_id = '$category_id' ";
			$db->setQuery("Select * from #__app_sch_categories where id = '$category_id'");
			$category = $db->loadObject();
		}
		
		if($employee_id > 0){
			$employeeSql = " and id in (Select service_id from #__app_sch_employee_service where employee_id = '$employee_id')";
		}else{
			$employeeSql = "";
		}
		
		if($vid > 0){
			$vidSql = " and id in (Select sid from #__app_sch_venue_services where vid = '$vid')";
		}else{
			$vidSql = "";
		}
		
		$db->setQuery("Select * from #__app_sch_services where published = '1' $catSql $employeeSql $vidSql order by ordering");
		$services = $db->loadObjectList();
		
		return $services;
	}
	
	public static function loadEmployees($services,$employee_id,$tempdate,$vid)
	{
		$db = JFactory::getDbo();
		$return = 0;
		$day = strtolower(substr(date("D",$tempdate),0,2));
		$day1 = date("Y-m-d",$tempdate);
		foreach ($services as $service){
			$sid = $service->id;
			if($vid > 0){
				$vidSql = " and a.id IN (Select employee_id from #__app_sch_employee_service where service_id = '$sid' and vid = '$vid')";
			}else{
				$vidSql = "";
			}
			if($employee_id > 0){
				$employeeSql = " and a.id = '$employee_id'";
			}else{
				$employeeSql = "";
			}
			$db->setQuery("Select a.* from #__app_sch_employee as a inner join #__app_sch_employee_service as b on a.id = b.employee_id where a.published = '1' and b.service_id = '$sid' and b.".$day." = '1' and a.id NOT IN (Select eid from #__app_sch_employee_rest_days where rest_date <= '$day1' and rest_date_to >= '$day1') $vidSql $employeeSql order by b.ordering");
			$employees = $db->loadObjectList();
			
			if(count($employees) > 0)
			{
				$return = 1;
				
			}
		}
		
		if($return == 1){
			return true;
		}else{
			return false;
		}
	}
	
	function checkDateInVenue($vid,$checkdate){
		if(! isset($vid))
		{
			return 1;
		}else{
			$currentdate = HelperOSappscheduleCommon::getRealTime();
			
			$db = JFactory::getDbo();
			$db->setQuery("Select * from #__app_sch_venues where id = '$vid'");
			$venue = $db->loadObject();
			$disable_booking_before = $venue->disable_booking_before;
			switch ($disable_booking_before){
				case "2":
					$number_date_before = $venue->number_date_before;
					if($currentdate > $checkdate - ($number_date_before-1)*3600*24){
						return 0;
					}
				break;
				case "3":
					$disable_date_before = $venue->disable_date_before;
					$disable_date_before = strtotime($disable_date_before);
					//echo date("Y-m-d",$checkdate);
					if($disable_date_before > $checkdate){
						return 0;
						
					}
				break;
				case "4":
					$number_hour_before = $venue->number_hour_before;
					$number_date_before	= $number_hour_before / 24;
					if($number_date_before > 1){
						$mod = $number_hour_before % 24;
						$number_date_before = $number_hour_before / 24;
						if($currentdate > $checkdate - ($number_date_before-1)*3600*24){
							return 0;
						}
					}
				break;
			}
			
			
			$disable_booking_after = $venue->disable_booking_after;
			switch ($disable_booking_after){
				case "2":
					$number_date_after = $venue->number_date_after;
					if($currentdate + ($number_date_after-1)*3600*24 < $checkdate){
						return 0;
					}
				break;
				case "3":
					$disable_date_after = $venue->disable_date_after;
					$disable_date_after = strtotime($disable_date_after);
					//echo date("Y-m-d",$checkdate);
					if($disable_date_after < $checkdate){
						return 0;
						
					}
				break;
			}
		}
		return 1;
	}
	
	public static function isTheSameDate($date1,$date2){
		
		if(($date1 != "") and ($date2 != "")){
			$date1 = explode(" ",$date1);
			$date1 = $date1[0];
			$date2 = explode(" ",$date2);
			$date2 = $date2[0];

			
			if(strtotime($date1) == strtotime($date2)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	public static function checkCommercialOptions($field){
		$db = JFactory::getDbo();
		$tempArr = array();
		if($field->field_type == 1){
			$db->setQuery("Select * from #__app_sch_field_options where field_id = '$field->id'");
			$rows = $db->loadObjectList();
			if(count($rows) > 0){
				foreach ($rows as $row){
					if($row->additional_price > 0){
						if(!in_array("field_".$field->id."||1",$tempArr)){
							$tempArr[] = "field_".$field->id."||1";
						}
					}
				}
			}
		}elseif($field->field_type == 2){
			$db->setQuery("Select * from #__app_sch_field_options where field_id = '$field->id'");
			$rows = $db->loadObjectList();
			if(count($rows) > 0){
				$i = 0;
				foreach ($rows as $row){
					if($row->additional_price > 0){
						$tempArr[] = "field_".$field->id."_checkboxes".$i."||2";
					}
					$i++;
				}
			}
		}
		return implode(",",$tempArr);
	}
	
	public static function encrypt_decrypt($action, $string) {
		//$plain_txt = "This is my plain text";
		//
		//$encrypted_txt = encrypt_decrypt('encrypt', $plain_txt);
		//echo "Encrypted Text = $encrypted_txt\n";
		//echo "<br />";
		//$decrypted_txt = encrypt_decrypt('decrypt', $encrypted_txt);
		//echo "Decrypted Text = $decrypted_txt\n";
	   $output = false;
	   
	//	if(function_exists( 'mcrypt_module_open' ) == false){
	//		logIt("Encryption module mcrypt is not enabled, some data is not being encrypted. For better security you should enable mcrypt.", "be_func2", "", "");
	//	}
		
		if(function_exists( 'mcrypt_module_open' ) == false || $string == "" || $string == null){
			return $string;
		}
	
	   $key = 'Sri}CU_BVD]X57v88RgNSGtM75xVX6';
	
	   // initialization vector 
	   $iv = md5(md5($key));
	
	   if( $action == 'encrypt' ) {
	       $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, $iv);
	       $output = base64_encode($output);
	   }
	   else if( $action == 'decrypt' ){
	       $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, $iv);
	       $output = rtrim($output, "");
	   }
	   return $output;
	}
	
	
	/**
	 * Return Service Price
	 * @param unknown_type $sid
	 * @param unknown_type $date
	 * @return unknown
	 */
	public static function returnServicePrice($sid,$date,$nslots = 1){
		$db = JFactory::getDbo();
		$amount = 0;
		$db->setQuery("Select service_price from #__app_sch_services where id = '$sid'");
		$price = $db->loadResult();
		
		$date_int = strtotime($date);
		$date_in_week = date("N",$date_int);

		$db->setQuery("Select count(id) from #__app_sch_service_price_adjustment where sid = '$sid' and date_in_week = '$date_in_week'");
		$count = $db->loadResult();

		if($count > 0){

			$db->setQuery("Select * from #__app_sch_service_price_adjustment where sid = '$sid' and date_in_week = '$date_in_week'");
			$adjustment_price = $db->loadObject();
			if($adjustment_price->same_as_original == 1){
				$price = $price;
			}else{
				$price = $adjustment_price->price;
			}
		}else{
			$price = $price;
		}
		
		$db->setQuery("Select count(id) from #__app_sch_service_custom_prices where sid = '$sid' and cstart <= '$date' and cend >= '$date'");
		$count = $db->loadResult();
		if($count > 0){
			$db->setQuery("Select amount from #__app_sch_service_custom_prices where sid = '$sid' and cstart <= '$date' and cend >= '$date'");
			$amount = $db->loadResult();
			$price_with_discount = self::checkEarlyBirdDiscount($sid,$date,$amount);//$amount;
			$price = self::discountBySlots($sid,$price_with_discount,$nslots);
			return $price;
		}else{
			$price_with_discount = self::checkEarlyBirdDiscount($sid,$date,$price);//$price;
			$price = self::discountBySlots($sid,$price_with_discount,$nslots);
			return $price;
		}
	}
	
	/**
	 * Discount by number slots
	 *
	 * @param unknown_type $sid
	 * @param unknown_type $price
	 * @param unknown_type $nslots
	 */
	public static function discountBySlots($sid,$price,$nslots){
		$db = JFactory::getDbo();
		$configClass = self::loadConfig();
		if($configClass['enable_slots_discount'] == 1){
			$db->setQuery("Select service_time_type,discount_timeslots,discount_type,discount_amount from #__app_sch_services where id = '$sid'");
			$service = $db->loadObject();
			if(($service->discount_timeslots > 0) and ($service->discount_timeslots <= $nslots) and ($service->service_time_type == 1)){
				$discount_type = $service->discount_type;
				$discount_amount = $service->discount_amount;
				if($discount_type == 0){
					$discount = $discount_amount;
				}else{
					$discount = round($discount_amount*$price/100,2);
				}
			}else{
				return $price;
			}
			if($price - $discount < 0){
				return 0;
			}else{
				return $price - $discount;
			}
		}else{
			return $price;
		}
	}
	
	/**
	 * Check Early Bird Discount
	 *
	 * @param unknown_type $sid
	 * @param unknown_type $date
	 */
	public static function checkEarlyBirdDiscount($sid,$date,$amount){
		$db = JFactory::getDbo();
		$configClass = OSBHelper::loadConfig();
		$enable_early_bird = $configClass['early_bird'];
		if($enable_early_bird == 1){
			$db->setQuery("Select early_bird_amount, early_bird_type,early_bird_days from #__app_sch_services where id = '$sid'");
			$bird = $db->loadObject();
			$current_date = HelperOSappscheduleCommon::getRealTime();
			$date_int = strtotime($date);
			if($current_date + $bird->early_bird_days*3600*24 <= $date_int){
				if($bird->early_bird_type == 0){
					$discount = $bird->early_bird_amount;
				}else{
					$discount = round($bird->early_bird_amount*$amount/100,2);
				}
			}else{
				return $amount;
			}
			if($amount - $discount < 0){
				return 0;
			}else{
				return $amount - $discount;
			}
		}else{
			return $amount;
		}
	}
	
	/**
	 * Generate Decimal number
	 *
	 * @param unknown_type $value
	 * @return unknown
	 */
	public static function generateDecimal($value){
		return rtrim(rtrim($value,'0'),'.');
	}
	
	public static function customServicesDiscountChecking($sid){
		$configClass = self::loadConfig();
		if($configClass['enable_slots_discount'] == 1){
			$db = JFactory::getDbo();
			$db->setQuery("Select discount_timeslots,discount_type,discount_amount from #__app_sch_services where id = '$sid'");
			$service = $db->loadObject();
			if(($service->discount_amount > 0) and ($service->discount_timeslots > 0)){
				if($service->discount_type == 0){
					$discount = self::generateDecimal($service->discount_amount). " ". $configClass['currency_format']. " ".JText::_('OS_PER_SLOT');
				}else{
					$discount = self::generateDecimal($service->discount_amount) ." % ".JText::_('OS_PER_SLOT_COST');
				}
				?>
				<div class="clearfix"></div>
				<div class="noticeMsg">
					<?php echo JText::sprintf('OS_CUSTOM_TIMESLOTS_DISCOUNT_MSG',$discount, $service->discount_timeslots);?>
				</div>
				<?php 
			}else{
				//do nothing
			}
		}
	}
	
	/**
	 * Order status
	 *
	 * @param unknown_type $order_id
	 * @param unknown_type $status
	 */
	public function orderStatus($order_id = 0,$status){
		switch ($status){
			case "P":
				return JText::_('OS_PENDING');
			break;
			case "S":
				return JText::_('OS_COMPLETED');
			break;
			case "C":
				return JText::_('OS_CANCELED');
			break;
			case "A":
				return JText::_('OS_ATTENDED');
			break;
			case "R":
				return JText::_('OS_REFUNDED');
			break;
			case "D":
				return JText::_('OS_DECLINED');
			break;
			case "T":
				return JText::_('OS_TIMEOUT');
			break;
		}
	}
	
	/**
	 * Build Order status dropdown list
	 *
	 * @param unknown_type $status
	 */
	public static function buildOrderStaticDropdownList($status,$onChangeScript,$firstoption,$name){
		$optionArr = array();
		$statusArr = array(JText::_('OS_PENDING'),JText::_('OS_COMPLETED'),JText::_('OS_CANCELED'),JText::_('OS_ATTENDED'),JText::_('OS_TIMEOUT'),JText::_('OS_DECLINED'),JText::_('OS_REFUNDED'));
		$statusVarriableCode = array('P','S','C','A','T','D','R');
		if($firstoption != ""){
			$optionArr[] = JHtml::_('select.option','',$firstoption);
		}
		for ($i=0;$i<count($statusArr);$i++){
			$optionArr[] = JHtml::_('select.option',$statusVarriableCode[$i],$statusArr[$i]);
		}
		return JHtml::_('select.genericlist',$optionArr,$name,'class="input-medium" '.$onChangeScript,'value','text',$status);
	}
	
	/**
	 * Load OS Services Booking language file
	 */
	public static function loadLanguage()
	{
		static $loaded;
		if (!$loaded)
		{
			$lang = JFactory::getLanguage();
			$tag  = $lang->getTag();
			if (!$tag)
				$tag = 'en-GB';
			$lang->load('com_osservicesbooking', JPATH_ROOT, $tag);
			$loaded = true;
		}
	}


    public static function generateQrcode($order_id){
        jimport('joomla.filesystem.folder');
        if(!JFolder::exists(JPATH_ROOT . '/media/com_osservicesbooking')){
            JFolder::create(JPATH_ROOT . '/media/com_osservicesbooking');
            JFolder::create(JPATH_ROOT . '/media/com_osservicesbooking/qrcodes');
        }
        if(!JFolder::exists(JPATH_ROOT . '/media/com_osservicesbooking/qrcodes')){
            JFolder::create(JPATH_ROOT . '/media/com_osservicesbooking/qrcodes');
        }
        $filename = $order_id . '.png';
        if (!file_exists(JPATH_ROOT . '/media/com_osservicesbooking/qrcodes/' . $filename))
        {
            require_once JPATH_ROOT . '/components/com_osservicesbooking/helpers/phpqrcode/qrlib.php';
            $checkinUrl = self::getSiteUrl() . 'index.php?option=com_osservicesbooking&task=default_checkin&id=' . $order_id;
            QRcode::png($checkinUrl, JPATH_ROOT . '/media/com_osservicesbooking/qrcodes/' . $filename);
        }
    }

    /**
     * Get URL of the site, using for Ajax request
     *
     * @return string
     *
     * @throws Exception
     */
    public static function getSiteUrl()
    {
        $uri  = JUri::getInstance();
        $base = $uri->toString(array('scheme', 'host', 'port'));
        if (strpos(php_sapi_name(), 'cgi') !== false && !ini_get('cgi.fix_pathinfo') && !empty($_SERVER['REQUEST_URI']))
        {
            $script_name = $_SERVER['PHP_SELF'];
        }
        else
        {
            $script_name = $_SERVER['SCRIPT_NAME'];
        }
        $path = rtrim(dirname($script_name), '/\\');
        if ($path)
        {
            $siteUrl = $base . $path . '/';
        }
        else
        {
            $siteUrl = $base . '/';
        }
        if (JFactory::getApplication()->isAdmin())
        {
            $adminPos = strrpos($siteUrl, 'administrator/');
            $siteUrl  = substr_replace($siteUrl, '', $adminPos, 14);
        }

        return $siteUrl;
    }

	public static function getUniqueCookie(){
		$session = JFactory::getSession();
		$unique_cookie = $session->get('unique_cookie');
		return $unique_cookie;
	}
}
?>