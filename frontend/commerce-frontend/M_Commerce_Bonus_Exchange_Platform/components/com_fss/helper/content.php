<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.html.pagination');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'pagination.php');
require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'tickethelper.php' );

class FSS_ContentEdit extends FSSView
{
	var $has_added = 0;
	var $has_ordering = 0;
	var $has_modified = 0;
	var $has_created = 0;
	var $has_products = 0;
	var $has_author = 1;
	
	var $list_published = 1;
	var $list_added = 0;
	var $list_user = 1;
	
	var $rel_lookup_join = array();
	var $rel_lookup_filter = array();
	
	// stuff for JView::escape
	var $_escape = 'htmlspecialchars';
	var $_charset = 'UTF-8';
	
	var $added_related_js = 0;
	var $filters = array();
	
	function __construct()
	{
		
	}
	
	function Init()
	{
		if (empty($this->userid))
		{
			$this->tmplpath = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'tmpl'.DS.'content';	
			$user = JFactory::getUser();
			$userid = $user->get('id');
			$this->userid = $userid;
		}
	}
	
	function Display($tpl = NULL)
	{
		if (!FSS_Permission::auth("core.edit", $this->getAsset()) && !FSS_Permission::auth("core.edit.own", $this->getAsset()))
			return FSS_Admin_Helper::NoPerm();
		
		$this->Init();

		$db = JFactory::getDBO();
		$this->what = FSS_Input::getCmd('what','');
		
		$user = JFactory::getUser();
		$userid = $user->get('id');
		
		$this->viewurl = "";
		
		$return = FSS_Input::getString('return','');
		if ($return == 1)
		{
			JRequest::setVar('return',$_SERVER['HTTP_REFERER']);
		}	
		
		if ($this->what == "pick")
			return $this->HandlePick();
		
		if ($this->what == "author")
			return $this->HandleAuthor();
		
		if ($this->what == "publish" || $this->what == "unpublish")
			return $this->HandlePublish();
		
		if ($this->what == "cancel")
		{
			$mainframe = JFactory::getApplication();
			$link = FSSRoute::_('index.php?option=com_fss&view=admin_content&type=' . $this->id,false);
			$return = FSS_Input::getString('return','');
			if ($return && $return != 1)
				$link = $return;
			$mainframe->redirect($link);
		}
		
		if ($this->what == "save" || $this->what == "apply" || $this->what == "savenew")
		{
			return $this->Save();
		}
		
		if ($this->what == "new")
		{
			return $this->Create();
		}
		
		if ($this->what == "edit")
		{
			$this->item = $this->getSingle();
			$this->viewurl = $this->getArtLink();
			
			if (FSS_Permission::auth("core.edit", $this->getAsset()))
				$this->authorselect = $this->AuthorSelect($this->item);
			
			FSS_Helper::IncludeModal();
			
			$this->Output("form");
			return;
		}
		
		$this->GetListFilter();
		$this->data = $this->getList();

		$this->Output("list");
	}
	
	function Create()
	{
		if (!FSS_Permission::auth("core.create", $this->getAsset()))
			return FSS_Admin_Helper::NoPerm();
		
		$db = JFactory::getDBO();
		$item = array();
		$item['id'] = 0;
		
		$user = JFactory::getUser();
		$userid = $user->get('id');
		foreach ($this->edit as $edit)
		{
			$field = $this->GetField($edit);
			$item[$field->field] = $field->default;
				
			if ($field->more)
				$item[$field->more] = "";
				
			if ($field->type == "related")
			{
				$field->rel_ids = array();
				$field->rel_id_list = "";
					
				if (!$this->added_related_js)
					$this->AddRelatedJS();	
			} elseif ($field->type == "products")
			{
				$this->GetProducts();
					
				$field->products = array();
					
				$prodcheck = "";
				foreach($this->products as $product)
				{
					$prodcheck .= '<label class="checkbox">';
					$prodcheck .= "<input type='checkbox' name='{$field->field}_prod_" . $product->id . "' />" . $product->title;
					$prodcheck .= '</label>';

					//$prodcheck .= "<input type='checkbox' name='{$field->field}_prod_" . $product->id . "' />" . $product->title . "<br>";
				}
				$field->products_check = $prodcheck;
				$field->products_yesno = JHTML::_('select.booleanlist', $field->field, 
					array('class' => "inputbox",
							'size' => "1", 
							'onclick' => "DoAllProdChange('{$field->field}');"), $item[$field->field]);

			} 
		}
		$this->item = $item;
			
		if (FSS_Permission::auth("core.edit.state", $this->getAsset()))
		{
			$this->item['published'] = 0;
			$this->item['author'] = $userid;
		}
		
		if (FSS_Permission::auth("core.edit", $this->getAsset()))
		{
			$this->authorselect = $this->AuthorSelect($this->item);	
		}
		
		if (FSS_Input::getString("title"))
			$this->item['title'] = FSS_Input::getString("title");
		
		if (FSS_Input::getString("body"))
			$this->item['body'] = FSS_Input::getHTML('body', '');
		
		if (FSS_Input::getString("question"))
			$this->item['question'] = FSS_Input::getString("question");
		
		if (FSS_Input::getString("answer"))
			$this->item['answer'] = FSS_Input::getHTML('answer', '');

		$this->Output("form");
		return;	
	}
	
	function AuthorSelect(&$item)
	{
		if (FSS_Permission::auth("core.edit.state", $this->getAsset()))
		{
			if ($this->has_author)
			{
				$curauthor = $item['author'];
				
				$user = JFactory::getUser($curauthor);
				$authorname = $user->get('name');
				
				$result = "<b id='content_authname'>".$authorname."</b>&nbsp;&nbsp;<button class='btn btn-default btn-small' id='change_author'>".JText::_('CHANGE')."</button>";
				$result .= "<input name='author' type='hidden' id='content_author' value='{$curauthor}' />";
				return $result;	
			}
		} else {
			$curauthor = null;
			if (isset($item['author']))
				$curauthor = $item['author'];
				
			$user = JFactory::getUser($curauthor);
			return $user->get('name');
		}
		
		return "";
	}
	
	function Save()
	{
		$db = JFactory::getDBO();
		$this->item = array();
		$this->item['id'] = FSS_Input::getInt('id',0);
		$user = JFactory::getUser();
		$userid = $user->get('id');

		$this->errors = array();
		$ok = true;
			
		foreach ($this->edit as $edit)
		{	
			$field = $this->GetField($edit);
				
			$this->item[$field->field] = FSS_Input::getString($field->input_name,'');
			if ($field->type == "text")
				$this->item[$field->field] = FSS_Input::getHTML($field->input_name, '');	
				
			if ($field->more)
			{
				if (strpos($this->item[$field->field],"system-readmore") > 0)
				{
					$pos = strpos($this->item[$field->field],"system-readmore");
					$top = substr($this->item[$field->field], 0, $pos);
					$top = substr($top,0, strrpos($top,"<"));
						
					$bottom = substr($this->item[$field->field], $pos);
					$bottom = substr($bottom, strpos($bottom,">")+1);
						
					$this->item[$field->field] = $top;
					$this->item[$field->more] = $bottom;                           
				} else {
					$this->item[$field->more] = '';
				}
			}
				
			if ($field->required)
			{
				if ($this->item[$field->field] == "")
				{
					$ok = false;
					$this->errors[$field->field] = $field->required;	
				}	
			}
		}
		
		$now = FSS_Helper::CurDate();	
		// if errors
		if ($ok)
		{
				
			if ($this->item['id'])
			{
				$qry = "UPDATE " . $this->table . " SET ";
					
				$sets = array();
			
				foreach ($this->edit as $edit)
				{
					$field = $this->GetField($edit);
					
					if ($field->type != "related" && $field->type != "tags")
						$sets[] = $field->field . " = '" . FSSJ3Helper::getEscaped($db, $this->item[$field->field]) . "'";
					if ($field->more)
						$sets[] = "`".$field->more . "` = '" . FSSJ3Helper::getEscaped($db, $this->item[$field->more]) . "'";
				}

				if (FSS_Permission::auth("core.edit.state", $this->getAsset()))
				{
					$sets[] = "published = " . FSS_Input::getInt('published',0);	
					if ($this->has_author)
						$sets[] = "author = " . FSS_Input::getInt('author',0);
				}
					
				if ($this->has_modified)
					$sets[] = "modified = '{$now}'";
					
				$qry .= implode(", ", $sets);
					
				$qry .= " WHERE id = '". FSSJ3Helper::getEscaped($db, $this->item['id']) . "'";
			} else {
				$fieldlist = array();
				if ($this->has_author)
					$fieldlist[] = "author";
				if ($this->has_added)
					$fieldlist[] = "added";
					
				$setlist = array();
					
				foreach($this->edit as $edit)
				{
					$field = $this->GetField($edit);
						
					if ($field->type == "related" || $field->type == "tags")
						continue;

					$fieldlist[] = $field->field;	
					$setlist[] = "'" . FSSJ3Helper::getEscaped($db, $this->item[$field->field]) . "'";
					if ($field->more)
					{
						$fieldlist[] = "`".$field->more."`";	
						$setlist[] = "'" . FSSJ3Helper::getEscaped($db, $this->item[$field->more]) . "'";
					}
						
				}
					
				if ($this->has_modified)
				{
					$fieldlist[] = "modified";
					$setlist[] = "'{$now}'";	
					$fieldlist[] = "created";
					$setlist[] = "'{$now}'";	
				}
				$fieldlist[] = "published";
				if (FSS_Permission::auth("core.edit.state", $this->getAsset()))
				{
					$setlist[] = FSS_Input::getInt('published',0);	
				} else {
					$setlist[] = "0";	
				}
					
				if ($this->has_ordering)
				{
					// need to get ordering value
					$order = $this->GetOrderValue();
					if ($order < 1)
						$order = 1;
					$fieldlist[] = "ordering";
					$setlist[] = $order;
				}

				$qry = "INSERT INTO " . $this->table . " (" . implode(", ",$fieldlist) . ") VALUES (";
				
				if ($this->has_author)
					$qry .= "'$userid', ";
					
				if ($this->has_added)
					$qry .= "'{$now}', ";
					
				$qry .= implode(", ", $setlist) . ")";
			}

			$db->setQuery($qry);
			$db->query($qry);
				
			if (!$this->item['id'])
			{
				$this->item['id'] = $db->insertid();
			}
			$this->articleid = $this->item['id'];
				
				
			foreach($this->edit as $edit)
			{
				$field = $this->GetField($edit);
					
				// save any products fields
				if ($field->type == "products")
				{
					$this->GetProducts();
						
					$qry = "DELETE FROM {$field->prod_table} WHERE {$field->prod_artid} = '".FSSJ3Helper::getEscaped($db, $this->item['id'])."'";
					$db->setQuery($qry);
					//echo $qry."<br>";
					$db->query($qry);
						
					if (!$this->item[$field->field])
					{
						foreach ($this->products as &$product)
						{
							$pid = $product->id;
							$name = $field->field."_prod_" . $pid;
							$val = FSS_Input::getString($name);
							if ($val == "on")
							{
								$qry = "INSERT INTO {$field->prod_table} ({$field->prod_prodid}, {$field->prod_artid}) VALUES
									($pid, '".FSSJ3Helper::getEscaped($db, $this->item['id'])."')";
								$db->setQuery($qry);
								//echo $qry."<br>";
								$db->query($qry);
							}
						}
						//echo "Saving products<br>";
					}
					//echo "Prod Field";	
				} elseif ($field->type == "related")
				{
					// save related field	
					$relids = explode(":",$this->item[$field->field]);
						
					$qry1 = "DELETE FROM {$field->rel_table} WHERE {$field->rel_id} = '". FSSJ3Helper::getEscaped($db, $this->item['id']) . "'";
					$db->setQuery($qry1);
					//echo $qry1."<br>";
					$db->query();
						
					foreach($relids as $id)
					{
						$id = FSSJ3Helper::getEscaped($db, $id);
						$qry1 = "REPLACE INTO {$field->rel_table} ({$field->rel_id}, {$field->rel_relid}) VALUES ('". FSSJ3Helper::getEscaped($db, $this->item['id']) . "', '$id')";
						$db->setQuery($qry1);
						//echo $qry1."<br>";
						$db->query();
					}
						
				} else if ($field->type == "tags")
				{
					//print_p($field);
					//print_p($this->item);	
					
					$qry1 = "DELETE FROM {$field->tags_table} WHERE {$field->tags_key} = '". FSSJ3Helper::getEscaped($db, $this->item['id']) . "'";
					//echo $qry1 . "<br>";
					$db->setQuery($qry1);
					$db->query();
					
					$tags = explode("\n", $this->item[$field->field]);
					
					foreach ($tags as $tag)
					{
						$tag = trim($tag);
						if (!$tag) continue;
						$qry1 = "REPLACE INTO {$field->tags_table} ({$field->tags_key}, tag, language) VALUES (	'". FSSJ3Helper::getEscaped($db, $this->item['id']) . "', ";
						$qry1 .= "'". FSSJ3Helper::getEscaped($db, $tag) . "', '". FSSJ3Helper::getEscaped($db, $this->item['language']) . "')";
						//echo $qry1 . "<br>";
						$db->setQuery($qry1);
						$db->query();
					
					}
					
					//exit;
				}	
			}
				
			// need to check for a redirect field here
			$mainframe = JFactory::getApplication();
			if ($this->what == "apply")
			{
				$link = FSSRoute::_("index.php?option=com_fss&view=admin_content&type={$this->id}&what=edit&id={$this->articleid}",false);
			} elseif ($this->what == "savenew")
			{
				$link = FSSRoute::_("index.php?option=com_fss&view=admin_content&type={$this->id}&what=new",false);
			} else {
				$link = FSSRoute::_('index.php?option=com_fss&view=admin_content&type=' . $this->id,false);
				$return = FSS_Input::getString('return','');
				if ($return && $return != 1)
					$link = $return;
			}
			$mainframe->redirect($link,JText::_('ARTICLE_SAVED'));	
			return;		
				
		} else {
			// need to put onto the form the field stuff for related and products fields
			foreach($this->edit as $edit)
			{
				$field = $this->GetField($edit);
				if ($field->type == "related")
				{
					$field->rel_ids = array();
						
					$relids = FSS_Input::getString($field->field);
					$relateds = explode(":",$relids);
					foreach ($relateds as $related)
					{
						if ($related == 0) continue;
						$field->rel_ids[$related] = $related;
					}
						
					$field->rel_id_list = implode(":", $field->rel_ids);
						
					if (count($field->rel_ids) > 0)
					{
						$ids = array();
						foreach ($field->rel_ids as $id)
							$ids[] = FSSJ3Helper::getEscaped($db, $id);
						$qry = "SELECT {$field->rel_lookup_id}, {$field->rel_display} FROM {$field->rel_lookup_table} WHERE {$field->rel_lookup_id} IN (" . implode(", ", $ids) . ")";
	///					$qry = "SELECT {$field->rel_lookup_id}, {$field->rel_lookup_display} FROM {$field->rel_lookup_table} WHERE {$field->rel_lookup_id} IN (" . implode(", ", $field->rel_ids) . ")";
						$db->setQuery($qry);
						$relateds = $db->loadAssocList($field->rel_lookup_id);
						foreach ($relateds as $id => &$related)
							$field->rel_ids[$id] = $related[$field->rel_lookup_display];
					}
						
					if (!$this->added_related_js)
						$this->AddRelatedJS();							
				} else if ($field->type == "products")
				{
					$this->GetProducts();
						
					$field->products = array();
						
					$prodcheck = "";
					foreach($this->products as $product)
					{
						$prodform = FSS_Input::getString($field->field . "_prod_" . $product->id);
						if ($prodform == "on")
						{
							$prodcheck .= '<label class="checkbox">';
							$prodcheck .= "<input type='checkbox' name='{$field->field}_prod_" . $product->id . "' checked />" . $product->title;
							$prodcheck .= '</label>';
						} else {
							$prodcheck .= '<label class="checkbox">';
							$prodcheck .= "<input type='checkbox' name='{$field->field}_prod_" . $product->id . "' />" . $product->title;
							$prodcheck .= '</label>';
						}
					}
					$field->products_check = $prodcheck;
					$field->products_yesno = JHTML::_('select.booleanlist', $field->field, 
						array('class' => "inputbox",
								'size' => "1", 
								'onclick' => "DoAllProdChange('{$field->field}');"), $this->item[$field->field]);		
				}	
			}
				
			if (FSS_Permission::auth("core.edit.state", $this->getAsset()))
			{
				$this->item['published'] = FSS_Input::getInt('published',0);
				$this->item['author'] = FSS_Input::getInt('author',0);
			}
			
			if (FSS_Permission::auth("core.edit", $this->getAsset()))
			{
				$this->authorselect = $this->AuthorSelect($this->item);
			}
			
			$this->Output("form");
		}
			
		// if no errors, forward to list
		return;	
	}
	
	function Header()
	{
		// output the header stuff
		echo FSS_Helper::PageStyle();
		echo FSS_Helper::PageTitle("CONTENT_MANAGEMENT");

		include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php');
		include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_content'.DS.'snippet'.DS.'_contentbar.php');
	}
	
	function Footer()
	{
		include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php';
		echo FSS_Helper::PageStyleEnd();	
	}
	
	function AddField(&$field)
	{
		$this->fields[$field->field] = $field;	
	}

	function AddFilter(&$filter)
	{
		$this->filters[$filter->field] = $filter;	
	}
	
	function Output($file)
	{
		$this->Header();	
		include $this->tmplpath . DS . $file . ".php";
		$this->Footer();
	}
	
	function GetListFilter()
	{
		$db = JFactory::getDBO();
		$mainframe = JFactory::getApplication();

		// search - normal text search on set of fields
		$this->filter_values['search'] = $mainframe->getUserStateFromRequest($this->id."search","search","");
		$this->filter_values['order'] = $mainframe->getUserStateFromRequest($this->id."order","order","");
		$this->filter_values['order_dir'] = $mainframe->getUserStateFromRequest($this->id."order_dir","order_dir","ASC");
		
		$this->filter_html = array();
		
		// filters	
		
		// user
		if ($this->has_author)
		{
			$this->filter_values['userid'] = 0;
			if (FSS_Permission::auth("core.edit.state", $this->getAsset()))
			{
				$this->filter_values['userid'] = $mainframe->getUserStateFromRequest($this->id."userid","userid","");
				$qry = "SELECT id, username, name FROM #__users WHERE id IN (SELECT author FROM {$this->table}) ORDER BY name";
				$db->setQuery($qry);
				$users = array();
				$users[] = JHTML::_('select.option', '0', JText::_("SELECT_USER"), 'id', 'name');
				$users = array_merge($users, $db->loadObjectList());
				$this->filter_html['userid'] = JHTML::_('select.genericlist',  $users, 'userid', 'class="input-medium" onchange="document.fssForm.submit( );"', 'id', 'name', $this->filter_values['userid']);
			}
		}
		// published
		$this->filter_values['published'] = $mainframe->getUserStateFromRequest($this->id."ispublished","ispublished","");
		
		$published = array();
		$published[] = JHTML::_('select.option', '0', JText::_("IS_PUBLISHED"), 'id', 'title');
		$published[] = JHTML::_('select.option', '2', JText::_("PUBLISHED"), 'id', 'title');
		$published[] = JHTML::_('select.option', '1', JText::_("UNPUBLISHED"), 'id', 'title');
		$this->filter_html['published'] = JHTML::_('select.genericlist',  $published, 'ispublished', 'class="input-medium" onchange="document.fssForm.submit( );"', 'id', 'title', $this->filter_values['published']);
		
		// optional fields such as category
		
		foreach ($this->filters as $filter)
		{
			$this->filter_values[$filter->field] = $mainframe->getUserStateFromRequest($this->id.$filter->input_name,$filter->input_name,"");
			
			$filtervalues = array();
			
			if ($filter->source_table)
			{
				$qry = "SELECT * FROM {$filter->source_table} ORDER BY {$filter->source_order}";
				$db->setQuery($qry);
				$filtervalues = array_merge($filtervalues, $db->loadObjectList());
				
				if ($filter->nested)
				{
					require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'nested.php');
					$filtervalues = FSS_Nested_Helper::BuildNest($filtervalues, "id", "parcatid", "ordering");
					
					$df = $filter->source_display;
					
					foreach ($filtervalues as &$item)
					{
						$item->$df = str_repeat("|&mdash;&thinsp;", $item->level) . $item->$df;
					}
				}				
			}
			
			array_unshift($filtervalues, JHTML::_('select.option', '', JText::_($filter->source_header), $filter->source_id, $filter->source_display));
			
			if ($filter->extra)
			{
				foreach ($filter->extra as $key => $value)
				{
					$filtervalues[] = JHTML::_('select.option', $key, $value, $filter->source_id, $filter->source_display);
				}
			}
		
			$this->filter_html[$filter->field] = JHTML::_('select.genericlist',  $filtervalues, $filter->input_name, 'class="input-medium" onchange="document.fssForm.submit( );"', $filter->source_id, $filter->source_display, $this->filter_values[$filter->field]);
		}
	}
	
	function getList()
	{
		$db = JFactory::getDBO();
		$mainframe = JFactory::getApplication();
		
		$fields = array();
		$fields[] = "a.id";
		foreach($this->list as $list)
		{
			$field = $this->GetField($list);
			$fields[] = "a." . $field->field;
		}
		if ($this->has_added)
			$fields[] = "a.added";
		if ($this->has_ordering)
			$fields[] = "a.ordering";
		if ($this->has_modified)
			$fields[] = "a.modified";
		if ($this->has_created)
			$fields[] = "a.created";

		$fields[] = "a.published";
		if ($this->has_author)
		{
			$fields[] = "u.name";
			$fields[] = "u.username";
			$fields[] = "u.id as userid";
		}

		if ($this->has_author)
		{
			$qry = "SELECT " . implode(", ", $fields) . " FROM {$this->table} as a LEFT JOIN #__users as u ON a.author = u.id ";
		} else {
			$qry = "SELECT " . implode(", ", $fields) . " FROM {$this->table} as a ";
		}
		
		$where = array();
		
		if ($this->has_author && !FSS_Permission::auth("core.edit", $this->getAsset()))
			$where[] = "a.author = {$this->userid}";
		
		if ($this->filter_values['published'] > 0)
			$where[] = "a.published = ".FSSJ3Helper::getEscaped($db, $this->filter_values['published']-1);
		
		if ($this->has_author && $this->filter_values['userid'] > 0)
			$where[] = "a.author = ".FSSJ3Helper::getEscaped($db, $this->filter_values['userid']);
	
		if ($this->filter_values['search'] != "")
		{
			$search = array();
			foreach($this->fields as $field)
			{
				if ($field->search)
				{
					$search[] = "{$field->field} LIKE '%".FSSJ3Helper::getEscaped($db, $this->filter_values['search'])."%'";	
				}	
			}	
			
			if (count($search) > 0)
			{
				$where[] = "( " . implode(" OR ", $search) . " )";	
			}
		}
		
		
		foreach ($this->filters as $filter)
		{
			$value = $this->filter_values[$filter->field];
			if ($value)
			{
				$where[] = "a.{$filter->field} = '".FSSJ3Helper::getEscaped($db, $value) . "'";
			}
		}
		
		if (count($where) > 0)
			$qry  .= " WHERE " . implode(" AND ", $where);
		
		if ($this->filter_values['order'])
		{
			$qry .= " ORDER BY " . FSSJ3Helper::getEscaped($db, $this->filter_values['order']) . " " . FSSJ3Helper::getEscaped($db, $this->filter_values['order_dir']);
		} else {
			$qry .= " ORDER BY " . $this->order;
		}
		
		$this->filter_values['limitstart'] = FSS_Input::getInt("limit_start",0);
		$this->filter_values['limit'] = $mainframe->getUserStateFromRequest($this->id."limit_base","limit_base","20");
		
		$this->_pagination = new JPaginationAjax($this->_getListCount($qry), $this->filter_values['limitstart'], $this->filter_values['limit'] );

		$db->setQuery($qry, $this->filter_values['limitstart'], $this->filter_values['limit']);
		$this->data = $db->loadAssocList();
		
		return $this->data;
	}
	
	function _getListCount($query)
	{
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$db->query();

		return $db->getNumRows();
	}

	function getSingle()
	{
		if ($this->has_products)
			$this->GetProducts();
		
		// get a list of announcements, including pagination and filter
		$id = FSS_Input::getInt('id',0);
		
		$db = JFactory::getDBO();
		if ($this->has_author)
		{
			$qry = "SELECT a.*, u.name, u.username, u.id as userid FROM {$this->table} as a LEFT JOIN #__users as u ON a.author = u.id ";
		} else {
			$qry = "SELECT a.* FROM {$this->table} as a ";
		}
		
		$qry .= "WHERE a.id = '".FSSJ3Helper::getEscaped($db, $id)."'";
		
		$db->setQuery($qry);
		$row = $db->loadAssoc();		
		
		if (!FSS_Permission::auth("core.edit", $this->getAsset()) && $this->userid != $row['userid'])
		{
			return null;	
		}
		
		foreach($this->edit as $edit)
		{
			$field = $this->GetField($edit);
			
			if ($field->type == "products")
			{
				$qry = "SELECT {$field->prod_prodid} FROM {$field->prod_table} WHERE {$field->prod_artid} = '".FSSJ3Helper::getEscaped($db, $id)."'";
				$db->setQuery($qry);
				$field->products = $db->loadAssocList($field->prod_prodid);
				
				$prodcheck = "";
				foreach($this->products as $product)
				{
					$checked = false;
					if (array_key_exists($product->id,$field->products))
					{
						$prodcheck .= '<label class="checkbox">';
						$prodcheck .= "<input type='checkbox' name='{$field->field}_prod_" . $product->id . "' checked />" . $product->title;
						$prodcheck .= '</label>';
					} else {
						$prodcheck .= '<label class="checkbox">';
						$prodcheck .= "<input type='checkbox' name='{$field->field}_prod_" . $product->id . "' />" . $product->title;
						$prodcheck .= '</label>';
					}
				}
				$field->products_check = $prodcheck;
				$field->products_yesno = JHTML::_('select.booleanlist', $field->field, 
					array('class' => "inputbox",
							'size' => "1", 
							'onclick' => "DoAllProdChange('{$field->field}');"),
						intval($row[$field->field]));

			} else if ($field->type == "related")
			{
				$qry = "SELECT {$field->rel_relid} FROM {$field->rel_table} WHERE {$field->rel_id} = '".FSSJ3Helper::getEscaped($db, $id)."'";
				$db->setQuery($qry);
				$field->rel_ids = array();
				
				$relateds = $db->loadAssocList($field->rel_relid);
				foreach ($relateds as $id => &$related)
				{
					if ($id == 0) continue;
					$field->rel_ids[$id] = $id;
				}
				
				$field->rel_id_list = implode(":", $field->rel_ids);
				
				if (count($field->rel_ids) > 0)
				{
					$qry = "SELECT {$field->rel_lookup_id}, {$field->rel_display} FROM {$field->rel_lookup_table} WHERE {$field->rel_lookup_id} IN (" . implode(", ", $field->rel_ids) . ")";
					$db->setQuery($qry);
					$relateds = $db->loadAssocList($field->rel_lookup_id);
					foreach ($relateds as $id => &$related)
						$field->rel_ids[$id] = $related[$field->rel_display];
				}
				
				if (!$this->added_related_js)
					$this->AddRelatedJS();
			} else if ($field->type == "tags")
			{
				$qry = "SELECT * FROM {$field->tags_table} WHERE {$field->tags_key} = '".FSSJ3Helper::getEscaped($db, $id)."'"; 
				$db->setQuery($qry);
				$row['tags'] = array();
				$taglist = $db->loadAssocList();
				foreach ($taglist as $id => $tag)
				{
					$row['tags'][] = $tag['tag'];
				}
				
				$row['tags'] = implode("\n", $row['tags']);
			}
		}
		
		return $row;
	}
	
	function getArtLink()
	{
		$link = $this->link;
		$link = str_replace("%ID%",$this->item['id'],$link);
		return FSSRoute::_($link, false); // OK
	}
	
	function GetProducts()
	{
		if (empty($this->products))
		{
			$db = JFactory::getDBO();
			$query = "SELECT * FROM #__fss_prod WHERE published = 1 ORDER BY title";
			$db->setQuery($query);
			$this->products = $db->loadObjectList();
		}
	}
	
	function GetField($name)
	{
		if (array_key_exists($name, $this->fields))
			return $this->fields[$name];	
		
		return "";
	}
	
	function LookupInput($field, $item)
	{
		if (property_exists($field, "lookup_table") && $field->lookup_table)
		{
			$query = "SELECT *
				 FROM {$field->lookup_table} 
				 ORDER BY {$field->lookup_order}";
			$db	= JFactory::getDBO();
			$db->setQuery($query);

			$sections = $db->loadObjectList();
			
			if (!empty($field->lookup_nested) && $field->lookup_nested)
			{
				require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'nested.php');
				$sections = FSS_Nested_Helper::BuildNest($sections, "id", "parcatid", "ordering");
				
				$df = $field->lookup_title;
				
				foreach ($sections as &$temp)
				{
					$temp->$df = str_repeat("|&mdash;&thinsp;", $temp->level) . $temp->$df;
				}	
			}
		} else {
			$sections = array();
		}

		if (property_exists($field, 'lookup_extra'))
		{
			$extra = array();
			foreach ($field->lookup_extra as $key => $value)
			{
				$obj = new stdClass();
				$id = $field->lookup_id;
				$title = $field->lookup_title;
				
				$obj->$id = $key;
				$obj->$title = $value;
				
				$extra[] = $obj;
			}
			
			$sections = array_merge($extra, $sections);
		}

		return JHTML::_('select.genericlist',  $sections, $field->input_name, 'class="inputbox" size="1" ', $field->lookup_id, $field->lookup_title, $item[$field->field]);
	}
	
	function GetLookupValues($field)
	{
		$ids = array();
		
		$db	= JFactory::getDBO();
		foreach($this->data as &$item)
		{
			$ids[$item[$field->field]] = FSSJ3Helper::getEscaped($db, $item[$field->field]);	
		}
		
		$this->lookup_values[$field->field] = array();
		
		if (property_exists($field, "lookup_table") && $field->lookup_table)
		{
			$query = "SELECT {$field->lookup_id}, {$field->lookup_title}
				 FROM {$field->lookup_table} 
				 WHERE {$field->lookup_id} IN ('" . implode("', '", $ids) . "')
				 ORDER BY {$field->lookup_order}";
			$db->setQuery($query);

			$rows = $db->loadAssocList();
			
			$this->lookup_values[$field->field] = array();
			
			foreach ($rows as $row)
			{
				$this->lookup_values[$field->field][$row[$field->lookup_id]] = $row;
			}
		}
		if (property_exists($field, 'lookup_extra'))
		{
			$extra = array();
			foreach ($field->lookup_extra as $key => $value)
			{
				$obj = array();
				$obj[$field->lookup_id] = $key;
				$obj[$field->lookup_title] = $value;
				
				$this->lookup_values[$field->field][$key] = $obj;
			}
		}
	}
	
	function GetLookupText($field, $id)
	{
		if (empty($this->lookup_values))
			$this->lookup_values = array();
		
		if (!array_key_exists($field->field, $this->lookup_values))
			$this->GetLookupValues($field);
		
		if (array_key_exists($id, $this->lookup_values[$field->field]))
			return $this->lookup_values[$field->field][$id][$field->lookup_title];

		return $id;
	}
	
	function HandleAuthor()
	{
		$db	= JFactory::getDBO();
		$mainframe = JFactory::getApplication();
		if (!FSS_Permission::auth("core.edit.state", $this->getAsset()))
			exit;
		
		$asset = $this->getAsset();
			
		$users = SupportUsers::usersWithPerm($asset, "core.edit.own");
		$users2 = SupportUsers::usersWithPerm($asset, "core.edit");
		
		$uids = array();
		foreach ($users as $uid => $user)
			$uids[$uid] = (int)$uid;
		foreach ($users2 as $uid => $user)
			$uids[$uid] = (int)$uid;
		
		// build query
		$qry = "SELECT * FROM #__users";
		$where = array();
		$where[] = "id IN (" . implode(", ", $uids) . ")";
		
		$limitstart = FSS_Input::getInt('limitstart',0);
		$mainframe = JFactory::getApplication();
		$limit = $mainframe->getUserStateFromRequest('users.limit', 'limit', 10, 'int');
		$search = FSS_Input::getString('search','');
		
		$db	= JFactory::getDBO();
		
		if ($search != "")
		{
			$where[] = "(username LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%' OR name LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%' OR email LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%')";
		}
				
		if (count($where) > 0)
		{
			$qry .= " WHERE " . implode(" AND ", $where);	
		}

		
		// Sort ordering
		$qry .= " ORDER BY name ";
		
		
		// get max items
		//echo $qry."<br>";
		$db->setQuery( $qry );
		$db->query();
		$maxitems = $db->getNumRows();
			
		
		// select picked items
		$db->setQuery( $qry, $limitstart, $limit );
		$this->users = $db->loadObjectList();

		
		// build pagination
		$this->pagination = new JPaginationEx($maxitems, $limitstart, $limit );
		$this->search = $search;
				
		include $this->tmplpath . DS . "user.php";		
	}
	
	function HandlePick()
	{
		$db	= JFactory::getDBO();
		$mainframe = JFactory::getApplication();

		$f = FSS_Input::getString('field');
		$field = $this->GetField($f);
		
		$this->HandlePickFilter($field);
		
		$this->pick_field = $field->field;
		//print_p($field);
		
		// get data for form
		$qry = "SELECT ";
		$fields = array();
		foreach($field->rel_lookup_display as $fieldname => $finfo)
		{
			
			$fields[] = $fieldname . " as " . $finfo['alias'];	
		}
		$fields[] = $field->rel_lookup_table_alias . '.' . $field->rel_lookup_id;
		
		$qry .= implode(", ", $fields);
		
		$qry .= " FROM " . $field->rel_lookup_table . " AS " . $field->rel_lookup_table_alias;
		
		foreach($field->rel_lookup_join as $join)
		{
			$qry .= " LEFT JOIN {$join['table']} AS {$join['alias']} ON {$field->rel_lookup_table_alias}.{$join['source']} = {$join['alias']}.{$join['dest']} ";
		}
		
		$where = array();
		
		if ($this->filter_values['published'] > 0)
			$where[] = "a.published = ".FSSJ3Helper::getEscaped($db, $this->filter_values['published']-1);
		
		if ($this->filter_values['userid'] > 0)
			$where[] = "a.author = ".FSSJ3Helper::getEscaped($db, $this->filter_values['userid']);
		
		if ($this->filter_values['search'] != "")
		{
			$search = array();
			foreach($field->rel_lookup_search as $searchfield)
			{
				$search[] = "{$searchfield} LIKE '%".FSSJ3Helper::getEscaped($db, $this->filter_values['search'])."%'";	
			}	
			
			if (count($search) > 0)
			{
				$where[] = "( " . implode(" OR ", $search) . " )";	
			}
		}
		
		foreach ($this->filters as $filter)
		{
			$value = FSS_Input::getString($filter->field);
			if ($value > 0)
			{
				$where[] = "a.{$filter->field} = ".FSSJ3Helper::getEscaped($db, $value);
			}
		}

		if (count($where) > 0)
			$qry .= "WHERE " . implode(" AND ",$where);

		if ($this->filter_values['order'])
		{
			$qry .= " ORDER BY " . FSSJ3Helper::getEscaped($db, $this->filter_values['order']) . " " . FSSJ3Helper::getEscaped($db, $this->filter_values['order_dir']);
		} else {
			$qry .= " ORDER BY " . $this->order;
		}
		
		//echo $qry."<br>";
		$this->filter_values['limitstart'] = FSS_Input::getInt("limit_start",0);
		$this->filter_values['limit'] = $mainframe->getUserStateFromRequest($field->field."limit_base","limit_base","10");
		
		$this->_pagination = new JPaginationAjax($this->_getListCount($qry), $this->filter_values['limitstart'], $this->filter_values['limit'] );

		$db->setQuery($qry, $this->filter_values['limitstart'], $this->filter_values['limit']);
		$this->pick_data = $db->loadAssocList();

		$this->field = $field;
		
		include $this->tmplpath . DS . "related.php";
	}
	
	function HandlePickFilter($field)
	{
		$db = JFactory::getDBO();
		$mainframe = JFactory::getApplication();

		// search - normal text search on set of fields
		$this->filter_values['search'] = $mainframe->getUserStateFromRequest($field->field."search","search","");
		$this->filter_values['order'] = $mainframe->getUserStateFromRequest($field->field."order","order","");
		$this->filter_values['order_dir'] = $mainframe->getUserStateFromRequest($field->field."order_dir","order_dir","ASC");
		
		$this->filter_html = array();
		
		// filters	
		
		// user
		if ($this->has_author)
		{
			$this->filter_values['userid'] = 0;
			if (FSS_Permission::auth("core.edit.state", $this->getAsset()))
			{
				$this->filter_values['userid'] = $mainframe->getUserStateFromRequest($field->field."userid","userid","");
				$qry = "SELECT id, username, name FROM #__users WHERE id IN (SELECT author FROM {$this->table}) ORDER BY name";
				$db->setQuery($qry);
				$users = array();
				$users[] = JHTML::_('select.option', '0', JText::_("SELECT_USER"), 'id', 'name');
				$users = array_merge($users, $db->loadObjectList());
				$this->filter_html['userid'] = JHTML::_('select.genericlist',  $users, 'userid', 'class="input-medium" size="1" onchange="document.fssForm.submit( );"', 'id', 'name', $this->filter_values['userid']);
			}
		}
		// published
		$this->filter_values['published'] = $mainframe->getUserStateFromRequest($field->field."ispublished","ispublished","");
		
		$published = array();
		$published[] = JHTML::_('select.option', '0', JText::_("IS_PUBLISHED"), 'id', 'title');
		$published[] = JHTML::_('select.option', '2', JText::_("PUBLISHED"), 'id', 'title');
		$published[] = JHTML::_('select.option', '1', JText::_("UNPUBLISHED"), 'id', 'title');
		$this->filter_html['published'] = JHTML::_('select.genericlist',  $published, 'ispublished', 'class="input-medium" size="1" onchange="document.fssForm.submit( );"', 'id', 'title', $this->filter_values['published']);
		
		// optional fields such as category
		foreach ($field->filters as $filter)
		{
			$this->filter_values[$filter->field] = $mainframe->getUserStateFromRequest($field->field.$filter->field,$filter->field,"");
			
			$qry = "SELECT {$filter->source_id}, {$filter->source_display} FROM {$filter->source_table} ORDER BY {$filter->source_order}";
			$db->setQuery($qry);
			$filtervalues = array();
			
			$filtervalues[] = JHTML::_('select.option', '', JText::_($filter->source_header), $filter->source_id, $filter->source_display);
			$filtervalues = array_merge($filtervalues, $db->loadObjectList());
			
			$this->filter_html[$filter->field] = JHTML::_('select.genericlist',  $filtervalues, $filter->field, 'class="input-medium" size="1" onchange="document.fssForm.submit( );"', $filter->source_id, $filter->source_display, $this->filter_values[$filter->field]);
		}
	}
	
	function AddRelatedJS()
	{
		if ($this->added_related_js)
			return;
			
		$this->added_related_js = 1;	
	}
	
	function GetOrderValue()
	{
		$db	= JFactory::getDBO();
		$qry = "SELECT MAX(ordering)+1 as nextorder FROM {$this->table}";
		$db->setQuery($qry);
		$data = $db->loadObject();
		return $data->nextorder;		
	}
	
	function HandlePublish()
	{
		$id = FSS_Input::getInt('id',0);
		if ($id < 1)
			exit;
		
		if (FSS_Permission::auth("core.edit.state", $this->getAsset()))
		{
			$db	= JFactory::getDBO();
			
			$pub = 0;
			if ($this->what == "publish")	
				$pub = 1;
			
			$qry = "UPDATE {$this->table} SET published = $pub WHERE id = '".FSSJ3Helper::getEscaped($db, $id)."'";
			
			$db->setquery($qry);
			$db->query();
		}
		
		exit;
	}
	
	function getCounts()
	{
		$db	= JFactory::getDBO();
		$this->Init();
		
		$counts = array();
		$counts['user_pub'] = 0;
		$counts['user_unpub'] = 0;
		$counts['user_total'] = 0;
		$counts['pub'] = 0;
		$counts['unpub'] = 0;
		$counts['total'] = 0;
		
		// return published, unpublished and total counts for the user
		if ($this->has_author)
		{
			$qry = "SELECT count(*) as cnt, published FROM {$this->table} WHERE author = {$this->userid} GROUP BY published";
			$db->setQuery($qry);
			$c1 = $db->loadObjectList("published");
		
			if (array_key_exists(0, $c1))
			{
				$counts['user_unpub'] += $c1[0]->cnt;
				$counts['user_total'] += $c1[0]->cnt;
			}
			if (array_key_exists(1, $c1))
			{
				$counts['user_pub'] += $c1[1]->cnt;
				$counts['user_total'] += $c1[1]->cnt;
			}
		}
		
		if (FSS_Permission::auth("core.edit.state", $this->getAsset()))
		{
			$qry = "SELECT count(*) as cnt, published FROM {$this->table} GROUP BY published";
			$db->setQuery($qry);
			$c1 = $db->loadObjectList("published");
			
			if (array_key_exists(0, $c1))
			{
				$counts['unpub'] += $c1[0]->cnt;
				$counts['total'] += $c1[0]->cnt;
			}
			if (array_key_exists(1, $c1))
			{
				$counts['pub'] += $c1[1]->cnt;
				$counts['total'] += $c1[1]->cnt;
			}
		}
		
		// if has mod perm, then return total unpublished	
		
		return $counts;
	}
	
	function getAsset()
	{
		switch ($this->id)
		{
		case 'announce':	
			return "com_fss.announce";
		case 'faqs':	
			return "com_fss.faq";
		case 'kb':	
			return "com_fss.kb";
		case 'glossary':	
			return "com_fss.glossary";
		}	
		
		return "com_fss";
	}
	
	function EditPanel($item,$changeclass = true)
	{
		$this->Init();
		$this->changeclass = $changeclass;
		
		$output = "";
		
		if (FSS_Permission::auth("core.edit", $this->getAsset()) || (FSS_Permission::auth("core.edit.own", $this->getAsset()) && $item['author'] == $this->userid))
		{
			$label_type = "success";
			if (!$item['published'])
				$label_type = "warning";
			
			$output = "<div class='pull-right label label-". $label_type . "'>";
			$output .= "<a href='".FSSRoute::_("index.php?option=com_fss&view=admin_content&type={$this->id}&what=edit&id={$item['id']}&option=com_fss&return=1")."' class='fssTip' title='".JText::_('EDIT_ARTICLE')."'><img src='" . JURI::root( true ). "/components/com_fss/assets/images/edit.png' alt='Edit' /></a>";	
		
			if ($item['published'])
			{
				$tip = JText::_("CONTENT_PUB_TIP");;	
			} else {
				$tip = JText::_('CONTENT_UNPUB_TIP');
			}
			
			if (FSS_Permission::auth("core.edit.state", $this->getAsset())) 
			{
				$output .= '<a href="#" id="publish_' .  $item['id'] . '" class="fss_publish_button fssTip" state="' . $item['published'] .'" title="' .  $tip . '">';
				$output .= FSS_Helper::GetPublishedText($item['published'],true);
				$output .= '</a>';
			} else {
				$output .= str_replace("_16.png","_g_16.png",FSS_Helper::GetPublishedText($item['published']));
			}
			
			$output .= "</div>";
		}
		
		return $output;
	}
	
			
	static $artcounts;
	static function getArticleCounts()
	{
		if (empty(self::$artcounts))
		{
			self::$artcounts = array();
		
			$types = array();
			$types[] = "announce";
			$types[] = "faqs";
			$types[] = "kb";
			$types[] = "glossary";
		
			foreach($types as $type)
			{
				require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'content'.DS.$type.'.php');
				$class = "FSS_ContentEdit_$type";
				$content = new $class();
				self::$artcounts[$type] = array();	
				self::$artcounts[$type]['desc'] = $content->descs;
				self::$artcounts[$type]['id'] = $content->id;
				self::$artcounts[$type]['counts'] = $content->getCounts();
			}
		}
		
		return self::$artcounts;
	}
	
		
	function auth($item)
	{
		return FSS_Permission::auth($item, $this->getAsset());	
	}
}

class FSS_Content_Field
{
	var $field = "";
	var $type = "string";
	var $desc = "";
	var $link = 0;
	var $required = "";
	var $more = "";
	var $default = "";
	var $search = 0;
	var $filters = array();
	var $hide = false;
	var $show_pagebreak = 0;
	
	function __construct($field, $desc, $type = "string", $input_name = "")
	{
		$this->field = $field;
		$this->desc = $desc;
		$this->type = $type;
		$this->input_name = $input_name;
		if (!$this->input_name)
			$this->input_name = $field;
		
		$this->required = "You must enter a " . strtolower($desc);	
	}
	
	function AddFilter(&$filter)
	{
		$this->filters[$filter->field] = $filter;	
	}
}

class FSS_Content_Filter
{
	function __construct($field,$sourcd_id,$source_display,$source_table,$source_order,$header, $input_name = "", $extra = "",$nested = false)
	{
		$this->field = $field;
		$this->source_id = $sourcd_id;
		$this->source_display = $source_display;
		$this->source_table = $source_table;
		$this->source_order = $source_order;
		$this->source_header = JText::_($header);
		$this->input_name = $input_name;
		if (!$this->input_name)
			$this->input_name = $field;
		$this->extra = $extra;
		$this->nested = $nested;
	}
}