<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport('joomla.form.formfield');

if (!defined("DS")) define('DS', DIRECTORY_SEPARATOR);

class JFormFieldModal_Faqid extends JFormField
{
    /**
     * Element name
     *
     * @access    protected
     * @var        string
     */
	protected $type = 'Modal_Faqid';

    function getInput()
    {
        $mainframe = JFactory::getApplication();

        $db            = JFactory::getDBO();
        $doc         = JFactory::getDocument();
        $template     = $mainframe->getTemplate();
        
		
        JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_fss'.DS.'tables');
        $faq = JTable::getInstance('faq','Table');
		
		$value = $this->value;
		
        if ($value) {
            $faq->load($value);
        } else {
            $faq->question = JText::_("SELECT_A_FAQ");
        }   
        
        $js = '
        function jSelectArticle(id, title, catid, object) {
				document.id("'.$this->id.'_id").value = id;
				document.id("'.$this->id.'_name").value = title;
				SqueezeBox.close();
			}';
        $doc->addScriptDeclaration($js);
                                     
        $link = 'index.php?option=com_fss&amp;task=pick&amp;tmpl=component&amp;controller=faq';

        JHTML::_('behavior.modal', 'a.modal');
        $html = "\n".'<div style="float: left;"><input style="background: #ffffff;" type="text" id="'.$this->id.'_name" value="'.htmlspecialchars($faq->question, ENT_QUOTES, 'UTF-8').'" disabled="disabled" /></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_("SELECT_A_FAQ").'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 410}}">'.JText::_("SELECT").'</a></div></div>'."\n";
        $html .= "\n".'<input type="hidden" id="'.$this->id.'_id" name="'.$this->name.'" value="'.(int)$value.'" />';

        return $html;
    }
}


