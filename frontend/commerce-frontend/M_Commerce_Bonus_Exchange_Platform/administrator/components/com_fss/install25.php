<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

if (!defined("DS")) define('DS', DIRECTORY_SEPARATOR);

/**
 * Script file of HelloWorld component
 */
class com_fssInstallerScript
{
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) 
	{
		// $parent is the class calling this method
	}
	
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) 
	{
		// $parent is the class calling this method
	}
	
	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
	}
	
	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) 
	{
		$source = $parent->getParent()->getPath('source');
		require_once ($source.DS.'admin'.DS.'updatedb.php');
		
		$updater = new FSSUpdater();
		global $log;
		$log = $updater->Process($source);

		// think this has to be done last
		InstallExtras($source);
		
		FSS_Done();
	}
}

jimport('joomla.application.component.controller');
jimport('joomla.application.component.model');
jimport('joomla.installer.installer');
jimport('joomla.installer.helper');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.archive');
jimport('joomla.filesystem.path');


class Status {
	var $STATUS_FAIL = 'Failed';
	var $STATUS_SUCCESS = 'Success';
	var $infomsg = array();
	var $errmsg = array();
	var $status;
}
$install_status = array();
global $install_status;

function InstallExtras($source)
{
	global $install_status;
	
	$pkg_path = $source.DS.'admin'.DS.'extensions'.DS;
	
	if ($dir = opendir($pkg_path))
	{
		while (($file = readdir($dir)) !== false) 
		{
			if ($file == "." || $file == "..") continue;
			if (stripos($file,".zip") < 1) continue;
			
			$installer = new JInstaller();
			$installer->setOverwrite(true);	
			
			$status = new Status();
			$status->status = $status->STATUS_FAIL;

			$package = JInstallerHelper::unpack( $pkg_path.$file );
			if( $installer->install( $package['dir'] ) )
			{
				$status->status = $status->STATUS_SUCCESS;
			}
			else
			{
				$status->errmsg[]=JText::_("UNABLE_TO_INSTALL_$PKGNAME");
			}
			
			$install_status[$file] = $status;
			
			JInstallerHelper::cleanupInstall( $pkg_path.$file, $package['dir'] );
		}
	}
}


function FSS_Done()
{
	global $install_status;
	global $log;
?>

<h1>Freestyle Support Portal Installation Completed</h1>
<?php if (count($install_status) > 0): ?>
<table class="adminlist table table-striped">
	<thead>
		<tr>
			<th class="title">Sub Component</th>
			<th width="60%">Status</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$i=0; 
	foreach ( $install_status as $component => $status ) {?>
		<tr class="row<?php echo $i; ?>">
			<td class="key"><?php echo $component; ?></td>
			<td>
				<?php echo ($status->status == $status->STATUS_SUCCESS)? '<strong>Installed</strong>' : '<em>Not Installed</em>'?>
				<?php if (count($status->errmsg) > 0 ) {
					foreach ( $status->errmsg as $errmsg ) {
						echo '<br/>Error: ' . $errmsg;
					}
				} ?>
				<?php if (count($status->infomsg) > 0 ) {
					foreach ( $status->infomsg as $infomsg ) {
						echo '<br/>Info: ' . $infomsg;
					}
				} ?>
			</td>
		</tr>	
	<?php
	if ($i=0){ $i=1;} else {$i = 0;}; 
	}?>
		
	</tbody>
</table>
<?php endif; ?>	
<?php
}
?>



