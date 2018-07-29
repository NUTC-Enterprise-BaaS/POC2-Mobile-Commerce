<?php
/**
 * @version		$Id: modules.php 10381 2008-06-01 03:35:53Z pasamio $
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * This is a file to add template specific chrome to module rendering.  To use it you would
 * set the style attribute for the given module(s) include in your template to use the style
 * for each given modChrome function.
 *
 * eg.  To render a module mod_test in the sliders style, you would use the following include:
 * <jdoc:include type="module" name="test" style="slider" />
 *
 * This gives template designers ultimate control over how modules are rendered.
 *
 * NOTICE: All chrome wrapping methods should be named: modChrome_{STYLE} and take the same
 * two arguments.
 */

/*
 * Module chrome for rendering the module in a slider
 */
function modChrome_slider($module, &$params, &$attribs)
{
	jimport('joomla.html.pane');
	// Initialize variables
	$sliders = & JPane::getInstance('sliders');
	$sliders->startPanel( JText::_( $module->title ), 'module' . $module->id );
	echo $module->content;
	$sliders->endPanel();
}

function modChrome_e4jstyle($module, &$params, &$attribs)
{
	if (!empty ($module->content)) : ?>
		<div class="moduletable<?php echo htmlspecialchars($params->get('moduleclass_sfx')); ?>">
		<?php if ($module->showtitle != 0) : 
		
		$e4jmodtitle = $module->title; 
		$parts = explode("||", $e4jmodtitle);
		if(isset($parts[1])){
    	$e4jmodtitle = '<div class="e4j-divmenutitle e4j-titlesplit"><span class="e4j-menutitle">'.$parts[0].'</span></div><span class="e4j-menusubtitle">'.$parts[1].'</span>';
		}else{
    		$e4jmodtitle = '<div class="e4j-divmenutitle"><span class="e4j-menutitle">'.$parts[0].'</span></div>';
	};
		?>
			<h3><div class="e4j-menutitle-cnt"><?php echo $e4jmodtitle;  ?></div></h3>
		
		<?php endif; ?>
			<?php echo $module->content; ?>
		</div>
	<?php endif;
}

function modChrome_e4jmainm($module, &$params, &$attribs)
{
	if (!empty ($module->content)) : ?>
		<div class="moduletable<?php echo htmlspecialchars($params->get('moduleclass_sfx')); ?>">
		<?php if ($module->showtitle != 0) : 		
		?>
		<h3></h3>
		<?php endif; ?>
			<?php echo $module->content; ?>
		</div>
	<?php endif;
}

function modChrome_gridmodule($module, &$params, &$attribs)
{
	if (!empty ($module->content)) : ?>
	<div class="module grid-module">
		<div class="moduletable<?php echo htmlspecialchars($params->get('moduleclass_sfx')); ?>">
		<?php if ($module->showtitle != 0) : 
		
		$e4jmodtitle = $module->title; 
		$parts = explode("||", $e4jmodtitle);
		if(isset($parts[1])){
    	$e4jmodtitle = '<div class="e4j-divmenutitle e4j-titlesplit"><span class="e4j-menutitle">'.$parts[0].'</span></div><span class="e4j-menusubtitle">'.$parts[1].'</span>';
		}else{
    		$e4jmodtitle = '<div class="e4j-divmenutitle"><span class="e4j-menutitle">'.$parts[0].'</span></div>';
	};
		?>
			<h3><div class="e4j-menutitle-cnt"><?php echo $e4jmodtitle;  ?></div></h3>
		
		<?php endif; ?>
			<?php echo $module->content; ?>
		</div>
	</div>
	<?php endif;
}

function modChrome_e4jmodule($module, &$params, &$attribs)
{
	if (!empty ($module->content)) : ?>
		<div class="module grid-module">
		<div class="moduletable<?php echo htmlspecialchars($params->get('moduleclass_sfx')); ?>">
		<?php if ($module->showtitle != 0) : 
		
		$e4jmodtitle = $module->title; 
		$parts = explode("||", $e4jmodtitle);
		if(isset($parts[1])){
    	$e4jmodtitle = '<div class="e4j-divmenutitle e4j-titlesplit"><span class="e4j-menutitle">'.$parts[0].'</span></div><span class="e4j-menusubtitle">'.$parts[1].'</span>';
		}else{
    		$e4jmodtitle = '<div class="e4j-divmenutitle"><span class="e4j-menutitle">'.$parts[0].'</span></div>';
	};
		?>
			<h3><div class="e4j-menutitle-cnt"><?php echo $e4jmodtitle;  ?></div></h3>
		
		<?php endif; ?>
			<?php echo $module->content; ?>
		</div>
	</div>
	<?php endif;
}
 ?>