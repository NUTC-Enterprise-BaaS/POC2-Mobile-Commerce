<?php
/*------------------------------------------------------------------------
 # JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

use Joomla\Registry\Registry;

class JTableusershop extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */

	protected $_jsonEncode = array('params', 'metadata');

	public function __construct(&$db)
	{
		parent::__construct('#__jbusinessdirectory_companies', 'id', $db);
	}
	public function store($updateNulls = false)
	{
		// Transform the params field
		if (is_array($this->params))
		{
			$registry = new Registry;
			$registry->loadArray($this->params);
			$this->params = (string) $registry;
		}

		$date   = JFactory::getDate()->toSql();
		$userId = JFactory::getUser()->id;

		// $this->modified = $date;

		if ($this->id)
		{
		}
		else
		{
			// New contact. A contact created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.
			if (!(int) $this->registerDate)
			{
				$this->registerDate = $date;
			}
		}

		// Store utf8 email as punycode
		$this->email = JStringPunycode::emailToPunycode($this->email);
		return parent::store($updateNulls);
	}

	public function check()
	{
		return true;
	}
	function setKey($k)
	{
		$this->_tbl_key = $k;
	}


	function getPackage($packageId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_points where id=".$packageId;
		$db->setQuery($query);
		// dump($query);
		return $db->loadObject();
	}

	function getCompanyPackage($companyId){
		$db =JFactory::getDBO();
		$query = "select p.*, group_concat(pf.feature) as featuresS
				  from #__jbusinessdirectory_packages p
				  inner join #__jbusinessdirectory_companies cp on cp.package_id=p.id
				  left join #__jbusinessdirectory_package_fields pf on p.id=pf.package_id
				  where p.status =1 and cp.id=$companyId
				  group by p.id " ;
		$db->setQuery($query);
		$package = $db->loadObject();
		if(isset($package)){
			$package->features = explode(",", $package->featuresS);
		}
		//dump($query);
		return $package;
	}

	function getPackages(){
		$db =JFactory::getDBO();
		$query = "select p.* , group_concat(pf.feature) as featuresS
					from #__jbusinessdirectory_packages p
					left join #__jbusinessdirectory_package_fields pf on p.id=pf.package_id
					where p.status =1
					group by p.id
					order by p.ordering asc";
		// 		dump($query);
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function changeState($pointId){
		$db =JFactory::getDBO();
		$query = 	" UPDATE #__jbusinessdirectory_users SET resetCount = IF(resetCount, 0, 1) WHERE id = ".$pointId ;
		$db->setQuery( $query );

		if (!$db->query()){
			return false;
		}
		return true;
	}

	function insertRelations($packageId, $features){
		$db =JFactory::getDBO();

		$query = "delete from #__jbusinessdirectory_package_fields where package_id = $packageId";
		$db->setQuery($query);
		if (!$db->query() )
		{
			echo 'INSERT / UPDATE sql STATEMENT error !';
			return false;
		}

		if(empty($features)){
			return;
		}

		$query = "insert into #__jbusinessdirectory_package_fields(package_id, feature) values ";
		foreach ($features as $feature){
			$query = $query."(".$packageId.",'".$db->escape($feature)."'),";
		}
		$query =substr($query, 0, -1);
		$query = $query." ON DUPLICATE KEY UPDATE package_id=values(package_id), feature=values(feature) ";

		$db->setQuery($query);
		if (!$db->query() )
		{
			echo 'INSERT / UPDATE sql STATEMENT error !';
			return false;
		}
	}

	function getLastPaidPackage($companyId){
		$db =JFactory::getDBO();
		$query = "select * , inv.id as invoice_id,  max(p.ordering)
				from #__jbusinessdirectory_packages p
				left join #__jbusinessdirectory_orders inv on p.id=inv.package_id
				where p.status=1 and inv.company_id=$companyId and inv.state = ".PAYMENT_STATUS_PAID."
				group by inv.company_id
				order by inv.start_date desc";
		$db->setQuery($query);
		return $db->loadObject();
	}

	function getLastPackage($companyId){
		$db =JFactory::getDBO();
		$query = "select inv.* , p.days, inv.start_date
				from #__jbusinessdirectory_orders inv
				inner join #__jbusinessdirectory_packages p on p.id=inv.package_id
				where p.status=1 and inv.company_id=$companyId
				order by inv.start_date desc, inv.id desc";
		$db->setQuery($query);
		return $db->loadObject();
	}


	function getCurrentActivePackage($companyId){
		$db =JFactory::getDBO();
		$query = "select * , inv.id as invoice_id,  max(p.ordering), GROUP_CONCAT(pf.feature) as featuresS
				from #__jbusinessdirectory_packages p
				left join #__jbusinessdirectory_orders inv on p.id=inv.package_id and inv.company_id=$companyId
				left join #__jbusinessdirectory_package_fields pf on p.id=pf.package_id
				where p.status=1 and ((inv.state= ".PAYMENT_STATUS_PAID." and (now() < (inv.start_date + INTERVAL p.days DAY) or (inv.package_id=p.id and p.days = 0))) or p.price=0)
				group by p.id
				order by p.price desc";

		$db->setQuery($query);
		$package = $db->loadObject();
		//dump($package);
		if(isset($package)){
			$package->features = explode(",", $package->featuresS);
		}

		return $package;
	}

	function getPackagePayment($companyId, $packageId){
		$db =JFactory::getDBO();
		$query = "select * , inv.id as invoice_id, max(p.ordering)
				from #__jbusinessdirectory_packages p
				left join #__jbusinessdirectory_orders inv on p.id=inv.package_id
				where p.status=1 and inv.company_id=$companyId and p.id=$packageId and inv.state = ".PAYMENT_STATUS_PAID." and DATE_ADD(inv.start_date, INTERVAL p.days DAY ) > now()
				group by p.id
				order by p.price desc";
		$db->setQuery($query);
		return $db->loadObject();

	}

	function getDefaultPackage(){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_packages where status = 1 order by ordering";
		$db->setQuery($query);

		return $db->loadObject();
	}

	function getFreePackage(){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_packages where status = 1 and price=0 order by ordering";
		$db->setQuery($query);

		return $db->loadObject();
	}

	function updateUnassignedCompanies($packageId){
		$db =JFactory::getDBO();
		$query = "update #__jbusinessdirectory_companies set package_id=$packageId where package_id not in ( select id from #__jbusinessdirectory_packages order by ordering)";
		$db->setQuery($query);
		//dump($query);
		return $db->query();
	}
}