<?php
/**------------------------------------------------------------------------
 * com_vikbooking - VikBooking
* ------------------------------------------------------------------------
* author    Alessio Gaggii - e4j - Extensionsforjoomla.com
* copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
* @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* Websites: http://www.extensionsforjoomla.com
* Technical Support:  tech@extensionsforjoomla.com
* ------------------------------------------------------------------------
*/

defined('_JEXEC') OR die('Restricted Area');

class vboCurrencyConverter {
	
	private $from_currency;
	private $to_currency;
	private $prices;
	private $format;
	private $currencymap;
	private $apis_url;
	
	public function __construct($from, $to, $numbers, $format) {
		$this->from_currency = $from;
		$this->to_currency = $to;
		$this->prices = $numbers;
		$this->format = $format;
		$this->currencymap = array(
				'ALL' => array('symbol' => '76'),
				'AFN' => array('symbol' => '1547'),
				'ARS' => array('symbol' => '36'),
				'AWG' => array('symbol' => '402'),
				'AUD' => array('symbol' => '36'),
				'AZN' => array('symbol' => '1084'),
				'BSD' => array('symbol' => '36'),
				'BBD' => array('symbol' => '36'),
				'BYR' => array('symbol' => '112', 'decimals' => 0),
				'BZD' => array('symbol' => '66'),
				'BMD' => array('symbol' => '36'),
				'BOB' => array('symbol' => '36'),
				'BAM' => array('symbol' => '75'),
				'BWP' => array('symbol' => '80'),
				'BGN' => array('symbol' => '1083'),
				'BRL' => array('symbol' => '82'),
				'BND' => array('symbol' => '36'),
				'KHR' => array('symbol' => '6107'),
				'CAD' => array('symbol' => '36'),
				'KYD' => array('symbol' => '36'),
				'CLP' => array('symbol' => '36', 'decimals' => 0),
				'CNY' => array('symbol' => '165'),
				'COP' => array('symbol' => '36'),
				'CRC' => array('symbol' => '8353'),
				'HRK' => array('symbol' => '107'),
				'CUP' => array('symbol' => '8369'),
				'CZK' => array('symbol' => '75'),
				'DKK' => array('symbol' => '107'),
				'DOP' => array('symbol' => '82'),
				'XCD' => array('symbol' => '36'),
				'EGP' => array('symbol' => '163'),
				'SVC' => array('symbol' => '36'),
				'EEK' => array('symbol' => '107'),
				'EUR' => array('symbol' => '8364'),
				'FKP' => array('symbol' => '163'),
				'FJD' => array('symbol' => '36'),
				'GHC' => array('symbol' => '162'),
				'GIP' => array('symbol' => '163'),
				'GTQ' => array('symbol' => '81'),
				'GGP' => array('symbol' => '163'),
				'GYD' => array('symbol' => '36'),
				'HNL' => array('symbol' => '76'),
				'HKD' => array('symbol' => '36'),
				'HUF' => array('symbol' => '70', 'decimals' => 0),
				'ISK' => array('symbol' => '107', 'decimals' => 0),
				'IDR' => array('symbol' => '82'),
				'INR' => array('symbol' => '8377'),
				'IRR' => array('symbol' => '65020'),
				'IMP' => array('symbol' => '163'),
				'ILS' => array('symbol' => '8362'),
				'JMD' => array('symbol' => '74'),
				'JPY' => array('symbol' => '165', 'decimals' => 0),
				'JEP' => array('symbol' => '163'),
				'KZT' => array('symbol' => '1083'),
				'KPW' => array('symbol' => '8361'),
				'KRW' => array('symbol' => '8361', 'decimals' => 0),
				'KGS' => array('symbol' => '1083'),
				'LAK' => array('symbol' => '8365'),
				'LVL' => array('symbol' => '76'),
				'LBP' => array('symbol' => '163'),
				'LRD' => array('symbol' => '36'),
				'LTL' => array('symbol' => '76'),
				'MKD' => array('symbol' => '1076'),
				'MYR' => array('symbol' => '82'),
				'MUR' => array('symbol' => '8360'),
				'MXN' => array('symbol' => '36'),
				'MNT' => array('symbol' => '8366'),
				'MZN' => array('symbol' => '77', 'decimals' => 0),
				'NAD' => array('symbol' => '36'),
				'NPR' => array('symbol' => '8360'),
				'ANG' => array('symbol' => '402'),
				'NZD' => array('symbol' => '36'),
				'NIO' => array('symbol' => '67'),
				'NGN' => array('symbol' => '8358'),
				'NOK' => array('symbol' => '107'),
				'OMR' => array('symbol' => '65020', 'decimals' => 3),
				'PKR' => array('symbol' => '8360'),
				'PAB' => array('symbol' => '66'),
				'PYG' => array('symbol' => '71', 'decimals' => 0),
				'PEN' => array('symbol' => '83'),
				'PHP' => array('symbol' => '8369'),
				'PLN' => array('symbol' => '122'),
				'QAR' => array('symbol' => '65020'),
				'RON' => array('symbol' => '108'),
				'RUB' => array('symbol' => '1088'),
				'SHP' => array('symbol' => '163'),
				'SAR' => array('symbol' => '65020'),
				'RSD' => array('symbol' => '1044'),
				'SCR' => array('symbol' => '8360'),
				'SGD' => array('symbol' => '36'),
				'SBD' => array('symbol' => '36'),
				'SOS' => array('symbol' => '83'),
				'ZAR' => array('symbol' => '82'),
				'LKR' => array('symbol' => '8360'),
				'SEK' => array('symbol' => '107'),
				'CHF' => array('symbol' => '67'),
				'SRD' => array('symbol' => '36'),
				'SYP' => array('symbol' => '163'),
				'TWD' => array('symbol' => '78'),
				'THB' => array('symbol' => '3647'),
				'TTD' => array('symbol' => '84'),
				'TRY' => array('symbol' => '8378', 'decimals' => 0),
				'UAH' => array('symbol' => '8372'),
				'GBP' => array('symbol' => '163'),
				'USD' => array('symbol' => '36'),
				'UYU' => array('symbol' => '36'),
				'UZS' => array('symbol' => '1083'),
				'VEF' => array('symbol' => '66'),
				'VND' => array('symbol' => '8363'),
				'YER' => array('symbol' => '65020'),
				'ZWD' => array('symbol' => '90')
		);
		$this->apis_url = 'http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s='. $this->from_currency . $this->to_currency .'=X';
	}
	
	public function getConversionRate() {
		$session = JFactory::getSession();
		$ses_conversions = $session->get('vboCurrencyConversions', '');
		$conversions_made = array();
		$data = '';
		$conv_rate = false;
		if (!empty($ses_conversions) && @is_array($ses_conversions) && @count($ses_conversions) > 0) {
			$conversions_made = $ses_conversions;
			if (array_key_exists($this->from_currency.'_'.$this->to_currency, $ses_conversions)) {
				if (strlen($ses_conversions[$this->from_currency.'_'.$this->to_currency]) > 0 && floatval($ses_conversions[$this->from_currency.'_'.$this->to_currency]) > 0.00) {
					$conv_rate = $ses_conversions[$this->from_currency.'_'.$this->to_currency];
				}
			}
		}
		if($conv_rate === false) {
			//http://finance.yahoo.com/currency-converter
			$fp = @fopen($this->apis_url, 'r');
			if ($fp) {
				while (!feof($fp)) {
					$data .= fread($fp, 4096);
				}
				if (!empty($data)) {
					$data = str_replace("\"", "", $data);
					$rate_info = explode(',', $data);
					if (strlen($rate_info[1]) > 0 && floatval($rate_info[1]) > 0.00) {
						$conv_rate = (float)$rate_info[1];
						$conversions_made[$this->from_currency.'_'.$this->to_currency] = $conv_rate;
						$session->set('vboCurrencyConversions', $conversions_made);
					}
				}
			}
		}
		
		return $conv_rate;
	}
	
	private function makeFloat($num) {
		$floated = $num;
		if (@is_array($this->format) && @count($this->format) == 3) {
			$decimals = '';
			if (strstr($num, $this->format[1]) !== false) {
				$decimals = substr($num, ((int)$this->format[0] - ((int)$this->format[0] * 2)));
			}
			$nosep = str_replace($this->format[1], '', $num);
			$nosep = str_replace($this->format[2], '', $nosep);
			$newdecimals = '';
			if ((int)$this->format[0] > 0 && !empty($decimals)) {
				$nosep = substr_replace($nosep, '', (strlen($decimals) - (strlen($decimals) * 2)));
				$decimalsabs = abs($decimals);
				if ($decimalsabs > 0) {
					$newdecimals = $decimals;
				}
			}
			$floated = floatval($nosep.(!empty($newdecimals) ? '.'.$newdecimals : ''));
		}
		return $floated;
	}
	
	private function currencySymbol() {
		if (array_key_exists($this->to_currency, $this->currencymap)) {
			$symbol = '&#'.$this->currencymap[$this->to_currency]['symbol'].';';	
		}else {
			$symbol = $this->to_currency;
		}
		return $symbol;
	}
	
	private function currencyFormat($num) {
		$num_decimals = (int)$this->format[0];
		if (array_key_exists($this->to_currency, $this->currencymap)) {
			if (array_key_exists('decimals', $this->currencymap[$this->to_currency])) {
				$num_decimals = $this->currencymap[$this->to_currency]['decimals'];
			}else {
				$num_decimals = 2;
			}
		}
		return number_format($num, $num_decimals, $this->format[1], $this->format[2]);
	}
	
	public function convert() {
		$conversion = array();
		if (empty($this->prices) || count($this->prices) == 0) {
			return $conversion;
		}
		
		$conv_rate = $this->getConversionRate();
		
		if ($conv_rate !== false) {
			$conv_symbol = $this->currencySymbol();
			foreach($this->prices as $k => $price) {
				$exchanged = $this->makeFloat($price) * $conv_rate;
				$conversion[$k]['symbol'] = $conv_symbol;
				$conversion[$k]['price'] = $this->currencyFormat($exchanged);
			}
		}
		
		return $conversion;
		
	}
	
}

?>