<?php /*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div id="dir-listing-description" class="dir-listing-description">
<?php
	if(isset($this->company->description) && $this->company->description!=''){ 
		echo JHTML::_("content.prepare", $this->company->description);
	} else {
		echo JText::_("LNG_NO_COMPANY_DETAILS");
	}
?>
</div>