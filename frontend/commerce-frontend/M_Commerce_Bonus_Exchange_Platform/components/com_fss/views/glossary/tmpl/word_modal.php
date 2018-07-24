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
<?php echo FSS_Helper::PageStylePopup(); ?>
<?php echo FSS_Helper::PageTitlePopup("GLOSSARY", $this->glossary->word); ?>

<?php echo $this->glossary->description; ?>
<?php echo $this->glossary->longdesc; ?>

<?php echo FSS_Helper::PageStylePopupEnd(); ?>