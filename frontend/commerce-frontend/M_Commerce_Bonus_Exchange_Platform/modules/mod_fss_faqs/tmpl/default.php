<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
FSS_Helper::ModuleStart("mod_fss_faqs");
?>


<?php if ($maxheight > 0): ?>
<script>

jQuery(document).ready(function () {
	setTimeout("faqsmod_scrollDown()",3000);
});

function faqsmod_scrollDown()
{
	var settings = { 
		direction: "down", 
		step: 40, 
		scroll: true, 
		onEdge: function (edge) { 
			if (edge.y == "bottom")
			{
				setTimeout("faqsmod_scrollUp()",3000);
			}
		} 
	};
	jQuery(".fss_mod_faqs_scroll").autoscroll(settings);
}

function faqsmod_scrollUp()
{
	var settings = { 
		direction: "up", 
		step: 40, 
		scroll: true,    
		onEdge: function (edge) { 
			if (edge.y == "top")
			{
				setTimeout("faqsmod_scrollDown()",3000);
			}
		} 
	};
	jQuery(".fss_mod_faqs_scroll").autoscroll(settings);
}
</script>

<style>
.fss_mod_faqs_scroll {
	max-height: <?php echo $maxheight; ?>px;
	overflow: hidden;
}
</style>


<?php endif; ?>

<?php
$class = "";
$style = "";
$curpage = 1;
$no = 0;

$uid = mt_rand(100000,999999);
?>

<div class="fss_mod_<?php echo $uid; ?>">

	<div id="fss_mod_faqs_scroll" class="fss_mod_faqs_scroll">
		<?php if ($mode == "" || $mode == "newpage"): ?>
			<?php foreach ($data as $row) :?>
				<?php 
						if ($per_page > 0)
						{
							$no++;
							if ($no > $per_page)
							{
								$curpage++;
								$style = "display: none;";	
								$no = 1;
							}
					
							$class = "art_page_" . $curpage;
						} 
				?>
				<div class='fss_mod_faqs_cont <?php echo $class; ?>' style='<?php echo $style; ?>'>
					<div class='fss_mod_faqs_title'>
						<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=faq&faqid=' . $row->id); ?>'>
							<?php echo $row->question; ?>
						</a>
					</div>
				</div>
			<?php endforeach;?>
	
		<?php elseif ($mode == "popup") : ?>
			<?php foreach ($data as $row) :?>
				<?php 
					if ($per_page > 0)
					{
						$no++;
						if ($no > $per_page)
						{
							$curpage++;
							$style = "display: none;";	
							$no = 1;
						}
				
						$class = "art_page_" . $curpage;
					} 
				?>
				<div class='fss_mod_faqs_cont <?php echo $class; ?>' style='<?php echo $style; ?>'>
					<div class='fss_mod_faqs_title'>
						<a class="show_modal_iframe" href='<?php echo FSSRoute::_('index.php?option=com_fss&view=faq&tmpl=component&faqid=' . $row->id); ?>' data_modal_width="<?php echo FSS_Settings::get('faq_popup_width'); ?>">
							<?php echo $row->question; ?>
						</a>
					</div>
				</div>
			<?php endforeach;?>

		<?php elseif ($mode == "accordion") : ?>
			<?php foreach ($data as $row) :?>
				<?php 
					if ($per_page > 0)
					{
						$no++;
						if ($no > $per_page)
						{
							$curpage++;
							$style = "display: none;";	
							$no = 1;
						}
				
						$class = "art_page_" . $curpage;
					} 
				?>
				<?php $rid = mt_rand(10000,99999); ?>
				<div class='fss_mod_faqs_cont <?php echo $class; ?>' style='<?php echo $style; ?>'>
					<div class='fss_mod_faqs_title' data-toggle="collapse" data-target="#mod_faq_<?php echo $rid; ?>">
						<a href="#" onclick='return false;'>
							<?php echo $row->question; ?>
						</a>
					</div>
					<div class="fss_mod_faqs_answer collapse" id="mod_faq_<?php echo $rid; ?>">
						<?php echo $row->answer; ?>
					</div>
				</div>
			<?php endforeach;?>

		<?php endif; ?>

	</div>

	<?php if ($curpage > 1): ?>
		<div class="pagination">
			<ul>
				<li class="disabled page_prev"><a href="#" onclick="fss_mod_page('<?php echo $uid; ?>', 'p');return false;">&laquo;</a></li>
				<?php for ($i = 1 ; $i <= $curpage ; $i++): ?>
					<li class='page_<?php echo $i; ?> <?php if ($i == 1) echo "active"; ?>'><a href="#" onclick="fss_mod_page('<?php echo $uid; ?>', '<?php echo $i; ?>');return false;"><?php echo $i; ?></a></li>
				<?php endfor; ?>
				<li class='page_next'><a href="#" onclick="fss_mod_page('<?php echo $uid; ?>', 'n');return false;">&raquo;</a></li>
			</ul>
		</div>
		<div class='cur_page' style="display: none;">1</div>
		<div class='page_max' style="display: none;"><?php echo $curpage; ?></div>
	<?php endif; ?>
	
</div>
	
<?php FSS_Helper::ModuleEnd(); ?>