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
<div class="es-album-menu es-media-item-menu es-album-menu-item">

	<?php if($lib->isOwner() || $creator->isFriends($this->my->id)){ ?>
	<div class="btn-group btn-group-xs">
			<div class="btn btn-es btn-media btn-album-favourite dropdown_ <?php echo $album->isFavourite($this->my->id)? 'is-fav btn-es-primary' : '' ?>" data-album-favourite-button>
				<a href="javascript:void(0);" ><i class="fa fa-star"></i> <span><?php echo JText::_('COM_EASYSOCIAL_ALBUMS_FAVOURITE_ALBUM'); ?></span></a>
		</div>
	</div>
	<?php } ?>

	<?php if( $lib->canUpload() || $lib->editable() || $lib->deleteable() ){ ?>
	<div class="btn-group btn-group-xs">

		<?php if( $options['canUpload'] && $lib->canUpload() ){ ?>
		<div class="btn btn-es btn-media" data-album-upload-button>
			<a href="javascript: void(0);"><i class="fa fa-plus"></i> <?php echo JText::_("COM_EASYSOCIAL_ALBUMS_ADD_PHOTOS"); ?></a>
		</div>
		<?php } ?>

		<?php if(($lib->editable() && $lib->isOwner() )|| $lib->deleteable() ){ ?>
		<div class="btn btn-es btn-media dropdown_" data-item-actions-menu>
			<a href="javascript:void(0);" data-bs-toggle="dropdown" class="dropdown-toggle_"><i class="fa fa-cog"></i> <span><?php echo JText::_('COM_EASYSOCIAL_ALBUMS_EDIT'); ?></span> </a>
			<ul class="dropdown-menu">
				<?php if( $lib->editable() && $lib->isOwner()){ ?>
				<li data-album-edit-button>
					<a href="<?php echo $album->getEditPermalink();?>" title="<?php echo $lib->getPageTitle('item');?>"><?php echo JText::_( 'COM_EASYSOCIAL_ALBUMS_EDIT_ALBUM' ); ?></a>
				</li>
				<?php } ?>

				<?php if( $lib->deleteable() ){ ?>
				<li class="divider"></li>
				<li data-album-delete-button>
					<a href="javascript:void(0);"><?php echo JText::_("COM_EASYSOCIAL_ALBUMS_DELETE_ALBUM"); ?></a>
				</li>
				<?php } ?>
			</ul>
		</div>
		<?php } ?>
	</div>
	<?php } ?>
	<div class="btn-group btn-group-xs">
		<?php if ($this->config->get('sharing.enabled')) { ?>
		<div class="btn btn-es btn-media">
			<?php echo FD::get( 'Sharing' , array( 'url' => $album->getPermalink( false , true ) , 'text' => JText::_( 'COM_EASYSOCIAL_ALBUMS_SHARE' ) ) )->html(true, false); ?>
		</div>
		<?php } ?>
		<div class="btn btn-es btn-media">
			<?php echo FD::reports()->getForm( 'com_easysocial' , SOCIAL_TYPE_ALBUM , $album->id , $album->get( 'title' ) , JText::_( 'COM_EASYSOCIAL_ALBUMS_REPORT' ) , JText::_( 'COM_EASYSOCIAL_ALBUMS_REPORT_ALBUM_TITLE' ) , JText::_( 'COM_EASYSOCIAL_ALBUMS_REPORT_DESC' ),
				$album->getPermalink( false , true ) ); ?>
		</div>
	</div>
</div>

<?php if ($lib->editable()) { ?>
<div class="es-album-menu es-media-item-menu es-album-menu-form">
	<div class="btn-group btn-group-xs">
		<div class="btn btn-es btn-media" data-album-cancel-button>
			<a href="<?php echo $album->getPermalink();?>" title="<?php echo $lib->getPageTitle('item');?>"><?php echo JText::_("COM_EASYSOCIAL_ALBUMS_CANCEL"); ?></a>
		</div>
		<div class="btn btn-media btn-es-primary" data-album-done-button>
			<a href="<?php echo $album->getPermalink(); ?>"><i class="fa fa-check"></i> <?php echo JText::_("COM_EASYSOCIAL_ALBUMS_DONE"); ?></a>
		</div>
	</div>
	<div class="btn-group btn-group-xs">
		<?php if( $options['canUpload'] && $lib->canUpload() ){ ?>
		<div class="btn btn-es btn-media" data-album-upload-button>
			<a href="javascript: void(0);"><i class="fa fa-plus"></i> <?php echo JText::_("COM_EASYSOCIAL_ALBUMS_ADD_PHOTOS"); ?></a>
		</div>
		<?php } ?>

		<?php if( $lib->deleteable() ){ ?>
		<div class="btn btn-es btn-media <?php echo (empty($album->id)) ? 'disabled' : ''; ?>" data-album-delete-button>
			<a href="javascript:void(0);"><i class="fa fa-remove"></i> <?php echo JText::_("COM_EASYSOCIAL_ALBUMS_DELETE_ALBUM"); ?></a>
		</div>
		<?php } ?>
	</div>
</div>
<?php } ?>
