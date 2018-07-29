<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_ticket.php');


class FsssViewAttachClean extends JViewLegacy
{
	var $orpahned = array();

	function display($tpl = null)
	{
		JToolBarHelper::title( JText::_("Ticket Attachment Tools"), 'fss_cronlog' );
		JToolBarHelper::cancel('cancellist');
		FSSAdminHelper::DoSubToolbar();
		
		$task = JRequest::getVar('task');
		
		if ($task == "removethumb")
			return $this->RemoveThumbs();

		if ($task == "stats")
			return $this->Stats();

		if ($task == "missing")
			return $this->RemoveMissing();

		if ($task == "orphaned")
			return $this->ShowOrphaned();

		if ($task == "deleteorphaned")
			return $this->DeleteOrphaned();

		if ($task == "verifydisk")
			return $this->VerifyDisk();

		if ($task == "cleaninline")
			return $this->CleanInline();

		parent::display($tpl);
	}

	function Stats()
	{
		// count up all stats about how many files and thumbnails there are

		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__fss_ticket_attach";
		$db->setQuery($qry);
		$this->files = $db->loadObjectList("diskfile");
		
		$stats = new stdClass();
		$stats->files = 0;
		$stats->size = 0;
		$stats->missing = 0;
		$stats->orphaned = 0;
		$stats->thumbs = 0;
		$stats->thumbsize = 0;

		foreach($this->files as &$file)
		{
			$stats->files++;
			$stats->size += $file->size;

			if (!file_exists(JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'support'.DS.$file->diskfile))
			{
				$stats->missing++;
			}
		}

		$this->stats = $stats;

		$folders = array(
			JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'support',
			JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'thumbnail'
		);

		foreach ($folders as $folder)
		{
			if (file_exists($folder))
			{
				$this->countStats($folder, '');
			}
		}

		parent::display("stats");
	}

	function RemoveMissing()
	{
		// count up all stats about how many files and thumbnails there are

		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__fss_ticket_attach";
		$db->setQuery($qry);
		$this->files = $db->loadObjectList("diskfile");
	
		$count = 0;

		foreach($this->files as &$file)
		{
			if (!file_exists(JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'support'.DS.$file->diskfile))
			{
				$db->setQuery("DELETE FROM #__fss_ticket_attach WHERE id = " . $file->id);
				$db->Query();
				$count++;
			}
		}

		JFactory::getApplication()->redirect("index.php?option=com_fss&view=attachclean", "$count attachments with missing files removed.", "message");
	}

	function countStats($base, $path)
	{
		$dh = opendir($base.$path);

		$count = 0;
		while ($file = readdir($dh))
		{
			if ($file == "." || $file == "..")
				continue;

			if (is_dir($base.$path.DS.$file))
			{
				$this->countStats($base,$path.DS.$file);
			} else {
				$ext = pathinfo($file, PATHINFO_EXTENSION);

				if ($ext == "thumb")
				{
					$this->stats->thumbs++;
					$this->stats->thumbsize += filesize($base.$path.DS.$file);
				} else {
					$key = trim($path.DS.$file, DS);
					if (!array_key_exists($key, $this->files))
					{
						if ($file == "index.html")
							continue;

						$this->orpahned[] = $base . DS . $key;
						$this->stats->orphaned++;
					}
				}
			}
		}
		closedir($dh);
	}

	function DeleteOrphaned()
	{
		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__fss_ticket_attach";
		$db->setQuery($qry);
		$this->files = $db->loadObjectList("diskfile");

		$this->stats = new stdClass();
		$this->stats->orpahned = 0;

		$this->countStats(JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'support', '');

		$count = 0;

		foreach ($this->orpahned as $file)
		{
			if (@unlink($file))
			{
				$count++;
			}
		}

		JFactory::getApplication()->redirect("index.php?option=com_fss&view=attachclean", "$count orphaned files removed", "message");
	}

	function ShowOrphaned()
	{
		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__fss_ticket_attach";
		$db->setQuery($qry);
		$this->files = $db->loadObjectList("diskfile");

		$this->stats = new stdClass();
		$this->stats->orpahned = 0;

		$this->countStats(JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'support', '');

		parent::display("orphaned");		
	}

	function RemoveThumbs()
	{
		$this->removed = array();

		$folders = array(
			JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'support',
			JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'thumbnail'
		);

		foreach ($folders as $folder)
		{
			if (file_exists($folder))
				$this->cleanThumbs($folder);
		}

		$count = count($this->removed);

		JFactory::getApplication()->redirect("index.php?option=com_fss&view=attachclean", "$count thumbnails removed", "message");
	}

	function cleanThumbs($path)
	{
		$dh = opendir($path);

		$count = 0;
		while ($file = readdir($dh))
		{
			if ($file == "." || $file == "..")
				continue;

			if (is_dir($path.DS.$file))
			{
				if ($this->cleanThumbs($path.DS.$file))
					$count++;
			} else {
				$ext = pathinfo($file, PATHINFO_EXTENSION);

				if ($ext == "thumb")
				{
					@unlink($path.DS.$file);
					$this->removed[] = $path.DS.$file;
				} else {
					$count++;
				}
			}
		}
		closedir($dh);

		if ($count == 0)
		{
			//$this->removed[] = $path;
			@rmdir($path);
			return false;
		}

		return true;
	}	

	function VerifyDisk()
	{

		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__fss_ticket_attach";
		$db->setQuery($qry);
		$this->files = $db->loadObjectList("diskfile");
	
		$count = 0;

		foreach($this->files as &$file)
		{
			if (file_exists(JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'support'.DS.$file->diskfile))
			{
				$ticket = new SupportTicket();
				$ticket->load($file->ticket_ticket_id);
				
				$destpath = JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'support'.DS;					
				$destname = FSS_File_Helper::makeAttachFilename("support", $file->filename, $file->added, $ticket, $file->user_id);
				
				if (rename($destpath.$file->diskfile, $destpath.$destname))
				{
					$qry = "UPDATE #__fss_ticket_attach SET diskfile = '" . $db->escape($destname) . "' WHERE id = " . $file->id;
					$db->setQuery($qry);
					$db->Query();
					$count++;
				}
			}
		}

		JFactory::getApplication()->redirect("index.php?option=com_fss&view=attachclean", "$count files verified.", "message");
	}

	function CleanInline()
	{
		$db = JFactory::getDBO();
		$qry = "SELECT ticket_ticket_id, id FROM #__fss_ticket_messages WHERE body LIKE '%]data:%'";
		$db->setQuery($qry);
		$this->messages = $db->loadObjectList();

		foreach ($this->messages as $message)
		{
			$st = new SupportTicket();
			$st->load($message->ticket_ticket_id);
			$st->stripImagesFromMessage($message->id);
		}

		$count = count($this->messages);

		JFactory::getApplication()->redirect("index.php?option=com_fss&view=attachclean", "$count inline images converted to attachments.", "message");
	}
}
