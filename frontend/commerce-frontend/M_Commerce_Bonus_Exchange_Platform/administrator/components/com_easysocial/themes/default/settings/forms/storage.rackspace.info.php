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
defined('_JEXEC') or die('Unauthorized Access');
?>
<p>
	<img src="<?php echo SOCIAL_MEDIA_URI . '/images/admin/rackspace_logo.png';?>" align="left" />
	<?php echo $settings->renderSettingText('Rackspace Introduction'); ?>
</p>
<p>
	<a href="http://aws.amazon.com/s3/" class="btn btn-success btn-mini"><?php echo $settings->renderSettingText('Rackspace Create Account'); ?></a>
	<a href="http://aws.amazon.com/s3/" class="btn btn-primary btn-mini"><?php echo $settings->renderSettingText('Rackspace Documentation'); ?></a>
</p>
