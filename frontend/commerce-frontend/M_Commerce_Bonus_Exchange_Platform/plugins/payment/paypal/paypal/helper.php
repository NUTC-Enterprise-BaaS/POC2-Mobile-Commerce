<?php
/**
 * @copyright  Copyright (c) 2009-2013 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2, or later
 */
defined('_JEXEC') or die(';)');
jimport('joomla.html.html');
jimport('joomla.plugin.helper');

/**
 * PlgPaymentBycheckHelper
 *
 * @package     CPG
 * @subpackage  site
 * @since       2.2
 */
class PlgPaymentPaypalHelper
{
	// Gets the paypal URL
	/**
	 * buildPaypalUrl.
	 *
	 * @param   string  $secure  Layout name
	 *
	 * @since   2.2
	 *
	 * @return   string  secure
	 */

	public function buildPaypalUrl($secure = true)
	{
		$plugin = JPluginHelper::getPlugin('payment', 'paypal');
		$params = json_decode($plugin->params);
		$url    = $params->sandbox ? 'www.sandbox.paypal.com' : 'www.paypal.com';
		$url    = 'https://' . $url . '/cgi-bin/webscr';

		return $url;
	}

	/**
	 * Store log
	 *
	 * @param   string  $name     name.
	 *
	 * @param   array   $logdata  data.
	 *
	 * @since   1.0
	 * @return  list.
	 */
	public function Storelog($name, $logdata)
	{
		jimport('joomla.error.log');
		$options = "{DATE}\t{TIME}\t{USER}\t{DESC}";
		$my      = JFactory::getUser();

		JLog::addLogger(
			array(
				'text_file' => $logdata['JT_CLIENT'] . '_' . $name . '.log',
				'text_entry_format' => $options
			),
			JLog::INFO,
			$logdata['JT_CLIENT']
		);
		$logEntry       = new JLogEntry('Transaction added', JLog::INFO, $logdata['JT_CLIENT']);
		$logEntry->user = $my->name . '(' . $my->id . ')';
		$logEntry->desc = json_encode($logdata['raw_data']);

		JLog::add($logEntry);

		//    $logs = &JLog::getInstance($logdata['JT_CLIENT'].'_'.$name.'.log',$options,$path);
		//  $logs->addEntry(array('user' => $my->name.'('.$my->id.')','desc'=>json_encode($logdata['raw_data'])));
	}

	/**
	 * validateIPN.
	 *
	 * @param   string  $data  data
	 *
	 * @since   2.2
	 *
	 * @return   string  data
	 */
	public function validateIPN($data)
	{
		// Parse the paypal URL
		$url              = self::buildPaypalUrl();
		$this->paypal_url = $url;
		$url_parsed       = parse_url($url);

		// Generate the post string from the _POST vars aswell as load the

		// _POST vars into an arry so we can play with them from the calling

		// Script.

		// Append ipn command

		// Open the connection to paypal
		$fp = fsockopen($url_parsed['host'], "80", $err_num, $err_str, 30);

		// $fp = fsockopen ($this->paypal_url, 80, $errno, $errstr, 30);

		if (!$fp)
		{
			// Could not open the connection.  If loggin is on, the error message

			// Will be in the log.
			$this->last_error = "fsockopen error no. $errnum: $errstr";
			self::log_ipn_results(false);

			return false;
		}
		else
		{
			$post_string = '';

			foreach ($data as $field => $value)
			{
				$this->ipn_data["$field"] = $value;
				$post_string .= $field . '=' . urlencode(stripslashes($value)) . '&';
			}

			$post_string .= "cmd=_notify-validate";

			// Post the data back to paypal
			fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n");
			fputs($fp, "Host: $url_parsed[host]\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: " . strlen($post_string) . "\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $post_string . "\r\n\r\n");

			// Loop through the response from the server and append to variable
			while (!feof($fp))
			{
				$this->ipn_response .= fgets($fp, 1024);
			}

			fclose($fp);

			// Close connection
		}

		if (preg_match("/verified/i", $post_string))
		{
			// Valid IPN transaction.
			self::log_ipn_results(true);

			return true;
		}
		else
		{
			// Invalid IPN transaction.  Check the log for details.
			$this->last_error = 'IPN Validation Failed.';
			self::log_ipn_results(false);

			return false;
		}
	}

	/**
	 * log_ipn_results.
	 *
	 * @param   string  $success  success
	 *
	 * @since   2.2
	 *
	 * @return   string  success
	 */
	public function log_ipn_results($success)
	{
		if (!$this->ipn_log)
		{
			return;
		}

		// Timestamp
		$text = '[' . date('m/d/Y g:i A') . '] - ';

		// Success or failure being logged?
		if ($success)
		{
			$text .= "SUCCESS!\n";
		}
		else
		{
			$text .= 'FAIL: ' . $this->last_error . "\n";
		}

		// Log the POST variables
		$text .= "IPN POST Vars from Paypal:\n";

		foreach ($this->ipn_data as $key => $value)
		{
			$text .= "$key=$value, ";
		}

		// Log the response from the paypal server
		$text .= "\nIPN Response from Paypal Server:\n " . $this->ipn_response;

		// Write to log
		$fp = fopen($this->ipn_log_file, 'a');
		fwrite($fp, $text . "\n\n");
		fclose($fp);

		// Close file
	}
}
