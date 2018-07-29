<?php
/*------------------------------------------------------------------------
# plg_completeimagestyles - Complete Image Styles
# ------------------------------------------------------------------------
# version 2.1.4
# author Impression eStudio
# copyright Copyright (C) 2013 Impression eStudio. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://joomla.impression-estudio.gr
# Technical Support: info@impression-estudio.gr
-------------------------------------------------------------------------*/

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

class JFormFieldStyles extends JFormField 
{
	protected $type = 'Styles';

	protected function getInput()
	{
		$document = JFactory::getDocument();
		
		// Load jQuery.
		$version = new JVersion();
		if (strcmp($version->RELEASE, "2.5")==0)
		{
			$document->addScript("../plugins/system/completeimagestyles/completeimagestyles/js/jquery-2.0.3.min.js");
			$document->addScript("../plugins/system/completeimagestyles/completeimagestyles/js/no-conflict.js");
		}
		
		// Load CSS and Javascript files.
		$document->addStyleSheet('../plugins/system/completeimagestyles/completeimagestyles/css/admin.css');
		$document->addScript('../plugins/system/completeimagestyles/completeimagestyles/js/admin.js');
		$document->addScript('../plugins/system/completeimagestyles/completeimagestyles/js/imagesloaded.pkgd.min.js');
		$document->addScript('../plugins/system/completeimagestyles/completeimagestyles/js/cis.js');
		
		// Get the current value of the parameter.
		$value = $this->value;
		
		ob_start();
?>
<div class="cis-info">
	<img src="../plugins/system/completeimagestyles/completeimagestyles/images/logo.jpg" width="120" height="120" /></td>
	<strong>Name</strong>: Complete Image Styles <br />
	<strong>Version</strong>: 2.1.4 <br />
	<strong>Author</strong>: Impression eStudio <br />
	<strong>Website</strong>: <a href="http://joomla.impression-estudio.gr" target="_blank">http://joomla.impression-estudio.gr</a> <br />
	<strong>Contact</strong>: <a href="mailto:info@impression-estudio.gr">info@impression-estudio.gr</a> <br />
	<strong>Demo</strong>: <a href="http://joomla.impression-estudio.gr/demo/demos/complete-image-styles/demo-1" target="_blank">Click here</a> <br />
	<strong>Help</strong>: <a href="http://joomla.impression-estudio.gr/complete-image-styles/index.php" target="_blank">Click here</a>
</div>
<?php 
$num_of_styles = 15;
for ($i=1; $i<=$num_of_styles; $i++) { 
?>
<div id="cis-style-<?php echo $i; ?>" class="cis-style">
	<div class="cis-header">
		Style <?php echo $i; ?> <span class="cis-openclose"></span>
	</div>
	<div class="cis-field">
		Comment:
		<textarea name="comment" cols="" rows="1"></textarea>
	</div>
	<div class="cis-field">
		Enabled:
		<select name="enabled" class="cis-switch">
			<option value="1">Enabled</option>
			<option value="0" selected="selected">Disabled</option>
		</select>
	</div>
	<?php if ($i==1) { ?>
	<div class="cis-toggle">
		<div class="cis-field">
			Class (Simple User): <input name="name" type="text" value="cis-style-<?php echo $i; ?>" readonly style="cursor:text;" />
			<div class="cis-field-comment">
				Add this class to the images you want to be styled like this style.
			</div>
		</div>
		<div class="cis-field">
			CSS Selectors (Advanced User):
			<input name="css_selectors" type="text" value="" />
		</div>
		<div class="cis-field">
			Theme:
			<div class="cis-field-comment">
				If you change theme then any changes to the styles below will be lost.
			</div>
			<select name="theme">
				<option value="simple" selected="selected">Simple</option>
				<option value="crazy">Crazy</option>
				<option value="paper">Paper</option>
				<option value="polaroid">Polaroid</option>
				<option value="emersion">Emersion</option>
				<option value="reflection">Reflection</option>
				<option value="mirror">Mirror</option>
				<option value="pillow">Pillow</option>
				<option value="wall">Wall</option>
				<option value="bentcorners">Bent Corners</option>
				<option value="tape">Tape</option>
				<option value="diary">Diary</option>
				<option value="slidingcaption">Sliding Caption</option>
				<option value="pickup">Pick Up</option>
				<option value="showup">Show Up</option>
				<option value="fillit">Fill It</option>
				<option value="scuttle">Scuttle</option>
				<option value="popup">Pop Up</option>
				<option value="dashed">Dashed</option>
				<option value="tin">Tin</option>
				<option value="closeit">Close It</option>
				<option value="inclined">Inclined</option>
				<option value="paperclip">Paperclip</option>
				<option value="grayscale">Grayscale</option>
				<option value="custom">Custom</option>
			</select>
			<div class="cis-themes">
				<div class="cis-theme">
					Simple<br />
					<img class="cis-theme-simple" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Crazy<br />
					<img class="cis-theme-crazy" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Paper<br />
					<img class="cis-theme-paper" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Polaroid<br />
					<img class="cis-theme-polaroid" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Emersion<br />
					<img class="cis-theme-emersion" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Reflection<br />
					<img class="cis-theme-reflection" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Mirror<br />
					<img class="cis-theme-mirror" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Pillow<br />
					<img class="cis-theme-pillow" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Wall<br />
					<img class="cis-theme-wall" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Bent Corners<br />
					<img class="cis-theme-bentcorners" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Tape<br />
					<img class="cis-theme-tape" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Diary<br />
					<img class="cis-theme-diary" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Sliding Caption<br />
					<img class="cis-theme-slidingcaption" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Pick Up<br />
					<img class="cis-theme-pickup" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Show Up<br />
					<img class="cis-theme-showup" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Fill It<br />
					<img class="cis-theme-fillit" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Scuttle<br />
					<img class="cis-theme-scuttle" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:125px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Pop Up<br />
					<img class="cis-theme-popup" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Dashed<br />
					<img class="cis-theme-dashed" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Tin<br />
					<img class="cis-theme-tin" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Close It<br />
					<img class="cis-theme-closeit" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Inclined<br />
					<img class="cis-theme-inclined" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Paperclip<br />
					<img class="cis-theme-paperclip" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="This is the title of the image." />
				</div>
				<div class="cis-theme">
					Grayscale<br />
					<img class="cis-theme-grayscale" src="../plugins/system/completeimagestyles/completeimagestyles/images/sample.jpg" style="width:200px; height:125px;" title="Unfortunately it does not work in IE10 and IE11." />
				</div>
				<div class="cis-theme">
					Custom<br />
					<img src="../plugins/system/completeimagestyles/completeimagestyles/images/custom.jpg" style="width:200px; height:125px;" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-border">
			<div class="cis-sub-header">
				Border <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/border.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="border_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (px):
					<input name="border_size" type="text" value="2" />
				</div>
				<div class="cis-field cis-indent">
					Color (HEX: #333333):
					<input name="border_color" type="text" value="#333333" />
				</div>
				<div class="cis-field cis-indent">
					Opacity (Decimal: 0 - 1):
					<input name="border_opacity" type="text" value="1" />
				</div>
				<div class="cis-field cis-indent">
					Style:
					<select name="border_style">
						<option value="solid" selected="selected">solid</option>
						<option value="dotted">dotted</option>
						<option value="dashed">dashed</option>
						<option value="double">double</option>
					</select>
				</div>
				<div class="cis-field">
					<span class="cis-bold">Hover</span>:
					<select name="border_hover" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (px):
					<input name="border_size_hover" type="text" value="2" />
				</div>
				<div class="cis-field cis-indent">
					Color (HEX: #333333):
					<input name="border_color_hover" type="text" value="#333333" />
				</div>
				<div class="cis-field cis-indent">
					Opacity (Decimal: 0 - 1):
					<input name="border_opacity_hover" type="text" value="1" />
				</div>
				<div class="cis-field cis-indent">
					Style:
					<select name="border_style_hover">
						<option value="solid">solid</option>
						<option value="dotted" selected="selected">dotted</option>
						<option value="dashed">dashed</option>
						<option value="double">double</option>
					</select>
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-cutout">
			<div class="cis-sub-header">
				Cutout <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/cutout.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="cutout_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (px):
					<input name="cutout_size" type="text" value="5" />
				</div>
				<div class="cis-field">
					<span class="cis-bold">Hover</span>:
					<select name="cutout_hover" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (px):
					<input name="cutout_size_hover" type="text" value="10" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-curled-corners">
			<div class="cis-sub-header">
				Curled Corners <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/curled-corners.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="curled_corners_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-double-outlined">
			<div class="cis-sub-header">
				Double Outlined <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/double-outlined.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="double_outlined_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Inner Color (HEX: #FFFFFF):
					<input name="double_outlined_inner_color" type="text" value="#FFFFFF" />
				</div>
				<div class="cis-field cis-indent">
					Inner Size (px):
					<input name="double_outlined_inner_size" type="text" value="5" />
				</div>
				<div class="cis-field cis-indent">
					Inner Opacity (Decimal: 0 - 1):
					<input name="double_outlined_inner_opacity" type="text" value="1" />
				</div>
				<div class="cis-field cis-indent">
					Outer Color (HEX: #FFFFFF):
					<input name="double_outlined_outer_color" type="text" value="#000000" />
				</div>
				<div class="cis-field cis-indent">
					Outer Size (px):
					<input name="double_outlined_outer_size" type="text" value="2" />
				</div>
				<div class="cis-field cis-indent">
					Outer Opacity (Decimal: 0 - 1):
					<input name="double_outlined_outer_opacity" type="text" value="1" />
				</div>
				<div class="cis-field">
					<span class="cis-bold">Hover</span>:
					<select name="double_outlined_hover" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Inner Color (HEX: #FFFFFF):
					<input name="double_outlined_inner_color_hover" type="text" value="#FFFFFF" />
				</div>
				<div class="cis-field cis-indent">
					Inner Size (px):
					<input name="double_outlined_inner_size_hover" type="text" value="5" />
				</div>
				<div class="cis-field cis-indent">
					Inner Opacity (Decimal: 0 - 1):
					<input name="double_outlined_inner_opacity_hover" type="text" value="1" />
				</div>
				<div class="cis-field cis-indent">
					Outer Color (HEX: #FFFFFF):
					<input name="double_outlined_outer_color_hover" type="text" value="#000000" />
				</div>
				<div class="cis-field cis-indent">
					Outer Size (px):
					<input name="double_outlined_outer_size_hover" type="text" value="20" />
				</div>
				<div class="cis-field cis-indent">
					Outer Opacity (Decimal: 0 - 1):
					<input name="double_outlined_outer_opacity_hover" type="text" value="0" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-embossed">
			<div class="cis-sub-header">
				Embossed <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/embossed.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="embossed_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (px):
					<input name="embossed_size" type="text" value="5" />
				</div>
				<div class="cis-field">
					<span class="cis-bold">Hover</span>:
					<select name="embossed_hover" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (px):
					<input name="embossed_size_hover" type="text" value="10" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-external-caption">
			<div class="cis-sub-header">
				External Caption <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<div class="cis-comment">
					You also need to set the 'title' attribute of the image.
				</div>
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/external-caption.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="external_caption_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Background Color (HEX: #F6F6F6):
					<input name="external_caption_background_color" type="text" value="#F6F6F6" />
				</div>
				<div class="cis-field cis-indent">
					Font Color (HEX: #333333):
					<input name="external_caption_font_color" type="text" value="#333333" />
				</div>
				<div class="cis-field cis-indent">
					Font Size (px):
					<input name="external_caption_font_size" type="text" value="12" />
				</div>
				<div class="cis-field cis-indent">
					Space (px):
					<input name="external_caption_space" type="text" value="10" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-float">
			<div class="cis-sub-header">
				Float <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/float.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="float_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Direction:
					<select name="float_direction">
						<option value="left" selected="selected">Left</option>
						<option value="right">Right</option>
					</select>
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-glowing">
			<div class="cis-sub-header">
				Glowing <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/glowing.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="glowing_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (px):
					<input name="glowing_size" type="text" value="20" />
				</div>
				<div class="cis-field">
					<span class="cis-bold">Hover</span>:
					<select name="glowing_hover" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (px):
					<input name="glowing_size_hover" type="text" value="30" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-grayscale">
			<div class="cis-sub-header">
				Grayscale <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<div class="cis-comment">
					Unfortunately it does not work in IE10 and IE11.
				</div>		
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/grayscale.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="grayscale_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field">
					<span class="cis-bold">Hover</span>:
					<select name="grayscale_hover" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-horizontal-curve">
			<div class="cis-sub-header">
				Horizontal Curve <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/horizontal-curve.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="horizontal_curve_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (px):
					<input name="horizontal_curve_size" type="text" value="15" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-internal-caption">
			<div class="cis-sub-header">
				Internal Caption <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<div class="cis-comment">
					You also need to set the 'title' attribute of the image.
				</div>
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/internal-caption.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="internal_caption_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Background Color (HEX: #F6F6F6):
					<input name="internal_caption_background_color" type="text" value="#000000" />
				</div>
				<div class="cis-field cis-indent">
					Font Color (HEX: #FFFFFF):
					<input name="internal_caption_font_color" type="text" value="#FFFFFF" />
				</div>
				<div class="cis-field cis-indent">
					Font Size (px):
					<input name="internal_caption_font_size" type="text" value="10" />
				</div>
				<div class="cis-field cis-indent">
					Position:
					<select name="internal_caption_position">
						<option value="bottom" selected="selected">Bottom</option>
						<option value="top">Top</option>
					</select>
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-lifted-corners">
			<div class="cis-sub-header">
				Lifted Corners <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/lifted-corners.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="lifted_corners_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-margin">
			<div class="cis-sub-header">
				Margin <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<div class="cis-comment">
					This will affect the position of the image if the image is centered.
				</div>
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/margin.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="margin_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Top Size (px):
					<input name="margin_top" type="text" value="10" />
				</div>
				<div class="cis-field cis-indent">
					Right Size (px):
					<input name="margin_right" type="text" value="10" />
				</div>
				<div class="cis-field cis-indent">
					Bottom Size (px):
					<input name="margin_bottom" type="text" value="10" />
				</div>
				<div class="cis-field cis-indent">
					Left Size (px):
					<input name="margin_left" type="text" value="10" />
				</div>
				<div class="cis-field">
					<span class="cis-bold">Hover</span>:
					<select name="margin_hover" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Top Size (px):
					<input name="margin_top_hover" type="text" value="20" />
				</div>
				<div class="cis-field cis-indent">
					Right Size (px):
					<input name="margin_right_hover" type="text" value="20" />
				</div>
				<div class="cis-field cis-indent">
					Bottom Size (px):
					<input name="margin_bottom_hover" type="text" value="20" />
				</div>
				<div class="cis-field cis-indent">
					Left Size (px):
					<input name="margin_left_hover" type="text" value="20" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-opacity">
			<div class="cis-sub-header">
				Opacity <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/opacity.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="opacity_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (Decimal: 0 - 1):
					<input name="opacity_size" type="text" value="0.7" />
				</div>
				<div class="cis-field">
					<span class="cis-bold">Hover</span>:
					<select name="opacity_hover" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (Decimal: 0 - 1):
					<input name="opacity_size_hover" type="text" value="1" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-perspective">
			<div class="cis-sub-header">
				Perspective <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/perspective.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="perspective_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Direction:
					<select name="perspective_direction">
						<option value="right" selected="selected">Right</option>
						<option value="left">Left</option>
					</select>
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-pop">
			<div class="cis-sub-header">
				Pop <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/pop.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Hover</span>:
					<select name="pop_hover" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (px):
					<input name="pop_size_hover" type="text" value="8" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-raised-box">
			<div class="cis-sub-header">
				Raised Box <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/raised-box.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="raised_box_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (px):
					<input name="raised_box_size" type="text" value="15" />
				</div>
				<div class="cis-field">
					<span class="cis-bold">Hover</span>:
					<select name="raised_box_hover" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (px):
					<input name="raised_box_size_hover" type="text" value="25" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-reflection">
			<div class="cis-sub-header">
				Reflection <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/reflection.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="reflection_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (px):
					<input name="reflection_size" type="text" value="30" />
				</div>
				<div class="cis-field cis-indent">
					Color (HEX: #FFFFFF):
					<input name="reflection_color" type="text" value="#FFFFFF" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-rotation">
			<div class="cis-sub-header">
				Rotation <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/rotation.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="rotation_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (Degrees):
					<input name="rotation_size" type="text" value="-3" />
				</div>
				<div class="cis-field">
					<span class="cis-bold">Hover</span>:
					<select name="rotation_hover" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (Degrees):
					<input name="rotation_size_hover" type="text" value="3" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-rounded-corners">
			<div class="cis-sub-header">
				Rounded Corners <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/rounded-corners.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="rounded_corners_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (px):
					<input name="rounded_corners_size" type="text" value="20" />
				</div>
				<div class="cis-field">
					<span class="cis-bold">Hover</span>:
					<select name="rounded_corners_hover" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (px):
					<input name="rounded_corners_size_hover" type="text" value="30" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-round-image">
			<div class="cis-sub-header">
				Round Image <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/round-image.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="round_image_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field">
					<span class="cis-bold">Hover</span>:
					<select name="round_image_hover" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-scale">
			<div class="cis-sub-header">
				Scale <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/scale.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="scale_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (Decimal):
					<div class="cis-field-comment">
						1 is like 100% of the actual size. 0.5 is like 50% smaller. 1.5 is like 50% bigger.
					</div>
					<input name="scale_size" type="text" value="1" />
				</div>
				<div class="cis-field">
					<span class="cis-bold">Hover</span>:
					<select name="scale_hover" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (Decimal):
					<div class="cis-field-comment">
						1 is like 100% of the actual size. 0.5 is like 50% smaller. 1.5 is like 50% bigger.
					</div>
					<input name="scale_size_hover" type="text" value="1.05" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-shadow">
			<div class="cis-sub-header">
				Shadow <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/shadow.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="shadow_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (px):
					<input name="shadow_size" type="text" value="10" />
				</div>
				<div class="cis-field cis-indent">
					Color (HEX: #333333):
					<input name="shadow_color" type="text" value="#333333" />
				</div>
				<div class="cis-field cis-indent">
					Opacity (Decimal: 0 - 1):
					<input name="shadow_opacity" type="text" value="1" />
				</div>
				<div class="cis-field">
					<span class="cis-bold">Hover</span>:
					<select name="shadow_hover" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (px):
					<input name="shadow_size_hover" type="text" value="15" />
				</div>
				<div class="cis-field cis-indent">
					Color (HEX: #000000):
					<input name="shadow_color_hover" type="text" value="#000000" />
				</div>
				<div class="cis-field cis-indent">
					Opacity (Decimal: 0 - 1):
					<input name="shadow_opacity_hover" type="text" value="1" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-shake">
			<div class="cis-sub-header">
				Shake <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/shake.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="shake_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field">
					<span class="cis-bold">Hover</span>:
					<select name="shake_hover" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-size">
			<div class="cis-sub-header">
				Size <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/size.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="size_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Width (px):
					<input name="size_width" type="text" value="200" />
				</div>
				<div class="cis-field cis-indent">
					Height (px):
					<input name="size_height" type="text" value="200" />
				</div>
				<div class="cis-field">
					<span class="cis-bold">Hover</span>:
					<select name="size_hover" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Width (px):
					<input name="size_width_hover" type="text" value="220" />
				</div>
				<div class="cis-field cis-indent">
					Height (px):
					<input name="size_height_hover" type="text" value="220" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-sliding-caption">
			<div class="cis-sub-header">
				Sliding Caption <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<div class="cis-comment">
					You also need to set the 'title' attribute of the image.
				</div>
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/sliding-caption.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Hover</span>:
					<select name="sliding_caption_hover" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Font Size (px):
					<input name="sliding_caption_font_size" type="text" value="11" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-stretch">
			<div class="cis-sub-header">
				Stretch <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<div class="cis-comment">
					If disabled then some part of the image may be hidden in order to retain the proportion of the natural width and height.
				</div>
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/stretch.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="stretch" class="cis-switch">
						<option value="1" selected="selected">Enabled</option>
						<option value="0">Disabled</option>
					</select>
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-vertical-curve">
			<div class="cis-sub-header">
				SVG <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/svg.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="svg_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Image:
					<select name="svg_image">
						<option value="paperclip-black.svg" selected="selected">Paperclip - Black</option>
						<option value="paperclip-blue.svg">Paperclip - Blue</option>
						<option value="paperclip-green.svg">Paperclip - Green</option>
						<option value="paperclip-pink.svg">Paperclip - Pink</option>
						<option value="paperclip-red.svg">Paperclip - Red</option>
						<option value="paperclip-white.svg">Paperclip - White</option>
						<option value="paperclip-yellow.svg">Paperclip - Yellow</option>
					</select>
				</div>
				<div class="cis-field cis-indent cis-hidden">
					Joomla URI:
					<input name="svg_joomla_uri" type="text" value="<?php echo JURI::root(); ?>" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-tape">
			<div class="cis-sub-header">
				Tape <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/tape.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="tape_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-tooltip">
			<div class="cis-sub-header">
				Tooltip <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<div class="cis-comment">
					You also need to set the 'title' attribute of the image.
				</div>
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/tooltip.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Hover</span>:
					<select name="tooltip_hover" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Font Size (px):
					<input name="tooltip_font_size" type="text" value="11" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-vertical-curve">
			<div class="cis-sub-header">
				Vertical Curve <span class="cis-openclose"></span> <span class="cis-onoff"></span>
			</div>
			<div class="cis-toggle">
				<img src="../plugins/system/completeimagestyles/completeimagestyles/images/styles/vertical-curve.jpg" class="cis-sample" />
				<div class="cis-field">
					<span class="cis-bold">Normal</span>:
					<select name="vertical_curve_normal" class="cis-switch">
						<option value="1">Enabled</option>
						<option value="0" selected="selected">Disabled</option>
					</select>
				</div>
				<div class="cis-field cis-indent">
					Size (px):
					<input name="vertical_curve_size" type="text" value="15" />
				</div>
			</div>
		</div>
		<div class="cis-substyle cis-style-transition">
			<div class="cis-sub-header">
				Transition
			</div>
			<div class="cis-toggle">
				<div class="cis-field">
					Duration (sec):
					<input name="transition_duration" type="text" value="0.5" />
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
</div>
<?php } ?>

<div class="cis-field cis-hidden">
	Total Field:
	<textarea name="jform[params][styles]" id="jform_params_styles"><?php echo $value; ?></textarea>
</div>
<?php		
		$output = ob_get_contents();
		ob_end_clean();
	
		return $output;
	}
}
?>