<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.html.parameter.element');

class JElementKbartid extends JElement
{
    /**
     * Element name
     *
     * @access    protected
     * @var        string
     */
    var    $_name = 'Kbartid';

    function fetchElement($name, $value, &$node, $control_name)
    {
        $mainframe = JFactory::getApplication();

        $db            = JFactory::getDBO();
        $doc         = JFactory::getDocument();
        $template     = $mainframe->getTemplate();
        $fieldName    = $control_name.'['.$name.']';
        
        JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_fss'.DS.'tables');
        $art = JTable::getInstance('kbart','Table');
        if ($value) {
            $art->load($value);
        } else {
            $art->title = JText::_("SELECT_AN_KB_ARTICLE");
        }   
        
        $js = "
        function jSelectArticle(id, title, object) {
            document.getElementById(object + '_id').value = id;
            document.getElementById(object + '_name').value = title;
            document.getElementById('sbox-window').close();
        }";
        $doc->addScriptDeclaration($js);
                                     
        $link = 'index.php?option=com_fss&amp;task=pick&amp;tmpl=component&amp;controller=kbart';

        JHTML::_('behavior.modal', 'a.modal');
        $html = "\n".'<div style="float: left;"><input style="background: #ffffff;" type="text" id="'.$name.'_name" value="'.htmlspecialchars($art->title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" /></div>';
//        $html .= "\n &nbsp; <input class=\"inputbox modal-button\" type=\"button\" value=\"".JText::_("SELECT")."\" />";
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_("SELECT_AN_KB_ARTICLE").'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 410}}">'.JText::_("SELECT").'</a></div></div>'."\n";
        $html .= "\n".'<input type="hidden" id="'.$name.'_id" name="'.$fieldName.'" value="'.(int)$value.'" />';

        return $html;
    }
}


