<?php
/**------------------------------------------------------------------------
 * com_vikchannelmanager - VikChannelManager
 * ------------------------------------------------------------------------
 * author    e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.installer.installer');
jimport('joomla.installer.helper');

/**
 * Script file of VikRestaurants component
 */
class com_vikchannelmanagerInstallerScript {
	/**
	 * method to install the component
	 *
	 *
	 * @return void
	 */
	function install($parent) {
	    
        ?>
        
        <style>
            .vcm-install-error {
                text-align: center;
                border: 1px solid #AA0000;
                border-radius: 5px;
                padding: 10px 10px 10px 10px;
                margin-bottom: 20px;
            }
            
            .vcm-install-error-title {
                font-weight: bold !important;
                color: #AA0000;
            }
            
            .vcm-install-error-message {
                font-style: italic;
                margin-left: 5px;
            }
        </style>
        
        <?php
	    
        $vb_params['currencysymb'] = "&euro;";
        $vb_params['currencyname'] = "EUR";
        $vb_params['emailadmin'] = "";
        $vb_params['dateformat'] = "%Y/%m/%d";
	    
        if( file_exists(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_vikbooking'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'lib.vikbooking.php') ) {
            require_once (JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_vikbooking'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'lib.vikbooking.php');
            $vb_params['currencysymb'] = vikbooking::getCurrencySymb(true);
            $vb_params['currencyname'] = vikbooking::getCurrencyName(true);
            $vb_params['emailadmin'] = vikbooking::getAdminMail(true);
            $vb_params['dateformat'] = vikbooking::getDateFormat(true);
        }
        
        $dbo = JFactory::getDBO();
        foreach( $vb_params as $k => $v ) {
            $q = "UPDATE `#__vikchannelmanager_config` SET `setting`=".$dbo->quote($v)." WHERE `param`=".$dbo->quote($k)." LIMIT 1;";
            $dbo->setQuery($q);
            $dbo->Query($q);
        }
        
		?>
		
		<div style="text-align: center;"><p><strong>Vik Channel Manager - e4j Extensionsforjoomla.com</strong></p><img src="<?php echo JURI::root(); ?>administrator/components/com_vikchannelmanager/assets/css/images/vikchannelmanager.jpg"/></div>
		
		<?php
		
		if( !file_exists(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_vikbooking'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'lib.vikbooking.php') ) {
		    ?><div class="vcm-install-error"><span class="vcm-install-error-title">WARNING!</span><span class="vcm-install-error-message">VikBooking is not installed on this Joomla website so VikChannelManager will not work.</span></div><?php
		} else {
		    require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_vikbooking'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'lib.vikbooking.php');
            if( !defined("E4J_SOFTWARE_VERSION") or version_compare(E4J_SOFTWARE_VERSION, '1.4') == 0 ) {
                ?><div class="vcm-install-error"><span class="vcm-install-error-title">WARNING!</span><span class="vcm-install-error-message">VikChannelManager requires VikBooking v.1.5 or higher. You cannot use this extensions unless you update VikBooking.</span></div><?php
            }
        }
	}

	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) {
		echo 'VikChannelManager was uninstalled. e4j - <a href="http://www.extensionsforjoomla.com">Extensionsforjoomla.com</a>';
	}

	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) {

	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) {

	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) {

	}
}

?>
