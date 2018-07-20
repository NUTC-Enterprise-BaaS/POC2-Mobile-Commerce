<?php /*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

$user = JFactory::getUser();
if($user->id == 0 || (!$this->actions->get('directory.access.events') && $this->appSettings->front_end_acl)){
	$app = JFactory::getApplication();
	$return = base64_encode(('index.php?option=com_jbusinessdirectory&view=managecompanyevent'));
	$app->redirect(JRoute::_('index.php?option=com_users&return='.$return,false));
}

$isProfile = true;
?>
<script>
	var isProfile = true;
</script>
<style>
#header-box, #control-panel-link{
	display: none;
}
</style>
<?php 

include(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'event'.DS.'tmpl'.DS.'edit.php');

?>
<div class="clear"></div>
