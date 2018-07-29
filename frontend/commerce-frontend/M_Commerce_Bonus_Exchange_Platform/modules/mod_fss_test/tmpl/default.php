<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
FSS_Helper::ModuleStart("mod_fss_test");

$ident = mt_rand(1,1000);
?>

<?php if ($maxheight > 0): ?>

<?php if ($loop_scroll): ?>
	
<script>
(function(jQuery, undefined) {
  jQuery.fn.loopScroll = function(p_options) {
	var options = jQuery.extend({
        direction: "upwards",
        speed: <?php echo $speed; ?>
    }, p_options);

    return this.each(function() {
      var obj = jQuery(this).find(".loop_container");
	  var text_height = obj.find(".loop_text").height();
      var start_y, end_y;
      if (options.direction == "downwards") {
        start_y = -text_height;
        end_y = 0;
      } else if (options.direction == "upwards") {
        start_y = 0;
        end_y = -text_height;
      }

      var animate = function() {
        // setup animation of specified block "obj"
        // calculate distance of animation    
        var distance = Math.abs(end_y - parseInt(obj.css("top")));
 
        //duration will be distance / speed
        obj.animate(
          { top: end_y },  //scroll upwards
          1000 * distance / options.speed,
          "linear",
          function() {
              // scroll to start position
              obj.css("top", start_y);
              animate();    
          }
        );
      };

      obj.find(".loop_text").clone().appendTo(obj);
      jQuery(this).on("mouseover", function() {
        obj.stop();
      }).on("mouseout", function() {
        animate(); // resume animation
      });
      obj.css("top", start_y);
      animate(); // start animation
        
    });
  };
}(jQuery));
	
jQuery(document).ready(function () {

	// check if the contents are larger than max height, if so scroll
	var text_height = jQuery("#fss_comments_scroll_outer_<?php echo $ident; ?>").find(".loop_text").height();
	
	if (text_height < <?php echo $maxheight; ?>)
	{
		jQuery("#fss_comments_scroll_outer_<?php echo $ident; ?>").css('height', text_height + 'px');
	} else {
		jQuery("#fss_comments_scroll_outer_<?php echo $ident; ?>").css('height', <?php echo $maxheight; ?> + 'px');
		setTimeout("jQuery('#fss_comments_scroll_outer_<?php echo $ident; ?>').loopScroll();",3000);
	}	
});

</script>

<style>
#fss_comments_scroll_outer_<?php echo $ident; ?> {
	max-height: <?php echo $maxheight; ?>px;
	overflow: hidden;
	position: relative;
}

#fss_comments_scroll_<?php echo $ident; ?> {
	position: absolute;  
	left: 0px;
	top: 0px;
}
</style>


<?php else: ?>
	
<script>
jQuery(document).ready(function () {
	setTimeout("scrollDown()",3000);
});

function scrollDown()
{
	var settings = { 
		direction: "down", 
		step: <?php echo $speed; ?>, 
		scroll: true, 
		onEdge: function (edge) { 
			if (edge.y == "bottom")
			{
				setTimeout("scrollUp()",3000);
			}
		} 
	};
	jQuery(".fss_comments_scroll_inner").autoscroll(settings);
}

function scrollUp()
{
	var settings = { 
		direction: "up", 
		step: <?php echo $speed; ?>, 
		scroll: true,    
		onEdge: function (edge) { 
			if (edge.y == "top")
			{
				setTimeout("scrollDown()",3000);
			}
		} 
	};
	jQuery(".fss_comments_scroll_inner").autoscroll(settings);
}
</script>

<style>
#fss_comments_scroll_inner_<?php echo $ident; ?> {
	max-height: <?php echo $maxheight; ?>px;
	overflow: hidden;
}
</style>

<?php endif; ?>

<?php endif; ?>
<?php if (1/*count($rows) > 0*/) : ?>
<div id="fss_comments_scroll_outer_<?php echo $ident; ?>" class="fss_comments_scroll_outer">
<div id="fss_comments_scroll_<?php echo $ident; ?>" class="fss_comments_scroll loop_container">
<div id="fss_comments_scroll_inner_<?php echo $ident; ?>" class="fss_comments_scroll_inner loop_text">
<?php $comments->DisplayComments($dispcount, $listtype, $maxlength); ?>
</div>
</div>
</div>
<?php if ($params->get('show_more')) : ?>
	<?php if ($params->get('morelink')): ?>
		<div class='fss_mod_test_all'><a href='<?php echo JRoute::_( $params->get('morelink') ); ?>'><?php echo JText::_("SHOW_MORE_TESTIMONIALS"); ?></a></div>
	<?php elseif ($prodid == -1): ?>
		<div class='fss_mod_test_all'><a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=test' ); ?>'><?php echo JText::_("SHOW_MORE_TESTIMONIALS"); ?></a></div>
	<?php else : ?>
		<div class='fss_mod_test_all'><a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=test&prodid=' . $prodid ); ?>'><?php echo JText::_("SHOW_MORE_TESTIMONIALS"); ?></a></div>
	<?php endif; ?>
<?php endif; ?>
<?php else: ?>
No testimonials found!.
<?php endif; ?>
<?php if ($params->get('show_add') && $comments->can_add): ?>
	<?php if ($params->get('addlink')) :?>
		<div class='fss_mod_test_add'><a href='<?php echo JRoute::_( $params->get('addlink') ); ?>'><?php echo JText::_("ADD_A_TESTIMONIAL"); ?></a></div>
	<?php else: ?>
		<div class='fss_mod_test_add'><a class='show_modal_iframe' data_modal_width="650" href='<?php echo FSSRoute::_( 'index.php?tmpl=component&option=com_fss&view=test&layout=create&onlyprodid=' . $prodid ); ?>'><?php echo JText::_("ADD_A_TESTIMONIAL"); ?></a></div>
	<?php endif; ?>
<?php endif; ?>

<?php FSS_Helper::ModuleEnd(); ?>