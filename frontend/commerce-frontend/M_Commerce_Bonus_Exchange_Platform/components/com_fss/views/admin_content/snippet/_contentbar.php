<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<ul class="nav nav-tabs">
	<?php if (FSS_Permission::auth("core.edit.own", "com_fss.announce") || FSS_Permission::auth("core.edit", "com_fss.announce")): ?>
		<li class="<?php if ($this->type == "announce") echo "active";?>">
			<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_content&type=announce' ); ?>'>
				<?php echo JText::_("ANNOUNCEMENTS"); ?>
			</a>
		</li>
	<?php endif; ?>
	
	<?php if (FSS_Permission::auth("core.edit.own", "com_fss.kb") || FSS_Permission::auth("core.edit", "com_fss.kb")): ?>
		<li class="<?php if ($this->type == "kb") echo "active";?>">
			<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_content&type=kb' ); ?>'>
				<?php echo JText::_("KB_ARTICLES"); ?>
			</a> 
		</li>
	<?php endif; ?>
	
	<?php if (FSS_Permission::auth("core.edit.own", "com_fss.faq") || FSS_Permission::auth("core.edit", "com_fss.faq")): ?>
		<li class="<?php if ($this->type == "faqs") echo "active";?>">
			<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_content&type=faqs' ); ?>'>
				<?php echo JText::_("FAQS"); ?>
			</a> 
		</li>	
	<?php endif; ?>
	
	<?php if (FSS_Permission::auth("core.edit.own", "com_fss.glossary") || FSS_Permission::auth("core.edit", "com_fss.glossary")): ?>
		<li class="<?php if ($this->type == "glossary") echo "active";?>">
			<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_content&type=glossary' ); ?>'>
				<?php echo JText::_("GLOSSARY"); ?>
			</a> 
		</li>	
	<?php endif; ?>
</ul>
