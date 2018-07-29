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

$filter = '';
if( $fid )
{
	$filter = FD::table( 'SearchFilter' );
	$filter->load( $fid );
}
?>
<div class="es-container">

	<div class="pt-10">
		<div class="es-adv-search pl-10">

			<?php if( $this->my->id != 0 ) { ?>
			<legend>
				<div class="row">
					<div class="col-md-12">
						<?php if( $fid ) { ?>
						<span class=""><?php echo JText::sprintf( 'COM_EASYSOCIAL_ADVANCED_SEARCH_LOADED_FROM_FILTER', $this->html( 'string.escape' , $filter->title ) );?></span>
						<a class="fd-small pull-right" href="javascript:void(0);" data-advsearch-deletefilter data-id="<?php echo $fid; ?>">
							<i class="fa fa-minus-circle"></i> <?php echo JText::_( 'COM_EASYSOCIAL_ADVANCED_SEARCH_DELETE_FILTER' );?>
						</a>

						<?php } else { ?>

						<span class=""><?php echo JText::_( 'COM_EASYSOCIAL_ADVANCED_SEARCH_SEARCH_CRITERIA' );?></span>
						<a class="fd-small pull-right" href="javascript:void(0);" data-advsearch-savefilter>
							<i class="fa fa-download"></i> <?php echo JText::_( 'COM_EASYSOCIAL_ADVANCED_SEARCH_SAVE_AS_FILTER' );?>
						</a>

						<?php } ?>
					</div>

				</div>
			</legend>
			<?php } ?>

			<?php
				$routerSegment = array();
				$routerSegment['layout'] = 'advanced';

				if( $fid )
				{
					$routerSegment['fid'] = $filter->getAlias();
				}
			?>

			<form name="frmAdvSearch" method="post" action="<?php echo FRoute::search( $routerSegment ); ?>" data-adv-search-form>

				<div class="es-search-criteria mb-15" data-advsearch-list>
					<?php echo $criteriaHTML; ?>
				</div>

				<div class="es-search-add-criteria mb-20">
					<a href="javascript:void(0);" class="btn btn-es-inverse btn-sm" data-adv-search-add-criteria></i> <?php echo JText::_( 'COM_EASYSOCIAL_ADVANCED_SEARCH_NEW_CRITERIA' ); ?></a>
				</div>

				<legend>
					<?php echo JText::_( 'COM_EASYSOCIAL_ADVANCED_SEARCH_SEARCH_OPTIONS' );?>
				</legend>

				<div class="es-search-options">
					<label class="radio-inline fd-small">
						<input class="mr-5" autocomplete="off" type="radio" name="matchType" value="all" <?php echo ( $match == 'all' ) ? ' checked="checked"' : '' ?> /> <?php echo JText::_( 'COM_EASYSOCIAL_ADVANCED_SEARCH_MATCH_ALL' ); ?>
					</label>
					<label class="radio-inline  fd-small">
						<input class="mr-5" autocomplete="off" type="radio" name="matchType" value="any" <?php echo ( $match == 'any' ) ? ' checked="checked"' : '' ?> /> <?php echo JText::_( 'COM_EASYSOCIAL_ADVANCED_SEARCH_MATCH_ANY' );?>
					</label>
				</div>

				<div class="checkbox es-search-options">
					<label class="fd-small" for="avatarOnly">
						<input id="avatarOnly" autocomplete="off" type="checkbox" name="avatarOnly" value="1" <?php echo ( $avatarOnly ) ? ' checked="checked"' : '' ?> /> <?php echo JText::_( "COM_EASYSOCIAL_ADVANCED_SEARCH_WITH_AVATAR" ); ?>
					</label>
				</div>


				<span><label class="control-label"><?php echo JText::_( 'COM_EASYSOCIAL_ADVANCED_SEARCH_SORTING' );?></label></span>
				<div class="es-search-options">
					<label class="radio-inline fd-small">
						<input class="mr-5" autocomplete="off" type="radio" name="sort" value="default" <?php echo ( $sort == 'default' ) ? ' checked="checked"' : '' ?> /> <?php echo JText::_( 'COM_EASYSOCIAL_ADVANCED_SEARCH_SORT_DEFAULT' ); ?>
					</label>
					<label class="radio-inline  fd-small">
						<input class="mr-5" autocomplete="off" type="radio" name="sort" value="registerDate" <?php echo ( $sort == 'registerDate' ) ? ' checked="checked"' : '' ?> /> <?php echo JText::_( 'COM_EASYSOCIAL_ADVANCED_SEARCH_SORT_LATEST' );?>
					</label>
					<label class="radio-inline  fd-small">
						<input class="mr-5" autocomplete="off" type="radio" name="sort" value="lastvisitDate" <?php echo ( $sort == 'lastvisitDate' ) ? ' checked="checked"' : '' ?> /> <?php echo JText::_( 'COM_EASYSOCIAL_ADVANCED_SEARCH_SORT_LOGIN' );?>
					</label>
				</div>

				<div class="form-actions">
					<button class="btn btn-es-primary pull-right" type="submit" data-advsearch-button><?php echo JText::_( 'COM_EASYSOCIAL_ADVANCED_SEARCH_SEARCH_BUTTON' );?></button>
				</div>

				<?php echo $this->html( 'form.token' ); ?>
				<?php echo $this->html( 'form.itemid' ); ?>
				<input type="hidden" name="option" value="com_easysocial" />
				<input type="hidden" name="view" value="search" />
				<input type="hidden" name="layout" value="advanced" />
			</form>

			<!-- Template for criteria -->
			<?php echo FD::get( 'AdvancedSearch' )->getCriteriaHTML( array( 'isTemplate' => true ) ); ?>

		</div>

	</div>

</div>

<?php if(! is_null( $results ) ) { ?>

<div class="es-container" data-advsearch-result >
	<div class="pt-10 p1-10">
		<div class="es-search-result" data-advsearch-result-list>

			<?php if( $results ) { ?>
				<?php echo $this->includeTemplate( 'site/advancedsearch/user/default.results', array( 'nextlimit' => $nextlimit, 'total' => $total, 'results' => $results, 'displayOptions' => $displayOptions ) ); ?>
			<?php } else { ?>

				<div class="center">
					<i class="icon-es-empty-search"></i>
					<div class="mt-10"><?php echo JText::_('COM_EASYSOCIAL_SEARCH_NO_RECORDS_FOUND'); ?></div>
				</div>

			<?php } ?>

		</div>
	</div>
</div>

<?php } ?>
