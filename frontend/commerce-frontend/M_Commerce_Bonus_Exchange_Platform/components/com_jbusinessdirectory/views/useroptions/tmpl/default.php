<?php // no direct access
/**
* @copyright	Copyright (C) 2008-2009 CMSJunkie. All rights reserved.
* 
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$config = new JConfig();

//retrieving current menu item parameters
$currentMenuId = null;
$activeMenu = JFactory::getApplication()->getMenu()->getActive();
if(isset($activeMenu))
	$currentMenuId = $activeMenu->id ; // `enter code here`
$document = JFactory::getDocument(); // `enter code here`
$app = JFactory::getApplication(); // `enter code here`
if(isset($activeMenu)){
	$menuitem   = $app->getMenu()->getItem($currentMenuId); // or get item by ID `enter code here`
	$params = $menuitem->params; // get the params `enter code here`
}else{
	$params = null;
}

//set page title
if(!empty($params) && $params->get('page_title') != ''){
	$title = $params->get('page_title', '');
}
if(empty($title)){
	$title = JText::_("LNG_CONTROL_PANEL").' | '.$config->sitename;
}
$document->setTitle($title);

//set page meta description and keywords
$description = $this->appSettings->meta_description;
$document->setDescription($description);
$document->setMetaData('keywords', $this->appSettings->meta_keywords);

if(!empty($params) && $params->get('menu-meta_description') != ''){
	$document->setMetaData( 'description', $params->get('menu-meta_description') );
	$document->setMetaData( 'keywords', $params->get('menu-meta_keywords') );
}

$uri     = JURI::getInstance();
$url = $uri->toString( array('scheme', 'host', 'port', 'path'));

$user = JFactory::getUser();
if($user->id == 0){
	$app = JFactory::getApplication();
	$return = base64_encode('index.php?option=com_jbusinessdirectory&view=useroptions');
	$app->redirect(JRoute::_('index.php?option=com_users&return='.$return,false));
}

$appSettings =  JBusinessUtil::getInstance()->getApplicationSettings();
$enablePackages = $appSettings->enable_packages;
$enableOffers = $appSettings->enable_offers;
$hasBusiness = isset($this->companies) && count($this->companies)>0;
?>

<style>
#content-wrapper{
	margin: 20px;
	padding: 0px;
}
</style>


<div id="user-options">
	<?php if($this->actions->get('directory.access.controlpanel') || !$appSettings->front_end_acl){ ?>
		<div class="row-fluid">
			<?php if($this->actions->get('directory.access.listings')|| !$appSettings->front_end_acl){?>
				<div class="span4 user-option-box">
					<div class="ibox" onclick="openLink('<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanies') ?>')">
						<div class="ibox-title">
							<div class="stats-icon pull-right">
								<img alt="<?php echo JTEXT::_("LNG_ADD_MODIFY_COMPANY_DATA") ?>" src="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName().'/assets/images/business-listings.png' ?>" />
							</div>
							<h5 class="clear"><?php echo JTEXT::_("LNG_ADD_MODIFY_COMPANY_DATA") ?></h5>
							<p class="small"> <?php echo JTEXT::_("LNG_ADD_MODIFY_COMPANY_DATA_INFO") ?></p>
						</div>
						<div class="ibox-content">
							<div class="row-fluid">
                                <div style="" class="span6">
                                    <small class="stats-label"><?php echo JText::_("LNG_BUSINESS_LISTINGS")?></small>
                                    <h4 class="text-success"><?php echo $this->statistics->totalListings ?></h4>
                                </div>

                                <div style="" class="span6">
                                    <small class="stats-label"><?php echo JText::_("LNG_VIEW_NUMBER")?></small>
                                    <h4><?php echo $this->statistics->listingsTotalViews?></h4>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			<?php }?>
			
			<?php if($enableOffers && ($this->actions->get('directory.access.offers')|| !$appSettings->front_end_acl)){?>
				<div class="span4 user-option-box">
					<div class="ibox" onclick="openLink('<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanyoffers') ?>')">
						<div class="ibox-title">
							<div class="stats-icon pull-right">
								<img alt="<?php echo JTEXT::_("LNG_ADD_MODIFY_OFFERS") ?>" src="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName().'/assets/images/special-offer.png' ?>" />
							</div>
							<h5 class="clear"><?php echo JTEXT::_("LNG_ADD_MODIFY_OFFERS") ?></h5>
							<p class="small"> <?php echo JTEXT::_("LNG_ADD_MODIFY_OFFERS_INFO") ?></p>
						</div>
						<div class="ibox-content">
							<div class="row-fluid">
                                <div style="" class="span6">
                                    <small class="stats-label"><?php echo JText::_("LNG_OFFERS")?></small>
                                    <h4 class="text-success"><?php echo $this->statistics->totalOffers ?></h4>
                                </div>

                                <div style="" class="span6">
                                    <small class="stats-label"><?php echo JText::_("LNG_VIEW_NUMBER")?></small>
                                    <h4><?php echo $this->statistics->offersTotalViews?></h4>
                                </div>
                            </div>
						</div>
					</div>
				</div>

				<!-- <div class="span4">
					<div class="ibox" onclick="openLink('<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanyoffercoupons') ?>')">
						<div class="ibox-title">
							<div class="stats-icon pull-right">
								<img alt="<?php echo JTEXT::_("LNG_ADD_MODIFY_COUPONS") ?>" src="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName().'/assets/images/coupons.png' ?>" />
							</div>
							<h5 class="clear"><?php echo JTEXT::_("LNG_ADD_MODIFY_COUPONS") ?></h5>
							<p class="small"> <?php echo JTEXT::_("LNG_ADD_MODIFY_COUPONS_INFO") ?></p>
						</div>
						<div class="ibox-content">
							<div class="row-fluid">
                                <div style="" class="span6">
                                    <small class="stats-label"><?php echo JText::_("LNG_COUPONS")?></small>
                                   <h4 class="text-success">1</h4>
                                </div>

                                <div style="" class="span6">
                                    <small class="stats-label"><?php echo JText::_("LNG_VIEW_NUMBER")?></small>
                                    <h4>0</h4>
                                </div>
                            </div>
						</div>
					</div>
				</div> -->
			<?php } ?>
			
			<?php if($appSettings->enable_events && ($this->actions->get('directory.access.events')|| !$appSettings->front_end_acl)){?>		
				<div class="span4 user-option-box">
					<div class="ibox" onclick="openLink('<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanyevents') ?>')">
						<div class="ibox-title">
							<div class="stats-icon pull-right">
								<img alt="<?php echo JTEXT::_("LNG_MANAGE_YOUR_EVENTS") ?>" src="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName().'/assets/images/events.png' ?>" />	
							</div>
							<h5 class="clear"><?php echo JTEXT::_("LNG_MANAGE_YOUR_EVENTS") ?></h5>
							<p class="small"> <?php echo JTEXT::_("LNG_EVENTS_INFO") ?></p>
						</div>
						<div class="ibox-content">
							<div class="row-fluid">
                                <div style="" class="span6">
                                    <small class="stats-label"><?php echo JText::_("LNG_EVENTS")?></small>
                                    <h4 class="text-success"><?php echo $this->statistics->totalEvents; ?></h4>
                                </div>
                                <div style="" class="span6">
                                    <small class="stats-label"><?php echo JText::_("LNG_VIEW_NUMBER")?></small>
                                    <h4><?php echo $this->statistics->eventsTotalViews; ?></h4>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>

		<div class="row-fluid">
			<?php if($appSettings->enable_packages) { ?>
				<div class="span4 user-option-box">
					<div class="ibox" onclick="openLink('<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=orders') ?>')">
						<div class="ibox-title">
							<div class="stats-icon pull-right">
								<img alt="<?php echo JTEXT::_("LNG_MANAGE_YOUR_ORDERS") ?>" src="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName().'/assets/images/orders.png' ?>" />
							</div>
							<h5 class="clear"><?php echo JTEXT::_("LNG_MANAGE_YOUR_ORDERS") ?></h5>
							<p class="small"> <?php echo JTEXT::_("LNG_ORDERS_INFO") ?></p>
						</div>
						<div class="ibox-content">
						
						</div>
					</div>
				</div>
			<?php } ?>
			
			<?php if($appSettings->enable_bookmarks && ($this->actions->get('directory.access.bookmarks') || !$appSettings->front_end_acl)) { ?>
				<div class="span4 user-option-box">
					<div class="ibox" onclick="openLink('<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=managebookmarks') ?>')">
						<div class="ibox-title">
							<div class="stats-icon pull-right">
								<img alt="<?php echo JTEXT::_("LNG_MANAGE_YOUR_BOOKMARKS") ?>" src="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName().'/assets/images/bookmark.png' ?>" />
							</div>
							<h5 class="clear"><?php echo JTEXT::_("LNG_MANAGE_YOUR_BOOKMARKS") ?></h5>
							<p class="small"> <?php echo JTEXT::_("LNG_BOOKMARKS_INFO") ?></p>
						</div>
						<div class="ibox-content">
							
						</div>
					</div>
				</div>
			<?php } ?>
			
			<?php if($appSettings->enable_packages){?>
				<div class="span4 user-option-box">
					<div class="ibox" onclick="openLink('<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=billingdetails&layout=edit') ?>')">
						<div class="ibox-title">
							<div class="stats-icon pull-right">
								<img alt="<?php echo JTEXT::_("LNG_BILLING_DETAILS") ?>" src="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName().'/assets/images/user.png' ?>" />	
							</div>
							<h5 class="clear"><?php echo JTEXT::_("LNG_BILLING_DETAILS") ?></h5>
							<p class="small"> <?php echo JTEXT::_("LNG_BILLING_DETAILS_INFO") ?></p>
						</div>
						<div class="ibox-content">
						
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	<?php } else {
			echo JText::_("LNG_NOT_AUTHORIZED");
		}
	?>
</div>


<script>
function openLink(link){
	document.location.href=link;
}
</script>
