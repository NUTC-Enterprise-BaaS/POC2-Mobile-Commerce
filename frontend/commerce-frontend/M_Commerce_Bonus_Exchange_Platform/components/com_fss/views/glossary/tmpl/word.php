<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once( JPATH_COMPONENT.DS.'helper'.DS.'glossary.php' );

?>
<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("GLOSSARY", $this->glossary->word); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'glossary'.DS.'snippet'.DS.'_letter_bar.php'); ?>

<dl class="fss_glossary_wordlist">
	<dt>
		<?php echo $this->glossary->word; ?>	
	</dt>
	<dd>
		<?php echo $this->glossary->description; ?>
		<?php echo $this->glossary->longdesc; ?>
	</dd>
</dl>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>