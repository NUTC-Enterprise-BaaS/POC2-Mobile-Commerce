<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');
?>

<div class="offer-categories-slider-wrapper<?php echo $moduleclass_sfx; ?>" >
    <div class="offer-categories-slider responsive slider">
        <?php if(!empty($categories)) { ?>
            <?php foreach($categories as $category) {
                if(!is_array($category) || $category[0]->published==0)
                    continue; ?>
                <div class="categories-slider-item">
                    <a href="<?php echo JBusinessUtil::getOfferCategoryLink($category[0]->id, $category[0]->alias) ?>">
                        <?php
                        if(isset($category[0]->imageLocation) && $category[0]->imageLocation!='')
                            $image = JURI::root().PICTURES_PATH.$category[0]->imageLocation;
                        else
                            $image = JURI::root().PICTURES_PATH.'/no_image.jpg';
                        ?>
                        <div class="categories-slide-image"
                             style="background: url(<?php echo $image; ?>); background-repeat: no-repeat; background-position: center center; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;">
                        </div>
                        <p><?php echo $category[0]->name; ?></p>
                    </a>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<script>
    jQuery(document).ready(function(){
        jQuery('.offer-categories-slider').slick({
            dots: false,
            prevArrow: '<a class="controller-prev" href="javascript:;"><span><i class="dir-icon-angle-left"></i></span></a>',
            nextArrow: '<a class="controller-next" href="javascript:;"><span><i class="dir-icon-angle-right"></i></span></a>',
            customPaging: function(slider, i) {
                return '<a class="controller-dot" href="javascript:;"><span><i class="dir-icon-circle"></i></span></a>';
            },
            autoplay: true,
            speed: 300,
            slidesToShow: 8,
            slidesToScroll: 1,
            infinite: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: false
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }]
        });
    });
</script>