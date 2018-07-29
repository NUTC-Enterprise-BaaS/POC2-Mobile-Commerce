<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

if (!defined("DS")) define('DS', DIRECTORY_SEPARATOR);

if (file_exists(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php'))
{
	
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php' );

	jimport('joomla.application.component.helper');


	// Load the base adapter.
	require_once JPATH_ADMINISTRATOR . '/components/com_finder/helpers/indexer/adapter.php';

	class plgFinderFSS_Glossary extends FinderIndexerAdapter
	{
		protected $context = 'FSS_Glossary';

		protected $extension = 'com_fss';

		protected $layout = 'glossary';

		protected $type_title = 'FSS_FINDER_FSS_GLOSSARY';

		protected $table = '#__fss_glossary';

		public function __construct(&$subject, $config)
		{
			parent::__construct($subject, $config);
			$this->loadLanguage();
		}

		protected function index(FinderIndexerResult $item, $format = 'html')
		{
			// Check if the extension is enabled
			if (JComponentHelper::isEnabled($this->extension) == false)
			{
				return;
			}

			$item->body = FinderIndexerHelper::prepareContent($item->getElement('body'));
			$item->summary = FinderIndexerHelper::prepareContent($item->getElement('body'));
			
			$item->addTaxonomy('Type', 'FSS_FINDER_GLOSSARY');
	
			$word = $item->title;
			$anchor = strtolower(preg_replace("/[^A-Za-z0-9]/", '-', $word));
			$letter = strtolower(substr($word,0,1));
			
			$item->url = 'index.php?option=com_fss&view=glossary&letter='.$letter.'#'.$anchor;
			
			$item->route = $item->url;
			$item->state = $item->published;

			// Get content extras.
			FinderIndexerHelper::getContentExtras($item);

			// Index the item.
			if (FSSJ3Helper::IsJ3())
			{
				$this->indexer->index($item);
			} else {
				FinderIndexer::index($item);
			}
		}

		protected function setup()
		{
			return true;
		}

		protected function getListQuery($sql = null)
		{
			$db = JFactory::getDbo();
			// Check if we can use the supplied SQL query.
			$sql = $sql instanceof JDatabaseQuery ? $sql : $db->getQuery(true);
			$sql->select('id, word as title, word as alias, description as body, published, access, language');
			$sql->where('published = 1');
			$sql->from('#__fss_glossary');
			
			return $sql;
		}
	}

}