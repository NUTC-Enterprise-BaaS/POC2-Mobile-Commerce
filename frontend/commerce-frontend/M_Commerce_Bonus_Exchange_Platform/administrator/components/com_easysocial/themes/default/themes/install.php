<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<form name="adminForm" id="adminForm" class="themesForm" method="post" enctype="multipart/form-data">
<div class="row">

	<div class="col-md-6">
		<div class="panel">
			<div class="panel-head">
				<b><?php echo JText::_( 'COM_EASYSOCIAL_THEMES_INSTALL_UPLOAD' );?></b>
				<p><?php echo JText::_( 'COM_EASYSOCIAL_THEMES_INSTALL_UPLOAD_DESC' );?></p>
			</div>

			<div class="panel-body">
				<input type="file" name="package" id="package" class="input" style="width:265px;" data-uniform />
				<button class="btn btn-small btn-es-primary installUpload"><?php echo JText::_( 'COM_EASYSOCIAL_UPLOAD_AND_INSTALL_BUTTON' );?> &raquo;</button>
			</div>
		</div>
	</div>

</div>
<input type="hidden" name="option" value="com_easysocial" />
<input type="hidden" name="controller" value="themes" />
<input type="hidden" name="task" value="upload" />
<?php echo JHTML::_( 'form.token' );?>
</form>
