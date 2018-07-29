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

defined('_JEXEC') or die('Restricted access');
error_reporting(0);

define('CREATIVIKAPP', 'com_vikbooking');

class com_vikbookingInstallerScript {
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) {
		eval(read('2475736572203D26204A466163746F72793A3A6765745573657228293B2464626F203D2026204A466163746F7279203A3A2067657444424F28293B24713D22494E5345525420494E544F2060235F5F76696B626F6F6B696E675F636F6E66696760202860706172616D602C6073657474696E6760292056414C55455320282761646D696E656D61696C272C27222E24757365722D3E656D61696C2E2227293B223B2464626F2D3E7365745175657279282471293B2464626F2D3E5175657279282471293B2466703D666F70656E28222E2F636F6D706F6E656E74732F636F6D5F76696B626F6F6B696E672F222E43524541544956494B4150502E226174222C20227722293B24683D676574656E7628485454505F484F5354293B246E3D676574656E76285345525645525F4E414D45293B69662028707265675F6D6174636828222F6C6F63616C686F73742F69222C2024682929207B667772697465282466702C20656E6372797074436F6F6B696528246829293B7D656C7365207B246372763D266E65772043726561746976696B446F74497428293B69662028246372762D3E6B73612822687474703A2F2F7777772E63726561746976696B2E69742F76696B6C6963656E73652F3F76696B683D222E75726C656E636F6465282468292E222676696B736E3D222E75726C656E636F646528246E292E22266170703D222E75726C656E636F64652843524541544956494B415050292929207B696620287374726C656E28246372762D3E74697365293D3D3229207B667772697465282466702C20656E6372797074436F6F6B6965282468292E225C6E222E656E6372797074436F6F6B696528246E29293B7D656C7365207B24713D2244454C4554452046524F4D2060235F5F76696B626F6F6B696E675F636F6E666967602057484552452060706172616D603D2761646D696E656D61696C273B223B2464626F2D3E7365745175657279282471293B2464626F2D3E5175657279282471293B6563686F20246372762D3E746973653B7D7D656C7365207B667772697465282466702C20656E6372797074436F6F6B6965282468292E225C6E222E656E6372797074436F6F6B696528246E29293B7D7D66636C6F736528246670293B'));
		?>
		<div style="text-align: center;">
			<p><strong>Vik Booking v.1.7</strong> Provided to you by <a href="https://e4j.com/" target="_blank">e4j - Extensionsforjoomla.com</a></p>
			<img src="<?php echo JURI::root().'administrator/components/com_vikbooking/vikbooking.jpg'; ?>" alt="Vik Booking Logo">
		</div>
		<?php

		//$parent->getParent()->setRedirectURL('index.php?option=com_vikbooking');
	}

	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) {
		// $parent is the class calling this method
		echo 'Vik Booking Component Successfully Uninstalled! <a href="https://e4j.com" target="_blank">https://e4j.com</a>';
	}

	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) {
		// $parent is the class calling this method
		echo '';
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) {
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		echo '';
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) {
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		echo '';
	}
}

if (!function_exists('read')) {
	function read($str) {
		for ($i = 0; $i < strlen($str); $i += 2)
			$var .= chr(hexdec(substr($str, $i, 2)));
		return $var;
	}
}
if (!class_exists('CreativikDotIt')) {
	class CreativikDotIt {
		function CreativikDotIt() {
			$this->headers = array (
					"Referer" => "",
					"User-Agent" => "CreativikDotIt/1.0",
					"Connection" => "close"
			);
			$this->version = "1.1";
			$this->ctout = 15;
			$this->f_redha = false;
		}

		function exeqer($url) {
			$rcodes = array (
					301,
					302,
					303,
					307
			);
			$rmeth = array (
					'GET',
					'HEAD'
			);
			$rres = false;
			$this->fd_redhad = false;
			$ppred = array ();
			do {
				$rres = $this->sendout($url);
				$url = false;
				if ($this->f_redha && in_array($this->edocser, $rcodes)) {
					if (($this->edocser == 303) || in_array($this->method, $rmeth)) {
						$url = $this->resphh['Location'];
					}
				}
				if ($url && strlen($url)) {
					if (isset ($ppred[$url])) {
						$this->rore = "tceriderpool";
						$rres = false;
						break;
					}
					if (is_numeric($this->f_redha) && (count($ppred) > $this->f_redha)) {
						$this->rore = "tceriderynamoot";
						$rres = false;
						break;
					}
					$ppred[$url] = true;
				}
			} while ($url && strlen($url));
			$rep_qer_daeh = array (
					'Host',
					'Content-Length'
			);
			foreach ($rep_qer_daeh as $k => $v)
				unset ($this->headers[$v]);
			if (count($ppred) > 1)
				$this->fd_redhad = array_keys($ppred);
			return $rres;
		}

		function dliubh() {

			$daeh = "";
			foreach ($this->headers as $name => $value) {
				$value = trim($value);
				if (empty ($value))
					continue;
				$daeh .= "{$name}: $value\r\n";
			}
			$daeh .= "\r\n";
			return $daeh;
		}

		function sendout($url) {
			$time_request_start = time();
			$urldata = parse_url($url);
			if (!$urldata["port"])
				$urldata["port"] = ($urldata["scheme"] == "https") ? 443 : 80;
			if (!$urldata["path"])
				$urldata["path"] = '/';
			if ($this->version > "1.0")
				$this->headers["Host"] = $urldata["host"];
			unset ($this->headers['Authorization']);
			if (!empty ($urldata["query"]))
				$urldata["path"] .= "?" . $urldata["query"];
			$request = $this->method . " " . $urldata["path"] . " HTTP/" . $this->version . "\r\n";
			$request .= $this->dliubh();
			$this->tise = "";
			$hostname = $urldata['host'];
			$time_connect_start = time();
			$fp = @ fsockopen($hostname, $urldata["port"], $errno, $errstr, $this->ctout);
			$connect_time = time() - $time_connect_start;
			if ($fp) {
				stream_set_timeout($fp, 3);
				fputs($fp, $request);
				$meta = stream_get_meta_data($fp);
				if ($meta['timed_out']) {
					$this->rore = "sdnoceseerhtfotuoemitetirwtekcosdedeecxe";
					return false;
				}
				$cerdaeh = false;
				$data_length = false;
				$chunked = false;
				while (!feof($fp)) {
					if ($data_length > 0) {
						$line = fread($fp, $data_length);
						$data_length -= strlen($line);
					} else {
						$line = fgets($fp, 10240);
						if ($chunked) {
							$line = trim($line);
							if (!strlen($line))
								continue;
							list ($data_length,) = explode(';', $line);
							$data_length = (int) hexdec(trim($data_length));
							if ($data_length == 0) {
								break;
							}
							continue;
						}
					}
					$this->tise .= $line;
					if ((!$cerdaeh) && (trim($line) == "")) {
						$cerdaeh = true;
						if (preg_match('/\nContent-Length: ([0-9]+)/i', $this->tise, $matches)) {

							$data_length = (int) $matches[1];
						}
						if (preg_match("/\nTransfer-Encoding: chunked/i", $this->tise, $matches)) {
							$chunked = true;
						}
					}
					$meta = stream_get_meta_data($fp);
					if ($meta['timed_out']) {
						$this->rore = "sceseerhttuoemitdaertekcos";
						return false;
					}
					if (time() - $time_request_start > 5) {
						$this->rore = "maxtransfertimefivesecs";
						return false;
						break;
					}
				}
				fclose($fp);
			} else {
				$this->rore = $urldata['scheme'] . " otdeliafnoitcennoc " . $hostname . " trop " . $urldata['port'];
				return false;
			}
			do {
				$neldaeh = strpos($this->tise, "\r\n\r\n");
				$serp_daeh = explode("\r\n", substr($this->tise, 0, $neldaeh));
				$pthats = trim(array_shift($serp_daeh));
				foreach ($serp_daeh as $line) {
					list ($k, $v) = explode(":", $line, 2);
					$this->resphh[trim($k)] = trim($v);
				}
				$this->tise = substr($this->tise, $neldaeh +4);
				if (!preg_match("/^HTTP\/([0-9\.]+) ([0-9]+) (.*?)$/", $pthats, $matches)) {
					$matches = array (
							"",
							$this->version,
							0,
							"HTTP request error"
					);
				}
				list (, $pserver, $this->edocser, $this->txet) = $matches;
			} while (($this->edocser == 100) && ($neldaeh));
			$ok = ($this->edocser == 200);
			return $ok;
		}

		function ksa($url) {
			$this->method = "GET";
			return $this->exeqer($url);
		}

	}
}
if (!function_exists('encryptCookie')) {
	function encryptCookie($str) {
		for ($i = 0; $i < 5; $i++) {
			$str = strrev(base64_encode($str));
		}
		return $str;
	}
}