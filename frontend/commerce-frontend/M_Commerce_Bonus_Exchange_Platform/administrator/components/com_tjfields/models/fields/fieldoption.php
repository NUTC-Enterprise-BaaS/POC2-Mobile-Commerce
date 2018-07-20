<?php
/**
 * @package		com_tjfields
 * @version		$versionID$
 * @author		TechJoomla
 * @author mail	extensions@techjoomla.com
 * @website		http://techjoomla.com
 * @copyright	Copyright © 2009-2013 TechJoomla. All rights reserved.
 * @license		GNU General Public License version 2, or later
*/

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');


?>

<?php

/**
 * Supports an HTML select list of categories
 */
class JFormFieldFieldoption extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */

	protected $type = 'text';
	function __construct ()
	{
		parent::__construct();
		$this->countoption=0;
		if(JVERSION>=3.0)
			{
				$this->tjfield_icon_plus = "icon-plus-2 ";
				$this->tjfield_icon_minus = "icon-minus-2 ";
				$this->tjfield_icon_star = "icon-featured";
				$this->tjfield_icon_emptystar = "icon-unfeatured";
			}
			else
			{ // for joomla3.0
				$this->tjfield_icon_plus = "icon-plus ";
				$this->tjfield_icon_minus = "icon-minus ";
				$this->tjfield_icon_star = "icon-star";
				$this->tjfield_icon_emptystar = "icon-star-empty";
			}
	}
	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		//print_r($this->value); die('asdas');
		$countoption=count($this->value);
		if(empty($this->value))
		$countoption=0;
		//$this->countoption=count($this->value);
		//$this->countoption=count($this->value);


			$k=0;
			$html='';
			if(JVERSION>=3.0)
			{
				$html.='

				<script>var field_lenght='.$countoption.'
					var tjfield_icon_emptystar = "icon-unfeatured";
					var tjfield_icon_star = "icon-featured";
					var tjfield_icon_minus = "icon-minus-2 ";
				</script>';
			}
			else
			{
				$html.='

				<script>var field_lenght='.$countoption.'
					var tjfield_icon_emptystar = "icon-star-empty";
					var tjfield_icon_star = "icon-star";
					var tjfield_icon_minus = "icon-minus ";
				</script>';
			}
			$html.='<div class="techjoomla-bootstrap">
				<div id="tjfield_container" class="tjfield_container" >';

			if($this->value)
			{
				for($k=0;$k<=count($this->value);$k++)
				{
						$html.=	'<div id="com_tjfields_repeating_block'.$k.'"    class="com_tjfields_repeating_block span7">
									<div class="form-inline">
										'.$this->fetchOptionName($this->name,(isset($this->value[$k]->options))?$this->value[$k]->options:"", $this->element, $this->options['control'],$k).$this->fetchOptionValue($this->name,(isset($this->value[$k]->value))?$this->value[$k]->value:"", $this->element, $this->options['control'],$k).$this->fetchdedaultoption($this->name,(isset($this->value[$k]->default_option))?$this->value[$k]->default_option:"", $this->element, $this->options['control'],$k).$this->fetchhiddenoption($this->name,(isset($this->value[$k]->default_option))?$this->value[$k]->default_option:"", $this->element, $this->options['control'],$k).$this->fetchhiddenoptionid($this->name,(isset($this->value[$k]->id))?$this->value[$k]->id:"", $this->element, $this->options['control'],$k).'
									</div>
								</div>';

							if($k<count($this->value)){
											$html.='<div id="remove_btn_div'.$k.'" class="com_tjfields_remove_button span3">
												<div class="com_tjfields_remove_button">
													<button class="btn btn-small btn-danger" type="button" id="remove'.$k.'" onclick="removeClone(\'com_tjfields_repeating_block'.$k.'\',\'remove_btn_div'.$k.'\');" >
																	<i class="'.$this->tjfield_icon_minus.'"></i></button>
												</div>
											</div>';
											}

				}
			}
			else
			{
						$html.=	'<div id="com_tjfields_repeating_block0" class="com_tjfields_repeating_block span7">
									<div class="form-inline">
										'.$this->fetchOptionName($this->name,(isset($this->value[$k]->options))?$this->value[$k]->options:"", $this->element, $this->options['control'],$k).$this->fetchOptionValue($this->name,(isset($this->value[$k]->value))?$this->value[$k]->value:"", $this->element, $this->options['control'],$k).$this->fetchdedaultoption($this->name,(isset($this->value[$k]->default_option))?$this->value[$k]->default_option:"", $this->element, $this->options['control'],$k).$this->fetchhiddenoption($this->name,(isset($this->value[$k]->default_option))?$this->value[$k]->default_option:"", $this->element, $this->options['control'],$k).$this->fetchhiddenoptionid($this->name,(isset($this->value[$k]->id))?$this->value[$k]->id:"", $this->element, $this->options['control'],$k).'
									</div>
								</div>';
			}
						$html.='<div class="com_tjfields_add_button span3">
														<button class="btn btn-small btn-success" type="button" id="add"
														onclick="addClone(\'com_tjfields_repeating_block\',\'com_tjlms_repeating_block\');"
														title='.JText::_("COM_TJFIELDS_ADD_BUTTON").'>
															<i class="'.$this->tjfield_icon_plus.'"></i>
														</button>
										</div>
					<div style="clear:both"></div>
					<div class="row-fluid">
						<div class="span9 alert alert-info alert-help-inline">' ;
					$html.= JText::sprintf("COM_TJFIELDS_MAKE_DEFAULT_MSG",' <i class="'.$this->tjfield_icon_emptystar.'"></i> ');
					$html.= '</div>
					</div>
				</div>

			</div>';//bootstrap div
			return $html;
	}

	var	$_name = 'fieldoption';
	function fetchOptionName($fieldName, $value, &$node, $control_name,$k)
	{

		return $OptionName='<input type="text" id="tjfields_optionname_'.$k.'"	 name="tjfields['.$k.'][optionname]" class="tjfields_optionname "  placeholder="Name" value="'.$value.'">';
	}

	function fetchOptionValue($fieldName, $value, &$node, $control_name,$k)
	{
		return $OptionValue='<input type="text" id="tjfields_optionvalue_'.$k.'" name="tjfields['.$k.'][optionvalue]"  class="tjfields_optionvalue "  placeholder="Value"  value="'.$value.'">';
	}
	function fetchdedaultoption($fieldName, $value, &$node, $control_name,$k)
	{
		if($value==1)
			$icon='class="'.$this->tjfield_icon_star.'"';
		else
			$icon='class="'.$this->tjfield_icon_emptystar.'"';
		return $dedaultoption='<span class=" tjfields_defaultoptionvalue " id="tjfields_defaultoptionvalue_'.$k.'" onclick="getdefaultimage(this.id)" name="tjfields['.$k.'][defaultoptionvalue]"   ><i '.$icon.' ></i></span>';

		/*
		'<img src="'.JURI::root().'administrator'.DS.'components'.DS.'com_tjfields'.DS.'images'.DS.'nodefault.png" id="tjfields_defaultoptionvalue_0" onclick="getdefaultimage(this.id)" name="tjfields[0][defaultoptionvalue]"  class="tjfields_defaultoptionvalue featured " />';
		*/
	}
	function fetchhiddenoption($fieldName, $value, &$node, $control_name,$k)
	{
		return $hiddenoption='<input type="hidden" id="tjfields_hiddenoption_'.$k.'" name="tjfields['.$k.'][hiddenoption]"  class="tjfields_hiddenoption "  placeholder="Value"  value="'.$value.'">';
	}
	function fetchhiddenoptionid($fieldName, $value, &$node, $control_name,$k)
	{
		return $hiddenoptionid='<input type="hidden" id="tjfields_hiddenoptionid_'.$k.'" name="tjfields['.$k.'][hiddenoptionid]"  class="tjfields_hiddenoptionid "  placeholder="Value"  value="'.$value.'">';
	}
}
?>

<script>




function addClone(rId,rClass)
		{
			//window.field_lenght=f_lenght;
			var pre=field_lenght;
			field_lenght++;


				var removeButton="<div id='remove_btn_div"+pre+"' class='com_tjfields_remove_button span2'>";
				removeButton+="<button class='btn btn-small btn-danger' type='button' id='remove"+pre+"'";
				removeButton+="onclick=\"removeClone('com_tjfields_repeating_block"+pre+"','remove_btn_div"+pre+"');\" title=\"<?php echo JText::_('COM_TJFIELDS_REMOVE_TOOLTIP');?>\" >";
				removeButton+="<i class=\""+tjfield_icon_minus+"\"></i></button>";
				removeButton+="</div>";

				var newElem=techjoomla.jQuery('#'+rId+pre).clone().attr('id',rId+field_lenght);
				newElem.find('input[name=\"tjfields[' + pre + '][optionname]\"]').attr({'name': 'tjfields[' + field_lenght + '][optionname]','value':''});
				newElem.find('input[name=\"tjfields[' + pre + '][optionvalue]\"]').attr({'name': 'tjfields[' + field_lenght + '][optionvalue]','value':''});
				newElem.find('input[name=\"tjfields[' + pre + '][hiddenoption]\"]').attr({'name': 'tjfields[' + field_lenght + '][hiddenoption]','value':''});
				newElem.find('input[name=\"tjfields[' + pre + '][hiddenoptionid]\"]').attr({'name': 'tjfields[' + field_lenght + '][hiddenoptionid]','value':''});
				newElem.find('span[name=\"tjfields[' + pre + '][defaultoptionvalue]\"]').attr({'name': 'tjfields[' + field_lenght + '][defaultoptionvalue]'}); //newElem.find('img[src="localhost/jt315/administrator/components/com_tjfields/images/default.png"]').attr({'src':'localhost/jt315/administrator/components/com_tjfields/images/nodefault.png'});

				/*incremnt id*/
				newElem.find('input[id=\"tjfields_optionname_'+pre+'\"]').attr({'id': 'tjfields_optionname_'+field_lenght,'value':''});
				newElem.find('input[id=\"tjfields_optionvalue_'+pre+'\"]').attr({'id': 'tjfields_optionvalue_'+field_lenght,'value':''});
				newElem.find('input[id=\"tjfields_hiddenoption_'+pre+'\"]').attr({'id': 'tjfields_hiddenoption_'+field_lenght,'value':''});
				newElem.find('input[id=\"tjfields_hiddenoptionid_'+pre+'\"]').attr({'id': 'tjfields_hiddenoptionid_'+field_lenght,'value':''});
				newElem.find('span[id=\"tjfields_defaultoptionvalue_'+pre+'\"]').attr({'id': 'tjfields_defaultoptionvalue_'+field_lenght,'value':''});

				techjoomla.jQuery('#'+rId+pre).after(newElem);
				techjoomla.jQuery('#tjfields_defaultoptionvalue_'+field_lenght).html('<i class="'+tjfield_icon_emptystar+'"></i>');
				techjoomla.jQuery('#'+rId+pre).after(removeButton)
			//	techjoomla.jQuery('#'+rId+pre).append(removeButton);
		}

	   function removeClone(rId,r_btndivId){
					techjoomla.jQuery('#'+rId).remove();
					techjoomla.jQuery('#'+r_btndivId).remove();
		}

		function getdefaultimage(span_id)
		{
			//make all nodefault..
				if(techjoomla.jQuery('#jform_type').val()=='single_select' || techjoomla.jQuery('#jform_type').val()=='radio')
				{
					techjoomla.jQuery('.tjfields_defaultoptionvalue').each(function(){

							techjoomla.jQuery(this).html("<i class='"+tjfield_icon_emptystar+"'></i>");
							//techjoomla.jQuery(this).attr('src',"<?php echo JUri::root().'administrator'.DS.'components'.DS.'com_tjfields'.DS.'images'.DS.'nodefault.png' ?>");

						});
					techjoomla.jQuery('.tjfields_hiddenoption').each(function(){
						techjoomla.jQuery(this).attr('value',0);
					});
				}
				var str1= span_id;
				var req_id= str1.split('_');


				if(techjoomla.jQuery('#'+span_id).children('i').hasClass( tjfield_icon_star ))
				{
					techjoomla.jQuery('#'+span_id).html("<i class='"+tjfield_icon_emptystar+"'></i>");
					techjoomla.jQuery('#tjfields_hiddenoption_'+req_id[2]).attr('value',0);
				}
				else
				{
					techjoomla.jQuery('#'+span_id).html("<i class='"+tjfield_icon_star+"'></i>");
					techjoomla.jQuery('#tjfields_hiddenoption_'+req_id[2]).attr('value',1);
				}

		}

</script>
