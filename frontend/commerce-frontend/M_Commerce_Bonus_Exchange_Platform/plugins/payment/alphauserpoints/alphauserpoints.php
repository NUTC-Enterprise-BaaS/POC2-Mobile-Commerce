<?php
/**
 *  @copyright  Copyright (c) 2009-2013 TechJoomla. All rights reserved.
 *  @license    GNU General Public License version 2, or later
 */
/** ensure this file is being included by a parent file */

defined( '_JEXEC' ) or die( 'Restricted access' );
//require_once JPATH_COMPONENT . DS . 'helper.php';
$lang = JFactory::getLanguage();
$lang->load('plg_payment_alphauserpoints', JPATH_ADMINISTRATOR);
require_once(dirname(__FILE__) . '/alphauserpoints/helper.php');
class plgpaymentalphauserpoints extends JPlugin
{
	var $_payment_gateway = 'payment_alphauserpoints';
	var $_log = null;

	function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		//Set the language in the class
		$config = JFactory::getConfig();


		//Define Payment Status codes in Authorise  And Respective Alias in Framework
		//1 = Approved, 2 = Declined, 3 = Error, 4 = Held for Review
		$this->responseStatus = array(
			'Success' =>'C',
			'Failure' =>'X',
			'ERROR'  => 'E',
		);
	}


	function buildLayoutPath($layout) {
		$app = JFactory::getApplication();
		$core_file 	= dirname(__FILE__) . '/' . $this->_name . '/tmpl/form.php';
		$override		= JPATH_BASE . '/' . 'templates' . '/' . $app->getTemplate()  . '/html/plugins/' . $this->_type . '/' . $this->_name . '/' . $layout.'.php';
		if(JFile::exists($override))
		{
			return $override;
		}
		else
		{
	  	return  $core_file;
	}
	}

	//Builds the layout to be shown, along with hidden f'Failure' =>'X',ields.
	function buildLayout($vars, $layout = 'default' )
	{

		// Load the layout & push variables
		ob_start();
        $layout = $this->buildLayoutPath($layout);
        include($layout);
        $html = ob_get_contents();
        ob_end_clean();
		return $html;
	}

	function onTP_GetHTML($vars)
	{
		$db = JFactory::getDBO();
		$api_AUP = JPATH_SITE . '/components/com_alphauserpoints';
		if ( file_exists($api_AUP))
		{
	 	$query = "SELECT points FROM #__alpha_userpoints where userid=".$vars->user_id;
		$db->setQuery($query);
		$user_points = $db->loadResult();
		$vars->user_points = $user_points;
		$vars->convert_val = $this->params->get('conversion');

		$html = $this->buildLayout($vars);
		return $html;
		}
	}

	function onTP_GetInfo($config)
	{

		if(!in_array($this->_name,$config))
		return;
		$obj 		= new stdClass;
		$obj->name 	= $this->params->get( 'plugin_name' );
		$obj->id	= $this->_name;
		return $obj;
	}

	//Adds a row for the first time in the db, calls the layout view
	function onTP_Processpayment($data,$vars)
	{
		$isValid = true;
		$error = array();
		$error['code']	= '';
		$error['desc']	= '';

		$db = JFactory::getDBO();
		$query = "SELECT points FROM #__alpha_userpoints where userid=".$data['user_id'];
		$db->setQuery($query);
		$points_count = $db->loadResult();
		$convert_val = $this->params->get('conversion');
		$points_charge = $data['total']*$convert_val;
		$payment_status = '';
		if($points_charge <= $points_count)
		{
			//$count = $points_count - $points_charge;
			$api_AUP = JPATH_SITE . '/components/com_alphauserpoints/helper.php';
			if ( file_exists($api_AUP))
			{
				require_once ($api_AUP);
				if(AlphaUserPointsHelper::newpoints($data['client'].'_aup', '','',JText::_("PUB_AD"), -$points_charge,true,'', JText::_("SUCCSESS")))
				{
					$payment_status = 'Success';
				}
				else
					$payment_status = 'Failure';
					$isValid = false;
			}
			else{
				$payment_status = 'Failure';
				$isValid = false;
			}
		}
		else
		{
			$payment_status = 'Failure';
		}

		//3.compare response order id and send order id in notify URL
		$res_orderid = '';
		$res_orderid = $data['order_id'];
		if($isValid ) {
			if(!empty($vars) && $res_orderid != $vars->order_id )
			{
				$payment_status = 'ERROR';
				$isValid = false;
				$error['desc'] = "ORDER_MISMATCH" . " Invalid ORDERID; notify order_is ". $vars->order_id .", and response ".$res_orderid;
			}
		}

		// amount check
		if($isValid ) {
			if(!empty($vars))
			{
				// Check that the amount is correct
				$order_amount = (float) $vars->amount;
				$retrunamount = (float)$data['total'];
				$epsilon = 0.01;

				if(($order_amount - $retrunamount) > $epsilon)
				{
					$payment_status = 'ERROR';
					$isValid = false;
					$error['desc'] = "ORDER_AMOUNT_MISTMATCH - order amount= ".$order_amount . ' response order amount = '.$retrunamount;
				}
			}
		}

		// TRANSLET RESPONSE
		$payment_status=$this->translateResponse($payment_status);


			$result = array('transaction_id'=>'',
    				'order_id'=>$data['order_id'],
						'status'=>$payment_status,
						'total_paid_amt'=>$data['total'],
						'raw_data'=>json_encode($data),
						'error'=>'',
						'return'=>$data['return'],
						);
    return $result;
  }
	function translateResponse($invoice_status){

    	foreach($this->responseStatus as $key=>$value)
				{
					if($key == $invoice_status)
					return $value;
				}
	}
	function onTP_Storelog($data)
	{
		$log_write = $this->params->get('log_write', '0');

		if($log_write == 1)
		{
			$log = plgPaymentAlphauserpointHelper::Storelog($this->_name,$data);
		}
	}
}
