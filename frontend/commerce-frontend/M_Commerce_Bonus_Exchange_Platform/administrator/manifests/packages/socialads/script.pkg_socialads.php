<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

defined('_JEXEC') or die();

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class pkg_socialadsInstallerScript
{
	private $componentStatus = "install";

	private $socialads_sh404;

	/** @var array The list of extra modules and plugins to install */
	private $installation_queue = array(
		'modules' => array(
			'admin' => array(
			),
			'site' => array(
			'socialadsmodule' => 1
			)
		),

		// Plugins => { (folder) => { (element) => (published) }* }*
		'plugins' => array(
			'system' => array(
				'tjassetsloader' => 1
			),
			'socialadspromote' => array(
				'cb_profile' => 0,
				'es_profile' => 0,
				'js_event' => 0,
				'js_groups' => 0,
				'js_profile' => 0,
				'sobi' => 0
			),
			'socialadstargeting' => array(
				'plug_esprofiletargeting' => 0,
				'plug_grouptargeting' => 0,
				'plug_jsprofiletargeting' => 0,
				'plug_xiprofiletargeting' => 0
			),
			'payment' => array(
				'2checkout' => 0,
				'alphauserpoints' => 0,
				'authorizenet' => 1,
				'bycheck' => 1,
				'byorder' => 1,
				'ccavenue' => 0,
				'jomsocialpoints' => 0,
				'linkpoint' => 1,
				'paypal' => 1,
				'paypalpro' => 0,
				'payu' => 1
			),
			'emailalerts' => array(
				'jma_socialads' => 0
			),
			'socialadslayout' => array(
				'plug_layout1' => 1,
				'plug_layout2' => 1,
				'plug_layout3' => 1,
				'plug_layout4' => 1,
				'plug_layout5' => 1,
				'plug_layout6' => 1
			),
			'adstax' => array(
				'adstax' => 0
			)
		),
		'libraries' => array(
			'techjoomla' => 1
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
		$status = $this->_installSubextensions($parent);
		//Install Techjoomla Straper
		$straperStatus = $this->_installStraper($parent);
		// Remove obsolete files and folders
		//$removeFilesAndFolders = $this->removeFilesAndFolders;
		//$this->_removeObsoleteFilesAndFolders($removeFilesAndFolders);

		// Code to add ad layout in the Joomla layout folder
		$straperStatus = $this->_addLayout($parent);
		// Show the post-installation page
		$this->_renderPostInstallation($status, $straperStatus, $parent); ?>
		<script type="text/javascript" src="<?php echo JUri::root() . 'components/com_socialads/js/jquery-1.11.min.js' ?>"></script>
		<?php
	}

	/**
	 * Renders the post-installation message
	 */
	//private function _renderPostInstallation($status, $straperStatus, $parent, $msgBox=array())
	private function _renderPostInstallation($status, $straperStatus, $parent)
	{
		$settings = JURI::base() . "index.php?option=com_config&view=component&component=com_socialads";
		$enable="<span class=\"label label-success\">Enabled</span>";
		$disable= "<span class=\"label label-important\">Disabled</span>";
		$updatemsg="Updated Successfully";
		$rows = 1;
		$bsSetupLink = JURI::base() . "index.php?option=com_socialads&view=setup&layout=setup";

		// Show link for payment plugins.
		$rows = 1; ?>
		<div class="alert alert-danger">
			If you are updating SocialAds from version less than 3.1 to the latest version. You need to do the SocialAds settings again from the SocialAds component option For example - Payment Mode
			<a href="<?php echo $settings; ?>"
				target="_blank"
				class="btn btn-primary "> SocialAds Settings</a>
		</div>
		<div class="alert alert-danger">
			To make SocialAds design compatible with Bootstrap 2 or 3 version according to your template refer
			<a href="<?php echo $bsSetupLink; ?>"
				target="_blank"
				class="btn btn-primary "> Setup Instructions</a>
		</div>
		<div class="alert alert-info">
			<?php
			$urlToCleanImages = JRoute::_(JUri::root().'index.php?option=com_socialads&tmpl=component&task=removeimagesCall');?>
			<input type="button" class="btn btn-danger" value="Click here" onclick="window.open('<?php echo $urlToCleanImages; ?>')">
			<b> to Clean unused images.</b>
		</div>
		<div class="techjoomla-bootstrap">
			<!--
			<?php
			//echo '<div class="techjoomla-bootstrap" >';
			//Code To populate database for geo location ?>
			<script type="text/javascript">
				function updategeodb()
				{
					var r=confirm('Are you sure you want to Installed Geo Targeting Tables');
					if (r==true)
					{
						jQuery('#geo_install_table').hide();
						jQuery('#loader_image_div').show();
						jQuery.ajax({
						url: '?option=com_socialads&task=populateGeolocation',
						type: 'GET',
						dataType: '',
						success: function(data) {
							jQuery('#loader_image_div').hide();
							jQuery('#geo_tar').html(data);
							} });
					}
				}
			</script>
			<div class="row-fluid">
				<div class="span12">
					<div id='geo_install_table'>
						<div class="alert alert-info">
							<input type="button" class="btn btn-primary" value="Click here"
								onclick="updategeodb();">
								<b>to Complete Installation for Geo Targeting tables.</b>
						</div>
					</div>
					<?php
				//	$image_path = JUri::root()."/components/com_socialads/images/loader_light_blue.gif";
				//	echo '<div class="alert alert-info" id="loader_image_div" style="display:none">Please wait &nbsp;<img src='.$image_path.' width="128" height="15" border="0"/></div>';
				//	echo JText::_('<div id="geo_tar" style="font-weight:bold; color:green;"></div>');
					?>
					<div class="alert alert-info">
						<?php
						//$clan_img_url=JRoute::_(JUri::root().'index.php?option=com_socialads&tmpl=component&task=removeimages_call');?>
						<input type="button" class="btn btn-danger" value="Click here" onclick="window.open('<?php echo $clan_img_url; ?>')">
						<b> to Clean unused images.</b>
					</div>
				</div>
			</div>
-->
			<table class="table-condensed table">
				<thead>
					<tr class="row1">
						<th class="title" colspan="2"><i>Extension</i></th>
						<th width="30%"><i>Status</i></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="3"></td>
					</tr>
				</tfoot>
				<tbody>
					<tr class="row2">
						<td class="key" colspan="2"><strong>SocialAds component</strong></td>
						<td><strong style="color: green">Installed</strong></td>
					</tr>
					<tr class="row2">
						<td class="key" colspan="2">
							<strong>TechJoomla Strapper <?php echo $straperStatus['version']?></strong> [<?php echo $straperStatus['date'] ?>]
						</td>
						<td>
							<strong>
								<span style="color: <?php echo $straperStatus['required'] ? ($straperStatus['installed']?'green':'red') : '#660' ?>; font-weight: bold;">
									<?php echo $straperStatus['required'] ? ($straperStatus['installed'] ?'Installed':'Not Installed') : 'Already up-to-date'; ?>
								</span>
							</strong>
						</td>
					</tr>
					<tr class="row2">
						<td class="key" colspan="2"><strong><?php echo JText::_("socialads_sh404");?></strong></td>
						<td><strong style="color: <?php echo ($this->socialads_sh404)? "green" : "blue"?>"><?php echo ($this->socialads_sh404)?'Placed sh404sef plugin for SocialAds':'sh404 not found, skipping plugin installation'; ?></strong>
					</tr>
					<?php
						/*if(!empty($msgBox))
						{
							// strore releated msg and menu releated msg
								foreach($msgBox as $key=>$msgTopic)
								{
									if(!empty($msgTopic))
									{
										foreach($msgTopic as $indexMsg=>$statusMsg)
										{
											if(!empty($statusMsg))
											{	?>
												<tr class="row2">
													<td class="key" colspan="2"><strong><?php echo $indexMsg;?></strong></td>
													<td><strong style="color: <?php echo ($statusMsg)? "green" : "red"?>"><?php echo ($statusMsg)? $statusMsg:''; ?></strong>
												</tr>
												<?php
											}
										}
									}
								}
						}*/ ?>

					<?php
					if (count($status->modules)) : ?>
						<tr class="row1">
							<th><i>Module</i></th>
							<th><i>Client</i></th>
							<th></th>
						</tr>
						<?php
						foreach ($status->modules as $module) : ?>
							<tr class="row2 <?php //echo ($rows++ % 2); ?>">
								<td class="key"><?php echo ucfirst($module['name']); ?></td>
								<td class="key"><?php echo ucfirst($module['client']); ?></td>
								<td>
									<strong style="color: <?php echo ($module['result'])? "green" : "red"?>"><?php echo ($this->componentStatus=="install") ?(($module['result'])?'Installed':'Not installed'):$updatemsg; ?></strong>
									<?php
									if($this->componentStatus=="install")
									{
										if(!empty($module['result'])) // if installed then only show msg
										{
											echo $mstat=($module['status']? $enable :$disable);
										}
									} ?>
								</td>
							</tr>
						<?php
						endforeach;?>
					<?php
					endif;?>
					<!-- PLUGIN DETAILS -->
					<?php
					if (count($status->plugins)) : ?>
						<tr class="row1">
							<th colspan="2"><i>Plugin</i></th>
							<th></th>
						</tr>
						<?php
						$oldplugingroup="";
						foreach ($status->plugins as $plugin) :
							if ($oldplugingroup!=$plugin['group'])
							{
								$oldplugingroup=$plugin['group']; ?>
								<tr class="row0">
									<th colspan="2"><i><strong><?php echo ucfirst($oldplugingroup)." Plugins";?></strong></i></th>
									<th></th>
								</tr>
							<?php
							} ?>
							<tr class="row2 <?php //echo ($rows++ % 2); ?>">
								<td colspan="2" class="key"><?php echo ucfirst($plugin['name']); ?></td>
				<!--			<td class="key"><?php //echo ucfirst($plugin['group']); ?></td> -->
								<td>
									<strong style="color: <?php echo ($plugin['result'])? "green" : "red"?>"><?php echo ($this->componentStatus=="install") ?(($plugin['result'])?'Installed':'Not installed'):$updatemsg; ?></strong>
									<?php
									if ($this->componentStatus=="install")
									{
										if(!empty($plugin['result']))
										{
											echo $pstat=($plugin['status']? "<span class=\"label label-success\">Enabled</span>" : "<span class=\"label label-important\">Disabled</span>");
										}
									} ?>
								</td>
							</tr>
						<?php
						endforeach; ?>
					<?php
					endif; ?>

					<!-- LIB INSTALL-->
					<?php
					if (count($status->libraries)) : ?>
						<tr class="row1">
							<th><i>Library</i></th>
							<th></th>
							<th></th>
						</tr>
						<?php
						foreach ($status->libraries as $libraries) : ?>
							<tr class="row2 <?php //echo ($rows++ % 2); ?>">
								<td class="key"><?php echo ucfirst($libraries['name']); ?></td>
								<td class="key"></td>
								<td>
									<strong style="color: <?php echo ($libraries['result'])? "green" : "red"?>"><?php echo ($libraries['result'])?'Installed':'Not installed'; ?></strong>
									<?php
										if(!empty($libraries['result'])) // if installed then only show msg
										{
											//	echo $mstat=($libraries['status']? "<span class=\"label label-success\">Enabled</span>" : "<span class=\"label label-important\">Disabled</span>");
										} ?>
								</td>
							</tr>
						<?php
						endforeach;?>
					<?php
					endif;?>
					<!-- Applications INSTALL -->
					<?php
					if (!empty($status->applications) && count($status->applications)) : ?>
						<tr class="row1">
							<th colspan="2">Applications</th>
							<th></th>
						</tr>
						<?php
						$oldappgroup = "";
						foreach ($status->applications as $app) :
							if ($oldappgroup!=$app['group'])
							{
								$oldappgroup=$app['group']; ?>
								<tr class="row0">
									<th colspan="2"><strong><?php echo ucfirst($oldappgroup)." Application";?></strong></th>
									<th></th>
								</tr>
							<?php
							} ?>
							<tr class="row2 <?php //echo ($rows++ % 2); ?>">
								<td colspan="2" class="key"><?php echo ucfirst($app['name']); ?></td>
								<td>
									<strong style="color: <?php echo ($app['result'])? "green" : "red"?>"><?php echo ($this->componentStatus=="install") ?(($app['result'])?'Installed':'Not installed'):$updatemsg; ?></strong>
									<?php
									if ($this->componentStatus=="install")
									{
										if(!empty($app['result']))
										{
											echo $pstat=($app['status']? "<span class=\"label label-success\">Enabled</span>" : "<span class=\"label label-important\">Disabled</span>");
										}
									} ?>
								</td>
							</tr>
						<?php
						endforeach; ?>
					<?php
					endif; ?>
				</tbody>
			</table>
		</div> <!-- end akeeba bootstrap -->
	<?php
	}

	/**
	 * Installs subextensions (modules, plugins) bundled with the main extension
	 *
	 * @param   JInstaller  $parent  Install extension array
	 *
	 * @return JObject The subextension installation status
	 *
	 * @since  1.6
	 */
	private function _installSubextensions($parent)
	{
		$src = $parent->getParent()->getPath('source');

		$db = JFactory::getDbo();

		$status = new JObject;
		$status->modules = array();
		$status->plugins = array();

		// Modules installation

		if (count($this->installation_queue['modules']))
		{
			foreach ($this->installation_queue['modules'] as $folder => $modules)
			{
				if (count($modules))
				{
					foreach ($modules as $module => $modulePreferences)
					{
						// Install the module

						if (empty($folder))
						{
							$folder = 'site';
						}

						$path = "$src/modules/$folder/$module";

						// If not dir
						if (!is_dir($path))
						{
							$path = "$src/modules/$folder/mod_$module";
						}

						if (!is_dir($path))
						{
							$path = "$src/modules/$module";
						}

						if (!is_dir($path))
						{
							$path = "$src/modules/mod_$module";
						}

						if (!is_dir($path))
						{
							$fortest = '';

							// Continue;
						}

						// Was the module already installed?
						$sql = $db->getQuery(true)
							->select('COUNT(*)')
							->from('#__modules')
							->where($db->qn('module') . ' = ' . $db->q('mod_' . $module));
						$db->setQuery($sql);

						$count = $db->loadResult();
						$installer = new JInstaller;
						$result = $installer->install($path);
						$status->modules[] = array(
							'name' => $module,
							'client' => $folder,
							'result' => $result,
							'status' => $modulePreferences[1]
						);

						// Modify where it's published and its published state
						if (!$count)
						{
							// A. Position and state
							list($modulePosition, $modulePublished) = $modulePreferences;

							if ($modulePosition == 'cpanel')
							{
								$modulePosition = 'icon';
							}

							$sql = $db->getQuery(true)
								->update($db->qn('#__modules'))
								->set($db->qn('position') . ' = ' . $db->q($modulePosition))
								->where($db->qn('module') . ' = ' . $db->q('mod_' . $module));

							if ($modulePublished)
							{
								$sql->set($db->qn('published') . ' = ' . $db->q('1'));
							}

							$db->setQuery($sql);
							$db->execute();

							// B. Change the ordering of back-end modules to 1 + max ordering
							if ($folder == 'admin')
							{
								$query = $db->getQuery(true);
								$query->select('MAX(' . $db->qn('ordering') . ')')
									->from($db->qn('#__modules'))
									->where($db->qn('position') . '=' . $db->q($modulePosition));
								$db->setQuery($query);
								$position = $db->loadResult();
								$position++;

								$query = $db->getQuery(true);
								$query->update($db->qn('#__modules'))
									->set($db->qn('ordering') . ' = ' . $db->q($position))
									->where($db->qn('module') . ' = ' . $db->q('mod_' . $module));
								$db->setQuery($query);
								$db->execute();
							}

							// C. Link to all pages
							$query = $db->getQuery(true);
							$query->select('id')->from($db->qn('#__modules'))
								->where($db->qn('module') . ' = ' . $db->q('mod_' . $module));
							$db->setQuery($query);
							$moduleid = $db->loadResult();

							$query = $db->getQuery(true);
							$query->select('*')->from($db->qn('#__modules_menu'))
								->where($db->qn('moduleid') . ' = ' . $db->q($moduleid));
							$db->setQuery($query);
							$assignments = $db->loadObjectList();
							$isAssigned = !empty($assignments);

							if (!$isAssigned)
							{
								$o = (object) array(
									'moduleid'	=> $moduleid,
									'menuid'	=> 0
								);
								$db->insertObject('#__modules_menu', $o);
							}
						}
					}
				}
			}
		}

		// Plugins installation
		if (count($this->installation_queue['plugins']))
		{
			foreach ($this->installation_queue['plugins'] as $folder => $plugins)
			{
				if (count($plugins))
				{
					foreach ($plugins as $plugin => $published)
					{
						$path = "$src/plugins/$folder/$plugin";

						if (!is_dir($path))
						{
							$path = "$src/plugins/$folder/plg_$plugin";
						}

						if (!is_dir($path))
						{
							$path = "$src/plugins/$plugin";
						}

						if (!is_dir($path))
						{
							$path = "$src/plugins/plg_$plugin";
						}

						if (!is_dir($path))
						{
							continue;
						}

						// Was the plugin already installed?
						$query = $db->getQuery(true)
							->select('COUNT(*)')
							->from($db->qn('#__extensions'))
							->where('( ' . ($db->qn('name') . ' = ' . $db->q($plugin)) . ' OR ' . ($db->qn('element') . ' = ' . $db->q($plugin)) . ' )')
							->where($db->qn('folder') . ' = ' . $db->q($folder));
						$db->setQuery($query);
						$count = $db->loadResult();

						$installer = new JInstaller;
						$result = $installer->install($path);

						$status->plugins[] = array('name' => $plugin, 'group' => $folder, 'result' => $result, 'status' => $published);

						if ($published && !$count)
						{
							$query = $db->getQuery(true)
								->update($db->qn('#__extensions'))
								->set($db->qn('enabled') . ' = ' . $db->q('1'))
								->where('( ' . ($db->qn('name') . ' = ' . $db->q($plugin)) . ' OR ' . ($db->qn('element') . ' = ' . $db->q($plugin)) . ' )')
								->where($db->qn('folder') . ' = ' . $db->q($folder));
							$db->setQuery($query);
							$db->execute();
						}
					}
				}
			}
		}

		// Library installation
		if (count($this->installation_queue['libraries']))
		{
			foreach ($this->installation_queue['libraries']  as $folder => $status1)
			{
					$path = "$src/libraries/$folder";
					$query = $db->getQuery(true)
						->select('COUNT(*)')
						->from($db->qn('#__extensions'))
						->where('( ' . ($db->qn('name') . ' = ' . $db->q($folder)) . ' OR ' . ($db->qn('element') . ' = ' . $db->q($folder)) . ' )')
						->where($db->qn('folder') . ' = ' . $db->q($folder));
					$db->setQuery($query);
					$count = $db->loadResult();

					$installer = new JInstaller;
					$result = $installer->install($path);

					$status->libraries[] = array('name' => $folder, 'group' => $folder, 'result' => $result, 'status' => $status1);

					if ($published && !$count)
					{
						$query = $db->getQuery(true)
							->update($db->qn('#__extensions'))
							->set($db->qn('enabled') . ' = ' . $db->q('1'))
							->where('( ' . ($db->qn('name') . ' = ' . $db->q($folder)) . ' OR ' . ($db->qn('element') . ' = ' . $db->q($folder)) . ' )')
							->where($db->qn('folder') . ' = ' . $db->q($folder));
						$db->setQuery($query);
						$db->execute();
					}
			}
		}

		return $status;
	}

	private function _installStraper($parent)
	{
		$src = $parent->getParent()->getPath('source');

		// Install strapper
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		jimport('joomla.utilities.date');

		$source = $src . '/tj_strapper';
		$target = JPATH_ROOT . '/media/techjoomla_strapper';

		$haveToInstallStraper = false;

		if (!JFolder::exists($target))
		{
			$haveToInstallStraper = true;
		}
		else
		{
			$straperVersion = array();

			if (JFile::exists($target . '/version.txt'))
			{
				$rawData = file_get_contents($target . '/version.txt');
				$info    = explode("\n", $rawData);
				$straperVersion['installed'] = array(
					'version' => trim($info[0]),
					'date'    => new JDate(trim($info[1]))
				);
			}
			else
			{
				$straperVersion['installed'] = array(
					'version' => '0.0',
					'date'    => new JDate('2011-01-01')
				);
			}

			$rawData = file_get_contents($source . '/version.txt');
			$info    = explode("\n", $rawData);
			$straperVersion['package'] = array(
				'version' => trim($info[0]),
				'date'    => new JDate(trim($info[1]))
			);

			$haveToInstallStraper = $straperVersion['package']['date']->toUNIX() > $straperVersion['installed']['date']->toUNIX();
		}

		$installedStraper = false;

		if ($haveToInstallStraper)
		{
			$versionSource = 'package';
			$installer     = new JInstaller;
			$installedStraper = $installer->install($source);
		}
		else
		{
			$versionSource = 'installed';
		}

		if (!isset($straperVersion))
		{
			$straperVersion = array();

			if (JFile::exists($target . '/version.txt'))
			{
				$rawData = file_get_contents($target . '/version.txt');
				$info    = explode("\n", $rawData);
				$straperVersion['installed'] = array(
					'version' => trim($info[0]),
					'date'    => new JDate(trim($info[1]))
				);
			}
			else
			{
				$straperVersion['installed'] = array(
					'version' => '0.0',
					'date'    => new JDate('2011-01-01')
				);
			}

			$rawData = file_get_contents($source . '/version.txt');
			$info    = explode("\n", $rawData);
			$straperVersion['package'] = array(
				'version' => trim($info[0]),
				'date'    => new JDate(trim($info[1]))
			);

			$versionSource = 'installed';
		}

		if (!($straperVersion[$versionSource]['date'] instanceof JDate))
		{
			$straperVersion[$versionSource]['date'] = new JDate;
		}

		return array(
			'required'	=> $haveToInstallStraper,
			'installed'	=> $installedStraper,
			'version'	=> $straperVersion[$versionSource]['version'],
			'date'		=> $straperVersion[$versionSource]['date']->format('Y-m-d'),
		);
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
	 * Uninstalls plugins
	 *
	 * @param   mixed  $parent  Object who called the uninstall method
	 *
	 * @return  void
	 */
	private function uninstallPlugins($parent)
	{
		$app     = JFactory::getApplication();
		$plugins = $parent->get("manifest")->plugins;

		if (count($plugins->children()))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);

			foreach ($plugins->children() as $plugin)
			{
				$pluginName  = (string) $plugin['plugin'];
				$pluginGroup = (string) $plugin['group'];
				$query
					->clear()
					->select('extension_id')
					->from('#__extensions')
					->where(
						array (
							'type LIKE ' . $db->quote('plugin'),
							'element LIKE ' . $db->quote($pluginName),
							'folder LIKE ' . $db->quote($pluginGroup)
						)
					);
				$db->setQuery($query);
				$extension = $db->loadResult();

				if (!empty($extension))
				{
					$installer = new JInstaller;
					$result    = $installer->uninstall('plugin', $extension);

					if ($result)
					{
						$app->enqueueMessage('Plugin ' . $pluginName . ' was uninstalled successfully');
					}
					else
					{
						$app->enqueueMessage('There was an issue uninstalling the plugin ' . $pluginName,
							'error');
					}
				}
			}
		}
	}

	/**
	 * Uninstalls plugins
	 *
	 * @param   mixed  $parent  Object who called the uninstall method
	 *
	 * @return  void
	 */
	private function uninstallModules($parent)
	{
		$app = JFactory::getApplication();

		if (!empty($parent->get("manifest")->modules))
		{
			$modules = $parent->get("manifest")->modules;

			if (count($modules->children()))
			{
				$db    = JFactory::getDbo();
				$query = $db->getQuery(true);

				foreach ($modules->children() as $plugin)
				{
					$moduleName = (string) $plugin['module'];
					$query
						->clear()
						->select('extension_id')
						->from('#__extensions')
						->where(
							array (
								'type LIKE ' . $db->quote('module'),
								'element LIKE ' . $db->quote($moduleName)
							)
						);
					$db->setQuery($query);
					$extension = $db->loadResult();

					if (!empty($extension))
					{
						$installer = new JInstaller;
						$result    = $installer->uninstall('module', $extension);

						if ($result)
						{
							$app->enqueueMessage('Module ' . $moduleName . ' was uninstalled successfully');
						}
						else
						{
							$app->enqueueMessage('There was an issue uninstalling the module ' . $moduleName,
								'error ');
						}
					}
				}
			}
		}
	}

	public function _addLayout($parent)
	{
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');

		$src = $parent->getParent()->getPath('source');
		$bs2LayoutPathAd = $src . "/layouts/ad";
		$bs2LayoutPathAdhtml = $src . "/layouts/adhtml";
		$bs2LayoutPathBs2 = $src . "/layouts/bs2";

		if (JFolder::exists(JPATH_SITE . '/layouts/ad'))
		{
			JFolder::delete(JPATH_SITE . '/layouts/ad');
		}

		if (JFolder::exists(JPATH_SITE . '/layouts/adhtml'))
		{
			JFolder::delete(JPATH_SITE . '/layouts/adhtml');
		}

		if (JFolder::exists(JPATH_SITE . '/layouts/bs2'))
		{
			JFolder::delete(JPATH_SITE . '/layouts/bs2');
		}

		// Copy
		JFolder::copy($bs2LayoutPathAd, JPATH_SITE . '/layouts/ad');
		JFolder::copy($bs2LayoutPathAdhtml, JPATH_SITE . '/layouts/adhtml');
		JFolder::copy($bs2LayoutPathBs2, JPATH_SITE . '/layouts/bs2');
	}

}
