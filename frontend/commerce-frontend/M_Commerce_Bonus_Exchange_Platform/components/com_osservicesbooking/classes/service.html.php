<?php
/*------------------------------------------------------------------------
# service.html.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class HTML_OsAppscheduleService{
    public static function listServices($services,$params,$list_type){
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
                        <?php echo JText::_('OS_LIST_ALL_SERVICES');?>
                    </h1>
                </div>
            <?php
            }
        }
        if(count($services) > 0){
            if($list_type == 0){
                foreach ($services as $service){
                    $link = Jroute::_('index.php?option=com_osservicesbooking&task=default_layout&sid='.$service->id.'&Itemid='.JRequest::getInt('Itemid',0));
                    ?>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span4">
                                    <div id="ospitem-watermark_box">
                                        <a href="<?php echo $link; ?>" title="<?php echo JText::_('OS_SERVICE_DETAILS');?>">
                                            <?php
                                            if($service->service_photo != ""){
                                                ?>
                                                <img src="<?php echo JURI::root()?>images/osservicesbooking/services/<?php echo $service->service_photo?>"/>
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
												<a href="<?php echo $link; ?>" title="<?php echo JText::_('OS_SERVICE_DETAILS');?>">
                                                    <?php
                                                    echo OSBHelper::getLanguageFieldValue($service,'service_name');
                                                    ?>
                                                </a>
											</span>
                                            </div>
                                        </div>
                                        <div class="row-fluid ospitem-toppad">
                                            <div class="span12">
                                                <span>
                                                    <i class="icon-tag"></i> <?php echo HelperOSappscheduleCommon::getCategoryName($service->id); ?>
                                                    <div class="clearfix"></div>
                                                    <?php HelperOSappscheduleCommon::showDescription(OSBHelper::getLanguageFieldValue($service,'service_description'));?>
                                                </span>
                                            </div>
                                        </div>
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
                foreach ($services as $service){
                    $j++;
                    $link = Jroute::_('index.php?option=com_osservicesbooking&task=default_layout&sid='.$service->id.'&Itemid='.JRequest::getInt('Itemid',0));
                    ?>
                    <div class="span4 information_box">
                        <div class="information_box_img">
                            <a href="<?php echo $link; ?>" title="<?php echo JText::_('OS_SERVICE_DETAILS');?>">
                                <?php
                                if($service->service_photo != ""){
                                    ?>
                                    <img src="<?php echo JURI::root()?>images/osservicesbooking/services/<?php echo $service->service_photo; ?>"/>
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
                            <h3><a href="<?php echo $link; ?>" title="<?php echo JText::_('OS_SERVICE_DETAILS');?>"><?php echo OSBHelper::getLanguageFieldValue($service,'service_name');?></a></h3>
                            <div class="full-desc">
                                <i class="icon-tag"></i> <?php echo HelperOSappscheduleCommon::getCategoryName($service->id); ?>
                                <div class="clearfix"></div>
                                <?php HelperOSappscheduleCommon::showDescription(strip_tags(OSBHelper::getLanguageFieldValue($service,'service_description')));?>
                            </div>
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
                        echo JText::_('OS_NO_SERVICES');
                        ?>
                    </strong>
                </div>
            </div>
        <?php
        }
    }


    public static function listItems($services,$categories,$employees,$show_category,$show_service,$show_employee,$params){

        if($params->get('show_page_heading') == 1){
            if($params->get('page_heading') != "") {
                ?>
                <div class="page-header">
                    <h1>
                        <?php echo $params->get('page_heading');?>
                    </h1>
                </div>
            <?php
            }
        }

        if(($show_category == 1) AND (count($categories) > 0)){
            ?>
            <div class="sub-page-header">
                <h2>
                    <?php echo JText::_('OS_LIST_ALL_CATEGORIES');?>
                </h2>
            </div>
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
                                    <?php HelperOSappscheduleCommon::showDescription(strip_tags(OSBHelper::getLanguageFieldValue($category,'category_description')));?>
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

        if(($show_service == 1) AND (count($services) > 0)){
            ?>
            <div class="sub-page-header">
                <h2>
                    <?php echo JText::_('OS_LIST_ALL_SERVICES');?>
                </h2>
            </div>
            <div id="mainwrapper" class="row-fluid">
                <div class="span12">
                <?php
                $j = 0;
                foreach ($services as $service){
                    $j++;
                    $link = Jroute::_('index.php?option=com_osservicesbooking&task=default_layout&sid='.$service->id.'&Itemid='.JRequest::getInt('Itemid',0));
                    ?>
                    <div class="span4 information_box">
                        <div class="information_box_img">
                            <a href="<?php echo $link; ?>" title="<?php echo JText::_('OS_SERVICE_DETAILS');?>">
                                <?php
                                if($service->service_photo != ""){
                                    ?>
                                    <img src="<?php echo JURI::root()?>images/osservicesbooking/services/<?php echo $service->service_photo; ?>"/>
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
                            <h3><a href="<?php echo $link; ?>" title="<?php echo JText::_('OS_SERVICE_DETAILS');?>"><?php echo OSBHelper::getLanguageFieldValue($service,'service_name');?></a></h3>
                            <div class="full-desc">
                                <i class="icon-tag"></i> <?php echo HelperOSappscheduleCommon::getCategoryName($service->id); ?>
                                <div class="clearfix"></div>
                                <?php echo HelperOSappscheduleCommon::showDescription(strip_tags(OSBHelper::getLanguageFieldValue($service,'service_description')));?>
                            </div>
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

        if(($show_employee == 1) AND (count($employees) > 0)){
            ?>
            <div class="sub-page-header">
                <h2>
                    <?php echo JText::_('OS_LIST_ALL_EMPLOYEES');?>
                </h2>
            </div>
            <div id="mainwrapper" class="row-fluid">
                <div class="span12">
                <?php
                $j = 0;
                foreach ($employees as $employee){
                    $j++;
                    $link = Jroute::_('index.php?option=com_osservicesbooking&task=default_layout&employee_id='.$employee->id.'&Itemid='.JRequest::getInt('Itemid',0));
                    ?>
                    <div class="span4 information_box">
                        <div class="information_box_img">
                            <a href="<?php echo $link; ?>" title="<?php echo JText::_('OS_DETAILS');?>">
                                <?php
                                if ($employee->employee_photo != "") {
                                    ?>
                                    <img src="<?php echo JURI::root()?>images/osservicesbooking/employee/<?php echo $employee->employee_photo?>"/>
                                <?php
                                } else {
                                    ?>
                                    <img src="<?php echo JURI::root()?>components/com_osservicesbooking/asset/images/no_image_available.png"/>
                                <?php
                                }
                                ?>
                            </a>
                        </div>
                        <span class="full-caption">
                            <h3><a href="<?php echo $link; ?>" title="<?php echo JText::_('OS_DETAILS');?>"><?php echo $employee->employee_name;?></a></h3>
                            <div class="full-desc">
                                <i class="icon-tag"></i> <?php echo HelperOSappscheduleCommon::getServiceNames($employee->id); ?>
                                <?php
                                echo '<div class="clearfix"></div>';
                                if ($employee->employee_phone != "") {
                                    echo "<i class='icon-phone'></i>&nbsp;".$employee->employee_phone;
                                    echo '<div class="clearfix"></div>';
                                }
                                if ($employee->employee_email != "") {
                                    echo "<i class='icon-mail'></i>&nbsp;<a href='mailto:" . $employee->employee_email . "'>" . $employee->employee_email . "</a>";
                                    echo '<div class="clearfix"></div>';
                                }
                                if ($employee->employee_notes != "") {
                                    echo $employee->employee_notes;
                                }
                                ?>
                            </div>
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
    }
}
?>