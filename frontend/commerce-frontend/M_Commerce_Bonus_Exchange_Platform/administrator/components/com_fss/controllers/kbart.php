<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php' );

class FsssControllerKbart extends FsssController
{

	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'unpublish', 'unpublish' );
		$this->registerTask( 'publish', 'publish' );
		$this->registerTask( 'orderup', 'orderup' );
		$this->registerTask( 'orderdown', 'orderdown' );
		$this->registerTask( 'saveorder', 'saveorder' );
		$this->registerTask( 'apply', 'save' );
		$this->registerTask( 'save2new', 'save' );
	}

	function cancellist()
	{
		$link = 'index.php?option=com_fss&view=fsss';
		$this->setRedirect($link, $msg);
	}

    function pick()
    {
        JRequest::setVar( 'view', 'kbarts' );
        JRequest::setVar( 'layout', 'pick'  );
        
        parent::display();
    }

	function edit()
	{
		JRequest::setVar( 'view', 'kbart' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		$model = $this->getModel('kbart');

        $post = JRequest::get('post');
        $post['body'] = JRequest::getVar('body', '', 'post', 'string', JREQUEST_ALLOWRAW);
        

		if ($model->store($post)) {
			$msg = JText::_("KNOWLEDGE_BASE_ARTICLE_SAVED");
		} else {
			$msg = JText::_("ERROR_SAVING_KNOWLEDGE_BASE_ARTICLE");
		}
             
             
        // process any new file uploaded
        $file = JRequest::getVar('filedata', '', 'FILES', 'array');
		if (array_key_exists('error',$file) && $file['error'] == 0)
        {

			if (!file_exists(JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'kb'))
				mkdir(JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'kb', 0755, true);

            $random = 0;
			$destname = JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'kb'.DS.$random.'-'.$file['name'];    
            while (JFile::exists($destname))
            {
                $random = $random+1; 
				$destname = JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'kb'.DS.$random.'-'.$file['name'];                
            }
            
            if (JFile::upload($file['tmp_name'], $destname))
            {
                $filedb = JTable::getInstance('kbattach','Table');
                $filedb->reset();
                $filedb->kb_art_id = $model->_id;
                $filedb->filename = $file['name'];
                $filedb->diskfile = $random.'-'.$file['name'];
                $filedb->title = $post['filetitle'];
                if (!$filedb->title)
                    $filedb->title = $file['name'];
                $filedb->description = $post['filedesc'];
                $filedb->size = $file['size'];
                $filedb->store();            
            }
        }
        
		$db = JFactory::getDBO();
		
        // process any file deletes
        $array = JRequest::getVar('cid',  0, '', 'array');
        if (count($array) > 0)
        {
            foreach ($array as $offset => $id)
            {
                if ($id < 1)
                    continue;
                    
               $query = 'SELECT *' .
                        ' FROM #__fss_kb_attach' .
                        ' WHERE id = "' . FSSJ3Helper::getEscaped($db, $id) . '"';
                       
     
                $db->setQuery($query);
                
                $file = $db->loadAssoc();
                
				$destname = JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'kb'.DS.$file['diskfile'];
                JFile::delete($destname);
                
                $msg .= ", File " . $file['filename'] . " deleted";
                
                $query = 'DELETE FROM #__fss_kb_attach WHERE id = "' . FSSJ3Helper::getEscaped($db, $id) . '"';
                $db->setQuery($query);$db->Query();
            } 
        }
		
		$query = 'SELECT * FROM #__fss_kb_attach WHERE kb_art_id = "' . $db->escape($model->_id) . '"';
		$db->setQuery($query);
		$items = $db->loadObjectList();
		
		foreach ($items as $item)
		{
			$field = "attach_order_" . $item->id;
			
			$value = JRequest::getVar($field, 0);
			
			$query = "UPDATE #__fss_kb_attach SET ordering = '". $db->escape($value) . "' WHERE id = '" . $item->id . "'";
			$db->setQuery($query);$db->Query();
		}
		
        // redirect

		if ($this->task == "apply")
		{
			$link = "index.php?option=com_fss&controller=kbart&task=edit&cid[]=" . $model->_id;
		} else if ($this->task == "save2new")
		{
			$link = 'index.php?option=com_fss&controller=kbart&task=edit';
		} else {
			$link = 'index.php?option=com_fss&view=kbarts';
		}
		$this->setRedirect($link, $msg);
	}


	function remove()
	{
		$model = $this->getModel('kbart');
		if(!$model->delete()) {
			$msg = JText::_("ERROR_ONE_OR_MORE_KNOWLEDGE_BASE_ARTICLE_COULD_NOT_BE_DELETED");
		} else {
			$msg = JText::_("KNOWLEDGE_BASE_ARTICLE_S_DELETED" );
		}
        
        // delete any attached files and their records!
        $db    = JFactory::getDBO();
        $query = 'SELECT *' .
                ' FROM #__fss_kb_attach' .
                ' WHERE kb_art_id = "' . FSSJ3Helper::getEscaped($db, $model->_id) . '"' .
                ' ORDER BY title ';
               
        $db->setQuery($query);
        
        $files = $db->loadAssocList();
        
        foreach ($files as $file)
        {
			 $destname = JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'kb'.DS.$file['diskfile']; 
             JFile::delete($destname);
        }
        
        $query = 'DELETE FROM #__fss_kb_attach WHERE kb_art_id = "' . FSSJ3Helper::getEscaped($db, $model->_id) . '"';
        $db->setQuery($query);
		$db->Query();

		$this->setRedirect( 'index.php?option=com_fss&view=kbarts', $msg );
	}


	function cancel()
	{
		$msg = JText::_("OPERATION_CANCELLED");
		$this->setRedirect( 'index.php?option=com_fss&view=kbarts', $msg );
	}

	function unpublish()
	{
		$model = $this->getModel('kbart');
		if (!$model->unpublish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_UNPUBLISHING_AN_KNOWLEDGE_BASE_ARTICLE");

		$this->setRedirect( 'index.php?option=com_fss&view=kbarts', $msg );
	}

	function publish()
	{
		$model = $this->getModel('kbart');
		if (!$model->publish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_PUBLISHING_AN_KNOWLEDGE_BASE_ARTICLE");

		$this->setRedirect( 'index.php?option=com_fss&view=kbarts', $msg );
	}

	function orderup()
	{
		$model = $this->getModel('kbart');
		if (!$model->changeorder(-1))
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=kbarts', $msg );
	}

	function orderdown()
	{
		$model = $this->getModel('kbart');
		if (!$model->changeorder(1))
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=kbarts', $msg );
	}

	function saveorder()
	{
		$model = $this->getModel('kbart');
		if (!$model->saveorder())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=kbarts', $msg );
	}
	
	function download()
	{
		$fileid = JRequest::getVar('fileid', 0, '', 'int');            
		
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM #__fss_kb_attach WHERE id = "' . FSSJ3Helper::getEscaped($db, $fileid) . '"';
		$db->setQuery($query);
		$row = $db->loadAssoc();
		
		
		$filename = FSS_Helper::basename($row['filename']);
		$file_extension = strtolower(substr(strrchr($filename,"."),1));
		$ctype = FSS_Helper::datei_mime($file_extension);
		
		while(ob_get_level() > 0)
			ob_end_clean();

		header("Cache-Control: public, must-revalidate");
		header('Cache-Control: pre-check=0, post-check=0, max-age=0');
		header("Pragma: no-cache");
		header("Expires: 0"); 
		header("Content-Description: File Transfer");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
		header("Content-Type: " . $ctype);
		//header("Content-Length: ".(string)$row['size']);
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		header("Content-Transfer-Encoding: binary\n");
		
		//echo getcwd(). "<br>";
		$file = "../components/com_fss/files/" . $row['diskfile'];
		//echo $file;
		@readfile($file);
		exit;
	}

	function prods()
	{
		JRequest::setVar( 'view', 'kbart' );
		JRequest::setVar( 'layout', 'prods'  );
		
		parent::display();
	}
	
	function resetviews()
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		
		if (count($cid) > 0)
		{
			$ids = array();
			foreach ($cid as $id)
				$ids[] = (int)$id;
			
			$qry = "UPDATE #__fss_kb_art SET views = 0 WHERE id IN (" . implode(", ", $ids) . ")";
			$db = JFactory::getDBO();
			$db->setQuery($qry);
			$db->Query();
			
			$msg = JText::_("KB_VIEWS_RESET");
		}
		

		$this->setRedirect( 'index.php?option=com_fss&view=kbarts', $msg );
	}
	
	function resetrating()
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		
		if (count($cid) > 0)
		{
			$ids = array();
			foreach ($cid as $id)
				$ids[] = (int)$id;
			
			$qry = "UPDATE #__fss_kb_art SET rating = 0, ratingdetail = '0|0|0' WHERE id IN (" . implode(", ", $ids) . ")";
			$db = JFactory::getDBO();
			$db->setQuery($qry);
			$db->Query();
			
			$msg = JText::_("KB_RATINGS_RESET");
		}
		
		$this->setRedirect( 'index.php?option=com_fss&view=kbarts', $msg );
	}	
	
		
	function autosort()
	{
		//echo "Auto Sort<br>";	
		
		$qry = "SELECT id FROM #__fss_kb_art ORDER BY title";
		$db = JFactory::getDBO();
		
		//echo "Qry : $qry<br>";
		
		$db->setQuery($qry);
		
		$rows = $db->loadObjectList();	
		
		$order = 1;
		foreach ($rows as $row)
		{
			$qry = "UPDATE #__fss_kb_art SET ordering = $order WHERE id = {$row->id}";
			$db->setQuery($qry);
			//echo $qry . "<br>";
			$db->Query(); 	
			$order++;
		}
		$this->setRedirect( 'index.php?option=com_fss&view=kbarts', "KB Articles ordered alphabetically" );
	}
}






