<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

jimport( 'joomla.application.component.model' );
require_once(JPATH_COMPONENT . DS . 'helper.php');

/*
 * Indexer Model
 * @package    socialads
 * @subpackage Models
 */
class socialadsModelIndexer extends JModelLegacy
{
	function makeIndexing($indexlimitstart,$indexlimit,$pkey)
	{
		$indexdate=date('Y-m-d H:i:s');
		$child_table1='#__finder_links_terms';
		$master_table='#__finder_terms';
		$link_table='#__finder_links';

		$db  = JFactory::getDBO();
		if($indexlimitstart==0)
		{
			$query = 'DELETE FROM #__ad_contextual_terms';
			$db->setQuery($query);
			$db->execute();
		}

		$flag=0;
			$query = "SELECT link_id FROM $link_table    ORDER BY  link_id 	LIMIT $indexlimitstart,$indexlimit ";

			$db->setQuery($query);
			$links= $db->loadobjectlist();
			//print_r($links);
			if(empty($links))
			$flag=1;
			foreach($links as $link)
			{

			$linkid=$link->link_id;
		for($i=0;$i<=9;$i++)
		{
			$child_table=$child_table1.$i;

			$query = "SELECT child.*,master.term FROM $child_table as `child`  INNER JOIN $master_table as `master` on master.term_id=child.term_id WHERE  master.term<>'' AND child.link_id=$linkid";

			$db->setQuery($query);
			$terms= $db->loadobjectlist();
			//print_r($terms);
			foreach($terms as $termarr)
			{
				$term = new stdClass;
				$term->indexdate=$indexdate;
				foreach($termarr as $key=>$value)
				{

					$term->$key =trim($value);

					if(!empty($term->term))
					if($db->insertObject('#__ad_contextual_terms', $term))
					{

					}


				}

		}
		}


		}
		if($flag==1)
		{
			echo "Indexing Done successfully Data Stored in #__ad_contextual_terms table.";

			die;
		}

		else{
				global $mainframe;

				$newindexlimitstart=$indexlimit;
				$newindexlimit=$indexlimit+20;
				$mainframe = JFactory::getApplication();
				echo "Indexing From #__finder_links Links starts from  $indexlimitstart to $indexlimit in #__ad_contextual_terms table.";
//				echo	$url=JRoute::_(JUri::root().'index.php?option=com_socialads&controller=indexer&task=makeIndexing&indexlimitstart='.$newindexlimitstart.'&indexlimit='.$newindexlimit.'&pkey='.$pkey,false);
				echo	$url=JRoute::_('index.php?option=com_socialads&&task=indexer.makeIndexing&indexlimitstart='.$newindexlimitstart.'&indexlimit='.$newindexlimit.'&pkey='.$pkey, false);

				sleep(2);
				$mainframe->redirect($url);
		}


	}

}
