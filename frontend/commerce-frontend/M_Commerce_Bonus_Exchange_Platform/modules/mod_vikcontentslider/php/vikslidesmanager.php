<?php
/**------------------------------------------------------------------------
 * mod_vikcontentslider - VikContentSlider
 * ------------------------------------------------------------------------
 * author    Alessio Gaggii - e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2015 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: https://e4j.com
 * Technical Support:  tech@e4j.com
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldVikslidesmanager extends JFormField {
	protected $type = 'Vikslidesmanager';

	protected function getInput() {
		$top_form = '<div id="vikslider-slides-container">'.
					'	<div id="vikslider-slides-add-wrapper">'.
					'		<a href="#add"><span class="icon-plus-circle"></span>'.JText::_('VIKCSADDSLIDE').'</a>'.
					'	</div>'.
					'	<div id="vikslider-slides-add-form">'.$this->loadFormData('add').'</div>'.
					'</div>';
		
		$edit_form = $this->loadFormData('edit');
		
		$data_form = '<div id="invisible" style="display: none;">'.
					'	<div class="vikslider-slider-entry">'.
					'		<div class="vikslider-slider-entry-inner">'.
					'			<div class="vikslider-slider-divtitle"><span class="vikslider-slider-title" title="'.JText::_('VIKTITLEDESC').'"></span></div>'.
					'			<div class="vikslider-slider-edit">'.
					//'			<span class="vikslider-slider-img"></span>'.
					'			<div class="vikslider-slider-remove"><a href="#remove" class="vikslider-slider-removebtn" title="'.JText::_('VIKCSREMOVESLIDE').'"><span class="icon-unpublish"></span>'.JText::_('VIKCSREMOVESLIDE').'</a></div>'.
					'			<div class="vikslider-slider-editbtn"><a href="#edit" class="vikslider-slider-editbtn" title="'.JText::_('VIKCSEDITSLIDE').'"><span class="icon-pencil-2"></span><span>'.JText::_('VIKCSEDITSLIDE').'</span></a></div>'.
					'			<div class="vikslider-slider-preview"><a rel="{handler:\'image\'}" class="vik-modal modal-img" title="'.JText::_('VIKCSPREVIEWSLIDE').'"><span class="icon-out-2"></span>'.JText::_('VIKCSPREVIEWSLIDE').'</a></div>'.				
					'			<div class="vikslider-slider-status"><div class="vikslider_publishediv"><span class="icon-radio-checked"></span><span class="vikslider_published">'.JText::_('VIKCSPUBLISHEDSLIDE').'</span></div><div class="vikslider_unpublisheddiv"><span class="icon-radio-unchecked"></span><span class="vikslider_unpublished">'.JText::_('VIKCSUNPUBLISHEDSLIDE').'</span></div></div>'.
					'		</div></div>'.
					'		<div class="vikslider-slider-editcontainer">'.
					'			<div class="vikslider-slider-editor">'.$edit_form.'</div>'.
					'		</div>'.
					'	</div>'.
					'</div>';
		
		$slides_list = '<div id="vikslider-allslides"></div>';

		$val_container = '<textarea name="'.$this->name.'" id="'.$this->id.'" style="display: none;">'.$this->value.'</textarea>';
		
		return $data_form . $top_form . $slides_list . $val_container;
	}
	
	private function loadFormData($type = 'add') {
		
		$form_data = '';

		if($type == 'add') {
			// Joomla JS function for Media Manager
			$jscript = '	function jInsertFieldValue(value,id) {'."\n";
			$jscript .= '		var old_id = document.getElementById(id).value;'."\n";
			$jscript .= '		if (old_id != id) {'."\n";
			$jscript .= '			document.getElementById(id).value = value;'."\n";
			$jscript .= '		}'."\n";
			$jscript .= '	}'."\n";
			JFactory::getDocument()->addScriptDeclaration($jscript);
			//
			// Slide IMG
			$form_data .= '<div class="vikslider-slideparam-block">';
			$form_data .= '	<label>'.JText::_('VIKCSIMGBGSLIDE').'</label>';
			$form_data .= '	<input type="text" name="jform[params][img]" id="jform_params_img" value="" class="viksliderparam_'.$type.'_image" />';
			$form_data .= '	<a class="btn modal modal-media" title="'.JText::_('JSELECT').'" href="index.php?option=com_media&view=images&tmpl=component&asset=com_modules&author=&fieldid=jform_params_img&folder=" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">'.JText::_('JSELECT').'</a>';
			$form_data .= '	<a title="'.JText::_('JCLEAR').'" class="btn" href="#" onclick="javascript:document.getElementById(\'jform_params_img\').value=\'\';return false;">'.JText::_('JCLEAR').'</a></p>';
			$form_data .= '</div>';
		} else {
			$form_data .= '<div class="vikslider-slideparam-block">';
			$form_data .= '	<label>'.JText::_('VIKCSIMGBGSLIDE').'</label>';
			$form_data .= '	<input type="text" name="jform_params_edit_img" id="jform_params_edit_img" value="" class="viksliderparam_'.$type.'_image" />';
			$form_data .= '	<a class="btn modal modal-media" title="'.JText::_('JSELECT').'" href="index.php?option=com_media&view=images&tmpl=component&asset=&author=&fieldid=jform_params_edit_img&folder=" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">'.JText::_('JSELECT').'</a>';
			$form_data .= '	<a title="'.JText::_('JCLEAR').'" href="#" onclick="javascript:document.getElementById(\'jform_params_edit_img\').value=\'\';return false;" class="btn modal-media-clear">'.JText::_('JCLEAR').'</a>';
			$form_data .= '</div>';
		}
		//Slide Title
		$form_data .= '<div class="vikslider-slideparam-block"><label title="'.JText::_('VIKTITLEDESC').'">'.JText::_('VIKTITLE').'</label><input type="text" class="viksliderparam_'.$type.'_title" /></div>';
		//Slide Caption
		$form_data .= '<div class="vikslider-slideparam-block"><label title="'.JText::_('VIKCSSLIDECAPTIONDESC').'">'.JText::_('VIKCSSLIDECAPTION').'</label><input type="text" class="viksliderparam_'.$type.'_caption" /></div>';
		//Slide Readmore Link
		$form_data .= '<div class="vikslider-slideparam-block"><label title="'.JText::_('READMOREDESC').'">'.JText::_('READMORE').'</label><input type="text" class="viksliderparam_'.$type.'_readmore" /></div>';
		//Slide Published
		$form_data .= '<div class="vikslider-slideparam-block"><label>'.JText::_('VIKCSSLIDESTATUS').'</label><select class="viksliderparam_'.$type.'_published"><option value="1">'.JText::_('VIKCSPUBLISHEDSLIDE').'</option><option value="0">'.JText::_('VIKCSUNPUBLISHEDSLIDE').'</option></select></div>';
		//Add/Cancel buttons
		$form_data .= '<div class="vikslider-slideparam-block vikslider-slideparam-block-'.$type.'"><a href="#save" class="btn btn-success">'.JText::_('VIKCSBTNADDSLIDE').'</a><a href="#cancel" class="btn">'.JText::_('VIKCSBTNCANCSLIDE').'</a></div>';
				
		return '<div class="vikslider-newslide-cont"><div class="vikslider-newslide-cont-'.$type.'">'.$form_data.'</div></div>';
	}
}