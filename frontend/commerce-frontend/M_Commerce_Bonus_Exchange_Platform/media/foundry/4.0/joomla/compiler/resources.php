<?php
/**
 * @package   Foundry
 * @copyright Copyright (C) 2010-2013 Stack Ideas Sdn Bhd. All rights reserved.
 * @license   GNU/GPL, see LICENSE.php
 *
 * Foundry is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ROOT . '/media/foundry/4.0/joomla/framework.php');
foreach($deps as $componentName => $component) {

	// Skip foundry
	if ($componentName=='Foundry') continue;

	echo 'FD40.installer("' . $componentName . '", "resources", function($){' . "\n";

	// 4. Templates
	if (!empty($component['template'])) {

		$templates = $component['template'];

		echo '$.require.template.loader(' . $this->getJSONData($templates) . ');' . "\n";
	}

	// 5. Views
	if (!empty($component['view'])) {

		$views = $component['view'];

		echo '$.require.template.loader(' . $this->getJSONData($views) . ');' . "\n";
	}

	// 6. Languages
	if (!empty($component['language'])) {

		$languages = $component['language'];

		echo '$.require.language.loader(' . $this->getJSONData($languages) . ');' . "\n";
	}

	echo '});' . "\n";
}
