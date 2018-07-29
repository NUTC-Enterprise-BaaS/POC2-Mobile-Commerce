<ul class="nav nav-pills nav-stacked">
	<?php foreach ($this->results as $result): ?>
		<li>
			<a class="show_modal_iframe" data_modal_width="<?php echo FSS_Settings::get('faq_popup_width'); ?>" href="<?php echo $result->link; ?>">
				<span class="label"><?php echo $result->type; ?></span>
				<?php echo $result->title; ?><!-- (<?php echo $result->score; ?>)-->
			</a>
		</li>
	<?php endforeach; ?>
</ul>