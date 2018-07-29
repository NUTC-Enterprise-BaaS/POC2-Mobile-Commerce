<?php
/**
 * @package    JBusinessDirectory
 * @subpackage  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2007 - 2015 CMS Junkie. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die('Restricted access');

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
?>

<script type="text/javascript">
    Joomla.submitbutton = function(task) {

        var defaultLang="<?php echo JFactory::getLanguage()->getTag() ?>";

        jQuery("#item-form").validationEngine('detach');
        var evt = document.createEvent("HTMLEvents");
        evt.initEvent("click", true, true);
        var tab = ("tab-"+defaultLang);
        if(!(document.getElementsByClassName(tab)[0] === undefined || document.getElementsByClassName(tab)[0] === null))
            document.getElementsByClassName(tab)[0].dispatchEvent(evt);
        if (task == 'reviewquestion.cancel' || !validateCmpForm()) {
            Joomla.submitform(task, document.getElementById('item-form'));
        }
        jQuery("#item-form").validationEngine('attach');
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-horizontal">
    <div class="clr mandatory oh">
        <p><?php echo JText::_("LNG_REQUIRED_INFO")?></p>
    </div><hr/>
    <div class="row-fluid">
        <div class="span6">
            <fieldset class="form-horizontal">
                <h2> <?php echo JText::_('LNG_REVIEW_QUESTION');?></h2>

                <div class="control-group">
                    <div class="control-label">
                        <label for="name"><?php echo JText::_('LNG_NAME')?> </label>
                    </div>
                    <div class="controls">
                    <?php
                    if($this->appSettings->enable_multilingual) {
                        $options = array(
                            'onActive' => 'function(title, description){
									description.setStyle("display", "block");
									title.addClass("open").removeClass("closed");
								}',
                            'onBackground' => 'function(title, description){
									description.setStyle("display", "none");
									title.addClass("closed").removeClass("open");
								}',
                            'startOffset' => 0,  // 0 starts on the first tab, 1 starts the second, etc...
                            'useCookie' => true, // this must not be a string. Don't use quotes.
                        );
                        echo JHtml::_('tabs.start', 'tab_groupsd_id', $options);
                        foreach( $this->languages  as $k=>$lng ) {
                            echo JHtml::_('tabs.panel', $lng, 'tab-'.$lng );
                            $langContent = isset($this->translations[$lng."_name"])?$this->translations[$lng."_name"]:"";
                            if($lng==JFactory::getLanguage()->getTag() && empty($langContent)){
                                $langContent = $this->item->name;
                            }
                            echo "<input type='text' name='name_$lng' id='name_$lng' class='input_txt validate[required]' value=\"".stripslashes($langContent)."\"  maxLength='250'>";
                            echo "<div class='clear'></div>";
                        }
                        echo JHtml::_('tabs.end');
                    } else { ?>
                        <input type="text" name="name" id="name" class="input_txt validate[required]" value="<?php echo $this->item->name ?>"  maxLength="250">
                    <?php } ?>
                    </div>
                </div>

                <div class="control-group">
                    <div class="control-label">
                        <label for="typeId"><?php echo JText::_('LNG_TYPE')?></label>
                    </div>
                    <div class="controls">
                         <select data-placeholder="<?php echo JText::_("LNG_JOPTION_SELECT_TYPE") ?>" class="inputbox input-medium validate[required] chosen-select" name="type" id="type">
                            <option value=""><?php echo JText::_("LNG_JOPTION_SELECT_TYPE") ?></option>
                            <?php echo JHtml::_('select.options', $this->types, 'value', 'text', $this->item->type);?>
                         </select>
                    </div>
                </div>

                <div class="control-group" >
                    <div class="control-label">
                        <label for="article_id"><?php echo JText::_('LNG_STATUS')?> </label>
                    </div>
                    <div class="controls">
                        <fieldset id="show_time_fld" class="radio btn-group btn-group-yesno">
                            <input type="radio" class="validate[required]" name="published" id="published1" value="1" <?php echo $this->item->published==1? 'checked="checked"' :""?> />
                            <label class="btn" for="published1"><?php echo JText::_('LNG_PUBLISHED')?></label>
                            <input type="radio" class="validate[required]" name="published" id="published0" value="0" <?php echo $this->item->published==0? 'checked="checked"' :""?> />
                            <label class="btn" for="published0"><?php echo JText::_('LNG_UNPUBLISHED')?></label>
                        </fieldset>
                    </div>
                </div>

                <div class="control-group" >
                    <div class="control-label">
                        <label for="article_id"><?php echo JText::_('LNG_MANDATORY')?> </label>
                    </div>
                    <div class="controls">
                        <fieldset id="show_time_fld" class="radio btn-group btn-group-yesno">
                            <input type="radio" class="validate[required]" name="is_mandatory" id="is_mandatory1" value="1" <?php echo $this->item->is_mandatory==1? 'checked="checked"' :""?> />
                            <label class="btn" for="is_mandatory1"><?php echo JText::_('LNG_YES')?></label>
                            <input type="radio" class="validate[required]" name="is_mandatory" id="is_mandatory0" value="0" <?php echo $this->item->is_mandatory==0? 'checked="checked"' :""?> />
                            <label class="btn" for="is_mandatory0"><?php echo JText::_('LNG_NO')?></label>
                        </fieldset>
                    </div>
                </div>

            </fieldset>
        </div>
    </div>
        <input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" />
        <input type="hidden" name="task" id="task" value="" />
        <input type="hidden" name="id" value="<?php echo $this->item->id ?>" />
        <input type="hidden" name="view" id="view" value="reviewquestion" />
        <?php echo JHTML::_( 'form.token' ); ?>
</form>

<script>
    function validateCmpForm() {
        var isError = jQuery("#item-form").validationEngine('validate');
        return !isError;
    }
</script>