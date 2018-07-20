<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated b68ae69eff756abceb4d3c43642483d3
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
jimport('joomla.utilities.date');

require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'glossary.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'email.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'comments.php');


class FssViewKb extends FSSView
{
	var $toc = "";
	var $pages_header = "";
	var $pages_footer = "";
	
    function display($tpl = null)
    {
		if (!FSS_Permission::auth("fss.view", "com_fss.kb"))
			return FSS_Helper::NoPerm();	
		
		$mainframe = JFactory::getApplication();

  		require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'content'.DS.'kb.php');
		$this->content = new FSS_ContentEdit_KB();
		$this->content->Init();
		
		$this->pagetitle = "";
		
		$model = $this->getModel();
		$model->content = $this->content;
             
		$this->assign('search','');
		
		$what = FSS_Input::getCmd('what');
		
		// basic permissions on article stats stop people seeing stats when disabled
		if (!FSS_Settings::get( 'kb_show_views' ))
			$what = "";
		if ($what == "recent" && !FSS_Settings::get( 'kb_show_recent' ))
			$what = "";
		if ($what == "viewed" && !FSS_Settings::get( 'kb_show_viewed' ))
			$what = "";
		if ($what == "rated" && !FSS_Settings::get( 'kb_show_rated' ))
			$what = "";

		$user = JFactory::getUser();
		$userid = $user->id;
		/*$db = JFactory::getDBO();
		$query = "SELECT * FROM #__fss_user WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."'";
		$db->setQuery($query);
		$this->_permissions = $db->loadAssoc();*/
	
		$fileid = FSS_Input::getInt('fileid');            
		if ($fileid > 0)
			return $this->downloadFile();       

		FSS_Helper::IncludeModal();

		if ($what != "")
			return $this->showWhat();
		
		$search = FSS_Input::getString('kbsearch');     
		if ($search != "")
			return $this->searchArticles();
		
		$search = FSS_Input::getString('prodsearch');     
		if ($search != "")
			return $this->searchProducts();
		
		$kbartid = FSS_Input::getInt('kbartid');            
        if ($kbartid > 0)
        	return $this->showArt();       
        
		$catid = FSS_Input::getInt('catid');  
        if ($catid > 0)
       		return $this->showCat();       
        
		$prodid = FSS_Input::getInt('prodid');            
        if ($prodid > 0)
       		return $this->showProd();       

		$this->showMain();
    	
    }
    
    function showCat()
    {
        $mainframe = JFactory::getApplication();

    	$aparams = FSS_Settings::GetViewSettingsObj('kb');
		
		$this->assign('view_mode_cat', $aparams->get('cat_cat_mode','normal'));
	    $this->assign('view_mode', $aparams->get('cat_cat_arts','normal'));
	    $this->assign('cat_art_pages', $aparams->get('cat_art_pages',0));
	    $this->assign('cat_search', $aparams->get('cat_search',0));
		$this->assign('cat_desc', $aparams->get('cat_desc',0));
		
		$this->arts = $this->get("Arts");
	    $this->cat = $this->get("Cat");
		if (!$this->cat && FSS_Input::getInt('catid') > 0)
			return JError::raiseError(404, 'Category Not Found');
		
		$this->product = $this->get("Product");
		if (!$this->product && FSS_Input::getInt('prodid') > 0)
	   		return JError::raiseError(404, 'Product Not Found');
		
		$this->assign('curcatid',0);
		
        $pathway = $mainframe->getPathway();
        
		if ($this->product)
			$this->pagetitle = $this->product['title'] . " - ";
		$this->pagetitle .= $this->cat['title']; 
	    //$document = JFactory::getDocument();
	    //$document->setTitle(JText::_("KNOWLEDGE_BASE"). ' - ' . $this->product['title'] . " - " . $this->cat['title']);
        
		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'kb' )))	
			$pathway->addItem(JText::_('KNOWLEDGE_BASE'), FSSRoute::_( 'index.php?option=com_fss&view=kb' ) );

		$prodid = "";
        if ($this->product['title'])
        {
			$prodid = $this->product['id'];
			$pathway->addItem($this->product['title'], FSSRoute::_( '&catid=prodid=' . $this->product['id'] ) );// FIX LINK
		}
    
	    $cat = $this->cat;
		$max = 3;
		
		$selectedcatid = FSS_Input::getInt('catid');
		
		while ($cat['parcatid'] > 0 && $max-- > 0)
		{
			JRequest::setVar('catid', $cat['parcatid']);
			$cat = $this->get("Cat");
			$pathway->addItem($cat['title'], FSSRoute::_( "&kbartid=prodid={$prodid}&catid={$cat['id']}" ) );// FIX LINK
		}
		
		JRequest::setVar('catid', $selectedcatid);
		
		$this->base_url = "index.php?option=com_fss&view=kb&catid=" . $selectedcatid;

        $pathway->addItem($this->cat['title']);

		$pagination = $this->get('ArtPagination');
		$this->pagination = $pagination;
		$this->assign('limit',$this->get("ArtLimit"));

		$this->subcats = $this->get('SubCats');
			
		$this->cats = $this->get('CatsArts');
		$this->main_cat_colums = 1;
		$this->hide_choose = 1;
		$this->hide_no_arts = 1;
	
		//print_p($this->subcats);
		//print_p($this->cats);
		parent::display("cat");
	}
	
    function showWhat()
    {
        $mainframe = JFactory::getApplication();

    	$aparams = FSS_Settings::GetViewSettingsObj('kb');
	    $this->assign('view_mode', $aparams->get('cat_cat_arts','normal'));
	    $this->assign('cat_art_pages', $aparams->get('cat_art_pages',0));
	    $this->assign('cat_search', $aparams->get('cat_search',0));
		$this->assign('view_mode_cat', 'normal');
		$what = FSS_Input::getCmd('what'); 
		
	    $this->arts = $this->get("ArtsWhat");
	    $this->cat = $this->get("Cat");
		$this->product = $this->get("Product");
	    
		$this->assign('curcatid',0);
		
        $pathway = $mainframe->getPathway();
		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'kb' )))	
			$pathway->addItem(JText::_('KNOWLEDGE_BASE'), FSSRoute::_( 'index.php?option=com_fss&view=kb' ) );
        
		if ($what == "recent")
		{
			$this->title = JText::_("MOST_RECENT");
			$this->image = "mostrecent.png";
		}
		if ($what == "viewed")
		{
			$this->title = JText::_("MOST_VIEWED");
			$this->image = "mostviewed.png";
		}
		if ($what == "rated")
		{
			$this->title = JText::_("HIGHEST_RATED");
			$this->image = "highestrated.png";
		}
			
	    $document = JFactory::getDocument();
		
		$pagetitle = '';
		if ($this->product['title'])
			$pagetitle .= $this->product['title'] . " - ";
		if ($this->cat['title'])
			$pagetitle .= $this->cat['title'] . " - ";
		$pagetitle .= $this->title;
		
		$this->pagetitle = $pagetitle;
		
	    //$document->setTitle( $pagetitle );
        
        if ($this->product['title'])
        {
			$pathway->addItem($this->product['title'], FSSRoute::_( '&catid=what=prodid=' . $this->product['id'] ) );// FIX LINK
		}
        
        if ($this->cat['title'])
        {
			$pathway->addItem($this->cat['title'], FSSRoute::_( '&catid=' . $this->cat['id'] . '&what=prodid=' . $this->product['id'] ) );// FIX LINK
		}
        
		if (isset($this->title)) $pathway->addItem($this->title);

		$pagination = $this->get('ArtPagination');
		$this->pagination = $pagination;
		$this->assign('limit',$this->get("ArtLimit"));

		parent::display("what");
	}
	
	function showProd()
	{
        $mainframe = JFactory::getApplication();
        
	   	$aparams = FSS_Settings::GetViewSettingsObj('kb');
	    $this->assign('view_mode_cat', $aparams->get('prod_cat_mode','normal'));
	    $this->assign('view_mode', $aparams->get('prod_cat_arts','list'));
	    $this->assign('main_cat_colums', $aparams->get('prod_cat_colums',1));
	    $this->assign('prod_search', $aparams->get('prod_search',1));
		
		//if ($this->view_mode_cat != 'normal')
		//{
			$this->cats = $this->get("CatsArts");
		//} else {
		//	$this->cats = $this->get("Cats");
		//}
		$this->assign('curcatid',0);

		$this->product = $this->get("Product");

		if (!$this->product && FSS_Input::getInt('prodid') > 0)
	 		return JError::raiseError(404, 'Product Not Found');
	    
	    $document = JFactory::getDocument();
	    $document->setTitle(JText::_("KNOWLEDGE_BASE") .' - ' . $this->product['title']);

        $pathway = $mainframe->getPathway();
		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'kb' )))	
			$pathway->addItem(JText::_('KNOWLEDGE_BASE'), FSSRoute::_( 'index.php?option=com_fss&view=kb' ) );
        $pathway->addItem($this->product['title']);
		
		$this->arts = $this->get('UncatArts');
		
		$this->base_url = "index.php?option=com_fss&view=kb&prodid=" . $this->product['id'];
		//echo "Base : " . $this->base_url . "<br>";
		
		// check for a single category and no uncat arts
		if (FSS_Settings::get('kb_auto_open_single_cat'))
		{
			$cat_count = 0;
			$catid = -1;
			foreach ($this->cats as $cat)
			{
				if (  (array_key_exists("subcats",$cat) && count($cat['subcats']) > 0) || (array_key_exists("arts",$cat) && count($cat['arts'])) || $this->view_mode_cat == "normal" || FSS_Input::getInt('catid') == $cat['id'] )
				{
					// check products that the cat can be shown in
					$prodid = FSS_Input::getInt('prodid');
					$can_show = true;
					if ($prodid > 0)
					{
						$prodids = $cat['prodids'];
						if ($prodids != "")
						{
							$prodids = explode(";", $prodids);
							if (!in_array($prodid, $prodids))
								$can_show = false;
						}
					}	
				
					if ($can_show) 
					{
						$catid = $cat['id'];
						$cat_count ++;
					}
				}
			}

			// if so, redirect into that category
			if ($cat_count == 1 && count($this->arts) == 0)
			{
				$link = FSSRoute::x("&catid=" . $catid);
				return JFactory::getApplication()->redirect($link);
				//echo "Redirect to $link<br>";
			}
		}
		
		
        parent::display("prod");
	}
	
	function showArt()
	{
        $mainframe = JFactory::getApplication();
			
		$kbartid = FSS_Input::getInt('kbartid');
		
		$this->comments = new FSS_Comments('kb',$kbartid);
		$this->comments->PerPage(FSS_Settings::Get('kb_comments_per_page'));

		if ($this->comments->Process())
			return;			

		$this->assign( 'kb_rate', FSS_Settings::get( 'kb_rate' ));
		$this->assign( 'kb_comments', FSS_Settings::get( 'kb_comments' ));
		//$this->assign( 'kb_comments_captcha', FSS_Settings::get( 'kb_comments_captcha' ));
		//$this->assign( 'kb_comments_moderate', FSS_Settings::get( 'kb_comments_moderate' ));
		
		$this->base_url = "index.php?option=com_fss&view=kb&kbartid=" . $kbartid;

		$error = "";
		
		$rate = FSS_Input::getCmd('rate');   
        
        if ($rate != "")
        {	
            $this->RateArticle($kbartid, $rate);
            return;
		}   
		
        $this->setLayout("article");
        $this->art = $this->get("Article");
		
		if (!$this->art)
		{
			return JError::raiseError(404, 'Article Not Found');
 			return;		
		}
		
		$this->handlePages();
		
		if (FSS_Settings::get('kb_use_content_plugins'))
		{
			// apply plugins to article body
			$dispatcher	= JDispatcher::getInstance();
			JPluginHelper::importPlugin('content');
			$art = new stdClass;
			$art->text = $this->art['body'];
			$art->noglossary = 1;
			$this->params = $mainframe->getParams('com_fss');
			$results = $dispatcher->trigger('onContentPrepare', array ('com_fss.kb', & $art, &$this->params, 0));
			$results = $dispatcher->trigger('onContentBeforeDisplay', array ('com_fss.kb', & $art, &$this->params, 0));
			$this->art['body'] = $art->text;
		}
		
        $this->artattach = $this->get("ArticleAttach");
		$this->products = $this->get("Products");
		$this->applies = $this->get("AppliesTo");
		$this->related = $this->get("Related");
        
	    $document = JFactory::getDocument();
	    $document->setTitle(JText::_("KNOWLEDGE_BASE").' - ' . $this->art['title']);
	    
        $pathway = $mainframe->getPathway();
 		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'kb' )))	
			$pathway->addItem(JText::_('KNOWLEDGE_BASE'), FSSRoute::_( 'index.php?option=com_fss&view=kb' ) );
       
		$this->product = $this->get("Product");
		$prodid = "";
        if ($this->product['title'])
        {
			$prodid = $this->product['id'];
            $pathway->addItem($this->product['title'], FSSRoute::_( 'index.php?option=com_fss&view=kb&prodid=' . $this->product['id'] ) );
		}

		JRequest::setVar('catid', $this->art['kb_cat_id']);
	    $this->cat = $this->get("Cat");
		
		if ($this->art['kb_cat_id'] > 0 && !$this->cat)
		{
			// unable to load the cat, so 404!	
			return JError::raiseError(404, 'Article Not Found');
		}
		
        if ($this->cat['title'])
        {
			$cat = $this->cat;
			$max = 3;
			while ($cat['parcatid'] > 0 && $max-- > 0)
			{
				JRequest::setVar('catid', $cat['parcatid']);
				$cat = $this->get("Cat");
				$pathway->addItem($cat['title'], FSSRoute::_( "index.php?option=com_fss&view=kb&prodid={$prodid}&catid={$cat['id']}" ) );
			}
			//print_p($this->cat);
            $pathway->addItem($this->cat['title'], FSSRoute::_( "index.php?option=com_fss&view=kb&prodid={$prodid}&catid={$this->cat['id']}" ) );
		}
        $pathway->addItem($this->art['title']);

		// update views
		if ($this->art['id'])
		{
			$db = JFactory::getDBO();
			$query = "UPDATE #__fss_kb_art SET views = views + 1 WHERE id = " . FSSJ3Helper::getEscaped($db, $this->art['id']);
			$db->setQuery($query);
			$db->Query();	
		}
		
		if (FSS_Input::getCmd('only') == "article")
		{
			parent::display('only');
			exit;
		} else {
			parent::display();    
		}
	}
	
	function handlePages()
	{
		$content = $this->art['body'];
		$limitstart = FSS_Input::getInt('page');
		
		$regex = '#<hr(.*)class="system-pagebreak"(.*)(\/)*>#iU';
		
		if (JString::strpos($content, 'class="system-pagebreak') === false) {
			return true;
		}

		$matches = array();
		preg_match_all($regex, $content, $matches, PREG_SET_ORDER);

		//print_p($matches);
		
		$this->buildTOC($matches);


		// showing all pages
		if ($limitstart == -1)
			return;
		
		$text = preg_split($regex, $content);
		
		$this->pages_header = "<div class='pagenavcounter'>" . JText::sprintf("JLIB_HTML_PAGE_CURRENT_OF_TOTAL", ($limitstart+1), count($text)) . "</div>";		
		$this->pages_footer .= "<div class='pagination'><ul><li>";
		if ($limitstart > 0)
		{
			$this->pages_footer .= "<a href='". FSSRoute::X("&page=" . ($limitstart - 1) ) . "'>&lt;&lt; ";
			$this->pages_footer .= JText::_("JPREV");
			$this->pages_footer .= "</a>";
		} else {
			$this->pages_footer .= JText::_("JPREV");
		}
		$this->pages_footer .= "</li><li>";
		if ($limitstart < count($text) - 1)
		{
			$this->pages_footer .= "<a href='". FSSRoute::X("&page=" . ($limitstart+1) ) . "'>";
			$this->pages_footer .= JText::_("JNEXT");
			$this->pages_footer .= " &gt;&gt;</a>";
		} else {
			$this->pages_footer .= JText::_("JNEXT");
		}
		$this->pages_footer .= "</li></ul></div>";

		$this->art['body'] = $text[$limitstart];
	}
	
	function buildTOC($matches)
	{
		$this->toc .= "<div id='article-index' class='article-index'>";
		
		$limitstart = FSS_Input::getInt('page');
		
		$class = ($limitstart === 0) ? 'toclink active' : 'toclink';
		$this->toc .= '<ul class="nav nav-tabs nav-stacked">
		<li>

			<a href="'. FSSRoute::X("&page=0") .'" class="'.$class.'">'
			. $this->art['title'] .
			'</a>

		</li>
		';		
		
		$i = 2;

		foreach ($matches as $bot) {
			$link = FSSRoute::X("&page=" . ($i-1));

			if (@$bot[0]) {
				$attrs2 = JUtility::parseAttributes($bot[0]);

				if (@$attrs2['alt']) {
					$title	= stripslashes($attrs2['alt']);
				} elseif (@$attrs2['title']) {
					$title	= stripslashes($attrs2['title']);
				} else {
					$title	= JText::sprintf('PLG_CONTENT_PAGEBREAK_PAGE_NUM', $i);
				}
			} else {
				$title	= JText::sprintf('PLG_CONTENT_PAGEBREAK_PAGE_NUM', $i);
			}
			$class = ($limitstart == $i-1) ? 'toclink active' : 'toclink';
			$this->toc .= '
				<li>
					<a href="'. $link .'" class="'.$class.'">'
				. $title .
				'</a>

				</li>
				';
			$i++;
		}
		
		// all pages entry
		$lang = JFactory::getLanguage();
		$lang->load('plg_content_pagebreak', JPATH_ADMINISTRATOR, null, false, true);


		$class = ($limitstart == -1) ? 'toclink active' : 'toclink';
		$this->toc .= '
				<li>
					<a href="'. FSSRoute::X("&page=-1") .'" class="'.$class.'">'
			. JText::_("PLG_CONTENT_PAGEBREAK_ALL_PAGES") .
			'</a>

				</li>
				';
		
		$this->toc .= '</ul></div>';
	}
	
	function removeEmptyProducts()
	{
		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__fss_kb_art WHERE allprods = 1 AND published = 1 AND ";
		$qry .= "access IN (" . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ") LIMIT 1";

		$db->setQuery($qry);
		$test = $db->loadObjectList();

		if ($test)
			return;

		$qry = "SELECT p.prod_id FROM #__fss_kb_art_prod as p LEFT JOIN #__fss_kb_art as a ON a.id = p.kb_art_id GROUP BY p.prod_id";
		$db->setQuery($qry);
		$prod_ids = $db->loadObjectList('prod_id');

		$res = array();
		foreach ($this->products as $product)
		{
			if (array_key_exists($product['id'], $prod_ids))
			{
				$res[] = $product;
			}
		}


		$this->products = $res;
	}

	function showMain()
	{
        $mainframe = JFactory::getApplication();
        
		$this->assign('curcatid',0);
		$this->products = $this->get("Products");
		
		$this->removeEmptyProducts();

		if (count($this->products) == 0)
		{
			return $this->showProd();
		} else if (count($this->products) == 1)
		{
			JRequest::setVar('prodid',$this->products[0]['id']);
			return $this->showProd();
		}
		
        $pathway = $mainframe->getPathway();
        if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'kb' )))	
			$pathway->addItem(JText::_('KNOWLEDGE_BASE'), FSSRoute::_( 'index.php?option=com_fss&view=kb' ) );
        
		$this->base_url = "index.php?option=com_fss&view=kb";

		// default view, show prods and/or cats
		$aparams = FSS_Settings::GetViewSettingsObj('kb');
        $this->assign('main_show_prod', $aparams->get('main_show_prod',1));
        $this->assign('main_show_cat', $aparams->get('main_show_cat',0));
        $this->assign('main_show_sidebyside', $aparams->get('main_show_sidebyside',0));
        $this->assign('main_show_search', $aparams->get('main_show_search',0));
        
        $this->assign('main_prod_colums', $aparams->get('main_prod_colums',1));
        $this->assign('main_prod_search', $aparams->get('main_prod_search',1));
        $this->assign('main_prod_pages', $aparams->get('main_prod_pages',0));
        
        $this->assign('view_mode_cat', $aparams->get('main_cat_mode','normal'));
        $this->assign('view_mode', $aparams->get('main_cat_arts','normal'));
        $this->assign('main_cat_colums', $aparams->get('main_cat_colums',1));
			
		$this->arts = array();
					
		if ($this->main_show_cat)
		{
			if ($this->view_mode_cat != 'normal')
			{
				$this->cats = $this->get("CatsArts");
			} else {
				$this->cats = $this->get("Cats");
			}
			
			$this->arts = $this->get('UncatArts');
		}
		
		$pagination = $this->get('ProdPagination');
		$this->pagination = $pagination;
		$this->assign('limit',$this->get("ProdLimit"));
        parent::display();		
	}
    
    function RateArticle($kbartid, $rate)
    {
    	if ($kbartid < 1)
    		return;
    	
        $db = JFactory::getDBO();
		
    	$query = 'SELECT id, rating, ratingdetail FROM #__fss_kb_art WHERE id = "' . FSSJ3Helper::getEscaped($db, $kbartid) . '"';
        $db->setQuery($query);
        $row = $db->loadAssoc();
        
        $rating = $row['rating'];
        $ratingdetail = $row['ratingdetail'];
        list($rating_up,$rating_same,$rating_down) = explode("|",$ratingdetail);
        
        if ($rating == "") $rating = 0;
        if ($rating_up == "") $rating_up = 0;
        if ($rating_same == "") $rating_same = 0;
        if ($rating_down == "") $rating_down = 0;
        
        if ($rate == "up")
        {
        	$rating++;
        	$rating_up++;	
		} else if ($rate == "same")
		{
			$rating_same++;
		} else if ($rate == "down")
		{
			$rating--;
			$rating_down++;
		}
		
		$ratingdetail = implode("|",array($rating_up,$rating_same,$rating_down));
		
		$query = 'UPDATE #__fss_kb_art SET rating = "' . FSSJ3Helper::getEscaped($db, $rating) . '", ratingdetail = "' . FSSJ3Helper::getEscaped($db, $ratingdetail) . '" WHERE id = "' . FSSJ3Helper::getEscaped($db, $kbartid) . '"';
		$db->setQuery($query);$db->Query();
	}
	
	function downloadFile()
	{
		$fileid = FSS_Input::getInt('fileid');            
		
        $db = JFactory::getDBO();
    	$query = 'SELECT * FROM #__fss_kb_attach WHERE id = "' . FSSJ3Helper::getEscaped($db, $fileid) . '"';
        $db->setQuery($query);
        $row = $db->loadAssoc();
        
        
		$filename = FSS_Helper::basename($row['filename']);
	    $file_extension = strtolower(substr(strrchr($filename,"."),1));
	    $ctype = FSS_Helper::datei_mime($file_extension);
		
		if (ob_get_level() > 0)
			ob_end_clean();

		$file = JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'kb'.DS.$row['diskfile'];

		if (!file_exists($file))
			$file = JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.$row['diskfile'];

		if (!file_exists($file))
		{
			echo "File not found - " . $row['diskfile']  . "<br />";
			echo "Paths Tested:<br />";
			echo "<ul>";
			echo "<li>" . JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.'kb' . "</li>";
			echo "<li>" . JPATH_SITE.DS.FSS_Helper::getAttachLocation() . "</li>";
			echo "</ul>";
			return;
		}

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
	    //echo $file;
	    readfile($file);
	    exit;
  	}
  	
  	function searchProducts()
  	{
        $mainframe = JFactory::getApplication();
        $aparams = FSS_Settings::GetViewSettingsObj('kb');
		$this->assign('main_prod_pages', $aparams->get('main_prod_pages',0));
		
		$pagination = $this->get('ProdPagination');
		$this->pagination = $pagination;
		
		$search = FSS_Input::getString('prodsearch');  
  		
		$this->prodsearch = $search;
		$this->results = $this->get("Products");
		
		FSS_Helper::allowBack();

		parent::display("search"); 
		exit;
	} 	
	
  	function searchArticles()
	{
		$mainframe = JFactory::getApplication();
		$aparams = FSS_Settings::GetViewSettingsObj('kb');
		
		$search = FSS_Input::getString('kbsearch', '');  
		$prodid = FSS_Input::getInt('prodid');  
		$catid = FSS_Input::getInt('catid');  
		$this->assign('cat_art_pages', $aparams->get('cat_art_pages',0));
		
		$search = FSS_Input::getString('kbsearch');  
		$this->assign('view_mode', $aparams->get('cat_cat_arts'));
		
		$document = JFactory::getDocument();
		$document->setTitle(JText::_("KNOWLEDGE_BASE") .' - ' . JText::_("SEARCH_RESULTS"));
		
		$pagination = $this->get('ArtPaginationSearch');
		$this->pagination = $pagination;
		$this->assign('limit',$this->get("ArtLimit"));
		
		$this->product = $this->get("Product");
		$this->cat = $this->get("Cat");
		
		$this->results = $this->get("ArtsWhat");
		$this->search = $search;
		
		$pathway = $mainframe->getPathway();
		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'kb' )))	
			$pathway->addItem(JText::_('KNOWLEDGE_BASE'), FSSRoute::_( 'index.php?option=com_fss&view=kb' ) );
		$pathway->addItem(JText::_("SEARCH_RESULTS"));
		
		FSS_Helper::allowBack();

		parent::display("kbsearch");  	

	} 

	function notEnoughArticles()
	{
		if (FSS_Settings::get('search_extra_like')) return false;
		
		$db = JFactory::getDBO();
		$sql = "SELECT count(*) as cnt FROM #__fss_kb_art";
		$db->setQuery($sql);
		$result = $db->loadObject();
		if ($result->cnt < 4) return true;
		
		return false;
	}
}
