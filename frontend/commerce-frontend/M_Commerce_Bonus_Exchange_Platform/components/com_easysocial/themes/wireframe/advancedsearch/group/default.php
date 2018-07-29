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
<div class="es-dashboard" data-adv-search>
	<div class="es-container">

		<div class="es-content">
			<i class="loading-indicator fd-small"></i>

			<div data-advsearch-content >
				<?php echo $this->includeTemplate( 'site/advancedsearch/group/default.content', array( 'activeGroup' => $activeGroup, 'displayOptions' => $displayOptions ) ); ?>
			</div>
		</div>

	</div>
</div>
