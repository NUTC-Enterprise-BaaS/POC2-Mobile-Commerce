<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 3f4d6e90a50912f4b13325b1cb83bf89
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
jimport('joomla.filesystem.file');
jimport('joomla.utilities.date');

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'admin_helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_actions.php');

class FssViewAdmin_Groups extends FSSView
{
    function display($tpl = null)
    {
		FSS_Helper::noBots();
		FSS_Helper::noCache();
		
		if (!FSS_Permission::AdminGroups())
			return FSS_Admin_Helper::NoPerm();
	
		$this->groupid = FSS_Input::getInt('groupid');
		
		$what = FSS_Input::getCmd('what');
		
		if (JRequest::getVar('messages') != "")
			return $this->redirectMessages();

		if ($what == "productlist")
			return $this->DisplayProducts();
			
		if ($what == "setperm")
			return $this->SetPerm();	
		
		if ($what == "toggleallemail")
			return $this->ToggleAllEMail();
		
		if ($what == "toggleadmin")
			return $this->ToggleIsAdmin();
			
		if ($what == "pickuser")
			return $this->PickUser();
	
		if ($what == "adduser")
			return $this->AddUser();
		
		if ($what == "removemembers")
			return $this->RemoveUsers();
			
		if ($what == "savegroup" || $what == "saveclose")
			return $this->SaveGroup($what);
			
		if ($what == "create")
			return $this->CreateGroup();
		
		if ($what == "deletegroup")
			return $this->DeleteGroup();
		
		if ($this->groupid > 0)
			return $this->DisplayGroup();
		
		return $this->DisplayGroupList();
		
		parent::display();
    }

	function RedirectMessages()
	{
		$messages = explode("|", JRequest::getVar("messages"));
		foreach ($messages as $message)
		{
			JFactory::getApplication()->enqueueMessage($message, 'warning');
		}
		JFactory::getApplication()->redirect(FSS_Helper::getCurrentURL(true, array("messages" => "*")));
	}
	
	function getGroupPerms()
	{
		$this->group_id_access = array();
		
		$model = $this->getModel();
		$model->group_id_access = $this->group_id_access;
	}

	function DisplayGroup()
	{
		$this->creating = false;
		
		$groupid = FSS_Input::getInt('groupid');
		if (!$this->canAdminGroup($groupid))
			return FSS_Admin_Helper::NoPerm();
		
		$this->group = $this->get('Group');
		$this->groupmembers = $this->get('GroupMembers');
		//print_p($this->groupmembers);
		
		$this->pagination = $this->get('GroupMembersPagination');
	
		$mainframe = JFactory::getApplication();
		$pathway = $mainframe->getPathway();
		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'groups' )))	
			$pathway->addItem(JText::_('TICKET_GROUPS'), FSSRoute::_( 'index.php?option=com_fss&view=admin_groups' ) );
		$pathway->addItem($this->group->groupname );

		$this->buildGroupEditForm();

		$this->order = FSS_Input::getCmd('filter_order');
		$this->order_Dir = FSS_Input::getCmd('filter_order_Dir');
		$this->limit_start = FSS_Input::getInt("limit_start");

		FSS_Helper::IncludeModal();

		parent::display('group');
	}
	
	function buildGroupEditForm()
	{
		$db = JFactory::getDBO();
		
		$idents = array();
		$idents[] = JHTML::_('select.option', '0', JText::_("VIEW_NONE"), 'id', 'title');
		$idents[] = JHTML::_('select.option', '1', JText::_("VIEW"), 'id', 'title');
		$idents[] = JHTML::_('select.option', '2', JText::_("VIEW_REPLY"), 'id', 'title');			
		$idents[] = JHTML::_('select.option', '3', JText::_("VIEW_REPLY_CLOSE"), 'id', 'title');			
		$this->allsee = JHTML::_('select.genericlist',  $idents, 'allsee', ' class="inputbox" size="1"', 'id', 'title', $this->group->allsee);

		$this->allprod = JHTML::_('select.booleanlist', 'allprods', 
			array('class' => "inputbox inline",
				'size' => "1", 
				'onclick' => "DoAllProdChange();"),
			 intval($this->group->allprods));

		$query = "SELECT * FROM #__fss_prod WHERE insupport = 1 AND published = 1 ORDER BY title";
		$db->setQuery($query);
		$products = $db->loadObjectList();

		$query = "SELECT * FROM #__fss_ticket_group_prod WHERE group_id = " . FSSJ3Helper::getEscaped($db, $this->group->id);
		$db->setQuery($query);
		$selprod = $db->loadAssocList('prod_id');
		
		$this->assign('allprods',$this->group->allprods);
		
		$prodcheck = "";
		foreach($products as $product)
		{
			$checked = false;
			if (array_key_exists($product->id,$selprod))
			{
				$prodcheck .= '<label class="checkbox">';
				$prodcheck .= '<input type="checkbox" name="prod_' . $product->id . '" checked>' . $product->title;
				$prodcheck .= '</label>';
				
				//$prodcheck .= "<input type='checkbox' name='prod_" . $product->id . "' checked />" . $product->title . "<br>";
			} else {
				$prodcheck .= '<label class="checkbox">';
				$prodcheck .= '<input type="checkbox" name="prod_' . $product->id . '">' . $product->title;
				$prodcheck .= '</label>';
				//$prodcheck .= "<input type='checkbox' name='prod_" . $product->id . "' />" . $product->title . "<br>";
			}
		}
		$this->products = $prodcheck;	
		
		$this->order = "";
		$this->order_Dir = "";
	}
		
	function DisplayGroupList()
	{
		$this->groups = $this->get('Groups');
		
		$mainframe = JFactory::getApplication();
		$pathway = $mainframe->getPathway();
		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'groups' )))	
			$pathway->addItem(JText::_('TICKET_GROUPS'), FSSRoute::_( 'index.php?option=com_fss&view=admin_groups' ) );
			
		FSS_Helper::IncludeModal();
			
		parent::display();
	}
	
	function DisplayProducts()
	{
		$this->products = $this->get('GroupProds');
		$this->group = $this->get('Group');
		
		parent::display('prods');
		
		exit;	
	}
	
	function SetPerm()
	{
		$db	= JFactory::getDBO();
		
		$userid = FSS_Input::getInt('userid');
		$groupid = FSS_Input::getInt('groupid');
		$perm = FSS_Input::getString('perm');
		
		if (!$this->canAdminGroup($groupid))
			return;
		
		$qry = "UPDATE #__fss_ticket_group_members SET allsee = '".FSSJ3Helper::getEscaped($db, $perm)."' WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		
		$db->setQuery($qry);
		$db->Query();
		
		echo "1";
		
		exit;		
	}
	
	function ToggleIsAdmin()
	{
		$db	= JFactory::getDBO();
		
		$userid = FSS_Input::getInt('userid');
		$groupid = FSS_Input::getInt('groupid');
		
		if (!$this->canAdminGroup($groupid))
			return;
			
		$qry = "SELECT isadmin FROM #__fss_ticket_group_members WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		
		$current = $db->loadObject();
		$isadmin = $current->isadmin;
		$isadmin = 1 - $isadmin;
		
		$qry = "UPDATE #__fss_ticket_group_members SET isadmin = '".FSSJ3Helper::getEscaped($db, $isadmin)."' WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		
		$db->setQuery($qry);
		$db->Query();
		
		echo FSS_Helper::GetYesNoText($isadmin);
		
		exit;		
		
	}
	
	function ToggleAllEMail()
	{
		$db	= JFactory::getDBO();
		
		$userid = FSS_Input::getInt('userid');
		$groupid = FSS_Input::getInt('groupid');
		
		if (!$this->canAdminGroup($groupid))
			return;
		
		$qry = "SELECT allemail FROM #__fss_ticket_group_members WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		
		$current = $db->loadObject();
		$allemail = $current->allemail;
		$allemail = 1 - $allemail;
		
		$qry = "UPDATE #__fss_ticket_group_members SET allemail = '".FSSJ3Helper::getEscaped($db, $allemail)."' WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		
		$db->setQuery($qry);
		$db->Query();
		
		echo FSS_Helper::GetYesNoText($allemail);
		
		exit;		
		
	}
	
	function canAdminGroup($groupid)
	{
		if (FSS_Permission::auth("fss.groups", "com_fss.groups"))
			return true;
		if (!array_key_exists($groupid, FSS_Permission::$group_id_access))
			return false;
		return true;	
	}
	
	function PickUser()
	{
		$filter = array();
		$filter[] = JHTML::_('select.option', '', JText::_('JOOMLA_GROUP'), 'id', 'name');
		$query = 'SELECT id, title as name FROM #__usergroups ORDER BY title';
		$db	= JFactory::getDBO();
		$db->setQuery($query);
		$filter = array_merge($filter, $db->loadObjectList());
		$this->gid = JHTML::_('select.genericlist',  $filter, 'gid', 'class="inputbox" size="1" onchange="document.fssForm.submit( );"', 'id', 'name', FSS_Input::getInt('gid'));

        $this->users = $this->get('Users');

		$this->search = FSS_Input::getString('search');
		$this->username = FSS_Input::getString('username');
		$this->email = FSS_Input::getString('email');
		$this->order = FSS_Input::getCmd('filter_order');
		$this->order_Dir = FSS_Input::getCmd('filter_order_Dir');

		$this->pagination = $this->get('UsersPagination');
		$this->limit_start = FSS_Input::getInt("limit_start");
		parent::display("users");
	}
	
	function AddUser()
	{
		$userids = FSS_Input::getArrayInt('cid');
		$groupid = FSS_Input::getInt('groupid');
		
		if (!$this->canAdminGroup($groupid))
			return;

		$messages = array();

		$db	= JFactory::getDBO();
		if (count($userids) > 0)
		{
			foreach ($userids as $userid)
			{
				if ($userid > 0)
				{
					$result = SupportActions::ActionResult("groupAdd", array('group_id' => $groupid, 'user_id' => $userid), true);	
					if ($result === true)
					{
						$qry = "REPLACE INTO #__fss_ticket_group_members (group_id, user_id) VALUES ('".FSSJ3Helper::getEscaped($db, $groupid)."', '".FSSJ3Helper::getEscaped($db, $userid)."')";
						$db->setQuery($qry);
						$db->query($qry);
					} else {
						$messages[] = $result;
					}
				}
			}
		}
	
		$link = FSSRoute::_('index.php?option=com_fss&view=admin_groups&groupid=' . $groupid);
		if (count($messages) > 0)
			$link .= "&messages=" . implode("|", $messages);
		echo "<script>\n";
		echo "parent.location.href=\"$link\";\n";
		echo "</script>";	
		exit;
	}
	
	function RemoveUsers()
	{
		$userids = FSS_Input::getArrayInt('cid');
		$groupid = FSS_Input::getInt('groupid');
		
		if (!$this->canAdminGroup($groupid))
			return;

		$db	= JFactory::getDBO();
		if (count($userids) > 0)
		{
			foreach ($userids as $userid)
			{
				$qry = "DELETE FROM #__fss_ticket_group_members WHERE group_id ='".FSSJ3Helper::getEscaped($db, $groupid)."' AND user_id = '".FSSJ3Helper::getEscaped($db, $userid)."'";
				$db->setQuery($qry);
				$db->query($qry);
			}
		}
		
		$mainframe = JFactory::getApplication();
		$link = FSSRoute::_('index.php?option=com_fss&view=admin_groups&groupid=' . $groupid,false);
		$mainframe->redirect($link,JText::_('SEL_REMOVED'));
	}
	
	function SaveGroup($what)
	{
		$db	= JFactory::getDBO();

		//echo "Saving Group<br>";
		//print_p($_POST);
		//exit;
		
		$groupid = FSS_Input::getInt('groupid');
		$groupname = FSS_Input::getString('groupname');
		$description = FSS_Input::getString('description');
		$allemail = FSS_Input::getInt('allemail');
		$allsee = FSS_Input::getInt('allsee');
		$allprods = FSS_Input::getInt('allprods');
		$ccexclude = FSS_Input::getInt('ccexclude');
		
		if (!$this->canAdminGroup($groupid))
			return;
		$msg = "";		
		if ($groupid > 0)
		{	
			$msg = JText::_("GROUP_SAVED");			
			
			// saving existing group	
			$qry = "UPDATE #__fss_ticket_group SET ";
			$qry .= " groupname = '".FSSJ3Helper::getEscaped($db, $groupname)."', ";
			$qry .= " description = '".FSSJ3Helper::getEscaped($db, $description)."', ";
			$qry .= " allsee = '".FSSJ3Helper::getEscaped($db, $allsee)."', ";
			$qry .= " allprods = '".FSSJ3Helper::getEscaped($db, $allprods)."', ";
			$qry .= " allemail = '".FSSJ3Helper::getEscaped($db, $allemail)."', ";
			$qry .= " ccexclude = '".FSSJ3Helper::getEscaped($db, $ccexclude)."' ";
			$qry .= " WHERE id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
			$db->setQuery($qry);
			//echo $qry."<br>";
			$db->Query();
			
			// save products
		} else {
			$msg = JText::_("GROUP_CREATED");			
			// creating new group	
			$qry = "INSERT INTO #__fss_ticket_group (groupname, description, allsee, allprods, allemail, ccexclude) VALUES (";
			$qry .= " '".FSSJ3Helper::getEscaped($db, $groupname)."', ";
			$qry .= " '".FSSJ3Helper::getEscaped($db, $description)."', ";
			$qry .= " '".FSSJ3Helper::getEscaped($db, $allsee)."', ";
			$qry .= " '".FSSJ3Helper::getEscaped($db, $allprods)."', ";
			$qry .= " '".FSSJ3Helper::getEscaped($db, $allemail)."', ";
			$qry .= " '".FSSJ3Helper::getEscaped($db, $ccexclude)."') ";
			
			$db->setQuery($qry);
			$db->Query();
			//echo $qry."<br>";
			$groupid = $db->insertid();
			//echo "New ID : $groupid<br>";
		}
		
		// save products
		if ($groupid > 0)
		{
			$qry = "DELETE FROM #__fss_ticket_group_prod WHERE group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'"; 
			//echo $qry."<br>";
			$db->setQuery($qry);
			$db->Query();
					
			if (!$allprods)
			{
				// get a product list
				$products = $this->get('Products');	
				foreach($products as &$product)
				{
					$id = $product->id;
					$field = "prod_" . $id;
					$value = FSS_Input::getString($field,'');
					if ($value == "on")
					{
						$qry = "REPLACE INTO #__fss_ticket_group_prod (group_id, prod_id) VALUES ('".FSSJ3Helper::getEscaped($db, $groupid)."', '".FSSJ3Helper::getEscaped($db, $id)."')";
						//echo $qry."<br>";
						$db->setQuery($qry);
						$db->Query();
				}	
				}
			}
		}
		//exit;
		
		$mainframe = JFactory::getApplication();
		
		if ($what == "saveclose")
		{
			$link = FSSRoute::_('index.php?option=com_fss&view=admin_groups',false);
			
		} else {
			$link = FSSRoute::_('index.php?option=com_fss&view=admin_groups&groupid=' . $groupid,false);
		}
		$mainframe->redirect($link,$msg);
	}
	
	function CreateGroup()
	{
		if (!FSS_Permission::auth("fss.groups", "com_fss.groups"))
			return FSS_Admin_Helper::NoPerm();
			
		$this->creating = true;
		$this->group = new stdclass();
		$this->group->id = 0;
		$this->group->groupname = null;
		$this->group->description = null;
		$this->group->allsee = 0;
		$this->group->allemail = 0;
		$this->group->allprods = 1;
		$this->group->ccexclude = 0;
		
		$this->buildGroupEditForm();
		
		parent::display('group');	
	}
	
	function DeleteGroup()
	{
		$db	= JFactory::getDBO();

		//echo "Deleting Group";
		$groupid = FSS_Input::getInt('groupid');
		if (!$this->canAdminGroup($groupid))
			return;
		
		$qry = "DELETE FROM #__fss_ticket_group WHERE id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		$db->Query();
		
		$qry = "DELETE FROM #__fss_ticket_group_members WHERE group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		$db->Query();
		
		$qry = "DELETE FROM #__fss_ticket_group_prod WHERE group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		$db->Query();
		
		//exit;
		
		$mainframe = JFactory::getApplication();
		$link = FSSRoute::_('index.php?option=com_fss&view=admin_groups',false);
		$mainframe->redirect($link,JText::_('GROUP_DELETED'));
	}
}

