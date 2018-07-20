<?php
/*------------------------------------------------------------------------
# category.html.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2012 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class HTML_OSappscheduleCategory{
	/**
	 * List Categories
	 *
	 * @param unknown_type $categories
	 */
	function listCategories($categories,$params,$list_type){
		global $mainframe,$configClass;
		//print_r($params);
		if($params->get('show_page_heading') == 1){
			if($params->get('page_heading') != ""){
				?>
				<div class="page-header">
					<h1>
						<?php echo $params->get('page_heading');?>
					</h1>
				</div>
				<?php
			}else{
				?>
				<div class="page-header">
					<h1>
						<?php echo JText::_('OS_LIST_ALL_CATEGORIES');?>
					</h1>
				</div>
				<?php
			}
		}
		if(count($categories) > 0){
			if($list_type == 0){
				foreach ($categories as $category){
				?>
				<div class="row-fluid">
					<div class="span12">
						<div class="row-fluid">
							<div class="span4">
								<div id="ospitem-watermark_box">
                                    <a href="<?php echo JText::_('index.php?option=com_osservicesbooking&task=default_layout&category_id='.$category->id.'&Itemid='.JRequest::getInt('Itemid',0))?>" title="<?php echo JText::_('OS_CATEGORY_DETAILS');?>">
                                        <?php
                                        if($category->category_photo != ""){
                                            ?>
                                            <img src="<?php echo JURI::root()?>images/osservicesbooking/category/<?php echo $category->category_photo?>"/>
                                            <?php
                                        }else{
                                            ?>
                                            <img src="<?php echo JURI::root()?>components/com_osservicesbooking/asset/images/no_image_available.png"/>
                                            <?php
                                        }
                                        ?>
                                    </a>
								</div>
							</div>
							<div class="span8 ospitem-leftpad">
								<div class="ospitem-leftpad">
									<div class="row-fluid ospitem-toppad">
										<div class="span12">
											<span class="ospitem-itemtitle title-blue">
												<a href="<?php echo JText::_('index.php?option=com_osservicesbooking&task=default_layout&category_id='.$category->id.'&Itemid='.JRequest::getInt('Itemid',0))?>" title="<?php echo JText::_('OS_CATEGORY_DETAILS');?>">
													<?php
													echo OSBHelper::getLanguageFieldValue($category,'category_name');
													?>
												</a>
											</span>
										</div>
									</div>
									<?php
									if($category->show_desc == 1){
									?>
									<div class="row-fluid ospitem-toppad">
										<div class="span12">
											<span>
												<?php HelperOSappscheduleCommon::showDescription(OSBHelper::getLanguageFieldValue($category,'category_description'));?>
											</span>
										</div>
									</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
				}
			}else{ //grid view
				?>
				<div id="mainwrapper" class="row-fluid">
                    <div class="span12">
                        <?php
                        $j = 0;
                        foreach ($categories as $category){
                            $j++;
                            $link = Jroute::_('index.php?option=com_osservicesbooking&task=default_layout&category_id='.$category->id.'&Itemid='.JRequest::getInt('Itemid',0));
                            ?>
                            <div class="span4 information_box">
                                <div class="information_box_img">
                                    <a href="<?php echo $link; ?>" title="<?php echo JText::_('OS_CATEGORY_DETAILS');?>">
                                        <?php
                                        if($category->category_photo != ""){
                                            ?>
                                            <img src="<?php echo JURI::root()?>images/osservicesbooking/category/<?php echo $category->category_photo?>"/>
                                        <?php
                                        }else{
                                            ?>
                                            <img src="<?php echo JURI::root()?>components/com_osservicesbooking/asset/images/no_image_available.png"/>
                                        <?php
                                        }
                                        ?>
                                    </a>
                                </div>
                                <span class="full-caption">
							        <h3><a href="<?php echo $link; ?>" title="<?php echo JText::_('OS_CATEGORY_DETAILS');?>"><?php echo OSBHelper::getLanguageFieldValue($category,'category_name');?></a></h3>
                                <?php
                                if($category->show_desc == 1){
                                    ?>
                                    <div class="full-desc">
                                        <?php echo HelperOSappscheduleCommon::showDescription(strip_tags(OSBHelper::getLanguageFieldValue($category,'category_description')));?>
                                    </div>
                                <?php
                                }
                                ?>
						        </span>
                            </div>
                        <?php
                            if($j == 3){
                                $j = 0;
                                ?>
                                </div></div><div class="row-fluid"><div class="span12">
                                <?php
                            }
                        }
                        ?>
                    </div>
				</div>
				<div class="clearfix"></div>
				<?php
			}
		}else{
			?>
			<div class="row-fluid">
				<div class="span12" style="text-align:center;padding:10px;">
					<strong>
						<?php
							echo JText::_('OS_NO_CATEGORIES');
						?>
					</strong>
				</div>
			</div>
			<?php
		}
	}
}
?>