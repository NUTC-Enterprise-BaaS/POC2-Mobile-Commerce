<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/imagesloaded.pkgd.min.js');
JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/jquery.isotope.min.js');
JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/isotope.init.js');
JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/isotope.init.js');
JHTML::_('stylesheet',  'components/com_jbusinessdirectory/assets/css/ultimate.min.css');
?>

<?php if (!empty($this->params) && $this->params->get('show_page_heading', 1)) { ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
<?php } ?>


<!-- layout -->
<div id="layout" class="pagewidth clearfix grid4 categories-grid" >

        <?php $k = 0; ?>
        <?php foreach($this->categories as $category){
        if(isset($category[0]->name)){
            if($k % 4 == 0 ) {
                echo '<div class="row">';
            }
                $k++;
        ?>

        <div id="post-<?php echo  $category[0]->id ?>" class="col-sm-3">
            <div class="wpb_wrapper">
                <div class="aio-icon-component style_2">
                    <a class="aio-icon-box-link" style="text-decoration: none;" href="<?php echo $category[0]->link ?>">
                        <div class="aio-icon-box top-icon">
                            <div class="aio-icon-top">
                                <div class="aio-icon none" style="">
                                    <div class="dir-icon-<?php echo $category[0]->icon ?>"></div>
                                </div>
                            </div>
                            <div class="aio-icon-header">
                                <h3 class="aio-icon-title">
                                    <?php echo $category[0]->name; ?>
                                </h3>
                                <?php if($this->appSettings->show_total_business_count) { ?>
                                    <h4>
                                        <span class="numberCircle"> <?php echo $category[0]->nr_listings ?></span>
                                    </h4>
                                <?php } ?>
                            </div>
                            <div class="aio-icon-description">
                                <?php echo JBusinessUtil::truncate($category[0]->description,100); ?>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

            <?php
                if($k % 4 == 0 )
                 echo '</div>';

            } ?>
        <?php } ?>
    <div class="clear"></div>
</div>